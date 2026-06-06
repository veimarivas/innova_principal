@extends('layouts.master')
@section('title') Trabajadores @endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
/* Modal scroll fix */
#modalEditar .modal-body {
    max-height: 65vh;
    overflow-y: auto;
}
#modalRegistro .modal-body {
    max-height: 65vh;
    overflow-y: auto;
}
html[data-bs-theme="dark"] #modalEditar .modal-body {
    max-height: 65vh;
    overflow-y: auto;
}
html[data-bs-theme="dark"] #modalRegistro .modal-body {
    max-height: 65vh;
    overflow-y: auto;
}

/* Search box */
.tw-search-card {
    background: var(--d-card);
    border: 1px solid var(--d-card-border);
    border-radius: 16px;
    box-shadow: var(--d-card-shadow);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.tw-search-row {
    display: flex;
    align-items: flex-end;
    gap: 1rem;
    flex-wrap: wrap;
}
.tw-search-input { flex: 1; min-width: 200px; }
.tw-search-input label {
    font-weight: 600 !important;
    font-size: 0.85rem !important;
    color: var(--d-title) !important;
    margin-bottom: 0.5rem !important;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}
.tw-search-input .form-control {
    background: var(--d-input-bg) !important;
    border: 2px solid var(--d-input-border) !important;
    border-radius: 10px !important;
    padding: 0.75rem 1rem !important;
    font-size: 0.95rem !important;
    color: var(--d-input-color) !important;
    font-weight: 500 !important;
}
.tw-search-input .form-control:focus {
    border-color: #fc7b04 !important;
    box-shadow: 0 0 0 4px rgba(252, 123, 4, 0.12) !important;
}
.tw-btn-search {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: linear-gradient(135deg, #743c04 0%, #9a4904 100%);
    border: none;
    color: #fff !important;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.75rem 1.4rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 3px 10px rgba(116, 60, 4, 0.3);
    white-space: nowrap;
}
.tw-btn-search:hover {
    background: linear-gradient(135deg, #9a4904 0%, #b55804 100%);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(116, 60, 4, 0.4);
    color: #fff !important;
}
.tw-btn-register {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: linear-gradient(135deg, #5a8a30 0%, #6dbf40 100%);
    border: none;
    color: #fff !important;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.75rem 1.4rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 3px 10px rgba(90, 138, 48, 0.3);
    white-space: nowrap;
}
.tw-btn-register:hover {
    background: linear-gradient(135deg, #6dbf40 0%, #82d455 100%);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(90, 138, 48, 0.4);
    color: #fff !important;
}
.tw-btn-register:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}
html[data-bs-theme="dark"] .tw-btn-search { background: #fc7b04; }
html[data-bs-theme="dark"] .tw-btn-search:hover { background: #ff9d4d; }

/* Persona found card */
.tw-persona-found {
    background: var(--d-card);
    border: 1px solid var(--d-card-border);
    border-radius: 16px;
    box-shadow: var(--d-card-shadow);
    overflow: hidden;
    margin-bottom: 1.5rem;
    display: none;
}
.tw-pf-header {
    background: linear-gradient(135deg, #391b04 0%, #743c04 40%, #c96004 100%);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.tw-pf-header h5 {
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.tw-pf-header h5 i { opacity: 0.9; }
.tw-pf-badge {
    background: rgba(255,255,255,0.2);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.25rem 0.65rem;
    border-radius: 6px;
    text-transform: uppercase;
}
.tw-pf-body { padding: 1.25rem 1.5rem; }
.tw-pf-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 0.75rem;
}
.tw-pf-item label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--d-muted);
    margin-bottom: 0.2rem;
    display: block;
}
.tw-pf-item span {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--d-body);
}
.tw-pf-actions {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--d-card-border);
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

/* Cargos selection */
.tw-cargos-section {
    background: var(--d-card);
    border: 1px solid var(--d-card-border);
    border-radius: 16px;
    box-shadow: var(--d-card-shadow);
    overflow: hidden;
    margin-bottom: 1.5rem;
    display: none;
}
.tw-cs-header {
    background: var(--d-header-bg);
    border-bottom: 1px solid var(--d-header-border);
    padding: 1rem 1.5rem;
}
.tw-cs-header h5 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--d-title);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.tw-cs-header h5 i { color: #fc7b04; }
.tw-cs-body { padding: 1.25rem 1.5rem; }
.tw-cargo-check {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border: 1px solid var(--d-row-border);
    border-radius: 10px;
    margin-bottom: 0.5rem;
    transition: all 0.2s;
    cursor: pointer;
}
.tw-cargo-check:hover { background: var(--d-row-hover); }
.tw-cargo-check.checked {
    background: rgba(252, 123, 4, 0.06);
    border-color: rgba(252, 123, 4, 0.3);
}
.tw-cargo-check input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #fc7b04;
    cursor: pointer;
}
.tw-cargo-name {
    font-weight: 600;
    color: var(--d-body);
    font-size: 0.9rem;
    flex: 1;
}
.tw-cargo-fields {
    display: none;
    margin-top: 0.5rem;
    padding: 0.75rem 1rem;
    background: rgba(252, 123, 4, 0.04);
    border-radius: 8px;
    border: 1px dashed var(--d-input-border);
}
.tw-cargo-fields.show { display: block; }
.tw-cargo-fields .row { margin-bottom: 0.5rem; }
.tw-cargo-fields label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--d-title);
    margin-bottom: 0.3rem;
}
.tw-cargo-fields .form-control,
.tw-cargo-fields .form-select {
    background: var(--d-input-bg) !important;
    border: 2px solid var(--d-input-border) !important;
    border-radius: 8px !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.82rem !important;
    color: var(--d-input-color) !important;
}
.tw-cargo-fields .form-control:focus,
.tw-cargo-fields .form-select:focus {
    border-color: #fc7b04 !important;
    box-shadow: 0 0 0 3px rgba(252, 123, 4, 0.12) !important;
}
.tw-cargo-fields .tw-sucursal-row {
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px dashed var(--d-row-border);
}
.tw-cargo-fields .tw-sucursal-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--d-muted);
    margin-bottom: 0.3rem;
}

/* Badge cargos */
.badge-cargo-asignado {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: linear-gradient(135deg, rgba(154, 73, 4, 0.12) 0%, rgba(154, 73, 4, 0.06) 100%);
    color: var(--d-badge-color);
    border: 1px solid var(--d-badge-border);
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.25rem 0.6rem;
    border-radius: 6px;
    margin: 0.15rem;
}
.badge-vigente { border-color: rgba(91, 138, 48, 0.3); color: #5a8a30; }
html[data-bs-theme="dark"] .badge-vigente { border-color: rgba(109, 191, 64, 0.3); color: #6dbf40; }
.badge-no-vigente { border-color: rgba(201, 96, 4, 0.3); color: #bc5404; }
html[data-bs-theme="dark"] .badge-no-vigente { border-color: rgba(236, 108, 4, 0.3); color: #ec6c04; }

/* Persona modal form */
.tw-persona-section-title {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: var(--d-muted);
    border-bottom: 1px solid var(--d-card-border);
    padding-bottom: 0.35rem;
    margin: 1.25rem 0 1rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.tw-persona-section-title:first-child { margin-top: 0; }
.tw-persona-section-title i { font-size: 0.85rem; color: #fc7b04; }

/* Already worker badge */
.tw-ya-trabajador {
    background: rgba(91, 138, 48, 0.10);
    border: 1px solid rgba(91, 138, 48, 0.25);
    border-radius: 10px;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.75rem;
}
.tw-ya-trabajador i { color: #5a8a30; font-size: 1.1rem; }
.tw-ya-trabajador span { font-size: 0.82rem; font-weight: 600; color: #5a8a30; }
html[data-bs-theme="dark"] .tw-ya-trabajador { background: rgba(109, 191, 64, 0.10); }
html[data-bs-theme="dark"] .tw-ya-trabajador i { color: #6dbf40; }
html[data-bs-theme="dark"] .tw-ya-trabajador span { color: #6dbf40; }

/* Cargos table in worker row */
.tw-cargos-list { max-height: 120px; overflow-y: auto; }

/* ── Hint de búsqueda y validación ── */
.search-validation-hint { font-size: 0.72rem; color: #6b7280; margin-top: 4px; display: flex; align-items: center; gap: 5px; min-height: 18px; }
.search-validation-hint i { font-size: 0.85rem; color: #fc7b04; }
.search-validation-hint.success { color: #16a34a; }
.search-validation-hint.success i { color: #16a34a; }
.search-validation-hint.error { color: #dc2626; }
.search-validation-hint.error i { color: #dc2626; }
.form-control.is-valid#searchCarnet { border-color: #16a34a !important; }
.form-control.is-invalid#searchCarnet { border-color: #dc2626 !important; }

/* ── Botones deshabilitados (estado más obvio) ── */
.tw-btn-search[disabled], .tw-btn-search:disabled,
.tw-btn-register[disabled], .tw-btn-register:disabled {
    opacity: 0.45 !important; cursor: not-allowed !important;
    background: #d1d5db !important; color: #6b7280 !important;
    box-shadow: none !important; animation: none !important; transform: none !important;
    border: none !important;
}

/* ── Caja "no es trabajador todavía" ── */
.tw-no-trabajador {
    margin-top: 0.85rem; padding: 14px 16px;
    background: linear-gradient(135deg, rgba(252,123,4,0.07) 0%, rgba(252,123,4,0.02) 100%);
    border: 1.5px dashed rgba(252,123,4,0.35);
    border-radius: 12px;
    display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
}
.tw-no-trab-icon {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    background: linear-gradient(135deg, #fc7b04, #b85500); color: #fff;
    display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem;
    box-shadow: 0 4px 12px rgba(252,123,4,0.30);
}
.tw-no-trab-text { flex: 1; min-width: 200px; font-size: 0.86rem; color: #475569; }
.tw-no-trab-text strong { color: #b85500; font-size: 0.95rem; display: block; margin-bottom: 2px; }
.tw-no-trab-text div { font-size: 0.78rem; color: #6b7280; }
.tw-btn-confirm {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: #fff;
    border: none; padding: 0.65rem 1.2rem; border-radius: 10px;
    font-weight: 600; font-size: 0.85rem; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px;
    box-shadow: 0 3px 10px rgba(22,163,74,0.25); transition: all .15s;
}
.tw-btn-confirm:hover { transform: translateY(-1px); box-shadow: 0 5px 15px rgba(22,163,74,0.40); }
html[data-bs-theme="dark"] .tw-no-trab-text { color: #cbd5e1; }
html[data-bs-theme="dark"] .tw-no-trab-text div { color: #94a3b8; }

/* ── Modal asignar cargos ── */
#modalAsignarCargos .modal-content { border: none; border-radius: 14px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.15); }
#modalAsignarCargos .modal-header {
    background: linear-gradient(135deg, #fc7b04 0%, #b85500 100%);
    color: #fff; border-bottom: none; padding: 16px 22px;
}
#modalAsignarCargos .modal-header .modal-title { color: #fff; font-weight: 700; font-size: 1rem; display: flex; align-items: center; gap: 8px; }
#modalAsignarCargos .modal-header .btn-close { filter: invert(1) brightness(2); opacity: .8; }
#modalAsignarCargos .modal-body { max-height: 65vh; overflow-y: auto; padding: 18px 22px; }
#modalAsignarCargos .modal-footer { background: #f8f9fa; border-top: 1px solid #f1f3f5; padding: 12px 22px; }

.ac-info-row {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 12px; margin-bottom: 14px;
    background: rgba(252,123,4,0.07); border-left: 3px solid #fc7b04;
    border-radius: 6px; font-size: 0.8rem; color: #475569;
}
.ac-info-row i { color: #fc7b04; font-size: 1rem; }

/* ═══════════════════════════════════════════════════════════════
   REDISEÑO VISUAL — pulido general
   ═══════════════════════════════════════════════════════════════ */

/* ── Header de página con degradado y patrón ── */
.dept-page-header {
    background: linear-gradient(135deg, #391b04 0%, #743c04 35%, #b85500 70%, #fc7b04 100%) !important;
    border-radius: 20px;
    padding: 1.6rem 2rem !important;
    margin-bottom: 1.5rem;
    box-shadow: 0 12px 40px rgba(184, 85, 0, .25);
    position: relative; overflow: hidden;
}
.dept-page-header::before {
    content: ''; position: absolute; top: -60px; right: -60px;
    width: 220px; height: 220px; border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,.08), transparent);
    pointer-events: none;
}
.dept-page-header::after {
    content: ''; position: absolute; bottom: -80px; left: -40px;
    width: 200px; height: 200px; border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,.05), transparent);
    pointer-events: none;
}
.dept-page-header .dph-inner { position: relative; z-index: 1; }
.dept-page-header .dph-icon-wrap {
    width: 60px !important; height: 60px !important;
    background: rgba(255,255,255,.15) !important;
    border: 1.5px solid rgba(255,255,255,.25) !important;
    backdrop-filter: blur(8px);
    border-radius: 16px !important;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 6px 18px rgba(0,0,0,.15);
}
.dept-page-header .dph-icon-wrap i { color: #fff !important; font-size: 1.8rem; }
.dept-page-header .dph-title {
    color: #fff !important;
    font-weight: 800 !important; font-size: 1.55rem !important;
    margin: 0 !important; letter-spacing: -.01em;
    text-shadow: 0 2px 8px rgba(0,0,0,.15);
}
.dept-page-header .dph-desc {
    color: rgba(255,255,255,.85) !important;
    font-size: .85rem !important; margin: 4px 0 0 !important;
}
.dept-page-header .dph-stat-card {
    background: rgba(255,255,255,.18) !important;
    border: 1.5px solid rgba(255,255,255,.25) !important;
    backdrop-filter: blur(10px); border-radius: 14px !important;
    padding: 12px 18px !important; box-shadow: 0 4px 12px rgba(0,0,0,.08);
}
.dept-page-header .dph-stat-icon {
    background: rgba(255,255,255,.20) !important; color: #fff !important;
    border-radius: 10px; width: 40px; height: 40px;
    display: inline-flex; align-items: center; justify-content: center;
}
.dept-page-header .dph-stat-num { color: #fff !important; font-size: 1.5rem !important; font-weight: 800 !important; }
.dept-page-header .dph-stat-label { color: rgba(255,255,255,.75) !important; font-size: .72rem !important; text-transform: uppercase; letter-spacing: .5px; }

/* ── Search card refinada ── */
.tw-search-card {
    border-radius: 18px !important;
    padding: 1.6rem !important;
    border: none !important;
    box-shadow: 0 4px 20px rgba(0,0,0,.05) !important;
    background: linear-gradient(180deg, #fff 0%, #fffaf3 100%) !important;
    border-top: 3px solid #fc7b04 !important;
}
html[data-bs-theme="dark"] .tw-search-card {
    background: linear-gradient(180deg, var(--d-card) 0%, rgba(252,123,4,.04) 100%) !important;
}
.tw-search-input label i {
    background: rgba(252,123,4,.12); width: 22px; height: 22px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 6px; font-size: .85rem !important;
}
.tw-search-input .form-control, .tw-search-input .form-select {
    border-radius: 10px !important;
    padding: .7rem 1rem !important;
    border: 1.5px solid #e9ecef !important;
    font-size: .88rem !important;
    transition: all .18s;
}
.tw-search-input .form-control:focus, .tw-search-input .form-select:focus {
    border-color: #fc7b04 !important;
    box-shadow: 0 0 0 4px rgba(252,123,4,.12) !important;
}

.tw-btn-search, .tw-btn-register {
    padding: .7rem 1.4rem !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    font-size: .88rem !important;
    display: inline-flex !important; align-items: center; gap: 6px !important;
    border: none !important;
    transition: all .18s !important;
}
.tw-btn-register {
    background: linear-gradient(135deg, #5a8a30 0%, #426a1f 100%) !important;
    color: #fff !important;
    box-shadow: 0 3px 10px rgba(90, 138, 48, .28) !important;
}
.tw-btn-register:hover { transform: translateY(-1px); box-shadow: 0 5px 15px rgba(90, 138, 48, .40) !important; }

/* ── Persona found refresh ── */
.tw-persona-found {
    border-radius: 18px !important;
    border: none !important;
    box-shadow: 0 4px 24px rgba(0,0,0,.06) !important;
    overflow: hidden;
}
.tw-pf-header {
    background: linear-gradient(135deg, #fc7b04 0%, #b85500 60%, #743c04 100%) !important;
    padding: 1.1rem 1.6rem !important;
}
.tw-pf-header h5 {
    font-size: 1.05rem !important;
    letter-spacing: .01em;
    text-shadow: 0 2px 4px rgba(0,0,0,.15);
}
.tw-pf-header h5 i {
    background: rgba(255,255,255,.22);
    width: 32px; height: 32px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; opacity: 1 !important;
}
.tw-pf-badge {
    background: rgba(255,255,255,.25) !important;
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,.30);
    border-radius: 12px !important;
    padding: .35rem .85rem !important;
    font-size: .75rem !important;
    letter-spacing: .05em;
}
.tw-pf-body { padding: 1.5rem 1.6rem !important; }
.tw-pf-grid { gap: 1rem !important; }
.tw-pf-item {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 10px;
    padding: 10px 14px;
    transition: all .15s;
}
.tw-pf-item:hover { background: #fff4e6; border-color: #fed7aa; }
.tw-pf-item label {
    color: #b85500 !important;
    margin-bottom: 4px !important;
}
.tw-pf-item span {
    font-size: .92rem !important;
    color: #1f2937 !important;
}
html[data-bs-theme="dark"] .tw-pf-item { background: rgba(252,123,4,.04); border-color: rgba(252,123,4,.10); }
html[data-bs-theme="dark"] .tw-pf-item:hover { background: rgba(252,123,4,.10); }
html[data-bs-theme="dark"] .tw-pf-item span { color: #e5e7eb !important; }

.tw-pf-actions {
    padding: 1rem 1.6rem !important;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9 !important;
}
html[data-bs-theme="dark"] .tw-pf-actions { background: rgba(0,0,0,.10); border-top-color: rgba(255,255,255,.05) !important; }

/* ── Caja "no es trabajador" mejorada ── */
.tw-no-trabajador {
    margin-top: 1.2rem !important;
    padding: 16px 18px !important;
    border-radius: 14px !important;
    border: 2px dashed rgba(252,123,4,.40) !important;
    background: linear-gradient(135deg, #fff8f0 0%, #fff 100%) !important;
    position: relative; overflow: hidden;
}
.tw-no-trabajador::before {
    content: ''; position: absolute; top: 0; right: 0;
    width: 120px; height: 120px;
    background: radial-gradient(circle at top right, rgba(252,123,4,.08), transparent 70%);
    pointer-events: none;
}
.tw-no-trab-icon {
    width: 52px !important; height: 52px !important;
    border-radius: 14px !important; font-size: 1.4rem !important;
    box-shadow: 0 6px 16px rgba(252,123,4,.30) !important;
}
.tw-no-trab-text strong { font-size: 1rem !important; }
.tw-btn-confirm {
    padding: .75rem 1.4rem !important;
    border-radius: 12px !important;
    font-size: .88rem !important;
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%) !important;
    box-shadow: 0 4px 14px rgba(22,163,74,.30) !important;
    position: relative; z-index: 1;
}
.tw-btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(22,163,74,.45) !important;
}

/* ── Tabla mejorada ── */
.dept-card {
    border: none !important;
    border-radius: 18px !important;
    box-shadow: 0 4px 24px rgba(0,0,0,.05) !important;
    overflow: hidden;
}
.dept-card-header {
    background: linear-gradient(135deg, #fff 0%, #fffaf3 100%) !important;
    border-bottom: 1px solid #f1f5f9 !important;
    padding: 1.3rem 1.6rem !important;
}
html[data-bs-theme="dark"] .dept-card-header { background: rgba(252,123,4,.04) !important; border-bottom-color: rgba(255,255,255,.06) !important; }
.dept-header-icon {
    background: linear-gradient(135deg, #fc7b04, #b85500) !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(252,123,4,.30) !important;
    border-radius: 12px !important;
}
.dept-title { font-weight: 700 !important; font-size: 1.05rem !important; color: #1f2937 !important; }
html[data-bs-theme="dark"] .dept-title { color: #f1f5f9 !important; }
.dept-subtitle { font-size: .82rem !important; color: #6b7280 !important; margin: 2px 0 0 !important; }

.dept-table { border-collapse: separate; border-spacing: 0; }
.dept-table thead tr th {
    background: linear-gradient(180deg, #fff8f0 0%, #fff 100%) !important;
    color: #b85500 !important;
    font-weight: 700 !important;
    font-size: .75rem !important;
    text-transform: uppercase;
    letter-spacing: .04em;
    padding: 14px 16px !important;
    border-bottom: 2px solid #fed7aa !important;
}
html[data-bs-theme="dark"] .dept-table thead tr th {
    background: rgba(252,123,4,.06) !important;
    border-bottom-color: rgba(252,123,4,.20) !important;
}
.dept-table tbody tr {
    transition: background .12s;
}
.dept-table tbody tr:hover { background: #fffaf3 !important; }
html[data-bs-theme="dark"] .dept-table tbody tr:hover { background: rgba(252,123,4,.05) !important; }
.dept-table tbody td {
    padding: 14px 16px !important;
    border-bottom: 1px solid #f1f5f9 !important;
    font-size: .87rem;
    vertical-align: middle;
}
html[data-bs-theme="dark"] .dept-table tbody td { border-bottom-color: rgba(255,255,255,.04) !important; }

/* ═══════════════════════════════════════════════════════════════
   DISEÑO COMPARTIDO CON ESTUDIANTES — modales y acciones
   ═══════════════════════════════════════════════════════════════ */
:root {
    --est-primary: #fc7b04;
    --est-primary-dark: #d46604;
    --est-text: #2d2924;
    --est-text-muted: #8c8880;
    --est-border: #ede8e2;
    --est-border-light: #f5f0eb;
    --est-success: #2e9a6e;
    --est-danger: #e05050;
    --est-bg-warm: #faf7f4;
}

/* ── Botones de acción en la tabla ── */
.est-action-cell { display: flex; align-items: center; justify-content: center; gap: 0.28rem; flex-wrap: wrap; }
.est-btn-action { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid transparent; transition: all 0.2s; cursor: pointer; background: transparent; color: var(--est-text-muted); }
.est-btn-action i { font-size: 0.92rem; }
.est-btn-action[disabled] { opacity: 0.35; cursor: not-allowed; }
.est-btn-action.est-btn-view { background: rgba(59,130,246,0.08); color: #3b82f6; border-color: rgba(59,130,246,0.22); }
.est-btn-action.est-btn-view:hover:not([disabled]) { background: rgba(59,130,246,0.18); color: #2563eb; }
.est-btn-action.est-btn-edit { background: rgba(252,123,4,0.08); color: var(--est-primary); border-color: rgba(252,123,4,0.22); }
.est-btn-action.est-btn-edit:hover:not([disabled]) { background: rgba(252,123,4,0.18); color: #d46604; }
.est-btn-action.est-btn-cuenta { background: rgba(124,58,237,0.08); color: #7c3aed; border-color: rgba(124,58,237,0.22); }
.est-btn-action.est-btn-cuenta:hover:not([disabled]) { background: rgba(124,58,237,0.18); color: #5b21b6; }
.est-btn-action.est-btn-whatsapp { background: rgba(37,211,102,0.08); color: #25D366; border-color: rgba(37,211,102,0.22); }
.est-btn-action.est-btn-whatsapp:hover:not([disabled]) { background: rgba(37,211,102,0.18); color: #128C7E; }
.est-btn-action.est-btn-delete { background: rgba(220,38,38,0.06); color: #dc2626; border-color: rgba(220,38,38,0.18); }
.est-btn-action.est-btn-delete:hover:not([disabled]) { background: rgba(220,38,38,0.15); color: #b91c1c; }

/* ── Modal con header gradiente (estilo estudiantes) ── */
.est-modal-form .modal-content { border: none; border-radius: 18px; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,.2); max-height: calc(100vh - 60px); display: flex; flex-direction: column; }
.est-modal-form .modal-header { background: linear-gradient(135deg,#391b04,#7c3c00,#c96004); color: #fff; padding: 1.25rem 1.5rem; border: none; position: relative; overflow: hidden; }
.est-modal-form .modal-header::after { content:''; position:absolute; top:-50px; right:-30px; width:150px; height:150px; background:radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%); border-radius:50%; pointer-events:none; }
.est-modal-form .modal-header .modal-title { color: #fff; font-size: 1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; margin: 0; font-family: 'Lexend', sans-serif; }
.est-modal-form .modal-header .modal-title i { color: #fff; font-size: 1.35rem; }
.est-modal-form .est-modal-subtitle { font-size: .73rem; opacity: .8; margin-top: .15rem; color: rgba(255,255,255,.85); }
.est-modal-form .est-modal-icon { width: 46px; height: 46px; background: rgba(255,255,255,.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; z-index: 1; }
.est-modal-form .est-modal-icon i { color: #fff; font-size: 1.35rem; }
.est-modal-form .modal-header .btn-close { filter: invert(1) brightness(2); opacity: .85; }
.est-modal-form .modal-header .btn-close:hover { opacity: 1; }
.est-modal-form .modal-body { padding: 0; background: #fff; max-height: calc(100vh - 210px); overflow-y: auto; }
.est-modal-form .modal-footer { position: sticky; bottom: 0; z-index: 2; border-top: 1px solid #e2e8f0; background: #f8fafc; padding: 1rem 1.5rem; border-radius: 0 0 18px 18px; gap: .5rem; }
.modal.fade .est-modal-form .modal-dialog { transform: scale(0.92) translateY(-10px); transition: transform 0.35s cubic-bezier(0.16,1,0.3,1), opacity 0.25s; }
.modal.show .est-modal-form .modal-dialog { transform: scale(1) translateY(0); }

/* ── Secciones dentro del modal ── */
.est-modal-section { padding: 1.2rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
.est-modal-section:last-child { border-bottom: none; }
.est-modal-section-title { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.85rem; }
.est-modal-section-title-icon { width: 26px; height: 26px; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.est-modal-section-title-icon i { font-size: .85rem; }
.est-modal-section-title-text { font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .07em; }
.est-modal-section.est-modal-section-warm { background: rgba(252,123,4,.02); }

/* ── Labels en modales ── */
.est-modal-form .est-flabel { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #64748b; display: block; margin-bottom: .4rem; }
.est-modal-form .est-flabel i { color: var(--est-primary); font-size: .8rem; }
.est-modal-form .est-flabel .text-danger { color: #ef4444; }

/* ── Form controls compactos en modal ── */
.est-modal-form .form-control,
.est-modal-form .form-select { font-size: .85rem; border-radius: 9px; }
.est-modal-form .form-control:focus,
.est-modal-form .form-select:focus { border-color: var(--est-primary); box-shadow: 0 0 0 3px rgba(252,123,4,0.1); }
.est-modal-form .form-control:read-only { background: #f1f5f9; cursor: not-allowed; }
.est-modal-form .form-select:disabled { background: #f1f5f9; cursor: not-allowed; }

/* ── Field wrapper con validation icon (estilo estudiantes) ── */
.est-modal-form .est-field { position: relative; }
.est-modal-form .est-field .form-control { border: 1px solid var(--est-border); border-radius: 10px; padding: 0.55rem 2.5rem 0.55rem 0.9rem; font-size: 0.85rem; color: var(--est-text); background: #faf8f5; transition: border-color 0.2s, box-shadow 0.2s, background 0.2s; width: 100%; }
.est-modal-form .est-field .form-control:focus { border-color: var(--est-primary); box-shadow: 0 0 0 3px rgba(252,123,4,0.1); background: #fff; outline: none; }
.est-modal-form .est-field .form-control.is-valid { border-color: var(--est-success); background: #f4faf6; }
.est-modal-form .est-field .form-control.is-invalid { border-color: var(--est-danger); background: #fef4f4; }
.est-modal-form .est-field .form-control:read-only { background: #f1f5f9; cursor: not-allowed; }
.est-modal-form .est-field .validation-icon { position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%); font-size: 1rem; pointer-events: none; opacity: 0; transition: opacity 0.2s; }
.est-modal-form .est-field .validation-icon.valid,
.est-modal-form .est-field .validation-icon.invalid { opacity: 1; }
.est-modal-form .est-field .validation-icon.valid i { color: var(--est-success); }
.est-modal-form .est-field .validation-icon.invalid i { color: var(--est-danger); }
.est-modal-form .est-field .form-select { border: 1px solid var(--est-border); border-radius: 10px; padding: 0.55rem 2.5rem 0.55rem 0.9rem; font-size: 0.85rem; color: var(--est-text); background: #faf8f5; transition: border-color 0.2s, box-shadow 0.2s; width: 100%; cursor: pointer; }
.est-modal-form .est-field .form-select:focus { border-color: var(--est-primary); box-shadow: 0 0 0 3px rgba(252,123,4,0.1); background: #fff; outline: none; }
.est-feedback { font-size: 0.76rem; margin-top: 0.3rem; min-height: 1rem; display: flex; align-items: center; gap: 0.3rem; transition: color 0.2s; }
.est-feedback.error { color: var(--est-danger); }
.est-feedback.success { color: var(--est-success); }

/* ── Photo upload (estilo estudiantes) ── */
.est-photo-wrap { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; }
.est-photo-circle { width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 3px solid var(--est-border); cursor: pointer; transition: border-color 0.25s, box-shadow 0.25s; position: relative; }
.est-photo-circle:hover { border-color: var(--est-primary); box-shadow: 0 0 0 4px rgba(252,123,4,0.1); }
.est-photo-circle img { width: 100%; height: 100%; object-fit: cover; }
.est-photo-circle .est-photo-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.35); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.25s; border-radius: 50%; }
.est-photo-circle:hover .est-photo-overlay { opacity: 1; }
.est-photo-circle .est-photo-overlay i { color: #fff; font-size: 1.4rem; }
.est-photo-hint { font-size: 0.72rem; color: var(--est-text-muted); }

/* ── Botones del modal footer ── */
.est-btn-cancel { background: #f0ece8; color: var(--est-text); border: none; border-radius: 8px; font-size: 0.82rem; font-weight: 500; padding: 0.4rem 1rem; transition: background 0.15s; }
.est-btn-cancel:hover { background: #e5dfd9; color: var(--est-text); }
.est-btn-submit { background: linear-gradient(135deg, #391b04, #c96004); color: #fff; border: none; border-radius: 8px; font-size: 0.82rem; font-weight: 700; padding: 0.4rem 1.15rem; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(252,123,4,0.3); cursor: pointer; }
.est-btn-submit:hover:not(:disabled) { box-shadow: 0 6px 16px rgba(252,123,4,0.4); transform: translateY(-1px); color: #fff; }
.est-btn-submit:disabled { opacity: 0.55; cursor: not-allowed; box-shadow: none; }
.est-btn-danger { background: #dc2626; color: #fff; border: none; border-radius: 8px; font-size: 0.82rem; font-weight: 700; padding: 0.4rem 1.15rem; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(220,38,38,0.25); cursor: pointer; }
.est-btn-danger:hover { background: #b91c1c; color: #fff; box-shadow: 0 6px 16px rgba(220,38,38,0.35); }

/* ════════════════ WHATSAPP MODAL ════════════════ */
.wa-modal-content { background: white; border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,.18); }
.wa-modal-header { position: relative; display: flex; align-items: flex-start; gap: 10px; padding: 1.1rem 1.25rem; background: linear-gradient(135deg, #075e54 0%, #128c7e 50%, #25d366 100%); overflow: hidden; }
.wa-modal-header-deco { position: absolute; top: -50px; right: -30px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
.wa-modal-header-body { display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0; position: relative; z-index: 1; }
.wa-modal-icon { width: 42px; height: 42px; flex-shrink: 0; background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.28); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: white; }
.wa-modal-header-text { flex: 1; min-width: 0; }
.wa-modal-title { font-size: .95rem; font-weight: 700; color: white; margin: 0 0 2px; }
.wa-modal-subtitle { font-size: .73rem; color: rgba(255,255,255,.82); margin: 0; }
.wa-modal-close { position: relative; z-index: 1; flex-shrink: 0; width: 30px; height: 30px; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25); border-radius: 8px; color: white; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background .2s; }
.wa-modal-close:hover { background: rgba(255,255,255,.28); }
.wa-persona-bar { display: flex; align-items: center; gap: 14px; padding: 1rem 1.25rem; background: #f0fdf4; border-bottom: 1px solid #d1fae5; }
.wa-persona-avatar { width: 46px; height: 46px; border-radius: 50%; background: linear-gradient(135deg, #25d366, #128c7e); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: white; flex-shrink: 0; box-shadow: 0 3px 10px rgba(37,211,102,.3); }
.wa-persona-rol { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #059669; display: flex; align-items: center; gap: 4px; margin-bottom: 2px; }
.wa-persona-nombre { font-size: .95rem; font-weight: 700; color: #065f46; }
.wa-modal-body { padding: 1.1rem 1.25rem; }
.wa-preview-label { display: flex; align-items: center; gap: 5px; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #64748b; margin-bottom: .6rem; }
.wa-preview-label i { color: #25d366; }
.wa-bubble-wrap { background: #e9f5fb; border-radius: 12px; padding: .85rem 1rem .6rem; position: relative; margin-bottom: .9rem; }
.wa-bubble { background: white; border-radius: 0 10px 10px 10px; padding: .75rem 1rem; box-shadow: 0 1px 4px rgba(0,0,0,.08); display: flex; flex-direction: column; gap: .45rem; position: relative; }
.wa-bubble::before { content: ''; position: absolute; top: 0; left: -8px; width: 0; height: 0; border-top: 8px solid white; border-left: 8px solid transparent; }
.wa-bubble-row { display: flex; align-items: baseline; gap: 6px; font-size: .85rem; line-height: 1.4; }
.wa-bubble-key { font-weight: 700; color: #1e293b; white-space: nowrap; flex-shrink: 0; }
.wa-bubble-val { color: #334155; }
.wa-mono { font-family: 'Courier New', monospace; font-size: .82rem; }
.wa-pass { background: #f0fdf4; border: 1px solid #86efac; border-radius: 5px; padding: 1px 8px; font-weight: 600; color: #15803d; }
.wa-bubble-tick { text-align: right; margin-top: .3rem; font-size: .82rem; color: #34b7f1; }
.wa-note { display: flex; align-items: flex-start; gap: .6rem; padding: .65rem .9rem; background: #fffbeb; border: 1px solid rgba(245,158,11,.25); border-radius: 8px; font-size: .78rem; color: #92400e; line-height: 1.55; }
.wa-note-icon { width: 22px; height: 22px; background: rgba(245,158,11,.15); border-radius: 5px; display: flex; align-items: center; justify-content: center; font-size: .85rem; color: #d97706; flex-shrink: 0; }
.wa-modal-footer { display: flex; align-items: center; justify-content: space-between; gap: .5rem; padding: .85rem 1.25rem; background: #f8fafc; border-top: 1px solid #e2e8f0; }
.wa-btn-reset { display: inline-flex; align-items: center; gap: .35rem; padding: .45rem 1rem; background: white; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: .8rem; font-weight: 600; color: #475569; cursor: pointer; transition: all .2s; font-family: inherit; }
.wa-btn-reset:hover { border-color: #94a3b8; color: #334155; }
.wa-btn-send { display: inline-flex; align-items: center; gap: .4rem; padding: .45rem 1.2rem; background: linear-gradient(135deg, #25d366, #128c7e); border: none; border-radius: 8px; color: white; font-size: .82rem; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(37,211,102,.3); transition: all .2s; font-family: inherit; }
.wa-btn-send:hover { background: linear-gradient(135deg, #1da851, #0d6e60); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(37,211,102,.35); }
</style>
@endsection

@section('content')
<div class="dept-page-header">
    <div class="container-fluid">
        <div class="dph-inner">
            <div class="dph-left">
                <div class="dph-icon-wrap"><i class="ri-user-star-line"></i></div>
                <div>
                    <h1 class="dph-title">Trabajadores</h1>
                    <p class="dph-desc">Gestión y asignación de trabajadores y sus cargos</p>
                </div>
            </div>
            <div class="dph-right">
                <div class="dph-stat-card">
                    <div class="dph-stat-icon"><i class="ri-hashtag"></i></div>
                    <div>
                        <div class="dph-stat-num" id="stat-total">—</div>
                        <div class="dph-stat-label">Total Registros</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    {{-- ═══ SEARCH ═══ --}}
    <div class="tw-search-card">
        <div class="tw-search-row">
            <div class="tw-search-input">
                <label><i class="ri-id-card-line" style="color:#fc7b04;"></i> Buscar por Carnet</label>
                <input type="text" class="form-control" id="searchCarnet" inputmode="numeric"
                       placeholder="Solo dígitos (7 a 11)" maxlength="11" autocomplete="off">
                <div class="search-validation-hint" id="searchHint">
                    <i class="ri-information-line"></i> Ingrese entre 7 y 11 dígitos numéricos.
                </div>
            </div>
            <div class="tw-search-input" style="min-width:180px;">
                <label><i class="ri-briefcase-line" style="color:#fc7b04;"></i> Filtrar por Cargo</label>
                <select class="form-select" id="filterCargo">
                    <option value="">Todos los cargos</option>
                </select>
            </div>
            <button type="button" class="tw-btn-search" id="btnBuscar" disabled>
                <i class="ri-search-line"></i> Buscar
            </button>
            <button type="button" class="tw-btn-register" id="btnAbrirRegistro" disabled
                    title="Primero realice una búsqueda. Si la persona no existe, podrá registrarla.">
                <i class="ri-user-add-line"></i> Registrar Trabajador
            </button>
            <button type="button" class="btn btn-modal-cancel" id="btnLimpiarBusqueda" style="display:none;">
                <i class="ri-close-line"></i> Limpiar
            </button>
        </div>
    </div>

    {{-- ═══ PERSONA FOUND ═══ --}}
    <div class="tw-persona-found" id="personaFound">
        <div class="tw-pf-header">
            <h5><i class="ri-user-line"></i> Persona Encontrada</h5>
            <span class="tw-pf-badge" id="pfCarnet"></span>
        </div>
        <div class="tw-pf-body">
            <div class="tw-pf-grid">
                <div class="tw-pf-item"><label>Nombres</label><span id="pfNombres"></span></div>
                <div class="tw-pf-item"><label>Apellido Paterno</label><span id="pfApPaterno"></span></div>
                <div class="tw-pf-item"><label>Apellido Materno</label><span id="pfApMaterno"></span></div>
                <div class="tw-pf-item"><label>Sexo</label><span id="pfSexo"></span></div>
                <div class="tw-pf-item"><label>Estado Civil</label><span id="pfEstadoCivil"></span></div>
                <div class="tw-pf-item"><label>Correo</label><span id="pfCorreo"></span></div>
                <div class="tw-pf-item"><label>Celular</label><span id="pfCelular"></span></div>
                <div class="tw-pf-item"><label>Ciudad</label><span id="pfCiudad"></span></div>
            </div>
            <div id="yaTrabajadorBox" class="tw-ya-trabajador" style="display:none;">
                <i class="ri-checkbox-circle-fill"></i>
                <span>Esta persona ya está registrada como trabajador.</span>
            </div>

            <div id="noEsTrabajadorBox" class="tw-no-trabajador" style="display:none;">
                <div class="tw-no-trab-icon"><i class="ri-information-line"></i></div>
                <div class="tw-no-trab-text">
                    <strong>Esta persona aún no es trabajador.</strong>
                    <div>¿Desea registrarla asignándole uno o más cargos?</div>
                </div>
                <button type="button" class="tw-btn-confirm" id="btnRegistrarPersonaExistente">
                    <i class="ri-user-add-line"></i> Sí, asignar como Trabajador
                </button>
            </div>
        </div>
        <div class="tw-pf-actions">
            <button type="button" class="btn btn-modal-cancel" id="btnLimpiarBusqueda2">
                <i class="ri-close-line"></i> Limpiar
            </button>
        </div>
    </div>

    {{-- ═══ CARGOS SELECTION ═══ --}}
    {{-- Sección legacy (oculta — la lógica ahora vive en el modal de abajo) --}}
    <div class="tw-cargos-section" id="cargosSection" style="display:none;"></div>

    {{-- ═══ MODAL ASIGNAR CARGOS ═══ --}}
    <div class="modal fade" id="modalAsignarCargos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-briefcase-line"></i> Asignar Cargos
                        <span id="acNombrePersona" style="color:#fc7b04;font-weight:700;margin-left:6px;"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="ac-info-row">
                        <i class="ri-information-line"></i>
                        <span>Marque uno o más cargos y complete la información requerida.</span>
                    </div>
                    <div id="cargosList"></div>
                    <div id="cargosError" class="field-feedback error" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-cancel" id="btnCancelarCargos" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-modal-submit" id="btnConfirmarAsignacion">
                        <i class="ri-check-line"></i> Guardar Asignación
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ TABLE ═══ --}}
    <div class="row">
        <div class="col-12">
            <div class="dept-card">
                <div class="dept-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="dept-header-icon"><i class="ri-table-line"></i></div>
                        <div>
                            <h5 class="dept-title">Listado de Trabajadores</h5>
                            <p class="dept-subtitle">Consulta y gestiona los trabajadores registrados</p>
                        </div>
                    </div>
                </div>
                <div class="dept-card-body">
                    <table id="tabla-trabajadores" class="dept-table">
                        <thead>
                            <tr>
                                <th>Carnet</th>
                                <th>Nombres y Apellidos</th>
                                <th>Cargos Asignados</th>
                                <th class="text-center" style="width:110px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════ MODAL REGISTRAR TRABAJADOR ════════════════ --}}
<div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl est-modal-form">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3 flex-grow-1" style="position:relative;z-index:1;">
                    <div class="est-modal-icon"><i class="ri-user-add-line"></i></div>
                    <div>
                        <h5 class="modal-title" id="modalRegistroTitle">Registrar Nuevo Trabajador</h5>
                        <div class="est-modal-subtitle">Completa los datos para registrar al trabajador en el sistema</div>
                    </div>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formRegistro" novalidate autocomplete="off" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="registroYaTrabajador" class="est-ya-trabajador" style="display:none;margin:1rem 1.5rem;background:rgba(90,138,48,0.08);border:1px solid rgba(90,138,48,0.2);border-radius:10px;padding:0.7rem 1rem;display:flex;align-items:center;gap:0.5rem;">
                        <i class="ri-checkbox-circle-fill" style="color:#5a8a30;font-size:1.1rem;"></i>
                        <span style="font-size:0.82rem;font-weight:600;color:#5a8a30;">Esta persona ya está registrada como trabajador.</span>
                    </div>

                    {{-- FOTOGRAFÍA --}}
                    <div class="est-modal-section" style="text-align:center;">
                        <div class="est-modal-section-title" style="justify-content:center;">
                            <div class="est-modal-section-title-icon" style="background:rgba(252,123,4,.12);">
                                <i class="ri-camera-line" style="color:#fc7b04;"></i>
                            </div>
                            <span class="est-modal-section-title-text" style="color:#fc7b04;">Fotografía</span>
                        </div>
                        <div class="est-photo-wrap">
                            <div class="est-photo-circle" onclick="document.getElementById('fotografiaRegistro').click()">
                                <img id="previewFotografiaRegistro" src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" alt="Foto">
                                <div class="est-photo-overlay"><i class="ri-camera-line"></i></div>
                            </div>
                            <input type="file" id="fotografiaRegistro" name="fotografia" accept="image/*" style="display:none;"
                                   onchange="previewImage(this, 'previewFotografiaRegistro')">
                            <small class="est-photo-hint">Click para seleccionar una imagen</small>
                        </div>
                    </div>

                    {{-- IDENTIDAD --}}
                    <div class="est-modal-section">
                        <div class="est-modal-section-title">
                            <div class="est-modal-section-title-icon" style="background:rgba(154,73,4,.12);">
                                <i class="ri-fingerprint-line" style="color:#9a4904;"></i>
                            </div>
                            <span class="est-modal-section-title-text" style="color:#9a4904;">Identidad</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="est-flabel">Carnet <span class="text-danger">*</span></label>
                                <div class="est-field">
                                    <input type="text" class="form-control" id="rCarnet" placeholder="Ej: 12345678" maxlength="20" autocomplete="off">
                                    <span class="validation-icon" id="iconRCarnet"></span>
                                </div>
                                <div class="est-feedback" id="fbRCarnet"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel"><i class="ri-map-pin-line"></i> Expedido</label>
                                <input type="text" class="form-control" id="rExpedido" placeholder="Ej: LP" maxlength="10" autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel"><i class="ri-calendar-line"></i> Fecha Nacimiento</label>
                                <input type="date" class="form-control" id="rFechaNacimiento">
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel">Sexo</label>
                                <select class="form-select" id="rSexo">
                                    <option value="">Seleccionar...</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="est-flabel">Nombres <span class="text-danger">*</span></label>
                                <div class="est-field">
                                    <input type="text" class="form-control" id="rNombres" placeholder="Ej: Juan Carlos" maxlength="100" autocomplete="off">
                                    <span class="validation-icon" id="iconRNombres"></span>
                                </div>
                                <div class="est-feedback" id="fbRNombres"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel">Apellido Paterno</label>
                                <input type="text" class="form-control" id="rApPaterno" placeholder="Ej: García" maxlength="80" autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel">Apellido Materno</label>
                                <input type="text" class="form-control" id="rApMaterno" placeholder="Ej: López" maxlength="80" autocomplete="off">
                            </div>
                            <div class="col-12">
                                <div class="est-feedback" id="fbRApellidos"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="est-flabel"><i class="ri-heart-line"></i> Estado Civil</label>
                                <select class="form-select" id="rEstadoCivil">
                                    <option value="">Seleccionar...</option>
                                    <option value="Soltero/a">Soltero/a</option>
                                    <option value="Casado/a">Casado/a</option>
                                    <option value="Divorciado/a">Divorciado/a</option>
                                    <option value="Viudo/a">Viudo/a</option>
                                    <option value="Unión Libre">Unión Libre</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="est-flabel"><i class="ri-map-pin-line"></i> Departamento</label>
                                <select class="form-select" id="rDepto">
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="est-flabel"><i class="ri-building-line"></i> Ciudad</label>
                                <select class="form-select" id="rCiudad" disabled>
                                    <option value="">Seleccione departamento primero</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- CONTACTO --}}
                    <div class="est-modal-section est-modal-section-warm">
                        <div class="est-modal-section-title">
                            <div class="est-modal-section-title-icon" style="background:rgba(252,123,4,.12);">
                                <i class="ri-contacts-line" style="color:#fc7b04;"></i>
                            </div>
                            <span class="est-modal-section-title-text" style="color:#c96004;">Contacto y Ubicación</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="est-flabel">Correo Electrónico</label>
                                <div class="est-field">
                                    <input type="email" class="form-control" id="rCorreo" placeholder="correo@ejemplo.com" maxlength="150" autocomplete="off">
                                    <span class="validation-icon" id="iconRCorreo"></span>
                                </div>
                                <div class="est-feedback" id="fbRCorreo"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel">Celular <span class="text-danger">*</span></label>
                                <div class="est-field">
                                    <input type="text" class="form-control" id="rCelular" inputmode="numeric" placeholder="70000000" maxlength="8" autocomplete="off">
                                    <span class="validation-icon" id="iconRCelular"></span>
                                </div>
                                <div class="est-feedback" id="fbRCelular"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel">Teléfono</label>
                                <input type="text" class="form-control" id="rTelefono" placeholder="2000000" maxlength="20" autocomplete="off">
                            </div>
                            <div class="col-12">
                                <label class="est-flabel"><i class="ri-map-pin-2-line"></i> Dirección</label>
                                <input type="text" class="form-control" id="rDireccion" placeholder="Ej: Av. 6 de Agosto N° 123" maxlength="200" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="est-btn-cancel" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="submit" class="est-btn-submit" id="btnGuardarTrabajador"><i class="ri-save-line"></i> Registrar Trabajador</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════ MODAL EDITAR ════════════════ --}}
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable est-modal-form">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3 flex-grow-1" style="position:relative;z-index:1;">
                    <div class="est-modal-icon"><i class="ri-pencil-line"></i></div>
                    <div>
                        <h5 class="modal-title">Editar Trabajador</h5>
                        <div class="est-modal-subtitle">Modifica los datos del trabajador y gestiona sus cargos</div>
                    </div>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditar" novalidate autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" id="idEditar">
                <div class="modal-body">
                    {{-- FOTOGRAFÍA --}}
                    <div class="est-modal-section" style="text-align:center;">
                        <div class="est-modal-section-title" style="justify-content:center;">
                            <div class="est-modal-section-title-icon" style="background:rgba(252,123,4,.12);">
                                <i class="ri-camera-line" style="color:#fc7b04;"></i>
                            </div>
                            <span class="est-modal-section-title-text" style="color:#fc7b04;">Fotografía</span>
                        </div>
                        <div class="est-photo-wrap">
                            <div class="est-photo-circle" onclick="document.getElementById('fotografiaEditar').click()">
                                <img id="previewFotografiaEditar" src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" alt="Foto">
                                <div class="est-photo-overlay"><i class="ri-camera-line"></i></div>
                            </div>
                            <input type="file" id="fotografiaEditar" name="fotografia" accept="image/*" style="display:none;"
                                   onchange="previewImage(this, 'previewFotografiaEditar')">
                            <small class="est-photo-hint">Click para cambiar la imagen</small>
                        </div>
                    </div>

                    {{-- IDENTIDAD --}}
                    <div class="est-modal-section">
                        <div class="est-modal-section-title">
                            <div class="est-modal-section-title-icon" style="background:rgba(154,73,4,.12);">
                                <i class="ri-fingerprint-line" style="color:#9a4904;"></i>
                            </div>
                            <span class="est-modal-section-title-text" style="color:#9a4904;">Identidad</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="est-flabel">Carnet <span class="text-danger">*</span></label>
                                <div class="est-field">
                                    <input type="text" class="form-control" id="eCarnet" placeholder="Ej: 12345678" maxlength="20" autocomplete="off">
                                    <span class="validation-icon" id="iconECarnet"></span>
                                </div>
                                <div class="est-feedback" id="fbECarnet"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel"><i class="ri-map-pin-line"></i> Expedido</label>
                                <input type="text" class="form-control" id="eExpedido" placeholder="Ej: LP" maxlength="10" autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel"><i class="ri-calendar-line"></i> Fecha Nacimiento</label>
                                <input type="date" class="form-control" id="eFechaNacimiento">
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel">Sexo</label>
                                <select class="form-select" id="eSexo">
                                    <option value="">Seleccionar...</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="est-flabel">Nombres <span class="text-danger">*</span></label>
                                <div class="est-field">
                                    <input type="text" class="form-control" id="eNombres" placeholder="Ej: Juan Carlos" maxlength="100" autocomplete="off">
                                    <span class="validation-icon" id="iconENombres"></span>
                                </div>
                                <div class="est-feedback" id="fbENombres"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel">Apellido Paterno</label>
                                <input type="text" class="form-control" id="eApPaterno" placeholder="Ej: García" maxlength="80" autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel">Apellido Materno</label>
                                <input type="text" class="form-control" id="eApMaterno" placeholder="Ej: López" maxlength="80" autocomplete="off">
                            </div>
                            <div class="col-12">
                                <div class="est-feedback" id="fbEApellidos"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="est-flabel"><i class="ri-heart-line"></i> Estado Civil</label>
                                <select class="form-select" id="eEstadoCivil">
                                    <option value="">Seleccionar...</option>
                                    <option value="Soltero/a">Soltero/a</option>
                                    <option value="Casado/a">Casado/a</option>
                                    <option value="Divorciado/a">Divorciado/a</option>
                                    <option value="Viudo/a">Viudo/a</option>
                                    <option value="Unión Libre">Unión Libre</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="est-flabel"><i class="ri-map-pin-line"></i> Departamento</label>
                                <select class="form-select" id="eDepto">
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="est-flabel"><i class="ri-building-line"></i> Ciudad</label>
                                <select class="form-select" id="eCiudad" disabled>
                                    <option value="">Seleccione departamento primero</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- CONTACTO --}}
                    <div class="est-modal-section est-modal-section-warm">
                        <div class="est-modal-section-title">
                            <div class="est-modal-section-title-icon" style="background:rgba(252,123,4,.12);">
                                <i class="ri-contacts-line" style="color:#fc7b04;"></i>
                            </div>
                            <span class="est-modal-section-title-text" style="color:#c96004;">Contacto y Ubicación</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="est-flabel">Correo Electrónico</label>
                                <div class="est-field">
                                    <input type="email" class="form-control" id="eCorreo" placeholder="correo@ejemplo.com" maxlength="150" autocomplete="off">
                                    <span class="validation-icon" id="iconECorreo"></span>
                                </div>
                                <div class="est-feedback" id="fbECorreo"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel">Celular <span class="text-danger">*</span></label>
                                <div class="est-field">
                                    <input type="text" class="form-control" id="eCelular" inputmode="numeric" placeholder="70000000" maxlength="8" autocomplete="off">
                                    <span class="validation-icon" id="iconECelular"></span>
                                </div>
                                <div class="est-feedback" id="fbECelular"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="est-flabel">Teléfono</label>
                                <input type="text" class="form-control" id="eTelefono" placeholder="2000000" maxlength="20" autocomplete="off">
                            </div>
                            <div class="col-12">
                                <label class="est-flabel"><i class="ri-map-pin-2-line"></i> Dirección</label>
                                <input type="text" class="form-control" id="eDireccion" placeholder="Ej: Av. 6 de Agosto N° 123" maxlength="200" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    {{-- CARGOS ACTUALES --}}
                    <div class="est-modal-section">
                        <div class="est-modal-section-title">
                            <div class="est-modal-section-title-icon" style="background:rgba(90,138,48,.12);">
                                <i class="ri-briefcase-line" style="color:#5a8a30;"></i>
                            </div>
                            <span class="est-modal-section-title-text" style="color:#5a8a30;">Cargos Asignados</span>
                        </div>
                        <div id="editCargosActuales"></div>
                    </div>

                    {{-- NUEVOS CARGOS --}}
                    <div class="est-modal-section">
                        <div class="est-modal-section-title">
                            <div class="est-modal-section-title-icon" style="background:rgba(124,58,237,.12);">
                                <i class="ri-add-circle-line" style="color:#7c3aed;"></i>
                            </div>
                            <span class="est-modal-section-title-text" style="color:#7c3aed;">Agregar Nuevos Cargos</span>
                        </div>
                        <div id="editCargosNuevos"></div>
                        <div id="editCargosError" class="est-feedback error" style="display:none;margin-top:.5rem;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="est-btn-cancel" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="submit" class="est-btn-submit" id="btnConfirmarEdicion"><i class="ri-check-line"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════ MODAL ELIMINAR CARGO ════════════════ --}}
<div class="modal fade" id="modalEliminarCargo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-error-warning-line"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="delete-warning-box">
                    <div class="delete-icon-ring"><i class="ri-briefcase-line"></i></div>
                    <p class="delete-msg-primary">¿Quitar este cargo?</p>
                    <p class="delete-msg-name"><strong id="nombreCargoEliminar"></strong></p>
                    <p class="delete-msg-warn"><i class="ri-information-line"></i> El cargo será removido del trabajador.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button type="button" class="btn btn-modal-cancel px-4" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-danger-modal px-4" id="btnConfirmarEliminarCargo"><i class="ri-delete-bin-line"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════ MODAL ELIMINAR ════════════════ --}}
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-error-warning-line"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="delete-warning-box">
                    <div class="delete-icon-ring"><i class="ri-delete-bin-5-line"></i></div>
                    <p class="delete-msg-primary">¿Eliminar trabajador?</p>
                    <p class="delete-msg-name"><strong id="nombreEliminar"></strong></p>
                    <p class="delete-msg-warn"><i class="ri-information-line"></i> Esta acción es permanente y no puede deshacerse.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button type="button" class="btn btn-modal-cancel px-4" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-danger-modal px-4" id="btnConfirmarEliminar"><i class="ri-delete-bin-line"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalWhatsappAccesos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content wa-modal-content">
            <div class="wa-modal-header">
                <div class="wa-modal-header-deco"></div>
                <div class="wa-modal-header-body">
                    <div class="wa-modal-icon">
                        <i class="ri-whatsapp-line"></i>
                    </div>
                    <div class="wa-modal-header-text">
                        <h5 class="wa-modal-title">Enviar Accesos por WhatsApp</h5>
                        <p class="wa-modal-subtitle">Credenciales de acceso &mdash; Trabajador</p>
                    </div>
                </div>
                <button type="button" class="wa-modal-close" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="wa-persona-bar">
                <div class="wa-persona-avatar">
                    <i class="ri-user-3-line"></i>
                </div>
                <div class="wa-persona-info">
                    <div class="wa-persona-rol">
                        <i class="ri-briefcase-line"></i> Trabajador
                    </div>
                    <div class="wa-persona-nombre" id="waModalNombre">—</div>
                </div>
            </div>
            <div class="wa-modal-body">
                <div class="wa-preview-label">
                    <i class="ri-message-3-line"></i> Vista previa del mensaje
                </div>
                <div class="wa-bubble-wrap">
                    <div class="wa-bubble">
                        <div class="wa-bubble-row">
                            <span class="wa-bubble-key">Trabajador:</span>
                            <span id="waModalNombrePreview" class="wa-bubble-val"></span>
                        </div>
                        <div class="wa-bubble-row">
                            <span class="wa-bubble-key">Usuario:</span>
                            <span id="waModalUsuario" class="wa-bubble-val wa-mono"></span>
                        </div>
                        <div class="wa-bubble-row">
                            <span class="wa-bubble-key">Contraseña:</span>
                            <span id="waModalPassword" class="wa-bubble-val wa-mono wa-pass"></span>
                        </div>
                    </div>
                    <div class="wa-bubble-tick">
                        <i class="ri-check-double-line"></i>
                    </div>
                </div>
                <div class="wa-note">
                    <div class="wa-note-icon"><i class="ri-information-line"></i></div>
                    <p class="mb-0">Si el trabajador cambi&oacute; su contraseña en Moodle, usa <strong>Restablecer</strong> para sincronizarla al valor original antes de enviar.</p>
                </div>
                <input type="hidden" id="waModalCelular">
                <input type="hidden" id="waModalTrabajadorId">
            </div>
            <div class="wa-modal-footer">
                <button type="button" id="waModalBtnReset" class="wa-btn-reset">
                    <i class="ri-refresh-line"></i> Restablecer contraseña
                </button>
                <button type="button" id="waModalBtnEnviar" class="wa-btn-send">
                    <i class="ri-whatsapp-line"></i> Enviar por WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCrearCuentasMoodle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">
            <div class="modal-header" style="background:linear-gradient(135deg,#3b1900 0%,#7a3b03 50%,#c96004 100%);color:white;padding:1.25rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(10px);flex-shrink:0;">
                        <i class="ri-cloud-line" style="font-size:1.4rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;letter-spacing:-.01em;color:#fff;">Crear Cuenta de Usuario</h5>
                        <div style="font-size:.73rem;opacity:.75;margin-top:.15rem;letter-spacing:.01em;color:rgba(255,255,255,.85);">Portal del trabajador + plataforma Moodle</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:0;">
                <div id="moodleCuentasLoading" class="text-center" style="padding:3rem 1.5rem;">
                    <div class="spinner-border" style="color:#fc7b04;width:2.25rem;height:2.25rem;" role="status"></div>
                    <p class="mt-3 mb-0" style="font-size:.85rem;color:#64748b;font-weight:500;">Verificando cuenta del trabajador…</p>
                </div>
                <div id="moodleCuentasEmpty" style="display:none;padding:3rem 1.5rem;text-align:center;">
                    <div style="width:68px;height:68px;background:linear-gradient(135deg,rgba(252,123,4,.12),rgba(154,73,4,.05));border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.1rem;border:2px solid rgba(252,123,4,.2);">
                        <i class="ri-shield-check-line" style="font-size:1.9rem;color:#fc7b04;"></i>
                    </div>
                    <h6 style="font-weight:700;color:#1e293b;margin-bottom:.4rem;font-size:.95rem;">¡Todo está al día!</h6>
                    <p style="font-size:.83rem;color:#64748b;margin:0;max-width:300px;margin-inline:auto;line-height:1.6;">Este trabajador ya tiene cuentas activas en el sistema y en Moodle.</p>
                </div>
                <div id="moodleCuentasList" style="display:none;">
                    <div style="padding:1rem 1.5rem;background:linear-gradient(135deg,rgba(252,123,4,.05),rgba(154,73,4,.03));border-bottom:1px solid rgba(252,123,4,.1);">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div id="moodleCuentasCountBadge" style="display:inline-flex;align-items:center;gap:.45rem;background:rgba(252,123,4,.1);border:1px solid rgba(252,123,4,.2);color:#9a4904;font-size:.8rem;font-weight:700;padding:.3rem .8rem;border-radius:20px;white-space:nowrap;">
                                <i class="ri-user-line"></i>
                                <span id="moodleCuentasCount">0</span> sin cuenta
                            </div>
                            <p class="mb-0" style="font-size:.78rem;color:#64748b;flex:1;line-height:1.55;">Se creará la cuenta del <strong style="color:#475569;">portal</strong> y de <strong style="color:#475569;">Moodle</strong> con las mismas credenciales para el trabajador seleccionado.</p>
                        </div>
                    </div>
                    <div style="padding:.75rem 1.25rem 1.25rem;max-height:360px;overflow-y:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:.8rem;" id="tablaCuentasMoodle">
                            <thead>
                                <tr style="border-bottom:2px solid #e2e8f0;">
                                    <th style="padding:.65rem .5rem;width:40px;text-align:center;">
                                        <input type="checkbox" id="selectAllMoodleAccounts" style="width:16px;height:16px;accent-color:#fc7b04;cursor:pointer;border-radius:4px;">
                                    </th>
                                    <th style="padding:.65rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;text-align:left;">Trabajador</th>
                                    <th style="padding:.65rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;text-align:left;">CI</th>
                                    <th style="padding:.65rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;text-align:left;">Usuario sugerido</th>
                                    <th style="padding:.65rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;text-align:left;">Contraseña</th>
                                </tr>
                            </thead>
                            <tbody id="moodleCuentasTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;gap:.5rem;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="btnConfirmarCrearCuentas" disabled
                    style="background:linear-gradient(135deg,#fc7b04,#d46604);border:none;border-radius:8px;font-size:.82rem;padding:.4rem 1.15rem;font-weight:600;box-shadow:0 4px 12px rgba(252,123,4,.3);transition:all .2s;">
                    <i class="ri-user-add-line me-1"></i>Crear Cuenta
                </button>
            </div>
        </div>
    </div>
</div>

<div id="toastContainer" class="toast-container"></div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

(function () {
    'use strict';

    let tabla;
    let idEliminar = null;
    let personaSeleccionada = null;
    let editTrabajadorId = null;
    let editPersonaId = null;
    let editCargosEliminados = [];
    let originalData = {};
    let cargoEliminarId = null;
    let todosCargos = [];
    let todasCiudades = [];
    let todasSedes = [];
    let carnetTimer = null, correoTimer = null;
    const CSRF = '{{ csrf_token() }}';

    /* ── INIT ── */
    function init() {
        cargarSelectores();
        initDataTable();
        bindEvents();
    }

    /* ── SELECTORES ── */
    function cargarSelectores() {
        $.getJSON('{{ route("admin.trabajadores.listarCargos") }}', function (r) {
            todosCargos = r.data;
            const opts = r.data.map(c => '<option value="' + c.id + '">' + esc(c.nombre) + '</option>').join('');
            $('#filterCargo').append(opts);
        });
        $.getJSON('{{ route("admin.trabajadores.listarSedes") }}', function (r) {
            todasSedes = r.data;
        });
        $.getJSON('{{ route("admin.trabajadores.listarDepartamentos") }}', function (r) {
            const opts = r.data.map(d => '<option value="' + d.id + '">' + esc(d.nombre) + '</option>').join('');
            $('#pDepto').append(opts);
            $('#rDepto').append(opts);
            $('#eDepto').append(opts);
        });
        $.getJSON('{{ route("admin.trabajadores.listarCiudades") }}', function (r) {
            todasCiudades = r.data;
        });
    }

    /* ── CASCADA DEPARTAMENTO → CIUDAD ── */
    function filtrarCiudades(deptoId, $ciudad) {
        $ciudad.find('option:not(:first)').remove();
        if (!deptoId) {
            $ciudad.prop('disabled', true).find('option:first').text('— Seleccione depto. —');
            return;
        }
        const filtradas = todasCiudades.filter(function (c) { return c.departamento_id == deptoId; });
        $ciudad.append(filtradas.map(c => '<option value="' + c.id + '">' + esc(c.nombre) + '</option>').join(''));
        $ciudad.prop('disabled', false).find('option:first').text('— Seleccione ciudad —');
    }

    $('#pDepto').on('change', function () {
        filtrarCiudades($(this).val(), $('#pCiudad'));
    });

    $('#rDepto').on('change', function () {
        filtrarCiudades($(this).val(), $('#rCiudad'));
    });

    /* ── DATATABLE ── */
    function initDataTable() {
        tabla = $('#tabla-trabajadores').DataTable({
            ajax: { 
                url: '{{ route("admin.trabajadores.listar") }}', 
                dataSrc: 'data',
                data: function(d) {
                    d.cargo_id = $('#filterCargo').val();
                }
            },
            ordering: true,
            columns: [
                {
                    data: null,
                    render: d => {
                        const p = d.persona;
                        if (!p) return '<span style="color:var(--d-muted)">—</span>';
                        let txt = '<span style="font-weight:700;">' + esc(p.carnet) + '</span>';
                        if (p.expedido) txt += '<br><small style="color:var(--d-muted);font-size:0.72rem;">exp. ' + esc(p.expedido) + '</small>';
                        return txt;
                    }
                },
                {
                    data: null,
                    render: d => {
                        const p = d.persona;
                        if (!p) return '<span style="color:var(--d-muted)">—</span>';
                        const n = esc(p.nombres);
                        const ap = [p.apellido_paterno, p.apellido_materno].filter(Boolean).map(esc).join(' ');
                        return '<span style="font-weight:600;">' + n + '</span>' + (ap ? '<br><small style="color:var(--d-muted);">' + ap + '</small>' : '');
                    }
                },
                {
                    data: null,
                    render: d => {
                        const cargos = d.trabajadores_cargos || [];
                        if (cargos.length === 0) return '<span style="color:var(--d-muted)">—</span>';
                        let h = '<div class="tw-cargos-list">';
                        cargos.forEach(function (tc) {
                            const estadoClass = tc.estado === 'Vigente' ? 'badge-vigente' : 'badge-no-vigente';
                            let label = esc(tc.cargo ? tc.cargo.nombre : '');
                            if (tc.sucursale) {
                                const sede = tc.sucursale.sede;
                                label += ' <small style="opacity:0.7;">(' + (sede ? esc(sede.nombre) : '') + ' / ' + esc(tc.sucursale.nombre) + ')</small>';
                            }
                            h += '<span class="badge-cargo-asignado ' + estadoClass + '">' + label + '</span>';
                        });
                        h += '</div>';
                        return h;
                    }
                },
                {
                    data: null, className: 'text-center',
                    render: d => {
                        const p = d.persona || {};
                        const nombre = esc((p.nombres || '') + ' ' + (p.apellido_paterno || ''));
                        const tieneUsuario = !!d.tiene_usuario;
                        const username     = d.usuario_username || '';
                        const password     = d.usuario_moodle_password || '';
                        const celular      = (p.celular || '').toString().replace(/\D/g, '');
                        const puedeWa      = tieneUsuario && celular.length >= 8;
                        const necesitaCuenta = !tieneUsuario;

                        let html = '<div class="est-action-cell">'
                            + '<button type="button" class="est-btn-action est-btn-edit btn-accion-editar" data-id="' + d.id + '" title="Editar trabajador"><i class="ri-pencil-fill"></i></button>';

                        if (necesitaCuenta) {
                            html += '<button type="button" class="est-btn-action est-btn-cuenta btn-crear-cuentas" data-id="' + d.id + '" title="Crear cuenta de sistema / Moodle"><i class="ri-user-add-line"></i></button>';
                        }

                        if (puedeWa) {
                            html += '<button type="button" class="est-btn-action est-btn-whatsapp btn-enviar-whatsapp"'
                                + ' data-id="' + d.id + '"'
                                + ' data-celular="' + esc(celular) + '"'
                                + ' data-nombre="' + nombre + '"'
                                + ' data-username="' + esc(username) + '"'
                                + ' data-password="' + esc(password) + '"'
                                + ' title="Enviar accesos por WhatsApp"><i class="ri-whatsapp-line"></i></button>';
                        }

                        html += '<button type="button" class="est-btn-action est-btn-delete btn-accion-eliminar" data-id="' + d.id + '" data-nombre="' + nombre + '" title="Eliminar trabajador"><i class="ri-delete-bin-fill"></i></button>'
                            + '</div>';
                        return html;
                    }
                }
            ],
            language: {
                processing: 'Procesando...', search: 'Buscar:', zeroRecords: 'No se encontraron registros',
                emptyTable: 'No hay datos disponibles',
                paginate: { first: 'Primero', previous: 'Anterior', next: 'Siguiente', last: 'Último' }
            },
            order: [[0, 'asc']],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
            pageLength: 10,
            drawCallback: function () {
                $('#stat-total').text(this.api().page.info().recordsTotal);
            }
        });
    }

    /* ── EVENTOS ── */
    function bindEvents() {
        /* Filtro cargo */
        $('#filterCargo').on('change', function() {
            tabla.ajax.reload(null, false);
        });

        /* Validación en tiempo real del carnet */
        $('#searchCarnet').on('input', function () {
            const val = $(this).val().replace(/\D/g, '');
            $(this).val(val);
            validarCarnetBusqueda(val);
            if (personaSeleccionada || $('#personaFound').is(':visible')) {
                $('#personaFound').slideUp(150);
                $('#cargosSection').slideUp(150);
                personaSeleccionada = null;
            }
            $('#btnAbrirRegistro').prop('disabled', true).attr('title', 'Primero realice una búsqueda. Si la persona no existe, podrá registrarla.');
        });
        $('#searchCarnet').on('keypress', function (e) {
            if (e.which === 13 && !$('#btnBuscar').prop('disabled')) buscarPersona();
        });

        /* Buscar */
        $('#btnBuscar').on('click', buscarPersona);

        /* Registro */
        $('#btnAbrirRegistro').on('click', abrirModalRegistro);
        $('#btnLimpiarBusqueda, #btnLimpiarBusqueda2').on('click', function () { limpiarBusqueda(); resetFormRegistro(); });

        /* Confirmar registro de persona existente como trabajador → abre modal de cargos */
        $('#btnRegistrarPersonaExistente').on('click', function () {
            mostrarCargos();
        });

        /* Cargos */
        $(document).on('change', '.tw-cargo-checkbox', function () {
            const $check = $(this);
            const $fields = $check.closest('.tw-cargo-item').find('.tw-cargo-fields');
            if ($check.is(':checked')) {
                $check.closest('.tw-cargo-check').addClass('checked');
                $fields.addClass('show');
            } else {
                $check.closest('.tw-cargo-check').removeClass('checked');
                $fields.removeClass('show');
            }
        });

        $('#btnConfirmarAsignacion').on('click', confirmarAsignacion);
        $('#btnCancelarCargos').on('click', function () {
            $('#cargosSection').slideUp(200);
        });

        /* Formulario registro */
        $('#formRegistro').on('submit', function (e) { e.preventDefault(); guardarTrabajador(); });
        $('#rDepto').on('change', filtrarCiudades);

        /* Validación carnet registro */
        $('#rCarnet').on('input', function () {
            clearTimeout(carnetTimer);
            const val = this.value.trim();
            if (!val) { return setError('rCarnet','iconRCarnet','fbRCarnet','El carnet es obligatorio.'); }
            if (val.length < 3) { return setError('rCarnet','iconRCarnet','fbRCarnet','Debe tener al menos 3 caracteres.'); }
            setChecking('rCarnet','iconRCarnet','fbRCarnet');
            carnetTimer = setTimeout(function () { verificarCarnetRegistro(val); }, 400);
        });

        $('#rNombres').on('input', function () { validarNombres('rNombres','iconRNombres','fbRNombres'); });
        $('#rApPaterno, #rApMaterno').on('input', validarApellidosRegistro);

        $('#rCorreo').on('input', function () {
            clearTimeout(correoTimer);
            const val = this.value.trim();
            if (!val) { return resetField('rCorreo','iconRCorreo','fbRCorreo'); }
            if (!isEmail(val)) { return setError('rCorreo','iconRCorreo','fbRCorreo','Formato de correo inválido.'); }
            setChecking('rCorreo','iconRCorreo','fbRCorreo');
            correoTimer = setTimeout(function () { verificarCorreoRegistro(val); }, 400);
        });

        /* Validación celular (registro y edición) */
        $('#rCelular').on('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 8);
            validarCelular('rCelular','iconRCelular','fbRCelular');
        });
        $('#eCelular').on('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 8);
            validarCelular('eCelular','iconECelular','fbECelular');
        });

        /* Eliminar */
        $(document).on('click', '.btn-accion-eliminar', function () {
            idEliminar = $(this).data('id');
            $('#nombreEliminar').text($(this).data('nombre'));
            openModal('modalEliminar');
        });
        $('#btnConfirmarEliminar').on('click', function () {
            if (idEliminar) eliminarTrabajador(idEliminar);
        });

        /* Editar */
        $(document).on('click', '.btn-accion-editar', function () {
            editarTrabajador($(this).data('id'));
        });
        $('#formEditar').on('submit', function (e) { e.preventDefault(); confirmarEdicion(); });

        /* ════════════════ CUENTAS (SISTEMA + MOODLE) ════════════════ */
        let trabajadoresSinCuenta = [];

        function generarUsernameMoodle(nombres, apellidoPaterno, apellidoMaterno) {
            const norm = s => (s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();
            const nombre = norm(nombres);
            const ap = norm(apellidoPaterno);
            const am = norm(apellidoMaterno);
            const parts = nombre.split(' ');
            const pn = parts[0] || '';
            const si = parts.length > 1 ? parts[1].charAt(0) : '';
            return {
                op1: (pn.charAt(0) + ap + am).replace(/\s/g, '').substring(0, 20),
                op2: (si + ap + am).replace(/\s/g, '').substring(0, 20),
                op3: (pn + ap + am).replace(/\s/g, '').substring(0, 20)
            };
        }

        function generarPassword(carnet) {
            const digits = (carnet || '').replace(/\D/g, '');
            return digits.length >= 7 ? digits : 'innova' + digits;
        }

        function crearCuentas(id) {
            $('#moodleCuentasLoading').show();
            $('#moodleCuentasEmpty').hide();
            $('#moodleCuentasList').hide();
            $('#btnConfirmarCrearCuentas').prop('disabled', true);
            openModal('modalCrearCuentasMoodle');

            $.get('{{ route("admin.trabajadores.obtener", ["id" => "__ID__"]) }}'.replace('__ID__', id))
                .done(function (r) {
                    const t = r.data;
                    const p = t.persona;
                    trabajadoresSinCuenta = [];

                    if (!t.tiene_cuenta_sistema || !t.tiene_cuenta_moodle) {
                        const nombres = p ? (p.nombres || '') : '';
                        const ap = p ? (p.apellido_paterno || '') : '';
                        const am = p ? (p.apellido_materno || '') : '';
                        const carnet = p ? (p.carnet || '') : '';
                        const fullName = [nombres, ap, am].filter(Boolean).join(' ');
                        const usernames = generarUsernameMoodle(nombres, ap, am);
                        const password = generarPassword(carnet);

                        trabajadoresSinCuenta.push({
                            id: t.id,
                            nombre: fullName,
                            ci: carnet,
                            correo: p ? (p.correo || '') : '',
                            usernames: usernames,
                            password: password
                        });
                    }

                    $('#moodleCuentasLoading').hide();

                    if (trabajadoresSinCuenta.length === 0) {
                        $('#moodleCuentasEmpty').show();
                    } else {
                        $('#moodleCuentasCount').text(trabajadoresSinCuenta.length);
                        renderTrabajadoresSinCuenta();
                        $('#moodleCuentasList').show();
                    }
                })
                .fail(function () {
                    closeModal('modalCrearCuentasMoodle');
                    toast('error', 'Error al cargar los datos del trabajador.');
                });
        }

        function renderTrabajadoresSinCuenta() {
            let html = '';
            trabajadoresSinCuenta.forEach(function (est) {
                html += '<tr style="border-bottom:1px solid #f1f5f9;">'
                    + '<td style="padding:.6rem .5rem;text-align:center;"><input type="checkbox" class="checkbox-moodle-account" data-id="' + est.id + '" checked style="width:16px;height:16px;accent-color:#fc7b04;cursor:pointer;"></td>'
                    + '<td style="padding:.6rem .75rem;font-weight:600;color:#1e293b;font-size:.82rem;">' + escHtml(est.nombre) + '</td>'
                    + '<td style="padding:.6rem .75rem;color:#64748b;font-size:.8rem;">' + escHtml(est.ci) + '</td>'
                    + '<td style="padding:.6rem .75rem;"><code style="font-size:.75rem;background:rgba(252,123,4,.07);color:#9a4904;padding:.15rem .4rem;border-radius:5px;border:1px solid rgba(252,123,4,.12);">' + escHtml(est.usernames.op1) + '</code></td>'
                    + '<td style="padding:.6rem .75rem;"><code style="font-size:.75rem;background:rgba(16,185,129,.07);color:#047857;padding:.15rem .4rem;border-radius:5px;border:1px solid rgba(16,185,129,.12);">' + escHtml(est.password) + '</code></td>'
                    + '</tr>';
            });
            $('#moodleCuentasTableBody').html(html);
            $('#btnConfirmarCrearCuentas').prop('disabled', false);
        }

        function escHtml(str) {
            return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        $(document).on('click', '.btn-crear-cuentas', function (e) {
            e.stopPropagation();
            crearCuentas($(this).data('id'));
        });

        $(document).on('change', '.checkbox-moodle-account', function () {
            const checked = $('.checkbox-moodle-account:checked').length;
            $('#btnConfirmarCrearCuentas').prop('disabled', checked === 0);
        });

        $('#selectAllMoodleAccounts').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('.checkbox-moodle-account').prop('checked', isChecked);
            $('#btnConfirmarCrearCuentas').prop('disabled', !isChecked);
        });

        $('#btnConfirmarCrearCuentas').on('click', function () {
            const seleccionados = [];
            $('.checkbox-moodle-account:checked').each(function () {
                const id = $(this).data('id');
                const est = trabajadoresSinCuenta.find(e => e.id === id);
                if (est) seleccionados.push(est);
            });

            if (seleccionados.length === 0) {
                toast('warning', 'Seleccione al menos un trabajador.');
                return;
            }

            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Creando...');

            const promises = seleccionados.map(est => {
                return $.ajax({
                    url: '{{ route("admin.trabajadores.crearCuentas", ["id" => "__ID__"]) }}'.replace('__ID__', est.id),
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });
            });

            Promise.all(promises.map(p => p.catch(e => e)))
                .then(results => {
                    let okCount = 0, failCount = 0;
                    results.forEach(r => {
                        if (r && r.success) okCount++; else failCount++;
                    });
                    closeModal('modalCrearCuentasMoodle');
                    if (okCount > 0) toast('success', okCount + ' cuenta(s) creada(s) correctamente.');
                    if (failCount > 0) toast('error', failCount + ' cuenta(s) no pudieron crearse.');
                    tabla.ajax.reload(null, false);
                })
                .always(function () {
                    btn.prop('disabled', false).html('<i class="ri-user-add-line me-1"></i>Crear Cuenta');
                });
        });

        /* ════════════════ WHATSAPP ════════════════ */
        $(document).on('click', '.btn-enviar-whatsapp', function (e) {
            e.stopPropagation();
            const btn = $(this);
            const celular  = btn.data('celular');
            const nombre   = btn.data('nombre');
            const username = btn.data('username');
            let password   = btn.data('password');
            const id       = btn.data('id');

            if (!password) {
                toast('warning', 'No se encontr\u00f3 la contraseña. Restablece primero.');
                return;
            }

            $('#waModalNombre').text(nombre);
            $('#waModalNombrePreview').text(nombre);
            $('#waModalUsuario').text(username);
            $('#waModalPassword').text(password);
            $('#waModalCelular').val(celular);
            $('#waModalTrabajadorId').val(id);
            $('#waModalBtnReset').data('btn-origen', btn);

            new bootstrap.Modal(document.getElementById('modalWhatsappAccesos')).show();
        });

        $('#waModalBtnEnviar').on('click', function () {
            const celular  = $('#waModalCelular').val();
            const nombre   = $('#waModalNombre').text();
            const username = $('#waModalUsuario').text();
            const password = $('#waModalPassword').text();

            const mensaje = '*\u00a1Bienvenido/a a la plataforma acad\u00e9mica!*\n\n'
                + '  Estimado/a ' + nombre + ',\n'
                + 'A continuaci\u00f3n encontrar\u00e1s tus credenciales de acceso a la plataforma virtual.\n\n'
                + '  *ACCESO A LA PLATAFORMA*\n'
                + '\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\n'
                + '   Sitio web:  https://posgradosinnovaciencia.com\n'
                + '   Usuario:      ' + username + '\n'
                + '   Contrase\u00f1a:  ' + password + '\n'
                + '\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\u25AC\n\n'
                + '  *PASOS PARA INGRESAR*\n'
                + '  Abre tu navegador (Chrome, Edge o Firefox)\n'
                + '  Visita \u2192 https://posgradosinnovaciencia.com\n'
                + '  Ingresa tu usuario y contraseña\n'
                + '  Completa tu perfil en el primer acceso\n\n'
                + '  *IMPORTANTE*\n'
                + '* Guarda tus credenciales en un lugar seguro.\n'
                + '* No compartas tu contraseña con nadie.\n'
                + '* Si olvidas tu acceso, cont\u00e1ctanos de inmediato.\n\n'
                + '  \u00a1\u00c9xitos en tu trabajo!\n\n'
                + '\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u00d7\n'
                + '  \u00c1rea Acad\u00e9mica\n'
                + '  Innova Ciencia Virtual\n'
                + '  soporte@posgradosinnovaciencia.com\n'
                + '  +591 XXX XXX XXX';

            window.open('https://wa.me/' + celular + '?text=' + encodeURIComponent(mensaje), '_blank');
            bootstrap.Modal.getInstance(document.getElementById('modalWhatsappAccesos')).hide();
        });

        $('#waModalBtnReset').on('click', function () {
            const id        = $('#waModalTrabajadorId').val();
            const btnReset  = $(this);
            const btnOrigen = btnReset.data('btn-origen');

            btnReset.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Restableciendo...');

            $.ajax({
                url: '{{ route("admin.trabajadores.resetPasswordMoodle", ["id" => "__ID__"]) }}'.replace('__ID__', id),
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    if (res.success) {
                        $('#waModalPassword').text(res.password);
                        if (btnOrigen) {
                            btnOrigen.data('password', res.password);
                        }
                        toast('success', 'Contrase\u00f1a restablecida en Moodle correctamente.');
                    } else {
                        toast('error', res.message || 'Error al restablecer la contraseña.');
                    }
                },
                error: function (xhr) {
                    const msg = xhr.responseJSON?.message || 'Error al conectar con Moodle.';
                    toast('error', msg);
                },
                complete: function () {
                    btnReset.prop('disabled', false).html('<i class="ri-refresh-line me-1"></i>Restablecer contraseña');
                }
            });
        });

        /* Validación editar */
        $('#eCarnet').on('input', function () {
            clearTimeout(carnetTimer);
            const val = this.value.trim();
            if (!val) { return setError('eCarnet','iconECarnet','fbECarnet','El carnet es obligatorio.'); }
            if (val.length < 3) { return setError('eCarnet','iconECarnet','fbECarnet','Debe tener al menos 3 caracteres.'); }
            setChecking('eCarnet','iconECarnet','fbECarnet');
            carnetTimer = setTimeout(function () { verificarCarnetEditar(val); }, 400);
        });
        $('#eNombres').on('input', function () { validarNombres('eNombres','iconENombres','fbENombres'); });
        $('#eApPaterno, #eApMaterno').on('input', validarApellidosEditar);
        $('#eCorreo').on('input', function () {
            clearTimeout(correoTimer);
            const val = this.value.trim();
            if (!val) { return resetField('eCorreo','iconECorreo','fbECorreo'); }
            if (!isEmail(val)) { return setError('eCorreo','iconECorreo','fbECorreo','Formato de correo inválido.'); }
            setChecking('eCorreo','iconECorreo','fbECorreo');
            correoTimer = setTimeout(function () { verificarCorreoEditar(val); }, 400);
        });
        $('#eDepto').on('change', function () { filtrarCiudades('Editar'); });

        /* Cargos edit - sede change */
        $(document).on('change', '.edit-cargo-sede', function () {
            const cargoId = $(this).data('cargo');
            const sedeId = $(this).val();
            const $sucursal = $('.edit-cargo-sucursal[data-cargo="' + cargoId + '"]');
            $sucursal.find('option:not(:first)').remove();
            if (!sedeId) {
                $sucursal.prop('disabled', true).find('option:first').text('— Seleccione sede —');
                return;
            }
            $sucursal.find('option:first').text('Cargando…');
            $.post('{{ route("admin.trabajadores.listarSucursalesPorSede") }}', { _token: CSRF, sede_id: sedeId })
                .done(function (r) {
                    $sucursal.find('option:first').text('— Seleccione sucursal —');
                    if (r.data.length === 0) {
                        $sucursal.prop('disabled', true).find('option:first').text('Sin sucursales');
                        return;
                    }
                    $sucursal.append(r.data.map(function (s) {
                        return '<option value="' + s.id + '">' + esc(s.nombre) + (s.direccion ? ' — ' + esc(s.direccion) : '') + '</option>';
                    }).join(''));
                    $sucursal.prop('disabled', false);
                })
                .fail(function () {
                    $sucursal.prop('disabled', true).find('option:first').text('Error al cargar');
                });
        });

        $(document).on('change', '.edit-cargo-checkbox', function () {
            const $check = $(this);
            const $fields = $check.closest('.edit-cargo-item').find('.edit-cargo-fields');
            if ($check.is(':checked')) {
                $check.closest('.edit-cargo-check').addClass('checked');
                $fields.addClass('show');
            } else {
                $check.closest('.edit-cargo-check').removeClass('checked');
                $fields.removeClass('show');
            }
        });

        document.getElementById('modalRegistro').addEventListener('hidden.bs.modal', resetFormRegistro);
        document.getElementById('modalEditar').addEventListener('hidden.bs.modal', function () {
            editTrabajadorId = null;
            editPersonaId = null;
            editCargosEliminados = [];
            originalData = {};
        });
    }

    /* ── VALIDACIÓN EN TIEMPO REAL DEL CARNET DE BÚSQUEDA ── */
    function validarCarnetBusqueda(val) {
        const $input = $('#searchCarnet');
        const $hint  = $('#searchHint');
        const $btn   = $('#btnBuscar');

        if (val.length === 0) {
            $input.removeClass('is-valid is-invalid');
            $hint.removeClass('success error').html('<i class="ri-information-line"></i> Ingrese entre 7 y 11 dígitos numéricos.');
            $btn.prop('disabled', true);
            return false;
        }
        if (!/^\d+$/.test(val)) {
            $input.removeClass('is-valid').addClass('is-invalid');
            $hint.removeClass('success').addClass('error').html('<i class="ri-close-circle-fill"></i> Solo se permiten números.');
            $btn.prop('disabled', true);
            return false;
        }
        if (val.length < 7) {
            $input.removeClass('is-valid').addClass('is-invalid');
            $hint.removeClass('success').addClass('error').html('<i class="ri-close-circle-fill"></i> Faltan ' + (7 - val.length) + ' dígitos (mínimo 7).');
            $btn.prop('disabled', true);
            return false;
        }
        if (val.length > 11) {
            $input.removeClass('is-valid').addClass('is-invalid');
            $hint.removeClass('success').addClass('error').html('<i class="ri-close-circle-fill"></i> Máximo 11 dígitos.');
            $btn.prop('disabled', true);
            return false;
        }
        $input.removeClass('is-invalid').addClass('is-valid');
        $hint.removeClass('error').addClass('success').html('<i class="ri-checkbox-circle-fill"></i> Carnet válido (' + val.length + ' dígitos). Presione buscar.');
        $btn.prop('disabled', false);
        return true;
    }

    /* ── BUSCAR PERSONA ── */
    function buscarPersona() {
        const carnet = $('#searchCarnet').val().trim();
        if (!validarCarnetBusqueda(carnet)) {
            toast('warning', 'Ingrese un carnet válido (7 a 11 dígitos).');
            return;
        }

        setBtnLoading('#btnBuscar', true, 'Buscando…');
        $.post('{{ route("admin.trabajadores.buscarCarnet") }}', { _token: CSRF, carnet: carnet })
            .done(function (r) {
                if (r.encontrado) {
                    personaSeleccionada = r.persona;
                    mostrarPersonaEncontrada(r.persona, r.ya_trabajador);
                    $('#btnAbrirRegistro').prop('disabled', true).attr('title', 'La persona ya existe. Use el botón "Sí, asignar como Trabajador" en el panel inferior.');
                } else {
                    $('#personaFound').slideUp(200);
                    $('#cargosSection').slideUp(200);
                    personaSeleccionada = null;
                    $('#btnAbrirRegistro').prop('disabled', false).removeAttr('title');
                    $('#btnLimpiarBusqueda').show();
                    toast('info', 'No se encontró ninguna persona con ese carnet. Puede registrarla como nueva.');
                }
            })
            .fail(function () { toast('error', 'Error al buscar. Intente nuevamente.'); })
            .always(function () { setBtnLoading('#btnBuscar', false, '<i class="ri-search-line"></i> Buscar'); });
    }

    /* ── MOSTRAR PERSONA ENCONTRADA ── */
    function mostrarPersonaEncontrada(p, yaTrabajador) {
        $('#pfCarnet').text(p.carnet);
        $('#pfNombres').text(p.nombres || '—');
        $('#pfApPaterno').text(p.apellido_paterno || '—');
        $('#pfApMaterno').text(p.apellido_materno || '—');
        $('#pfSexo').text(p.sexo ? (p.sexo === 'M' ? 'Masculino' : 'Femenino') : '—');
        $('#pfEstadoCivil').text(p.estado_civil || '—');
        $('#pfCorreo').text(p.correo || '—');
        $('#pfCelular').text(p.celular || '—');
        $('#pfCiudad').text(p.ciudad ? p.ciudad.nombre : '—');

        if (yaTrabajador) {
            $('#yaTrabajadorBox').show();
            $('#noEsTrabajadorBox').hide();
        } else {
            $('#yaTrabajadorBox').hide();
            $('#noEsTrabajadorBox').css('display','flex').hide().slideDown(220);
        }

        $('#personaFound').slideDown(300);
        $('#cargosSection').slideUp(200);
        $('#btnLimpiarBusqueda').show();
        $('#btnLimpiarBusqueda2').show();
    }

    /* ── MOSTRAR CARGOS ── */
    function mostrarCargos() {
        if (!personaSeleccionada) return;

        const sedeOptions = '<option value="">— Seleccione sede —</option>' +
            todasSedes.map(function (s) { return '<option value="' + s.id + '">' + esc(s.nombre) + '</option>'; }).join('');

        let html = '';
        todosCargos.forEach(function (c) {
            const today = new Date().toISOString().split('T')[0];
            html += '<div class="tw-cargo-item">'
                + '<label class="tw-cargo-check">'
                + '<input type="checkbox" class="tw-cargo-checkbox" value="' + c.id + '">'
                + '<span class="tw-cargo-name">' + esc(c.nombre) + '</span>'
                + '</label>'
                + '<div class="tw-cargo-fields">'
                + '<div class="row g-2">'
                + '<div class="col-md-6">'
                + '<div class="tw-sucursal-label"><i class="ri-building-line" style="color:#fc7b04;"></i> Sede</div>'
                + '<select class="form-select tw-cargo-sede" data-cargo="' + c.id + '">' + sedeOptions + '</select>'
                + '</div>'
                + '<div class="col-md-6">'
                + '<div class="tw-sucursal-label"><i class="ri-building-2-line" style="color:#fc7b04;"></i> Sucursal</div>'
                + '<select class="form-select tw-cargo-sucursal" data-cargo="' + c.id + '" disabled>'
                + '<option value="">— Seleccione sede —</option>'
                + '</select>'
                + '</div>'
                + '<div class="col-md-4">'
                + '<label>Estado</label>'
                + '<select class="form-select tw-cargo-estado" data-cargo="' + c.id + '">'
                + '<option value="Vigente">Vigente</option>'
                + '<option value="No Vigente">No Vigente</option>'
                + '</select>'
                + '</div>'
                + '<div class="col-md-4">'
                + '<label>Fecha de Ingreso</label>'
                + '<input type="date" class="form-control tw-cargo-fecha-ingreso" data-cargo="' + c.id + '" value="' + today + '">'
                + '</div>'
                + '<div class="col-md-4">'
                + '<label>Fecha de Término</label>'
                + '<input type="date" class="form-control tw-cargo-fecha-termino" data-cargo="' + c.id + '">'
                + '</div>'
                + '</div></div></div>';
        });

        const nombrePersona = [personaSeleccionada.nombres, personaSeleccionada.apellido_paterno, personaSeleccionada.apellido_materno]
            .filter(Boolean).join(' ');
        $('#acNombrePersona').text(nombrePersona ? '— ' + nombrePersona : '');
        $('#cargosList').html(html);
        $('#cargosError').hide();

        /* Bind sede change → load sucursales */
        $(document).off('change', '.tw-cargo-sede').on('change', '.tw-cargo-sede', function () {
            const cargoId = $(this).data('cargo');
            const sedeId = $(this).val();
            const $sucursal = $('.tw-cargo-sucursal[data-cargo="' + cargoId + '"]');
            $sucursal.find('option:not(:first)').remove();
            if (!sedeId) {
                $sucursal.prop('disabled', true).find('option:first').text('— Seleccione sede —');
                return;
            }
            $sucursal.find('option:first').text('Cargando…');
            $.post('{{ route("admin.trabajadores.listarSucursalesPorSede") }}', { _token: CSRF, sede_id: sedeId })
                .done(function (r) {
                    $sucursal.find('option:first').text('— Seleccione sucursal —');
                    if (r.data.length === 0) {
                        $sucursal.prop('disabled', true).find('option:first').text('Sin sucursales');
                        return;
                    }
                    $sucursal.append(r.data.map(function (s) {
                        return '<option value="' + s.id + '">' + esc(s.nombre) + (s.direccion ? ' — ' + esc(s.direccion) : '') + '</option>';
                    }).join(''));
                    $sucursal.prop('disabled', false);
                })
                .fail(function () {
                    $sucursal.prop('disabled', true).find('option:first').text('Error al cargar');
                });
        });

        openModal('modalAsignarCargos');
    }

    /* ── CONFIRMAR ASIGNACIÓN ── */
    function confirmarAsignacion() {
        const cargosSeleccionados = [];
        let hasError = false;

        $('.tw-cargo-checkbox:checked').each(function () {
            if (hasError) return;
            const cargoId = $(this).val();
            const sucursalId = $('.tw-cargo-sucursal[data-cargo="' + cargoId + '"]').val();
            const estado = $('.tw-cargo-estado[data-cargo="' + cargoId + '"]').val();
            const fechaIngreso = $('.tw-cargo-fecha-ingreso[data-cargo="' + cargoId + '"]').val();
            const fechaTermino = $('.tw-cargo-fecha-termino[data-cargo="' + cargoId + '"]').val() || null;

            if (!sucursalId) {
                $('#cargosError').text('Debe seleccionar una sucursal para cada cargo asignado.').show();
                hasError = true;
                return;
            }
            if (!fechaIngreso) {
                $('#cargosError').text('La fecha de ingreso es obligatoria para todos los cargos seleccionados.').show();
                hasError = true;
                return;
            }

            cargosSeleccionados.push({
                cargo_id: cargoId,
                sucursale_id: sucursalId,
                estado: estado,
                fecha_ingreso: fechaIngreso,
                fecha_termino: fechaTermino
            });
        });

        if (hasError) return;

        if (cargosSeleccionados.length === 0) {
            $('#cargosError').text('Debe seleccionar al menos un cargo.').show();
            return;
        }

        setBtnLoading('#btnConfirmarAsignacion', true, 'Asignando…');
        $.post('{{ route("admin.trabajadores.asignar") }}', {
            _token: CSRF,
            persona_id: personaSeleccionada.id,
            cargos: cargosSeleccionados
        })
        .done(function (r) {
            toast('success', r.message);
            closeModal('modalAsignarCargos');
            limpiarBusqueda();
            tabla.ajax.reload(null, false);
        })
        .fail(function (xhr) {
            const msg = xhr.responseJSON?.errors
                ? Object.values(xhr.responseJSON.errors).flat().join(' ')
                : xhr.responseJSON?.message || 'Error al asignar.';
            toast('error', msg);
        })
        .always(function () {
            setBtnLoading('#btnConfirmarAsignacion', false, '<i class="ri-check-line"></i> Guardar Asignación');
        });
    }

    /* ── ABRIR MODAL REGISTRO ── */
    function abrirModalRegistro() {
        resetFormRegistro();

        if (personaSeleccionada) {
            const p = personaSeleccionada;
            $('#rCarnet').val(p.carnet).prop('readonly', true);
            $('#rNombres').val(p.nombres || '').prop('readonly', true);
            $('#rApPaterno').val(p.apellido_paterno || '').prop('readonly', true);
            $('#rApMaterno').val(p.apellido_materno || '').prop('readonly', true);
            $('#rCorreo').val(p.correo || '').prop('readonly', true);
            $('#rCelular').val(p.celular || '').prop('readonly', true);
            $('#rTelefono').val(p.telefono || '').prop('readonly', true);
            $('#rDireccion').val(p.direccion || '').prop('readonly', true);
            $('#rExpedido').val(p.expedido || '').prop('readonly', true);
            $('#rFechaNacimiento').val(p.fecha_nacimiento || '').prop('readonly', true);
            $('#rSexo').val(p.sexo || '').prop('disabled', true);
            $('#rEstadoCivil').val(p.estado_civil || '').prop('disabled', true);

            if (p.ciudad) {
                $('#rDepto').val(p.ciudad.departamento_id).trigger('change');
                setTimeout(function () {
                    $('#rCiudad').val(p.ciudad.id).prop('disabled', true);
                }, 200);
            }

            ['rCarnet','rNombres'].forEach(function (id) {
                const input = document.getElementById(id);
                if (input) { input.classList.remove('is-invalid'); input.classList.add('is-valid'); }
            });
            if (p.correo) {
                const input = document.getElementById('rCorreo');
                if (input) { input.classList.remove('is-invalid'); input.classList.add('is-valid'); }
            }

            $.post('{{ route("admin.trabajadores.buscarCarnet") }}', { _token: CSRF, carnet: p.carnet })
                .done(function (r) {
                    if (r.ya_trabajador) {
                        $('#registroYaTrabajador').show();
                        $('#btnGuardarTrabajador').prop('disabled', true).html('<i class="ri-error-warning-line"></i> Ya es trabajador');
                    } else {
                        $('#registroYaTrabajador').hide();
                        $('#btnGuardarTrabajador').prop('disabled', false).html('<i class="ri-save-line"></i> Registrar Trabajador');
                    }
                });

            $('#modalRegistroTitle').html('<i class="ri-user-check-line"></i> Registrar como Trabajador — ' + esc(p.carnet));
        } else {
            // Pre-llenar carnet desde búsqueda y bloquearlo
            const carnetBuscado = $('#searchCarnet').val().trim();
            if (carnetBuscado) {
                $('#rCarnet').val(carnetBuscado).prop('readonly', true);
                const input = document.getElementById('rCarnet');
                if (input) { input.classList.remove('is-invalid'); input.classList.add('is-valid'); }
                $('#fbRCarnet').removeClass('error').addClass('success').html('<i class="ri-checkbox-circle-fill"></i> Carnet desde la búsqueda (no editable).');
            }
            $('#modalRegistroTitle').html('<i class="ri-user-add-line"></i> Registrar Nuevo Trabajador' + (carnetBuscado ? ' — ' + esc(carnetBuscado) : ''));
        }

        openModal('modalRegistro');
    }

    /* ── GUARDAR TRABAJADOR ── */
    function guardarTrabajador() {
        const okC = validarCarnetSync('rCarnet','iconRCarnet','fbRCarnet');
        const okN = validarNombres('rNombres','iconRNombres','fbRNombres');
        const okAp = validarApellidosRegistro();
        const okCel = validarCelular('rCelular','iconRCelular','fbRCelular');
        if (!okC || !okN || !okAp || !okCel) return;
        if (document.getElementById('rCarnet').classList.contains('is-invalid')) return;
        if (document.getElementById('rCorreo').classList.contains('is-invalid')) return;

        setBtnLoading('#btnGuardarTrabajador', true, 'Guardando…');
        
        var formData = new FormData();
        formData.append('_token', CSRF);
        formData.append('carnet', $('#rCarnet').val().trim());
        formData.append('expedido', $('#rExpedido').val().trim());
        formData.append('nombres', $('#rNombres').val().trim());
        formData.append('apellido_paterno', $('#rApPaterno').val().trim());
        formData.append('apellido_materno', $('#rApMaterno').val().trim());
        formData.append('sexo', $('#rSexo').val());
        formData.append('estado_civil', $('#rEstadoCivil').val());
        formData.append('fecha_nacimiento', $('#rFechaNacimiento').val() || '');
        formData.append('correo', $('#rCorreo').val().trim());
        formData.append('direccion', $('#rDireccion').val().trim());
        formData.append('celular', $('#rCelular').val().trim());
        formData.append('telefono', $('#rTelefono').val().trim());
        formData.append('ciudade_id', $('#rCiudad').val() || '');

        var fotoInput = document.getElementById('fotografiaRegistro');
        if (fotoInput && fotoInput.files && fotoInput.files[0]) {
            formData.append('fotografia', fotoInput.files[0]);
        }

        $.ajax({
            url: '{{ route("admin.trabajadores.guardarPersona") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        })
        .done(function (r) {
            closeModal('modalRegistro');
            toast('success', r.message || 'Persona registrada. Asígnele uno o más cargos para completar el registro.');
            // Carnet en el campo de búsqueda + persona seleccionada en memoria
            $('#searchCarnet').val(r.data.carnet);
            validarCarnetBusqueda(r.data.carnet);
            personaSeleccionada = r.data;
            mostrarPersonaEncontrada(r.data, false);
            // Abrir directamente el modal de asignar cargos
            setTimeout(function () { mostrarCargos(); }, 200);
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                const errs = xhr.responseJSON.errors || {};
                if (errs.carnet) setError('rCarnet','iconRCarnet','fbRCarnet', errs.carnet[0]);
                if (errs.nombres) setError('rNombres','iconRNombres','fbRNombres', errs.nombres[0]);
                if (errs.correo) setError('rCorreo','iconRCorreo','fbRCorreo', errs.correo[0]);
                if (errs.celular) setError('rCelular','iconRCelular','fbRCelular', errs.celular[0]);
                if (errs.apellidos) {
                    $('#fbRApellidos').addClass('error').html('<i class="ri-error-warning-line"></i>' + errs.apellidos[0]);
                }
            } else {
                toast('error', 'Error al registrar. Intente nuevamente.');
            }
        })
        .always(function () {
            setBtnLoading('#btnGuardarTrabajador', false, '<i class="ri-save-line"></i> Registrar Trabajador');
        });
    }

    /* ── GUARDAR PERSONA ── */
    function guardarPersona() {
        const okC = validarCarnetSync('pCarnet','iconPCarnet','fbPCarnet');
        const okN = validarNombres('pNombres','iconPNombres','fbPNombres');
        const okAp = validarApellidos();
        if (!okC || !okN || !okAp) return;
        if (document.getElementById('pCarnet').classList.contains('is-invalid')) return;
        if (document.getElementById('pCorreo').classList.contains('is-invalid')) return;

        setBtnLoading('#btnGuardarPersona', true, 'Guardando…');
        $.post('{{ route("admin.trabajadores.guardarPersona") }}', {
            _token: CSRF,
            carnet: $('#pCarnet').val().trim(),
            expedido: $('#pExpedido').val().trim(),
            nombres: $('#pNombres').val().trim(),
            apellido_paterno: $('#pApPaterno').val().trim(),
            apellido_materno: $('#pApMaterno').val().trim(),
            sexo: $('#pSexo').val(),
            estado_civil: $('#pEstadoCivil').val(),
            fecha_nacimiento: $('#pFechaNacimiento').val() || null,
            correo: $('#pCorreo').val().trim(),
            direccion: $('#pDireccion').val().trim(),
            celular: $('#pCelular').val().trim(),
            telefono: $('#pTelefono').val().trim(),
            ciudade_id: $('#pCiudad').val() || null,
        })
        .done(function (r) {
            closeModal('modalPersona');
            toast('success', r.message);
            // Auto-buscar la persona recién creada
            $('#searchCarnet').val(r.data.carnet);
            buscarPersona();
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                const errs = xhr.responseJSON.errors || {};
                if (errs.carnet) setError('pCarnet','iconPCarnet','fbPCarnet', errs.carnet[0]);
                if (errs.nombres) setError('pNombres','iconPNombres','fbPNombres', errs.nombres[0]);
                if (errs.correo) setError('pCorreo','iconPCorreo','fbPCorreo', errs.correo[0]);
                if (errs.apellidos) {
                    $('#fbPApellidos').addClass('error').html('<i class="ri-error-warning-line"></i>' + errs.apellidos[0]);
                }
            } else {
                toast('error', 'Error al registrar. Intente nuevamente.');
            }
        })
        .always(function () {
            setBtnLoading('#btnGuardarPersona', false, '<i class="ri-save-line"></i> Registrar Persona');
        });
    }

/* ── EDITAR TRABAJADOR ── */
    function editarTrabajador(id) {
        editTrabajadorId = id;
        editPersonaId = null;
        editCargosEliminados = [];

        setBtnLoading('#btnConfirmarEdicion', true, 'Cargando…');
        $.get('{{ route("admin.trabajadores.obtener", ["id" => "__ID__"]) }}'.replace('__ID__', id))
            .done(function (r) {
                const t = r.data;
                const p = t.persona;
                if (!p) {
                    toast('error', 'No se encontró la persona.');
                    setBtnLoading('#btnConfirmarEdicion', false, '<i class="ri-check-line"></i> Guardar Cambios');
                    return;
                }
                editPersonaId = p.id;

                /* Datos personales */
                $('#eCarnet').val(p.carnet || '');
                $('#eExpedido').val(p.expedido || '');
                $('#eNombres').val(p.nombres || '');
                $('#eApPaterno').val(p.apellido_paterno || '');
                $('#eApMaterno').val(p.apellido_materno || '');
                $('#eSexo').val(p.sexo || '');
                $('#eEstadoCivil').val(p.estado_civil || '');
                $('#eFechaNacimiento').val(p.fecha_nacimiento || '');
                $('#eCorreo').val(p.correo || '');
                $('#eCelular').val(p.celular || '');
                $('#eTelefono').val(p.telefono || '');
                $('#eDireccion').val(p.direccion || '');

                /* Guardar datos originales para comparar cambios */
                originalData = {
                    eCarnet: p.carnet || '',
                    eExpedido: p.expedido || '',
                    eNombres: p.nombres || '',
                    eApPaterno: p.apellido_paterno || '',
                    eApMaterno: p.apellido_materno || '',
                    eSexo: p.sexo || '',
                    eEstadoCivil: p.estado_civil || '',
                    eFechaNacimiento: p.fecha_nacimiento || '',
                    eCorreo: p.correo || '',
                    eCelular: p.celular || '',
                    eTelefono: p.telefono || '',
                    eDireccion: p.direccion || '',
                    eCiudad: p.ciudad ? p.ciudad.id : ''
                };

                /* Validar celular cargado */
                if ($('#eCelular').val()) {
                    validarCelular('eCelular','iconECelular','fbECelular');
                }

                /* Fotografía */
                if (p.fotografia) {
                    var fotoUrl = '{{ url("images/personas") }}/' + p.fotografia;
                    $('#previewFotografiaEditar').attr('src', fotoUrl);
                } else {
                    $('#previewFotografiaEditar').attr('src', '{{ URL::asset("build/images/users/avatar-1.jpg") }}');
                }

                /* Departamento y Ciudad */
                if (p.ciudad) {
                    $('#eDepto').val(p.ciudad.departamento_id);
                    $.when(filtrarCiudadesAsync('Editar')).then(function () {
                        $('#eCiudad').val(p.ciudad.id);
                    });
                } else {
                    $('#eDepto').val('');
                    $('#eCiudad').val('').prop('disabled', true);
                }

                /* Cargos actuales */
                let htmlActuales = '';
                const cargosActuales = t.trabajadores_cargos || [];
                if (cargosActuales.length === 0) {
                    htmlActuales = '<p style="color:var(--d-muted);font-size:0.85rem;">Sin cargos asignados.</p>';
                } else {
                    htmlActuales = '<div class="table-responsive"><table class="table table-sm align-middle mb-0" style="font-size:0.82rem;">';
                    htmlActuales += '<thead><tr><th>Cargo</th><th>Sede / Sucursal</th><th>Estado</th><th>Fecha Ingreso</th><th>Fecha Término</th><th style="width:80px;"></th></tr></thead><tbody>';
                    cargosActuales.forEach(function (tc) {
                        const sede = tc.sucursale ? (tc.sucursale.sede ? tc.sucursale.sede.nombre + ' / ' : '') + tc.sucursale.nombre : '—';
                        const estadoBadge = tc.estado === 'Vigente'
                            ? '<span class="badge bg-success-subtle text-success">Vigente</span>'
                            : '<span class="badge bg-warning-subtle text-warning">No Vigente</span>';
                        htmlActuales += '<tr>'
                            + '<td>' + esc(tc.cargo ? tc.cargo.nombre : '') + '</td>'
                            + '<td>' + esc(sede) + '</td>'
                            + '<td>' + estadoBadge + '</td>'
                            + '<td>' + (tc.fecha_ingreso || '—') + '</td>'
                            + '<td>' + (tc.fecha_termino || '—') + '</td>'
                            + '<td><button class="btn btn-sm btn-outline-danger btn-quitar-cargo" data-id="' + tc.id + '" data-cargo-id="' + tc.cargo_id + '" data-nombre="' + esc(tc.cargo ? tc.cargo.nombre : '') + '" title="Quitar cargo"><i class="ri-delete-bin-line"></i></button></td>'
                            + '</tr>';
                    });
                    htmlActuales += '</tbody></table></div>';
                }
                $('#editCargosActuales').html(htmlActuales);

                /* Cargos nuevos — excluir los que ya tiene */
                const cargoIdsActuales = cargosActuales.map(function (tc) { return tc.cargo_id; });
                const cargosDisponibles = todosCargos.filter(function (c) { return cargoIdsActuales.indexOf(c.id) === -1; });

                const sedeOptions = '<option value="">— Seleccione sede —</option>' +
                    todasSedes.map(function (s) { return '<option value="' + s.id + '">' + esc(s.nombre) + '</option>'; }).join('');
                let htmlNuevos = '';
                if (cargosDisponibles.length === 0) {
                    htmlNuevos = '<p style="color:var(--d-muted);font-size:0.85rem;">No hay cargos disponibles para agregar.</p>';
                } else {
                    cargosDisponibles.forEach(function (c) {
                        const today = new Date().toISOString().split('T')[0];
                        htmlNuevos += '<div class="edit-cargo-item">'
                            + '<label class="tw-cargo-check edit-cargo-check">'
                            + '<input type="checkbox" class="edit-cargo-checkbox" value="' + c.id + '">'
                            + '<span class="tw-cargo-name">' + esc(c.nombre) + '</span>'
                            + '</label>'
                            + '<div class="tw-cargo-fields edit-cargo-fields">'
                            + '<div class="row g-2">'
                            + '<div class="col-md-6">'
                            + '<div class="tw-sucursal-label"><i class="ri-building-line" style="color:#fc7b04;"></i> Sede</div>'
                            + '<select class="form-select edit-cargo-sede" data-cargo="' + c.id + '">' + sedeOptions + '</select>'
                            + '</div>'
                            + '<div class="col-md-6">'
                            + '<div class="tw-sucursal-label"><i class="ri-building-2-line" style="color:#fc7b04;"></i> Sucursal</div>'
                            + '<select class="form-select edit-cargo-sucursal" data-cargo="' + c.id + '" disabled>'
                            + '<option value="">— Seleccione sede —</option>'
                            + '</select>'
                            + '</div>'
                            + '<div class="col-md-4">'
                            + '<label>Estado</label>'
                            + '<select class="form-select edit-cargo-estado" data-cargo="' + c.id + '">'
                            + '<option value="Vigente">Vigente</option>'
                            + '<option value="No Vigente">No Vigente</option>'
                            + '</select>'
                            + '</div>'
                            + '<div class="col-md-4">'
                            + '<label>Fecha de Ingreso</label>'
                            + '<input type="date" class="form-control edit-cargo-fecha-ingreso" data-cargo="' + c.id + '" value="' + today + '">'
                            + '</div>'
                            + '<div class="col-md-4">'
                            + '<label>Fecha de Término</label>'
                            + '<input type="date" class="form-control edit-cargo-fecha-termino" data-cargo="' + c.id + '">'
                            + '</div>'
                            + '</div></div></div>';
                    });
                }
                $('#editCargosNuevos').html(htmlNuevos);
                $('#editCargosError').hide();

                openModal('modalEditar');
            })
            .fail(function () {
                toast('error', 'Error al cargar los datos del trabajador.');
            })
            .always(function () {
                setBtnLoading('#btnConfirmarEdicion', false, '<i class="ri-check-line"></i> Guardar Cambios');
            });
    }

    /* ── Quitar cargo actual ── */
    $(document).on('click', '.btn-quitar-cargo', function () {
        cargoEliminarId = $(this).data('id');
        $('#nombreCargoEliminar').text($(this).data('nombre'));
        openModal('modalEliminarCargo');
    });
    $('#btnConfirmarEliminarCargo').on('click', function () {
        if (!cargoEliminarId) return;
        editCargosEliminados.push(cargoEliminarId);
        closeModal('modalEliminarCargo');
        $('.btn-quitar-cargo[data-id="' + cargoEliminarId + '"]').closest('tr').fadeOut(200, function () { $(this).remove(); });
        cargoEliminarId = null;
        toast('success', 'Cargo eliminado. Se aplicará al guardar cambios.');
        refreshCargosDisponibles();
    });

    /* ── Refrescar cargos disponibles en el modal de edición ── */
    function refreshCargosDisponibles() {
        const sedeOptions = '<option value="">— Seleccione sede —</option>' +
            todasSedes.map(function (s) { return '<option value="' + s.id + '">' + esc(s.nombre) + '</option>'; }).join('');

        const cargosIdsActuales = [];
        $('#editCargosActuales table tbody tr').each(function () {
            const $btn = $(this).find('.btn-quitar-cargo');
            const tcId = $btn.data('id');
            if (editCargosEliminados.indexOf(tcId) === -1) {
                cargosIdsActuales.push($btn.data('cargo-id'));
            }
        });

        const cargosDisponibles = todosCargos.filter(function (c) {
            return cargosIdsActuales.indexOf(c.id) === -1;
        });

        let htmlNuevos = '';
        if (cargosDisponibles.length === 0) {
            htmlNuevos = '<p style="color:var(--d-muted);font-size:0.85rem;">No hay cargos disponibles para agregar.</p>';
        } else {
            cargosDisponibles.forEach(function (c) {
                const today = new Date().toISOString().split('T')[0];
                htmlNuevos += '<div class="edit-cargo-item">'
                    + '<label class="tw-cargo-check edit-cargo-check">'
                    + '<input type="checkbox" class="edit-cargo-checkbox" value="' + c.id + '">'
                    + '<span class="tw-cargo-name">' + esc(c.nombre) + '</span>'
                    + '</label>'
                    + '<div class="tw-cargo-fields edit-cargo-fields">'
                    + '<div class="row g-2">'
                    + '<div class="col-md-6">'
                    + '<div class="tw-sucursal-label"><i class="ri-building-line" style="color:#fc7b04;"></i> Sede</div>'
                    + '<select class="form-select edit-cargo-sede" data-cargo="' + c.id + '">' + sedeOptions + '</select>'
                    + '</div>'
                    + '<div class="col-md-6">'
                    + '<div class="tw-sucursal-label"><i class="ri-building-2-line" style="color:#fc7b04;"></i> Sucursal</div>'
                    + '<select class="form-select edit-cargo-sucursal" data-cargo="' + c.id + '" disabled>'
                    + '<option value="">— Seleccione sede —</option>'
                    + '</select>'
                    + '</div>'
                    + '<div class="col-md-4">'
                    + '<label>Estado</label>'
                    + '<select class="form-select edit-cargo-estado" data-cargo="' + c.id + '">'
                    + '<option value="Vigente">Vigente</option>'
                    + '<option value="No Vigente">No Vigente</option>'
                    + '</select>'
                    + '</div>'
                    + '<div class="col-md-4">'
                    + '<label>Fecha de Ingreso</label>'
                    + '<input type="date" class="form-control edit-cargo-fecha-ingreso" data-cargo="' + c.id + '" value="' + today + '">'
                    + '</div>'
                    + '<div class="col-md-4">'
                    + '<label>Fecha de Término</label>'
                    + '<input type="date" class="form-control edit-cargo-fecha-termino" data-cargo="' + c.id + '">'
                    + '</div>'
                    + '</div></div></div>';
            });
        }
        $('#editCargosNuevos').html(htmlNuevos);
    }

    /* ── CONFIRMAR EDICION ── */
    function confirmarEdicion() {
        if (!editTrabajadorId || !editPersonaId) return;

        // Verificar si hay fotografía nueva
        var fotoInput = document.getElementById('fotografiaEditar');
        var hasFoto = fotoInput && fotoInput.files && fotoInput.files[0];

        // Validar datos personales
        const okC = validarCarnetSync('eCarnet','iconECarnet','fbECarnet');
        const okN = validarNombres('eNombres','iconENombres','fbENombres');
        const okAp = validarApellidosEditar();
        const okCel = validarCelular('eCelular','iconECelular','fbECelular');
        if (!okC || !okN || !okAp || !okCel) return;
        if (document.getElementById('eCarnet').classList.contains('is-invalid')) return;
        if (document.getElementById('eCorreo').classList.contains('is-invalid')) return;

        const cargosAgregar = [];
        let hasError = false;

        $('.edit-cargo-checkbox:checked').each(function () {
            if (hasError) return;
            const cargoId = $(this).val();
            const sucursalId = $('.edit-cargo-sucursal[data-cargo="' + cargoId + '"]').val();
            const estado = $('.edit-cargo-estado[data-cargo="' + cargoId + '"]').val();
            const fechaIngreso = $('.edit-cargo-fecha-ingreso[data-cargo="' + cargoId + '"]').val();
            const fechaTermino = $('.edit-cargo-fecha-termino[data-cargo="' + cargoId + '"]').val() || null;

            if (!sucursalId) {
                $('#editCargosError').text('Debe seleccionar una sucursal para cada cargo nuevo.').show();
                hasError = true;
                return;
            }
            if (!fechaIngreso) {
                $('#editCargosError').text('La fecha de ingreso es obligatoria para todos los cargos nuevos.').show();
                hasError = true;
                return;
            }

            cargosAgregar.push({
                cargo_id: cargoId,
                sucursale_id: sucursalId,
                estado: estado,
                fecha_ingreso: fechaIngreso,
                fecha_termino: fechaTermino
            });
        });

        if (hasError) return;

        // Verificar si hay cambios (datos personales, fotografía o cargos)
        var hayCambiosPersona = hasFoto; // Si hay foto nueva, hay cambios
        
        // Verificar si cambiaron otros campos
        if (!hayCambiosPersona) {
            hayCambiosPersona = (
                $('#eCarnet').val().trim() !== originalData.eCarnet ||
                $('#eExpedido').val().trim() !== originalData.eExpedido ||
                $('#eNombres').val().trim() !== originalData.eNombres ||
                $('#eApPaterno').val().trim() !== originalData.eApPaterno ||
                $('#eApMaterno').val().trim() !== originalData.eApMaterno ||
                $('#eSexo').val() !== originalData.eSexo ||
                $('#eEstadoCivil').val() !== originalData.eEstadoCivil ||
                $('#eFechaNacimiento').val() !== originalData.eFechaNacimiento ||
                $('#eCorreo').val().trim() !== originalData.eCorreo ||
                $('#eCelular').val().trim() !== originalData.eCelular ||
                $('#eTelefono').val().trim() !== originalData.eTelefono ||
                $('#eDireccion').val().trim() !== originalData.eDireccion ||
                $('#eCiudad').val() !== originalData.eCiudad
            );
        }

        if (cargosAgregar.length === 0 && editCargosEliminados.length === 0 && !hayCambiosPersona) {
            toast('warning', 'No hay cambios para guardar.');
            return;
        }

setBtnLoading('#btnConfirmarEdicion', true, 'Guardando…');

        // Actualizar datos personales (siempre usar POST con _method: PUT)
        var formData = new FormData();
        formData.append('_token', CSRF);
        formData.append('_method', 'PUT');
        formData.append('carnet', $('#eCarnet').val().trim());
        formData.append('expedido', $('#eExpedido').val().trim());
        formData.append('nombres', $('#eNombres').val().trim());
        formData.append('apellido_paterno', $('#eApPaterno').val().trim());
        formData.append('apellido_materno', $('#eApMaterno').val().trim());
        formData.append('sexo', $('#eSexo').val());
        formData.append('estado_civil', $('#eEstadoCivil').val());
        formData.append('fecha_nacimiento', $('#eFechaNacimiento').val() || '');
        formData.append('correo', $('#eCorreo').val().trim());
        formData.append('celular', $('#eCelular').val().trim());
        formData.append('telefono', $('#eTelefono').val().trim());
        formData.append('direccion', $('#eDireccion').val().trim());
        formData.append('ciudade_id', $('#eCiudad').val() || '');

        if (hasFoto) {
            formData.append('fotografia', fotoInput.files[0]);
        }

        var personaUrl = '/admin/personas/' + editPersonaId;
        $.ajax({ url: personaUrl, type: 'POST', data: formData, processData: false, contentType: false })
        .done(function (r) {
            // Luego actualizar cargos
            return $.post('{{ route("admin.trabajadores.actualizarCargos") }}', {
                _token: CSRF,
                trabajadore_id: editTrabajadorId,
                cargos_agregar: cargosAgregar,
                cargos_eliminar: editCargosEliminados
            });
        })
        .done(function (r) {
            toast('success', r.message);
            closeModal('modalEditar');
            tabla.ajax.reload(null, false);
        })
        .fail(function (xhr) {
            const msg = xhr.responseJSON?.errors
                ? Object.values(xhr.responseJSON.errors).flat().join(' ')
                : xhr.responseJSON?.message || 'Error al actualizar.';
            toast('error', msg);
        })
        .always(function () {
            setBtnLoading('#btnConfirmarEdicion', false, '<i class="ri-check-line"></i> Guardar Cambios');
        });
    }

    /* ── ELIMINAR TRABAJADOR ── */
    function eliminarTrabajador(id) {
        setBtnLoading('#btnConfirmarEliminar', true, '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
        $.ajax({ url: '/admin/trabajadores/' + id, type: 'DELETE', data: { _token: CSRF } })
            .done(function (r) {
                closeModal('modalEliminar');
                tabla.ajax.reload(null, false);
                toast('success', r.message);
            })
            .fail(function (xhr) {
                const msg = xhr.responseJSON?.message || 'No se pudo eliminar.';
                toast('error', msg);
            })
            .always(function () {
                setBtnLoading('#btnConfirmarEliminar', false, '<i class="ri-delete-bin-line"></i> Eliminar');
                idEliminar = null;
            });
    }

    /* ── LIMPIAR BÚSQUEDA ── */
    function limpiarBusqueda() {
        $('#searchCarnet').val('').removeClass('is-valid is-invalid');
        $('#searchHint').removeClass('success error').html('<i class="ri-information-line"></i> Ingrese entre 7 y 11 dígitos numéricos.');
        $('#personaFound').slideUp(200);
        $('#cargosSection').slideUp(200);
        $('#btnLimpiarBusqueda').hide();
        $('#btnLimpiarBusqueda2').hide();
        $('#btnBuscar').prop('disabled', true);
        $('#btnAbrirRegistro').prop('disabled', true).attr('title', 'Primero realice una búsqueda. Si la persona no existe, podrá registrarla.');
        $('#yaTrabajadorBox').hide();
        $('#noEsTrabajadorBox').hide();
        personaSeleccionada = null;
    }

    /* ── RESET FORM REGISTRO ── */
    function resetFormRegistro() {
        $('#formRegistro')[0].reset();
        resetField('rCarnet', 'iconRCarnet', 'fbRCarnet');
        resetField('rNombres', 'iconRNombres', 'fbRNombres');
        resetField('rCorreo', 'iconRCorreo', 'fbRCorreo');
        $('#fbRApellidos').removeClass('error success').html('');
        $('#rDepto').val('');
        $('#rCiudad').find('option:not(:first)').remove().end().prop('disabled', true).find('option:first').text('— Seleccione depto. —');

        ['rCarnet','rNombres','rApPaterno','rApMaterno','rCorreo','rCelular','rTelefono','rDireccion','rExpedido','rFechaNacimiento'].forEach(function (id) {
            $('#' + id).prop('readonly', false);
        });
        ['rSexo','rEstadoCivil','rCiudad'].forEach(function (id) {
            $('#' + id).prop('disabled', false);
        });

        $('#registroYaTrabajador').hide();
        $('#btnGuardarTrabajador').prop('disabled', false).html('<i class="ri-save-line"></i> Registrar Trabajador');
        $('#modalRegistroTitle').html('<i class="ri-user-add-line"></i> Registrar Trabajador');
    }

    /* ── RESET FORM PERSONA ── */
    function resetFormPersona() {
        $('#formPersona')[0].reset();
        ['pCarnet','pNombres','pCorreo'].forEach(function (id) {
            resetField(id, 'icon' + id.charAt(1).toUpperCase() + id.slice(1), 'fb' + id.charAt(1).toUpperCase() + id.slice(1));
        });
        resetField('pCarnet', 'iconPCarnet', 'fbPCarnet');
        resetField('pNombres', 'iconPNombres', 'fbPNombres');
        resetField('pCorreo', 'iconPCorreo', 'fbPCorreo');
        $('#fbPApellidos').removeClass('error success').html('');
        $('#pDepto').val('');
        $('#pCiudad').find('option:not(:first)').remove().end().prop('disabled', true).find('option:first').text('— Seleccione depto. —');
    }

    /* ════════════════════ VALIDACIÓN ════════════════════ */

    function setError(inputId, iconId, fbId, msg) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        if (icon) { icon.className = 'validation-icon invalid'; icon.innerHTML = '<i class="ri-close-circle-fill"></i>'; }
        if (fb) { fb.className = 'est-feedback error'; fb.innerHTML = '<i class="ri-error-warning-line"></i>' + msg; }
        return false;
    }

    function setOk(inputId, iconId, fbId, msg) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        if (icon) { icon.className = 'validation-icon valid'; icon.innerHTML = '<i class="ri-checkbox-circle-fill"></i>'; }
        if (fb) { fb.className = 'est-feedback success'; fb.innerHTML = '<i class="ri-check-line"></i>' + msg; }
        return true;
    }

    function setChecking(inputId, iconId, fbId) {
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        if (icon) { icon.className = 'validation-icon'; icon.innerHTML = '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;"></i>'; }
        if (fb) { fb.className = 'est-feedback'; fb.innerHTML = 'Verificando…'; }
    }

    function resetField(inputId, iconId, fbId) {
        const input = document.getElementById(inputId);
        if (input) input.classList.remove('is-valid', 'is-invalid');
        const icon = document.getElementById(iconId);
        if (icon) { icon.className = 'validation-icon'; icon.innerHTML = ''; }
        const fb = document.getElementById(fbId);
        if (fb) { fb.className = 'est-feedback'; fb.innerHTML = ''; }
    }

    function isEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

    function verificarCarnetPersona(val) {
        $.post('{{ route("admin.trabajadores.verificarCarnetPersona") }}', { _token: CSRF, carnet: val }, function (r) {
            if (r.existe) setError('pCarnet','iconPCarnet','fbPCarnet','Este carnet ya está registrado.');
            else setOk('pCarnet','iconPCarnet','fbPCarnet','Carnet disponible');
        }).fail(function () { resetField('pCarnet','iconPCarnet','fbPCarnet'); });
    }

    function verificarCarnetRegistro(val) {
        $.post('{{ route("admin.trabajadores.verificarCarnetPersona") }}', { _token: CSRF, carnet: val }, function (r) {
            if (r.existe) setError('rCarnet','iconRCarnet','fbRCarnet','Este carnet ya está registrado.');
            else setOk('rCarnet','iconRCarnet','fbRCarnet','Carnet disponible');
        }).fail(function () { resetField('rCarnet','iconRCarnet','fbRCarnet'); });
    }

    function verificarCorreoPersona(val) {
        $.post('{{ route("admin.trabajadores.verificarCorreoPersona") }}', { _token: CSRF, correo: val }, function (r) {
            if (r.existe) setError('pCorreo','iconPCorreo','fbPCorreo','Este correo ya está registrado.');
            else setOk('pCorreo','iconPCorreo','fbPCorreo','Correo disponible');
        }).fail(function () { resetField('pCorreo','iconPCorreo','fbPCorreo'); });
    }

    function verificarCorreoRegistro(val) {
        $.post('{{ route("admin.trabajadores.verificarCorreoPersona") }}', { _token: CSRF, correo: val }, function (r) {
            if (r.existe) setError('rCorreo','iconRCorreo','fbRCorreo','Este correo ya está registrado.');
            else setOk('rCorreo','iconRCorreo','fbRCorreo','Correo disponible');
        }).fail(function () { resetField('rCorreo','iconRCorreo','fbRCorreo'); });
    }

    function verificarCarnetEditar(val) {
        $.post('{{ route("admin.trabajadores.verificarCarnetPersona") }}', { _token: CSRF, carnet: val, id: editPersonaId }, function (r) {
            if (r.existe) setError('eCarnet','iconECarnet','fbECarnet','Este carnet ya está registrado.');
            else setOk('eCarnet','iconECarnet','fbECarnet','Carnet disponible');
        }).fail(function () { resetField('eCarnet','iconECarnet','fbECarnet'); });
    }

    function verificarCorreoEditar(val) {
        $.post('{{ route("admin.trabajadores.verificarCorreoPersona") }}', { _token: CSRF, correo: val, id: editPersonaId }, function (r) {
            if (r.existe) setError('eCorreo','iconECorreo','fbECorreo','Este correo ya está registrado.');
            else setOk('eCorreo','iconECorreo','fbECorreo','Correo disponible');
        }).fail(function () { resetField('eCorreo','iconECorreo','fbECorreo'); });
    }

    function validarApellidosEditar() {
        const p = $('#eApPaterno').val().trim();
        const m = $('#eApMaterno').val().trim();
        const fb = document.getElementById('fbEApellidos');
        if (!p && !m) {
            fb.className = 'est-feedback error';
            fb.innerHTML = '<i class="ri-error-warning-line"></i>Debe registrar al menos un apellido (paterno o materno).';
            return false;
        }
        fb.className = 'est-feedback success';
        fb.innerHTML = '<i class="ri-check-line"></i>Apellido(s) válido(s)';
        return true;
    }

    function filtrarCiudadesAsync(ctx) {
        const deptoId = $('#eDepto').val();
        const $ciudad = $('#eCiudad');
        const prevVal = $ciudad.val();
        $ciudad.find('option:not(:first)').remove();
        if (!deptoId) {
            $ciudad.prop('disabled', true).find('option:first').text('— Seleccione depto. —');
            return $.Deferred().resolve();
        }
        const filtradas = todasCiudades.filter(function (c) { return c.departamento_id == deptoId; });
        $ciudad.append(filtradas.map(function (c) { return '<option value="' + c.id + '">' + esc(c.nombre) + '</option>'; }).join(''));
        $ciudad.prop('disabled', false).find('option:first').text('— Seleccione ciudad —');
        if (filtradas.some(function (c) { return c.id == prevVal; })) $ciudad.val(prevVal);
        return $.Deferred().resolve();
    }

    function validarCarnetSync(inputId, iconId, fbId) {
        const val = document.getElementById(inputId).value.trim();
        if (!val) return setError(inputId, iconId, fbId, 'El carnet es obligatorio.');
        if (val.length < 3) return setError(inputId, iconId, fbId, 'Debe tener al menos 3 caracteres.');
        return true;
    }

    function validarNombres(inputId, iconId, fbId) {
        const val = document.getElementById(inputId).value.trim();
        if (!val) return setError(inputId, iconId, fbId, 'El nombre es obligatorio.');
        if (val.length < 2) return setError(inputId, iconId, fbId, 'Debe tener al menos 2 caracteres.');
        return setOk(inputId, iconId, fbId, 'Nombre válido');
    }

    function validarCelular(inputId, iconId, fbId) {
        const val = document.getElementById(inputId).value.trim();
        if (val.length === 0) {
            return setError(inputId, iconId, fbId, 'El celular es obligatorio.');
        }
        if (!/^\d+$/.test(val)) {
            return setError(inputId, iconId, fbId, 'Solo se permiten números.');
        }
        if (val.length !== 8) {
            return setError(inputId, iconId, fbId, 'El celular debe tener exactamente 8 dígitos (' + val.length + '/8).');
        }
        return setOk(inputId, iconId, fbId, 'Celular válido');
    }

    function validarApellidos() {
        const p = $('#pApPaterno').val().trim();
        const m = $('#pApMaterno').val().trim();
        const fb = document.getElementById('fbPApellidos');
        if (!p && !m) {
            fb.className = 'est-feedback error';
            fb.innerHTML = '<i class="ri-error-warning-line"></i>Debe registrar al menos un apellido (paterno o materno).';
            return false;
        }
        fb.className = 'est-feedback success';
        fb.innerHTML = '<i class="ri-check-line"></i>Apellido(s) válido(s)';
        return true;
    }

    function validarApellidosRegistro() {
        const p = $('#rApPaterno').val().trim();
        const m = $('#rApMaterno').val().trim();
        const fb = document.getElementById('fbRApellidos');
        if (!p && !m) {
            fb.className = 'est-feedback error';
            fb.innerHTML = '<i class="ri-error-warning-line"></i>Debe registrar al menos un apellido (paterno o materno).';
            return false;
        }
        fb.className = 'est-feedback success';
        fb.innerHTML = '<i class="ri-check-line"></i>Apellido(s) válido(s)';
        return true;
    }

    /* ════════════════════ UTILIDADES ════════════════════ */

    function setBtnLoading(sel, loading, labelHtml) {
        const btn = document.querySelector(sel);
        if (!btn) return;
        btn.disabled = loading;
        if (loading) {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' + labelHtml;
        } else {
            btn.innerHTML = labelHtml;
        }
    }

    function openModal(id) { new bootstrap.Modal(document.getElementById(id)).show(); }

    function closeModal(id) {
        const el = document.getElementById(id);
        const m = bootstrap.Modal.getInstance(el);
        if (m) m.hide();
    }

    function esc(str) {
        return String(str || '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function toast(tipo, mensaje) {
        const iconMap = { success: 'ri-check-double-line', error: 'ri-close-circle-line', warning: 'ri-alert-line' };
        const el = document.createElement('div');
        el.className = 'toast-notify ' + tipo;
        el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') + '"></i></div>'
            + '<div class="toast-body-text"><span>' + mensaje + '</span></div>'
            + '<button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';
        let c = document.getElementById('toastContainer');
        if (!c) { c = document.createElement('div'); c.id = 'toastContainer'; c.className = 'toast-container'; document.body.appendChild(c); }
        if (c.parentElement !== document.body) document.body.appendChild(c);
        c.style.top = Math.max(20, window.scrollY + 20) + 'px';
        c.appendChild(el);
        el.querySelector('.toast-close').addEventListener('click', function () { removeToast(el); });
        setTimeout(function () { removeToast(el); }, 4500);
    }

    function removeToast(el) {
        el.classList.add('hiding');
        el.addEventListener('animationend', function () { el.remove(); }, { once: true });
    }

    $(document).ready(init);
})();
</script>
@endsection
