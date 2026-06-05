<style>
    /* ==========================================
       ESTILOS PREMIUM GLASSMORPHIC & ACCESIBLES
       ========================================== */

    .oferta-details-theme-wrapper {
        --d-card: var(--vz-card-bg, #ffffff);
        --d-card-border: var(--vz-border-color, #e9ebec);
        --d-card-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        --d-muted: #878a99;
        --d-body: #495057;
        --d-bg: #f3f3f9;
        --d-title: #333333;
        
        overflow: visible;
    }

    .tab-content-section {
        display: none;
    }

    .tab-content-section.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    /* Cabecera Unificada Premium */
    .oferta-unified-header {
        background: linear-gradient(135deg, rgba(30, 20, 10, 0.95) 0%, rgba(var(--brand-color-rgb), 0.2) 60%, rgba(var(--brand-color-rgb), 0.7) 100%),
                    linear-gradient(220deg, rgba(0, 0, 0, 0.8) 0%, rgba(20, 20, 20, 0.95) 100%);
        background-blend-mode: overlay, normal;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        padding: 2.25rem 2.25rem 0 2.25rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .oferta-unified-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 80%;
        height: 200%;
        background: radial-gradient(ellipse, rgba(var(--brand-color-rgb), 0.25) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Barra Superior */
    .ouh-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
    }

    .ouh-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .ouh-btn-back:hover {
        background: rgba(var(--brand-color-rgb), 0.25);
        border-color: rgba(var(--brand-color-rgb), 0.4);
        color: #fff;
        transform: translateX(-3px);
    }

    .ouh-breadcrumbs {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.75rem;
        font-weight: 500;
    }

    .ouh-breadcrumb-item.active {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
    }

    /* Sección Principal */
    .ouh-main-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 2rem;
        margin-bottom: 1.75rem;
        position: relative;
        z-index: 2;
    }

    .ouh-left-section {
        flex: 1;
        min-width: 280px;
    }

    .ouh-header-code-line {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .ouh-program-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.3rem 0.85rem;
        border-radius: 20px;
        background: var(--brand-color);
        color: var(--brand-contrast-color);
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(var(--brand-color-rgb), 0.35);
    }

    .ouh-header-version-badge {
        font-size: 0.7rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.6);
        background: rgba(255, 255, 255, 0.08);
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .ouh-title {
        color: #ffffff;
        font-size: 1.75rem;
        font-weight: 800;
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.5px;
        line-height: 1.25;
        text-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }

    .ouh-subtitle {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.85rem;
        font-weight: 500;
    }

    .ouh-subtitle-item i {
        margin-right: 0.35rem;
        color: var(--brand-color);
    }

    .ouh-subtitle-separator {
        color: rgba(255, 255, 255, 0.3);
    }

    /* Widgets de Estadísticas */
    .ouh-right-section {
        display: flex;
        align-items: center;
    }

    .ouh-stats-grid {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .ouh-stat-card {
        background: rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 0.85rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        min-width: 125px;
        transition: all 0.25s ease;
    }

    .ouh-stat-card:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(var(--brand-color-rgb), 0.3);
        transform: translateY(-2px);
    }

    .ouh-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(var(--brand-color-rgb), 0.15);
        border: 1px solid rgba(var(--brand-color-rgb), 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--brand-color);
        font-size: 1.15rem;
    }

    .ouh-stat-details {
        display: flex;
        flex-direction: column;
    }

    .ouh-stat-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.1;
    }

    .ouh-stat-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: rgba(255, 255, 255, 0.5);
        font-weight: 700;
        margin-top: 0.15rem;
    }

    /* Badges de Fila de Estado */
    .ouh-badges-row {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
        position: relative;
        z-index: 2;
    }

    .ouh-badge {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.35rem 0.8rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        backdrop-filter: blur(5px);
        transition: all 0.2s;
    }

    .ouh-badge:hover {
        transform: translateY(-1px);
    }

    .ouh-badge i {
        font-size: 0.85rem;
    }

    .badge-fase-custom {
        background: rgba(239, 68, 68, 0.12);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    
    .badge-modalidad-custom {
        background: rgba(34, 197, 94, 0.12);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .badge-gestion-custom {
        background: rgba(59, 130, 246, 0.12);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .badge-grupo-custom {
        background: rgba(168, 85, 247, 0.12);
        color: #c084fc;
        border: 1px solid rgba(168, 85, 247, 0.2);
    }

    /* Pestañas (Tabs) */
    .oferta-tabs {
        display: flex;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        gap: 0.5rem;
        overflow-x: auto;
        position: relative;
        z-index: 2;
        margin-left: -2.25rem;
        margin-right: -2.25rem;
        padding-left: 2.25rem;
        padding-right: 2.25rem;
    }

    .oferta-tab {
        padding: 1.15rem 1.25rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.6);
        border: none;
        background: none;
        cursor: pointer;
        position: relative;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.25s ease;
    }

    .oferta-tab::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 1.25rem;
        right: 1.25rem;
        height: 3px;
        background: var(--brand-color);
        border-radius: 3px 3px 0 0;
        transform: scaleX(0);
        transition: transform 0.25s ease;
        box-shadow: 0 -2px 10px rgba(var(--brand-color-rgb), 0.8);
    }

    .oferta-tab:hover {
        color: #ffffff;
    }

    .oferta-tab.active {
        color: var(--brand-color);
    }

    .oferta-tab.active::after {
        transform: scaleX(1);
    }

    .tab-badge {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.1rem 0.4rem;
        border-radius: 8px;
    }

    .oferta-tab.active .tab-badge {
        background: rgba(var(--brand-color-rgb), 0.15);
        border-color: rgba(var(--brand-color-rgb), 0.25);
        color: var(--brand-color);
    }

    /* =========================================================
       TAB INFORMACIÓN GENERAL — tgi-* classes
       ========================================================= */

    .tgi-wrap {
        padding: 1.5rem;
    }

    /* ── Banner ── */
    .tgi-banner {
        background: var(--d-card);
        border: 1px solid var(--d-card-border);
        border-radius: 14px;
        padding: 1.35rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.25rem;
        box-shadow: var(--d-card-shadow);
        transition: box-shadow .2s;
    }

    .tgi-banner:hover { box-shadow: 0 8px 24px rgba(0,0,0,.06); }

    .tgi-banner-left {
        display: flex;
        align-items: center;
        gap: 1.1rem;
        flex: 1;
        min-width: 0;
    }

    .tgi-banner-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
        flex-shrink: 0;
    }

    .tgi-banner-text { min-width: 0; }

    .tgi-banner-code {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--d-muted);
        margin-bottom: .1rem;
    }

    .tgi-banner-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--d-title);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: .35rem;
    }

    .tgi-banner-pills {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem;
    }

    .tgi-pill {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .2rem .65rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
    }

    .tgi-pill-gray {
        background: var(--d-bg);
        color: var(--d-muted);
        border: 1px solid var(--d-card-border);
    }

    .tgi-banner-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: .4rem;
        flex-shrink: 0;
    }

    /* Estado pill */
    .tgi-estado {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .35rem .9rem;
        border-radius: 20px;
        font-size: .78rem;
        font-weight: 700;
    }

    .tgi-estado-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: currentColor;
        opacity: .8;
        animation: tgiPulse 2s infinite;
    }

    @keyframes tgiPulse {
        0%,100% { transform: scale(1); opacity: .8; }
        50%      { transform: scale(1.3); opacity: 1; }
    }

    .tgi-estado-activo { background: rgba(34,197,94,.1); color: #16a34a; border: 1px solid rgba(34,197,94,.2); }
    .tgi-estado-prox   { background: rgba(59,130,246,.1); color: #2563eb; border: 1px solid rgba(59,130,246,.2); }
    .tgi-estado-fin    { background: rgba(100,116,139,.1); color: #64748b; border: 1px solid rgba(100,116,139,.2); }

    .tgi-duracion {
        font-size: .72rem;
        color: var(--d-muted);
        display: flex;
        align-items: center;
        gap: .25rem;
    }

    /* ── KPI Row ── */
    .tgi-kpi-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .tgi-kpi {
        background: var(--d-card);
        border: 1px solid var(--d-card-border);
        border-radius: 12px;
        padding: 1rem 1.1rem;
        display: flex;
        align-items: center;
        gap: .85rem;
        box-shadow: var(--d-card-shadow);
        transition: all .2s ease;
    }

    .tgi-kpi:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,.06);
    }

    .tgi-kpi-ico {
        width: 42px; height: 42px;
        border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .tgi-kpi-body { display: flex; flex-direction: column; min-width: 0; }

    .tgi-kpi-lbl {
        font-size: .67rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--d-muted);
        margin-bottom: .15rem;
    }

    .tgi-kpi-val {
        font-size: .9rem;
        font-weight: 700;
        color: var(--d-body);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── Secondary Grid ── */
    .tgi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.25rem;
    }

    .tgi-col { display: flex; flex-direction: column; }

    .tgi-card {
        background: var(--d-card);
        border: 1px solid var(--d-card-border);
        border-radius: 14px;
        padding: 1.35rem 1.5rem;
        box-shadow: var(--d-card-shadow);
        flex: 1;
    }

    .tgi-card-mt { margin-top: 1.25rem; }

    .tgi-card-hdr {
        display: flex;
        align-items: center;
        gap: .75rem;
        font-size: .8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--d-title);
        padding-bottom: .85rem;
        margin-bottom: 1.1rem;
        border-bottom: 1px solid var(--d-card-border);
    }

    .tgi-card-hdr-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* ── Timeline ── */
    .tgi-timeline { display: flex; flex-direction: column; }

    .tgi-tl-node {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        position: relative;
    }

    .tgi-tl-node:not(.tgi-tl-last) { padding-bottom: .1rem; }

    .tgi-tl-dot {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .tgi-tl-dot-green  { background: rgba(34,197,94,.12);  color: #16a34a; }
    .tgi-tl-dot-orange { background: rgba(245,158,11,.12); color: #d97706; }
    .tgi-tl-dot-red    { background: rgba(239,68,68,.12);  color: #dc2626; }

    .tgi-tl-connector {
        position: absolute;
        left: 17px;
        top: 36px;
        width: 2px;
        height: calc(100% + .75rem);
        background: var(--d-card-border);
        z-index: 0;
    }

    .tgi-tl-content {
        padding-bottom: 1.1rem;
        flex: 1;
    }

    .tgi-tl-label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--d-muted);
        margin-bottom: .25rem;
    }

    .tgi-tl-date {
        font-size: .88rem;
        font-weight: 600;
        color: var(--d-body);
        background: var(--d-bg);
        border: 1px solid var(--d-card-border);
        border-radius: 8px;
        padding: .35rem .75rem;
        display: inline-block;
    }

    .tgi-tl-date-empty {
        color: var(--d-muted);
        font-style: italic;
        font-weight: 400;
        background: transparent;
        border-color: transparent;
        padding-left: 0;
    }

    /* ── Config Académica ── */
    .tgi-acad-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .tgi-acad-stat {
        background: var(--d-bg);
        border: 1px solid var(--d-card-border);
        border-radius: 12px;
        padding: 1rem .75rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all .2s ease;
    }

    .tgi-acad-stat:hover {
        border-color: rgba(var(--brand-color-rgb),.2);
        box-shadow: 0 4px 12px rgba(0,0,0,.04);
        transform: translateY(-2px);
    }

    .tgi-acad-ico {
        width: 36px; height: 36px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        margin-bottom: .5rem;
    }

    .tgi-acad-num {
        font-size: 1.55rem;
        font-weight: 800;
        color: var(--d-title);
        line-height: 1;
        margin-bottom: .25rem;
    }

    .tgi-acad-lbl {
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--d-muted);
        text-align: center;
    }

    /* Nota mínima circular */
    .tgi-nota-circle {
        width: 52px; height: 52px;
        border-radius: 50%;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        margin-bottom: .25rem;
    }

    .tgi-nota-val  { font-size: 1.15rem; font-weight: 800; line-height: 1; }
    .tgi-nota-sub  { font-size: .5rem; font-weight: 700; text-transform: uppercase; opacity: .8; line-height: 1; }

    /* ── Responsables ── */
    .tgi-responsables { display: flex; flex-direction: column; gap: .85rem; }

    .tgi-resp {
        display: flex;
        align-items: center;
        gap: .9rem;
        background: var(--d-bg);
        border: 1px solid var(--d-card-border);
        border-radius: 12px;
        padding: .85rem 1rem;
        transition: all .2s ease;
    }

    .tgi-resp:hover {
        border-color: rgba(var(--brand-color-rgb),.2);
        box-shadow: 0 4px 12px rgba(0,0,0,.04);
        transform: translateX(2px);
    }

    .tgi-resp-avatar {
        width: 42px; height: 42px;
        border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: 1.1rem;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(0,0,0,.12);
    }

    .tgi-resp-info { flex: 1; min-width: 0; }

    .tgi-resp-rol {
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--d-muted);
        margin-bottom: .1rem;
    }

    .tgi-resp-nombre {
        font-size: .88rem;
        font-weight: 700;
        color: var(--d-title);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .tgi-resp-tag {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .28rem .7rem;
        border-radius: 20px;
        font-size: .68rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .tgi-empty-resp {
        display: flex;
        align-items: center;
        gap: .65rem;
        padding: .9rem 1rem;
        border: 1.5px dashed var(--d-card-border);
        border-radius: 12px;
        color: var(--d-muted);
        font-size: .8rem;
    }

    .tgi-empty-resp i { font-size: 1.1rem; opacity: .5; flex-shrink: 0; }

    /* ── Documentos ── */
    .tgi-docs { display: flex; flex-direction: column; gap: .85rem; }

    .tgi-doc {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: var(--d-bg);
        border: 1px solid var(--d-card-border);
        border-radius: 12px;
        padding: .85rem 1rem;
        transition: all .2s ease;
        position: relative;
    }

    .tgi-doc:hover {
        border-color: rgba(var(--brand-color-rgb),.2);
        box-shadow: 0 4px 12px rgba(0,0,0,.04);
        transform: translateY(-1px);
    }

    .tgi-doc-thumb {
        width: 64px; height: 48px;
        border-radius: 8px;
        overflow: hidden;
        background: rgba(148,163,184,.1);
        border: 1px solid var(--d-card-border);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: 1.5rem;
        color: #94a3b8;
    }

    .tgi-doc-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .tgi-doc-thumb-pdf { background: rgba(239,68,68,.06); border-color: rgba(239,68,68,.15); }

    .tgi-doc-info { flex: 1; min-width: 0; }

    .tgi-doc-name {
        font-size: .85rem;
        font-weight: 700;
        color: var(--d-title);
        margin-bottom: .1rem;
    }

    .tgi-doc-sub {
        font-size: .68rem;
        color: var(--d-muted);
        margin-bottom: .35rem;
    }

    .tgi-doc-link {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .72rem;
        font-weight: 700;
        color: var(--brand-color);
        text-decoration: none;
        padding: .22rem .7rem;
        border-radius: 6px;
        background: rgba(var(--brand-color-rgb),.08);
        border: 1px solid rgba(var(--brand-color-rgb),.18);
        transition: all .15s;
    }

    .tgi-doc-link:hover {
        background: var(--brand-color);
        color: #fff;
        text-decoration: none;
    }

    .tgi-doc-ok {
        font-size: 1.15rem;
        color: #16a34a;
        flex-shrink: 0;
    }

    .tgi-doc-empty {
        display: flex;
        align-items: center;
        gap: .65rem;
        padding: 1rem;
        border: 1.5px dashed var(--d-card-border);
        border-radius: 12px;
        color: var(--d-muted);
        font-size: .8rem;
    }

    .tgi-doc-empty i { font-size: 1.2rem; opacity: .45; flex-shrink: 0; }

    /* ── File drop zone ── */
    .tgi-file-drop {
        border: 2px dashed var(--d-card-border);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        background: var(--d-bg);
        color: var(--d-muted);
        font-size: .82rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
    }

    .tgi-file-drop i { font-size: 1.3rem; opacity: .6; }

    .tgi-file-drop:hover,
    .tgi-file-drop.has-file {
        border-color: var(--brand-color);
        background: rgba(var(--brand-color-rgb),.04);
        color: var(--brand-color);
    }

    .tgi-file-drop.has-file i { opacity: 1; }

    /* ── Botón editar en header ── */
    .tgi-edit-btn {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .25rem .75rem;
        border-radius: 7px;
        border: 1px solid var(--d-card-border);
        background: var(--d-bg);
        color: var(--d-muted);
        font-size: .72rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
        white-space: nowrap;
    }

    .tgi-edit-btn:hover {
        border-color: var(--brand-color);
        color: var(--brand-color);
        background: rgba(var(--brand-color-rgb),.06);
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .tgi-wrap { padding: 1rem; }
        .tgi-grid { grid-template-columns: 1fr; }
        .tgi-kpi-row { grid-template-columns: repeat(2, 1fr); }
        .tgi-banner { flex-direction: column; align-items: flex-start; }
        .tgi-banner-right { align-items: flex-start; }
        .tgi-acad-grid { gap: .65rem; }
    }

    /* =========================================================
       NUEVO LAYOUT FLEXIBLE Y COLLAPSABLE DE MÓDULOS Y CALENDARIO
       ========================================================= */
    .modulos-layout-wrapper {
        display: flex;
        align-items: stretch;
        gap: 1.5rem;
        width: 100%;
        position: relative;
        overflow: visible;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform, margin-left;
    }

    .modulos-sidebar {
        width: 340px;
        flex-shrink: 0;
        border: 1px solid var(--d-card-border);
        border-radius: 16px;
        padding: 1.25rem;
        max-height: 750px;
        overflow-y: auto;
        background: var(--d-card);
        box-shadow: var(--d-card-shadow);
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), margin-left 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.35s ease;
        will-change: transform, margin-left;
        position: relative;
    }

    .sidebar-collapsed .modulos-sidebar {
        transform: translateX(-100%);
        margin-left: -355px; /* width + gap */
        opacity: 0;
        pointer-events: none;
    }

    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 1040;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .sidebar-overlay.mobile-active {
        opacity: 1;
        pointer-events: all;
    }

    /* Botón de Colapso Premium */
    .btn-toggle-sidebar {
        background: rgba(var(--brand-color-rgb), 0.08);
        border: 1px solid rgba(var(--brand-color-rgb), 0.15);
        color: var(--brand-color);
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.15rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0;
        margin-right: 0.25rem;
    }

    .btn-toggle-sidebar:hover {
        background: var(--brand-color);
        color: var(--brand-contrast-color);
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(var(--brand-color-rgb), 0.25);
    }

    .btn-toggle-sidebar:active {
        transform: scale(0.95);
    }

    /* Cabecera del Sidebar */
    .modulos-sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--d-card-border);
    }

    .modulos-sidebar-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        color: var(--d-title);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modulos-sidebar-title i {
        color: var(--brand-color);
        font-size: 1.1rem;
    }

    /* Botón Maestro "Todos los Módulos" */
    .btn-todos-modulos {
        width: 100%;
        padding: 0.8rem 1.25rem;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 12px;
        border: 2px dashed rgba(var(--brand-color-rgb), 0.3);
        background: transparent;
        color: var(--brand-color);
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    .btn-todos-modulos:hover {
        background: rgba(var(--brand-color-rgb), 0.05);
        border-color: var(--brand-color);
        transform: translateY(-1px);
    }

    .btn-todos-modulos.active {
        background: linear-gradient(135deg, var(--brand-color) 0%, rgba(var(--brand-color-rgb), 0.8) 100%);
        border-style: solid;
        border-color: var(--brand-color);
        color: var(--brand-contrast-color);
        box-shadow: 0 4px 12px rgba(var(--brand-color-rgb), 0.2);
    }

    /* Tarjetas de Módulo Premium */
    .modulo-sidebar-item {
        display: flex;
        align-items: stretch;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--d-card-border);
        margin-bottom: 0.75rem;
        background: var(--d-card);
        overflow: hidden;
        position: relative;
    }

    .modulo-sidebar-item:hover {
        border-color: rgba(var(--brand-color-rgb), 0.25);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        transform: translateY(-3px) translateX(2px);
    }

    .modulo-sidebar-item.active {
        border-color: var(--active-mod-color, var(--brand-color)) !important;
        background: var(--active-mod-bg, rgba(var(--brand-color-rgb), 0.05)) !important;
        box-shadow: 0 6px 15px var(--active-mod-shadow, rgba(var(--brand-color-rgb), 0.08)) !important;
    }

    /* Barra vertical en extremo izquierdo */
    .msi-strip {
        width: 5px;
        align-self: stretch;
        flex-shrink: 0;
        border-radius: 0 4px 4px 0;
        transition: width 0.2s ease;
    }

    .modulo-sidebar-item:hover .msi-strip {
        width: 7px;
    }

    .msi-body {
        flex: 1;
        min-width: 0;
        padding: 0.8rem 0.85rem 0.7rem 0.85rem;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .msi-top {
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
    }

    .msi-num {
        font-size: 0.85rem;
        font-weight: 800;
        min-width: 20px;
        flex-shrink: 0;
        margin-top: 0.05rem;
        font-family: 'Outfit', 'Inter', sans-serif;
    }

    .msi-info {
        flex: 1;
        min-width: 0;
    }

    .modulo-sidebar-item .msi-name {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--d-title);
        line-height: 1.3;
        margin-bottom: 0.15rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        white-space: normal;
    }

    .modulo-sidebar-item .msi-docente {
        font-size: 0.7rem;
        color: var(--d-muted);
        display: flex;
        align-items: center;
        gap: 0.3rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .modulo-sidebar-item .msi-docente i {
        font-size: 0.75rem;
        color: #94a3b8;
        flex-shrink: 0;
    }

    .modulo-sidebar-item .msi-trabajador {
        font-size: 0.7rem;
        color: #6366f1;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 0.1rem;
    }

    .modulo-sidebar-item .msi-trabajador i {
        font-size: 0.75rem;
        color: #818cf8;
        flex-shrink: 0;
    }

    .modulo-sidebar-item .msi-trabajador-cargo {
        color: #94a3b8;
        font-weight: 400;
        font-size: 0.68rem;
    }

    .modulo-sidebar-item .msi-trabajador-vacio {
        color: #94a3b8;
        font-style: italic;
    }

    .modulo-sidebar-item .msi-trabajador-vacio i {
        color: #cbd5e1;
    }

    .modulo-sidebar-item .msi-estado-row {
        margin-top: 0.15rem;
        margin-bottom: 0.1rem;
    }

    .modulo-sidebar-item .msi-enlace-vid {
        font-size: 0.7rem;
        color: #6366f1;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        margin-top: 0.1rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .modulo-sidebar-item .msi-enlace-vid i {
        font-size: 0.75rem;
        flex-shrink: 0;
        color: #818cf8;
    }

    .modulo-sidebar-item .msi-enlace-vid a {
        color: #6366f1;
        text-decoration: none;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .modulo-sidebar-item .msi-enlace-vid a:hover {
        text-decoration: underline;
    }

    .modulo-sidebar-item .msi-enlace-vacio {
        color: #94a3b8;
        font-style: italic;
    }

    .modulo-sidebar-item .msi-enlace-vacio i {
        color: #cbd5e1;
    }

    /* Micro-Acciones en fila horizontal al pie de la tarjeta */
    .modulo-sidebar-item .msi-actions-row {
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        gap: 4px;
        margin-top: 0.45rem;
        padding-top: 0.4rem;
        border-top: 1px solid var(--d-card-border);
        opacity: 0.6;
        transition: opacity 0.2s ease;
    }

    .modulo-sidebar-item:hover .msi-actions-row,
    .modulo-sidebar-item.active .msi-actions-row {
        opacity: 1;
    }

    .msi-btn {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 1px solid var(--d-card-border);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        cursor: pointer;
        background: var(--d-bg);
        color: var(--d-muted);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .msi-btn:hover {
        transform: scale(1.15);
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        color: #334155;
        border-color: #cbd5e1;
    }

    .btn-ver-modulo:hover {
        background: rgba(148, 163, 184, 0.12);
        color: #475569;
        border-color: rgba(148, 163, 184, 0.3);
    }

    .btn-edit-modulo:hover {
        background: rgba(37, 99, 235, 0.1);
        color: #2563eb;
        border-color: rgba(37, 99, 235, 0.25);
    }

    .msi-btn-add:hover {
        background: rgba(22, 163, 74, 0.1);
        color: #16a34a;
        border-color: rgba(22, 163, 74, 0.25);
    }

    .msi-btn-moodle {
        background: rgba(239, 108, 0, 0.08);
        color: #ef6c00;
        border: 1px solid rgba(239, 108, 0, 0.18);
    }

    .msi-btn-moodle:hover {
        background: #ef6c00;
        color: #fff;
        border-color: #ef6c00;
    }

    .msi-moodle-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 2px 4px;
        border-radius: 4px;
        font-size: 0.65rem;
        background: rgba(239, 108, 0, 0.1);
        color: #ef6c00;
        vertical-align: middle;
        flex-shrink: 0;
        line-height: 1;
        height: 14px;
    }

    /* Barra de Progreso Fina */
    .msi-progress-wrap {
        height: 4px;
        background: #f1f5f9;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 0.2rem;
    }

    .msi-progress-bar {
        height: 100%;
        border-radius: 4px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .msi-sessions-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.68rem;
        color: #94a3b8;
        font-weight: 600;
        margin-top: 0.1rem;
    }

    /* Badge de Filtro (legacy — oculto visualmente, mantenido para compatibilidad JS) */
    .modulo-seleccionado-badge {
        display: none !important;
    }

    /* =========================================================
       PANEL DE MÓDULO ACTIVO — nuevo diseño prominente
       ========================================================= */
    .modulo-activo-panel {
        margin-top: 0.75rem;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid rgba(var(--map-color-rgb, var(--brand-color-rgb)), 0.22);
        background: rgba(var(--map-color-rgb, var(--brand-color-rgb)), 0.04);
        animation: fadeIn 0.25s ease;
        display: flex;
        align-items: stretch;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .map-bar {
        width: 4px;
        flex-shrink: 0;
        border-radius: 0;
    }

    .map-body {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 0.9rem;
        flex: 1;
        min-width: 0;
    }

    .map-icon-wrap {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(var(--map-color-rgb, var(--brand-color-rgb)), 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
        color: var(--map-color, var(--brand-color));
    }

    .map-info {
        flex: 1;
        min-width: 0;
    }

    .map-label {
        font-size: 0.68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--d-muted);
        line-height: 1;
        margin-bottom: 0.2rem;
    }

    .map-nombre {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--d-title);
        white-space: normal;
        word-break: break-word;
        line-height: 1.3;
        font-family: 'Outfit', 'Inter', sans-serif;
    }

    .map-clear {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        border: 1px solid rgba(var(--d-card-border), 0.8);
        background: var(--d-card);
        color: var(--d-muted);
        font-size: 0.73rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.18s ease;
        white-space: nowrap;
    }

    .map-clear:hover {
        border-color: #ef4444;
        color: #ef4444;
        background: rgba(239, 68, 68, 0.06);
    }

    .map-clear i { font-size: 0.85rem; }

    /* =========================================================
       CABECERA DEL CALENDARIO — rediseño
       ========================================================= */
    .calendar-container {
        flex: 1;
        min-width: 0;
        padding: 0;
        background: var(--d-card);
        border: 1px solid var(--d-card-border);
        border-radius: 16px;
        box-shadow: var(--d-card-shadow);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .calendar-header {
        padding: 1.1rem 1.4rem 0.9rem 1.4rem;
        background: var(--d-card);
        border-bottom: 1px solid var(--d-card-border);
        margin-bottom: 0;
    }

    .cal-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .cal-header-left {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        min-width: 0;
    }

    .cal-title-wrap {
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .cal-title-icon {
        font-size: 1.1rem;
        color: var(--brand-color);
        flex-shrink: 0;
    }

    .cal-title-text {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--d-title);
        white-space: nowrap;
        font-family: 'Outfit', 'Inter', sans-serif;
    }

    .cal-header-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-shrink: 0;
    }

    .cal-legend {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.72rem;
        font-weight: 500;
        color: var(--d-muted);
        padding: 0.3rem 0.75rem;
        background: var(--d-bg);
        border-radius: 20px;
        border: 1px solid var(--d-card-border);
    }

    .cal-legend-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    /* Calendario propiamente (padding interno) */
    .calendar-container #calendar {
        padding: 1.25rem 1.4rem 1.4rem;
    }

    .calendar-container .fc {
        font-family: 'Inter', 'Outfit', sans-serif;
    }

    /* Grid lines */
    .calendar-container .fc-theme-standard td,
    .calendar-container .fc-theme-standard th {
        border-color: var(--d-card-border) !important;
    }

    /* Cabecera días (lun, mar, ...) */
    .calendar-container .fc-col-header-cell {
        background: var(--d-bg) !important;
        padding: 0.65rem 0 !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.7rem !important;
        letter-spacing: 0.9px;
        color: var(--d-muted) !important;
    }

    /* Título del mes/semana en la toolbar de FC */
    .calendar-container .fc-toolbar-title {
        font-size: 1.1rem !important;
        font-weight: 800 !important;
        color: var(--d-title) !important;
        font-family: 'Outfit', sans-serif !important;
        letter-spacing: -0.02em;
    }

    /* Toolbar de FC — separación */
    .calendar-container .fc-toolbar.fc-header-toolbar {
        margin-bottom: 1rem !important;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--d-card-border);
    }

    /* Botones de Navegación del Calendario */
    .calendar-container .fc-button {
        border-radius: 8px !important;
        font-size: 0.77rem !important;
        font-weight: 700 !important;
        padding: 0.42rem 0.85rem !important;
        border: 1px solid var(--brand-color) !important;
        background: var(--brand-color) !important;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(var(--brand-color-rgb), 0.2) !important;
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1) !important;
        text-transform: capitalize !important;
        letter-spacing: 0.01em;
    }

    .calendar-container .fc .fc-toolbar-chunk .fc-today-button {
        background: var(--brand-color) !important;
        border-color: var(--brand-color) !important;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(var(--brand-color-rgb), 0.2) !important;
    }

    .calendar-container .fc-button:hover {
        background: color-mix(in srgb, var(--brand-color) 85%, #000) !important;
        border-color: color-mix(in srgb, var(--brand-color) 85%, #000) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(var(--brand-color-rgb), 0.3) !important;
    }

    .calendar-container .fc .fc-button-primary:not(:disabled).fc-button-active,
    .calendar-container .fc .fc-button-primary:not(:disabled):active {
        background: color-mix(in srgb, var(--brand-color) 55%, #3a1f0a) !important;
        border-color: color-mix(in srgb, var(--brand-color) 55%, #3a1f0a) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(var(--brand-color-rgb), 0.3) !important;
    }

    /* Números de día */
    .calendar-container .fc-daygrid-day-number {
        font-weight: 600 !important;
        font-size: 0.82rem !important;
        color: var(--d-body);
        padding: 5px 9px !important;
        transition: color 0.15s;
    }

    /* Celda de Hoy con Glow */
    .calendar-container .fc-day-today {
        background: rgba(var(--brand-color-rgb), 0.03) !important;
    }

    .calendar-container .fc-day-today .fc-daygrid-day-number {
        background: var(--brand-color) !important;
        color: var(--brand-contrast-color) !important;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        margin: 4px !important;
        padding: 0 !important;
        box-shadow: 0 3px 10px rgba(var(--brand-color-rgb), 0.3) !important;
        font-weight: 800 !important;
    }

    /* Fines de semana */
    .calendar-container .fc-day-sat .fc-daygrid-day-number,
    .calendar-container .fc-day-sun .fc-daygrid-day-number {
        color: var(--d-muted) !important;
    }

    /* Días fuera del mes */
    .calendar-container .fc-day-other .fc-daygrid-day-number {
        opacity: 0.4;
    }

    /* Píldoras de Evento — mejoradas */
    .calendar-container .fc-event {
        cursor: pointer;
        border-radius: 6px !important;
        font-size: 0.72rem !important;
        font-weight: 600 !important;
        padding: 3px 7px !important;
        border: none !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08) !important;
        transition: all 0.18s ease !important;
        margin: 2px 2px 0 2px !important;
        letter-spacing: 0.01em;
    }

    .calendar-container .fc-event:hover {
        transform: translateY(-1px) scale(1.015);
        box-shadow: 0 5px 14px rgba(0,0,0,0.14) !important;
        filter: brightness(1.06);
    }

    /* Sesión Postergada (Estilo Discontinuo) */
    .calendar-container .fc-event-postergado {
        background: rgba(var(--brand-color-rgb), 0.03) !important;
        border: 1.5px dashed var(--event-color) !important;
        color: var(--event-color) !important;
        box-shadow: none !important;
        opacity: 0.8;
    }

    .calendar-container .fc-event-postergado:hover {
        opacity: 1;
        background: rgba(var(--brand-color-rgb), 0.06) !important;
        transform: translateY(-1px);
    }

    /* Más eventos link */
    .calendar-container .fc-daygrid-more-link {
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        color: var(--brand-color) !important;
        background: rgba(var(--brand-color-rgb), 0.08);
        border-radius: 4px;
        padding: 1px 5px;
        margin: 1px 2px;
        transition: background 0.15s;
    }

    .calendar-container .fc-daygrid-more-link:hover {
        background: rgba(var(--brand-color-rgb), 0.15);
    }

    /* Vista lista */
    .calendar-container .fc-list-event:hover td {
        background: rgba(var(--brand-color-rgb), 0.04) !important;
    }

    .calendar-container .fc-list-event-dot {
        border-radius: 50% !important;
        width: 10px !important;
        height: 10px !important;
    }

    .calendar-container .fc-list-table .fc-list-day-cushion {
        background: var(--d-bg) !important;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--d-muted) !important;
        padding: 0.55rem 1rem !important;
    }

    /* Semana — time grid */
    .calendar-container .fc-timegrid-slot {
        height: 2.5rem !important;
        border-color: var(--d-card-border) !important;
    }

    .calendar-container .fc-timegrid-slot-label {
        font-size: 0.7rem !important;
        font-weight: 600;
        color: var(--d-muted) !important;
    }

    /* =========================================================
       DISEÑO RESPONSIVO (DRAWER MÓVIL)
       ========================================================= */
    @media (max-width: 991.98px) {
        .modulos-layout-wrapper {
            gap: 0;
        }

        .modulos-sidebar {
            position: fixed;
            top: 0;
            left: -325px;
            z-index: 1050;
            width: 300px;
            height: 100vh;
            max-height: 100vh;
            border-radius: 0;
            border: none;
            border-right: 1px solid var(--d-card-border);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.15);
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overscroll-behavior: contain;
        }

        .modulos-sidebar.mobile-active {
            left: 0;
        }

        .calendar-container {
            border-radius: 12px;
        }

        .calendar-header {
            padding: 0.9rem 1rem 0.75rem;
        }

        .cal-header-row {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .cal-legend {
            display: none;
        }

        .calendar-container #calendar {
            padding: 0.75rem 0.85rem 1rem;
        }

        .calendar-header-flex-legacy {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
    }

    .contable-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 5rem 2rem;
        text-align: center;
    }

    .contable-placeholder .contable-icon {
        width: 100px;
        height: 100px;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.1), rgba(252, 123, 4, 0.05));
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        border: 2px solid rgba(252, 123, 4, 0.2);
    }

    .contable-placeholder .contable-icon i {
        font-size: 3rem;
        color: #fc7b04;
    }

    .contable-placeholder h5 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--d-body);
        margin-bottom: 0.5rem;
    }

    .contable-placeholder p {
        font-size: 0.9rem;
        color: var(--d-muted);
        max-width: 450px;
        line-height: 1.6;
    }

    .modal-header-gradient {
        background: linear-gradient(135deg, #391b04 0%, #5c2d0a 50%, #c96004 100%);
        border-bottom: none;
        padding: 1.25rem 1.5rem;
    }

    .modal-header-gradient .modal-title {
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-header-gradient .modal-title i {
        font-size: 1.25rem;
    }

    .modal-header-gradient .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }

    .modal-header-gradient .btn-close:hover {
        opacity: 1;
    }

    .modal-header-modulo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-header-modulo .modulo-color-bar {
        width: 6px;
        height: 32px;
        border-radius: 4px;
    }

    .color-preview-box {
        width: 70px;
        height: 45px;
        border-radius: 10px;
        border: 2px solid var(--d-card-border);
        display: inline-block;
        vertical-align: middle;
        margin-left: 0.75rem;
        transition: background 0.15s;
    }

    .docente-confirm-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.15), rgba(252, 123, 4, 0.05));
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        border: 3px solid rgba(252, 123, 4, 0.2);
    }

    .docente-confirm-icon i {
        font-size: 2.5rem;
        color: #fc7b04;
    }

    .estudio-row {
        padding: 1rem;
        background: var(--d-bg);
        border: 1px solid var(--d-card-border);
        border-radius: 12px;
        margin-bottom: 0.75rem;
        position: relative;
        transition: all 0.2s;
    }

    .estudio-row:hover {
        border-color: rgba(252, 123, 4, 0.3);
    }

    .estudio-row .estudio-num {
        position: absolute;
        top: -10px;
        left: 12px;
        background: linear-gradient(135deg, #fc7b04, #c96004);
        color: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .estudio-row .btn-remove-estudio {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        width: 26px;
        height: 26px;
        border-radius: 8px;
        border: none;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
    }

    .estudio-row .btn-remove-estudio:hover {
        background: rgba(239, 68, 68, 0.2);
        transform: scale(1.05);
    }

    .form-control.is-invalid-custom,
    .form-select.is-invalid-custom {
        border-color: #ef4444;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='M6 3v3.5M6 8h.01'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .horario-detail-field {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.85rem 0;
        border-bottom: 1px solid var(--d-card-border);
    }

    .horario-detail-field:last-child {
        border-bottom: none;
    }

    .horario-detail-field .hdf-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .horario-detail-field .hdf-label {
        font-size: 0.7rem;
        color: var(--d-muted);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .horario-detail-field .hdf-value {
        font-size: 0.92rem;
        font-weight: 600;
        color: var(--d-body);
        margin-top: 0.15rem;
    }

    .session-stat-card {
        background: var(--d-bg);
        border: 1px solid var(--d-card-border);
        border-radius: 14px;
        padding: 1rem;
        text-align: center;
        transition: all 0.2s;
    }

    .session-stat-card:hover {
        border-color: rgba(252, 123, 4, 0.3);
        transform: translateY(-2px);
    }

    .session-stat-card .ssc-value {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.35rem;
    }

    .session-stat-card .ssc-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--d-muted);
        font-weight: 700;
    }

    .fila-concepto {
        transition: background 0.15s;
    }

    .fila-concepto:hover {
        background: var(--d-row-hover);
    }

    .fila-concepto .select-concepto.is-invalid-custom {
        border-color: #ef4444;
    }

    .fila-concepto .warning-precio {
        font-size: 0.7rem;
        color: #f59e0b;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .btn-remove-fila {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: none;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-remove-fila:hover {
        background: rgba(239, 68, 68, 0.2);
        transform: scale(1.05);
    }

    .btn-remove-fila:disabled {
        opacity: 0.3;
        cursor: not-allowed;
        transform: none;
    }

    .contable-plan-card {
        background: var(--d-card-bg);
        border: 1px solid var(--d-border);
        border-radius: 14px;
        overflow: hidden;
        transition: box-shadow 0.2s, transform 0.15s;
        position: relative;
    }

    .contable-accent-bar {
        height: 4px;
        width: 100%;
    }

    .contable-plan-card:hover {
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.07);
        transform: translateY(-1px);
    }

    .contable-plan-card.contable-plan-promo {
        border-color: rgba(252, 123, 4, 0.25);
        background: linear-gradient(135deg, var(--d-card-bg) 0%, rgba(252, 123, 4, 0.03) 100%);
    }

    .contable-plan-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--d-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .contable-plan-header-promo {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(252, 123, 4, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .contable-plan-header-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .contable-plan-header-right {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .contable-plan-nombre {
        font-weight: 800;
        font-size: 1.05rem;
        color: var(--d-title);
    }

    .contable-promo-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.7rem;
        color: #fc7b04;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        background: rgba(252, 123, 4, 0.12);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .contable-promo-dates-bar {
        padding: 0.5rem 1.25rem;
        background: rgba(252, 123, 4, 0.06);
        border-bottom: 1px solid rgba(252, 123, 4, 0.1);
        font-size: 0.78rem;
        color: #fc7b04;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .contable-promo-dates-bar i {
        font-size: 0.9rem;
    }

    .contable-plan-type-row {
        padding: 0.4rem 1.25rem;
        border-bottom: 1px solid var(--d-border);
    }

    .contable-plan-type-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
    }

    .contable-plan-type-label.promo {
        color: #fc7b04;
        background: rgba(252, 123, 4, 0.1);
    }

    .contable-plan-type-label.normal {
        color: var(--brand-color);
        background: rgba(var(--brand-color-rgb), 0.1);
    }

    .contable-plan-total {
        font-size: 1.15rem;
        font-weight: 800;
        color: #fc7b04;
    }

    .btn-contable-edit-plan {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        border: 1px solid rgba(var(--brand-color-rgb), 0.3);
        background: rgba(var(--brand-color-rgb), 0.1);
        color: var(--brand-color);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-contable-edit-plan:hover {
        background: rgba(var(--brand-color-rgb), 0.2);
        border-color: rgba(var(--brand-color-rgb), 0.5);
        transform: scale(1.03);
    }

    .contable-conceptos-list {
        padding: 0.5rem 0;
    }

    .contable-conceptos-table {
        width: 100%;
        border-collapse: collapse;
    }

    .contable-conceptos-table thead th {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--d-muted);
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid var(--d-border);
    }

    .contable-conceptos-table tbody td {
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        vertical-align: middle;
    }

    .contable-conceptos-table tbody tr:last-child td {
        border-bottom: none;
    }

    .contable-conceptos-table tbody tr:hover {
        background: var(--d-row-hover);
    }

    .contable-cuotas-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        background: rgba(var(--brand-color-rgb), 0.12);
        color: var(--brand-color);
        font-weight: 700;
        font-size: 0.75rem;
    }

    .btn-contable-edit,
    .btn-contable-delete {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
        font-size: 0.75rem;
    }

    .btn-contable-edit {
        background: rgba(var(--brand-color-rgb), 0.12);
        color: var(--brand-color);
    }

    .btn-contable-edit:hover {
        background: rgba(var(--brand-color-rgb), 0.25);
        transform: scale(1.05);
    }

    .btn-contable-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .btn-contable-delete:hover {
        background: rgba(239, 68, 68, 0.2);
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .oferta-detail-header {
            padding: 1.25rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-section {
            padding: 1rem 1.25rem;
        }

        .fecha-timeline {
            flex-direction: column;
            align-items: flex-start;
        }

        .fecha-timeline::before {
            display: none;
        }

        .fecha-line {
            display: none;
        }

        .modulos-sidebar {
            border-right: none;
            border-bottom: 1px solid var(--d-card-border);
            max-height: 350px;
        }

        .cal-header-row {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .calendar-stats {
            flex-wrap: wrap;
        }

        .header-stats {
            width: 100%;
            justify-content: center;
        }
    }

    .inscripciones-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--d-card-border);
    }

    .inscripciones-title-section {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .inscripciones-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-crear-cuentas-moodle {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-crear-cuentas-moodle:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        transform: translateY(-1px);
    }

    .btn-group-filtros {
        display: flex;
        gap: 0.5rem;
    }

    .btn-filtro-inscripciones {
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 8px;
        background: var(--d-bg);
        color: var(--d-muted);
        border: 1px solid var(--d-card-border);
        transition: all 0.2s;
    }

    .btn-filtro-inscripciones.active {
        background: rgba(252, 123, 4, 0.12) !important;
        color: #fc7b04 !important;
        border-color: rgba(252, 123, 4, 0.3) !important;
    }

    .btn-filtro-inscripciones:not(.active):hover {
        background: var(--d-row-hover) !important;
    }

    .table-inscripciones-container {
        overflow: visible;
        overflow-x: hidden;
        max-width: 100%;
    }

    #tabla-inscripciones {
        margin-bottom: 0;
        width: 100% !important;
        table-layout: auto !important;
        overflow: visible;
        overflow-x: hidden;
    }

    #tabla-inscripciones_wrapper {
        overflow: visible !important;
        overflow-x: hidden !important;
    }

    #tab-inscripciones .table-responsive {
        overflow: visible !important;
        overflow-x: hidden !important;
    }

    #tab-inscripciones {
        overflow: visible !important;
        overflow-x: hidden !important;
    }

    #tabla-inscripciones thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--d-muted);
        padding: 0.6rem 0.4rem;
        border-bottom: 2px solid var(--d-card-border);
        white-space: nowrap;
    }

    #tabla-inscripciones thead th:first-child {
        width: 40px;
        text-align: center;
    }

    #tabla-inscripciones tbody td {
        padding: 0.5rem 0.35rem;
        font-size: 0.75rem;
        color: var(--d-body);
        vertical-align: middle;
        border-bottom: 1px solid var(--d-card-border);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #tabla-inscripciones tbody td:first-child {
        text-align: center;
        width: 40px;
        padding-left: 0.2rem;
        padding-right: 0.2rem;
    }

    #tabla-inscripciones tbody td:nth-child(2) {
        max-width: none;
    }

    #tabla-inscripciones tbody td:nth-child(4) {
        max-width: 160px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    #tabla-inscripciones tbody tr {
        transition: background 0.15s;
    }

    #tabla-inscripciones tbody tr:hover {
        background: rgba(252, 123, 4, 0.04);
    }

    #tabla-inscripciones .inscrito-estado {
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.7rem;
        display: inline-block;
        min-width: 70px;
        text-align: center;
    }

    #tabla-inscripciones .inscrito-estado.inscrito {
        background: rgba(34, 197, 94, 0.12);
        color: #16a34a;
    }

    #tabla-inscripciones .inscrito-estado.pre-inscrito {
        background: rgba(245, 158, 11, 0.12);
        color: #d97706;
    }

    .moodle-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }

    .moodle-status.tiene-cuenta {
        background: rgba(34, 197, 94, 0.1);
        color: #16a34a;
    }

    .moodle-status.sin-cuenta {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* Botones de acción en tablas */
    #tabla-inscripciones .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 4px;
        border: none;
        background: rgba(252, 123, 4, 0.1);
        color: #fc7b04;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        padding: 0;
    }

    #tabla-inscripciones .action-btn i {
        font-size: 0.85rem;
    }

    #tabla-inscripciones .action-btn:hover {
        background: #fc7b04;
        color: #fff;
    }

    #tabla-inscripciones .action-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    #tabla-inscripciones .action-btn:disabled:hover {
        background: rgba(252, 123, 4, 0.1);
        color: #fc7b04;
    }

    #tabla-inscripciones .d-flex {
        display: flex;
    }

    #tabla-inscripciones .gap-1 {
        gap: 0.25rem;
    }


    .btn-whatsapp-moodle {
        color: #25d366;
        background: rgba(37, 211, 102, 0.1);
        border: 1px solid rgba(37, 211, 102, 0.3);
    }
    .btn-whatsapp-moodle:hover {
        background: #25d366;
        color: #fff;
    }
    /* =========================================================
       HEADER BAR COMPARTIDO (todos los tabs)
       ========================================================= */
    .tab-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--d-card-border);
    }

    .tab-section-header-left {
        display: flex;
        align-items: center;
        gap: .875rem;
    }

    .tab-section-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .mod-icon-color { background: linear-gradient(135deg,var(--brand-color),color-mix(in srgb,var(--brand-color) 75%,#fff)); box-shadow:0 4px 12px color-mix(in srgb,var(--brand-color) 25%,transparent); }
    .con-icon-color { background: linear-gradient(135deg,var(--brand-color),color-mix(in srgb,var(--brand-color) 75%,#fff)); box-shadow:0 4px 12px color-mix(in srgb,var(--brand-color) 25%,transparent); }
    .fin-icon-color { background: linear-gradient(135deg,#0891b2,#06b6d4); box-shadow:0 4px 12px rgba(8,145,178,.25); }

    .tab-section-title {
        font-size: .92rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }

    .tab-section-sub {
        font-size: .72rem;
        color: #64748b;
        margin-top: .1rem;
    }

    .tab-section-stats {
        display: flex;
        gap: 1rem;
    }

    .tab-section-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: .4rem .9rem;
        background: white;
        border: 1px solid var(--d-card-border);
        border-radius: 10px;
        min-width: 60px;
    }

    .tab-stat-value {
        font-size: 1.1rem;
        font-weight: 800;
        color: #fc7b04;
        line-height: 1;
    }

    .tab-stat-label {
        font-size: .65rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-top: .1rem;
    }

    .tab-section-action-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .45rem 1rem;
        font-size: .8rem;
        font-weight: 600;
        border: 1px solid rgba(var(--brand-color-rgb),.3);
        border-radius: 20px;
        background: rgba(var(--brand-color-rgb),.08);
        color: var(--brand-color);
        cursor: pointer;
        transition: all .2s;
    }

    .tab-section-action-btn:hover {
        background: var(--brand-color);
        color: white;
        border-color: var(--brand-color);
    }

    /* =========================================================
       MÓDULOS SIDEBAR — MEJORAS
       ========================================================= */


    /* =========================================================
       ÁREA CONTABLE — MEJORAS
       ========================================================= */
    #tab-contable {
        padding: 0;
    }

    .contable-cards-wrapper {
        padding: 1.25rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* =========================================================
       FINANZAS — TABLA REDISEÑADA
       ========================================================= */
    #tab-finanzas { padding: 0; }

    /* ── Wrapper card ── */
    .fin-table-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15,23,42,.06), 0 4px 16px rgba(15,23,42,.04);
    }

    /* ── Header ── */
    .fin-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.1rem 1.4rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid #e9eef5;
    }

    .fin-table-header-left {
        display: flex;
        align-items: center;
        gap: .65rem;
    }

    .fin-table-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(252,123,4,.12);
        color: #fc7b04;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .fin-table-title {
        font-size: .9rem;
        font-weight: 700;
        color: #0f172a;
    }

    /* ── Tabs pill ── */
    .fin-tabs {
        display: flex;
        gap: .25rem;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        padding: .28rem;
    }

    .fin-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .32rem .9rem !important;
        font-size: .75rem !important;
        font-weight: 600 !important;
        border-radius: 18px !important;
        border: none !important;
        color: #64748b !important;
        background: transparent !important;
        transition: all .2s;
    }

    .fin-tab-btn.active {
        background: linear-gradient(135deg,#fc7b04,#ea580c) !important;
        color: white !important;
        box-shadow: 0 2px 10px rgba(252,123,4,.32);
    }

    .fin-tab-btn:hover:not(.active) {
        background: #f1f5f9 !important;
        color: #334155 !important;
    }

    .fin-tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        border-radius: 10px;
        font-size: .65rem;
        font-weight: 700;
    }

    .fin-tab-btn.active .fin-tab-count      { background: rgba(255,255,255,.28); }
    .fin-tab-btn:not(.active) .fin-tab-count { background: #e2e8f0; color: #64748b; }

    /* ── Table base ── */
    .fin-tbl {
        font-size: .78rem !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }

    /* ── Group header row ── */
    .fin-tbl thead tr.fin-thead-group th {
        padding: .38rem .75rem .22rem !important;
        font-size: .62rem !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #94a3b8 !important;
        background: #f8fafc !important;
        border: none !important;
        border-bottom: none !important;
    }

    .fin-tbl thead tr.fin-thead-group th.fin-th-group-conceptos {
        background: rgba(99,102,241,.05) !important;
        color: #6366f1 !important;
        border-left: 2px solid rgba(99,102,241,.15) !important;
        border-right: 2px solid rgba(99,102,241,.15) !important;
    }

    .fin-tbl thead tr.fin-thead-group th.fin-th-group-totales {
        background: rgba(5,150,105,.05) !important;
        color: #059669 !important;
        border-left: 2px solid rgba(5,150,105,.15) !important;
        border-right: 2px solid rgba(5,150,105,.15) !important;
    }

    /* ── Column header row ── */
    .fin-tbl thead tr.fin-thead-cols {
        background: linear-gradient(135deg, #f1f5f9, #e9eef5);
        border-bottom: 2px solid #dde3ec;
    }

    .fin-tbl thead tr.fin-thead-cols th {
        padding: .65rem .8rem !important;
        font-size: .66rem !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #475569 !important;
        white-space: nowrap;
        border-top: none !important;
        border-bottom: 2px solid #dde3ec !important;
        border-left: none !important;
        border-right: none !important;
    }

    .fin-tbl thead tr.fin-thead-cols th.fin-th-conceptos-col {
        background: rgba(99,102,241,.05);
        border-left: 2px solid rgba(99,102,241,.12) !important;
        border-right: 2px solid rgba(99,102,241,.12) !important;
    }

    .fin-tbl thead tr.fin-thead-cols th.fin-th-totales-col {
        background: rgba(5,150,105,.04);
        border-left: 2px solid rgba(5,150,105,.1) !important;
    }

    .fin-th-num { width: 40px; }

    /* ── Body rows ── */
    .fin-row {
        border-bottom: 1px solid #f1f5f9;
        transition: background .15s, box-shadow .15s;
    }

    .fin-row:last-child { border-bottom: none; }

    .fin-row:hover {
        background: #fefaf7;
        box-shadow: inset 3px 0 0 #fc7b04;
    }

    .fin-row td {
        padding: .72rem .8rem !important;
        vertical-align: middle;
        border: none !important;
    }

    .fin-row td.fin-td-conceptos-col {
        background: rgba(99,102,241,.025);
        border-left: 2px solid rgba(99,102,241,.08) !important;
        border-right: 2px solid rgba(99,102,241,.08) !important;
    }

    .fin-row td.fin-td-totales-col {
        background: rgba(5,150,105,.025);
        border-left: 2px solid rgba(5,150,105,.07) !important;
    }

    .fin-row:hover td.fin-td-conceptos-col { background: rgba(99,102,241,.04); }
    .fin-row:hover td.fin-td-totales-col   { background: rgba(5,150,105,.04); }

    .fin-td-num {
        color: #cbd5e1;
        font-weight: 700;
        font-size: .72rem;
    }

    /* ── Student cell ── */
    .fin-student-cell {
        display: flex;
        align-items: center;
        gap: .7rem;
        min-width: 190px;
    }

    .fin-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .72rem;
        font-weight: 800;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,.14);
    }

    .fin-student-name {
        font-weight: 600;
        color: #1e293b;
        font-size: .8rem;
        text-decoration: none;
        display: block;
        line-height: 1.3;
        transition: color .15s;
    }

    .fin-student-name:hover { color: #fc7b04; text-decoration: underline; }

    .fin-ci-badge {
        display: inline-flex;
        align-items: center;
        gap: .22rem;
        background: rgba(16,185,129,.1);
        color: #059669;
        font-size: .65rem;
        font-weight: 700;
        padding: .1rem .5rem;
        border-radius: 20px;
        margin-top: .14rem;
    }

    .fin-td-estudiante { min-width: 210px; }

    /* ── Plan badge ── */
    .fin-plan-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        background: rgba(252,123,4,.1);
        color: #9a3f00;
        border: 1px solid rgba(252,123,4,.2);
        font-size: .68rem;
        font-weight: 600;
        padding: .22rem .65rem;
        border-radius: 20px;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ── Misc ── */
    .fin-link  { color: #0284c7; font-weight: 600; text-decoration: none; font-size: .74rem; }
    .fin-link:hover { color: #0369a1; text-decoration: underline; }
    .fin-muted { color: #94a3b8; font-size: .74rem; }
    .fin-fecha { color: #64748b; white-space: nowrap; font-size: .73rem; font-variant-numeric: tabular-nums; }

    /* ── Amount cells ── */
    .fin-amount-total {
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        font-size: .8rem;
    }

    .fin-amount-paid {
        font-family: 'Sora','DM Sans',sans-serif;
        font-weight: 700;
        color: #059669;
        white-space: nowrap;
        font-size: .82rem;
    }

    .fin-amount-saldo {
        font-family: 'Sora','DM Sans',sans-serif;
        font-weight: 700;
        color: #dc2626;
        white-space: nowrap;
        font-size: .82rem;
    }

    /* ── Concepto cells ── */
    .fin-concepto-cell {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: .15rem;
    }

    .fin-concepto-amount {
        font-weight: 700;
        font-size: .76rem;
        white-space: nowrap;
        line-height: 1.2;
    }

    .fin-concepto-amount.ok     { color: #059669; }
    .fin-concepto-amount.parcial{ color: #d97706; }

    .fin-concepto-sub {
        font-size: .64rem;
        white-space: nowrap;
        line-height: 1;
    }

    .fin-concepto-sub.saldo    { color: #dc2626; }
    .fin-concepto-sub.pagado-ok{ color: #059669; opacity: .7; }

    .fin-chip {
        display: inline-flex;
        align-items: center;
        gap: .2rem;
        font-size: .6rem;
        font-weight: 700;
        padding: .1rem .45rem;
        border-radius: 10px;
    }

    .fin-chip-ok   { background: rgba(5,150,105,.1);   color: #059669; }
    .fin-chip-pend { background: rgba(217,119,6,.1);   color: #b45309; }
    .fin-chip-zero { background: rgba(148,163,184,.1); color: #94a3b8; }

    /* ── Progress + % combined ── */
    .fin-avance-cell {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .28rem;
        min-width: 80px;
    }

    .fin-pct-badge {
        font-family: 'Sora','DM Sans',sans-serif;
        font-size: .72rem;
        font-weight: 800;
        padding: .18rem .55rem;
        border-radius: 20px;
        white-space: nowrap;
        line-height: 1.3;
    }

    .fin-progress-wrap {
        width: 72px;
        height: 6px;
        background: #e9eef5;
        border-radius: 10px;
        overflow: hidden;
    }

    .fin-progress-bar {
        height: 100%;
        border-radius: 10px;
        transition: width .8s ease;
    }

    /* ── Empty state ── */
    .fin-empty-row {
        text-align: center;
        padding: 3.5rem 1rem !important;
        color: #94a3b8;
        background: #fafbfc;
    }

    .fin-empty-row i {
        font-size: 2.8rem;
        opacity: .2;
        display: block;
        margin-bottom: .6rem;
    }

    .fin-empty-row p { margin: 0; font-size: .88rem; }

    /* =========================================================
       (estilos del tab información general movidos a bloque tgi-*)
       ========================================================= */

    /* Hero Card — legacy, no usado */
    .info-hero-card {
        background: linear-gradient(145deg, #ffffff, #fcfcfc);
        border: 1px solid var(--d-card-border);
        border-top: 5px solid #fc7b04; /* Fallback, inline override exists */
        border-radius: 16px;
        padding: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .info-hero-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(252, 123, 4, 0.06);
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    .hero-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--d-title);
        margin: 0 0 0.5rem 0;
        line-height: 1.2;
    }

    .hero-subtitle {
        font-size: 1rem;
        color: var(--d-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .hero-decoration {
        position: absolute;
        right: -20px;
        bottom: -30px;
        font-size: 10rem;
        line-height: 1;
        z-index: 1;
        transform: rotate(-15deg);
        pointer-events: none;
    }

    /* KPIs Grid */
    .info-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .info-kpi-card {
        background: var(--d-bg);
        border: 1px solid var(--d-card-border);
        border-radius: 14px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.25s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
    }

    .info-kpi-card:hover {
        border-color: rgba(252, 123, 4, 0.3);
        box-shadow: 0 8px 25px rgba(0,0,0,0.04);
        transform: translateY(-2px);
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .color-green { background: rgba(34, 197, 94, 0.1); color: #16a34a; }
    .color-orange { background: rgba(252, 123, 4, 0.1); color: #ea580c; }
    .color-blue { background: rgba(59, 130, 246, 0.1); color: #2563eb; }
    .color-purple { background: rgba(168, 85, 247, 0.1); color: #9333ea; }

    .kpi-details {
        display: flex;
        flex-direction: column;
    }

    .kpi-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.6px;
        color: var(--d-muted);
        margin-bottom: 0.25rem;
    }

    .kpi-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--d-body);
        line-height: 1.2;
    }

    /* Layout 2 Columnas */
    .info-secondary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
    }

    .info-content-card {
        background: var(--d-bg);
        border: 1px solid var(--d-card-border);
        border-radius: 16px;
        padding: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
    }

    .card-header-styled {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--d-title);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px dashed var(--d-card-border);
        padding-bottom: 0.75rem;
    }

    .card-header-styled i {
        color: #fc7b04;
        font-size: 1.2rem;
    }

    /* Timelines Refinadas */
    .elegant-timeline {
        padding: 0.5rem 0 !important;
        margin: 0 !important;
    }

    .elegant-timeline::before {
        top: 2rem !important;
        height: 3px !important;
        opacity: 0.7;
    }

    .elegant-timeline .fecha-value {
        font-size: 0.85rem !important;
        padding: 0.4rem 0.8rem !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    /* Stats Académicos */
    .academic-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .academic-stat {
        background: rgba(252, 123, 4, 0.04);
        border: 1px solid rgba(252, 123, 4, 0.1);
        border-radius: 12px;
        padding: 0.6rem;
        text-align: center;
        transition: all 0.2s ease;
    }

    .academic-stat:hover {
        background: rgba(252, 123, 4, 0.08);
        border-color: rgba(252, 123, 4, 0.2);
    }

    .ac-value {
        display: block;
        font-size: 1.2rem;
        font-weight: 800;
        color: #fc7b04;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .ac-value small {
        font-size: 0.8rem;
        opacity: 0.7;
    }

    .ac-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--d-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Responsables */
    .responsibles-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .elegant-resp-card {
        padding: 0.5rem 0.75rem !important;
        border-radius: 10px !important;
        margin: 0 !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .elegant-resp-card .responsible-avatar {
        width: 36px;
        height: 36px;
        font-size: 1rem;
    }

    .bg-gradient-blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important; }
    .bg-gradient-orange { background: linear-gradient(135deg, #fc7b04, #ea580c) !important; }

    /* Documentos */
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .doc-card {
        display: flex;
        flex-direction: row;
        align-items: center;
        background: var(--d-bg);
        border: 1px solid var(--d-card-border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.25s ease;
    }

    .doc-card:hover {
        border-color: rgba(252, 123, 4, 0.3);
        box-shadow: 0 6px 15px rgba(0,0,0,0.03);
        transform: translateY(-2px);
    }

    .doc-preview {
        height: 60px;
        width: 60px;
        flex-shrink: 0;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid var(--d-card-border);
    }

    .doc-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .doc-icon {
        font-size: 1.5rem;
        color: #ef4444;
        opacity: 0.8;
    }

    .doc-info {
        padding: 0.5rem 0.75rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        text-align: left;
        align-items: flex-start;
    }

    .doc-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--d-body);
        margin-bottom: 0.25rem;
    }

    .doc-link {
        font-size: 0.7rem;
        color: #fff;
        background: #fc7b04;
        padding: 0.25rem 0.6rem;
        border-radius: 15px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: background 0.2s;
    }

    .doc-link:hover {
        background: #e06c03;
        color: #fff;
    }

    .empty-state-mini {
        background: #f8fafc;
        border: 1px dashed var(--d-card-border);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        font-size: 0.8rem;
        color: var(--d-muted);
        font-style: italic;
    }

    .doc-empty {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-secondary-grid {
            grid-template-columns: 1fr;
        }
        
        .info-hero-card {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem;
        }

        .hero-subtitle {
            justify-content: center;
        }

        .hero-decoration {
            display: none;
        }
    }
    /* Estilos para Modal Detalle de Horario */
    .horario-detail-container {
        display: flex;
        flex-direction: column;
    }
    .horario-detail-field {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .hdf-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .hdf-icon-lg {
        width: 52px;
        height: 52px;
        font-size: 1.5rem;
    }
    .hdf-content {
        flex: 1;
    }
    .hdf-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--d-muted);
        margin-bottom: 0.15rem;
    }
    .hdf-value {
        font-size: 0.95rem;
        color: var(--d-title);
    }
    .modal-header-gradient {
        background: linear-gradient(135deg, #391b04 0%, #c96004 100%) !important;
        border-bottom: none;
    }
    .modal-header-modulo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* =========================================================
       TAB INSCRIPCIONES — NUEVO DISEÑO
       ========================================================= */
    #tab-inscripciones {
        padding: 0;
    }

    .ins-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--d-card-border);
        border-radius: 0;
    }

    .ins-header-left {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .ins-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #9a4904, #fc7b04);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(252,123,4,.25);
    }

    .ins-header-title {
        font-size: .92rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }

    .ins-header-sub {
        font-size: .72rem;
        color: #64748b;
        margin-top: .1rem;
    }

    .ins-header-right {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .ins-btn-cuentas {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .45rem 1rem;
        font-size: .8rem;
        font-weight: 600;
        border: none;
        border-radius: 20px;
        background: linear-gradient(135deg, #fc7b04, #d46604);
        color: white;
        cursor: pointer;
        transition: all .2s;
        box-shadow: 0 2px 8px rgba(252,123,4,.25);
    }

    .ins-btn-cuentas:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(252,123,4,.35);
    }

    .ins-filter-group {
        display: flex;
        gap: .3rem;
        background: white;
        border: 1px solid var(--d-card-border);
        border-radius: 20px;
        padding: .25rem;
    }

    .ins-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .3rem .8rem;
        font-size: .75rem;
        font-weight: 600;
        border: none;
        border-radius: 16px;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        transition: all .2s;
    }

    .ins-filter-btn.active {
        background: linear-gradient(135deg, rgba(252,123,4,.15), rgba(252,123,4,.08));
        color: #c05e00;
    }

    .ins-filter-btn:hover:not(.active) {
        background: #f1f5f9;
        color: #334155;
    }

    /* States */
    .ins-state-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3.5rem 1rem;
        gap: .5rem;
    }

    .ins-spinner {
        color: #fc7b04;
        width: 1.75rem;
        height: 1.75rem;
    }

    .ins-state-text {
        color: #94a3b8;
        font-size: .85rem;
        margin: 0;
    }

    .ins-empty-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(252,123,4,.08);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: #fc7b04;
        margin-bottom: .25rem;
    }

    /* Table */
    .ins-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .ins-tbl {
        width: 100%;
        border-collapse: collapse;
        font-size: .78rem;
    }

    .ins-tbl thead tr {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        border-bottom: none;
    }

    .ins-tbl thead th {
        padding: .7rem .875rem;
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #f1f5f9;
        white-space: nowrap;
    }

    .ins-th-num {
        width: 40px;
        text-align: center;
    }

    .ins-tbl tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background .12s;
    }

    .ins-tbl tbody tr:last-child {
        border-bottom: none;
    }

    .ins-tbl tbody tr:hover {
        background: rgba(252,123,4,.03);
    }

    .ins-tbl tbody td {
        padding: .65rem .875rem;
        color: #334155;
        vertical-align: middle;
    }

    /* Student cell */
    .ins-student-cell {
        display: flex;
        align-items: center;
        gap: .65rem;
        min-width: 180px;
    }

    .ins-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .72rem;
        font-weight: 700;
        color: white;
        flex-shrink: 0;
        background: linear-gradient(135deg, #9a4904, #fc7b04);
        box-shadow: 0 2px 6px rgba(154,73,4,.25);
    }

    .ins-student-name {
        font-weight: 600;
        color: #1e293b;
        font-size: .8rem;
        text-decoration: none;
        line-height: 1.3;
        display: block;
    }

    .ins-student-name:hover {
        color: #fc7b04;
    }

    .ins-ci-badge {
        display: inline-flex;
        align-items: center;
        gap: .2rem;
        background: rgba(252,123,4,.1);
        color: #9a4904;
        font-size: .68rem;
        font-weight: 700;
        padding: .1rem .5rem;
        border-radius: 20px;
        margin-top: .15rem;
    }

    /* Status badges */
    .ins-estado-badge {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        padding: .25rem .7rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .ins-estado-badge.inscrito {
        background: rgba(34,197,94,.12);
        color: #16a34a;
    }

    .ins-estado-badge.pre-inscrito {
        background: rgba(245,158,11,.12);
        color: #b45309;
    }

    .ins-plan-badge {
        display: inline-block;
        background: rgba(252,123,4,.1);
        color: #9a4904;
        font-size: .7rem;
        font-weight: 600;
        padding: .2rem .6rem;
        border-radius: 20px;
        max-width: 130px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Account chips */
    .ins-acc-chip {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        padding: .2rem .55rem;
        border-radius: 20px;
        font-size: .7rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .ins-acc-chip.activa {
        background: rgba(34,197,94,.1);
        color: #16a34a;
    }

    .ins-acc-chip.sin {
        background: rgba(239,68,68,.08);
        color: #dc2626;
    }

    /* Action buttons */
    .ins-actions {
        display: flex;
        gap: .3rem;
        justify-content: center;
        align-items: center;
    }

    .ins-action-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .82rem;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
    }

    .ins-action-btn:hover {
        background: #fc7b04;
        border-color: #fc7b04;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(252,123,4,.3);
    }

    .ins-action-btn.wa:hover {
        background: #25d366;
        border-color: #25d366;
        box-shadow: 0 3px 8px rgba(37,211,102,.3);
    }

    .ins-action-btn.upgrade:hover {
        background: #fc7b04;
        border-color: #fc7b04;
        box-shadow: 0 3px 8px rgba(252,123,4,.3);
    }

    .ins-action-btn:disabled {
        opacity: .3;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* =========================================================
       TAB PLATAFORMA MOODLE — NUEVO DISEÑO
       ========================================================= */
    #tab-plataforma {
        padding: 0;
    }

    .plt-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--d-card-border);
    }

    .plt-header-left {
        display: flex;
        align-items: center;
        gap: .875rem;
    }

    .plt-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #c05e00, #fc7b04);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(252,123,4,.3);
    }

    .plt-header-title {
        font-size: .92rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }

    .plt-header-sub {
        font-size: .72rem;
        color: #64748b;
        margin-top: .1rem;
    }

    .plt-btn-refresh {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .45rem 1rem;
        font-size: .8rem;
        font-weight: 600;
        border: 1px solid rgba(252,123,4,.3);
        border-radius: 20px;
        background: rgba(252,123,4,.08);
        color: #c05e00;
        cursor: pointer;
        transition: all .2s;
    }

    .plt-btn-refresh:hover {
        background: #fc7b04;
        color: white;
        border-color: #fc7b04;
    }

    /* Legend */
    .plt-legend {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
        padding: .75rem 1.5rem;
        border-bottom: 1px solid var(--d-card-border);
        background: white;
    }

    .plt-chip {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .2rem .7rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .plt-chip-pagado   { background: rgba(34,197,94,.1);  color: #16a34a; border-color: rgba(34,197,94,.25); }
    .plt-chip-vencida  { background: rgba(239,68,68,.1);  color: #dc2626; border-color: rgba(239,68,68,.25); }
    .plt-chip-pendiente{ background: rgba(245,158,11,.1); color: #d97706; border-color: rgba(245,158,11,.25); }
    .plt-chip-sin      { background: rgba(100,116,139,.1);color: #64748b; border-color: rgba(100,116,139,.2); }
    .plt-chip-bloqueado{ background: rgba(185,28,28,.1);  color: #b91c1c; border-color: rgba(185,28,28,.25); }

    /* Table wrapper */
    .plt-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 1.25rem 1.5rem;
    }

    .plt-tbl {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: .8rem;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        min-width: 480px;
    }

    .plt-tbl thead tr {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }

    .plt-tbl thead th {
        padding: .65rem .75rem;
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
        vertical-align: bottom;
    }

    /* Columna "Estudiante" — sticky, no se trunca */
    .plt-tbl thead th:first-child {
        position: sticky;
        left: 0;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        z-index: 2;
        min-width: 190px;
        white-space: nowrap;
    }

    /* Columnas de módulo — compactas con wrap */
    .plt-tbl thead th:not(:first-child) {
        text-align: center;
        min-width: 100px;
        max-width: 130px;
        width: 115px;
        white-space: normal;
        word-break: break-word;
        line-height: 1.35;
        hyphens: auto;
    }

    /* Separador visual entre nombre módulo y alerta sin-curso */
    .plt-tbl thead th .plt-th-alert {
        display: block;
        font-size: .62rem;
        font-weight: 500;
        color: #ef4444;
        margin-top: .3rem;
        white-space: nowrap;
        text-transform: none;
        letter-spacing: 0;
    }

    .plt-tbl tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background .12s;
    }

    .plt-tbl tbody tr:hover {
        background: rgba(252,123,4,.025);
    }

    .plt-tbl tbody tr:last-child {
        border-bottom: none;
    }

    .plt-tbl tbody td {
        padding: .6rem .75rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .plt-tbl tbody td:first-child {
        position: sticky;
        left: 0;
        background: white;
        z-index: 1;
        border-right: 1px solid #e2e8f0;
        box-shadow: 2px 0 6px rgba(0,0,0,.04);
    }

    .plt-tbl tbody tr:hover td:first-child {
        background: rgba(252,123,4,.025);
    }

    /* Celdas de módulo — centrado y padding reducido */
    .plt-tbl tbody td:not(:first-child) {
        text-align: center;
        padding: .5rem .5rem;
    }

    /* Student cell in plataforma */
    .plt-student-cell {
        display: flex;
        align-items: center;
        gap: .6rem;
        min-width: 185px;
    }

    .plt-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #c05e00, #fc7b04);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .68rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .plt-student-name {
        font-weight: 600;
        font-size: .82rem;
        color: #1e293b;
        white-space: nowrap;
    }

    .plt-no-moodle {
        font-size: .68rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: .2rem;
        margin-top: .15rem;
    }

    /* Status cells */
    .plt-cell-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .35rem;
    }

    .plt-status-pill {
        display: inline-flex;
        align-items: center;
        gap: .2rem;
        padding: .18rem .5rem;
        border-radius: 20px;
        font-size: .68rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .plt-status-pagado   { background: rgba(34,197,94,.12);  color: #16a34a; }
    .plt-status-pendiente{ background: rgba(245,158,11,.12); color: #d97706; }
    .plt-status-vencida  { background: rgba(239,68,68,.12);  color: #dc2626; }
    .plt-status-bloqueado{ background: rgba(185,28,28,.12);  color: #b91c1c; }
    .plt-status-sin      { background: rgba(100,116,139,.08);color: #94a3b8; }

    .plt-monto {
        font-size: .68rem;
        color: #64748b;
        font-weight: 500;
    }

    .plt-fecha {
        font-size: .65rem;
        color: #94a3b8;
    }

    .plt-btn-accion {
        display: inline-flex;
        align-items: center;
        gap: .2rem;
        padding: .22rem .6rem;
        border-radius: 20px;
        border: none;
        font-size: .68rem;
        font-weight: 700;
        cursor: pointer;
        transition: all .15s;
        white-space: nowrap;
    }

    .plt-btn-bloquear {
        background: rgba(220,38,38,.1);
        color: #dc2626;
        border: 1px solid rgba(220,38,38,.25);
    }

    .plt-btn-bloquear:hover {
        background: #dc2626;
        color: white;
    }

    .plt-btn-habilitar {
        background: rgba(16,185,129,.1);
        color: #059669;
        border: 1px solid rgba(16,185,129,.25);
    }

    .plt-btn-habilitar:hover {
        background: #059669;
        color: white;
    }
</style>
