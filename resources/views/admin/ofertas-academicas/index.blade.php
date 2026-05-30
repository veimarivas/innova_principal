@extends('layouts.master')
@section('title')
    Ofertas Académicas - {{ $posgrado->nombre }}
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <style>
        .badge-display {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, rgba(154, 73, 4, 0.12) 0%, rgba(154, 73, 4, 0.06) 100%);
            color: var(--d-title);
            border: 1px solid rgba(154, 73, 4, 0.15);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
        }

        .badge-info {
            background: rgba(252, 123, 4, 0.12);
            color: #fc7b04;
            border-color: rgba(252, 123, 4, 0.2);
        }

        .col-fields-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }

        .col-fields-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0.85rem;
        }

        @media (max-width: 576px) {

            .col-fields-2,
            .col-fields-3 {
                grid-template-columns: 1fr;
            }
        }

        .mb-form {
            margin-bottom: 0.75rem;
        }

        .modal-dialog-scrollable .modal-footer {
            flex-shrink: 0;
            position: sticky;
            bottom: 0;
            background: var(--d-modal-bg);
            z-index: 2;
            border-top: 1px solid var(--d-card-border);
            padding: 0.75rem 1rem;
        }

        .modal-dialog-scrollable .modal-body {
            overflow-y: auto;
            max-height: calc(90vh - 140px);
            padding-bottom: 1rem;
        }

        .modal-dialog-scrollable .modal-header {
            flex-shrink: 0;
        }

        .posgrado-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.85rem;
            background: rgba(252, 123, 4, 0.08);
            border: 1px solid rgba(252, 123, 4, 0.15);
            border-radius: 8px;
        }

        .posgrado-badge i {
            color: #fc7b04;
            font-size: 1.1rem;
        }

        .posgrado-badge span {
            font-weight: 500;
            font-size: 0.9rem;
        }
    </style>
@endsection

@section('content')
    <div class="dept-page-header">
        <div class="container-fluid">
            <div class="dph-inner">
                <div class="dph-left">
                    <div class="dph-icon-wrap"><i class="ri-book-open-line"></i></div>
                    <div class="dph-text-block">
                        <h1 class="dph-title">Ofertas Académicas</h1>
                        <div class="posgrado-badge mt-1">
                            <i class="ri-graduation-cap-line"></i>
                            <span>{{ $posgrado->nombre }}</span>
                        </div>
                    </div>
                </div>
                <div class="dph-right">
                    <div class="dph-stat-card">
                        <div class="dph-stat-icon"><i class="ri-hashtag"></i></div>
                        <div>
                            <div class="dph-stat-num" id="stat-total">—</div>
                            <div class="dph-stat-label">Total Ofertas</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.posgrads.index') }}" class="dph-btn-new"
                        style="text-decoration:none; background: rgba(154, 73, 4, 0.15); border: 1px solid rgba(154, 73, 4, 0.25);">
                        <i class="ri-arrow-go-back-line"></i> <span>Volver</span>
                    </a>
                    <button type="button" class="dph-btn-new" id="btn-nuevo"><i class="ri-add-line"></i> <span>Nueva
                            Oferta</span></button>
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
                            <div class="dept-header-icon"><i class="ri-table-line"></i></div>
                            <div>
                                <h5 class="dept-title">Listado de Ofertas</h5>
                                <p class="dept-subtitle">Ofertas académicas asociadas a este posgrado</p>
                            </div>
                        </div>
                    </div>
                    <div class="dept-card-body">
                        <table id="tabla-ofertas" class="dept-table">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Programa</th>
                                    <th>Fase</th>
                                    <th>Sucursal</th>
                                    <th>Modalidad</th>
                                    <th>Gestión</th>
                                    <th>Inicio Inscripciones</th>
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

    {{-- Modal Crear --}}
    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:820px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-book-open-line"></i> Nueva Oferta Académica</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formCrear" novalidate autocomplete="off">
                    <div class="modal-body">
                        <input type="hidden" id="posgradoIdCrear" value="{{ $posgrado->id }}">
                        <div class="col-fields-2 mb-form">
                            <div>
                                <label for="codigoCrear" class="form-label"><i class="ri-hashtag"
                                        style="color:#fc7b04;"></i> Código <span class="req">*</span></label>
                                <input type="text" class="form-control" id="codigoCrear" placeholder="Ej: OF-2026-001"
                                    maxlength="50" autocomplete="off">
                                <div class="field-feedback" id="fbCodigoCrear"></div>
                            </div>
                            <div>
                                <label for="gestionCrear" class="form-label"><i class="ri-calendar-event-line"
                                        style="color:#fc7b04;"></i> Gestión <span class="req">*</span></label>
                                <input type="number" class="form-control" id="gestionCrear" placeholder="2026"
                                    min="2000" value="2026">
                            </div>
                        </div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="programaCrear" class="form-label"><i class="ri-file-list-3-line"
                                        style="color:#fc7b04;"></i> Programa <span class="req">*</span></label>
                                <select class="form-select" id="programaCrear">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($programas as $p)
                                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="faseCrear" class="form-label"><i class="ri-route-line"
                                        style="color:#fc7b04;"></i> Fase <span class="req">*</span></label>
                                <select class="form-select" id="faseCrear">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($fases as $f)
                                        <option value="{{ $f->id }}">{{ $f->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="modalidadCrear" class="form-label"><i class="ri-wifi-line"
                                        style="color:#fc7b04;"></i> Modalidad</label>
                                <select class="form-select" id="modalidadCrear">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($modalidades as $m)
                                        <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-form">
                            <label for="sucursalCrear" class="form-label"><i class="ri-map-pin-line"
                                    style="color:#fc7b04;"></i> Sucursal</label>
                            <select class="form-select" id="sucursalCrear">
                                <option value="">Seleccionar...</option>
                                @foreach ($sucursales as $s)
                                    <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="fechaInscripcionesCrear" class="form-label"><i class="ri-calendar-check-line"
                                        style="color:#fc7b04;"></i> Inicio Inscripciones <span
                                        class="req">*</span></label>
                                <input type="date" class="form-control" id="fechaInscripcionesCrear">
                            </div>
                            <div>
                                <label for="fechaInicioCrear" class="form-label"><i class="ri-play-line"
                                        style="color:#fc7b04;"></i> Inicio Programa <span class="req">*</span></label>
                                <input type="date" class="form-control" id="fechaInicioCrear">
                            </div>
                            <div>
                                <label for="fechaFinCrear" class="form-label"><i class="ri-stop-line"
                                        style="color:#fc7b04;"></i> Fin Programa <span class="req">*</span></label>
                                <input type="date" class="form-control" id="fechaFinCrear">
                            </div>
                        </div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="nModulosCrear" class="form-label"><i class="ri-layout-grid-line"
                                        style="color:#fc7b04;"></i> N° Módulos <span class="req">*</span></label>
                                <input type="number" class="form-control" id="nModulosCrear" placeholder="0"
                                    min="1" value="1">
                            </div>
                            <div>
                                <label for="cantSesionesCrear" class="form-label"><i class="ri-team-line"
                                        style="color:#fc7b04;"></i> N° Sesiones <span class="req">*</span></label>
                                <input type="number" class="form-control" id="cantSesionesCrear" placeholder="0"
                                    min="1" value="1">
                            </div>
                            <div>
                                <label for="notaMinimaCrear" class="form-label"><i class="ri-star-line"
                                        style="color:#fc7b04;"></i> Nota Mínima <span class="req">*</span></label>
                                <input type="number" class="form-control" id="notaMinimaCrear" placeholder="51"
                                    min="0" max="100" value="51">
                            </div>
                        </div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="versionCrear" class="form-label"><i class="ri-git-commit-line"
                                        style="color:#fc7b04;"></i> Versión</label>
                                <input type="number" class="form-control" id="versionCrear" placeholder="1"
                                    min="1" value="1">
                            </div>
                            <div>
                                <label for="grupoCrear" class="form-label"><i class="ri-group-line"
                                        style="color:#fc7b04;"></i> Grupo</label>
                                <input type="number" class="form-control" id="grupoCrear" placeholder="1"
                                    min="1" value="1">
                            </div>
                            <div>
                                <label for="colorCrear" class="form-label"><i class="ri-palette-line"
                                        style="color:#fc7b04;"></i> Color</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" class="form-control form-control-color" id="colorCrear"
                                        value="#fc7b04" style="width:42px;height:38px;padding:3px;">
                                    <input type="text" class="form-control" id="colorTextCrear" value="#fc7b04"
                                        maxlength="7" style="flex:1;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i
                                class="ri-close-line me-1"></i>Cancelar</button>
                        <button type="button" class="btn btn-modal-submit" id="btnGuardar"><i class="ri-save-line"></i>
                            Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Editar --}}
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:820px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-edit-2-line"></i> Editar Oferta Académica</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formEditar" novalidate autocomplete="off">
                    <input type="hidden" id="idEditar">
                    <input type="hidden" id="posgradoIdEditar" value="{{ $posgrado->id }}">
                    <div class="modal-body">
                        <div class="col-fields-2 mb-form">
                            <div>
                                <label for="codigoEditar" class="form-label"><i class="ri-hashtag"
                                        style="color:#fc7b04;"></i> Código <span class="req">*</span></label>
                                <input type="text" class="form-control" id="codigoEditar"
                                    placeholder="Ej: OF-2026-001" maxlength="50" autocomplete="off">
                                <div class="field-feedback" id="fbCodigoEditar"></div>
                            </div>
                            <div>
                                <label for="gestionEditar" class="form-label"><i class="ri-calendar-event-line"
                                        style="color:#fc7b04;"></i> Gestión <span class="req">*</span></label>
                                <input type="number" class="form-control" id="gestionEditar" placeholder="2026"
                                    min="2000">
                            </div>
                        </div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="programaEditar" class="form-label"><i class="ri-file-list-3-line"
                                        style="color:#fc7b04;"></i> Programa <span class="req">*</span></label>
                                <select class="form-select" id="programaEditar">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($programas as $p)
                                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="faseEditar" class="form-label"><i class="ri-route-line"
                                        style="color:#fc7b04;"></i> Fase <span class="req">*</span></label>
                                <select class="form-select" id="faseEditar">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($fases as $f)
                                        <option value="{{ $f->id }}">{{ $f->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="modalidadEditar" class="form-label"><i class="ri-wifi-line"
                                        style="color:#fc7b04;"></i> Modalidad</label>
                                <select class="form-select" id="modalidadEditar">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($modalidades as $m)
                                        <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-form">
                            <label for="sucursalEditar" class="form-label"><i class="ri-map-pin-line"
                                    style="color:#fc7b04;"></i> Sucursal</label>
                            <select class="form-select" id="sucursalEditar">
                                <option value="">Seleccionar...</option>
                                @foreach ($sucursales as $s)
                                    <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="fechaInscripcionesEditar" class="form-label"><i
                                        class="ri-calendar-check-line" style="color:#fc7b04;"></i> Inicio Inscripciones
                                    <span class="req">*</span></label>
                                <input type="date" class="form-control" id="fechaInscripcionesEditar">
                            </div>
                            <div>
                                <label for="fechaInicioEditar" class="form-label"><i class="ri-play-line"
                                        style="color:#fc7b04;"></i> Inicio Programa <span class="req">*</span></label>
                                <input type="date" class="form-control" id="fechaInicioEditar">
                            </div>
                            <div>
                                <label for="fechaFinEditar" class="form-label"><i class="ri-stop-line"
                                        style="color:#fc7b04;"></i> Fin Programa <span class="req">*</span></label>
                                <input type="date" class="form-control" id="fechaFinEditar">
                            </div>
                        </div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="nModulosEditar" class="form-label"><i class="ri-layout-grid-line"
                                        style="color:#fc7b04;"></i> N° Módulos <span class="req">*</span></label>
                                <input type="number" class="form-control" id="nModulosEditar" placeholder="0"
                                    min="1">
                            </div>
                            <div>
                                <label for="cantSesionesEditar" class="form-label"><i class="ri-team-line"
                                        style="color:#fc7b04;"></i> N° Sesiones <span class="req">*</span></label>
                                <input type="number" class="form-control" id="cantSesionesEditar" placeholder="0"
                                    min="1">
                            </div>
                            <div>
                                <label for="notaMinimaEditar" class="form-label"><i class="ri-star-line"
                                        style="color:#fc7b04;"></i> Nota Mínima <span class="req">*</span></label>
                                <input type="number" class="form-control" id="notaMinimaEditar" placeholder="51"
                                    min="0" max="100">
                            </div>
                        </div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="versionEditar" class="form-label"><i class="ri-git-commit-line"
                                        style="color:#fc7b04;"></i> Versión</label>
                                <input type="number" class="form-control" id="versionEditar" placeholder="1"
                                    min="1">
                            </div>
                            <div>
                                <label for="grupoEditar" class="form-label"><i class="ri-group-line"
                                        style="color:#fc7b04;"></i> Grupo</label>
                                <input type="number" class="form-control" id="grupoEditar" placeholder="1"
                                    min="1">
                            </div>
                            <div>
                                <label for="colorEditar" class="form-label"><i class="ri-palette-line"
                                        style="color:#fc7b04;"></i> Color</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" class="form-control form-control-color" id="colorEditar"
                                        value="#fc7b04" style="width:42px;height:38px;padding:3px;">
                                    <input type="text" class="form-control" id="colorTextEditar" value="#fc7b04"
                                        maxlength="7" style="flex:1;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i
                                class="ri-close-line me-1"></i>Cancelar</button>
                        <button type="button" class="btn btn-modal-submit" id="btnActualizar"><i
                                class="ri-refresh-line"></i> Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Eliminar --}}
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-error-warning-line"></i> Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="delete-warning-box">
                        <div class="delete-icon-ring"><i class="ri-delete-bin-5-line"></i></div>
                        <p class="delete-msg-primary">¿Eliminar oferta académica?</p>
                        <p class="delete-msg-name"><strong id="codigoEliminar"></strong></p>
                        <p class="delete-msg-warn"><i class="ri-information-line"></i> Esta acción es permanente y no
                            puede deshacerse.</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center gap-3">
                    <button type="button" class="btn btn-modal-cancel px-4" data-bs-dismiss="modal"><i
                            class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="button" class="btn btn-danger-modal px-4" id="btnConfirmarEliminar"><i
                            class="ri-delete-bin-line"></i> Eliminar</button>
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
        (function() {
            'use strict';
            let tabla;
            let idEliminar = null;
            const CSRF = '{{ csrf_token() }}';
            const POSGRADO_ID = {{ $posgrado->id }};

            $.fn.dataTable.ext.errMode = 'none';

            function init() {
                initDataTable();
                bindEvents();
            }

            function initDataTable() {
                tabla = $('#tabla-ofertas').DataTable({
                    ajax: {
                        url: '/admin/posgrads/' + POSGRADO_ID + '/ofertas/listar',
                        dataSrc: 'data',
                        error: function(xhr) {
                            const msg = xhr.status === 0 ? 'Sin conexión al servidor.' : 'Error ' + xhr
                                .status + ' al cargar los datos.';
                            toast('error', msg);
                        }
                    },
                    ordering: true,
                    paging: false,
                    info: false,
                    columns: [{
                            data: 'codigo',
                            render: c => '<span style="font-weight:600;">' + escHtml(c) + '</span>'
                        },
                        {
                            data: 'programa.nombre',
                            render: d => d ? '<span class="badge-display">' + escHtml(d) + '</span>' :
                                '<span class="text-muted">-</span>'
                        },
                        {
                            data: 'fase.nombre',
                            render: d => d ? '<span class="badge-display badge-info">' + escHtml(d) +
                                '</span>' : '<span class="text-muted">-</span>'
                        },
                        {
                            data: 'sucursal.nombre',
                            render: d => d ? escHtml(d) : '<span class="text-muted">-</span>'
                        },
                        {
                            data: 'modalidad.nombre',
                            render: d => d ? escHtml(d) : '<span class="text-muted">-</span>'
                        },
                        {
                            data: 'gestion',
                            render: g => g ? '<span style="font-weight:500;">' + g + '</span>' : '-'
                        },
                        {
                            data: 'fecha_inicio_inscripciones',
                            render: d => d ? formatDate(d) : '-'
                        },
                        {
                            data: null,
                            className: 'text-center',
                            render: d => '<div class="action-cell">' +
                                '<a href="/admin/posgrads/ofertas/' + d.id +
                                '/detalle" class="btn btn-action" title="Ver detalle" style="color:#0d9488;"><i class="ri-eye-line"></i></a>' +
                                '<button class="btn btn-action btn-modulos-oferta" data-id="' + d.id +
                                '" data-n-modulos="' + (d.n_modulos || 0) + '" data-codigo="' + escHtml(d
                                    .codigo) +
                                '" title="Gestionar Módulos" style="color:#6366f1;"><i class="ri-stack-line"></i></button>' +
                                '<button class="btn btn-action btn-action-edit btn-accion-editar" data-id="' +
                                d.id + '" data-codigo="' + escHtml(d.codigo) + '" data-programa_id="' + (d
                                    .programa_id || '') + '" data-fase_id="' + (d.fase_id || '') +
                                '" data-modalidad_id="' + (d.modalidad_id || '') + '" data-sucursale_id="' +
                                (d.sucursale_id || '') + '" data-gestion="' + (d.gestion || '') +
                                '" data-fecha_inicio_inscripciones="' + (d.fecha_inicio_inscripciones ||
                                    '') + '" data-fecha_inicio_programa="' + (d.fecha_inicio_programa ||
                                    '') +
                                '" data-fecha_fin_programa="' + (d.fecha_fin_programa || '') +
                                '" data-n_modulos="' + (d.n_modulos || '') + '" data-cantidad_sesiones="' +
                                (d.cantidad_sesiones || '') + '" data-nota_minima="' + (d.nota_minima ||
                                    '') + '" data-version="' + (d.version || '') + '" data-grupo="' + (d
                                    .grupo || '') + '" data-color="' + (d.color || '#fc7b04') +
                                '" title="Editar oferta"><i class="ri-pencil-fill"></i></button>' +
                                '<button class="btn btn-action btn-action-delete btn-accion-eliminar" data-id="' +
                                d.id + '" data-codigo="' + escHtml(d.codigo) +
                                '" title="Eliminar oferta"><i class="ri-delete-bin-fill"></i></button>' +
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

            function formatDate(dateStr) {
                if (!dateStr) return '-';
                const parts = dateStr.split('-');
                if (parts.length === 3) return parts[2] + '/' + parts[1] + '/' + parts[0];
                return dateStr;
            }

            function bindEvents() {
                $('#btn-nuevo').on('click', () => {
                    $('#formCrear')[0].reset();
                    $('#posgradoIdCrear').val(POSGRADO_ID);
                    $('#gestionCrear').val(new Date().getFullYear());
                    $('#nModulosCrear').val(1);
                    $('#cantSesionesCrear').val(1);
                    $('#notaMinimaCrear').val(51);
                    $('#versionCrear').val(1);
                    $('#grupoCrear').val(1);
                    $('#colorCrear').val('#fc7b04');
                    $('#colorTextCrear').val('#fc7b04');
                    $('#fbCodigoCrear').html('');
                    openModal('modalCrear');
                });

                $(document).on('click', '.btn-accion-editar', function() {
                    const d = $(this).data();
                    $('#idEditar').val(d.id);
                    $('#posgradoIdEditar').val(POSGRADO_ID);
                    $('#codigoEditar').val(d.codigo);
                    $('#programaEditar').val(d.programa_id || '');
                    $('#faseEditar').val(d.fase_id || '');
                    $('#modalidadEditar').val(d.modalidad_id || '');
                    $('#sucursalEditar').val(d.sucursale_id || '');
                    $('#gestionEditar').val(d.gestion || '');
                    $('#fechaInscripcionesEditar').val(d.fecha_inicio_inscripciones || '');
                    $('#fechaInicioEditar').val(d.fecha_inicio_programa || '');
                    $('#fechaFinEditar').val(d.fecha_fin_programa || '');
                    $('#nModulosEditar').val(d.n_modulos || '');
                    $('#cantSesionesEditar').val(d.cantidad_sesiones || '');
                    $('#notaMinimaEditar').val(d.nota_minima || '');
                    $('#versionEditar').val(d.version || '');
                    $('#grupoEditar').val(d.grupo || '');
                    const color = d.color || '#fc7b04';
                    $('#colorEditar').val(color);
                    $('#colorTextEditar').val(color);
                    $('#fbCodigoEditar').html('');
                    openModal('modalEditar');
                });

                $(document).on('click', '.btn-accion-eliminar', function() {
                    idEliminar = $(this).data('id');
                    $('#codigoEliminar').text($(this).data('codigo'));
                    openModal('modalEliminar');
                });

                $('#btnConfirmarEliminar').on('click', function() {
                    if (!idEliminar) return;
                    eliminarOferta(idEliminar);
                });

                $('#btnGuardar').on('click', function() {
                    guardar();
                });
                $('#btnActualizar').on('click', function() {
                    actualizar();
                });

                $('#colorCrear').on('input', function() {
                    $('#colorTextCrear').val($(this).val());
                });
                $('#colorTextCrear').on('input', function() {
                    const v = $(this).val();
                    if (/^#[0-9A-Fa-f]{6}$/.test(v)) $('#colorCrear').val(v);
                });
                $('#colorEditar').on('input', function() {
                    $('#colorTextEditar').val($(this).val());
                });
                $('#colorTextEditar').on('input', function() {
                    const v = $(this).val();
                    if (/^#[0-9A-Fa-f]{6}$/.test(v)) $('#colorEditar').val(v);
                });

                document.getElementById('modalCrear').addEventListener('hidden.bs.modal', () => {
                    $('#formCrear')[0].reset();
                });
                document.getElementById('modalEditar').addEventListener('hidden.bs.modal', () => {
                    $('#formEditar')[0].reset();
                });
            }

            function guardar() {
                setBtnLoading('#btnGuardar', true, 'Guardando…');
                $.ajax({
                        url: '{{ route('admin.posgrads.ofertas.guardar') }}',
                        type: 'POST',
                        data: {
                            _token: CSRF,
                            codigo: $('#codigoCrear').val().trim(),
                            posgrado_id: $('#posgradoIdCrear').val(),
                            programa_id: $('#programaCrear').val(),
                            fase_id: $('#faseCrear').val(),
                            modalidade_id: $('#modalidadCrear').val(),
                            sucursale_id: $('#sucursalCrear').val(),
                            fecha_inicio_inscripciones: $('#fechaInscripcionesCrear').val(),
                            fecha_inicio_programa: $('#fechaInicioCrear').val(),
                            fecha_fin_programa: $('#fechaFinCrear').val(),
                            gestion: $('#gestionCrear').val(),
                            n_modulos: $('#nModulosCrear').val(),
                            cantidad_sesiones: $('#cantSesionesCrear').val(),
                            version: $('#versionCrear').val(),
                            grupo: $('#grupoCrear').val(),
                            nota_minima: $('#notaMinimaCrear').val(),
                            color: $('#colorCrear').val()
                        }
                    })
                    .done(r => {
                        closeModal('modalCrear');
                        tabla.ajax.reload();
                        toast('success', r.message || 'Oferta registrada correctamente.');
                    })
                    .fail(xhr => {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errs = xhr.responseJSON.errors;
                            if (errs.codigo) {
                                $('#fbCodigoCrear').html(
                                    '<span style="color:#ef4444;font-size:0.78rem;"><i class="ri-error-warning-line"></i>' +
                                    errs.codigo[0] + '</span>');
                            }
                        } else {
                            toast('error', 'Ocurrió un error. Intente nuevamente.');
                        }
                    })
                    .always(() => setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar'));
            }

            function actualizar() {
                const id = $('#idEditar').val();
                setBtnLoading('#btnActualizar', true, 'Actualizando…');
                $.ajax({
                        url: '/admin/posgrads/ofertas/' + id,
                        type: 'PUT',
                        data: {
                            _token: CSRF,
                            codigo: $('#codigoEditar').val().trim(),
                            posgrado_id: $('#posgradoIdEditar').val(),
                            programa_id: $('#programaEditar').val(),
                            fase_id: $('#faseEditar').val(),
                            modalidade_id: $('#modalidadEditar').val(),
                            sucursale_id: $('#sucursalEditar').val(),
                            fecha_inicio_inscripciones: $('#fechaInscripcionesEditar').val(),
                            fecha_inicio_programa: $('#fechaInicioEditar').val(),
                            fecha_fin_programa: $('#fechaFinEditar').val(),
                            gestion: $('#gestionEditar').val(),
                            n_modulos: $('#nModulosEditar').val(),
                            cantidad_sesiones: $('#cantSesionesEditar').val(),
                            version: $('#versionEditar').val(),
                            grupo: $('#grupoEditar').val(),
                            nota_minima: $('#notaMinimaEditar').val(),
                            color: $('#colorEditar').val()
                        }
                    })
                    .done(r => {
                        closeModal('modalEditar');
                        tabla.ajax.reload();
                        toast('success', r.message || 'Oferta actualizada correctamente.');
                    })
                    .fail(xhr => {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errs = xhr.responseJSON.errors;
                            if (errs.codigo) {
                                $('#fbCodigoEditar').html(
                                    '<span style="color:#ef4444;font-size:0.78rem;"><i class="ri-error-warning-line"></i>' +
                                    errs.codigo[0] + '</span>');
                            }
                        } else {
                            toast('error', 'Ocurrió un error. Intente nuevamente.');
                        }
                    })
                    .always(() => setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'));
            }

            function eliminarOferta(id) {
                setBtnLoading('#btnConfirmarEliminar', true,
                    '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
                $.ajax({
                        url: '/admin/posgrads/ofertas/' + id,
                        type: 'DELETE',
                        data: {
                            _token: CSRF
                        }
                    })
                    .done(r => {
                        closeModal('modalEliminar');
                        tabla.ajax.reload();
                        toast('success', r.message || 'Oferta eliminada correctamente.');
                    })
                    .fail(xhr => {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'No se pudo eliminar.';
                        toast(xhr.status === 400 ? 'warning' : 'error', msg);
                    })
                    .always(() => {
                        setBtnLoading('#btnConfirmarEliminar', false,
                            '<i class="ri-delete-bin-line"></i> Eliminar');
                        idEliminar = null;
                    });
            }

            function setBtnLoading(sel, loading, labelHtml) {
                const btn = document.querySelector(sel);
                if (!btn) return;
                btn.disabled = loading;
                if (loading) {
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
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g,
                    '&quot;');
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
                el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') +
                    '"></i></div>' +
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

            // === MODULOS ===
            let tablaModulos = null;
            let idEliminarModulo = null;
            let currentOfertaModulos = null;
            let pendingDocenteSearch = null;

            $(document).on('click', '.btn-modulos-oferta', function() {
                currentOfertaModulos = $(this).data('id');
                const nModulos = parseInt($(this).data('n-modulos')) || 0;
                const codigo = $(this).data('codigo') || '';
                $('#modulosOfertaId').val(currentOfertaModulos);
                $('#modulosNModulos').val(nModulos);
                $('#modulosOfertaCodigo').text(codigo);
                initModulosTable(currentOfertaModulos);
                openModal('modalModulos');
            });

            function initModulosTable(ofertaId) {
                if (tablaModulos) {
                    tablaModulos.destroy();
                    $('#tabla-modulos tbody').empty();
                }
                tablaModulos = $('#tabla-modulos').DataTable({
                    ajax: {
                        url: '/admin/posgrads/ofertas/' + ofertaId + '/modulos/listar',
                        dataSrc: 'data',
                        error: function(xhr) {
                            toast('error', 'Error al cargar los módulos.');
                        }
                    },
                    ordering: true,
                    paging: false,
                    info: false,
                    columns: [{
                            data: 'n_modulo',
                            render: n => '<span class="badge bg-primary">' + n + '</span>'
                        },
                        {
                            data: 'nombre',
                            render: d => '<span class="fw-semibold">' + escHtml(d) + '</span>'
                        },
                        {
                            data: 'docente',
                            render: d => {
                                if (d && d.persona) {
                                    const p = d.persona;
                                    const nombre = (p.nombres || '') + ' ' + (p.apellido_paterno ||
                                        '') + ' ' + (p.apellido_materno || '');
                                    return '<span style="font-size:0.85rem;">' + escHtml(nombre
                                            .trim()) +
                                        '</span><br><span style="font-size:0.72rem;color:var(--d-muted);">CI: ' +
                                        escHtml(p.carnet || '') + '</span>';
                                }
                                return '<span class="text-muted" style="font-size:0.8rem;">Sin asignar</span>';
                            }
                        },
                        {
                            data: 'estado',
                            render: d => {
                                const colors = {
                                    'No Inicio': 'bg-warning',
                                    'En Desarrollo': 'bg-success',
                                    'Concluido': 'bg-info'
                                };
                                const cls = colors[d] || 'bg-secondary';
                                return '<span class="badge ' + cls + '">' + escHtml(d) + '</span>';
                            }
                        },
                        {
                            data: 'fecha_inicio',
                            render: d => d ? formatDate(d) : '-'
                        },
                        {
                            data: 'fecha_fin',
                            render: d => d ? formatDate(d) : '-'
                        },
                        {
                            data: null,
                            className: 'text-center',
                            render: d => '<div class="action-cell">' +
                                '<button class="btn btn-action btn-edit-modulo" data-id="' + d.id +
                                '" title="Editar" style="color:#f59e0b;"><i class="ri-pencil-fill"></i></button>' +
                                '<button class="btn btn-action btn-delete-modulo" data-id="' + d.id +
                                '" data-nombre="' + escHtml(d.nombre) +
                                '" title="Eliminar" style="color:#ef4444;"><i class="ri-delete-bin-line"></i></button>' +
                                '</div>'
                        }
                    ],
                    language: {
                        processing: 'Procesando...',
                        loadingRecords: 'Cargando...',
                        zeroRecords: 'No se encontraron módulos',
                        emptyTable: 'No hay módulos registrados'
                    },
                    order: [
                        [0, 'asc']
                    ],
                    drawCallback: function() {
                        const total = tablaModulos.data().count();
                        const max = parseInt($('#modulosNModulos').val()) || 0;
                        $('#modulosCountBadge').text(total + ' / ' + max + ' módulos');
                    }
                });
            }

            $('#btnNuevoModulo').on('click', function() {
                resetModuloForm();
                const total = tablaModulos ? tablaModulos.data().count() : 0;
                const max = parseInt($('#modulosNModulos').val()) || 0;
                if (total >= max) {
                    toast('warning', 'Ya se alcanzó el máximo de ' + max + ' módulos.');
                    return;
                }
                const nextN = total + 1;
                $('#moduloId').val('');
                $('#moduloOfertaId').val($('#modulosOfertaId').val());
                $('#moduloNN').val(nextN);
                $('#moduloFormTitle').html(
                    '<i class="ri-stack-line" style="color:#6366f1;"></i> Nuevo Módulo ' + nextN);
                openModal('modalModuloForm');
            });

            $(document).on('click', '.btn-edit-modulo', function() {
                const btn = $(this);
                const id = btn.data('id');
                const row = tablaModulos.row(btn.closest('tr')).data();
                $('#moduloId').val(id);
                $('#moduloOfertaId').val($('#modulosOfertaId').val());
                $('#moduloNN').val(row.n_modulo);
                $('#moduloNombre').val(row.nombre);
                $('#moduloColor').val(row.color || '#6366f1');
                $('#moduloColorText').val(row.color || '#6366f1');
                $('#moduloFechaInicio').val(row.fecha_inicio || '');
                $('#moduloFechaFin').val(row.fecha_fin || '');
                $('#moduloDocenteId').val(row.docente_id || '');
                $('#moduloDocenteNombre').val('');
                if (row.docente && row.docente.persona) {
                    const p = row.docente.persona;
                    const nombre = (p.nombres || '') + ' ' + (p.apellido_paterno || '') + ' ' + (p
                        .apellido_materno || '');
                    $('#moduloDocenteNombre').val(nombre.trim());
                    showDocentePreview(row.docente_id, nombre.trim(), p.carnet || '');
                } else {
                    hideDocentePreview();
                }
                $('#moduloFormTitle').html(
                    '<i class="ri-stack-line" style="color:#6366f1;"></i> Editar Módulo ' + row.n_modulo);
                openModal('modalModuloForm');
            });

            $(document).on('click', '.btn-delete-modulo', function() {
                const btn = $(this);
                idEliminarModulo = btn.data('id');
                $('#eliminarModuloNombre').text(btn.data('nombre'));
                openModal('modalEliminarModulo');
            });

            $('#btnConfirmarEliminarModulo').on('click', function() {
                if (!idEliminarModulo) return;
                setBtnLoading('#btnConfirmarEliminarModulo', true,
                    '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
                $.ajax({
                        url: '/admin/modulos/' + idEliminarModulo,
                        type: 'DELETE',
                        data: {
                            _token: CSRF
                        }
                    })
                    .done(function(r) {
                        closeModal('modalEliminarModulo');
                        if (tablaModulos) tablaModulos.ajax.reload();
                        toast('success', r.message || 'Módulo eliminado.');
                    })
                    .fail(function(xhr) {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'No se pudo eliminar.';
                        toast('error', msg);
                    })
                    .always(function() {
                        setBtnLoading('#btnConfirmarEliminarModulo', false,
                            '<i class="ri-delete-bin-line"></i> Eliminar');
                        idEliminarModulo = null;
                    });
            });

            function resetModuloForm() {
                $('#moduloId').val('');
                $('#moduloOfertaId').val('');
                $('#moduloNN').val('');
                $('#moduloNombre').val('');
                $('#moduloColor').val('#6366f1');
                $('#moduloColorText').val('#6366f1');
                $('#moduloFechaInicio').val('');
                $('#moduloFechaFin').val('');
                $('#moduloDocenteId').val('');
                $('#moduloDocenteNombre').val('');
                $('#moduloDocenteSearch').val('');
                $('#fbModuloNombre').html('');
                $('#fbModuloFechaInicio').html('');
                $('#fbModuloFechaFin').html('');
                $('#fbModuloDocente').html('');
                hideDocentePreview();
            }

            function showDocentePreview(id, nombre, carnet) {
                $('#moduloDocentePreview').show();
                $('#moduloDocenteAvatar').text(nombre ? nombre.charAt(0).toUpperCase() : 'D');
                $('#moduloDocenteNombreDisplay').text(nombre);
                $('#moduloDocenteCarnetDisplay').text('CI: ' + carnet);
            }

            function hideDocentePreview() {
                $('#moduloDocentePreview').hide();
                $('#moduloDocenteNombreDisplay').text('');
                $('#moduloDocenteCarnetDisplay').text('');
            }

            $('#btnLimpiarDocente').on('click', function() {
                $('#moduloDocenteId').val('');
                $('#moduloDocenteNombre').val('');
                $('#moduloDocenteSearch').val('');
                hideDocentePreview();
            });

            $('#moduloColor').on('input', function() {
                $('#moduloColorText').val($(this).val());
            });
            $('#moduloColorText').on('input', function() {
                const v = $(this).val();
                if (/^#[0-9A-Fa-f]{6}$/.test(v)) {
                    $('#moduloColor').val(v);
                }
            });

            // Busqueda de docente por carnet
            let docenteSearchTimeout = null;
            $('#moduloDocenteSearch').on('input', function() {
                clearTimeout(docenteSearchTimeout);
                const carnet = $(this).val().trim();
                if (carnet.length < 2) {
                    $('#moduloDocenteId').val('');
                    $('#moduloDocenteNombre').val('');
                    hideDocentePreview();
                    $('#fbModuloDocente').html('');
                    return;
                }
                docenteSearchTimeout = setTimeout(function() {
                    buscarDocentePorCarnet(carnet);
                }, 500);
            });

            function buscarDocentePorCarnet(carnet) {
                $.ajax({
                        url: '{{ route('admin.posgrads.modulos.buscar-docente') }}',
                        type: 'POST',
                        data: {
                            _token: CSRF,
                            carnet: carnet
                        }
                    })
                    .done(function(r) {
                        if (r.es_docente) {
                            $('#moduloDocenteId').val(r.docente.id);
                            $('#moduloDocenteNombre').val(r.docente.nombre);
                            showDocentePreview(r.docente.id, r.docente.nombre, r.docente.carnet);
                            $('#fbModuloDocente').html(
                                '<span style="color:#22c55e;font-size:0.75rem;"><i class="ri-checkbox-circle-fill"></i> Docente encontrado</span>'
                            );
                        } else if (r.persona_encontrada && !r.es_docente) {
                            pendingDocenteSearch = r.persona;
                            $('#confirmPersonaNombre').text(r.persona.nombre);
                            $('#confirmPersonaCarnet').text(r.persona.carnet);
                            $('#confirmPersonaId').val(r.persona.id);
                            closeModal('modalModuloForm');
                            openModal('modalConfirmarDocente');
                        } else {
                            pendingDocenteSearch = null;
                            closeModal('modalModuloForm');
                            $('#regDocenteCarnet').val(carnet);
                            openModal('modalRegistrarPersonaDocente');
                        }
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 404) {
                            pendingDocenteSearch = null;
                            closeModal('modalModuloForm');
                            $('#regDocenteCarnet').val(carnet);
                            openModal('modalRegistrarPersonaDocente');
                        } else {
                            toast('error', 'Error al buscar docente.');
                        }
                    });
            }

            $('#btnConfirmarDocente').on('click', function() {
                const personaId = $('#confirmPersonaId').val();
                if (!personaId) return;
                setBtnLoading('#btnConfirmarDocente', true,
                    '<span class="spinner-border spinner-border-sm"></span> Registrando…');
                $.ajax({
                        url: '{{ route('admin.posgrads.modulos.registrar-docente') }}',
                        type: 'POST',
                        data: {
                            _token: CSRF,
                            persona_id: personaId
                        }
                    })
                    .done(function(r) {
                        closeModal('modalConfirmarDocente');
                        $('#moduloDocenteId').val(r.data.id);
                        $('#moduloDocenteNombre').val(r.data.nombre);
                        showDocentePreview(r.data.id, r.data.nombre, '');
                        openModal('modalModuloForm');
                        toast('success', r.message || 'Docente registrado.');
                    })
                    .fail(function(xhr) {
                        toast('error', 'Error al registrar docente.');
                    })
                    .always(function() {
                        setBtnLoading('#btnConfirmarDocente', false,
                            '<i class="ri-check-line"></i> Sí, registrar como docente');
                    });
            });

            $('#btnRegistrarPersonaDocente').on('click', function() {
                const carnet = $('#regDocenteCarnet').val().trim();
                const nombres = $('#regDocenteNombres').val().trim();
                const apPaterno = $('#regDocenteApPaterno').val().trim();
                if (!carnet || !nombres || !apPaterno) {
                    toast('warning', 'Complete carnet, nombres y apellido paterno.');
                    return;
                }
                setBtnLoading('#btnRegistrarPersonaDocente', true,
                    '<span class="spinner-border spinner-border-sm"></span> Registrando…');
                $.ajax({
                        url: '{{ route('admin.posgrads.modulos.registrar-persona-docente') }}',
                        type: 'POST',
                        data: {
                            _token: CSRF,
                            carnet: carnet,
                            nombres: nombres,
                            apellido_paterno: apPaterno,
                            apellido_materno: $('#regDocenteApMaterno').val().trim(),
                            correo: $('#regDocenteCorreo').val().trim(),
                            celular: $('#regDocenteCelular').val().trim(),
                        }
                    })
                    .done(function(r) {
                        closeModal('modalRegistrarPersonaDocente');
                        $('#moduloDocenteId').val(r.data.id);
                        $('#moduloDocenteNombre').val(r.data.nombre);
                        showDocentePreview(r.data.id, r.data.nombre, r.data.carnet);
                        openModal('modalModuloForm');
                        toast('success', r.message || 'Persona y docente registrados.');
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errs = xhr.responseJSON.errors;
                            if (errs.carnet) toast('error', 'Carnet: ' + errs.carnet[0]);
                            if (errs.nombres) toast('error', 'Nombres: ' + errs.nombres[0]);
                            if (errs.apellido_paterno) toast('error', 'Apellido Paterno: ' + errs
                                .apellido_paterno[0]);
                        } else {
                            toast('error', 'Error al registrar.');
                        }
                    })
                    .always(function() {
                        setBtnLoading('#btnRegistrarPersonaDocente', false,
                            '<i class="ri-user-add-line"></i> Registrar y Asignar');
                    });
            });

            // Guardar modulo
            $('#btnGuardarModulo').on('click', function() {
                const id = $('#moduloId').val();
                const isEdit = id !== '';
                const nombre = $('#moduloNombre').val().trim();
                const fechaInicio = $('#moduloFechaInicio').val();
                const fechaFin = $('#moduloFechaFin').val();

                if (!nombre) {
                    toast('warning', 'El nombre del módulo es obligatorio.');
                    return;
                }
                if (!fechaInicio) {
                    toast('warning', 'La fecha de inicio es obligatoria.');
                    return;
                }
                if (!fechaFin) {
                    toast('warning', 'La fecha de fin es obligatoria.');
                    return;
                }
                if (fechaFin < fechaInicio) {
                    toast('warning', 'La fecha de fin debe ser mayor o igual al inicio.');
                    return;
                }

                setBtnLoading('#btnGuardarModulo', true, 'Guardando…');
                const data = {
                    _token: CSRF,
                    ofertas_academica_id: $('#moduloOfertaId').val(),
                    n_modulo: parseInt($('#moduloNN').val()),
                    nombre: nombre,
                    color: $('#moduloColor').val(),
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin,
                    docente_id: $('#moduloDocenteId').val() || null,
                };

                $.ajax({
                        url: isEdit ? '/admin/modulos/' + id :
                            '{{ route('admin.posgrads.modulos.guardar') }}',
                        type: isEdit ? 'PUT' : 'POST',
                        data: data,
                        headers: {
                            'X-HTTP-Method-Override': isEdit ? 'PUT' : 'POST'
                        }
                    })
                    .done(function(r) {
                        closeModal('modalModuloForm');
                        if (tablaModulos) tablaModulos.ajax.reload();
                        toast('success', r.message || (isEdit ? 'Módulo actualizado.' :
                            'Módulo registrado.'));
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errs = xhr.responseJSON.errors;
                            if (errs.nombre) toast('error', errs.nombre[0]);
                            if (errs.fecha_inicio) toast('error', errs.fecha_inicio[0]);
                            if (errs.fecha_fin) toast('error', errs.fecha_fin[0]);
                        } else {
                            toast('error', 'Error al guardar el módulo.');
                        }
                    })
                    .always(function() {
                        setBtnLoading('#btnGuardarModulo', false, '<i class="ri-save-line"></i> Guardar');
                    });
            });

            $('#btnCancelarModulo').on('click', function() {
                closeModal('modalModuloForm');
            });

            $('#modalModulos').on('hidden.bs.modal', function() {
                if (tablaModulos) {
                    tablaModulos.destroy();
                    tablaModulos = null;
                }
            });

            $(document).ready(init);
        })();
    </script>

    {{-- Modal Gestion de Modulos --}}
    <div class="modal fade" id="modalModulos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:900px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-stack-line" style="color:#6366f1;"></i> Gestion de Módulos -
                        <span id="modulosOfertaCodigo"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modulosOfertaId">
                    <input type="hidden" id="modulosNModulos">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="badge bg-primary" id="modulosCountBadge">0 / 0 módulos</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-success" id="btnNuevoModulo"><i
                                class="ri-add-line"></i> Agregar Módulo</button>
                    </div>
                    <div class="table-responsive">
                        <table id="tabla-modulos" class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:50px;">N°</th>
                                    <th>Nombre</th>
                                    <th>Docente</th>
                                    <th>Estado</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th class="text-center" style="width:90px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="ri-close-line"></i> Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Crear/Editar Modulo --}}
    <div class="modal fade" id="modalModuloForm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moduloFormTitle"><i class="ri-stack-line" style="color:#6366f1;"></i>
                        Nuevo Módulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="moduloId">
                    <input type="hidden" id="moduloOfertaId">
                    <div class="mb-form">
                        <div class="col-fields-2">
                            <div>
                                <label class="form-label"><i class="ri-hashtag" style="color:#6366f1;"></i> N°
                                    Módulo</label>
                                <input type="text" class="form-control" id="moduloNN" readonly
                                    style="background:rgba(99,102,241,0.06);border-color:rgba(99,102,241,0.2);color:#6366f1;font-weight:700;">
                            </div>
                            <div>
                                <label class="form-label"><i class="ri-palette-line" style="color:#6366f1;"></i>
                                    Color</label>
                                <div class="d-flex gap-2">
                                    <input type="color" class="form-control form-control-color" id="moduloColor"
                                        value="#6366f1" style="width:48px;height:38px;">
                                    <input type="text" class="form-control" id="moduloColorText" value="#6366f1"
                                        maxlength="7" style="width:100px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-form">
                        <label class="form-label"><i class="ri-file-text-line" style="color:#6366f1;"></i> Nombre del
                            Módulo <span class="req">*</span></label>
                        <input type="text" class="form-control" id="moduloNombre"
                            placeholder="Ej: Fundamentos de Investigación" maxlength="200">
                        <div class="field-feedback" id="fbModuloNombre"></div>
                    </div>
                    <div class="mb-form">
                        <div class="col-fields-2">
                            <div>
                                <label class="form-label"><i class="ri-calendar-event-line" style="color:#6366f1;"></i>
                                    Fecha Inicio <span class="req">*</span></label>
                                <input type="date" class="form-control" id="moduloFechaInicio">
                                <div class="field-feedback" id="fbModuloFechaInicio"></div>
                            </div>
                            <div>
                                <label class="form-label"><i class="ri-calendar-check-line" style="color:#6366f1;"></i>
                                    Fecha Fin <span class="req">*</span></label>
                                <input type="date" class="form-control" id="moduloFechaFin">
                                <div class="field-feedback" id="fbModuloFechaFin"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-form">
                        <label class="form-label"><i class="ri-user-star-line" style="color:#6366f1;"></i>
                            Docente</label>
                        <div class="d-flex gap-2 align-items-start">
                            <div class="flex-grow-1">
                                <input type="text" class="form-control" id="moduloDocenteSearch"
                                    placeholder="Buscar por carnet del docente..." autocomplete="off">
                                <input type="hidden" id="moduloDocenteId">
                                <input type="hidden" id="moduloDocenteNombre">
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm" id="btnLimpiarDocente"
                                title="Quitar docente"><i class="ri-close-line"></i></button>
                        </div>
                        <div id="moduloDocentePreview" class="mt-2" style="display:none;">
                            <div class="d-flex align-items-center gap-2 p-2 rounded"
                                style="background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.15);">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:36px;height:36px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;font-weight:700;font-size:0.85rem;"
                                    id="moduloDocenteAvatar">D</div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.85rem;" id="moduloDocenteNombreDisplay">
                                    </div>
                                    <div style="font-size:0.72rem;color:var(--d-muted);" id="moduloDocenteCarnetDisplay">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field-feedback" id="fbModuloDocente"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-cancel" id="btnCancelarModulo"><i
                            class="ri-close-line"></i> Cancelar</button>
                    <button type="button" class="btn btn-modal-submit" id="btnGuardarModulo"><i
                            class="ri-save-line"></i> Guardar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Confirmar Asignar como Docente --}}
    <div class="modal fade" id="modalConfirmarDocente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:450px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-question-line" style="color:#f59e0b;"></i> Confirmar Asignación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="ri-user-star-line" style="font-size:3rem;color:#f59e0b;"></i>
                    </div>
                    <p class="mb-2">Se encontró a la persona:</p>
                    <h6 class="fw-bold" id="confirmPersonaNombre"></h6>
                    <p class="text-muted" style="font-size:0.85rem;">Carnet: <span id="confirmPersonaCarnet"></span></p>
                    <p class="mt-3" style="font-size:0.9rem;">Esta persona no está registrada como
                        docente.<br><strong>¿Desea registrarla como docente y asignarla?</strong></p>
                    <input type="hidden" id="confirmPersonaId">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="ri-close-line"></i> No, cancelar</button>
                    <button type="button" class="btn btn-success" id="btnConfirmarDocente"><i
                            class="ri-check-line"></i> Sí, registrar como docente</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Registrar Nueva Persona como Docente --}}
    <div class="modal fade" id="modalRegistrarPersonaDocente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:550px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-user-add-line" style="color:#10b981;"></i> Registrar Nueva
                        Persona como Docente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-form">
                        <label class="form-label"><i class="ri-id-card-line" style="color:#10b981;"></i> Carnet <span
                                class="req">*</span></label>
                        <input type="text" class="form-control" id="regDocenteCarnet" placeholder="Ej: 12345678"
                            maxlength="20">
                        <div class="field-feedback" id="fbRegDocenteCarnet"></div>
                    </div>
                    <div class="mb-form">
                        <label class="form-label"><i class="ri-user-line" style="color:#10b981;"></i> Nombres <span
                                class="req">*</span></label>
                        <input type="text" class="form-control" id="regDocenteNombres" placeholder="Nombres"
                            maxlength="100">
                        <div class="field-feedback" id="fbRegDocenteNombres"></div>
                    </div>
                    <div class="mb-form">
                        <div class="col-fields-2">
                            <div>
                                <label class="form-label"><i class="ri-user-line" style="color:#10b981;"></i> Apellido
                                    Paterno <span class="req">*</span></label>
                                <input type="text" class="form-control" id="regDocenteApPaterno"
                                    placeholder="Apellido Paterno" maxlength="100">
                                <div class="field-feedback" id="fbRegDocenteApPaterno"></div>
                            </div>
                            <div>
                                <label class="form-label"><i class="ri-user-line" style="color:#10b981;"></i> Apellido
                                    Materno</label>
                                <input type="text" class="form-control" id="regDocenteApMaterno"
                                    placeholder="Apellido Materno" maxlength="100">
                            </div>
                        </div>
                    </div>
                    <div class="mb-form">
                        <div class="col-fields-2">
                            <div>
                                <label class="form-label"><i class="ri-mail-line" style="color:#10b981;"></i>
                                    Correo</label>
                                <input type="email" class="form-control" id="regDocenteCorreo"
                                    placeholder="correo@dominio.com" maxlength="150">
                            </div>
                            <div>
                                <label class="form-label"><i class="ri-phone-line" style="color:#10b981;"></i>
                                    Celular</label>
                                <input type="text" class="form-control" id="regDocenteCelular"
                                    placeholder="Ej: 70712345" maxlength="20">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="ri-close-line"></i> Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnRegistrarPersonaDocente"><i
                            class="ri-user-add-line"></i> Registrar y Asignar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Eliminar Modulo --}}
    <div class="modal fade" id="modalEliminarModulo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-delete-bin-line" style="color:#ef4444;"></i> Eliminar Módulo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-1">¿Está seguro de eliminar el módulo?</p>
                    <h6 class="fw-bold" id="eliminarModuloNombre"></h6>
                    <input type="hidden" id="eliminarModuloId">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="ri-close-line"></i> Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminarModulo"><i
                            class="ri-delete-bin-line"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
