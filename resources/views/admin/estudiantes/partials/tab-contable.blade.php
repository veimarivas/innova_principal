<!-- Tab: Contable -->
<style>
.cuotas-card {
    background: linear-gradient(145deg, #ffffff, #f8fafc);
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: visible;
    box-shadow: 0 4px 24px -4px rgba(0, 0, 0, 0.08);
}

.cuotas-table {
    width: 100%;
    border-collapse: collapse;
}

.cuotas-table thead th {
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 12px 14px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #64748b;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
}

.cuotas-table thead th:first-child { width: 40px; text-align: center; }
.cuotas-table thead th:last-child { width: 90px; text-align: center; }

.cuotas-table tbody tr {
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.cuotas-table tbody tr:hover {
    background: linear-gradient(90deg, rgba(252, 123, 4, 0.04) 0%, rgba(252, 123, 4, 0.02) 100%);
}

.cuotas-table tbody td {
    padding: 14px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.85rem;
}

.cuotas-table tbody tr:last-child td {
    border-bottom: none;
}

.cuota-num {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.75rem;
    color: #64748b;
}

.cuota-monto {
    font-weight: 600;
    color: #1e293b;
}

.cuota-pendiente {
    font-weight: 600;
}

.cuota-vencimiento {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.cuota-vencimiento .fecha {
    font-weight: 500;
    color: #334155;
}

.cuota-vencimiento .dias-restantes {
    display: block;
    font-size: 0.65rem;
    margin-top: 2px;
}

.cuota-vencimiento.vencido .dias-restantes { color: #dc2626; }
.cuota-vencimiento.por-vencer .dias-restantes { color: #f59e0b; }
.cuota-vencimiento.ok .dias-restantes { color: #22c55e; }

.cuota-vencimiento.vencido .fecha { color: #dc2626; font-weight: 600; }
.cuota-vencimiento.por-vencer .fecha { color: #f59e0b; font-weight: 600; }
.cuota-vencimiento.ok .fecha { color: #22c55e; font-weight: 600; }

.cuota-pago-fecha {
    font-size: 0.8rem;
    color: #334155;
}

.cuota-total-pagado {
    font-weight: 600;
    color: #15803d;
}

.cuota-estado-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    width: fit-content;
}

.cuota-estado-pill.pagado {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.12) 0%, rgba(34, 197, 94, 0.06) 100%);
    color: #15803d;
}

.cuota-estado-pill.vencido {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.12) 0%, rgba(239, 68, 68, 0.06) 100%);
    color: #dc2626;
}

.cuota-estado-pill.pendiente {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.12) 0%, rgba(245, 158, 11, 0.06) 100%);
    color: #b45309;
}

.cuota-actions {
    display: flex;
    gap: 6px;
    justify-content: center;
}

.cuota-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.95rem;
}

.cuota-action-btn.pagar {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: white;
}

.cuota-action-btn.pagar:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
}

.cuota-action-btn.ver {
    background: #f1f5f9;
    color: #64748b;
}

.cuota-action-btn.ver:hover {
    background: #e2e8f0;
    color: #334155;
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
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="est-stat-card">
                <div class="est-stat-body">
                    <div class="est-stat-icon"
                        style="background: var(--est-success-light); color: var(--est-success);"><i
                            class="ri-checkbox-circle-line"></i></div>
                    <div>
                        <div class="est-stat-value" style="color: var(--est-success);">Bs.
                            {{ number_format($totalPagado, 2) }}</div>
                        <div class="est-stat-label">Total Pagado</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="est-stat-card">
                <div class="est-stat-body">
                    <div class="est-stat-icon"
                        style="background: var(--est-warning-light); color: var(--est-warning);"><i
                            class="ri-time-line"></i></div>
                    <div>
                        <div class="est-stat-value" style="color: var(--est-warning);">Bs.
                            {{ number_format($totalPendiente, 2) }}</div>
                        <div class="est-stat-label">Pendiente</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="est-stat-card">
                <div class="est-stat-body">
                    <div class="est-stat-icon"
                        style="background: var(--est-danger-light); color: var(--est-danger);"><i
                            class="ri-alert-line"></i></div>
                    <div>
                        <div class="est-stat-value" style="color: var(--est-danger);">Bs.
                            {{ number_format($totalVencido, 2) }}</div>
                        <div class="est-stat-label">Vencido</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($inscripciones->count() > 0)
        <div class="est-oferta-tabs-nav">
            @foreach ($inscripciones as $key => $ins)
                <button type="button" class="est-oferta-tab-btn {{ $key == 0 ? 'active' : '' }}"
                    data-target="contable-oferta-{{ $key }}">
                    <i class="ri-money-dollar-circle-line"></i>
                    {{ $ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta ' . ($key + 1) }}
                </button>
            @endforeach
        </div>
        @foreach ($inscripciones as $key => $ins)
            <div class="est-oferta-content {{ $key == 0 ? 'active' : '' }}"
                id="contable-oferta-{{ $key }}">
                <div class="est-data-card mb-4">
                    <div class="est-data-card-header">
                        <div class="est-data-card-icon"
                            style="background: var(--est-primary-light); color: var(--est-primary);"><i
                                class="ri-money-dollar-circle-line"></i></div>
                        <div style="flex: 1;">
                            <h5 class="est-data-card-title">
                                {{ $ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta #' . $ins->ofertas_academica_id }}
                            </h5>
                            <div style="font-size: 0.75rem; color: var(--est-text-muted);">Plan:
                                {{ $ins->planesPago?->nombre ?? '—' }}</div>
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
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div class="est-stat-body" style="border-right: 1px solid var(--d-row-border);">
                                <div class="est-stat-icon"
                                    style="background: var(--est-success-light); color: var(--est-success);"><i
                                        class="ri-checkbox-circle-line"></i></div>
                                <div>
                                    <div class="est-stat-value"
                                        style="color: var(--est-success); font-size: 1rem;">Bs.
                                        {{ number_format($insPagado, 2) }}</div>
                                    <div class="est-stat-label">Pagado</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="est-stat-body" style="border-right: 1px solid var(--d-row-border);">
                                <div class="est-stat-icon"
                                    style="background: var(--est-warning-light); color: var(--est-warning);"><i
                                        class="ri-time-line"></i></div>
                                <div>
                                    <div class="est-stat-value"
                                        style="color: var(--est-warning); font-size: 1rem;">Bs.
                                        {{ number_format($insPendiente, 2) }}</div>
                                    <div class="est-stat-label">Pendiente</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="est-stat-body">
                                <div class="est-stat-icon"
                                    style="background: var(--est-danger-light); color: var(--est-danger);"><i
                                        class="ri-alert-line"></i></div>
                                <div>
                                    <div class="est-stat-value"
                                        style="color: var(--est-danger); font-size: 1rem;">Bs.
                                        {{ number_format($insVencido, 2) }}</div>
                                    <div class="est-stat-label">Vencido</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($ins->cuotas && $ins->cuotas->count() > 0)
                        <div class="cuotas-card" style="border-top: 1px solid var(--d-row-border);">
                            <div
                                style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--est-text-muted); padding: 14px 18px; display: flex; justify-content: space-between; align-items: center;">
                                <span><i class="ri-install-line"></i> Cuotas
                                    ({{ $ins->cuotas->count() }})
                                </span>
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
                                <button type="button"
                                    class="btn btn-sm btn-action btn-action-edit btn-pago-masivo"
                                    data-inscripcion-id="{{ $ins->id }}"
                                    data-oferta="{{ $ins->ofertaAcademica?->posgrado?->nombre ?? 'Oferta #' . $ins->ofertas_academica_id }}"
                                    data-cuotas='{{ json_encode($cuotasData) }}'
                                    title="Registro Masivo">
                                    <i class="ri-file-list-3-line"></i>
                                </button>
                            </div>
                            <table class="cuotas-table">
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
                                            
                                            // diffInDays(fechaFutura, hoy) = positivo
                                            // diffInDays(fechaPasada, hoy) = negativo
                                            $diasFaltantes = null;
                                            if ($vencDate && $hoy) {
                                                $diasFaltantes = (int) $hoy->diffInDays($vencDate, false);
                                            }
                                            
                                            // Determinar clase y color
                                            $claseVenc = '';
                                            $colorFecha = '#334155';
                                            if ($cuota->estado == 'Pagado') {
                                                $claseVenc = 'pagado';
                                                $colorFecha = '#64748b';
                                            } elseif ($diasFaltantes !== null && $diasFaltantes < 0) {
                                                // fecha ya pasó y no está pagada
                                                $claseVenc = 'vencido';
                                                $colorFecha = '#dc2626';
                                            } else {
                                                // fecha futura o hoy
                                                $claseVenc = 'ok';
                                                $colorFecha = '#22c55e';
                                            }
                                            $estadoClass = $cuota->estado == 'Pagado' ? 'pagado' : ($cuota->estado == 'Vencido' ? 'vencido' : 'pendiente');
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="cuota-num">{{ $cuota->n_cuota }}</div>
                                            </td>
                                            <td class="fw-medium">{{ $cuota->nombre }}</td>
                                            <td class="cuota-monto">Bs. {{ number_format($cuota->monto_bs, 2) }}</td>
                                            <td>{{ $cuota->descuento_bs > 0 ? 'Bs. ' . number_format($cuota->descuento_bs, 2) : '—' }}
                                            </td>
                                            <td class="cuota-pendiente">Bs.
                                                {{ number_format($cuota->pago_pendiente_bs ?? $cuota->monto_bs, 2) }}
                                            </td>
                                            <td>
                                                <div class="cuota-vencimiento {{ $claseVenc }}" style="background: {{ $claseVenc == 'vencido' ? 'rgba(220,38,38,0.1)' : ($claseVenc == 'ok' ? 'rgba(34,197,94,0.1)' : 'transparent') }}; padding: 4px 8px; border-radius: 6px;">
                                                    <span class="fecha" style="color: {{ $colorFecha }}; font-weight: 600;">{{ $vencDate ? $vencDate->format('d/m/Y') : '—' }}</span>
                                                    @if($diasFaltantes !== null && $cuota->estado != 'Pagado')
                                                        <span class="dias-restantes" style="display: block; font-size: 0.65rem; color: {{ $claseVenc == 'vencido' ? '#dc2626' : ($claseVenc == 'ok' ? '#22c55e' : '#64748b') }};">
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
                                            <td class="cuota-pago-fecha">
                                                @if ($cuota->estado != 'Pagado')
                                                    —
                                                @else
                                                    {{ $cuota->fecha_pago ? \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') : '—' }}
                                                @endif
                                            </td>
                                            <td class="cuota-total-pagado">Bs. {{ number_format($totalPagadoCuota, 2) }}</td>
                                            <td>
                                                <span class="cuota-estado-pill {{ $estadoClass }}">{{ $cuota->estado }}</span>
                                            </td>
                                            <td>
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
                                                                    ? $pago->trabajadorCargo->trabajador
                                                                            ->persona->nombres .
                                                                        ' ' .
                                                                        $pago->trabajadorCargo->trabajador
                                                                            ->persona->apellido_paterno
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
                                                <div class="cuota-actions">
                                                    @if ($cuota->estado != 'Pagado')
                                                        <button type="button"
                                                            class="cuota-action-btn pagar btn-pagar-cuota"
                                                            data-id="{{ $cuota->id }}"
                                                            data-nombre="{{ $cuota->nombre }}"
                                                            data-monto="{{ $cuota->pago_pendiente_bs ?? $cuota->monto_bs }}"
                                                            title="Pagar">
                                                            <i class="ri-money-dollar-circle-line"></i>
                                                        </button>
                                                    @endif
                                                    @if (count($pagosData) > 0)
                                                        <button type="button"
                                                            class="cuota-action-btn ver btn-ver-detalle-pago"
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

    <!-- Modal Registrar Pago -->
    <div class="modal fade" id="modalPagarCuota" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title"><i class="ri-money-dollar-circle-line"></i> Registrar Pago -
                            Cuota
                            #<span id="pago-cuota-numero"></span></h5>
                        @if ($trabajadorActual)
                            <small class="text-muted d-block">
                                <i class="ri-user-line"></i> Registrado por:
                                <strong>{{ $trabajadorActual['nombre'] }}</strong> -
                                {{ $trabajadorActual['cargo'] }}
                                @if ($trabajadorActual['sucursal'])
                                    ({{ $trabajadorActual['sucursal'] }}
                                    @if ($trabajadorActual['sede'])
                                        - {{ $trabajadorActual['sede'] }}
                                    @endif)
                                @endif
                            </small>
                        @else
                            <small class="text-danger d-block">
                                <i class="ri-error-warning-line"></i> No se pudo identificar al trabajador
                            </small>
                        @endif
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="formPagarCuota">
                    <div class="modal-body">
                        <div class="alert alert-info d-flex justify-content-between align-items-center mb-3">
                            <div><i class="ri-information-line"></i> <strong>Deuda Pendiente:</strong></div>
                            <div class="fw-bold text-danger" id="pago-deuda-actual"
                                style="font-size: 1.2rem;">—</div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><i class="ri-install-line"></i> Cuota</label>
                                <input type="text" class="form-control" id="pago-cuota-nombre" readonly>
                                <input type="hidden" id="pago-cuota-id" name="cuota_id">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="ri-calendar-line"></i> Fecha de
                                    Pago</label>
                                <input type="date" class="form-control" id="pago-fecha" name="fecha_pago"
                                    value="{{ now('America/La_Paz')->format('Y-m-d') }}"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="ri-money-dollar-line"></i> Monto a Pagar
                                    (Bs.)</label>
                                <input type="number" class="form-control" id="pago-monto" name="monto"
                                    step="0.01" min="0.01" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="ri-discount-line"></i> Descuento
                                    (Bs.)</label>
                                <input type="number" class="form-control" id="pago-descuento"
                                    name="descuento" step="0.01" min="0" value="0">
                            </div>
                            <div class="col-12">
                                <div
                                    class="alert alert-success d-flex justify-content-between align-items-center mb-0 py-2">
                                    <div><i class="ri-calculator-line"></i> <strong>Nueva Deuda:</strong></div>
                                    <div class="fw-bold" id="pago-nueva-deuda" style="font-size: 1.2rem;">—
                                    </div>
                                </div>
                            </div>
                            <div class="col-12" id="pago-mensaje-error" style="display: none;">
                                <div class="alert alert-danger d-flex align-items-center mb-0 py-2">
                                    <i class="ri-error-warning-line me-2"></i>
                                    <span id="pago-mensaje-texto"></span>
                                </div>
                            </div>
                            <input type="hidden" id="pago-trabajador-cargo" name="trabajador_cargo_id"
                                value="{{ $trabajadorActual['id'] ?? '' }}">
                            <div class="col-md-12">
                                <label class="form-label"><i class="ri-bank-card-line"></i> Método de
                                    Pago</label>
                                <select class="form-select" id="pago-metodo" name="metodo" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Qr">QR</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Parcial">Parcial (Efectivo + QR)</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="campo-efectivo" style="display:none;">
                                <label class="form-label"><i class="ri-money-dollar-line"></i> Efectivo
                                    (Bs.)</label>
                                <input type="number" class="form-control" id="pago-efectivo"
                                    name="efectivo" step="0.01" min="0">
                            </div>
                            <div class="col-md-6" id="campo-qr" style="display:none;">
                                <label class="form-label"><i class="ri-qr-code-line"></i> QR (Bs.)</label>
                                <input type="number" class="form-control" id="pago-qr" name="qr"
                                    step="0.01" min="0">
                            </div>
                            <div class="col-md-6" id="pago-cuenta-bancaria-container" style="display:none;">
                                <label class="form-label"><i class="ri-bank-line"></i> Cuenta Bancaria</label>
                                <select class="form-select" id="pago-cuenta-bancaria" name="cuenta_bancaria_id">
                                    <option value="">Seleccionar cuenta...</option>
                                    @foreach($cuentasBancarias as $cuenta)
                                        <option value="{{ $cuenta->id }}">{{ $cuenta->banco->nombre }} - {{ $cuenta->numero_cuenta }} ({{ $cuenta->tipo_cuenta }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" id="pago-referencia-container" style="display:none;">
                                <label class="form-label"><i class="ri-file-info-line"></i> Referencia</label>
                                <input type="text" class="form-control" id="pago-referencia" name="referencia"
                                    placeholder="Número de referencia">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i
                                class="ri-close-line"></i> Cancelar</button>
                        <button type="submit" class="btn btn-modal-submit"><i class="ri-save-line"></i>
                            Registrar Pago</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ver Detalle Pago -->
    <div class="modal fade" id="modalVerDetallePago" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #fc7b04, #c96004); color: white;">
                    <h5 class="modal-title"><i class="ri-file-list-3-line"></i> Detalle del Pago</h5>
                    <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="lista-pagos" class="list-group list-group-flush p-2"
                        style="max-height: 400px; overflow-y: auto;"></div>
                    <div id="detalle-pago-container" class="p-3" style="display: none; font-family: 'Times New Roman', serif; font-size: 11px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary mb-2"
                            id="btn-volver-lista">
                            <i class="ri-arrow-left-line"></i> Volver
                        </button>
                        <div class="border-bottom pb-2 mb-3" style="border-bottom: 2px solid #000;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('build/images/logo-dark.png') }}" alt="Logo" style="width: 40px;">
                                    <div>
                                        <div class="fw-bold" style="font-size: 12px;">INNOVA CIENCIA VIRTUAL</div>
                                        <div class="text-muted" style="font-size: 9px;">Educación Superior Virtual</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" style="font-size: 14px;">COMPROBANTE</div>
                                    <div class="bg-warning text-dark px-2 py-1 rounded fw-bold" id="detalle-recibo" style="display: inline-block; font-size: 11px;">—</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 text-muted" style="font-size: 10px;">
                                <span><strong>Fecha:</strong> <span id="detalle-fecha">—</span></span>
                                <span><strong>Forma Pago:</strong> <span id="detalle-metodo">—</span></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-1">
                                <strong>Estudiante:</strong> <span id="detalle-estudiante">—</span>
                            </div>
                            <div class="mb-1">
                                <strong>Programa:</strong> <span id="detalle-programa">—</span>
                            </div>
                            <div class="mb-1">
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
                <div class="modal-footer">
                    <a href="#" id="btn-descargar-pdf" class="btn text-white" target="_blank" 
                       style="background: #fc7b04; border-color: #fc7b04;"
                       onmouseover="this.style.background='#c96004'" onmouseout="this.style.background='#fc7b04'">
                        <i class="ri-file-pdf-line"></i> Descargar PDF
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pago Masivo -->
    <div class="modal fade" id="modalPagoMasivo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title"><i class="ri-file-list-3-line"></i> Registro Masivo de Cuotas
                        </h5>
                        <small class="text-muted d-block" id="pago-masivo-oferta"></small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="formPagoMasivo">
                    <div class="modal-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label"><i class="ri-money-dollar-line"></i> Monto a Pagar
                                    (Bs.)</label>
                                <input type="number" class="form-control" id="pago-masivo-monto"
                                    name="monto" step="0.01" min="0.01" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><i class="ri-discount-line"></i> Descuento
                                    (Bs.)</label>
                                <input type="number" class="form-control" id="pago-masivo-descuento"
                                    name="descuento" step="0.01" min="0" value="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><i class="ri-calendar-line"></i> Fecha de
                                    Pago</label>
                                <input type="date" class="form-control" id="pago-masivo-fecha"
                                    name="fecha_pago" 
                                    value="{{ now('America/La_Paz')->format('Y-m-d') }}" 
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><i class="ri-bank-card-line"></i> Método de
                                    Pago</label>
                                <select class="form-select" id="pago-masivo-metodo" name="metodo" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Qr">QR</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Parcial">Parcial (Efectivo + QR)</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="pago-masivo-campo-efectivo" style="display:none;">
                                <label class="form-label"><i class="ri-money-dollar-line"></i> Efectivo
                                    (Bs.)</label>
                                <input type="number" class="form-control" id="pago-masivo-efectivo"
                                    name="efectivo" step="0.01" min="0">
                            </div>
                            <div class="col-md-6" id="pago-masivo-campo-qr" style="display:none;">
                                <label class="form-label"><i class="ri-qr-code-line"></i> QR (Bs.)</label>
                                <input type="number" class="form-control" id="pago-masivo-qr"
                                    name="qr" step="0.01" min="0">
                            </div>
                            <div class="col-md-6" id="pago-masivo-cuenta-bancaria-container" style="display:none;">
                                <label class="form-label"><i class="ri-bank-line"></i> Cuenta Bancaria</label>
                                <select class="form-select" id="pago-masivo-cuenta-bancaria" name="cuenta_bancaria_id">
                                    <option value="">Seleccionar cuenta...</option>
                                    @foreach($cuentasBancarias as $cuenta)
                                        <option value="{{ $cuenta->id }}">{{ $cuenta->banco->nombre }} - {{ $cuenta->numero_cuenta }} ({{ $cuenta->tipo_cuenta }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" id="pago-masivo-referencia-container" style="display:none;">
                                <label class="form-label"><i class="ri-file-info-line"></i> Referencia</label>
                                <input type="text" class="form-control" id="pago-masivo-referencia" name="referencia"
                                    placeholder="Número de referencia">
                            </div>
                        </div>

                        <div id="pago-masivo-lista-cuotas" class="mb-4"
                            style="max-height: 300px; overflow-y: auto;">
                        </div>

                        <div class="alert alert-info">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="text-muted small">Total Deuda</div>
                                    <div class="fw-bold" id="pago-masivo-deuda-total">—</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small">Monto Ingresado</div>
                                    <div class="fw-bold text-primary" id="pago-masivo-monto-ingresado">—</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small">Nueva Deuda</div>
                                    <div class="fw-bold text-success" id="pago-masivo-nueva-deuda">—</div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="pago-masivo-estudiante-id" name="estudiante_id">
                        <input type="hidden" id="pago-masivo-inscripcion-id" name="inscripcion_id">
                        <input type="hidden" id="pago-masivo-trabajador-cargo" name="trabajador_cargo_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal"><i
                                class="ri-close-line"></i> Cancelar</button>
                        <button type="submit" class="btn btn-modal-submit"><i class="ri-save-line"></i>
                            Registrar Pago</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>