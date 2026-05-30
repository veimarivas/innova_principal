<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\OfertasAcademica;
use App\Models\Pago;
use Illuminate\Http\Request;
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

        $conceptosGlobales = [
            'Matrícula' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'cantidad_cuotas' => 0],
            'Colegiatura' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'cantidad_cuotas' => 0],
            'Certificación' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'cantidad_cuotas' => 0],
        ];

        foreach ($ofertas as $oferta) {
            $totalProgramado = 0;
            $totalPagado = 0;
            $totalPendiente = 0;
            $inscritosCount = 0;

            $resumenPorConcepto = [
                'Matrícula' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'porcentaje' => 0, 'cuotas' => []],
                'Colegiatura' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'porcentaje' => 0, 'cuotas' => []],
                'Certificación' => ['total' => 0, 'pagado' => 0, 'pendiente' => 0, 'porcentaje' => 0, 'cuotas' => []],
            ];

            $inscripcionesConPagos = 0;

            foreach ($oferta->inscripciones as $inscripcion) {
                $inscritosCount++;
                $tienePagos = false;
                
                $inscripcionId = $inscripcion->id;
                $pagadoAcumuladoIns = $pagosAcumulados[$inscripcionId] ?? 0;

                foreach ($inscripcion->cuotas as $cuota) {
                    $totalCuota = (float) ($cuota->monto_bs ?? 0);
                    
                    // Calcular pagado de la cuota basado en pagos del período
                    $pagosCuota = \App\Models\PagosCuota::where('cuota_id', $cuota->id)
                        ->whereHas('pago', function ($q) use ($inicio, $fin) {
                            $q->whereBetween('fecha_pago', [$inicio, $fin]);
                        })
                        ->sum('monto_bs');
                    
                    $pagadoCuota = (float) $pagosCuota;
                    $pendienteCuota = max(0, $totalCuota - $pagadoCuota);

                    if ($pagadoCuota > 0) {
                        $tienePagos = true;
                    }

                    $totalProgramado += $totalCuota;
                    $totalPagado += $pagadoCuota;
                    $totalPendiente += $pendienteCuota;

                    $concepto = $this->determinarConcepto($cuota->nombre);
                    if (isset($resumenPorConcepto[$concepto])) {
                        $resumenPorConcepto[$concepto]['total'] += $totalCuota;
                        $resumenPorConcepto[$concepto]['pagado'] += $pagadoCuota;
                        $resumenPorConcepto[$concepto]['pendiente'] += $pendienteCuota;
                        $resumenPorConcepto[$concepto]['cuotas'][] = [
                            'n_cuota' => $cuota->n_cuota,
                            'nombre' => $cuota->nombre,
                            'monto' => $totalCuota,
                            'pagado' => $pagadoCuota,
                            'pendiente' => $pendienteCuota,
                            'estado' => $cuota->estado,
                        ];
                    }
                }

                if ($tienePagos) {
                    $inscripcionesConPagos++;
                }
            }

            foreach ($resumenPorConcepto as $concepto => &$datos) {
                if ($datos['total'] > 0) {
                    $datos['porcentaje'] = ($datos['pagado'] / $datos['total']) * 100;
                    $datos['cantidad_cuotas'] = count($datos['cuotas']);
                    
                    $conceptosGlobales[$concepto]['total'] += $datos['total'];
                    $conceptosGlobales[$concepto]['pagado'] += $datos['pagado'];
                    $conceptosGlobales[$concepto]['pendiente'] += $datos['pendiente'];
                    $conceptosGlobales[$concepto]['cantidad_cuotas'] += count($datos['cuotas']);
                }
            }

            $ofertasData[] = [
                'id' => $oferta->id,
                'codigo' => $oferta->codigo,
                'nombre' => $oferta->nombre,
                'programa' => $oferta->programa?->nombre,
                'fase' => $oferta->fase?->nombre,
                'inscritos' => $inscritosCount,
                'inscripciones_con_pagos' => $inscripcionesConPagos,
                'total_programado' => $totalProgramado,
                'total_pagado' => $totalPagado,
                'total_pendiente' => $totalPendiente,
                'porcentaje_pagado' => $totalProgramado > 0 ? ($totalPagado / $totalProgramado) * 100 : 0,
                'resumen_por_concepto' => $resumenPorConcepto,
            ];

            $totalesGlobales['total_programado'] += $totalProgramado;
            // $totalesGlobales['total_pagado'] ya tiene el valor acumulado de BD
            // $totalesGlobales['total_pendiente'] se calcula después
            $totalesGlobales['total_inscritos'] += $inscritosCount;
        }

        // Calcular pendiente global: Programado - Cobrado Acumulado
        $totalesGlobales['total_pendiente'] = $totalesGlobales['total_programado'] - $cobradoAcumulado;
        
        $totalesGlobales['porcentaje_pagado'] = $totalesGlobales['total_programado'] > 0 
            ? ($cobradoAcumulado / $totalesGlobales['total_programado']) * 100 
            : 0;

        foreach ($conceptosGlobales as $concepto => &$datos) {
            if ($datos['total'] > 0) {
                $datos['porcentaje'] = ($datos['pagado'] / $datos['total']) * 100;
            }
        }

        return view('admin.contabilidad.index', compact(
            'ofertasData', 'totalesGlobales', 'conceptosGlobales',
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
            'detalles'
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
}
