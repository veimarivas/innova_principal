<div class="tab-content-section" id="tab-contable">

    {{-- Header bar --}}
    <div class="tab-section-header">
        <div class="tab-section-header-left">
            <div class="tab-section-icon con-icon-color"><i class="ri-calculator-line"></i></div>
            <div>
                <div class="tab-section-title">Configuración de Precios</div>
                <div class="tab-section-sub">Planes de pago y conceptos de cobro de la oferta</div>
            </div>
        </div>
        <button type="button" id="btn-nuevo-plan-concepto" class="tab-section-action-btn">
            <i class="ri-add-line"></i> Nueva Configuración
        </button>
    </div>

    {{-- Empty state --}}
    <div id="contableEmptyState" class="ins-state-box" style="display:none;">
        <div class="ins-empty-icon" style="color:var(--brand-color);background:rgba(var(--brand-color-rgb),.08);">
            <i class="ri-receipt-line"></i>
        </div>
        <p class="ins-state-text fw-semibold" style="color:#334155;">No hay configuraciones de precio registradas</p>
        <p class="ins-state-text">Haz clic en "Nueva Configuración" para comenzar</p>
    </div>

    {{-- Cards container --}}
    <div id="contableCardsContainer" class="contable-cards-wrapper"></div>

</div>
