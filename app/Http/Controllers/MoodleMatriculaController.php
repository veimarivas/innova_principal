<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Inscripcione;
use App\Models\MoodleMatricula;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MoodleMatriculaController extends Controller
{
    public function __construct(private MoodleService $moodle) {}

    /**
     * Devuelve la lista de estudiantes inscritos en la oferta del módulo,
     * indicando si cada uno ya existe como usuario en Moodle.
     */
    public function estadoEstudiantes(int $moduloId)
    {
        try {
        $modulo = Modulo::with('oferta_academica.programa')->find($moduloId);
        if (!$modulo) {
            return response()->json(['success' => false, 'message' => 'Módulo no encontrado.'], 404);
        }

        $nombrePrograma = $modulo->oferta_academica?->programa?->nombre ?? 'Programa';

        $cursoNombre = null;
        if (!$modulo->moodle_course_id) {
            $shortname = $this->moodle->buildCourseShortname(
                $modulo->ofertas_academica_id,
                $modulo->n_modulo
            );
            $curso = $this->moodle->getCourseByShortname($shortname);

            if ($curso && isset($curso['id'])) {
                $modulo->moodle_course_id = $curso['id'];
                $modulo->save();
                $cursoNombre = $curso['fullname'] ?? $curso['shortname'] ?? null;
            } else {
                return response()->json([
                    'success'          => false,
                    'sin_curso_moodle' => true,
                    'message'          => 'Este módulo no tiene un curso en Moodle. Guarda los módulos para crearlos automáticamente.',
                ]);
            }
        } else {
            $shortname = $this->moodle->buildCourseShortname(
                $modulo->ofertas_academica_id,
                $modulo->n_modulo
            );
            $curso = $this->moodle->getCourseByShortname($shortname);
            if (!$curso) {
                // El curso ya no existe en Moodle — limpiar el ID obsoleto y pedir recreación
                $modulo->moodle_course_id = null;
                $modulo->save();
                return response()->json([
                    'success'          => false,
                    'sin_curso_moodle' => true,
                    'message'          => 'El curso ya no existe en Moodle. Puedes crearlo nuevamente.',
                ]);
            }
            $cursoNombre = $curso['fullname'] ?? $curso['shortname'] ?? null;
        }

        $inscripciones = Inscripcione::with(['estudiante.persona.usuario'])
            ->where('ofertas_academica_id', $modulo->ofertas_academica_id)
            ->where('estado', 'Inscrito')
            ->get();

        $courseId = $modulo->moodle_course_id;

        $matriculasExistentes = MoodleMatricula::where('modulo_id', $moduloId)
            ->pluck('moodle_user_id', 'inscripcion_id')
            ->toArray();

        $lista = [];
        $reservedUsernames = [];

        foreach ($inscripciones as $inscripcion) {
            $persona = $inscripcion->estudiante?->persona;
            if (!$persona) continue;

            $savedUserId = $matriculasExistentes[$inscripcion->id] ?? null;
            $inscripcionMoodleUserId = $inscripcion->moodle_user_id ?? null;

            $moodleUser = $this->moodle->findExistingMoodleUser(
                $persona->nombres ?? '',
                $persona->apellido_paterno ?? '',
                $persona->apellido_materno ?? ''
            );

            if (!$moodleUser && $savedUserId) {
                $moodleUser = $this->moodle->getUserByField('id', (string) $savedUserId);
            }

            if (!$moodleUser && $inscripcionMoodleUserId) {
                $moodleUser = $this->moodle->getUserByField('id', (string) $inscripcionMoodleUserId);
            }

            if ($moodleUser) {
                $username     = $moodleUser['username'];
                $moodleUserId = (int) $moodleUser['id'];
                $tieneCuenta  = true;
                $reservedUsernames[] = $username;
            } else {
                $username = $this->moodle->buildProposedUsername(
                    $persona->nombres ?? '',
                    $persona->apellido_paterno ?? '',
                    $persona->apellido_materno ?? '',
                    $reservedUsernames
                );
                $moodleUserId = $savedUserId ?: $inscripcionMoodleUserId;
                $tieneCuenta  = false;
                $reservedUsernames[] = $username;
            }

            $nombreCompleto = trim(
                ($persona->nombres ?? '') . ' ' .
                ($persona->apellido_paterno ?? '') . ' ' .
                ($persona->apellido_materno ?? '')
            );

            $effectiveUserId = $moodleUserId ?: ($savedUserId ? (int) $savedUserId : null);
            $enCurso = false;
            if ($courseId && $effectiveUserId) {
                $enCurso = $this->moodle->isUserEnrolledInCourse((int) $effectiveUserId, (int) $courseId);
            }

            $lista[] = [
                'inscripcion_id'       => $inscripcion->id,
                'estudiante_id'        => $inscripcion->estudiante_id,
                'nombre'               => $nombreCompleto,
                'carnet'               => $persona->carnet ?? '',
                'correo'               => $persona->correo ?? '',
                'celular'              => $persona->celular ?? '',
                'username'             => $username,
                'password'             => $persona->usuario?->moodle_password ?? $this->generarPasswordSegura($persona->carnet),
                'en_moodle'            => $tieneCuenta || (bool) $savedUserId || !empty($inscripcionMoodleUserId),
                'tiene_cuenta'         => $tieneCuenta || $savedUserId || $inscripcionMoodleUserId,
                'en_curso'             => $enCurso,
                'ya_matriculado'       => $savedUserId !== null,
                'moodle_user_id'       => $moodleUserId,
            ];
        }

        return response()->json([
            'success'         => true,
            'modulo_nombre'   => $modulo->nombre,
            'moodle_course_id'=> $modulo->moodle_course_id,
            'moodle_course_nombre' => $cursoNombre,
            'programa_nombre' => $nombrePrograma,
            'estudiantes'     => $lista,
        ]);
        } catch (\Exception $e) {
            Log::error('estadoEstudiantes error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al conectar con Moodle: ' . $e->getMessage()], 503);
        }
    }

    /**
     * Crea el curso en Moodle para un módulo que todavía no tiene moodle_course_id.
     */
    public function crearCurso(int $moduloId)
    {
        $modulo = Modulo::with(['oferta_academica.programa'])->find($moduloId);
        if (!$modulo) {
            return response()->json(['success' => false, 'message' => 'Módulo no encontrado.'], 404);
        }

        if ($modulo->moodle_course_id) {
            return response()->json(['success' => true, 'message' => 'El módulo ya tiene un curso en Moodle.', 'moodle_course_id' => $modulo->moodle_course_id]);
        }

        $oferta   = $modulo->oferta_academica;
        $programa = $oferta?->programa;

        if (!$programa) {
            return response()->json(['success' => false, 'message' => 'No se encontró el programa de esta oferta.'], 422);
        }

        if (!$programa->moodle_category_id) {
            $parentId   = (int) config('moodle.category_parent', 0);
            $categoryId = $this->moodle->createCategory($programa->nombre, $parentId);

            if (!$categoryId) {
                return response()->json(['success' => false, 'message' => 'No se pudo crear la categoría del programa en Moodle. Verifica la conexión.'], 500);
            }

            $programa->moodle_category_id = $categoryId;
            $programa->save();
        }

        $shortname = $this->moodle->buildCourseShortname($modulo->ofertas_academica_id, $modulo->n_modulo);

        $existing = $this->moodle->getCourseByShortname($shortname);
        if ($existing && isset($existing['id'])) {
            $modulo->moodle_course_id = $existing['id'];
            $modulo->save();
            return response()->json(['success' => true, 'message' => 'Curso encontrado y sincronizado desde Moodle.', 'moodle_course_id' => $modulo->moodle_course_id]);
        }

        $startDate = $modulo->fecha_inicio ? $modulo->fecha_inicio->format('Y-m-d') : null;
        $endDate   = $modulo->fecha_fin   ? $modulo->fecha_fin->format('Y-m-d')   : null;

        $courseId = $this->moodle->createCourse(
            $modulo->nombre,
            $shortname,
            $programa->moodle_category_id,
            $startDate,
            $endDate
        );

        if (!$courseId) {
            return response()->json(['success' => false, 'message' => 'No se pudo crear el curso en Moodle. Verifica la conexión y el token.'], 500);
        }

        $modulo->moodle_course_id = $courseId;
        $modulo->save();

        return response()->json(['success' => true, 'message' => 'Curso creado en Moodle correctamente.', 'moodle_course_id' => $courseId]);
    }

    /**
     * Registra en Moodle a los estudiantes que no existen y los matricula
     * en el curso del módulo.
     */
    public function matricular(Request $request, int $moduloId)
    {
        $modulo = Modulo::find($moduloId);
        if (!$modulo || !$modulo->moodle_course_id) {
            return response()->json(['success' => false, 'message' => 'Módulo sin curso en Moodle.'], 422);
        }

        $request->validate([
            'estudiantes'                => 'required|array|min:1',
            'estudiantes.*.estudiante_id'=> 'required|integer',
            'estudiantes.*.moodle_user_id' => 'nullable|integer',
        ]);

        $courseId = $modulo->moodle_course_id;
        $resultados = [];

        foreach ($request->estudiantes as $item) {
            $inscripcion = Inscripcione::with(['estudiante.persona.usuario'])
                ->where('ofertas_academica_id', $modulo->ofertas_academica_id)
                ->where('estudiante_id', $item['estudiante_id'])
                ->first();

            if (!$inscripcion) {
                $resultados[] = [
                    'estudiante_id' => $item['estudiante_id'],
                    'ok'     => false,
                    'mensaje'=> 'Inscripción no encontrada.',
                ];
                continue;
            }

            $persona = $inscripcion->estudiante?->persona;
            if (!$persona) {
                $resultados[] = [
                    'estudiante_id' => $item['estudiante_id'],
                    'ok'     => false,
                    'mensaje'=> 'Persona no encontrada.',
                ];
                continue;
            }

            $nombreCompleto = trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''));
            $moodleUserId   = $item['moodle_user_id'] ? (int) $item['moodle_user_id'] : null;

            if (!$moodleUserId) {
                $username  = $this->moodle->getOrBuildUsername(
                    $persona->nombres ?? '',
                    $persona->apellido_paterno ?? '',
                    $persona->apellido_materno ?? '',
                    $persona->carnet ?? ''
                );
                $password  = $this->generarPasswordSegura($persona->carnet);
                $firstname = trim($persona->nombres ?? '') ?: 'Sin Nombre';
                $lastname  = trim(($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) ?: 'Sin Apellido';
                $email     = $persona->correo ?: "{$username}@innova.edu.bo";

                $moodleUserId = $this->moodle->createUser($username, $password, $firstname, $lastname, $email);

                if (!$moodleUserId) {
                    $resultados[] = [
                        'estudiante_id' => $item['estudiante_id'],
                        'nombre'  => $nombreCompleto,
                        'ok'      => false,
                        'mensaje' => 'No se pudo crear el usuario en Moodle.',
                    ];
                    continue;
                }

                if ($persona->usuario) {
                    $persona->usuario->update(['moodle_password' => $password]);
                }
            }

            $this->moodle->enrollUserInCourse($moodleUserId, $courseId);

            MoodleMatricula::updateOrCreate(
                [
                    'inscripcion_id' => $inscripcion->id,
                    'modulo_id' => $moduloId,
                ],
                [
                    'moodle_user_id' => $moodleUserId,
                    'moodle_course_id' => $courseId,
                    'matriculado_at' => now(),
                ]
            );

            // Sincronizar moodle_user_id en inscripciones para que funcione
            // el enrolamiento masivo en módulos posteriores
            if (!$inscripcion->moodle_user_id) {
                $inscripcion->moodle_user_id      = $moodleUserId;
                $inscripcion->en_moodle           = true;
                $inscripcion->matriculado_moodle_at = now();
                $inscripcion->save();
            }

            $resultados[] = [
                'estudiante_id'  => $item['estudiante_id'],
                'nombre'         => $nombreCompleto,
                'moodle_user_id' => $moodleUserId,
                'ok'             => true,
                'mensaje'        => 'Matriculado correctamente.',
            ];
        }

        $exitosos = count(array_filter($resultados, fn($r) => $r['ok']));
        $fallidos  = count($resultados) - $exitosos;

        return response()->json([
            'success'    => true,
            'mensaje'    => "{$exitosos} matriculado(s) correctamente." . ($fallidos ? " {$fallidos} con error." : ''),
            'resultados' => $resultados,
        ]);
    }

    /**
     * Devuelve la grilla estudiante × módulo con estado de cuota y matrícula Moodle.
     */
    public function controlAccesoData(int $ofertaId)
    {
        $modulos = Modulo::where('ofertas_academica_id', $ofertaId)
            ->orderBy('n_modulo')
            ->get();

        $inscripciones = Inscripcione::with(['estudiante.persona', 'cuotas'])
            ->where('ofertas_academica_id', $ofertaId)
            ->where('estado', 'Inscrito')
            ->get();

        if ($inscripciones->isEmpty()) {
            return response()->json(['success' => true, 'modulos' => [], 'estudiantes' => []]);
        }

        $hoy = now()->toDateString();

        $matriculasMap = MoodleMatricula::whereIn('modulo_id', $modulos->pluck('id'))
            ->get()
            ->keyBy(fn($m) => $m->inscripcion_id . '-' . $m->modulo_id);

        $moodleUserIds = MoodleMatricula::whereIn('inscripcion_id', $inscripciones->pluck('id'))
            ->whereNotNull('moodle_user_id')
            ->get()
            ->unique('inscripcion_id')
            ->pluck('moodle_user_id', 'inscripcion_id');

        $estudiantesData = [];

        foreach ($inscripciones as $inscripcion) {
            $persona = $inscripcion->estudiante?->persona;
            if (!$persona) continue;

            $nombre = trim(($persona->nombres ?? '') . ' ' .
                ($persona->apellido_paterno ?? '') . ' ' .
                ($persona->apellido_materno ?? ''));

            $moodleUserId = $moodleUserIds[$inscripcion->id] ?? null;

            $cuotasColeg = $inscripcion->cuotas
                ->filter(fn($c) => stripos($c->nombre, 'coleg') !== false)
                ->sortBy('n_cuota')
                ->values();

            $modulosData = [];
            foreach ($modulos as $modulo) {
                $matricula = $matriculasMap->get($inscripcion->id . '-' . $modulo->id);

                $cuota = $cuotasColeg->get($modulo->n_modulo - 1);

                $cuotaData = null;
                if ($cuota) {
                    $vencFecha = $cuota->fecha_vencimiento?->toDateString();
                    $vencida   = $vencFecha && $vencFecha <= $hoy;
                    $pagada    = strtolower($cuota->estado ?? '') === 'pagado'
                        || (float) $cuota->pago_pendiente_bs <= 0;

                    $cuotaData = [
                        'id'                => $cuota->id,
                        'nombre'            => $cuota->nombre,
                        'monto_bs'          => (float) $cuota->monto_bs,
                        'pago_pendiente_bs' => (float) $cuota->pago_pendiente_bs,
                        'fecha_vencimiento' => $vencFecha,
                        'estado'            => $cuota->estado,
                        'vencida'           => $vencida,
                        'pagada'            => $pagada,
                    ];
                }

                $modulosData[$modulo->id] = [
                    'modulo_id'         => $modulo->id,
                    'moodle_course_id'  => $modulo->moodle_course_id,
                    'matriculado'       => $matricula !== null,
                    'acceso_suspendido' => $matricula?->acceso_suspendido ?? false,
                    'cuota'             => $cuotaData,
                ];
            }

            $estudiantesData[] = [
                'inscripcion_id'    => $inscripcion->id,
                'nombre'            => $nombre,
                'celular'           => $persona->celular ?? '',
                'moodle_user_id'    => $moodleUserId,
                'tiene_cuenta_moodle' => $moodleUserId !== null,
                'modulos'           => $modulosData,
            ];
        }

        return response()->json([
            'success'     => true,
            'modulos'     => $modulos->map(fn($m) => [
                'id'              => $m->id,
                'nombre'          => $m->nombre,
                'n_modulo'        => $m->n_modulo,
                'moodle_course_id'=> $m->moodle_course_id,
            ])->values(),
            'estudiantes' => $estudiantesData,
        ]);
    }

    /**
     * Suspende o reactiva el acceso de un estudiante a un curso Moodle específico.
     */
    public function suspenderAcceso(Request $request, int $moduloId)
    {
        Log::info('suspenderAcceso inicio', ['moduloId' => $moduloId, 'body' => $request->all()]);
        
        $request->validate([
            'inscripcion_id' => 'required|integer',
            'suspender'      => 'required|boolean',
        ]);

        $modulo = Modulo::find($moduloId);
        if (!$modulo || !$modulo->moodle_course_id) {
            Log::warning('suspenderAcceso: modulo sin moodle_course_id', ['moduloId' => $moduloId]);
            return response()->json(['success' => false, 'message' => 'Módulo sin curso en Moodle.'], 422);
        }

        $matricula = MoodleMatricula::where('inscripcion_id', $request->inscripcion_id)
            ->where('modulo_id', $moduloId)
            ->whereNotNull('moodle_user_id')
            ->first();

        if (!$matricula) {
            Log::warning('suspenderAcceso: matricula no encontrada', [
                'inscripcion_id' => $request->inscripcion_id,
                'modulo_id' => $moduloId
            ]);
            return response()->json([
                'success' => false,
                'message' => 'El estudiante no está matriculado en este módulo de Moodle.',
            ], 422);
        }

        Log::info('suspenderAcceso intentando', [
            'moodle_user_id' => $matricula->moodle_user_id,
            'moodle_course_id' => $modulo->moodle_course_id,
            'suspender' => $request->suspender
        ]);

        $moodleResult = $this->moodle->suspendEnrollment(
            $matricula->moodle_user_id,
            $modulo->moodle_course_id,
            (bool) $request->suspender
        );

        if ($moodleResult === false) {
            Log::error('suspenderAcceso: Moodle API falló', [
                'moodle_user_id' => $matricula->moodle_user_id,
                'moodle_course_id' => $modulo->moodle_course_id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al contactar Moodle. Intenta de nuevo.',
            ], 500);
        }

        if (is_array($moodleResult) && isset($moodleResult['error'])) {
            Log::warning('suspenderAcceso: error de negocio', $moodleResult);
            return response()->json([
                'success' => false,
                'message' => $moodleResult['message'] ?? 'Error al procesar.',
            ], 422);
        }

        $matricula->acceso_suspendido = (bool) $request->suspender;
        $matricula->save();

        Log::info('suspenderAcceso success', ['acceso_suspendido' => $matricula->acceso_suspendido]);

        return response()->json([
            'success' => true,
            'message' => $request->suspender
                ? 'Acceso bloqueado correctamente.'
                : 'Acceso habilitado correctamente.',
        ]);
    }

    /**
     * Matricula masivamente en el curso Moodle del módulo.
     * Usa inscripciones.moodle_user_id — los estudiantes ya tienen cuenta en Moodle.
     */
    public function matricularTodosEnMoodle(int $moduloId)
    {
        try {
            $modulo = Modulo::find($moduloId);
            if (!$modulo) {
                return response()->json(['success' => false, 'message' => 'Módulo no encontrado.'], 404);
            }

            if (!$modulo->moodle_course_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'El módulo no tiene un curso en Moodle.',
                ], 422);
            }

            $inscripciones = Inscripcione::where('ofertas_academica_id', $modulo->ofertas_academica_id)
                ->where('estado', 'Inscrito')
                ->get();

            if ($inscripciones->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'mensaje' => 'No hay estudiantes inscritos en esta oferta.',
                    'exitosos' => 0, 'omitidos' => 0, 'fallidos' => 0,
                ]);
            }

            $matriculasExistentes = MoodleMatricula::where('modulo_id', $moduloId)
                ->get()
                ->keyBy('inscripcion_id');

            $courseId = $modulo->moodle_course_id;
            $exitosos = 0;
            $omitidos = 0;
            $fallidos = 0;

            foreach ($inscripciones as $inscripcion) {
                $existingMatricula = $matriculasExistentes->get($inscripcion->id);
                $moodleUserId = $inscripcion->moodle_user_id ?? $existingMatricula?->moodle_user_id;

                if (!$moodleUserId) {
                    $omitidos++;
                    continue;
                }

                // Verificar si realmente está matriculado en el curso Moodle
                $enrolled = $this->moodle->isUserEnrolledInCourse((int) $moodleUserId, $courseId);

                if ($enrolled) {
                    // Asegurar que exista el registro local aunque venga de una fuente externa
                    if (!$existingMatricula) {
                        MoodleMatricula::create([
                            'inscripcion_id'  => $inscripcion->id,
                            'modulo_id'       => $moduloId,
                            'moodle_user_id'  => $moodleUserId,
                            'moodle_course_id'=> $courseId,
                            'matriculado_at'  => now(),
                        ]);
                    }
                    $omitidos++;
                    continue;
                }

                // No está en el curso — matricular
                $ok = $this->moodle->enrollUserInCourse((int) $moodleUserId, $courseId);

                if (!$ok) {
                    $fallidos++;
                    continue;
                }

                MoodleMatricula::updateOrCreate(
                    ['inscripcion_id' => $inscripcion->id, 'modulo_id' => $moduloId],
                    [
                        'moodle_user_id'   => $moodleUserId,
                        'moodle_course_id' => $courseId,
                        'matriculado_at'   => now(),
                        'acceso_suspendido'=> false,
                    ]
                );

                if (!$inscripcion->moodle_user_id) {
                    $inscripcion->moodle_user_id        = $moodleUserId;
                    $inscripcion->en_moodle             = true;
                    $inscripcion->matriculado_moodle_at = now();
                    $inscripcion->save();
                }

                $exitosos++;
            }

            $msg = "{$exitosos} estudiante(s) matriculado(s) en el curso Moodle.";
            if ($omitidos) $msg .= " {$omitidos} ya estaban matriculados.";
            if ($fallidos) $msg .= " {$fallidos} con error al matricular.";

            return response()->json([
                'success'  => true,
                'mensaje'  => $msg,
                'exitosos' => $exitosos,
                'omitidos' => $omitidos,
                'fallidos' => $fallidos,
            ]);
        } catch (\Exception $e) {
            Log::error('matricularTodosEnMoodle error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Matricula a un estudiante individual en el curso Moodle del módulo.
     * Usa inscripciones.moodle_user_id directamente.
     */
    public function matricularUnoEnMoodle(int $moduloId, int $inscripcionId)
    {
        try {
            $modulo = Modulo::find($moduloId);
            if (!$modulo || !$modulo->moodle_course_id) {
                return response()->json(['success' => false, 'message' => 'Módulo sin curso en Moodle.'], 422);
            }

            $inscripcion = Inscripcione::find($inscripcionId);
            if (!$inscripcion) {
                return response()->json(['success' => false, 'message' => 'Inscripción no encontrada.'], 404);
            }

            // Buscar moodle_user_id en inscripcion o en MoodleMatricula
            $moodleUserId = $inscripcion->moodle_user_id;
            if (!$moodleUserId) {
                $matricula = MoodleMatricula::where('inscripcion_id', $inscripcionId)
                    ->where('modulo_id', $moduloId)
                    ->first();
                $moodleUserId = $matricula?->moodle_user_id;
            }

            if (!$moodleUserId) {
                return response()->json(['success' => false, 'message' => 'El estudiante no tiene cuenta en Moodle.'], 422);
            }

            // Verificar si ya está matriculado realmente en Moodle
            $enrolled = $this->moodle->isUserEnrolledInCourse((int) $moodleUserId, $modulo->moodle_course_id);
            if ($enrolled) {
                // Asegurar que exista el registro local
                MoodleMatricula::updateOrCreate(
                    ['inscripcion_id' => $inscripcionId, 'modulo_id' => $moduloId],
                    [
                        'moodle_user_id'   => $moodleUserId,
                        'moodle_course_id' => $modulo->moodle_course_id,
                        'matriculado_at'   => now(),
                        'acceso_suspendido'=> false,
                    ]
                );
                return response()->json(['success' => true, 'mensaje' => 'El estudiante ya estaba matriculado en Moodle. Registro sincronizado.']);
            }

            // No está en el curso — matricular
            $ok = $this->moodle->enrollUserInCourse((int) $moodleUserId, $modulo->moodle_course_id);

            if (!$ok) {
                return response()->json(['success' => false, 'message' => 'Error al matricular en Moodle. Intenta de nuevo.'], 500);
            }

            MoodleMatricula::updateOrCreate(
                ['inscripcion_id' => $inscripcionId, 'modulo_id' => $moduloId],
                [
                    'moodle_user_id'   => $moodleUserId,
                    'moodle_course_id' => $modulo->moodle_course_id,
                    'matriculado_at'   => now(),
                    'acceso_suspendido'=> false,
                ]
            );

            if (!$inscripcion->moodle_user_id) {
                $inscripcion->moodle_user_id        = $moodleUserId;
                $inscripcion->en_moodle             = true;
                $inscripcion->matriculado_moodle_at = now();
                $inscripcion->save();
            }

            return response()->json(['success' => true, 'mensaje' => 'Estudiante matriculado en Moodle correctamente.']);
        } catch (\Exception $e) {
            Log::error('matricularUnoEnMoodle error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Genera contraseña que cumple política Moodle: mayúscula + minúscula + dígito + especial + ≥8 chars.
     */
    private function generarPasswordSegura(?string $carnet): string
    {
        $base = preg_replace('/[^a-zA-Z0-9]/', '', $carnet ?: '');

        $letters = preg_replace('/[^a-zA-Z]/', '', $base) ?: 'Inn';
        $digits  = preg_replace('/[^0-9]/', '', $base) ?: '01';

        $pwd = strtoupper(substr($letters, 0, 1))
             . strtolower(substr($letters, 1, 4))
             . str_pad(substr($digits, 0, 2), 2, '0', STR_PAD_RIGHT)
             . '#';

        if (strlen($pwd) < 8) {
            $pwd = str_pad($pwd, 8, 'a', STR_PAD_LEFT);
        }

        return $pwd;
    }
}