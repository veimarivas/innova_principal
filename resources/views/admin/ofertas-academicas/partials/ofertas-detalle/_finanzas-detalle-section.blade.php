{{--
    Partial reutilizable: KPIs + 3 gráficos + tarjetas Resumen por Concepto.
    Variables esperadas:
      $resumen           array  ['Matrícula' => ['total','pagado','pendiente','porcentaje'], ...]
      $suffix            string  sufijo para IDs únicos del DOM (ej: 'Completo', 'IngresosReales', 'Retirados')
      $kpiCountValue     int
      $kpiCountLabel     string
      $kpiCountIconBg    string  (rgba)
      $kpiCountIconColor string
      $kpiCountIcon      string  (clase remix)
      $labels            array   ['programado','cobrado','pendiente'] textos
      $pendienteVariant  string  'normal' (rojo simple) | 'perdida' (rojo oscuro)
      $colorMap          array
      $iconMap           array
--}}
@php
    $secTotalProg = collect($resumen)->sum('total');
    $secTotalPag  = collect($resumen)->sum('pagado');
    $secTotalPend = collect($resumen)->sum('pendiente');
    $secPct       = $secTotalProg > 0 ? ($secTotalPag / $secTotalProg) * 100 : 0;
    $secPctPend   = $secTotalProg > 0 ? ($secTotalPend / $secTotalProg) * 100 : 0;
    $secTrendCls  = $secPct >= 50 ? 'up' : 'down';
    $pendGrad     = ($pendienteVariant ?? 'normal') === 'perdida'
        ? 'linear-gradient(90deg,#991b1b,#dc2626)'
        : 'linear-gradient(90deg,#dc2626,#f87171)';
    $pendIcon     = ($pendienteVariant ?? 'normal') === 'perdida' ? 'ri-close-circle-line' : 'ri-time-line';

    // Distribución por plan de pago y sexo dentro de la población de este apartado
    $participantes = $participantes ?? [];
    $distPlan = [];
    $distSexo = ['M' => 0, 'F' => 0, 'Otro' => 0];
    foreach ($participantes as $p) {
        $nombrePlan = $p['plan_pago'] ?? 'Sin plan';
        if (!isset($distPlan[$nombrePlan])) $distPlan[$nombrePlan] = 0;
        $distPlan[$nombrePlan]++;

        $sx = strtoupper(trim((string) ($p['sexo'] ?? '')));
        if ($sx === 'M') $distSexo['M']++;
        elseif ($sx === 'F') $distSexo['F']++;
        else $distSexo['Otro']++;
    }
    if ($distSexo['Otro'] === 0) unset($distSexo['Otro']);
    arsort($distPlan);
    $totalParticipantes = count($participantes);
@endphp

{{-- ── KPI Cards ── --}}
<div class="fin-kpi-grid">
    {{-- KPI 1: contador --}}
    <div class="fin-kpi-card kpi-ins">
        <div class="fin-kpi-body">
            <div class="fin-kpi-header">
                <div class="fin-kpi-icon ins" style="background:{{ $kpiCountIconBg }};color:{{ $kpiCountIconColor }};">
                    <i class="{{ $kpiCountIcon }}"></i>
                </div>
                <span class="fin-kpi-trend {{ $secTrendCls }}">
                    <i class="ri-arrow-{{ $secTrendCls === 'up' ? 'up' : 'down' }}-line"></i>
                    {{ number_format($secPct, 1) }}%
                </span>
            </div>
            <div class="fin-kpi-value">{{ $kpiCountValue }}</div>
            <div class="fin-kpi-label">{{ $kpiCountLabel }}</div>
        </div>
        <div class="fin-kpi-bar">
            <div class="fin-kpi-bar-fill" style="width:{{ min($secPct,100) }}%;background:linear-gradient(90deg,{{ $kpiCountIconColor }},{{ $kpiCountIconColor }}aa);"></div>
        </div>
    </div>

    {{-- KPI 2: Programado --}}
    <div class="fin-kpi-card kpi-prog">
        <div class="fin-kpi-body">
            <div class="fin-kpi-header">
                <div class="fin-kpi-icon prog"><i class="ri-calculator-line"></i></div>
            </div>
            <div class="fin-kpi-value">Bs. {{ number_format($secTotalProg, 0, ',', '.') }}</div>
            <div class="fin-kpi-label">{{ $labels['programado'] }}</div>
        </div>
        <div class="fin-kpi-bar">
            <div class="fin-kpi-bar-fill" style="width:100%;background:linear-gradient(90deg,#64748b,#94a3b8);"></div>
        </div>
    </div>

    {{-- KPI 3: Cobrado --}}
    <div class="fin-kpi-card kpi-pag">
        <div class="fin-kpi-body">
            <div class="fin-kpi-header">
                <div class="fin-kpi-icon pag"><i class="ri-checkbox-circle-line"></i></div>
                <span class="fin-kpi-trend up">
                    <i class="ri-arrow-up-line"></i>
                    {{ number_format($secPct, 1) }}%
                </span>
            </div>
            <div class="fin-kpi-value">Bs. {{ number_format($secTotalPag, 0, ',', '.') }}</div>
            <div class="fin-kpi-label">{{ $labels['cobrado'] }}</div>
        </div>
        <div class="fin-kpi-bar">
            <div class="fin-kpi-bar-fill" style="width:{{ min($secPct,100) }}%;background:linear-gradient(90deg,#059669,#10b981);"></div>
        </div>
    </div>

    {{-- KPI 4: Pendiente / Perdido --}}
    <div class="fin-kpi-card kpi-pend">
        <div class="fin-kpi-body">
            <div class="fin-kpi-header">
                <div class="fin-kpi-icon pend"><i class="{{ $pendIcon }}"></i></div>
                <span class="fin-kpi-trend {{ $secTrendCls }}">
                    <i class="ri-arrow-{{ $secTrendCls === 'up' ? 'down' : 'up' }}-line"></i>
                    {{ number_format($secPctPend, 1) }}%
                </span>
            </div>
            <div class="fin-kpi-value">Bs. {{ number_format($secTotalPend, 0, ',', '.') }}</div>
            <div class="fin-kpi-label">{{ $labels['pendiente'] }}</div>
        </div>
        <div class="fin-kpi-bar">
            <div class="fin-kpi-bar-fill" style="width:{{ min($secPctPend,100) }}%;background:{{ $pendGrad }};"></div>
        </div>
    </div>
</div>

{{-- ── Charts Row ── --}}
<div class="fin-charts-row">
    {{-- 1. Distribución por Concepto (Donut) --}}
    <div class="fin-chart-card" style="border-top:3px solid #6366f1;">
        <div class="fin-chart-header">
            <div class="fin-chart-title"><i class="ri-pie-chart-2-line"></i> Distribución por Concepto</div>
            <span class="fin-chart-badge">{{ $labels['programado'] }}</span>
        </div>
        <div class="fin-doughnut-wrap">
            <canvas id="finChartConceptos{{ $suffix }}" data-fin-chart-suffix="{{ $suffix }}"></canvas>
            <div class="fin-center-label">
                <div class="fin-center-value">Bs.&nbsp;{{ number_format($secTotalProg, 0, ',', '.') }}</div>
                <div class="fin-center-sub">{{ $labels['programado'] }}</div>
            </div>
        </div>
        <div class="fin-legend-list" id="finLegendConceptos{{ $suffix }}"></div>
    </div>

    {{-- 2. Porcentaje de Cobro (CSS barras) --}}
    <div class="fin-chart-card" style="border-top:3px solid #0d9488;">
        <div class="fin-chart-header">
            <div class="fin-chart-title"><i class="ri-bar-chart-horizontal-line"></i> Porcentaje de Cobro</div>
            <span class="fin-chart-badge">por concepto</span>
        </div>
        <div class="fin-hbars">
            @foreach ($resumen as $concepto => $datos)
                @php
                    $pctBar  = $datos['total'] > 0 ? ($datos['pagado'] / $datos['total']) * 100 : 0;
                    $dotClr  = $colorMap[$concepto] ?? '#64748b';
                    $pctClr  = $pctBar >= 70 ? '#059669' : ($pctBar >= 40 ? '#d97706' : '#dc2626');
                    $barGrad = $pctBar >= 70
                        ? 'linear-gradient(90deg,#059669,#34d399)'
                        : ($pctBar >= 40 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)');
                    $twVal   = number_format(min($pctBar, 100), 2, '.', '');
                @endphp
                <div>
                    <div class="fin-hbar-meta">
                        <div class="fin-hbar-concept">
                            <span class="fin-hbar-dot" style="background:{{ $dotClr }};"></span>
                            {{ $concepto }}
                        </div>
                        <div class="fin-hbar-right">
                            <span class="fin-hbar-pct" style="color:{{ $pctClr }};">{{ number_format($pctBar, 1) }}%</span>
                            <span class="fin-hbar-of">cobrado</span>
                        </div>
                    </div>
                    <div class="fin-hbar-track">
                        <div class="fin-hbar-fill" style="--tw:{{ $twVal }}%;background:{{ $barGrad }};"></div>
                    </div>
                    <div class="fin-hbar-amounts">
                        <span class="fin-hbar-cobrado" style="color:{{ $pctClr }};">Bs.&nbsp;{{ number_format($datos['pagado'], 0, ',', '.') }}</span>
                        <span class="fin-hbar-total">de Bs.&nbsp;{{ number_format($datos['total'], 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 3. Estado de Pagos (Donut) --}}
    <div class="fin-chart-card" style="border-top:3px solid #059669;">
        <div class="fin-chart-header">
            <div class="fin-chart-title"><i class="ri-donut-chart-line"></i> Estado de Pagos</div>
            <span class="fin-chart-badge">Global</span>
        </div>
        @php
            $secColorPct = $secPct >= 70 ? '#059669' : ($secPct >= 40 ? '#d97706' : '#dc2626');
        @endphp
        <div class="fin-doughnut-wrap">
            <canvas id="finChartEstado{{ $suffix }}" data-fin-estado-suffix="{{ $suffix }}"></canvas>
            <div class="fin-center-label">
                <div class="fin-estado-pct-big" style="color:{{ $secColorPct }};">{{ number_format($secPct, 1) }}%</div>
                <div class="fin-center-sub">{{ $labels['cobrado'] }}</div>
            </div>
        </div>
        <div class="fin-estado-cards">
            <div class="fin-estado-mini" style="border-left-color:#059669;">
                <div class="fin-estado-mini-value" style="color:#059669;">Bs.&nbsp;{{ number_format($secTotalPag, 0, ',', '.') }}</div>
                <div class="fin-estado-mini-label"><i class="ri-checkbox-circle-line"></i> {{ $labels['cobrado'] }}</div>
            </div>
            <div class="fin-estado-mini" style="border-left-color:#dc2626;">
                <div class="fin-estado-mini-value" style="color:#dc2626;">Bs.&nbsp;{{ number_format($secTotalPend, 0, ',', '.') }}</div>
                <div class="fin-estado-mini-label"><i class="{{ $pendIcon }}"></i> {{ $labels['pendiente'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Distribución de Estudiantes (Planes / Sexo) ── --}}
@if ($totalParticipantes > 0)
<h3 class="fin-section-title">Distribución de Estudiantes</h3>
<div class="fin-dist-row">
    {{-- Planes de pago --}}
    <div class="fin-chart-card" style="border-top:3px solid #8b5cf6;">
        <div class="fin-chart-header">
            <div class="fin-chart-title"><i class="ri-wallet-3-line"></i> Planes de Pago</div>
            <span class="fin-chart-badge">{{ count($distPlan) }} plan(es)</span>
        </div>
        <div style="position:relative;height:240px;">
            <canvas id="finChartPlanes{{ $suffix }}"></canvas>
        </div>
        <div class="fin-dist-legend" id="finPlanesLegend{{ $suffix }}"></div>
    </div>

    {{-- Sexo --}}
    <div class="fin-chart-card" style="border-top:3px solid #ec4899;">
        <div class="fin-chart-header">
            <div class="fin-chart-title"><i class="ri-group-line"></i> Distribución por Sexo</div>
            <span class="fin-chart-badge">{{ $totalParticipantes }} estudiante(s)</span>
        </div>
        <div class="fin-doughnut-wrap" style="height:200px;">
            <canvas id="finChartSexo{{ $suffix }}"></canvas>
            <div class="fin-center-label">
                <div class="fin-center-value">{{ $totalParticipantes }}</div>
                <div class="fin-center-sub">Total</div>
            </div>
        </div>
        <div class="fin-sexo-legend">
            @foreach ($distSexo as $sx => $cant)
                @php
                    $sxColor = $sx === 'M' ? '#3b82f6' : ($sx === 'F' ? '#ec4899' : '#94a3b8');
                    $sxIcon  = $sx === 'M' ? 'ri-men-line' : ($sx === 'F' ? 'ri-women-line' : 'ri-user-3-line');
                    $sxLabel = $sx === 'M' ? 'Masculino' : ($sx === 'F' ? 'Femenino' : 'Otro');
                    $sxPct   = $totalParticipantes > 0 ? round($cant / $totalParticipantes * 100, 1) : 0;
                @endphp
                <div class="fin-sexo-card" style="border-left:3px solid {{ $sxColor }};">
                    <div class="fin-sexo-card-icon" style="background:{{ $sxColor }}18;color:{{ $sxColor }};"><i class="{{ $sxIcon }}"></i></div>
                    <div style="flex:1;">
                        <div class="fin-sexo-card-value">{{ $cant }}</div>
                        <div class="fin-sexo-card-label">{{ $sxLabel }}</div>
                    </div>
                    <div class="fin-sexo-card-pct" style="color:{{ $sxColor }};">{{ $sxPct }}%</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<script type="application/json" id="finDistData{{ $suffix }}">@json([
    'planes' => $distPlan,
    'sexo' => $distSexo,
])</script>
@endif

{{-- ── Resumen por Concepto ── --}}
<h3 class="fin-section-title">Resumen por Concepto</h3>
<div class="fin-rc-grid">
    @foreach ($resumen as $concepto => $datos)
        @php
            $rcPct    = $datos['total'] > 0 ? ($datos['pagado'] / $datos['total']) * 100 : 0;
            $rcColor  = $colorMap[$concepto] ?? '#64748b';
            $rcPctClr = $rcPct >= 70 ? '#059669' : ($rcPct >= 40 ? '#d97706' : '#dc2626');
            $rcGrad   = $rcPct >= 70
                ? 'linear-gradient(90deg,#059669,#34d399)'
                : ($rcPct >= 40 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)');
            $rcTw     = number_format(min($rcPct, 100), 2, '.', '');
            $rcIcon   = $iconMap[$concepto] ?? 'ri-money-dollar-circle-line';
        @endphp
        <div class="fin-rc-card">
            <div class="fin-rc-accent" style="background:{{ $rcColor }};"></div>
            <div class="fin-rc-body">
                <div class="fin-rc-top">
                    <div class="fin-rc-icon-wrap">
                        <div class="fin-rc-icon" style="background:{{ $rcColor }}18;color:{{ $rcColor }};">
                            <i class="{{ $rcIcon }}"></i>
                        </div>
                        <div>
                            <div class="fin-rc-name">{{ $concepto }}</div>
                            <div class="fin-rc-sub">
                                <i class="ri-coins-line"></i>
                                {{ number_format($datos['total'], 0, ',', '.') }} Bs. {{ strtolower($labels['programado']) }}
                            </div>
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div class="fin-rc-pct-badge" style="color:{{ $rcPctClr }};">{{ number_format($rcPct, 1) }}%</div>
                        <div class="fin-rc-pct-label">cobrado</div>
                    </div>
                </div>
                <div class="fin-rc-track">
                    <div class="fin-rc-fill" style="--tw:{{ $rcTw }}%;background:{{ $rcGrad }};"></div>
                </div>
                <div class="fin-rc-stats">
                    <div class="fin-rc-stat">
                        <div class="fin-rc-stat-value">Bs.&nbsp;{{ number_format($datos['total'], 0, ',', '.') }}</div>
                        <div class="fin-rc-stat-label">{{ $labels['programado'] }}</div>
                    </div>
                    <div class="fin-rc-stat">
                        <div class="fin-rc-stat-value" style="color:#059669;">Bs.&nbsp;{{ number_format($datos['pagado'], 0, ',', '.') }}</div>
                        <div class="fin-rc-stat-label">{{ $labels['cobrado'] }}</div>
                    </div>
                    <div class="fin-rc-stat">
                        <div class="fin-rc-stat-value" style="color:#dc2626;">Bs.&nbsp;{{ number_format($datos['pendiente'], 0, ',', '.') }}</div>
                        <div class="fin-rc-stat-label">{{ $labels['pendiente'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
