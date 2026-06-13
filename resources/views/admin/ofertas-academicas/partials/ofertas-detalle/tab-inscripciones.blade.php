<div class="tab-content-section" id="tab-inscripciones">

    {{-- Header bar --}}
    <div class="ins-header-bar">
        <div class="ins-header-left">
            <div class="ins-header-icon"><i class="ri-user-follow-line"></i></div>
            <div>
                <div class="ins-header-title">Listado de Inscripciones</div>
                <div class="ins-header-sub">Gestión de estudiantes inscritos y pre-inscritos</div>
            </div>
        </div>
        <div class="ins-header-right">
            <button type="button" class="ins-btn-cuentas" id="btnCrearCuentasMoodle">
                <i class="ri-cloud-line"></i> Crear Cuentas
            </button>
            <div class="ins-filter-group">
                <button type="button" class="ins-filter-btn active" data-filter="todos">
                    <i class="ri-list-unordered"></i> Todos
                </button>
                <button type="button" class="ins-filter-btn" data-filter="Inscrito">
                    <i class="ri-user-check-line"></i> Inscritos
                </button>
                <button type="button" class="ins-filter-btn" data-filter="Pre-Inscrito">
                    <i class="ri-user-add-line"></i> Pre-Inscritos
                </button>
            </div>
        </div>
    </div>

    {{-- Loading --}}
    <div id="inscripcionesLoading" class="ins-state-box">
        <div class="spinner-border ins-spinner" role="status"></div>
        <p class="ins-state-text">Cargando inscripciones…</p>
    </div>

    {{-- Empty --}}
    <div id="inscripcionesEmpty" class="ins-state-box" style="display:none;">
        <div class="ins-empty-icon"><i class="ri-user-search-line"></i></div>
        <p class="ins-state-text">No hay inscripciones registradas</p>
    </div>

    @php
        $insFaseDesarrollo = (int) ($oferta->fase_id ?? 0) === 4;
    @endphp
    <script>window.OFERTA_FASE_DESARROLLO = {{ $insFaseDesarrollo ? 'true' : 'false' }};</script>

    {{-- Table --}}
    <div class="ins-table-wrap" id="inscripcionesTableWrap" style="display:none;">
        <table class="ins-tbl {{ $insFaseDesarrollo ? 'ins-tbl--desarrollo' : '' }}" id="tabla-inscripciones">
            <thead>
                <tr>
                    <th class="ins-th-num">#</th>
                    <th>Estudiante</th>
                    <th class="text-center">Celular</th>
                    <th>Correo</th>
                    <th class="text-center">Plan de Pago</th>
                    <th class="text-center">Estado</th>
                    @if ($insFaseDesarrollo)
                        <th class="text-center" title="Activo / Retirado (solo Inscritos)">Activo</th>
                        <th class="text-center" title="Estado Contable (solo Inscritos)">Contable</th>
                        <th class="text-center" title="Estado Académico (solo Inscritos)">Académico</th>
                    @endif
                    <th class="text-center">Sistema</th>
                    <th class="text-center">Moodle</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="inscripcionesTableBody"></tbody>
        </table>
    </div>

</div>

{{-- Modal Crear Cuentas Moodle --}}
<div class="modal fade" id="modalCrearCuentasMoodle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">

            {{-- Header --}}
            <div class="modal-header" style="background:linear-gradient(135deg,#3b1900 0%,#7a3b03 50%,#c96004 100%);color:white;padding:1.25rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(10px);flex-shrink:0;">
                        <i class="ri-cloud-line" style="font-size:1.4rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;letter-spacing:-.01em;color:#fff;">Crear Cuentas de Usuario</h5>
                        <div style="font-size:.73rem;opacity:.75;margin-top:.15rem;letter-spacing:.01em;color:rgba(255,255,255,.85);">Portal del estudiante + plataforma Moodle</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="padding:0;">

                {{-- Estado: cargando --}}
                <div id="moodleCuentasLoading" class="text-center" style="padding:3rem 1.5rem;">
                    <div class="spinner-border" style="color:#fc7b04;width:2.25rem;height:2.25rem;" role="status"></div>
                    <p class="mt-3 mb-0" style="font-size:.85rem;color:#64748b;font-weight:500;">Verificando cuentas de estudiantes…</p>
                </div>

                {{-- Estado: todos ya tienen cuenta --}}
                <div id="moodleCuentasEmpty" style="display:none;padding:3rem 1.5rem;text-align:center;">
                    <div style="width:68px;height:68px;background:linear-gradient(135deg,rgba(252,123,4,.12),rgba(154,73,4,.05));border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.1rem;border:2px solid rgba(252,123,4,.2);">
                        <i class="ri-shield-check-line" style="font-size:1.9rem;color:#fc7b04;"></i>
                    </div>
                    <h6 style="font-weight:700;color:#1e293b;margin-bottom:.4rem;font-size:.95rem;">¡Todo está al día!</h6>
                    <p style="font-size:.83rem;color:#64748b;margin:0;max-width:300px;margin-inline:auto;line-height:1.6;">Todos los estudiantes inscritos ya tienen cuentas activas en el sistema y en Moodle.</p>
                </div>

                {{-- Lista de estudiantes sin cuenta --}}
                <div id="moodleCuentasList" style="display:none;">

                    {{-- Banner informativo --}}
                    <div style="padding:1rem 1.5rem;background:linear-gradient(135deg,rgba(252,123,4,.05),rgba(154,73,4,.03));border-bottom:1px solid rgba(252,123,4,.1);">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div id="moodleCuentasCountBadge" style="display:inline-flex;align-items:center;gap:.45rem;background:rgba(252,123,4,.1);border:1px solid rgba(252,123,4,.2);color:#9a4904;font-size:.8rem;font-weight:700;padding:.3rem .8rem;border-radius:20px;white-space:nowrap;">
                                <i class="ri-user-line"></i>
                                <span id="moodleCuentasCount">0</span> sin cuenta
                            </div>
                            <p class="mb-0" style="font-size:.78rem;color:#64748b;flex:1;line-height:1.55;">Se creará la cuenta del <strong style="color:#475569;">portal</strong> y de <strong style="color:#475569;">Moodle</strong> con las mismas credenciales para los estudiantes seleccionados.</p>
                        </div>
                    </div>

                    {{-- Tabla --}}
                    <div style="padding:.75rem 1.25rem 1.25rem;max-height:360px;overflow-y:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:.8rem;" id="tablaCuentasMoodle">
                            <thead>
                                <tr style="border-bottom:2px solid #e2e8f0;">
                                    <th style="padding:.65rem .5rem;width:40px;text-align:center;">
                                        <input type="checkbox" id="selectAllMoodleAccounts" style="width:16px;height:16px;accent-color:#fc7b04;cursor:pointer;border-radius:4px;">
                                    </th>
                                    <th style="padding:.65rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;text-align:left;">Estudiante</th>
                                    <th style="padding:.65rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;text-align:left;">CI</th>
                                    <th style="padding:.65rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;text-align:left;">Usuario sugerido</th>
                                    <th style="padding:.65rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;text-align:left;">Contraseña</th>
                                </tr>
                            </thead>
                            <tbody id="moodleCuentasTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;gap:.5rem;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="btnConfirmarCrearCuentas" disabled
                    style="background:linear-gradient(135deg,#fc7b04,#d46604);border:none;border-radius:8px;font-size:.82rem;padding:.4rem 1.15rem;font-weight:600;box-shadow:0 4px 12px rgba(252,123,4,.3);transition:all .2s;">
                    <i class="ri-user-add-line me-1"></i>Crear Cuentas Seleccionadas
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ═══════════════ Modal combinado: Sugerir baja general ═══════════════ --}}
<div class="modal fade" id="modalGeneralBaja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">
            <div class="modal-header" style="background:linear-gradient(135deg,#7f1d1d 0%,#b91c1c 50%,#dc2626 100%);color:#fff;padding:1.1rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3" style="flex:1;">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-user-unfollow-line" style="font-size:1.4rem;"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;color:#fff;">Sugerencia de baja general</h5>
                        <div id="modalGeneralSubtitulo" style="font-size:.73rem;opacity:.85;margin-top:.15rem;color:rgba(255,255,255,.9);">—</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding:0;">
                <div id="modalGeneralLoading" class="text-center" style="padding:3rem 1.5rem;">
                    <div class="spinner-border" style="color:#dc2626;width:2.25rem;height:2.25rem;" role="status"></div>
                    <p class="mt-3 mb-0" style="font-size:.85rem;color:#64748b;font-weight:500;">Cargando detalle…</p>
                </div>

                <div id="modalGeneralContent" style="display:none;">
                    <div style="padding:12px 18px;background:rgba(220,38,38,.08);border-bottom:1px solid #fecaca;color:#7f1d1d;font-size:.82rem;font-weight:600;display:flex;gap:10px;align-items:center;">
                        <i class="ri-error-warning-fill" style="font-size:1.1rem;"></i>
                        <span>El estudiante tiene <strong>mora contable</strong> y <strong>2 o más módulos reprobados</strong>. Se sugiere darlo de baja general.</span>
                    </div>

                    {{-- Contable --}}
                    <div class="fin-mc-section">
                        <div class="fin-mc-section-title fin-mc-section-title--danger" style="justify-content:space-between;">
                            <span><i class="ri-money-dollar-circle-line"></i> Detalle Contable</span>
                            <span id="modalGeneralContBanner" style="font-size:.7rem;font-weight:600;color:#64748b;background:#f1f5f9;padding:2px 8px;border-radius:999px;">—</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table fin-mc-tbl mb-0">
                                <thead>
                                    <tr>
                                        <th>Cuota</th>
                                        <th class="text-end">Pendiente</th>
                                        <th class="text-center">Vencimiento</th>
                                        <th class="text-center">Días atraso</th>
                                    </tr>
                                </thead>
                                <tbody id="modalGeneralContTbody"></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Académica --}}
                    <div class="fin-mc-section" style="border-top:1px solid #f1f5f9;">
                        <div class="fin-mc-section-title fin-mc-section-title--danger" style="justify-content:space-between;">
                            <span><i class="ri-graduation-cap-line"></i> Detalle Académico</span>
                            <span id="modalGeneralAcadBanner" style="font-size:.7rem;font-weight:600;color:#64748b;background:#f1f5f9;padding:2px 8px;border-radius:999px;">—</span>
                        </div>
                        <div id="modalGeneralAcadWrap" class="table-responsive">
                            <table class="table aa-mc-tbl mb-0">
                                <thead>
                                    <tr>
                                        <th>Módulo</th>
                                        <th class="text-center">Nota Regular</th>
                                        <th class="text-center">Mínima</th>
                                        <th class="text-center">Diferencia</th>
                                    </tr>
                                </thead>
                                <tbody id="modalGeneralAcadTbody"></tbody>
                            </table>
                        </div>
                        <div id="modalGeneralAcadEmpty" style="display:none;padding:14px;text-align:center;color:#64748b;font-size:.82rem;">
                            <i class="ri-information-line" style="margin-right:5px;"></i>
                            Para ver el detalle de los módulos reprobados, abrí primero el tab Área Académica.
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:.85rem 1.25rem;justify-content:space-between;background:#f8fafc;">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" style="border:1px solid #cbd5e1;font-weight:600;">
                    <i class="ri-close-line"></i> Cerrar
                </button>
                <button type="button" class="btn btn-sm" id="modalGeneralAccion" style="background:linear-gradient(135deg,#7f1d1d,#b91c1c);font-weight:700;color:#fff;border:none;padding:.45rem 1.1rem;border-radius:8px;">
                    <i class="ri-user-unfollow-line"></i> Dar de baja general
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Wrap de celda con toggle + sugerencia en el tab Inscripciones */
    .ins-cell-wrap { display: flex; flex-direction: column; align-items: center; gap: 4px; }
    .ins-cell-sug {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: .65rem; font-weight: 700;
        padding: 2px 8px; border-radius: 999px;
        cursor: pointer; white-space: nowrap;
        font-family: 'Sora','DM Sans',sans-serif;
        border: 1px solid transparent;
        animation: insCellSugPulse 2.4s ease-in-out infinite;
        transition: transform .15s ease, filter .15s ease;
    }
    .ins-cell-sug i { font-size: .8rem; }
    .ins-cell-sug--down { background: rgba(220,38,38,.10); color: #b91c1c; border-color: rgba(220,38,38,.28); }
    .ins-cell-sug--up   { background: rgba(5,150,105,.10); color: #047857; border-color: rgba(5,150,105,.28); }
    .ins-cell-sug-btn:hover { transform: translateY(-1px); filter: brightness(1.05); }
    .ins-cell-sug-eye { opacity: .65; margin-left: 2px; }
    @keyframes insCellSugPulse { 0%,100% { opacity: .9; } 50% { opacity: .55; } }

    /* Tinte de fila según el estado general (activo / baja) — solo cuando aplica */
    .ins-row--gen-on  > td { background-color: rgba(16, 185, 129, 0.06) !important; }
    .ins-row--gen-off > td { background-color: rgba(220, 38, 38, 0.06) !important; }
    .ins-row--gen-on > td, .ins-row--gen-off > td { transition: background-color .25s ease; }
</style>
