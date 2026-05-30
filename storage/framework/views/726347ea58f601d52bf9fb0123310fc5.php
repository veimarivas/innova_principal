<?php $__env->startSection('title'); ?>
    Cuentas de Videollamada
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
    .btn-action-view {
        background: rgba(61,90,241,0.08);
        border: 1px solid rgba(61,90,241,0.2);
        color: #3d5af1;
    }
    .btn-action-view:hover {
        background: #3d5af1;
        color: #fff;
        border-color: #3d5af1;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="dept-page-header">
        <div class="container-fluid">
            <div class="dph-inner">
                <div class="dph-left">
                    <div class="dph-icon-wrap">
                        <i class="ri-video-on-line"></i>
                    </div>
                    <div class="dph-text-block">
                        <h1 class="dph-title">Cuentas de Videollamada</h1>
                        <p class="dph-desc">Gestión de cuentas y plataformas de videollamadas</p>
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
                    <button type="button" class="dph-btn-new" id="btn-nuevo">
                        <i class="ri-add-line"></i>
                        <span>Nueva Cuenta</span>
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
                                <h5 class="dept-title">Listado de Cuentas de Videollamada</h5>
                                <p class="dept-subtitle">Consulta, edita o elimina los registros existentes</p>
                            </div>
                        </div>
                    </div>
                    <div class="dept-card-body">
                        <table id="tabla-cuentas" class="dept-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Plataforma</th>
                                    <th class="text-center" style="width:100px;">Estado</th>
                                    <th class="text-center" style="width:145px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-add-circle-line"></i> Nueva Cuenta de Videollamada
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formCrear" novalidate autocomplete="off">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombreCrear" class="form-label">
                                <i class="ri-video-on-line" style="color:#fc7b04;"></i>
                                Nombre de la Cuenta <span class="req">*</span>
                            </label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="nombreCrear"
                                    placeholder="Ej: Zoom Corporativo, Meet Principal…" maxlength="200" autocomplete="off">
                                <span class="validation-icon" id="iconCrear"></span>
                            </div>
                            <div class="field-feedback" id="fbCrear"></div>
                            <div class="char-hint" id="hintCrear">0 / 200</div>
                        </div>
                        <div class="mb-3">
                            <label for="plataformaCrear" class="form-label">
                                <i class="ri-global-line" style="color:#fc7b04;"></i>
                                Plataforma <span class="req">*</span>
                            </label>
                            <select class="form-select" id="plataformaCrear">
                                <option value="">Seleccione una plataforma…</option>
                                <option value="Zoom">Zoom</option>
                                <option value="Google Meet">Google Meet</option>
                                <option value="Microsoft Teams">Microsoft Teams</option>
                                <option value="Jitsi">Jitsi</option>
                                <option value="Cisco Webex">Cisco Webex</option>
                                <option value="Otro">Otro</option>
                            </select>
                            <div class="field-feedback" id="fbPlataformaCrear"></div>
                        </div>
                        <div class="mb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="activoCrear" checked>
                                <label class="form-check-label" for="activoCrear">Cuenta activa</label>
                            </div>
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
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-edit-2-line"></i> Editar Cuenta de Videollamada
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formEditar" novalidate autocomplete="off">
                    <input type="hidden" id="idEditar">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombreEditar" class="form-label">
                                <i class="ri-video-on-line" style="color:#fc7b04;"></i>
                                Nombre de la Cuenta <span class="req">*</span>
                            </label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="nombreEditar"
                                    placeholder="Ej: Zoom Corporativo, Meet Principal…" maxlength="200" autocomplete="off">
                                <span class="validation-icon" id="iconEditar"></span>
                            </div>
                            <div class="field-feedback" id="fbEditar"></div>
                            <div class="char-hint" id="hintEditar">0 / 200</div>
                        </div>
                        <div class="mb-3">
                            <label for="plataformaEditar" class="form-label">
                                <i class="ri-global-line" style="color:#fc7b04;"></i>
                                Plataforma <span class="req">*</span>
                            </label>
                            <select class="form-select" id="plataformaEditar">
                                <option value="">Seleccione una plataforma…</option>
                                <option value="Zoom">Zoom</option>
                                <option value="Google Meet">Google Meet</option>
                                <option value="Microsoft Teams">Microsoft Teams</option>
                                <option value="Jitsi">Jitsi</option>
                                <option value="Cisco Webex">Cisco Webex</option>
                                <option value="Otro">Otro</option>
                            </select>
                            <div class="field-feedback" id="fbPlataformaEditar"></div>
                        </div>
                        <div class="mb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="activoEditar" checked>
                                <label class="form-check-label" for="activoEditar">Cuenta activa</label>
                            </div>
                        </div>
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
                        <p class="delete-msg-primary">¿Eliminar cuenta de videollamada?</p>
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

    <div id="toastContainer" class="toast-container"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        (function() {
            'use strict';

            let tabla;
            let idEliminar = null;
            const CSRF = '<?php echo e(csrf_token()); ?>';

            function init() {
                initDataTable();
                bindEvents();
            }

            function initDataTable() {
                tabla = $('#tabla-cuentas').DataTable({
                    ajax: {
                        url: '<?php echo e(route('admin.cuentas-videollamada.listar')); ?>',
                        dataSrc: 'data'
                    },
                    ordering: true,
                    paging: false,
                    info: false,
                    columns: [{
                            data: 'nombre',
                            render: n => '<span style="font-weight:600;">' + escHtml(n) + '</span>'
                        },
                        {
                            data: 'plataforma',
                            render: p => {
                                const iconMap = {
                                    'Zoom': 'ri-video-on-line',
                                    'Google Meet': 'ri-google-line',
                                    'Microsoft Teams': 'ri-microsoft-line',
                                    'Jitsi': 'ri-code-s-slash-line',
                                    'Cisco Webex': 'ri-global-line',
                                    'Otro': 'ri-link'
                                };
                                const icon = iconMap[p] || 'ri-video-line';
                                return '<span><i class="' + icon + ' me-1"></i>' + escHtml(p) + '</span>';
                            }
                        },
                        {
                            data: 'activo',
                            className: 'text-center',
                            render: a => a
                                ? '<span class="badge bg-success">Activo</span>'
                                : '<span class="badge bg-secondary">Inactivo</span>'
                        },
                        {
                            data: null,
                            className: 'text-center',
                            render: d =>
                                '<div class="action-cell">' +
                                '<a class="btn btn-action btn-action-view" href="/admin/cuentas-videollamada/' +
                                d.id + '/ver" title="Ver detalles"><i class="ri-eye-line"></i></a>' +
                                '<button class="btn btn-action btn-action-edit btn-accion-editar" data-id="' +
                                d.id + '" data-nombre="' + escHtml(d.nombre) +
                                '" data-plataforma="' + escHtml(d.plataforma) +
                                '" data-activo="' + d.activo +
                                '" title="Editar cuenta"><i class="ri-pencil-fill"></i></button>' +
                                '<button class="btn btn-action btn-action-delete btn-accion-eliminar" data-id="' +
                                d.id + '" data-nombre="' + escHtml(d.nombre) +
                                '" title="Eliminar cuenta"><i class="ri-delete-bin-fill"></i></button>' +
                                '</div>'
                        }
                    ],
                    language: {
                        processing: 'Procesando...',
                        search: 'Buscar:',
                        lengthMenu: 'Mostrar _MENU_ registros',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                        infoEmpty: 'Mostrando 0 a 0 de 0 registros',
                        infoFiltered: '(filtrado de _MAX_ registros totales)',
                        infoPostFix: '',
                        loadingRecords: 'Cargando...',
                        zeroRecords: 'No se encontraron registros',
                        emptyTable: 'No hay datos disponibles',
                        paginate: {
                            first: 'Primero',
                            previous: 'Anterior',
                            next: 'Siguiente',
                            last: 'Último'
                        },
                        aria: {
                            sortAscending: ': activar para ordenar ascendente',
                            sortDescending: ': activar para ordenar descendente'
                        }
                    },
                    order: [
                        [0, 'asc']
                    ],
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, 'Todos']
                    ],
                    pageLength: 10,
                    drawCallback: function() {
                        const api = this.api();
                        const recordsTotal = api.rows().data().length;

                        $('#stat-total').text(recordsTotal);

                        if (recordsTotal > 10) {
                            api.page('first').draw(false);
                            $('.dataTables_paginate').show();
                            $('.dataTables_length').show();
                        } else {
                            $('.dataTables_paginate').hide();
                            $('.dataTables_length').hide();
                        }
                    }
                });
            }

            function bindEvents() {
                $('#btn-nuevo').on('click', () => {
                    resetField('nombreCrear', 'iconCrear', 'fbCrear', 'hintCrear');
                    $('#formCrear')[0].reset();
                    $('#plataformaCrear').val('');
                    $('#activoCrear').prop('checked', true);
                    $('#btnGuardar').prop('disabled', true);
                    resetPlataformaFeedback('fbPlataformaCrear');
                    openModal('modalCrear');
                });

                $(document).on('click', '.btn-accion-editar', function() {
                    const id = $(this).data('id');
                    const nombre = $(this).data('nombre');
                    const plataforma = $(this).data('plataforma');
                    const activo = $(this).data('activo');
                    $('#idEditar').val(id);
                    $('#nombreEditar').val(nombre);
                    $('#plataformaEditar').val(plataforma);
                    $('#activoEditar').prop('checked', activo === true || activo === '1' || activo === 1);
                    actualizarHint('nombreEditar', 'hintEditar');
                    $('#btnActualizar').prop('disabled', false);
                    resetPlataformaFeedback('fbPlataformaEditar');
                    openModal('modalEditar');
                });

                $(document).on('click', '.btn-accion-eliminar', function() {
                    const id = $(this).data('id');
                    const nombre = $(this).data('nombre');
                    idEliminar = id;
                    $('#nombreEliminar').text(nombre);
                    openModal('modalEliminar');
                });

                $('#btnConfirmarEliminar').on('click', function() {
                    if (!idEliminar) return;
                    eliminarCuenta(idEliminar);
                });

                $('#formCrear').on('submit', e => {
                    e.preventDefault();
                    guardar();
                });
                $('#formEditar').on('submit', e => {
                    e.preventDefault();
                    actualizar();
                });

                $('#btnGuardar').on('click', function() {
                    guardar();
                });
                $('#btnActualizar').on('click', function() {
                    actualizar();
                });

                $('#nombreCrear').on('input', function() {
                    actualizarHint('nombreCrear', 'hintCrear');
                    verificarNombre('nombreCrear', 'iconCrear', 'fbCrear', null, '#btnGuardar');
                });

                $('#nombreEditar').on('input', function() {
                    actualizarHint('nombreEditar', 'hintEditar');
                    const id = $('#idEditar').val();
                    verificarNombre('nombreEditar', 'iconEditar', 'fbEditar', id, '#btnActualizar');
                });

                document.getElementById('modalCrear').addEventListener('hidden.bs.modal', () => {
                    resetField('nombreCrear', 'iconCrear', 'fbCrear', 'hintCrear');
                    $('#formCrear')[0].reset();
                    $('#btnGuardar').prop('disabled', true);
                });
            }

            function validar(inputId, iconId, fbId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                const fb = document.getElementById(fbId);
                const val = input.value.trim();

                const setError = msg => {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                    icon.className = 'validation-icon invalid';
                    icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                    fb.className = 'field-feedback error';
                    fb.innerHTML = '<i class="ri-error-warning-line"></i>' + msg;
                    return false;
                };

                const setOk = () => {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                    icon.className = 'validation-icon valid';
                    icon.innerHTML = '<i class="ri-checkbox-circle-fill"></i>';
                    fb.className = 'field-feedback success';
                    fb.innerHTML = '<i class="ri-check-line"></i>Nombre válido';
                    return true;
                };

                if (!val) return setError('El nombre de la cuenta es obligatorio.');
                if (val.length < 2) return setError('Debe tener al menos 2 caracteres.');
                if (val.length > 200) return setError('No puede superar los 200 caracteres.');

                return setOk();
            }

            function validarPlataforma(plataformaId, fbId) {
                const val = document.getElementById(plataformaId).value;
                const fb = document.getElementById(fbId);
                if (!val) {
                    fb.className = 'field-feedback error';
                    fb.innerHTML = '<i class="ri-error-warning-line"></i>Seleccione una plataforma.';
                    return false;
                }
                fb.className = 'field-feedback success';
                fb.innerHTML = '<i class="ri-check-line"></i>Plataforma válida.';
                return true;
            }

            function resetPlataformaFeedback(fbId) {
                const fb = document.getElementById(fbId);
                fb.className = 'field-feedback';
                fb.innerHTML = '';
            }

            function verificarNombre(inputId, iconId, fbId, idCuenta, btnId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                const fb = document.getElementById(fbId);
                const val = input.value.trim();

                if (val.length < 2) {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                    icon.className = 'validation-icon invalid';
                    icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                    fb.className = 'field-feedback error';
                    fb.innerHTML = '<i class="ri-error-warning-line"></i>Debe tener al menos 2 caracteres.';
                    $(btnId).prop('disabled', true);
                    return;
                }

                $.ajax({
                    url: '<?php echo e(route('admin.cuentas-videollamada.verificar')); ?>',
                    type: 'POST',
                    data: {
                        _token: CSRF,
                        nombre: val,
                        id: idCuenta || null
                    },
                    success: function(r) {
                        if (r.existe) {
                            input.classList.remove('is-valid');
                            input.classList.add('is-invalid');
                            icon.className = 'validation-icon invalid';
                            icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                            fb.className = 'field-feedback error';
                            fb.innerHTML = '<i class="ri-error-warning-line"></i>Esta cuenta ya existe.';
                            $(btnId).prop('disabled', true);
                        } else {
                            input.classList.remove('is-invalid');
                            input.classList.add('is-valid');
                            icon.className = 'validation-icon valid';
                            icon.innerHTML = '<i class="ri-checkbox-circle-fill"></i>';
                            fb.className = 'field-feedback success';
                            fb.innerHTML = '<i class="ri-check-line"></i>Nombre disponible';
                            $(btnId).prop('disabled', false);
                        }
                    },
                    error: function() {
                        $(btnId).prop('disabled', true);
                    }
                });
            }

            function resetField(inputId, iconId, fbId, hintId) {
                const input = document.getElementById(inputId);
                input.classList.remove('is-valid', 'is-invalid');
                document.getElementById(iconId).className = 'validation-icon';
                document.getElementById(iconId).innerHTML = '';
                document.getElementById(fbId).className = 'field-feedback';
                document.getElementById(fbId).innerHTML = '';
                if (hintId) {
                    document.getElementById(hintId).textContent = '0 / 200';
                    document.getElementById(hintId).className = 'char-hint';
                }
            }

            function actualizarHint(inputId, hintId) {
                const len = document.getElementById(inputId).value.length;
                const hint = document.getElementById(hintId);
                hint.textContent = len + ' / 200';
                hint.className = 'char-hint' + (len > 180 ? ' warning' : '');
            }

            function guardar() {
                if (!validar('nombreCrear', 'iconCrear', 'fbCrear')) return;
                if (!validarPlataforma('plataformaCrear', 'fbPlataformaCrear')) return;

                setBtnLoading('#btnGuardar', true, 'Guardando…');
                $.post('<?php echo e(route('admin.cuentas-videollamada.guardar')); ?>', {
                        _token: CSRF,
                        nombre: $('#nombreCrear').val().trim(),
                        plataforma: $('#plataformaCrear').val(),
                        activo: $('#activoCrear').is(':checked') ? 1 : 0
                    })
                    .done(r => {
                        closeModal('modalCrear');
                        tabla.ajax.reload();
                        toast('success', r.message || 'Cuenta guardada correctamente.');
                    })
                    .fail(xhr => handleAjaxError(xhr, 'nombreCrear', 'iconCrear', 'fbCrear', 'guardar'))
                    .always(() => setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar'));
            }

            function actualizar() {
                if (!validar('nombreEditar', 'iconEditar', 'fbEditar')) return;
                if (!validarPlataforma('plataformaEditar', 'fbPlataformaEditar')) return;

                const id = $('#idEditar').val();
                setBtnLoading('#btnActualizar', true, 'Actualizando…');
                $.ajax({
                        url: '/admin/cuentas-videollamada/' + id,
                        type: 'PUT',
                        data: {
                            _token: CSRF,
                            nombre: $('#nombreEditar').val().trim(),
                            plataforma: $('#plataformaEditar').val(),
                            activo: $('#activoEditar').is(':checked') ? 1 : 0
                        }
                    })
                    .done(r => {
                        closeModal('modalEditar');
                        tabla.ajax.reload();
                        toast('success', r.message || 'Cuenta actualizada correctamente.');
                    })
                    .fail(xhr => handleAjaxError(xhr, 'nombreEditar', 'iconEditar', 'fbEditar', 'actualizar'))
                    .always(() => setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'));
            }

            function eliminarCuenta(id) {
                if (!id) return;
                setBtnLoading('#btnConfirmarEliminar', true,
                    '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
                $.ajax({
                        url: '/admin/cuentas-videollamada/' + id,
                        type: 'DELETE',
                        data: {
                            _token: CSRF
                        }
                    })
                    .done(r => {
                        closeModal('modalEliminar');
                        tabla.ajax.reload();
                        toast('success', r.message || 'Cuenta eliminada correctamente.');
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

            function handleAjaxError(xhr, inputId, iconId, fbId, ctx) {
                if (xhr.status === 422) {
                    const errs = xhr.responseJSON.errors || {};
                    if (errs.nombre) {
                        const input = document.getElementById(inputId);
                        const icon = document.getElementById(iconId);
                        const fb = document.getElementById(fbId);
                        input.classList.remove('is-valid');
                        input.classList.add('is-invalid');
                        icon.className = 'validation-icon invalid';
                        icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                        fb.className = 'field-feedback error';
                        fb.innerHTML = '<i class="ri-error-warning-line"></i>' + errs.nombre[0];
                    }
                } else {
                    toast('error', 'Ocurrió un error al ' + (ctx === 'guardar' ? 'guardar' : 'actualizar') +
                        '. Intente nuevamente.');
                }
            }

            function setBtnLoading(sel, loading, labelHtml) {
                const btn = document.querySelector(sel);
                if (!btn) return;
                btn.disabled = loading;
                if (loading) {
                    btn.dataset.orig = btn.innerHTML;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' +
                        labelHtml;
                } else {
                    btn.innerHTML = labelHtml;
                }
            }

            function openModal(id) {
                new bootstrap.Modal(document.getElementById(id)).show();
            }

            function closeModal(id) {
                const el = document.getElementById(id);
                const m = bootstrap.Modal.getInstance(el);
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
                const iconMap = {
                    success: 'ri-check-double-line',
                    error: 'ri-close-circle-line',
                    warning: 'ri-alert-line'
                };
                const el = document.createElement('div');
                el.className = 'toast-notify ' + tipo;
                el.innerHTML =
                    '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') + '"></i></div>' +
                    '<div class="toast-body-text"><span>' + mensaje + '</span></div>' +
                    '<button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';

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
                el.addEventListener('animationend', () => el.remove(), {
                    once: true
                });
            }

            $(document).ready(init);
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/cuentas-videollamada/index.blade.php ENDPATH**/ ?>