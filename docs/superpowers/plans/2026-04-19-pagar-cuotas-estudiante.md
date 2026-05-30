# Pago de Cuotas Estudiante Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Agregar botón "Pagar" en cada cuota del tab Contable que abre modal para registrar pago.

**Architecture:** 
1. Botón en cada fila de cuota (visible si estado Pendiente/Vencido)
2. Modal con formulario: monto, fecha, método
3. Endpoint POST para registrar pago en backend
4. Actualizar cuota y redirigir

**Tech Stack:** Laravel Blade, Alpine.js, jQuery (existing), Controller

---

## Task 1: Agregar ruta POST para pagar cuota

**Files:**
- Modify: `routes/web.php:256-270`

- [ ] **Step 1: Agregar ruta**

Añadir dentro del grupo `Route::prefix('admin/estudiantes')`:

```php
Route::post('/cuota/{cuota}/pagar', [EstudianteController::class, 'registrarPago'])->name('registrarPago');
```

---

## Task 2: Agregar método registrarPago en controller

**Files:**
- Modify: `app/Http/Controllers/Admin/EstudianteController.php:1-205`

- [ ] **Step 1: Añadir método al final del controller**

```php
public function registrarPago(Request $request, $cuotaId)
{
    $cuota = Cuota::find($cuotaId);
    
    if (!$cuota) {
        return response()->json(['success' => false, 'message' => 'Cuota no encontrada.'], 404);
    }
    
    if ($cuota->estado === 'Pagado') {
        return response()->json(['success' => false, 'message' => 'La cuota ya está pagada.'], 409);
    }
    
    $validator = Validator::make($request->all(), [
        'monto' => 'required|numeric|min:0.01',
        'fecha_pago' => 'required|date|before_or_equal:today',
    ]);
    
    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }
    
    $monto = $request->monto;
    $fechaPago = $request->fecha_pago;
    $metodo = $request->metodo;
    
    $montoPendiente = $cuota->pago_pendiente_bs ?? $cuota->monto_bs;
    
    if ($monto >= $montoPendiente) {
        $cuota->update([
            'estado' => 'Pagado',
            'pago_pendiente_bs' => 0,
            'fecha_pago' => $fechaPago,
            'metodo_pago' => $metodo,
        ]);
    } else {
        $cuota->update([
            'estado' => 'Parcial',
            'pago_pendiente_bs' => $montoPendiente - $monto,
            'fecha_pago' => $fechaPago,
            'metodo_pago' => $metodo,
        ]);
    }
    
    $estudianteId = $cuota->inscripcione->estudiante_id;
    
    return response()->json([
        'success' => true,
        'message' => 'Pago registrado correctamente.',
        'redirect' => "/admin/estudiantes/{$estudianteId}/detalle?tab=contable"
    ]);
}
```

- [ ] **Step 2: Verificar imports al inicio del archivo**

Ya existen:
- `use App\Models\Cuota;`
- `use Illuminate\Http\Request;`

No agregar nada.

---

## Task 3: Agregar botón Pagar en tabla de cuotas

**Files:**
- Modify: `resources/views/admin/estudiantes/detalle.blade.php:1019-1033`

- [ ] **Step 1: Reemplazar columna Pago en tabla de cuotas**

Buscar (líneas ~1027-1031):
```blade
<td>{{ $cuota->fecha_pago ? \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') : '—' }}</td>
<td><span class="estado-badge-est ...
```

Reemplazar por:
```blade
<td>
@if(in_array($cuota->estado, ['Pendiente', 'Vencido']))
    <button type="button" class="btn btn-sm btn-outline-primary" onclick="abrirModalPago({{ $cuota->id }}, '{{ $cuota->nombre }}', {{ $cuota->pago_pendiente_bs ?? $cuota->monto_bs }})">
        <i class="ri-money-dollar-circle-line"></i> Pagar
    </button>
@else
    {{ $cuota->fecha_pago ? \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') : '—' }}
@endif
</td>
```

---

## Task 4: Agregar modal de pago

**Files:**
- Modify: `resources/views/admin/estudiantes/detalle.blade.php:1044` (antes del cierre del tab-contable)

- [ ] **Step 1: Agregar HTML del modal**

Añadir antes de `</div>` que cierra `tab-contable` (línea ~1052):

```blade
<!-- Modal Registrar Pago -->
<div class="modal fade" id="modalPagarCuota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Pago - Cuota #<span id="pago-cuota-numero"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPagarCuota">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cuota</label>
                        <input type="text" class="form-control" id="pago-cuota-nombre" readonly>
                        <input type="hidden" id="pago-cuota-id" name="cuota_id">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto (Bs.)</label>
                        <input type="number" class="form-control" id="pago-monto" name="monto" step="0.01" min="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de Pago</label>
                        <input type="date" class="form-control" id="pago-fecha" name="fecha_pago" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Método</label>
                        <select class="form-select" id="pago-metodo" name="metodo" required>
                            <option value="">Seleccionar...</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="QR">QR</option>
                            <option value="Parcial">Parcial</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>
```

---

## Task 5: Agregar JavaScript para modal y submit

**Files:**
- Modify: `resources/views/admin/estudiantes/detalle.blade.php:1057-1097`

- [ ] **Step 1: Agregar funciones JavaScript**

Añadir dentro de `<script>` (antes del cierre `</script>`):

```javascript
// Variables globales para el modal
let modalPagoInstance = null;

function abrirModalPago(cuotaId, cuotaNombre, montoPendiente) {
    document.getElementById('pago-cuota-id').value = cuotaId;
    document.getElementById('pago-cuota-nombre').value = cuotaNombre;
    document.getElementById('pago-cuota-numero').textContent = cuotaNombre;
    document.getElementById('pago-monto').value = montoPendiente.toFixed(2);
    document.getElementById('pago-fecha').value = new Date().toISOString().split('T')[0];
    document.getElementById('pago-metodo').value = '';
    
    if (!modalPagoInstance) {
        modalPagoInstance = new bootstrap.Modal(document.getElementById('modalPagarCuota'));
    }
    modalPagoInstance.show();
}

document.getElementById('formPagarCuota').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const cuotaId = document.getElementById('pago-cuota-id').value;
    const formData = {
        monto: parseFloat(document.getElementById('pago-monto').value),
        fecha_pago: document.getElementById('pago-fecha').value,
        metodo: document.getElementById('pago-metodo').value
    };
    
    fetch(`/admin/estudiantes/cuota/${cuotaId}/pagar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            modalPagoInstance.hide();
            // Recargar página para ver cambios
            window.location.href = data.redirect;
        } else {
            alert(data.message || 'Error al registrar pago');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al registrar pago');
    });
});
```

---

## Task 6: Verificar funcionamientoy

**Files:**
- Test manually

- [ ] **Step 1: Test flujo completo**

1. Ir a_admin/estudiantes/{id}/detalle
2. Click en tab "Contable"
3. Verificar que hay cuotas pendientes
4. Click botón "Pagar"
5. Modal abre con datos correctos
6. Cambiar fecha/método si desired
7. Click "Registrar Pago"
8. Verificar redirect y cuota marcada como Pagada

---

## Resumen de archivos

| Archivo | Acción |
|---------|-------|
| `routes/web.php` | +1 ruta POST |
| `app/Http/Controllers/Admin/EstudianteController.php` | +1 método |
| `resources/views/admin/estudiantes/detalle.blade.php` | +botón, +modal, +JS |

Total: 3 archivos modificados