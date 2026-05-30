@extends('layouts.master')
@section('title') Fases @endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
.color-dot { display: inline-block; width: 24px; height: 24px; border-radius: 6px; border: 2px solid #e2e8f0; }
.color-dot.color-none { background: #f1f5f9; background-image: repeating-linear-gradient(45deg, #e2e8f0 0, #e2e8f0 2px, transparent 2px, transparent 4px); }
.color-picker-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px; margin-top: 8px; }
.color-option { width: 32px; height: 32px; border-radius: 6px; cursor: pointer; border: 2px solid transparent; transition: all 0.2s; }
.color-option:hover { transform: scale(1.1); }
.color-option.selected { border-color: #1e293b; box-shadow: 0 0 0 2px rgba(30,41,59,0.3); }
</style>
@endsection

@section('content')
<div class="dept-page-header">
    <div class="container-fluid">
        <div class="dph-inner">
            <div class="dph-left">
                <div class="dph-icon-wrap">
                    <i class="ri-flashlight-line"></i>
                </div>
                <div class="dph-text-block">
                    <h1 class="dph-title">Fases</h1>
                    <p class="dph-desc">Gestión y administración de fases del programa</p>
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
                    <span>Nueva Fase</span>
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
                            <h5 class="dept-title">Listado de Fases</h5>
                            <p class="dept-subtitle">Consulta, edita o elimina los registros existentes</p>
                        </div>
                    </div>
                </div>
                <div class="dept-card-body">
                    <table id="tabla-fases" class="dept-table">
                        <thead>
                            <tr>
                                <th style="width:80px;">N° Fase</th>
                                <th>Nombre de la Fase</th>
                                <th class="text-center" style="width:100px;">Color</th>
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
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-add-circle-line"></i> Nueva Fase
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formCrear" novalidate autocomplete="off">
                <div class="modal-body">
                    <div class="mb-1">
                        <label for="nFaseCrear" class="form-label">
                            <i class="ri-hashtag" style="color:#fc7b04;"></i>
                            Número de Fase <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="number" class="form-control" id="nFaseCrear"
                                   placeholder="Ej: 1, 2, 3…"
                                   min="1" autocomplete="off">
                            <span class="validation-icon" id="iconNfaseCrear"></span>
                        </div>
                        <div class="field-feedback" id="fbNfaseCrear"></div>
                    </div>
                    <div class="mb-1">
                        <label for="nombreCrear" class="form-label">
                            <i class="ri-flashlight-line" style="color:#fc7b04;"></i>
                            Nombre de la Fase <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="nombreCrear"
                                   placeholder="Ej: Formación, Práctica…"
                                   maxlength="100" autocomplete="off">
                            <span class="validation-icon" id="iconCrear"></span>
                        </div>
                        <div class="field-feedback" id="fbCrear"></div>
                        <div class="char-hint" id="hintCrear">0 / 100</div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">
                            <i class="ri-palette-line" style="color:#fc7b04;"></i>
                            Color <span class="req">*</span>
                        </label>
                        <input type="hidden" id="colorCrear" value="#6366f1">
                        <div class="color-picker-grid" id="colorPickerCrear"></div>
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
                    <i class="ri-edit-2-line"></i> Editar Fase
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditar" novalidate autocomplete="off">
                <input type="hidden" id="idEditar">
                <div class="modal-body">
                    <div class="mb-1">
                        <label for="nFaseEditar" class="form-label">
                            <i class="ri-hashtag" style="color:#fc7b04;"></i>
                            Número de Fase <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="number" class="form-control" id="nFaseEditar"
                                   placeholder="Ej: 1, 2, 3…"
                                   min="1" autocomplete="off">
                            <span class="validation-icon" id="iconNfaseEditar"></span>
                        </div>
                        <div class="field-feedback" id="fbNfaseEditar"></div>
                    </div>
                    <div class="mb-1">
                        <label for="nombreEditar" class="form-label">
                            <i class="ri-flashlight-line" style="color:#fc7b04;"></i>
                            Nombre de la Fase <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="nombreEditar"
                                   placeholder="Ej: Formación, Práctica…"
                                   maxlength="100" autocomplete="off">
                            <span class="validation-icon" id="iconEditar"></span>
                        </div>
                        <div class="field-feedback" id="fbEditar"></div>
                        <div class="char-hint" id="hintEditar">0 / 100</div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">
                            <i class="ri-palette-line" style="color:#fc7b04;"></i>
                            Color <span class="req">*</span>
                        </label>
                        <input type="hidden" id="colorEditar" value="#6366f1">
                        <div class="color-picker-grid" id="colorPickerEditar"></div>
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
                    <p class="delete-msg-primary">¿Eliminar fase?</p>
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
    const COLORS = ['#6366f1','#8b5cf6','#a855f7','#d946ef','#ec4899','#f43f5e','#e11d48','#dc2626','#ea580c','#f97316','#fb923c','#fbbf24','#f59e0b','#eab308','#84cc16','#22c55e','#10b981','#14b8a6','#06b6d4','#0ea5e9','#3b82f6','#2563eb'];

    function init() {
        initDataTable();
        initColorPickers();
        bindEvents();
    }

    function initColorPickers() {
        const colors = COLORS;
        
        function renderPicker(containerId, inputId, selectedColor) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            colors.forEach(color => {
                const div = document.createElement('div');
                div.className = 'color-option' + (color === selectedColor ? ' selected' : '');
                div.style.backgroundColor = color;
                div.dataset.color = color;
                div.addEventListener('click', () => selectColor(containerId, inputId, color));
                container.appendChild(div);
            });
        }
        
        window.selectColor = function(containerId, inputId, color) {
            document.getElementById(inputId).value = color;
            const container = document.getElementById(containerId);
            container.querySelectorAll('.color-option').forEach(el => {
                el.classList.toggle('selected', el.dataset.color === color);
            });
        };
        
        renderPicker('colorPickerCrear', 'colorCrear', '#6366f1');
        renderPicker('colorPickerEditar', 'colorEditar', '#6366f1');
    }

    function initDataTable() {
        tabla = $('#tabla-fases').DataTable({
            ajax: { url: '{{ route("admin.fases.listar") }}', dataSrc: 'data' },
            ordering: true,
            paging: false,
            info: false,
            columns: [
                {
                    data: 'n_fase',
                    render: n => '<span style="font-weight:700;color:var(--d-subtitle);font-size:1rem;">' + escHtml(n) + '</span>'
                },
                {
                    data: 'nombre',
                    render: n => '<span style="font-weight:600;">' + escHtml(n) + '</span>'
                },
                {
                    data: 'color',
                    className: 'text-center',
                    render: c => c 
                        ? '<span class="color-dot" style="background-color:' + escHtml(c) + ';"></span>' 
                        : '<span class="color-dot color-none"></span>'
                },
                {
                    data: null, className: 'text-center',
                    render: d =>
                        '<div class="action-cell">'
                        + '<button class="btn btn-action btn-action-edit btn-accion-editar" data-id="' + d.id + '" data-nfase="' + escHtml(d.n_fase) + '" data-nombre="' + escHtml(d.nombre) + '" data-color="' + escHtml(d.color || '') + '" title="Editar fase"><i class="ri-pencil-fill"></i></button>'
                        + '<button class="btn btn-action btn-action-delete btn-accion-eliminar" data-id="' + d.id + '" data-nombre="' + escHtml(d.nombre) + '" title="Eliminar fase"><i class="ri-delete-bin-fill"></i></button>'
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
            resetField('nombreCrear', 'iconCrear', 'fbCrear', 'hintCrear');
            resetField('nFaseCrear', 'iconNfaseCrear', 'fbNfaseCrear', null);
            $('#formCrear')[0].reset();
            selectColor('colorPickerCrear', 'colorCrear', '#6366f1');
            $('#btnGuardar').prop('disabled', true);
            openModal('modalCrear');
        });

        $(document).on('click', '.btn-accion-editar', function () {
            const id = $(this).data('id');
            const nfase = $(this).data('nfase');
            const nombre = $(this).data('nombre');
            const color = $(this).data('color') || '#6366f1';
            $('#idEditar').val(id);
            $('#nFaseEditar').val(nfase);
            $('#nombreEditar').val(nombre);
            $('#colorEditar').val(color);
            selectColor('colorPickerEditar', 'colorEditar', color);
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
            eliminar(idEliminar);
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
            resetField('nFaseCrear', 'iconNfaseCrear', 'fbNfaseCrear', null);
            $('#formCrear')[0].reset();
            selectColor('colorPickerCrear', 'colorCrear', '#6366f1');
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

        if (!val)                                    return setError('El nombre de la fase es obligatorio.');
        if (val.length < 2)                          return setError('Debe tener al menos 2 caracteres.');
        if (val.length > 100)                        return setError('No puede superar los 100 caracteres.');
        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-']+$/.test(val)) return setError('Solo se permiten letras, espacios y guiones.');

        return setOk();
    }

    function verificarNombre(inputId, iconId, fbId, idFase, btnId) {
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

        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-']+$/.test(val)) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            icon.className = 'validation-icon invalid';
            icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
            fb.className   = 'field-feedback error';
            fb.innerHTML   = '<i class="ri-error-warning-line"></i>Solo se permiten letras, espacios y guiones.';
            $(btnId).prop('disabled', true);
            return;
        }

        $.ajax({
            url: '{{ route("admin.fases.verificar") }}',
            type: 'POST',
            data: {
                _token: CSRF,
                nombre: val,
                id: idFase || null
            },
            success: function(r) {
                if (r.existe) {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                    icon.className = 'validation-icon invalid';
                    icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                    fb.className   = 'field-feedback error';
                    fb.innerHTML   = '<i class="ri-error-warning-line"></i>Esta fase ya existe.';
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
            document.getElementById(hintId).textContent = '0 / 100';
            document.getElementById(hintId).className = 'char-hint';
        }
    }

    function actualizarHint(inputId, hintId) {
        const len  = document.getElementById(inputId).value.length;
        const hint = document.getElementById(hintId);
        hint.textContent = len + ' / 100';
        hint.className   = 'char-hint' + (len > 90 ? ' warning' : '');
    }

    function guardar() {
        if (!validar('nombreCrear', 'iconCrear', 'fbCrear')) return;

        const nFase = $('#nFaseCrear').val();
        if (!nFase) {
            const input = document.getElementById('nFaseCrear');
            const icon  = document.getElementById('iconNfaseCrear');
            const fb    = document.getElementById('fbNfaseCrear');
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            icon.className = 'validation-icon invalid';
            icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
            fb.className   = 'field-feedback error';
            fb.innerHTML   = '<i class="ri-error-warning-line"></i>El número de fase es obligatorio.';
            return;
        }

        setBtnLoading('#btnGuardar', true, 'Guardando…');
        $.post('{{ route("admin.fases.guardar") }}', {
            _token: CSRF,
            n_fase: nFase,
            nombre: $('#nombreCrear').val().trim(),
            color: $('#colorCrear').val()
        })
        .done(r => {
            closeModal('modalCrear');
            tabla.ajax.reload();
            toast('success', r.message || 'Fase guardada correctamente.');
        })
        .fail(xhr => handleAjaxError(xhr, 'nombreCrear', 'iconCrear', 'fbCrear', 'guardar'))
        .always(() => setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar'));
    }

    function actualizar() {
        if (!validar('nombreEditar', 'iconEditar', 'fbEditar')) return;

        const nFase = $('#nFaseEditar').val();
        if (!nFase) {
            const input = document.getElementById('nFaseEditar');
            const icon  = document.getElementById('iconNfaseEditar');
            const fb    = document.getElementById('fbNfaseEditar');
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            icon.className = 'validation-icon invalid';
            icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
            fb.className   = 'field-feedback error';
            fb.innerHTML   = '<i class="ri-error-warning-line"></i>El número de fase es obligatorio.';
            return;
        }

        const id = $('#idEditar').val();
        setBtnLoading('#btnActualizar', true, 'Actualizando…');
        $.ajax({
            url: '/admin/fases/' + id,
            type: 'PUT',
            data: {
                _token: CSRF,
                n_fase: nFase,
                nombre: $('#nombreEditar').val().trim(),
                color: $('#colorEditar').val()
            }
        })
        .done(r => {
            closeModal('modalEditar');
            tabla.ajax.reload();
            toast('success', r.message || 'Fase actualizada correctamente.');
        })
        .fail(xhr => handleAjaxError(xhr, 'nombreEditar', 'iconEditar', 'fbEditar', 'actualizar'))
        .always(() => setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'));
    }

    function eliminar(id) {
        if (!id) return;
        setBtnLoading('#btnConfirmarEliminar', true, '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
        $.ajax({
            url: '/admin/fases/' + id,
            type: 'DELETE',
            data: { _token: CSRF }
        })
        .done(r => {
            closeModal('modalEliminar');
            tabla.ajax.reload();
            toast('success', r.message || 'Fase eliminada correctamente.');
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
            if (errs.n_fase) {
                const input = document.getElementById(ctx === 'guardar' ? 'nFaseCrear' : 'nFaseEditar');
                const icon  = document.getElementById(ctx === 'guardar' ? 'iconNfaseCrear' : 'iconNfaseEditar');
                const fb    = document.getElementById(ctx === 'guardar' ? 'fbNfaseCrear' : 'fbNfaseEditar');
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                icon.className = 'validation-icon invalid';
                icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                fb.className   = 'field-feedback error';
                fb.innerHTML   = '<i class="ri-error-warning-line"></i>' + errs.n_fase[0];
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