@extends('layouts.master')
@section('title')
    Posgrados
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

        .row-actions {
            display: flex;
            gap: 0.5rem;
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

        #modalModulos input[type="text"],
        #modalModulos input[type="date"],
        #modalModulos input[type="number"],
        #modalModulos select,
        #modalModulos textarea {
            pointer-events: auto !important;
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            user-select: text !important;
            cursor: text !important;
            background-color: #fff !important;
        }

        #modalModulos .mod-docente-search {
            pointer-events: auto !important;
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            user-select: text !important;
            cursor: text !important;
            background-color: #fff !important;
            opacity: 1 !important;
            color: #000 !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
        }

        #modalModulos .mod-docente-search:focus {
            outline: 2px solid #0d6efd !important;
            outline-offset: 1px !important;
        }

        .modal-dialog-scrollable .modal-header {
            flex-shrink: 0;
        }

        /* Select con botón agregar */
        .select-with-add {
            display: flex;
            gap: 0.5rem;
            align-items: flex-end;
        }

        .select-with-add .select-wrap {
            flex: 1;
        }

        .select-with-add .form-select {
            padding-right: 2.5rem !important;
        }

        .btn-add-quick {
            width: 40px;
            height: 40px;
            min-width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(252, 123, 4, 0.12), rgba(252, 123, 4, 0.06));
            border: 1px solid rgba(252, 123, 4, 0.2);
            border-radius: 8px;
            color: #fc7b04;
            font-size: 1.15rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-add-quick:hover {
            background: linear-gradient(135deg, rgba(252, 123, 4, 0.2), rgba(252, 123, 4, 0.1));
            border-color: rgba(252, 123, 4, 0.35);
            transform: translateY(-1px);
            box-shadow: 0 3px 12px rgba(252, 123, 4, 0.12);
        }

        .btn-add-quick:active {
            transform: translateY(0);
        }

        /* Quick add modal */
        .quick-add-body {
            padding: 1.25rem;
        }

        .quick-add-field {
            margin-bottom: 1rem;
        }

        .quick-add-field:last-child {
            margin-bottom: 0;
        }

        .quick-add-footer {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--d-card-border);
        }

        /* Estado toggle switch */
        .estado-toggle {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            user-select: none;
        }

        .estado-toggle input {
            display: none;
        }

        .estado-slider {
            width: 40px;
            height: 22px;
            background: rgba(154, 73, 4, 0.15);
            border-radius: 11px;
            position: relative;
            transition: all 0.3s ease;
            border: 1px solid rgba(154, 73, 4, 0.2);
        }

        .estado-slider::before {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            left: 2px;
            top: 2px;
            background: rgba(154, 73, 4, 0.4);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .estado-toggle input:checked+.estado-slider {
            background: rgba(252, 123, 4, 0.25);
            border-color: rgba(252, 123, 4, 0.4);
        }

        .estado-toggle input:checked+.estado-slider::before {
            transform: translateX(18px);
            background: #fc7b04;
        }

        .estado-label-text {
            font-size: 0.78rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .estado-label-text.active {
            color: #22c55e;
        }

        .estado-label-text.inactive {
            color: #ef4444;
        }

        /* Estado badge en tabla */
        .badge-estado {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge-estado.activo {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .badge-estado.inactivo {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .badge-estado-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .badge-estado.activo .badge-estado-dot {
            background: #22c55e;
        }

        .badge-estado.inactivo .badge-estado-dot {
            background: #ef4444;
        }

        /* Ofertas modal */
        .ofertas-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background: var(--d-edit-bg);
            border-bottom: 1px solid var(--d-card-border);
        }

        .ofertas-posgrado-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            font-size: 0.92rem;
        }

        .ofertas-posgrado-info i {
            color: #fc7b04;
            font-size: 1.15rem;
        }

        .btn-oferta-nueva {
            background: linear-gradient(135deg, var(--amber-400), var(--amber-600));
            border: none;
            color: var(--obsidian);
            padding: 0.45rem 1rem;
            font-weight: 600;
            font-size: 0.82rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-oferta-nueva:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(252, 123, 4, 0.25);
        }

        .ofertas-table-wrap {
            padding: 1rem;
            max-height: 500px;
            overflow-y: auto;
        }

        /* Botón ofertas en tabla */
        .btn-action-ofertas {
            color: #22c55e !important;
        }

        .btn-action-ofertas:hover {
            background: rgba(34, 197, 94, 0.1) !important;
        }

        /* Ofertas form sections */
        .oferta-section-title {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--d-muted);
            padding: 0.6rem 0 0.4rem;
            margin-bottom: 0.25rem;
            border-bottom: 1px solid var(--d-card-border);
        }

        .oferta-section-title i {
            color: #fc7b04;
            font-size: 1rem;
        }

        /* Searchable select */
        .searchable-select-wrap {
            position: relative;
        }

        .searchable-select-wrap .form-control {
            padding-right: 2rem;
        }

        .searchable-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 200px;
            overflow-y: auto;
            background: var(--d-modal-bg);
            border: 1px solid var(--d-card-border);
            border-radius: 8px;
            z-index: 100;
            display: none;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .searchable-dropdown.show {
            display: block;
        }

        .searchable-dropdown-item {
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background 0.15s;
            border-bottom: 1px solid rgba(154, 73, 4, 0.06);
        }

        .searchable-dropdown-item:last-child {
            border-bottom: none;
        }

        .searchable-dropdown-item:hover,
        .searchable-dropdown-item.active {
            background: rgba(252, 123, 4, 0.1);
            color: #fc7b04;
        }

        .searchable-dropdown-item .dd-sub {
            font-size: 0.75rem;
            color: var(--d-muted);
            display: block;
        }

        .searchable-dropdown-empty {
            padding: 0.75rem;
            text-align: center;
            color: var(--d-muted);
            font-size: 0.82rem;
        }

        /* File upload zone */
        .file-upload-zone {
            border: 2px dashed rgba(252, 123, 4, 0.15);
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
            background: rgba(252, 123, 4, 0.02);
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-upload-zone:hover {
            border-color: rgba(252, 123, 4, 0.35);
            background: rgba(252, 123, 4, 0.04);
        }

        .file-upload-zone.has-file {
            border-color: rgba(34, 197, 94, 0.3);
            background: rgba(34, 197, 94, 0.03);
        }

        .file-preview-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.4rem;
            color: var(--d-muted);
            font-size: 0.82rem;
        }

        .file-preview-area i {
            font-size: 2rem;
            color: rgba(252, 123, 4, 0.3);
        }

        .file-preview-area img {
            max-width: 100%;
            max-height: 100px;
            border-radius: 6px;
            object-fit: cover;
        }

        .file-preview-area .file-name {
            font-size: 0.75rem;
            color: var(--d-title);
            font-weight: 500;
            word-break: break-all;
        }

        .file-preview-area .file-remove {
            font-size: 0.75rem;
            color: #ef4444;
            cursor: pointer;
            font-weight: 500;
        }

        .file-preview-area .file-remove:hover {
            text-decoration: underline;
        }

        /* Color preview */
        .color-preview-box {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            border: 2px solid rgba(154, 73, 4, 0.15);
            transition: background 0.2s ease;
            flex-shrink: 0;
        }

        /* Filtros */
        .dept-filters {
            background: var(--d-edit-bg);
            border-bottom: 1px solid var(--d-card-border);
            padding: 1rem 1.5rem;
        }

        .dept-filters-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr) auto;
            gap: 0.75rem;
            align-items: end;
        }

        .dept-filter-item {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .dept-filter-actions {
            justify-self: end;
        }

        .dept-filter-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--d-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dept-filter-select {
            background: var(--d-input-bg) !important;
            border: 2px solid var(--d-input-border) !important;
            border-radius: 10px !important;
            padding: 0.5rem 2.5rem 0.5rem 0.8rem !important;
            font-size: 0.82rem !important;
            color: var(--d-input-color) !important;
            transition: all 0.2s ease !important;
            font-weight: 500 !important;
            width: 100%;
        }

        .dept-filter-select:focus {
            border-color: #fc7b04 !important;
            box-shadow: 0 0 0 3px rgba(252, 123, 4, 0.10) !important;
            outline: none !important;
        }

        .btn-filter-clear {
            background: var(--d-cancel-bg) !important;
            border: 1px solid var(--d-cancel-border) !important;
            color: var(--d-cancel-color) !important;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.82rem;
            border-radius: 10px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            cursor: pointer;
            white-space: nowrap;
            height: 40px;
        }

        .btn-filter-clear:hover {
            background: rgba(154, 73, 4, 0.15) !important;
            color: var(--d-title) !important;
            border-color: rgba(154, 73, 4, 0.30) !important;
            transform: translateY(-1px);
        }

        @media (max-width: 1200px) {
            .dept-filters-grid {
                grid-template-columns: repeat(3, 1fr) auto;
            }
        }

        @media (max-width: 992px) {
            .dept-filters-grid {
                grid-template-columns: 1fr 1fr;
            }

            .dept-filter-actions {
                grid-column: 1 / -1;
                justify-self: start;
            }
        }

        @media (max-width: 576px) {
            .dept-filters-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="dept-page-header">
        <div class="container-fluid">
            <div class="dph-inner">
                <div class="dph-left">
                    <div class="dph-icon-wrap"><i class="ri-graduation-cap-line"></i></div>
                    <div class="dph-text-block">
                        <h1 class="dph-title">Posgrados</h1>
                        <p class="dph-desc">Gestión y administración de posgrados</p>
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
                    <div class="dph-stat-card">
                        <div class="dph-stat-icon" style="color:#22c55e;"><i class="ri-checkbox-circle-line"></i></div>
                        <div>
                            <div class="dph-stat-num" id="stat-activos">—</div>
                            <div class="dph-stat-label">Activos</div>
                        </div>
                    </div>
                    <button type="button" class="dph-btn-new" id="btn-nuevo"><i class="ri-add-line"></i> <span>Nuevo
                            Posgrado</span></button>
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
                                <h5 class="dept-title">Listado de Posgrados</h5>
                                <p class="dept-subtitle">Consulta, edita o elimina los registros existentes</p>
                            </div>
                        </div>
                    </div>
                    <div class="dept-card-body">
                        <div class="dept-filters">
                            <div class="dept-filters-grid">
                                <div class="dept-filter-item">
                                    <label class="dept-filter-label" for="filtro-convenio">Convenio</label>
                                    <select class="dept-filter-select" id="filtro-convenio">
                                        <option value="">Todos</option>
                                        @foreach ($convenios as $c)
                                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="dept-filter-item">
                                    <label class="dept-filter-label" for="filtro-area">Área</label>
                                    <select class="dept-filter-select" id="filtro-area">
                                        <option value="">Todas</option>
                                        @foreach ($areas as $a)
                                            <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="dept-filter-item">
                                    <label class="dept-filter-label" for="filtro-tipo">Tipo</label>
                                    <select class="dept-filter-select" id="filtro-tipo">
                                        <option value="">Todos</option>
                                        @foreach ($tipos as $t)
                                            <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="dept-filter-item">
                                    <label class="dept-filter-label" for="filtro-estado">Estado</label>
                                    <select class="dept-filter-select" id="filtro-estado">
                                        <option value="">Todos</option>
                                        <option value="1">Activo</option>
                                        <option value="0">No Activo</option>
                                    </select>
                                </div>
                                <div class="dept-filter-item dept-filter-actions">
                                    <label class="dept-filter-label">&nbsp;</label>
                                    <button type="button" class="btn btn-filter-clear" id="btn-limpiar-filtros">
                                        <i class="ri-close-circle-line"></i> Limpiar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <table id="tabla-posgrads" class="dept-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Convenio</th>
                                    <th>Área</th>
                                    <th>Tipo</th>
                                    <th>Duración</th>
                                    <th class="text-center" style="width:90px;">Estado</th>
                                    <th class="text-center" style="width:140px;">Acciones</th>
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
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:780px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-add-circle-line"></i> Nuevo Posgrado</h5><button type="button"
                        class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formCrear" novalidate autocomplete="off">
                    <div class="modal-body">
                        <div class="mb-form">
                            <label for="nombreCrear" class="form-label"><i class="ri-book-2-line"
                                    style="color:#fc7b04;"></i> Nombre del Posgrado <span class="req">*</span></label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="nombreCrear"
                                    placeholder="Ej: Maestría en..." maxlength="200" autocomplete="off">
                                <span class="validation-icon" id="iconCrear"></span>
                            </div>
                            <div class="field-feedback" id="fbCrear"></div>
                        </div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="creditajeCrear" class="form-label"><i class="ri-medal-line"
                                        style="color:#fc7b04;"></i> Creditaje</label>
                                <input type="number" class="form-control" id="creditajeCrear" placeholder="0"
                                    min="0" value="0">
                            </div>
                            <div>
                                <label for="cargaHorariaCrear" class="form-label"><i class="ri-time-line"
                                        style="color:#fc7b04;"></i> Carga Horaria</label>
                                <input type="number" class="form-control" id="cargaHorariaCrear" placeholder="0"
                                    min="0" value="0">
                            </div>
                            <div>
                                <label for="duracionNumeroCrear" class="form-label"><i class="ri-calendar-line"
                                        style="color:#fc7b04;"></i> Duración</label>
                                <input type="number" class="form-control" id="duracionNumeroCrear" placeholder="0"
                                    min="0" value="0">
                            </div>
                        </div>
                        <div class="mb-form">
                            <label for="duracionUnidadCrear" class="form-label"><i class="ri-time-line"
                                    style="color:#fc7b04;"></i> Unidad de Duración</label>
                            <select class="form-select" id="duracionUnidadCrear">
                                <option value="Horas">Horas</option>
                                <option value="Días">Días</option>
                                <option value="Semanas">Semanas</option>
                                <option value="Meses">Meses</option>
                            </select>
                        </div>
                        <div class="mb-form">
                            <label for="convenioCrear" class="form-label"><i class="ri-handshake-line"
                                    style="color:#fc7b04;"></i> Convenio <span class="req">*</span></label>
                            <select class="form-select" id="convenioCrear">
                                <option value="">Seleccionar...</option>
                                @foreach ($convenios as $c)
                                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-fields-2 mb-form">
                            <div>
                                <label for="areaCrear" class="form-label"><i class="ri-folder-line"
                                        style="color:#fc7b04;"></i> Área <span class="req">*</span></label>
                                <div class="select-with-add">
                                    <div class="select-wrap">
                                        <select class="form-select" id="areaCrear">
                                            <option value="">Seleccionar...</option>
                                            @foreach ($areas as $a)
                                                <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="btn-add-quick" id="btnQuickArea"
                                        title="Agregar nueva área">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label for="tipoCrear" class="form-label"><i class="ri-price-tag-3-line"
                                        style="color:#fc7b04;"></i> Tipo <span class="req">*</span></label>
                                <div class="select-with-add">
                                    <div class="select-wrap">
                                        <select class="form-select" id="tipoCrear">
                                            <option value="">Seleccionar...</option>
                                            @foreach ($tipos as $t)
                                                <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="btn-add-quick" id="btnQuickTipo"
                                        title="Agregar nuevo tipo">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-form">
                            <label for="dirigidoCrear" class="form-label"><i class="ri-user-star-line"
                                    style="color:#fc7b04;"></i> Dirigido a</label>
                            <textarea class="form-control" id="dirigidoCrear" rows="3" maxlength="255"
                                placeholder="Ej: Profesionales con título universitario"
                                style="padding: 0.55rem 0.9rem !important; resize: vertical;"></textarea>
                        </div>
                        <div class="mb-form">
                            <label for="objetivoCrear" class="form-label"><i class="ri-target-line"
                                    style="color:#fc7b04;"></i> Objetivo</label>
                            <textarea class="form-control" id="objetivoCrear" rows="2" placeholder="Objetivo del posgrado"
                                style="padding: 0.55rem 0.9rem !important; resize: vertical;"></textarea>
                        </div>
                        <div class="mb-form">
                            <label class="form-label"><i class="ri-toggle-line" style="color:#fc7b04;"></i>
                                Estado</label>
                            <div class="estado-toggle">
                                <input type="checkbox" id="estadoCrear" checked>
                                <span class="estado-slider"></span>
                                <span class="estado-label-text active" id="estadoCrearLabel">Activo</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i
                                class="ri-close-line me-1"></i>Cancelar</button>
                        <button type="button" class="btn btn-modal-submit" id="btnGuardar" disabled><i
                                class="ri-save-line"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Editar --}}
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:780px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-edit-2-line"></i> Editar Posgrado</h5><button type="button"
                        class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formEditar" novalidate autocomplete="off">
                    <input type="hidden" id="idEditar">
                    <div class="modal-body">
                        <div class="mb-form">
                            <label for="nombreEditar" class="form-label"><i class="ri-book-2-line"
                                    style="color:#fc7b04;"></i> Nombre del Posgrado <span class="req">*</span></label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="nombreEditar"
                                    placeholder="Ej: Maestría en..." maxlength="200" autocomplete="off">
                                <span class="validation-icon" id="iconEditar"></span>
                            </div>
                            <div class="field-feedback" id="fbEditar"></div>
                        </div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="creditajeEditar" class="form-label"><i class="ri-medal-line"
                                        style="color:#fc7b04;"></i> Creditaje</label>
                                <input type="number" class="form-control" id="creditajeEditar" placeholder="0"
                                    min="0">
                            </div>
                            <div>
                                <label for="cargaHorariaEditar" class="form-label"><i class="ri-time-line"
                                        style="color:#fc7b04;"></i> Carga Horaria</label>
                                <input type="number" class="form-control" id="cargaHorariaEditar" placeholder="0"
                                    min="0">
                            </div>
                            <div>
                                <label for="duracionNumeroEditar" class="form-label"><i class="ri-calendar-line"
                                        style="color:#fc7b04;"></i> Duración</label>
                                <input type="number" class="form-control" id="duracionNumeroEditar" placeholder="0"
                                    min="0">
                            </div>
                        </div>
                        <div class="mb-form">
                            <label for="duracionUnidadEditar" class="form-label"><i class="ri-time-line"
                                    style="color:#fc7b04;"></i> Unidad de Duración</label>
                            <select class="form-select" id="duracionUnidadEditar">
                                <option value="Horas">Horas</option>
                                <option value="Días">Días</option>
                                <option value="Semanas">Semanas</option>
                                <option value="Meses">Meses</option>
                            </select>
                        </div>
                        <div class="mb-form">
                            <label for="convenioEditar" class="form-label"><i class="ri-handshake-line"
                                    style="color:#fc7b04;"></i> Convenio <span class="req">*</span></label>
                            <select class="form-select" id="convenioEditar">
                                <option value="">Seleccionar...</option>
                                @foreach ($convenios as $c)
                                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-fields-2 mb-form">
                            <div>
                                <label for="areaEditar" class="form-label"><i class="ri-folder-line"
                                        style="color:#fc7b04;"></i> Área <span class="req">*</span></label>
                                <div class="select-with-add">
                                    <div class="select-wrap">
                                        <select class="form-select" id="areaEditar">
                                            <option value="">Seleccionar...</option>
                                            @foreach ($areas as $a)
                                                <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="btn-add-quick" id="btnQuickAreaEditar"
                                        title="Agregar nueva área">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label for="tipoEditar" class="form-label"><i class="ri-price-tag-3-line"
                                        style="color:#fc7b04;"></i> Tipo <span class="req">*</span></label>
                                <div class="select-with-add">
                                    <div class="select-wrap">
                                        <select class="form-select" id="tipoEditar">
                                            <option value="">Seleccionar...</option>
                                            @foreach ($tipos as $t)
                                                <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="btn-add-quick" id="btnQuickTipoEditar"
                                        title="Agregar nuevo tipo">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-form">
                            <label for="dirigidoEditar" class="form-label"><i class="ri-user-star-line"
                                    style="color:#fc7b04;"></i> Dirigido a</label>
                            <textarea class="form-control" id="dirigidoEditar" rows="3" maxlength="255"
                                placeholder="Ej: Professionals with university degree"
                                style="padding: 0.55rem 0.9rem !important; resize: vertical;"></textarea>
                        </div>
                        <div class="mb-form">
                            <label for="objetivoEditar" class="form-label"><i class="ri-target-line"
                                    style="color:#fc7b04;"></i> Objetivo</label>
                            <textarea class="form-control" id="objetivoEditar" rows="2" placeholder="Objetivo del posgrado"
                                style="padding: 0.55rem 0.9rem !important; resize: vertical;"></textarea>
                        </div>
                        <div class="mb-form">
                            <label class="form-label"><i class="ri-toggle-line" style="color:#fc7b04;"></i>
                                Estado</label>
                            <div class="estado-toggle">
                                <input type="checkbox" id="estadoEditar" checked>
                                <span class="estado-slider"></span>
                                <span class="estado-label-text active" id="estadoEditarLabel">Activo</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i
                                class="ri-close-line me-1"></i>Cancelar</button>
                        <button type="button" class="btn btn-modal-submit" id="btnActualizar" disabled><i
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
                    <h5 class="modal-title"><i class="ri-error-warning-line"></i> Confirmar Eliminación</h5><button
                        type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="delete-warning-box">
                        <div class="delete-icon-ring"><i class="ri-delete-bin-5-line"></i></div>
                        <p class="delete-msg-primary">¿Eliminar posgrado?</p>
                        <p class="delete-msg-name"><strong id="nombreEliminar"></strong></p>
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

    {{-- Quick Add: Área --}}
    <div class="modal fade" id="modalQuickArea" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h5 class="modal-title" style="font-size:1.05rem;"><i class="ri-folder-add-line"
                            style="color:#fc7b04;"></i> Nueva Área</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="quick-add-body">
                    <div class="quick-add-field">
                        <label for="quickAreaNombre" class="form-label" style="font-size:0.82rem;">Nombre del Área <span
                                class="req">*</span></label>
                        <input type="text" class="form-control" id="quickAreaNombre"
                            placeholder="Ej: Ingeniería, Salud..." maxlength="100" autocomplete="off">
                        <div class="field-feedback" id="fbQuickArea"></div>
                    </div>
                </div>
                <div class="quick-add-footer">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"
                        style="font-size:0.82rem; padding:0.45rem 0.85rem;"><i
                            class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="button" class="btn btn-modal-submit" id="btnGuardarQuickArea"
                        style="font-size:0.82rem; padding:0.45rem 0.85rem;"><i class="ri-save-line"></i> Guardar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Add: Tipo --}}
    <div class="modal fade" id="modalQuickTipo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h5 class="modal-title" style="font-size:1.05rem;"><i class="ri-price-tag-add-line"
                            style="color:#fc7b04;"></i> Nuevo Tipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="quick-add-body">
                    <div class="quick-add-field">
                        <label for="quickTipoNombre" class="form-label" style="font-size:0.82rem;">Nombre del Tipo <span
                                class="req">*</span></label>
                        <input type="text" class="form-control" id="quickTipoNombre"
                            placeholder="Ej: Maestría, Doctorado..." maxlength="100" autocomplete="off">
                        <div class="field-feedback" id="fbQuickTipo"></div>
                    </div>
                    <div class="quick-add-field">
                        <label for="quickTipoDesc" class="form-label" style="font-size:0.82rem;">Descripción</label>
                        <textarea class="form-control" id="quickTipoDesc" rows="2" placeholder="Descripción opcional..."
                            maxlength="500" style="resize:vertical;"></textarea>
                    </div>
                </div>
                <div class="quick-add-footer">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"
                        style="font-size:0.82rem; padding:0.45rem 0.85rem;"><i
                            class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="button" class="btn btn-modal-submit" id="btnGuardarQuickTipo"
                        style="font-size:0.82rem; padding:0.45rem 0.85rem;"><i class="ri-save-line"></i> Guardar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Ofertas Académicas --}}
    <div class="modal fade" id="modalOfertas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:1100px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-book-open-line" style="color:#fc7b04;"></i> Ofertas Académicas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ofertas-header-bar">
                        <div class="ofertas-posgrado-info">
                            <i class="ri-graduation-cap-line"></i>
                            <span id="ofertasPosgradoNombre"></span>
                        </div>
                        <button type="button" class="btn btn-oferta-nueva" id="btnNuevaOferta">
                            <i class="ri-add-line"></i> Nueva Oferta
                        </button>
                    </div>
                    <div class="ofertas-table-wrap">
                        <table id="tabla-ofertas" class="dept-table" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Programa</th>
                                    <th>Fase</th>
                                    <th>Sucursal</th>
                                    <th>Modalidad</th>
                                    <th>Gestión</th>
                                    <th>Inicio</th>
                                    <th class="text-center" style="width:90px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Crear/Editar Oferta --}}
    <div class="modal fade" id="modalOfertaForm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:860px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ofertaFormTitle"><i class="ri-book-open-line"
                            style="color:#fc7b04;"></i> Nueva Oferta Académica</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formOferta" novalidate autocomplete="off">
                    <div class="modal-body">
                        <input type="hidden" id="ofertaId">
                        <input type="hidden" id="ofertaPosgradoId">

                        <div class="oferta-section-title"><i class="ri-information-line"></i> Información General</div>
                        <div class="col-fields-2 mb-form">
                            <div>
                                <label for="ofertaCodigo" class="form-label"><i class="ri-hashtag"
                                        style="color:#fc7b04;"></i> Código <span class="req">*</span></label>
                                <input type="text" class="form-control" id="ofertaCodigo"
                                    placeholder="Ej: OF-2026-001" maxlength="50" autocomplete="off">
                                <div class="field-feedback" id="fbOfertaCodigo"></div>
                            </div>
                            <div>
                                <label for="ofertaGestion" class="form-label"><i class="ri-calendar-event-line"
                                        style="color:#fc7b04;"></i> Gestión <span class="req">*</span></label>
                                <input type="number" class="form-control" id="ofertaGestion" placeholder="2026"
                                    min="2000">
                                <div class="field-feedback" id="fbOfertaGestion"></div>
                            </div>
                        </div>

                        <div class="oferta-section-title"><i class="ri-settings-3-line"></i> Configuración Académica</div>
                        <div class="mb-form">
                            <label for="ofertaProgramaText" class="form-label"><i class="ri-file-list-3-line"
                                    style="color:#fc7b04;"></i> Programa <span class="req">*</span></label>
                            <input type="text" class="form-control" id="ofertaProgramaText" readonly
                                placeholder="Se genera automáticamente" maxlength="200" autocomplete="off">
                            <div class="field-feedback" id="fbOfertaPrograma"></div>
                            <input type="hidden" id="ofertaPrograma">
                        </div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="ofertaFase" class="form-label"><i class="ri-route-line"
                                        style="color:#fc7b04;"></i> Fase <span class="req">*</span></label>
                                <select class="form-select" id="ofertaFase" disabled>
                                    <option value="">Seleccionar...</option>
                                    @foreach ($fases as $f)
                                        <option value="{{ $f->id }}">{{ $f->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="field-feedback" id="fbOfertaFase"></div>
                            </div>
                            <div>
                                <label for="ofertaModalidad" class="form-label"><i class="ri-wifi-line"
                                        style="color:#fc7b04;"></i> Modalidad</label>
                                <select class="form-select" id="ofertaModalidad">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($modalidades as $m)
                                        <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-form">
                            <label for="ofertaSucursal" class="form-label"><i class="ri-map-pin-line"
                                    style="color:#fc7b04;"></i> Sucursal</label>
                            <select class="form-select" id="ofertaSucursal">
                                <option value="">Seleccionar...</option>
                                @foreach ($sucursales as $s)
                                    <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="oferta-section-title"><i class="ri-calendar-schedule-line"></i> Fechas</div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="ofertaFechaInscripciones" class="form-label"><i
                                        class="ri-calendar-check-line" style="color:#fc7b04;"></i> Inicio Inscripciones
                                    <span class="req">*</span></label>
                                <input type="date" class="form-control" id="ofertaFechaInscripciones">
                                <div class="field-feedback" id="fbOfertaFechaInsc"></div>
                            </div>
                            <div>
                                <label for="ofertaFechaInicio" class="form-label"><i class="ri-play-line"
                                        style="color:#fc7b04;"></i> Inicio Programa <span class="req">*</span></label>
                                <input type="date" class="form-control" id="ofertaFechaInicio">
                                <div class="field-feedback" id="fbOfertaFechaInicio"></div>
                            </div>
                            <div>
                                <label for="ofertaFechaFin" class="form-label"><i class="ri-stop-line"
                                        style="color:#fc7b04;"></i> Fin Programa <span class="req">*</span></label>
                                <input type="date" class="form-control" id="ofertaFechaFin">
                                <div class="field-feedback" id="fbOfertaFechaFin"></div>
                            </div>
                        </div>

                        <div class="oferta-section-title"><i class="ri-bar-chart-grouped-line"></i> Detalles</div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="ofertaNModulos" class="form-label"><i class="ri-layout-grid-line"
                                        style="color:#fc7b04;"></i> N° Módulos</label>
                                <input type="number" class="form-control" id="ofertaNModulos" placeholder="1"
                                    min="1" value="1">
                                <div class="field-feedback" id="fbOfertaNModulos"></div>
                            </div>
                            <div>
                                <label for="ofertaCantSesiones" class="form-label"><i class="ri-team-line"
                                        style="color:#fc7b04;"></i> N° Sesiones</label>
                                <input type="number" class="form-control" id="ofertaCantSesiones" placeholder="1"
                                    min="1" value="1">
                                <div class="field-feedback" id="fbOfertaCantSesiones"></div>
                            </div>
                            <div>
                                <label for="ofertaNotaMinima" class="form-label"><i class="ri-star-line"
                                        style="color:#fc7b04;"></i> Nota Mínima</label>
                                <input type="number" class="form-control" id="ofertaNotaMinima" placeholder="61"
                                    min="0" max="100" value="61">
                                <div class="field-feedback" id="fbOfertaNotaMinima"></div>
                            </div>
                        </div>
                        <div class="col-fields-3 mb-form">
                            <div>
                                <label for="ofertaVersion" class="form-label"><i class="ri-git-commit-line"
                                        style="color:#fc7b04;"></i> Versión</label>
                                <input type="number" class="form-control" id="ofertaVersion" placeholder="1"
                                    min="1" value="1">
                            </div>
                            <div>
                                <label for="ofertaGrupo" class="form-label"><i class="ri-group-line"
                                        style="color:#fc7b04;"></i> Grupo</label>
                                <input type="number" class="form-control" id="ofertaGrupo" placeholder="1"
                                    min="1" value="1">
                            </div>
                            <div>
                                <label for="ofertaColor" class="form-label"><i class="ri-palette-line"
                                        style="color:#fc7b04;"></i> Color</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="color-preview-box" id="ofertaColorPreview" style="background:#fc7b04;">
                                    </div>
                                    <input type="color" class="form-control form-control-color" id="ofertaColor"
                                        value="#fc7b04" style="width:42px;height:38px;padding:3px;">
                                    <input type="text" class="form-control" id="ofertaColorText" value="#fc7b04"
                                        maxlength="7" style="flex:1;">
                                </div>
                            </div>
                        </div>

                        <div class="oferta-section-title"><i class="ri-user-settings-line"></i> Responsables</div>
                        <div class="col-fields-2 mb-form">
                            <div>
                                <label for="ofertaRespAcademico" class="form-label"><i class="ri-user-star-line"
                                        style="color:#fc7b04;"></i> Responsable Académico</label>
                                <div class="searchable-select-wrap">
                                    <input type="text" class="form-control" id="ofertaRespAcademicoSearch"
                                        placeholder="Buscar responsable..." autocomplete="off">
                                    <div class="searchable-dropdown" id="ofertaRespAcademicoDropdown"></div>
                                    <input type="hidden" id="ofertaRespAcademico">
                                </div>
                            </div>
                            <div>
                                <label for="ofertaRespMarketing" class="form-label"><i class="ri-megaphone-line"
                                        style="color:#fc7b04;"></i> Responsable Marketing</label>
                                <div class="searchable-select-wrap">
                                    <input type="text" class="form-control" id="ofertaRespMarketingSearch"
                                        placeholder="Buscar responsable..." autocomplete="off">
                                    <div class="searchable-dropdown" id="ofertaRespMarketingDropdown"></div>
                                    <input type="hidden" id="ofertaRespMarketing">
                                </div>
                            </div>
                        </div>

                        <div class="oferta-section-title"><i class="ri-image-add-line"></i> Documentos</div>
                        <div class="col-fields-2 mb-form">
                            <div>
                                <label for="ofertaPortada" class="form-label"><i class="ri-image-line"
                                        style="color:#fc7b04;"></i> Portada del Programa</label>
                                <div class="file-upload-zone" id="portadaUploadZone"
                                    onclick="document.getElementById('ofertaPortada').click()">
                                    <input type="file" id="ofertaPortada" accept="image/*" style="display:none;">
                                    <div id="portadaPreview" class="file-preview-area">
                                        <i class="ri-image-add-line"></i>
                                        <span>Click o arrastra una imagen</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="ofertaCertificado" class="form-label"><i class="ri-file-certification-line"
                                        style="color:#fc7b04;"></i> Certificado</label>
                                <div class="file-upload-zone" id="certificadoUploadZone"
                                    onclick="document.getElementById('ofertaCertificado').click()">
                                    <input type="file" id="ofertaCertificado" accept="image/*,.pdf"
                                        style="display:none;">
                                    <div id="certificadoPreview" class="file-preview-area">
                                        <i class="ri-file-add-line"></i>
                                        <span>Click o arrastra un archivo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i
                                class="ri-close-line me-1"></i>Cancelar</button>
                        <button type="button" class="btn btn-modal-submit" id="btnGuardarOferta"><i
                                class="ri-save-line"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Eliminar Oferta --}}
    <div class="modal fade" id="modalEliminarOferta" tabindex="-1" aria-hidden="true">
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
                        <p class="delete-msg-name"><strong id="ofertaEliminarCodigo"></strong></p>
                        <p class="delete-msg-warn"><i class="ri-information-line"></i> Esta acción es permanente y no
                            puede deshacerse.</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center gap-3">
                    <button type="button" class="btn btn-modal-cancel px-4" data-bs-dismiss="modal"><i
                            class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="button" class="btn btn-danger-modal px-4" id="btnConfirmarEliminarOferta"><i
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
            let quickAddTarget = null;
            const CSRF = '{{ csrf_token() }}';

            $.fn.dataTable.ext.errMode = 'none';

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const convenio = $('#filtro-convenio').val();
                const area = $('#filtro-area').val();
                const tipo = $('#filtro-tipo').val();
                const estado = $('#filtro-estado').val();

                if (!convenio && !area && !tipo && !estado) return true;

                const row = settings.aoData[dataIndex];
                if (!row) return true;
                const raw = row._aData;
                if (!raw) return true;

                if (convenio && String(raw.convenio_id || '') !== convenio) return false;
                if (area && String(raw.area_id || '') !== area) return false;
                if (tipo && String(raw.tipo_id || '') !== tipo) return false;
                if (estado !== '' && String(raw.estado ? 1 : 0) !== estado) return false;

                return true;
            });

            function init() {
                initDataTable();
                bindEvents();
            }

            function initDataTable() {
                tabla = $('#tabla-posgrads').DataTable({
                    ajax: {
                        url: '{{ route('admin.posgrads.listar') }}',
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
                            data: 'nombre',
                            render: n => '<span style="font-weight:600;">' + escHtml(n) + '</span>'
                        },
                        {
                            data: 'convenio.nombre',
                            render: d => d ? '<span class="badge-display">' + escHtml(d) + '</span>' :
                                '<span class="text-muted">-</span>'
                        },
                        {
                            data: 'area.nombre',
                            render: d => d ? '<span class="badge-display badge-info">' + escHtml(d) +
                                '</span>' : '<span class="text-muted">-</span>'
                        },
                        {
                            data: 'tipo.nombre',
                            render: d => d ? '<span class="badge-display">' + escHtml(d) + '</span>' :
                                '<span class="text-muted">-</span>'
                        },
                        {
                            data: null,
                            render: d => d.duracion_numero > 0 ? d.duracion_numero + ' ' + (d
                                .duracion_unidad || 'Horas') : '-'
                        },
                        {
                            data: null,
                            className: 'text-center',
                            render: d => {
                                const activo = d.estado;
                                const label = activo ? 'Activo' : 'No Activo';
                                const cls = activo ? 'activo' : 'inactivo';
                                return '<span class="badge-estado ' + cls + '">' +
                                    '<span class="badge-estado-dot"></span>' + label + '</span>';
                            }
                        },
                        {
                            data: null,
                            className: 'text-center',
                            render: d => '<div class="action-cell">' +
                                '<a href="/admin/posgrads/' + d.id +
                                '/ofertas" class="btn btn-action btn-action-ofertas" data-id="' + d.id +
                                '" data-nombre="' + escHtml(d.nombre) +
                                '" title="Ofertas académicas" style="color:#22c55e;"><i class="ri-book-open-line"></i></a>' +
                                '<button class="btn btn-action btn-action-edit btn-accion-editar" data-id="' +
                                d.id + '" data-nombre="' + escHtml(d.nombre) + '" data-creditaje="' + (d
                                    .creditaje || '') + '" data-carga_horaria="' + (d.carga_horaria || '') +
                                '" data-duracion_numero="' + (d.duracion_numero || '') +
                                '" data-duracion_unidad="' + (d.duracion_unidad || '') +
                                '" data-convenio_id="' + (d.convenio_id || '') + '" data-area_id="' + (d
                                    .area_id || '') + '" data-tipo_id="' + (d.tipo_id || '') +
                                '" data-dirigido="' + escHtml(d.dirigido || '') + '" data-objetivo="' +
                                escHtml(d.objetivo || '') + '" data-estado="' + (d.estado ? 1 : 0) +
                                '" title="Editar posgrado"><i class="ri-pencil-fill"></i></button>' +
                                '<button class="btn btn-action btn-action-delete btn-accion-eliminar" data-id="' +
                                d.id + '" data-nombre="' + escHtml(d.nombre) +
                                '" title="Eliminar posgrado"><i class="ri-delete-bin-fill"></i></button>' +
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
                        const activosCount = api.rows().data().filter(function(row) {
                            return row.estado;
                        }).length;
                        $('#stat-total').text(recordsTotal);
                        $('#stat-activos').text(activosCount);
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

            function populateSelects() {
                $.when(
                    $.get('{{ route('admin.areas.listar') }}'),
                    $.get('{{ route('admin.tipos.listar') }}')
                ).done(function(areasRes, tiposRes) {
                    const areas = areasRes[0].data;
                    const tipos = tiposRes[0].data;

                    const selects = ['areaCrear', 'areaEditar'];
                    selects.forEach(function(selId) {
                        const currentVal = $('#' + selId).val();
                        const $sel = $('#' + selId);
                        $sel.find('option:not(:first)').remove();
                        areas.forEach(function(a) {
                            $sel.append('<option value="' + a.id + '">' + escHtml(a.nombre) +
                                '</option>');
                        });
                        if (currentVal) $sel.val(currentVal);
                    });

                    const tipoSelects = ['tipoCrear', 'tipoEditar'];
                    tipoSelects.forEach(function(selId) {
                        const currentVal = $('#' + selId).val();
                        const $sel = $('#' + selId);
                        $sel.find('option:not(:first)').remove();
                        tipos.forEach(function(t) {
                            $sel.append('<option value="' + t.id + '">' + escHtml(t.nombre) +
                                '</option>');
                        });
                        if (currentVal) $sel.val(currentVal);
                    });
                });
            }

            function addOptionToSelect(selectId, value, text) {
                const $sel = $('#' + selectId);
                if ($sel.find('option[value="' + value + '"]').length === 0) {
                    $sel.append('<option value="' + value + '">' + escHtml(text) + '</option>');
                }
                $sel.val(value);
            }

            function bindEvents() {
                $('#btn-nuevo').on('click', () => {
                    resetField('nombreCrear', 'iconCrear', 'fbCrear');
                    $('#formCrear')[0].reset();
                    $('#estadoCrear').prop('checked', true);
                    $('#estadoCrearLabel').text('Activo').removeClass('inactive').addClass('active');
                    $('#btnGuardar').prop('disabled', true);
                    openModal('modalCrear');
                });

                $('#estadoCrear').on('change', function() {
                    const label = $('#estadoCrearLabel');
                    if ($(this).is(':checked')) {
                        label.text('Activo').removeClass('inactive').addClass('active');
                    } else {
                        label.text('No Activo').removeClass('active').addClass('inactive');
                    }
                });

                $('#estadoEditar').on('change', function() {
                    const label = $('#estadoEditarLabel');
                    if ($(this).is(':checked')) {
                        label.text('Activo').removeClass('inactive').addClass('active');
                    } else {
                        label.text('No Activo').removeClass('active').addClass('inactive');
                    }
                });

                $(document).on('click', '.btn-accion-editar', function() {
                    const d = $(this).data();
                    $('#idEditar').val(d.id);
                    $('#nombreEditar').val(d.nombre);
                    $('#creditajeEditar').val(d.creditaje !== undefined ? d.creditaje : '');
                    $('#cargaHorariaEditar').val(d.carga_horaria !== undefined ? d.carga_horaria : '');
                    $('#duracionNumeroEditar').val(d.duracion_numero !== undefined ? d.duracion_numero : '');
                    $('#duracionUnidadEditar').val(d.duracion_unidad || 'Horas');
                    $('#convenioEditar').val(d.convenio_id || '');
                    $('#areaEditar').val(d.area_id || '');
                    $('#tipoEditar').val(d.tipo_id || '');
                    $('#dirigidoEditar').val(d.dirigido || '');
                    $('#objetivoEditar').val(d.objetivo || '');
                    const estadoVal = d.estado !== undefined ? d.estado : 1;
                    $('#estadoEditar').prop('checked', estadoVal == 1);
                    if (estadoVal == 1) {
                        $('#estadoEditarLabel').text('Activo').removeClass('inactive').addClass('active');
                    } else {
                        $('#estadoEditarLabel').text('No Activo').removeClass('active').addClass('inactive');
                    }
                    resetField('nombreEditar', 'iconEditar', 'fbEditar');
                    $('#btnActualizar').prop('disabled', true);
                    verificarNombre('nombreEditar', 'iconEditar', 'fbEditar', d.id, '#btnActualizar');
                    openModal('modalEditar');
                });

                $(document).on('click', '.btn-accion-eliminar', function() {
                    idEliminar = $(this).data('id');
                    $('#nombreEliminar').text($(this).data('nombre'));
                    openModal('modalEliminar');
                });

                $('#btnConfirmarEliminar').on('click', function() {
                    if (!idEliminar) return;
                    eliminarPosgrado(idEliminar);
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
                    verificarNombre('nombreCrear', 'iconCrear', 'fbCrear', null, '#btnGuardar');
                });

                $('#nombreEditar').on('input', function() {
                    const id = $('#idEditar').val();
                    verificarNombre('nombreEditar', 'iconEditar', 'fbEditar', id, '#btnActualizar');
                });

                document.getElementById('modalCrear').addEventListener('hidden.bs.modal', () => {
                    resetField('nombreCrear', 'iconCrear', 'fbCrear');
                    $('#formCrear')[0].reset();
                    $('#btnGuardar').prop('disabled', true);
                });

                document.getElementById('modalEditar').addEventListener('hidden.bs.modal', () => {
                    resetField('nombreEditar', 'iconEditar', 'fbEditar');
                    $('#formEditar')[0].reset();
                });

                $('#filtro-convenio, #filtro-area, #filtro-tipo, #filtro-estado').on('change', function() {
                    tabla.draw();
                });

                $('#btn-limpiar-filtros').on('click', function() {
                    $('#filtro-convenio, #filtro-area, #filtro-tipo, #filtro-estado').val('');
                    tabla.draw();
                });

                // Quick add: Área
                $('#btnQuickArea, #btnQuickAreaEditar').on('click', function() {
                    quickAddTarget = $(this).closest('.col-fields-2').find('select[id^="area"]').attr('id');
                    $('#quickAreaNombre').val('');
                    $('#fbQuickArea').html('');
                    openModal('modalQuickArea');
                    setTimeout(() => $('#quickAreaNombre').focus(), 300);
                });

                $('#btnGuardarQuickArea').on('click', function() {
                    const nombre = $('#quickAreaNombre').val().trim();
                    if (!nombre || nombre.length < 2) {
                        $('#fbQuickArea').html(
                            '<span style="color:#ef4444;font-size:0.78rem;"><i class="ri-error-warning-line"></i>Debe tener al menos 2 caracteres.</span>'
                        );
                        return;
                    }
                    setBtnLoading('#btnGuardarQuickArea', true, 'Guardando…');
                    $.post('{{ route('admin.areas.guardar') }}', {
                            _token: CSRF,
                            nombre: nombre
                        })
                        .done(function(r) {
                            closeModal('modalQuickArea');
                            populateSelects();
                            setTimeout(function() {
                                if (quickAddTarget) {
                                    addOptionToSelect(quickAddTarget, r.data.id, r.data.nombre);
                                }
                            }, 200);
                            toast('success', r.message || 'Área guardada correctamente.');
                        })
                        .fail(function(xhr) {
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors && xhr
                                .responseJSON.errors.nombre) {
                                $('#fbQuickArea').html(
                                    '<span style="color:#ef4444;font-size:0.78rem;"><i class="ri-error-warning-line"></i>' +
                                    xhr.responseJSON.errors.nombre[0] + '</span>');
                            } else {
                                toast('error', 'No se pudo guardar el área.');
                            }
                        })
                        .always(function() {
                            setBtnLoading('#btnGuardarQuickArea', false,
                                '<i class="ri-save-line"></i> Guardar');
                        });
                });

                $('#quickAreaNombre').on('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        $('#btnGuardarQuickArea').click();
                    }
                });

                // Quick add: Tipo
                $('#btnQuickTipo, #btnQuickTipoEditar').on('click', function() {
                    quickAddTarget = $(this).closest('.col-fields-2').find('select[id^="tipo"]').attr('id');
                    $('#quickTipoNombre').val('');
                    $('#quickTipoDesc').val('');
                    $('#fbQuickTipo').html('');
                    openModal('modalQuickTipo');
                    setTimeout(() => $('#quickTipoNombre').focus(), 300);
                });

                $('#btnGuardarQuickTipo').on('click', function() {
                    const nombre = $('#quickTipoNombre').val().trim();
                    if (!nombre || nombre.length < 2) {
                        $('#fbQuickTipo').html(
                            '<span style="color:#ef4444;font-size:0.78rem;"><i class="ri-error-warning-line"></i>Debe tener al menos 2 caracteres.</span>'
                        );
                        return;
                    }
                    setBtnLoading('#btnGuardarQuickTipo', true, 'Guardando…');
                    $.post('{{ route('admin.tipos.guardar') }}', {
                            _token: CSRF,
                            nombre: nombre,
                            descripcion: $('#quickTipoDesc').val().trim()
                        })
                        .done(function(r) {
                            closeModal('modalQuickTipo');
                            populateSelects();
                            setTimeout(function() {
                                if (quickAddTarget) {
                                    addOptionToSelect(quickAddTarget, r.data.id, r.data.nombre);
                                }
                            }, 200);
                            toast('success', r.message || 'Tipo guardado correctamente.');
                        })
                        .fail(function(xhr) {
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors && xhr
                                .responseJSON.errors.nombre) {
                                $('#fbQuickTipo').html(
                                    '<span style="color:#ef4444;font-size:0.78rem;"><i class="ri-error-warning-line"></i>' +
                                    xhr.responseJSON.errors.nombre[0] + '</span>');
                            } else {
                                toast('error', 'No se pudo guardar el tipo.');
                            }
                        })
                        .always(function() {
                            setBtnLoading('#btnGuardarQuickTipo', false,
                                '<i class="ri-save-line"></i> Guardar');
                        });
                });

                $('#quickTipoNombre').on('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        $('#btnGuardarQuickTipo').click();
                    }
                });

                // Reset quickAddTarget when quick modals close
                document.getElementById('modalQuickArea').addEventListener('hidden.bs.modal', function() {
                    quickAddTarget = null;
                });
                document.getElementById('modalQuickTipo').addEventListener('hidden.bs.modal', function() {
                    quickAddTarget = null;
                });

                // === OFERTAS ACADÉMICAS ===
                let tablaOfertas = null;
                let currentPosgradoOfertas = null;
                let idEliminarOferta = null;
                let portadaFile = null;
                let certificadoFile = null;

                const programasData = @json($programas);
                const trabajadoresAcademicosData = @json($trabajadoresAcademicosData);
                const trabajadoresMarketingData = @json($trabajadoresMarketingData);
                const fasesData = @json($fases);
                const faseAprobadoId = fasesData.find(function(f) {
                    return f.nombre.toLowerCase().indexOf('aprobado') !== -1;
                });
                const faseNoAprobadoId = fasesData.find(function(f) {
                    return f.nombre.toLowerCase().indexOf('no aprobado') !== -1 || f.nombre.toLowerCase()
                        .indexOf('no aprob') !== -1;
                });

                function generarNombrePrograma() {
                    const posgradoNombre = $('#ofertasPosgradoNombre').text().trim();
                    const version = $('#ofertaVersion').val() || 1;
                    const grupo = $('#ofertaGrupo').val() || 1;
                    if (posgradoNombre) {
                        $('#ofertaProgramaText').val(posgradoNombre + ' V' + version + ' G' + grupo);
                    }
                }

                $('#ofertaVersion, #ofertaGrupo').on('input', function() {
                    generarNombrePrograma();
                });

                function initSearchableSelect(searchId, dropdownId, hiddenId, data, displayKey, subKey) {
                    const $search = $('#' + searchId);
                    const $dropdown = $('#' + dropdownId);
                    const $hidden = $('#' + hiddenId);
                    let activeIndex = -1;

                    function renderItems(filter) {
                        const f = filter.toLowerCase();
                        const filtered = data.filter(function(item) {
                            return item[displayKey].toLowerCase().indexOf(f) !== -1 || (item[subKey] && item[
                                subKey].toLowerCase().indexOf(f) !== -1);
                        });
                        if (filtered.length === 0) {
                            $dropdown.html('<div class="searchable-dropdown-empty">Sin resultados</div>');
                        } else {
                            let html = '';
                            filtered.forEach(function(item, i) {
                                const sub = item[subKey] ? '<span class="dd-sub">' + escHtml(item[subKey]) +
                                    '</span>' : '';
                                html += '<div class="searchable-dropdown-item" data-index="' + i +
                                    '" data-id="' + item.id + '">' + escHtml(item[displayKey]) + sub + '</div>';
                            });
                            $dropdown.html(html);
                        }
                        activeIndex = -1;
                    }

                    $search.on('focus', function() {
                        renderItems($(this).val());
                        $dropdown.addClass('show');
                    });

                    $search.on('input', function() {
                        renderItems($(this).val());
                        $dropdown.addClass('show');
                        $hidden.val('');
                    });

                    $dropdown.on('click', '.searchable-dropdown-item', function() {
                        const id = $(this).data('id');
                        const text = $(this).clone().children().remove().end().text().trim();
                        $search.val(text);
                        $hidden.val(id);
                        $dropdown.removeClass('show');
                    });

                    $search.on('keydown', function(e) {
                        const items = $dropdown.find('.searchable-dropdown-item');
                        if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            activeIndex = Math.min(activeIndex + 1, items.length - 1);
                            items.removeClass('active').eq(activeIndex).addClass('active');
                        } else if (e.key === 'ArrowUp') {
                            e.preventDefault();
                            activeIndex = Math.max(activeIndex - 1, 0);
                            items.removeClass('active').eq(activeIndex).addClass('active');
                        } else if (e.key === 'Enter') {
                            e.preventDefault();
                            if (activeIndex >= 0 && items.length > 0) {
                                items.eq(activeIndex).click();
                            }
                        }
                    });

                    $(document).on('click', function(e) {
                        if (!$(e.target).closest('.' + 'searchable-select-wrap').length) {
                            $dropdown.removeClass('show');
                        }
                    });

                    return {
                        setValue: function(id, text) {
                            $hidden.val(id);
                            $search.val(text);
                        },
                        reset: function() {
                            $hidden.val('');
                            $search.val('');
                        }
                    };
                }

                let programaSelect, respAcademicoSelect, respMarketingSelect;

                $(document).on('click', '.btn-action-ofertas', function(e) {
                    e.preventDefault();
                    const posgradoId = $(this).data('id');
                    const posgradoNombre = $(this).data('nombre');
                    currentPosgradoOfertas = posgradoId;
                    $('#ofertasPosgradoNombre').text(posgradoNombre);
                    openModal('modalOfertas');
                });

                $('#modalOfertas').on('shown.bs.modal', function() {
                    if (currentPosgradoOfertas) {
                        initOfertasTable(currentPosgradoOfertas);
                    }
                });

                $('#btnNuevaOferta').on('click', function() {
                    $('#formOferta')[0].reset();
                    $('#ofertaId').val('');
                    $('#ofertaPosgradoId').val(currentPosgradoOfertas);
                    $('#ofertaGestion').val(new Date().getFullYear());
                    $('#ofertaNModulos').val(1);
                    $('#ofertaCantSesiones').val(1);
                    $('#ofertaNotaMinima').val(61);
                    $('#ofertaVersion').val(1);
                    $('#ofertaGrupo').val(1);
                    $('#ofertaColor').val('#fc7b04');
                    $('#ofertaColorText').val('#fc7b04');
                    $('#ofertaColorPreview').css('background', '#fc7b04');
                    $('#fbOfertaCodigo').html('');
                    $('#fbOfertaFechaInsc').html('');
                    $('#fbOfertaFechaInicio').html('');
                    $('#fbOfertaFechaFin').html('');
                    $('#ofertaFormTitle').html(
                        '<i class="ri-book-open-line" style="color:#fc7b04;"></i> Nueva Oferta Académica');
                    $('#ofertaPrograma').val('');
                    $('#ofertaFase').val('');
                    if (faseNoAprobadoId) {
                        $('#ofertaFase').val(faseNoAprobadoId.id);
                    }
                    generarNombrePrograma();
                    if (respAcademicoSelect) respAcademicoSelect.reset();
                    if (respMarketingSelect) respMarketingSelect.reset();
                    resetFilePreview('portadaPreview', 'portadaUploadZone');
                    resetFilePreview('certificadoPreview', 'certificadoUploadZone');
                    portadaFile = null;
                    certificadoFile = null;
                    openModal('modalOfertaForm');
                });

                $(document).on('click', '.btn-edit-oferta', function() {
                    const btn = $(this);
                    const d = {
                        id: btn.data('id'),
                        codigo: btn.data('codigo'),
                        programa_id: btn.data('programa_id'),
                        programa_nombre: btn.data('programa_nombre'),
                        fase_id: btn.data('fase_id'),
                        modalidade_id: btn.data('modalidade_id'),
                        sucursale_id: btn.data('sucursale_id'),
                        gestion: btn.data('gestion'),
                        fecha_inicio_inscripciones: btn.data('fecha_inicio_inscripciones'),
                        fecha_inicio_programa: btn.data('fecha_inicio_programa'),
                        fecha_fin_programa: btn.data('fecha_fin_programa'),
                        n_modulos: btn.data('n_modulos'),
                        cantidad_sesiones: btn.data('cantidad_sesiones'),
                        nota_minima: btn.data('nota_minima'),
                        version: btn.data('version'),
                        grupo: btn.data('grupo'),
                        color: btn.data('color'),
                        responsable_academico_id: btn.data('responsable_academico_id'),
                        responsable_academico_nombre: btn.data('responsable_academico_nombre'),
                        responsable_marketing_id: btn.data('responsable_marketing_id'),
                        responsable_marketing_nombre: btn.data('responsable_marketing_nombre'),
                        portada: btn.data('portada'),
                        certificado: btn.data('certificado'),
                    };
                    $('#ofertaId').val(d.id);
                    $('#ofertaPosgradoId').val(currentPosgradoOfertas);
                    $('#ofertaCodigo').val(d.codigo);
                    $('#ofertaProgramaText').val(d.programa_nombre || '');
                    $('#ofertaPrograma').val(d.programa_id || '');
                    $('#ofertaFase').val(d.fase_id || '');
                    console.log('Edit oferta data:', d);
                    console.log('modalidade_id raw:', btn.data('modalidade_id'), 'type:', typeof btn.data(
                        'modalidade_id'));
                    $('#ofertaModalidad').val(String(d.modalidade_id || ''));
                    console.log('ofertaModalidad val after set:', $('#ofertaModalidad').val());
                    console.log('ofertaModalidad options:', $('#ofertaModalidad option').map(function() {
                        return {
                            val: this.value,
                            text: this.text
                        };
                    }).get());
                    $('#ofertaSucursal').val(d.sucursale_id || '');
                    $('#ofertaGestion').val(d.gestion || '');
                    $('#ofertaFechaInscripciones').val(d.fecha_inicio_inscripciones || '');
                    $('#ofertaFechaInicio').val(d.fecha_inicio_programa || '');
                    $('#ofertaFechaFin').val(d.fecha_fin_programa || '');
                    $('#ofertaNModulos').val(d.n_modulos || '');
                    $('#ofertaCantSesiones').val(d.cantidad_sesiones || '');
                    $('#ofertaNotaMinima').val(d.nota_minima || '');
                    $('#ofertaVersion').val(d.version || '');
                    $('#ofertaGrupo').val(d.grupo || '');
                    const color = d.color || '#fc7b04';
                    $('#ofertaColor').val(color);
                    $('#ofertaColorText').val(color);
                    $('#ofertaColorPreview').css('background', color);
                    if (respAcademicoSelect) respAcademicoSelect.setValue(d.responsable_academico_id || '', d
                        .responsable_academico_nombre || '');
                    if (respMarketingSelect) respMarketingSelect.setValue(d.responsable_marketing_id || '', d
                        .responsable_marketing_nombre || '');
                    $('#fbOfertaCodigo').html('');
                    $('#fbOfertaFechaInsc').html('');
                    $('#fbOfertaFechaInicio').html('');
                    $('#fbOfertaFechaFin').html('');
                    $('#ofertaFormTitle').html(
                        '<i class="ri-edit-2-line" style="color:#fc7b04;"></i> Editar Oferta Académica');

                    portadaFile = null;
                    certificadoFile = null;
                    if (d.portada) {
                        showExistingFile('portadaPreview', 'portadaUploadZone', d.portada, function() {
                            portadaFile = null;
                        });
                    } else {
                        resetFilePreview('portadaPreview', 'portadaUploadZone');
                    }
                    if (d.certificado) {
                        showExistingFile('certificadoPreview', 'certificadoUploadZone', d.certificado,
                            function() {
                                certificadoFile = null;
                            });
                    } else {
                        resetFilePreview('certificadoPreview', 'certificadoUploadZone');
                    }
                    openModal('modalOfertaForm');
                });

                $(document).on('click', '.btn-delete-oferta', function() {
                    idEliminarOferta = $(this).data('id');
                    $('#ofertaEliminarCodigo').text($(this).data('codigo'));
                    openModal('modalEliminarOferta');
                });

                $('#btnConfirmarEliminarOferta').on('click', function() {
                    if (!idEliminarOferta) return;
                    eliminarOferta(idEliminarOferta);
                });

                $('#btnGuardarOferta').on('click', function() {
                    console.log('=== Click en Guardar Oferta ===');
                    guardarOferta();
                });

                $('#ofertaColor').on('input', function() {
                    const v = $(this).val();
                    $('#ofertaColorText').val(v);
                    $('#ofertaColorPreview').css('background', v);
                });
                $('#ofertaColorText').on('input', function() {
                    const v = $(this).val();
                    if (/^#[0-9A-Fa-f]{6}$/.test(v)) {
                        $('#ofertaColor').val(v);
                        $('#ofertaColorPreview').css('background', v);
                    }
                });

                // Date validation
                $('#ofertaFechaInscripciones, #ofertaFechaInicio, #ofertaFechaFin').on('change', validateFechas);

                function validateFechas() {
                    const insc = $('#ofertaFechaInscripciones').val();
                    const inicio = $('#ofertaFechaInicio').val();
                    const fin = $('#ofertaFechaFin').val();
                    let valid = true;

                    $('#fbOfertaFechaInsc').html('');
                    $('#fbOfertaFechaInicio').html('');
                    $('#fbOfertaFechaFin').html('');

                    if (insc && inicio && insc >= inicio) {
                        $('#fbOfertaFechaInsc').html(
                            '<span style="color:#ef4444;font-size:0.75rem;"><i class="ri-error-warning-line"></i>Debe ser menor al inicio del programa</span>'
                        );
                        valid = false;
                    }
                    if (inicio && insc && inicio <= insc) {
                        $('#fbOfertaFechaInicio').html(
                            '<span style="color:#ef4444;font-size:0.75rem;"><i class="ri-error-warning-line"></i>Debe ser mayor al inicio de inscripciones</span>'
                        );
                        valid = false;
                    }
                    if (inicio && fin && fin <= inicio) {
                        $('#fbOfertaFechaFin').html(
                            '<span style="color:#ef4444;font-size:0.75rem;"><i class="ri-error-warning-line"></i>Debe ser mayor al inicio del programa</span>'
                        );
                        valid = false;
                    }
                    if (fin && inicio && fin <= inicio) {
                        $('#fbOfertaFechaFin').html(
                            '<span style="color:#ef4444;font-size:0.75rem;"><i class="ri-error-warning-line"></i>Debe ser mayor al inicio del programa</span>'
                        );
                        valid = false;
                    }
                    return valid;
                }

                // File upload handlers
                $('#ofertaPortada').on('change', function(e) {
                    handleFileSelect(e.target.files[0], 'portadaPreview', 'portadaUploadZone', function(file) {
                        portadaFile = file;
                    });
                });

                $('#ofertaCertificado').on('change', function(e) {
                    handleFileSelect(e.target.files[0], 'certificadoPreview', 'certificadoUploadZone', function(
                        file) {
                        certificadoFile = file;
                    });
                });

                // Drag and drop
                ['portadaUploadZone', 'certificadoUploadZone'].forEach(function(zoneId) {
                    const zone = document.getElementById(zoneId);
                    if (!zone) return;
                    zone.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        zone.style.borderColor = '#fc7b04';
                    });
                    zone.addEventListener('dragleave', function() {
                        zone.style.borderColor = '';
                    });
                    zone.addEventListener('drop', function(e) {
                        e.preventDefault();
                        zone.style.borderColor = '';
                        const inputId = zoneId === 'portadaUploadZone' ? 'ofertaPortada' :
                            'ofertaCertificado';
                        const previewId = zoneId === 'portadaUploadZone' ? 'portadaPreview' :
                            'certificadoPreview';
                        if (e.dataTransfer.files.length > 0) {
                            const file = e.dataTransfer.files[0];
                            document.getElementById(inputId).files = e.dataTransfer.files;
                            handleFileSelect(file, previewId, zoneId, function(f) {
                                if (zoneId === 'portadaUploadZone') portadaFile = f;
                                else certificadoFile = f;
                            });
                        }
                    });
                });

                function handleFileSelect(file, previewId, zoneId, callback) {
                    if (!file) return;
                    callback(file);
                    const $preview = $('#' + previewId);
                    const $zone = $('#' + zoneId);
                    $zone.addClass('has-file');

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $preview.html(
                                '<img src="' + e.target.result + '" alt="Preview">' +
                                '<span class="file-name">' + escHtml(file.name) + '</span>' +
                                '<span class="file-remove" onclick="removeFile(\'' + previewId + '\', \'' +
                                zoneId + '\', \'' + (zoneId === 'portadaUploadZone' ? 'portada' :
                                    'certificado') + '\')">Quitar</span>'
                            );
                        };
                        reader.readAsDataURL(file);
                    } else {
                        $preview.html(
                            '<i class="ri-file-pdf-line" style="font-size:2.5rem;color:#ef4444;"></i>' +
                            '<span class="file-name">' + escHtml(file.name) + '</span>' +
                            '<span class="file-remove" onclick="removeFile(\'' + previewId + '\', \'' + zoneId +
                            '\', \'' + (zoneId === 'portadaUploadZone' ? 'portada' : 'certificado') +
                            '\')">Quitar</span>'
                        );
                    }
                }

                function resetFilePreview(previewId, zoneId) {
                    const $preview = $('#' + previewId);
                    const $zone = $('#' + zoneId);
                    $zone.removeClass('has-file');
                    if (previewId === 'portadaPreview') {
                        $preview.html('<i class="ri-image-add-line"></i><span>Click o arrastra una imagen</span>');
                    } else {
                        $preview.html('<i class="ri-file-add-line"></i><span>Click o arrastra un archivo</span>');
                    }
                }

                function showExistingFile(previewId, zoneId, filePath, onRemoveCallback) {
                    const $preview = $('#' + previewId);
                    const $zone = $('#' + zoneId);
                    $zone.addClass('has-file');
                    const isImage = /\.(jpe?g|png|gif|webp)$/i.test(filePath);
                    const fileName = filePath.split('/').pop();
                    const fileUrl = '/storage/' + filePath;
                    if (isImage) {
                        $preview.html(
                            '<img src="' + fileUrl +
                            '" alt="Preview" style="max-width:100%;max-height:100px;border-radius:6px;object-fit:cover;">' +
                            '<span class="file-name">' + escHtml(fileName) + '</span>' +
                            '<span class="file-remove" onclick="removeExistingFile(\'' + previewId + '\', \'' +
                            zoneId + '\')">Quitar</span>'
                        );
                    } else {
                        $preview.html(
                            '<i class="ri-file-pdf-line" style="font-size:2.5rem;color:#ef4444;"></i>' +
                            '<span class="file-name">' + escHtml(fileName) + '</span>' +
                            '<span class="file-remove" onclick="removeExistingFile(\'' + previewId + '\', \'' +
                            zoneId + '\')">Quitar</span>'
                        );
                    }
                }

                window.removeExistingFile = function(previewId, zoneId) {
                    resetFilePreview(previewId, zoneId);
                    const inputId = zoneId === 'portadaUploadZone' ? 'ofertaPortada' : 'ofertaCertificado';
                    document.getElementById(inputId).value = '';
                    if (zoneId === 'portadaUploadZone') portadaFile = null;
                    else certificadoFile = null;
                };

                window.removeFile = function(previewId, zoneId, varName) {
                    if (varName === 'portada') portadaFile = null;
                    else certificadoFile = null;
                    const inputId = zoneId === 'portadaUploadZone' ? 'ofertaPortada' : 'ofertaCertificado';
                    document.getElementById(inputId).value = '';
                    resetFilePreview(previewId, zoneId);
                };

                document.getElementById('modalOfertas').addEventListener('hidden.bs.modal', function() {
                    if (tablaOfertas) {
                        tablaOfertas.destroy();
                        tablaOfertas = null;
                    }
                });

                document.getElementById('modalOfertaForm').addEventListener('hidden.bs.modal', function() {
                    $('#formOferta')[0].reset();
                });

                // Init searchable selects when modal opens
                $('#modalOfertaForm').on('shown.bs.modal', function() {
                    if (!respAcademicoSelect) {
                        respAcademicoSelect = initSearchableSelect('ofertaRespAcademicoSearch',
                            'ofertaRespAcademicoDropdown', 'ofertaRespAcademico',
                            trabajadoresAcademicosData, 'nombre', 'cargo');
                    }
                    if (!respMarketingSelect) {
                        respMarketingSelect = initSearchableSelect('ofertaRespMarketingSearch',
                            'ofertaRespMarketingDropdown', 'ofertaRespMarketing', trabajadoresMarketingData,
                            'nombre', 'cargo');
                    }
                });

                function getOrCreatePrograma(nombre) {
                    const nombreUpper = nombre.toUpperCase();
                    const existing = programasData.find(function(p) {
                        return p.nombre.toUpperCase() === nombreUpper;
                    });
                    if (existing) {
                        return $.when({
                            id: existing.id,
                            nombre: existing.nombre
                        });
                    }
                    return $.post('{{ route('admin.programas.guardar') }}', {
                        _token: CSRF,
                        nombre: nombre
                    }).then(function(r) {
                        programasData.push({
                            id: r.data.id,
                            nombre: r.data.nombre
                        });
                        return {
                            id: r.data.id,
                            nombre: r.data.nombre
                        };
                    });
                }

                function initOfertasTable(posgradoId) {
                    if (tablaOfertas) {
                        tablaOfertas.destroy();
                        $('#tabla-ofertas tbody').empty();
                    }
                    tablaOfertas = $('#tabla-ofertas').DataTable({
                        ajax: {
                            url: '/admin/posgrads/' + posgradoId + '/ofertas/listar',
                            dataSrc: 'data',
                            error: function(xhr) {
                                toast('error', 'Error al cargar las ofertas.');
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
                                    '/detalle" class="btn btn-action btn-action-view" title="Ver detalle" style="color:#0d6efd;"><i class="ri-eye-line"></i></a>' +
                                    '<button class="btn btn-action btn-modulos-oferta" data-id="' + d.id +
                                    '" data-n-modulos="' + (d.n_modulos || 0) + '" data-codigo="' + escHtml(
                                        d.codigo) +
                                    '" title="Gestionar Módulos" style="color:#6366f1;"><i class="ri-stack-line"></i></button>' +
                                    '<button class="btn btn-action btn-action-edit btn-edit-oferta" data-id="' +
                                    d.id + '" data-codigo="' + escHtml(d.codigo) + '" data-programa_id="' +
                                    (d.programa_id || '') + '" data-programa_nombre="' + escHtml(d
                                        .programa ? d.programa.nombre : '') + '" data-fase_id="' + (d
                                        .fase_id || '') + '" data-modalidade_id="' + (d.modalidade_id ||
                                        '') +
                                    '" data-sucursale_id="' + (d.sucursale_id || '') + '" data-gestion="' +
                                    (d.gestion || '') + '" data-fecha_inicio_inscripciones="' + (d
                                        .fecha_inicio_inscripciones || '') +
                                    '" data-fecha_inicio_programa="' + (d.fecha_inicio_programa || '') +
                                    '" data-fecha_fin_programa="' + (d.fecha_fin_programa || '') +
                                    '" data-n_modulos="' + (d.n_modulos || '') +
                                    '" data-cantidad_sesiones="' + (d.cantidad_sesiones || '') +
                                    '" data-nota_minima="' + (d.nota_minima || '') + '" data-version="' + (d
                                        .version || '') + '" data-grupo="' + (d.grupo || '') +
                                    '" data-color="' + (d.color || '#fc7b04') +
                                    '" data-responsable_academico_id="' + (d.responsable_academico_id ||
                                        '') +
                                    '" data-responsable_academico_nombre="' + escHtml(d
                                        .responsable_academico_nombre || '') +
                                    '" data-responsable_marketing_id="' +
                                    (d.responsable_marketing_id || '') +
                                    '" data-responsable_marketing_nombre="' + escHtml(d
                                        .responsable_marketing_nombre || '') + '" data-portada="' + (d
                                        .portada || '') + '" data-certificado="' + (d.certificado || '') +
                                    '" title="Editar oferta"><i class="ri-pencil-fill"></i></button>' +
                                    '<button class="btn btn-action btn-action-delete btn-delete-oferta" data-id="' +
                                    d.id + '" data-codigo="' + escHtml(d.codigo) +
                                    '" title="Eliminar oferta"><i class="ri-delete-bin-fill"></i></button>' +
                                    '</div>'
                            }
                        ],
                        language: {
                            processing: 'Procesando...',
                            loadingRecords: 'Cargando...',
                            zeroRecords: 'No se encontraron registros',
                            emptyTable: 'No hay ofertas registradas'
                        },
                        order: [
                            [0, 'asc']
                        ]
                    });
                }

                function formatDate(dateStr) {
                    if (!dateStr) return '-';
                    const parts = dateStr.split('-');
                    if (parts.length === 3) return parts[2] + '/' + parts[1] + '/' + parts[0];
                    return dateStr;
                }

                // Real-time validation feedback
                function setFieldFeedback(fieldId, feedbackId, isValid, message) {
                    const $fb = $('#' + feedbackId);
                    if (isValid) {
                        $fb.html('<span style="color:#22c55e;font-size:0.75rem;"><i class="ri-check-line"></i> ' +
                            message + '</span>');
                    } else {
                        $fb.html(
                            '<span style="color:#ef4444;font-size:0.75rem;"><i class="ri-error-warning-line"></i> ' +
                            message + '</span>');
                    }
                }

                $('#ofertaCodigo').on('input', function() {
                    const v = $(this).val().trim();
                    if (!v) setFieldFeedback('ofertaCodigo', 'fbOfertaCodigo', false,
                        'El código es obligatorio.');
                    else if (v.length < 2) setFieldFeedback('ofertaCodigo', 'fbOfertaCodigo', false,
                        'Mínimo 2 caracteres.');
                    else setFieldFeedback('ofertaCodigo', 'fbOfertaCodigo', true, 'Código válido.');
                });

                $('#ofertaProgramaText').on('input', function() {
                    const v = $(this).val().trim();
                    if (!v) setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', false,
                        'El programa es obligatorio.');
                    else if (v.length < 2) setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', false,
                        'Mínimo 2 caracteres.');
                    else setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', true, 'Programa válido.');
                });

                $('#ofertaGestion').on('input', function() {
                    const v = parseInt($(this).val());
                    if (!v || v < 2000) setFieldFeedback('ofertaGestion', 'fbOfertaGestion', false,
                        'Gestión debe ser ≥ 2000.');
                    else setFieldFeedback('ofertaGestion', 'fbOfertaGestion', true, 'Gestión válida.');
                });

                $('#ofertaFechaInscripciones, #ofertaFechaInicio, #ofertaFechaFin').on('change', function() {
                    validateFechas();
                });

                $('#ofertaNModulos').on('input', function() {
                    const v = parseInt($(this).val());
                    if (!v || v < 1) setFieldFeedback('ofertaNModulos', 'fbOfertaNModulos', false,
                        'Mínimo 1 módulo.');
                    else setFieldFeedback('ofertaNModulos', 'fbOfertaNModulos', true, 'Válido.');
                });

                $('#ofertaCantSesiones').on('input', function() {
                    const v = parseInt($(this).val());
                    if (!v || v < 1) setFieldFeedback('ofertaCantSesiones', 'fbOfertaCantSesiones', false,
                        'Mínimo 1 sesión.');
                    else setFieldFeedback('ofertaCantSesiones', 'fbOfertaCantSesiones', true, 'Válido.');
                });

                $('#ofertaNotaMinima').on('input', function() {
                    const v = parseFloat($(this).val());
                    if (isNaN(v) || v < 0 || v > 100) setFieldFeedback('ofertaNotaMinima', 'fbOfertaNotaMinima',
                        false, 'Debe ser entre 0 y 100.');
                    else setFieldFeedback('ofertaNotaMinima', 'fbOfertaNotaMinima', true, 'Válido.');
                });

                function validateAllFields() {
                    let valid = true;
                    const codigo = $('#ofertaCodigo').val().trim();
                    const programa = $('#ofertaProgramaText').val().trim();
                    const gestion = parseInt($('#ofertaGestion').val());
                    const fase = $('#ofertaFase').val();
                    const insc = $('#ofertaFechaInscripciones').val();
                    const inicio = $('#ofertaFechaInicio').val();
                    const fin = $('#ofertaFechaFin').val();
                    const modulos = parseInt($('#ofertaNModulos').val());
                    const sesiones = parseInt($('#ofertaCantSesiones').val());
                    const nota = parseFloat($('#ofertaNotaMinima').val());

                    if (!codigo || codigo.length < 2) {
                        setFieldFeedback('ofertaCodigo', 'fbOfertaCodigo', false,
                            'El código es obligatorio (mín. 2 caracteres).');
                        valid = false;
                    } else {
                        setFieldFeedback('ofertaCodigo', 'fbOfertaCodigo', true, 'Código válido.');
                    }

                    if (!programa || programa.length < 2) {
                        setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', false,
                            'El programa es obligatorio.');
                        valid = false;
                    } else {
                        setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', true, 'Programa válido.');
                    }

                    if (!gestion || gestion < 2000) {
                        setFieldFeedback('ofertaGestion', 'fbOfertaGestion', false, 'Gestión debe ser ≥ 2000.');
                        valid = false;
                    } else {
                        setFieldFeedback('ofertaGestion', 'fbOfertaGestion', true, 'Gestión válida.');
                    }

                    if (!fase) {
                        setFieldFeedback('ofertaFase', 'fbOfertaFase', false, 'Fase obligatoria.');
                        valid = false;
                    }

                    if (!insc) {
                        setFieldFeedback('ofertaFechaInscripciones', 'fbOfertaFechaInsc', false, 'Fecha obligatoria.');
                        valid = false;
                    }
                    if (!inicio) {
                        setFieldFeedback('ofertaFechaInicio', 'fbOfertaFechaInicio', false, 'Fecha obligatoria.');
                        valid = false;
                    }
                    if (!fin) {
                        setFieldFeedback('ofertaFechaFin', 'fbOfertaFechaFin', false, 'Fecha obligatoria.');
                        valid = false;
                    }

                    if (insc && inicio && insc >= inicio) {
                        setFieldFeedback('ofertaFechaInscripciones', 'fbOfertaFechaInsc', false,
                            'Debe ser menor al inicio del programa.');
                        valid = false;
                    }
                    if (inicio && insc && inicio <= insc) {
                        setFieldFeedback('ofertaFechaInicio', 'fbOfertaFechaInicio', false,
                            'Debe ser mayor a inscripciones.');
                        valid = false;
                    }
                    if (fin && inicio && fin <= inicio) {
                        setFieldFeedback('ofertaFechaFin', 'fbOfertaFechaFin', false, 'Debe ser mayor al inicio.');
                        valid = false;
                    }

                    if (!modulos || modulos < 1) {
                        setFieldFeedback('ofertaNModulos', 'fbOfertaNModulos', false, 'Mínimo 1 módulo.');
                        valid = false;
                    } else {
                        setFieldFeedback('ofertaNModulos', 'fbOfertaNModulos', true, 'Válido.');
                    }
                    if (!sesiones || sesiones < 1) {
                        setFieldFeedback('ofertaCantSesiones', 'fbOfertaCantSesiones', false, 'Mínimo 1 sesión.');
                        valid = false;
                    } else {
                        setFieldFeedback('ofertaCantSesiones', 'fbOfertaCantSesiones', true, 'Válido.');
                    }
                    if (isNaN(nota) || nota < 0 || nota > 100) {
                        setFieldFeedback('ofertaNotaMinima', 'fbOfertaNotaMinima', false, 'Entre 0 y 100.');
                        valid = false;
                    } else {
                        setFieldFeedback('ofertaNotaMinima', 'fbOfertaNotaMinima', true, 'Válido.');
                    }

                    return valid;
                }

                function guardarOferta() {
                    console.log('guardarOferta() llamada');
                    const validacion = validateAllFields();
                    console.log('validateAllFields:', validacion);
                    if (!validacion) {
                        toast('warning', 'Completa todos los campos obligatorios correctamente.');
                        return;
                    }

                    const programaNombre = $('#ofertaProgramaText').val().trim();
                    const version = $('#ofertaVersion').val();
                    const grupo = $('#ofertaGrupo').val();
                    const posgradoId = $('#ofertaPosgradoId').val();
                    const id = $('#ofertaId').val();
                    const isEdit = id !== '';

                    $.ajax({
                        url: '{{ route('admin.posgrads.ofertas.verificar-programa') }}',
                        type: 'POST',
                        data: {
                            _token: CSRF,
                            posgrado_id: posgradoId,
                            programa_nombre: programaNombre,
                            version: version,
                            grupo: grupo,
                            exclude_id: isEdit ? id : null
                        }
                    }).done(function(r) {
                        if (r.existe) {
                            setBtnLoading('#btnGuardarOferta', false, '<i class="ri-save-line"></i> Guardar');
                            setFieldFeedback('ofertaProgramaText', 'fbOfertaPrograma', false,
                                'Ya existe una oferta con este programa, versión y grupo.');
                            toast('error', 'Ya existe una oferta con este programa, versión y grupo.');
                            return;
                        }
                        proceedToSave();
                    });

                    function proceedToSave() {
                        console.log('programa:', programaNombre, 'isEdit:', isEdit, 'id:', id);
                        const btnSel = '#btnGuardarOferta';
                        setBtnLoading(btnSel, true, 'Guardando…');

                        console.log('Llamando getOrCreatePrograma...');
                        getOrCreatePrograma(programaNombre).done(function(progResult) {
                                console.log('getOrCreatePrograma done:', progResult);
                                const formData = new FormData();
                                formData.append('_token', CSRF);
                                formData.append('codigo', $('#ofertaCodigo').val().trim());
                                formData.append('posgrado_id', $('#ofertaPosgradoId').val());
                                formData.append('programa_id', progResult.id);
                                formData.append('fase_id', $('#ofertaFase').val());
                                formData.append('modalidade_id', $('#ofertaModalidad').val());
                                formData.append('sucursale_id', $('#ofertaSucursal').val());
                                formData.append('fecha_inicio_inscripciones', $('#ofertaFechaInscripciones').val());
                                formData.append('fecha_inicio_programa', $('#ofertaFechaInicio').val());
                                formData.append('fecha_fin_programa', $('#ofertaFechaFin').val());
                                formData.append('gestion', $('#ofertaGestion').val());
                                formData.append('n_modulos', $('#ofertaNModulos').val());
                                formData.append('cantidad_sesiones', $('#ofertaCantSesiones').val());
                                formData.append('version', $('#ofertaVersion').val());
                                formData.append('grupo', $('#ofertaGrupo').val());
                                formData.append('nota_minima', $('#ofertaNotaMinima').val());
                                formData.append('color', $('#ofertaColor').val());
                                formData.append('responsable_academico_id', $('#ofertaRespAcademico').val());
                                formData.append('responsable_marketing_id', $('#ofertaRespMarketing').val());
                                if (portadaFile) formData.append('portada', portadaFile);
                                if (certificadoFile) formData.append('certificado', certificadoFile);

                                $.ajax({
                                        url: isEdit ? '/admin/posgrads/ofertas/' + id :
                                            '{{ route('admin.posgrads.ofertas.guardar') }}',
                                        type: 'POST',
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        headers: {
                                            'X-HTTP-Method-Override': isEdit ? 'PUT' : 'POST'
                                        }
                                    })
                                    .done(function(r) {
                                        closeModal('modalOfertaForm');
                                        if (tablaOfertas) tablaOfertas.ajax.reload();
                                        toast('success', r.message || (isEdit ? 'Oferta actualizada.' :
                                            'Oferta registrada.'));
                                    })
                                    .fail(function(xhr) {
                                        console.log('Error response:', xhr);
                                        console.log('Errors JSON:', xhr.responseJSON);
                                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                            const errs = xhr.responseJSON.errors;
                                            console.log('Validation errors:', errs);
                                            let allErrors = [];
                                            if (errs.codigo) {
                                                setFieldFeedback('ofertaCodigo', 'fbOfertaCodigo', false, errs
                                                    .codigo[0]);
                                                allErrors.push(errs.codigo[0]);
                                            }
                                            if (errs.programa_id) {
                                                toast('error', errs.programa_id[0]);
                                                allErrors.push(errs.programa_id[0]);
                                            }
                                            if (errs.fase_id) {
                                                toast('error', errs.fase_id[0]);
                                                allErrors.push(errs.fase_id[0]);
                                            }
                                            if (errs.fecha_inicio_inscripciones) {
                                                setFieldFeedback('ofertaFechaInscripciones',
                                                    'fbOfertaFechaInsc',
                                                    false, errs.fecha_inicio_inscripciones[0]);
                                                allErrors.push(errs.fecha_inicio_inscripciones[0]);
                                            }
                                            if (errs.fecha_inicio_programa) {
                                                setFieldFeedback('ofertaFechaInicio', 'fbOfertaFechaInicio',
                                                    false,
                                                    errs.fecha_inicio_programa[0]);
                                                allErrors.push(errs.fecha_inicio_programa[0]);
                                            }
                                            if (errs.fecha_fin_programa) {
                                                setFieldFeedback('ofertaFechaFin', 'fbOfertaFechaFin', false,
                                                    errs
                                                    .fecha_fin_programa[0]);
                                                allErrors.push(errs.fecha_fin_programa[0]);
                                            }
                                            if (errs.gestion) {
                                                setFieldFeedback('ofertaGestion', 'fbOfertaGestion', false, errs
                                                    .gestion[0]);
                                                allErrors.push(errs.gestion[0]);
                                            }
                                            if (errs.n_modulos) {
                                                setFieldFeedback('ofertaNModulos', 'fbOfertaNModulos', false,
                                                    errs
                                                    .n_modulos[0]);
                                                allErrors.push(errs.n_modulos[0]);
                                            }
                                            if (errs.cantidad_sesiones) {
                                                setFieldFeedback('ofertaCantSesiones', 'fbOfertaCantSesiones',
                                                    false, errs.cantidad_sesiones[0]);
                                                allErrors.push(errs.cantidad_sesiones[0]);
                                            }
                                            if (errs.nota_minima) {
                                                setFieldFeedback('ofertaNotaMinima', 'fbOfertaNotaMinima',
                                                    false,
                                                    errs.nota_minima[0]);
                                                allErrors.push(errs.nota_minima[0]);
                                            }
                                            if (errs.posgrado_id) {
                                                allErrors.push('posgrado_id: ' + errs.posgrado_id[0]);
                                            }
                                            if (errs.version) {
                                                allErrors.push('version: ' + errs.version[0]);
                                            }
                                            if (errs.grupo) {
                                                allErrors.push('grupo: ' + errs.grupo[0]);
                                            }
                                            if (errs.color) {
                                                allErrors.push('color: ' + errs.color[0]);
                                            }
                                            if (errs.responsable_academico_id) {
                                                allErrors.push('responsable_academico_id: ' + errs
                                                    .responsable_academico_id[0]);
                                            }
                                            if (errs.responsable_marketing_id) {
                                                allErrors.push('responsable_marketing_id: ' + errs
                                                    .responsable_marketing_id[0]);
                                            }
                                            if (errs.portada) {
                                                allErrors.push('portada: ' + errs.portada[0]);
                                            }
                                            if (errs.certificado) {
                                                allErrors.push('certificado: ' + errs.certificado[0]);
                                            }
                                            if (allErrors.length > 0) {
                                                toast('error', 'Errores de validación: ' + allErrors.join(
                                                    ' | '));
                                            }
                                        } else {
                                            console.log('Full error:', xhr.responseText);
                                            toast('error', 'Error: ' + (xhr.responseJSON && xhr.responseJSON
                                                .message ? xhr.responseJSON.message :
                                                'Intente nuevamente.'
                                            ));
                                        }
                                    })
                                    .always(function() {
                                        setBtnLoading(btnSel, false, '<i class="ri-save-line"></i> Guardar');
                                    });
                            }
                        }).fail(function(xhr) {
                        console.log('Programa error:', xhr);
                        toast('error', 'No se pudo registrar el programa. ' + (xhr.responseJSON && xhr
                            .responseJSON.errors && xhr.responseJSON.errors.nombre ? xhr.responseJSON
                            .errors.nombre[0] : ''));
                        setBtnLoading(btnSel, false, '<i class="ri-save-line"></i> Guardar');
                    });
                }

                function eliminarOferta(id) {
                    setBtnLoading('#btnConfirmarEliminarOferta', true,
                        '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
                    $.ajax({
                            url: '/admin/posgrads/ofertas/' + id,
                            type: 'DELETE',
                            data: {
                                _token: CSRF
                            }
                        })
                        .done(r => {
                            closeModal('modalEliminarOferta');
                            if (tablaOfertas) tablaOfertas.ajax.reload();
                            toast('success', r.message || 'Oferta eliminada.');
                        })
                        .fail(xhr => {
                            const msg = xhr.responseJSON ? xhr.responseJSON.message : 'No se pudo eliminar.';
                            toast(xhr.status === 400 ? 'warning' : 'error', msg);
                        })
                        .always(() => {
                            setBtnLoading('#btnConfirmarEliminarOferta', false,
                                '<i class="ri-delete-bin-line"></i> Eliminar');
                            idEliminarOferta = null;
                        });
                }
            }

            function verificarNombre(inputId, iconId, fbId, idPosgrado, btnId) {
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
                    url: '{{ route('admin.posgrads.verificar') }}',
                    type: 'POST',
                    data: {
                        _token: CSRF,
                        nombre: val,
                        id: idPosgrado || null
                    },
                    success: function(r) {
                        if (r.existe) {
                            input.classList.remove('is-valid');
                            input.classList.add('is-invalid');
                            icon.className = 'validation-icon invalid';
                            icon.innerHTML = '<i class="ri-close-circle-fill"></i>';
                            fb.className = 'field-feedback error';
                            fb.innerHTML = '<i class="ri-error-warning-line"></i>Este posgrado ya existe.';
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

            function resetField(inputId, iconId, fbId) {
                const input = document.getElementById(inputId);
                input.classList.remove('is-valid', 'is-invalid');
                document.getElementById(iconId).className = 'validation-icon';
                document.getElementById(iconId).innerHTML = '';
                document.getElementById(fbId).className = 'field-feedback';
                document.getElementById(fbId).innerHTML = '';
            }

            function guardar() {
                if ($('#btnGuardar').prop('disabled')) return;
                setBtnLoading('#btnGuardar', true, 'Guardando…');
                $.ajax({
                        url: '{{ route('admin.posgrads.guardar') }}',
                        type: 'POST',
                        data: {
                            _token: CSRF,
                            nombre: $('#nombreCrear').val().trim(),
                            creditaje: $('#creditajeCrear').val() || 0,
                            carga_horaria: $('#cargaHorariaCrear').val() || 0,
                            duracion_numero: $('#duracionNumeroCrear').val() || 0,
                            duracion_unidad: $('#duracionUnidadCrear').val(),
                            dirigido: $('#dirigidoCrear').val().trim(),
                            objetivo: $('#objetivoCrear').val().trim(),
                            estado: $('#estadoCrear').is(':checked') ? 1 : 0,
                            convenio_id: $('#convenioCrear').val(),
                            area_id: $('#areaCrear').val(),
                            tipo_id: $('#tipoCrear').val()
                        }
                    })
                    .done(r => {
                        closeModal('modalCrear');
                        tabla.ajax.reload();
                        toast('success', r.message || 'Posgrado guardado correctamente.');
                    })
                    .fail(xhr => handleAjaxError(xhr, 'nombreCrear', 'iconCrear', 'fbCrear'))
                    .always(() => setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar'));
            }

            function actualizar() {
                if ($('#btnActualizar').prop('disabled')) return;
                const id = $('#idEditar').val();
                setBtnLoading('#btnActualizar', true, 'Actualizando…');
                $.ajax({
                        url: '/admin/posgrads/' + id,
                        type: 'PUT',
                        data: {
                            _token: CSRF,
                            nombre: $('#nombreEditar').val().trim(),
                            creditaje: $('#creditajeEditar').val() || 0,
                            carga_horaria: $('#cargaHorariaEditar').val() || 0,
                            duracion_numero: $('#duracionNumeroEditar').val() || 0,
                            duracion_unidad: $('#duracionUnidadEditar').val(),
                            dirigido: $('#dirigidoEditar').val().trim(),
                            objetivo: $('#objetivoEditar').val().trim(),
                            estado: $('#estadoEditar').is(':checked') ? 1 : 0,
                            convenio_id: $('#convenioEditar').val(),
                            area_id: $('#areaEditar').val(),
                            tipo_id: $('#tipoEditar').val()
                        }
                    })
                    .done(r => {
                        closeModal('modalEditar');
                        tabla.ajax.reload();
                        toast('success', r.message || 'Posgrado actualizado correctamente.');
                    })
                    .fail(xhr => handleAjaxError(xhr, 'nombreEditar', 'iconEditar', 'fbEditar'))
                    .always(() => setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'));
            }

            function eliminarPosgrado(id) {
                setBtnLoading('#btnConfirmarEliminar', true,
                    '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
                $.ajax({
                        url: '/admin/posgrads/' + id,
                        type: 'DELETE',
                        data: {
                            _token: CSRF
                        }
                    })
                    .done(r => {
                        closeModal('modalEliminar');
                        tabla.ajax.reload();
                        toast('success', r.message || 'Posgrado eliminado correctamente.');
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

            function handleAjaxError(xhr, inputId, iconId, fbId) {
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
                    toast('error', 'Ocurrió un error. Intente nuevamente.');
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
                    '"></i></div>' + '<div class="toast-body-text"><span>' + mensaje + '</span></div>' +
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

    {{-- ===== MODULOS ===== --}}
    <div class="modal fade" id="modalModulos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:1100px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-stack-line" style="color:#6366f1;"></i> Modulos - <span
                            id="modulosOfertaCodigo"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modulosOfertaId">
                    <input type="hidden" id="modulosNModulos">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary" id="modulosCountBadge">0 módulos</span>
                        <span class="text-muted" style="font-size:0.8rem;">Complete los datos de cada módulo y presione
                            Guardar</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;text-align:center;">N°</th>
                                    <th>Nombre del Módulo <span class="req">*</span></th>
                                    <th style="width:130px;">Fecha Inicio <span class="req">*</span></th>
                                    <th style="width:130px;">Fecha Fin <span class="req">*</span></th>
                                    <th style="width:220px;">Docente (Carnet)</th>
                                    <th style="width:60px;text-align:center;">Color</th>
                                    <th style="width:80px;text-align:center;">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="modulosTableBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="ri-close-line"></i> Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarModulos"><i class="ri-save-line"></i>
                        Guardar Todos los Módulos</button>
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
                    <div class="mb-3"><i class="ri-user-star-line" style="font-size:3rem;color:#f59e0b;"></i></div>
                    <p class="mb-2">Se encontró a la persona:</p>
                    <h6 class="fw-bold" id="confirmPersonaNombre"></h6>
                    <p class="text-muted" style="font-size:0.85rem;">Carnet: <span id="confirmPersonaCarnet"></span></p>
                    <p class="mt-3" style="font-size:0.9rem;">Esta persona no está registrada como
                        docente.<br><strong>¿Desea registrarla como docente y asignarla?</strong></p>
                    <input type="hidden" id="confirmPersonaId">
                    <input type="hidden" id="confirmModuloRow">
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
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:750px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-user-add-line" style="color:#10b981;"></i> Registrar Nueva
                        Persona como Docente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">

                    <p class="section-sep"><i class="ri-id-card-line"></i> Datos de Identidad</p>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label"><i class="ri-id-card-line" style="color:#10b981;"></i> Carnet <span
                                    class="req">*</span></label>
                            <input type="text" class="form-control" id="regDocenteCarnet" placeholder="Ej: 12345678"
                                maxlength="20">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-map-pin-line" style="color:#10b981;"></i>
                                Expedido</label>
                            <input type="text" class="form-control" id="regDocenteExpedido" placeholder="Ej: LP"
                                maxlength="10">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-calendar-line" style="color:#10b981;"></i> Fecha de
                                Nacimiento</label>
                            <input type="date" class="form-control" id="regDocenteFechaNacimiento">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label"><i class="ri-user-line" style="color:#10b981;"></i> Nombres
                                <span class="req">*</span></label>
                            <input type="text" class="form-control" id="regDocenteNombres"
                                placeholder="Ej: Juan Carlos" maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-user-3-line" style="color:#10b981;"></i>
                                Sexo</label>
                            <select class="form-select" id="regDocenteSexo">
                                <option value="">— Seleccione —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Apellido Paterno <span class="req">*</span></label>
                            <input type="text" class="form-control" id="regDocenteApPaterno"
                                placeholder="Ej: García" maxlength="80">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control" id="regDocenteApMaterno"
                                placeholder="Ej: López" maxlength="80">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-heart-line" style="color:#10b981;"></i> Estado
                                Civil</label>
                            <select class="form-select" id="regDocenteEstadoCivil">
                                <option value="">— Seleccione —</option>
                                <option value="Soltero/a">Soltero/a</option>
                                <option value="Casado/a">Casado/a</option>
                                <option value="Divorciado/a">Divorciado/a</option>
                                <option value="Viudo/a">Viudo/a</option>
                                <option value="Unión Libre">Unión Libre</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-map-2-line" style="color:#10b981;"></i>
                                Departamento</label>
                            <select class="form-select" id="regDocenteDepto">
                                <option value="">— Seleccione —</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-building-line" style="color:#10b981;"></i>
                                Ciudad</label>
                            <select class="form-select" id="regDocenteCiudad" disabled>
                                <option value="">— Seleccione depto. —</option>
                            </select>
                        </div>
                    </div>

                    <p class="section-sep" style="margin-top:1.5rem;"><i class="ri-phone-line"></i> Datos de Contacto
                    </p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="ri-mail-line" style="color:#10b981;"></i> Correo
                                Electrónico</label>
                            <input type="email" class="form-control" id="regDocenteCorreo"
                                placeholder="correo@dominio.com" maxlength="150">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-smartphone-line" style="color:#10b981;"></i>
                                Celular</label>
                            <input type="text" class="form-control" id="regDocenteCelular"
                                placeholder="Ej: 70000000" maxlength="20">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-phone-line" style="color:#10b981;"></i>
                                Teléfono</label>
                            <input type="text" class="form-control" id="regDocenteTelefono"
                                placeholder="Ej: 2000000" maxlength="20">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="ri-map-pin-2-line" style="color:#10b981;"></i>
                                Dirección</label>
                            <input type="text" class="form-control" id="regDocenteDireccion"
                                placeholder="Ej: Av. 6 de Agosto N° 123" maxlength="200">
                        </div>
                    </div>

                    <p class="section-sep" style="margin-top:1.5rem;"><i class="ri-graduation-cap-line"></i> Estudios
                        Académicos</p>

                    <div id="regDocEstudiosLock"
                        style="background:rgba(252,123,4,0.04);border:1px dashed var(--d-input-border);border-radius:12px;padding:2rem;text-align:center;">
                        <i class="ri-lock-line" style="font-size:2rem;color:var(--d-muted);"></i>
                        <p style="color:var(--d-muted);font-size:0.85rem;margin:0.5rem 0 0;">Guarda primero los datos de
                            la persona para agregar estudios.</p>
                    </div>

                    <div id="regDocEstudiosActivo" style="display:none;">
                        <div class="estudios-wrap mb-3">
                            <table class="estudios-table">
                                <thead>
                                    <tr>
                                        <th>Grado Académico</th>
                                        <th>Profesión</th>
                                        <th>Universidad</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Principal</th>
                                        <th class="text-center" style="width:90px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="regDocBodyEstudios">
                                    <tr>
                                        <td colspan="6" class="estudios-empty"><i class="ri-inbox-line me-1"></i>
                                            Sin estudios registrados</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="estudio-form-box">
                            <p style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:var(--d-muted);margin-bottom:0.75rem;"
                                id="regDocTituloFormEstudio">
                                <i class="ri-add-circle-line" style="color:#fc7b04;"></i> Agregar nuevo estudio
                            </p>
                            <input type="hidden" id="regDocEstudioId">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.8rem;margin-bottom:0.3rem;"><i
                                            class="ri-medal-line" style="color:#fc7b04;"></i> Grado Académico <span
                                            class="req">*</span></label>
                                    <select class="form-select" id="regDocGradoEstudio">
                                        <option value="">— Seleccione —</option>
                                    </select>
                                    <div class="field-feedback" id="fbRegDocGradoEstudio"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.8rem;margin-bottom:0.3rem;"><i
                                            class="ri-briefcase-line" style="color:#fc7b04;"></i> Profesión</label>
                                    <select class="form-select" id="regDocProfesionEstudio">
                                        <option value="">— Seleccione —</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.8rem;margin-bottom:0.3rem;"><i
                                            class="ri-building-2-line" style="color:#fc7b04;"></i> Universidad</label>
                                    <select class="form-select" id="regDocUniversidadEstudio">
                                        <option value="">— Seleccione —</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.8rem;margin-bottom:0.3rem;"><i
                                            class="ri-toggle-line" style="color:#fc7b04;"></i> Estado <span
                                            class="req">*</span></label>
                                    <select class="form-select" id="regDocEstadoEstudio">
                                        <option value="Concluido">Concluido</option>
                                        <option value="En Desarrollo">En Desarrollo</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end pb-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="regDocPrincipalEstudio">
                                        <label class="form-check-label" for="regDocPrincipalEstudio"
                                            style="font-size:0.83rem;font-weight:600;color:var(--d-title);">
                                            <i class="ri-star-line" style="color:#fc7b04;"></i> Estudio Principal
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end gap-2">
                                    <button type="button" class="btn btn-modal-submit flex-fill"
                                        id="regDocBtnGuardarEstudio" style="padding:0.55rem 0.9rem;font-size:0.83rem;">
                                        <i class="ri-add-line"></i> <span id="regDocLabelBtnEstudio">Agregar</span>
                                    </button>
                                    <button type="button" class="btn btn-modal-cancel" id="regDocBtnCancelarEstudio"
                                        style="display:none;padding:0.55rem 0.9rem;font-size:0.83rem;">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="regDocenteModuloRow">
                    <input type="hidden" id="regDocentePersonaId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="ri-close-line"></i> Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarPersonaDocente"><i
                            class="ri-save-line"></i> Guardar Persona</button>
                    <button type="button" class="btn btn-primary" id="btnFinalizarRegistroDocente"
                        style="display:none;"><i class="ri-check-line"></i> Finalizar y Asignar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            let currentOfertaModulos = null;
            let pendingDocenteSearch = null;
            let pendingDocenteRow = null;
            const CSRF = '{{ csrf_token() }}';
            const defaultColors = ['#6366f1', '#8b5cf6', '#ec4899', '#ef4444', '#f59e0b', '#22c55e', '#06b6d4',
                '#3b82f6', '#14b8a6', '#f97316', '#a855f7', '#e11d48'
            ];

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

            function toast(tipo, mensaje) {
                const iconMap = {
                    success: 'ri-check-double-line',
                    error: 'ri-close-circle-line',
                    warning: 'ri-alert-line'
                };
                const el = document.createElement('div');
                el.className = 'toast-notify ' + tipo;
                el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') +
                    '"></i></div><div class="toast-body-text"><span>' + mensaje +
                    '</span></div><button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';
                let c = document.getElementById('toastContainer');
                if (!c) {
                    c = document.createElement('div');
                    c.id = 'toastContainer';
                    c.className = 'toast-container';
                    document.body.appendChild(c);
                }
                c.appendChild(el);
                el.querySelector('.toast-close').addEventListener('click', () => {
                    el.classList.add('hiding');
                    el.addEventListener('animationend', () => el.remove(), {
                        once: true
                    });
                });
                setTimeout(() => {
                    el.classList.add('hiding');
                    el.addEventListener('animationend', () => el.remove(), {
                        once: true
                    });
                }, 4500);
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

            $(document).on('click', '.btn-modulos-oferta', function() {
                currentOfertaModulos = $(this).data('id');
                const nModulos = parseInt($(this).data('n-modulos')) || 0;
                const codigo = $(this).data('codigo') || '';
                if (nModulos < 1) {
                    toast('warning', 'La oferta no tiene módulos definidos.');
                    return;
                }
                $('#modulosOfertaId').val(currentOfertaModulos);
                $('#modulosNModulos').val(nModulos);
                $('#modulosOfertaCodigo').text(codigo);
                buildModulosTable(nModulos);
                openModal('modalModulos');
            });

            $('#modalModulos').on('shown.bs.modal', function() {
                setTimeout(function() {
                    var container = document.getElementById('modulosTableBody');
                    if (!container) return;

                    var existingInputs = container.querySelectorAll('.mod-docente-search');
                    existingInputs.forEach(function(oldInput) {
                        var dataRow = oldInput.getAttribute('data-row');
                        var placeholder = oldInput.getAttribute('placeholder') || 'Carnet...';
                        var currentVal = oldInput.value;

                        var parent = oldInput.parentNode;
                        while (parent && parent.tagName !== 'DIV') {
                            parent = parent.parentNode;
                        }
                        if (!parent) parent = oldInput.parentNode;

                        var newInput = document.createElement('input');
                        newInput.type = 'text';
                        newInput.className = 'mod-docente-search';
                        newInput.setAttribute('data-row', dataRow);
                        newInput.setAttribute('placeholder', placeholder);
                        newInput.setAttribute('autocomplete', 'off');
                        newInput.value = currentVal;
                        newInput.style.cssText =
                            'flex:1;min-width:80px;padding:4px 8px;font-size:13px;border:1px solid #ced4da;border-radius:4px;background:#fff;color:#000;outline:none;width:100%;display:block;';

                        var firstChild = parent.firstChild;
                        if (firstChild) {
                            parent.insertBefore(newInput, firstChild);
                        } else {
                            parent.appendChild(newInput);
                        }
                        oldInput.remove();

                        newInput.focus();
                    });
                }, 2000);
            });

            function buildModulosTable(nModulos) {
                const $tbody = $('#modulosTableBody');
                $tbody.empty();
                for (let i = 1; i <= nModulos; i++) {
                    const color = defaultColors[(i - 1) % defaultColors.length];
                    const html =
                        '<tr data-row="' + i + '">' +
                        '<td class="text-center"><span class="badge bg-primary">' + i + '</span></td>' +
                        '<td><input type="text" class="form-control form-control-sm mod-nombre" data-row="' + i +
                        '" placeholder="Nombre del módulo ' + i + '" maxlength="200"></td>' +
                        '<td><input type="date" class="form-control form-control-sm mod-fecha-inicio" data-row="' + i +
                        '"></td>' +
                        '<td><input type="date" class="form-control form-control-sm mod-fecha-fin" data-row="' + i +
                        '"></td>' +
                        '<td>' +
                        '<div style="display:flex;gap:4px;align-items:center;">' +
                        '<input type="text" class="mod-docente-search" data-row="' + i +
                        '" placeholder="Carnet..." style="flex:1;min-width:80px;padding:4px 8px;font-size:13px;border:1px solid #ced4da;border-radius:4px;background:#fff;color:#000;" />' +
                        '<button class="mod-docente-search-btn btn btn-sm btn-outline-primary" type="button" data-row="' +
                        i + '" style="padding:4px 8px;white-space:nowrap;"><i class="ri-search-line"></i></button>' +
                        '<button class="mod-docente-clear btn btn-sm btn-outline-danger" type="button" data-row="' + i +
                        '" style="display:none;padding:4px 8px;"><i class="ri-close-line"></i></button>' +
                        '</div>' +
                        '<input type="hidden" class="mod-docente-id" data-row="' + i + '">' +
                        '<input type="hidden" class="mod-docente-nombre" data-row="' + i + '">' +
                        '<div class="mod-docente-preview mt-1" data-row="' + i + '" style="display:none;">' +
                        '<div class="d-flex align-items-center gap-1 p-1 rounded" style="background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.12);">' +
                        '<div class="rounded-circle d-flex align-items-center justify-content-center mod-docente-avatar" style="width:28px;height:28px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;font-weight:700;font-size:0.7rem;flex-shrink:0;">D</div>' +
                        '<div class="mod-docente-info" style="min-width:0;"><div class="fw-semibold mod-docente-nombre-display" style="font-size:0.75rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></div><div style="font-size:0.65rem;color:#6c757d;" class="mod-docente-carnet-display"></div></div>' +
                        '</div>' +
                        '</div>' +
                        '</td>' +
                        '<td class="text-center">' +
                        '<div style="display:flex;align-items:center;justify-content:center;gap:4px;">' +
                        '<input type="color" class="mod-color" data-row="' + i + '" value="' + color +
                        '" style="width:36px;height:30px;border:2px solid #dee2e6;border-radius:6px;cursor:pointer;padding:1px;background:#fff;">' +
                        '<span class="mod-color-label" data-row="' + i +
                        '" style="font-size:0.65rem;color:#666;font-family:monospace;">' + color + '</span>' +
                        '</div>' +
                        '</td>' +
                        '<td class="text-center"><span class="badge bg-warning mod-estado" data-row="' + i +
                        '">No Inicio</span></td>' +
                        '</tr>';
                    $tbody.append(html);
                }
                $('#modulosCountBadge').text(nModulos + ' módulos');
                loadExistingModulos();
            }

            function loadExistingModulos() {
                const ofertaId = $('#modulosOfertaId').val();
                $.ajax({
                        url: '/admin/posgrads/ofertas/' + ofertaId + '/modulos/listar'
                    })
                    .done(function(r) {
                        const modulos = r.data || [];
                        modulos.forEach(function(mod) {
                            const n = mod.n_modulo;
                            const $row = $('#modulosTableBody tr[data-row="' + n + '"]');
                            if ($row.length) {
                                $row.find('.mod-nombre').val(mod.nombre);
                                const fechaInicio = mod.fecha_inicio ? mod.fecha_inicio.substring(0, 10) :
                                    '';
                                const fechaFin = mod.fecha_fin ? mod.fecha_fin.substring(0, 10) : '';
                                $row.find('.mod-fecha-inicio').val(fechaInicio);
                                $row.find('.mod-fecha-fin').val(fechaFin);
                                $row.find('.mod-color').val(mod.color || '#6366f1');
                                $row.find('.mod-color-label').text(mod.color || '#6366f1');
                                if (mod.estado && mod.estado !== 'No Inicio') {
                                    const colors = {
                                        'En Desarrollo': 'bg-success',
                                        'Concluido': 'bg-info'
                                    };
                                    $row.find('.mod-estado').removeClass('bg-warning bg-success bg-info')
                                        .addClass(colors[mod.estado] || 'bg-secondary').text(mod.estado);
                                }
                                if (mod.docente && mod.docente.persona) {
                                    const p = mod.docente.persona;
                                    const nombre = (p.nombres || '') + ' ' + (p.apellido_paterno || '') +
                                        ' ' + (p.apellido_materno || '');
                                    $row.find('.mod-docente-id').val(mod.docente_id);
                                    $row.find('.mod-docente-nombre').val(nombre.trim());
                                    showDocentePreviewInRow(n, nombre.trim(), p.carnet || '');
                                }
                            }
                        });
                    })
                    .fail(function() {});
            }

            function showDocentePreviewInRow(row, nombre, carnet) {
                const $row = $('#modulosTableBody tr[data-row="' + row + '"]');
                $row.find('.mod-docente-preview').show();
                $row.find('.mod-docente-avatar').text(nombre ? nombre.charAt(0).toUpperCase() : 'D');
                $row.find('.mod-docente-nombre-display').text(nombre);
                $row.find('.mod-docente-carnet-display').text('CI: ' + carnet);
                $row.find('.mod-docente-clear').show();
                var input = $row.find('.mod-docente-search')[0];
                if (input) {
                    input.readOnly = false;
                    input.disabled = false;
                    input.removeAttribute('readonly');
                    input.removeAttribute('disabled');
                    input.style.cssText =
                        'flex:1;min-width:80px;padding:4px 8px;font-size:13px;border:1px solid #ced4da;border-radius:4px;background:#fff;color:#000;pointer-events:auto !important;user-select:text !important;';
                }
            }

            function hideDocentePreviewInRow(row) {
                const $row = $('#modulosTableBody tr[data-row="' + row + '"]');
                $row.find('.mod-docente-preview').hide();
                $row.find('.mod-docente-nombre-display').text('');
                $row.find('.mod-docente-carnet-display').text('');
                $row.find('.mod-docente-id').val('');
                $row.find('.mod-docente-nombre').val('');
                var input = $row.find('.mod-docente-search')[0];
                if (input) {
                    input.value = '';
                    input.readOnly = false;
                    input.disabled = false;
                    input.removeAttribute('readonly');
                    input.removeAttribute('disabled');
                    input.style.cssText =
                        'flex:1;min-width:80px;padding:4px 8px;font-size:13px;border:1px solid #ced4da;border-radius:4px;background:#fff;color:#000;pointer-events:auto !important;user-select:text !important;';
                }
                $row.find('.mod-docente-clear').hide();
            }

            $(document).on('click', '.mod-docente-clear', function() {
                const row = $(this).data('row');
                hideDocentePreviewInRow(row);
            });

            $(document).on('input change', '.mod-color', function() {
                const row = $(this).data('row');
                const color = $(this).val();
                $('#modulosTableBody tr[data-row="' + row + '"] .mod-color-label').text(color);
            });

            let docenteSearchTimeout = null;

            $(document).on('click', '.mod-docente-search-btn', function() {
                const row = $(this).data('row');
                const input = document.querySelector('.mod-docente-search[data-row="' + row + '"]');
                if (!input) return;
                const carnet = input.value.trim();
                if (carnet.length < 2) {
                    toast('warning', 'Ingrese al menos 2 caracteres del carnet.');
                    return;
                }
                pendingDocenteRow = row;
                clearTimeout(docenteSearchTimeout);
                buscarDocentePorCarnet(carnet, row);
            });

            $(document).on('keypress', '.mod-docente-search', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    const row = $(this).data('row');
                    $(this).closest('td').find('.mod-docente-search-btn').trigger('click');
                }
            });

            function buscarDocentePorCarnet(carnet, row) {
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
                            const $row = $('#modulosTableBody tr[data-row="' + row + '"]');
                            $row.find('.mod-docente-id').val(r.docente.id);
                            $row.find('.mod-docente-nombre').val(r.docente.nombre);
                            showDocentePreviewInRow(row, r.docente.nombre, r.docente.carnet);
                            toast('success', 'Docente encontrado: ' + r.docente.nombre);
                        } else if (r.persona_encontrada && !r.es_docente) {
                            $('#confirmPersonaNombre').text(r.persona.nombre);
                            $('#confirmPersonaCarnet').text(r.persona.carnet);
                            $('#confirmPersonaId').val(r.persona.id);
                            $('#confirmModuloRow').val(row);
                            openModal('modalConfirmarDocente');
                        } else {
                            resetRegistroDocenteForm();
                            $('#regDocenteCarnet').val(carnet);
                            $('#regDocenteModuloRow').val(row);
                            openModal('modalRegistrarPersonaDocente');
                        }
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 404) {
                            resetRegistroDocenteForm();
                            $('#regDocenteCarnet').val(carnet);
                            $('#regDocenteModuloRow').val(row);
                            openModal('modalRegistrarPersonaDocente');
                        } else {
                            toast('error', 'Error al buscar docente.');
                        }
                    });
            }

            $('#btnConfirmarDocente').on('click', function() {
                const personaId = $('#confirmPersonaId').val();
                const row = $('#confirmModuloRow').val();
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
                        showDocentePreviewInRow(row, r.data.nombre, '');
                        const $row = $('#modulosTableBody tr[data-row="' + row + '"]');
                        $row.find('.mod-docente-id').val(r.data.id);
                        $row.find('.mod-docente-nombre').val(r.data.nombre);
                        toast('success', r.message || 'Docente registrado.');
                    })
                    .fail(function() {
                        toast('error', 'Error al registrar docente.');
                    })
                    .always(function() {
                        setBtnLoading('#btnConfirmarDocente', false,
                            '<i class="ri-check-line"></i> Sí, registrar como docente');
                    });
            });

            let regDocEstudios = [];
            let regDocModoEdicion = false;

            function cargarSelectoresRegistroDocente() {
                $.getJSON('{{ route('admin.personas.listarDepartamentos') }}', function(r) {
                    const opts = r.data.map(d => '<option value="' + d.id + '">' + escHtml(d.nombre) +
                        '</option>').join('');
                    $('#regDocenteDepto').find('option:not(:first)').remove();
                    $('#regDocenteDepto').append(opts);
                });
                $.getJSON('{{ route('admin.personas.listarCiudades') }}', function(r) {
                    window._regDocTodasCiudades = r.data;
                });
                $.getJSON('{{ route('admin.personas.listarGrados') }}', function(r) {
                    const opts = r.data.map(g => '<option value="' + g.id + '">' + escHtml(g.nombre) +
                        '</option>').join('');
                    $('#regDocGradoEstudio').find('option:not(:first)').remove();
                    $('#regDocGradoEstudio').append(opts);
                });
                $.getJSON('{{ route('admin.personas.listarProfesiones') }}', function(r) {
                    const opts = r.data.map(p => '<option value="' + p.id + '">' + escHtml(p.nombre) +
                        '</option>').join('');
                    $('#regDocProfesionEstudio').find('option:not(:first)').remove();
                    $('#regDocProfesionEstudio').append(opts);
                });
                $.getJSON('{{ route('admin.personas.listarUniversidades') }}', function(r) {
                    const opts = r.data.map(u => '<option value="' + u.id + '">' + escHtml(u.nombre) + (u
                        .sigla ? ' (' + u.sigla + ')' : '') + '</option>').join('');
                    $('#regDocUniversidadEstudio').find('option:not(:first)').remove();
                    $('#regDocUniversidadEstudio').append(opts);
                });
            }

            $('#modalRegistrarPersonaDocente').on('shown.bs.modal', function() {
                cargarSelectoresRegistroDocente();
            });

            $('#regDocenteDepto').on('change', function() {
                const deptoId = $(this).val();
                const $ciudad = $('#regDocenteCiudad');
                $ciudad.find('option:not(:first)').remove();
                if (!deptoId) {
                    $ciudad.prop('disabled', true).find('option:first').text('— Seleccione depto. —');
                    return;
                }
                const filtradas = (window._regDocTodasCiudades || []).filter(c => c.departamento_id == deptoId);
                $ciudad.append(filtradas.map(c => '<option value="' + c.id + '">' + escHtml(c.nombre) +
                    '</option>').join(''));
                $ciudad.prop('disabled', false).find('option:first').text('— Seleccione ciudad —');
            });

            function resetRegistroDocenteForm() {
                $('#regDocenteCarnet, #regDocenteExpedido, #regDocenteFechaNacimiento, #regDocenteNombres, #regDocenteApPaterno, #regDocenteApMaterno, #regDocenteCorreo, #regDocenteCelular, #regDocenteTelefono, #regDocenteDireccion')
                    .val('');
                $('#regDocenteSexo, #regDocenteEstadoCivil, #regDocenteDepto').val('');
                $('#regDocenteCiudad').val('').prop('disabled', true).find('option:not(:first)').remove();
                $('#regDocenteCiudad').find('option:first').text('— Seleccione depto. —');
                $('#regDocenteModuloRow').val('');
                $('#regDocentePersonaId').val('');
                $('#regDocEstudiosLock').show();
                $('#regDocEstudiosActivo').hide();
                $('#btnGuardarPersonaDocente').show();
                $('#btnFinalizarRegistroDocente').hide();
                regDocEstudios = [];
                regDocModoEdicion = false;
                resetFormEstudioDocente();
            }

            function renderTablaEstudiosDocente() {
                const $tbody = $('#regDocBodyEstudios');
                if (!regDocEstudios.length) {
                    $tbody.html(
                        '<tr><td colspan="6" class="estudios-empty"><i class="ri-inbox-line me-1"></i> Sin estudios registrados</td></tr>'
                    );
                    return;
                }
                let html = '';
                regDocEstudios.forEach(function(e, idx) {
                    const grado = e.grado_nombre || '<span style="color:var(--d-muted)">—</span>';
                    const profesion = e.profesion_nombre || '<span style="color:var(--d-muted)">—</span>';
                    const universidad = e.universidad_nombre || '<span style="color:var(--d-muted)">—</span>';
                    const estadoBadge = e.estado === 'En Desarrollo' ? 'bg-warning' : 'bg-success';
                    const principal = e.principal ? '<span class="badge bg-success">Sí</span>' :
                        '<span class="badge bg-secondary">No</span>';
                    const dataAttr = JSON.stringify(e).replace(/'/g, '&#39;');
                    html += '<tr>' +
                        '<td>' + grado + '</td>' +
                        '<td>' + profesion + '</td>' +
                        '<td>' + universidad + '</td>' +
                        '<td class="text-center"><span class="badge ' + estadoBadge + '">' + e.estado +
                        '</span></td>' +
                        '<td class="text-center">' + principal + '</td>' +
                        '<td class="text-center">' +
                        '<button class="btn btn-action btn-action-edit btn-edit-regdoc-estudio" data-idx="' +
                        idx + '" title="Editar"><i class="ri-pencil-fill"></i></button>' +
                        '<button class="btn btn-action btn-action-delete btn-del-regdoc-estudio" data-idx="' +
                        idx + '" title="Eliminar"><i class="ri-delete-bin-fill"></i></button>' +
                        '</td>' +
                        '</tr>';
                });
                $tbody.html(html);
            }

            function resetFormEstudioDocente() {
                $('#regDocEstudioId').val('');
                $('#regDocGradoEstudio').val('');
                $('#regDocProfesionEstudio').val('');
                $('#regDocUniversidadEstudio').val('');
                $('#regDocEstadoEstudio').val('Concluido');
                $('#regDocPrincipalEstudio').prop('checked', false);
                $('#fbRegDocGradoEstudio').removeClass('error success').html('');
                $('#regDocBtnCancelarEstudio').hide();
                $('#regDocLabelBtnEstudio').text('Agregar');
                $('#regDocTituloFormEstudio').html(
                    '<i class="ri-add-circle-line" style="color:#fc7b04;"></i> Agregar nuevo estudio');
                regDocModoEdicion = false;
            }

            function cargarEstudioEnFormDocente(e, idx) {
                $('#regDocEstudioId').val(idx);
                $('#regDocGradoEstudio').val(e.grado_id || '');
                $('#regDocProfesionEstudio').val(e.profesion_id || '');
                $('#regDocUniversidadEstudio').val(e.universidad_id || '');
                $('#regDocEstadoEstudio').val(e.estado || 'Concluido');
                $('#regDocPrincipalEstudio').prop('checked', !!e.principal);
                $('#regDocBtnCancelarEstudio').show();
                $('#regDocLabelBtnEstudio').text('Actualizar');
                $('#regDocTituloFormEstudio').html(
                    '<i class="ri-pencil-line" style="color:#fc7b04;"></i> Editar estudio');
                regDocModoEdicion = true;
            }

            $('#regDocBtnGuardarEstudio').on('click', function() {
                const gradoId = $('#regDocGradoEstudio').val();
                if (!gradoId) {
                    $('#fbRegDocGradoEstudio').addClass('error').html(
                        '<i class="ri-error-warning-line"></i> El grado académico es obligatorio.');
                    return;
                }
                $('#fbRegDocGradoEstudio').removeClass('error').html('');
                const estudio = {
                    grado_id: gradoId,
                    grado_nombre: $('#regDocGradoEstudio option:selected').text(),
                    profesion_id: $('#regDocProfesionEstudio').val() || '',
                    profesion_nombre: $('#regDocProfesionEstudio option:selected').text() || '',
                    universidad_id: $('#regDocUniversidadEstudio').val() || '',
                    universidad_nombre: $('#regDocUniversidadEstudio option:selected').text() || '',
                    estado: $('#regDocEstadoEstudio').val(),
                    principal: $('#regDocPrincipalEstudio').is(':checked')
                };
                if (regDocModoEdicion) {
                    const idx = parseInt($('#regDocEstudioId').val());
                    regDocEstudios[idx] = estudio;
                    toast('success', 'Estudio actualizado.');
                } else {
                    regDocEstudios.push(estudio);
                    toast('success', 'Estudio agregado.');
                }
                renderTablaEstudiosDocente();
                resetFormEstudioDocente();
            });

            $('#regDocBtnCancelarEstudio').on('click', resetFormEstudioDocente);

            $(document).on('click', '.btn-edit-regdoc-estudio', function() {
                const idx = parseInt($(this).data('idx'));
                cargarEstudioEnFormDocente(regDocEstudios[idx], idx);
            });

            $(document).on('click', '.btn-del-regdoc-estudio', function() {
                const idx = parseInt($(this).data('idx'));
                if (!confirm('¿Eliminar este estudio?')) return;
                regDocEstudios.splice(idx, 1);
                renderTablaEstudiosDocente();
                if (regDocModoEdicion) resetFormEstudioDocente();
                toast('success', 'Estudio eliminado.');
            });

            $('#btnGuardarPersonaDocente').on('click', function() {
                const carnet = $('#regDocenteCarnet').val().trim();
                const nombres = $('#regDocenteNombres').val().trim();
                const apPaterno = $('#regDocenteApPaterno').val().trim();
                const row = $('#regDocenteModuloRow').val();
                if (!carnet || !nombres || !apPaterno) {
                    toast('warning', 'Complete carnet, nombres y apellido paterno.');
                    return;
                }
                setBtnLoading('#btnGuardarPersonaDocente', true,
                    '<span class="spinner-border spinner-border-sm"></span> Guardando…');
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
                            expedido: $('#regDocenteExpedido').val().trim(),
                            fecha_nacimiento: $('#regDocenteFechaNacimiento').val(),
                            sexo: $('#regDocenteSexo').val(),
                            estado_civil: $('#regDocenteEstadoCivil').val(),
                            departamento_id: $('#regDocenteDepto').val() || null,
                            ciudad_id: $('#regDocenteCiudad').val() || null,
                            telefono: $('#regDocenteTelefono').val().trim(),
                            direccion: $('#regDocenteDireccion').val().trim()
                        }
                    })
                    .done(function(r) {
                        const personaId = r.data.id || r.persona_id;
                        const nombreCompleto = r.data.nombre || (nombres + ' ' + apPaterno);
                        const carnetResp = r.data.carnet || carnet;
                        $('#regDocentePersonaId').val(personaId);
                        $('#regDocEstudiosLock').hide();
                        $('#regDocEstudiosActivo').show();
                        $('#btnGuardarPersonaDocente').hide();
                        $('#btnFinalizarRegistroDocente').show();
                        toast('success', (r.message || 'Persona guardada.') +
                            ' Ahora puedes agregar estudios.');
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errs = xhr.responseJSON.errors;
                            if (errs.carnet) toast('error', 'Carnet: ' + errs.carnet[0]);
                            else if (errs.nombres) toast('error', 'Nombres: ' + errs.nombres[0]);
                            else toast('error', 'Errores de validación.');
                        } else {
                            toast('error', 'Error al registrar.');
                        }
                    })
                    .always(function() {
                        setBtnLoading('#btnGuardarPersonaDocente', false,
                            '<i class="ri-save-line"></i> Guardar Persona');
                    });
            });

            $('#btnFinalizarRegistroDocente').on('click', function() {
                const personaId = $('#regDocentePersonaId').val();
                const row = $('#regDocenteModuloRow').val();
                if (!personaId) {
                    toast('error', 'Error: no se obtuvo el ID de la persona.');
                    return;
                }

                const estudiosData = regDocEstudios.map(function(e) {
                    return {
                        grado_id: e.grado_id,
                        profesion_id: e.profesion_id || null,
                        universidad_id: e.universidad_id || null,
                        estado: e.estado,
                        principal: e.principal ? 1 : 0
                    };
                });

                setBtnLoading('#btnFinalizarRegistroDocente', true,
                    '<span class="spinner-border spinner-border-sm"></span> Finalizando…');

                function asignarDocenteYContinuar(docenteId, nombreDocente, carnetDocente) {
                    const $row = $('#modulosTableBody tr[data-row="' + row + '"]');
                    $row.find('.mod-docente-id').val(docenteId);
                    $row.find('.mod-docente-nombre').val(nombreDocente);
                    showDocentePreviewInRow(row, nombreDocente, carnetDocente);
                    closeModal('modalRegistrarPersonaDocente');
                    loadExistingModulos();
                    toast('success', 'Docente asignado al módulo correctamente.');
                }

                if (estudiosData.length > 0) {
                    $.ajax({
                            url: '/admin/posgrads/modulos/registrar-estudios-docente',
                            type: 'POST',
                            data: {
                                _token: CSRF,
                                persona_id: personaId,
                                estudios: estudiosData
                            }
                        })
                        .done(function(r) {
                            $.ajax({
                                    url: '{{ route('admin.posgrads.modulos.buscar-docente') }}',
                                    type: 'POST',
                                    data: {
                                        _token: CSRF,
                                        carnet: $('#regDocenteCarnet').val().trim()
                                    }
                                })
                                .done(function(r2) {
                                    if (r2.es_docente) {
                                        asignarDocenteYContinuar(r2.docente.id, r2.docente.nombre, r2
                                            .docente.carnet);
                                    } else {
                                        const nombre = r.data.nombre || ($('#regDocenteNombres').val()
                                            .trim() + ' ' + $('#regDocenteApPaterno').val().trim());
                                        asignarDocenteYContinuar(personaId, nombre, $(
                                            '#regDocenteCarnet').val().trim());
                                    }
                                })
                                .fail(function() {
                                    const nombre = r.data.nombre || ($('#regDocenteNombres').val()
                                        .trim() + ' ' + $('#regDocenteApPaterno').val().trim());
                                    asignarDocenteYContinuar(personaId, nombre, $('#regDocenteCarnet')
                                        .val().trim());
                                })
                                .always(function() {
                                    setBtnLoading('#btnFinalizarRegistroDocente', false,
                                        '<i class="ri-check-line"></i> Finalizar y Asignar');
                                });
                        })
                        .fail(function() {
                            toast('error', 'Error al guardar estudios, pero la persona fue registrada.');
                            setBtnLoading('#btnFinalizarRegistroDocente', false,
                                '<i class="ri-check-line"></i> Finalizar y Asignar');
                        });
                } else {
                    $.ajax({
                            url: '{{ route('admin.posgrads.modulos.buscar-docente') }}',
                            type: 'POST',
                            data: {
                                _token: CSRF,
                                carnet: $('#regDocenteCarnet').val().trim()
                            }
                        })
                        .done(function(r) {
                            if (r.es_docente) {
                                asignarDocenteYContinuar(r.docente.id, r.docente.nombre, r.docente.carnet);
                            } else {
                                const nombre = $('#regDocenteNombres').val().trim() + ' ' + $(
                                    '#regDocenteApPaterno').val().trim();
                                asignarDocenteYContinuar(personaId, nombre, $('#regDocenteCarnet').val()
                                    .trim());
                            }
                        })
                        .fail(function() {
                            const nombre = $('#regDocenteNombres').val().trim() + ' ' + $(
                                '#regDocenteApPaterno').val().trim();
                            asignarDocenteYContinuar(personaId, nombre, $('#regDocenteCarnet').val()
                                .trim());
                        })
                        .always(function() {
                            setBtnLoading('#btnFinalizarRegistroDocente', false,
                                '<i class="ri-check-line"></i> Finalizar y Asignar');
                        });
                }
            });

            $('#btnGuardarModulos').on('click', function() {
                const ofertaId = $('#modulosOfertaId').val();
                const modulos = [];
                let hasError = false;
                let firstErrorRow = null;

                $('#modulosTableBody tr').each(function() {
                    const row = $(this).data('row');
                    const nombre = $(this).find('.mod-nombre').val().trim();
                    const fechaInicio = $(this).find('.mod-fecha-inicio').val();
                    const fechaFin = $(this).find('.mod-fecha-fin').val();
                    const color = $(this).find('.mod-color').val();
                    const docenteId = $(this).find('.mod-docente-id').val() || null;

                    if (!nombre) {
                        if (!hasError) {
                            toast('warning', 'El módulo ' + row + ' requiere un nombre.');
                            firstErrorRow = row;
                        }
                        hasError = true;
                        return;
                    }
                    if (!fechaInicio) {
                        if (!hasError) {
                            toast('warning', 'El módulo ' + row + ' requiere fecha de inicio.');
                            firstErrorRow = row;
                        }
                        hasError = true;
                        return;
                    }
                    if (!fechaFin) {
                        if (!hasError) {
                            toast('warning', 'El módulo ' + row + ' requiere fecha de fin.');
                            firstErrorRow = row;
                        }
                        hasError = true;
                        return;
                    }
                    if (fechaFin < fechaInicio) {
                        if (!hasError) {
                            toast('warning', 'En el módulo ' + row +
                                ', la fecha de fin debe ser mayor o igual al inicio.');
                            firstErrorRow = row;
                        }
                        hasError = true;
                        return;
                    }

                    modulos.push({
                        n_modulo: row,
                        nombre: nombre,
                        color: color,
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin,
                        docente_id: docenteId,
                    });
                });

                if (hasError) return;
                if (modulos.length === 0) {
                    toast('warning', 'No hay módulos para registrar.');
                    return;
                }

                setBtnLoading('#btnGuardarModulos', true, 'Guardando…');
                $.ajax({
                        url: '{{ route('admin.posgrads.modulos.guardar-batch') }}',
                        type: 'POST',
                        data: {
                            _token: CSRF,
                            ofertas_academica_id: ofertaId,
                            modulos: modulos
                        }
                    })
                    .done(function(r) {
                        closeModal('modalModulos');
                        toast('success', r.message || 'Módulos registrados correctamente.');
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errs = xhr.responseJSON.errors;
                            if (errs.modulos) toast('error', 'Error en módulos: ' + JSON.stringify(errs
                                .modulos));
                            else toast('error', 'Errores de validación. Revise los campos.');
                        } else {
                            toast('error', 'Error al guardar los módulos.');
                        }
                    })
                    .always(function() {
                        setBtnLoading('#btnGuardarModulos', false,
                            '<i class="ri-save-line"></i> Guardar Todos los Módulos');
                    });
            });

            $('#modalModulos').on('hidden.bs.modal', function() {
                $('#modulosTableBody').empty();
            });
        })();
    </script>
@endsection
