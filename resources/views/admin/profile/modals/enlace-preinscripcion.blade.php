<div class="modal fade" id="enlacePreinscripcionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content enl-modal-content">

            {{-- Header --}}
            <div class="enl-header">
                <div class="enl-header-decoration"></div>
                <div class="enl-header-inner">
                    <div class="enl-header-icon">
                        <i class="ri-qr-code-line"></i>
                    </div>
                    <div class="enl-header-text">
                        <h5 class="enl-header-title">Enlace de Pre-inscripción</h5>
                        <p class="enl-header-subtitle" id="enlaceModalPrograma">—</p>
                    </div>
                </div>
                <button type="button" class="enl-close-btn" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="ri-close-line"></i>
                </button>
            </div>

            {{-- Tabs --}}
            <div class="enl-tabs-wrap">
                <div class="enl-tabs">
                    <button type="button" id="tabSinPlan" class="enl-tab" onclick="enlaceSwitchTab('sin-plan')">
                        <i class="ri-link-m"></i>
                        <span>Sin plan de pago</span>
                    </button>
                    <button type="button" id="tabConPlan" class="enl-tab" onclick="enlaceSwitchTab('con-plan')">
                        <i class="ri-price-tag-3-line"></i>
                        <span>Con plan de pago</span>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="modal-body enl-body">

                {{-- ── TAB SIN PLAN ── --}}
                <div id="panelSinPlan">

                    <div id="loadingSinPlan" class="enl-loading">
                        <div class="spinner-border enl-spinner" role="status"></div>
                        <p class="enl-loading-text">Generando enlace...</p>
                    </div>

                    <div id="contentSinPlan" style="display:none;">
                        @include('admin.profile.modals.partials.enlace-display', ['prefix' => 'SinPlan'])
                    </div>

                    <div id="errorSinPlan" style="display:none;" class="enl-error-state">
                        <div class="enl-error-icon"><i class="ri-error-warning-line"></i></div>
                        <p class="enl-error-text" id="errorMsgSinPlan"></p>
                    </div>

                </div>

                {{-- ── TAB CON PLAN ── --}}
                <div id="panelConPlan" style="display:none;">

                    {{-- Selector de plan --}}
                    <div id="planSelectorWrap" class="enl-plan-selector">
                        <label class="enl-plan-label">
                            <i class="ri-price-tag-3-line"></i> Selecciona el plan de pago
                        </label>
                        <div id="planesLoadingMsg" class="enl-planes-loading" style="display:none;">
                            <div class="spinner-border spinner-border-sm enl-spinner" role="status"></div>
                            <span>Cargando planes disponibles...</span>
                        </div>
                        <select id="selectPlan" class="enl-select" onchange="onPlanChange()">
                            <option value="">— Elige un plan —</option>
                        </select>
                        <div id="planDetalle" class="enl-plan-detail" style="display:none;"></div>
                    </div>

                    <div id="loadingConPlan" style="display:none;" class="enl-loading">
                        <div class="spinner-border enl-spinner" role="status"></div>
                        <p class="enl-loading-text">Generando enlace con plan...</p>
                    </div>

                    <div id="contentConPlan" style="display:none;">
                        @include('admin.profile.modals.partials.enlace-display', ['prefix' => 'ConPlan'])
                    </div>

                    <div id="errorConPlan" style="display:none;" class="enl-error-state">
                        <div class="enl-error-icon"><i class="ri-error-warning-line"></i></div>
                        <p class="enl-error-text" id="errorMsgConPlan"></p>
                    </div>

                </div>

            </div>{{-- /.modal-body --}}

            {{-- Footer --}}
            <div class="enl-footer">
                <div class="enl-footer-tip">
                    <i class="ri-shield-check-line"></i>
                    <span>El enlace es personalizado y está vinculado a tu cuenta de docente</span>
                </div>
                <button type="button" class="enl-btn-close" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

<style>
    /* ── Modal contenedor ── */
    .enl-modal-content {
        background: white;
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,0.18);
    }

    /* ── Header ── */
    .enl-header {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #9a4904 0%, #bf5c04 50%, #df6a04 100%);
        overflow: hidden;
    }

    .enl-header-decoration {
        position: absolute;
        top: -40px; right: -20px;
        width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .enl-header-decoration::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -40px;
        width: 120px; height: 120px;
        background: radial-gradient(circle, rgba(255,255,255,0.07) 0%, transparent 70%);
        border-radius: 50%;
    }

    .enl-header-inner {
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
        z-index: 1;
    }

    .enl-header-icon {
        width: 46px; height: 46px;
        background: rgba(255,255,255,0.18);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        color: white;
        flex-shrink: 0;
    }

    .enl-header-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: white;
        margin: 0 0 3px;
    }

    .enl-header-subtitle {
        font-size: 0.78rem;
        color: rgba(255,255,255,0.80);
        margin: 0;
        font-weight: 500;
    }

    .enl-close-btn {
        position: relative;
        z-index: 1;
        width: 32px; height: 32px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 8px;
        color: white;
        font-size: 1.1rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
        flex-shrink: 0;
    }

    .enl-close-btn:hover { background: rgba(255,255,255,0.28); }

    /* ── Tabs ── */
    .enl-tabs-wrap {
        padding: 0 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .enl-tabs {
        display: flex;
        gap: 4px;
        padding-top: 10px;
    }

    .enl-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 8px 8px 0 0;
        border: 1px solid #e2e8f0;
        border-bottom: 2px solid transparent;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: transparent;
        color: #64748b;
        margin-bottom: -1px;
    }

    .enl-tab i { font-size: 0.9rem; }

    /* ── Body ── */
    .enl-body {
        padding: 1.5rem;
        background: white;
    }

    /* ── Loading ── */
    .enl-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 3rem 1rem;
    }

    .enl-spinner {
        width: 2rem; height: 2rem;
        color: #9a4904;
        border-width: 3px;
    }

    .enl-loading-text {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
    }

    /* ── Error state ── */
    .enl-error-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 2.5rem 1rem;
        text-align: center;
    }

    .enl-error-icon {
        width: 54px; height: 54px;
        background: #fef2f2;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
        color: #ef4444;
    }

    .enl-error-text {
        font-size: 0.85rem;
        color: #ef4444;
        margin: 0;
    }

    /* ── Plan selector ── */
    .enl-plan-selector {
        margin-bottom: 1.25rem;
        padding: 1rem 1.1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    .enl-plan-label {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #64748b;
        margin-bottom: 8px;
    }

    .enl-plan-label i { color: #fc7b04; }

    .enl-planes-loading {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #64748b;
        padding: 6px 0;
    }

    .enl-select {
        width: 100%;
        padding: 9px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #1e293b;
        background: white;
        font-family: 'Plus Jakarta Sans', sans-serif;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .enl-select:focus {
        border-color: #9a4904;
        box-shadow: 0 0 0 3px rgba(154,73,4,0.1);
    }

    .enl-plan-detail {
        margin-top: 10px;
        background: rgba(154,73,4,0.05);
        border: 1px solid rgba(154,73,4,0.18);
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.78rem;
    }

    /* ── Footer ── */
    .enl-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }

    .enl-footer-tip {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.72rem;
        color: #64748b;
    }

    .enl-footer-tip i { color: #10b981; font-size: 0.9rem; }

    .enl-btn-close {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 16px;
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .enl-btn-close:hover {
        border-color: #9a4904;
        color: #9a4904;
    }

    /* ── Responsive ── */
    @media (max-width: 576px) {
        .enl-header { padding: 1rem 1.1rem; }
        .enl-body { padding: 1.1rem; }
        .enl-tabs-wrap { padding: 0 1rem; }
        .enl-footer { padding: 10px 1rem; }
        .enl-footer-tip { display: none; }
    }
</style>
