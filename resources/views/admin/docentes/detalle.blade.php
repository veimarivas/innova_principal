@extends('layouts.master')
@section('title')
    Detalle Docente
@endsection
@section('css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

    :root {
        --doc-primary: #fc7b04;
        --doc-primary-light: rgba(252, 123, 4, 0.1);
        --doc-primary-dark: #d46604;
        --doc-accent: #e8860a;
        --doc-surface: #faf8f5;
        --doc-border: #ede8e2;
        --doc-border-light: #f5f0eb;
        --doc-text: #2a2520;
        --doc-text-muted: #7a746c;
        --doc-success: #2e9a6e;
        --doc-danger: #d94f4f;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.05), 0 2px 8px rgba(0,0,0,0.03);
    }

    .doc-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--doc-text);
    }

    .doc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding: 20px 28px;
        background: linear-gradient(135deg, #3b1900 0%, #6b3300 45%, #c96004 100%);
        border-radius: var(--radius-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .doc-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -5%;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(252, 123, 4, 0.3) 0%, transparent 70%);
        border-radius: 50%;
    }

    .doc-header h1 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
        position: relative;
        z-index: 1;
        color: white;
    }

    .doc-header p {
        margin: 4px 0 0;
        opacity: 0.8;
        font-size: 0.9rem;
    }

    .doc-header-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.5rem 1rem;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 8px;
        color: white;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.82rem;
        transition: all 0.25s ease;
        backdrop-filter: blur(4px);
        cursor: pointer;
        position: relative;
        z-index: 1;
    }

    .doc-header-btn:hover {
        background: white;
        color: var(--doc-primary);
        border-color: white;
        transform: translateY(-1px);
    }

    .doc-tabs-card {
        background: var(--d-card, white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--d-card-border, #e2e8f0);
        box-shadow: var(--d-card-shadow, var(--shadow-sm));
        overflow: hidden;
    }

    .doc-tabs-nav {
        display: flex;
        overflow-x: auto;
        scrollbar-width: none;
        padding: 0;
        background: var(--d-header-bg, var(--doc-surface));
        border-bottom: 1px solid var(--d-header-border, var(--doc-border));
    }

    .doc-tabs-nav::-webkit-scrollbar { display: none; }

    .doc-tab-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 16px 22px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--doc-text-muted);
        border: none;
        background: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .doc-tab-btn:hover:not(.active) {
        color: var(--doc-primary);
        background: var(--doc-primary-light);
    }

    .doc-tab-btn.active {
        color: var(--doc-primary);
        border-bottom-color: var(--doc-primary);
        background: var(--d-card, white);
    }

    .doc-tab-btn i { font-size: 1.05rem; }

    .doc-tabs-body {
        padding: 24px;
        display: none;
    }

    .doc-tabs-body.active { display: block; }

    .doc-data-card {
        background: var(--d-card, white);
        border-radius: var(--radius-md);
        border: 1px solid var(--d-card-border, #e2e8f0);
        box-shadow: var(--d-card-shadow, var(--shadow-sm));
        overflow: visible;
    }

    .doc-data-card-header {
        padding: 14px 18px;
        border-bottom: 1px solid var(--d-card-border, #e2e8f0);
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--d-header-bg, var(--doc-surface));
    }

    .doc-data-card-icon {
        width: 34px;
        height: 34px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }

    .doc-data-card-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
        color: var(--doc-text);
    }

    .doc-data-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
    }

    .doc-data-row + .doc-data-row {
        border-top: 1px solid var(--d-row-border, #e2e8f0);
    }

    .doc-data-row-icon {
        width: 30px;
        height: 30px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        flex-shrink: 0;
    }

    .doc-data-row-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--doc-text-muted);
        font-weight: 700;
    }

    .doc-data-row-value {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--doc-text);
    }

    /* ─── PERFIL PERSONAL (nuevo diseño) ─── */
    .dci-hero {
        background: linear-gradient(135deg, #3b1900 0%, #6b3300 40%, #c96004 100%);
        border-radius: var(--radius-lg);
        padding: 1.5rem 1.75rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1.25rem;
        position: relative;
        overflow: hidden;
        flex-wrap: wrap;
    }

    .dci-hero::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -5%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(252,123,4,0.22) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .dci-photo-wrap { position: relative; flex-shrink: 0; margin-bottom: 0.5rem; }

    .dci-photo {
        width: 88px;
        height: 104px;
        border-radius: 10px;
        overflow: hidden;
        background: rgba(255,255,255,0.12);
        border: 2px solid rgba(255,255,255,0.22);
    }

    .dci-photo img { width: 100%; height: 100%; object-fit: cover; }

    .dci-photo-initials {
        font-size: 2.2rem;
        font-weight: 800;
        color: rgba(255,255,255,0.72);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .dci-badge-role {
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--doc-primary);
        color: #fff;
        font-size: 0.58rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        padding: 0.18rem 0.6rem;
        border-radius: 20px;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.25);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .dci-hero-info {
        flex: 1;
        min-width: 0;
        position: relative;
        z-index: 1;
    }

    .dci-hero-nombre {
        font-size: 1.45rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.2;
        margin-bottom: 0.3rem;
        letter-spacing: -0.02em;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .dci-hero-sub {
        color: rgba(255,255,255,0.65);
        font-size: 0.8rem;
        margin-bottom: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .dci-hero-sub span { display: flex; align-items: center; gap: 0.3rem; }
    .dci-hero-sub i { font-size: 0.85rem; }

    .dci-hero-chips { display: flex; flex-wrap: wrap; gap: 0.4rem; }

    .dci-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.28rem;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 20px;
        padding: 0.22rem 0.6rem;
        font-size: 0.7rem;
        color: rgba(255,255,255,0.88);
        font-weight: 500;
        backdrop-filter: blur(4px);
    }

    .dci-chip i { font-size: 0.78rem; color: rgba(255,255,255,0.55); }

    .dci-hero-estado {
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .dci-estado-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.32rem 0.85rem;
        border-radius: 20px;
        font-size: 0.73rem;
        font-weight: 700;
    }

    .dci-badge-activo   { background: rgba(46,154,110,0.22); color: #7eefc4; border: 1px solid rgba(46,154,110,0.38); }
    .dci-badge-inactivo { background: rgba(220,80,80,0.22);  color: #fca5a5; border: 1px solid rgba(220,80,80,0.35); }

    .dci-fecha-reg {
        font-size: 0.67rem;
        color: rgba(255,255,255,0.45);
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* ─── INFO GRID ─── */
    .dci-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .dci-card {
        background: #fff;
        border: 1px solid var(--doc-border);
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .dci-card-head {
        padding: 0.8rem 1rem;
        border-bottom: 1px solid var(--doc-border);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        background: linear-gradient(135deg, #fff 0%, #fdf9f4 100%);
    }

    .dci-card-head-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        background: rgba(252,123,4,0.1);
        color: var(--doc-primary);
        flex-shrink: 0;
    }

    .dci-card-head-title {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--doc-text);
        margin: 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .dci-field {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.68rem 1rem;
        border-bottom: 1px solid var(--doc-border-light);
    }

    .dci-field:last-child { border-bottom: none; }

    .dci-field-icon {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        flex-shrink: 0;
        background: rgba(252,123,4,0.07);
        color: var(--doc-primary);
    }

    .dci-field-body { flex: 1; min-width: 0; }

    .dci-field-label {
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--doc-text-muted);
        font-weight: 700;
        line-height: 1;
        margin-bottom: 2px;
    }

    .dci-field-value {
        font-size: 0.84rem;
        font-weight: 500;
        color: var(--doc-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dci-field-value.muted { color: var(--doc-text-muted); font-style: italic; font-weight: 400; }

    .dci-account-ok {
        background: rgba(46,154,110,0.07);
        border: 1px solid rgba(46,154,110,0.2);
        border-radius: 8px;
        padding: 0.55rem 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
        margin: 0.75rem 1rem 0.25rem;
    }

    .dci-account-ok i { color: var(--doc-success); font-size: 0.95rem; flex-shrink: 0; }
    .dci-account-ok span { font-size: 0.76rem; font-weight: 600; color: var(--doc-success); }

    .dci-no-account {
        background: rgba(148,163,184,0.06);
        border: 1px solid rgba(148,163,184,0.2);
        border-radius: 8px;
        padding: 0.6rem 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
        margin: 0.75rem 1rem;
        color: var(--doc-text-muted);
        font-size: 0.76rem;
    }

    .dci-wa-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        margin: 0.6rem 1rem 1rem;
        padding: 0.6rem 1rem;
        background: rgba(37,211,102,0.08);
        border: 1px solid rgba(37,211,102,0.25);
        border-radius: 9px;
        color: #1a9e49;
        font-weight: 600;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
        width: calc(100% - 2rem);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .dci-wa-btn:hover { background: rgba(37,211,102,0.15); border-color: rgba(37,211,102,0.4); color: #148a3c; }
    .dci-wa-btn i { font-size: 1rem; }

    /* ─── PLACEHOLDER (old .doc-ci-wrap) ─── */
    .doc-ci-wrap { display: none; }

    /* ─── RESPONSIVE grid ─── */
    @media (max-width: 1024px) { .dci-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px)  { .dci-grid { grid-template-columns: 1fr; } .dci-hero { gap: 1rem; } .dci-hero-estado { align-items: flex-start; flex-direction: row; flex-wrap: wrap; } }
    @media (max-width: 576px)  { .dci-hero-nombre { font-size: 1.15rem; } .dci-photo { width: 70px; height: 85px; } }

    /* Tabla de módulos */
    .doc-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .doc-table thead th {
        background: var(--d-thead-bg, var(--doc-surface));
        padding: 12px 16px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--d-thead-color, var(--doc-text-muted));
        border-bottom: 1px solid var(--d-header-border, var(--doc-border));
    }

    .doc-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--d-row-border, #e2e8f0);
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .doc-table tbody tr:last-child td { border-bottom: none; }
    .doc-table tbody tr:hover td { background: var(--d-row-hover, var(--doc-primary-light)); }

    .modulo-estado-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: .25rem .65rem;
        border-radius: 20px;
        font-size: .68rem;
        font-weight: 600;
    }

    .modulo-estado-badge.activo { background: #dcfce7; color: #15803d; }
    .modulo-estado-badge.inactivo { background: #f1f5f9; color: #64748b; }

    /* Documentos */
    .doc-doc-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }

    .doc-doc-card {
        background: var(--d-card, white);
        border: 1px solid var(--d-card-border, #e2e8f0);
        border-radius: var(--radius-md);
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s;
    }

    .doc-doc-card:hover {
        border-color: var(--doc-primary);
        box-shadow: var(--shadow-md);
    }

    .doc-doc-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .doc-doc-info { flex: 1; }

    .doc-doc-name {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 2px;
    }

    .doc-doc-status {
        font-size: 0.72rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .doc-doc-status.verificado { color: #15803d; }
    .doc-doc-status.pendiente { color: #d97706; }

    .doc-doc-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-doc-action {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .btn-doc-view { background: var(--doc-primary-light); color: var(--doc-primary); }
    .btn-doc-view:hover { background: var(--doc-primary); color: white; }
    .btn-doc-check { background: rgba(34, 197, 94, 0.1); color: #15803d; }
    .btn-doc-check:hover { background: #15803d; color: white; }
    .btn-doc-uncheck { background: rgba(148, 163, 184, 0.1); color: #64748b; }
    .btn-doc-uncheck:hover { background: #64748b; color: white; }

    /* Contable */
    .doc-stat-card {
        background: var(--d-card, white);
        border-radius: var(--radius-md);
        border: 1px solid var(--d-card-border, #e2e8f0);
        box-shadow: var(--d-card-shadow, var(--shadow-sm));
        overflow: hidden;
        transition: all 0.25s ease;
    }

    .doc-stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }

    .doc-stat-body {
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .doc-stat-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.2rem;
        line-height: 1.1;
    }

    .doc-stat-label {
        font-size: 0.72rem;
        color: var(--doc-text-muted);
        margin: 0;
    }

    .doc-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: var(--doc-text-muted);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p { margin: 0; font-size: 0.9rem; }

    @media (max-width: 768px) {
        .doc-header { padding: 16px 18px; }
        .doc-tabs-body { padding: 16px; }
    }

    /* ─── FORMACIÓN ACADÉMICA ─── */
    .est-principal-banner {
        background: linear-gradient(135deg, #1a3a5c 0%, #1e5799 55%, #2e86de 100%);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
        position: relative;
        overflow: hidden;
        flex-wrap: wrap;
    }

    .est-principal-banner::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -4%;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(46,134,222,0.3) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .est-principal-icon {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-md);
        background: rgba(255,255,255,0.15);
        border: 1.5px solid rgba(255,255,255,0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: #fff;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .est-principal-info {
        flex: 1;
        min-width: 0;
        position: relative;
        z-index: 1;
    }

    .est-principal-label {
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.6);
        margin-bottom: 0.2rem;
    }

    .est-principal-grado {
        font-size: 1.15rem;
        font-weight: 700;
        color: #fff;
        font-family: 'Outfit', sans-serif;
        line-height: 1.2;
        margin-bottom: 0.3rem;
    }

    .est-principal-sub {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem 1rem;
        font-size: 0.78rem;
        color: rgba(255,255,255,0.75);
    }

    .est-principal-sub span { display: flex; align-items: center; gap: 0.3rem; }

    .est-principal-badges {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.4rem;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .est-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.28rem;
        padding: 0.22rem 0.7rem;
        border-radius: 20px;
        font-size: 0.68rem;
        font-weight: 700;
    }

    .est-badge-principal { background: rgba(255,215,0,0.2); color: #ffd700; border: 1px solid rgba(255,215,0,0.35); }
    .est-badge-concluido { background: rgba(46,154,110,0.22); color: #7eefc4; border: 1px solid rgba(46,154,110,0.38); }
    .est-badge-desarrollo { background: rgba(251,191,36,0.2); color: #fbbf24; border: 1px solid rgba(251,191,36,0.35); }

    .est-empty-principal {
        background: rgba(148,163,184,0.06);
        border: 1.5px dashed rgba(148,163,184,0.3);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        text-align: center;
        color: var(--doc-text-muted);
        margin-bottom: 1.25rem;
    }

    .est-empty-principal i { font-size: 2rem; opacity: 0.4; display: block; margin-bottom: 0.5rem; }
    .est-empty-principal p { margin: 0; font-size: 0.82rem; }

    .est-section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--doc-text-muted);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .est-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--doc-border);
    }

    .est-form-card {
        background: var(--d-card, white);
        border: 1px solid var(--d-card-border, #e2e8f0);
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        margin-top: 1.25rem;
    }

    .est-form-header {
        padding: 0.9rem 1.1rem;
        border-bottom: 1px solid var(--d-card-border, #e2e8f0);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        background: var(--d-header-bg, var(--doc-surface));
        cursor: pointer;
        user-select: none;
    }

    .est-form-header-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        background: rgba(252,123,4,0.1);
        color: var(--doc-primary);
        flex-shrink: 0;
    }

    .est-form-header-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--doc-text);
        flex: 1;
    }

    .est-form-body {
        padding: 1.25rem 1.25rem 0.5rem;
    }

    .est-form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.85rem;
    }

    @media (max-width: 768px) { .est-form-grid { grid-template-columns: 1fr; } }

    .est-form-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--doc-text-muted);
        margin-bottom: 0.3rem;
    }

    .est-form-select {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid var(--doc-border);
        border-radius: var(--radius-sm);
        font-size: 0.85rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--doc-text);
        background: #fff;
        transition: border-color 0.2s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%237a746c' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.65rem center;
        padding-right: 2rem;
    }

    .est-form-select:focus { outline: none; border-color: var(--doc-primary); box-shadow: 0 0 0 3px rgba(252,123,4,0.1); }

    .est-principal-toggle {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.6rem 0.85rem;
        background: rgba(252,123,4,0.04);
        border: 1px solid rgba(252,123,4,0.15);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 0.85rem;
    }

    .est-principal-toggle:has(input:checked) {
        background: rgba(252,123,4,0.1);
        border-color: rgba(252,123,4,0.35);
    }

    .est-principal-toggle input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--doc-primary); cursor: pointer; flex-shrink: 0; }
    .est-principal-toggle-label { font-size: 0.82rem; font-weight: 600; color: var(--doc-text); line-height: 1.2; }
    .est-principal-toggle-sub { font-size: 0.7rem; color: var(--doc-text-muted); font-weight: 400; }

    .est-btn-guardar {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.25rem;
        background: var(--doc-primary);
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        font-size: 0.84rem;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
    }

    .est-btn-guardar:hover { background: var(--doc-primary-dark); transform: translateY(-1px); }
    .est-btn-guardar:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    /* Tabla de estudios */
    .est-table-wrap {
        border: 1px solid var(--d-card-border, #e2e8f0);
        border-radius: var(--radius-md);
        overflow: hidden;
    }

    .est-table-action {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .est-action-docs { background: rgba(14,165,233,0.08); color: #0284c7; }
    .est-action-docs:hover, .est-action-docs-active { background: #0284c7; color: white; }
    .est-action-principal { background: rgba(252,123,4,0.1); color: var(--doc-primary); }
    .est-action-principal:hover { background: var(--doc-primary); color: white; }
    .est-action-del { background: rgba(217,79,79,0.08); color: var(--doc-danger); }
    .est-action-del:hover { background: var(--doc-danger); color: white; }

    /* ─── TOAST ─── */
    .est-toast {
        position: fixed;
        bottom: 1.75rem;
        right: 1.75rem;
        padding: 0.65rem 1rem;
        border-radius: 10px;
        font-size: 0.84rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        z-index: 19999;
        box-shadow: 0 4px 24px rgba(0,0,0,0.14);
        transform: translateY(16px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
        pointer-events: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .est-toast.show { transform: translateY(0); opacity: 1; }
    .est-toast-success { background: #15803d; color: #fff; }
    .est-toast-error   { background: var(--doc-danger); color: #fff; }
    .est-toast-info    { background: #0284c7; color: #fff; }

    /* ─── MODAL DOCUMENTO ─── */
    .est-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 18000;
        background: rgba(0,0,0,0.55);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        backdrop-filter: blur(3px);
    }
    .est-modal-overlay.open { display: flex; }

    .est-modal-box {
        background: #fff;
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 820px;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 24px 64px rgba(0,0,0,0.22);
        animation: estModalIn 0.25s ease;
    }

    @keyframes estModalIn {
        from { opacity: 0; transform: scale(0.96) translateY(8px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .est-modal-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--doc-border);
        display: flex;
        align-items: center;
        gap: 0.85rem;
        background: var(--doc-surface);
        flex-shrink: 0;
    }

    .est-modal-header-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .est-modal-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--doc-text);
        margin: 0;
    }

    .est-modal-sub {
        font-size: 0.72rem;
        color: var(--doc-text-muted);
        margin-top: 1px;
    }

    .est-modal-close {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: rgba(0,0,0,0.05);
        color: var(--doc-text-muted);
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.15s;
        flex-shrink: 0;
    }
    .est-modal-close:hover { background: rgba(217,79,79,0.1); color: var(--doc-danger); }

    .est-modal-body {
        flex: 1;
        overflow: auto;
        min-height: 300px;
        background: #f1f5f9;
    }

    /* Panel de documentos por estudio */
    .est-docs-panel {
        background: var(--doc-surface);
        border-top: 1px solid var(--doc-border);
        border-bottom: 1px solid var(--doc-border);
    }

    .est-docs-inner {
        padding: 1rem 1.25rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.85rem;
    }

    @media (max-width: 640px) { .est-docs-inner { grid-template-columns: 1fr; } }

    .est-doc-card {
        background: #fff;
        border: 1px solid var(--doc-border);
        border-radius: var(--radius-md);
        padding: 0.9rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        transition: border-color 0.2s;
    }

    .est-doc-card:hover { border-color: rgba(252,123,4,0.35); }

    .est-doc-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .est-doc-card-body { flex: 1; min-width: 0; }

    .est-doc-card-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--doc-text);
        margin-bottom: 3px;
    }

    .est-doc-card-status {
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 500;
    }

    .est-doc-card-status.verificado { color: var(--doc-success); }
    .est-doc-card-status.pendiente  { color: #d97706; }
    .est-doc-card-status.sin-doc    { color: var(--doc-text-muted); }

    .est-doc-actions {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        flex-shrink: 0;
    }

    .est-doc-btn {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        font-size: 0.82rem;
        transition: all 0.18s;
    }

    .est-doc-btn-view   { background: rgba(252,123,4,0.1); color: var(--doc-primary); }
    .est-doc-btn-view:hover   { background: var(--doc-primary); color: #fff; }
    .est-doc-btn-upload { background: rgba(14,165,233,0.1); color: #0284c7; }
    .est-doc-btn-upload:hover { background: #0284c7; color: #fff; }
    .est-doc-btn-check  { background: rgba(34,197,94,0.1); color: #15803d; }
    .est-doc-btn-check:hover  { background: #15803d; color: #fff; }
    .est-doc-btn-uncheck { background: rgba(148,163,184,0.1); color: #64748b; }
    .est-doc-btn-uncheck:hover { background: #64748b; color: #fff; }

    .est-form-file-wrap {
        border: 1.5px dashed var(--doc-border);
        border-radius: var(--radius-sm);
        padding: 0.6rem 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        background: #fff;
    }

    .est-form-file-wrap:hover { border-color: var(--doc-primary); background: var(--doc-primary-light); }

    .est-form-file-wrap input[type="file"] { display: none; }

    .est-form-file-label {
        font-size: 0.75rem;
        color: var(--doc-text-muted);
        font-weight: 500;
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .est-form-file-icon {
        font-size: 1rem;
        color: var(--doc-primary);
        flex-shrink: 0;
    }

    /* Chip de estudio en tab personal */
    .dci-estudio-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: rgba(30,87,153,0.08);
        border: 1px solid rgba(30,87,153,0.18);
        border-radius: 20px;
        padding: 0.18rem 0.55rem;
        font-size: 0.68rem;
        color: #1e5799;
        font-weight: 600;
    }

    .doc-card-compact {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        transition: box-shadow 0.2s;
    }
    .doc-card-compact:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .doc-card-compact .doc-header {
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .doc-card-compact .doc-body {
        padding: 10px 14px;
    }

    .estudio-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: box-shadow 0.2s;
    }
    .estudio-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .estudio-card .estudio-header {
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        flex-wrap: wrap;
    }
    .estudio-card .estudio-body {
        padding: 16px;
    }
    .estudio-card .estudio-footer {
        padding: 10px 16px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }
</style>
@endsection

@section('content')
<div class="doc-page">
    <div class="doc-header">
        <div>
            <h1>Detalle del Docente</h1>
            <p>Información completa del registro</p>
        </div>
        <a href="{{ route('admin.docentes.index') }}" class="doc-header-btn">
            <i class="ri-arrow-left-line"></i> Volver
        </a>
    </div>

    <div class="container-fluid">
        {{-- Tabs Navigation --}}
        <div class="doc-tabs-card">
            <div class="doc-tabs-nav">
                <button class="doc-tab-btn active" onclick="switchDocTab(this, 'tab-personal')">
                    <i class="ri-user-3-line"></i> Personal
                </button>
                <button class="doc-tab-btn" onclick="switchDocTab(this, 'tab-documentos')">
                    <i class="ri-file-paper-line"></i> Documentos
                </button>
                <button class="doc-tab-btn" onclick="switchDocTab(this, 'tab-modulos')">
                    <i class="ri-book-3-line"></i> Módulos
                </button>
                <button class="doc-tab-btn" onclick="switchDocTab(this, 'tab-contable')">
                    <i class="ri-money-dollar-circle-line"></i> Contable
                </button>
            </div>

            {{-- TAB: PERSONAL --}}
            <div class="doc-tabs-body active" id="tab-personal">
                @php
                    $persona = $docente->persona;
                    $tieneFoto = $persona && $persona->fotografia && file_exists(public_path('images/personas/' . $persona->fotografia));
                    $avatarUrl = $tieneFoto ? asset('images/personas/' . $persona->fotografia) : null;
                    $nombreCompleto = $persona ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) : 'Docente';
                    $iniciales = collect(explode(' ', $nombreCompleto))->filter()->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');
                    $edad = ($persona && $persona->fecha_nacimiento) ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->age : null;
                    $tieneCuenta = $persona && $persona->usuario;
                    $ubicacion = $persona?->ciudad?->nombre
                        ? ($persona->ciudad->nombre . ($persona->ciudad?->departamento?->nombre ? ', ' . $persona->ciudad->departamento->nombre : ''))
                        : null;
                @endphp

                {{-- Hero de perfil --}}
                <div class="dci-hero">
                    <div class="dci-photo-wrap">
                        <div class="dci-photo">
                            <img src="{{ $avatarUrl ?? '' }}" alt="Foto" id="dci-foto-img"
                                 style="{{ $tieneFoto ? '' : 'display:none;' }}"
                                 onerror="this.style.display='none';document.getElementById('dci-initials').style.display='flex';">
                            <div id="dci-initials" class="dci-photo-initials" style="{{ $tieneFoto ? 'display:none;' : '' }}">{{ $iniciales ?: '?' }}</div>
                        </div>
                        <span class="dci-badge-role"><i class="ri-graduation-cap-fill"></i> Docente</span>
                    </div>

                    <div class="dci-hero-info">
                        <div class="dci-hero-nombre">{{ $nombreCompleto }}</div>
                        <div class="dci-hero-sub">
                            @if($persona?->carnet)
                                <span><i class="ri-shield-check-line"></i> CI: {{ $persona->carnet }}{{ $persona->expedido ? ' ' . $persona->expedido : '' }}</span>
                            @endif
                            @if($ubicacion)
                                <span><i class="ri-map-pin-2-line"></i> {{ $ubicacion }}</span>
                            @endif
                        </div>
                        <div class="dci-hero-chips">
                            @if($edad)
                                <span class="dci-chip"><i class="ri-calendar-line"></i> {{ $edad }} años</span>
                            @endif
                            @if($persona?->sexo)
                                <span class="dci-chip"><i class="ri-user-line"></i> {{ $persona->sexo == 'M' ? 'Masculino' : ($persona->sexo == 'F' ? 'Femenino' : $persona->sexo) }}</span>
                            @endif
                            @if($persona?->estado_civil)
                                <span class="dci-chip"><i class="ri-heart-line"></i> {{ $persona->estado_civil }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="dci-hero-estado">
                        <span class="dci-estado-badge dci-badge-activo">
                            <i class="ri-checkbox-circle-fill"></i> Activo
                        </span>
                        <span class="dci-fecha-reg">
                            <i class="ri-calendar-check-line"></i>
                            Registrado {{ $docente->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>

                {{-- Grid de tarjetas de información --}}
                <div class="dci-grid">

                    {{-- Tarjeta: Datos de Identidad --}}
                    <div class="dci-card">
                        <div class="dci-card-head">
                            <div class="dci-card-head-icon"><i class="ri-id-card-line"></i></div>
                            <span class="dci-card-head-title">Datos de Identidad</span>
                        </div>
                        @if($persona?->carnet)
                        <div class="dci-field">
                            <div class="dci-field-icon"><i class="ri-shield-check-line"></i></div>
                            <div class="dci-field-body">
                                <div class="dci-field-label">Carnet de Identidad</div>
                                <div class="dci-field-value">{{ $persona->carnet }}{{ $persona->expedido ? ' — exp. ' . $persona->expedido : '' }}</div>
                            </div>
                        </div>
                        @endif
                        @if($persona?->fecha_nacimiento)
                        <div class="dci-field">
                            <div class="dci-field-icon"><i class="ri-cake-2-line"></i></div>
                            <div class="dci-field-body">
                                <div class="dci-field-label">Fecha de Nacimiento</div>
                                <div class="dci-field-value">{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') . ($edad ? ' · ' . $edad . ' años' : '') }}</div>
                            </div>
                        </div>
                        @endif
                        @if($persona?->sexo)
                        <div class="dci-field">
                            <div class="dci-field-icon"><i class="ri-user-3-line"></i></div>
                            <div class="dci-field-body">
                                <div class="dci-field-label">Sexo</div>
                                <div class="dci-field-value">{{ $persona->sexo == 'M' ? 'Masculino' : ($persona->sexo == 'F' ? 'Femenino' : $persona->sexo) }}</div>
                            </div>
                        </div>
                        @endif
                        @if($persona?->estado_civil)
                        <div class="dci-field">
                            <div class="dci-field-icon"><i class="ri-heart-line"></i></div>
                            <div class="dci-field-body">
                                <div class="dci-field-label">Estado Civil</div>
                                <div class="dci-field-value">{{ $persona->estado_civil }}</div>
                            </div>
                        </div>
                        @endif
                        @if(!$persona?->carnet && !$persona?->fecha_nacimiento && !$persona?->sexo && !$persona?->estado_civil)
                        <div class="dci-field">
                            <div class="dci-field-body">
                                <div class="dci-field-value muted">Sin datos de identidad registrados</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Tarjeta: Contacto y Ubicación --}}
                    <div class="dci-card">
                        <div class="dci-card-head">
                            <div class="dci-card-head-icon"><i class="ri-contacts-line"></i></div>
                            <span class="dci-card-head-title">Contacto y Ubicación</span>
                        </div>
                        <div class="dci-field">
                            <div class="dci-field-icon"><i class="ri-mail-line"></i></div>
                            <div class="dci-field-body">
                                <div class="dci-field-label">Correo Electrónico</div>
                                <div class="dci-field-value {{ !$persona?->correo ? 'muted' : '' }}">{{ $persona?->correo ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="dci-field">
                            <div class="dci-field-icon"><i class="ri-smartphone-line"></i></div>
                            <div class="dci-field-body">
                                <div class="dci-field-label">Celular</div>
                                <div class="dci-field-value {{ !$persona?->celular ? 'muted' : '' }}">{{ $persona?->celular ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="dci-field">
                            <div class="dci-field-icon"><i class="ri-phone-line"></i></div>
                            <div class="dci-field-body">
                                <div class="dci-field-label">Teléfono</div>
                                <div class="dci-field-value {{ !$persona?->telefono ? 'muted' : '' }}">{{ $persona?->telefono ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="dci-field">
                            <div class="dci-field-icon"><i class="ri-map-pin-line"></i></div>
                            <div class="dci-field-body">
                                <div class="dci-field-label">Ciudad / Departamento</div>
                                <div class="dci-field-value {{ !$ubicacion ? 'muted' : '' }}">{{ $ubicacion ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="dci-field">
                            <div class="dci-field-icon"><i class="ri-home-4-line"></i></div>
                            <div class="dci-field-body">
                                <div class="dci-field-label">Dirección</div>
                                <div class="dci-field-value {{ !$persona?->direccion ? 'muted' : '' }}">{{ $persona?->direccion ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tarjeta: Cuenta del Sistema --}}
                    <div class="dci-card">
                        <div class="dci-card-head">
                            <div class="dci-card-head-icon"><i class="ri-user-settings-line"></i></div>
                            <span class="dci-card-head-title">Cuenta del Sistema</span>
                        </div>

                        @if($tieneCuenta)
                        <div class="dci-account-ok">
                            <i class="ri-checkbox-circle-fill"></i>
                            <span>Cuenta activa y verificada</span>
                        </div>
                        <div class="dci-field">
                            <div class="dci-field-icon"><i class="ri-user-line"></i></div>
                            <div class="dci-field-body">
                                <div class="dci-field-label">Usuario</div>
                                <div class="dci-field-value">{{ $persona->usuario->username ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="dci-field">
                            <div class="dci-field-icon"><i class="ri-mail-lock-line"></i></div>
                            <div class="dci-field-body">
                                <div class="dci-field-label">Correo de acceso</div>
                                <div class="dci-field-value">{{ $persona->usuario->email ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="dci-field">
                            <div class="dci-field-icon"><i class="ri-shield-star-line"></i></div>
                            <div class="dci-field-body">
                                <div class="dci-field-label">Rol asignado</div>
                                <div class="dci-field-value">{{ ucfirst($persona->usuario->role ?? 'docente') }}</div>
                            </div>
                        </div>
                        @else
                        <div class="dci-no-account">
                            <i class="ri-close-circle-line"></i>
                            <span>Sin cuenta registrada en el sistema</span>
                        </div>
                        @endif

                        @if($tieneCuenta && $persona?->celular)
                        @php
                            $celularLimpio = preg_replace('/\D/', '', $persona->celular);
                            $password = strlen(preg_replace('/\D/', '', $persona->carnet ?? '')) >= 7
                                ? preg_replace('/\D/', '', $persona->carnet)
                                : 'innova' . preg_replace('/\D/', '', $persona->carnet ?? '');
                        @endphp
                        <button type="button" class="dci-wa-btn btn-enviar-whatsapp"
                            data-celular="{{ $celularLimpio }}"
                            data-nombre="{{ $nombreCompleto }}"
                            data-username="{{ $persona->usuario->username ?? '' }}"
                            data-password="{{ $password }}">
                            <i class="ri-whatsapp-line"></i>
                            Enviar accesos por WhatsApp
                        </button>
                        @endif
                    </div>

                </div>{{-- /dci-grid --}}

                @php
                    $estudioPrincipal = $persona?->estudios?->firstWhere('principal', true);
                @endphp
                @if($estudioPrincipal)
                <div style="margin-top: 1rem;">
                    <div class="est-section-title"><i class="ri-award-line"></i> Formación Principal</div>
                    <div style="display:flex; align-items:center; gap:1rem; padding:1rem; background:#fff; border:1px solid var(--doc-border); border-radius:var(--radius-md); box-shadow:var(--shadow-sm);">
                        <div style="width:40px;height:40px;border-radius:10px;background:rgba(30,87,153,0.1);color:#1e5799;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                            <i class="ri-graduation-cap-line"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:700;font-size:0.9rem;color:var(--doc-text);">{{ $estudioPrincipal->grado_academico?->nombre ?? '—' }}</div>
                            <div style="font-size:0.76rem;color:var(--doc-text-muted);margin-top:2px;">
                                {{ $estudioPrincipal->profesion?->nombre ?? '—' }} &nbsp;·&nbsp; {{ $estudioPrincipal->universidad?->nombre ?? '—' }}
                            </div>
                        </div>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;justify-content:flex-end;">
                            <span class="dci-estudio-chip"><i class="ri-star-fill"></i> Principal</span>
                            <span class="modulo-estado-badge {{ $estudioPrincipal->estado === 'Concluido' ? 'activo' : 'inactivo' }}">
                                <i class="ri-{{ $estudioPrincipal->estado === 'Concluido' ? 'check-line' : 'time-line' }}"></i>
                                {{ $estudioPrincipal->estado }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif

            </div>{{-- /tab-personal --}}

            {{-- TAB: DOCUMENTOS --}}
            @php
                $personaDoc = $persona ?? $docente->persona;
                $estudiosDoc = $personaDoc?->estudios ?? collect();

                $estadoDoc = function($archivo, $verificado) {
                    if (!$archivo) return ['label' => 'Pendiente', 'cls' => 'pending', 'icon' => 'ri-add-circle-line'];
                    if ($verificado) return ['label' => 'Aprobado', 'cls' => 'approved', 'icon' => 'ri-checkbox-circle-fill'];
                    return ['label' => 'En revision', 'cls' => 'review', 'icon' => 'ri-time-line'];
                };

                $docsIdentidad = [
                    ['nombre' => 'Carnet de Identidad', 'icono' => 'ri-id-card-line', 'archivo' => $personaDoc?->fotografia_carnet ?? null, 'verificado' => $personaDoc?->carnet_verificado ?? false, 'tipo' => 'fotografia_carnet'],
                    ['nombre' => 'Cert. Nacimiento', 'icono' => 'ri-file-paper-line', 'archivo' => $personaDoc?->fotografia_certificado_nacimiento ?? null, 'verificado' => $personaDoc?->certificado_nacimiento_verificado ?? false, 'tipo' => 'fotografia_certificado_nacimiento'],
                ];

                $totalDocs = count($docsIdentidad);
                $verificados = 0;
                foreach ($docsIdentidad as $d) { if ($d['verificado']) $verificados++; }
                foreach ($estudiosDoc as $est) { $totalDocs += 2; if ($est->documento_academico_verificado) $verificados++; if ($est->documento_provision_verificado) $verificados++; }
                $pctDocs = $totalDocs > 0 ? ($verificados / $totalDocs) * 100 : 0;
            @endphp

            <div class="doc-tabs-body" id="tab-documentos">
                <div style="padding: 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                            <i class="ri-folder-shield-line" style="color: #fc7b04;"></i>
                            Documentacion del Docente
                        </h3>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="flex: 1; max-width: 150px; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                                <div style="height: 100%; background: linear-gradient(90deg, #fc7b04, #f97316); border-radius: 4px; width: {{ number_format($pctDocs, 0) }}%; transition: width 0.3s;"></div>
                            </div>
                            <span style="font-size: 0.875rem; font-weight: 700; color: #fc7b04;">{{ number_format($pctDocs, 0) }}%</span>
                        </div>
                    </div>

                    {{-- SECCIÓN 1: DOCUMENTACIÓN DEL DOCENTE --}}
                    <div style="margin-bottom: 32px;">
                        <h4 style="font-size: 1rem; font-weight: 600; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <i class="ri-id-card-line" style="color: #fc7b04;"></i>
                            Documentación del Docente
                        </h4>
                        <div class="row g-3">
                            @foreach ($docsIdentidad as $doc)
                                @php
                                    $estado = $estadoDoc($doc['archivo'], $doc['verificado']);
                                    $bgIcon = $estado['cls'] == 'approved' ? '#dcfce7' : ($estado['cls'] == 'review' ? '#e0f2fe' : '#fef3c7');
                                    $colorIcon = $estado['cls'] == 'approved' ? '#16a34a' : ($estado['cls'] == 'review' ? '#0891b2' : '#d97706');
                                    $bgBadge = $estado['cls'] == 'approved' ? '#dcfce7' : ($estado['cls'] == 'review' ? '#e0f2fe' : '#fef3c7');
                                    $colorBadge = $estado['cls'] == 'approved' ? '#16a34a' : ($estado['cls'] == 'review' ? '#0891b2' : '#d97706');
                                @endphp
                                <div class="col-md-6" id="doc-card-{{ $doc['tipo'] }}">
                                    <div class="doc-card-compact">
                                        <div class="doc-header">
                                            <div data-doc-icon style="width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; background: {{ $bgIcon }}; color: {{ $colorIcon }}; flex-shrink: 0;">
                                                <i class="{{ $doc['icono'] }}"></i>
                                            </div>
                                            <div style="flex: 1; min-width: 0;">
                                                <div style="font-size: 0.85rem; font-weight: 600; color: #1e293b;">{{ $doc['nombre'] }}</div>
                                            </div>
                                            <span style="padding: 3px 8px; border-radius: 20px; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; background: {{ $bgBadge }}; color: {{ $colorBadge }};">
                                                {{ $estado['label'] }}
                                            </span>
                                        </div>
                                        <div class="doc-body">
                                            @if ($doc['archivo'])
                                                <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f8fafc; border-radius: 8px; margin-bottom: 10px;">
                                                    <div style="width: 32px; height: 32px; border-radius: 6px; background: #fee2e2; display: flex; align-items: center; justify-content: center; color: #dc2626; font-size: 1rem;">
                                                        <i class="ri-file-pdf-fill"></i>
                                                    </div>
                                                    <div style="flex: 1; min-width: 0;">
                                                        <div style="font-size: 0.78rem; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $doc['tipo'] }}.pdf</div>
                                                        <div data-file-status style="font-size: 0.65rem; display: flex; align-items: center; gap: 4px; color: {{ $doc['verificado'] ? '#16a34a' : '#d97706' }};">
                                                            @if ($doc['verificado'])
                                                                <i class="ri-shield-check-fill"></i> Verificado
                                                            @else
                                                                <i class="ri-time-fill"></i> En revision
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                                    <button type="button" class="btn btn-sm btn-action btn-action-view btn-ver-doc-docente"
                                                            data-id="{{ $docente->id }}"
                                                            data-tipo="{{ $doc['tipo'] }}"
                                                            title="Visualizar"
                                                            style="flex: 1; padding: 6px 10px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; min-width: 60px;">
                                                        <i class="ri-eye-line"></i> Ver
                                                    </button>
                                                    <label style="flex: 1; padding: 6px 10px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; min-width: 60px;">
                                                        <i class="ri-upload-line"></i> Cambiar
                                                        <input type="file" class="d-none btn-subir-doc-docente"
                                                               data-id="{{ $docente->id }}"
                                                               data-tipo="{{ $doc['tipo'] }}"
                                                               accept=".pdf,.png,.jpg,.jpeg">
                                                    </label>
                                                    @if (!$doc['verificado'])
                                                    <button type="button" class="btn btn-sm btn-action btn-action-edit btn-verificar-doc-docente"
                                                            data-id="{{ $docente->id }}"
                                                            data-tipo="{{ $doc['tipo'] }}"
                                                            title="Verificar"
                                                            style="flex: 1; padding: 6px 10px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; border: none; background: #22c55e; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; min-width: 70px;">
                                                        <i class="ri-check-line"></i> Aprobar
                                                    </button>
                                                    @endif
                                                </div>
                                            @else
                                                <label style="display: block; border: 2px dashed #e2e8f0; border-radius: 8px; padding: 18px 12px; text-align: center; cursor: pointer; transition: all 0.2s; background: #f8fafc;">
                                                    <input type="file" class="d-none btn-subir-doc-docente"
                                                           data-id="{{ $docente->id }}"
                                                           data-tipo="{{ $doc['tipo'] }}"
                                                           accept=".pdf,.png,.jpg,.jpeg">
                                                    <i class="ri-upload-cloud-line" style="font-size: 1.3rem; color: #64748b; margin-bottom: 4px; display: block;"></i>
                                                    <p style="font-size: 0.75rem; color: #64748b; margin: 0;">
                                                        <strong style="color: #fc7b04;">Subir archivo</strong> o arrastrar
                                                    </p>
                                                    <p style="font-size: 0.65rem; color: #94a3b8; margin: 2px 0 0;">PDF, PNG, JPG - max 2MB</p>
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- SECCIÓN 2: FORMACIÓN ACADÉMICA --}}
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                            <h4 style="font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px; margin: 0;">
                                <i class="ri-graduation-cap-line" style="color: #fc7b04;"></i>
                                Formación Académica
                                <span id="estudios-count-badge" class="badge" style="background: #e2e8f0; color: #475569; font-size: 0.7rem; font-weight: 600;">{{ $estudiosDoc->count() }}</span>
                            </h4>
                            <button type="button" class="btn btn-sm btn-agregar-estudio-docente"
                                    data-docente-id="{{ $docente->id }}"
                                    data-persona-id="{{ $personaDoc?->id }}"
                                    style="padding: 8px 16px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; border: none; background: #fc7b04; color: white; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="ri-add-line"></i> Agregar Estudio
                            </button>
                        </div>

                        @php
                            $estPrincipal = $estudiosDoc->firstWhere('principal', true);
                            $totalEstudios = $estudiosDoc->count();
                        @endphp

                        @if($estPrincipal)
                        <div class="est-principal-banner" style="margin-bottom:1.25rem;">
                            <div class="est-principal-icon"><i class="ri-graduation-cap-fill"></i></div>
                            <div class="est-principal-info">
                                <div class="est-principal-label">Grado Académico Principal</div>
                                <div class="est-principal-grado">{{ $estPrincipal->grado_academico?->nombre ?? '—' }}</div>
                                <div class="est-principal-sub">
                                    <span><i class="ri-briefcase-line"></i> {{ $estPrincipal->profesion?->nombre ?? '—' }}</span>
                                    <span><i class="ri-building-line"></i> {{ $estPrincipal->universidad?->nombre ?? '—' }}{{ $estPrincipal->universidad?->sigla ? ' (' . $estPrincipal->universidad->sigla . ')' : '' }}</span>
                                </div>
                            </div>
                            <div class="est-principal-badges">
                                <span class="est-badge est-badge-principal"><i class="ri-star-fill"></i> Principal</span>
                                <span class="est-badge {{ $estPrincipal->estado === 'Concluido' ? 'est-badge-concluido' : 'est-badge-desarrollo' }}">
                                    <i class="ri-{{ $estPrincipal->estado === 'Concluido' ? 'checkbox-circle-fill' : 'time-line' }}"></i>
                                    {{ $estPrincipal->estado }}
                                </span>
                            </div>
                        </div>
                        @else
                        <div class="est-empty-principal" style="margin-bottom:1.25rem;">
                            <i class="ri-graduation-cap-line"></i>
                            <p>No hay estudio marcado como principal. Registre uno y márquelo como principal.</p>
                        </div>
                        @endif

                        @if($totalEstudios > 0)
                        <div class="est-table-wrap">
                            <table class="doc-table">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Grado Académico</th>
                                        <th>Profesión</th>
                                        <th>Universidad</th>
                                        <th style="width:110px;">Estado</th>
                                        <th style="width:80px;text-align:center;">Docs</th>
                                        <th style="width:80px;text-align:center;">Principal</th>
                                        <th style="width:110px;text-align:center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($estudiosDoc->sortByDesc('principal') as $index => $est)
                                    <tr>
                                        <td class="text-center text-muted" style="font-size:0.75rem;">{{ $index + 1 }}</td>
                                        <td style="font-weight:600;">{{ $est->grado_academico?->nombre ?? '—' }}</td>
                                        <td style="font-size:0.84rem;">{{ $est->profesion?->nombre ?? '—' }}</td>
                                        <td style="font-size:0.84rem;">
                                            {{ $est->universidad?->nombre ?? '—' }}
                                            @if($est->universidad?->sigla)
                                                <span style="font-size:0.7rem;color:var(--doc-text-muted);"> ({{ $est->universidad->sigla }})</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="modulo-estado-badge {{ $est->estado === 'Concluido' ? 'activo' : 'inactivo' }}">
                                                <i class="ri-{{ $est->estado === 'Concluido' ? 'check-line' : 'time-line' }}"></i>
                                                {{ $est->estado }}
                                            </span>
                                        </td>
                                        <td class="text-center" style="font-size:1rem;">
                                            <span title="Doc. Académico" style="color:{{ $est->documento_academico ? ($est->documento_academico_verificado ? '#15803d' : '#d97706') : '#94a3b8' }};">
                                                <i class="{{ $est->documento_academico ? ($est->documento_academico_verificado ? 'ri-file-check-fill' : 'ri-file-warning-fill') : 'ri-file-close-fill' }}"></i>
                                            </span>
                                            <span title="Provisión Nacional" style="color:{{ $est->documento_provision_nacional ? ($est->documento_provision_verificado ? '#15803d' : '#d97706') : '#94a3b8' }};margin-left:2px;">
                                                <i class="{{ $est->documento_provision_nacional ? ($est->documento_provision_verificado ? 'ri-file-check-fill' : 'ri-file-warning-fill') : 'ri-file-close-fill' }}"></i>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($est->principal)
                                                <span class="est-badge est-badge-principal" style="font-size:0.62rem;"><i class="ri-star-fill"></i> Sí</span>
                                            @else
                                                <span style="font-size:0.72rem;color:var(--doc-text-muted);">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="display:flex;gap:0.3rem;justify-content:center;">
                                                <button class="est-table-action est-action-docs btn-toggle-docs"
                                                    data-est-id="{{ $est->id }}"
                                                    title="Ver/gestionar documentos">
                                                    <i class="ri-folder-open-line"></i>
                                                </button>
                                                @if(!$est->principal)
                                                <button class="est-table-action est-action-principal btn-set-principal-estudio-docente"
                                                    data-docente-id="{{ $docente->id }}"
                                                    data-estudio-id="{{ $est->id }}"
                                                    title="Marcar como principal">
                                                    <i class="ri-star-line"></i>
                                                </button>
                                                @endif
                                                <button class="est-table-action est-action-del btn-eliminar-estudio-docente"
                                                    data-docente-id="{{ $docente->id }}"
                                                    data-estudio-id="{{ $est->id }}"
                                                    title="Eliminar estudio">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @foreach($estudiosDoc as $est)
                        @php
                            $tieneDA  = !empty($est->documento_academico);
                            $tieneDP  = !empty($est->documento_provision_nacional);
                            $isPdfDA  = $tieneDA && strtolower(pathinfo($est->documento_academico, PATHINFO_EXTENSION)) === 'pdf';
                            $isPdfDP  = $tieneDP && strtolower(pathinfo($est->documento_provision_nacional, PATHINFO_EXTENSION)) === 'pdf';
                            $urlDA    = route('admin.docentes.estudios.documentos.visualizar', ['id' => $docente->id, 'estudioId' => $est->id]) . '?tipo=documento_academico';
                            $urlDP    = route('admin.docentes.estudios.documentos.visualizar', ['id' => $docente->id, 'estudioId' => $est->id]) . '?tipo=documento_provision_nacional';
                            $subDA    = ($est->grado_academico?->nombre ?? '') . ' · ' . ($est->universidad?->nombre ?? '');
                        @endphp
                        <div class="est-docs-panel" id="est-docs-panel-{{ $est->id }}" style="display:none;margin-top:0.75rem;border:1px solid var(--doc-border);border-radius:var(--radius-md);overflow:hidden;">
                            <div style="padding:0.65rem 1rem;background:linear-gradient(135deg,var(--doc-primary-light),rgba(252,123,4,0.03));border-bottom:1px solid var(--doc-border);display:flex;align-items:center;gap:0.5rem;">
                                <i class="ri-folder-open-line" style="color:var(--doc-primary);"></i>
                                <span style="font-size:0.78rem;font-weight:700;color:var(--doc-text);">Documentos — {{ $est->grado_academico?->nombre ?? 'Estudio #'.$est->id }}</span>
                            </div>
                            <div class="est-docs-inner">

                                <div class="est-doc-card">
                                    <div class="est-doc-card-icon"
                                        style="background:{{ $tieneDA ? 'rgba(34,197,94,0.1)' : 'rgba(148,163,184,0.1)' }};color:{{ $tieneDA ? '#15803d' : '#94a3b8' }};">
                                        <i class="ri-file-text-line"></i>
                                    </div>
                                    <div class="est-doc-card-body">
                                        <div class="est-doc-card-title">Documento Académico</div>
                                        <div class="est-doc-card-status {{ $tieneDA ? ($est->documento_academico_verificado ? 'verificado' : 'pendiente') : 'sin-doc' }}">
                                            <i class="{{ $tieneDA ? ($est->documento_academico_verificado ? 'ri-checkbox-circle-fill' : 'ri-time-line') : 'ri-upload-line' }}"></i>
                                            {{ $tieneDA ? ($est->documento_academico_verificado ? 'Verificado' : 'Pendiente de verificación') : 'Sin documento subido' }}
                                        </div>
                                    </div>
                                    <div class="est-doc-actions">
                                        <button class="est-doc-btn est-doc-btn-view btn-ver-doc-estudio"
                                            style="{{ $tieneDA ? 'display:flex;' : 'display:none;' }}"
                                            data-id="{{ $docente->id }}"
                                            data-tipo="documento_academico"
                                            data-estudio-id="{{ $est->id }}"
                                            data-url="{{ $urlDA }}"
                                            data-titulo="Documento Académico"
                                            data-subtitulo="{{ $subDA }}"
                                            data-verificado="{{ $est->documento_academico_verificado ? '1' : '0' }}"
                                            data-is-pdf="{{ $isPdfDA ? '1' : '0' }}"
                                            title="Ver documento">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <button class="est-doc-btn {{ $est->documento_academico_verificado ? 'est-doc-btn-uncheck' : 'est-doc-btn-check' }} btn-verificar-doc-estudio"
                                            style="{{ $tieneDA ? 'display:flex;' : 'display:none;' }}"
                                            data-id="{{ $docente->id }}"
                                            data-tipo="documento_academico"
                                            data-estudio-id="{{ $est->id }}"
                                            title="{{ $est->documento_academico_verificado ? 'Quitar verificación' : 'Verificar documento' }}">
                                            <i class="{{ $est->documento_academico_verificado ? 'ri-close-circle-line' : 'ri-check-line' }}"></i>
                                        </button>
                                        <label class="est-doc-btn est-doc-btn-upload" title="{{ $tieneDA ? 'Reemplazar archivo' : 'Subir archivo' }}" style="cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                            <i class="ri-upload-2-line"></i>
                                            <input type="file" accept=".pdf,.jpg,.jpeg,.png"
                                                class="btn-subir-doc-estudio"
                                                data-id="{{ $docente->id }}"
                                                data-tipo="documento_academico"
                                                data-estudio-id="{{ $est->id }}"
                                                style="display:none;">
                                        </label>
                                    </div>
                                </div>

                                <div class="est-doc-card">
                                    <div class="est-doc-card-icon"
                                        style="background:{{ $tieneDP ? 'rgba(34,197,94,0.1)' : 'rgba(148,163,184,0.1)' }};color:{{ $tieneDP ? '#15803d' : '#94a3b8' }};">
                                        <i class="ri-file-shield-line"></i>
                                    </div>
                                    <div class="est-doc-card-body">
                                        <div class="est-doc-card-title">Provisión Nacional</div>
                                        <div class="est-doc-card-status {{ $tieneDP ? ($est->documento_provision_verificado ? 'verificado' : 'pendiente') : 'sin-doc' }}">
                                            <i class="{{ $tieneDP ? ($est->documento_provision_verificado ? 'ri-checkbox-circle-fill' : 'ri-time-line') : 'ri-upload-line' }}"></i>
                                            {{ $tieneDP ? ($est->documento_provision_verificado ? 'Verificado' : 'Pendiente de verificación') : 'Sin documento subido' }}
                                        </div>
                                    </div>
                                    <div class="est-doc-actions">
                                        <button class="est-doc-btn est-doc-btn-view btn-ver-doc-estudio"
                                            style="{{ $tieneDP ? 'display:flex;' : 'display:none;' }}"
                                            data-id="{{ $docente->id }}"
                                            data-tipo="documento_provision_nacional"
                                            data-estudio-id="{{ $est->id }}"
                                            data-url="{{ $urlDP }}"
                                            data-titulo="Provisión Nacional"
                                            data-subtitulo="{{ $subDA }}"
                                            data-verificado="{{ $est->documento_provision_verificado ? '1' : '0' }}"
                                            data-is-pdf="{{ $isPdfDP ? '1' : '0' }}"
                                            title="Ver documento">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <button class="est-doc-btn {{ $est->documento_provision_verificado ? 'est-doc-btn-uncheck' : 'est-doc-btn-check' }} btn-verificar-doc-estudio"
                                            style="{{ $tieneDP ? 'display:flex;' : 'display:none;' }}"
                                            data-id="{{ $docente->id }}"
                                            data-tipo="documento_provision_nacional"
                                            data-estudio-id="{{ $est->id }}"
                                            title="{{ $est->documento_provision_verificado ? 'Quitar verificación' : 'Verificar documento' }}">
                                            <i class="{{ $est->documento_provision_verificado ? 'ri-close-circle-line' : 'ri-check-line' }}"></i>
                                        </button>
                                        <label class="est-doc-btn est-doc-btn-upload" title="{{ $tieneDP ? 'Reemplazar archivo' : 'Subir archivo' }}" style="cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                            <i class="ri-upload-2-line"></i>
                                            <input type="file" accept=".pdf,.jpg,.jpeg,.png"
                                                class="btn-subir-doc-estudio"
                                                data-id="{{ $docente->id }}"
                                                data-tipo="documento_provision_nacional"
                                                data-estudio-id="{{ $est->id }}"
                                                style="display:none;">
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="empty-state" style="padding:2rem 1rem;">
                            <i class="ri-book-open-line"></i>
                            <p>No hay estudios registrados para este docente.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- TAB: MÓDULOS --}}
            <div class="doc-tabs-body" id="tab-modulos">
                @php
                    $modulosAgrupados = $docente->modulos->groupBy('ofertas_academica_id');
                @endphp
                @if($docente->modulos && $docente->modulos->count() > 0)
                    @foreach($modulosAgrupados as $ofertaId => $modulos)
                        @php
                            $oferta = $modulos->first()->ofertaAcademica;
                            $programa = $oferta?->programa;
                        @endphp
                        <div class="doc-data-card mb-4">
                            <div class="doc-data-card-header" style="background: linear-gradient(135deg, var(--doc-primary-light) 0%, rgba(252, 123, 4, 0.04) 100%);">
                                <div class="doc-data-card-icon" style="background: var(--doc-primary); color: white;">
                                    <i class="ri-graduation-cap-line"></i>
                                </div>
                                <h5 class="doc-data-card-title">
                                    {{ $programa?->nombre ?? 'Programa sin nombre' }}
                                    <span style="font-weight: 400; color: var(--doc-text-muted); font-size: 0.8rem;"> - {{ $oferta?->nombre ?? 'Oferta sin nombre' }}</span>
                                </h5>
                                <span class="modulo-estado-badge ms-auto {{ $modulos->where('estado', 'Activo')->count() > 0 ? 'activo' : 'inactivo' }}">
                                    {{ $modulos->count() }} módulo(s)
                                </span>
                            </div>
                            <table class="doc-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Nombre del Módulo</th>
                                        <th style="width: 120px;">Estado</th>
                                        <th style="width: 100px;">Moodle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modulos as $index => $modulo)
                                    <tr>
                                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <span style="font-weight: 600;">{{ $modulo->nombre ?? 'Sin nombre' }}</span>
                                            @if($modulo->n_modulo)
                                            <span class="text-muted" style="font-size: 0.75rem;"> - Módulo {{ $modulo->n_modulo }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="modulo-estado-badge {{ ($modulo->estado ?? 'Inactivo') === 'Activo' ? 'activo' : 'inactivo' }}">
                                                <i class="ri-{{ ($modulo->estado ?? 'Inactivo') === 'Activo' ? 'check-line' : 'close-line' }}"></i>
                                                {{ $modulo->estado ?? 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($modulo->moodle_course_id)
                                            <a href="{{ config('moodle.url') }}/course/view.php?id={{ $modulo->moodle_course_id }}" 
                                               target="_blank" 
                                               class="btn btn-sm" 
                                               style="background: rgba(252, 123, 4, 0.1); color: var(--doc-primary); border: 1px solid rgba(252, 123, 4, 0.25); padding: 0.25rem 0.5rem; font-size: 0.75rem;"
                                               title="Abrir curso en Moodle">
                                                <i class="ri-external-link-line"></i> Moodle
                                            </a>
                                            @else
                                            <span style="color: var(--doc-text-muted); font-size: 0.75rem;">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @else
                <div class="empty-state">
                    <i class="ri-book-2-line"></i>
                    <p>Este docente no tiene módulos asignados.</p>
                </div>
                @endif
            </div>

            {{-- TAB: CONTABLE --}}
            <div class="doc-tabs-body" id="tab-contable">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="doc-stat-card">
                            <div class="doc-stat-body">
                                <div class="doc-stat-icon" style="background: var(--doc-primary-light); color: var(--doc-primary);">
                                    <i class="ri-book-2-line"></i>
                                </div>
                                <div>
                                    <div class="doc-stat-value">{{ $docente->modulos->count() ?? 0 }}</div>
                                    <div class="doc-stat-label">Módulos Asignados</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="doc-stat-card">
                            <div class="doc-stat-body">
                                <div class="doc-stat-icon" style="background: rgba(34, 197, 94, 0.1); color: #15803d;">
                                    <i class="ri-checkbox-circle-line"></i>
                                </div>
                                <div>
                                    <div class="doc-stat-value">{{ $docente->modulos->where('estado', 'Activo')->count() ?? 0 }}</div>
                                    <div class="doc-stat-label">Módulos Activos</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="doc-stat-card">
                            <div class="doc-stat-body">
                                <div class="doc-stat-icon" style="background: rgba(252, 123, 4, 0.1); color: var(--doc-primary);">
                                    <i class="ri-user-settings-line"></i>
                                </div>
                                <div>
                                    <div class="doc-stat-value">{{ $tieneCuenta ? '1' : '0' }}</div>
                                    <div class="doc-stat-label">Cuenta Sistema</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="doc-stat-card">
                            <div class="doc-stat-body">
                                <div class="doc-stat-icon" style="background: rgba(14, 165, 233, 0.1); color: #0284c7;">
                                    <i class="ri-calendar-line"></i>
                                </div>
                                <div>
                                    <div class="doc-stat-value">{{ $docente->created_at->format('d/m/Y') }}</div>
                                    <div class="doc-stat-label">Fecha Registro</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="doc-data-card">
                    <div class="doc-data-card-header">
                        <div class="doc-data-card-icon" style="background: var(--doc-primary-light); color: var(--doc-primary);">
                            <i class="ri-history-line"></i>
                        </div>
                        <h5 class="doc-data-card-title">Historial de Actividad</h5>
                    </div>
                    <div class="doc-data-row">
                        <div class="doc-data-row-icon" style="background: var(--doc-primary-light); color: var(--doc-primary);">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                        <div style="flex: 1;">
                            <div class="doc-data-row-label">Fecha de Registro</div>
                            <div class="doc-data-row-value">{{ $docente->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="doc-data-row">
                        <div class="doc-data-row-icon" style="background: var(--doc-primary-light); color: var(--doc-primary);">
                            <i class="ri-update-line"></i>
                        </div>
                        <div style="flex: 1;">
                            <div class="doc-data-row-label">Última Actualización</div>
                            <div class="doc-data-row-value">{{ $docente->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            </div>

        </div>
    </div>
</div>

{{-- Modal Agregar/Editar Estudio --}}
<div class="modal fade" id="modalAgregarEstudioDocente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #391b04, #5c2d0a); padding: 16px 20px;">
                <h5 class="modal-title text-white" style="font-family: 'Outfit', sans-serif;">
                    <i class="ri-graduation-cap-line me-2"></i><span id="modalEstudioTituloDocente">Registrar Estudio</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background: #f8fafc;">
                <input type="hidden" id="estudioPersonaIdDocente">
                <input type="hidden" id="estudioDocenteId">
                <input type="hidden" id="estudioEditIdDocente">

                <div id="estudioErrorMsgDocente" style="display: none; padding: 10px 14px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; color: #dc2626; font-size: 0.85rem; font-weight: 500; margin-bottom: 16px; align-items: center; gap: 8px;">
                    <i class="ri-alert-circle-line" style="font-size: 1.1rem; flex-shrink: 0;"></i>
                    <span id="estudioErrorTextDocente"></span>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">Grado Académico *</label>
                        <select class="form-select" id="estudioTipoDocente" style="border-radius: 8px;">
                            <option value="">Seleccionar...</option>
                            @foreach($gradosAcademicos as $grado)
                                <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">Profesión</label>
                        <select class="form-select" id="estudioProfesionDocente" style="border-radius: 8px;">
                            <option value="">Seleccionar...</option>
                            @foreach($profesiones as $prof)
                                <option value="{{ $prof->id }}">{{ $prof->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">Universidad</label>
                        <select class="form-select" id="estudioUniversidadDocente" style="border-radius: 8px;">
                            <option value="">Seleccionar...</option>
                            @foreach($universidades as $uni)
                                <option value="{{ $uni->id }}">{{ $uni->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">Estado</label>
                        <select class="form-select" id="estudioEstadoDocente" style="border-radius: 8px;">
                            <option value="Concluido">Concluido</option>
                            <option value="En Desarrollo">En Desarrollo</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">Documento Académico (Título/Bachiller)</label>
                        <input type="file" class="form-control" id="inputDocumentoAcademicoDocente" accept=".pdf,.png,.jpg,.jpeg" style="border-radius: 8px;">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">Documento Provisión Nacional</label>
                        <input type="file" class="form-control" id="inputProvisionNacionalDocente" accept=".pdf,.png,.jpg,.jpeg" style="border-radius: 8px;">
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="estudioPrincipalDocente" value="1" checked>
                            <label class="form-check-label" for="estudioPrincipalDocente" style="font-size: 0.875rem;">
                                Establecer como estudio principal
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: white; border-top: 1px solid #e2e8f0; padding: 12px 20px;">
                <button type="button" id="btnGuardarEstudioDocente" class="btn" style="background: #fc7b04; color: white; border-radius: 8px;">
                    <i class="ri-save-line me-1"></i>Guardar
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Confirmar Principal --}}
<div class="modal fade" id="modalConfirmarPrincipalDocente" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: linear-gradient(135deg, #391b04, #5c2d0a); padding: 16px 20px; border: none;">
                <h5 class="modal-title text-white" style="font-size: 0.95rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <i class="ri-star-line"></i> Cambiar Formación Principal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px 20px;">
                <div style="display: flex; align-items: flex-start; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: #fef3c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="ri-star-fill" style="font-size: 1.3rem; color: #d97706;"></i>
                    </div>
                    <div>
                        <p style="margin: 0 0 6px; font-size: 0.9rem; font-weight: 600; color: #1e293b;">¿Marcar como formación principal?</p>
                        <p style="margin: 0; font-size: 0.8rem; color: #64748b; line-height: 1.5;">
                            Esta acción cambiará la formación académica principal del docente. La anterior dejará de ser principal.
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 12px 20px; border-top: 1px solid #e2e8f0; gap: 8px;">
                <button type="button" class="btn" data-bs-dismiss="modal"
                        style="padding: 8px 16px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; border: 1px solid #e2e8f0; background: #fff; color: #64748b;">
                    Cancelar
                </button>
                <button type="button" id="btnConfirmarPrincipalDocente"
                        style="padding: 8px 18px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; border: none; background: linear-gradient(135deg, #fc7b04, #f97316); color: white; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="ri-star-fill"></i> Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal visualización de documentos --}}
<div class="est-modal-overlay" id="modal-doc-view" onclick="cerrarModalDocOverlay(event)">
    <div class="est-modal-box">
        <div class="est-modal-header">
            <div class="est-modal-header-icon" id="modal-doc-icon" style="background:rgba(252,123,4,0.1);color:var(--doc-primary);">
                <i class="ri-file-text-line"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div class="est-modal-title" id="modal-doc-title"></div>
                <div class="est-modal-sub" id="modal-doc-sub"></div>
            </div>
            <div id="modal-doc-badge" style="flex-shrink:0;margin-right:0.5rem;"></div>
            <button class="est-modal-close" onclick="cerrarModalDoc()" title="Cerrar"><i class="ri-close-line"></i></button>
        </div>
        <div class="est-modal-body" id="modal-doc-body">
            <div style="display:flex;align-items:center;justify-content:center;height:300px;color:var(--doc-text-muted);">
                <i class="ri-loader-4-line" style="font-size:2rem;animation:spin 1s linear infinite;"></i>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
</style>

<script>
function switchDocTab(btn, tabId) {
    document.querySelectorAll('.doc-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.doc-tabs-body').forEach(t => t.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
}

function enviarWhatsappDocente(celular, nombre, username, password) {
    if (!celular || celular.length < 8) { alert('El docente no tiene un celular válido registrado.'); return; }
    const mensaje = '*¡Bienvenido/a como Docente!*\n\nEstimado/a ' + nombre + ',\n\nLe proporcionamos sus datos de acceso al sistema:\n\n*Usuario:* ' + username + '\n*Contraseña:* ' + password + '\n\n*Área Académica Innova-Ciencia-Virtual*';
    window.open('https://wa.me/' + celular + '?text=' + encodeURIComponent(mensaje), '_blank');
}

function abrirModalDoc(btn) {
    document.getElementById('modal-doc-title').textContent = btn.dataset.titulo;
    document.getElementById('modal-doc-sub').textContent = btn.dataset.subtitulo || '';
    const isDA = btn.dataset.titulo === 'Documento Académico';
    document.getElementById('modal-doc-icon').innerHTML = '<i class="' + (isDA ? 'ri-file-text-line' : 'ri-file-shield-line') + '"></i>';
    const v = btn.dataset.verificado === '1';
    document.getElementById('modal-doc-badge').innerHTML = v
        ? '<span class="modulo-estado-badge activo" style="font-size:0.68rem;"><i class="ri-checkbox-circle-fill"></i> Verificado</span>'
        : '<span class="modulo-estado-badge inactivo" style="font-size:0.68rem;"><i class="ri-time-line"></i> Pendiente</span>';
    const body = document.getElementById('modal-doc-body');
    if (btn.dataset.isPdf === '1') {
        body.innerHTML = '<iframe src="' + btn.dataset.url + '" style="width:100%;height:68vh;border:none;display:block;" allowfullscreen></iframe>';
    } else {
        body.innerHTML = '<div style="padding:1.5rem;display:flex;align-items:center;justify-content:center;min-height:300px;background:#f1f5f9;"><img src="' + btn.dataset.url + '" style="max-width:100%;max-height:68vh;object-fit:contain;border-radius:10px;box-shadow:0 2px 16px rgba(0,0,0,0.12);" onerror="this.parentNode.innerHTML=\'<span style=color:var(--doc-text-muted)>No se pudo cargar la imagen</span>\'"></div>';
    }
    document.getElementById('modal-doc-view').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function cerrarModalDoc() {
    document.getElementById('modal-doc-view').classList.remove('open');
    document.getElementById('modal-doc-body').innerHTML = '';
    document.body.style.overflow = '';
}

function cerrarModalDocOverlay(e) {
    if (e.target === document.getElementById('modal-doc-view')) cerrarModalDoc();
}

function validarArchivo(archivo) {
    const tiposOk = ['application/pdf', 'image/png', 'image/jpeg'];
    if (archivo.size > 2048 * 1024) { showToast('El archivo no puede superar los 2 MB.', 'error'); return false; }
    if (!tiposOk.includes(archivo.type)) { showToast('Solo se permiten archivos PDF, JPG o PNG.', 'error'); return false; }
    return true;
}

function showToast(msg, type) {
    type = type || 'success';
    const t = document.createElement('div');
    t.className = 'est-toast est-toast-' + type;
    const icons = { success: 'ri-checkbox-circle-fill', error: 'ri-close-circle-fill', info: 'ri-information-fill' };
    t.innerHTML = '<i class="' + (icons[type] || icons.success) + '"></i> ' + msg;
    document.body.appendChild(t);
    requestAnimationFrame(() => { requestAnimationFrame(() => t.classList.add('show')); });
    setTimeout(() => { t.classList.remove('show'); setTimeout(() => t.remove(), 400); }, 3200);
}

function buildIdentityDocHTML(tipo, docenteId) {
    return '<div style="display:flex;align-items:center;gap:10px;padding:10px;background:#f8fafc;border-radius:8px;margin-bottom:10px;">'
        + '<div style="width:32px;height:32px;border-radius:6px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1rem;"><i class="ri-file-pdf-fill"></i></div>'
        + '<div style="flex:1;min-width:0;">'
        + '<div style="font-size:0.78rem;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + tipo + '.pdf</div>'
        + '<div data-file-status style="font-size:0.65rem;display:flex;align-items:center;gap:4px;color:#d97706;"><i class="ri-time-fill"></i> En revision</div>'
        + '</div></div>'
        + '<div style="display:flex;gap:6px;flex-wrap:wrap;">'
        + '<button type="button" class="btn btn-sm btn-action btn-action-view btn-ver-doc-docente" data-id="' + docenteId + '" data-tipo="' + tipo + '" title="Visualizar" style="flex:1;padding:6px 10px;border-radius:6px;font-size:0.72rem;font-weight:600;border:1px solid #e2e8f0;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:4px;min-width:60px;"><i class="ri-eye-line"></i> Ver</button>'
        + '<label style="flex:1;padding:6px 10px;border-radius:6px;font-size:0.72rem;font-weight:600;border:1px solid #e2e8f0;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:4px;min-width:60px;"><i class="ri-upload-line"></i> Cambiar<input type="file" class="d-none btn-subir-doc-docente" data-id="' + docenteId + '" data-tipo="' + tipo + '" accept=".pdf,.png,.jpg,.jpeg"></label>'
        + '<button type="button" class="btn btn-sm btn-action btn-action-edit btn-verificar-doc-docente" data-id="' + docenteId + '" data-tipo="' + tipo + '" title="Verificar" style="flex:1;padding:6px 10px;border-radius:6px;font-size:0.72rem;font-weight:600;border:none;background:#22c55e;color:white;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:4px;min-width:70px;"><i class="ri-check-line"></i> Aprobar</button>'
        + '</div>';
}

document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    const docenteId = '{{ $docente->id }}';

    // ─── Subir / Cambiar doc identidad ───
    document.addEventListener('change', function(e) {
        const input = e.target.closest('.btn-subir-doc-docente');
        if (!input || !input.files.length) return;
        const file = input.files[0];
        if (!validarArchivo(file)) { input.value = ''; return; }
        const tipo = input.dataset.tipo;
        const fd = new FormData();
        fd.append('tipo_documento', tipo);
        fd.append('archivo', file);
        const label = input.closest('label');
        if (label) label.style.pointerEvents = 'none';
        fetch('/admin/docentes/' + docenteId + '/documentos/subir', {
            method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: fd
        }).then(r => r.json()).then(data => {
            if (label) label.style.pointerEvents = '';
            if (data.success) {
                showToast(data.mensaje || 'Documento subido', 'success');
                const colEl = document.getElementById('doc-card-' + tipo);
                if (colEl) {
                    const iconEl = colEl.querySelector('[data-doc-icon]');
                    const badge  = colEl.querySelector('.doc-header > span');
                    if (iconEl) { iconEl.style.background = '#e0f2fe'; iconEl.style.color = '#0891b2'; }
                    if (badge)  { badge.style.background = '#e0f2fe'; badge.style.color = '#0891b2'; badge.textContent = 'En revisión'; }
                    const docBody = colEl.querySelector('.doc-body');
                    if (docBody) docBody.innerHTML = buildIdentityDocHTML(tipo, docenteId);
                }
            } else {
                showToast(data.error || 'Error al subir', 'error');
            }
        }).catch(() => { if (label) label.style.pointerEvents = ''; showToast('Error de conexión', 'error'); });
    });

    // ─── Aprobar doc identidad ───
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-verificar-doc-docente');
        if (!btn) return;
        const tipo = btn.dataset.tipo;
        fetch('/admin/docentes/' + docenteId + '/documentos/verificar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ tipo_documento: tipo, accion: 'verificar' })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                showToast(data.mensaje || 'Documento verificado', 'success');
                const colEl = document.getElementById('doc-card-' + tipo);
                if (colEl) {
                    const iconEl     = colEl.querySelector('[data-doc-icon]');
                    const badge      = colEl.querySelector('.doc-header > span');
                    const fileStatus = colEl.querySelector('[data-file-status]');
                    if (iconEl)     { iconEl.style.background = '#dcfce7'; iconEl.style.color = '#16a34a'; }
                    if (badge)      { badge.style.background = '#dcfce7'; badge.style.color = '#16a34a'; badge.textContent = 'Aprobado'; }
                    if (fileStatus) { fileStatus.style.color = '#16a34a'; fileStatus.innerHTML = '<i class="ri-shield-check-fill"></i> Verificado'; }
                    btn.remove();
                }
            } else {
                showToast(data.error || 'Error', 'error');
            }
        }).catch(() => showToast('Error de conexión', 'error'));
    });

    // ─── Ver identidad (nueva pestaña) ───
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-ver-doc-docente');
        if (!btn) return;
        window.open('/admin/docentes/' + docenteId + '/documentos/visualizar?tipo=' + btn.dataset.tipo, '_blank');
    });

    // ─── Subir / Cambiar doc estudio ───
    document.addEventListener('change', function(e) {
        const input = e.target.closest('.btn-subir-doc-estudio');
        if (!input || !input.files.length) return;
        const file = input.files[0];
        if (!validarArchivo(file)) { input.value = ''; return; }
        const estudioId = input.dataset.estudioId;
        const tipo = input.dataset.tipo;
        const fd = new FormData();
        fd.append('tipo_documento', tipo);
        fd.append('archivo', file);
        const label = input.closest('label');
        if (label) label.style.pointerEvents = 'none';
        fetch('/admin/docentes/' + docenteId + '/estudios/' + estudioId + '/documentos/subir', {
            method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: fd
        }).then(r => r.json()).then(data => {
            if (label) label.style.pointerEvents = '';
            if (data.success) {
                showToast(data.message || 'Documento subido', 'success');
                const card = input.closest('.est-doc-card');
                if (card) {
                    const iconEl   = card.querySelector('.est-doc-card-icon');
                    const statusEl = card.querySelector('.est-doc-card-status');
                    const viewBtn  = card.querySelector('.btn-ver-doc-estudio');
                    const checkBtn = card.querySelector('.btn-verificar-doc-estudio');
                    if (iconEl)  { iconEl.style.background = 'rgba(34,197,94,0.1)'; iconEl.style.color = '#15803d'; }
                    if (statusEl) { statusEl.className = 'est-doc-card-status pendiente'; statusEl.innerHTML = '<i class="ri-time-line"></i> Pendiente de verificación'; }
                    if (viewBtn) {
                        viewBtn.style.display = 'flex';
                        viewBtn.dataset.url = '/admin/docentes/' + docenteId + '/estudios/' + estudioId + '/documentos/visualizar?tipo=' + tipo;
                        viewBtn.dataset.verificado = '0';
                        viewBtn.dataset.isPdf = data.is_pdf ? '1' : '0';
                    }
                    if (checkBtn) {
                        checkBtn.style.display = 'flex';
                        checkBtn.className = 'est-doc-btn est-doc-btn-check btn-verificar-doc-estudio';
                        checkBtn.title = 'Verificar documento';
                        checkBtn.innerHTML = '<i class="ri-check-line"></i>';
                    }
                }
            } else {
                showToast(data.message || 'Error al subir', 'error');
            }
        }).catch(() => { if (label) label.style.pointerEvents = ''; showToast('Error de conexión', 'error'); });
    });

    // ─── Aprobar / quitar verificación doc estudio ───
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-verificar-doc-estudio');
        if (!btn) return;
        const estudioId = btn.dataset.estudioId;
        const tipo = btn.dataset.tipo;
        const accion = btn.classList.contains('est-doc-btn-uncheck') ? 'quitar' : 'verificar';
        fetch('/admin/docentes/' + docenteId + '/estudios/' + estudioId + '/documentos/verificar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ tipo_documento: tipo, accion: accion })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                showToast(data.message || (data.verificado ? 'Documento verificado' : 'Verificación removida'), 'success');
                const card = btn.closest('.est-doc-card');
                if (card) {
                    const statusEl = card.querySelector('.est-doc-card-status');
                    const viewBtn  = card.querySelector('.btn-ver-doc-estudio');
                    if (data.verificado) {
                        if (statusEl) { statusEl.className = 'est-doc-card-status verificado'; statusEl.innerHTML = '<i class="ri-checkbox-circle-fill"></i> Verificado'; }
                        if (viewBtn) viewBtn.dataset.verificado = '1';
                        btn.className = 'est-doc-btn est-doc-btn-uncheck btn-verificar-doc-estudio';
                        btn.title = 'Quitar verificación';
                        btn.innerHTML = '<i class="ri-close-circle-line"></i>';
                    } else {
                        if (statusEl) { statusEl.className = 'est-doc-card-status pendiente'; statusEl.innerHTML = '<i class="ri-time-line"></i> Pendiente de verificación'; }
                        if (viewBtn) viewBtn.dataset.verificado = '0';
                        btn.className = 'est-doc-btn est-doc-btn-check btn-verificar-doc-estudio';
                        btn.title = 'Verificar documento';
                        btn.innerHTML = '<i class="ri-check-line"></i>';
                    }
                }
            } else {
                showToast(data.message || 'Error', 'error');
            }
        }).catch(() => showToast('Error de conexión', 'error'));
    });

    // ─── Ver doc estudio (modal) ───
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-ver-doc-estudio');
        if (!btn) return;
        abrirModalDoc(btn);
    });

    // ─── Agregar estudio ───
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-agregar-estudio-docente');
        if (!btn) return;
        document.getElementById('estudioEditIdDocente').value = '';
        document.getElementById('estudioPersonaIdDocente').value = btn.dataset.personaId;
        document.getElementById('estudioDocenteId').value = btn.dataset.docenteId;
        document.getElementById('estudioPrincipalDocente').checked = true;
        document.getElementById('estudioTipoDocente').value = '';
        document.getElementById('estudioProfesionDocente').value = '';
        document.getElementById('estudioUniversidadDocente').value = '';
        document.getElementById('estudioEstadoDocente').value = 'Concluido';
        document.getElementById('inputDocumentoAcademicoDocente').value = '';
        document.getElementById('inputProvisionNacionalDocente').value = '';
        document.getElementById('inputDocumentoAcademicoDocente').closest('.col-md-6').style.display = 'block';
        document.getElementById('inputProvisionNacionalDocente').closest('.col-md-6').style.display = 'block';
        document.getElementById('estudioErrorMsgDocente').style.display = 'none';
        document.getElementById('modalEstudioTituloDocente').textContent = 'Registrar Estudio';
        new bootstrap.Modal(document.getElementById('modalAgregarEstudioDocente')).show();
    });

    // ─── Editar estudio ───
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-editar-estudio-docente');
        if (!btn) return;
        document.getElementById('estudioEditIdDocente').value = btn.dataset.estudioId;
        document.getElementById('estudioPersonaIdDocente').value = btn.dataset.personaId;
        document.getElementById('estudioDocenteId').value = btn.dataset.docenteId;
        document.getElementById('estudioTipoDocente').value = btn.dataset.gradoId || '';
        document.getElementById('estudioProfesionDocente').value = btn.dataset.profesionId || '';
        document.getElementById('estudioUniversidadDocente').value = btn.dataset.universidadId || '';
        document.getElementById('estudioEstadoDocente').value = btn.dataset.estado || 'Concluido';
        document.getElementById('estudioPrincipalDocente').checked = btn.dataset.principal === '1';
        document.getElementById('inputDocumentoAcademicoDocente').value = '';
        document.getElementById('inputProvisionNacionalDocente').value = '';
        document.querySelector('#inputDocumentoAcademicoDocente').closest('.col-md-6').style.display = 'none';
        document.querySelector('#inputProvisionNacionalDocente').closest('.col-md-6').style.display = 'none';
        document.getElementById('estudioErrorMsgDocente').style.display = 'none';
        document.getElementById('modalEstudioTituloDocente').textContent = 'Editar Estudio';
        new bootstrap.Modal(document.getElementById('modalAgregarEstudioDocente')).show();
    });

    // ─── Guardar / Actualizar estudio ───
    document.getElementById('btnGuardarEstudioDocente').addEventListener('click', async function() {
        const editId = document.getElementById('estudioEditIdDocente').value;
        const personaId = document.getElementById('estudioPersonaIdDocente').value;
        const gradoAcademicoId = document.getElementById('estudioTipoDocente').value;
        const profesioneId = document.getElementById('estudioProfesionDocente').value;
        const universidadeId = document.getElementById('estudioUniversidadDocente').value;
        const estado = document.getElementById('estudioEstadoDocente').value;
        const esPrincipal = document.getElementById('estudioPrincipalDocente').checked;

        const errorDiv = document.getElementById('estudioErrorMsgDocente');
        const errorText = document.getElementById('estudioErrorTextDocente');
        errorDiv.style.display = 'none';

        if (!gradoAcademicoId) {
            errorText.textContent = 'Por favor seleccione el grado académico';
            errorDiv.style.display = 'flex';
            return;
        }

        const esEditar = !!editId;

        if (esEditar) {
            fetch('/admin/personas/' + personaId + '/estudios/' + editId, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: (function() {
                    const fd = new FormData();
                    fd.append('_method', 'PUT');
                    fd.append('grados_academico_id', gradoAcademicoId);
                    if (profesioneId) fd.append('profesione_id', profesioneId);
                    if (universidadeId) fd.append('universidade_id', universidadeId);
                    fd.append('estado', estado);
                    fd.append('principal', esPrincipal ? 1 : 0);
                    return fd;
                })()
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalAgregarEstudioDocente'))?.hide();
                    showToast(data.message || 'Estudio actualizado', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    errorText.textContent = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Error al actualizar');
                    errorDiv.style.display = 'flex';
                }
            })
            .catch(() => {
                errorText.textContent = 'Error de conexión al actualizar';
                errorDiv.style.display = 'flex';
            });
        } else {
            const urlGuardar = '/admin/docentes/' + document.getElementById('estudioDocenteId').value + '/estudios';
            const fd = new FormData();
            fd.append('grados_academico_id', gradoAcademicoId);
            if (profesioneId) fd.append('profesione_id', profesioneId);
            if (universidadeId) fd.append('universidade_id', universidadeId);
            fd.append('estado', estado);
            fd.append('principal', esPrincipal ? 1 : 0);

            const archivoAcademico = document.getElementById('inputDocumentoAcademicoDocente').files[0];
            const archivoProvision = document.getElementById('inputProvisionNacionalDocente').files[0];
            if (archivoAcademico) fd.append('documento_academico', archivoAcademico);
            if (archivoProvision) fd.append('documento_provision_nacional', archivoProvision);

            fetch(urlGuardar, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalAgregarEstudioDocente'))?.hide();
                    showToast(data.message || 'Estudio registrado', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    errorText.textContent = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Error al guardar');
                    errorDiv.style.display = 'flex';
                }
            })
            .catch(() => {
                errorText.textContent = 'Error de conexión al guardar';
                errorDiv.style.display = 'flex';
            });
        }
    });

    // ─── Marcar principal ───
    let _setPrincipalDocenteId = null;
    let _setPrincipalEstudioId = null;

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-set-principal-estudio-docente');
        if (!btn) return;
        _setPrincipalDocenteId = btn.dataset.docenteId;
        _setPrincipalEstudioId = btn.dataset.estudioId;
        new bootstrap.Modal(document.getElementById('modalConfirmarPrincipalDocente')).show();
    });

    document.getElementById('btnConfirmarPrincipalDocente').addEventListener('click', function() {
        const estudioId = _setPrincipalEstudioId;
        if (!estudioId) return;
        bootstrap.Modal.getInstance(document.getElementById('modalConfirmarPrincipalDocente'))?.hide();
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="ri-loader-4-line"></i> Guardando...';
        fetch('/admin/docentes/' + _setPrincipalDocenteId + '/estudios/' + estudioId + '/principal', {
            method: 'PATCH', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(r => r.json().catch(() => ({ success: false, message: 'Error del servidor' })))
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-star-fill"></i> Confirmar';
            if (data.success) { showToast(data.message || 'Estudio marcado como principal', 'success'); setTimeout(() => location.reload(), 1000); }
            else { showToast(data.message || 'Error al marcar como principal', 'error'); }
        })
        .catch(() => { btn.disabled = false; btn.innerHTML = '<i class="ri-star-fill"></i> Confirmar'; showToast('Error de conexión', 'error'); });
    });

    // ─── Eliminar estudio (sin reload) ───
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-eliminar-estudio-docente');
        if (!btn) return;
        if (!confirm('¿Está seguro de eliminar este estudio? Los documentos asociados también se eliminarán.')) return;
        const estudioId = btn.dataset.estudioId;
        const row = btn.closest('tr');
        const wasPrincipal = !!row?.querySelector('.est-badge-principal');
        fetch('/admin/docentes/' + btn.dataset.docenteId + '/estudios/' + estudioId, {
            method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken }
        })
        .then(r => r.json().catch(() => ({ success: false, message: 'Error del servidor' })))
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Estudio eliminado', 'success');
                if (row) row.remove();
                const panel = document.getElementById('est-docs-panel-' + estudioId);
                if (panel) panel.remove();
                const countBadge = document.getElementById('estudios-count-badge');
                if (countBadge) countBadge.textContent = Math.max(0, parseInt(countBadge.textContent) - 1);
                if (wasPrincipal) {
                    const banner = document.querySelector('.est-principal-banner');
                    if (banner) banner.outerHTML = '<div class="est-empty-principal" style="margin-bottom:1.25rem;"><i class="ri-graduation-cap-line"></i><p>No hay estudio marcado como principal. Registre uno y márquelo como principal.</p></div>';
                }
                const tbody = document.querySelector('.est-table-wrap tbody');
                if (tbody && tbody.querySelectorAll('tr').length === 0) {
                    const tableWrap = document.querySelector('.est-table-wrap');
                    if (tableWrap) tableWrap.outerHTML = '<div class="empty-state" style="padding:2rem 1rem;"><i class="ri-book-open-line"></i><p>No hay estudios registrados para este docente.</p></div>';
                }
            } else {
                showToast(data.message || 'Error al eliminar', 'error');
            }
        })
        .catch(() => showToast('Error de conexión', 'error'));
    });

    // ─── Toggle panel de documentos ───
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-toggle-docs');
        if (!btn) return;
        const estId   = btn.dataset.estId;
        const panel   = document.getElementById('est-docs-panel-' + estId);
        if (!panel) return;
        const visible = panel.style.display !== 'none';
        document.querySelectorAll('.est-docs-panel').forEach(p => p.style.display = 'none');
        document.querySelectorAll('.btn-toggle-docs').forEach(b => b.classList.remove('est-action-docs-active'));
        if (!visible) { panel.style.display = 'block'; btn.classList.add('est-action-docs-active'); }
    });

    // ─── WhatsApp ───
    document.querySelectorAll('.btn-enviar-whatsapp').forEach(function(btn) {
        btn.addEventListener('click', function() {
            enviarWhatsappDocente(this.dataset.celular, this.dataset.nombre, this.dataset.username, this.dataset.password);
        });
    });
});
</script>
@endsection