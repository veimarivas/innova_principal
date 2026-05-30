<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Inscripcione;
use App\Models\MoodleMatricula;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VirtualEstudianteController extends Controller
{
    public function __construct(private MoodleService $moodle) {}

    // ── Helpers ────────────────────────────────────────────────────────────────

    private function getMatricula(int $moduloId): ?MoodleMatricula
    {
        $persona = Auth::user()->persona;
        if (!$persona) return null;

        $estudiante = Estudiante::where('persona_id', $persona->id)->first();
        if (!$estudiante) return null;

        $inscripcionIds = Inscripcione::where('estudiante_id', $estudiante->id)->pluck('id');

        return MoodleMatricula::where('modulo_id', $moduloId)
            ->whereIn('inscripcion_id', $inscripcionIds)
            ->whereNotNull('moodle_course_id')
            ->whereNotNull('moodle_user_id')
            ->first();
    }

    private function getCm(int $cmid, int $courseId): ?object
    {
        return DB::connection('moodle')
            ->table('course_modules')
            ->where('id', $cmid)
            ->where('course', $courseId)
            ->first();
    }

    private function err403(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['success' => false, 'message' => 'No tienes acceso a este módulo.'], 403);
    }

    private function err404(string $msg = 'Actividad no encontrada.'): \Illuminate\Http\JsonResponse
    {
        return response()->json(['success' => false, 'message' => $msg], 404);
    }

    private function isForumExpired(object $cm): bool
    {
        $forum = DB::connection('moodle')
            ->table('forum')
            ->where('id', $cm->instance)
            ->first(['duedate', 'cutoffdate']);

        if (!$forum) return false;

        $now = time();
        if (!empty($forum->cutoffdate) && $now > $forum->cutoffdate) return true;
        if (!empty($forum->duedate) && $now > $forum->duedate) return true;

        return false;
    }

    // ── TAREA ──────────────────────────────────────────────────────────────────

    public function getTarea(int $moduloId, int $cmid)
    {
        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        $cm = $this->getCm($cmid, $mat->moodle_course_id);
        if (!$cm) return $this->err404();

        try {
            $data = $this->moodle->getAssignDetailsForStudent(
                $cmid,
                $mat->moodle_course_id,
                $mat->moodle_user_id
            );

            if (empty($data)) return $this->err404('No se encontró la tarea.');

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::getTarea [{$moduloId}/{$cmid}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al obtener la tarea.'], 500);
        }
    }

    public function submitTarea(Request $request, int $moduloId, int $cmid)
    {
        $request->validate(['text' => 'nullable|string|max:100000']);

        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        $cm = $this->getCm($cmid, $mat->moodle_course_id);
        if (!$cm) return $this->err404();

        try {
            $ok = $this->moodle->submitAssignmentOnlineText(
                $cmid,
                $mat->moodle_course_id,
                $mat->moodle_user_id,
                $request->input('text')
            );

            return $ok
                ? response()->json(['success' => true, 'message' => 'Tarea entregada correctamente.'])
                : response()->json(['success' => false, 'message' => 'No se pudo guardar la entrega.'], 500);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::submitTarea [{$moduloId}/{$cmid}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al entregar la tarea.'], 500);
        }
    }

    public function uploadTareaArchivo(Request $request, int $moduloId, int $cmid)
    {
        $request->validate([
            'archivo' => 'required|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,jpg,jpeg,png,gif,txt',
        ]);

        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        $cm = $this->getCm($cmid, $mat->moodle_course_id);
        if (!$cm) return $this->err404();

        try {
            $ok = $this->moodle->uploadAssignmentFile(
                $cmid,
                $mat->moodle_course_id,
                $mat->moodle_user_id,
                $request->file('archivo')
            );

            return $ok
                ? response()->json(['success' => true, 'message' => 'Archivo adjuntado correctamente.'])
                : response()->json(['success' => false, 'message' => 'No se pudo adjuntar el archivo. Verifica la configuración de Moodle.'], 500);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::uploadTareaArchivo [{$moduloId}/{$cmid}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al subir el archivo.'], 500);
        }
    }

    public function eliminarTareaArchivo(Request $request, int $moduloId, int $cmid)
    {
        $request->validate(['filename' => 'required|string|max:255']);

        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        $cm = $this->getCm($cmid, $mat->moodle_course_id);
        if (!$cm) return $this->err404();

        try {
            $ok = $this->moodle->deleteAssignmentFile(
                $cmid,
                $mat->moodle_course_id,
                $mat->moodle_user_id,
                $request->input('filename')
            );

            return $ok
                ? response()->json(['success' => true, 'message' => 'Archivo eliminado.'])
                : response()->json(['success' => false, 'message' => 'No se pudo eliminar el archivo.'], 500);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::eliminarTareaArchivo [{$moduloId}/{$cmid}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar el archivo.'], 500);
        }
    }

    public function descargarTareaArchivo(int $moduloId, int $cmid, string $filename)
    {
        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        $cm = $this->getCm($cmid, $mat->moodle_course_id);
        if (!$cm) return $this->err404('Tarea no encontrada.');

        try {
            $file = $this->moodle->getAssignmentFile($cmid, $mat->moodle_course_id, $mat->moodle_user_id, $filename);
            if (!$file) return $this->err404('Archivo no encontrado.');

            return response()->stream(function () use ($file) {
                echo $file['content'];
            }, 200, [
                'Content-Type'        => $file['mimetype'] ?? 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . rawurlencode($file['filename']) . '"',
                'Content-Length'      => $file['filesize'],
            ]);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::descargarTareaArchivo [{$moduloId}/{$cmid}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al descargar el archivo.'], 500);
        }
    }

    // ── FORO ───────────────────────────────────────────────────────────────────

    public function getForoDiscusiones(int $moduloId, int $cmid)
    {
        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        $cm = $this->getCm($cmid, $mat->moodle_course_id);
        if (!$cm) return $this->err404('Foro no encontrado.');

        try {
            $data = $this->moodle->getForumDiscussionsWithCount($cm->instance, $mat->moodle_user_id);
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::getForoDiscusiones [{$moduloId}/{$cmid}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al obtener las discusiones.'], 500);
        }
    }

    public function getDiscusionPosts(int $moduloId, int $cmid, int $discussionId)
    {
        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        $cm = $this->getCm($cmid, $mat->moodle_course_id);
        if (!$cm) return $this->err404('Foro no encontrado.');

        try {
            $db = DB::connection('moodle');

            $discussion = $db->table('forum_discussions')
                ->where('id', $discussionId)
                ->where('forum', $cm->instance)
                ->first();

            if (!$discussion) return $this->err404('Discusión no encontrada.');

            $posts = $this->moodle->getForumDiscussionPostsWithAuthors($discussionId);

            return response()->json([
                'success'    => true,
                'discussion' => ['id' => $discussion->id, 'name' => $discussion->name],
                'posts'      => $posts,
                'my_user_id' => $mat->moodle_user_id,
            ]);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::getDiscusionPosts [{$moduloId}/{$cmid}/{$discussionId}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al obtener los mensajes.'], 500);
        }
    }

    public function createDiscusion(Request $request, int $moduloId, int $cmid)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:1|max:50000',
        ]);

        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        $cm = $this->getCm($cmid, $mat->moodle_course_id);
        if (!$cm) return $this->err404('Foro no encontrado.');

        if ($this->isForumExpired($cm)) {
            return response()->json(['success' => false, 'message' => 'El plazo para participar en este foro ha vencido.'], 403);
        }

        try {
            $discussionId = $this->moodle->createForumDiscussionAsUser(
                $cm->instance,
                $mat->moodle_course_id,
                $mat->moodle_user_id,
                $request->input('subject'),
                $request->input('message')
            );

            return $discussionId
                ? response()->json(['success' => true, 'message' => 'Discusión creada.', 'discussion_id' => $discussionId])
                : response()->json(['success' => false, 'message' => 'No se pudo crear la discusión.'], 500);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::createDiscusion [{$moduloId}/{$cmid}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al crear la discusión.'], 500);
        }
    }

    public function replyDiscusion(Request $request, int $moduloId, int $cmid, int $discussionId)
    {
        $request->validate([
            'message'   => 'required|string|min:1|max:50000',
            'parent_id' => 'required|integer|min:1',
        ]);

        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        $cm = $this->getCm($cmid, $mat->moodle_course_id);
        if (!$cm) return $this->err404('Foro no encontrado.');

        if ($this->isForumExpired($cm)) {
            return response()->json(['success' => false, 'message' => 'El plazo para participar en este foro ha vencido.'], 403);
        }

        try {
            $db = DB::connection('moodle');

            $discussion = $db->table('forum_discussions')
                ->where('id', $discussionId)
                ->where('forum', $cm->instance)
                ->first();

            if (!$discussion) return $this->err404('Discusión no encontrada.');

            $postId = $this->moodle->addForumReplyAsUser(
                $discussionId,
                (int) $request->input('parent_id'),
                $cm->instance,
                $mat->moodle_user_id,
                $discussion->name,
                $request->input('message')
            );

            return $postId
                ? response()->json(['success' => true, 'message' => 'Respuesta publicada.', 'post_id' => $postId])
                : response()->json(['success' => false, 'message' => 'No se pudo publicar la respuesta.'], 500);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::replyDiscusion [{$moduloId}/{$cmid}/{$discussionId}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al publicar la respuesta.'], 500);
        }
    }

    // ── CUESTIONARIO ───────────────────────────────────────────────────────────

    public function getQuiz(int $moduloId, int $cmid)
    {
        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        $cm = $this->getCm($cmid, $mat->moodle_course_id);
        if (!$cm) return $this->err404('Cuestionario no encontrado.');

        try {
            $data     = $this->moodle->getQuizDetailsForStudent($cm->instance, $mat->moodle_user_id);
            $moodleBase = rtrim(config('moodle.url'), '/');

            $db = DB::connection('moodle');
            $quiz = $db->table('quiz')->where('id', $cm->instance)->first(['sumgrades']);

            return response()->json([
                'success'  => true,
                'data'     => $data,
                'quiz_url' => $moodleBase . '/mod/quiz/view.php?id=' . $cmid,
                'sumgrades' => $quiz ? (float) $quiz->sumgrades : 0,
            ]);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::getQuiz [{$moduloId}/{$cmid}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al obtener el cuestionario.'], 500);
        }
    }

    /**
     * Inicia un nuevo intento de cuestionario.
     */
    public function startQuiz(int $moduloId, int $cmid)
    {
        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        $cm = $this->getCm($cmid, $mat->moodle_course_id);
        if (!$cm) return $this->err404('Cuestionario no encontrado.');

        try {
            $attempt = $this->moodle->startQuizAttempt($cm->instance, $mat->moodle_user_id);
            if (!$attempt) {
                return response()->json(['success' => false, 'message' => 'No se pudo iniciar el intento.'], 500);
            }

            return response()->json([
                'success'  => true,
                'attempt'  => $attempt,
                'timestart' => $attempt['timestart'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::startQuiz [{$moduloId}/{$cmid}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al iniciar el cuestionario.'], 500);
        }
    }

    /**
     * Obtiene las preguntas de un intento de cuestionario.
     */
    public function getAttemptQuestions(int $moduloId, int $cmid, int $attemptId)
    {
        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        try {
            $data = $this->moodle->getAttemptData($attemptId, $mat->moodle_user_id);
            if (empty($data['questions'])) {
                return response()->json(['success' => false, 'message' => 'No se pudieron cargar las preguntas.'], 500);
            }

            return response()->json([
                'success'   => true,
                'questions' => $data['questions'],
                'attempt'   => $data['attempt'],
            ]);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::getAttemptQuestions [{$moduloId}/{$cmid}/{$attemptId}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al cargar preguntas.'], 500);
        }
    }

    /**
     * Guarda o envía las respuestas de un intento de cuestionario.
     */
    public function submitQuiz(Request $request, int $moduloId, int $cmid, int $attemptId)
    {
        $request->validate([
            'data'   => 'required|array',
            'finish' => 'boolean',
        ]);

        $mat = $this->getMatricula($moduloId);
        if (!$mat) return $this->err403();

        try {
            $result = $this->moodle->processAttempt(
                $attemptId,
                $mat->moodle_user_id,
                $request->input('data'),
                $request->boolean('finish')
            );

            if ($result === null) {
                return response()->json(['success' => false, 'message' => 'Error al procesar las respuestas.'], 500);
            }

            // Si se finalizó, obtener datos actualizados del intento
            $attemptData = null;
            if ($request->boolean('finish')) {
                $data = $this->moodle->getAttemptData($attemptId, $mat->moodle_user_id);
                $attemptData = $data['questions'];
            }

            return response()->json([
                'success'      => true,
                'state'        => $result['state'],
                'attempt_data' => $attemptData,
            ]);
        } catch (\Exception $e) {
            Log::error("VirtualEstudiante::submitQuiz [{$moduloId}/{$cmid}/{$attemptId}]: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al guardar respuestas.'], 500);
        }
    }
}
