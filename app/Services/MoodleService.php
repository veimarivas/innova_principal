<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoodleService
{
    private string $url;
    private string $token;

    public function __construct()
    {
        $this->url   = rtrim(config('moodle.url', 'http://localhost/moodle'), '/');
        $this->token = config('moodle.token', '');
        $this->ensureQuizWsFunctions();
    }

    /**
     * Ensure required quiz WS functions are registered in the Laravel Integration service.
     */
    private function ensureQuizWsFunctions(): void
    {
        $quizFunctions = [
            'mod_quiz_start_attempt',
            'mod_quiz_get_attempt_data',
            'mod_quiz_process_attempt',
            'mod_quiz_save_attempt',
        ];

        try {
            $db = DB::connection('moodle');
            $service = $db->table('external_services')
                ->where('name', 'Laravel Integration')
                ->where('enabled', 1)
                ->first(['id']);

            if (!$service) return;

            $existing = $db->table('external_services_functions')
                ->where('externalserviceid', $service->id)
                ->whereIn('functionname', $quizFunctions)
                ->pluck('functionname')
                ->toArray();

            $missing = array_diff($quizFunctions, $existing);

            foreach ($missing as $fn) {
                $db->table('external_services_functions')->insert([
                    'externalserviceid' => $service->id,
                    'functionname'      => $fn,
                ]);
            }

            if (!empty($missing)) {
                Log::info("Moodle WS functions added to Laravel Integration service: " . implode(', ', $missing));
            }
        } catch (\Exception $e) {
            Log::warning("Could not ensure quiz WS functions: " . $e->getMessage());
        }
    }

    /**
     * Crea una categoría en Moodle y devuelve su ID.
     * Retorna null si falla.
     */
    public function createCategory(string $name, int $parentId = 0): ?int
    {
        $response = $this->call('core_course_create_categories', [
            'categories[0][name]'   => $name,
            'categories[0][parent]' => $parentId,
        ]);

        if ($response && isset($response[0]['id'])) {
            return (int) $response[0]['id'];
        }

        return null;
    }

    /**
     * Actualiza el nombre de una categoría en Moodle.
     */
    public function updateCategory(int $moodleCategoryId, string $name): bool
    {
        $response = $this->call('core_course_update_categories', [
            'categories[0][id]'   => $moodleCategoryId,
            'categories[0][name]' => $name,
        ]);

        return $response !== null && empty($response);
    }

    /**
     * Elimina una categoría en Moodle.
     */
    public function deleteCategory(int $moodleCategoryId): bool
    {
        $response = $this->call('core_course_delete_categories', [
            'categories[0][id]' => $moodleCategoryId,
        ]);

        return $response !== null;
    }

    /**
     * Obtiene todas las categorías de Moodle.
     */
    public function getCategories(): array
    {
        return $this->call('core_course_get_categories') ?? [];
    }

    // -------------------------------------------------------------------------
    // CURSOS
    // -------------------------------------------------------------------------

    /**
     * Crea un curso en Moodle dentro de una categoría y devuelve su ID.
     * Si se especifica $templateCourseId, importa la estructura del curso plantilla.
     */
    public function createCourse(
        string $fullname,
        string $shortname,
        int    $categoryId,
        ?string $startDate = null,
        ?string $endDate   = null,
        ?int    $templateCourseId = null
    ): ?int {
        $params = [
            'courses[0][fullname]'   => $fullname,
            'courses[0][shortname]'  => $shortname,
            'courses[0][categoryid]' => $categoryId,
        ];

        if ($startDate) {
            $params['courses[0][startdate]'] = strtotime($startDate);
        }
        if ($endDate) {
            $params['courses[0][enddate]'] = strtotime($endDate);
        }

        $response = $this->call('core_course_create_courses', $params);

        if ($response && isset($response[0]['id'])) {
            $courseId = (int) $response[0]['id'];
            
            if ($templateCourseId) {
                $this->importCourse($templateCourseId, $courseId);
            }
            
            return $courseId;
        }

        return null;
    }

    /**
     * Actualiza los datos de un curso en Moodle.
     */
    public function updateCourse(
        int     $moodleCourseId,
        string  $fullname,
        string  $shortname,
        ?string $startDate = null,
        ?string $endDate   = null
    ): bool {
        $params = [
            'courses[0][id]'        => $moodleCourseId,
            'courses[0][fullname]'  => $fullname,
            'courses[0][shortname]' => $shortname,
        ];

        if ($startDate) {
            $params['courses[0][startdate]'] = strtotime($startDate);
        }
        if ($endDate) {
            $params['courses[0][enddate]'] = strtotime($endDate);
        }

        $response = $this->call('core_course_update_courses', $params);

        return $response !== null && empty($response);
    }

    /**
     * Elimina un curso en Moodle.
     */
    public function deleteCourse(int $moodleCourseId): bool
    {
        $response = $this->call('core_course_delete_courses', [
            'courseids[0]' => $moodleCourseId,
        ]);

        return $response !== null;
    }

    /**
     * Importa la estructura del curso plantilla al curso destino usando core_course_import_course.
     * Copia secciones, actividades y recursos sin duplicar usuarios ni calificaciones.
     */
    public function importCourse(int $fromCourseId, int $toCourseId): bool
    {
        $response = $this->call('core_course_import_course', [
            'importfrom'    => $fromCourseId,
            'importto'      => $toCourseId,
            'deletecontent' => 0,
        ]);

        return $response !== null;
    }

    /**
     * Genera un shortname único para un módulo (máx. 100 chars, requerido por Moodle).
     */
    public function buildCourseShortname(int $ofertaId, int $nModulo): string
    {
        return "OFR{$ofertaId}-MOD{$nModulo}";
    }

    // -------------------------------------------------------------------------
    // USUARIOS
    // -------------------------------------------------------------------------

    /**
     * Busca un usuario en Moodle por un campo específico (username, email, idnumber).
     * Retorna el usuario o null si no existe.
     */
    public function getUserByField(string $field, string $value): ?array
    {
        $response = $this->call('core_user_get_users_by_field', [
            'field'      => $field,
            'values[0]'  => $value,
        ]);

        if ($response && is_array($response) && count($response) > 0) {
            return $response[0];
        }

        return null;
    }

    /**
     * Crea un usuario en Moodle.
     * Retorna el ID del usuario creado o null si falla.
     */
    public function createUser(
        string $username,
        string $password,
        string $firstname,
        string $lastname,
        string $email
    ): ?int {
        $response = $this->call('core_user_create_users', [
            'users[0][username]'    => $username,
            'users[0][password]'    => $password,
            'users[0][firstname]'   => $firstname,
            'users[0][lastname]'    => $lastname,
            'users[0][email]'       => $email,
            'users[0][auth]'        => 'manual',
        ]);

        if ($response && isset($response[0]['id'])) {
            return (int) $response[0]['id'];
        }

        return null;
    }

    /**
     * Actualiza la contraseña de un usuario en Moodle.
     */
    public function updateUserPassword(int $moodleUserId, string $password): bool
    {
        $response = $this->call('core_user_update_users', [
            'users[0][id]'       => $moodleUserId,
            'users[0][password]' => $password,
        ]);
        return $response !== null;
    }

    /**
     * Suspende o reactiva un usuario completo en Moodle (no la matrícula a un curso).
     * $suspend = true  → bloquea login del usuario
     * $suspend = false → reactiva el usuario
     */
    public function suspendUser(int $moodleUserId, bool $suspend): bool
    {
        $response = $this->call('core_user_update_users', [
            'users[0][id]'        => $moodleUserId,
            'users[0][suspended]' => $suspend ? 1 : 0,
        ]);
        return $response !== null;
    }

    /**
     * Verifica si un usuario está matriculado en un curso específico.
     * Usa core_enrol_get_users_courses (por usuario).
     */
    public function isUserEnrolledInCourse(int $moodleUserId, int $moodleCourseId): bool
    {
        $response = $this->call('core_enrol_get_users_courses', [
            'userid' => $moodleUserId,
        ]);

        if (!$response || !is_array($response)) {
            return false;
        }

        foreach ($response as $course) {
            if (isset($course['id']) && (int) $course['id'] === $moodleCourseId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Suspende o reactiva la matrícula de un usuario en un curso de Moodle.
     * $suspend = true  → bloquea el acceso (enrolments[suspend]=1)
     * $suspend = false → reactiva el acceso (enrolments[suspend]=0)
     */
    public function suspendEnrollment(int $moodleUserId, int $moodleCourseId, bool $suspend): array|false
    {
        $user = $this->getUserByField('id', (string) $moodleUserId);
        
        if (!$user) {
            return ['error' => 'user_not_found', 'message' => 'El usuario ya no existe en Moodle. Debe re-matricularse.'];
        }

        $enrollments = $this->getUserEnrollments($moodleUserId, $moodleCourseId);

        $existingEnrollment = null;
        if ($enrollments && is_array($enrollments)) {
            foreach ($enrollments as $enrol) {
                if (($enrol['enrol'] ?? '') === 'manual') {
                    $existingEnrollment = $enrol;
                    break;
                }
            }
        }

        if (!$existingEnrollment) {
            $result = $this->call('enrol_manual_enrol_users', [
                'enrolments[0][roleid]'    => 5,
                'enrolments[0][userid]'    => $moodleUserId,
                'enrolments[0][courseid]'  => $moodleCourseId,
            ]);
            if ($result === null) {
                return ['error' => 'enrollment_failed', 'message' => 'No se pudo matricular al estudiante en el curso.'];
            }
        }

        $result = $this->call('enrol_manual_enrol_users', [
            'enrolments[0][roleid]'    => 5,
            'enrolments[0][userid]'    => $moodleUserId,
            'enrolments[0][courseid]'  => $moodleCourseId,
            'enrolments[0][suspend]'   => $suspend ? 1 : 0,
        ]);

return $result !== null ? [] : false;
    }

    /**
     * Busca un curso por su ID.
     */
    public function getCourseById(int $courseId): ?array
    {
        $response = $this->call('core_course_get_courses_by_field', [
            'field' => 'id',
            'value' => $courseId,
        ]);

        if ($response && isset($response['courses']) && count($response['courses']) > 0) {
            return $response['courses'][0];
        }

        return null;
    }

    // -------------------------------------------------------------------------
    // MATRÍCULAS
    // -------------------------------------------------------------------------

    /**
     * Matricula un usuario en un curso de Moodle con un rol específico.
     * roleId 5 = student, roleId 3 = teacher editor
     * Si Moodle falla solo por no poder enviar email, verifica el enrollment directamente.
     */
    public function enrollUserInCourse(int $userId, int $courseId, int $roleId = 5): bool
    {
        $response = $this->call('enrol_manual_enrol_users', [
            'enrolments[0][roleid]'   => $roleId,
            'enrolments[0][userid]'   => $userId,
            'enrolments[0][courseid]' => $courseId,
        ]);

        if ($response !== null) {
            return true;
        }

        // Moodle puede retornar excepción solo por fallo de email pero el enrollment sí ocurrió.
        return $this->isUserEnrolledInCourse($userId, $courseId);
    }

    /**
     * Desmatricula un usuario de un curso (elimina matrícula manual).
     * Nota: enrol_manual_unenrol_users NO acepta roleid.
     */
    public function unenrollUserFromCourse(int $userId, int $courseId): bool
    {
        $response = $this->call('enrol_manual_unenrol_users', [
            'enrolments[0][userid]'   => $userId,
            'enrolments[0][courseid]' => $courseId,
        ]);
        return $response !== null;
    }

    /**
     * Obtiene las matrículas de un usuario en un curso (para verificar si ya está inscrito).
     */
    public function getUserEnrollments(int $userId, int $courseId): ?array
    {
        $response = $this->call('core_enrol_get_enrolled_users', [
            'courseid' => $courseId,
        ]);
        if ($response && is_array($response)) {
            foreach ($response as $user) {
                if ((int)($user['id'] ?? 0) === $userId) {
                    return [['enrol' => 'manual']];
                }
            }
        }
        return null;
    }

    // -------------------------------------------------------------------------
    // USUARIOS — helpers de username
    // -------------------------------------------------------------------------

    /**
     * Busca un curso en Moodle por su shortname.
     */
    public function getCourseByShortname(string $shortname): ?array
    {
        $response = $this->call('core_course_get_courses_by_field', [
            'field' => 'shortname',
            'value' => $shortname,
        ]);
        if ($response && isset($response['courses'][0])) {
            return $response['courses'][0];
        }
        return null;
    }

    private function normalizarTexto(string $str): string
    {
        return strtr(mb_strtolower($str, 'UTF-8'), [
            'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u',
            'à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u',
            'ä'=>'a','ë'=>'e','ï'=>'i','ö'=>'o','ü'=>'u',
            'â'=>'a','ê'=>'e','î'=>'i','ô'=>'o','û'=>'u',
            'ã'=>'a','ñ'=>'n','õ'=>'o',
        ]);
    }

    /**
     * Construye un username determinista a partir del nombre y carnet del usuario.
     * Moodle requiere: solo letras minúsculas, dígitos, punto, guion, guion bajo; sin espacios.
     */
    public function getOrBuildUsername(string $nombres, string $apPaterno, string $apMaterno, string $carnet): string
    {
        $first = preg_replace('/[^a-z]/', '', $this->normalizarTexto(explode(' ', trim($nombres))[0] ?? ''));
        $pat   = preg_replace('/[^a-z0-9]/', '', $this->normalizarTexto($apPaterno));
        $mat   = preg_replace('/[^a-z0-9]/', '', $this->normalizarTexto($apMaterno));

        // Formato: inicial(primerNombre) + apellidoPaterno + apellidoMaterno (sin puntos)
        $initial = $first ? substr($first, 0, 1) : '';
        $clean   = substr($initial . $pat . $mat, 0, 20);
        $clean   = preg_replace('/[^a-z0-9]/', '', $clean);

        if (!$clean) {
            $clean = 'est' . preg_replace('/[^a-z0-9]/', '', strtolower($carnet));
        }
        if (!$clean || $clean === 'est') {
            $clean = 'usuario' . abs(crc32($nombres . $apPaterno));
        }

        return $clean;
    }

    /**
     * Propone un username único evitando colisiones con la lista reservada.
     */
    public function buildProposedUsername(string $nombres, string $apPaterno, string $apMaterno, array $reserved = []): string
    {
        $base = $this->getOrBuildUsername($nombres, $apPaterno, $apMaterno, '');
        $candidate = $base;
        $i = 2;
        while (in_array($candidate, $reserved)) {
            $candidate = $base . $i++;
        }
        return $candidate;
    }

    /**
     * Busca un usuario existente en Moodle por username derivado del nombre.
     */
    public function findExistingMoodleUser(string $nombres, string $apPaterno, string $apMaterno): ?array
    {
        $username = $this->getOrBuildUsername($nombres, $apPaterno, $apMaterno, '');
        return $this->getUserByField('username', $username);
    }

    // -------------------------------------------------------------------------
    // CONTENIDO DEL CURSO (secciones, tareas, cuestionarios, foros)
    // -------------------------------------------------------------------------

    /**
     * Devuelve las secciones del curso con sus módulos/actividades.
     * Cada sección tiene: id, name, modules[] con modname, name, url, visible.
     */
    public function getCourseContents(int $moodleCourseId): array
    {
        $contents = $this->call('core_course_get_contents', [
            'courseid' => $moodleCourseId,
        ]) ?? [];

        // Normalizar @@PLUGINFILE@@ y pluginfile.php en todos los campos de módulos y secciones
        return $this->normalizePluginfileUrls($contents);
    }

    /**
     * Compatibilidad hacia atrás: delega en normalizePluginfileUrls.
     */
    public function rewritePluginfileUrls(array $contents): array
    {
        return $this->normalizePluginfileUrls($contents);
    }

    /**
     * Convierte @@PLUGINFILE@@ y pluginfile.php a webservice/pluginfile.php?token=
     * en descripciones de secciones y módulos.
     */
    private function normalizePluginfileUrls(array $contents): array
    {
        $moodleUrl = rtrim($this->url, '/');
        $token = $this->token;

        foreach ($contents as &$section) {
            // Normalizar el summary de la sección
            if (!empty($section['summary'])) {
                $section['summary'] = $this->normalizeSingleText($section['summary'], $moodleUrl, $token);
            }
            foreach (($section['modules'] ?? []) as &$module) {
                foreach (['description', 'intro'] as $field) {
                    if (!empty($module[$field])) {
                        $module[$field] = $this->normalizeSingleText($module[$field], $moodleUrl, $token);
                    }
                }
            }
            unset($module);
        }
        unset($section);

        return $contents;
    }

    /**
     * Convierte @@PLUGINFILE@@ y URLs pluginfile.php a webservice/pluginfile.php?token=
     * en un texto HTML.
     */
    private function normalizeSingleText(string $html, string $moodleUrl, string $token): string
    {
        // 1. Convertir @@PLUGINFILE@@ a URL del webservice con token
        $html = preg_replace_callback(
            '/@@PLUGINFILE@@([^"\'<\s]*)/i',
            static function (array $m) use ($moodleUrl, $token): string {
                $path = $m[1];
                $sep  = str_contains($path, '?') ? '&' : '?';
                return $moodleUrl . '/webservice/pluginfile.php' . $path . $sep . 'token=' . $token;
            },
            $html
        ) ?? $html;

        // 2. Convertir pluginfile.php normal a webservice/pluginfile.php con token
        $html = preg_replace_callback(
            '/(https?:\/\/[^\/\s"\'<>]+)\/pluginfile\.php([^"\'<>\s]*)/i',
            static function (array $m) use ($token): string {
                // Evitar doble conversión si ya es webservice/pluginfile.php con token
                if (str_contains($m[0], '/webservice/pluginfile.php') && str_contains($m[0], 'token=')) {
                    return $m[0];
                }
                $base = $m[1];
                $path = $m[2];
                $newPath = str_contains($m[0], '/webservice/pluginfile.php') ? $path : $path;
                $sep = str_contains($path, '?') ? '&' : '?';
                return $base . '/webservice/pluginfile.php' . $path . (str_contains($path, 'token=') ? '' : $sep . 'token=' . $token);
            },
            $html
        ) ?? $html;

        return $html;
    }

    /**
     * Devuelve las tareas (assignments) de un curso.
     */
    public function getAssignments(int $moodleCourseId): array
    {
        $response = $this->call('mod_assign_get_assignments', [
            'courseids[0]' => $moodleCourseId,
        ]);
        return $response['courses'][0]['assignments'] ?? [];
    }

    /**
     * Lee fechas de tareas directamente de la BD de Moodle (sin depender del WS).
     * Devuelve mapa keyed por instance_id y por 'cm_{cmid}'.
     */
    public function getAssignDatesByCourseDirect(int $moodleCourseId): array
    {
        $db = DB::connection('moodle');
        try {
            // 1. Obtener el module_id del tipo 'assign'
            $moduleId = $db->table('modules')->where('name', 'assign')->value('id');
            if (!$moduleId) return [];

            // 2. Obtener course_modules de tipo assign para este curso
            $cmRows = $db->table('course_modules')
                ->where('course', $moodleCourseId)
                ->where('module', $moduleId)
                ->select('id as cmid', 'instance')
                ->get();

            if ($cmRows->isEmpty()) return [];

            // 3. Obtener fechas de la tabla assign
            $instanceIds = $cmRows->pluck('instance')->filter()->unique()->values()->toArray();
            $assignRows  = $db->table('assign')
                ->whereIn('id', $instanceIds)
                ->get(['id', 'allowsubmissionsfromdate', 'duedate']);

            // Índice assign_id → fechas
            $assignIndex = [];
            foreach ($assignRows as $a) {
                $assignIndex[$a->id] = [
                    'open' => $a->allowsubmissionsfromdate ?: null,
                    'due'  => $a->duedate                  ?: null,
                ];
            }

            // 4. Construir mapa final keyed por instance y por cmid
            $map = [];
            foreach ($cmRows as $cm) {
                $entry = $assignIndex[$cm->instance] ?? ['open' => null, 'due' => null];
                $map[(int) $cm->instance]       = $entry;
                $map['cm_' . (int) $cm->cmid]   = $entry;
            }
            Log::debug("getAssignDatesByCourseDirect course={$moodleCourseId} assigns=" . count($assignRows) . " map_keys=" . count($map));
            return $map;

        } catch (\Exception $e) {
            Log::warning("getAssignDatesByCourseDirect ERROR: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lee tareas (assignments) directamente de la BD de Moodle (sin WS).
     */
    public function getAssignmentsFromDb(int $moodleCourseId): array
    {
        try {
            $db = DB::connection('moodle');
            $moduleId = $db->table('modules')->where('name', 'assign')->value('id');
            if (!$moduleId) return [];

            $cms = $db->table('course_modules')
                ->where('course', $moodleCourseId)
                ->where('module', $moduleId)
                ->get(['id as cmid', 'instance']);

            if ($cms->isEmpty()) return [];

            $instanceIds = $cms->pluck('instance')->filter()->unique()->values()->toArray();
            $rows = $db->table('assign')
                ->whereIn('id', $instanceIds)
                ->get();

            $result = [];
            $cmIndex = [];
            foreach ($cms as $cm) {
                $cmIndex[(int)$cm->instance] = (int)$cm->cmid;
            }
            foreach ($rows as $row) {
                $cmid = $cmIndex[(int)$row->id] ?? null;
                $entry = [
                    'id'                     => (int)$row->id,
                    'cmid'                   => $cmid,
                    'coursemodule'           => $cmid,
                    'name'                   => $row->name ?? '',
                    'intro'                  => $row->intro ?? '',
                    'introformat'            => 1,
                    'introfiles'             => [],
                    'duedate'                => (int)$row->duedate,
                    'allowsubmissionsfromdate' => (int)$row->allowsubmissionsfromdate,
                    'cutoffdate'             => (int)$row->cutoffdate,
                    'grade'                  => (int)$row->grade,
                ];
                // Chequear si tiene archivo adjunto en intro
                if ($cmid) {
                    $file = $db->table('files')
                        ->where('component', 'mod_assign')
                        ->where('filearea', 'introattachment')
                        ->where('itemid', 0)
                        ->where('filename', '<>', '.')
                        ->join('context', function ($j) use ($cmid) {
                            $j->on('files.contextid', '=', 'context.id')
                              ->where('context.contextlevel', 70)
                              ->where('context.instanceid', $cmid);
                        })
                        ->first(['files.filename', 'files.filesize', 'files.mimetype']);
                    if ($file) {
                        $entry['introfiles'][] = [
                            'filename' => $file->filename,
                            'filesize' => (int)$file->filesize,
                            'mimetype' => $file->mimetype,
                            'filepath' => '/',
                        ];
                    }
                }
                $result[] = $entry;
            }
            return $result;
        } catch (\Exception $e) {
            Log::warning("getAssignmentsFromDb: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Pobla description en módulos assign de secciones desde BD directa de Moodle.
     */
    public function populateAssignDescriptionsFromDb(array &$secciones, int $moodleCourseId): void
    {
        try {
            $db = DB::connection('moodle');
            $moduleId = $db->table('modules')->where('name', 'assign')->value('id');
            if (!$moduleId) return;

            $cms = $db->table('course_modules')
                ->where('course', $moodleCourseId)
                ->where('module', $moduleId)
                ->get(['id as cmid', 'instance']);

            if ($cms->isEmpty()) return;

            $instanceIds = $cms->pluck('instance')->filter()->unique()->values()->toArray();
            $rows = $db->table('assign')
                ->whereIn('id', $instanceIds)
                ->get(['id', 'intro', 'introformat']);

            $introMap = [];
            foreach ($rows as $row) {
                $introMap[(int)$row->id] = $row->intro ?? '';
            }

            foreach ($secciones as &$section) {
                foreach ($section['modules'] ?? [] as &$mod) {
                    if (($mod['modname'] ?? '') !== 'assign') continue;
                    $instance = $mod['instance'] ?? null;
                    if (!$instance || !isset($introMap[$instance])) continue;
                    $intro = $introMap[$instance];
                    if (!empty($intro)) {
                        $mod['description'] = $this->rewritePluginfileUrlsInText($intro);
                    }
                }
                unset($mod);
            }
            unset($section);
        } catch (\Exception $e) {
            Log::warning("populateAssignDescriptionsFromDb: " . $e->getMessage());
        }
    }

    /**
     * Devuelve los cuestionarios (quizzes) de un curso.
     */
    public function getQuizzes(int $moodleCourseId): array
    {
        $response = $this->call('mod_quiz_get_quizzes_by_courses', [
            'courseids[0]' => $moodleCourseId,
        ]);
        return $response['quizzes'] ?? [];
    }

    /**
     * Devuelve los foros de un curso.
     */
    public function getForums(int $moodleCourseId): array
    {
        return $this->call('mod_forum_get_forums_by_courses', [
            'courseids[0]' => $moodleCourseId,
        ]) ?? [];
    }

    /**
     * Lee foros directamente de la BD de Moodle (sin WS).
     */
    public function getForumsFromDb(int $moodleCourseId): array
    {
        try {
            $db = DB::connection('moodle');
            $moduleId = $db->table('modules')->where('name', 'forum')->value('id');
            if (!$moduleId) return [];

            $cms = $db->table('course_modules')
                ->where('course', $moodleCourseId)
                ->where('module', $moduleId)
                ->get(['id as cmid', 'instance']);

            if ($cms->isEmpty()) return [];

            $instanceIds = $cms->pluck('instance')->filter()->unique()->values()->toArray();
            $rows = $db->table('forum')
                ->whereIn('id', $instanceIds)
                ->get();

            $result = [];
            $cmIndex = [];
            foreach ($cms as $cm) {
                $cmIndex[(int)$cm->instance] = (int)$cm->cmid;
            }
            foreach ($rows as $row) {
                $cmid = $cmIndex[(int)$row->id] ?? null;
                $result[] = [
                    'id'          => (int)$row->id,
                    'cmid'        => $cmid,
                    'coursemodule'=> $cmid,
                    'name'        => $row->name ?? '',
                    'intro'       => $row->intro ?? '',
                    'type'        => $row->type ?? 'general',
                    'duedate'     => (int)$row->duedate,
                    'cutoffdate'  => (int)$row->cutoffdate,
                    'timeopen'    => (int)$row->timeopen,
                    'timeclose'   => (int)$row->timeclose,
                ];
            }
            return $result;
        } catch (\Exception $e) {
            Log::warning("getForumsFromDb: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Devuelve las páginas (page) de un curso con su contenido HTML.
     */
    public function getPages(int $moodleCourseId): array
    {
        $response = $this->call('mod_page_get_pages_by_courses', [
            'courseids[0]' => $moodleCourseId,
        ]);
        $pages = $response['pages'] ?? [];
        
        return array_map(function($page) {
            if (!empty($page['content']['0']['text'])) {
                $page['description'] = $page['content']['0']['text'];
            }
            return $page;
        }, $pages);
    }

    /**
     * Devuelve los recursos (resource) de un curso.
     */
    public function getResources(int $moodleCourseId): array
    {
        $response = $this->call('mod_resource_get_resources_by_courses', [
            'courseids[0]' => $moodleCourseId,
        ]);
        $resources = $response['resources'] ?? [];
        
        return array_map(function($resource) {
            if (!empty($resource['intro'])) {
                $resource['description'] = $resource['intro'];
            }
            return $resource;
        }, $resources);
    }

    /**
     * Devuelve las URLs de un curso.
     */
    public function getUrls(int $moodleCourseId): array
    {
        $db = DB::connection('moodle');

        // Obtener el ID del módulo 'url' en mdl_modules
        $moduleId = $db->table('modules')->where('name', 'url')->value('id');
        if (!$moduleId) return [];

        $rows = $db->table('url as u')
            ->join('course_modules as cm', function ($j) use ($moduleId) {
                $j->on('cm.instance', '=', 'u.id')
                  ->where('cm.module', '=', $moduleId);
            })
            ->where('u.course', $moodleCourseId)
            ->select('u.id', 'u.name', 'u.intro as description', 'u.externalurl', 'u.display', 'cm.id as cmid')
            ->get();

        return $rows->map(fn($r) => (array)$r)->toArray();
    }

    /**
     * Devuelve las etiquetas (label) del curso - el contenido viene en 'intro'.
     * Se extrae de core_course_get_contents.
     */
    public function getLabelsFromContents(array $contents): array
    {
        $labels = [];
        foreach ($contents as $section) {
            foreach ($section['modules'] ?? [] as $mod) {
                if (($mod['modname'] ?? '') === 'label') {
                    $labels[] = [
                        'id'          => $mod['id'] ?? null,
                        'instance'    => $mod['instance'] ?? null,
                        'name'        => $mod['name'] ?? '',
                        'description'=> $mod['intro'] ?? '',
                        'cmid'       => $mod['cmid'] ?? null,
                    ];
                }
            }
        }
        return $labels;
    }

    /**
     * Combina el contenido de las secciones con los detalles de cada actividad.
     * Añade el campo 'description' a cada módulo desde las funciones específicas.
     */
    public function getCourseContentsWithDetails(int $moodleCourseId): array
    {
        $secciones = $this->getCourseContents($moodleCourseId);
        
        $pages      = $this->getPages($moodleCourseId);
        $resources = $this->getResources($moodleCourseId);
        $urls      = $this->getUrls($moodleCourseId);
        $assigns   = $this->getAssignments($moodleCourseId);
        $quizzes   = $this->getQuizzes($moodleCourseId);
        $foros     = $this->getForums($moodleCourseId);
        
        $pagesMap = array_column(array_map(fn($p) => [
            'instance' => $p['id'],
            'description' => $p['description'] ?? '',
            'cmid' => $p['cmid'] ?? null,
        ], $pages), null, 'instance');

        $resourcesMap = array_column(array_map(fn($r) => [
            'instance' => $r['id'],
            'description' => $r['description'] ?? '',
            'cmid' => $r['cmid'] ?? null,
        ], $resources), null, 'instance');

        $urlsMap = array_column(array_map(fn($u) => [
            'instance'    => $u['id'],
            'description' => $u['description'] ?? '',
            'cmid'        => $u['cmid'] ?? null,
            'externalurl' => $u['externalurl'] ?? '',
        ], $urls), null, 'instance');

        $urlsByCmid = array_column(array_map(fn($u) => [
            'cmid'        => $u['cmid'],
            'description' => $u['description'] ?? '',
            'externalurl' => $u['externalurl'] ?? '',
        ], $urls), null, 'cmid');

        $assignsMap = array_column(array_map(fn($a) => [
            'instance'    => $a['id'],
            'description' => $a['intro'] ?? '',
            'cmid'        => $a['cmid'] ?? null,
            'dates'       => [
                'open'  => ($a['allowsubmissionsfromdate'] ?? 0) ?: null,
                'due'   => ($a['duedate'] ?? 0) ?: null,
                'close' => ($a['cutoffdate'] ?? 0) ?: null,
            ],
        ], $assigns), null, 'instance');

        // Leer fechas de assign directamente de la BD (fuente confiable, sin depender del WS)
        $assignDbDates = [];
        try {
            $db = DB::connection('moodle');
            $instanceIds = array_map(fn($m) => $m['instance'] ?? null,
                array_filter(
                    array_merge(...array_map(fn($s) => $s['modules'] ?? [], $secciones)),
                    fn($m) => ($m['modname'] ?? '') === 'assign' && !empty($m['instance'])
                )
            );
            if (!empty($instanceIds)) {
                $rows = $db->table('assign')
                    ->whereIn('id', array_values(array_unique(array_filter($instanceIds))))
                    ->get(['id', 'duedate', 'allowsubmissionsfromdate', 'cutoffdate', 'intro']);
                foreach ($rows as $row) {
                    $assignDbDates[$row->id] = [
                        'due'   => $row->duedate                   ?: null,
                        'open'  => $row->allowsubmissionsfromdate  ?: null,
                        'close' => $row->cutoffdate                ?: null,
                    ];
                    // Fuente principal: BD de Moodle (más confiable que el WS)
                    $assignsMap[$row->id] = [
                        'instance'    => $row->id,
                        'description' => $row->intro ?? ($assignsMap[$row->id]['description'] ?? ''),
                        'cmid'        => $assignsMap[$row->id]['cmid'] ?? null,
                        'dates'       => $assignDbDates[$row->id],
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("getCourseContentsWithDetails: no se pudieron leer fechas de assigns desde BD: " . $e->getMessage());
        }

        $quizzesMap = array_column(array_map(fn($q) => [
            'instance'    => $q['id'],
            'description' => $q['intro'] ?? '',
            'cmid'        => $q['cmid'] ?? null,
            'dates'       => [
                'open'  => ($q['timeopen'] ?? 0) ?: null,
                'close' => ($q['timeclose'] ?? 0) ?: null,
            ],
        ], $quizzes), null, 'instance');

        $forosMap = array_column(array_map(fn($f) => [
            'instance'    => $f['id'],
            'description' => $f['intro'] ?? '',
            'cmid'        => $f['cmid'] ?? null,
            'dates'       => [
                'open'  => ($f['timeopen'] ?? 0) ?: null,
                'due'   => ($f['duedate'] ?? 0) ?: null,
                'close' => ($f['cutoffdate'] ?? 0) ?: null,
            ],
        ], $foros), null, 'instance');

        // Leer fechas de foro desde course_modules.availability (fallback para versiones sin timeopen/timeclose en mdl_forum)
        try {
            $db = DB::connection('moodle');
            $forumCms = array_merge(...array_map(fn($s) => array_filter($s['modules'] ?? [], fn($m) => ($m['modname'] ?? '') === 'forum'), $secciones));
            foreach ($forumCms as $mod) {
                $instance = $mod['instance'] ?? null;
                $cmid = $mod['id'] ?? null;
                if (!$instance || !$cmid || !isset($forosMap[$instance])) continue;
                $avail = $db->table('course_modules')->where('id', $cmid)->value('availability');
                if (!$avail) continue;
                $json = json_decode($avail, true);
                if (!$json || !isset($json['c']) || !is_array($json['c'])) continue;
                foreach ($json['c'] as $cond) {
                    if (($cond['type'] ?? '') === 'date') {
                        if (($cond['d'] ?? '') === '>=') {
                            $forosMap[$instance]['dates']['open'] = (int) ($cond['t'] ?? 0);
                        } elseif (($cond['d'] ?? '') === '<') {
                            $forosMap[$instance]['dates']['close'] = (int) ($cond['t'] ?? 0);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning("getCourseContentsWithDetails: no se pudieron leer fechas de foro desde availability: " . $e->getMessage());
        }

        foreach ($secciones as &$section) {
            // Agregar descripción de la sección (summary)
            $sectionSummary = $section['summary'] ?? '';
            if (!empty($sectionSummary)) {
                // El summary viene en formato HTML, procesarlo para imágenes
                $section['description'] = $this->rewritePluginfileUrlsInText($sectionSummary);
                // También guardar el original sin procesar por si acaso
                $section['summary_raw'] = $sectionSummary;
            }
            
            foreach ($section['modules'] ?? [] as &$mod) {
                $modname = $mod['modname'] ?? '';
                $instance = $mod['instance'] ?? null;
                
                $desc = match ($modname) {
                    'page'     => $pagesMap[$instance]['description'] ?? '',
                    'resource' => $resourcesMap[$instance]['description'] ?? '',
                    'url'      => $urlsMap[$instance]['description'] ?? '',
                    'assign'   => $assignsMap[$instance]['description'] ?? '',
                    'quiz'     => $quizzesMap[$instance]['description'] ?? '',
                    'forum'    => $forosMap[$instance]['description'] ?? '',
                    'label'    => $mod['intro'] ?? '',
                    default    => '',
                };

                if ($desc) {
                    $mod['description'] = $this->rewritePluginfileUrlsInText($desc);
                }

                // Pasar externalurl para módulos URL (busca por instance y por cmid como fallback)
                if ($modname === 'url') {
                    $cmid = $mod['id'] ?? null;
                    $externalurl = $urlsMap[$instance]['externalurl']
                        ?? $urlsByCmid[$cmid]['externalurl']
                        ?? '';
                    if ($externalurl !== '') {
                        $mod['externalurl'] = $externalurl;
                    }
                }

                $actDates = match ($modname) {
                    'assign' => $assignDbDates[$instance] ?? ($assignsMap[$instance]['dates'] ?? null),
                    'quiz'   => $quizzesMap[$instance]['dates'] ?? null,
                    'forum'  => $forosMap[$instance]['dates'] ?? null,
                    default  => null,
                };

                if ($actDates) {
                    $mod['activity_dates'] = $actDates;
                }
            }
            unset($mod);
        }
        unset($section);

        return $secciones;
    }

    /**
     * Descarga las imágenes pluginfile de Moodle y las convierte a data URIs base64.
     * Primero normaliza @@PLUGINFILE@@ y URLs pluginfile.php, luego descarga y embebe.
     */
    public function rewritePluginfileUrlsInText(string $html): string
    {
        if (empty($html)) return $html;

        $moodleUrl = rtrim($this->url, '/');
        $token = $this->token;

        // Paso 1: Normalizar @@PLUGINFILE@@ y pluginfile.php -> webservice/pluginfile.php?token=
        $html = $this->normalizeSingleText($html, $moodleUrl, $token);

        // Paso 2: Descargar imágenes y convertir a base64
        // Regex robusto: captura src="..." o src='...' sin importar el orden de atributos en <img>
        $html = preg_replace_callback(
            '/(<img\b[^>]*?)\bsrc=(["\'])([^"\'<>\s]+)\2([^>]*?>)/i',
            function (array $m) use ($token): string {
                $before = $m[1];
                $quote  = $m[2];
                $imgUrl = $m[3];
                $after  = $m[4];

                // Solo procesar URLs de pluginfile
                if (stripos($imgUrl, 'pluginfile.php') === false) {
                    return $m[0];
                }

                // Asegurar que la URL tiene token
                $fullUrl = $imgUrl;
                if (stripos($fullUrl, 'token=') === false) {
                    $fullUrl .= (str_contains($fullUrl, '?') ? '&' : '?') . 'token=' . $this->token;
                }

                try {
                    $response = Http::timeout(15)->get($fullUrl);
                    if ($response->successful()) {
                        $contentType = $response->header('Content-Type') ?? '';
                        $contentType = trim(explode(';', $contentType)[0]);

                        if (str_starts_with($contentType, 'image/')) {
                            $dataUri = 'data:' . $contentType . ';base64,' . base64_encode($response->body());
                            return $before . 'src=' . $quote . $dataUri . $quote . $after;
                        }

                        // webservice/pluginfile.php devolvio JSON (accessexception: 'Can download files' no habilitado)
                        // Intentar con pluginfile.php normal (acceso servidor-a-servidor en la misma red local)
                        Log::info('MoodleService: webservice/pluginfile.php devolvio ' . $contentType . ', intentando con pluginfile.php directo');
                    }
                } catch (\Exception $e) {
                    Log::warning('MoodleService: error descargando imagen webservice', ['url' => $fullUrl, 'error' => $e->getMessage()]);
                }

                // Segundo intento: pluginfile.php directo (sin /webservice/)
                // Funciona desde el servidor Laravel cuando esta en la misma red que Moodle
                $directUrl = str_ireplace('/webservice/pluginfile.php', '/pluginfile.php', $fullUrl);
                // Quitar el token de la URL directa (no es necesario y puede causar errores)
                $directUrl = preg_replace('/([?&])token=[^&]+(&|$)/', '$1', $directUrl);
                $directUrl = rtrim($directUrl, '?&');

                try {
                    $resp2 = Http::timeout(15)->get($directUrl);
                    if ($resp2->successful()) {
                        $ct2 = trim(explode(';', $resp2->header('Content-Type') ?? '')[0]);
                        if (str_starts_with($ct2, 'image/')) {
                            $dataUri = 'data:' . $ct2 . ';base64,' . base64_encode($resp2->body());
                            return $before . 'src=' . $quote . $dataUri . $quote . $after;
                        }
                    }
                } catch (\Exception $e2) {
                    Log::warning('MoodleService: error descargando imagen directo', ['url' => $directUrl, 'error' => $e2->getMessage()]);
                }

                // Ultimo fallback: dejar URL con token (funciona si Moodle habilita 'Can download files')
                return $before . 'src=' . $quote . $fullUrl . $quote . $after;
            },
            $html
        ) ?? $html;

        return $html;
    }

    /**
     * Devuelve las discusiones de un foro específico.
     */
    public function getForumDiscussions(int $forumId, int $page = 0): array
    {
        $response = $this->call('mod_forum_get_forum_discussions', [
            'forumid' => $forumId,
            'page'    => $page,
            'perpage' => 30,
        ]);
        return $response['discussions'] ?? [];
    }

    /**
     * Devuelve los ítems de calificación del curso con las notas de cada usuario.
     * Lee desde la BD de Moodle (grade_items, grade_grades, course_modules).
     * aggregationcoef se usa como peso (coeficiente 0-1, se convierte a %).
     */
    public function getStudentGrades(int $moodleCourseId, array $moodleUserIds): array
    {
        $itemsMap = [];

        try {
            $db = DB::connection('moodle');

            // 1. Obtener todos los módulos activos del curso (soportando deletioninprogress != 1 o NULL)
            $cms = $db->table('course_modules')
                ->join('modules', 'course_modules.module', '=', 'modules.id')
                ->where('course_modules.course', $moodleCourseId)
                ->whereIn('modules.name', ['assign', 'quiz', 'forum', 'workshop', 'scorm', 'feedback', 'lesson', 'lti'])
                ->where(function($q) {
                    $q->where('course_modules.deletioninprogress', 0)
                      ->orWhere('course_modules.deletioninprogress', '!=', 1)
                      ->orWhereNull('course_modules.deletioninprogress');
                })
                ->get(['course_modules.id as cmid', 'modules.name as modname', 'course_modules.instance']);

            if ($cms->isEmpty()) {
                return [];
            }

            // 2. Obtener todos los ítems de calificación mod de Moodle para este curso
            $gradeItems = $db->table('grade_items')
                ->where('courseid', $moodleCourseId)
                ->where('itemtype', 'mod')
                ->get()
                ->keyBy(function($item) {
                    return $item->itemmodule . '_' . $item->iteminstance;
                });

            // 3. Procesar cada módulo activo
            foreach ($cms as $cm) {
                $key = $cm->modname . '_' . $cm->instance;
                $gi = $gradeItems->get($key);

                // Obtener datos del módulo de la tabla respectiva (nombre y nota max por defecto)
                $name = '';
                $maxGrade = 100.0;
                
                try {
                    $inst = $db->table($cm->modname)->where('id', $cm->instance)->first();
                    if ($inst) {
                        $name = $inst->name ?? '';
                        if ($cm->modname === 'quiz') {
                            $maxGrade = $inst->grade ?? 100.0;
                        } elseif ($cm->modname === 'forum') {
                            $maxGrade = $inst->scale ?? 100.0;
                        } elseif ($cm->modname === 'scorm') {
                            $maxGrade = $inst->maxgrade ?? 100.0;
                        } else {
                            $maxGrade = $inst->grade ?? 100.0;
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("getStudentGrades: Could not read instance from table {$cm->modname}: " . $e->getMessage());
                }

                // Filtrar actividades no calificables (por ejemplo, foros como Avisos con escala/grade <= 0)
                $maxGrade = (float) $maxGrade;
                if ($maxGrade <= 0) {
                    continue;
                }

                $itemId = $gi ? (int) $gi->id : (2000000 + (int) $cm->cmid);
                $weightRaw = $gi ? (float) ($gi->aggregationcoef ?? 0) : 0.0;

                // Si viene del grade_item y tiene nombre, usarlo como prioridad
                if ($gi && !empty($gi->itemname)) {
                    $name = $gi->itemname;
                }

                $itemsMap[$itemId] = [
                    'id'     => $itemId,
                    'name'   => $name,
                    'module' => $cm->modname,
                    'cmid'   => (int) $cm->cmid,
                    'max'    => $gi ? (float) ($gi->grademax ?? $maxGrade) : $maxGrade,
                    'weight' => round($weightRaw * 100, 2),
                    'grades' => [],
                ];

                // Rellenar notas de estudiantes
                if ($gi) {
                    $grades = $db->table('grade_grades')
                        ->where('itemid', $gi->id)
                        ->whereIn('userid', $moodleUserIds)
                        ->get(['userid', 'rawgrade', 'finalgrade']);

                    foreach ($grades as $g) {
                        $uid = (int) $g->userid;
                        $grade = $g->finalgrade !== null ? (float) $g->finalgrade
                            : ($g->rawgrade !== null ? (float) $g->rawgrade : null);
                        $itemsMap[$itemId]['grades'][$uid] = $grade !== null ? round($grade, 2) : null;
                    }
                }

                // Rellenar null para alumnos sin nota
                foreach ($moodleUserIds as $uid) {
                    $uidInt = (int) $uid;
                    if (!array_key_exists($uidInt, $itemsMap[$itemId]['grades'])) {
                        $itemsMap[$itemId]['grades'][$uidInt] = null;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("getStudentGrades DB fallback failed: " . $e->getMessage());
        }

        return array_values($itemsMap);
    }

    /**
     * Crea una nueva discusión en un foro de Moodle.
     * Retorna el discussion ID o null si falla.
     */
    public function addForumDiscussion(int $forumId, string $subject, string $message): ?int
    {
        $response = $this->call('mod_forum_add_discussion', [
            'forumid' => $forumId,
            'subject' => $subject,
            'message' => $message,
        ]);
        if ($response && isset($response['discussionid'])) {
            return (int) $response['discussionid'];
        }
        return null;
    }

    /**
     * Realiza la llamada HTTP a la API REST de Moodle.
     */
    /**
     * Obtiene el token de un usuario específico en Moodle llamando a login/token.php.
     * Retorna ['token' => ..., 'privatetoken' => ...] o null si falla.
     */
    public function getUserToken(string $username, string $password, string $service = 'moodle_mobile_app'): ?array
    {
        try {
            $response = Http::timeout(10)->get("{$this->url}/login/token.php", [
                'username' => $username,
                'password' => $password,
                'service'  => $service,
            ]);
            $data = $response->json();
            if (isset($data['token'])) {
                return [
                    'token'        => $data['token'],
                    'privatetoken' => $data['privatetoken'] ?? '',
                ];
            }
            Log::warning('Moodle getUserToken: no token en respuesta', ['error' => $data['error'] ?? '']);
            return null;
        } catch (\Exception $e) {
            Log::error("Moodle getUserToken failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene una clave de auto-login usando el token del propio usuario.
     * Requiere que tool_mobile esté habilitado en Moodle.
     */
    public function getAutoLoginKey(string $userToken, string $privateToken): ?string
    {
        try {
            $response = Http::timeout(10)->asForm()->post("{$this->url}/webservice/rest/server.php", [
                'wstoken'            => $userToken,
                'wsfunction'         => 'tool_mobile_get_autologin_key',
                'moodlewsrestformat' => 'json',
                'privatetoken'       => $privateToken,
            ]);
            $data = $response->json();
            if (isset($data['exception'])) {
                Log::warning('Moodle getAutoLoginKey exception', [
                    'exception' => $data['exception'] ?? '',
                    'errorcode' => $data['errorcode'] ?? '',
                    'message'   => $data['message'] ?? '',
                ]);
                return null;
            }
            if (!isset($data['key'])) {
                Log::warning('Moodle getAutoLoginKey: respuesta sin clave', ['data' => $data]);
            }
            return $data['key'] ?? null;
        } catch (\Exception $e) {
            Log::error("Moodle getAutoLoginKey failed: " . $e->getMessage());
            return null;
        }
    }

    public function ping(): bool
    {
        try {
            $response = Http::timeout(5)->asForm()->post("{$this->url}/webservice/rest/server.php", [
                'wstoken'            => $this->token,
                'wsfunction'         => 'core_course_get_categories',
                'moodlewsrestformat' => 'json',
            ]);
            if (!$response->successful()) {
                return false;
            }
            $data = $response->json();
            return is_array($data) && !isset($data['exception']);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene o crea un token del servicio mobile para un usuario de Moodle.
     * El servicio mobile (id=1) tiene todas las funciones de quiz necesarias.
     */
    private function getUserQuizToken(int $moodleUserId): ?string
    {
        try {
            $db = DB::connection('moodle');

            // Resolver dinámicamente el ID del servicio Moodle Mobile (shortname 'moodle_mobile_app')
            // En instalaciones distintas el id puede no ser 1.
            $serviceId = $db->table('external_services')
                ->where('shortname', 'moodle_mobile_app')
                ->value('id');

            if (!$serviceId) {
                // Fallback: usar cualquier servicio habilitado y restringido a usuarios
                $serviceId = $db->table('external_services')
                    ->where('enabled', 1)
                    ->orderBy('id')
                    ->value('id');
            }

            if (!$serviceId) {
                Log::error("getUserQuizToken: no se encontró ningún external_service en Moodle");
                return null;
            }

            $existing = $db->table('external_tokens')
                ->where('userid', $moodleUserId)
                ->where('externalserviceid', $serviceId)
                ->where(function ($q) {
                    $q->whereNull('validuntil')->orWhere('validuntil', 0)->orWhere('validuntil', '>', time());
                })
                ->orderBy('id', 'desc')
                ->first(['token']);

            if ($existing) {
                return $existing->token;
            }

            // Si el servicio está restringido a usuarios específicos, asegurar que el usuario esté autorizado
            try {
                $svc = $db->table('external_services')->where('id', $serviceId)->first(['restrictedusers']);
                if ($svc && (int) $svc->restrictedusers === 1) {
                    $exists = $db->table('external_services_users')
                        ->where('externalserviceid', $serviceId)
                        ->where('userid', $moodleUserId)
                        ->exists();
                    if (!$exists) {
                        $db->table('external_services_users')->insert([
                            'externalserviceid' => $serviceId,
                            'userid'            => $moodleUserId,
                            'iprestriction'     => null,
                            'validuntil'        => null,
                            'timecreated'       => time(),
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::warning("getUserQuizToken: no se pudo asegurar autorización en servicio {$serviceId} para user {$moodleUserId}: " . $e->getMessage());
            }

            // Create a new service token for this user
            $newToken = md5(uniqid('quiz_' . $moodleUserId . '_', true));

            $db->table('external_tokens')->insert([
                'token'             => $newToken,
                'tokentype'         => 0,
                'userid'            => $moodleUserId,
                'externalserviceid' => $serviceId,
                'sid'               => null,
                'contextid'         => 1,
                'validuntil'        => 0,
                'iprestriction'     => null,
                'name'              => '',
                'creatorid'         => 2,
                'timecreated'       => time(),
                'lastaccess'        => null,
                'privatetoken'      => null,
            ]);

            return $newToken;
        } catch (\Exception $e) {
            Log::error("getUserQuizToken failed for user {$moodleUserId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Realiza una llamada WS a Moodle USANDO EL TOKEN DEL USUARIO (servicio mobile).
     * Necesario para funciones como mod_quiz_start_attempt que requieren ser el usuario.
     */
    private function callAsUser(string $function, int $userId, array $params = []): mixed
    {
        $token = $this->getUserQuizToken($userId);
        if (!$token) {
            Log::error("callAsUser: no se pudo obtener token para user {$userId}, function {$function}");
            return null;
        }

        try {
            $response = Http::timeout(30)->asForm()->post("{$this->url}/webservice/rest/server.php", array_merge([
                'wstoken'             => $token,
                'wsfunction'          => $function,
                'moodlewsrestformat'  => 'json',
            ], $params));

            $data = $response->json();

            if (isset($data['exception'])) {
                Log::error("Moodle API user error [{$function}]: " . ($data['message'] ?? 'Unknown error') . ' | errorcode: ' . ($data['errorcode'] ?? ''));
                return null;
            }

            return $data ?? [];
        } catch (\Exception $e) {
            Log::error("Moodle conexión fallida [{$function}]: " . $e->getMessage());
            return null;
        }
    }

    private function call(string $function, array $params = []): mixed
    {
        try {
            $response = Http::timeout(10)->asForm()->post("{$this->url}/webservice/rest/server.php", array_merge([
                'wstoken'             => $this->token,
                'wsfunction'          => $function,
                'moodlewsrestformat'  => 'json',
            ], $params));

            $data = $response->json();

            if (isset($data['exception'])) {
                // Moodle lanza excepción cuando falla el envío de email de notificación,
                // pero la operación (enrolamiento/suspensión) ya se completó correctamente.
                if (str_contains($data['errorcode'] ?? '', 'Message was not sent') ||
                    str_contains($data['message'] ?? '', 'Message was not sent')) {
                    Log::debug("Moodle email notify falló (operación exitosa) [{$function}]: " . ($data['message'] ?? ''));
                    return [];
                }
                Log::error("Moodle API error [{$function}]: " . ($data['message'] ?? 'Unknown error') . ' | errorcode: ' . ($data['errorcode'] ?? ''));
                return null;
            }

            // enrol_manual_enrol_users y otros endpoints devuelven body vacío en éxito → [] en vez de null
            return $data ?? [];
        } catch (\Exception $e) {
            Log::error("Moodle conexión fallida [{$function}]: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Elimina un módulo/actividad del curso usando la API de Moodle.
     * @param int $cmid Course module ID (cmid)
     * @return bool true si exitos, false otherwise
     */
    public function deleteModule(int $cmid): bool
    {
        $response = $this->call('core_course_delete_module', [
            'cmid' => $cmid,
        ]);

        return $response !== null && empty($response);
    }

    // ── GESTIÓN DE ACTIVIDADES — CALIFICACIONES, FOROS, QUIZZES ──

    /**
     * Obtiene las entregas de una tarea específica.
     * @param int $cmid Course module ID
     * @return array Listado de submissions
     */
    public function getAssignSubmissions(int $cmid): array
    {
        // Resolver cmid → instance ID (assignmentids requiere el instance, no el cmid)
        try {
            $db = DB::connection('moodle');
            $cm = $db->table('course_modules')->where('id', $cmid)->first();
            $instanceId = $cm ? (int)$cm->instance : $cmid;
        } catch (\Exception $e) {
            $instanceId = $cmid;
        }

        $response = $this->call('mod_assign_get_submissions', [
            'assignmentids[0]' => $instanceId,
        ]);
        return $response['submissions'] ?? [];
    }

    /**
     * Obtiene las entregas (submissions) de una tarea desde la BD de Moodle,
     * incluyendo archivos adjuntos e indicador de entrega tardía.
     * @param int $cmid Course module ID
     * @return array Mapa userId => ['status','timemodified','late','files'=>[]]
     */
    public function getAssignSubmissionsFromDb(int $cmid): array
    {
        $result = [];
        try {
            $db = DB::connection('moodle');

            $cm = $db->table('course_modules')->where('id', $cmid)->first(['instance', 'course']);
            if (!$cm) return [];

            $instanceId = (int) $cm->instance;
            $courseId   = (int) $cm->course;

            // Obtener duedate de la tarea
            $assign = $db->table('assign')->where('id', $instanceId)->first(['duedate', 'cutoffdate']);
            $duedate = $assign ? (int) $assign->duedate : 0;

            // Submissions (latest = 1)
            $submissions = $db->table('assign_submission')
                ->where('assignment', $instanceId)
                ->where('latest', 1)
                ->get(['id', 'userid', 'status', 'timemodified']);

            if ($submissions->isEmpty()) return [];

            // Contexto para archivos
            $context = $db->table('context')
                ->where('contextlevel', 70)
                ->where('instanceid', $cmid)
                ->first(['id']);
            $contextId = $context ? (int) $context->id : null;

            $subIds = $submissions->pluck('id')->toArray();

            // Archivos por submission
            $filesBySubmission = [];
            if ($contextId) {
                $fileRows = $db->table('files')
                    ->where('contextid', $contextId)
                    ->where('component', 'assignsubmission_file')
                    ->where('filearea', 'submission_files')
                    ->whereIn('itemid', $subIds)
                    ->where('filename', '<>', '.')
                    ->get(['itemid', 'filename', 'filesize', 'filepath']);
                foreach ($fileRows as $f) {
                    $sid = (int) $f->itemid;
                    if (!isset($filesBySubmission[$sid])) $filesBySubmission[$sid] = [];
                    $filesBySubmission[$sid][] = [
                        'filename' => $f->filename,
                        'filesize' => (int) $f->filesize,
                        'filepath' => $f->filepath,
                    ];
                }
            }

            foreach ($submissions as $sub) {
                $uid = (int) $sub->userid;
                $timemodified = (int) $sub->timemodified;
                $isLate = $duedate > 0 && $timemodified > $duedate;

                $result[$uid] = [
                    'status'       => $sub->status, // 'submitted' or 'draft'
                    'timemodified' => $timemodified,
                    'late'         => $isLate,
                    'files'        => $filesBySubmission[(int) $sub->id] ?? [],
                ];
            }
        } catch (\Exception $e) {
            Log::error("getAssignSubmissionsFromDb failed: " . $e->getMessage());
        }
        return $result;
    }

    /**
     * Obtiene las calificaciones de una tarea desde la BD de Moodle.
     * @param int $cmid Course module ID de la tarea
     * @param array $moodleUserIds Lista de IDs de usuario en Moodle
     * @return array Mapa userId => ['grade' => float|null, 'feedback' => string]
     */
    public function getAssignGradesByUsers(int $cmid, int $courseId, array $moodleUserIds): array
    {
        $gradesByUser = [];

        try {
            $db = DB::connection('moodle');

            // Resolver instance ID desde course_modules
            $cm = $db->table('course_modules')->where('id', $cmid)->first(['instance']);
            if (!$cm) return [];

            $instanceId = (int) $cm->instance;

            // 1. Leer calificaciones directo de la tabla assign_grades
            $agRows = $db->table('assign_grades')
                ->where('assignment', $instanceId)
                ->whereIn('userid', $moodleUserIds)
                ->get(['id', 'userid', 'grade', 'grader']);

            foreach ($agRows as $row) {
                if ($row->grade === null) continue;
                $uid = (int) $row->userid;
                $gradesByUser[$uid] = [
                    'grade'    => (float) $row->grade,
                    'feedback' => '',
                    '_grade_id' => (int) $row->id,
                ];
            }

            // 2. Leer feedback de assignfeedback_comments
            $gradeIds = array_column($gradesByUser, '_grade_id');
            if (!empty($gradeIds)) {
                $fbRows = $db->table('assignfeedback_comments')
                    ->where('assignment', $instanceId)
                    ->whereIn('gradeid', $gradeIds)
                    ->get(['gradeid', 'commenttext']);
                foreach ($fbRows as $fb) {
                    foreach ($gradesByUser as $uid => &$g) {
                        if (($g['_grade_id'] ?? null) === (int) $fb->gradeid) {
                            $g['feedback'] = $fb->commenttext ?? '';
                            break;
                        }
                    }
                    unset($g);
                }
            }

            // 3. Fallback: grade_grades para usuarios sin calificación en assign_grades
            foreach ($moodleUserIds as $uid) {
                if (isset($gradesByUser[$uid])) continue;
                $gi = $db->table('grade_items')
                    ->where('courseid', $courseId)
                    ->where('itemmodule', 'assign')
                    ->where('iteminstance', $instanceId)
                    ->first(['id']);
                if ($gi) {
                    $gg = $db->table('grade_grades')
                        ->where('itemid', $gi->id)
                        ->where('userid', $uid)
                        ->first(['rawgrade']);
                    if ($gg && $gg->rawgrade !== null) {
                        $gradesByUser[$uid] = [
                            'grade'    => (float) $gg->rawgrade,
                            'feedback' => '',
                        ];
                    }
                }
            }

            // Limpiar campo interno
            foreach ($gradesByUser as $uid => &$g) {
                unset($g['_grade_id']);
            }
            unset($g);
        } catch (\Exception $e) {
            Log::error("getAssignGradesByUsers DB fallback failed: " . $e->getMessage());
        }

        return $gradesByUser;
    }

    /**
     * Guarda calificación y feedback en una tarea.
     * @param int $cmid Course module ID
     * @param int $userId ID del usuario en Moodle
     * @param float $grade Nota 0-100
     * @param string $feedback Texto HTML con tags básicos permitidos
     * @return bool
     */
    public function saveGrade(int $cmid, int $userId, float $grade, string $feedback): bool
    {
        // Resolver assignment instance ID desde cmid
        $assignmentId = $cmid;
        try {
            $db = DB::connection('moodle');
            $cm = $db->table('course_modules')->where('id', $cmid)->first(['instance']);
            if ($cm) $assignmentId = (int) $cm->instance;
        } catch (\Exception $e) {}

        $response = $this->call('mod_assign_save_grade', [
            'assignmentid'                   => $assignmentId,
            'userid'                         => $userId,
            'grade'                          => $grade,
            'attemptnumber'                  => -1,
            'addattempt'                     => 0,
            'workflowstate'                  => 'graded',
            'applytoall'                     => 1,
            'plugindata[assignfeedbackcomments_editor][text]'   => $feedback,
            'plugindata[assignfeedbackcomments_editor][format]' => 1,
        ]);
        return $response !== null;
    }

    /**
     * Obtiene los posts de una discusión de foro.
     * @param int $discussionId ID de la discusión
     * @return array Listado de posts
     */
    public function getDiscussionPosts(int $discussionId): array
    {
        try {
            $db = DB::connection('moodle');

            $rows = $db->table('forum_posts')
                ->where('discussion', $discussionId)
                ->orderBy('created')
                ->get(['id', 'discussion', 'parent', 'userid', 'subject', 'message', 'created', 'modified']);

            if ($rows->isEmpty()) return [];

            // Obtener nombres de usuarios
            $userIds = $rows->pluck('userid')->filter()->unique()->toArray();
            $users = [];
            if (!empty($userIds)) {
                $userRows = $db->table('user')
                    ->whereIn('id', $userIds)
                    ->get(['id', 'firstname', 'lastname']);
                foreach ($userRows as $u) {
                    $users[(int) $u->id] = trim($u->firstname . ' ' . $u->lastname);
                }
            }

            $posts = [];
            foreach ($rows as $row) {
                $posts[] = [
                    'id'           => (int) $row->id,
                    'discussionid' => (int) $row->discussion,
                    'parentid'     => (int) $row->parent,
                    'userid'       => (int) $row->userid,
                    'userfullname' => $users[(int) $row->userid] ?? 'Usuario #' . $row->userid,
                    'subject'      => $row->subject ?? '',
                    'message'      => $row->message ?? '',
                    'created'      => (int) $row->created,
                    'modified'     => (int) $row->modified,
                ];
            }
            return $posts;
        } catch (\Exception $e) {
            Log::error("getDiscussionPosts DB failed: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Agrega una respuesta a una discusión de foro.
     * @param int $discussionId ID de la discusión
     * @param string $subject Asunto
     * @param string $message Mensaje HTML
     * @return int|null Post ID o null si falla
     */
    public function addDiscussionPost(int $discussionId, string $subject, string $message): ?int
    {
        $response = $this->call('mod_forum_add_discussion_post', [
            'discussionid' => $discussionId,
            'subject'      => $subject,
            'message'      => $message,
            'options[0][name]'  => 'discussionsubscribe',
            'options[0][value]' => 0,
        ]);
        if ($response && isset($response['postid'])) {
            return (int)$response['postid'];
        }
        return null;
    }

    /**
     * Obtiene las calificaciones de foro y conteo de posts para estudiantes.
     * Lee desde la BD de Moodle (grade_items, grade_grades, forum_posts).
     * @param int $cmid Course module ID
     * @param int $forumId Forum instance ID
     * @param int $courseId Moodle course ID
     * @param array $moodleUserIds IDs de estudiantes
     * @return array Mapa userId => ['grade' => float|null, 'post_count' => int]
     */
    public function getForumGradesWithParticipation(int $cmid, int $forumId, int $courseId, array $moodleUserIds): array
    {
        $result = [];
        try {
            $db = DB::connection('moodle');

            // Contar posts por usuario en este foro
            $postCounts = $db->table('forum_posts')
                ->join('forum_discussions', 'forum_discussions.id', '=', 'forum_posts.discussion')
                ->where('forum_discussions.forum', $forumId)
                ->whereIn('forum_posts.userid', $moodleUserIds)
                ->groupBy('forum_posts.userid')
                ->select('forum_posts.userid', DB::raw('COUNT(*) as cnt'))
                ->pluck('cnt', 'userid');

            // Buscar grade_item para este foro
            $gi = $db->table('grade_items')
                ->where('courseid', $courseId)
                ->where('itemmodule', 'forum')
                ->where('iteminstance', $forumId)
                ->first(['id', 'grademax']);
            $gradeItemId = $gi ? (int) $gi->id : null;
            $gradeMax = $gi ? (float) $gi->grademax : 100;

            $grades = [];
            if ($gradeItemId) {
                $gradeRows = $db->table('grade_grades')
                    ->where('itemid', $gradeItemId)
                    ->whereIn('userid', $moodleUserIds)
                    ->get(['userid', 'finalgrade', 'rawgrade']);
                foreach ($gradeRows as $g) {
                    $uid = (int) $g->userid;
                    $grades[$uid] = $g->finalgrade !== null ? (float) $g->finalgrade
                        : ($g->rawgrade !== null ? (float) $g->rawgrade : null);
                }
            }

            foreach ($moodleUserIds as $uid) {
                $result[$uid] = [
                    'grade'      => $grades[$uid] ?? null,
                    'grade_max'  => $gradeMax,
                    'post_count' => (int) ($postCounts[$uid] ?? 0),
                ];
            }
        } catch (\Exception $e) {
            Log::error("getForumGradesWithParticipation failed: " . $e->getMessage());
        }
        return $result;
    }

    /**
     * Guarda una calificación de foro directamente en grade_grades de Moodle.
     */
    public function saveForumGradeDb(int $cmid, int $forumId, int $courseId, int $userId, float $grade): bool
    {
        try {
            $db = DB::connection('moodle');

            $gi = $db->table('grade_items')
                ->where('courseid', $courseId)
                ->where('itemmodule', 'forum')
                ->where('iteminstance', $forumId)
                ->first(['id', 'grademax']);
            if (!$gi) return false;

            $now = time();
            $existing = $db->table('grade_grades')
                ->where('itemid', $gi->id)
                ->where('userid', $userId)
                ->first(['id']);

            if ($existing) {
                $db->table('grade_grades')
                    ->where('id', $existing->id)
                    ->update([
                        'rawgrade'    => $grade,
                        'finalgrade'  => $grade,
                        'timemodified' => $now,
                    ]);
            } else {
                $db->table('grade_grades')->insert([
                    'itemid'      => $gi->id,
                    'userid'      => $userId,
                    'rawgrade'    => $grade,
                    'finalgrade'  => $grade,
                    'rawgrademax' => $gi->grademax,
                    'rawgrademin' => 0,
                    'timemodified'=> $now,
                    'timecreated' => $now,
                ]);
            }
            return true;
        } catch (\Exception $e) {
            Log::error("saveForumGradeDb failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene los intentos de un quiz.
     * @param int $quizId Instance ID del quiz
     * @return array Listado de intentos
     */
    public function getQuizAttempts(int $quizId): array
    {
        try {
            $db = DB::connection('moodle');

            // Obtener nota máxima del quiz
            $quiz = $db->table('quiz')->where('id', $quizId)->first(['grade', 'sumgrades']);
            $gradeMax = $quiz ? (float) $quiz->grade : 100;
            $sumgradesMax = $quiz ? (float) $quiz->sumgrades : 100;

            $rows = $db->table('quiz_attempts')
                ->where('quiz', $quizId)
                ->orderBy('userid')
                ->orderBy('attempt')
                ->get(['id', 'quiz', 'userid', 'attempt', 'state', 'timestart', 'timefinish', 'sumgrades']);

            $attempts = [];
            foreach ($rows as $row) {
                $raw = $row->sumgrades !== null ? (float) $row->sumgrades : null;
                // Escalar nota al rango 0-gradeMax
                $grade = $raw !== null && $sumgradesMax > 0 ? round($raw * $gradeMax / $sumgradesMax, 2) : null;
                $attempts[] = [
                    'id'         => (int) $row->id,
                    'quiz'       => (int) $row->quiz,
                    'userid'     => (int) $row->userid,
                    'attempt'    => (int) $row->attempt,
                    'state'      => $row->state,
                    'timestart'  => (int) $row->timestart,
                    'timefinish' => (int) $row->timefinish,
                    'sumgrades'  => $raw,
                    'grade'      => $grade,
                    'grade_max'  => $gradeMax,
                ];
            }
            return $attempts;
        } catch (\Exception $e) {
            Log::error("getQuizAttempts DB failed: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene los datos detallados de un intento de quiz desde la BD de Moodle.
     * @param int $attemptId ID del intento
     * @return array Preguntas con respuestas
     */
    public function getAttemptData(int $attemptId, ?int $userId = null): array
    {
        $questions = [];
        $attemptInfo = null;
        $usageId = 0;
        try {
            $db = DB::connection('moodle');

            $attempt = $db->table('quiz_attempts')->where('id', $attemptId)->first([
                'id', 'uniqueid', 'quiz', 'userid', 'attempt', 'state',
                'timestart', 'timefinish', 'timemodified', 'timecheckstate', 'sumgrades',
            ]);
            if (!$attempt) return ['questions' => [], 'attempt' => null];

            // Obtener timelimit del quiz para conocer cuándo termina el intento
            $quizRow = $db->table('quiz')->where('id', $attempt->quiz)->first(['timelimit', 'timeclose']);
            $timelimit = (int) ($quizRow->timelimit ?? 0);
            $timeclose = (int) ($quizRow->timeclose ?? 0);
            $timestart = (int) ($attempt->timestart ?? 0);
            $timeFinishExpected = 0;
            if ($timestart > 0 && $timelimit > 0) {
                $timeFinishExpected = $timestart + $timelimit;
                if ($timeclose > 0 && $timeclose < $timeFinishExpected) {
                    $timeFinishExpected = $timeclose;
                }
            } elseif ($timestart > 0 && $timeclose > 0) {
                $timeFinishExpected = $timeclose;
            }

            $attemptInfo = [
                'id'                   => (int) $attempt->id,
                'quiz'                 => (int) $attempt->quiz,
                'userid'               => (int) $attempt->userid,
                'attempt'              => (int) $attempt->attempt,
                'state'                => $attempt->state,
                'timestart'            => $timestart,
                'timefinish'           => (int) $attempt->timefinish,
                'timemodified'         => (int) $attempt->timemodified,
                'timelimit'            => $timelimit,
                'time_finish_expected' => $timeFinishExpected,
            ];

            $usageId = (int) $attempt->uniqueid;

            // 1) Intento principal: usar WS de Moodle (renderiza el HTML interactivo de cada pregunta).
            //    Esto provee las opciones (radio buttons, textarea, etc.) que el estudiante puede responder.
            $effectiveUserId = $userId ?: (int) $attempt->userid;
            $wsFn = $attempt->state === 'finished' ? 'mod_quiz_get_attempt_review' : 'mod_quiz_get_attempt_data';

            // 1a) Intentar con token del estudiante
            if ($effectiveUserId > 0) {
                $resp = $this->callWsGetAttemptQuestions($wsFn, $effectiveUserId, $attemptId);
                if (!empty($resp)) {
                    $questions = $resp;
                    return ['questions' => $questions, 'attempt' => $attemptInfo];
                }
                Log::warning("getAttemptData: WS {$wsFn} con token de usuario no devolvió preguntas para attempt={$attemptId}, reintentando con token admin…");
            }

            // 1b) Fallback con token de administrador (por si el WS del estudiante falla por permisos)
            $resp = $this->callWsGetAttemptQuestions($wsFn, null, $attemptId);
            if (!empty($resp)) {
                $questions = $resp;
                return ['questions' => $questions, 'attempt' => $attemptInfo];
            }
            Log::warning("getAttemptData: WS {$wsFn} con token admin tampoco devolvió preguntas para attempt={$attemptId}, fallback a BD.");

            // Obtener question_attempts para este usage (ya incluye questionid y slot)
            $qAttempts = $db->table('question_attempts')
                ->where('questionusageid', $usageId)
                ->orderBy('slot')
                ->get(['id', 'slot', 'questionid', 'maxmark', 'behaviour', 'responsesummary', 'rightanswer', 'variant']);

            // Obtener preguntas (texto y tipo)
            $qIds = $qAttempts->pluck('questionid')->filter()->unique()->toArray();
            $questionsData = [];
            if (!empty($qIds)) {
                $questionsData = $db->table('question')
                    ->whereIn('id', $qIds)
                    ->get(['id', 'qtype', 'questiontext', 'name'])
                    ->keyBy('id');
            }

            // Obtener step_data names/values para reconstruir form field names
            $qaIds = $qAttempts->pluck('id')->toArray();
            $stepDataMap = [];
            if (!empty($qaIds)) {
                $latestStepIds = $db->table('question_attempt_steps')
                    ->selectRaw('MAX(id) as id, questionattemptid')
                    ->whereIn('questionattemptid', $qaIds)
                    ->groupBy('questionattemptid')
                    ->pluck('id', 'questionattemptid');
                if (!empty($latestStepIds)) {
                    $allStepData = $db->table('question_attempt_step_data')
                        ->whereIn('attemptstepid', $latestStepIds->values()->toArray())
                        ->get(['attemptstepid', 'name', 'value']);
                    foreach ($allStepData as $sd) {
                        $qaid = $latestStepIds->search($sd->attemptstepid);
                        if ($qaid !== false) {
                            $stepDataMap[$qaid][$sd->name] = $sd->value;
                        }
                    }
                }
            }

            foreach ($qAttempts as $qa) {
                $qData = $questionsData->get($qa->questionid);

                // Extraer fraction del último step y sequencecheck (num_steps = dernière sequencenumber + 1)
                $fraction = null;
                $lastStep = $db->table('question_attempt_steps')
                    ->where('questionattemptid', $qa->id)
                    ->orderByDesc('sequencenumber')
                    ->first(['state', 'fraction', 'sequencenumber']);
                $sequencecheck = $lastStep ? (int) $lastStep->sequencenumber + 1 : 1;
                if ($lastStep) {
                    $fraction = $lastStep->fraction !== null ? (float) $lastStep->fraction : null;
                    $state = $lastStep->state;
                    if ($state === 'gradedright') $fraction = 1.0;
                    else if ($state === 'gradedwrong') $fraction = 0.0;
                    else if ($state === 'gradedpartial' && $fraction === null) $fraction = 0.0;
                }

                // Extraer texto de pregunta
                $questionText = '';
                $qtype = $qData->qtype ?? 'unknown';
                if ($qData && !empty($qData->questiontext)) {
                    $qtxt = json_decode($qData->questiontext, true);
                    if (is_array($qtxt) && isset($qtxt[0]['text'])) {
                        $questionText = $qtxt[0]['text'];
                    } elseif (is_string($qtxt)) {
                        $questionText = $qtxt;
                    } else {
                        $questionText = $qData->questiontext;
                    }
                }

                // Construir HTML interactivo desde BD para types comunes
                // Form field naming compatible con Moodle: q{slot}:{seq}_{variant}_
                $variant = (int) ($qa->variant ?? 0);
                $basePrefix = 'q' . $qa->slot . ':' . $sequencecheck . ($variant > 1 ? '_' . $variant : '') . '_';
                $localStepData = $stepDataMap[$qa->id] ?? [];
                $localStepData['_sequencecheck'] = (string) $sequencecheck; // inyectar sequencecheck real
                $qHtml = $this->buildDbQuestionHtml($qtype, $qa->id, $qa->questionid, $questionText, $basePrefix, $db, $localStepData);

                $questions[] = [
                    'slot'           => (int) $qa->slot,
                    'questionnumber' => (int) $qa->slot,
                    'questionname'   => $qData->name ?? ('Pregunta ' . $qa->slot),
                    'questiontext'   => $questionText,
                    'html'           => $qHtml,
                    'sequencecheck'  => $sequencecheck,
                    'maxmark'        => (float) ($qa->maxmark ?? 0),
                    'fraction'       => $fraction,
                    'response'       => $qa->responsesummary ?? '',
                    'rightanswer'    => $qa->rightanswer ?? '',
                ];
            }
        } catch (\Exception $e) {
            Log::error("getAttemptData DB failed: " . $e->getMessage());
        }
        return ['questions' => $questions, 'attempt' => $attemptInfo];
    }

    /**
     * Construye HTML interactivo para una pregunta desde la BD de Moodle.
     * Se usa como fallback cuando el WS no devuelve preguntas.
     */
    private function buildDbQuestionHtml(
        string $qtype,
        int $questionAttemptId,
        int $questionId,
        string $questionText,
        string $fieldPrefix,
        $db,
        array $stepData
    ): string {
        $html = '<div class="qtext">' . $questionText . '</div>';
        $html .= '<div class="ablock"><div class="answer">';

        try {
            // Helper para extraer texto de columna que puede ser JSON (Moodle multi-format) o texto plano
            $extractText = function($val): string {
                if (empty($val)) return '';
                $decoded = json_decode($val, true);
                if (is_array($decoded) && isset($decoded[0]['text'])) {
                    return $decoded[0]['text'];
                }
                if (is_string($decoded)) return $decoded;
                return (string) $val;
            };

            switch ($qtype) {
                case 'multichoice':
                    $mcOpts = $db->table('qtype_multichoice_options')
                        ->where('questionid', $questionId)->first(['single']);
                    $single = $mcOpts && (int) $mcOpts->single === 1;
                    $answers = $db->table('question_answers')
                        ->where('question', $questionId)
                        ->orderBy('id')
                        ->get();
                    $inputType = $single ? 'radio' : 'checkbox';
                    $currentAnswer = $stepData['_answer'] ?? '';
                    $currentOrder = $stepData['_order'] ?? '';
                    $orderedIds = $currentOrder ? explode(',', $currentOrder) : $answers->pluck('id')->toArray();
                    $inputName = $fieldPrefix . 'answer';
                    $rowIdx = 0;
                    foreach ($orderedIds as $aid) {
                        $ans = $answers->firstWhere('id', $aid);
                        if (!$ans) continue;
                        $cls = $rowIdx % 2 === 0 ? 'r0' : 'r1';
                        $checked = (string) $aid === (string) $currentAnswer ? ' checked' : '';
                        $html .= '<div class="' . $cls . '">';
                        $html .= '<input type="' . $inputType . '" name="' . $inputName . '" value="' . $aid . '" id="' . $inputName . '_' . $aid . '"' . $checked . '>';
                        $html .= '<label for="' . $inputName . '_' . $aid . '">' . e($extractText($ans->answer ?? '')) . '</label>';
                        $html .= '</div>';
                        $rowIdx++;
                    }
                    break;

                case 'truefalse':
                    // Usar question_answers con fraction (1=true, 0=false) en vez de qtype_truefalse_options
                    $allAnswers = $db->table('question_answers')
                        ->where('question', $questionId)
                        ->orderBy('id')
                        ->get();
                    $trueAns  = $allAnswers->firstWhere('fraction', 1);
                    $falseAns = $allAnswers->firstWhere('fraction', 0);
                    $trueId   = $trueAns  ? (int) $trueAns->id  : 0;
                    $falseId  = $falseAns ? (int) $falseAns->id : 0;
                    $currentAnswer = $stepData['_answer'] ?? '';
                    $inputName = $fieldPrefix . 'answer';
                    $tChecked = (string) $trueId === (string) $currentAnswer ? ' checked' : '';
                    $fChecked = (string) $falseId === (string) $currentAnswer ? ' checked' : '';
                    $trueLabel  = $trueAns  ? e($extractText($trueAns->answer))  : 'Verdadero';
                    $falseLabel = $falseAns ? e($extractText($falseAns->answer)) : 'Falso';
                    $html .= '<div class="r0">';
                    $html .= '<input type="radio" name="' . $inputName . '" value="' . $trueId . '" id="' . $inputName . '_true"' . $tChecked . '>';
                    $html .= '<label for="' . $inputName . '_true">' . $trueLabel . '</label>';
                    $html .= '</div>';
                    $html .= '<div class="r1">';
                    $html .= '<input type="radio" name="' . $inputName . '" value="' . $falseId . '" id="' . $inputName . '_false"' . $fChecked . '>';
                    $html .= '<label for="' . $inputName . '_false">' . $falseLabel . '</label>';
                    $html .= '</div>';
                    break;

                case 'shortanswer':
                case 'numerical':
                case 'calculated':
                case 'calculatedsimple':
                    $currentValue = $stepData['_answer'] ?? '';
                    $html .= '<div class="r0">';
                    $html .= '<input type="' . ($qtype === 'numerical' || $qtype === 'calculated' || $qtype === 'calculatedsimple' ? 'number' : 'text') . '" name="' . $fieldPrefix . 'answer" value="' . e($currentValue) . '" style="width:100%;">';
                    $html .= '</div>';
                    break;

                case 'matching':
                    $subqs = [];
                    $allMatchAnswers = [];
                    try {
                        $subqs = $db->table('qtype_matching_subquestions')
                            ->where('questionid', $questionId)
                            ->orderBy('id')
                            ->get();
                        $allMatchAnswers = $db->table('question_answers')
                            ->where('question', $questionId)
                            ->orderBy('id')
                            ->get();
                    } catch (\Exception $e) {
                        Log::warning("buildDbQuestionHtml: matching tables not found for qid={$questionId}: " . $e->getMessage());
                    }
                    $subIdx = 0;
                    foreach ($subqs as $sq) {
                        $subName = $fieldPrefix . 'sub' . $subIdx;
                        $currentVal = $stepData['_sub' . $subIdx] ?? '';
                        $html .= '<div class="r0" style="display:flex;align-items:center;gap:.5rem;margin:.35rem 0;">';
                        $html .= '<span style="font-weight:500;font-size:.82rem;">' . e($extractText($sq->questiontext ?? '')) . '</span>';
                        $html .= '<select name="' . $subName . '" style="padding:.3rem .5rem;border:1px solid #d1d5db;border-radius:6px;">';
                        $html .= '<option value="">Seleccionar…</option>';
                        foreach ($allMatchAnswers as $ans) {
                            $sel = (string) $ans->id === (string) $currentVal ? ' selected' : '';
                            $html .= '<option value="' . $ans->id . '"' . $sel . '>' . e($extractText($ans->answer ?? '')) . '</option>';
                        }
                        $html .= '</select></div>';
                        $subIdx++;
                    }
                    if ($subIdx === 0) {
                        $html .= '<div style="color:#64748b;font-size:.82rem;"><i class="ri-information-line"></i> Pregunta de cotejamiento — no se pudieron cargar las opciones desde la BD.</div>';
                    }
                    break;

                case 'essay':
                    $currentValue = $stepData['_answer'] ?? '';
                    $html .= '<div class="r0">';
                    $html .= '<textarea name="' . $fieldPrefix . 'answer" rows="6" style="width:100%;">' . e($currentValue) . '</textarea>';
                    $html .= '</div>';
                    break;

                default:
                    $html .= '<div style="color:#64748b;font-size:.82rem;">' . $questionText . '</div>';
                    break;
            }
        } catch (\Exception $e) {
            Log::warning("buildDbQuestionHtml: error qtype={$qtype} qid={$questionId}: " . $e->getMessage());
            $html .= '<div style="color:#64748b;font-size:.82rem;">' . $questionText . '</div>';
        }

        $html .= '</div></div>';
        // sequencecheck = num_steps (inyectado desde getAttemptData)
        $seqVal = $stepData['_sequencecheck'] ?? '1';
        $html .= '<input type="hidden" name="sequencecheck" value="' . $seqVal . '">';
        return $html;
    }

    /**
     * Llama al WS get_attempt_data / get_attempt_review iterando por páginas si es necesario.
     * @param int|null $userId null = usar token admin
     */
    private function callWsGetAttemptQuestions(string $wsFn, ?int $userId, int $attemptId): array
    {
        $allQuestions = [];
        $seenSlots = [];

        // Iterar páginas 0..9 hasta encontrar una sin preguntas nuevas
        for ($page = 0; $page <= 9; $page++) {
            $params = ['attemptid' => $attemptId, 'page' => $page];

            if ($userId !== null) {
                $resp = $this->callAsUser($wsFn, $userId, $params);
            } else {
                $resp = $this->call($wsFn, $params);
            }

            if (!is_array($resp) || empty($resp['questions'])) {
                break;
            }

            $hasNew = false;
            foreach ($resp['questions'] as $q) {
                $slot = (int) ($q['slot'] ?? 0);
                if ($slot > 0 && !isset($seenSlots[$slot])) {
                    $seenSlots[$slot] = true;
                    $hasNew = true;
                    $allQuestions[] = $q;
                }
            }

            if (!$hasNew) break;
        }

        return $allQuestions;
    }

    /**
     * Parsea el array de preguntas devuelto por el WS de Moodle al formato interno.
     */
    private function parseWsQuestions(array $rawQuestions): array
    {
        $questions = [];
        foreach ($rawQuestions as $q) {
            $questions[] = [
                'slot'           => (int) ($q['slot'] ?? 0),
                'questionnumber' => (int) ($q['number'] ?? $q['slot'] ?? 0),
                'questionname'   => $q['questionname'] ?? '',
                'html'           => $q['html'] ?? '',
                'sequencecheck'  => (int) ($q['sequencecheck'] ?? 0),
                'state'          => $q['state'] ?? '',
                'status'         => $q['status'] ?? '',
                'maxmark'        => (float) ($q['maxmark'] ?? 0),
                'mark'           => $q['mark'] ?? null,
                'flagged'        => (bool) ($q['flagged'] ?? false),
            ];
        }
        return $questions;
    }

    /**
     * Inicia un nuevo intento de cuestionario en Moodle.
     * @param int $quizId ID del quiz (instance)
     * @param int $userId ID del usuario en Moodle
     * @return array|null Datos del intento iniciado o null si falla
     */
    public function startQuizAttempt(int $quizId, int $userId): ?array
    {
        $response = $this->callAsUser('mod_quiz_start_attempt', $userId, [
            'quizid' => $quizId,
        ]);
        return $response['attempt'] ?? null;
    }

    /**
     * Procesa/guarda/envía un intento de cuestionario en Moodle.
     * @param int $attemptId ID del intento
     * @param int $userId ID del usuario en Moodle
     * @param array $data Arreglo de datos del formulario [['name'=>'...','value'=>'...'], ...]
     * @param bool $finish Si debe finalizar el intento
     * @return array|null Respuesta de Moodle o null si falla
     */
    public function processAttempt(int $attemptId, int $userId, array $data, bool $finish = false): ?array
    {
        $params = [
            'attemptid'    => $attemptId,
            'finishattempt' => $finish ? 1 : 0,
            'timeup'       => 0,
        ];

        foreach ($data as $i => $item) {
            $params["data[{$i}][name]"]  = $item['name'] ?? '';
            $params["data[{$i}][value]"] = $item['value'] ?? '';
        }

        $response = $this->callAsUser('mod_quiz_process_attempt', $userId, $params);

        // Fallback: si el token del estudiante falla, reintentar con token admin
        if ($response === null) {
            Log::warning("processAttempt: WS mod_quiz_process_attempt con token usuario falló para attempt={$attemptId}, reintentando con token admin…");
            $response = $this->call('mod_quiz_process_attempt', $params);
        }

        if ($response === null) return null;

        return [
            'state'       => $response['state'] ?? 'inprogress',
            'attempt'     => $response['attempt'] ?? null,
            'warnings'    => $response['warnings'] ?? [],
        ];
    }

    /**
     * Limpia un curso importado de elementos no deseados:
     * - Foro llamado "avisos" en cualquier sección
     * - Páginas cuyo nombre coincide con el nombre de su sección
     * @param int $courseId ID del curso a limpiar
     * @return bool true si la limpieza se ejecutó (aunque algunos elementos no se encontraran)
     */
    public function cleanImportedCourse(int $courseId): bool
    {
        try {
            $secciones = $this->getCourseContentsWithDetails($courseId);
            
            $deletedCount = 0;
            
            foreach ($secciones as $seccion) {
                $sectionName = $seccion['name'] ?? '';
                
                foreach ($seccion['modules'] ?? [] as $mod) {
                    $modname = $mod['modname'] ?? '';
                    $cmid = $mod['id'] ?? null;
                    $modnameLower = strtolower($modname);
                    
                    if (!$cmid) {
                        continue;
                    }
                    
                    // Eliminar foro llamado "avisos"
                    if ($modnameLower === 'forum') {
                        $forumName = strtolower($mod['name'] ?? '');
                        if ($forumName === 'avisos') {
                            if ($this->deleteModule($cmid)) {
                                $deletedCount++;
                                Log::info("Eliminado foro 'avisos' (cmid=$cmid) del curso $courseId");
                            }
                        }
                    }
                    
                    // Eliminar páginas cuyo nombre coincide con el nombre de la sección
                    if ($modnameLower === 'page') {
                        $pageName = trim($mod['name'] ?? '');
                        $sectionNameTrim = trim($sectionName);
                        if (strcasecmp($pageName, $sectionNameTrim) === 0) {
                            if ($this->deleteModule($cmid)) {
                                $deletedCount++;
                                Log::info("Eliminada página '$pageName' (cmid=$cmid) del curso $courseId");
                            }
                        }
                    }
                }
            }
            
            Log::info("Limpieza de curso $courseId completada. Elementos eliminados: $deletedCount");
            return true;
            
        } catch (\Exception $e) {
            Log::warning("Limpieza de curso $courseId incompletada: " . $e->getMessage());
            return false;
        }
    }

    // ============================================================
    // EDITOR DE ACTIVIDADES — CRUD
    // ============================================================

    /**
     * Crea una nueva sección incrementando numsections y renombrándola.
     */
    public function createSection(int $courseId, string $name, string $summary = ''): ?int
    {
        $contents = $this->getCourseContents($courseId);
        $currentCount = count($contents);

        $updateResponse = $this->call('core_course_update_courses', [
            'courses[0][id]'          => $courseId,
            'courses[0][numsections]' => $currentCount + 1,
        ]);
        if ($updateResponse === null) {
            return null;
        }

        $newContents = $this->getCourseContents($courseId);
        $newSection = end($newContents);
        if (!$newSection || !isset($newSection['section'])) {
            return null;
        }
        $newSectionNum = (int)$newSection['section'];
        $newSectionId  = (int)$newSection['id'];

        $editOk = $this->editSection($courseId, $newSectionId, $name, $summary);
        return $editOk ? $newSectionNum : null;
    }

    /**
     * Edita nombre y resumen de una sección existente.
     * Usa la conexión directa a la BD de Moodle porque core_course_update_sections
     * no está disponible como webservice en esta instalación.
     * Requiere el ID de base de datos de la sección (sec.id de getCourseContents).
     */
    public function editSection(int $courseId, int $sectionId, string $name, string $summary = ''): bool
    {
        try {
            $db = DB::connection('moodle');

            $updated = $db->table('course_sections')
                ->where('id', $sectionId)
                ->where('course', $courseId)
                ->update([
                    'name'          => $name,
                    'summary'       => $summary,
                    'summaryformat' => 1,
                    'timemodified'  => time(),
                ]);

            // Incrementar cacherev del curso para que Moodle invalide su caché interna
            $db->table('course')
                ->where('id', $courseId)
                ->increment('cacherev');

            return $updated !== false;
        } catch (\Exception $e) {
            Log::error("MoodleService::editSection DB error (sectionId=$sectionId): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina una sección y todo su contenido.
     */
    public function deleteSection(int $courseId, int $sectionNumber): bool
    {
        $response = $this->call('core_course_delete_section', [
            'courseid' => $courseId,
            'section'  => $sectionNumber,
        ]);
        return $response !== null && empty($response);
    }

    /**
     * Actualiza una actividad directamente en las tablas de Moodle.
     * Usado para editar nombre, descripción y fechas de assign/quiz/forum.
     */
    public function updateActivityViaDB(int $cmid, string $moduleType, array $data): bool
    {
        try {
            $db  = DB::connection('moodle');
            $now = time();

            $cm = $db->table('course_modules')->where('id', $cmid)->first();
            if (!$cm) {
                Log::error("updateActivityViaDB: cmid=$cmid no encontrado en course_modules");
                return false;
            }

            Log::info("updateActivityViaDB: cmid=$cmid type=$moduleType instance={$cm->instance} course={$cm->course}");

            $common = [
                'name'         => $data['name']        ?? 'Sin título',
                'intro'        => $data['description'] ?? '',
                'introformat'  => 1,
                'timemodified' => $now,
            ];

            $affected = match ($moduleType) {
                'assign' => $db->table('assign')->where('id', $cm->instance)->update($common + [
                    'duedate'                  => $data['duedate']                  ?? 0,
                    'allowsubmissionsfromdate' => $data['allowsubmissionsfromdate'] ?? 0,
                    'cutoffdate'               => $data['cutoffdate']               ?? 0,
                    'grade'                    => $data['grade']                    ?? 100,
                ]),
                'quiz' => $db->table('quiz')->where('id', $cm->instance)->update($common + [
                    'timeopen'  => $data['timeopen']  ?? 0,
                    'timeclose' => $data['timeclose']  ?? 0,
                    'timelimit' => ($data['timelimit'] ?? 30) * 60,
                    'attempts'  => $data['attempts']  ?? 3,
                    'grade'     => (float)($data['grade'] ?? 100),
                    'sumgrades' => (float)($data['grade'] ?? 100),
                ]),
                'forum' => $db->table('forum')->where('id', $cm->instance)->update($common + [
                    'type'           => $data['forum_type']  ?? 'general',
                    'forcesubscribe' => $data['subscription'] ?? 0,
                    'assessed'       => !empty($data['grade']) ? 1 : 0,
                    'scale'          => !empty($data['grade']) ? (int) $data['grade'] : 0,
                    'duedate'        => $data['duedate'] ?? 0,
                    'cutoffdate'     => $data['cutoffdate'] ?? 0,
                ]) && $this->syncForumAvailability($db, $cmid, $data),
                'url' => $db->table('url')->where('id', $cm->instance)->update($common + [
                    'externalurl' => $data['externalurl'] ?? '',
                    'display'     => $data['display'] ?? 5,
                    'timemodified' => $now,
                ]),
                'resource' => $db->table('resource')->where('id', $cm->instance)->update($common),
                default => $db->table($moduleType)->where('id', $cm->instance)->update($common),
            };

            // Actualizar configuración adicional de plugins de entrega (assign)
            if ($moduleType === 'assign') {
                $this->upsertAssignPluginConfig($db, (int)$cm->instance, $data);
            }

            // Actualizar eventos de calendario
            $this->syncCalendarEvents($db, $moduleType, (int)$cm->instance, $data, $now);

            // Invalidar caché del curso (mismo mecanismo que createModuleViaDB)
            $db->table('course')->where('id', $cm->course)->increment('cacherev');

            return true;
        } catch (\Exception $e) {
            Log::error("updateActivityViaDB (cmid=$cmid): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crea o actualiza los registros de assign_plugin_config para una tarea existente.
     */
    private function upsertAssignPluginConfig($db, int $instanceId, array $data): void
    {
        $configs = [
            ['onlinetext', 'assignsubmission', 'enabled',                (int)($data['onlinetext']      ?? 1)],
            ['file',       'assignsubmission', 'enabled',                (int)($data['filesubmission']  ?? 1)],
            ['file',       'assignsubmission', 'maxfilesubmissions',     (int)($data['maxfiles']        ?? 3)],
            ['file',       'assignsubmission', 'maxsubmissionsizebytes', (int)($data['maxsize']         ?? 5242880)],
        ];

        foreach ($configs as [$plugin, $subtype, $name, $value]) {
            $exists = $db->table('assign_plugin_config')
                ->where('assignment', $instanceId)
                ->where('plugin',     $plugin)
                ->where('subtype',    $subtype)
                ->where('name',       $name)
                ->exists();

            if ($exists) {
                $db->table('assign_plugin_config')
                    ->where('assignment', $instanceId)
                    ->where('plugin',     $plugin)
                    ->where('subtype',    $subtype)
                    ->where('name',       $name)
                    ->update(['value' => (string)$value]);
            } else {
                $db->table('assign_plugin_config')->insert([
                    'assignment' => $instanceId,
                    'plugin'     => $plugin,
                    'subtype'    => $subtype,
                    'name'       => $name,
                    'value'      => (string)$value,
                ]);
            }
        }
    }

    /**
     * Sincroniza los eventos del calendario de Moodle tras editar fechas de una actividad.
     * Moodle crea eventos en mdl_event para mostrar fechas en el curso y el calendario.
     */
    private function syncCalendarEvents($db, string $moduleType, int $instanceId, array $data, int $now): void
    {
        $eventMap = match ($moduleType) {
            'assign' => [
                'duedate'                  => 'due',
                'allowsubmissionsfromdate' => 'open',
                'cutoffdate'               => 'close',
            ],
            'quiz' => [
                'timeopen'  => 'open',
                'timeclose' => 'close',
            ],
            'forum' => [
                'duedate'    => 'due',
                'cutoffdate' => 'close',
            ],
            default => [],
        };

        // Listar todos los eventos existentes de esta actividad para diagnóstico
        $existingEvents = $db->table('event')
            ->where('modulename', $moduleType)
            ->where('instance', $instanceId)
            ->get(['id','eventtype','timestart','name']);
        Log::info("syncCalendarEvents: eventos existentes para $moduleType instance=$instanceId → " . json_encode($existingEvents));

        foreach ($eventMap as $field => $eventType) {
            $timestamp = (int)($data[$field] ?? 0);

            $event = $db->table('event')
                ->where('modulename', $moduleType)
                ->where('instance',   $instanceId)
                ->where('eventtype',  $eventType)
                ->first();

            if ($timestamp > 0) {
                if ($event) {
                    $rows = $db->table('event')->where('id', $event->id)->update([
                        'name'         => $data['name'] ?? $event->name,
                        'timestart'    => $timestamp,
                        'timesort'     => $timestamp,
                        'timemodified' => $now,
                        'visible'      => 1,
                    ]);
                    Log::info("syncCalendarEvents: UPDATE event id={$event->id} eventtype=$eventType timestart=$timestamp rows=$rows");
                } else {
                    Log::info("syncCalendarEvents: no existe evento $eventType para $moduleType instance=$instanceId — omitiendo creación");
                }
            } elseif ($event) {
                $db->table('event')->where('id', $event->id)->update([
                    'visible'      => 0,
                    'timemodified' => $now,
                ]);
                Log::info("syncCalendarEvents: ocultado evento id={$event->id} eventtype=$eventType");
            }
        }
    }

    /**
     * Construye y escribe el JSON de availability en course_modules para un foro
     * basado en timeopen/timeclose (simula restricciones de fecha vía disponibilidad).
     */
    private function syncForumAvailability($db, int $cmid, array $data): bool
    {
        $timeopen  = (int) ($data['timeopen'] ?? 0);
        $timeclose = (int) ($data['timeclose'] ?? 0);

        if ($timeopen <= 0 && $timeclose <= 0) {
            $db->table('course_modules')->where('id', $cmid)->update(['availability' => null]);
            return true;
        }

        $conditions = [];
        $showc = [];

        if ($timeopen > 0) {
            $conditions[] = ['type' => 'date', 'd' => '>=', 't' => $timeopen];
            $showc[] = true;
        }
        if ($timeclose > 0) {
            $conditions[] = ['type' => 'date', 'd' => '<', 't' => $timeclose];
            $showc[] = true;
        }

        $availability = json_encode([
            'op'    => '&',
            'c'     => $conditions,
            'showc' => $showc,
        ]);

        $db->table('course_modules')->where('id', $cmid)->update(['availability' => $availability]);
        return true;
    }

    /**
     * Cambia el nombre de un módulo/actividad.
     */
    public function updateModuleName(int $cmid, string $name): bool
    {
        $response = $this->call('core_course_update_module', [
            'cmid'         => $cmid,
            'module[name]' => $name,
        ]);
        return $response !== null;
    }

    /**
     * Actualiza la descripción de un módulo/actividad.
     */
    public function updateModuleDescription(int $cmid, string $description): bool
    {
        $response = $this->call('core_course_update_module', [
            'cmid'                    => $cmid,
            'module[description]'     => $description,
            'module[descriptionformat]' => 1,
        ]);
        return $response !== null;
    }

    /**
     * Reordena las secciones de un curso.
     */
    public function reorderSections(int $courseId, array $sectionIds): bool
    {
        $params = ['courses[0][id]' => $courseId];
        foreach ($sectionIds as $i => $sid) {
            $params["courses[0][sections][{$i}][id]"] = $sid;
        }
        $response = $this->call('core_course_update_courses', $params);
        return $response !== null && empty($response);
    }

    /**
     * Reordena las actividades dentro de una sección.
     */
    public function reorderActivities(int $courseId, int $sectionId, array $cmids): bool
    {
        $response = $this->call('core_course_update_courses', [
            'courses[0][id]'                    => $courseId,
            'courses[0][sections][0][id]'       => $sectionId,
            'courses[0][sections][0][sequence]' => implode(',', $cmids),
        ]);
        return $response !== null && empty($response);
    }

    /**
     * Crea una actividad en Moodle.
     * Primero intenta REST API; si falla, usa DB directa.
     */
    public function createActivity(int $courseId, int $sectionNumber, string $moduleType, array $data): ?int
    {
        return $this->createModuleViaDB($courseId, $sectionNumber, $moduleType, $data);
    }

    /**
     * Sube un archivo al draft area de Moodle.
     */
    public function uploadFileToDraftArea(string $filepath, string $filename): ?int
    {
        $fileContent = base64_encode(file_get_contents($filepath));

        $response = $this->call('core_files_upload', [
            'component'    => 'user',
            'filearea'     => 'draft',
            'itemid'       => 0,
            'filepath'     => '/',
            'filename'     => $filename,
            'filecontent'  => $fileContent,
            'contextlevel' => 'user',
            'instanceid'   => 0,
        ]);

        return $response && isset($response['itemid']) ? (int)$response['itemid'] : null;
    }

    /**
     * Crea un recurso tipo Archivo desde un draft area.
     * Primero intenta REST API; si falla, crea recurso básico sin archivo vía DB.
     */
    public function createResourceFromDraft(int $courseId, int $sectionNumber, string $name, int $draftId, string $description = ''): ?int
    {
        return $this->createModuleViaDB($courseId, $sectionNumber, 'resource', [
            'name' => $name,
            'description' => $description,
        ]);
    }

    /**
     * Crea un recurso (resource) con su archivo adjunto escribiendo directamente
     * en el filesystem de Moodle y en mdl_files — mismo mecanismo que attachFileToAssignIntro.
     */
    public function createResourceWithFile(int $courseId, int $sectionNumber, string $name, string $description, \Illuminate\Http\UploadedFile $uploadedFile): ?int
    {
        try {
            $db         = DB::connection('moodle');
            $moodleData = config('moodle.dataroot');
            $now        = time();

            // 1. Crear el módulo resource en la BD
            $cmId = $this->createModuleViaDB($courseId, $sectionNumber, 'resource', [
                'name'        => $name,
                'description' => $description,
            ]);
            if (!$cmId) return null;

            // 2. Obtener el context del course_module recién creado
            $context = $db->table('context')
                ->where('contextlevel', 70)
                ->where('instanceid', $cmId)
                ->first();
            if (!$context) {
                Log::error("createResourceWithFile: context no encontrado para cmId=$cmId");
                return $cmId; // Devuelve el cmid aunque no se pudo adjuntar el archivo
            }

            // 3. Almacenar el archivo físicamente en filedir de Moodle
            $content  = file_get_contents($uploadedFile->getRealPath());
            $hash     = sha1($content);
            $filename = $uploadedFile->getClientOriginalName();
            $mimetype = $uploadedFile->getMimeType() ?: 'application/octet-stream';
            $filesize = $uploadedFile->getSize();

            $dirPath = rtrim($moodleData, DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR . 'filedir'
                . DIRECTORY_SEPARATOR . substr($hash, 0, 2)
                . DIRECTORY_SEPARATOR . substr($hash, 2, 2);
            if (!is_dir($dirPath)) mkdir($dirPath, 0755, true);
            $physicalPath = $dirPath . DIRECTORY_SEPARATOR . $hash;
            if (!file_exists($physicalPath)) file_put_contents($physicalPath, $content);

            // 4. Registrar directorio raíz en mdl_files si no existe
            $dirPhash = sha1("/{$context->id}/mod_resource/content/0/.");
            if (!$db->table('files')->where('pathnamehash', $dirPhash)->exists()) {
                $db->table('files')->insert([
                    'contenthash'     => 'da39a3ee5e6b4b0d3255bfef95601890afd80709',
                    'pathnamehash'    => $dirPhash,
                    'contextid'       => $context->id,
                    'component'       => 'mod_resource',
                    'filearea'        => 'content',
                    'itemid'          => 0,
                    'filepath'        => '/',
                    'filename'        => '.',
                    'userid'          => null,
                    'filesize'        => 0,
                    'mimetype'        => null,
                    'status'          => 0,
                    'source'          => null,
                    'author'          => null,
                    'license'         => null,
                    'timecreated'     => $now,
                    'timemodified'    => $now,
                    'sortorder'       => 0,
                    'referencefileid' => null,
                ]);
            }

            // 5. Registrar el archivo en mdl_files
            $pathnamehash = sha1("/{$context->id}/mod_resource/content/0/{$filename}");
            $fileRow = [
                'contenthash'     => $hash,
                'pathnamehash'    => $pathnamehash,
                'contextid'       => $context->id,
                'component'       => 'mod_resource',
                'filearea'        => 'content',
                'itemid'          => 0,
                'filepath'        => '/',
                'filename'        => $filename,
                'userid'          => null,
                'filesize'        => $filesize,
                'mimetype'        => $mimetype,
                'status'          => 0,
                'source'          => $filename,
                'author'          => null,
                'license'         => null,
                'timecreated'     => $now,
                'timemodified'    => $now,
                'sortorder'       => 1,
                'referencefileid' => null,
            ];

            if ($db->table('files')->where('pathnamehash', $pathnamehash)->exists()) {
                $db->table('files')->where('pathnamehash', $pathnamehash)->update([
                    'contenthash'  => $hash,
                    'filesize'     => $filesize,
                    'mimetype'     => $mimetype,
                    'timemodified' => $now,
                ]);
            } else {
                $db->table('files')->insert($fileRow);
            }

            $db->table('course')->where('id', $courseId)->increment('cacherev');

            Log::info("createResourceWithFile: resource cmId=$cmId archivo=$filename course=$courseId");
            return $cmId;

        } catch (\Exception $e) {
            Log::error("createResourceWithFile: " . $e->getMessage());
            return null;
        }
    }

    // ============================================================
    // CREACIÓN VÍA DB DIRECTA (fallback cuando REST no está disponible)
    // ============================================================

    /**
     * Crea una actividad insertando directamente en las tablas de Moodle.
     * Usado cuando core_course_create_module no está registrado en external_functions.
     */
    private function createModuleViaDB(int $courseId, int $sectionNumber, string $moduleType, array $data): ?int
    {
        try {
            $db = DB::connection('moodle');
            $now = time();

            // 1. Obtener ID del tipo de módulo
            $module = $db->table('modules')->where('name', $moduleType)->first();
            if (!$module) {
                Log::error("createModuleViaDB: tipo de módulo '$moduleType' no encontrado en mdl_modules");
                return null;
            }

            // 2. Obtener sección: intenta por número de sección, luego por ID directo
            $section = $db->table('course_sections')
                ->where('course', $courseId)
                ->where('section', $sectionNumber)
                ->first();

            if (!$section) {
                $section = $db->table('course_sections')
                    ->where('course', $courseId)
                    ->where('id', $sectionNumber)
                    ->first();
            }

            if (!$section) {
                Log::error("createModuleViaDB: sección $sectionNumber no encontrada en course $courseId");
                return null;
            }

            // 3. Crear instancia en la tabla específica del módulo
            $common = [
                'course'       => $courseId,
                'name'         => $data['name'] ?? 'Sin título',
                'intro'        => $data['description'] ?? '',
                'introformat'  => 1,
                'timemodified' => $now,
            ];

            $instanceId = match ($moduleType) {
                'assign' => $db->table('assign')->insertGetId($common + [
                    'duedate'                    => $data['duedate'] ?? 0,
                    'grade'                      => $data['grade'] ?? 100,
                    'alwaysshowdescription'      => 1,
                    'nosubmissions'              => $data['filesubmission'] ?? 1 ? 0 : 1,
                    'submissiondrafts'           => 0,
                    'sendnotifications'          => $data['notifyteachers'] ?? 0,
                    'sendlatenotifications'      => 0,
                    'allowsubmissionsfromdate'   => $data['allowsubmissionsfromdate'] ?? 0,
                    'cutoffdate'                 => $data['cutoffdate'] ?? 0,
                    'gradingduedate'             => 0,
                    'requiresubmissionstatement' => $data['requirestatement'] ?? 0,
                    'completionsubmit'           => 0,
                    'teamsubmission'             => 0,
                    'blindmarking'               => 0,
                    'markingworkflow'            => 0,
                    'markingallocation'          => 0,
                    'sendstudentnotifications'   => 1,
                ]),
                'quiz' => $db->table('quiz')->insertGetId($common + [
                    'timeopen'           => $data['timeopen'] ?? 0,
                    'timeclose'          => $data['timeclose'] ?? 0,
                    'timelimit'          => ($data['timelimit'] ?? 30) * 60,
                    'overduehandling'    => 'autoabandon',
                    'graceperiod'        => 0,
                    'preferredbehaviour' => 'deferredfeedback',
                    'canredoquestions'   => 0,
                    'attempts'           => $data['attempts'] ?? 3,
                    'attemptonlast'      => 0,
                    'grademethod'        => 1,
                    'decimalpoints'      => 2,
                    'questiondecimalpoints' => -1,
                    'reviewattempt'      => 0,
                    'reviewcorrectness'  => 0,
                    'reviewmaxmarks'     => 0,
                    'reviewmarks'        => 0,
                    'reviewspecificfeedback' => 0,
                    'reviewgeneralfeedback' => 0,
                    'reviewrightanswer'  => 0,
                    'reviewoverallfeedback' => 0,
                    'questionsperpage'   => 0,
                    'navmethod'          => 'free',
                    'shuffleanswers'     => 0,
                    'grade'              => (float)($data['grade'] ?? 100),
                    'sumgrades'          => (float)($data['grade'] ?? 100),
                    'timecreated'        => $now,
                    'password'           => '',
                    'subnet'             => '',
                    'browsersecurity'    => '',
                    'delay1'            => 0,
                    'delay2'            => 0,
                    'showblocks'         => 0,
                    'showuserpicture'    => 0,
                    'allowofflineattempts' => 0,
                ]),
                'forum' => $db->table('forum')->insertGetId($common + [
                    'type'           => $data['forum_type'] ?? 'general',
                    'forcesubscribe' => $data['subscription'] ?? 0,
                    'assessed'       => !empty($data['grade']) ? 1 : 0,
                    'scale'          => !empty($data['grade']) ? (int) $data['grade'] : 0,
                    'duedate'        => $data['duedate'] ?? 0,
                    'cutoffdate'     => $data['cutoffdate'] ?? 0,
                    'maxattachments' => 1,
                    'maxbytes'       => 0,
                ]),
                'page' => $db->table('page')->insertGetId($common + [
                    'content'       => $data['content'] ?? '',
                    'contentformat' => 1,
                    'revision'      => 1,
                ]),
                'url' => $db->table('url')->insertGetId($common + [
                    'externalurl'    => $data['externalurl'] ?? '',
                    'display'        => $data['display'] ?? 2,
                    'displayoptions' => '',
                    'parameters'     => '',
                ]),
                'resource' => $db->table('resource')->insertGetId($common + [
                    'tobemigrated'    => 0,
                    'legacyfiles'     => 0,
                    'legacyfileslast' => null,
                    'display'         => 0,
                    'displayoptions'  => '',
                    'filterfiles'     => 0,
                    'revision'        => 1,
                ]),
                default => null,
            };

            if (!$instanceId) {
                Log::error("createModuleViaDB: no se pudo crear instancia para '$moduleType'");
                return null;
            }

            // 3b. Sección inicial para quiz
            if ($moduleType === 'quiz') {
                $db->table('quiz_sections')->insert([
                    'quizid'           => $instanceId,
                    'firstslot'        => 1,
                    'heading'          => '',
                    'shufflequestions' => 0,
                ]);
            }

            // 3c. Configuración de plugins para assign (tipos de entrega)
            if ($moduleType === 'assign') {
                $pluginConfigs = [
                    ['onlinetext', 'assignsubmission', 'enabled', (int)($data['onlinetext'] ?? 1)],
                    ['file',       'assignsubmission', 'enabled', (int)($data['filesubmission'] ?? 1)],
                    ['file',       'assignsubmission', 'maxfilesubmissions', (int)($data['maxfiles'] ?? 3)],
                    ['file',       'assignsubmission', 'maxsubmissionsizebytes', (int)($data['maxsize'] ?? 5242880)],
                    ['file',       'assignsubmission', 'filetypeslist', ''],
                    ['comments',   'assignsubmission', 'enabled', 1],
                    ['comments',   'assignfeedback',   'enabled', 1],
                    ['comments',   'assignfeedback',   'commentinline', 0],
                    ['file',       'assignfeedback',   'enabled', 0],
                    ['offline',    'assignfeedback',   'enabled', 0],
                    ['editpdf',    'assignfeedback',   'enabled', 0],
                ];
                foreach ($pluginConfigs as $cfg) {
                    $db->table('assign_plugin_config')->insert([
                        'assignment' => $instanceId,
                        'plugin'     => $cfg[0],
                        'subtype'    => $cfg[1],
                        'name'       => $cfg[2],
                        'value'      => (string)$cfg[3],
                    ]);
                }
            }

            // 4. Crear course_modules
            $cmId = $db->table('course_modules')->insertGetId([
                'course'                  => $courseId,
                'module'                  => $module->id,
                'instance'                => $instanceId,
                'section'                 => $section->id,
                'idnumber'                => '',
                'added'                   => $now,
                'score'                   => 0,
                'indent'                  => 0,
                'visible'                 => 1,
                'visibleoncoursepage'     => 1,
                'visibleold'              => 1,
                'groupmode'               => 0,
                'groupingid'              => 0,
                'completion'              => 0,
                'completiongradeitemnumber' => null,
                'completionview'          => 0,
                'completionexpected'      => 0,
                'showdescription'         => 0,
                'availability'            => null,
                'deletioninprogress'      => 0,
            ]);

            // 5. Crear context (CONTEXT_MODULE = 70)
            $courseContext = $db->table('context')
                ->where('contextlevel', 50)
                ->where('instanceid', $courseId)
                ->first();

            $depth = $courseContext ? ($courseContext->depth + 1) : 3;

            $contextId = $db->table('context')->insertGetId([
                'contextlevel' => 70,
                'instanceid'   => $cmId,
                'path'         => '',
                'depth'        => $depth,
                'locked'       => 0,
            ]);

            // El path debe terminar con el ID del propio context, no con el cmId
            $parentPath = $courseContext ? $courseContext->path : '/1';
            $db->table('context')->where('id', $contextId)->update([
                'path' => $parentPath . '/' . $contextId,
            ]);

            // 5b. Sincronizar availability para foros (restricciones de fecha)
            if ($moduleType === 'forum') {
                $this->syncForumAvailability($db, $cmId, $data);
            }

            // 6. Actualizar sequence de la sección
            $oldSeq = $section->sequence ?? '';
            $newSeq = $oldSeq ? $oldSeq . ',' . $cmId : (string)$cmId;
            $db->table('course_sections')
                ->where('id', $section->id)
                ->update(['sequence' => $newSeq]);

            // 7. Invalidar caché del curso
            $db->table('course')
                ->where('id', $courseId)
                ->increment('cacherev');

            Log::info("createModuleViaDB: creado $moduleType cmid=$cmId en course=$courseId section=$sectionNumber");
            return $cmId;

        } catch (\Exception $e) {
            Log::error("createModuleViaDB error: " . $e->getMessage());
            return null;
        }
    }

    // ══════════════════════════════════════════════════════════════════════════
    // STUDENT ACTIVITY METHODS (direct DB — uses student's moodle_user_id)
    // ══════════════════════════════════════════════════════════════════════════

    // ── Assignment ────────────────────────────────────────────────────────────

    /**
     * Retorna un array [cmid => 'submitted'|null] con el estado de submission
     * de todas las tareas (assign) de un curso para un usuario.
     */
    public function getStudentAssignSubmissions(int $courseId, int $userId): array
    {
        $db = DB::connection('moodle');

        $cms = $db->table('course_modules')
            ->join('modules', 'modules.id', '=', 'course_modules.module')
            ->where('course_modules.course', $courseId)
            ->where('modules.name', 'assign')
            ->select('course_modules.id', 'course_modules.instance')
            ->get();

        if ($cms->isEmpty()) return [];

        $instanceIds = $cms->pluck('instance')->toArray();

        $submissions = $db->table('assign_submission')
            ->whereIn('assignment', $instanceIds)
            ->where('userid', $userId)
            ->where('latest', 1)
            ->where('status', 'submitted')
            ->pluck('status', 'assignment')
            ->toArray();

        $result = [];
        foreach ($cms as $cm) {
            if (isset($submissions[$cm->instance])) {
                $result[$cm->id] = $submissions[$cm->instance];
            }
        }
        return $result;
    }

    /**
     * Retorna [cmid => [['filename'=>'...','filesize'=>...], ...]] con los archivos
     * de las submissions de tareas de un usuario en un curso.
     */
    public function getStudentAssignFiles(int $courseId, int $userId): array
    {
        $db = DB::connection('moodle');

        $cms = $db->table('course_modules')
            ->join('modules', 'modules.id', '=', 'course_modules.module')
            ->where('course_modules.course', $courseId)
            ->where('modules.name', 'assign')
            ->select('course_modules.id', 'course_modules.instance')
            ->get();

        if ($cms->isEmpty()) return [];

        $instanceIds = $cms->pluck('instance')->toArray();

        $submissions = $db->table('assign_submission')
            ->whereIn('assignment', $instanceIds)
            ->where('userid', $userId)
            ->where('latest', 1)
            ->where('status', 'submitted')
            ->select('id', 'assignment')
            ->get()
            ->keyBy('assignment');

        if ($submissions->isEmpty()) return [];

        $result = [];
        foreach ($cms as $cm) {
            $sub = $submissions->get($cm->instance);
            if (!$sub) continue;

            $context = $db->table('context')
                ->where('contextlevel', 70)->where('instanceid', $cm->id)->first();
            if (!$context) continue;

            $files = $db->table('files')
                ->where('contextid', $context->id)
                ->where('component', 'assignsubmission_file')
                ->where('filearea', 'submission_files')
                ->where('itemid', $sub->id)
                ->where('filename', '<>', '.')
                ->select('filename', 'filesize')
                ->get()
                ->toArray();

            if (!empty($files)) {
                $result[$cm->id] = $files;
            }
        }
        return $result;
    }

    public function getAssignDetailsForStudent(int $cmid, int $courseId, int $userId): array
    {
        $db = DB::connection('moodle');

        $cm = $db->table('course_modules')
            ->where('id', $cmid)->where('course', $courseId)->first();
        if (!$cm) return [];

        $assign = $db->table('assign')->where('id', $cm->instance)->first();
        if (!$assign) return [];

        $submission = $db->table('assign_submission')
            ->where('assignment', $assign->id)
            ->where('userid', $userId)
            ->where('latest', 1)
            ->first();

        $onlineText = null;
        $files      = [];

        $context = null;
        if ($submission) {
            $ot = $db->table('assignsubmission_onlinetext')
                ->where('assignment', $assign->id)
                ->where('submission', $submission->id)
                ->first();
            $onlineText = $ot?->onlinetext;

            $context = $db->table('context')
                ->where('contextlevel', 70)->where('instanceid', $cmid)->first();
            if ($context) {
                $files = $db->table('files')
                    ->where('contextid', $context->id)
                    ->where('component', 'assignsubmission_file')
                    ->where('filearea', 'submission_files')
                    ->where('itemid', $submission->id)
                    ->where('filename', '<>', '.')
                    ->select('filename', 'filesize', 'mimetype', 'timecreated')
                    ->get()->toArray();
            }
        }

        // Calificación — intenta assign_grades primero, luego grade_grades como fallback
        $gradeVal = null;
        $gradeGrader = null;
        $gradeTime = null;

        $ag = $db->table('assign_grades')
            ->where('assignment', $assign->id)
            ->where('userid', $userId)
            ->orderByDesc('id')
            ->first(['grade', 'grader', 'timemodified']);

        if ($ag && $ag->grade !== null) {
            $gradeVal    = (float) $ag->grade;
            $gradeGrader = $ag->grader;
            $gradeTime   = $ag->timemodified;
        } else {
            $gi = $db->table('grade_items')
                ->where('courseid', $courseId)
                ->where('itemmodule', 'assign')
                ->where('iteminstance', $assign->id)
                ->first(['id']);
            if ($gi) {
                $gg = $db->table('grade_grades')
                    ->where('itemid', $gi->id)
                    ->where('userid', $userId)
                    ->first(['rawgrade', 'usermodified', 'timemodified']);
                if ($gg && $gg->rawgrade !== null) {
                    $gradeVal    = (float) $gg->rawgrade;
                    $gradeGrader = $gg->usermodified;
                    $gradeTime   = $gg->timemodified;
                }
            }
        }

        return [
            'assign' => [
                'id'             => $assign->id,
                'name'           => $assign->name,
                'intro'          => $assign->intro,
                'duedate'        => $assign->duedate,
                'cutoffdate'     => $assign->cutoffdate,
                'nosubmissions'  => $assign->nosubmissions,
                'allowonlinetext'=> true,
                'contextid'      => $context?->id,
                'grade'          => $gradeVal,
                'grademax'       => (float) ($assign->grade ?? 100),
                'grade_grader'   => $gradeGrader,
                'grade_time'     => $gradeTime,
            ],
            'submission' => $submission ? [
                'id'          => $submission->id,
                'status'      => $submission->status,
                'timemodified'=> $submission->timemodified,
                'onlinetext'  => $onlineText,
                'files'       => $files,
            ] : null,
        ];
    }

    public function submitAssignmentOnlineText(int $cmid, int $courseId, int $userId, ?string $text = null): bool
    {
        $db  = DB::connection('moodle');
        $now = time();

        $cm = $db->table('course_modules')
            ->where('id', $cmid)->where('course', $courseId)->first();
        if (!$cm) return false;
        $assignId = $cm->instance;

        try {
            $existing = $db->table('assign_submission')
                ->where('assignment', $assignId)
                ->where('userid', $userId)
                ->where('latest', 1)
                ->first();

            if ($existing) {
                $db->table('assign_submission')
                    ->where('id', $existing->id)
                    ->update(['status' => 'submitted', 'timemodified' => $now]);

                if ($text !== null && $text !== '') {
                    $existingText = $db->table('assignsubmission_onlinetext')
                        ->where('assignment', $assignId)
                        ->where('submission', $existing->id)
                        ->first();

                    if ($existingText) {
                        $db->table('assignsubmission_onlinetext')
                            ->where('id', $existingText->id)
                            ->update(['onlinetext' => $text, 'onlineformat' => 1]);
                    } else {
                        $db->table('assignsubmission_onlinetext')->insert([
                            'assignment'   => $assignId,
                            'submission'   => $existing->id,
                            'onlinetext'   => $text,
                            'onlineformat' => 1,
                        ]);
                    }
                }
            } else {
                $submissionId = $db->table('assign_submission')->insertGetId([
                    'assignment'    => $assignId,
                    'userid'        => $userId,
                    'timecreated'   => $now,
                    'timemodified'  => $now,
                    'status'        => 'submitted',
                    'groupid'       => 0,
                    'attemptnumber' => 0,
                    'latest'        => 1,
                ]);

                if ($text !== null && $text !== '') {
                    $db->table('assignsubmission_onlinetext')->insert([
                        'assignment'   => $assignId,
                        'submission'   => $submissionId,
                        'onlinetext'   => $text,
                        'onlineformat' => 1,
                    ]);
                }
            }

            Log::info("submitAssignmentOnlineText: assign={$assignId} user={$userId}");
            return true;
        } catch (\Exception $e) {
            Log::error("submitAssignmentOnlineText: " . $e->getMessage());
            return false;
        }
    }

    public function uploadAssignmentFile(int $cmid, int $courseId, int $userId, $uploadedFile): bool
    {
        $moodleData = config('moodle.dataroot');
        if (!$moodleData) {
            $db = DB::connection('moodle');
            $row = $db->table('config')->where('name', 'dataroot')->first();
            $moodleData = $row?->value;
        }
        if (!$moodleData) {
            Log::warning('uploadAssignmentFile: MOODLE_DATAROOT not configured and could not be detected from DB.');
            return false;
        }

        $db  = DB::connection('moodle');
        $now = time();

        $cm = $db->table('course_modules')
            ->where('id', $cmid)->where('course', $courseId)->first();
        if (!$cm) return false;
        $assignId = $cm->instance;

        $context = $db->table('context')
            ->where('contextlevel', 70)->where('instanceid', $cmid)->first();
        if (!$context) return false;

        try {
            $submission = $db->table('assign_submission')
                ->where('assignment', $assignId)->where('userid', $userId)->where('latest', 1)->first();

            if (!$submission) {
                $submissionId = $db->table('assign_submission')->insertGetId([
                    'assignment'    => $assignId,
                    'userid'        => $userId,
                    'timecreated'   => $now,
                    'timemodified'  => $now,
                    'status'        => 'submitted',
                    'groupid'       => 0,
                    'attemptnumber' => 0,
                    'latest'        => 1,
                ]);
            } else {
                $submissionId = $submission->id;
                $db->table('assign_submission')
                    ->where('id', $submissionId)
                    ->update(['status' => 'submitted', 'timemodified' => $now]);
            }

            $content  = file_get_contents($uploadedFile->getRealPath());
            $hash     = sha1($content);
            $filename = $uploadedFile->getClientOriginalName();
            $mimetype = $uploadedFile->getMimeType() ?: 'application/octet-stream';
            $filesize = $uploadedFile->getSize();

            $dirPath = rtrim($moodleData, DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR . 'filedir'
                . DIRECTORY_SEPARATOR . substr($hash, 0, 2)
                . DIRECTORY_SEPARATOR . substr($hash, 2, 2);

            if (!is_dir($dirPath)) mkdir($dirPath, 0755, true);
            $physicalPath = $dirPath . DIRECTORY_SEPARATOR . $hash;
            if (!file_exists($physicalPath)) file_put_contents($physicalPath, $content);

            // pathnamehash: sha1("/{contextid}/{component}/{filearea}/{itemid}{filepath}{filename}")
            $pathnamehash = sha1("/{$context->id}/assignsubmission_file/submission_files/{$submissionId}/{$filename}");

            // Registrar entrada de directorio raíz de la submission si no existe
            $dirHash = sha1("/{$context->id}/assignsubmission_file/submission_files/{$submissionId}/.");
            if (!$db->table('files')->where('pathnamehash', $dirHash)->exists()) {
                $db->table('files')->insert([
                    'contenthash'     => 'da39a3ee5e6b4b0d3255bfef95601890afd80709',
                    'pathnamehash'    => $dirHash,
                    'contextid'       => $context->id,
                    'component'       => 'assignsubmission_file',
                    'filearea'        => 'submission_files',
                    'itemid'          => $submissionId,
                    'filepath'        => '/',
                    'filename'        => '.',
                    'userid'          => null,
                    'filesize'        => 0,
                    'mimetype'        => null,
                    'status'          => 0,
                    'source'          => null,
                    'author'          => null,
                    'license'         => null,
                    'timecreated'     => $now,
                    'timemodified'    => $now,
                    'sortorder'       => 0,
                    'referencefileid' => null,
                ]);
            }

            if (!$db->table('files')->where('pathnamehash', $pathnamehash)->exists()) {
                $db->table('files')->insert([
                    'contenthash'     => $hash,
                    'pathnamehash'    => $pathnamehash,
                    'contextid'       => $context->id,
                    'component'       => 'assignsubmission_file',
                    'filearea'        => 'submission_files',
                    'itemid'          => $submissionId,
                    'filepath'        => '/',
                    'filename'        => $filename,
                    'userid'          => $userId,
                    'filesize'        => $filesize,
                    'mimetype'        => $mimetype,
                    'status'          => 0,
                    'source'          => $filename,
                    'author'          => null,
                    'license'         => null,
                    'timecreated'     => $now,
                    'timemodified'    => $now,
                    'sortorder'       => 0,
                    'referencefileid' => null,
                ]);
            }

            Log::info("uploadAssignmentFile: assign={$assignId} user={$userId} file={$filename}");
            return true;
        } catch (\Exception $e) {
            Log::error("uploadAssignmentFile: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el contenido de un archivo de submission para descarga.
     */
    public function getAssignmentFile(int $cmid, int $courseId, int $userId, string $filename): ?array
    {
        $moodleData = config('moodle.dataroot');
        if (!$moodleData) {
            $db = DB::connection('moodle');
            $row = $db->table('config')->where('name', 'dataroot')->first();
            $moodleData = $row?->value;
        }
        if (!$moodleData) return null;

        $db = DB::connection('moodle');

        $cm = $db->table('course_modules')
            ->where('id', $cmid)->where('course', $courseId)->first();
        if (!$cm) return null;

        $context = $db->table('context')
            ->where('contextlevel', 70)->where('instanceid', $cmid)->first();
        if (!$context) return null;

        $submission = $db->table('assign_submission')
            ->where('assignment', $cm->instance)->where('userid', $userId)->where('latest', 1)->first();
        if (!$submission) return null;

        $file = $db->table('files')
            ->where('contextid', $context->id)
            ->where('component', 'assignsubmission_file')
            ->where('filearea', 'submission_files')
            ->where('itemid', $submission->id)
            ->where('filename', $filename)
            ->first(['filename', 'filesize', 'mimetype', 'contenthash']);

        if (!$file) return null;

        $dirPath = rtrim($moodleData, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . 'filedir'
            . DIRECTORY_SEPARATOR . substr($file->contenthash, 0, 2)
            . DIRECTORY_SEPARATOR . substr($file->contenthash, 2, 2);
        $physicalPath = $dirPath . DIRECTORY_SEPARATOR . $file->contenthash;

        if (!file_exists($physicalPath)) return null;

        return [
            'filename' => $file->filename,
            'filesize' => $file->filesize,
            'mimetype' => $file->mimetype,
            'content'  => file_get_contents($physicalPath),
        ];
    }

    /**
     * Elimina un archivo de la submission de una tarea (solo registro en BD, no el físico).
     */
    public function deleteAssignmentFile(int $cmid, int $courseId, int $userId, string $filename): bool
    {
        $db = DB::connection('moodle');

        $cm = $db->table('course_modules')
            ->where('id', $cmid)->where('course', $courseId)->first();
        if (!$cm) return false;

        $context = $db->table('context')
            ->where('contextlevel', 70)->where('instanceid', $cmid)->first();
        if (!$context) return false;

        $submission = $db->table('assign_submission')
            ->where('assignment', $cm->instance)->where('userid', $userId)->where('latest', 1)->first();
        if (!$submission) return false;

        $pathnamehash = sha1("/{$context->id}/assignsubmission_file/submission_files/{$submission->id}/{$filename}");

        $deleted = $db->table('files')
            ->where('pathnamehash', $pathnamehash)
            ->where('component', 'assignsubmission_file')
            ->where('filearea', 'submission_files')
            ->delete();

        if ($deleted) {
            Log::info("deleteAssignmentFile: cmid={$cmid} user={$userId} file={$filename}");
        }

        return (bool) $deleted;
    }

    /**
     * Adjunta un archivo como intro-attachment de una tarea (assign).
     * Reemplaza cualquier adjunto anterior.
     */
    public function attachFileToAssignIntro(int $cmid, \Illuminate\Http\UploadedFile $uploadedFile): bool
    {
        try {
            $db         = DB::connection('moodle');
            $moodleData = config('moodle.dataroot');
            $now        = time();

            $cm = $db->table('course_modules')->where('id', $cmid)->first();
            if (!$cm) return false;

            $context = $db->table('context')
                ->where('contextlevel', 70)
                ->where('instanceid', $cmid)
                ->first();
            if (!$context) return false;

            $content  = file_get_contents($uploadedFile->getRealPath());
            $hash     = sha1($content);
            $filename = $uploadedFile->getClientOriginalName();
            $mimetype = $uploadedFile->getMimeType() ?: 'application/octet-stream';
            $filesize = $uploadedFile->getSize();

            // Almacenar físicamente en el filedir de Moodle
            $dirPath = rtrim($moodleData, DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR . 'filedir'
                . DIRECTORY_SEPARATOR . substr($hash, 0, 2)
                . DIRECTORY_SEPARATOR . substr($hash, 2, 2);
            if (!is_dir($dirPath)) mkdir($dirPath, 0755, true);
            $physicalPath = $dirPath . DIRECTORY_SEPARATOR . $hash;
            if (!file_exists($physicalPath)) file_put_contents($physicalPath, $content);

            // Eliminar adjuntos anteriores (no el directorio '.')
            $db->table('files')
                ->where('contextid', $context->id)
                ->where('component', 'mod_assign')
                ->where('filearea', 'introattachment')
                ->where('itemid', 0)
                ->where('filename', '<>', '.')
                ->delete();

            // Registro directorio raíz si no existe
            $dirPhash = sha1("/{$context->id}/mod_assign/introattachment/0/.");
            if (!$db->table('files')->where('pathnamehash', $dirPhash)->exists()) {
                $db->table('files')->insert([
                    'contenthash'     => 'da39a3ee5e6b4b0d3255bfef95601890afd80709',
                    'pathnamehash'    => $dirPhash,
                    'contextid'       => $context->id,
                    'component'       => 'mod_assign',
                    'filearea'        => 'introattachment',
                    'itemid'          => 0,
                    'filepath'        => '/',
                    'filename'        => '.',
                    'userid'          => null,
                    'filesize'        => 0,
                    'mimetype'        => null,
                    'status'          => 0,
                    'source'          => null,
                    'author'          => null,
                    'license'         => null,
                    'timecreated'     => $now,
                    'timemodified'    => $now,
                    'sortorder'       => 0,
                    'referencefileid' => null,
                ]);
            }

            // Registro del archivo
            $pathnamehash = sha1("/{$context->id}/mod_assign/introattachment/0/{$filename}");
            $fileRow = [
                'contenthash'     => $hash,
                'pathnamehash'    => $pathnamehash,
                'contextid'       => $context->id,
                'component'       => 'mod_assign',
                'filearea'        => 'introattachment',
                'itemid'          => 0,
                'filepath'        => '/',
                'filename'        => $filename,
                'userid'          => null,
                'filesize'        => $filesize,
                'mimetype'        => $mimetype,
                'status'          => 0,
                'source'          => $filename,
                'author'          => null,
                'license'         => null,
                'timecreated'     => $now,
                'timemodified'    => $now,
                'sortorder'       => 0,
                'referencefileid' => null,
            ];

            if ($db->table('files')->where('pathnamehash', $pathnamehash)->exists()) {
                $db->table('files')->where('pathnamehash', $pathnamehash)->update([
                    'contenthash'  => $hash,
                    'filesize'     => $filesize,
                    'mimetype'     => $mimetype,
                    'timemodified' => $now,
                ]);
            } else {
                $db->table('files')->insert($fileRow);
            }

            // Actualizar introattachments en assign si la columna existe
            try {
                $db->table('assign')->where('id', $cm->instance)->update(['introattachments' => 1, 'timemodified' => $now]);
            } catch (\Exception) {
                $db->table('assign')->where('id', $cm->instance)->update(['timemodified' => $now]);
            }

            $db->table('course')->where('id', $cm->course)->increment('cacherev');

            Log::info("attachFileToAssignIntro: cmid=$cmid file=$filename");
            return true;
        } catch (\Exception $e) {
            Log::error("attachFileToAssignIntro (cmid=$cmid): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retorna el nombre e info del archivo adjunto de intro de una tarea, si existe.
     */
    public function getAssignIntroAttachment(int $cmid): ?array
    {
        try {
            $db      = DB::connection('moodle');
            $context = $db->table('context')
                ->where('contextlevel', 70)
                ->where('instanceid', $cmid)
                ->first();
            if (!$context) return null;

            $file = $db->table('files')
                ->where('contextid', $context->id)
                ->where('component', 'mod_assign')
                ->where('filearea', 'introattachment')
                ->where('itemid', 0)
                ->where('filename', '<>', '.')
                ->first(['filename', 'mimetype', 'filesize']);

            return $file ? ['name' => $file->filename, 'size' => $file->filesize, 'mime' => $file->mimetype] : null;
        } catch (\Exception $e) {
            Log::error("getAssignIntroAttachment (cmid=$cmid): " . $e->getMessage());
            return null;
        }
    }

    // ── Forum ─────────────────────────────────────────────────────────────────

    /**
     * Obtiene un mapa de forum cmid => bool indicando si el usuario ha participado.
     * @param array $forumCms Arreglo de objetos course_modules con al menos id, instance
     * @param int $userId ID del usuario en Moodle
     * @return array [cmid => true/false]
     */
    public function getForumParticipationMap(array $forumCms, int $userId): array
    {
        $map = [];
        if (empty($forumCms)) return $map;

        $db = DB::connection('moodle');
        $instanceIds = array_map(fn($cm) => $cm->instance, $forumCms);

        $participated = $db->table('forum_discussions')
            ->whereIn('forum', $instanceIds)
            ->where('userid', $userId)
            ->pluck('forum')
            ->toArray();

        foreach ($forumCms as $cm) {
            $map[(int) $cm->id] = in_array((int) $cm->instance, $participated);
        }

        return $map;
    }

    public function getForumDiscussionsWithCount(int $forumId, int $currentUserId): array
    {
        $db = DB::connection('moodle');

        $discussions = $db->table('forum_discussions')
            ->join('forum_posts', 'forum_posts.id', '=', 'forum_discussions.firstpost')
            ->join('user', 'user.id', '=', 'forum_discussions.userid')
            ->where('forum_discussions.forum', $forumId)
            ->select(
                'forum_discussions.id',
                'forum_discussions.name',
                'forum_discussions.userid',
                'forum_discussions.timemodified',
                'forum_posts.message as firstmessage',
                'user.firstname',
                'user.lastname'
            )
            ->orderByDesc('forum_discussions.timemodified')
            ->get();

        if ($discussions->isEmpty()) return [];

        // Count posts per discussion in a single aggregation query
        $counts = $db->table('forum_posts')
            ->whereIn('discussion', $discussions->pluck('id')->toArray())
            ->where('deleted', 0)
            ->select('discussion', DB::raw('COUNT(*) as cnt'))
            ->groupBy('discussion')
            ->pluck('cnt', 'discussion')
            ->toArray();

        return $discussions->map(fn($d) => [
            'id'           => $d->id,
            'name'         => $d->name,
            'author'       => trim($d->firstname . ' ' . $d->lastname),
            'is_mine'      => (int) $d->userid === $currentUserId,
            'firstmessage' => mb_substr(strip_tags($d->firstmessage ?? ''), 0, 200),
            'replies'      => max(0, (int) ($counts[$d->id] ?? 1) - 1),
            'timemodified' => $d->timemodified,
        ])->toArray();
    }

    public function getForumDiscussionPostsWithAuthors(int $discussionId): array
    {
        $db = DB::connection('moodle');

        return $db->table('forum_posts as p')
            ->join('user as u', 'u.id', '=', 'p.userid')
            ->where('p.discussion', $discussionId)
            ->where('p.deleted', 0)
            ->select('p.id', 'p.parent', 'p.userid', 'p.created', 'p.modified', 'p.subject', 'p.message', 'p.messageformat', 'u.firstname', 'u.lastname')
            ->orderBy('p.created')
            ->get()
            ->map(fn($p) => [
                'id'      => $p->id,
                'parent'  => $p->parent,
                'userid'  => $p->userid,
                'author'  => trim($p->firstname . ' ' . $p->lastname),
                'created' => $p->created,
                'subject' => $p->subject,
                'message' => $p->messageformat == 1
                    ? $p->message
                    : nl2br(htmlspecialchars($p->message ?? '', ENT_QUOTES, 'UTF-8')),
            ])
            ->toArray();
    }

    public function getForumAllPostsByForum(int $forumId): array
    {
        $db = DB::connection('moodle');
        try {
            return $db->table('forum_posts as p')
                ->join('forum_discussions as d', 'd.id', '=', 'p.discussion')
                ->join('user as u', 'u.id', '=', 'p.userid')
                ->where('d.forum', $forumId)
                ->where('p.deleted', 0)
                ->select('p.id', 'p.discussion', 'p.parent', 'p.userid', 'p.created', 'p.subject', 'p.message', 'p.messageformat', 'd.name as discussion_name', 'u.firstname', 'u.lastname')
                ->orderBy('d.id')
                ->orderBy('p.created')
                ->get()
                ->map(fn($p) => [
                    'id'              => $p->id,
                    'discussion_id'   => $p->discussion,
                    'discussion_name' => $p->discussion_name,
                    'parent'          => $p->parent,
                    'userid'          => $p->userid,
                    'author'          => trim($p->firstname . ' ' . $p->lastname),
                    'created'         => $p->created,
                    'subject'         => $p->subject,
                    'message'         => $p->messageformat == 1
                        ? $p->message
                        : nl2br(htmlspecialchars($p->message ?? '', ENT_QUOTES, 'UTF-8')),
                ])
                ->toArray();
        } catch (\Exception $e) {
            Log::warning("getForumAllPostsByForum: " . $e->getMessage());
            return [];
        }
    }

    public function createForumDiscussionAsUser(int $forumId, int $courseId, int $userId, string $subject, string $message): ?int
    {
        $db  = DB::connection('moodle');
        $now = time();
        $html = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

        try {
            $postId = $db->table('forum_posts')->insertGetId([
                'discussion'     => 0,
                'parent'         => 0,
                'userid'         => $userId,
                'created'        => $now,
                'modified'       => $now,
                'mailed'         => 0,
                'subject'        => $subject,
                'message'        => $html,
                'messageformat'  => 1,
                'messagetrust'   => 0,
                'attachment'     => '',
                'totalscore'     => 0,
                'mailnow'        => 0,
                'deleted'        => 0,
                'privatereplyto' => 0,
                'wordcount'      => str_word_count(strip_tags($message)),
                'charcount'      => mb_strlen($message),
            ]);

            $discussionId = $db->table('forum_discussions')->insertGetId([
                'course'       => $courseId,
                'forum'        => $forumId,
                'name'         => $subject,
                'firstpost'    => $postId,
                'userid'       => $userId,
                'groupid'      => -1,
                'assessed'     => 1,
                'timemodified' => $now,
                'usermodified' => $userId,
                'timestart'    => 0,
                'timeend'      => 0,
                'pinned'       => 0,
                'timelocked'   => 0,
            ]);

            $db->table('forum_posts')->where('id', $postId)->update(['discussion' => $discussionId]);
            $db->table('forum')->where('id', $forumId)->update(['timemodified' => $now]);

            Log::info("createForumDiscussionAsUser: forum={$forumId} user={$userId} discussion={$discussionId}");
            return $discussionId;
        } catch (\Exception $e) {
            Log::error("createForumDiscussionAsUser: " . $e->getMessage());
            return null;
        }
    }

    public function addForumReplyAsUser(int $discussionId, int $parentPostId, int $forumId, int $userId, string $subject, string $message): ?int
    {
        $db  = DB::connection('moodle');
        $now = time();
        $html = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

        try {
            $postId = $db->table('forum_posts')->insertGetId([
                'discussion'     => $discussionId,
                'parent'         => $parentPostId,
                'userid'         => $userId,
                'created'        => $now,
                'modified'       => $now,
                'mailed'         => 0,
                'subject'        => 'Re: ' . $subject,
                'message'        => $html,
                'messageformat'  => 1,
                'messagetrust'   => 0,
                'attachment'     => '',
                'totalscore'     => 0,
                'mailnow'        => 0,
                'deleted'        => 0,
                'privatereplyto' => 0,
                'wordcount'      => str_word_count(strip_tags($message)),
                'charcount'      => mb_strlen($message),
            ]);

            $db->table('forum_discussions')->where('id', $discussionId)->update([
                'timemodified' => $now,
                'usermodified' => $userId,
            ]);
            $db->table('forum')->where('id', $forumId)->update(['timemodified' => $now]);

            Log::info("addForumReplyAsUser: discussion={$discussionId} user={$userId} post={$postId}");
            return $postId;
        } catch (\Exception $e) {
            Log::error("addForumReplyAsUser: " . $e->getMessage());
            return null;
        }
    }

    // ── Quiz ──────────────────────────────────────────────────────────────────

    public function getQuizDetailsForStudent(int $quizId, int $userId): array
    {
        $db = DB::connection('moodle');

        $quiz = $db->table('quiz')->where('id', $quizId)->first();
        if (!$quiz) return [];

        $attempts = $db->table('quiz_attempts')
            ->where('quiz', $quizId)
            ->where('userid', $userId)
            ->where('state', 'finished')
            ->orderBy('attempt')
            ->get()
            ->map(function ($a) use ($quiz) {
                $sumgrades = round((float) $a->sumgrades, 2);
                $grade     = ($quiz->sumgrades > 0 && $quiz->grade > 0)
                    ? round($sumgrades / $quiz->sumgrades * $quiz->grade, 2)
                    : null;
                return [
                    'attempt'    => $a->attempt,
                    'timestart'  => $a->timestart,
                    'timefinish' => $a->timefinish,
                    'sumgrades'  => $sumgrades,
                    'grade'      => $grade,
                    'passed'     => $grade !== null && $grade >= ($quiz->grade * 0.51),
                ];
            })
            ->toArray();

        $inProgressRow = $db->table('quiz_attempts')
            ->where('quiz', $quizId)
            ->where('userid', $userId)
            ->where('state', 'inprogress')
            ->first(['id']);

        $hasInProgress = (bool) $inProgressRow;
        $inProgressAttemptId = $inProgressRow ? (int) $inProgressRow->id : null;

        return [
            'quiz' => [
                'id'        => $quiz->id,
                'name'      => $quiz->name,
                'intro'     => $quiz->intro,
                'timeopen'  => $quiz->timeopen,
                'timeclose' => $quiz->timeclose,
                'timelimit' => $quiz->timelimit,
                'attempts'  => $quiz->attempts,
                'grade'     => $quiz->grade,
            ],
            'student_attempts'       => $attempts,
            'has_inprogress'         => $hasInProgress,
            'inprogress_attempt_id'  => $inProgressAttemptId,
            'max_attempts_reached'   => $quiz->attempts > 0 && count($attempts) >= $quiz->attempts,
        ];
    }

    /**
     * Sincroniza la ponderación de una actividad con Moodle:
     * - Actualiza grademax en grade_items y en la tabla del módulo (assign/quiz).
     * - En modo 'ponderar': rescala las notas de los estudiantes proporcionalmente.
     * - En modo 'mantener': deja las notas intactas, solo cambia grademax.
     * - Actualiza aggregationcoef/aggregationcoef2 según el método de agregación.
     */
    public function sincronizarPonderacion(
        int    $gradeItemId,
        string $moduleType,
        int    $instanceId,
        int    $courseId,
        float  $nuevaNotaMax,
        float  $viejaNotaMax,
        array  $estudiantesGrades,  // [moodle_user_id => raw_grade|null]
        string $modo                 // 'ponderar' | 'mantener'
    ): array {
        $db  = DB::connection('moodle');
        $now = time();
        $actualizados = 0;
        $errores      = 0;

        try {
            // ── 1. Si el ID es sintético, lo creamos de verdad en grade_items (self-healing) ──
            if ($gradeItemId >= 1000000) {
                $existingItem = $db->table('grade_items')
                    ->where('courseid', $courseId)
                    ->where('itemtype', 'mod')
                    ->where('itemmodule', $moduleType)
                    ->where('iteminstance', $instanceId)
                    ->first(['id']);
                
                if ($existingItem) {
                    $gradeItemId = (int) $existingItem->id;
                } else {
                    $cat = $db->table('grade_categories')
                        ->where('courseid', $courseId)
                        ->first(['id']);
                    $categoryId = $cat ? $cat->id : null;

                    $name = '';
                    try {
                        $inst = $db->table($moduleType)->where('id', $instanceId)->first(['name']);
                        if ($inst) {
                            $name = $inst->name;
                        }
                    } catch (\Exception $ex) {
                        $name = ucfirst($moduleType) . ' ' . $instanceId;
                    }

                    $gradeItemId = $db->table('grade_items')->insertGetId([
                        'courseid'         => $courseId,
                        'categoryid'       => $categoryId,
                        'itemname'         => $name,
                        'itemtype'         => 'mod',
                        'itemmodule'       => $moduleType,
                        'iteminstance'     => $instanceId,
                        'itemnumber'       => 0,
                        'gradetype'        => 1, // 1 = value, 0 = none, 2 = scale, 3 = text
                        'grademax'         => $nuevaNotaMax,
                        'grademin'         => 0,
                        'aggregationcoef'  => 0,
                        'timecreated'      => $now,
                        'timemodified'     => $now,
                    ]);
                }
            }

            // ── 2. Obtener método de agregación de la categoría del ítem ──
            $gi = $db->table('grade_items')->where('id', $gradeItemId)->first(['categoryid']);
            $aggregation = 13; // Natural por defecto
            if ($gi && $gi->categoryid) {
                $cat = $db->table('grade_categories')->where('id', $gi->categoryid)->first(['aggregation']);
                if ($cat) $aggregation = (int) $cat->aggregation;
            } else {
                $cat = $db->table('grade_categories')
                    ->where('courseid', $courseId)
                    ->whereNull('parent')
                    ->first(['aggregation']);
                if ($cat) $aggregation = (int) $cat->aggregation;
            }

            // ── 3. Actualizar grademax en grade_items y coeficiente de peso ──
            // Natural (13): aggregationcoef2 lleva el peso decimal; aggregationcoef es flag extra-crédito.
            // Weighted Mean (1): aggregationcoef lleva el peso decimal.
            // Suma (10) y otros: no hay ponderación nativa; no tocar aggregationcoef (flag extra-crédito).
            $pesoDecimal = round($nuevaNotaMax / 100, 10);

            $gradeItemUpdate = [
                'grademax'         => $nuevaNotaMax,
                'aggregationcoef2' => $pesoDecimal,
                'timemodified'     => $now,
            ];

            if ($aggregation === 1) {
                $gradeItemUpdate['aggregationcoef'] = $pesoDecimal;
                $gradeItemUpdate['weightoverride']  = 1;
            } elseif ($aggregation === 13) {
                $gradeItemUpdate['weightoverride'] = 1;
            }
            // Suma (10): NO tocar aggregationcoef ni weightoverride

            $db->table('grade_items')->where('id', $gradeItemId)->update($gradeItemUpdate);

            // ── 4. Actualizar grademax en la tabla del módulo (assign / quiz) ──
            $moduleTablesWithGrade = ['assign', 'quiz', 'workshop', 'lesson'];
            if (in_array($moduleType, $moduleTablesWithGrade)) {
                try {
                    $db->table($moduleType)->where('id', $instanceId)->update(['grade' => $nuevaNotaMax]);
                } catch (\Throwable $ex) {
                    \Log::warning("sincronizarPonderacion: no se pudo actualizar {$moduleType}.grade: " . $ex->getMessage());
                }
            }

            // ── 5. Rescalar o mantener notas de estudiantes en grade_grades y tablas del módulo ──
            if ($modo === 'ponderar' && $viejaNotaMax > 0 && $viejaNotaMax != $nuevaNotaMax) {
                $factor = $nuevaNotaMax / $viejaNotaMax;

                // Libro de calificaciones
                $db->table('grade_grades')
                    ->where('itemid', $gradeItemId)
                    ->whereNotNull('rawgrade')
                    ->update([
                        'rawgrade'     => DB::raw("ROUND(rawgrade * {$factor}, 5)"),
                        'finalgrade'   => DB::raw("CASE WHEN finalgrade IS NOT NULL THEN ROUND(finalgrade * {$factor}, 5) ELSE NULL END"),
                        'rawgrademax'  => $nuevaNotaMax,
                        'timemodified' => $now,
                        'information'  => null,
                    ]);

                // Tabla específica del módulo (casilla del calificador)
                if ($moduleType === 'assign') {
                    $db->table('assign_grades')
                        ->where('assignment', $instanceId)
                        ->whereNotNull('grade')
                        ->where('grade', '>=', 0)
                        ->update([
                            'grade'        => DB::raw("ROUND(grade * {$factor}, 5)"),
                            'timemodified' => $now,
                        ]);
                } elseif ($moduleType === 'quiz') {
                    $db->table('quiz_grades')
                        ->where('quiz', $instanceId)
                        ->whereNotNull('grade')
                        ->update([
                            'grade'        => DB::raw("ROUND(grade * {$factor}, 5)"),
                            'timemodified' => $now,
                        ]);
                }
            } else {
                // modo 'mantener': solo actualizar rawgrademax para consistencia
                $db->table('grade_grades')
                    ->where('itemid', $gradeItemId)
                    ->whereNotNull('rawgrade')
                    ->update([
                        'rawgrademax'  => $nuevaNotaMax,
                        'timemodified' => $now,
                    ]);
            }

            \Log::info("sincronizarPonderacion: gradeItemId={$gradeItemId} aggregation={$aggregation} modo={$modo} peso={$viejaNotaMax}→{$nuevaNotaMax}");

            $actualizados = 1;

            // ── 6. Invalidar caché del curso para que Moodle recalcule ──
            $db->table('course')->where('id', $courseId)->increment('cacherev');

        } catch (\Throwable $e) {
            \Log::error("sincronizarPonderacion gradeItemId={$gradeItemId}: " . $e->getMessage());
            $errores++;
        }

        return ['actualizados' => $actualizados, 'errores' => $errores];
    }
}
