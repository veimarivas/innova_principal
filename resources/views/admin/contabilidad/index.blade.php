@extends('layouts.master')
@section('title')
    Contabilidad General
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Sora:wght@300;400;500;600;700;800&display=swap');

        :root {
            --cont-primary: #0d9488;
            --cont-primary-dark: #0f766e;
            --cont-primary-light: rgba(13, 148, 136, 0.08);
            --cont-success: #059669;
            --cont-success-light: rgba(5, 150, 105, 0.12);
            --cont-warning: #d97706;
            --cont-warning-light: rgba(217, 119, 6, 0.12);
            --cont-danger: #dc2626;
            --cont-danger-light: rgba(220, 38, 38, 0.1);
            --cont-info: #0284c7;
            --cont-info-light: rgba(2, 132, 199, 0.1);
            --cont-surface: #ffffff;
            --cont-surface-alt: #f8fafc;
            --cont-border: #e2e8f0;
            --cont-border-light: #f1f5f9;
            --cont-text: #0f172a;
            --cont-text-secondary: #475569;
            --cont-text-muted: #94a3b8;
            --cont-shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.04);
            --cont-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.06), 0 2px 4px -2px rgba(15, 23, 42, 0.06);
            --cont-shadow-lg: 0 10px 15px -3px rgba(15, 23, 42, 0.08), 0 4px 6px -4px rgba(15, 23, 42, 0.04);
        }

        .cont-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--cont-text);
            background: var(--cont-surface-alt);
            max-width: 100%;
            padding: 0;
        }

        .cont-page .container-fluid {
            max-width: 100%;
            padding: 0 24px;
        }

        .cont-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 24px;
            margin-bottom: 32px;
            padding: 32px 36px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            border-radius: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .cont-header::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(13, 148, 136, 0.25) 0%, transparent 65%);
            border-radius: 50%;
            animation: pulse-glow 4s ease-in-out infinite alternate;
        }

        .cont-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 5%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(5, 150, 105, 0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        @keyframes pulse-glow {
            0% {
                opacity: 0.6;
                transform: scale(1);
            }

            100% {
                opacity: 1;
                transform: scale(1.05);
            }
        }

        .cont-header h1 {
            font-family: 'Sora', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.03em;
            position: relative;
            z-index: 1;
            color: white;
        }

        .cont-header p {
            margin: 6px 0 0;
            opacity: 0.75;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
            font-weight: 400;
        }

        .cont-header .header-stats {
            display: flex;
            gap: 24px;
            position: relative;
            z-index: 1;
        }

        .cont-header-stat {
            text-align: center;
        }

        .cont-header-stat-value {
            font-family: 'Sora', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2dd4bf;
        }

        .cont-header-stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.7;
        }

        .cont-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        .cont-kpi-card {
            background: var(--cont-surface);
            border-radius: 18px;
            border: 1px solid var(--cont-border);
            box-shadow: var(--cont-shadow-sm);
            overflow: hidden;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cont-kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .cont-kpi-card.kpi-inscritos::before {
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
        }

        .cont-kpi-card.kpi-programado::before {
            background: linear-gradient(90deg, #64748b, #94a3b8);
        }

        .cont-kpi-card.kpi-pagado::before {
            background: linear-gradient(90deg, #059669, #10b981);
        }

        .cont-kpi-card.kpi-pendiente::before {
            background: linear-gradient(90deg, #dc2626, #f87171);
        }

        .cont-kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--cont-shadow-lg);
        }

        .cont-kpi-body {
            padding: 24px 26px;
        }

        .cont-kpi-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .cont-kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .cont-kpi-trend {
            font-size: 0.78rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .cont-kpi-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1.75rem;
            line-height: 1.2;
            color: var(--cont-text);
            margin-bottom: 6px;
        }

        .cont-kpi-label {
            font-size: 0.82rem;
            color: var(--cont-text-muted);
            font-weight: 500;
        }

        .cont-kpi-bar {
            height: 6px;
            background: var(--cont-border-light);
            border-radius: 0 0 18px 18px;
            overflow: hidden;
        }

        .cont-kpi-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .cont-kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .cont-kpi-icon.inscritos {
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
        }

        .cont-kpi-icon.programado {
            background: rgba(100, 116, 139, 0.12);
            color: #64748b;
        }

        .cont-kpi-icon.pagado {
            background: var(--cont-success-light);
            color: var(--cont-success);
        }

        .cont-kpi-icon.pendiente {
            background: var(--cont-danger-light);
            color: var(--cont-danger);
        }

        .cont-kpi-trend {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .cont-kpi-trend.up {
            background: var(--cont-success-light);
            color: var(--cont-success);
        }

        .cont-kpi-trend.down {
            background: var(--cont-danger-light);
            color: var(--cont-danger);
        }

        .cont-kpi-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1.6rem;
            line-height: 1.2;
            color: var(--cont-text);
            margin-bottom: 4px;
        }

        .cont-kpi-label {
            font-size: 0.8rem;
            color: var(--cont-text-muted);
            font-weight: 500;
        }

        .cont-kpi-bar {
            height: 6px;
            background: var(--cont-border-light);
            border-radius: 0 0 16px 16px;
            overflow: hidden;
        }

        .cont-kpi-bar-fill {
            height: 100%;
            border-radius: 0 0 0 16px;
            transition: width 1s ease-out;
        }

        .cont-charts-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .cont-chart-card {
            background: var(--cont-surface);
            border-radius: 16px;
            border: 1px solid var(--cont-border);
            padding: 24px;
            box-shadow: var(--cont-shadow-sm);
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .cont-chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--cont-border-light);
            flex-shrink: 0;
        }

        .cont-chart-title {
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--cont-text);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .cont-chart-title i {
            font-size: 1.1rem;
            color: var(--cont-primary);
        }

        .cont-chart-card canvas {
            max-width: 100%;
            max-height: 100%;
            flex: 1;
            min-height: 180px;
        }

        .cont-chart-wrapper {
            position: relative;
            flex: 1;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cont-chart-wrapper canvas {
            max-width: 100%;
            max-height: 100%;
        }

        .cont-conceptos-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }

        .cont-concepto-card {
            background: var(--cont-surface);
            border-radius: 14px;
            border: 1px solid var(--cont-border);
            padding: 12px;
            box-shadow: var(--cont-shadow-sm);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .cont-concepto-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--cont-shadow);
        }

        .cont-concepto-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
        }

        .cont-concepto-card.matricula::after {
            background: #6366f1;
        }

        .cont-concepto-card.colegiatura::after {
            background: #0891b2;
        }

        .cont-concepto-card.certificacion::after {
            background: #d97706;
        }

        .cont-concepto-card.otro::after {
            background: #64748b;
        }

        .cont-concepto-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .cont-concepto-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        .cont-concepto-card.matricula .cont-concepto-icon {
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
        }

        .cont-concepto-card.colegiatura .cont-concepto-icon {
            background: rgba(8, 145, 178, 0.12);
            color: #0891b2;
        }

        .cont-concepto-card.certificacion .cont-concepto-icon {
            background: rgba(217, 119, 6, 0.12);
            color: #d97706;
        }

        .cont-concepto-card.otro .cont-concepto-icon {
            background: rgba(100, 116, 139, 0.12);
            color: #64748b;
        }

        .cont-concepto-name {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--cont-text);
        }

        .cont-concepto-stats {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 2px;
            margin-top: 4px;
        }

        .cont-concepto-stat {
            text-align: center;
            padding: 2px;
        }

        .cont-concepto-stat-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.65rem;
            white-space: nowrap;
        }

        .cont-concepto-stat-label {
            font-size: 0.5rem;
            color: var(--cont-text-muted);
        }

        .cont-progress-ring {
            width: 36px;
            height: 36px;
            margin: 0 auto 4px;
            position: relative;
        }

        .cont-progress-ring svg {
            transform: rotate(-90deg);
        }

        .cont-progress-ring-bg {
            fill: none;
            stroke: var(--cont-border-light);
            stroke-width: 4;
        }

        .cont-progress-ring-fill {
            fill: none;
            stroke-width: 4;
            stroke-linecap: round;
            transition: stroke-dashoffset 1s ease-out;
        }

        .cont-progress-ring-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.6rem;
        }

        :first-letter {
            text-transform: uppercase;
        }

        .cont-section-title {
            font-family: 'Sora', sans-serif;
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--cont-text);
            margin-bottom: 20px;
            margin-top: 36px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cont-section-title::before {
            content: '';
            width: 5px;
            height: 26px;
            background: linear-gradient(180deg, var(--cont-primary), var(--cont-primary-dark));
            border-radius: 3px;
        }

        .cont-oferta-card {
            background: var(--cont-surface);
            border-radius: 18px;
            border: 1px solid var(--cont-border);
            box-shadow: var(--cont-shadow-sm);
            margin-bottom: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .cont-oferta-card:hover {
            box-shadow: var(--cont-shadow);
            border-color: var(--cont-primary-light);
        }

        .cont-oferta-header {
            padding: 24px 28px;
            background: linear-gradient(135deg, var(--cont-surface) 0%, var(--cont-surface-alt) 100%);
            border-bottom: 1px solid var(--cont-border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            cursor: pointer;
        }

        .cont-oferta-info {
            flex: 1;
            min-width: 200px;
        }

        .cont-oferta-info h5 {
            margin: 0;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 1.05rem;
            color: var(--cont-text);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cont-oferta-info p {
            margin: 8px 0 0;
            font-size: 0.88rem;
            color: var(--cont-text-secondary);
        }

        .cont-oferta-stats {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .cont-oferta-stat {
            text-align: center;
            min-width: 90px;
        }

        .cont-oferta-stat-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            color: var(--cont-text);
        }

        .cont-oferta-stat-label {
            font-size: 0.65rem;
            color: var(--cont-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-top: 2px;
        }

        .cont-oferta-stat-value.success {
            color: var(--cont-success);
        }

        .cont-oferta-stat-value.danger {
            color: var(--cont-danger);
        }

        .cont-oferta-stat-value.warning {
            color: var(--cont-warning);
        }

        .cont-fase-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .cont-fase-badge.inscripciones {
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
        }

        .cont-fase-badge.desarrollo {
            background: var(--cont-success-light);
            color: var(--cont-success);
        }

        .cont-fase-badge.finalizado {
            background: rgba(148, 163, 184, 0.12);
            color: #64748b;
        }

        .cont-expand-btn {
            background: var(--cont-surface-alt);
            border: 1px solid var(--cont-border);
            color: var(--cont-text-secondary);
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .cont-expand-btn:hover {
            background: var(--cont-primary-light);
            color: var(--cont-primary);
            border-color: var(--cont-primary);
        }

        .cont-details {
            display: none;
            background: var(--cont-surface-alt);
            border-top: 1px solid var(--cont-border-light);
        }

        .cont-details.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cont-concepto-section {
            padding: 24px 28px;
            border-bottom: 1px solid var(--cont-border-light);
        }

        .cont-concepto-section:last-child {
            border-bottom: none;
        }

        .cont-concepto-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .cont-concepto-section-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .cont-concepto-section-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .cont-concepto-section-title {
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            color: var(--cont-text);
        }

        .cont-concepto-section-meta {
            font-size: 0.78rem;
            color: var(--cont-text-muted);
        }

        .cont-concepto-section-stats {
            display: flex;
            gap: 24px;
        }

        .cont-concepto-section-stat {
            text-align: right;
            min-width: 100px;
        }

        .cont-concepto-section-stat-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.92rem;
        }

        .cont-concepto-section-stat-label {
            font-size: 0.65rem;
            color: var(--cont-text-muted);
        }

        .cont-concepto-section-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cont-concepto-section-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .cont-concepto-section-icon.matricula {
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
        }

        .cont-concepto-section-icon.colegiatura {
            background: rgba(8, 145, 178, 0.12);
            color: #0891b2;
        }

        .cont-concepto-section-icon.certificacion {
            background: rgba(217, 119, 6, 0.12);
            color: #d97706;
        }

        .cont-concepto-section-icon.otro {
            background: rgba(100, 116, 139, 0.12);
            color: #64748b;
        }

        .cont-concepto-section-title {
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--cont-text);
        }

        .cont-concepto-section-meta {
            font-size: 0.75rem;
            color: var(--cont-text-muted);
        }

        .cont-concepto-section-stats {
            display: flex;
            gap: 16px;
        }

        .cont-concepto-section-stat {
            text-align: right;
        }

        .cont-concepto-section-stat-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .cont-concepto-section-stat-label {
            font-size: 0.6rem;
            color: var(--cont-text-muted);
        }

        .cont-cuota-detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
            background: var(--cont-surface);
            border-radius: 12px;
            overflow: hidden;
        }

        .cont-cuota-detail-table th {
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
            color: var(--cont-text-secondary);
            background: var(--cont-surface-alt);
            border-bottom: 1px solid var(--cont-border-light);
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .cont-cuota-detail-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--cont-border-light);
        }

        .cont-cuota-detail-table tbody tr:hover {
            background: var(--cont-surface-alt);
        }

        .cont-cuota-row-num {
            font-weight: 600;
            color: var(--cont-text-secondary);
        }

        .cont-cuota-estado-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }

        .cont-cuota-estado-dot.pagado {
            background: #059669;
        }

        .cont-cuota-estado-dot.pendiente {
            background: #d97706;
        }

        .cont-cuota-estado-dot.vencido {
            background: #dc2626;
        }

        .cont-cuota-estado-dot.parcial {
            background: #0284c7;
        }

        .cont-concepto-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 16px;
        }

        .cont-concepto-stat {
            background: var(--cont-surface);
            border: 1px solid var(--cont-border-light);
            border-radius: 10px;
            padding: 14px;
            text-align: center;
            transition: all 0.2s ease;
        }

        .cont-concepto-stat:hover {
            border-color: var(--cont-primary-light);
            background: var(--cont-surface);
        }

        .cont-concepto-stat-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .cont-concepto-stat-label {
            font-size: 0.7rem;
            color: var(--cont-text-muted);
            margin-top: 4px;
        }

        .cont-cuotas-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            background: var(--cont-surface);
            border-radius: 12px;
            overflow: hidden;
        }

        .cont-cuotas-table th {
            padding: 14px 18px;
            text-align: left;
            font-weight: 600;
            color: var(--cont-text-secondary);
            background: var(--cont-surface-alt);
            border-bottom: 1px solid var(--cont-border-light);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .cont-cuotas-table td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--cont-border-light);
        }

        .cont-cuotas-table tr:last-child td {
            border-bottom: none;
        }

        .cont-cuotas-table tbody tr {
            transition: background 0.15s ease;
        }

        .cont-cuotas-table tbody tr:hover {
            background: var(--cont-surface-alt);
        }

        .cont-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .cont-badge-pagado {
            background: var(--cont-success-light);
            color: var(--cont-success);
        }

        .cont-badge-pendiente {
            background: var(--cont-warning-light);
            color: var(--cont-warning);
        }

        .cont-badge-vencido {
            background: var(--cont-danger-light);
            color: var(--cont-danger);
        }

        .cont-badge-parcial {
            background: var(--cont-info-light);
            color: var(--cont-info);
        }

        .cont-empty-state {
            text-align: center;
            padding: 60px 20px;
            background: var(--cont-surface);
            border-radius: 16px;
            border: 1px dashed var(--cont-border);
        }

        .cont-empty-state i {
            font-size: 3.5rem;
            color: var(--cont-text-muted);
            opacity: 0.4;
            margin-bottom: 16px;
        }

        .cont-empty-state p {
            color: var(--cont-text-muted);
            font-size: 0.95rem;
        }

        @media (max-width: 1200px) {
            .cont-kpi-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .cont-charts-row {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .cont-conceptos-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .cont-concepto-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .cont-oferta-stats {
                gap: 16px;
            }

            .cont-oferta-stat {
                min-width: 70px;
            }

            .cont-chart-wrapper {
                min-height: 180px;
            }
        }

        @media (max-width: 768px) {
            .cont-page .container-fluid {
                padding: 0 12px;
            }

            .cont-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
            }

            .cont-header .header-stats {
                width: 100%;
                justify-content: space-between;
                margin-top: 16px;
            }

            .cont-kpi-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .cont-charts-row {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .cont-conceptos-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .cont-oferta-header {
                padding: 16px;
                flex-direction: column;
                align-items: flex-start;
            }

            .cont-oferta-stats {
                flex-wrap: wrap;
                gap: 12px;
                width: 100%;
            }

            .cont-oferta-stat {
                min-width: 60px;
            }

            .cont-concepto-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .cont-oferta-info h5 {
                font-size: 0.9rem;
            }

            .cont-oferta-stat-value {
                font-size: 0.85rem;
            }

            .cont-chart-card {
                padding: 16px;
            }

            .cont-chart-wrapper {
                min-height: 160px;
            }
        }

        /* ── Mejoras gráficos principales ─────────────────────────── */
        .cont-chart-badge {
            background: var(--cont-surface-alt);
            border: 1px solid var(--cont-border);
            color: var(--cont-text-secondary);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        /* Donut con overlay central */
        .chart-doughnut-wrap {
            position: relative;
            height: 210px;
        }

        .chart-center-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
            width: 100%;
        }

        .chart-center-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--cont-text);
            line-height: 1.3;
            white-space: nowrap;
        }

        .chart-center-sub {
            font-size: 0.6rem;
            color: var(--cont-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        /* Leyenda personalizada filas */
        .chart-legend-list {
            margin-top: 14px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .chart-legend-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 10px;
            border-radius: 8px;
            cursor: default;
            transition: background 0.15s;
        }

        .chart-legend-row:hover { background: var(--cont-surface-alt); }

        .chart-legend-swatch {
            width: 10px;
            height: 10px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .chart-legend-name {
            flex: 1;
            font-size: 0.79rem;
            font-weight: 500;
            color: var(--cont-text);
        }

        .chart-legend-amount {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.79rem;
            color: var(--cont-text);
        }

        .chart-legend-pct-badge {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
            min-width: 42px;
            text-align: center;
        }

        /* Barras horizontales CSS (Porcentaje de Cobro) */
        .chart-hbars {
            display: flex;
            flex-direction: column;
            gap: 22px;
            padding: 6px 0;
            flex: 1;
        }

        .chart-hbar-meta {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 8px;
        }

        .chart-hbar-concept {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.83rem;
            font-weight: 600;
            color: var(--cont-text);
        }

        .chart-hbar-concept-dot {
            width: 9px;
            height: 9px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .chart-hbar-right {
            display: flex;
            align-items: baseline;
            gap: 5px;
        }

        .chart-hbar-pct {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1.2rem;
            line-height: 1;
        }

        .chart-hbar-of {
            font-size: 0.7rem;
            color: var(--cont-text-muted);
        }

        .chart-hbar-track {
            height: 13px;
            background: var(--cont-border-light);
            border-radius: 13px;
            overflow: hidden;
        }

        @keyframes hbarGrow {
            from { width: 0; }
            to   { width: var(--tw, 0%); }
        }

        .chart-hbar-fill {
            height: 100%;
            border-radius: 13px;
            width: 0;
            animation: hbarGrow 1.3s cubic-bezier(0.4, 0, 0.2, 1) 0.35s both;
        }

        .chart-hbar-amounts {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }

        .chart-hbar-cobrado {
            font-size: 0.72rem;
            font-weight: 600;
        }

        .chart-hbar-total {
            font-size: 0.72rem;
            color: var(--cont-text-muted);
        }

        /* Estado de pagos - cifra central grande */
        .chart-estado-pct-big {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1.9rem;
            line-height: 1;
        }

        /* Mini tarjetas debajo del donut de estado */
        .chart-estado-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 14px;
        }

        .chart-estado-mini {
            background: var(--cont-surface-alt);
            border-radius: 10px;
            padding: 10px 12px;
            border-left: 3px solid transparent;
        }

        .chart-estado-mini-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.82rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chart-estado-mini-label {
            font-size: 0.65rem;
            color: var(--cont-text-muted);
            margin-top: 3px;
        }

        /* ── Resumen por Concepto rediseñado ─────────────────────── */
        .rc-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .rc-card {
            background: var(--cont-surface);
            border-radius: 18px;
            border: 1px solid var(--cont-border);
            box-shadow: var(--cont-shadow-sm);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .rc-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--cont-shadow);
        }

        .rc-card-accent { height: 4px; }

        .rc-card-body { padding: 22px 24px 20px; }

        .rc-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .rc-icon-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .rc-icon {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .rc-name {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            color: var(--cont-text);
            line-height: 1.3;
        }

        .rc-cuotas {
            font-size: 0.7rem;
            color: var(--cont-text-muted);
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .rc-pct-badge {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1.7rem;
            line-height: 1;
        }

        .rc-pct-label {
            font-size: 0.62rem;
            color: var(--cont-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            text-align: right;
        }

        @keyframes rcFill { from { width: 0; } to { width: var(--tw, 0%); } }

        .rc-track {
            height: 8px;
            background: var(--cont-border-light);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .rc-fill {
            height: 100%;
            border-radius: 8px;
            width: 0;
            animation: rcFill 1.2s cubic-bezier(0.4, 0, 0.2, 1) 0.2s both;
        }

        .rc-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .rc-stat {
            background: var(--cont-surface-alt);
            border-radius: 10px;
            padding: 10px 8px 8px;
            text-align: center;
        }

        .rc-stat-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.78rem;
            line-height: 1.3;
            word-break: break-all;
        }

        .rc-stat-label {
            font-size: 0.62rem;
            color: var(--cont-text-muted);
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        /* ── Detalle por Oferta rediseñado ─────────────────────────── */
        .oferta-card {
            background: var(--cont-surface);
            border-radius: 18px;
            border: 1px solid var(--cont-border);
            box-shadow: var(--cont-shadow-sm);
            margin-bottom: 14px;
            overflow: hidden;
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .oferta-card:hover {
            box-shadow: var(--cont-shadow);
            border-color: rgba(13, 148, 136, 0.25);
        }

        .oferta-head {
            padding: 18px 22px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            align-items: center;
            cursor: pointer;
            background: linear-gradient(135deg, var(--cont-surface) 0%, var(--cont-surface-alt) 100%);
            border-bottom: 1px solid transparent;
            transition: border-color 0.2s;
        }

        .oferta-card.open .oferta-head {
            border-bottom-color: var(--cont-border-light);
        }

        .oferta-head-title {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.96rem;
            color: var(--cont-text);
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            line-height: 1.4;
        }

        .oferta-codigo-chip {
            background: var(--cont-primary-light);
            color: var(--cont-primary);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 9px;
            border-radius: 6px;
            white-space: nowrap;
            font-family: 'DM Sans', sans-serif;
        }

        .oferta-meta-row {
            font-size: 0.81rem;
            color: var(--cont-text-secondary);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .oferta-progress-mini {
            height: 5px;
            background: var(--cont-border-light);
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
            max-width: 380px;
        }

        .oferta-progress-mini-fill {
            height: 100%;
            border-radius: 5px;
            transition: width 1s ease;
        }

        .oferta-head-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .oferta-kpi-strip {
            display: flex;
            gap: 5px;
            align-items: stretch;
        }

        .oferta-kpi {
            text-align: center;
            min-width: 76px;
            padding: 8px 10px;
            background: var(--cont-surface);
            border: 1px solid var(--cont-border-light);
            border-radius: 10px;
        }

        .oferta-kpi-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.86rem;
            line-height: 1.2;
            white-space: nowrap;
        }

        .oferta-kpi-label {
            font-size: 0.58rem;
            color: var(--cont-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-top: 2px;
        }

        .oferta-actions {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        .oferta-link-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 13px;
            background: var(--cont-primary-light);
            color: var(--cont-primary);
            border: 1px solid rgba(13, 148, 136, 0.2);
            border-radius: 8px;
            font-size: 0.74rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .oferta-link-btn:hover {
            background: var(--cont-primary);
            color: white;
            border-color: var(--cont-primary);
            text-decoration: none;
        }

        .oferta-link-btn i { font-size: 0.88rem; }

        .oferta-toggle-btn {
            width: 34px;
            height: 34px;
            background: var(--cont-surface-alt);
            border: 1px solid var(--cont-border);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--cont-text-secondary);
            transition: all 0.2s ease;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .oferta-toggle-btn:hover {
            background: var(--cont-primary-light);
            color: var(--cont-primary);
            border-color: var(--cont-primary);
        }

        /* Panel expandido de oferta */
        .oferta-body {
            display: none;
            background: var(--cont-surface-alt);
        }

        .oferta-body.show {
            display: block;
            animation: slideDown 0.25s ease-out;
        }

        .oferta-conceptos-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
        }

        .oferta-concepto-block {
            padding: 20px 22px;
            border-right: 1px solid var(--cont-border-light);
            position: relative;
        }

        .oferta-concepto-block:last-child { border-right: none; }

        .oferta-cb-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }

        .oferta-cb-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .oferta-cb-name {
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--cont-text);
        }

        .oferta-cb-cuotas {
            font-size: 0.68rem;
            color: var(--cont-text-muted);
            margin-top: 1px;
        }

        .oferta-cb-pct-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 7px;
        }

        .oferta-cb-pct {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            line-height: 1;
        }

        .oferta-cb-pct-label {
            font-size: 0.65rem;
            color: var(--cont-text-muted);
        }

        @keyframes cbFill { from { width: 0; } to { width: var(--tw, 0%); } }

        .oferta-cb-track {
            height: 7px;
            background: var(--cont-border-light);
            border-radius: 7px;
            overflow: hidden;
            margin-bottom: 14px;
        }

        .oferta-cb-fill {
            height: 100%;
            border-radius: 7px;
            width: 0;
            animation: cbFill 1.1s cubic-bezier(0.4, 0, 0.2, 1) 0.5s both;
        }

        .oferta-cb-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }

        .oferta-cb-stat {
            background: var(--cont-surface);
            border-radius: 8px;
            padding: 8px 10px;
            text-align: center;
        }

        .oferta-cb-stat.full { grid-column: span 2; }

        .oferta-cb-stat-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.78rem;
        }

        .oferta-cb-stat-label {
            font-size: 0.6rem;
            color: var(--cont-text-muted);
            margin-top: 2px;
        }

        .oferta-empty-concepto { opacity: 0.45; }

        .oferta-no-data {
            text-align: center;
            padding: 20px 0 10px;
            color: var(--cont-text-muted);
            font-size: 0.8rem;
        }

        .oferta-no-data i {
            font-size: 1.6rem;
            display: block;
            margin-bottom: 6px;
            opacity: 0.35;
        }

        /* Responsive mejoras */
        @media (max-width: 1200px) {
            .rc-grid { grid-template-columns: repeat(2, 1fr); }
            .oferta-kpi-strip { flex-wrap: wrap; }
            .oferta-kpi { min-width: 66px; }
        }

        @media (max-width: 900px) {
            .oferta-head { grid-template-columns: 1fr; }
            .oferta-head-right { flex-wrap: wrap; }
            .oferta-conceptos-grid { grid-template-columns: 1fr; }
            .oferta-concepto-block { border-right: none; border-bottom: 1px solid var(--cont-border-light); }
            .oferta-concepto-block:last-child { border-bottom: none; }
        }

        @media (max-width: 576px) {
            .rc-grid { grid-template-columns: 1fr; }
            .oferta-kpi-strip { gap: 4px; }
            .oferta-kpi { min-width: 58px; padding: 6px 7px; }
        }

        /* ── Tabs principales de Contabilidad (Detalle Completo / Ingresos Reales / Retirados) ── */
        .cont-main-tabs-card {
            background: var(--cont-surface);
            border: 1px solid var(--cont-border);
            border-radius: 16px;
            padding: 8px;
            margin-bottom: 22px;
            box-shadow: var(--cont-shadow-sm);
        }
        .cont-main-tabs { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; border: 0; margin: 0; padding: 0; list-style: none; }
        .cont-main-tabs .nav-item { margin: 0; }
        .cont-main-tab-btn {
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
            color: var(--cont-text);
            font-family: 'Sora','DM Sans',sans-serif;
            text-align: left;
            position: relative;
            transition: all .25s ease;
        }
        .cont-main-tab-btn i {
            grid-row: 1 / span 2;
            font-size: 1.45rem;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--cont-surface-alt);
            border-radius: 10px;
            color: var(--cont-text-muted);
            transition: all .25s ease;
        }
        .cont-main-tab-btn span { font-weight: 700; font-size: .92rem; line-height: 1.2; }
        .cont-main-tab-btn small { font-size: .7rem; font-weight: 500; color: var(--cont-text-muted); line-height: 1.2; }
        .cont-main-tab-btn:hover { background: var(--cont-surface-alt); }
        .cont-main-tab-btn.active {
            background: linear-gradient(135deg, rgba(99,102,241,.07), rgba(13,148,136,.05));
            border-color: rgba(99,102,241,.25);
            box-shadow: 0 4px 12px rgba(99,102,241,.10);
        }
        .cont-main-tab-btn.active i { background: linear-gradient(135deg,#6366f1,#0d9488); color: #fff; }
        .cont-main-tab-btn.active span { color: #1e1b4b; }
        #cont-tab-ingresos.active { background: linear-gradient(135deg, rgba(5,150,105,.08), rgba(16,185,129,.04)); border-color: rgba(5,150,105,.25); box-shadow: 0 4px 12px rgba(5,150,105,.10); }
        #cont-tab-ingresos.active i { background: linear-gradient(135deg,#059669,#10b981); }
        #cont-tab-retirados.active { background: linear-gradient(135deg, rgba(220,38,38,.08), rgba(248,113,113,.04)); border-color: rgba(220,38,38,.25); box-shadow: 0 4px 12px rgba(220,38,38,.10); }
        #cont-tab-retirados.active i { background: linear-gradient(135deg,#dc2626,#f87171); }

        .cont-main-tab-badge {
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

        .cont-main-tab-intro {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            background: var(--cont-surface-alt);
            border: 1px solid var(--cont-border-light);
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 22px;
        }
        .cont-main-tab-intro--danger { background: linear-gradient(135deg, rgba(220,38,38,.04), rgba(248,113,113,.02)); border-color: rgba(220,38,38,.18); }
        .cont-main-tab-intro-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
        .cont-main-tab-intro-title { font-family: 'Sora','DM Sans',sans-serif; font-weight: 700; font-size: 1rem; color: var(--cont-text); margin-bottom: 2px; }
        .cont-main-tab-intro-sub { font-size: .8rem; color: #475569; line-height: 1.5; }

        /* ── Sub-tabs por oferta ── */
        .cont-sub-tabs {
            display: flex;
            gap: 4px;
            border: 0;
            margin: 0 0 14px;
            padding: 6px;
            list-style: none;
            background: var(--cont-surface-alt);
            border-radius: 10px;
        }
        .cont-sub-tabs .nav-item { margin: 0; flex: 1; }
        .cont-sub-tab-btn {
            width: 100%;
            background: transparent;
            border: 0;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: .8rem;
            font-weight: 600;
            color: var(--cont-text-muted);
            cursor: pointer;
            transition: all .2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            position: relative;
        }
        .cont-sub-tab-btn:hover { color: var(--cont-text); }
        .cont-sub-tab-btn.active {
            background: var(--cont-surface);
            color: var(--cont-text);
            box-shadow: var(--cont-shadow-sm);
        }
        .cont-sub-tab-btn i { font-size: .95rem; }
        .cont-sub-tab-badge {
            background: #dc2626; color: #fff; font-size: .6rem; font-weight: 700;
            padding: 1px 7px; border-radius: 20px; min-width: 18px; text-align: center; margin-left: 2px;
        }

        .cont-sub-tab-summary {
            display: flex; flex-wrap: wrap; gap: 8px;
            align-items: center; padding: 8px 14px;
            background: var(--cont-surface-alt);
            border: 1px solid var(--cont-border-light);
            border-radius: 10px; margin-bottom: 12px;
            font-size: .78rem; color: var(--cont-text);
        }
        .cont-sub-tab-summary .sep { color: var(--cont-text-muted); opacity: .5; }
        .cont-sub-tab-summary strong { font-family: 'Sora','DM Sans',sans-serif; }
        .cont-sub-tab-summary--danger { background: linear-gradient(135deg, rgba(220,38,38,.04), rgba(248,113,113,.02)); border-color: rgba(220,38,38,.18); }

        @media (max-width: 900px) {
            .cont-main-tabs { grid-template-columns: 1fr; }
            .cont-main-tab-btn small { font-size: .65rem; }
            .cont-sub-tabs { flex-wrap: wrap; }
            .cont-sub-tabs .nav-item { flex: 1 1 100%; }
        }
    </style>
@endsection

@section('content')
    <?php
    $colorConceptos = [
        'Matrícula' => '#6366f1',
        'Colegiatura' => '#0891b2',
        'Certificación' => '#d97706',
    ];
    
    $totalPagado = $totalesGlobales['total_pagado'];
    $totalPendiente = $totalesGlobales['total_pendiente'];
    $porcentajeGlobal = $totalesGlobales['porcentaje_pagado'];
    $colorPorcentaje = $porcentajeGlobal >= 70 ? '#059669' : ($porcentajeGlobal >= 40 ? '#d97706' : '#dc2626');
    $trendClass = $porcentajeGlobal >= 50 ? 'up' : 'down';
    ?>
    <div class="cont-page" style="padding: 24px;">
        <div class="cont-header">
            <div>
                <h1>Contabilidad General</h1>
                <p>Período: {{ $nombreMes }} {{ $gestion }} | Resumen financiero de todas las ofertas académicas</p>
            </div>
            <div class="header-stats">
                <div class="cont-header-stat">
                    <div class="cont-header-stat-value">{{ number_format($totalesGlobales['total_inscritos']) }}</div>
                    <div class="cont-header-stat-label">Inscritos</div>
                </div>
                <div class="cont-header-stat">
                    <div class="cont-header-stat-value">Bs.
                        {{ number_format($totalesGlobales['total_programado'], 0, ',', '.') }}</div>
                    <div class="cont-header-stat-label">Programado</div>
                </div>
                <div class="cont-header-stat">
                    <div class="cont-header-stat-value" style="color: #34d399;">Bs.
                        {{ number_format($totalesGlobales['total_pagado'], 0, ',', '.') }}</div>
                    <div class="cont-header-stat-label">Cobrado</div>
                </div>
            </div>
        </div>

        <div class="cont-filters"
            style="background:var(--cont-surface);border:1px solid var(--cont-border);border-radius:14px;padding:14px 18px;margin-bottom:22px;box-shadow:var(--cont-shadow-sm);">
            <form method="GET" action="{{ route('admin.contabilidad.dashboard') }}" style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
                <div style="display:flex;flex-direction:column;min-width:140px;">
                    <label style="font-size:0.7rem;font-weight:700;color:var(--cont-text-muted);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:4px;">Gestión</label>
                    <select name="gestion" style="background:var(--cont-surface-alt) !important;border:1px solid var(--cont-border) !important;border-radius:9px !important;padding:.5rem .75rem !important;color:var(--cont-text) !important;font-size:0.85rem !important;">
                        @foreach ($gestiones as $g)
                            <option value="{{ $g }}" {{ $gestion == $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;flex-direction:column;min-width:160px;">
                    <label style="font-size:0.7rem;font-weight:700;color:var(--cont-text-muted);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:4px;">Mes</label>
                    <select name="mes" style="background:var(--cont-surface-alt) !important;border:1px solid var(--cont-border) !important;border-radius:9px !important;padding:.5rem .75rem !important;color:var(--cont-text) !important;font-size:0.85rem !important;">
                        @foreach ($meses as $num => $nombre)
                            <option value="{{ $num }}" {{ $mes == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#0d9488,#0f766e);color:#fff;border:none;border-radius:10px;padding:.5rem 1.1rem;font-size:0.82rem;font-weight:700;cursor:pointer;transition:all .2s;box-shadow:0 3px 10px rgba(13,148,136,0.28);">
                    <i class="ri-filter-3-line"></i> Aplicar
                </button>
                <a href="{{ route('admin.contabilidad.dashboard') }}" style="display:inline-flex;align-items:center;gap:5px;background:transparent;color:var(--cont-text-muted);border:1px solid var(--cont-border);border-radius:10px;padding:.45rem .95rem;font-size:0.8rem;font-weight:600;cursor:pointer;transition:all .2s;text-decoration:none;">
                    <i class="ri-refresh-line"></i> Limpiar
                </a>
            </form>
        </div>
        <div class="cont-chart-card" style="margin-bottom: 24px;">
            <div class="cont-chart-header">
                <div class="cont-chart-title">
                    <i class="ri-line-chart-line"></i>
                    Ingresos Diarios por Concepto - {{ $nombreMes }} {{ $gestion }}
                </div>
            </div>
            <div class="cont-chart-wrapper" style="min-height: 220px;">
                <canvas id="chartIngresosDiarios"></canvas>
            </div>
        </div>

        @php
            $contLabels = ['programado' => 'Programado', 'cobrado' => 'Cobrado', 'pendiente' => 'Pendiente'];
        @endphp

        {{-- ═══════════════ TABS PRINCIPALES DE CONTABILIDAD ═══════════════ --}}
        <div class="cont-main-tabs-card">
            <ul class="cont-main-tabs nav" id="contResumenTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="cont-main-tab-btn nav-link active" id="cont-tab-completo" data-bs-toggle="tab" data-bs-target="#cont-pane-completo" type="button" role="tab">
                        <i class="ri-list-check-2"></i>
                        <span>Detalle Completo</span>
                        <small>Todos los inscritos</small>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="cont-main-tab-btn nav-link" id="cont-tab-ingresos" data-bs-toggle="tab" data-bs-target="#cont-pane-ingresos" type="button" role="tab">
                        <i class="ri-line-chart-line"></i>
                        <span>Ingresos Reales</span>
                        <small>Activos + cobrado de retirados</small>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="cont-main-tab-btn nav-link" id="cont-tab-retirados" data-bs-toggle="tab" data-bs-target="#cont-pane-retirados" type="button" role="tab">
                        <i class="ri-user-unfollow-line"></i>
                        <span>Pérdida por Retiros</span>
                        <small>Estudiantes dados de baja</small>
                        @if (($inscritosRetiradosGlobal ?? 0) > 0)
                            <span class="cont-main-tab-badge">{{ $inscritosRetiradosGlobal }}</span>
                        @endif
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content cont-main-tab-content" id="contResumenTabsContent">

            {{-- ═══ TAB 1: DETALLE COMPLETO ═══ --}}
            <div class="tab-pane fade show active" id="cont-pane-completo" role="tabpanel" aria-labelledby="cont-tab-completo">
                <div class="cont-main-tab-intro">
                    <div class="cont-main-tab-intro-icon" style="background:rgba(99,102,241,.12);color:#6366f1;"><i class="ri-list-check-2"></i></div>
                    <div>
                        <div class="cont-main-tab-intro-title">Detalle Completo</div>
                        <div class="cont-main-tab-intro-sub">Todo lo que se debería cobrar a todos los inscritos del período (activos y retirados).</div>
                    </div>
                </div>
                @include('admin.contabilidad._contabilidad-global-section', [
                    'totales'           => $totalesGlobales,
                    'conceptos'         => $conceptosGlobales,
                    'suffix'            => 'Completo',
                    'labels'            => $contLabels,
                    'kpiCountLabel'     => 'Total Inscritos',
                    'kpiCountIcon'      => 'ri-user-star-line',
                    'kpiCountIconBg'    => 'rgba(99,102,241,.12)',
                    'kpiCountIconColor' => '#6366f1',
                    'pendienteVariant'  => 'normal',
                    'colorConceptos'    => $colorConceptos,
                ])
            </div>

            {{-- ═══ TAB 2: INGRESOS REALES ═══ --}}
            <div class="tab-pane fade" id="cont-pane-ingresos" role="tabpanel" aria-labelledby="cont-tab-ingresos">
                <div class="cont-main-tab-intro">
                    <div class="cont-main-tab-intro-icon" style="background:rgba(5,150,105,.12);color:#059669;"><i class="ri-line-chart-line"></i></div>
                    <div>
                        <div class="cont-main-tab-intro-title">Ingresos Reales</div>
                        <div class="cont-main-tab-intro-sub">
                            Programado de los inscritos activos + lo que ya se cobró a los retirados.
                            El pendiente solo incluye lo cobrable de activos.
                        </div>
                    </div>
                </div>
                @include('admin.contabilidad._contabilidad-global-section', [
                    'totales'           => $totalesGlobalesIngresosReales,
                    'conceptos'         => $conceptosGlobalesIngresosReales,
                    'suffix'            => 'IngresosReales',
                    'labels'            => ['programado' => 'Realizable', 'cobrado' => 'Cobrado', 'pendiente' => 'Por Cobrar'],
                    'kpiCountLabel'     => 'Estudiantes Activos',
                    'kpiCountIcon'      => 'ri-user-follow-line',
                    'kpiCountIconBg'    => 'rgba(5,150,105,.12)',
                    'kpiCountIconColor' => '#059669',
                    'pendienteVariant'  => 'normal',
                    'colorConceptos'    => $colorConceptos,
                ])
            </div>

            {{-- ═══ TAB 3: PÉRDIDA POR RETIROS ═══ --}}
            <div class="tab-pane fade" id="cont-pane-retirados" role="tabpanel" aria-labelledby="cont-tab-retirados">
                <div class="cont-main-tab-intro cont-main-tab-intro--danger">
                    <div class="cont-main-tab-intro-icon" style="background:rgba(220,38,38,.12);color:#dc2626;"><i class="ri-user-unfollow-line"></i></div>
                    <div>
                        <div class="cont-main-tab-intro-title">Pérdida por Retiros</div>
                        <div class="cont-main-tab-intro-sub">
                            Detalle de los estudiantes retirados: lo que se les debía cobrar, lo que se alcanzó a cobrar
                            y lo que ya no se podrá cobrar (pérdida).
                        </div>
                    </div>
                </div>
                @if (($inscritosRetiradosGlobal ?? 0) === 0)
                    <div style="background:var(--cont-surface-alt);border:1px dashed var(--cont-border);border-radius:14px;padding:42px 22px;text-align:center;color:var(--cont-text-muted);">
                        <i class="ri-user-unfollow-line" style="font-size:2.2rem;display:block;margin-bottom:10px;opacity:.55;"></i>
                        <div style="font-size:.95rem;font-weight:600;color:#475569;margin-bottom:4px;">Sin retiros registrados</div>
                        <div style="font-size:.82rem;">No hay estudiantes dados de baja en las ofertas del período.</div>
                    </div>
                @else
                    @include('admin.contabilidad._contabilidad-global-section', [
                        'totales'           => $totalesGlobalesRetiradosFinal,
                        'conceptos'         => $conceptosGlobalesRetirados,
                        'suffix'            => 'Retirados',
                        'labels'            => ['programado' => 'Programado', 'cobrado' => 'Cobrado', 'pendiente' => 'Perdido'],
                        'kpiCountLabel'     => 'Estudiantes Retirados',
                        'kpiCountIcon'      => 'ri-user-unfollow-line',
                        'kpiCountIconBg'    => 'rgba(220,38,38,.12)',
                        'kpiCountIconColor' => '#dc2626',
                        'pendienteVariant'  => 'perdida',
                        'colorConceptos'    => $colorConceptos,
                    ])
                @endif
            </div>

        </div>
        {{-- ═══════════════ FIN TABS PRINCIPALES ═══════════════ --}}

        <h3 class="cont-section-title">Detalle por Oferta Académica</h3>

        @foreach ($ofertasData as $oferta)
            <?php
            $ofFaseClass = match ($oferta['fase']) {
                'Inscripciones' => 'inscripciones',
                'En Desarrollo' => 'desarrollo',
                default => 'finalizado',
            };
            $ofFaseIcon  = $ofFaseClass === 'inscripciones' ? 'user-plus-line' : ($ofFaseClass === 'desarrollo' ? 'progress-3-line' : 'check-line');
            $ofPctClr    = $oferta['porcentaje_pagado'] >= 70 ? '#059669' : ($oferta['porcentaje_pagado'] >= 40 ? '#d97706' : '#dc2626');
            $ofGrad      = $oferta['porcentaje_pagado'] >= 70
                ? 'linear-gradient(90deg,#059669,#34d399)'
                : ($oferta['porcentaje_pagado'] >= 40 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)');
            $ofPctFmt    = number_format(min($oferta['porcentaje_pagado'], 100), 2, '.', '');
            ?>
            <div class="oferta-card" id="card-{{ $oferta['id'] }}">

                {{-- Cabecera de la oferta --}}
                <div class="oferta-head" onclick="toggleOferta('{{ $oferta['id'] }}')">
                    <div>
                        <div class="oferta-head-title">
                            <span class="oferta-codigo-chip">{{ $oferta['codigo'] }}</span>
                            {{ $oferta['programa'] ?? 'Programa' }}
                        </div>
                        <div class="oferta-meta-row">
                            <i class="ri-building-2-line"></i>
                            {{ $oferta['nombre'] }}
                            <span class="cont-fase-badge {{ $ofFaseClass }}" style="padding:3px 9px; font-size:0.68rem; margin-left:4px;">
                                <i class="ri-{{ $ofFaseIcon }}"></i> {{ $oferta['fase'] ?? '—' }}
                            </span>
                        </div>
                        <div class="oferta-progress-mini">
                            <div class="oferta-progress-mini-fill" style="width:{{ $ofPctFmt }}%; background:{{ $ofGrad }};"></div>
                        </div>
                    </div>

                    <div class="oferta-head-right">
                        <div class="oferta-kpi-strip">
                            <div class="oferta-kpi">
                                <div class="oferta-kpi-value">{{ $oferta['inscritos'] }}</div>
                                <div class="oferta-kpi-label">Inscritos</div>
                            </div>
                            <div class="oferta-kpi">
                                <div class="oferta-kpi-value">{{ number_format($oferta['total_programado'], 0, ',', '.') }}</div>
                                <div class="oferta-kpi-label">Prog. Bs.</div>
                            </div>
                            <div class="oferta-kpi">
                                <div class="oferta-kpi-value" style="color:#059669;">{{ number_format($oferta['total_pagado'], 0, ',', '.') }}</div>
                                <div class="oferta-kpi-label">Cobrado Bs.</div>
                            </div>
                            <div class="oferta-kpi">
                                <div class="oferta-kpi-value" style="color:#dc2626;">{{ number_format($oferta['total_pendiente'], 0, ',', '.') }}</div>
                                <div class="oferta-kpi-label">Pendiente Bs.</div>
                            </div>
                            <div class="oferta-kpi">
                                <div class="oferta-kpi-value" style="color:{{ $ofPctClr }};">{{ number_format($oferta['porcentaje_pagado'], 1) }}%</div>
                                <div class="oferta-kpi-label">% Cobrado</div>
                            </div>
                        </div>
                        <div class="oferta-actions">
                            <a href="{{ url('/admin/posgrads/ofertas/' . $oferta['id'] . '/detalle') }}"
                               class="oferta-link-btn"
                               onclick="event.stopPropagation();"
                               title="Ver detalle completo de la oferta">
                                <i class="ri-external-link-line"></i> Ver oferta
                            </a>
                            <button class="oferta-toggle-btn" id="btn-{{ $oferta['id'] }}" type="button"
                                    onclick="event.stopPropagation(); toggleOferta('{{ $oferta['id'] }}');">
                                <i class="ri-arrow-down-s-line"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Detalle expandido por concepto (3 sub-tabs) --}}
                <div id="oferta-{{ $oferta['id'] }}" class="oferta-body">
                    <ul class="cont-sub-tabs nav" id="ofertaSubTabs-{{ $oferta['id'] }}" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="cont-sub-tab-btn nav-link active" data-bs-toggle="tab" data-bs-target="#oferta-{{ $oferta['id'] }}-completo" type="button" role="tab">
                                <i class="ri-list-check-2"></i> Detalle Completo
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="cont-sub-tab-btn nav-link" data-bs-toggle="tab" data-bs-target="#oferta-{{ $oferta['id'] }}-ingresos" type="button" role="tab">
                                <i class="ri-line-chart-line"></i> Ingresos Reales
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="cont-sub-tab-btn nav-link" data-bs-toggle="tab" data-bs-target="#oferta-{{ $oferta['id'] }}-retirados" type="button" role="tab">
                                <i class="ri-user-unfollow-line"></i> Pérdida por Retiros
                                @if (($oferta['inscritos_retirados'] ?? 0) > 0)
                                    <span class="cont-sub-tab-badge">{{ $oferta['inscritos_retirados'] }}</span>
                                @endif
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content cont-sub-tab-content">
                        {{-- Completo --}}
                        <div class="tab-pane fade show active" id="oferta-{{ $oferta['id'] }}-completo" role="tabpanel">
                            <div class="cont-sub-tab-summary">
                                <span><i class="ri-user-line"></i> {{ $oferta['inscritos'] }} inscritos</span>
                                <span class="sep">·</span>
                                <span>Prog. <strong>Bs. {{ number_format($oferta['total_programado'], 0, ',', '.') }}</strong></span>
                                <span class="sep">·</span>
                                <span style="color:#059669;">Cobrado <strong>Bs. {{ number_format($oferta['total_pagado'], 0, ',', '.') }}</strong></span>
                                <span class="sep">·</span>
                                <span style="color:#dc2626;">Pendiente <strong>Bs. {{ number_format($oferta['total_pendiente'], 0, ',', '.') }}</strong></span>
                            </div>
                            @include('admin.contabilidad._contabilidad-oferta-conceptos', [
                                'resumen' => $oferta['resumen_por_concepto'],
                                'colorConceptos' => $colorConceptos,
                                'labels' => ['cobrado' => 'Cobrado', 'pendiente' => 'Pendiente', 'programado' => 'Programado'],
                                'pendienteVariant' => 'normal',
                            ])
                        </div>

                        {{-- Ingresos Reales --}}
                        <div class="tab-pane fade" id="oferta-{{ $oferta['id'] }}-ingresos" role="tabpanel">
                            <div class="cont-sub-tab-summary">
                                <span><i class="ri-user-follow-line" style="color:#059669;"></i> {{ $oferta['inscritos_activos'] }} activos</span>
                                <span class="sep">·</span>
                                <span>Realizable <strong>Bs. {{ number_format($oferta['total_programado_ingresos'], 0, ',', '.') }}</strong></span>
                                <span class="sep">·</span>
                                <span style="color:#059669;">Cobrado <strong>Bs. {{ number_format($oferta['total_pagado_ingresos'], 0, ',', '.') }}</strong></span>
                                <span class="sep">·</span>
                                <span style="color:#dc2626;">Por Cobrar <strong>Bs. {{ number_format($oferta['total_pendiente_ingresos'], 0, ',', '.') }}</strong></span>
                            </div>
                            @include('admin.contabilidad._contabilidad-oferta-conceptos', [
                                'resumen' => $oferta['resumen_por_concepto_ingresos_reales'],
                                'colorConceptos' => $colorConceptos,
                                'labels' => ['cobrado' => 'Cobrado', 'pendiente' => 'Por Cobrar', 'programado' => 'Realizable'],
                                'pendienteVariant' => 'normal',
                            ])
                        </div>

                        {{-- Retirados --}}
                        <div class="tab-pane fade" id="oferta-{{ $oferta['id'] }}-retirados" role="tabpanel">
                            @if (($oferta['inscritos_retirados'] ?? 0) === 0)
                                <div style="background:var(--cont-surface-alt);border:1px dashed var(--cont-border);border-radius:12px;padding:28px 18px;text-align:center;color:var(--cont-text-muted);">
                                    <i class="ri-user-unfollow-line" style="font-size:1.6rem;display:block;margin-bottom:6px;opacity:.55;"></i>
                                    <div style="font-size:.85rem;font-weight:600;color:#475569;">Sin retiros en esta oferta</div>
                                </div>
                            @else
                                <div class="cont-sub-tab-summary cont-sub-tab-summary--danger">
                                    <span><i class="ri-user-unfollow-line" style="color:#dc2626;"></i> {{ $oferta['inscritos_retirados'] }} retirados</span>
                                    <span class="sep">·</span>
                                    <span>Prog. <strong>Bs. {{ number_format($oferta['total_programado_retirados'], 0, ',', '.') }}</strong></span>
                                    <span class="sep">·</span>
                                    <span style="color:#059669;">Cobrado <strong>Bs. {{ number_format($oferta['total_pagado_retirados'], 0, ',', '.') }}</strong></span>
                                    <span class="sep">·</span>
                                    <span style="color:#991b1b;">Perdido <strong>Bs. {{ number_format($oferta['total_pendiente_retirados'], 0, ',', '.') }}</strong></span>
                                </div>
                                @include('admin.contabilidad._contabilidad-oferta-conceptos', [
                                    'resumen' => $oferta['resumen_por_concepto_retirados'],
                                    'colorConceptos' => $colorConceptos,
                                    'labels' => ['cobrado' => 'Cobrado', 'pendiente' => 'Perdido', 'programado' => 'Programado'],
                                    'pendienteVariant' => 'perdida',
                                ])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if (count($ofertasData) === 0)
            <div class="cont-empty-state">
                <i class="ri-calculator-line"></i>
                <p>No hay ofertas académicas con información contable.</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        function toggleOferta(id) {
            const body = document.getElementById('oferta-' + id);
            const card = document.getElementById('card-' + id);
            const btn  = document.getElementById('btn-' + id);
            const icon = btn ? btn.querySelector('i') : null;

            if (body.classList.contains('show')) {
                body.classList.remove('show');
                card.classList.remove('open');
                if (icon) { icon.className = 'ri-arrow-down-s-line'; }
            } else {
                body.classList.add('show');
                card.classList.add('open');
                if (icon) { icon.className = 'ri-arrow-up-s-line'; }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const conceptos = ['Matrícula', 'Colegiatura', 'Certificación'];
            const colores = ['#6366f1', '#0891b2', '#d97706'];

            const datasetsResumen = {
                'Completo':       @json($conceptosGlobales ?? []),
                'IngresosReales': @json($conceptosGlobalesIngresosReales ?? []),
                'Retirados':      @json($conceptosGlobalesRetirados ?? []),
            };
            const renderedSections = {};

            function renderSection(suffix) {
                if (renderedSections[suffix]) return;
                const data = datasetsResumen[suffix];
                if (!data) return;

                const totales = conceptos.map(c => Number((data[c] || {}).total) || 0);
                const pagados = conceptos.map(c => Number((data[c] || {}).pagado) || 0);
                const totalProg = totales.reduce((a, b) => a + b, 0);
                const totalPag  = pagados.reduce((a, b) => a + b, 0);
                const totalPend = totalProg - totalPag;

                const ctx1 = document.getElementById('chartConceptos' + suffix);
                if (ctx1) {
                    new Chart(ctx1, {
                        type: 'doughnut',
                        data: {
                            labels: conceptos,
                            datasets: [{
                                data: totales,
                                backgroundColor: colores,
                                hoverBackgroundColor: colores.map(c => c + 'dd'),
                                borderWidth: 3,
                                borderColor: '#ffffff',
                                hoverOffset: 12,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '68%',
                            animation: { animateRotate: true, duration: 900 },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) {
                                            const sum = totales.reduce((a, b) => a + b, 0);
                                            const pct = sum > 0 ? (ctx.parsed / sum * 100).toFixed(1) : 0;
                                            return `  Bs. ${ctx.parsed.toLocaleString('es-BO')}  (${pct}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    const legendEl = document.getElementById('legendConceptos' + suffix);
                    if (legendEl) {
                        legendEl.innerHTML = '';
                        conceptos.forEach(function(label, i) {
                            const distPct = totalProg > 0 ? (totales[i] / totalProg * 100).toFixed(1) : '0.0';
                            const row = document.createElement('div');
                            row.className = 'chart-legend-row';
                            row.innerHTML =
                                `<span class="chart-legend-swatch" style="background:${colores[i]};"></span>` +
                                `<span class="chart-legend-name">${label}</span>` +
                                `<span class="chart-legend-amount">Bs. ${totales[i].toLocaleString('es-BO')}</span>` +
                                `<span class="chart-legend-pct-badge" style="background:${colores[i]}22;color:${colores[i]};">${distPct}%</span>`;
                            legendEl.appendChild(row);
                        });
                    }
                }

                const ctx3 = document.getElementById('chartEstado' + suffix);
                if (ctx3) {
                    const pendColor = (suffix === 'Retirados') ? '#991b1b' : '#f1f5f9';
                    const pendHover = (suffix === 'Retirados') ? '#7f1d1d' : '#e2e8f0';
                    new Chart(ctx3, {
                        type: 'doughnut',
                        data: {
                            labels: (suffix === 'Retirados') ? ['Cobrado', 'Perdido'] : ['Cobrado', 'Pendiente'],
                            datasets: [{
                                data: [totalPag, totalPend],
                                backgroundColor: ['#059669', pendColor],
                                hoverBackgroundColor: ['#047857', pendHover],
                                borderWidth: 3,
                                borderColor: '#ffffff',
                                hoverOffset: 10,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '72%',
                            animation: { animateRotate: true, duration: 1000 },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) { return `  Bs. ${ctx.parsed.toLocaleString('es-BO')}`; }
                                    }
                                }
                            }
                        }
                    });
                }

                renderedSections[suffix] = true;
            }

            renderSection('Completo');
            ['cont-tab-completo', 'cont-tab-ingresos', 'cont-tab-retirados'].forEach(function(id) {
                const btn = document.getElementById(id);
                if (!btn) return;
                btn.addEventListener('shown.bs.tab', function() {
                    const target = btn.getAttribute('data-bs-target') || '';
                    if (target.indexOf('ingresos') !== -1) renderSection('IngresosReales');
                    else if (target.indexOf('retirados') !== -1) renderSection('Retirados');
                    else renderSection('Completo');
                });
            });

            // Gráfico de Ingresos Diarios
            new Chart(document.getElementById('chartIngresosDiarios'), {
                type: 'line',
                data: {
                    labels: {{ json_encode($labelsDias) }},
                    datasets: [{
                            label: 'Matrícula',
                            data: {{ json_encode($datosMatricula) }},
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.15)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 3,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Colegiatura',
                            data: {{ json_encode($datosColegiatura) }},
                            borderColor: '#0891b2',
                            backgroundColor: 'rgba(8, 145, 178, 0.15)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 3,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Certificación',
                            data: {{ json_encode($datosCertificacion) }},
                            borderColor: '#d97706',
                            backgroundColor: 'rgba(217, 119, 6, 0.15)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 3,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    family: "'DM Sans', sans-serif",
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#1a1a1a',
                            bodyColor: '#3d2810',
                            borderColor: 'rgba(0,0,0,0.08)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Bs. ' + context.parsed.y
                                        .toLocaleString('es-BO');
                                },
                                afterBody: function(items) {
                                    let total = 0;
                                    items.forEach(i => total += i.parsed.y);
                                    return ['', '─────────────────', 'Total: Bs. ' + total.toLocaleString('es-BO')];
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                font: {
                                    family: "'DM Sans', sans-serif"
                                },
                                callback: function(value) {
                                    return 'Bs. ' + value.toLocaleString('es-BO');
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'DM Sans', sans-serif"
                                },
                                maxTicksLimit: 15
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
