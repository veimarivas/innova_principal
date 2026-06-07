@extends('layouts.master')
@section('title') Usuarios @endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
.search-box { display: flex; align-items: flex-end; gap: 0.75rem; flex-wrap: wrap; }
.search-box .field-wrapper { margin-bottom: 0; flex: 1; min-width: 200px; }
.search-box .form-control { background: var(--d-input-bg) !important; border: 2px solid var(--d-input-border) !important; border-radius: 10px !important; padding: 0.65rem 1rem !important; font-size: 0.9rem !important; color: var(--d-input-color) !important; transition: all 0.25s ease !important; font-weight: 500 !important; }
.search-box .form-control:focus { border-color: #fc7b04 !important; box-shadow: 0 0 0 4px rgba(252, 123, 4, 0.12) !important; outline: none !important; }
.btn-search { display: inline-flex; align-items: center; gap: 0.4rem; background: linear-gradient(135deg, #743c04 0%, #9a4904 100%); border: none; color: #fff !important; font-weight: 600; font-size: 0.85rem; padding: 0.65rem 1.2rem; border-radius: 10px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 3px 10px rgba(116, 60, 4, 0.3); white-space: nowrap; }
.btn-search:hover { background: linear-gradient(135deg, #9a4904 0%, #b55804 100%); transform: translateY(-1px); box-shadow: 0 5px 15px rgba(116, 60, 4, 0.4); color: #fff !important; }
html[data-bs-theme="dark"] .btn-search { background: #fc7b04; }
html[data-bs-theme="dark"] .btn-search:hover { background: #ff9d4d; }
.persona-info-card { background: rgba(252, 123, 4, 0.06); border: 1px solid rgba(252, 123, 4, 0.15); border-radius: 12px; padding: 1rem 1.25rem; margin-top: 0.75rem; display: none; }
.persona-info-card.show { display: block; }
.persona-info-card .info-row { display: flex; gap: 1.5rem; flex-wrap: wrap; }
.persona-info-card .info-item { flex: 1; min-width: 150px; }
.persona-info-card .info-label { font-size: 0.7rem; font-weight: 600; color: var(--d-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.2rem; }
.persona-info-card .info-value { font-size: 0.9rem; font-weight: 600; color: var(--d-body); }
.badge-role { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.3rem 0.75rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.badge-admin { background: linear-gradient(135deg, rgba(252, 123, 4, 0.15) 0%, rgba(252, 123, 4, 0.08) 100%); color: #fc7b04; border: 1px solid rgba(252, 123, 4, 0.25); }
.badge-user { background: linear-gradient(135deg, rgba(90, 138, 48, 0.12) 0%, rgba(90, 138, 48, 0.06) 100%); color: #5a8a30; border: 1px solid rgba(90, 138, 48, 0.2); }
.badge-estado { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.3rem 0.75rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
.badge-activo { background: rgba(90, 138, 48, 0.1); color: #5a8a30; }
.badge-inactivo { background: rgba(201, 96, 4, 0.1); color: #c96004; }

/* ── Badges de cuentas (Sistema / Moodle) ── */
.user-cuentas-cell { display: flex; flex-direction: column; gap: 4px; align-items: flex-start; }
.user-cuenta-badge { display: inline-flex; align-items: center; gap: 0.22rem; border-radius: 20px; padding: 0.18rem 0.55rem; font-size: 0.7rem; font-weight: 700; }
.user-cuenta-badge.active { background: rgba(90,138,48,0.10); color: #5a8a30; border: 1px solid rgba(90,138,48,0.22); }
.user-cuenta-badge.inactive { background: rgba(150,150,150,0.08); color: #6b7280; border: 1px solid rgba(150,150,150,0.18); }

/* ── Botones de acción ── */
.user-action-cell { display: flex; align-items: center; justify-content: center; gap: 0.28rem; flex-wrap: wrap; }
.user-btn-action { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: all 0.2s; cursor: pointer; }
.user-btn-action i { font-size: 0.92rem; }
.user-btn-action[disabled] { opacity: 0.35; cursor: not-allowed; }
.user-btn-wa       { background: rgba(37,211,102,0.08); color: #25D366; border: 1px solid rgba(37,211,102,0.22); }
.user-btn-wa:hover:not([disabled]) { background: rgba(37,211,102,0.18); color: #128C7E; }
.user-btn-reset    { background: rgba(37,99,235,0.08); color: #2563eb; border: 1px solid rgba(37,99,235,0.22); }
.user-btn-reset:hover:not([disabled]) { background: rgba(37,99,235,0.18); color: #1d4ed8; }
.user-btn-disable  { background: rgba(217,119,6,0.08); color: #d97706; border: 1px solid rgba(217,119,6,0.22); }
.user-btn-disable:hover:not([disabled]) { background: rgba(217,119,6,0.18); color: #b45309; }
.user-btn-enable   { background: rgba(22,163,74,0.08); color: #16a34a; border: 1px solid rgba(22,163,74,0.22); }
.user-btn-enable:hover:not([disabled]) { background: rgba(22,163,74,0.18); color: #15803d; }
.user-btn-delete   { background: rgba(220,38,38,0.06); color: #dc2626; border: 1px solid rgba(220,38,38,0.18); }
.user-btn-delete:hover:not([disabled]) { background: rgba(220,38,38,0.15); color: #b91c1c; }

/* ── Modal — credenciales preview ── */
.cred-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: .85rem 1rem; margin-top: .75rem; }
.cred-row { display: flex; align-items: center; gap: .65rem; padding: .35rem 0; font-size: .85rem; }
.cred-row + .cred-row { border-top: 1px dashed #e2e8f0; }
.cred-row .lbl { width: 90px; font-weight: 700; color: #64748b; font-size: .72rem; text-transform: uppercase; letter-spacing: .03em; }
.cred-row .val { font-family: 'Outfit', sans-serif; color: #0f172a; font-weight: 600; word-break: break-all; }
.search-feedback { font-size: 0.78rem; font-weight: 600; min-height: 1.2em; margin-top: 0.35rem; display: flex; align-items: center; gap: 0.3rem; transition: all 0.2s; }
.search-feedback.error { color: var(--d-invalid-color); }
.search-feedback.success { color: var(--d-valid-color); }
.search-feedback i { font-size: 0.85rem; }

/* ── Tabs de tipo de usuario ── */
.tipo-usuario-tabs {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px;
}
@media (max-width: 900px) { .tipo-usuario-tabs { grid-template-columns: 1fr; } }
.tipo-tab {
    background: #fff; border: 2px solid #e9ecef; border-radius: 14px;
    padding: 14px 18px; display: flex; align-items: center; gap: 14px;
    cursor: pointer; transition: all .2s; text-align: left;
    position: relative; overflow: hidden;
}
.tipo-tab::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(252,123,4,0) 0%, rgba(252,123,4,.05) 100%);
    opacity: 0; transition: opacity .2s;
}
.tipo-tab:hover { border-color: #fed7aa; transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,.06); }
.tipo-tab.active {
    border-color: #fc7b04;
    background: linear-gradient(135deg, #fff8f0 0%, #fff 100%);
    box-shadow: 0 6px 18px rgba(252,123,4,.18);
}
.tipo-tab.active::before { opacity: 1; }
.tipo-tab-icon {
    width: 48px; height: 48px; border-radius: 12px; flex-shrink: 0;
    background: linear-gradient(135deg, #fc7b04, #b85500); color: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.35rem; box-shadow: 0 4px 12px rgba(252,123,4,.30);
    position: relative; z-index: 1;
}
.tipo-tab-icon.tipo-virtual { background: linear-gradient(135deg, #2c5fb7, #1e3a8a); box-shadow: 0 4px 12px rgba(44,95,183,.30); }
.tipo-tab-icon.tipo-sin-acceso { background: linear-gradient(135deg, #9ca3af, #6b7280); box-shadow: 0 4px 12px rgba(107,114,128,.30); }
.tipo-tab[data-tipo="sin_acceso"]:hover { border-color: #d1d5db; }
.tipo-tab[data-tipo="sin_acceso"].active {
    border-color: #6b7280;
    background: linear-gradient(135deg, #f3f4f6 0%, #fff 100%);
    box-shadow: 0 6px 18px rgba(107,114,128,.18);
}
.tipo-tab[data-tipo="sin_acceso"].active .tipo-tab-count { color: #6b7280; }
.tipo-tab-info { flex: 1; position: relative; z-index: 1; }
.tipo-tab-label { font-weight: 700; font-size: 1rem; color: #1f2937; }
.tipo-tab-desc { font-size: .78rem; color: #6b7280; margin-top: 2px; }
.tipo-tab-count {
    font-weight: 800; font-size: 1.4rem; color: #b85500;
    min-width: 40px; text-align: right; position: relative; z-index: 1;
}
.tipo-tab:not(.active) .tipo-tab-count { color: #9ca3af; }

/* ── Access options en modal crear ── */
.access-options { display: grid; gap: 8px; }
.access-option {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 14px; border: 2px solid #e9ecef; border-radius: 12px;
    cursor: pointer; transition: all .15s; background: #fff;
    position: relative;
}
.access-option:hover { border-color: #fed7aa; }
.access-option input[type=checkbox] { display: none; }
.access-option .check-icon {
    color: #16a34a; font-size: 1.3rem; opacity: 0; transition: opacity .15s;
    margin-left: auto;
}
.access-option:has(input:checked) {
    border-color: #fc7b04; background: linear-gradient(135deg, #fff8f0, #fff);
}
.access-option:has(input:checked) .check-icon { opacity: 1; }
.access-option .access-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: linear-gradient(135deg, #fc7b04, #b85500); color: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.access-option .access-icon.access-virtual { background: linear-gradient(135deg, #2c5fb7, #1e3a8a); }
.access-option .access-title { font-weight: 700; font-size: .9rem; color: #1f2937; }
.access-option .access-desc { font-size: .75rem; color: #6b7280; margin-top: 1px; }

/* ── Badges de acceso en tabla ── */
.access-badges { display: inline-flex; gap: 4px; flex-wrap: wrap; justify-content: center; }
.access-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 12px;
    font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .02em;
}
.access-badge.admin { background: rgba(252,123,4,.12); color: #b85500; border: 1px solid rgba(252,123,4,.30); }
.access-badge.virtual { background: rgba(44,95,183,.10); color: #1e3a8a; border: 1px solid rgba(44,95,183,.25); }
.access-badge i { font-size: .78rem; }

/* ── Modal Toggle Admin: alerts/cards de detalle ── */
.ta-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 14px; border-radius: 12px;
    font-size: .85rem;
}
.ta-alert i { font-size: 1.4rem; margin-top: 1px; flex-shrink: 0; }
.ta-alert strong { display: block; font-weight: 700; margin-bottom: 2px; }
.ta-alert .small { font-size: .75rem; opacity: .85; }
.ta-alert-success {
    background: linear-gradient(135deg, rgba(22,163,74,.10), rgba(22,163,74,.04));
    border: 1.5px solid rgba(22,163,74,.30); color: #15803d;
}
.ta-alert-success i { color: #16a34a; }
.ta-alert-warning {
    background: linear-gradient(135deg, #fff4e6, #fff);
    border: 1.5px dashed rgba(217,119,6,.45); color: #92400e;
}
.ta-alert-warning i { color: #d97706; }

.ta-persona-card {
    background: #f8fafc; border: 1px solid #f1f5f9;
    border-radius: 12px; padding: 12px 16px;
}
html[data-bs-theme="dark"] .ta-persona-card {
    background: rgba(255,255,255,.04); border-color: rgba(255,255,255,.06);
}
.ta-persona-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 6px 0; font-size: .85rem;
}
.ta-persona-row + .ta-persona-row { border-top: 1px dashed #e5e7eb; }
html[data-bs-theme="dark"] .ta-persona-row + .ta-persona-row { border-top-color: rgba(255,255,255,.06); }
.ta-persona-row span {
    color: #6b7280; font-weight: 600; font-size: .75rem;
    text-transform: uppercase; letter-spacing: .03em;
}
.ta-persona-row strong { color: #1f2937; font-weight: 600; }
html[data-bs-theme="dark"] .ta-persona-row strong { color: #f1f5f9; }

.ta-seccion-titulo {
    font-size: .78rem; font-weight: 700; color: #b85500;
    text-transform: uppercase; letter-spacing: .04em;
    margin: 10px 0 8px; display: flex; align-items: center; gap: 6px;
    padding-bottom: 4px; border-bottom: 1.5px solid rgba(252,123,4,.18);
}
.ta-cargos-list { display: flex; flex-direction: column; gap: 8px; }
.ta-cargo-item {
    background: #fff; border: 1.5px solid #fed7aa;
    border-left: 4px solid #fc7b04;
    border-radius: 10px; padding: 10px 14px;
    display: flex; flex-direction: column; gap: 4px;
}
html[data-bs-theme="dark"] .ta-cargo-item {
    background: rgba(252,123,4,.05); border-color: rgba(252,123,4,.20);
}
.ta-cargo-nombre {
    font-weight: 700; font-size: .92rem; color: #b85500;
    display: flex; align-items: center; gap: 6px;
}
.ta-cargo-detalle {
    display: flex; flex-wrap: wrap; gap: 10px;
    font-size: .76rem; color: #6b7280;
}
.ta-cargo-detalle span { display: inline-flex; align-items: center; gap: 4px; }
.ta-cargo-detalle i { color: #fc7b04; font-size: .85rem; }
.ta-cargo-estado {
    display: inline-block; padding: 2px 8px; border-radius: 10px;
    font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em;
}
.ta-cargo-estado.vigente { background: rgba(22,163,74,.12); color: #15803d; }
.ta-cargo-estado.no-vigente { background: rgba(220,38,38,.10); color: #b91c1c; }
.ta-cargos-vacio {
    text-align: center; color: #9ca3af; font-size: .85rem;
    padding: 18px; border: 1.5px dashed #e5e7eb; border-radius: 10px;
}

.ta-info-box {
    display: flex; align-items: flex-start; gap: 8px;
    padding: 10px 12px; background: rgba(252,123,4,.07);
    border-left: 3px solid #fc7b04; border-radius: 6px;
    font-size: .8rem; color: #475569;
}
.ta-info-box i { color: #fc7b04; font-size: 1rem; flex-shrink: 0; }
html[data-bs-theme="dark"] .ta-info-box { color: #cbd5e1; }
</style>
@endsection

@section('content')
<div class="dept-page-header">
    <div class="container-fluid">
        <div class="dph-inner">
            <div class="dph-left">
                <div class="dph-icon-wrap">
                    <i class="ri-user-settings-line"></i>
                </div>
                <div class="dph-text-block">
                    <h1 class="dph-title">Usuarios</h1>
                    <p class="dph-desc">Gestión de cuentas de usuario del sistema</p>
                </div>
            </div>
            <div class="dph-right">
                <div class="dph-stat-card">
                    <div class="dph-stat-icon"><i class="ri-user-line"></i></div>
                    <div>
                        <div class="dph-stat-num" id="stat-total">—</div>
                        <div class="dph-stat-label">Total Usuarios</div>
                    </div>
                </div>
                <button type="button" class="dph-btn-new" id="btn-nuevo">
                    <i class="ri-user-add-line"></i>
                    <span>Crear Cuenta</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <!-- Tabs de tipo de usuario -->
    <div class="tipo-usuario-tabs mb-3">
        <button type="button" class="tipo-tab active" data-tipo="admin">
            <div class="tipo-tab-icon"><i class="ri-shield-user-line"></i></div>
            <div class="tipo-tab-info">
                <div class="tipo-tab-label">Administradores</div>
                <div class="tipo-tab-desc">Trabajadores con acceso al sistema</div>
            </div>
            <div class="tipo-tab-count" id="cnt-admin">0</div>
        </button>
        <button type="button" class="tipo-tab" data-tipo="virtual">
            <div class="tipo-tab-icon tipo-virtual"><i class="ri-presentation-line"></i></div>
            <div class="tipo-tab-info">
                <div class="tipo-tab-label">Portal Virtual</div>
                <div class="tipo-tab-desc">Docentes y estudiantes</div>
            </div>
            <div class="tipo-tab-count" id="cnt-virtual">0</div>
        </button>
        <button type="button" class="tipo-tab" data-tipo="sin_acceso">
            <div class="tipo-tab-icon tipo-sin-acceso"><i class="ri-shield-cross-line"></i></div>
            <div class="tipo-tab-info">
                <div class="tipo-tab-label">Sin Acceso</div>
                <div class="tipo-tab-desc">Cuentas deshabilitadas</div>
            </div>
            <div class="tipo-tab-count" id="cnt-sin_acceso">0</div>
        </button>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="dept-card">
                <div class="dept-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="dept-header-icon">
                            <i class="ri-table-line"></i>
                        </div>
                        <div>
                            <h5 class="dept-title" id="tabla-titulo">Usuarios Administradores</h5>
                            <p class="dept-subtitle" id="tabla-subtitulo">Cuentas con acceso al panel administrativo</p>
                        </div>
                    </div>
                </div>
                <div class="dept-card-body">
                    <table id="tabla-users" class="dept-table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Correo</th>
                                <th>Persona</th>
                                <th class="text-center">Accesos</th>
                                <th class="text-center">Cuentas</th>
                                <th class="text-center" style="width:200px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL CREAR ===================== -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-user-add-line"></i> Crear Cuenta de Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label for="buscarCarnet" class="form-label">
                        <i class="ri-search-line" style="color:#fc7b04;"></i>
                        Buscar por Carnet de Identidad <span class="req">*</span>
                    </label>
                    <div class="d-flex gap-2">
                        <div class="field-wrapper" style="flex:1;">
                            <input type="text" class="form-control" id="buscarCarnet"
                                   placeholder="Ingrese el número de carnet"
                                   maxlength="20" autocomplete="off">
                            <span class="validation-icon" id="iconBuscar"></span>
                        </div>
                        <button type="button" class="btn btn-search" id="btnBuscar">
                            <i class="ri-search-line"></i> Buscar
                        </button>
                    </div>
                    <div class="search-feedback" id="fbBuscar"></div>
                </div>

                <div class="persona-info-card" id="personaInfoCard">
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Nombre Completo</div>
                            <div class="info-value" id="infoNombre">—</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Carnet</div>
                            <div class="info-value" id="infoCarnet">—</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Correo</div>
                            <div class="info-value" id="infoCorreo">—</div>
                        </div>
                    </div>
                </div>

                <form id="formCrear" novalidate autocomplete="off" style="margin-top:1rem;">
                    <input type="hidden" id="personaIdCrear">
                    <div class="mb-1">
                        <label for="nameCrear" class="form-label">
                            <i class="ri-user-line" style="color:#fc7b04;"></i>
                            Nombre de Usuario <span class="req">*</span>
                        </label>
                        <input type="text" class="form-control" id="nameCrear" disabled
                               style="opacity:0.85;cursor:not-allowed;" placeholder="Se asignará automáticamente">
                    </div>
                    <div class="mb-1">
                        <label class="form-label">
                            <i class="ri-mail-line" style="color:#fc7b04;"></i>
                            Correo Electrónico
                        </label>
                        <input type="text" class="form-control" id="emailPreview" disabled
                               style="opacity:0.7;cursor:not-allowed;" placeholder="Se asignará automáticamente">
                    </div>
                    <div class="mb-1">
                        <label class="form-label">
                            <i class="ri-lock-line" style="color:#fc7b04;"></i>
                            Contraseña
                        </label>
                        <input type="text" class="form-control" id="passwordPreview" disabled
                               style="opacity:0.7;cursor:not-allowed;" placeholder="Se asignará automáticamente">
                    </div>
                    <div class="mb-1">
                        <label class="form-label">
                            <i class="ri-shield-line" style="color:#fc7b04;"></i>
                            Tipo de Acceso <span class="req">*</span>
                        </label>
                        <div class="access-options">
                            <label class="access-option" data-acceso="admin">
                                <input type="checkbox" id="accesoAdminChk" checked>
                                <div class="access-icon"><i class="ri-shield-user-line"></i></div>
                                <div class="access-info">
                                    <div class="access-title">Panel Administrativo</div>
                                    <div class="access-desc">Gestión del sistema</div>
                                </div>
                                <i class="ri-checkbox-circle-fill check-icon"></i>
                            </label>
                            <label class="access-option" data-acceso="virtual">
                                <input type="checkbox" id="accesoVirtualChk">
                                <div class="access-icon access-virtual"><i class="ri-presentation-line"></i></div>
                                <div class="access-info">
                                    <div class="access-title">Portal Virtual</div>
                                    <div class="access-desc">Como docente o estudiante</div>
                                </div>
                                <i class="ri-checkbox-circle-fill check-icon"></i>
                            </label>
                        </div>
                        <div class="form-text mt-2" style="font-size:.76rem;">
                            <i class="ri-information-line"></i> Puede seleccionar uno o ambos. Si selecciona ambos, el usuario podrá elegir el modo al iniciar sesión.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-modal-submit" id="btnGuardar" disabled>
                    <i class="ri-save-line"></i> Crear Cuenta
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL ELIMINAR ===================== -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-error-warning-line"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="delete-warning-box">
                    <div class="delete-icon-ring">
                        <i class="ri-delete-bin-5-line"></i>
                    </div>
                    <p class="delete-msg-primary">¿Eliminar usuario?</p>
                    <p class="delete-msg-name">
                        <strong id="nombreEliminar"></strong>
                    </p>
                    <p class="delete-msg-warn">
                        <i class="ri-information-line"></i>
                        Esta acción es permanente y no puede deshacerse.
                    </p>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button type="button" class="btn btn-modal-cancel px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-danger-modal px-4" id="btnConfirmarEliminar">
                    <i class="ri-delete-bin-line"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL ENVIAR CREDENCIALES ===================== -->
<div class="modal fade" id="modalCredenciales" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-whatsapp-line"></i> Enviar credenciales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2" style="font-size:.88rem;color:#475569;">Se abrirá WhatsApp con un mensaje pre-formado para <strong id="credModalNombre">—</strong>.</p>
                <div class="cred-box">
                    <div class="cred-row"><span class="lbl">Usuario</span><span class="val" id="credModalUsuario">—</span></div>
                    <div class="cred-row"><span class="lbl">Contraseña</span><span class="val" id="credModalPassword">—</span></div>
                    <div class="cred-row"><span class="lbl">Celular</span><span class="val" id="credModalCelular">—</span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-modal-submit" id="credModalBtnEnviar"><i class="ri-send-plane-line"></i> Abrir WhatsApp</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL REINICIAR PASSWORD ===================== -->
<div class="modal fade" id="modalReiniciarPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-lock-password-line"></i> Reiniciar contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="delete-warning-box">
                    <div class="delete-icon-ring" style="background:rgba(37,99,235,.1);color:#2563eb;">
                        <i class="ri-key-2-line"></i>
                    </div>
                    <p class="delete-msg-primary">¿Reiniciar contraseña?</p>
                    <p class="delete-msg-name"><strong id="resetNombre">—</strong></p>
                    <p class="delete-msg-warn">
                        <i class="ri-information-line"></i>
                        La contraseña se restablecerá al carnet del usuario en sistema y Moodle.
                    </p>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button type="button" class="btn btn-modal-cancel px-4" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-modal-submit px-4" id="btnConfirmarReset"><i class="ri-refresh-line"></i> Reiniciar</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL TOGGLE ESTADO ===================== -->
<div class="modal fade" id="modalToggleEstado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-shield-line"></i> <span id="toggleTitulo">Cambiar estado</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="delete-warning-box">
                    <div class="delete-icon-ring" id="toggleIconRing">
                        <i class="ri-shield-cross-line" id="toggleIcon"></i>
                    </div>
                    <p class="delete-msg-primary" id="toggleMsgPrimary">¿Deshabilitar cuenta?</p>
                    <p class="delete-msg-name"><strong id="toggleNombre">—</strong></p>
                    <p class="delete-msg-warn">
                        <i class="ri-information-line"></i>
                        <span id="toggleMsgInfo">La cuenta no podrá ingresar al sistema ni a Moodle.</span>
                    </p>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button type="button" class="btn btn-modal-cancel px-4" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-modal-submit px-4" id="btnConfirmarToggle"><i class="ri-shield-check-line"></i> <span id="toggleBtnLabel">Confirmar</span></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Asignar Roles -->
<div class="modal fade" id="modalAsignarRoles" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-shield-user-line"></i> Asignar roles a: <span id="ar-nombre" style="color:#b85500;font-weight:700;"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="ar-id">
                <p class="text-muted small mb-3">Seleccione uno o más roles. Al usuario se le asignarán exclusivamente los roles marcados.</p>
                <div id="ar-roles-container" style="display:flex;flex-direction:column;gap:8px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-modal-submit" id="btnGuardarRoles"><i class="ri-save-line"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Toggle Acceso Admin -->
<div class="modal fade" id="modalToggleAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:560px;">
        <div class="modal-content">
            <div class="modal-header" id="ta-header" style="background:linear-gradient(135deg,#fc7b04,#b85500);color:#fff;border-bottom:none;">
                <h5 class="modal-title" style="color:#fff;font-weight:700;display:flex;align-items:center;gap:8px;">
                    <i id="ta-header-icon" class="ri-shield-user-line"></i>
                    <span id="ta-header-text">Acceso al Panel Administrativo</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1) brightness(2);opacity:.85;"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <input type="hidden" id="ta-id">

                <!-- Estado de carga -->
                <div id="ta-loading" class="text-center py-4" style="display:none;">
                    <span class="spinner-border" style="color:#fc7b04;"></span>
                    <div class="mt-2 text-muted small">Verificando información del trabajador...</div>
                </div>

                <!-- Vista simple (cuando NO se va a habilitar — solo deshabilitar) -->
                <div id="ta-vista-simple" class="text-center" style="display:none;">
                    <div id="ta-icon-ring" style="width:72px;height:72px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px;font-size:2rem;box-shadow:inset 0 0 0 2px currentColor;">
                        <i id="ta-icon" class="ri-shield-user-line"></i>
                    </div>
                    <p id="ta-titulo" style="font-weight:700;font-size:1rem;margin-bottom:6px;color:#1f2937;">¿Otorgar acceso al panel administrativo?</p>
                    <p style="font-size:.92rem;color:#b85500;font-weight:600;margin-bottom:6px;">
                        <strong id="ta-nombre-simple">—</strong>
                    </p>
                    <p id="ta-mensaje" style="font-size:.82rem;color:#6b7280;margin-bottom:0;">—</p>
                </div>

                <!-- Vista detallada: persona ES trabajador -->
                <div id="ta-vista-trabajador" style="display:none;">
                    <div class="ta-alert ta-alert-success mb-3">
                        <i class="ri-checkbox-circle-fill"></i>
                        <div>
                            <strong>Persona registrada como trabajador.</strong>
                            <div class="small">Confirma para habilitar nuevamente el acceso al panel administrativo.</div>
                        </div>
                    </div>

                    <div class="ta-persona-card mb-3">
                        <div class="ta-persona-row"><span>Nombre</span><strong id="ta-tp-nombre">—</strong></div>
                        <div class="ta-persona-row"><span>Carnet</span><strong id="ta-tp-carnet">—</strong></div>
                        <div class="ta-persona-row"><span>Correo</span><strong id="ta-tp-correo">—</strong></div>
                        <div class="ta-persona-row"><span>Celular</span><strong id="ta-tp-celular">—</strong></div>
                    </div>

                    <div class="ta-seccion-titulo">
                        <i class="ri-briefcase-line"></i> Cargos asignados (<span id="ta-cargos-count">0</span>)
                    </div>
                    <div id="ta-cargos-list" class="ta-cargos-list"></div>
                </div>

                <!-- Vista detallada: persona NO es trabajador -->
                <div id="ta-vista-no-trabajador" style="display:none;">
                    <div class="ta-alert ta-alert-warning mb-3">
                        <i class="ri-error-warning-fill"></i>
                        <div>
                            <strong>Esta persona no está registrada como trabajador.</strong>
                            <div class="small">Para tener acceso al panel administrativo necesita ser registrada como trabajador y tener al menos un cargo asignado.</div>
                        </div>
                    </div>

                    <div class="ta-persona-card mb-3">
                        <div class="ta-persona-row"><span>Nombre</span><strong id="ta-np-nombre">—</strong></div>
                        <div class="ta-persona-row"><span>Carnet</span><strong id="ta-np-carnet">—</strong></div>
                        <div class="ta-persona-row"><span>Correo</span><strong id="ta-np-correo">—</strong></div>
                        <div class="ta-persona-row"><span>Celular</span><strong id="ta-np-celular">—</strong></div>
                    </div>

                    <div class="ta-info-box">
                        <i class="ri-information-line"></i>
                        <span>Al confirmar serás redirigido a la sección de trabajadores con la búsqueda pre-cargada para que puedas asignarle uno o más cargos.</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-center gap-3" style="background:#f8fafc;border-top:1px solid #f1f5f9;">
                <button type="button" class="btn btn-modal-cancel px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-modal-submit px-4" id="ta-confirmar">
                    <i class="ri-shield-user-line"></i> Otorgar acceso admin
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Toggle Acceso Virtual -->
<div class="modal fade" id="modalToggleVirtual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content">
            <div class="modal-header" id="tv-header" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;border-bottom:none;">
                <h5 class="modal-title" style="color:#fff;font-weight:700;display:flex;align-items:center;gap:8px;">
                    <i id="tv-header-title-icon" class="ri-presentation-line"></i>
                    <span id="tv-header-title-text">Acceso al Portal Virtual</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1) brightness(2);opacity:.85;"></button>
            </div>
            <div class="modal-body text-center px-4 py-3">
                <input type="hidden" id="tv-id">
                <div id="tv-icon-ring" style="width:72px;height:72px;border-radius:50%;background:rgba(37,99,235,.12);color:#2563eb;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px;font-size:2rem;box-shadow:inset 0 0 0 2px currentColor;opacity:1;">
                    <i id="tv-icon" class="ri-presentation-line"></i>
                </div>
                <p id="tv-titulo" style="font-weight:700;font-size:1rem;margin-bottom:6px;color:#1f2937;">¿Otorgar acceso al portal virtual?</p>
                <p style="font-size:.92rem;color:#b85500;font-weight:600;margin-bottom:6px;">
                    <strong id="tv-nombre">—</strong>
                </p>
                <p id="tv-mensaje" style="font-size:.82rem;color:#6b7280;margin-bottom:0;">
                    El usuario podrá entrar al portal virtual.
                </p>
            </div>
            <div class="modal-footer justify-content-center gap-3" style="background:#f8fafc;border-top:1px solid #f1f5f9;">
                <button type="button" class="btn btn-modal-cancel px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-modal-submit px-4" id="tv-confirmar">
                    <i class="ri-presentation-line"></i> Otorgar acceso virtual
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
(function () {
    'use strict';

    let tabla;
    let idEliminar = null;
    const CSRF = '{{ csrf_token() }}';

    function init() {
        initDataTable();
        bindEvents();
    }

    let tipoActual = 'admin';

    function initDataTable() {
        tabla = $('#tabla-users').DataTable({
            ajax: {
                url: '{{ route("admin.users.listar") }}',
                data: function (d) { d.tipo = tipoActual; },
                dataSrc: 'data'
            },
            ordering: true,
            paging: true,
            info: true,
            pagingType: 'simple_numbers',
            columns: [
                {
                    data: 'name',
                    render: n => '<span style="font-weight:600;">' + escHtml(n) + '</span>'
                },
                {
                    data: 'email',
                    render: e => '<span style="font-size:0.82rem;">' + escHtml(e) + '</span>'
                },
                {
                    data: null,
                    render: d => {
                        if (d.persona) {
                            const nombre = [d.persona.nombres, d.persona.apellido_paterno, d.persona.apellido_materno]
                                .filter(Boolean).join(' ');
                            return '<span style="font-size:0.82rem;">' + escHtml(nombre) + '</span>';
                        }
                        return '<span style="color:var(--d-muted);font-style:italic;">Sin asignar</span>';
                    }
                },
                {
                    data: null, className: 'text-center',
                    render: d => {
                        let html = '<div class="access-badges">';
                        if (d.acceso_admin) html += '<span class="access-badge admin"><i class="ri-shield-user-line"></i> Admin</span>';
                        if (d.acceso_virtual) html += '<span class="access-badge virtual"><i class="ri-presentation-line"></i> Virtual</span>';
                        if (!d.acceso_admin && !d.acceso_virtual) html += '<span class="text-muted small">—</span>';
                        html += '</div>';
                        return html;
                    }
                },
                {
                    data: null, className: 'text-center',
                    render: d => {
                        const sis  = d.tiene_cuenta_sistema
                            ? '<span class="user-cuenta-badge active"><i class="ri-check-line"></i>Sistema</span>'
                            : '<span class="user-cuenta-badge inactive"><i class="ri-close-line"></i>Sistema</span>';
                        const mood = d.tiene_cuenta_moodle
                            ? '<span class="user-cuenta-badge active"><i class="ri-check-line"></i>Moodle</span>'
                            : '<span class="user-cuenta-badge inactive"><i class="ri-close-line"></i>Moodle</span>';
                        return '<div class="user-cuentas-cell">' + sis + mood + '</div>';
                    }
                },
                {
                    data: null, className: 'text-center',
                    render: d => {
                        const persona = d.persona || {};
                        const nombre  = escHtml([persona.nombres, persona.apellido_paterno, persona.apellido_materno].filter(Boolean).join(' ') || d.name);
                        const carnet  = persona.carnet || '';
                        const celular = (persona.celular || '').replace(/\D/g, '');
                        const username = d.usuario_username || d.username || d.email || '';
                        const password = generarPasswordDefault(carnet);
                        const activo   = d.tiene_cuenta_sistema;

                        let btns = '<div class="user-action-cell">';

                        // 1) Enviar credenciales por WhatsApp
                        if (celular.length >= 8 && username) {
                            btns += '<button type="button" class="user-btn-action user-btn-wa btn-enviar-credenciales" '
                                  + 'data-celular="' + celular + '" '
                                  + 'data-nombre="'  + nombre  + '" '
                                  + 'data-username="' + escHtml(username) + '" '
                                  + 'data-password="' + escHtml(password) + '" '
                                  + 'title="Enviar credenciales por WhatsApp"><i class="ri-whatsapp-line"></i></button>';
                        } else {
                            btns += '<button type="button" class="user-btn-action user-btn-wa" disabled title="Sin celular o usuario para enviar"><i class="ri-whatsapp-line"></i></button>';
                        }

                        // 2) Reiniciar contraseña
                        btns += '<button type="button" class="user-btn-action user-btn-reset btn-reiniciar-password" '
                              + 'data-id="' + d.id + '" '
                              + 'data-nombre="' + nombre + '" '
                              + 'data-carnet="' + escHtml(carnet) + '" '
                              + 'title="Reiniciar contraseña"><i class="ri-lock-password-line"></i></button>';

                        // 3) Habilitar / Deshabilitar cuenta
                        if (activo) {
                            btns += '<button type="button" class="user-btn-action user-btn-disable btn-toggle-estado" '
                                  + 'data-id="' + d.id + '" data-nombre="' + nombre + '" data-activo="1" '
                                  + 'title="Deshabilitar cuenta"><i class="ri-shield-cross-line"></i></button>';
                        } else {
                            btns += '<button type="button" class="user-btn-action user-btn-enable btn-toggle-estado" '
                                  + 'data-id="' + d.id + '" data-nombre="' + nombre + '" data-activo="0" '
                                  + 'title="Habilitar cuenta"><i class="ri-shield-check-line"></i></button>';
                        }

                        // 4) Asignar Roles (Spatie) — solo si el usuario tiene acceso admin
                        if (d.acceso_admin) {
                            btns += '<button type="button" class="user-btn-action user-btn-reset btn-asignar-roles" '
                                  + 'data-id="' + d.id + '" data-nombre="' + nombre + '" '
                                  + 'style="background:#fff4e6;color:#b85500;border:1px solid rgba(252,123,4,.25);" '
                                  + 'title="Asignar roles"><i class="ri-shield-keyhole-line"></i></button>';
                        }

                        // 4b) Toggle acceso ADMIN — siempre visible
                        {
                            const yaEsAdmin = !!d.acceso_admin;
                            const colorBg   = yaEsAdmin ? '#dcfce7' : '#fff4e6';
                            const colorFg   = yaEsAdmin ? '#16a34a' : '#d97706';
                            const icono     = yaEsAdmin ? 'ri-shield-check-line' : 'ri-shield-cross-line';
                            const titulo    = yaEsAdmin
                                ? 'Acceso admin habilitado — Click para deshabilitar'
                                : 'Acceso admin deshabilitado — Click para habilitar';
                            btns += '<button type="button" class="user-btn-action btn-toggle-admin" '
                                  + 'data-id="' + d.id + '" data-nombre="' + nombre + '" '
                                  + 'data-es-admin="' + (yaEsAdmin ? '1' : '0') + '" '
                                  + 'data-otro-acceso="' + (d.acceso_virtual ? '1' : '0') + '" '
                                  + 'style="background:' + colorBg + ';color:' + colorFg + ';border:1px solid ' + colorFg + '33;" '
                                  + 'title="' + titulo + '"><i class="' + icono + '"></i></button>';
                        }

                        // 4c) Toggle acceso VIRTUAL — siempre visible
                        {
                            const yaEsVirtual = !!d.acceso_virtual;
                            const colorBg     = yaEsVirtual ? '#dbeafe' : '#fff4e6';
                            const colorFg     = yaEsVirtual ? '#2563eb' : '#d97706';
                            const icono       = yaEsVirtual ? 'ri-presentation-fill' : 'ri-presentation-line';
                            const titulo      = yaEsVirtual
                                ? 'Acceso al portal virtual habilitado — Click para deshabilitar'
                                : 'Acceso al portal virtual deshabilitado — Click para habilitar';
                            btns += '<button type="button" class="user-btn-action btn-toggle-virtual" '
                                  + 'data-id="' + d.id + '" data-nombre="' + nombre + '" '
                                  + 'data-es-virtual="' + (yaEsVirtual ? '1' : '0') + '" '
                                  + 'data-otro-acceso="' + (d.acceso_admin ? '1' : '0') + '" '
                                  + 'style="background:' + colorBg + ';color:' + colorFg + ';border:1px solid ' + colorFg + '33;" '
                                  + 'title="' + titulo + '"><i class="' + icono + '"></i></button>';
                        }

                        // 5) Eliminar
                        btns += '<button type="button" class="user-btn-action user-btn-delete btn-accion-eliminar" '
                              + 'data-id="' + d.id + '" data-nombre="' + escHtml(d.name) + '" '
                              + 'title="Eliminar usuario"><i class="ri-delete-bin-fill"></i></button>';

                        btns += '</div>';
                        return btns;
                    }
                }
            ],
            language: {
                processing:     'Procesando...',
                search:         'Buscar:',
                lengthMenu:     'Mostrar _MENU_ registros',
                info:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty:      'Mostrando 0 a 0 de 0 registros',
                infoFiltered:   '(filtrado de _MAX_ registros totales)',
                loadingRecords: 'Cargando...',
                zeroRecords:    'No se encontraron registros',
                emptyTable:     'No hay datos disponibles',
                paginate: {
                    first:    'Primero',
                    previous: 'Anterior',
                    next:     'Siguiente',
                    last:     'Último'
                },
                aria: {
                    sortAscending:  ': activar para ordenar ascendente',
                    sortDescending: ': activar para ordenar descendente'
                }
            },
            order: [[0, 'asc']],
            lengthChange: false,
            pageLength: 10,
            drawCallback: function () {
                const api = this.api();
                const total = api.rows().data().length;
                $('#stat-total').text(total);
                $('#cnt-' + tipoActual).text(total);
            }
        });

        // Cargar contador del otro tipo de forma asíncrona
        actualizarContadorOtroTipo();
    }

    function actualizarContadorOtroTipo() {
        ['admin', 'virtual', 'sin_acceso'].forEach(function (tipo) {
            if (tipo === tipoActual) return;
            $.get('{{ route("admin.users.listar") }}', { tipo: tipo })
                .done(r => $('#cnt-' + tipo).text((r.data || []).length))
                .fail(() => {});
        });
    }

    let idReiniciar = null;
    let idToggle = null;
    let toggleEsActivo = null;

    function generarPasswordDefault(carnet) {
        const digits = String(carnet || '').replace(/\D/g, '');
        return digits.length >= 7 ? digits : 'innova' + digits;
    }
    window.generarPasswordDefault = generarPasswordDefault;

    function bindEvents() {
        // === Cambio de tab (Administradores / Virtual / Sin acceso) ===
        $('.tipo-tab').on('click', function () {
            const t = $(this).data('tipo');
            if (t === tipoActual) return;
            tipoActual = t;
            $('.tipo-tab').removeClass('active');
            $(this).addClass('active');
            if (t === 'admin') {
                $('#tabla-titulo').text('Usuarios Administradores');
                $('#tabla-subtitulo').text('Cuentas con acceso al panel administrativo');
            } else if (t === 'virtual') {
                $('#tabla-titulo').text('Usuarios del Portal Virtual');
                $('#tabla-subtitulo').text('Docentes y estudiantes con acceso a la plataforma');
            } else if (t === 'sin_acceso') {
                $('#tabla-titulo').text('Cuentas Sin Acceso');
                $('#tabla-subtitulo').text('Usuarios deshabilitados — habilite al menos un acceso para que puedan iniciar sesión');
            }
            tabla.ajax.reload();
        });

        $('#btn-nuevo').on('click', () => {
            resetCreateModal();
            openModal('modalCrear');
        });

        // === Enviar credenciales por WhatsApp ===
        $(document).on('click', '.btn-enviar-credenciales', function () {
            const $b = $(this);
            const celular  = $b.data('celular');
            const nombre   = $b.data('nombre');
            const username = $b.data('username');
            const password = $b.data('password');
            $('#credModalNombre').text(nombre);
            $('#credModalUsuario').text(username);
            $('#credModalPassword').text(password);
            $('#credModalCelular').text('+' + celular);
            $('#credModalBtnEnviar').data('celular', celular)
                                    .data('nombre', nombre)
                                    .data('username', username)
                                    .data('password', password);
            openModal('modalCredenciales');
        });

        $('#credModalBtnEnviar').on('click', function () {
            const $b = $(this);
            const celular  = $b.data('celular');
            const nombre   = $b.data('nombre');
            const username = $b.data('username');
            const password = $b.data('password');

            const mensaje = '*¡Bienvenido/a a tu plataforma académica!*\n\n' +
                'Estimado/a ' + nombre + ',\n' +
                'A continuación encontrarás tus credenciales de acceso al portal.\n\n' +
                '*ACCESO A LA PLATAFORMA*\n' +
                '──────────────────────\n' +
                'Sitio web:  https://posgradosinnovaciencia.com\n' +
                'Usuario:    ' + username + '\n' +
                'Contraseña: ' + password + '\n' +
                '──────────────────────\n\n' +
                'Por favor cambia tu contraseña en el primer acceso.\n' +
                'Si necesitas ayuda, contáctanos.\n\n' +
                'Área Académica — Innova Ciencia Virtual';

            window.open('https://wa.me/' + celular + '?text=' + encodeURIComponent(mensaje), '_blank');
            closeModal('modalCredenciales');
        });

        // === Reiniciar contraseña ===
        $(document).on('click', '.btn-reiniciar-password', function () {
            idReiniciar = $(this).data('id');
            $('#resetNombre').text($(this).data('nombre'));
            openModal('modalReiniciarPassword');
        });

        $('#btnConfirmarReset').on('click', function () {
            if (!idReiniciar) return;
            setBtnLoading('#btnConfirmarReset', true, 'Reiniciando…');
            $.post('/admin/users/' + idReiniciar + '/reiniciar-password', { _token: CSRF })
                .done(r => {
                    closeModal('modalReiniciarPassword');
                    toast('success', r.message || 'Contraseña reiniciada.');
                    tabla.ajax.reload(null, false);
                })
                .fail(xhr => toast('error', xhr.responseJSON?.message || 'Error al reiniciar.'))
                .always(() => {
                    setBtnLoading('#btnConfirmarReset', false, '<i class="ri-refresh-line"></i> Reiniciar');
                    idReiniciar = null;
                });
        });

        // === Toggle estado (habilitar / deshabilitar) ===
        $(document).on('click', '.btn-toggle-estado', function () {
            idToggle       = $(this).data('id');
            toggleEsActivo = String($(this).data('activo')) === '1';
            const nombre   = $(this).data('nombre');

            $('#toggleNombre').text(nombre);
            if (toggleEsActivo) {
                $('#toggleTitulo').text('Deshabilitar cuenta');
                $('#toggleMsgPrimary').text('¿Deshabilitar cuenta?');
                $('#toggleMsgInfo').text('La cuenta no podrá ingresar al sistema ni a Moodle.');
                $('#toggleIcon').attr('class', 'ri-shield-cross-line');
                $('#toggleIconRing').css({ background: 'rgba(217,119,6,.1)', color: '#d97706' });
                $('#toggleBtnLabel').text('Deshabilitar');
                $('#btnConfirmarToggle').find('i').attr('class', 'ri-shield-cross-line');
            } else {
                $('#toggleTitulo').text('Habilitar cuenta');
                $('#toggleMsgPrimary').text('¿Habilitar cuenta?');
                $('#toggleMsgInfo').text('La cuenta volverá a tener acceso al sistema y Moodle.');
                $('#toggleIcon').attr('class', 'ri-shield-check-line');
                $('#toggleIconRing').css({ background: 'rgba(22,163,74,.1)', color: '#16a34a' });
                $('#toggleBtnLabel').text('Habilitar');
                $('#btnConfirmarToggle').find('i').attr('class', 'ri-shield-check-line');
            }
            openModal('modalToggleEstado');
        });

        $('#btnConfirmarToggle').on('click', function () {
            if (!idToggle) return;
            setBtnLoading('#btnConfirmarToggle', true, 'Procesando…');
            $.post('/admin/users/' + idToggle + '/toggle-estado', { _token: CSRF })
                .done(r => {
                    closeModal('modalToggleEstado');
                    toast(r.moodle_ok === false ? 'warning' : 'success', r.message || 'Estado actualizado.');
                    tabla.ajax.reload(null, false);
                })
                .fail(xhr => toast('error', xhr.responseJSON?.message || 'Error al actualizar.'))
                .always(() => {
                    setBtnLoading('#btnConfirmarToggle', false, '<i class="ri-shield-check-line"></i> <span id="toggleBtnLabel">Confirmar</span>');
                    idToggle = null;
                });
        });

        $('#btnBuscar').on('click', buscarPersona);

        $('#buscarCarnet').on('keypress', function (e) {
            if (e.which === 13) {
                buscarPersona();
            }
        });

        $('#nameCrear').on('input', function () {
        });

        $('#btnGuardar').on('click', function() { guardar(); });

        $(document).on('click', '.btn-accion-eliminar', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            idEliminar = id;
            $('#nombreEliminar').text(nombre);
            openModal('modalEliminar');
        });

        // Estado del modal toggle-admin: 'deshabilitar' | 'habilitar-trabajador' | 'habilitar-no-trabajador'
        let taAccion = null;
        let taCarnetParaRedirect = null;

        // Toggle acceso ADMIN
        $(document).on('click', '.btn-toggle-admin', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            const yaEsAdmin = $(this).data('es-admin') == 1;
            const tieneVirtual = $(this).data('otro-acceso') == 1;
            $('#ta-id').val(id);

            // Reset vistas
            $('#ta-vista-simple, #ta-vista-trabajador, #ta-vista-no-trabajador').hide();
            $('#ta-loading').hide();

            if (yaEsAdmin) {
                // ── Deshabilitar (vista simple) ──
                taAccion = 'deshabilitar';
                taCarnetParaRedirect = null;
                $('#ta-header').css('background', 'linear-gradient(135deg,#dc2626,#b91c1c)');
                $('#ta-header-icon').attr('class', 'ri-shield-cross-line');
                $('#ta-header-text').text('Quitar acceso administrativo');
                $('#ta-icon-ring').css({ background: 'rgba(220,38,38,.10)', color: '#dc2626' });
                $('#ta-icon').attr('class', 'ri-shield-cross-line');
                $('#ta-titulo').text('¿Quitar acceso al panel administrativo?');
                $('#ta-nombre-simple').text(nombre);
                let msg = 'El usuario dejará de poder ingresar al panel administrativo.';
                msg += tieneVirtual
                    ? ' Conservará su acceso al portal virtual.'
                    : ' Como no tiene otro acceso, no podrá iniciar sesión hasta que se le habilite nuevamente.';
                $('#ta-mensaje').text(msg);
                $('#ta-vista-simple').show();
                $('#ta-confirmar').removeClass('btn-modal-submit').addClass('btn-danger-modal')
                    .html('<i class="ri-shield-cross-line"></i> Sí, quitar acceso')
                    .prop('disabled', false);
                openModal('modalToggleAdmin');
                return;
            }

            // ── Habilitar: primero consultar si es trabajador ──
            $('#ta-header').css('background', 'linear-gradient(135deg,#fc7b04,#b85500)');
            $('#ta-header-icon').attr('class', 'ri-shield-user-line');
            $('#ta-header-text').text('Habilitar acceso administrativo');
            $('#ta-loading').show();
            $('#ta-confirmar').prop('disabled', true).html('<i class="ri-shield-user-line"></i> Sí, habilitar');
            openModal('modalToggleAdmin');

            $.get('/admin/users/' + id + '/info-trabajador').done(function (r) {
                $('#ta-loading').hide();
                if (!r.success) {
                    toast('error', r.message || 'Error al obtener información.');
                    closeModal('modalToggleAdmin');
                    return;
                }

                const p = r.persona || {};
                const nombreCompleto = [p.nombres, p.apellido_paterno, p.apellido_materno].filter(Boolean).join(' ');
                taCarnetParaRedirect = p.carnet || null;

                if (r.es_trabajador) {
                    // ── Vista: es trabajador ──
                    taAccion = 'habilitar-trabajador';
                    $('#ta-tp-nombre').text(nombreCompleto || '—');
                    $('#ta-tp-carnet').text(p.carnet || '—');
                    $('#ta-tp-correo').text(p.correo || '—');
                    $('#ta-tp-celular').text(p.celular || '—');

                    const cargos = r.cargos || [];
                    $('#ta-cargos-count').text(cargos.length);

                    if (cargos.length === 0) {
                        $('#ta-cargos-list').html('<div class="ta-cargos-vacio"><i class="ri-error-warning-line"></i> Es trabajador pero no tiene cargos asignados todavía.</div>');
                    } else {
                        let html = '';
                        cargos.forEach(function (c) {
                            const estadoClass = (c.estado || '').toLowerCase() === 'vigente' ? 'vigente' : 'no-vigente';
                            html += '<div class="ta-cargo-item">'
                                + '  <div class="ta-cargo-nombre"><i class="ri-briefcase-fill"></i> ' + escHtml(c.cargo || '—')
                                + '    <span class="ta-cargo-estado ' + estadoClass + '">' + escHtml(c.estado || '—') + '</span>'
                                + '  </div>'
                                + '  <div class="ta-cargo-detalle">'
                                + (c.sede ? '<span><i class="ri-building-line"></i>' + escHtml(c.sede) + '</span>' : '')
                                + (c.sucursal ? '<span><i class="ri-building-2-line"></i>' + escHtml(c.sucursal) + '</span>' : '')
                                + (c.fecha_ingreso ? '<span><i class="ri-calendar-event-line"></i>Ingreso: ' + escHtml(c.fecha_ingreso) + '</span>' : '')
                                + (c.fecha_termino ? '<span><i class="ri-calendar-close-line"></i>Término: ' + escHtml(c.fecha_termino) + '</span>' : '')
                                + '  </div>'
                                + '</div>';
                        });
                        $('#ta-cargos-list').html(html);
                    }

                    $('#ta-vista-trabajador').show();
                    $('#ta-confirmar')
                        .removeClass('btn-danger-modal').addClass('btn-modal-submit')
                        .html('<i class="ri-shield-check-line"></i> Sí, habilitar acceso admin')
                        .prop('disabled', false);
                } else {
                    // ── Vista: NO es trabajador ──
                    taAccion = 'habilitar-no-trabajador';
                    $('#ta-np-nombre').text(nombreCompleto || '—');
                    $('#ta-np-carnet').text(p.carnet || '—');
                    $('#ta-np-correo').text(p.correo || '—');
                    $('#ta-np-celular').text(p.celular || '—');
                    $('#ta-vista-no-trabajador').show();
                    $('#ta-confirmar')
                        .removeClass('btn-danger-modal').addClass('btn-modal-submit')
                        .html('<i class="ri-user-add-line"></i> Registrar como Trabajador')
                        .prop('disabled', false);
                }
            }).fail(function () {
                $('#ta-loading').hide();
                toast('error', 'Error al obtener información del trabajador.');
                closeModal('modalToggleAdmin');
            });
        });

        $('#ta-confirmar').on('click', function () {
            const id = $('#ta-id').val();
            const $btn = $(this);
            const origLabel = $btn.html();

            // Si NO es trabajador → redirigir al panel trabajadores
            if (taAccion === 'habilitar-no-trabajador') {
                if (taCarnetParaRedirect) {
                    sessionStorage.setItem('trab_carnet_busqueda', taCarnetParaRedirect);
                    window.location.href = '/admin/trabajadores?carnet=' + encodeURIComponent(taCarnetParaRedirect);
                } else {
                    window.location.href = '/admin/trabajadores';
                }
                return;
            }

            // Otros casos → toggle del acceso admin
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Procesando…');
            $.post('/admin/users/' + id + '/toggle-acceso-admin', { _token: '{{ csrf_token() }}' })
                .done(function (r) {
                    closeModal('modalToggleAdmin');
                    tabla.ajax.reload(null, false);
                    actualizarContadorOtroTipo();
                    toast('success', r.message || 'Acceso actualizado.');
                })
                .fail(function (xhr) {
                    toast('error', xhr.responseJSON?.message || 'No se pudo actualizar el acceso.');
                })
                .always(function () {
                    $btn.prop('disabled', false).html(origLabel);
                });
        });

        // Toggle acceso VIRTUAL
        $(document).on('click', '.btn-toggle-virtual', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            const yaEsVirtual = $(this).data('es-virtual') == 1;
            const tieneAdmin  = $(this).data('otro-acceso') == 1;
            $('#tv-id').val(id);
            $('#tv-nombre').text(nombre);
            if (yaEsVirtual) {
                $('#tv-icon-ring').css({ background: 'rgba(220,38,38,.10)', color: '#dc2626' });
                $('#tv-icon').attr('class', 'ri-shield-cross-line');
                $('#tv-header').css({ background: 'linear-gradient(135deg,#dc2626,#b91c1c)' });
                $('#tv-header-title-text').text('Quitar acceso al portal virtual');
                $('#tv-header-title-icon').attr('class', 'ri-shield-cross-line');
                $('#tv-titulo').text('¿Quitar acceso al portal virtual?');
                let msg = 'El usuario dejará de poder ingresar al portal virtual.';
                msg += tieneAdmin
                    ? ' Conservará su acceso al panel administrativo.'
                    : ' Como no tiene otro acceso, no podrá iniciar sesión hasta que se le habilite nuevamente.';
                $('#tv-mensaje').text(msg);
                $('#tv-confirmar').removeClass('btn-modal-submit').addClass('btn-danger-modal')
                    .html('<i class="ri-shield-cross-line"></i> Quitar acceso virtual');
            } else {
                $('#tv-icon-ring').css({ background: 'rgba(37,99,235,.12)', color: '#2563eb' });
                $('#tv-icon').attr('class', 'ri-presentation-line');
                $('#tv-header').css({ background: 'linear-gradient(135deg,#2563eb,#1d4ed8)' });
                $('#tv-header-title-text').text('Otorgar acceso al portal virtual');
                $('#tv-header-title-icon').attr('class', 'ri-presentation-line');
                $('#tv-titulo').text('¿Otorgar acceso al portal virtual?');
                let msg = 'El usuario podrá entrar al portal virtual.';
                if (tieneAdmin) msg += ' Verá el selector de modo al iniciar sesión (admin / virtual).';
                $('#tv-mensaje').text(msg);
                $('#tv-confirmar').removeClass('btn-danger-modal').addClass('btn-modal-submit')
                    .html('<i class="ri-presentation-line"></i> Otorgar acceso virtual');
            }
            openModal('modalToggleVirtual');
        });

        $('#tv-confirmar').on('click', function () {
            const id = $('#tv-id').val();
            const $btn = $(this);
            const origLabel = $btn.html();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Procesando…');
            $.post('/admin/users/' + id + '/toggle-acceso-virtual', { _token: '{{ csrf_token() }}' })
                .done(function (r) {
                    closeModal('modalToggleVirtual');
                    tabla.ajax.reload(null, false);
                    actualizarContadorOtroTipo();
                    toast('success', r.message || 'Acceso actualizado.');
                })
                .fail(function (xhr) {
                    toast('error', xhr.responseJSON?.message || 'No se pudo actualizar el acceso.');
                })
                .always(function () {
                    $btn.prop('disabled', false).html(origLabel);
                });
        });

        $(document).on('click', '.btn-asignar-roles', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            $('#ar-id').val(id);
            $('#ar-nombre').text(nombre);
            $('#ar-roles-container').html('<div class="text-center py-3"><span class="spinner-border spinner-border-sm"></span></div>');
            openModal('modalAsignarRoles');
            $.get('/admin/users/' + id + '/roles').done(function (r) {
                if (!r.success) { toast('error', r.message || 'Error'); return; }
                if (!r.roles || r.roles.length === 0) {
                    $('#ar-roles-container').html('<p class="text-muted">No hay roles registrados. Cree roles desde el módulo Roles.</p>');
                    return;
                }
                let html = '';
                r.roles.forEach(function (rol) {
                    html += '<label style="display:flex;align-items:center;gap:10px;padding:10px 12px;border:1px solid #e9ecef;border-radius:10px;cursor:pointer;background:'+(rol.asignado?'#fff4e6':'#fff')+';">';
                    html += '<input type="checkbox" class="ar-role-cb" value="'+rol.id+'" '+(rol.asignado?'checked':'')+'>';
                    html += '<span style="font-weight:600;text-transform:uppercase;">'+rol.name+'</span>';
                    html += '</label>';
                });
                $('#ar-roles-container').html(html);
            }).fail(function () {
                $('#ar-roles-container').html('<p class="text-danger">Error al cargar roles.</p>');
            });
        });

        $('#btnGuardarRoles').on('click', function () {
            const id = $('#ar-id').val();
            const ids = $('#ar-roles-container input.ar-role-cb:checked').map(function(){return this.value;}).get();
            const $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Guardando…');
            $.post('/admin/users/'+id+'/roles', { _token: '{{ csrf_token() }}', roles: ids })
                .done(function (r) {
                    closeModal('modalAsignarRoles');
                    tabla.ajax.reload(null, false);
                    toast('success', r.message || 'Roles actualizados.');
                })
                .fail(function (xhr) {
                    toast('error', xhr.responseJSON?.message || 'Error al guardar roles.');
                })
                .always(function () {
                    $btn.prop('disabled', false).html('<i class="ri-save-line"></i> Guardar');
                });
        });

        $('#btnConfirmarEliminar').on('click', function () {
            if (!idEliminar) return;
            eliminar(idEliminar);
        });

        document.getElementById('modalCrear').addEventListener('hidden.bs.modal', () => {
            resetCreateModal();
        });
    }

    function resetCreateModal() {
        $('#buscarCarnet').val('');
        $('#personaIdCrear').val('');
        $('#nameCrear').val('').prop('disabled', true);
        $('#emailPreview').val('');
        $('#passwordPreview').val('');
        $('#personaInfoCard').removeClass('show');
        $('#btnGuardar').prop('disabled', true);
        // Preset según el tab actual
        $('#accesoAdminChk').prop('checked', tipoActual === 'admin');
        $('#accesoVirtualChk').prop('checked', tipoActual === 'virtual');
        resetField('nameCrear', 'iconCrear', 'fbCrear', 'hintCrear');
        document.getElementById('iconBuscar').className = 'validation-icon';
        document.getElementById('iconBuscar').innerHTML = '';
        document.getElementById('fbBuscar').className = 'search-feedback';
        document.getElementById('fbBuscar').innerHTML = '';
    }

    function buscarPersona() {
        const carnet = $('#buscarCarnet').val().trim();
        const fb = $('#fbBuscar');
        const icon = document.getElementById('iconBuscar');

        if (!carnet) {
            fb.text('Ingrese el número de carnet.').addClass('error').removeClass('success');
            icon.className = 'validation-icon invalid';
            icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
            return;
        }

        fb.text('').removeClass('error success');
        icon.className = 'validation-icon';
        icon.innerHTML = '';

        setBtnLoading('#btnBuscar', true, 'Buscando…');

        $.ajax({
            url: '{{ route("admin.users.buscarCarnet") }}',
            type: 'POST',
            data: { _token: CSRF, carnet: carnet }
        })
        .done(r => {
            if (r.success) {
                const p = r.data;
                const nombreCompleto = [p.nombres, p.apellido_paterno, p.apellido_materno]
                    .filter(Boolean).join(' ');

                $('#personaIdCrear').val(p.id);
                $('#infoNombre').text(nombreCompleto);
                $('#infoCarnet').text(p.carnet);
                $('#infoCorreo').text(p.correo || 'Sin correo registrado');
                $('#personaInfoCard').addClass('show');

                const nameCompleto = [p.nombres, p.apellido_paterno, p.apellido_materno]
                    .filter(Boolean).join('').toUpperCase();
                $('#nameCrear').val(nameCompleto);

                $('#emailPreview').val(p.correo || 'Sin correo — no se podrá crear la cuenta');
                $('#passwordPreview').val(p.carnet);
                $('#btnGuardar').prop('disabled', false);

                fb.html('<i class="ri-checkbox-circle-fill"></i>Persona encontrada. Puede crear la cuenta.').addClass('success').removeClass('error');
                document.getElementById('iconBuscar').className = 'validation-icon valid';
                document.getElementById('iconBuscar').innerHTML = '<i class="ri-checkbox-circle-fill"></i>';
            } else {
                $('#personaInfoCard').removeClass('show');
                $('#personaIdCrear').val('');
                $('#nameCrear').prop('disabled', true).val('');
                $('#emailPreview').val('');
                $('#passwordPreview').val('');
                $('#btnGuardar').prop('disabled', true);

                fb.text(r.message || 'No se encontró la persona.').addClass('error').removeClass('success');
                icon.className = 'validation-icon invalid';
                icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
            }
        })
        .fail(xhr => {
            const msg = xhr.responseJSON?.message || 'Error al buscar la persona.';
            fb.text(msg).addClass('error').removeClass('success');
            icon.className = 'validation-icon invalid';
            icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
        })
        .always(() => setBtnLoading('#btnBuscar', false, '<i class="ri-search-line"></i> Buscar'));
    }

    function resetField(inputId, iconId, fbId, hintId) {
        const input = document.getElementById(inputId);
        if (input) input.classList.remove('is-valid', 'is-invalid');
        const iconEl = document.getElementById(iconId);
        if (iconEl) {
            iconEl.className = 'validation-icon';
            iconEl.innerHTML = '';
        }
        const fbEl = document.getElementById(fbId);
        if (fbEl) {
            fbEl.className = 'field-feedback';
            fbEl.innerHTML = '';
        }
    }

    function guardar() {
        const personaId = $('#personaIdCrear').val();
        if (!personaId) {
            toast('error', 'Primero busque una persona por su carnet.');
            return;
        }

        const name = $('#nameCrear').val().trim();
        if (!name || name.length < 2) {
            toast('error', 'El nombre de la persona no es válido.');
            return;
        }

        const accesoAdmin = $('#accesoAdminChk').is(':checked');
        const accesoVirtual = $('#accesoVirtualChk').is(':checked');
        if (!accesoAdmin && !accesoVirtual) {
            toast('error', 'Debe seleccionar al menos un tipo de acceso.');
            return;
        }

        setBtnLoading('#btnGuardar', true, 'Creando…');
        $.post('{{ route("admin.users.guardar") }}', {
            _token: CSRF,
            persona_id: personaId,
            name: $('#nameCrear').val().trim(),
            acceso_admin: accesoAdmin ? 1 : 0,
            acceso_virtual: accesoVirtual ? 1 : 0
        })
        .done(r => {
            closeModal('modalCrear');
            tabla.ajax.reload();
            actualizarContadorOtroTipo();
            toast('success', r.message || 'Cuenta creada correctamente.');
        })
        .fail(xhr => {
            if (xhr.status === 422) {
                const errs = xhr.responseJSON.errors || {};
                if (errs.persona_id) {
                    toast('error', errs.persona_id[0]);
                } else if (errs.name) {
                    toast('error', errs.name[0]);
                } else {
                    toast('error', 'Error de validación al crear la cuenta.');
                }
            } else {
                const msg = xhr.responseJSON?.message || 'Ocurrió un error al crear la cuenta.';
                toast('error', msg);
            }
        })
        .always(() => setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Crear Cuenta'));
    }

    function eliminar(id) {
        if (!id) return;
        setBtnLoading('#btnConfirmarEliminar', true, '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
        $.ajax({
            url: '/admin/users/' + id,
            type: 'DELETE',
            data: { _token: CSRF }
        })
        .done(r => {
            closeModal('modalEliminar');
            tabla.ajax.reload();
            toast('success', r.message || 'Usuario eliminado correctamente.');
        })
        .fail(xhr => {
            const msg = xhr.responseJSON?.message || xhr.statusText || 'No se pudo eliminar.';
            toast(xhr.status === 400 ? 'warning' : 'error', msg);
        })
        .always(() => {
            setBtnLoading('#btnConfirmarEliminar', false, '<i class="ri-delete-bin-line"></i> Eliminar');
            idEliminar = null;
        });
    }

    function setBtnLoading(sel, loading, labelHtml) {
        const btn = document.querySelector(sel);
        if (!btn) return;
        btn.disabled = loading;
        if (loading) {
            btn.dataset.orig = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' + labelHtml;
        } else {
            btn.innerHTML = labelHtml;
        }
    }

    function openModal(id) { new bootstrap.Modal(document.getElementById(id)).show(); }

    function closeModal(id) {
        const el = document.getElementById(id);
        const m  = bootstrap.Modal.getInstance(el);
        if (m) m.hide();
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function getToastContainer() {
        let c = document.getElementById('toastContainer');
        if (c && c.parentElement !== document.body) {
            document.body.appendChild(c);
        }
        return c;
    }

    function toast(tipo, mensaje) {
        const iconMap = { success: 'ri-check-double-line', error: 'ri-close-circle-line', warning: 'ri-alert-line' };
        const el = document.createElement('div');
        el.className = 'toast-notify ' + tipo;
        el.innerHTML =
            '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') + '"></i></div>'
            + '<div class="toast-body-text"><span>' + mensaje + '</span></div>'
            + '<button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';

        const container = getToastContainer();
        const updatePosition = () => {
            container.style.top = Math.max(20, window.scrollY + 20) + 'px';
        };
        container.style.transition = 'top 0.3s ease';
        updatePosition();

        if (!container._scrollListenerAttached) {
            container._scrollListenerAttached = true;
            let scrollTimeout;
            window.addEventListener('scroll', () => {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(updatePosition, 10);
            });
        }

        container.appendChild(el);
        el.querySelector('.toast-close').addEventListener('click', () => removeToast(el));
        setTimeout(() => removeToast(el), 4500);
    }

    function removeToast(el) {
        el.classList.add('hiding');
        el.addEventListener('animationend', () => el.remove(), { once: true });
    }

    $(document).ready(init);
})();
</script>
@endsection
