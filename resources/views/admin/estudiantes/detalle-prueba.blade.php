@extends('admin.dashboard')

@section('admin')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

        :root {
            --est-primary: #0f766e;
            --est-primary-light: #f0fdfa;
            --est-primary-dark: #0d5f59;
            --est-accent: #f59e0b;
            --est-accent-light: #fffbeb;
            --est-surface: #f8fafc;
            --est-border: #e2e8f0;
            --est-text: #1e293b;
            --est-text-muted: #64748b;
            --est-success: #10b981;
            --est-success-light: #ecfdf5;
            --est-danger: #ef4444;
            --est-danger-light: #fef2f2;
            --est-info: #0891b2;
            --est-info-light: #ecfeff;
            --est-warning: #f59e0b;
            --est-warning-light: #fffbeb;
            --cont-primary: #0f766e;
            --cont-primary-light: #f0fdfa;
            --cont-primary-dark: #0d5f59;
            --cont-accent: #f59e0b;
            --cont-surface: #f8fafc;
            --cont-border: #e2e8f0;
            --cont-text: #1e293b;
            --cont-text-muted: #64748b;
            --cont-success: #10b981;
            --cont-success-light: #ecfdf5;
            --cont-danger: #ef4444;
            --cont-danger-light: #fef2f2;
            --cont-info: #0891b2;
            --cont-info-light: #ecfeff;
            --cont-warning: #f59e0b;
            --cont-warning-light: #fffbeb;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 8px -2px rgba(0, 0, 0, 0.08), 0 2px 4px -2px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 10px 25px -4px rgba(0, 0, 0, 0.1), 0 4px 8px -4px rgba(0, 0, 0, 0.06);
        }

        .estudiante-page {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--est-text);
            animation: estFadeIn 0.5s ease-out;
        }

        @keyframes estFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Page Header */
        .est-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
            padding: 20px 28px;
            background: linear-gradient(135deg, var(--est-primary) 0%, var(--est-primary-dark) 100%);
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
            background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, transparent 70%);
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

        /* Tabs Card */
        .est-tabs-card {
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid var(--est-border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .est-tabs-nav {
            display: flex;
            overflow-x: auto;
            scrollbar-width: none;
            padding: 0;
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
            background: rgba(15, 118, 110, 0.04);
        }

        .est-tab-btn.active {
            color: var(--est-primary);
            border-bottom-color: var(--est-primary);
            background: white;
        }

        .est-tab-btn i {
            font-size: 1.05rem;
        }

        .est-tab-badge {
            font-size: 0.62rem;
            padding: 2px 7px;
            border-radius: 50px;
            font-weight: 700;
            line-height: 1;
        }

        .est-tabs-body {
            padding: 24px;
        }

        /* Data Cards */
        .est-data-card {
            background: white;
            border-radius: var(--radius-md);
            border: 1px solid var(--est-border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            height: 100%;
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
            font-size: 0.95rem;
        }

        .est-data-card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0;
            color: var(--est-text);
        }

        .est-data-card-body {
            padding: 0;
        }

        .est-data-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
        }

        .est-data-row+.est-data-row {
            border-top: 1px solid var(--est-border);
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

        /* Stat Cards */
        .est-stat-card {
            background: white;
            border-radius: var(--radius-md);
            border: 1px solid var(--est-border);
            box-shadow: var(--shadow-sm);
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

        /* Tables */
        .est-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .est-table thead th {
            background: var(--est-surface);
            padding: 12px 16px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--est-text-muted);
            border-bottom: 1px solid var(--est-border);
        }

        .est-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--est-border);
            vertical-align: middle;
            font-size: 0.85rem;
        }

        .est-table tbody tr:last-child td {
            border-bottom: none;
        }

        .est-table tbody tr:hover {
            background: var(--est-primary-light);
        }

        .est-table tfoot td {
            padding: 10px 16px;
            background: var(--est-surface);
            font-weight: 700;
            font-size: 0.82rem;
            border-top: 2px solid var(--est-border);
        }

        /* Badges */
        .estado-badge-est {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
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

        .estado-badge-est.verificado {
            background: var(--est-success-light);
            color: var(--est-success);
        }

        .estado-badge-est.sin-verificar {
            background: var(--est-warning-light);
            color: var(--est-warning);
        }

        .estado-badge-est.no-subido {
            background: var(--est-danger-light);
            color: var(--est-danger);
        }

        /* Action buttons */
        .est-action-btn {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .est-action-btn.view {
            background: var(--est-info-light);
            color: var(--est-info);
            border-color: rgba(8, 145, 178, 0.2);
        }

        .est-action-btn.view:hover {
            background: var(--est-info);
            color: white;
            transform: translateY(-1px);
        }

        .est-action-btn.download {
            background: var(--est-success-light);
            color: var(--est-success);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .est-action-btn.download:hover {
            background: var(--est-success);
            color: white;
            transform: translateY(-1px);
        }

        .est-action-btn.pay {
            background: var(--est-success-light);
            color: var(--est-success);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .est-action-btn.pay:hover {
            background: var(--est-success);
            color: white;
            transform: translateY(-1px);
        }

        .est-action-btn.receipts {
            background: var(--est-info-light);
            color: var(--est-info);
            border-color: rgba(8, 145, 178, 0.2);
            position: relative;
        }

        .est-action-btn.receipts:hover {
            background: var(--est-info);
            color: white;
            transform: translateY(-1px);
        }

        .est-action-btn.receipts .receipt-count {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--est-info);
            color: white;
            font-size: 0.55rem;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid white;
        }

        /* Accordion */
        .est-accordion {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .est-accordion-item {
            background: white;
            border-radius: var(--radius-md);
            border: 1px solid var(--est-border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            position: relative;
            transition: all 0.25s ease;
        }

        .est-accordion-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
        }

        .est-accordion-item.color-success::before {
            background: var(--est-success);
        }

        .est-accordion-item.color-primary::before {
            background: var(--est-primary);
        }

        .est-accordion-item.color-warning::before {
            background: var(--est-warning);
        }

        .est-accordion-item.color-danger::before {
            background: var(--est-danger);
        }

        .est-accordion-item.color-info::before {
            background: var(--est-info);
        }

        .est-accordion-item.color-secondary::before {
            background: #94a3b8;
        }

        .est-accordion-item:hover {
            box-shadow: var(--shadow-md);
        }

        .est-accordion-btn {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 16px 20px;
            background: transparent;
            border: none;
            cursor: pointer;
            gap: 14px;
            transition: background 0.2s ease;
        }

        .est-accordion-btn:hover {
            background: var(--est-surface);
        }

        .est-accordion-btn.collapsed {
            background: transparent;
        }

        .est-prog-avatar {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
            flex-shrink: 0;
        }

        .est-prog-info {
            flex: 1;
            min-width: 0;
        }

        .est-prog-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--est-text);
            margin: 0 0 4px;
        }

        .est-prog-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 0.75rem;
            color: var(--est-text-muted);
        }

        .est-prog-meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .est-prog-meta-item i {
            color: var(--est-primary);
        }

        .est-prog-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
            flex-shrink: 0;
        }

        .est-prog-badges {
            display: flex;
            gap: 6px;
        }

        .est-prog-badge-paid {
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            background: var(--est-success-light);
            color: var(--est-success);
        }

        .est-prog-badge-debt {
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            background: var(--est-danger-light);
            color: var(--est-danger);
        }

        .est-prog-progress {
            width: 110px;
        }

        .est-prog-progress-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.65rem;
            margin-bottom: 3px;
        }

        .est-prog-progress-label {
            color: var(--est-text-muted);
            font-weight: 600;
        }

        .est-prog-progress-value {
            font-weight: 700;
        }

        .est-prog-progress-bar {
            height: 5px;
            border-radius: 3px;
            background: var(--est-border);
            overflow: hidden;
        }

        .est-prog-progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.6s ease;
        }

        .est-accordion-body {
            border-top: 1px solid var(--est-border);
        }

        /* Empty State */
        .est-empty-state {
            padding: 56px 24px;
            text-align: center;
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid var(--est-border);
            box-shadow: var(--shadow-sm);
        }

        .est-empty-state-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 16px;
            background: var(--est-surface);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .est-empty-state-icon i {
            font-size: 2rem;
            color: #cbd5e1;
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

        /* Modals */
        .modal-est .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .modal-est .modal-header {
            background: linear-gradient(135deg, var(--est-primary) 0%, var(--est-primary-dark) 100%);
            color: white;
            border-bottom: none;
            padding: 18px 24px;
        }

        .modal-est .modal-header h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
        }

        .modal-est .modal-body {
            padding: 22px 24px;
        }

        .modal-est .modal-footer {
            border-top: 1px solid var(--est-border);
            padding: 14px 24px;
            background: var(--est-surface);
        }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999999 !important;
        }

        /* Responsive */
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

        /* Multi-pay bar */
        .multi-pay-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            background: var(--est-primary-light);
            border-top: 1px solid var(--est-border);
            border-bottom: 1px solid var(--est-border);
        }

        .multi-pay-text {
            font-size: 0.82rem;
            color: var(--est-text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .multi-pay-text strong {
            color: var(--est-primary);
        }

        .btn-multi-pay {
            background: var(--est-primary);
            color: white;
            border: none;
            padding: 7px 16px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.82rem;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-multi-pay:hover {
            background: var(--est-primary-dark);
            transform: translateY(-1px);
            color: white;
        }

        /* Program Summary */
        .prog-summary {
            padding: 14px 20px;
            background: var(--est-surface);
            border-bottom: 1px solid var(--est-border);
        }

        .prog-summary-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .prog-summary-stat .ps-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--est-text-muted);
            font-weight: 700;
        }

        .prog-summary-stat .ps-value {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
        }

        /* Concept Cards */
        .concept-section {
            padding: 16px 20px;
            border-bottom: 1px solid var(--est-border);
        }

        .concept-section:last-child {
            border-bottom: none;
        }

        .concept-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }

        .concept-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--est-text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .concept-title i {
            color: var(--est-accent);
        }

        .concept-badges {
            display: flex;
            gap: 6px;
        }

        .concept-badge-paid {
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.68rem;
            font-weight: 600;
            background: var(--est-success-light);
            color: var(--est-success);
        }

        .concept-badge-debt {
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.68rem;
            font-weight: 600;
            background: var(--est-danger-light);
            color: var(--est-danger);
        }

        .concept-stat {
            text-align: center;
            padding: 8px 10px;
            border-radius: var(--radius-sm);
            background: white;
            border: 1px solid var(--est-border);
        }

        .concept-stat-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--est-text-muted);
            font-weight: 700;
        }

        .concept-stat-value {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
        }

        /* Cuotas Table */
        .cuotas-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .cuotas-table thead th {
            background: var(--est-surface);
            padding: 10px 14px;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--est-text-muted);
            border-bottom: 1px solid var(--est-border);
        }

        .cuotas-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--est-border);
            vertical-align: middle;
            font-size: 0.84rem;
        }

        .cuotas-table tbody tr:last-child td {
            border-bottom: none;
        }

        .cuotas-table tbody tr:hover {
            background: var(--est-primary-light);
        }

        .cuotas-table tbody tr.row-pagado {
            background: var(--est-success-light);
        }

        .cuotas-table tbody tr.row-pagado:hover {
            background: #d1fae5;
        }

        .cuotas-table tbody tr.row-parcial {
            background: var(--est-warning-light);
        }

        .cuotas-table tbody tr.row-parcial:hover {
            background: #fef3c7;
        }

        .cuotas-table tfoot td {
            padding: 10px 14px;
            background: var(--est-surface);
            font-weight: 700;
            font-size: 0.82rem;
            border-top: 2px solid var(--est-border);
        }

        .cuota-num-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 28px;
            border-radius: var(--radius-sm);
            background: var(--est-surface);
            color: var(--est-text-muted);
            font-weight: 700;
            font-size: 0.78rem;
            border: 1px solid var(--est-border);
        }

        .cuota-name {
            font-weight: 600;
            font-size: 0.86rem;
        }

        .cuota-payments {
            font-size: 0.72rem;
            color: var(--est-text-muted);
        }

        .cuota-progress {
            height: 3px;
            border-radius: 2px;
            background: var(--est-border);
            overflow: hidden;
            margin-top: 4px;
        }

        .cuota-progress-fill {
            height: 100%;
            border-radius: 2px;
            transition: width 0.4s ease;
        }

        .estado-badge-cont {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .estado-badge-cont.pagado {
            background: var(--est-success-light);
            color: var(--est-success);
        }

        .estado-badge-cont.pendiente {
            background: var(--est-warning-light);
            color: var(--est-warning);
        }

        .cuota-action-btn {
            width: 30px;
            height: 30px;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.88rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .cuota-action-btn.pay {
            background: var(--est-success-light);
            color: var(--est-success);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .cuota-action-btn.pay:hover {
            background: var(--est-success);
            color: white;
            transform: translateY(-1px);
        }

        .cuota-action-btn.receipts {
            background: var(--est-info-light);
            color: var(--est-info);
            border-color: rgba(8, 145, 178, 0.2);
            position: relative;
        }

        .cuota-action-btn.receipts:hover {
            background: var(--est-info);
            color: white;
            transform: translateY(-1px);
        }

        .cuota-action-btn.receipts .receipt-count {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--est-info);
            color: white;
            font-size: 0.55rem;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid white;
        }

        /* Footer Summary */
        .footer-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 18px 24px;
            background: white;
            border-radius: var(--radius-md);
            border: 1px solid var(--est-border);
            box-shadow: var(--shadow-sm);
            margin-top: 20px;
        }

        .footer-stat {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-stat-icon {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .footer-stat-value {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            line-height: 1.2;
        }

        .footer-stat-label {
            font-size: 0.68rem;
            color: var(--est-text-muted);
            font-weight: 600;
        }

        /* Tabla card header */
        .tabla-card-header {
            padding: 16px 24px;
            border-bottom: 1px dashed var(--est-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tabla-card-header h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            margin: 0;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tabla-card-header h5 i {
            color: var(--est-primary);
        }

        .badge-recibos-count {
            background: var(--est-primary-light);
            color: var(--est-primary);
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 50px;
        }

        /* Recibo cell */
        .recibo-cell {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .recibo-number {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.88rem;
            color: var(--est-primary);
            letter-spacing: -0.01em;
        }

        .tipo-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
            width: fit-content;
        }

        .tipo-badge.efectivo {
            background: var(--est-success-light);
            color: var(--est-success);
        }

        .tipo-badge.transferencia {
            background: var(--est-info-light);
            color: var(--est-info);
        }

        .tipo-badge.deposito {
            background: var(--est-primary-light);
            color: var(--est-primary);
        }

        .tipo-badge.tarjeta {
            background: var(--est-warning-light);
            color: var(--est-warning);
        }

        .tipo-badge.otro {
            background: #f1f5f9;
            color: var(--est-text-muted);
        }

        /* Date cell */
        .date-cell .date-main {
            font-weight: 600;
            font-size: 0.86rem;
        }

        .date-cell .date-time {
            color: var(--est-text-muted);
            font-size: 0.75rem;
        }

        /* Modal Styles */
        .modal-cont .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .modal-cont .modal-header {
            background: linear-gradient(135deg, var(--est-primary) 0%, var(--est-primary-dark) 100%);
            color: white;
            border-bottom: none;
            padding: 18px 24px;
        }

        .modal-cont .modal-header h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
        }

        .modal-cont .modal-body {
            padding: 22px 24px;
        }

        .modal-cont .modal-footer {
            border-top: 1px solid var(--est-border);
            padding: 14px 24px;
            background: var(--est-surface);
        }

        /* Pagar Cuota Modal */
        .pago-info-card {
            background: var(--est-surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--est-border);
            padding: 16px;
            height: 100%;
        }

        .pago-info-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--est-text-muted);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pago-info-title i {
            color: var(--est-accent);
        }

        .pago-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid var(--est-border);
            font-size: 0.84rem;
        }

        .pago-info-row:last-child {
            border-bottom: none;
        }

        .pago-resumen-card {
            background: linear-gradient(135deg, var(--est-primary) 0%, var(--est-primary-dark) 100%);
            border-radius: var(--radius-md);
            padding: 18px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .pago-resumen-card::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -5%;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .pago-res-label {
            font-size: 0.68rem;
            opacity: 0.75;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 2px;
            position: relative;
            z-index: 1;
        }

        .pago-res-value {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }

        .pago-res-divider {
            width: 1px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
        }

        .pago-form-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--est-text);
            margin-bottom: 4px;
        }

        .pago-form-control {
            border-radius: var(--radius-sm);
            border: 1px solid var(--est-border);
            padding: 8px 12px;
            font-size: 0.85rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--est-surface);
            transition: all 0.2s ease;
        }

        .pago-form-control:focus {
            border-color: var(--est-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
            background: white;
        }

        .cobrador-alert {
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
        }

        .cobrador-alert .cobrador-name {
            font-weight: 600;
        }

        .cobrador-alert .cobrador-cargo {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        /* Pagar Multiple Modal */
        .pc-cuotas-panel {
            background: var(--est-surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--est-border);
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .pc-cuotas-panel-header {
            padding: 12px 16px;
            background: white;
            border-bottom: 1px solid var(--est-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .pc-cuotas-panel-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--est-text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pc-cuotas-panel-title i {
            color: var(--est-primary);
        }

        .pc-cuotas-panel-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pc-btn-sm {
            background: white;
            color: var(--est-text-muted);
            border: 1px solid var(--est-border);
            padding: 4px 10px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.72rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .pc-btn-sm:hover {
            background: var(--est-primary-light);
            color: var(--est-primary);
            border-color: var(--est-primary);
        }

        .pc-selected-badge {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.82rem;
            padding: 4px 12px;
            border-radius: var(--radius-sm);
            background: var(--est-primary-light);
            color: var(--est-primary);
            border: 1px solid rgba(15, 118, 110, 0.15);
        }

        .pc-cuotas-list {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
        }

        .pc-cuotas-list::-webkit-scrollbar {
            width: 5px;
        }

        .pc-cuotas-list::-webkit-scrollbar-track {
            background: transparent;
        }

        .pc-cuotas-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .pc-prog-group {
            margin-bottom: 12px;
        }

        .pc-prog-group:last-child {
            margin-bottom: 0;
        }

        .pc-prog-group-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            background: white;
            border-radius: var(--radius-sm);
            border: 1px solid var(--est-border);
            margin-bottom: 6px;
        }

        .pc-prog-group-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.78rem;
            color: var(--est-text);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pc-prog-group-title i {
            color: var(--est-accent);
            font-size: 0.9rem;
        }

        .pc-prog-group-total {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.78rem;
            color: var(--est-danger);
            background: var(--est-danger-light);
            padding: 2px 8px;
            border-radius: 50px;
        }

        .pc-cuota-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--est-border);
            margin-bottom: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            position: relative;
        }

        .pc-cuota-item:last-child {
            margin-bottom: 0;
        }

        .pc-cuota-item:hover {
            background: var(--est-primary-light);
            border-color: var(--est-primary);
            transform: translateX(2px);
        }

        .pc-cuota-item.selected {
            background: var(--est-primary-light);
            border-color: var(--est-primary);
            box-shadow: 0 0 0 2px rgba(15, 118, 110, 0.12);
        }

        .pc-cuota-item.selected::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: var(--est-primary);
            border-radius: 0 2px 2px 0;
        }

        .pc-cuota-check {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid var(--est-border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s ease;
            background: white;
            cursor: pointer;
        }

        .pc-cuota-item.selected .pc-cuota-check {
            background: var(--est-primary);
            border-color: var(--est-primary);
        }

        .pc-cuota-check i {
            font-size: 0.75rem;
            color: white;
            opacity: 0;
            transition: opacity 0.15s ease;
        }

        .pc-cuota-item.selected .pc-cuota-check i {
            opacity: 1;
        }

        .pc-cuota-item input[type=checkbox] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            margin: 0;
            padding: 0;
        }

        .pc-cuota-info {
            flex: 1;
            min-width: 0;
        }

        .pc-cuota-name {
            font-weight: 600;
            font-size: 0.82rem;
            color: var(--est-text);
            margin-bottom: 2px;
        }

        .pc-cuota-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.72rem;
        }

        .pc-cuota-pending {
            color: var(--est-danger);
            font-weight: 600;
        }

        .pc-cuota-tipo {
            padding: 1px 8px;
            border-radius: 50px;
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .pc-cuota-tipo.tipo-matricula {
            background: var(--est-primary-light);
            color: var(--est-primary);
        }

        .pc-cuota-tipo.tipo-colegiatura {
            background: var(--est-success-light);
            color: var(--est-success);
        }

        .pc-cuota-tipo.tipo-certificacion {
            background: var(--est-warning-light);
            color: var(--est-warning);
        }

        .pc-cuota-tipo.tipo-otros {
            background: #f1f5f9;
            color: var(--est-text-muted);
        }

        .pc-cuota-amount {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--est-text);
            flex-shrink: 0;
        }

        .pc-empty-state {
            text-align: center;
            padding: 32px 16px;
            color: var(--est-text-muted);
        }

        .pc-empty-state i {
            font-size: 2rem;
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        .pc-form-panel {
            background: white;
            border-radius: var(--radius-md);
            border: 1px solid var(--est-border);
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .pc-form-panel-header {
            padding: 12px 16px;
            background: var(--est-surface);
            border-bottom: 1px solid var(--est-border);
        }

        .pc-form-panel-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--est-text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pc-form-panel-title i {
            color: var(--est-primary);
        }

        .pc-form-panel-body {
            padding: 16px;
            flex: 1;
            overflow-y: auto;
        }

        .pc-form-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--est-text);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .pc-form-control {
            border-radius: var(--radius-sm);
            border: 1px solid var(--est-border);
            padding: 8px 12px;
            font-size: 0.85rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--est-surface);
            transition: all 0.2s ease;
        }

        .pc-form-control:focus {
            border-color: var(--est-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
            background: white;
        }

        .pc-resumen-card {
            background: linear-gradient(135deg, var(--est-primary) 0%, var(--est-primary-dark) 100%);
            border-radius: var(--radius-md);
            padding: 16px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .pc-resumen-card::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -5%;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .pc-res-label {
            font-size: 0.62rem;
            opacity: 0.75;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 2px;
            position: relative;
            z-index: 1;
        }

        .pc-res-value {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        .pc-res-divider {
            width: 1px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
        }

        .pc-cobrador-alert {
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
        }

        .pc-cobrador-name {
            font-weight: 600;
        }

        .pc-cobrador-cargo {
            font-size: 0.75rem;
            opacity: 0.8;
        }
    </style>

    <div class="container-fluid estudiante-page">
        <!-- Page Header -->
        <div class="est-header">
            <div>
                <h1><i class="ri-user-line me-2"></i>Detalle del Estudiante</h1>
                <p>{{ $estudiante->persona->nombres ?? '' }} {{ $estudiante->persona->apellido_paterno ?? '' }}
                    {{ $estudiante->persona->apellido_materno ?? '' }}</p>
            </div>
            <div class="est-header-actions">
                <a href="{{ route('admin.estudiantes.listar') }}" class="est-header-btn">
                    <i class="ri-arrow-left-line"></i> Volver
                </a>
                <a href="{{ route('admin.contabilidad.estudiante', $estudiante->id) }}" class="est-header-btn">
                    <i class="ri-money-dollar-circle-line"></i> Contabilidad
                </a>
                <a href="{{ route('admin.estudiantes.editar', $estudiante->id) }}" class="est-header-btn">
                    <i class="ri-edit-line"></i> Editar
                </a>
                <button class="est-header-btn" onclick="window.print()">
                    <i class="ri-printer-line"></i> Imprimir
                </button>
            </div>
        </div>

        <!-- Student Header -->
        @include('admin.estudiantes.partials.detalle-header')

        <!-- Tabs -->
        @php
            $docPend = [];
            if (!$estudiante->persona->carnet_verificado && $estudiante->persona->fotografia_carnet) {
                $docPend[] = 'Carnet';
            }
            if (
                !$estudiante->persona->certificado_nacimiento_verificado &&
                $estudiante->persona->fotografia_certificado_nacimiento
            ) {
                $docPend[] = 'Cert. Nacimiento';
            }
            $epTab = $estudiante->persona->estudios->where('principal', 1)->first();
            if ($epTab) {
                if (!$epTab->documento_academico_verificado && $epTab->documento_academico) {
                    $docPend[] = 'Doc. Académico';
                }
                if (!$epTab->documento_provision_verificado && $epTab->documento_provision_nacional) {
                    $docPend[] = 'Prov. Nacional';
                }
            }
        @endphp

        <div class="est-tabs-card">
            <div class="est-tabs-nav" role="tablist">
                <button class="est-tab-btn active" data-bs-toggle="tab" data-bs-target="#tab-personal" type="button"
                    role="tab">
                    <i class="ri-user-3-line"></i>
                    <span>Personal</span>
                </button>
                <button class="est-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-documentos" type="button"
                    role="tab">
                    <i class="ri-file-text-line"></i>
                    <span>Documentos</span>
                    @if (count($docPend) > 0)
                        <span class="est-tab-badge bg-danger text-white">{{ count($docPend) }}</span>
                    @endif
                </button>
                <button class="est-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-academico" type="button"
                    role="tab">
                    <i class="ri-graduation-cap-line"></i>
                    <span>Académico</span>
                    @if ($estudiante->inscripciones->count() > 0)
                        <span class="est-tab-badge"
                            style="background:var(--est-primary-light);color:var(--est-primary);">{{ $estudiante->inscripciones->count() }}</span>
                    @endif
                </button>
                <button class="est-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-financiero" type="button"
                    role="tab">
                    <i class="ri-money-dollar-circle-line"></i>
                    <span>Financiero</span>
                </button>
                <button class="est-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-pagos" type="button" role="tab">
                    <i class="ri-history-line"></i>
                    <span>Historial</span>
                    @if ($pagosEstudiante && $pagosEstudiante->count() > 0)
                        <span class="est-tab-badge"
                            style="background:var(--est-success-light);color:var(--est-success);">{{ $pagosEstudiante->count() }}</span>
                    @endif
                </button>
            </div>

            <div class="est-tabs-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-personal" role="tabpanel">
                        @include('admin.estudiantes.partials.detalle-personal')
                    </div>
                    <div class="tab-pane fade" id="tab-documentos" role="tabpanel">
                        @include('admin.estudiantes.partials.detalle-documentos')
                    </div>
                    <div class="tab-pane fade" id="tab-academico" role="tabpanel">
                        @include('admin.estudiantes.partials.detalle-academico')
                    </div>
                    <div class="tab-pane fade" id="tab-financiero" role="tabpanel">
                        @include('admin.estudiantes.partials.detalle-financiero')
                    </div>
                    <div class="tab-pane fade" id="tab-pagos" role="tabpanel">
                        @include('admin.estudiantes.partials.detalle-historial-pagos')
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        @include('admin.estudiantes.partials.modales-subida-documentos')
        @include('admin.estudiantes.partials.modal-preview-documento')
        @include('admin.estudiantes.partials.modal-eliminacion')
        @include('admin.estudiantes.partials.modal-pagar-cuota')
        @include('admin.contabilidad.partials.modal-pagar-contabilidad')
        @include('admin.estudiantes.partials.modal-recibos-cuota')
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('admin.estudiantes.partials.scripts-documentos')
    @include('admin.estudiantes.partials.scripts-tabs')
    @include('admin.estudiantes.partials.estilos-tabs')

    <script>
        var pcCuotasData = {};

        function togglePcFields() {
            var tipo = document.getElementById('pc_tipo_pago').value;
            document.getElementById('pc_campo_caja').style.display = 'none';
            document.getElementById('pc_campo_cuenta').style.display = 'none';
            document.getElementById('pc_campo_comprobante').style.display = 'none';
            document.getElementById('pc_caja_id').removeAttribute('required');
            document.getElementById('pc_cuenta_id').removeAttribute('required');
            document.getElementById('pc_n_comprobante').removeAttribute('required');
            if (tipo === 'Efectivo') {
                document.getElementById('pc_campo_caja').style.display = 'block';
                document.getElementById('pc_caja_id').setAttribute('required', 'required');
            } else if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipo)) {
                document.getElementById('pc_campo_cuenta').style.display = 'block';
                document.getElementById('pc_campo_comprobante').style.display = 'block';
                document.getElementById('pc_cuenta_id').setAttribute('required', 'required');
                document.getElementById('pc_n_comprobante').setAttribute('required', 'required');
            }
        }

        $(document).ready(function() {
            $('.accordion-button').on('click', function() {
                $(this).toggleClass('collapsed');
            });

            var scSaldoPendiente = 0;

            function scActualizarResumen() {
                var monto = parseFloat($('#monto_pago').val()) || 0;
                var descuento = parseFloat($('#descuento').val()) || 0;
                if (monto > scSaldoPendiente) {
                    monto = scSaldoPendiente;
                    $('#monto_pago').val(monto.toFixed(2));
                }
                if (descuento > monto) {
                    descuento = monto;
                    $('#descuento').val(descuento.toFixed(2));
                }
                var total = Math.max(0, monto - descuento);
                var pct = scSaldoPendiente > 0 ? Math.min(100, (monto / scSaldoPendiente) * 100) : 0;
                $('#resumen-monto').text(monto.toFixed(2) + ' Bs');
                $('#resumen-descuento').text(descuento.toFixed(2) + ' Bs');
                $('#resumen-total').text(total.toFixed(2) + ' Bs');
                $('#progreso-pago').css('width', pct.toFixed(1) + '%');
                $('#texto-progreso').text(pct.toFixed(1) + '% del saldo pendiente');
            }

            $(document).on('input', '#monto_pago, #descuento', scActualizarResumen);

            $(document).on('click', '.btn-pagar-cuota', function() {
                var cuotaId = $(this).data('cuota-id');
                var estudianteId = $(this).data('estudiante-id');
                $('#formPagarCuota')[0].reset();
                $('#cuota_id').val(cuotaId);
                $('#estudiante_id').val(estudianteId);
                $('#fecha_pago').val(new Date().toISOString().split('T')[0]);
                togglePaymentFields();
                $('#modalPagarCuota').modal('show');
                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/cuota/' + cuotaId,
                    type: 'GET',
                    success: function(r) {
                        if (r.success) {
                            var total = parseFloat(r.cuota.pago_total_bs) || 0;
                            var pendiente = parseFloat(r.cuota.pago_pendiente_bs) || 0;
                            var pagado = parseFloat(r.cuota.saldo_pagado) || 0;
                            scSaldoPendiente = pendiente;
                            $('#info-cuota-nombre').text(r.cuota.nombre);
                            $('#info-cuota-programa').text(r.cuota.programa);
                            $('#info-cuota-total').text(total.toFixed(2));
                            $('#info-cuota-pagado').text(pagado.toFixed(2));
                            $('#info-cuota-pendiente').text(pendiente.toFixed(2));
                            $('#maximo_pago').text(pendiente.toFixed(2));
                            $('#monto_pago').val(pendiente.toFixed(2)).attr('max', pendiente);
                            scActualizarResumen();
                        } else {
                            $('#modalPagarCuota').modal('hide');
                            Swal.fire('Error', r.msg || 'No se pudo cargar la cuota.', 'error');
                        }
                    },
                    error: function() {
                        $('#modalPagarCuota').modal('hide');
                        Swal.fire('Error', 'No se pudo cargar la información de la cuota.',
                            'error');
                    }
                });
            });

            $(document).on('submit', '#formPagarCuota', function(e) {
                e.preventDefault();
                var estudianteId = $('#estudiante_id').val();
                var tipo = $('#tipo_pago').val();
                if (!tipo) {
                    Swal.fire('Atención', 'Seleccione el tipo de pago.', 'warning');
                    return;
                }
                if (tipo === 'Efectivo' && !$('#caja_id').val()) {
                    Swal.fire('Atención', 'Seleccione una caja.', 'warning');
                    return;
                }
                if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipo) && !$('#cuenta_bancaria_id')
                    .val()) {
                    Swal.fire('Atención', 'Seleccione una cuenta bancaria.', 'warning');
                    return;
                }
                var $btn = $(this).find('[type=submit]');
                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>Procesando...');
                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/pagar-cuota',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(r) {
                        if (r.success) {
                            $('#modalPagarCuota').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: '¡Pago Registrado!',
                                html: '<b>Recibo:</b> ' + r.recibo +
                                    '<br><a href="' + r.pdf_url +
                                    '" target="_blank" class="btn btn-sm btn-primary mt-2">' +
                                    '<i class="ri-download-line me-1"></i>Descargar Recibo PDF</a>',
                                confirmButtonText: 'Cerrar'
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', r.msg, 'error');
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Error al registrar el pago.';
                        if (xhr.responseJSON) msg = xhr.responseJSON.msg || msg;
                        Swal.fire('Error', msg, 'error');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(
                            '<i class="ri-checkbox-circle-line me-1"></i>Registrar Pago');
                    }
                });
            });

            $(document).on('click', '.btn-pagar-multiple', function() {
                var estudianteId = $(this).data('estudiante-id');
                $('#pc_estudiante_id').val(estudianteId);
                $('#formPagarContabilidad')[0].reset();
                $('#pc_tipo_pago').val('');
                togglePcFields();
                pcCargarCuotas(estudianteId, null);
                $('#modalPagarContabilidad').modal('show');
            });

            function pcActualizarResumen() {
                var monto = parseFloat($('#pc_monto_pago').val()) || 0;
                var desc = parseFloat($('#pc_descuento').val()) || 0;
                var total = Math.max(0, monto - desc);
                var pend = parseFloat($('#pc_pendiente_total').text()) || 0;
                var pct = pend > 0 ? Math.min(100, (total / pend) * 100) : 0;
                $('#pc_res_monto').text(monto.toFixed(2) + ' Bs');
                $('#pc_res_desc').text(desc.toFixed(2) + ' Bs');
                $('#pc_res_total').text(total.toFixed(2) + ' Bs');
                $('#pc_progreso').css('width', pct.toFixed(1) + '%');
                $('#pc_txt_progreso').text(pct.toFixed(1) + '% del total seleccionado');
            }

            function pcActualizarTotales() {
                var total = 0,
                    count = 0;
                $('.pc-cuota-check-input:checked').each(function() {
                    total += pcCuotasData[$(this).val()] || 0;
                    count++;
                });
                $('#pc_pendiente_total').text(total.toFixed(2));
                $('#totalSelBadge').text(total.toFixed(2) + ' Bs');
                $('#pc_monto_pago').val(total.toFixed(2));
                $('#pc_res_cuotas').text(count);
                pcActualizarResumen();
            }

            function pcCargarCuotas(estudianteId, preseleccionarId) {
                $('#listaCuotasPago').html(
                    '<div class="pc-empty-state"><div class="spinner-border" role="status" style="color:var(--est-primary);width:24px;height:24px;"></div><p class="mt-2 mb-0">Cargando cuotas pendientes...</p></div>'
                    );
                pcCuotasData = {};
                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/cuotas-pendientes',
                    type: 'GET',
                    success: function(response) {
                        if (!response.success || !response.programas || response.programas.length ===
                            0) {
                            $('#listaCuotasPago').html(
                                '<div class="pc-empty-state"><i class="ri-file-list-3-line d-block"></i><p class="mb-0">No hay cuotas pendientes.</p></div>'
                                );
                            return;
                        }

                        var tipoClsMap = {
                            'Matrícula': 'tipo-matricula',
                            'Colegiatura': 'tipo-colegiatura',
                            'Certificación': 'tipo-certificacion',
                            'Otros': 'tipo-otros',
                        };

                        var html = '';
                        response.programas.forEach(function(prog) {
                            var progTotal = prog.cuotas.reduce(function(s, c) {
                                return s + c.pendiente_bs;
                            }, 0);
                            html += '<div class="pc-prog-group">';
                            html += '<div class="pc-prog-group-header">';
                            html +=
                                '<span class="pc-prog-group-title"><i class="ri-graduation-cap-line"></i>' +
                                prog.programa + '</span>';
                            html += '<span class="pc-prog-group-total">' + progTotal.toFixed(
                                2) + ' Bs</span>';
                            html += '</div>';

                            prog.cuotas.forEach(function(cuota) {
                                pcCuotasData[cuota.id] = cuota.pendiente_bs;
                                var checked = (preseleccionarId && cuota.id ==
                                    preseleccionarId) ? 'checked' : '';
                                var selCls = checked ? 'selected' : '';
                                var tipoCls = tipoClsMap[cuota.tipo] || 'tipo-otros';
                                html += '<div class="pc-cuota-item ' + selCls +
                                    '" data-cuota-id="' + cuota.id + '">';
                                html +=
                                    '<input class="pc-cuota-check-input" type="checkbox" value="' +
                                    cuota.id + '" ' + checked + '>';
                                html +=
                                    '<div class="pc-cuota-check"><i class="ri-check-line"></i></div>';
                                html += '<div class="pc-cuota-info">';
                                html += '<div class="pc-cuota-name">' + cuota.nombre +
                                    '</div>';
                                html += '<div class="pc-cuota-meta">';
                                html += '<span class="pc-cuota-pending">Pendiente: ' +
                                    cuota.pendiente_bs.toFixed(2) + ' Bs</span>';
                                html += '<span class="pc-cuota-tipo ' + tipoCls + '">' +
                                    cuota.tipo + '</span>';
                                html += '</div></div>';
                                html += '<div class="pc-cuota-amount">' + cuota
                                    .pendiente_bs.toFixed(2) + '</div>';
                                html += '</div>';
                            });
                            html += '</div>';
                        });

                        $('#listaCuotasPago').html(html);
                        pcActualizarTotales();
                    },
                    error: function() {
                        $('#listaCuotasPago').html(
                            '<div class="alert alert-danger mb-0">Error al cargar cuotas.</div>');
                    }
                });
            }

            $(document).on('change', '.pc-cuota-check-input', function() {
                $(this).closest('.pc-cuota-item').toggleClass('selected', this.checked);
                pcActualizarTotales();
            });

            $(document).on('click', '.pc-cuota-item', function(e) {
                if (e.target.type !== 'checkbox') {
                    var cb = $(this).find('.pc-cuota-check-input');
                    cb.prop('checked', !cb.prop('checked'));
                    $(this).toggleClass('selected', cb.prop('checked'));
                    pcActualizarTotales();
                }
            });

            $('#btnSeleccionarTodas').on('click', function() {
                $('.pc-cuota-check-input').prop('checked', true).closest('.pc-cuota-item').addClass(
                    'selected');
                pcActualizarTotales();
            });

            $('#btnDeseleccionarTodas').on('click', function() {
                $('.pc-cuota-check-input').prop('checked', false).closest('.pc-cuota-item').removeClass(
                    'selected');
                pcActualizarTotales();
            });

            $(document).on('input', '#pc_monto_pago, #pc_descuento', pcActualizarResumen);

            $('#btnRegistrarPagoContabilidad').on('click', function() {
                var cuotaIds = [];
                $('.pc-cuota-check-input:checked').each(function() {
                    cuotaIds.push($(this).val());
                });
                if (cuotaIds.length === 0) {
                    Swal.fire('Atención', 'Seleccione al menos una cuota.', 'warning');
                    return;
                }
                var tipo = $('#pc_tipo_pago').val();
                if (!tipo) {
                    Swal.fire('Atención', 'Seleccione el tipo de pago.', 'warning');
                    return;
                }
                if (tipo === 'Efectivo' && !$('#pc_caja_id').val()) {
                    Swal.fire('Atención', 'Seleccione una caja.', 'warning');
                    return;
                }
                if (['Transferencia', 'Depósito', 'Tarjeta'].includes(tipo) && !$('#pc_cuenta_id').val()) {
                    Swal.fire('Atención', 'Seleccione una cuenta bancaria.', 'warning');
                    return;
                }
                var monto = parseFloat($('#pc_monto_pago').val()) || 0;
                if (monto <= 0) {
                    Swal.fire('Atención', 'El monto debe ser mayor a cero.', 'warning');
                    return;
                }
                var estudianteId = $('#pc_estudiante_id').val();
                var formData = new FormData(document.getElementById('formPagarContabilidad'));
                cuotaIds.forEach(function(id) {
                    formData.append('cuota_ids[]', id);
                });
                var btn = $(this);
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>Registrando...');
                $.ajax({
                    url: '/admin/estudiantes/' + estudianteId + '/pagar-multiples-cuotas',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(r) {
                        if (r.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Pago Registrado!',
                                html: '<b>Recibo:</b> ' + r.recibo +
                                    '<br><a href="' + r.pdf_url +
                                    '" target="_blank" class="btn btn-sm btn-primary mt-2">' +
                                    '<i class="ri-download-line me-1"></i>Descargar Recibo PDF</a>',
                                confirmButtonText: 'Cerrar'
                            }).then(function() {
                                $('#modalPagarContabilidad').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', r.msg || 'No se pudo registrar el pago.',
                                'error');
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Error al registrar el pago.';
                        if (xhr.responseJSON) msg = xhr.responseJSON.msg || xhr.responseJSON
                            .message || msg;
                        Swal.fire('Error', msg, 'error');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(
                            '<i class="ri-checkbox-circle-line me-1"></i>Registrar Pago');
                    }
                });
            });

            $(document).on('click', '.btn-ver-recibos', function() {
                var cuotaId = $(this).data('cuota-id');
                var cuotaNombre = $(this).data('cuota-nombre');
                $('#modalRecibosTitle').text('Recibos de: ' + cuotaNombre);
                $('#modalRecibosCuota').modal('show');
                $('#contenidoRecibos').html(
                    '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Cargando...</p></div>'
                    );
                $.ajax({
                    url: '/admin/estudiantes/cuota/' + cuotaId + '/recibos',
                    type: 'GET',
                    success: function(r) {
                        $('#contenidoRecibos').html(r.success ? r.html :
                            '<div class="alert alert-danger">' + (r.msg || 'Error') +
                            '</div>');
                    },
                    error: function() {
                        $('#contenidoRecibos').html(
                            '<div class="alert alert-danger">Error al cargar los recibos.</div>'
                            );
                    }
                });
            });

            $(document).on('click', '.btn-ver-detalle-pago', function() {
                var pagoId = $(this).data('pago-id');
                $('#modalDetallePago').modal('show');
                $('#contenidoDetallePago').html(
                    '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted small">Cargando...</p></div>'
                    );
                $.ajax({
                    url: '/admin/estudiantes/pago/' + pagoId + '/detalle',
                    type: 'GET',
                    success: function(r) {
                        if (!r.success) {
                            $('#contenidoDetallePago').html('<div class="alert alert-danger">' +
                                (r.msg || 'Error') + '</div>');
                            return;
                        }
                        var p = r.pago;
                        var cuotas = r.cuotas;
                        var est = r.estudiante;
                        var monto = parseFloat(p.pago_bs || 0);
                        var desc = parseFloat(p.descuento_bs || 0);
                        var neto = monto - desc;
                        var tipoBadge = {
                            'Efectivo': 'bg-success',
                            'Transferencia': 'bg-info',
                            'Depósito': 'bg-primary',
                            'Tarjeta': 'bg-warning text-dark'
                        };
                        var tbCls = tipoBadge[p.tipo_pago] || 'bg-secondary';
                        var fecha = p.fecha_pago ?
                            new Date(p.fecha_pago).toLocaleString('es-ES', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            }) :
                            '—';
                        var estHtml = est ?
                            '<div class="px-4 py-2 border-bottom d-flex align-items-center gap-2" style="background:#f0f5ff;">' +
                            '<i class="ri-user-line text-primary"></i>' +
                            '<span class="small fw-medium">' + est.persona.nombres + ' ' + est
                            .persona.apellido_paterno + '</span>' +
                            '<span class="badge bg-secondary ms-auto rounded-pill" style="font-size:.7rem;">' +
                            est.persona.carnet + '</span>' +
                            '</div>' :
                            '';
                        var cuotasRows = '';
                        cuotas.forEach(function(c) {
                            cuotasRows +=
                                '<tr><td class="text-center"><span class="badge bg-light text-dark border">' +
                                c.cuota.n_cuota + '</span></td>' +
                                '<td class="fw-medium small">' + c.cuota.nombre +
                                '</td>' +
                                '<td class="text-end fw-semibold text-success">' +
                                parseFloat(c.pago_bs).toFixed(2) + ' Bs</td></tr>';
                        });
                        var html = estHtml + '<div class="p-4">' +
                            '<div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">' +
                            '<div><div class="text-muted" style="font-size:.7rem;">NÚMERO DE RECIBO</div><div class="fw-bold fs-5 text-primary">' +
                            p.recibo + '</div></div>' +
                            '<div class="text-end"><div class="text-muted" style="font-size:.7rem;">FECHA</div><div class="fw-medium small">' +
                            fecha + '</div></div></div>' +
                            '<div class="row g-3 mb-4"><div class="col-md-6"><div class="rounded-3 border p-3 h-100">' +
                            '<div class="text-muted mb-2" style="font-size:.7rem;">TIPO DE PAGO</div>' +
                            '<span class="badge ' + tbCls +
                            ' rounded-pill px-3 py-2 fs-13"><i class="ri-money-dollar-circle-line me-1"></i>' +
                            p.tipo_pago + '</span>' +
                            (p.n_comprobante ?
                                '<div class="mt-3"><div class="text-muted" style="font-size:.7rem;">N° COMPROBANTE</div><div class="fw-medium small">' +
                                p.n_comprobante + '</div></div>' : '') +
                            '</div></div><div class="col-md-6"><div class="rounded-3 border p-3 h-100">' +
                            '<div class="text-muted mb-2" style="font-size:.7rem;">RESUMEN FINANCIERO</div>' +
                            '<div class="d-flex justify-content-between small mb-1"><span class="text-muted">Monto cobrado</span><span class="fw-semibold text-success">' +
                            monto.toFixed(2) + ' Bs</span></div>' +
                            (desc > 0 ?
                                '<div class="d-flex justify-content-between small mb-1"><span class="text-muted">Descuento</span><span class="fw-semibold text-warning">-' +
                                desc.toFixed(2) + ' Bs</span></div>' : '') +
                            '<div class="d-flex justify-content-between small pt-2 border-top mt-2"><span class="fw-semibold">Total neto</span><span class="fw-bold text-primary">' +
                            neto.toFixed(2) + ' Bs</span></div>' +
                            '</div></div></div>' +
                            '<div class="mb-1"><div class="text-muted" style="font-size:.7rem;">CUOTAS INCLUIDAS EN ESTE PAGO</div></div>' +
                            '<div class="table-responsive"><table class="table table-hover align-middle mb-0" style="font-size:.84rem;">' +
                            '<thead><tr style="background:#f8f9fa;"><th width="8%" class="border-0 py-2 text-center text-muted fw-semibold" style="font-size:.7rem;">#</th>' +
                            '<th class="border-0 py-2 text-muted fw-semibold" style="font-size:.7rem;">CUOTA</th>' +
                            '<th width="20%" class="border-0 py-2 text-end text-muted fw-semibold" style="font-size:.7rem;">MONTO</th></tr></thead>' +
                            '<tbody>' + cuotasRows + '</tbody>' +
                            '<tfoot><tr style="background:#f8f9fa;"><td colspan="2" class="text-end fw-semibold text-muted small py-2">Total:</td>' +
                            '<td class="text-end fw-bold text-success py-2">' + monto.toFixed(
                            2) + ' Bs</td></tr></tfoot></table></div></div>';
                        $('#contenidoDetallePago').html(html);
                        $('#btnDescargarRecibo').attr('href',
                            '/admin/estudiantes/descargar-recibo/' + pagoId);
                    },
                    error: function() {
                        $('#contenidoDetallePago').html(
                            '<div class="alert alert-danger m-3">Error al cargar los detalles del pago.</div>'
                            );
                    }
                });
            });

            $('#btnImprimirDetalle').on('click', function() {
                var contenido = $('#contenidoDetallePago').html();
                var ventana = window.open('', '_blank');
                ventana.document.write('<html><head><title>Detalle de Pago</title>' +
                    '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">' +
                    '</head><body class="p-4">' + contenido + '</body></html>');
                ventana.document.close();
                ventana.print();
            });
        });
    </script>
@endpush
