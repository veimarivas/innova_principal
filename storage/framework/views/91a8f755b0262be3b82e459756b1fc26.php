<?php $__env->startSection('title'); ?>
    Recibos
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        :root {
            --rc-primary: #fc7b04;
            --rc-primary-dark: #c96004;
            --rc-primary-light: rgba(252, 123, 4, 0.1);
            --rc-primary-subtle: rgba(252, 123, 4, 0.06);
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
            --shadow-lg: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .rc-page {
            animation: rcFadeIn .5s ease-out;
        }

        @keyframes rcFadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: none; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.04); } }

        .rc-header {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 24px;
            padding: 32px 36px;
            margin-bottom: 28px;
            background: linear-gradient(135deg, #8b4500 0%, #c96004 40%, #fc7b04 100%);
            border-radius: var(--radius-xl);
            color: #fff;
            box-shadow: 0 8px 32px rgba(252, 123, 4, 0.3);
        }
        .rc-header::before {
            content: ''; position: absolute; top: -40%; right: -8%; width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .rc-header::after {
            content: ''; position: absolute; bottom: -30%; left: -5%; width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .rc-header-content { position: relative; z-index: 1; }
        .rc-header h1 {
            margin: 0; font-size: 1.65rem; font-weight: 700;
            display: flex; align-items: center; gap: 14px; color: #fff;
            letter-spacing: -.02em;
        }
        .rc-header h1 i { color: #fed7aa; font-size: 1.5rem; }
        .rc-header p { margin: 6px 0 0; opacity: .9; font-size: .9rem; }
        .rc-header-meta {
            position: relative; z-index: 1;
            background: rgba(255,255,255,.15); backdrop-filter: blur(12px);
            padding: 10px 20px; border-radius: var(--radius-md);
            border: 1px solid rgba(255,255,255,.2);
            font-size: .88rem; font-weight: 500;
        }
        .rc-header-meta i { color: #fed7aa; margin-right: 6px; }

        .rc-filter-bar {
            background: var(--rc-surface-2); border: 1px solid var(--rc-border);
            border-radius: var(--radius-lg); padding: 18px 24px; margin-bottom: 28px;
            display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
            box-shadow: var(--shadow-sm);
        }
        .rc-filter-bar label { font-weight: 600; font-size: .82rem; color: var(--rc-text-muted); white-space: nowrap; }
        .rc-filter-bar select {
            border-radius: var(--radius-sm); border: 1px solid var(--rc-border);
            padding: 10px 14px; font-size: .875rem; min-width: 170px;
            background: var(--rc-surface); color: var(--rc-text); cursor: pointer;
            transition: all .2s; appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='16' height='16' fill='%2364748b'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px;
        }
        .rc-filter-bar select:hover { border-color: var(--rc-primary); }
        .rc-filter-bar select:focus {
            outline: none; border-color: var(--rc-primary);
            box-shadow: 0 0 0 3px var(--rc-primary-light);
        }

        .stats-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: var(--rc-surface-2); border: 1px solid var(--rc-border);
            border-radius: var(--radius-lg); padding: 22px 16px; text-align: center;
            transition: all .35s cubic-bezier(.4,0,.2,1);
            position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--stat-color), transparent);
        }
        .stat-card:hover {
            transform: translateY(-5px); box-shadow: var(--shadow-lg);
            border-color: var(--stat-color);
        }
        .stat-card .stat-icon {
            font-size: 1.6rem; margin-bottom: 10px; display: block;
            transition: transform .3s ease;
        }
        .stat-card:hover .stat-icon { transform: scale(1.15); }
        .stat-card.matricula { --stat-color: #3b82f6; }
        .stat-card.colegiatura { --stat-color: #10b981; }
        .stat-card.certificacion { --stat-color: #8b5cf6; }
        .stat-card.total {
            --stat-color: var(--rc-primary);
            background: linear-gradient(135deg, var(--rc-primary-light), var(--rc-primary-subtle));
            border-color: rgba(252,123,4,.3);
        }
        .stat-card.efectivo { --stat-color: #0ea5e9; }
        .stat-card.qr { --stat-color: #14b8a6; }
        .stat-card.transferencia { --stat-color: #6366f1; }
        .stat-card.matricula .stat-icon { color: #3b82f6; }
        .stat-card.colegiatura .stat-icon { color: #10b981; }
        .stat-card.certificacion .stat-icon { color: #8b5cf6; }
        .stat-card.total .stat-icon { color: var(--rc-primary-dark); }
        .stat-card.efectivo .stat-icon { color: #0ea5e9; }
        .stat-card.qr .stat-icon { color: #14b8a6; }
        .stat-card.transferencia .stat-icon { color: #6366f1; }
        .stat-label {
            font-size: .72rem; font-weight: 600; color: var(--rc-text-muted);
            text-transform: uppercase; letter-spacing: .6px; margin-bottom: 6px;
        }
        .stat-value {
            font-size: 1.35rem; font-weight: 700; color: var(--rc-text);
        }
        .stat-card.total .stat-value { color: var(--rc-primary-dark); }

        .rc-panel {
            background: var(--rc-surface-2);
            border: 1px solid var(--rc-border);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        .rc-panel-header {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 16px;
            padding: 20px 28px;
            border-bottom: 1px solid var(--rc-border);
            background: linear-gradient(180deg, #fafafa 0%, var(--rc-surface-2) 100%);
        }
        .rc-panel-title {
            margin: 0; font-size: 1.05rem; font-weight: 600; color: var(--rc-text);
            display: flex; align-items: center; gap: 10px;
        }
        .rc-panel-title i { color: var(--rc-primary); }
        .rc-panel-badge {
            background: var(--rc-primary-light); color: var(--rc-primary-dark);
            padding: 5px 14px; border-radius: 20px; font-size: .78rem; font-weight: 600;
        }

        .recibos-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .recibos-table thead th {
            padding: 14px 16px;
            font-size: .68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
            background: #fafbfc;
            position: sticky; top: 0; z-index: 2;
        }
        .recibos-table thead th:first-child { width: 50px; text-align: center; border-radius: 0; }
        .recibos-table thead th:last-child { width: 100px; text-align: center; }
        .recibos-table tbody tr {
            transition: all .2s ease;
        }
        .recibos-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(252,123,4,.04) 0%, rgba(252,123,4,.01) 100%);
        }
        .recibos-table tbody td {
            padding: 16px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: .85rem;
        }
        .recibos-table tbody tr:last-child td { border-bottom: none; }

        .recibo-num-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 30px; height: 30px;
            background: linear-gradient(135deg, #fc7b04, #c96004);
            color: white; border-radius: 8px;
            font-weight: 700; font-size: .72rem;
        }

        .recibo-cell {
            display: flex; align-items: center; gap: 10px;
        }
        .recibo-cell-icon {
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            background: var(--rc-primary-light);
            border-radius: 10px; color: var(--rc-primary-dark);
            font-size: 1rem; flex-shrink: 0;
        }
        .recibo-cell-info { display: flex; flex-direction: column; gap: 1px; }
        .recibo-cell-code {
            font-weight: 700; font-size: .88rem; color: var(--rc-text);
            font-family: 'Inter', 'Plus Jakarta Sans', system-ui, sans-serif;
        }
        .recibo-cell-sub {
            font-size: .7rem; color: var(--rc-text-muted);
        }

        .recibo-fecha {
            display: flex; flex-direction: column; gap: 2px;
        }
        .recibo-fecha .dia {
            font-weight: 700; color: #1e293b; font-size: .9rem;
        }
        .recibo-fecha .mes { font-size: .68rem; color: #94a3b8; }

        .cobrador-info {
            display: flex; flex-direction: column; gap: 2px;
        }
        .cobrador-nombre { font-weight: 500; color: #334155; }
        .cobrador-cargo { font-size: .68rem; color: #94a3b8; }

        .est-link {
            color: #1e293b; text-decoration: none; font-weight: 600;
            transition: all .2s ease;
        }
        .est-link:hover { color: #fc7b04; }

        .recibo-oferta {
            display: inline-block;
            font-size: .75rem; color: #64748b;
            max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .recibo-monto {
            font-weight: 700; font-size: .9rem; color: #15803d;
        }
        .recibo-descuento {
            font-weight: 500; font-size: .78rem; color: #dc2626;
        }

        .factura-badge {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: .72rem; font-weight: 600;
            padding: 4px 12px; border-radius: 20px; cursor: default;
        }
        .factura-si {
            background: rgba(5,150,105,.12); color: #059669;
            border: 1px solid rgba(5,150,105,.25); cursor: pointer;
            transition: all .2s;
        }
        .factura-si:hover { background: rgba(5,150,105,.2); transform: scale(1.04); }
        .factura-no {
            background: rgba(239,68,68,.08); color: #dc2626;
            border: 1px solid rgba(239,68,68,.15);
        }

        .ver-btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 7px 14px;
            background: #f1f5f9; border: 1px solid #e2e8f0;
            color: #64748b; border-radius: 8px;
            font-size: .74rem; font-weight: 600;
            transition: all .25s ease; cursor: pointer;
        }
        .ver-btn:hover {
            background: linear-gradient(135deg, #fc7b04, #c96004);
            color: white; border-color: #fc7b04;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(252,123,4,.3);
        }
        .ver-btn i { font-size: .85rem; }

        .pagination-wrapper {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem;
            padding: 16px 28px;
            border-top: 1px solid var(--rc-border);
        }
        .pagination-info { font-size: .84rem; color: var(--rc-text-muted); }
        .pagination { margin: 0; gap: 4px; }
        .pagination .page-link {
            border-radius: 6px !important; border: 1px solid var(--rc-border);
            color: var(--rc-text); padding: 8px 13px;
            background: var(--rc-surface-2); font-size: .84rem;
            transition: all .2s;
        }
        .pagination .page-item.active .page-link {
            background: var(--rc-primary); border-color: var(--rc-primary); color: #fff;
            box-shadow: 0 2px 8px rgba(252,123,4,.3);
        }
        .pagination .page-item.disabled .page-link { background: var(--rc-surface); color: var(--rc-text-muted); opacity: .6; }
        .pagination .page-link:hover {
            background: var(--rc-primary-light); border-color: var(--rc-primary);
            color: var(--rc-primary-dark);
        }

        .empty-state { text-align: center; padding: 70px 20px; }
        .empty-state i { font-size: 4.5rem; color: #cbd5e1; }
        .empty-state p { color: var(--rc-text-muted); margin-top: 12px; font-size: .95rem; }

        @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 576px) {
            .stats-grid { grid-template-columns: 1fr; }
            .rc-header { flex-direction: column; align-items: flex-start; }
            .rc-filter-bar { flex-direction: column; align-items: stretch; }
            .rc-filter-bar select { width: 100%; }
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
                <?php echo e($meses[$mes]); ?> <?php echo e($gestion); ?>

            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card matricula">
                <div class="stat-icon"><i class="ri-file-paper-line"></i></div>
                <div class="stat-label">Matrícula</div>
                <div class="stat-value">Bs. <?php echo e(number_format($stats['matricula'], 2)); ?></div>
            </div>
            <div class="stat-card colegiatura">
                <div class="stat-icon"><i class="ri-school-line"></i></div>
                <div class="stat-label">Colegiatura</div>
                <div class="stat-value">Bs. <?php echo e(number_format($stats['colegiatura'], 2)); ?></div>
            </div>
            <div class="stat-card certificacion">
                <div class="stat-icon"><i class="ri-award-line"></i></div>
                <div class="stat-label">Certificación</div>
                <div class="stat-value">Bs. <?php echo e(number_format($stats['certificacion'], 2)); ?></div>
            </div>
            <div class="stat-card total">
                <div class="stat-icon"><i class="ri-money-dollar-circle-line"></i></div>
                <div class="stat-label">Total Ingresos</div>
                <div class="stat-value">Bs. <?php echo e(number_format($stats['total'], 2)); ?></div>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card efectivo">
                <div class="stat-icon"><i class="ri-cash-line"></i></div>
                <div class="stat-label">Efectivo</div>
                <div class="stat-value">Bs. <?php echo e(number_format($stats['efectivo'], 2)); ?></div>
            </div>
            <div class="stat-card qr">
                <div class="stat-icon"><i class="ri-qr-code-line"></i></div>
                <div class="stat-label">QR</div>
                <div class="stat-value">Bs. <?php echo e(number_format($stats['qr'], 2)); ?></div>
            </div>
            <div class="stat-card transferencia">
                <div class="stat-icon"><i class="ri-bank-line"></i></div>
                <div class="stat-label">Transferencia</div>
                <div class="stat-value">Bs. <?php echo e(number_format($stats['transferencia'] ?? 0, 2)); ?></div>
            </div>
            <div class="stat-card total">
                <div class="stat-icon"><i class="ri-check-double-line"></i></div>
                <div class="stat-label">Total Medios</div>
                <div class="stat-value">Bs. <?php echo e(number_format(($stats['efectivo'] + $stats['qr'] + ($stats['transferencia'] ?? 0)), 2)); ?></div>
            </div>
        </div>

        <form method="GET" action="<?php echo e(route('admin.contabilidad.recibos')); ?>" class="rc-filter-bar">
            <label>Gestión:</label>
            <select name="gestion" onchange="this.form.submit()">
                <?php $__currentLoopData = $gestiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($g); ?>" <?php echo e($g == $gestion ? 'selected' : ''); ?>><?php echo e($g); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <label>Mes:</label>
            <select name="mes" onchange="this.form.submit()">
                <?php for($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo e($i); ?>" <?php echo e($i == $mes ? 'selected' : ''); ?>><?php echo e($meses[$i]); ?></option>
                <?php endfor; ?>
            </select>
        </form>

        <div class="rc-panel">
            <div class="rc-panel-header">
                <h5 class="rc-panel-title">
                    <i class="ri-file-list-3-line"></i>
                    Recibos del período
                </h5>
                <span class="rc-panel-badge"><?php echo e($pagos->total()); ?> registros</span>
            </div>

            <?php if($pagos->isEmpty()): ?>
                <div class="empty-state">
                    <i class="ri-inbox-line"></i>
                    <p>No hay recibos en el período seleccionado</p>
                </div>
            <?php else: ?>
                <div class="recibos-card">
                    <table class="recibos-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                    <th>Recibo</th>
                                    <th>Fecha</th>
                                    <th>Estudiante</th>
                                <th>Oferta</th>
                                <th class="text-end">Monto</th>
                                <th class="text-end">Descuento</th>
                                <th class="text-center">Factura</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $contador = ($pagos->currentPage() - 1) * $pagos->perPage() + 1;
                            ?>
                            <?php $__currentLoopData = $pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
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
                                ?>
                                <tr>
                                    <td>
                                        <div class="recibo-num-badge"><?php echo e($contador++); ?></div>
                                    </td>
                                    <td>
                                        <div class="recibo-cell">
                                            <div class="recibo-cell-icon"><i class="ri-receipt-line"></i></div>
                                            <div class="recibo-cell-info">
                                                <span class="recibo-cell-code"><?php echo e($pago->recibo); ?></span>
                                                <span class="recibo-cell-sub"><?php echo e($cobradorNombre); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="recibo-fecha">
                                            <span class="dia"><?php echo e(\Carbon\Carbon::parse($pago->fecha_pago)->format('d')); ?></span>
                                            <span class="mes"><?php echo e($meses[\Carbon\Carbon::parse($pago->fecha_pago)->format('n')]); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($estudianteId): ?>
                                            <a href="<?php echo e(route('admin.estudiantes.verDetalle', $estudianteId)); ?>" class="est-link" title="Ver detalle del estudiante">
                                                <?php echo e($estudianteNombre); ?>

                                            </a>
                                        <?php else: ?>
                                            <span class="recibo-estudiante"><?php echo e($estudianteNombre); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="recibo-oferta"><?php echo e($ofertaNombre); ?></span>
                                    </td>
                                    <td class="text-end">
                                        <span class="recibo-monto">Bs. <?php echo e(number_format($pago->monto_total, 2)); ?></span>
                                    </td>
                                    <td class="text-end">
                                        <span class="recibo-descuento">Bs. <?php echo e(number_format($pago->descuento_bs ?? 0, 2)); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php if($pago->documento_factura): ?>
                                            <span class="factura-badge factura-si" title="Haz clic para ver factura" style="cursor:pointer;" onclick="verFactura('<?php echo e(Storage::url($pago->documento_factura)); ?>', '<?php echo e($pago->recibo); ?>', '<?php echo e($estudianteNombre); ?>', '<?php echo e(number_format($pago->monto_total, 2)); ?>', '<?php echo e($ofertaNombre); ?>')">
                                                <i class="ri-checkbox-circle-fill"></i> Sí
                                            </span>
                                        <?php else: ?>
                                            <span class="factura-badge factura-no">
                                                <i class="ri-close-circle-line"></i> No
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="ver-btn" data-bs-toggle="modal"
                                            data-bs-target="#modalDetalle<?php echo e($pago->id); ?>">
                                            <i class="ri-eye-line"></i> Ver
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    <span class="pagination-info">Mostrando <?php echo e($pagos->firstItem()); ?>-<?php echo e($pagos->lastItem()); ?> de
                        <?php echo e($pagos->total()); ?></span>
                    <?php echo e($pagos->links('pagination::bootstrap-4')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php $__currentLoopData = $pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
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
        ?>
        <div class="modal fade" id="modalDetalle<?php echo e($pago->id); ?>" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
                    <div class="modal-header" style="background:linear-gradient(135deg,#fc7b04,#c96004);color:white;padding:1.25rem 1.5rem;border:none;">
                        <h5 class="modal-title" style="font-weight:600;font-size:1.1rem;color:white;">
                            <i class="ri-receipt-line me-2"></i>Detalle del Recibo <span style="opacity:0.9;"><?php echo e($pago->recibo); ?></span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding:1.5rem;background:#f8fafc;">
                        <div class="row mb-4" style="background:white;padding:1rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                            <div class="col-md-6">
                                <p class="mb-2" style="color:#64748b;font-size:.85rem;"><i class="ri-calendar-line me-1"></i><strong>Fecha:</strong></p>
                                <p class="mb-2" style="font-weight:600;color:#1e293b;"><?php echo e(\Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y')); ?></p>
                                <p class="mb-0" style="color:#64748b;font-size:.85rem;"><i class="ri-user-line me-1"></i><strong>Cobrador:</strong></p>
                                <p class="mb-0" style="font-weight:600;color:#1e293b;"><?php echo e($cobradorNombre); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2" style="color:#64748b;font-size:.85rem;"><i class="ri-user-smile-line me-1"></i><strong>Estudiante:</strong></p>
                                <p class="mb-2" style="font-weight:600;color:#1e293b;"><?php echo e($estudianteNombre); ?></p>
                                <p class="mb-0" style="color:#64748b;font-size:.85rem;"><i class="ri-graduation-cap-line me-1"></i><strong>Oferta:</strong></p>
                                <p class="mb-0" style="font-weight:600;color:#1e293b;font-size:.9rem;"><?php echo e($ofertaNombre); ?></p>
                            </div>
                        </div>

                        <h6 style="color:#475569;font-weight:600;margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:2px solid #e2e8f0;">
                            <i class="ri-payment-line me-2"></i>Método de Pago
                        </h6>

                        <div class="row justify-content-center g-3 mb-4">
                            <?php if($tipoPago === 'Efectivo'): ?>
                                <div class="col-md-5">
                                    <div class="card" style="border:none;border-radius:12px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);box-shadow:0 4px 12px rgba(37,99,235,0.15);">
                                        <div class="card-body text-center py-3">
                                            <i class="ri-cash-line fs-2" style="color:#2563eb;"></i>
                                            <p class="mb-1 mt-2" style="color:#1e40af;font-weight:600;">Efectivo</p>
                                            <h4 class="mb-0" style="color:#1e293b;font-weight:700;">Bs. <?php echo e(number_format($totalEfectivo, 2)); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif($tipoPago === 'Qr'): ?>
                                <div class="col-md-5">
                                    <div class="card" style="border:none;border-radius:12px;background:linear-gradient(135deg,#d1fae5,#a7f3d0);box-shadow:0 4px 12px rgba(16,185,129,0.15);">
                                        <div class="card-body text-center py-3">
                                            <i class="ri-qr-code-line fs-2" style="color:#059669;"></i>
                                            <p class="mb-1 mt-2" style="color:#065f46;font-weight:600;">QR</p>
                                            <h4 class="mb-0" style="color:#1e293b;font-weight:700;">Bs. <?php echo e(number_format($totalQr, 2)); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif($tipoPago === 'Transferencia'): ?>
                                <div class="col-md-5">
                                    <div class="card" style="border:none;border-radius:12px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);box-shadow:0 4px 12px rgba(99,102,241,0.15);">
                                        <div class="card-body text-center py-3">
                                            <i class="ri-bank-line fs-2" style="color:#4f46e5;"></i>
                                            <p class="mb-1 mt-2" style="color:#4338ca;font-weight:600;">Transferencia</p>
                                            <h4 class="mb-0" style="color:#1e293b;font-weight:700;">Bs. <?php echo e(number_format($totalTransferencia, 2)); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif($tipoPago === 'Parcial'): ?>
                                <div class="col-md-5">
                                    <div class="card" style="border:none;border-radius:12px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);box-shadow:0 4px 12px rgba(37,99,235,0.15);">
                                        <div class="card-body text-center py-3">
                                            <i class="ri-cash-line fs-2" style="color:#2563eb;"></i>
                                            <p class="mb-1 mt-2" style="color:#1e40af;font-weight:600;">Efectivo</p>
                                            <h4 class="mb-0" style="color:#1e293b;font-weight:700;">Bs. <?php echo e(number_format($totalEfectivo, 2)); ?></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="card" style="border:none;border-radius:12px;background:linear-gradient(135deg,#d1fae5,#a7f3d0);box-shadow:0 4px 12px rgba(16,185,129,0.15);">
                                        <div class="card-body text-center py-3">
                                            <i class="ri-qr-code-line fs-2" style="color:#059669;"></i>
                                            <p class="mb-1 mt-2" style="color:#065f46;font-weight:600;">QR</p>
                                            <h4 class="mb-0" style="color:#1e293b;font-weight:700;">Bs. <?php echo e(number_format($totalQr, 2)); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div style="background:white;padding:1.25rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span style="color:#64748b;font-size:.9rem;"><strong>Monto Total:</strong></span>
                                <span style="color:#1e293b;font-weight:700;font-size:1.1rem;">Bs. <?php echo e(number_format($pago->monto_total, 2)); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <span style="color:#64748b;font-size:.9rem;"><strong>Descuento:</strong></span>
                                <span style="color:#dc2626;font-weight:600;">- Bs. <?php echo e(number_format($pago->descuento_bs ?? 0, 2)); ?></span>
                            </div>
                        </div>

                        <?php if(count($cuotasPagadas) > 0): ?>
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
                                            <?php $__currentLoopData = $cuotasPagadas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><i
                                                            class="ri-check-line text-success me-2"></i><?php echo e($cuota['nombre']); ?>

                                                    </td>
                                                    <td class="text-end">Bs. <?php echo e(number_format($cuota['monto'], 2)); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="table-success">
                                                <td><strong>Total</strong></td>
                                                <td class="text-end"><strong>Bs.
                                                        <?php echo e(number_format($totalMontoCuotas, 2)); ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mt-3" style="background:white;padding:1.25rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                            <h6 style="color:#475569;font-weight:600;margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:2px solid #e2e8f0;">
                                <i class="ri-file-list-3-line me-2"></i>Factura
                            </h6>
                            <?php if($pago->documento_factura): ?>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="factura-badge factura-si">
                                        <i class="ri-checkbox-circle-fill"></i> Factura registrada
                                    </span>
                                    <button type="button" class="btn btn-sm" style="background:#059669;color:white;border:none;border-radius:8px;padding:0.35rem 0.85rem;font-size:.8rem;cursor:pointer;" onclick="verFactura('<?php echo e(Storage::url($pago->documento_factura)); ?>', '<?php echo e($pago->recibo); ?>', '<?php echo e($estudianteNombre); ?>', '<?php echo e(number_format($pago->monto_total, 2)); ?>', '<?php echo e($ofertaNombre); ?>')">
                                        <i class="ri-eye-line me-1"></i> Ver factura
                                    </button>
                                </div>
                            <?php else: ?>
                                <form class="factura-upload-form" data-pago-id="<?php echo e($pago->id); ?>" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="factura-badge factura-no">
                                            <i class="ri-close-circle-line"></i> Sin factura
                                        </span>
                                        <input type="file" name="documento_factura" accept="image/*,application/pdf" class="form-control form-control-sm" style="max-width:220px;font-size:.8rem;" required>
                                        <button type="submit" class="btn btn-sm" style="background:#059669;color:white;border:none;border-radius:8px;padding:0.35rem 0.85rem;font-size:.8rem;">
                                            <i class="ri-upload-line me-1"></i> Subir
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="modal-footer" style="background:#f8fafc;border:none;padding:1rem 1.5rem;">
                        <a href="<?php echo e(route('admin.estudiantes.generarReciboPdf', ['pagoId' => $pago->id])); ?>"
                            target="_blank" class="btn" style="background:linear-gradient(135deg,#059669,#10b981);color:white;border:none;padding:0.6rem 1.25rem;border-radius:8px;font-weight:500;">
                            <i class="ri-download-line me-1"></i> Descargar Recibo
                        </a>
                        <button type="button" class="btn" style="background:#e2e8f0;color:#475569;border:none;padding:0.6rem 1.25rem;border-radius:8px;font-weight:500;" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<div class="modal fade" id="modalVerFactura" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background:linear-gradient(135deg,#fc7b04,#c96004);color:white;padding:1.25rem 1.5rem;border:none;">
                <h5 class="modal-title" style="font-weight:600;font-size:1.1rem;color:white;">
                    <i class="ri-file-list-3-line me-2"></i>Factura — Recibo <span id="facturaReciboNum"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;background:#f8fafc;">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div style="background:white;padding:.75rem 1rem;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.04);">
                            <p style="color:#64748b;font-size:.75rem;margin-bottom:.2rem;">Estudiante</p>
                            <p id="facturaEstudiante" style="font-weight:600;color:#1e293b;margin:0;font-size:.9rem;"></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background:white;padding:.75rem 1rem;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.04);">
                            <p style="color:#64748b;font-size:.75rem;margin-bottom:.2rem;">Oferta</p>
                            <p id="facturaOferta" style="font-weight:600;color:#1e293b;margin:0;font-size:.9rem;"></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background:white;padding:.75rem 1rem;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.04);">
                            <p style="color:#64748b;font-size:.75rem;margin-bottom:.2rem;">Monto</p>
                            <p id="facturaMonto" style="font-weight:700;color:#059669;margin:0;font-size:.9rem;"></p>
                        </div>
                    </div>
                </div>
                <div style="background:white;padding:1rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.05);text-align:center;">
                    <div id="facturaFileContainer" style="max-height:500px;overflow:auto;"></div>
                </div>
            </div>
            <div class="modal-footer" style="background:#f8fafc;border:none;padding:1rem 1.5rem;">
                <a id="facturaDownloadLink" href="#" target="_blank" class="btn" style="background:linear-gradient(135deg,#fc7b04,#c96004);color:white;border:none;padding:0.6rem 1.25rem;border-radius:8px;font-weight:500;">
                    <i class="ri-download-line me-1"></i> Descargar
                </a>
                <button type="button" class="btn" style="background:#e2e8f0;color:#475569;border:none;padding:0.6rem 1.25rem;border-radius:8px;font-weight:500;" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function verFactura(url, recibo, estudiante, monto, oferta) {
    document.getElementById('facturaReciboNum').textContent = recibo;
    document.getElementById('facturaEstudiante').textContent = estudiante;
    document.getElementById('facturaOferta').textContent = oferta;
    document.getElementById('facturaMonto').textContent = 'Bs. ' + monto;
    document.getElementById('facturaDownloadLink').href = url;

    var container = document.getElementById('facturaFileContainer');
    var ext = url.split('.').pop().toLowerCase();
    if (ext === 'pdf') {
        container.innerHTML = '<iframe src="' + url + '" style="width:100%;height:500px;border:none;border-radius:8px;"></iframe>';
    } else {
        container.innerHTML = '<img src="' + url + '" style="max-width:100%;max-height:500px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">';
    }

    new bootstrap.Modal(document.getElementById('modalVerFactura')).show();
}

$(document).on('submit', '.factura-upload-form', function (e) {
    e.preventDefault();
    const form = $(this);
    const pagoId = form.data('pago-id');
    const formData = new FormData(form[0]);
    const btn = form.find('button[type="submit"]');

    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

    $.ajax({
        url: '<?php echo e(route("admin.contabilidad.recibos.subir-factura", ["pagoId" => "__ID__"])); ?>'.replace('__ID__', pagoId),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (r) {
            location.reload();
        },
        error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Error al subir la factura.';
                alert(msg);
                btn.prop('disabled', false).html('<i class="ri-upload-line me-1"></i> Subir');
            }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/contabilidad/recibos.blade.php ENDPATH**/ ?>