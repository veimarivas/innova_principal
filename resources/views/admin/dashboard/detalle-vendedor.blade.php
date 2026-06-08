@extends('layouts.master')
@section('title', 'Inscripciones de ' . trim($persona->nombres . ' ' . $persona->apellido_paterno))

@section('css')
    <style>
        :root {
            --dash-primary: #9a4904;
            --dash-primary-light: rgba(154, 73, 4, 0.08);
            --dash-primary-dark: #7a3b03;
            --dash-accent: #fc7b04;
            --dash-surface: #f8fafc;
            --dash-border: #e2e8f0;
            --dash-text: #1e293b;
            --dash-text-muted: #64748b;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
        }

        .detalle-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--dash-text);
            animation: fadeInUp 0.45s ease-out;
            padding-bottom: 80px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== SECTION LABEL ===== */
        .section-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: var(--dash-text-muted);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--dash-border);
        }

        /* ===== HEADER ===== */
        .detalle-header {
            margin-bottom: 24px;
            padding: 0;
            background: linear-gradient(135deg, #7a3202 0%, #a84b03 40%, #d46204 75%, #fc7b04 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 36px rgba(154, 73, 4, 0.38);
        }

        .header-orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, transparent 70%);
        }

        .header-orb-1 {
            width: 340px;
            height: 340px;
            top: -100px;
            right: -60px;
        }

        .header-orb-2 {
            width: 200px;
            height: 200px;
            bottom: -70px;
            left: 30%;
        }

        .header-orb-3 {
            width: 120px;
            height: 120px;
            top: -20px;
            left: 8%;
            opacity: 0.5;
        }

        .header-orb-4 {
            width: 80px;
            height: 80px;
            bottom: 10px;
            right: 28%;
            opacity: 0.4;
        }

        /* Main content row */
        .header-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            padding: 28px 32px 22px;
            position: relative;
            z-index: 1;
        }

        .detalle-header-left {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        /* Avatar con anillo doble */
        .avatar-ring {
            position: relative;
            flex-shrink: 0;
        }

        .avatar-ring::before {
            content: '';
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.35);
        }

        .avatar-ring::after {
            content: '';
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .detalle-avatar {
            width: 92px;
            height: 92px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.3);
            display: block;
        }

        .header-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.32);
            border-radius: 20px;
            padding: 3px 13px;
            font-size: 0.73rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            margin-bottom: 7px;
            backdrop-filter: blur(4px);
        }

        .header-info h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            margin: 0 0 8px;
            letter-spacing: -0.02em;
            color: #ffffff;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .header-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
        }

        .header-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            opacity: 0.88;
        }

        .header-meta-item i {
            font-size: 0.9rem;
            opacity: 0.75;
        }

        .detalle-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            opacity: 0.75;
            margin-top: 4px;
        }

        .detalle-breadcrumb a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: color 0.2s;
        }

        .detalle-breadcrumb a:hover {
            color: #fff;
            opacity: 1;
        }

        .detalle-breadcrumb .sep {
            opacity: 0.4;
        }

        .detalle-breadcrumb .cur {
            color: #fff;
            font-weight: 500;
            opacity: 1;
        }

        .header-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, 0.16);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.32);
            padding: 10px 22px;
            border-radius: 24px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.22s;
            white-space: nowrap;
            backdrop-filter: blur(4px);
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.28);
            color: white;
            transform: translateX(-3px);
            border-color: rgba(255, 255, 255, 0.55);
        }

        /* Quick stat chips inside header */
        .header-chips {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .hchip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(0, 0, 0, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 0.78rem;
            font-weight: 700;
            backdrop-filter: blur(4px);
        }

        .hchip .hchip-num {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .hchip.c-total {
            border-color: rgba(255, 255, 255, 0.35);
        }

        .hchip.c-insc {
            background: rgba(22, 163, 74, 0.3);
            border-color: rgba(74, 222, 128, 0.4);
        }

        .hchip.c-pre {
            background: rgba(245, 158, 11, 0.3);
            border-color: rgba(251, 191, 36, 0.4);
        }


        /* ===== KPI CARDS (contabilidad style) ===== */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .kpi-card {
            background: var(--cont-surface, #fff);
            border-radius: 18px;
            border: 1px solid var(--dash-border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .kpi-card.kpi-total::before { background: linear-gradient(90deg, #9a4904, #fc7b04); }
        .kpi-card.kpi-insc::before  { background: linear-gradient(90deg, #16a34a, #4ade80); }
        .kpi-card.kpi-pre::before   { background: linear-gradient(90deg, #d97706, #fbbf24); }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .kpi-body {
            padding: 22px 24px;
        }

        .kpi-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .kpi-card.kpi-total .kpi-icon { background: rgba(154,73,4,0.12); color: #9a4904; }
        .kpi-card.kpi-insc .kpi-icon  { background: rgba(22,163,74,0.12); color: #16a34a; }
        .kpi-card.kpi-pre .kpi-icon   { background: rgba(217,119,6,0.12); color: #d97706; }

        .kpi-trend {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
        }

        .kpi-trend.total { background: rgba(154,73,4,0.1); color: #9a4904; }
        .kpi-trend.insc  { background: rgba(22,163,74,0.1); color: #16a34a; }
        .kpi-trend.pre   { background: rgba(217,119,6,0.1); color: #d97706; }

        .kpi-value {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            font-size: 2rem;
            line-height: 1.2;
            color: var(--dash-text);
            margin-bottom: 4px;
        }

        .kpi-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--dash-text-muted);
        }

        .kpi-bar {
            height: 6px;
            background: var(--dash-surface);
            border-radius: 0 0 18px 18px;
            overflow: hidden;
        }

        .kpi-bar-fill {
            height: 100%;
            border-radius: 0 0 0 18px;
            transition: width 0.7s cubic-bezier(.4,0,.2,1);
        }

        .kpi-card.kpi-total .kpi-bar-fill { background: linear-gradient(90deg, #9a4904, #fc7b04); }
        .kpi-card.kpi-insc .kpi-bar-fill  { background: linear-gradient(90deg, #16a34a, #4ade80); }
        .kpi-card.kpi-pre .kpi-bar-fill   { background: linear-gradient(90deg, #d97706, #fbbf24); }

        /* ===== TIPOS ROW ===== */
        .tipos-row {
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid var(--dash-border);
            box-shadow: var(--shadow-sm);
            padding: 16px 22px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 28px;
        }

        .tipos-row-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--dash-text-muted);
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-right: 4px;
        }

        .tipos-row-label i { color: var(--dash-primary); }

        .tipo-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 14px 7px 10px;
            border-radius: 40px;
            border: 1.5px solid transparent;
            cursor: default;
            transition: all 0.2s;
            background: white;
        }

        .tipo-chip:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .tipo-chip-icon {
            width: 30px; height: 30px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .tipo-chip-body { line-height: 1.2; }

        .tipo-chip-num {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
            display: block;
        }

        .tipo-chip-name {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--dash-text-muted);
            display: block;
        }

        .tipo-chip-pct {
            font-size: 0.65rem;
            color: #94a3b8;
        }

        /* ===== FILTER BAR ===== */
        .filter-bar {
            background: var(--cont-surface, #fff);
            border-radius: var(--radius-lg);
            padding: 18px 22px;
            margin-bottom: 28px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--dash-border);
        }

        .filter-bar-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--dash-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .filter-bar-title i { color: var(--dash-primary); }

        .filter-bar-row {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-group {
            display:flex; flex-direction:column; min-width:150px;
        }

        .filter-group label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--dash-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        .filter-group label i { font-size:0.75rem; color:var(--dash-primary); }

        .filter-select {
            width: 100%;
            padding: .5rem .75rem;
            border: 1px solid var(--dash-border);
            border-radius: 9px;
            font-size: 0.85rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--dash-text);
            background: var(--dash-surface);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
            transition: all 0.2s;
        }
        .filter-select:focus {
            outline: none;
            border-color: var(--dash-primary);
            box-shadow: 0 0 0 3px rgba(154,73,4,0.1);
            background-color: white;
        }

        .filter-actions {
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }

        .btn-filtrar {
            display:inline-flex; align-items:center; gap:6px;
            padding: .5rem 1.1rem;
            background: linear-gradient(135deg, #9a4904 0%, #df6a04 55%, #fc7b04 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.82rem;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all 0.25s;
            white-space: nowrap;
            box-shadow: 0 3px 10px rgba(154,73,4,0.28);
        }
        .btn-filtrar:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(154,73,4,0.38);
        }
        .btn-filtrar:active { transform: translateY(0); }

        .btn-limpiar {
            display:inline-flex; align-items:center; gap:5px;
            padding: .45rem .95rem;
            background: transparent;
            color: var(--dash-text-muted);
            border: 1px solid var(--dash-border);
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-limpiar:hover {
            background: var(--dash-surface);
            border-color: #cbd5e1;
            color: var(--dash-text);
        }

        /* ===== CHART CARD ===== */
        .chart-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--dash-border);
            border-top: 4px solid var(--dash-primary);
            overflow: hidden;
            position: relative;
            transition: box-shadow 0.25s;
            height: 100%;
        }

        .chart-card:hover {
            box-shadow: var(--shadow-md);
        }

        .chart-card-header {
            padding: 15px 22px;
            border-bottom: 1px solid var(--dash-border);
            background: linear-gradient(135deg, #fefefe 0%, var(--dash-surface) 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chart-card-header h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            margin: 0;
            font-size: 0.95rem;
            color: var(--dash-text);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .chart-card-header h6 i {
            color: var(--dash-primary);
        }

        .period-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            border-radius: 20px;
            background: rgba(154, 73, 4, 0.1);
            color: var(--dash-primary);
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* ===== TABLE CARD ===== */
        .table-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--dash-border);
            border-top: 4px solid var(--dash-primary);
            overflow: hidden;
            position: relative;
        }

        .section-title-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 15px 22px;
            border-bottom: 1px solid var(--dash-border);
            background: linear-gradient(135deg, #fefefe 0%, var(--dash-surface) 100%);
        }

        .section-title-bar h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--dash-text);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title-bar h5 i {
            color: var(--dash-primary);
        }

        .table-header-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-input-wrap {
            position: relative;
        }

        .search-input-wrap i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
            pointer-events: none;
        }

        .search-input {
            padding: 7px 12px 7px 32px;
            border: 1px solid var(--dash-border);
            border-radius: var(--radius-sm);
            font-size: 0.84rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--dash-text);
            width: 195px;
            transition: all 0.22s;
            background: var(--dash-surface);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--dash-primary);
            box-shadow: 0 0 0 3px rgba(154, 73, 4, 0.1);
            width: 230px;
            background: white;
        }

        /* ===== INSCRIPCION LIST ===== */
        .ins-list {
            padding: 0;
        }

        .ins-row {
            display: grid;
            grid-template-columns: 36px 1fr auto;
            align-items: center;
            gap: 16px;
            padding: 16px 22px;
            border-bottom: 1px solid rgba(226,232,240,0.6);
            transition: background 0.15s;
            position: relative;
        }

        .ins-row:last-child {
            border-bottom: none;
        }

        .ins-row:hover {
            background: linear-gradient(90deg, rgba(154,73,4,0.03), rgba(252,123,4,0.05));
        }

        /* índice */
        .ins-idx {
            font-size: 0.72rem;
            font-weight: 700;
            color: #94a3b8;
            text-align: center;
            line-height: 1;
            background: var(--dash-surface);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--dash-border);
            font-family: 'Outfit', sans-serif;
        }

        /* bloque central: estudiante + programa */
        .ins-main {
            min-width: 0;
        }

        .ins-top {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .student-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--dash-primary), var(--dash-accent));
        }

        .student-name-link {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--dash-text);
            text-decoration: none;
            transition: color 0.18s;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .student-name-link:hover {
            color: var(--dash-primary);
        }

        .ins-bottom {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 4px;
        }

        .program-link {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--dash-primary);
            text-decoration: none;
            line-height: 1.3;
            transition: color 0.18s;
        }

        .program-link:hover {
            color: var(--dash-primary-dark);
            text-decoration: underline;
        }

        .ins-sep {
            color: #cbd5e1;
            font-size: 0.7rem;
        }

        .sede-name {
            font-size: 0.72rem;
            color: var(--dash-text-muted);
            display: inline-flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap;
        }

        .sede-name i {
            color: var(--dash-primary);
            font-size: 0.7rem;
        }

        /* bloque derecho: tipo + estado + fecha */
        .ins-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
            flex-shrink: 0;
        }

        .ins-meta-top {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .estado-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            white-space: nowrap;
            letter-spacing: 0.02em;
        }

        .estado-inscrito {
            background: rgba(22, 163, 74, 0.12);
            color: #15803d;
            border: 1px solid rgba(22, 163, 74, 0.18);
        }

        .estado-preinscrito {
            background: rgba(217, 119, 6, 0.12);
            color: #b45309;
            border: 1px solid rgba(217, 119, 6, 0.18);
        }

        .tipo-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 0.67rem;
            font-weight: 700;
            background: rgba(154, 73, 4, 0.08);
            color: var(--dash-primary);
            white-space: nowrap;
            border: 1px solid rgba(154, 73, 4, 0.15);
        }

        .fecha-text {
            font-variant-numeric: tabular-nums;
            color: #94a3b8;
            font-size: 0.72rem;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        /* list header */
        .ins-list-header {
            display: grid;
            grid-template-columns: 36px 1fr auto;
            gap: 16px;
            padding: 10px 22px;
            background: linear-gradient(180deg, var(--dash-surface), #f1f5f9);
            border-bottom: 2px solid var(--dash-border);
        }

        .ins-list-header span {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--dash-text-muted);
        }

        .ins-list-header span:last-child {
            text-align: right;
        }

        /* ===== LOADING OVERLAY ===== */
        .loading-overlay {
            display: none;
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.82);
            z-index: 20;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
        }

        .loading-overlay.active {
            display: flex;
        }

        .spinner {
            width: 36px;
            height: 36px;
            border: 3px solid rgba(154, 73, 4, 0.18);
            border-top-color: var(--dash-primary);
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            padding: 56px 24px;
            text-align: center;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: #cbd5e1;
            display: block;
            margin-bottom: 14px;
        }

        .empty-state h6 {
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: var(--dash-text-muted);
            margin-bottom: 5px;
        }

        .empty-state p {
            color: #94a3b8;
            margin: 0;
            font-size: 0.88rem;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991px) {
            .stats-row {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 767px) {
            .header-body {
                flex-direction: column;
                align-items: flex-start;
                padding: 22px 20px 18px;
            }

            .header-actions {
                align-items: flex-start;
            }

            .header-info h4 {
                font-size: 1.3rem;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .stat-block {
                border-right: none;
                border-bottom: 1px solid var(--dash-border);
            }

            .stat-block:last-child {
                border-bottom: none;
            }

            .filter-bar-row {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                min-width: 100%;
            }

            .filter-actions {
                flex-direction: row;
            }

            .btn-filtrar,
            .btn-limpiar {
                flex: 1;
                justify-content: center;
            }

            .stat-value {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .detalle-avatar {
                width: 72px;
                height: 72px;
            }

            .tipo-chip {
                padding: 6px 11px 6px 8px;
            }

            .dash-table thead th,
            .dash-table tbody td {
                padding: 9px 10px;
            }

            .search-input {
                width: 150px;
            }

            .search-input:focus {
                width: 170px;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $nombreCompleto = trim(
            $persona->nombres . ' ' . $persona->apellido_paterno . ' ' . ($persona->apellido_materno ?? ''),
        );
        $totalInscritos = $inscripciones->where('estado', 'Inscrito')->count();
        $totalPreInscritos = $inscripciones->where('estado', 'Pre-Inscrito')->count();
        $totalInscripciones = $inscripciones->count();

        $sexo = strtoupper(trim($persona->sexo ?? ''));
        $avatar = ($persona->fotografia && file_exists(public_path('images/personas/' . $persona->fotografia)))
            ? asset('images/personas/' . $persona->fotografia)
                : ($sexo === 'M'
                    ? asset('images/chico.png')
                    : asset('images/mujer.png'));

        $pctInscritos = $totalInscripciones > 0 ? round(($totalInscritos / $totalInscripciones) * 100) : 0;
        $pctPreInscritos = $totalInscripciones > 0 ? round(($totalPreInscritos / $totalInscripciones) * 100) : 0;

        $tipoColors = ['#9a4904', '#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444', '#14b8a6', '#ec4899'];
        $tipoIcons = [
            'Maestría' => 'ri-graduation-cap-line',
            'Diplomado' => 'ri-medal-line',
            'Especialidad' => 'ri-star-line',
            'Doctorado' => 'ri-award-line',
            'Curso' => 'ri-book-open-line',
        ];
        $tipoColorIdx = 0;
    @endphp

    <div class="detalle-page">

        {{-- ===== HEADER ===== --}}
        <div class="detalle-header">
            <div class="header-orb header-orb-1"></div>
            <div class="header-orb header-orb-2"></div>
            <div class="header-orb header-orb-3"></div>
            <div class="header-orb header-orb-4"></div>

            <div class="header-body">
                <div class="detalle-header-left">
                    <div class="avatar-ring">
                        <img src="{{ $avatar }}" alt="{{ $nombreCompleto }}" class="detalle-avatar">
                    </div>
                    <div class="header-info">
                        <div class="header-role-badge">
                            <i class="ri-user-star-line"></i> Asesor / Vendedor
                        </div>
                        <h4>{{ $nombreCompleto }}</h4>
                        <div class="header-meta">
                            @if (!empty($persona->ci))
                                <span class="header-meta-item"><i class="ri-fingerprint-line"></i> CI:
                                    {{ $persona->ci }}</span>
                            @endif
                            @if (!empty($persona->telefono))
                                <span class="header-meta-item"><i class="ri-phone-line"></i> {{ $persona->telefono }}</span>
                            @endif
                            @if (!empty($persona->email))
                                <span class="header-meta-item"><i class="ri-mail-line"></i> {{ $persona->email }}</span>
                            @endif
                            <span class="header-meta-item">
                                <i class="ri-map-pin-line"></i>
                                {{ $sexo === 'M' ? 'Masculino' : 'Femenino' }}
                            </span>
                        </div>
                        <div class="detalle-breadcrumb mt-1">
                            <a href="{{ route('admin.dashboard') }}"><i class="ri-dashboard-line me-1"></i>Dashboard</a>
                            <span class="sep">/</span>
                            <span class="cur">Inscripciones del Asesor</span>
                        </div>
                    </div>
                </div>

                <div class="header-actions">
                    <a href="{{ route('admin.dashboard') }}" class="back-btn">
                        <i class="ri-arrow-left-line"></i> Volver al Dashboard
                    </a>
                    <div class="header-chips">
                        <div class="hchip c-total">
                            <span class="hchip-num" id="statTotal">{{ $totalInscripciones }}</span>
                            <span>Total</span>
                        </div>
                        <div class="hchip c-insc">
                            <i class="ri-user-follow-line"></i>
                            <span class="hchip-num" id="statInscritos">{{ $totalInscritos }}</span>
                            <span>Inscritos</span>
                        </div>
                        <div class="hchip c-pre">
                            <i class="ri-user-add-line"></i>
                            <span class="hchip-num" id="statPreInscritos">{{ $totalPreInscritos }}</span>
                            <span>Pre-Insc.</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ===== KPI CARDS (contabilidad style) ===== --}}
        <div class="kpi-grid">
            <div class="kpi-card kpi-total">
                <div class="kpi-body">
                    <div class="kpi-header">
                        <div class="kpi-icon"><i class="ri-group-line"></i></div>
                        <span class="kpi-trend total">Base</span>
                    </div>
                    <div class="kpi-value" id="statTotalPanel">{{ $totalInscripciones }}</div>
                    <div class="kpi-label">Total Inscripciones</div>
                </div>
                <div class="kpi-bar"><div class="kpi-bar-fill" style="width:100%"></div></div>
            </div>
            <div class="kpi-card kpi-insc">
                <div class="kpi-body">
                    <div class="kpi-header">
                        <div class="kpi-icon"><i class="ri-user-follow-line"></i></div>
                        <span class="kpi-trend insc" id="pctInscritos">{{ $pctInscritos }}%</span>
                    </div>
                    <div class="kpi-value" id="statInscritosPanel">{{ $totalInscritos }}</div>
                    <div class="kpi-label">Inscritos</div>
                </div>
                <div class="kpi-bar"><div class="kpi-bar-fill" id="barInscritos" style="width:{{ $pctInscritos }}%"></div></div>
            </div>
            <div class="kpi-card kpi-pre">
                <div class="kpi-body">
                    <div class="kpi-header">
                        <div class="kpi-icon"><i class="ri-user-add-line"></i></div>
                        <span class="kpi-trend pre" id="pctPreInscritos">{{ $pctPreInscritos }}%</span>
                    </div>
                    <div class="kpi-value" id="statPreInscritosPanel">{{ $totalPreInscritos }}</div>
                    <div class="kpi-label">Pre-Inscritos</div>
                </div>
                <div class="kpi-bar"><div class="kpi-bar-fill" id="barPreInscritos" style="width:{{ $pctPreInscritos }}%"></div></div>
            </div>
        </div>

        {{-- Fila de tipos de programa --}}
        @if (!empty($datosPorTipo))
            <div class="tipos-row">
                <span class="tipos-row-label"><i class="ri-layout-grid-line"></i> Tipos:</span>
                @foreach ($datosPorTipo as $tipo => $total)
                    @php
                        $color = $tipoColors[$tipoColorIdx % count($tipoColors)];
                        $icon = $tipoIcons[$tipo] ?? 'ri-file-list-line';
                        $pct = $totalInscripciones > 0 ? round(($total / $totalInscripciones) * 100) : 0;
                        $tipoColorIdx++;
                    @endphp
                    <div class="tipo-chip" data-tipo="{{ $tipo }}"
                         style="border-color:{{ $color }}22; background:{{ $color }}08;">
                        <div class="tipo-chip-icon" style="background:{{ $color }}18; color:{{ $color }};">
                            <i class="{{ $icon }}"></i>
                        </div>
                        <div class="tipo-chip-body">
                            <span class="tipo-chip-num" style="color:{{ $color }};">{{ $total }}</span>
                            <span class="tipo-chip-name">{{ $tipo }}</span>
                        </div>
                        <span class="tipo-chip-pct">{{ $pct }}%</span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ===== FILTROS ===== --}}
        @php
            $ofertasParaFiltro = $inscripciones
                ->filter(fn($i) => $i->ofertaAcademica && $i->ofertaAcademica->posgrado)
                ->unique('ofertas_academica_id')
                ->map(
                    fn($i) => [
                        'id' => $i->ofertaAcademica->id,
                        'nombre' => $i->ofertaAcademica->posgrado->nombre,
                        'sede' => $i->ofertaAcademica->sucursal->nombre ?? '',
                    ],
                )
                ->sortBy('nombre')
                ->values();
        @endphp
        <div class="filter-bar">
            <div class="filter-bar-title">
                <i class="ri-equalizer-line"></i> Filtros de búsqueda
            </div>
            <div class="filter-bar-row">
                <div class="filter-group">
                    <label for="filtroGestion"><i class="ri-calendar-2-line me-1"></i>Gestión</label>
                    <select id="filtroGestion" class="filter-select">
                        <option value="">Todas las gestiones</option>
                        @for ($g = date('Y'); $g >= 2020; $g--)
                            <option value="{{ $g }}" {{ $g == date('Y') ? 'selected' : '' }}>
                                {{ $g }}</option>
                        @endfor
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filtroMes"><i class="ri-calendar-line me-1"></i>Mes</label>
                    <select id="filtroMes" class="filter-select">
                        <option value="todos">Todos los meses</option>
                        @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $i => $mes)
                            <option value="{{ $i + 1 }}" {{ $i + 1 == date('n') ? 'selected' : '' }}>
                                {{ $mes }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group" style="min-width:200px;">
                    <label for="filtroOferta"><i class="ri-graduation-cap-line me-1"></i>Oferta Académica</label>
                    <select id="filtroOferta" class="filter-select">
                        <option value="">Todas las ofertas</option>
                        @foreach ($ofertasParaFiltro as $of)
                            <option value="{{ $of['id'] }}">
                                {{ Str::limit($of['nombre'], 40) }}{{ $of['sede'] ? ' · ' . $of['sede'] : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group" style="min-width:160px;">
                    <label for="filtroEstado"><i class="ri-user-follow-line me-1"></i>Estado</label>
                    <select id="filtroEstado" class="filter-select">
                        <option value="">Todos los estados</option>
                        <option value="Inscrito">✅ Inscrito</option>
                        <option value="Pre-Inscrito">⏳ Pre-Inscrito</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button id="btnFiltrar" class="btn-filtrar">
                        <i class="ri-filter-3-line"></i> Filtrar
                    </button>
                    <button id="btnLimpiar" class="btn-limpiar">
                        <i class="ri-refresh-line"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>

        {{-- ===== GRÁFICOS ===== --}}
        <p class="section-label"><i class="ri-line-chart-line"></i> Análisis Visual</p>
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="chart-card" style="position:relative;">
                    <div class="loading-overlay" id="loadingChart">
                        <div class="spinner"></div>
                    </div>
                    <div class="chart-card-header">
                        <h6><i class="ri-bar-chart-2-line"></i> Inscripciones por Mes</h6>
                        <span class="period-badge"><i class="ri-calendar-check-line"></i> Histórico</span>
                    </div>
                    <div style="padding: 20px 20px 16px; height: 290px;">
                        <canvas id="graficoMeses"></canvas>
                    </div>
                    <div
                        style="padding:10px 22px 14px;border-top:1px solid var(--dash-border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                        <span
                            style="font-size:0.72rem;font-weight:700;color:var(--dash-text-muted);display:flex;align-items:center;gap:5px;">
                            <i class="ri-bar-chart-2-line" style="color:var(--dash-primary);"></i> Total período
                        </span>
                        <span
                            style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:800;color:var(--dash-text);"
                            id="totalPeriodo">{{ $inscripciones->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-card" style="position:relative;">
                    <div class="chart-card-header">
                        <h6><i class="ri-donut-chart-line"></i> Por Estado</h6>
                    </div>
                    <div style="padding: 16px; height: 290px; display:flex; align-items:center; justify-content:center;">
                        <canvas id="graficoEstado"></canvas>
                    </div>
                    <div
                        style="padding:10px 22px 14px;border-top:1px solid var(--dash-border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                        <span
                            style="font-size:0.72rem;font-weight:700;color:var(--dash-text-muted);display:flex;align-items:center;gap:5px;">
                            <i class="ri-donut-chart-line" style="color:var(--dash-primary);"></i> Total registros
                        </span>
                        <span
                            style="font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:800;color:var(--dash-text);"
                            id="totalEstado">{{ $inscripciones->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== TABLA INSCRIPCIONES ===== --}}
        <div class="table-card">
            <div class="section-title-bar">
                <h5><i class="ri-list-check-2"></i> Listado de Inscripciones</h5>
                <div class="table-header-right">
                    <div class="search-input-wrap">
                        <i class="ri-search-line"></i>
                        <input type="text" id="searchInput" class="search-input" placeholder="Buscar...">
                    </div>
                    <span id="totalLabel" class="period-badge">
                        <i class="ri-file-list-3-line"></i>
                        <span id="totalCount">{{ $inscripciones->count() }}</span> registros
                    </span>
                </div>
            </div>
            <div class="loading-overlay" id="loadingTable">
                <div class="spinner"></div>
            </div>
            <div id="tablaContainer">
                @include('admin.dashboard.partials.detalle-tabla', ['inscripciones' => $inscripciones])
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const PERSONA_ID = {{ $persona->id }};
        let graficoMeses = null;
        let graficoEstado = null;

        /* ----- Bar chart ----- */
        function initBarChart(data) {
            const ctx = document.getElementById('graficoMeses').getContext('2d');
            if (graficoMeses) graficoMeses.destroy();

            const labels = Object.values(data).map(d => d.label);
            const inscritos = Object.values(data).map(d => d.inscritos);
            const preInscritos = Object.values(data).map(d => d.pre_inscritos);

            graficoMeses = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                            label: 'Inscritos',
                            data: inscritos,
                            backgroundColor: '#9a4904',
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                        {
                            label: 'Pre-Inscritos',
                            data: preInscritos,
                            backgroundColor: '#fc7b04',
                            borderRadius: 6,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                padding: 14,
                                font: {
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
                                label: function(ctx) {
                                    return ' ' + ctx.dataset.label + ': ' + ctx.parsed.y;
                                },
                                afterBody: function(items) {
                                    let total = 0;
                                    items.forEach(i => total += i.parsed.y);
                                    return ['', '─────────────────', ' Total: ' + total];
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: 'rgba(226,232,240,0.6)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        /* ----- Donut chart ----- */
        function initDonutChart(inscritos, preInscritos) {
            const ctx = document.getElementById('graficoEstado').getContext('2d');
            if (graficoEstado) graficoEstado.destroy();

            graficoEstado = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Inscritos', 'Pre-Inscritos'],
                    datasets: [{
                        data: [inscritos, preInscritos],
                        backgroundColor: ['#9a4904', '#fc7b04'],
                        borderColor: ['#fff', '#fff'],
                        borderWidth: 3,
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '66%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 14,
                                font: {
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
                                label: function(ctx) {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? Math.round((ctx.raw / total) * 100) : 0;
                                    return ' ' + ctx.label + ': ' + ctx.raw + ' (' + pct + '%)';
                                },
                                footer: function(items) {
                                    let total = 0;
                                    items.forEach(i => total += i.raw);
                                    return 'Total: ' + total;
                                }
                            }
                        }
                    }
                }
            });
        }

        /* ----- Update stat cards ----- */
        function updateStats(total, inscritos, preInscritos) {
            document.getElementById('statTotal').textContent = total;
            document.getElementById('statInscritos').textContent = inscritos;
            document.getElementById('statPreInscritos').textContent = preInscritos;
            document.getElementById('statTotalPanel').textContent = total;
            document.getElementById('statInscritosPanel').textContent = inscritos;
            document.getElementById('statPreInscritosPanel').textContent = preInscritos;
            document.getElementById('totalCount').textContent = total;
            document.getElementById('totalPeriodo').textContent = total;
            document.getElementById('totalEstado').textContent = total;

            const pctI = total > 0 ? Math.round((inscritos / total) * 100) : 0;
            const pctP = total > 0 ? Math.round((preInscritos / total) * 100) : 0;

            document.getElementById('barInscritos').style.width = pctI + '%';
            document.getElementById('barPreInscritos').style.width = pctP + '%';
            document.getElementById('pctInscritos').textContent = pctI + '%';
            document.getElementById('pctPreInscritos').textContent = pctP + '%';
        }

        /* ----- Update tipo chips ----- */
        function updateTipoCards(graficoPorTipo, total) {
            document.querySelectorAll('[data-tipo]').forEach(chip => {
                const tipo = chip.dataset.tipo;
                const num = graficoPorTipo[tipo] ?? 0;
                const numEl = chip.querySelector('.tipo-chip-num');
                const pctEl = chip.querySelector('.tipo-chip-pct');
                if (numEl) numEl.textContent = num;
                const pct = total > 0 ? Math.round((num / total) * 100) : 0;
                if (pctEl) pctEl.textContent = pct + '%';
            });
        }

        /* ----- Client-side search ----- */
        function applySearch() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            document.querySelectorAll('#tablaContainer .ins-row').forEach(row => {
                row.style.display = (!q || row.textContent.toLowerCase().includes(q)) ? '' : 'none';
            });
        }

        /* ----- Loading state ----- */
        function setLoading(on) {
            const chart = document.getElementById('loadingChart');
            const table = document.getElementById('loadingTable');
            chart && chart.classList.toggle('active', on);
            table && table.classList.toggle('active', on);
        }

        /* ----- Fetch & update ----- */
        function cargarDatos() {
            const gestion = document.getElementById('filtroGestion').value;
            const mes = document.getElementById('filtroMes').value;
            const ofertaId = document.getElementById('filtroOferta').value;
            const estado = document.getElementById('filtroEstado').value;

            setLoading(true);

            const params = new URLSearchParams({
                gestion,
                mes
            });
            if (ofertaId) params.set('oferta_id', ofertaId);
            if (estado) params.set('estado', estado);

            fetch(`{{ route('admin.vendedor.data', ['personaId' => $persona->id]) }}?${params}`)
                .then(r => r.json())
                .then(data => {
                    initBarChart(data.graficoMeses);
                    initDonutChart(data.inscritos, data.pre_inscritos);
                    document.getElementById('tablaContainer').innerHTML = data.tablaHtml;
                    updateStats(data.total, data.inscritos, data.pre_inscritos);
                    if (data.graficoPorTipo) updateTipoCards(data.graficoPorTipo, data.total);
                    applySearch();
                })
                .catch(console.error)
                .finally(() => setLoading(false));
        }

        /* ----- Events ----- */
        document.getElementById('btnFiltrar').addEventListener('click', cargarDatos);

        document.getElementById('btnLimpiar').addEventListener('click', function() {
            document.getElementById('filtroGestion').value = '{{ date('Y') }}';
            document.getElementById('filtroMes').value = '{{ date('n') }}';
            document.getElementById('filtroOferta').value = '';
            document.getElementById('filtroEstado').value = '';
            document.getElementById('searchInput').value = '';
            cargarDatos();
        });

        document.getElementById('searchInput').addEventListener('input', applySearch);

        /* ----- Initial load with current month/year selected ----- */
        cargarDatos();
    </script>
@endsection
