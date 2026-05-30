<?php

namespace App\Http\Controllers;

use App\Models\LocalGradeEntry;
use App\Models\Modulo;
use App\Models\MoodleMatricula;
use App\Models\MoodleGradeConfig;
use App\Models\Inscripcione;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicoController extends Controller
{
    public function __construct(private MoodleService $moodle) {}

    /**
     * Devuelve los ítems de calificación de Moodle y las notas de cada estudiante.
     * También incluye las ponderaciones locales guardadas para el módulo.
     */
    public function getGradeBook(int $moduloId)
    {
        try {
            return $this->_getGradeBook($moduloId);
        } catch (\Throwable $e) {
            \Log::error("getGradeBook failed for modulo $moduloId: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno al cargar calificaciones: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function _getGradeBook(int $moduloId)
    {
        $modulo = Modulo::findOrFail($moduloId);

        if (!$modulo->moodle_course_id) {
            return response()->json([
                'success' => false,
                'message' => 'El módulo no tiene curso asignado en Moodle.',
            ]);
        }

        // Matrículas de estudiantes en Moodle (excluir docentes)
        $matriculas = MoodleMatricula::where('modulo_id', $moduloId)
            ->whereNotNull('inscripcion_id')
            ->whereNotNull('moodle_user_id')
            ->whereNull('docente_id')
            ->get();

        if ($matriculas->isEmpty()) {
            // Fallback: buscar estudiantes con moodle_user_id en inscripciones (sistema anterior)
            $inscripcionesConMoodle = Inscripcione::where('ofertas_academica_id', $modulo->ofertas_academica_id)
                ->whereNotNull('moodle_user_id')
                ->whereIn('estado', ['Inscrito', 'Confirmado', 'Activo', 'activo'])
                ->with('estudiante.persona')
                ->get();

            if ($inscripcionesConMoodle->isNotEmpty() && $modulo->moodle_course_id) {
                // Crear registros moodle_matriculas para estos estudiantes y enrolarlos
                foreach ($inscripcionesConMoodle as $insc) {
                    MoodleMatricula::updateOrCreate(
                        ['inscripcion_id' => $insc->id, 'modulo_id' => $moduloId],
                        [
                            'moodle_user_id'   => $insc->moodle_user_id,
                            'moodle_course_id' => $modulo->moodle_course_id,
                            'matriculado_at'   => now(),
                        ]
                    );
                }
                // Recargar con los nuevos registros
                $matriculas = MoodleMatricula::where('modulo_id', $moduloId)
                    ->whereNotNull('inscripcion_id')
                    ->whereNotNull('moodle_user_id')
                    ->whereNull('docente_id')
                    ->get();
            }

            if ($matriculas->isEmpty()) {
                $sinMoodleId    = MoodleMatricula::where('modulo_id', $moduloId)
                    ->whereNotNull('inscripcion_id')
                    ->whereNull('moodle_user_id')
                    ->whereNull('docente_id')
                    ->count();
                $totalRegistros = MoodleMatricula::where('modulo_id', $moduloId)->count();

                $msg = 'No hay estudiantes matriculados en Moodle para este módulo.';
                if ($sinMoodleId > 0) {
                    $msg = "Hay {$sinMoodleId} estudiante(s) con matrícula local pero sin cuenta Moodle asignada. Deben ser matriculados en Moodle desde el panel de administración.";
                } elseif ($totalRegistros === 0) {
                    $msg = 'No hay registros de matrícula Moodle para este módulo. Los estudiantes deben ser matriculados en Moodle desde el panel de administración.';
                }

                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ]);
            }
        }

        // Datos de cada estudiante vía inscripciones
        $inscripcionIds = $matriculas->pluck('inscripcion_id')->filter()->values();
        $inscripciones  = Inscripcione::whereIn('id', $inscripcionIds)
            ->with('estudiante.persona')
            ->get()
            ->keyBy('id');

        $moodleUserIds = $matriculas->pluck('moodle_user_id')->filter()->unique()->values()->toArray();

        // Calificaciones desde Moodle
        $items = $this->moodle->getStudentGrades($modulo->moodle_course_id, $moodleUserIds);

        if (empty($items)) {
            return $this->_getGradeBookManual($moduloId, $modulo, $matriculas, $moodleUserIds, $inscripciones);
        }

        // Ponderaciones locales guardadas
        $localConfigs = MoodleGradeConfig::where('modulo_id', $moduloId)
            ->get()
            ->keyBy('moodle_item_id');

        // Determinar modo acumulatorio desde la primera config guardada (o default true)
        $isCumulative = $localConfigs->isNotEmpty()
            ? (bool) $localConfigs->first()->is_cumulative
            : true;

        // Combinar pesos locales con ítems de Moodle
        $gradeItems = [];
        foreach ($items as $item) {
            $config = $localConfigs->get($item['id']);
            $gradeItems[] = [
                'id'               => $item['id'],
                'name'             => $item['name'],
                'module'           => $item['module'],
                'cmid'             => $item['cmid'],
                'max'              => $item['max'],
                'moodle_weight'    => $item['weight'],
                'weight'           => $config ? (float) $config->weight : ($item['weight'] > 0 ? (float) $item['weight'] : (float) $item['max']),
                'calculation_mode' => $config ? ($config->calculation_mode ?? 'ponderar') : 'ponderar',
                'grades'           => $item['grades'],
            ];
        }

        $estudiantes = $this->_buildEstudiantes($matriculas, $inscripciones);

        return response()->json([
            'success'          => true,
            'grade_items'      => $gradeItems,
            'estudiantes'      => $estudiantes,
            'is_cumulative'    => $isCumulative,
            'manual_mode'      => false,
            'moodle_url'       => rtrim(config('moodle.url'), '/'),
            'moodle_course_id' => $modulo->moodle_course_id,
        ]);
    }

    private function _buildEstudiantes($matriculas, $inscripciones): array
    {
        $estudiantes = [];
        foreach ($matriculas as $mat) {
            $inscripcion = $inscripciones->get($mat->inscripcion_id);
            $persona     = $inscripcion?->estudiante?->persona;
            $estudiantes[] = [
                'moodle_user_id' => (int) $mat->moodle_user_id,
                'nombre' => $persona
                    ? trim(($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '') . ' ' . ($persona->nombres ?? ''))
                    : 'Sin nombre',
                'ci' => $persona?->carnet ?? '—',
            ];
        }
        usort($estudiantes, fn($a, $b) => strcmp($a['nombre'], $b['nombre']));
        return $estudiantes;
    }

    private function _getGradeBookManual(int $moduloId, $modulo, $matriculas, array $moodleUserIds, $inscripciones)
    {
        $manualConfigs = MoodleGradeConfig::where('modulo_id', $moduloId)
            ->where('moodle_item_id', '<', 0)
            ->orderBy('moodle_item_id', 'desc')
            ->get();

        $isCumulative = MoodleGradeConfig::where('modulo_id', $moduloId)->value('is_cumulative') ?? true;
        $estudiantes  = $this->_buildEstudiantes($matriculas, $inscripciones);

        $gradeItems = [];
        if ($manualConfigs->isNotEmpty()) {
            $localEntries = LocalGradeEntry::where('modulo_id', $moduloId)
                ->whereIn('moodle_item_id', $manualConfigs->pluck('moodle_item_id'))
                ->get()
                ->groupBy('moodle_item_id');

            foreach ($manualConfigs as $config) {
                $grades     = [];
                $itemGrades = $localEntries->get($config->moodle_item_id, collect());
                foreach ($itemGrades as $entry) {
                    $grades[(int) $entry->moodle_user_id] = $entry->grade !== null ? (float) $entry->grade : null;
                }
                foreach ($moodleUserIds as $uid) {
                    if (!array_key_exists((int) $uid, $grades)) {
                        $grades[(int) $uid] = null;
                    }
                }
                $gradeItems[] = [
                    'id'               => $config->moodle_item_id,
                    'name'             => $config->activity_name,
                    'module'           => $config->activity_type,
                    'cmid'             => 0,
                    'max'              => (float) ($config->max_grade ?? 100),
                    'moodle_weight'    => (float) $config->weight,
                    'weight'           => (float) $config->weight,
                    'calculation_mode' => $config->calculation_mode ?? 'ponderar',
                    'grades'           => $grades,
                    'is_manual'        => true,
                ];
            }
        }

        return response()->json([
            'success'          => true,
            'grade_items'      => $gradeItems,
            'estudiantes'      => $estudiantes,
            'is_cumulative'    => (bool) $isCumulative,
            'manual_mode'      => true,
            'moodle_url'       => rtrim(config('moodle.url'), '/'),
            'moodle_course_id' => $modulo->moodle_course_id,
        ]);
    }

    public function storeLocalItem(Request $request, int $moduloId)
    {
        $request->validate([
            'name'   => 'required|string|max:200',
            'type'   => 'required|string|max:50',
            'max'    => 'required|numeric|min:1|max:10000',
            'weight' => 'required|numeric|min:0|max:100',
        ]);

        Modulo::findOrFail($moduloId);

        $minId    = MoodleGradeConfig::where('modulo_id', $moduloId)
            ->where('moodle_item_id', '<', 0)
            ->min('moodle_item_id') ?? 0;
        $newItemId = $minId - 1;

        MoodleGradeConfig::create([
            'modulo_id'      => $moduloId,
            'moodle_item_id' => $newItemId,
            'activity_name'  => $request->name,
            'activity_type'  => $request->type,
            'max_grade'      => $request->max,
            'cmid'           => 0,
            'weight'         => $request->weight,
            'is_cumulative'  => true,
        ]);

        return response()->json([
            'success' => true,
            'item'    => [
                'id'            => $newItemId,
                'name'          => $request->name,
                'module'        => $request->type,
                'cmid'          => 0,
                'max'           => (float) $request->max,
                'moodle_weight' => (float) $request->weight,
                'weight'        => (float) $request->weight,
                'grades'        => (object) [],
                'is_manual'     => true,
            ],
        ]);
    }

    public function destroyLocalItem(int $moduloId, int $itemId)
    {
        if ($itemId >= 0) {
            return response()->json(['success' => false, 'message' => 'Solo se pueden eliminar actividades manuales.'], 422);
        }

        MoodleGradeConfig::where('modulo_id', $moduloId)
            ->where('moodle_item_id', $itemId)
            ->delete();

        LocalGradeEntry::where('modulo_id', $moduloId)
            ->where('moodle_item_id', $itemId)
            ->delete();

        return response()->json(['success' => true]);
    }

    public function saveLocalGrades(Request $request, int $moduloId)
    {
        $request->validate([
            'grades'   => 'required|array',
            'grades.*' => 'array',
        ]);

        Modulo::findOrFail($moduloId);

        foreach ($request->grades as $itemIdStr => $userGrades) {
            $itemId = (int) $itemIdStr;
            if ($itemId >= 0) continue;

            foreach ((array) $userGrades as $userIdStr => $grade) {
                LocalGradeEntry::updateOrCreate(
                    [
                        'modulo_id'      => $moduloId,
                        'moodle_item_id' => $itemId,
                        'moodle_user_id' => (int) $userIdStr,
                    ],
                    ['grade' => ($grade !== null && $grade !== '') ? (float) $grade : null]
                );
            }
        }

        return response()->json(['success' => true, 'mensaje' => 'Calificaciones guardadas correctamente.']);
    }

    /**
     * Guarda las ponderaciones de los ítems de calificación para el módulo.
     * Si es acumulatoria, la suma debe ser exactamente 100.
     */
    public function saveWeights(Request $request, int $moduloId)
    {
        $request->validate([
            'items'                       => 'required|array|min:1',
            'items.*.id'                  => 'required|integer',
            'items.*.name'                => 'required|string',
            'items.*.module'              => 'nullable|string',
            'items.*.cmid'                => 'nullable|integer',
            'items.*.weight'              => 'required|numeric|min:0|max:100',
            'items.*.calculation_mode'    => 'nullable|in:ponderar,mantener',
            'is_cumulative'               => 'required|boolean',
        ]);

        $isCumulative = (bool) $request->is_cumulative;
        $items        = $request->items;

        Modulo::findOrFail($moduloId);

        foreach ($items as $item) {
            MoodleGradeConfig::updateOrCreate(
                [
                    'modulo_id'      => $moduloId,
                    'moodle_item_id' => $item['id'],
                ],
                [
                    'activity_name'    => $item['name'],
                    'activity_type'    => $item['module'] ?? 'assign',
                    'cmid'             => $item['cmid'] ?? null,
                    'weight'           => $item['weight'],
                    'is_cumulative'    => $isCumulative,
                    'calculation_mode' => $item['calculation_mode'] ?? 'ponderar',
                ]
            );
        }

        return response()->json([
            'success' => true,
            'mensaje' => 'Ponderaciones guardadas correctamente.',
        ]);
    }

    public function reporteNotasDetallado(int $moduloId)
    {
        $data = $this->_buildReporteData($moduloId);
        $pdf  = \Pdf::loadView('admin.ofertas-academicas.reporte-notas-detallado', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('reporte-notas-detallado.pdf');
    }

    public function reporteNotasFinales(int $moduloId)
    {
        $data = $this->_buildReporteData($moduloId);
        $pdf  = \Pdf::loadView('admin.ofertas-academicas.reporte-notas-finales', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('reporte-notas-finales.pdf');
    }

    private function _buildReporteData(int $moduloId): array
    {
        $modulo = Modulo::with([
            'docente.persona',
            'oferta_academica.programa',
            'oferta_academica.trabajador_cargo_academico.trabajador.persona',
        ])->findOrFail($moduloId);

        $matriculas = MoodleMatricula::where('modulo_id', $moduloId)
            ->whereNotNull('inscripcion_id')
            ->whereNotNull('moodle_user_id')
            ->whereNull('docente_id')
            ->get();

        $inscripcionIds = $matriculas->pluck('inscripcion_id')->filter()->values();
        $inscripciones  = Inscripcione::whereIn('id', $inscripcionIds)
            ->with('estudiante.persona')
            ->get()
            ->keyBy('id');

        $moodleUserIds = $matriculas->pluck('moodle_user_id')->filter()->unique()->values()->toArray();
        $items         = $this->moodle->getStudentGrades($modulo->moodle_course_id, $moodleUserIds);

        $localConfigs = MoodleGradeConfig::where('modulo_id', $moduloId)
            ->get()
            ->keyBy('moodle_item_id');

        $isCumulative = $localConfigs->isNotEmpty()
            ? (bool) $localConfigs->first()->is_cumulative
            : true;

        $gradeItems = [];
        foreach ($items as $item) {
            $config       = $localConfigs->get($item['id']);
            $gradeItems[] = [
                'id'     => $item['id'],
                'name'   => $item['name'],
                'module' => $item['module'],
                'max'    => $item['max'],
                'weight' => $config ? (float) $config->weight : ($item['weight'] > 0 ? (float) $item['weight'] : (float) $item['max']),
                'grades' => $item['grades'],
            ];
        }

        $estudiantes = [];
        foreach ($matriculas as $mat) {
            $inscripcion  = $inscripciones->get($mat->inscripcion_id);
            $persona      = $inscripcion?->estudiante?->persona;
            $moodleUserId = (int) $mat->moodle_user_id;

            $notasPorActividad = [];
            $notaFinal         = 0.0;
            $cnt               = 0;

            foreach ($gradeItems as $item) {
                $rawVal    = $item['grades'][$moodleUserId] ?? null;
                $raw       = $rawVal !== null ? (float) $rawVal : 0.0;
                $max       = $item['max'] !== null ? (float) $item['max'] : null;
                $weight    = (float) $item['weight'];
                $ponderada = ($max !== null && $max > 0) ? ($raw / $max) * $weight : 0.0;

                $notasPorActividad[$item['id']] = [
                    'raw'       => $rawVal !== null ? round($raw, 2) : null,
                    'ponderada' => round($ponderada, 2),
                ];

                $notaFinal += $ponderada;
                $cnt++;
            }

            if (!$isCumulative && $cnt > 0) {
                $notaFinal = $notaFinal / $cnt;
            }

            $notaFinal = round($notaFinal, 2);

            $estudiantes[] = [
                'nombre'       => $persona
                    ? trim(($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '') . ' ' . ($persona->nombres ?? ''))
                    : 'Sin nombre',
                'ci'           => $persona?->carnet ?? '—',
                'notas'        => $notasPorActividad,
                'nota_final'   => $notaFinal,
                'nota_literal' => $this->numeroALiteral($notaFinal),
            ];
        }

        usort($estudiantes, fn($a, $b) => strcmp($a['nombre'], $b['nombre']));

        $docentePersona = $modulo->docente?->persona;
        $docenteNombre  = $docentePersona
            ? trim(($docentePersona->nombres ?? '') . ' ' . ($docentePersona->apellido_paterno ?? '') . ' ' . ($docentePersona->apellido_materno ?? ''))
            : '——————';

        $acadPersona   = $modulo->oferta_academica?->trabajador_cargo_academico?->trabajador?->persona;
        $acadNombre    = $acadPersona
            ? trim(($acadPersona->nombres ?? '') . ' ' . ($acadPersona->apellido_paterno ?? '') . ' ' . ($acadPersona->apellido_materno ?? ''))
            : '——————';

        return [
            'modulo'          => $modulo,
            'grade_items'     => $gradeItems,
            'estudiantes'     => $estudiantes,
            'is_cumulative'   => $isCumulative,
            'docente_nombre'  => $docenteNombre,
            'academico_nombre'=> $acadNombre,
            'programa_nombre' => $modulo->oferta_academica?->programa?->nombre ?? '——',
            'oferta_codigo'   => $modulo->oferta_academica?->codigo ?? '——',
        ];
    }

    private function numeroALiteral(float $numero): string
    {
        $entero    = (int) floor($numero);
        $decimales = (int) round(($numero - $entero) * 100);
        $formatter = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);
        $palabras  = mb_strtoupper($formatter->format($entero), 'UTF-8');
        if ($decimales > 0) {
            $decPalabras = mb_strtoupper($formatter->format($decimales), 'UTF-8');
            return $palabras . ' CON ' . $decPalabras . '/100';
        }
        return $palabras;
    }

    /**
     * Sincroniza las ponderaciones guardadas con la base de datos de Moodle:
     * actualiza grademax de cada actividad y recalcula las notas de los estudiantes.
     */
    public function sincronizarMoodle(Request $request, int $moduloId)
    {
        try {
            $request->validate([
                'items'                  => 'required|array|min:1',
                'items.*.id'             => 'required|integer',
                'items.*.module'         => 'required|string',
                'items.*.cmid'           => 'required|integer',
                'items.*.peso'           => 'required|numeric|min:0',
                'items.*.peso_original'  => 'required|numeric|min:0',
                'items.*.modo'           => 'required|in:ponderar,mantener',
                'items.*.grades'         => 'nullable|array',
            ]);

            $modulo = Modulo::findOrFail($moduloId);

            if (!$modulo->moodle_course_id) {
                return response()->json(['success' => false, 'mensaje' => 'El módulo no tiene curso en Moodle.']);
            }

            $db = DB::connection('moodle');
            $totalActualizados = 0;
            $totalErrores      = 0;
            $detalles          = [];

            foreach ($request->items as $itemData) {
                $gradeItemId  = (int)   $itemData['id'];
                $moduleType   =         $itemData['module'];
                $cmid         = (int)   $itemData['cmid'];
                // Skip manual items (they have cmid=0 and negative IDs)
                if ($cmid === 0) continue;
                $nuevoPeso    = (float) $itemData['peso'];
                $viejoPeso    = (float) $itemData['peso_original'];
                $modo         =         $itemData['modo'];
                $grades       =         $itemData['grades'] ?? [];

                // Obtener instance_id desde course_modules
                $cm = $db->table('course_modules')->where('id', $cmid)->first(['instance']);
                if (!$cm) {
                    $totalErrores++;
                    $detalles[] = ['item_id' => $gradeItemId, 'error' => 'course_module no encontrado'];
                    continue;
                }
                $instanceId = (int) $cm->instance;

                // Convertir grades a [int => float|null]
                $estudiantesGrades = [];
                foreach ($grades as $userId => $rawGrade) {
                    $estudiantesGrades[(int) $userId] = $rawGrade !== null ? (float) $rawGrade : null;
                }

                $resultado = $this->moodle->sincronizarPonderacion(
                    $gradeItemId,
                    $moduleType,
                    $instanceId,
                    $modulo->moodle_course_id,
                    $nuevoPeso,
                    $viejoPeso,
                    $estudiantesGrades,
                    $modo
                );

                $totalActualizados += $resultado['actualizados'];
                $totalErrores      += $resultado['errores'];
                $detalles[]         = array_merge(['item_id' => $gradeItemId, 'modulo' => $moduleType], $resultado);
            }

            $exito = $totalErrores === 0;
            return response()->json([
                'success'      => $exito,
                'actualizados' => $totalActualizados,
                'errores'      => $totalErrores,
                'mensaje'      => $exito
                    ? "Moodle actualizado: {$totalActualizados} calificación(es) sincronizada(s)."
                    : "Sincronización parcial: {$totalActualizados} actualizadas, {$totalErrores} con error.",
                'detalles'     => $detalles,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Datos inválidos: ' . implode(' ', array_map(fn($msgs) => implode(' ', $msgs), $e->errors())),
            ], 422);
        } catch (\Throwable $e) {
            \Log::error("sincronizarMoodle moduloId={$moduloId}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'mensaje' => 'Error interno al sincronizar: ' . $e->getMessage(),
            ], 500);
        }
    }
}
