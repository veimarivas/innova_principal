# Diseño: Fix Asignar Horario — Sesiones y Guardado

**Fecha:** 2026-04-05
**Autor:** Asistente IA

## Problema

En `admin/ofertas-academicas/detalle.blade.php`, al asignar horarios a un módulo:

1. **Filas insuficientes:** Cuando no hay horarios registrados, solo se muestra 1 fila vacía en lugar de mostrar `ofertas_academicas.cantidad_sesiones` filas.
2. **Botón Guardar no funciona:** Al hacer clic en "Guardar Todas" no se ejecuta ninguna acción visible.

## Causas raíz

### Problema 1: Lógica de filas (`openAsignarHorario`, línea ~1844)

```js
const rowsToShow = Math.max(horariosCount, 1);
```

Cuando `horariosCount === 0`, `rowsToShow` es 1. Solo se renderiza 1 fila vacía sin importar el valor de `cantidad_sesiones`.

### Problema 2: Handler de guardado (`#btnGuardarAsignarHorario`, línea ~1870)

- `$.when.apply($, promises)` tiene un comportamiento inconsistente con un solo elemento en el array — jQuery no lo envuelve correctamente como deferred array.
- La validación con `hasError` usa `return false` dentro de `.each()` que rompe el loop pero no impide que se dispare `$.when` si ya se agregaron promesas válidas antes del error.

## Solución

### Fix 1: Mostrar cantidad_sesiones filas

Cambiar la línea:
```js
const rowsToShow = Math.max(horariosCount, 1);
```
Por:
```js
const rowsToShow = CANTIDAD_SESIONES;
```

El loop `for (let i = 0; i < rowsToShow; i++)` ya itera correctamente: para los primeros `horariosCount` índices encuentra datos existentes (`horarios[i]`), y para el resto `h` es `null` y `addSesionRow()` recibe valores vacíos, renderizando filas editables.

### Fix 2: Reescribir handler de guardado

Separar validación de ejecución:

1. **Primera pasada:** iterar todas las filas, validar que tengan fecha, hora_inicio y hora_fin. Si alguna falla, mostrar toast y abortar sin hacer AJAX.
2. **Segunda pasada:** construir el array de promesas solo si todas las filas son válidas.
3. **Ejecución:** usar `Promise.all(promises)` en lugar de `$.when.apply($, promises)` para evitar la inconsistencia de jQuery con arrays de un elemento.

Pseudocódigo:
```js
$('#btnGuardarAsignarHorario').on('click', function() {
    const rows = $('#sesionesRowsContainer .sesion-row');
    const trabajadorId = $('#asigTrabajadorId').val() || null;
    
    // 1. Validate all rows first
    let valid = true;
    rows.each(function() {
        const fecha = $(this).find('.sesion-fecha').val();
        const inicio = $(this).find('.sesion-inicio').val();
        const fin = $(this).find('.sesion-fin').val();
        if (!fecha || !inicio || !fin) { valid = false; return false; }
    });
    
    if (!valid) {
        toast('warning', 'Complete fecha, hora inicio y hora fin en todas las filas.');
        return;
    }
    
    if (rows.length === 0) {
        toast('warning', 'No hay sesiones para registrar.');
        return;
    }
    
    // 2. Build promises
    const promises = [];
    rows.each(function() {
        const horarioId = $(this).data('horario-id');
        const fecha = $(this).find('.sesion-fecha').val();
        const inicio = $(this).find('.sesion-inicio').val();
        const fin = $(this).find('.sesion-fin').val();
        
        if (horarioId) {
            promises.push($.ajax({ url: '/admin/posgrado/horarios/' + horarioId, type: 'PUT', data: { _token: CSRF, fecha, hora_inicio: inicio, hora_fin: fin, trabajadores_cargo_id: trabajadorId, estado: 'Confirmado' } }));
        } else {
            promises.push($.ajax({ url: '/admin/posgrado/modulos/' + currentModuloId + '/horarios', type: 'POST', data: { _token: CSRF, fecha, hora_inicio: inicio, hora_fin: fin, trabajadores_cargo_id: trabajadorId, estado: 'Confirmado' } }));
        }
    });
    
    // 3. Execute with Promise.all
    setBtnLoading('#btnGuardarAsignarHorario', true, 'Guardando…');
    Promise.all(promises)
        .then(function() {
            toast('success', promises.length + ' horario(s) actualizado(s).');
            closeModal('modalAsignarHorario');
            cargarModulosSidebar();
            refreshCalendar();
        })
        .catch(function() { toast('error', 'Error al guardar horarios.'); })
        .finally(function() { setBtnLoading('#btnGuardarAsignarHorario', false, '<i class="ri-check-line"></i> Guardar Todas'); });
});
```

## Archivos modificados

- `resources/views/admin/ofertas-academicas/detalle.blade.php`
  - Línea ~1844: cambiar `rowsToShow` a `CANTIDAD_SESIONES`
  - Líneas ~1870-1918: reescribir handler `#btnGuardarAsignarHorario`

## Pruebas

1. Módulo sin horarios: al abrir asignar, deben aparecer `cantidad_sesiones` filas vacías.
2. Módulo con algunos horarios: deben aparecer `cantidad_sesiones` filas, las primeras con datos existentes (no editables o editables según diseño actual), las restantes vacías.
3. Click en "Guardar Todas" con filas completas: deben guardarse correctamente.
4. Click en "Guardar Todas" con filas incompletas: debe mostrar warning y no enviar nada.
