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
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.05);
        }

        .detalle-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--dash-text);
            animation: fadeInUp 0.45s ease-out;
            padding-bottom: 80px;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
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
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 28px;
            padding: 28px 32px;
            background: linear-gradient(135deg, #8a3e03 0%, #b55204 55%, #e86e04 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 28px rgba(154, 73, 4, 0.32);
        }

        .header-orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }
        .header-orb-1 { width: 260px; height: 260px; top: -70px; right: -40px; }
        .header-orb-2 { width: 180px; height: 180px; bottom: -60px; left: 28%; }
        .header-orb-3 { width: 100px; height: 100px; top: -10px; left: 12%; opacity: 0.6; }

        .detalle-header-left {
            position: relative; z-index: 1;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .detalle-avatar {
            width: 74px; height: 74px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.5);
            box-shadow: 0 4px 16px rgba(0,0,0,0.25);
            flex-shrink: 0;
        }

        .header-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .header-info h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 5px;
            letter-spacing: -0.02em;
        }

        .detalle-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            opacity: 0.82;
        }
        .detalle-breadcrumb a { color: rgba(255,255,255,0.85); text-decoration: none; transition: color 0.2s; }
        .detalle-breadcrumb a:hover { color: #fff; }
        .detalle-breadcrumb .sep { opacity: 0.45; }
        .detalle-breadcrumb .cur { color: #fff; font-weight: 500; }

        .header-actions {
            position: relative; z-index: 1;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255,255,255,0.15);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 10px 22px;
            border-radius: 24px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.22s;
            white-space: nowrap;
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.28);
            color: white;
            transform: translateX(-3px);
            border-color: rgba(255,255,255,0.5);
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid var(--dash-border);
            border-top: 4px solid transparent;
            box-shadow: var(--shadow-sm);
            padding: 22px 24px;
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.25s ease, transform 0.25s ease;
        }
        .stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
        }
        .stat-card.total     { border-top-color: var(--dash-primary); }
        .stat-card.inscritos { border-top-color: #16a34a; }
        .stat-card.preinsc   { border-top-color: #d97706; }

        .stat-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 50px; height: 50px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-card.total   .stat-icon { background: rgba(154,73,4,0.1);  color: var(--dash-primary); }
        .stat-card.inscritos .stat-icon { background: rgba(22,163,74,0.1);  color: #16a34a; }
        .stat-card.preinsc   .stat-icon { background: rgba(217,119,6,0.1);  color: #d97706; }

        .stat-value {
            font-family: 'Outfit', sans-serif;
            font-size: 2.3rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 3px;
        }
        .stat-card.total   .stat-value { color: var(--dash-primary); }
        .stat-card.inscritos .stat-value { color: #16a34a; }
        .stat-card.preinsc   .stat-value { color: #d97706; }

        .stat-label {
            font-size: 0.77rem;
            font-weight: 700;
            color: var(--dash-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-progress {
            height: 5px;
            background: rgba(0,0,0,0.07);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 5px;
        }
        .stat-progress-bar {
            height: 100%;
            border-radius: 10px;
            transition: width 0.6s ease;
        }
        .stat-card.total   .stat-progress-bar { background: linear-gradient(90deg, var(--dash-primary), var(--dash-accent)); width: 100%; }
        .stat-card.inscritos .stat-progress-bar { background: linear-gradient(90deg, #16a34a, #4ade80); }
        .stat-card.preinsc   .stat-progress-bar { background: linear-gradient(90deg, #d97706, #fbbf24); }

        .stat-pct {
            font-size: 0.73rem;
            color: var(--dash-text-muted);
        }

        /* ===== TIPO CARDS ===== */
        .tipo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));
            gap: 14px;
            margin-bottom: 28px;
        }

        .tipo-card {
            background: white;
            border-radius: var(--radius-md);
            border: 1px solid var(--dash-border);
            border-top: 4px solid transparent;
            box-shadow: var(--shadow-sm);
            padding: 18px 16px;
            text-align: center;
            transition: box-shadow 0.22s, transform 0.22s;
        }
        .tipo-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .tipo-card-icon {
            width: 42px; height: 42px;
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            margin: 0 auto 10px;
        }

        .tipo-card .tipo-num {
            font-family: 'Outfit', sans-serif;
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
        }

        .tipo-name {
            font-size: 0.76rem;
            font-weight: 700;
            color: var(--dash-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .tipo-pct {
            font-size: 0.7rem;
            color: #94a3b8;
            margin-top: 3px;
        }

        /* ===== FILTER BAR ===== */
        .filter-bar {
            background: white;
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            margin-bottom: 28px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--dash-border);
        }

        .filter-bar-title {
            font-size: 0.72rem;
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
            gap: 14px;
            flex-wrap: wrap;
        }

        .filter-group { flex: 1; min-width: 160px; }

        .filter-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--dash-text-muted);
            margin-bottom: 6px;
        }

        .filter-select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--dash-border);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
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
            gap: 10px;
        }

        .btn-filtrar {
            padding: 10px 22px;
            background: linear-gradient(135deg, #9a4904 0%, #df6a04 55%, #fc7b04 100%);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all 0.25s;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-filtrar:hover {
            background: linear-gradient(135deg, #7a3b03, #9a4904);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        .btn-filtrar:active { transform: translateY(0); }

        .btn-limpiar {
            padding: 10px 18px;
            background: white;
            color: var(--dash-text-muted);
            border: 1px solid var(--dash-border);
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.88rem;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
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
        .chart-card:hover { box-shadow: var(--shadow-md); }

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
        .chart-card-header h6 i { color: var(--dash-primary); }

        .period-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            border-radius: 20px;
            background: rgba(154,73,4,0.1);
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
        .section-title-bar h5 i { color: var(--dash-primary); }

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
            left: 10px; top: 50%;
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
            box-shadow: 0 0 0 3px rgba(154,73,4,0.1);
            width: 230px;
            background: white;
        }

        /* ===== DETAIL TABLE ===== */
        .dash-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .dash-table thead th {
            background: linear-gradient(180deg, var(--dash-surface), #f1f5f9);
            padding: 12px 16px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--dash-text-muted);
            border-bottom: 2px solid var(--dash-border);
            white-space: nowrap;
        }

        .dash-table tbody tr {
            transition: background 0.12s, transform 0.12s;
        }
        .dash-table tbody tr:nth-child(even) {
            background: rgba(248,250,252,0.65);
        }
        .dash-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(154,73,4,0.04), rgba(252,123,4,0.05));
            transform: translateX(2px);
        }

        .dash-table tbody td {
            padding: 11px 16px;
            border-bottom: 1px solid rgba(226,232,240,0.5);
            vertical-align: middle;
            font-size: 0.875rem;
            color: var(--dash-text);
        }
        .dash-table tbody tr:last-child td { border-bottom: none; }

        .row-num {
            font-size: 0.73rem;
            color: #94a3b8;
            font-weight: 600;
            text-align: center;
            padding-left: 12px !important;
            padding-right: 8px !important;
            width: 36px;
        }

        .student-cell {
            display: flex;
            align-items: center;
            gap: 11px;
        }
        .student-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.88rem;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--dash-primary), var(--dash-accent));
        }
        .student-name-link {
            font-weight: 600;
            color: var(--dash-text);
            text-decoration: none;
            transition: color 0.18s;
            font-size: 0.875rem;
        }
        .student-name-link:hover { color: var(--dash-primary); text-decoration: underline; }

        .program-cell { max-width: 220px; }
        .program-name {
            font-size: 0.84rem;
            font-weight: 500;
            color: var(--dash-text);
            display: block;
            white-space: normal;
            line-height: 1.3;
        }
        .sede-name {
            font-size: 0.74rem;
            color: var(--dash-text-muted);
            display: flex;
            align-items: center;
            gap: 3px;
            margin-top: 3px;
        }
        .sede-name i { color: var(--dash-primary); }

        .estado-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.74rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .estado-inscrito  { background: rgba(22,163,74,0.1);  color: #15803d; }
        .estado-preinscrito { background: rgba(217,119,6,0.1); color: #b45309; }

        .tipo-pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.71rem;
            font-weight: 700;
            background: rgba(154,73,4,0.1);
            color: var(--dash-primary);
            white-space: nowrap;
        }

        .fecha-text {
            font-variant-numeric: tabular-nums;
            color: var(--dash-text-muted);
            font-size: 0.81rem;
            white-space: nowrap;
        }

        /* ===== LOADING OVERLAY ===== */
        .loading-overlay {
            display: none;
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.82);
            z-index: 20;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
        }
        .loading-overlay.active { display: flex; }

        .spinner {
            width: 36px; height: 36px;
            border: 3px solid rgba(154,73,4,0.18);
            border-top-color: var(--dash-primary);
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

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
        .empty-state p { color: #94a3b8; margin: 0; font-size: 0.88rem; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 767px) {
            .detalle-header { flex-direction: column; align-items: flex-start; padding: 22px 20px; }
            .header-info h4 { font-size: 1.2rem; }
            .filter-bar-row { flex-direction: column; align-items: stretch; }
            .filter-group { min-width: 100%; }
            .filter-actions { flex-direction: row; }
            .btn-filtrar, .btn-limpiar { flex: 1; justify-content: center; }
            .stat-value { font-size: 1.9rem; }
        }
        @media (max-width: 576px) {
            .tipo-grid { grid-template-columns: repeat(2, 1fr); }
            .dash-table thead th,
            .dash-table tbody td { padding: 9px 10px; }
            .search-input { width: 150px; }
            .search-input:focus { width: 170px; }
        }
    </style>
@endsection

@section('content')
    @php
        $nombreCompleto  = trim($persona->nombres . ' ' . $persona->apellido_paterno . ' ' . ($persona->apellido_materno ?? ''));
        $totalInscritos  = $inscripciones->where('estado', 'Inscrito')->count();
        $totalPreInscritos = $inscripciones->where('estado', 'Pre-Inscrito')->count();
        $totalInscripciones = $inscripciones->count();

        $avatar = ($persona->fotografia && file_exists(public_path($persona->fotografia)))
            ? asset($persona->fotografia)
            : (str_contains(strtolower($persona->sexo ?? ''), 'hombre')
                ? asset('backend/assets/images/hombre.png')
                : asset('backend/assets/images/mujer.png'));

        $pctInscritos    = $totalInscripciones > 0 ? round(($totalInscritos    / $totalInscripciones) * 100) : 0;
        $pctPreInscritos = $totalInscripciones > 0 ? round(($totalPreInscritos / $totalInscripciones) * 100) : 0;

        $tipoColors = ['#9a4904','#3b82f6','#10b981','#8b5cf6','#f59e0b','#ef4444','#14b8a6','#ec4899'];
        $tipoIcons  = [
            'Maestría'     => 'ri-graduation-cap-line',
            'Diplomado'    => 'ri-medal-line',
            'Especialidad' => 'ri-star-line',
            'Doctorado'    => 'ri-award-line',
            'Curso'        => 'ri-book-open-line',
        ];
        $tipoColorIdx = 0;
    @endphp

    <div class="detalle-page">

        {{-- ===== HEADER ===== --}}
        <div class="detalle-header">
            <div class="header-orb header-orb-1"></div>
            <div class="header-orb header-orb-2"></div>
            <div class="header-orb header-orb-3"></div>

            <div class="detalle-header-left">
                <img src="{{ $avatar }}" alt="{{ $nombreCompleto }}" class="detalle-avatar">
                <div class="header-info">
                    <div class="header-role-badge">
                        <i class="ri-user-star-line"></i> Asesor / Vendedor
                    </div>
                    <h4>{{ $nombreCompleto }}</h4>
                    <div class="detalle-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}"><i class="ri-dashboard-line me-1"></i>Dashboard</a>
                        <span class="sep">/</span>
                        <span class="cur">Detalle de Inscripciones</span>
                    </div>
                </div>
            </div>

            <div class="header-actions">
                <a href="{{ route('admin.dashboard') }}" class="back-btn">
                    <i class="ri-arrow-left-line"></i> Volver al Dashboard
                </a>
            </div>
        </div>

        {{-- ===== STATS ===== --}}
        <p class="section-label"><i class="ri-bar-chart-box-line"></i> Resumen General</p>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card total">
                    <div class="stat-card-top">
                        <div>
                            <div class="stat-value" id="statTotal">{{ $totalInscripciones }}</div>
                            <div class="stat-label">Total Inscripciones</div>
                        </div>
                        <div class="stat-icon"><i class="ri-group-line"></i></div>
                    </div>
                    <div class="stat-progress"><div class="stat-progress-bar" style="width:100%"></div></div>
                    <div class="stat-pct">100% del total</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card inscritos">
                    <div class="stat-card-top">
                        <div>
                            <div class="stat-value" id="statInscritos">{{ $totalInscritos }}</div>
                            <div class="stat-label">Inscritos</div>
                        </div>
                        <div class="stat-icon"><i class="ri-user-follow-line"></i></div>
                    </div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" id="barInscritos" style="width:{{ $pctInscritos }}%"></div>
                    </div>
                    <div class="stat-pct" id="pctInscritos">{{ $pctInscritos }}% del total</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card preinsc">
                    <div class="stat-card-top">
                        <div>
                            <div class="stat-value" id="statPreInscritos">{{ $totalPreInscritos }}</div>
                            <div class="stat-label">Pre-Inscritos</div>
                        </div>
                        <div class="stat-icon"><i class="ri-user-add-line"></i></div>
                    </div>
                    <div class="stat-progress">
                        <div class="stat-progress-bar" id="barPreInscritos" style="width:{{ $pctPreInscritos }}%"></div>
                    </div>
                    <div class="stat-pct" id="pctPreInscritos">{{ $pctPreInscritos }}% del total</div>
                </div>
            </div>
        </div>

        {{-- ===== DISTRIBUCIÓN POR TIPO ===== --}}
        @if (!empty($datosPorTipo))
            <p class="section-label"><i class="ri-pie-chart-line"></i> Por Tipo de Programa</p>
            <div class="tipo-grid">
                @foreach ($datosPorTipo as $tipo => $total)
                    @php
                        $color = $tipoColors[$tipoColorIdx % count($tipoColors)];
                        $icon  = $tipoIcons[$tipo] ?? 'ri-file-list-line';
                        $pct   = $totalInscripciones > 0 ? round(($total / $totalInscripciones) * 100) : 0;
                        $tipoColorIdx++;
                    @endphp
                    <div class="tipo-card" data-tipo="{{ $tipo }}" style="border-top-color:{{ $color }};">
                        <div class="tipo-card-icon" style="background:{{ $color }}1a; color:{{ $color }};">
                            <i class="{{ $icon }}"></i>
                        </div>
                        <div class="tipo-num" style="color:{{ $color }};">{{ $total }}</div>
                        <div class="tipo-name">{{ $tipo }}</div>
                        <div class="tipo-pct">{{ $pct }}% del total</div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ===== FILTROS ===== --}}
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
                            <option value="{{ $g }}">{{ $g }}</option>
                        @endfor
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filtroMes"><i class="ri-calendar-line me-1"></i>Mes</label>
                    <select id="filtroMes" class="filter-select">
                        <option value="todos">Todos los meses</option>
                        @foreach (['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $mes)
                            <option value="{{ $i + 1 }}">{{ $mes }}</option>
                        @endforeach
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
                    <div class="loading-overlay" id="loadingChart"><div class="spinner"></div></div>
                    <div class="chart-card-header">
                        <h6><i class="ri-bar-chart-2-line"></i> Inscripciones por Mes</h6>
                        <span class="period-badge"><i class="ri-calendar-check-line"></i> Histórico</span>
                    </div>
                    <div style="padding: 20px 20px 16px; height: 290px;">
                        <canvas id="graficoMeses"></canvas>
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
            <div class="loading-overlay" id="loadingTable"><div class="spinner"></div></div>
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
        let graficoMeses  = null;
        let graficoEstado = null;

        /* ----- Bar chart ----- */
        function initBarChart(data) {
            const ctx = document.getElementById('graficoMeses').getContext('2d');
            if (graficoMeses) graficoMeses.destroy();

            const labels       = Object.values(data).map(d => d.label);
            const inscritos    = Object.values(data).map(d => d.inscritos);
            const preInscritos = Object.values(data).map(d => d.pre_inscritos);

            graficoMeses = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
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
                        legend: { position: 'top', labels: { boxWidth: 12, padding: 14, font: { size: 12 } } }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { size: 11 } },
                            grid: { color: 'rgba(226,232,240,0.6)' }
                        },
                        x: { grid: { display: false }, ticks: { font: { size: 11 } } }
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
                        legend: { position: 'bottom', labels: { boxWidth: 12, padding: 14, font: { size: 12 } } },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct   = total > 0 ? Math.round((ctx.raw / total) * 100) : 0;
                                    return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        /* ----- Update stat cards ----- */
        function updateStats(total, inscritos, preInscritos) {
            document.getElementById('statTotal').textContent       = total;
            document.getElementById('statInscritos').textContent   = inscritos;
            document.getElementById('statPreInscritos').textContent = preInscritos;
            document.getElementById('totalCount').textContent      = total;

            const pctI = total > 0 ? Math.round((inscritos    / total) * 100) : 0;
            const pctP = total > 0 ? Math.round((preInscritos / total) * 100) : 0;

            document.getElementById('barInscritos').style.width    = pctI + '%';
            document.getElementById('barPreInscritos').style.width = pctP + '%';
            document.getElementById('pctInscritos').textContent    = pctI + '% del total';
            document.getElementById('pctPreInscritos').textContent = pctP + '% del total';
        }

        /* ----- Update tipo cards ----- */
        function updateTipoCards(graficoPorTipo, total) {
            document.querySelectorAll('[data-tipo]').forEach(card => {
                const tipo = card.dataset.tipo;
                const num  = graficoPorTipo[tipo] ?? 0;
                card.querySelector('.tipo-num').textContent = num;
                const pct = total > 0 ? Math.round((num / total) * 100) : 0;
                card.querySelector('.tipo-pct').textContent = pct + '% del total';
            });
        }

        /* ----- Client-side search ----- */
        function applySearch() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            document.querySelectorAll('#tablaContainer .dash-table tbody tr').forEach(row => {
                if (row.querySelector('td[colspan]')) return;
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
            const mes     = document.getElementById('filtroMes').value;

            setLoading(true);

            fetch(`{{ route('admin.vendedor.data', ['personaId' => $persona->id]) }}?gestion=${gestion}&mes=${mes}`)
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

        document.getElementById('btnLimpiar').addEventListener('click', function () {
            document.getElementById('filtroGestion').value = '';
            document.getElementById('filtroMes').value     = 'todos';
            document.getElementById('searchInput').value  = '';
            cargarDatos();
        });

        document.getElementById('searchInput').addEventListener('input', applySearch);

        /* ----- Initial charts ----- */
        @php
            $mesesData = [];
            foreach ($inscripciones->groupBy(fn($i) => \Carbon\Carbon::parse($i->fecha_registro)->format('Y-m')) as $key => $grupo) {
                $mesesData[$key] = [
                    'label'         => \Carbon\Carbon::parse($grupo->first()->fecha_registro)->translatedFormat('M Y'),
                    'inscritos'     => $grupo->where('estado', 'Inscrito')->count(),
                    'pre_inscritos' => $grupo->where('estado', 'Pre-Inscrito')->count(),
                ];
            }
            ksort($mesesData);
        @endphp
        initBarChart(@json($mesesData));
        initDonutChart({{ $totalInscritos }}, {{ $totalPreInscritos }});
    </script>
@endsection
