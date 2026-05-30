# Design Spec: Mejoras en Modal de Edición de Módulo

**Date:** 2026-04-05
**File:** `resources/views/admin/ofertas-academicas/detalle.blade.php`
**Controller:** `app/Http/Controllers/ModuloController.php`
**Routes:** `routes/web.php`

---

## Problem Statement

1. **Color feedback:** Al editar un módulo, el selector de color no muestra feedback visual claro al usuario. El color se guarda correctamente pero no hay indicación visual del color seleccionado.

2. **Docente no encontrado:** Al buscar un docente por carnet y no encontrarlo, solo se muestra un toast de advertencia. No existe flujo para registrar un nuevo docente y asignarlo al módulo.

---

## Architecture

### Component 1: Color Visual Feedback

**Files modified:** `detalle.blade.php` (CSS + HTML + JS)

**Changes:**
- Agregar barra de color en el header del modal `modalEditarModulo` usando la clase existente `modal-header-modulo` y `modulo-color-bar`
- Agregar caja de preview de color (60x40px) junto al input color picker
- JavaScript listener en el evento `input` del color picker para actualizar ambos elementos en tiempo real
- Al abrir el modal, inicializar ambos elementos con el color del módulo existente

**CSS additions:**
```css
.color-preview-box {
    width: 60px;
    height: 40px;
    border-radius: 8px;
    border: 2px solid var(--d-card-border);
    display: inline-block;
    vertical-align: middle;
    margin-left: 0.5rem;
    transition: background 0.15s;
}
```

**HTML changes (modal header):**
- Agregar clase `modal-header-modulo` al `<div class="modal-header">`
- Insertar `<div class="modulo-color-bar" id="editModuloColorBar"></div>` dentro del header

**HTML changes (color field):**
- Envolver el input color y el preview en un flex container
- Agregar `<div id="editModuloColorPreview" class="color-preview-box"></div>`

**JS changes:**
```js
// Al abrir el modal (btn-edit-modulo click):
$('#editModuloColorBar').css('background', mod.color || '#6366f1');
$('#editModuloColorPreview').css('background', mod.color || '#6366f1');

// Listener en tiempo real:
$('#editModuloColor').on('input', function() {
    const color = this.value;
    $('#editModuloColorBar').css('background', color);
    $('#editModuloColorPreview').css('background', color);
});
```

---

### Component 2: Docente Registration Flow

**Files modified:**
- `detalle.blade.php` - 2 nuevos modales + JS
- `ModuloController.php` - método `registrarDocente`
- `routes/web.php` - nueva ruta

#### Modal 1: Confirmación de Docente No Encontrado

**ID:** `modalConfirmarRegistroDocente`

**Content:**
- Icono de advertencia
- Título: "Docente no encontrado"
- Mensaje dinámico según el caso:
  - `not_found`: "No se encontr"
  - `persona_encontrada`: "Se encontró a [nombre] pero no está registrado como docente."
- Pregunta: "¿Desea registrar este docente y asignarlo al módulo?"
- Botones: "Cancelar" (secundario), "Registrar Docente" (primario)

#### Modal 2: Registro de Docente

**ID:** `modalRegistroDocente`

**Content:**
- Título: "Registrar Nuevo Docente"
- Formulario con campos:
  - Carnet (readonly si persona encontrada, editable si not_found)
  - Nombres (pre-llenado si persona encontrada)
  - Apellido Paterno (pre-llenado si persona encontrada)
  - Apellido Materno (pre-llenado si persona encontrada)
  - Correo electrónico
  - Celular
- Botón: "Registrar y Asignar"
- Hidden field: `persona_id` (si ya existe la persona)

#### Backend: Registrar Docente

**Route:** `POST /admin/posgrados/docentes/registrar`
**Name:** `admin.posgrads.docentes.registrar`
**Controller:** `ModuloController@registrarDocente`

**Logic:**
1. Validar campos requeridos (carnet, nombres, apellido_paterno)
2. Si `persona_id` viene en el request, usar esa persona existente
3. Si no, crear nueva Persona con los datos del formulario
4. Crear registro Docente asociado a la persona
5. Retornar: `{ success: true, docente: { id, persona: { nombres, apellido_paterno, apellido_materno, carnet } } }`

#### JavaScript Flow

```
btnBuscarDocenteModulo click
  → AJAX POST buscar-docente
  → Response handling:
    → es_docente === true:
        → Mostrar preview del docente (comportamiento actual)
    → persona_encontrada === true (persona existe, no es docente):
        → Guardar datos en variable temporal
        → Mostrar modalConfirmarRegistroDocente con mensaje personalizado
        → Si usuario confirma:
            → Pre-llenar modalRegistroDocente con datos de la persona
            → Mostrar modalRegistroDocente
    → not_found === true (persona no existe):
        → Guardar carnet en variable temporal
        → Mostrar modalConfirmarRegistroDocente
        → Si usuario confirma:
            → Pre-llenar solo carnet en modalRegistroDocente
            → Mostrar modalRegistroDocente

btnRegistrarYAsignarDocente click
  → Validar formulario
  → AJAX POST registrar-docente
  → Si éxito:
      → Guardar docente_id en #editModuloDocenteId
      → Mostrar preview del docente en modalEditarModulo
      → Cerrar modalRegistroDocente
      → Toast: "Docente registrado y asignado al módulo correctamente"
  → Si error:
      → Toast con mensaje de error
```

---

## Data Flow

```
[Buscar Docente por Carnet]
        |
        v
[Backend: buscarDocente]
        |
   +----+----+
   |         |
   v         v
[es_docente] [not_found / persona_encontrada]
   |              |
   v              v
[Mostrar    [Modal Confirmación]
 preview]         |
             +----+----+
             |         |
             v         v
         [Cancelar] [Registrar]
                        |
                        v
               [Modal Registro Docente]
                        |
                        v
               [Backend: registrarDocente]
                        |
                        v
               [Actualizar preview + toast]
```

---

## Error Handling

1. **Búsqueda de docente fallida:** Toast de error genérico "Error al buscar docente"
2. **Registro de docente fallido:** Toast con mensaje de error del backend
3. **Validación de formulario:** Resaltar campos requeridos vacíos, toast informativo
4. **Carnet duplicado:** El backend debe validar y retornar error específico
5. **Campos obligatorios:** carnet, nombres, apellido_paterno son requeridos

---

## Testing

**Manual testing checklist:**
- [ ] Al abrir modal de edición, el color se muestra correctamente en barra y preview
- [ ] Al cambiar el color picker, ambos elementos se actualizan en tiempo real
- [ ] Al guardar el módulo, el color se persiste correctamente
- [ ] Al buscar docente existente, se muestra el preview (comportamiento actual)
- [ ] Al buscar carnet no encontrado, aparece modal de confirmación
- [ ] Al buscar persona existente no docente, aparece modal con nombre de la persona
- [ ] Al confirmar registro, se abre modal con campos pre-llenados correctamente
- [ ] Al registrar nuevo docente, se crea persona + docente y se asigna al módulo
- [ ] Toast de confirmación muestra mensaje correcto
- [ ] El sidebar se actualiza con el nombre del nuevo docente

---

## Files Changed Summary

| File | Changes |
|------|---------|
| `detalle.blade.php` | CSS: +10 líneas, HTML: +2 modales (~80 líneas), JS: +120 líneas |
| `ModuloController.php` | +1 método `registrarDocente` (~40 líneas) |
| `routes/web.php` | +1 ruta POST |
