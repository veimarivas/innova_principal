<!-- Tab: Contable -->
<style>
.ctb-stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.ctb-stat {
    background: #fff;
    border: 1px solid #e9e2d9;
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: .9rem;
    transition: all .25s ease;
    box-shadow: 0 2px 12px -4px rgba(0,0,0,.04);
}
.ctb-stat:hover {
    box-shadow: 0 6px 24px -8px rgba(0,0,0,.1);
    transform: translateY(-2px);
}
.ctb-stat-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.ctb-stat-icon.success { background: rgba(34,197,94,.12); color: #15803d; }
.ctb-stat-icon.warning { background: rgba(245,158,11,.12); color: #b45309; }
.ctb-stat-icon.danger  { background: rgba(239,68,68,.12); color: #dc2626; }
.ctb-stat-val {
    font-size: 1.2rem;
    font-weight: 800;
    line-height: 1.2;
    font-family: Outfit, sans-serif;
}
.ctb-stat-val.success { color: #15803d; }
.ctb-stat-val.warning { color: #b45309; }
.ctb-stat-val.danger  { color: #dc2626; }
.ctb-stat-lbl {
    font-size: .72rem;
    color: #7b6f62;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-top: 1px;
}

.ctb-oferta-card {
    background: #fff;
    border: 1px solid #e9e2d9;
    border-radius: 16px;
    overflow: visible;
    box-shadow: 0 4px 20px -6px rgba(0,0,0,.06);
    margin-bottom: 1.25rem;
}
.ctb-oferta-card:last-child { margin-bottom: 0; }

.ctb-oferta-head {
    display: flex;
    align-items: center;
    gap: .85rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f0ebe4;
}
.ctb-oferta-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: rgba(252,123,4,.1);
    color: var(--est-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.ctb-oferta-name {
    font-size: .95rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.3;
}
.ctb-oferta-plan {
    font-size: .72rem;
    color: #7b6f62;
}
.ctb-oferta-head-right {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: .75rem;
}

.ctb-sub-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    border-bottom: 1px solid #f0ebe4;
}
.ctb-sub-stat {
    padding: .75rem 1.25rem;
    display: flex;
    align-items: center;
    gap: .7rem;
    border-right: 1px solid #f0ebe4;
}
.ctb-sub-stat:last-child { border-right: none; }
.ctb-sub-stat-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.ctb-sub-stat-icon.s-green { background: rgba(34,197,94,.1); color: #15803d; }
.ctb-sub-stat-icon.s-yellow { background: rgba(245,158,11,.1); color: #b45309; }
.ctb-sub-stat-icon.s-red { background: rgba(239,68,68,.1); color: #dc2626; }
.ctb-sub-stat-val {
    font-weight: 700;
    font-size: .88rem;
    font-family: Outfit, sans-serif;
}
.ctb-sub-stat-val.green { color: #15803d; }
.ctb-sub-stat-val.yellow { color: #b45309; }
.ctb-sub-stat-val.red { color: #dc2626; }
.ctb-sub-stat-lbl {
    font-size: .62rem;
    color: #7b6f62;
    text-transform: uppercase;
    letter-spacing: .04em;
    font-weight: 600;
}

.ctb-cuotas-wrap {
    padding: .25rem 0;
}
.ctb-cuotas-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .85rem 1.25rem .65rem;
}
.ctb-cuotas-title {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #7b6f62;
    display: flex;
    align-items: center;
    gap: 6px;
}
.ctb-btn-masivo {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: .72rem;
    font-weight: 600;
    border: 1px solid #e9e2d9;
    background: #fff;
    color: var(--est-primary);
    cursor: pointer;
    transition: all .2s ease;
}
.ctb-btn-masivo:hover {
    background: rgba(252,123,4,.08);
    border-color: var(--est-primary);
}

.ctb-table {
    width: 100%;
    border-collapse: collapse;
}
.ctb-table thead th {
    background: #f8f5f1;
    padding: .6rem .85rem;
    font-size: .62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #7b6f62;
    border-bottom: 1px solid #e9e2d9;
    text-align: left;
    white-space: nowrap;
}
.ctb-table thead th:first-child { width: 36px; text-align: center; }
.ctb-table thead th:last-child { width: 80px; text-align: center; }
.ctb-table tbody tr {
    transition: background .2s ease;
}
.ctb-table tbody tr:hover {
    background: rgba(252,123,4,.035);
}
.ctb-table tbody td {
    padding: .75rem .85rem;
    vertical-align: middle;
    border-bottom: 1px solid #f0ebe4;
    font-size: .82rem;
    color: #1e293b;
}
.ctb-table tbody tr:last-child td { border-bottom: none; }
.ctb-table .text-center { text-align: center; }
.ctb-table .text-end { text-align: right; }

.ctb-num-badge {
    width: 26px; height: 26px;
    border-radius: 7px;
    background: #f0ebe4;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: .7rem;
    color: #7b6f62;
}

.ctb-monto {
    font-weight: 600;
    color: #1e293b;
    font-family: Outfit, sans-serif;
}

.ctb-venc-wrap {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.ctb-venc-wrap .fecha {
    font-weight: 500;
    font-size: .8rem;
}
.ctb-venc-wrap .dias {
    font-size: .64rem;
    font-weight: 600;
}
.ctb-venc-wrap.vencido .fecha { color: #dc2626; font-weight: 700; }
.ctb-venc-wrap.vencido .dias { color: #dc2626; }
.ctb-venc-wrap.ok .fecha { color: #22a34a; font-weight: 600; }
.ctb-venc-wrap.ok .dias { color: #22a34a; }

.ctb-pago-fecha {
    font-size: .78rem;
    color: #475569;
}

.ctb-total-pagado {
    font-weight: 700;
    color: #15803d;
    font-family: Outfit, sans-serif;
}

.ctb-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: .66rem;
    font-weight: 700;
    letter-spacing: .02em;
    width: fit-content;
}
.ctb-pill.pagado {
    background: rgba(34,197,94,.1);
    color: #15803d;
}
.ctb-pill.vencido {
    background: rgba(239,68,68,.1);
    color: #dc2626;
}
.ctb-pill.pendiente {
    background: rgba(245,158,11,.1);
    color: #b45309;
}

.ctb-actions {
    display: flex;
    gap: 5px;
    justify-content: center;
}
.ctb-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px; height: 30px;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    transition: all .2s ease;
    font-size: .9rem;
}
.ctb-action.pay {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
}
.ctb-action.pay:hover {
    transform: scale(1.12);
    box-shadow: 0 4px 14px rgba(34,197,94,.4);
}
.ctb-action.view {
    background: #f0ebe4;
    color: #7b6f62;
}
.ctb-action.view:hover {
    background: #e4ddd4;
    color: #4a3f34;
    transform: scale(1.08);
}

.ctb-tabs {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
    margin-bottom: 1rem;
    background: #f8f5f1;
    border-radius: 12px;
    padding: 4px;
    border: 1px solid #e9e2d9;
}
.ctb-tab {
    padding: .5rem 1rem;
    border-radius: 9px;
    font-size: .78rem;
    font-weight: 600;
    color: #7b6f62;
    border: none;
    background: transparent;
    cursor: pointer;
    transition: all .2s ease;
    display: flex;
    align-items: center;
    gap: 5px;
}
.ctb-tab:hover { color: var(--est-primary); }
.ctb-tab.active {
    background: #fff;
    color: var(--est-primary);
    box-shadow: 0 2px 8px -2px rgba(0,0,0,.08);
}
</style>
<div class="est-tabs-body" id="tab-contable">
    @php
        $totalPagado = 0;
        $totalPendiente = 0;
        $totalVencido = 0;
        foreach ($inscripciones as $ins) {
            foreach ($ins->cuotas as $cuota) {
                $pagadoEnCuota = 0;
                foreach ($cuota->pagosCuota as $pc) {
                    $pagadoEnCuota += $pc->monto_bs ?? 0;
                }
                if ($pagadoEnCuota > 0) {
                    $totalPagado += $pagadoEnCuota;
                }
                $pendiente = $cuota->monto_bs - $pagadoEnCuota;
                if ($pendiente > 0) {
                    if ($cuota->estado == 'Vencido') {
                        $totalVencido += $pendiente;
                    } else {
                        $totalPendiente += $pendiente;
                    }
                }
            }
        }
    @endphp
    <div class="ctb-stats-row">
        <div class="ctb-stat">
            <div class="ctb-stat-icon success"><i class="ri-checkbox-circle-line"></i></div>
            <div>
                <div class="ctb-stat-val success">Bs. {{ number_format($totalPagado, 2) }}</div>
                <div class="ctb-stat-lbl">Total Pagado</div>
            </div>
        </div>
        <div class="ctb-stat">
            <div class="ctb-stat-icon warning"><i class="ri-time-line"></i></div>
            <div>
                <div class="ctb-stat-val warning">Bs. {{ number_format($totalPendiente, 2) }}</div>
                <div class="ctb-stat-lbl">Pendiente</div>
            </div>
        </div>
        <div class="ctb-stat">
            <div class="ctb-stat-icon danger"><i class="ri-alert-line"></i></div>
            <div>
                <div class="ctb-stat-val danger">Bs. {{ number_format($totalVencido, 2) }}</div>
                <div class="ctb-stat-lbl">Vencido</div>
            </div>
        </div>
    </div>

    @if ($inscripciones->count() > 0)
        <div class="ctb-tabs">
            @foreach ($inscripciones as $key => $ins)
                <button type="button" class="ctb-tab {{ $key == 0 ? 'active' : '' }}"
                    data-target="contable-oferta-{{ $key }}">
                    <i class="ri-money-dollar-circle-line"></i>
                    {{ $ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta ' . ($key + 1) }}
                </button>
            @endforeach
        </div>
        @foreach ($inscripciones as $key => $ins)
            <div class="est-oferta-content {{ $key == 0 ? 'active' : '' }}"
                id="contable-oferta-{{ $key }}">
                <div class="ctb-oferta-card">
                    <div class="ctb-oferta-head">
                        <div class="ctb-oferta-icon"><i class="ri-money-dollar-circle-line"></i></div>
                        <div>
                            <div class="ctb-oferta-name">
                                {{ $ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta #' . $ins->ofertas_academica_id }}
                            </div>
                            <div class="ctb-oferta-plan">Plan: {{ $ins->planesPago?->nombre ?? '—' }}</div>
                        </div>
                    </div>
                    @php
                        $insPagado = 0;
                        $insPendiente = 0;
                        $insVencido = 0;
                        foreach ($ins->cuotas as $cuota) {
                            $pagadoEnCuota = 0;
                            foreach ($cuota->pagosCuota as $pc) {
                                $pagadoEnCuota += $pc->monto_bs ?? 0;
                            }
                            if ($pagadoEnCuota > 0) {
                                $insPagado += $pagadoEnCuota;
                            }
                            $pendiente = $cuota->monto_bs - $pagadoEnCuota;
                            if ($pendiente > 0) {
                                if ($cuota->estado == 'Vencido') {
                                    $insVencido += $pendiente;
                                } else {
                                    $insPendiente += $pendiente;
                                }
                            }
                        }
                    @endphp
                    <div class="ctb-sub-stats">
                        <div class="ctb-sub-stat">
                            <div class="ctb-sub-stat-icon s-green"><i class="ri-checkbox-circle-line"></i></div>
                            <div>
                                <div class="ctb-sub-stat-val green">Bs. {{ number_format($insPagado, 2) }}</div>
                                <div class="ctb-sub-stat-lbl">Pagado</div>
                            </div>
                        </div>
                        <div class="ctb-sub-stat">
                            <div class="ctb-sub-stat-icon s-yellow"><i class="ri-time-line"></i></div>
                            <div>
                                <div class="ctb-sub-stat-val yellow">Bs. {{ number_format($insPendiente, 2) }}</div>
                                <div class="ctb-sub-stat-lbl">Pendiente</div>
                            </div>
                        </div>
                        <div class="ctb-sub-stat">
                            <div class="ctb-sub-stat-icon s-red"><i class="ri-alert-line"></i></div>
                            <div>
                                <div class="ctb-sub-stat-val red">Bs. {{ number_format($insVencido, 2) }}</div>
                                <div class="ctb-sub-stat-lbl">Vencido</div>
                            </div>
                        </div>
                    </div>
                    @if ($ins->cuotas && $ins->cuotas->count() > 0)
                        <div class="ctb-cuotas-wrap">
                            <div class="ctb-cuotas-header">
                                <span class="ctb-cuotas-title"><i class="ri-install-line"></i> Cuotas ({{ $ins->cuotas->count() }})</span>
                                @php
                                    $cuotasPendientes = $ins->cuotas->filter(fn($c) => $c->estado !== 'Pagado')->values();
                                    $cuotasData = $cuotasPendientes->map(fn($c) => [
                                        'id' => $c->id,
                                        'n_cuota' => $c->n_cuota,
                                        'nombre' => $c->nombre,
                                        'monto_bs' => $c->monto_bs,
                                        'pago_pendiente_bs' => $c->pago_pendiente_bs ?? $c->monto_bs,
                                        'fecha_vencimiento' => $c->fecha_vencimiento,
                                        'estado' => $c->estado
                                    ])->toArray();
                                @endphp
                                <button type="button" class="ctb-btn-masivo btn-pago-masivo"
                                    data-inscripcion-id="{{ $ins->id }}"
                                    data-oferta="{{ $ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta #' . $ins->ofertas_academica_id }}"
                                    data-cuotas='{{ json_encode($cuotasData) }}'
                                    title="Registro Masivo">
                                    <i class="ri-file-list-3-line"></i> Pago Múltiple
                                </button>
                            </div>
                            <table class="ctb-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cuota</th>
                                        <th>Monto</th>
                                        <th>Descuento</th>
                                        <th>Pendiente</th>
                                        <th>Vencimiento</th>
                                        <th>Pago</th>
                                        <th>Total Pagado</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ins->cuotas as $cuota)
                                        @php
                                            $totalPagadoCuota = 0;
                                            foreach ($cuota->pagosCuota as $pc) {
                                                $totalPagadoCuota += $pc->monto_bs ?? 0;
                                            }
                                            $vencDate = $cuota->fecha_vencimiento ? \Carbon\Carbon::parse($cuota->fecha_vencimiento)->startOfDay() : null;
                                            $hoy = \Carbon\Carbon::now()->startOfDay();
                                            
                                            $diasFaltantes = null;
                                            if ($vencDate && $hoy) {
                                                $diasFaltantes = (int) $hoy->diffInDays($vencDate, false);
                                            }
                                            
                                            $claseVenc = '';
                                            if ($cuota->estado == 'Pagado') {
                                                $claseVenc = '';
                                            } elseif ($diasFaltantes !== null && $diasFaltantes < 0) {
                                                $claseVenc = 'vencido';
                                            } else {
                                                $claseVenc = 'ok';
                                            }
                                            $estadoClass = $cuota->estado == 'Pagado' ? 'pagado' : ($cuota->estado == 'Vencido' ? 'vencido' : 'pendiente');
                                        @endphp
                                        <tr>
                                            <td class="text-center"><span class="ctb-num-badge">{{ $cuota->n_cuota }}</span></td>
                                            <td><span style="font-weight:500;">{{ $cuota->nombre }}</span></td>
                                            <td><span class="ctb-monto">Bs. {{ number_format($cuota->monto_bs, 2) }}</span></td>
                                            <td>{{ $cuota->descuento_bs > 0 ? 'Bs. ' . number_format($cuota->descuento_bs, 2) : '—' }}</td>
                                            <td><span class="ctb-monto" style="color:#b45309;">Bs. {{ number_format($cuota->pago_pendiente_bs ?? $cuota->monto_bs, 2) }}</span></td>
                                            <td>
                                                <div class="ctb-venc-wrap {{ $claseVenc }}">
                                                    <span class="fecha">{{ $vencDate ? $vencDate->format('d/m/Y') : '—' }}</span>
                                                    @if($diasFaltantes !== null && $cuota->estado != 'Pagado')
                                                        <span class="dias">
                                                            @if($diasFaltantes < 0)
                                                                {{ abs($diasFaltantes) }} días vencido
                                                            @elseif($diasFaltantes == 0)
                                                                Vence hoy
                                                            @else
                                                                {{ $diasFaltantes }} días faltantes
                                                            @endif
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="ctb-pago-fecha">
                                                    @if ($cuota->estado != 'Pagado')
                                                        —
                                                    @else
                                                        {{ $cuota->fecha_pago ? \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') : '—' }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td><span class="ctb-total-pagado">Bs. {{ number_format($totalPagadoCuota, 2) }}</span></td>
                                            <td><span class="ctb-pill {{ $estadoClass }}">{{ $cuota->estado }}</span></td>
                                            <td class="text-center">
                                                @php
                                                    $pagosData = [];
                                                    $pagosCuotas = $cuota->pagosCuota;
                                                    foreach ($pagosCuotas as $pc) {
                                                        if ($pc->pago) {
                                                            $pago = $pc->pago;
                                                            $trabajadorNombre =
                                                                $pago->trabajadorCargo &&
                                                                $pago->trabajadorCargo->trabajador &&
                                                                $pago->trabajadorCargo->trabajador->persona
                                                                    ? $pago->trabajadorCargo->trabajador->persona->nombres .
                                                                        ' ' .
                                                                        $pago->trabajadorCargo->trabajador->persona->apellido_paterno
                                                                    : '—';
                                                            $comprobante = null;
                                                            $cuotaIds = $pago->pagosCuotas->pluck('cuota_id')->toArray();
                                                            if (!empty($cuotaIds)) {
                                                                $respaldos = \DB::table('pago_respaldo_cuota')
                                                                    ->whereIn('pago_respaldo_cuota.cuota_id', $cuotaIds)
                                                                    ->join('pagos_respaldos', 'pago_respaldo_cuota.pago_respaldo_id', '=', 'pagos_respaldos.id')
                                                                    ->where('pagos_respaldos.estado', 'verificado')
                                                                    ->select('pagos_respaldos.archivo')
                                                                    ->first();
                                                                if ($respaldos) {
                                                                    $comprobante = [
                                                                        'archivo' => $respaldos->archivo,
                                                                        'url' => asset('storage/comprobantes/' . $respaldos->archivo),
                                                                    ];
                                                                }
                                                            }
                                                             $pagosData[] = [
                                                                 'id' => $pago->id,
                                                                 'recibo' => $pago->recibo,
                                                                 'fecha' => $pago->fecha_pago,
                                                                 'monto' => $pago->monto_total,
                                                                 'descuento' => $pago->descuento_bs,
                                                                 'metodo' => $pago->tipo_pago,
                                                                 'trabajador' => $trabajadorNombre,
                                                                 'estudiante' => ($estudiante->persona->nombres ?? '') . ' ' . ($estudiante->persona->apellido_paterno ?? ''),
                                                                 'programa' => $ins->ofertaAcademica->posgrado->nombre ?? '',
                                                                 'plan' => $ins->planesPago->nombre ?? '',
                                                                 'comprobante' => $comprobante,
                                                                 'documento_factura' => $pago->documento_factura ? \Storage::url($pago->documento_factura) : null,
                                                                'detalles' => ($pago->detalles ?? collect())
                                                                    ->map(function ($d) {
                                                                        return [
                                                                            'tipo' => $d->tipo_pago,
                                                                            'monto' => $d->monto_bs,
                                                                        ];
                                                                    })
                                                                    ->toArray(),
                                                                'cuotas' => ($pago->pagosCuotas ?? [])
                                                                    ->map(function ($pc) {
                                                                        return [
                                                                            'nombre' => $pc->cuota->nombre ?? ('Cuota #' . $pc->cuota_id),
                                                                            'n_cuota' => $pc->cuota->n_cuota ?? null,
                                                                            'monto' => $pc->monto_bs,
                                                                        ];
                                                                    })
                                                                    ->toArray(),
                                                            ];
                                                        }
                                                    }
                                                    $pagosDataJson = json_encode($pagosData);
                                                @endphp
                                                <div class="ctb-actions">
                                                    @if ($cuota->estado != 'Pagado')
                                                        <button type="button"
                                                            class="ctb-action pay btn-pagar-cuota"
                                                            data-id="{{ $cuota->id }}"
                                                            data-nombre="{{ $cuota->nombre }}"
                                                            data-monto="{{ $cuota->pago_pendiente_bs ?? $cuota->monto_bs }}"
                                                            title="Pagar">
                                                            <i class="ri-money-dollar-circle-line"></i>
                                                        </button>
                                                    @endif
                                                    @if (count($pagosData) > 0)
                                                        <button type="button"
                                                            class="ctb-action view btn-ver-detalle-pago"
                                                            data-pagos='{{ $pagosDataJson }}'
                                                            title="Ver Detalle">
                                                            <i class="ri-eye-line"></i>
                                                        </button>
                                                    @elseif($cuota->estado == 'Pagado')
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            style="padding: 20px; text-align: center; color: var(--est-text-muted); background: var(--d-row-hover);">
                            <i class="ri-money-dollar-line" style="font-size: 1.5rem; opacity: 0.5;"></i>
                            <p>Sin cuotas registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="est-empty-state"><i class="ri-money-dollar-line"></i>
            <h5>Sin información contable</h5>
            <p>No hay ofertas académicas registradas</p>
        </div>
    @endif
</div>

    <!-- Modal Registrar Pago (cuota individual) -->
    <div class="modal fade pmp-modal" id="modalPagarCuota" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content pmp-content">

                <div class="pmp-header">
                    <div class="pmp-header-icon"><i class="ri-money-dollar-circle-line"></i></div>
                    <div class="pmp-header-text">
                        <h5 class="pmp-header-title">Registrar Pago — Cuota #<span id="pago-cuota-numero"></span></h5>
                        @if ($trabajadorActual)
                            <small class="pmp-header-sub">
                                <i class="ri-user-line"></i> Registrado por:
                                <strong>{{ $trabajadorActual['nombre'] }}</strong> · {{ $trabajadorActual['cargo'] }}
                                @if ($trabajadorActual['sucursal'])
                                    · {{ $trabajadorActual['sucursal'] }}@if ($trabajadorActual['sede']) — {{ $trabajadorActual['sede'] }}@endif
                                @endif
                            </small>
                        @else
                            <small class="pmp-header-sub danger">
                                <i class="ri-error-warning-line"></i> No se pudo identificar al trabajador
                            </small>
                        @endif
                    </div>
                    <button type="button" class="pmp-close-btn" data-bs-dismiss="modal" aria-label="Cerrar">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <form id="formPagarCuota">
                    <div class="modal-body pmp-body">

                        {{-- Banner deuda actual --}}
                        <div class="pmp-banner deuda mb-3">
                            <div class="pmp-banner-text"><i class="ri-error-warning-line"></i> Deuda Pendiente</div>
                            <div class="pmp-banner-val" id="pago-deuda-actual">—</div>
                        </div>

                        {{-- Datos del pago --}}
                        <div class="pmp-section">
                            <div class="pmp-section-title"><i class="ri-edit-2-line"></i> Datos del pago</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="pmp-label"><i class="ri-install-line"></i> Cuota</label>
                                    <input type="text" class="form-control pmp-input" id="pago-cuota-nombre" readonly>
                                    <input type="hidden" id="pago-cuota-id" name="cuota_id">
                                </div>
                                <div class="col-md-6">
                                    <label class="pmp-label"><i class="ri-calendar-line"></i> Fecha de Pago</label>
                                    <input type="date" class="form-control pmp-input" id="pago-fecha" name="fecha_pago"
                                        value="{{ now('America/La_Paz')->format('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="pmp-label"><i class="ri-money-dollar-line"></i> Monto a Pagar <span class="opt">(Bs.)</span></label>
                                    <input type="number" class="form-control pmp-input" id="pago-monto" name="monto"
                                        step="0.01" min="0.01" required placeholder="0.00">
                                </div>
                                <div class="col-md-6">
                                    <label class="pmp-label"><i class="ri-discount-line"></i> Descuento <span class="opt">(Bs.)</span></label>
                                    <input type="number" class="form-control pmp-input" id="pago-descuento"
                                        name="descuento" step="0.01" min="0" value="0">
                                </div>
                            </div>
                        </div>

                        {{-- Banner nueva deuda --}}
                        <div class="pmp-banner nueva mb-3">
                            <div class="pmp-banner-text"><i class="ri-calculator-line"></i> Nueva Deuda</div>
                            <div class="pmp-banner-val" id="pago-nueva-deuda">—</div>
                        </div>

                        {{-- Error --}}
                        <div id="pago-mensaje-error" class="pmp-banner error mb-3" style="display: none;">
                            <div class="pmp-banner-text"><i class="ri-error-warning-line"></i> <span id="pago-mensaje-texto"></span></div>
                        </div>

                        <input type="hidden" id="pago-trabajador-cargo" name="trabajador_cargo_id"
                            value="{{ $trabajadorActual['id'] ?? '' }}">

                        {{-- Método de pago --}}
                        <div class="pmp-section">
                            <div class="pmp-section-title"><i class="ri-bank-card-line"></i> Método de pago</div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="pmp-label"><i class="ri-bank-card-line"></i> Método</label>
                                    <select class="form-select pmp-input" id="pago-metodo" name="metodo" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Qr">QR</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Parcial">Parcial (Efectivo + QR)</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="campo-efectivo" style="display:none;">
                                    <label class="pmp-label"><i class="ri-money-dollar-line"></i> Efectivo <span class="opt">(Bs.)</span></label>
                                    <input type="number" class="form-control pmp-input" id="pago-efectivo"
                                        name="efectivo" step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="col-md-6" id="campo-qr" style="display:none;">
                                    <label class="pmp-label"><i class="ri-qr-code-line"></i> QR <span class="opt">(Bs.)</span></label>
                                    <input type="number" class="form-control pmp-input" id="pago-qr" name="qr"
                                        step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="col-md-6" id="pago-cuenta-bancaria-container" style="display:none;">
                                    <label class="pmp-label"><i class="ri-bank-line"></i> Cuenta Bancaria</label>
                                    <select class="form-select pmp-input" id="pago-cuenta-bancaria" name="cuenta_bancaria_id">
                                        <option value="">Seleccionar cuenta...</option>
                                        @foreach($cuentasBancarias as $cuenta)
                                            <option value="{{ $cuenta->id }}">{{ $cuenta->banco->nombre }} - {{ $cuenta->numero_cuenta }} ({{ $cuenta->tipo_cuenta }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6" id="pago-referencia-container" style="display:none;">
                                    <label class="pmp-label"><i class="ri-file-info-line"></i> Referencia</label>
                                    <input type="text" class="form-control pmp-input" id="pago-referencia" name="referencia"
                                        placeholder="Número de referencia">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="pmp-footer">
                        <button type="button" class="pmp-btn pmp-btn-cancel" data-bs-dismiss="modal">
                            <i class="ri-close-line"></i> Cancelar
                        </button>
                        <button type="submit" class="pmp-btn pmp-btn-submit">
                            <i class="ri-save-line"></i> Registrar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ver Detalle Pago -->
    <div class="modal fade pmp-modal" id="modalVerDetallePago" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content pmp-content">

                <div class="pmp-header">
                    <div class="pmp-header-icon"><i class="ri-file-list-3-line"></i></div>
                    <div class="pmp-header-text">
                        <h5 class="pmp-header-title">Detalle del Pago</h5>
                        <small class="pmp-header-sub">Comprobante de pago</small>
                    </div>
                    <button type="button" class="pmp-close-btn" data-bs-dismiss="modal" aria-label="Cerrar">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="pmp-body">
                    <div id="lista-pagos" class="list-group list-group-flush"
                        style="max-height: 400px; overflow-y: auto; border-radius: 10px; border: 1px solid #e2e8f0;"></div>
                    <div id="detalle-pago-container" class="p-3" style="display: none; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <button type="button" class="pmp-btn pmp-btn-cancel mb-2" id="btn-volver-lista">
                            <i class="ri-arrow-left-line"></i> Volver
                        </button>
                        <div class="border-bottom pb-2 mb-3" style="border-bottom: 2px solid var(--est-primary);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('images/logo_secundario.png') }}" alt="Logo" style="width: 40px;">
                                    <div>
                                        <div class="fw-bold" style="font-size: 12px; color: #1e293b;">INNOVA CIENCIA VIRTUAL</div>
                                        <div class="text-muted" style="font-size: 9px;">Educación Superior Virtual</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" style="font-size: 14px; color: var(--est-primary);">COMPROBANTE</div>
                                    <div class="pmp-section-title d-inline-flex mt-1" id="detalle-recibo">—</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 text-muted" style="font-size: 10px;">
                                <span><strong>Fecha:</strong> <span id="detalle-fecha">—</span></span>
                                <span><strong>Forma Pago:</strong> <span id="detalle-metodo">—</span></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-1" style="font-size: 0.85rem;">
                                <strong>Estudiante:</strong> <span id="detalle-estudiante">—</span>
                            </div>
                            <div class="mb-1" style="font-size: 0.85rem;">
                                <strong>Programa:</strong> <span id="detalle-programa">—</span>
                            </div>
                            <div class="mb-1" style="font-size: 0.85rem;">
                                <strong>Plan de Pago:</strong> <span id="detalle-plan">—</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <table class="table table-bordered table-sm" style="font-size: 10px; margin-bottom: 0;">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Concepto</th>
                                        <th class="text-end">Monto</th>
                                    </tr>
                                </thead>
                                <tbody id="detalle-tabla"></tbody>
                                <tfoot class="table-warning">
                                    <tr>
                                        <td colspan="2" class="fw-bold">Total (Bs.)</td>
                                        <td class="text-end fw-bold" id="detalle-total">—</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mb-2" id="detalle-descuento-container" style="display: none;">
                            <strong>Descuento:</strong> <span class="text-warning" id="detalle-descuento">—</span>
                        </div>
                        <div id="detalle-factura-container" style="display:none; margin-bottom:12px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <i class="ri-file-list-3-line" style="color:#059669;font-size:1.1rem;"></i>
                                    <div style="font-size:.85rem;font-weight:700;color:#059669;" id="detalle-factura-estado">Con factura</div>
                                </div>
                                <button type="button" id="btn-ver-factura-detalle"
                                    style="background:#059669;color:white;border:none;border-radius:8px;padding:0.45rem 1rem;font-size:.8rem;cursor:pointer;display:flex;align-items:center;gap:6px;">
                                    <i class="ri-eye-line"></i> Ver factura
                                </button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4" style="font-size: 10px;">
                            <div class="text-center" style="width: 45%;">
                                <div class="border-top py-1" id="detalle-trabajador">—</div>
                                <div class="fw-bold">EMISOR</div>
                            </div>
                            <div class="text-center" style="width: 45%;">
                                <div class="border-top py-1" id="detalle-depositante">—</div>
                                <div class="fw-bold">DEPOSITANTE</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pmp-footer">
                    <a href="#" id="btn-descargar-pdf" class="pmp-btn pmp-btn-submit" target="_blank">
                        <i class="ri-file-pdf-line"></i> Descargar PDF
                    </a>
                    <button type="button" class="pmp-btn pmp-btn-cancel" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Pago Masivo -->
    <div class="modal fade pmp-modal" id="modalPagoMasivo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content pmp-content">

                <div class="pmp-header">
                    <div class="pmp-header-icon"><i class="ri-file-list-3-line"></i></div>
                    <div class="pmp-header-text">
                        <h5 class="pmp-header-title">Registro Masivo de Cuotas</h5>
                        <small class="pmp-header-sub" id="pago-masivo-oferta">—</small>
                    </div>
                    <button type="button" class="pmp-close-btn" data-bs-dismiss="modal" aria-label="Cerrar">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <form id="formPagoMasivo">
                    <div class="modal-body pmp-body">

                        {{-- Datos del pago --}}
                        <div class="pmp-section">
                            <div class="pmp-section-title"><i class="ri-edit-2-line"></i> Datos del pago</div>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="pmp-label"><i class="ri-money-dollar-line"></i> Monto a Pagar <span class="opt">(Bs.)</span></label>
                                    <input type="number" class="form-control pmp-input" id="pago-masivo-monto" name="monto" step="0.01" min="0.01" required placeholder="0.00">
                                </div>
                                <div class="col-md-3">
                                    <label class="pmp-label"><i class="ri-discount-line"></i> Descuento <span class="opt">(Bs.)</span></label>
                                    <input type="number" class="form-control pmp-input" id="pago-masivo-descuento" name="descuento" step="0.01" min="0" value="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="pmp-label"><i class="ri-calendar-line"></i> Fecha de Pago</label>
                                    <input type="date" class="form-control pmp-input" id="pago-masivo-fecha" name="fecha_pago"
                                        value="{{ now('America/La_Paz')->format('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="pmp-label"><i class="ri-bank-card-line"></i> Método</label>
                                    <select class="form-select pmp-input" id="pago-masivo-metodo" name="metodo" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Qr">QR</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Parcial">Parcial (Efectivo + QR)</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="pago-masivo-campo-efectivo" style="display:none;">
                                    <label class="pmp-label"><i class="ri-money-dollar-line"></i> Efectivo <span class="opt">(Bs.)</span></label>
                                    <input type="number" class="form-control pmp-input" id="pago-masivo-efectivo" name="efectivo" step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="col-md-6" id="pago-masivo-campo-qr" style="display:none;">
                                    <label class="pmp-label"><i class="ri-qr-code-line"></i> QR <span class="opt">(Bs.)</span></label>
                                    <input type="number" class="form-control pmp-input" id="pago-masivo-qr" name="qr" step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="col-md-6" id="pago-masivo-cuenta-bancaria-container" style="display:none;">
                                    <label class="pmp-label"><i class="ri-bank-line"></i> Cuenta Bancaria</label>
                                    <select class="form-select pmp-input" id="pago-masivo-cuenta-bancaria" name="cuenta_bancaria_id">
                                        <option value="">Seleccionar cuenta...</option>
                                        @foreach($cuentasBancarias as $cuenta)
                                            <option value="{{ $cuenta->id }}">{{ $cuenta->banco->nombre }} - {{ $cuenta->numero_cuenta }} ({{ $cuenta->tipo_cuenta }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6" id="pago-masivo-referencia-container" style="display:none;">
                                    <label class="pmp-label"><i class="ri-file-info-line"></i> Referencia</label>
                                    <input type="text" class="form-control pmp-input" id="pago-masivo-referencia" name="referencia" placeholder="Número de referencia">
                                </div>
                            </div>
                        </div>

                        {{-- Lista de cuotas --}}
                        <div class="pmp-section">
                            <div class="pmp-section-title"><i class="ri-list-check-2"></i> Cuotas del programa</div>
                            <div id="pago-masivo-lista-cuotas" class="pmp-cuotas-wrap"></div>
                        </div>

                        {{-- Resumen --}}
                        <div class="pmp-resumen">
                            <div class="pmp-resumen-item">
                                <div class="pmp-resumen-icon"><i class="ri-wallet-3-line"></i></div>
                                <div>
                                    <div class="pmp-resumen-lbl">Total Deuda</div>
                                    <div class="pmp-resumen-val pmp-deuda" id="pago-masivo-deuda-total">—</div>
                                </div>
                            </div>
                            <div class="pmp-resumen-item">
                                <div class="pmp-resumen-icon ingresado"><i class="ri-money-dollar-circle-line"></i></div>
                                <div>
                                    <div class="pmp-resumen-lbl">Monto Ingresado</div>
                                    <div class="pmp-resumen-val pmp-ingresado" id="pago-masivo-monto-ingresado">—</div>
                                </div>
                            </div>
                            <div class="pmp-resumen-item">
                                <div class="pmp-resumen-icon nueva"><i class="ri-checkbox-circle-line"></i></div>
                                <div>
                                    <div class="pmp-resumen-lbl">Nueva Deuda</div>
                                    <div class="pmp-resumen-val pmp-nueva" id="pago-masivo-nueva-deuda">—</div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="pago-masivo-estudiante-id" name="estudiante_id">
                        <input type="hidden" id="pago-masivo-inscripcion-id" name="inscripcion_id">
                        <input type="hidden" id="pago-masivo-trabajador-cargo" name="trabajador_cargo_id">
                    </div>

                    <div class="pmp-footer">
                        <button type="button" class="pmp-btn pmp-btn-cancel" data-bs-dismiss="modal">
                            <i class="ri-close-line"></i> Cancelar
                        </button>
                        <button type="submit" class="pmp-btn pmp-btn-submit">
                            <i class="ri-save-line"></i> Registrar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para ver factura -->
    <div class="modal fade pmp-modal" id="modalVerFactura" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content pmp-content">

                <div class="pmp-header">
                    <div class="pmp-header-icon"><i class="ri-file-list-3-line"></i></div>
                    <div class="pmp-header-text">
                        <h5 class="pmp-header-title">Factura — <span id="facturaReciboNum"></span></h5>
                        <small class="pmp-header-sub">Documento fiscal del pago</small>
                    </div>
                    <button type="button" class="pmp-close-btn" data-bs-dismiss="modal" aria-label="Cerrar">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="pmp-body">
                    <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;padding:14px 18px;background:linear-gradient(135deg,#fdf6ee,#fef9f2);border:1px solid #e9e2d9;border-radius:12px;">
                        <img src="{{ asset('images/logo_secundario.png') }}" alt="Logo" style="width:42px;height:42px;object-fit:contain;flex-shrink:0;">
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.7rem;color:#927f64;text-transform:uppercase;letter-spacing:.06em;font-weight:600;">INNOVA CIENCIA VIRTUAL</div>
                            <div style="display:flex;flex-wrap:wrap;gap:14px;margin-top:6px;font-size:.82rem;">
                                <span><span style="color:#927f64;">Estudiante:</span> <strong id="facturaEstudiante" style="color:#1e293b;">—</strong></span>
                                <span><span style="color:#927f64;">Monto:</span> <strong id="facturaMonto" style="color:#059669;">—</strong></span>
                                <span><span style="color:#927f64;">Programa:</span> <strong id="facturaOferta" style="color:#1e293b;">—</strong></span>
                            </div>
                        </div>
                    </div>

                    <div style="background:#faf8f5;border:1px solid #e9e2d9;border-radius:12px;padding:4px;min-height:300px;">
                        <div id="facturaFileContainer" style="max-height:520px;overflow:auto;border-radius:10px;"></div>
                    </div>
                </div>

                <div class="pmp-footer">
                    <a id="facturaDownloadLink" href="#" target="_blank" class="pmp-btn pmp-btn-submit">
                        <i class="ri-download-2-line"></i> Descargar
                    </a>
                    <button type="button" class="pmp-btn pmp-btn-cancel" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>