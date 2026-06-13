{{--
    Partial: grid de 3 bloques de concepto (Matrícula/Colegiatura/Certificación) para una oferta.
    Variables esperadas:
      $resumen        array
      $colorConceptos array
      $labels         array ['cobrado','pendiente','programado']
      $pendienteVariant 'normal' | 'perdida'
--}}
<div class="oferta-conceptos-grid">
    @foreach (['Matrícula', 'Colegiatura', 'Certificación'] as $concepto)
        @php
            $cbDatos  = $resumen[$concepto] ?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'cantidad_cuotas' => 0, 'cuotas' => []];
            $cbColor  = $colorConceptos[$concepto] ?? '#64748b';
            $cbPct    = $cbDatos['total'] > 0 ? ($cbDatos['pagado'] / $cbDatos['total']) * 100 : 0;
            $cbPctClr = $cbPct >= 70 ? '#059669' : ($cbPct >= 40 ? '#d97706' : '#dc2626');
            $cbGrad   = $cbPct >= 70
                ? 'linear-gradient(90deg,#059669,#34d399)'
                : ($cbPct >= 40 ? 'linear-gradient(90deg,#d97706,#fbbf24)' : 'linear-gradient(90deg,#dc2626,#f87171)');
            $cbTw     = number_format(min($cbPct, 100), 2, '.', '');
            $cbIcon   = $concepto === 'Matrícula' ? 'file-text-line' : ($concepto === 'Colegiatura' ? 'calendar-check-line' : 'award-line');
            $cbEmpty  = $cbDatos['total'] == 0;
            $pendClr  = ($pendienteVariant ?? 'normal') === 'perdida' ? '#991b1b' : '#dc2626';
        @endphp
        <div class="oferta-concepto-block {{ $cbEmpty ? 'oferta-empty-concepto' : '' }}">
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:{{ $cbColor }};border-radius:0;"></div>
            <div class="oferta-cb-header">
                <div class="oferta-cb-icon" style="background:{{ $cbColor }}18; color:{{ $cbColor }};">
                    <i class="ri-{{ $cbIcon }}"></i>
                </div>
                <div>
                    <div class="oferta-cb-name">{{ $concepto }}</div>
                    <div class="oferta-cb-cuotas">
                        <i class="ri-coins-line"></i>
                        {{ $cbDatos['cantidad_cuotas'] ?? count($cbDatos['cuotas'] ?? []) }} cuota(s)
                    </div>
                </div>
            </div>
            @if (!$cbEmpty)
                <div class="oferta-cb-pct-row">
                    <div class="oferta-cb-pct" style="color:{{ $cbPctClr }};">{{ number_format($cbPct, 1) }}%</div>
                    <div class="oferta-cb-pct-label">cobrado</div>
                </div>
                <div class="oferta-cb-track">
                    <div class="oferta-cb-fill" style="--tw:{{ $cbTw }}%; background:{{ $cbGrad }};"></div>
                </div>
                <div class="oferta-cb-stats">
                    <div class="oferta-cb-stat">
                        <div class="oferta-cb-stat-value" style="color:#059669;">Bs.&nbsp;{{ number_format($cbDatos['pagado'], 0, ',', '.') }}</div>
                        <div class="oferta-cb-stat-label">{{ $labels['cobrado'] }}</div>
                    </div>
                    <div class="oferta-cb-stat">
                        <div class="oferta-cb-stat-value" style="color:{{ $pendClr }};">Bs.&nbsp;{{ number_format($cbDatos['pendiente'], 0, ',', '.') }}</div>
                        <div class="oferta-cb-stat-label">{{ $labels['pendiente'] }}</div>
                    </div>
                    <div class="oferta-cb-stat full">
                        <div class="oferta-cb-stat-value">Bs.&nbsp;{{ number_format($cbDatos['total'], 0, ',', '.') }}</div>
                        <div class="oferta-cb-stat-label">Total {{ strtolower($labels['programado']) }}</div>
                    </div>
                </div>
            @else
                <div class="oferta-no-data">
                    <i class="ri-inbox-line"></i>
                    Sin registros
                </div>
            @endif
        </div>
    @endforeach
</div>
