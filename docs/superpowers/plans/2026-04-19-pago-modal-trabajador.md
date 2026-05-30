# Pago Modal Trabajador - Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mostrar el nombre del trabajador autenticado en la cabecera del modal de pago y quitar el campo editable "Registrado por"

**Architecture:** Modificar el controlador EstudianteController para pasar los datos del trabajador actual a la vista, y actualizar el modal en detalle.blade.php para mostrar la información en la cabecera en lugar del campo desplegable.

**Tech Stack:** Laravel Blade, PHP, JavaScript

---

### Task 1: Modificar EstudianteController para pasar datos del trabajador

**Files:**
- Modify: `app/Http/Controllers/Admin/EstudianteController.php:25-47`

- [ ] **Step 1: Leer método verDetalle actual**

Leer líneas 25-47 del archivo para ver el método actual.

- [ ] **Step 2: Agregar obtención de datos del trabajador**

Reemplazar el método `verDetalle()` completo con:

```php
public function verDetalle($id)
{
    $estudiante = Estudiante::with([
        'persona.ciudad.departamento',
        'persona.estudios.grado_academico',
        'persona.estudios.profesion',
        'persona.estudios.universidad'
    ])->findOrFail($id);

    $inscripciones = Inscripcione::where('estudiante_id', $id)
        ->with([
            'ofertaAcademica.posgrado',
            'planesPago',
            'trabajador_cargo.cargo',
            'trabajador_cargo.sucursale.sede',
            'cuotas',
            'matriculaciones.modulo.docente.persona'
        ])
        ->orderBy('fecha_registro', 'desc')
        ->get();

    // Obtener datos del trabajador autenticado
    $trabajadorActual = null;
    if (auth()->check() && auth()->user()->persona) {
        $persona = auth()->user()->persona;
        $trabajador = $persona->trabajador;
        
        if ($trabajador) {
            $cargoPrincipal = $trabajador->cargos()
                ->where('estado', 'Vigente')
                ->orderBy('principal', 'desc')
                ->first();
            
            if ($cargoPrincipal) {
                $trabajadorActual = [
                    'id' => $cargoPrincipal->id,
                    'nombre' => $persona->nombres . ' ' . 
                               $persona->apellido_paterno . ' ' . 
                               $persona->apellido_materno,
                    'cargo' => $cargoPrincipal->cargo->nombre ?? 'Sin cargo',
                    'sucursal' => $cargoPrincipal->sucursale->nombre ?? '',
                    'sede' => $cargoPrincipal->sucursale->sede->nombre ?? ''
                ];
            }
        }
    }

    return view('admin.estudiantes.detalle', compact('estudiante', 'inscripciones', 'trabajadorActual'));
}
```

- [ ] **Step 3: Verificar que el código es válido**

Ejecutar: `php artisan route:list --path=admin/estudiantes`
Expected: Sin errores de sintaxis

---

### Task 2: Modificar vista detalle.blade.php - cabecera del modal

**Files:**
- Modify: `resources/views/admin/estudiantes/detalle.blade.php:1084-1098`

- [ ] **Step 1: Leer la cabecera actual del modal**

Leer líneas 1084-1089 para ver el código actual.

- [ ] **Step 2: Reemplazar la cabecera del modal**

Reemplazar:
```php
<div class="modal-header">
    <h5 class="modal-title"><i class="ri-money-dollar-circle-line"></i> Registrar Pago - Cuota
        #<span id="pago-cuota-numero"></span></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"
        aria-label="Close"></button>
</div>
```

Por:
```php
<div class="modal-header">
    <div>
        <h5 class="modal-title"><i class="ri-money-dollar-circle-line"></i> Registrar Pago - Cuota
            #<span id="pago-cuota-numero"></span></h5>
        @if($trabajadorActual)
        <small class="text-muted d-block">
            <i class="ri-user-line"></i> Registrado por: 
            <strong>{{ $trabajadorActual['nombre'] }}</strong> - 
            {{ $trabajadorActual['cargo'] }}
            @if($trabajadorActual['sucursal'])
                ({{ $trabajadorActual['sucursal'] }}
                @if($trabajadorActual['sede'])
                    - {{ $trabajadorActual['sede'] }}
                @endif)
            @endif
            )
        </small>
        @else
        <small class="text-danger d-block">
            <i class="ri-error-warning-line"></i> No se pudo identificar al trabajador
        </small>
        @endif
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal"
        aria-label="Close"></button>
</div>
```

---

### Task 3: Modificar vista detalle.blade.php - quitar campo select

**Files:**
- Modify: `resources/views/admin/estudiantes/detalle.blade.php:1116-1122`

- [ ] **Step 1: Leer el campo "Registrado por" actual**

Leer líneas 1116-1122 para ver el código actual.

- [ ] **Step 2: Reemplazar el campo select por campo oculto**

Reemplazar:
```php
<div class="col-md-12">
    <label class="form-label"><i class="ri-user-line"></i> Registrado por</label>
    <select class="form-select" id="pago-trabajador-cargo"
        name="trabajador_cargo_id" required>
        <option value="">Seleccionar...</option>
    </select>
</div>
```

Por:
```php
<input type="hidden" id="pago-trabajador-cargo" 
    name="trabajador_cargo_id" 
    value="{{ $trabajadorActual['id'] ?? '' }}">
```

---

### Task 4: Modificar JavaScript - quitar carga AJAX

**Files:**
- Modify: `resources/views/admin/estudiantes/detalle.blade.php:1291-1356`

- [ ] **Step 1: Leer el evento click del botón pagar**

Leer líneas 1291-1356 para ver el código actual.

- [ ] **Step 2: Simplificar el evento click**

Eliminar las líneas que cargan los cargos del usuario:
- Línea 1311-1316: `// Resetear select de trabajador` y `// Cargar cargos del usuario autenticado`

El código debe quedar simplificado, eliminando:
```javascript
// Resetear select de trabajador
const trabajadorSelect = document.getElementById('pago-trabajador-cargo');
trabajadorSelect.innerHTML = '<option value="">Cargando...</option>';

// Cargar cargos del usuario autenticado
await cargarCargosUsuario();
```

- [ ] **Step 3: Actualizar la validación del formulario**

Eliminar la validación de líneas 1366-1370:
```javascript
// Validar campos requeridos
const trabajadorCargoId = document.getElementById('pago-trabajador-cargo').value;
if (!trabajadorCargoId) {
    alert('Debe seleccionar quién registra el pago.');
    return;
}
```

El campo ya no necesita validación porque es un campo oculto con valor por defecto.

---

### Task 5: Verificar la implementación

**Files:**
- Test: Acceder a la vista detalle de un estudiante

- [ ] **Step 1: Probar la vista**

Acceder a: `/admin/estudiantes/1/detalle?tab=contable`
Expected: La página carga sin errores

- [ ] **Step 2: Abrir modal de pago**

Hacer clic en el botón "Pagar" de una cuota
Expected: 
- La cabecera muestra "Registrado por: [Nombre] - [Cargo]"
- No hay campo desplegable "Registrado por"

- [ ] **Step 3: Registrar un pago**

Llenar el formulario y enviar
Expected: El pago se registra correctamente

---