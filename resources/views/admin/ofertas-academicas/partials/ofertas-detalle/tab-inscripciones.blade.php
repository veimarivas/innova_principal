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

    {{-- Table --}}
    <div class="ins-table-wrap" id="inscripcionesTableWrap" style="display:none;">
        <table class="ins-tbl" id="tabla-inscripciones">
            <thead>
                <tr>
                    <th class="ins-th-num">#</th>
                    <th>Estudiante</th>
                    <th class="text-center">Celular</th>
                    <th>Correo</th>
                    <th class="text-center">Plan de Pago</th>
                    <th class="text-center">Estado</th>
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
