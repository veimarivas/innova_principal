<div class="tab-content-section" id="tab-finanzas">
<style>
    /* ── Variables reutilizadas del sistema contabilidad ── */
    .fin-tab {
        --fc-primary:   #0d9488;
        --fc-indigo:    #6366f1;
        --fc-cyan:      #0891b2;
        --fc-amber:     #d97706;
        --fc-slate:     #64748b;
        --fc-success:   #059669;
        --fc-danger:    #dc2626;
        --fc-surface:   #ffffff;
        --fc-alt:       #f8fafc;
        --fc-border:    #e2e8f0;
        --fc-border-lt: #f1f5f9;
        --fc-text:      #0f172a;
        --fc-muted:     #94a3b8;
        --fc-shadow-sm: 0 1px 2px rgba(15,23,42,.04);
        --fc-shadow:    0 4px 6px -1px rgba(15,23,42,.06),0 2px 4px -2px rgba(15,23,42,.06);
        --fc-shadow-lg: 0 10px 15px -3px rgba(15,23,42,.08),0 4px 6px -4px rgba(15,23,42,.04);
        font-family: 'DM Sans', 'Inter', sans-serif;
    }

    /* ── KPI grid ── */
    .fin-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    .fin-kpi-card {
        background: var(--fc-surface);
        border-radius: 18px;
        border: 1px solid var(--fc-border);
        box-shadow: var(--fc-shadow-sm);
        overflow: hidden;
        position: relative;
        transition: all .3s cubic-bezier(.4,0,.2,1);
    }
    .fin-kpi-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
    }
    .fin-kpi-card.kpi-ins::before  { background: linear-gradient(90deg,#6366f1,#8b5cf6); }
    .fin-kpi-card.kpi-prog::before { background: linear-gradient(90deg,#64748b,#94a3b8); }
    .fin-kpi-card.kpi-pag::before  { background: linear-gradient(90deg,#059669,#10b981); }
    .fin-kpi-card.kpi-pend::before { background: linear-gradient(90deg,#dc2626,#f87171); }
    .fin-kpi-card:hover { transform: translateY(-4px); box-shadow: var(--fc-shadow-lg); }
    .fin-kpi-body { padding: 22px 22px 16px; }
    .fin-kpi-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
    .fin-kpi-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
    .fin-kpi-icon.ins  { background: rgba(99,102,241,.12);  color: #6366f1; }
    .fin-kpi-icon.prog { background: rgba(100,116,139,.12); color: #64748b; }
    .fin-kpi-icon.pag  { background: rgba(5,150,105,.12);   color: #059669; }
    .fin-kpi-icon.pend { background: rgba(220,38,38,.1);    color: #dc2626; }
    .fin-kpi-trend { font-size: .75rem; font-weight: 600; padding: 3px 8px; border-radius: 20px; }
    .fin-kpi-trend.up   { background: rgba(5,150,105,.12); color: #059669; }
    .fin-kpi-trend.down { background: rgba(220,38,38,.1);  color: #dc2626; }
    .fin-kpi-value { font-family: 'Sora','DM Sans',sans-serif; font-weight: 700; font-size: 1.55rem; line-height: 1.2; color: var(--fc-text); margin-bottom: 4px; }
    .fin-kpi-label { font-size: .8rem; color: var(--fc-muted); font-weight: 500; }
    .fin-kpi-bar { height: 6px; background: var(--fc-border-lt); border-radius: 0 0 16px 16px; overflow: hidden; }
    .fin-kpi-bar-fill { height: 100%; border-radius: 0 0 0 16px; transition: width 1s ease-out; }

    /* ── Charts row ── */
    .fin-charts-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    .fin-chart-card {
        background: var(--fc-surface);
        border-radius: 16px;
        border: 1px solid var(--fc-border);
        padding: 22px;
        box-shadow: var(--fc-shadow-sm);
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    .fin-chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--fc-border-lt);
        flex-shrink: 0;
    }
    .fin-chart-title { font-weight: 600; font-size: .88rem; color: var(--fc-text); display: flex; align-items: center; gap: 6px; }
    .fin-chart-title i { font-size: 1.05rem; color: var(--fc-primary); }
    .fin-chart-badge { background: var(--fc-alt); border: 1px solid var(--fc-border); color: #475569; font-size: .7rem; font-weight: 600; padding: 3px 10px; border-radius: 20px; white-space: nowrap; }

    /* Donut con overlay central */
    .fin-doughnut-wrap { position: relative; height: 200px; }
    .fin-center-label { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); text-align: center; pointer-events: none; width: 100%; }
    .fin-center-value { font-family: 'Sora',sans-serif; font-weight: 700; font-size: .88rem; color: var(--fc-text); line-height: 1.3; white-space: nowrap; }
    .fin-center-sub   { font-size: .58rem; color: var(--fc-muted); text-transform: uppercase; letter-spacing: .07em; }

    /* Leyenda donut */
    .fin-legend-list { margin-top: 14px; display: flex; flex-direction: column; gap: 5px; }
    .fin-legend-row { display: flex; align-items: center; gap: 8px; padding: 6px 10px; border-radius: 8px; transition: background .15s; }
    .fin-legend-row:hover { background: var(--fc-alt); }
    .fin-legend-swatch { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
    .fin-legend-name   { flex: 1; font-size: .79rem; font-weight: 500; color: var(--fc-text); }
    .fin-legend-amount { font-family: 'Sora',sans-serif; font-weight: 700; font-size: .79rem; color: var(--fc-text); }
    .fin-legend-pct    { font-size: .68rem; font-weight: 700; padding: 2px 8px; border-radius: 12px; min-width: 42px; text-align: center; }

    /* Barras CSS horizontales */
    .fin-hbars { display: flex; flex-direction: column; gap: 20px; padding: 6px 0; flex: 1; }
    .fin-hbar-meta { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 7px; }
    .fin-hbar-concept { display: flex; align-items: center; gap: 8px; font-size: .83rem; font-weight: 600; color: var(--fc-text); }
    .fin-hbar-dot { width: 9px; height: 9px; border-radius: 3px; flex-shrink: 0; }
    .fin-hbar-right { display: flex; align-items: baseline; gap: 5px; }
    .fin-hbar-pct { font-family: 'Sora',sans-serif; font-weight: 800; font-size: 1.15rem; line-height: 1; }
    .fin-hbar-of  { font-size: .7rem; color: var(--fc-muted); }
    .fin-hbar-track { height: 12px; background: var(--fc-border-lt); border-radius: 12px; overflow: hidden; }
    @keyframes finHbarGrow { from { width: 0; } to { width: var(--tw, 0%); } }
    .fin-hbar-fill { height: 100%; border-radius: 12px; width: 0; animation: finHbarGrow 1.3s cubic-bezier(.4,0,.2,1) .4s both; }
    .fin-hbar-amounts { display: flex; justify-content: space-between; margin-top: 5px; }
    .fin-hbar-cobrado { font-size: .72rem; font-weight: 600; }
    .fin-hbar-total   { font-size: .72rem; color: var(--fc-muted); }

    /* Estado mini cards debajo del donut */
    .fin-estado-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 14px; }
    .fin-estado-mini { background: var(--fc-alt); border-radius: 10px; padding: 10px 12px; border-left: 3px solid transparent; }
    .fin-estado-mini-value { font-family: 'Sora',sans-serif; font-weight: 700; font-size: .82rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .fin-estado-mini-label { font-size: .65rem; color: var(--fc-muted); margin-top: 3px; }
    .fin-estado-pct-big { font-family: 'Sora',sans-serif; font-weight: 800; font-size: 1.85rem; line-height: 1; }

    /* ── Resumen por Concepto (rc-style) ── */
    .fin-rc-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 28px; }
    .fin-rc-card { background: var(--fc-surface); border-radius: 18px; border: 1px solid var(--fc-border); box-shadow: var(--fc-shadow-sm); overflow: hidden; transition: transform .3s ease, box-shadow .3s ease; }
    .fin-rc-card:hover { transform: translateY(-3px); box-shadow: var(--fc-shadow); }
    .fin-rc-accent { height: 4px; }
    .fin-rc-body { padding: 20px 22px 18px; }
    .fin-rc-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
    .fin-rc-icon-wrap { display: flex; align-items: center; gap: 12px; }
    .fin-rc-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
    .fin-rc-name { font-family: 'Sora',sans-serif; font-weight: 700; font-size: .95rem; color: var(--fc-text); line-height: 1.3; }
    .fin-rc-sub  { font-size: .7rem; color: var(--fc-muted); margin-top: 3px; display: flex; align-items: center; gap: 4px; }
    .fin-rc-pct-badge { font-family: 'Sora',sans-serif; font-weight: 800; font-size: 1.65rem; line-height: 1; }
    .fin-rc-pct-label { font-size: .62rem; color: var(--fc-muted); text-transform: uppercase; letter-spacing: .06em; text-align: right; }
    @keyframes finRcFill { from { width: 0; } to { width: var(--tw, 0%); } }
    .fin-rc-track { height: 8px; background: var(--fc-border-lt); border-radius: 8px; overflow: hidden; margin-bottom: 14px; }
    .fin-rc-fill  { height: 100%; border-radius: 8px; width: 0; animation: finRcFill 1.2s cubic-bezier(.4,0,.2,1) .2s both; }
    .fin-rc-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; }
    .fin-rc-stat  { background: var(--fc-alt); border-radius: 10px; padding: 10px 8px 8px; text-align: center; }
    .fin-rc-stat-value { font-family: 'Sora',sans-serif; font-weight: 700; font-size: .78rem; line-height: 1.3; word-break: break-all; }
    .fin-rc-stat-label { font-size: .62rem; color: var(--fc-muted); margin-top: 3px; text-transform: uppercase; letter-spacing: .03em; }

    /* Sección title */
    .fin-section-title {
        font-family: 'Sora','DM Sans',sans-serif;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--fc-text);
        margin: 0 0 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .fin-section-title::before {
        content: '';
        width: 4px;
        height: 22px;
        background: linear-gradient(180deg, #fc7b04, #e86e00);
        border-radius: 3px;
        flex-shrink: 0;
    }

    @media (max-width: 1200px) {
        .fin-kpi-grid   { grid-template-columns: repeat(2, 1fr); }
        .fin-charts-row { grid-template-columns: repeat(2, 1fr); }
        .fin-rc-grid    { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .fin-kpi-grid   { grid-template-columns: 1fr; }
        .fin-charts-row { grid-template-columns: 1fr; }
        .fin-rc-grid    { grid-template-columns: 1fr; }
    }
</style>

<div class="fin-tab">

    
    <div class="tab-section-header">
        <div class="tab-section-header-left">
            <div class="tab-section-icon fin-icon-color"><i class="ri-money-dollar-circle-line"></i></div>
            <div>
                <div class="tab-section-title">Resumen Financiero</div>
                <div class="tab-section-sub">Estado de cobranza y pagos de los participantes</div>
            </div>
        </div>
    </div>

    <?php
        $finTotalProg   = collect($resumenPorConcepto)->sum('total');
        $finTotalPag    = collect($resumenPorConcepto)->sum('pagado');
        $finTotalPend   = collect($resumenPorConcepto)->sum('pendiente');
        $finInscritos   = count(array_filter($participantesFinanzas, fn($p) => in_array($p['estado'], ['Inscrito','Confirmado','Inscrito '])));
        $finPct         = $finTotalProg > 0 ? ($finTotalPag / $finTotalProg) * 100 : 0;
        $finPctPend     = $finTotalProg > 0 ? ($finTotalPend / $finTotalProg) * 100 : 0;
        $finTrendCls    = $finPct >= 50 ? 'up' : 'down';
        $finColorMap    = ['Matrícula' => '#6366f1', 'Colegiatura' => '#0891b2', 'Certificación' => '#d97706'];
        $finIconMap     = ['Matrícula' => 'ri-file-text-line', 'Colegiatura' => 'ri-calendar-check-line', 'Certificación' => 'ri-award-line'];
    ?>

    
    <div class="fin-kpi-grid">
        
        <div class="fin-kpi-card kpi-ins">
            <div class="fin-kpi-body">
                <div class="fin-kpi-header">
                    <div class="fin-kpi-icon ins"><i class="ri-user-star-line"></i></div>
                    <span class="fin-kpi-trend <?php echo e($finTrendCls); ?>">
                        <i class="ri-arrow-<?php echo e($finTrendCls === 'up' ? 'up' : 'down'); ?>-line"></i>
                        <?php echo e(number_format($finPct, 1)); ?>%
                    </span>
                </div>
                <div class="fin-kpi-value"><?php echo e($finInscritos); ?></div>
                <div class="fin-kpi-label">Total Inscritos</div>
            </div>
            <div class="fin-kpi-bar">
                <div class="fin-kpi-bar-fill" style="width:<?php echo e(min($finPct,100)); ?>%;background:linear-gradient(90deg,#6366f1,#8b5cf6);"></div>
            </div>
        </div>

        
        <div class="fin-kpi-card kpi-prog">
            <div class="fin-kpi-body">
                <div class="fin-kpi-header">
                    <div class="fin-kpi-icon prog"><i class="ri-calculator-line"></i></div>
                </div>
                <div class="fin-kpi-value">Bs. <?php echo e(number_format($finTotalProg, 0, ',', '.')); ?></div>
                <div class="fin-kpi-label">Total Programado</div>
            </div>
            <div class="fin-kpi-bar">
                <div class="fin-kpi-bar-fill" style="width:100%;background:linear-gradient(90deg,#64748b,#94a3b8);"></div>
            </div>
        </div>

        
        <div class="fin-kpi-card kpi-pag">
            <div class="fin-kpi-body">
                <div class="fin-kpi-header">
                    <div class="fin-kpi-icon pag"><i class="ri-checkbox-circle-line"></i></div>
                    <span class="fin-kpi-trend up">
                        <i class="ri-arrow-up-line"></i>
                        <?php echo e(number_format($finPct, 1)); ?>%
                    </span>
                </div>
                <div class="fin-kpi-value">Bs. <?php echo e(number_format($finTotalPag, 0, ',', '.')); ?></div>
                <div class="fin-kpi-label">Total Cobrado</div>
            </div>
            <div class="fin-kpi-bar">
                <div class="fin-kpi-bar-fill" style="width:<?php echo e(min($finPct,100)); ?>%;background:linear-gradient(90deg,#059669,#10b981);"></div>
            </div>
        </div>

        
        <div class="fin-kpi-card kpi-pend">
            <div class="fin-kpi-body">
                <div class="fin-kpi-header">
                    <div class="fin-kpi-icon pend"><i class="ri-time-line"></i></div>
                    <span class="fin-kpi-trend <?php echo e($finTrendCls); ?>">
                        <i class="ri-arrow-<?php echo e($finTrendCls === 'up' ? 'down' : 'up'); ?>-line"></i>
                        <?php echo e(number_format($finPctPend, 1)); ?>%
                    </span>
                </div>
                <div class="fin-kpi-value">Bs. <?php echo e(number_format($finTotalPend, 0, ',', '.')); ?></div>
                <div class="fin-kpi-label">Total Pendiente</div>
            </div>
            <div class="fin-kpi-bar">
                <div class="fin-kpi-bar-fill" style="width:<?php echo e(min($finPctPend,100)); ?>%;background:linear-gradient(90deg,#dc2626,#f87171);"></div>
            </div>
        </div>
    </div>

    
    <div class="fin-charts-row">

        
        <div class="fin-chart-card" style="border-top:3px solid #6366f1;">
            <div class="fin-chart-header">
                <div class="fin-chart-title"><i class="ri-pie-chart-2-line"></i> Distribución por Concepto</div>
                <span class="fin-chart-badge">Cobrado</span>
            </div>
            <div class="fin-doughnut-wrap">
                <canvas id="finChartConceptos"></canvas>
                <div class="fin-center-label">
                    <div class="fin-center-value">Bs.&nbsp;<?php echo e(number_format($finTotalProg, 0, ',', '.')); ?></div>
                    <div class="fin-center-sub">Programado</div>
                </div>
            </div>
            <div class="fin-legend-list" id="finLegendConceptos"></div>
        </div>

        
        <div class="fin-chart-card" style="border-top:3px solid #0d9488;">
            <div class="fin-chart-header">
                <div class="fin-chart-title"><i class="ri-bar-chart-horizontal-line"></i> Porcentaje de Cobro</div>
                <span class="fin-chart-badge">por concepto</span>
            </div>
            <div class="fin-hbars">
                <?php $__currentLoopData = $resumenPorConcepto; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $concepto => $datos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $pctBar  = $datos['total'] > 0 ? ($datos['pagado'] / $datos['total']) * 100 : 0;
                        $dotClr  = $finColorMap[$concepto] ?? '#64748b';
                        $pctClr  = $pctBar >= 70 ? '#059669' : ($pctBar >= 40 ? '#d97706' : '#dc2626');
                        $barGrad = $pctBar >= 70
                            ? 'linear-gradient(90deg,#059669,#34d399)'
                            : ($pctBar >= 40 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)');
                        $twVal   = number_format(min($pctBar, 100), 2, '.', '');
                    ?>
                    <div>
                        <div class="fin-hbar-meta">
                            <div class="fin-hbar-concept">
                                <span class="fin-hbar-dot" style="background:<?php echo e($dotClr); ?>;"></span>
                                <?php echo e($concepto); ?>

                            </div>
                            <div class="fin-hbar-right">
                                <span class="fin-hbar-pct" style="color:<?php echo e($pctClr); ?>;"><?php echo e(number_format($pctBar, 1)); ?>%</span>
                                <span class="fin-hbar-of">cobrado</span>
                            </div>
                        </div>
                        <div class="fin-hbar-track">
                            <div class="fin-hbar-fill" style="--tw:<?php echo e($twVal); ?>%;background:<?php echo e($barGrad); ?>;"></div>
                        </div>
                        <div class="fin-hbar-amounts">
                            <span class="fin-hbar-cobrado" style="color:<?php echo e($pctClr); ?>;">Bs.&nbsp;<?php echo e(number_format($datos['pagado'], 0, ',', '.')); ?></span>
                            <span class="fin-hbar-total">de Bs.&nbsp;<?php echo e(number_format($datos['total'], 0, ',', '.')); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="fin-chart-card" style="border-top:3px solid #059669;">
            <div class="fin-chart-header">
                <div class="fin-chart-title"><i class="ri-donut-chart-line"></i> Estado de Pagos</div>
                <span class="fin-chart-badge">Global</span>
            </div>
            <?php
                $finColorPct = $finPct >= 70 ? '#059669' : ($finPct >= 40 ? '#d97706' : '#dc2626');
            ?>
            <div class="fin-doughnut-wrap">
                <canvas id="finChartEstado"></canvas>
                <div class="fin-center-label">
                    <div class="fin-estado-pct-big" style="color:<?php echo e($finColorPct); ?>;"><?php echo e(number_format($finPct, 1)); ?>%</div>
                    <div class="fin-center-sub">Cobrado</div>
                </div>
            </div>
            <div class="fin-estado-cards">
                <div class="fin-estado-mini" style="border-left-color:#059669;">
                    <div class="fin-estado-mini-value" style="color:#059669;">Bs.&nbsp;<?php echo e(number_format($finTotalPag, 0, ',', '.')); ?></div>
                    <div class="fin-estado-mini-label"><i class="ri-checkbox-circle-line"></i> Cobrado</div>
                </div>
                <div class="fin-estado-mini" style="border-left-color:#dc2626;">
                    <div class="fin-estado-mini-value" style="color:#dc2626;">Bs.&nbsp;<?php echo e(number_format($finTotalPend, 0, ',', '.')); ?></div>
                    <div class="fin-estado-mini-label"><i class="ri-time-line"></i> Pendiente</div>
                </div>
            </div>
        </div>

    </div>

    
    <h3 class="fin-section-title">Resumen por Concepto</h3>
    <div class="fin-rc-grid">
        <?php $__currentLoopData = $resumenPorConcepto; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $concepto => $datos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $rcPct    = $datos['total'] > 0 ? ($datos['pagado'] / $datos['total']) * 100 : 0;
                $rcColor  = $finColorMap[$concepto] ?? '#64748b';
                $rcPctClr = $rcPct >= 70 ? '#059669' : ($rcPct >= 40 ? '#d97706' : '#dc2626');
                $rcGrad   = $rcPct >= 70
                    ? 'linear-gradient(90deg,#059669,#34d399)'
                    : ($rcPct >= 40 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)');
                $rcTw     = number_format(min($rcPct, 100), 2, '.', '');
                $rcIcon   = $finIconMap[$concepto] ?? 'ri-money-dollar-circle-line';
            ?>
            <div class="fin-rc-card">
                <div class="fin-rc-accent" style="background:<?php echo e($rcColor); ?>;"></div>
                <div class="fin-rc-body">
                    <div class="fin-rc-top">
                        <div class="fin-rc-icon-wrap">
                            <div class="fin-rc-icon" style="background:<?php echo e($rcColor); ?>18;color:<?php echo e($rcColor); ?>;">
                                <i class="<?php echo e($rcIcon); ?>"></i>
                            </div>
                            <div>
                                <div class="fin-rc-name"><?php echo e($concepto); ?></div>
                                <div class="fin-rc-sub">
                                    <i class="ri-coins-line"></i>
                                    <?php echo e(number_format($datos['total'], 0, ',', '.')); ?> Bs. programados
                                </div>
                            </div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <div class="fin-rc-pct-badge" style="color:<?php echo e($rcPctClr); ?>;"><?php echo e(number_format($rcPct, 1)); ?>%</div>
                            <div class="fin-rc-pct-label">cobrado</div>
                        </div>
                    </div>
                    <div class="fin-rc-track">
                        <div class="fin-rc-fill" style="--tw:<?php echo e($rcTw); ?>%;background:<?php echo e($rcGrad); ?>;"></div>
                    </div>
                    <div class="fin-rc-stats">
                        <div class="fin-rc-stat">
                            <div class="fin-rc-stat-value">Bs.&nbsp;<?php echo e(number_format($datos['total'], 0, ',', '.')); ?></div>
                            <div class="fin-rc-stat-label">Programado</div>
                        </div>
                        <div class="fin-rc-stat">
                            <div class="fin-rc-stat-value" style="color:#059669;">Bs.&nbsp;<?php echo e(number_format($datos['pagado'], 0, ',', '.')); ?></div>
                            <div class="fin-rc-stat-label">Cobrado</div>
                        </div>
                        <div class="fin-rc-stat">
                            <div class="fin-rc-stat-value" style="color:#dc2626;">Bs.&nbsp;<?php echo e(number_format($datos['pendiente'], 0, ',', '.')); ?></div>
                            <div class="fin-rc-stat-label">Pendiente</div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php
        $inscritosData   = array_filter($participantesFinanzas, fn($p) => in_array($p['estado'], ['Inscrito', 'Confirmado', 'Inscrito ']));
        $preInscritosData = array_filter($participantesFinanzas, fn($p) => $p['estado'] === 'Pre-Inscrito');
    ?>

    
    <div class="fin-table-card table-card mt-4">
        <div class="fin-table-header">
            <div class="fin-table-header-left">
                <div class="fin-table-icon"><i class="ri-wallet-3-line"></i></div>
                <span class="fin-table-title">Estado Financiero de Participantes</span>
            </div>
            <ul class="fin-tabs nav" id="finanzasTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="fin-tab-btn nav-link active" id="inscritos-tab" data-bs-toggle="tab" data-bs-target="#inscritos-pane" type="button" role="tab">
                        <i class="ri-user-check-line"></i> Inscritos
                        <span class="fin-tab-count"><?php echo e(count($inscritosData)); ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="fin-tab-btn nav-link" id="preinscritos-tab" data-bs-toggle="tab" data-bs-target="#preinscritos-pane" type="button" role="tab">
                        <i class="ri-user-add-line"></i> Pre-Inscritos
                        <span class="fin-tab-count"><?php echo e(count($preInscritosData)); ?></span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="finanzasTabsContent">
                <?php
                    /* Macro para las dos tablas — evita repetir el thead */
                    $finThead = '
                    <thead>
                        <tr class="fin-thead-group">
                            <th colspan="5"></th>
                            <th class="text-center fin-th-group-conceptos" colspan="3">Conceptos de Pago</th>
                            <th class="text-center fin-th-group-totales" colspan="2">Totales</th>
                            <th colspan="1"></th>
                        </tr>
                        <tr class="fin-thead-cols">
                            <th class="text-center fin-th-num">#</th>
                            <th>Estudiante</th>
                            <th class="text-center">Plan</th>
                            <th class="text-center">Vendedor</th>
                            <th class="text-center">F. Insc.</th>
                            <th class="text-end fin-th-conceptos-col">Matrícula</th>
                            <th class="text-end fin-th-conceptos-col">Colegiatura</th>
                            <th class="text-end fin-th-conceptos-col">Certificación</th>
                            <th class="text-end fin-th-totales-col">Cobrado</th>
                            <th class="text-end fin-th-totales-col">Saldo</th>
                            <th class="text-center">Avance</th>
                        </tr>
                    </thead>';
                ?>

                <!-- Tab Inscritos -->
                <div class="tab-pane fade show active" id="inscritos-pane" role="tabpanel" aria-labelledby="inscritos-tab">
                    <div class="table-responsive">
                        <table class="fin-tbl table align-middle mb-0">
                            <thead>
                                <tr class="fin-thead-group">
                                    <th colspan="5"></th>
                                    <th class="text-center fin-th-group-conceptos" colspan="3">Conceptos de Pago</th>
                                    <th class="text-center fin-th-group-totales" colspan="2">Totales</th>
                                    <th colspan="1"></th>
                                </tr>
                                <tr class="fin-thead-cols">
                                    <th class="text-center fin-th-num">#</th>
                                    <th>Estudiante</th>
                                    <th class="text-center">Plan</th>
                                    <th class="text-center">Vendedor</th>
                                    <th class="text-center">F. Insc.</th>
                                    <th class="text-end fin-th-conceptos-col"><i class="ri-file-text-line" style="color:#6366f1;margin-right:3px;"></i>Matrícula</th>
                                    <th class="text-end fin-th-conceptos-col"><i class="ri-calendar-check-line" style="color:#0891b2;margin-right:3px;"></i>Colegiatura</th>
                                    <th class="text-end fin-th-conceptos-col"><i class="ri-award-line" style="color:#d97706;margin-right:3px;"></i>Certificación</th>
                                    <th class="text-end fin-th-totales-col"><i class="ri-check-double-line" style="color:#059669;margin-right:3px;"></i>Cobrado</th>
                                    <th class="text-end fin-th-totales-col"><i class="ri-time-line" style="color:#dc2626;margin-right:3px;"></i>Saldo</th>
                                    <th class="text-center">Avance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $inscritosData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $participante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $pct = $participante['porcentaje_pagado'];
                                        $color = match (true) {
                                            $pct >= 100 => '#16a34a',
                                            $pct >= 70  => '#0891b2',
                                            $pct >= 50  => '#d97706',
                                            default     => '#dc2626',
                                        };
                                        $matricula    = $participante['conceptos']['Matrícula']    ?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                        $colegiatura  = $participante['conceptos']['Colegiatura']  ?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                        $certificacion= $participante['conceptos']['Certificación']?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                        $partes = explode(' ', trim($participante['nombre_completo']));
                                        $ini = strtoupper(substr($partes[0] ?? '?', 0, 1) . substr($partes[1] ?? '', 0, 1));
                                    ?>
                                    <tr class="fin-row">
                                        <td class="text-center fin-td-num"><?php echo e($loop->iteration); ?></td>
                                        <td class="fin-td-estudiante">
                                            <div class="fin-student-cell">
                                                <div class="fin-avatar" style="background:linear-gradient(135deg,<?php echo e($color); ?>,<?php echo e($color); ?>bb);"><?php echo e($ini); ?></div>
                                                <div>
                                                    <a href="/admin/estudiantes/<?php echo e($participante['estudiante_id']); ?>/detalle" class="fin-student-name"><?php echo e($participante['nombre_completo']); ?></a>
                                                    <span class="fin-ci-badge"><i class="ri-fingerprint-line"></i><?php echo e($participante['carnet']); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="fin-plan-badge"><i class="ri-wallet-line" style="font-size:.65rem;"></i><?php echo e($participante['plan_pago']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if($participante['vendedor_persona_id'] ?? null): ?>
                                                <a href="<?php echo e(route('admin.vendedor.inscripciones', $participante['vendedor_persona_id'])); ?>" class="fin-link"><?php echo e($participante['vendedor'] ?? '—'); ?></a>
                                            <?php else: ?>
                                                <span class="fin-muted"><?php echo e($participante['vendedor'] ?? '—'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center fin-fecha">
                                            <i class="ri-calendar-2-line" style="font-size:.7rem;opacity:.5;margin-right:2px;"></i><?php echo e(\Carbon\Carbon::parse($participante['fecha_inscripcion'])->format('d/m/Y')); ?>

                                        </td>
                                        <td class="text-end fin-td-conceptos-col"><?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle._concepto-cell', ['c' => $matricula], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                                        <td class="text-end fin-td-conceptos-col"><?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle._concepto-cell', ['c' => $colegiatura], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                                        <td class="text-end fin-td-conceptos-col"><?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle._concepto-cell', ['c' => $certificacion], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                                        <td class="text-end fin-td-totales-col fin-amount-paid">Bs <?php echo e(number_format($participante['total_pagado'], 2)); ?></td>
                                        <td class="text-end fin-td-totales-col fin-amount-saldo">Bs <?php echo e(number_format($participante['saldo'], 2)); ?></td>
                                        <td class="text-center">
                                            <div class="fin-avance-cell">
                                                <span class="fin-pct-badge" style="background:<?php echo e($color); ?>18;color:<?php echo e($color); ?>;"><?php echo e(number_format($pct, 0)); ?>%</span>
                                                <div class="fin-progress-wrap">
                                                    <div class="fin-progress-bar" style="width:<?php echo e(min($pct,100)); ?>%;background:linear-gradient(90deg,<?php echo e($color); ?>,<?php echo e($color); ?>cc);"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="11" class="fin-empty-row">
                                        <i class="ri-wallet-line"></i>
                                        <p>No hay participantes inscritos</p>
                                    </td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Pre-Inscritos -->
                <div class="tab-pane fade" id="preinscritos-pane" role="tabpanel" aria-labelledby="preinscritos-tab">
                    <div class="table-responsive">
                        <table class="fin-tbl table align-middle mb-0">
                            <thead>
                                <tr class="fin-thead-group">
                                    <th colspan="5"></th>
                                    <th class="text-center fin-th-group-conceptos" colspan="3">Conceptos de Pago</th>
                                    <th class="text-center fin-th-group-totales" colspan="2">Totales</th>
                                    <th colspan="1"></th>
                                </tr>
                                <tr class="fin-thead-cols">
                                    <th class="text-center fin-th-num">#</th>
                                    <th>Estudiante</th>
                                    <th class="text-center">Plan</th>
                                    <th class="text-center">Vendedor</th>
                                    <th class="text-center">F. Insc.</th>
                                    <th class="text-end fin-th-conceptos-col"><i class="ri-file-text-line" style="color:#6366f1;margin-right:3px;"></i>Matrícula</th>
                                    <th class="text-end fin-th-conceptos-col"><i class="ri-calendar-check-line" style="color:#0891b2;margin-right:3px;"></i>Colegiatura</th>
                                    <th class="text-end fin-th-conceptos-col"><i class="ri-award-line" style="color:#d97706;margin-right:3px;"></i>Certificación</th>
                                    <th class="text-end fin-th-totales-col"><i class="ri-check-double-line" style="color:#059669;margin-right:3px;"></i>Cobrado</th>
                                    <th class="text-end fin-th-totales-col"><i class="ri-time-line" style="color:#dc2626;margin-right:3px;"></i>Saldo</th>
                                    <th class="text-center">Avance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $preInscritosData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $participante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $pct = $participante['porcentaje_pagado'];
                                        $color = match (true) {
                                            $pct >= 100 => '#16a34a',
                                            $pct >= 70  => '#0891b2',
                                            $pct >= 50  => '#d97706',
                                            default     => '#dc2626',
                                        };
                                        $matricula    = $participante['conceptos']['Matrícula']    ?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                        $colegiatura  = $participante['conceptos']['Colegiatura']  ?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                        $certificacion= $participante['conceptos']['Certificación']?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                        $partes = explode(' ', trim($participante['nombre_completo']));
                                        $ini = strtoupper(substr($partes[0] ?? '?', 0, 1) . substr($partes[1] ?? '', 0, 1));
                                    ?>
                                    <tr class="fin-row">
                                        <td class="text-center fin-td-num"><?php echo e($loop->iteration); ?></td>
                                        <td class="fin-td-estudiante">
                                            <div class="fin-student-cell">
                                                <div class="fin-avatar" style="background:linear-gradient(135deg,#d97706,#f59e0b);"><?php echo e($ini); ?></div>
                                                <div>
                                                    <a href="/admin/estudiantes/<?php echo e($participante['estudiante_id']); ?>/detalle" class="fin-student-name"><?php echo e($participante['nombre_completo']); ?></a>
                                                    <span class="fin-ci-badge"><i class="ri-fingerprint-line"></i><?php echo e($participante['carnet']); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="fin-plan-badge"><i class="ri-wallet-line" style="font-size:.65rem;"></i><?php echo e($participante['plan_pago']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if($participante['vendedor_persona_id'] ?? null): ?>
                                                <a href="<?php echo e(route('admin.vendedor.inscripciones', $participante['vendedor_persona_id'])); ?>" class="fin-link"><?php echo e($participante['vendedor'] ?? '—'); ?></a>
                                            <?php else: ?>
                                                <span class="fin-muted"><?php echo e($participante['vendedor'] ?? '—'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center fin-fecha">
                                            <i class="ri-calendar-2-line" style="font-size:.7rem;opacity:.5;margin-right:2px;"></i><?php echo e(\Carbon\Carbon::parse($participante['fecha_inscripcion'])->format('d/m/Y')); ?>

                                        </td>
                                        <td class="text-end fin-td-conceptos-col"><?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle._concepto-cell', ['c' => $matricula], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                                        <td class="text-end fin-td-conceptos-col"><?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle._concepto-cell', ['c' => $colegiatura], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                                        <td class="text-end fin-td-conceptos-col"><?php echo $__env->make('admin.ofertas-academicas.partials.ofertas-detalle._concepto-cell', ['c' => $certificacion], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                                        <td class="text-end fin-td-totales-col fin-amount-paid">Bs <?php echo e(number_format($participante['total_pagado'], 2)); ?></td>
                                        <td class="text-end fin-td-totales-col fin-amount-saldo">Bs <?php echo e(number_format($participante['saldo'], 2)); ?></td>
                                        <td class="text-center">
                                            <div class="fin-avance-cell">
                                                <span class="fin-pct-badge" style="background:<?php echo e($color); ?>18;color:<?php echo e($color); ?>;"><?php echo e(number_format($pct, 0)); ?>%</span>
                                                <div class="fin-progress-wrap">
                                                    <div class="fin-progress-bar" style="width:<?php echo e(min($pct,100)); ?>%;background:linear-gradient(90deg,<?php echo e($color); ?>,<?php echo e($color); ?>cc);"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="11" class="fin-empty-row">
                                        <i class="ri-user-line"></i>
                                        <p>No hay participantes pre-inscritos</p>
                                    </td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/ofertas-academicas/partials/ofertas-detalle/tab-finanzas.blade.php ENDPATH**/ ?>