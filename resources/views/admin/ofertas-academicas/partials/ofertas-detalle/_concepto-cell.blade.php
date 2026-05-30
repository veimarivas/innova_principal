@if($c['total'] > 0)
    <div class="fin-concepto-cell">
        @if($c['pagado'] >= $c['total'])
            <span class="fin-concepto-amount ok">Bs {{ number_format($c['pagado'], 2) }}</span>
            <span class="fin-chip fin-chip-ok"><i class="ri-check-line"></i> Pagado</span>
        @elseif($c['pagado'] > 0)
            <span class="fin-concepto-amount parcial">Bs {{ number_format($c['pagado'], 2) }}</span>
            <span class="fin-concepto-sub saldo">–Bs {{ number_format($c['pendiente'], 2) }}</span>
            <span class="fin-chip fin-chip-pend"><i class="ri-time-line"></i> Parcial</span>
        @else
            <span class="fin-concepto-amount parcial" style="color:#94a3b8;">Bs {{ number_format($c['total'], 2) }}</span>
            <span class="fin-chip fin-chip-pend"><i class="ri-alarm-warning-line"></i> Pendiente</span>
        @endif
    </div>
@else
    <span class="fin-muted" style="display:block;text-align:right;">—</span>
@endif
