@extends('layouts.master')
@section('title')
    Detalle Estudiante
@section('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

        :root {
            --est-primary: #fc7b04;
            --est-primary-light: rgba(252, 123, 4, 0.1);
            --est-primary-dark: #c96004;
            --est-accent: #fc7b04;
            --est-surface: var(--d-card-bg, #f8fafc);
            --est-border: var(--d-card-border, #e2e8f0);
            --est-text: var(--d-body, #1e293b);
            --est-text-muted: var(--d-muted, #64748b);
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
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 8px -2px rgba(0, 0, 0, 0.08), 0 2px 4px -2px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 10px 25px -4px rgba(0, 0, 0, 0.1), 0 4px 8px -4px rgba(0, 0, 0, 0.06);
        }

        .detalle-icon {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .text-orange {
            color: #fc7b04;
        }

        .text-success {
            color: #22c55e;
        }

        .bg-gradient-orange {
            background: linear-gradient(135deg, #fc7b04, #c96004);
        }

        .estudiante-page {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--est-text);
        }

        .est-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
            padding: 20px 28px;
            background: linear-gradient(135deg, #391b04 0%, #5c2d0a 50%, #c96004 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .est-header::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -5%;
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, rgba(252, 123, 4, 0.3) 0%, transparent 70%);
            border-radius: 50%;
        }

        .est-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
            color: white;
        }

        .est-header p {
            margin: 4px 0 0;
            opacity: 0.8;
            font-size: 0.85rem;
            position: relative;
            z-index: 1;
            color: white;
        }

        .est-header-actions {
            display: flex;
            gap: 8px;
            position: relative;
            z-index: 1;
        }

        .est-header-btn {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 8px 18px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.82rem;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(4px);
            cursor: pointer;
        }

        .est-header-btn:hover {
            background: white;
            color: var(--est-primary);
            border-color: white;
            transform: translateY(-1px);
        }

        .est-tabs-card {
            background: var(--d-card, white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--d-card-border, #e2e8f0);
            box-shadow: var(--d-card-shadow, var(--shadow-sm));
            overflow: hidden;
        }

        .est-tabs-nav {
            display: flex;
            overflow-x: auto;
            scrollbar-width: none;
            padding: 0;
            background: var(--d-header-bg, var(--est-surface));
            border-bottom: 1px solid var(--d-header-border, var(--est-border));
        }

        .est-tabs-nav::-webkit-scrollbar {
            display: none;
        }

        .est-tab-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 16px 22px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--est-text-muted);
            border: none;
            background: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
            position: relative;
        }

        .est-tab-btn:hover:not(.active) {
            color: var(--est-primary);
            background: var(--est-primary-light);
        }

        .est-tab-btn.active {
            color: var(--est-primary);
            border-bottom-color: var(--est-primary);
            background: var(--d-card, white);
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

        .est-data-card {
            background: var(--d-card, white);
            border-radius: var(--radius-md);
            border: 1px solid var(--d-card-border, #e2e8f0);
            box-shadow: var(--d-card-shadow, var(--shadow-sm));
            overflow: visible;
        }

        .est-data-card-header {
            padding: 14px 18px;
            border-bottom: 1px solid var(--d-card-border, #e2e8f0);
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--d-header-bg, var(--est-surface));
        }

        .est-data-card-icon {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
        }

        .est-data-card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0;
            color: var(--est-text);
        }

        .est-data-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
        }

        .est-data-row+.est-data-row {
            border-top: 1px solid var(--d-row-border, #e2e8f0);
        }

        .est-data-row-icon {
            width: 30px;
            height: 30px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            flex-shrink: 0;
        }

        .est-data-row-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--est-text-muted);
            font-weight: 700;
        }

        .est-data-row-value {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--est-text);
        }

        .est-stat-card {
            background: var(--d-card, white);
            border-radius: var(--radius-md);
            border: 1px solid var(--d-card-border, #e2e8f0);
            box-shadow: var(--d-card-shadow, var(--shadow-sm));
            overflow: hidden;
            transition: all 0.25s ease;
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

        .est-stat-label {
            font-size: 0.72rem;
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

        .est-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .est-table thead th {
            background: var(--d-thead-bg, var(--est-surface));
            padding: 12px 16px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--d-thead-color, var(--est-text-muted));
            border-bottom: 1px solid var(--d-header-border, var(--est-border));
        }

        .est-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--d-row-border, #e2e8f0);
            vertical-align: middle;
            font-size: 0.85rem;
        }

        .est-table tbody tr:last-child td {
            border-bottom: none;
        }

        .est-table tbody tr:hover td {
            background: var(--d-row-hover, var(--est-primary-light));
        }

        .estado-badge-est {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .estado-badge-est.pagado,
        .estado-badge-est.concluido {
            background: var(--est-success-light);
            color: var(--est-success);
        }

        .estado-badge-est.pendiente,
        .estado-badge-est.en-desarrollo {
            background: var(--est-warning-light);
            color: var(--est-warning);
        }

        .estado-badge-est.vencido {
            background: var(--est-danger-light);
            color: var(--est-danger);
        }

        .estado-badge-est.inscrito {
            background: var(--est-success-light);
            color: var(--est-success);
        }

        .estado-badge-est.pre-inscrito {
            background: var(--est-warning-light);
            color: var(--est-warning);
        }

        .estado-badge-est.verificado {
            background: var(--est-success-light);
            color: var(--est-success);
        }

        .estado-badge-est.no-verificado {
            background: var(--est-warning-light);
            color: var(--est-warning);
        }

        .estado-badge-est.sin-subir {
            background: var(--est-danger-light);
            color: var(--est-danger);
        }

        .est-empty-state {
            padding: 40px 24px;
            text-align: center;
            background: var(--d-card, white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--d-card-border, #e2e8f0);
            box-shadow: var(--d-card-shadow, var(--shadow-sm));
        }

        .est-empty-state i {
            font-size: 2rem;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .est-empty-state h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: var(--est-text);
            margin-bottom: 4px;
        }

        .est-empty-state p {
            color: var(--est-text-muted);
            font-size: 0.85rem;
            margin: 0;
        }

        .doc-card {
            background: var(--d-card, white);
            border-radius: var(--radius-md);
            border: 1px solid var(--d-card-border, #e2e8f0);
            box-shadow: var(--d-card-shadow, var(--shadow-sm));
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            transition: all 0.2s ease;
        }

        .doc-card:hover {
            border-color: var(--est-primary);
            box-shadow: var(--shadow-md);
        }

        .doc-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .doc-info {
            flex: 1;
            min-width: 0;
        }

        .doc-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--est-text);
            margin-bottom: 2px;
        }

        .doc-meta {
            font-size: 0.75rem;
            color: var(--est-text-muted);
        }

        .doc-status {
            flex-shrink: 0;
        }

        @media (max-width: 767.98px) {
            .est-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .est-tabs-body {
                padding: 16px;
            }

            .est-tabs-nav {
                padding: 0 12px;
            }

            .est-tab-btn {
                padding: 12px 14px;
                font-size: 0.78rem;
            }
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
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--est-text-muted);
            background: var(--d-card-bg, white);
            border: 1px solid var(--d-card-border, #e2e8f0);
            border-radius: var(--radius-sm);
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
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

        /* ═══════════════════════════════════════
           Carnet de Identificación (tab Personal)
        ═══════════════════════════════════════ */

        .est-ci-wrap {
            background: #fff;
            border: 1.5px solid var(--est-border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 6px 30px rgba(0,0,0,.07);
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
            color: rgba(255,255,255,.75);
        }

        .est-ci-foto {
            width: 140px;
            height: 175px;
            border-radius: 10px;
            border: 3px solid rgba(255,255,255,.45);
            background: rgba(255,255,255,.12);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,.3);
            flex-shrink: 0;
        }

        .est-ci-foto img { width: 100%; height: 100%; object-fit: cover; }

        .est-ci-initials {
            font-family: 'Outfit', sans-serif;
            font-size: 2.6rem;
            font-weight: 800;
            color: rgba(255,255,255,.7);
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
            background: rgba(255,255,255,.11);
            border-radius: 6px;
            color: rgba(255,255,255,.9);
        }

        .est-ci-qd-item i    { color: rgba(255,255,255,.65); font-size: .82rem; }
        .est-ci-qd-label     { color: rgba(255,255,255,.58); font-size: .63rem; text-transform: uppercase; letter-spacing: .03em; }
        .est-ci-qd-val       { color: #fff; font-weight: 600; text-align: right; font-size: .72rem; }

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

        .est-ci-badge-activo   { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .est-ci-badge-inactivo { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

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

        .est-ci-section-title i { color: var(--est-accent); }

        .est-ci-datos-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: .65rem;
        }

        .est-ci-dato         { display: flex; flex-direction: column; gap: .1rem; }
        .est-ci-dato.est-ci-full { grid-column: 1 / -1; }

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

        .est-ci-right-header i { color: var(--est-accent); font-size: 1rem; }

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
            width: 28px; height: 28px;
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
            color: rgba(255,255,255,.8);
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .est-ci-bottom-bar i { margin-right: 4px; }

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

        @media (max-width: 575px) {
            .est-ci-body { grid-template-columns: 1fr; }
            .est-ci-left  { flex-direction: row; flex-wrap: wrap; align-items: flex-start; gap: .75rem; }
            .est-ci-foto  { width: 100px; height: 125px; }
            .est-ci-right { grid-column: 1; }
        }

        /* ═══════════════════════════════════════
           Formación Académica (tabla con toggle docs)
        ═══════════════════════════════════════ */

        .est-table-wrap {
            border: 1px solid var(--est-border);
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
        .est-action-principal { background: var(--est-primary-light); color: var(--est-primary); }
        .est-action-principal:hover { background: var(--est-primary); color: white; }
        .est-action-del { background: var(--est-danger-light); color: var(--est-danger); }
        .est-action-del:hover { background: var(--est-danger); color: white; }

        .est-docs-panel {
            background: var(--est-surface);
            border-top: 1px solid var(--est-border);
            border-bottom: 1px solid var(--est-border);
        }

        .est-docs-inner {
            padding: 1rem 1.25rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }

        @media (max-width: 640px) {
            .est-docs-inner { grid-template-columns: 1fr; }
        }

        .est-doc-card {
            background: #fff;
            border: 1px solid var(--est-border);
            border-radius: var(--radius-md);
            padding: 0.9rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            transition: border-color 0.2s;
        }

        .est-doc-card:hover { border-color: var(--est-primary); opacity: 0.85; }

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
            color: var(--est-text);
            margin-bottom: 3px;
        }

        .est-doc-card-status {
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 500;
        }

        .est-doc-card-status.verificado { color: var(--est-success); }
        .est-doc-card-status.pendiente  { color: #d97706; }
        .est-doc-card-status.sin-doc    { color: var(--est-text-muted); }

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

        .est-doc-btn-view   { background: var(--est-primary-light); color: var(--est-primary); }
        .est-doc-btn-view:hover   { background: var(--est-primary); color: #fff; }
        .est-doc-btn-upload { background: var(--est-info-light); color: #0284c7; }
        .est-doc-btn-upload:hover { background: #0284c7; color: #fff; }
        .est-doc-btn-check  { background: var(--est-success-light); color: #15803d; }
        .est-doc-btn-check:hover  { background: #15803d; color: #fff; }
        .est-doc-btn-uncheck { background: rgba(148,163,184,0.1); color: #64748b; }
        .est-doc-btn-uncheck:hover { background: #64748b; color: #fff; }

        /* ══════════════════════════════════════════════════════════
           Modal de pago (cuota / masivo) — paleta cálida del sistema
        ══════════════════════════════════════════════════════════ */
        .pmp-modal .modal-dialog { max-width: 1080px; }
        .pmp-modal .modal-dialog.modal-lg { max-width: 760px; }
        .pmp-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(154,73,4,.18), 0 8px 24px rgba(0,0,0,.08);
        }

        /* Header con gradiente cálido */
        .pmp-header {
            position: relative;
            display: flex; align-items: center; gap: 1rem;
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #5c2a04 0%, #9a4904 45%, #c96004 75%, #fc7b04 100%);
            overflow: hidden;
        }
        .pmp-header::before {
            content: '';
            position: absolute; top: -50%; right: -8%;
            width: 280px; height: 280px; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.14) 0%, transparent 70%);
            pointer-events: none;
        }
        .pmp-header-icon {
            width: 52px; height: 52px;
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.28);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.55rem;
            flex-shrink: 0; z-index: 1;
            box-shadow: 0 4px 14px rgba(0,0,0,.18);
        }
        .pmp-header-text { z-index: 1; min-width: 0; flex: 1; }
        .pmp-header-title {
            font-family: 'Outfit', sans-serif;
            color: #fff; font-weight: 800; font-size: 1.15rem;
            margin: 0 0 2px;
            letter-spacing: -.015em;
        }
        .pmp-header-sub {
            color: rgba(255,255,255,.85);
            font-size: .8rem; font-weight: 500;
            display: block;
            line-height: 1.3;
        }
        .pmp-header-sub.danger { color: rgba(255, 200, 200, .95); }
        .pmp-close-btn {
            z-index: 1; flex-shrink: 0;
            width: 36px; height: 36px;
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.28);
            border-radius: 9px;
            color: #fff; font-size: 1.15rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: background .2s;
        }
        .pmp-close-btn:hover { background: rgba(255,255,255,.3); }

        /* Body */
        .pmp-body {
            padding: 1.25rem 1.5rem;
            background: #faf7f3;
        }
        .pmp-section { margin-bottom: 1.15rem; }
        .pmp-section:last-of-type { margin-bottom: 0; }
        .pmp-section-title {
            display: inline-flex; align-items: center; gap: .4rem;
            font-size: .7rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: .07em;
            color: #c96004;
            margin-bottom: .6rem;
            padding: .3rem .65rem;
            background: rgba(252,123,4,.08);
            border-radius: 6px;
        }
        .pmp-section-title i { font-size: .9rem; }

        /* Labels e inputs */
        .pmp-label {
            display: inline-flex; align-items: center; gap: .35rem;
            font-size: .72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .04em;
            color: #475569;
            margin-bottom: .35rem;
        }
        .pmp-label i { color: #fc7b04; font-size: .85rem; }
        .pmp-label span.opt { color: #94a3b8; font-weight: 600; text-transform: none; letter-spacing: 0; }

        .pmp-input.form-control,
        .pmp-input.form-select {
            background: #fff !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 10px !important;
            padding: .6rem .85rem !important;
            font-size: .88rem !important;
            font-weight: 600 !important;
            color: #0f172a !important;
            transition: border-color .2s, box-shadow .2s !important;
            font-family: inherit !important;
        }
        .pmp-input.form-control:focus,
        .pmp-input.form-select:focus {
            border-color: #fc7b04 !important;
            box-shadow: 0 0 0 4px rgba(252,123,4,.14) !important;
            outline: none !important;
        }
        .pmp-input[readonly] { background: #f8fafc !important; color: #475569 !important; }

        /* Banners (deuda actual / nueva deuda / error) */
        .pmp-banner {
            display: flex; align-items: center; justify-content: space-between;
            gap: .75rem;
            padding: .85rem 1rem;
            border-radius: 12px;
            border: 1px solid transparent;
        }
        .pmp-banner-text {
            display: inline-flex; align-items: center; gap: .45rem;
            font-size: .82rem; font-weight: 700;
        }
        .pmp-banner-text i { font-size: 1.05rem; }
        .pmp-banner-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem; font-weight: 800;
        }
        .pmp-banner.deuda    { background: rgba(220,38,38,.08); border-color: rgba(220,38,38,.22); color: #b91c1c; }
        .pmp-banner.deuda .pmp-banner-val    { color: #b91c1c; }
        .pmp-banner.nueva    { background: rgba(34,197,94,.08); border-color: rgba(34,197,94,.22); color: #15803d; }
        .pmp-banner.nueva .pmp-banner-val    { color: #15803d; }
        .pmp-banner.error    { background: rgba(220,38,38,.06); border-color: rgba(220,38,38,.25); color: #991b1b; }

        /* Lista de cuotas (pago masivo) */
        .pmp-cuotas-wrap {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            max-height: 340px; overflow-y: auto;
        }
        .pmp-cuotas-wrap h6 { display: none; }
        .pmp-cuotas-wrap #tabla-cuotas-pendientes {
            margin: 0;
            font-size: .82rem;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        .pmp-cuotas-wrap #tabla-cuotas-pendientes thead th {
            position: sticky; top: 0; z-index: 2;
            background: linear-gradient(180deg, #f8f5f1 0%, #f1ebe2 100%);
            color: #6b3102;
            font-size: .65rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: .05em;
            padding: .65rem .75rem;
            border-bottom: 2px solid rgba(252,123,4,.18);
            white-space: nowrap;
        }
        .pmp-cuotas-wrap #tabla-cuotas-pendientes tbody td {
            padding: .65rem .75rem;
            border-top: 1px solid #f1f5f9;
            vertical-align: middle;
            color: #1e293b;
        }
        .pmp-cuotas-wrap #tabla-cuotas-pendientes tbody tr:hover td { background: rgba(252,123,4,.04); }
        .pmp-cuotas-wrap #tabla-cuotas-pendientes tbody tr.cuota-incluida td { background: rgba(34,197,94,.06); }
        .pmp-cuotas-wrap #tabla-cuotas-pendientes .badge {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            border-radius: 20px !important;
            padding: .2rem .55rem !important;
            font-size: .65rem !important;
        }

        /* Resumen (pago masivo) */
        .pmp-resumen {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .85rem;
            padding: 1rem 1.15rem;
            background: linear-gradient(135deg, #fff 0%, #fff7ed 100%);
            border: 1px solid rgba(252,123,4,.18);
            border-radius: 12px;
        }
        .pmp-resumen-item { display: flex; align-items: center; gap: .7rem; padding: .35rem 0; }
        .pmp-resumen-item + .pmp-resumen-item { border-left: 1px dashed #fed7aa; padding-left: .85rem; }
        .pmp-resumen-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(220,38,38,.1); color: #b91c1c;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .pmp-resumen-icon.ingresado { background: rgba(37,99,235,.1); color: #2563eb; }
        .pmp-resumen-icon.nueva     { background: rgba(34,197,94,.1); color: #16a34a; }
        .pmp-resumen-lbl {
            font-size: .65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .05em;
            color: #64748b;
        }
        .pmp-resumen-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem; font-weight: 800;
            color: #0f172a; line-height: 1.1;
        }
        .pmp-resumen-val.pmp-deuda     { color: #b91c1c; }
        .pmp-resumen-val.pmp-ingresado { color: #2563eb; }
        .pmp-resumen-val.pmp-nueva     { color: #16a34a; }

        /* Footer */
        .pmp-footer {
            display: flex; align-items: center; justify-content: flex-end;
            gap: .65rem;
            padding: 1rem 1.5rem;
            background: #fff;
            border-top: 1px solid #e2e8f0;
        }
        .pmp-btn {
            display: inline-flex; align-items: center; gap: .35rem;
            padding: .6rem 1.25rem;
            border-radius: 10px;
            font-size: .85rem; font-weight: 700;
            border: 1.5px solid transparent;
            cursor: pointer;
            transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
            font-family: inherit;
        }
        .pmp-btn-cancel { background: #fff; border-color: #e2e8f0; color: #475569; }
        .pmp-btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; }
        .pmp-btn-submit {
            background: linear-gradient(135deg, #9a4904 0%, #fc7b04 100%);
            color: #fff; border-color: #9a4904;
            box-shadow: 0 4px 14px rgba(154,73,4,.32);
        }
        .pmp-btn-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 22px rgba(154,73,4,.42); }

        @media (max-width: 720px) {
            .pmp-resumen { grid-template-columns: 1fr; }
            .pmp-resumen-item + .pmp-resumen-item { border-left: none; border-top: 1px dashed #fed7aa; padding-left: 0; padding-top: .65rem; }
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid py-4">
        @php
            $persona = $estudiante->persona;
        @endphp
        <div class="est-header">
            <div>
                <h1><i class="ri-user-line me-2"></i>Detalle del Estudiante</h1>
                <p>{{ $persona->nombres ?? '' }} {{ $persona->apellido_paterno ?? '' }}
                    {{ $persona->apellido_materno ?? '' }}</p>
            </div>
            <div class="est-header-actions">
                <a href="{{ route('admin.estudiantes.index') }}" class="est-header-btn"><i class="ri-arrow-left-line"></i>
                    Volver</a>
            </div>
        </div>

        <div class="est-tabs-card">
            @include('admin.estudiantes.partials.tabs-header')
            @include('admin.estudiantes.partials.tab-personal')
            @include('admin.estudiantes.partials.tab-documentos')
            @include('admin.estudiantes.partials.tab-academico')
            @include('admin.estudiantes.partials.tab-contable')
        </div>
    </div>

    <div class="modal fade" id="modalReciboPago" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
                <div class="modal-body p-0">
                    <div class="text-center py-4 px-4" style="background: linear-gradient(135deg, #16a34a, #15803d);">
                        <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                            style="width: 64px; height: 64px; background: rgba(255,255,255,0.2);">
                            <i class="ri-checkbox-circle-fill text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="text-white fw-bold mb-1">Pago Registrado</h5>
                        <p class="text-white mb-0" style="opacity:.85; font-size:.875rem;" id="recibo-mensaje-exito">—</p>
                    </div>
                    <div class="px-4 py-3" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">N° Recibo</span>
                            <span class="fw-bold" style="color: #1e293b;" id="recibo-numero">—</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-muted small">Total Pagado</span>
                            <span class="fw-bold text-success" id="recibo-total">—</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-muted small">Saldo Restante</span>
                            <span class="fw-bold" id="recibo-saldo">—</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-muted text-center mb-3" style="font-size:.8rem;">¿Desea generar el comprobante de pago?</p>
                        <div class="d-grid gap-2">
                            <button type="button" id="btn-imprimir-recibo" class="btn fw-semibold"
                                style="background: #fc7b04; color: white; border-radius: 10px; padding: 10px;">
                                <i class="ri-printer-line me-2"></i>Imprimir Recibo
                            </button>
                            <button type="button" id="btn-descargar-recibo" class="btn btn-outline-secondary fw-semibold"
                                style="border-radius: 10px; padding: 10px;">
                                <i class="ri-download-line me-2"></i>Descargar Recibo
                            </button>
                            <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal"
                                style="font-size:.85rem;">
                                Cerrar sin imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Estudio -->
    <div class="modal fade" id="modalAgregarEstudio" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
                <div class="modal-header" style="background: linear-gradient(135deg, #391b04, #5c2d0a); padding: 16px 20px;">
                    <h5 class="modal-title text-white" style="font-family: 'Outfit', sans-serif;">
                        <i class="ri-graduation-cap-line me-2"></i><span id="modalEstudioTitulo">Registrar Estudio</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="background: #f8fafc;">
                    <input type="hidden" id="estudioPersonaId">
                    <input type="hidden" id="estudioEstudianteId">
                    <input type="hidden" id="estudioEditId">
                    
                    <div id="estudioErrorMsg" style="display: none; padding: 10px 14px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; color: #dc2626; font-size: 0.85rem; font-weight: 500; margin-bottom: 16px; align-items: center; gap: 8px;">
                        <i class="ri-alert-circle-line" style="font-size: 1.1rem; flex-shrink: 0;"></i>
                        <span id="estudioErrorText"></span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">Grado Académico *</label>
                            <select class="form-select" id="estudioTipo" style="border-radius: 8px;">
                                <option value="">Seleccionar...</option>
                                @foreach($tiposEstudio as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">Profesión</label>
                            <select class="form-select" id="estudioProfesion" style="border-radius: 8px;">
                                <option value="">Seleccionar...</option>
                                @foreach($profesiones as $prof)
                                    <option value="{{ $prof->id }}">{{ $prof->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">Universidad</label>
                            <select class="form-select" id="estudioUniversidad" style="border-radius: 8px;">
                                <option value="">Seleccionar...</option>
                                @foreach($universidades as $uni)
                                    <option value="{{ $uni->id }}">{{ $uni->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">Estado</label>
                            <select class="form-select" id="estudioEstado" style="border-radius: 8px;">
                                <option value="Concluido">Concluido</option>
                                <option value="En Desarrollo">En Desarrollo</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">Documento Académico (Título/Bachiller)</label>
                            <input type="file" class="form-control" id="inputDocumentoAcademico" accept=".pdf,.png,.jpg,.jpeg" style="border-radius: 8px;">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">Documento Provisión Nacional</label>
                            <input type="file" class="form-control" id="inputProvisionNacional" accept=".pdf,.png,.jpg,.jpeg" style="border-radius: 8px;">
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="estudioPrincipal" value="1" checked>
                                <label class="form-check-label" for="estudioPrincipal" style="font-size: 0.875rem;">
                                    Establecer como estudio principal
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: white; border-top: 1px solid #e2e8f0; padding: 12px 20px;">
                    <button type="button" id="btnGuardarEstudio" class="btn" style="background: #fc7b04; color: white; border-radius: 8px;">
                        <i class="ri-save-line me-1"></i>Guardar
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalVerDocumento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
                <div class="modal-header" style="background: linear-gradient(135deg, #391b04, #5c2d0a); padding: 16px 20px;">
                    <h5 class="modal-title text-white" style="font-family: 'Outfit', sans-serif;">
                        <i class="ri-eye-line me-2"></i>Previsualizar Documento
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="background: #f8fafc; min-height: 400px; display: flex; align-items: center; justify-content: center;">
                    <div id="contenido-documento" class="w-100 h-100 d-flex align-items-center justify-content-center">
                        <div class="text-center text-muted">
                            <i class="ri-loader-4-line" style="font-size: 2rem; animation: spin 1s linear infinite;"></i>
                            <p class="mt-2">Cargando documento...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: white; border-top: 1px solid #e2e8f0; padding: 12px 20px;">
                    <a href="#" id="btn-descargar-doc" class="btn" style="background: #fc7b04; color: white; border-radius: 8px;">
                        <i class="ri-download-line me-1"></i>Descargar
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Confirmar estudio principal --}}
    <div class="modal fade" id="modalConfirmarPrincipal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                                Esta acción cambiará la formación académica principal del estudiante. La anterior dejará de ser principal.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 12px 20px; border-top: 1px solid #e2e8f0; gap: 8px;">
                    <button type="button" class="btn" data-bs-dismiss="modal"
                            style="padding: 8px 16px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; border: 1px solid #e2e8f0; background: #fff; color: #64748b;">
                        Cancelar
                    </button>
                    <button type="button" id="btnConfirmarPrincipal"
                            style="padding: 8px 18px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; border: none; background: linear-gradient(135deg, #fc7b04, #f97316); color: white; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="ri-star-fill"></i> Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .moodle-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            background: #ea4300;
            color: white;
            border-radius: 4px;
            font-size: 0.75rem;
            text-decoration: none;
        }
        .moodle-badge:hover {
            background: #c63800;
            color: white;
        }
    </style>

    <script>
        function formatDateEs(dateStr) {
            if (!dateStr) return '—';
            const d = new Date(dateStr);
            const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
            return d.getDate() + ' de ' + meses[d.getMonth()] + ' del ' + d.getFullYear();
        }

        function toast(tipo, mensaje) {
            const iconMap = {
                success: 'ri-checkbox-circle-line',
                error: 'ri-error-warning-line',
                warning: 'ri-alert-line'
            };
            const el = document.createElement('div');
            el.className = 'toast-notify ' + tipo;
            el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') +
                '"></i></div>' +
                '<div class="toast-body-text"><span>' + mensaje + '</span></div>';
            let c = document.getElementById('toastContainer');
            if (!c) {
                c = document.createElement('div');
                c.id = 'toastContainer';
                c.className = 'toast-container';
                document.body.appendChild(c);
            }
            c.appendChild(el);
            setTimeout(() => {
                el.classList.add('show');
            }, 10);
            setTimeout(() => {
                el.classList.remove('show');
                setTimeout(() => {
                    el.remove();
                }, 300);
            }, 4000);
        }

        function abrirModalRecibo(data) {
            document.getElementById('recibo-mensaje-exito').textContent = data.message || 'Pago registrado correctamente.';
            document.getElementById('recibo-numero').textContent = data.data.recibo || '—';
            document.getElementById('recibo-total').textContent = 'Bs. ' + parseFloat(data.data.total_pagado || 0).toFixed(2);
            document.getElementById('recibo-saldo').textContent = 'Bs. ' + parseFloat(data.data.nueva_deuda || 0).toFixed(2);
            const pagoId = data.data.pago_id;
            document.getElementById('btn-imprimir-recibo').onclick = () =>
                window.open('/admin/estudiantes/recibo/' + pagoId + '/pdf?inline=1', '_blank');
            document.getElementById('btn-descargar-recibo').onclick = () =>
                window.open('/admin/estudiantes/recibo/' + pagoId + '/pdf', '_blank');
            new bootstrap.Modal(document.getElementById('modalReciboPago')).show();
        }

        function validarMontoPago() {
            const btnSubmit = document.querySelector('#formPagarCuota button[type="submit"]');
            const deuda = window.deudaActual || 0;
            const metodo = document.getElementById('pago-metodo').value;
            let montoAPagar = parseFloat(document.getElementById('pago-monto').value) || 0;
            const descuento = parseFloat(document.getElementById('pago-descuento').value) || 0;
            let mensajes = [];
            let valido = true;

            if (metodo === 'Parcial') {
                const efectivo = parseFloat(document.getElementById('pago-efectivo').value) || 0;
                const qr = parseFloat(document.getElementById('pago-qr').value) || 0;
                montoAPagar = efectivo + qr;
            }

            const totalAbonado = montoAPagar + descuento;
            const nuevaDeuda = deuda - totalAbonado;

            if (!metodo) {
                mensajes.push('Seleccione un método de pago');
                valido = false;
            }
            if (montoAPagar <= 0) {
                mensajes.push('Ingrese un monto mayor a 0');
                valido = false;
            }
            if (totalAbonado > deuda) {
                mensajes.push('Total a pagar (Bs. ' + totalAbonado.toFixed(2) + ') excede la deuda (Bs. ' + deuda.toFixed(
                    2) + ')');
                valido = false;
            }

            const containerError = document.getElementById('pago-mensaje-error');
            const textoError = document.getElementById('pago-mensaje-texto');
            if (!valido && mensajes.length > 0) {
                containerError.style.display = 'block';
                textoError.textContent = mensajes.join(' • ');
            } else {
                containerError.style.display = 'none';
            }

            if (btnSubmit) {
                btnSubmit.disabled = !valido;
            }
        }

        let modalPagoInstance = null;

        function switchTab(btn, tabId) {
            document.querySelectorAll('.est-tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.est-tabs-body').forEach(t => t.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.ctb-tab').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    if (!targetId) return;

                    const parentTab = this.closest('.est-tabs-body');
                    if (!parentTab) return;

                    parentTab.querySelectorAll('.ctb-tab').forEach(b => b.classList
                        .remove('active'));
                    this.classList.add('active');
                    parentTab.querySelectorAll('.est-oferta-content').forEach(c => c.classList
                        .remove('active'));

                    const targetContent = document.getElementById(targetId);
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });

            const pagoFecha = document.getElementById('pago-fecha');
            if (pagoFecha && !pagoFecha.value) {
                const nowLaPazDom = new Date(new Date().toLocaleString('en-US', { timeZone: 'America/La_Paz' }));
                const yearDom = nowLaPazDom.getFullYear();
                const monthDom = String(nowLaPazDom.getMonth() + 1).padStart(2, '0');
                const dayDom = String(nowLaPazDom.getDate()).padStart(2, '0');
                pagoFecha.value = yearDom + '-' + monthDom + '-' + dayDom;
            }
        });

document.querySelectorAll('.btn-pagar-cuota').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const cuotaId = this.getAttribute('data-id');
                const cuotaNombre = this.getAttribute('data-nombre');
                const montoPendiente = parseFloat(this.getAttribute('data-monto'));

                document.getElementById('pago-cuota-id').value = cuotaId;
                document.getElementById('pago-cuota-nombre').value = cuotaNombre;
                document.getElementById('pago-cuota-numero').textContent = cuotaNombre;
                document.getElementById('pago-deuda-actual').textContent = 'Bs. ' + montoPendiente.toFixed(2);
                document.getElementById('pago-monto').value = montoPendiente.toFixed(2);
                
                const nowLaPaz = new Date(new Date().toLocaleString('en-US', { timeZone: 'America/La_Paz' }));
                const year = nowLaPaz.getFullYear();
                const month = String(nowLaPaz.getMonth() + 1).padStart(2, '0');
                const day = String(nowLaPaz.getDate()).padStart(2, '0');
                document.getElementById('pago-fecha').value = year + '-' + month + '-' + day;
                
                document.getElementById('pago-metodo').value = '';
                document.getElementById('pago-efectivo').value = '';
                document.getElementById('pago-qr').value = '';
                document.getElementById('pago-descuento').value = '0';
                document.getElementById('campo-efectivo').style.display = 'none';
                document.getElementById('campo-qr').style.display = 'none';

                setTimeout(actualizarNuevaDeuda, 100);

                window.deudaActual = montoPendiente;
                document.getElementById('pago-descuento').value = '0';
                document.getElementById('pago-mensaje-error').style.display = 'none';

                validarMontoPago();

                document.getElementById('pago-metodo').onchange = function() {
                    const campoEfectivo = document.getElementById('campo-efectivo');
                    const campoQr = document.getElementById('campo-qr');
                    const cuentaBancariaContainer = document.getElementById('pago-cuenta-bancaria-container');
                    const referenciaContainer = document.getElementById('pago-referencia-container');

                    if (this.value === 'Parcial') {
                        campoEfectivo.style.display = 'block';
                        campoQr.style.display = 'block';
                        cuentaBancariaContainer.style.display = 'block';
                        referenciaContainer.style.display = 'none';
                    } else if (this.value === 'Qr') {
                        campoEfectivo.style.display = 'none';
                        campoQr.style.display = 'none';
                        cuentaBancariaContainer.style.display = 'block';
                        referenciaContainer.style.display = 'none';
                    } else if (this.value === 'Transferencia') {
                        campoEfectivo.style.display = 'none';
                        campoQr.style.display = 'none';
                        cuentaBancariaContainer.style.display = 'block';
                        referenciaContainer.style.display = 'block';
                    } else {
                        campoEfectivo.style.display = 'none';
                        campoQr.style.display = 'none';
                        cuentaBancariaContainer.style.display = 'none';
                        referenciaContainer.style.display = 'none';
                    }
                    validarMontoPago();
                };

                document.getElementById('pago-efectivo').oninput = function() {
                    const monto = parseFloat(document.getElementById('pago-monto').value) || 0;
                    const efectivo = parseFloat(this.value) || 0;
                    const qrCampo = document.getElementById('pago-qr');
                    const restante = monto - efectivo;
                    qrCampo.value = Math.max(0, restante).toFixed(2);
                };

                document.getElementById('pago-qr').oninput = function() {
                    const monto = parseFloat(document.getElementById('pago-monto').value) || 0;
                    const qr = parseFloat(this.value) || 0;
                    const efectivoCampo = document.getElementById('pago-efectivo');
                    const restante = monto - qr;
                    if (efectivoCampo) efectivoCampo.value = Math.max(0, restante).toFixed(2);
                    validarMontoPago();
                };

                document.getElementById('pago-descuento').addEventListener('input', function() {
                    validarMontoPago();
                    actualizarNuevaDeuda();
                });

                document.getElementById('pago-monto').addEventListener('input', function() {
                    validarMontoPago();
                    actualizarNuevaDeuda();
                });
                document.getElementById('pago-efectivo').addEventListener('input', function() {
                    validarMontoPago();
                    actualizarNuevaDeuda();
                });
                document.getElementById('pago-qr').addEventListener('input', function() {
                    validarMontoPago();
                    actualizarNuevaDeuda();
                });

                function actualizarNuevaDeuda() {
                    const deuda = window.deudaActual || 0;
                    let monto = parseFloat(document.getElementById('pago-monto').value) || 0;
                    const descuento = parseFloat(document.getElementById('pago-descuento').value) || 0;
                    const metodo = document.getElementById('pago-metodo').value;

                    if (metodo === 'Parcial') {
                        monto = (parseFloat(document.getElementById('pago-efectivo').value) || 0) + (
                            parseFloat(document.getElementById('pago-qr').value) || 0);
                    }

                    const totalAbonado = monto + descuento;
                    const nuevaDeuda = Math.max(0, deuda - totalAbonado);
                    document.getElementById('pago-nueva-deuda').textContent = 'Bs. ' + nuevaDeuda.toFixed(2);

                    validarMontoPago();
                }

                if (!modalPagoInstance) {
                    modalPagoInstance = new bootstrap.Modal(document.getElementById('modalPagarCuota'));
                }
                modalPagoInstance.show();
            });
        });

        document.getElementById('formPagarCuota').addEventListener('submit', function(e) {
            e.preventDefault();

            const btnSubmit = this.querySelector('button[type="submit"]');
            const originalText = btnSubmit.innerHTML;

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Procesando...';

            const cuotaId = document.getElementById('pago-cuota-id').value;
            const metodo = document.getElementById('pago-metodo').value;

            const formData = {
                monto: parseFloat(document.getElementById('pago-monto').value),
                fecha_pago: document.getElementById('pago-fecha').value,
                metodo: metodo,
                descuento: parseFloat(document.getElementById('pago-descuento').value) || 0,
                trabajador_cargo_id: document.getElementById('pago-trabajador-cargo').value,
                cuenta_bancaria_id: document.getElementById('pago-cuenta-bancaria')?.value || '',
                referencia: document.getElementById('pago-referencia')?.value || '',
                _token: '{{ csrf_token() }}'
            };

            if (metodo === 'Parcial') {
                formData.efectivo = parseFloat(document.getElementById('pago-efectivo').value) || 0;
                formData.qr = parseFloat(document.getElementById('pago-qr').value) || 0;

                const totalParcial = formData.efectivo + formData.qr;
                if (Math.abs(totalParcial - formData.monto) > 0.01) {
                    alert('La suma de Efectivo + QR debe ser igual al Monto a Pagar.');
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = originalText;
                    return;
                }
            }

            if (['Qr','Transferencia','Parcial'].includes(metodo) && !formData.cuenta_bancaria_id) {
                const errCont = document.getElementById('pago-mensaje-error');
                const errTxt = document.getElementById('pago-mensaje-texto');
                if (errCont && errTxt) {
                    errCont.style.display = 'block';
                    errTxt.textContent = 'Debe seleccionar una cuenta bancaria.';
                }
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = originalText;
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            fetch(`/admin/estudiantes/cuota/${cuotaId}/pagar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    }
                    throw new Error('Respuesta no válida del servidor');
                })
                .then(data => {
                    if (data.success) {
                        if (modalPagoInstance) {
                            modalPagoInstance.hide();
                        }
                        toast('success', data.message || 'Pago registrado correctamente.');
                        if (data.data && data.data.pago_id) {
                            abrirModalRecibo(data);
                        } else {
                            setTimeout(() => {
                                const estudianteId = '{{ $estudiante->id }}';
                                window.location.href = `/admin/estudiantes/${estudianteId}/detalle?tab=contable`;
                            }, 1500);
                        }
                    } else {
                        toast('error', data.message || 'Error al registrar el pago.');
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error en el pago:', error);
                    toast('error', 'Ocurrió un error al procesar el pago. Por favor, intente nuevamente.');
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = originalText;
                });
        });

        document.querySelectorAll('.btn-modal-cancel').forEach(btn => {
            btn.addEventListener('click', function() {
                if (modalPagoInstance) {
                    modalPagoInstance.hide();
                }
            });
        });

        document.getElementById('modalPagarCuota').addEventListener('hidden.bs.modal', function() {
            document.getElementById('formPagarCuota').reset();
            document.getElementById('campo-efectivo').style.display = 'none';
            document.getElementById('campo-qr').style.display = 'none';
        });

        let modalDetalleInstance = null;
        let pagosData = [];
        let pagoActual = null;

        document.querySelectorAll('.btn-ver-detalle-pago').forEach(function(btn) {
            btn.addEventListener('click', function() {
                pagosData = JSON.parse(this.getAttribute('data-pagos'));
                const listaPagos = document.getElementById('lista-pagos');
                const container = document.getElementById('detalle-pago-container');
                const btnVolver = document.getElementById('btn-volver-lista');

                if (pagosData.length === 1) {
                    listaPagos.style.display = 'none';
                    container.style.display = 'block';
                    mostrarDetallePago(pagosData[0]);
                } else {
                    listaPagos.style.display = 'block';
                    container.style.display = 'none';

                    listaPagos.innerHTML = '';
                    pagosData.forEach(function(pago, index) {
                        const item = document.createElement('div');
                        item.style.cssText = 'display: flex; align-items: center; gap: 14px; padding: 14px 16px; cursor: pointer; border-bottom: 1px solid #f1ebe2; transition: all 0.2s ease; background: #fff;';
                        item.onmouseover = function() { this.style.background = 'rgba(252,123,4,0.04)'; };
                        item.onmouseout = function() { this.style.background = '#fff'; };
                        item.innerHTML = '<div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, rgba(252,123,4,0.12), rgba(252,123,4,0.06)); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #c96004; font-size: 1.1rem;"><i class="ri-receipt-line"></i></div>' +
                            '<div style="flex: 1; min-width: 0;"><div style="font-weight: 700; font-size: 0.88rem; color: #c96004;">' + (pago.recibo || '—') + '</div>' +
                            '<div style="font-size: 0.72rem; color: #64748b; display: flex; align-items: center; gap: 6px; margin-top: 2px;"><i class="ri-calendar-line"></i> ' + (pago.fecha ? formatDateEs(pago.fecha) : '') + '</div></div>' +
                            '<div style="text-align: right;"><div style="font-weight: 800; font-size: 1rem; color: #0f172a; font-family: Outfit, sans-serif;">Bs. ' + parseFloat(pago.monto).toFixed(2) + '</div>' +
                            '<div style="font-size: 0.7rem; color: #64748b; display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 6px; background: rgba(252,123,4,0.08); margin-top: 3px; font-weight: 600;"><i class="ri-bank-card-line"></i> ' + pago.metodo + '</div></div>' +
                            '<div style="width: 28px; height: 28px; border-radius: 8px; background: rgba(252,123,4,0.1); display: flex; align-items: center; justify-content: center; color: #c96004; font-size: 0.9rem; flex-shrink: 0;"><i class="ri-arrow-right-s-line"></i></div>';
                        item.addEventListener('click', function() {
                            listaPagos.style.display = 'none';
                            container.style.display = 'block';
                            mostrarDetallePago(pago);
                        });
                        listaPagos.appendChild(item);
                    });

                    const totalGeneral = pagosData.reduce((sum, p) => sum + parseFloat(p.monto), 0);
                    const totalItem = document.createElement('div');
                    totalItem.style.cssText = 'display: flex; align-items: center; gap: 14px; padding: 14px 16px; background: linear-gradient(135deg, #9a4904, #fc7b04); color: #fff; border-radius: 0 0 10px 10px;';
                    totalItem.innerHTML =
                        '<div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.25); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem;"><i class="ri-wallet-3-line"></i></div>' +
                        '<div style="flex: 1; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.02em;">Total Acumulado</div>' +
                        '<div style="font-weight: 800; font-size: 1.1rem; font-family: Outfit, sans-serif;">Bs. ' + totalGeneral.toFixed(2) + '</div>';
                    listaPagos.appendChild(totalItem);
                }

                if (!modalDetalleInstance) {
                    modalDetalleInstance = new bootstrap.Modal(document.getElementById(
                        'modalVerDetallePago'));

                    document.getElementById('btn-volver-lista').addEventListener('click', function() {
                        listaPagos.style.display = 'block';
                        container.style.display = 'none';
                    });
                }
                modalDetalleInstance.show();
            });
        });

        function mostrarDetallePago(pago) {
            pagoActual = pago;
            console.log('mostrarDetallePago - pago:', pago);
            
            document.getElementById('detalle-recibo').textContent = pago.recibo || '—';
            document.getElementById('detalle-fecha').textContent = formatDateEs(pago.fecha);
            document.getElementById('detalle-metodo').textContent = pago.metodo || '—';
            
            document.getElementById('detalle-estudiante').textContent = pago.estudiante || '—';
            document.getElementById('detalle-programa').textContent = pago.programa || '—';
            document.getElementById('detalle-plan').textContent = pago.plan || '—';
            
            const tbody = document.getElementById('detalle-tabla');
            tbody.innerHTML = '';
            let totalDetalle = 0;
            if (pago.cuotas && pago.cuotas.length > 0) {
                pago.cuotas.forEach(function(c, index) {
                    totalDetalle += parseFloat(c.monto);
                    const tr = document.createElement('tr');
                    tr.innerHTML = '<td>' + (index + 1) + '</td><td>' + (c.nombre || 'Cuota #' + (c.n_cuota || index + 1)) + '</td><td class="text-end">Bs. ' + parseFloat(c.monto).toFixed(2) + '</td>';
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">Sin cuotas</td></tr>';
            }
            document.getElementById('detalle-total').textContent = 'Bs. ' + totalDetalle.toFixed(2);
            
            const descuentoContainer = document.getElementById('detalle-descuento-container');
            if (pago.descuento && parseFloat(pago.descuento) > 0) {
                descuentoContainer.style.display = 'block';
                document.getElementById('detalle-descuento').textContent = 'Bs. ' + parseFloat(pago.descuento).toFixed(2);
            } else {
                descuentoContainer.style.display = 'none';
            }
            
            document.getElementById('detalle-trabajador').textContent = pago.trabajador || '—';
            document.getElementById('detalle-depositante').textContent = pago.estudiante || '—';

            const facturaContainer = document.getElementById('detalle-factura-container');
            if (facturaContainer) {
                const facturaEstado = document.getElementById('detalle-factura-estado');
                const btnVerFactura = document.getElementById('btn-ver-factura-detalle');
                if (pago.documento_factura) {
                    facturaContainer.style.display = 'block';
                    facturaEstado.textContent = 'Con factura';
                    btnVerFactura.onclick = function() {
                        verFactura(pago.documento_factura, pago.recibo, pago.estudiante, pago.monto, pago.programa);
                    };
                } else {
                    facturaContainer.style.display = 'none';
                }
            }

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
                btnPdf.href = '/admin/estudiantes/recibo/' + pago.id + '/pdf';
            }
        }

        function verFactura(url, recibo, estudiante, monto, oferta) {
            document.getElementById('facturaReciboNum').textContent = recibo;
            document.getElementById('facturaEstudiante').textContent = estudiante;
            document.getElementById('facturaOferta').textContent = oferta;
            document.getElementById('facturaMonto').textContent = 'Bs. ' + monto;
            document.getElementById('facturaDownloadLink').href = url;
            var container = document.getElementById('facturaFileContainer');
            container.innerHTML = '';
            if (url.match(/\.pdf$/i)) {
                container.innerHTML = '<iframe src="' + url + '" style="width:100%;height:500px;border-radius:10px;border:1px solid #e2e8f0;" frameborder="0"></iframe>';
            } else {
                container.innerHTML = '<img src="' + url + '" style="width:100%;border-radius:10px;border:1px solid #e2e8f0;" alt="Factura"/>';
            }
            var modal = new bootstrap.Modal(document.getElementById('modalVerFactura'));
            modal.show();
        }

        let modalPagoMasivoInstance = null;
        let cuotaData = [];
        let deudaTotalGlobal = 0;

        document.addEventListener('DOMContentLoaded', function() {
            const nowLaPazInit = new Date(new Date().toLocaleString('en-US', { timeZone: 'America/La_Paz' }));
            const yearInit = nowLaPazInit.getFullYear();
            const monthInit = String(nowLaPazInit.getMonth() + 1).padStart(2, '0');
            const dayInit = String(nowLaPazInit.getDate()).padStart(2, '0');
            document.getElementById('pago-masivo-fecha').value = yearInit + '-' + monthInit + '-' + dayInit;

            console.log('Botones pago-masivo encontrados:', document.querySelectorAll('.btn-pago-masivo').length);

            document.querySelectorAll('.btn-pago-masivo').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    console.log('Click en btn-pago-masivo');
                    const inscripcionId = this.getAttribute('data-inscripcion-id');
                    const ofertaNombre = this.getAttribute('data-oferta');
                    const cuotasAttr = this.getAttribute('data-cuotas');
                    console.log('data-cuotas:', cuotasAttr);
                    let cuotasData = [];
                    try {
                        cuotasData = cuotasAttr ? JSON.parse(cuotasAttr) : [];
                    } catch(e) {
                        console.error('Error parseando cuotas:', e, 'raw:', cuotasAttr);
                    }
                    console.log('cuotasData parsed:', cuotasData);
                    abrirModalPagoMasivo(inscripcionId, ofertaNombre, cuotasData);
                });
            });

            document.getElementById('pago-masivo-metodo').addEventListener('change', function() {
                const campoEfectivo = document.getElementById('pago-masivo-campo-efectivo');
                const campoQr = document.getElementById('pago-masivo-campo-qr');
                const cuentaBancariaContainer = document.getElementById('pago-masivo-cuenta-bancaria-container');
                const referenciaContainer = document.getElementById('pago-masivo-referencia-container');
                
                if (this.value === 'Parcial') {
                    campoEfectivo.style.display = 'block';
                    campoQr.style.display = 'block';
                    cuentaBancariaContainer.style.display = 'block';
                    referenciaContainer.style.display = 'none';
                } else if (this.value === 'Qr') {
                    campoEfectivo.style.display = 'none';
                    campoQr.style.display = 'none';
                    cuentaBancariaContainer.style.display = 'block';
                    referenciaContainer.style.display = 'none';
                } else if (this.value === 'Transferencia') {
                    campoEfectivo.style.display = 'none';
                    campoQr.style.display = 'none';
                    cuentaBancariaContainer.style.display = 'block';
                    referenciaContainer.style.display = 'block';
                } else {
                    campoEfectivo.style.display = 'none';
                    campoQr.style.display = 'none';
                    cuentaBancariaContainer.style.display = 'none';
                    referenciaContainer.style.display = 'none';
                }
                actualizarResumenPagoMasivo();
            });

            document.getElementById('pago-masivo-qr').addEventListener('input', function() {
                const monto = parseFloat(document.getElementById('pago-masivo-monto').value) || 0;
                const qr = parseFloat(this.value) || 0;
                const efectivoCampo = document.getElementById('pago-masivo-efectivo');
                const restante = monto - qr;
                if (efectivoCampo) efectivoCampo.value = Math.max(0, restante).toFixed(2);
                actualizarResumenPagoMasivo();
            });

            document.getElementById('pago-masivo-efectivo').addEventListener('input', function() {
                const monto = parseFloat(document.getElementById('pago-masivo-monto').value) || 0;
                const efectivo = parseFloat(this.value) || 0;
                const qrCampo = document.getElementById('pago-masivo-qr');
                const restante = monto - efectivo;
                if (qrCampo) qrCampo.value = Math.max(0, restante).toFixed(2);
                actualizarResumenPagoMasivo();
            });

            document.getElementById('pago-masivo-monto').addEventListener('input', actualizarResumenPagoMasivo);
            document.getElementById('pago-masivo-descuento').addEventListener('input', actualizarResumenPagoMasivo);

            document.getElementById('formPagoMasivo').addEventListener('submit', function(e) {
                e.preventDefault();
                enviarPagoMasivo();
            });
        });

        function abrirModalPagoMasivo(inscripcionId, ofertaNombre, cuotasData) {
            console.log('abrirModalPagoMasivo llamado', {inscripcionId, ofertaNombre, cuotasData});
            const estudianteId = '{{ $estudiante->id }}';
            const trabajadorCargoId = document.getElementById('pago-trabajador-cargo').value;

            document.getElementById('pago-masivo-estudiante-id').value = estudianteId;
            document.getElementById('pago-masivo-inscripcion-id').value = inscripcionId;
            document.getElementById('pago-masivo-trabajador-cargo').value = trabajadorCargoId;
            document.getElementById('pago-masivo-oferta').textContent = ofertaNombre;

            document.getElementById('pago-masivo-monto').value = '';
            document.getElementById('pago-masivo-descuento').value = '0';
            const nowLaPazModal = new Date(new Date().toLocaleString('en-US', { timeZone: 'America/La_Paz' }));
            const yearModal = String(nowLaPazModal.getFullYear());
            const monthModal = String(nowLaPazModal.getMonth() + 1).padStart(2, '0');
            const dayModal = String(nowLaPazModal.getDate()).padStart(2, '0');
            document.getElementById('pago-masivo-fecha').value = yearModal + '-' + monthModal + '-' + dayModal;
            document.getElementById('pago-masivo-metodo').value = '';
            document.getElementById('pago-masivo-efectivo').value = '';
            document.getElementById('pago-masivo-qr').value = '';
            document.getElementById('pago-masivo-campo-efectivo').style.display = 'none';
            document.getElementById('pago-masivo-campo-qr').style.display = 'none';

            mostrarCuotasPendientes(cuotasData);

            if (!modalPagoMasivoInstance) {
                modalPagoMasivoInstance = new bootstrap.Modal(document.getElementById('modalPagoMasivo'));
            }
            modalPagoMasivoInstance.show();
        }

        function mostrarCuotasPendientes(cuotas) {
            console.log('mostrarCuotasPendientes llamado', cuotas);
            const listaCuotas = document.getElementById('pago-masivo-lista-cuotas');
            console.log('listaCuotas element:', listaCuotas);
            let deudaTotal = 0;

            if (!cuotas || cuotas.length === 0) {
                listaCuotas.innerHTML = '<div class="text-center text-muted py-4">No hay cuotas pendientes</div>';
                return;
            }

            let cuotasOrdenadas = [];
            ['Matrícula', 'Colegiatura', 'Certificación'].forEach(function(concBuscada) {
                cuotas.forEach(function(cuota) {
                    let nombreRaw = (cuota.nombre || '').toLowerCase();
                    let esConcepto = false;
                    if (concBuscada === 'Matrícula' && nombreRaw.includes('matr')) esConcepto = true;
                    else if (concBuscada === 'Colegiatura' && nombreRaw.includes('coleg')) esConcepto = true;
                    else if (concBuscada === 'Certificación' && nombreRaw.includes('certif')) esConcepto = true;
                    
                    if (esConcepto) {
                        cuota.concepto = concBuscada;
                        cuotasOrdenadas.push(cuota);
                    }
                });
            });
            
            cuotas.forEach(function(cuota) {
                if (!cuota.concepto) {
                    cuota.concepto = 'Otro';
                    cuotasOrdenadas.push(cuota);
                }
            });

            let html = '<h6 class="text-muted mb-3"><i class="ri-install-line"></i> Cuotas Pendientes</h6>';
            html += '<table class="table table-sm table-hover" id="tabla-cuotas-pendientes"><thead><tr>';
            html += '<th>#</th><th>Concepto</th><th>Cuota</th><th>Monto</th><th>Pendiente</th><th>A Pagar</th><th>Vencimiento</th><th>Estado</th>';
            html += '</tr></thead><tbody>';

            cuotasOrdenadas.forEach(function(cuota, idx) {
                let concepto = cuota.concepto || 'Otro';
                let color = concepto === 'Matrícula' ? '#2563eb' : (concepto === 'Colegiatura' ? '#0891b2' : (concepto === 'Certificación' ? '#d97706' : '#64748b'));
                let pendiente = parseFloat(cuota.pago_pendiente_bs) || 0;
                
                html += '<tr class="cuota-row" data-concepto="' + concepto + '" data-cuota-id="' + cuota.id + '" data-pendiente="' + pendiente + '">';
                html += '<td>' + (idx + 1) + '</td>';
                html += '<td><span class="badge fw-semibold" style="background: ' + color + '20; color: ' + color + ';">' + concepto + '</span></td>';
                html += '<td>' + cuota.nombre + '</td>';
                html += '<td>Bs. ' + parseFloat(cuota.monto_bs).toFixed(2) + '</td>';
                html += '<td class="text-warning fw-bold" data-pendiente="' + pendiente + '">Bs. ' + pendiente.toFixed(2) + '</td>';
                html += '<td class="text-success fw-bold a-pagar-cell">—</td>';
                html += '<td>' + formatDateEs(cuota.fecha_vencimiento) + '</td>';
                html += '<td><span class="badge bg-warning text-dark">' + cuota.estado + '</span></td>';
                html += '</tr>';
                deudaTotal += pendiente;
            });

            html += '</tbody></table>';
            listaCuotas.innerHTML = html;
            window.cuotaData = cuotas;
            window.deudaTotalGlobal = deudaTotal;
            document.getElementById('pago-masivo-deuda-total').textContent = 'Bs. ' + deudaTotal.toFixed(2);
            actualizarResumenPagoMasivo();
        }

        function actualizarResumenPagoMasivo() {
            const monto = parseFloat(document.getElementById('pago-masivo-monto').value) || 0;
            const descuento = parseFloat(document.getElementById('pago-masivo-descuento').value) || 0;
            const metodo = document.getElementById('pago-masivo-metodo').value;

            let montoIngresado = monto;
            if (metodo === 'Parcial') {
                montoIngresado = (parseFloat(document.getElementById('pago-masivo-efectivo').value) || 0) +
                    (parseFloat(document.getElementById('pago-masivo-qr').value) || 0);
            }

            const totalIngresado = Math.round((montoIngresado + descuento) * 100) / 100;
            const nuevaDeuda = Math.max(0, Math.round((window.deudaTotalGlobal - totalIngresado) * 100) / 100);

            document.getElementById('pago-masivo-monto-ingresado').textContent = 'Bs. ' + totalIngresado.toFixed(2);
            document.getElementById('pago-masivo-nueva-deuda').textContent = 'Bs. ' + nuevaDeuda.toFixed(2);

            const tabla = document.getElementById('tabla-cuotas-pendientes');
            if (tabla) {
                const filas = tabla.querySelectorAll('.cuota-row');
                let remaining = totalIngresado;
                filas.forEach(function(fila) {
                    fila.style.background = '';
                    const cellAPagar = fila.querySelector('.a-pagar-cell');
                    if (cellAPagar) cellAPagar.textContent = '—';
                    const pendiente = parseFloat(fila.getAttribute('data-pendiente')) || 0;
                    if (remaining > 0 && pendiente > 0) {
                        const aPagar = Math.round(Math.min(pendiente, remaining) * 100) / 100;
                        if (aPagar > 0) {
                            fila.style.background = 'rgba(34, 197, 94, 0.15)';
                            fila.dataset.seleccionada = '1';
                            if (cellAPagar) cellAPagar.textContent = 'Bs. ' + aPagar.toFixed(2);
                            remaining = Math.round((remaining - aPagar) * 100) / 100;
                        } else {
                            delete fila.dataset.seleccionada;
                        }
                    }
                });
            }

            const btnSubmit = document.querySelector('#formPagoMasivo button[type="submit"]');
            if (btnSubmit) {
                const valido = monto > 0 && metodo && totalIngresado <= window.deudaTotalGlobal + 0.01;
                btnSubmit.disabled = !valido;
            }
        }

        function enviarPagoMasivo() {
            const btnSubmit = document.querySelector('#formPagoMasivo button[type="submit"]');
            const originalText = btnSubmit.innerHTML;

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Procesando...';

            const tabla = document.getElementById('tabla-cuotas-pendientes');
            const cuotasSeleccionadas = [];
            if (tabla) {
                tabla.querySelectorAll('.cuota-row').forEach(function(fila) {
                    if (fila.dataset.seleccionada === '1') {
                        const cuotaId = fila.getAttribute('data-cuota-id');
                        const cellAPagar = fila.querySelector('.a-pagar-cell');
                        const aPagar = cellAPagar && cellAPagar.textContent !== '—' ? parseFloat(cellAPagar.textContent.replace('Bs. ', '')) : 0;
                        if (cuotaId && aPagar > 0) {
                            cuotasSeleccionadas.push({id: cuotaId, monto: aPagar});
                        }
                    }
                });
            }

            const metodo = document.getElementById('pago-masivo-metodo').value;
            const cuentaBancariaId = document.getElementById('pago-masivo-cuenta-bancaria')?.value || '';

            if (['Qr','Transferencia','Parcial'].includes(metodo) && !cuentaBancariaId) {
                toast('warning', 'Debe seleccionar una cuenta bancaria para este método de pago.');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = originalText;
                return;
            }

            const formData = {
                estudiante_id: document.getElementById('pago-masivo-estudiante-id').value,
                inscripcion_id: document.getElementById('pago-masivo-inscripcion-id').value,
                monto: parseFloat(document.getElementById('pago-masivo-monto').value),
                descuento: parseFloat(document.getElementById('pago-masivo-descuento').value) || 0,
                metodo: metodo,
                efectivo: parseFloat(document.getElementById('pago-masivo-efectivo').value) || 0,
                qr: parseFloat(document.getElementById('pago-masivo-qr').value) || 0,
                trabajador_cargo_id: document.getElementById('pago-masivo-trabajador-cargo').value,
                fecha_pago: document.getElementById('pago-masivo-fecha').value,
                cuenta_bancaria_id: cuentaBancariaId,
                referencia: document.getElementById('pago-masivo-referencia')?.value || '',
                cuotas: cuotasSeleccionadas,
                _token: '{{ csrf_token() }}'
            };
            console.log('Enviando formData:', formData);

            fetch('/admin/estudiantes/cuotas/pago-masivo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    }
                    throw new Error('Respuesta no válida del servidor');
                })
                .then(data => {
                    console.log('Respuesta servidor:', data);
                    if (data.success) {
                        if (modalPagoMasivoInstance) {
                            modalPagoMasivoInstance.hide();
                        }
                        toast('success', data.message || 'Pago registrado correctamente.');
                        if (data.data && data.data.pago_id) {
                            abrirModalRecibo(data);
                        } else {
                            setTimeout(() => {
                                const estudianteId = '{{ $estudiante->id }}';
                                window.location.href = '/admin/estudiantes/' + estudianteId + '/detalle?tab=contable';
                            }, 1500);
                        }
                    } else {
                        toast('error', data.message || 'Error al registrar el pago.');
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toast('error', 'Ocurrió un error al procesar el pago.');
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = originalText;
                });
        }

        document.getElementById('modalReciboPago').addEventListener('hidden.bs.modal', function() {
            const estudianteId = '{{ $estudiante->id }}';
            window.location.href = '/admin/estudiantes/' + estudianteId + '/detalle?tab=contable';
        });

        // ─── Delegación de eventos para elementos dinámicos ───

        // Subir documento
        document.addEventListener('change', function(e) {
            const input = e.target.closest('.btn-subir-doc');
            if (!input) return;

            const file = input.files[0];
            if (!file) return;

            const tipoDocumento = input.dataset.tipo;
            const estudianteId = input.dataset.id;
            const estudioId = input.dataset.estudioId;

            if (file.size > 2048 * 1024) {
                alert('El archivo excede el tamaño máximo de 2MB.');
                input.value = '';
                return;
            }

            const tiposPermitidos = ['application/pdf', 'image/png', 'image/jpeg'];
            if (!tiposPermitidos.includes(file.type)) {
                alert('Tipo de archivo no permitido. Solo PDF, PNG, JPG, JPEG.');
                input.value = '';
                return;
            }

            const formData = new FormData();
            formData.append('tipo_documento', tipoDocumento);
            formData.append('archivo', file);
            if (estudioId) formData.append('estudio_id', estudioId);

            const label = input.closest('label');
            if (label) label.style.pointerEvents = 'none';

            fetch(`/admin/estudiantes/${estudianteId}/documentos/subir`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    toast('success', data.mensaje || 'Documento subido exitosamente');
                    actualizarDocCard(input, file.name, tipoDocumento, estudianteId, estudioId);
                } else {
                    alert(data.error || 'Error al subir el documento');
                    if (label) label.style.pointerEvents = '';
                }
            })
            .catch(() => {
                alert('Error al subir el documento');
                if (label) label.style.pointerEvents = '';
            });
        });

        function actualizarDocCard(input, fileName, tipoDocumento, estudianteId, estudioId) {
            const cardBody = input.closest('.doc-body');
            if (!cardBody) { location.reload(); return; }

            const estudioAttr = estudioId ? ` data-estudio-id="${estudioId}"` : '';
            const downloadName = tipoDocumento + '_' + estudianteId + (estudioId ? '_' + estudioId : '');

            cardBody.innerHTML =
                '<div style="display: flex; align-items: center; gap: 8px; padding: 8px; background: #f8fafc; border-radius: 6px; margin-bottom: 8px;">' +
                    '<div style="width: 28px; height: 28px; border-radius: 5px; background: #fee2e2; display: flex; align-items: center; justify-content: center; color: #dc2626; font-size: 0.85rem;">' +
                        '<i class="ri-file-pdf-fill"></i>' +
                    '</div>' +
                    '<div style="flex: 1; min-width: 0;">' +
                        '<div style="font-size: 0.72rem; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' + fileName + '</div>' +
                        '<div style="font-size: 0.6rem; display: flex; align-items: center; gap: 3px; color: #d97706;">' +
                            '<i class="ri-time-fill"></i> En revision' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div style="display: flex; gap: 4px; flex-wrap: wrap;">' +
                    '<button type="button" class="btn btn-sm btn-action btn-action-view btn-ver-doc" data-id="' + estudianteId + '" data-tipo="' + tipoDocumento + '"' + estudioAttr + ' title="Visualizar" style="flex: 1; padding: 5px 8px; border-radius: 5px; font-size: 0.68rem; font-weight: 600; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 3px; min-width: 50px;">' +
                        '<i class="ri-eye-line"></i> Ver' +
                    '</button>' +
                    '<label style="flex: 1; padding: 5px 8px; border-radius: 5px; font-size: 0.68rem; font-weight: 600; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 3px; min-width: 50px;">' +
                        '<i class="ri-upload-line"></i> Cambiar' +
                        '<input type="file" class="d-none btn-subir-doc" data-id="' + estudianteId + '" data-tipo="' + tipoDocumento + '"' + estudioAttr + ' accept=".pdf,.png,.jpg,.jpeg">' +
                    '</label>' +
                    '<button type="button" class="btn btn-sm btn-action btn-action-edit btn-verificar-doc" data-id="' + estudianteId + '" data-tipo="' + tipoDocumento + '"' + estudioAttr + ' title="Verificar" style="flex: 1; padding: 5px 8px; border-radius: 5px; font-size: 0.68rem; font-weight: 600; border: none; background: #22c55e; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 3px; min-width: 60px;">' +
                        '<i class="ri-check-line"></i> Aprobar' +
                    '</button>' +
                '</div>';
        }

        // Verificar / Quitar verificación
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-verificar-doc, .btn-quitar-verificacion');
            if (!btn) return;

            const esQuitar = btn.classList.contains('btn-quitar-verificacion');
            if (esQuitar && !confirm('Esta seguro de quitar la verificacion del documento?')) return;

            const tipoDocumento = btn.dataset.tipo;
            const estudianteId = btn.dataset.id;
            const estudioId = btn.dataset.estudioId;

            const body = { tipo_documento: tipoDocumento, accion: esQuitar ? 'quitar' : 'verificar' };
            if (estudioId) body.estudio_id = estudioId;

            fetch(`/admin/estudiantes/${estudianteId}/documentos/verificar`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(body)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const cardBody = btn.closest('.doc-body');
                    if (cardBody) {
                        const statusEl = cardBody.querySelector('.doc-header span:last-child, [class*="badge"]');
                        const badge = cardBody.querySelector('[class*="badge"], span:has(.ri-shield-check-fill), span:has(.ri-time-fill)');
                        // Simple reload for verify to keep UI consistent
                    }
                    location.reload();
                } else {
                    alert(data.error || 'Error al procesar el documento');
                }
            })
            .catch(() => alert('Error al procesar el documento'));
        });

        // Ver documento
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-ver-doc');
            if (!btn) return;

            const tipoDocumento = btn.dataset.tipo;
            const estudianteId = btn.dataset.id;
            const estudioId = btn.dataset.estudioId;

            const modal = new bootstrap.Modal(document.getElementById('modalVerDocumento'));
            const contenido = document.getElementById('contenido-documento');
            const btnDescargar = document.getElementById('btn-descargar-doc');

            contenido.innerHTML = '<div class="text-center text-muted"><i class="ri-loader-4-line" style="font-size: 2rem; animation: spin 1s linear infinite;"></i><p class="mt-2">Cargando documento...</p></div>';
            btnDescargar.href = '#';
            modal.show();

            let docUrl = `/admin/estudiantes/${estudianteId}/documentos/visualizar?tipo=${tipoDocumento}`;
            if (estudioId) docUrl += `&estudio_id=${estudioId}`;

            fetch(docUrl)
            .then(async response => {
                if (!response.ok) {
                    const err = await response.json().catch(() => ({}));
                    throw new Error(err.error || 'Error al cargar el documento');
                }
                const contentType = response.headers.get('content-type');
                const blob = await response.blob();
                const url = URL.createObjectURL(blob);

                btnDescargar.href = url;
                btnDescargar.download = tipoDocumento + '_' + estudianteId + (estudioId ? '_' + estudioId : '');

                if (contentType && contentType.includes('pdf')) {
                    contenido.innerHTML = `<iframe src="${url}" style="width: 100%; height: 500px; border: none;"></iframe>`;
                } else if (contentType && contentType.includes('image')) {
                    contenido.innerHTML = `<img src="${url}" style="max-width: 100%; max-height: 500px; object-fit: contain;">`;
                } else {
                    contenido.innerHTML = `<div class="text-center p-4"><p>Tipo de archivo no compatible</p><a href="${url}" download class="btn" style="background: #fc7b04; color: white;">Descargar</a></div>`;
                }
            })
            .catch(err => {
                contenido.innerHTML = `<div class="text-center text-muted p-4"><i class="ri-error-warning-line" style="font-size: 2rem;"></i><p class="mt-2">${err.message}</p></div>`;
            });
        });

        // Agregar estudio (abrir modal en modo crear)
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-agregar-estudio');
            if (!btn) return;

            document.getElementById('estudioEditId').value = '';
            document.getElementById('estudioPersonaId').value = btn.dataset.personaId;
            document.getElementById('estudioEstudianteId').value = btn.dataset.estudianteId;
            document.getElementById('estudioPrincipal').checked = true;
            document.getElementById('estudioTipo').value = '';
            document.getElementById('estudioProfesion').value = '';
            document.getElementById('estudioUniversidad').value = '';
            document.getElementById('estudioEstado').value = 'Concluido';
            document.getElementById('inputDocumentoAcademico').value = '';
            document.getElementById('inputProvisionNacional').value = '';
            document.getElementById('inputDocumentoAcademico').closest('.col-md-6').style.display = 'block';
            document.getElementById('inputProvisionNacional').closest('.col-md-6').style.display = 'block';
            document.getElementById('estudioErrorMsg').style.display = 'none';
            document.getElementById('modalEstudioTitulo').textContent = 'Registrar Estudio';

            new bootstrap.Modal(document.getElementById('modalAgregarEstudio')).show();
        });

        // Editar estudio (abrir modal en modo editar)
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-editar-estudio');
            if (!btn) return;

            document.getElementById('estudioEditId').value = btn.dataset.estudioId;
            document.getElementById('estudioPersonaId').value = btn.dataset.personaId;
            document.getElementById('estudioEstudianteId').value = btn.dataset.estudianteId;
            document.getElementById('estudioTipo').value = btn.dataset.gradoId || '';
            document.getElementById('estudioProfesion').value = btn.dataset.profesionId || '';
            document.getElementById('estudioUniversidad').value = btn.dataset.universidadId || '';
            document.getElementById('estudioEstado').value = btn.dataset.estado || 'Concluido';
            document.getElementById('estudioPrincipal').checked = btn.dataset.principal === '1';
            document.getElementById('inputDocumentoAcademico').value = '';
            document.getElementById('inputProvisionNacional').value = '';
            document.querySelector('#inputDocumentoAcademico').closest('.col-md-6').style.display = 'none';
            document.querySelector('#inputProvisionNacional').closest('.col-md-6').style.display = 'none';
            document.getElementById('estudioErrorMsg').style.display = 'none';
            document.getElementById('modalEstudioTitulo').textContent = 'Editar Estudio';

            new bootstrap.Modal(document.getElementById('modalAgregarEstudio')).show();
        });

        // Guardar / Actualizar estudio
        document.getElementById('btnGuardarEstudio').addEventListener('click', async function() {
            const editId = document.getElementById('estudioEditId').value;
            const personaId = document.getElementById('estudioPersonaId').value;
            const gradoAcademicoId = document.getElementById('estudioTipo').value;
            const profesioneId = document.getElementById('estudioProfesion').value;
            const universidadeId = document.getElementById('estudioUniversidad').value;
            const estado = document.getElementById('estudioEstado').value;
            const esPrincipal = document.getElementById('estudioPrincipal').checked;

            const errorDiv = document.getElementById('estudioErrorMsg');
            const errorText = document.getElementById('estudioErrorText');
            errorDiv.style.display = 'none';

            if (!gradoAcademicoId) {
                errorText.textContent = 'Por favor seleccione el grado académico';
                errorDiv.style.display = 'flex';
                return;
            }

            const esEditar = !!editId;
            const url = esEditar ? `/admin/personas/${personaId}/estudios/${editId}` : `/admin/personas/${personaId}/estudios`;

            const formData = new FormData();
            if (esEditar) formData.append('_method', 'PUT');
            formData.append('grados_academico_id', gradoAcademicoId);
            if (profesioneId) formData.append('profesione_id', profesioneId);
            if (universidadeId) formData.append('universidade_id', universidadeId);
            formData.append('estado', estado);
            formData.append('principal', esPrincipal ? 1 : 0);

            if (!esEditar) {
                const archivoAcademico = document.getElementById('inputDocumentoAcademico').files[0];
                const archivoProvision = document.getElementById('inputProvisionNacional').files[0];
                if (archivoAcademico) formData.append('documento_academico', archivoAcademico);
                if (archivoProvision) formData.append('documento_provision_nacional', archivoProvision);
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalAgregarEstudio'))?.hide();
                    toast('success', data.message || (esEditar ? 'Estudio actualizado' : 'Estudio registrado'));
                    setTimeout(() => location.reload(), 1000);
                } else {
                    errorText.textContent = data.message || (data.errors ? Object.values(data.errors).flat().join('\\n') : 'Error al guardar el estudio');
                    errorDiv.style.display = 'flex';
                }
            } catch (error) {
                errorText.textContent = 'Error de conexión al guardar el estudio';
                errorDiv.style.display = 'flex';
            }
        });

        // Marcar estudio como principal
        let _setPrincipalEstudianteId = null;
        let _setPrincipalEstudioId = null;

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-set-principal-estudio');
            if (!btn) return;

            _setPrincipalEstudianteId = btn.dataset.estudianteId;
            _setPrincipalEstudioId = btn.dataset.estudioId;

            new bootstrap.Modal(document.getElementById('modalConfirmarPrincipal')).show();
        });

        document.getElementById('btnConfirmarPrincipal').addEventListener('click', function() {
            const estudianteId = _setPrincipalEstudianteId;
            const estudioId = _setPrincipalEstudioId;

            if (!estudianteId || !estudioId) return;

            const modalEl = document.getElementById('modalConfirmarPrincipal');
            bootstrap.Modal.getInstance(modalEl)?.hide();

            const btnConfirmar = this;
            btnConfirmar.disabled = true;
            btnConfirmar.innerHTML = '<i class="ri-loader-4-line"></i> Guardando...';

            fetch(`/admin/estudiantes/${estudianteId}/estudios/${estudioId}/set-principal`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(r => r.json().catch(() => ({ success: false, message: 'Error del servidor (HTTP ' + r.status + ')' })))
            .then(data => {
                btnConfirmar.disabled = false;
                btnConfirmar.innerHTML = '<i class="ri-star-fill"></i> Confirmar';
                if (data.success) {
                    toast('success', data.message || 'Estudio marcado como principal');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toast('error', data.message || 'Error al marcar como principal');
                }
            })
            .catch(() => {
                btnConfirmar.disabled = false;
                btnConfirmar.innerHTML = '<i class="ri-star-fill"></i> Confirmar';
                toast('error', 'Error de conexión al marcar como principal');
            });
        });

        // Eliminar estudio
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-eliminar-estudio');
            if (!btn) return;

            const estudianteId = btn.dataset.estudianteId;
            const estudioId = btn.dataset.estudioId;

            if (!confirm('¿Está seguro de eliminar este estudio? Los documentos asociados también se eliminarán.')) return;

            fetch(`/admin/estudiantes/${estudianteId}/estudios/${estudioId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(r => r.json().catch(() => ({ success: false, message: 'Error del servidor' })))
            .then(data => {
                if (data.success) {
                    toast('success', data.message || 'Estudio eliminado correctamente');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toast('error', data.message || 'Error al eliminar el estudio');
                }
            })
            .catch(() => toast('error', 'Error de conexión al eliminar el estudio'));
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
    </script>
@endsection