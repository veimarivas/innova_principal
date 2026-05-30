# Fix Asignar Horario — Sesiones y Guardado Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Fix two bugs in the horario assignment modal: show `cantidad_sesiones` rows instead of 1, and make the "Guardar Todas" button actually save.

**Architecture:** Two surgical edits in a single blade view file — fix the row count calculation and rewrite the save handler to validate before executing AJAX calls.

**Tech Stack:** Laravel Blade, jQuery, JavaScript

---

### Task 1: Fix row count to show cantidad_sesiones rows

**Files:**
- Modify: `resources/views/admin/ofertas-academicas/detalle.blade.php:1844`

- [ ] **Step 1: Change rowsToShow from `Math.max(horariosCount, 1)` to `CANTIDAD_SESIONES`**

Current code at line 1844:
```js
const rowsToShow = Math.max(horariosCount, 1);
```

Replace with:
```js
const rowsToShow = CANTIDAD_SESIONES;
```

This ensures that when opening the "Asignar Horarios" modal, the loop at lines 1846-1853 iterates `CANTIDAD_SESIONES` times. Existing horarios populate the first N rows (since `horarios[i]` returns data for registered sessions), and the remaining rows render empty via `addSesionRow('', '', '', null)`.

- [ ] **Step 2: Commit**

```bash
git add resources/views/admin/ofertas-academicas/detalle.blade.php
git commit -m "fix: show cantidad_sesiones rows in asignar horario modal"
```

---

### Task 2: Rewrite save handler to validate before executing

**Files:**
- Modify: `resources/views/admin/ofertas-academicas/detalle.blade.php:1870-1919`

- [ ] **Step 1: Replace the entire `#btnGuardarAsignarHorario` click handler**

Current code (lines 1870-1919):
```js
    $('#btnGuardarAsignarHorario').on('click', function() {
        const rows = $('#sesionesRowsContainer .sesion-row');
        const trabajadorId = $('#asigTrabajadorId').val() || null;
        const promises = [];
        let hasError = false;

        console.log('Total rows:', rows.length, 'currentModuloId:', currentModuloId);
        
        rows.each(function() {
            const $row = $(this);
            const horarioId = $row.data('horario-id');
            const fecha = $row.find('.sesion-fecha').val();
            const inicio = $row.find('.sesion-inicio').val();
            const fin = $row.find('.sesion-fin').val();
            
            console.log('Row horarioId:', horarioId, 'fecha:', fecha);
            
            if (!fecha || !inicio || !fin) { hasError = true; return false; }

            if (horarioId && horarioId !== '') {
                console.log('Updating horario:', horarioId);
                promises.push($.ajax({
                    url: '/admin/posgrado/horarios/' + horarioId,
                    type: 'PUT',
                    data: { _token: CSRF, fecha: fecha, hora_inicio: inicio, hora_fin: fin, trabajadores_cargo_id: trabajadorId, estado: 'Confirmado' }
                }));
            } else {
                console.log('Creating new horario');
                promises.push($.ajax({
                    url: '/admin/posgrado/modulos/' + currentModuloId + '/horarios',
                    type: 'POST',
                    data: { _token: CSRF, fecha: fecha, hora_inicio: inicio, hora_fin: fin, trabajadores_cargo_id: trabajadorId, estado: 'Confirmado' }
                }));
            }
        });

        if (hasError) { toast('warning', 'Complete fecha, hora inicio y hora fin en todas las filas.'); return; }
        if (!promises.length) { toast('warning', 'No hay sesiones para registrar.'); return; }

        setBtnLoading('#btnGuardarAsignarHorario', true, 'Guardando…');
        $.when.apply($, promises)
        .done(function() {
            toast('success', promises.length + ' horario(s) actualizado(s).');
            closeModal('modalAsignarHorario');
            cargarModulosSidebar();
            refreshCalendar();
        })
        .fail(function() { toast('error', 'Error al guardar horarios.'); })
        .always(function() { setBtnLoading('#btnGuardarAsignarHorario', false, '<i class="ri-check-line"></i> Guardar Todas'); });
    });
```

Replace entirely with:
```js
    $('#btnGuardarAsignarHorario').on('click', function() {
        const rows = $('#sesionesRowsContainer .sesion-row');
        const trabajadorId = $('#asigTrabajadorId').val() || null;

        // 1. Validate all rows first — no AJAX until we know everything is valid
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

        // 2. Build promises only after validation passes
        const promises = [];
        rows.each(function() {
            const $row = $(this);
            const horarioId = $row.data('horario-id');
            const fecha = $row.find('.sesion-fecha').val();
            const inicio = $row.find('.sesion-inicio').val();
            const fin = $row.find('.sesion-fin').val();

            if (horarioId && horarioId !== '') {
                promises.push($.ajax({
                    url: '/admin/posgrado/horarios/' + horarioId,
                    type: 'PUT',
                    data: { _token: CSRF, fecha: fecha, hora_inicio: inicio, hora_fin: fin, trabajadores_cargo_id: trabajadorId, estado: 'Confirmado' }
                }));
            } else {
                promises.push($.ajax({
                    url: '/admin/posgrado/modulos/' + currentModuloId + '/horarios',
                    type: 'POST',
                    data: { _token: CSRF, fecha: fecha, hora_inicio: inicio, hora_fin: fin, trabajadores_cargo_id: trabajadorId, estado: 'Confirmado' }
                }));
            }
        });

        // 3. Execute with Promise.all — works correctly for any array size including 1
        setBtnLoading('#btnGuardarAsignarHorario', true, 'Guardando…');
        Promise.all(promises)
            .then(function() {
                toast('success', promises.length + ' horario(s) actualizado(s).');
                closeModal('modalAsignarHorario');
                cargarModulosSidebar();
                refreshCalendar();
            })
            .catch(function() {
                toast('error', 'Error al guardar horarios.');
            })
            .finally(function() {
                setBtnLoading('#btnGuardarAsignarHorario', false, '<i class="ri-check-line"></i> Guardar Todas');
            });
    });
```

Key changes:
- **Two-pass approach:** First pass validates all rows without side effects. Second pass builds promises only if validation succeeds. This prevents the original bug where `hasError` was checked after promises were already pushed.
- **`Promise.all` instead of `$.when.apply($, ...)`:** `Promise.all` handles arrays of any size consistently, including single-element arrays where jQuery's `$.when` behaves differently.
- **`.finally()` instead of `.always()`:** Standard Promise API, equivalent behavior.

- [ ] **Step 2: Commit**

```bash
git add resources/views/admin/ofertas-academicas/detalle.blade.php
git commit -m "fix: rewrite save handler with two-pass validation and Promise.all"
```
