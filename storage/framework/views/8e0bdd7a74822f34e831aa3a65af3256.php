<!-- Modal: Crear Plan/Concepto -->
<div class="modal fade" id="modalCrearPc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title"><i class="ri-add-circle-line"></i> Nueva Configuración de Precio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formCrearPc" novalidate>
                    <div class="mb-3"><label class="form-label" style="font-weight:600;">Plan de Pago <span
                                style="color:#ef4444;">*</span></label><select class="form-select" id="planesPagoCrear">
                            <option value="">— Seleccionar —</option>
                        </select>
                        <div id="badgePromocionCrear"
                            style="display:none;margin-top:0.5rem;padding:0.6rem 0.75rem;border-radius:8px;background:rgba(252,123,4,0.08);border:1px solid rgba(252,123,4,0.2);">
                            <div class="d-flex align-items-center gap-2"><i class="ri-gift-line"
                                    style="color:#fc7b04;font-size:1.1rem;"></i>
                                <div>
                                    <div style="font-size:0.8rem;font-weight:700;color:#fc7b04;">Plan de Promoción</div>
                                    <div style="font-size:0.72rem;color:var(--d-muted);margin-top:0.15rem;"
                                        id="rangoPromocionCrear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="bannerSinPrincipal" style="display:none;margin-bottom:1rem;" class="p-3 rounded">
                        <div class="d-flex align-items-center gap-2"><i class="ri-alert-line"
                                style="color:#f59e0b;font-size:1.25rem;"></i><span
                                style="font-size:0.85rem;font-weight:600;color:#f59e0b;">No se puede registrar: no
                                existe un plan principal configurado con precio base.</span></div>
                    </div>
                    <div id="tablaConceptosContainer" style="display:none;">
                        <div class="d-flex justify-content-between align-items-center mb-2"><label
                                class="form-label mb-0"
                                style="font-weight:600;font-size:0.85rem;">Conceptos</label><button type="button"
                                class="btn btn-sm btn-outline-primary" id="btnAddFilaConcepto"><i
                                    class="ri-add-line"></i> Agregar Concepto</button></div>
                        <div class="table-responsive" style="max-height:300px;overflow-y:auto;">
                            <table class="table table-sm align-middle mb-0" id="tablaFilasConceptos">
                                <thead style="position:sticky;top:0;background:var(--d-bg);z-index:1;">
                                    <tr>
                                        <th style="min-width:180px;">Concepto <span style="color:#ef4444;">*</span></th>
                                        <th style="width:80px;">Cuotas <span style="color:#ef4444;">*</span></th>
                                        <th style="width:110px;">P. Regular (Bs)</th>
                                        <th style="width:110px;" class="col-descuento" data-promocion="1">Descuento (Bs)
                                        </th>
                                        <th style="width:100px;" class="text-end">Pago (Bs)</th>
                                        <th style="width:40px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="filasConceptosBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-3 p-3 rounded" id="totalGeneralContainer"
                        style="display:none;background:rgba(252,123,4,0.08);border:1px solid rgba(252,123,4,0.2);">
                        <div class="d-flex justify-content-between align-items-center"><span
                                style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--d-title);"><i
                                    class="ri-money-dollar-circle-line" style="color:#fc7b04;"></i> Total a
                                Pagar</span><span style="font-size:1.1rem;font-weight:800;color:#fc7b04;"
                                id="totalGeneralCrear">Bs. 0.00</span></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i
                        class="ri-close-line"></i> Cancelar</button><button type="button"
                    class="btn btn-sm btn-success" id="btnGuardarPc" disabled><i class="ri-save-line"></i> Guardar
                    Todo</button></div>
        </div>
    </div>
</div>

<!-- Modal: Editar Plan/Concepto -->
<div class="modal fade" id="modalEditarPc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title"><i class="ri-edit-2-line"></i> Editar Configuración</h5><button type="button"
                    class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idEditarPc">
                <div class="mb-3"><label class="form-label" style="font-weight:600;">Plan — Concepto</label>
                    <div id="labelPcEditar"
                        style="font-size:0.85rem;color:var(--d-muted);padding:0.5rem;background:var(--d-bg);border-radius:8px;border:1px solid var(--d-card-border);">
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label" style="font-weight:600;">N° Cuotas <span
                                style="color:#ef4444;">*</span></label><input type="number" class="form-control"
                            id="nCuotasEditar" value="1" min="1" max="60"></div>
                    <div class="col-md-4"><label class="form-label" style="font-weight:600;">Precio Regular
                            (Bs)</label><input type="number" class="form-control" id="precioRegularEditar"
                            value="0.00" min="0" step="0.01"></div>
                    <div class="col-md-4"><label class="form-label" style="font-weight:600;">Descuento
                            (Bs)</label><input type="number" class="form-control" id="descuentoBsEditar"
                            value="0.00" min="0" step="0.01"></div>
                </div>
                <div class="mt-3 p-3 rounded"
                    style="background:rgba(252,123,4,0.08);border:1px solid rgba(252,123,4,0.2);">
                    <div class="d-flex justify-content-between align-items-center"><span
                            style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--d-title);"><i
                                class="ri-money-dollar-circle-line" style="color:#fc7b04;"></i> Total a
                            Pagar</span><input type="text" class="form-control" id="pagoBsEditar" value="0.00"
                            readonly
                            style="width:120px;text-align:right;font-weight:700;color:#fc7b04;background:transparent;border:none;padding:0;">
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary"
                    data-bs-dismiss="modal"><i class="ri-close-line"></i> Cancelar</button><button type="button"
                    class="btn btn-sm btn-success" id="btnActualizarPc"><i class="ri-refresh-line"></i>
                    Actualizar</button></div>
        </div>
    </div>
</div>

<!-- Modal: Eliminar Plan/Concepto -->
<div class="modal fade" id="modalEliminarPc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title"><i class="ri-error-warning-line"></i> Confirmar Eliminación</h5><button
                    type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="delete-warning-box">
                    <div class="delete-icon-ring"><i class="ri-delete-bin-5-line"></i></div>
                    <p class="delete-msg-primary">¿Eliminar configuración?</p>
                    <p class="delete-msg-name"><strong id="nombreEliminarPc"></strong></p>
                    <p class="delete-msg-warn"><i class="ri-information-line"></i> Esta acción es permanente y no
                        puede deshacerse.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-3"><button type="button"
                    class="btn btn-sm btn-modal-cancel px-4" data-bs-dismiss="modal"><i class="ri-close-line"></i>
                    Cancelar</button><button type="button" class="btn btn-sm btn-danger-modal px-4"
                    id="btnConfirmarEliminarPc"><i class="ri-delete-bin-line"></i> Eliminar</button></div>
        </div>
    </div>
</div>

<!-- Modal: Editar Plan Completo -->
<div class="modal fade" id="modalEditarPlanCompleto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title"><i class="ri-pencil-fill"></i> Editar Plan de Pago</h5><button type="button"
                    class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label" style="font-weight:600;">Plan de Pago</label><input
                        type="text" class="form-control" id="editPlanNombre" readonly
                        style="background:var(--d-input-bg);opacity:0.7;"><input type="hidden"
                        id="editPlanId"><input type="hidden" id="editPlanEsPromocion"></div>
                <div id="editBadgePromocion"
                    style="display:none;margin-bottom:0.75rem;padding:0.6rem 0.75rem;border-radius:8px;background:rgba(252,123,4,0.08);border:1px solid rgba(252,123,4,0.2);">
                    <div class="d-flex align-items-center gap-2"><i class="ri-gift-line"
                            style="color:#fc7b04;font-size:1.1rem;"></i>
                        <div>
                            <div style="font-size:0.8rem;font-weight:700;color:#fc7b04;">Plan de Promoción</div>
                            <div style="font-size:0.72rem;color:var(--d-muted);margin-top:0.15rem;"
                                id="editRangoPromocion"></div>
                        </div>
                    </div>
                </div>
                <div id="editPlanFechasBox" style="display:none;">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label" style="font-weight:600;">Fecha Inicio
                                Promoción</label><input type="date" class="form-control" id="editPlanFechaInicio">
                        </div>
                        <div class="col-md-6"><label class="form-label" style="font-weight:600;">Fecha Fin
                                Promoción</label><input type="date" class="form-control" id="editPlanFechaFin">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2"><label class="form-label mb-0"
                        style="font-weight:600;font-size:0.85rem;">Conceptos</label></div>
                <div class="table-responsive" style="max-height:300px;overflow-y:auto;">
                    <table class="table table-sm align-middle mb-0" id="tablaEditarPlanConceptos">
                        <thead style="position:sticky;top:0;background:var(--d-bg);z-index:1;">
                            <tr>
                                <th style="min-width:150px;">Concepto</th>
                                <th style="width:80px;" class="text-center">Cuotas</th>
                                <th style="width:110px;" class="text-end">P. Regular (Bs)</th>
                                <th style="width:110px;" class="text-end col-edit-descuento">Descuento (Bs)</th>
                                <th style="width:100px;" class="text-end">Pago (Bs)</th>
                            </tr>
                        </thead>
                        <tbody id="editarPlanConceptosBody"></tbody>
                    </table>
                </div>
                <div class="mt-3 p-3 rounded" id="editTotalPlanContainer"
                    style="background:rgba(252,123,4,0.08);border:1px solid rgba(252,123,4,0.2);">
                    <div class="d-flex justify-content-between align-items-center"><span
                            style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--d-title);"><i
                                class="ri-money-dollar-circle-line" style="color:#fc7b04;"></i> Total del
                            Plan</span><span style="font-size:1.1rem;font-weight:800;color:#fc7b04;"
                            id="editTotalPlanValor">Bs. 0.00</span></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary"
                    data-bs-dismiss="modal"><i class="ri-close-line"></i> Cancelar</button><button type="button"
                    class="btn btn-sm btn-success" id="btnGuardarEditarPlan"><i class="ri-save-line"></i> Guardar
                    Cambios</button></div>
        </div>
    </div>
</div>

<!-- Modal: Detalle de Horario -->
<div class="modal fade" id="modalDetalleHorario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:820px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden;">

            
            <div class="modal-header py-3 px-4" style="background:linear-gradient(135deg,#1e1b4b,#312e81,#4338ca);border:none;">
                <div class="d-flex align-items-center gap-3 flex-grow-1 min-width-0">
                    <div id="detHorarioColorBar" style="width:8px;height:36px;border-radius:8px;flex-shrink:0;"></div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold text-white" style="font-size:.95rem;">
                            <i class="ri-calendar-event-line me-2"></i>Detalle de Sesión
                        </h5>
                        <div style="font-size:.72rem;color:rgba(255,255,255,.65);margin-top:.1rem;">Información y gestión de la sesión académica</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            
            <div class="modal-body p-0">
                <div class="row g-0">

                    
                    <div class="col-md-5 d-flex flex-column" style="padding:1.35rem 1.5rem;border-right:1px solid #e9ecef;background:#f8fafc;">

                        
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="hdf-icon" style="background:rgba(252,123,4,.1);color:#fc7b04;flex-shrink:0;width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <i class="ri-book-open-line" style="font-size:.9rem;"></i>
                            </div>
                            <div style="min-width:0;">
                                <div class="hdf-label" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#94a3b8;margin-bottom:.2rem;">Módulo Académico</div>
                                <div class="fw-bold" id="detHorarioModulo" style="font-size:.9rem;line-height:1.3;color:#1e293b;"></div>
                            </div>
                        </div>

                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="d-flex align-items-start gap-2">
                                    <div style="width:28px;height:28px;border-radius:7px;background:rgba(41,156,219,.1);color:#299cdb;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ri-calendar-line" style="font-size:.82rem;"></i>
                                    </div>
                                    <div>
                                        <div class="hdf-label" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#94a3b8;margin-bottom:.15rem;">Fecha</div>
                                        <div class="fw-semibold" id="detHorarioFecha" style="font-size:.83rem;color:#334155;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-start gap-2">
                                    <div style="width:28px;height:28px;border-radius:7px;background:rgba(34,197,94,.1);color:#22c55e;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ri-time-line" style="font-size:.82rem;"></i>
                                    </div>
                                    <div>
                                        <div class="hdf-label" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#94a3b8;margin-bottom:.15rem;">Horario</div>
                                        <div class="fw-semibold" id="detHorarioHora" style="font-size:.83rem;color:#334155;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="d-flex align-items-start gap-2 mb-3">
                            <div style="width:28px;height:28px;border-radius:7px;background:rgba(99,102,241,.1);color:#6366f1;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-user-star-line" style="font-size:.82rem;"></i>
                            </div>
                            <div style="min-width:0;">
                                <div class="hdf-label" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#94a3b8;margin-bottom:.15rem;">Docente Encargado</div>
                                <div class="fw-semibold" id="detHorarioDocente" style="font-size:.83rem;color:#334155;"></div>
                            </div>
                        </div>

                        
                        <div class="d-flex align-items-start gap-2 mb-3">
                            <div style="width:28px;height:28px;border-radius:7px;background:rgba(245,158,11,.1);color:#f59e0b;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-flag-line" style="font-size:.82rem;"></i>
                            </div>
                            <div>
                                <div class="hdf-label" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#94a3b8;margin-bottom:.15rem;">Estado Actual</div>
                                <div id="detHorarioEstado"></div>
                            </div>
                        </div>

                        
                        <div id="detHorarioReprogramadoInfo" style="display:none;" class="alert alert-info py-2 px-3 border-0 rounded-3 mb-0">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-information-line text-primary" style="font-size:.9rem;"></i>
                                <div style="font-size:.78rem;font-weight:500;" id="detHorarioReprogramadoMsg"></div>
                            </div>
                        </div>

                    </div>

                    
                    <div class="col-md-7 d-flex flex-column" style="padding:1.35rem 1.5rem;gap:.85rem;">

                        
                        <div class="p-3 rounded-3" style="background:#f1f5f9;border:1px dashed #cbd5e1;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div style="width:28px;height:28px;border-radius:7px;background:rgba(168,85,247,.1);color:#a855f7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ri-shield-user-line" style="font-size:.82rem;"></i>
                                </div>
                                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#64748b;">Personal de Seguimiento</div>
                            </div>
                            <select class="form-select form-select-sm" id="detHorarioTrabajador"
                                style="font-size:.85rem;font-weight:600;color:var(--d-body);border-radius:8px;">
                                <option value="">— Sin asignar —</option>
                            </select>
                        </div>

                        
                        <div id="detHorarioEnlaceWrap" class="p-3 rounded-3" style="display:none;background:rgba(99,102,241,.05);border:1px solid rgba(99,102,241,.2);">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div style="width:28px;height:28px;border-radius:7px;background:rgba(99,102,241,.1);color:#6366f1;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ri-video-chat-line" style="font-size:.82rem;"></i>
                                </div>
                                <div>
                                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#4338ca;">Sesión Virtual</div>
                                    <div class="fw-semibold" id="detHorarioEnlaceNombre" style="font-size:.78rem;color:#6366f1;"></div>
                                </div>
                            </div>
                            <button type="button" id="detHorarioEnlaceUrl"
                                style="display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .9rem;background:linear-gradient(135deg,#4f46e5,#6366f1);border:none;border-radius:8px;color:#fff;font-size:.78rem;font-weight:600;cursor:pointer;box-shadow:0 3px 10px rgba(99,102,241,.3);transition:all .2s;">
                                <i class="ri-external-link-line"></i>Unirse a la sesión
                            </button>
                        </div>

                        
                        <div id="detHorarioGrabacionWrap" class="p-3 rounded-3 flex-grow-1" style="background:rgba(16,185,129,.05);border:1px solid rgba(16,185,129,.2);">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div style="width:28px;height:28px;border-radius:7px;background:rgba(16,185,129,.1);color:#10b981;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ri-video-line" style="font-size:.82rem;"></i>
                                </div>
                                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#059669;">Grabación de la Sesión</div>
                            </div>
                            
                            <div id="detGrabacionVacia" style="display:none;">
                                <span style="font-size:.8rem;color:#94a3b8;font-style:italic;">Sin grabación registrada</span>
                            </div>
                            
                            <div id="detGrabacionConEnlace" style="display:none;">
                                <button type="button" id="btnAbrirGrabacion"
                                    style="display:inline-flex;align-items:center;gap:.4rem;padding:.3rem .8rem;background:linear-gradient(135deg,#059669,#10b981);border:none;border-radius:8px;color:#fff;font-size:.78rem;font-weight:600;cursor:pointer;box-shadow:0 3px 10px rgba(16,185,129,.3);transition:all .2s;">
                                    <i class="ri-play-circle-line"></i>Ver grabación
                                </button>
                            </div>
                            
                            <div id="detGrabacionForm" style="display:none;margin-top:.5rem;">
                                <div class="d-flex gap-2">
                                    <input type="text" id="detGrabacionInput" class="form-control form-control-sm"
                                        placeholder="https://drive.google.com/..." maxlength="500"
                                        style="border-radius:8px;font-size:.8rem;">
                                    <button type="button" id="btnGuardarGrabacion"
                                        style="flex-shrink:0;padding:.3rem .75rem;background:#10b981;border:none;border-radius:8px;color:#fff;font-size:.78rem;font-weight:600;cursor:pointer;transition:all .2s;">
                                        <i class="ri-save-line"></i>
                                    </button>
                                    <button type="button" id="btnCancelarGrabacion"
                                        style="flex-shrink:0;padding:.3rem .6rem;background:#e2e8f0;border:none;border-radius:8px;color:#64748b;font-size:.78rem;cursor:pointer;">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <button type="button" id="btnEditarGrabacion"
                                style="margin-top:.5rem;display:inline-flex;align-items:center;gap:.3rem;background:none;border:1px dashed rgba(16,185,129,.4);border-radius:8px;color:#10b981;font-size:.74rem;font-weight:600;padding:.2rem .6rem;cursor:pointer;transition:all .15s;">
                                <i class="ri-edit-line"></i><span id="btnEditarGrabacionText">Agregar enlace</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            
            <div class="modal-footer py-2 px-4" style="background:#f8fafc;border-top:1px solid #e9ecef;">
                <button type="button" class="btn btn-sm btn-link text-muted text-decoration-none fw-semibold" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
                <div class="ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btnCambiarEstadoHorario">
                        <i class="ri-refresh-line me-1"></i>Cambiar Estado
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal: Asignar Horario -->
<div class="modal fade" id="modalAsignarHorario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" style="max-width:680px;">
        <div class="modal-content" style="border:none;border-radius:18px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.2);">

            
            <div class="modal-header" style="background:linear-gradient(135deg,#391b04,#7c3c00,<?php echo e($brandColor); ?>);color:white;padding:1.25rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3 flex-grow-1 min-width-0">
                    <div style="position:relative;width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-calendar-schedule-line" style="font-size:1.35rem;"></i>
                        <div id="asigHorarioColorBar" style="position:absolute;bottom:-3px;right:-3px;width:13px;height:13px;border-radius:50%;border:2px solid rgba(255,255,255,.6);"></div>
                    </div>
                    <div style="min-width:0;">
                        <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;color:#fff;">Asignar Horarios</h5>
                        <div id="asigHorarioModuloNombre" style="font-size:.73rem;opacity:.85;margin-top:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:340px;"></div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            
            <div class="modal-body" style="padding:0;">

                
                <div style="padding:1.1rem 1.5rem;border-bottom:1px solid #f1f5f9;">
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.85rem;">
                        <div style="width:26px;height:26px;border-radius:7px;background:rgba(252,123,4,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="ri-bar-chart-2-line" style="color:<?php echo e($brandColor); ?>;font-size:.85rem;"></i>
                        </div>
                        <span style="font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:<?php echo e($brandColor); ?>;">Resumen de Sesiones</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="session-stat-card" style="background:rgba(252,123,4,.06);border-color:rgba(252,123,4,.2);">
                                <div class="ssc-value" style="color:#fc7b04;" id="asigTotalSesiones">0</div>
                                <div class="ssc-label">Total</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="session-stat-card" style="background:rgba(34,197,94,.06);border-color:rgba(34,197,94,.2);">
                                <div class="ssc-value" style="color:#22c55e;" id="asigRegistradas">0</div>
                                <div class="ssc-label">Registradas</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="session-stat-card" style="background:rgba(239,68,68,.06);border-color:rgba(239,68,68,.2);">
                                <div class="ssc-value" style="color:#ef4444;" id="asigPendientes">0</div>
                                <div class="ssc-label">Pendientes</div>
                            </div>
                        </div>
                    </div>

                    
                    <div id="asigHorarioBannerPeriodo" style="display:none;margin-top:.85rem;padding:.6rem .9rem;background:rgba(252,123,4,.05);border:1px dashed rgba(252,123,4,.3);border-radius:10px;">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <i class="ri-calendar-2-line" style="color:<?php echo e($brandColor); ?>;font-size:.85rem;flex-shrink:0;"></i>
                            <span style="font-size:.77rem;color:#475569;font-weight:500;">Período del módulo:</span>
                            <span style="display:inline-flex;align-items:center;gap:.25rem;font-size:.77rem;font-weight:600;color:#1e293b;background:white;padding:.12rem .55rem;border-radius:20px;border:1px solid #e2e8f0;">
                                <i class="ri-play-circle-line" style="color:<?php echo e($brandColor); ?>;font-size:.75rem;"></i>
                                <span id="asigBannerFechaInicio"></span>
                            </span>
                            <i class="ri-arrow-right-line" style="color:#cbd5e1;font-size:.75rem;"></i>
                            <span style="display:inline-flex;align-items:center;gap:.25rem;font-size:.77rem;font-weight:600;color:#1e293b;background:white;padding:.12rem .55rem;border-radius:20px;border:1px solid #e2e8f0;">
                                <i class="ri-stop-circle-line" style="color:#22c55e;font-size:.75rem;"></i>
                                <span id="asigBannerFechaFin"></span>
                            </span>
                            
                            <span id="asigHorarioFechaInicio" style="display:none;"></span>
                            <span id="asigHorarioFechaFin" style="display:none;"></span>
                            <div id="asigHorarioPeriodoWrap" style="display:none!important;"></div>
                        </div>
                    </div>
                </div>

                
                <div style="padding:1.1rem 1.5rem;border-bottom:1px solid #f1f5f9;">
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.85rem;">
                        <div style="width:26px;height:26px;border-radius:7px;background:rgba(99,102,241,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="ri-list-check" style="color:#6366f1;font-size:.85rem;"></i>
                        </div>
                        <span style="font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#6366f1;">Sesiones a Registrar</span>
                    </div>
                    <div id="sesionesRowsContainer"></div>
                    
                    <button type="button" id="btnAddAllSesiones" style="display:none;border-radius:8px;font-size:.8rem;" class="btn btn-sm btn-success">
                        <i class="ri-stack-line"></i> Todas
                    </button>
                </div>

                
                <div style="padding:1.1rem 1.5rem;background:rgba(16,185,129,.02);">
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.85rem;">
                        <div style="width:26px;height:26px;border-radius:7px;background:rgba(16,185,129,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="ri-shield-user-line" style="color:#10b981;font-size:.85rem;"></i>
                        </div>
                        <span style="font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#059669;">Trabajador Asignado</span>
                        <span style="font-size:.68rem;color:#94a3b8;">(para todas las sesiones)</span>
                    </div>
                    <div>
                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Buscar por nombre o carnet</label>
                        <div class="input-group" style="border-radius:9px;overflow:hidden;">
                            <input type="text" class="form-control" id="asigTrabajadorSearch"
                                placeholder="Ej: Juan Pérez o 1234567..."
                                style="border-radius:9px 0 0 9px;font-size:.85rem;">
                            <button type="button" class="btn" id="btnBuscarTrabajador"
                                style="background:<?php echo e($brandColor); ?>;border:none;color:#fff;padding:.4rem .85rem;border-radius:0 9px 9px 0;font-size:.85rem;">
                                <i class="ri-search-line"></i>
                            </button>
                        </div>
                        <input type="hidden" id="asigTrabajadorId">
                        <div id="asigTrabajadorPreview" style="display:none;margin-top:.6rem;padding:.55rem .8rem;background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.2);border-radius:9px;">
                            <small class="fw-semibold" id="asigTrabajadorNombre"></small>
                        </div>
                    </div>
                </div>

            </div>

            
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;gap:.5rem;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-sm" id="btnGuardarAsignarHorario"
                    style="background:linear-gradient(135deg,#391b04,<?php echo e($brandColor); ?>);border:none;color:white;border-radius:8px;font-size:.82rem;padding:.4rem 1.15rem;font-weight:700;box-shadow:0 4px 12px rgba(252,123,4,.3);transition:all .2s;">
                    <i class="ri-check-line me-1"></i>Guardar Todas
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Modal: Editar Módulo -->
<div class="modal fade" id="modalEditarModulo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">

            
            <div class="modal-header" style="background:linear-gradient(135deg,#391b04,#7c3c00,<?php echo e($brandColor); ?>);color:white;padding:1.25rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3 flex-grow-1 min-width-0">
                    <div style="position:relative;width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-pencil-ruler-2-line" style="font-size:1.35rem;"></i>
                        <div id="editModuloColorBar" style="position:absolute;bottom:-3px;right:-3px;width:13px;height:13px;border-radius:50%;border:2px solid rgba(255,255,255,.6);"></div>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;color:#fff;">Editar Módulo</h5>
                        <div style="font-size:.73rem;opacity:.8;margin-top:.15rem;">Nombre, fechas, color y docente del módulo</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            
            <div class="modal-body" style="padding:1.5rem;">
                <input type="hidden" id="editModuloId">

                
                <div class="mb-3">
                    <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#878a99;display:flex;align-items:center;gap:.35rem;margin-bottom:.5rem;">
                        <i class="ri-text" style="color:<?php echo e($brandColor); ?>;"></i> Nombre del Módulo <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" class="form-control" id="editModuloNombre" maxlength="200" placeholder="Ingrese el nombre del módulo" style="border-radius:10px;">
                </div>

                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#878a99;display:flex;align-items:center;gap:.35rem;margin-bottom:.5rem;">
                            <i class="ri-play-circle-line" style="color:<?php echo e($brandColor); ?>;"></i> Fecha Inicio
                        </label>
                        <input type="date" class="form-control" id="editModuloFechaInicio" style="border-radius:10px;">
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#878a99;display:flex;align-items:center;gap:.35rem;margin-bottom:.5rem;">
                            <i class="ri-stop-circle-line" style="color:#22c55e;"></i> Fecha Fin
                        </label>
                        <input type="date" class="form-control" id="editModuloFechaFin" style="border-radius:10px;">
                    </div>
                </div>

                
                <div class="mb-4">
                    <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#878a99;display:flex;align-items:center;gap:.35rem;margin-bottom:.5rem;">
                        <i class="ri-palette-line" style="color:<?php echo e($brandColor); ?>;"></i> Color Identificador
                    </label>
                    <div class="d-flex align-items-center gap-3">
                        <input type="color" class="form-control form-control-color" id="editModuloColor" value="#6366f1" style="width:52px;height:44px;border-radius:10px;cursor:pointer;flex-shrink:0;">
                        <div id="editModuloColorPreview" class="color-preview-box" style="background:#6366f1;flex:1;height:44px;border-radius:10px;margin-left:0;"></div>
                    </div>
                </div>

                
                <div style="margin-top:1rem;padding:1rem;background:rgba(99,102,241,.04);border:1px solid rgba(99,102,241,.15);border-radius:12px;">
                    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.85rem;">
                        <div style="width:30px;height:30px;border-radius:8px;background:rgba(99,102,241,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="ri-user-star-line" style="color:#6366f1;font-size:.95rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#6366f1;">Docente Asignado</div>
                            <div style="font-size:.68rem;color:#94a3b8;margin-top:.05rem;">Busca por carnet de identidad</div>
                        </div>
                    </div>

                    
                    <div id="editModuloDocentePreview" style="display:none;margin-bottom:.75rem;padding:.6rem .85rem;background:#fff;border:1.5px solid rgba(99,102,241,.3);border-radius:10px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:.5rem;">
                            <div style="display:flex;align-items:center;gap:.6rem;min-width:0;">
                                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ri-user-line" style="color:#fff;font-size:.8rem;"></i>
                                </div>
                                <div style="min-width:0;">
                                    <div style="font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.05rem;">Asignado</div>
                                    <div id="editModuloDocenteNombre" style="font-size:.85rem;font-weight:700;color:#3730a3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></div>
                                </div>
                            </div>
                            <button type="button" id="btnLimpiarDocenteModulo" title="Quitar docente"
                                style="flex-shrink:0;background:rgba(239,68,68,.1);border:none;border-radius:6px;padding:.25rem .5rem;color:#ef4444;cursor:pointer;font-size:.75rem;transition:all .15s;">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                    </div>

                    
                    <input type="hidden" id="editModuloDocenteId">
                    <div style="display:flex;gap:.5rem;align-items:stretch;">
                        <div style="position:relative;flex:1;">
                            <i class="ri-id-card-line" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:#a5b4fc;font-size:.9rem;pointer-events:none;"></i>
                            <input type="text" id="editModuloDocenteCarnet" maxlength="20"
                                placeholder="Ingresa el carnet de identidad..."
                                style="width:100%;padding:.5rem .75rem .5rem 2.1rem;border:1.5px solid rgba(99,102,241,.25);border-radius:10px;font-size:.85rem;color:#334155;background:#f8fafc;transition:border-color .2s;outline:none;"
                                onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='rgba(99,102,241,.25)'">
                        </div>
                        <button type="button" id="btnBuscarDocenteModulo"
                            style="flex-shrink:0;padding:.5rem 1rem;background:linear-gradient(135deg,#4f46e5,#6366f1);border:none;border-radius:10px;color:#fff;font-size:.82rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.35rem;box-shadow:0 3px 8px rgba(99,102,241,.3);transition:all .2s;white-space:nowrap;">
                            <i class="ri-search-line"></i> Buscar
                        </button>
                    </div>
                    <div style="margin-top:.5rem;font-size:.71rem;color:#94a3b8;display:flex;align-items:center;gap:.3rem;">
                        <i class="ri-information-line"></i> Si el docente no existe, podrás registrarlo inmediatamente.
                    </div>
                </div>

            </div>

            
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;gap:.5rem;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-sm" id="btnGuardarEditarModulo"
                    style="background:linear-gradient(135deg,#391b04,<?php echo e($brandColor); ?>);border:none;color:white;border-radius:8px;font-size:.82rem;padding:.4rem 1.15rem;font-weight:700;box-shadow:0 4px 12px rgba(252,123,4,.3);transition:all .2s;">
                    <i class="ri-check-line me-1"></i>Guardar Cambios
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Modal: Confirmar Registro de Docente -->
<div class="modal fade" id="modalConfirmarRegistroDocente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 50px rgba(0,0,0,.2);">

            
            <div class="modal-header" style="background:linear-gradient(135deg,#391b04,#7c3c00,<?php echo e($brandColor); ?>);color:white;padding:1.1rem 1.4rem;border:none;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:40px;height:40px;background:rgba(255,255,255,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-user-search-line" style="font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-size:.95rem;font-weight:700;color:#fff;">Docente no encontrado</h5>
                        <div style="font-size:.7rem;opacity:.75;margin-top:.1rem;">¿Deseas registrarlo ahora?</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>

            
            <div class="modal-body" style="padding:1.35rem 1.5rem;">
                <div style="display:flex;align-items:flex-start;gap:.85rem;padding:.9rem 1rem;background:rgba(252,123,4,.06);border:1px dashed rgba(252,123,4,.3);border-radius:12px;margin-bottom:1rem;">
                    <div style="width:36px;height:36px;background:rgba(252,123,4,.12);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.1rem;">
                        <i class="ri-alert-line" style="color:#fc7b04;font-size:1rem;"></i>
                    </div>
                    <p class="mb-0" id="confirmarRegistroDocenteMsg" style="font-size:.84rem;color:#92400e;line-height:1.55;font-weight:500;"></p>
                </div>
                <p style="font-size:.85rem;font-weight:600;color:#334155;margin:0;">
                    <i class="ri-question-line" style="color:#6366f1;margin-right:.35rem;"></i>
                    ¿Deseas registrar este docente y asignarlo al módulo?
                </p>
            </div>

            
            <div class="modal-footer" style="padding:.9rem 1.4rem;gap:.5rem;border-top:1px solid #f1f5f9;background:#f8fafc;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" id="btnConfirmarRegistroDocente"
                    style="padding:.4rem 1.1rem;background:linear-gradient(135deg,#391b04,<?php echo e($brandColor); ?>);border:none;border-radius:8px;color:#fff;font-size:.82rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;box-shadow:0 3px 10px rgba(252,123,4,.3);transition:all .2s;">
                    <i class="ri-user-add-line"></i> Registrar Docente
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Modal: Registro de Docente -->
<div class="modal fade" id="modalRegistroDocente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" style="max-width:700px;">
        <div class="modal-content" style="border:none;border-radius:18px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.2);">

            
            <div class="modal-header" style="background:linear-gradient(135deg,#391b04,#7c3c00,<?php echo e($brandColor); ?>);color:white;padding:1.25rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3 flex-grow-1">
                    <div style="position:relative;width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-user-add-line" style="font-size:1.35rem;"></i>
                        <div id="registroDocenteColorBar" style="position:absolute;bottom:-3px;right:-3px;width:13px;height:13px;border-radius:50%;border:2px solid rgba(255,255,255,.6);"></div>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;color:#fff;">Registrar Nuevo Docente</h5>
                        <div style="font-size:.73rem;opacity:.8;margin-top:.15rem;">Completa los datos para crear el perfil del docente</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            
            <div class="modal-body" style="padding:0;">
                <input type="hidden" id="registroDocentePersonaId">

                
                <div style="padding:1.2rem 1.5rem;border-bottom:1px solid #f1f5f9;">
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.85rem;">
                        <div style="width:26px;height:26px;border-radius:7px;background:rgba(99,102,241,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="ri-id-card-line" style="color:#6366f1;font-size:.85rem;"></i>
                        </div>
                        <span style="font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#6366f1;">Identificación</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:flex;align-items:center;gap:.3rem;margin-bottom:.4rem;">
                                Carnet <i class="ri-lock-line" style="color:#a5b4fc;font-size:.8rem;" title="Generado desde la búsqueda"></i>
                            </label>
                            <div style="position:relative;">
                                <input type="text" class="form-control" id="registroDocenteCarnet" maxlength="20" readonly
                                    style="border-radius:9px;background:#f1f5f9;color:#334155;font-weight:700;border-color:#e2e8f0;cursor:default;padding-right:2rem;">
                                <i class="ri-lock-2-line" style="position:absolute;right:.6rem;top:50%;transform:translateY(-50%);color:#a5b4fc;font-size:.8rem;pointer-events:none;"></i>
                            </div>
                            <div class="invalid-feedback" id="registroDocenteCarnetError"></div>
                        </div>
                        <div class="col-md-3">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Nombres <span style="color:#ef4444;">*</span></label>
                            <input type="text" class="form-control" id="registroDocenteNombres" maxlength="100" placeholder="Nombres completos" style="border-radius:9px;">
                            <div class="invalid-feedback" id="registroDocenteNombresError"></div>
                        </div>
                        <div class="col-md-3">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Ap. Paterno <span style="color:#ef4444;">*</span></label>
                            <input type="text" class="form-control" id="registroDocenteApellidoPaterno" maxlength="100" placeholder="Apellido paterno" style="border-radius:9px;">
                            <div class="invalid-feedback" id="registroDocenteApellidoPaternoError"></div>
                        </div>
                        <div class="col-md-3">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Ap. Materno</label>
                            <input type="text" class="form-control" id="registroDocenteApellidoMaterno" maxlength="100" placeholder="Apellido materno" style="border-radius:9px;">
                        </div>
                    </div>
                </div>

                
                <div style="padding:1.2rem 1.5rem;border-bottom:1px solid #f1f5f9;background:rgba(16,185,129,.02);">
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.85rem;">
                        <div style="width:26px;height:26px;border-radius:7px;background:rgba(16,185,129,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="ri-user-heart-line" style="color:#10b981;font-size:.85rem;"></i>
                        </div>
                        <span style="font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#059669;">Datos Personales</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="registroDocenteFechaNacimiento" style="border-radius:9px;">
                        </div>
                        <div class="col-md-4">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Sexo</label>
                            <select class="form-select" id="registroDocenteSexo" style="border-radius:9px;">
                                <option value="">— Seleccionar —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Estado Civil</label>
                            <select class="form-select" id="registroDocenteEstadoCivil" style="border-radius:9px;">
                                <option value="">— Seleccionar —</option>
                                <option value="Soltero/a">Soltero/a</option>
                                <option value="Casado/a">Casado/a</option>
                                <option value="Divorciado/a">Divorciado/a</option>
                                <option value="Viudo/a">Viudo/a</option>
                                <option value="Unión Libre">Unión Libre</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:flex;align-items:center;gap:.35rem;margin-bottom:.4rem;">
                                <i class="ri-map-pin-line" style="color:#10b981;font-size:.8rem;"></i> Departamento
                            </label>
                            <select class="form-select" id="registroDocenteDepartamento" style="border-radius:9px;">
                                <option value="">— Seleccionar —</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:flex;align-items:center;gap:.35rem;margin-bottom:.4rem;">
                                <i class="ri-building-line" style="color:#10b981;font-size:.8rem;"></i> Ciudad
                            </label>
                            <select class="form-select" id="registroDocenteCiudad" style="border-radius:9px;" disabled>
                                <option value="">— Primero elige departamento —</option>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div style="padding:1.2rem 1.5rem;border-bottom:1px solid #f1f5f9;background:rgba(59,130,246,.02);">
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.85rem;">
                        <div style="width:26px;height:26px;border-radius:7px;background:rgba(59,130,246,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="ri-phone-line" style="color:#3b82f6;font-size:.85rem;"></i>
                        </div>
                        <span style="font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#2563eb;">Contacto</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Correo Electrónico</label>
                            <div style="position:relative;">
                                <i class="ri-mail-line" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.9rem;pointer-events:none;"></i>
                                <input type="email" class="form-control" id="registroDocenteCorreo" maxlength="150" placeholder="correo@ejemplo.com" style="border-radius:9px;padding-left:2.1rem;">
                            </div>
                            <div class="invalid-feedback" id="registroDocenteCorreoError"></div>
                        </div>
                        <div class="col-md-4">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Celular</label>
                            <div style="position:relative;">
                                <i class="ri-smartphone-line" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.9rem;pointer-events:none;"></i>
                                <input type="text" class="form-control" id="registroDocenteCelular" maxlength="20" placeholder="+591 70000000" style="border-radius:9px;padding-left:2.1rem;">
                            </div>
                            <div class="invalid-feedback" id="registroDocenteCelularError"></div>
                        </div>
                        <div class="col-md-3">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Teléfono</label>
                            <div style="position:relative;">
                                <i class="ri-phone-line" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.9rem;pointer-events:none;"></i>
                                <input type="text" class="form-control" id="registroDocenteTelefono" maxlength="20" placeholder="2000000" style="border-radius:9px;padding-left:2.1rem;">
                            </div>
                        </div>
                    </div>
                </div>

                
                <div style="padding:1.2rem 1.5rem;background:rgba(99,102,241,.02);">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.85rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;">
                            <div style="width:26px;height:26px;border-radius:7px;background:rgba(99,102,241,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-book-open-line" style="color:#6366f1;font-size:.85rem;"></i>
                            </div>
                            <span style="font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#6366f1;">Estudios Académicos</span>
                            <span style="font-size:.68rem;color:#94a3b8;">(opcional)</span>
                        </div>
                        <button type="button" id="btnAddEstudioRow"
                            style="padding:.35rem .85rem;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.25);border-radius:8px;color:#6366f1;font-size:.78rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;transition:all .15s;">
                            <i class="ri-add-circle-line"></i> Agregar Estudio
                        </button>
                    </div>
                    <div id="estudiosEmptyMsg" style="text-align:center;padding:.9rem;border:1.5px dashed rgba(99,102,241,.2);border-radius:10px;color:#94a3b8;font-size:.8rem;margin-bottom:.5rem;">
                        <i class="ri-graduation-cap-line" style="font-size:1.3rem;display:block;margin-bottom:.3rem;opacity:.4;"></i>
                        Sin estudios agregados — pulsa <strong style="color:#6366f1;">Agregar Estudio</strong> para incluir formación académica
                    </div>
                    <div id="estudiosRowsContainer"></div>
                </div>

            </div>

            
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;gap:.5rem;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" id="btnRegistrarYAsignarDocente"
                    style="padding:.4rem 1.15rem;background:linear-gradient(135deg,#391b04,<?php echo e($brandColor); ?>);border:none;border-radius:8px;color:#fff;font-size:.82rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;box-shadow:0 4px 12px rgba(252,123,4,.3);transition:all .2s;">
                    <i class="ri-user-check-line"></i> Registrar y Asignar
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Modal: Cambiar Estado de Horario -->
<div class="modal fade" id="modalCambiarEstadoHorario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 50px rgba(0,0,0,.18);">
            <div class="modal-header modal-header-gradient" style="padding:1rem 1.25rem;">
                <h5 class="modal-title"><i class="ri-flag-line"></i> Cambiar Estado de Sesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" style="padding:1.25rem;">
                <div class="mb-3">
                    <label class="form-label" style="font-weight:600;font-size:.82rem;">Nuevo Estado <span style="color:#ef4444;">*</span></label>
                    <select class="form-select" id="nuevoEstadoHorario" style="border-radius:10px;">
                        <option value="Confirmado">Confirmado</option>
                        <option value="Desarrollado">Desarrollado</option>
                        <option value="Postergado">Postergado</option>
                    </select>
                </div>

                
                <div class="mb-3" id="boxNuevaFechaPostergado" style="display:none;">
                    <label class="form-label" style="font-weight:600;font-size:.82rem;">Nueva Fecha de Sesión <span style="color:#ef4444;">*</span></label>
                    <input type="date" class="form-control" id="nuevaFechaPostergado" style="border-radius:10px;">
                    <div class="form-text text-muted" style="font-size:.75rem;margin-top:.3rem;">Se creará una nueva sesión vinculada a la actual.</div>
                </div>

                
                <div id="boxEnlaceGrabacion" style="display:none;">
                    <div style="margin-bottom:.75rem;padding:.65rem .9rem;background:rgba(16,185,129,.06);border:1px dashed rgba(16,185,129,.3);border-radius:10px;display:flex;align-items:flex-start;gap:.5rem;">
                        <i class="ri-video-line" style="color:#10b981;font-size:.95rem;flex-shrink:0;margin-top:.05rem;"></i>
                        <p class="mb-0" style="font-size:.75rem;color:#064e3b;line-height:1.55;">Sesión marcada como <strong>Desarrollada</strong>. Puedes registrar el enlace de la grabación (opcional).</p>
                    </div>
                    <label class="form-label" style="font-weight:600;font-size:.82rem;">
                        <i class="ri-video-line me-1" style="color:#10b981;"></i>Enlace de Grabación
                        <span style="font-size:.72rem;font-weight:400;color:#94a3b8;">(opcional)</span>
                    </label>
                    <input type="text" class="form-control" id="inputEnlaceGrabacion"
                        placeholder="https://drive.google.com/..." maxlength="500" style="border-radius:10px;">
                </div>
            </div>
            <div class="modal-footer" style="padding:.9rem 1.25rem;gap:.5rem;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;">Cancelar</button>
                <button type="button" class="btn btn-sm btn-success" id="btnConfirmarCambiarEstado" style="border-radius:8px;font-weight:600;">
                    <i class="ri-check-line me-1"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Cambiar Estado de Módulo -->
<div class="modal fade" id="modalCambiarEstadoModulo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 50px rgba(0,0,0,.18);">
            <div class="modal-header modal-header-gradient" style="padding:1rem 1.25rem;">
                <h5 class="modal-title"><i class="ri-flag-line"></i> Cambiar Estado del Módulo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" style="padding:1.25rem;">
                <div class="mb-3 p-2 rounded-3" style="background:rgba(252,123,4,.06);border:1px dashed rgba(252,123,4,.2);">
                    <div style="font-size:.72rem;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.15rem;">Módulo</div>
                    <div id="cambiarEstadoModuloNombre" style="font-size:.88rem;font-weight:700;color:#1e293b;"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-weight:600;font-size:.82rem;">Nuevo Estado <span style="color:#ef4444;">*</span></label>
                    <select class="form-select" id="nuevoEstadoModulo" style="border-radius:10px;">
                        <option value="No Inició">No Inició</option>
                        <option value="En Desarrollo">En Desarrollo</option>
                        <option value="Concluido">Concluido</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="padding:.9rem 1.25rem;gap:.5rem;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;">Cancelar</button>
                <button type="button" class="btn btn-sm btn-success" id="btnConfirmarCambiarEstadoModulo" style="border-radius:8px;font-weight:600;">
                    <i class="ri-check-line me-1"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Plan de Pago del Participante -->
<div class="modal fade" id="modalPlanParticipante" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header"
                style="background: linear-gradient(135deg, #391b04 0%, #5c2d0a 50%, #c96004 100%); color: white;">
                <h5 class="modal-title"><i class="ri-money-dollar-circle-line"></i> Plan de Pagos - <span
                        id="planesParticipanteNombre"></span></h5><button type="button"
                    class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="planesParticipanteLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"><span
                            class="visually-hidden">Cargando...</span></div>
                    <p class="mt-2 text-muted">Cargando plan de pagos...</p>
                </div>
                <div id="planesParticipanteEmpty" class="text-center py-4" style="display: none;"><i
                        class="ri-inbox-line fs-1 text-muted"></i>
                    <p class="mt-2 text-muted">No hay información de pagos</p>
                </div>
                <div id="planesParticipanteContainer"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary"
                    data-bs-dismiss="modal"><i class="ri-close-line"></i> Cerrar</button></div>
        </div>
    </div>
</div>

<!-- Modal: Cambiar de Pre-Inscrito a Inscrito -->
<div class="modal fade" id="modalCambiarAInscrito" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">

            
            <div class="modal-header" style="background:linear-gradient(135deg,#78350f,#b45309,#f59e0b);color:white;padding:1.25rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-user-star-line" style="font-size:1.4rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;letter-spacing:-.01em;">Cambiar a Inscrito</h5>
                        <div style="font-size:.73rem;opacity:.8;margin-top:.15rem;">Asignar plan de pago y completar la inscripción</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            
            <div class="modal-body" style="padding:0;">

                
                <div style="padding:1.25rem 1.5rem;background:linear-gradient(135deg,rgba(245,158,11,.07),rgba(251,191,36,.03));border-bottom:1px solid rgba(245,158,11,.18);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#fbbf24);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 14px rgba(245,158,11,.35);">
                            <i class="ri-user-line" style="font-size:1.3rem;color:white;"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:700;font-size:.95rem;color:#1e293b;line-height:1.3;" id="cambiarEstudianteNombre">—</div>
                            <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                <span style="display:inline-flex;align-items:center;gap:.25rem;font-size:.73rem;color:#64748b;background:white;padding:.2rem .65rem;border-radius:20px;border:1px solid #e2e8f0;font-weight:500;">
                                    <i class="ri-id-card-line" style="font-size:.75rem;"></i>
                                    <span id="cambiarEstudianteCi">—</span>
                                </span>
                                <span style="display:inline-flex;align-items:center;gap:.25rem;font-size:.72rem;color:#92400e;background:rgba(245,158,11,.12);padding:.2rem .65rem;border-radius:20px;border:1px solid rgba(245,158,11,.25);font-weight:600;">
                                    <i class="ri-user-add-line" style="font-size:.75rem;"></i> Pre-Inscrito
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div style="padding:1.25rem 1.5rem;">
                    <label class="form-label" style="font-weight:700;font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#475569;margin-bottom:.55rem;">
                        <i class="ri-file-list-3-line me-1" style="color:#f59e0b;"></i>Plan de Pago <span class="text-danger">*</span>
                    </label>
                    <select id="cambiarPlanPagoSelect" class="form-select" style="border-radius:10px;border:1.5px solid #e2e8f0;font-size:.85rem;padding:.65rem .875rem;color:#334155;">
                        <option value="">— Seleccionar plan de pago —</option>
                    </select>

                    
                    <div id="cambiarConceptosContainer" class="mt-3" style="display:none;">
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                            <div style="padding:.6rem 1rem;background:linear-gradient(135deg,rgba(245,158,11,.07),rgba(251,191,36,.03));border-bottom:1px solid rgba(245,158,11,.15);display:flex;align-items:center;gap:.5rem;">
                                <i class="ri-list-ordered" style="color:#f59e0b;font-size:.9rem;"></i>
                                <span style="font-size:.72rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.4px;">Detalle del Plan</span>
                            </div>
                            <div style="padding:.75rem 1rem;" id="cambiarConceptosList"></div>
                        </div>
                    </div>

                    
                    <div style="margin-top:1rem;padding:.75rem 1rem;background:rgba(245,158,11,.05);border:1px dashed rgba(245,158,11,.35);border-radius:10px;display:flex;align-items:flex-start;gap:.6rem;">
                        <i class="ri-information-line" style="color:#f59e0b;font-size:1rem;flex-shrink:0;margin-top:.05rem;"></i>
                        <p class="mb-0" style="font-size:.77rem;color:#64748b;line-height:1.6;">Al confirmar se crearán las cuotas correspondientes al plan seleccionado y se completará la inscripción del estudiante.</p>
                    </div>
                </div>
            </div>

            
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;gap:.5rem;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-sm" id="btnConfirmarCambiarAInscrito"
                    style="background:linear-gradient(135deg,#f59e0b,#fbbf24);border:none;color:#78350f;border-radius:8px;font-size:.82rem;padding:.4rem 1.15rem;font-weight:700;box-shadow:0 4px 12px rgba(245,158,11,.35);transition:all .2s;">
                    <i class="ri-user-check-line me-1"></i>Confirmar Inscripción
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Modal: Subir Comprobante de Pago (post-inscripción) -->
<div class="modal fade" id="modalComprobanteInscripcion" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width:620px;">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.2);">

            
            <div class="modal-header" style="background:linear-gradient(135deg,#391b04,#7c3c00,#c96004);color:white;padding:1.25rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-file-list-3-line" style="font-size:1.4rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;">Subir Comprobante de Pago</h5>
                        <div style="font-size:.73rem;opacity:.8;margin-top:.15rem;">Inscripción completada — adjunta el comprobante</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            
            <div class="modal-body" style="padding:1.5rem;">

                
                <div style="padding:.75rem 1rem;background:linear-gradient(135deg,rgba(16,185,129,.08),rgba(5,150,105,.04));border:1px solid rgba(16,185,129,.2);border-radius:10px;margin-bottom:1.25rem;display:flex;align-items:center;gap:.75rem;">
                    <div style="width:36px;height:36px;background:rgba(16,185,129,.12);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-checkbox-circle-line" style="color:#10b981;font-size:1.1rem;"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:.88rem;color:#064e3b;" id="compInscNombre">—</div>
                        <div style="font-size:.75rem;color:#059669;margin-top:.1rem;" id="compInscDetalle">Inscripción completada correctamente</div>
                    </div>
                </div>

                
                <div style="margin-bottom:1rem;">
                    <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#475569;display:block;margin-bottom:.5rem;">
                        <i class="ri-attachment-line me-1" style="color:#c96004;"></i>Archivo del comprobante <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="file" id="compInscArchivo" accept=".jpg,.jpeg,.png,.pdf"
                        style="border:1.5px solid #e2e8f0;border-radius:10px;padding:.5rem .875rem;font-size:.83rem;width:100%;background:#f8fafc;cursor:pointer;transition:border-color .2s;">
                    <div style="font-size:.71rem;color:#94a3b8;margin-top:.35rem;display:flex;align-items:center;gap:.3rem;">
                        <i class="ri-information-line"></i> JPG, PNG o PDF — máximo 5 MB
                    </div>
                </div>

                
                <div style="margin-bottom:1.25rem;">
                    <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#475569;display:block;margin-bottom:.5rem;">
                        <i class="ri-chat-3-line me-1" style="color:#c96004;"></i>Observaciones
                    </label>
                    <textarea id="compInscObservaciones" rows="2" placeholder="Opcional — banco, referencia, etc."
                        style="border:1.5px solid #e2e8f0;border-radius:10px;padding:.6rem .875rem;font-size:.83rem;width:100%;background:#f8fafc;resize:vertical;transition:border-color .2s;"></textarea>
                </div>

                
                <div>
                    <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#475569;display:block;margin-bottom:.5rem;">
                        <i class="ri-bank-card-line me-1" style="color:#c96004;"></i>Cuotas que cubre este comprobante <span style="color:#dc2626;">*</span>
                    </label>
                    <div id="compInscCuotasLoading" class="text-center py-3">
                        <div class="spinner-border spinner-border-sm" style="color:#c96004;"></div>
                        <span class="ms-2" style="font-size:.8rem;color:#64748b;">Cargando cuotas...</span>
                    </div>
                    <div id="compInscCuotasContainer" style="display:none;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;"></div>
                </div>

            </div>

            
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;gap:.5rem;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                    <i class="ri-skip-right-line me-1"></i>Omitir por ahora
                </button>
                <button type="button" id="btnEnviarComprobanteInscripcion"
                    style="display:inline-flex;align-items:center;gap:.4rem;padding:.4rem 1.15rem;background:linear-gradient(135deg,#9a4904,#c96004);border:none;border-radius:8px;color:white;font-size:.82rem;font-weight:600;cursor:pointer;box-shadow:0 4px 12px rgba(154,73,4,.3);transition:all .2s;">
                    <i class="ri-upload-cloud-line"></i>Enviar Comprobante
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Modal: Enviar Credenciales Moodle -->
<div class="modal fade" id="modalMatricularMoodle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">

            
            <div class="modal-header" style="background:linear-gradient(135deg,#391b04,#7c3c00,#c96004);color:#fff;padding:1.25rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3 flex-grow-1 min-width-0">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-send-plane-2-line" style="font-size:1.35rem;"></i>
                    </div>
                    <div style="min-width:0;">
                        <h5 class="modal-title mb-0" style="font-size:.98rem;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            Enviar Credenciales —
                            <span id="moodleModuloNombre" style="font-weight:400;color:#fff;"></span>
                        </h5>
                        <div style="font-size:.72rem;color:rgba(255,255,255,.75);margin-top:.15rem;">Envía los accesos Moodle a los estudiantes por WhatsApp</div>
                    </div>
                    <span id="moodleCursoNombre" class="ms-auto" style="display:none;background:rgba(255,255,255,.2);font-size:.72rem;font-weight:500;padding:.25rem .75rem;border-radius:20px;white-space:nowrap;flex-shrink:0;"></span>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal"></button>
            </div>

            
            <div class="modal-body" style="min-height:220px;padding:0;">

                
                <div id="moodleLoadingState" class="text-center" style="padding:3rem 1.5rem;">
                    <div style="width:56px;height:56px;margin:0 auto 1rem;background:rgba(154,73,4,.08);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <div class="spinner-border" style="color:#9a4904;width:1.7rem;height:1.7rem;" role="status"></div>
                    </div>
                    <div style="color:#64748b;font-size:.88rem;font-weight:500;">Consultando estado en Moodle...</div>
                </div>

                
                <div id="moodleSinCursoState" style="display:none;padding:1.5rem;">
                    <div style="padding:1rem 1.25rem;background:rgba(245,158,11,.07);border:1px solid rgba(245,158,11,.25);border-radius:12px;margin-bottom:1.25rem;display:flex;align-items:flex-start;gap:.75rem;">
                        <div style="width:38px;height:38px;background:rgba(245,158,11,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.1rem;">
                            <i class="ri-alert-line" style="color:#d97706;font-size:1.1rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.9rem;color:#92400e;">Este módulo no tiene un curso en Moodle</div>
                            <div style="font-size:.8rem;color:#b45309;margin-top:.3rem;line-height:1.5;">Puedes crearlo directamente desde aquí para empezar a gestionar los accesos de los estudiantes.</div>
                        </div>
                    </div>
                    <div class="text-center py-2">
                        <button type="button" id="btnCrearCursoMoodle"
                            style="padding:.65rem 1.75rem;background:linear-gradient(135deg,#9a4904,#c96004);border:none;border-radius:10px;color:#fff;font-weight:600;font-size:.88rem;cursor:pointer;display:inline-flex;align-items:center;gap:.5rem;box-shadow:0 4px 12px rgba(154,73,4,.25);transition:all .2s;">
                            <i class="ri-graduation-cap-line"></i> Crear curso en Moodle
                        </button>
                        <div id="crearCursoMoodleMsg" class="mt-2" style="display:none;font-size:0.85rem;"></div>
                    </div>
                </div>

                
                <div id="moodleErrorState" style="display:none;padding:1.5rem;">
                    <div style="padding:1rem 1.25rem;background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.2);border-radius:12px;display:flex;align-items:center;gap:.75rem;">
                        <div style="width:38px;height:38px;background:rgba(239,68,68,.12);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="ri-close-circle-line" style="color:#ef4444;font-size:1.1rem;"></i>
                        </div>
                        <span id="moodleErrorMsg" style="font-size:.88rem;font-weight:500;color:#dc2626;">Error al conectar con Moodle.</span>
                    </div>
                </div>

                
                <div id="moodleListaState" style="display:none;">

                    
                    <div id="moodleCursoInfo" style="display:none;margin:1.25rem 1.5rem 0;padding:.65rem 1rem;background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.2);border-radius:10px;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-graduation-cap-line" style="color:#10b981;font-size:.95rem;"></i>
                            <span id="moodleCursoNombreBody" style="font-size:.82rem;font-weight:600;color:#065f46;"></span>
                        </div>
                    </div>

                    
                    <div style="padding:.85rem 1.5rem .0rem;margin-top:.85rem;">
                        <div style="display:flex;gap:0;border-bottom:2px solid #e2e8f0;">
                            <button type="button" id="tabBtnCredenciales"
                                style="padding:.5rem 1.1rem;border:none;border-bottom:2.5px solid #9a4904;background:none;font-size:.8rem;font-weight:700;color:#9a4904;cursor:pointer;margin-bottom:-2px;display:inline-flex;align-items:center;gap:.35rem;transition:all .15s;">
                                <i class="ri-key-2-line"></i> Credenciales Moodle
                            </button>
                            <button type="button" id="tabBtnInfoModulo"
                                style="padding:.5rem 1.1rem;border:none;border-bottom:2.5px solid transparent;background:none;font-size:.8rem;font-weight:600;color:#94a3b8;cursor:pointer;margin-bottom:-2px;display:inline-flex;align-items:center;gap:.35rem;transition:all .15s;">
                                <i class="ri-calendar-schedule-line"></i> Info del Módulo
                            </button>
                        </div>
                    </div>

                    
                    <div id="moodleTabCredenciales">
                        
                        <div style="padding:.75rem 1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;border-bottom:1px solid #f1f5f9;">
                            <div class="d-flex gap-2">
                                <button type="button" id="btnSeleccionarTodosMoodle"
                                    style="border:1px solid #e2e8f0;background:#f8fafc;color:#475569;border-radius:8px;font-size:.77rem;padding:.3rem .75rem;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;transition:background .15s;">
                                    <i class="ri-checkbox-multiple-line"></i>Todos
                                </button>
                                <button type="button" id="btnDeseleccionarTodosMoodle"
                                    style="border:1px solid #e2e8f0;background:#f8fafc;color:#475569;border-radius:8px;font-size:.77rem;padding:.3rem .75rem;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;transition:background .15s;">
                                    <i class="ri-checkbox-blank-line"></i>Ninguno
                                </button>
                            </div>
                            <span id="moodleContadorSeleccion" style="font-size:.77rem;color:#64748b;font-weight:500;"></span>
                        </div>
                        
                        <div class="table-responsive" style="max-height:300px;overflow-y:auto;">
                            <table style="width:100%;border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                                        <th style="width:36px;padding:.7rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;"></th>
                                        <th style="padding:.7rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;">Estudiante</th>
                                        <th style="padding:.7rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;">Usuario Moodle</th>
                                        <th style="padding:.7rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;">Contraseña Inicial</th>
                                        <th style="padding:.7rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;text-align:center;width:60px;">WA</th>
                                    </tr>
                                </thead>
                                <tbody id="moodleEstudiantesBody"></tbody>
                            </table>
                        </div>
                        
                        <div style="margin:.75rem 1.5rem 1rem;padding:.6rem .9rem;background:rgba(154,73,4,.05);border:1px dashed rgba(154,73,4,.2);border-radius:8px;display:flex;align-items:flex-start;gap:.5rem;">
                            <i class="ri-information-line" style="color:#9a4904;font-size:.9rem;flex-shrink:0;margin-top:.1rem;"></i>
                            <p class="mb-0" style="font-size:.74rem;color:#64748b;line-height:1.55;">
                                La <strong>contraseña mostrada es la inicial</strong>. Si el estudiante la cambió, use <em>"¿Olvidé mi contraseña?"</em> en Moodle.
                                Estudiantes <strong>sin cuenta</strong> deben matricularse primero desde la pestaña <em>Plataforma</em>.
                            </p>
                        </div>
                    </div>

                    
                    <div id="moodleTabInfoModulo" style="display:none;">

                        
                        <div style="padding:.85rem 1.5rem .5rem;">
                            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;margin-bottom:.5rem;display:flex;align-items:center;gap:.35rem;">
                                <i class="ri-eye-line" style="color:#6366f1;"></i> Vista previa del mensaje
                            </div>
                            <div id="moodleInfoMsgPreview"
                                style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:.85rem 1rem;font-size:.78rem;line-height:1.65;color:#166534;white-space:pre-wrap;max-height:200px;overflow-y:auto;font-family:monospace;">
                            </div>
                        </div>

                        
                        <div style="padding:.6rem 1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;border-bottom:1px solid #f1f5f9;border-top:1px solid #f1f5f9;">
                            <div class="d-flex gap-2">
                                <button type="button" id="btnSeleccionarTodosInfo"
                                    style="border:1px solid #e2e8f0;background:#f8fafc;color:#475569;border-radius:8px;font-size:.77rem;padding:.3rem .75rem;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;">
                                    <i class="ri-checkbox-multiple-line"></i>Todos
                                </button>
                                <button type="button" id="btnDeseleccionarTodosInfo"
                                    style="border:1px solid #e2e8f0;background:#f8fafc;color:#475569;border-radius:8px;font-size:.77rem;padding:.3rem .75rem;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;">
                                    <i class="ri-checkbox-blank-line"></i>Ninguno
                                </button>
                            </div>
                            <span id="moodleInfoContador" style="font-size:.77rem;color:#64748b;font-weight:500;"></span>
                        </div>

                        
                        <div class="table-responsive" style="max-height:220px;overflow-y:auto;">
                            <table style="width:100%;border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                                        <th style="width:36px;padding:.6rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;"></th>
                                        <th style="padding:.6rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;">Estudiante</th>
                                        <th style="padding:.6rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;">Celular</th>
                                        <th style="padding:.6rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;text-align:center;width:60px;">WA</th>
                                    </tr>
                                </thead>
                                <tbody id="moodleInfoEstudiantesBody"></tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>

            
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;gap:.5rem;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                    style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
                <button type="button" id="btnEnviarInfoModulo" disabled
                    style="display:none;align-items:center;gap:.4rem;padding:.4rem 1.15rem;background:linear-gradient(135deg,#25d366,#128c7e);border:none;border-radius:8px;color:white;font-size:.82rem;font-weight:600;cursor:pointer;box-shadow:0 4px 12px rgba(37,211,102,.3);transition:all .2s;opacity:.6;">
                    <i class="ri-whatsapp-line"></i>Enviar Info a Seleccionados
                </button>
                <button type="button" id="btnEnviarCredencialesMoodle" disabled
                    style="display:inline-flex;align-items:center;gap:.4rem;padding:.4rem 1.15rem;background:linear-gradient(135deg,#25d366,#128c7e);border:none;border-radius:8px;color:white;font-size:.82rem;font-weight:600;cursor:pointer;box-shadow:0 4px 12px rgba(37,211,102,.3);transition:all .2s;opacity:.6;">
                    <i class="ri-whatsapp-line"></i>Enviar Credenciales
                </button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="modalEnlaceVideollamada" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">

            
            <div class="modal-header" style="background:linear-gradient(135deg,#391b04,#7c3c00,<?php echo e($brandColor); ?>);color:white;padding:1.25rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3 flex-grow-1 min-width-0">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-video-chat-line" style="font-size:1.4rem;"></i>
                    </div>
                    <div style="min-width:0;">
                        <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;color:#fff;" id="enlaceVidTitulo">Enlace de Sesión Virtual</h5>
                        <div id="enlaceVidModuloNombre" style="font-size:.73rem;opacity:.8;margin-top:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            
            <div class="modal-body" style="padding:1.5rem;">
                <input type="hidden" id="enlaceVidModuloId">
                <input type="hidden" id="enlaceVidEnlaceId">

                
                <div class="mb-3" id="enlaceVidCuentaWrap">
                    <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#878a99;display:flex;align-items:center;gap:.35rem;margin-bottom:.5rem;">
                        <i class="ri-computer-line" style="color:<?php echo e($brandColor); ?>;"></i> Cuenta de Videollamada <span style="color:#ef4444;">*</span>
                    </label>
                    <select id="enlaceVidCuentaId" class="form-select" style="border-radius:10px;">
                        <option value="">— Seleccionar cuenta —</option>
                    </select>
                    <div id="enlaceVidCuentaPreview" style="display:none;margin-top:.5rem;padding:.5rem .75rem;background:rgba(252,123,4,.06);border:1px solid rgba(252,123,4,.2);border-radius:8px;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-computer-line" style="color:#fc7b04;font-size:.9rem;"></i>
                            <span id="enlaceVidCuentaPreviewText" style="font-size:.84rem;font-weight:600;color:#92400e;"></span>
                        </div>
                    </div>
                </div>

                
                <div class="mb-3">
                    <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#878a99;display:flex;align-items:center;gap:.35rem;margin-bottom:.5rem;">
                        <i class="ri-text" style="color:<?php echo e($brandColor); ?>;"></i> Nombre del Enlace <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" id="enlaceVidNombre" class="form-control" maxlength="200"
                        placeholder="Ej: Clase Módulo 1 — Semana 1" style="border-radius:10px;">
                    <div class="invalid-feedback" id="enlaceVidNombreError"></div>
                </div>

                
                <div class="mb-1">
                    <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#878a99;display:flex;align-items:center;gap:.35rem;margin-bottom:.5rem;">
                        <i class="ri-link" style="color:<?php echo e($brandColor); ?>;"></i> Enlace de la Sesión <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" id="enlaceVidUrl" class="form-control" maxlength="500"
                        placeholder="https://meet.google.com/xxx-xxxx-xxx" style="border-radius:10px;">
                    <div class="invalid-feedback" id="enlaceVidUrlError"></div>
                </div>

                <div style="margin-top:1rem;padding:.65rem .9rem;background:rgba(252,123,4,.05);border:1px dashed rgba(252,123,4,.3);border-radius:10px;display:flex;align-items:flex-start;gap:.5rem;">
                    <i class="ri-information-line" style="color:#fc7b04;font-size:.95rem;flex-shrink:0;margin-top:.05rem;"></i>
                    <p class="mb-0" style="font-size:.75rem;color:#64748b;line-height:1.6;">
                        El enlace se registrará en el módulo y se asignará automáticamente a todas las sesiones del mismo.
                    </p>
                </div>
            </div>

            
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;gap:.5rem;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" id="btnGuardarEnlaceVid"
                    style="background:linear-gradient(135deg,#391b04,<?php echo e($brandColor); ?>);border:none;color:white;border-radius:8px;font-size:.82rem;padding:.4rem 1.15rem;font-weight:700;box-shadow:0 4px 12px rgba(252,123,4,.3);transition:all .2s;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;">
                    <i class="ri-save-line" id="enlaceVidBtnIcon"></i><span id="enlaceVidBtnText">Registrar Enlace</span>
                </button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="modalEditarResponsables" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 10px 40px rgba(0,0,0,.15);">

            <div class="modal-header" style="border-bottom:1px solid #e9ebec;padding:1.1rem 1.4rem;">
                <h5 class="modal-title" style="font-size:.95rem;font-weight:700;color:#333;display:flex;align-items:center;gap:.5rem;">
                    <i class="ri-team-line" style="color:var(--brand-color);"></i> Editar Equipo Responsable
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding:1.4rem;">
                <input type="hidden" id="respAcademicoId" value="<?php echo e($oferta->responsable_academico_id ?? ''); ?>">
                <input type="hidden" id="respMarketingId" value="<?php echo e($oferta->responsable_marketing_id ?? ''); ?>">

                
                <div class="mb-4">
                    <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#878a99;display:block;margin-bottom:.5rem;">
                        <i class="ri-book-open-line" style="color:#3b82f6;"></i> Coordinador Académico
                    </label>

                    
                    <div id="respAcademicoActual" style="display:none;margin-bottom:.6rem;padding:.55rem .75rem;background:rgba(59,130,246,.06);border:1px solid rgba(59,130,246,.2);border-radius:8px;align-items:center;justify-content:space-between;gap:.5rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;min-width:0;">
                            <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#1d4ed8);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-user-line" style="color:#fff;font-size:.8rem;"></i>
                            </div>
                            <div style="min-width:0;">
                                <div id="respAcademicoNombre" style="font-weight:600;font-size:.85rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></div>
                                <div id="respAcademicoCargo" style="font-size:.72rem;color:#64748b;"></div>
                            </div>
                        </div>
                        <button type="button" id="btnLimpiarAcademico" title="Quitar asignación"
                            style="flex-shrink:0;background:rgba(239,68,68,.1);border:none;border-radius:6px;padding:.25rem .5rem;color:#ef4444;cursor:pointer;font-size:.75rem;transition:all .15s;">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>

                    
                    <div style="position:relative;">
                        <div style="display:flex;gap:.4rem;">
                            <div style="position:relative;flex:1;">
                                <i class="ri-search-line" style="position:absolute;left:.65rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.9rem;pointer-events:none;"></i>
                                <input type="text" id="inputBuscarAcademico" autocomplete="off"
                                    placeholder="Buscar por carnet o nombre..."
                                    style="width:100%;padding:.45rem .75rem .45rem 2rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:.84rem;color:#334155;background:#f8fafc;transition:border-color .2s;"
                                    onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">
                            </div>
                        </div>
                        <div id="dropAcademico" style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:#fff;border:1.5px solid #e2e8f0;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.1);z-index:9999;max-height:200px;overflow-y:auto;"></div>
                    </div>
                </div>

                
                <div class="mb-1">
                    <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#878a99;display:block;margin-bottom:.5rem;">
                        <i class="ri-megaphone-line" style="color:var(--brand-color);"></i> Coordinador de Marketing
                    </label>

                    
                    <div id="respMarketingActual" style="display:none;margin-bottom:.6rem;padding:.55rem .75rem;background:rgba(var(--brand-color-rgb),.06);border:1px solid rgba(var(--brand-color-rgb),.2);border-radius:8px;align-items:center;justify-content:space-between;gap:.5rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;min-width:0;">
                            <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,<?php echo e($brandColor); ?>,#c96004);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-user-line" style="color:#fff;font-size:.8rem;"></i>
                            </div>
                            <div style="min-width:0;">
                                <div id="respMarketingNombre" style="font-weight:600;font-size:.85rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></div>
                                <div id="respMarketingCargo" style="font-size:.72rem;color:#64748b;"></div>
                            </div>
                        </div>
                        <button type="button" id="btnLimpiarMarketing" title="Quitar asignación"
                            style="flex-shrink:0;background:rgba(239,68,68,.1);border:none;border-radius:6px;padding:.25rem .5rem;color:#ef4444;cursor:pointer;font-size:.75rem;transition:all .15s;">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>

                    
                    <div style="position:relative;">
                        <div style="display:flex;gap:.4rem;">
                            <div style="position:relative;flex:1;">
                                <i class="ri-search-line" style="position:absolute;left:.65rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.9rem;pointer-events:none;"></i>
                                <input type="text" id="inputBuscarMarketing" autocomplete="off"
                                    placeholder="Buscar por carnet o nombre..."
                                    style="width:100%;padding:.45rem .75rem .45rem 2rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:.84rem;color:#334155;background:#f8fafc;transition:border-color .2s;"
                                    onfocus="this.style.borderColor='var(--brand-color)'" onblur="this.style.borderColor='#e2e8f0'">
                            </div>
                        </div>
                        <div id="dropMarketing" style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:#fff;border:1.5px solid #e2e8f0;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.1);z-index:9999;max-height:200px;overflow-y:auto;"></div>
                    </div>
                </div>

                <p style="margin-top:1rem;font-size:.71rem;color:#94a3b8;display:flex;align-items:center;gap:.3rem;">
                    <i class="ri-information-line"></i> Busca por número de carnet o nombre del trabajador.
                </p>
            </div>

            <div class="modal-footer" style="border-top:1px solid #e9ebec;padding:.9rem 1.4rem;gap:.5rem;">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal"
                    style="border-radius:8px;font-weight:500;">Cancelar</button>
                <button type="button" id="btnGuardarResponsables"
                    style="padding:.45rem 1.2rem;border-radius:8px;border:none;background:var(--brand-color);color:#fff;font-size:.85rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;">
                    <i class="ri-save-line"></i> Guardar
                </button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="modalEditarDocumentos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 10px 40px rgba(0,0,0,.15);">

            <div class="modal-header" style="border-bottom:1px solid #e9ebec;padding:1.1rem 1.4rem;">
                <h5 class="modal-title" style="font-size:.95rem;font-weight:700;color:#333;display:flex;align-items:center;gap:.5rem;">
                    <i class="ri-folder-open-line" style="color:var(--brand-color);"></i> Gestionar Documentos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding:1.4rem;">
                <form id="formEditarDocumentos" novalidate enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    
                    <div class="mb-4">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem;">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#878a99;display:flex;align-items:center;gap:.4rem;margin:0;">
                                <i class="ri-image-line" style="color:#6366f1;"></i> Imagen de Portada
                            </label>
                            <?php if($oferta->portada): ?>
                                <span style="font-size:.68rem;color:#16a34a;display:flex;align-items:center;gap:.3rem;">
                                    <i class="ri-checkbox-circle-fill"></i> Actual:
                                    <a href="<?php echo e(asset('storage/' . $oferta->portada)); ?>" target="_blank"
                                       style="color:#16a34a;text-decoration:underline;">ver archivo</a>
                                </span>
                            <?php else: ?>
                                <span style="font-size:.68rem;color:#94a3b8;"><i class="ri-close-circle-line"></i> Sin portada</span>
                            <?php endif; ?>
                        </div>
                        <div class="tgi-file-drop" id="dropPortada" onclick="document.getElementById('inputPortada').click()">
                            <i class="ri-upload-cloud-2-line"></i>
                            <span id="labelPortada">Haz clic o arrastra una imagen (JPG, PNG, GIF — máx. 2 MB)</span>
                        </div>
                        <input type="file" id="inputPortada" name="portada"
                            accept="image/jpeg,image/png,image/gif,image/webp"
                            style="display:none;"
                            onchange="tgiFileSelected('inputPortada','labelPortada','dropPortada')">
                    </div>

                    
                    <div class="mb-1">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem;">
                            <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#878a99;display:flex;align-items:center;gap:.4rem;margin:0;">
                                <i class="ri-file-pdf-line" style="color:#ef4444;"></i> Certificado Base
                            </label>
                            <?php if($oferta->certificado): ?>
                                <span style="font-size:.68rem;color:#16a34a;display:flex;align-items:center;gap:.3rem;">
                                    <i class="ri-checkbox-circle-fill"></i> Actual:
                                    <a href="<?php echo e(asset('storage/' . $oferta->certificado)); ?>" target="_blank"
                                       style="color:#16a34a;text-decoration:underline;">ver archivo</a>
                                </span>
                            <?php else: ?>
                                <span style="font-size:.68rem;color:#94a3b8;"><i class="ri-close-circle-line"></i> Sin certificado</span>
                            <?php endif; ?>
                        </div>
                        <div class="tgi-file-drop" id="dropCertificado" onclick="document.getElementById('inputCertificado').click()">
                            <i class="ri-upload-cloud-2-line"></i>
                            <span id="labelCertificado">Haz clic o arrastra un archivo (PDF, JPG, PNG — máx. 5 MB)</span>
                        </div>
                        <input type="file" id="inputCertificado" name="certificado"
                            accept="image/jpeg,image/png,image/gif,.pdf,application/pdf"
                            style="display:none;"
                            onchange="tgiFileSelected('inputCertificado','labelCertificado','dropCertificado')">
                    </div>

                    <p style="font-size:.71rem;color:#94a3b8;margin-top:.9rem;display:flex;align-items:center;gap:.3rem;">
                        <i class="ri-information-line"></i>
                        Solo debes seleccionar el archivo que deseas actualizar. Los archivos no seleccionados se mantendrán sin cambios.
                    </p>
                </form>
            </div>

            <div class="modal-footer" style="border-top:1px solid #e9ebec;padding:.9rem 1.4rem;gap:.5rem;">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal"
                    style="border-radius:8px;font-weight:500;">Cancelar</button>
                <button type="button" id="btnGuardarDocumentos"
                    style="padding:.45rem 1.2rem;border-radius:8px;border:none;background:var(--brand-color);color:#fff;font-size:.85rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;">
                    <i class="ri-save-line"></i> Guardar cambios
                </button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="modalEditarDocSolo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 10px 40px rgba(0,0,0,.15);">

            <div class="modal-header" style="border-bottom:1px solid #e9ebec;padding:1.1rem 1.4rem;">
                <h5 class="modal-title" id="tituloDocSolo" style="font-size:.95rem;font-weight:700;color:#333;display:flex;align-items:center;gap:.5rem;">
                    <i id="iconoDocSolo" class="ri-image-line" style="color:var(--brand-color);"></i>
                    <span id="labelTituloDocSolo">Editar Portada</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding:1.4rem;">
                <input type="hidden" id="tipoDocSolo">

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem;">
                    <label id="labelDescDocSolo" style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#878a99;margin:0;"></label>
                    <span id="estadoActualDocSolo" style="font-size:.68rem;"></span>
                </div>

                <div class="tgi-file-drop" id="dropDocSolo" onclick="document.getElementById('inputDocSolo').click()">
                    <i class="ri-upload-cloud-2-line"></i>
                    <span id="labelDocSolo">Haz clic o arrastra el archivo aquí</span>
                </div>
                <input type="file" id="inputDocSolo" style="display:none;"
                    onchange="tgiFileSelected('inputDocSolo','labelDocSolo','dropDocSolo')">

                <p id="hintDocSolo" style="font-size:.71rem;color:#94a3b8;margin-top:.65rem;display:flex;align-items:center;gap:.3rem;"></p>
            </div>

            <div class="modal-footer" style="border-top:1px solid #e9ebec;padding:.9rem 1.4rem;gap:.5rem;">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal"
                    style="border-radius:8px;font-weight:500;">Cancelar</button>
                <button type="button" id="btnGuardarDocSolo"
                    style="padding:.45rem 1.2rem;border-radius:8px;border:none;background:var(--brand-color);color:#fff;font-size:.85rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;">
                    <i class="ri-save-line"></i> Guardar
                </button>
            </div>

        </div>
    </div>
</div>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/ofertas-academicas/partials/ofertas-detalle/modals.blade.php ENDPATH**/ ?>