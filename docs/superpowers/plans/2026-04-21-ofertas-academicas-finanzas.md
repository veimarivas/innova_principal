# Ofertas Académicas Finanzas Tab Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Actualizar el tab "finanzas" en ofertas-academicas.detalle.blade para mostrar resumen por concepto, gráficos y tabla detallada con pagos por concepto.

**Architecture:** Modificar el controlador para generar datos por concepto (Matrícula, Colegiatura, Certificación), reemplazar la vista tab-finanzas.blade.php con el diseño de codigo-prueba.blade.php, y agregar inicialización de Chart.js.

**Tech Stack:** Laravel/Blade, Chart.js

---

### Task 1: Actualizar Controlador - Método detalle()

**Files:**
- Modify: `app/Http/Controllers/OfertasAcademicaController.php:248-286`

- [ ] **Step 1: Modificar el método detalle() para agregar relaciones**

Reemplazar el bloque de carga de relaciones (líneas 248-252):
```php
$inscripciones = $oferta->inscripciones()
    ->with([
        'estudiante.persona', 
        'estudiante.persona.estudios.grado',
        'planesPago', 
        'cuotas.pagosCuota',
        'trabajador.persona'
    ])
    ->whereIn('estado', ['Inscrito', 'Pre-Inscrito', 'Confirmado'])
    ->get();
```

- [ ] **Step 2: Agregar lógica de resumen por concepto**

Reemplazar el bloque de procesamiento (líneas 254-284):
```php
$participantesFinanzas = [];
$resumenPorConcepto = [
    'Matrícula' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'porcentaje' => 0],
    'Colegiatura' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'porcentaje' => 0],
    'Certificación' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'porcentaje' => 0],
];
$totalPlan = 0;
$totalPagado = 0;

foreach ($inscripciones as $inscripcion) {
    if (!$inscripcion->estudiante) continue;

    $estudiante = $inscripcion->estudiante;
    $persona = $estudiante->persona;
    
    // Datos por concepto para este estudiante
    $conceptosEstudiante = [
        'Matrícula' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0],
        'Colegiatura' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0],
        'Certificación' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0],
    ];
    
    // Procesar cuotas por concepto
    foreach ($inscripcion->cuotas as $cuota) {
        $concepto = $cuota->nombre ?? 'Otro';
        $totalCuota = $cuota->monto_bs;
        $pagadoCuota = $cuota->pagosCuota->sum('monto_bs');
        $pendienteCuota = $totalCuota - $pagadoCuota;
        
        if (isset($conceptosEstudiante[$concepto])) {
            $conceptosEstudiante[$concepto]['total'] += $totalCuota;
            $conceptosEstudiante[$concepto]['pagado'] += $pagadoCuota;
            $conceptosEstudiante[$concepto]['pendiente'] += $pendienteCuota;
            
            $resumenPorConcepto[$concepto]['total'] += $totalCuota;
            $resumenPorConcepto[$concepto]['pagado'] += $pagadoCuota;
            $resumenPorConcepto[$concepto]['pendiente'] += $pendienteCuota;
        } else {
            // Concepto no reconocido, agrupar en "Otro"
            if (!isset($conceptosEstudiante['Otro'])) {
                $conceptosEstudiante['Otro'] = ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
            }
            $conceptosEstudiante['Otro']['total'] += $totalCuota;
            $conceptosEstudiante['Otro']['pagado'] += $pagadoCuota;
            $conceptosEstudiante['Otro']['pendiente'] += $pendienteCuota;
        }
    }
    
    $totalInscripcion = array_sum(array_column($conceptosEstudiante, 'total'));
    $pagadoInscripcion = array_sum(array_column($conceptosEstudiante, 'pagado'));
    $saldoInscripcion = $totalInscripcion - $pagadoInscripcion;
    $porcentaje = $totalInscripcion > 0 ? ($pagadoInscripcion / $totalInscripcion) * 100 : 0;
    
    // Obtener profesión del estudio principal
    $estudioPrincipal = $estudiante->persona->estudios()->where('principal', 1)->first();
    $profesion = $estudioPrincipal?->profesion?->nombre ?? '—';
    
    // Obtener vendedor
    $vendedorNombre = $inscripcion->trabajador?->persona 
        ? trim(($inscripcion->trabajador->persona->nombres ?? '') . ' ' . ($inscripcion->trabajador->persona->apellido_paterno ?? ''))
        : '—';
    
    $participantesFinanzas[] = [
        'estudiante_id' => $estudiante->id,
        'nombre_completo' => trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')),
        'carnet' => $persona->carnet ?? '—',
        'plan_pago' => $inscripcion->planesPago?->nombre ?? '—',
        'vendedor' => $vendedorNombre,
        'vendedor_persona_id' => $inscripcion->trabajador?->id,
        'fecha_inscripcion' => $inscripcion->fecha_registro,
        'profesion' => $profesion,
        'celular' => $persona->celular ?? 'Sin celular',
        'correo' => $persona->correo ?? 'Sin correo',
        'total_plan' => $totalInscripcion,
        'conceptos' => $conceptosEstudiante,
        'total_pagado' => $pagadoInscripcion,
        'saldo' => $saldoInscripcion,
        'porcentaje_pagado' => $porcentaje,
    ];

    $totalPlan += $totalInscripcion;
    $totalPagado += $pagadoInscripcion;
}

// Calcular porcentajes del resumen global
foreach ($resumenPorConcepto as $concepto => $datos) {
    $resumenPorConcepto[$concepto]['porcentaje'] = $datos['total'] > 0 
        ? ($datos['pagado'] / $datos['total']) * 100 
        : 0;
}
```

- [ ] **Step 3: Actualizar el return del método**

Reemplazar línea 286:
```php
return view('admin.ofertas-academicas.detalle', compact(
    'oferta', 'respAcademico', 'respMarketing', 
    'participantesFinanzas', 'resumenPorConcepto',
    'totalPlan', 'totalPagado'
));
```

---

### Task 2: Reemplazar Vista tab-finanzas.blade.php

**Files:**
- Modify: `resources/views/admin/ofertas-academicas/partials/ofertas-detalle/tab-finanzas.blade.php`

- [ ] **Step 1: Reemplazar todo el contenido del archivo**

Reemplazar todo el contenido con:
```blade
<div class="tab-content-section" id="tab-finanzas">
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
                        <i class="ri-pie-chart-line align-middle me-2" style="color: #fc7b04;"></i>
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
                        <i class="ri-bar-chart-line align-middle me-2" style="color: #fc7b04;"></i>
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
                    <i class="ri-wallet-line align-middle me-2" style="color: #fc7b04;"></i>
                    Estado Financiero de Participantes
                </h5>
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
                        @forelse($participantesFinanzas as $index => $participante)
                            @php
                                $color = match (true) {
                                    $participante['porcentaje_pagado'] >= 100 => '#16a34a',
                                    $participante['porcentaje_pagado'] >= 70 => '#0891b2',
                                    $participante['porcentaje_pagado'] >= 50 => '#d97706',
                                    default => '#dc2626',
                                };
                                $matricula = $participante['conceptos']['Matrícula'] ?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                $colegiatura = $participante['conceptos']['Colegiatura'] ?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
                                $certificacion = $participante['conceptos']['Certificación'] ?? ['total' => 0, 'pagado' => 0, 'pendiente' => 0];
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
                                    <span class="badge fs-9 text-white" style="background: #16a34a;">{{ $participante['carnet'] }}</span>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <span class="badge fs-9"
                                        style="background: #fc7b0420; color: #fc7b04;">
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
                                    <span class="text-muted fs-10">{{ \Carbon\Carbon::parse($participante['fecha_inscripcion'])->format('d/m/Y') }}</span>
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
                                    <div class="text-muted fs-9">Total: {{ number_format($matricula['total'], 2) }}</div>
                                </td>
                                <td class="px-2 py-2 text-end">
                                    <div class="text-success">{{ number_format($colegiatura['pagado'], 2) }}</div>
                                    <div class="text-muted fs-9">Total: {{ number_format($colegiatura['total'], 2) }}</div>
                                </td>
                                <td class="px-2 py-2 text-end">
                                    <div class="text-success">{{ number_format($certificacion['pagado'], 2) }}</div>
                                    <div class="text-muted fs-9">Total: {{ number_format($certificacion['total'], 2) }}</div>
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
                        @empty
                            <tr>
                                <td colspan="17" class="text-center py-4 text-muted">
                                    <i class="ri-wallet-line" style="font-size: 2rem; opacity: 0.5;"></i>
                                    <p>No hay participantes con información financiera</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
```

---

### Task 3: Agregar Chart.js y Inicialización de Gráficos

**Files:**
- Modify: `resources/views/admin/ofertas-academicas/detalle.blade.php:34-38`
- Modify: `resources/views/admin/ofertas-academicas/partials/ofertas-detalle/scripts.blade.php`

- [ ] **Step 1: Agregar Chart.js CDN en detalle.blade.php**

En la sección @section('script'), agregar antes de incluir los otros scripts:
```php
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ URL::asset('build/libs/fullcalendar/index.global.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    @include('admin.ofertas-academicas.partials.ofertas-detalle.scripts')
@endsection
```

- [ ] **Step 2: Agregar inicialización de charts al final de scripts.blade.php**

Agregar al final del archivo (antes del cierre del IIFE principal):
```javascript
// ===== FINANZAS CHARTS =====
(function() {
    'use strict';
    
    const resumenPorConcepto = @json($resumenPorConcepto ?? []);
    if (!resumenPorConcepto || Object.keys(resumenPorConcepto).length === 0) return;

    const conceptos = Object.keys(resumenPorConcepto);
    const conceptosFiltrados = conceptos.filter(function(c) {
        return (resumenPorConcepto[c].total || 0) > 0;
    });
    
    if (conceptosFiltrados.length === 0) return;

    const ingresosData = conceptosFiltrados.map(function(c) {
        return resumenPorConcepto[c].pagado || 0;
    });
    const cobranzaData = conceptosFiltrados.map(function(c) {
        return resumenPorConcepto[c].porcentaje || 0;
    });

    // Colors
    var colors = {
        'Matrícula': '#2563eb',
        'Colegiatura': '#0891b2',
        'Certificación': '#d97706',
    };
    var bgColors = conceptosFiltrados.map(function(c) {
        return colors[c] || '#64748b';
    });

    // Ingresos por Concepto (Pie/Doughnut)
    var ctx1 = document.getElementById('ingresosConceptoChart');
    if (ctx1) {
        new Chart(ctx1.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: conceptosFiltrados,
                datasets: [{
                    data: ingresosData,
                    backgroundColor: bgColors,
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 12, padding: 8 } }
                }
            }
        });
    }

    // Estado de Cobranza (Bar)
    var ctx2 = document.getElementById('cobranzaConceptoChart');
    if (ctx2) {
        new Chart(ctx2.getContext('2d'), {
            type: 'bar',
            data: {
                labels: conceptosFiltrados,
                datasets: [{
                    label: '% Cobrado',
                    data: cobranzaData,
                    backgroundColor: bgColors,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, max: 100 }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
})();
```

---

### Task 4: Verificación

**Files:**
- Test: Visitar `/admin/ofertas-academicas/{id}/detalle` y hacer clic en tab "Finanzas"

- [ ] **Step 1: Verificar que se muestren los cards por concepto**

Esperado: 3 cards (Matrícula, Colegiatura, Certificación) con totales, pagado, pendiente y barra de progreso

- [ ] **Step 2: Verificar que se muestren los gráficos**

Esperado: Gráfico doughnut de ingresos y gráfico de barras de cobranza

- [ ] **Step 3: Verificar tabla detallada**

Esperado: Tabla con columnas por concepto mostrando desglose de pagos por estudiante

- [ ] **Step 4: Verificar datos correctos**

Esperado: Los totales globales coinciden con la suma de los conceptos mostrados