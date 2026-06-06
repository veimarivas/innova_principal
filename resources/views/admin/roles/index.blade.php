@extends('layouts.master')
@section('title')
    Roles
@endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
    .badge-soft { background: #fff4e6; color: #b85500; padding: 3px 9px; border-radius: 12px; font-size: .75rem; font-weight: 600; }

    /* ── Modal de permisos rediseñado ── */
    #modalPermisos .modal-content {
        border: none; border-radius: 16px; overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,.15);
    }
    #modalPermisos .modal-header {
        background: linear-gradient(135deg, #fc7b04 0%, #b85500 100%);
        color: #fff; border-bottom: none; padding: 18px 24px;
    }
    #modalPermisos .modal-header .modal-title { color: #fff; font-weight: 700; font-size: 1.05rem; display: flex; align-items: center; gap: 10px; }
    #modalPermisos .modal-header .modal-title i { font-size: 1.4rem; }
    #modalPermisos .modal-header .btn-close { filter: invert(1) brightness(2); opacity: .8; }
    #modalPermisos .badge-rol {
        background: rgba(255,255,255,.22); color: #fff; padding: 4px 12px;
        border-radius: 14px; font-size: .82rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .03em;
    }

    /* Toolbar sticky */
    .perm-toolbar {
        position: sticky; top: 0; z-index: 5;
        background: #fff; padding: 14px 24px 12px;
        border-bottom: 1px solid #f1f3f5;
        display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between;
    }
    .perm-search-wrap { position: relative; flex: 1 1 240px; max-width: 320px; }
    .perm-search-wrap i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #fc7b04; font-size: 1rem; pointer-events: none;
    }
    .perm-search-wrap input {
        width: 100%; padding: 8px 14px 8px 38px; border-radius: 22px;
        border: 1.5px solid #e9ecef; font-size: .85rem;
        background: #fff; transition: all .18s;
    }
    .perm-search-wrap input:focus {
        outline: none; border-color: #fc7b04;
        box-shadow: 0 0 0 4px rgba(252,123,4,.12);
    }
    .perm-toolbar-actions { display: flex; gap: 6px; }
    .btn-tb {
        border: 1.5px solid #e9ecef; background: #fff; color: #555;
        padding: 6px 12px; border-radius: 20px; font-size: .78rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 5px; transition: all .15s; cursor: pointer;
    }
    .btn-tb:hover { background: #fff4e6; border-color: #fc7b04; color: #b85500; }
    .btn-tb i { font-size: .95rem; }

    /* Contador resumen */
    .perm-summary {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 24px;
        background: linear-gradient(to right, #fff8f0, #fff);
        border-bottom: 1px solid #f1f3f5;
        font-size: .82rem; color: #6b7280;
    }
    .perm-summary strong { color: #b85500; font-weight: 700; font-size: .95rem; }
    .perm-summary .pill {
        background: #fff4e6; color: #b85500; padding: 3px 10px;
        border-radius: 12px; font-weight: 700; font-size: .76rem;
    }

    /* Cuerpo con scroll */
    .perm-body-scroll {
        max-height: 50vh; overflow-y: auto; padding: 14px 24px 8px;
    }
    .perm-body-scroll::-webkit-scrollbar { width: 8px; }
    .perm-body-scroll::-webkit-scrollbar-thumb { background: #fc7b0455; border-radius: 4px; }
    .perm-body-scroll::-webkit-scrollbar-thumb:hover { background: #fc7b04; }

    /* Grupos como accordion */
    .perm-grupo {
        border: 1px solid #e9ecef; border-radius: 10px; margin-bottom: 8px;
        background: #fff; transition: border-color .15s, box-shadow .15s;
    }
    .perm-grupo:hover { border-color: #fed7aa; }
    .perm-grupo.has-selected { border-color: #fc7b04; box-shadow: 0 1px 4px rgba(252,123,4,.10); }

    .perm-grupo-header {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 14px; cursor: pointer; user-select: none;
        border-radius: 10px;
    }
    .perm-grupo-header .chevron {
        color: #adb5bd; font-size: 1rem; transition: transform .2s; flex-shrink: 0;
    }
    .perm-grupo.open .chevron { transform: rotate(90deg); color: #fc7b04; }
    .perm-grupo-icon {
        width: 28px; height: 28px; border-radius: 8px;
        background: #fff4e6; color: #fc7b04;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .95rem; flex-shrink: 0;
    }
    .perm-grupo.has-selected .perm-grupo-icon {
        background: linear-gradient(135deg, #fc7b04, #b85500); color: #fff;
    }
    .perm-grupo-name {
        flex: 1; font-weight: 700; font-size: .9rem;
        text-transform: capitalize; color: #333;
    }
    .perm-grupo-count {
        font-size: .72rem; color: #6b7280; font-weight: 600;
        background: #f8f9fa; padding: 2px 8px; border-radius: 10px;
    }
    .perm-grupo.has-selected .perm-grupo-count {
        background: #fff4e6; color: #b85500;
    }
    .perm-grupo-toggleall {
        font-size: .68rem; color: #6b7280; padding: 3px 8px;
        border: 1px solid #e9ecef; background: #fff;
        border-radius: 10px; cursor: pointer; font-weight: 600;
        transition: all .15s;
    }
    .perm-grupo-toggleall:hover { background: #fff4e6; border-color: #fc7b04; color: #b85500; }

    .perm-grupo-body {
        display: none; padding: 4px 14px 12px;
        border-top: 1px dashed #f1f3f5;
        display: none;
    }
    .perm-grupo.open .perm-grupo-body {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 6px; padding-top: 10px;
    }

    .perm-chip {
        display: flex; align-items: center; gap: 8px;
        padding: 7px 11px; border-radius: 8px;
        background: #fff; border: 1.5px solid #e9ecef;
        font-size: .8rem; cursor: pointer; user-select: none;
        transition: all .12s;
    }
    .perm-chip:hover { border-color: #fed7aa; background: #fffaf3; }
    .perm-chip input {
        margin: 0; cursor: pointer;
        accent-color: #fc7b04;
        width: 14px; height: 14px;
    }
    .perm-chip.checked {
        background: #fff4e6; border-color: #fc7b04;
        color: #b85500; font-weight: 600;
    }
    .perm-chip span { text-transform: capitalize; }

    #modalPermisos .modal-footer {
        background: #f8f9fa; border-top: 1px solid #f1f3f5;
        padding: 14px 24px;
    }
    .perm-empty {
        text-align: center; padding: 30px 20px; color: #9ca3af;
    }
    .perm-empty i { font-size: 2.5rem; color: #fed7aa; display: block; margin-bottom: 8px; }

    /* ── Validación en tiempo real ── */
    .field-wrapper { position: relative; }
    .field-wrapper .validation-icon {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        font-size: 1.1rem; pointer-events: none; opacity: 0; transition: opacity .15s;
    }
    .field-wrapper .validation-icon.valid   { opacity: 1; color: #16a34a; }
    .field-wrapper .validation-icon.invalid { opacity: 1; color: #dc2626; }
    .form-control.is-valid   { border-color: #16a34a !important; padding-right: 36px; }
    .form-control.is-invalid { border-color: #dc2626 !important; padding-right: 36px; }
    .form-control.is-valid:focus, .form-control.is-invalid:focus { box-shadow: 0 0 0 .2rem rgba(252,123,4,.15) !important; }

    .field-feedback {
        font-size: .78rem; margin-top: 4px; min-height: 18px;
        display: flex; align-items: center; gap: 5px;
    }
    .field-feedback.error   { color: #dc2626; }
    .field-feedback.success { color: #16a34a; }
    .field-feedback.checking{ color: #6b7280; }

    .char-hint {
        font-size: .72rem; color: #6b7280; text-align: right; margin-top: 2px;
    }
    .char-hint.warning { color: #d97706; font-weight: 600; }

    /* ── Buscador rediseñado ── */
    .dataTables_wrapper .dataTables_filter { margin-bottom: 14px; }
    .dataTables_wrapper .dataTables_filter label {
        font-weight: 0; color: transparent; position: relative; display: block; margin: 0;
    }
    .dataTables_wrapper .dataTables_filter input {
        width: 320px !important; max-width: 100%;
        padding: 9px 14px 9px 38px !important;
        border-radius: 24px !important;
        border: 1.5px solid #e9ecef !important;
        background: #fff !important;
        font-size: .88rem;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
        transition: all .18s;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none !important;
        border-color: #fc7b04 !important;
        box-shadow: 0 0 0 4px rgba(252,123,4,.12) !important;
    }
    .dataTables_wrapper .dataTables_filter label::before {
        content: "\f0d1";
        font-family: "remixicon" !important;
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #fc7b04; font-size: 1rem; pointer-events: none; z-index: 2;
    }
    .dataTables_wrapper .dataTables_length select {
        border-radius: 18px !important;
        border: 1.5px solid #e9ecef !important;
        padding: 5px 28px 5px 12px !important;
        font-size: .85rem; font-weight: 600;
        color: #b85500; background: #fff;
    }
    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #fc7b04 !important;
        box-shadow: 0 0 0 3px rgba(252,123,4,.12) !important;
    }

    /* ── Paginación compacta ── */
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 14px; text-align: right;
    }
    .dataTables_wrapper .dataTables_paginate .pagination {
        gap: 0 !important; margin: 0; padding: 0;
        justify-content: flex-end; display: inline-flex; flex-wrap: wrap;
    }
    .dataTables_wrapper .dataTables_paginate .pagination .page-item { margin: 0 !important; padding: 0 !important; }
    .dataTables_wrapper .dataTables_paginate .pagination .page-item + .page-item { margin-left: 3px !important; }
    .dataTables_wrapper .dataTables_paginate .page-item .page-link,
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        margin: 0 !important;
        min-width: 32px; height: 32px;
        padding: 0 9px !important;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 7px !important;
        border: 1px solid #e9ecef !important;
        color: #555 !important;
        font-weight: 600; font-size: .8rem; line-height: 1;
        background: #fff; transition: all .15s;
        box-shadow: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .page-item .page-link:hover,
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #fff4e6 !important;
        border-color: #fc7b04 !important;
        color: #b85500 !important;
    }
    .dataTables_wrapper .dataTables_paginate .page-item.active .page-link,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: linear-gradient(135deg, #fc7b04, #b85500) !important;
        border-color: #b85500 !important;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(252,123,4,.35) !important;
    }
    .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: #f8f9fa !important; color: #adb5bd !important; border-color: #f1f3f5 !important;
    }
    .dataTables_wrapper .dataTables_paginate .ellipsis {
        padding: 0 6px !important; color: #adb5bd; align-self: center;
    }
    .dataTables_wrapper .dataTables_info {
        font-size: .82rem; color: #6b7280; padding-top: 18px;
    }

    @keyframes spin { from { transform: translateY(-50%) rotate(0deg); } to { transform: translateY(-50%) rotate(360deg); } }
</style>
@endsection

@section('content')
    <div class="dept-page-header">
        <div class="container-fluid">
            <div class="dph-inner">
                <div class="dph-left">
                    <div class="dph-icon-wrap"><i class="ri-shield-user-line"></i></div>
                    <div class="dph-text-block">
                        <h1 class="dph-title">Roles</h1>
                        <p class="dph-desc">Administre los roles del sistema y sus permisos</p>
                    </div>
                </div>
                <div class="dph-right">
                    <div class="dph-stat-card">
                        <div class="dph-stat-icon"><i class="ri-hashtag"></i></div>
                        <div>
                            <div class="dph-stat-num" id="stat-total">—</div>
                            <div class="dph-stat-label">Total Roles</div>
                        </div>
                    </div>
                    <button type="button" class="dph-btn-new" id="btn-nuevo">
                        <i class="ri-add-line"></i><span>Nuevo Rol</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="dept-card">
            <div class="dept-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="dept-header-icon"><i class="ri-table-line"></i></div>
                    <div>
                        <h5 class="dept-title">Listado de Roles</h5>
                        <p class="dept-subtitle">Edite, asigne permisos o elimine roles</p>
                    </div>
                </div>
            </div>
            <div class="dept-card-body">
                <table id="tabla-roles" class="dept-table">
                    <thead>
                        <tr>
                            <th>Rol</th>
                            <th class="text-center" style="width:120px;">Permisos</th>
                            <th class="text-center" style="width:120px;">Usuarios</th>
                            <th class="text-center" style="width:200px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-add-circle-line"></i> Nuevo Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formCrear" novalidate autocomplete="off">
                    <div class="modal-body">
                        <label for="nombreCrear" class="form-label">
                            <i class="ri-shield-user-line" style="color:#fc7b04;"></i>
                            Nombre del Rol <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="nombreCrear" maxlength="100" placeholder="ej: supervisor" autocomplete="off">
                            <span class="validation-icon" id="iconCrear"></span>
                        </div>
                        <div class="field-feedback" id="fbCrear"></div>
                        <div class="char-hint" id="hintCrear">0 / 100</div>
                        <div class="form-text mt-2">
                            Solo letras minúsculas, números, guiones y guion bajo.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-modal-submit" id="btnGuardar" disabled>
                            <i class="ri-save-line"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-edit-2-line"></i> Editar Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditar" novalidate autocomplete="off">
                    <input type="hidden" id="idEditar">
                    <div class="modal-body">
                        <label for="nombreEditar" class="form-label">
                            <i class="ri-shield-user-line" style="color:#fc7b04;"></i>
                            Nombre del Rol <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="nombreEditar" maxlength="100" autocomplete="off">
                            <span class="validation-icon" id="iconEditar"></span>
                        </div>
                        <div class="field-feedback" id="fbEditar"></div>
                        <div class="char-hint" id="hintEditar">0 / 100</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-modal-submit" id="btnActualizar" disabled>
                            <i class="ri-refresh-line"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPermisos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width:720px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-shield-keyhole-line"></i>
                        Permisos del rol
                        <span id="rolNombrePermisos" class="badge-rol"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="perm-toolbar">
                    <div class="perm-search-wrap">
                        <i class="ri-search-2-line"></i>
                        <input type="text" id="permSearch" placeholder="Buscar permiso o módulo...">
                    </div>
                    <div class="perm-toolbar-actions">
                        <button type="button" class="btn-tb" id="btnExpandAll" title="Expandir todos">
                            <i class="ri-expand-vertical-line"></i> Expandir
                        </button>
                        <button type="button" class="btn-tb" id="btnCollapseAll" title="Colapsar todos">
                            <i class="ri-contract-vertical-line"></i> Colapsar
                        </button>
                        <button type="button" class="btn-tb" id="btnSelAll" title="Marcar todos">
                            <i class="ri-checkbox-multiple-line"></i> Todos
                        </button>
                        <button type="button" class="btn-tb" id="btnSelNone" title="Desmarcar todos">
                            <i class="ri-checkbox-blank-line"></i> Ninguno
                        </button>
                    </div>
                </div>

                <div class="perm-summary">
                    <i class="ri-information-line" style="color:#fc7b04;"></i>
                    <span><strong id="permCount">0</strong> de <strong id="permTotal">0</strong> permisos seleccionados</span>
                    <span class="ms-auto pill" id="permGruposCount">0 módulos</span>
                </div>

                <input type="hidden" id="rolIdPermisos">
                <div class="perm-body-scroll" id="permisosContainer"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-modal-submit" id="btnSyncPermisos">
                        <i class="ri-save-line"></i> Guardar permisos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-error-warning-line"></i> Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Eliminar el rol <strong id="nombreEliminar"></strong>?</p>
                    <p class="text-muted small">Los usuarios que tengan asignado este rol lo perderán.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger-modal" id="btnConfirmarEliminar"><i class="ri-delete-bin-line"></i> Eliminar</button>
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
(function(){
    'use strict';
    const CSRF = '{{ csrf_token() }}';
    const REGEX_NAME = /^[a-z0-9\-\_]+$/;
    const MIN_LEN = 3;
    const MAX_LEN = 100;
    let tabla, idEliminar = null;
    let timerCrear = null, timerEditar = null;

    $(document).ready(function(){
        tabla = $('#tabla-roles').DataTable({
            ajax: { url: '{{ route("admin.roles.listar") }}', dataSrc: 'data' },
            paging: true, pageLength: 10, info: true,
            pagingType: 'simple_numbers',
            dom: "<'row mb-2'<'col-sm-6'l><'col-sm-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>",
            columns: [
                { data: 'name', render: n => '<span style="font-weight:600;text-transform:uppercase;color:#b85500;">'+escHtml(n)+'</span>' },
                { data: 'permissions_count', className:'text-center', render: c => '<span class="badge-soft">'+c+'</span>' },
                { data: 'users_count', className:'text-center', render: c => '<span class="badge-soft">'+c+'</span>' },
                { data: null, className:'text-center', render: d => {
                    const noEliminar = (d.name === 'admin');
                    return '<div class="action-cell">'+
                        '<button class="btn btn-action btn-action-edit btn-permisos" data-id="'+d.id+'" data-name="'+escHtml(d.name)+'" title="Permisos"><i class="ri-shield-keyhole-line"></i></button>'+
                        '<button class="btn btn-action btn-action-edit btn-editar" data-id="'+d.id+'" data-name="'+escHtml(d.name)+'" title="Editar"><i class="ri-pencil-fill"></i></button>'+
                        (noEliminar ? '' : '<button class="btn btn-action btn-action-delete btn-eliminar" data-id="'+d.id+'" data-name="'+escHtml(d.name)+'" title="Eliminar"><i class="ri-delete-bin-fill"></i></button>')+
                        '</div>';
                }}
            ],
            order: [[0,'asc']],
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
                search: '',
                searchPlaceholder: 'Buscar rol...',
                zeroRecords: 'No se encontraron coincidencias',
                emptyTable: 'No hay roles registrados',
                lengthMenu: '_MENU_',
                info: 'Mostrando <strong>_START_</strong> a <strong>_END_</strong> de <strong>_TOTAL_</strong>',
                infoEmpty: 'Sin registros',
                infoFiltered: '(filtrado de _MAX_)',
                paginate: { first: '«', previous: '‹', next: '›', last: '»' }
            },
            drawCallback: function(){ $('#stat-total').text(this.api().rows().data().length); }
        });

        // ── Botón Nuevo ──
        $('#btn-nuevo').on('click', ()=>{
            $('#formCrear')[0].reset();
            resetField('nombreCrear','iconCrear','fbCrear','hintCrear');
            $('#btnGuardar').prop('disabled', true);
            openModal('modalCrear');
        });

        // ── Botón Editar ──
        $(document).on('click', '.btn-editar', function(){
            $('#idEditar').val($(this).data('id'));
            $('#nombreEditar').val($(this).data('name'));
            resetField('nombreEditar','iconEditar','fbEditar','hintEditar');
            actualizarHint('nombreEditar','hintEditar');
            setOk('nombreEditar','iconEditar','fbEditar','Nombre válido');
            $('#btnActualizar').prop('disabled', false);
            openModal('modalEditar');
        });

        $(document).on('click', '.btn-eliminar', function(){
            idEliminar = $(this).data('id');
            $('#nombreEliminar').text($(this).data('name'));
            openModal('modalEliminar');
        });

        $(document).on('click', '.btn-permisos', function(){
            const id = $(this).data('id');
            $('#rolIdPermisos').val(id);
            $('#rolNombrePermisos').text($(this).data('name'));
            cargarPermisos(id);
            openModal('modalPermisos');
        });

        // ── Validación en tiempo real: Crear ──
        $('#nombreCrear').on('input', function(){
            actualizarHint('nombreCrear','hintCrear');
            clearTimeout(timerCrear);
            const val = $(this).val().trim().toLowerCase();
            if (!validarFormato(val, 'nombreCrear','iconCrear','fbCrear','#btnGuardar')) return;
            setChecking('nombreCrear','iconCrear','fbCrear','Verificando disponibilidad...');
            $('#btnGuardar').prop('disabled', true);
            timerCrear = setTimeout(()=> verificarNombre(val, null, 'nombreCrear','iconCrear','fbCrear','#btnGuardar'), 350);
        });

        // ── Validación en tiempo real: Editar ──
        $('#nombreEditar').on('input', function(){
            actualizarHint('nombreEditar','hintEditar');
            clearTimeout(timerEditar);
            const val = $(this).val().trim().toLowerCase();
            const id = $('#idEditar').val();
            if (!validarFormato(val, 'nombreEditar','iconEditar','fbEditar','#btnActualizar')) return;
            setChecking('nombreEditar','iconEditar','fbEditar','Verificando disponibilidad...');
            $('#btnActualizar').prop('disabled', true);
            timerEditar = setTimeout(()=> verificarNombre(val, id, 'nombreEditar','iconEditar','fbEditar','#btnActualizar'), 350);
        });

        // ── Submits ──
        $('#formCrear').on('submit', e => { e.preventDefault(); guardar(); });
        $('#formEditar').on('submit', e => { e.preventDefault(); actualizar(); });
        $('#btnGuardar').on('click', guardar);
        $('#btnActualizar').on('click', actualizar);
        $('#btnConfirmarEliminar').on('click', ()=> idEliminar && eliminar(idEliminar));
        $('#btnSyncPermisos').on('click', sincronizarPermisos);

        $('#btnSelAll').on('click', ()=> {
            $('#permisosContainer .perm-grupo:visible input[type=checkbox]').prop('checked', true).trigger('change');
        });
        $('#btnSelNone').on('click', ()=> {
            $('#permisosContainer .perm-grupo:visible input[type=checkbox]').prop('checked', false).trigger('change');
        });
        $('#btnExpandAll').on('click', ()=> $('#permisosContainer .perm-grupo').addClass('open'));
        $('#btnCollapseAll').on('click', ()=> $('#permisosContainer .perm-grupo').removeClass('open'));

        $('#permSearch').on('input', function(){
            const q = $(this).val().toLowerCase().trim();
            $('#permisosContainer .perm-grupo').each(function(){
                const $g = $(this);
                const modulo = ($g.data('modulo') || '').toLowerCase();
                let anyVisible = false;
                $g.find('.perm-chip').each(function(){
                    const match = !q || $(this).text().toLowerCase().includes(q) || modulo.includes(q);
                    $(this).toggle(match);
                    if (match) anyVisible = true;
                });
                $g.toggle(anyVisible);
                if (q && anyVisible) $g.addClass('open');
            });
        });

        // Toggle expand/collapse al hacer click en el header
        $(document).on('click', '#permisosContainer .perm-grupo-header', function(e){
            if ($(e.target).closest('.perm-grupo-toggleall').length) return;
            $(this).closest('.perm-grupo').toggleClass('open');
        });

        // Marcar/desmarcar todos los permisos de un módulo
        $(document).on('click', '#permisosContainer .perm-grupo-toggleall', function(e){
            e.stopPropagation();
            const $g = $(this).closest('.perm-grupo');
            const $cbs = $g.find('input[type=checkbox]');
            const allChecked = $cbs.length === $cbs.filter(':checked').length;
            $cbs.prop('checked', !allChecked).trigger('change');
        });

        $(document).on('change', '#permisosContainer input[type=checkbox]', function(){
            $(this).closest('.perm-chip').toggleClass('checked', this.checked);
            actualizarGrupoEstado($(this).closest('.perm-grupo'));
            actualizarResumenPermisos();
        });

        document.getElementById('modalCrear').addEventListener('hidden.bs.modal', ()=>{
            $('#formCrear')[0].reset();
            resetField('nombreCrear','iconCrear','fbCrear','hintCrear');
            $('#btnGuardar').prop('disabled', true);
        });
    });

    // ── Validación de formato (cliente) ──
    function validarFormato(val, inputId, iconId, fbId, btnSel){
        if (val.length === 0) {
            resetField(inputId, iconId, fbId, null);
            $(btnSel).prop('disabled', true);
            return false;
        }
        if (val.length < MIN_LEN) {
            setError(inputId, iconId, fbId, 'Debe tener al menos '+MIN_LEN+' caracteres.');
            $(btnSel).prop('disabled', true);
            return false;
        }
        if (val.length > MAX_LEN) {
            setError(inputId, iconId, fbId, 'No puede superar los '+MAX_LEN+' caracteres.');
            $(btnSel).prop('disabled', true);
            return false;
        }
        if (!REGEX_NAME.test(val)) {
            setError(inputId, iconId, fbId, 'Solo letras minúsculas, números, guiones y guion bajo.');
            $(btnSel).prop('disabled', true);
            return false;
        }
        return true;
    }

    function verificarNombre(val, id, inputId, iconId, fbId, btnSel){
        $.ajax({
            url: '{{ route("admin.roles.verificar") }}',
            type: 'POST',
            data: { _token: CSRF, name: val, id: id || null }
        })
        .done(r => {
            if (r.existe) {
                setError(inputId, iconId, fbId, 'Este rol ya existe.');
                $(btnSel).prop('disabled', true);
            } else {
                setOk(inputId, iconId, fbId, 'Nombre disponible');
                $(btnSel).prop('disabled', false);
            }
        })
        .fail(() => {
            setError(inputId, iconId, fbId, 'Error al verificar disponibilidad.');
            $(btnSel).prop('disabled', true);
        });
    }

    function setError(inputId, iconId, fbId, msg){
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        const fb    = document.getElementById(fbId);
        input.classList.remove('is-valid'); input.classList.add('is-invalid');
        icon.className = 'validation-icon invalid';
        icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
        fb.className = 'field-feedback error';
        fb.innerHTML = '<i class="ri-error-warning-line"></i> ' + msg;
    }
    function setOk(inputId, iconId, fbId, msg){
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        const fb    = document.getElementById(fbId);
        input.classList.remove('is-invalid'); input.classList.add('is-valid');
        icon.className = 'validation-icon valid';
        icon.innerHTML = '<i class="ri-checkbox-circle-fill"></i>';
        fb.className = 'field-feedback success';
        fb.innerHTML = '<i class="ri-check-line"></i> ' + msg;
    }
    function setChecking(inputId, iconId, fbId, msg){
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        const fb    = document.getElementById(fbId);
        input.classList.remove('is-valid','is-invalid');
        icon.className = 'validation-icon';
        icon.innerHTML = '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;opacity:1;color:#6b7280;"></i>';
        fb.className = 'field-feedback checking';
        fb.innerHTML = '<i class="ri-time-line"></i> ' + msg;
    }
    function resetField(inputId, iconId, fbId, hintId){
        const input = document.getElementById(inputId);
        input.classList.remove('is-valid','is-invalid');
        document.getElementById(iconId).className = 'validation-icon';
        document.getElementById(iconId).innerHTML = '';
        document.getElementById(fbId).className = 'field-feedback';
        document.getElementById(fbId).innerHTML = '';
        if (hintId) {
            document.getElementById(hintId).textContent = '0 / '+MAX_LEN;
            document.getElementById(hintId).className = 'char-hint';
        }
    }
    function actualizarHint(inputId, hintId){
        const len = document.getElementById(inputId).value.length;
        const hint = document.getElementById(hintId);
        hint.textContent = len + ' / ' + MAX_LEN;
        hint.className = 'char-hint' + (len > MAX_LEN - 15 ? ' warning' : '');
    }

    const MODULO_ICONS = {
        usuarios:'ri-user-line', roles:'ri-shield-user-line', permisos:'ri-key-2-line',
        personas:'ri-team-line', estudiantes:'ri-graduation-cap-line', docentes:'ri-user-star-line',
        trabajadores:'ri-briefcase-line', cargos:'ri-suitcase-line', departamentos:'ri-map-pin-line',
        areas:'ri-layout-grid-line', tipos:'ri-price-tag-3-line', convenios:'ri-handshake-line',
        posgrados:'ri-medal-line', ofertas:'ri-store-2-line', modulos:'ri-stack-line',
        programas:'ri-book-open-line', 'grados-academicos':'ri-award-line',
        profesiones:'ri-building-line', universidades:'ri-bank-line', sedes:'ri-building-2-line',
        fases:'ri-flow-chart', modalidades:'ri-list-settings-line',
        conceptos:'ri-coin-line', 'planes-pago':'ri-bill-line', cronograma:'ri-calendar-line',
        'cuentas-videollamada':'ri-vidicon-line', contabilidad:'ri-calculator-line',
        comprobantes:'ri-receipt-line', moodle:'ri-global-line',
        actividades:'ri-list-check-2', academico:'ri-book-mark-line', dashboard:'ri-dashboard-line'
    };

    function iconoModulo(m){ return MODULO_ICONS[m] || 'ri-folder-2-line'; }

    function cargarPermisos(id){
        $('#permisosContainer').html('<div class="text-center py-5"><span class="spinner-border" style="color:#fc7b04;"></span><div class="mt-2 text-muted small">Cargando permisos...</div></div>');
        $('#permCount').text('0');
        $('#permTotal').text('0');
        $('#permGruposCount').text('0 módulos');

        $.get('/admin/roles/'+id+'/permisos').done(r=>{
            if(!r.success){ toast('error', r.message); return; }
            const grupos = r.grupos || {};
            const keys = Object.keys(grupos).sort();

            if (keys.length === 0) {
                $('#permisosContainer').html('<div class="perm-empty"><i class="ri-inbox-line"></i><div>No hay permisos registrados.</div></div>');
                return;
            }

            let html = '';
            let totalPermisos = 0;
            keys.forEach(modulo => {
                const items = grupos[modulo] || [];
                const asignados = items.filter(p => p.asignado).length;
                const total = items.length;
                totalPermisos += total;
                const hasSel = asignados > 0;

                html += '<div class="perm-grupo '+(hasSel?'has-selected':'')+'" data-modulo="'+escHtml(modulo)+'">';
                html += '  <div class="perm-grupo-header">';
                html += '    <i class="ri-arrow-right-s-line chevron"></i>';
                html += '    <div class="perm-grupo-icon"><i class="'+iconoModulo(modulo)+'"></i></div>';
                html += '    <div class="perm-grupo-name">'+escHtml(modulo.replace(/-/g,' '))+'</div>';
                html += '    <span class="perm-grupo-count"><span class="cnt-sel">'+asignados+'</span>/<span class="cnt-tot">'+total+'</span></span>';
                html += '    <button type="button" class="perm-grupo-toggleall" title="Marcar/desmarcar todos en este módulo">'+
                                '<i class="ri-checkbox-multiple-line"></i></button>';
                html += '  </div>';
                html += '  <div class="perm-grupo-body">';
                items.forEach(p => {
                    html += '<label class="perm-chip '+(p.asignado?'checked':'')+'" title="'+escHtml(p.name)+'">';
                    html += '  <input type="checkbox" value="'+p.id+'" '+(p.asignado?'checked':'')+'>';
                    html += '  <span>'+escHtml(p.accion)+'</span>';
                    html += '</label>';
                });
                html += '  </div>';
                html += '</div>';
            });

            $('#permisosContainer').html(html);
            $('#permTotal').text(totalPermisos);
            $('#permGruposCount').text(keys.length + ' ' + (keys.length === 1 ? 'módulo' : 'módulos'));
            actualizarResumenPermisos();
        }).fail(()=> {
            $('#permisosContainer').html('<div class="perm-empty"><i class="ri-error-warning-line"></i><div>Error al cargar permisos.</div></div>');
            toast('error','No se pudieron cargar los permisos.');
        });
    }

    function actualizarGrupoEstado($g){
        const $cbs = $g.find('input[type=checkbox]');
        const sel = $cbs.filter(':checked').length;
        const tot = $cbs.length;
        $g.find('.cnt-sel').text(sel);
        $g.toggleClass('has-selected', sel > 0);
    }

    function actualizarResumenPermisos(){
        const total = $('#permisosContainer input[type=checkbox]:checked').length;
        $('#permCount').text(total);
    }

    function sincronizarPermisos(){
        const id = $('#rolIdPermisos').val();
        const ids = $('#permisosContainer input[type=checkbox]:checked').map(function(){ return this.value; }).get();
        $('#btnSyncPermisos').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Guardando…');
        $.post('/admin/roles/'+id+'/permisos', { _token: CSRF, permisos: ids })
            .done(r=>{ closeModal('modalPermisos'); tabla.ajax.reload(); toast('success', r.message); })
            .fail(()=> toast('error', 'Error al guardar permisos.'))
            .always(()=> $('#btnSyncPermisos').prop('disabled', false).html('<i class="ri-save-line"></i> Guardar permisos'));
    }

    function guardar(){
        const name = $('#nombreCrear').val().trim().toLowerCase();
        if (!validarFormato(name,'nombreCrear','iconCrear','fbCrear','#btnGuardar')) return;
        setBtnLoading('#btnGuardar', true, 'Guardando…');
        $.post('{{ route("admin.roles.guardar") }}', { _token: CSRF, name })
            .done(r=>{ closeModal('modalCrear'); tabla.ajax.reload(); toast('success', r.message); })
            .fail(xhr=> handleErr(xhr, 'nombreCrear','iconCrear','fbCrear'))
            .always(()=> setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar'));
    }

    function actualizar(){
        const id = $('#idEditar').val();
        const name = $('#nombreEditar').val().trim().toLowerCase();
        if (!validarFormato(name,'nombreEditar','iconEditar','fbEditar','#btnActualizar')) return;
        setBtnLoading('#btnActualizar', true, 'Actualizando…');
        $.ajax({ url: '/admin/roles/'+id, type:'PUT', data:{ _token: CSRF, name }})
            .done(r=>{ closeModal('modalEditar'); tabla.ajax.reload(); toast('success', r.message); })
            .fail(xhr=> handleErr(xhr, 'nombreEditar','iconEditar','fbEditar'))
            .always(()=> setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'));
    }

    function eliminar(id){
        setBtnLoading('#btnConfirmarEliminar', true, 'Eliminando…');
        $.ajax({ url:'/admin/roles/'+id, type:'DELETE', data:{ _token: CSRF }})
            .done(r=>{ closeModal('modalEliminar'); tabla.ajax.reload(); toast('success', r.message); })
            .fail(xhr=> toast('error', xhr.responseJSON?.message || 'Error al eliminar.'))
            .always(()=>{ setBtnLoading('#btnConfirmarEliminar', false, '<i class="ri-delete-bin-line"></i> Eliminar'); idEliminar = null; });
    }

    function handleErr(xhr, inputId, iconId, fbId){
        if (xhr.status === 422) {
            const errs = xhr.responseJSON?.errors?.name;
            setError(inputId, iconId, fbId, errs ? errs[0] : 'Error de validación.');
        } else {
            toast('error', xhr.responseJSON?.message || 'Error al procesar.');
        }
    }

    function setBtnLoading(sel, loading, labelHtml){
        const btn = document.querySelector(sel);
        if (!btn) return;
        btn.disabled = loading;
        if (loading) {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + labelHtml;
        } else {
            btn.innerHTML = labelHtml;
        }
    }

    function openModal(id){ new bootstrap.Modal(document.getElementById(id)).show(); }
    function closeModal(id){ const m = bootstrap.Modal.getInstance(document.getElementById(id)); if(m) m.hide(); }
    function escHtml(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

    function toast(tipo, mensaje){
        const el = document.createElement('div');
        el.className = 'toast-notify '+tipo;
        const icon = tipo==='success'?'ri-check-double-line':(tipo==='error'?'ri-close-circle-line':'ri-alert-line');
        el.innerHTML = '<div class="toast-icon"><i class="'+icon+'"></i></div><div class="toast-body-text"><span>'+mensaje+'</span></div><button class="toast-close"><i class="ri-close-line"></i></button>';
        document.getElementById('toastContainer').appendChild(el);
        el.querySelector('.toast-close').addEventListener('click', ()=> el.remove());
        setTimeout(()=> el.remove(), 4500);
    }
})();
</script>
@endsection
