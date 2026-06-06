{{-- Tab: Académico --}}
<style>
/* ══════════════════════════════════════════════════
   Tab Académico — diseño completo
══════════════════════════════════════════════════ */

/* ── Navegación de ofertas ─────────────────────── */
.acad-nav-wrap {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
    padding: 16px 20px 0;
    background: var(--d-card-bg, #fff);
    border-bottom: 1px solid #e2e8f0;
}

.acad-nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 9px 14px 10px;
    border: 1px solid #e2e8f0;
    border-bottom: none;
    border-radius: 12px 12px 0 0;
    background: #f8fafc;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    bottom: -1px;
    min-width: 220px;
    max-width: 360px;
    text-align: left;
}
.acad-nav-btn-icon {
    width: 30px;
    height: 30px;
    border-radius: 9px;
    background: rgba(252,123,4,.1);
    color: #c96004;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    transition: all 0.2s ease;
}
.acad-nav-btn-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
    flex: 1;
}
.acad-nav-btn-code {
    font-size: 0.62rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    font-family: 'Courier New', monospace;
    line-height: 1;
    transition: color 0.2s ease;
}
.acad-nav-btn-name {
    font-size: 0.8rem;
    font-weight: 700;
    color: #334155;
    line-height: 1.25;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    word-break: break-word;
    transition: color 0.2s ease;
}
.acad-nav-btn.active {
    background: white;
    border-color: #e2e8f0;
    border-bottom-color: white;
    box-shadow: 0 -3px 10px rgba(252,123,4,.10);
}
.acad-nav-btn.active .acad-nav-btn-icon {
    background: linear-gradient(135deg, #fc7b04, #c96004);
    color: white;
    box-shadow: 0 3px 8px rgba(252,123,4,.32);
}
.acad-nav-btn.active .acad-nav-btn-name { color: #c96004; }
.acad-nav-btn.active .acad-nav-btn-code { color: #fc7b04; }

.acad-nav-btn:hover:not(.active) {
    background: white;
    color: #334155;
}
.acad-nav-btn:hover:not(.active) .acad-nav-btn-icon {
    background: rgba(252,123,4,.18);
}
.acad-nav-btn:hover:not(.active) .acad-nav-btn-name { color: #1e293b; }

/* ── Panel por oferta ──────────────────────────── */
.acad-panel { display: none; }
.acad-panel.active { display: block; }

/* ── Hero de la oferta ─────────────────────────── */
.acad-hero {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    padding: 20px 24px;
    background: linear-gradient(135deg, #fef9f5 0%, #fff7ed 100%);
    border-bottom: 1px solid #fde8d5;
    flex-wrap: wrap;
}

.acad-hero-left {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    flex: 1;
    min-width: 0;
}

.acad-hero-icon {
    width: 48px;
    height: 48px;
    border-radius: 13px;
    background: linear-gradient(135deg, #fc7b04, #c96004);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(252,123,4,.3);
}

.acad-hero-text { min-width: 0; }

.acad-hero-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.35;
    margin-bottom: 6px;
}

.acad-hero-chips {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.acad-code-chip {
    background: rgba(252,123,4,.1);
    color: #c96004;
    padding: 2px 9px;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 700;
    font-family: 'Courier New', monospace;
    letter-spacing: 0.02em;
}

.acad-estado-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.02em;
}

.acad-chip-inscrito {
    background: rgba(34,197,94,.1);
    color: #15803d;
    border: 1px solid rgba(34,197,94,.2);
}

.acad-chip-preinscrito {
    background: rgba(245,158,11,.1);
    color: #b45309;
    border: 1px solid rgba(245,158,11,.2);
}

/* ── Stats chips del hero ──────────────────────── */
.acad-hero-stats {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: flex-start;
}

.acad-stat {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 14px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 11px;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    min-width: 130px;
}

.acad-stat-ico {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: rgba(252,123,4,.08);
    color: #fc7b04;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.acad-stat-body { display: flex; flex-direction: column; gap: 1px; }

.acad-stat-lbl {
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #94a3b8;
}

.acad-stat-val {
    font-size: 0.8rem;
    font-weight: 600;
    color: #1e293b;
}

/* ── Sección módulos ───────────────────────────── */
.acad-mods-section { padding: 20px 20px 24px; }

.acad-mods-hdr {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
}

.acad-mods-hdr-icon {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: rgba(252,123,4,.08);
    color: #fc7b04;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.acad-mods-hdr-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #475569;
    flex: 1;
}

.acad-mods-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 22px;
    padding: 0 6px;
    border-radius: 7px;
    background: linear-gradient(135deg, #fc7b04, #c96004);
    color: white;
    font-size: 0.68rem;
    font-weight: 700;
}

/* ── Tabla de módulos ──────────────────────────── */
.acad-table-wrap {
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
    overflow-x: auto;
}

.acad-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 620px;
}

.acad-table thead tr {
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
}

.acad-table thead th {
    padding: 11px 14px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #64748b;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
    white-space: nowrap;
}

.acad-table th.th-num  { width: 38px; text-align: center; padding: 11px 8px; }
.acad-table th.th-mod  { }
.acad-table th.th-doc  { width: 210px; }
.acad-table th.th-est  { width: 105px; }
.acad-table th.th-moo  { width: 155px; }
.acad-table th.th-act  { width: 44px; text-align: center; }

.acad-table tbody tr {
    transition: background 0.15s;
    animation: acadFadeRow 0.35s ease backwards;
}

@keyframes acadFadeRow {
    from { opacity: 0; transform: translateY(4px); }
    to   { opacity: 1; transform: translateY(0);   }
}

.acad-table tbody tr:hover { background: rgba(252,123,4,.025); }

.acad-table tbody td {
    padding: 13px 14px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.acad-table tbody tr:last-child td { border-bottom: none; }

/* número de fila */
.td-num {
    text-align: center;
    font-size: 0.68rem;
    font-weight: 600;
    color: #d1d5db;
    font-variant-numeric: tabular-nums;
}

/* módulo */
.acad-mod-col { display: flex; flex-direction: column; gap: 5px; }

.acad-mod-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}

.acad-mod-link:hover .acad-mod-name { color: #fc7b04; }

.acad-mod-name {
    font-weight: 600;
    font-size: 0.87rem;
    color: #1e293b;
    transition: color .2s;
    line-height: 1.3;
}

.acad-mod-name.muted { color: #94a3b8; }

.acad-ext-ico {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 16px;
    height: 16px;
    border-radius: 4px;
    background: #ea4300;
    color: white;
    font-size: 0.58rem;
    opacity: 0;
    transform: scale(.8);
    transition: all .2s;
    flex-shrink: 0;
}

.acad-mod-link:hover .acad-ext-ico { opacity: 1; transform: scale(1); }

.acad-moodle-tag {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 2px 7px;
    background: linear-gradient(135deg, #ea4300, #ff6b2b);
    color: white;
    border-radius: 20px;
    font-size: 0.59rem;
    font-weight: 700;
    width: fit-content;
    letter-spacing: .03em;
}

.acad-moodle-tag-sm {
    display: inline-flex;
    align-items: center;
    padding: 1px 6px;
    background: linear-gradient(135deg, #ea4300, #ff6b2b);
    color: white;
    border-radius: 20px;
    font-size: 0.55rem;
    font-weight: 700;
}

/* docente */
.acad-doc-cell {
    display: flex;
    align-items: center;
    gap: 9px;
}

.acad-doc-av {
    width: 33px;
    height: 33px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fc7b04, #c96004);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.78rem;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(252,123,4,.18);
}

.acad-doc-name {
    font-weight: 500;
    font-size: 0.81rem;
    color: #334155;
    line-height: 1.25;
    display: flex;
    align-items: center;
    gap: 5px;
    flex-wrap: wrap;
}

.acad-doc-empty {
    color: #94a3b8;
    font-size: 0.81rem;
    font-style: italic;
}

/* estado pill */
.acad-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.71rem;
    font-weight: 600;
    width: fit-content;
}

.acad-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    animation: acad-pulse 2s ease infinite;
}

@keyframes acad-pulse {
    0%,100% { opacity:1; }
    50%      { opacity:.4; }
}

.acad-pill-activo    { background:rgba(34,197,94,.1);  color:#15803d; }
.acad-pill-activo .acad-dot    { background:#22c55e; box-shadow:0 0 5px rgba(34,197,94,.5); }

.acad-pill-finalizado { background:rgba(14,165,233,.1); color:#0369a1; }
.acad-pill-finalizado .acad-dot { background:#0ea5e9; }

.acad-pill-cerrado   { background:rgba(100,116,139,.1);color:#475569; }
.acad-pill-cerrado .acad-dot   { background:#94a3b8; }

.acad-pill-pendiente { background:rgba(245,158,11,.1); color:#b45309; }
.acad-pill-pendiente .acad-dot { background:#f59e0b; box-shadow:0 0 5px rgba(245,158,11,.5); }

/* acceso moodle */
.acad-moo-cell { display:flex; flex-direction:column; gap:5px; }

.acad-moo-ok {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 9px; border-radius:20px; font-size:.65rem; font-weight:600;
    background:rgba(34,197,94,.1); color:#15803d;
    border:1px solid rgba(34,197,94,.2);
}

.acad-moo-block {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 9px; border-radius:20px; font-size:.65rem; font-weight:600;
    background:rgba(220,38,38,.08); color:#dc2626;
    border:1px solid rgba(220,38,38,.18);
}

.acad-moo-none {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 9px; border-radius:20px; font-size:.65rem; font-weight:500;
    background:#f1f5f9; color:#94a3b8; border:1px solid #e2e8f0;
}

.acad-btn-block {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 9px; border-radius:7px; font-size:.65rem; font-weight:600;
    border:1px solid rgba(220,38,38,.2); background:rgba(220,38,38,.05);
    color:#dc2626; cursor:pointer; transition:all .18s;
}

.acad-btn-block:hover { background:#dc2626; color:white; border-color:#dc2626; }

.acad-btn-enable {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 9px; border-radius:7px; font-size:.65rem; font-weight:600;
    border:1px solid rgba(22,163,74,.2); background:rgba(22,163,74,.05);
    color:#16a34a; cursor:pointer; transition:all .18s;
}

.acad-btn-enable:hover { background:#16a34a; color:white; border-color:#16a34a; }

/* botón ojo */
.acad-eye-btn {
    display:inline-flex; align-items:center; justify-content:center;
    width:30px; height:30px;
    border:none; background:transparent; color:#94a3b8;
    border-radius:8px; cursor:pointer; transition:all .18s; font-size:1rem;
    margin: 0 auto; display: flex;
}

.acad-eye-btn:hover {
    background:linear-gradient(135deg,#fc7b04,#c96004);
    color:white; box-shadow:0 3px 8px rgba(252,123,4,.22);
}

/* empty states */
.acad-mods-empty {
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    gap:8px; padding:28px 20px;
    background:#f8fafc; border-radius:12px;
    border:1px dashed #e2e8f0; text-align:center;
}

.acad-mods-empty i { font-size:1.6rem; color:#cbd5e1; }
.acad-mods-empty p { margin:0; font-size:.82rem; color:#94a3b8; }

.acad-no-inscripciones {
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    gap:12px; padding:52px 24px; text-align:center;
}

.acad-no-inscripciones i { font-size:2.8rem; color:#e2e8f0; }
.acad-no-inscripciones h5 { font-size:.95rem; font-weight:600; color:#94a3b8; margin:0; }
.acad-no-inscripciones p  { font-size:.82rem; color:#cbd5e1; margin:0; }

/* ══════════════════════════════════════════════════
   Hero v2 — diseño refinado con programa principal
══════════════════════════════════════════════════ */
.acad-hero.acad-hero-v2 {
    flex-direction: column;
    align-items: stretch;
    padding: 18px 22px 12px;
    background:
        radial-gradient(circle at top right, rgba(252,123,4,.10) 0%, transparent 55%),
        linear-gradient(135deg, #fff7ed 0%, #fef9f5 60%, #ffffff 100%);
    border-bottom: 1px solid #fde8d5;
    gap: 10px;
    position: relative;
    overflow: hidden;
}
.acad-hero.acad-hero-v2::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 90% 10%, rgba(252,123,4,.08), transparent 40%),
        radial-gradient(circle at 10% 90%, rgba(252,123,4,.04), transparent 40%);
    pointer-events: none;
}
.acad-hero-top {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: flex-start;
    gap: 16px;
}
.acad-hero.acad-hero-v2 .acad-hero-icon {
    width: 54px;
    height: 54px;
    border-radius: 15px;
    font-size: 1.4rem;
    box-shadow: 0 8px 22px rgba(252,123,4,.32);
    background: linear-gradient(135deg, #fc7b04, #c96004);
}
.acad-hero.acad-hero-v2 .acad-hero-text {
    flex: 1;
    min-width: 0;
}
.acad-hero-overline {
    font-size: 0.7rem;
    font-weight: 700;
    color: #c96004;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(252,123,4,.1);
    padding: 3px 9px;
    border-radius: 999px;
}
.acad-hero.acad-hero-v2 .acad-hero-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #1e1b15;
    line-height: 1.3;
    margin-bottom: 10px;
    word-break: break-word;
    letter-spacing: -0.01em;
}
.acad-hero.acad-hero-v2 .acad-hero-chips {
    gap: 6px;
}

/* Chip variants */
.acad-hero-chips .acad-code-chip { background:rgba(252,123,4,.12); color:#c96004; padding:3px 11px; border-radius:999px; font-family:'Courier New',monospace; font-size:.72rem; border:1px solid rgba(252,123,4,.18); display:inline-flex; align-items:center; gap:4px; }
.acad-fase-chip   { background:rgba(99,102,241,.1);  color:#4f46e5; padding:3px 10px; border-radius:999px; font-size:.7rem; font-weight:600; border:1px solid rgba(99,102,241,.2); display:inline-flex; align-items:center; gap:4px; }
.acad-mod-chip    { background:rgba(14,165,233,.1);  color:#0369a1; padding:3px 10px; border-radius:999px; font-size:.7rem; font-weight:600; border:1px solid rgba(14,165,233,.2); display:inline-flex; align-items:center; gap:4px; }
.acad-suc-chip    { background:rgba(20,184,166,.1);  color:#0f766e; padding:3px 10px; border-radius:999px; font-size:.7rem; font-weight:600; border:1px solid rgba(20,184,166,.2); display:inline-flex; align-items:center; gap:4px; }
.acad-gestion-chip{ background:rgba(245,158,11,.1);  color:#b45309; padding:3px 10px; border-radius:999px; font-size:.7rem; font-weight:600; border:1px solid rgba(245,158,11,.2); display:inline-flex; align-items:center; gap:4px; }
.acad-grupo-chip  { background:rgba(168,85,247,.1);  color:#7e22ce; padding:3px 10px; border-radius:999px; font-size:.7rem; font-weight:600; border:1px solid rgba(168,85,247,.2); display:inline-flex; align-items:center; gap:4px; }

.acad-hero-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 14px;
    border-radius: 10px;
    background: rgba(255,255,255,.85);
    border: 1px solid rgba(252,123,4,.25);
    color: #c96004;
    font-size: 0.76rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
    flex-shrink: 0;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.acad-hero-link:hover {
    background: linear-gradient(135deg, #fc7b04, #c96004);
    color: white;
    border-color: transparent;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(252,123,4,.28);
}
.acad-hero-link i { font-size: 0.95rem; }

.acad-hero.acad-hero-v2 .acad-hero-stats {
    position: relative;
    z-index: 1;
    gap: 9px;
    padding-top: 14px;
    border-top: 1px dashed rgba(252,123,4,.2);
}
.acad-hero.acad-hero-v2 .acad-stat {
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(4px);
    border-radius: 10px;
    padding: 8px 12px;
}

/* ── Stats grid v2 — distribución mejorada ── */
.acad-hero-stats-grid {
    position: relative;
    z-index: 1;
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 8px;
    padding-top: 10px;
    border-top: 1px dashed rgba(252,123,4,.2);
    margin-bottom: 0;
}
.acad-hero-stats-grid > .acad-stat-group {
    flex: 1 1 260px;
    min-width: 0;
    margin: 0;
}
.acad-hero-stats-grid > .acad-stat-group:last-child { margin-bottom: 0; }
.acad-stat-group {
    background: rgba(255,255,255,.92);
    border: 1px solid rgba(252,123,4,.12);
    border-radius: 11px;
    padding: 7px 12px 8px;
    backdrop-filter: blur(6px);
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
    transition: box-shadow 0.2s, transform 0.15s;
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.acad-stat-group:hover {
    box-shadow: 0 6px 18px rgba(252,123,4,.08);
    transform: translateY(-1px);
}
.acad-stat-group-title {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.6rem;
    font-weight: 800;
    color: #c96004;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    margin: 0;
    line-height: 1;
}
.acad-stat-group-title i { font-size: 0.78rem; }
.acad-stat-group-body {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.acad-stat-group-dates .acad-stat-group-body {
    justify-content: space-between;
    flex-wrap: nowrap;
}
.acad-stat-group-plan .acad-stat-group-body {
    flex-direction: column;
    align-items: stretch;
    gap: 4px;
}
.acad-stat-group-arrow {
    color: #cbd5e1;
    font-size: 0.95rem;
    flex-shrink: 0;
}
.acad-stat-mini {
    display: flex;
    flex-direction: column;
    gap: 1px;
    min-width: 0;
    flex: 1;
    line-height: 1.2;
}
.acad-stat-group-plan .acad-stat-mini {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}
.acad-stat-mini-lbl {
    font-size: 0.6rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    line-height: 1;
}
.acad-stat-mini-val {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #1e293b;
    font-variant-numeric: tabular-nums;
    line-height: 1.2;
}
.acad-stat-mini-val i { font-size: 0.9rem; }

/* Registrado por */
.acad-stat-group-by .acad-reg-body {
    display: flex;
    align-items: center;
    gap: 9px;
}
.acad-reg-avatar {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: linear-gradient(135deg, #fc7b04, #c96004);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.78rem;
    flex-shrink: 0;
    box-shadow: 0 3px 8px rgba(252,123,4,.28);
}
.acad-reg-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 1px;
    line-height: 1.2;
}
.acad-reg-name {
    font-size: 0.78rem;
    font-weight: 700;
    color: #1e293b;
}
.acad-reg-cargo {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.7rem;
    color: #4f46e5;
    font-weight: 600;
}
.acad-reg-cargo i { font-size: 0.75rem; }
.acad-reg-sede {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.68rem;
    color: #64748b;
}
.acad-reg-sede i { font-size: 0.75rem; color: #94a3b8; }

@media (max-width: 600px) {
    .acad-hero-stats-grid { grid-template-columns: 1fr; }
    .acad-stat-group-dates .acad-stat-group-body { justify-content: flex-start; }
    .acad-stat-group-arrow { transform: rotate(90deg); }
}

/* ══════════════════════════════════════════════════
   Tabla — columna NOTAS
══════════════════════════════════════════════════ */
.acad-table th.th-notas { width: 160px; }
.acad-table th.th-est { width: 110px; }

.acad-notas-cell {
    display: inline-flex;
    flex-direction: column;
    gap: 4px;
    background: linear-gradient(180deg, #faf8f3, #fff);
    border: 1px solid #ede5d5;
    border-radius: 9px;
    padding: 6px 10px;
    min-width: 140px;
}
.acad-notas-cell-blocked {
    background: repeating-linear-gradient(
        45deg,
        rgba(148, 163, 184, 0.04),
        rgba(148, 163, 184, 0.04) 6px,
        rgba(148, 163, 184, 0.09) 6px,
        rgba(148, 163, 184, 0.09) 12px
    );
    border-color: #e5e7eb;
    cursor: help;
}
.acad-notas-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.acad-nota-tag {
    font-size: 0.6rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.acad-nota-val {
    font-size: 0.85rem;
    font-weight: 800;
    padding: 1px 8px;
    border-radius: 6px;
    font-variant-numeric: tabular-nums;
    min-width: 50px;
    text-align: center;
    border: 1px solid transparent;
}
.acad-nota-empty { color: #cbd5e1; background: transparent; font-weight: 500; }
.acad-nota-ok    { color: #15803d; background: rgba(34,197,94,.12);  border-color: rgba(34,197,94,.25); }
.acad-nota-fail  { color: #b91c1c; background: rgba(239,68,68,.1);   border-color: rgba(239,68,68,.25); }

.acad-pill-finalizado { background:rgba(99,102,241,.1); color:#4f46e5; }
.acad-pill-finalizado .acad-dot { background:#6366f1; }

@media (max-width: 768px) {
    .acad-hero.acad-hero-v2 { padding: 18px; }
    .acad-hero-top { flex-wrap: wrap; }
    .acad-hero-link { width: 100%; justify-content: center; }
}
</style>

<div class="est-tabs-body" id="tab-academico">
    @if ($inscripciones->count() > 0)

        {{-- Navegación de ofertas --}}
        <div class="acad-nav-wrap">
            @foreach ($inscripciones as $key => $ins)
                @php
                    $oferta_nav      = $ins->ofertaAcademica;
                    $nombrePrograma  = $oferta_nav?->programa?->nombre ?? $oferta_nav?->posgrado?->nombre ?? 'Oferta ' . ($key + 1);
                @endphp
                <button type="button"
                    class="acad-nav-btn {{ $key == 0 ? 'active' : '' }}"
                    data-target="acad-panel-{{ $key }}"
                    title="{{ $nombrePrograma }}"
                    onclick="switchAcadTab(this)">
                    <div class="acad-nav-btn-icon"><i class="ri-graduation-cap-fill"></i></div>
                    <div class="acad-nav-btn-text">
                        @if ($oferta_nav?->codigo)
                            <span class="acad-nav-btn-code">{{ $oferta_nav->codigo }}</span>
                        @endif
                        <span class="acad-nav-btn-name">{{ $nombrePrograma }}</span>
                    </div>
                </button>
            @endforeach
        </div>

        {{-- Paneles --}}
        @foreach ($inscripciones as $key => $ins)
            @php
                $oferta  = $ins->ofertaAcademica;
                $matriculas = $ins->matriculaciones;
                $totalMods  = $matriculas ? $matriculas->count() : 0;
                $fechaInicio = $oferta?->fecha_inicio_programa
                    ? \Carbon\Carbon::parse($oferta->fecha_inicio_programa)->format('d/m/Y')
                    : '—';
                $fechaFin = $oferta?->fecha_fin_programa
                    ? \Carbon\Carbon::parse($oferta->fecha_fin_programa)->format('d/m/Y')
                    : '—';
                $fechaReg = $ins->fecha_registro
                    ? \Carbon\Carbon::parse($ins->fecha_registro)->format('d/m/Y H:i')
                    : '—';
            @endphp

            <div class="acad-panel {{ $key == 0 ? 'active' : '' }}" id="acad-panel-{{ $key }}">

                {{-- Hero de la oferta --}}
                <div class="acad-hero acad-hero-v2">
                    {{-- Bloque superior: programa + chips de identidad --}}
                    <div class="acad-hero-top">
                        <div class="acad-hero-icon">
                            <i class="ri-graduation-cap-fill"></i>
                        </div>
                        <div class="acad-hero-text">
                            @if($oferta?->posgrado?->nombre)
                                <div class="acad-hero-overline">
                                    <i class="ri-bookmark-3-line"></i> {{ $oferta->posgrado->nombre }}
                                </div>
                            @endif
                            <div class="acad-hero-title">
                                {{ $oferta?->programa?->nombre ?? ($oferta?->posgrado?->nombre ?? 'Oferta #' . $ins->ofertas_academica_id) }}
                            </div>
                            <div class="acad-hero-chips">
                                @if($oferta?->codigo)
                                    <span class="acad-code-chip"><i class="ri-hashtag"></i> {{ $oferta->codigo }}</span>
                                @endif
                                <span class="acad-estado-chip {{ $ins->estado == 'Inscrito' ? 'acad-chip-inscrito' : 'acad-chip-preinscrito' }}">
                                    <i class="{{ $ins->estado == 'Inscrito' ? 'ri-user-check-line' : 'ri-user-add-line' }}"></i>
                                    {{ $ins->estado }}
                                </span>
                                @if($oferta?->fase?->nombre)
                                    <span class="acad-fase-chip"><i class="ri-route-line"></i> {{ $oferta->fase->nombre }}</span>
                                @endif
                                @if($oferta?->modalidad?->nombre)
                                    <span class="acad-mod-chip"><i class="ri-wifi-line"></i> {{ $oferta->modalidad->nombre }}</span>
                                @endif
                                @if($oferta?->sucursal?->nombre)
                                    <span class="acad-suc-chip"><i class="ri-map-pin-line"></i> {{ $oferta->sucursal->nombre }}</span>
                                @endif
                                @if($oferta?->gestion)
                                    <span class="acad-gestion-chip"><i class="ri-calendar-event-line"></i> Gestión {{ $oferta->gestion }}</span>
                                @endif
                                @if($oferta?->grupo)
                                    <span class="acad-grupo-chip"><i class="ri-group-line"></i> Grupo {{ $oferta->grupo }}</span>
                                @endif
                            </div>
                        </div>
                        @if($oferta)
                            <a href="{{ route('admin.posgrads.ofertas.detalle', $oferta->id) }}"
                               class="acad-hero-link" title="Abrir oferta académica">
                                <i class="ri-external-link-line"></i>
                                Ver oferta
                            </a>
                        @endif
                    </div>

                    {{-- Bloque inferior: stats reorganizadas en grid --}}
                    <div class="acad-hero-stats-grid">

                        {{-- Grupo 1: Fechas del programa --}}
                        <div class="acad-stat-group acad-stat-group-dates">
                            <div class="acad-stat-group-title">
                                <i class="ri-calendar-2-line"></i> Periodo del Programa
                            </div>
                            <div class="acad-stat-group-body">
                                <div class="acad-stat-mini">
                                    <span class="acad-stat-mini-lbl">Inicio</span>
                                    <span class="acad-stat-mini-val">
                                        <i class="ri-play-circle-line" style="color:#fc7b04;"></i>
                                        {{ $fechaInicio }}
                                    </span>
                                </div>
                                <div class="acad-stat-group-arrow"><i class="ri-arrow-right-line"></i></div>
                                <div class="acad-stat-mini">
                                    <span class="acad-stat-mini-lbl">Fin</span>
                                    <span class="acad-stat-mini-val">
                                        <i class="ri-stop-circle-line" style="color:#22c55e;"></i>
                                        {{ $fechaFin }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Grupo 2: Plan de pago + Inscripción --}}
                        <div class="acad-stat-group acad-stat-group-plan">
                            <div class="acad-stat-group-title">
                                <i class="ri-file-list-3-line"></i> Inscripción
                            </div>
                            <div class="acad-stat-group-body">
                                <div class="acad-stat-mini">
                                    <span class="acad-stat-mini-lbl">Plan de pago</span>
                                    <span class="acad-stat-mini-val">
                                        <i class="ri-money-dollar-circle-line" style="color:#fc7b04;"></i>
                                        {{ $ins->planesPago?->nombre ?? '—' }}
                                    </span>
                                </div>
                                <div class="acad-stat-mini">
                                    <span class="acad-stat-mini-lbl">Registrada</span>
                                    <span class="acad-stat-mini-val">
                                        <i class="ri-time-line" style="color:#0ea5e9;"></i>
                                        {{ $fechaReg }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Grupo 3: Registrado por --}}
                        @if($ins->trabajador_cargo?->cargo?->nombre || $ins->trabajador_cargo?->trabajador?->persona)
                            @php
                                $regPersona  = $ins->trabajador_cargo?->trabajador?->persona;
                                $regNombre   = $regPersona
                                    ? trim(($regPersona->nombres ?? '') . ' ' . ($regPersona->apellido_paterno ?? ''))
                                    : null;
                                $regIniciales = $regNombre
                                    ? strtoupper(mb_substr($regPersona->nombres ?? '', 0, 1) . mb_substr($regPersona->apellido_paterno ?? '', 0, 1))
                                    : 'AD';
                            @endphp
                            <div class="acad-stat-group acad-stat-group-by">
                                <div class="acad-stat-group-title">
                                    <i class="ri-user-star-line"></i> Registrado por
                                </div>
                                <div class="acad-stat-group-body acad-reg-body">
                                    <div class="acad-reg-avatar">{{ $regIniciales }}</div>
                                    <div class="acad-reg-info">
                                        @if($regNombre)
                                            <div class="acad-reg-name">{{ $regNombre }}</div>
                                        @endif
                                        <div class="acad-reg-cargo">
                                            <i class="ri-briefcase-line"></i> {{ $ins->trabajador_cargo->cargo->nombre ?? '—' }}
                                        </div>
                                        @if($ins->trabajador_cargo?->sucursale?->sede?->nombre || $ins->trabajador_cargo?->sucursale?->nombre)
                                            <div class="acad-reg-sede">
                                                <i class="ri-map-pin-line"></i>
                                                @if($ins->trabajador_cargo?->sucursale?->nombre)
                                                    {{ $ins->trabajador_cargo->sucursale->nombre }}
                                                @endif
                                                @if($ins->trabajador_cargo?->sucursale?->sede?->nombre)
                                                    · {{ $ins->trabajador_cargo->sucursale->sede->nombre }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Módulos matriculados --}}
                <div class="acad-mods-section">
                    <div class="acad-mods-hdr">
                        <div class="acad-mods-hdr-icon"><i class="ri-book-3-line"></i></div>
                        <span class="acad-mods-hdr-label">Módulos matriculados</span>
                        <span class="acad-mods-count">{{ $totalMods }}</span>
                    </div>

                    @if ($matriculas && $totalMods > 0)
                        <div class="acad-table-wrap">
                            <table class="acad-table">
                                <thead>
                                    <tr>
                                        <th class="th-num">#</th>
                                        <th class="th-mod">Módulo</th>
                                        <th class="th-doc">Docente</th>
                                        <th class="th-est">Estado</th>
                                        <th class="th-notas">Notas</th>
                                        <th class="th-moo">Acceso Moodle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($matriculas as $index => $matricula)
                                        @php
                                            $modulo          = $matricula->modulo;
                                            $docente         = $modulo?->docente;
                                            $moodleVinculado = $modulo && $modulo->moodle_course_id;
                                            $docenteMoodle   = $docente && $docente->moodle_id;
                                            $estado          = $modulo?->estado ?? '—';
                                            $pillClass       = match($estado) {
                                                'Concluido','Finalizado' => 'acad-pill-finalizado',
                                                'En Desarrollo','Activo' => 'acad-pill-activo',
                                                'Cerrado','Inactivo'     => 'acad-pill-cerrado',
                                                default                  => 'acad-pill-pendiente',
                                            };
                                            $moodleMatric = null;
                                            if ($moodleVinculado) {
                                                $moodleMatric = \App\Models\MoodleMatricula::where('inscripcion_id', $ins->id)
                                                    ->where('modulo_id', $modulo->id)
                                                    ->first();
                                            }
                                            $accesoSuspendido  = $moodleMatric?->acceso_suspendido ?? false;
                                            $tieneMatricMoodle = $moodleMatric !== null;
                                            $moduloConcluido   = $estado === 'Concluido';
                                            $notaFinal         = $matricula->nota_regular;
                                            $nota2da           = $matricula->nota_nivelacion;
                                            $aprobacion        = $oferta?->nota_minima ?? 71;
                                        @endphp
                                        <tr style="animation-delay:{{ $index * 0.04 }}s">
                                            <td class="td-num">{{ $index + 1 }}</td>

                                            {{-- Módulo --}}
                                            <td>
                                                <div class="acad-mod-col">
                                                    @if($moodleVinculado)
                                                        <a href="{{ config('moodle.url') }}/course/view.php?id={{ $modulo->moodle_course_id }}"
                                                           target="_blank"
                                                           class="acad-mod-link"
                                                           title="Abrir curso en Moodle">
                                                            <span class="acad-mod-name">{{ $modulo->nombre }}</span>
                                                            <span class="acad-ext-ico"><i class="ri-external-link-line"></i></span>
                                                        </a>
                                                    @else
                                                        <span class="acad-mod-name {{ $modulo ? '' : 'muted' }}">
                                                            {{ $modulo?->nombre ?? '—' }}
                                                        </span>
                                                    @endif
                                                    @if($moodleVinculado)
                                                        <span class="acad-moodle-tag"><i class="ri-links-line"></i> Moodle</span>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Docente --}}
                                            <td>
                                                @if($docente)
                                                    <div class="acad-doc-cell">
                                                        <div class="acad-doc-av">
                                                            {{ strtoupper(substr($docente->persona->nombres ?? 'D', 0, 1)) }}
                                                        </div>
                                                        <span class="acad-doc-name">
                                                            {{ $docente->persona->nombres ?? '' }}
                                                            {{ $docente->persona->apellido_paterno ?? '' }}
                                                            @if($docenteMoodle)
                                                                <span class="acad-moodle-tag-sm"><i class="ri-links-line"></i></span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="acad-doc-empty">Sin asignar</span>
                                                @endif
                                            </td>

                                            {{-- Estado del módulo --}}
                                            <td>
                                                <span class="acad-pill {{ $pillClass }}">
                                                    <span class="acad-dot"></span>
                                                    {{ $estado }}
                                                </span>
                                            </td>

                                            {{-- Notas --}}
                                            <td>
                                                @if (!$moduloConcluido)
                                                    <div class="acad-notas-cell acad-notas-cell-blocked"
                                                         title="Las notas se mostrarán cuando el módulo esté Concluido">
                                                        <div class="acad-notas-row">
                                                            <span class="acad-nota-tag">Final</span>
                                                            <span class="acad-nota-val acad-nota-empty">—</span>
                                                        </div>
                                                        <div class="acad-notas-row">
                                                            <span class="acad-nota-tag">2da Inst.</span>
                                                            <span class="acad-nota-val acad-nota-empty">—</span>
                                                        </div>
                                                    </div>
                                                @else
                                                    @php
                                                        $nfNum = is_numeric($notaFinal) ? (float) $notaFinal : null;
                                                        $n2Num = is_numeric($nota2da)   ? (float) $nota2da   : null;
                                                        $nfCls = $nfNum === null ? 'acad-nota-empty' : ($nfNum >= $aprobacion ? 'acad-nota-ok' : 'acad-nota-fail');
                                                        $n2Cls = $n2Num === null ? 'acad-nota-empty' : ($n2Num >= $aprobacion ? 'acad-nota-ok' : 'acad-nota-fail');
                                                    @endphp
                                                    <div class="acad-notas-cell">
                                                        <div class="acad-notas-row">
                                                            <span class="acad-nota-tag">Final</span>
                                                            <span class="acad-nota-val {{ $nfCls }}">{{ $nfNum !== null ? number_format($nfNum, 2) : '—' }}</span>
                                                        </div>
                                                        <div class="acad-notas-row">
                                                            <span class="acad-nota-tag">2da Inst.</span>
                                                            <span class="acad-nota-val {{ $n2Cls }}">{{ $n2Num !== null ? number_format($n2Num, 2) : '—' }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- Acceso Moodle --}}
                                            <td>
                                                <div class="acad-moo-cell">
                                                    @if($moodleVinculado && $tieneMatricMoodle)
                                                        @if($accesoSuspendido)
                                                            <span class="acad-moo-block"><i class="ri-lock-fill"></i> Bloqueado</span>
                                                            <button type="button"
                                                                class="acad-btn-enable btn-est-moodle-toggle"
                                                                data-mod="{{ $modulo->id }}"
                                                                data-ins="{{ $ins->id }}"
                                                                data-nombre="{{ addslashes($modulo->nombre) }}"
                                                                data-sus="0">
                                                                <i class="ri-lock-unlock-line"></i> Habilitar
                                                            </button>
                                                        @else
                                                            <span class="acad-moo-ok"><i class="ri-check-circle-fill"></i> Habilitado</span>
                                                            <button type="button"
                                                                class="acad-btn-block btn-est-moodle-toggle"
                                                                data-mod="{{ $modulo->id }}"
                                                                data-ins="{{ $ins->id }}"
                                                                data-nombre="{{ addslashes($modulo->nombre) }}"
                                                                data-sus="1">
                                                                <i class="ri-lock-line"></i> Bloquear
                                                            </button>
                                                        @endif
                                                    @elseif($moodleVinculado)
                                                        <span class="acad-moo-none"><i class="ri-minus-circle-line"></i> Sin matrícula</span>
                                                    @else
                                                        <span class="acad-moo-none"><i class="ri-minus-circle-line"></i> Sin Moodle</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="acad-mods-empty">
                            <i class="ri-book-line"></i>
                            <p>Sin módulos matriculados</p>
                        </div>
                    @endif
                </div>

            </div>
        @endforeach

    @else
        <div class="acad-no-inscripciones">
            <i class="ri-book-open-line"></i>
            <h5>Sin ofertas académicas</h5>
            <p>El estudiante no tiene inscripciones registradas</p>
        </div>
    @endif
</div>

{{-- Modal Confirmar Acceso Moodle --}}
<div class="modal fade" id="modalConfirmarAccesoEstudiante" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 10px 40px rgba(0,0,0,.18);">
            <div class="modal-header" style="border-bottom:1px solid #e2e8f0;padding:1rem 1.5rem;">
                <h5 class="modal-title" id="modalConfirmarAccesoEstudianteTitle"
                    style="font-weight:600;color:#1e293b;font-size:.93rem;">
                    <i class="ri-shield-keyhole-line me-2"></i>Confirmar Acción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <p id="modalConfirmarAccesoEstudianteMsg"
                    style="color:#475569;font-size:.88rem;line-height:1.6;margin:0;"></p>
                <div style="margin-top:1rem;padding:.8rem 1rem;background:#f8fafc;border-radius:10px;border-left:4px solid #fc7b04;">
                    <span style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;">Módulo</span>
                    <div style="font-weight:600;font-size:.9rem;color:#1e293b;margin-top:2px;" id="modalModuloEstudiante"></div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;gap:8px;">
                <button type="button" data-bs-dismiss="modal"
                    style="padding:.42rem 1rem;border-radius:8px;border:1px solid #cbd5e1;background:white;color:#475569;font-weight:500;font-size:.83rem;cursor:pointer;">
                    Cancelar
                </button>
                <button type="button" id="btnConfirmarAccesoEstudiante"
                    style="padding:.42rem 1.1rem;border-radius:8px;border:none;background:#fc7b04;color:white;font-weight:600;font-size:.83rem;cursor:pointer;transition:background .2s;">
                    <i class="ri-check-line me-1"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    /* ── Tab switcher de ofertas ── */
    window.switchAcadTab = function (clickedBtn) {
        const nav = clickedBtn.closest('.acad-nav-wrap');
        if (!nav) return;
        nav.querySelectorAll('.acad-nav-btn').forEach(function (b) { b.classList.remove('active'); });
        clickedBtn.classList.add('active');
        const targetId = clickedBtn.dataset.target;
        const container = nav.closest('.est-tabs-body');
        if (!container) return;
        container.querySelectorAll('.acad-panel').forEach(function (p) { p.classList.remove('active'); });
        const panel = container.querySelector('#' + targetId);
        if (panel) panel.classList.add('active');
    };

    /* ── Toast helper ── */
    function toastEst(tipo, mensaje) {
        if (typeof window.toast === 'function') { window.toast(tipo, mensaje); return; }
        const colors = { success: '#16a34a', error: '#dc2626', warning: '#f59e0b' };
        const el = document.createElement('div');
        el.style.cssText = 'position:fixed;top:1.5rem;right:1.5rem;z-index:10050;padding:.75rem 1.25rem;' +
            'border-radius:10px;background:' + (colors[tipo] || '#475569') + ';color:white;font-size:.875rem;' +
            'font-weight:500;box-shadow:0 4px 20px rgba(0,0,0,.15);transition:opacity .3s;';
        el.textContent = mensaje;
        document.body.appendChild(el);
        setTimeout(function () {
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 300);
        }, 3700);
    }

    /* ── Toggle acceso Moodle ── */
    let _pendiente = null;
    let _modalInst = null;

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-est-moodle-toggle');
        if (!btn) return;
        e.preventDefault();
        e.stopPropagation();

        _pendiente = {
            btn        : btn,
            moduloId   : btn.dataset.mod,
            inscripcionId: btn.dataset.ins,
            nombre     : btn.dataset.nombre || 'módulo',
            suspender  : btn.dataset.sus === '1',
        };

        const susp = _pendiente.suspender;
        document.getElementById('modalConfirmarAccesoEstudianteTitle').innerHTML =
            '<i class="ri-shield-keyhole-line me-2"></i> ' + (susp ? 'Bloquear Acceso' : 'Habilitar Acceso');
        document.getElementById('modalConfirmarAccesoEstudianteMsg').innerHTML = susp
            ? '¿Está seguro que desea <strong>bloquear</strong> el acceso a la plataforma Moodle para este módulo? El estudiante no podrá acceder al contenido hasta que se reactive.'
            : '¿Está seguro que desea <strong>habilitar</strong> el acceso a la plataforma Moodle para este módulo? El estudiante podrá acceder nuevamente al contenido.';
        document.getElementById('modalModuloEstudiante').textContent = _pendiente.nombre;
        document.getElementById('btnConfirmarAccesoEstudiante').style.background =
            susp ? '#dc2626' : '#16a34a';

        const modalEl = document.getElementById('modalConfirmarAccesoEstudiante');
        if (!_modalInst) _modalInst = new bootstrap.Modal(modalEl);
        _modalInst.show();
    });

    document.getElementById('btnConfirmarAccesoEstudiante').addEventListener('click', function (e) {
        e.preventDefault();
        if (!_pendiente) return;

        const { btn, moduloId, inscripcionId, nombre, suspender } = _pendiente;
        _pendiente = null;

        if (_modalInst) _modalInst.hide();

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:.75rem;height:.75rem;"></span>';

        fetch('/admin/posgrads/modulos/' + moduloId + '/moodle/suspender-acceso?_=' + Date.now(), {
            method : 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body   : JSON.stringify({ inscripcion_id: parseInt(inscripcionId), suspender: suspender })
        })
        .then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(function (r) {
            if (r.success) {
                toastEst('success', 'Acceso ' + (suspender ? 'bloqueado' : 'habilitado') + ' en «' + nombre + '».');
                const cell = btn.closest('td');
                if (cell) {
                    const mooCell = cell.querySelector('.acad-moo-cell');
                    const ne = nombre.replace(/"/g, '&quot;');
                    if (mooCell) {
                        if (suspender) {
                            mooCell.innerHTML =
                                '<span class="acad-moo-block"><i class="ri-lock-fill"></i> Bloqueado</span>' +
                                '<button type="button" class="acad-btn-enable btn-est-moodle-toggle"' +
                                ' data-mod="' + moduloId + '" data-ins="' + inscripcionId +
                                '" data-nombre="' + ne + '" data-sus="0">' +
                                '<i class="ri-lock-unlock-line"></i> Habilitar</button>';
                        } else {
                            mooCell.innerHTML =
                                '<span class="acad-moo-ok"><i class="ri-check-circle-fill"></i> Habilitado</span>' +
                                '<button type="button" class="acad-btn-block btn-est-moodle-toggle"' +
                                ' data-mod="' + moduloId + '" data-ins="' + inscripcionId +
                                '" data-nombre="' + ne + '" data-sus="1">' +
                                '<i class="ri-lock-line"></i> Bloquear</button>';
                        }
                    }
                }
            } else {
                toastEst('error', r.message || 'Error al actualizar acceso.');
                btn.disabled = false;
                btn.innerHTML = suspender
                    ? '<i class="ri-lock-line"></i> Bloquear'
                    : '<i class="ri-lock-unlock-line"></i> Habilitar';
            }
        })
        .catch(function (err) {
            console.error(err);
            toastEst('error', 'Error de conexión.');
            btn.disabled = false;
            btn.innerHTML = suspender
                ? '<i class="ri-lock-line"></i> Bloquear'
                : '<i class="ri-lock-unlock-line"></i> Habilitar';
        });
    });

})();
</script>
