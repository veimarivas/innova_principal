# Subir Documentos Estudiante Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Agregar funcionalidad en el tab "Documentos" de la vista detalle de estudiante para permitir subir, visualizar y verificar documentos personales y de formación académica.

**Architecture:** Sistema de gestión de documentos con tres endpoints: upload, verify y visualize. La vista carga los documentos junto con los datos del estudiante y usa JavaScript (Fetch API) para las operaciones asíncronas.

**Tech Stack:** Laravel Blade, PHP, JavaScript (Fetch API), Laravel Storage

---

## Estructura de Archivos

### Archivos a modificar:
- `routes/web.php` - Agregar rutas para documentos
- `app/Http/Controllers/Admin/EstudianteController.php` - Agregar métodos para documentos
- `resources/views/admin/estudiantes/detalle.blade.php` - Modificar tab Documentos

---

## Tareas

### Task 1: Agregar rutas para documentos

**Files:**
- Modify: `routes/web.php:325-345`

- [ ] **Step 1: Agregar rutas de documentos al grupo de estudiantes**

Agregar dentro del Route::prefix('admin/estudiantes')->group(function () {

```php
Route::post('/{id}/documentos/subir', [EstudianteController::class, 'subirDocumento'])->name('documentos.subir');
Route::post('/{id}/documentos/verificar', [EstudianteController::class, 'verificarDocumento'])->name('documentos.verificar');
Route::get('/{id}/documentos/visualizar', [EstudianteController::class, 'visualizarDocumento'])->name('documentos.visualizar');
```

Ejecutar: `php artisan route:list | grep documentos` para verificar rutas creadas

---

### Task 2: Agregar métodos en EstudianteController

**Files:**
- Modify: `app/Http/Controllers/Admin/EstudianteController.php`

- [ ] **Step 1: Agregar método subirDocumento**

Agregar método en EstudianteController.php después del método verDetalle():

```php
public function subirDocumento(Request $request, $id)
{
    $request->validate([
        'tipo_documento' => 'required|in:fotocopia_carnet,fotocopia_certificado_nacimiento,documento_academico,documento_provision_nacional',
        'archivo' => 'required|file|mimes:pdf,png,jpg,jpeg|max:2048',
    ]);

    $estudiante = Estudiante::findOrFail($id);
    $persona = $estudiante->persona;
    $tipoDocumento = $request->tipo_documento;

    // Determinar si es documento personal o académico
    $esDocumentoAcademico = in_array($tipoDocumento, ['documento_academico', 'documento_provision_nacional']);

    if ($esDocumentoAcademico) {
        $estudio = $persona->estudios()->where('principal', 1)->orderBy('id', 'asc')->first();
        if (!$estudio) {
            $estudio = $persona->estudios()->orderBy('id', 'asc')->first();
        }
        if (!$estudio) {
            return response()->json(['success' => false, 'error' => 'No se encontró estudio registrado para este estudiante.'], 400);
        }
    }

    // Guardar archivo
    $archivo = $request->file('archivo');
    $nombreArchivo = $tipoDocumento . '_' . $estudiante->id . '_' . time() . '.' . $archivo->getClientOriginalExtension();
    $ruta = $archivo->storeAs('documentos', $nombreArchivo, 'public');

    try {
        if ($esDocumentoAcademico) {
            $campoArchivo = $tipoDocumento === 'documento_academico' ? 'documento_academico' : 'documento_provision_nacional';
            $estudio->update([$campoArchivo => $ruta]);
        } else {
            $campoVerificacion = $tipoDocumento === 'fotocopia_carnet' ? 'carnet_verificado' : 'certificado_nacimiento_verificado';
            $persona->update([
                $tipoDocumento => $ruta,
                $campoVerificacion => 0,
            ]);
        }
        return response()->json(['success' => true, 'ruta' => $ruta, 'mensaje' => 'Documento subido exitosamente']);
    } catch (\Exception $e) {
        Storage::disk('public')->delete($ruta);
        return response()->json(['success' => false, 'error' => 'Error al guardar en la base de datos.'], 500);
    }
}
```

- [ ] **Step 2: Agregar método verificarDocumento**

```php
public function verificarDocumento(Request $request, $id)
{
    $request->validate([
        'tipo_documento' => 'required|in:fotocopia_carnet,fotocopia_certificado_nacimiento,documento_academico,documento_provision_nacional',
    ]);

    $estudiante = Estudiante::findOrFail($id);
    $persona = $estudiante->persona;
    $tipoDocumento = $request->tipo_documento;

    $esDocumentoAcademico = in_array($tipoDocumento, ['documento_academico', 'documento_provision_nacional']);

    try {
        if ($esDocumentoAcademico) {
            $estudio = $persona->estudios()->where('principal', 1)->orderBy('id', 'asc')->first();
            if (!$estudio) {
                $estudio = $persona->estudios()->orderBy('id', 'asc')->first();
            }
            if (!$estudio) {
                return response()->json(['success' => false, 'error' => 'No se encontró estudio registrado.'], 400);
            }
            $campoVerificacion = $tipoDocumento === 'documento_academico' ? 'documento_academico_verificado' : 'documento_provision_verificado';
            $estudio->update([$campoVerificacion => 1]);
        } else {
            $campoVerificacion = $tipoDocumento === 'fotocopia_carnet' ? 'carnet_verificado' : 'certificado_nacimiento_verificado';
            $persona->update([$campoVerificacion => 1]);
        }
        return response()->json(['success' => true, 'mensaje' => 'Documento verificado']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => 'Error al verificar el documento.'], 500);
    }
}
```

- [ ] **Step 3: Agregar método visualizarDocumento**

```php
public function visualizarDocumento(Request $request, $id)
{
    $request->validate([
        'tipo' => 'required|in:fotocopia_carnet,fotocopia_certificado_nacimiento,documento_academico,documento_provision_nacional',
    ]);

    $estudiante = Estudiante::findOrFail($id);
    $persona = $estudiante->persona;
    $tipo = $request->tipo;

    $esDocumentoAcademico = in_array($tipo, ['documento_academico', 'documento_provision_nacional']);

    $rutaArchivo = null;

    if ($esDocumentoAcademico) {
        $estudio = $persona->estudios()->where('principal', 1)->orderBy('id', 'asc')->first();
        if (!$estudio) {
            $estudio = $persona->estudios()->orderBy('id', 'asc')->first();
        }
        if ($estudio) {
            $campoArchivo = $tipo === 'documento_academico' ? 'documento_academico' : 'documento_provision_nacional';
            $rutaArchivo = $estudio->$campoArchivo;
        }
    } else {
        $rutaArchivo = $persona->$tipo;
    }

    if (!$rutaArchivo || !Storage::disk('public')->exists($rutaArchivo)) {
        return response()->json(['success' => false, 'error' => 'Documento no encontrado.'], 404);
    }

    $archivo = Storage::disk('public')->get($rutaArchivo);
    $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION);
    $mimeType = match ($extension) {
        'pdf' => 'application/pdf',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        default => 'application/octet-stream',
    };

    return response($archivo, 200, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . basename($rutaArchivo) . '"',
    ]);
}
```

---

### Task 3: Modificar método verDetalle para incluir datos de documentos

**Files:**
- Modify: `app/Http/Controllers/Admin/EstudianteController.php:44-92`

- [ ] **Step 1: Agregar carga de estudios en verDetalle**

En el método verDetalle(), agregar 'persona.estudios' al array de with:

```php
$estudiante = Estudiante::with([
    'persona.ciudad.departamento',
    'persona.estudios' => function ($q) {
        $q->orderBy('id', 'asc');
    },
    'persona.estudios.grado_academico',
    'persona.estudios.profesion',
    'persona.estudios.universidad'
])->findOrFail($id);
```

- [ ] **Step 2: Agregar variable de estudio principal**

Agregar después de obtener el estudiante:

```php
$estudioPrincipal = null;
if ($estudiante->persona && $estudiante->persona->estudios) {
    $estudioPrincipal = $estudiante->persona->estudios->where('principal', 1)->first();
    if (!$estudioPrincipal) {
        $estudioPrincipal = $estudiante->persona->estudios->first();
    }
}
```

- [ ] **Step 3: Agregar estudioPrincipal al compact**

Cambiar la línea return view:
```php
return view('admin.estudiantes.detalle', compact('estudiante', 'inscripciones', 'trabajadorActual', 'estudioPrincipal'));
```

---

### Task 4: Modificar vista detalle.blade.php - tab Documentos

**Files:**
- Modify: `resources/views/admin/estudiantes/detalle.blade.php:711-757`

- [ ] **Step 1: Reemplazar contenido del tab Documentos**

Reemplazar todo el div con id="tab-documentos" con la nueva estructura que incluye:
- Sección de Documentos Personales (fotocopia_carnet, fotocopia_certificado_nacimiento)
- Sección de Documentos de Formación Académica (documento_academico, documento_provision_nacional)
- Botones de subir/visualizar según estado
- Botones de verificación
- Estilos para badges

Estructura HTML a implementar:

```html
<div class="est-tabs-body" id="tab-documentos">
    <h5 class="mb-3"><i class="ri-user-line"></i> Documentos Personales</h5>
    <div class="row g-3 mb-4">
        @php
        $docsPersonales = [
            [
                'nombre' => 'Fotocopia Carnet',
                'campo' => 'fotocopia_carnet',
                'verificacion' => 'carnet_verificado',
                'icono' => 'ri-id-card-line',
            ],
            [
                'nombre' => 'Fotocopia Certificado Nacimiento',
                'campo' => 'fotocopia_certificado_nacimiento',
                'verificacion' => 'certificado_nacimiento_verificado',
                'icono' => 'ri-file-paper-line',
            ],
        ];
        @endphp
        @foreach ($docsPersonales as $doc)
        <div class="col-md-6 col-lg-4">
            <div class="doc-card">
                <div class="doc-icon" style="background: var(--est-primary-light); color: var(--est-primary);">
                    <i class="{{ $doc['icono'] }}"></i>
                </div>
                <div class="doc-info">
                    <div class="doc-name">{{ $doc['nombre'] }}</div>
                    <div class="doc-meta">{{ $persona->{$doc['campo']} ? 'Subido' : 'Sin subir' }}</div>
                </div>
                <div class="doc-status d-flex gap-2">
                    @if ($persona->{$doc['campo']})
                        <a href="{{ route('admin.estudiantes.documentos.visualizar', [$estudiante->id, 'tipo' => $doc['campo']]) }}" 
                           target="_blank" class="btn btn-sm btn-action btn-action-view" title="Visualizar">
                            <i class="ri-eye-line"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-action btn-action-edit btn-verificar-doc" 
                                data-id="{{ $estudiante->id }}" 
                                data-tipo="{{ $doc['campo'] }}"
                                data-actual="{{ $persona->{$doc['verificacion'] }}"
                                title="{{ $persona->{$doc['verificacion']} ? 'Verificado' : 'Verificar' }}">
                            <i class="ri-checkbox-circle-line"></i>
                        </button>
                        <span class="estado-badge-est {{ $persona->{$doc['verificacion']} ? 'verificado' : 'pendiente' }}">
                            {{ $persona->{$doc['verificacion']} ? 'Verificado' : 'Pendiente' }}
                        </span>
                    @else
                        <label class="btn btn-sm btn-action btn-action-upload mb-0" title="Subir">
                            <i class="ri-upload-line"></i>
                            <input type="file" class="d-none btn-subir-doc" 
                                   data-id="{{ $estudiante->id }}" 
                                   data-tipo="{{ $doc['campo'] }}"
                                   accept=".pdf,.png,.jpg,.jpeg">
                        </label>
                        <span class="estado-badge-est sin-subir">Sin subir</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <h5 class="mb-3"><i class="ri-graduation-cap-line"></i> Formación Académica</h5>
    @if($estudioPrincipal)
    <div class="row g-3">
        @php
        $docsAcademicos = [
            [
                'nombre' => 'Documento Académico',
                'campo' => 'documento_academico',
                'verificacion' => 'documento_academico_verificado',
                'icono' => 'ri-file-text-line',
            ],
            [
                'nombre' => 'Provisión Nacional',
                'campo' => 'documento_provision_nacional',
                'verificacion' => 'documento_provision_verificado',
                'icono' => 'ri-government-line',
            ],
        ];
        @endphp
        @foreach ($docsAcademicos as $doc)
        <div class="col-md-6 col-lg-4">
            <div class="doc-card">
                <div class="doc-icon" style="background: var(--est-info-light); color: var(--est-info);">
                    <i class="{{ $doc['icono'] }}"></i>
                </div>
                <div class="doc-info">
                    <div class="doc-name">{{ $doc['nombre'] }}</div>
                    <div class="doc-meta">{{ $estudioPrincipal->{$doc['campo']} ? 'Subido' : 'Sin subir' }}</div>
                </div>
                <div class="doc-status d-flex gap-2">
                    @if ($estudioPrincipal->{$doc['campo']})
                        <a href="{{ route('admin.estudiantes.documentos.visualizar', [$estudiante->id, 'tipo' => $doc['campo']]) }}" 
                           target="_blank" class="btn btn-sm btn-action btn-action-view" title="Visualizar">
                            <i class="ri-eye-line"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-action btn-action-edit btn-verificar-doc" 
                                data-id="{{ $estudiante->id }}" 
                                data-tipo="{{ $doc['campo'] }}"
                                data-actual="{{ $estudioPrincipal->{$doc['verificacion'] }}"
                                title="{{ $estudioPrincipal->{$doc['verificacion']} ? 'Verificado' : 'Verificar' }}">
                            <i class="ri-checkbox-circle-line"></i>
                        </button>
                        <span class="estado-badge-est {{ $estudioPrincipal->{$doc['verificacion']} ? 'verificado' : 'pendiente' }}">
                            {{ $estudioPrincipal->{$doc['verificacion']} ? 'Verificado' : 'Pendiente' }}
                        </span>
                    @else
                        <label class="btn btn-sm btn-action btn-action-upload mb-0" title="Subir">
                            <i class="ri-upload-line"></i>
                            <input type="file" class="d-none btn-subir-doc" 
                                   data-id="{{ $estudiante->id }}" 
                                   data-tipo="{{ $doc['campo'] }}"
                                   accept=".pdf,.png,.jpg,.jpeg">
                        </label>
                        <span class="estado-badge-est sin-subir">Sin subir</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="est-empty-state">
        <i class="ri-book-line"></i>
        <h5>Sin estudios registrados</h5>
        <p>El estudiante no tiene estudios registrados</p>
    </div>
    @endif
</div>
```

---

### Task 5: Agregar JavaScript para operaciones AJAX

**Files:**
- Modify: `resources/views/admin/estudiantes/detalle.blade.php` (agregar al final del archivo en @section('js') o crear nueva sección)

- [ ] **Step 1: Agregar script para subir documentos**

```javascript
// Subir documento
document.querySelectorAll('.btn-subir-doc').forEach(input => {
    input.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const tipoDocumento = this.dataset.tipo;
        const estudianteId = this.dataset.id;

        // Validar tamaño (2MB = 2048KB)
        if (file.size > 2048 * 1024) {
            alert('El archivo excede el tamaño máximo de 2MB.');
            return;
        }

        // Validar tipo
        const tiposPermitidos = ['application/pdf', 'image/png', 'image/jpeg'];
        if (!tiposPermitidos.includes(file.type)) {
            alert('Tipo de archivo no permitido. Solo PDF, PNG, JPG, JPEG.');
            return;
        }

        const formData = new FormData();
        formData.append('tipo_documento', tipoDocumento);
        formData.append('archivo', file);

        try {
            const response = await fetch(`/admin/estudiantes/${estudianteId}/documentos/subir`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Error al subir el documento');
            }
        } catch (error) {
            alert('Error al subir el documento');
        }
    });
});
```

- [ ] **Step 2: Agregar script para verificar documentos**

```javascript
// Verificar documento
document.querySelectorAll('.btn-verificar-doc').forEach(btn => {
    btn.addEventListener('click', async function() {
        const tipoDocumento = this.dataset.tipo;
        const estudianteId = this.dataset.id;

        try {
            const response = await fetch(`/admin/estudiantes/${estudianteId}/documentos/verificar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ tipo_documento: tipoDocumento })
            });

            const data = await response.json();

            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Error al verificar el documento');
            }
        } catch (error) {
            alert('Error al verificar el documento');
        }
    });
});
```

- [ ] **Step 3: Verificar que existe meta csrf-token en el layout**

Verificar que en el layout master exista:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

---

### Task 6: Verificar funcionamiento

**Files:**
- Test: `routes/web.php`, `EstudianteController.php`, `detalle.blade.php`

- [ ] **Step 1: Verificar rutas**

Ejecutar: `php artisan route:list | grep documentos`

Expected: Mostrar las tres rutas de documentos

- [ ] **Step 2: Probar endpoint de visualización**

Acceder a: `/admin/estudiantes/1/documentos/visualizar?tipo=fotocopia_carnet` (con estudiante que tenga documento)

- [ ] **Step 3: Probar vista de documentos**

Navegar a: `/admin/estudiantes/{id}/detalle` y hacer clic en tab "Documentos"

---

## Resumen de Archivos a Modificar

| Archivo | Acción | Descripción |
|---------|--------|-------------|
| `routes/web.php` | Modificar | Agregar 3 rutas para documentos |
| `app/Http/Controllers/Admin/EstudianteController.php` | Modificar | Agregar métodos: subirDocumento, verificarDocumento, visualizarDocumento; modificar verDetalle |
| `resources/views/admin/estudiantes/detalle.blade.php` | Modificar | Rediseñar tab Documentos con botones de subir/visualizar/verificar; agregar JavaScript |

---

## Spec Coverage Check

- [x] Documentos personales (fotocopia_carnet, fotocopia_certificado_nacimiento) - Task 4
- [x] Documentos académicos (documento_academico, documento_provision_nacional) - Task 4
- [x] Estudio principal con principal=1 - Task 3
- [x] Mensaje sin estudios registrados - Task 4
- [x] Validación: PDF, PNG, JPG, JPEG, 2MB - Task 2
- [x] Endpoint subir - Task 2
- [x] Endpoint verificar - Task 2
- [x] Endpoint visualizar - Task 2
- [x] Carga inicial en verDetalle - Task 3
- [x] JavaScript con Fetch API - Task 5
- [x] Manejo de errores - Task 2, Task 5