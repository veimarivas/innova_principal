<?php $__env->startSection('title'); ?> Personas <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
.badge-sexo { display: inline-flex; align-items: center; justify-content: center; background: var(--d-badge-bg); color: var(--d-badge-color); border: 1px solid var(--d-badge-border); font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 6px; text-transform: uppercase; }
.badge-estado-concluido { display: inline-flex; align-items: center; gap: 0.3rem; background: rgba(91,138,48,0.12); color: #5a8a30; border: 1px solid rgba(91,138,48,0.25); font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 6px; }
html[data-bs-theme="dark"] .badge-estado-concluido { background: rgba(109,191,64,0.12); color: #6dbf40; border-color: rgba(109,191,64,0.25); }
.badge-estado-desarrollo { display: inline-flex; align-items: center; gap: 0.3rem; background: rgba(252,123,4,0.12); color: #bc5404; border: 1px solid rgba(252,123,4,0.25); font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 6px; }
html[data-bs-theme="dark"] .badge-estado-desarrollo { background: rgba(252,123,4,0.15); color: #fc7b04; border-color: rgba(252,123,4,0.30); }
.badge-principal { display: inline-flex; align-items: center; gap: 0.25rem; background: rgba(252,200,4,0.15); color: #a07804; border: 1px solid rgba(252,200,4,0.30); font-size: 0.68rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 5px; }
html[data-bs-theme="dark"] .badge-principal { background: rgba(252,200,4,0.12); color: #fcc804; border-color: rgba(252,200,4,0.25); }
.btn-action-estudios { height: 34px; padding: 0 0.85rem !important; width: auto; border-radius: 10px !important; font-size: 0.77rem !important; font-weight: 600; background: rgba(91,164,4,0.10); border: 1px solid rgba(91,164,4,0.20); color: #4d8700; display: inline-flex !important; align-items: center; gap: 0.35rem; transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1) !important; cursor: pointer; }
html[data-bs-theme="dark"] .btn-action-estudios { background: rgba(109,191,64,0.12); border-color: rgba(109,191,64,0.20); color: #6dbf40; }
.btn-action-estudios:hover { background: linear-gradient(135deg,#5ba404,#7bc800); color: #fff; border-color: transparent; transform: scale(1.05); }
.section-sep { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: var(--d-muted); border-bottom: 1px solid var(--d-card-border); padding-bottom: 0.35rem; margin: 1.25rem 0 1rem; display: flex; align-items: center; gap: 0.4rem; }
.section-sep:first-child { margin-top: 0; }
.section-sep i { font-size: 0.85rem; color: #fc7b04; }
.estudios-wrap { background: rgba(252,123,4,0.04); border: 1px solid var(--d-card-border); border-radius: 12px; overflow: hidden; }
html[data-bs-theme="dark"] .estudios-wrap { background: rgba(252,123,4,0.03); }
.estudios-table { width: 100%; border-collapse: separate; border-spacing: 0; }
.estudios-table thead th { background: var(--d-thead-bg); color: var(--d-thead-color); font-size: 0.67rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; padding: 0.65rem 0.85rem; border-bottom: 2px solid var(--d-header-border); }
.estudios-table tbody td { padding: 0.7rem 0.85rem; border-bottom: 1px solid var(--d-row-border); color: var(--d-body); vertical-align: middle; font-size: 0.83rem; }
.estudios-table tbody tr:hover td { background: var(--d-row-hover); }
.estudios-table tbody tr:last-child td { border-bottom: none; }
.estudios-empty { text-align: center; color: var(--d-muted); padding: 1.75rem; font-size: 0.85rem; }
.estudio-form-box { background: var(--d-input-bg); border: 1.5px dashed var(--d-input-border); border-radius: 12px; padding: 1rem 1.1rem 1.1rem; margin-top: 0.75rem; }
html[data-bs-theme="dark"] .estudio-form-box { background: rgba(255,255,255,0.03); }
.modal-dialog-scrollable .modal-content form { display: flex; flex-direction: column; flex: 1 1 auto; overflow: hidden; min-height: 0; }
.modal-dialog-scrollable .modal-content form .modal-body { flex: 1 1 auto; overflow-y: auto; }
.modal-dialog-scrollable .modal-content form .modal-footer { flex-shrink: 0; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="dept-page-header">
    <div class="container-fluid">
        <div class="dph-inner">
            <div class="dph-left">
                <div class="dph-icon-wrap"><i class="ri-user-line"></i></div>
                <div>
                    <h1 class="dph-title">Personas</h1>
                    <p class="dph-desc">Gestión y administración de personas registradas</p>
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
                    <i class="ri-add-line"></i><span>Nueva Persona</span>
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
                        <div class="dept-header-icon"><i class="ri-table-line"></i></div>
                        <div>
                            <h5 class="dept-title">Listado de Personas</h5>
                            <p class="dept-subtitle">Consulta, edita o administra los estudios de cada persona</p>
                        </div>
                    </div>
                </div>
                <div class="dept-card-body">
                    <table id="tabla-personas" class="dept-table">
                        <thead>
                            <tr>
                                <th>Carnet</th>
                                <th>Nombres y Apellidos</th>
                                <th>Sexo</th>
                                <th>Estado Civil</th>
                                <th>Correo / Celular</th>
                                <th>Ciudad</th>
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
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-user-add-line"></i> Nueva Persona</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formCrear" novalidate autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" id="idCrear">
                <div class="modal-body">

                    <div class="row g-3 mb-3">
                        <div class="col-md-4 text-center">
                            <label class="form-label d-block"><i class="ri-camera-line" style="color:#fc7b04;"></i> Fotografía</label>
                            <div class="photo-upload-container" style="cursor:pointer;" onclick="document.getElementById('fotografiaCrear').click()">
                                <img id="previewFotografiaCrear" src="<?php echo e(URL::asset('build/images/users/avatar-1.jpg')); ?>" 
                                     style="width:100px;height:100px;object-fit:cover;border-radius:50%;border:3px solid #e2e8f0;">
                                <input type="file" id="fotografiaCrear" name="fotografia" accept="image/*" style="display:none;" 
                                       onchange="previewImage(this, 'previewFotografiaCrear')">
                            </div>
                            <small class="text-muted">Click para seleccionar</small>
                        </div>
                    </div>

                    <p class="section-sep"><i class="ri-id-card-line"></i> Datos de Identidad</p>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label"><i class="ri-id-card-line" style="color:#fc7b04;"></i> Carnet <span class="req">*</span></label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="carnetCrear" placeholder="Ej: 12345678" maxlength="20" autocomplete="off">
                                <span class="validation-icon" id="iconCarnetCrear"></span>
                            </div>
                            <div class="field-feedback" id="fbCarnetCrear"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-map-pin-line" style="color:#fc7b04;"></i> Expedido</label>
                            <input type="text" class="form-control" id="expedidoCrear" placeholder="Ej: LP" maxlength="10" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-calendar-line" style="color:#fc7b04;"></i> Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fechaNacimientoCrear">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label"><i class="ri-user-line" style="color:#fc7b04;"></i> Nombres <span class="req">*</span></label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="nombresCrear" placeholder="Ej: Juan Carlos" maxlength="100" autocomplete="off">
                                <span class="validation-icon" id="iconNombresCrear"></span>
                            </div>
                            <div class="field-feedback" id="fbNombresCrear"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-user-3-line" style="color:#fc7b04;"></i> Sexo</label>
                            <select class="form-select" id="sexoCrear">
                                <option value="">— Seleccione —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Apellido Paterno</label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="apPaternoCrear" placeholder="Ej: García" maxlength="80" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido Materno</label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="apMaternoCrear" placeholder="Ej: López" maxlength="80" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="field-feedback" id="fbApellidosCrear"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-heart-line" style="color:#fc7b04;"></i> Estado Civil</label>
                            <select class="form-select" id="estadoCivilCrear">
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
                            <select class="form-select" id="deptoCrear">
                                <option value="">— Seleccione —</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-building-line" style="color:#fc7b04;"></i> Ciudad</label>
                            <select class="form-select" id="ciudadCrear" disabled>
                                <option value="">— Seleccione depto. —</option>
                            </select>
                        </div>
                    </div>

                    <p class="section-sep"><i class="ri-phone-line"></i> Datos de Contacto</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="ri-mail-line" style="color:#fc7b04;"></i> Correo Electrónico</label>
                            <div class="field-wrapper">
                                <input type="email" class="form-control" id="correoCrear" placeholder="Ej: correo@dominio.com" maxlength="150" autocomplete="off">
                                <span class="validation-icon" id="iconCorreoCrear"></span>
                            </div>
                            <div class="field-feedback" id="fbCorreoCrear"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-smartphone-line" style="color:#fc7b04;"></i> Celular</label>
                            <input type="text" class="form-control" id="celularCrear" placeholder="Ej: 70000000" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-phone-line" style="color:#fc7b04;"></i> Teléfono</label>
                            <input type="text" class="form-control" id="telefonoCrear" placeholder="Ej: 2000000" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="ri-map-pin-2-line" style="color:#fc7b04;"></i> Dirección</label>
                            <input type="text" class="form-control" id="direccionCrear" placeholder="Ej: Av. 6 de Agosto N° 123" maxlength="200" autocomplete="off">
                        </div>
                    </div>

                    
                    <p class="section-sep" style="margin-top:1.5rem;"><i class="ri-graduation-cap-line"></i> Estudios Académicos</p>

                    <div id="estudiosLockCrear" style="background:rgba(252,123,4,0.04);border:1px dashed var(--d-input-border);border-radius:12px;padding:2rem;text-align:center;">
                        <i class="ri-lock-line" style="font-size:2rem;color:var(--d-muted);"></i>
                        <p style="color:var(--d-muted);font-size:0.85rem;margin:0.5rem 0 0;">Guarda primero los datos de la persona para agregar estudios.</p>
                    </div>

                    <div id="estudiosActivoCrear" style="display:none;">
                        <div class="estudios-wrap mb-3">
                            <table class="estudios-table">
                                <thead><tr>
                                    <th>Grado Académico</th><th>Profesión</th><th>Universidad</th>
                                    <th class="text-center">Estado</th><th class="text-center">Principal</th>
                                    <th class="text-center" style="width:90px;">Acciones</th>
                                </tr></thead>
                                <tbody id="bodyEstudiosCrear">
                                    <tr><td colspan="6" class="estudios-empty"><i class="ri-inbox-line me-1"></i> Sin estudios registrados</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="estudio-form-box">
                            <p style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:var(--d-muted);margin-bottom:0.75rem;" id="tituloFormEstudioCrear">
                                <i class="ri-add-circle-line" style="color:#fc7b04;"></i> Agregar nuevo estudio
                            </p>
                            <input type="hidden" id="estudioIdCrear">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.8rem;margin-bottom:0.3rem;"><i class="ri-medal-line" style="color:#fc7b04;"></i> Grado Académico <span class="req">*</span></label>
                                    <select class="form-select" id="gradoEstudioCrear" style="padding:0.55rem 0.85rem !important;font-size:0.85rem !important;"><option value="">— Seleccione —</option></select>
                                    <div class="field-feedback" id="fbGradoEstudioCrear"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.8rem;margin-bottom:0.3rem;"><i class="ri-briefcase-line" style="color:#fc7b04;"></i> Profesión</label>
                                    <select class="form-select" id="profesionEstudioCrear" style="padding:0.55rem 0.85rem !important;font-size:0.85rem !important;"><option value="">— Seleccione —</option></select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.8rem;margin-bottom:0.3rem;"><i class="ri-building-2-line" style="color:#fc7b04;"></i> Universidad</label>
                                    <select class="form-select" id="universidadEstudioCrear" style="padding:0.55rem 0.85rem !important;font-size:0.85rem !important;"><option value="">— Seleccione —</option></select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.8rem;margin-bottom:0.3rem;"><i class="ri-toggle-line" style="color:#fc7b04;"></i> Estado <span class="req">*</span></label>
                                    <select class="form-select" id="estadoEstudioCrear" style="padding:0.55rem 0.85rem !important;font-size:0.85rem !important;">
                                        <option value="Concluido">Concluido</option>
                                        <option value="En Desarrollo">En Desarrollo</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end pb-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="principalEstudioCrear">
                                        <label class="form-check-label" for="principalEstudioCrear" style="font-size:0.83rem;font-weight:600;color:var(--d-title);">
                                            <i class="ri-star-line" style="color:#fc7b04;"></i> Estudio Principal
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end gap-2">
                                    <button type="button" class="btn btn-modal-submit flex-fill" id="btnGuardarEstudioCrear" style="padding:0.55rem 0.9rem;font-size:0.83rem;">
                                        <i class="ri-add-line"></i> <span id="labelBtnEstudioCrear">Agregar</span>
                                    </button>
                                    <button type="button" class="btn btn-modal-cancel" id="btnCancelarEstudioCrear" style="display:none;padding:0.55rem 0.9rem;font-size:0.83rem;">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-modal-submit" id="btnGuardar"><i class="ri-save-line"></i> Guardar Persona</button>
                    <button type="button" class="btn btn-modal-cancel" id="btnFinalizarCrear" style="display:none;" data-bs-dismiss="modal"><i class="ri-check-line me-1"></i>Finalizar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-edit-2-line"></i> Editar Persona</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditar" novalidate autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" id="idEditar">
                <div class="modal-body">

                    <div class="row g-3 mb-3">
                        <div class="col-md-4 text-center">
                            <label class="form-label d-block"><i class="ri-camera-line" style="color:#fc7b04;"></i> Fotografía</label>
                            <div class="photo-upload-container" style="cursor:pointer;" onclick="document.getElementById('fotografiaEditar').click()">
                                <img id="previewFotografiaEditar" src="<?php echo e(URL::asset('build/images/users/avatar-1.jpg')); ?>" 
                                     style="width:100px;height:100px;object-fit:cover;border-radius:50%;border:3px solid #e2e8f0;">
                                <input type="file" id="fotografiaEditar" name="fotografia" accept="image/*" style="display:none;" 
                                       onchange="previewImage(this, 'previewFotografiaEditar')">
                            </div>
                            <small class="text-muted">Click para seleccionar</small>
                        </div>
                    </div>

                    <p class="section-sep"><i class="ri-id-card-line"></i> Datos de Identidad</p>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label"><i class="ri-id-card-line" style="color:#fc7b04;"></i> Carnet <span class="req">*</span></label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="carnetEditar" placeholder="Ej: 12345678" maxlength="20" autocomplete="off">
                                <span class="validation-icon" id="iconCarnetEditar"></span>
                            </div>
                            <div class="field-feedback" id="fbCarnetEditar"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-map-pin-line" style="color:#fc7b04;"></i> Expedido</label>
                            <input type="text" class="form-control" id="expedidoEditar" placeholder="Ej: LP" maxlength="10" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-calendar-line" style="color:#fc7b04;"></i> Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fechaNacimientoEditar">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label"><i class="ri-user-line" style="color:#fc7b04;"></i> Nombres <span class="req">*</span></label>
                            <div class="field-wrapper">
                                <input type="text" class="form-control" id="nombresEditar" placeholder="Ej: Juan Carlos" maxlength="100" autocomplete="off">
                                <span class="validation-icon" id="iconNombresEditar"></span>
                            </div>
                            <div class="field-feedback" id="fbNombresEditar"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-user-3-line" style="color:#fc7b04;"></i> Sexo</label>
                            <select class="form-select" id="sexoEditar">
                                <option value="">— Seleccione —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control" id="apPaternoEditar" placeholder="Ej: García" maxlength="80" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control" id="apMaternoEditar" placeholder="Ej: López" maxlength="80" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <div class="field-feedback" id="fbApellidosEditar"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-heart-line" style="color:#fc7b04;"></i> Estado Civil</label>
                            <select class="form-select" id="estadoCivilEditar">
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
                            <select class="form-select" id="deptoEditar">
                                <option value="">— Seleccione —</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="ri-building-line" style="color:#fc7b04;"></i> Ciudad</label>
                            <select class="form-select" id="ciudadEditar" disabled>
                                <option value="">— Seleccione depto. —</option>
                            </select>
                        </div>
                    </div>

                    <p class="section-sep"><i class="ri-phone-line"></i> Datos de Contacto</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="ri-mail-line" style="color:#fc7b04;"></i> Correo Electrónico</label>
                            <div class="field-wrapper">
                                <input type="email" class="form-control" id="correoEditar" placeholder="Ej: correo@dominio.com" maxlength="150" autocomplete="off">
                                <span class="validation-icon" id="iconCorreoEditar"></span>
                            </div>
                            <div class="field-feedback" id="fbCorreoEditar"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-smartphone-line" style="color:#fc7b04;"></i> Celular</label>
                            <input type="text" class="form-control" id="celularEditar" placeholder="Ej: 70000000" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="ri-phone-line" style="color:#fc7b04;"></i> Teléfono</label>
                            <input type="text" class="form-control" id="telefonoEditar" placeholder="Ej: 2000000" maxlength="20" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="ri-map-pin-2-line" style="color:#fc7b04;"></i> Dirección</label>
                            <input type="text" class="form-control" id="direccionEditar" placeholder="Ej: Av. 6 de Agosto N° 123" maxlength="200" autocomplete="off">
                        </div>
                    </div>

                    
                    <p class="section-sep" style="margin-top:1.5rem;"><i class="ri-graduation-cap-line"></i> Estudios Académicos</p>

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
                            <tbody id="bodyEstudios">
                                <tr><td colspan="6" class="estudios-empty"><i class="ri-inbox-line me-1"></i> Sin estudios registrados</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="estudio-form-box">
                        <p style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:var(--d-muted);margin-bottom:0.75rem;" id="tituloFormEstudio">
                            <i class="ri-add-circle-line" style="color:#fc7b04;"></i> Agregar nuevo estudio
                        </p>
                        <input type="hidden" id="estudioIdEditar">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label" style="font-size:0.8rem;margin-bottom:0.3rem;">
                                    <i class="ri-medal-line" style="color:#fc7b04;"></i> Grado Académico <span class="req">*</span>
                                </label>
                                <select class="form-select" id="gradoEstudio" style="padding:0.55rem 0.85rem !important;font-size:0.85rem !important;">
                                    <option value="">— Seleccione —</option>
                                </select>
                                <div class="field-feedback" id="fbGradoEstudio"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" style="font-size:0.8rem;margin-bottom:0.3rem;">
                                    <i class="ri-briefcase-line" style="color:#fc7b04;"></i> Profesión
                                </label>
                                <select class="form-select" id="profesionEstudio" style="padding:0.55rem 0.85rem !important;font-size:0.85rem !important;">
                                    <option value="">— Seleccione —</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" style="font-size:0.8rem;margin-bottom:0.3rem;">
                                    <i class="ri-building-2-line" style="color:#fc7b04;"></i> Universidad
                                </label>
                                <select class="form-select" id="universidadEstudio" style="padding:0.55rem 0.85rem !important;font-size:0.85rem !important;">
                                    <option value="">— Seleccione —</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" style="font-size:0.8rem;margin-bottom:0.3rem;">
                                    <i class="ri-toggle-line" style="color:#fc7b04;"></i> Estado <span class="req">*</span>
                                </label>
                                <select class="form-select" id="estadoEstudio" style="padding:0.55rem 0.85rem !important;font-size:0.85rem !important;">
                                    <option value="Concluido">Concluido</option>
                                    <option value="En Desarrollo">En Desarrollo</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end pb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="principalEstudio">
                                    <label class="form-check-label" for="principalEstudio"
                                           style="font-size:0.83rem;font-weight:600;color:var(--d-title);">
                                        <i class="ri-star-line" style="color:#fc7b04;"></i> Estudio Principal
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end gap-2">
                                <button type="button" class="btn btn-modal-submit flex-fill" id="btnGuardarEstudio"
                                        style="padding:0.55rem 0.9rem;font-size:0.83rem;">
                                    <i class="ri-add-line"></i> <span id="labelBtnEstudio">Agregar</span>
                                </button>
                                <button type="button" class="btn btn-modal-cancel" id="btnCancelarEstudio" style="display:none;padding:0.55rem 0.9rem;font-size:0.83rem;">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-modal-submit" id="btnActualizar"><i class="ri-refresh-line"></i> Actualizar</button>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="delete-warning-box">
                    <div class="delete-icon-ring"><i class="ri-delete-bin-5-line"></i></div>
                    <p class="delete-msg-primary">¿Eliminar persona?</p>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
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
    let idEliminar      = null;
    let modoEdicionE    = false;   // para estudios en editar
    let modoEdicionC    = false;   // para estudios en crear
    let carnetTimer     = null, correoTimer = null;
    let todasCiudades   = [];
    const CSRF = '<?php echo e(csrf_token()); ?>';

    /* ── INIT ── */
    function init() {
        cargarSelectores();
        initDataTable();
        bindEvents();
    }

    /* ── SELECTORES ── */
    function cargarSelectores() {
        $.getJSON('<?php echo e(route("admin.personas.listarDepartamentos")); ?>', function (r) {
            const opts = r.data.map(d => '<option value="' + d.id + '">' + esc(d.nombre) + '</option>').join('');
            $('#deptoCrear, #deptoEditar').append(opts);
        });
        $.getJSON('<?php echo e(route("admin.personas.listarCiudades")); ?>', function (r) {
            todasCiudades = r.data;
        });
        $.getJSON('<?php echo e(route("admin.personas.listarGrados")); ?>', function (r) {
            const opts = r.data.map(g => '<option value="' + g.id + '">' + esc(g.nombre) + '</option>').join('');
            $('#gradoEstudio, #gradoEstudioCrear').append(opts);
        });
        $.getJSON('<?php echo e(route("admin.personas.listarProfesiones")); ?>', function (r) {
            const opts = r.data.map(p => '<option value="' + p.id + '">' + esc(p.nombre) + '</option>').join('');
            $('#profesionEstudio, #profesionEstudioCrear').append(opts);
        });
        $.getJSON('<?php echo e(route("admin.personas.listarUniversidades")); ?>', function (r) {
            const opts = r.data.map(u => '<option value="' + u.id + '">' + esc(u.nombre) + (u.sigla ? ' (' + u.sigla + ')' : '') + '</option>').join('');
            $('#universidadEstudio, #universidadEstudioCrear').append(opts);
        });
    }

    /* ── CASCADA DEPARTAMENTO → CIUDAD ── */
    function filtrarCiudades(ctx) {
        const deptoId  = $('#depto' + ctx).val();
        const $ciudad  = $('#ciudad' + ctx);
        const prevVal  = $ciudad.val();
        $ciudad.find('option:not(:first)').remove();
        if (!deptoId) {
            $ciudad.prop('disabled', true).find('option:first').text('— Seleccione depto. —');
            return;
        }
        const filtradas = todasCiudades.filter(function (c) { return c.departamento_id == deptoId; });
        $ciudad.append(filtradas.map(c => '<option value="' + c.id + '">' + esc(c.nombre) + '</option>').join(''));
        $ciudad.prop('disabled', false).find('option:first').text('— Seleccione ciudad —');
        if (filtradas.some(function (c) { return c.id == prevVal; })) $ciudad.val(prevVal);
    }

    /* ── DATATABLE ── */
    function initDataTable() {
        tabla = $('#tabla-personas').DataTable({
            ajax: { url: '<?php echo e(route("admin.personas.listar")); ?>', dataSrc: 'data' },
            ordering: true,
            columns: [
                {
                    data: null,
                    render: d => {
                        let txt = '<span style="font-weight:700;">' + esc(d.carnet) + '</span>';
                        if (d.expedido) txt += '<br><small style="color:var(--d-muted);font-size:0.72rem;">exp. ' + esc(d.expedido) + '</small>';
                        return txt;
                    }
                },
                {
                    data: null,
                    render: d => {
                        const n  = esc(d.nombres);
                        const ap = [d.apellido_paterno, d.apellido_materno].filter(Boolean).map(esc).join(' ');
                        return '<span style="font-weight:600;">' + n + '</span>' + (ap ? '<br><small style="color:var(--d-muted);">' + ap + '</small>' : '');
                    }
                },
                {
                    data: 'sexo',
                    render: s => s ? '<span class="badge-sexo">' + esc(s) + '</span>' : '<span style="color:var(--d-muted)">—</span>'
                },
                {
                    data: 'estado_civil',
                    render: e => e ? esc(e) : '<span style="color:var(--d-muted)">—</span>'
                },
                {
                    data: null,
                    render: d => {
                        let h = '';
                        if (d.correo)  h += '<div style="font-size:0.82rem;"><i class="ri-mail-line" style="color:#fc7b04;font-size:0.78rem;"></i> ' + esc(d.correo) + '</div>';
                        if (d.celular) h += '<div style="font-size:0.82rem;"><i class="ri-smartphone-line" style="color:#fc7b04;font-size:0.78rem;"></i> ' + esc(d.celular) + '</div>';
                        return h || '<span style="color:var(--d-muted)">—</span>';
                    }
                },
                {
                    data: 'ciudad',
                    render: c => c ? esc(c.nombre) : '<span style="color:var(--d-muted)">—</span>'
                },
                {
                    data: null, className: 'text-center',
                    render: d => '<div class="action-cell">'
                        + '<a href="/admin/personas/' + d.id + '/ver" class="btn btn-action btn-action-view" title="Ver detalle"><i class="ri-eye-fill"></i></a>'
                        + '<button class="btn btn-action btn-action-edit btn-accion-editar"'
                        + ' data-row=\'' + JSON.stringify(d).replace(/'/g, '&#39;') + '\''
                        + ' title="Editar persona"><i class="ri-pencil-fill"></i></button>'
                        + '<button class="btn btn-action btn-action-delete btn-accion-eliminar"'
                        + ' data-id="' + d.id + '"'
                        + ' data-nombre="' + esc((d.nombres || '') + ' ' + (d.apellido_paterno || '')) + '"'
                        + ' title="Eliminar persona"><i class="ri-delete-bin-fill"></i></button>'
                        + '</div>'
                }
            ],
            language: {
                processing: 'Procesando...', search: 'Buscar:', zeroRecords: 'No se encontraron registros',
                emptyTable: 'No hay datos disponibles',
                paginate: { first: 'Primero', previous: 'Anterior', next: 'Siguiente', last: 'Último' }
            },
            order: [[1, 'asc']],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
            pageLength: 10,
            drawCallback: function () {
                $('#stat-total').text(this.api().page.info().recordsTotal);
            }
        });
    }

    /* ── EVENTOS ── */
    function bindEvents() {
        $('#btn-nuevo').on('click', function () { resetFormCrear(); openModal('modalCrear'); });

        $('#formCrear').on('submit', function (e) { e.preventDefault(); guardar(); });
        $('#formEditar').on('submit', function (e) { e.preventDefault(); actualizar(); });

        $(document).on('click', '.btn-accion-editar', function () {
            llenarFormEditar(JSON.parse($(this).attr('data-row')));
            openModal('modalEditar');
        });

        $(document).on('click', '.btn-accion-eliminar', function () {
            idEliminar = $(this).data('id');
            $('#nombreEliminar').text($(this).data('nombre'));
            openModal('modalEliminar');
        });

        $('#btnConfirmarEliminar').on('click', function () { if (idEliminar) eliminarPersona(idEliminar); });

        /* Validación tiempo real — Carnet */
        $('#carnetCrear').on('input', function () {
            clearTimeout(carnetTimer);
            const val = this.value.trim();
            if (!val) { return setError('carnetCrear','iconCarnetCrear','fbCarnetCrear','El carnet es obligatorio.'); }
            if (val.length < 3) { return setError('carnetCrear','iconCarnetCrear','fbCarnetCrear','Debe tener al menos 3 caracteres.'); }
            setChecking('carnetCrear','iconCarnetCrear','fbCarnetCrear');
            carnetTimer = setTimeout(function () { verificarCarnet('carnetCrear','iconCarnetCrear','fbCarnetCrear', null); }, 400);
        });
        $('#carnetEditar').on('input', function () {
            clearTimeout(carnetTimer);
            const val = this.value.trim();
            if (!val) { return setError('carnetEditar','iconCarnetEditar','fbCarnetEditar','El carnet es obligatorio.'); }
            if (val.length < 3) { return setError('carnetEditar','iconCarnetEditar','fbCarnetEditar','Debe tener al menos 3 caracteres.'); }
            setChecking('carnetEditar','iconCarnetEditar','fbCarnetEditar');
            carnetTimer = setTimeout(function () { verificarCarnet('carnetEditar','iconCarnetEditar','fbCarnetEditar', $('#idEditar').val()); }, 400);
        });

        /* Validación tiempo real — Nombres */
        $('#nombresCrear').on('input', function () { validarNombres('nombresCrear','iconNombresCrear','fbNombresCrear'); });
        $('#nombresEditar').on('input', function () { validarNombres('nombresEditar','iconNombresEditar','fbNombresEditar'); });

        /* Validación tiempo real — Apellidos */
        $('#apPaternoCrear, #apMaternoCrear').on('input', function () { validarApellidos('Crear'); });
        $('#apPaternoEditar, #apMaternoEditar').on('input', function () { validarApellidos('Editar'); });

        /* Validación tiempo real — Correo */
        $('#correoCrear').on('input', function () {
            clearTimeout(correoTimer);
            const val = this.value.trim();
            if (!val) { return resetField('correoCrear','iconCorreoCrear','fbCorreoCrear'); }
            if (!isEmail(val)) { return setError('correoCrear','iconCorreoCrear','fbCorreoCrear','Formato de correo inválido.'); }
            setChecking('correoCrear','iconCorreoCrear','fbCorreoCrear');
            correoTimer = setTimeout(function () { verificarCorreo('correoCrear','iconCorreoCrear','fbCorreoCrear', null); }, 400);
        });
        $('#correoEditar').on('input', function () {
            clearTimeout(correoTimer);
            const val = this.value.trim();
            if (!val) { return resetField('correoEditar','iconCorreoEditar','fbCorreoEditar'); }
            if (!isEmail(val)) { return setError('correoEditar','iconCorreoEditar','fbCorreoEditar','Formato de correo inválido.'); }
            setChecking('correoEditar','iconCorreoEditar','fbCorreoEditar');
            correoTimer = setTimeout(function () { verificarCorreo('correoEditar','iconCorreoEditar','fbCorreoEditar', $('#idEditar').val()); }, 400);
        });

        /* Cascada departamento → ciudad */
        $('#deptoCrear').on('change', function () { filtrarCiudades('Crear'); });
        $('#deptoEditar').on('change', function () { filtrarCiudades('Editar'); });

        /* Estudios — modal Editar */
        $('#btnGuardarEstudio').on('click', function () { modoEdicionE ? actualizarEstudio() : agregarEstudio(); });
        $('#btnCancelarEstudio').on('click', resetFormEstudio);
        $(document).on('click', '.btn-edit-estudio', function () {
            const ctx = $(this).closest('#estudiosActivoCrear').length ? 'Crear' : 'Editar';
            cargarEstudioEnForm(JSON.parse($(this).attr('data-estudio')), ctx);
        });
        $(document).on('click', '.btn-del-estudio', function () {
            const $wrap = $(this).closest('[id^="estudiosActivo"]');
            const ctx   = $wrap.attr('id') === 'estudiosActivoCrear' ? 'Crear' : 'Editar';
            const pid   = ctx === 'Crear' ? $('#idCrear').val() : $('#idEditar').val();
            const eid   = $(this).data('id');
            if (!confirm('¿Eliminar este estudio?')) return;
            $.ajax({ url: '/admin/personas/' + pid + '/estudios/' + eid, type: 'DELETE', data: { _token: CSRF } })
                .done(r  => { cargarEstudiosCtx(pid, ctx); tabla.ajax.reload(null, false); toast('success', r.message); })
                .fail(xhr => toast('error', xhr.responseJSON?.message || 'Error al eliminar.'));
        });

        /* Estudios — modal Crear */
        $('#btnGuardarEstudioCrear').on('click', function () { modoEdicionC ? actualizarEstudioCrear() : agregarEstudioCrear(); });
        $('#btnCancelarEstudioCrear').on('click', resetFormEstudioCrear);

        document.getElementById('modalCrear').addEventListener('hidden.bs.modal', resetFormCrear);
        document.getElementById('modalEditar').addEventListener('hidden.bs.modal', function () {
            resetFormEstudio();
            $('#deptoEditar').val('');
            $('#ciudadEditar').find('option:not(:first)').remove().end().prop('disabled', true).find('option:first').text('— Seleccione depto. —');
        });
    }

    /* ── VERIFICAR CARNET (AJAX) ── */
    function verificarCarnet(inputId, iconId, fbId, personaId) {
        const val = document.getElementById(inputId).value.trim();
        $.post('<?php echo e(route("admin.personas.verificarCarnet")); ?>', { _token: CSRF, carnet: val, id: personaId || '' }, function (r) {
            if (r.existe) setError(inputId, iconId, fbId, 'Este carnet ya está registrado.');
            else          setOk(inputId, iconId, fbId, 'Carnet disponible');
        }).fail(function () { resetField(inputId, iconId, fbId); });
    }

    /* ── VERIFICAR CORREO (AJAX) ── */
    function verificarCorreo(inputId, iconId, fbId, personaId) {
        const val = document.getElementById(inputId).value.trim();
        $.post('<?php echo e(route("admin.personas.verificarCorreo")); ?>', { _token: CSRF, correo: val, id: personaId || '' }, function (r) {
            if (r.existe) setError(inputId, iconId, fbId, 'Este correo ya está registrado.');
            else          setOk(inputId, iconId, fbId, 'Correo disponible');
        }).fail(function () { resetField(inputId, iconId, fbId); });
    }

    /* ── GUARDAR PERSONA ── */
    function guardar() {
        // Bloquear re-envío si ya se guardó
        if ($('#idCrear').val()) return;

        const okC  = validarCarnetSync('carnetCrear','iconCarnetCrear','fbCarnetCrear');
        const okN  = validarNombres('nombresCrear','iconNombresCrear','fbNombresCrear');
        const okAp = validarApellidos('Crear');
        if (!okC || !okN || !okAp) return;
        if (document.getElementById('carnetCrear').classList.contains('is-invalid')) return;
        if (document.getElementById('correoCrear').classList.contains('is-invalid')) return;

        const hasFoto = document.getElementById('fotografiaCrear').files.length > 0;
        const payload = buildPayload('Crear', hasFoto);
        
        setBtnLoading('#btnGuardar', true, 'Guardando…');
        
        if (hasFoto) {
            $.ajax({
                url: '<?php echo e(route("admin.personas.guardar")); ?>',
                type: 'POST',
                data: payload,
                processData: false,
                contentType: false
            })
            .done(function (r) {
                tabla.ajax.reload(null, false);
                $('#idCrear').val(r.data.id);
                $('#estudiosLockCrear').hide();
                $('#estudiosActivoCrear').show();
                $('#btnGuardar').hide();
                $('#btnFinalizarCrear').show();
                renderTablaEstudiosCtx(r.data.estudios || [], r.data.id, 'Crear');
                toast('success', r.message + ' Ahora puedes agregar estudios.');
            })
            .fail(function (xhr) { handleAjaxError(xhr, 'Crear'); })
            .always(function () { setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar Persona'); });
        } else {
            $.post('<?php echo e(route("admin.personas.guardar")); ?>', payload)
                .done(function (r) {
                    tabla.ajax.reload(null, false);
                    $('#idCrear').val(r.data.id);
                    $('#estudiosLockCrear').hide();
                    $('#estudiosActivoCrear').show();
                    $('#btnGuardar').hide();
                    $('#btnFinalizarCrear').show();
                    renderTablaEstudiosCtx(r.data.estudios || [], r.data.id, 'Crear');
                    toast('success', r.message + ' Ahora puedes agregar estudios.');
                })
                .fail(function (xhr) { handleAjaxError(xhr, 'Crear'); })
                .always(function () { setBtnLoading('#btnGuardar', false, '<i class="ri-save-line"></i> Guardar Persona'); });
        }
    }

    function actualizar() {
        const okC  = validarCarnetSync('carnetEditar','iconCarnetEditar','fbCarnetEditar');
        const okN  = validarNombres('nombresEditar','iconNombresEditar','fbNombresEditar');
        const okAp = validarApellidos('Editar');
        if (!okC || !okN || !okAp) return;
        if (document.getElementById('carnetEditar').classList.contains('is-invalid')) return;
        if (document.getElementById('correoEditar').classList.contains('is-invalid')) return;

        const id = $('#idEditar').val();
        const hasFoto = document.getElementById('fotografiaEditar').files.length > 0;
        const payload = buildPayload('Editar', hasFoto);
        
        setBtnLoading('#btnActualizar', true, 'Actualizando…');
        
        if (hasFoto) {
            payload.append('_method', 'PUT');
            $.ajax({ url: '/admin/personas/' + id, type: 'POST', data: payload, processData: false, contentType: false })
                .done(function (r) { closeModal('modalEditar'); tabla.ajax.reload(null, false); toast('success', r.message); })
                .fail(function (xhr) { handleAjaxError(xhr, 'Editar'); })
                .always(function () { setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'); });
        } else {
            $.ajax({ url: '/admin/personas/' + id, type: 'PUT', data: payload })
                .done(function (r) { closeModal('modalEditar'); tabla.ajax.reload(null, false); toast('success', r.message); })
                .fail(function (xhr) { handleAjaxError(xhr, 'Editar'); })
                .always(function () { setBtnLoading('#btnActualizar', false, '<i class="ri-refresh-line"></i> Actualizar'); });
        }
    }

    function eliminarPersona(id) {
        setBtnLoading('#btnConfirmarEliminar', true, '<span class="spinner-border spinner-border-sm"></span> Eliminando…');
        $.ajax({ url: '/admin/personas/' + id, type: 'DELETE', data: { _token: CSRF } })
            .done(function (r)  { closeModal('modalEliminar'); tabla.ajax.reload(null, false); toast('success', r.message); })
            .fail(function (xhr){ toast(xhr.status === 400 ? 'warning' : 'error', xhr.responseJSON?.message || 'Error al eliminar.'); })
            .always(function () { setBtnLoading('#btnConfirmarEliminar', false, '<i class="ri-delete-bin-line"></i> Eliminar'); idEliminar = null; });
    }

    function buildPayload(ctx, includeFile = false) {
        if (includeFile) {
            const formData = new FormData();
            formData.append('_token', CSRF);
            formData.append('carnet', $('#carnet' + ctx).val().trim());
            formData.append('expedido', $('#expedido' + ctx).val().trim());
            formData.append('nombres', $('#nombres' + ctx).val().trim());
            formData.append('apellido_paterno', $('#apPaterno' + ctx).val().trim());
            formData.append('apellido_materno', $('#apMaterno' + ctx).val().trim());
            formData.append('sexo', $('#sexo' + ctx).val());
            formData.append('estado_civil', $('#estadoCivil' + ctx).val());
            formData.append('fecha_nacimiento', $('#fechaNacimiento' + ctx).val());
            formData.append('correo', $('#correo' + ctx).val().trim());
            formData.append('celular', $('#celular' + ctx).val().trim());
            formData.append('telefono', $('#telefono' + ctx).val().trim());
            formData.append('direccion', $('#direccion' + ctx).val().trim());
            formData.append('ciudade_id', $('#ciudad' + ctx).val());
            
            const fotoInput = document.getElementById('fotografia' + ctx);
            if (fotoInput && fotoInput.files.length > 0) {
                formData.append('fotografia', fotoInput.files[0]);
            }
            return formData;
        }
        return {
            _token:           CSRF,
            carnet:           $('#carnet'          + ctx).val().trim(),
            expedido:         $('#expedido'         + ctx).val().trim(),
            nombres:          $('#nombres'          + ctx).val().trim(),
            apellido_paterno: $('#apPaterno'        + ctx).val().trim(),
            apellido_materno: $('#apMaterno'        + ctx).val().trim(),
            sexo:             $('#sexo'             + ctx).val(),
            estado_civil:     $('#estadoCivil'      + ctx).val(),
            fecha_nacimiento: $('#fechaNacimiento'  + ctx).val(),
            correo:           $('#correo'           + ctx).val().trim(),
            celular:          $('#celular'          + ctx).val().trim(),
            telefono:         $('#telefono'         + ctx).val().trim(),
            direccion:        $('#direccion'        + ctx).val().trim(),
            ciudade_id:       $('#ciudad'           + ctx).val(),
        };
    }

    /* ── ESTUDIOS ── */
    function cargarEstudiosCtx(pid, ctx) {
        $.getJSON('<?php echo e(route("admin.personas.listar")); ?>', function (r) {
            const p = r.data.find(function (x) { return x.id == pid; });
            renderTablaEstudiosCtx(p ? p.estudios : [], pid, ctx);
        });
    }

    function renderTablaEstudiosCtx(estudios, pid, ctx) {
        const tbodyId = ctx === 'Crear' ? '#bodyEstudiosCrear' : '#bodyEstudios';
        const tbody   = $(tbodyId);
        if (!estudios || !estudios.length) {
            tbody.html('<tr><td colspan="6" class="estudios-empty"><i class="ri-inbox-line me-1"></i> Sin estudios registrados</td></tr>');
            return;
        }
        let html = '';
        estudios.forEach(function (e) {
            const grado    = e.grado_academico ? esc(e.grado_academico.nombre) : '<span style="color:var(--d-muted)">—</span>';
            const prof     = e.profesion        ? esc(e.profesion.nombre)       : '<span style="color:var(--d-muted)">—</span>';
            const univ     = e.universidad      ? esc(e.universidad.nombre)     : '<span style="color:var(--d-muted)">—</span>';
            const estado   = e.estado === 'Concluido'
                ? '<span class="badge-estado-concluido"><i class="ri-checkbox-circle-line"></i>Concluido</span>'
                : '<span class="badge-estado-desarrollo"><i class="ri-loader-line"></i>En Desarrollo</span>';
            const principal = e.principal
                ? '<span class="badge-principal"><i class="ri-star-fill"></i>Sí</span>'
                : '<span style="color:var(--d-muted);font-size:0.75rem;">—</span>';
            html += '<tr>'
                + '<td>' + grado + '</td><td>' + prof + '</td><td>' + univ + '</td>'
                + '<td class="text-center">' + estado + '</td>'
                + '<td class="text-center">' + principal + '</td>'
                + '<td class="text-center"><div class="action-cell">'
                +   '<button class="btn btn-action btn-action-edit btn-edit-estudio" data-estudio=\'' + JSON.stringify(e).replace(/'/g,'&#39;') + '\' title="Editar"><i class="ri-pencil-fill"></i></button>'
                +   '<button class="btn btn-action btn-action-delete btn-del-estudio" data-id="' + e.id + '" title="Eliminar"><i class="ri-delete-bin-fill"></i></button>'
                + '</div></td></tr>';
        });
        tbody.html(html);
    }

    /* Estudios — modal Editar */
    function agregarEstudio() {
        const pid = $('#idEditar').val();
        if (!$('#gradoEstudio').val()) {
            $('#fbGradoEstudio').addClass('error').html('<i class="ri-error-warning-line"></i> El grado académico es obligatorio.');
            return;
        }
        setBtnLoading('#btnGuardarEstudio', true, 'Guardando…');
        $.post('/admin/personas/' + pid + '/estudios', {
            _token: CSRF,
            grados_academico_id: $('#gradoEstudio').val(),
            profesione_id:       $('#profesionEstudio').val() || null,
            universidade_id:     $('#universidadEstudio').val() || null,
            estado:              $('#estadoEstudio').val(),
            principal:           $('#principalEstudio').is(':checked') ? 1 : 0,
        })
        .done(function (r) { cargarEstudiosCtx(pid, 'Editar'); tabla.ajax.reload(null, false); toast('success', r.message); resetFormEstudio(); })
        .fail(function (xhr) {
            const errs = xhr.responseJSON?.errors || {};
            if (errs.grados_academico_id) $('#fbGradoEstudio').addClass('error').html('<i class="ri-error-warning-line"></i>' + errs.grados_academico_id[0]);
            else toast('error', xhr.responseJSON?.message || 'Error al guardar.');
        })
        .always(function () { setBtnLoading('#btnGuardarEstudio', false, '<i class="ri-add-line"></i> <span id="labelBtnEstudio">Agregar</span>'); });
    }

    function actualizarEstudio() {
        const pid = $('#idEditar').val();
        const eid = $('#estudioIdEditar').val();
        if (!$('#gradoEstudio').val()) {
            $('#fbGradoEstudio').addClass('error').html('<i class="ri-error-warning-line"></i> El grado académico es obligatorio.');
            return;
        }
        setBtnLoading('#btnGuardarEstudio', true, 'Actualizando…');
        $.ajax({
            url: '/admin/personas/' + pid + '/estudios/' + eid, type: 'PUT',
            data: {
                _token: CSRF,
                grados_academico_id: $('#gradoEstudio').val(),
                profesione_id:       $('#profesionEstudio').val() || null,
                universidade_id:     $('#universidadEstudio').val() || null,
                estado:              $('#estadoEstudio').val(),
                principal:           $('#principalEstudio').is(':checked') ? 1 : 0,
            }
        })
        .done(function (r) { cargarEstudiosCtx(pid, 'Editar'); tabla.ajax.reload(null, false); toast('success', r.message); resetFormEstudio(); })
        .fail(function (xhr) { toast('error', xhr.responseJSON?.message || 'Error al actualizar.'); })
        .always(function () { setBtnLoading('#btnGuardarEstudio', false, '<i class="ri-add-line"></i> <span id="labelBtnEstudio">Agregar</span>'); });
    }

    function cargarEstudioEnForm(e, ctx) {
        if (ctx === 'Crear') {
            modoEdicionC = true;
            $('#estudioIdCrear').val(e.id);
            $('#gradoEstudioCrear').val(e.grados_academico_id);
            $('#profesionEstudioCrear').val(e.profesione_id || '');
            $('#universidadEstudioCrear').val(e.universidade_id || '');
            $('#estadoEstudioCrear').val(e.estado);
            $('#principalEstudioCrear').prop('checked', !!e.principal);
            document.getElementById('tituloFormEstudioCrear').innerHTML = '<i class="ri-edit-2-line" style="color:#fc7b04;"></i> Editar estudio';
            $('#labelBtnEstudioCrear').text('Actualizar');
            $('#btnCancelarEstudioCrear').show();
            $('#fbGradoEstudioCrear').removeClass('error').html('');
        } else {
            modoEdicionE = true;
            $('#estudioIdEditar').val(e.id);
            $('#gradoEstudio').val(e.grados_academico_id);
            $('#profesionEstudio').val(e.profesione_id || '');
            $('#universidadEstudio').val(e.universidade_id || '');
            $('#estadoEstudio').val(e.estado);
            $('#principalEstudio').prop('checked', !!e.principal);
            document.getElementById('tituloFormEstudio').innerHTML = '<i class="ri-edit-2-line" style="color:#fc7b04;"></i> Editar estudio';
            $('#labelBtnEstudio').text('Actualizar');
            $('#btnCancelarEstudio').show();
            $('#fbGradoEstudio').removeClass('error').html('');
        }
    }

    function resetFormEstudio() {
        modoEdicionE = false;
        $('#estudioIdEditar').val('');
        $('#gradoEstudio, #profesionEstudio, #universidadEstudio').val('');
        $('#estadoEstudio').val('Concluido');
        $('#principalEstudio').prop('checked', false);
        document.getElementById('tituloFormEstudio').innerHTML = '<i class="ri-add-circle-line" style="color:#fc7b04;"></i> Agregar nuevo estudio';
        $('#labelBtnEstudio').text('Agregar');
        $('#btnCancelarEstudio').hide();
        $('#fbGradoEstudio').removeClass('error').html('');
    }

    /* Estudios — modal Crear */
    function agregarEstudioCrear() {
        const pid = $('#idCrear').val();
        if (!$('#gradoEstudioCrear').val()) {
            $('#fbGradoEstudioCrear').addClass('error').html('<i class="ri-error-warning-line"></i> El grado académico es obligatorio.');
            return;
        }
        setBtnLoading('#btnGuardarEstudioCrear', true, 'Guardando…');
        $.post('/admin/personas/' + pid + '/estudios', {
            _token: CSRF,
            grados_academico_id: $('#gradoEstudioCrear').val(),
            profesione_id:       $('#profesionEstudioCrear').val() || null,
            universidade_id:     $('#universidadEstudioCrear').val() || null,
            estado:              $('#estadoEstudioCrear').val(),
            principal:           $('#principalEstudioCrear').is(':checked') ? 1 : 0,
        })
        .done(function (r) { cargarEstudiosCtx(pid, 'Crear'); tabla.ajax.reload(null, false); toast('success', r.message); resetFormEstudioCrear(); })
        .fail(function (xhr) {
            const errs = xhr.responseJSON?.errors || {};
            if (errs.grados_academico_id) $('#fbGradoEstudioCrear').addClass('error').html('<i class="ri-error-warning-line"></i>' + errs.grados_academico_id[0]);
            else toast('error', xhr.responseJSON?.message || 'Error al guardar.');
        })
        .always(function () { setBtnLoading('#btnGuardarEstudioCrear', false, '<i class="ri-add-line"></i> <span id="labelBtnEstudioCrear">Agregar</span>'); });
    }

    function actualizarEstudioCrear() {
        const pid = $('#idCrear').val();
        const eid = $('#estudioIdCrear').val();
        if (!$('#gradoEstudioCrear').val()) {
            $('#fbGradoEstudioCrear').addClass('error').html('<i class="ri-error-warning-line"></i> El grado académico es obligatorio.');
            return;
        }
        setBtnLoading('#btnGuardarEstudioCrear', true, 'Actualizando…');
        $.ajax({
            url: '/admin/personas/' + pid + '/estudios/' + eid, type: 'PUT',
            data: {
                _token: CSRF,
                grados_academico_id: $('#gradoEstudioCrear').val(),
                profesione_id:       $('#profesionEstudioCrear').val() || null,
                universidade_id:     $('#universidadEstudioCrear').val() || null,
                estado:              $('#estadoEstudioCrear').val(),
                principal:           $('#principalEstudioCrear').is(':checked') ? 1 : 0,
            }
        })
        .done(function (r) { cargarEstudiosCtx(pid, 'Crear'); tabla.ajax.reload(null, false); toast('success', r.message); resetFormEstudioCrear(); })
        .fail(function (xhr) { toast('error', xhr.responseJSON?.message || 'Error al actualizar.'); })
        .always(function () { setBtnLoading('#btnGuardarEstudioCrear', false, '<i class="ri-add-line"></i> <span id="labelBtnEstudioCrear">Agregar</span>'); });
    }

    function resetFormEstudioCrear() {
        modoEdicionC = false;
        $('#estudioIdCrear').val('');
        $('#gradoEstudioCrear, #profesionEstudioCrear, #universidadEstudioCrear').val('');
        $('#estadoEstudioCrear').val('Concluido');
        $('#principalEstudioCrear').prop('checked', false);
        document.getElementById('tituloFormEstudioCrear').innerHTML = '<i class="ri-add-circle-line" style="color:#fc7b04;"></i> Agregar nuevo estudio';
        $('#labelBtnEstudioCrear').text('Agregar');
        $('#btnCancelarEstudioCrear').hide();
        $('#fbGradoEstudioCrear').removeClass('error').html('');
    }

    /* ── VALIDACIÓN ── */
    function validarCarnetSync(inputId, iconId, fbId) {
        const val = document.getElementById(inputId).value.trim();
        if (!val)         return setError(inputId, iconId, fbId, 'El carnet es obligatorio.');
        if (val.length < 3) return setError(inputId, iconId, fbId, 'Debe tener al menos 3 caracteres.');
        return true;
    }

    function validarNombres(inputId, iconId, fbId) {
        const val = document.getElementById(inputId).value.trim();
        if (!val)           return setError(inputId, iconId, fbId, 'El nombre es obligatorio.');
        if (val.length < 2) return setError(inputId, iconId, fbId, 'Debe tener al menos 2 caracteres.');
        return setOk(inputId, iconId, fbId, 'Nombre válido');
    }

    function validarApellidos(ctx) {
        const pat = $('#apPaterno' + ctx).val().trim();
        const mat = $('#apMaterno' + ctx).val().trim();
        const fb  = document.getElementById('fbApellidos' + ctx);
        if (!pat && !mat) {
            fb.className = 'field-feedback error';
            fb.innerHTML = '<i class="ri-error-warning-line"></i> Debe registrar al menos un apellido (paterno o materno).';
            return false;
        }
        fb.className = 'field-feedback';
        fb.innerHTML = '';
        return true;
    }

    function isEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

    function setChecking(inputId, iconId, fbId) {
        const ic = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        document.getElementById(inputId).classList.remove('is-valid','is-invalid');
        if (ic) { ic.className = 'validation-icon'; ic.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:0.85rem;height:0.85rem;color:#fc7b04;"></span>'; }
        if (fb) { fb.className = 'field-feedback'; fb.innerHTML = ''; }
    }

    function setError(inputId, iconId, fbId, msg) {
        const i = document.getElementById(inputId);
        const ic = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        if (i)  { i.classList.remove('is-valid'); i.classList.add('is-invalid'); }
        if (ic) { ic.className = 'validation-icon invalid'; ic.innerHTML = '<i class="ri-close-circle-fill"></i>'; }
        if (fb) { fb.className = 'field-feedback error'; fb.innerHTML = '<i class="ri-error-warning-line"></i>' + msg; }
        return false;
    }

    function setOk(inputId, iconId, fbId, msg) {
        const i = document.getElementById(inputId);
        const ic = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        if (i)  { i.classList.remove('is-invalid'); i.classList.add('is-valid'); }
        if (ic) { ic.className = 'validation-icon valid'; ic.innerHTML = '<i class="ri-checkbox-circle-fill"></i>'; }
        if (fb) { fb.className = 'field-feedback success'; fb.innerHTML = '<i class="ri-check-line"></i>' + msg; }
        return true;
    }

    function resetField(inputId, iconId, fbId) {
        const i = document.getElementById(inputId);
        const ic = document.getElementById(iconId);
        const fb = document.getElementById(fbId);
        if (i)  i.classList.remove('is-valid','is-invalid');
        if (ic) { ic.className = 'validation-icon'; ic.innerHTML = ''; }
        if (fb) { fb.className = 'field-feedback'; fb.innerHTML = ''; }
    }

    /* ── HELPERS ── */
    function resetFormCrear() {
        document.getElementById('formCrear').reset();
        $('#idCrear').val('');
        // Limpiar cascada ciudad
        $('#deptoCrear').val('');
        $('#ciudadCrear').find('option:not(:first)').remove().end().prop('disabled', true).find('option:first').text('— Seleccione depto. —');
        // Limpiar validaciones
        ['carnetCrear','nombresCrear','correoCrear'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('is-valid','is-invalid');
        });
        ['iconCarnetCrear','iconNombresCrear','iconCorreoCrear'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) { el.className = 'validation-icon'; el.innerHTML = ''; }
        });
        ['fbCarnetCrear','fbNombresCrear','fbCorreoCrear','fbApellidosCrear'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) { el.className = 'field-feedback'; el.innerHTML = ''; }
        });
        // Resetear sección estudios
        $('#estudiosLockCrear').show();
        $('#estudiosActivoCrear').hide();
        $('#btnGuardar').show();
        $('#btnFinalizarCrear').hide();
        $('#bodyEstudiosCrear').html('<tr><td colspan="6" class="estudios-empty"><i class="ri-inbox-line me-1"></i> Sin estudios registrados</td></tr>');
        resetFormEstudioCrear();
    }

    function llenarFormEditar(d) {
        $('#idEditar').val(d.id);
        $('#carnetEditar').val(d.carnet);
        $('#expedidoEditar').val(d.expedido || '');
        $('#nombresEditar').val(d.nombres);
        $('#apPaternoEditar').val(d.apellido_paterno || '');
        $('#apMaternoEditar').val(d.apellido_materno || '');
        $('#sexoEditar').val(d.sexo || '');
        $('#estadoCivilEditar').val(d.estado_civil || '');
        $('#fechaNacimientoEditar').val(d.fecha_nacimiento || '');
        $('#correoEditar').val(d.correo || '');
        $('#celularEditar').val(d.celular || '');
        $('#telefonoEditar').val(d.telefono || '');
        $('#direccionEditar').val(d.direccion || '');
        
        // Fotografía
        var fotoUrl = '<?php echo e(url("images/personas")); ?>/' + d.fotografia;
        if (d.fotografia) {
            $('#previewFotografiaEditar').attr('src', fotoUrl);
        } else {
            $('#previewFotografiaEditar').attr('src', '<?php echo e(URL::asset("build/images/user-placeholder.jpg")); ?>');
        }
        // Cascada departamento → ciudad
        var deptoId = d.ciudad ? d.ciudad.departamento_id : null;
        if (!deptoId && d.ciudade_id) {
            var found = todasCiudades.find(function (c) { return c.id == d.ciudade_id; });
            if (found) deptoId = found.departamento_id;
        }
        $('#deptoEditar').val(deptoId || '');
        filtrarCiudades('Editar');
        $('#ciudadEditar').val(d.ciudade_id || '');
        // Limpiar validaciones visuales
        ['carnetEditar','nombresEditar','correoEditar'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('is-valid','is-invalid');
        });
        ['iconCarnetEditar','iconNombresEditar','iconCorreoEditar'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) { el.className = 'validation-icon'; el.innerHTML = ''; }
        });
        ['fbCarnetEditar','fbNombresEditar','fbCorreoEditar','fbApellidosEditar'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) { el.className = 'field-feedback'; el.innerHTML = ''; }
        });
        resetFormEstudio();
        cargarEstudiosCtx(d.id, 'Editar');
    }

    function handleAjaxError(xhr, ctx) {
        if (xhr.status === 422) {
            const errs = xhr.responseJSON?.errors || {};
            let handled = false;
            if (errs.carnet)    { setError('carnet'  + ctx, 'iconCarnet'  + ctx, 'fbCarnet'  + ctx, errs.carnet[0]);  handled = true; }
            if (errs.nombres)   { setError('nombres' + ctx, 'iconNombres' + ctx, 'fbNombres' + ctx, errs.nombres[0]); handled = true; }
            if (errs.correo)    { setError('correo'  + ctx, 'iconCorreo'  + ctx, 'fbCorreo'  + ctx, errs.correo[0]);  handled = true; }
            if (errs.apellidos) {
                const fb = document.getElementById('fbApellidos' + ctx);
                if (fb) { fb.className = 'field-feedback error'; fb.innerHTML = '<i class="ri-error-warning-line"></i>' + errs.apellidos[0]; }
                handled = true;
            }
            if (!handled) {
                const firstErr = Object.values(errs)[0];
                toast('error', firstErr ? firstErr[0] : (xhr.responseJSON?.message || 'Error de validación.'));
            }
        } else {
            toast('error', xhr.responseJSON?.message || 'Ocurrió un error. Intente nuevamente.');
        }
    }

    function setBtnLoading(sel, loading, labelHtml) {
        const btn = document.querySelector(sel);
        if (!btn) return;
        btn.disabled = loading;
        if (loading) btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' + labelHtml;
        else         btn.innerHTML = labelHtml;
    }

    function openModal(id)  { new bootstrap.Modal(document.getElementById(id)).show(); }
    function closeModal(id) { const m = bootstrap.Modal.getInstance(document.getElementById(id)); if (m) m.hide(); }

    function esc(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function getToastContainer() {
        let c = document.getElementById('toastContainer');
        if (c && c.parentElement !== document.body) document.body.appendChild(c);
        return c;
    }

    function toast(tipo, mensaje) {
        const iconMap = { success:'ri-check-double-line', error:'ri-close-circle-line', warning:'ri-alert-line' };
        const el = document.createElement('div');
        el.className = 'toast-notify ' + tipo;
        el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo]||'ri-information-line') + '"></i></div>'
            + '<div class="toast-body-text"><span>' + mensaje + '</span></div>'
            + '<button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';
        const container = getToastContainer();
        const upd = () => { container.style.top = Math.max(20, window.scrollY + 20) + 'px'; };
        container.style.transition = 'top 0.3s ease';
        upd();
        if (!container._scrollListenerAttached) {
            container._scrollListenerAttached = true;
            let t; window.addEventListener('scroll', () => { clearTimeout(t); t = setTimeout(upd, 10); });
        }
        container.appendChild(el);
        el.querySelector('.toast-close').addEventListener('click', () => removeToast(el));
        setTimeout(() => removeToast(el), 4500);
    }

    function removeToast(el) { el.classList.add('hiding'); el.addEventListener('animationend', () => el.remove(), { once: true }); }

    $(document).ready(init);
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/personas/index.blade.php ENDPATH**/ ?>