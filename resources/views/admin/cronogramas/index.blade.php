@extends('layouts.master')

@section('title', 'Cronograma General')

@section('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300..700&family=DM+Sans:opsz,wght@9..40,300..700&display=swap" rel="stylesheet">
<style>
:root {
    --crono-primary:      #fc7b04;
    --crono-primary-dark: #9a4904;
    --crono-primary-deep: #6b3102;
    --crono-accent:       #e86e04;
    --crono-bg-warm:      #f8f5f1;
    --crono-card-bg:      #ffffff;
    --crono-text:         #1e1610;
    --crono-text-muted:   #7a7068;
    --crono-border:       #e8e2da;
    --crono-border-light: #f2ede6;
    --crono-shadow-sm:    0 1px 4px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.04);
    --crono-shadow-md:    0 4px 20px rgba(0,0,0,0.07), 0 2px 8px rgba(0,0,0,0.04);
    --crono-shadow-lg:    0 16px 48px rgba(0,0,0,0.09), 0 6px 20px rgba(0,0,0,0.05);
    --crono-shadow-glow:  0 8px 32px rgba(154,73,4,0.18);
    --crono-success:      #16a34a;
    --crono-danger:       #dc2626;
    --crono-warning:      #d97706;
    --crono-info:         #2563eb;
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
}

body { font-family: 'DM Sans', sans-serif; -webkit-font-smoothing: antialiased; }
h1,h2,h3,h4,h5,h6,.modal-title,.crono-calendar-title,.crono-section-title,.crono-detail-section-title { font-family: 'Lexend', sans-serif; }

/* ── PAGE BACKGROUND ── */
.crono-page { position: relative; min-height: 100%; background: var(--crono-bg-warm); }

/* ── ANIMATIONS ── */
.crono-animate { opacity: 0; transform: translateY(16px); animation: cronoFadeUp 0.55s cubic-bezier(0.16,1,0.3,1) forwards; }
.crono-animate-1 { animation-delay: 0.04s; }
.crono-animate-2 { animation-delay: 0.1s; }
.crono-animate-3 { animation-delay: 0.17s; }
@keyframes cronoFadeUp { to { opacity: 1; transform: translateY(0); } }
@keyframes cronoSlideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
@keyframes shimmer { 0%,100%{ opacity:0.6; } 50%{ opacity:1; } }

/* ══════════════════════════════════════
   PAGE HEADER  (franja superior)
══════════════════════════════════════ */
.crono-header-page {
    position: relative;
    padding: 1.6rem 0 1.4rem;
    background: linear-gradient(135deg, #fff 0%, #fef9f4 55%, #fdf5ec 100%);
    border-bottom: 1px solid var(--crono-border);
    overflow: hidden;
}
.crono-header-page::before {
    content: '';
    position: absolute; top: -60%; right: -8%;
    width: 560px; height: 560px; border-radius: 50%;
    background: radial-gradient(circle, rgba(252,123,4,0.07) 0%, transparent 65%);
    pointer-events: none;
}
.crono-header-page::after {
    content: '';
    position: absolute; bottom: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(154,73,4,0.2), transparent);
}
.crono-header-inner { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; position: relative; z-index: 1; }
.crono-header-left { display: flex; align-items: center; gap: 1.15rem; }
.crono-header-icon {
    width: 54px; height: 54px;
    background: linear-gradient(135deg, #9a4904 0%, #fc7b04 100%);
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(154,73,4,0.3);
}
.crono-header-icon i { font-size: 1.5rem; color: #fff; }
.crono-header-text h1 {
    font-size: 1.4rem; font-weight: 700;
    color: var(--crono-text); margin: 0 0 0.15rem;
    letter-spacing: -0.025em;
}
.crono-header-text p { font-size: 0.82rem; color: var(--crono-text-muted); margin: 0; }
.crono-header-right { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }

.crono-stat-card {
    display: flex; align-items: center; gap: 0.8rem;
    background: #fff;
    border: 1px solid var(--crono-border);
    border-radius: var(--radius-md);
    padding: 0.65rem 1.2rem;
    box-shadow: var(--crono-shadow-sm);
    transition: box-shadow 0.25s, transform 0.2s;
}
.crono-stat-card:hover { box-shadow: var(--crono-shadow-md); transform: translateY(-2px); }
.crono-stat-icon {
    width: 38px; height: 38px;
    background: linear-gradient(135deg, rgba(154,73,4,0.1), rgba(252,123,4,0.06));
    border-radius: 10px; display: flex; align-items: center; justify-content: center;
}
.crono-stat-icon i { color: var(--crono-primary-dark); font-size: 1.05rem; }
.crono-stat-num { font-size: 1.4rem; font-weight: 800; color: var(--crono-primary-dark); line-height: 1; letter-spacing: -0.03em; }
.crono-stat-label { font-size: 0.7rem; color: var(--crono-text-muted); margin-top: 2px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.04em; }

/* ══════════════════════════════════════
   MAIN CARD
══════════════════════════════════════ */
.crono-card {
    background: var(--crono-card-bg);
    border: 1px solid var(--crono-border);
    border-radius: var(--radius-xl);
    box-shadow: var(--crono-shadow-md);
    overflow: hidden;
}

/* ── Gradient card header ── */
.crono-header {
    background: linear-gradient(135deg, #5c2a04 0%, #8c4504 35%, #c46204 70%, #fc7b04 100%);
    padding: 1.6rem 2rem;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
    position: relative; overflow: hidden;
}
.crono-header::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 85% 50%, rgba(255,200,100,0.15) 0%, transparent 55%);
    pointer-events: none;
}
.crono-header::after {
    content: '';
    position: absolute; bottom: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, rgba(255,255,255,0), rgba(255,255,255,0.15), rgba(255,255,255,0));
}
.crono-header h4 {
    color: #fff; margin: 0; font-weight: 700;
    display: flex; align-items: center; gap: 0.6rem;
    position: relative; z-index: 1;
    font-size: 1.15rem; letter-spacing: -0.01em;
    text-shadow: 0 1px 4px rgba(0,0,0,0.2);
}
.crono-header h4 i { font-size: 1.25rem; }
.crono-header .breadcrumb { background: transparent; margin: 0 0 0.45rem; padding: 0; position: relative; z-index: 1; }
.crono-header .breadcrumb-item { font-size: 0.77rem; }
.crono-header .breadcrumb-item a { color: rgba(255,255,255,0.7); text-decoration: none; transition: color 0.2s; }
.crono-header .breadcrumb-item a:hover { color: #fff; }
.crono-header .breadcrumb-item.active { color: rgba(255,255,255,0.92); }
.crono-header .breadcrumb-item+.breadcrumb-item::before { color: rgba(255,255,255,0.35); }

.crono-stat-badge {
    background: rgba(0,0,0,0.2);
    color: #fff; font-size: 0.82rem; font-weight: 700;
    padding: 0.45rem 1.1rem; border-radius: 24px;
    position: relative; z-index: 1;
    display: inline-flex; align-items: center; gap: 0.45rem;
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,0.18);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.crono-stat-badge i { font-size: 1rem; }

/* ══════════════════════════════════════
   FILTERS
══════════════════════════════════════ */
.crono-filters {
    padding: 1.25rem 1.75rem;
    border-bottom: 1px solid var(--crono-border-light);
    background: linear-gradient(180deg, #fff 0%, #faf7f3 100%);
    position: relative;
}
.crono-filters::before {
    content: '';
    position: absolute; top: 0; left: 1.75rem; right: 1.75rem; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(154,73,4,0.12), transparent);
}
.crono-filter-label {
    font-size: 0.68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.07em;
    color: var(--crono-text-muted); margin-bottom: 0.45rem;
    display: flex; align-items: center; gap: 4px;
}
.crono-filter-select {
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--crono-border);
    padding: 0.6rem 2.2rem 0.6rem 0.9rem;
    font-size: 0.855rem; font-weight: 500;
    background-color: #faf8f5;
    color: var(--crono-text);
    transition: all 0.22s; width: 100%;
    cursor: pointer; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%239a4904'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.8rem center;
    font-family: 'DM Sans', sans-serif;
    box-shadow: var(--crono-shadow-sm);
}
.crono-filter-select:focus {
    border-color: var(--crono-primary-dark);
    box-shadow: 0 0 0 3px rgba(154,73,4,0.12);
    outline: none; background-color: #fff;
}
.crono-filter-select:disabled { opacity: 0.45; cursor: not-allowed; }

.crono-checkbox-wrapper {
    display: flex; align-items: center; gap: 0.65rem;
    padding: 0.65rem 1rem;
    background: #faf8f5;
    border: 1.5px solid var(--crono-border);
    border-radius: var(--radius-sm);
    cursor: pointer; transition: all 0.22s;
    box-shadow: var(--crono-shadow-sm);
}
.crono-checkbox-wrapper:hover { border-color: var(--crono-primary-dark); background: #fff6ed; box-shadow: 0 0 0 3px rgba(154,73,4,0.08); }
.crono-checkbox-wrapper input[type="checkbox"] { accent-color: var(--crono-primary-dark); width: 16px; height: 16px; cursor: pointer; flex-shrink: 0; }
.crono-checkbox-wrapper label { color: var(--crono-text); margin: 0; cursor: pointer; font-weight: 500; font-size: 0.83rem; user-select: none; }
.crono-checkbox-wrapper input:checked ~ label { color: var(--crono-primary-dark); font-weight: 600; }

/* ══════════════════════════════════════
   OFERTAS SECTION
══════════════════════════════════════ */
.crono-ofertas-section {
    padding: 1.35rem 1.75rem;
    border-bottom: 1px solid var(--crono-border-light);
    background: linear-gradient(180deg, #faf7f3 0%, #fff 100%);
    animation: cronoSlideDown 0.3s ease;
}
.crono-section-header { display: flex; align-items: center; gap: 0.4rem; margin-bottom: 1rem; }
.crono-section-title {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--crono-text-muted);
    display: flex; align-items: center; gap: 0.4rem;
}
.crono-section-title i { color: var(--crono-primary-dark); font-size: 0.95rem; }
.crono-count-badge {
    font-size: 0.68rem; padding: 0.22rem 0.65rem;
    background: linear-gradient(135deg, rgba(154,73,4,0.08), rgba(252,123,4,0.05));
    color: var(--crono-primary-dark);
    border: 1px solid rgba(154,73,4,0.15);
    border-radius: 20px; font-weight: 700;
    margin-left: auto;
}

.crono-ofertas-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; }

.crono-oferta-card {
    background: #fff;
    border: 1.5px solid var(--crono-border);
    border-radius: var(--radius-md);
    padding: 1.1rem 1.2rem 1.1rem 1.4rem;
    cursor: pointer; transition: all 0.25s cubic-bezier(.4,0,.2,1);
    position: relative; overflow: hidden;
    box-shadow: var(--crono-shadow-sm);
}
.crono-oferta-card::before {
    content: '';
    position: absolute; left: 0; top: 0; bottom: 0; width: 5px;
    background: var(--card-color, var(--crono-primary));
    border-radius: 0 3px 3px 0;
    transition: width 0.2s;
}
.crono-oferta-card::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, var(--card-color, var(--crono-primary)) 0%, transparent 60%);
    opacity: 0; transition: opacity 0.25s;
    pointer-events: none;
    border-radius: inherit;
}
.crono-oferta-card:hover { transform: translateY(-4px); box-shadow: var(--crono-shadow-md); border-color: var(--card-color, var(--crono-primary)); }
.crono-oferta-card:hover::after { opacity: 0.04; }
.crono-oferta-card:hover::before { width: 6px; }
.crono-oferta-card.active {
    border-color: var(--card-color, var(--crono-primary));
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--card-color, var(--crono-primary)) 18%, transparent), var(--crono-shadow-md);
    transform: translateY(-4px);
}
.crono-oferta-card.active::after { opacity: 0.05; }

.crono-oferta-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; }
.crono-oferta-codigo { font-size: 0.9rem; font-weight: 700; color: var(--crono-text); font-family: 'Lexend', sans-serif; letter-spacing: -0.01em; }
.crono-oferta-badge {
    font-size: 0.66rem; padding: 0.22rem 0.65rem; border-radius: 20px;
    background: var(--card-color, var(--crono-primary));
    color: #fff; font-weight: 700;
    display: inline-flex; align-items: center; gap: 0.25rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.crono-oferta-nombre { font-size: 0.8rem; color: var(--crono-text-muted); line-height: 1.45; margin-bottom: 0.75rem; }
.crono-oferta-meta {
    display: flex; gap: 1rem;
    padding-top: 0.7rem;
    border-top: 1px dashed var(--crono-border);
    font-size: 0.72rem; color: var(--crono-text-muted);
}
.crono-oferta-meta span { display: flex; align-items: center; gap: 0.3rem; }
.crono-oferta-meta i { color: var(--crono-primary-dark); }

/* ══════════════════════════════════════
   CALENDAR SECTION
══════════════════════════════════════ */
.crono-calendar-card { border-top: 1px solid var(--crono-border-light); }

.crono-calendar-header {
    padding: 1.1rem 1.75rem;
    border-bottom: 1px solid var(--crono-border-light);
    background: linear-gradient(135deg, #fff 0%, #faf7f3 100%);
    display: flex; align-items: center; justify-content: space-between;
}
.crono-calendar-title {
    font-size: 0.95rem; font-weight: 700;
    color: var(--crono-text);
    display: flex; align-items: center; gap: 0.5rem;
    letter-spacing: -0.01em;
}
.crono-calendar-title i { color: var(--crono-primary-dark); font-size: 1.05rem; }
.crono-calendar-body { padding: 1.25rem 1.5rem 1.5rem; }

/* ── FullCalendar ── */
.fc .fc-toolbar { margin-bottom: 1.25rem; }
.fc .fc-toolbar-title {
    font-size: 1.05rem; font-weight: 700;
    color: var(--crono-text); font-family: 'Lexend', sans-serif;
    letter-spacing: -0.015em;
}
.fc .fc-button {
    border-radius: var(--radius-sm) !important;
    font-size: 0.77rem !important;
    padding: 0.42rem 0.85rem !important;
    font-weight: 600 !important;
    font-family: 'DM Sans', sans-serif;
    transition: all 0.2s !important;
    box-shadow: var(--crono-shadow-sm) !important;
}
.fc .fc-button-primary {
    background: #fff !important;
    border: 1.5px solid var(--crono-border) !important;
    color: var(--crono-text) !important;
}
.fc .fc-button-primary:hover {
    background: linear-gradient(135deg, var(--crono-primary-dark), var(--crono-primary)) !important;
    border-color: var(--crono-primary-dark) !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(154,73,4,0.22) !important;
    transform: translateY(-1px);
}
.fc .fc-button-primary:disabled { opacity: 0.38 !important; box-shadow: none !important; }
.fc .fc-button-primary:not(:disabled).fc-button-active {
    background: linear-gradient(135deg, var(--crono-primary-dark), var(--crono-primary)) !important;
    border-color: var(--crono-primary-dark) !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(154,73,4,0.25) !important;
}
.fc .fc-button-group { gap: 2px; }

.fc .fc-col-header-cell {
    background: linear-gradient(180deg, #faf7f3 0%, #f5f0e8 100%);
    font-weight: 700; font-size: 0.68rem;
    color: var(--crono-text-muted); text-transform: uppercase;
    letter-spacing: 0.06em; font-family: 'Lexend', sans-serif;
    padding: 0.65rem 0;
    border-color: var(--crono-border) !important;
}
.fc .fc-scrollgrid { border-radius: var(--radius-md); overflow: hidden; border-color: var(--crono-border) !important; }
.fc .fc-scrollgrid td, .fc .fc-scrollgrid th { border-color: var(--crono-border) !important; }

.fc .fc-daygrid-day-number {
    font-size: 0.82rem; font-weight: 600; color: var(--crono-text-muted);
    padding: 5px 7px;
}
.fc .fc-daygrid-day:hover { background: rgba(252,123,4,0.025) !important; }
.fc .fc-daygrid-day.fc-day-today { background: rgba(154,73,4,0.04) !important; }
.fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
    background: linear-gradient(135deg, var(--crono-primary-dark), var(--crono-primary));
    color: #fff; border-radius: 50%;
    width: 28px; height: 28px;
    display: flex; align-items: center; justify-content: center;
    margin: 3px; font-weight: 700;
    box-shadow: 0 2px 8px rgba(154,73,4,0.3);
}
.fc .fc-daygrid-day-frame { min-height: 85px; }

.fc-event {
    border-radius: 6px !important;
    font-size: 0.76rem !important;
    padding: 3px 8px !important;
    border: none !important;
    cursor: pointer;
    color: #fff !important;
    font-weight: 600;
    transition: filter 0.18s, transform 0.15s, box-shadow 0.18s;
    margin: 1px 3px !important;
    box-shadow: 0 1px 4px rgba(0,0,0,0.15) !important;
}
.fc-event:hover {
    filter: brightness(1.08);
    transform: scale(1.03) translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
    cursor: pointer;
}

/* ── Lista view ── */
.fc .fc-list-table { border-color: var(--crono-border) !important; }
.fc-list-event:hover td { background: rgba(154,73,4,0.04) !important; }
.fc-list-event-time { font-weight: 700; color: var(--crono-primary-dark) !important; font-size: 0.8rem; }
.fc .fc-list-day-text, .fc .fc-list-day-side-text {
    font-weight: 700; color: var(--crono-text);
    font-family: 'Lexend', sans-serif; font-size: 0.85rem;
}
.fc .fc-list-day-cushion {
    background: linear-gradient(90deg, rgba(154,73,4,0.06), rgba(252,123,4,0.03)) !important;
    border-bottom: 1px solid var(--crono-border) !important;
}

/* ── CURSOR: grab / grabbing en el calendario ── */
.fc-view-harness,
.fc-daygrid-body,
.fc-timegrid-body,
.fc .fc-scroller,
.fc-daygrid-day-frame,
.fc-list-table {
    cursor: grab !important;
}
.fc-view-harness:active,
.fc-daygrid-body:active,
.fc-timegrid-body:active,
.fc .fc-scroller:active,
.fc-daygrid-day-frame:active {
    cursor: grabbing !important;
}
/* Los eventos y botones mantienen pointer/default */
.fc-event,
.fc-event * { cursor: pointer !important; }
.fc .fc-button { cursor: pointer !important; }
.fc .fc-daygrid-day-number { cursor: default !important; }

/* ══════════════════════════════════════
   MODAL
══════════════════════════════════════ */
.crono-modal .modal-content {
    border: none; border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: 0 24px 64px rgba(0,0,0,0.14), 0 8px 24px rgba(0,0,0,0.08);
}
.crono-modal .modal-header {
    border-bottom: 1px solid var(--crono-border-light);
    padding: 1.15rem 1.5rem;
    background: linear-gradient(135deg, #fff 0%, #faf7f3 100%);
}
.crono-modal .modal-title {
    font-size: 1rem; font-weight: 700;
    color: var(--crono-text); letter-spacing: -0.015em;
}
.crono-modal .modal-header .btn-close { transition: transform 0.22s, opacity 0.2s; opacity: 0.4; }
.crono-modal .modal-header .btn-close:hover { transform: rotate(90deg); opacity: 1; }
.crono-modal .modal-body { padding: 1.35rem; background: #faf8f5; }

.crono-detail-card {
    background: #fff; border-radius: var(--radius-md);
    padding: 1.2rem 1.35rem;
    border: 1px solid var(--crono-border);
    margin-bottom: 0.85rem;
    box-shadow: var(--crono-shadow-sm);
    transition: box-shadow 0.2s;
}
.crono-detail-card:hover { box-shadow: var(--crono-shadow-md); }
.crono-detail-card:last-child { margin-bottom: 0; }

.crono-detail-section-title {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--crono-primary-dark);
    margin-bottom: 1rem; padding-bottom: 0.55rem;
    border-bottom: 2px solid;
    border-image: linear-gradient(90deg, var(--crono-primary-dark), var(--crono-primary), transparent) 1;
    display: flex; align-items: center; gap: 0.5rem;
}
.crono-detail-section-title i { color: var(--crono-primary-dark); font-size: 1rem; }

.crono-detail-label {
    font-size: 0.65rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.07em;
    color: var(--crono-text-muted); margin-bottom: 0.3rem;
}
.crono-detail-value { font-size: 0.9rem; font-weight: 600; color: var(--crono-text); line-height: 1.3; }

.crono-detail-badge {
    display: inline-flex; align-items: center;
    padding: 0.3rem 0.75rem; border-radius: 20px;
    font-size: 0.78rem; font-weight: 600; gap: 0.35rem;
    box-shadow: var(--crono-shadow-sm);
}
.crono-badge-confirmado  { background: rgba(37,99,235,0.08);  color: #1e40af; border: 1px solid rgba(37,99,235,0.18); }
.crono-badge-desarrollado{ background: rgba(22,163,74,0.08);  color: #15803d; border: 1px solid rgba(22,163,74,0.18); }
.crono-badge-postergado  { background: rgba(217,119,6,0.08);  color: #92400e; border: 1px solid rgba(217,119,6,0.18); }
.crono-badge-cancelado   { background: rgba(220,38,38,0.08);  color: #991b1b; border: 1px solid rgba(220,38,38,0.18); }
.crono-color-bar { width: 7px; height: 42px; border-radius: 4px; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.12); }

/* ── Modal animation ── */
.modal-backdrop { background: rgba(0,0,0,0.35); }
@supports (backdrop-filter: blur(4px)) { .modal-backdrop { backdrop-filter: blur(4px); background: rgba(0,0,0,0.22); } }
.modal.fade .modal-dialog { transform: scale(0.93) translateY(-12px); transition: transform 0.32s cubic-bezier(0.16,1,0.3,1), opacity 0.22s; }
.modal.show .modal-dialog { transform: scale(1) translateY(0); }

/* ══════════════════════════════════════
   TOAST NOTIFICATIONS
══════════════════════════════════════ */
.toast-container { position: fixed; right: 20px; bottom: 24px; z-index: 1060; display: flex; flex-direction: column; gap: 0.5rem; pointer-events: none; }
.toast-notify {
    display: flex; align-items: center; gap: 0.65rem;
    padding: 0.8rem 1.1rem;
    background: rgba(255,255,255,0.96); backdrop-filter: blur(12px);
    border-radius: var(--radius-md);
    box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
    font-size: 0.83rem; font-weight: 500; color: var(--crono-text);
    border-left: 4px solid;
    animation: cronoToastIn 0.32s cubic-bezier(0.16,1,0.3,1);
    pointer-events: auto; min-width: 280px; max-width: 400px;
}
.toast-notify.hiding { animation: cronoToastOut 0.22s ease forwards; }
.toast-notify.success { border-left-color: var(--crono-success); }
.toast-notify.error   { border-left-color: var(--crono-danger); }
.toast-notify.warning { border-left-color: var(--crono-warning); }
.toast-notify.info    { border-left-color: var(--crono-info); }
.toast-icon { flex-shrink: 0; font-size: 1.1rem; }
.toast-notify.success .toast-icon i { color: var(--crono-success); }
.toast-notify.error   .toast-icon i { color: var(--crono-danger); }
.toast-notify.warning .toast-icon i { color: var(--crono-warning); }
.toast-notify.info    .toast-icon i { color: var(--crono-info); }
.toast-body-text { flex: 1; }
.toast-close { background: none; border: none; color: var(--crono-text-muted); cursor: pointer; padding: 0; font-size: 1rem; opacity: 0.45; transition: opacity 0.2s; flex-shrink: 0; }
.toast-close:hover { opacity: 1; }
@keyframes cronoToastIn  { 0% { opacity:0; transform: translateX(100%) scale(0.94); } 100% { opacity:1; transform: translateX(0) scale(1); } }
@keyframes cronoToastOut { 0% { opacity:1; transform: translateX(0) scale(1); } 100% { opacity:0; transform: translateX(100%) scale(0.94); } }

/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */
@media (max-width: 768px) {
    .crono-ofertas-grid { grid-template-columns: 1fr; }
    .crono-header { padding: 1.25rem 1.25rem; }
    .crono-filters { padding: 1rem 1.25rem; }
    .crono-calendar-body { padding: 0.75rem 0.75rem 1rem; }
    .crono-calendar-header { padding: 1rem 1.25rem; }
}
</style>
@endsection

@section('content')
    <div class="crono-page">
    <div class="crono-header-page">
        <div class="container-fluid">
            <div class="crono-header-inner">
                <div class="crono-header-left crono-animate crono-animate-1">
                    <div class="crono-header-icon"><i class="ri-calendar-check-line"></i></div>
                    <div class="crono-header-text">
                        <h1>Cronograma General</h1>
                        <p>Gestión de horarios y calendario académico</p>
                    </div>
                </div>
                <div class="crono-header-right crono-animate crono-animate-2">
                    <div class="crono-stat-card">
                        <div class="crono-stat-icon"><i class="ri-time-line"></i></div>
                        <div>
                            <div class="crono-stat-num" id="totalHorarios">0</div>
                            <div class="crono-stat-label">Horarios</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="crono-card">
                    <div class="crono-header">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.posgrads.index') }}">Posgrados</a>
                                    </li>
                                    <li class="breadcrumb-item active">Cronograma General</li>
                                </ol>
                                <h4><i class="ri-calendar-check-line me-2"></i>Cronograma General</h4>
                            </div>
                        </div>
                    </div>

                    <div class="crono-filters">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-3 col-md-6">
                                <div class="crono-filter-label">Sede</div>
                                <select class="form-select crono-filter-select" id="filtroSede">
                                    <option value="">Todas las sedes</option>
                                    @foreach ($sedes as $sede)
                                        <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="crono-filter-label">Sucursal</div>
                                <select class="form-select crono-filter-select" id="filtroSucursal" disabled>
                                    <option value="">Seleccionar sucursal...</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="crono-checkbox-wrapper">
                                    <input type="checkbox" id="mostrarTodosHorarios" checked>
                                    <label for="mostrarTodosHorarios">Mostrar todos los horarios</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="contenedorOfertas" class="crono-ofertas-section" style="display: none;">
                        <div class="crono-section-header">
                            <div class="crono-section-title">
                                <i class="ri-book-open-line"></i>
                                Ofertas Académicas
                            </div>
                            <span class="crono-count-badge" id="countOfertas">0 programas</span>
                        </div>
                        <div id="listaOfertas" class="crono-ofertas-grid"></div>
                    </div>

                    <div class="crono-calendar-card">
                        <div class="crono-calendar-header">
                            <div class="crono-calendar-title">
                                <i class="ri-calendar-line"></i>
                                <span id="tituloCalendario">Calendario de Horarios</span>
                            </div>
                        </div>
                        <div class="crono-calendar-body">
                            <div id="calendarCronograma"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade crono-modal" id="modalDetalleHorario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="crono-color-bar" id="detColorBar"></div>
                        <div>
                            <h5 class="modal-title mb-1" id="detTitulo"></h5>
                            <small class="text-muted" id="detSubtitulo"></small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="crono-detail-card mb-3">
                        <div class="crono-detail-section-title">
                            <i class="ri-time-line me-2"></i>Información del Horario
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="crono-detail-label">Fecha</div>
                                <div class="crono-detail-value" id="detFecha"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="crono-detail-label">Hora</div>
                                <div class="crono-detail-value" id="detHora"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="crono-detail-label">Estado</div>
                                <div id="detEstado"></div>
                            </div>
                        </div>
                    </div>

                    <div class="crono-detail-card mb-3" id="detalleOferta">
                        <div class="crono-detail-section-title">
                            <i class="ri-graduation-cap-line me-2"></i>Programa Académico
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="crono-detail-label">Código</div>
                                <div class="crono-detail-value" id="detOfertaCodigo"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="crono-detail-label">Gestión</div>
                                <div class="crono-detail-value" id="detOfertaGestion"></div>
                            </div>
                            <div class="col-12">
                                <div class="crono-detail-label">Programa</div>
                                <div class="crono-detail-value" id="detOfertaPrograma"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="crono-detail-label">Fase</div>
                                <div class="crono-detail-value" id="detOfertaFase"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="crono-detail-label">Modalidad</div>
                                <div class="crono-detail-value" id="detOfertaModalidad"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="crono-detail-label">Fecha Inicio</div>
                                <div class="crono-detail-value" id="detOfertaFechaInicio"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="crono-detail-label">Fecha Fin</div>
                                <div class="crono-detail-value" id="detOfertaFechaFin"></div>
                            </div>
                        </div>
                    </div>

                    <div class="crono-detail-card mb-3" id="detalleModulo">
                        <div class="crono-detail-section-title">
                            <i class="ri-file-list-3-line me-2"></i>Módulo
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="crono-detail-label">Módulo N°</div>
                                <div class="crono-detail-value" id="detModuloNumero"></div>
                            </div>
                            <div class="col-md-8">
                                <div class="crono-detail-label">Nombre</div>
                                <div class="crono-detail-value" id="detModuloNombre"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="crono-detail-label">Fecha Inicio</div>
                                <div class="crono-detail-value" id="detModuloFechaInicio"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="crono-detail-label">Fecha Fin</div>
                                <div class="crono-detail-value" id="detModuloFechaFin"></div>
                            </div>
                        </div>
                    </div>

                    <div class="crono-detail-card">
                        <div class="crono-detail-section-title">
                            <i class="ri-user-line me-2"></i>Docente y Asignación
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="crono-detail-label">Docente</div>
                                <div class="crono-detail-value" id="detDocente"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="crono-detail-label">Cargo Asignado</div>
                                <div id="detCargo"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('script')
    <script src="{{ URL::asset('build/libs/fullcalendar/index.global.min.js') }}"></script>
    <script>
        (function() {
            'use strict';

            let calendar = null;
            let currentSucursalId = null;
            let currentOfertaId = null;
            let allHorariosData = [];

            function showToast(tipo, mensaje) {
                const icons = {
                    success: 'ri-check-double-line',
                    error: 'ri-close-circle-line',
                    warning: 'ri-alert-line',
                    info: 'ri-information-line'
                };
                const container = document.getElementById('toastContainer') || createToastContainer();
                const toast = document.createElement('div');
                toast.className = `toast-notify ${tipo}`;
                toast.innerHTML = `
            <div class="toast-icon"><i class="${icons[tipo] || 'ri-information-line'}"></i></div>
            <div class="toast-body-text"><span>${mensaje}</span></div>
            <button class="toast-close"><i class="ri-close-line"></i></button>
        `;
                container.appendChild(toast);
                toast.querySelector('.toast-close').addEventListener('click', () => dismissToast(toast));
                setTimeout(() => dismissToast(toast), 4500);
            }

            function createToastContainer() {
                const c = document.createElement('div');
                c.id = 'toastContainer';
                c.className = 'toast-container';
                document.body.appendChild(c);
                return c;
            }

            function dismissToast(el) {
                el.classList.add('hiding');
                el.addEventListener('animationend', () => el.remove(), {
                    once: true
                });
            }

            function escHtml(str) {
                return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(
                    /"/g, '&quot;');
            }

            function formatDate(str) {
                if (!str) return '—';
                const p = str.split('-');
                return `${p[2]}/${p[1]}/${p[0]}`;
            }

            function formatTime(str) {
                return str ? str.substring(0, 5) : '—';
            }

            function updateTotalHorarios() {
                $('#totalHorarios').text(allHorariosData.length);
            }

            function initCascadingFilters() {
                const $filtroSede = $('#filtroSede');
                const $filtroSucursal = $('#filtroSucursal');
                const $mostrarTodos = $('#mostrarTodosHorarios');
                const $contenedorOfertas = $('#contenedorOfertas');

                $filtroSede.on('change', function() {
                    const sedeId = $(this).val();
                    $filtroSucursal.html('<option value="">Seleccionar sucursal...</option>').prop('disabled', !
                        sedeId);
                    $contenedorOfertas.hide();
                    currentSucursalId = null;
                    currentOfertaId = null;

                    if (calendar) {
                        calendar.removeAllEvents();
                        $('#tituloCalendario').text('Calendario de Horarios');
                        allHorariosData = [];
                        updateTotalHorarios();
                    }

                    if (!sedeId) return;

                    $.get(`/admin/posgrads/cronograma/sucursales/${sedeId}?_t=${Date.now()}`)
                        .done(function(r) {
                            let html = '<option value="">Seleccionar sucursal...</option>';
                            (r.data || []).forEach(function(s) {
                                html += `<option value="${s.id}">${escHtml(s.nombre)}</option>`;
                            });
                            $filtroSucursal.html(html);
                        })
                        .fail(() => showToast('error', 'Error al cargar sucursales.'));
                });

                $filtroSucursal.on('change', function() {
                    const sucursalId = $(this).val();
                    currentSucursalId = sucursalId;
                    currentOfertaId = null;

                    if (calendar) {
                        calendar.removeAllEvents();
                        $('#tituloCalendario').text('Calendario de Horarios');
                        allHorariosData = [];
                        updateTotalHorarios();
                    }

                    if (!sucursalId) {
                        $contenedorOfertas.hide();
                        return;
                    }

                    $mostrarTodos.prop('checked', false);
                    loadOfertas(sucursalId);
                });

                $mostrarTodos.on('change', function() {
                    if ($(this).is(':checked')) {
                        currentOfertaId = null;
                        $('#contenedorOfertas').hide();
                        $('#tituloCalendario').text('Calendario de Horarios');
                    } else if (currentSucursalId) {
                        $('#contenedorOfertas').show();
                    }
                    if (calendar) calendar.refetchEvents();
                });

                function loadOfertas(sucursalId) {
                    $.get(`/admin/posgrads/cronograma/sucursal/${sucursalId}/ofertas?_t=${Date.now()}`)
                        .done(function(r) {
                            const ofertas = r.data || [];
                            if (ofertas.length === 0) {
                                $('#listaOfertas').html(
                                    '<div class="text-center text-muted py-4">No hay ofertas académicas en esta sucursal</div>'
                                );
                                $contenedorOfertas.show();
                                $('#countOfertas').text('0 programas');
                                return;
                            }

                            $('#countOfertas').text(`${ofertas.length} programa${ofertas.length !== 1 ? 's' : ''}`);

                            let html = '';
                            ofertas.forEach(function(oferta) {
                                const color = oferta.color || '#9a4904';
                                const totalHorarios = oferta.horarios_count || 0;
                                const label =
                                    `${oferta.codigo || ''} — ${oferta.posgrado?.nombre || 'Sin nombre'}${oferta.fase ? ` (${oferta.fase.nombre})` : ''}`;

                                html += `
                            <div class="crono-oferta-card" data-oferta-id="${oferta.id}" data-color="${color}" data-label="${escHtml(label)}" style="--card-color: ${color}; --card-color-alpha: ${color}20;">
                                <div class="crono-oferta-header">
                                    <span class="crono-oferta-codigo">${escHtml(oferta.codigo || 'Sin código')}</span>
                                    <span class="crono-oferta-badge"><i class="ri-time-line"></i> ${totalHorarios}</span>
                                </div>
                                <div class="crono-oferta-nombre">${escHtml(oferta.posgrado?.nombre || 'Sin nombre')}</div>
                                <div class="crono-oferta-meta">
                                    <span><i class="ri-folder-line"></i> ${oferta.fase?.nombre || 'Sin fase'}</span>
                                    <span><i class="ri-calendar-line"></i> ${oferta.gestion || ''}</span>
                                </div>
                            </div>
                        `;
                            });

                            $('#listaOfertas').html(html);
                            $contenedorOfertas.show();

                            $('.crono-oferta-card').on('click', function() {
                                $('.crono-oferta-card').removeClass('active');
                                $(this).addClass('active');
                                currentOfertaId = $(this).data('oferta-id');
                                const label = $(this).data('label');
                                $('#tituloCalendario').text(label);
                                if (calendar) calendar.refetchEvents();
                            });
                        })
                        .fail(() => showToast('error', 'Error al cargar ofertas.'));
                }
            }

            function initCalendar() {
                const calendarEl = document.getElementById('calendarCronograma');
                if (!calendarEl) return;

                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'es',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día',
                        list: 'Lista'
                    },
                    editable: false,
                    selectable: true,
                    height: 'auto',
                    events: function(fetchInfo, successCallback, failureCallback) {
                        const params = {};

                        if (currentSucursalId) {
                            params.sucursal_id = currentSucursalId;
                        }
                        if (currentOfertaId) {
                            params.oferta_id = currentOfertaId;
                        }

                        $.get(`/admin/posgrads/cronograma/horarios?_t=${Date.now()}`, params)
                            .done(function(r) {
                                allHorariosData = r.data || [];
                                updateTotalHorarios();

                                const events = [];

                                allHorariosData.forEach(function(horario) {
                                    if (!horario.fecha) return;

                                    const color = horario.color || '#6366f1';
                                    const modulo = horario.modulo;
                                    const oferta = horario.oferta;
                                    const docente = horario.docente;
                                    const docenteNombre = docente ?
                                        `${docente.nombres || ''} ${docente.apellido_paterno || ''}`
                                        .trim() :
                                        'Sin asignar';

                                    // Si hay oferta seleccionada, mostrar nombre del módulo; si no, mostrar nombre de la oferta
                                    let tituloEvento;
                                    if (currentOfertaId) {
                                        tituloEvento =
                                            `${formatTime(horario.hora_inicio)} - ${formatTime(horario.hora_fin)}${modulo ? ' | ' + modulo.nombre : ''}`;
                                    } else {
                                        tituloEvento =
                                            `${formatTime(horario.hora_inicio)} - ${formatTime(horario.hora_fin)}${oferta?.posgrado ? ' | ' + oferta.posgrado.nombre : ''}`;
                                    }

                                    events.push({
                                        id: `h-${horario.id}`,
                                        title: tituloEvento,
                                        start: horario.fecha,
                                        allDay: false,
                                        backgroundColor: color,
                                        borderColor: color,
                                        textColor: '#ffffff',
                                        extendedProps: {
                                            horario_id: horario.id,
                                            fecha: horario.fecha,
                                            hora_inicio: horario.hora_inicio,
                                            hora_fin: horario.hora_fin,
                                            estado: horario.estado,
                                            color: color,
                                            modulo: modulo,
                                            oferta: oferta,
                                            docente: docente,
                                            docente_nombre: docenteNombre,
                                            trabajador_cargo: horario.trabajador_cargo,
                                            verOfertaCompleta: !currentOfertaId,
                                        }
                                    });
                                });

                                successCallback(events);
                            })
                            .fail(function() {
                                failureCallback();
                                showToast('error', 'Error al cargar los horarios.');
                            });
                    },
                    eventClick: function(info) {
                        openDetalleHorario(info.event);
                    },
                    eventDisplay: 'block',
                    slotMinTime: '06:00:00',
                    slotMaxTime: '23:00:00',
                });

                calendar.render();
            }

            function openDetalleHorario(event) {
                const props = event.extendedProps;
                const modal = new bootstrap.Modal(document.getElementById('modalDetalleHorario'));
                const oferta = props.oferta;
                const modulo = props.modulo;

                $('#detColorBar').css('background', props.color);

                if (currentOfertaId) {
                    $('#detTitulo').text(modulo?.nombre || 'Horario');
                    $('#detSubtitulo').text(oferta?.codigo || '');
                } else {
                    $('#detTitulo').text(oferta?.posgrado?.nombre || 'Programa');
                    $('#detSubtitulo').text(modulo?.nombre || '');
                }

                $('#detFecha').text(formatDate(props.fecha));
                $('#detHora').text(`${formatTime(props.hora_inicio)} — ${formatTime(props.hora_fin)}`);

                const estadoConfig = {
                    'Confirmado': {
                        class: 'crono-badge-confirmado',
                        icon: 'ri-check-line'
                    },
                    'Desarrollado': {
                        class: 'crono-badge-desarrollado',
                        icon: 'ri-checkbox-circle-line'
                    },
                    'Postergado': {
                        class: 'crono-badge-postergado',
                        icon: 'ri-time-line'
                    },
                    'Cancelado': {
                        class: 'crono-badge-cancelado',
                        icon: 'ri-close-circle-line'
                    }
                };
                const estado = estadoConfig[props.estado] || {
                    class: 'crono-badge-confirmado',
                    icon: 'ri-check-line'
                };
                $('#detEstado').html(
                    `<span class="crono-detail-badge ${estado.class}"><i class="${estado.icon} me-1"></i>${escHtml(props.estado || 'Confirmado')}</span>`
                );

                $('#detOfertaCodigo').text(oferta?.codigo || '—');
                $('#detOfertaGestion').text(oferta?.gestion || '—');
                $('#detOfertaPrograma').text(oferta?.posgrado?.nombre || '—');
                $('#detOfertaFase').text(oferta?.fase?.nombre || '—');
                $('#detOfertaModalidad').text(oferta?.modalidad?.nombre || '—');
                $('#detOfertaFechaInicio').text(oferta?.fecha_inicio_programa ? formatDate(oferta
                    .fecha_inicio_programa) : '—');
                $('#detOfertaFechaFin').text(oferta?.fecha_fin_programa ? formatDate(oferta.fecha_fin_programa) : '—');

                $('#detModuloNumero').text(modulo ? `Módulo ${modulo.n_modulo}` : '—');
                $('#detModuloNombre').text(modulo?.nombre || '—');
                $('#detModuloFechaInicio').text(modulo?.fecha_inicio ? formatDate(modulo.fecha_inicio) : '—');
                $('#detModuloFechaFin').text(modulo?.fecha_fin ? formatDate(modulo.fecha_fin) : '—');

                $('#detDocente').text(props.docente_nombre);
                $('#detCargo').html(props.trabajador_cargo ?
                    `<span class="crono-detail-badge" style="background: rgba(99, 102, 241, 0.15); color: #4338ca;"><i class="ri-user-line me-1"></i>${escHtml(props.trabajador_cargo)}</span>` :
                    '<span class="text-muted">Sin asignar</span>');

                modal.show();
            }

            document.addEventListener('DOMContentLoaded', function() {
                initCascadingFilters();
                initCalendar();
                if (calendar) {
                    setTimeout(() => calendar.refetchEvents(), 100);
                }
            });
        })();
    </script>
@endsection
