@extends('layouts.master')

@section('title', 'Todas las Ofertas Académicas')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #9a4904;
            --primary-light: rgba(154, 73, 4, 0.1);
            --primary-soft: rgba(154, 73, 4, 0.05);
            --primary-dark: #7a3b03;
            --accent: #fc7b04;
            --gray-50: #f9fafb;
            --gray-200: #e5e7eb;
            --gray-500: #6b7280;
            --gray-700: #374151;
            --gray-900: #111827;
            --radius-lg: 1rem;
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-50);
        }

        .ofertas-page {
            animation: fadeInUp 0.4s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header */
        .ofertas-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: var(--radius-lg);
            padding: 1.75rem 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }

        .ofertas-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            margin: 0;
        }

        .ofertas-header p {
            color: rgba(255, 255, 255, 0.85);
            margin: 0.25rem 0 0;
        }

        .btn-nueva-oferta {
            background: var(--accent);
            padding: 0.6rem 1.2rem;
            border-radius: 2rem;
            font-weight: 600;
            transition: all 0.2s;
            color: white;
        }

        .btn-nueva-oferta:hover {
            background: #e06a00;
            transform: translateY(-2px);
            color: white;
        }

        /* Filtros - sin scroll horizontal */
        .filter-bar-modern {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-200);
        }

        .filters-container {
            display: flex;
            flex-wrap: nowrap;
            gap: 0.5rem;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1 1 0;
            min-width: 0;
            max-width: none;
        }

        .filter-group label {
            font-size: 0.67rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--gray-500);
            margin-bottom: 0.2rem;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 0.45rem 0.5rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.5rem;
            font-size: 0.8rem;
            background: white;
        }

        .filter-actions {
            flex: 0 0 auto;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
            outline: none;
        }

        .btn-aplicar-filtros {
            background: var(--primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            border: none;
            white-space: nowrap;
        }

        .btn-limpiar-filtros {
            border: 1px solid var(--gray-200);
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            white-space: nowrap;
        }

        /* Tabla */
        .table-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .table-card-header {
            padding: 1rem 1.5rem;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-card-header h5 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--gray-700);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-card-header h5 i {
            color: var(--primary);
            font-size: 1.1rem;
        }

        #results-count {
            background: var(--primary-light);
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 700;
            border: 1px solid rgba(154, 73, 4, 0.2);
            padding: 0.3rem 0.8rem;
            border-radius: 1rem;
        }

        /* DataTable: buscador y longitud en la misma fila */
        .dataTables_wrapper .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin: 0;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--gray-200);
            border-radius: 0.5rem;
            padding: 0.4rem 0.75rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length {
            display: none;
        }

        .dataTables_wrapper .dataTables_filter {
            display: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.3rem 0.8rem;
            border-radius: 0.5rem;
            border: 1px solid var(--gray-200);
            margin: 0 0.2rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary);
            color: white !important;
            border-color: var(--primary);
        }

        /* Hover de fila suave (naranja muy claro) */
        .ofertas-table tbody tr {
            transition: background-color 0.15s;
        }

        .ofertas-table tbody tr:hover {
            background-color: var(--primary-soft) !important;
        }

        .ofertas-table thead th {
            background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
            padding: 0.75rem 1rem;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: var(--gray-500);
            border-bottom: 2px solid var(--gray-200);
            white-space: nowrap;
        }

        .ofertas-table thead th:first-child {
            border-left: 3px solid var(--primary);
        }

        .ofertas-table tbody td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-200);
        }

        .ofertas-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Programa cell */
        .prog-nombre {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--gray-900);
            line-height: 1.3;
        }

        .prog-meta {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 3px;
            flex-wrap: wrap;
        }

        .prog-codigo {
            background: rgba(154, 73, 4, 0.08);
            color: var(--primary);
            font-size: 0.67rem;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 20px;
            border: 1px solid rgba(154, 73, 4, 0.15);
        }

        .prog-gestion {
            font-size: 0.72rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        .prog-sede {
            margin-top: 3px;
            font-size: 0.76rem;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .prog-sede i {
            color: var(--primary);
            font-size: 0.8rem;
        }

        /* Inscritos cell */
        .ins-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            border: 1px solid;
        }

        .ins-pill.confirmed {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border-color: rgba(34, 197, 94, 0.2);
        }

        .ins-pill.pending {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
            border-color: rgba(245, 158, 11, 0.2);
        }

        .actions-cell {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .actions-row {
            display: flex;
            gap: 3px;
            align-items: center;
        }

        /* Badge de fase con fondo semitransparente y texto sólido */
        .badge-fase {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: linear-gradient(135deg, rgba(252, 123, 4, 0.15), rgba(154, 73, 4, 0.1));
            border: 1px solid rgba(252, 123, 4, 0.25);
            color: #9a4904;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
        }

        .badge-fase i {
            font-size: 0.85rem;
        }

        .badge-modulos {
            background: #eef2ff;
            color: #4f46e5;
            padding: 0.2rem 0.6rem;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-modalidad {
            background: #e0f2fe;
            color: #0284c7;
            padding: 0.2rem 0.6rem;
            border-radius: 2rem;
            font-size: 0.7rem;
        }

        .convenio-img-small {
            width: 32px;
            height: 32px;
            border-radius: 0.5rem;
            object-fit: cover;
            border: 1px solid var(--gray-200);
        }

        .convenio-placeholder {
            width: 32px;
            height: 32px;
            border-radius: 0.5rem;
            background: var(--gray-50);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--gray-200);
        }

        .action-btn {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .action-btn i {
            font-size: 0.95rem;
        }

        .action-btn.ver-detalle {
            color: #3b82f6;
            background: rgba(59, 130, 246, 0.15);
        }

        .action-btn.ver-detalle:hover {
            background: #2563eb;
            color: white;
            transform: scale(1.1);
        }

        .action-btn.ver-planes {
            color: #f59e0b;
            background: rgba(245, 158, 11, 0.15);
        }

        .action-btn.ver-planes:hover {
            background: #6366f1;
            color: white;
            transform: scale(1.1);
        }

        /* Toast positioning */
        #toastContainer {
            position: fixed;
            top: 80px;
            right: 20px;
            left: auto;
            z-index: 9999;
            max-width: 400px;
        }

        .action-btn.ver-inscripciones {
            color: #10b981;
            background: rgba(16, 185, 129, 0.15);
        }

        .action-btn.ver-inscripciones:hover {
            background: #059669;
            color: white;
            transform: scale(1.1);
        }

        .action-btn.cambiar-fase {
            color: #8b5cf6;
            background: rgba(139, 92, 246, 0.15);
        }

        .action-btn.cambiar-fase:hover {
            background: #7c3aed;
            color: white;
            transform: scale(1.1);
        }

        .action-btn.cambiar-fase-bloqueado {
            color: #d1d5db;
            background: rgba(209, 213, 219, 0.15);
            cursor: not-allowed !important;
        }

        .action-btn.cambiar-fase-bloqueado:hover {
            background: rgba(209, 213, 219, 0.25);
            color: #9ca3af;
            transform: none;
        }

        .fechas-cell {
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .fechas-cell div {
            white-space: nowrap;
        }

        .fecha-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
        }

        .fecha-badge.proxima {
            background: rgba(34, 197, 94, 0.12);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .fecha-badge.en-proceso {
            background: rgba(245, 158, 11, 0.12);
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .fecha-badge.en-curso {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .inscritos-placeholder {
            background: #f3f4f6;
            padding: 0.2rem 0.5rem;
            border-radius: 1rem;
            font-size: 0.7rem;
            color: #6b7280;
            text-align: center;
        }

        /* Modal Planes de Pago */
        .modal-header-gradient {
            background: linear-gradient(135deg, #391b04 0%, #5c2d0a 50%, #c96004 100%);
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }

        .modal-header-gradient .modal-title {
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-header-gradient .modal-title i {
            font-size: 1.25rem;
        }

        .modal-header-gradient .btn-close {
            filter: invert(1);
            opacity: 0.8;
        }

        .modal-header-gradient .btn-close:hover {
            opacity: 1;
        }

        .contable-plan-card {
            background: #fff;
            border: 1px solid var(--gray-200);
            border-radius: 14px;
            overflow: hidden;
        }

        .contable-plan-card.contable-plan-promo {
            border-color: rgba(252, 123, 4, 0.25);
            background: linear-gradient(135deg, #fff 0%, rgba(252, 123, 4, 0.03) 100%);
        }

        .contable-plan-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .contable-plan-nombre {
            font-weight: 800;
            font-size: 1.05rem;
            color: var(--gray-900);
        }

        .contable-plan-total {
            font-size: 1.1rem;
            font-weight: 800;
            color: #fc7b04;
        }

        .contable-promo-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.7rem;
            color: #fc7b04;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            background: rgba(252, 123, 4, 0.12);
        }

        .contable-promo-dates-bar {
            padding: 0.5rem 1.25rem;
            background: rgba(252, 123, 4, 0.06);
            border-bottom: 1px solid rgba(252, 123, 4, 0.1);
            font-size: 0.78rem;
            color: #fc7b04;
            font-weight: 600;
        }

        .contable-conceptos-table {
            width: 100%;
            border-collapse: collapse;
        }

        .contable-conceptos-table thead th {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: var(--gray-500);
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .contable-conceptos-table tbody td {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
            border-bottom: 1px solid var(--gray-100);
        }

        .contable-cuotas-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
            font-weight: 700;
            font-size: 0.75rem;
        }

        /* ── Modal Nueva Inscripción - student card ── */
        .ni-student-card {
            background: linear-gradient(135deg, rgba(154,73,4,.06) 0%, rgba(252,123,4,.03) 100%);
            border: 1px solid rgba(154,73,4,.18);
            border-radius: 14px;
            padding: 1rem 1.25rem;
            margin-bottom: 0.85rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .ni-student-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, #9a4904, #fc7b04);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(154,73,4,.3);
        }
        .ni-student-info { flex: 1; min-width: 0; }
        .ni-student-name {
            font-size: .95rem;
            font-weight: 700;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .ni-student-meta {
            font-size: .75rem;
            color: #9a4904;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .3rem;
            margin-top: .15rem;
        }

        /* ── Concepto cards (detalle del plan) ── */
        .ni-concepto-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: .85rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }
        .ni-concepto-header {
            padding: .7rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ni-concepto-nombre {
            font-size: .85rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: .4rem;
        }
        .ni-concepto-totals { display: flex; align-items: center; gap: .5rem; }
        .ni-badge-total {
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            background: rgba(255,255,255,.18);
            color: #fff;
            font-size: .75rem;
            font-weight: 700;
            padding: .2rem .6rem;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,.28);
        }
        .ni-concepto-body { padding: .75rem 1rem; }
        .ni-controls-bar {
            background: #fff8f0;
            border: 1px solid rgba(154,73,4,.22);
            border-radius: 8px;
            padding: .5rem .8rem;
            margin-bottom: .6rem;
            display: flex;
            align-items: center;
            gap: .65rem;
            flex-wrap: wrap;
        }
        .ni-controls-bar.green { background: #fff7ed; border-color: #fdba74; }
        .ni-ctrl-label {
            font-size: .7rem;
            font-weight: 700;
            color: #9a4904;
            white-space: nowrap;
            margin-bottom: 0;
        }
        .ni-controls-bar.green .ni-ctrl-label { color: #c96004; }
        .ni-ctrl-divider { width: 1px; height: 22px; background: rgba(154,73,4,.25); flex-shrink: 0; }
        .ni-controls-bar.green .ni-ctrl-divider { background: #fdba74; }
        .ni-cuotas-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
        .ni-cuotas-table thead tr { background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
        .ni-cuotas-table thead th {
            padding: .4rem .65rem;
            font-size: .63rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #64748b;
        }
        .ni-cuotas-table tbody tr { border-bottom: 1px solid #f1f5f9; }
        .ni-cuotas-table tbody tr:last-child { border-bottom: none; }
        .ni-cuotas-table tbody tr:hover { background: #fafbfc; }
        .ni-cuotas-table tbody td { padding: .42rem .65rem; color: #374151; vertical-align: middle; }
        .ni-cuota-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            background: rgba(154,73,4,.12);
            color: #9a4904;
            border-radius: 50%;
            font-size: .65rem;
            font-weight: 700;
            margin-right: .3rem;
        }
        .ni-footer-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: .75rem;
            padding-top: .6rem;
            border-top: 1px solid #f1f5f9;
            margin-top: .25rem;
        }

        @media (max-width: 768px) {
            .dataTables_wrapper .row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .filters-container {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                min-width: 100%;
            }
        }

        /* ── Modal headers ── */
        .mhdr {
            border-bottom: none;
            padding: 1.1rem 1.35rem;
        }

        .mhdr .modal-title {
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .mhdr .btn-close {
            filter: invert(1);
            opacity: 0.75;
        }

        .mhdr .btn-close:hover {
            opacity: 1;
        }

        .mhdr-green {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        }

        .mhdr-purple {
            background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
        }

        .mhdr-amber {
            background: linear-gradient(135deg, #b45309 0%, #f59e0b 100%);
        }

        .mhdr-teal {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
        }

        .mhdr-primary {
            background: linear-gradient(135deg, #3b1900 0%, #7a3b03 50%, #c96004 100%);
        }

        /* ── Modal body sections ── */
        .msec {
            background: #f8fafc;
            border: 1px solid #e8edf3;
            border-radius: 12px;
            padding: 0.9rem 1rem;
            margin-bottom: 0.75rem;
        }

        .msec-lbl {
            font-size: 0.67rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* ── Result cards (busqueda) ── */
        .rcrd {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            border-radius: 10px;
            padding: 0.8rem 1rem;
            border: 1px solid;
        }

        .rcrd.ok {
            background: #f0fdf4;
            border-color: #86efac;
        }

        .rcrd.warn {
            background: #fefce8;
            border-color: #fde68a;
        }

        .rcrd.err {
            background: #fef2f2;
            border-color: #fca5a5;
        }

        .rcrd.info {
            background: #fff7ed;
            border-color: #fed7aa;
        }

        .rcrd-ico {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.95rem;
        }

        .rcrd.ok .rcrd-ico {
            background: #dcfce7;
            color: #16a34a;
        }

        .rcrd.warn .rcrd-ico {
            background: #fef9c3;
            color: #ca8a04;
        }

        .rcrd.err .rcrd-ico {
            background: #fee2e2;
            color: #dc2626;
        }

        .rcrd.info .rcrd-ico {
            background: #ffedd5;
            color: #ea580c;
        }

        .rcrd-body {
            flex: 1;
        }

        .rcrd-title {
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 0.1rem;
        }

        .rcrd-sub {
            font-size: 0.78rem;
            color: #64748b;
        }

        /* ── Inscripciones table ── */
        .ins-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.81rem;
        }

        .ins-tbl thead th {
            background: #f8fafc;
            padding: 0.6rem 0.9rem;
            font-size: 0.64rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
            text-align: left;
        }

        .ins-tbl tbody td {
            padding: 0.6rem 0.9rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .ins-tbl tbody tr:last-child td {
            border-bottom: none;
        }

        .ins-tbl tbody tr:hover td {
            background: var(--primary-soft);
        }

        /* ── Estudios builder ── */
        .estudio-row {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-wrap: wrap;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.55rem 0.7rem;
            margin-bottom: 0.45rem;
            animation: estIn 0.2s ease;
        }

        @keyframes estIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .estudio-row.principal-row {
            border-color: rgba(154, 73, 4, 0.3);
            background: rgba(252, 123, 4, 0.025);
        }

        .est-tag-principal {
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.35px;
            background: rgba(252, 123, 4, 0.12);
            color: #9a4904;
            padding: 0.1rem 0.45rem;
            border-radius: 20px;
            border: 1px solid rgba(252, 123, 4, 0.2);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .est-select {
            flex: 1;
            min-width: 110px;
            font-size: 0.8rem !important;
        }

        .btn-del-est {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            border: none;
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
            font-size: 0.82rem;
        }

        .btn-del-est:hover {
            background: #dc2626;
            color: #fff;
        }

        .btn-add-est {
            width: 100%;
            border: 1.5px dashed rgba(154, 73, 4, 0.3);
            border-radius: 8px;
            background: rgba(154, 73, 4, 0.04);
            color: #9a4904;
            padding: 0.45rem;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.35rem;
        }

        .btn-add-est:hover {
            background: rgba(154, 73, 4, 0.09);
            border-color: rgba(154, 73, 4, 0.5);
        }

        /* ==========================================================
           MODAL PLANES DE PAGO — REDISEÑO ELEGANTE
           ========================================================== */
        #modalPlanesPago .modal-content {
            border: none !important;
            border-radius: 18px !important;
            overflow: hidden;
            box-shadow:
                0 30px 80px rgba(15, 10, 5, 0.18),
                0 12px 32px rgba(15, 10, 5, 0.10),
                0 0 0 1px rgba(252, 123, 4, 0.06) !important;
        }
        #modalPlanesPago .modal-header.modal-header-gradient {
            background:
                linear-gradient(135deg, #2a1605 0%, #7a3b03 55%, #fc7b04 100%) !important;
            padding: 1.1rem 1.5rem !important;
            border-bottom: none !important;
            position: relative;
            overflow: hidden;
        }
        #modalPlanesPago .modal-header.modal-header-gradient::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(255,255,255,.18) 0%, transparent 60%),
                radial-gradient(circle at bottom left, rgba(0,0,0,.18) 0%, transparent 65%);
            pointer-events: none;
        }
        #modalPlanesPago .modal-header.modal-header-gradient .modal-title {
            color: #fff !important;
            font-weight: 700;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            position: relative;
            z-index: 1;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        #modalPlanesPago .modal-header.modal-header-gradient .modal-title i {
            font-size: 1.3rem;
            background: rgba(255,255,255,0.18);
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,0.2);
        }
        #modalPlanesPago .modal-header.modal-header-gradient #planesOfertaCodigo {
            font-weight: 600;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.25);
            padding: 0.18rem 0.7rem;
            border-radius: 999px;
            font-size: 0.82rem;
            letter-spacing: 0.02em;
        }
        #modalPlanesPago .modal-header.modal-header-gradient .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.85;
            position: relative;
            z-index: 1;
            transition: opacity 0.2s, transform 0.2s;
        }
        #modalPlanesPago .modal-header.modal-header-gradient .btn-close:hover {
            opacity: 1;
            transform: rotate(90deg);
        }
        #modalPlanesPago .modal-body {
            background: #faf7f4;
            padding: 1.5rem;
        }
        #modalPlanesPago .modal-footer {
            background: linear-gradient(180deg, #fbfaf7, #f7f4ee);
            border-top: 1px solid #ede8e2;
            padding: 0.9rem 1.5rem;
        }
        #modalPlanesPago .modal-footer .btn-secondary {
            background: #ece8e2 !important;
            color: #2d2924 !important;
            border: none !important;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.5rem 1.1rem;
            font-size: 0.85rem;
        }
        #modalPlanesPago .modal-footer .btn-secondary:hover {
            background: #ddd6cc !important;
            transform: translateY(-1px);
        }

        /* Cards de planes — refined within modal */
        #modalPlanesPago .contable-plan-card {
            background: #fff !important;
            border: 1px solid #ede8e2 !important;
            border-radius: 16px !important;
            box-shadow: 0 2px 6px rgba(15,10,5,.04), 0 1px 2px rgba(15,10,5,.03) !important;
            overflow: hidden;
            transition: box-shadow .3s cubic-bezier(.16,1,.3,1), transform .2s, border-color .2s;
            position: relative;
        }
        #modalPlanesPago .contable-plan-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, #fc7b04, rgba(252,123,4,.55));
        }
        #modalPlanesPago .contable-plan-card.contable-plan-promo::before {
            background: linear-gradient(90deg, #f59e0b, #fc7b04, #f59e0b);
            background-size: 200% 100%;
            animation: planesShimmer 3s linear infinite;
        }
        @keyframes planesShimmer {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }
        #modalPlanesPago .contable-plan-card:hover {
            box-shadow: 0 18px 40px rgba(15,10,5,.08), 0 6px 14px rgba(15,10,5,.05) !important;
            transform: translateY(-2px);
            border-color: rgba(252,123,4,.2) !important;
        }
        #modalPlanesPago .contable-plan-card.contable-plan-promo {
            background: linear-gradient(135deg,#fffaf3 0%,#fff 35%,#fff 65%,#fff5e6 100%) !important;
            border-color: rgba(252,123,4,.22) !important;
        }
        #modalPlanesPago .contable-plan-header {
            padding: 1.1rem 1.35rem !important;
            border-bottom: 1px solid #f1ece5 !important;
            background: linear-gradient(180deg,#fcfaf6 0%,#fff 100%) !important;
            margin-top: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.6rem;
        }
        #modalPlanesPago .contable-plan-card.contable-plan-promo .contable-plan-header {
            background: linear-gradient(180deg,rgba(252,123,4,.06) 0%,transparent 100%) !important;
            border-bottom-color: rgba(252,123,4,.15) !important;
        }
        #modalPlanesPago .contable-plan-nombre {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e1b15;
            letter-spacing: -0.01em;
        }
        #modalPlanesPago .contable-promo-badge {
            background: linear-gradient(135deg,#fc7b04,#f59e0b);
            color: #fff;
            border-radius: 999px;
            padding: 0.25rem 0.7rem;
            font-size: 0.66rem;
            letter-spacing: 0.06em;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 3px 8px rgba(252,123,4,.3);
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            margin-left: 0.5rem;
        }
        #modalPlanesPago .contable-plan-total {
            font-size: 1.2rem;
            font-weight: 800;
            color: #9a4904;
            background: linear-gradient(135deg, rgba(154,73,4,.08), rgba(154,73,4,.03));
            padding: 0.4rem 0.85rem;
            border-radius: 10px;
            border: 1px solid rgba(154,73,4,.18);
            letter-spacing: -0.01em;
            line-height: 1;
        }
        #modalPlanesPago .contable-plan-card.contable-plan-promo .contable-plan-total {
            color: #c96004;
            background: linear-gradient(135deg, rgba(252,123,4,.1), rgba(252,123,4,.03));
            border-color: rgba(252,123,4,.22);
        }
        #modalPlanesPago .contable-promo-dates-bar {
            background: linear-gradient(90deg, rgba(252,123,4,.08), rgba(252,123,4,.02)) !important;
            border-bottom: 1px solid rgba(252,123,4,.12) !important;
            padding: 0.55rem 1.35rem;
            font-size: 0.78rem;
            color: #c96004;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        #modalPlanesPago .contable-promo-dates-bar i {
            background: rgba(252,123,4,.18);
            width: 22px;
            height: 22px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fc7b04;
        }
        #modalPlanesPago .contable-conceptos-list {
            padding: 0.25rem 0 0.6rem !important;
        }
        #modalPlanesPago .contable-conceptos-table {
            width: 100%;
        }
        #modalPlanesPago .contable-conceptos-table thead th {
            font-size: 0.66rem !important;
            color: #94908a !important;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-weight: 700;
            padding: 0.7rem 1.35rem !important;
            border-bottom: 1px solid #f1ece5;
            background: transparent;
        }
        #modalPlanesPago .contable-conceptos-table tbody td {
            padding: 0.7rem 1.35rem !important;
            font-size: 0.875rem;
            color: #44423d;
            border-bottom: 1px solid #faf6f0 !important;
        }
        #modalPlanesPago .contable-conceptos-table tbody tr:hover td {
            background: rgba(252,123,4,.025);
        }
        #modalPlanesPago .contable-cuotas-badge {
            background: linear-gradient(135deg, rgba(154,73,4,.16), rgba(154,73,4,.06)) !important;
            color: #9a4904 !important;
            font-weight: 700;
            font-size: 0.78rem;
            padding: 0.25rem 0.7rem;
            border-radius: 999px;
            border: 1px solid rgba(154,73,4,.18);
            display: inline-block;
            min-width: 32px;
            text-align: center;
        }

        /* Loading / empty states */
        #modalPlanesPago #planesPagoLoading,
        #modalPlanesPago #planesPagoEmpty {
            background: #fff;
            border: 1px dashed rgba(252,123,4,.25);
            border-radius: 14px;
            padding: 2rem 1.5rem;
        }
        #modalPlanesPago #planesPagoLoading .spinner-border {
            color: #fc7b04 !important;
        }
        #modalPlanesPago #planesPagoEmpty i {
            color: rgba(252,123,4,.4);
        }

        /* ==========================================================
           VALIDACIONES — feedback de campos en Registrar Persona
           ========================================================== */
        #modalRegistrarPersona .form-control.is-invalid-custom,
        #modalRegistrarPersona .form-select.is-invalid-custom {
            border-color: #ef4444 !important;
            background-color: #fef4f4;
        }
        #modalRegistrarPersona .form-control.is-valid-custom,
        #modalRegistrarPersona .form-select.is-valid-custom {
            border-color: #10b981 !important;
        }
        #modalRegistrarPersona .invalid-feedback-custom {
            display: block;
            color: #ef4444;
            font-size: 0.7rem;
            margin-top: 0.2rem;
        }
        #modalRegistrarPersona .valid-feedback-custom {
            display: block;
            color: #10b981;
            font-size: 0.7rem;
            margin-top: 0.2rem;
        }

        /* Carnet feedback inline en modal inscripciones */
        #carnetSearchFeedback {
            font-size: 0.72rem;
            min-height: 1rem;
            margin-top: 0.25rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid ofertas-page py-3">
        <!-- Header -->
        <div class="ofertas-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1><i class="ri-book-open-line me-2"></i>Todas las Ofertas Académicas</h1>
                <p>Gestión centralizada de todos los posgrados y programas</p>
            </div>
        </div>

        <!-- Filtros sin scroll horizontal -->
        <div class="filter-bar-modern">
            <div class="filters-container">
                <div class="filter-group">
                    <label><i class="ri-handshake-line me-1"></i> Convenio</label>
                    <select id="filterConvenio">
                        <option value="">Todos</option>
                        @foreach ($convenios as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="ri-pie-chart-line me-1"></i> Área</label>
                    <select id="filterArea">
                        <option value="">Todas</option>
                        @foreach ($areas as $a)
                            <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="ri-file-list-line me-1"></i> Tipo</label>
                    <select id="filterTipo">
                        <option value="">Todos</option>
                        @foreach ($tipos as $t)
                            <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="ri-bar-chart-2-line me-1"></i> Fase</label>
                    <select id="filterFase">
                        <option value="">Todas</option>
                        @foreach ($fases as $f)
                            <option value="{{ $f->id }}">{{ $f->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="ri-calendar-line me-1"></i> Gestión</label>
                    <select id="filterGestion">
                        <option value="">Todas</option>
                        @foreach ($gestiones as $g)
                            <option value="{{ $g }}">{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group" style="flex: 1.5 1 0;">
                    <label><i class="ri-search-line me-1"></i> Buscar</label>
                    <input type="text" id="searchOfertas" placeholder="Código, programa...">
                </div>
                <div class="filter-actions d-flex gap-1 align-items-end">
                    <button id="btnAplicarFiltros" class="btn-aplicar-filtros" title="Aplicar filtros">
                        <i class="ri-filter-3-line me-1"></i> Filtrar
                    </button>
                    <button id="btnLimpiarFiltros" class="btn-limpiar-filtros" title="Limpiar filtros">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="table-card">
            <div class="table-card-header">
                <h5><i class="ri-book-open-line"></i>Listado de Ofertas Académicas</h5>
                <span id="results-count">0 ofertas</span>
            </div>
            <div class="table-responsive">
                <table id="tabla-ofertas-global" class="ofertas-table w-100">
                    <thead>
                        <tr>
                            <th>Programa</th>
                            <th>Convenio</th>
                            <th>Modalidad</th>
                            <th>Módulos</th>
                            <th>Fechas</th>
                            <th>Inscritos</th>
                            <th>Fase</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <!-- Modal Planes de Pago -->
        <div class="modal fade" id="modalPlanesPago" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header modal-header-gradient">
                        <h5 class="modal-title">
                            <i class="ri-credit-card-line"></i> Planes de Pago - <span id="planesOfertaCodigo"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div id="planesPagoLoading" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted">Cargando planes de pago...</p>
                        </div>
                        <div id="planesPagoEmpty" class="text-center py-4" style="display: none;">
                            <i class="ri-inbox-line fs-1 text-muted"></i>
                            <p class="mt-2 text-muted">No hay planes de pago configurados</p>
                            <button type="button" class="btn btn-sm btn-primary mt-2" id="btnConfigurarPlanesDesdeModal">
                                <i class="ri-settings-line"></i> Configurar Planes
                            </button>
                        </div>
                        <div id="planesPagoContainer"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                            <i class="ri-close-line"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Confirmar Cambio de Fase -->
        <div class="modal fade" id="modalConfirmarCambioFase" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width:430px;">
                <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.18);">
                    <div class="modal-header" style="background:linear-gradient(135deg,#3b1900 0%,#7a3b03 45%,#c96004 100%);border-bottom:none;padding:1.1rem 1.35rem;">
                        <h5 class="modal-title" style="color:#fff;font-weight:700;font-size:1rem;display:flex;align-items:center;gap:0.45rem;">
                            <i class="ri-exchange-line" style="font-size:1.15rem;"></i>
                            Cambio de Fase
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar" style="opacity:0.78;"></button>
                    </div>
                    <div class="modal-body" style="padding:1.6rem 1.5rem 1.1rem;text-align:center;background:#fff;">
                        <div id="cfIconRing" style="width:66px;height:66px;border-radius:50%;background:rgba(252,123,4,0.1);border:2px solid rgba(252,123,4,0.22);display:flex;align-items:center;justify-content:center;margin:0 auto 1.1rem;transition:all 0.25s;">
                            <i id="cfIcon" class="ri-arrow-left-right-line" style="font-size:1.8rem;color:#fc7b04;transition:all 0.25s;"></i>
                        </div>
                        <p id="cfMsgPrimary" style="font-size:0.9rem;color:#374151;font-weight:500;margin-bottom:0.85rem;">¿Está seguro de cambiar la fase?</p>
                        <div id="cfFaseBox" style="background:linear-gradient(135deg,rgba(252,123,4,0.07),rgba(154,73,4,0.04));border:1px solid rgba(252,123,4,0.2);border-radius:10px;padding:0.65rem 1.1rem;">
                            <p id="faseNuevaNombre" style="margin:0;font-size:1rem;font-weight:700;color:#9a4904;line-height:1.35;"></p>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f0ece8;padding:0.85rem 1.35rem;justify-content:center;gap:0.65rem;background:#faf8f5;">
                        <button type="button" class="btn" data-bs-dismiss="modal"
                            style="background:#f0ece8;color:#374151;border:none;border-radius:10px;font-size:0.875rem;font-weight:500;padding:0.5rem 1.1rem;">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-confirmar-cambio-fase"
                            style="background:linear-gradient(135deg,#fc7b04,#d46604);color:#fff;border:none;border-radius:10px;font-size:0.875rem;font-weight:600;padding:0.5rem 1.35rem;box-shadow:0 3px 12px rgba(252,123,4,0.3);">
                            <i class="ri-check-line me-1"></i>Confirmar Cambio
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Inscripciones -->
        <div class="modal fade" id="modalInscripciones" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
                <div class="modal-content"
                    style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
                    <div class="modal-header mhdr mhdr-primary">
                        <h5 class="modal-title">
                            <i class="ri-user-follow-line"></i>
                            Inscripciones &mdash; <span id="inscripcionesOfertaCodigo"
                                style="font-weight:400;opacity:0.85;"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body" style="padding:1.25rem;background:#fff;">
                        <!-- Buscador -->
                        <div class="msec">
                            <div class="msec-lbl" style="color:#9a4904;"><i class="ri-search-line"></i> Buscar Estudiante
                            </div>
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <input type="text" id="buscarCarnet" class="form-control form-control-sm"
                                    placeholder="Carnet (7 a 11 dígitos)..." maxlength="11" inputmode="numeric"
                                    style="max-width:220px;font-size:0.88rem;border-radius:8px;">
                                <button type="button" id="btnBuscarEstudiante" class="btn btn-sm" disabled
                                    style="background:#9a4904;color:#fff;font-weight:600;border-radius:8px;padding:0.4rem 1rem;font-size:0.82rem;opacity:0.5;cursor:not-allowed;pointer-events:none;">
                                    <i class="ri-search-line me-1"></i>Buscar
                                </button>
                                <button type="button" id="btnNuevaInscripcion" class="btn btn-sm ms-auto" disabled
                                    style="background:linear-gradient(135deg,#7a3b03,#fc7b04);color:#fff;font-weight:600;border-radius:8px;padding:0.4rem 1rem;font-size:0.82rem;">
                                    <i class="ri-add-circle-line me-1"></i>Nueva Inscripción
                                </button>
                            </div>
                            <div id="carnetSearchFeedback"></div>
                            <div id="busquedaResultado" class="mt-3"></div>
                        </div>

                        <!-- Lista de inscritos -->
                        <div class="d-flex align-items-center gap-2 mb-2 mt-3">
                            <i class="ri-list-check" style="color:#9a4904;font-size:1rem;"></i>
                            <span
                                style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#334155;">Lista
                                de Inscritos</span>
                        </div>
                        <div id="inscripcionesLoading" class="text-center py-4">
                            <div class="spinner-border" style="color:#9a4904;width:1.75rem;height:1.75rem;"
                                role="status"></div>
                            <p class="mt-2 text-muted" style="font-size:0.85rem;">Cargando inscripciones...</p>
                        </div>
                        <div id="inscripcionesEmpty" class="text-center py-4" style="display:none;">
                            <div
                                style="width:52px;height:52px;border-radius:50%;background:rgba(154,73,4,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto 0.65rem;">
                                <i class="ri-user-line" style="font-size:1.4rem;color:#9a4904;"></i>
                            </div>
                            <p class="text-muted mb-0" style="font-size:0.88rem;">No hay inscripciones registradas</p>
                        </div>
                        <div id="inscripcionesContainer"
                            style="border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;"></div>
                    </div>
                    <div class="modal-footer" style="padding:0.8rem 1.25rem;border-top:1px solid #e2e8f0;">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Registrar Persona -->
        <div class="modal fade" id="modalRegistrarPersona" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content"
                    style="border:none;border-radius:18px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.2);">
                    <div class="modal-header" style="background:linear-gradient(135deg,#391b04,#7c3c00,#c96004);color:white;padding:1.25rem 1.5rem;border:none;">
                        <div class="d-flex align-items-center gap-3 flex-grow-1">
                            <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="ri-user-add-line" style="font-size:1.35rem;"></i>
                            </div>
                            <div>
                                <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;color:#fff;">Registrar Nueva Persona</h5>
                                <div style="font-size:.73rem;opacity:.8;margin-top:.15rem;">Completa los datos para crear el perfil del estudiante</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body" style="padding:0;background:#fff;">
                        <form id="formRegistrarPersona">
                            <!-- Identidad -->
                            <div style="padding:1.2rem 1.5rem;border-bottom:1px solid #f1f5f9;">
                                <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.85rem;">
                                    <div style="width:26px;height:26px;border-radius:7px;background:rgba(154,73,4,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ri-fingerprint-line" style="color:#9a4904;font-size:.85rem;"></i>
                                    </div>
                                    <span style="font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#9a4904;">Identidad</span>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Carnet <span class="text-danger">*</span></label>
                                        <input type="text" id="inputCarnetPersona" name="carnet"
                                            class="form-control form-control-sm" required maxlength="20" readonly
                                            style="background:#f1f5f9;cursor:not-allowed;font-size:0.85rem;border-radius:9px;">
                                        <input type="hidden" name="expedido" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Nombres <span class="text-danger">*</span></label>
                                        <input type="text" name="nombres" class="form-control form-control-sm"
                                            required maxlength="100" style="font-size:0.85rem;border-radius:9px;">
                                        <div class="invalid-feedback-custom" id="fbNombres" style="display:none;"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Apellido Paterno <span class="text-danger" title="Al menos un apellido es requerido">*</span></label>
                                        <input type="text" name="apellido_paterno" class="form-control form-control-sm"
                                            maxlength="80" style="font-size:0.85rem;border-radius:9px;">
                                        <div class="invalid-feedback-custom" id="fbApellidoPaterno" style="display:none;"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Apellido Materno <span class="text-danger" title="Al menos un apellido es requerido">*</span></label>
                                        <input type="text" name="apellido_materno" class="form-control form-control-sm"
                                            maxlength="80" style="font-size:0.85rem;border-radius:9px;">
                                        <div class="invalid-feedback-custom" id="fbApellidoMaterno" style="display:none;"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Sexo <span class="text-danger">*</span></label>
                                        <select name="sexo" class="form-select form-select-sm" required style="font-size:0.85rem;border-radius:9px;">
                                            <option value="">Seleccionar...</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Femenino</option>
                                        </select>
                                        <div class="invalid-feedback-custom" id="fbSexo" style="display:none;"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Fecha Nacimiento</label>
                                        <input type="date" name="fecha_nacimiento" class="form-control form-control-sm"
                                            style="font-size:0.85rem;border-radius:9px;">
                                    </div>
                                    <div class="col-md-4">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Estado Civil <span class="text-danger">*</span></label>
                                        <select name="estado_civil" class="form-select form-select-sm" required style="font-size:0.85rem;border-radius:9px;">
                                            <option value="">Seleccionar...</option>
                                            <option value="Soltero/a">Soltero/a</option>
                                            <option value="Casado/a">Casado/a</option>
                                            <option value="Divorciado/a">Divorciado/a</option>
                                            <option value="Viudo/a">Viudo/a</option>
                                            <option value="Unión Libre">Unión Libre</option>
                                        </select>
                                        <div class="invalid-feedback-custom" id="fbEstadoCivil" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contacto -->
                            <div style="padding:1.2rem 1.5rem;border-bottom:1px solid #f1f5f9;background:rgba(252,123,4,.02);">
                                <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.85rem;">
                                    <div style="width:26px;height:26px;border-radius:7px;background:rgba(252,123,4,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ri-contacts-line" style="color:#fc7b04;font-size:.85rem;"></i>
                                    </div>
                                    <span style="font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#c96004;">Contacto y Ubicación</span>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Correo <span class="text-danger">*</span></label>
                                        <div style="position:relative;">
                                            <i class="ri-mail-line" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.9rem;pointer-events:none;"></i>
                                            <input type="email" name="correo" class="form-control form-control-sm"
                                                maxlength="150" placeholder="correo@ejemplo.com" style="font-size:0.85rem;border-radius:9px;padding-left:2.1rem;">
                                        </div>
                                        <div class="invalid-feedback-custom" id="fbCorreo" style="display:none;"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Celular <span class="text-danger">*</span></label>
                                        <div style="position:relative;">
                                            <i class="ri-smartphone-line" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.9rem;pointer-events:none;"></i>
                                            <input type="text" name="celular" class="form-control form-control-sm"
                                                maxlength="8" inputmode="numeric" placeholder="70000000" style="font-size:0.85rem;border-radius:9px;padding-left:2.1rem;">
                                        </div>
                                        <div class="invalid-feedback-custom" id="fbCelular" style="display:none;"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Teléfono</label>
                                        <div style="position:relative;">
                                            <i class="ri-phone-line" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.9rem;pointer-events:none;"></i>
                                            <input type="text" name="telefono" class="form-control form-control-sm"
                                                maxlength="20" style="font-size:0.85rem;border-radius:9px;padding-left:2.1rem;">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:flex;align-items:center;gap:.3rem;margin-bottom:.4rem;">
                                            <i class="ri-map-pin-line" style="color:#fc7b04;font-size:.8rem;"></i> Departamento
                                        </label>
                                        <select name="departamento_id" class="form-select form-select-sm"
                                            id="inputDepartamento" style="font-size:0.85rem;border-radius:9px;">
                                            <option value="">Seleccionar...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:flex;align-items:center;gap:.3rem;margin-bottom:.4rem;">
                                            <i class="ri-building-line" style="color:#fc7b04;font-size:.8rem;"></i> Ciudad <span class="text-danger">*</span>
                                        </label>
                                        <select name="ciudade_id" class="form-select form-select-sm" id="inputCiudad" required
                                            disabled style="font-size:0.85rem;border-radius:9px;">
                                            <option value="">Seleccione departamento primero</option>
                                        </select>
                                        <div class="invalid-feedback-custom" id="fbCiudadeId" style="display:none;"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;display:block;margin-bottom:.4rem;">Dirección</label>
                                        <input type="text" name="direccion" class="form-control form-control-sm"
                                            maxlength="200" style="font-size:0.85rem;border-radius:9px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Estudios Académicos -->
                            <div style="padding:1.2rem 1.5rem;background:rgba(99,102,241,.02);">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.85rem;">
                                    <div style="display:flex;align-items:center;gap:.5rem;">
                                        <div style="width:26px;height:26px;border-radius:7px;background:rgba(99,102,241,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i class="ri-graduation-cap-line" style="color:#6366f1;font-size:.85rem;"></i>
                                        </div>
                                        <span style="font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#6366f1;">Estudios Académicos</span>
                                        <span style="font-size:.68rem;color:#94a3b8;">(opcional)</span>
                                    </div>
                                    <button type="button" id="btnAgregarEstudio"
                                        style="padding:.35rem .85rem;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.25);border-radius:8px;color:#6366f1;font-size:.78rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;transition:all .15s;">
                                        <i class="ri-add-circle-line"></i> Agregar Estudio
                                    </button>
                                </div>
                                <div id="estudiosLista"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;gap:.5rem;">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="button" id="btnConfirmarRegistrarPersona" disabled
                            style="padding:.4rem 1.15rem;background:linear-gradient(135deg,#391b04,#c96004);border:none;border-radius:8px;color:#fff;font-size:.82rem;font-weight:700;cursor:not-allowed;display:inline-flex;align-items:center;gap:.4rem;box-shadow:0 4px 12px rgba(252,123,4,.3);transition:all .2s;opacity:.55;">
                            <i class="ri-user-add-line"></i> Registrar Persona
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Registrar Estudiante (persona existe pero no es estudiante) -->
        <div class="modal fade" id="modalRegistrarEstudiante" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width:430px;">
                <div class="modal-content"
                    style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
                    <div class="modal-header mhdr mhdr-amber">
                        <h5 class="modal-title">
                            <i class="ri-user-star-line"></i> Registrar como Estudiante
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body" style="padding:1.5rem;text-align:center;background:#fff;">
                        <div
                            style="width:60px;height:60px;border-radius:50%;background:rgba(245,158,11,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                            <i class="ri-user-star-line" style="font-size:1.6rem;color:#f59e0b;"></i>
                        </div>
                        <div id="personaExistenteInfo" class="mb-3 p-3 rounded"
                            style="background:#fef3c7;border:1px solid #fde68a;text-align:left;">
                            <div
                                style="font-size:0.72rem;font-weight:700;text-transform:uppercase;color:#92400e;letter-spacing:0.4px;margin-bottom:0.3rem;">
                                Persona encontrada</div>
                            <div style="font-size:0.95rem;font-weight:700;color:#1e293b;" id="personaNombreDisplay"></div>
                            <div style="font-size:0.82rem;color:#64748b;margin-top:0.15rem;">CI: <strong
                                    id="personaCiDisplay"></strong></div>
                        </div>
                        <p class="text-muted mb-0" style="font-size:0.88rem;">¿Desea registrar a esta persona como
                            estudiante en el sistema?</p>
                    </div>
                    <div class="modal-footer"
                        style="padding:0.8rem 1.25rem;border-top:1px solid #e2e8f0;justify-content:center;gap:0.65rem;">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn" id="btnConfirmarRegistrarEstudiante"
                            style="background:linear-gradient(135deg,#b45309,#f59e0b);color:#fff;font-weight:600;border-radius:8px;padding:0.5rem 1.25rem;font-size:0.875rem;">
                            <i class="ri-user-star-line me-1"></i>Registrar como Estudiante
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Nueva Inscripción -->
        <div class="modal fade" id="modalNuevaInscripcion" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">

                    {{-- Header --}}
                    <div class="modal-header" style="background:linear-gradient(135deg,#3b1900,#7a3b03,#c96004);padding:1.25rem 1.5rem;border:none;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;backdrop-filter:blur(10px);">
                                <i class="ri-user-add-line" style="font-size:1.35rem;color:#fff;"></i>
                            </div>
                            <div>
                                <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;color:#fff;letter-spacing:-.01em;">Nueva Inscripción</h5>
                                <div style="font-size:.73rem;opacity:.8;margin-top:.15rem;color:rgba(255,255,255,.9);">Registra un estudiante en la oferta académica</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body" style="padding:1.25rem;background:#fff;">

                        {{-- Tarjeta del estudiante --}}
                        <div class="ni-student-card">
                            <div class="ni-student-avatar">
                                <i class="ri-user-line"></i>
                            </div>
                            <div class="ni-student-info">
                                <div class="ni-student-name" id="inscripcionEstudianteNombre">—</div>
                                <div class="ni-student-meta">
                                    <i class="ri-id-card-line"></i>
                                    <span id="inscripcionEstudianteCi">—</span>
                                </div>
                            </div>
                            <span style="flex-shrink:0;display:inline-flex;align-items:center;gap:.3rem;background:rgba(154,73,4,.1);color:#9a4904;font-size:.7rem;font-weight:700;padding:.28rem .7rem;border-radius:20px;border:1px solid rgba(154,73,4,.22);">
                                <i class="ri-graduation-cap-line"></i> Estudiante
                            </span>
                        </div>

                        {{-- Formulario de inscripción --}}
                        <div class="msec">
                            <div class="msec-lbl" style="color:#9a4904;">
                                <i class="ri-file-list-2-line"></i> Datos de Inscripción
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" style="font-size:.73rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem;">
                                        <i class="ri-checkbox-circle-line me-1" style="color:#9a4904;"></i>Estado <span class="text-danger">*</span>
                                    </label>
                                    <select id="inscripcionEstado" class="form-select form-select-sm" style="font-size:.85rem;border-radius:9px;border-color:#e2e8f0;">
                                        <option value="Pre-Inscrito">Pre-Inscrito</option>
                                        <option value="Inscrito">Inscrito</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" style="font-size:.73rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem;">
                                        <i class="ri-bank-card-line me-1" style="color:#9a4904;"></i>Plan de Pago
                                    </label>
                                    <select id="inscripcionPlanPago" class="form-select form-select-sm" style="font-size:.85rem;border-radius:9px;border-color:#e2e8f0;">
                                        <option value="">Seleccionar plan...</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" style="font-size:.73rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem;">
                                        <i class="ri-money-dollar-circle-line me-1" style="color:#9a4904;"></i>Adelanto (Bs)
                                    </label>
                                    <div style="position:relative;">
                                        <span style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);font-size:.72rem;font-weight:700;color:#94a3b8;pointer-events:none;">Bs.</span>
                                        <input type="number" id="inscripcionAdelanto" class="form-control form-control-sm" value="0" min="0" step="0.01" style="font-size:.85rem;border-radius:9px;padding-left:2.1rem;border-color:#e2e8f0;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" style="font-size:.73rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem;">
                                        <i class="ri-chat-3-line me-1" style="color:#9a4904;"></i>Observación
                                    </label>
                                    <textarea id="inscripcionObservacion" class="form-control form-control-sm" rows="1" style="font-size:.85rem;border-radius:9px;resize:none;border-color:#e2e8f0;"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Detalle del Plan --}}
                        <div id="conceptosContainer" class="mt-1" style="display:none;">
                            <div style="display:flex;align-items:center;gap:.55rem;margin-bottom:.8rem;padding:.6rem .9rem;background:linear-gradient(135deg,rgba(154,73,4,.07),rgba(252,123,4,.03));border-radius:10px;border:1px solid rgba(154,73,4,.18);">
                                <div style="width:28px;height:28px;background:rgba(154,73,4,.12);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ri-list-ordered" style="color:#9a4904;font-size:.85rem;"></i>
                                </div>
                                <div>
                                    <div style="font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#9a4904;line-height:1.2;">Detalle del Plan</div>
                                    <div style="font-size:.66rem;color:#64748b;margin-top:.06rem;">Configure los montos y fechas de vencimiento por concepto</div>
                                </div>
                            </div>
                            <div id="conceptosList"></div>
                        </div>

                    </div>

                    <div class="modal-footer" style="padding:.85rem 1.25rem;border-top:1px solid #e2e8f0;background:#f8fafc;gap:.5rem;">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-modal-submit" id="btnConfirmarInscripcion">
                            <i class="ri-check-line me-1"></i>Registrar Inscripción
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Comprobante de Pago (post-inscripción) -->
    <div class="modal fade" id="modalComprobanteInscripcion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:580px;">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">

                {{-- Header --}}
                <div class="modal-header" style="background:linear-gradient(135deg,#3b1900,#7a3b03,#c96004);padding:1.25rem 1.5rem;border:none;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;backdrop-filter:blur(10px);">
                            <i class="ri-upload-cloud-line" style="font-size:1.35rem;color:#fff;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;color:#fff;letter-spacing:-.01em;">Subir Comprobante de Pago</h5>
                            <div style="font-size:.73rem;opacity:.8;margin-top:.15rem;color:rgba(255,255,255,.9);">Adjunta el archivo del comprobante de la inscripción</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="padding:1.25rem;background:#fff;">

                    {{-- Tarjeta del estudiante --}}
                    <div class="ni-student-card">
                        <div class="ni-student-avatar">
                            <i class="ri-user-line"></i>
                        </div>
                        <div class="ni-student-info">
                            <div class="ni-student-name" id="cmpEstudianteNombre">—</div>
                            <div class="ni-student-meta">
                                <i class="ri-file-list-3-line"></i>
                                <span id="cmpEstudianteDetalle">—</span>
                            </div>
                        </div>
                        <span style="flex-shrink:0;display:inline-flex;align-items:center;gap:.3rem;background:rgba(154,73,4,.1);color:#9a4904;font-size:.7rem;font-weight:700;padding:.28rem .7rem;border-radius:20px;border:1px solid rgba(154,73,4,.22);">
                            <i class="ri-receipt-line"></i> Comprobante
                        </span>
                    </div>

                    {{-- Archivo del comprobante --}}
                    <div class="msec" style="margin-bottom:.75rem;">
                        <div class="msec-lbl" style="color:#9a4904;">
                            <i class="ri-attachment-line"></i> Archivo del comprobante <span class="text-danger ms-1">*</span>
                        </div>
                        <input type="file" id="cmpArchivo" accept=".jpg,.jpeg,.png,.pdf"
                            style="border:1px solid #e2e8f0;border-radius:9px;padding:.45rem .75rem;font-size:.85rem;width:100%;background:#f8fafc;cursor:pointer;">
                        <div style="display:flex;align-items:center;gap:.35rem;margin-top:.45rem;flex-wrap:wrap;">
                            <span style="display:inline-flex;align-items:center;gap:.25rem;background:rgba(154,73,4,.08);color:#9a4904;font-size:.68rem;font-weight:700;padding:.15rem .5rem;border-radius:6px;">
                                <i class="ri-image-line"></i> JPG / PNG
                            </span>
                            <span style="display:inline-flex;align-items:center;gap:.25rem;background:rgba(239,68,68,.08);color:#dc2626;font-size:.68rem;font-weight:700;padding:.15rem .5rem;border-radius:6px;">
                                <i class="ri-file-pdf-line"></i> PDF
                            </span>
                            <span style="font-size:.7rem;color:#94a3b8;">— máx. 5 MB</span>
                        </div>
                    </div>

                    {{-- Observaciones --}}
                    <div class="msec" style="margin-bottom:.75rem;">
                        <div class="msec-lbl" style="color:#9a4904;">
                            <i class="ri-chat-3-line"></i> Observaciones
                        </div>
                        <textarea id="cmpObservaciones" rows="2" placeholder="Opcional..."
                            style="border:1px solid #e2e8f0;border-radius:9px;padding:.5rem .75rem;font-size:.85rem;width:100%;background:#f8fafc;resize:none;"></textarea>
                    </div>

                    {{-- Cuotas --}}
                    <div class="msec" style="margin-bottom:0;">
                        <div class="msec-lbl" style="color:#9a4904;">
                            <i class="ri-check-double-line"></i> Cuotas que cubre este comprobante <span class="text-danger ms-1">*</span>
                        </div>
                        <div id="cmpCuotasLoading" class="text-center py-3">
                            <div class="spinner-border spinner-border-sm" style="color:#9a4904;"></div>
                            <span class="ms-2 text-muted" style="font-size:.8rem;">Cargando cuotas...</span>
                        </div>
                        <div id="cmpCuotasContainer" style="display:none;"></div>
                    </div>

                </div>

                <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:.85rem 1.25rem;background:#f8fafc;justify-content:space-between;gap:.5rem;">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                        <i class="ri-skip-forward-line me-1"></i>Omitir por ahora
                    </button>
                    <button type="button" id="btnEnviarComprobanteInscripcion"
                        style="padding:.45rem 1.2rem;border-radius:8px;border:none;background:linear-gradient(135deg,#3b1900,#7a3b03,#c96004);color:#fff;font-weight:600;font-size:.875rem;cursor:pointer;box-shadow:0 3px 12px rgba(154,73,4,.3);display:inline-flex;align-items:center;gap:.4rem;">
                        <i class="ri-upload-cloud-line"></i> Enviar Comprobante
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
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

        $(function() {
            var table = $('#tabla-ofertas-global').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.ofertas.listarGlobal') }}',
                    error: function(xhr, error, thrown) {
                        console.log('DataTables error:', xhr.responseText);
                        alert('Error al cargar datos: ' + (xhr.responseText ? JSON.parse(xhr
                            .responseText).message : thrown));
                    },
                    data: function(d) {
                        d.convenio_id = $('#filterConvenio').val();
                        d.area_id = $('#filterArea').val();
                        d.tipo_id = $('#filterTipo').val();
                        d.fase_id = $('#filterFase').val();
                        d.gestion = $('#filterGestion').val();
                    }
                },
                columns: [{
                        data: 'programa_sede',
                        name: 'programa_sede'
                    },
                    {
                        data: 'convenio_imagen',
                        name: 'convenio_nombre',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'modalidad_nombre',
                        name: 'modalidad_nombre'
                    },
                    {
                        data: 'modulos_count',
                        name: 'modulos_count',
                        orderable: false
                    },
                    {
                        data: 'fechas',
                        name: 'fecha_inicio_programa'
                    },
                    {
                        data: 'inscritos',
                        name: 'inscritos',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'fase_nombre',
                        name: 'fase_nombre'
                    },
                    {
                        data: 'acciones',
                        name: 'acciones',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [4, 'desc']
                ],
                language: {
                    "decimal": "",
                    "emptyTable": "<div class='text-center py-4'><i class='ri-inbox-line fs-1 text-muted'></i><p>No hay ofertas registradas</p></div>",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ ofertas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 ofertas",
                    "infoFiltered": "(filtrado de _MAX_ ofertas totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ ofertas",
                    "loadingRecords": "Cargando...",
                    "processing": "<div class='text-center py-4'><i class='ri-loader-4-line spin fs-1 text-muted'></i><p>Cargando...</p></div>",
                    "search": "Buscar:",
                    "zeroRecords": "<div class='text-center py-4'><i class='ri-search-line fs-1 text-muted'></i><p>No se encontraron resultados</p></div>",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar columna ascendente",
                        "sortDescending": ": activar para ordenar columna descendente"
                    }
                },
                dom: 'ftrip',
                drawCallback: function(settings) {
                    let info = this.api().page.info();
                    $('#results-count').text(info.recordsTotal + ' oferta' + (info.recordsTotal !== 1 ?
                        's' : ''));
                }
            });

            let searchTimeout;
            $('#searchOfertas').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    table.search($(this).val()).draw();
                }, 400);
            });

            $('#btnAplicarFiltros').click(() => table.draw());
            $('#btnLimpiarFiltros').click(() => {
                $('#filterConvenio, #filterArea, #filterTipo, #filterFase, #filterGestion').val('');
                $('#searchOfertas').val('');
                table.draw();
            });
            $('#filterConvenio, #filterArea, #filterTipo, #filterFase, #filterGestion').on('change', () => table
                .draw());

            // Modal de Planes de Pago
            let planesPagoData = [];

            $(document).on('click', '.ver-planes', function(e) {
                e.preventDefault();
                const ofertaId = $(this).data('id');
                const codigo = $(this).data('codigo');
                console.log('Click ver-planes:', ofertaId, codigo);

                $('#planesOfertaCodigo').text(codigo);
                $('#planesPagoLoading').show();
                $('#planesPagoEmpty').hide();
                $('#planesPagoContainer').empty();

                $.ajax({
                    url: '/admin/ofertas-academicas/' + ofertaId + '/configuraciones-precio',
                    type: 'GET',
                    success: function(response) {
                        console.log('Response planes:', response);
                        $('#planesPagoLoading').hide();
                        planesPagoData = response.data || [];

                        if (!planesPagoData.length) {
                            $('#planesPagoEmpty').show();
                            $('#modalPlanesPago').modal('show');
                            return;
                        }

                        renderPlanesPagoCards();
                        $('#modalPlanesPago').modal('show');
                    },
                    error: function(xhr) {
                        console.log('Error planes:', xhr);
                        $('#planesPagoLoading').hide();
                        $('#planesPagoEmpty').show();
                        $('#modalPlanesPago').modal('show');
                    }
                });
            });

            function renderPlanesPagoCards() {
                const $container = $('#planesPagoContainer');

                const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto',
                    'septiembre', 'octubre', 'noviembre', 'diciembre'
                ];

                function formatDateLong(dateStr) {
                    if (!dateStr) return '';
                    const parts = dateStr.split('-');
                    if (parts.length !== 3) return dateStr;
                    const dia = parseInt(parts[2]);
                    const mes = meses[parseInt(parts[1]) - 1];
                    const anio = parts[0];
                    return dia + ' de ' + mes + ' del ' + anio;
                }

                const planes = {};
                planesPagoData.forEach(function(item) {
                    const planNombre = item.plan_pago?.nombre || 'Sin plan';
                    const planId = item.plan_pago?.id || 0;
                    const esPromocion = item.plan_pago?.es_promocion || false;
                    const fechaInicio = item.plan_pago?.fecha_inicio_promocion || null;
                    const fechaFin = item.plan_pago?.fecha_fin_promocion || null;

                    if (!planes[planId]) {
                        planes[planId] = {
                            nombre: planNombre,
                            es_promocion: esPromocion,
                            fecha_inicio: fechaInicio,
                            fecha_fin: fechaFin,
                            conceptos: []
                        };
                    }
                    planes[planId].conceptos.push(item);
                });

                let html = '';
                Object.keys(planes).forEach(function(planId) {
                    const plan = planes[planId];
                    let totalPlan = 0;
                    plan.conceptos.forEach(function(c) {
                        totalPlan += parseFloat(c.pago_bs || 0);
                    });

                    const cardClass = plan.es_promocion ? 'contable-plan-card contable-plan-promo mb-3' :
                        'contable-plan-card mb-3';
                    const promoBadge = plan.es_promocion ?
                        '<span class="contable-promo-badge"><i class="ri-gift-line"></i> Promoción</span>' :
                        '';

                    html += '<div class="' + cardClass +
                        '" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">';
                    html +=
                        '<div class="contable-plan-header" style="background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);">';
                    html += '<div class="contable-plan-header-left">';
                    html += '<span class="contable-plan-nombre">' + plan.nombre + '</span>';
                    html += promoBadge;
                    html += '</div>';
                    html += '<div class="contable-plan-header-right">';
                    html += '<span class="contable-plan-total">Bs. ' + totalPlan.toFixed(2) + '</span>';
                    html += '</div></div>';

                    if (plan.es_promocion && plan.fecha_inicio && plan.fecha_fin) {
                        html += '<div class="contable-promo-dates-bar">';
                        html += '<i class="ri-calendar-event-line"></i> ' + formatDateLong(plan
                            .fecha_inicio) + ' al ' + formatDateLong(plan.fecha_fin);
                        html += '</div>';
                    }

                    html += '<div class="contable-conceptos-list" style="padding: 0.75rem 1rem;">';
                    html += '<table class="contable-conceptos-table"><thead><tr>';
                    html +=
                        '<th style="font-size: 0.65rem; text-transform: uppercase; color: #6b7280;">Concepto</th>';
                    html +=
                        '<th style="font-size: 0.65rem; text-transform: uppercase; color: #6b7280;">Cuotas</th>';
                    html +=
                        '<th style="font-size: 0.65rem; text-transform: uppercase; color: #6b7280;" class="text-end">P. Regular</th>';
                    if (plan.es_promocion) html +=
                        '<th style="font-size: 0.65rem; text-transform: uppercase; color: #6b7280;" class="text-end">Descuento</th>';
                    html +=
                        '<th style="font-size: 0.65rem; text-transform: uppercase; color: #6b7280;" class="text-end">Pago</th></tr></thead><tbody>';

                    plan.conceptos.forEach(function(c) {
                        html += '<tr style="border-bottom: 1px solid #f3f4f6;">';
                        html +=
                            '<td style="font-size: 0.85rem; color: #374151; font-weight: 500;">' + (
                                c.concepto?.nombre || '—') + '</td>';
                        html += '<td><span class="contable-cuotas-badge">' + c.cuotas +
                            '</span></td>';
                        html +=
                            '<td class="text-end" style="font-size: 0.85rem; color: #6b7280;">Bs. ' +
                            parseFloat(c.precio_regular_bs || 0).toFixed(2) + '</td>';
                        if (plan.es_promocion) {
                            html +=
                                '<td class="text-end" style="font-size: 0.85rem; color: #dc2626;">-Bs. ' +
                                parseFloat(c.descuento_bs || 0).toFixed(2) + '</td>';
                        }
                        html +=
                            '<td class="text-end"><strong style="color: #9a4904; font-size: 0.9rem;">Bs. ' +
                            parseFloat(c.pago_bs || 0).toFixed(2) + '</strong></td>';
                        html += '</tr>';
                    });

                    html += '</tbody></table></div></div>';
                });

                $container.html(html);
            }

            // Render Inscripciones Table
            function renderInscripcionesTable(inscripciones) {
                let html = '<div class="table-responsive"><table class="ins-tbl"><thead><tr>';
                html += '<th>Estudiante</th><th>CI</th><th>Plan de Pago</th>';
                html += '<th>Estado</th><th style="text-align:right;">Adelanto</th>';
                html += '<th>Fecha</th><th>Registrado por</th><th>Obs.</th>';
                html += '</tr></thead><tbody>';

                inscripciones.forEach(function(ins) {
                    let estadoStyle = 'background:rgba(108,117,125,0.12);color:#6c757d;';
                    if (ins.estado === 'Inscrito') estadoStyle =
                        'background:rgba(25,135,84,0.12);color:#198754;';
                    if (ins.estado === 'Pre-Inscrito') estadoStyle =
                        'background:rgba(255,193,7,0.15);color:#997404;';

                    html += '<tr>';
                    html += '<td><strong style="font-size:0.83rem;">' + (ins.estudiante_nombre || '—') +
                        '</strong></td>';
                    html += '<td style="font-size:0.8rem;color:#64748b;">' + (ins.estudiante_ci || '—') +
                        '</td>';
                    html += '<td style="font-size:0.8rem;">' + (ins.plan_pago || '—') + '</td>';
                    html += '<td><span style="' + estadoStyle +
                        'font-size:0.7rem;font-weight:700;padding:0.18rem 0.55rem;border-radius:20px;display:inline-block;">' +
                        (ins.estado || '—') + '</span></td>';
                    html +=
                        '<td style="text-align:right;font-weight:600;color:#1e293b;font-size:0.82rem;">Bs. ' +
                        parseFloat(ins.adelanto_bs || 0).toFixed(2) + '</td>';
                    html += '<td style="font-size:0.78rem;color:#64748b;white-space:nowrap;">' + (ins
                        .fecha_registro || '—') + '</td>';
                    html += '<td style="font-size:0.78rem;color:#64748b;">' + (ins.trabajador || '—') +
                        '</td>';
                    html +=
                        '<td style="font-size:0.78rem;color:#64748b;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="' +
                        (ins.observacion || '') + '">' + (ins.observacion || '—') + '</td>';
                    html += '</tr>';
                });

                html += '</tbody></table></div>';
                $('#inscripcionesContainer').html(html);
            }

            // Cambio de fase
            let cambioFaseData = null;

            $(document).on('click', '.cambiar-fase', function() {
                const btn = $(this);
                const puedeCambiar = btn.data('puede-cambiar') === true || btn.data('puede-cambiar') ===
                    'true';

                if (!puedeCambiar) {
                    const motivo = btn.data('motivo-bloqueo') ||
                        'No cumple los requisitos para cambiar de fase';
                    $('#cfIconRing').css({'background':'rgba(220,38,38,0.08)','border-color':'rgba(220,38,38,0.2)'});
                    $('#cfIcon').attr('class','ri-close-circle-line').css('color','#dc2626');
                    $('#cfFaseBox').css({'background':'rgba(220,38,38,0.05)','border-color':'rgba(220,38,38,0.18)'});
                    $('#cfMsgPrimary').text('No es posible cambiar de fase');
                    $('#faseNuevaNombre').text(motivo).css('color','#dc2626');
                    $('.btn-confirmar-cambio-fase').hide();
                    $('#modalConfirmarCambioFase').modal('show');
                    return;
                }

                cambioFaseData = {
                    ofertaId: btn.data('oferta-id'),
                    faseNuevaId: btn.data('fase-nueva-id'),
                    faseNuevaNombre: btn.data('fase-nueva-nombre'),
                    direccion: btn.data('direccion')
                };

                $('#cfIconRing').css({'background':'rgba(252,123,4,0.10)','border-color':'rgba(252,123,4,0.22)'});
                $('#cfIcon').attr('class','ri-arrow-left-right-line').css('color','#fc7b04');
                $('#cfFaseBox').css({'background':'linear-gradient(135deg,rgba(252,123,4,0.07),rgba(154,73,4,0.04))','border-color':'rgba(252,123,4,0.2)'});
                $('#cfMsgPrimary').text('¿Está seguro de cambiar la fase?');
                $('#faseNuevaNombre').text(cambioFaseData.faseNuevaNombre).css('color','#9a4904');
                $('.btn-confirmar-cambio-fase').show();
                $('#modalConfirmarCambioFase').modal('show');
            });

            $('.btn-confirmar-cambio-fase').on('click', function() {
                if (!cambioFaseData) return;

                const btn = $(this);
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

                $.ajax({
                    url: '/admin/ofertas-academicas/' + cambioFaseData.ofertaId + '/cambiar-fase',
                    type: 'PUT',
                    data: {
                        fase_id: cambioFaseData.faseNuevaId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        const nombreFase = cambioFaseData.faseNuevaNombre;
                        cambioFaseData = null;
                        $('#modalConfirmarCambioFase').modal('hide');
                        btn.prop('disabled', false).html('<i class="ri-check-line me-1"></i>Confirmar Cambio');
                        showToast('success', 'Fase cambiada correctamente a: ' + nombreFase);
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        showToast('error', xhr.responseJSON?.error || 'Error al cambiar la fase.');
                        btn.prop('disabled', false).html('<i class="ri-check-line me-1"></i>Confirmar Cambio');
                    }
                });
            });

            // Variables para inscripciones
            let currentOfertaId = null;
            let selectedEstudiante = null;
            let planesPagoDisponibles = [];
            let validarTimeout;
            let validaciones = {
                carnet: {
                    valido: true,
                    verificado: false
                },
                correo: {
                    valido: true,
                    verificado: false
                },
                sexo: {
                    valido: false
                }
            };

            // ── Estudios académicos ──────────────────────────────────────
            let estudiosLista = [];
            let gradosOpts = '';
            let univsOpts = '';
            let profesOpts = '';
            let catalogosCargados = false;

            function cargarCatalogos(callback) {
                if (catalogosCargados) {
                    if (callback) callback();
                    return;
                }
                Promise.all([
                    $.ajax({
                        url: '{{ route('admin.grados-academicos.listar') }}',
                        type: 'GET'
                    }),
                    $.ajax({
                        url: '{{ route('admin.universidades.listar') }}',
                        type: 'GET'
                    }),
                    $.ajax({
                        url: '{{ route('admin.profesiones.listar') }}',
                        type: 'GET'
                    }),
                ]).then(function([grados, univs, profes]) {
                    gradosOpts = '<option value="">— Grado académico —</option>';
                    const ga = grados.data || grados;
                    if (Array.isArray(ga)) ga.forEach(g => {
                        gradosOpts += '<option value="' + g.id + '">' + g.nombre + '</option>';
                    });

                    univsOpts = '<option value="">— Universidad —</option>';
                    const ua = univs.data || univs;
                    if (Array.isArray(ua)) ua.forEach(u => {
                        univsOpts += '<option value="' + u.id + '">' + u.nombre + (u.sigla ? ' (' +
                            u.sigla + ')' : '') + '</option>';
                    });

                    profesOpts = '<option value="">— Profesión —</option>';
                    const pa = profes.data || profes;
                    if (Array.isArray(pa)) pa.forEach(p => {
                        profesOpts += '<option value="' + p.id + '">' + p.nombre + '</option>';
                    });

                    catalogosCargados = true;
                    if (callback) callback();
                }).catch(function() {
                    catalogosCargados = true;
                    if (callback) callback();
                });
            }

            function renderEstudios() {
                let html = '';
                estudiosLista.forEach(function(est, idx) {
                    const isPrincipal = idx === 0;
                    html += '<div class="estudio-row' + (isPrincipal ? ' principal-row' : '') +
                        '" data-idx="' + idx + '">';
                    if (isPrincipal) {
                        html +=
                            '<span class="est-tag-principal"><i class="ri-star-fill"></i> Principal</span>';
                    }
                    html += '<select class="form-select form-select-sm est-select est-grado" data-idx="' +
                        idx + '">' + gradosOpts + '</select>';
                    html += '<select class="form-select form-select-sm est-select est-univ"  data-idx="' +
                        idx + '">' + univsOpts + '</select>';
                    html += '<select class="form-select form-select-sm est-select est-prof"  data-idx="' +
                        idx + '">' + profesOpts + '</select>';
                    html += '<button type="button" class="btn-del-est" data-idx="' + idx +
                        '" title="Eliminar"><i class="ri-delete-bin-line"></i></button>';
                    html += '</div>';
                });
                $('#estudiosLista').html(html);
                // Restaurar seleccionados
                estudiosLista.forEach(function(est, idx) {
                    if (est.grados_academico_id) $('.est-grado[data-idx="' + idx + '"]').val(est
                        .grados_academico_id);
                    if (est.universidade_id) $('.est-univ[data-idx="' + idx + '"]').val(est
                    .universidade_id);
                    if (est.profesione_id) $('.est-prof[data-idx="' + idx + '"]').val(est.profesione_id);
                });
            }

            $(document).on('click', '#btnAgregarEstudio', function() {
                estudiosLista.push({
                    grados_academico_id: '',
                    universidade_id: '',
                    profesione_id: ''
                });
                renderEstudios();
            });

            $(document).on('click', '.btn-del-est', function() {
                estudiosLista.splice(parseInt($(this).data('idx')), 1);
                renderEstudios();
            });

            $(document).on('change', '.est-grado', function() {
                estudiosLista[parseInt($(this).data('idx'))].grados_academico_id = $(this).val();
            });
            $(document).on('change', '.est-univ', function() {
                estudiosLista[parseInt($(this).data('idx'))].universidade_id = $(this).val();
            });
            $(document).on('change', '.est-prof', function() {
                estudiosLista[parseInt($(this).data('idx'))].profesione_id = $(this).val();
            });
            // ─────────────────────────────────────────────────────────────

            // Abrir modal de inscripciones
            $(document).on('click', '.ver-inscripciones', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Click en ver-inscripciones', $(this).data());
                currentOfertaId = $(this).data('oferta-id');
                const codigo = $(this).data('codigo');

                $('#inscripcionesOfertaCodigo').text(codigo);
                $('#buscarCarnet').val('');
                $('#busquedaResultado').html('');
                $('#btnNuevaInscripcion').prop('disabled', true);
                selectedEstudiante = null;

                $('#inscripcionesLoading').show();
                $('#inscripcionesEmpty').hide();
                $('#inscripcionesContainer').empty();

                console.log('Making AJAX request to:', '/admin/ofertas-academicas/' + currentOfertaId +
                    '/inscripciones');

                $.ajax({
                    url: '/admin/ofertas-academicas/' + currentOfertaId + '/inscripciones',
                    type: 'GET',
                    success: function(response) {
                        console.log('Respuesta inscripciones:', response);
                        $('#inscripcionesLoading').hide();
                        const inscripciones = response.data || [];

                        if (!inscripciones.length) {
                            $('#inscripcionesEmpty').show();
                            $('#modalInscripciones').modal('show');
                            return;
                        }

                        renderInscripcionesTable(inscripciones);
                        $('#modalInscripciones').modal('show');
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr);
                        $('#inscripcionesLoading').hide();
                        alert('Error al cargar las inscripciones: ' + (xhr.responseText ||
                            'Error'));
                    }
                });

                // Cargar planes de pago disponibles para el select
                $.ajax({
                    url: '/admin/ofertas-academicas/' + currentOfertaId + '/configuraciones-precio',
                    type: 'GET',
                    timeout: 10000,
                    success: function(response) {
                        const configs = response.data || [];
                        const planesMap = {};

                        if (!configs.length) {
                            $('#inscripcionPlanPago').html(
                                '<option value="">No hay planes disponibles para esta oferta</option>'
                            );
                            return;
                        }

                        configs.forEach(function(c) {
                            if (c.plan_pago && !planesMap[c.plan_pago.id]) {
                                planesMap[c.plan_pago.id] = c.plan_pago.nombre;
                            }
                        });

                        let options = '<option value="">Seleccionar plan...</option>';

                        if (Object.keys(planesMap).length === 0) {
                            options = '<option value="">No hay planes disponibles</option>';
                        } else {
                            planesPagoDisponibles = Object.entries(planesMap).map(function([id,
                                nombre
                            ]) {
                                return {
                                    id: id,
                                    nombre: nombre
                                };
                            });
                            planesPagoDisponibles.forEach(function(p) {
                                options += '<option value="' + p.id + '">' + p.nombre +
                                    '</option>';
                            });
                        }
                        $('#inscripcionPlanPago').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error cargando planes:', status, error);
                        $('#inscripcionPlanPago').html(
                            '<option value="">Error al cargar planes</option>'
                        );
                    }
                });
            });

            // Validación en tiempo real del carnet de búsqueda (7 a 11 dígitos)
            function setBtnBuscarEstudianteEnabled(enabled) {
                const $btn = $('#btnBuscarEstudiante');
                $btn.prop('disabled', !enabled).css({
                    'opacity': enabled ? '1' : '0.5',
                    'cursor': enabled ? 'pointer' : 'not-allowed',
                    'pointer-events': enabled ? '' : 'none'
                });
            }
            function validarCarnetBusqueda() {
                const $input = $('#buscarCarnet');
                const $fb = $('#carnetSearchFeedback');
                const raw = $input.val() || '';
                const cleaned = raw.replace(/\D/g, '').slice(0, 11);
                if (cleaned !== raw) $input.val(cleaned);
                $input.css('border-color', '');
                if (!cleaned) {
                    $fb.html('').css('color', '');
                    setBtnBuscarEstudianteEnabled(false);
                    return { ok: false, empty: true, value: cleaned };
                }
                if (cleaned.length < 7) {
                    $fb.html('<i class="ri-error-warning-line"></i> El carnet debe tener entre 7 y 11 dígitos. Faltan ' + (7 - cleaned.length) + '.').css('color', '#ef4444');
                    $input.css('border-color', '#ef4444');
                    setBtnBuscarEstudianteEnabled(false);
                    return { ok: false, value: cleaned };
                }
                if (cleaned.length > 11) {
                    $fb.html('<i class="ri-error-warning-line"></i> Máximo 11 dígitos.').css('color', '#ef4444');
                    $input.css('border-color', '#ef4444');
                    setBtnBuscarEstudianteEnabled(false);
                    return { ok: false, value: cleaned };
                }
                $fb.html('<i class="ri-checkbox-circle-line"></i> Carnet válido (' + cleaned.length + ' dígitos).').css('color', '#10b981');
                $input.css('border-color', '#10b981');
                setBtnBuscarEstudianteEnabled(true);
                return { ok: true, value: cleaned };
            }
            $(document).on('input', '#buscarCarnet', function() {
                validarCarnetBusqueda();
            });
            $(document).on('shown.bs.modal', '#modalInscripciones', function() {
                validarCarnetBusqueda();
            });

            // Buscar estudiante por carnet
            $('#btnBuscarEstudiante').on('click', function() {
                const v = validarCarnetBusqueda();
                if (!v.ok) {
                    return;
                }
                const carnet = v.value;

                $('#busquedaResultado').html(
                    '<div class="text-center py-2"><span class="spinner-border spinner-border-sm"></span> Buscando...</div>'
                );

                $.ajax({
                    url: '{{ route('admin.estudiantes.buscarCarnet') }}',
                    type: 'POST',
                    data: {
                        carnet: carnet,
                        _token: '{{ csrf_token() }}'
                    },
                    timeout: 10000,
                    success: function(response) {
                        if (!response.encontrado) {
                            $('#busquedaResultado').html(
                                '<div class="rcrd warn">' +
                                '<div class="rcrd-ico"><i class="ri-user-search-line"></i></div>' +
                                '<div class="rcrd-body">' +
                                '<div class="rcrd-title">Persona no encontrada</div>' +
                                '<div class="rcrd-sub mb-2">No existe ningún registro con ese carnet.</div>' +
                                '<button type="button" class="btn btn-sm" id="btnRegistrarNuevaPersona" style="background:linear-gradient(135deg,#7a3b03,#fc7b04);color:#fff;font-size:0.78rem;font-weight:600;border-radius:7px;padding:0.35rem 0.9rem;box-shadow:0 3px 10px rgba(252,123,4,.3);">' +
                                '<i class="ri-user-add-line me-1"></i>Registrar nueva persona</button>' +
                                '</div></div>'
                            );
                            return;
                        }

                        if (response.ya_estudiante) {
                            $.ajax({
                                url: '/admin/ofertas-academicas/' + currentOfertaId +
                                    '/inscripciones',
                                type: 'GET',
                                success: function(inscResp) {
                                    const inscripciones = inscResp.data || [];
                                    const yaInscrito = inscripciones.some(function(
                                        i) {
                                        return i.estudiante_ci === response
                                            .persona.carnet;
                                    });

                                    if (yaInscrito) {
                                        $('#busquedaResultado').html(
                                            '<div class="rcrd err">' +
                                            '<div class="rcrd-ico"><i class="ri-error-warning-line"></i></div>' +
                                            '<div class="rcrd-body"><div class="rcrd-title" style="color:#dc2626;">Ya inscrito</div>' +
                                            '<div class="rcrd-sub">Este estudiante ya está inscrito en esta oferta académica.</div></div></div>'
                                        );
                                    } else {
                                        selectedEstudiante = {
                                            id: response.estudiante_id,
                                            es_estudiante: true,
                                            persona: response.persona
                                        };
                                        const nombre = (response.persona.nombres +
                                            ' ' + (response.persona
                                                .apellido_paterno || '')).trim();
                                        $('#busquedaResultado').html(
                                            '<div class="rcrd ok">' +
                                            '<div class="rcrd-ico"><i class="ri-user-follow-line"></i></div>' +
                                            '<div class="rcrd-body"><div class="rcrd-title" style="color:#16a34a;">Estudiante encontrado</div>' +
                                            '<div class="rcrd-sub"><strong>' +
                                            nombre + '</strong> &mdash; CI: ' +
                                            response.persona.carnet +
                                            '</div></div></div>'
                                        );
                                        $('#btnNuevaInscripcion').prop('disabled',
                                            false);
                                    }
                                }
                            });
                        } else {
                            selectedEstudiante = {
                                id: response.persona.id,
                                es_estudiante: false,
                                persona: response.persona
                            };
                            const nombre = (response.persona.nombres + ' ' + (response.persona
                                .apellido_paterno || '')).trim();
                            $('#busquedaResultado').html(
                                '<div class="rcrd info">' +
                                '<div class="rcrd-ico"><i class="ri-user-line"></i></div>' +
                                '<div class="rcrd-body"><div class="rcrd-title" style="color:#ea580c;">Persona encontrada — no es estudiante</div>' +
                                '<div class="rcrd-sub mb-2"><strong>' + nombre +
                                '</strong> &mdash; CI: ' + response.persona.carnet +
                                '</div>' +
                                '<button type="button" class="btn btn-sm" id="btnRegistrarComoEstudiante" style="background:linear-gradient(135deg,#b45309,#f59e0b);color:#fff;font-size:0.78rem;font-weight:600;border-radius:7px;padding:0.35rem 0.9rem;">' +
                                '<i class="ri-user-star-line me-1"></i>Registrar como estudiante</button>' +
                                '</div></div>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        let msg = 'Error al buscar. Intente nuevamente.';
                        if (status === 'timeout') msg = 'Tiempo de espera agotado.';
                        else if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr
                            .responseJSON.message;
                        $('#busquedaResultado').html(
                            '<div class="rcrd err">' +
                            '<div class="rcrd-ico"><i class="ri-wifi-off-line"></i></div>' +
                            '<div class="rcrd-body"><div class="rcrd-title" style="color:#dc2626;">Error de búsqueda</div>' +
                            '<div class="rcrd-sub mb-2">' + msg + '</div>' +
                            '<button type="button" class="btn btn-sm btn-outline-secondary" id="btnReintentarBusqueda" style="font-size:0.78rem;border-radius:7px;">' +
                            '<i class="ri-refresh-line me-1"></i>Reintentar</button></div></div>'
                        );
                    }
                });
            });

            // Reintentar búsqueda
            $(document).on('click', '#btnReintentarBusqueda', function() {
                $('#btnBuscarEstudiante').trigger('click');
            });

            // Registrar nueva persona
            $(document).on('click', '#btnRegistrarNuevaPersona', function() {
                const carnetBuscado = $('#buscarCarnet').val().trim();
                $('#inputCarnetPersona').val(carnetBuscado);

                // Resetear validaciones y estudios
                validaciones = {
                    carnet: {
                        valido: true,
                        verificado: false
                    },
                    correo: {
                        valido: false,
                        verificado: false
                    },
                    sexo: {
                        valido: false
                    }
                };
                estudiosLista = [];
                $('.validacion-feedback').remove();
                $('#btnConfirmarRegistrarPersona').prop('disabled', true);
                $('#formRegistrarPersona')[0].reset();
                $('#inputCarnetPersona').val(carnetBuscado);
                $('#modalRegistrarPersona .form-control, #modalRegistrarPersona .form-select').removeClass('is-invalid-custom is-valid-custom');
                $('#modalRegistrarPersona .invalid-feedback-custom').text('').hide();
                // Mostrar inmediatamente los requisitos pendientes (apellidos, correo)
                setTimeout(function() {
                    validarApellidosPersona();
                    $('#fbCorreo').text('Ingrese un correo válido.').css('color', '#94a3b8').show();
                }, 0);

                // Cargar departamentos si no existen
                if ($('#inputDepartamento option').length <= 1) {
                    $.ajax({
                        url: '{{ route('admin.departamentos.listar') }}',
                        type: 'GET',
                        success: function(response) {
                            let options = '<option value="">Seleccionar...</option>';
                            const deptos = response.data || response;
                            if (Array.isArray(deptos)) {
                                deptos.forEach(function(d) {
                                    options += '<option value="' + d.id + '">' + d
                                        .nombre + '</option>';
                                });
                            }
                            $('#inputDepartamento').html(options);
                        }
                    });
                }

                // Cargar catálogos para estudios y renderizar lista vacía
                cargarCatalogos(function() {
                    renderEstudios();
                });

                $('#modalRegistrarPersona').modal('show');
            });

            // Cascada departamento -> ciudad
            $(document).on('change', '#inputDepartamento', function() {
                const deptId = $(this).val();
                if (!deptId) {
                    $('#inputCiudad').html('<option value="">Seleccione departamento primero</option>')
                        .prop('disabled', true);
                    return;
                }

                $.ajax({
                    url: '/admin/departamentos/' + deptId + '/ciudades/listar',
                    type: 'GET',
                    success: function(data) {
                        let options = '<option value="">Seleccionar...</option>';
                        data.forEach(function(c) {
                            options += '<option value="' + c.id + '">' + c.nombre +
                                '</option>';
                        });
                        $('#inputCiudad').html(options).prop('disabled', false);
                    }
                });
            });

            // Helpers de validación visual
            function fbShow($el, ok, msg) {
                if (!msg) {
                    $el.text('').hide();
                    return;
                }
                $el.text(msg).css('color', ok ? '#10b981' : '#ef4444').show();
            }
            function markField(name, ok, msg) {
                const $el = $('[name="' + name + '"]').first();
                $el.removeClass('is-invalid-custom is-valid-custom');
                if (msg !== undefined) $el.addClass(ok ? 'is-valid-custom' : 'is-invalid-custom');
                const fbId = 'fb' + name.replace(/(^|_)(\w)/g, (m, _, c) => c.toUpperCase());
                const $fb = $('#' + fbId);
                if ($fb.length) fbShow($fb, ok, msg);
            }

            function validarApellidosPersona() {
                const ap = ($('[name="apellido_paterno"]').val() || '').trim();
                const am = ($('[name="apellido_materno"]').val() || '').trim();
                $('[name="apellido_paterno"], [name="apellido_materno"]').removeClass('is-invalid-custom is-valid-custom');
                $('#fbApellidoPaterno, #fbApellidoMaterno').text('').hide();
                if (!ap && !am) {
                    $('[name="apellido_paterno"], [name="apellido_materno"]').addClass('is-invalid-custom');
                    fbShow($('#fbApellidoPaterno'), false, 'Ingrese al menos un apellido (paterno o materno).');
                    fbShow($('#fbApellidoMaterno'), false, 'Ingrese al menos un apellido (paterno o materno).');
                    return false;
                }
                if (ap) $('[name="apellido_paterno"]').addClass('is-valid-custom');
                if (am) $('[name="apellido_materno"]').addClass('is-valid-custom');
                return true;
            }

            function validarCelularPersona() {
                const $input = $('[name="celular"]');
                const cleaned = ($input.val() || '').replace(/\D/g, '').slice(0, 8);
                if (cleaned !== $input.val()) $input.val(cleaned);
                $input.removeClass('is-invalid-custom is-valid-custom');
                if (!cleaned) {
                    $input.addClass('is-invalid-custom');
                    fbShow($('#fbCelular'), false, 'El celular es requerido.');
                    return false;
                }
                if (cleaned.length !== 8) {
                    $input.addClass('is-invalid-custom');
                    fbShow($('#fbCelular'), false, 'Debe tener exactamente 8 dígitos.');
                    return false;
                }
                $input.addClass('is-valid-custom');
                fbShow($('#fbCelular'), true, 'Celular válido.');
                return true;
            }

            function validarSelectRequerido(name, msg) {
                const $el = $('[name="' + name + '"]').first();
                const val = $el.val();
                $el.removeClass('is-invalid-custom is-valid-custom');
                const fbId = '#fb' + name.replace(/(^|_)(\w)/g, (m, _, c) => c.toUpperCase());
                const $fb = $(fbId);
                if (!val) {
                    $el.addClass('is-invalid-custom');
                    fbShow($fb, false, msg);
                    return false;
                }
                $el.addClass('is-valid-custom');
                fbShow($fb, true, '');
                return true;
            }

            function validarNombresPersona() {
                const $el = $('[name="nombres"]');
                const val = ($el.val() || '').trim();
                $el.removeClass('is-invalid-custom is-valid-custom');
                if (!val) {
                    $el.addClass('is-invalid-custom');
                    fbShow($('#fbNombres'), false, 'Los nombres son requeridos.');
                    return false;
                }
                $el.addClass('is-valid-custom');
                fbShow($('#fbNombres'), true, '');
                return true;
            }

            // Validación de formato del correo (la unicidad se verifica con `validarCampo`)
            function validarCorreoFormato() {
                const $el = $('[name="correo"]');
                const val = ($el.val() || '').trim();
                $el.removeClass('is-invalid-custom is-valid-custom');
                if (!val) {
                    $el.addClass('is-invalid-custom');
                    fbShow($('#fbCorreo'), false, 'El correo es requerido.');
                    validaciones.correo.valido = false;
                    validaciones.correo.verificado = false;
                    return false;
                }
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                    $el.addClass('is-invalid-custom');
                    fbShow($('#fbCorreo'), false, 'Ingrese un correo válido (ejemplo@dominio.com).');
                    validaciones.correo.valido = false;
                    validaciones.correo.verificado = false;
                    return false;
                }
                // Formato correcto; el estado final lo define la verificación AJAX de unicidad
                $el.addClass('is-valid-custom');
                return true;
            }

            // Función para actualizar botón registrar
            function actualizarBotonRegistrar() {
                const btn = $('#btnConfirmarRegistrarPersona');

                const sexoOk = ($('select[name="sexo"]').val() || '') !== '';
                const ecOk = ($('select[name="estado_civil"]').val() || '') !== '';
                const ciudadOk = ($('select[name="ciudade_id"]').val() || '') !== '';
                const nombresOk = (($('[name="nombres"]').val() || '').trim()) !== '';
                const apOk = (($('[name="apellido_paterno"]').val() || '').trim()) !== ''
                    || (($('[name="apellido_materno"]').val() || '').trim()) !== '';
                const cel = (($('[name="celular"]').val() || '').replace(/\D/g, ''));
                const celOk = cel.length === 8;

                validaciones.sexo.valido = sexoOk;

                const habilitar = validaciones.carnet.valido &&
                    validaciones.carnet.verificado &&
                    validaciones.correo.valido &&
                    validaciones.correo.verificado &&
                    sexoOk && ecOk && ciudadOk && nombresOk && apOk && celOk;

                btn.prop('disabled', !habilitar);
                btn.css({
                    'opacity': habilitar ? '1' : '0.55',
                    'cursor': habilitar ? 'pointer' : 'not-allowed'
                });

                if (!habilitar) {
                    btn.attr('title',
                        'Complete todos los campos requeridos (sexo, estado civil, ciudad, al menos un apellido, celular de 8 dígitos) y verifique carnet/correo.');
                } else {
                    btn.removeAttr('title');
                }
            }

            // Función para validar campos
            function validarCampo(carnet, correo) {
                $('#btnConfirmarRegistrarPersona').prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.estudiantes.validarCampos') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        carnet: carnet,
                        correo: correo
                    },
                    success: function(response) {
                        // Validar carnet
                        if (carnet && carnet.length >= 3) {
                            validaciones.carnet.verificado = true;
                            validaciones.carnet.valido = response.disponible.carnet;

                            const carnetFeedback = response.disponible.carnet ?
                                '<span class="text-success"><i class="ri-check-circle-fill"></i> ' +
                                response.mensajes.carnet + '</span>' :
                                '<span class="text-danger"><i class="ri-close-circle-fill"></i> ' +
                                response.mensajes.carnet + '</span>';

                            $('#inputCarnetPersona').next('.validacion-feedback').remove();
                            $('#inputCarnetPersona').after('<div class="validacion-feedback small">' +
                                carnetFeedback + '</div>');
                        }

                        // Validar correo (sólo si el formato ya es válido)
                        if (correo && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
                            validaciones.correo.valido = response.disponible.correo;
                            validaciones.correo.verificado = true;

                            const $correo = $('input[name="correo"]');
                            $correo.removeClass('is-invalid-custom is-valid-custom');
                            if (response.disponible.correo) {
                                $correo.addClass('is-valid-custom');
                                $('#fbCorreo').text('Correo disponible.').css('color', '#10b981').show();
                            } else {
                                $correo.addClass('is-invalid-custom');
                                $('#fbCorreo').text(response.mensajes.correo || 'Este correo ya está registrado.').css('color', '#ef4444').show();
                            }
                        }

                        actualizarBotonRegistrar();
                    },
                    error: function() {
                        $('#inputCarnetPersona').after(
                            '<div class="validacion-feedback small text-danger"><i class="ri-error-warning-fill"></i> Error al validar</div>'
                        );
                        $('#btnConfirmarRegistrarPersona').prop('disabled', true);
                    }
                });
            }

            // Eventos input para validar
            $(document).on('input', '#inputCarnetPersona, input[name="correo"]', function() {
                clearTimeout(validarTimeout);
                const isCorreo = $(this).is('[name="correo"]');
                if (isCorreo) {
                    const formatoOk = validarCorreoFormato();
                    actualizarBotonRegistrar();
                    if (!formatoOk) return;
                }
                const carnet = $('#inputCarnetPersona').val().trim();
                const correo = $('input[name="correo"]').val().trim();

                if (carnet.length >= 3 || correo.length >= 3) {
                    validarTimeout = setTimeout(function() {
                        validarCampo(carnet, correo);
                    }, 350);
                }
            });

            $(document).on('change', 'select[name="sexo"]', function() {
                validarSelectRequerido('sexo', 'Seleccione el sexo.');
                actualizarBotonRegistrar();
            });
            $(document).on('change', 'select[name="estado_civil"]', function() {
                validarSelectRequerido('estado_civil', 'Seleccione el estado civil.');
                actualizarBotonRegistrar();
            });
            $(document).on('change', 'select[name="ciudade_id"]', function() {
                validarSelectRequerido('ciudade_id', 'Seleccione la ciudad.');
                actualizarBotonRegistrar();
            });
            $(document).on('input', '[name="nombres"]', function() {
                validarNombresPersona();
                actualizarBotonRegistrar();
            });
            $(document).on('input', '[name="apellido_paterno"], [name="apellido_materno"]', function() {
                validarApellidosPersona();
                actualizarBotonRegistrar();
            });
            $(document).on('input', '[name="celular"]', function() {
                validarCelularPersona();
                actualizarBotonRegistrar();
            });

            $('#btnConfirmarRegistrarPersona').on('click', function() {
                // Sincronizar selects actuales antes de enviar
                $('.est-grado').each(function() {
                    const i = parseInt($(this).data('idx'));
                    if (estudiosLista[i]) estudiosLista[i].grados_academico_id = $(this).val();
                });
                $('.est-univ').each(function() {
                    const i = parseInt($(this).data('idx'));
                    if (estudiosLista[i]) estudiosLista[i].universidade_id = $(this).val();
                });
                $('.est-prof').each(function() {
                    const i = parseInt($(this).data('idx'));
                    if (estudiosLista[i]) estudiosLista[i].profesione_id = $(this).val();
                });

                const form = $('#formRegistrarPersona')[0];
                let okExtras = true;
                if (!validarNombresPersona()) okExtras = false;
                if (!validarApellidosPersona()) okExtras = false;
                if (!validarSelectRequerido('sexo', 'Seleccione el sexo.')) okExtras = false;
                if (!validarSelectRequerido('estado_civil', 'Seleccione el estado civil.')) okExtras = false;
                if (!validarSelectRequerido('ciudade_id', 'Seleccione la ciudad.')) okExtras = false;
                if (!validarCelularPersona()) okExtras = false;
                if (!validarCorreoFormato()) okExtras = false;
                if (!validaciones.correo.verificado || !validaciones.correo.valido) {
                    fbShow($('#fbCorreo'), false, 'El correo no ha sido verificado o ya está registrado.');
                    okExtras = false;
                }
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                if (!okExtras) {
                    showToast('error', 'Corrija los campos marcados antes de continuar.');
                    return;
                }

                const btn = $(this);
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

                const estudiosValidos = estudiosLista.filter(e => e.grados_academico_id);

                $.ajax({
                    url: '{{ route('admin.estudiantes.guardarPersona') }}',
                    type: 'POST',
                    data: $('#formRegistrarPersona').serialize() +
                        '&_token={{ csrf_token() }}&estudios_json=' + encodeURIComponent(JSON
                            .stringify(estudiosValidos)),
                    success: function(response) {
                        $('#modalRegistrarPersona').modal('hide');
                        form.reset();
                        estudiosLista = [];
                        showToast('success', 'Persona registrada correctamente.');

                        selectedEstudiante = {
                            id: response.data.id,
                            es_estudiante: false,
                            persona: response.data
                        };
                        const nombreReg = (response.data.nombres + ' ' + (response.data
                            .apellido_paterno || '')).trim();
                        $('#busquedaResultado').html(
                            '<div class="rcrd info">' +
                            '<div class="rcrd-ico"><i class="ri-user-line"></i></div>' +
                            '<div class="rcrd-body"><div class="rcrd-title" style="color:#ea580c;">Persona registrada — registrar como estudiante</div>' +
                            '<div class="rcrd-sub mb-2"><strong>' + nombreReg +
                            '</strong> &mdash; CI: ' + response.data.carnet + '</div>' +
                            '<button type="button" class="btn btn-sm" id="btnRegistrarComoEstudiante" style="background:linear-gradient(135deg,#b45309,#f59e0b);color:#fff;font-size:0.78rem;font-weight:600;border-radius:7px;padding:0.35rem 0.9rem;">' +
                            '<i class="ri-user-star-line me-1"></i>Registrar como estudiante</button>' +
                            '</div></div>'
                        );
                        btn.prop('disabled', false).html(
                            '<i class="ri-user-add-line me-1"></i>Registrar Persona');
                    },
                    error: function(xhr) {
                        showToast('error', 'Error al registrar persona: ' + (xhr.responseJSON
                            ?.errors ?
                            Object.values(xhr.responseJSON.errors).flat().join(', ') :
                            xhr.responseJSON?.error || 'Error desconocido'));
                        btn.prop('disabled', false).html(
                            '<i class="ri-user-add-line me-1"></i>Registrar Persona');
                    }
                });
            });

            // Registrar como estudiante (persona existe)
            $(document).on('click', '#btnRegistrarComoEstudiante, #btnConfirmarRegistrarEstudiante', function() {
                if (!selectedEstudiante) return;

                const btn = $(this);
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Registrando...');

                $.ajax({
                    url: '{{ route('admin.estudiantes.registrar') }}',
                    type: 'POST',
                    data: {
                        persona_id: selectedEstudiante.id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#modalRegistrarEstudiante').modal('hide');
                        showToast('success', 'Estudiante registrado correctamente.');

                        selectedEstudiante = {
                            id: response.data.id,
                            es_estudiante: true,
                            persona: selectedEstudiante.persona
                        };
                        const nombreEst = (selectedEstudiante.persona.nombres + ' ' + (
                            selectedEstudiante.persona.apellido_paterno || '')).trim();
                        $('#busquedaResultado').html(
                            '<div class="rcrd ok">' +
                            '<div class="rcrd-ico"><i class="ri-user-follow-line"></i></div>' +
                            '<div class="rcrd-body"><div class="rcrd-title" style="color: var(--primary);">Estudiante registrado correctamente</div>' +
                            '<div class="rcrd-sub"><strong>' + nombreEst +
                            '</strong> &mdash; CI: ' + selectedEstudiante.persona.carnet +
                            '</div></div></div>'
                        );
                        $('#btnNuevaInscripcion').prop('disabled', false);
                        btn.prop('disabled', false).html(
                            '<i class="ri-user-star-line me-1"></i>Registrar como Estudiante'
                            );
                    },
                    error: function(xhr) {
                        showToast('error', 'Error al registrar estudiante: ' + (xhr.responseJSON
                            ?.error ||
                            'Error desconocido'));
                        btn.prop('disabled', false).html('Registrar como Estudiante');
                    }
                });
            });

            // Nueva inscripción - abrir modal
            $('#btnNuevaInscripcion').on('click', function() {
                if (!selectedEstudiante) return;

                $('#inscripcionEstudianteNombre').text(
                    (selectedEstudiante.persona?.nombres || '') + ' ' +
                    (selectedEstudiante.persona?.apellido_paterno || '') + ' ' +
                    (selectedEstudiante.persona?.apellido_materno || '')
                );
                $('#inscripcionEstudianteCi').text(selectedEstudiante.persona?.carnet || '');
                $('#inscripcionEstado').val('Pre-Inscrito');
                $('#inscripcionAdelanto').val(0);
                $('#inscripcionObservacion').val('');
                $('#conceptosContainer').hide();
                $('#conceptosList').empty();

                $('#modalNuevaInscripcion').modal('show');
            });

            // Cambiar plan de pago - cargar conceptos
            $('#inscripcionPlanPago').on('change', function() {
                const planId = $(this).val();
                const estado = $('#inscripcionEstado').val();

                if (!planId || estado !== 'Inscrito') {
                    $('#conceptosContainer').hide();
                    return;
                }

                $.ajax({
                    url: '/admin/ofertas-academicas/' + currentOfertaId + '/plan/' + planId +
                        '/detalle',
                    type: 'GET',
                    success: function(response) {
                        if (response.success && response.data.length) {
                            renderConceptos(response.data);
                            $('#conceptosContainer').show();
                        } else {
                            $('#conceptosContainer').hide();
                        }
                    },
                    error: function() {
                        $('#conceptosContainer').hide();
                    }
                });
            });

            // Cambiar estado - mostrar/ocultar conceptos
            $('#inscripcionEstado').on('change', function() {
                const planId = $('#inscripcionPlanPago').val();
                const estado = $(this).val();

                if (!planId || estado !== 'Inscrito') {
                    $('#conceptosContainer').hide();
                    return;
                }

                if (!planesPagoDisponibles || planesPagoDisponibles.length === 0) {
                    showToast('error',
                        'No hay planes de pago configurados para esta oferta. Solo puede registrar Pre-Inscripción.'
                    );
                    $('#inscripcionEstado').val('Pre-Inscrito');
                    $('#conceptosContainer').hide();
                    return;
                }

                $.ajax({
                    url: '/admin/ofertas-academicas/' + currentOfertaId + '/plan/' + planId +
                        '/detalle',
                    type: 'GET',
                    success: function(response) {
                        if (response.success && response.data.length) {
                            renderConceptos(response.data);
                            $('#conceptosContainer').show();
                        } else {
                            showToast('error', 'No hay conceptos configurados para este plan.');
                            $('#conceptosContainer').hide();
                        }
                    },
                    error: function() {
                        $('#conceptosContainer').hide();
                    }
                });
            });

            function renderConceptos(data) {
                let html = '';

                const MESES_OPTS = [
                    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ].map(function(m, mi) {
                    return '<option value="' + (mi + 1) + '">' + m + '</option>';
                }).join('');

                const conceptColors = [
                    'linear-gradient(135deg,#391b04,#9a4904)',
                    'linear-gradient(135deg,#7a3b03,#c96004)',
                    'linear-gradient(135deg,#9a4904,#df6a04)',
                    'linear-gradient(135deg,#5c2d0a,#fc7b04)',
                ];

                data.forEach(function(item, idx) {
                    const pagoBsOriginal = parseFloat(item.pago_bs);
                    const esUnaCuota = item.n_cuotas === 1;
                    const disabledDividir = esUnaCuota ? 'disabled' : '';
                    const headerColor = conceptColors[idx % conceptColors.length];

                    html += '<div class="ni-concepto-card">';

                    // ── Cabecera del concepto ─────────────────────────────
                    html += '<div class="ni-concepto-header" style="background:' + headerColor + ';">';
                    html += '<div class="ni-concepto-nombre">';
                    html += '<i class="ri-price-tag-3-line" style="opacity:.75;font-size:.9rem;"></i>';
                    html += '<span>' + (item.concepto || '—') + '</span>';
                    html += '</div>';
                    html += '<div class="ni-concepto-totals">';
                    html += '<span class="badge-total-concepto ni-badge-total" data-original="' + pagoBsOriginal + '">';
                    html += 'Bs. ' + pagoBsOriginal.toFixed(2);
                    html += '</span>';
                    html += '<span class="badge-diferencia"></span>';
                    html += '</div></div>';

                    // ── Cuerpo ────────────────────────────────────────────
                    html += '<div class="ni-concepto-body">';

                    // Control de monto / dividir cuotas
                    html += '<div class="ni-controls-bar">';
                    html += '<label class="ni-ctrl-label"><i class="ri-funds-line me-1"></i>Monto por cuota:</label>';
                    html += '<input type="number" class="form-control form-control-sm" id="monto-pagar-' + idx + '" placeholder="0.00" min="0" step="1" style="width:105px;font-size:.82rem;border-radius:7px;" ' + disabledDividir + '>';
                    html += '<button type="button" class="btn btn-sm btn-dividir-cuotas" data-concepto-idx="' + idx + '" data-n-cuotas="' + item.n_cuotas + '" ' + disabledDividir + ' style="background:#9a4904;color:#fff;font-size:.75rem;border-radius:7px;padding:.28rem .7rem;">';
                    html += '<i class="ri-divide-line me-1"></i>Aplicar a ' + item.n_cuotas + ' cuota' + (item.n_cuotas !== 1 ? 's' : '') + '</button>';
                    if (item.n_cuotas > 1) {
                        html += '<button type="button" class="btn btn-sm btn-invertir-cuotas" data-concepto-idx="' + idx + '" title="Invertir orden de cuotas" style="background:#7a3b03;color:#fff;font-size:.75rem;border-radius:7px;padding:.28rem .65rem;">';
                        html += '<i class="ri-swap-line me-1"></i>Invertir</button>';
                    }
                    if (esUnaCuota) {
                        html += '<span style="font-size:.71rem;color:#64748b;display:inline-flex;align-items:center;gap:.25rem;"><i class="ri-information-line"></i>Cuota única</span>';
                    }
                    html += '</div>';

                    // Control de fechas
                    html += '<div class="ni-controls-bar green">';
                    html += '<div class="d-flex align-items-center gap-2">';
                    html += '<label class="ni-ctrl-label"><i class="ri-calendar-check-line me-1"></i>Día de pago:</label>';
                    html += '<input type="number" class="form-control form-control-sm" id="dia-venc-' + idx + '" min="1" max="31" placeholder="1–31" style="width:68px;font-size:.82rem;border-radius:7px;">';
                    html += '<button type="button" class="btn btn-sm btn-aplicar-dia" data-concepto-idx="' + idx + '" style="background:#c96004;color:#fff;font-size:.73rem;border-radius:7px;padding:.28rem .62rem;">';
                    html += '<i class="ri-check-line me-1"></i>Aplicar</button>';
                    html += '</div>';
                    html += '<div class="ni-ctrl-divider"></div>';
                    html += '<div class="d-flex align-items-center gap-2">';
                    html += '<label class="ni-ctrl-label"><i class="ri-calendar-line me-1"></i>Mes inicio:</label>';
                    html += '<select class="form-select form-select-sm" id="mes-inicio-' + idx + '" style="width:128px;font-size:.78rem;border-radius:7px;"><option value="">— Mes —</option>' + MESES_OPTS + '</select>';
                    html += '<button type="button" class="btn btn-sm btn-aplicar-mes" data-concepto-idx="' + idx + '" data-n-cuotas="' + item.n_cuotas + '" style="background:#c96004;color:#fff;font-size:.73rem;border-radius:7px;padding:.28rem .62rem;">';
                    html += '<i class="ri-check-line me-1"></i>Aplicar</button>';
                    html += '</div>';
                    html += '</div>';

                    // Tabla de cuotas
                    html += '<div style="border-radius:8px;overflow:hidden;border:1px solid #e2e8f0;">';
                    html += '<table class="ni-cuotas-table"><thead><tr>';
                    html += '<th style="width:90px;">#</th>';
                    html += '<th>Monto (Bs)</th>';
                    html += '<th>Fecha Vencimiento</th>';
                    html += '</tr></thead><tbody>';

                    item.cuotas.forEach(function(cuota, cuotaIdx) {
                        const fechaVenc = cuota.fecha_vencimiento || '';
                        const disabledAttr = esUnaCuota ? 'disabled' : '';
                        html += '<tr>';
                        html += '<td><span style="font-size:.75rem;color:#64748b;font-weight:600;">Cuota ' + cuota.n_cuota + '</span></td>';
                        html += '<td><input type="number" class="form-control form-control-sm cuota-monto" data-concepto-idx="' + idx + '" data-cuota-idx="' + cuotaIdx + '" value="' + cuota.monto_bs + '" min="0" step="1" style="width:105px;font-size:.82rem;border-radius:6px;" ' + disabledAttr + '></td>';
                        html += '<td><input type="date" class="form-control form-control-sm cuota-fecha" data-concepto-idx="' + idx + '" data-cuota-idx="' + cuotaIdx + '" value="' + fechaVenc + '" style="width:152px;font-size:.82rem;border-radius:6px;"></td>';
                        html += '</tr>';
                    });

                    html += '</tbody></table></div>';

                    if (esUnaCuota) {
                        html += '<div style="font-size:.71rem;color:#94a3b8;margin-top:.4rem;display:flex;align-items:center;gap:.3rem;">';
                        html += '<i class="ri-information-line"></i> Monto no modificable para cuota única';
                        html += '</div>';
                    }

                    html += '</div>'; // .ni-concepto-body
                    html += '</div>'; // .ni-concepto-card
                });

                // Barra final: validación + guardar
                html += '<div class="ni-footer-bar">';
                html += '<div id="mensajeValidacion" style="display:inline-block;font-size:.8rem;"></div>';
                html += '<button type="button" class="btn btn-sm" id="btnGuardarCuotas" style="background:linear-gradient(135deg,#3b1900,#7a3b03,#c96004);color:#fff;border:none;border-radius:8px;padding:.4rem 1rem;font-size:.82rem;font-weight:600;box-shadow:0 3px 12px rgba(154,73,4,.3);">';
                html += '<i class="ri-save-line me-1"></i>Guardar Cambios</button>';
                html += '</div>';

                $('#conceptosList').html(html);

                // ── Aplicar día a todas las fechas del concepto ──────────
                $('.btn-aplicar-dia').on('click', function() {
                    const conceptoIdx = $(this).data('concepto-idx');
                    const dia = parseInt($('#dia-venc-' + conceptoIdx).val());
                    if (!dia || dia < 1 || dia > 31) {
                        showToast('warning', 'Ingrese un día válido entre 1 y 31.');
                        return;
                    }
                    let aplicados = 0;
                    $('.cuota-fecha[data-concepto-idx="' + conceptoIdx + '"]').each(function() {
                        const val = $(this).val();
                        if (val) {
                            const parts = val.split('-');
                            if (parts.length === 3) {
                                parts[2] = String(dia).padStart(2, '0');
                                $(this).val(parts.join('-'));
                                aplicados++;
                            }
                        }
                    });
                    if (aplicados > 0) {
                        showToast('success', 'Día ' + dia + ' aplicado a ' + aplicados + ' fecha' + (
                            aplicados !== 1 ? 's' : '') + '.');
                    } else {
                        showToast('warning', 'No hay fechas con valores para modificar.');
                    }
                });

                // ── Aplicar mes inicio al concepto ───────────────────────
                $('.btn-aplicar-mes').on('click', function() {
                    const conceptoIdx = $(this).data('concepto-idx');
                    const nCuotas = parseInt($(this).data('n-cuotas')) || 1;
                    const mesInicio = parseInt($('#mes-inicio-' + conceptoIdx).val());
                    if (!mesInicio) {
                        showToast('warning', 'Seleccione un mes de inicio.');
                        return;
                    }

                    const $fechas = $('.cuota-fecha[data-concepto-idx="' + conceptoIdx + '"]');

                    // Determinar el día: usa el campo "día" del concepto si está completo,
                    // si no toma el día de la primera fecha existente, o 1 como fallback.
                    let dia = 1;
                    const diaInput = parseInt($('#dia-venc-' + conceptoIdx).val());
                    if (diaInput && diaInput >= 1 && diaInput <= 31) {
                        dia = diaInput;
                    } else {
                        const primeraFecha = $fechas.first().val();
                        if (primeraFecha) {
                            const p = primeraFecha.split('-');
                            if (p.length === 3) dia = parseInt(p[2]) || 1;
                        }
                    }

                    // Determinar el año base desde la primera fecha existente
                    let anioBase = new Date().getFullYear();
                    $fechas.each(function() {
                        const v = $(this).val();
                        if (v) {
                            const p = v.split('-');
                            if (p.length === 3) {
                                anioBase = parseInt(p[0]);
                                return false;
                            }
                        }
                    });

                    $fechas.each(function(cuotaIdx) {
                        let mes = mesInicio + cuotaIdx;
                        let anio = anioBase + Math.floor((mes - 1) / 12);
                        mes = ((mes - 1) % 12) + 1;
                        $(this).val(anio + '-' + String(mes).padStart(2, '0') + '-' + String(dia)
                            .padStart(2, '0'));
                    });

                    const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                    ];
                    showToast('success', 'Fechas redistribuidas desde ' + nombresMeses[mesInicio - 1] +
                        ' (' + nCuotas + ' cuota' + (nCuotas !== 1 ? 's' : '') + ').');
                });
                // ─────────────────────────────────────────────────────────

                $('.btn-dividir-cuotas').on('click', function() {
                    const conceptoIdx = $(this).data('concepto-idx');
                    const nCuotas = $(this).data('n-cuotas');
                    const montoPorCuota = parseFloat($('#monto-pagar-' + conceptoIdx).val()) || 0;
                    const totalOriginal = parseFloat($('.badge-total-concepto').eq(conceptoIdx).data(
                        'original')) || 0;

                    if (montoPorCuota <= 0) {
                        showToast('warning', 'Ingrese un monto mayor a 0.');
                        return;
                    }

                    const montoUltimaCuota = totalOriginal - (montoPorCuota * (nCuotas - 1));

                    if (montoUltimaCuota <= 0) {
                        showToast('warning',
                            'El monto por cuota genera una última cuota con valor 0 o negativo. ' +
                            'Total: Bs. ' + totalOriginal.toFixed(2) + ', Cuotas: ' + nCuotas +
                            '. Ingrese un monto menor.');
                        return;
                    }

                    $('.cuota-monto[data-concepto-idx="' + conceptoIdx + '"]').each(function(idx) {
                        const monto = (idx === nCuotas - 1) ? montoUltimaCuota : montoPorCuota;
                        $(this).val(monto);
                    });

                    $('#monto-pagar-' + conceptoIdx).val('');
                    recalcularTotalConcepto(conceptoIdx);
                    showToast('success', 'Monto distribuido en ' + nCuotas + ' cuota' + (nCuotas !== 1 ?
                        's' : '') + ' correctamente.');
                });

                $(document).on('click', '.btn-invertir-cuotas', function() {
                    const conceptoIdx = $(this).data('concepto-idx');
                    const $montos = $('.cuota-monto[data-concepto-idx="' + conceptoIdx + '"]');

                    let valores = [];
                    $montos.each(function() {
                        valores.push($(this).val());
                    });

                    valores.reverse();

                    $montos.each(function(idx) {
                        $(this).val(valores[idx]);
                    });

                    recalcularTotalConcepto(conceptoIdx);
                    showToast('success', 'Orden de cuotas invertido correctamente.');
                });

                $('.cuota-monto').on('change', function() {
                    recalcularTotalConcepto($(this).data('concepto-idx'));
                });

                data.forEach(function(item, idx) {
                    recalcularTotalConcepto(idx);
                });
            }

            function recalcularTotalConcepto(conceptoIdx) {
                let total = 0;
                $('.cuota-monto[data-concepto-idx="' + conceptoIdx + '"]').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });

                const badgeTotal = $('.badge-total-concepto').eq(conceptoIdx);
                const original = parseFloat(badgeTotal.data('original')) || 0;
                const diferencia = total - original;
                const badgeDif = $('.badge-diferencia').eq(conceptoIdx);

                badgeTotal.text('Total: Bs. ' + total.toFixed(2));

                if (diferencia === 0) {
                    badgeDif.html(
                        '<span class="badge" style="background:#16a34a;color:#fff;"><i class="ri-check-line"></i> Correcto</span>'
                    );
                } else if (diferencia > 0) {
                    badgeDif.html(
                        '<span class="badge" style="background: rgba(239, 68, 68, 0.15); color: #dc2626;"><i class="ri-error-warning-line"></i> Exceso: Bs. ' +
                        diferencia.toFixed(2) + '</span>');
                } else {
                    badgeDif.html(
                        '<span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #d97706;"><i class="ri-alert-line"></i> Falta: Bs. ' +
                        Math.abs(diferencia).toFixed(2) + '</span>');
                }

                actualizarEstadoBotonGuardar();
            }

            function actualizarEstadoBotonGuardar() {
                let hayErrores = false;
                $('.badge-total-concepto').each(function() {
                    const original = parseFloat($(this).data('original')) || 0;
                    const actual = parseFloat($(this).text().replace('Total: Bs. ', '')) || 0;
                    if (actual !== original) {
                        hayErrores = true;
                    }
                });

                const btn = $('#btnGuardarCuotas');
                if (hayErrores) {
                    btn.prop('disabled', true);
                    $('#mensajeValidacion').html(
                        '<span class="text-danger"><i class="ri-alert-line"></i> Corrige los montos antes de guardar</span>'
                    );
                } else {
                    btn.prop('disabled', false);
                    $('#mensajeValidacion').html(
                        '<span style="color:#c96004;font-weight:600;"><i class="ri-check-line"></i> Los montos coinciden</span>');
                }
            }

            $(document).on('click', '#btnGuardarCuotas', function() {
                const btn = $(this);
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

                const datosCuotas = [];
                $('.cuota-monto').each(function() {
                    datosCuotas.push({
                        concepto_idx: $(this).data('concepto-idx'),
                        cuota_idx: $(this).data('cuota-idx'),
                        monto: $(this).val(),
                        fecha: $('.cuota-fecha[data-concepto-idx="' + $(this).data(
                            'concepto-idx') + '"][data-cuota-idx="' + $(this).data(
                            'cuota-idx') + '"]').val()
                    });
                });

                $.ajax({
                    url: '/admin/ofertas-academicas/' + currentOfertaId + '/cuotas/actualizar',
                    type: 'POST',
                    data: {
                        cuotas: JSON.stringify(datosCuotas),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        showToast('success', 'Cuotas actualizadas correctamente.');
                        btn.prop('disabled', false).html(
                            '<i class="ri-save-line"></i> Guardar Cambios');
                    },
                    error: function(xhr) {
                        showToast('error', 'Error al guardar: ' + (xhr.responseJSON?.error ||
                            'Error desconocido'));
                        btn.prop('disabled', false).html(
                            '<i class="ri-save-line"></i> Guardar Cambios');
                    }
                });
            });

            // Confirmar inscripción
            $('#btnConfirmarInscripcion').on('click', function() {
                const planPagoId = $('#inscripcionPlanPago').val();
                const estado = $('#inscripcionEstado').val();

                if (estado === 'Inscrito' && !planPagoId) {
                    showToast('error', 'Para inscribir como Inscrito debe seleccionar un plan de pago');
                    return;
                }

                const btn = $(this);
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

                // Recolectar montos y fechas editadas por el usuario
                const cuotasPersonalizadas = [];
                if (estado === 'Inscrito' && planPagoId) {
                    $('.cuota-monto').each(function() {
                        const conceptoIdx = $(this).data('concepto-idx');
                        const cuotaIdx = $(this).data('cuota-idx');
                        const fecha = $('.cuota-fecha[data-concepto-idx="' + conceptoIdx +
                            '"][data-cuota-idx="' + cuotaIdx + '"]').val();
                        cuotasPersonalizadas.push({
                            concepto_idx: parseInt(conceptoIdx),
                            cuota_idx: parseInt(cuotaIdx),
                            monto: parseFloat($(this).val()) || 0,
                            fecha: fecha || null
                        });
                    });
                }

                $.ajax({
                    url: '/admin/ofertas-academicas/' + currentOfertaId + '/inscripciones',
                    type: 'POST',
                    data: {
                        estudiante_id: selectedEstudiante.id,
                        planes_pago_id: planPagoId || null,
                        estado: estado,
                        adelanto_bs: $('#inscripcionAdelanto').val(),
                        observacion: $('#inscripcionObservacion').val(),
                        cuotas_personalizadas: cuotasPersonalizadas.length ?
                            JSON.stringify(cuotasPersonalizadas) :
                            null,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#modalNuevaInscripcion').modal('hide');
                        const msg = estado === 'Inscrito' ?
                            'Inscripción registrada correctamente. Se crearon las cuotas y matriculaciones.' :
                            'Pre-Inscripción registrada correctamente.';
                        showToast('success', msg);

                        btn.prop('disabled', false).html('Registrar Inscripción');

                        if (estado === 'Inscrito' && response.data && response.data.id) {
                            const ins = response.data;
                            const nombreEst = (selectedEstudiante?.persona?.nombres || '') +
                                ' ' +
                                (selectedEstudiante?.persona?.apellido_paterno || '') + ' ' +
                                (selectedEstudiante?.persona?.apellido_materno || '');
                            abrirModalComprobanteInscripcion(ins.id, nombreEst.trim());
                        } else {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Error al registrar inscripción';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.error) {
                                msg = xhr.responseJSON.error;
                            } else if (xhr.responseJSON.errors) {
                                const firstError = Object.values(xhr.responseJSON.errors)[0];
                                msg = firstError[0] || msg;
                            }
                        }
                        showToast('error', msg);
                        console.log('Error response:', xhr.responseJSON);
                        btn.prop('disabled', false).html('Registrar Inscripción');
                    }
                });
            });

            // ── Comprobante de pago post-inscripción ──────────────────────
            let cmpInscripcionId = null;

            function abrirModalComprobanteInscripcion(inscripcionId, nombreEstudiante) {
                cmpInscripcionId = inscripcionId;

                $('#cmpEstudianteNombre').text(nombreEstudiante);
                $('#cmpEstudianteDetalle').text('Inscripción #' + inscripcionId);
                $('#cmpArchivo').val('');
                $('#cmpObservaciones').val('');
                $('#cmpCuotasContainer').hide().html('');
                $('#cmpCuotasLoading').show();

                $('#modalComprobanteInscripcion').modal('show');

                $.ajax({
                    url: '{{ route('admin.ofertas.inscripcion.cuotas', ':id') }}'.replace(':id',
                        inscripcionId),
                    type: 'GET',
                    success: function(data) {
                        $('#cmpCuotasLoading').hide();
                        if (!data.success || !data.grupo) {
                            $('#cmpCuotasContainer').html(
                                '<p class="text-muted" style="font-size:.8rem;">No se encontraron cuotas pendientes.</p>'
                                ).show();
                            return;
                        }
                        const grupo = data.grupo;
                        let html =
                            '<div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.65rem;padding:.45rem .75rem;background:linear-gradient(135deg,rgba(154,73,4,.07),rgba(252,123,4,.03));border-radius:8px;border:1px solid rgba(154,73,4,.15);">' +
                            '<i class="ri-bank-card-line" style="color:#9a4904;font-size:.85rem;"></i>' +
                            '<span style="font-size:.78rem;font-weight:700;color:#9a4904;">' + escapeHtml(grupo.plan_nombre) + '</span>' +
                            '</div>';
                        if (!grupo.cuotas || !grupo.cuotas.length) {
                            html +=
                                '<p class="text-muted" style="font-size:.8rem;">No hay cuotas pendientes.</p>';
                        } else {
                            grupo.cuotas.forEach(function(c) {
                                html +=
                                    '<label style="display:flex;align-items:center;gap:.7rem;padding:.6rem .85rem;background:#fff;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:.4rem;cursor:pointer;transition:border-color .15s,background .15s;" ' +
                                    'onmouseenter="this.style.borderColor=\'rgba(154,73,4,.35)\';this.style.background=\'rgba(154,73,4,.03)\'" ' +
                                    'onmouseleave="this.style.borderColor=\'#e2e8f0\';this.style.background=\'#fff\'">' +
                                    '<input type="checkbox" name="cmp_cuotas[]" value="' + c.id + '" style="width:16px;height:16px;accent-color:#9a4904;flex-shrink:0;cursor:pointer;">' +
                                    '<span style="flex:1;font-size:.83rem;font-weight:500;color:#334155;">' + escapeHtml(c.nombre) + '</span>' +
                                    '<span style="font-weight:700;color:#9a4904;font-size:.85rem;white-space:nowrap;">Bs. ' + c.pago_pendiente_bs + '</span>' +
                                    (c.fecha_vencimiento ?
                                        '<span style="font-size:.7rem;color:#94a3b8;background:#f8fafc;border:1px solid #e2e8f0;padding:.1rem .45rem;border-radius:5px;white-space:nowrap;">' + c.fecha_vencimiento + '</span>' : '') +
                                    '</label>';
                            });
                        }
                        $('#cmpCuotasContainer').html(html).show();
                    },
                    error: function() {
                        $('#cmpCuotasLoading').hide();
                        $('#cmpCuotasContainer').html(
                            '<p class="text-danger" style="font-size:.8rem;">Error al cargar cuotas.</p>'
                            ).show();
                    }
                });
            }

            function escapeHtml(str) {
                return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(
                    /"/g, '&quot;');
            }

            $('#btnEnviarComprobanteInscripcion').on('click', function() {
                if (!cmpInscripcionId) return;

                const archivo = $('#cmpArchivo')[0].files[0];
                if (!archivo) {
                    showToast('error', 'Debes seleccionar un archivo.');
                    return;
                }

                const cuotasChecked = $('input[name="cmp_cuotas[]"]:checked');
                if (!cuotasChecked.length) {
                    showToast('error', 'Selecciona al menos una cuota.');
                    return;
                }

                const formData = new FormData();
                formData.append('inscripcion_id', cmpInscripcionId);
                formData.append('archivo', archivo);
                formData.append('observaciones', $('#cmpObservaciones').val());
                cuotasChecked.each(function() {
                    formData.append('cuotas[]', $(this).val());
                });
                formData.append('_token', '{{ csrf_token() }}');

                const btn = $('#btnEnviarComprobanteInscripcion');
                btn.prop('disabled', true).html('<i class="ri-loader-4-line me-1"></i>Enviando...');

                $.ajax({
                    url: '{{ route('admin.ofertas.inscripcion.comprobante') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        btn.prop('disabled', false).html(
                            '<i class="ri-upload-cloud-line me-1"></i> Enviar Comprobante');
                        if (data.success) {
                            $('#modalComprobanteInscripcion').modal('hide');
                            showToast('success', data.mensaje ||
                                'Comprobante enviado correctamente.');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1800);
                        } else {
                            showToast('error', data.message ||
                                'Error al enviar el comprobante.');
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false).html(
                            '<i class="ri-upload-cloud-line me-1"></i> Enviar Comprobante');
                        showToast('error', 'Error al enviar el comprobante.');
                    }
                });
            });

            $('#modalComprobanteInscripcion').on('hidden.bs.modal', function() {
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            });
        });
    </script>
@endsection
