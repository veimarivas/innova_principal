@extends('admin.dashboard')
@section('admin')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap');

        :root {
            --ofertas-primary: #0f766e;
            --ofertas-primary-light: #f0fdfa;
            --ofertas-primary-dark: #0d5f59;
            --ofertas-accent: #f59e0b;
            --ofertas-accent-light: #fffbeb;
            --ofertas-surface: #f8fafc;
            --ofertas-border: #e2e8f0;
            --ofertas-text: #1e293b;
            --ofertas-text-muted: #64748b;
            --ofertas-success: #10b981;
            --ofertas-danger: #ef4444;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }

        .ofertas-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--ofertas-text);
            animation: fadeInUp 0.5s ease-out;
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

        /* Page Header */
        .ofertas-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
            padding: 22px 26px;
            background: linear-gradient(135deg, var(--ofertas-primary) 0%, var(--ofertas-primary-dark) 100%);
            border-radius: var(--radius-lg);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .ofertas-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .ofertas-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.55rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
            color: white;
        }

        .ofertas-header h1 i {
            color: white;
        }

        .ofertas-header p {
            margin: 4px 0 0;
            opacity: 0.85;
            font-size: 0.88rem;
            position: relative;
            z-index: 1;
            color: white;
        }

        /* Filter Bar */
        .filter-bar {
            background: white;
            border-radius: var(--radius-md);
            padding: 14px 18px;
            margin-bottom: 18px;
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: wrap;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--ofertas-border);
        }

        .filter-group {
            flex: 1;
            min-width: 130px;
        }

        .filter-group label {
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--ofertas-text-muted);
            display: block;
            margin-bottom: 4px;
        }

        .filter-group select {
            width: 100%;
            padding: 7px 10px;
            border: 1px solid var(--ofertas-border);
            border-radius: var(--radius-sm);
            font-size: 0.82rem;
            font-family: 'DM Sans', sans-serif;
            background: var(--ofertas-surface);
            transition: all 0.2s ease;
        }

        .filter-group select:focus {
            outline: none;
            border-color: var(--ofertas-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
            background: white;
        }

        .btn-clear-filters {
            padding: 7px 16px;
            border: 1px solid var(--ofertas-border);
            border-radius: var(--radius-sm);
            background: white;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--ofertas-text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-clear-filters:hover {
            background: var(--ofertas-surface);
            color: var(--ofertas-text);
            border-color: var(--ofertas-primary);
        }

        .btn-nueva-persona:hover:not(:disabled) {
            background: var(--ofertas-primary-dark) !important;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--ofertas-border);
            overflow: hidden;
        }

        .table-card-header {
            padding: 16px 22px;
            border-bottom: 1px dashed var(--ofertas-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-card-header h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            margin: 0;
            font-size: 1rem;
        }

        .table-responsive {
            overflow-x: visible !important;
        }

        .ofertas-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .ofertas-table thead th {
            background: var(--ofertas-surface);
            padding: 10px 14px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--ofertas-text-muted);
            border-bottom: 1px solid var(--ofertas-border);
            white-space: normal;
            vertical-align: middle;
        }

        .ofertas-table tbody tr {
            transition: background 0.15s ease;
        }

        .ofertas-table tbody tr:hover {
            background: var(--ofertas-primary-light);
        }

        .ofertas-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--ofertas-border);
            vertical-align: middle;
            white-space: normal;
            font-size: 0.85rem;
        }

        .ofertas-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Oferta Name Cell */
        .oferta-name-cell {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .oferta-avatar {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: var(--ofertas-primary-light);
            color: var(--ofertas-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .oferta-name-text h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--ofertas-text);
            line-height: 1.3;
        }

        .oferta-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 4px;
            flex-wrap: wrap;
        }

        .oferta-code-badge {
            background: #1e293b;
            color: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-family: monospace;
        }

        .oferta-sucursal {
            color: var(--ofertas-text-muted);
            font-size: 0.72rem;
        }

        .oferta-gestion {
            color: var(--ofertas-text-muted);
            font-size: 0.72rem;
            margin-top: 2px;
        }

        /* Convenio Cell */
        .convenio-cell {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .convenio-img-small {
            width: 28px;
            height: 28px;
            border-radius: 5px;
            object-fit: cover;
            border: 1px solid var(--ofertas-border);
            flex-shrink: 0;
        }

        .convenio-placeholder {
            width: 28px;
            height: 28px;
            border-radius: 5px;
            background: var(--ofertas-surface);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid var(--ofertas-border);
        }

        .convenio-placeholder i {
            font-size: 0.7rem;
            color: var(--ofertas-text-muted);
        }

        .convenio-name-text {
            min-width: 0;
        }

        .convenio-sigla {
            font-weight: 600;
            font-size: 0.72rem;
            color: var(--ofertas-text);
        }

        .convenio-full-name {
            color: var(--ofertas-text-muted);
            font-size: 0.65rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 60px;
        }

        /* Badges */
        .badge-modulos {
            background: #dbeafe;
            color: #2563eb;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-modalidad {
            background: #eef2ff;
            color: #4f46e5;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.68rem;
            font-weight: 500;
        }

        .badge-fase {
            color: #fff;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.72rem;
            font-weight: 500;
        }

        .badge-inscritos {
            background: var(--ofertas-success);
            color: #fff;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .badge-preinscritos {
            margin-top: 2px;
        }

        .badge-pre-count {
            background: #fff7ed;
            color: #ea580c;
            padding: 2px 7px;
            border-radius: 8px;
            font-size: 0.65rem;
            font-weight: 600;
            border: 1px solid #fed7aa;
        }

        .fecha-inicio {
            color: #059669;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .fecha-fin {
            color: #94a3b8;
            font-size: 0.68rem;
        }

        .badge-inscripcion {
            background: #cffafe;
            color: #0891b2;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 0.62rem;
            margin-top: 3px;
            display: inline-block;
        }

        /* Action Buttons */
        .action-btn {
            width: 28px;
            height: 28px;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .action-btn.edit {
            background: var(--ofertas-accent-light);
            color: #d97706;
            border-color: #fde68a;
        }

        .action-btn.edit:hover {
            background: #fde68a;
        }

        .action-btn.plan-pago {
            background: #f0fdf4;
            color: #16a34a;
            border-color: #bbf7d0;
        }

        .action-btn.plan-pago:hover {
            background: #bbf7d0;
        }

        .action-btn.inscribir {
            background: #eff6ff;
            color: #2563eb;
            border-color: #bfdbfe;
        }

        .action-btn.inscribir:hover {
            background: #bfdbfe;
        }

        .action-btn.fase {
            background: #faf5ff;
            color: #9333ea;
            border-color: #e9d5ff;
        }

        .action-btn.fase:hover {
            background: #e9d5ff;
        }

        .actions-cell {
            display: flex;
            gap: 4px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Table Footer */
        .table-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--ofertas-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            background: var(--ofertas-surface);
        }

        .table-footer .results-count {
            font-size: 0.82rem;
            color: var(--ofertas-text-muted);
        }

        .pagination .page-link {
            border-radius: var(--radius-sm) !important;
            border: 1px solid var(--ofertas-border);
            color: var(--ofertas-text-muted);
            font-size: 0.82rem;
            padding: 5px 10px;
            margin: 0 2px;
        }

        .pagination .page-item.active .page-link {
            background: var(--ofertas-primary);
            border-color: var(--ofertas-primary);
            color: white;
        }

        .pagination .page-link:hover {
            background: var(--ofertas-primary-light);
            border-color: var(--ofertas-primary);
            color: var(--ofertas-primary);
        }

        /* Empty State */
        .empty-state {
            padding: 48px 24px;
            text-align: center;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .empty-state p {
            color: var(--ofertas-text-muted);
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 767.98px) {
            .ofertas-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                min-width: 100%;
            }

            .table-footer {
                flex-direction: column;
                align-items: center;
            }
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid var(--ofertas-border);
            padding: 16px 24px;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            border-top: 1px solid var(--ofertas-border);
            padding: 16px 24px;
        }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999999 !important;
        }

        .toast {
            min-width: 300px;
            max-width: 350px;
            border-radius: var(--radius-md);
            overflow: hidden;
            margin-bottom: 10px;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .btn .spin {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Existing styles for modals and functionality */
        .plan-card {
            border-left: 4px solid var(--bs-primary);
            transition: transform 0.2s ease;
        }

        .plan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .concepto-item {
            border-bottom: 1px solid #f0f0f0;
            padding: 0.75rem 0;
        }

        .concepto-item:last-child {
            border-bottom: none;
        }

        .cuota-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .monto-total {
            font-weight: 600;
            color: var(--bs-success);
        }

        .plan-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
        }

        .accordion-button:not(.collapsed) {
            background-color: rgba(var(--bs-primary-rgb), 0.1);
            color: var(--bs-primary);
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
        }

        .table-group-divider {
            border-top-color: var(--bs-primary);
        }

        .form-select {
            background-color: #fff;
            border: 1px solid #ced4da;
            transition: border-color 0.15s ease-in-out;
        }

        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-group .btn:hover {
            z-index: 2;
            transform: none;
        }

        .input-group-sm .input-group-text {
            font-size: 0.875rem;
        }

        .concepto-select-editar,
        .n-cuotas-editar,
        .monto-cuota-editar {
            min-width: 100px;
        }

        .eliminarConceptoBtn:hover {
            background-color: #dc3545;
            color: white;
        }

        .agregarConceptoPlanBtn:hover {
            background-color: #198754;
            color: white;
        }

        .loading {
            opacity: 0.7;
            pointer-events: none;
            position: relative;
        }

        .loading::after {
            content: 'Cargando...';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
    </style>

    <div class="container-fluid ofertas-page">
        <!-- Page Header -->
        <div class="ofertas-header">
            <div>
                <h1><i class="ri-graduation-cap-line me-2"></i>Gestión de Ofertas Académicas</h1>
                <p>Administra las ofertas de posgrado disponibles</p>
            </div>
        </div>

        <!-- Filters -->
        @include('admin.ofertas.partials.filtros')

        <!-- Table -->
        @include('admin.ofertas.partials.tabla-resultados')
    </div>

    <!-- Modales -->
    @include('admin.ofertas.modals.editar-oferta')
    @include('admin.ofertas.modals.editar-fase2')
    @include('admin.ofertas.modals.agregar-plan-pago')
    @include('admin.ofertas.modals.inscribir-estudiante')
    @include('admin.ofertas.modals.ver-planes-pago')
    @include('admin.ofertas.modals.editar-planes-pago')
@endsection

@push('script')
    @include('admin.ofertas.partials.scripts')
@endpush
