<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\OfertasAcademica;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ContabilidadController extends Controller
{
    public function dashboard(Request $request)
    {
        // Obtener gestión y mes del request (defaults: actual)
        $gestion = $request->input('gestion', date('Y'));
        $mes = $request->input('mes', date('n'));
        
        // Calcular período
        $inicio = Carbon::create($gestion, 1, 1)->startOfYear();
        $fin = Carbon::create($gestion, $mes, 1)->endOfMonth();
        
        // Lista de gestiones disponibles (últimos 10 años o desde BD)
        $anioActual = (int) date('Y');
        $anioInicio = $anioActual - 10;
        
        $gestionesExistentes = Pago::selectRaw('YEAR(fecha_pago) as anio')
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');
        
        if ($gestionesExistentes->isNotEmpty()) {
            $anioInicio = min($anioInicio, $gestionesExistentes->min());
        }
        
        $gestiones = collect(range($anioInicio, $anioActual));
        
        // Nombres de meses
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        $nombreMes = $meses[$mes];
        
        // Calcular total cobrado acumulado hasta $fin del período
        $cobradoAcumulado = Pago::where('fecha_pago', '<=', $fin)->sum('monto_total');
        
        // Calcular ingresos diarios por concepto para el gráfico
        $ingresosDiarios = Pago::whereBetween('fecha_pago', [$inicio, $fin])
            ->with(['pagosCuotas.cuota'])
            ->get()
            ->groupBy(function ($pago) {
                return $pago->fecha_pago->format('Y-m-d');
            })
            ->map(function ($pagos) {
                $matricula = 0;
                $colegiatura = 0;
                $certificacion = 0;
                
                foreach ($pagos as $pago) {
                    if (!$pago->pagosCuotas) continue;
                    
                    foreach ($pago->pagosCuotas as $pc) {
                        if (!$pc->cuota) continue;
                        
                        $concepto = $this->determinarConcepto($pc->cuota->nombre);
                        $monto = (float) ($pc->monto_bs ?? 0);
                        
                        if ($concepto === 'Matrícula') {
                            $matricula += $monto;
                        } elseif ($concepto === 'Colegiatura') {
                            $colegiatura += $monto;
                        } elseif ($concepto === 'Certificación') {
                            $certificacion += $monto;
                        }
                    }
                }
                
                return [
                    'matricula' => $matricula,
                    'colegiatura' => $colegiatura,
                    'certificacion' => $certificacion,
                    'total' => $matricula + $colegiatura + $certificacion,
                ];
            });

        // Generar array de días del mes y datos para el gráfico
        $diasEnMes = Carbon::create($gestion, $mes, 1)->daysInMonth;
        $labelsDias = range(1, $diasEnMes);

        $datosMatricula = [];
        $datosColegiatura = [];
        $datosCertificacion = [];

        foreach ($labelsDias as $dia) {
            $fecha = Carbon::create($gestion, $mes, $dia)->format('Y-m-d');
            $datos = $ingresosDiarios[$fecha] ?? ['matricula' => 0, 'colegiatura' => 0, 'certificacion' => 0];
            $datosMatricula[] = $datos['matricula'];
            $datosColegiatura[] = $datos['colegiatura'];
            $datosCertificacion[] = $datos['certificacion'];
        }
        
        // Obtener IDs de pagos del período para filtrar cuotas
        $pagosIds = Pago::whereBetween('fecha_pago', [$inicio, $fin])->pluck('id');
        
        // Obtener pagos acumulados hasta fin del período por inscripción
        $pagosAcumulados = Pago::where('fecha_pago', '<=', $fin)
            ->with(['pagosCuotas.cuota.inscripcion'])
            ->get()
            ->flatMap(function ($pago) {
                return $pago->pagosCuotas->map(function ($pc) use ($pago) {
                    return [
                        'inscripcion_id' => $pc->cuota?->inscripcione_id,
                        'monto' => $pc->monto_bs,
                        'fecha' => $pago->fecha_pago,
                    ];
                });
            })
            ->groupBy('inscripcion_id')
            ->map(fn($items) => $items->sum('monto'));
        
        $ofertas = OfertasAcademica::with([
            'programa',
            'fase',
            'inscripciones' => function ($q) {
                $q->whereIn('estado', ['Inscrito', 'Confirmado', 'Pre-Inscrito'])
                    ->with(['estudiante.persona', 'cuotas' => function($cq) {
                        $cq->orderBy('n_cuota');
                    }]);
            }
        ])
        ->whereHas('fase', function ($q) {
            $q->whereIn('nombre', ['Inscripciones', 'En Desarrollo', 'Finalizado']);
        })
        ->orderBy('codigo', 'desc')
        ->get();

        $ofertasData = [];
        $totalesGlobales = [
            'total_programado' => 0,
            'total_pagado' => $cobradoAcumulado,
            'total_pendiente' => 0,
            'total_inscritos' => 0,
        ];

        $emptyConceptos = fn() => [
            'Matrícula' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'porcentaje' => 0, 'cantidad_cuotas' => 0, 'cuotas' => []],
            'Colegiatura' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'porcentaje' => 0, 'cantidad_cuotas' => 0, 'cuotas' => []],
            'Certificación' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'porcentaje' => 0, 'cantidad_cuotas' => 0, 'cuotas' => []],
        ];
        $conceptosGlobales              = $emptyConceptos();
        $conceptosGlobalesIngresosReales = $emptyConceptos();
        $conceptosGlobalesRetirados     = $emptyConceptos();

        $totalesGlobalesActivos = ['total_programado' => 0, 'total_pagado' => 0, 'total_pendiente' => 0];
        $totalesGlobalesRetirados = ['total_programado' => 0, 'total_pagado' => 0, 'total_pendiente' => 0];
        $inscritosActivosGlobal   = 0;
        $inscritosRetiradosGlobal = 0;

        foreach ($ofertas as $oferta) {
            $inscritosCount = 0;
            $inscritosActivos = 0;
            $inscritosRetirados = 0;

            // Agregados base por concepto: activos y retirados (luego se componen las 3 vistas)
            $resumenActivos   = $emptyConceptos();
            $resumenRetirados = $emptyConceptos();

            // Totales base por oferta (activos vs retirados)
            $totProgA = 0; $totPagA = 0; $totPendA = 0;
            $totProgR = 0; $totPagR = 0; $totPendR = 0;

            $inscripcionesConPagos = 0;

            foreach ($oferta->inscripciones as $inscripcion) {
                $inscritosCount++;
                $esRetirado = !((bool) ($inscripcion->activo ?? true));
                if ($esRetirado) { $inscritosRetirados++; } else { $inscritosActivos++; }

                $tienePagos = false;

                foreach ($inscripcion->cuotas as $cuota) {
                    $totalCuota = (float) ($cuota->monto_bs ?? 0);

                    // Pagado de la cuota dentro del período seleccionado
                    $pagosCuota = \App\Models\PagosCuota::where('cuota_id', $cuota->id)
                        ->whereHas('pago', function ($q) use ($inicio, $fin) {
                            $q->whereBetween('fecha_pago', [$inicio, $fin]);
                        })
                        ->sum('monto_bs');

                    $pagadoCuota = (float) $pagosCuota;
                    $pendienteCuota = max(0, $totalCuota - $pagadoCuota);

                    if ($pagadoCuota > 0) { $tienePagos = true; }

                    $concepto = $this->determinarConcepto($cuota->nombre);
                    $target = $esRetirado ? $resumenRetirados : $resumenActivos;
                    if (isset($target[$concepto])) {
                        $bucket = $esRetirado ? 'resumenRetirados' : 'resumenActivos';
                        ${$bucket}[$concepto]['total']     += $totalCuota;
                        ${$bucket}[$concepto]['pagado']    += $pagadoCuota;
                        ${$bucket}[$concepto]['pendiente'] += $pendienteCuota;
                        ${$bucket}[$concepto]['cuotas'][] = [
                            'n_cuota'   => $cuota->n_cuota,
                            'nombre'    => $cuota->nombre,
                            'monto'     => $totalCuota,
                            'pagado'    => $pagadoCuota,
                            'pendiente' => $pendienteCuota,
                            'estado'    => $cuota->estado,
                        ];

                        if ($esRetirado) {
                            $totProgR += $totalCuota; $totPagR += $pagadoCuota; $totPendR += $pendienteCuota;
                        } else {
                            $totProgA += $totalCuota; $totPagA += $pagadoCuota; $totPendA += $pendienteCuota;
                        }
                    }
                }

                if ($tienePagos) { $inscripcionesConPagos++; }
            }

            // Componer las 3 vistas a partir de activos/retirados
            $resumenPorConcepto              = $emptyConceptos();
            $resumenPorConceptoIngresosReales = $emptyConceptos();
            foreach ($resumenActivos as $c => $a) {
                $r = $resumenRetirados[$c];

                // 1. Completo = activos + retirados
                $resumenPorConcepto[$c]['total']     = $a['total'] + $r['total'];
                $resumenPorConcepto[$c]['pagado']    = $a['pagado'] + $r['pagado'];
                $resumenPorConcepto[$c]['pendiente'] = $a['pendiente'] + $r['pendiente'];
                $resumenPorConcepto[$c]['cuotas']    = array_merge($a['cuotas'], $r['cuotas']);
                $resumenPorConcepto[$c]['cantidad_cuotas'] = count($resumenPorConcepto[$c]['cuotas']);

                // 2. Ingresos Reales = activos completos + lo cobrado de retirados (pendiente solo de activos)
                $resumenPorConceptoIngresosReales[$c]['total']     = $a['total'] + $r['pagado'];
                $resumenPorConceptoIngresosReales[$c]['pagado']    = $a['pagado'] + $r['pagado'];
                $resumenPorConceptoIngresosReales[$c]['pendiente'] = $a['pendiente'];
                $resumenPorConceptoIngresosReales[$c]['cuotas']    = $a['cuotas'];
                $resumenPorConceptoIngresosReales[$c]['cantidad_cuotas'] = count($a['cuotas']);

                // 3. Retirados (pendiente = pérdida)
                $r['cantidad_cuotas'] = count($r['cuotas']);
                $resumenRetirados[$c] = $r;
            }

            $aplicarPorcentaje = function (array &$resumen) {
                foreach ($resumen as $c => $d) {
                    $resumen[$c]['porcentaje'] = $d['total'] > 0 ? ($d['pagado'] / $d['total']) * 100 : 0;
                }
            };
            $aplicarPorcentaje($resumenPorConcepto);
            $aplicarPorcentaje($resumenPorConceptoIngresosReales);
            $aplicarPorcentaje($resumenRetirados);

            // Acumular en globales (3 vistas)
            foreach (['Matrícula','Colegiatura','Certificación'] as $c) {
                $conceptosGlobales[$c]['total']           += $resumenPorConcepto[$c]['total'];
                $conceptosGlobales[$c]['pagado']          += $resumenPorConcepto[$c]['pagado'];
                $conceptosGlobales[$c]['pendiente']       += $resumenPorConcepto[$c]['pendiente'];
                $conceptosGlobales[$c]['cantidad_cuotas'] += $resumenPorConcepto[$c]['cantidad_cuotas'];

                $conceptosGlobalesIngresosReales[$c]['total']           += $resumenPorConceptoIngresosReales[$c]['total'];
                $conceptosGlobalesIngresosReales[$c]['pagado']          += $resumenPorConceptoIngresosReales[$c]['pagado'];
                $conceptosGlobalesIngresosReales[$c]['pendiente']       += $resumenPorConceptoIngresosReales[$c]['pendiente'];
                $conceptosGlobalesIngresosReales[$c]['cantidad_cuotas'] += $resumenPorConceptoIngresosReales[$c]['cantidad_cuotas'];

                $conceptosGlobalesRetirados[$c]['total']           += $resumenRetirados[$c]['total'];
                $conceptosGlobalesRetirados[$c]['pagado']          += $resumenRetirados[$c]['pagado'];
                $conceptosGlobalesRetirados[$c]['pendiente']       += $resumenRetirados[$c]['pendiente'];
                $conceptosGlobalesRetirados[$c]['cantidad_cuotas'] += $resumenRetirados[$c]['cantidad_cuotas'];
            }

            $totalesGlobalesActivos['total_programado'] += $totProgA;
            $totalesGlobalesActivos['total_pagado']     += $totPagA;
            $totalesGlobalesActivos['total_pendiente']  += $totPendA;
            $totalesGlobalesRetirados['total_programado'] += $totProgR;
            $totalesGlobalesRetirados['total_pagado']     += $totPagR;
            $totalesGlobalesRetirados['total_pendiente']  += $totPendR;
            $inscritosActivosGlobal   += $inscritosActivos;
            $inscritosRetiradosGlobal += $inscritosRetirados;

            $totalProgramado = $totProgA + $totProgR;
            $totalPagado     = $totPagA + $totPagR;
            $totalPendiente  = $totPendA + $totPendR;

            $ofertasData[] = [
                'id' => $oferta->id,
                'codigo' => $oferta->codigo,
                'nombre' => $oferta->nombre,
                'programa' => $oferta->programa?->nombre,
                'fase' => $oferta->fase?->nombre,
                'inscritos' => $inscritosCount,
                'inscritos_activos'   => $inscritosActivos,
                'inscritos_retirados' => $inscritosRetirados,
                'inscripciones_con_pagos' => $inscripcionesConPagos,
                'total_programado' => $totalProgramado,
                'total_pagado' => $totalPagado,
                'total_pendiente' => $totalPendiente,
                'porcentaje_pagado' => $totalProgramado > 0 ? ($totalPagado / $totalProgramado) * 100 : 0,
                'resumen_por_concepto' => $resumenPorConcepto,
                'resumen_por_concepto_ingresos_reales' => $resumenPorConceptoIngresosReales,
                'resumen_por_concepto_retirados' => $resumenRetirados,
                'total_programado_ingresos' => $totProgA + $totPagR,
                'total_pagado_ingresos'     => $totPagA + $totPagR,
                'total_pendiente_ingresos'  => $totPendA,
                'total_programado_retirados' => $totProgR,
                'total_pagado_retirados'     => $totPagR,
                'total_pendiente_retirados'  => $totPendR,
            ];

            $totalesGlobales['total_programado'] += $totalProgramado;
            $totalesGlobales['total_inscritos']  += $inscritosCount;
        }

        // Calcular pendiente global: Programado - Cobrado Acumulado
        $totalesGlobales['total_pendiente'] = $totalesGlobales['total_programado'] - $cobradoAcumulado;
        
        $totalesGlobales['porcentaje_pagado'] = $totalesGlobales['total_programado'] > 0 
            ? ($cobradoAcumulado / $totalesGlobales['total_programado']) * 100 
            : 0;

        $calcPorcentaje = function (array &$res) {
            foreach ($res as $c => $d) {
                $res[$c]['porcentaje'] = $d['total'] > 0 ? ($d['pagado'] / $d['total']) * 100 : 0;
            }
        };
        $calcPorcentaje($conceptosGlobales);
        $calcPorcentaje($conceptosGlobalesIngresosReales);
        $calcPorcentaje($conceptosGlobalesRetirados);

        // Totales globales para los tabs "Ingresos Reales" y "Retirados"
        $totalesGlobalesIngresosReales = [
            'total_programado' => $totalesGlobalesActivos['total_programado'] + $totalesGlobalesRetirados['total_pagado'],
            'total_pagado'     => $totalesGlobalesActivos['total_pagado']     + $totalesGlobalesRetirados['total_pagado'],
            'total_pendiente'  => $totalesGlobalesActivos['total_pendiente'],
            'total_inscritos'  => $inscritosActivosGlobal,
        ];
        $totalesGlobalesIngresosReales['porcentaje_pagado'] = $totalesGlobalesIngresosReales['total_programado'] > 0
            ? ($totalesGlobalesIngresosReales['total_pagado'] / $totalesGlobalesIngresosReales['total_programado']) * 100 : 0;

        $totalesGlobalesRetiradosFinal = [
            'total_programado' => $totalesGlobalesRetirados['total_programado'],
            'total_pagado'     => $totalesGlobalesRetirados['total_pagado'],
            'total_pendiente'  => $totalesGlobalesRetirados['total_pendiente'],
            'total_inscritos'  => $inscritosRetiradosGlobal,
        ];
        $totalesGlobalesRetiradosFinal['porcentaje_pagado'] = $totalesGlobalesRetiradosFinal['total_programado'] > 0
            ? ($totalesGlobalesRetiradosFinal['total_pagado'] / $totalesGlobalesRetiradosFinal['total_programado']) * 100 : 0;

        return view('admin.contabilidad.index', compact(
            'ofertasData',
            'totalesGlobales', 'totalesGlobalesIngresosReales', 'totalesGlobalesRetiradosFinal',
            'conceptosGlobales', 'conceptosGlobalesIngresosReales', 'conceptosGlobalesRetirados',
            'inscritosActivosGlobal', 'inscritosRetiradosGlobal',
            'gestion', 'mes', 'gestiones', 'meses', 'nombreMes', 'inicio', 'fin',
            'labelsDias', 'datosMatricula', 'datosColegiatura', 'datosCertificacion', 'diasEnMes'
        ));
    }

    private function determinarConcepto($nombreCuota)
    {
        $nombreRaw = $nombreCuota ?? '';
        $concepto = trim(preg_replace('/(?:-|cuota|nro\.?|n°|#)\s*\d+/i', '', $nombreRaw));
        
        if (stripos($concepto, 'matr') !== false) {
            return 'Matrícula';
        }
        if (stripos($concepto, 'coleg') !== false) {
            return 'Colegiatura';
        }
        if (stripos($concepto, 'certif') !== false) {
            return 'Certificación';
        }
        return 'Colegiatura';
    }

    public function deudasRetrasadas(Request $request)
    {
        $hoy = now()->toDateString();

        $query = OfertasAcademica::with(['inscripciones' => function ($q) {
                $q->where('inscripciones.estado', 'Inscrito')
                    ->with(['estudiante.persona']);
            }])
            ->whereHas('fase', function ($q) {
                $q->whereIn('nombre', ['Inscripciones', 'En Desarrollo']);
            })
            ->whereHas('inscripciones', function ($q) use ($hoy) {
                $q->where('inscripciones.estado', 'Inscrito')
                    ->whereHas('cuotas', function ($q2) use ($hoy) {
                        $q2->where('estado', '!=', 'Pagado')
                            ->where('fecha_vencimiento', '<=', $hoy);
                    });
            });

        if ($request->filled('oferta_id')) {
            $query->where('id', $request->oferta_id);
        }

        $ofertas = $query->orderBy('codigo')->get();

        $resultados = [];
        foreach ($ofertas as $oferta) {
            $estudiantesConDeuda = [];

            foreach ($oferta->inscripciones as $inscripcion) {
                $persona = $inscripcion->estudiante?->persona;
                if (!$persona) continue;

                $nombreCompleto = trim($persona->nombres . ' ' . $persona->apellido_paterno . ' ' . ($persona->apellido_materno ?? ''));

                $cuotas = Cuota::where('inscripcione_id', $inscripcion->id)
                    ->where('estado', '!=', 'Pagado')
                    ->where('fecha_vencimiento', '<=', $hoy)
                    ->orderBy('fecha_vencimiento')
                    ->orderBy('n_cuota')
                    ->get();

                if ($cuotas->isEmpty()) continue;

                $montoTotal = $cuotas->sum('pago_pendiente_bs');

                $estudiantesConDeuda[] = [
                    'estudiante_id'   => $inscripcion->estudiante_id,
                    'nombre'          => $nombreCompleto,
                    'apellido_paterno' => $persona->apellido_paterno,
                    'apellido_materno' => $persona->apellido_materno ?? '',
                    'nombres'         => $persona->nombres,
                    'celular'         => $persona->celular,
                    'cuotas'          => $cuotas->map(fn($c) => [
                        'id'         => $c->id,
                        'n_cuota'    => $c->n_cuota,
                        'nombre'     => $c->nombre,
                        'monto_bs'   => (float) $c->pago_pendiente_bs,
                        'fecha_pago' => $c->fecha_vencimiento,
                        'estado'     => 'retrasada',
                    ]),
                    'retrasadas'  => $cuotas->count(),
                    'monto_total' => (float) $montoTotal,
                ];
            }

            if (empty($estudiantesConDeuda)) continue;

            usort($estudiantesConDeuda, function ($a, $b) {
                $cmp = strcasecmp($a['apellido_paterno'], $b['apellido_paterno']);
                if ($cmp !== 0) return $cmp;
                $cmp = strcasecmp($a['apellido_materno'], $b['apellido_materno']);
                if ($cmp !== 0) return $cmp;
                return strcasecmp($a['nombres'], $b['nombres']);
            });

            $estudiantesConDeuda = array_map(function ($est) {
                unset($est['apellido_paterno'], $est['apellido_materno'], $est['nombres']);
                return $est;
            }, $estudiantesConDeuda);

            $resultados[] = [
                'oferta_id'         => $oferta->id,
                'oferta_nombre'     => $oferta->programa?->nombre ?? 'Programa ' . $oferta->codigo,
                'estudiantes'       => $estudiantesConDeuda,
                'total_estudiantes' => count($estudiantesConDeuda),
                'total_monto'       => array_sum(array_column($estudiantesConDeuda, 'monto_total')),
            ];
        }

        $todasOfertas = OfertasAcademica::whereHas('fase', function ($q) {
                $q->whereIn('nombre', ['Inscripciones', 'En Desarrollo']);
            })
            ->with('programa')
            ->orderBy('codigo')
            ->get()
            ->mapWithKeys(fn($o) => [$o->id => ($o->programa?->nombre ?? 'Programa') . ' - ' . $o->codigo]);

        return view('admin.contabilidad.deudas-retrasadas', compact('resultados', 'todasOfertas'));
    }

    public function cuotasProximas(Request $request)
    {
        $hoy        = now()->toDateString();
        $fechaLimite = now()->addDays(7)->toDateString();

        $query = OfertasAcademica::with(['inscripciones' => function ($q) {
                $q->where('inscripciones.estado', 'Inscrito')
                    ->with(['estudiante.persona']);
            }])
            ->whereHas('fase', function ($q) {
                $q->whereIn('nombre', ['Inscripciones', 'En Desarrollo']);
            })
            ->whereHas('inscripciones', function ($q) use ($hoy, $fechaLimite) {
                $q->where('inscripciones.estado', 'Inscrito')
                    ->whereHas('cuotas', function ($q2) use ($hoy, $fechaLimite) {
                        $q2->where('estado', '!=', 'Pagado')
                            ->where('fecha_vencimiento', '>', $hoy)
                            ->where('fecha_vencimiento', '<=', $fechaLimite);
                    });
            });

        if ($request->filled('oferta_id')) {
            $query->where('id', $request->oferta_id);
        }

        $ofertas = $query->orderBy('codigo')->get();

        $resultados = [];
        foreach ($ofertas as $oferta) {
            $estudiantesList = [];

            foreach ($oferta->inscripciones as $inscripcion) {
                $persona = $inscripcion->estudiante?->persona;
                if (!$persona) continue;

                $nombreCompleto = trim($persona->nombres . ' ' . $persona->apellido_paterno . ' ' . ($persona->apellido_materno ?? ''));

                $cuotas = Cuota::where('inscripcione_id', $inscripcion->id)
                    ->where('estado', '!=', 'Pagado')
                    ->where('fecha_vencimiento', '>', $hoy)
                    ->where('fecha_vencimiento', '<=', $fechaLimite)
                    ->orderBy('fecha_vencimiento')
                    ->orderBy('n_cuota')
                    ->get();

                if ($cuotas->isEmpty()) continue;

                $montoTotal = $cuotas->sum('pago_pendiente_bs');

                $estudiantesList[] = [
                    'estudiante_id'   => $inscripcion->estudiante_id,
                    'nombre'          => $nombreCompleto,
                    'apellido_paterno' => $persona->apellido_paterno,
                    'apellido_materno' => $persona->apellido_materno ?? '',
                    'nombres'         => $persona->nombres,
                    'celular'         => $persona->celular,
                    'cuotas'          => $cuotas->map(function ($c) {
                        $diasRestantes = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($c->fecha_vencimiento), false);
                        return [
                            'id'             => $c->id,
                            'n_cuota'        => $c->n_cuota,
                            'nombre'         => $c->nombre,
                            'monto_bs'       => (float) $c->pago_pendiente_bs,
                            'fecha_pago'     => $c->fecha_vencimiento,
                            'estado'         => 'proxima',
                            'dias_restantes' => max(0, $diasRestantes),
                        ];
                    }),
                    'proximas'    => $cuotas->count(),
                    'monto_total' => (float) $montoTotal,
                ];
            }

            if (empty($estudiantesList)) continue;

            usort($estudiantesList, function ($a, $b) {
                $cmp = strcasecmp($a['apellido_paterno'], $b['apellido_paterno']);
                if ($cmp !== 0) return $cmp;
                $cmp = strcasecmp($a['apellido_materno'], $b['apellido_materno']);
                if ($cmp !== 0) return $cmp;
                return strcasecmp($a['nombres'], $b['nombres']);
            });

            $estudiantesList = array_map(function ($est) {
                unset($est['apellido_paterno'], $est['apellido_materno'], $est['nombres']);
                return $est;
            }, $estudiantesList);

            $resultados[] = [
                'oferta_id'         => $oferta->id,
                'oferta_nombre'     => $oferta->programa?->nombre ?? 'Programa ' . $oferta->codigo,
                'estudiantes'       => $estudiantesList,
                'total_estudiantes' => count($estudiantesList),
                'total_monto'       => array_sum(array_column($estudiantesList, 'monto_total')),
            ];
        }

        $todasOfertas = OfertasAcademica::whereHas('fase', function ($q) {
                $q->whereIn('nombre', ['Inscripciones', 'En Desarrollo']);
            })
            ->with('programa')
            ->orderBy('codigo')
            ->get()
            ->mapWithKeys(fn($o) => [$o->id => ($o->programa?->nombre ?? 'Programa') . ' - ' . $o->codigo]);

        return view('admin.contabilidad.cuotas-proximas', compact('resultados', 'todasOfertas'));
    }

    public function recibos(Request $request)
    {
        $gestion = $request->input('gestion', date('Y'));
        $mes = $request->input('mes', date('n'));

        $inicio = Carbon::create($gestion, $mes, 1)->startOfMonth();
        $fin = Carbon::create($gestion, $mes, 1)->endOfMonth();

        $gestiones = Pago::selectRaw('YEAR(fecha_pago) as anio')
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        if ($gestiones->isEmpty()) {
            $gestiones = collect([date('Y')]);
        }

        $pagos = Pago::with([
            'trabajadorCargo.trabajador.persona',
            'trabajadorCargo.cargo',
            'pagosCuotas.cuota.inscripcion.estudiante.persona',
            'pagosCuotas.cuota.inscripcion.ofertaAcademica.programa',
            'detalles.caja.trabajadorCargo.trabajador.persona',
            'detalles.cuentaBancaria.banco',
        ])
        ->whereBetween('fecha_pago', [$inicio, $fin])
        ->orderBy('recibo', 'desc')
        ->paginate(15);

        $stats = $this->getStatsByConcept($inicio, $fin);

        return view('admin.contabilidad.recibos', compact('pagos', 'gestion', 'mes', 'gestiones', 'stats'));
    }

    private function getStatsByConcept($inicio, $fin)
    {
        $pagosCuotas = \App\Models\PagosCuota::whereHas('pago', function ($query) use ($inicio, $fin) {
            $query->whereBetween('fecha_pago', [$inicio, $fin]);
        })->with('cuota')->get();

        $matricula = 0;
        $colegiatura = 0;
        $certificacion = 0;

        foreach ($pagosCuotas as $pc) {
            $nombre = strtoupper($pc->cuota->nombre ?? '');
            if (str_contains($nombre, 'MATRÍCULA') || str_contains($nombre, 'MATRICULA')) {
                $matricula += $pc->monto_bs;
            } elseif (str_contains($nombre, 'COLEGIATURA')) {
                $colegiatura += $pc->monto_bs;
            } elseif (str_contains($nombre, 'CERTIF') || str_contains($nombre, 'CERTIFIC')) {
                $certificacion += $pc->monto_bs;
            }
        }

        $detalles = \App\Models\Detalle::whereHas('pago', function ($query) use ($inicio, $fin) {
            $query->whereBetween('fecha_pago', [$inicio, $fin]);
        })->get();

        $efectivo = $detalles->where('tipo_pago', 'Efectivo')->sum('monto_bs');
        $qr       = $detalles->where('tipo_pago', 'Qr')->sum('monto_bs');
        $transferencia = $detalles->where('tipo_pago', 'Transferencia')->sum('monto_bs');

        return [
            'matricula'    => $matricula,
            'colegiatura'  => $colegiatura,
            'certificacion' => $certificacion,
            'total'        => $matricula + $colegiatura + $certificacion,
            'efectivo'     => $efectivo,
            'qr'           => $qr,
            'transferencia' => $transferencia,
        ];
    }

    public function subirFactura(Request $request, $pagoId)
    {
        $pago = Pago::findOrFail($pagoId);

        $request->validate([
            'documento_factura' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        if ($pago->documento_factura) {
            Storage::disk('public')->delete($pago->documento_factura);
        }

        $path = $request->file('documento_factura')->store('facturas', 'public');

        $pago->update(['documento_factura' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Factura subida correctamente.',
            'path'    => Storage::url($path),
        ]);
    }

    /**
     * Control de pagos: detalle por trabajador, por oferta y por método.
     * Soporta filtros por rango (fecha_inicio/fecha_fin) o gestión/mes.
     */
    public function controlPagos(Request $request)
    {
        $modo = $request->input('modo', 'mes');   // 'mes' | 'rango'

        // ── Rango de fechas ──
        if ($modo === 'rango' && $request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $inicio = Carbon::parse($request->input('fecha_inicio'))->startOfDay();
            $fin    = Carbon::parse($request->input('fecha_fin'))->endOfDay();
            $gestion = $inicio->year;
            $mes     = $inicio->month;
        } else {
            $modo    = 'mes';
            $gestion = (int) $request->input('gestion', date('Y'));
            $mes     = (int) $request->input('mes', date('n'));
            $inicio  = Carbon::create($gestion, $mes, 1)->startOfMonth();
            $fin     = Carbon::create($gestion, $mes, 1)->endOfMonth();
        }

        // ── Gestiones disponibles ──
        $gestiones = Pago::selectRaw('YEAR(fecha_pago) as anio')
            ->distinct()->orderBy('anio', 'desc')->pluck('anio');
        if ($gestiones->isEmpty()) {
            $gestiones = collect([(int) date('Y')]);
        }

        // ── Carga de pagos del período ──
        $pagos = Pago::with([
                'trabajadorCargo.trabajador.persona',
                'trabajadorCargo.cargo',
                'pagosCuotas.cuota.inscripcion.estudiante.persona',
                'pagosCuotas.cuota.inscripcion.ofertaAcademica.programa',
                'detalles.caja.trabajadorCargo.trabajador.persona',
                'detalles.cuentaBancaria.banco',
            ])
            ->whereBetween('fecha_pago', [$inicio, $fin])
            ->orderBy('fecha_pago', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // ── Stats globales ──
        $totalGeneral  = (float) $pagos->sum('monto_total');
        $totalDesc     = (float) $pagos->sum('descuento_bs');
        $cantidadPagos = $pagos->count();

        $totalEfectivo      = 0.0;
        $totalQr            = 0.0;
        $totalTransferencia = 0.0;
        $totalOtros         = 0.0;

        foreach ($pagos as $p) {
            foreach ($p->detalles as $d) {
                $monto = (float) $d->monto_bs;
                $tipo  = $d->tipo_pago;
                if ($tipo === 'Efectivo')              $totalEfectivo      += $monto;
                elseif ($tipo === 'Qr')                $totalQr            += $monto;
                elseif ($tipo === 'Transferencia')     $totalTransferencia += $monto;
                else                                   $totalOtros         += $monto;
            }
        }

        // ── Por trabajador (cobrador) ──
        $porTrabajador = [];
        foreach ($pagos as $p) {
            $tc       = $p->trabajadorCargo;
            $tcId     = $tc?->id ?? 0;
            $nombre   = '—';
            $cargo    = '—';
            if ($tc && $tc->trabajador && $tc->trabajador->persona) {
                $persona = $tc->trabajador->persona;
                $nombre  = trim(($persona->nombres ?? '').' '.($persona->apellido_paterno ?? '').' '.($persona->apellido_materno ?? '')) ?: '—';
            }
            if ($tc) {
                $cargo = $tc->cargo->nombre ?? ($tc->nombre_cargo ?? '—');
            }

            if (!isset($porTrabajador[$tcId])) {
                $porTrabajador[$tcId] = [
                    'id' => $tcId, 'nombre' => $nombre, 'cargo' => $cargo,
                    'cantidad' => 0, 'total' => 0.0, 'efectivo' => 0.0, 'qr' => 0.0, 'transferencia' => 0.0, 'otros' => 0.0,
                ];
            }
            $porTrabajador[$tcId]['cantidad']++;
            $porTrabajador[$tcId]['total'] += (float) $p->monto_total;
            foreach ($p->detalles as $d) {
                $m = (float) $d->monto_bs;
                $t = $d->tipo_pago;
                if ($t === 'Efectivo')          $porTrabajador[$tcId]['efectivo']      += $m;
                elseif ($t === 'Qr')            $porTrabajador[$tcId]['qr']            += $m;
                elseif ($t === 'Transferencia') $porTrabajador[$tcId]['transferencia'] += $m;
                else                            $porTrabajador[$tcId]['otros']         += $m;
            }
        }
        $porTrabajador = collect($porTrabajador)->sortByDesc('total')->values()->all();

        // ── Por oferta académica ──
        $porOferta = [];
        foreach ($pagos as $p) {
            // Una oferta por pago (tomamos la primera cuota relacionada)
            $ofertaId     = 0;
            $ofertaNombre = '—';
            foreach ($p->pagosCuotas as $pc) {
                if ($pc->cuota && $pc->cuota->inscripcion && $pc->cuota->inscripcion->ofertaAcademica) {
                    $oa = $pc->cuota->inscripcion->ofertaAcademica;
                    $ofertaId     = $oa->id;
                    $ofertaNombre = $oa->programa->nombre ?? ($oa->nombre ?? ('Oferta #'.$oa->id));
                    break;
                }
            }
            if (!isset($porOferta[$ofertaId])) {
                $porOferta[$ofertaId] = [
                    'id' => $ofertaId, 'nombre' => $ofertaNombre,
                    'cantidad' => 0, 'total' => 0.0, 'estudiantes' => [],
                ];
            }
            $porOferta[$ofertaId]['cantidad']++;
            $porOferta[$ofertaId]['total'] += (float) $p->monto_total;
            // Tracking de estudiantes únicos
            foreach ($p->pagosCuotas as $pc) {
                if ($pc->cuota && $pc->cuota->inscripcion && $pc->cuota->inscripcion->estudiante) {
                    $porOferta[$ofertaId]['estudiantes'][$pc->cuota->inscripcion->estudiante->id] = true;
                }
            }
        }
        foreach ($porOferta as &$o) {
            $o['estudiantes_count'] = count($o['estudiantes']);
            unset($o['estudiantes']);
        }
        unset($o);
        $porOferta = collect($porOferta)->sortByDesc('total')->values()->all();

        // ── Serie diaria para gráfico ──
        $diasMap = [];
        $cursor  = $inicio->copy();
        while ($cursor->lte($fin)) {
            $diasMap[$cursor->format('Y-m-d')] = [
                'fecha' => $cursor->format('Y-m-d'),
                'efectivo' => 0.0, 'qr' => 0.0, 'transferencia' => 0.0, 'otros' => 0.0,
            ];
            $cursor->addDay();
        }
        foreach ($pagos as $p) {
            $key = Carbon::parse($p->fecha_pago)->format('Y-m-d');
            if (!isset($diasMap[$key])) continue;
            foreach ($p->detalles as $d) {
                $m = (float) $d->monto_bs;
                $t = $d->tipo_pago;
                if ($t === 'Efectivo')          $diasMap[$key]['efectivo']      += $m;
                elseif ($t === 'Qr')            $diasMap[$key]['qr']            += $m;
                elseif ($t === 'Transferencia') $diasMap[$key]['transferencia'] += $m;
                else                            $diasMap[$key]['otros']         += $m;
            }
        }
        $chartLabels        = array_map(fn($d) => Carbon::parse($d)->locale('es')->translatedFormat('j \d\e F \d\e\l Y'), array_keys($diasMap));
        $chartEfectivo      = array_map(fn($d) => round($d['efectivo'], 2),      array_values($diasMap));
        $chartQr            = array_map(fn($d) => round($d['qr'], 2),            array_values($diasMap));
        $chartTransferencia = array_map(fn($d) => round($d['transferencia'], 2), array_values($diasMap));

        // ── Detalle de pagos para la tabla ──
        $detallePagos = $pagos->map(function ($p) {
            // Estudiante / oferta
            $estNombre   = '—';
            $ofertaNombre = '—';
            foreach ($p->pagosCuotas as $pc) {
                if ($pc->cuota && $pc->cuota->inscripcion) {
                    $ins = $pc->cuota->inscripcion;
                    if ($ins->estudiante && $ins->estudiante->persona) {
                        $pp = $ins->estudiante->persona;
                        $estNombre = trim(($pp->nombres ?? '').' '.($pp->apellido_paterno ?? '').' '.($pp->apellido_materno ?? '')) ?: '—';
                    }
                    if ($ins->ofertaAcademica) {
                        $ofertaNombre = $ins->ofertaAcademica->programa->nombre ?? ($ins->ofertaAcademica->nombre ?? '—');
                    }
                    break;
                }
            }
            // Cobrador
            $cobrador = '—';
            if ($p->trabajadorCargo && $p->trabajadorCargo->trabajador && $p->trabajadorCargo->trabajador->persona) {
                $cp = $p->trabajadorCargo->trabajador->persona;
                $cobrador = trim(($cp->nombres ?? '').' '.($cp->apellido_paterno ?? '').' '.($cp->apellido_materno ?? '')) ?: '—';
            }
            // Detalles enriquecidos
            $detalles = $p->detalles->map(function ($d) {
                $cajaInfo = null;
                if ($d->caja) {
                    $enc = null;
                    if ($d->caja->trabajadorCargo && $d->caja->trabajadorCargo->trabajador && $d->caja->trabajadorCargo->trabajador->persona) {
                        $pp = $d->caja->trabajadorCargo->trabajador->persona;
                        $enc = trim(($pp->nombres ?? '').' '.($pp->apellido_paterno ?? '').' '.($pp->apellido_materno ?? '')) ?: null;
                    }
                    $cajaInfo = ['nombre' => $d->caja->nombre, 'encargado' => $enc];
                }
                $cuentaInfo = null;
                if ($d->cuentaBancaria) {
                    $cuentaInfo = [
                        'banco'         => $d->cuentaBancaria->banco->nombre ?? '—',
                        'numero'        => $d->cuentaBancaria->numero_cuenta,
                        'tipo'          => $d->cuentaBancaria->tipo_cuenta,
                    ];
                }
                return [
                    'tipo'       => $d->tipo_pago,
                    'monto'      => (float) $d->monto_bs,
                    'caja'       => $cajaInfo,
                    'cuenta'     => $cuentaInfo,
                    'referencia' => $d->referencia,
                ];
            })->values()->all();

            return [
                'id'           => $p->id,
                'recibo'       => $p->recibo,
                'fecha'        => $p->fecha_pago,
                'estudiante'   => $estNombre,
                'oferta'       => $ofertaNombre,
                'cobrador'     => $cobrador,
                'monto'        => (float) $p->monto_total,
                'descuento'    => (float) ($p->descuento_bs ?? 0),
                'tipo_pago'    => $p->tipo_pago,
                'detalles'     => $detalles,
                'pdf_url'      => route('admin.estudiantes.generarReciboPdf', ['pagoId' => $p->id]),
            ];
        })->values()->all();

        return view('admin.contabilidad.control-pagos', [
            'modo'               => $modo,
            'gestion'            => $gestion,
            'mes'                => $mes,
            'inicio'             => $inicio,
            'fin'                => $fin,
            'gestiones'          => $gestiones,
            'totalGeneral'       => $totalGeneral,
            'totalDesc'          => $totalDesc,
            'cantidadPagos'      => $cantidadPagos,
            'totalEfectivo'      => $totalEfectivo,
            'totalQr'            => $totalQr,
            'totalTransferencia' => $totalTransferencia,
            'totalOtros'         => $totalOtros,
            'porTrabajador'      => $porTrabajador,
            'porOferta'          => $porOferta,
            'chartLabels'        => $chartLabels,
            'chartEfectivo'      => $chartEfectivo,
            'chartQr'            => $chartQr,
            'chartTransferencia' => $chartTransferencia,
            'detallePagos'       => $detallePagos,
        ]);
    }
}
