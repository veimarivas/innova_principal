<?php

namespace App\Http\Controllers;

use App\Models\Inscripcione;
use App\Models\Modulo;
use App\Models\MoodleMatricula;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActividadesGestionController extends Controller
{
    public function __construct(private MoodleService $moodle) {}

    private function getModulo(int $moduloId): Modulo
    {
        $modulo = Modulo::findOrFail($moduloId);
        if (!$modulo->moodle_course_id) {
            abort(400, 'El módulo no tiene curso Moodle asignado.');
        }
        return $modulo;
    }

    /**
     * GET /admin/posgrads/modulos/{modulo}/actividades/{cmid}/entregas
     * Devuelve todos los estudiantes matriculados con su estado de entrega y calificación.
     */
    public function getSubmissions(int $moduloId, int $cmid)
    {
        try {
            $modulo = $this->getModulo($moduloId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Módulo no encontrado.'], 404);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getStatusCode());
        }

        $courseId = $modulo->moodle_course_id;

        // Resolver instance ID de la tarea
        try {
            $cm = DB::connection('moodle')
                ->table('course_modules')
                ->where('id', $cmid)
                ->first(['instance']);
            $instanceId = $cm ? (int) $cm->instance : null;
        } catch (\Exception $e) {
            Log::error("getSubmissions: error al resolver cmid={$cmid}: " . $e->getMessage());
            $instanceId = null;
        }

        // 1. Obtener todos los estudiantes matriculados
        $matriculas = MoodleMatricula::where('modulo_id', $moduloId)
            ->whereNotNull('inscripcion_id')
            ->whereNotNull('moodle_user_id')
            ->whereNull('docente_id')
            ->get();

        if ($matriculas->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay estudiantes matriculados en Moodle para este módulo.',
            ]);
        }

        // Datos de estudiantes (nombre, carnet)
        $inscripcionIds = $matriculas->pluck('inscripcion_id')->filter()->values();
        $inscripciones  = Inscripcione::whereIn('id', $inscripcionIds)
            ->with('estudiante.persona')
            ->get()
            ->keyBy('id');

        // 2. Obtener submissions desde BD de Moodle (archivos, fecha, late)
        $dbSubmissions = $this->moodle->getAssignSubmissionsFromDb($cmid);

        // 3. Obtener calificaciones existentes desde BD de Moodle
        $moodleUserIds = $matriculas->pluck('moodle_user_id')->filter()->unique()->values()->toArray();
        $gradesByUser = $this->moodle->getAssignGradesByUsers($cmid, $courseId, $moodleUserIds);

        // Obtener duedate para calcular tardanza
        $dueDate = 0;
        if ($instanceId) {
            try {
                $assign = DB::connection('moodle')->table('assign')->where('id', $instanceId)->first(['duedate']);
                $dueDate = $assign ? (int) $assign->duedate : 0;
            } catch (\Exception $e) {
                Log::error("getSubmissions: error al obtener duedate para assign={$instanceId}: " . $e->getMessage());
            }
        }

        // 4. Combinar todo
        $students = [];
        foreach ($matriculas as $mat) {
            $uid = (int) $mat->moodle_user_id;
            $inscripcion = $inscripciones->get($mat->inscripcion_id);
            $persona = $inscripcion?->estudiante?->persona;
            $nombre = $persona
                ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''))
                : 'Sin nombre';
            $carnet = $persona?->carnet ?? '';

            $sub = $dbSubmissions[$uid] ?? null;
            $grade = $gradesByUser[$uid] ?? null;

            $students[] = [
                'userid'         => $uid,
                'name'           => $nombre,
                'carnet'         => $carnet,
                'status'         => $sub ? ($sub['status'] === 'submitted' ? 'submitted' : 'draft') : 'none',
                'has_submission' => $sub !== null,
                'late'           => $sub ? $sub['late'] : false,
                'timemodified'   => $sub ? $sub['timemodified'] : null,
                'files'          => $sub ? $sub['files'] : [],
                'grade'          => $grade ? $grade['grade'] : null,
                'feedback'       => $grade ? $grade['feedback'] : '',
                'duedate'        => $dueDate ?: null,
            ];
        }

        return response()->json([
            'success'  => true,
            'students' => $students,
        ]);
    }

    /**
     * POST /admin/posgrads/modulos/{modulo}/actividades/{cmid}/calificar
     */
    public function saveGrade(Request $request, int $moduloId, int $cmid)
    {
        $this->getModulo($moduloId);

        $data = $request->validate([
            'user_id'  => 'required|integer',
            'grade'    => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:10000',
        ]);

        $feedback = strip_tags($data['feedback'] ?? '', '<strong><em><ul><ol><li><p><br>');

        $ok = $this->moodle->saveGrade(
            $cmid,
            (int)$data['user_id'],
            (float)$data['grade'],
            $feedback
        );

        if ($ok) {
            return response()->json(['success' => true, 'message' => 'Calificación guardada.']);
        }

        return response()->json(['success' => false, 'message' => 'Error al guardar la calificación en Moodle.'], 500);
    }

    /**
     * GET /admin/posgrads/modulos/{modulo}/actividades/foro/{forumId}/discusiones/{discussionId}/posts
     */
    public function getDiscussionPosts(int $moduloId, int $forumId, int $discussionId)
    {
        $modulo = $this->getModulo($moduloId);

        // Resolver cmid desde forumId
        $cm = DB::connection('moodle')
            ->table('course_modules')
            ->join('modules', 'modules.id', '=', 'course_modules.module')
            ->where('course_modules.instance', $forumId)
            ->where('modules.name', 'forum')
            ->first(['course_modules.id as cmid', 'course_modules.course']);
        $cmid = $cm ? (int) $cm->cmid : 0;
        $courseId = $cm ? (int) $cm->course : 0;

        $posts = $this->moodle->getDiscussionPosts($discussionId);

        // Obtener notas por usuario
        $userIds = [];
        foreach ($posts as $p) {
            $uid = (int) ($p['userid'] ?? 0);
            if ($uid) $userIds[$uid] = true;
        }
        $userIds = array_keys($userIds);

        $gradesByUser = [];
        if (!empty($userIds)) {
            $gradesByUser = $this->moodle->getForumGradesWithParticipation($cmid, $forumId, $courseId, $userIds);
        }

        // Agrupar posts por usuario y agregar calificación
        $groupedPosts = [];
        $seen = [];
        foreach ($posts as $p) {
            $uid = (int) ($p['userid'] ?? 0);
            if ($uid && !isset($seen[$uid])) {
                $gd = $gradesByUser[$uid] ?? ['grade' => null, 'grade_max' => 100];
                $seen[$uid] = true;
                $groupedPosts[] = [
                    '_user_header' => true,
                    'userid'       => $uid,
                    'userfullname' => $p['userfullname'] ?? '',
                    'grade'        => $gd['grade'],
                    'grade_max'    => $gd['grade_max'],
                ];
            }
            $groupedPosts[] = $p;
        }

        return response()->json([
            'success' => true,
            'posts'   => $groupedPosts,
            'cmid'    => $cmid,
        ]);
    }

    /**
     * POST /admin/posgrads/modulos/{modulo}/actividades/foro/{forumId}/discusiones/{discussionId}/responder
     */
    public function replyDiscussion(Request $request, int $moduloId, int $forumId, int $discussionId)
    {
        $this->getModulo($moduloId);

        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $postId = $this->moodle->addDiscussionPost(
            $discussionId,
            $data['subject'],
            $data['message']
        );

        if ($postId) {
            return response()->json(['success' => true, 'post_id' => $postId, 'message' => 'Respuesta publicada.']);
        }

        return response()->json(['success' => false, 'message' => 'Error al publicar la respuesta en Moodle.'], 500);
    }

    /**
     * GET /admin/posgrads/modulos/{modulo}/actividades/quiz/{quizId}/resultados
     */
    public function getQuizResults(int $moduloId, int $quizId)
    {
        $modulo = $this->getModulo($moduloId);

        $attempts = $this->moodle->getQuizAttempts($quizId);

        // Enriquecer con nombres de estudiantes
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

        $userMap = [];
        foreach ($matriculas as $mat) {
            $uid = (int) $mat->moodle_user_id;
            $inscripcion = $inscripciones->get($mat->inscripcion_id);
            $persona = $inscripcion?->estudiante?->persona;
            $nombre = $persona
                ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''))
                : 'Usuario #' . $uid;
            $userMap[$uid] = $nombre;
        }

        foreach ($attempts as &$a) {
            $a['user_name'] = $userMap[(int) $a['userid']] ?? ('Usuario #' . $a['userid']);
        }
        unset($a);

        return response()->json(['success' => true, 'attempts' => $attempts]);
    }

    /**
     * GET /admin/posgrads/modulos/{modulo}/actividades/quiz/{quizId}/resultados/{attemptId}
     */
    public function getAttemptDetail(int $moduloId, int $quizId, int $attemptId)
    {
        $this->getModulo($moduloId);
        $data = $this->moodle->getAttemptData($attemptId);
        return response()->json(['success' => true, 'questions' => $data['questions']]);
    }

    /**
     * GET /admin/posgrads/modulos/{modulo}/actividades/{cmid}/foro/calificaciones
     */
    public function getForumGrades(int $moduloId, int $cmid)
    {
        $modulo = $this->getModulo($moduloId);

        $cm = DB::connection('moodle')
            ->table('course_modules')
            ->where('id', $cmid)
            ->first(['instance']);
        $forumId = $cm ? (int) $cm->instance : 0;

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

        $gradesData = $this->moodle->getForumGradesWithParticipation($cmid, $forumId, $modulo->moodle_course_id, $moodleUserIds);

        $students = [];
        foreach ($matriculas as $mat) {
            $uid = (int) $mat->moodle_user_id;
            $inscripcion = $inscripciones->get($mat->inscripcion_id);
            $persona = $inscripcion?->estudiante?->persona;
            $nombre = $persona
                ? trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''))
                : 'Usuario #' . $uid;
            $gd = $gradesData[$uid] ?? ['grade' => null, 'grade_max' => 100, 'post_count' => 0];

            $students[] = [
                'userid'     => $uid,
                'name'       => $nombre,
                'carnet'     => $persona?->carnet ?? '',
                'grade'      => $gd['grade'],
                'grade_max'  => $gd['grade_max'],
                'post_count' => $gd['post_count'],
            ];
        }

        return response()->json(['success' => true, 'students' => $students]);
    }

    /**
     * POST /admin/posgrads/modulos/{modulo}/actividades/{cmid}/foro/calificar
     */
    public function saveForumGrade(Request $request, int $moduloId, int $cmid)
    {
        $modulo = $this->getModulo($moduloId);

        $data = $request->validate([
            'user_id' => 'required|integer',
            'grade'   => 'required|numeric|min:0|max:100',
        ]);

        $cm = DB::connection('moodle')
            ->table('course_modules')
            ->where('id', $cmid)
            ->first(['instance']);
        $forumId = $cm ? (int) $cm->instance : 0;

        $ok = $this->moodle->saveForumGradeDb(
            $cmid,
            $forumId,
            $modulo->moodle_course_id,
            (int) $data['user_id'],
            (float) $data['grade']
        );

        if ($ok) {
            return response()->json(['success' => true, 'message' => 'Calificación guardada.']);
        }

        return response()->json(['success' => false, 'message' => 'Error al guardar la calificación.'], 500);
    }

    /**
     * GET /admin/posgrads/modulos/{modulo}/actividades/{cmid}/archivo/{userId}/{filename}
     * Descarga un archivo de la submission de un estudiante.
     */
    public function downloadFile(int $moduloId, int $cmid, int $userId, string $filename)
    {
        $modulo = $this->getModulo($moduloId);

        $file = $this->moodle->getAssignmentFile($cmid, $modulo->moodle_course_id, $userId, $filename);
        if (!$file) {
            return response()->json(['success' => false, 'message' => 'Archivo no encontrado.'], 404);
        }

        return response()->stream(function () use ($file) {
            echo $file['content'];
        }, 200, [
            'Content-Type'        => $file['mimetype'] ?? 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . rawurlencode($file['filename']) . '"',
            'Content-Length'      => $file['filesize'],
        ]);
    }
}
