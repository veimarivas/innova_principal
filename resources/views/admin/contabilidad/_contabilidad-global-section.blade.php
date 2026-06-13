{{--
    Partial reutilizable: KPIs + 3 gráficos + tarjetas Resumen por Concepto
    para la sección general del dashboard de Contabilidad.

    Variables esperadas:
      $totales          array  [total_inscritos, total_programado, total_pagado, total_pendiente, porcentaje_pagado]
      $conceptos        array  [<concepto> => ['total','pagado','pendiente','cantidad_cuotas','porcentaje']]
      $suffix           string sufijo para IDs únicos (Completo / IngresosReales / Retirados)
      $labels           array ['programado','cobrado','pendiente']
      $kpiCountLabel    string
      $kpiCountIcon     string (clase remix)
      $kpiCountIconBg   string (rgba)
      $kpiCountIconColor string
      $pendienteVariant string 'normal' | 'perdida'
      $colorConceptos   array
--}}
@php
    $secProg = (float)($totales['total_programado'] ?? 0);
    $secPag  = (float)($totales['total_pagado'] ?? 0);
    $secPend = (float)($totales['total_pendiente'] ?? 0);
    $secPct  = (float)($totales['porcentaje_pagado'] ?? ($secProg > 0 ? ($secPag / $secProg) * 100 : 0));
    $secPctPend = $secProg > 0 ? ($secPend / $secProg) * 100 : 0;
    $secColorPct = $secPct >= 70 ? '#059669' : ($secPct >= 40 ? '#d97706' : '#dc2626');
    $secTrend = $secPct >= 50 ? 'up' : 'down';
    $pendGrad = ($pendienteVariant ?? 'normal') === 'perdida'
        ? 'linear-gradient(90deg,#991b1b,#dc2626)'
        : 'linear-gradient(90deg,#dc2626,#f87171)';
    $pendIcon = ($pendienteVariant ?? 'normal') === 'perdida' ? 'ri-close-circle-line' : 'ri-time-line';
@endphp

{{-- ── KPI Cards ── --}}
<div class="cont-kpi-grid">
    <div class="cont-kpi-card kpi-inscritos">
        <div class="cont-kpi-body">
            <div class="cont-kpi-header">
                <div class="cont-kpi-icon inscritos" style="background:{{ $kpiCountIconBg }};color:{{ $kpiCountIconColor }};">
                    <i class="{{ $kpiCountIcon }}"></i>
                </div>
                <span class="cont-kpi-trend {{ $secTrend }}">
                    <i class="ri-arrow-{{ $secTrend === 'up' ? 'up' : 'down' }}-line"></i> {{ number_format($secPct, 1) }}%
                </span>
            </div>
            <div class="cont-kpi-value">{{ number_format($totales['total_inscritos'] ?? 0) }}</div>
            <div class="cont-kpi-label">{{ $kpiCountLabel }}</div>
        </div>
        <div class="cont-kpi-bar">
            <div class="cont-kpi-bar-fill" style="width:{{ min($secPct, 100) }}%; background: linear-gradient(90deg, {{ $kpiCountIconColor }}, {{ $kpiCountIconColor }}aa);"></div>
        </div>
    </div>

    <div class="cont-kpi-card kpi-programado">
        <div class="cont-kpi-body">
            <div class="cont-kpi-header">
                <div class="cont-kpi-icon programado"><i class="ri-calculator-line"></i></div>
            </div>
            <div class="cont-kpi-value">Bs. {{ number_format($secProg, 0, ',', '.') }}</div>
            <div class="cont-kpi-label">{{ $labels['programado'] }}</div>
        </div>
        <div class="cont-kpi-bar">
            <div class="cont-kpi-bar-fill" style="width: 100%; background: linear-gradient(90deg, #64748b, #94a3b8);"></div>
        </div>
    </div>

    <div class="cont-kpi-card kpi-pagado">
        <div class="cont-kpi-body">
            <div class="cont-kpi-header">
                <div class="cont-kpi-icon pagado"><i class="ri-checkbox-circle-line"></i></div>
                <span class="cont-kpi-trend up">
                    <i class="ri-arrow-up-line"></i> {{ number_format($secPct, 1) }}%
                </span>
            </div>
            <div class="cont-kpi-value">Bs. {{ number_format($secPag, 0, ',', '.') }}</div>
            <div class="cont-kpi-label">{{ $labels['cobrado'] }}</div>
        </div>
        <div class="cont-kpi-bar">
            <div class="cont-kpi-bar-fill" style="width:{{ min($secPct, 100) }}%; background: linear-gradient(90deg, #059669, #10b981);"></div>
        </div>
    </div>

    <div class="cont-kpi-card kpi-pendiente">
        <div class="cont-kpi-body">
            <div class="cont-kpi-header">
                <div class="cont-kpi-icon pendiente"><i class="{{ $pendIcon }}"></i></div>
                <span class="cont-kpi-trend {{ $secTrend }}">
                    <i class="ri-arrow-{{ $secTrend === 'up' ? 'down' : 'up' }}-line"></i>
                    {{ number_format($secPctPend, 1) }}%
                </span>
            </div>
            <div class="cont-kpi-value">Bs. {{ number_format($secPend, 0, ',', '.') }}</div>
            <div class="cont-kpi-label">{{ $labels['pendiente'] }}</div>
        </div>
        <div class="cont-kpi-bar">
            <div class="cont-kpi-bar-fill" style="width:{{ min($secPctPend, 100) }}%; background:{{ $pendGrad }};"></div>
        </div>
    </div>
</div>

{{-- ── Charts row ── --}}
<div class="cont-charts-row">
    <div class="cont-chart-card" style="border-top: 3px solid #6366f1;">
        <div class="cont-chart-header">
            <div class="cont-chart-title"><i class="ri-pie-chart-2-line"></i> Distribución por Concepto</div>
            <span class="cont-chart-badge">{{ $labels['programado'] }}</span>
        </div>
        <div class="chart-doughnut-wrap">
            <canvas id="chartConceptos{{ $suffix }}"></canvas>
            <div class="chart-center-label">
                <div class="chart-center-value">Bs.&nbsp;{{ number_format($secProg, 0, ',', '.') }}</div>
                <div class="chart-center-sub">{{ $labels['programado'] }}</div>
            </div>
        </div>
        <div class="chart-legend-list" id="legendConceptos{{ $suffix }}"></div>
    </div>

    <div class="cont-chart-card" style="border-top: 3px solid #0d9488;">
        <div class="cont-chart-header">
            <div class="cont-chart-title"><i class="ri-bar-chart-horizontal-line"></i> Porcentaje de Cobro</div>
            <span class="cont-chart-badge">por concepto</span>
        </div>
        <div class="chart-hbars">
            @foreach ($conceptos as $concepto => $datos)
                @php
                    $pctBar  = $datos['total'] > 0 ? ($datos['pagado'] / $datos['total']) * 100 : 0;
                    $dotClr  = $colorConceptos[$concepto] ?? '#64748b';
                    $pctClr  = $pctBar >= 70 ? '#059669' : ($pctBar >= 40 ? '#d97706' : '#dc2626');
                    $barGrad = $pctBar >= 70
                        ? 'linear-gradient(90deg,#059669,#34d399)'
                        : ($pctBar >= 40 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)');
                    $twVal   = number_format(min($pctBar, 100), 2, '.', '');
                @endphp
                <div class="chart-hbar-item">
                    <div class="chart-hbar-meta">
                        <div class="chart-hbar-concept">
                            <span class="chart-hbar-concept-dot" style="background:{{ $dotClr }};"></span>
                            {{ $concepto }}
                        </div>
                        <div class="chart-hbar-right">
                            <span class="chart-hbar-pct" style="color:{{ $pctClr }};">{{ number_format($pctBar, 1) }}%</span>
                            <span class="chart-hbar-of">cobrado</span>
                        </div>
                    </div>
                    <div class="chart-hbar-track">
                        <div class="chart-hbar-fill" style="--tw:{{ $twVal }}%; background:{{ $barGrad }};"></div>
                    </div>
                    <div class="chart-hbar-amounts">
                        <span class="chart-hbar-cobrado" style="color:{{ $pctClr }};">Bs.&nbsp;{{ number_format($datos['pagado'], 0, ',', '.') }}</span>
                        <span class="chart-hbar-total">de Bs.&nbsp;{{ number_format($datos['total'], 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="cont-chart-card" style="border-top: 3px solid #059669;">
        <div class="cont-chart-header">
            <div class="cont-chart-title"><i class="ri-donut-chart-line"></i> Estado de Pagos</div>
            <span class="cont-chart-badge">Global</span>
        </div>
        <div class="chart-doughnut-wrap">
            <canvas id="chartEstado{{ $suffix }}"></canvas>
            <div class="chart-center-label">
                <div class="chart-estado-pct-big" style="color:{{ $secColorPct }};">{{ number_format($secPct, 1) }}%</div>
                <div class="chart-center-sub">{{ $labels['cobrado'] }}</div>
            </div>
        </div>
        <div class="chart-estado-cards">
            <div class="chart-estado-mini" style="border-left-color:#059669;">
                <div class="chart-estado-mini-value" style="color:#059669;">Bs.&nbsp;{{ number_format($secPag, 0, ',', '.') }}</div>
                <div class="chart-estado-mini-label"><i class="ri-checkbox-circle-line"></i> {{ $labels['cobrado'] }}</div>
            </div>
            <div class="chart-estado-mini" style="border-left-color:#dc2626;">
                <div class="chart-estado-mini-value" style="color:#dc2626;">Bs.&nbsp;{{ number_format($secPend, 0, ',', '.') }}</div>
                <div class="chart-estado-mini-label"><i class="{{ $pendIcon }}"></i> {{ $labels['pendiente'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Resumen por Concepto ── --}}
<h3 class="cont-section-title">Resumen por Concepto</h3>
<div class="rc-grid">
    @foreach ($conceptos as $concepto => $datos)
        @php
            $rcPct    = $datos['total'] > 0 ? ($datos['pagado'] / $datos['total']) * 100 : 0;
            $rcColor  = $colorConceptos[$concepto] ?? '#64748b';
            $rcPctClr = $rcPct >= 70 ? '#059669' : ($rcPct >= 40 ? '#d97706' : '#dc2626');
            $rcGrad   = $rcPct >= 70
                ? 'linear-gradient(90deg,#059669,#34d399)'
                : ($rcPct >= 40 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)');
            $rcTw     = number_format(min($rcPct, 100), 2, '.', '');
            $rcIcon   = $concepto === 'Matrícula' ? 'file-text-line' : ($concepto === 'Colegiatura' ? 'calendar-check-line' : 'award-line');
        @endphp
        <div class="rc-card">
            <div class="rc-card-accent" style="background: {{ $rcColor }};"></div>
            <div class="rc-card-body">
                <div class="rc-top">
                    <div class="rc-icon-wrap">
                        <div class="rc-icon" style="background:{{ $rcColor }}18; color:{{ $rcColor }};">
                            <i class="ri-{{ $rcIcon }}"></i>
                        </div>
                        <div>
                            <div class="rc-name">{{ $concepto }}</div>
                            <div class="rc-cuotas">
                                <i class="ri-coins-line"></i>
                                {{ $datos['cantidad_cuotas'] ?? 0 }} cuota(s)
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right; flex-shrink: 0;">
                        <div class="rc-pct-badge" style="color:{{ $rcPctClr }};">{{ number_format($rcPct, 1) }}%</div>
                        <div class="rc-pct-label">cobrado</div>
                    </div>
                </div>
                <div class="rc-track">
                    <div class="rc-fill" style="--tw:{{ $rcTw }}%; background:{{ $rcGrad }};"></div>
                </div>
                <div class="rc-stats">
                    <div class="rc-stat">
                        <div class="rc-stat-value">Bs.&nbsp;{{ number_format($datos['total'], 0, ',', '.') }}</div>
                        <div class="rc-stat-label">{{ $labels['programado'] }}</div>
                    </div>
                    <div class="rc-stat">
                        <div class="rc-stat-value" style="color:#059669;">Bs.&nbsp;{{ number_format($datos['pagado'], 0, ',', '.') }}</div>
                        <div class="rc-stat-label">{{ $labels['cobrado'] }}</div>
                    </div>
                    <div class="rc-stat">
                        <div class="rc-stat-value" style="color:#dc2626;">Bs.&nbsp;{{ number_format($datos['pendiente'], 0, ',', '.') }}</div>
                        <div class="rc-stat-label">{{ $labels['pendiente'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
