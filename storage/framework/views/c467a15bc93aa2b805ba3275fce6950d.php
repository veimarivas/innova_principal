<?php $__env->startSection('title'); ?>
    Planes de Pago
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
    .form-toggle { position: relative; width: 48px; height: 24px; }
    .form-toggle input { opacity: 0; width: 0; height: 0; }
    .form-toggle .slider { position: absolute; cursor: pointer; inset: 0; background-color: #ccc; transition: 0.3s; border-radius: 24px; }
    .form-toggle .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: 0.3s; border-radius: 50%; }
    .form-toggle input:checked + .slider { background-color: #28a745; }
    .form-toggle input:checked + .slider:before { transform: translateX(24px); }
    .toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--d-input-border, #e0e0e0); }
    .toggle-row:last-child { border-bottom: none; }
    .toggle-label { font-size: 0.875rem; color: var(--d-body-color, #333); }
    .promo-dates { display: none; margin-top: 12px; padding: 12px; background: rgba(40, 167, 69, 0.08); border-radius: 8px; border: 1px solid rgba(40, 167, 69, 0.2); }
    .promo-dates.show { display: block; }
    .promo-dates .date-row { display: flex; gap: 12px; }
    .promo-dates .date-row > div { flex: 1; }
    .estado-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .estado-habilitado { background: rgba(40, 167, 69, 0.15); color: #28a745; }
    .estado-deshabilitado { background: rgba(108, 117, 125, 0.15); color: #6c757d; }
    .estado-principal { background: rgba(252, 123, 4, 0.15); color: #fc7b04; }
    .estado-promocion { background: rgba(0, 123, 255, 0.15); color: #007bff; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="dept-page-header">
        <div class="container-fluid">
            <div class="dph-inner">
                <div class="dph-left">
                    <div class="dph-icon-wrap">
                        <i class="ri-secure-payment-line"></i>
                    </div>
                    <div class="dph-text-block">
                        <h1 class="dph-title">Planes de Pago</h1>
                        <p class="dph-desc">Gestión y administración de planes de pago</p>
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
                        <span>Nuevo Plan</span>
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
                                <h5 class="dept-title">Listado de Planes de Pago</h5>
                                <p class="dept-subtitle">Consulta, edita o elimina los registros existentes</p>
                            </div>
                        </div>
                    </div>
                    <div class="dept-card-body">
                        <table id="tabla-planes" class="dept-table">
                            <thead>
                                <tr>
                                    <th>Nombre del Plan</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Tipo</th>
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

    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-add-circle-line"></i> Nuevo Plan de Pago
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formCrear" novalidate autocomplete="off">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="nombreCrear" class="form-label">
                                <i class="ri-secure-payment-line" style="color:#fc7b04;"></i>
                                Nombre del Plan <span class="req">*</span>
                            </label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="nombreCrear"
                                    placeholder="Ej: Plan Contado, Plan Cuotas, Plan Promocional…" maxlength="100" autocomplete="off">
                                <span class="validation-icon" id="iconCrear"></span>
                            </div>
                            <div class="field-feedback" id="fbCrear"></div>
                            <div class="char-hint" id="hintCrear">0 / 100</div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label"><i class="ri-settings-3-line" style="color:#fc7b04;"></i> Opciones</label>
                            <div class="toggle-row">
                                <span class="toggle-label">Habilitado</span>
                                <label class="form-toggle">
                                    <input type="checkbox" id="habilitadoCrear" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="toggle-row">
                                <span class="toggle-label">Plan Principal</span>
                                <label class="form-toggle">
                                    <input type="checkbox" id="principalCrear">
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="toggle-row">
                                <span class="toggle-label">Es Promoción</span>
                                <label class="form-toggle" id="togglePromoCrear">
                                    <input type="checkbox" id="esPromocionCrear">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="promo-dates" id="promoDatesCrear">
                            <div class="date-row">
                                <div>
                                    <label for="fechaInicioCrear" class="form-label" style="font-size:0.8rem;">
                                        <i class="ri-calendar-line" style="color:#28a745;"></i> Inicio
                                    </label>
                                    <input type="date" class="form-control" id="fechaInicioCrear">
                                </div>
                                <div>
                                    <label for="fechaFinCrear" class="form-label" style="font-size:0.8rem;">
                                        <i class="ri-calendar-line" style="color:#dc3545;"></i> Fin
                                    </label>
                                    <input type="date" class="form-control" id="fechaFinCrear">
                                </div>
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
        <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-edit-2-line"></i> Editar Plan de Pago
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formEditar" novalidate autocomplete="off">
                    <input type="hidden" id="idEditar">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="nombreEditar" class="form-label">
                                <i class="ri-secure-payment-line" style="color:#fc7b04;"></i>
                                Nombre del Plan <span class="req">*</span>
                            </label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="nombreEditar"
                                    placeholder="Ej: Plan Contado, Plan Cuotas, Plan Promocional…" maxlength="100" autocomplete="off">
                                <span class="validation-icon" id="iconEditar"></span>
                            </div>
                            <div class="field-feedback" id="fbEditar"></div>
                            <div class="char-hint" id="hintEditar">0 / 100</div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label"><i class="ri-settings-3-line" style="color:#fc7b04;"></i> Opciones</label>
                            <div class="toggle-row">
                                <span class="toggle-label">Habilitado</span>
                                <label class="form-toggle">
                                    <input type="checkbox" id="habilitadoEditar">
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="toggle-row">
                                <span class="toggle-label">Plan Principal</span>
                                <label class="form-toggle">
                                    <input type="checkbox" id="principalEditar">
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="toggle-row">
                                <span class="toggle-label">Es Promoción</span>
                                <label class="form-toggle" id="togglePromoEditar">
                                    <input type="checkbox" id="esPromocionEditar">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="promo-dates" id="promoDatesEditar">
                            <div class="date-row">
                                <div>
                                    <label for="fechaInicioEditar" class="form-label" style="font-size:0.8rem;">
                                        <i class="ri-calendar-line" style="color:#28a745;"></i> Inicio
                                    </label>
                                    <input type="date" class="form-control" id="fechaInicioEditar">
                                </div>
                                <div>
                                    <label for="fechaFinEditar" class="form-label" style="font-size:0.8rem;">
                                        <i class="ri-calendar-line" style="color:#dc3545;"></i> Fin
                                    </label>
                                    <input type="date" class="form-control" id="fechaFinEditar">
                                </div>
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
                        <p class="delete-msg-primary">¿Eliminar plan de pago?</p>
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
                tabla = $('#tabla-planes').DataTable({
                    ajax: {
                        url: '<?php echo e(route('admin.planes-pagos.listar')); ?>',
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
                            data: 'habilitado',
                            className: 'text-center',
                            render: d => d
                                ? '<span class="estado-badge estado-habilitado"><i class="ri-check-line me-1"></i>Habilitado</span>'
                                : '<span class="estado-badge estado-deshabilitado"><i class="ri-close-line me-1"></i>Deshabilitado</span>'
                        },
                        {
                            data: null,
                            className: 'text-center',
                            render: d => {
                                let badges = [];
                                if (d.principal) badges.push('<span class="estado-badge estado-principal"><i class="ri-star-line me-1"></i>Principal</span>');
                                if (d.es_promocion) badges.push('<span class="estado-badge estado-promocion"><i class="ri-percent-line me-1"></i>Promoción</span>');
                                return badges.length ? badges.join(' ') : '<span class="text-muted">—</span>';
                            }
                        },
                        {
                            data: null,
                            className: 'text-center',
                            render: d =>
                                '<div class="action-cell">' +
                                '<button class="btn btn-action btn-action-edit btn-accion-editar" data-id="' +
                                d.id + '" data-nombre="' + escHtml(d.nombre) +
                                '" data-habilitado="' + (d.habilitado ? '1' : '0') +
                                '" data-principal="' + (d.principal ? '1' : '0') +
                                '" data-es-promocion="' + (d.es_promocion ? '1' : '0') +
                                '" data-fecha-inicio="' + (d.fecha_inicio_promocion || '') +
                                '" data-fecha-fin="' + (d.fecha_fin_promocion || '') +
                                '" title="Editar plan"><i class="ri-pencil-fill"></i></button>' +
                                '<button class="btn btn-action btn-action-delete btn-accion-eliminar" data-id="' +
                                d.id + '" data-nombre="' + escHtml(d.nombre) +
                                '" title="Eliminar plan"><i class="ri-delete-bin-fill"></i></button>' +
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
                    $('#habilitadoCrear').prop('checked', true);
                    $('#principalCrear').prop('checked', false);
                    $('#esPromocionCrear').prop('checked', false);
                    $('#promoDatesCrear').removeClass('show');
                    $('#btnGuardar').prop('disabled', true);
                    openModal('modalCrear');
                });

                $(document).on('click', '.btn-accion-editar', function() {
                    const id = $(this).data('id');
                    $('#idEditar').val(id);
                    $('#nombreEditar').val($(this).data('nombre'));
                    $('#habilitadoEditar').prop('checked', $(this).data('habilitado') == '1');
                    $('#principalEditar').prop('checked', $(this).data('principal') == '1');
                    $('#esPromocionEditar').prop('checked', $(this).data('es-promocion') == '1');
                    $('#fechaInicioEditar').val($(this).data('fecha-inicio'));
                    $('#fechaFinEditar').val($(this).data('fecha-fin'));
                    if ($(this).data('es-promocion') == '1') {
                        $('#promoDatesEditar').addClass('show');
                    } else {
                        $('#promoDatesEditar').removeClass('show');
                    }
                    actualizarHint('nombreEditar', 'hintEditar');
                    $('#btnActualizar').prop('disabled', false);
                    openModal('modalEditar');
                });

                $(document).on('click', '.btn-accion-eliminar', function() {
                    idEliminar = $(this).data('id');
                    $('#nombreEliminar').text($(this).data('nombre'));
                    openModal('modalEliminar');
                });

                $('#btnConfirmarEliminar').on('click', function() {
                    if (!idEliminar) return;
                    eliminarPlan(idEliminar);
                });

                $('#formCrear').on('submit', e => { e.preventDefault(); guardar(); });
                $('#formEditar').on('submit', e => { e.preventDefault(); actualizar(); });
                $('#btnGuardar').on('click', function() { guardar(); });
                $('#btnActualizar').on('click', function() { actualizar(); });

                $('#nombreCrear').on('input', function() {
                    actualizarHint('nombreCrear', 'hintCrear');
                    verificarNombre('nombreCrear', 'iconCrear', 'fbCrear', null, '#btnGuardar');
                });

                $('#nombreEditar').on('input', function() {
                    actualizarHint('nombreEditar', 'hintEditar');
                    const id = $('#idEditar').val();
                    verificarNombre('nombreEditar', 'iconEditar', 'fbEditar', id, '#btnActualizar');
                });

                $('#esPromocionCrear').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#promoDatesCrear').addClass('show');
                    } else {
                        $('#promoDatesCrear').removeClass('show');
                        $('#fechaInicioCrear').val('');
                        $('#fechaFinCrear').val('');
                    }
                });

                $('#esPromocionEditar').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#promoDatesEditar').addClass('show');
                    } else {
                        $('#promoDatesEditar').removeClass('show');
                        $('#fechaInicioEditar').val('');
                        $('#fechaFinEditar').val('');
                    }
                });

                document.getElementById('modalCrear').addEventListener('hidden.bs.modal', () => {
                    resetField('nombreCrear', 'iconCrear', 'fbCrear', 'hintCrear');
                    $('#formCrear')[0].reset();
                    $('#promoDatesCrear').removeClass('show');
                    $('#btnGuardar').prop('disabled', true);
                });
            }

            function verificarNombre(inputId, iconId, fbId, idPlan, btnId) {
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

                if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-']+$/.test(val)) {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                    icon.className = 'validation-icon invalid';
                    icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                    fb.className = 'field-feedback error';
                    fb.innerHTML = '<i class="ri-error-warning-line"></i>Solo se permiten letras, espacios y guiones.';
                    $(btnId).prop('disabled', true);
                    return;
                }

                $.ajax({
                    url: '<?php echo e(route('admin.planes-pagos.verificar')); ?>',
                    type: 'POST',
                    data: {
                        _token: CSRF,
                        nombre: val,
                        id: idPlan || null
                    },
                    success: function(r) {
                        if (r.existe) {
                            input.classList.remove('is-valid');
                            input.classList.add('is-invalid');
                            icon.className = 'validation-icon invalid';
                            icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                            fb.className = 'field-feedback error';
                            fb.innerHTML = '<i class="ri-error-warning-line"></i>Este plan ya existe.';
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
                    document.getElementById(hintId).textContent = '0 / 100';
                    document.getElementById(hintId).className = 'char-hint';
                }
            }

            function actualizarHint(inputId, hintId) {
                const len = document.getElementById(inputId).value.length;
                const hint = document.getElementById(hintId);
                hint.textContent = len + ' / 100';
                hint.className = 'char-hint' + (len > 90 ? ' warning' : '');
            }

            function guardar() {
                if ($('#btnGuardar').prop('disabled')) return;
                setBtnLoading('#btnGuardar', true, 'Guardando…');
                $.post('<?php echo e(route('admin.planes-pagos.guardar')); ?>', {
                        _token: CSRF,
                        nombre: $('#nombreCrear').val().trim(),
                        habilitado: $('#habilitadoCrear').is(':checked'),
                        principal: $('#principalCrear').is(':checked'),
                        es_promocion: $('#esPromocionCrear').is(':checked'),
                        fecha_inicio_promocion: $('#fechaInicioCrear').val() || null,
                        fecha_fin_promocion: $('#fechaFinCrear').val() || null
                    })
                    .done(r => {
                        closeModal('modalCrear');
                        tabla.ajax.reload();
                        toast('success', r.message || 'Plan de pago guardado correctamente.');
                    })
                    .fail(xhr => handleAjaxError(xhr, 'nombreCrear', 'iconCrear', 'fbCrear', 'guardar'))
                    .always(() => setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar'));
            }

            function actualizar() {
                if ($('#btnActualizar').prop('disabled')) return;
                const id = $('#idEditar').val();
                setBtnLoading('#btnActualizar', true, 'Actualizando…');
                $.ajax({
                        url: '/admin/planes-pagos/' + id,
                        type: 'PUT',
                        data: {
                            _token: CSRF,
                            nombre: $('#nombreEditar').val().trim(),
                            habilitado: $('#habilitadoEditar').is(':checked'),
                            principal: $('#principalEditar').is(':checked'),
                            es_promocion: $('#esPromocionEditar').is(':checked'),
                            fecha_inicio_promocion: $('#fechaInicioEditar').val() || null,
                            fecha_fin_promocion: $('#fechaFinEditar').val() || null
                        }
                    })
                    .done(r => {
                        closeModal('modalEditar');
                        tabla.ajax.reload();
                        toast('success', r.message || 'Plan de pago actualizado correctamente.');
                    })
                    .fail(xhr => handleAjaxError(xhr, 'nombreEditar', 'iconEditar', 'fbEditar', 'actualizar'))
                    .always(() => setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'));
            }

            function eliminarPlan(id) {
                if (!id) return;
                setBtnLoading('#btnConfirmarEliminar', true,
                    '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
                $.ajax({
                        url: '/admin/planes-pagos/' + id,
                        type: 'DELETE',
                        data: { _token: CSRF }
                    })
                    .done(r => {
                        closeModal('modalEliminar');
                        tabla.ajax.reload();
                        toast('success', r.message || 'Plan de pago eliminado correctamente.');
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
                const m = bootstrap.Modal.getInstance(el);
                if (m) m.hide();
            }

            function escHtml(str) {
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }

            function getToastContainer() {
                let c = document.getElementById('toastContainer');
                if (c && c.parentElement !== document.body) { document.body.appendChild(c); }
                return c;
            }

            function toast(tipo, mensaje) {
                const iconMap = { success: 'ri-check-double-line', error: 'ri-close-circle-line', warning: 'ri-alert-line' };
                const el = document.createElement('div');
                el.className = 'toast-notify ' + tipo;
                el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') + '"></i></div>' +
                    '<div class="toast-body-text"><span>' + mensaje + '</span></div>' +
                    '<button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';
                const container = getToastContainer();
                const updatePosition = () => { container.style.top = Math.max(20, window.scrollY + 20) + 'px'; };
                container.style.transition = 'top 0.3s ease';
                updatePosition();
                if (!container._scrollListenerAttached) {
                    container._scrollListenerAttached = true;
                    let scrollTimeout;
                    window.addEventListener('scroll', () => { clearTimeout(scrollTimeout); scrollTimeout = setTimeout(updatePosition, 10); });
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/planes-pagos/index.blade.php ENDPATH**/ ?>