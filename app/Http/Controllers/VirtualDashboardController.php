<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\Cuota;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Horario;
use App\Models\Inscripcione;
use App\Models\Modulo;
use App\Models\MoodleMatricula;
use App\Models\PagoRespaldo;
use App\Models\Pago;
use App\Services\MoodleService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VirtualDashboardController extends Controller
{
    public function __construct(private MoodleService $moodle) {}

    public function index()
    {
        $user    = Auth::user();
        $persona = $user->persona;

        $esEstudiante = false;
        $esDocente    = false;

        if ($persona) {
            $est = $persona->estudiante;
            if ($est) {
                $esEstudiante = Inscripcione::where('estudiante_id', $est->id)
                    ->whereIn('estado', ['activo', 'Activo', 'Inscrito', 'Confirmado'])
                    ->exists();
            }

            $doc = $persona->docente;
            if ($doc) {
                $esDocente = Modulo::where('docente_id', $doc->id)
                    ->whereNotNull('id')
                    ->exists();
            }
        }

        if (!$esEstudiante && !$esDocente) {
            return redirect('/')->with('error', 'No tienes acceso al portal.');
        }

        $perfilActivo = session('perfil_activo', $esEstudiante ? 'estudiante' : 'docente');

        $estudiante    = null;
        $inscripciones = collect();
        $estudioPrincipal = null;
        $ofertasCronograma = collect();
        $moodleUserId = null;

        if ($esEstudiante) {
            $estudiante = Estudiante::where('persona_id', $persona->id)->first();

            if ($estudiante) {
                $estudiante->load([
                    'persona.estudios.universidad',
                    'persona.estudios.profesion',
                    'persona.estudios.grado_academico',
                    'persona.ciudad.departamento',
                ]);

                $estudioPrincipal = $estudiante->persona?->estudios?->first();

                $inscripciones = Inscripcione::where('estudiante_id', $estudiante->id)
                    ->with([
                        'ofertaAcademica.programa',
                        'ofertaAcademica.posgrado',
                        'planesPago',
                        'moodleMatriculas.modulo.docente.persona',
                        'cuotas.pagosCuota.pago.trabajadorCargo.trabajador.persona',
                        'cuotas.pagosCuota.pago.detalles',
                        'cuotas.pagosCuota.pago.pagosCuotas.cuota',
                        'pagosRespaldos.cuotas',
                    ])
                    ->orderBy('fecha_registro', 'desc')
                    ->get();

                $inscripcionesIds = Inscripcione::where('estudiante_id', $estudiante->id)->pluck('id');
                $matricula = MoodleMatricula::whereIn('inscripcion_id', $inscripcionesIds)
                    ->whereNotNull('moodle_user_id')
                    ->first();
                $moodleUserId = $matricula?->moodle_user_id;

                // Si el estudiante no tiene cuenta Moodle pero también es docente, se revisará abajo

                $ofertasCronograma = \App\Models\OfertasAcademica::whereHas('inscripciones', function($q) use ($estudiante) {
                    $q->where('estudiante_id', $estudiante->id)
                     ->whereIn('estado', ['Inscrito', 'Confirmado']);
                })
                ->with([
                    'modulos.horarios.trabajadorCargo',
                    'modulos.horarios.enlaceVideollamada',
                    'modulos.horarios.reprogramado',
                    'modulos.horarios.reprogramado_a',
                    'modulos.docente.persona',
                    'programa'
                ])
                ->get()
                ->map(fn($oferta) => [
                    'id' => $oferta->id,
                    'codigo' => $oferta->codigo,
                    'nombre' => $oferta->programa?->nombre ?? $oferta->posgrado?->nombre ?? $oferta->codigo,
                    'n_modulos' => $oferta->modulos->count(),
                    'cantidad_sesiones' => $oferta->modulos->sum(fn($m) => $m->horarios->count()),
                    'modulos' => $oferta->modulos->map(fn($m) => [
                        'id' => $m->id,
                        'nombre' => $m->nombre,
                        'numero' => $m->n_modulo,
                        'color' => $m->color,
                        'docente' => $m->docente?->persona
                            ? trim($m->docente->persona->nombres . ' ' . $m->docente->persona->apellido_paterno)
                            : 'Sin asignar',
                        'docente_ci' => $m->docente?->persona?->carnet ?? null,
                        'sesiones_count' => $m->horarios->count(),
                        'moodle_course_id' => $m->moodle_course_id,
                        'sesiones' => $m->horarios->map(fn($s) => [
                            'id' => $s->id,
                            'titulo' => $m->nombre,
                            'start' => $s->fecha->format('Y-m-d') . 'T' . $s->hora_inicio,
                            'end' => $s->fecha->format('Y-m-d') . 'T' . $s->hora_fin,
                            'docente' => $m->docente?->persona
                                ? trim($m->docente->persona->nombres . ' ' . $m->docente->persona->apellido_paterno)
                                : 'Sin asignar',
                            'salon' => $s->trabajadorCargo?->trabajador?->persona?->nombres ?? 'Sin asignar',
                            'estado' => $s->estado ?? 'Confirmado',
                            'enlace_videollamada_url'    => $s->enlaceVideollamada?->enlace ?? '',
                            'enlace_videollamada_nombre' => $s->enlaceVideollamada?->nombre ?? '',
                            'enlace_grabacion'           => $s->enlace_grabacion ?? '',
                            'reprogramado_de_fecha'      => $s->reprogramado ? $s->reprogramado->fecha?->format('d/m/Y') : null,
                            'reprogramado_a_fecha'       => $s->reprogramado_a ? $s->reprogramado_a->fecha?->format('d/m/Y') : null,
                        ])
                    ])
                ]);
            }
        }

        $modulosDocente          = collect();
        $horariosDocente         = collect();
        $ofertasHorariosDocente  = collect();
        $moodleDocenteId         = null;

        if ($esDocente) {
            $docente = $persona->docente;
            if ($docente) {
                $modulosDocente = Modulo::where('docente_id', $docente->id)
                    ->with([
                        'ofertaAcademica.programa',
                        'ofertaAcademica.posgrado',
                        'horarios.enlaceVideollamada',
                        'horarios.reprogramado',
                        'horarios.reprogramado_a',
                    ])
                    ->get();

                // Moodle ID del docente (si tiene cuenta en Moodle)
                $moodleDocenteId = MoodleMatricula::where('docente_id', $docente->id)
                    ->whereNotNull('moodle_user_id')
                    ->value('moodle_user_id');
                if (!$moodleUserId) {
                    $moodleUserId = $moodleDocenteId;
                }

                // Horarios planos para compatibilidad con código existente
                $horariosDocente = $modulosDocente->flatMap(fn($m) => $m->horarios->map(fn($h) => [
                    'id'     => $h->id,
                    'titulo' => $m->nombre,
                    'start'  => $h->fecha->format('Y-m-d') . 'T' . $h->hora_inicio,
                    'end'    => $h->fecha->format('Y-m-d') . 'T' . $h->hora_fin,
                    'color'  => $m->color ?? '#6366f1',
                ]));

                // Datos estructurados por oferta para el nuevo tab Mi Horario
                $ofertasHorariosDocente = $modulosDocente
                    ->groupBy('ofertas_academica_id')
                    ->map(fn($modulos, $ofertaId) => [
                        'id'               => (int) $ofertaId,
                        'codigo'           => $modulos->first()->ofertaAcademica?->codigo ?? 'OF-' . $ofertaId,
                        'nombre'           => $modulos->first()->ofertaAcademica?->programa?->nombre
                                              ?? $modulos->first()->ofertaAcademica?->posgrado?->nombre
                                              ?? 'Oferta #' . $ofertaId,
                        'n_modulos'        => $modulos->count(),
                        'cantidad_sesiones'=> $modulos->sum(fn($m) => $m->horarios->count()),
                        'modulos'          => $modulos->values()->map(fn($m) => [
                            'id'              => $m->id,
                            'nombre'          => $m->nombre,
                            'numero'          => $m->n_modulo,
                            'color'           => $m->color,
                            'moodle_course_id'=> $m->moodle_course_id,
                            'sesiones_count'  => $m->horarios->count(),
                            'sesiones'      => $m->horarios->map(fn($s) => [
                                'id'                        => $s->id,
                                'titulo'                    => $m->nombre,
                                'start'                     => $s->fecha->format('Y-m-d') . 'T' . $s->hora_inicio,
                                'end'                       => $s->fecha->format('Y-m-d') . 'T' . $s->hora_fin,
                                'estado'                    => $s->estado ?? 'Confirmado',
                                'enlace_videollamada_url'    => $s->enlaceVideollamada?->enlace ?? '',
                                'enlace_videollamada_nombre' => $s->enlaceVideollamada?->nombre ?? '',
                                'enlace_grabacion'           => $s->enlace_grabacion ?? '',
                                'reprogramado_de_fecha'      => $s->reprogramado ? $s->reprogramado->fecha?->format('d/m/Y') : null,
                                'reprogramado_a_fecha'       => $s->reprogramado_a ? $s->reprogramado_a->fecha?->format('d/m/Y') : null,
                            ])->values(),
                        ])->values(),
                    ])
                    ->values();
            }
        }

        $bancos = Banco::where('estado', true)
            ->with(['cuentas' => fn($q) => $q->where('estado', true)])
            ->orderBy('nombre')
            ->get();

        return view('virtual.dashboard', compact(
            'user', 'persona', 'estudiante', 'inscripciones',
            'moodleUserId', 'moodleDocenteId', 'estudioPrincipal', 'ofertasCronograma',
            'esEstudiante', 'esDocente', 'perfilActivo',
            'modulosDocente', 'horariosDocente', 'ofertasHorariosDocente', 'bancos'
        ));
    }

    public function cambiarPerfil(Request $request)
    {
        $request->validate([
            'perfil' => 'required|in:estudiante,docente',
        ]);

        session(['perfil_activo' => $request->perfil]);

        return response()->json(['success' => true]);
    }

    public function actividades($moduloId)
    {
        $user    = Auth::user();
        $persona = $user->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Perfil no encontrado.'], 404);
        }

        $estudiante = Estudiante::where('persona_id', $persona->id)->first();
        if (!$estudiante) {
            return response()->json(['success' => false, 'message' => 'No estás registrado como estudiante.'], 403);
        }

        $inscripcionesIds = Inscripcione::where('estudiante_id', $estudiante->id)->pluck('id');

        $matricula = MoodleMatricula::where('modulo_id', $moduloId)
            ->whereIn('inscripcion_id', $inscripcionesIds)
            ->whereNotNull('moodle_course_id')
            ->whereNotNull('moodle_user_id')
            ->first();

        if (!$matricula) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes matrícula Moodle activa en este módulo.',
            ], 403);
        }

        try {
            $contenidos     = $this->moodle->getCourseContentsWithDetails($matricula->moodle_course_id);
            $calificaciones = $this->moodle->getStudentGrades($matricula->moodle_course_id, [$matricula->moodle_user_id]);
            $entregas       = $this->moodle->getStudentAssignSubmissions($matricula->moodle_course_id, $matricula->moodle_user_id);
            $archivosSubidos = $this->moodle->getStudentAssignFiles($matricula->moodle_course_id, $matricula->moodle_user_id);

            // Datos de fechas por tipo de actividad (igual que vista admin)
            $tareas        = $this->moodle->getAssignments($matricula->moodle_course_id);
            $cuestionarios = $this->moodle->getQuizzes($matricula->moodle_course_id);
            $foros         = $this->moodle->getForums($matricula->moodle_course_id);
            $tareasFechas  = $this->moodle->getAssignDatesByCourseDirect($matricula->moodle_course_id);

            // Fallback si la BD directa no devuelve fechas
            if (empty($tareasFechas)) {
                foreach ($tareas as $t) {
                    $open  = (int) ($t['allowsubmissionsfromdate'] ?? 0);
                    $due   = (int) ($t['duedate']                  ?? 0);
                    $entry = ['open' => $open ?: null, 'due' => $due ?: null];
                    if (!empty($t['id']))           $tareasFechas[(int) $t['id']]                   = $entry;
                    if (!empty($t['coursemodule'])) $tareasFechas['cm_' . (int) $t['coursemodule']] = $entry;
                }
            }

            // Recolectar foros para saber si el usuario participó
            $forumCms = [];
            foreach ($contenidos as $seccion) {
                foreach ($seccion['modules'] ?? [] as $mod) {
                    if (($mod['modname'] ?? '') === 'forum' && !empty($mod['id'])) {
                        $cm = (object) ['id' => $mod['id'], 'instance' => $mod['instance'] ?? 0];
                        $forumCms[] = $cm;
                    }
                }
            }
            $forosParticipacion = !empty($forumCms)
                ? $this->moodle->getForumParticipationMap($forumCms, $matricula->moodle_user_id)
                : [];
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo conectar con Moodle en este momento.',
            ], 500);
        }

        return response()->json([
            'success'             => true,
            'contenidos'          => $contenidos,
            'calificaciones'      => $calificaciones,
            'entregas'            => $entregas,
            'archivos_subidos'    => $archivosSubidos,
            'foros_participacion' => $forosParticipacion,
            'tareas_fechas'       => $tareasFechas,
            'cuestionarios'       => $cuestionarios,
            'foros'               => $foros,
        ]);
    }

    public function moodleSso(Request $request)
    {
        $target     = $request->query('target', '');
        $moodleBase = rtrim(config('moodle.url'), '/');
        $fallback   = $moodleBase . '/login/index.php' . ($target ? '?wantsurl=' . urlencode($target) : '');

        $wantsJson = $request->expectsJson() || $request->header('Accept') === 'application/json';

        \Log::info('moodleSso inicio', ['target' => $target, 'expectsJson' => $wantsJson]);

        if (!$target || !str_starts_with($target, $moodleBase)) {
            \Log::warning('moodleSso: target inválido', ['target' => $target, 'moodleBase' => $moodleBase]);
            return $wantsJson
                ? response()->json(['error' => 'URL inválida', 'redirectUrl' => $fallback])
                : redirect($fallback);
        }

        $user    = Auth::user();
        $persona = $user->persona;

        if (!$persona || !$user->username) {
            \Log::warning('moodleSso: sin persona o username', ['user_id' => $user->id]);
            return $wantsJson
                ? response()->json(['error' => 'Sin perfil', 'redirectUrl' => $fallback])
                : redirect($fallback);
        }

        // Obtener moodle_user_id desde moodle_matriculas (fuente canónica)
        $estudiante = Estudiante::where('persona_id', $persona->id)->first();
        if (!$estudiante) {
            \Log::warning('moodleSso: sin registro de estudiante', ['user_id' => $user->id]);
            return $wantsJson
                ? response()->json(['error' => 'Sin perfil estudiante', 'redirectUrl' => $fallback])
                : redirect($fallback);
        }
        $inscripcionesIds = Inscripcione::where('estudiante_id', $estudiante->id)->pluck('id');
        $moodleUserId = MoodleMatricula::whereIn('inscripcion_id', $inscripcionesIds)
            ->whereNotNull('moodle_user_id')
            ->value('moodle_user_id');

        if (!$moodleUserId) {
            \Log::warning('moodleSso: sin moodle_user_id');
            return $wantsJson
                ? response()->json(['error' => 'Sin usuario Moodle', 'redirectUrl' => $fallback])
                : redirect($fallback);
        }

        // Intentar obtener token probando ambos formatos de contraseña
        $carnet   = $persona->carnet ?? '';
        $pwdForms = [
            $this->derivarPasswordMoodle($carnet),
            (function ($c) {
                $d = preg_replace('/[^0-9]/', '', $c);
                return strlen($d) >= 7 ? $d : 'innova' . $d;
            })($carnet),
        ];

        $tokens   = null;
        $password = '';
        foreach ($pwdForms as $attempt) {
            $t = $this->moodle->getUserToken($user->username, $attempt, 'moodle_mobile_app');
            if ($t && !empty($t['token'])) {
                $tokens   = $t;
                $password = $attempt;
                break;
            }
        }

        \Log::info('moodleSso: resultado getUserToken', [
            'username'     => $user->username,
            'moodleUserId' => $moodleUserId,
            'token_ok'     => $tokens !== null,
            'pwd_len'      => strlen($password),
            'pwd_prefix'   => $password ? (substr($password, 0, 3) . '***') : 'none',
        ]);

        if (!$tokens) {
            \Log::error('moodleSso: getUserToken falló con todas las contraseñas', ['username' => $user->username]);
            return $wantsJson
                ? response()->json(['error' => 'Sin token Moodle', 'redirectUrl' => $fallback])
                : redirect($fallback);
        }

        $hasPrivateToken = !empty($tokens['privatetoken']);
        \Log::info('moodleSso: token obtenido', ['has_privatetoken' => $hasPrivateToken]);

        // Intentar autologin con privatetoken
        if ($hasPrivateToken) {
            $key = $this->moodle->getAutoLoginKey($tokens['token'], $tokens['privatetoken']);
            if ($key) {
                $redirectUrl = $moodleBase . '/admin/tool/mobile/autologin.php'
                    . '?userid=' . $moodleUserId
                    . '&key=' . urlencode($key)
                    . '&urltogo=' . urlencode($target);
                \Log::info('moodleSso: autologin key exitosa');
                return $wantsJson
                    ? response()->json(['redirectUrl' => $redirectUrl])
                    : redirect($redirectUrl);
            }
            \Log::warning('moodleSso: getAutoLoginKey devolvió null');
        }

        // Fallback: autologin por formulario (no requiere privatetoken)
        if (!$wantsJson) {
            try {
                $loginPage = Http::timeout(10)->get($moodleBase . '/login/index.php');
                $html = $loginPage->body();

                // Extraer logintoken sin importar el orden de atributos del input
                $logintoken = '';
                if (preg_match('/<input[^>]+name=["\']logintoken["\'][^>]*>/i', $html, $inputTag)) {
                    preg_match('/value=["\']([^"\']*)["\']/', $inputTag[0], $valMatch);
                    $logintoken = $valMatch[1] ?? '';
                }

                \Log::info('moodleSso: form-based', [
                    'logintoken_found' => $logintoken !== '',
                    'logintoken_len'   => strlen($logintoken),
                    'http_status'      => $loginPage->status(),
                ]);

                if ($logintoken) {
                    \Log::info('moodleSso: usando form-based autologin');
                    return view('estudiante.moodle-autologin', [
                        'action'     => $moodleBase . '/login/index.php',
                        'username'   => $user->username,
                        'password'   => $password,
                        'logintoken' => $logintoken,
                        'wantsurl'   => $target,
                    ]);
                }
                \Log::warning('moodleSso: no se pudo extraer logintoken de Moodle');
            } catch (\Exception $e) {
                \Log::error('moodleSso: form-based autologin falló', ['error' => $e->getMessage()]);
            }
        }

        // Último recurso: login page de Moodle
        \Log::warning('moodleSso: redirigiendo a login manual', ['has_privatetoken' => $hasPrivateToken]);
        $loginUrl = $moodleBase . '/login/index.php?wantsurl=' . urlencode($target);
        return $wantsJson
            ? response()->json(['redirectUrl' => $loginUrl])
            : redirect($loginUrl);
    }

    /**
     * Replica la lógica de MoodleMatriculaController::generarPasswordSegura()
     * para obtener la contraseña con la que fue creada la cuenta Moodle.
     */
    private function derivarPasswordMoodle(string $carnet): string
    {
        $base    = preg_replace('/[^a-zA-Z0-9]/', '', $carnet ?: '');
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

    public function getCuotasPendientes(int $id)
    {
        $user    = Auth::user();
        $persona = $user->persona;

        $estudiante = $persona ? Estudiante::where('persona_id', $persona->id)->first() : null;
        if (!$estudiante) {
            return response()->json(['success' => false, 'error' => 'Sin acceso'], 403);
        }

        $inscripcion = Inscripcione::where('id', $id)
            ->where('estudiante_id', $estudiante->id)
            ->with(['cuotas', 'planesPago'])
            ->first();

        if (!$inscripcion) {
            return response()->json(['success' => false, 'error' => 'Inscripción no encontrada'], 404);
        }

        $cuotasPendientes = $inscripcion->cuotas->filter(fn ($c) => (float) $c->pago_pendiente_bs > 0);

        return response()->json([
            'success' => true,
            'grupo'   => [
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
            ],
        ]);
    }

    public function subirComprobante(Request $request)
    {
        $request->validate([
            'inscripcion_id' => 'required|integer|exists:inscripciones,id',
            'archivo'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'observaciones'  => 'nullable|string|max:500',
            'cuotas'         => 'required|array|min:1',
            'cuotas.*'       => 'integer|exists:cuotas,id',
        ]);

        $user    = Auth::user();
        $persona = $user->persona;
        $estudiante = $persona ? Estudiante::where('persona_id', $persona->id)->first() : null;

        if (!$estudiante) {
            return response()->json(['success' => false, 'message' => 'Sin acceso'], 403);
        }

        $inscripcion = Inscripcione::where('id', $request->inscripcion_id)
            ->where('estudiante_id', $estudiante->id)
            ->first();

        if (!$inscripcion) {
            return response()->json(['success' => false, 'message' => 'Inscripción no encontrada.'], 404);
        }

        $cuotasIds = Cuota::where('inscripcione_id', $inscripcion->id)
            ->whereIn('id', $request->cuotas)
            ->pluck('id');

        if ($cuotasIds->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Las cuotas no pertenecen a esta inscripción.'], 422);
        }

        $dir = public_path('storage/comprobantes');
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $ext           = $request->file('archivo')->getClientOriginalExtension();
        $nombreArchivo = 'comprobante_est_' . $inscripcion->id . '_' . time() . '.' . $ext;
        $request->file('archivo')->move($dir, $nombreArchivo);

        $comprobante = PagoRespaldo::create([
            'inscripcione_id' => $inscripcion->id,
            'archivo'         => $nombreArchivo,
            'observaciones'   => $request->observaciones,
            'estado'          => 'pendiente',
        ]);

        $comprobante->cuotas()->attach($cuotasIds);

        return response()->json([
            'success' => true,
            'mensaje' => 'Comprobante enviado correctamente. Será verificado a la brevedad.',
        ]);
    }

    public function moodleFile(Request $request)
    {
        $url      = $request->query('url', '');
        $download = $request->boolean('download');

        if (!$url) abort(404);

        $moodleBase = rtrim(config('moodle.url'), '/');
        if (!str_starts_with($url, $moodleBase)) abort(403);
        if (!str_contains($url, 'pluginfile.php')) abort(403);

        if (!str_contains($url, 'token=')) {
            $sep = str_contains($url, '?') ? '&' : '?';
            $url .= $sep . 'token=' . config('moodle.token');
        }

        try {
            $response = Http::timeout(30)->get($url);
            if (!$response->successful()) abort(404);

            $contentType = $response->header('Content-Type') ?? 'application/octet-stream';
            $filename    = rawurldecode(basename(parse_url($url, PHP_URL_PATH)));
            $disposition = $download ? 'attachment' : 'inline';

            return response($response->body(), 200, [
                'Content-Type'        => $contentType,
                'Content-Disposition' => $disposition . '; filename="' . $filename . '"',
                'Cache-Control'       => 'private, max-age=3600',
            ]);
        } catch (\Exception) {
            abort(500);
        }
    }

    public function generarReciboPdf($pagoId)
    {
        $user = Auth::user();
        $persona = $user->persona;
        
        $estudiante = Estudiante::where('persona_id', $persona->id)->first();

        if (!$estudiante) {
            abort(403, 'No tienes acceso a esta función');
        }

        $pago = Pago::with([
            'trabajadorCargo.trabajador.persona',
            'detalles',
            'pagosCuotas.cuota.inscripcion.estudiante.persona',
            'pagosCuotas.cuota.inscripcion.ofertaAcademica.posgrado',
            'pagosCuotas.cuota.inscripcion.planesPago',
            'pagosCuotas.cuota.inscripcion.ofertaAcademica.sucursal.sede'
        ])->findOrFail($pagoId);

        $esPropietario = $pago->pagosCuotas->contains(function ($pc) use ($estudiante) {
            return $pc->cuota->inscripcion->estudiante_id === $estudiante->id;
        });

        if (!$esPropietario) {
            abort(403, 'No tienes acceso a este recibo');
        }

        $pdf = Pdf::loadView('admin.estudiantes.recibo', [
            'pago' => $pago,
        ]);

        if (request()->query('inline')) {
            return $pdf->stream('recibo-' . ($pago->recibo ?? $pago->id) . '.pdf');
        }

        return $pdf->download('recibo-' . ($pago->recibo ?? $pago->id) . '.pdf');
    }

    public function docenteModulo(int $moduloId)
    {
        // La autorización (docente dueño del módulo) ya fue verificada por CheckDocenteModule
        $modulo = Modulo::with([
                'docente.persona',
                'horarios',
                'ofertaAcademica.programa',
                'ofertaAcademica.posgrado',
            ])
            ->findOrFail($moduloId);

        $ofertaId = $modulo->ofertas_academica_id;

        $inscripciones = Inscripcione::where('ofertas_academica_id', $ofertaId)
            ->whereIn('estado', ['Inscrito', 'Confirmado', 'activo', 'Activo'])
            ->with(['estudiante.persona', 'matriculaciones'])
            ->get();

        $moodleMatriculas = MoodleMatricula::where('modulo_id', $moduloId)
            ->get()
            ->keyBy('inscripcion_id');

        $inscritos = [];
        foreach ($inscripciones as $inscripcion) {
            $matricula       = $inscripcion->matriculaciones()->where('modulo_id', $moduloId)->first();
            $moodleMatricula = $moodleMatriculas->get($inscripcion->id);

            $inscritos[] = [
                'id'                 => $inscripcion->id,
                'estudiante_nombre'  => $inscripcion->estudiante?->persona
                    ? trim(($inscripcion->estudiante->persona->nombres ?? '') . ' '
                        . ($inscripcion->estudiante->persona->apellido_paterno ?? '') . ' '
                        . ($inscripcion->estudiante->persona->apellido_materno ?? ''))
                    : 'Sin nombre',
                'estudiante_ci'      => $inscripcion->estudiante?->persona?->carnet ?? '—',
                'celular'            => $inscripcion->estudiante?->persona?->celular ?? '—',
                'correo'             => $inscripcion->estudiante?->persona?->correo ?? '—',
                'matriculado'        => $matricula !== null,
                'en_moodle'          => $moodleMatricula !== null && $moodleMatricula->moodle_user_id !== null,
                'acceso_suspendido'  => (bool) ($moodleMatricula?->acceso_suspendido),
                'tiene_cuenta_moodle'=> !empty($inscripcion->moodle_user_id),
            ];
        }

        return view('virtual.docente-modulo', compact('modulo', 'inscritos', 'ofertaId'));
    }
}
