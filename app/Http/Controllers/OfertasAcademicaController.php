<?php

namespace App\Http\Controllers;

use App\Models\OfertasAcademica;
use App\Models\Posgrado;
use App\Models\Sucursale;
use App\Models\Modalidade;
use App\Models\Programa;
use App\Models\Fase;
use App\Models\Area;
use App\Models\Tipo;
use App\Models\Convenio;
use App\Models\TrabajadoresCargo;
use App\Models\PlanesPago;
use App\Models\PlanesConcepto;
use App\Models\Concepto;
use App\Models\Inscripcione;
use App\Models\Persona;
use App\Models\Estudiante;
use App\Models\Trabajadore;
use App\Models\Cuota;
use App\Models\Matriculacione;
use App\Models\Modulo;
use App\Models\MoodleMatricula;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PagoRespaldo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OfertasAcademicaController extends Controller
{
    public function index($posgradoId)
    {
        $posgrado = Posgrado::findOrFail($posgradoId);
        $sucursales = Sucursale::orderBy('nombre', 'asc')->get();
        $modalidades = Modalidade::orderBy('nombre', 'asc')->get();
        $programas = Programa::orderBy('nombre', 'asc')->get();
        $fases = Fase::orderBy('nombre', 'asc')->get();
        $trabajadores = TrabajadoresCargo::select('trabajadores_cargos.*')
            ->with(['trabajador.persona', 'cargo'])
            ->leftJoin('cargos', 'trabajadores_cargos.cargo_id', '=', 'cargos.id')
            ->orderBy('cargos.nombre', 'asc')
            ->get();
        return view('admin.ofertas-academicas.index', compact(
            'posgrado',
            'sucursales',
            'modalidades',
            'programas',
            'fases',
            'trabajadores'
        ));
    }

    /**
     * Obtiene el plan de pagos (cuotas) de una inscripción específica.
     */
    public function planPagosParticipante($ofertaId, $inscripcionId)
    {
        try {
            $inscripcion = Inscripcione::with(['planesPago', 'cuotas'])
                ->where('id', $inscripcionId)
                ->where('ofertas_academica_id', $ofertaId)
                ->firstOrFail();

            $planNombre = $inscripcion->planesPago->nombre ?? 'Sin plan';

            // Agrupar cuotas por concepto (extraído del campo 'nombre')
            $cuotas = $inscripcion->cuotas()
                ->orderBy('n_cuota')
                ->get()
                ->map(function ($cuota) {
                    return [
                        'id' => $cuota->id,
                        'nombre' => $cuota->nombre,
                        'n_cuota' => $cuota->n_cuota,
                        'monto_bs' => $cuota->monto_bs,
                        'pago_pendiente_bs' => $cuota->pago_pendiente_bs,
                        'descuento_bs' => $cuota->descuento_bs,
                        'fecha_vencimiento' => $cuota->fecha_vencimiento ? $cuota->fecha_vencimiento->format('d/m/Y') : null,
                        'fecha_pago' => $cuota->fecha_pago ? $cuota->fecha_pago->format('d/m/Y') : null,
                        'estado' => $cuota->estado,
                    ];
                });

            // Agrupar manualmente por concepto (primeras palabras del nombre)
            $grupos = [];
            foreach ($cuotas as $cuota) {
                $partes = explode(' - ', $cuota['nombre']);
                $concepto = $partes[0] ?? 'Otro';
                if (!isset($grupos[$concepto])) {
                    $grupos[$concepto] = [
                        'concepto' => $concepto,
                        'cuotas' => [],
                        'subtotal' => 0,
                    ];
                }
                $grupos[$concepto]['cuotas'][] = $cuota;
                $grupos[$concepto]['subtotal'] += $cuota['monto_bs'];
            }

            $totalGeneral = array_sum(array_column($grupos, 'subtotal'));

            return response()->json([
                'success' => true,
                'plan_nombre' => $planNombre,
                'grupos' => array_values($grupos),
                'total_general' => $totalGeneral,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en planPagosParticipante: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudo cargar el plan de pagos.'], 500);
        }
    }

    public function cambiarAInscrito(Request $request, $ofertaId, $inscripcionId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'planes_pago_id' => 'required|exists:planes_pagos,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $inscripcion = Inscripcione::where('id', $inscripcionId)
                ->where('ofertas_academica_id', $ofertaId)
                ->firstOrFail();

            if ($inscripcion->estado !== 'Pre-Inscrito') {
                return response()->json(['success' => false, 'message' => 'Solo se puede cambiar desde estado Pre-Inscrito.'], 400);
            }

            DB::transaction(function () use ($inscripcion, $request, $ofertaId) {
                // Actualizar estado y plan
                $inscripcion->update([
                    'estado' => 'Inscrito',
                    'planes_pago_id' => $request->planes_pago_id,
                ]);

                // Indexar cuotas personalizadas enviadas desde el frontend
                $cuotasPersonalizadas = [];
                if ($request->filled('cuotas_personalizadas')) {
                    $decoded = json_decode($request->cuotas_personalizadas, true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $c) {
                            $key = intval($c['concepto_idx']) . '_' . intval($c['cuota_idx']);
                            $cuotasPersonalizadas[$key] = $c;
                        }
                    }
                }

                // Crear cuotas basadas en el plan seleccionado
                $planesConceptos = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
                    ->where('planes_pago_id', $request->planes_pago_id)
                    ->with('concepto')
                    ->get();

                $nCuota = 1;
                foreach ($planesConceptos as $conceptoIdx => $pc) {
                    $conceptoNombre = $pc->concepto->nombre ?? 'Concepto';
                    $nCuotas = $pc->n_cuotas ?? 1;
                    $montoPorCuotaDefault = $nCuotas > 0 ? round(floatval($pc->pago_bs) / $nCuotas, 2) : floatval($pc->pago_bs);

                    for ($i = 0; $i < $nCuotas; $i++) {
                        $key    = $conceptoIdx . '_' . $i;
                        $custom = $cuotasPersonalizadas[$key] ?? null;

                        $montoCuota = ($custom && isset($custom['monto']) && floatval($custom['monto']) > 0)
                            ? round(floatval($custom['monto']), 2)
                            : $montoPorCuotaDefault;

                        $fechaVencimiento = ($custom && !empty($custom['fecha']))
                            ? \Carbon\Carbon::parse($custom['fecha'])
                            : now()->addMonths($i + 1);

                        $nombreCuota = "{$conceptoNombre} - Cuota " . ($i + 1);

                        Cuota::create([
                            'inscripcione_id' => $inscripcion->id,
                            'nombre' => $nombreCuota,
                            'n_cuota' => $nCuota,
                            'monto_bs' => $montoCuota,
                            'pago_pendiente_bs' => $montoCuota,
                            'descuento_bs' => 0,
                            'fecha_vencimiento' => $fechaVencimiento,
                            'estado' => 'pendiente',
                        ]);
                        $nCuota++;
                    }
                }

                // Crear matriculaciones para todos los módulos de la oferta
                $modulos = Modulo::where('ofertas_academica_id', $ofertaId)->get();
                foreach ($modulos as $modulo) {
                    Matriculacione::firstOrCreate([
                        'inscripcione_id' => $inscripcion->id,
                        'modulo_id' => $modulo->id,
                    ]);
                }
            });

            return response()->json(['success' => true, 'message' => 'Inscripción completada correctamente.']);
        } catch (\Exception $e) {
            \Log::error('Error en cambiarAInscrito: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Lista todos los planes de pago configurados para una oferta (tengan o no conceptos asignados previamente).
     */
    public function listarPlanesConfigurados($ofertaId)
    {
        try {
            // Obtener los IDs de los planes que tienen configuraciones en esta oferta
            $planesIds = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
                ->distinct()
                ->pluck('planes_pago_id');

            $planes = PlanesPago::whereIn('id', $planesIds)
                ->orderBy('nombre')
                ->get(['id', 'nombre']);

            return response()->json(['data' => $planes]);
        } catch (\Exception $e) {
            \Log::error('Error en listarPlanesConfigurados: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudieron cargar los planes.'], 500);
        }
    }

    public function listar($posgradoId)
    {
        try {
            $ofertas = OfertasAcademica::with(['sucursal', 'modalidad', 'programa', 'fase', 'trabajador_cargo_academico.trabajador.persona', 'trabajador_cargo_marketing.trabajador.persona'])
                ->where('posgrado_id', $posgradoId)
                ->orderBy('gestion', 'desc')
                ->get();
            return response()->json(['data' => $ofertas]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function detalle($id)
    {
        $oferta = OfertasAcademica::with([
            'posgrado.convenio',
            'posgrado.area',
            'posgrado.tipo',
            'sucursal',
            'modalidad',
            'programa',
            'fase',
            'trabajador_cargo_academico.trabajador.persona',
            'trabajador_cargo_marketing.trabajador.persona',
            'modulos.docente.persona',
            'modulos.horarios.trabajadorCargo.trabajador.persona',
        ])->findOrFail($id);

        $respAcademico = '';
        if ($oferta->trabajador_cargo_academico && $oferta->trabajador_cargo_academico->trabajador && $oferta->trabajador_cargo_academico->trabajador->persona) {
            $p = $oferta->trabajador_cargo_academico->trabajador->persona;
            $respAcademico = trim(($p->nombres ?? '') . ' ' . ($p->apellido_paterno ?? '') . ' ' . ($p->apellido_materno ?? ''));
        }

        $respMarketing = '';
        if ($oferta->trabajador_cargo_marketing && $oferta->trabajador_cargo_marketing->trabajador && $oferta->trabajador_cargo_marketing->trabajador->persona) {
            $p = $oferta->trabajador_cargo_marketing->trabajador->persona;
            $respMarketing = trim(($p->nombres ?? '') . ' ' . ($p->apellido_paterno ?? '') . ' ' . ($p->apellido_materno ?? ''));
        }

        // Cargar datos financieros de los participantes
        $inscripciones = $oferta->inscripciones()
            ->with([
                'estudiante.persona',
                'estudiante.persona.ciudad.departamento',
                'estudiante.persona.estudios.grado_academico',
                'estudiante.persona.estudios.profesion',
                'estudiante.persona.estudios.universidad',
                'planesPago',
                'cuotas.pagosCuota',
                'trabajador_cargo.trabajador.persona'
            ])
            ->whereIn('estado', ['Inscrito', 'Pre-Inscrito', 'Confirmado'])
            ->get();

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
                $nombreRaw = $cuota->nombre ?? 'Otro';
                // Normalizar: "Matrícula - Cuota 1" -> "Matrícula", "Matrícula 1" -> "Matrícula"
                $concepto = trim(preg_replace('/(?:-|cuota|nro\.?|n°|#)\s*\d+/i', '', $nombreRaw));
                if (stripos($concepto, 'matr') !== false) {
                    $concepto = 'Matrícula';
                } elseif (stripos($concepto, 'coleg') !== false) {
                    $concepto = 'Colegiatura';
                } elseif (stripos($concepto, 'certif') !== false) {
                    $concepto = 'Certificación';
                } else {
                    $concepto = $nombreRaw;
                }
                
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
            $vendedorNombre = $inscripcion->trabajador_cargo?->trabajador?->persona 
                ? trim(($inscripcion->trabajador_cargo->trabajador->persona->nombres ?? '') . ' ' . ($inscripcion->trabajador_cargo->trabajador->persona->apellido_paterno ?? ''))
                : '—';
            
            $participantesFinanzas[] = [
                'estudiante_id' => $estudiante->id,
                'nombre_completo' => trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')),
                'carnet' => $persona->carnet ?? '—',
                'plan_pago' => $inscripcion->planesPago?->nombre ?? '—',
                'vendedor' => $vendedorNombre,
                'vendedor_persona_id' => $inscripcion->trabajador_cargo?->trabajador?->persona?->id,
                'fecha_inscripcion' => $inscripcion->fecha_registro,
                'profesion' => $profesion,
                'celular' => $persona->celular ?? 'Sin celular',
                'correo' => $persona->correo ?? 'Sin correo',
                'total_plan' => $totalInscripcion,
                'conceptos' => $conceptosEstudiante,
                'total_pagado' => $pagadoInscripcion,
                'saldo' => $saldoInscripcion,
                'porcentaje_pagado' => $porcentaje,
                'estado' => $inscripcion->estado,
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

        $trabajadores = TrabajadoresCargo::select('trabajadores_cargos.*')
            ->with(['trabajador.persona', 'cargo'])
            ->leftJoin('cargos', 'trabajadores_cargos.cargo_id', '=', 'cargos.id')
            ->orderBy('cargos.nombre', 'asc')
            ->get();

        // ── Área Académica: lista de estudiantes con su perfil completo ──────
        $areaAcademicaEstudiantes = [];
        foreach ($inscripciones as $inscripcion) {
            if (!$inscripcion->estudiante || !$inscripcion->estudiante->persona) continue;
            $persona = $inscripcion->estudiante->persona;

            $estudios = [];
            foreach ($persona->estudios as $est) {
                $grado     = $est->grado_academico?->nombre;
                $profesion = $est->profesion?->nombre;
                $universidad = $est->universidad?->nombre;
                $parts = array_filter([$grado, $profesion, $universidad], fn($v) => $v && trim($v) !== '');
                if (!empty($parts)) {
                    $estudios[] = [
                        'texto'      => implode(' — ', $parts),
                        'grado'      => $grado,
                        'profesion'  => $profesion,
                        'universidad'=> $universidad,
                        'estado'     => $est->estado,
                        'principal'  => (bool) $est->principal,
                    ];
                }
            }

            $areaAcademicaEstudiantes[] = [
                'inscripcion_id'   => $inscripcion->id,
                'estudiante_id'    => $inscripcion->estudiante->id,
                'carnet'           => $persona->carnet ?? '—',
                'apellido_paterno' => $persona->apellido_paterno ?? '',
                'apellido_materno' => $persona->apellido_materno ?? '',
                'nombres'          => $persona->nombres ?? '',
                'celular'          => $persona->celular ?? '—',
                'correo'           => $persona->correo ?? '—',
                'departamento'     => $persona->ciudad?->departamento?->nombre ?? '—',
                'ciudad'           => $persona->ciudad?->nombre ?? '—',
                'sexo'             => $persona->sexo ?? '—',
                'fecha_nacimiento' => $persona->fecha_nacimiento
                    ? (\Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y'))
                    : '—',
                'estado_civil'     => $persona->estado_civil ?? '—',
                'estudios'         => $estudios,
                'estado'           => $inscripcion->estado,
            ];
        }

        // Ordenar por apellido paterno, luego materno, luego nombres
        usort($areaAcademicaEstudiantes, function ($a, $b) {
            return strcmp(
                ($a['apellido_paterno'] . ' ' . $a['apellido_materno'] . ' ' . $a['nombres']),
                ($b['apellido_paterno'] . ' ' . $b['apellido_materno'] . ' ' . $b['nombres'])
            );
        });

        return view('admin.ofertas-academicas.detalle', compact(
            'oferta', 'respAcademico', 'respMarketing',
            'trabajadores',
            'participantesFinanzas', 'resumenPorConcepto',
            'totalPlan', 'totalPagado',
            'areaAcademicaEstudiantes'
        ));
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo'                    => 'required|string|max:50|unique:ofertas_academicas,codigo',
            'posgrado_id'               => 'required|exists:posgrados,id',
            'programa_id'               => 'required|exists:programas,id',
            'fase_id'                   => 'required|exists:fases,id',
            'sucursale_id'              => 'nullable|exists:sucursales,id',
            'modalidade_id'             => 'nullable|exists:modalidades,id',
            'fecha_inicio_inscripciones' => 'required|date',
            'fecha_inicio_programa'     => 'required|date|after_or_equal:fecha_inicio_inscripciones',
            'fecha_fin_programa'        => 'required|date|after_or_equal:fecha_inicio_programa',
            'gestion'                   => 'required|integer|min:2000',
            'n_modulos'                 => 'required|integer|min:1',
            'cantidad_sesiones'         => 'required|integer|min:1',
            'version'                   => 'required|integer|min:1',
            'grupo'                     => 'required|integer|min:1',
            'nota_minima'               => 'required|numeric|min:0|max:100',
            'color'                     => 'nullable|string|max:7',
            'responsable_marketing_id'  => 'nullable|exists:trabajadores_cargos,id',
            'responsable_academico_id'  => 'nullable|exists:trabajadores_cargos,id',
            'portada'                   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'certificado'               => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'duplicated_from'           => 'nullable|exists:ofertas_academicas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = [
            'codigo'                    => strtoupper($request->codigo),
            'posgrado_id'               => $request->posgrado_id,
            'programa_id'               => $request->programa_id,
            'fase_id'                   => $request->fase_id,
            'sucursale_id'              => $request->sucursale_id,
            'modalidade_id'             => $request->modalidade_id,
            'fecha_inicio_inscripciones' => $request->fecha_inicio_inscripciones,
            'fecha_inicio_programa'     => $request->fecha_inicio_programa,
            'fecha_fin_programa'        => $request->fecha_fin_programa,
            'gestion'                   => $request->gestion,
            'n_modulos'                 => $request->n_modulos,
            'cantidad_sesiones'         => $request->cantidad_sesiones,
            'version'                   => $request->version,
            'grupo'                     => $request->grupo,
            'nota_minima'               => $request->nota_minima,
            'color'                     => $request->color ?? '#fc7b04',
            'responsable_marketing_id'  => $request->responsable_marketing_id,
            'responsable_academico_id'  => $request->responsable_academico_id,
        ];

        if ($request->hasFile('portada')) {
            $data['portada'] = $request->file('portada')->store('ofertas/portadas', 'public');
        }
        if ($request->hasFile('certificado')) {
            $data['certificado'] = $request->file('certificado')->store('ofertas/certificados', 'public');
        }

        $oferta = OfertasAcademica::create($data);

        if ($request->filled('duplicated_from')) {
            $originalId = $request->duplicated_from;
            
            // Duplicar planes de conceptos
            $planes = PlanesConcepto::where('ofertas_academica_id', $originalId)->get();
            foreach ($planes as $plan) {
                $newPlan = $plan->replicate();
                $newPlan->ofertas_academica_id = $oferta->id;
                $newPlan->save();
            }

            // Duplicar módulos (sin horarios) y crear cursos en Moodle
            $modulos = Modulo::where('ofertas_academica_id', $originalId)->get();
            $moodle = app(\App\Services\MoodleService::class);
            $oferta->load('programa');
            $programa = $oferta->programa;

            if ($programa && !$programa->moodle_category_id) {
                $parentId = (int) config('moodle.category_parent', 0);
                $categoryId = $moodle->createCategory($programa->nombre, $parentId);
                if ($categoryId) {
                    $programa->moodle_category_id = $categoryId;
                    $programa->save();
                }
            }

            $moodleCategoryId = $programa->moodle_category_id ?? null;

            foreach ($modulos as $modulo) {
                $newModulo = $modulo->replicate();
                $newModulo->ofertas_academica_id = $oferta->id;
                $newModulo->docente_id = null;
                $newModulo->estado = 'No Inicio';
                
                // Crear curso en Moodle si existe categoría para el programa
                if ($moodleCategoryId) {
                    $shortname = $moodle->buildCourseShortname($oferta->id, $newModulo->n_modulo);
                    
                    // Asegurarse de que las fechas tengan el formato correcto para Moodle
                    $fInicio = $newModulo->fecha_inicio ? $newModulo->fecha_inicio->format('Y-m-d') : null;
                    $fFin = $newModulo->fecha_fin ? $newModulo->fecha_fin->format('Y-m-d') : null;

                    $moodleCourseId = $moodle->createCourse(
                        $newModulo->nombre,
                        $shortname,
                        $moodleCategoryId,
                        $fInicio,
                        $fFin,
                        54 // Course ID de plantilla para estructura
                    );
                    $newModulo->moodle_course_id = $moodleCourseId;
                } else {
                    $newModulo->moodle_course_id = null;
                }
                
                $newModulo->save();
            }

            $oferta->fase_id = 2;
            $oferta->save();
        }

        return response()->json(['success' => true, 'message' => 'Oferta académica registrada correctamente.', 'data' => $oferta]);
    }

    public function actualizar(Request $request, $id)
    {
        $oferta = OfertasAcademica::find($id);
        if (!$oferta) {
            return response()->json(['success' => false, 'message' => 'Oferta no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'codigo'                    => 'required|string|max:50|unique:ofertas_academicas,codigo,' . $id,
            'posgrado_id'               => 'required|exists:posgrados,id',
            'programa_id'               => 'required|exists:programas,id',
            'fase_id'                   => 'required|exists:fases,id',
            'sucursale_id'              => 'nullable|exists:sucursales,id',
            'modalidade_id'             => 'nullable|exists:modalidades,id',
            'fecha_inicio_inscripciones' => 'required|date',
            'fecha_inicio_programa'     => 'required|date|after_or_equal:fecha_inicio_inscripciones',
            'fecha_fin_programa'        => 'required|date|after_or_equal:fecha_inicio_programa',
            'gestion'                   => 'required|integer|min:2000',
            'n_modulos'                 => 'required|integer|min:1',
            'cantidad_sesiones'         => 'required|integer|min:1',
            'version'                   => 'required|integer|min:1',
            'grupo'                     => 'required|integer|min:1',
            'nota_minima'               => 'required|numeric|min:0|max:100',
            'color'                     => 'nullable|string|max:7',
            'responsable_marketing_id'  => 'nullable|exists:trabajadores_cargos,id',
            'responsable_academico_id'  => 'nullable|exists:trabajadores_cargos,id',
            'portada'                   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'certificado'               => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = [
            'codigo'                    => strtoupper($request->codigo),
            'posgrado_id'               => $request->posgrado_id,
            'programa_id'               => $request->programa_id,
            'fase_id'                   => $request->fase_id,
            'sucursale_id'              => $request->sucursale_id,
            'modalidade_id'             => $request->modalidade_id,
            'fecha_inicio_inscripciones' => $request->fecha_inicio_inscripciones,
            'fecha_inicio_programa'     => $request->fecha_inicio_programa,
            'fecha_fin_programa'        => $request->fecha_fin_programa,
            'gestion'                   => $request->gestion,
            'n_modulos'                 => $request->n_modulos,
            'cantidad_sesiones'         => $request->cantidad_sesiones,
            'version'                   => $request->version,
            'grupo'                     => $request->grupo,
            'nota_minima'               => $request->nota_minima,
            'color'                     => $request->color ?? '#fc7b04',
            'responsable_marketing_id'  => $request->responsable_marketing_id,
            'responsable_academico_id'  => $request->responsable_academico_id,
        ];

        if ($request->hasFile('portada')) {
            if ($oferta->portada) Storage::disk('public')->delete($oferta->portada);
            $data['portada'] = $request->file('portada')->store('ofertas/portadas', 'public');
        }
        if ($request->hasFile('certificado')) {
            if ($oferta->certificado) Storage::disk('public')->delete($oferta->certificado);
            $data['certificado'] = $request->file('certificado')->store('ofertas/certificados', 'public');
        }

        $oferta->update($data);

        return response()->json(['success' => true, 'message' => 'Oferta académica actualizada correctamente.', 'data' => $oferta]);
    }

    public function eliminar($id)
    {
        $oferta = OfertasAcademica::find($id);
        if (!$oferta) {
            return response()->json(['success' => false, 'message' => 'Oferta no encontrada.'], 404);
        }
        if ($oferta->portada) Storage::disk('public')->delete($oferta->portada);
        if ($oferta->certificado) Storage::disk('public')->delete($oferta->certificado);
        $oferta->delete();
        return response()->json(['success' => true, 'message' => 'Oferta académica eliminada correctamente.']);
    }

    public function listarPlanesConceptos($ofertaId)
    {
        $planes = PlanesConcepto::with(['plan_pago', 'concepto'])
            ->where('ofertas_academica_id', $ofertaId)
            ->orderBy('id', 'asc')
            ->get();
        return response()->json(['data' => $planes]);
    }

    public function listarPlanesPagoDisponibles($ofertaId)
    {
        $asignados = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
            ->pluck('planes_pago_id')
            ->toArray();

        $planes = PlanesPago::where('habilitado', true)
            ->whereNotIn('id', $asignados)
            ->orderBy('nombre', 'asc')
            ->get(['id', 'nombre', 'es_promocion', 'fecha_inicio_promocion', 'fecha_fin_promocion', 'principal']);
        return response()->json(['data' => $planes]);
    }

    public function listarConceptosDisponibles($ofertaId)
    {
        $conceptos = Concepto::orderBy('nombre', 'asc')
            ->get(['id', 'nombre']);
        return response()->json(['data' => $conceptos]);
    }

    public function verificarPlanPrincipal($ofertaId)
    {
        $planPrincipalId = PlanesPago::where('principal', true)->value('id');

        if (!$planPrincipalId) {
            return response()->json(['tiene_principal' => false, 'message' => 'No existe un plan de pago marcado como principal.']);
        }

        $tieneConceptos = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
            ->where('planes_pago_id', $planPrincipalId)
            ->exists();

        return response()->json([
            'tiene_principal' => $tieneConceptos,
            'plan_principal_id' => $planPrincipalId,
        ]);
    }

    public function obtenerPrecioBase($ofertaId, $conceptoId)
    {
        $planPrincipalId = PlanesPago::where('principal', true)->value('id');

        if (!$planPrincipalId) {
            return response()->json(['precio_base' => null, 'message' => 'No existe un plan principal configurado.']);
        }

        $registro = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
            ->where('planes_pago_id', $planPrincipalId)
            ->where('concepto_id', $conceptoId)
            ->first();

        if (!$registro) {
            return response()->json(['precio_base' => null, 'message' => 'No existe precio base para este concepto en el plan principal.']);
        }

        return response()->json(['precio_base' => $registro->pago_bs]);
    }

    public function guardarPlanesConceptoMultiple(Request $request, $ofertaId)
    {
        $validator = Validator::make($request->all(), [
            'planes_pago_id' => 'required|exists:planes_pagos,id',
            'conceptos' => 'required|array|min:1',
            'conceptos.*.concepto_id' => 'required|exists:conceptos,id',
            'conceptos.*.n_cuotas' => 'required|integer|min:1',
            'conceptos.*.precio_regular' => 'required|numeric|min:0',
            'conceptos.*.descuento_bs' => 'nullable|numeric|min:0',
        ], [
            'planes_pago_id.required' => 'Seleccione un plan de pago.',
            'conceptos.required' => 'Debe agregar al menos un concepto.',
            'conceptos.min' => 'Debe agregar al menos un concepto.',
            'conceptos.*.concepto_id.required' => 'Seleccione un concepto en cada fila.',
            'conceptos.*.n_cuotas.required' => 'Ingrese el número de cuotas.',
            'conceptos.*.n_cuotas.min' => 'Debe tener al menos 1 cuota.',
            'conceptos.*.precio_regular.required' => 'Ingrese el precio regular.',
            'conceptos.*.precio_regular.min' => 'El precio no puede ser negativo.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $planPagoId = $request->planes_pago_id;
        $conceptos = $request->conceptos;

        $yaRegistrado = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
            ->where('planes_pago_id', $planPagoId)
            ->exists();

        if ($yaRegistrado) {
            return response()->json(['success' => false, 'message' => 'Este plan ya tiene configuraciones en esta oferta.'], 400);
        }

        $planEsPromocion = PlanesPago::where('id', $planPagoId)->value('es_promocion');
        if ($planEsPromocion) {
            $planPrincipalId = PlanesPago::where('principal', true)->value('id');
            if ($planPrincipalId) {
                $tienePrincipal = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
                    ->where('planes_pago_id', $planPrincipalId)
                    ->exists();
            } else {
                $tienePrincipal = false;
            }

            if (!$tienePrincipal) {
                return response()->json(['success' => false, 'message' => 'Debe existir un plan principal configurado antes de registrar promociones.'], 400);
            }
        }

        $conceptoIds = array_column($conceptos, 'concepto_id');
        if (count($conceptoIds) !== count(array_unique($conceptoIds))) {
            return response()->json(['success' => false, 'message' => 'No puede repetir el mismo concepto en la misma configuración.'], 422);
        }

        foreach ($conceptos as $index => $c) {
            $descuento = $c['descuento_bs'] ?? 0;
            if ($descuento > $c['precio_regular']) {
                return response()->json([
                    'success' => false,
                    'message' => 'El descuento no puede ser mayor que el precio regular en la fila ' . ($index + 1) . '.',
                ], 422);
            }
        }

        $planNombre = PlanesPago::find($planPagoId)->nombre;
        $registrados = 0;

        DB::transaction(function () use ($ofertaId, $planPagoId, $conceptos, &$registrados) {
            foreach ($conceptos as $c) {
                $descuento = $c['descuento_bs'] ?? 0;
                PlanesConcepto::create([
                    'ofertas_academica_id' => $ofertaId,
                    'planes_pago_id' => $planPagoId,
                    'concepto_id' => $c['concepto_id'],
                    'n_cuotas' => $c['n_cuotas'],
                    'precio_regular' => $c['precio_regular'],
                    'descuento_bs' => $descuento,
                    'pago_bs' => max(0, $c['precio_regular'] - $descuento),
                ]);
                $registrados++;
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Configuración guardada: {$registrados} concepto(s) registrado(s) para {$planNombre}.",
            'data' => [
                'planes_pago_id' => $planPagoId,
                'conceptos_registrados' => $registrados,
            ],
        ]);
    }

    public function guardarPlanesConcepto(Request $request, $ofertaId)
    {
        $validator = Validator::make($request->all(), [
            'planes_pago_id' => 'required|exists:planes_pagos,id',
            'concepto_id' => 'required|exists:conceptos,id',
            'n_cuotas' => 'required|integer|min:1',
            'precio_regular' => 'required|numeric|min:0',
            'descuento_bs' => 'nullable|numeric|min:0',
            'pago_bs' => 'nullable|numeric|min:0',
        ], [
            'planes_pago_id.required' => 'Seleccione un plan de pago.',
            'planes_pago_id.exists' => 'El plan de pago seleccionado no existe.',
            'concepto_id.required' => 'Seleccione un concepto.',
            'concepto_id.exists' => 'El concepto seleccionado no existe.',
            'n_cuotas.required' => 'Ingrese el número de cuotas.',
            'n_cuotas.min' => 'Debe tener al menos 1 cuota.',
            'precio_regular.required' => 'Ingrese el precio regular.',
            'precio_regular.min' => 'El precio no puede ser negativo.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $existe = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
            ->where('planes_pago_id', $request->planes_pago_id)
            ->where('concepto_id', $request->concepto_id)
            ->exists();

        if ($existe) {
            return response()->json(['success' => false, 'message' => 'Esta combinación de plan y concepto ya está configurada para esta oferta.'], 400);
        }

        $plan = PlanesConcepto::create([
            'ofertas_academica_id' => $ofertaId,
            'planes_pago_id' => $request->planes_pago_id,
            'concepto_id' => $request->concepto_id,
            'n_cuotas' => $request->n_cuotas,
            'precio_regular' => $request->precio_regular,
            'descuento_bs' => $request->descuento_bs ?? 0,
            'pago_bs' => $request->pago_bs ?? ($request->precio_regular - ($request->descuento_bs ?? 0)),
        ]);

        $plan->load(['plan_pago', 'concepto']);

        return response()->json(['success' => true, 'message' => 'Configuración guardada correctamente.', 'data' => $plan]);
    }

    public function actualizarPlanesConcepto(Request $request, $id)
    {
        $plan = PlanesConcepto::find($id);
        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Configuración no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'n_cuotas' => 'required|integer|min:1',
            'precio_regular' => 'required|numeric|min:0',
            'descuento_bs' => 'nullable|numeric|min:0',
            'pago_bs' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $plan->update([
            'n_cuotas' => $request->n_cuotas,
            'precio_regular' => $request->precio_regular,
            'descuento_bs' => $request->descuento_bs ?? 0,
            'pago_bs' => $request->pago_bs ?? ($request->precio_regular - ($request->descuento_bs ?? 0)),
        ]);

        $plan->load(['plan_pago', 'concepto']);

        return response()->json(['success' => true, 'message' => 'Configuración actualizada correctamente.', 'data' => $plan]);
    }

    public function eliminarPlanesConcepto($id)
    {
        $plan = PlanesConcepto::find($id);
        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Configuración no encontrada.'], 404);
        }
        $plan->delete();
        return response()->json(['success' => true, 'message' => 'Configuración eliminada correctamente.']);
    }

    public function indexGlobal()
    {
        $convenios = Convenio::orderBy('nombre')->get();
        $areas = Area::orderBy('nombre')->get();
        $tipos = Tipo::orderBy('nombre')->get();
        $fases = Fase::orderBy('n_fase')->get();
        $gestiones = OfertasAcademica::distinct()->pluck('gestion')->sort()->values();

        return view('admin.ofertas-academicas.listar', compact(
            'convenios',
            'areas',
            'tipos',
            'fases',
            'gestiones'
        ));
    }

    public function listarGlobal(Request $request)
    {
        try {
            $query = OfertasAcademica::with([
                'posgrado.convenio',
                'posgrado.area',
                'posgrado.tipo',
                'sucursal.sede',
                'modalidad',
                'programa',
                'fase',
                'modulos'
            ]);

            // Aplicar filtros (sin cambios)
            if ($request->filled('convenio_id')) {
                $query->whereHas('posgrado', fn($q) => $q->where('convenio_id', $request->convenio_id));
            }
            if ($request->filled('area_id')) {
                $query->whereHas('posgrado', fn($q) => $q->where('area_id', $request->area_id));
            }
            if ($request->filled('tipo_id')) {
                $query->whereHas('posgrado', fn($q) => $q->where('tipo_id', $request->tipo_id));
            }
            if ($request->filled('fase_id')) {
                $query->where('fase_id', $request->fase_id);
            }
            if ($request->filled('gestion')) {
                $query->where('gestion', $request->gestion);
            }
            if ($request->filled('search.value')) {
                $search = $request->input('search.value');
                // Asegurar que sea string y escapar caracteres especiales si es necesario
                $search = trim((string) $search);
                if ($search !== '') {
                    $query->where(function ($q) use ($search) {
                        $q->where('codigo', 'LIKE', "%{$search}%")
                            ->orWhereHas('programa', fn($p) => $p->where('nombre', 'LIKE', "%{$search}%"))
                            ->orWhereHas('posgrado', fn($pos) => $pos->where('nombre', 'LIKE', "%{$search}%"));
                    });
                }
            }

            // Ordenamiento
            $orderColumnIndex = $request->input('order.0.column', 4);
            $orderDir = $request->input('order.0.dir', 'desc');
            if ($orderColumnIndex == 4) {
                $query->orderBy('fecha_inicio_programa', $orderDir);
            } else {
                $query->orderBy('fecha_inicio_programa', 'desc');
            }

            // Paginación
            $total = $query->count();
            $perPage = (int) $request->input('length', 20);
            if ($perPage <= 0) $perPage = 20;
            $page = ($request->input('start', 0) / $perPage) + 1;
            $ofertas = $query->forPage($page, $perPage)->get();

            // Función auxiliar para convertir cualquier valor a string seguro
            $safeString = function ($value) {
                if (is_null($value)) return '';
                if (is_array($value) || (is_object($value) && !method_exists($value, '__toString'))) {
                    return json_encode($value, JSON_UNESCAPED_UNICODE);
                }
                return (string) $value;
            };

            $data = $ofertas->map(function ($oferta) use ($safeString) {
                try {
                    $ofertaId = $oferta->id;

                    // Programa + Sede/Sucursal
                    $programaNombre = $safeString($oferta->programa?->nombre) ?: '-';
                    $codigo = $safeString($oferta->codigo);
                    $gestion = $safeString($oferta->gestion);
                    $sucursalNombre = $oferta->sucursal ? $safeString($oferta->sucursal->nombre) : '-';
                    $sedeNombre = ($oferta->sucursal && $oferta->sucursal->sede) ? $safeString($oferta->sucursal->sede->nombre) : '';

                    $programaSedeHtml = "
                        <div style='max-width:260px;'>
                            <div class='prog-nombre'>" . e($programaNombre) . "</div>
                            <div class='prog-meta'>
                                <span class='prog-codigo'>" . e($codigo) . "</span>
                                <span class='prog-gestion'>" . e($gestion) . "</span>
                            </div>
                            <div class='prog-sede'>
                                <i class='ri-map-pin-line'></i>
                                " . e($sucursalNombre) . ($sedeNombre ? " <span style='color:#9ca3af;'>·</span> " . e($sedeNombre) : '') . "
                            </div>
                        </div>";

                    // Convenio (logo)
                    $convenioHtml = '<div class="convenio-placeholder"><i class="ri-handshake-line"></i></div>';
                    if ($oferta->posgrado && $oferta->posgrado->convenio) {
                        $conv = $oferta->posgrado->convenio;
                        $convNombre = $safeString($conv->nombre);
                        $img = $conv->imagen ? (str_starts_with($conv->imagen, 'http') ? $conv->imagen : '/storage/' . $conv->imagen) : null;
                        if ($img) {
                            $convenioHtml = '<img src="' . e($img) . '" class="convenio-img-small" title="' . e($convNombre) . '">';
                        } else {
                            $convenioHtml = '<div class="convenio-placeholder" title="' . e($convNombre) . '"><i class="ri-handshake-line"></i></div>';
                        }
                    }

                    // Modalidad
                    $modalidadNombre = $oferta->modalidad ? $safeString($oferta->modalidad->nombre) : '-';
                    $modalidadHtml = $oferta->modalidad ? '<span class="badge-modalidad">' . e($modalidadNombre) . '</span>' : '-';

                    // Módulos (cantidad) - robusto
                    $modulosCount = 0;
                    if ($oferta->relationLoaded('modulos')) {
                        $modulos = $oferta->modulos;
                        if ($modulos instanceof \Illuminate\Support\Collection) {
                            $modulosCount = $modulos->count();
                        } elseif (is_array($modulos)) {
                            $modulosCount = count($modulos);
                        }
                    }
                    $modulosHtml = '<span class="badge-modulos">' . $modulosCount . '</span>';

                    // Fechas - mostrar las 3 fechas con badges de color
                    $fechasHtml = '
                        <div class="fechas-cell">
                            <div class="d-flex flex-column gap-1">
                                <div class="fecha-badge" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2); color: #16a34a; padding: 2px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-shopping-bag-line"></i>
                                    ' . ($oferta->fecha_inicio_inscripciones?->format('d/m/Y') ?? '—') . '
                                </div>
                                <div class="fecha-badge" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); color: #d97706; padding: 2px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-calendar-event-line"></i>
                                    ' . ($oferta->fecha_inicio_programa?->format('d/m/Y') ?? '—') . '
                                </div>
                                <div class="fecha-badge" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #dc2626; padding: 2px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-flag-line"></i>
                                    ' . ($oferta->fecha_fin_programa?->format('d/m/Y') ?? '—') . '
                                </div>
                            </div>
                        </div>';

                    // Obtener fase actual
                    $faseActual = $oferta->fase;

                    // Inscritos - mostrar cantidad de Inscritos y Pre-Inscritos
                    $inscritosCount = $oferta->inscripciones()->count();
                    $inscritosReales = $oferta->inscripciones()->where('estado', 'Inscrito')->count();
                    $inscritosPre = $oferta->inscripciones()->where('estado', 'Pre-Inscrito')->count();

                    $inscritosHtml = '
                        <div class="d-flex flex-column gap-1">
                            <span class="ins-pill confirmed" title="Inscritos confirmados">
                                <i class="ri-user-follow-line"></i> ' . $inscritosReales . '
                            </span>
                            <span class="ins-pill pending" title="Pre-inscritos">
                                <i class="ri-user-add-line"></i> ' . $inscritosPre . '
                            </span>
                        </div>';

                    // Botón inscripciones si n_fase = 3
                    $inscripcionesBtn = '';
                    if ($faseActual && $faseActual->n_fase == 3) {
                        $inscripcionesBtn = '<button type="button" class="action-btn ver-inscripciones" 
                            data-oferta-id="' . $ofertaId . '" 
                            data-codigo="' . e($codigo) . '"
                            title="Gestionar Inscripciones">
                            <i class="ri-user-follow-line"></i>
                        </button>';
                    }

                    // Fase - usar color de la fase
                    $faseHtml = '-';
                    if ($oferta->fase) {
                        $faseNombre = $safeString($oferta->fase->nombre);
                        $faseColor = $oferta->fase->color ?? '#9a4904';
                        $faseIcon = 'ri-circle-line';

                        $faseLower = strtolower($faseNombre);
                        if (str_contains($faseLower, 'inscrip')) {
                            $faseIcon = 'ri-shopping-bag-line';
                        } elseif (str_contains($faseLower, 'curso')) {
                            $faseIcon = 'ri-time-line';
                        } elseif (str_contains($faseLower, 'fin') || str_contains($faseLower, 'culmin')) {
                            $faseIcon = 'ri-check-double-line';
                        }

                        $faseHtml = '
                            <span class="badge-fase" style="background: ' . $faseColor . '20; border: 1px solid ' . $faseColor . '40; color: ' . $faseColor . ';">
                                <i class="' . $faseIcon . '"></i>
                                ' . $faseNombre . '
                            </span>';
                    }

                    // Acciones - mostrar botón de planes si hay configuraciones de precio
                    $hasPlanes = $oferta->planesConceptos()->exists();
                    $planesTitle = ($faseActual && $faseActual->n_fase >= 2) 
                        ? 'Ver Planes de Pago' 
                        : 'Esta fase no permite ver planes de pago';
                    $planesBtn = '';
                    if ($hasPlanes) {
                        $planesBtn = '<button type="button" class="action-btn ver-planes" title="' . $planesTitle . '" data-id="' . $ofertaId . '" data-codigo="' . e($codigo) . '">
                            <i class="ri-money-dollar-circle-line"></i>
                        </button>';
                    }

                    // Botones de cambio de fase
                    $faseActual = $oferta->fase;
                    $todasFases = Fase::orderBy('n_fase')->get();
                    $faseActualIdx = $todasFases->search(fn($f) => $f->id == $faseActual?->id);
                    $faseAnterior = null;
                    $faseSiguiente = null;

                    if ($faseActualIdx !== false) {
                        if ($faseActualIdx > 0) {
                            $faseAnterior = $todasFases[$faseActualIdx - 1];
                        }
                        if ($faseActualIdx < $todasFases->count() - 1) {
                            $faseSiguiente = $todasFases[$faseActualIdx + 1];
                        }
                    }

                    // Validar requisitos para cambiar a siguiente fase
                    $tienePlanes = $oferta->planesConceptos()->exists();
                    $tieneModulos = $oferta->modulos()->exists();
                    $n_fase_actual = $faseActual?->n_fase ?? 0;
                    $puedeCambiarSiguiente = $n_fase_actual >= 2 ? ($tienePlanes && $tieneModulos) : true;
                    $motivoBloqueo = '';
                    if ($n_fase_actual >= 2) {
                        if (!$tienePlanes && !$tieneModulos) {
                            $motivoBloqueo = 'Falta configurar planes de pago y módulos';
                        } elseif (!$tienePlanes) {
                            $motivoBloqueo = 'Falta configurar planes de pago';
                        } elseif (!$tieneModulos) {
                            $motivoBloqueo = 'Falta registrar módulos';
                        }
                    }

                    $cambiarFaseBtns = '';
                    if ($faseAnterior) {
                        $cambiarFaseBtns .= '<button type="button" class="action-btn cambiar-fase" 
                            data-oferta-id="' . $ofertaId . '" 
                            data-fase-nueva-id="' . $faseAnterior->id . '" 
                            data-fase-nueva-nombre="' . e($faseAnterior->nombre) . '" 
                            data-direccion="anterior"
                            data-puede-cambiar="true"
                            title="Cambiar a fase anterior: ' . e($faseAnterior->nombre) . '">
                            <i class="ri-arrow-left-s-line"></i>
                        </button>';
                    }
                    if ($faseSiguiente) {
                        $cambiarFaseBtns .= '<button type="button" class="action-btn cambiar-fase ' . ($puedeCambiarSiguiente ? '' : 'cambiar-fase-bloqueado') . '" 
                            data-oferta-id="' . $ofertaId . '" 
                            data-fase-nueva-id="' . $faseSiguiente->id . '" 
                            data-fase-nueva-nombre="' . e($faseSiguiente->nombre) . '" 
                            data-direccion="siguiente"
                            data-puede-cambiar="' . ($puedeCambiarSiguiente ? 'true' : 'false') . '"
                            data-motivo-bloqueo="' . e($motivoBloqueo) . '"
                            title="' . ($puedeCambiarSiguiente ? 'Cambiar a fase siguiente: ' . e($faseSiguiente->nombre) : $motivoBloqueo) . '">
                            <i class="ri-arrow-right-s-line"></i>
                        </button>';
                    }

                    $accionesHtml = '<div class="actions-cell">
                                <div class="actions-row">
                                    <a href="/admin/posgrads/ofertas/' . $ofertaId . '/detalle" class="action-btn ver-detalle" title="Ver detalle">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    ' . $planesBtn . '
                                    ' . $inscripcionesBtn . '
                                </div>
                                ' . ($cambiarFaseBtns ? '<div class="actions-row mt-1">' . $cambiarFaseBtns . '</div>' : '') . '
                            </div>';

                    return [
                        'programa_sede' => $programaSedeHtml,
                        'convenio_imagen' => $convenioHtml,
                        'modalidad_nombre' => $modalidadHtml,
                        'modulos_count' => $modulosHtml,
                        'fechas' => $fechasHtml,
                        'inscritos' => $inscritosHtml,
                        'fase_nombre' => $faseHtml,
                        'acciones' => $accionesHtml,
                    ];
                } catch (\Throwable $e) {
                    \Log::error("Error formateando oferta ID {$oferta->id}: " . $e->getMessage(), [
                        'trace' => $e->getTraceAsString()
                    ]);
                    return [
                        'programa_sede' => 'Error: ' . $e->getMessage(),
                        'convenio_imagen' => '-',
                        'modalidad_nombre' => '-',
                        'modulos_count' => '-',
                        'fechas' => '-',
                        'inscritos' => '-',
                        'fase_nombre' => '-',
                        'acciones' => '-',
                    ];
                }
            });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data
            ]);
        } catch (\Throwable $e) {
            \Log::error('Error en listarGlobal: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listarConfiguracionesPrecio($ofertaId)
    {
        try {
            $configs = PlanesConcepto::with(['plan_pago', 'concepto'])
                ->where('ofertas_academica_id', $ofertaId)
                ->get();

            return response()->json([
                'data' => $configs->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'plan_pago' => $c->plan_pago ? [
                            'id' => $c->plan_pago->id,
                            'nombre' => $c->plan_pago->nombre,
                            'es_promocion' => $c->plan_pago->es_promocion ?? false,
                            'fecha_inicio_promocion' => $c->plan_pago->fecha_inicio_promocion,
                            'fecha_fin_promocion' => $c->plan_pago->fecha_fin_promocion,
                        ] : null,
                        'concepto' => $c->concepto ? [
                            'id' => $c->concepto->id,
                            'nombre' => $c->concepto->nombre,
                        ] : null,
                        'cuotas' => $c->n_cuotas,
                        'precio_regular_bs' => $c->precio_regular,
                        'descuento_bs' => $c->descuento_bs,
                        'pago_bs' => $c->pago_bs,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en listarConfiguracionesPrecio: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDetallePlan($ofertaId, $planPagoId)
    {
        try {
            $configs = PlanesConcepto::with(['plan_pago', 'concepto'])
                ->where('ofertas_academica_id', $ofertaId)
                ->where('planes_pago_id', $planPagoId)
                ->get();

            $oferta = OfertasAcademica::findOrFail($ofertaId);
            $fechaInicioProg = $oferta->fecha_inicio_programa;

            $resultado = [];
            foreach ($configs as $c) {
                $conceptoNombre = $c->concepto?->nombre ?? 'Sin concepto';
                $totalPago = floatval($c->pago_bs);
                $numCuotas = intval($c->n_cuotas);

                $cuotas = [];
                $montoCuota = $numCuotas > 0 ? floor($totalPago / $numCuotas) : 0;
                $resto = $totalPago - ($montoCuota * $numCuotas);

                for ($i = 1; $i <= $numCuotas; $i++) {
                    $monto = ($i === $numCuotas) ? ($montoCuota + $resto) : $montoCuota;
                    $mesesAgregar = $i - 1;
                    $fechaVenc = $fechaInicioProg ? \Carbon\Carbon::parse($fechaInicioProg)->addMonths($mesesAgregar)->format('Y-m-d') : null;

                    $cuotas[] = [
                        'n_cuota' => $i,
                        'monto_bs' => $monto,
                        'fecha_vencimiento' => $fechaVenc
                    ];
                }

                $resultado[] = [
                    'concepto' => $conceptoNombre,
                    'n_cuotas' => $numCuotas,
                    'pago_bs' => $totalPago,
                    'cuotas' => $cuotas
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $resultado
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cambiarFase(Request $request, $ofertaId)
    {
        try {
            $request->validate([
                'fase_id' => 'required|exists:fases,id'
            ]);

            $oferta = OfertasAcademica::findOrFail($ofertaId);
            $oferta->fase_id = $request->fase_id;
            $oferta->save();

            return response()->json([
                'success' => true,
                'message' => 'Fase actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listarInscripciones($ofertaId)
    {
        try {
            $oferta = OfertasAcademica::with('programa')->find($ofertaId);
            $nombrePrograma = $oferta?->programa?->nombre ?? 'Programa';

            $inscripciones = Inscripcione::with(['estudiante.persona.usuario', 'planesPago', 'trabajador_cargo.trabajador.persona'])
                ->where('ofertas_academica_id', $ofertaId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'data' => $inscripciones->map(function ($i) use ($nombrePrograma, $ofertaId) {
                    $estudiante = $i->estudiante?->persona;
                    $nombreEstudiante = $estudiante
                        ? trim(($estudiante->nombres ?? '') . ' ' . ($estudiante->apellido_paterno ?? '') . ' ' . ($estudiante->apellido_materno ?? ''))
                        : 'Sin nombre';

                    $moodleUsername = null;
                    $moodlePassword = null;
                    $tieneCuentaMoodle = !empty($i->moodle_user_id);
                    $tieneCuentaSistema = $estudiante?->usuario !== null;

                    if ($estudiante) {
                        $moodleUsername = $estudiante->usuario?->username ?? $this->buildMoodleUsernameLocal(
                            $estudiante->nombres ?? '',
                            $estudiante->apellido_paterno ?? '',
                            $estudiante->apellido_materno ?? ''
                        );
                        $moodlePassword = $estudiante->usuario?->moodle_password ?? ($estudiante->carnet ?? '');
                    }

                    return [
                        'id' => $i->id,
                        'estudiante_id' => $i->estudiante_id,
                        'estudiante_nombre' => $nombreEstudiante ?: '—',
                        'apellido_paterno' => $estudiante?->apellido_paterno ?? '',
                        'apellido_materno' => $estudiante?->apellido_materno ?? '',
                        'nombres' => $estudiante?->nombres ?? '',
                        'estudiante_ci' => $estudiante?->carnet ?? '—',
                        'celular' => $estudiante?->celular ?? '—',
                        'correo' => $estudiante?->correo ?? '—',
                        'estado' => $i->estado,
                        'plan_pago' => $i->planesPago?->nombre ?? '—',
                        'plan_pago_id' => $i->planes_pago_id,
                        'adelanto_bs' => $i->adelanto_bs,
                        'fecha_registro' => $i->fecha_registro?->format('d/m/Y H:i'),
                        'observacion' => $i->observacion,
                        'trabajador' => $i->trabajador_cargo?->trabajador?->persona
                            ? trim(($i->trabajador_cargo->trabajador->persona->nombres ?? '') . ' ' . ($i->trabajador_cargo->trabajador->persona->apellido_paterno ?? ''))
                            : '—',
                        'programa_nombre' => $nombrePrograma,
                        'moodle_username' => $moodleUsername,
                        'moodle_password' => $moodlePassword,
                        'tiene_cuenta_moodle' => $tieneCuentaMoodle,
                        'tiene_cuenta_sistema' => $tieneCuentaSistema,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Genera el username propuesto (opción 1: inicial + apellidos) sin llamar a Moodle.
     * Solo para visualización en el tab de inscripciones.
     */
    private function buildMoodleUsernameLocal(string $nombres, string $apellidoPaterno, string $apellidoMaterno): string
    {
        $reemplazos = ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n'];
        $palabras     = preg_split('/\s+/', trim($nombres));
        $primerNombre = $palabras[0] ?? '';

        $apPaternoNorm = strtr(preg_replace('/[^a-záéíóúüñ]/u', '', mb_strtolower($apellidoPaterno)), $reemplazos);
        $apMaternoNorm = strtr(preg_replace('/[^a-záéíóúüñ]/u', '', mb_strtolower($apellidoMaterno)), $reemplazos);
        $inicialNorm   = mb_strtolower(mb_substr($primerNombre, 0, 1));

        return $inicialNorm . $apPaternoNorm . $apMaternoNorm;
    }

    private function buildMoodleUsername(string $nombres, string $apellidoPaterno, string $apellidoMaterno): string
    {
        $palabras = preg_split('/\s+/', trim($nombres));
        $primerNombre = $palabras[0] ?? '';

        $apPaternoNorm = mb_strtolower(preg_replace('/[^a-záéíóúüñ]/u', '', $apellidoPaterno));
        $apMaternoNorm = mb_strtolower(preg_replace('/[^a-záéíóúüñ]/u', '', $apellidoMaterno));
        
        // Normalizar
        $reemplazos = ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n'];
        $apPaternoNorm = strtr($apPaternoNorm, $reemplazos);
        $apMaternoNorm = strtr($apMaternoNorm, $reemplazos);
        
        // Intento 1: inicial + apellidos
        $inicial = mb_substr($primerNombre, 0, 1);
        $username = $inicial . $apPaternoNorm . $apMaternoNorm;
        
        if (!$this->usernameExistsInMoodle($username)) {
            return $username;
        }
        
        // Intento 2: 2 letras + apellidos
        $dosLetras = mb_substr($primerNombre, 0, 2);
        $username = $dosLetras . $apPaternoNorm . $apMaternoNorm;
        
        if (!$this->usernameExistsInMoodle($username)) {
            return $username;
        }
        
        // Intento 3: nombre completo + apellidos
        $nombreCompleto = mb_strtolower(preg_replace('/[^a-záéíóúüñ]/u', '', $primerNombre));
        $nombreCompleto = strtr($nombreCompleto, $reemplazos);
        $username = $nombreCompleto . $apPaternoNorm . $apMaternoNorm;
        
        if (!$this->usernameExistsInMoodle($username)) {
            return $username;
        }
        
        // Intento 4+: con número
        for ($i = 1; $i <= 99; $i++) {
            $username = $dosLetras . $apPaternoNorm . $apMaternoNorm . $i;
            if (!$this->usernameExistsInMoodle($username)) {
                return $username;
            }
        }
        
        return 'user' . uniqid();
    }

    /**
     * Verifica si un username existe en Moodle
     */
    private function usernameExistsInMoodle(string $username): bool
    {
        try {
            $moodle = new \App\Services\MoodleService();
            $user = $moodle->getUserByField('username', $username);
            return $user !== null;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function registrarInscripcion(Request $request, $ofertaId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'estudiante_id' => 'required|exists:estudiantes,id',
                'planes_pago_id' => 'nullable|exists:planes_pagos,id', // ahora nullable siempre
                'estado' => 'required|in:Inscrito,Pre-Inscrito',
                'adelanto_bs' => 'nullable|numeric|min:0',
                'observacion' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                \Log::error('Error de validación en registrarInscripcion', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);
                return response()->json([
                    'success' => false, 
                    'errors' => $validator->errors(),
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            // Verificar si ya está inscrito
            $yaInscrito = Inscripcione::where('ofertas_academica_id', $ofertaId)
                ->where('estudiante_id', $request->estudiante_id)
                ->exists();

            if ($yaInscrito) {
                return response()->json([
                    'success' => false,
                    'message' => 'El estudiante ya está inscrito en esta oferta académica.'
                ], 422);
            }

            // Obtener trabajadores_cargo_id del usuario actual
            $trabajadoresCargoId = null;
            $user = auth()->user();
            if ($user && $user->persona_id) {
                $trabajador = Trabajadore::where('persona_id', $user->persona_id)->first();
                if ($trabajador) {
                    $cargo = TrabajadoresCargo::where('trabajadore_id', $trabajador->id)->first();
                    if ($cargo) {
                        $trabajadoresCargoId = $cargo->id;
                    }
                }
            }

            // Guardar inscripción (planes_pago_id se guarda siempre que venga, incluso en Pre-Inscrito)
            $inscripcion = Inscripcione::create([
                'ofertas_academica_id' => $ofertaId,
                'estudiante_id' => $request->estudiante_id,
                'planes_pago_id' => $request->planes_pago_id, // sin condicional
                'estado' => $request->estado,
                'adelanto_bs' => $request->adelanto_bs ?? 0,
                'observacion' => $request->observacion,
                'fecha_registro' => now(),
                'trabajadores_cargo_id' => $trabajadoresCargoId,
            ]);

            // Si el estado es "Inscrito", crear cuotas y matriculaciones
            if ($request->estado === 'Inscrito') {
                \Log::info('Iniciando creacion de cuotas', [
                    'oferta_id' => $ofertaId,
                    'planes_pago_id' => $request->planes_pago_id,
                    'estudiante_id' => $request->estudiante_id
                ]);

                // 1. Obtener configuraciones del plan de pago para esta oferta
                $planesConceptos = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
                    ->where('planes_pago_id', $request->planes_pago_id)
                    ->with('concepto')
                    ->get();

                \Log::info('Conceptos encontrados', [
                    'cantidad' => $planesConceptos->count(),
                    'conceptos' => $planesConceptos->toArray()
                ]);

                if ($planesConceptos->isEmpty()) {
                    return response()->json([
                        'error' => 'El plan de pago seleccionado no tiene conceptos configurados. Por favor, configure los conceptos del plan en la pestaña "Contable" antes de registrar una inscripción como "Inscrito".'
                    ], 422);
                }

                // Indexar cuotas personalizadas enviadas desde el frontend
                // Estructura: { "conceptoIdx_cuotaIdx" => ['monto' => x, 'fecha' => 'Y-m-d'] }
                $cuotasPersonalizadas = [];
                if ($request->filled('cuotas_personalizadas')) {
                    $decoded = json_decode($request->cuotas_personalizadas, true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $c) {
                            $key = intval($c['concepto_idx']) . '_' . intval($c['cuota_idx']);
                            $cuotasPersonalizadas[$key] = $c;
                        }
                    }
                }

                $nCuota = 1;
                foreach ($planesConceptos as $conceptoIdx => $pc) {
                    $conceptoNombre = $pc->concepto && $pc->concepto->nombre ? $pc->concepto->nombre : 'Concepto';
                    $nCuotas = $pc->n_cuotas ?? 1;
                    $montoPorCuotaDefault = $nCuotas > 0 ? floatval($pc->pago_bs) / $nCuotas : floatval($pc->pago_bs);
                    $montoPorCuotaDefault = round($montoPorCuotaDefault, 2);

                    \Log::info('Creando cuotas para concepto', [
                        'concepto' => $conceptoNombre,
                        'n_cuotas' => $nCuotas,
                        'monto_por_cuota' => $montoPorCuotaDefault
                    ]);

                    for ($i = 0; $i < $nCuotas; $i++) {
                        $key = $conceptoIdx . '_' . $i;
                        $custom = $cuotasPersonalizadas[$key] ?? null;

                        // Usar monto personalizado si existe y es válido, si no el calculado del plan
                        $montoCuota = ($custom && isset($custom['monto']) && floatval($custom['monto']) > 0)
                            ? round(floatval($custom['monto']), 2)
                            : $montoPorCuotaDefault;

                        // Usar fecha personalizada si existe, si no calcular desde hoy
                        $fechaVencimiento = ($custom && !empty($custom['fecha']))
                            ? \Carbon\Carbon::parse($custom['fecha'])
                            : now()->addMonths($i + 1);

                        $nombreCuota = "{$conceptoNombre} - Cuota " . ($i + 1);

                        Cuota::create([
                            'inscripcione_id' => $inscripcion->id,
                            'nombre' => $nombreCuota,
                            'n_cuota' => $nCuota,
                            'monto_bs' => $montoCuota,
                            'pago_pendiente_bs' => $montoCuota,
                            'descuento_bs' => 0,
                            'fecha_vencimiento' => $fechaVencimiento,
                            'estado' => 'pendiente',
                        ]);
                        $nCuota++;
                    }
                }

                // 2. Crear matriculaciones para todos los módulos de la oferta
                $modulos = Modulo::where('ofertas_academica_id', $ofertaId)->get();
                \Log::info('Creando matriculaciones', ['modulos_count' => $modulos->count()]);
                
                foreach ($modulos as $modulo) {
                    Matriculacione::create([
                        'inscripcione_id' => $inscripcion->id,
                        'modulo_id' => $modulo->id,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => $request->estado === 'Inscrito'
                    ? 'Inscripción registrada correctamente. Se crearon las cuotas y matriculaciones.'
                    : 'Pre-Inscripción registrada correctamente.',
                'data' => $inscripcion
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Error de base de datos en registrarInscripcion: ' . $e->getMessage());
            return response()->json(['error' => 'Error de base de datos: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            \Log::error('Error en registrarInscripcion: ' . $e->getMessage());
            return response()->json(['error' => 'Error al registrar la inscripción: ' . $e->getMessage()], 500);
        }
    }

    public function getCuotasInscripcion(int $inscripcionId)
    {
        $inscripcion = Inscripcione::where('id', $inscripcionId)
            ->with(['cuotas', 'planesPago'])
            ->first();

        if (!$inscripcion) {
            return response()->json(['success' => false, 'error' => 'Inscripción no encontrada'], 404);
        }

        $cuotasPendientes = $inscripcion->cuotas->filter(fn ($c) => (float) $c->pago_pendiente_bs > 0);

        $grupo = [
            'plan_nombre' => $inscripcion->planesPago?->nombre ?? 'Plan de Pago',
            'cuotas'      => $cuotasPendientes->map(fn ($c) => [
                'id'                => $c->id,
                'nombre'            => $c->nombre,
                'n_cuota'           => $c->n_cuota,
                'monto_bs'          => number_format((float) $c->monto_bs, 2),
                'pago_pendiente_bs' => number_format((float) $c->pago_pendiente_bs, 2),
                'fecha_vencimiento' => $c->fecha_vencimiento?->format('d/m/Y'),
                'estado'            => $c->estado,
            ])->values(),
        ];

        return response()->json(['success' => true, 'grupo' => $grupo]);
    }

    public function subirComprobanteInscripcion(Request $request)
    {
        $request->validate([
            'inscripcion_id' => 'required|integer|exists:inscripciones,id',
            'archivo'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'observaciones'  => 'nullable|string|max:500',
            'cuotas'         => 'required|array|min:1',
            'cuotas.*'       => 'integer|exists:cuotas,id',
        ]);

        $inscripcion = Inscripcione::where('id', $request->inscripcion_id)
            ->where('estado', 'Inscrito')
            ->first();

        if (!$inscripcion) {
            return response()->json(['success' => false, 'message' => 'Inscripción no encontrada.'], 404);
        }

        $cuotasIds = Cuota::where('inscripcione_id', $inscripcion->id)
            ->whereIn('id', $request->cuotas)
            ->pluck('id');

        if ($cuotasIds->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Las cuotas seleccionadas no pertenecen a esta inscripción.'], 422);
        }

        $dir = public_path('storage/comprobantes');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext           = $request->file('archivo')->getClientOriginalExtension();
        $nombreArchivo = 'comprobante_' . $inscripcion->id . '_' . time() . '.' . $ext;
        $request->file('archivo')->move($dir, $nombreArchivo);

        $comprobante = PagoRespaldo::create([
            'inscripcione_id' => $inscripcion->id,
            'archivo'         => $nombreArchivo,
            'observaciones'   => $request->observaciones,
            'estado'          => 'pendiente',
        ]);

        $comprobante->cuotas()->attach($cuotasIds);

        return response()->json(['success' => true, 'mensaje' => 'Comprobante subido correctamente. Pendiente de verificación.']);
    }

    public function actualizarCuotas(Request $request, $ofertaId)
    {
        try {
            $datosCuotas = json_decode($request->cuotas, true);

            // Agrupar por concepto_idx para calcular el total
            $porConcepto = [];
            foreach ($datosCuotas as $cuota) {
                $idx = $cuota['concepto_idx'];
                if (!isset($porConcepto[$idx])) {
                    $porConcepto[$idx] = ['total' => 0, 'configs' => []];
                }
                $porConcepto[$idx]['total'] += floatval($cuota['monto']);
            }

            // Obtener configs de la oferta para actualizar
            $configs = PlanesConcepto::where('ofertas_academica_id', $ofertaId)
                ->with('concepto')
                ->get();

            // Actualizar cada config con el nuevo monto total (suma de cuotas)
            $configIdx = 0;
            foreach ($configs as $config) {
                if (isset($porConcepto[$configIdx])) {
                    $config->pago_bs = $porConcepto[$configIdx]['total'];
                    $config->save();
                }
                $configIdx++;
            }

            return response()->json([
                'success' => true,
                'message' => 'Cuotas actualizadas correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function crearCuentasMoodle(Request $request, $ofertaId)
    {
        try {
            $oferta = OfertasAcademica::with('programa')->findOrFail($ofertaId);
            $estudiantes = json_decode($request->estudiantes, true);
            
            if (!$estudiantes || count($estudiantes) === 0) {
                return response()->json(['success' => false, 'message' => 'No hay estudiantes seleccionados'], 400);
            }

            $moodleCategoryId = $oferta->programa?->moodle_category_id;
            
            $creados = 0;
            $errores = [];
            
            foreach ($estudiantes as $est) {
                try {
                    $inscripcion = Inscripcione::find($est['id']);
                    if (!$inscripcion || !$inscripcion->estudiante) {
                        $errores[] = "Inscripción no encontrada: {$est['nombre']}";
                        continue;
                    }
                    
                    $estudiante = $inscripcion->estudiante;
                    $persona = $estudiante->persona;
                    
                    if (!$persona) {
                        $errores[] = "Persona no encontrada para: {$est['nombre']}";
                        continue;
                    }

                    $username = $est['username'];
                    $password = $est['password'];
                    $email     = $persona->correo ?: "{$username}@innova.edu.bo";
                    $firstname = trim($persona->nombres ?? '') ?: 'Estudiante';
                    $lastname  = trim(($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) ?: 'Sin Apellido';

                    $moodleUserId = null;

                    $moodleService = app(\App\Services\MoodleService::class);

                    $existingUser = $moodleService->getUserByField('username', $username);
                    if ($existingUser && isset($existingUser['id'])) {
                        $moodleUserId = (int) $existingUser['id'];
                    } else {
                        $moodleUserId = $moodleService->createUser($username, $password, $firstname, $lastname, $email);
                    }

                    if ($moodleUserId) {
                        $inscripcion->moodle_user_id = $moodleUserId;
                        $inscripcion->en_moodle = true;
                        $inscripcion->matriculado_moodle_at = now();
                        $inscripcion->save();
                        $creados++;
                    } else {
                        $errores[] = "No se pudo crear usuario en Moodle: {$est['nombre']}";
                    }

                    // Crear o actualizar cuenta del sistema Laravel
                    $userRecord = User::where('persona_id', $persona->id)->first()
                        ?? ($persona->correo ? User::where('email', $persona->correo)->first() : null);

                    if (!$userRecord && $persona->correo) {
                        $nombre = trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''));
                        User::create([
                            'name'            => $nombre,
                            'username'        => $username,
                            'email'           => $persona->correo,
                            'password'        => $password,
                            'moodle_password' => $password,
                            'role'            => 'moodle',
                            'acceso_admin'    => false,
                            'acceso_virtual'  => true,
                            'estado'          => 'Activo',
                            'persona_id'      => $persona->id,
                        ]);
                    } elseif ($userRecord) {
                        $userRecord->update(['moodle_password' => $password]);
                    }
                    
                } catch (\Exception $e) {
                    $errores[] = "Error con {$est['nombre']}: " . $e->getMessage();
                }
            }

            $message = "{$creados} cuenta(s) creada(s) correctamente.";
            if (count($errores) > 0) {
                $message .= " Errores: " . implode('; ', $errores);
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function verificarPrograma(Request $request)
    {
        $posgradoId = $request->posgrado_id;
        $programaNombre = $request->programa_nombre;
        $version = $request->version;
        $grupo = $request->grupo;
        $excludeId = $request->exclude_id;

        $query = OfertasAcademica::where('posgrado_id', $posgradoId)
            ->where('version', $version)
            ->where('grupo', $grupo);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existe = $query->exists();

        return response()->json(['existe' => $existe]);
    }
}
