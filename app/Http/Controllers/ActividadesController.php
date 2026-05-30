<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Services\MoodleService;
use Illuminate\Http\Request;

class ActividadesController extends Controller
{
    public function __construct(private MoodleService $moodle) {}

    /**
     * Devuelve secciones, tareas, cuestionarios y foros del curso Moodle del módulo.
     */
    public function getActividades(int $moduloId)
    {
        $modulo = Modulo::findOrFail($moduloId);

        if (!$modulo->moodle_course_id) {
            return response()->json([
                'success' => false,
                'message' => 'El módulo no tiene curso en Moodle.',
            ]);
        }

        $courseId = $modulo->moodle_course_id;

        $secciones     = $this->moodle->getCourseContentsWithDetails($courseId);
        $tareas        = $this->moodle->getAssignments($courseId);
        $cuestionarios = $this->moodle->getQuizzes($courseId);
        $foros         = $this->moodle->getForums($courseId);

        // Fechas desde BD directa (no depende del WS mod_assign_get_assignments)
        $tareasFechas = $this->moodle->getAssignDatesByCourseDirect($courseId);

        // Complementar con datos del WS si la BD no los devolvió
        if (empty($tareasFechas)) {
            foreach ($tareas as $t) {
                $open  = (int) ($t['allowsubmissionsfromdate'] ?? 0);
                $due   = (int) ($t['duedate']                  ?? 0);
                $entry = ['open' => $open ?: null, 'due' => $due ?: null];
                if (!empty($t['id']))           $tareasFechas[(int) $t['id']]                 = $entry;
                if (!empty($t['coursemodule'])) $tareasFechas['cm_' . (int) $t['coursemodule']] = $entry;
            }
        }

        return response()->json([
            'success'        => true,
            'secciones'      => $secciones,
            'tareas'         => $tareas,
            'tareas_fechas'  => $tareasFechas,
            'cuestionarios'  => $cuestionarios,
            'foros'          => $foros,
            'moodle_course_id' => $courseId,
            'moodle_url'     => rtrim(config('moodle.url'), '/'),
        ]);
    }

    /**
     * Devuelve las discusiones de un foro específico dentro del módulo.
     */
    public function getDiscusiones(int $moduloId, int $forumId)
    {
        $modulo = Modulo::findOrFail($moduloId);

        if (!$modulo->moodle_course_id) {
            return response()->json([
                'success' => false,
                'message' => 'El módulo no tiene curso en Moodle.',
            ]);
        }

        $discusiones = $this->moodle->getForumDiscussions($forumId);

        return response()->json([
            'success'     => true,
            'discusiones' => $discusiones,
        ]);
    }

    /**
     * Crea una nueva discusión en un foro de Moodle.
     */
    public function crearDiscusion(Request $request, int $moduloId, int $forumId)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $modulo = Modulo::findOrFail($moduloId);

        if (!$modulo->moodle_course_id) {
            return response()->json([
                'success' => false,
                'message' => 'El módulo no tiene curso en Moodle.',
            ]);
        }

        $discussionId = $this->moodle->addForumDiscussion(
            $forumId,
            $request->subject,
            $request->message
        );

        if (!$discussionId) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear la discusión en Moodle.',
            ]);
        }

        return response()->json([
            'success'       => true,
            'mensaje'       => 'Discusión creada correctamente.',
            'discussion_id' => $discussionId,
        ]);
    }
}
