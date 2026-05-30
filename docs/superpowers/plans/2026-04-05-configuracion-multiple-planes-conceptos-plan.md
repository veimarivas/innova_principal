# Configuración Múltiple de Planes y Conceptos — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the single-concept registration modal in the Área Contable tab with a dynamic multi-row modal that lets users select a payment plan and register multiple concepts in one save operation, with auto-completed pricing from the principal plan.

**Architecture:** Add 3 new backend endpoints (verify principal plan, get base price, save multiple concepts) to `OfertasAcademicaController`. Replace the existing `modalCrearPc` in `detalle.blade.php` with a `modal-lg` containing a dynamic table of concept rows. All concepts for a plan are saved in a single DB transaction.

**Tech Stack:** Laravel 11 (PHP), Blade templates, jQuery, DataTables, Bootstrap 5 modals

---

## File Structure

| File | Action | Responsibility |
|------|--------|----------------|
| `routes/web.php` | Modify | Add 3 new routes for multiple concept registration |
| `app/Http/Controllers/OfertasAcademicaController.php` | Modify | Add 3 new methods: `verificarPlanPrincipal`, `obtenerPrecioBase`, `guardarPlanesConceptoMultiple` |
| `resources/views/admin/ofertas-academicas/detalle.blade.php` | Modify | Replace `modalCrearPc` HTML and rewrite JS functions for dynamic row management |

---

### Task 1: Add new routes for multiple concept registration

**Files:**
- Modify: `routes/web.php:93` (after existing planes-conceptos routes)

- [ ] **Step 1: Add 3 new routes**

Add these routes inside the `admin/posgrados` group, after line 93 (after `ofertas.planes-conceptos.eliminar`):

```php
    Route::get('/ofertas/{ofertaId}/planes-conceptos/verificar-principal', [OfertasAcademicaController::class, 'verificarPlanPrincipal'])->name('ofertas.planes-conceptos.verificar-principal');
    Route::get('/ofertas/{ofertaId}/planes-conceptos/precio-base/{conceptoId}', [OfertasAcademicaController::class, 'obtenerPrecioBase'])->name('ofertas.planes-conceptos.precio-base');
    Route::post('/ofertas/{ofertaId}/planes-conceptos/multiple', [OfertasAcademicaController::class, 'guardarPlanesConceptoMultiple'])->name('ofertas.planes-conceptos.multiple');
```

- [ ] **Step 2: Verify routes are registered**

Run: `php artisan route:list | grep planes-conceptos`
Expected: All 6 routes (3 existing + 3 new) should appear under the posgrados prefix.

---

### Task 2: Add backend method — verificarPlanPrincipal

**Files:**
- Modify: `app/Http/Controllers/OfertasAcademicaController.php` (add after `listarConceptosDisponibles` method, around line 248)

- [ ] **Step 1: Add verificarPlanPrincipal method**

Add this method to `OfertasAcademicaController.php` after the `listarConceptosDisponibles` method:

```php
    public function verificarPlanPrincipal($ofertaId)
    {
        $planPrincipalId = PlanesPago::where('principal', true)->value('id');

        if (!$planPrincipalId) {
            return response()->json(['tiene_principal' => false, 'message' => 'No existe un plan de pago marcado como principal.']);
        }

        $tieneConceptos = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
            ->where('planes_pago_id', $planPrincipalId)
            ->exists();

        return response()->json([
            'tiene_principal' => $tieneConceptos,
            'plan_principal_id' => $planPrincipalId,
        ]);
    }
```

- [ ] **Step 2: Verify the method works**

Run: `php artisan tinker` then test the route manually or via browser:
`GET /admin/posgrados/ofertas/{ofertaId}/planes-conceptos/verificar-principal`
Expected JSON: `{"tiene_principal": true, "plan_principal_id": 1}` or `{"tiene_principal": false, "message": "..."}`

---

### Task 3: Add backend method — obtenerPrecioBase

**Files:**
- Modify: `app/Http/Controllers/OfertasAcademicaController.php` (add after `verificarPlanPrincipal`)

- [ ] **Step 1: Add obtenerPrecioBase method**

Add this method after `verificarPlanPrincipal`:

```php
    public function obtenerPrecioBase($ofertaId, $conceptoId)
    {
        $planPrincipalId = PlanesPago::where('principal', true)->value('id');

        if (!$planPrincipalId) {
            return response()->json(['precio_base' => null, 'message' => 'No existe un plan principal configurado.']);
        }

        $registro = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
            ->where('planes_pago_id', $planPrincipalId)
            ->where('concepto_id', $conceptoId)
            ->first();

        if (!$registro) {
            return response()->json(['precio_base' => null, 'message' => 'No existe precio base para este concepto en el plan principal.']);
        }

        return response()->json(['precio_base' => $registro->pago_bs]);
    }
```

- [ ] **Step 2: Verify the method works**

Test via browser or tinker:
`GET /admin/posgrados/ofertas/{ofertaId}/planes-conceptos/precio-base/{conceptoId}`
Expected JSON: `{"precio_base": 500.00}` or `{"precio_base": null, "message": "..."}`

---

### Task 4: Add backend method — guardarPlanesConceptoMultiple

**Files:**
- Modify: `app/Http/Controllers/OfertasAcademicaController.php` (add after `obtenerPrecioBase`)

- [ ] **Step 1: Add use statement for DB facade**

At the top of the controller, ensure `use Illuminate\Support\Facades\DB;` is imported. Check existing imports first — if `DB` is not already imported, add it:

```php
use Illuminate\Support\Facades\DB;
```

- [ ] **Step 2: Add guardarPlanesConceptoMultiple method**

Add this method after `obtenerPrecioBase`:

```php
    public function guardarPlanesConceptoMultiple(Request $request, $ofertaId)
    {
        $validator = Validator::make($request->all(), [
            'planes_pago_id' => 'required|exists:planes_pagos,id',
            'conceptos' => 'required|array|min:1',
            'conceptos.*.concepto_id' => 'required|exists:conceptos,id',
            'conceptos.*.n_cuotas' => 'required|integer|min:1',
            'conceptos.*.precio_regular' => 'required|numeric|min:0',
            'conceptos.*.descuento_bs' => 'nullable|numeric|min:0',
        ], [
            'planes_pago_id.required' => 'Seleccione un plan de pago.',
            'conceptos.required' => 'Debe agregar al menos un concepto.',
            'conceptos.min' => 'Debe agregar al menos un concepto.',
            'conceptos.*.concepto_id.required' => 'Seleccione un concepto en cada fila.',
            'conceptos.*.n_cuotas.required' => 'Ingrese el número de cuotas.',
            'conceptos.*.n_cuotas.min' => 'Debe tener al menos 1 cuota.',
            'conceptos.*.precio_regular.required' => 'Ingrese el precio regular.',
            'conceptos.*.precio_regular.min' => 'El precio no puede ser negativo.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $planPagoId = $request->planes_pago_id;
        $conceptos = $request->conceptos;

        // Verificar que el plan no esté ya registrado en esta oferta
        $yaRegistrado = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
            ->where('planes_pago_id', $planPagoId)
            ->exists();

        if ($yaRegistrado) {
            return response()->json(['success' => false, 'message' => 'Este plan ya tiene configuraciones en esta oferta.'], 400);
        }

        // Verificar que exista un plan principal con conceptos para esta oferta
        $planPrincipalId = PlanesPago::where('principal', true)->value('id');
        if ($planPrincipalId) {
            $tienePrincipal = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
                ->where('planes_pago_id', $planPrincipalId)
                ->exists();
        } else {
            $tienePrincipal = false;
        }

        if (!$tienePrincipal) {
            return response()->json(['success' => false, 'message' => 'Debe existir un plan principal configurado antes de registrar esta configuración.'], 400);
        }

        // Verificar que no haya concepto_id duplicados en el payload
        $conceptoIds = array_column($conceptos, 'concepto_id');
        if (count($conceptoIds) !== count(array_unique($conceptoIds))) {
            return response()->json(['success' => false, 'message' => 'No puede repetir el mismo concepto en la misma configuración.'], 422);
        }

        // Verificar que ningún concepto ya esté registrado en esta oferta
        $conceptosExistentes = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
            ->whereIn('concepto_id', $conceptoIds)
            ->pluck('concepto_id')
            ->toArray();

        if (!empty($conceptosExistentes)) {
            $conceptoNombres = Concepto::whereIn('id', $conceptosExistentes)->pluck('nombre')->toArray();
            return response()->json([
                'success' => false,
                'message' => 'Los siguientes conceptos ya están configurados en esta oferta: ' . implode(', ', $conceptoNombres),
            ], 422);
        }

        // Verificar que descuento_bs no sea mayor que precio_regular en ninguna fila
        foreach ($conceptos as $index => $c) {
            $descuento = $c['descuento_bs'] ?? 0;
            if ($descuento > $c['precio_regular']) {
                return response()->json([
                    'success' => false,
                    'message' => 'El descuento no puede ser mayor que el precio regular en la fila ' . ($index + 1) . '.',
                ], 422);
            }
        }

        // Guardar en transacción
        $planNombre = PlanesPago::find($planPagoId)->nombre;
        $registrados = 0;

        DB::transaction(function () use ($ofertaId, $planPagoId, $conceptos, &$registrados) {
            foreach ($conceptos as $c) {
                $descuento = $c['descuento_bs'] ?? 0;
                PlanesConcepto::create([
                    'ofertas_academica_id' => $ofertaId,
                    'planes_pago_id' => $planPagoId,
                    'concepto_id' => $c['concepto_id'],
                    'n_cuotas' => $c['n_cuotas'],
                    'precio_regular' => $c['precio_regular'],
                    'descuento_bs' => $descuento,
                    'pago_bs' => max(0, $c['precio_regular'] - $descuento),
                ]);
                $registrados++;
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Configuración guardada: {$registrados} concepto(s) registrado(s) para {$planNombre}.",
            'data' => [
                'planes_pago_id' => $planPagoId,
                'conceptos_registrados' => $registrados,
            ],
        ]);
    }
```

- [ ] **Step 3: Verify syntax**

Run: `php artisan route:list | grep planes-conceptos/multiple`
Expected: Route should appear with POST method.

---

### Task 5: Replace modalCrearPc HTML with dynamic multi-row modal

**Files:**
- Modify: `resources/views/admin/ofertas-academicas/detalle.blade.php` (lines 1190-1241, the entire `modalCrearPc` block)

- [ ] **Step 1: Read the current modalCrearPc block**

The current modal spans lines 1190-1241. It will be replaced entirely.

- [ ] **Step 2: Replace modalCrearPc with the new dynamic modal**

Replace the entire `modalCrearPc` block (lines 1190-1241) with:

```html
<div class="modal fade" id="modalCrearPc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title"><i class="ri-add-circle-line"></i> Nueva Configuración de Precio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formCrearPc" novalidate>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600;">Plan de Pago <span style="color:#ef4444;">*</span></label>
                        <select class="form-select" id="planesPagoCrear">
                            <option value="">— Seleccionar —</option>
                        </select>
                        <div id="badgePromocionCrear" style="display:none;margin-top:0.35rem;">
                            <span class="badge" style="background:rgba(252,123,4,0.15);color:#fc7b04;font-weight:700;"><i class="ri-gift-line"></i> Promoción</span>
                        </div>
                    </div>

                    <div id="bannerSinPrincipal" style="display:none;" class="mb-3 p-3 rounded" style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.25);">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-alert-line" style="color:#f59e0b;font-size:1.25rem;"></i>
                            <span style="font-size:0.85rem;font-weight:600;color:#f59e0b;">No se puede registrar: no existe un plan principal configurado con precio base.</span>
                        </div>
                    </div>

                    <div id="tablaConceptosContainer" style="display:none;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0" style="font-weight:600;font-size:0.85rem;">Conceptos</label>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddFilaConcepto">
                                <i class="ri-add-line"></i> Agregar Concepto
                            </button>
                        </div>
                        <div class="table-responsive" style="max-height:300px;overflow-y:auto;">
                            <table class="table table-sm align-middle mb-0" id="tablaFilasConceptos">
                                <thead style="position:sticky;top:0;background:var(--d-bg);z-index:1;">
                                    <tr>
                                        <th style="min-width:180px;">Concepto <span style="color:#ef4444;">*</span></th>
                                        <th style="width:80px;">Cuotas <span style="color:#ef4444;">*</span></th>
                                        <th style="width:110px;">P. Regular (Bs)</th>
                                        <th style="width:110px;">Descuento (Bs)</th>
                                        <th style="width:100px;" class="text-end">Pago (Bs)</th>
                                        <th style="width:40px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="filasConceptosBody">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3 p-3 rounded" id="totalGeneralContainer" style="display:none;background:rgba(252,123,4,0.08);border:1px solid rgba(252,123,4,0.2);">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--d-title);">
                                <i class="ri-money-dollar-circle-line" style="color:#fc7b04;"></i> Total a Pagar
                            </span>
                            <span style="font-size:1.1rem;font-weight:800;color:#fc7b04;" id="totalGeneralCrear">Bs. 0.00</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="ri-close-line"></i> Cancelar</button>
                <button type="button" class="btn btn-sm btn-success" id="btnGuardarPc" disabled><i class="ri-save-line"></i> Guardar Todo</button>
            </div>
        </div>
    </div>
</div>
```

---

### Task 6: Add CSS styles for the dynamic table rows

**Files:**
- Modify: `resources/views/admin/ofertas-academicas/detalle.blade.php` (inside the `@section('css')` block, add new styles before `</style>`)

- [ ] **Step 1: Add CSS for concept rows**

Add these styles inside the `<style>` block, before the closing `</style>` tag (around line 825, before the `@media` query):

```css
.fila-concepto {
    transition: background 0.15s;
}
.fila-concepto:hover {
    background: var(--d-row-hover);
}
.fila-concepto .select-concepto.is-invalid-custom {
    border-color: #ef4444;
}
.fila-concepto .warning-precio {
    font-size: 0.7rem;
    color: #f59e0b;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}
.btn-remove-fila {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    border: none;
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-remove-fila:hover {
    background: rgba(239, 68, 68, 0.2);
    transform: scale(1.05);
}
.btn-remove-fila:disabled {
    opacity: 0.3;
    cursor: not-allowed;
    transform: none;
}
```

---

### Task 7: Rewrite JavaScript — Replace modalCrearPc initialization and add dynamic row functions

**Files:**
- Modify: `resources/views/admin/ofertas-academicas/detalle.blade.php` (the `@section('script')` block, replace the existing `modalCrearPc` related JS functions)

- [ ] **Step 1: Replace the existing `btn-nuevo-plan-concepto` click handler and related functions**

Find the existing block that starts with:
```js
$('#btn-nuevo-plan-concepto').on('click', function() {
```
And the functions: `calcularPago`, `$('#precioRegularCrear, #descuentoBsCrear').on('input', ...)`, `$('#btnGuardarPc').on('click', ...)`

Replace ALL of these with the new dynamic row system. The replacement code:

```javascript
    // ===== NUEVO SISTEMA: Filas dinámicas de conceptos =====
    let filaConceptoCount = 0;
    let planesPagoData = [];
    let conceptosData = [];
    let tienePlanPrincipal = false;

    function renderFilaConcepto(conceptoId, nCuotas, precioRegular, descuentoBs) {
        filaConceptoCount++;
        const idx = filaConceptoCount;

        let conceptosOpts = '<option value="">— Seleccionar —</option>';
        conceptosData.forEach(function(c) {
            const selected = c.id == conceptoId ? 'selected' : '';
            conceptosOpts += '<option value="' + c.id + '" ' + selected + '>' + escHtml(c.nombre) + '</option>';
        });

        const html = '<tr class="fila-concepto" data-fila-idx="' + idx + '">' +
            '<td>' +
                '<select class="form-select form-select-sm select-concepto" data-fila="' + idx + '">' + conceptosOpts + '</select>' +
                '<div class="warning-precio" style="display:none;"><i class="ri-information-line"></i> <span>Sin precio base</span></div>' +
            '</td>' +
            '<td><input type="number" class="form-control form-control-sm input-cuotas" data-fila="' + idx + '" value="' + (nCuotas || 1) + '" min="1" max="60"></td>' +
            '<td><input type="number" class="form-control form-control-sm input-precio" data-fila="' + idx + '" value="' + (precioRegular || '0.00') + '" min="0" step="0.01"></td>' +
            '<td><input type="number" class="form-control form-control-sm input-descuento" data-fila="' + idx + '" value="' + (descuentoBs || '0.00') + '" min="0" step="0.01"></td>' +
            '<td class="text-end"><span class="fila-pago" data-fila="' + idx + '" style="font-weight:700;color:#fc7b04;">0.00</span></td>' +
            '<td class="text-center"><button type="button" class="btn-remove-fila" data-fila="' + idx + '" title="Eliminar fila"><i class="ri-close-line"></i></button></td>' +
        '</tr>';

        $('#filasConceptosBody').append(html);
        actualizarBotonesEliminar();
        calcularTotalGeneral();
    }

    function actualizarBotonesEliminar() {
        const totalFilas = $('#filasConceptosBody .fila-concepto').length;
        $('#filasConceptosBody .btn-remove-fila').prop('disabled', totalFilas <= 1);
    }

    function calcularPagoFila(filaIdx) {
        const $fila = $('.fila-concepto[data-fila-idx="' + filaIdx + '"]');
        const regular = parseFloat($fila.find('.input-precio').val()) || 0;
        const descuento = parseFloat($fila.find('.input-descuento').val()) || 0;
        const pago = Math.max(0, regular - descuento);
        $fila.find('.fila-pago').text(pago.toFixed(2));
        calcularTotalGeneral();
    }

    function calcularTotalGeneral() {
        let total = 0;
        $('#filasConceptosBody .fila-concepto').each(function() {
            const regular = parseFloat($(this).find('.input-precio').val()) || 0;
            const descuento = parseFloat($(this).find('.input-descuento').val()) || 0;
            total += Math.max(0, regular - descuento);
        });
        $('#totalGeneralCrear').text('Bs. ' + total.toFixed(2));
        validarFormularioCrear();
    }

    function validarFormularioCrear() {
        const planId = $('#planesPagoCrear').val();
        const filas = $('#filasConceptosBody .fila-concepto');
        let todasCompletas = true;

        if (!planId || filas.length === 0) {
            $('#btnGuardarPc').prop('disabled', true);
            return;
        }

        filas.each(function() {
            const conceptoId = $(this).find('.select-concepto').val();
            const cuotas = $(this).find('.input-cuotas').val();
            const precio = $(this).find('.input-precio').val();
            if (!conceptoId || !cuotas || !precio) {
                todasCompletas = false;
                return false;
            }
        });

        $('#btnGuardarPc').prop('disabled', !todasCompletas || !tienePlanPrincipal);
    }

    function autoCompletarPrecio(filaIdx) {
        const $fila = $('.fila-concepto[data-fila-idx="' + filaIdx + '"]');
        const conceptoId = $fila.find('.select-concepto').val();
        const $warning = $fila.find('.warning-precio');

        if (!conceptoId) {
            $warning.hide();
            return;
        }

        $.getJSON('{{ route('admin.posgrads.ofertas.planes-conceptos.precio-base', ['ofertaId' => $oferta->id, 'conceptoId' => '__ID__']) }}'.replace('__ID__', conceptoId))
        .done(function(r) {
            if (r.precio_base !== null) {
                $fila.find('.input-precio').val(parseFloat(r.precio_base).toFixed(2));
                $warning.hide();
            } else {
                $fila.find('.input-precio').val('0.00');
                $warning.find('span').text('Sin precio base — ingrese manualmente');
                $warning.show();
            }
            calcularPagoFila(filaIdx);
        })
        .fail(function() {
            $warning.find('span').text('Error al obtener precio base');
            $warning.show();
        });
    }

    $('#btn-nuevo-plan-concepto').on('click', function() {
        $('#formCrearPc')[0].reset();
        $('#planesPagoCrear').html('<option value="">— Seleccionar —</option>');
        $('#filasConceptosBody').empty();
        filaConceptoCount = 0;
        tienePlanPrincipal = false;
        $('#bannerSinPrincipal').hide();
        $('#tablaConceptosContainer').hide();
        $('#totalGeneralContainer').hide();
        $('#badgePromocionCrear').hide();
        $('#btnGuardarPc').prop('disabled', true);
        $('#totalGeneralCrear').text('Bs. 0.00');

        $.when(
            $.getJSON('{{ route('admin.posgrads.ofertas.planes-pago.disponibles', $oferta->id) }}'),
            $.getJSON('{{ route('admin.posgrads.ofertas.conceptos.disponibles', $oferta->id) }}'),
            $.getJSON('{{ route('admin.posgrads.ofertas.planes-conceptos.verificar-principal', $oferta->id) }}')
        ).done(function(rPlanes, rConceptos, rPrincipal) {
            planesPagoData = rPlanes[0].data || [];
            conceptosData = rConceptos[0].data || [];
            tienePlanPrincipal = rPrincipal[0].tiene_principal || false;

            planesPagoData.forEach(function(p) {
                $('#planesPagoCrear').append('<option value="' + p.id + '" data-promocion="' + (p.es_promocion ? '1' : '0') + '">' + escHtml(p.nombre) + '</option>');
            });

            if (!tienePlanPrincipal) {
                $('#bannerSinPrincipal').show();
            } else {
                $('#tablaConceptosContainer').show();
                $('#totalGeneralContainer').show();
                renderFilaConcepto();
            }
        }).fail(function() {
            toast('error', 'Error al cargar datos.');
        });

        openModal('modalCrearPc');
    });

    $('#planesPagoCrear').on('change', function() {
        const selected = $(this).find('option:selected');
        const esPromocion = selected.data('promocion') == '1';
        if (esPromocion) {
            $('#badgePromocionCrear').show();
        } else {
            $('#badgePromocionCrear').hide();
        }
    });

    $('#btnAddFilaConcepto').on('click', function() {
        renderFilaConcepto();
    });

    $(document).on('change', '.select-concepto', function() {
        const filaIdx = $(this).data('fila');
        autoCompletarPrecio(filaIdx);
        validarFormularioCrear();
    });

    $(document).on('input', '.input-precio, .input-descuento', function() {
        const filaIdx = $(this).data('fila');
        calcularPagoFila(filaIdx);
    });

    $(document).on('input', '.input-cuotas', function() {
        validarFormularioCrear();
    });

    $(document).on('click', '.btn-remove-fila', function() {
        const totalFilas = $('#filasConceptosBody .fila-concepto').length;
        if (totalFilas <= 1) return;
        $(this).closest('.fila-concepto').remove();
        actualizarBotonesEliminar();
        calcularTotalGeneral();
    });

    $('#btnGuardarPc').on('click', function() {
        const planId = $('#planesPagoCrear').val();
        if (!planId) { toast('warning', 'Seleccione un plan de pago.'); return; }

        const conceptos = [];
        const conceptoIdsSet = new Set();
        let valid = true;

        $('#filasConceptosBody .fila-concepto').each(function() {
            const conceptoId = $(this).find('.select-concepto').val();
            const nCuotas = $(this).find('.input-cuotas').val();
            const precioRegular = $(this).find('.input-precio').val();
            const descuentoBs = $(this).find('.input-descuento').val();

            if (!conceptoId) {
                $(this).find('.select-concepto').addClass('is-invalid-custom');
                valid = false;
                return false;
            }
            $(this).find('.select-concepto').removeClass('is-invalid-custom');

            if (conceptoIdsSet.has(conceptoId)) {
                toast('warning', 'No puede repetir el mismo concepto.');
                valid = false;
                return false;
            }
            conceptoIdsSet.add(conceptoId);

            conceptos.push({
                concepto_id: conceptoId,
                n_cuotas: nCuotas,
                precio_regular: precioRegular,
                descuento_bs: descuentoBs || 0,
            });
        });

        if (!valid || conceptos.length === 0) {
            toast('warning', 'Complete todos los campos obligatorios.');
            return;
        }

        setBtnLoading('#btnGuardarPc', true, 'Guardando…');
        $.post('{{ route('admin.posgrads.ofertas.planes-conceptos.multiple', $oferta->id) }}', {
            _token: CSRF,
            planes_pago_id: planId,
            conceptos: conceptos,
        })
        .done(function(r) {
            closeModal('modalCrearPc');
            tablaPlanesConceptos.ajax.reload();
            toast('success', r.message || 'Configuración guardada.');
        })
        .fail(function(xhr) {
            const msg = xhr.responseJSON?.message || 'Error al guardar.';
            toast(xhr.status === 400 || xhr.status === 422 ? 'warning' : 'error', msg);
        })
        .always(function() { setBtnLoading('#btnGuardarPc', false, '<i class="ri-save-line"></i> Guardar Todo'); });
    });
```

- [ ] **Step 2: Remove the old single-concept JS code**

Remove these old blocks that are now replaced:
- The old `calcularPago` function
- The old `$('#precioRegularCrear, #descuentoBsCrear').on('input', ...)` handler
- The old `$('#btnGuardarPc').on('click', ...)` handler (the single-concept version)

These are the blocks around lines 2574-2644 in the current file.

---

### Task 8: Manual testing — end-to-end flow

**Files:**
- Test via browser at `/admin/posgrados/ofertas/{id}/detalle` → tab "Área Contable"

- [ ] **Step 1: Test happy path — register plan with 3 concepts**

1. Navigate to Área Contable tab
2. Click "Nueva Configuración"
3. Verify modal opens with plan selector and one empty concept row
4. Select a plan (not principal)
5. In the first row, select a concept → verify `precio_regular` auto-fills from principal plan
6. Click "Agregar Concepto" → verify new row appears
7. Select second concept, set n_cuotas = 6, descuento_bs = 50
8. Add third concept
9. Verify "Total a Pagar" shows correct sum
10. Click "Guardar Todo" → verify success toast and DataTable reloads with 3 new rows

- [ ] **Step 2: Test error — plan already registered**

1. Try to register the same plan again
2. Verify plan does NOT appear in the selector

- [ ] **Step 3: Test error — duplicate concept in same form**

1. Open modal, select plan
2. Add 2 rows, select same concept in both
3. Click "Guardar Todo"
4. Verify warning toast: "No puede repetir el mismo concepto"

- [ ] **Step 4: Test error — no principal plan**

1. If no principal plan concepts exist, open modal
2. Verify banner shows: "No se puede registrar: no existe un plan principal..."
3. Verify "Guardar Todo" button is disabled

- [ ] **Step 5: Test promotion badge**

1. Open modal, select a plan with `es_promocion = 1`
2. Verify "Promoción" badge appears next to plan name

- [ ] **Step 6: Test row removal**

1. Add 3 rows
2. Verify all 3 have delete buttons enabled
3. Remove 2 rows
4. Verify last row's delete button is disabled
5. Verify total recalculates correctly

- [ ] **Step 7: Test auto-calculation**

1. Set precio_regular = 500, descuento_bs = 100
2. Verify pago shows 400.00
3. Set descuento_bs = 600 (greater than precio)
4. Verify pago shows 0.00 (not negative)
