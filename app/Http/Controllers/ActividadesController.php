<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $urls          = $this->moodle->getUrls($courseId);

        // Si el WS no devolvió tareas, leer desde BD directa de Moodle
        if (empty($tareas)) {
            $tareas = $this->moodle->getAssignmentsFromDb($courseId);
        }

        // Poblar description en módulos assign desde BD de Moodle (fallback directo)
        $tareasByInstance = [];
        foreach ($tareas as $t) {
            $tareasByInstance[(int)($t['id'] ?? 0)] = $t;
        }
        foreach ($secciones as $si => &$section) {
            foreach ($section['modules'] ?? [] as $mi => $mod) {
                $modname = $mod['modname'] ?? '';
                $inst = (int)($mod['instance'] ?? 0);
                if ($modname === 'assign' && $inst && isset($tareasByInstance[$inst])) {
                    $intro = $tareasByInstance[$inst]['intro'] ?? '';
                    if (!empty($intro)) {
                        try {
                            $secciones[$si]['modules'][$mi]['description'] = $this->moodle->rewritePluginfileUrlsInText($intro);
                        } catch (\Exception $e) {
                            $secciones[$si]['modules'][$mi]['description'] = $intro;
                        }
                    }
                }
                if ($modname === 'forum') {
                    // Poblar descripción y fechas desde BD de Moodle
                    if ($inst) {
                        $fechas = [];
                        // Consulta directa a la BD de Moodle (más confiable)
                        try {
                            $forumRow = DB::connection('moodle')->table('forum')->where('id', $inst)->first();
                            if ($forumRow) {
                                $intro = $forumRow->intro ?? '';
                                if (!empty($intro)) {
                                    try {
                                        $secciones[$si]['modules'][$mi]['description'] = $this->moodle->rewritePluginfileUrlsInText($intro);
                                    } catch (\Exception $e) {
                                        $secciones[$si]['modules'][$mi]['description'] = $intro;
                                    }
                                }
                                $open = (int)($forumRow->timeopen ?? 0);
                                $close = (int)($forumRow->cutoffdate ?? 0);
                                $timeclose = (int)($forumRow->timeclose ?? 0);
                                if ($open)       $fechas['open'] = $open;
                                if ($close)      $fechas['close'] = $close;
                                if ($timeclose)  $fechas['close'] = $timeclose;
                            }
                        } catch (\Exception $e) {
                            // Silencioso - no romper la petición
                        }
                        if (!empty($fechas)) {
                            if (!isset($secciones[$si]['modules'][$mi]['activity_dates'])) {
                                $secciones[$si]['modules'][$mi]['activity_dates'] = [];
                            }
                            foreach ($fechas as $k => $v) {
                                $secciones[$si]['modules'][$mi]['activity_dates'][$k] = $v;
                            }
                        }
                    }
                }
            }
        }
        unset($section);

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

        // Indexar URLs por cmid para que el JS pueda hacer lookup confiable
        $urlsByCmid = [];
        foreach ($urls as $u) {
            if (!empty($u['cmid'])) {
                $urlsByCmid[(int)$u['cmid']] = $u['externalurl'] ?? '';
            }
        }

        return response()->json([
            'success'          => true,
            'secciones'        => $secciones,
            'tareas'           => $tareas,
            'tareas_fechas'    => $tareasFechas,
            'cuestionarios'    => $cuestionarios,
            'foros'            => $foros,
            'urls_by_cmid'     => $urlsByCmid,
            'moodle_course_id' => $courseId,
            'moodle_url'       => rtrim(config('moodle.url'), '/'),
        ]);
    }

    /**
     * Devuelve discusiones y posts de un foro específico dentro del módulo.
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

        $descripcion = '';
        try {
            $row = DB::connection('moodle')->table('forum')->where('id', $forumId)->first(['intro']);
            if ($row) $descripcion = $this->moodle->rewritePluginfileUrlsInText($row->intro ?? '');
        } catch (\Exception $e) {}

        $discusiones = $this->moodle->getForumDiscussions($forumId);
        $posts = $this->moodle->getForumAllPostsByForum($forumId);

        return response()->json([
            'success'     => true,
            'descripcion' => $descripcion,
            'discusiones' => $discusiones,
            'posts'       => $posts,
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
