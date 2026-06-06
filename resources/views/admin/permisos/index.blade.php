@extends('layouts.master')
@section('title')
    Permisos
@endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<style>
    .badge-soft { background: #fff4e6; color: #b85500; padding: 3px 9px; border-radius: 12px; font-size: .75rem; font-weight: 600; }
    .badge-mod { background:#eef4ff; color:#2c5fb7; padding:3px 9px; border-radius:12px; font-size:.75rem; font-weight:600; }

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
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 14px;
    }
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
        content: "\f0d1"; /* remix search icon fallback via unicode */
        font-family: "remixicon" !important;
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #fc7b04; font-size: 1rem; pointer-events: none; z-index: 2;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 18px !important;
        border: 1.5px solid #e9ecef !important;
        padding: 5px 28px 5px 12px !important;
        font-size: .85rem;
        font-weight: 600;
        color: #b85500;
        background: #fff;
    }
    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #fc7b04 !important;
        box-shadow: 0 0 0 3px rgba(252,123,4,.12) !important;
    }

    /* ── Paginación compacta ── */
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 14px;
        text-align: right;
    }
    .dataTables_wrapper .dataTables_paginate .pagination {
        gap: 0 !important;
        margin: 0;
        padding: 0;
        justify-content: flex-end;
        display: inline-flex;
        flex-wrap: wrap;
    }
    .dataTables_wrapper .dataTables_paginate .pagination .page-item {
        margin: 0 !important;
        padding: 0 !important;
    }
    .dataTables_wrapper .dataTables_paginate .pagination .page-item + .page-item {
        margin-left: 3px !important;
    }
    .dataTables_wrapper .dataTables_paginate .page-item .page-link,
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        margin: 0 !important;
        min-width: 32px;
        height: 32px;
        padding: 0 9px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px !important;
        border: 1px solid #e9ecef !important;
        color: #555 !important;
        font-weight: 600;
        font-size: .8rem;
        line-height: 1;
        background: #fff;
        transition: all .15s;
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
        background: #f8f9fa !important;
        color: #adb5bd !important;
        border-color: #f1f3f5 !important;
    }
    .dataTables_wrapper .dataTables_paginate .ellipsis {
        padding: 0 6px !important;
        color: #adb5bd;
        align-self: center;
    }
    .dataTables_wrapper .dataTables_info {
        font-size: .82rem; color: #6b7280; padding-top: 18px;
    }

    @keyframes spin { from { transform: translateY(-50%) rotate(0deg); } to { transform: translateY(-50%) rotate(360deg); } }
    .validation-icon .ri-loader-4-line { display:inline-block; }
</style>
@endsection

@section('content')
    <div class="dept-page-header">
        <div class="container-fluid">
            <div class="dph-inner">
                <div class="dph-left">
                    <div class="dph-icon-wrap"><i class="ri-key-2-line"></i></div>
                    <div class="dph-text-block">
                        <h1 class="dph-title">Permisos</h1>
                        <p class="dph-desc">Catálogo de permisos disponibles en el sistema</p>
                    </div>
                </div>
                <div class="dph-right">
                    <div class="dph-stat-card">
                        <div class="dph-stat-icon"><i class="ri-hashtag"></i></div>
                        <div>
                            <div class="dph-stat-num" id="stat-total">—</div>
                            <div class="dph-stat-label">Total Permisos</div>
                        </div>
                    </div>
                    <button type="button" class="dph-btn-new" id="btn-nuevo">
                        <i class="ri-add-line"></i><span>Nuevo Permiso</span>
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
                        <h5 class="dept-title">Listado de Permisos</h5>
                        <p class="dept-subtitle">Formato: <code>modulo.accion</code> (ej. usuarios.crear)</p>
                    </div>
                </div>
            </div>
            <div class="dept-card-body">
                <table id="tabla-permisos" class="dept-table">
                    <thead>
                        <tr>
                            <th>Permiso</th>
                            <th class="text-center" style="width:140px;">Módulo</th>
                            <th class="text-center" style="width:140px;">Acción</th>
                            <th class="text-center" style="width:120px;">Roles</th>
                            <th class="text-center" style="width:140px;">Acciones</th>
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
                    <h5 class="modal-title"><i class="ri-add-circle-line"></i> Nuevo Permiso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formCrear" novalidate autocomplete="off">
                    <div class="modal-body">
                        <label for="nombreCrear" class="form-label">
                            <i class="ri-key-2-line" style="color:#fc7b04;"></i>
                            Nombre del Permiso <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="nombreCrear" maxlength="120" placeholder="ej: usuarios.crear" autocomplete="off">
                            <span class="validation-icon" id="iconCrear"></span>
                        </div>
                        <div class="field-feedback" id="fbCrear"></div>
                        <div class="char-hint" id="hintCrear">0 / 120</div>
                        <div class="form-text mt-2">
                            Formato <strong>modulo.accion</strong>. Solo minúsculas, números, puntos, guiones y guion bajo.
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
                    <h5 class="modal-title"><i class="ri-edit-2-line"></i> Editar Permiso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditar" novalidate autocomplete="off">
                    <input type="hidden" id="idEditar">
                    <div class="modal-body">
                        <label for="nombreEditar" class="form-label">
                            <i class="ri-key-2-line" style="color:#fc7b04;"></i>
                            Nombre del Permiso <span class="req">*</span>
                        </label>
                        <div class="field-wrapper">
                            <input type="text" class="form-control" id="nombreEditar" maxlength="120" autocomplete="off">
                            <span class="validation-icon" id="iconEditar"></span>
                        </div>
                        <div class="field-feedback" id="fbEditar"></div>
                        <div class="char-hint" id="hintEditar">0 / 120</div>
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
                    <h5 class="modal-title"><i class="ri-error-warning-line"></i> Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Eliminar el permiso <strong id="nombreEliminar"></strong>?</p>
                    <p class="text-muted small">Se removerá de todos los roles que lo tengan asignado.</p>
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
    const REGEX_NAME = /^[a-z0-9\.\-\_]+$/;
    const MIN_LEN = 3;
    const MAX_LEN = 120;
    let tabla, idEliminar = null;
    let timerCrear = null, timerEditar = null;

    $(document).ready(function(){
        tabla = $('#tabla-permisos').DataTable({
            ajax: { url: '{{ route("admin.permisos.listar") }}', dataSrc: 'data' },
            paging: true, pageLength: 10, info: true,
            pagingType: 'simple_numbers',
            dom: "<'row mb-2'<'col-sm-6'l><'col-sm-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>",
            columns: [
                { data: 'name', render: n => '<code style="font-weight:600;color:#b85500;">'+escHtml(n)+'</code>' },
                { data: 'modulo', className:'text-center', render: m => m ? '<span class="badge-mod">'+escHtml(m)+'</span>' : '—' },
                { data: 'accion', className:'text-center', render: a => a ? '<span class="badge-soft">'+escHtml(a)+'</span>' : '—' },
                { data: 'roles_count', className:'text-center', render: c => '<span class="badge-soft">'+c+'</span>' },
                { data: null, className:'text-center', render: d =>
                    '<div class="action-cell">'+
                    '<button class="btn btn-action btn-action-edit btn-editar" data-id="'+d.id+'" data-name="'+escHtml(d.name)+'" title="Editar"><i class="ri-pencil-fill"></i></button>'+
                    '<button class="btn btn-action btn-action-delete btn-eliminar" data-id="'+d.id+'" data-name="'+escHtml(d.name)+'" title="Eliminar"><i class="ri-delete-bin-fill"></i></button>'+
                    '</div>'
                }
            ],
            order: [[1,'asc'],[2,'asc']],
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
                search: '',
                searchPlaceholder: 'Buscar permiso...',
                zeroRecords: 'No se encontraron coincidencias',
                emptyTable: 'No hay permisos registrados',
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
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#idEditar').val(id);
            $('#nombreEditar').val(name);
            resetField('nombreEditar','iconEditar','fbEditar','hintEditar');
            actualizarHint('nombreEditar','hintEditar');
            // Pre-validar el nombre actual
            setOk('nombreEditar','iconEditar','fbEditar','Nombre válido');
            $('#btnActualizar').prop('disabled', false);
            openModal('modalEditar');
        });

        $(document).on('click', '.btn-eliminar', function(){
            idEliminar = $(this).data('id');
            $('#nombreEliminar').text($(this).data('name'));
            openModal('modalEliminar');
        });

        // ── Validación en tiempo real: Crear ──
        $('#nombreCrear').on('input', function(){
            actualizarHint('nombreCrear','hintCrear');
            clearTimeout(timerCrear);
            const val = $(this).val().trim();
            if (!validarFormato(val, 'nombreCrear','iconCrear','fbCrear','#btnGuardar')) return;
            setChecking('nombreCrear','iconCrear','fbCrear','Verificando disponibilidad...');
            $('#btnGuardar').prop('disabled', true);
            timerCrear = setTimeout(()=> verificarNombre(val, null, 'nombreCrear','iconCrear','fbCrear','#btnGuardar'), 350);
        });

        // ── Validación en tiempo real: Editar ──
        $('#nombreEditar').on('input', function(){
            actualizarHint('nombreEditar','hintEditar');
            clearTimeout(timerEditar);
            const val = $(this).val().trim();
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

        // Reset al cerrar
        document.getElementById('modalCrear').addEventListener('hidden.bs.modal', ()=>{
            $('#formCrear')[0].reset();
            resetField('nombreCrear','iconCrear','fbCrear','hintCrear');
            $('#btnGuardar').prop('disabled', true);
        });
    });

    // ── Validación de formato (en cliente, instantánea) ──
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
            setError(inputId, iconId, fbId, 'Solo minúsculas, números, puntos, guiones y guion bajo.');
            $(btnSel).prop('disabled', true);
            return false;
        }
        if (!val.includes('.')) {
            setError(inputId, iconId, fbId, 'Use el formato modulo.accion (ej. usuarios.crear).');
            $(btnSel).prop('disabled', true);
            return false;
        }
        return true;
    }

    // ── Verificación en servidor ──
    function verificarNombre(val, id, inputId, iconId, fbId, btnSel){
        $.ajax({
            url: '{{ route("admin.permisos.verificar") }}',
            type: 'POST',
            data: { _token: CSRF, name: val, id: id || null }
        })
        .done(r => {
            if (r.existe) {
                setError(inputId, iconId, fbId, 'Este permiso ya existe.');
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

    // ── Helpers de estado del campo ──
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
        hint.className = 'char-hint' + (len > MAX_LEN - 20 ? ' warning' : '');
    }

    function guardar(){
        const name = $('#nombreCrear').val().trim();
        if (!validarFormato(name,'nombreCrear','iconCrear','fbCrear','#btnGuardar')) return;
        setBtnLoading('#btnGuardar', true, 'Guardando…');
        $.post('{{ route("admin.permisos.guardar") }}', { _token: CSRF, name })
            .done(r=>{ closeModal('modalCrear'); tabla.ajax.reload(); toast('success', r.message); })
            .fail(xhr=> handleErr(xhr, 'nombreCrear','iconCrear','fbCrear'))
            .always(()=> setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar'));
    }

    function actualizar(){
        const id = $('#idEditar').val();
        const name = $('#nombreEditar').val().trim();
        if (!validarFormato(name,'nombreEditar','iconEditar','fbEditar','#btnActualizar')) return;
        setBtnLoading('#btnActualizar', true, 'Actualizando…');
        $.ajax({ url: '/admin/permisos/'+id, type:'PUT', data:{ _token: CSRF, name }})
            .done(r=>{ closeModal('modalEditar'); tabla.ajax.reload(); toast('success', r.message); })
            .fail(xhr=> handleErr(xhr, 'nombreEditar','iconEditar','fbEditar'))
            .always(()=> setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'));
    }

    function eliminar(id){
        setBtnLoading('#btnConfirmarEliminar', true, 'Eliminando…');
        $.ajax({ url:'/admin/permisos/'+id, type:'DELETE', data:{ _token: CSRF }})
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
