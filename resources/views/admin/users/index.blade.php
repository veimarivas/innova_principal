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
                                <th>Estado</th>
                                <th class="text-center" style="width:80px;">Acciones</th>
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
            paging: false,
            info: false,
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
                    data: 'estado',
                    render: e => {
                        if (e === 'activo') {
                            return '<span class="badge-estado badge-activo"><i class="ri-checkbox-circle-fill"></i> Activo</span>';
                        }
                        return '<span class="badge-estado badge-inactivo"><i class="ri-close-circle-fill"></i> Inactivo</span>';
                    }
                },
                {
                    data: null, className: 'text-center',
                    render: d =>
                        '<div class="action-cell">'
                        + '<button class="btn btn-action btn-action-delete btn-accion-eliminar" data-id="' + d.id + '" data-nombre="' + escHtml(d.name) + '" title="Eliminar usuario"><i class="ri-delete-bin-fill"></i></button>'
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
            resetCreateModal();
            openModal('modalCrear');
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
