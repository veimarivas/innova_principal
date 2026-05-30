@extends('layouts.master')
@section('title') Trabajadores @endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
/* Modal scroll fix */
#modalEditar .modal-body {
    max-height: 65vh;
    overflow-y: auto;
}
#modalRegistro .modal-body {
    max-height: 65vh;
    overflow-y: auto;
}
html[data-bs-theme="dark"] #modalEditar .modal-body {
    max-height: 65vh;
    overflow-y: auto;
}
html[data-bs-theme="dark"] #modalRegistro .modal-body {
    max-height: 65vh;
    overflow-y: auto;
}

/* Search box */
.tw-search-card {
    background: var(--d-card);
    border: 1px solid var(--d-card-border);
    border-radius: 16px;
    box-shadow: var(--d-card-shadow);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.tw-search-row {
    display: flex;
    align-items: flex-end;
    gap: 1rem;
    flex-wrap: wrap;
}
.tw-search-input { flex: 1; min-width: 200px; }
.tw-search-input label {
    font-weight: 600 !important;
    font-size: 0.85rem !important;
    color: var(--d-title) !important;
    margin-bottom: 0.5rem !important;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}
.tw-search-input .form-control {
    background: var(--d-input-bg) !important;
    border: 2px solid var(--d-input-border) !important;
    border-radius: 10px !important;
    padding: 0.75rem 1rem !important;
    font-size: 0.95rem !important;
    color: var(--d-input-color) !important;
    font-weight: 500 !important;
}
.tw-search-input .form-control:focus {
    border-color: #fc7b04 !important;
    box-shadow: 0 0 0 4px rgba(252, 123, 4, 0.12) !important;
}
.tw-btn-search {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: linear-gradient(135deg, #743c04 0%, #9a4904 100%);
    border: none;
    color: #fff !important;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.75rem 1.4rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 3px 10px rgba(116, 60, 4, 0.3);
    white-space: nowrap;
}
.tw-btn-search:hover {
    background: linear-gradient(135deg, #9a4904 0%, #b55804 100%);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(116, 60, 4, 0.4);
    color: #fff !important;
}
.tw-btn-register {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: linear-gradient(135deg, #5a8a30 0%, #6dbf40 100%);
    border: none;
    color: #fff !important;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.75rem 1.4rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 3px 10px rgba(90, 138, 48, 0.3);
    white-space: nowrap;
}
.tw-btn-register:hover {
    background: linear-gradient(135deg, #6dbf40 0%, #82d455 100%);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(90, 138, 48, 0.4);
    color: #fff !important;
}
.tw-btn-register:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}
html[data-bs-theme="dark"] .tw-btn-search { background: #fc7b04; }
html[data-bs-theme="dark"] .tw-btn-search:hover { background: #ff9d4d; }

/* Persona found card */
.tw-persona-found {
    background: var(--d-card);
    border: 1px solid var(--d-card-border);
    border-radius: 16px;
    box-shadow: var(--d-card-shadow);
    overflow: hidden;
    margin-bottom: 1.5rem;
    display: none;
}
.tw-pf-header {
    background: linear-gradient(135deg, #391b04 0%, #743c04 40%, #c96004 100%);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.tw-pf-header h5 {
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.tw-pf-header h5 i { opacity: 0.9; }
.tw-pf-badge {
    background: rgba(255,255,255,0.2);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.25rem 0.65rem;
    border-radius: 6px;
    text-transform: uppercase;
}
.tw-pf-body { padding: 1.25rem 1.5rem; }
.tw-pf-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 0.75rem;
}
.tw-pf-item label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--d-muted);
    margin-bottom: 0.2rem;
    display: block;
}
.tw-pf-item span {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--d-body);
}
.tw-pf-actions {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--d-card-border);
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

/* Cargos selection */
.tw-cargos-section {
    background: var(--d-card);
    border: 1px solid var(--d-card-border);
    border-radius: 16px;
    box-shadow: var(--d-card-shadow);
    overflow: hidden;
    margin-bottom: 1.5rem;
    display: none;
}
.tw-cs-header {
    background: var(--d-header-bg);
    border-bottom: 1px solid var(--d-header-border);
    padding: 1rem 1.5rem;
}
.tw-cs-header h5 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--d-title);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.tw-cs-header h5 i { color: #fc7b04; }
.tw-cs-body { padding: 1.25rem 1.5rem; }
.tw-cargo-check {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border: 1px solid var(--d-row-border);
    border-radius: 10px;
    margin-bottom: 0.5rem;
    transition: all 0.2s;
    cursor: pointer;
}
.tw-cargo-check:hover { background: var(--d-row-hover); }
.tw-cargo-check.checked {
    background: rgba(252, 123, 4, 0.06);
    border-color: rgba(252, 123, 4, 0.3);
}
.tw-cargo-check input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #fc7b04;
    cursor: pointer;
}
.tw-cargo-name {
    font-weight: 600;
    color: var(--d-body);
    font-size: 0.9rem;
    flex: 1;
}
.tw-cargo-fields {
    display: none;
    margin-top: 0.5rem;
    padding: 0.75rem 1rem;
    background: rgba(252, 123, 4, 0.04);
    border-radius: 8px;
    border: 1px dashed var(--d-input-border);
}
.tw-cargo-fields.show { display: block; }
.tw-cargo-fields .row { margin-bottom: 0.5rem; }
.tw-cargo-fields label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--d-title);
    margin-bottom: 0.3rem;
}
.tw-cargo-fields .form-control,
.tw-cargo-fields .form-select {
    background: var(--d-input-bg) !important;
    border: 2px solid var(--d-input-border) !important;
    border-radius: 8px !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.82rem !important;
    color: var(--d-input-color) !important;
}
.tw-cargo-fields .form-control:focus,
.tw-cargo-fields .form-select:focus {
    border-color: #fc7b04 !important;
    box-shadow: 0 0 0 3px rgba(252, 123, 4, 0.12) !important;
}
.tw-cargo-fields .tw-sucursal-row {
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px dashed var(--d-row-border);
}
.tw-cargo-fields .tw-sucursal-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--d-muted);
    margin-bottom: 0.3rem;
}

/* Badge cargos */
.badge-cargo-asignado {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: linear-gradient(135deg, rgba(154, 73, 4, 0.12) 0%, rgba(154, 73, 4, 0.06) 100%);
    color: var(--d-badge-color);
    border: 1px solid var(--d-badge-border);
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.25rem 0.6rem;
    border-radius: 6px;
    margin: 0.15rem;
}
.badge-vigente { border-color: rgba(91, 138, 48, 0.3); color: #5a8a30; }
html[data-bs-theme="dark"] .badge-vigente { border-color: rgba(109, 191, 64, 0.3); color: #6dbf40; }
.badge-no-vigente { border-color: rgba(201, 96, 4, 0.3); color: #bc5404; }
html[data-bs-theme="dark"] .badge-no-vigente { border-color: rgba(236, 108, 4, 0.3); color: #ec6c04; }

/* Persona modal form */
.tw-persona-section-title {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: var(--d-muted);
    border-bottom: 1px solid var(--d-card-border);
    padding-bottom: 0.35rem;
    margin: 1.25rem 0 1rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.tw-persona-section-title:first-child { margin-top: 0; }
.tw-persona-section-title i { font-size: 0.85rem; color: #fc7b04; }

/* Already worker badge */
.tw-ya-trabajador {
    background: rgba(91, 138, 48, 0.10);
    border: 1px solid rgba(91, 138, 48, 0.25);
    border-radius: 10px;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.75rem;
}
.tw-ya-trabajador i { color: #5a8a30; font-size: 1.1rem; }
.tw-ya-trabajador span { font-size: 0.82rem; font-weight: 600; color: #5a8a30; }
html[data-bs-theme="dark"] .tw-ya-trabajador { background: rgba(109, 191, 64, 0.10); }
html[data-bs-theme="dark"] .tw-ya-trabajador i { color: #6dbf40; }
html[data-bs-theme="dark"] .tw-ya-trabajador span { color: #6dbf40; }

/* Cargos table in worker row */
.tw-cargos-list { max-height: 120px; overflow-y: auto; }
</style>
@endsection

@section('content')
<div class="dept-page-header">
    <div class="container-fluid">
        <div class="dph-inner">
            <div class="dph-left">
                <div class="dph-icon-wrap"><i class="ri-user-star-line"></i></div>
                <div>
                    <h1 class="dph-title">Trabajadores</h1>
                    <p class="dph-desc">Gestión y asignación de trabajadores y sus cargos</p>
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
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    {{-- ═══ SEARCH ═══ --}}
    <div class="tw-search-card">
        <div class="tw-search-row">
            <div class="tw-search-input">
                <label><i class="ri-id-card-line" style="color:#fc7b04;"></i> Buscar por Carnet</label>
                <input type="text" class="form-control" id="searchCarnet" placeholder="Ingrese el número de carnet…" maxlength="20" autocomplete="off">
            </div>
            <div class="tw-search-input" style="min-width:180px;">
                <label><i class="ri-briefcase-line" style="color:#fc7b04;"></i> Filtrar por Cargo</label>
                <select class="form-select" id="filterCargo">
                    <option value="">Todos los cargos</option>
                </select>
            </div>
            <button type="button" class="tw-btn-search" id="btnBuscar">
                <i class="ri-search-line"></i> Buscar
            </button>
            <button type="button" class="tw-btn-register" id="btnAbrirRegistro">
                <i class="ri-user-add-line"></i> Registrar Trabajador
            </button>
            <button type="button" class="btn btn-modal-cancel" id="btnLimpiarBusqueda" style="display:none;">
                <i class="ri-close-line"></i> Limpiar
            </button>
        </div>
    </div>

    {{-- ═══ PERSONA FOUND ═══ --}}
    <div class="tw-persona-found" id="personaFound">
        <div class="tw-pf-header">
            <h5><i class="ri-user-line"></i> Persona Encontrada</h5>
            <span class="tw-pf-badge" id="pfCarnet"></span>
        </div>
        <div class="tw-pf-body">
            <div class="tw-pf-grid">
                <div class="tw-pf-item"><label>Nombres</label><span id="pfNombres"></span></div>
                <div class="tw-pf-item"><label>Apellido Paterno</label><span id="pfApPaterno"></span></div>
                <div class="tw-pf-item"><label>Apellido Materno</label><span id="pfApMaterno"></span></div>
                <div class="tw-pf-item"><label>Sexo</label><span id="pfSexo"></span></div>
                <div class="tw-pf-item"><label>Estado Civil</label><span id="pfEstadoCivil"></span></div>
                <div class="tw-pf-item"><label>Correo</label><span id="pfCorreo"></span></div>
                <div class="tw-pf-item"><label>Celular</label><span id="pfCelular"></span></div>
                <div class="tw-pf-item"><label>Ciudad</label><span id="pfCiudad"></span></div>
            </div>
            <div id="yaTrabajadorBox" class="tw-ya-trabajador" style="display:none;">
                <i class="ri-checkbox-circle-fill"></i>
                <span>Esta persona ya está registrada como trabajador.</span>
            </div>
        </div>
        <div class="tw-pf-actions">
            <button type="button" class="btn btn-modal-submit" id="btnAsignarCargo" disabled>
                <i class="ri-add-circle-line"></i> Asignar Cargo(s)
            </button>
            <button type="button" class="btn btn-modal-cancel" id="btnLimpiarBusqueda2">
                <i class="ri-close-line"></i> Limpiar
            </button>
        </div>
    </div>

    {{-- ═══ CARGOS SELECTION ═══ --}}
    <div class="tw-cargos-section" id="cargosSection">
        <div class="tw-cs-header">
            <h5><i class="ri-briefcase-line"></i> Seleccionar Cargos</h5>
        </div>
        <div class="tw-cs-body">
            <p style="font-size:0.82rem;color:var(--d-muted);margin-bottom:1rem;">
                Seleccione los cargos que tendrá el trabajador y complete la información requerida.
            </p>
            <div id="cargosList"></div>
            <div id="cargosError" class="field-feedback error" style="display:none;"></div>
            <div class="mt-3 d-flex gap-2">
                <button type="button" class="btn btn-modal-submit" id="btnConfirmarAsignacion">
                    <i class="ri-check-line"></i> Confirmar Asignación
                </button>
                <button type="button" class="btn btn-modal-cancel" id="btnCancelarCargos">
                    <i class="ri-close-line"></i> Cancelar
                </button>
            </div>
        </div>
    </div>

    {{-- ═══ TABLE ═══ --}}
    <div class="row">
        <div class="col-12">
            <div class="dept-card">
                <div class="dept-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="dept-header-icon"><i class="ri-table-line"></i></div>
                        <div>
                            <h5 class="dept-title">Listado de Trabajadores</h5>
                            <p class="dept-subtitle">Consulta y gestiona los trabajadores registrados</p>
                        </div>
                    </div>
                </div>
                <div class="dept-card-body">
                    <table id="tabla-trabajadores" class="dept-table">
                        <thead>
                            <tr>
                                <th>Carnet</th>
                                <th>Nombres y Apellidos</th>
                                <th>Cargos Asignados</th>
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

{{-- ════════════════ MODAL REGISTRAR TRABAJADOR ════════════════ --}}
<div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistroTitle"><i class="ri-user-add-line"></i> Registrar Trabajador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formRegistro" novalidate autocomplete="off" enctype="multipart/form-data">
                <div class="modal-body" style="max-height:70vh;overflow-y:auto;">
                    <div id="registroYaTrabajador" class="tw-ya-trabajador" style="display:none;">
                        <i class="ri-checkbox-circle-fill"></i>
                        <span>Esta persona ya está registrada como trabajador.</span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4 text-center">
                            <label class="form-label d-block"><i class="ri-camera-line" style="color:#fc7b04;"></i> Fotografía</label>
                            <div class="photo-upload-container" style="cursor:pointer;" onclick="document.getElementById('fotografiaRegistro').click()">
                                <img id="previewFotografiaRegistro" src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" 
                                     style="width:100px;height:100px;object-fit:cover;border-radius:50%;border:3px solid #e2e8f0;">
                                <input type="file" id="fotografiaRegistro" name="fotografia" accept="image/*" style="display:none;" 
                                       onchange="previewImage(this, 'previewFotografiaRegistro')">
                            </div>
                            <small class="text-muted">Click para seleccionar</small>
                        </div>
                    </div>

                    <p class="tw-persona-section-title"><i class="ri-id-card-line"></i> Datos de Identidad</p>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label"><i class="ri-id-card-line" style="color:#fc7b04;"></i> Carnet <span class="req">*</span></label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="rCarnet" placeholder="Ej: 12345678" maxlength="20" autocomplete="off">
                                <span class="validation-icon" id="iconRCarnet"></span>
                            </div>
                            <div class="field-feedback" id="fbRCarnet"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-map-pin-line" style="color:#fc7b04;"></i> Expedido</label>
                            <input type="text" class="form-control" id="rExpedido" placeholder="Ej: LP" maxlength="10" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-calendar-line" style="color:#fc7b04;"></i> Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="rFechaNacimiento">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label"><i class="ri-user-line" style="color:#fc7b04;"></i> Nombres <span class="req">*</span></label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="rNombres" placeholder="Ej: Juan Carlos" maxlength="100" autocomplete="off">
                                <span class="validation-icon" id="iconRNombres"></span>
                            </div>
                            <div class="field-feedback" id="fbRNombres"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-user-3-line" style="color:#fc7b04;"></i> Sexo</label>
                            <select class="form-select" id="rSexo">
                                <option value="">— Seleccione —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido Paterno</label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="rApPaterno" placeholder="Ej: García" maxlength="80" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido Materno</label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="rApMaterno" placeholder="Ej: López" maxlength="80" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="field-feedback" id="fbRApellidos"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-heart-line" style="color:#fc7b04;"></i> Estado Civil</label>
                            <select class="form-select" id="rEstadoCivil">
                                <option value="">— Seleccione —</option>
                                <option value="Soltero/a">Soltero/a</option>
                                <option value="Casado/a">Casado/a</option>
                                <option value="Divorciado/a">Divorciado/a</option>
                                <option value="Viudo/a">Viudo/a</option>
                                <option value="Unión Libre">Unión Libre</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-map-2-line" style="color:#fc7b04;"></i> Departamento</label>
                            <select class="form-select" id="rDepto">
                                <option value="">— Seleccione —</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-building-line" style="color:#fc7b04;"></i> Ciudad</label>
                            <select class="form-select" id="rCiudad" disabled>
                                <option value="">— Seleccione depto. —</option>
                            </select>
                        </div>
                    </div>

                    <p class="tw-persona-section-title"><i class="ri-phone-line"></i> Datos de Contacto</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="ri-mail-line" style="color:#fc7b04;"></i> Correo Electrónico</label>
                            <div class="field-wrapper">
                                <input type="email" class="form-control" id="rCorreo" placeholder="Ej: correo@dominio.com" maxlength="150" autocomplete="off">
                                <span class="validation-icon" id="iconRCorreo"></span>
                            </div>
                            <div class="field-feedback" id="fbRCorreo"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-smartphone-line" style="color:#fc7b04;"></i> Celular</label>
                            <input type="text" class="form-control" id="rCelular" placeholder="Ej: 70000000" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-phone-line" style="color:#fc7b04;"></i> Teléfono</label>
                            <input type="text" class="form-control" id="rTelefono" placeholder="Ej: 2000000" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="ri-map-pin-2-line" style="color:#fc7b04;"></i> Dirección</label>
                            <input type="text" class="form-control" id="rDireccion" placeholder="Ej: Av. 6 de Agosto N° 123" maxlength="200" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="position:sticky;bottom:0;background:var(--d-card);border-top:1px solid var(--d-card-border);">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-modal-submit" id="btnGuardarTrabajador"><i class="ri-save-line"></i> Registrar Trabajador</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════ MODAL EDITAR ════════════════ --}}
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-pencil-line"></i> Editar Trabajador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditar" novalidate autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" id="idEditar">
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4 text-center">
                            <label class="form-label d-block"><i class="ri-camera-line" style="color:#fc7b04;"></i> Fotografía</label>
                            <div class="photo-upload-container" style="cursor:pointer;" onclick="document.getElementById('fotografiaEditar').click()">
                                <img id="previewFotografiaEditar" src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" 
                                     style="width:100px;height:100px;object-fit:cover;border-radius:50%;border:3px solid #e2e8f0;">
                                <input type="file" id="fotografiaEditar" name="fotografia" accept="image/*" style="display:none;" 
                                       onchange="previewImage(this, 'previewFotografiaEditar')">
                            </div>
                            <small class="text-muted">Click para seleccionar</small>
                        </div>
                    </div>

                    <p class="tw-persona-section-title"><i class="ri-id-card-line"></i> Datos de Identidad</p>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label"><i class="ri-id-card-line" style="color:#fc7b04;"></i> Carnet <span class="req">*</span></label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="eCarnet" placeholder="Ej: 12345678" maxlength="20" autocomplete="off">
                                <span class="validation-icon" id="iconECarnet"></span>
                            </div>
                            <div class="field-feedback" id="fbECarnet"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-map-pin-line" style="color:#fc7b04;"></i> Expedido</label>
                            <input type="text" class="form-control" id="eExpedido" placeholder="Ej: LP" maxlength="10" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-calendar-line" style="color:#fc7b04;"></i> Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="eFechaNacimiento">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label"><i class="ri-user-line" style="color:#fc7b04;"></i> Nombres <span class="req">*</span></label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="eNombres" placeholder="Ej: Juan Carlos" maxlength="100" autocomplete="off">
                                <span class="validation-icon" id="iconENombres"></span>
                            </div>
                            <div class="field-feedback" id="fbENombres"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-user-3-line" style="color:#fc7b04;"></i> Sexo</label>
                            <select class="form-select" id="eSexo">
                                <option value="">— Seleccione —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido Paterno</label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="eApPaterno" placeholder="Ej: García" maxlength="80" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido Materno</label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="eApMaterno" placeholder="Ej: López" maxlength="80" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="field-feedback" id="fbEApellidos"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-heart-line" style="color:#fc7b04;"></i> Estado Civil</label>
                            <select class="form-select" id="eEstadoCivil">
                                <option value="">— Seleccione —</option>
                                <option value="Soltero/a">Soltero/a</option>
                                <option value="Casado/a">Casado/a</option>
                                <option value="Divorciado/a">Divorciado/a</option>
                                <option value="Viudo/a">Viudo/a</option>
                                <option value="Unión Libre">Unión Libre</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-map-2-line" style="color:#fc7b04;"></i> Departamento</label>
                            <select class="form-select" id="eDepto">
                                <option value="">— Seleccione —</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-building-line" style="color:#fc7b04;"></i> Ciudad</label>
                            <select class="form-select" id="eCiudad" disabled>
                                <option value="">— Seleccione depto. —</option>
                            </select>
                        </div>
                    </div>

                    <p class="tw-persona-section-title"><i class="ri-phone-line"></i> Datos de Contacto</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="ri-mail-line" style="color:#fc7b04;"></i> Correo Electrónico</label>
                            <div class="field-wrapper">
                                <input type="email" class="form-control" id="eCorreo" placeholder="Ej: correo@dominio.com" maxlength="150" autocomplete="off">
                                <span class="validation-icon" id="iconECorreo"></span>
                            </div>
                            <div class="field-feedback" id="fbECorreo"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-smartphone-line" style="color:#fc7b04;"></i> Celular</label>
                            <input type="text" class="form-control" id="eCelular" placeholder="Ej: 70000000" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-phone-line" style="color:#fc7b04;"></i> Teléfono</label>
                            <input type="text" class="form-control" id="eTelefono" placeholder="Ej: 2000000" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="ri-map-pin-2-line" style="color:#fc7b04;"></i> Dirección</label>
                            <input type="text" class="form-control" id="eDireccion" placeholder="Ej: Av. 6 de Agosto N° 123" maxlength="200" autocomplete="off">
                        </div>
                    </div>

                    {{-- Cargos --}}
                    <p class="tw-persona-section-title"><i class="ri-briefcase-line"></i> Cargos Asignados</p>
                    <div id="editCargosActuales" class="mb-3"></div>

                    <p class="tw-persona-section-title"><i class="ri-add-circle-line"></i> Agregar Nuevos Cargos</p>
                    <div id="editCargosNuevos"></div>
                    <div id="editCargosError" class="field-feedback error" style="display:none;"></div>
                </div>
                <div class="modal-footer" style="position:sticky;bottom:0;background:var(--d-card);border-top:1px solid var(--d-card-border);">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-modal-submit" id="btnConfirmarEdicion"><i class="ri-check-line"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════ MODAL ELIMINAR CARGO ════════════════ --}}
<div class="modal fade" id="modalEliminarCargo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-error-warning-line"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="delete-warning-box">
                    <div class="delete-icon-ring"><i class="ri-briefcase-line"></i></div>
                    <p class="delete-msg-primary">¿Quitar este cargo?</p>
                    <p class="delete-msg-name"><strong id="nombreCargoEliminar"></strong></p>
                    <p class="delete-msg-warn"><i class="ri-information-line"></i> El cargo será removido del trabajador.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button type="button" class="btn btn-modal-cancel px-4" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-danger-modal px-4" id="btnConfirmarEliminarCargo"><i class="ri-delete-bin-line"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════ MODAL ELIMINAR ════════════════ --}}
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
                    <p class="delete-msg-primary">¿Eliminar trabajador?</p>
                    <p class="delete-msg-name"><strong id="nombreEliminar"></strong></p>
                    <p class="delete-msg-warn"><i class="ri-information-line"></i> Esta acción es permanente y no puede deshacerse.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button type="button" class="btn btn-modal-cancel px-4" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-danger-modal px-4" id="btnConfirmarEliminar"><i class="ri-delete-bin-line"></i> Eliminar</button>
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
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

(function () {
    'use strict';

    let tabla;
    let idEliminar = null;
    let personaSeleccionada = null;
    let editTrabajadorId = null;
    let editPersonaId = null;
    let editCargosEliminados = [];
    let originalData = {};
    let cargoEliminarId = null;
    let todosCargos = [];
    let todasCiudades = [];
    let todasSedes = [];
    let carnetTimer = null, correoTimer = null;
    const CSRF = '{{ csrf_token() }}';

    /* ── INIT ── */
    function init() {
        cargarSelectores();
        initDataTable();
        bindEvents();
    }

    /* ── SELECTORES ── */
    function cargarSelectores() {
        $.getJSON('{{ route("admin.trabajadores.listarCargos") }}', function (r) {
            todosCargos = r.data;
            const opts = r.data.map(c => '<option value="' + c.id + '">' + esc(c.nombre) + '</option>').join('');
            $('#filterCargo').append(opts);
        });
        $.getJSON('{{ route("admin.trabajadores.listarSedes") }}', function (r) {
            todasSedes = r.data;
        });
        $.getJSON('{{ route("admin.trabajadores.listarDepartamentos") }}', function (r) {
            const opts = r.data.map(d => '<option value="' + d.id + '">' + esc(d.nombre) + '</option>').join('');
            $('#pDepto').append(opts);
            $('#rDepto').append(opts);
            $('#eDepto').append(opts);
        });
        $.getJSON('{{ route("admin.trabajadores.listarCiudades") }}', function (r) {
            todasCiudades = r.data;
        });
    }

    /* ── CASCADA DEPARTAMENTO → CIUDAD ── */
    function filtrarCiudades(deptoId, $ciudad) {
        $ciudad.find('option:not(:first)').remove();
        if (!deptoId) {
            $ciudad.prop('disabled', true).find('option:first').text('— Seleccione depto. —');
            return;
        }
        const filtradas = todasCiudades.filter(function (c) { return c.departamento_id == deptoId; });
        $ciudad.append(filtradas.map(c => '<option value="' + c.id + '">' + esc(c.nombre) + '</option>').join(''));
        $ciudad.prop('disabled', false).find('option:first').text('— Seleccione ciudad —');
    }

    $('#pDepto').on('change', function () {
        filtrarCiudades($(this).val(), $('#pCiudad'));
    });

    $('#rDepto').on('change', function () {
        filtrarCiudades($(this).val(), $('#rCiudad'));
    });

    /* ── DATATABLE ── */
    function initDataTable() {
        tabla = $('#tabla-trabajadores').DataTable({
            ajax: { 
                url: '{{ route("admin.trabajadores.listar") }}', 
                dataSrc: 'data',
                data: function(d) {
                    d.cargo_id = $('#filterCargo').val();
                }
            },
            ordering: true,
            columns: [
                {
                    data: null,
                    render: d => {
                        const p = d.persona;
                        if (!p) return '<span style="color:var(--d-muted)">—</span>';
                        let txt = '<span style="font-weight:700;">' + esc(p.carnet) + '</span>';
                        if (p.expedido) txt += '<br><small style="color:var(--d-muted);font-size:0.72rem;">exp. ' + esc(p.expedido) + '</small>';
                        return txt;
                    }
                },
                {
                    data: null,
                    render: d => {
                        const p = d.persona;
                        if (!p) return '<span style="color:var(--d-muted)">—</span>';
                        const n = esc(p.nombres);
                        const ap = [p.apellido_paterno, p.apellido_materno].filter(Boolean).map(esc).join(' ');
                        return '<span style="font-weight:600;">' + n + '</span>' + (ap ? '<br><small style="color:var(--d-muted);">' + ap + '</small>' : '');
                    }
                },
                {
                    data: null,
                    render: d => {
                        const cargos = d.trabajadores_cargos || [];
                        if (cargos.length === 0) return '<span style="color:var(--d-muted)">—</span>';
                        let h = '<div class="tw-cargos-list">';
                        cargos.forEach(function (tc) {
                            const estadoClass = tc.estado === 'Vigente' ? 'badge-vigente' : 'badge-no-vigente';
                            let label = esc(tc.cargo ? tc.cargo.nombre : '');
                            if (tc.sucursale) {
                                const sede = tc.sucursale.sede;
                                label += ' <small style="opacity:0.7;">(' + (sede ? esc(sede.nombre) : '') + ' / ' + esc(tc.sucursale.nombre) + ')</small>';
                            }
                            h += '<span class="badge-cargo-asignado ' + estadoClass + '">' + label + '</span>';
                        });
                        h += '</div>';
                        return h;
                    }
                },
                {
                    data: null, className: 'text-center',
                    render: d => '<div class="action-cell">'
                        + '<button class="btn btn-action btn-action-edit btn-accion-editar" data-id="' + d.id + '" title="Editar cargos"><i class="ri-pencil-fill"></i></button>'
                        + '<button class="btn btn-action btn-action-delete btn-accion-eliminar" data-id="' + d.id + '" data-nombre="' + esc((d.persona ? d.persona.nombres + ' ' + (d.persona.apellido_paterno || '') : 'Trabajador')) + '" title="Eliminar trabajador"><i class="ri-delete-bin-fill"></i></button>'
                        + '</div>'
                }
            ],
            language: {
                processing: 'Procesando...', search: 'Buscar:', zeroRecords: 'No se encontraron registros',
                emptyTable: 'No hay datos disponibles',
                paginate: { first: 'Primero', previous: 'Anterior', next: 'Siguiente', last: 'Último' }
            },
            order: [[0, 'asc']],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
            pageLength: 10,
            drawCallback: function () {
                $('#stat-total').text(this.api().page.info().recordsTotal);
            }
        });
    }

    /* ── EVENTOS ── */
    function bindEvents() {
        /* Filtro cargo */
        $('#filterCargo').on('change', function() {
            tabla.ajax.reload(null, false);
        });

        /* Buscar */
        $('#btnBuscar').on('click', buscarPersona);
        $('#searchCarnet').on('keypress', function (e) { if (e.which === 13) buscarPersona(); });

        /* Registro */
        $('#btnAbrirRegistro').on('click', abrirModalRegistro);
        $('#btnLimpiarBusqueda, #btnLimpiarBusqueda2').on('click', function () { limpiarBusqueda(); resetFormRegistro(); });

        /* Acciones persona encontrada */
        $('#btnAsignarCargo').on('click', mostrarCargos);

        /* Cargos */
        $(document).on('change', '.tw-cargo-checkbox', function () {
            const $check = $(this);
            const $fields = $check.closest('.tw-cargo-item').find('.tw-cargo-fields');
            if ($check.is(':checked')) {
                $check.closest('.tw-cargo-check').addClass('checked');
                $fields.addClass('show');
            } else {
                $check.closest('.tw-cargo-check').removeClass('checked');
                $fields.removeClass('show');
            }
        });

        $('#btnConfirmarAsignacion').on('click', confirmarAsignacion);
        $('#btnCancelarCargos').on('click', function () {
            $('#cargosSection').slideUp(200);
        });

        /* Formulario registro */
        $('#formRegistro').on('submit', function (e) { e.preventDefault(); guardarTrabajador(); });
        $('#rDepto').on('change', filtrarCiudades);

        /* Validación carnet registro */
        $('#rCarnet').on('input', function () {
            clearTimeout(carnetTimer);
            const val = this.value.trim();
            if (!val) { return setError('rCarnet','iconRCarnet','fbRCarnet','El carnet es obligatorio.'); }
            if (val.length < 3) { return setError('rCarnet','iconRCarnet','fbRCarnet','Debe tener al menos 3 caracteres.'); }
            setChecking('rCarnet','iconRCarnet','fbRCarnet');
            carnetTimer = setTimeout(function () { verificarCarnetRegistro(val); }, 400);
        });

        $('#rNombres').on('input', function () { validarNombres('rNombres','iconRNombres','fbRNombres'); });
        $('#rApPaterno, #rApMaterno').on('input', validarApellidosRegistro);

        $('#rCorreo').on('input', function () {
            clearTimeout(correoTimer);
            const val = this.value.trim();
            if (!val) { return resetField('rCorreo','iconRCorreo','fbRCorreo'); }
            if (!isEmail(val)) { return setError('rCorreo','iconRCorreo','fbRCorreo','Formato de correo inválido.'); }
            setChecking('rCorreo','iconRCorreo','fbRCorreo');
            correoTimer = setTimeout(function () { verificarCorreoRegistro(val); }, 400);
        });

        /* Eliminar */
        $(document).on('click', '.btn-accion-eliminar', function () {
            idEliminar = $(this).data('id');
            $('#nombreEliminar').text($(this).data('nombre'));
            openModal('modalEliminar');
        });
        $('#btnConfirmarEliminar').on('click', function () {
            if (idEliminar) eliminarTrabajador(idEliminar);
        });

        /* Editar */
        $(document).on('click', '.btn-accion-editar', function () {
            editarTrabajador($(this).data('id'));
        });
        $('#formEditar').on('submit', function (e) { e.preventDefault(); confirmarEdicion(); });

        /* Validación editar */
        $('#eCarnet').on('input', function () {
            clearTimeout(carnetTimer);
            const val = this.value.trim();
            if (!val) { return setError('eCarnet','iconECarnet','fbECarnet','El carnet es obligatorio.'); }
            if (val.length < 3) { return setError('eCarnet','iconECarnet','fbECarnet','Debe tener al menos 3 caracteres.'); }
            setChecking('eCarnet','iconECarnet','fbECarnet');
            carnetTimer = setTimeout(function () { verificarCarnetEditar(val); }, 400);
        });
        $('#eNombres').on('input', function () { validarNombres('eNombres','iconENombres','fbENombres'); });
        $('#eApPaterno, #eApMaterno').on('input', validarApellidosEditar);
        $('#eCorreo').on('input', function () {
            clearTimeout(correoTimer);
            const val = this.value.trim();
            if (!val) { return resetField('eCorreo','iconECorreo','fbECorreo'); }
            if (!isEmail(val)) { return setError('eCorreo','iconECorreo','fbECorreo','Formato de correo inválido.'); }
            setChecking('eCorreo','iconECorreo','fbECorreo');
            correoTimer = setTimeout(function () { verificarCorreoEditar(val); }, 400);
        });
        $('#eDepto').on('change', function () { filtrarCiudades('Editar'); });

        /* Cargos edit - sede change */
        $(document).on('change', '.edit-cargo-sede', function () {
            const cargoId = $(this).data('cargo');
            const sedeId = $(this).val();
            const $sucursal = $('.edit-cargo-sucursal[data-cargo="' + cargoId + '"]');
            $sucursal.find('option:not(:first)').remove();
            if (!sedeId) {
                $sucursal.prop('disabled', true).find('option:first').text('— Seleccione sede —');
                return;
            }
            $sucursal.find('option:first').text('Cargando…');
            $.post('{{ route("admin.trabajadores.listarSucursalesPorSede") }}', { _token: CSRF, sede_id: sedeId })
                .done(function (r) {
                    $sucursal.find('option:first').text('— Seleccione sucursal —');
                    if (r.data.length === 0) {
                        $sucursal.prop('disabled', true).find('option:first').text('Sin sucursales');
                        return;
                    }
                    $sucursal.append(r.data.map(function (s) {
                        return '<option value="' + s.id + '">' + esc(s.nombre) + (s.direccion ? ' — ' + esc(s.direccion) : '') + '</option>';
                    }).join(''));
                    $sucursal.prop('disabled', false);
                })
                .fail(function () {
                    $sucursal.prop('disabled', true).find('option:first').text('Error al cargar');
                });
        });

        $(document).on('change', '.edit-cargo-checkbox', function () {
            const $check = $(this);
            const $fields = $check.closest('.edit-cargo-item').find('.edit-cargo-fields');
            if ($check.is(':checked')) {
                $check.closest('.edit-cargo-check').addClass('checked');
                $fields.addClass('show');
            } else {
                $check.closest('.edit-cargo-check').removeClass('checked');
                $fields.removeClass('show');
            }
        });

        document.getElementById('modalRegistro').addEventListener('hidden.bs.modal', resetFormRegistro);
        document.getElementById('modalEditar').addEventListener('hidden.bs.modal', function () {
            editTrabajadorId = null;
            editPersonaId = null;
            editCargosEliminados = [];
            originalData = {};
        });
    }

    /* ── BUSCAR PERSONA ── */
    function buscarPersona() {
        const carnet = $('#searchCarnet').val().trim();
        if (!carnet) {
            toast('warning', 'Ingrese un número de carnet para buscar.');
            return;
        }

        setBtnLoading('#btnBuscar', true, 'Buscando…');
        $.post('{{ route("admin.trabajadores.buscarCarnet") }}', { _token: CSRF, carnet: carnet })
            .done(function (r) {
                if (r.encontrado) {
                    personaSeleccionada = r.persona;
                    mostrarPersonaEncontrada(r.persona, r.ya_trabajador);
                } else {
                    $('#personaFound').slideUp(200);
                    $('#cargosSection').slideUp(200);
                    toast('warning', 'No se encontró ninguna persona con el carnet: ' + carnet);
                    $('#btnNuevaPersona').prop('disabled', false);
                }
            })
            .fail(function () { toast('error', 'Error al buscar. Intente nuevamente.'); })
            .always(function () { setBtnLoading('#btnBuscar', false, '<i class="ri-search-line"></i> Buscar'); });
    }

    /* ── MOSTRAR PERSONA ENCONTRADA ── */
    function mostrarPersonaEncontrada(p, yaTrabajador) {
        $('#pfCarnet').text(p.carnet);
        $('#pfNombres').text(p.nombres || '—');
        $('#pfApPaterno').text(p.apellido_paterno || '—');
        $('#pfApMaterno').text(p.apellido_materno || '—');
        $('#pfSexo').text(p.sexo ? (p.sexo === 'M' ? 'Masculino' : 'Femenino') : '—');
        $('#pfEstadoCivil').text(p.estado_civil || '—');
        $('#pfCorreo').text(p.correo || '—');
        $('#pfCelular').text(p.celular || '—');
        $('#pfCiudad').text(p.ciudad ? p.ciudad.nombre : '—');

        if (yaTrabajador) {
            $('#yaTrabajadorBox').show();
            $('#btnAsignarCargo').prop('disabled', true).html('<i class="ri-error-warning-line"></i> Ya es trabajador');
        } else {
            $('#yaTrabajadorBox').hide();
            $('#btnAsignarCargo').prop('disabled', false).html('<i class="ri-add-circle-line"></i> Asignar Cargo(s)');
        }

        $('#personaFound').slideDown(300);
        $('#cargosSection').slideUp(200);
        $('#btnLimpiarBusqueda').show();
        $('#btnLimpiarBusqueda2').show();
    }

    /* ── MOSTRAR CARGOS ── */
    function mostrarCargos() {
        if (!personaSeleccionada) return;

        const sedeOptions = '<option value="">— Seleccione sede —</option>' +
            todasSedes.map(function (s) { return '<option value="' + s.id + '">' + esc(s.nombre) + '</option>'; }).join('');

        let html = '';
        todosCargos.forEach(function (c) {
            const today = new Date().toISOString().split('T')[0];
            html += '<div class="tw-cargo-item">'
                + '<label class="tw-cargo-check">'
                + '<input type="checkbox" class="tw-cargo-checkbox" value="' + c.id + '">'
                + '<span class="tw-cargo-name">' + esc(c.nombre) + '</span>'
                + '</label>'
                + '<div class="tw-cargo-fields">'
                + '<div class="row g-2">'
                + '<div class="col-md-6">'
                + '<div class="tw-sucursal-label"><i class="ri-building-line" style="color:#fc7b04;"></i> Sede</div>'
                + '<select class="form-select tw-cargo-sede" data-cargo="' + c.id + '">' + sedeOptions + '</select>'
                + '</div>'
                + '<div class="col-md-6">'
                + '<div class="tw-sucursal-label"><i class="ri-building-2-line" style="color:#fc7b04;"></i> Sucursal</div>'
                + '<select class="form-select tw-cargo-sucursal" data-cargo="' + c.id + '" disabled>'
                + '<option value="">— Seleccione sede —</option>'
                + '</select>'
                + '</div>'
                + '<div class="col-md-4">'
                + '<label>Estado</label>'
                + '<select class="form-select tw-cargo-estado" data-cargo="' + c.id + '">'
                + '<option value="Vigente">Vigente</option>'
                + '<option value="No Vigente">No Vigente</option>'
                + '</select>'
                + '</div>'
                + '<div class="col-md-4">'
                + '<label>Fecha de Ingreso</label>'
                + '<input type="date" class="form-control tw-cargo-fecha-ingreso" data-cargo="' + c.id + '" value="' + today + '">'
                + '</div>'
                + '<div class="col-md-4">'
                + '<label>Fecha de Término</label>'
                + '<input type="date" class="form-control tw-cargo-fecha-termino" data-cargo="' + c.id + '">'
                + '</div>'
                + '</div></div></div>';
        });

        $('#cargosList').html(html);
        $('#cargosError').hide();

        /* Bind sede change → load sucursales */
        $(document).off('change', '.tw-cargo-sede').on('change', '.tw-cargo-sede', function () {
            const cargoId = $(this).data('cargo');
            const sedeId = $(this).val();
            const $sucursal = $('.tw-cargo-sucursal[data-cargo="' + cargoId + '"]');
            $sucursal.find('option:not(:first)').remove();
            if (!sedeId) {
                $sucursal.prop('disabled', true).find('option:first').text('— Seleccione sede —');
                return;
            }
            $sucursal.find('option:first').text('Cargando…');
            $.post('{{ route("admin.trabajadores.listarSucursalesPorSede") }}', { _token: CSRF, sede_id: sedeId })
                .done(function (r) {
                    $sucursal.find('option:first').text('— Seleccione sucursal —');
                    if (r.data.length === 0) {
                        $sucursal.prop('disabled', true).find('option:first').text('Sin sucursales');
                        return;
                    }
                    $sucursal.append(r.data.map(function (s) {
                        return '<option value="' + s.id + '">' + esc(s.nombre) + (s.direccion ? ' — ' + esc(s.direccion) : '') + '</option>';
                    }).join(''));
                    $sucursal.prop('disabled', false);
                })
                .fail(function () {
                    $sucursal.prop('disabled', true).find('option:first').text('Error al cargar');
                });
        });

        $('#cargosSection').slideDown(300);
    }

    /* ── CONFIRMAR ASIGNACIÓN ── */
    function confirmarAsignacion() {
        const cargosSeleccionados = [];
        let hasError = false;

        $('.tw-cargo-checkbox:checked').each(function () {
            if (hasError) return;
            const cargoId = $(this).val();
            const sucursalId = $('.tw-cargo-sucursal[data-cargo="' + cargoId + '"]').val();
            const estado = $('.tw-cargo-estado[data-cargo="' + cargoId + '"]').val();
            const fechaIngreso = $('.tw-cargo-fecha-ingreso[data-cargo="' + cargoId + '"]').val();
            const fechaTermino = $('.tw-cargo-fecha-termino[data-cargo="' + cargoId + '"]').val() || null;

            if (!sucursalId) {
                $('#cargosError').text('Debe seleccionar una sucursal para cada cargo asignado.').show();
                hasError = true;
                return;
            }
            if (!fechaIngreso) {
                $('#cargosError').text('La fecha de ingreso es obligatoria para todos los cargos seleccionados.').show();
                hasError = true;
                return;
            }

            cargosSeleccionados.push({
                cargo_id: cargoId,
                sucursale_id: sucursalId,
                estado: estado,
                fecha_ingreso: fechaIngreso,
                fecha_termino: fechaTermino
            });
        });

        if (hasError) return;

        if (cargosSeleccionados.length === 0) {
            $('#cargosError').text('Debe seleccionar al menos un cargo.').show();
            return;
        }

        setBtnLoading('#btnConfirmarAsignacion', true, 'Asignando…');
        $.post('{{ route("admin.trabajadores.asignar") }}', {
            _token: CSRF,
            persona_id: personaSeleccionada.id,
            cargos: cargosSeleccionados
        })
        .done(function (r) {
            toast('success', r.message);
            $('#cargosSection').slideUp(200);
            tabla.ajax.reload(null, false);
        })
        .fail(function (xhr) {
            const msg = xhr.responseJSON?.errors
                ? Object.values(xhr.responseJSON.errors).flat().join(' ')
                : xhr.responseJSON?.message || 'Error al asignar.';
            toast('error', msg);
        })
        .always(function () {
            setBtnLoading('#btnConfirmarAsignacion', false, '<i class="ri-check-line"></i> Confirmar Asignación');
        });
    }

    /* ── ABRIR MODAL REGISTRO ── */
    function abrirModalRegistro() {
        resetFormRegistro();

        if (personaSeleccionada) {
            const p = personaSeleccionada;
            $('#rCarnet').val(p.carnet).prop('readonly', true);
            $('#rNombres').val(p.nombres || '').prop('readonly', true);
            $('#rApPaterno').val(p.apellido_paterno || '').prop('readonly', true);
            $('#rApMaterno').val(p.apellido_materno || '').prop('readonly', true);
            $('#rCorreo').val(p.correo || '').prop('readonly', true);
            $('#rCelular').val(p.celular || '').prop('readonly', true);
            $('#rTelefono').val(p.telefono || '').prop('readonly', true);
            $('#rDireccion').val(p.direccion || '').prop('readonly', true);
            $('#rExpedido').val(p.expedido || '').prop('readonly', true);
            $('#rFechaNacimiento').val(p.fecha_nacimiento || '').prop('readonly', true);
            $('#rSexo').val(p.sexo || '').prop('disabled', true);
            $('#rEstadoCivil').val(p.estado_civil || '').prop('disabled', true);

            if (p.ciudad) {
                $('#rDepto').val(p.ciudad.departamento_id).trigger('change');
                setTimeout(function () {
                    $('#rCiudad').val(p.ciudad.id).prop('disabled', true);
                }, 200);
            }

            ['rCarnet','rNombres'].forEach(function (id) {
                const input = document.getElementById(id);
                if (input) { input.classList.remove('is-invalid'); input.classList.add('is-valid'); }
            });
            if (p.correo) {
                const input = document.getElementById('rCorreo');
                if (input) { input.classList.remove('is-invalid'); input.classList.add('is-valid'); }
            }

            $.post('{{ route("admin.trabajadores.buscarCarnet") }}', { _token: CSRF, carnet: p.carnet })
                .done(function (r) {
                    if (r.ya_trabajador) {
                        $('#registroYaTrabajador').show();
                        $('#btnGuardarTrabajador').prop('disabled', true).html('<i class="ri-error-warning-line"></i> Ya es trabajador');
                    } else {
                        $('#registroYaTrabajador').hide();
                        $('#btnGuardarTrabajador').prop('disabled', false).html('<i class="ri-save-line"></i> Registrar Trabajador');
                    }
                });

            $('#modalRegistroTitle').html('<i class="ri-user-check-line"></i> Registrar como Trabajador — ' + esc(p.carnet));
        } else {
            $('#modalRegistroTitle').html('<i class="ri-user-add-line"></i> Registrar Trabajador');
        }

        openModal('modalRegistro');
    }

    /* ── GUARDAR TRABAJADOR ── */
    function guardarTrabajador() {
        const okC = validarCarnetSync('rCarnet','iconRCarnet','fbRCarnet');
        const okN = validarNombres('rNombres','iconRNombres','fbRNombres');
        const okAp = validarApellidosRegistro();
        if (!okC || !okN || !okAp) return;
        if (document.getElementById('rCarnet').classList.contains('is-invalid')) return;
        if (document.getElementById('rCorreo').classList.contains('is-invalid')) return;

        setBtnLoading('#btnGuardarTrabajador', true, 'Guardando…');
        
        var formData = new FormData();
        formData.append('_token', CSRF);
        formData.append('carnet', $('#rCarnet').val().trim());
        formData.append('expedido', $('#rExpedido').val().trim());
        formData.append('nombres', $('#rNombres').val().trim());
        formData.append('apellido_paterno', $('#rApPaterno').val().trim());
        formData.append('apellido_materno', $('#rApMaterno').val().trim());
        formData.append('sexo', $('#rSexo').val());
        formData.append('estado_civil', $('#rEstadoCivil').val());
        formData.append('fecha_nacimiento', $('#rFechaNacimiento').val() || '');
        formData.append('correo', $('#rCorreo').val().trim());
        formData.append('direccion', $('#rDireccion').val().trim());
        formData.append('celular', $('#rCelular').val().trim());
        formData.append('telefono', $('#rTelefono').val().trim());
        formData.append('ciudade_id', $('#rCiudad').val() || '');

        var fotoInput = document.getElementById('fotografiaRegistro');
        if (fotoInput && fotoInput.files && fotoInput.files[0]) {
            formData.append('fotografia', fotoInput.files[0]);
        }

        $.ajax({
            url: '{{ route("admin.trabajadores.guardarPersona") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        })
        .done(function (r) {
            closeModal('modalRegistro');
            toast('success', r.message);
            $('#searchCarnet').val(r.data.carnet);
            buscarPersona();
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                const errs = xhr.responseJSON.errors || {};
                if (errs.carnet) setError('rCarnet','iconRCarnet','fbRCarnet', errs.carnet[0]);
                if (errs.nombres) setError('rNombres','iconRNombres','fbRNombres', errs.nombres[0]);
                if (errs.correo) setError('rCorreo','iconRCorreo','fbRCorreo', errs.correo[0]);
                if (errs.apellidos) {
                    $('#fbRApellidos').addClass('error').html('<i class="ri-error-warning-line"></i>' + errs.apellidos[0]);
                }
            } else {
                toast('error', 'Error al registrar. Intente nuevamente.');
            }
        })
        .always(function () {
            setBtnLoading('#btnGuardarTrabajador', false, '<i class="ri-save-line"></i> Registrar Trabajador');
        });
    }

    /* ── GUARDAR PERSONA ── */
    function guardarPersona() {
        const okC = validarCarnetSync('pCarnet','iconPCarnet','fbPCarnet');
        const okN = validarNombres('pNombres','iconPNombres','fbPNombres');
        const okAp = validarApellidos();
        if (!okC || !okN || !okAp) return;
        if (document.getElementById('pCarnet').classList.contains('is-invalid')) return;
        if (document.getElementById('pCorreo').classList.contains('is-invalid')) return;

        setBtnLoading('#btnGuardarPersona', true, 'Guardando…');
        $.post('{{ route("admin.trabajadores.guardarPersona") }}', {
            _token: CSRF,
            carnet: $('#pCarnet').val().trim(),
            expedido: $('#pExpedido').val().trim(),
            nombres: $('#pNombres').val().trim(),
            apellido_paterno: $('#pApPaterno').val().trim(),
            apellido_materno: $('#pApMaterno').val().trim(),
            sexo: $('#pSexo').val(),
            estado_civil: $('#pEstadoCivil').val(),
            fecha_nacimiento: $('#pFechaNacimiento').val() || null,
            correo: $('#pCorreo').val().trim(),
            direccion: $('#pDireccion').val().trim(),
            celular: $('#pCelular').val().trim(),
            telefono: $('#pTelefono').val().trim(),
            ciudade_id: $('#pCiudad').val() || null,
        })
        .done(function (r) {
            closeModal('modalPersona');
            toast('success', r.message);
            // Auto-buscar la persona recién creada
            $('#searchCarnet').val(r.data.carnet);
            buscarPersona();
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                const errs = xhr.responseJSON.errors || {};
                if (errs.carnet) setError('pCarnet','iconPCarnet','fbPCarnet', errs.carnet[0]);
                if (errs.nombres) setError('pNombres','iconPNombres','fbPNombres', errs.nombres[0]);
                if (errs.correo) setError('pCorreo','iconPCorreo','fbPCorreo', errs.correo[0]);
                if (errs.apellidos) {
                    $('#fbPApellidos').addClass('error').html('<i class="ri-error-warning-line"></i>' + errs.apellidos[0]);
                }
            } else {
                toast('error', 'Error al registrar. Intente nuevamente.');
            }
        })
        .always(function () {
            setBtnLoading('#btnGuardarPersona', false, '<i class="ri-save-line"></i> Registrar Persona');
        });
    }

/* ── EDITAR TRABAJADOR ── */
    function editarTrabajador(id) {
        editTrabajadorId = id;
        editPersonaId = null;
        editCargosEliminados = [];

        setBtnLoading('#btnConfirmarEdicion', true, 'Cargando…');
        $.get('{{ route("admin.trabajadores.obtener", ["id" => "__ID__"]) }}'.replace('__ID__', id))
            .done(function (r) {
                const t = r.data;
                const p = t.persona;
                if (!p) {
                    toast('error', 'No se encontró la persona.');
                    setBtnLoading('#btnConfirmarEdicion', false, '<i class="ri-check-line"></i> Guardar Cambios');
                    return;
                }
                editPersonaId = p.id;

                /* Datos personales */
                $('#eCarnet').val(p.carnet || '');
                $('#eExpedido').val(p.expedido || '');
                $('#eNombres').val(p.nombres || '');
                $('#eApPaterno').val(p.apellido_paterno || '');
                $('#eApMaterno').val(p.apellido_materno || '');
                $('#eSexo').val(p.sexo || '');
                $('#eEstadoCivil').val(p.estado_civil || '');
                $('#eFechaNacimiento').val(p.fecha_nacimiento || '');
                $('#eCorreo').val(p.correo || '');
                $('#eCelular').val(p.celular || '');
                $('#eTelefono').val(p.telefono || '');
                $('#eDireccion').val(p.direccion || '');

                /* Guardar datos originales para comparar cambios */
                originalData = {
                    eCarnet: p.carnet || '',
                    eExpedido: p.expedido || '',
                    eNombres: p.nombres || '',
                    eApPaterno: p.apellido_paterno || '',
                    eApMaterno: p.apellido_materno || '',
                    eSexo: p.sexo || '',
                    eEstadoCivil: p.estado_civil || '',
                    eFechaNacimiento: p.fecha_nacimiento || '',
                    eCorreo: p.correo || '',
                    eCelular: p.celular || '',
                    eTelefono: p.telefono || '',
                    eDireccion: p.direccion || '',
                    eCiudad: p.ciudad ? p.ciudad.id : ''
                };

                /* Fotografía */
                if (p.fotografia) {
                    var fotoUrl = '{{ url("images/personas") }}/' + p.fotografia;
                    $('#previewFotografiaEditar').attr('src', fotoUrl);
                } else {
                    $('#previewFotografiaEditar').attr('src', '{{ URL::asset("build/images/users/avatar-1.jpg") }}');
                }

                /* Departamento y Ciudad */
                if (p.ciudad) {
                    $('#eDepto').val(p.ciudad.departamento_id);
                    $.when(filtrarCiudadesAsync('Editar')).then(function () {
                        $('#eCiudad').val(p.ciudad.id);
                    });
                } else {
                    $('#eDepto').val('');
                    $('#eCiudad').val('').prop('disabled', true);
                }

                /* Cargos actuales */
                let htmlActuales = '';
                const cargosActuales = t.trabajadores_cargos || [];
                if (cargosActuales.length === 0) {
                    htmlActuales = '<p style="color:var(--d-muted);font-size:0.85rem;">Sin cargos asignados.</p>';
                } else {
                    htmlActuales = '<div class="table-responsive"><table class="table table-sm align-middle mb-0" style="font-size:0.82rem;">';
                    htmlActuales += '<thead><tr><th>Cargo</th><th>Sede / Sucursal</th><th>Estado</th><th>Fecha Ingreso</th><th>Fecha Término</th><th style="width:80px;"></th></tr></thead><tbody>';
                    cargosActuales.forEach(function (tc) {
                        const sede = tc.sucursale ? (tc.sucursale.sede ? tc.sucursale.sede.nombre + ' / ' : '') + tc.sucursale.nombre : '—';
                        const estadoBadge = tc.estado === 'Vigente'
                            ? '<span class="badge bg-success-subtle text-success">Vigente</span>'
                            : '<span class="badge bg-warning-subtle text-warning">No Vigente</span>';
                        htmlActuales += '<tr>'
                            + '<td>' + esc(tc.cargo ? tc.cargo.nombre : '') + '</td>'
                            + '<td>' + esc(sede) + '</td>'
                            + '<td>' + estadoBadge + '</td>'
                            + '<td>' + (tc.fecha_ingreso || '—') + '</td>'
                            + '<td>' + (tc.fecha_termino || '—') + '</td>'
                            + '<td><button class="btn btn-sm btn-outline-danger btn-quitar-cargo" data-id="' + tc.id + '" data-cargo-id="' + tc.cargo_id + '" data-nombre="' + esc(tc.cargo ? tc.cargo.nombre : '') + '" title="Quitar cargo"><i class="ri-delete-bin-line"></i></button></td>'
                            + '</tr>';
                    });
                    htmlActuales += '</tbody></table></div>';
                }
                $('#editCargosActuales').html(htmlActuales);

                /* Cargos nuevos — excluir los que ya tiene */
                const cargoIdsActuales = cargosActuales.map(function (tc) { return tc.cargo_id; });
                const cargosDisponibles = todosCargos.filter(function (c) { return cargoIdsActuales.indexOf(c.id) === -1; });

                const sedeOptions = '<option value="">— Seleccione sede —</option>' +
                    todasSedes.map(function (s) { return '<option value="' + s.id + '">' + esc(s.nombre) + '</option>'; }).join('');
                let htmlNuevos = '';
                if (cargosDisponibles.length === 0) {
                    htmlNuevos = '<p style="color:var(--d-muted);font-size:0.85rem;">No hay cargos disponibles para agregar.</p>';
                } else {
                    cargosDisponibles.forEach(function (c) {
                        const today = new Date().toISOString().split('T')[0];
                        htmlNuevos += '<div class="edit-cargo-item">'
                            + '<label class="tw-cargo-check edit-cargo-check">'
                            + '<input type="checkbox" class="edit-cargo-checkbox" value="' + c.id + '">'
                            + '<span class="tw-cargo-name">' + esc(c.nombre) + '</span>'
                            + '</label>'
                            + '<div class="tw-cargo-fields edit-cargo-fields">'
                            + '<div class="row g-2">'
                            + '<div class="col-md-6">'
                            + '<div class="tw-sucursal-label"><i class="ri-building-line" style="color:#fc7b04;"></i> Sede</div>'
                            + '<select class="form-select edit-cargo-sede" data-cargo="' + c.id + '">' + sedeOptions + '</select>'
                            + '</div>'
                            + '<div class="col-md-6">'
                            + '<div class="tw-sucursal-label"><i class="ri-building-2-line" style="color:#fc7b04;"></i> Sucursal</div>'
                            + '<select class="form-select edit-cargo-sucursal" data-cargo="' + c.id + '" disabled>'
                            + '<option value="">— Seleccione sede —</option>'
                            + '</select>'
                            + '</div>'
                            + '<div class="col-md-4">'
                            + '<label>Estado</label>'
                            + '<select class="form-select edit-cargo-estado" data-cargo="' + c.id + '">'
                            + '<option value="Vigente">Vigente</option>'
                            + '<option value="No Vigente">No Vigente</option>'
                            + '</select>'
                            + '</div>'
                            + '<div class="col-md-4">'
                            + '<label>Fecha de Ingreso</label>'
                            + '<input type="date" class="form-control edit-cargo-fecha-ingreso" data-cargo="' + c.id + '" value="' + today + '">'
                            + '</div>'
                            + '<div class="col-md-4">'
                            + '<label>Fecha de Término</label>'
                            + '<input type="date" class="form-control edit-cargo-fecha-termino" data-cargo="' + c.id + '">'
                            + '</div>'
                            + '</div></div></div>';
                    });
                }
                $('#editCargosNuevos').html(htmlNuevos);
                $('#editCargosError').hide();

                openModal('modalEditar');
            })
            .fail(function () {
                toast('error', 'Error al cargar los datos del trabajador.');
            })
            .always(function () {
                setBtnLoading('#btnConfirmarEdicion', false, '<i class="ri-check-line"></i> Guardar Cambios');
            });
    }

    /* ── Quitar cargo actual ── */
    $(document).on('click', '.btn-quitar-cargo', function () {
        cargoEliminarId = $(this).data('id');
        $('#nombreCargoEliminar').text($(this).data('nombre'));
        openModal('modalEliminarCargo');
    });
    $('#btnConfirmarEliminarCargo').on('click', function () {
        if (!cargoEliminarId) return;
        editCargosEliminados.push(cargoEliminarId);
        closeModal('modalEliminarCargo');
        $('.btn-quitar-cargo[data-id="' + cargoEliminarId + '"]').closest('tr').fadeOut(200, function () { $(this).remove(); });
        cargoEliminarId = null;
        toast('success', 'Cargo eliminado. Se aplicará al guardar cambios.');
        refreshCargosDisponibles();
    });

    /* ── Refrescar cargos disponibles en el modal de edición ── */
    function refreshCargosDisponibles() {
        const sedeOptions = '<option value="">— Seleccione sede —</option>' +
            todasSedes.map(function (s) { return '<option value="' + s.id + '">' + esc(s.nombre) + '</option>'; }).join('');

        const cargosIdsActuales = [];
        $('#editCargosActuales table tbody tr').each(function () {
            const $btn = $(this).find('.btn-quitar-cargo');
            const tcId = $btn.data('id');
            if (editCargosEliminados.indexOf(tcId) === -1) {
                cargosIdsActuales.push($btn.data('cargo-id'));
            }
        });

        const cargosDisponibles = todosCargos.filter(function (c) {
            return cargosIdsActuales.indexOf(c.id) === -1;
        });

        let htmlNuevos = '';
        if (cargosDisponibles.length === 0) {
            htmlNuevos = '<p style="color:var(--d-muted);font-size:0.85rem;">No hay cargos disponibles para agregar.</p>';
        } else {
            cargosDisponibles.forEach(function (c) {
                const today = new Date().toISOString().split('T')[0];
                htmlNuevos += '<div class="edit-cargo-item">'
                    + '<label class="tw-cargo-check edit-cargo-check">'
                    + '<input type="checkbox" class="edit-cargo-checkbox" value="' + c.id + '">'
                    + '<span class="tw-cargo-name">' + esc(c.nombre) + '</span>'
                    + '</label>'
                    + '<div class="tw-cargo-fields edit-cargo-fields">'
                    + '<div class="row g-2">'
                    + '<div class="col-md-6">'
                    + '<div class="tw-sucursal-label"><i class="ri-building-line" style="color:#fc7b04;"></i> Sede</div>'
                    + '<select class="form-select edit-cargo-sede" data-cargo="' + c.id + '">' + sedeOptions + '</select>'
                    + '</div>'
                    + '<div class="col-md-6">'
                    + '<div class="tw-sucursal-label"><i class="ri-building-2-line" style="color:#fc7b04;"></i> Sucursal</div>'
                    + '<select class="form-select edit-cargo-sucursal" data-cargo="' + c.id + '" disabled>'
                    + '<option value="">— Seleccione sede —</option>'
                    + '</select>'
                    + '</div>'
                    + '<div class="col-md-4">'
                    + '<label>Estado</label>'
                    + '<select class="form-select edit-cargo-estado" data-cargo="' + c.id + '">'
                    + '<option value="Vigente">Vigente</option>'
                    + '<option value="No Vigente">No Vigente</option>'
                    + '</select>'
                    + '</div>'
                    + '<div class="col-md-4">'
                    + '<label>Fecha de Ingreso</label>'
                    + '<input type="date" class="form-control edit-cargo-fecha-ingreso" data-cargo="' + c.id + '" value="' + today + '">'
                    + '</div>'
                    + '<div class="col-md-4">'
                    + '<label>Fecha de Término</label>'
                    + '<input type="date" class="form-control edit-cargo-fecha-termino" data-cargo="' + c.id + '">'
                    + '</div>'
                    + '</div></div></div>';
            });
        }
        $('#editCargosNuevos').html(htmlNuevos);
    }

    /* ── CONFIRMAR EDICION ── */
    function confirmarEdicion() {
        if (!editTrabajadorId || !editPersonaId) return;

        // Verificar si hay fotografía nueva
        var fotoInput = document.getElementById('fotografiaEditar');
        var hasFoto = fotoInput && fotoInput.files && fotoInput.files[0];

        // Validar datos personales
        const okC = validarCarnetSync('eCarnet','iconECarnet','fbECarnet');
        const okN = validarNombres('eNombres','iconENombres','fbENombres');
        const okAp = validarApellidosEditar();
        if (!okC || !okN || !okAp) return;
        if (document.getElementById('eCarnet').classList.contains('is-invalid')) return;
        if (document.getElementById('eCorreo').classList.contains('is-invalid')) return;

        const cargosAgregar = [];
        let hasError = false;

        $('.edit-cargo-checkbox:checked').each(function () {
            if (hasError) return;
            const cargoId = $(this).val();
            const sucursalId = $('.edit-cargo-sucursal[data-cargo="' + cargoId + '"]').val();
            const estado = $('.edit-cargo-estado[data-cargo="' + cargoId + '"]').val();
            const fechaIngreso = $('.edit-cargo-fecha-ingreso[data-cargo="' + cargoId + '"]').val();
            const fechaTermino = $('.edit-cargo-fecha-termino[data-cargo="' + cargoId + '"]').val() || null;

            if (!sucursalId) {
                $('#editCargosError').text('Debe seleccionar una sucursal para cada cargo nuevo.').show();
                hasError = true;
                return;
            }
            if (!fechaIngreso) {
                $('#editCargosError').text('La fecha de ingreso es obligatoria para todos los cargos nuevos.').show();
                hasError = true;
                return;
            }

            cargosAgregar.push({
                cargo_id: cargoId,
                sucursale_id: sucursalId,
                estado: estado,
                fecha_ingreso: fechaIngreso,
                fecha_termino: fechaTermino
            });
        });

        if (hasError) return;

        // Verificar si hay cambios (datos personales, fotografía o cargos)
        var hayCambiosPersona = hasFoto; // Si hay foto nueva, hay cambios
        
        // Verificar si cambiaron otros campos
        if (!hayCambiosPersona) {
            hayCambiosPersona = (
                $('#eCarnet').val().trim() !== originalData.eCarnet ||
                $('#eExpedido').val().trim() !== originalData.eExpedido ||
                $('#eNombres').val().trim() !== originalData.eNombres ||
                $('#eApPaterno').val().trim() !== originalData.eApPaterno ||
                $('#eApMaterno').val().trim() !== originalData.eApMaterno ||
                $('#eSexo').val() !== originalData.eSexo ||
                $('#eEstadoCivil').val() !== originalData.eEstadoCivil ||
                $('#eFechaNacimiento').val() !== originalData.eFechaNacimiento ||
                $('#eCorreo').val().trim() !== originalData.eCorreo ||
                $('#eCelular').val().trim() !== originalData.eCelular ||
                $('#eTelefono').val().trim() !== originalData.eTelefono ||
                $('#eDireccion').val().trim() !== originalData.eDireccion ||
                $('#eCiudad').val() !== originalData.eCiudad
            );
        }

        if (cargosAgregar.length === 0 && editCargosEliminados.length === 0 && !hayCambiosPersona) {
            toast('warning', 'No hay cambios para guardar.');
            return;
        }

setBtnLoading('#btnConfirmarEdicion', true, 'Guardando…');

        // Actualizar datos personales (siempre usar POST con _method: PUT)
        var formData = new FormData();
        formData.append('_token', CSRF);
        formData.append('_method', 'PUT');
        formData.append('carnet', $('#eCarnet').val().trim());
        formData.append('expedido', $('#eExpedido').val().trim());
        formData.append('nombres', $('#eNombres').val().trim());
        formData.append('apellido_paterno', $('#eApPaterno').val().trim());
        formData.append('apellido_materno', $('#eApMaterno').val().trim());
        formData.append('sexo', $('#eSexo').val());
        formData.append('estado_civil', $('#eEstadoCivil').val());
        formData.append('fecha_nacimiento', $('#eFechaNacimiento').val() || '');
        formData.append('correo', $('#eCorreo').val().trim());
        formData.append('celular', $('#eCelular').val().trim());
        formData.append('telefono', $('#eTelefono').val().trim());
        formData.append('direccion', $('#eDireccion').val().trim());
        formData.append('ciudade_id', $('#eCiudad').val() || '');

        if (hasFoto) {
            formData.append('fotografia', fotoInput.files[0]);
        }

        var personaUrl = '/admin/personas/' + editPersonaId;
        $.ajax({ url: personaUrl, type: 'POST', data: formData, processData: false, contentType: false })
        .done(function (r) {
            // Luego actualizar cargos
            return $.post('{{ route("admin.trabajadores.actualizarCargos") }}', {
                _token: CSRF,
                trabajadore_id: editTrabajadorId,
                cargos_agregar: cargosAgregar,
                cargos_eliminar: editCargosEliminados
            });
        })
        .done(function (r) {
            toast('success', r.message);
            closeModal('modalEditar');
            tabla.ajax.reload(null, false);
        })
        .fail(function (xhr) {
            const msg = xhr.responseJSON?.errors
                ? Object.values(xhr.responseJSON.errors).flat().join(' ')
                : xhr.responseJSON?.message || 'Error al actualizar.';
            toast('error', msg);
        })
        .always(function () {
            setBtnLoading('#btnConfirmarEdicion', false, '<i class="ri-check-line"></i> Guardar Cambios');
        });
    }

    /* ── ELIMINAR TRABAJADOR ── */
    function eliminarTrabajador(id) {
        setBtnLoading('#btnConfirmarEliminar', true, '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
        $.ajax({ url: '/admin/trabajadores/' + id, type: 'DELETE', data: { _token: CSRF } })
            .done(function (r) {
                closeModal('modalEliminar');
                tabla.ajax.reload(null, false);
                toast('success', r.message);
            })
            .fail(function (xhr) {
                const msg = xhr.responseJSON?.message || 'No se pudo eliminar.';
                toast('error', msg);
            })
            .always(function () {
                setBtnLoading('#btnConfirmarEliminar', false, '<i class="ri-delete-bin-line"></i> Eliminar');
                idEliminar = null;
            });
    }

    /* ── LIMPIAR BÚSQUEDA ── */
    function limpiarBusqueda() {
        $('#searchCarnet').val('');
        $('#personaFound').slideUp(200);
        $('#cargosSection').slideUp(200);
        $('#btnLimpiarBusqueda').hide();
        $('#btnLimpiarBusqueda2').hide();
        personaSeleccionada = null;
    }

    /* ── RESET FORM REGISTRO ── */
    function resetFormRegistro() {
        $('#formRegistro')[0].reset();
        resetField('rCarnet', 'iconRCarnet', 'fbRCarnet');
        resetField('rNombres', 'iconRNombres', 'fbRNombres');
        resetField('rCorreo', 'iconRCorreo', 'fbRCorreo');
        $('#fbRApellidos').removeClass('error success').html('');
        $('#rDepto').val('');
        $('#rCiudad').find('option:not(:first)').remove().end().prop('disabled', true).find('option:first').text('— Seleccione depto. —');

        ['rCarnet','rNombres','rApPaterno','rApMaterno','rCorreo','rCelular','rTelefono','rDireccion','rExpedido','rFechaNacimiento'].forEach(function (id) {
            $('#' + id).prop('readonly', false);
        });
        ['rSexo','rEstadoCivil','rCiudad'].forEach(function (id) {
            $('#' + id).prop('disabled', false);
        });

        $('#registroYaTrabajador').hide();
        $('#btnGuardarTrabajador').prop('disabled', false).html('<i class="ri-save-line"></i> Registrar Trabajador');
        $('#modalRegistroTitle').html('<i class="ri-user-add-line"></i> Registrar Trabajador');
    }

    /* ── RESET FORM PERSONA ── */
    function resetFormPersona() {
        $('#formPersona')[0].reset();
        ['pCarnet','pNombres','pCorreo'].forEach(function (id) {
            resetField(id, 'icon' + id.charAt(1).toUpperCase() + id.slice(1), 'fb' + id.charAt(1).toUpperCase() + id.slice(1));
        });
        resetField('pCarnet', 'iconPCarnet', 'fbPCarnet');
        resetField('pNombres', 'iconPNombres', 'fbPNombres');
        resetField('pCorreo', 'iconPCorreo', 'fbPCorreo');
        $('#fbPApellidos').removeClass('error success').html('');
        $('#pDepto').val('');
        $('#pCiudad').find('option:not(:first)').remove().end().prop('disabled', true).find('option:first').text('— Seleccione depto. —');
    }

    /* ════════════════════ VALIDACIÓN ════════════════════ */

    function setError(inputId, iconId, fbId, msg) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        if (icon) { icon.className = 'validation-icon invalid'; icon.innerHTML = '<i class="ri-close-circle-fill"></i>'; }
        if (fb) { fb.className = 'field-feedback error'; fb.innerHTML = '<i class="ri-error-warning-line"></i>' + msg; }
        return false;
    }

    function setOk(inputId, iconId, fbId, msg) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        if (icon) { icon.className = 'validation-icon valid'; icon.innerHTML = '<i class="ri-checkbox-circle-fill"></i>'; }
        if (fb) { fb.className = 'field-feedback success'; fb.innerHTML = '<i class="ri-check-line"></i>' + msg; }
        return true;
    }

    function setChecking(inputId, iconId, fbId) {
        const icon = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        if (icon) { icon.className = 'validation-icon'; icon.innerHTML = '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;"></i>'; }
        if (fb) { fb.className = 'field-feedback'; fb.innerHTML = 'Verificando…'; }
    }

    function resetField(inputId, iconId, fbId) {
        const input = document.getElementById(inputId);
        if (input) input.classList.remove('is-valid', 'is-invalid');
        const icon = document.getElementById(iconId);
        if (icon) { icon.className = 'validation-icon'; icon.innerHTML = ''; }
        const fb = document.getElementById(fbId);
        if (fb) { fb.className = 'field-feedback'; fb.innerHTML = ''; }
    }

    function isEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

    function verificarCarnetPersona(val) {
        $.post('{{ route("admin.trabajadores.verificarCarnetPersona") }}', { _token: CSRF, carnet: val }, function (r) {
            if (r.existe) setError('pCarnet','iconPCarnet','fbPCarnet','Este carnet ya está registrado.');
            else setOk('pCarnet','iconPCarnet','fbPCarnet','Carnet disponible');
        }).fail(function () { resetField('pCarnet','iconPCarnet','fbPCarnet'); });
    }

    function verificarCarnetRegistro(val) {
        $.post('{{ route("admin.trabajadores.verificarCarnetPersona") }}', { _token: CSRF, carnet: val }, function (r) {
            if (r.existe) setError('rCarnet','iconRCarnet','fbRCarnet','Este carnet ya está registrado.');
            else setOk('rCarnet','iconRCarnet','fbRCarnet','Carnet disponible');
        }).fail(function () { resetField('rCarnet','iconRCarnet','fbRCarnet'); });
    }

    function verificarCorreoPersona(val) {
        $.post('{{ route("admin.trabajadores.verificarCorreoPersona") }}', { _token: CSRF, correo: val }, function (r) {
            if (r.existe) setError('pCorreo','iconPCorreo','fbPCorreo','Este correo ya está registrado.');
            else setOk('pCorreo','iconPCorreo','fbPCorreo','Correo disponible');
        }).fail(function () { resetField('pCorreo','iconPCorreo','fbPCorreo'); });
    }

    function verificarCorreoRegistro(val) {
        $.post('{{ route("admin.trabajadores.verificarCorreoPersona") }}', { _token: CSRF, correo: val }, function (r) {
            if (r.existe) setError('rCorreo','iconRCorreo','fbRCorreo','Este correo ya está registrado.');
            else setOk('rCorreo','iconRCorreo','fbRCorreo','Correo disponible');
        }).fail(function () { resetField('rCorreo','iconRCorreo','fbRCorreo'); });
    }

    function verificarCarnetEditar(val) {
        $.post('{{ route("admin.trabajadores.verificarCarnetPersona") }}', { _token: CSRF, carnet: val, id: editPersonaId }, function (r) {
            if (r.existe) setError('eCarnet','iconECarnet','fbECarnet','Este carnet ya está registrado.');
            else setOk('eCarnet','iconECarnet','fbECarnet','Carnet disponible');
        }).fail(function () { resetField('eCarnet','iconECarnet','fbECarnet'); });
    }

    function verificarCorreoEditar(val) {
        $.post('{{ route("admin.trabajadores.verificarCorreoPersona") }}', { _token: CSRF, correo: val, id: editPersonaId }, function (r) {
            if (r.existe) setError('eCorreo','iconECorreo','fbECorreo','Este correo ya está registrado.');
            else setOk('eCorreo','iconECorreo','fbECorreo','Correo disponible');
        }).fail(function () { resetField('eCorreo','iconECorreo','fbECorreo'); });
    }

    function validarApellidosEditar() {
        const p = $('#eApPaterno').val().trim();
        const m = $('#eApMaterno').val().trim();
        const fb = document.getElementById('fbEApellidos');
        if (!p && !m) {
            fb.className = 'field-feedback error';
            fb.innerHTML = '<i class="ri-error-warning-line"></i>Debe registrar al menos un apellido (paterno o materno).';
            return false;
        }
        fb.className = 'field-feedback success';
        fb.innerHTML = '<i class="ri-check-line"></i>Apellido(s) válido(s)';
        return true;
    }

    function filtrarCiudadesAsync(ctx) {
        const deptoId = $('#eDepto').val();
        const $ciudad = $('#eCiudad');
        const prevVal = $ciudad.val();
        $ciudad.find('option:not(:first)').remove();
        if (!deptoId) {
            $ciudad.prop('disabled', true).find('option:first').text('— Seleccione depto. —');
            return $.Deferred().resolve();
        }
        const filtradas = todasCiudades.filter(function (c) { return c.departamento_id == deptoId; });
        $ciudad.append(filtradas.map(function (c) { return '<option value="' + c.id + '">' + esc(c.nombre) + '</option>'; }).join(''));
        $ciudad.prop('disabled', false).find('option:first').text('— Seleccione ciudad —');
        if (filtradas.some(function (c) { return c.id == prevVal; })) $ciudad.val(prevVal);
        return $.Deferred().resolve();
    }

    function validarCarnetSync(inputId, iconId, fbId) {
        const val = document.getElementById(inputId).value.trim();
        if (!val) return setError(inputId, iconId, fbId, 'El carnet es obligatorio.');
        if (val.length < 3) return setError(inputId, iconId, fbId, 'Debe tener al menos 3 caracteres.');
        return true;
    }

    function validarNombres(inputId, iconId, fbId) {
        const val = document.getElementById(inputId).value.trim();
        if (!val) return setError(inputId, iconId, fbId, 'El nombre es obligatorio.');
        if (val.length < 2) return setError(inputId, iconId, fbId, 'Debe tener al menos 2 caracteres.');
        return setOk(inputId, iconId, fbId, 'Nombre válido');
    }

    function validarApellidos() {
        const p = $('#pApPaterno').val().trim();
        const m = $('#pApMaterno').val().trim();
        const fb = document.getElementById('fbPApellidos');
        if (!p && !m) {
            fb.className = 'field-feedback error';
            fb.innerHTML = '<i class="ri-error-warning-line"></i>Debe registrar al menos un apellido (paterno o materno).';
            return false;
        }
        fb.className = 'field-feedback success';
        fb.innerHTML = '<i class="ri-check-line"></i>Apellido(s) válido(s)';
        return true;
    }

    function validarApellidosRegistro() {
        const p = $('#rApPaterno').val().trim();
        const m = $('#rApMaterno').val().trim();
        const fb = document.getElementById('fbRApellidos');
        if (!p && !m) {
            fb.className = 'field-feedback error';
            fb.innerHTML = '<i class="ri-error-warning-line"></i>Debe registrar al menos un apellido (paterno o materno).';
            return false;
        }
        fb.className = 'field-feedback success';
        fb.innerHTML = '<i class="ri-check-line"></i>Apellido(s) válido(s)';
        return true;
    }

    /* ════════════════════ UTILIDADES ════════════════════ */

    function setBtnLoading(sel, loading, labelHtml) {
        const btn = document.querySelector(sel);
        if (!btn) return;
        btn.disabled = loading;
        if (loading) {
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

    function esc(str) {
        return String(str || '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function toast(tipo, mensaje) {
        const iconMap = { success: 'ri-check-double-line', error: 'ri-close-circle-line', warning: 'ri-alert-line' };
        const el = document.createElement('div');
        el.className = 'toast-notify ' + tipo;
        el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') + '"></i></div>'
            + '<div class="toast-body-text"><span>' + mensaje + '</span></div>'
            + '<button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';
        let c = document.getElementById('toastContainer');
        if (!c) { c = document.createElement('div'); c.id = 'toastContainer'; c.className = 'toast-container'; document.body.appendChild(c); }
        if (c.parentElement !== document.body) document.body.appendChild(c);
        c.style.top = Math.max(20, window.scrollY + 20) + 'px';
        c.appendChild(el);
        el.querySelector('.toast-close').addEventListener('click', function () { removeToast(el); });
        setTimeout(function () { removeToast(el); }, 4500);
    }

    function removeToast(el) {
        el.classList.add('hiding');
        el.addEventListener('animationend', function () { el.remove(); }, { once: true });
    }

    $(document).ready(init);
})();
</script>
@endsection
