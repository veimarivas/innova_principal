<div class="modal fade" id="enlacePreinscripcionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background:white;border:1px solid var(--prof-border);border-radius:14px;">

            <div class="modal-header" style="border-bottom:1px solid var(--prof-border);padding:1.2rem 1.5rem;">
                <div>
                    <h5 class="modal-title mb-0" style="font-weight:700;color:var(--prof-text);">
                        <i class="ri-links-line me-2" style="color:#9a4904;"></i>Enlace de Pre-inscripción
                    </h5>
                    <p class="mb-0 mt-1" id="enlaceModalPrograma" style="font-size:.77rem;color:#64748b;"></p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- TABS --}}
            <div style="border-bottom:1px solid var(--prof-border);padding:.75rem 1.5rem 0;">
                <div style="display:flex;gap:.5rem;">
                    <button type="button" id="tabSinPlan"
                        style="padding:.45rem 1.1rem;border-radius:8px 8px 0 0;border:1px solid var(--prof-border);border-bottom:none;font-size:.8rem;font-weight:600;cursor:pointer;transition:background .2s;"
                        onclick="enlaceSwitchTab('sin-plan')">
                        <i class="ri-link-m me-1"></i>Sin plan de pago
                    </button>
                    <button type="button" id="tabConPlan"
                        style="padding:.45rem 1.1rem;border-radius:8px 8px 0 0;border:1px solid var(--prof-border);border-bottom:none;font-size:.8rem;font-weight:600;cursor:pointer;transition:background .2s;"
                        onclick="enlaceSwitchTab('con-plan')">
                        <i class="ri-price-tag-3-line me-1"></i>Con plan de pago
                    </button>
                </div>
            </div>

            <div class="modal-body" style="padding:1.5rem;">

                {{-- ── TAB SIN PLAN ── --}}
                <div id="panelSinPlan">

                    <div id="loadingSinPlan" class="text-center py-4">
                        <div class="spinner-border" role="status" style="color:#9a4904;"></div>
                        <p class="mt-2 text-muted small">Generando enlace...</p>
                    </div>

                    <div id="contentSinPlan" style="display:none;">
                        @include('admin.profile.modals.partials.enlace-display', ['prefix' => 'SinPlan'])
                    </div>

                    <div id="errorSinPlan" style="display:none;" class="text-center py-4">
                        <i class="ri-error-warning-line" style="font-size:2rem;color:#ef4444;display:block;margin-bottom:.5rem;"></i>
                        <p style="font-size:.82rem;color:#ef4444;" id="errorMsgSinPlan"></p>
                    </div>

                </div>

                {{-- ── TAB CON PLAN ── --}}
                <div id="panelConPlan" style="display:none;">

                    {{-- Selector de plan --}}
                    <div id="planSelectorWrap" style="margin-bottom:1.2rem;">
                        <label style="font-size:.72rem;font-weight:600;color:#64748b;letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:.5rem;">
                            Selecciona el plan de pago
                        </label>
                        <div id="planesLoadingMsg" style="font-size:.8rem;color:#64748b;display:none;">
                            <div class="spinner-border spinner-border-sm me-2" role="status" style="color:#9a4904;"></div>Cargando planes...
                        </div>
                        <select id="selectPlan"
                            style="width:100%;background:rgba(0,0,0,.2);border:1px solid var(--prof-border);border-radius:8px;padding:.6rem .9rem;color:var(--prof-text);font-size:.85rem;outline:none;cursor:pointer;"
                            onchange="onPlanChange()">
                            <option value="">— Elige un plan —</option>
                        </select>
                        {{-- Detalle del plan seleccionado --}}
                        <div id="planDetalle" style="display:none;margin-top:.8rem;background:rgba(154,73,4,.06);border:1px solid rgba(154,73,4,.2);border-radius:8px;padding:.9rem 1rem;font-size:.78rem;"></div>
                    </div>

                    <div id="loadingConPlan" style="display:none;" class="text-center py-4">
                        <div class="spinner-border" role="status" style="color:#9a4904;"></div>
                        <p class="mt-2 text-muted small">Generando enlace con plan...</p>
                    </div>

                    <div id="contentConPlan" style="display:none;">
                        @include('admin.profile.modals.partials.enlace-display', ['prefix' => 'ConPlan'])
                    </div>

                    <div id="errorConPlan" style="display:none;" class="text-center py-4">
                        <i class="ri-error-warning-line" style="font-size:2rem;color:#ef4444;display:block;margin-bottom:.5rem;"></i>
                        <p style="font-size:.82rem;color:#ef4444;" id="errorMsgConPlan"></p>
                    </div>

                </div>

            </div><!-- /.modal-body -->

        </div>
    </div>
</div>
