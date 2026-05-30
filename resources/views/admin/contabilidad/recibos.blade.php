@extends('layouts.master')

@section('title')
    Recibos
@endsection

@section('content')
    <style>
        :root {
            --rc-primary: #fc7b04;
            --rc-primary-dark: #c96004;
            --rc-primary-light: rgba(252, 123, 4, 0.1);
            --rc-surface: #f8fafc;
            --rc-surface-2: #ffffff;
            --rc-border: #e2e8f0;
            --rc-text: #1e293b;
            --rc-text-muted: #64748b;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] {
            --rc-surface: #1e1e2d;
            --rc-surface-2: #212229;
            --rc-border: #2d2d3a;
            --rc-text: #e9ecef;
            --rc-text-muted: #9ca3af;
            --rc-primary-light: rgba(252, 123, 4, 0.15);
        }

        .rc-page {
            animation: rcFadeIn .4s ease-out;
        }

        @keyframes rcFadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .rc-header {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 24px;
            padding: 28px 32px;
            margin-bottom: 24px;
            background: linear-gradient(135deg, var(--rc-primary-dark) 0%, #8b4500 100%);
            border-radius: var(--radius-xl);
            color: #fff;
            box-shadow: 0 8px 32px rgba(252, 123, 4, 0.3);
        }

        .rc-header::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -5%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .rc-header::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -5%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            pointer-events: none;
        }

        .rc-header-content {
            position: relative;
            z-index: 1;
        }

        .rc-header h1 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
        }

        .rc-header h1 i {
            color: #fed7aa;
        }

        .rc-header p {
            margin: 6px 0 0;
            opacity: .9;
            font-size: .9rem;
        }

        .rc-header-meta {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 10px 18px;
            border-radius: var(--radius-md);
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: .85rem;
            font-weight: 500;
        }

        .rc-header-meta i {
            color: #fed7aa;
            margin-right: 6px;
        }

        .rc-filter-bar {
            background: var(--rc-surface-2);
            border: 1px solid var(--rc-border);
            border-radius: var(--radius-lg);
            padding: 16px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            box-shadow: var(--shadow-sm);
        }

        .rc-filter-bar label {
            font-weight: 600;
            font-size: .85rem;
            color: var(--rc-text-muted);
            white-space: nowrap;
        }

        .rc-filter-bar select {
            border-radius: var(--radius-sm);
            border: 1px solid var(--rc-border);
            padding: 10px 14px;
            font-size: .875rem;
            min-width: 160px;
            background: var(--rc-surface);
            color: var(--rc-text);
            cursor: pointer;
            transition: all 0.2s;
        }

        .rc-filter-bar select:hover {
            border-color: var(--rc-primary);
        }

        .rc-filter-bar select:focus {
            outline: none;
            border-color: var(--rc-primary);
            box-shadow: 0 0 0 3px var(--rc-primary-light);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--rc-surface-2);
            border: 1px solid var(--rc-border);
            border-radius: var(--radius-lg);
            padding: 20px 16px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--stat-color), transparent);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: var(--stat-color);
        }

        .stat-card.matricula {
            --stat-color: #3b82f6;
        }

        .stat-card.colegiatura {
            --stat-color: #10b981;
        }

        .stat-card.certificacion {
            --stat-color: #8b5cf6;
        }

        .stat-card.total {
            --stat-color: var(--rc-primary);
            background: var(--rc-primary-light);
            border-color: var(--rc-primary);
        }

        .stat-card.efectivo {
            --stat-color: #0ea5e9;
        }

        .stat-card.qr {
            --stat-color: #14b8a6;
        }

        .stat-card.transferencia {
            --stat-color: #6366f1;
        }

        .stat-card.metodos {
            grid-column: span 3;
            display: flex;
            gap: 24px;
            justify-content: center;
            align-items: center;
            padding: 16px 24px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .stat-card.metodos .metodo-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            flex: 1;
        }

        .stat-card.metodos .stat-label {
            font-size: .7rem;
            margin-bottom: 4px;
        }

        .stat-card.metodos .stat-value {
            font-size: 1.1rem;
        }

        .stat-card.metodos .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 12px;
            display: block;
        }

        .stat-card.matricula .stat-icon {
            color: #3b82f6;
        }

        .stat-card.colegiatura .stat-icon {
            color: #10b981;
        }

        .stat-card.certificacion .stat-icon {
            color: #8b5cf6;
        }

        .stat-card.total .stat-icon {
            color: var(--rc-primary-dark);
        }

        .stat-card.efectivo .stat-icon {
            color: #0ea5e9;
        }

        .stat-card.qr .stat-icon {
            color: #14b8a6;
        }

        .stat-card.transferencia .stat-icon {
            color: #6366f1;
        }

        .stat-label {
            font-size: .75rem;
            font-weight: 600;
            color: var(--rc-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--rc-text);
        }

        .stat-card.total .stat-value {
            color: var(--rc-primary-dark);
        }

        .rc-panel {
            background: var(--rc-surface-2);
            border: 1px solid var(--rc-border);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .rc-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--rc-border);
            background: linear-gradient(180deg, var(--rc-surface) 0%, var(--rc-surface-2) 100%);
        }

        .rc-panel-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--rc-text);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rc-panel-title i {
            color: var(--rc-primary);
        }

        .rc-panel-badge {
            background: var(--rc-primary-light);
            color: var(--rc-primary-dark);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: .8rem;
            font-weight: 600;
        }

        .rc-table-wrapper {
            overflow-x: auto;
        }

        .recibos-card {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: visible;
            box-shadow: 0 4px 24px -4px rgba(0, 0, 0, 0.08);
        }

        .recibos-table {
            width: 100%;
            border-collapse: collapse;
        }

        .recibos-table thead th {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 14px 16px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .recibos-table thead th:first-child { width: 50px; text-align: center; }
        .recibos-table thead th:last-child { width: 100px; text-align: center; }

        .recibos-table tbody tr {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .recibos-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(252, 123, 4, 0.04) 0%, rgba(252, 123, 4, 0.02) 100%);
        }

        .recibos-table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.85rem;
        }

        .recibos-table tbody tr:last-child td {
            border-bottom: none;
        }

        .recibo-num-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #fc7b04, #c96004);
            color: white;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.75rem;
        }

        .recibo-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .est-link {
            color: #1e293b;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .est-link:hover {
            color: #fc7b04;
            text-decoration: underline;
        }

        .recibo-oferta {
            font-size: 0.75rem;
            color: #64748b;
        }

        .cobrador-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .cobrador-nombre {
            font-weight: 500;
            color: #334155;
        }

        .cobrador-cargo {
            font-size: 0.7rem;
            color: #94a3b8;
        }

        .recibo-monto {
            font-weight: 700;
            font-size: 0.9rem;
            color: #15803d;
        }

        .recibo-descuento {
            font-weight: 500;
            font-size: 0.8rem;
            color: #dc2626;
        }

        .recibo-fecha {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .recibo-fecha .dia {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.9rem;
        }

        .recibo-fecha .mes {
            font-size: 0.7rem;
            color: #64748b;
        }

        .ver-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #64748b;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .ver-btn:hover {
            background: linear-gradient(135deg, #fc7b04, #c96004);
            color: white;
            border-color: #fc7b04;
            transform: scale(1.02);
        }

        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 16px 24px;
            border-top: 1px solid var(--rc-border);
            background: var(--rc-surface);
        }

        .pagination-info {
            font-size: .85rem;
            color: var(--rc-text-muted);
        }

        .pagination {
            margin: 0;
            gap: 4px;
        }

        .pagination .page-link {
            border-radius: 6px !important;
            border: 1px solid var(--rc-border);
            color: var(--rc-text);
            padding: 8px 12px;
            background: var(--rc-surface-2);
            font-size: .85rem;
        }

        .pagination .page-item.active .page-link {
            background: var(--rc-primary);
            border-color: var(--rc-primary);
            color: #fff;
        }

        .pagination .page-item.disabled .page-link {
            background: var(--rc-surface);
            color: var(--rc-text-muted);
            opacity: 0.6;
        }

        .pagination .page-link:hover {
            background: var(--rc-primary-light);
            border-color: var(--rc-primary);
            color: var(--rc-primary-dark);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--rc-border);
        }

        .empty-state p {
            color: var(--rc-text-muted);
            margin-top: 12px;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .rc-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .rc-filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .rc-filter-bar select {
                width: 100%;
            }
        }
    </style>


    <?php
    $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    ?>

    <div class="rc-page">
        <div class="rc-header">
            <div class="rc-header-content">
                <h1><i class="ri-receipt-line"></i> Recibos</h1>
                <p>Listado de todos los pagos realizados</p>
            </div>
            <div class="rc-header-meta">
                <i class="ri-calendar-check-line"></i>
                {{ $meses[$mes] }} {{ $gestion }}
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card matricula">
                <div class="stat-icon"><i class="ri-file-paper-line"></i></div>
                <div class="stat-label">Matrícula</div>
                <div class="stat-value">Bs. {{ number_format($stats['matricula'], 2) }}</div>
            </div>
            <div class="stat-card colegiatura">
                <div class="stat-icon"><i class="ri-school-line"></i></div>
                <div class="stat-label">Colegiatura</div>
                <div class="stat-value">Bs. {{ number_format($stats['colegiatura'], 2) }}</div>
            </div>
            <div class="stat-card certificacion">
                <div class="stat-icon"><i class="ri-award-line"></i></div>
                <div class="stat-label">Certificación</div>
                <div class="stat-value">Bs. {{ number_format($stats['certificacion'], 2) }}</div>
            </div>
            <div class="stat-card total">
                <div class="stat-icon"><i class="ri-money-dollar-circle-line"></i></div>
                <div class="stat-label">Total Ingresos</div>
                <div class="stat-value">Bs. {{ number_format($stats['total'], 2) }}</div>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card efectivo">
                <div class="stat-icon"><i class="ri-cash-line"></i></div>
                <div class="stat-label">Efectivo</div>
                <div class="stat-value">Bs. {{ number_format($stats['efectivo'], 2) }}</div>
            </div>
            <div class="stat-card qr">
                <div class="stat-icon"><i class="ri-qr-code-line"></i></div>
                <div class="stat-label">QR</div>
                <div class="stat-value">Bs. {{ number_format($stats['qr'], 2) }}</div>
            </div>
            <div class="stat-card transferencia">
                <div class="stat-icon"><i class="ri-bank-line"></i></div>
                <div class="stat-label">Transferencia</div>
                <div class="stat-value">Bs. {{ number_format($stats['transferencia'] ?? 0, 2) }}</div>
            </div>
            <div class="stat-card total">
                <div class="stat-icon"><i class="ri-check-double-line"></i></div>
                <div class="stat-label">Total Medios</div>
                <div class="stat-value">Bs. {{ number_format(($stats['efectivo'] + $stats['qr'] + ($stats['transferencia'] ?? 0)), 2) }}</div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.contabilidad.recibos') }}" class="rc-filter-bar">
            <label>Gestión:</label>
            <select name="gestion" onchange="this.form.submit()">
                @foreach ($gestiones as $g)
                    <option value="{{ $g }}" {{ $g == $gestion ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
            </select>
            <label>Mes:</label>
            <select name="mes" onchange="this.form.submit()">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $i == $mes ? 'selected' : '' }}>{{ $meses[$i] }}</option>
                @endfor
            </select>
        </form>

        <div class="rc-panel">
            <div class="rc-panel-header">
                <h5 class="rc-panel-title">
                    <i class="ri-file-list-3-line"></i>
                    Recibos del período
                </h5>
                <span class="rc-panel-badge">{{ $pagos->total() }} registros</span>
            </div>

            @if ($pagos->isEmpty())
                <div class="empty-state">
                    <i class="ri-inbox-line"></i>
                    <p>No hay recibos en el período seleccionado</p>
                </div>
            @else
                <div class="recibos-card">
                    <table class="recibos-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Recibo</th>
                                <th>Fecha</th>
                                <th>Cobrador</th>
                                <th>Estudiante</th>
                                <th>Oferta</th>
                                <th class="text-end">Monto</th>
                                <th class="text-end">Descuento</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $contador = ($pagos->currentPage() - 1) * $pagos->perPage() + 1;
                            @endphp
                            @foreach ($pagos as $pago)
                                @php
                                    // Cobrador: trabajadores_cargos -> trabajadores -> personas
                                    $cobradorNombre = '-';
                                    $cobradorCargo = '';
                                    $tc = $pago->trabajadorCargo;
                                    if ($tc) {
                                        $trab = $tc->trabajador;
                                        if ($trab && $trab->persona) {
                                            $cobradorNombre = trim(
                                                ($trab->persona->nombres ?? '') .
                                                    ' ' .
                                                    ($trab->persona->apellido_paterno ?? '') .
                                                    ' ' .
                                                    ($trab->persona->apellido_materno ?? ''),
                                            );
                                            $cobradorNombre = $cobradorNombre ?: '-';
                                        }
                                        if ($tc->cargo) {
                                            $cobradorCargo = $tc->cargo->nombre ?? '';
                                        } elseif ($tc->nombre_cargo) {
                                            $cobradorCargo = $tc->nombre_cargo;
                                        }
                                    }

                                    // Estudiante: pagos_cuotas -> cuotas -> inscripciones -> estudiantes -> personas
                                    $estudianteNombre = '-';
                                    $estudianteId = null;
                                    $ofertaNombre = '-';
                                    foreach ($pago->pagosCuotas as $pc) {
                                        $cuota = $pc->cuota;
                                        if ($cuota) {
                                            $inscripcion = $cuota->inscripcion;
                                            if ($inscripcion) {
                                                $est = $inscripcion->estudiante;
                                                $estudianteId = $est ? $est->id : null;
                                                if ($est && $est->persona) {
                                                    $estudianteNombre = trim(
                                                        ($est->persona->nombres ?? '') .
                                                            ' ' .
                                                            ($est->persona->apellido_paterno ?? '') .
                                                            ' ' .
                                                            ($est->persona->apellido_materno ?? ''),
                                                    );
                                                    $estudianteNombre = $estudianteNombre ?: '-';
                                                }
                                                if (
                                                    $inscripcion->ofertaAcademica &&
                                                    $inscripcion->ofertaAcademica->programa
                                                ) {
                                                    $ofertaNombre =
                                                        $inscripcion->ofertaAcademica->programa->nombre ?? '-';
                                                } elseif ($inscripcion->ofertaAcademica) {
                                                    $ofertaNombre = $inscripcion->ofertaAcademica->nombre ?? '-';
                                                }
                                                break;
                                            }
                                        }
                                    }

                                    $fechaFormateada = \Carbon\Carbon::parse($pago->fecha_pago)->format('d');
                                    $fechaFormateada .=
                                        ' de ' . $meses[\Carbon\Carbon::parse($pago->fecha_pago)->format('n')];
                                    $fechaFormateada .= ' del ' . \Carbon\Carbon::parse($pago->fecha_pago)->format('Y');
                                @endphp
                                <tr>
                                    <td>
                                        <div class="recibo-num-badge">{{ $contador++ }}</div>
                                    </td>
                                    <td>
                                        <div class="recibo-info">
                                            <span class="recibo-estudiante">{{ $pago->recibo }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="recibo-fecha">
                                            <span class="dia">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d') }}</span>
                                            <span class="mes">{{ $meses[\Carbon\Carbon::parse($pago->fecha_pago)->format('n')] }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="cobrador-info">
                                            <span class="cobrador-nombre">{{ $cobradorNombre }}</span>
                                            @if($cobradorCargo)
                                                <span class="cobrador-cargo">{{ $cobradorCargo }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($estudianteId)
                                            <a href="{{ route('admin.estudiantes.verDetalle', $estudianteId) }}" class="est-link" title="Ver detalle del estudiante">
                                                {{ $estudianteNombre }}
                                            </a>
                                        @else
                                            <span class="recibo-estudiante">{{ $estudianteNombre }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="recibo-oferta">{{ $ofertaNombre }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="recibo-monto">Bs. {{ number_format($pago->monto_total, 2) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="recibo-descuento">Bs. {{ number_format($pago->descuento_bs ?? 0, 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="ver-btn" data-bs-toggle="modal"
                                            data-bs-target="#modalDetalle{{ $pago->id }}">
                                            <i class="ri-eye-line"></i> Ver
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    <span class="pagination-info">Mostrando {{ $pagos->firstItem() }}-{{ $pagos->lastItem() }} de
                        {{ $pagos->total() }}</span>
                    {{ $pagos->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    @foreach ($pagos as $pago)
        @php
            $cobradorNombre = '-';
            $tc = $pago->trabajadorCargo;
            if ($tc && $tc->trabajador && $tc->trabajador->persona) {
                $cobradorNombre = trim(
                    ($tc->trabajador->persona->nombres ?? '') .
                        ' ' .
                        ($tc->trabajador->persona->apellido_paterno ?? '') .
                        ' ' .
                        ($tc->trabajador->persona->apellido_materno ?? ''),
                );
                $cobradorNombre = $cobradorNombre ?: '-';
            }

            $estudianteNombre = '-';
            $ofertaNombre = '-';
            $cuotasPagadas = [];
            $totalMontoCuotas = 0;
            foreach ($pago->pagosCuotas as $pc) {
                if ($pc->cuota && $pc->cuota->inscripcion) {
                    $inscripcion = $pc->cuota->inscripcion;
                    if ($inscripcion->estudiante && $inscripcion->estudiante->persona) {
                        $estudianteNombre = trim(
                            ($inscripcion->estudiante->persona->nombres ?? '') .
                                ' ' .
                                ($inscripcion->estudiante->persona->apellido_paterno ?? '') .
                                ' ' .
                                ($inscripcion->estudiante->persona->apellido_materno ?? ''),
                        );
                        $estudianteNombre = $estudianteNombre ?: '-';
                    }
                    if ($inscripcion->ofertaAcademica && $inscripcion->ofertaAcademica->programa) {
                        $ofertaNombre = $inscripcion->ofertaAcademica->programa->nombre ?? '-';
                    } elseif ($inscripcion->ofertaAcademica) {
                        $ofertaNombre = $inscripcion->ofertaAcademica->nombre ?? '-';
                    }
                    $cuotasPagadas[] = [
                        'nombre' => $pc->cuota->nombre ?? 'Cuota ' . $pc->cuota->n_cuota,
                        'monto' => $pc->monto_bs,
                    ];
                    $totalMontoCuotas += $pc->monto_bs;
                }
            }

            $detallesColeccion = collect($pago->detalles);
            $totalEfectivo = $detallesColeccion->where('tipo_pago', 'Efectivo')->sum('monto_bs');
            $totalQr       = $detallesColeccion->where('tipo_pago', 'Qr')->sum('monto_bs');
            $totalTransferencia = $detallesColeccion->where('tipo_pago', 'Transferencia')->sum('monto_bs');
            $totalDeposito = $detallesColeccion->where('tipo_pago', 'Depósito')->sum('monto_bs');
            $totalCheque   = $detallesColeccion->where('tipo_pago', 'Cheque')->sum('monto_bs');
            $tipoPago      = $pago->tipo_pago;
        @endphp
        <div class="modal fade" id="modalDetalle{{ $pago->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
                    <div class="modal-header" style="background:linear-gradient(135deg,#059669,#10b981);color:white;padding:1.25rem 1.5rem;border:none;">
                        <h5 class="modal-title" style="font-weight:600;font-size:1.1rem;">
                            <i class="ri-receipt-line me-2"></i>Detalle del Recibo <span style="opacity:0.9;">{{ $pago->recibo }}</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding:1.5rem;background:#f8fafc;">
                        <div class="row mb-4" style="background:white;padding:1rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                            <div class="col-md-6">
                                <p class="mb-2" style="color:#64748b;font-size:.85rem;"><i class="ri-calendar-line me-1"></i><strong>Fecha:</strong></p>
                                <p class="mb-2" style="font-weight:600;color:#1e293b;">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</p>
                                <p class="mb-0" style="color:#64748b;font-size:.85rem;"><i class="ri-user-line me-1"></i><strong>Cobrador:</strong></p>
                                <p class="mb-0" style="font-weight:600;color:#1e293b;">{{ $cobradorNombre }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2" style="color:#64748b;font-size:.85rem;"><i class="ri-user-smile-line me-1"></i><strong>Estudiante:</strong></p>
                                <p class="mb-2" style="font-weight:600;color:#1e293b;">{{ $estudianteNombre }}</p>
                                <p class="mb-0" style="color:#64748b;font-size:.85rem;"><i class="ri-graduation-cap-line me-1"></i><strong>Oferta:</strong></p>
                                <p class="mb-0" style="font-weight:600;color:#1e293b;font-size:.9rem;">{{ $ofertaNombre }}</p>
                            </div>
                        </div>

                        <h6 style="color:#475569;font-weight:600;margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:2px solid #e2e8f0;">
                            <i class="ri-payment-line me-2"></i>Método de Pago
                        </h6>

                        <div class="row justify-content-center g-3 mb-4">
                            @if ($tipoPago === 'Efectivo')
                                <div class="col-md-5">
                                    <div class="card" style="border:none;border-radius:12px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);box-shadow:0 4px 12px rgba(37,99,235,0.15);">
                                        <div class="card-body text-center py-3">
                                            <i class="ri-cash-line fs-2" style="color:#2563eb;"></i>
                                            <p class="mb-1 mt-2" style="color:#1e40af;font-weight:600;">Efectivo</p>
                                            <h4 class="mb-0" style="color:#1e293b;font-weight:700;">Bs. {{ number_format($totalEfectivo, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($tipoPago === 'Qr')
                                <div class="col-md-5">
                                    <div class="card" style="border:none;border-radius:12px;background:linear-gradient(135deg,#d1fae5,#a7f3d0);box-shadow:0 4px 12px rgba(16,185,129,0.15);">
                                        <div class="card-body text-center py-3">
                                            <i class="ri-qr-code-line fs-2" style="color:#059669;"></i>
                                            <p class="mb-1 mt-2" style="color:#065f46;font-weight:600;">QR</p>
                                            <h4 class="mb-0" style="color:#1e293b;font-weight:700;">Bs. {{ number_format($totalQr, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($tipoPago === 'Transferencia')
                                <div class="col-md-5">
                                    <div class="card" style="border:none;border-radius:12px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);box-shadow:0 4px 12px rgba(99,102,241,0.15);">
                                        <div class="card-body text-center py-3">
                                            <i class="ri-bank-line fs-2" style="color:#4f46e5;"></i>
                                            <p class="mb-1 mt-2" style="color:#4338ca;font-weight:600;">Transferencia</p>
                                            <h4 class="mb-0" style="color:#1e293b;font-weight:700;">Bs. {{ number_format($totalTransferencia, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($tipoPago === 'Parcial')
                                <div class="col-md-5">
                                    <div class="card" style="border:none;border-radius:12px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);box-shadow:0 4px 12px rgba(37,99,235,0.15);">
                                        <div class="card-body text-center py-3">
                                            <i class="ri-cash-line fs-2" style="color:#2563eb;"></i>
                                            <p class="mb-1 mt-2" style="color:#1e40af;font-weight:600;">Efectivo</p>
                                            <h4 class="mb-0" style="color:#1e293b;font-weight:700;">Bs. {{ number_format($totalEfectivo, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="card" style="border:none;border-radius:12px;background:linear-gradient(135deg,#d1fae5,#a7f3d0);box-shadow:0 4px 12px rgba(16,185,129,0.15);">
                                        <div class="card-body text-center py-3">
                                            <i class="ri-qr-code-line fs-2" style="color:#059669;"></i>
                                            <p class="mb-1 mt-2" style="color:#065f46;font-weight:600;">QR</p>
                                            <h4 class="mb-0" style="color:#1e293b;font-weight:700;">Bs. {{ number_format($totalQr, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div style="background:white;padding:1.25rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span style="color:#64748b;font-size:.9rem;"><strong>Monto Total:</strong></span>
                                <span style="color:#1e293b;font-weight:700;font-size:1.1rem;">Bs. {{ number_format($pago->monto_total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <span style="color:#64748b;font-size:.9rem;"><strong>Descuento:</strong></span>
                                <span style="color:#dc2626;font-weight:600;">- Bs. {{ number_format($pago->descuento_bs ?? 0, 2) }}</span>
                            </div>
                        </div>

                        @if (count($cuotasPagadas) > 0)
                            <div class="mt-3">
                                <h6 class="border-bottom pb-2 mb-3">Cuotas Pagadas</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Cuota</th>
                                                <th class="text-end">Monto Pagado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cuotasPagadas as $cuota)
                                                <tr>
                                                    <td><i
                                                            class="ri-check-line text-success me-2"></i>{{ $cuota['nombre'] }}
                                                    </td>
                                                    <td class="text-end">Bs. {{ number_format($cuota['monto'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                            <tr class="table-success">
                                                <td><strong>Total</strong></td>
                                                <td class="text-end"><strong>Bs.
                                                        {{ number_format($totalMontoCuotas, 2) }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer" style="background:#f8fafc;border:none;padding:1rem 1.5rem;">
                        <a href="{{ route('admin.estudiantes.generarReciboPdf', ['pagoId' => $pago->id]) }}"
                            target="_blank" class="btn" style="background:linear-gradient(135deg,#059669,#10b981);color:white;border:none;padding:0.6rem 1.25rem;border-radius:8px;font-weight:500;">
                            <i class="ri-download-line me-1"></i> Descargar Recibo
                        </a>
                        <button type="button" class="btn" style="background:#e2e8f0;color:#475569;border:none;padding:0.6rem 1.25rem;border-radius:8px;font-weight:500;" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
