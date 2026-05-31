<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

:root {
    --est-primary: #fc7b04;
    --est-primary-light: rgba(252, 123, 4, 0.1);
    --est-primary-dark: #c96004;
    --est-accent: #fc7b04;
    --est-surface: #f8fafc;
    --est-border: #e2e8f0;
    --est-text: #1e293b;
    --est-text-muted: #64748b;
    --est-success: #22c55e;
    --est-success-light: rgba(34, 197, 94, 0.1);
    --est-danger: #ef4444;
    --est-danger-light: rgba(239, 68, 68, 0.1);
    --est-info: #0ea5e9;
    --est-info-light: rgba(14, 165, 233, 0.1);
    --est-warning: #f59e0b;
    --est-warning-light: rgba(245, 158, 11, 0.1);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
    --shadow-md: 0 4px 8px -2px rgba(0, 0, 0, .08), 0 2px 4px -2px rgba(0, 0, 0, .04);
}

/* ── Hero ─────────────────────────────────────────────────── */
.est-hero {
    background: linear-gradient(135deg, #391b04 0%, #5c2d0a 40%, #9a4904 75%, #c96004 100%);
    border-radius: 16px;
    padding: 1.75rem 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.75rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 28px rgba(57, 27, 4, 0.35), 0 2px 8px rgba(0, 0, 0, 0.12);
}

.est-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -8%;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 65%);
    border-radius: 50%;
    pointer-events: none;
}

.est-hero::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: 15%;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(252, 123, 4, 0.18) 0%, transparent 65%);
    border-radius: 50%;
    pointer-events: none;
}

.est-hero-avatar {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, .5);
    flex-shrink: 0;
    box-shadow: 0 4px 18px rgba(0, 0, 0, .3);
    position: relative;
    z-index: 1;
}

.est-hero > div {
    position: relative;
    z-index: 1;
}

.est-hero-name {
    font-family: 'Outfit', sans-serif;
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: .15rem;
    letter-spacing: -0.01em;
}

.est-hero-sub {
    font-size: .85rem;
    opacity: .85;
    display: flex;
    align-items: center;
    gap: .4rem;
}

.est-hero-badges {
    display: flex;
    gap: .5rem;
    margin-top: .65rem;
    flex-wrap: wrap;
}

.est-hero-badge {
    background: rgba(255, 255, 255, .16);
    border: 1px solid rgba(255, 255, 255, .22);
    border-radius: 20px;
    padding: .22rem .75rem;
    font-size: .72rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    backdrop-filter: blur(4px);
}

.est-hero-badge.sin {
    background: rgba(255, 80, 80, .22);
    border-color: rgba(255, 80, 80, .35);
}

.est-hero-badge.est-hero-role.estudiante {
    background: rgba(252, 123, 4, .25);
    border-color: rgba(252, 123, 4, .4);
}

.est-hero-badge.est-hero-role.docente {
    background: rgba(14, 165, 233, .22);
    border-color: rgba(14, 165, 233, .38);
}

.est-hero-badge.est-hero-role.ambos {
    background: rgba(34, 197, 94, .2);
    border-color: rgba(34, 197, 94, .35);
}

/* ── Role Switcher (below hero, only when ambos roles) ────── */
.rol-switcher {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.75rem;
    padding: .85rem 1.25rem;
    background: var(--vz-card-bg, #fff);
    border-radius: var(--radius-lg);
    border: 1px solid var(--est-border);
    box-shadow: var(--shadow-sm);
}

.rol-switcher-label {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--est-text-muted);
    flex-shrink: 0;
    white-space: nowrap;
}

.rol-switcher-btns {
    display: flex;
    gap: .6rem;
    flex: 1;
}

.rol-btn {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .6rem 1rem;
    background: var(--est-surface);
    border: 1.5px solid var(--est-border);
    border-radius: 10px;
    cursor: pointer;
    transition: all .2s;
    flex: 1;
    text-align: left;
    position: relative;
    overflow: hidden;
}

.rol-btn:hover:not(.active):not(:disabled) {
    border-color: var(--est-primary);
    background: rgba(252, 123, 4, .04);
}

.rol-btn.active {
    background: rgba(252, 123, 4, .07);
    border-color: var(--est-primary);
    box-shadow: 0 0 0 3px rgba(252, 123, 4, .1);
}

.rol-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
}

.rol-btn-icon {
    width: 38px;
    height: 38px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    background: var(--vz-card-bg, #fff);
    color: var(--est-text-muted);
    border: 1px solid var(--est-border);
    flex-shrink: 0;
    transition: all .2s;
}

.rol-btn.active .rol-btn-icon {
    background: var(--est-primary);
    color: #fff;
    border-color: var(--est-primary);
    box-shadow: 0 3px 8px rgba(252, 123, 4, .3);
}

.rol-btn-text {
    flex: 1;
    min-width: 0;
}

.rol-btn-title {
    display: block;
    font-size: .85rem;
    font-weight: 700;
    color: var(--est-text);
    line-height: 1.2;
}

.rol-btn-sub {
    display: block;
    font-size: .7rem;
    color: var(--est-text-muted);
    margin-top: .15rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.rol-btn.active .rol-btn-title {
    color: var(--est-primary-dark);
}

.rol-btn-check {
    margin-left: auto;
    flex-shrink: 0;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: var(--est-primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
}

.rol-btn-spinner {
    margin-left: auto;
    flex-shrink: 0;
    width: 18px;
    height: 18px;
    border: 2px solid rgba(252, 123, 4, .3);
    border-top-color: var(--est-primary);
    border-radius: 50%;
    animation: rolSpin .6s linear infinite;
    display: none;
}

@keyframes rolSpin {
    to { transform: rotate(360deg); }
}

.rol-btn.loading .rol-btn-spinner { display: block; }
.rol-btn.loading .rol-btn-check   { display: none; }

@media (max-width: 576px) {
    .rol-switcher { flex-direction: column; align-items: stretch; gap: .65rem; }
    .rol-switcher-label { text-align: center; }
    .rol-btn-sub { display: none; }
}

/* ── Stats bar ────────────────────────────────────────────── */
.est-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 1rem;
    margin-bottom: 1.75rem;
}

.est-stat-card-sm {
    background: #fff;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    border: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: .75rem;
}

html[data-bs-theme="dark"] .est-stat-card-sm {
    background: #1a1d21;
    border-color: #2c2e33;
}

.est-stat-icon-sm {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.est-stat-icon-sm.orange { background: rgba(252, 123, 4, .12); color: #fc7b04; }
.est-stat-icon-sm.blue { background: rgba(59, 130, 246, .12); color: #3b82f6; }
.est-stat-icon-sm.green { background: rgba(90, 138, 48, .12); color: #5a8a30; }

.est-stat-num { font-size: 1.4rem; font-weight: 700; line-height: 1; }
.est-stat-label { font-size: .72rem; color: #6c757d; margin-top: .1rem; }

/* ── Tabs card ────────────────────────────────────────────── */
.est-tabs-card {
    background: #fff;
    border-radius: var(--radius-lg);
    border: 1px solid var(--est-border);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    margin-bottom: 1.75rem;
}

.est-tabs-nav {
    display: flex;
    overflow-x: auto;
    scrollbar-width: none;
    background: var(--est-surface);
    border-bottom: 1px solid var(--est-border);
}

.est-tabs-nav::-webkit-scrollbar { display: none; }

.est-tab-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 16px 22px;
    font-size: .85rem;
    font-weight: 600;
    color: var(--est-text-muted);
    border: none;
    background: none;
    border-bottom: 3px solid transparent;
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s ease;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.est-tab-btn:hover:not(.active) {
    color: var(--est-primary);
    background: var(--est-primary-light);
}

.est-tab-btn.active {
    color: var(--est-primary);
    border-bottom-color: var(--est-primary);
    background: #fff;
}

.est-tab-btn i { font-size: 1.05rem; }

.est-tabs-body {
    padding: 24px;
    display: none;
}

.est-tabs-body.active { display: block; }

/* ── Contable ─────────────────────────────────────────────── */
.est-stat-card {
    background: #fff;
    border-radius: var(--radius-md);
    border: 1px solid var(--est-border);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: all .25s ease;
}

.est-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.est-stat-body {
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
}

.est-stat-value {
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: 1.2rem;
    line-height: 1.1;
}

.est-stat-label-sm {
    font-size: .72rem;
    color: var(--est-text-muted);
    margin: 0;
}

.est-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
}

.est-oferta-tabs-nav {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 8px;
    margin-bottom: 20px;
}

.est-oferta-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    font-size: .82rem;
    font-weight: 600;
    color: var(--est-text-muted);
    background: #fff;
    border: 1px solid var(--est-border);
    border-radius: var(--radius-sm);
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s ease;
}

.est-oferta-tab-btn:hover {
    border-color: var(--est-primary);
    color: var(--est-primary);
}

.est-oferta-tab-btn.active {
    background: var(--est-primary);
    color: white;
    border-color: var(--est-primary);
}

.est-oferta-content { display: none; }
.est-oferta-content.active { display: block; }

.est-data-card {
    background: #fff;
    border-radius: var(--radius-md);
    border: 1px solid var(--est-border);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.est-data-card-header {
    padding: 14px 18px;
    border-bottom: 1px solid var(--est-border);
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--est-surface);
}

.est-data-card-icon {
    width: 34px;
    height: 34px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
}

.est-data-card-title {
    font-family: 'Outfit', sans-serif;
    font-size: .9rem;
    font-weight: 600;
    margin: 0;
    color: var(--est-text);
}

.est-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.est-table thead th {
    background: var(--est-surface);
    padding: 12px 16px;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--est-text-muted);
    border-bottom: 1px solid var(--est-border);
}

.est-table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--est-border);
    vertical-align: middle;
    font-size: .85rem;
}

.est-table tbody tr:last-child td { border-bottom: none; }
.est-table tbody tr:hover td { background: var(--est-primary-light); }

.estado-badge-est {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: .7rem;
    font-weight: 600;
}

.estado-badge-est.pagado { background: var(--est-success-light); color: var(--est-success); }
.estado-badge-est.pendiente { background: var(--est-warning-light); color: var(--est-warning); }
.estado-badge-est.vencido { background: var(--est-danger-light); color: var(--est-danger); }

.est-empty-state {
    padding: 40px 24px;
    text-align: center;
    background: #fff;
    border-radius: var(--radius-lg);
    border: 1px solid var(--est-border);
}

.est-empty-state i {
    font-size: 2rem;
    color: #cbd5e1;
    margin-bottom: 12px;
}

.est-empty-state h5 {
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
}

.est-empty-state p {
    color: var(--est-text-muted);
    font-size: .85rem;
    margin: 0;
}

/* ── Académico ────────────────────────────────────────────── */
.est-section-title {
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #6c757d;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: .5rem;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: .4rem;
}

.est-section-title i { color: #fc7b04; }

.est-prog-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e9ecef;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.est-prog-header {
    background: linear-gradient(135deg, #1a1d21 0%, #2c2e33 100%);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.est-prog-header h6 {
    color: #fff;
    font-weight: 700;
    margin: 0;
    font-size: .95rem;
}

.est-prog-header small {
    color: rgba(255, 255, 255, .6);
    font-size: .72rem;
}

.est-estado-badge {
    padding: .25rem .7rem;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 700;
}

.est-estado-badge.inscrito { background: rgba(90, 138, 48, .2); color: #5a8a30; }
.est-estado-badge.pendiente { background: rgba(252, 123, 4, .15); color: #c96004; }
.est-estado-badge.otro { background: rgba(108, 117, 125, .15); color: #6c757d; }

.est-modulos-list { padding: 1rem 1.5rem; }

.est-modulo-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .85rem 0;
    border-bottom: 1px solid #f0f0f0;
    gap: 1rem;
    flex-wrap: wrap;
}

.est-modulo-item:last-child { border-bottom: none; }

.est-modulo-info { flex: 1; min-width: 0; }

.est-modulo-name {
    font-weight: 700;
    font-size: .9rem;
    margin-bottom: .2rem;
}

.est-modulo-meta {
    font-size: .75rem;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.est-modulo-meta span { display: flex; align-items: center; gap: .25rem; }

.est-btn-actividades {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    background: linear-gradient(135deg, #743c04, #c96004);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: .45rem 1rem;
    font-size: .8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
    text-decoration: none;
}

.est-btn-actividades:hover {
    opacity: .9;
    transform: translateY(-1px);
    color: #fff;
}

.est-act-panel {
    background: var(--vz-body-bg, #f3f3f9);
    border-top: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
    display: none;
}

.est-act-section {
    font-size: .8rem;
    font-weight: 700;
    color: #6c757d;
    text-transform: uppercase;
    margin: .75rem 0 .4rem;
}

.est-act-item {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: .55rem .9rem;
    margin-bottom: .4rem;
    gap: .75rem;
}

/* Columna 1: ícono + nombre + tipo */
.est-act-item-name {
    font-size: .85rem;
    font-weight: 600;
    flex: 0 0 auto;
    min-width: 160px;
    max-width: 260px;
}

.est-act-item-name small {
    display: block;
    font-size: .7rem;
    font-weight: 400;
    color: #6c757d;
}

/* Columna 2: fechas */
.est-act-item-dates {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: .2rem;
    border-left: 2px solid #e9ecef;
    padding-left: .75rem;
    justify-content: center;
}

/* Columna 3: nota + botón */
.est-act-item-actions {
    display: flex;
    align-items: center;
    gap: .5rem;
    flex-shrink: 0;
}

.est-nota-badge {
    padding: .2rem .6rem;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 700;
    white-space: nowrap;
}

.est-nota-badge.aprobado { background: rgba(90, 138, 48, .12); color: #5a8a30; }
.est-nota-badge.reprobado { background: rgba(220, 53, 69, .12); color: #dc3545; }
.est-nota-badge.pendiente { background: rgba(108, 117, 125, .1); color: #6c757d; }

/* ── Fechas de actividades (misma estructura que modulo-detalle admin) ── */
.act-dates-row {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem;
    margin-top: 4px;
}
.act-date-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 600;
    white-space: nowrap;
    border: 1px solid transparent;
}
.act-date-open {
    background: rgba(14,165,233,.1);
    color: #0369a1;
    border-color: rgba(14,165,233,.3);
}
.act-date-open.act-date-active {
    background: rgba(34,197,94,.1);
    color: #15803d;
    border-color: rgba(34,197,94,.3);
}
.act-date-due {
    background: rgba(252,123,4,.1);
    color: #c96004;
    border-color: rgba(252,123,4,.3);
}
.act-date-due.act-date-overdue {
    background: rgba(239,68,68,.1);
    color: #dc2626;
    border-color: rgba(239,68,68,.3);
}

.est-spinner {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .85rem;
    color: #6c757d;
    padding: .75rem 0;
}

.est-label-content {
    padding: .75rem 0;
    border-bottom: 1px solid #f0f0f0;
    line-height: 1.7;
    font-size: .9rem;
    word-break: break-word;
}

.est-label-content:last-child { border-bottom: none; }

.est-label-content img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    margin: .35rem 0;
    display: block;
}

.est-label-content p { margin-bottom: .5rem; }
.est-label-content p:last-child { margin-bottom: 0; }
.est-label-content ul, .est-label-content ol { padding-left: 1.5rem; margin-bottom: .5rem; }
.est-label-content li { margin-bottom: .2rem; }
.est-label-content a { color: #fc7b04; text-decoration: underline; }
.est-label-content a:hover { color: #c96004; }

.est-no-cuenta {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e9ecef;
    padding: 2.5rem;
    text-align: center;
}

.est-no-cuenta i { font-size: 3rem; color: #adb5bd; }
.est-no-cuenta h5 { margin: .75rem 0 .4rem; font-weight: 700; }
.est-no-cuenta p { font-size: .875rem; color: #6c757d; max-width: 380px; margin: 0 auto; }

.text-orange { color: #fc7b04; }

/* ── Personal – Carnet ────────────────────────────────────── */
.est-ci-wrap {
    background: #fff;
    border: 1.5px solid var(--est-border);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 6px 30px rgba(0, 0, 0, .07);
    position: relative;
}

.est-ci-stripe {
    height: 5px;
    background: linear-gradient(90deg, #391b04 0%, #9a4904 35%, #fc7b04 65%, #9a4904 100%);
}

.est-ci-body {
    display: grid;
    grid-template-columns: 220px 1fr 280px;
    gap: 0;
}

.est-ci-left {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .85rem;
    padding: 1.5rem 1rem 1.25rem;
    background: linear-gradient(180deg, #9a4904 0%, #5a2800 100%);
}

.est-ci-foto-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, .75);
}

.est-ci-foto {
    width: 140px;
    height: 175px;
    border-radius: 10px;
    border: 3px solid rgba(255, 255, 255, .45);
    background: rgba(255, 255, 255, .12);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0, 0, 0, .3);
    flex-shrink: 0;
}

.est-ci-foto img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.est-ci-initials {
    font-family: 'Outfit', sans-serif;
    font-size: 2.6rem;
    font-weight: 800;
    color: rgba(255, 255, 255, .7);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.est-ci-quick-data {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: .28rem;
}

.est-ci-qd-item {
    display: grid;
    grid-template-columns: 14px auto 1fr;
    align-items: center;
    gap: .35rem;
    font-size: .7rem;
    padding: .28rem .4rem;
    background: rgba(255, 255, 255, .11);
    border-radius: 6px;
    color: rgba(255, 255, 255, .9);
}

.est-ci-qd-item i {
    color: rgba(255, 255, 255, .65);
    font-size: .82rem;
}

.est-ci-qd-label {
    color: rgba(255, 255, 255, .58);
    font-size: .63rem;
    text-transform: uppercase;
    letter-spacing: .03em;
}

.est-ci-qd-val {
    color: #fff;
    font-weight: 600;
    text-align: right;
    font-size: .72rem;
}

.est-ci-center {
    display: flex;
    flex-direction: column;
    padding: 1.4rem 1.25rem 1.25rem;
    border-right: 1.5px solid var(--est-border);
}

.est-ci-nombre-wrap {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: .6rem;
    margin-bottom: 1rem;
    padding-bottom: .85rem;
    border-bottom: 1.5px solid var(--est-border);
    flex-wrap: wrap;
}

.est-ci-nombre {
    font-family: 'Outfit', sans-serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--est-text);
    line-height: 1.2;
}

.est-ci-estado-label {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: .78rem;
    color: var(--est-primary);
    font-weight: 600;
    margin-top: 4px;
}

.est-ci-estado-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: .25rem .65rem;
    border-radius: 20px;
    font-size: .68rem;
    font-weight: 700;
    white-space: nowrap;
    align-self: flex-start;
}

.est-ci-badge-activo {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #86efac;
}

.est-ci-badge-inactivo {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fca5a5;
}

.est-ci-section-title {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--est-text-muted);
    margin-bottom: .65rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.est-ci-section-title i {
    color: var(--est-accent);
}

.est-ci-section-title.mt-4 {
    margin-top: 1rem;
}

.est-ci-datos-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: .65rem;
}

.est-ci-dato {
    display: flex;
    flex-direction: column;
    gap: .1rem;
}

.est-ci-dato.est-ci-full {
    grid-column: 1 / -1;
}

.est-ci-label {
    font-size: .62rem;
    color: var(--est-text-muted);
    text-transform: uppercase;
    letter-spacing: .05em;
    font-weight: 600;
}

.est-ci-value {
    font-size: .86rem;
    font-weight: 500;
    color: var(--est-text);
}

.est-ci-right {
    display: flex;
    flex-direction: column;
    padding: 1.25rem 1rem 1.25rem;
    background: var(--est-surface);
    gap: .75rem;
}

.est-ci-right-header {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--est-text-muted);
    padding-bottom: .7rem;
    border-bottom: 1.5px solid var(--est-border);
}

.est-ci-right-header i {
    color: var(--est-accent);
    font-size: 1rem;
}

.est-ci-account-list {
    display: flex;
    flex-direction: column;
    gap: .5rem;
    flex: 1;
}

.est-ci-acc-item {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .45rem .55rem;
    background: white;
    border: 1px solid var(--est-border);
    border-radius: 8px;
}

.est-ci-acc-icon {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: var(--est-primary-light);
    color: var(--est-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    flex-shrink: 0;
}

.est-ci-acc-label {
    font-size: .62rem;
    color: var(--est-text-muted);
    text-transform: uppercase;
    letter-spacing: .04em;
    font-weight: 600;
}

.est-ci-acc-value {
    font-size: .82rem;
    font-weight: 600;
    color: var(--est-text);
    word-break: break-all;
}

.est-ci-bottom-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .5rem 1.25rem;
    background: linear-gradient(90deg, #391b04 0%, #9a4904 60%, #bc5404 100%);
    color: rgba(255, 255, 255, .8);
    font-size: .68rem;
    font-weight: 600;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.est-ci-bottom-bar i {
    margin-right: 4px;
}

html[data-bs-theme="dark"] .est-ci-wrap {
    background: #1a1d21;
    border-color: #2c2e33;
}

html[data-bs-theme="dark"] .est-ci-center {
    border-color: #2c2e33;
}

html[data-bs-theme="dark"] .est-ci-nombre-wrap {
    border-color: #2c2e33;
}

html[data-bs-theme="dark"] .est-ci-nombre {
    color: #e2e8f0;
}

html[data-bs-theme="dark"] .est-ci-right {
    background: #1a1d21;
}

html[data-bs-theme="dark"] .est-ci-right-header {
    border-color: #2c2e33;
}

html[data-bs-theme="dark"] .est-ci-acc-item {
    background: #1a1d21;
    border-color: #2c2e33;
}

html[data-bs-theme="dark"] .est-ci-label,
html[data-bs-theme="dark"] .est-ci-value,
html[data-bs-theme="dark"] .est-ci-dato {
    color: #94a3b8;
}

/* ── Responsive ───────────────────────────────────────────── */
@media (max-width: 991px) {
    .est-ci-body { grid-template-columns: 180px 1fr; grid-template-rows: auto auto; }
    .est-ci-right { grid-column: 1 / -1; border-top: 1.5px solid var(--est-border); }
}

@media (max-width: 767px) {
    .est-tabs-body { padding: 16px; }
    .est-tab-btn { padding: 12px 14px; font-size: .78rem; }
}

/* ── Docente Módulos Activos Mejorado ──────────────────────────── */
.docente-modulos-section {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-top: 24px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}

.docente-modulos-header {
    background: linear-gradient(135deg, #1e293b 0%, #2c3036 100%);
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.docente-modulos-header-left { display: flex; align-items: center; gap: 12px; }

.docente-modulos-header-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(252, 123, 4, 0.2) 0%, rgba(252, 123, 4, 0.05) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fc7b04;
    font-size: 1.2rem;
}

.docente-modulos-header-text h4 {
    font-family: 'Outfit', sans-serif;
    font-size: .95rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
}

.docente-modulos-header-text span { font-size: .72rem; color: rgba(255, 255, 255, 0.6); }

.docente-modulos-count-badge {
    background: rgba(252, 123, 4, 0.15);
    border: 1px solid rgba(252, 123, 4, 0.3);
    border-radius: 20px;
    padding: 6px 16px;
    color: #fc7b04;
    font-size: .8rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 6px;
}

.docente-modulos-body { padding: 20px; }

.docente-modulo-card {
    display: flex;
    align-items: stretch;
    gap: 0;
    background: #fff;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    margin-bottom: 12px;
    overflow: hidden;
    transition: all .25s ease;
    position: relative;
}

.docente-modulo-card:hover {
    border-color: #fc7b04;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

.docente-modulo-card:last-child { margin-bottom: 0; }

.docente-modulo-color-bar { width: 6px; flex-shrink: 0; }

.docente-modulo-content {
    flex: 1;
    display: flex;
    align-items: center;
    padding: 16px 20px;
    gap: 20px;
    flex-wrap: wrap;
}

.docente-modulo-main { flex: 1; min-width: 200px; }

.docente-modulo-num {
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #94a3b8;
    margin-bottom: 4px;
}

.docente-modulo-name {
    font-family: 'Outfit', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.docente-modulo-programa {
    font-size: .8rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}

.docente-modulo-meta { display: flex; gap: 16px; flex-wrap: wrap; }

.docente-modulo-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    background: #f8fafc;
    border-radius: 10px;
    font-size: .78rem;
    color: #475569;
}

.docente-modulo-meta-item i { color: #fc7b04; font-size: 1rem; }
.docente-modulo-meta-item strong { color: #1e293b; font-weight: 700; }

.docente-modulo-actions { display: flex; gap: 8px; flex-shrink: 0; }

.docente-modulo-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    border-radius: 10px;
    font-size: .8rem;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s ease;
    white-space: nowrap;
}

.docente-modulo-btn.primary {
    background: linear-gradient(135deg, #fc7b04 0%, #e67300 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(252, 123, 4, 0.25);
}

.docente-modulo-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(252, 123, 4, 0.35);
    color: #fff;
}

.docente-modulo-btn.secondary {
    background: #fff;
    border: 2px solid #e2e8f0;
    color: #475569;
}

.docente-modulo-btn.secondary:hover {
    border-color: #fc7b04;
    color: #fc7b04;
}

.docente-empty-modules {
    text-align: center;
    padding: 40px 20px;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
}

.docente-empty-modules i { font-size: 3rem; color: #cbd5e1; margin-bottom: 12px; display: block; }
.docente-empty-modules h5 { font-family: 'Outfit', sans-serif; font-weight: 600; margin-bottom: 8px; }
.docente-empty-modules p { font-size: .85rem; color: #64748b; margin: 0; }

/* ── Calendario Docente Mejorado ───────────────────────────────── */
.calendario-docente-wrapper {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}

.calendario-docente-header {
    background: linear-gradient(135deg, #1e293b 0%, #2c3036 100%);
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.calendario-docente-title { display: flex; align-items: center; gap: 14px; }

.calendario-docente-title-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: linear-gradient(135deg, rgba(252, 123, 4, 0.2) 0%, rgba(252, 123, 4, 0.05) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fc7b04;
    font-size: 1.4rem;
}

.calendario-docente-title-text h3 {
    font-family: 'Outfit', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
}

.calendario-docente-title-text span { font-size: .78rem; color: rgba(255, 255, 255, 0.6); }

.calendario-docente-stats { display: flex; gap: 16px; }

.calendario-docente-stat {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    backdrop-filter: blur(8px);
}

.calendario-docente-stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.calendario-docente-stat-icon.courses {
    background: linear-gradient(135deg, rgba(252, 123, 4, 0.3) 0%, rgba(252, 123, 4, 0.1) 100%);
    color: #fc7b04;
}

.calendario-docente-stat-icon.sessions {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.3) 0%, rgba(99, 102, 241, 0.1) 100%);
    color: #818cf8;
}

.calendario-docente-stat-info { text-align: left; }

.calendario-docente-stat-value {
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: 1.2rem;
    color: #fff;
    line-height: 1;
}

.calendario-docente-stat-label {
    font-size: .7rem;
    color: rgba(255, 255, 255, 0.6);
    text-transform: uppercase;
    letter-spacing: .04em;
}

.calendario-docente-body { padding: 20px; }

.calendario-docente-body .fc { font-family: 'Plus Jakarta Sans', sans-serif; }
.calendario-docente-body .fc-toolbar { gap: 16px; flex-wrap: wrap; }

.calendario-docente-body .fc-toolbar-title {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 700 !important;
    font-size: 1.3rem !important;
    color: #1e293b;
}

.calendario-docente-body .fc-button {
    background: linear-gradient(135deg, #fc7b04 0%, #e67300 100%) !important;
    border: none !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    font-size: .82rem !important;
    padding: 10px 18px !important;
    transition: all .25s ease !important;
    box-shadow: 0 4px 12px rgba(252, 123, 4, 0.25) !important;
    text-transform: capitalize;
}

.calendario-docente-body .fc-button:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(252, 123, 4, 0.35) !important;
    filter: brightness(1.05);
}

.calendario-docente-body .fc-button-active {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
    box-shadow: 0 4px 12px rgba(30, 41, 59, 0.3) !important;
}

.calendario-docente-body .fc-daygrid-day-number,
.calendario-docente-body .fc-col-header-cell-cushion {
    font-weight: 600;
    text-decoration: none;
}

.calendario-docente-body .fc-daygrid-day-number { font-size: .85rem; color: #475569; padding: 8px; }

.calendario-docente-body .fc-col-header-cell-cushion {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #64748b;
    padding: 12px 8px;
}

.calendario-docente-body .fc-event {
    border-radius: 8px !important;
    padding: 6px 10px !important;
    font-weight: 600 !important;
    font-size: .78rem !important;
    cursor: pointer !important;
    transition: all .2s ease !important;
    border: none !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.calendario-docente-body .fc-event:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.calendario-docente-body .fc-day-today { background: rgba(252, 123, 4, 0.06) !important; }

.calendario-docente-body .fc-day-today .fc-daygrid-day-number {
    background: #fc7b04;
    color: #fff !important;
    border-radius: 10px;
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.calendario-docente-body .fc-scrollgrid { border-radius: 12px; overflow: hidden; }
.calendario-docente-body .fc-scrollgrid td { border-color: #e2e8f0 !important; }
.calendario-docente-body .fc-scrollgrid th { background: #f8fafc !important; border-color: #e2e8f0 !important; }

.calendario-docente-body .fc-timegrid-slot-label-cushion {
    font-size: .72rem;
    color: #64748b;
    font-weight: 600;
}

.calendario-docente-empty {
    text-align: center;
    padding: 60px 20px;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
}

.calendario-docente-empty i { font-size: 4rem; color: #cbd5e1; margin-bottom: 16px; display: block; }
.calendario-docente-empty h5 { font-family: 'Outfit', sans-serif; font-weight: 600; margin-bottom: 8px; }
.calendario-docente-empty p { font-size: .9rem; color: #64748b; margin: 0; }

/* ── Tab Pagos Mejorado ─────────────────────────────────────── */
/* ── Pagos — pills wrapping navigation (usa .contable-prog-pill de contable) ── */
.pagos-tabs-wrapper {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-bottom: 24px;
}

.pagos-tabs-header {
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 12px 16px;
    border-bottom: 1px solid #e2e8f0;
}

/* ── Pagos — bancos grid & cards (rediseñado) ──────────────────── */
.pagos-bancos-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    padding: 18px;
    background: #fff;
}

.pagos-banco-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    transition: box-shadow .2s;
}
.pagos-banco-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
}

.pagos-banco-head {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
}

.pagos-banco-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(252,123,4,.1);
    color: #fc7b04;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .9rem;
    flex-shrink: 0;
}

.pagos-banco-name {
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .82rem;
    color: #1e293b;
}

.pagos-banco-sigla {
    font-size: .65rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .04em;
}

.pagos-banco-body {
    padding: 8px;
}

.pagos-banco-cuenta {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 8px;
    border-bottom: 1px solid #f1f5f9;
}
.pagos-banco-cuenta:last-child { border-bottom: none; }

.pagos-banco-cuenta-main { flex: 1; min-width: 0; }

.pagos-banco-cuenta-num {
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .82rem;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 6px;
}
.pagos-banco-cuenta-num i { color: #fc7b04; font-size: .8rem; }

.pagos-banco-cuenta-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 3px;
    flex-wrap: wrap;
}

.pagos-banco-badge {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: .62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .03em;
}
.pagos-banco-badge.cc { background: rgba(99,102,241,.1); color: #6366f1; }
.pagos-banco-badge.ca { background: rgba(34,197,94,.1); color: #16a34a; }

.pagos-banco-titular {
    font-size: .68rem;
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.pagos-banco-titular i { font-size: .65rem; }

.pagos-banco-qr {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    cursor: pointer;
    flex-shrink: 0;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .2s;
}
.pagos-banco-qr:hover { border-color: #fc7b04; box-shadow: 0 2px 8px rgba(252,123,4,.12); }
.pagos-banco-qr img { width: 100%; height: 100%; object-fit: cover; }
.pagos-banco-qr span {
    display: none;
    position: absolute;
    inset: 0;
    background: rgba(30,41,59,.85);
    color: #fff;
    font-size: .55rem;
    font-weight: 700;
    align-items: center;
    justify-content: center;
    gap: 3px;
    text-align: center;
    line-height: 1.2;
}
.pagos-banco-qr:hover span { display: flex; }

.pagos-oferta-content { display: none; }
.pagos-oferta-content.active { display: block; }

/* ── Pagos — grid de dos columnas (cuotas + comprobantes) ──── */
.pagos-grid-2 {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 16px;
    padding: 18px;
}

.pagos-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.pagos-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    gap: 8px;
}

.pagos-card-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.pagos-card-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
    flex-shrink: 0;
}

.pagos-card-icon.orange { background: rgba(252,123,4,.12); color: #fc7b04; }
.pagos-card-icon.indigo { background: rgba(99,102,241,.12); color: #6366f1; }

.pagos-card-title {
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .85rem;
    color: #1e293b;
    margin: 0;
    line-height: 1.2;
}

.pagos-card-sub {
    font-size: .68rem;
    color: #94a3b8;
}

.pagos-card-body {
    flex: 1;
    overflow-x: auto;
}

/* ── Pagos — mini tabla dentro de card ──────────────────────── */
.pagos-mini-table {
    width: 100%;
    border-collapse: collapse;
}

.pagos-mini-table thead th {
    background: #f8fafc;
    padding: 8px 10px;
    font-size: .62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #94a3b8;
    border-bottom: 1.5px solid #e2e8f0;
    text-align: left;
    white-space: nowrap;
}

.pagos-mini-table tbody td {
    padding: 8px 10px;
    font-size: .78rem;
    color: #475569;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    white-space: nowrap;
}

.pagos-mini-table tbody tr:last-child td { border-bottom: none; }
.pagos-mini-table tbody tr:hover td { background: #fafbfc; }

.pagos-mini-table .num-cuota {
    font-weight: 700;
    color: #fc7b04;
}

.pagos-mini-table .fecha-cell { color: #94a3b8; font-size: .78rem; }

/* Micro barra en cuota (reutilizada de contable) */
.pagos-mini-table .cuota-pay-micro .track { height: 5px; }
.pagos-mini-table .cuota-pay-micro .pct { font-size: .68rem; min-width: 30px; }

.pagos-cuota-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: .68rem;
    font-weight: 600;
}

.pagos-cuota-badge.pagado { background: #dcfce7; color: #16a34a; }
.pagos-cuota-badge.pendiente { background: #fef3c7; color: #d97706; }
.pagos-cuota-badge.vencido { background: #fee2e2; color: #dc2626; }

/* ── Pagos — comprobante rows ───────────────────────────────── */
.pagos-comp-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-bottom: 1px solid #f1f5f9;
    transition: background .15s;
}
.pagos-comp-row:last-child { border-bottom: none; }
.pagos-comp-row:hover { background: #fafbfc; }

.pagos-comp-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.pagos-comp-icon.pdf { background: rgba(220,38,38,.1); color: #dc2626; }
.pagos-comp-icon.img { background: rgba(99,102,241,.1); color: #6366f1; }

.pagos-comp-body {
    flex: 1;
    min-width: 0;
}
.pagos-comp-body .top {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.pagos-comp-body .top .fecha {
    font-size: .78rem;
    color: #64748b;
}
.pagos-comp-body .top .cuota-tags {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}
.pagos-comp-body .top .cuota-tag {
    font-size: .65rem;
    font-weight: 600;
    padding: 2px 8px;
    background: rgba(252,123,4,.1);
    color: #c96004;
    border-radius: 6px;
}
.pagos-comp-body .obs {
    font-size: .75rem;
    color: #94a3b8;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.pagos-comp-actions {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}

.pagos-comp-badge {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: .65rem;
    font-weight: 600;
    white-space: nowrap;
}

.pagos-comp-badge.verificado { background: #dcfce7; color: #16a34a; }
.pagos-comp-badge.revision { background: #fef3c7; color: #d97706; }
.pagos-comp-badge.rechazado { background: #fee2e2; color: #dc2626; }

.pagos-comp-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: .72rem;
    font-weight: 600;
    color: #fc7b04;
    text-decoration: none;
    background: rgba(252,123,4,.08);
    transition: all .2s;
}
.pagos-comp-link:hover { background: rgba(252,123,4,.16); color: #c96004; }

/* ── Pagos — empty states ────────────────────────────────────── */
.pagos-card-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 32px 16px;
    color: #94a3b8;
    text-align: center;
    flex: 1;
}
.pagos-card-empty i { font-size: 1.8rem; opacity: .4; margin-bottom: 8px; }
.pagos-card-empty p { font-size: .8rem; margin: 0; }

/* ── Pagos — botón subir/estado al día ──────────────────────── */
.pagos-btn-subir {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #fc7b04 0%, #e67300 100%);
    border: none;
    border-radius: 9px;
    color: #fff;
    font-size: .76rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .25s;
    box-shadow: 0 3px 10px rgba(252, 123, 4, 0.2);
    white-space: nowrap;
}

.pagos-btn-subir:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 16px rgba(252, 123, 4, 0.3);
}

.pagos-btn-al-dia {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    background: rgba(34, 197, 94, 0.1);
    border-radius: 20px;
    color: #16a34a;
    font-size: .75rem;
    font-weight: 600;
    border: 1px solid rgba(34, 197, 94, 0.2);
    white-space: nowrap;
}

/* ── Modal Detalle Pago ───────────────────────────────────────── */
.detalle-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 20px;
}

.detalle-header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.detalle-logo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.detalle-logo img { width: 48px; }

.detalle-logo-text {
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: .9rem;
    color: #1e293b;
}

.detalle-logo-sub { font-size: .7rem; color: #64748b; }

.detalle-recibo-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #fc7b04 0%, #e67300 100%);
    border-radius: 10px;
    color: #fff;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
}

.detalle-meta { display: flex; gap: 20px; font-size: .82rem; color: #64748b; }
.detalle-meta strong { color: #475569; }

.detalle-info-section { margin-bottom: 20px; }

.detalle-info-section h6 {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #94a3b8;
    margin-bottom: 10px;
}

.detalle-info-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }

.detalle-info-item {
    padding: 12px 16px;
    background: #f8fafc;
    border-radius: 10px;
}

.detalle-info-label {
    font-size: .7rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-bottom: 4px;
}

.detalle-info-value {
    font-weight: 600;
    color: #1e293b;
    font-size: .9rem;
}

.detalle-tabla {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.detalle-tabla table { margin: 0; font-size: .85rem; }
.detalle-tabla thead { background: #f8fafc; }

.detalle-tabla thead th {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #64748b;
    padding: 14px 16px;
    border-bottom: 2px solid #e2e8f0;
}

.detalle-tabla tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
}

.detalle-tabla tfoot td {
    padding: 16px;
    background: linear-gradient(135deg, #fef3c7 0%, #fef9c3 100%);
    font-weight: 700;
}

.detalle-footer {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    gap: 20px;
}

.detalle-footer-box {
    flex: 1;
    text-align: center;
    padding: 16px;
    background: #f8fafc;
    border-radius: 12px;
}

.detalle-footer-box .label {
    font-size: .7rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: 8px;
}

.detalle-footer-box .value {
    font-weight: 700;
    color: #1e293b;
    font-size: .9rem;
    border-top: 2px solid #e2e8f0;
    padding-top: 8px;
}

.modal-detalle-pago .modal-footer {
    border-top: 1px solid #e2e8f0;
    padding: 16px 24px;
    background: #f8fafc;
    display: flex;
    gap: 12px;
}

.modal-detalle-pago .btn-descargar {
    padding: 12px 20px;
    border-radius: 12px;
    background: linear-gradient(135deg, #fc7b04 0%, #e67300 100%);
    color: #fff;
    font-weight: 600;
    border: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all .25s;
    box-shadow: 0 4px 12px rgba(252, 123, 4, 0.3);
}

.modal-detalle-pago .btn-descargar:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(252, 123, 4, 0.4);
}

.modal-detalle-pago .btn-comprobante {
    padding: 12px 20px;
    border-radius: 12px;
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: #fff;
    font-weight: 600;
    border: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all .25s;
}

.modal-detalle-pago .btn-comprobante:hover { transform: translateY(-2px); }

.modal-detalle-pago .btn-cerrar {
    padding: 12px 20px;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    background: #fff;
    color: #475569;
    font-weight: 600;
    transition: all .2s;
}

.modal-detalle-pago .btn-cerrar:hover { background: #f1f5f9; }

.pago-list-container .list-group-item {
    border-left: 3px solid #fc7b04;
    margin-bottom: 4px;
    border-radius: 8px;
    transition: all .2s;
}

.pago-list-container .list-group-item:hover {
    background: #fef3c7;
    border-left-color: #e67300;
}

.pago-list-container .list-group-item.active {
    background: linear-gradient(135deg, #fc7b04 0%, #e67300 100%);
    color: #fff;
    border-left-color: #c96004;
}

.pago-list-container .list-group-item.active .text-muted { color: rgba(255,255,255,0.8) !important; }

/* ── Cronograma ───────────────────────────────────────────────── */
.cronograma-container {
    display: flex;
    gap: 0;
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}

.cronograma-sidebar {
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 0;
    height: 100%;
    min-height: 600px;
    display: flex;
    flex-direction: column;
    width: 340px;
    flex-shrink: 0;
    border-right: 1px solid #e2e8f0;
}

.cronograma-sidebar-head {
    background: linear-gradient(135deg, #1a1d21 0%, #2c3036 100%);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #fff;
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
    font-size: .9rem;
    flex-shrink: 0;
}
.cronograma-sidebar-head i { color: #fc7b04; font-size: 1rem; }

.cronograma-sidebar-body {
    padding: 14px 16px;
    flex: 1;
    overflow-y: auto;
}

.cronograma-select {
    width: 100%;
    padding: 11px 14px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: .8rem;
    font-weight: 500;
    color: #1e293b;
    background: #fff;
    cursor: pointer;
    transition: all .2s;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 36px;
    margin-bottom: 10px;
}
.cronograma-select:hover { border-color: #fc7b04; }
.cronograma-select:focus {
    border-color: #fc7b04;
    box-shadow: 0 0 0 3px rgba(252,123,4,.1);
    outline: none;
}

.cronograma-btn-all {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    padding: 11px 16px;
    margin-bottom: 14px;
    background: linear-gradient(135deg, #fc7b04 0%, #e67300 100%);
    border: none;
    border-radius: 10px;
    font-size: .8rem;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
    transition: all .25s ease;
    box-shadow: 0 3px 10px rgba(252,123,4,.2);
}
.cronograma-btn-all:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 16px rgba(252,123,4,.3);
}
.cronograma-btn-all.active {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    box-shadow: 0 3px 10px rgba(30,41,59,.25);
}

.cronograma-sidebar-empty {
    text-align: center;
    padding: 24px 16px;
    font-size: .78rem;
    color: #94a3b8;
}
.cronograma-sidebar-empty i {
    font-size: 1.4rem;
    display: block;
    margin-bottom: 8px;
    color: #cbd5e1;
}

.cronograma-modulo-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all .2s ease;
    position: relative;
    overflow: hidden;
}
.cronograma-modulo-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--mod-color, #6366f1);
    border-radius: 3px 0 0 10px;
    transition: width .2s;
}
.cronograma-modulo-card:hover {
    border-color: var(--mod-color, #fc7b04);
    background: #f8fafc;
    transform: translateX(3px);
}
.cronograma-modulo-card:hover::before { width: 4px; }
.cronograma-modulo-card.active {
    border-color: var(--mod-color, #fc7b04);
    background: linear-gradient(135deg, rgba(252,123,4,.05) 0%, rgba(252,123,4,.02) 100%);
}
.cronograma-modulo-card.active::before { width: 4px; }

.cronograma-modulo-dot {
    width: 12px; height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(0,0,0,.1);
}
.cronograma-modulo-info { flex: 1; min-width: 0; }
.cronograma-modulo-num {
    font-size: .6rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: #94a3b8;
    margin-bottom: 1px;
}
.cronograma-modulo-name {
    font-size: .8rem; font-weight: 600;
    color: #1e293b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.cronograma-modulo-docente {
    font-size: .68rem;
    color: #64748b;
    margin-top: 2px;
}
.cronograma-modulo-badge {
    font-size: .58rem; font-weight: 700;
    padding: 2px 7px; border-radius: 20px;
    background: #f1f5f9;
    color: #64748b;
    white-space: nowrap;
}

.cronograma-main {
    flex: 1;
    padding: 20px 24px;
    background: #fff;
    min-width: 0;
}

.cronograma-title-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 12px;
}

.cronograma-title-left { display: flex; align-items: center; gap: 12px; }

.cronograma-title-icon {
    width: 40px; height: 40px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, rgba(252,123,4,.12) 0%, rgba(252,123,4,.04) 100%);
    color: #fc7b04;
    font-size: 1.15rem;
}
.cronograma-title-text h4 {
    font-family: 'Outfit', sans-serif;
    font-weight: 700; font-size: 1.05rem;
    color: #1e293b; margin: 0;
}
.cronograma-title-text span { font-size: .73rem; color: #64748b; }

.cronograma-legend-dot {
    display: inline-block;
    width: 10px; height: 10px;
    border-radius: 50%;
}
.cronograma-legend-dot.confirmed { background: #22c55e; }
.cronograma-legend-dot.postponed {
    background: transparent;
    border: 1.5px dashed #94a3b8;
}

.cronograma-filter-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: linear-gradient(135deg, rgba(252,123,4,.1) 0%, rgba(252,123,4,.05) 100%);
    border: 1.5px solid #fc7b04;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 600;
    color: #c96004;
    animation: fadeInScale .3s ease;
}

@keyframes fadeInScale {
    from { opacity: 0; transform: scale(.9); }
    to { opacity: 1; transform: scale(1); }
}
.cronograma-filter-badge .dot { width: 8px; height: 8px; border-radius: 50%; background: #fc7b04; }
.cronograma-filter-badge button {
    background: none; border: none; padding: 0;
    color: #c96004; cursor: pointer;
    display: flex; align-items: center;
    margin-left: 3px; transition: transform .2s;
}
.cronograma-filter-badge button:hover { transform: scale(1.2); }

.cronograma-calendar-wrapper {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    padding: 18px;
}

.cronograma-calendar-wrapper .fc { font-family: 'Plus Jakarta Sans', sans-serif; }
.cronograma-calendar-wrapper .fc-toolbar { gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }

.cronograma-calendar-wrapper .fc-toolbar-title {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 700 !important;
    font-size: 1.2rem !important;
    color: #1e293b;
}

.cronograma-calendar-wrapper .fc-button {
    background: linear-gradient(135deg, #fc7b04 0%, #e67300 100%) !important;
    border: none !important;
    border-radius: 9px !important;
    font-weight: 600 !important;
    font-size: .78rem !important;
    padding: 8px 14px !important;
    transition: all .2s ease !important;
    box-shadow: 0 3px 10px rgba(252,123,4,.2) !important;
    text-transform: capitalize;
}
.cronograma-calendar-wrapper .fc-button:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 5px 16px rgba(252,123,4,.3) !important;
}
.cronograma-calendar-wrapper .fc-button-primary:not(:disabled).fc-button-active,
.cronograma-calendar-wrapper .fc-button-primary:not(:disabled):active {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
    box-shadow: 0 3px 10px rgba(30,41,59,.25) !important;
}
.cronograma-calendar-wrapper .fc-button-primary:disabled {
    opacity: .5 !important;
    box-shadow: none !important;
}

.cronograma-calendar-wrapper .fc-daygrid-day-number {
    font-size: .82rem; font-weight: 600; color: #475569; padding: 6px; text-decoration: none;
}
.cronograma-calendar-wrapper .fc-col-header-cell-cushion {
    font-size: .7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .05em;
    color: #64748b; padding: 10px 6px; text-decoration: none;
}

.cronograma-calendar-wrapper .fc-event {
    border-radius: 6px !important;
    padding: 4px 8px !important;
    font-weight: 600 !important;
    font-size: .75rem !important;
    cursor: pointer !important;
    transition: all .15s ease !important;
    border: none !important;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
}
.cronograma-calendar-wrapper .fc-event:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(0,0,0,.12);
}
.cronograma-calendar-wrapper .fc-event.fc-event-postergado {
    background: transparent !important;
    border: 2px dashed var(--fc-event-border-color, #94a3b8) !important;
}
.cronograma-calendar-wrapper .fc-event.fc-event-postergado .fc-event-title {
    font-style: italic;
    opacity: .85;
}

.cronograma-calendar-wrapper .fc-day-today { background: rgba(252,123,4,.05) !important; }
.cronograma-calendar-wrapper .fc-day-today .fc-daygrid-day-number {
    background: #fc7b04;
    color: #fff !important;
    border-radius: 8px;
    width: 28px; height: 28px;
    display: inline-flex; align-items: center; justify-content: center;
}

.cronograma-calendar-wrapper .fc-scrollgrid {
    border-radius: 10px; overflow: hidden;
    border-color: #e2e8f0 !important;
}
.cronograma-calendar-wrapper .fc-scrollgrid td { border-color: #e2e8f0 !important; }
.cronograma-calendar-wrapper .fc-scrollgrid th {
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%) !important;
    border-color: #e2e8f0 !important;
}

.cronograma-calendar-wrapper .fc-timegrid-slot-label-cushion {
    font-size: .7rem; color: #64748b; font-weight: 600;
}

.cronograma-calendar-wrapper .fc-list-day-cushion {
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%) !important;
    padding: 10px 16px !important;
}
.cronograma-calendar-wrapper .fc-list-day-text,
.cronograma-calendar-wrapper .fc-list-day-side-text {
    font-weight: 700 !important;
    color: #1e293b !important;
}
.cronograma-calendar-wrapper .fc-list-event:hover td {
    background: rgba(252,123,4,.04) !important;
}
.cronograma-calendar-wrapper .fc-list-event-graphic { padding-left: 12px !important; }
.cronograma-calendar-wrapper .fc-list-event-title a {
    font-weight: 600; color: #1e293b; text-decoration: none;
}
.cronograma-calendar-wrapper .fc-list-event-time {
    font-weight: 600; color: #64748b;
}

/* ── Toast de sesión ─────────────────────────────────────────── */
.session-toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    background: #fff;
    border-radius: 20px;
    padding: 20px 24px;
    min-width: 340px;
    max-width: 420px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 8px 24px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    animation: slideInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideInUp {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.session-toast-header { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.session-toast-dot { width: 12px; height: 12px; border-radius: 50%; }

.session-toast-title {
    flex: 1;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: 1rem;
    color: #1e293b;
}

.session-toast-state {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
}

.session-toast-state.confirmado { background: #dcfce7; color: #16a34a; }
.session-toast-state.postergado { background: #f1f5f9; color: #64748b; }

.session-toast-body { display: grid; gap: 12px; }
.session-toast-row { display: flex; align-items: center; gap: 10px; }

.session-toast-icon {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(252, 123, 4, 0.1);
    color: #fc7b04;
    font-size: .9rem;
    flex-shrink: 0;
}

.session-toast-text { font-size: .85rem; color: #475569; }

.session-toast-close {
    position: absolute;
    top: 16px;
    right: 16px;
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    font-size: 1.2rem;
    padding: 4px;
    transition: all .2s;
}

.session-toast-close:hover { color: #1e293b; transform: scale(1.1); }

/* ── Responsive general ───────────────────────────────────────── */
@media (max-width: 767px) {
    .docente-modulo-content { flex-direction: column; align-items: flex-start; }
    .docente-modulo-meta { width: 100%; }
    .docente-modulo-actions { width: 100%; justify-content: flex-start; }
    .calendario-docente-header { flex-direction: column; align-items: flex-start; }
    .calendario-docente-stats { width: 100%; justify-content: flex-start; }
    .pagos-bancos-grid { grid-template-columns: 1fr; }
    .cronograma-container { flex-direction: column; }
    .cronograma-sidebar { width: 100%; min-height: auto; border-right: none; }
    .cronograma-main { padding: 16px; }
    .cronograma-calendar-wrapper { padding: 12px; }
    .cronograma-calendar-wrapper .fc-toolbar-title { font-size: 1rem !important; }
    .cronograma-calendar-wrapper .fc-button { padding: 6px 10px !important; font-size: .72rem !important; }
}

/* FullCalendar overrides */
.fc .fc-button-hoy,
.fc .fc-button-hoy:hover {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
}

.fc .fc-button-mes,
.fc .fc-button-semana,
.fc .fc-button-lista {
    text-transform: capitalize;
}

/* ── Hero role badges ─────────────────────────────────────── */
.est-hero::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -5%;
    width: 280px;
    height: 280px;
    background: radial-gradient(circle, rgba(252,123,4,.25) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.est-hero-role {
    font-size: .78rem !important;
    font-weight: 700 !important;
    padding: .3rem .85rem !important;
    border-radius: 20px !important;
    display: inline-flex;
    align-items: center;
    gap: .35rem;
}
.est-hero-role.estudiante {
    background: rgba(34,197,94,.22) !important;
    border: 1px solid rgba(34,197,94,.4) !important;
    color: #bbf7d0 !important;
}
.est-hero-role.docente {
    background: rgba(99,102,241,.22) !important;
    border: 1px solid rgba(99,102,241,.4) !important;
    color: #c7d2fe !important;
}
.est-hero-role.ambos {
    background: rgba(252,123,4,.22) !important;
    border: 1px solid rgba(252,123,4,.45) !important;
    color: #fed7aa !important;
}

/* ── pers-* contact rows (admin style) ───────────────────── */
.pers-contact-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 7px 8px;
    border-radius: 9px;
    transition: background .15s;
}
.pers-contact-row:hover { background: rgba(252,123,4,.04); }

.pers-contact-ico {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: rgba(252,123,4,.08);
    color: #fc7b04;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .88rem;
    flex-shrink: 0;
}

.pers-contact-body { flex: 1; min-width: 0; }

.pers-contact-lbl {
    font-size: .59rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #94a3b8;
    line-height: 1;
    margin-bottom: 2px;
}

.pers-contact-val {
    font-size: .84rem;
    font-weight: 500;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.pers-contact-link { text-decoration: none; color: #1e293b; transition: color .15s; }
.pers-contact-link:hover { color: #fc7b04; }

.pers-contact-act {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 7px;
    border: 1px solid #e2e8f0;
    background: white;
    color: #64748b;
    cursor: pointer;
    transition: all .18s;
    font-size: .82rem;
    flex-shrink: 0;
    text-decoration: none;
}
.pers-contact-act:hover { border-color: #fc7b04; background: rgba(252,123,4,.08); color: #fc7b04; }
.pers-contact-act.wa:hover { border-color: #25d366; background: rgba(37,211,102,.08); color: #25d366; }

/* ── pers-* inline grid ──────────────────────────────────── */
.pers-inline-grid {
    display: grid;
    grid-template-columns: repeat(2,1fr);
    gap: 10px;
    padding: 10px 10px 4px;
}
.pers-inline-item { display: flex; flex-direction: column; gap: 2px; }

/* ── pers-* account chips (left column dark bg) ─────────── */
.pers-acc-chips {
    display: flex;
    flex-direction: column;
    gap: 5px;
    width: 100%;
    padding-top: 4px;
}
.pers-acc-chip {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 8px;
    font-size: .67rem;
    font-weight: 600;
    letter-spacing: .02em;
}
.pers-chip-ok { background: rgba(34,197,94,.18); color: #dcfce7; border: 1px solid rgba(34,197,94,.35); }
.pers-chip-ok i { color: #86efac; }
.pers-chip-no { background: rgba(255,255,255,.08); color: rgba(255,255,255,.45); border: 1px solid rgba(255,255,255,.12); }

/* ── pers-* info items (right column) ───────────────────── */
.pers-info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    background: white;
    border: 1px solid var(--est-border,#e2e8f0);
    border-radius: 9px;
    transition: box-shadow .18s;
}
.pers-info-item:hover { box-shadow: 0 2px 8px rgba(252,123,4,.08); }

.pers-info-ico {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    background: rgba(252,123,4,.08);
    color: #fc7b04;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .82rem;
    flex-shrink: 0;
}
.pers-info-lbl { font-size: .6rem; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; font-weight: 700; line-height: 1; margin-bottom: 2px; }
.pers-info-val { font-size: .82rem; font-weight: 600; color: #1e293b; word-break: break-all; }

/* ── pers-* study cards ──────────────────────────────────── */
.pers-study-card {
    background: white;
    border: 1px solid var(--est-border,#e2e8f0);
    border-radius: 10px;
    padding: 10px 12px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    transition: box-shadow .15s;
}
.pers-study-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,.06); }

.pers-study-grado {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 5px;
    background: rgba(252,123,4,.09);
    color: #c96004;
    font-size: .62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    width: fit-content;
}
.pers-study-profesion { font-size: .83rem; font-weight: 600; color: #1e293b; line-height: 1.3; }
.pers-study-univ { display: flex; align-items: center; gap: 4px; font-size: .73rem; color: #64748b; }

/* ── pers-* section separator ───────────────────────────── */
.pers-section-sep {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--est-text-muted,#64748b);
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 0 6px;
}
.pers-section-sep i { color: #fc7b04; }

@media (max-width:767px) { .pers-inline-grid { grid-template-columns: 1fr; } }

/* ═══════════════════════════════════════════════════════════════
   MEJORAS VISUALES — TABS ACADÉMICO, CONTABLE Y PAGOS
   ═══════════════════════════════════════════════════════════════ */

/* ── Banner superior de tab ────────────────────────────────────── */
.tab-banner {
    border-radius: 16px;
    padding: 18px 22px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
    overflow: hidden;
}
.tab-banner::after {
    content: '';
    position: absolute;
    right: -28px;
    top: -28px;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
    pointer-events: none;
}
.tab-banner.academico {
    background: linear-gradient(135deg, #391b04 0%, #743c04 55%, #c96004 100%);
}
.tab-banner-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    background: rgba(255,255,255,.13);
    border: 1px solid rgba(255,255,255,.2);
    color: #fff;
}
.tab-banner-body { flex: 1; min-width: 0; }
.tab-banner-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.05rem; font-weight: 700;
    color: #fff; margin: 0 0 3px;
}
.tab-banner-sub { font-size: .78rem; color: rgba(255,255,255,.65); margin: 0; }
.tab-banner-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px;
    border-radius: 20px;
    font-size: .78rem; font-weight: 700;
    white-space: nowrap; flex-shrink: 0;
    background: rgba(255,255,255,.13);
    color: rgba(255,255,255,.9);
    border: 1px solid rgba(255,255,255,.22);
}

/* ── Académico — encabezado de programa (gradiente de marca) ───── */
.est-prog-card .est-prog-header {
    background: linear-gradient(135deg, #391b04 0%, #743c04 50%, #c96004 100%);
}

/* ── Académico — módulos como mini-tarjetas ────────────────────── */
.est-modulos-list .est-modulo-item {
    background: #f8fafc;
    border-radius: 12px;
    border: 1.5px solid #e2e8f0 !important;
    padding: 14px 16px;
    margin-bottom: 8px;
    border-bottom: 1.5px solid #e2e8f0 !important;
    transition: border-color .2s, background .2s, transform .15s;
}
.est-modulos-list .est-modulo-item:last-child {
    margin-bottom: 0;
    border-bottom: 1.5px solid #e2e8f0 !important;
}
.est-modulos-list .est-modulo-item:hover {
    border-color: rgba(252,123,4,.38) !important;
    background: rgba(252,123,4,.025);
    transform: translateX(3px);
}

/* ── Académico — selector de programa ──────────────────────────── */
.acad-prog-selector {
    background: var(--vz-card-bg, #fff);
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-bottom: 1rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.acad-prog-selector-header {
    display: flex; align-items: center; gap: .5rem;
    padding: .6rem 1rem;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    font-size: .7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .05em;
    color: #64748b;
}
.acad-prog-count {
    margin-left: auto;
    background: #fc7b04; color: #fff;
    border-radius: 10px; padding: 1px 8px;
    font-size: .68rem;
}
.acad-prog-pills {
    display: flex; gap: .5rem; flex-wrap: wrap;
    padding: .75rem 1rem;
}
.acad-prog-pill {
    display: flex; align-items: center; gap: .55rem;
    padding: .5rem .85rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    cursor: pointer; transition: all .2s;
    text-align: left;
}
.acad-prog-pill:hover:not(.active) {
    border-color: #fc7b04;
    background: rgba(252,123,4,.04);
}
.acad-prog-pill.active {
    background: linear-gradient(135deg, #9a4904, #df6a04);
    border-color: #9a4904;
    color: #fff;
}
.acad-prog-pill-num {
    width: 22px; height: 22px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; font-weight: 700; flex-shrink: 0;
    background: rgba(252,123,4,.12); color: #fc7b04;
}
.acad-prog-pill.active .acad-prog-pill-num {
    background: rgba(255,255,255,.22); color: #fff;
}
.acad-prog-pill-info { display: flex; flex-direction: column; line-height: 1.2; }
.acad-prog-pill-name { font-size: .82rem; font-weight: 600; }
.acad-prog-pill-code { font-size: .68rem; opacity: .7; }
.acad-prog-pill-estado {
    font-size: .65rem; font-weight: 700;
    padding: .15rem .5rem; border-radius: 8px; flex-shrink: 0;
}
.acad-prog-pill-estado.inscrito { background: rgba(90,138,48,.15); color: #5a8a30; }
.acad-prog-pill-estado.pendiente { background: rgba(252,123,4,.15); color: #c96004; }
.acad-prog-pill-estado.otro { background: rgba(100,116,139,.12); color: #64748b; }
.acad-prog-pill.active .acad-prog-pill-estado { background: rgba(255,255,255,.2); color: #fff; }

/* ── Académico — cabecera del programa seleccionado ─────────────── */
.acad-prog-header-bar {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
    padding: 1rem 1.25rem;
    background: linear-gradient(135deg, #391b04 0%, #5c2d0a 40%, #c96004 100%);
    border-radius: 12px;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    position: relative; overflow: hidden;
}
.acad-prog-header-bar::after {
    content: ''; position: absolute; top: -40%; right: -5%;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 70%);
    border-radius: 50%; pointer-events: none;
}
.acad-prog-header-info { position: relative; z-index: 1; }
.acad-prog-header-name {
    font-family: 'Outfit', sans-serif;
    font-size: .95rem; font-weight: 700; color: #fff; margin-bottom: .35rem;
}
.acad-prog-header-meta {
    display: flex; gap: .85rem; flex-wrap: wrap;
}
.acad-prog-header-meta span {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .75rem; color: rgba(255,255,255,.8);
}
.acad-prog-header-bar .est-estado-badge { position: relative; z-index: 1; flex-shrink: 0; }

/* ── Académico — grid de módulos ────────────────────────────────── */
.acad-modulos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: .85rem;
    align-items: start;
}

.acad-mod-card {
    background: var(--vz-card-bg, #fff);
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    display: flex; flex-direction: column;
    transition: box-shadow .2s, transform .2s;
}
.acad-mod-card:hover {
    box-shadow: 0 4px 18px rgba(0,0,0,.08);
    transform: translateY(-2px);
}
.acad-mod-stripe { height: 4px; width: 100%; flex-shrink: 0; }
.acad-mod-body {
    padding: .85rem 1rem;
    display: flex; flex-direction: column; gap: .5rem; flex: 1;
}
.acad-mod-top {
    display: flex; align-items: flex-start; gap: .45rem;
}
.acad-mod-num {
    font-size: .68rem; font-weight: 700;
    padding: .18rem .55rem; border-radius: 6px;
    white-space: nowrap; flex-shrink: 0; margin-top: .1rem;
}
.acad-mod-name {
    font-size: .88rem; font-weight: 700; color: var(--est-text, #1e293b);
    flex: 1; line-height: 1.3;
}
.acad-mod-badge {
    display: inline-flex; align-items: center; gap: .22rem;
    padding: .15rem .5rem; border-radius: 20px;
    font-size: .63rem; font-weight: 700; white-space: nowrap; flex-shrink: 0;
}
.acad-mod-badge.activo  { background: rgba(34,197,94,.12); color: #16a34a; }
.acad-mod-badge.blocked { background: rgba(239,68,68,.1); color: #dc2626; }
.acad-mod-badge.pending { background: rgba(100,116,139,.1); color: #64748b; }

.acad-mod-meta {
    display: flex; flex-direction: column; gap: .22rem;
}
.acad-mod-meta span {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .74rem; color: #64748b;
}
.acad-mod-blocked {
    font-size: .76rem; color: #b91c1c;
    background: rgba(239,68,68,.06);
    border: 1px solid rgba(239,68,68,.18);
    border-radius: 8px; padding: .45rem .7rem;
    display: flex; align-items: center; gap: .4rem;
    margin-top: auto;
}
.acad-mod-actions {
    display: flex; gap: .45rem; flex-wrap: wrap;
    margin-top: auto; padding-top: .6rem;
    border-top: 1px solid #f1f5f9;
}
.acad-mod-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: .3rem;
    padding: .38rem .7rem; border-radius: 8px;
    font-size: .76rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .2s;
    text-decoration: none; flex: 1;
    background: rgba(252,123,4,.1); color: #c96004;
}
.acad-mod-btn:hover { background: #fc7b04; color: #fff; }
.acad-mod-btn-go {
    background: rgba(90,138,48,.1); color: #3d6b1a;
}
.acad-mod-btn-go:hover { background: #5a8a30; color: #fff; }

/* Panel de actividades dentro del grid — ocupa toda la fila */
.acad-modulos-grid .est-act-panel {
    grid-column: 1 / -1;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    margin-top: -.4rem;
}

.acad-empty-state {
    text-align: center; padding: 2.5rem 1rem;
    color: #94a3b8;
}
.acad-empty-state i { font-size: 2.5rem; display: block; margin-bottom: .5rem; }
.acad-empty-state p { font-size: .85rem; margin: 0; }

/* ── Contable — Programa pills (wrapping, no scroll) ──────────── */
.contable-prog-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 0;
}
.contable-prog-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    font-size: .81rem;
    font-weight: 600;
    color: var(--est-text-muted);
    background: #fff;
    border: 1.5px solid var(--est-border);
    border-radius: 10px;
    cursor: pointer;
    transition: all .2s ease;
    white-space: nowrap;
}
.contable-prog-pill:hover {
    border-color: var(--est-primary);
    color: var(--est-primary);
    background: var(--est-primary-light);
}
.contable-prog-pill.active {
    background: var(--est-primary);
    color: #fff;
    border-color: var(--est-primary);
    box-shadow: 0 2px 8px rgba(252,123,4,.2);
}
.contable-prog-pill .pill-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 22px;
    border-radius: 6px;
    font-size: .65rem;
    font-weight: 700;
    padding: 0 6px;
    background: rgba(255,255,255,.2);
    color: inherit;
}
.contable-prog-pill:not(.active) .pill-badge {
    background: var(--est-surface);
    color: var(--est-text-muted);
}
.contable-prog-pill.active .pill-badge {
    background: rgba(255,255,255,.2);
    color: #fff;
}
.contable-prog-pill .pill-arrow {
    font-size: .7rem;
    opacity: .5;
    margin-left: 2px;
}

/* ── Contable — Progress bars ─────────────────────────────────── */
.contable-pay-progress {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 18px;
    border-bottom: 1px solid var(--est-border);
    background: #fafcfe;
}
.contable-pay-track {
    flex: 1;
    height: 10px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
}
.contable-pay-track-fill {
    height: 100%;
    border-radius: 10px;
    background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
    transition: width .6s ease;
}
.contable-pay-track-fill.some {
    background: linear-gradient(90deg, #f59e0b 0%, #eab308 100%);
}
.contable-pay-track-fill.low {
    background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
}
.contable-pay-pct {
    font-family: 'Outfit', sans-serif;
    font-size: .9rem;
    font-weight: 800;
    color: #1e293b;
    flex-shrink: 0;
    min-width: 48px;
    text-align: right;
}

/* ── Contable — Mini stats row inside offer ───────────────────── */
.contable-mini-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    border-bottom: 1px solid var(--est-border);
}
.contable-mini-stat {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 18px;
    border-right: 1px solid var(--est-border);
}
.contable-mini-stat:last-child { border-right: none; }
.contable-mini-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.contable-mini-icon.green { background: rgba(34,197,94,.12); color: #22c55e; }
.contable-mini-icon.amber { background: rgba(245,158,11,.12); color: #f59e0b; }
.contable-mini-icon.red   { background: rgba(239,68,68,.12); color: #ef4444; }
.contable-mini-val {
    font-family: 'Outfit', sans-serif;
    font-size: .95rem; font-weight: 700; line-height: 1.1;
}
.contable-mini-val.green { color: #22c55e; }
.contable-mini-val.amber { color: #f59e0b; }
.contable-mini-val.red   { color: #ef4444; }
.contable-mini-lbl {
    font-size: .65rem; color: #94a3b8;
    text-transform: uppercase; letter-spacing: .04em;
}

/* ── Contable — Cuotas compact table ──────────────────────────── */
.contable-cuotas-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.contable-cuotas-table thead th {
    padding: 10px 14px;
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #94a3b8;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}
.contable-cuotas-table tbody td {
    padding: 12px 14px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    font-size: .83rem;
}
.contable-cuotas-table tbody tr:last-child td { border-bottom: none; }
.contable-cuotas-table tbody tr:hover td { background: #f8fafc; }
.contable-cuotas-table .mono {
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
}
.contable-cuotas-table .text-muted-sm {
    font-size: .7rem;
    color: #94a3b8;
}
.contable-cuotas-table .cuota-name {
    font-weight: 600;
    color: #1e293b;
}

/* Mini barra de pago individual por cuota */
.cuota-pay-micro {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 100px;
}
.cuota-pay-micro .track {
    flex: 1;
    height: 6px;
    background: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
}
.cuota-pay-micro .track .fill {
    height: 100%;
    border-radius: 4px;
    transition: width .4s ease;
}
.cuota-pay-micro .track .fill.full { background: #22c55e; }
.cuota-pay-micro .track .fill.part { background: #f59e0b; }
.cuota-pay-micro .track .fill.empty { background: #ef4444; width: 100% !important; opacity: .5; }
.cuota-pay-micro .pct {
    font-size: .72rem;
    font-weight: 700;
    color: #64748b;
    min-width: 36px;
    text-align: right;
}

/* ── Contable — tarjeta de resumen financiero ──────────────────── */
.contable-balance-card {
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.contable-balance-header {
    background: linear-gradient(135deg, #1e293b 0%, #2d3748 100%);
    padding: 14px 20px;
    display: flex; align-items: center; gap: 10px;
}
.contable-balance-header i { color: #fc7b04; font-size: 1.1rem; }
.contable-balance-title {
    font-family: 'Outfit', sans-serif;
    font-size: .9rem; font-weight: 700; color: #fff; margin: 0;
}
.contable-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    background: #fff;
}
.contable-stat-item {
    display: flex; align-items: center; gap: 14px;
    padding: 22px 20px;
    border-right: 1px solid #e2e8f0;
    transition: background .2s;
}
.contable-stat-item:last-child { border-right: none; }
.contable-stat-item:hover { background: #f8fafc; }
.contable-stat-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; flex-shrink: 0;
}
.contable-stat-icon.pagado {
    background: linear-gradient(135deg, rgba(34,197,94,.15) 0%, rgba(34,197,94,.05) 100%);
    color: #22c55e;
}
.contable-stat-icon.pendiente {
    background: linear-gradient(135deg, rgba(245,158,11,.15) 0%, rgba(245,158,11,.05) 100%);
    color: #f59e0b;
}
.contable-stat-icon.vencido {
    background: linear-gradient(135deg, rgba(239,68,68,.15) 0%, rgba(239,68,68,.05) 100%);
    color: #ef4444;
}
.contable-stat-value {
    font-family: 'Outfit', sans-serif;
    font-weight: 700; font-size: 1.1rem; line-height: 1.1; margin-bottom: 3px;
}
.contable-stat-value.pagado   { color: #22c55e; }
.contable-stat-value.pendiente { color: #f59e0b; }
.contable-stat-value.vencido  { color: #ef4444; }
.contable-stat-label { font-size: .72rem; color: #64748b; }

/* ── Contable — wrapper sub-tabs (igual estilo que pagos) ──────── */
.contable-tabs-wrapper {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.contable-tabs-header {
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 12px 16px;
    border-bottom: 1px solid #e2e8f0;
    display: flex; gap: 8px;
    overflow-x: auto; scrollbar-width: none;
}
.contable-tabs-header::-webkit-scrollbar { display: none; }
.contable-tabs-body { padding: 20px; }

/* ── Contable — encabezado de tarjeta de datos mejorado ────────── */
.est-data-card-header {
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%) !important;
}

/* ── Responsive ────────────────────────────────────────────────── */
@media (max-width: 767px) {
    .contable-stats-grid { grid-template-columns: 1fr; }
    .contable-stat-item { border-right: none; border-bottom: 1px solid #e2e8f0; }
    .contable-stat-item:last-child { border-bottom: none; }
    .tab-banner { padding: 14px 16px; }
    .tab-banner-badge { display: none; }
    .contable-tabs-body { padding: 14px; }
    .acad-modulos-grid { grid-template-columns: 1fr; }
    .contable-prog-pills { gap: 6px; }
    .contable-prog-pill { padding: 7px 12px; font-size: .75rem; }
    .contable-mini-stats { grid-template-columns: 1fr; }
    .contable-mini-stat { border-right: none; border-bottom: 1px solid var(--est-border); }
    .contable-mini-stat:last-child { border-bottom: none; }
    .contable-cuotas-table thead { display: none; }
    .contable-cuotas-table tbody td {
        display: block;
        padding: 6px 14px;
        border: none;
        text-align: right;
    }
    .contable-cuotas-table tbody td::before {
        content: attr(data-label);
        float: left;
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #94a3b8;
    }
    .contable-cuotas-table tbody tr {
        display: block;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .contable-cuotas-table tbody tr:last-child { border-bottom: none; }
    .cuota-pay-micro { min-width: 80px; }
    .pagos-grid-2 { grid-template-columns: 1fr; }
    .pagos-mini-table thead { display: none; }
    .pagos-mini-table tbody td {
        display: block;
        padding: 6px 14px;
        border: none;
        text-align: right;
    }
    .pagos-mini-table tbody td::before {
        content: attr(data-label);
        float: left;
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #94a3b8;
    }
    .pagos-mini-table tbody tr {
        display: block;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .pagos-mini-table tbody tr:last-child { border-bottom: none; }
}
</style><?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/virtual/partials/styles.blade.php ENDPATH**/ ?>