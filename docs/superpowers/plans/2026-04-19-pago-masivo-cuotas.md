# Pago Masivo de Cuotas Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Agregar funcionalidad de pago masivo de cuotas en el tab contable de detalle estudiante - un pago se distribuye automáticamente entre múltiples cuotas pendientes.

**Architecture:** El frontend muestra las cuotas agrupadas por concepto con selección visual automática. El backend calcula la distribución real y crea un registro de pago con múltiples registros en pagos_cuota.

**Tech Stack:** Laravel Blade, PHP, JavaScript (Fetch API)

---

## Archivos a Modificar

| Archivo | Acción |
|---------|--------|
| `routes/web.php` | Agregar ruta para endpoint |
| `app/Http/Controllers/Admin/EstudianteController.php` | Agregar método pagarMasivo |
| `resources/views/admin/estudiantes/detalle.blade.php` | Agregar botón y modal |

---

## Task 1: Agregar Ruta del Endpoint

**Files:**
- Modify: `routes/web.php:267`

- [ ] **Step 1: Agregar ruta**

Después de la línea 267 (`Route::post('/admin/estudiantes/cuota/{cuota}/pagar'...`), agregar:

```php
Route::post('/cuotas/pago-masivo', [EstudianteController::class, 'pagoMasivo'])
    ->name('registrarPagoMasivo');
```

Run: Verificar con `php artisan route:list | grep pago-masivo`

---

## Task 2: Agregar Método en Controlador

**Files:**
- Modify: `app/Http/Controllers/Admin/EstudianteController.php`

- [ ] **Step 1: Agregar método pagoMasivo**

Después del método `registrarPago` (línea 345), agregar:

```php
public function pagoMasivo(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'estudiante_id' => 'required|exists:estudiantes,id',
            'inscripcion_id' => 'required|exists:inscripciones,id',
            'monto' => 'required|numeric|min:0.01',
            'descuento' => 'nullable|numeric|min:0',
            'metodo' => 'required|in:Efectivo,Qr,Parcial',
            'trabajador_cargo_id' => 'required|exists:trabajadores_cargos,id',
            'fecha_pago' => 'required|date|before_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $estudianteId = $request->estudiante_id;
        $inscripcionId = $request->inscripcion_id;
        $monto = $request->monto;
        $descuento = $request->descuento ?? 0;
        $metodo = $request->metodo;
        $efectivo = $request->efectivo ?? 0;
        $qr = $request->qr ?? 0;
        $trabajadorCargoId = $request->trabajador_cargo_id;
        $fechaPago = $request->fecha_pago;

        // Validar que la inscripción pertenezca al estudiante
        $inscripcion = Inscripcione::where('id', $inscripcionId)
            ->where('estudiante_id', $estudianteId)
            ->first();

        if (!$inscripcion) {
            return response()->json(['success' => false, 'message' => 'La inscripción no pertenece al estudiante.'], 404);
        }

        // Obtener cuotas pendientes ordenadas por concepto y n_cuota
        $cuotas = Cuota::where('inscripcione_id', $inscripcionId)
            ->whereIn('estado', ['Pendiente', 'Vencido', 'Parcial'])
            ->orderBy('nombre')
            ->orderBy('n_cuota')
            ->get();

        if ($cuotas->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No hay cuotas pendientes.'], 400);
        }

        // Calcular deuda total
        $deudaTotal = $cuotas->sum(function ($c) {
            return $c->pago_pendiente_bs ?? $c->monto_bs;
        });

        $montoDisponible = $monto + $descuento;

        if ($montoDisponible > $deudaTotal) {
            return response()->json(['success' => false, 'message' => 'El monto excede la deuda total (Bs. ' . number_format($deudaTotal, 2) . ').'], 400);
        }

        // Agrupar por concepto (nombre) para procesar en orden
        $cuotasAgrupadas = $cuotas->groupBy('nombre');

        $pagosRealizados = [];
        $montoRestante = $montoDisponible;

        // Crear registro en tabla pagos (un solo pago para todas las cuotas)
        $pago = Pago::create([
            'trabajadore_cargo_id' => $trabajadorCargoId,
            'monto_total' => $monto,
            'descuento_bs' => $descuento,
            'tipo_pago' => $metodo,
            'fecha_pago' => $fechaPago,
            'estado' => 'Pagado',
        ]);

        // Crear detalles según método
        if ($metodo === 'Parcial') {
            if ($efectivo > 0) {
                Detalle::create([
                    'pago_id' => $pago->id,
                    'tipo_pago' => 'Efectivo',
                    'monto_bs' => $efectivo,
                ]);
            }
            if ($qr > 0) {
                Detalle::create([
                    'pago_id' => $pago->id,
                    'tipo_pago' => 'Qr',
                    'monto_bs' => $qr,
                ]);
            }
        } else {
            Detalle::create([
                'pago_id' => $pago->id,
                'tipo_pago' => $metodo,
                'monto_bs' => $monto,
            ]);
        }

        // Procesar cuotas en orden: por nombre (concepto) y luego n_cuota
        foreach ($cuotasAgrupadas as $nombre => $grupoCuotas) {
            foreach ($grupoCuotas as $cuota) {
                if ($montoRestante <= 0) break;

                $pendiente = $cuota->pago_pendiente_bs ?? $cuota->monto_bs;

                if ($pendiente <= 0) continue;

                // Determinar monto a pagar en esta cuota
                $montoACuota = min($pendiente, $montoRestante);

                // Crear registro en pagos_cuota
                PagosCuota::create([
                    'pago_id' => $pago->id,
                    'cuota_id' => $cuota->id,
                    'monto_bs' => $montoACuota,
                    'fecha_pago' => $fechaPago,
                ]);

                // Actualizar cuota
                $nuevoPendiente = $pendiente - $montoACuota;

                if ($nuevoPendiente <= 0) {
                    $cuota->update([
                        'estado' => 'Pagado',
                        'pago_pendiente_bs' => 0,
                        'fecha_pago' => $fechaPago,
                        'descuento_bs' => $descuento,
                    ]);
                } else {
                    $cuota->update([
                        'estado' => 'Parcial',
                        'pago_pendiente_bs' => $nuevoPendiente,
                        'fecha_pago' => $fechaPago,
                        'descuento_bs' => $descuento,
                    ]);
                }

                $pagosRealizados[] = [
                    'cuota_id' => $cuota->id,
                    'nombre' => $cuota->nombre,
                    'monto' => $montoACuota,
                    'tipo' => $nuevoPendiente <= 0 ? 'Completo' : 'Parcial',
                ];

                $montoRestante -= $montoACuota;
            }
        }

        // Calcular nueva deuda
        $nuevaDeuda = Cuota::where('inscripcione_id', $inscripcionId)
            ->whereIn('estado', ['Pendiente', 'Vencido', 'Parcial'])
            ->get()
            ->sum(function ($c) {
                return $c->pago_pendiente_bs ?? $c->monto_bs;
            });

        return response()->json([
            'success' => true,
            'message' => 'Pago registrado correctamente. Se pagaron ' . count($pagosRealizados) . ' cuota(s).',
            'data' => [
                'recibo' => $pago->recibo ?? 'PAG-' . date('Y') . '-' . str_pad($pago->id, 4, '0', 'STR_PAD_LEFT'),
                'pagos_cuota' => $pagosRealizados,
                'total_pagado' => $monto,
                'nueva_deuda' => $nuevaDeuda,
            ],
            'redirect' => "/admin/estudiantes/{$estudianteId}/detalle?tab=contable"
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}
```

---

## Task 3: Agregar UI en Blade (Botón y Modal)

**Files:**
- Modify: `resources/views/admin/estudiantes/detalle.blade.php:1017-1030` (sección de cuotas)

- [ ] **Step 1: Agregar botón "Registro Masivo" después del título de cuotas**

Buscar línea ~1017 donde dice `<i class="ri-install-line"></i> Cuotas` y agregar después del cierre del div de título:

```html
<button type="button" class="btn btn-sm btn-action btn-action-edit btn-pago-masivo"
    data-inscripcion-id="{{ $ins->id }}"
    data-oferta="{{ $ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta #' . $ins->ofertas_academica_id }}"
    title="Registro Masivo">
    <i class="ri-file-list-3-line"></i> Registro Masivo
</button>
```

- [ ] **Step 2: Agregar Modal de Pago Masivo** (después del modal de pago individual, línea ~1248)

```html
<!-- Modal Pago Masivo -->
<div class="modal fade" id="modalPagoMasivo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title"><i class="ri-file-list-3-line"></i> Registro Masivo de Cuotas</h5>
                    <small class="text-muted d-block" id="pago-masivo-oferta"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPagoMasivo">
                <div class="modal-body">
                    <!-- Sección de pago -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-money-dollar-line"></i> Monto a Pagar (Bs.)</label>
                            <input type="number" class="form-control" id="pago-masivo-monto" name="monto" step="0.01" min="0.01" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-discount-line"></i> Descuento (Bs.)</label>
                            <input type="number" class="form-control" id="pago-masivo-descuento" name="descuento" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-calendar-line"></i> Fecha de Pago</label>
                            <input type="date" class="form-control" id="pago-masivo-fecha" name="fecha_pago" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-bank-card-line"></i> Método de Pago</label>
                            <select class="form-select" id="pago-masivo-metodo" name="metodo" required>
                                <option value="">Seleccionar...</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Qr">QR</option>
                                <option value="Parcial">Parcial (Efectivo + QR)</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="pago-masivo-campo-efectivo" style="display:none;">
                            <label class="form-label"><i class="ri-money-dollar-line"></i> Efectivo (Bs.)</label>
                            <input type="number" class="form-control" id="pago-masivo-efectivo" name="efectivo" step="0.01" min="0">
                        </div>
                        <div class="col-md-6" id="pago-masivo-campo-qr" style="display:none;">
                            <label class="form-label"><i class="ri-qr-code-line"></i> QR (Bs.)</label>
                            <input type="number" class="form-control" id="pago-masivo-qr" name="qr" step="0.01" min="0">
                        </div>
                    </div>

                    <!-- Lista de cuotas -->
                    <div id="pago-masivo-lista-cuotas" class="mb-4" style="max-height: 300px; overflow-y: auto;">
                        <!-- Se填充a dinamicamente -->
                    </div>

                    <!-- Resumen -->
                    <div class="alert alert-info">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="text-muted small">Total Deuda</div>
                                <div class="fw-bold" id="pago-masivo-deuda-total">—</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Monto Ingresado</div>
                                <div class="fw-bold text-primary" id="pago-masivo-monto-ingresado">—</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Nueva Deuda</div>
                                <div class="fw-bold text-success" id="pago-masivo-nueva-deuda">—</div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="pago-masivo-estudiante-id" name="estudiante_id">
                    <input type="hidden" id="pago-masivo-inscripcion-id" name="inscripcion_id">
                    <input type="hidden" id="pago-masivo-trabajador-cargo" name="trabajador_cargo_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i class="ri-close-line"></i> Cancelar</button>
                    <button type="submit" class="btn btn-modal-submit"><i class="ri-save-line"></i> Registrar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>
```

---

## Task 4: Agregar JavaScript para Pago Masivo

**Files:**
- Modify: `resources/views/admin/estudiantes/detalle.blade.php` (al final, antes de `</script>`)

- [ ] **Step 1: Agregar JavaScript** (antes de `</script>` en línea ~1727)

```javascript
// ===== PAGO MASIVO DE CUOTAS =====

let modalPagoMasivoInstance = null;
let cuotasData = [];
let deudaTotalGlobal = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar fecha
    document.getElementById('pago-masivo-fecha').value = new Date().toISOString().split('T')[0];

    // Botón abrir modal
    document.querySelectorAll('.btn-pago-masivo').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const inscripcionId = this.getAttribute('data-inscripcion-id');
            const ofertaNombre = this.getAttribute('data-oferta');
            
            abrirModalPagoMasivo(inscripcionId, ofertaNombre);
        });
    });

    // Cambio método de pago
    document.getElementById('pago-masivo-metodo').addEventListener('change', function() {
        const campoEfectivo = document.getElementById('pago-masivo-campo-efectivo');
        const campoQr = document.getElementById('pago-masivo-campo-qr');
        
        if (this.value === 'Parcial') {
            campoEfectivo.style.display = 'block';
            campoQr.style.display = 'block';
        } else {
            campoEfectivo.style.display = 'none';
            campoQr.style.display = 'none';
        }
    });

    // Calcular automático en efectivo cuando ingresa QR
    document.getElementById('pago-masivo-qr').addEventListener('input', function() {
        const monto = parseFloat(document.getElementById('pago-masivo-monto').value) || 0;
        const qr = parseFloat(this.value) || 0;
        const efectivoCampo = document.getElementById('pago-masivo-efectivo');
        const restante = monto - qr;
        if (efectivoCampo) efectivoCampo.value = Math.max(0, restante).toFixed(2);
        actualizarResumenPagoMasivo();
    });

    // Calcular automático en QR cuando ingresa efectivo
    document.getElementById('pago-masivo-efectivo').addEventListener('input', function() {
        const monto = parseFloat(document.getElementById('pago-masivo-monto').value) || 0;
        const efectivo = parseFloat(this.value) || 0;
        const qrCampo = document.getElementById('pago-masivo-qr');
        const restante = monto - efectivo;
        if (qrCampo) qrCampo.value = Math.max(0, restante).toFixed(2);
        actualizarResumenPagoMasivo();
    });

    // Actualizar resumen al cambiar monto
    document.getElementById('pago-masivo-monto').addEventListener('input', actualizarResumenPagoMasivo);
    document.getElementById('pago-masivo-descuento').addEventListener('input', actualizarResumenPagoMasivo);

    // Submit formulario
    document.getElementById('formPagoMasivo').addEventListener('submit', function(e) {
        e.preventDefault();
        enviarPagoMasivo();
    });
});

function abrirModalPagoMasivo(inscripcionId, ofertaNombre) {
    // Obtener datos del estudiante desde la página
    const estudianteId = '{{ $estudiante->id }}';
    const trabajadorCargoId = document.getElementById('pago-trabajador-cargo').value;

    document.getElementById('pago-masivo-estudiante-id').value = estudianteId;
    document.getElementById('pago-masivo-inscripcion-id').value = inscripcionId;
    document.getElementById('pago-masivo-trabajador-cargo').value = trabajadorCargoId;
    document.getElementById('pago-masivo-oferta').textContent = ofertaNombre;

    // Resetear campos
    document.getElementById('pago-masivo-monto').value = '';
    document.getElementById('pago-masivo-descuento').value = '0';
    document.getElementById('pago-masivo-fecha').value = new Date().toISOString().split('T')[0];
    document.getElementById('pago-masivo-metodo').value = '';
    document.getElementById('pago-masivo-efectivo').value = '';
    document.getElementById('pago-masivo-qr').value = '';
    document.getElementById('pago-masivo-campo-efectivo').style.display = 'none';
    document.getElementById('pago-masivo-campo-qr').style.display = 'none';

    // Cargar cuotas de la inscripción
    cargarCuotasParaPagoMasivo(inscripcionId);

    // Mostrar modal
    if (!modalPagoMasivoInstance) {
        modalPagoMasivoInstance = new bootstrap.Modal(document.getElementById('modalPagoMasivo'));
    }
    modalPagoMasivoInstance.show();
}

function cargarCuotasParaPagoMasivo(inscripcionId) {
    // Obtener cuotas del elemento DOM actual
    const contenido = document.querySelector('#contable-oferta-0');
    if (!contenido) return;

    const listaCuotas = document.getElementById('pago-masivo-lista-cuotas');
    const filas = contenido.querySelectorAll('tbody tr');

    cuotaData = [];
    let deudaTotal = 0;

    lethtml = '<h6 class="text-muted mb-3"><i class="ri-install-line"></i> Cuotas Pendientes</h6>';
    html += '<div class="accordion" id="accordionCuotas">';

    // Agrupar por concepto
    const grupos = {};

    filas.forEach(function(fila) {
        const estado = fila.querySelector('.estado-badge-est');
        if (!estado || estado.classList.contains('pagado')) return;

        const cells = fila.querySelectorAll('td');
        const nCuota = cells[0].textContent.trim();
        const nombre = cells[1].textContent.trim();
        const monto = cells[2].textContent.replace('Bs. ', '').replace(/,/g, '');
        const pendiente = cells[4].textContent.replace('Bs. ', '').replace(/,/g, '');
        const vencimiento = cells[5].textContent.trim();

        // Extraer concepto (todo antes de " - ")
        const concepto = nombre.includes(' - ') ? nombre.split(' - ')[0].trim() : nombre;

        if (!grupos[concepto]) {
            grupos[concepto] = [];
        }

        grupos[concepto].push({
            n_cuota: nCuota,
            nombre: nombre,
            monto: parseFloat(monto),
            pendiente: parseFloat(pendiente),
            vencimiento: vencimiento
        });

        deudaTotal += parseFloat(pendiente);
    });

    // Renderizar grupos
    let index = 0;
    for (const [concepto, cuotas] of Object.entries(grupos)) {
        const primerasPendientes = cuotas.filter(c => c.pendiente > 0);
        if (primerasPendientes.length === 0) continue;

        html += '<div class="accordion-item">';
        html += '<h2 class="accordion-header">';
        html += '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-' + index + '">';
        html += concepto + ' (' + primerasPendientes.length + ' cuota(s) pendiente(s))';
        html += '</button></h2>';
        html += '<div id="collapse-' + index + '" class="accordion-collapse collapse" data-bs-parent="#accordionCuotas">';
        html += '<div class="accordion-body p-0">';
        html += '<table class="table table-sm table-hover mb-0"><thead><tr><th>#</th><th>Cuota</th><th>Monto</th><th>Pendiente</th><th>Vencimiento</th><th>Estado</th></tr></thead><tbody>';

        primerasPendientes.forEach(function(cuota, idx) {
            html += '<tr class="cuota-seleccionada" data-cuota="' + cuota.n_cuota + '">';
            html += '<td>' + cuota.n_cuota + '</td>';
            html += '<td>' + cuota.nombre + '</td>';
            html += '<td>Bs. ' + cuota.monto.toFixed(2) + '</td>';
            html += '<td class="text-warning fw-bold">Bs. ' + cuota.pendiente.toFixed(2) + '</td>';
            html += '<td>' + cuota.vencimiento + '</td>';
            html += '<td><span class="badge bg-warning text-dark">Pendiente</span></td>';
            html += '</tr>';
        });

        html += '</tbody></table></div></div></div>';
        index++;
    }

    html += '</div>';

    if (Object.keys(grupos).length === 0) {
        html += '<div class="text-center text-muted py-4">No hay cuotas pendientes</div>';
    }

    listaCuotas.innerHTML = html;
    deudaTotalGlobal = deudaTotal;
    document.getElementById('pago-masivo-deuda-total').textContent = 'Bs. ' + deudaTotal.toFixed(2);
    actualizarResumenPagoMasivo();
}

function actualizarResumenPagoMasivo() {
    const monto = parseFloat(document.getElementById('pago-masivo-monto').value) || 0;
    const descuento = parseFloat(document.getElementById('pago-masivo-descuento').value) || 0;
    const metodo = document.getElementById('pago-masivo-metodo').value;

    let montoIngresado = monto;
    if (metodo === 'Parcial') {
        montoIngresado = (parseFloat(document.getElementById('pago-masivo-efectivo').value) || 0) + 
                       (parseFloat(document.getElementById('pago-masivo-qr').value) || 0);
    }

    const totalIngresado = montoIngresado + descuento;
    const nuevaDeuda = Math.max(0, deudaTotalGlobal - totalIngresado);

    document.getElementById('pago-masivo-monto-ingresado').textContent = 'Bs. ' + totalIngresado.toFixed(2);
    document.getElementById('pago-masivo-nueva-deuda').textContent = 'Bs. ' + nuevaDeuda.toFixed(2);

    // Validar y deshabilitar botón si es inválido
    const btnSubmit = document.querySelector('#formPagoMasivo button[type="submit"]');
    if (btnSubmit) {
        const valido = monto > 0 && metodo && totalIngresado <= deudaTotalGlobal + 0.01;
        btnSubmit.disabled = !valido;
    }
}

function enviarPagoMasivo() {
    const btnSubmit = document.querySelector('#formPagoMasivo button[type="submit"]');
    const originalText = btnSubmit.innerHTML;
    
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Procesando...';

    const formData = {
        estudiante_id: document.getElementById('pago-masivo-estudiante-id').value,
        inscripcion_id: document.getElementById('pago-masivo-inscripcion-id').value,
        monto: parseFloat(document.getElementById('pago-masivo-monto').value),
        descuento: parseFloat(document.getElementById('pago-masivo-descuento').value) || 0,
        metodo: document.getElementById('pago-masivo-metodo').value,
        efectivo: parseFloat(document.getElementById('pago-masivo-efectivo').value) || 0,
        qr: parseFloat(document.getElementById('pago-masivo-qr').value) || 0,
        trabajador_cargo_id: document.getElementById('pago-masivo-trabajador-cargo').value,
        fecha_pago: document.getElementById('pago-masivo-fecha').value,
        _token: '{{ csrf_token() }}'
    };

    fetch('/admin/estudiantes/cuotas/pago-masivo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(formData)
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        }
        throw new Error('Respuesta no válida del servidor');
    })
    .then(data => {
        if (data.success) {
            if (modalPagoMasivoInstance) {
                modalPagoMasivoInstance.hide();
            }
            if (typeof toast === 'function') {
                toast('success', data.message || 'Pago registrado correctamente.');
            } else {
                alert('Pago registrado correctamente.');
            }
            setTimeout(() => {
                const estudianteId = '{{ $estudiante->id }}';
                window.location.href = '/admin/estudiantes/' + estudianteId + '/detalle?tab=contable';
            }, 1500);
        } else {
            alert(data.message || 'Error al registrar el pago.');
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar el pago.');
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalText;
    });
}
```

---

## Self-Review

1. **Spec coverage:** 
   - [x] Ruta endpoint - Task 1
   - [x] Método controlador - Task 2  
   - [x] Botón en UI - Task 3 Step 1
   - [x] Modal estructura - Task 3 Step 2
   - [x] JavaScript funcionalidad - Task 4

2. **Placeholder scan:** No hay placeholders en el plan.

3. **Type consistency:** Los nombres de campos coinciden entre frontend (monto, descuento, metodo) y backend (REQUEST).

**Plan complete and saved to `docs/superpowers/plans/2026-04-19-pago-masivo-cuotas.md`**

---

## Execution Choice

**Which approach?**

1. **Subagent-Driven (recommended)** - I dispatch a fresh subagent per task, review between tasks, fast iteration
2. **Inline Execution** - Execute tasks in this session using executing-plans, batch execution with checkpoints