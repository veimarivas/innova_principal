<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ActividadesEditorController extends Controller
{
    protected MoodleService $moodle;

    public function __construct(MoodleService $moodle)
    {
        $this->moodle = $moodle;
    }

    /**
     * Obtiene el curso de Moodle asociado al módulo.
     */
    private function getCourseId(Modulo $modulo): ?int
    {
        return $modulo->moodle_course_id;
    }

    /**
     * Valida que el course_id del body coincida con el del módulo.
     */
    private function validateCourseMatch(Modulo $modulo, Request $request): bool
    {
        return $modulo->moodle_course_id === (int)$request->input('course_id');
    }

    /**
     * Crear o renombrar sección.
     */
    public function guardarSeccion(Request $request, int $moduloId)
    {
        $data = $request->validate([
            'section_id' => 'nullable|integer',
            'name'       => 'required|string|max:255',
            'summary'    => 'nullable|string',
            'course_id'  => 'required|integer',
        ]);

        $modulo = Modulo::findOrFail($moduloId);
        if (!$this->validateCourseMatch($modulo, $request)) {
            return response()->json(['success' => false, 'message' => 'El course_id no coincide con el módulo.'], 422);
        }

        $courseId = (int)$data['course_id'];

        try {
            if ($data['section_id']) {
                // Editar existente
                $ok = $this->moodle->editSection($courseId, (int)$data['section_id'], $data['name'], $data['summary'] ?? '');
            } else {
                // Crear nueva
                $sectionId = $this->moodle->createSection($courseId, $data['name'], $data['summary'] ?? '');
                $ok = $sectionId !== null;
            }

            if ($ok) {
                return response()->json(['success' => true, 'message' => 'Sección guardada correctamente.']);
            }
            return response()->json(['success' => false, 'message' => 'Error al guardar la sección en Moodle.'], 500);
        } catch (\Exception $e) {
            Log::error("Error guardarSeccion (moduloId=$moduloId): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error del servidor al guardar la sección.'], 500);
        }
    }

    /**
     * Eliminar sección.
     */
    public function eliminarSeccion(int $moduloId, int $sectionNumber)
    {
        $modulo = Modulo::findOrFail($moduloId);
        $courseId = $this->getCourseId($modulo);

        if (!$courseId) {
            return response()->json(['success' => false, 'message' => 'El módulo no tiene curso Moodle asignado.'], 400);
        }

        try {
            $ok = $this->moodle->deleteSection($courseId, $sectionNumber);
            if ($ok) {
                return response()->json(['success' => true, 'message' => 'Sección eliminada correctamente.']);
            }
            return response()->json(['success' => false, 'message' => 'Error al eliminar la sección en Moodle.'], 500);
        } catch (\Exception $e) {
            Log::error("Error eliminarSeccion (moduloId=$moduloId): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error del servidor al eliminar la sección.'], 500);
        }
    }

    /**
     * Devuelve los datos actuales de una actividad leyendo directo de la BD de Moodle.
     */
    public function datosActividad(int $moduloId, int $cmid)
    {
        try {
            $db = DB::connection('moodle');

            $cm = $db->table('course_modules')->where('id', $cmid)->first();
            if (!$cm) {
                return response()->json(['success' => false, 'message' => 'Actividad no encontrada.'], 404);
            }

            $module = $db->table('modules')->where('id', $cm->module)->value('name');

            if ($module === 'assign') {
                $row = (array) $db->table('assign')->where('id', $cm->instance)->first([
                    'name', 'intro', 'duedate', 'allowsubmissionsfromdate', 'cutoffdate', 'grade',
                ]);
                foreach ($db->table('assign_plugin_config')->where('assignment', $cm->instance)->get() as $cfg) {
                    if ($cfg->plugin === 'onlinetext' && $cfg->subtype === 'assignsubmission' && $cfg->name === 'enabled') $row['onlinetext'] = (int)$cfg->value;
                    if ($cfg->plugin === 'file' && $cfg->subtype === 'assignsubmission' && $cfg->name === 'enabled') $row['filesubmission'] = (int)$cfg->value;
                    if ($cfg->plugin === 'file' && $cfg->subtype === 'assignsubmission' && $cfg->name === 'maxfilesubmissions') $row['maxfiles'] = (int)$cfg->value;
                    if ($cfg->plugin === 'file' && $cfg->subtype === 'assignsubmission' && $cfg->name === 'maxsubmissionsizebytes') $row['maxsize'] = (int)$cfg->value;
                }
                $row['introfile'] = $this->moodle->getAssignIntroAttachment($cmid);
                $data = $row;
            } else {
                $data = match ($module) {
                    'quiz' => (array) $db->table('quiz')->where('id', $cm->instance)->first([
                        'name', 'intro', 'timeopen', 'timeclose', 'timelimit', 'attempts', 'grade',
                    ]),
                    'forum' => call_user_func(function () use ($db, $cmid, $cm) {
                        $row = (array) $db->table('forum')->where('id', $cm->instance)->first([
                            'name', 'intro', 'type', 'forcesubscribe', 'assessed', 'scale', 'duedate', 'cutoffdate'
                        ]);
                        $row['grade'] = !empty($row['assessed']) && ($row['scale'] ?? 0) > 0
                            ? (int) $row['scale']
                            : 0;
                        $row['timeopen'] = 0;
                        $row['timeclose'] = 0;
                        $avail = $db->table('course_modules')
                            ->where('id', $cmid)
                            ->value('availability');
                        if ($avail) {
                            $json = json_decode($avail, true);
                            if ($json && isset($json['c']) && is_array($json['c'])) {
                                foreach ($json['c'] as $cond) {
                                    if (($cond['type'] ?? '') === 'date') {
                                        if (($cond['d'] ?? '') === '>=') {
                                            $row['timeopen'] = (int) ($cond['t'] ?? 0);
                                        } elseif (($cond['d'] ?? '') === '<') {
                                            $row['timeclose'] = (int) ($cond['t'] ?? 0);
                                        }
                                    }
                                }
                            }
                        }
                        return $row;
                    }),
                    default => [],
                };
            }

            return response()->json([
                'success' => true,
                'modname' => $module,
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            Log::error("datosActividad (cmid=$cmid): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al obtener datos.'], 500);
        }
    }

    /**
     * Crear o editar actividad.
     */
    public function guardarActividad(Request $request, int $moduloId)
    {
        $rules = [
            'cmid'        => 'nullable|integer',
            'modname'     => 'required|string|in:assign,quiz,forum,page,url',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'section'     => 'required|integer',
            'course_id'   => 'required|integer',
        ];

        if ($request->input('modname') === 'assign') {
            $rules['duedate']                 = 'nullable|integer';
            $rules['grade']                   = 'nullable|integer|min:0|max:100';
            $rules['allowsubmissionsfromdate'] = 'nullable|integer';
            $rules['cutoffdate']              = 'nullable|integer';
            $rules['onlinetext']              = 'nullable|integer|in:0,1';
            $rules['filesubmission']          = 'nullable|integer|in:0,1';
            $rules['maxfiles']                = 'nullable|integer|min:0|max:100';
            $rules['maxsize']                 = 'nullable|integer|min:0';
            $rules['requirestatement']        = 'nullable|integer|in:0,1';
            $rules['notifyteachers']          = 'nullable|integer|in:0,1';
        }
        if ($request->input('modname') === 'quiz') {
            $rules['timelimit'] = 'nullable|integer|min:0';
            $rules['attempts']  = 'nullable|integer|min:0';
            $rules['grade']     = 'nullable|integer|min:0|max:100';
            $rules['timeopen']  = 'nullable|integer';
            $rules['timeclose'] = 'nullable|integer';
        }
        if ($request->input('modname') === 'forum') {
            $rules['forum_type']   = 'nullable|string|in:single,general,qanda';
            $rules['subscription'] = 'nullable|integer|in:0,1,2';
            $rules['grade']        = 'nullable|integer|min:0|max:100';
            $rules['timeopen']     = 'nullable|integer';
            $rules['timeclose']    = 'nullable|integer';
            $rules['duedate']      = 'nullable|integer';
            $rules['cutoffdate']   = 'nullable|integer';
            
            // Fallback para clientes con caché antigua
            if (!$request->has('duedate') && $request->has('timeopen')) {
                $request->merge(['duedate' => $request->input('timeopen')]);
            }
            if (!$request->has('cutoffdate') && $request->has('timeclose')) {
                $request->merge(['cutoffdate' => $request->input('timeclose')]);
            }
        }
        if ($request->input('modname') === 'page') {
            $rules['content'] = 'nullable|string';
        }
        if ($request->input('modname') === 'url') {
            $rules['externalurl'] = 'nullable|url';
            $rules['display']     = 'nullable|integer|in:0,1,2';
        }

        $data = $request->validate($rules);
        $data['course'] = (int)$request->input('course_id');
        $data['modname'] = $request->input('modname');
        
        \Log::info("guardarActividad request data: ", $data);

        $modulo = Modulo::findOrFail($moduloId);
        if (!$this->validateCourseMatch($modulo, $request)) {
            return response()->json(['success' => false, 'message' => 'El course_id no coincide con el módulo.'], 422);
        }

        $courseId = (int)$data['course_id'];

        try {
            if (!empty($data['cmid'])) {
                // Editar existente vía DB directa (nombre, descripción y fechas)
                $ok = $this->moodle->updateActivityViaDB((int)$data['cmid'], $data['modname'], $data);
                if ($ok) {
                    return response()->json(['success' => true, 'message' => 'Actividad actualizada correctamente.']);
                }
                return response()->json(['success' => false, 'message' => 'Error al actualizar la actividad en Moodle.'], 500);
            } else {
                // Crear nueva
                $cmid = $this->moodle->createActivity($courseId, (int)$data['section'], $data['modname'], $data);
                if ($cmid) {
                    return response()->json(['success' => true, 'data' => ['cmid' => $cmid], 'message' => 'Actividad creada correctamente.']);
                }
                return response()->json(['success' => false, 'message' => 'Error al crear la actividad en Moodle.'], 500);
            }
        } catch (\Exception $e) {
            Log::error("Error guardarActividad (moduloId=$moduloId): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error del servidor al guardar la actividad.'], 500);
        }
    }

    /**
     * Eliminar actividad.
     */
    public function eliminarActividad(int $moduloId, int $cmid)
    {
        $modulo = Modulo::findOrFail($moduloId);
        if (!$modulo->moodle_course_id) {
            return response()->json(['success' => false, 'message' => 'El módulo no tiene curso Moodle asignado.'], 400);
        }

        try {
            $ok = $this->moodle->deleteModule($cmid);
            if ($ok) {
                return response()->json(['success' => true, 'message' => 'Actividad eliminada correctamente.']);
            }
            return response()->json(['success' => false, 'message' => 'Error al eliminar la actividad en Moodle.'], 500);
        } catch (\Exception $e) {
            Log::error("Error eliminarActividad (moduloId=$moduloId): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error del servidor al eliminar la actividad.'], 500);
        }
    }

    /**
     * Reordenar secciones o actividades.
     */
    public function reordenar(Request $request, int $moduloId)
    {
        $data = $request->validate([
            'type'      => 'required|string|in:secciones,actividades',
            'ids'       => 'required|array',
            'ids.*'     => 'integer',
            'sectionId' => 'required_if:type,actividades|integer',
        ]);

        $modulo = Modulo::findOrFail($moduloId);
        $courseId = $this->getCourseId($modulo);

        if (!$courseId) {
            return response()->json(['success' => false, 'message' => 'El módulo no tiene curso Moodle asignado.'], 400);
        }

        try {
            if ($data['type'] === 'secciones') {
                $ok = $this->moodle->reorderSections($courseId, $data['ids']);
            } else {
                $ok = $this->moodle->reorderActivities($courseId, (int)$data['sectionId'], $data['ids']);
            }

            if ($ok) {
                return response()->json(['success' => true, 'message' => 'Orden actualizado correctamente.']);
            }
            return response()->json(['success' => false, 'message' => 'Error al reordenar en Moodle.'], 500);
        } catch (\Exception $e) {
            Log::error("Error reordenar (moduloId=$moduloId): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error del servidor al reordenar.'], 500);
        }
    }

    /**
     * Subir archivo como recurso a Moodle.
     */
    public function subirArchivo(Request $request, int $moduloId)
    {
        $data = $request->validate([
            'file'    => 'required|file|max:51200|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,mp4,jpg,png',
            'section' => 'required|integer',
            'name'    => 'required|string|max:255',
            'course_id' => 'required|integer',
        ]);

        $modulo = Modulo::findOrFail($moduloId);
        if (!$this->validateCourseMatch($modulo, $request)) {
            return response()->json(['success' => false, 'message' => 'El course_id no coincide con el módulo.'], 422);
        }

        $courseId = (int)$data['course_id'];
        $file = $request->file('file');

        try {
            $tempPath = $file->storeAs('temp', uniqid() . '_' . $file->getClientOriginalName());
            $fullPath = Storage::path($tempPath);

            $draftId = $this->moodle->uploadFileToDraftArea($fullPath, $file->getClientOriginalName());
            if (!$draftId) {
                Storage::delete($tempPath);
                return response()->json(['success' => false, 'message' => 'Error al subir el archivo a Moodle.'], 500);
            }

            $cmid = $this->moodle->createResourceFromDraft($courseId, (int)$data['section'], $data['name'], $draftId);
            Storage::delete($tempPath);

            if ($cmid) {
                $moodleUrl = rtrim(config('moodle.url'), '/');
                return response()->json([
                    'success' => true,
                    'data' => [
                        'cmid' => $cmid,
                        'name' => $data['name'],
                        'moodle_url' => $moodleUrl . '/mod/resource/view.php?id=' . $cmid,
                    ],
                    'message' => 'Recurso creado correctamente.',
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Error al crear el recurso en Moodle.'], 500);
        } catch (\Exception $e) {
            if (isset($tempPath)) Storage::delete($tempPath);
            Log::error("Error subirArchivo (moduloId=$moduloId): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error del servidor al subir el archivo.'], 500);
        }
    }

    /**
     * Adjunta un archivo a las instrucciones (intro) de una tarea en Moodle.
     */
    public function subirAdjuntoTarea(Request $request, int $moduloId, int $cmid)
    {
        $request->validate([
            'file' => 'required|file|max:51200|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png',
        ]);

        try {
            $ok = $this->moodle->attachFileToAssignIntro($cmid, $request->file('file'));
            if ($ok) {
                $attachment = $this->moodle->getAssignIntroAttachment($cmid);
                return response()->json(['success' => true, 'introfile' => $attachment, 'message' => 'Archivo adjunto guardado.']);
            }
            return response()->json(['success' => false, 'message' => 'Error al adjuntar el archivo.'], 500);
        } catch (\Exception $e) {
            Log::error("subirAdjuntoTarea (moduloId=$moduloId cmid=$cmid): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error del servidor al adjuntar el archivo.'], 500);
        }
    }
}
