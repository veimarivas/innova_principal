<div class="tab-content-section" id="tab-finanzas">
<style>
    /* ── Variables reutilizadas del sistema contabilidad ── */
    .fin-tab {
        --fc-primary:   #0d9488;
        --fc-indigo:    #6366f1;
        --fc-cyan:      #0891b2;
        --fc-amber:     #d97706;
        --fc-slate:     #64748b;
        --fc-success:   #059669;
        --fc-danger:    #dc2626;
        --fc-surface:   #ffffff;
        --fc-alt:       #f8fafc;
        --fc-border:    #e2e8f0;
        --fc-border-lt: #f1f5f9;
        --fc-text:      #0f172a;
        --fc-muted:     #94a3b8;
        --fc-shadow-sm: 0 1px 2px rgba(15,23,42,.04);
        --fc-shadow:    0 4px 6px -1px rgba(15,23,42,.06),0 2px 4px -2px rgba(15,23,42,.06);
        --fc-shadow-lg: 0 10px 15px -3px rgba(15,23,42,.08),0 4px 6px -4px rgba(15,23,42,.04);
        font-family: 'DM Sans', 'Inter', sans-serif;
    }

    /* ── KPI grid ── */
    .fin-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    .fin-kpi-card {
        background: var(--fc-surface);
        border-radius: 18px;
        border: 1px solid var(--fc-border);
        box-shadow: var(--fc-shadow-sm);
        overflow: hidden;
        position: relative;
        transition: all .3s cubic-bezier(.4,0,.2,1);
    }
    .fin-kpi-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
    }
    .fin-kpi-card.kpi-ins::before  { background: linear-gradient(90deg,#6366f1,#8b5cf6); }
    .fin-kpi-card.kpi-prog::before { background: linear-gradient(90deg,#64748b,#94a3b8); }
    .fin-kpi-card.kpi-pag::before  { background: linear-gradient(90deg,#059669,#10b981); }
    .fin-kpi-card.kpi-pend::before { background: linear-gradient(90deg,#dc2626,#f87171); }
    .fin-kpi-card:hover { transform: translateY(-4px); box-shadow: var(--fc-shadow-lg); }
    .fin-kpi-body { padding: 22px 22px 16px; }
    .fin-kpi-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
    .fin-kpi-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
    .fin-kpi-icon.ins  { background: rgba(99,102,241,.12);  color: #6366f1; }
    .fin-kpi-icon.prog { background: rgba(100,116,139,.12); color: #64748b; }
    .fin-kpi-icon.pag  { background: rgba(5,150,105,.12);   color: #059669; }
    .fin-kpi-icon.pend { background: rgba(220,38,38,.1);    color: #dc2626; }
    .fin-kpi-trend { font-size: .75rem; font-weight: 600; padding: 3px 8px; border-radius: 20px; }
    .fin-kpi-trend.up   { background: rgba(5,150,105,.12); color: #059669; }
    .fin-kpi-trend.down { background: rgba(220,38,38,.1);  color: #dc2626; }
    .fin-kpi-value { font-family: 'Sora','DM Sans',sans-serif; font-weight: 700; font-size: 1.55rem; line-height: 1.2; color: var(--fc-text); margin-bottom: 4px; }
    .fin-kpi-label { font-size: .8rem; color: var(--fc-muted); font-weight: 500; }
    .fin-kpi-bar { height: 6px; background: var(--fc-border-lt); border-radius: 0 0 16px 16px; overflow: hidden; }
    .fin-kpi-bar-fill { height: 100%; border-radius: 0 0 0 16px; transition: width 1s ease-out; }

    /* ── Charts row ── */
    .fin-charts-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    .fin-chart-card {
        background: var(--fc-surface);
        border-radius: 16px;
        border: 1px solid var(--fc-border);
        padding: 22px;
        box-shadow: var(--fc-shadow-sm);
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    .fin-chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--fc-border-lt);
        flex-shrink: 0;
    }
    .fin-chart-title { font-weight: 600; font-size: .88rem; color: var(--fc-text); display: flex; align-items: center; gap: 6px; }
    .fin-chart-title i { font-size: 1.05rem; color: var(--fc-primary); }
    .fin-chart-badge { background: var(--fc-alt); border: 1px solid var(--fc-border); color: #475569; font-size: .7rem; font-weight: 600; padding: 3px 10px; border-radius: 20px; white-space: nowrap; }

    /* Donut con overlay central */
    .fin-doughnut-wrap { position: relative; height: 200px; }
    .fin-center-label { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); text-align: center; pointer-events: none; width: 100%; }
    .fin-center-value { font-family: 'Sora',sans-serif; font-weight: 700; font-size: .88rem; color: var(--fc-text); line-height: 1.3; white-space: nowrap; }
    .fin-center-sub   { font-size: .58rem; color: var(--fc-muted); text-transform: uppercase; letter-spacing: .07em; }

    /* Leyenda donut */
    .fin-legend-list { margin-top: 14px; display: flex; flex-direction: column; gap: 5px; }
    .fin-legend-row { display: flex; align-items: center; gap: 8px; padding: 6px 10px; border-radius: 8px; transition: background .15s; }
    .fin-legend-row:hover { background: var(--fc-alt); }
    .fin-legend-swatch { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
    .fin-legend-name   { flex: 1; font-size: .79rem; font-weight: 500; color: var(--fc-text); }
    .fin-legend-amount { font-family: 'Sora',sans-serif; font-weight: 700; font-size: .79rem; color: var(--fc-text); }
    .fin-legend-pct    { font-size: .68rem; font-weight: 700; padding: 2px 8px; border-radius: 12px; min-width: 42px; text-align: center; }

    /* Barras CSS horizontales */
    .fin-hbars { display: flex; flex-direction: column; gap: 20px; padding: 6px 0; flex: 1; }
    .fin-hbar-meta { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 7px; }
    .fin-hbar-concept { display: flex; align-items: center; gap: 8px; font-size: .83rem; font-weight: 600; color: var(--fc-text); }
    .fin-hbar-dot { width: 9px; height: 9px; border-radius: 3px; flex-shrink: 0; }
    .fin-hbar-right { display: flex; align-items: baseline; gap: 5px; }
    .fin-hbar-pct { font-family: 'Sora',sans-serif; font-weight: 800; font-size: 1.15rem; line-height: 1; }
    .fin-hbar-of  { font-size: .7rem; color: var(--fc-muted); }
    .fin-hbar-track { height: 12px; background: var(--fc-border-lt); border-radius: 12px; overflow: hidden; }
    @keyframes finHbarGrow { from { width: 0; } to { width: var(--tw, 0%); } }
    .fin-hbar-fill { height: 100%; border-radius: 12px; width: 0; animation: finHbarGrow 1.3s cubic-bezier(.4,0,.2,1) .4s both; }
    .fin-hbar-amounts { display: flex; justify-content: space-between; margin-top: 5px; }
    .fin-hbar-cobrado { font-size: .72rem; font-weight: 600; }
    .fin-hbar-total   { font-size: .72rem; color: var(--fc-muted); }

    /* Estado mini cards debajo del donut */
    .fin-estado-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 14px; }
    .fin-estado-mini { background: var(--fc-alt); border-radius: 10px; padding: 10px 12px; border-left: 3px solid transparent; }
    .fin-estado-mini-value { font-family: 'Sora',sans-serif; font-weight: 700; font-size: .82rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .fin-estado-mini-label { font-size: .65rem; color: var(--fc-muted); margin-top: 3px; }
    .fin-estado-pct-big { font-family: 'Sora',sans-serif; font-weight: 800; font-size: 1.85rem; line-height: 1; }

    /* ── Resumen por Concepto (rc-style) ── */
    .fin-rc-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 28px; }
    .fin-rc-card { background: var(--fc-surface); border-radius: 18px; border: 1px solid var(--fc-border); box-shadow: var(--fc-shadow-sm); overflow: hidden; transition: transform .3s ease, box-shadow .3s ease; }
    .fin-rc-card:hover { transform: translateY(-3px); box-shadow: var(--fc-shadow); }
    .fin-rc-accent { height: 4px; }
    .fin-rc-body { padding: 20px 22px 18px; }
    .fin-rc-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
    .fin-rc-icon-wrap { display: flex; align-items: center; gap: 12px; }
    .fin-rc-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
    .fin-rc-name { font-family: 'Sora',sans-serif; font-weight: 700; font-size: .95rem; color: var(--fc-text); line-height: 1.3; }
    .fin-rc-sub  { font-size: .7rem; color: var(--fc-muted); margin-top: 3px; display: flex; align-items: center; gap: 4px; }
    .fin-rc-pct-badge { font-family: 'Sora',sans-serif; font-weight: 800; font-size: 1.65rem; line-height: 1; }
    .fin-rc-pct-label { font-size: .62rem; color: var(--fc-muted); text-transform: uppercase; letter-spacing: .06em; text-align: right; }
    @keyframes finRcFill { from { width: 0; } to { width: var(--tw, 0%); } }
    .fin-rc-track { height: 8px; background: var(--fc-border-lt); border-radius: 8px; overflow: hidden; margin-bottom: 14px; }
    .fin-rc-fill  { height: 100%; border-radius: 8px; width: 0; animation: finRcFill 1.2s cubic-bezier(.4,0,.2,1) .2s both; }
    .fin-rc-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; }
    .fin-rc-stat  { background: var(--fc-alt); border-radius: 10px; padding: 10px 8px 8px; text-align: center; }
    .fin-rc-stat-value { font-family: 'Sora',sans-serif; font-weight: 700; font-size: .78rem; line-height: 1.3; word-break: break-all; }
    .fin-rc-stat-label { font-size: .62rem; color: var(--fc-muted); margin-top: 3px; text-transform: uppercase; letter-spacing: .03em; }

    /* Sección title */
    .fin-section-title {
        font-family: 'Sora','DM Sans',sans-serif;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--fc-text);
        margin: 0 0 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .fin-section-title::before {
        content: '';
        width: 4px;
        height: 22px;
        background: linear-gradient(180deg, #fc7b04, #e86e00);
        border-radius: 3px;
        flex-shrink: 0;
    }

    /* ── Tabs principales de Finanzas (Detalle Completo / Ingresos Reales / Retirados) ── */
    .fin-main-tabs-card {
        background: var(--fc-surface);
        border: 1px solid var(--fc-border);
        border-radius: 16px;
        padding: 8px;
        margin-bottom: 22px;
        box-shadow: var(--fc-shadow-sm);
    }
    .fin-main-tabs { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; border: 0; margin: 0; padding: 0; list-style: none; }
    .fin-main-tabs .nav-item { margin: 0; }
    .fin-main-tab-btn {
        width: 100%;
        display: grid;
        grid-template-columns: auto 1fr;
        grid-template-rows: auto auto;
        column-gap: 12px;
        align-items: center;
        padding: 12px 16px;
        background: transparent;
        border: 1px solid transparent;
        border-radius: 12px;
        color: var(--fc-text);
        font-family: 'Sora','DM Sans',sans-serif;
        text-align: left;
        position: relative;
        transition: all .25s ease;
    }
    .fin-main-tab-btn i {
        grid-row: 1 / span 2;
        font-size: 1.45rem;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--fc-alt);
        border-radius: 10px;
        color: var(--fc-muted);
        transition: all .25s ease;
    }
    .fin-main-tab-btn span { font-weight: 700; font-size: .92rem; line-height: 1.2; }
    .fin-main-tab-btn small { font-size: .7rem; font-weight: 500; color: var(--fc-muted); line-height: 1.2; }
    .fin-main-tab-btn:hover { background: var(--fc-alt); }
    .fin-main-tab-btn.active {
        background: linear-gradient(135deg, rgba(99,102,241,.07), rgba(13,148,136,.05));
        border-color: rgba(99,102,241,.25);
        box-shadow: 0 4px 12px rgba(99,102,241,.10);
    }
    .fin-main-tab-btn.active i { background: linear-gradient(135deg,#6366f1,#0d9488); color: #fff; }
    .fin-main-tab-btn.active span { color: #1e1b4b; }
    #fin-tab-ingresos.active { background: linear-gradient(135deg, rgba(5,150,105,.08), rgba(16,185,129,.04)); border-color: rgba(5,150,105,.25); box-shadow: 0 4px 12px rgba(5,150,105,.10); }
    #fin-tab-ingresos.active i { background: linear-gradient(135deg,#059669,#10b981); }
    #fin-tab-retirados.active { background: linear-gradient(135deg, rgba(220,38,38,.08), rgba(248,113,113,.04)); border-color: rgba(220,38,38,.25); box-shadow: 0 4px 12px rgba(220,38,38,.10); }
    #fin-tab-retirados.active i { background: linear-gradient(135deg,#dc2626,#f87171); }

    .fin-main-tab-badge {
        position: absolute;
        top: 8px; right: 10px;
        background: #dc2626;
        color: #fff;
        font-size: .65rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        min-width: 22px;
        text-align: center;
    }

    .fin-main-tab-intro {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        background: var(--fc-alt);
        border: 1px solid var(--fc-border-lt);
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 22px;
    }
    .fin-main-tab-intro--danger { background: linear-gradient(135deg, rgba(220,38,38,.04), rgba(248,113,113,.02)); border-color: rgba(220,38,38,.18); }
    .fin-main-tab-intro-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
    .fin-main-tab-intro-title { font-family: 'Sora','DM Sans',sans-serif; font-weight: 700; font-size: 1rem; color: var(--fc-text); margin-bottom: 2px; }
    .fin-main-tab-intro-sub { font-size: .8rem; color: #475569; line-height: 1.5; }

    @media (max-width: 900px) {
        .fin-main-tabs { grid-template-columns: 1fr; }
        .fin-main-tab-btn small { font-size: .65rem; }
    }

    /* ── Tinte de fila según estado contable ── */
    .fin-row--contable-on  > td { background-color: rgba(16, 185, 129, 0.07) !important; }
    .fin-row--contable-on:hover > td { background-color: rgba(16, 185, 129, 0.12) !important; }
    .fin-row--contable-off > td { background-color: rgba(220, 38, 38, 0.07) !important; }
    .fin-row--contable-off:hover > td { background-color: rgba(220, 38, 38, 0.12) !important; }
    .fin-row--contable-on  > td,
    .fin-row--contable-off > td { transition: background-color .25s ease; }

    /* ── Distribución (planes + sexo) ── */
    .fin-dist-row {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 20px;
        margin-bottom: 28px;
    }
    .fin-dist-legend { margin-top: 10px; display: flex; flex-wrap: wrap; gap: 6px; }
    .fin-dist-legend .fin-dist-legend-row {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--fc-alt); border: 1px solid var(--fc-border-lt);
        padding: 4px 9px; border-radius: 999px;
        font-size: .72rem; color: var(--fc-text);
    }
    .fin-dist-legend .fin-dist-legend-row .swatch { width: 10px; height: 10px; border-radius: 3px; }
    .fin-dist-legend .fin-dist-legend-row strong { font-family: 'Sora','DM Sans',sans-serif; }
    .fin-sexo-legend { margin-top: 14px; display: flex; flex-direction: column; gap: 8px; }
    .fin-sexo-card {
        display: flex; align-items: center; gap: 10px;
        background: var(--fc-alt); border-radius: 10px; padding: 9px 12px;
    }
    .fin-sexo-card-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .fin-sexo-card-value { font-family: 'Sora','DM Sans',sans-serif; font-weight: 700; font-size: 1.05rem; line-height: 1; color: var(--fc-text); }
    .fin-sexo-card-label { font-size: .7rem; color: var(--fc-muted); margin-top: 2px; }
    .fin-sexo-card-pct { font-family: 'Sora','DM Sans',sans-serif; font-weight: 800; font-size: 1.05rem; }
    @media (max-width: 900px) {
        .fin-dist-row { grid-template-columns: 1fr; }
    }

    /* ── Badge de estado general (Activo / Retirado) ── */
    .fin-activo-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-family: 'Sora','DM Sans',sans-serif;
        font-size: .7rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 999px;
        white-space: nowrap;
        border: 1px solid transparent;
    }
    .fin-activo-badge i { font-size: .85rem; }
    .fin-activo-badge--on  { background: rgba(16, 185, 129, .12); color: #047857; border-color: rgba(16, 185, 129, .28); }
    .fin-activo-badge--off { background: rgba(220, 38, 38, .12);  color: #b91c1c; border-color: rgba(220, 38, 38, .28); }

    /* ── Celda Contable con sugerencia ── */
    .fin-contable-cell {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }
    .fin-contable-sug {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: .66rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 999px;
        cursor: help;
        white-space: nowrap;
        animation: finContableSugPulse 2.4s ease-in-out infinite;
    }
    button.fin-contable-sug-btn {
        cursor: pointer;
        font-family: inherit;
        transition: transform .15s ease, filter .15s ease;
    }
    button.fin-contable-sug-btn:hover { transform: translateY(-1px); filter: brightness(1.05); }
    button.fin-contable-sug-btn:active { transform: translateY(0); }
    .fin-contable-sug-eye { font-size: .8rem; opacity: .65; margin-left: 2px; }
    .fin-contable-sug i { font-size: .82rem; }
    .fin-contable-sug--down {
        background: rgba(220,38,38,.10);
        color: #b91c1c;
        border: 1px solid rgba(220,38,38,.28);
    }
    .fin-contable-sug--up {
        background: rgba(5,150,105,.10);
        color: #047857;
        border: 1px solid rgba(5,150,105,.28);
    }
    @keyframes finContableSugPulse {
        0%, 100% { opacity: .9; }
        50%      { opacity: .55; }
    }

    @media (max-width: 1200px) {
        .fin-kpi-grid   { grid-template-columns: repeat(2, 1fr); }
        .fin-charts-row { grid-template-columns: repeat(2, 1fr); }
        .fin-rc-grid    { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .fin-kpi-grid   { grid-template-columns: 1fr; }
        .fin-charts-row { grid-template-columns: 1fr; }
        .fin-rc-grid    { grid-template-columns: 1fr; }
    }
</style>

<div class="fin-tab">

    {{-- Header bar --}}
    <div class="tab-section-header">
        <div class="tab-section-header-left">
            <div class="tab-section-icon fin-icon-color"><i class="ri-money-dollar-circle-line"></i></div>
            <div>
                <div class="tab-section-title">Resumen Financiero</div>
                <div class="tab-section-sub">Estado de cobranza y pagos de los participantes</div>
            </div>
        </div>
    </div>

    @php
        $finColorMap = ['Matrícula' => '#6366f1', 'Colegiatura' => '#0891b2', 'Certificación' => '#d97706'];
        $finIconMap  = ['Matrícula' => 'ri-file-text-line', 'Colegiatura' => 'ri-calendar-check-line', 'Certificación' => 'ri-award-line'];
        $finCantInscritos = count(array_filter($participantesFinanzas, fn($p) => in_array($p['estado'], ['Inscrito','Confirmado','Inscrito '])));
        // Poblaciones para los gráficos de distribución (planes / sexo) por apartado
        $finPartCompleto  = array_filter($participantesFinanzas, fn($p) => in_array($p['estado'], ['Inscrito','Confirmado','Inscrito ']));
        // Ingresos Reales: SOLO estado === 'Inscrito' (y no retirados para la cantidad activos)
        $finPartActivos   = array_filter($participantesFinanzas, fn($p) => $p['estado'] === 'Inscrito' && ($p['activo'] ?? true) === true);
        $finPartRetirados = array_filter($participantesFinanzas, fn($p) => ($p['activo'] ?? true) === false);
    @endphp

    {{-- ═══════════════ TABS PRINCIPALES DE FINANZAS ═══════════════ --}}
    <div class="fin-main-tabs-card">
        <ul class="fin-main-tabs nav" id="finResumenTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="fin-main-tab-btn nav-link active" id="fin-tab-completo" data-bs-toggle="tab" data-bs-target="#fin-pane-completo" type="button" role="tab">
                    <i class="ri-list-check-2"></i>
                    <span>Detalle Completo</span>
                    <small>Todos los inscritos</small>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="fin-main-tab-btn nav-link" id="fin-tab-ingresos" data-bs-toggle="tab" data-bs-target="#fin-pane-ingresos" type="button" role="tab">
                    <i class="ri-line-chart-line"></i>
                    <span>Ingresos Reales</span>
                    <small>Activos + cobrado de retirados</small>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="fin-main-tab-btn nav-link" id="fin-tab-retirados" data-bs-toggle="tab" data-bs-target="#fin-pane-retirados" type="button" role="tab">
                    <i class="ri-user-unfollow-line"></i>
                    <span>Pérdida por Retiros</span>
                    <small>Estudiantes dados de baja</small>
                    @if (($cantidadRetirados ?? 0) > 0)
                        <span class="fin-main-tab-badge">{{ $cantidadRetirados }}</span>
                    @endif
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content fin-main-tab-content" id="finResumenTabsContent">

        {{-- ═══ TAB 1: DETALLE COMPLETO ═══ --}}
        <div class="tab-pane fade show active" id="fin-pane-completo" role="tabpanel" aria-labelledby="fin-tab-completo">
            <div class="fin-main-tab-intro">
                <div class="fin-main-tab-intro-icon" style="background:rgba(99,102,241,.12);color:#6366f1;"><i class="ri-list-check-2"></i></div>
                <div>
                    <div class="fin-main-tab-intro-title">Detalle Completo</div>
                    <div class="fin-main-tab-intro-sub">Todo lo que se debería cobrar a todos los inscritos (activos y retirados).</div>
                </div>
            </div>
            @include('admin.ofertas-academicas.partials.ofertas-detalle._finanzas-detalle-section', [
                'resumen'           => $resumenPorConcepto,
                'suffix'            => 'Completo',
                'kpiCountValue'     => $finCantInscritos,
                'kpiCountLabel'     => 'Total Inscritos',
                'kpiCountIconBg'    => 'rgba(99,102,241,.12)',
                'kpiCountIconColor' => '#6366f1',
                'kpiCountIcon'      => 'ri-user-star-line',
                'labels'            => ['programado' => 'Programado', 'cobrado' => 'Cobrado', 'pendiente' => 'Pendiente'],
                'pendienteVariant'  => 'normal',
                'colorMap'          => $finColorMap,
                'iconMap'           => $finIconMap,
                'participantes'     => $finPartCompleto,
            ])
        </div>

        {{-- ═══ TAB 2: INGRESOS REALES ═══ --}}
        <div class="tab-pane fade" id="fin-pane-ingresos" role="tabpanel" aria-labelledby="fin-tab-ingresos">
            <div class="fin-main-tab-intro">
                <div class="fin-main-tab-intro-icon" style="background:rgba(5,150,105,.12);color:#059669;"><i class="ri-line-chart-line"></i></div>
                <div>
                    <div class="fin-main-tab-intro-title">Ingresos Reales</div>
                    <div class="fin-main-tab-intro-sub">
                        Programado de los inscritos activos + lo que ya se cobró a los retirados.
                        El pendiente solo incluye lo cobrable de activos (los retirados ya no se podrán cobrar).
                    </div>
                </div>
            </div>
            @include('admin.ofertas-academicas.partials.ofertas-detalle._finanzas-detalle-section', [
                'resumen'           => $resumenPorConceptoIngresosReales,
                'suffix'            => 'IngresosReales',
                'kpiCountValue'     => $cantidadInscritoActivo ?? 0,
                'kpiCountLabel'     => 'Inscritos Activos',
                'kpiCountIconBg'    => 'rgba(5,150,105,.12)',
                'kpiCountIconColor' => '#059669',
                'kpiCountIcon'      => 'ri-user-follow-line',
                'labels'            => ['programado' => 'Realizable', 'cobrado' => 'Cobrado', 'pendiente' => 'Por Cobrar'],
                'pendienteVariant'  => 'normal',
                'colorMap'          => $finColorMap,
                'iconMap'           => $finIconMap,
                'participantes'     => $finPartActivos,
            ])
        </div>

        {{-- ═══ TAB 3: PÉRDIDA POR RETIROS ═══ --}}
        <div class="tab-pane fade" id="fin-pane-retirados" role="tabpanel" aria-labelledby="fin-tab-retirados">
            <div class="fin-main-tab-intro fin-main-tab-intro--danger">
                <div class="fin-main-tab-intro-icon" style="background:rgba(220,38,38,.12);color:#dc2626;"><i class="ri-user-unfollow-line"></i></div>
                <div>
                    <div class="fin-main-tab-intro-title">Pérdida por Retiros</div>
                    <div class="fin-main-tab-intro-sub">
                        Detalle de los estudiantes retirados: lo que se les debía cobrar, lo que se alcanzó a cobrar
                        y lo que ya no se podrá cobrar (pérdida).
                    </div>
                </div>
            </div>

            @if (($cantidadRetirados ?? 0) === 0)
                <div style="background:var(--fc-alt);border:1px dashed var(--fc-border);border-radius:14px;padding:42px 22px;text-align:center;color:var(--fc-muted);">
                    <i class="ri-user-unfollow-line" style="font-size:2.2rem;display:block;margin-bottom:10px;opacity:.55;"></i>
                    <div style="font-size:.95rem;font-weight:600;color:#475569;margin-bottom:4px;">Sin retiros registrados</div>
                    <div style="font-size:.82rem;">No hay estudiantes dados de baja en esta oferta académica.</div>
                </div>
            @else
                @include('admin.ofertas-academicas.partials.ofertas-detalle._finanzas-detalle-section', [
                    'resumen'           => $resumenPorConceptoRetirados,
                    'suffix'            => 'Retirados',
                    'kpiCountValue'     => $cantidadRetirados,
                    'kpiCountLabel'     => 'Estudiantes Retirados',
                    'kpiCountIconBg'    => 'rgba(220,38,38,.12)',
                    'kpiCountIconColor' => '#dc2626',
                    'kpiCountIcon'      => 'ri-user-unfollow-line',
                    'labels'            => ['programado' => 'Programado', 'cobrado' => 'Cobrado', 'pendiente' => 'Perdido'],
                    'pendienteVariant'  => 'perdida',
                    'colorMap'          => $finColorMap,
                    'iconMap'           => $finIconMap,
                    'participantes'     => $finPartRetirados,
                ])
            @endif
        </div>

    </div>
    {{-- ═══════════════ FIN TABS PRINCIPALES ═══════════════ --}}

    @php
        $inscritosData   = array_filter($participantesFinanzas, fn($p) => in_array($p['estado'], ['Inscrito', 'Confirmado', 'Inscrito ']));
        $preInscritosData = array_filter($participantesFinanzas, fn($p) => $p['estado'] === 'Pre-Inscrito');
        $finFaseDesarrollo = (int) ($oferta->fase_id ?? 0) === 4;
        $finExtraCols      = $finFaseDesarrollo ? 3 : 2; // [Avance + Activo (+ Contable)]
        $finTotalCols      = $finFaseDesarrollo ? 13 : 12;
    @endphp

    {{-- ── Tabla de Estado Financiero ── --}}
    <div class="fin-table-card table-card mt-4">
        <div class="fin-table-header">
            <div class="fin-table-header-left">
                <div class="fin-table-icon"><i class="ri-wallet-3-line"></i></div>
                <span class="fin-table-title">Estado Financiero de Participantes</span>
            </div>
            <ul class="fin-tabs nav" id="finanzasTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="fin-tab-btn nav-link active" id="inscritos-tab" data-bs-toggle="tab" data-bs-target="#inscritos-pane" type="button" role="tab">
                        <i class="ri-user-check-line"></i> Inscritos
                        <span class="fin-tab-count">{{ count($inscritosData) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="fin-tab-btn nav-link" id="preinscritos-tab" data-bs-toggle="tab" data-bs-target="#preinscritos-pane" type="button" role="tab">
                        <i class="ri-user-add-line"></i> Pre-Inscritos
                        <span class="fin-tab-count">{{ count($preInscritosData) }}</span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="finanzasTabsContent">
                @php
                    /* Macro para las dos tablas — evita repetir el thead */
                    $finThead = '
                    <thead>
                        <tr class="fin-thead-group">
                            <th colspan="5"></th>
                            <th class="text-center fin-th-group-conceptos" colspan="3">Conceptos de Pago</th>
                            <th class="text-center fin-th-group-totales" colspan="2">Totales</th>
                            <th colspan="1"></th>
                        </tr>
                        <tr class="fin-thead-cols">
                            <th class="text-center fin-th-num">#</th>
                            <th>Estudiante</th>
                            <th class="text-center">Plan</th>
                            <th class="text-center">Vendedor</th>
                            <th class="text-center">F. Insc.</th>
                            <th class="text-end fin-th-conceptos-col">Matrícula</th>
                            <th class="text-end fin-th-conceptos-col">Colegiatura</th>
                            <th class="text-end fin-th-conceptos-col">Certificación</th>
                            <th class="text-end fin-th-totales-col">Cobrado</th>
                            <th class="text-end fin-th-totales-col">Saldo</th>
                            <th class="text-center">Avance</th>
                        </tr>
                    </thead>';
                @endphp

                <!-- Tab Inscritos -->
                <div class="tab-pane fade show active" id="inscritos-pane" role="tabpanel" aria-labelledby="inscritos-tab">
                    <div class="table-responsive">
                        <table class="fin-tbl table align-middle mb-0">
                            <thead>
                                <tr class="fin-thead-group">
                                    <th colspan="5"></th>
                                    <th class="text-center fin-th-group-conceptos" colspan="3">Conceptos de Pago</th>
                                    <th class="text-center fin-th-group-totales" colspan="2">Totales</th>
                                    <th colspan="{{ $finExtraCols }}"></th>
                                </tr>
                                <tr class="fin-thead-cols">
                                    <th class="text-center fin-th-num">#</th>
                                    <th>Estudiante</th>
                                    <th class="text-center">Plan</th>
                                    <th class="text-center">Vendedor</th>
                                    <th class="text-center">F. Insc.</th>
                                    <th class="text-end fin-th-conceptos-col"><i class="ri-file-text-line" style="color:#6366f1;margin-right:3px;"></i>Matrícula</th>
                                    <th class="text-end fin-th-conceptos-col"><i class="ri-calendar-check-line" style="color:#0891b2;margin-right:3px;"></i>Colegiatura</th>
                                    <th class="text-end fin-th-conceptos-col"><i class="ri-award-line" style="color:#d97706;margin-right:3px;"></i>Certificación</th>
                                    <th class="text-end fin-th-totales-col"><i class="ri-check-double-line" style="color:#059669;margin-right:3px;"></i>Cobrado</th>
                                    <th class="text-end fin-th-totales-col"><i class="ri-time-line" style="color:#dc2626;margin-right:3px;"></i>Saldo</th>
                                    <th class="text-center">Avance</th>
                                    <th class="text-center" title="Activo general / Retirado (inscripciones.activo)">Activo</th>
                                    @if ($finFaseDesarrollo)
                                        <th class="text-center" title="Estado contable con sugerencia automática según cuotas vencidas / pagos al día">Contable</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inscritosData as $index => $participante)
                                    @php
                                        $pct = $participante['porcentaje_pagado'];
                                        $color = match (true) {
                                            $pct >= 100 => '#16a34a',
                                            $pct >= 70  => '#0891b2',
                                            $pct >= 50  => '#d97706',
                                            default     => '#dc2626',
                                        };
                                        $matricula    = $participante['conceptos']['Matrícula']    ?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                        $colegiatura  = $participante['conceptos']['Colegiatura']  ?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                        $certificacion= $participante['conceptos']['Certificación']?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                        $partes = explode(' ', trim($participante['nombre_completo']));
                                        $ini = strtoupper(substr($partes[0] ?? '?', 0, 1) . substr($partes[1] ?? '', 0, 1));
                                        $contableActivo = $participante['activo_contable'] ?? true;
                                        $sugerencia     = $participante['sugerencia_contable'] ?? null;
                                    @endphp
                                    <tr class="fin-row{{ $finFaseDesarrollo ? ($contableActivo ? ' fin-row--contable-on' : ' fin-row--contable-off') : '' }}" data-inscripcion-id="{{ $participante['inscripcion_id'] }}">
                                        <td class="text-center fin-td-num">{{ $loop->iteration }}</td>
                                        <td class="fin-td-estudiante">
                                            <div class="fin-student-cell">
                                                <div class="fin-avatar" style="background:linear-gradient(135deg,{{ $color }},{{ $color }}bb);">{{ $ini }}</div>
                                                <div>
                                                    <a href="/admin/estudiantes/{{ $participante['estudiante_id'] }}/detalle" class="fin-student-name">{{ $participante['nombre_completo'] }}</a>
                                                    <span class="fin-ci-badge"><i class="ri-fingerprint-line"></i>{{ $participante['carnet'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="fin-plan-badge"><i class="ri-wallet-line" style="font-size:.65rem;"></i>{{ $participante['plan_pago'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($participante['vendedor_persona_id'] ?? null)
                                                <a href="{{ route('admin.vendedor.inscripciones', $participante['vendedor_persona_id']) }}" class="fin-link">{{ $participante['vendedor'] ?? '—' }}</a>
                                            @else
                                                <span class="fin-muted">{{ $participante['vendedor'] ?? '—' }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center fin-fecha">
                                            <i class="ri-calendar-2-line" style="font-size:.7rem;opacity:.5;margin-right:2px;"></i>{{ \Carbon\Carbon::parse($participante['fecha_inscripcion'])->format('d/m/Y') }}
                                        </td>
                                        <td class="text-end fin-td-conceptos-col">@include('admin.ofertas-academicas.partials.ofertas-detalle._concepto-cell', ['c' => $matricula])</td>
                                        <td class="text-end fin-td-conceptos-col">@include('admin.ofertas-academicas.partials.ofertas-detalle._concepto-cell', ['c' => $colegiatura])</td>
                                        <td class="text-end fin-td-conceptos-col">@include('admin.ofertas-academicas.partials.ofertas-detalle._concepto-cell', ['c' => $certificacion])</td>
                                        <td class="text-end fin-td-totales-col fin-amount-paid">Bs {{ number_format($participante['total_pagado'], 2) }}</td>
                                        <td class="text-end fin-td-totales-col fin-amount-saldo">Bs {{ number_format($participante['saldo'], 2) }}</td>
                                        <td class="text-center">
                                            <div class="fin-avance-cell">
                                                <span class="fin-pct-badge" style="background:{{ $color }}18;color:{{ $color }};">{{ number_format($pct, 0) }}%</span>
                                                <div class="fin-progress-wrap">
                                                    <div class="fin-progress-bar" style="width:{{ min($pct,100) }}%;background:linear-gradient(90deg,{{ $color }},{{ $color }}cc);"></div>
                                                </div>
                                            </div>
                                        </td>
                                        @php $genActivo = $participante['activo'] ?? true; @endphp
                                        <td class="text-center">
                                            @if ($genActivo)
                                                <span class="fin-activo-badge fin-activo-badge--on">
                                                    <i class="ri-checkbox-circle-line"></i> Activo
                                                </span>
                                            @else
                                                <span class="fin-activo-badge fin-activo-badge--off">
                                                    <i class="ri-user-unfollow-line"></i> Retirado
                                                </span>
                                            @endif
                                        </td>
                                        @if ($finFaseDesarrollo)
                                        <td class="text-center">
                                            <div class="fin-contable-cell"
                                                 data-inscripcion-id="{{ $participante['inscripcion_id'] }}"
                                                 data-tiene-vencidas="{{ ($participante['tiene_cuota_vencida'] ?? false) ? 1 : 0 }}">
                                                <button type="button"
                                                        class="ins-estado-toggle {{ $contableActivo ? 'on' : 'off' }} fin-contable-toggle"
                                                        data-inscripcion-id="{{ $participante['inscripcion_id'] }}"
                                                        data-campo="activo_contable"
                                                        data-valor="{{ $contableActivo ? 1 : 0 }}"
                                                        title="Estado contable: {{ $contableActivo ? 'Activo' : 'Inactivo' }} — click para cambiar">
                                                    <span class="ins-estado-toggle-track"><span class="ins-estado-toggle-knob"></span></span>
                                                    <span class="ins-estado-toggle-label">{{ $contableActivo ? 'Activo' : 'Baja' }}</span>
                                                </button>
                                                @if ($sugerencia === 'desactivar')
                                                    <button type="button"
                                                            class="fin-contable-sug fin-contable-sug--down fin-contable-sug-btn"
                                                            data-inscripcion-id="{{ $participante['inscripcion_id'] }}"
                                                            title="Tiene cuotas vencidas — ver detalle">
                                                        <i class="ri-error-warning-line"></i> Sugerir baja
                                                        <i class="ri-eye-line fin-contable-sug-eye"></i>
                                                    </button>
                                                @elseif ($sugerencia === 'activar')
                                                    <button type="button"
                                                            class="fin-contable-sug fin-contable-sug--up fin-contable-sug-btn"
                                                            data-inscripcion-id="{{ $participante['inscripcion_id'] }}"
                                                            title="Está al día — ver detalle">
                                                        <i class="ri-arrow-up-circle-line"></i> Sugerir alta
                                                        <i class="ri-eye-line fin-contable-sug-eye"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ $finTotalCols }}" class="fin-empty-row">
                                        <i class="ri-wallet-line"></i>
                                        <p>No hay participantes inscritos</p>
                                    </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Pre-Inscritos -->
                <div class="tab-pane fade" id="preinscritos-pane" role="tabpanel" aria-labelledby="preinscritos-tab">
                    <div class="table-responsive">
                        <table class="fin-tbl table align-middle mb-0">
                            <thead>
                                <tr class="fin-thead-group">
                                    <th colspan="5"></th>
                                    <th class="text-center fin-th-group-conceptos" colspan="3">Conceptos de Pago</th>
                                    <th class="text-center fin-th-group-totales" colspan="2">Totales</th>
                                    <th colspan="1"></th>
                                </tr>
                                <tr class="fin-thead-cols">
                                    <th class="text-center fin-th-num">#</th>
                                    <th>Estudiante</th>
                                    <th class="text-center">Plan</th>
                                    <th class="text-center">Vendedor</th>
                                    <th class="text-center">F. Insc.</th>
                                    <th class="text-end fin-th-conceptos-col"><i class="ri-file-text-line" style="color:#6366f1;margin-right:3px;"></i>Matrícula</th>
                                    <th class="text-end fin-th-conceptos-col"><i class="ri-calendar-check-line" style="color:#0891b2;margin-right:3px;"></i>Colegiatura</th>
                                    <th class="text-end fin-th-conceptos-col"><i class="ri-award-line" style="color:#d97706;margin-right:3px;"></i>Certificación</th>
                                    <th class="text-end fin-th-totales-col"><i class="ri-check-double-line" style="color:#059669;margin-right:3px;"></i>Cobrado</th>
                                    <th class="text-end fin-th-totales-col"><i class="ri-time-line" style="color:#dc2626;margin-right:3px;"></i>Saldo</th>
                                    <th class="text-center">Avance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($preInscritosData as $index => $participante)
                                    @php
                                        $pct = $participante['porcentaje_pagado'];
                                        $color = match (true) {
                                            $pct >= 100 => '#16a34a',
                                            $pct >= 70  => '#0891b2',
                                            $pct >= 50  => '#d97706',
                                            default     => '#dc2626',
                                        };
                                        $matricula    = $participante['conceptos']['Matrícula']    ?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                        $colegiatura  = $participante['conceptos']['Colegiatura']  ?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                        $certificacion= $participante['conceptos']['Certificación']?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                        $partes = explode(' ', trim($participante['nombre_completo']));
                                        $ini = strtoupper(substr($partes[0] ?? '?', 0, 1) . substr($partes[1] ?? '', 0, 1));
                                    @endphp
                                    <tr class="fin-row">
                                        <td class="text-center fin-td-num">{{ $loop->iteration }}</td>
                                        <td class="fin-td-estudiante">
                                            <div class="fin-student-cell">
                                                <div class="fin-avatar" style="background:linear-gradient(135deg,#d97706,#f59e0b);">{{ $ini }}</div>
                                                <div>
                                                    <a href="/admin/estudiantes/{{ $participante['estudiante_id'] }}/detalle" class="fin-student-name">{{ $participante['nombre_completo'] }}</a>
                                                    <span class="fin-ci-badge"><i class="ri-fingerprint-line"></i>{{ $participante['carnet'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="fin-plan-badge"><i class="ri-wallet-line" style="font-size:.65rem;"></i>{{ $participante['plan_pago'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($participante['vendedor_persona_id'] ?? null)
                                                <a href="{{ route('admin.vendedor.inscripciones', $participante['vendedor_persona_id']) }}" class="fin-link">{{ $participante['vendedor'] ?? '—' }}</a>
                                            @else
                                                <span class="fin-muted">{{ $participante['vendedor'] ?? '—' }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center fin-fecha">
                                            <i class="ri-calendar-2-line" style="font-size:.7rem;opacity:.5;margin-right:2px;"></i>{{ \Carbon\Carbon::parse($participante['fecha_inscripcion'])->format('d/m/Y') }}
                                        </td>
                                        <td class="text-end fin-td-conceptos-col">@include('admin.ofertas-academicas.partials.ofertas-detalle._concepto-cell', ['c' => $matricula])</td>
                                        <td class="text-end fin-td-conceptos-col">@include('admin.ofertas-academicas.partials.ofertas-detalle._concepto-cell', ['c' => $colegiatura])</td>
                                        <td class="text-end fin-td-conceptos-col">@include('admin.ofertas-academicas.partials.ofertas-detalle._concepto-cell', ['c' => $certificacion])</td>
                                        <td class="text-end fin-td-totales-col fin-amount-paid">Bs {{ number_format($participante['total_pagado'], 2) }}</td>
                                        <td class="text-end fin-td-totales-col fin-amount-saldo">Bs {{ number_format($participante['saldo'], 2) }}</td>
                                        <td class="text-center">
                                            <div class="fin-avance-cell">
                                                <span class="fin-pct-badge" style="background:{{ $color }}18;color:{{ $color }};">{{ number_format($pct, 0) }}%</span>
                                                <div class="fin-progress-wrap">
                                                    <div class="fin-progress-bar" style="width:{{ min($pct,100) }}%;background:linear-gradient(90deg,{{ $color }},{{ $color }}cc);"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="11" class="fin-empty-row">
                                        <i class="ri-user-line"></i>
                                        <p>No hay participantes pre-inscritos</p>
                                    </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- ═══════════════ Modal Detalle Estado Contable ═══════════════ --}}
<div class="modal fade" id="modalContableDetalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">
            <div class="modal-header" id="modalContableHeader" style="background:linear-gradient(135deg,#7f1d1d 0%,#b91c1c 50%,#dc2626 100%);color:white;padding:1.1rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3" style="flex:1;">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i id="modalContableHeaderIcon" class="ri-error-warning-line" style="font-size:1.4rem;"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <h5 class="modal-title mb-0" id="modalContableTitulo" style="font-size:1rem;font-weight:700;letter-spacing:-.01em;color:#fff;">Detalle de estado contable</h5>
                        <div id="modalContableSubtitulo" style="font-size:.73rem;opacity:.82;margin-top:.15rem;color:rgba(255,255,255,.9);">—</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding:0;">
                <div id="modalContableLoading" class="text-center" style="padding:3rem 1.5rem;">
                    <div class="spinner-border" style="color:#dc2626;width:2.25rem;height:2.25rem;" role="status"></div>
                    <p class="mt-3 mb-0" style="font-size:.85rem;color:#64748b;font-weight:500;">Cargando detalle de cuotas…</p>
                </div>

                <div id="modalContableContent" style="display:none;">
                    {{-- Banner sugerencia --}}
                    <div id="modalContableBanner" class="fin-mc-banner"></div>

                    {{-- KPIs --}}
                    <div class="fin-mc-kpi-row">
                        <div class="fin-mc-kpi fin-mc-kpi--danger">
                            <div class="fin-mc-kpi-label"><i class="ri-error-warning-line"></i> Vencidas</div>
                            <div class="fin-mc-kpi-value" id="modalContableKpiVencidas">0</div>
                            <div class="fin-mc-kpi-sub" id="modalContableKpiVencidasMonto">Bs. 0</div>
                        </div>
                        <div class="fin-mc-kpi fin-mc-kpi--warn">
                            <div class="fin-mc-kpi-label"><i class="ri-time-line"></i> Por vencer</div>
                            <div class="fin-mc-kpi-value" id="modalContableKpiPorVencer">0</div>
                            <div class="fin-mc-kpi-sub" id="modalContableKpiPorVencerMonto">Bs. 0</div>
                        </div>
                        <div class="fin-mc-kpi fin-mc-kpi--ok">
                            <div class="fin-mc-kpi-label"><i class="ri-checkbox-circle-line"></i> Pagadas</div>
                            <div class="fin-mc-kpi-value" id="modalContableKpiPagadas">0</div>
                            <div class="fin-mc-kpi-sub">cuota(s)</div>
                        </div>
                    </div>

                    {{-- Tabla de cuotas vencidas --}}
                    <div class="fin-mc-section" id="modalContableSeccionVencidas">
                        <div class="fin-mc-section-title fin-mc-section-title--danger">
                            <i class="ri-error-warning-fill"></i> Cuotas Vencidas
                        </div>
                        <div class="table-responsive">
                            <table class="table fin-mc-tbl mb-0">
                                <thead>
                                    <tr>
                                        <th>Cuota</th>
                                        <th class="text-end">Monto</th>
                                        <th class="text-end">Pagado</th>
                                        <th class="text-end">Pendiente</th>
                                        <th class="text-center">Vencimiento</th>
                                        <th class="text-center">Días atraso</th>
                                    </tr>
                                </thead>
                                <tbody id="modalContableTbodyVencidas"></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tabla de cuotas por vencer --}}
                    <div class="fin-mc-section" id="modalContableSeccionPorVencer">
                        <div class="fin-mc-section-title fin-mc-section-title--warn">
                            <i class="ri-time-line"></i> Cuotas por Vencer
                        </div>
                        <div class="table-responsive">
                            <table class="table fin-mc-tbl mb-0">
                                <thead>
                                    <tr>
                                        <th>Cuota</th>
                                        <th class="text-end">Monto</th>
                                        <th class="text-end">Pagado</th>
                                        <th class="text-end">Pendiente</th>
                                        <th class="text-center">Vencimiento</th>
                                    </tr>
                                </thead>
                                <tbody id="modalContableTbodyPorVencer"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:.85rem 1.25rem;justify-content:space-between;background:#f8fafc;">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" style="border:1px solid #cbd5e1;font-weight:600;">
                    <i class="ri-close-line"></i> Cerrar
                </button>
                <button type="button" class="btn btn-sm" id="modalContableAccion" style="display:none;font-weight:700;color:#fff;border:none;padding:.45rem 1.1rem;border-radius:8px;">
                    <i class="ri-check-line"></i> <span id="modalContableAccionText">Aplicar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .fin-mc-banner { padding: 12px 18px; font-size: .82rem; font-weight: 600; display: flex; gap: 10px; align-items: center; border-bottom: 1px solid #e2e8f0; }
    .fin-mc-banner i { font-size: 1.1rem; }
    .fin-mc-banner.suggest-down { background: rgba(220,38,38,.07); color: #991b1b; }
    .fin-mc-banner.suggest-up   { background: rgba(5,150,105,.07); color: #047857; }
    .fin-mc-banner.no-suggest   { background: #f8fafc; color: #475569; }

    .fin-mc-kpi-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; padding: 16px 18px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .fin-mc-kpi { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; }
    .fin-mc-kpi-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #64748b; display: flex; align-items: center; gap: 5px; margin-bottom: 4px; }
    .fin-mc-kpi-value { font-family: 'Sora','DM Sans',sans-serif; font-weight: 800; font-size: 1.35rem; line-height: 1.1; }
    .fin-mc-kpi-sub   { font-size: .7rem; color: #64748b; margin-top: 2px; }
    .fin-mc-kpi--danger { border-left: 3px solid #dc2626; }
    .fin-mc-kpi--danger .fin-mc-kpi-value { color: #b91c1c; }
    .fin-mc-kpi--warn   { border-left: 3px solid #d97706; }
    .fin-mc-kpi--warn .fin-mc-kpi-value { color: #b45309; }
    .fin-mc-kpi--ok     { border-left: 3px solid #059669; }
    .fin-mc-kpi--ok .fin-mc-kpi-value { color: #047857; }

    .fin-mc-section { padding: 14px 18px; border-bottom: 1px solid #f1f5f9; }
    .fin-mc-section:last-child { border-bottom: none; }
    .fin-mc-section-title { display: flex; align-items: center; gap: 6px; font-family: 'Sora','DM Sans',sans-serif; font-weight: 700; font-size: .82rem; margin-bottom: 8px; }
    .fin-mc-section-title--danger { color: #b91c1c; }
    .fin-mc-section-title--warn   { color: #b45309; }

    .fin-mc-tbl thead th { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #64748b; background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 8px 10px; }
    .fin-mc-tbl tbody td { font-size: .78rem; color: #1e293b; padding: 8px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .fin-mc-tbl tbody tr:last-child td { border-bottom: none; }
    .fin-mc-tbl .fin-mc-dias { display: inline-block; background: rgba(220,38,38,.1); color: #b91c1c; font-weight: 700; padding: 2px 9px; border-radius: 999px; font-size: .72rem; }
</style>

</div>{{-- .fin-tab --}}
</div>{{-- .tab-content-section --}}
