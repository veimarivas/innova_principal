@extends('layouts.virtual')
@section('title')
    Mi Portal Virtual
@endsection

@if (session('route_not_found'))
    <div class="container mt-3">
        <div class="alert alert-warning" role="alert">
            {{ session('route_not_found') }}
        </div>
    </div>
@endif

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

        /* ── Variables ────────────────────────────────────────────── */
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
            background: linear-gradient(135deg, #391b04 0%, #743c04 45%, #c96004 100%);
            border-radius: 16px;
            padding: 1.75rem 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.75rem;
            color: #fff;
        }

        .est-hero-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, .4);
            flex-shrink: 0;
        }

        .est-hero-name {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: .15rem;
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
            margin-top: .6rem;
            flex-wrap: wrap;
        }

        .est-hero-badge {
            background: rgba(255, 255, 255, .18);
            border-radius: 20px;
            padding: .2rem .7rem;
            font-size: .72rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
        }

        .est-hero-badge.sin {
            background: rgba(255, 80, 80, .25);
        }

        .perfil-selector {
            display: flex;
            align-items: center;
            gap: .4rem;
            margin-top: .5rem;
            flex-wrap: wrap;
        }

        .perfil-btn {
            background: rgba(255, 255, 255, .15);
            border: 1.5px solid rgba(255, 255, 255, .3);
            border-radius: 8px;
            padding: .3rem .75rem;
            font-size: .72rem;
            font-weight: 600;
            color: rgba(255, 255, 255, .75);
            cursor: pointer;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
        }

        .perfil-btn:hover {
            background: rgba(255, 255, 255, .25);
            color: #fff;
        }

        .perfil-btn.active {
            background: rgba(255, 255, 255, .3);
            border-color: rgba(255, 255, 255, .6);
            color: #fff;
            font-weight: 700;
        }

        .docente-curso-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            padding: 1.25rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: box-shadow .2s;
        }

        .docente-curso-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        }

        .docente-curso-color {
            width: 6px;
            height: 100%;
            min-height: 60px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .docente-curso-info {
            flex: 1;
        }

        .docente-curso-num {
            font-size: .7rem;
            font-weight: 700;
            color: var(--est-text-muted);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .15rem;
        }

        .docente-curso-nombre {
            font-size: 1rem;
            font-weight: 700;
            color: var(--est-text);
            margin-bottom: .3rem;
        }

        .docente-curso-oferta {
            font-size: .8rem;
            color: var(--est-text-muted);
            margin-bottom: .4rem;
        }

        .docente-curso-fechas {
            font-size: .75rem;
            color: var(--est-text-muted);
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .docente-curso-fechas span {
            display: inline-flex;
            align-items: center;
            gap: .2rem;
        }

        .docente-curso-acciones {
            display: flex;
            flex-direction: column;
            gap: .4rem;
            flex-shrink: 0;
        }

        html[data-bs-theme="dark"] .docente-curso-card {
            background: #1a1d21;
            border-color: #2c2e33;
        }

        html[data-bs-theme="dark"] .docente-curso-nombre {
            color: #e2e8f0;
        }

        html[data-bs-theme="dark"] .docente-curso-oferta,
        html[data-bs-theme="dark"] .docente-curso-fechas {
            color: #94a3b8;
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

        .est-stat-icon-sm.orange {
            background: rgba(252, 123, 4, .12);
            color: #fc7b04;
        }

        .est-stat-icon-sm.blue {
            background: rgba(59, 130, 246, .12);
            color: #3b82f6;
        }

        .est-stat-icon-sm.green {
            background: rgba(90, 138, 48, .12);
            color: #5a8a30;
        }

        .est-stat-num {
            font-size: 1.4rem;
            font-weight: 700;
            line-height: 1;
        }

        .est-stat-label {
            font-size: .72rem;
            color: #6c757d;
            margin-top: .1rem;
        }

        /* ── Tabs card ────────────────────────────────────────────── */
        .est-tabs-card {
            background: #fff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--est-border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 1.75rem;
        }

        html[data-bs-theme="dark"] .est-tabs-card {
            background: #1a1d21;
            border-color: #2c2e33;
        }

        .est-tabs-nav {
            display: flex;
            overflow-x: auto;
            scrollbar-width: none;
            background: var(--est-surface);
            border-bottom: 1px solid var(--est-border);
        }

        .est-tabs-nav::-webkit-scrollbar {
            display: none;
        }

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

        html[data-bs-theme="dark"] .est-tab-btn.active {
            background: #1a1d21;
        }

        .est-tab-btn i {
            font-size: 1.05rem;
        }

        .est-tabs-body {
            padding: 24px;
            display: none;
        }

        .est-tabs-body.active {
            display: block;
        }

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

        .est-ci-section-title {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .9rem;
            font-weight: 600;
            color: var(--est-text-muted);
            margin-bottom: .75rem;
            padding-bottom: .5rem;
            border-bottom: 1px solid var(--est-border);
        }

        .est-ci-ofertas-list {
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .est-ci-oferta-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .6rem .75rem;
            background: var(--est-bg-card);
            border: 1px solid var(--est-border);
            border-radius: 8px;
        }

        .est-ci-oferta-name {
            font-size: .875rem;
            font-weight: 500;
            color: var(--est-text);
        }

        .est-ci-oferta-details {
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: .8rem;
        }

        .est-ci-oferta-estado {
            padding: .2rem .5rem;
            border-radius: 4px;
            background: var(--est-primary-light);
            color: var(--est-primary);
            font-weight: 500;
        }

        .est-ci-oferta-saldo {
            font-weight: 600;
        }

        .est-ci-oferta-saldo.pendiente {
            color: #e11d48;
        }

        .est-ci-oferta-saldo.al-dia {
            color: #16a34a;
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

        /* ── Contable ─────────────────────────────────────────────── */
        .est-stat-card {
            background: #fff;
            border-radius: var(--radius-md);
            border: 1px solid var(--est-border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: all .25s ease;
        }

        html[data-bs-theme="dark"] .est-stat-card {
            background: #1a1d21;
            border-color: #2c2e33;
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

        html[data-bs-theme="dark"] .est-oferta-tab-btn {
            background: #1a1d21;
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

        .est-oferta-content {
            display: none;
        }

        .est-oferta-content.active {
            display: block;
        }

        .est-data-card {
            background: #fff;
            border-radius: var(--radius-md);
            border: 1px solid var(--est-border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        html[data-bs-theme="dark"] .est-data-card {
            background: #1a1d21;
            border-color: #2c2e33;
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

        .est-table tbody tr:last-child td {
            border-bottom: none;
        }

        .est-table tbody tr:hover td {
            background: var(--est-primary-light);
        }

        .estado-badge-est {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: .7rem;
            font-weight: 600;
        }

        .estado-badge-est.pagado {
            background: var(--est-success-light);
            color: var(--est-success);
        }

        .estado-badge-est.pendiente {
            background: var(--est-warning-light);
            color: var(--est-warning);
        }

        .estado-badge-est.vencido {
            background: var(--est-danger-light);
            color: var(--est-danger);
        }

        .est-empty-state {
            padding: 40px 24px;
            text-align: center;
            background: #fff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--est-border);
        }

        html[data-bs-theme="dark"] .est-empty-state {
            background: #1a1d21;
            border-color: #2c2e33;
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

        html[data-bs-theme="dark"] .est-section-title {
            border-color: #2c2e33;
        }

        .est-section-title i {
            color: #fc7b04;
        }

        .est-prog-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e9ecef;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        html[data-bs-theme="dark"] .est-prog-card {
            background: #1a1d21;
            border-color: #2c2e33;
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

        .est-estado-badge.inscrito {
            background: rgba(90, 138, 48, .2);
            color: #5a8a30;
        }

        .est-estado-badge.pendiente {
            background: rgba(252, 123, 4, .15);
            color: #c96004;
        }

        .est-estado-badge.otro {
            background: rgba(108, 117, 125, .15);
            color: #6c757d;
        }

        .est-modulos-list {
            padding: 1rem 1.5rem;
        }

        .est-modulo-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .85rem 0;
            border-bottom: 1px solid #f0f0f0;
            gap: 1rem;
            flex-wrap: wrap;
        }

        html[data-bs-theme="dark"] .est-modulo-item {
            border-color: #2c2e33;
        }

        .est-modulo-item:last-child {
            border-bottom: none;
        }

        .est-modulo-info {
            flex: 1;
            min-width: 0;
        }

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

        .est-modulo-meta span {
            display: flex;
            align-items: center;
            gap: .25rem;
        }

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

        html[data-bs-theme="dark"] .est-act-panel {
            border-color: #2c2e33;
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
            justify-content: space-between;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: .6rem .9rem;
            margin-bottom: .4rem;
            gap: .75rem;
        }

        html[data-bs-theme="dark"] .est-act-item {
            background: #1a1d21;
            border-color: #2c2e33;
        }

        .est-act-item-name {
            font-size: .85rem;
            font-weight: 600;
            flex: 1;
            min-width: 0;
        }

        .est-act-item-name small {
            display: block;
            font-size: .72rem;
            font-weight: 400;
            color: #6c757d;
        }

        .est-nota-badge {
            padding: .2rem .6rem;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .est-nota-badge.aprobado {
            background: rgba(90, 138, 48, .12);
            color: #5a8a30;
        }

        .est-nota-badge.reprobado {
            background: rgba(220, 53, 69, .12);
            color: #dc3545;
        }

        .est-nota-badge.pendiente {
            background: rgba(108, 117, 125, .1);
            color: #6c757d;
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

        html[data-bs-theme="dark"] .est-label-content {
            border-color: #2c2e33;
        }

        .est-label-content:last-child {
            border-bottom: none;
        }

        .est-label-content img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin: .35rem 0;
            display: block;
        }

        .est-label-content p {
            margin-bottom: .5rem;
        }

        .est-label-content p:last-child {
            margin-bottom: 0;
        }

        .est-label-content ul,
        .est-label-content ol {
            padding-left: 1.5rem;
            margin-bottom: .5rem;
        }

        .est-label-content li {
            margin-bottom: .2rem;
        }

        .est-label-content a {
            color: #fc7b04;
            text-decoration: underline;
        }

        .est-label-content a:hover {
            color: #c96004;
        }

        .est-no-cuenta {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e9ecef;
            padding: 2.5rem;
            text-align: center;
        }

        html[data-bs-theme="dark"] .est-no-cuenta {
            background: #1a1d21;
            border-color: #2c2e33;
        }

        .est-no-cuenta i {
            font-size: 3rem;
            color: #adb5bd;
        }

        .est-no-cuenta h5 {
            margin: .75rem 0 .4rem;
            font-weight: 700;
        }

        .est-no-cuenta p {
            font-size: .875rem;
            color: #6c757d;
            max-width: 380px;
            margin: 0 auto;
        }

        .text-orange {
            color: #fc7b04;
        }

        /* ── Responsive ───────────────────────────────────────────── */
        @media (max-width: 991px) {
            .est-ci-body {
                grid-template-columns: 180px 1fr;
                grid-template-rows: auto auto;
            }

            .est-ci-right {
                grid-column: 1 / -1;
                border-top: 1.5px solid var(--est-border);
            }
        }

        @media (max-width: 767px) {
            .est-tabs-body {
                padding: 16px;
            }

            .est-tab-btn {
                padding: 12px 14px;
                font-size: .78rem;
            }
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

    .docente-modulos-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

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

    .docente-modulos-header-text span {
        font-size: .72rem;
        color: rgba(255, 255, 255, 0.6);
    }

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

    .docente-modulos-body {
        padding: 20px;
    }

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

    .docente-modulo-card:last-child {
        margin-bottom: 0;
    }

    .docente-modulo-color-bar {
        width: 6px;
        flex-shrink: 0;
    }

    .docente-modulo-content {
        flex: 1;
        display: flex;
        align-items: center;
        padding: 16px 20px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .docente-modulo-main {
        flex: 1;
        min-width: 200px;
    }

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

    .docente-modulo-meta {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

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

    .docente-modulo-meta-item i {
        color: #fc7b04;
        font-size: 1rem;
    }

    .docente-modulo-meta-item strong {
        color: #1e293b;
        font-weight: 700;
    }

    .docente-modulo-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

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

    .docente-empty-modules i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 12px;
        display: block;
    }

    .docente-empty-modules h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .docente-empty-modules p {
        font-size: .85rem;
        color: #64748b;
        margin: 0;
    }

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

    .calendario-docente-title {
        display: flex;
        align-items: center;
        gap: 14px;
    }

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

    .calendario-docente-title-text span {
        font-size: .78rem;
        color: rgba(255, 255, 255, 0.6);
    }

    .calendario-docente-stats {
        display: flex;
        gap: 16px;
    }

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

    .calendario-docente-stat-info {
        text-align: left;
    }

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

    .calendario-docente-body {
        padding: 20px;
    }

    .calendario-docente-body .fc {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .calendario-docente-body .fc-toolbar {
        gap: 16px;
        flex-wrap: wrap;
    }

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

    .calendario-docente-body .fc-daygrid-day-number {
        font-size: .85rem;
        color: #475569;
        padding: 8px;
    }

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

    .calendario-docente-body .fc-day-today {
        background: rgba(252, 123, 4, 0.06) !important;
    }

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

    .calendario-docente-body .fc-scrollgrid {
        border-radius: 12px;
        overflow: hidden;
    }

    .calendario-docente-body .fc-scrollgrid td {
        border-color: #e2e8f0 !important;
    }

    .calendario-docente-body .fc-scrollgrid th {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }

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

    .calendario-docente-empty i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 16px;
        display: block;
    }

    .calendario-docente-empty h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .calendario-docente-empty p {
        font-size: .9rem;
        color: #64748b;
        margin: 0;
    }

    @media (max-width: 767px) {
        .docente-modulo-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .docente-modulo-meta {
            width: 100%;
        }
        
        .docente-modulo-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .calendario-docente-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .calendario-docente-stats {
            width: 100%;
            justify-content: flex-start;
        }
    }

    @media (max-width: 575px) {
        .pagos-stats-row {
            grid-template-columns: 1fr;
        }
    }

    /* FullCalendar Spanish locale overrides */
    .fc .fc-button-hoy,
    .fc .fc-button-hoy:hover {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
    }

    .fc .fc-button-mes,
    .fc .fc-button-semana,
    .fc .fc-button-lista {
        text-transform: capitalize;
    }

    /* ── Tab Pagos Mejorado ─────────────────────────────────────── */
    .pagos-stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .pagos-stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all .25s ease;
    }

    .pagos-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .pagos-stat-card.verificado {
        border-left: 4px solid #22c55e;
    }

    .pagos-stat-card.pendiente {
        border-left: 4px solid #f59e0b;
    }

    .pagos-stat-card.rechazado {
        border-left: 4px solid #ef4444;
    }

    .pagos-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .pagos-stat-icon.verificado {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.05) 100%);
        color: #22c55e;
    }

    .pagos-stat-icon.pendiente {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.05) 100%);
        color: #f59e0b;
    }

    .pagos-stat-icon.rechazado {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.05) 100%);
        color: #ef4444;
    }

    .pagos-stat-info {
        flex: 1;
    }

    .pagos-stat-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.8rem;
        color: #1e293b;
        line-height: 1;
    }

    .pagos-stat-label {
        font-size: .8rem;
        color: #64748b;
        margin-top: 4px;
    }

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
        display: flex;
        gap: 8px;
        overflow-x: auto;
    }

    .pagos-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        font-size: .85rem;
        font-weight: 600;
        color: #64748b;
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        white-space: nowrap;
        transition: all .2s ease;
    }

    .pagos-tab-btn:hover {
        border-color: #fc7b04;
        color: #fc7b04;
    }

    .pagos-tab-btn.active {
        background: linear-gradient(135deg, #fc7b04 0%, #e67300 100%);
        border-color: #fc7b04;
        color: #fff;
        box-shadow: 0 4px 12px rgba(252, 123, 4, 0.3);
    }

    .pagos-oferta-content {
        display: none;
        padding: 20px;
    }

    .pagos-oferta-content.active {
        display: block;
    }

    .pagos-section-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .pagos-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid #e2e8f0;
    }

    .pagos-section-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pagos-section-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .pagos-section-icon.cuotas {
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.15) 0%, rgba(252, 123, 4, 0.05) 100%);
        color: #fc7b04;
    }

    .pagos-section-icon.comprobantes {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0.05) 100%);
        color: #6366f1;
    }

    .pagos-section-title-text h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: .95rem;
        color: #1e293b;
        margin: 0;
    }

    .pagos-section-title-text span {
        font-size: .72rem;
        color: #64748b;
    }

    .pagos-btn-subir {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: linear-gradient(135deg, #fc7b04 0%, #e67300 100%);
        border: none;
        border-radius: 10px;
        color: #fff;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .25s;
        box-shadow: 0 4px 12px rgba(252, 123, 4, 0.25);
    }

    .pagos-btn-subir:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(252, 123, 4, 0.35);
    }

    .pagos-btn-al-dia {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.05) 100%);
        border-radius: 20px;
        color: #16a34a;
        font-size: .8rem;
        font-weight: 600;
    }

    .pagos-table-wrapper {
        overflow-x: auto;
        padding: 0 4px;
    }

    .pagos-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
    }

    .pagos-table thead th {
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 16px 20px;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0;
        text-align: left;
        white-space: nowrap;
    }

    .pagos-table thead th:first-child {
        border-radius: 12px 0 0 0;
    }

    .pagos-table thead th:last-child {
        border-radius: 0 12px 0 0;
    }

    .pagos-table tbody tr {
        transition: all .2s ease;
    }

    .pagos-table tbody td {
        padding: 16px 20px;
        font-size: .85rem;
        color: #475569;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .pagos-table tbody tr:first-child td:first-child {
        border-radius: 12px 0 0 0;
    }

    .pagos-table tbody tr:first-child td:last-child {
        border-radius: 0 12px 0 0;
    }

    .pagos-table tbody tr:last-child td:first-child {
        border-radius: 0 0 0 12px;
    }

    .pagos-table tbody tr:last-child td:last-child {
        border-radius: 0 0 12px 0;
    }

    .pagos-table tbody tr:hover {
        background: linear-gradient(90deg, rgba(252, 123, 4, 0.03) 0%, rgba(252, 123, 4, 0.06) 100%);
    }

    .pagos-table tbody tr:hover td {
        color: #1e293b;
    }

    .pagos-table .num-cuota {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: .9rem;
        color: #1e293b;
        background: #f8fafc;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }

    .pagos-table .monto-cell {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: #1e293b;
    }

    .pagos-table .fecha-cell {
        color: #64748b;
        font-size: .8rem;
    }

    .pagos-cuota-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 24px;
        font-size: .72rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .pagos-cuota-badge.pagado {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.08) 100%);
        color: #16a34a;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .pagos-cuota-badge.pendiente {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.08) 100%);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .pagos-cuota-badge.vencido {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.08) 100%);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .pagos-comp-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 24px;
        font-size: .72rem;
        font-weight: 700;
    }

    .pagos-comp-badge.verificado {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.08) 100%);
        color: #16a34a;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .pagos-comp-badge.revision {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.08) 100%);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .pagos-comp-badge.rechazado {
        background: #fee2e2;
        color: #dc2626;
    }

    .pagos-comp-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #fc7b04;
        font-size: .8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
    }

    .pagos-comp-link:hover {
        color: #c96004;
    }

    .pagos-cuota-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .pagos-cuota-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.12) 0%, rgba(252, 123, 4, 0.06) 100%);
        color: #c96004;
        border-radius: 8px;
        font-size: .72rem;
        font-weight: 600;
        border: 1px solid rgba(252, 123, 4, 0.15);
        transition: all .2s;
    }

    .pagos-cuota-tag:hover {
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.18) 0%, rgba(252, 123, 4, 0.1) 100%);
    }

    .pagos-comp-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #fc7b04;
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none;
        padding: 8px 14px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.08) 0%, rgba(252, 123, 4, 0.04) 100%);
        border: 1px solid rgba(252, 123, 4, 0.15);
        transition: all .25s;
    }

    .pagos-comp-link:hover {
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.15) 0%, rgba(252, 123, 4, 0.08) 100%);
        transform: translateY(-1px);
        color: #c96004;
    }

    .pagos-comp-link i {
        font-size: .95rem;
    }

    .pagos-empty {
        text-align: center;
        padding: 40px 20px;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .pagos-empty i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 16px;
        display: block;
    }

    .pagos-empty p {
        color: #64748b;
        font-size: .9rem;
    }

    @media (max-width: 767px) {
        .pagos-stats-row {
            grid-template-columns: 1fr;
        }
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

    .detalle-logo img {
        width: 48px;
    }

    .detalle-logo-text {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: .9rem;
        color: #1e293b;
    }

    .detalle-logo-sub {
        font-size: .7rem;
        color: #64748b;
    }

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

    .detalle-meta {
        display: flex;
        gap: 20px;
        font-size: .82rem;
        color: #64748b;
    }

    .detalle-meta strong {
        color: #475569;
    }

    .detalle-info-section {
        margin-bottom: 20px;
    }

    .detalle-info-section h6 {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #94a3b8;
        margin-bottom: 10px;
    }

    .detalle-info-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

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

    .detalle-tabla table {
        margin: 0;
        font-size: .85rem;
    }

    .detalle-tabla thead {
        background: #f8fafc;
    }

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

    .modal-detalle-pago .btn-comprobante:hover {
        transform: translateY(-2px);
    }

    .modal-detalle-pago .btn-cerrar {
        padding: 12px 20px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        background: #fff;
        color: #475569;
        font-weight: 600;
        transition: all .2s;
    }

    .modal-detalle-pago .btn-cerrar:hover {
        background: #f1f5f9;
    }

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

    .pago-list-container .list-group-item.active .text-muted {
        color: rgba(255,255,255,0.8) !important;
    }

    /* ── Modal Comprobante ───────────────────────────────────────── */
    .modal-comprobante .modal-body {
        padding: 24px;
    }

    .comprobante-info-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 20px;
        border-left: 4px solid #fc7b04;
    }

    .comprobante-info-card .programa {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: .95rem;
        color: #1e293b;
    }

    .comprobante-info-card .plan {
        font-size: .8rem;
        color: #64748b;
        margin-top: 4px;
    }

    .comprobante-file-area {
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        padding: 32px;
        text-align: center;
        background: #fafbfc;
        transition: all .25s ease;
        cursor: pointer;
        margin-bottom: 20px;
    }

    .comprobante-file-area:hover {
        border-color: #fc7b04;
        background: rgba(252, 123, 4, 0.03);
    }

    .comprobante-file-area.has-file {
        border-color: #22c55e;
        background: rgba(34, 197, 94, 0.03);
    }

    .comprobante-file-area i {
        font-size: 2.5rem;
        color: #cbd5e1;
        margin-bottom: 12px;
        display: block;
    }

    .comprobante-file-area.has-file i {
        color: #22c55e;
    }

    .comprobante-file-area span {
        font-size: .85rem;
        color: #64748b;
    }

    .comprobante-file-area small {
        display: block;
        font-size: .72rem;
        color: #94a3b8;
        margin-top: 8px;
    }

    .comprobante-label {
        font-size: .8rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 8px;
        display: block;
    }

    .comprobante-textarea {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        font-size: .85rem;
        width: 100%;
        background: #f8fafc;
        transition: all .2s;
        resize: vertical;
    }

    .comprobante-textarea:focus {
        border-color: #fc7b04;
        box-shadow: 0 0 0 3px rgba(252, 123, 4, 0.1);
        outline: none;
        background: #fff;
    }

    .comprobante-cuotas-container {
        display: grid;
        gap: 10px;
    }

    .modal-comprobante .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 16px 24px;
        background: #f8fafc;
    }

    .modal-comprobante .btn-cancelar {
        padding: 12px 24px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        background: #fff;
        color: #475569;
        font-weight: 600;
        transition: all .2s;
    }

    .modal-comprobante .btn-cancelar:hover {
        border-color: #cbd5e1;
        background: #f1f5f9;
    }

    .modal-comprobante .btn-enviar {
        padding: 12px 24px;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #fc7b04 0%, #e67300 100%);
        color: #fff;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all .25s;
        box-shadow: 0 4px 12px rgba(252, 123, 4, 0.3);
    }

    .modal-comprobante .btn-enviar:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(252, 123, 4, 0.4);
    }

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
        background: linear-gradient(180deg, #fafbfc 0%, #f1f5f9 100%);
        border-right: 1px solid #e2e8f0;
        padding: 20px;
        height: 100%;
        min-height: 600px;
    }

    .cronograma-header-strip {
        background: linear-gradient(135deg, #1a1d21 0%, #2c3036 100%);
        padding: 16px 20px;
        margin: -20px -20px 20px -20px;
        color: #fff;
    }

    .cronograma-header-strip h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: .95rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cronograma-header-strip i {
        color: #fc7b04;
    }

    .cronograma-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: .85rem;
        font-weight: 500;
        color: #1e293b;
        background: #fff;
        transition: all .2s ease;
        cursor: pointer;
        width: 100%;
        margin-bottom: 12px;
    }

    .cronograma-select:hover {
        border-color: #fc7b04;
    }

    .cronograma-select:focus {
        border-color: #fc7b04;
        box-shadow: 0 0 0 3px rgba(252, 123, 4, 0.1);
        outline: none;
    }

    .cronograma-btn-all {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 14px 18px;
        background: linear-gradient(135deg, #fc7b04 0%, #e67300 100%);
        border: none;
        border-radius: 12px;
        font-size: .85rem;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        transition: all .25s ease;
        margin-bottom: 16px;
        box-shadow: 0 4px 12px rgba(252, 123, 4, 0.25);
    }

    .cronograma-btn-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(252, 123, 4, 0.35);
    }

    .cronograma-btn-all.active {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        box-shadow: 0 4px 12px rgba(30, 41, 59, 0.3);
    }

    .cronograma-modulo-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all .25s ease;
        position: relative;
        overflow: hidden;
    }

    .cronograma-modulo-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--mod-color, #6366f1);
        border-radius: 4px 0 0 14px;
        transition: all .25s ease;
    }

    .cronograma-modulo-card:hover {
        border-color: var(--mod-color, #fc7b04);
        transform: translateX(4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }

    .cronograma-modulo-card:hover::before {
        width: 6px;
    }

    .cronograma-modulo-card.active {
        border-color: var(--mod-color, #fc7b04);
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.05) 0%, rgba(252, 123, 4, 0.02) 100%);
    }

    .cronograma-modulo-card.active::before {
        width: 6px;
    }

    .cronograma-modulo-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .cronograma-modulo-info {
        flex: 1;
        min-width: 0;
    }

    .cronograma-modulo-num {
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #94a3b8;
        margin-bottom: 2px;
    }

    .cronograma-modulo-name {
        font-size: .85rem;
        font-weight: 600;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cronograma-modulo-docente {
        font-size: .7rem;
        color: #64748b;
        margin-top: 2px;
    }

    .cronograma-modulo-badge {
        font-size: .6rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
        background: #f1f5f9;
        color: #64748b;
        white-space: nowrap;
    }

    .cronograma-main {
        padding: 24px;
        background: #fff;
    }

    .cronograma-title-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .cronograma-title-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cronograma-title-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.15) 0%, rgba(252, 123, 4, 0.05) 100%);
        color: #fc7b04;
        font-size: 1.3rem;
    }

    .cronograma-title-text h4 {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        color: #1e293b;
        margin: 0;
    }

    .cronograma-title-text span {
        font-size: .75rem;
        color: #64748b;
    }

    .cronograma-filter-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.12) 0%, rgba(252, 123, 4, 0.06) 100%);
        border: 1.5px solid #fc7b04;
        border-radius: 24px;
        font-size: .82rem;
        font-weight: 600;
        color: #c96004;
        animation: fadeInScale 0.3s ease;
    }

    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    .cronograma-filter-badge .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .cronograma-filter-badge button {
        background: none;
        border: none;
        padding: 0;
        color: #c96004;
        cursor: pointer;
        display: flex;
        align-items: center;
        margin-left: 4px;
        transition: transform .2s;
    }

    .cronograma-filter-badge button:hover {
        transform: scale(1.2);
    }

    .cronograma-stats {
        display: flex;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .cronograma-stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 20px;
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        transition: all .25s ease;
        min-width: 150px;
    }

    .cronograma-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .cronograma-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .cronograma-stat-icon.orange {
        background: linear-gradient(135deg, rgba(252, 123, 4, 0.15) 0%, rgba(252, 123, 4, 0.05) 100%);
        color: #fc7b04;
    }

    .cronograma-stat-icon.green {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.05) 100%);
        color: #16a34a;
    }

    .cronograma-stat-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.4rem;
        color: #1e293b;
        line-height: 1;
    }

    .cronograma-stat-label {
        font-size: .72rem;
        color: #64748b;
        margin-top: 4px;
    }

    .cronograma-calendar-wrapper {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 20px;
    }

    .cronograma-calendar-wrapper .fc {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .cronograma-calendar-wrapper .fc-toolbar {
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .cronograma-calendar-wrapper .fc-toolbar-title {
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 1.3rem !important;
        color: #1e293b;
    }

    .cronograma-calendar-wrapper .fc-button {
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

    .cronograma-calendar-wrapper .fc-button:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(252, 123, 4, 0.35) !important;
        filter: brightness(1.05);
    }

    .cronograma-calendar-wrapper .fc-button-active {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
        box-shadow: 0 4px 12px rgba(30, 41, 59, 0.3) !important;
    }

    .cronograma-calendar-wrapper .fc-daygrid-day-number,
    .cronograma-calendar-wrapper .fc-col-header-cell-cushion {
        font-weight: 600;
        text-decoration: none;
    }

    .cronograma-calendar-wrapper .fc-daygrid-day-number {
        font-size: .85rem;
        color: #475569;
        padding: 8px;
    }

    .cronograma-calendar-wrapper .fc-col-header-cell-cushion {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #64748b;
        padding: 12px 8px;
    }

    .cronograma-calendar-wrapper .fc-event {
        border-radius: 8px !important;
        padding: 6px 10px !important;
        font-weight: 600 !important;
        font-size: .78rem !important;
        cursor: pointer !important;
        transition: all .2s ease !important;
        border: none !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .cronograma-calendar-wrapper .fc-event:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .cronograma-calendar-wrapper .fc-day-today {
        background: rgba(252, 123, 4, 0.06) !important;
    }

    .cronograma-calendar-wrapper .fc-day-today .fc-daygrid-day-number {
        background: #fc7b04;
        color: #fff !important;
        border-radius: 10px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .cronograma-calendar-wrapper .fc-scrollgrid {
        border-radius: 12px;
        overflow: hidden;
    }

    .cronograma-calendar-wrapper .fc-scrollgrid td {
        border-color: #e2e8f0 !important;
    }

    .cronograma-calendar-wrapper .fc-scrollgrid th {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }

    .cronograma-calendar-wrapper .fc-timegrid-slot-label-cushion {
        font-size: .72rem;
        color: #64748b;
        font-weight: 600;
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

    .session-toast-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .session-toast-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

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

    .session-toast-state.confirmado {
        background: #dcfce7;
        color: #16a34a;
    }

    .session-toast-state.postergado {
        background: #f1f5f9;
        color: #64748b;
    }

    .session-toast-body {
        display: grid;
        gap: 12px;
    }

    .session-toast-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

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

    .session-toast-text {
        font-size: .85rem;
        color: #475569;
    }

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

    .session-toast-close:hover {
        color: #1e293b;
        transform: scale(1.1);
    }
    </style>
@endsection

@section('content')

    {{-- ── HERO ──────────────────────────────────────────────────────── --}}
    <div class="est-hero">
        @if ($persona && $persona->fotografia)
            <img src="{{ url('images/personas/' . $persona->fotografia) }}" alt="Foto" class="est-hero-avatar"
                onerror="this.src='{{ URL::asset('build/images/users/avatar-1.jpg') }}'">
        @else
            <img src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" alt="Foto" class="est-hero-avatar">
        @endif
        <div style="flex:1;min-width:0;">
            <div class="est-hero-name">
                {{ $persona ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) : $user->name }}
            </div>
            @if ($persona)
                <div class="est-hero-sub">
                    <i class="ri-id-card-line"></i> {{ $persona->carnet }}
                    @if ($persona->correo)
                        &nbsp;·&nbsp;<i class="ri-mail-line"></i> {{ $persona->correo }}
                    @endif
                </div>
            @endif
            <div class="est-hero-badges">
                @if ($moodleUserId)
                    <span class="est-hero-badge"><i class="ri-graduation-cap-line"></i> Cuenta Moodle activa</span>
                @else
                    <span class="est-hero-badge sin"><i class="ri-close-circle-line"></i> Sin cuenta Moodle</span>
                @endif
                <span class="est-hero-badge"><i class="ri-checkbox-circle-line"></i> Sesión activa</span>
            </div>
            @if ($esEstudiante && $esDocente)
                <div class="perfil-selector">
                    <span style="font-size:.7rem;opacity:.7;font-weight:600;">Ver como:</span>
                    <button type="button" class="perfil-btn {{ $perfilActivo === 'estudiante' ? 'active' : '' }}"
                            onclick="cambiarPerfil('estudiante')">
                        <i class="ri-user-student-line"></i> Estudiante
                    </button>
                    <button type="button" class="perfil-btn {{ $perfilActivo === 'docente' ? 'active' : '' }}"
                            onclick="cambiarPerfil('docente')">
                        <i class="ri-user-settings-line"></i> Docente
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if ($esEstudiante)
    {{-- ── STATS BAR ESTUDIANTE ───────────────────────────────────────── --}}
    @php
        $totalProgramas = $inscripciones->count();
        $totalModulos = $inscripciones->sum(fn($i) => $i->moodleMatriculas->count());
        $activas = $inscripciones->whereIn('estado', ['Inscrito', 'Confirmado'])->count();
    @endphp
    <div class="est-stats" id="stats-estudiante" {!! $perfilActivo !== 'estudiante' ? 'style="display:none"' : '' !!}>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm orange"><i class="ri-book-open-line"></i></div>
            <div>
                <div class="est-stat-num">{{ $totalProgramas }}</div>
                <div class="est-stat-label">Programa(s)</div>
            </div>
        </div>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm blue"><i class="ri-stack-line"></i></div>
            <div>
                <div class="est-stat-num">{{ $totalModulos }}</div>
                <div class="est-stat-label">Módulo(s)</div>
            </div>
        </div>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm green"><i class="ri-check-double-line"></i></div>
            <div>
                <div class="est-stat-num">{{ $activas }}</div>
                <div class="est-stat-label">Inscripción(es) activa(s)</div>
            </div>
        </div>
    </div>
    @endif

    @if ($esDocente)
    @php
        $totalCursos = $modulosDocente->count();
        $totalSesiones = $modulosDocente->sum(fn($m) => $m->horarios->count());
    @endphp
    <div class="est-stats" id="stats-docente" {!! $perfilActivo !== 'docente' ? 'style="display:none"' : '' !!}>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm orange"><i class="ri-book-3-line"></i></div>
            <div>
                <div class="est-stat-num">{{ $totalCursos }}</div>
                <div class="est-stat-label">Curso(s)</div>
            </div>
        </div>
        <div class="est-stat-card-sm">
            <div class="est-stat-icon-sm blue"><i class="ri-calendar-event-line"></i></div>
            <div>
                <div class="est-stat-num">{{ $totalSesiones }}</div>
                <div class="est-stat-label">Sesión(es)</div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── TABS ──────────────────────────────────────────────────────── --}}
    <div class="est-tabs-card">

        @if ($esEstudiante)
        {{-- Navegación estudiante --}}
        <div class="est-tabs-nav" id="nav-estudiante" {!! $perfilActivo !== 'estudiante' ? 'style="display:none"' : '' !!}>
            <button class="est-tab-btn active" onclick="switchTab(this,'tab-personal')">
                <i class="ri-user-3-line"></i> Personal
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-documentos')">
                <i class="ri-file-paper-line"></i> Documentos
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-academico')">
                <i class="ri-book-3-line"></i> Académico
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-contable')">
                <i class="ri-money-dollar-circle-line"></i> Contable
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-pagos')">
                <i class="ri-file-list-3-line"></i> Pagos
            </button>
            <button class="est-tab-btn" onclick="switchTab(this,'tab-cronograma')">
                <i class="ri-calendar-line"></i> Cronograma
            </button>
        </div>
        @endif

        @if ($esDocente)
        <div class="est-tabs-nav" id="nav-docente" {!! $perfilActivo !== 'docente' ? 'style="display:none"' : '' !!}>
            <button class="est-tab-btn active" onclick="switchTabDocente(this,'tab-personal-docente')">
                <i class="ri-user-3-line"></i> Personal
            </button>
            <button class="est-tab-btn" onclick="switchTabDocente(this,'tab-documentos-docente')">
                <i class="ri-file-paper-line"></i> Documentos
            </button>
            <button class="est-tab-btn" onclick="switchTabDocente(this,'tab-academico-docente')">
                <i class="ri-book-3-line"></i> Académico
            </button>
            <button class="est-tab-btn" onclick="switchTabDocente(this,'tab-horario-docente')">
                <i class="ri-calendar-check-line"></i> Mi Horario
            </button>
        </div>
        @endif

        @if ($esDocente)
        <div id="content-docente" {!! $perfilActivo !== 'docente' ? 'style="display:none"' : '' !!}>

        <div class="est-tabs-body active" id="tab-personal-docente">
            @php
                $tieneFotoDoc = $persona && $persona->fotografia && file_exists(public_path('images/personas/' . $persona->fotografia));
                $avatarUrlDoc = $tieneFotoDoc ? asset('images/personas/' . $persona->fotografia) : null;
                $nombreCompletoDoc = $persona
                    ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''))
                    : $user->name;
                $inicialesDoc = collect(explode(' ', $nombreCompletoDoc))->filter()->take(2)->map(fn($p) => strtoupper($p[0]))->implode('');
                $edadDoc = $persona && $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->age : null;
                $ubicacionDoc = $persona && $persona->ciudad
                    ? optional($persona->ciudad)->nombre . ', ' . (optional(optional($persona->ciudad)->departamento)->nombre ?? '')
                    : null;
                $docenteModel = $persona?->docente;
            @endphp
            <div class="est-ci-wrap">
                <div class="est-ci-stripe"></div>
                <div class="est-ci-body">
                    {{-- Izquierda: foto --}}
                    <div class="est-ci-left">
                        <div class="est-ci-foto-label"><i class="ri-building-2-line"></i><span>INNOVA CIENCIA</span></div>
                        <div class="est-ci-foto">
                            <img src="{{ $avatarUrlDoc ?? '' }}" alt="Foto" id="doc-ci-foto-img"
                                style="{{ $tieneFotoDoc ? '' : 'display:none;' }}"
                                onerror="this.style.display='none';document.getElementById('doc-ci-initials').style.display='flex';">
                            <div id="doc-ci-initials" class="est-ci-initials"
                                style="{{ $tieneFotoDoc ? 'display:none;' : '' }}">
                                {{ $inicialesDoc ?: '?' }}
                            </div>
                        </div>
                        <div class="est-ci-quick-data">
                            @if ($persona?->carnet)
                                <div class="est-ci-qd-item">
                                    <i class="ri-shield-check-line"></i>
                                    <span class="est-ci-qd-label">CI</span>
                                    <span class="est-ci-qd-val">{{ $persona->carnet }}{{ $persona->expedido ? ' ' . $persona->expedido : '' }}</span>
                                </div>
                            @endif
                            @if ($persona?->fecha_nacimiento)
                                <div class="est-ci-qd-item">
                                    <i class="ri-cake-line"></i>
                                    <span class="est-ci-qd-label">Nacimiento</span>
                                    <span class="est-ci-qd-val">{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }}</span>
                                </div>
                            @endif
                            @if ($edadDoc)
                                <div class="est-ci-qd-item">
                                    <i class="ri-user-line"></i>
                                    <span class="est-ci-qd-label">Edad</span>
                                    <span class="est-ci-qd-val">{{ $edadDoc }} años</span>
                                </div>
                            @endif
                            @if ($persona?->sexo)
                                <div class="est-ci-qd-item">
                                    <i class="ri-genderless-line"></i>
                                    <span class="est-ci-qd-label">Sexo</span>
                                    <span class="est-ci-qd-val">{{ $persona->sexo == 'M' ? 'Masculino' : ($persona->sexo == 'F' ? 'Femenino' : '—') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Centro: datos de contacto --}}
                    <div class="est-ci-center">
                        <div class="est-ci-nombre-wrap">
                            <div>
                                <div class="est-ci-nombre">{{ $nombreCompletoDoc }}</div>
                                <div class="est-ci-estado-label">Docente</div>
                            </div>
                            <span class="est-ci-estado-badge est-ci-badge-activo">
                                <i class="ri-checkbox-circle-line"></i> Activo
                            </span>
                        </div>
                        <div class="est-ci-section-title"><i class="ri-contacts-line"></i> Datos de Contacto</div>
                        <div class="est-ci-datos-grid">
                            <div class="est-ci-dato">
                                <span class="est-ci-label">Correo</span>
                                <span class="est-ci-value">{{ $persona?->correo ?? '—' }}</span>
                            </div>
                            <div class="est-ci-dato">
                                <span class="est-ci-label">Celular</span>
                                <span class="est-ci-value">{{ $persona?->celular ?? '—' }}</span>
                            </div>
                            <div class="est-ci-dato">
                                <span class="est-ci-label">Teléfono</span>
                                <span class="est-ci-value">{{ $persona?->telefono ?? '—' }}</span>
                            </div>
                            <div class="est-ci-dato">
                                <span class="est-ci-label">Estado Civil</span>
                                <span class="est-ci-value">{{ $persona?->estado_civil ?? '—' }}</span>
                            </div>
                            <div class="est-ci-dato est-ci-full">
                                <span class="est-ci-label">Ciudad / Departamento</span>
                                <span class="est-ci-value">{{ $ubicacionDoc ?? '—' }}</span>
                            </div>
                            <div class="est-ci-dato est-ci-full">
                                <span class="est-ci-label">Dirección</span>
                                <span class="est-ci-value">{{ $persona?->direccion ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Derecha: datos del docente --}}
                    <div class="est-ci-right">
                        <div class="est-ci-right-header">
                            <i class="ri-user-star-line"></i><span>Datos del Docente</span>
                        </div>
                        <div class="est-ci-account-list">
                            @if ($docenteModel?->created_at)
                                <div class="est-ci-acc-item">
                                    <div class="est-ci-acc-icon"><i class="ri-calendar-check-line"></i></div>
                                    <div>
                                        <div class="est-ci-acc-label">Registro</div>
                                        <div class="est-ci-acc-value">{{ $docenteModel->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            @endif
                            <div class="est-ci-acc-item">
                                <div class="est-ci-acc-icon"><i class="ri-book-3-line"></i></div>
                                <div>
                                    <div class="est-ci-acc-label">Módulos asignados</div>
                                    <div class="est-ci-acc-value">{{ $modulosDocente->count() }} módulo(s)</div>
                                </div>
                            </div>
                            <div class="est-ci-acc-item">
                                <div class="est-ci-acc-icon"><i class="ri-time-line"></i></div>
                                <div>
                                    <div class="est-ci-acc-label">Total de sesiones</div>
                                    <div class="est-ci-acc-value">{{ $modulosDocente->sum(fn($m) => $m->horarios->count()) }} sesión(es)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="est-ci-bottom-bar">
                    <span><i class="ri-id-card-line"></i> Carnet de Identificación · Docente</span>
                    <span>{{ now()->format('Y') }}</span>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TAB DOCUMENTOS DOCENTE
        ══════════════════════════════════════════════════════════ --}}
        <div class="est-tabs-body" id="tab-documentos-docente">
            @php
                $estadoDocDoc = function ($archivo, $verificado) {
                    if (!$archivo) {
                        return ['label' => 'Pendiente', 'cls' => 'pending', 'icon' => 'ri-add-circle-line'];
                    }
                    if ($verificado) {
                        return ['label' => 'Aprobado', 'cls' => 'approved', 'icon' => 'ri-checkbox-circle-fill'];
                    }
                    return ['label' => 'En revisión', 'cls' => 'review', 'icon' => 'ri-time-line'];
                };
                $docsIdentidadDoc = [
                    [
                        'nombre'    => 'Carnet de Identidad',
                        'icono'     => 'ri-id-card-line',
                        'archivo'   => $persona->fotografia_carnet ?? null,
                        'verificado'=> $persona->carnet_verificado ?? false,
                        'tipo'      => 'fotografia_carnet',
                    ],
                    [
                        'nombre'    => 'Cert. Nacimiento',
                        'icono'     => 'ri-file-paper-line',
                        'archivo'   => $persona->fotografia_certificado_nacimiento ?? null,
                        'verificado'=> $persona->certificado_nacimiento_verificado ?? false,
                        'tipo'      => 'fotografia_certificado_nacimiento',
                    ],
                ];
                $totalDocsDoc = count($docsIdentidadDoc);
                $verificadosDoc = 0;
                foreach ($docsIdentidadDoc as $d) {
                    if ($d['verificado']) $verificadosDoc++;
                }
                $estudioDocente = $persona?->estudios?->first();
                if ($estudioDocente) {
                    $totalDocsDoc += 2;
                    if ($estudioDocente->documento_academico_verificado) $verificadosDoc++;
                    if ($estudioDocente->documento_provision_verificado) $verificadosDoc++;
                }
                $pctDocsDoc = $totalDocsDoc > 0 ? ($verificadosDoc / $totalDocsDoc) * 100 : 0;
            @endphp

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:16px;">
                <h3 style="margin:0;font-size:1.1rem;font-weight:600;display:flex;align-items:center;gap:8px;">
                    <i class="ri-folder-shield-line" style="color:#fc7b04;"></i> Documentación
                </h3>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="flex:1;max-width:150px;height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                        <div style="height:100%;background:linear-gradient(90deg,#fc7b04,#f97316);border-radius:4px;width:{{ $pctDocsDoc }}%;transition:width .3s;"></div>
                    </div>
                    <span style="font-size:.875rem;font-weight:700;color:#fc7b04;">{{ number_format($pctDocsDoc, 0) }}%</span>
                </div>
            </div>

            <h4 style="font-size:1rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="ri-id-card-line" style="color:#fc7b04;"></i> Documentación Personal
            </h4>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:32px;">
                @foreach ($docsIdentidadDoc as $doc)
                    @php
                        $estado = $estadoDocDoc($doc['archivo'], $doc['verificado']);
                        $bgIcon   = $estado['cls'] == 'approved' ? '#dcfce7' : ($estado['cls'] == 'review' ? '#e0f2fe' : '#fef3c7');
                        $colorIcon= $estado['cls'] == 'approved' ? '#16a34a' : ($estado['cls'] == 'review' ? '#0891b2' : '#d97706');
                    @endphp
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                        <div style="padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                            <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:{{ $bgIcon }};color:{{ $colorIcon }};flex-shrink:0;">
                                <i class="{{ $doc['icono'] }}"></i>
                            </div>
                            <div style="flex:1;min-width:0;font-size:.875rem;font-weight:600;">{{ $doc['nombre'] }}</div>
                            <span style="padding:4px 10px;border-radius:20px;font-size:.6875rem;font-weight:600;text-transform:uppercase;background:{{ $bgIcon }};color:{{ $colorIcon }};">
                                {{ $estado['label'] }}
                            </span>
                        </div>
                        <div style="padding:16px;">
                            @if ($doc['archivo'])
                                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:8px;">
                                    <div style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.1rem;">
                                        <i class="ri-file-pdf-fill"></i>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-size:.8125rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $doc['tipo'] }}.pdf</div>
                                        <div style="font-size:.6875rem;display:flex;align-items:center;gap:4px;color:{{ $doc['verificado'] ? '#16a34a' : '#d97706' }};">
                                            @if ($doc['verificado'])
                                                <i class="ri-shield-check-fill"></i> Verificado
                                            @else
                                                <i class="ri-time-fill"></i> En revisión
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div style="text-align:center;padding:20px;color:#94a3b8;font-size:.85rem;">
                                    <i class="ri-file-unknown-line" style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                    Documento no subido
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <h4 style="font-size:1rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="ri-graduation-cap-line" style="color:#fc7b04;"></i> Formación Académica
            </h4>
            @if ($estudioDocente)
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:#e0f2fe;color:#0891b2;">
                            <i class="ri-school-line"></i>
                        </div>
                        <div>
                            <div style="font-size:.875rem;font-weight:600;">{{ $estudioDocente->grado_academico->nombre ?? 'Sin grado' }}</div>
                            <div style="font-size:.75rem;color:#64748b;">{{ $estudioDocente->profesion->nombre ?? 'Sin profesión' }} | {{ $estudioDocente->estado ?? '—' }}</div>
                        </div>
                        <span class="ms-auto badge" style="background:#dcfce7;color:#16a34a;font-size:.6875rem;padding:4px 10px;border-radius:20px;">Principal</span>
                    </div>
                    @if ($estudioDocente->universidad)
                        <div style="font-size:.8125rem;color:#64748b;border-top:1px solid #e2e8f0;padding-top:12px;">
                            <i class="ri-building-line me-1"></i> {{ $estudioDocente->universidad->nombre }}
                        </div>
                    @endif
                </div>
                @php
                    $docsAcademicoDoc = [
                        [
                            'nombre'    => 'Título/Bachiller',
                            'icono'     => 'ri-graduation-cap-line',
                            'archivo'   => $estudioDocente->documento_academico,
                            'verificado'=> $estudioDocente->documento_academico_verificado,
                            'tipo'      => 'documento_academico',
                        ],
                        [
                            'nombre'    => 'Provisión Nacional',
                            'icono'     => 'ri-government-line',
                            'archivo'   => $estudioDocente->documento_provision_nacional,
                            'verificado'=> $estudioDocente->documento_provision_verificado,
                            'tipo'      => 'documento_provision_nacional',
                        ],
                    ];
                @endphp
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;">
                    @foreach ($docsAcademicoDoc as $doc)
                        @php
                            $estado = $estadoDocDoc($doc['archivo'], $doc['verificado']);
                            $bgIcon   = $estado['cls'] == 'approved' ? '#dcfce7' : ($estado['cls'] == 'review' ? '#e0f2fe' : '#fef3c7');
                            $colorIcon= $estado['cls'] == 'approved' ? '#16a34a' : ($estado['cls'] == 'review' ? '#0891b2' : '#d97706');
                        @endphp
                        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                            <div style="padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                                <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:{{ $bgIcon }};color:{{ $colorIcon }};flex-shrink:0;">
                                    <i class="{{ $doc['icono'] }}"></i>
                                </div>
                                <div style="flex:1;min-width:0;font-size:.875rem;font-weight:600;">{{ $doc['nombre'] }}</div>
                                <span style="padding:4px 10px;border-radius:20px;font-size:.6875rem;font-weight:600;text-transform:uppercase;background:{{ $bgIcon }};color:{{ $colorIcon }};">
                                    {{ $estado['label'] }}
                                </span>
                            </div>
                            <div style="padding:16px;">
                                @if ($doc['archivo'])
                                    <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:8px;">
                                        <div style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.1rem;">
                                            <i class="ri-file-pdf-fill"></i>
                                        </div>
                                        <div style="flex:1;min-width:0;">
                                            <div style="font-size:.8125rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $doc['tipo'] }}.pdf</div>
                                            <div style="font-size:.6875rem;display:flex;align-items:center;gap:4px;color:{{ $doc['verificado'] ? '#16a34a' : '#d97706' }};">
                                                @if ($doc['verificado'])
                                                    <i class="ri-shield-check-fill"></i> Verificado
                                                @else
                                                    <i class="ri-time-fill"></i> En revisión
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div style="text-align:center;padding:20px;color:#94a3b8;font-size:.85rem;">
                                        <i class="ri-file-unknown-line" style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                        Documento no subido
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center;padding:40px 20px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;">
                    <i class="ri-user-unfollow-line" style="font-size:2.5rem;color:#94a3b8;opacity:.5;"></i>
                    <p style="margin:16px 0 0;color:#64748b;">Sin registro académico registrado</p>
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TAB ACADÉMICO DOCENTE
        ══════════════════════════════════════════════════════════ --}}
        <div class="est-tabs-body" id="tab-academico-docente">
            <p class="est-section-title"><i class="ri-book-line"></i> Mis Módulos</p>

            @if ($modulosDocente->isEmpty())
                <div class="est-no-cuenta">
                    <i class="ri-book-open-line"></i>
                    <h5>Sin módulos asignados</h5>
                    <p>Aún no tienes módulos asignados como docente. Contacta con administración para más información.</p>
                </div>
            @else
                @php
                    $modulosPorOferta = $modulosDocente->groupBy('ofertas_academica_id');
                @endphp
                <div class="est-oferta-tabs-nav">
                    @foreach ($modulosPorOferta as $ofertaId => $mods)
                        @php
                            $primerMod = $mods->first();
                            $nombreOferta = $primerMod->ofertaAcademica?->programa?->nombre
                                ?? $primerMod->ofertaAcademica?->posgrado?->nombre
                                ?? 'Oferta #' . $ofertaId;
                        @endphp
                        <button type="button"
                            class="est-oferta-tab-btn {{ $loop->first ? 'active' : '' }}"
                            data-target="doc-oferta-{{ $loop->index }}">
                            <i class="ri-book-2-line"></i> {{ $nombreOferta }}
                        </button>
                    @endforeach
                </div>

                @foreach ($modulosPorOferta as $ofertaId => $mods)
                    @php
                        $primerMod   = $mods->first();
                        $oferta      = $primerMod->ofertaAcademica;
                        $nombreOferta= $oferta?->programa?->nombre ?? $oferta?->posgrado?->nombre ?? 'Oferta #' . $ofertaId;
                    @endphp
                    <div class="est-oferta-content {{ $loop->first ? 'active' : '' }}"
                        id="doc-oferta-{{ $loop->index }}">
                        <div class="est-prog-card">
                            <div class="est-prog-header">
                                <div>
                                    <h6>{{ $nombreOferta }}</h6>
                                    <small>{{ $oferta?->codigo ?? '' }}
                                        @if ($oferta?->fecha_inicio)
                                            · Inicio: {{ \Carbon\Carbon::parse($oferta->fecha_inicio)->format('d/m/Y') }}
                                        @endif
                                    </small>
                                </div>
                                <span class="est-estado-badge inscrito">Docente</span>
                            </div>

                            <div class="est-modulos-list">
                                @foreach ($mods->sortBy('n_modulo') as $modulo)
                                    <div class="est-modulo-item">
                                        <div class="est-modulo-info">
                                            <div class="est-modulo-name">
                                                <span style="background:{{ $modulo->color ?? '#6366f1' }};width:8px;height:8px;border-radius:50%;display:inline-block;margin-right:.4rem;"></span>
                                                Módulo {{ $modulo->n_modulo }}: {{ $modulo->nombre }}
                                            </div>
                                            <div class="est-modulo-meta">
                                                @if ($modulo->fecha_inicio)
                                                    <span><i class="ri-calendar-line"></i>
                                                        {{ \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') }}
                                                        @if ($modulo->fecha_fin)
                                                            — {{ \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y') }}
                                                        @endif
                                                    </span>
                                                @endif
                                                <span><i class="ri-time-line"></i> {{ $modulo->horarios->count() }} sesión(es)</span>
                                            </div>
                                        </div>
                                        <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                                            @if ($modulo->moodle_course_id)
                                                <a href="{{ route('virtual.moodle-sso', ['target' => config('moodle.url') . '/course/view.php?id=' . $modulo->moodle_course_id]) }}"
                                                   target="_blank" class="est-btn-actividades"
                                                   style="background:#5a8a30;" title="Abrir curso completo en Moodle">
                                                    <i class="ri-external-link-line"></i> Ir al curso
                                                </a>
                                            @else
                                                <span style="font-size:.72rem;color:#94a3b8;padding:.35rem .5rem;">Sin Moodle</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="est-tabs-body" id="tab-horario-docente">
            <div class="calendario-docente-wrapper">
                <div class="calendario-docente-header">
                    <div class="calendario-docente-title">
                        <div class="calendario-docente-title-icon">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                        <div class="calendario-docente-title-text">
                            <h3>Mi Horario</h3>
                            <span>Calendario de sesiones programadas</span>
                        </div>
                    </div>
                    <div class="calendario-docente-stats">
                        <div class="calendario-docente-stat">
                            <div class="calendario-docente-stat-icon courses">
                                <i class="ri-book-3-line"></i>
                            </div>
                            <div class="calendario-docente-stat-info">
                                <div class="calendario-docente-stat-value">{{ $modulosDocente->count() }}</div>
                                <div class="calendario-docente-stat-label">Módulo(s)</div>
                            </div>
                        </div>
                        <div class="calendario-docente-stat">
                            <div class="calendario-docente-stat-icon sessions">
                                <i class="ri-time-line"></i>
                            </div>
                            <div class="calendario-docente-stat-info">
                                <div class="calendario-docente-stat-value">{{ $modulosDocente->sum(fn($m) => $m->horarios->count()) }}</div>
                                <div class="calendario-docente-stat-label">Sesión(es)</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="calendario-docente-body">
                    @if ($modulosDocente->count() > 0)
                        <div id="calendar-docente"></div>
                    @else
                        <div class="calendario-docente-empty">
                            <i class="ri-calendar-line"></i>
                            <h5>Sin horarios programados</h5>
                            <p>No tienes sesiones programadas. Los horarios aparecerán aquí una vez que se te asignen.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @endif

        </div>{{-- /content-docente --}}
        <div id="content-estudiante" {!! $perfilActivo !== 'estudiante' ? 'style="display:none"' : '' !!}>
        @php
            $tieneFoto =
                $persona && $persona->fotografia && file_exists(public_path('images/personas/' . $persona->fotografia));
            $avatarUrl = $tieneFoto ? asset('images/personas/' . $persona->fotografia) : null;
            $nombreCompleto = $persona
                ? trim(
                    ($persona->nombres ?? '') .
                        ' ' .
                        ($persona->apellido_paterno ?? '') .
                        ' ' .
                        ($persona->apellido_materno ?? ''),
                )
                : 'Estudiante';
            $iniciales = collect(explode(' ', $nombreCompleto))
                ->filter()
                ->take(2)
                ->map(fn($p) => strtoupper($p[0]))
                ->implode('');
            $edad =
                $persona && $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->age : null;
            $ubicacion =
                $persona && $persona->ciudad
                    ? optional($persona->ciudad)->nombre .
                        ', ' .
                        (optional(optional($persona->ciudad)->departamento)->nombre ?? '')
                    : null;
            $estudio = $persona?->estudios?->first();
        @endphp
        <div class="est-tabs-body active" id="tab-personal">
            <div class="est-ci-wrap">
                <div class="est-ci-stripe"></div>
                <div class="est-ci-body">
                    {{-- Izquierda: foto --}}
                    <div class="est-ci-left">
                        <div class="est-ci-foto-label"><i class="ri-building-2-line"></i><span>INNOVA CIENCIA</span></div>
                        <div class="est-ci-foto">
                            <img src="{{ $avatarUrl ?? '' }}" alt="Foto" id="est-ci-foto-img"
                                style="{{ $tieneFoto ? '' : 'display:none;' }}"
                                onerror="this.style.display='none';document.getElementById('est-ci-initials').style.display='flex';">
                            <div id="est-ci-initials" class="est-ci-initials"
                                style="{{ $tieneFoto ? 'display:none;' : '' }}">
                                {{ $iniciales ?: '?' }}
                            </div>
                        </div>
                        <div class="est-ci-quick-data">
                            @if ($persona?->carnet)
                                <div class="est-ci-qd-item">
                                    <i class="ri-shield-check-line"></i>
                                    <span class="est-ci-qd-label">CI</span>
                                    <span
                                        class="est-ci-qd-val">{{ trim($persona->carnet . ($persona->expedido ? ' ' . trim($persona->expedido) : '')) }}</span>
                                </div>
                            @endif
                            @if ($persona?->fecha_nacimiento)
                                <div class="est-ci-qd-item">
                                    <i class="ri-cake-line"></i>
                                    <span class="est-ci-qd-label">Nacimiento</span>
                                    <span
                                        class="est-ci-qd-val">{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }}</span>
                                </div>
                            @endif
                            @if ($edad)
                                <div class="est-ci-qd-item">
                                    <i class="ri-user-line"></i>
                                    <span class="est-ci-qd-label">Edad</span>
                                    <span class="est-ci-qd-val">{{ $edad }} años</span>
                                </div>
                            @endif
                            @if ($persona?->sexo)
                                <div class="est-ci-qd-item">
                                    <i class="ri-genderless-line"></i>
                                    <span class="est-ci-qd-label">Sexo</span>
                                    <span
                                        class="est-ci-qd-val">{{ $persona->sexo == 'M' ? 'Masculino' : ($persona->sexo == 'F' ? 'Femenino' : '—') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Centro: datos de contacto --}}
                    <div class="est-ci-center">
                        <div class="est-ci-nombre-wrap">
                            <div>
                                <div class="est-ci-nombre">{{ $nombreCompleto }}</div>
                                <div class="est-ci-estado-label">Estudiante</div>
                            </div>
                            @if ($estudiante)
                                <span
                                    class="est-ci-estado-badge est-ci-badge-{{ ($estudiante->estado ?? 'Activo') === 'Activo' ? 'activo' : 'inactivo' }}">
                                    <i class="ri-checkbox-circle-line"></i>
                                    {{ $estudiante->estado ?? 'Activo' }}
                                </span>
                            @endif
                        </div>
                        <div class="est-ci-section-title"><i class="ri-contacts-line"></i> Datos de Contacto</div>
                        <div class="est-ci-datos-grid">
                            <div class="est-ci-dato">
                                <span class="est-ci-label">Correo</span>
                                <span class="est-ci-value">{{ $persona?->correo ?? '—' }}</span>
                            </div>
                            <div class="est-ci-dato">
                                <span class="est-ci-label">Celular</span>
                                <span class="est-ci-value">{{ $persona?->celular ?? '—' }}</span>
                            </div>
                            <div class="est-ci-dato">
                                <span class="est-ci-label">Teléfono</span>
                                <span class="est-ci-value">{{ $persona?->telefono ?? '—' }}</span>
                            </div>
                            <div class="est-ci-dato">
                                <span class="est-ci-label">Estado Civil</span>
                                <span class="est-ci-value">{{ $persona?->estado_civil ?? '—' }}</span>
                            </div>
                            <div class="est-ci-dato est-ci-full">
                                <span class="est-ci-label">Ciudad / Departamento</span>
                                <span class="est-ci-value">{{ $ubicacion ?? '—' }}</span>
                            </div>
                            <div class="est-ci-dato est-ci-full">
                                <span class="est-ci-label">Dirección</span>
                                <span class="est-ci-value">{{ $persona?->direccion ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Derecha: datos del estudiante --}}
                    <div class="est-ci-right">
                        <div class="est-ci-right-header">
                            <i class="ri-graduation-cap-line"></i><span>Datos del Estudiante</span>
                        </div>
                        <div class="est-ci-account-list">
                            @if ($estudio?->universidad)
                                <div class="est-ci-acc-item">
                                    <div class="est-ci-acc-icon"><i class="ri-building-4-line"></i></div>
                                    <div>
                                        <div class="est-ci-acc-label">Universidad</div>
                                        <div class="est-ci-acc-value">{{ $estudio->universidad->nombre ?? '—' }}</div>
                                    </div>
                                </div>
                            @endif
                            @if ($estudio?->profesion)
                                <div class="est-ci-acc-item">
                                    <div class="est-ci-acc-icon"><i class="ri-graduation-cap-line"></i></div>
                                    <div>
                                        <div class="est-ci-acc-label">Carrera / Programa</div>
                                        <div class="est-ci-acc-value">{{ $estudio->profesion->nombre ?? '—' }}</div>
                                    </div>
                                </div>
                            @endif
                            @if ($estudiante)
                                <div class="est-ci-acc-item">
                                    <div class="est-ci-acc-icon"><i class="ri-calendar-check-line"></i></div>
                                    <div>
                                        <div class="est-ci-acc-label">Inscripción</div>
                                        <div class="est-ci-acc-value">{{ $estudiante->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                <div class="est-ci-acc-item">
                                    <div class="est-ci-acc-icon"><i class="ri-vip-diamond-line"></i></div>
                                    <div>
                                        <div class="est-ci-acc-label">Estado</div>
                                        <div class="est-ci-acc-value">{{ $estudiante->estado ?? 'Activo' }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="est-ci-section-title mt-4">
                            <i class="ri-book-2-line"></i><span>Ofertas Académicas Inscritas</span>
                        </div>
                        <div class="est-ci-ofertas-list">
                            @forelse($inscripciones as $ins)
                                @php
                                    $nombreOferta = $ins->ofertaAcademica?->programa?->nombre ?? 
                                        ($ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta #' . $ins->ofertas_academica_id);
                                    $saldoPendiente = 0;
                                    foreach ($ins->cuotas as $cuota) {
                                        $pagado = $cuota->pagosCuota->sum('monto_bs');
                                        $pendiente = $cuota->monto_bs - $pagado;
                                        if ($pendiente > 0) {
                                            $saldoPendiente += $pendiente;
                                        }
                                    }
                                @endphp
                                <div class="est-ci-oferta-item">
                                    <div class="est-ci-oferta-name">{{ $nombreOferta }}</div>
                                    <div class="est-ci-oferta-details">
                                        <span class="est-ci-oferta-estado">{{ $ins->estado }}</span>
                                        <span class="est-ci-oferta-saldo {{ $saldoPendiente > 0 ? 'pendiente' : 'al-dia' }}">
                                            {{ $saldoPendiente > 0 ? 'Bs. ' . number_format($saldoPendiente, 2, ',', '.') : 'Al día' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted py-2">No hay ofertas académicas inscritas</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="est-ci-bottom-bar">
                    <span><i class="ri-id-card-line"></i> Carnet de Identificación</span>
                    <span>{{ now()->format('Y') }}</span>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TAB DOCUMENTOS (solo lectura)
        ══════════════════════════════════════════════════════════ --}}
        @php
            $estadoDoc = function ($archivo, $verificado) {
                if (!$archivo) {
                    return ['label' => 'Pendiente', 'cls' => 'pending', 'icon' => 'ri-add-circle-line'];
                }
                if ($verificado) {
                    return ['label' => 'Aprobado', 'cls' => 'approved', 'icon' => 'ri-checkbox-circle-fill'];
                }
                return ['label' => 'En revisión', 'cls' => 'review', 'icon' => 'ri-time-line'];
            };
            $docsIdentidad = [
                [
                    'nombre' => 'Carnet de Identidad',
                    'icono' => 'ri-id-card-line',
                    'archivo' => $persona->fotografia_carnet ?? null,
                    'verificado' => $persona->carnet_verificado ?? false,
                    'tipo' => 'fotografia_carnet',
                ],
                [
                    'nombre' => 'Cert. Nacimiento',
                    'icono' => 'ri-file-paper-line',
                    'archivo' => $persona->fotografia_certificado_nacimiento ?? null,
                    'verificado' => $persona->certificado_nacimiento_verificado ?? false,
                    'tipo' => 'fotografia_certificado_nacimiento',
                ],
            ];
            $totalDocs = count($docsIdentidad);
            $verificados = 0;
            foreach ($docsIdentidad as $d) {
                if ($d['verificado']) {
                    $verificados++;
                }
            }
            if ($estudioPrincipal) {
                $totalDocs += 2;
                if ($estudioPrincipal->documento_academico_verificado) {
                    $verificados++;
                }
                if ($estudioPrincipal->documento_provision_verificado) {
                    $verificados++;
                }
            }
            $pctDocs = $totalDocs > 0 ? ($verificados / $totalDocs) * 100 : 0;
        @endphp
        <div class="est-tabs-body" id="tab-documentos">
            <div
                style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:16px;">
                <h3 style="margin:0;font-size:1.1rem;font-weight:600;display:flex;align-items:center;gap:8px;">
                    <i class="ri-folder-shield-line" style="color:#fc7b04;"></i> Documentación
                </h3>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="flex:1;max-width:150px;height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                        <div
                            style="height:100%;background:linear-gradient(90deg,#fc7b04,#f97316);border-radius:4px;width:{{ $pctDocs }}%;transition:width .3s;">
                        </div>
                    </div>
                    <span
                        style="font-size:.875rem;font-weight:700;color:#fc7b04;">{{ number_format($pctDocs, 0) }}%</span>
                </div>
            </div>

            {{-- Identidad --}}
            <h4 style="font-size:1rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="ri-id-card-line" style="color:#fc7b04;"></i> Documentación Personal
            </h4>
            <div
                style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:32px;">
                @foreach ($docsIdentidad as $doc)
                    @php
                        $estado = $estadoDoc($doc['archivo'], $doc['verificado']);
                        $bgIcon =
                            $estado['cls'] == 'approved'
                                ? '#dcfce7'
                                : ($estado['cls'] == 'review'
                                    ? '#e0f2fe'
                                    : '#fef3c7');
                        $colorIcon =
                            $estado['cls'] == 'approved'
                                ? '#16a34a'
                                : ($estado['cls'] == 'review'
                                    ? '#0891b2'
                                    : '#d97706');
                    @endphp
                    <div
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                        <div
                            style="padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                            <div
                                style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:{{ $bgIcon }};color:{{ $colorIcon }};flex-shrink:0;">
                                <i class="{{ $doc['icono'] }}"></i>
                            </div>
                            <div style="flex:1;min-width:0;font-size:.875rem;font-weight:600;">{{ $doc['nombre'] }}</div>
                            <span
                                style="padding:4px 10px;border-radius:20px;font-size:.6875rem;font-weight:600;text-transform:uppercase;background:{{ $bgIcon }};color:{{ $colorIcon }};">
                                {{ $estado['label'] }}
                            </span>
                        </div>
                        <div style="padding:16px;">
                            @if ($doc['archivo'])
                                <div
                                    style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:8px;">
                                    <div
                                        style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.1rem;">
                                        <i class="ri-file-pdf-fill"></i>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div
                                            style="font-size:.8125rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $doc['tipo'] }}.pdf</div>
                                        <div
                                            style="font-size:.6875rem;display:flex;align-items:center;gap:4px;color:{{ $doc['verificado'] ? '#16a34a' : '#d97706' }};">
                                            @if ($doc['verificado'])
                                                <i class="ri-shield-check-fill"></i> Verificado
                                            @else
                                                <i class="ri-time-fill"></i> En revisión
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div style="text-align:center;padding:20px;color:#94a3b8;font-size:.85rem;">
                                    <i class="ri-file-unknown-line"
                                        style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                    Documento no subido
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Formación académica --}}
            <h4 style="font-size:1rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="ri-graduation-cap-line" style="color:#fc7b04;"></i> Formación Académica
            </h4>
            @if ($estudioPrincipal)
                <div
                    style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div
                            style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:#e0f2fe;color:#0891b2;">
                            <i class="ri-school-line"></i>
                        </div>
                        <div>
                            <div style="font-size:.875rem;font-weight:600;">
                                {{ $estudioPrincipal->grado_academico->nombre ?? 'Sin grado' }}</div>
                            <div style="font-size:.75rem;color:#64748b;">
                                {{ $estudioPrincipal->profesion->nombre ?? 'Sin profesión' }} |
                                {{ $estudioPrincipal->estado ?? '—' }}</div>
                        </div>
                        <span class="ms-auto badge"
                            style="background:#dcfce7;color:#16a34a;font-size:.6875rem;padding:4px 10px;border-radius:20px;">Principal</span>
                    </div>
                    @if ($estudioPrincipal->universidad)
                        <div style="font-size:.8125rem;color:#64748b;border-top:1px solid #e2e8f0;padding-top:12px;">
                            <i class="ri-building-line me-1"></i> {{ $estudioPrincipal->universidad->nombre }}
                        </div>
                    @endif
                </div>
                @php
                    $docsAcademico = [
                        [
                            'nombre' => 'Título/Bachiller',
                            'icono' => 'ri-graduation-cap-line',
                            'archivo' => $estudioPrincipal->documento_academico,
                            'verificado' => $estudioPrincipal->documento_academico_verificado,
                            'tipo' => 'documento_academico',
                        ],
                        [
                            'nombre' => 'Provisión Nacional',
                            'icono' => 'ri-government-line',
                            'archivo' => $estudioPrincipal->documento_provision_nacional,
                            'verificado' => $estudioPrincipal->documento_provision_verificado,
                            'tipo' => 'documento_provision_nacional',
                        ],
                    ];
                @endphp
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;">
                    @foreach ($docsAcademico as $doc)
                        @php
                            $estado = $estadoDoc($doc['archivo'], $doc['verificado']);
                            $bgIcon =
                                $estado['cls'] == 'approved'
                                    ? '#dcfce7'
                                    : ($estado['cls'] == 'review'
                                        ? '#e0f2fe'
                                        : '#fef3c7');
                            $colorIcon =
                                $estado['cls'] == 'approved'
                                    ? '#16a34a'
                                    : ($estado['cls'] == 'review'
                                        ? '#0891b2'
                                        : '#d97706');
                        @endphp
                        <div
                            style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                            <div
                                style="padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                                <div
                                    style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;background:{{ $bgIcon }};color:{{ $colorIcon }};flex-shrink:0;">
                                    <i class="{{ $doc['icono'] }}"></i>
                                </div>
                                <div style="flex:1;min-width:0;font-size:.875rem;font-weight:600;">{{ $doc['nombre'] }}
                                </div>
                                <span
                                    style="padding:4px 10px;border-radius:20px;font-size:.6875rem;font-weight:600;text-transform:uppercase;background:{{ $bgIcon }};color:{{ $colorIcon }};">
                                    {{ $estado['label'] }}
                                </span>
                            </div>
                            <div style="padding:16px;">
                                @if ($doc['archivo'])
                                    <div
                                        style="display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:8px;">
                                        <div
                                            style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.1rem;">
                                            <i class="ri-file-pdf-fill"></i>
                                        </div>
                                        <div style="flex:1;min-width:0;">
                                            <div
                                                style="font-size:.8125rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                {{ $doc['tipo'] }}.pdf</div>
                                            <div
                                                style="font-size:.6875rem;display:flex;align-items:center;gap:4px;color:{{ $doc['verificado'] ? '#16a34a' : '#d97706' }};">
                                                @if ($doc['verificado'])
                                                    <i class="ri-shield-check-fill"></i> Verificado
                                                @else
                                                    <i class="ri-time-fill"></i> En revisión
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div style="text-align:center;padding:20px;color:#94a3b8;font-size:.85rem;">
                                        <i class="ri-file-unknown-line"
                                            style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
                                        Documento no subido
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    style="text-align:center;padding:40px 20px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;">
                    <i class="ri-user-unfollow-line" style="font-size:2.5rem;color:#94a3b8;opacity:.5;"></i>
                    <p style="margin:16px 0 0;color:#64748b;">Sin registro académico registrado</p>
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TAB ACADÉMICO (contenido actual del dashboard)
        ══════════════════════════════════════════════════════════ --}}
        <div class="est-tabs-body" id="tab-academico">
            <p class="est-section-title"><i class="ri-book-line"></i> Mis Programas</p>

            @if ($inscripciones->count() > 0)
                <div class="est-oferta-tabs-nav">
                    @foreach ($inscripciones as $key => $insc)
                        <button type="button" class="est-oferta-tab-btn {{ $key == 0 ? 'active' : '' }}"
                            data-target="academico-oferta-{{ $key }}">
                            <i class="ri-book-2-line"></i>
                            {{ $insc->ofertaAcademica?->programa?->nombre ?? ($insc->ofertaAcademica?->posgrado?->nombre ?? 'Programa ' . ($key + 1)) }}
                        </button>
                    @endforeach
                </div>

                @foreach ($inscripciones as $key => $insc)
                    @php
                        $oferta = $insc->ofertaAcademica;
                        $programa = $oferta?->programa;
                        $matriculas = $insc->moodleMatriculas->sortBy(fn($m) => $m->modulo?->n_modulo);
                        $estadoClass = match ($insc->estado) {
                            'Inscrito', 'Confirmado' => 'inscrito',
                            'Pre-Inscrito' => 'pendiente',
                            default => 'otro',
                        };
                    @endphp
                    <div class="est-oferta-content {{ $key == 0 ? 'active' : '' }}"
                        id="academico-oferta-{{ $key }}">
                        <div class="est-prog-card">
                            <div class="est-prog-header">
                                <div>
                                    <h6>{{ $programa?->nombre ?? 'Programa' }}</h6>
                                    <small>{{ $oferta?->codigo ?? '' }}
                                        @if ($oferta?->fecha_inicio)
                                            · Inicio: {{ \Carbon\Carbon::parse($oferta->fecha_inicio)->format('d/m/Y') }}
                                        @endif
                                    </small>
                                </div>
                                <span class="est-estado-badge {{ $estadoClass }}">{{ $insc->estado }}</span>
                            </div>

                    @if ($matriculas->isEmpty())
                        <div class="est-modulos-list" style="color:#6c757d;font-size:.85rem;">
                            <i class="ri-information-line"></i> Aún no tienes módulos matriculados en este programa.
                        </div>
                    @else
                        <div class="est-modulos-list">
                            @foreach ($matriculas as $matricula)
                                @php
                                    $modulo = $matricula->modulo;
                                    $tieneMoodle = $matricula->moodle_course_id && $matricula->moodle_user_id;
                                @endphp
                                @if (!$modulo)
                                    @continue
                                @endif
                                <div class="est-modulo-item">
                                    <div class="est-modulo-info">
                                        <div class="est-modulo-name">
                                            <span
                                                style="background:{{ $modulo->color ?? '#6366f1' }};width:8px;height:8px;border-radius:50%;display:inline-block;margin-right:.4rem;"></span>
                                            Módulo {{ $modulo->n_modulo }}: {{ $modulo->nombre }}
                                        </div>
                                        <div class="est-modulo-meta">
                                            @if ($modulo->fecha_inicio)
                                                <span><i class="ri-calendar-line"></i>
                                                    {{ \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') }}
                                                    @if ($modulo->fecha_fin)
                                                        — {{ \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y') }}
                                                    @endif
                                                </span>
                                            @endif
                                            @if ($modulo->docente?->persona)
                                                <span><i class="ri-user-line"></i>
                                                    {{ trim(($modulo->docente->persona->nombres ?? '') . ' ' . ($modulo->docente->persona->apellido_paterno ?? '')) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($tieneMoodle)
                                        <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                                            <button class="est-btn-actividades btn-ver-actividades"
                                                data-modulo="{{ $modulo->id }}"
                                                data-panel="panel-mod-{{ $modulo->id }}">
                                                <i class="ri-eye-line"></i> Ver actividades
                                            </button>
                                            @php
                                                $moodleBase = rtrim(config('moodle.url'), '/');
                                                $courseUrl =
                                                    $moodleBase . '/course/view.php?id=' . $matricula->moodle_course_id;
                                                $ssoUrl =
                                                    route('virtual.moodle-sso') . '?target=' . urlencode($courseUrl);
                                            @endphp
                                            <a href="{{ $ssoUrl }}" target="_blank" class="est-btn-actividades"
                                                style="background:#5a8a30;" title="Abrir curso completo en Moodle">
                                                <i class="ri-external-link-line"></i> Ir al curso
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                @if ($tieneMoodle)
                                    <div class="est-act-panel" id="panel-mod-{{ $modulo->id }}">
                                        <div class="est-spinner" id="spinner-mod-{{ $modulo->id }}">
                                            <div class="spinner-border spinner-border-sm"></div> Cargando actividades…
                                        </div>
                                        <div id="contenido-mod-{{ $modulo->id }}"></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
                </div>
                @endforeach
            @else
                <div class="est-no-cuenta">
                    <i class="ri-book-open-line"></i>
                    <h5>Sin inscripciones</h5>
                    <p>Aún no tienes programas inscritos. Contacta con administración para gestionar tu inscripción.</p>
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TAB CONTABLE (solo lectura)
        ══════════════════════════════════════════════════════════ --}}
        @php
            $totalPagado = 0;
            $totalPendiente = 0;
            $totalVencido = 0;
            foreach ($inscripciones as $ins) {
                foreach ($ins->cuotas as $cuota) {
                    $pagadoEnCuota = $cuota->pagosCuota->sum('monto_bs');
                    if ($pagadoEnCuota > 0) {
                        $totalPagado += $pagadoEnCuota;
                    }
                    $pendiente = $cuota->monto_bs - $pagadoEnCuota;
                    if ($pendiente > 0) {
                        if ($cuota->estado == 'Vencido') {
                            $totalVencido += $pendiente;
                        } else {
                            $totalPendiente += $pendiente;
                        }
                    }
                }
            }
        @endphp
        <div class="est-tabs-body" id="tab-contable">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="est-stat-card">
                        <div class="est-stat-body">
                            <div class="est-stat-icon"
                                style="background:var(--est-success-light);color:var(--est-success);"><i
                                    class="ri-checkbox-circle-line"></i></div>
                            <div>
                                <div class="est-stat-value" style="color:var(--est-success);">Bs.
                                    {{ number_format($totalPagado, 2) }}</div>
                                <div class="est-stat-label-sm">Total Pagado</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="est-stat-card">
                        <div class="est-stat-body">
                            <div class="est-stat-icon"
                                style="background:var(--est-warning-light);color:var(--est-warning);"><i
                                    class="ri-time-line"></i></div>
                            <div>
                                <div class="est-stat-value" style="color:var(--est-warning);">Bs.
                                    {{ number_format($totalPendiente, 2) }}</div>
                                <div class="est-stat-label-sm">Pendiente</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="est-stat-card">
                        <div class="est-stat-body">
                            <div class="est-stat-icon"
                                style="background:var(--est-danger-light);color:var(--est-danger);"><i
                                    class="ri-alert-line"></i></div>
                            <div>
                                <div class="est-stat-value" style="color:var(--est-danger);">Bs.
                                    {{ number_format($totalVencido, 2) }}</div>
                                <div class="est-stat-label-sm">Vencido</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($inscripciones->count() > 0)
                <div class="est-oferta-tabs-nav">
                    @foreach ($inscripciones as $key => $ins)
                        <button type="button" class="est-oferta-tab-btn {{ $key == 0 ? 'active' : '' }}"
                            data-target="contable-oferta-{{ $key }}">
                            <i class="ri-money-dollar-circle-line"></i>
                            {{ $ins->ofertaAcademica?->programa?->nombre ?? ($ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta ' . ($key + 1)) }}
                        </button>
                    @endforeach
                </div>

                @foreach ($inscripciones as $key => $ins)
                    @php
                        $insPagado = 0;
                        $insPendiente = 0;
                        $insVencido = 0;
                        foreach ($ins->cuotas as $cuota) {
                            $pagadoEnCuota = $cuota->pagosCuota->sum('monto_bs');
                            if ($pagadoEnCuota > 0) {
                                $insPagado += $pagadoEnCuota;
                            }
                            $pendiente = $cuota->monto_bs - $pagadoEnCuota;
                            if ($pendiente > 0) {
                                if ($cuota->estado == 'Vencido') {
                                    $insVencido += $pendiente;
                                } else {
                                    $insPendiente += $pendiente;
                                }
                            }
                        }
                    @endphp
                    <div class="est-oferta-content {{ $key == 0 ? 'active' : '' }}"
                        id="contable-oferta-{{ $key }}">
                        <div class="est-data-card mb-4">
                            <div class="est-data-card-header">
                                <div class="est-data-card-icon"
                                    style="background:var(--est-primary-light);color:var(--est-primary);"><i
                                        class="ri-money-dollar-circle-line"></i></div>
                                <div style="flex:1;">
                                    <h5 class="est-data-card-title">
                                        {{ $ins->ofertaAcademica?->programa?->nombre ?? ($ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta #' . $ins->ofertas_academica_id) }}
                                    </h5>
                                    <div style="font-size:.75rem;color:var(--est-text-muted);">Plan:
                                        {{ $ins->planesPago?->nombre ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="est-stat-body" style="border-right:1px solid var(--est-border);">
                                        <div class="est-stat-icon"
                                            style="background:var(--est-success-light);color:var(--est-success);"><i
                                                class="ri-checkbox-circle-line"></i></div>
                                        <div>
                                            <div class="est-stat-value" style="color:var(--est-success);font-size:1rem;">
                                                Bs. {{ number_format($insPagado, 2) }}</div>
                                            <div class="est-stat-label-sm">Pagado</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="est-stat-body" style="border-right:1px solid var(--est-border);">
                                        <div class="est-stat-icon"
                                            style="background:var(--est-warning-light);color:var(--est-warning);"><i
                                                class="ri-time-line"></i></div>
                                        <div>
                                            <div class="est-stat-value" style="color:var(--est-warning);font-size:1rem;">
                                                Bs. {{ number_format($insPendiente, 2) }}</div>
                                            <div class="est-stat-label-sm">Pendiente</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="est-stat-body">
                                        <div class="est-stat-icon"
                                            style="background:var(--est-danger-light);color:var(--est-danger);"><i
                                                class="ri-alert-line"></i></div>
                                        <div>
                                            <div class="est-stat-value" style="color:var(--est-danger);font-size:1rem;">
                                                Bs. {{ number_format($insVencido, 2) }}</div>
                                            <div class="est-stat-label-sm">Vencido</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($ins->cuotas && $ins->cuotas->count() > 0)
                                <div style="padding:14px 18px;border-top:1px solid var(--est-border);">
                                    <div
                                        style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--est-text-muted);margin-bottom:10px;">
                                        <i class="ri-install-line"></i> Cuotas ({{ $ins->cuotas->count() }})
                                    </div>
                                    <div style="overflow-x:auto;">
                                        <table class="est-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Cuota</th>
                                                    <th>Monto</th>
                                                    <th>Descuento</th>
                                                    <th>Pendiente</th>
                                                    <th>Vencimiento</th>
                                                    <th>Total Pagado</th>
                                                    <th>Estado</th>
                                                    <th>Detalle</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ins->cuotas as $cuota)
                                                    @php
                                                        $totalPagadoCuota = $cuota->pagosCuota->sum('monto_bs');
                                                        $pagosData = [];
                                                        foreach ($cuota->pagosCuota as $pc) {
                                                            if ($pc->pago) {
                                                                $pago = $pc->pago;
                                                                $trabajadorNombre = $pago->trabajadorCargo?->trabajador
                                                                    ?->persona
                                                                    ? $pago->trabajadorCargo->trabajador->persona
                                                                            ->nombres .
                                                                        ' ' .
                                                                        $pago->trabajadorCargo->trabajador->persona
                                                                            ->apellido_paterno
                                                                    : '—';
                                                                $comprobante = null;
                                                                $cuotaIds = $pago->pagosCuotas
                                                                    ->pluck('cuota_id')
                                                                    ->toArray();
                                                                if (!empty($cuotaIds)) {
                                                                    $respaldos = \DB::table('pago_respaldo_cuota')
                                                                        ->whereIn(
                                                                            'pago_respaldo_cuota.cuota_id',
                                                                            $cuotaIds,
                                                                        )
                                                                        ->join(
                                                                            'pagos_respaldos',
                                                                            'pago_respaldo_cuota.pago_respaldo_id',
                                                                            '=',
                                                                            'pagos_respaldos.id',
                                                                        )
                                                                        ->where('pagos_respaldos.estado', 'verificado')
                                                                        ->select('pagos_respaldos.archivo')
                                                                        ->first();
                                                                    if ($respaldos) {
                                                                        $comprobante = [
                                                                            'archivo' => $respaldos->archivo,
                                                                            'url' => asset(
                                                                                'storage/comprobantes/' .
                                                                                    $respaldos->archivo,
                                                                            ),
                                                                        ];
                                                                    }
                                                                }
                                                                $pagosData[] = [
                                                                    'id' => $pago->id,
                                                                    'recibo' => $pago->recibo,
                                                                    'fecha' => $pago->fecha_pago,
                                                                    'monto' => $pago->monto_total,
                                                                    'descuento' => $pago->descuento_bs,
                                                                    'metodo' => $pago->tipo_pago,
                                                                    'trabajador' => $trabajadorNombre,
                                                                    'estudiante' => trim(
                                                                        ($persona->nombres ?? '') .
                                                                            ' ' .
                                                                            ($persona->apellido_paterno ?? ''),
                                                                    ),
                                                                    'programa' =>
                                                                        $ins->ofertaAcademica?->posgrado?->nombre ??
                                                                        ($ins->ofertaAcademica?->programa?->nombre ??
                                                                            ''),
                                                                    'plan' => $ins->planesPago?->nombre ?? '',
                                                                    'comprobante' => $comprobante,
                                                                    'detalles' => ($pago->detalles ?? collect())
                                                                        ->map(
                                                                            fn($d) => [
                                                                                'tipo' => $d->tipo_pago,
                                                                                'monto' => $d->monto_bs,
                                                                            ],
                                                                        )
                                                                        ->toArray(),
                                                                    'cuotas' => ($pago->pagosCuotas ?? collect())
                                                                        ->map(
                                                                            fn($pc2) => [
                                                                                'nombre' =>
                                                                                    $pc2->cuota?->nombre ??
                                                                                    'Cuota #' . $pc2->cuota_id,
                                                                                'n_cuota' =>
                                                                                    $pc2->cuota?->n_cuota ?? null,
                                                                                'monto' => $pc2->monto_bs,
                                                                            ],
                                                                        )
                                                                        ->toArray(),
                                                                ];
                                                            }
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $cuota->n_cuota }}</td>
                                                        <td>{{ $cuota->nombre }}</td>
                                                        <td>Bs. {{ number_format($cuota->monto_bs, 2) }}</td>
                                                        <td>{{ $cuota->descuento_bs > 0 ? 'Bs. ' . number_format($cuota->descuento_bs, 2) : '—' }}
                                                        </td>
                                                        <td>Bs.
                                                            {{ number_format($cuota->pago_pendiente_bs ?? $cuota->monto_bs, 2) }}
                                                        </td>
                                                        <td>{{ $cuota->fecha_vencimiento ? \Carbon\Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y') : '—' }}
                                                        </td>
                                                        <td>Bs. {{ number_format($totalPagadoCuota, 2) }}</td>
                                                        <td>
                                                            <span
                                                                class="estado-badge-est {{ $cuota->estado == 'Pagado' ? 'pagado' : ($cuota->estado == 'Vencido' ? 'vencido' : 'pendiente') }}">
                                                                {{ $cuota->estado }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if (count($pagosData) > 0)
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-primary btn-ver-detalle-pago"
                                                                    data-pagos='{{ json_encode($pagosData) }}'
                                                                    title="Ver detalle">
                                                                    <i class="ri-eye-line"></i>
                                                                </button>
                                                            @else
                                                                —
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div style="padding:20px;text-align:center;color:var(--est-text-muted);">
                                    <i class="ri-money-dollar-line" style="font-size:1.5rem;opacity:.5;"></i>
                                    <p>Sin cuotas registradas</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="est-empty-state">
                    <i class="ri-money-dollar-line"></i>
                    <h5>Sin información contable</h5>
                    <p>No hay ofertas académicas registradas</p>
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TAB PAGOS — Comprobantes de Pago
        ══════════════════════════════════════════════════════════ --}}
        <div class="est-tabs-body" id="tab-pagos">

            @php
                $totalComprobantes = 0;
                $comprobantesVerificados = 0;
                $comprobantesPendientes = 0;
                $comprobantesRechazados = 0;
                foreach ($inscripciones as $ins) {
                    foreach ($ins->pagosRespaldos as $r) {
                        $totalComprobantes++;
                        if ($r->estado === 'verificado') $comprobantesVerificados++;
                        elseif ($r->estado === 'rechazado') $comprobantesRechazados++;
                        else $comprobantesPendientes++;
                    }
                }
            @endphp

            {{-- Stats --}}
            <div class="pagos-stats-row">
                <div class="pagos-stat-card verificado">
                    <div class="pagos-stat-icon verificado">
                        <i class="ri-checkbox-circle-line"></i>
                    </div>
                    <div class="pagos-stat-info">
                        <div class="pagos-stat-value">{{ $comprobantesVerificados }}</div>
                        <div class="pagos-stat-label">Verificados</div>
                    </div>
                </div>
                <div class="pagos-stat-card pendiente">
                    <div class="pagos-stat-icon pendiente">
                        <i class="ri-time-line"></i>
                    </div>
                    <div class="pagos-stat-info">
                        <div class="pagos-stat-value">{{ $comprobantesPendientes }}</div>
                        <div class="pagos-stat-label">En revisión</div>
                    </div>
                </div>
                <div class="pagos-stat-card rechazado">
                    <div class="pagos-stat-icon rechazado">
                        <i class="ri-close-circle-line"></i>
                    </div>
                    <div class="pagos-stat-info">
                        <div class="pagos-stat-value">{{ $comprobantesRechazados }}</div>
                        <div class="pagos-stat-label">Rechazados</div>
                    </div>
                </div>
            </div>

            @if ($inscripciones->count() > 0)

                {{-- Sub-tabs por inscripción --}}
                <div class="pagos-tabs-wrapper">
                    <div class="pagos-tabs-header">
                        @foreach ($inscripciones as $key => $ins)
                            <button type="button" class="pagos-tab-btn {{ $key == 0 ? 'active' : '' }}"
                                data-target="pagos-oferta-{{ $key }}">
                                <i class="ri-book-2-line"></i>
                                {{ $ins->ofertaAcademica?->programa?->nombre ?? ($ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta ' . ($key + 1)) }}
                            </button>
                        @endforeach
                    </div>

                    @foreach ($inscripciones as $key => $ins)
                        @php
                            $cuotasPendIns = $ins->cuotas->filter(fn($c) => (float)($c->pago_pendiente_bs ?? $c->monto_bs) > 0);
                            $tienePendientes = $cuotasPendIns->isNotEmpty();
                        @endphp
                        <div class="pagos-oferta-content {{ $key == 0 ? 'active' : '' }}" id="pagos-oferta-{{ $key }}">

                            {{-- Cuotas --}}
                            <div class="pagos-section-card">
                                <div class="pagos-section-header">
                                    <div class="pagos-section-title">
                                        <div class="pagos-section-icon cuotas">
                                            <i class="ri-installment-line"></i>
                                        </div>
                                        <div class="pagos-section-title-text">
                                            <h5>Estado de Cuotas</h5>
                                            <span>{{ $ins->planesPago?->nombre ?? 'Sin plan definido' }}</span>
                                        </div>
                                    </div>
                                    @if ($tienePendientes)
                                        @php
                                            $progNombre = addslashes($ins->ofertaAcademica?->programa?->nombre ?? ($ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta'));
                                            $planNombre = addslashes($ins->planesPago?->nombre ?? '');
                                        @endphp
                                        <button type="button" class="pagos-btn-subir"
                                            onclick="estAbrirModal('{{ $ins->id }}', '{{ $progNombre }}', '{{ $planNombre }}')">
                                            <i class="ri-upload-cloud-line"></i> Subir Comprobante
                                        </button>
                                    @else
                                        <span class="pagos-btn-al-dia">
                                            <i class="ri-checkbox-circle-fill"></i> Al día
                                        </span>
                                    @endif
                                </div>

                                @if ($ins->cuotas && $ins->cuotas->count() > 0)
                                    <div class="pagos-table-wrapper">
                                        <table class="pagos-table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 60px;">#</th>
                                                    <th>Cuota</th>
                                                    <th>Monto</th>
                                                    <th>Pendiente</th>
                                                    <th>Vencimiento</th>
                                                    <th style="width: 120px;">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ins->cuotas as $cuota)
                                                    @php
                                                        $estadoClass = $cuota->estado == 'Pagado' ? 'pagado' : ($cuota->estado == 'Vencido' ? 'vencido' : 'pendiente');
                                                    @endphp
                                                    <tr>
                                                        <td><span class="num-cuota">{{ $cuota->n_cuota }}</span></td>
                                                        <td style="font-weight: 600; color: #1e293b;">{{ $cuota->nombre }}</td>
                                                        <td class="monto-cell">Bs. {{ number_format($cuota->monto_bs, 2) }}</td>
                                                        <td style="color: #f59e0b;">Bs. {{ number_format($cuota->pago_pendiente_bs ?? $cuota->monto_bs, 2) }}</td>
                                                        <td class="fecha-cell">{{ $cuota->fecha_vencimiento ? \Carbon\Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                                                        <td><span class="pagos-cuota-badge {{ $estadoClass }}">{{ $cuota->estado }}</span></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="pagos-empty">
                                        <i class="ri-inbox-line"></i>
                                        <p>Sin cuotas registradas</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Comprobantes enviados --}}
                            <div class="pagos-section-card">
                                <div class="pagos-section-header">
                                    <div class="pagos-section-title">
                                        <div class="pagos-section-icon comprobantes">
                                            <i class="ri-file-list-3-line"></i>
                                        </div>
                                        <div class="pagos-section-title-text">
                                            <h5>Comprobantes Enviados</h5>
                                            <span>{{ $ins->pagosRespaldos->count() }} total(es)</span>
                                        </div>
                                    </div>
                                </div>

                                @if ($ins->pagosRespaldos->count() > 0)
                                    <div class="pagos-table-wrapper">
                                        <table class="pagos-table">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Cuotas</th>
                                                    <th>Observaciones</th>
                                                    <th style="width: 120px;">Estado</th>
                                                    <th style="width: 100px;">Archivo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ins->pagosRespaldos->sortByDesc('created_at') as $resp)
                                                    @php
                                                        $stClass = $resp->estado === 'verificado' ? 'verificado' : ($resp->estado === 'rechazado' ? 'rechazado' : 'revision');
                                                        $stLabel = $resp->estado === 'verificado' ? 'Verificado' : ($resp->estado === 'rechazado' ? 'Rechazado' : 'En revisión');
                                                        $archivoUrl = asset('storage/comprobantes/' . $resp->archivo);
                                                        $esImagen = preg_match('/\.(jpg|jpeg|png)$/i', $resp->archivo);
                                                    @endphp
                                                    <tr>
                                                        <td class="fecha-cell"><i class="ri-calendar-line" style="margin-right: 6px; color: #94a3b8;"></i>{{ $resp->created_at->format('d/m/Y') }} <span style="color: #94a3b8; font-size: .75rem;">{{ $resp->created_at->format('H:i') }}</span></td>
                                                        <td>
                                                            <div class="pagos-cuota-tags">
                                                                @forelse ($resp->cuotas as $cq)
                                                                    <span class="pagos-cuota-tag">{{ $cq->nombre }}</span>
                                                                @empty
                                                                    <span style="color: #94a3b8;">—</span>
                                                                @endforelse
                                                            </div>
                                                        </td>
                                                        <td style="color: #64748b;">{{ $resp->observaciones ?: '—' }}</td>
                                                        <td><span class="pagos-comp-badge {{ $stClass }}">{{ $stLabel }}</span></td>
                                                        <td>
                                                            <a href="{{ $archivoUrl }}" target="_blank" class="pagos-comp-link">
                                                                <i class="{{ $esImagen ? 'ri-image-fill' : 'ri-file-pdf-fill' }}"></i> Ver
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="pagos-empty">
                                        <i class="ri-file-upload-line"></i>
                                        <p>No has enviado comprobantes aún</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>

            @else
                <div class="est-empty-state">
                    <i class="ri-file-list-3-line"></i>
                    <h5>Sin inscripciones</h5>
                    <p>No hay inscripciones para mostrar</p>
                </div>
            @endif
        </div>{{-- /tab-pagos --}}
        </div>{{-- /content-estudiante --}}

        @if ($esDocente)
        <div id="content-docente" {!! $perfilActivo !== 'docente' ? 'style="display:none"' : '' !!}>
        @endif
        </div>{{-- /content-docente --}}

    </div>{{-- /est-tabs-card --}}

    {{-- Modal Subir Comprobante (estudiante) --}}
    <div class="modal fade modal-comprobante" id="modalEstComprobante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width:600px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-file-upload-line"></i> Subir Comprobante de Pago</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="comprobante-info-card" id="estCompInfo">
                        <div class="programa" id="estCompPrograma"></div>
                        <div class="plan" id="estCompPlan"></div>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label class="comprobante-label">Archivo del comprobante <span style="color:#dc2626;">*</span></label>
                        <div class="comprobante-file-area" id="comprobanteFileArea" onclick="document.getElementById('estCompArchivo').click()">
                            <i class="ri-upload-cloud-line"></i>
                            <span>Haz clic para seleccionar el archivo</span>
                            <small>JPG, PNG o PDF — máx. 5 MB</small>
                        </div>
                        <input type="file" id="estCompArchivo" accept=".jpg,.jpeg,.png,.pdf" style="display: none;">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label class="comprobante-label">Observaciones</label>
                        <textarea class="comprobante-textarea" id="estCompObservaciones" rows="2" placeholder="Opcional: Agrega alguna observación sobre tu pago..."></textarea>
                    </div>
                    <div>
                        <label class="comprobante-label">Cuotas que cubre este comprobante <span style="color:#dc2626;">*</span></label>
                        <div id="estCompCuotasLoading" class="text-center py-3">
                            <div class="spinner-border spinner-border-sm" style="color:#fc7b04;"></div>
                            <span class="ms-2 text-muted" style="font-size:.8rem;">Cargando cuotas...</span>
                        </div>
                        <div id="estCompCuotasContainer" class="comprobante-cuotas-container" style="display:none;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancelar" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Cancelar
                    </button>
                    <button type="button" id="btnEstEnviarComprobante" class="btn-enviar">
                        <i class="ri-send-plane-line"></i> Enviar Comprobante
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast container (estudiante) --}}
    <div id="est-toast-container" style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:.5rem;"></div>

    {{-- Modal Ver Detalle Pago (usado por tab contable) --}}
    <div class="modal fade modal-detalle-pago" id="modalVerDetallePago" tabindex="-1" aria-labelledby="modalVerDetallePagoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVerDetallePagoLabel"><i class="ri-file-receipt-line"></i> Detalle del Pago</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="lista-pagos" class="pago-list-container"></div>
                    <div id="detalle-pago-container" style="display:none;">
                        <div class="detalle-header">
                            <div class="detalle-header-top">
                                <div class="detalle-logo">
                                    <img src="{{ asset('build/images/logo-dark.png') }}" alt="Logo" style="width: 40px;">
                                    <div>
                                        <div class="detalle-logo-text">INNOVA CIENCIA VIRTUAL</div>
                                        <div class="detalle-logo-sub">Educación Superior Virtual</div>
                                    </div>
                                </div>
                                <div class="detalle-recibo-badge">
                                    <i class="ri-file-list-3-line"></i>
                                    <span id="detalle-recibo">—</span>
                                </div>
                            </div>
                            <div class="detalle-meta">
                                <span><strong>Fecha:</strong> <span id="detalle-fecha">—</span></span>
                                <span><strong>Forma de Pago:</strong> <span id="detalle-metodo">—</span></span>
                            </div>
                        </div>
                        <div class="detalle-info-section">
                            <h6><i class="ri-user-line"></i> Información del Estudiante</h6>
                            <div class="detalle-info-row">
                                <div class="detalle-info-item">
                                    <div class="detalle-info-label">Estudiante</div>
                                    <div class="detalle-info-value" id="detalle-estudiante">—</div>
                                </div>
                                <div class="detalle-info-item">
                                    <div class="detalle-info-label">Programa</div>
                                    <div class="detalle-info-value" id="detalle-programa">—</div>
                                </div>
                            </div>
                        </div>
                        <div class="detalle-info-section">
                            <h6><i class="ri-money-dollar-line"></i> Detalle del Pago</h6>
                            <div class="detalle-info-item" style="margin-bottom: 12px;">
                                <div class="detalle-info-label">Plan de Pago</div>
                                <div class="detalle-info-value" id="detalle-plan">—</div>
                            </div>
                            <div class="detalle-tabla">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Concepto</th>
                                            <th class="text-end">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detalle-tabla"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="fw-bold">Total (Bs.)</td>
                                            <td class="text-end fw-bold" id="detalle-total">—</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="detalle-descuento-container" style="display:none; margin-top: 12px;">
                            <div class="detalle-info-item" style="background: linear-gradient(135deg, #fef3c7 0%, #fef9c3 100%);">
                                <div class="detalle-info-label">Descuento Aplicado</div>
                                <div class="detalle-info-value" style="color: #d97706;" id="detalle-descuento">—</div>
                            </div>
                        </div>
                        <div class="detalle-footer">
                            <div class="detalle-footer-box">
                                <div class="label"><i class="ri-user-star-line"></i> Emisor</div>
                                <div class="value" id="detalle-trabajador">—</div>
                            </div>
                            <div class="detalle-footer-box">
                                <div class="label"><i class="ri-account-circle-line"></i> Depositante</div>
                                <div class="value" id="detalle-depositante">—</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="btn-descargar-pdf" class="btn-descargar" target="_blank">
                        <i class="ri-file-pdf-line"></i> Descargar PDF
                    </a>
                    <button type="button" class="btn-cerrar" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

{{-- ══════════════════════════════════════════════════════════
     TAB CRONOGRAMA — Cronograma de Clases
═════════════════════════════════════════════════════════ --}}
<div class="est-tabs-body" id="tab-cronograma">

    @if($ofertasCronograma->isEmpty())
        <div class="est-empty-state">
            <i class="ri-calendar-close-line"></i>
            <h5>No tienes ofertas inscritas</h5>
            <p>No tienes ofertas académicas con inscripción activa para mostrar cronograma.</p>
        </div>
    @else
        <div class="cronograma-container d-flex" style="min-height: 600px;">
            <div class="cronograma-sidebar col-xl-4">
                <div class="cronograma-header-strip">
                    <h5><i class="ri-calendar-event-line"></i> Oferta Académica</h5>
                </div>
                <select class="cronograma-select w-100 mb-3" id="select-oferta-cronograma" onchange="cargarModulosCronograma()">
                    <option value="">Seleccionar oferta académica</option>
                    @foreach($ofertasCronograma as $oferta)
                        <option value="{{ $oferta['id'] }}">{{ $oferta['codigo'] }} - {{ $oferta['nombre'] }}</option>
                    @endforeach
                </select>
                <button class="cronograma-btn-all active" id="btnTodosModulosCronograma" onclick="verTodosModulosCronograma()">
                    <i class="ri-layout-grid-line"></i> Todos los módulos
                </button>
                <div id="modulosSidebarListCronograma">
                    <div class="text-center text-muted py-4" style="font-size: .85rem;">
                        <i class="ri-arrow-up-line" style="font-size: 1.5rem; display: block; margin-bottom: 8px; color: #cbd5e1;"></i>
                        Selecciona una oferta académica
                    </div>
                </div>
            </div>
            <div class="cronograma-main col-xl-8">
                <div class="cronograma-title-section">
                    <div class="cronograma-title-left">
                        <div class="cronograma-title-icon">
                            <i class="ri-calendar-line"></i>
                        </div>
                        <div class="cronograma-title-text">
                            <h4>Calendario de Sesiones</h4>
                            <span>Visualiza todas tus clases programadas</span>
                        </div>
                    </div>
                    <div id="moduloSeleccionadoBadgeCronograma" class="cronograma-filter-badge" style="display: none;">
                        <span class="dot"></span>
                        <span class="modulo-badge-name"></span>
                        <button type="button" title="Quitar filtro" onclick="verTodosModulosCronograma()">
                            <i class="ri-close-circle-fill"></i>
                        </button>
                    </div>
                </div>
                <div class="cronograma-stats" id="calendarStatsCronograma">
                    <div class="cronograma-stat-card">
                        <div class="cronograma-stat-icon orange">
                            <i class="ri-stack-line"></i>
                        </div>
                        <div>
                            <div class="cronograma-stat-value" id="stat-modulos">0</div>
                            <div class="cronograma-stat-label">Módulos activos</div>
                        </div>
                    </div>
                    <div class="cronograma-stat-card">
                        <div class="cronograma-stat-icon green">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                        <div>
                            <div class="cronograma-stat-value" id="stat-sesiones">0</div>
                            <div class="cronograma-stat-label">Sesiones totales</div>
                        </div>
                    </div>
                </div>
                <div class="cronograma-calendar-wrapper">
                    <div id="calendarCronograma"></div>
                </div>
            </div>
        </div>
    @endif
        </div>{{-- /content-estudiante --}}
</div>

@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/fullcalendar/index.global.min.js') }}"></script>
    <script>
        /* ── Variables globales ── */
        const loaded = {};
        let calendarioCronograma = null;
        let calendarioDocente = null;
        let datosCronograma = @json($ofertasCronograma);
        @if ($esDocente)
        let datosHorariosDocente = {!! json_encode($horariosDocente) !!};
        @endif
        let moduloSeleccionadoId = null;

        /* ── switchTab (estudiante tabs) ── */
        function switchTab(btn, tabId) {
            var nav = btn.closest('.est-tabs-nav');
            if (nav) nav.querySelectorAll('.est-tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            var container = document.getElementById('content-estudiante');
            if (container) container.querySelectorAll('.est-tabs-body').forEach(t => t.classList.remove('active'));
            var targetTab = document.getElementById(tabId);
            if (targetTab) targetTab.classList.add('active');

            if (tabId === 'tab-cronograma') {
                if (!calendarioCronograma && datosCronograma && datosCronograma.length > 0) {
                    const select = document.getElementById('select-oferta-cronograma');
                    if (select) {
                        select.value = datosCronograma[0].id;
                        cargarModulosCronograma();
                    }
                } else if (calendarioCronograma) {
                    setTimeout(function() { calendarioCronograma.updateSize(); }, 10);
                }
            }
        }

        /* ── switchTabDocente (docente tabs) ── */
        function switchTabDocente(btn, tabId) {
            var nav = document.getElementById('nav-docente');
            if (nav) nav.querySelectorAll('.est-tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            var container = document.getElementById('content-docente');
            if (container) container.querySelectorAll('.est-tabs-body').forEach(t => t.classList.remove('active'));
            var targetTab = document.getElementById(tabId);
            if (targetTab) targetTab.classList.add('active');

            if (tabId === 'tab-horario-docente') {
                setTimeout(function() {
                    if (typeof initCalendarDocente === 'function' && typeof calendarioDocente === 'undefined') {
                        if (datosHorariosDocente && datosHorariosDocente.length > 0) {
                            initCalendarDocente(datosHorariosDocente);
                        }
                    } else if (calendarioDocente) {
                        calendarioDocente.updateSize();
                    }
                }, 50);
            }
        }

        window.switchTab = switchTab;
        window.switchTabDocente = switchTabDocente;

        /* ── Cronograma funciones ─────────────────────────────── */
        window.cargarModulosCronograma = function() {
                const ofertaId = document.getElementById('select-oferta-cronograma').value;
                const listaModulos = document.getElementById('modulosSidebarListCronograma');
                const btnTodos = document.getElementById('btnTodosModulosCronograma');
                const badge = document.getElementById('moduloSeleccionadoBadgeCronograma');

                moduloSeleccionadoId = null;
                badge.style.display = 'none';
                btnTodos.classList.add('active');

                if (!ofertaId) {
                    listaModulos.innerHTML = '<div class="text-center text-muted py-4">Selecciona una oferta</div>';
                    document.getElementById('stat-modulos').textContent = '0';
                    document.getElementById('stat-sesiones').textContent = '0';
                    if (calendarioCronograma) {
                        calendarioCronograma.removeAllEvents();
                    }
                    return;
                }

                const oferta = datosCronograma.find(function(o) { return o.id == parseInt(ofertaId); });
                if (!oferta) {
                    return;
                }

                document.getElementById('stat-modulos').textContent = oferta.n_modulos;
                document.getElementById('stat-sesiones').textContent = oferta.cantidad_sesiones;

                listaModulos.innerHTML = oferta.modulos.map(function(modulo) {
                    var badgeMoodle = modulo.moodle_course_id ? 
                        '<span style="background:rgba(21,101,192,0.12);color:#1565c0;padding:1px 5px;border-radius:4px;font-size:0.65rem;margin-left:5px;">Moodle</span>' : '';
                    var docenteHtml = '<div class="cronograma-modulo-docente">' + (modulo.docente || 'Sin docente') + '</div>';
                    var sesionesCount = modulo.sesiones_count + '/' + oferta.cantidad_sesiones;
                    return '<div class="cronograma-modulo-card" style="--mod-color:' + modulo.color + ';" onclick="seleccionarModuloCronograma(' + modulo.id + ', \'' + modulo.nombre.replace(/'/g, "\\'") + '\', \'' + modulo.color + '\', event)">' +
                        '<div class="cronograma-modulo-dot" style="background:' + modulo.color + '"></div>' +
                        '<div class="cronograma-modulo-info">' +
                            '<div class="cronograma-modulo-num">Módulo ' + modulo.numero + '</div>' +
                            '<div class="cronograma-modulo-name">' + modulo.nombre + badgeMoodle + '</div>' +
                            docenteHtml +
                        '</div>' +
                        '<div class="cronograma-modulo-badge">' + sesionesCount + '</div>' +
                        '</div>';
                }).join('');

                actualizarCalendarioCronograma(oferta.modulos);
            };

            window.seleccionarModuloCronograma = function(moduloId, moduloNombre, moduloColor, evt) {
                const badge = document.getElementById('moduloSeleccionadoBadgeCronograma');
                const btnTodos = document.getElementById('btnTodosModulosCronograma');

                badge.style.display = 'flex';
                badge.querySelector('.dot').style.background = moduloColor;
                badge.querySelector('.modulo-badge-name').textContent = 'Módulo: ' + moduloNombre;

                btnTodos.classList.remove('active');
                document.querySelectorAll('.cronograma-modulo-card').forEach(function(el) { el.classList.remove('active'); });
                if (evt && evt.target) {
                    evt.target.closest('.cronograma-modulo-card').classList.add('active');
                }

                moduloSeleccionadoId = moduloId;

                const ofertaId = document.getElementById('select-oferta-cronograma').value;
                const oferta = datosCronograma.find(function(o) { return o.id == parseInt(ofertaId); });
                const modulo = oferta.modulos.find(function(m) { return m.id == moduloId; });
                
                actualizarCalendarioCronograma([modulo]);
            };

            window.verTodosModulosCronograma = function() {
                const ofertaId = document.getElementById('select-oferta-cronograma').value;
                const badge = document.getElementById('moduloSeleccionadoBadgeCronograma');
                const btnTodos = document.getElementById('btnTodosModulosCronograma');

                moduloSeleccionadoId = null;
                badge.style.display = 'none';
                btnTodos.classList.add('active');

                document.querySelectorAll('.cronograma-modulo-card').forEach(function(el) { el.classList.remove('active'); });

                if (!ofertaId) return;

                const oferta = datosCronograma.find(function(o) { return o.id == parseInt(ofertaId); });
                actualizarCalendarioCronograma(oferta.modulos);
            };

            window.actualizarCalendarioCronograma = function(modulos) {
                const eventos = [];
                modulos.forEach(function(modulo) {
                    if (modulo.sesiones && modulo.sesiones.length > 0) {
                        modulo.sesiones.forEach(function(sesion) {
                            eventos.push({
                                id: sesion.id,
                                title: sesion.titulo + '\n' + sesion.salon + ' - ' + sesion.docente,
                                start: sesion.start,
                                end: sesion.end,
                                backgroundColor: modulo.color,
                                borderColor: modulo.color,
                                textColor: '#fff',
                                extendedProps: {
                                    docente: sesion.docente,
                                    salon: sesion.salon,
                                    estado: sesion.estado
                                }
                            });
                        });
                    }
                });

                const calendarEl = document.getElementById('calendarCronograma');

                if (!calendarioCronograma) {
                    calendarioCronograma = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek'
                        },
                        locale: 'es',
                        buttonText: {
                            today: 'Hoy',
                            month: 'Mes',
                            week: 'Semana',
                            day: 'Día',
                            list: 'Lista'
                        },
                        editable: false,
                        selectable: false,
                        eventDisplay: 'block',
                        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
                        eventClick: function(info) {
                            const props = info.event.extendedProps || {};
                            const start = info.event.start;
                            const end = info.event.end;
                            const fecha = start.toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                            const horaInicio = start.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
                            const horaFin = end ? end.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' }) : '—';
                            
                            const partes = info.event.title.split('\n');
                            const moduloNombre = partes[0] || '';
                            const docente = props.docente || 'Sin asignar';
                            const salon = props.salon || 'Sin asignar';
                            const estado = props.estado || 'Confirmado';
                            
                            const estadoColor = estado === 'Postergado' ? '#64748b' : '#22c55e';
                            const estadoBg = estado === 'Postergado' ? '#f1f5f9' : '#dcfce7';
                            
                            const toast = document.createElement('div');
                            toast.className = 'session-toast';
                            toast.innerHTML = 
                                '<button class="session-toast-close" onclick="this.parentElement.remove()"><i class="ri-close-line"></i></button>' +
                                '<div class="session-toast-header">' +
                                    '<div class="session-toast-dot" style="background:' + info.event.backgroundColor + ';"></div>' +
                                    '<div class="session-toast-title">' + moduloNombre + '</div>' +
                                    '<span class="session-toast-state ' + (estado === 'Confirmado' ? 'confirmado' : 'postergado') + '">' + estado + '</span>' +
                                '</div>' +
                                '<div class="session-toast-body">' +
                                    '<div class="session-toast-row"><div class="session-toast-icon"><i class="ri-calendar-line"></i></div><span class="session-toast-text">' + fecha + '</span></div>' +
                                    '<div class="session-toast-row"><div class="session-toast-icon"><i class="ri-time-line"></i></div><span class="session-toast-text">' + horaInicio + ' – ' + horaFin + '</span></div>' +
                                    '<div class="session-toast-row"><div class="session-toast-icon"><i class="ri-user-line"></i></div><span class="session-toast-text">' + docente + '</span></div>' +
                                    '<div class="session-toast-row"><div class="session-toast-icon"><i class="ri-map-pin-line"></i></div><span class="session-toast-text">' + salon + '</span></div>' +
                                '</div>';
                            document.body.appendChild(toast);
                            setTimeout(function() { toast.remove(); }, 7000);
                        },
                        height: 'auto'
                    });
                    calendarioCronograma.render();
                    calendarioCronograma.addEventSource(eventos);
                } else {
                    calendarioCronograma.removeAllEventSources();
                    calendarioCronograma.addEventSource(eventos);
                }
            };

            /* ── Oferta sub-tabs (contable) ────────────────────────── */
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.est-oferta-tab-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const parentTab = this.closest('.est-tabs-body');
                        if (!parentTab || !targetId) return;
                        parentTab.querySelectorAll('.est-oferta-tab-btn').forEach(b => b
                            .classList.remove('active'));
                        this.classList.add('active');
                        parentTab.querySelectorAll('.est-oferta-content').forEach(c => c
                            .classList.remove('active'));
                        const el = document.getElementById(targetId);
                        if (el) el.classList.add('active');
                    });
                });
                
                // Pagos tabs (nuevo)
                document.querySelectorAll('.pagos-tab-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const parentWrapper = this.closest('.pagos-tabs-wrapper');
                        if (!parentWrapper || !targetId) return;
                        parentWrapper.querySelectorAll('.pagos-tab-btn').forEach(b => b
                            .classList.remove('active'));
                        this.classList.add('active');
                        parentWrapper.querySelectorAll('.pagos-oferta-content').forEach(c => c
                            .classList.remove('active'));
                        const el = document.getElementById(targetId);
                        if (el) el.classList.add('active');
                    });
                });
            });

            /* ── Ver detalle pago (contable) ───────────────────────── */
            document.querySelectorAll('.btn-ver-detalle-pago').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const pagosData = JSON.parse(this.getAttribute('data-pagos'));
                    const listaPagos = document.getElementById('lista-pagos');
                    const container = document.getElementById('detalle-pago-container');

                    if (pagosData.length === 1) {
                        listaPagos.style.display = 'none';
                        container.style.display = 'block';
                        mostrarDetallePago(pagosData[0]);
                    } else {
                        listaPagos.style.display = 'block';
                        container.style.display = 'none';
                        listaPagos.innerHTML = '';
                        pagosData.forEach(function(pago) {
                            const item = document.createElement('div');
                            item.className =
                                'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                            item.style.cursor = 'pointer';
                            item.innerHTML =
                                '<div><div class="fw-bold text-orange">' + (pago.recibo ||
                                '—') + '</div>' +
                                '<small class="text-muted">' + (pago.fecha ? new Date(pago
                                    .fecha).toLocaleDateString('es-ES') : '') +
                                '</small></div>' +
                                '<div class="text-end"><div class="fw-bold">Bs. ' + parseFloat(
                                    pago.monto).toFixed(2) + '</div>' +
                                '<small class="text-muted">' + pago.metodo + '</small></div>';
                            item.addEventListener('click', function() {
                                listaPagos.style.display = 'none';
                                container.style.display = 'block';
                                mostrarDetallePago(pago);
                            });
                            listaPagos.appendChild(item);
                        });
                        const totalGeneral = pagosData.reduce((s, p) => s + parseFloat(p.monto), 0);
                        const totalItem = document.createElement('div');
                        totalItem.className = 'list-group-item bg-success text-white';
                        totalItem.innerHTML =
                            '<div class="fw-bold">Total Acumulado</div><div class="fw-bold">Bs. ' +
                            totalGeneral.toFixed(2) + '</div>';
                        listaPagos.appendChild(totalItem);
                    }

                    const modalEl = document.getElementById('modalVerDetallePago');
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    document.body.classList.add('modal-open');
                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                    setTimeout(function() { backdrop.classList.add('show'); }, 10);
                });
            });

            function closePagoModal() {
                const modalEl = document.getElementById('modalVerDetallePago');
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(function(b) { b.remove(); });
            }

            document.getElementById('btn-volver-lista')?.addEventListener('click', function() {
                document.getElementById('lista-pagos').style.display = 'block';
                document.getElementById('detalle-pago-container').style.display = 'none';
                closePagoModal();
            });

            document.getElementById('modalVerDetallePago').querySelectorAll('[data-bs-dismiss="modal"], .btn-close').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    closePagoModal();
                });
            });

            document.getElementById('modalVerDetallePago').querySelector('.btn-cerrar')?.addEventListener('click', function() {
                closePagoModal();
            });

            function mostrarDetallePago(pago) {
                document.getElementById('detalle-recibo').textContent = pago.recibo || '—';
                document.getElementById('detalle-fecha').textContent = pago.fecha ? new Date(pago.fecha)
                    .toLocaleDateString('es-ES') : '—';
                document.getElementById('detalle-metodo').textContent = pago.metodo || '—';
                document.getElementById('detalle-estudiante').textContent = pago.estudiante || '—';
                document.getElementById('detalle-programa').textContent = pago.programa || '—';
                document.getElementById('detalle-plan').textContent = pago.plan || '—';

                const tbody = document.getElementById('detalle-tabla');
                tbody.innerHTML = '';
                let totalDetalle = 0;
                if (pago.cuotas && pago.cuotas.length > 0) {
                    pago.cuotas.forEach(function(c, i) {
                        totalDetalle += parseFloat(c.monto);
                        const tr = document.createElement('tr');
                        tr.innerHTML = '<td>' + (i + 1) + '</td><td>' + (c.nombre || 'Cuota #' + (c.n_cuota ||
                                i + 1)) + '</td><td class="text-end">Bs. ' + parseFloat(c.monto).toFixed(2) +
                            '</td>';
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center">Sin cuotas</td></tr>';
                }
                document.getElementById('detalle-total').textContent = 'Bs. ' + totalDetalle.toFixed(2);

                const descContainer = document.getElementById('detalle-descuento-container');
                if (pago.descuento && parseFloat(pago.descuento) > 0) {
                    descContainer.style.display = 'block';
                    document.getElementById('detalle-descuento').textContent = 'Bs. ' + parseFloat(pago.descuento)
                        .toFixed(2);
                } else {
                    descContainer.style.display = 'none';
                }
                document.getElementById('detalle-trabajador').textContent = pago.trabajador || '—';
                document.getElementById('detalle-depositante').textContent = pago.estudiante || '—';

                const btnPdf = document.getElementById('btn-descargar-pdf');
                const footerBtns = btnPdf ? btnPdf.parentNode : null;
                if (footerBtns) {
                    const existingComprobanteBtn = footerBtns.querySelector('.btn-comprobante');
                    if (existingComprobanteBtn) existingComprobanteBtn.remove();

                    if (pago.comprobante) {
                        const btnComprobante = document.createElement('a');
                        btnComprobante.href = pago.comprobante.url;
                        btnComprobante.target = '_blank';
                        btnComprobante.className = 'btn text-white btn-comprobante me-2';
                        btnComprobante.style.background = '#059669';
                        btnComprobante.innerHTML = '<i class="ri-file-image-line"></i> Ver Comprobante';
                        footerBtns.insertBefore(btnComprobante, btnPdf);
                    }
                }

                if (btnPdf && pago && pago.id) {
                    btnPdf.href = '/virtual/recibo/' + pago.id + '/pdf';
                }
            }

            /* ── Moodle SSO helper ─────────────────────────────────── */
            function openMoodleSso(targetUrl) {
                window.open('/estudiante/moodle-sso?target=' + encodeURIComponent(targetUrl), '_blank');
            }

            /* ── Actividades (tab académico) ───────────────────────── */
            $(document).on('click', '.btn-ver-actividades', function() {
                const moduloId = $(this).data('modulo');
                const panelId = $(this).data('panel');
                const $panel = $('#' + panelId);

                if ($panel.is(':visible')) {
                    $panel.slideUp(200);
                    $(this).html('<i class="ri-eye-line"></i> Ver actividades');
                    return;
                }
                $panel.slideDown(200);
                $(this).html('<i class="ri-eye-off-line"></i> Ocultar');
                if (loaded[moduloId]) return;

                $.get('/virtual/actividades/' + moduloId)
                    .done(function(r) {
                        $('#spinner-mod-' + moduloId).hide();
                        if (!r.success) {
                            $('#contenido-mod-' + moduloId).html(
                                '<p style="color:#dc3545;font-size:.85rem;"><i class="ri-close-circle-line"></i> ' +
                                escHtml(r.message) + '</p>');
                            return;
                        }
                        renderActividades(moduloId, r.contenidos, r.calificaciones);
                        loaded[moduloId] = true;
                    })
                    .fail(function() {
                        $('#spinner-mod-' + moduloId).hide();
                        $('#contenido-mod-' + moduloId).html(
                            '<p style="color:#dc3545;font-size:.85rem;"><i class="ri-wifi-off-line"></i> Error al conectar con Moodle.</p>'
                            );
                    });
            });

            function renderActividades(moduloId, contenidos, calificaciones) {
                const gradeMap = {};
                if (calificaciones && Array.isArray(calificaciones)) {
                    calificaciones.forEach(function(item) {
                        if (item.cmid) gradeMap[item.cmid] = item;
                    });
                }
                let html = '';
                if (!contenidos || contenidos.length === 0) {
                    html =
                        '<p style="color:#6c757d;font-size:.85rem;"><i class="ri-information-line"></i> No hay contenido disponible.</p>';
                } else {
                    contenidos.forEach(function(seccion) {
                        const modulos = seccion.modules || [];
                        if (modulos.length === 0) return;
                        html += '<div class="est-act-section">' + escHtml(seccion.name || 'Sección') + '</div>';
                        modulos.forEach(function(mod) {
                            if (mod.modname === 'label') {
                                if (mod.description) html += '<div class="est-label-content">' + mod
                                    .description + '</div>';
                                return;
                            }
                            const grade = gradeMap[mod.id];
                            const nota = grade ? grade.gradeformatted : null;
                            const icono = iconoModulo(mod.modname);
                            let badge = '';
                            if (nota && nota !== '-') {
                                const numVal = parseFloat(nota);
                                if (!isNaN(numVal)) {
                                    badge = numVal >= 51 ?
                                        '<span class="est-nota-badge aprobado">' + escHtml(nota) +
                                        '</span>' :
                                        '<span class="est-nota-badge reprobado">' + escHtml(nota) +
                                        '</span>';
                                } else {
                                    badge = '<span class="est-nota-badge pendiente">' + escHtml(nota) +
                                        '</span>';
                                }
                            } else {
                                badge = '<span class="est-nota-badge pendiente">Pendiente</span>';
                            }
                            const url = mod.url ?
                                '<a href="#" class="moodle-link" data-target="' + escHtml(mod.url) +
                                '" style="font-size:.72rem;color:#fc7b04;margin-left:.5rem;"><i class="ri-external-link-line"></i></a>' :
                                '';
                            html += '<div class="est-act-item"><div class="est-act-item-name">' +
                                icono + ' ' + escHtml(mod.name) + url + '<small>' + etiquetaModulo(mod
                                    .modname) + '</small></div>' + badge + '</div>';
                        });
                    });
                }
                $('#contenido-mod-' + moduloId).html(html);
            }

            function iconoModulo(modname) {
                const map = {
                    assign: '<i class="ri-file-text-line" style="color:#3b82f6;"></i>',
                    quiz: '<i class="ri-question-line" style="color:#f97316;"></i>',
                    forum: '<i class="ri-discuss-line" style="color:#8b5cf6;"></i>',
                    resource: '<i class="ri-file-line" style="color:#6c757d;"></i>',
                    url: '<i class="ri-links-line" style="color:#10b981;"></i>',
                    page: '<i class="ri-pages-line" style="color:#6366f1;"></i>',
                    label: '<i class="ri-text" style="color:#0ea5e9;"></i>'
                };
                return map[modname] || '<i class="ri-apps-line" style="color:#6c757d;"></i>';
            }

            function etiquetaModulo(modname) {
                const map = {
                    assign: 'Tarea',
                    quiz: 'Cuestionario',
                    forum: 'Foro',
                    resource: 'Archivo',
                    url: 'Enlace',
                    page: 'Página',
                    label: 'Área de texto y medios'
                };
                return map[modname] || modname;
            }

            function escHtml(str) {
                return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(
                    /"/g, '&quot;');
            }

            $(document).on('click', '.moodle-link', function(e) {
                e.preventDefault();
                openMoodleSso($(this).data('target'));
            });

        /* ══════════════════════════════════════════════
           TAB PAGOS — funciones globales
        ══════════════════════════════════════════════ */
        var estCompInscripcionId = null;

        // Event listener para el file input del comprobante
        document.getElementById('estCompArchivo')?.addEventListener('change', function(e) {
            var file = e.target.files[0];
            var area = document.getElementById('comprobanteFileArea');
            if (file) {
                area.classList.add('has-file');
                area.innerHTML = '<i class="ri-file-check-line"></i><span>' + file.name + '</span><small>Listo para subir</small>';
            } else {
                area.classList.remove('has-file');
                area.innerHTML = '<i class="ri-upload-cloud-line"></i><span>Haz clic para seleccionar el archivo</span><small>JPG, PNG o PDF — máx. 5 MB</small>';
            }
        });

        function estMostrarToast(tipo, mensaje) {
            var bg    = tipo === 'success' ? '#16a34a' : tipo === 'warning' ? '#d97706' : '#dc2626';
            var icono = tipo === 'success' ? 'ri-checkbox-circle-line' : tipo === 'warning' ? 'ri-alert-line' : 'ri-close-circle-line';
            var t = document.createElement('div');
            t.style.cssText = 'background:' + bg + ';color:#fff;padding:.75rem 1.25rem;border-radius:10px;font-size:.85rem;font-weight:500;display:flex;align-items:center;gap:.6rem;box-shadow:0 8px 24px rgba(0,0,0,.18);max-width:360px;';
            t.innerHTML = '<i class="' + icono + '" style="font-size:1.1rem;flex-shrink:0;"></i><span>' + String(mensaje).replace(/</g,'&lt;') + '</span>';
            var c = document.getElementById('est-toast-container');
            if (c) c.appendChild(t);
            setTimeout(function() { t.style.opacity='0'; t.style.transition='opacity .4s'; setTimeout(function(){ t.remove(); }, 400); }, 4000);
        }

        function estAbrirModal(inscripcionId, programa, plan) {
            estCompInscripcionId = inscripcionId;
            document.getElementById('estCompPrograma').textContent = programa || '—';
            document.getElementById('estCompPlan').textContent = 'Plan: ' + (plan || '—');
            document.getElementById('estCompArchivo').value = '';
            document.getElementById('estCompObservaciones').value = '';
            
            // Reset file area visual
            var area = document.getElementById('comprobanteFileArea');
            area.classList.remove('has-file');
            area.innerHTML = '<i class="ri-upload-cloud-line"></i><span>Haz clic para seleccionar el archivo</span><small>JPG, PNG o PDF — máx. 5 MB</small>';

            var cuotasContainer = document.getElementById('estCompCuotasContainer');
            var cuotasLoading   = document.getElementById('estCompCuotasLoading');
            cuotasContainer.style.display = 'none';
            cuotasContainer.innerHTML = '';
            cuotasLoading.style.display = 'block';

            var modalEl = document.getElementById('modalEstComprobante');
            modalEl.style.display = 'block';
            modalEl.classList.add('show');
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden';
            if (!document.getElementById('est-modal-backdrop')) {
                var bd = document.createElement('div');
                bd.id = 'est-modal-backdrop';
                bd.className = 'modal-backdrop fade show';
                document.body.appendChild(bd);
            }

            fetch('/virtual/inscripcion/' + inscripcionId + '/cuotas', { headers: { 'Accept': 'application/json' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    cuotasLoading.style.display = 'none';
                    if (!data.success) {
                        cuotasContainer.innerHTML = '<p class="text-muted" style="font-size:.82rem;">No se pudieron cargar las cuotas.</p>';
                        cuotasContainer.style.display = 'block';
                        return;
                    }
                    var grupo = data.grupo;
                    var html = '<div style="border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;">'
                        + '<div style="background:#f8fafc;padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;font-weight:600;font-size:.82rem;color:#475569;"><i class="ri-bank-card-line me-1"></i>'
                        + String(grupo.plan_nombre || '').replace(/</g,'&lt;')
                        + '</div><div style="padding:.75rem;">';

                    if (!grupo.cuotas.length) {
                        html += '<p style="color:#16a34a;font-size:.82rem;margin:0;"><i class="ri-checkbox-circle-line me-1"></i>Todas las cuotas están al día.</p>';
                    } else {
                        grupo.cuotas.forEach(function(c) {
                            var eColor = c.estado === 'Pagado' ? '#16a34a' : c.estado === 'Vencido' ? '#dc2626' : '#f59e0b';
                            html += '<label style="display:flex;align-items:center;gap:.75rem;padding:.5rem .25rem;border-bottom:1px solid #f8fafc;cursor:pointer;">'
                                + '<input type="checkbox" name="est_cuotas[]" value="' + c.id + '" style="width:15px;height:15px;accent-color:#fc7b04;flex-shrink:0;">'
                                + '<div style="flex:1;">'
                                + '<div style="font-size:.83rem;font-weight:500;color:#1e293b;">' + String(c.nombre||'').replace(/</g,'&lt;') + ' #' + c.n_cuota + '</div>'
                                + '<div style="font-size:.72rem;color:#64748b;">Bs ' + c.monto_bs + ' · Pendiente Bs ' + c.pago_pendiente_bs + ' · Vence: ' + (c.fecha_vencimiento || '—') + '</div>'
                                + '</div>'
                                + '<span style="font-size:.7rem;font-weight:600;color:' + eColor + ';background:' + eColor + '1a;padding:.15rem .45rem;border-radius:4px;">' + String(c.estado||'').replace(/</g,'&lt;') + '</span>'
                                + '</label>';
                        });
                    }
                    html += '</div></div>';
                    cuotasContainer.innerHTML = html;
                    cuotasContainer.style.display = 'block';
                })
                .catch(function() {
                    cuotasLoading.style.display = 'none';
                    cuotasContainer.innerHTML = '<p class="text-muted" style="font-size:.82rem;">Error al cargar cuotas.</p>';
                    cuotasContainer.style.display = 'block';
                });
        }

        function estCerrarModal() {
            var modalEl = document.getElementById('modalEstComprobante');
            if (!modalEl) return;
            modalEl.style.display = 'none';
            modalEl.classList.remove('show');
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            var bd = document.getElementById('est-modal-backdrop');
            if (bd) bd.remove();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Cerrar modal con botones Cancelar / X
            document.querySelectorAll('#modalEstComprobante [data-bs-dismiss="modal"], #modalEstComprobante .btn-close').forEach(function(btn) {
                btn.addEventListener('click', estCerrarModal);
            });

            var btnEstEnviar = document.getElementById('btnEstEnviarComprobante');
            if (!btnEstEnviar) return;
            btnEstEnviar.addEventListener('click', function() {
                if (!estCompInscripcionId) return;

                var archivo = document.getElementById('estCompArchivo').files[0];
                if (!archivo) { estMostrarToast('error', 'Debes seleccionar un archivo.'); return; }
                if (archivo.size > 5 * 1024 * 1024) { estMostrarToast('error', 'El archivo supera el límite de 5 MB.'); return; }

                var cuotasChecked = Array.from(document.querySelectorAll('#estCompCuotasContainer input[name="est_cuotas[]"]:checked'));
                if (!cuotasChecked.length) { estMostrarToast('error', 'Selecciona al menos una cuota.'); return; }

                var formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('inscripcion_id', estCompInscripcionId);
                formData.append('archivo', archivo);
                formData.append('observaciones', document.getElementById('estCompObservaciones').value);
                cuotasChecked.forEach(function(cb) { formData.append('cuotas[]', cb.value); });

                btnEstEnviar.disabled = true;
                btnEstEnviar.innerHTML = '<i class="ri-loader-4-line me-1"></i>Enviando...';

                fetch('{{ route("virtual.comprobante.subir") }}', { method: 'POST', body: formData })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        btnEstEnviar.disabled = false;
                        btnEstEnviar.innerHTML = '<i class="ri-upload-cloud-line me-1"></i> Enviar Comprobante';
                        if (data.success) {
                            estCerrarModal();
                            estMostrarToast('success', data.mensaje || 'Comprobante enviado correctamente.');
                            setTimeout(function() { window.location.reload(); }, 1800);
                        } else {
                            estMostrarToast('error', data.message || 'Error al enviar el comprobante.');
                        }
                    })
                    .catch(function() {
                        btnEstEnviar.disabled = false;
                        btnEstEnviar.innerHTML = '<i class="ri-upload-cloud-line me-1"></i> Enviar Comprobante';
                        estMostrarToast('error', 'Error de conexión.');
                    });
            });
        });

        function cambiarPerfil(perfil) {
            fetch('{{ route('virtual.cambiarPerfil') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ perfil: perfil })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        function initCalendarDocente(events) {
            if (!events || events.length === 0) return;
            const calendarEl = document.getElementById('calendar-docente');
            if (!calendarEl) return;
            if (typeof FullCalendar === 'undefined') return;

            calendarioDocente = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    list: 'Lista'
                },
                locale: 'es',
                height: 500,
                events: events,
                eventDisplay: 'block',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                dayMaxEvents: true,
                nowIndicator: true,
                eventDidMount: function(info) {
                    info.el.style.borderRadius = '8px';
                    info.el.style.marginBottom = '2px';
                }
            });

            calendarioDocente.render();
        }

        @if ($esDocente && $perfilActivo === 'docente')
        document.addEventListener('DOMContentLoaded', function() {
            if (datosHorariosDocente && datosHorariosDocente.length > 0) {
                initCalendarDocente(datosHorariosDocente);
            }
        });
        @endif
    </script>
@endsection
