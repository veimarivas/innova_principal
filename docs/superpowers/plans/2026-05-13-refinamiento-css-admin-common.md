# Refinamiento CSS Admin Common — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Refinar `resources/scss/admin-common.scss` para que las ~25 vistas `admin.*.index.blade.php` se vean más elegantes, profesionales y amigables, sin tocar ningún archivo Blade.

**Architecture:** Un solo archivo SCSS compartido. Los cambios se propagan automáticamente a todas las vistas. Se modifica: sombras multicapa, animaciones de modal con scale+fade, micro-interacciones en hover de filas y botones, efecto vidrio en toasts, gradiente sutil en header, y focus states mejorados. Todo con `prefers-reduced-motion` y `@supports` para accesibilidad.

**Tech Stack:** SCSS, Vite (compilación)

---

### Task 1: Refinar todos los estilos en admin-common.scss

**Files:**
- Modify: `resources/scss/admin-common.scss` (todo el archivo)
- Build output: `public/build/css/admin-common.min.css` (se regenera automáticamente)

- [ ] **Step 1: Reemplazar el contenido completo de admin-common.scss**

Escribir el nuevo contenido del archivo `resources/scss/admin-common.scss`:

```scss
// =================================================================
// ADMIN COMMON — Estilos personalizados del panel administrativo
// dept-* / dph-* / footer-* / form helpers / modal buttons
// =================================================================

$brand:       #fc7b04;
$brand-dark:  #c96004;
$brand-light: rgba(252, 123, 4, 0.12);
$brand-bg:    linear-gradient(135deg, #fc7b04 0%, #e06b00 100%);

// -----------------------------------------------------------------
// Page Header  (dept-page-header / dph-*)
// -----------------------------------------------------------------
.dept-page-header {
    background: linear-gradient(to bottom, #fff, #f8fafc);
    border-bottom: 1px solid var(--vz-border-color, #e2e8f0);
    padding: 1.25rem 0;
}

.dph-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.dph-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.dph-icon-wrap {
    width: 48px;
    height: 48px;
    background: $brand-light;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;

    i { font-size: 1.5rem; color: $brand; }
}

.dph-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--vz-heading-color, #1e293b);
    margin: 0 0 0.15rem;
    line-height: 1.2;
}

.dph-desc {
    font-size: 0.83rem;
    color: var(--vz-secondary-color, #64748b);
    margin: 0;
}

.dph-right {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.dph-stat-card {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    background: var(--vz-tertiary-bg, #f8fafc);
    border: 1px solid var(--vz-border-color, #e2e8f0);
    border-radius: 10px;
    padding: 0.6rem 1rem;
    transition: box-shadow 0.2s, transform 0.2s;

    &:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
}

.dph-stat-icon {
    width: 34px;
    height: 34px;
    background: $brand-light;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;

    i { color: $brand; font-size: 1rem; }
}

.dph-stat-num {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--vz-heading-color, #1e293b);
    line-height: 1;
}

.dph-stat-label {
    font-size: 0.72rem;
    color: var(--vz-secondary-color, #64748b);
    margin-top: 2px;
}

.dph-btn-new {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: $brand-bg;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 0.55rem 1.1rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
    white-space: nowrap;

    i { font-size: 1.1rem; }

    &:hover, &:focus {
        background: $brand-dark;
        color: #fff;
        box-shadow: 0 4px 15px rgba(252, 123, 4, 0.35);
        outline: none;
    }

    @media (prefers-reduced-motion: no-preference) {
        &:hover {
            transform: translateY(-1px);
        }
    }
}

// -----------------------------------------------------------------
// Dept Card
// -----------------------------------------------------------------
.dept-card {
    background: var(--vz-card-bg, #fff);
    border: 1px solid var(--vz-border-color, #e2e8f0);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 16px rgba(0, 0, 0, 0.04);
    transition: box-shadow 0.25s;

    @media (prefers-reduced-motion: no-preference) {
        &:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06), 0 8px 32px rgba(0, 0, 0, 0.05);
        }
    }
}

.dept-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--vz-border-color, #e2e8f0);
}

.dept-header-icon {
    width: 38px;
    height: 38px;
    background: $brand-light;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;

    i { color: $brand; font-size: 1.1rem; }
}

.dept-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--vz-heading-color, #1e293b);
    margin: 0;
}

.dept-subtitle {
    font-size: 0.78rem;
    color: var(--vz-secondary-color, #64748b);
    margin: 2px 0 0;
}

.dept-card-body {
    padding: 0;
}

.dept-table {
    width: 100% !important;
    border-collapse: collapse;

    thead th {
        background: var(--vz-tertiary-bg, #f8fafc);
        color: var(--vz-body-color, #495057);
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1rem;
        border-bottom: 2px solid var(--vz-border-color, #e2e8f0);
        white-space: nowrap;
    }

    tbody td {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        color: var(--vz-body-color, #212529);
        border-bottom: 1px solid var(--vz-border-color-translucent, rgba(0, 0, 0, 0.06));
        vertical-align: middle;
    }

    tbody tr {
        transition: background 0.2s;

        @media (prefers-reduced-motion: no-preference) {
            transition: background 0.2s, transform 0.15s;
        }
    }

    tbody tr:last-child td { border-bottom: none; }

    @media (min-width: 768px) {
        tbody tr:hover td { background: $brand-light; }

        [dir="rtl"] & tbody tr:hover {
            transform: none;
        }

        @media (prefers-reduced-motion: no-preference) {
            tbody tr:hover {
                transform: translateX(3px);
            }
        }
    }
}

// -----------------------------------------------------------------
// Action buttons (table row edit/delete)
// -----------------------------------------------------------------
.btn-action {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid transparent;
    transition: background 0.2s, border-color 0.2s, color 0.2s;
    background: transparent;
    color: var(--vz-secondary-color, #64748b);

    i { font-size: 0.95rem; }

    &:hover {
        border-color: var(--vz-border-color, #e2e8f0);
    }
}

.btn-action-edit {
    &:hover {
        background: rgba(252, 123, 4, 0.1);
        color: $brand;
    }
}

.btn-action-delete {
    &:hover {
        background: rgba(220, 38, 38, 0.08);
        color: #dc2626;
    }
}

// -----------------------------------------------------------------
// Form helpers
// -----------------------------------------------------------------
.req {
    color: #ef4444;
    font-weight: 600;
}

.field-wrapper {
    position: relative;

    .form-control {
        padding-right: 2.5rem;
        transition: box-shadow 0.2s, border-color 0.2s;

        &:focus {
            box-shadow: 0 0 0 3px rgba(252, 123, 4, 0.15);
            border-color: $brand;
        }
    }
}

.validation-icon {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1rem;
    pointer-events: none;
    transition: opacity 0.2s;
}

.field-feedback {
    font-size: 0.78rem;
    margin-top: 0.25rem;
    min-height: 1rem;
    transition: color 0.2s;
}

.char-hint {
    font-size: 0.72rem;
    color: var(--vz-secondary-color, #64748b);
    text-align: right;
    margin-top: 3px;
    transition: color 0.25s;

    &.warning {
        color: $brand;
    }

    &.danger {
        color: #ef4444;
    }
}

// -----------------------------------------------------------------
// Modal animation refinements
// -----------------------------------------------------------------
.modal-backdrop {
    background: rgba(0, 0, 0, 0.35);

    @supports (backdrop-filter: blur(2px)) {
        backdrop-filter: blur(2px);
    }

    &.fade {
        transition: opacity 0.2s;
    }
}

.modal-content {
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.12);
    border: none;
}

.modal-header {
    padding: 1rem 1.25rem 0.75rem;
    border-bottom: 1px solid var(--vz-border-color, #e2e8f0);
}

.modal-body {
    padding: 0.75rem 1.25rem;
}

.modal-footer {
    padding: 0.75rem 1.25rem;
    border-top: 1px solid var(--vz-border-color, #e2e8f0);
}

@media (prefers-reduced-motion: no-preference) {
    .modal.fade .modal-dialog {
        transform: scale(0.92) translateY(-8px);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s;
    }

    .modal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }
}

.btn-close {
    transition: transform 0.2s;

    @media (prefers-reduced-motion: no-preference) {
        &:hover {
            transform: rotate(90deg);
        }
    }
}

// -----------------------------------------------------------------
// Modal Buttons
// -----------------------------------------------------------------
.btn-modal-cancel {
    background: var(--vz-tertiary-bg, #f1f5f9);
    color: var(--vz-body-color, #495057);
    border: 1px solid var(--vz-border-color, #dee2e6);
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.55rem 1rem;
    transition: background 0.15s, transform 0.15s;

    &:hover {
        background: var(--vz-border-color, #e2e8f0);
        color: var(--vz-body-color, #495057);
    }
}

.btn-modal-submit {
    background: $brand-bg;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.55rem 1.25rem;
    transition: background 0.2s, box-shadow 0.2s, transform 0.15s;

    &:hover, &:focus {
        background: $brand-dark;
        color: #fff;
        box-shadow: 0 3px 12px rgba(252, 123, 4, 0.35);
    }

    @media (prefers-reduced-motion: no-preference) {
        &:hover:not(:disabled) {
            transform: scale(1.02);
        }
    }

    &:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
}

.btn-danger-modal {
    background: #dc2626;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.55rem 1.25rem;
    transition: background 0.2s, box-shadow 0.2s, transform 0.15s;

    &:hover {
        background: #b91c1c;
        color: #fff;
        box-shadow: 0 3px 12px rgba(220, 38, 38, 0.35);
    }

    @media (prefers-reduced-motion: no-preference) {
        &:hover {
            transform: scale(1.02);
        }
    }
}

// -----------------------------------------------------------------
// Delete Warning Box
// -----------------------------------------------------------------
.delete-warning-box {
    text-align: center;
    padding: 0.75rem 0;
}

.delete-icon-ring {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: rgba(220, 38, 38, 0.1);
    border: 2px solid rgba(220, 38, 38, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;

    i { font-size: 1.8rem; color: #dc2626; }
}

.delete-msg-primary {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--vz-heading-color, #1e293b);
    margin-bottom: 0.35rem;
}

.delete-msg-name {
    font-size: 0.95rem;
    color: var(--vz-body-color, #495057);
    margin-bottom: 0.5rem;

    strong { color: $brand; }
}

.delete-msg-warn {
    font-size: 0.8rem;
    color: var(--vz-secondary-color, #64748b);
    margin-bottom: 0;

    i { color: #f59e0b; margin-right: 4px; }
}

// -----------------------------------------------------------------
// Toast notifications (frosted glass)
// -----------------------------------------------------------------
.toast-notify {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.7rem 1rem;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 10px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    font-size: 0.82rem;
    font-weight: 500;
    color: #495057;
    border-left: 5px solid;
    animation: slideIn 0.3s ease;

    @supports (backdrop-filter: blur(8px)) {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(8px);
    }

    &.success { border-left-color: #16a34a; }
    &.error   { border-left-color: #dc2626; }
    &.warning { border-left-color: #f59e0b; }
}

@keyframes slideIn {
    from { opacity: 0; transform: translateX(100%); }
    to   { opacity: 1; transform: translateX(0); }
}

// -----------------------------------------------------------------
// Footer inner
// -----------------------------------------------------------------
.footer {
    .footer-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .footer-copy {
        font-size: 0.82rem;
        color: var(--vz-footer-color, #6c757d);

        span {
            font-weight: 600;
            color: $brand;
        }
    }

    .footer-links {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.82rem;

        a {
            color: var(--vz-footer-color, #6c757d);
            text-decoration: none;
            transition: color 0.15s;

            i { margin-right: 3px; }
            &:hover { color: $brand; }
        }
    }

    .footer-badge {
        background: $brand-light;
        color: $brand;
        border: 1px solid rgba(252, 123, 4, 0.25);
        border-radius: 20px;
        padding: 2px 10px;
        font-size: 0.72rem;
        font-weight: 600;
    }
}
```

- [ ] **Step 2: Compilar con Vite**

Run: `npx vite build` (o `npm run build` si está configurado en package.json)
Expected: SCSS compilado a `public/build/css/admin-common.min.css` sin errores

- [ ] **Step 3: Verificar visualmente en una vista**

1. Abrir `/admin/profesiones` en el navegador
2. Confirmar que el header tiene gradiente sutil
3. Hover en una fila de la tabla → debe moverse 3px a la derecha con background naranja
4. Abrir modal Crear → debe animarse con scale 0.92→1
5. Cerrar modal → debe animarse con fade out
6. Hacer clic en botón Nuevo → debe tener elevación sutil
7. Editar y eliminar botones en tabla → hover con colores refinados
8. Focus en input → box-shadow naranja en lugar de outline azul
9. Mostrar un toast → debe tener efecto vidrio (transparencia + blur)

- [ ] **Step 4: Verificar accesibilidad**

1. En DevTools → Rendering → Emulate CSS media feature prefers-reduced-motion → `reduce`
2. Abrir modal → debe aparecer sin animación de escala (solo fade)
3. Hover sobre fila de tabla → sin transformación de translateX
4. Verificar contraste con Lighthouse o DevTools contrast checker
5. Tab navigation por la página → focus visible en todos los elementos interactivos

- [ ] **Step 5: Verificar responsive**

1. Reducir viewport a 375px
2. Tabla debe ser legible (scroll horizontal si es necesario)
3. Filas no deben tener transformación en hover
4. Header debe hacer wrap correctamente (botón Nueva + stat card)

- [ ] **Step 6: Commit**

```bash
git add resources/scss/admin-common.scss public/build/css/admin-common.min.css
git commit -m "style: refinar CSS de admin-common con animaciones y micro-interacciones

- Sombras multicapa en cards con hover sutil
- Animación de entrada en modales (scale + fade) con prefers-reduced-motion
- Micro-desplazamiento en hover de filas de tabla (≥768px)
- Efecto vidrio (backdrop-filter) en toasts con fallback
- Gradiente sutil en page header
- Focus states con box-shadow naranja en inputs
- Acciones de tabla con hover refinado (edit/delete)
- Botones con elevación y scale en hover
- Accesibilidad: prefers-reduced-motion y @supports en todas las animaciones"
```
