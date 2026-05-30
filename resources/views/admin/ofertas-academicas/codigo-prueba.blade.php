<!-- Pestaña 3: Finanzas - Diseño Premium -->
<div class="tab-pane fade" id="tab-finanzas" role="tabpanel">
    <!-- Resumen por Concepto -->
    <div class="row g-3 mb-3">
        @foreach ($resumenPorConcepto as $concepto => $datos)
            @php
                $color = match ($concepto) {
                    'Matrícula' => '#2563eb',
                    'Colegiatura' => '#0891b2',
                    'Certificación' => '#d97706',
                    default => '#64748b',
                };
                $icono = match ($concepto) {
                    'Matrícula' => 'ri-file-text-line',
                    'Colegiatura' => 'ri-calendar-line',
                    'Certificación' => 'ri-award-line',
                    default => 'ri-money-dollar-circle-line',
                };
            @endphp
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-xs d-flex align-items-center justify-content-center rounded-2"
                                    style="background: {{ $color }}20;">
                                    <i class="{{ $icono }}"
                                        style="color: {{ $color }}; font-size: 0.9rem;"></i>
                                </div>
                                <h6 class="mb-0 fw-semibold" style="color: {{ $color }};">{{ $concepto }}
                                </h6>
                            </div>
                            <span class="badge fs-10"
                                style="background: {{ $color }}20; color: {{ $color }};">{{ number_format($datos['porcentaje'], 1) }}%</span>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <div class="rounded p-2" style="background: #f8fafc;">
                                    <span class="text-muted fs-9 d-block">Total</span>
                                    <span class="fw-bold fs-12">{{ number_format($datos['total'], 2) }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rounded p-2" style="background: #f0fdf4;">
                                    <span class="text-success fs-9 d-block">Pagado</span>
                                    <span
                                        class="fw-bold fs-12 text-success">{{ number_format($datos['pagado'], 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="rounded p-2" style="background: #fef2f2;">
                            <span class="text-danger fs-9 d-block">Pendiente</span>
                            <span class="fw-bold fs-12 text-danger">{{ number_format($datos['pendiente'], 2) }}</span>
                        </div>
                        <div class="progress mt-2" style="height: 4px; background: #e2e8f0;">
                            <div class="progress-bar"
                                style="width: {{ $datos['porcentaje'] }}%; background: {{ $color }};"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Gráficos - Misma altura -->
    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header border-0 bg-transparent py-2 px-3">
                    <h6 class="card-title mb-0 fw-semibold">
                        <i class="ri-pie-chart-line align-middle me-2" style="color: var(--dash-primary);"></i>
                        Ingresos por Concepto
                    </h6>
                </div>
                <div class="card-body py-2" style="max-height: 200px; overflow: hidden;">
                    <canvas id="ingresosConceptoChart" class="w-100" style="max-height: 160px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header border-0 bg-transparent py-2 px-3">
                    <h6 class="card-title mb-0 fw-semibold">
                        <i class="ri-bar-chart-line align-middle me-2" style="color: var(--dash-primary);"></i>
                        Estado de Cobranza
                    </h6>
                </div>
                <div class="card-body py-2" style="max-height: 200px; overflow: hidden;">
                    <canvas id="cobranzaConceptoChart" class="w-100" style="max-height: 160px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Estado Financiero Completa -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header border-0 bg-transparent py-3 px-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-semibold">
                    <i class="ri-wallet-line align-middle me-2" style="color: var(--dash-primary);"></i>
                    Estado Financiero de Participantes
                </h5>
                <a href="{{ route('admin.ofertas.exportar-estado-financiero', $oferta->id) }}"
                    class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
                    style="border-radius: 8px;">
                    <i class="ri-download-line"></i> Exportar
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.75rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="px-1 py-2 fw-semibold text-center" width="3%">#</th>
                            <th class="px-1 py-2 fw-semibold">Estudiante</th>
                            <th class="px-1 py-2 fw-semibold text-center" width="6%">Carnet</th>
                            <th class="px-1 py-2 fw-semibold text-center" width="8%">Plan</th>
                            <th class="px-1 py-2 fw-semibold text-center" width="8%">Vendedor</th>
                            <th class="px-1 py-2 fw-semibold text-center" width="6%">F. Insc</th>
                            <th class="px-1 py-2 fw-semibold text-center" width="6%">Profesión</th>
                            <th class="px-1 py-2 fw-semibold text-center" width="7%">Celular</th>
                            <th class="px-1 py-2 fw-semibold text-center" width="8%">Correo</th>
                            <th class="px-1 py-2 fw-semibold text-end" width="6%">Total Plan</th>
                            <th class="px-1 py-2 fw-semibold text-end" width="5%">Matrícula</th>
                            <th class="px-1 py-2 fw-semibold text-end" width="5%">Colegiatura</th>
                            <th class="px-1 py-2 fw-semibold text-end" width="5%">Certificación</th>
                            <th class="px-1 py-2 fw-semibold text-end" width="5%">Pagado</th>
                            <th class="px-1 py-2 fw-semibold text-end" width="5%">Saldo</th>
                            <th class="px-1 py-2 fw-semibold text-center" width="3%">%</th>
                            <th class="px-1 py-2 fw-semibold text-center" width="5%">Progreso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($participantesFinanzas as $index => $participante)
                            @php
                                $color = match (true) {
                                    $participante['porcentaje_pagado'] >= 100 => '#16a34a',
                                    $participante['porcentaje_pagado'] >= 70 => '#0891b2',
                                    $participante['porcentaje_pagado'] >= 50 => '#d97706',
                                    default => '#dc2626',
                                };
                                $matricula = $participante['conceptos']['Matrícula'] ?? [
                                    'total' => 0,
                                    'pagado' => 0,
                                    'pendiente' => 0,
                                ];
                                $colegiatura = $participante['conceptos']['Colegiatura'] ?? [
                                    'total' => 0,
                                    'pagado' => 0,
                                    'pendiente' => 0,
                                ];
                                $certificacion = $participante['conceptos']['Certificación'] ?? [
                                    'total' => 0,
                                    'pagado' => 0,
                                    'pendiente' => 0,
                                ];
                            @endphp
                            <tr>
                                <td class="px-2 py-2 text-center">{{ $index + 1 }}</td>
                                <td class="px-2 py-2">
                                    <a href="{{ route('admin.estudiantes.detalle', $participante['estudiante_id']) }}"
                                        class="text-decoration-none">
                                        <strong>{{ $participante['nombre_completo'] }}</strong>
                                    </a>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <span class="badge fs-9 text-white"
                                        style="background: #16a34a;">{{ $participante['carnet'] }}</span>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <span class="badge fs-9"
                                        style="background: var(--dash-primary-light); color: var(--dash-primary);">
                                        {{ $participante['plan_pago'] }}
                                    </span>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    @if ($participante['vendedor_persona_id'] ?? null)
                                        <a href="{{ route('admin.vendedor.inscripciones', $participante['vendedor_persona_id']) }}"
                                            class="text-decoration-none" style="color: #0284c7;">
                                            {{ $participante['vendedor'] ?? 'N/A' }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ $participante['vendedor'] ?? 'N/A' }}</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <span
                                        class="text-muted fs-10">{{ \Carbon\Carbon::parse($participante['fecha_inscripcion'])->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <span class="text-muted fs-10">{{ $participante['profesion'] }}</span>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    @if ($participante['celular'] != 'Sin celular')
                                        <a href="tel:{{ $participante['celular'] }}"
                                            class="text-decoration-none text-success">{{ $participante['celular'] }}</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2 text-center">
                                    @if ($participante['correo'] != 'Sin correo')
                                        <a href="mailto:{{ $participante['correo'] }}"
                                            class="text-decoration-none text-primary" style="font-size: 0.7rem;">
                                            {{ Str::limit($participante['correo'], 15) }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2 text-end fw-bold">
                                    {{ number_format($participante['total_plan'], 2) }}</td>
                                <td class="px-2 py-2 text-end">
                                    <div class="text-success">{{ number_format($matricula['pagado'], 2) }}</div>
                                    <div class="text-muted fs-9">Total: {{ number_format($matricula['total'], 2) }}
                                    </div>
                                </td>
                                <td class="px-2 py-2 text-end">
                                    <div class="text-success">{{ number_format($colegiatura['pagado'], 2) }}</div>
                                    <div class="text-muted fs-9">Total: {{ number_format($colegiatura['total'], 2) }}
                                    </div>
                                </td>
                                <td class="px-2 py-2 text-end">
                                    <div class="text-success">{{ number_format($certificacion['pagado'], 2) }}</div>
                                    <div class="text-muted fs-9">Total:
                                        {{ number_format($certificacion['total'], 2) }}</div>
                                </td>
                                <td class="px-2 py-2 text-end text-success fw-bold">
                                    {{ number_format($participante['total_pagado'], 2) }}</td>
                                <td class="px-2 py-2 text-end text-danger fw-bold">
                                    {{ number_format($participante['saldo'], 2) }}</td>
                                <td class="px-2 py-2 text-center">
                                    <span class="badge fs-10"
                                        style="background: {{ $color }}20; color: {{ $color }};">
                                        {{ number_format($participante['porcentaje_pagado'], 0) }}%
                                    </span>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <div class="progress" style="height: 6px; width: 60px; background: #e2e8f0;">
                                        <div class="progress-bar"
                                            style="width: {{ $participante['porcentaje_pagado'] }}%; background: {{ $color }};">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
