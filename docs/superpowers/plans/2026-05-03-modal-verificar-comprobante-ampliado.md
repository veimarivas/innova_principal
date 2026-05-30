# Modal Verificar Comprobante Ampliado - Plan de Implementación

> **Para agentes:** Usar superpowers:subagent-driven-development o superpowers:executing-plans para implementar tarea por tarea. Pasos usam checkbox (`- [ ]`) para seguimiento.

**Objetivo:** Ampliar el modal de verificación de comprobantes con dos columnas: lado izquierdo muestra el comprobante (imagen/PDF), lado derecho muestra cuotas agrupadas por concepto con distribución automática editable y tipo de pago "Parcial" con campos efectivo/QR.

**Arquitectura:** Modal Bootstrap con layout de dos columnas (45%/55%), estructura unchanged del backend pero con endpoints expandidos para nuevos datos, JavaScript para distribución automática y validaciones.

**Tech Stack:** Laravel 10, Blade templates, vanilla JS, Bootstrap 5

---

## Estructura de Archivos

- **Modify:** `app/Http/Controllers/Admin/ComprobantesPagoController.php` - Endpoints getCuotas y verificar expandidos
- **Modify:** `resources/views/admin/comprobantes/index.blade.php` - Rediseño del modal con dos columnas
- **Create:** `database/migrations/XXXX_XX_XX_extend_pagos_tipo_pago.php` - Nueva migración para enum

---

## Tarea 1: Migración para extender tipo_pago

**Files:**
- Create: `database/migrations/2026_05_03_000000_extend_pagos_tipo_pago.php`

- [ ] **Step 1: Crear migración**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cambiar tipo_pago de enum a string para mayor flexibilidad
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('tipo_pago', 20)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->enum('tipo_pago', ['Efectivo', 'Qr', 'Parcial'])->change();
        });
    }
};
```

- [ ] **Step 2: Ejecutar migración**

Run: `php artisan migrate`

---

## Tarea 2: Actualizar getCuotas en Controller

**Files:**
- Modify: `app/Http/Controllers/Admin/ComprobantesPagoController.php:48-79`

- [ ] **Step 1: Modificar método getCuotas para agregar campos seleccionada y concepto**

Reemplazar el método completo:

```php
public function getCuotas(int $id)
{
    $comprobante = PagoRespaldo::with([
        'cuotas',
        'inscripcion.estudiante.persona',
        'inscripcion.planesPago',
    ])->findOrFail($id);

    // Obtener IDs de cuotas asociadas al comprobante
    $cuotasComprobanteIds = $comprobante->cuotas->pluck('id')->toArray();

    // Obtener todas las cuotas de la inscripción del comprobante
    $inscripcion = $comprobante->inscripcion;
    $todasCuotas = $inscripcion ? Cuota::where('inscripcione_id', $inscripcion->id)->get() : collect();

    // Extraer concepto del nombre (ej: "Matrícula - Cuota 1" → "Matrícula")
    $extraerConcepto = function ($nombre) {
        $partes = explode(' - ', $nombre);
        return $partes[0] ?? $nombre;
    };

    $persona = $comprobante->inscripcion?->estudiante?->persona;
    $nombre  = trim(($persona?->nombres ?? '') . ' ' . ($persona?->apellido_paterno ?? '') . ' ' . ($persona?->apellido_materno ?? ''));

    return response()->json([
        'success'      => true,
        'comprobante'  => [
            'id'             => $comprobante->id,
            'archivo_url'    => asset('storage/comprobantes/' . $comprobante->archivo),
            'archivo_ext'    => strtolower(pathinfo($comprobante->archivo, PATHINFO_EXTENSION)),
            'observaciones'  => $comprobante->observaciones,
        ],
        'estudiante'   => $nombre,
        'plan_nombre'  => $comprobante->inscripcion?->planesPago?->nombre ?? 'Sin plan',
        'cuotas'       => $todasCuotas->map(function ($c) use ($cuotasComprobanteIds, $extraerConcepto) {
            return [
                'id'                => $c->id,
                'nombre'            => $c->nombre,
                'n_cuota'           => $c->n_cuota,
                'monto_bs'          => (float) $c->monto_bs,
                'pago_pendiente_bs' => (float) $c->pago_pendiente_bs,
                'estado'            => $c->estado,
                'seleccionada'      => in_array($c->id, $cuotasComprobanteIds),
                'concepto'          => $extraerConcepto($c->nombre),
            ];
        })->values(),
    ]);
}
```

- [ ] **Step 2: Probar endpoint**

Run: `php artisan route:list | grep comprobantes`
Verify: El endpoint `GET /admin/comprobantes/{id}/cuotas` responde con campos `seleccionada` y `concepto`

---

## Tarea 3: Rediseñar modal en Blade (dos columnas)

**Files:**
- Modify: `resources/views/admin/comprobantes/index.blade.php:226-305`

- [ ] **Step 1: Reemplazar estructura del modal completo**

Eliminar el modal existente (líneas 226-298) y reemplazar con:

```php
{{-- Modal Verificar Comprobante (dos columnas) --}}
<div class="modal fade" id="modalVerificar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="max-width:900px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 10px 40px rgba(0,0,0,.2);">
            <div class="modal-header" style="background:linear-gradient(135deg,#065f46,#059669);color:white;border-radius:12px 12px 0 0;padding:1rem 1.5rem;">
                <h5 class="modal-title" style="font-weight:600;font-size:1rem;">
                    <i class="ri-check-double-line me-2"></i>Verificar Comprobante
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:0;">
                <div class="row g-0" style="margin:0;">
                    {{-- LADO IZQUIERDO: Preview del comprobante --}}
                    <div class="col-md-5" style="background:#f8fafc;padding:1.25rem;border-right:1px solid #e2e8f0;min-height:450px;">
                        <h6 style="font-size:.8rem;font-weight:600;color:#475569;margin-bottom:1rem;">
                            <i class="ri-file-image-line me-1"></i>Comprobante
                        </h6>
                        <div id="vPreviewContainer" style="background:white;border-radius:8px;padding:.5rem;box-shadow:0 1px 3px rgba(0,0,0,.1);min-height:350px;">
                            <div id="modalVerificarLoading" class="text-center py-5">
                                <div class="spinner-border" style="color:#059669;" role="status"></div>
                                <p class="mt-2 mb-0 text-muted" style="font-size:.85rem;">Cargando...</p>
                            </div>
                            <div id="vPreviewContent" style="display:none;">
                                <div id="vImagePreview" style="display:none;text-align:center;">
                                    <img id="vPreviewImg" src="" alt="Comprobante" style="max-width:100%;max-height:380px;border-radius:4px;">
                                </div>
                                <div id="vPdfPreview" style="display:none;">
                                    <iframe id="vPreviewIframe" src="" style="width:100%;height:380px;border:none;border-radius:4px;"></iframe>
                                </div>
                            </div>
                        </div>
                        <div id="vObservaciones" style="margin-top:1rem;font-size:.8rem;color:#64748b;padding:.5rem;background:white;border-radius:6px;border:1px solid #e2e8f0;"></div>
                    </div>

                    {{-- LADO DERECHO: Formulario de registro --}}
                    <div class="col-md-7" style="padding:1.25rem;">
                        <div id="modalVerificarBodyRight" style="display:none;">
                            {{-- Info estudiante --}}
                            <div style="padding:.65rem 1rem;background:#f8fafc;border-radius:8px;border-left:4px solid #059669;margin-bottom:1rem;">
                                <div style="font-weight:600;color:#1e293b;font-size:.9rem;" id="vEstudianteNombre"></div>
                                <div style="font-size:.78rem;color:#64748b;margin-top:.2rem;" id="vPlanNombre"></div>
                            </div>

                            {{-- Tipo de pago --}}
                            <div style="margin-bottom:1rem;">
                                <label style="font-size:.8rem;font-weight:600;color:#475569;display:block;margin-bottom:.35rem;">
                                    Tipo de pago <span style="color:#dc2626;">*</span>
                                </label>
                                <select id="vTipoPago" style="border:1px solid #e2e8f0;border-radius:6px;padding:.42rem .75rem;font-size:.875rem;width:100%;background:#f8fafc;">
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Qr">QR</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Depósito">Depósito</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Parcial">Parcial (Efectivo + QR)</option>
                                </select>
                            </div>

                            {{-- Campos parcial (escondidos por defecto) --}}
                            <div id="vCamposParcial" style="display:none;margin-bottom:1rem;padding:.75rem;background:#fef3c7;border-radius:8px;border:1px solid #fcd34d;">
                                <div style="font-size:.78rem;font-weight:600;color:#92400e;margin-bottom:.5rem;">
                                    <i class="ri-money-dollar-circle-line me-1"></i>Detalle de pago mixto
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label style="font-size:.72rem;color:#78350f;display:block;">Monto en efectivo</label>
                                        <input type="number" id="vEfectivo" class="form-control form-control-sm" placeholder="0.00" step="0.01" min="0">
                                    </div>
                                    <div class="col-6">
                                        <label style="font-size:.72rem;color:#78350f;display:block;">Monto por QR</label>
                                        <input type="number" id="vQr" class="form-control form-control-sm" placeholder="0.00" step="0.01" min="0">
                                    </div>
                                </div>
                                <div style="font-size:.7rem;color:#dc2626;margin-top:.35rem;" id="vParcialError" style="display:none;"></div>
                            </div>

                            {{-- Cuotas agrupadas por concepto --}}
                            <div>
                                <label style="font-size:.8rem;font-weight:600;color:#475569;display:block;margin-bottom:.5rem;">
                                    Cuotas a registrar <span style="color:#dc2626;">*</span>
                                </label>
                                <div id="vCuotasContainer" style="border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;max-height:200px;overflow-y:auto;"></div>
                            </div>

                            {{-- Monto total --}}
                            <div style="margin-top:1rem;padding:.75rem;background:#f0fdf4;border-radius:8px;border:1px solid #bbf7d0;">
                                <div style="display:flex;align-items:center;justify-content:space-between;">
                                    <label style="font-size:.8rem;font-weight:600;color:#166534;">Monto total del pago</label>
                                    <input type="number" id="vMontoTotal" style="width:120px;border:1px solid #86efac;border-radius:6px;padding:.35rem .6rem;font-size:.9rem;font-weight:600;text-align:right;color:#166534;background:white;" step="0.01" min="0">
                                </div>
                            </div>

                            {{-- Validación suma --}}
                            <div id="vSumaError" style="display:none;margin-top:.5rem;padding:.5rem;background:#fee2e2;border-radius:6px;font-size:.75rem;color:#991b1b;">
                                <i class="ri-error-warning-line me-1"></i>
                                <span id="vSumaErrorMsg"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;">
                <button type="button" class="btn" data-bs-dismiss="modal" style="padding:.5rem 1.25rem;border-radius:6px;border:1px solid #cbd5e1;background:white;color:#475569;font-weight:500;">
                    Cancelar
                </button>
                <button type="button" id="btnConfirmarVerificar" style="padding:.5rem 1.25rem;border-radius:6px;border:none;background:#059669;color:white;font-weight:500;cursor:pointer;">
                    <i class="ri-check-double-line"></i> Verificar y Registrar
                </button>
            </div>
        </div>
    </div>
</div>
```

- [ ] **Step 2: Verificar que el modal se renderiza correctamente**

Run: Visitar la página admin.comprobantes.index y verificar que no hay errores de sintaxis

---

## Tarea 4: JavaScript para comportamiento completo

**Files:**
- Modify: `resources/views/admin/comprobantes/index.blade.php:309-459` (reescribir script completo)

- [ ] **Step 1: Reescribir el JavaScript completo**

Reemplazar todo el script existente (líneas 309-459) con:

```javascript
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let verifiandoId = null;
    let cuotasData = [];

    function toast(tipo, msg) {
        const el = document.getElementById('toastComp');
        const inn = document.getElementById('toastCompInner');
        inn.style.background = tipo === 'success' ? '#16a34a' : '#dc2626';
        document.getElementById('toastCompIcon').className = tipo === 'success' ? 'ri-check-circle-line' : 'ri-error-warning-line';
        document.getElementById('toastCompMsg').textContent = msg;
        el.style.display = 'block';
        setTimeout(() => { el.style.display = 'none'; }, 3500);
    }

    function escH(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function agruparPorConcepto(cuotas) {
        const grupos = {};
        cuotas.forEach(c => {
            const concepto = c.concepto || 'Otros';
            if (!grupos[concepto]) grupos[concepto] = [];
            grupos[concepto].push(c);
        });
        return grupos;
    }

    function distribuirMonto(montoTotal, cuotasSeleccionadas) {
        const totalPendiente = cuotasSeleccionadas.reduce((sum, c) => sum + c.pago_pendiente_bs, 0);
        if (totalPendiente === 0) return {};
        
        const resultado = {};
        let restante = montoTotal;
        
        cuotasSeleccionadas.forEach((c, idx) => {
            const proporcion = c.pago_pendiente_bs / totalPendiente;
            let monto = Math.round(montoTotal * proporcion * 100) / 100;
            if (idx === cuotasSeleccionadas.length - 1) {
                monto = Math.round(restante * 100) / 100;
            } else {
                restante -= monto;
            }
            resultado[c.id] = Math.min(monto, c.pago_pendiente_bs);
        });
        
        return resultado;
    }

    function renderizarCuotas() {
        const container = document.getElementById('vCuotasContainer');
        const grupos = agruparPorConcepto(cuotasData);
        let html = '';
        
        Object.keys(grupos).sort().forEach(concepto => {
            const cuotas = grupos[concepto];
            html += `<div style="background:#f8fafc;padding:.5rem .75rem;border-bottom:1px solid #e2e8f0;">
                <span style="font-size:.75rem;font-weight:600;color:#475569;text-transform:uppercase;">${escH(concepto)}</span>
            </div>`;
            
            cuotas.forEach(c => {
                const estadoColor = c.estado === 'pagado' ? '#16a34a' : c.estado === 'vencido' ? '#dc2626' : '#f59e0b';
                const disabled = c.estado === 'pagado' ? 'disabled' : '';
                const checked = c.seleccionada ? 'checked' : '';
                
                html += `<div class="cuota-pago-row" style="display:flex;align-items:center;gap:.5rem;padding:.5rem .75rem;border-bottom:1px solid #f1f5f9;">
                    <input type="checkbox" class="cuota-checkbox" data-cuota-id="${c.id}" ${checked} ${disabled}>
                    <div class="cuota-pago-info" style="flex:1;">
                        <div style="font-size:.8rem;font-weight:500;color:#1e293b;">${escH(c.nombre)} #${c.n_cuota}</div>
                        <div style="font-size:.7rem;color:#64748b;">Total: Bs ${c.monto_bs.toFixed(2)} · Pendiente: <strong>Bs ${c.pago_pendiente_bs.toFixed(2)}</strong></div>
                    </div>
                    <span style="font-size:.65rem;font-weight:600;color:${estadoColor};background:${estadoColor}1a;padding:.1rem .35rem;border-radius:3px;">${escH(c.estado)}</span>
                    <input type="number" class="cuota-monto-input" data-cuota-id="${c.id}" 
                        style="width:90px;border:1px solid #e2e8f0;border-radius:4px;padding:.25rem .5rem;font-size:.8rem;text-align:right;background:${c.seleccionada ? 'white' : '#f1f5f9'};${c.seleccionada ? '' : 'pointer-events:none;'}"
                        ${disabled} min="0" max="${c.pago_pendiente_bs}" step="0.01" value="${c.seleccionada ? c.pago_pendiente_bs.toFixed(2) : '0.00'}">
                </div>`;
            });
        });
        
        container.innerHTML = html;
        
        // Event listeners para checkboxes y inputs
        container.querySelectorAll('.cuota-checkbox').forEach(chk => {
            chk.addEventListener('change', () => {
                const input = container.querySelector(`.cuota-monto-input[data-cuota-id="${chk.dataset.cuotaId}"]`);
                if (chk.checked) {
                    input.style.background = 'white';
                    input.style.pointerEvents = 'auto';
                    const cuota = cuotasData.find(c => c.id == chk.dataset.cuotaId);
                    input.value = cuota.pago_pendiente_bs.toFixed(2);
                } else {
                    input.style.background = '#f1f5f9';
                    input.style.pointerEvents = 'none';
                    input.value = '0.00';
                }
                recalcularYDistribuir();
            });
        });
        
        container.querySelectorAll('.cuota-monto-input').forEach(inp => {
            inp.addEventListener('input', () => {
                const suma = calcularSuma();
                document.getElementById('vMontoTotal').value = suma.toFixed(2);
                validarSuma();
            });
        });
        
        recalcularYDistribuir();
    }

    function calcularSuma() {
        let suma = 0;
        document.querySelectorAll('.cuota-monto-input').forEach(inp => {
            suma += parseFloat(inp.value) || 0;
        });
        return suma;
    }

    function recalcularYDistribuir() {
        const montoTotal = parseFloat(document.getElementById('vMontoTotal').value) || 0;
        const seleccionadas = [];
        cuotasData.forEach(c => {
            const chk = document.querySelector(`.cuota-checkbox[data-cuota-id="${c.id}"]`);
            if (chk && chk.checked) seleccionadas.push(c);
        });
        
        if (seleccionadas.length > 0 && montoTotal > 0) {
            const distribucion = distribuirMonto(montoTotal, seleccionadas);
            Object.keys(distribucion).forEach(cuotaId => {
                const inp = document.querySelector(`.cuota-monto-input[data-cuota-id="${cuotaId}"]`);
                if (inp) inp.value = distribucion[cuotaId].toFixed(2);
            });
        }
        validarSuma();
    }

    function validarSuma() {
        const suma = calcularSuma();
        const total = parseFloat(document.getElementById('vMontoTotal').value) || 0;
        const diff = Math.abs(suma - total) > 0.01;
        const errorDiv = document.getElementById('vSumaError');
        
        if (diff) {
            errorDiv.style.display = 'block';
            document.getElementById('vSumaErrorMsg').textContent = `La suma de cuotas (Bs ${suma.toFixed(2)}) no coincide con el monto total (Bs ${total.toFixed(2)})`;
        } else {
            errorDiv.style.display = 'none';
        }
        return !diff;
    }

    function validarParcial() {
        const tipoPago = document.getElementById('vTipoPago').value;
        if (tipoPago !== 'Parcial') return true;
        
        const efectivo = parseFloat(document.getElementById('vEfectivo').value) || 0;
        const qr = parseFloat(document.getElementById('vQr').value) || 0;
        const total = parseFloat(document.getElementById('vMontoTotal').value) || 0;
        
        const errorDiv = document.getElementById('vParcialError');
        
        if (efectivo <= 0 || qr <= 0) {
            errorDiv.style.display = 'block';
            errorDiv.textContent = 'Ingresa monto en efectivo y por QR';
            return false;
        }
        
        if (Math.abs((efectivo + qr) - total) > 0.01) {
            errorDiv.style.display = 'block';
            errorDiv.textContent = `Efectivo (Bs ${efectivo.toFixed(2)}) + QR (Bs ${qr.toFixed(2)}) debe ser igual al monto total (Bs ${total.toFixed(2)})`;
            return false;
        }
        
        errorDiv.style.display = 'none';
        return true;
    }

    // Abrir modal verificar
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-btn-verificar');
        if (!btn) return;

        verifiandoId = btn.dataset.id;
        
        // Reset UI
        document.getElementById('modalVerificarLoading').style.display = 'block';
        document.getElementById('vPreviewContent').style.display = 'none';
        document.getElementById('vObservaciones').textContent = '';
        document.getElementById('modalVerificarBodyRight').style.display = 'none';
        document.getElementById('btnConfirmarVerificar').disabled = false;
        document.getElementById('btnConfirmarVerificar').innerHTML = '<i class="ri-check-double-line"></i> Verificar y Registrar';
        
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalVerificar')).show();

        fetch(`/admin/comprobantes/${verifiandoId}/cuotas`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('modalVerificarLoading').style.display = 'none';
                if (!data.success) {
                    toast('error', 'Error al cargar datos');
                    return;
                }

                // Info estudiante
                document.getElementById('vEstudianteNombre').textContent = data.estudiante;
                document.getElementById('vPlanNombre').textContent = data.plan_nombre;
                
                // Preview
                document.getElementById('vObservaciones').textContent = data.comprobante.observaciones || '';
                const ext = data.comprobante.archivo_ext;
                const url = data.comprobante.archivo_url;
                
                if (ext === 'pdf') {
                    document.getElementById('vImagePreview').style.display = 'none';
                    document.getElementById('vPdfPreview').style.display = 'block';
                    document.getElementById('vPreviewIframe').src = url;
                } else {
                    document.getElementById('vPdfPreview').style.display = 'none';
                    document.getElementById('vImagePreview').style.display = 'block';
                    document.getElementById('vPreviewImg').src = url;
                }
                
                document.getElementById('vPreviewContent').style.display = 'block';
                
                // Cuotas
                cuotasData = data.cuotas;
                renderizarCuotas();
                
                // Reset tipo pago
                document.getElementById('vTipoPago').value = 'Efectivo';
                document.getElementById('vCamposParcial').style.display = 'none';
                document.getElementById('vEfectivo').value = '';
                document.getElementById('vQr').value = '';
                
                document.getElementById('modalVerificarBodyRight').style.display = 'block';
            })
            .catch(() => {
                document.getElementById('modalVerificarLoading').style.display = 'none';
                toast('error', 'Error de conexión');
            });
    });

    // Cambio tipo pago
    document.getElementById('vTipoPago')?.addEventListener('change', function() {
        const camposParcial = document.getElementById('vCamposParcial');
        camposParcial.style.display = this.value === 'Parcial' ? 'block' : 'none';
        if (this.value !== 'Parcial') {
            document.getElementById('vEfectivo').value = '';
            document.getElementById('vQr').value = '';
            document.getElementById('vParcialError').style.display = 'none';
        }
    });

    // Cambio monto total - redistribute
    document.getElementById('vMontoTotal')?.addEventListener('input', recalcularYDistribuir);

    // Confirmar verificación
    document.getElementById('btnConfirmarVerificar')?.addEventListener('click', function () {
        if (!verifiandoId) return;

        // Validaciones
        const seleccionadas = [];
        document.querySelectorAll('.cuota-checkbox:checked').forEach(chk => {
            seleccionadas.push(parseInt(chk.dataset.cuotaId));
        });
        
        if (seleccionadas.length === 0) {
            toast('error', 'Selecciona al menos una cuota');
            return;
        }

        if (!validarSuma()) {
            toast('error', 'La suma de cuotas debe ser igual al monto total');
            return;
        }

        if (!validarParcial()) {
            return;
        }

        const tipoPago = document.getElementById('vTipoPago').value;
        const montoTotal = parseFloat(document.getElementById('vMontoTotal').value);
        
        const cuotas = [];
        document.querySelectorAll('.cuota-checkbox:checked').forEach(chk => {
            const cuotaId = parseInt(chk.dataset.cuotaId);
            const input = document.querySelector(`.cuota-monto-input[data-cuota-id="${cuotaId}"]`);
            const monto = parseFloat(input.value);
            if (monto > 0) {
                cuotas.push({ cuota_id: cuotaId, monto });
            }
        });

        const payload = {
            tipo_pago: tipoPago,
            monto_total: montoTotal,
            cuotas: cuotas
        };

        if (tipoPago === 'Parcial') {
            payload.efectivo = parseFloat(document.getElementById('vEfectivo').value);
            payload.qr = parseFloat(document.getElementById('vQr').value);
        }

        this.disabled = true;
        this.innerHTML = '<i class="ri-loader-4-line"></i> Procesando...';

        fetch(`/admin/comprobantes/${verifiandoId}/verificar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(data => {
            this.disabled = false;
            this.innerHTML = '<i class="ri-check-double-line"></i> Verificar y Registrar';
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalVerificar'))?.hide();
                toast('success', data.mensaje);
                setTimeout(() => location.reload(), 1400);
            } else {
                toast('error', data.message || 'Error al verificar');
            }
        })
        .catch(() => {
            this.disabled = false;
            this.innerHTML = '<i class="ri-check-double-line"></i> Verificar y Registrar';
            toast('error', 'Error de conexión');
        });
    });

    // Rechazar / Pendiente
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-btn-simple');
        if (!btn) return;

        const id = btn.dataset.id;
        const accion = btn.dataset.accion;
        btn.disabled = true;

        fetch(`/admin/comprobantes/${id}/${accion}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { toast('success', data.mensaje); setTimeout(() => location.reload(), 1200); }
            else { toast('error', data.message || 'Error'); btn.disabled = false; }
        })
        .catch(() => { toast('error', 'Error de conexión'); btn.disabled = false; });
    });
})();
```

- [ ] **Step 2: Probar funcionalidad**

Verificar que:
1. El modal abre con preview de imagen/PDF a la izquierda
2. Las cuotas aparecen agrupadas por concepto
3. Las cuotas del comprobante vienen marcadas por defecto
4. Al cambiar el monto total se redistribuye automáticamente
5. Al seleccionar "Parcial" aparecen campos efectivo y QR
6. Las validaciones muestran errores apropiados

---

## Tarea 5: Actualizar método verificar en Controller

**Files:**
- Modify: `app/Http/Controllers/Admin/ComprobantesPagoController.php:81-143`

- [x] **Step 1: Actualizar validación del método verificar**

Reemplazar método verificar completo:

```php
public function verificar(Request $request, int $id)
{
    $request->validate([
        'tipo_pago'    => 'required|string|max:20',
        'monto_total'  => 'required|numeric|min:0.01',
        'cuotas'       => 'required|array|min:1',
        'cuotas.*.cuota_id' => 'required|integer',
        'cuotas.*.monto'    => 'required|numeric|min:0.01',
        'efectivo'     => 'nullable|numeric|min:0',
        'qr'           => 'nullable|numeric|min:0',
    ]);

    // Validación especial para tipo Parcial
    if ($request->tipo_pago === 'Parcial') {
        $efectivo = (float) ($request->efectivo ?? 0);
        $qr = (float) ($request->qr ?? 0);
        $total = (float) $request->monto_total;
        
        if ($efectivo <= 0 || $qr <= 0) {
            return response()->json(['success' => false, 'message' => 'Para pago parcial debe ingresar monto en efectivo y por QR.'], 422);
        }
        if (abs(($efectivo + $qr) - $total) > 0.01) {
            return response()->json(['success' => false, 'message' => 'La suma de efectivo + QR debe ser igual al monto total del pago.'], 422);
        }
    }

    $comprobante = PagoRespaldo::with('cuotas', 'inscripcion.trabajador_cargo')->findOrFail($id);

    if ($comprobante->estado === 'verificado') {
        return response()->json(['success' => false, 'message' => 'Este comprobante ya fue verificado.'], 422);
    }

    $cuotaIdsComprobante = $comprobante->cuotas->pluck('id')->toArray();
    $cuotasInput = $request->cuotas;

    // Validar que todos los cuota_id pertenecen al comprobante
    foreach ($cuotasInput as $item) {
        if (!in_array((int) $item['cuota_id'], $cuotaIdsComprobante)) {
            return response()->json(['success' => false, 'message' => "La cuota {$item['cuota_id']} no pertenece a este comprobante."], 422);
        }
    }

    DB::transaction(function () use ($comprobante, $cuotasInput, $request) {
        $montoTotal = (float) $request->monto_total;

        // Para tipo Parcial, guardar tipo_pago como "Parcial" pero almacenar detalles en observación o campo adicional
        $tipoPagoFinal = $request->tipo_pago === 'Parcial' ? 'Parcial' : $request->tipo_pago;
        
        $pago = Pago::create([
            'trabajadore_cargo_id' => $comprobante->inscripcion?->trabajadores_cargo_id,
            'monto_total'         => $montoTotal,
            'descuento_bs'        => 0,
            'tipo_pago'           => $tipoPagoFinal,
            'fecha_pago'          => now()->toDateString(),
            'estado'              => 'pagado',
        ]);

        foreach ($cuotasInput as $item) {
            $cuotaId = (int) $item['cuota_id'];
            $monto = (float) $item['monto'];
            $cuota = Cuota::find($cuotaId);
            if (!$cuota) continue;

            PagosCuota::create([
                'pago_id'    => $pago->id,
                'cuota_id'   => $cuota->id,
                'monto_bs'   => $monto,
                'fecha_pago' => now()->toDateString(),
            ]);

            $nuevoPendiente = max(0, (float) $cuota->pago_pendiente_bs - $monto);
            $nuevoEstado    = $nuevoPendiente <= 0 ? 'pagado' : $cuota->estado;

            $cuota->update([
                'pago_pendiente_bs' => $nuevoPendiente,
                'estado'            => $nuevoEstado,
                'fecha_pago'        => $nuevoPendiente <= 0 ? now()->toDateString() : $cuota->fecha_pago,
            ]);
        }

        $comprobante->update(['estado' => 'verificado']);
    });

    return response()->json(['success' => true, 'mensaje' => 'Comprobante verificado y pagos registrados correctamente.']);
}
```

- [x] **Step 2: Probar verificación completa**

Run: Verificar un comprobante y confirmar que:
1. Se crea el pago con tipo correcto
2. Se registran los pagos por cuota
3. Las cuotas actualizan su estado
4. El comprobante cambia a "verificado"

---

## Tarea 6: Estilos CSS adicionales

**Files:**
- Modify: `resources/views/admin/comprobantes/index.blade.php:4-90`

- [ ] **Step 1: Agregar estilos para nuevos elementos**

Agregar al final de la sección @section('css'):

```css
/* Modal verificar dos columnas */
#modalVerificar .modal-body { padding: 0; }
#modalVerificar .row.g-0 { min-height: 450px; }
.cuota-checkbox { width: 18px; height: 18px; cursor: pointer; }
.cuota-monto-input:disabled { opacity: 0.6; }
```

---

## Checklist Final de Verificación

- [ ] Migración ejecutada correctamente
- [ ] Endpoint getCuotas retorna campos `seleccionada` y `concepto`
- [ ] Modal muestra dos columnas (preview izquierda, formulario derecha)
- [ ] Preview de imagen/PDF funciona
- [ ] Cuotas agrupadas por concepto
- [ ] Checkboxes marcados por defecto para cuotas del comprobante
- [ ] Distribución automática editable funciona
- [ ] Selector tipo de pago con 6 opciones
- [ ] Campos efectivo/QR aparecen solo para "Parcial"
- [ ] Validación efectivo+qr = monto total funciona
- [ ] Validación suma cuotas = monto total funciona
- [ ] Backend acepta nuevos parámetros
- [ ] Verificación crea pago y actualiza cuotas correctamente