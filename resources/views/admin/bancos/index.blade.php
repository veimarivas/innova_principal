@extends('layouts.master')
@section('title') Bancos @endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
.badge-cuentas {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: linear-gradient(135deg, rgba(154, 73, 4, 0.12) 0%, rgba(154, 73, 4, 0.06) 100%);
    color: var(--d-badge-color);
    border: 1px solid var(--d-badge-border);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    transition: all 0.2s ease;
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
}
.badge-cuentas:hover { background: linear-gradient(135deg, rgba(154, 73, 4, 0.2) 0%, rgba(154, 73, 4, 0.1) 100%); max-width: 300px; }
.badge-cuentas i { font-size: 0.7rem; }
.badge-vacio { background: rgba(154, 73, 4, 0.06); border: 1px dashed rgba(154, 73, 4, 0.3); color: var(--d-muted); }
.estado-toggle {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.3rem 0.7rem;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    user-select: none;
}
.estado-toggle.activo {
    background: rgba(40, 167, 69, 0.12);
    color: #28a745;
    border-color: rgba(40, 167, 69, 0.25);
}
.estado-toggle.activo:hover {
    background: rgba(40, 167, 69, 0.2);
    border-color: rgba(40, 167, 69, 0.4);
}
.estado-toggle.inactivo {
    background: rgba(108, 117, 125, 0.10);
    color: #6c757d;
    border-color: rgba(108, 117, 125, 0.20);
}
.estado-toggle.inactivo:hover {
    background: rgba(108, 117, 125, 0.18);
    border-color: rgba(108, 117, 125, 0.35);
}
.cuenta-tipo-badge { font-size: 0.7rem; padding: 0.2rem 0.55rem; border-radius: 10px; font-weight: 600; white-space: nowrap; }
.cuenta-tipo-badge.cc { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
.cuenta-tipo-badge.ca { background: rgba(25, 135, 84, 0.1); color: #198754; }
.cuenta-principal-badge { font-size: 0.65rem; padding: 0.15rem 0.45rem; border-radius: 10px; background: rgba(255, 193, 7, 0.15); color: #997404; font-weight: 700; }
.cuenta-actions { display: flex; align-items: center; gap: 0.3rem; justify-content: center; }
#tablaCuentas td { padding: 0.65rem 0.75rem !important; vertical-align: middle; }
#tablaCuentas thead th { font-size: 0.65rem !important; padding: 0.6rem 0.75rem !important; }
.btn-cuenta-action {
    width: 32px; height: 32px; padding: 0 !important;
    display: inline-flex !important; align-items: center; justify-content: center;
    border-radius: 8px !important; border: 1px solid transparent;
    transition: all 0.2s ease !important; font-size: 0.85rem !important;
    cursor: pointer;
}
.btn-cuenta-action.edit { background: var(--d-edit-bg); color: var(--d-edit-color); border-color: rgba(154, 73, 4, 0.18); }
.btn-cuenta-action.edit:hover { background: linear-gradient(135deg, #9a4904, #df6a04); color: #fff; border-color: transparent; }
.btn-cuenta-action.delete { background: var(--d-del-bg); color: var(--d-del-color); border-color: rgba(201, 96, 4, 0.18); }
.btn-cuenta-action.delete:hover { background: linear-gradient(135deg, #bc5404, #df6a04); color: #fff; border-color: transparent; }
.btn-cuenta-action.principal { background: rgba(255, 193, 7, 0.12); color: #997404; border-color: rgba(255, 193, 7, 0.25); }
.btn-cuenta-action.principal:hover { background: rgba(255, 193, 7, 0.25); color: #664d03; }
.cuenta-empty { text-align: center; padding: 2rem; color: var(--d-muted); }
.cuenta-empty i { font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5; }
.cuentas-form-section {
    background: rgba(154, 73, 4, 0.04);
    border: 1px solid rgba(154, 73, 4, 0.10);
    border-radius: 12px;
    padding: 1.25rem 1.25rem 0.75rem;
    margin-bottom: 1rem;
}
.cuentas-form-section .form-label { font-size: 0.78rem !important; margin-bottom: 0.3rem !important; }
.cuentas-form-section .form-control,
.cuentas-form-section .form-select {
    padding: 0.55rem 0.85rem !important;
    font-size: 0.85rem !important;
    border-radius: 8px !important;
}
.cuentas-form-section .form-check-label { font-size: 0.82rem; color: var(--d-body); font-weight: 500; }
.cuentas-form-section .form-check-input { cursor: pointer; }
.cuentas-form-section .form-check-input:checked { background-color: #fc7b04; border-color: #fc7b04; }
.cuentas-divider {
    display: flex; align-items: center; gap: 0.75rem;
    margin: 0.75rem 0 1rem;
    color: var(--d-muted);
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.cuentas-divider::after {
    content: ''; flex: 1; height: 1px;
    background: linear-gradient(to right, var(--d-row-border), transparent);
}
.cuentas-section-title {
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--d-subtitle);
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 0.75rem;
}
.cuentas-section-title i { font-size: 0.85rem; }
</style>
@endsection

@section('content')
<!-- Hero Header -->
<div class="dept-page-header">
    <div class="container-fluid">
        <div class="dph-inner">
            <div class="dph-left">
                <div class="dph-icon-wrap">
                    <i class="ri-bank-line"></i>
                </div>
                <div class="dph-text-block">
                    <h1 class="dph-title">Bancos</h1>
                    <p class="dph-desc">Gestión y administración de bancos y cuentas bancarias</p>
                    <ol class="dph-breadcrumb">
                        <li><i class="ri-home-4-line"></i> Finanzas</li>
                        <li class="dph-sep"><i class="ri-arrow-right-s-line"></i></li>
                        <li class="active">Bancos</li>
                    </ol>
                </div>
            </div>
            <div class="dph-right">
                <div class="dph-stat-card">
                    <div class="dph-stat-icon"><i class="ri-bank-line"></i></div>
                    <div>
                        <div class="dph-stat-num" id="stat-total">—</div>
                        <div class="dph-stat-label">Total Registros</div>
                    </div>
                </div>
                <button type="button" class="dph-btn-new" id="btn-nuevo">
                    <i class="ri-add-line"></i>
                    <span>Nuevo Banco</span>
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
                            <h5 class="dept-title">Listado de Bancos</h5>
                            <p class="dept-subtitle">Consulta, edita o elimina los registros existentes</p>
                        </div>
                    </div>
                </div>
                <div class="dept-card-body">
                    <table id="tabla-bancos" class="dept-table">
                        <thead>
                            <tr>
                                <th>Nombre del Banco</th>
                                <th>Sigla</th>
                                <th style="width:150px;">Cuentas</th>
                                <th class="text-center" style="width:100px;">Estado</th>
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

<!-- ===================== MODAL CREAR ===================== -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-add-circle-line"></i> Nuevo Banco
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formCrear" novalidate autocomplete="off">
                <div class="modal-body">
                    <div class="mb-1">
                        <label for="nombreCrear" class="form-label">
                            <i class="ri-bank-line" style="color:#fc7b04;"></i>
                            Nombre del Banco <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="nombreCrear"
                                   placeholder="Ej: Banco Mercantil Santa Cruz..."
                                   maxlength="100" autocomplete="off">
                            <span class="validation-icon" id="iconCrear"></span>
                        </div>
                        <div class="field-feedback" id="fbCrear"></div>
                        <div class="char-hint" id="hintCrear">0 / 100</div>
                    </div>
                    <div class="mb-1">
                        <label for="siglaCrear" class="form-label">
                            <i class="ri-font-size" style="color:#fc7b04;"></i>
                            Sigla
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="siglaCrear"
                                   placeholder="Ej: BMSA" maxlength="20" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-modal-submit" id="btnGuardar">
                        <i class="ri-save-line"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===================== MODAL EDITAR ===================== -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-edit-2-line"></i> Editar Banco
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditar" novalidate autocomplete="off">
                <input type="hidden" id="idEditar">
                <div class="modal-body">
                    <div class="mb-1">
                        <label for="nombreEditar" class="form-label">
                            <i class="ri-bank-line" style="color:#fc7b04;"></i>
                            Nombre del Banco <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="nombreEditar"
                                   placeholder="Ej: Banco Mercantil Santa Cruz..."
                                   maxlength="100" autocomplete="off">
                            <span class="validation-icon" id="iconEditar"></span>
                        </div>
                        <div class="field-feedback" id="fbEditar"></div>
                        <div class="char-hint" id="hintEditar">0 / 100</div>
                    </div>
                    <div class="mb-1">
                        <label for="siglaEditar" class="form-label">
                            <i class="ri-font-size" style="color:#fc7b04;"></i>
                            Sigla
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="siglaEditar"
                                   placeholder="Ej: BMSA" maxlength="20" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-modal-submit" id="btnActualizar">
                        <i class="ri-refresh-line"></i> Actualizar
                    </button>
                </div>
            </form>
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
                    <p class="delete-msg-primary">¿Eliminar banco?</p>
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

<!-- ===================== MODAL CUENTAS ===================== -->
<div class="modal fade" id="modalCuentas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:720px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-bank-card-line"></i> Cuentas de <span id="bancoCuentasNombre"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="cuentas-form-section">
                    <div class="cuentas-section-title">
                        <i class="ri-add-circle-line"></i>
                        <span id="tituloFormCuenta">Registrar Nueva Cuenta</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="numeroCuenta" class="form-label">
                                Número de Cuenta <span class="req">*</span>
                            </label>
                            <input type="text" class="form-control" id="numeroCuenta"
                                   placeholder="Ej: 401-234567-1-..." maxlength="50" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label for="tipoCuenta" class="form-label">Tipo de Cuenta <span class="req">*</span></label>
                            <select class="form-select" id="tipoCuenta">
                                <option value="Cuenta Corriente">Cuenta Corriente</option>
                                <option value="Cuenta de Ahorro">Cuenta de Ahorro</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="titularCuenta" class="form-label">Titular</label>
                            <input type="text" class="form-control" id="titularCuenta"
                                   placeholder="Nombre del titular (opcional)" maxlength="150" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label for="ciTitularCuenta" class="form-label">CI / NIT Titular</label>
                            <input type="text" class="form-control" id="ciTitularCuenta"
                                   placeholder="Cédula de identidad (opcional)" maxlength="20" autocomplete="off">
                        </div>
                    </div>
                    <div class="row g-3 mt-2 align-items-center">
                        <div class="col-md-5">
                            <div class="form-check mb-0">
                                <input type="checkbox" class="form-check-input" id="esPrincipalCuenta">
                                <label class="form-check-label" for="esPrincipalCuenta">
                                    <i class="ri-star-fill" style="color:#fc7b04;"></i>
                                    Marcar como cuenta principal
                                </label>
                            </div>
                        </div>
                        <div class="col-md-7 d-flex justify-content-end gap-2">
                            <input type="hidden" id="idCuentaEditar">
                            <button type="button" class="btn btn-modal-cancel d-none" id="btnCancelarCuenta">
                                <i class="ri-close-line"></i> Cancelar
                            </button>
                            <button type="button" class="btn btn-modal-submit" id="btnAgregarCuenta">
                                <i class="ri-add-line"></i> <span id="txtBtnCuenta">Agregar Cuenta</span>
                            </button>
                        </div>
                    </div>
                    <div class="field-feedback mt-2" id="fbCuenta"></div>
                </div>

                <div class="cuentas-divider">
                    <i class="ri-list-check"></i> Cuentas registradas
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tablaCuentas">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Tipo</th>
                                <th>Titular</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Principal</th>
                                <th class="text-center" style="width:140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuentasList"></tbody>
                    </table>
                </div>
                <div class="cuenta-empty text-center py-4 d-none" id="cuentaEmpty">
                    <i class="ri-bank-card-line d-block mb-2" style="font-size: 2rem; opacity: 0.4;"></i>
                    <p class="mb-0 text-muted">No hay cuentas registradas para este banco</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL ELIMINAR CUENTA ===================== -->
<div class="modal fade" id="modalEliminarCuenta" tabindex="-1" aria-hidden="true">
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
                    <p class="delete-msg-primary">¿Eliminar cuenta bancaria?</p>
                    <p class="delete-msg-name">
                        <strong id="nombreEliminarCuenta"></strong>
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
                <button type="button" class="btn btn-danger-modal px-4" id="btnConfirmarEliminarCuenta">
                    <i class="ri-delete-bin-line"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Contenedor de toasts -->
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
    let idEliminarCuenta = null;
    const CSRF = '{{ csrf_token() }}';

    function init() {
        initDataTable();
        bindEvents();
    }

    function initDataTable() {
        tabla = $('#tabla-bancos').DataTable({
            ajax: { url: '{{ route("admin.bancos.listar") }}', dataSrc: 'data' },
            ordering: true,
            columns: [
                {
                    data: 'nombre',
                    render: n => '<span style="font-weight:600;">' + escHtml(n) + '</span>'
                },
                {
                    data: 'sigla',
                    render: s => s ? '<span class="text-muted">' + escHtml(s) + '</span>' : '<span class="text-muted" style="opacity:0.4;">—</span>'
                },
                {
                    data: null,
                    render: d => {
                        const cuentas = d.cuentas || [];
                        if (cuentas.length === 0) {
                            return '<span class="badge-cuentas badge-vacio btn-abrir-cuentas" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '"><i class="ri-add-line"></i> Agregar</span>';
                        }
                        return '<span class="badge-cuentas btn-abrir-cuentas" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" title="Click para gestionar cuentas"><i class="ri-bank-card-line"></i> ' + cuentas.length + '</span>';
                    }
                },
                {
                    data: null, className: 'text-center',
                    render: d => {
                        const activo = d.estado;
                        return '<span class="estado-toggle ' + (activo ? 'activo' : 'inactivo') + ' btn-toggle-estado" data-id="' + d.id + '">'
                            + '<i class="ri-' + (activo ? 'checkbox-circle' : 'close-circle') + '-line"></i> '
                            + (activo ? 'Activo' : 'Inactivo')
                            + '</span>';
                    }
                },
                {
                    data: null, className: 'text-center',
                    render: d =>
                        '<div class="action-cell">'
                        + '<button class="btn btn-action btn-action-edit btn-accion-editar" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" data-sigla="' + escHtml(d.sigla || '') + '" title="Editar banco"><i class="ri-pencil-fill"></i></button>'
                        + '<button class="btn btn-action btn-action-delete btn-accion-eliminar" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" title="Eliminar banco"><i class="ri-delete-bin-fill"></i></button>'
                        + '</div>'
                }
            ],
            language: {
                processing:     'Procesando...',
                search:         'Buscar:',
                lengthMenu:     'Mostrar _MENU_ registros',
                info:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty:      'Mostrando 0 a 0 de 0 registros',
                infoFiltered:   '(filtrado de _MAX_ registros totales)',
                infoPostFix:    '',
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
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
            pageLength: 10,
            drawCallback: function () {
                const info = this.api().page.info();
                $('#stat-total').text(info.recordsTotal);
            }
        });
    }

    function bindEvents() {
        $('#btn-nuevo').on('click', () => {
            resetField('nombreCrear', 'iconCrear', 'fbCrear', 'hintCrear');
            $('#formCrear')[0].reset();
            openModal('modalCrear');
        });

        $(document).on('click', '.btn-accion-editar', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            const sigla = $(this).data('sigla');
            $('#idEditar').val(id);
            $('#nombreEditar').val(nombre);
            $('#siglaEditar').val(sigla);
            actualizarHint('nombreEditar', 'hintEditar');
            validar('nombreEditar', 'iconEditar', 'fbEditar');
            openModal('modalEditar');
        });

        $(document).on('click', '.btn-accion-eliminar', function () {
            idEliminar = $(this).data('id');
            $('#nombreEliminar').text($(this).data('nombre'));
            openModal('modalEliminar');
        });

        $(document).on('click', '.btn-abrir-cuentas', function () {
            const bancoId = $(this).data('id');
            const bancoNombre = $(this).data('nombre');
            $('#bancoCuentasNombre').text(bancoNombre);
            limpiarFormCuenta();
            cargarCuentas(bancoId);
            window.idBancoCuenta = bancoId;
            openModal('modalCuentas');
            // Focus al campo numero
            setTimeout(() => $('#numeroCuenta').focus(), 400);
        });

        $(document).on('click', '.btn-toggle-estado', function () {
            const id = $(this).data('id');
            toggleEstado(id);
        });

        $('#btnAgregarCuenta').on('click', function () {
            agregarCuenta();
        });

        $('#numeroCuenta, #tipoCuenta, #titularCuenta, #ciTitularCuenta').on('keypress', function (e) {
            if (e.which === 13) {
                agregarCuenta();
            }
        });

        $(document).on('click', '.btn-eliminar-cuenta', function () {
            idEliminarCuenta = $(this).data('id');
            const numero = $(this).closest('.cuenta-item').find('.cuenta-numero').text();
            $('#nombreEliminarCuenta').text(numero);
            openModal('modalEliminarCuenta');
        });

        $('#btnConfirmarEliminarCuenta').on('click', function () {
            if (!idEliminarCuenta) return;
            setBtnLoading('#btnConfirmarEliminarCuenta', true, '<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                url: '/admin/cuentas-bancarias/' + idEliminarCuenta,
                type: 'DELETE',
                data: { _token: CSRF }
            })
            .done(r => {
                closeModal('modalEliminarCuenta');
                toast('success', r.message || 'Cuenta eliminada correctamente.');
                cargarCuentas(window.idBancoCuenta);
                tabla.ajax.reload();
            })
            .fail(xhr => {
                const msg = xhr.responseJSON?.message || 'No se pudo eliminar la cuenta.';
                toast(xhr.status === 400 ? 'warning' : 'error', msg);
            })
            .always(() => {
                setBtnLoading('#btnConfirmarEliminarCuenta', false, '<i class="ri-delete-bin-line"></i> Eliminar');
                idEliminarCuenta = null;
            });
        });

        $(document).on('click', '.btn-editar-cuenta', function () {
            const id = $(this).data('id');
            const numero = $(this).data('numero');
            const tipo = $(this).data('tipo');
            const titular = $(this).data('titular') || '';
            const ci = $(this).data('ci') || '';
            const principal = $(this).data('principal') || false;
            $('#idCuentaEditar').val(id);
            $('#numeroCuenta').val(numero).focus();
            $('#tipoCuenta').val(tipo);
            $('#titularCuenta').val(titular);
            $('#ciTitularCuenta').val(ci);
            $('#esPrincipalCuenta').prop('checked', principal);
            $('#txtBtnCuenta').text('Actualizar Cuenta');
            $('#tituloFormCuenta').text('Editar Cuenta');
            $('#btnAgregarCuenta').html('<i class="ri-refresh-line"></i> <span id="txtBtnCuenta">Actualizar Cuenta</span>');
            $('#btnCancelarCuenta').removeClass('d-none');
            $('#fbCuenta').text('').removeClass('text-danger text-success');
        });

        $('#btnCancelarCuenta').on('click', function () {
            limpiarFormCuenta();
            $('#numeroCuenta').focus();
        });

        $('#formCrear').on('submit', e => { e.preventDefault(); guardar(); });
        $('#formEditar').on('submit', e => { e.preventDefault(); actualizar(); });
        $('#btnConfirmarEliminar').on('click', confirmarEliminar);

        $('#nombreCrear').on('input', function () {
            actualizarHint('nombreCrear', 'hintCrear');
            validar('nombreCrear', 'iconCrear', 'fbCrear');
        });

        $('#nombreEditar').on('input', function () {
            actualizarHint('nombreEditar', 'hintEditar');
            validar('nombreEditar', 'iconEditar', 'fbEditar');
        });

        document.getElementById('modalCrear').addEventListener('hidden.bs.modal', () => {
            resetField('nombreCrear', 'iconCrear', 'fbCrear', 'hintCrear');
            $('#formCrear')[0].reset();
        });
    }

    function limpiarFormCuenta() {
        $('#idCuentaEditar').val('');
        $('#numeroCuenta').val('');
        $('#tipoCuenta').val('Cuenta Corriente');
        $('#titularCuenta').val('');
        $('#ciTitularCuenta').val('');
        $('#esPrincipalCuenta').prop('checked', false);
        $('#tituloFormCuenta').text('Registrar Nueva Cuenta');
        $('#btnAgregarCuenta').html('<i class="ri-add-line"></i> <span id="txtBtnCuenta">Agregar Cuenta</span>');
        $('#btnCancelarCuenta').addClass('d-none');
        $('#fbCuenta').text('').removeClass('text-danger text-success');
    }

    function validar(inputId, iconId, fbId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        const fb    = document.getElementById(fbId);
        const val   = input.value.trim();

        const setError = msg => {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            icon.className = 'validation-icon invalid';
            icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
            fb.className   = 'field-feedback error';
            fb.innerHTML   = '<i class="ri-error-warning-line"></i>' + msg;
            return false;
        };

        const setOk = () => {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            icon.className = 'validation-icon valid';
            icon.innerHTML = '<i class="ri-checkbox-circle-fill"></i>';
            fb.className   = 'field-feedback success';
            fb.innerHTML   = '<i class="ri-check-line"></i>Nombre válido';
            return true;
        };

        if (!val)                                    return setError('El nombre del banco es obligatorio.');
        if (val.length < 2)                          return setError('Debe tener al menos 2 caracteres.');
        if (val.length > 100)                        return setError('No puede superar los 100 caracteres.');
        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-\.\,]+$/.test(val)) return setError('Solo se permiten letras, espacios, puntos y guiones.');

        return setOk();
    }

    function resetField(inputId, iconId, fbId, hintId) {
        const input = document.getElementById(inputId);
        input.classList.remove('is-valid', 'is-invalid');
        document.getElementById(iconId).className = 'validation-icon';
        document.getElementById(iconId).innerHTML = '';
        document.getElementById(fbId).className   = 'field-feedback';
        document.getElementById(fbId).innerHTML   = '';
        document.getElementById(hintId).textContent = '0 / 100';
        document.getElementById(hintId).className = 'char-hint';
    }

    function actualizarHint(inputId, hintId) {
        const len  = document.getElementById(inputId).value.length;
        const hint = document.getElementById(hintId);
        hint.textContent = len + ' / 100';
        hint.className   = 'char-hint' + (len > 90 ? ' warning' : '');
    }

    function guardar() {
        if (!validar('nombreCrear', 'iconCrear', 'fbCrear')) return;
        setBtnLoading('#btnGuardar', true, 'Guardando…');
        $.post('{{ route("admin.bancos.store") }}', {
            _token: CSRF,
            nombre: $('#nombreCrear').val().trim(),
            sigla: $('#siglaCrear').val().trim()
        })
        .done(r => {
            closeModal('modalCrear');
            tabla.ajax.reload();
            toast('success', r.message || 'Banco guardado correctamente.');
        })
        .fail(xhr => handleAjaxError(xhr, 'nombreCrear', 'iconCrear', 'fbCrear', 'guardar'))
        .always(() => setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar'));
    }

    function actualizar() {
        if (!validar('nombreEditar', 'iconEditar', 'fbEditar')) return;
        const id = $('#idEditar').val();
        setBtnLoading('#btnActualizar', true, 'Actualizando…');
        $.ajax({
            url: '{{ route("admin.bancos.update", ["banco" => "__ID__"]) }}'.replace('__ID__', id),
            type: 'PUT',
            data: {
                _token: CSRF,
                nombre: $('#nombreEditar').val().trim(),
                sigla: $('#siglaEditar').val().trim()
            }
        })
        .done(r => {
            closeModal('modalEditar');
            tabla.ajax.reload();
            toast('success', r.message || 'Banco actualizado correctamente.');
        })
        .fail(xhr => handleAjaxError(xhr, 'nombreEditar', 'iconEditar', 'fbEditar', 'actualizar'))
        .always(() => setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'));
    }

    function confirmarEliminar() {
        if (!idEliminar) return;
        setBtnLoading('#btnConfirmarEliminar', true, '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
        $.ajax({ url: '/admin/bancos/' + idEliminar, type: 'DELETE', data: { _token: CSRF } })
        .done(r => {
            closeModal('modalEliminar');
            tabla.ajax.reload();
            toast('success', r.message || 'Banco eliminado correctamente.');
        })
        .fail(xhr => {
            const msg = xhr.responseJSON ? xhr.responseJSON.message : 'No se pudo eliminar.';
            toast(xhr.status === 400 ? 'warning' : 'error', msg);
        })
        .always(() => {
            setBtnLoading('#btnConfirmarEliminar', false, '<i class="ri-delete-bin-line"></i> Eliminar');
            idEliminar = null;
        });
    }

    function toggleEstado(id) {
        $.ajax({
            url: '/admin/bancos/' + id + '/toggle',
            type: 'PATCH',
            data: { _token: CSRF }
        })
        .done(r => {
            tabla.ajax.reload(null, false);
            toast('success', r.message || 'Estado actualizado.');
        })
        .fail(() => {
            toast('error', 'No se pudo actualizar el estado.');
        });
    }

    function cargarCuentas(bancoId) {
        $.get('{{ route("admin.bancos.listar") }}')
        .done(r => {
            const banco = r.data.find(d => d.id === bancoId);
            const cuentas = banco ? banco.cuentas || [] : [];
            const list = $('#cuentasList');
            const empty = $('#cuentaEmpty');

            if (cuentas.length === 0) {
                list.html('');
                empty.removeClass('d-none');
                return;
            }

            empty.addClass('d-none');
            list.html(cuentas.map(c => {
                const tipoClass = c.tipo_cuenta === 'Cuenta Corriente' ? 'cc' : 'ca';
                const tipoLabel = c.tipo_cuenta === 'Cuenta Corriente' ? 'Cta. Corriente' : 'Cta. Ahorro';
                const estadoHtml = c.estado
                    ? '<span class="badge bg-success" style="font-size:0.7rem;font-weight:600;">Activa</span>'
                    : '<span class="badge bg-secondary" style="font-size:0.7rem;font-weight:600;">Inactiva</span>';
                const principalHtml = c.es_principal
                    ? '<span style="color:#997404;font-size:1rem;"><i class="ri-star-fill"></i></span>'
                    : '<span style="color:var(--d-muted);opacity:0.3;font-size:1rem;"><i class="ri-star-line"></i></span>';

                let btns = '<div class="cuenta-actions">';
                btns += '<a href="/admin/cuentas-bancarias/' + c.id + '/detalle" class="btn-cuenta-action" style="background:rgba(13,110,253,0.08);color:#0d6efd;border-color:rgba(13,110,253,0.18);" title="Ver detalle"><i class="ri-eye-line"></i></a>';
                btns += '<button class="btn-cuenta-action edit btn-editar-cuenta" data-id="' + c.id + '" data-numero="' + escHtml(c.numero_cuenta) + '" data-tipo="' + escHtml(c.tipo_cuenta) + '" data-titular="' + escHtml(c.titular || '') + '" data-ci="' + escHtml(c.ci_titular || '') + '" data-principal="' + (c.es_principal ? '1' : '0') + '" title="Editar cuenta"><i class="ri-pencil-fill"></i></button>';
                if (!c.es_principal) {
                    btns += '<button class="btn-cuenta-action principal btn-set-principal" data-id="' + c.id + '" title="Establecer como principal"><i class="ri-star-line"></i></button>';
                }
                btns += '<button class="btn-cuenta-action delete btn-eliminar-cuenta" data-id="' + c.id + '" title="Eliminar cuenta"><i class="ri-delete-bin-fill"></i></button>';
                btns += '</div>';

                return '<tr>'
                    + '<td><span style="font-weight:600;font-size:0.85rem;">' + escHtml(c.numero_cuenta) + '</span></td>'
                    + '<td><span class="cuenta-tipo-badge ' + tipoClass + '">' + tipoLabel + '</span></td>'
                    + '<td style="font-size:0.82rem;color:var(--d-body);">' + (c.titular ? escHtml(c.titular) : '<span style="color:var(--d-muted);opacity:0.4;">—</span>') + '</td>'
                    + '<td class="text-center">' + estadoHtml + '</td>'
                    + '<td class="text-center">' + principalHtml + '</td>'
                    + '<td class="text-center">' + btns + '</td>'
                    + '</tr>';
            }).join(''));

            // Bind principal toggle
            $('.btn-set-principal').off('click').on('click', function () {
                const cuentaId = $(this).data('id');
                setBtnLoading(this, true, '<span class="spinner-border spinner-border-sm"></span>');
                $.ajax({
                    url: '/admin/cuentas-bancarias/' + cuentaId + '/principal',
                    type: 'PATCH',
                    data: { _token: CSRF }
                })
                .done(r => {
                    toast('success', r.message || 'Cuenta principal actualizada.');
                    cargarCuentas(window.idBancoCuenta);
                    tabla.ajax.reload();
                })
                .fail(xhr => {
                    const msg = xhr.responseJSON?.message || 'Error al actualizar.';
                    toast('error', msg);
                })
                .always(() => {
                    setBtnLoading(this, false, '<i class="ri-star-line"></i>');
                });
            });
        });
    }

    function agregarCuenta() {
        const numero = $('#numeroCuenta').val().trim();
        const tipo = $('#tipoCuenta').val();
        const titular = $('#titularCuenta').val().trim();
        const ci = $('#ciTitularCuenta').val().trim();
        const principal = $('#esPrincipalCuenta').is(':checked');
        const fb = $('#fbCuenta');
        const idCuenta = $('#idCuentaEditar').val();

        if (!numero) {
            fb.text('El número de cuenta es obligatorio.').addClass('text-danger').removeClass('text-success');
            return;
        }

        fb.text('').removeClass('text-danger text-success');

        const data = {
            _token: CSRF,
            banco_id: window.idBancoCuenta,
            numero_cuenta: numero,
            tipo_cuenta: tipo,
            titular: titular,
            ci_titular: ci,
            es_principal: principal ? 1 : 0
        };

        if (idCuenta) {
            setBtnLoading('#btnAgregarCuenta', true, '<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                url: '/admin/cuentas-bancarias/' + idCuenta,
                type: 'PUT',
                data: data
            })
            .done(r => {
                limpiarFormCuenta();
                fb.text(r.message).addClass('text-success').removeClass('text-danger');
                cargarCuentas(window.idBancoCuenta);
                tabla.ajax.reload();
            })
            .fail(xhr => {
                const msg = xhr.responseJSON?.errors?.numero_cuenta?.[0] || xhr.responseJSON?.message || 'Error al actualizar cuenta.';
                fb.text(msg).addClass('text-danger').removeClass('text-success');
            })
            .always(() => setBtnLoading('#btnAgregarCuenta', false, '<i class="ri-refresh-line"></i> <span id="txtBtnCuenta">Actualizar Cuenta</span>'));
        } else {
            // Agregar cuenta
            setBtnLoading('#btnAgregarCuenta', true, '<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                url: '{{ route("admin.cuentas-bancarias.store") }}',
                type: 'POST',
                data: data
            })
            .done(r => {
                limpiarFormCuenta();
                fb.text(r.message).addClass('text-success').removeClass('text-danger');
                cargarCuentas(window.idBancoCuenta);
                tabla.ajax.reload();
            })
            .fail(xhr => {
                const msg = xhr.responseJSON?.errors?.numero_cuenta?.[0] || xhr.responseJSON?.message || 'Error al agregar cuenta.';
                fb.text(msg).addClass('text-danger').removeClass('text-success');
            })
            .always(() => setBtnLoading('#btnAgregarCuenta', false, '<i class="ri-add-line"></i> <span id="txtBtnCuenta">Agregar Cuenta</span>'));
        }
    }

    function handleAjaxError(xhr, inputId, iconId, fbId, ctx) {
        if (xhr.status === 422) {
            const errs = xhr.responseJSON.errors || {};
            const field = errs.nombre ? 'nombre' : (errs.sigla ? 'sigla' : null);
            if (field) {
                const input = document.getElementById(inputId);
                const icon  = document.getElementById(iconId);
                const fb    = document.getElementById(fbId);
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                icon.className = 'validation-icon invalid';
                icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                fb.className   = 'field-feedback error';
                fb.innerHTML   = '<i class="ri-error-warning-line"></i>' + (errs[field] ? errs[field][0] : 'Error de validación');
            }
        } else {
            toast('error', 'Ocurrió un error al ' + (ctx === 'guardar' ? 'guardar' : 'actualizar') + '. Intente nuevamente.');
        }
    }

    function setBtnLoading(sel, loading, labelHtml) {
        if (typeof sel === 'string') {
            const btn = document.querySelector(sel);
            if (!btn) return;
            btn.disabled = loading;
            if (loading) {
                btn.dataset.orig = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' + labelHtml;
            } else {
                btn.innerHTML = labelHtml;
            }
        } else {
            // jQuery/DOM element
            sel.disabled = loading;
            if (loading) {
                sel.dataset.orig = sel.innerHTML;
                sel.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' + labelHtml;
            } else {
                sel.innerHTML = labelHtml;
            }
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