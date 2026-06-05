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
    <div class="row">
        <div class="col-12">
            <div class="dept-card">
                <div class="dept-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="dept-header-icon">
                            <i class="ri-table-line"></i>
                        </div>
                        <div>
                            <h5 class="dept-title">Listado de Usuarios</h5>
                            <p class="dept-subtitle">Consulta y gestiona las cuentas de usuario existentes</p>
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
                                <th>Rol</th>
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
                            Rol
                        </label>
                        <input type="text" class="form-control" value="admin" disabled
                               style="opacity:0.7;cursor:not-allowed;">
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

    function initDataTable() {
        tabla = $('#tabla-users').DataTable({
            ajax: { url: '{{ route("admin.users.listar") }}', dataSrc: 'data' },
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
                    data: 'role',
                    render: r => {
                        if (r === 'admin') {
                            return '<span class="badge-role badge-admin"><i class="ri-shield-check-line"></i> Admin</span>';
                        }
                        return '<span class="badge-role badge-user"><i class="ri-user-line"></i> User</span>';
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

                        // 4) Eliminar
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
                $('#stat-total').text(api.rows().data().length);
            }
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

        setBtnLoading('#btnGuardar', true, 'Creando…');
        $.post('{{ route("admin.users.guardar") }}', {
            _token: CSRF,
            persona_id: personaId,
            name: $('#nameCrear').val().trim()
        })
        .done(r => {
            closeModal('modalCrear');
            tabla.ajax.reload();
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
