@extends('layouts.master')
@section('title') Universidades @endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
.badge-sigla { display: inline-flex; align-items: center; justify-content: center; background: var(--d-sigla-bg, rgba(154, 73, 4, 0.08)); color: var(--d-sigla-color, #743c04); border: 1px solid var(--d-sigla-border, rgba(154, 73, 4, 0.16)); font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 6px; letter-spacing: 0.5px; text-transform: uppercase; }
</style>
@endsection

@section('content')
<div class="dept-page-header">
    <div class="container-fluid">
        <div class="dph-inner">
            <div class="dph-left">
                <div class="dph-icon-wrap">
                    <i class="ri-building-2-line"></i>
                </div>
                <div class="dph-text-block">
                    <h1 class="dph-title">Universidades</h1>
                    <p class="dph-desc">Gestión y administración de universidades</p>
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
                    <span>Nueva Universidad</span>
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
                            <h5 class="dept-title">Listado de Universidades</h5>
                            <p class="dept-subtitle">Consulta, edita o elimina los registros existentes</p>
                        </div>
                    </div>
                </div>
                <div class="dept-card-body">
                    <table id="tabla-universidades" class="dept-table">
                        <thead>
                            <tr>
                                <th>Nombre de la Universidad</th>
                                <th>Sigla</th>
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
                    <i class="ri-add-circle-line"></i> Nueva Universidad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formCrear" novalidate autocomplete="off">
                <div class="modal-body">
                    <div class="mb-1">
                        <label for="nombreCrear" class="form-label">
                            <i class="ri-building-2-line" style="color:#fc7b04;"></i>
                            Nombre de la Universidad <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="nombreCrear"
                                   placeholder="Ej: Universidad Mayor de San Andrés…"
                                   maxlength="150" autocomplete="off">
                            <span class="validation-icon" id="iconCrear"></span>
                        </div>
                        <div class="field-feedback" id="fbCrear"></div>
                        <div class="char-hint" id="hintCrear">0 / 150</div>
                    </div>
                    <div class="mb-1">
                        <label for="siglaCrear" class="form-label">
                            <i class="ri-bookmark-line" style="color:#fc7b04;"></i>
                            Sigla
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="siglaCrear"
                                   placeholder="Ej: UMSA"
                                   maxlength="20" autocomplete="off">
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
                    <i class="ri-edit-2-line"></i> Editar Universidad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditar" novalidate autocomplete="off">
                <input type="hidden" id="idEditar">
                <div class="modal-body">
                    <div class="mb-1">
                        <label for="nombreEditar" class="form-label">
                            <i class="ri-building-2-line" style="color:#fc7b04;"></i>
                            Nombre de la Universidad <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="nombreEditar"
                                   placeholder="Ej: Universidad Mayor de San Andrés…"
                                   maxlength="150" autocomplete="off">
                            <span class="validation-icon" id="iconEditar"></span>
                        </div>
                        <div class="field-feedback" id="fbEditar"></div>
                        <div class="char-hint" id="hintEditar">0 / 150</div>
                    </div>
                    <div class="mb-1">
                        <label for="siglaEditar" class="form-label">
                            <i class="ri-bookmark-line" style="color:#fc7b04;"></i>
                            Sigla
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="siglaEditar"
                                   placeholder="Ej: UMSA"
                                   maxlength="20" autocomplete="off">
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
                    <p class="delete-msg-primary">¿Eliminar universidad?</p>
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
        tabla = $('#tabla-universidades').DataTable({
            ajax: { url: '{{ route("admin.universidades.listar") }}', dataSrc: 'data' },
            ordering: true,
            paging: false,
            info: false,
            columns: [
                {
                    data: 'nombre',
                    render: n => '<span style="font-weight:600;">' + escHtml(n) + '</span>'
                },
                {
                    data: 'sigla',
                    render: s => s ? '<span class="badge-sigla">' + escHtml(s) + '</span>' : '<span class="text-muted">-</span>'
                },
                {
                    data: null, className: 'text-center',
                    render: d =>
                        '<div class="action-cell">'
                        + '<button class="btn btn-action btn-action-edit btn-accion-editar" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" data-sigla="' + escHtml(d.sigla || '') + '" title="Editar universidad"><i class="ri-pencil-fill"></i></button>'
                        + '<button class="btn btn-action btn-action-delete btn-accion-eliminar" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" title="Eliminar universidad"><i class="ri-delete-bin-fill"></i></button>'
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
            $('#btnGuardar').prop('disabled', true);
            openModal('modalCrear');
        });

        $(document).on('click', '.btn-accion-editar', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            const sigla = $(this).data('sigla');
            $('#idEditar').val(id);
            $('#nombreEditar').val(nombre);
            $('#siglaEditar').val(sigla || '');
            actualizarHint('nombreEditar', 'hintEditar');
            $('#btnActualizar').prop('disabled', false);
            openModal('modalEditar');
        });

        $(document).on('click', '.btn-accion-eliminar', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            idEliminar = id;
            $('#nombreEliminar').text(nombre);
            openModal('modalEliminar');
        });

        $('#btnConfirmarEliminar').on('click', function () {
            if (!idEliminar) return;
            eliminarUniversidade(idEliminar);
        });

        $('#formCrear').on('submit', e => { e.preventDefault(); guardar(); });
        $('#formEditar').on('submit', e => { e.preventDefault(); actualizar(); });

        $('#btnGuardar').on('click', function() { guardar(); });
        $('#btnActualizar').on('click', function() { actualizar(); });

        $('#nombreCrear').on('input', function () {
            actualizarHint('nombreCrear', 'hintCrear');
            verificarNombre('nombreCrear', 'iconCrear', 'fbCrear', null, '#btnGuardar');
        });

        $('#nombreEditar').on('input', function () {
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

        if (!val)                                    return setError('El nombre de la universidad es obligatorio.');
        if (val.length < 2)                          return setError('Debe tener al menos 2 caracteres.');
        if (val.length > 150)                        return setError('No puede superar los 150 caracteres.');

        return setOk();
    }

    function verificarNombre(inputId, iconId, fbId, idUniversidade, btnId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        const fb    = document.getElementById(fbId);
        const val   = input.value.trim();

        if (val.length < 2) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            icon.className = 'validation-icon invalid';
            icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
            fb.className   = 'field-feedback error';
            fb.innerHTML   = '<i class="ri-error-warning-line"></i>Debe tener al menos 2 caracteres.';
            $(btnId).prop('disabled', true);
            return;
        }

        $.ajax({
            url: '{{ route("admin.universidades.verificar") }}',
            type: 'POST',
            data: { 
                _token: CSRF, 
                nombre: val,
                id: idUniversidade || null
            },
            success: function(r) {
                if (r.existe) {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                    icon.className = 'validation-icon invalid';
                    icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                    fb.className   = 'field-feedback error';
                    fb.innerHTML   = '<i class="ri-error-warning-line"></i>Esta universidad ya existe.';
                    $(btnId).prop('disabled', true);
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                    icon.className = 'validation-icon valid';
                    icon.innerHTML = '<i class="ri-checkbox-circle-fill"></i>';
                    fb.className   = 'field-feedback success';
                    fb.innerHTML   = '<i class="ri-check-line"></i>Nombre disponible';
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
        document.getElementById(fbId).className   = 'field-feedback';
        document.getElementById(fbId).innerHTML   = '';
        if (hintId) {
            document.getElementById(hintId).textContent = '0 / 150';
            document.getElementById(hintId).className = 'char-hint';
        }
    }

    function actualizarHint(inputId, hintId) {
        const len  = document.getElementById(inputId).value.length;
        const hint = document.getElementById(hintId);
        hint.textContent = len + ' / 150';
        hint.className   = 'char-hint' + (len > 140 ? ' warning' : '');
    }

    function guardar() {
        if (!validar('nombreCrear', 'iconCrear', 'fbCrear')) return;
        
        setBtnLoading('#btnGuardar', true, 'Guardando…');
        $.post('{{ route("admin.universidades.guardar") }}', {
            _token: CSRF,
            nombre: $('#nombreCrear').val().trim(),
            sigla:  $('#siglaCrear').val().trim()
        })
        .done(r => {
            closeModal('modalCrear');
            tabla.ajax.reload();
            toast('success', r.message || 'Universidad guardada correctamente.');
        })
        .fail(xhr => handleAjaxError(xhr, 'nombreCrear', 'iconCrear', 'fbCrear', 'guardar'))
        .always(() => setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar'));
    }

    function actualizar() {
        if (!validar('nombreEditar', 'iconEditar', 'fbEditar')) return;
        
        const id = $('#idEditar').val();
        setBtnLoading('#btnActualizar', true, 'Actualizando…');
        $.ajax({
            url: '/admin/universidades/' + id,
            type: 'PUT',
            data: { 
                _token: CSRF, 
                nombre: $('#nombreEditar').val().trim(),
                sigla:  $('#siglaEditar').val().trim()
            }
        })
        .done(r => {
            closeModal('modalEditar');
            tabla.ajax.reload();
            toast('success', r.message || 'Universidad actualizada correctamente.');
        })
        .fail(xhr => handleAjaxError(xhr, 'nombreEditar', 'iconEditar', 'fbEditar', 'actualizar'))
        .always(() => setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'));
    }

    function eliminarUniversidade(id) {
        if (!id) return;
        setBtnLoading('#btnConfirmarEliminar', true, '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
        $.ajax({ 
            url: '/admin/universidades/' + id, 
            type: 'DELETE', 
            data: { _token: CSRF }
        })
        .done(r => {
            closeModal('modalEliminar');
            tabla.ajax.reload();
            toast('success', r.message || 'Universidad eliminada correctamente.');
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
