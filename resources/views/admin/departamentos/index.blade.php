@extends('layouts.master')
@section('title') Departamentos @endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
.num-cell { font-weight: 700; color: var(--d-subtitle); font-size: 0.85rem; font-variant-numeric: tabular-nums; text-align: center; min-width: 45px; }
.badge-ciudades { display: inline-flex; align-items: center; gap: 0.3rem; background: linear-gradient(135deg, rgba(154, 73, 4, 0.12) 0%, rgba(154, 73, 4, 0.06) 100%); color: var(--d-badge-color); border: 1px solid var(--d-badge-border); font-size: 0.75rem; font-weight: 600; padding: 0.35rem 0.75rem; border-radius: 20px; transition: all 0.2s ease; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; }
.badge-ciudades:hover { background: linear-gradient(135deg, rgba(154, 73, 4, 0.2) 0%, rgba(154, 73, 4, 0.1) 100%); max-width: 300px; }
.badge-ciudades i { font-size: 0.7rem; }
.badge-vacio { background: rgba(154, 73, 4, 0.06); border: 1px dashed rgba(154, 73, 4, 0.3); color: var(--d-muted); }
.ciudades-list { max-height: 250px; overflow-y: auto; border: 1px solid var(--d-row-border); border-radius: 10px; }
.ciudad-item { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; border-bottom: 1px solid var(--d-row-border); transition: background 0.15s; }
.ciudad-item:last-child { border-bottom: none; }
.ciudad-item:hover { background: var(--d-row-hover); }
.ciudad-nombre { font-weight: 600; color: var(--d-body); font-size: 0.9rem; }
.ciudad-delete { background: none; border: none; color: #dc3545; cursor: pointer; padding: 0.25rem 0.5rem; border-radius: 6px; transition: all 0.2s; font-size: 1rem; }
.ciudad-delete:hover { background: rgba(220, 53, 69, 0.1); transform: scale(1.1); }
.ciudad-empty { text-align: center; padding: 2rem; color: var(--d-muted); }
.ciudad-empty i { font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5; }
</style>
@endsection

@section('content')
<!-- Hero Header -->
<div class="dept-page-header">
    <div class="container-fluid">
        <div class="dph-inner">
            <!-- Left: icon + title + breadcrumb -->
            <div class="dph-left">
                <div class="dph-icon-wrap">
                    <i class="ri-map-2-line"></i>
                </div>
                <div class="dph-text-block">
                    <h1 class="dph-title">Departamentos</h1>
                    <p class="dph-desc">Gestión y administración de departamentos geográficos</p>
                    <ol class="dph-breadcrumb">
                        <li><i class="ri-home-4-line"></i> Ubicaciones</li>
                        <li class="dph-sep"><i class="ri-arrow-right-s-line"></i></li>
                        <li class="active">Departamentos</li>
                    </ol>
                </div>
            </div>
            <!-- Right: stat + button -->
            <div class="dph-right">
                <div class="dph-stat-card">
                    <div class="dph-stat-icon"><i class="ri-building-line"></i></div>
                    <div>
                        <div class="dph-stat-num" id="stat-total">—</div>
                        <div class="dph-stat-label">Total Registros</div>
                    </div>
                </div>
                <button type="button" class="dph-btn-new" id="btn-nuevo">
                    <i class="ri-add-line"></i>
                    <span>Nuevo Departamento</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Contenido principal -->
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="dept-card">
                <!-- Cabecera del card -->
                <div class="dept-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="dept-header-icon">
                            <i class="ri-table-line"></i>
                        </div>
                        <div>
                            <h5 class="dept-title">Listado de Departamentos</h5>
                            <p class="dept-subtitle">Consulta, edita o elimina los registros existentes</p>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="dept-card-body">
                    <table id="tabla-departamentos" class="dept-table">
                        <thead>
                            <tr>
                                <th>Nombre del Departamento</th>
                                <th style="width:150px;">Ciudades</th>
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
                    <i class="ri-add-circle-line"></i> Nuevo Departamento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formCrear" novalidate autocomplete="off">
                <div class="modal-body">
                    <div class="mb-1">
                        <label for="nombreCrear" class="form-label">
                            <i class="ri-map-pin-line" style="color:#fc7b04;"></i>
                            Nombre del Departamento <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="nombreCrear"
                                   placeholder="Ej: La Paz, Cochabamba…"
                                   maxlength="100" autocomplete="off">
                            <span class="validation-icon" id="iconCrear"></span>
                        </div>
                        <div class="field-feedback" id="fbCrear"></div>
                        <div class="char-hint" id="hintCrear">0 / 100</div>
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
                    <i class="ri-edit-2-line"></i> Editar Departamento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditar" novalidate autocomplete="off">
                <input type="hidden" id="idEditar">
                <div class="modal-body">
                    <div class="mb-1">
                        <label for="nombreEditar" class="form-label">
                            <i class="ri-map-pin-line" style="color:#fc7b04;"></i>
                            Nombre del Departamento <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="nombreEditar"
                                   placeholder="Ej: La Paz, Cochabamba…"
                                   maxlength="100" autocomplete="off">
                            <span class="validation-icon" id="iconEditar"></span>
                        </div>
                        <div class="field-feedback" id="fbEditar"></div>
                        <div class="char-hint" id="hintEditar">0 / 100</div>
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
                    <p class="delete-msg-primary">¿Eliminar departamento?</p>
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

<!-- ===================== MODAL CIUDADES ===================== -->
<div class="modal fade" id="modalCiudades" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:550px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-building-2-line"></i> Ciudades de <span id="deptCiudadesNombre"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nombreCiudad" class="form-label">
                        <i class="ri-map-pin-line" style="color:#fc7b04;"></i>
                        Nueva Ciudad <span class="req">*</span>
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="nombreCiudad"
                               placeholder="Ej: La Paz, Cochabamba…"
                               maxlength="100" autocomplete="off">
                        <input type="hidden" id="idCiudadEditar">
                        <button type="button" class="btn btn-modal-submit" id="btnAgregarCiudad">
                            <i class="ri-add-line"></i> <span id="txtBtnCiudad">Agregar</span>
                        </button>
                        <button type="button" class="btn btn-modal-cancel d-none" id="btnCancelarCiudad">
                            <i class="ri-close-line"></i> Cancelar
                        </button>
                    </div>
                    <div class="field-feedback" id="fbCiudad"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tablaCiudades">
                        <thead>
                            <tr>
                                <th>Ciudad</th>
                                <th class="text-center" style="width:100px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="ciudadesList">
                            <!-- Lista de ciudades -->
                        </tbody>
                    </table>
                </div>
                <div class="ciudad-empty text-center py-4 d-none" id="ciudadEmpty">
                    <i class="ri-building-2-line d-block mb-2" style="font-size: 2rem; opacity: 0.4;"></i>
                    <p class="mb-0 text-muted">No hay ciudades agregadas</p>
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

<!-- ===================== MODAL ELIMINAR CIUDAD ===================== -->
<div class="modal fade" id="modalEliminarCiudad" tabindex="-1" aria-hidden="true">
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
                    <p class="delete-msg-primary">¿Eliminar ciudad?</p>
                    <p class="delete-msg-name">
                        <strong id="nombreEliminarCiudad"></strong>
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
                <button type="button" class="btn btn-danger-modal px-4" id="btnConfirmarEliminarCiudad">
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
    let idEliminarCiudad = null;
    const CSRF = '{{ csrf_token() }}';

    /* ─── INIT ──────────────────────────────────────────────── */
    function init() {
        initDataTable();
        bindEvents();
    }

    /* ─── DATATABLE ─────────────────────────────────────────── */
    function initDataTable() {
        tabla = $('#tabla-departamentos').DataTable({
            ajax: { url: '{{ route("admin.departamentos.listar") }}', dataSrc: 'data' },
            ordering: true,
            columns: [
                {
                    data: 'nombre',
                    render: n => '<span style="font-weight:600;">' + escHtml(n) + '</span>'
                },
                {
                    data: null,
                    render: d => {
                        const ciudades = d.ciudades || [];
                        if (ciudades.length === 0) {
                            return '<span class="badge-ciudades badge-vacio btn-abrir-ciudades" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '"><i class="ri-add-line"></i> Agregar</span>';
                        }
                        return '<span class="badge-ciudades btn-abrir-ciudades" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" title="Click para gestionar ciudades"><i class="ri-building-2-line"></i> ' + ciudades.length + '</span>';
                    }
                },
                {
                    data: null, className: 'text-center',
                    render: d =>
                        '<div class="action-cell">'
                        + '<button class="btn btn-action btn-action-edit btn-accion-editar" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" title="Editar departamento"><i class="ri-pencil-fill"></i></button>'
                        + '<button class="btn btn-action btn-action-delete btn-accion-eliminar" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" title="Eliminar departamento"><i class="ri-delete-bin-fill"></i></button>'
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
            order: [[1, 'asc']],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
            pageLength: 10,
            drawCallback: function () {
                const info = this.api().page.info();
                $('#stat-total').text(info.recordsTotal);
            }
        });
    }

    /* ─── EVENTOS ───────────────────────────────────────────── */
    function bindEvents() {
        /* Abrir modal crear */
        $('#btn-nuevo').on('click', () => {
            resetField('nombreCrear', 'iconCrear', 'fbCrear', 'hintCrear');
            $('#formCrear')[0].reset();
            openModal('modalCrear');
        });

        /* Abrir modal editar */
        $(document).on('click', '.btn-accion-editar', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            $('#idEditar').val(id);
            $('#nombreEditar').val(nombre);
            actualizarHint('nombreEditar', 'hintEditar');
            validar('nombreEditar', 'iconEditar', 'fbEditar');
            openModal('modalEditar');
        });

        /* Abrir modal eliminar */
        $(document).on('click', '.btn-accion-eliminar', function () {
            idEliminar = $(this).data('id');
            $('#nombreEliminar').text($(this).data('nombre'));
            openModal('modalEliminar');
        });

        /* Abrir modal ciudades */
        $(document).on('click', '.btn-abrir-ciudades', function () {
            const deptId = $(this).data('id');
            const deptNombre = $(this).data('nombre');
            $('#deptCiudadesNombre').text(deptNombre);
            $('#nombreCiudad').val('');
            $('#fbCiudad').text('').removeClass('text-danger text-success');
            cargarCiudades(deptId);
            window.idDeptCiudad = deptId;
            openModal('modalCiudades');
        });

        /* Agregar ciudad */
        $('#btnAgregarCiudad').on('click', function () {
            agregarCiudad();
        });

        $('#nombreCiudad').on('keypress', function (e) {
            if (e.which === 13) {
                agregarCiudad();
            }
        });

        /* Eliminar ciudad - abrir modal */
        $(document).on('click', '.btn-eliminar-ciudad', function () {
            idEliminarCiudad = $(this).data('id');
            const nombre = $(this).closest('tr').find('.ciudad-nombre').text();
            $('#nombreEliminarCiudad').text(nombre);
            openModal('modalEliminarCiudad');
        });

        /* Confirmar eliminar ciudad */
        $('#btnConfirmarEliminarCiudad').on('click', function () {
            if (!idEliminarCiudad) return;
            setBtnLoading('#btnConfirmarEliminarCiudad', true, '<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                url: '/admin/departamentos/' + window.idDeptCiudad + '/ciudades/' + idEliminarCiudad,
                type: 'DELETE',
                data: { _token: CSRF }
            })
            .done(r => {
                closeModal('modalEliminarCiudad');
                toast('success', r.message || 'Ciudad eliminada correctamente.');
                cargarCiudades(window.idDeptCiudad);
                tabla.ajax.reload();
            })
            .fail(xhr => {
                const msg = xhr.responseJSON?.message || 'No se pudo eliminar la ciudad.';
                toast(xhr.status === 400 ? 'warning' : 'error', msg);
            })
            .always(() => {
                setBtnLoading('#btnConfirmarEliminarCiudad', false, '<i class="ri-delete-bin-line"></i> Eliminar');
                idEliminarCiudad = null;
            });
        });

        /* Editar ciudad */
        $(document).on('click', '.btn-editar-ciudad', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            $('#idCiudadEditar').val(id);
            $('#nombreCiudad').val(nombre).focus();
            $('#txtBtnCiudad').text('Actualizar');
            $('#btnCancelarCiudad').removeClass('d-none');
            $('#fbCiudad').text('').removeClass('text-danger text-success');
        });

        /* Cancelar edición de ciudad */
        $('#btnCancelarCiudad').on('click', function () {
            $('#idCiudadEditar').val('');
            $('#nombreCiudad').val('');
            $('#btnAgregarCiudad').html('<i class="ri-add-line"></i> <span id="txtBtnCiudad">Agregar</span>');
            $(this).addClass('d-none');
            $('#fbCiudad').text('').removeClass('text-danger text-success');
        });

        /* Submit crear */
        $('#formCrear').on('submit', e => { e.preventDefault(); guardar(); });

        /* Submit editar */
        $('#formEditar').on('submit', e => { e.preventDefault(); actualizar(); });

        /* Confirmar eliminar */
        $('#btnConfirmarEliminar').on('click', confirmarEliminar);

        /* Validación en tiempo real */
        $('#nombreCrear').on('input', function () {
            actualizarHint('nombreCrear', 'hintCrear');
            validar('nombreCrear', 'iconCrear', 'fbCrear');
        });

        $('#nombreEditar').on('input', function () {
            actualizarHint('nombreEditar', 'hintEditar');
            validar('nombreEditar', 'iconEditar', 'fbEditar');
        });

        /* Limpiar al cerrar modal crear */
        document.getElementById('modalCrear').addEventListener('hidden.bs.modal', () => {
            resetField('nombreCrear', 'iconCrear', 'fbCrear', 'hintCrear');
            $('#formCrear')[0].reset();
        });
    }

    /* ─── VALIDACIÓN ────────────────────────────────────────── */
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

        if (!val)                                    return setError('El nombre del departamento es obligatorio.');
        if (val.length < 2)                          return setError('Debe tener al menos 2 caracteres.');
        if (val.length > 100)                        return setError('No puede superar los 100 caracteres.');
        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-']+$/.test(val)) return setError('Solo se permiten letras, espacios y guiones.');

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

    /* ─── CRUD ──────────────────────────────────────────────── */
    function guardar() {
        if (!validar('nombreCrear', 'iconCrear', 'fbCrear')) return;
        setBtnLoading('#btnGuardar', true, 'Guardando…');
        $.post('{{ route("admin.departamentos.guardar") }}', {
            _token: CSRF,
            nombre: $('#nombreCrear').val().trim()
        })
        .done(r => {
            closeModal('modalCrear');
            tabla.ajax.reload();
            toast('success', r.message || 'Departamento guardado correctamente.');
        })
        .fail(xhr => handleAjaxError(xhr, 'nombreCrear', 'iconCrear', 'fbCrear', 'guardar'))
        .always(() => setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar'));
    }

    function actualizar() {
        if (!validar('nombreEditar', 'iconEditar', 'fbEditar')) return;
        const id = $('#idEditar').val();
        setBtnLoading('#btnActualizar', true, 'Actualizando…');
        $.ajax({
            url: '/admin/departamentos/' + id,
            type: 'PUT',
            data: { _token: CSRF, nombre: $('#nombreEditar').val().trim() }
        })
        .done(r => {
            closeModal('modalEditar');
            tabla.ajax.reload();
            toast('success', r.message || 'Departamento actualizado correctamente.');
        })
        .fail(xhr => handleAjaxError(xhr, 'nombreEditar', 'iconEditar', 'fbEditar', 'actualizar'))
        .always(() => setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'));
    }

    function confirmarEliminar() {
        if (!idEliminar) return;
        setBtnLoading('#btnConfirmarEliminar', true, '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
        $.ajax({ url: '/admin/departamentos/' + idEliminar, type: 'DELETE', data: { _token: CSRF } })
        .done(r => {
            closeModal('modalEliminar');
            tabla.ajax.reload();
            toast('success', r.message || 'Departamento eliminado correctamente.');
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

    function cargarCiudades(deptId) {
        $.get('/admin/departamentos/listar')
        .done(r => {
            const dept = r.data.find(d => d.id === deptId);
            const ciudades = dept ? dept.ciudades || [] : [];
            const list = $('#ciudadesList');
            const empty = $('#ciudadEmpty');
            
            if (ciudades.length === 0) {
                list.html('');
                empty.removeClass('d-none');
                return;
            }
            
            empty.addClass('d-none');
            list.html(ciudades.map(c => 
                '<tr>'
                    + '<td class="ciudad-nombre">' + escHtml(c.nombre) + '</td>'
                    + '<td>'
                        + '<div class="d-flex gap-1 justify-content-center">'
                            + '<button class="btn btn-action btn-action-edit btn-editar-ciudad" data-id="' + c.id + '" data-nombre="' + escHtml(c.nombre) + '" title="Editar ciudad"><i class="ri-pencil-fill"></i></button>'
                            + '<button class="btn btn-action btn-action-delete btn-eliminar-ciudad" data-id="' + c.id + '" title="Eliminar ciudad"><i class="ri-delete-bin-fill"></i></button>'
                        + '</div>'
                    + '</td>'
                + '</tr>'
            ).join(''));
        });
    }

    function agregarCiudad() {
        const nombre = $('#nombreCiudad').val().trim();
        const fb = $('#fbCiudad');
        const idCiudad = $('#idCiudadEditar').val();
        
        if (!nombre) {
            fb.text('El nombre de la ciudad es obligatorio.').addClass('text-danger').removeClass('text-success');
            return;
        }
        
        fb.text('').removeClass('text-danger text-success');
        
        if (idCiudad) {
            // Editar ciudad
            setBtnLoading('#btnAgregarCiudad', true, '<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                url: '/admin/departamentos/' + window.idDeptCiudad + '/ciudades/' + idCiudad,
                type: 'PUT',
                data: { _token: CSRF, nombre: nombre }
            })
            .done(r => {
                $('#nombreCiudad').val('');
                $('#idCiudadEditar').val('');
                $('#btnAgregarCiudad').html('<i class="ri-add-line"></i> <span id="txtBtnCiudad">Agregar</span>');
                $('#btnCancelarCiudad').addClass('d-none');
                fb.text(r.message).addClass('text-success').removeClass('text-danger');
                cargarCiudades(window.idDeptCiudad);
                tabla.ajax.reload();
            })
            .fail(xhr => {
                const msg = xhr.responseJSON?.errors?.nombre?.[0] || xhr.responseJSON?.message || 'Error al actualizar ciudad.';
                fb.text(msg).addClass('text-danger').removeClass('text-success');
            })
            .always(() => setBtnLoading('#btnAgregarCiudad', false, '<i class="ri-refresh-line"></i> Actualizar'));
        } else {
            // Agregar ciudad
            setBtnLoading('#btnAgregarCiudad', true, '<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                url: '/admin/departamentos/' + window.idDeptCiudad + '/ciudades',
                type: 'POST',
                data: { _token: CSRF, nombre: nombre }
            })
            .done(r => {
                $('#nombreCiudad').val('');
                fb.text(r.message).addClass('text-success').removeClass('text-danger');
                cargarCiudades(window.idDeptCiudad);
                tabla.ajax.reload();
            })
            .fail(xhr => {
                const msg = xhr.responseJSON?.errors?.nombre?.[0] || xhr.responseJSON?.message || 'Error al agregar ciudad.';
                fb.text(msg).addClass('text-danger').removeClass('text-success');
            })
            .always(() => setBtnLoading('#btnAgregarCiudad', false, '<i class="ri-add-line"></i> <span id="txtBtnCiudad">Agregar</span>'));
        }
    }

    /* ─── HELPERS ───────────────────────────────────────────── */
    function handleAjaxError(xhr, inputId, iconId, fbId, ctx) {
        if (xhr.status === 422) {
            const errs = xhr.responseJSON.errors || {};
            if (errs.nombre) {
                const input = document.getElementById(inputId);
                const icon  = document.getElementById(iconId);
                const fb    = document.getElementById(fbId);
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                icon.className = 'validation-icon invalid';
                icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                fb.className   = 'field-feedback error';
                fb.innerHTML   = '<i class="ri-error-warning-line"></i>' + errs.nombre[0];
            }
        } else {
            toast('error', 'Ocurrió un error al ' + (ctx === 'guardar' ? 'guardar' : 'actualizar') + '. Intente nuevamente.');
        }
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

    /* ─── TOAST ─────────────────────────────────────────────── */
    function getToastContainer() {
        let c = document.getElementById('toastContainer');
        // Mover al <body> si aún está dentro del contenido (para que position:fixed sea relativo al viewport)
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
        
        // Posicionar toast basado en scroll actual con transition suave
        const updatePosition = () => {
            container.style.top = Math.max(20, window.scrollY + 20) + 'px';
        };
        
        // Posición inicial inmediata
        container.style.transition = 'top 0.3s ease';
        updatePosition();
        
        // Actualizar posición al hacer scroll (solo agregar una vez)
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

    /* ─── ARRANQUE ──────────────────────────────────────────── */
    $(document).ready(init);
})();
</script>
@endsection
