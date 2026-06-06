<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\OfertasAcademica;
use App\Models\Docente;
use App\Models\Persona;
use App\Models\Horario;
use App\Models\Estudio;
use App\Models\TrabajadoresCargo;
use App\Models\MoodleMatricula;
use App\Models\Inscripcione;
use App\Models\Matriculacione;
use App\Models\User;
use App\Models\EnlaceVideollamada;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ModuloController extends Controller
{
    public function __construct(private MoodleService $moodle) {}

    public function detalle($ofertaId, $moduloId)
    {
        $modulo = Modulo::with(['docente.persona', 'horarios', 'oferta_academica.programa', 'oferta_academica'])
            ->where('ofertas_academica_id', $ofertaId)
            ->findOrFail($moduloId);

        $inscripciones = Inscripcione::select('inscripciones.*')
            ->where('ofertas_academica_id', $ofertaId)
            ->where('estado', 'Inscrito')
            ->join('estudiantes', 'inscripciones.estudiante_id', '=', 'estudiantes.id')
            ->join('personas', 'estudiantes.persona_id', '=', 'personas.id')
            ->orderBy('personas.apellido_paterno', 'asc')
            ->orderBy('personas.apellido_materno', 'asc')
            ->orderBy('personas.nombres', 'asc')
            ->with([
                'estudiante.persona.ciudad.departamento',
                'estudiante.persona.estudios.grado_academico',
                'estudiante.persona.estudios.profesion',
                'estudiante.persona.estudios.universidad',
                'trabajador_cargo',
                'matriculaciones',
                'planesPago'
            ])
            ->get();

        $moodleMatriculas = MoodleMatricula::where('modulo_id', $moduloId)
            ->get()
            ->keyBy('inscripcion_id');

        $inscritos = [];
        foreach ($inscripciones as $inscripcion) {
            $matricula = $inscripcion->matriculaciones()->where('modulo_id', $moduloId)->first();
            $moodleMatricula = $moodleMatriculas->get($inscripcion->id);

            $inscripcionMoodleUserId = $inscripcion->moodle_user_id;
            $matriculaMoodleUserId = $moodleMatricula?->moodle_user_id;
            $effectiveMoodleUserId = $inscripcionMoodleUserId ?: $matriculaMoodleUserId;

            $enrolledInMoodle = false;
            if ($effectiveMoodleUserId && $modulo->moodle_course_id) {
                try {
                    $enrolledInMoodle = $this->moodle->isUserEnrolledInCourse(
                        (int) $effectiveMoodleUserId,
                        (int) $modulo->moodle_course_id
                    );
                } catch (\Exception $e) {
                    Log::warning("detalle: error checking moodle enrollment for user {$effectiveMoodleUserId}: " . $e->getMessage());
                }
            }

            $persona = $inscripcion->estudiante?->persona;
            $estudios = $persona?->estudios ?? collect();
            $estudiosData = $estudios->map(function ($e) {
                $grado     = $e->grado_academico?->nombre ?? '';
                $profesion = $e->profesion?->nombre ?? '';
                $universidad = $e->universidad?->nombre ?? '';
                $parts = array_filter([$grado, $profesion, $universidad], fn($v) => $v && trim($v) !== '');
                return [
                    'grado' => $grado,
                    'profesion' => $profesion,
                    'universidad' => $universidad,
                    'estado' => $e->estado,
                    'principal' => (bool) $e->principal,
                    'texto' => implode(' — ', $parts),
                ];
            })->toArray();

            $inscritos[] = [
                'id' => $inscripcion->id,
                'estudiante_id' => $inscripcion->estudiante_id,
                'estudiante_nombre' => $persona
                    ? trim(($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '') . ' ' . ($persona->nombres ?? ''))
                    : 'Sin nombre',
                'nombres' => $persona->nombres ?? '',
                'apellido_paterno' => $persona->apellido_paterno ?? '',
                'apellido_materno' => $persona->apellido_materno ?? '',
                'estudiante_ci' => $persona->carnet ?? '—',
                'celular' => $persona->celular ?? '—',
                'correo' => $persona->correo ?? '—',
                'departamento' => $persona?->ciudad?->departamento?->nombre ?? '—',
                'ciudad' => $persona?->ciudad?->nombre ?? '—',
                'sexo' => $persona?->sexo ?? '—',
                'fecha_nacimiento' => $persona?->fecha_nacimiento
                    ? (\Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y'))
                    : '—',
                'estado_civil' => $persona?->estado_civil ?? '—',
                'plan_pago' => $inscripcion->planesPago?->nombre ?? '—',
                'estudios' => $estudiosData,
                'matriculado' => $matricula !== null,
                'nota_regular' => $matricula?->nota_regular,
                'nota_nivelacion' => $matricula?->nota_nivelacion,
                'matricula_id' => $matricula?->id,
                'en_moodle' => $enrolledInMoodle,
                'moodle_user_id' => $effectiveMoodleUserId,
                'acceso_suspendido' => (bool) ($moodleMatricula?->acceso_suspendido),
                'tiene_cuenta_moodle' => !empty($effectiveMoodleUserId),
            ];
        }

        return view('admin.ofertas-academicas.modulo-detalle', [
            'modulo' => $modulo,
            'inscritos' => $inscritos,
            'ofertaId' => $ofertaId,
        ]);
    }

    public function listar($ofertaId)
    {
        try {
            $modulos = Modulo::with([
                'docente.persona',
                'enlaceVideollamada.cuenta',
                'horarios.reprogramado',
                'horarios.reprogramado_a',
                'horarios.trabajadorCargo.trabajador.persona',
                'horarios.enlaceVideollamada',
            ])
                ->where('ofertas_academica_id', $ofertaId)
                ->orderBy('nombre', 'asc')
                ->get();

            $data = $modulos->map(function ($modulo) {
                $docenteNombre = '';
                if ($modulo->docente && $modulo->docente->persona) {
                    $p = $modulo->docente->persona;
                    $docenteNombre = trim(($p->nombres ?? '') . ' ' . ($p->apellido_paterno ?? '') . ' ' . ($p->apellido_materno ?? ''));
                }

                return [
                    'id' => $modulo->id,
                    'n_modulo' => $modulo->n_modulo,
                    'nombre' => $modulo->nombre,
                    'color' => $modulo->color,
                    'fecha_inicio' => $modulo->fecha_inicio ? $modulo->fecha_inicio->format('Y-m-d') : null,
                    'fecha_fin' => $modulo->fecha_fin ? $modulo->fecha_fin->format('Y-m-d') : null,
                    'estado' => $modulo->estado,
                    'moodle_course_id' => $modulo->moodle_course_id,
                    'enlace_videollamada_id'     => $modulo->enlace_videollamada_id,
                    'enlace_videollamada_nombre' => $modulo->enlaceVideollamada?->nombre,
                    'enlace_videollamada_url'    => $modulo->enlaceVideollamada?->enlace,
                    'enlace_videollamada_cuenta' => $modulo->enlaceVideollamada?->cuenta?->nombre,
                    'docente' => $modulo->docente ? [
                        'id' => $modulo->docente->id,
                        'persona' => [
                            'id' => $modulo->docente->persona->id,
                            'nombres' => $modulo->docente->persona->nombres,
                            'apellido_paterno' => $modulo->docente->persona->apellido_paterno,
                            'apellido_materno' => $modulo->docente->persona->apellido_materno,
                            'carnet' => $modulo->docente->persona->carnet,
                        ],
                    ] : null,
                    'docente_nombre' => $docenteNombre,
                    'horarios' => $modulo->horarios->map(function ($h) use ($modulo) {
                        return [
                            'id' => $h->id,
                            'fecha' => $h->fecha ? $h->fecha->format('Y-m-d') : '',
                            'hora_inicio' => $h->hora_inicio,
                            'hora_fin' => $h->hora_fin,
                            'estado' => $h->estado ?? 'Confirmado',
                            'color' => $h->color ?? $modulo->color,
                            'trabajadores_cargo_id' => $h->trabajadores_cargo_id,
                            'enlace_videollamada_url'    => $h->enlaceVideollamada?->enlace,
                            'enlace_videollamada_nombre' => $h->enlaceVideollamada?->nombre,
                            'enlace_grabacion'           => $h->enlace_grabacion,
                            'trabajador_cargo' => $h->trabajadorCargo ? [
                                'id'          => $h->trabajadorCargo->id,
                                'nombre_cargo' => $h->trabajadorCargo->nombre_cargo,
                                'trabajador'  => $h->trabajadorCargo->trabajador && $h->trabajadorCargo->trabajador->persona ? [
                                    'persona' => [
                                        'nombres'          => $h->trabajadorCargo->trabajador->persona->nombres,
                                        'apellido_paterno' => $h->trabajadorCargo->trabajador->persona->apellido_paterno,
                                        'apellido_materno' => $h->trabajadorCargo->trabajador->persona->apellido_materno,
                                    ],
                                ] : null,
                            ] : null,
                            'reprogramado_de_fecha' => $h->reprogramado ? $h->reprogramado->fecha?->format('d/m/Y') : null,
                            'reprogramado_a_fecha' => $h->reprogramado_a ? $h->reprogramado_a->fecha?->format('d/m/Y') : null,
                        ];
                    }),
                ];
            });

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function buscarDocente(Request $request)
    {
        try {
            $carnet = strtoupper($request->carnet);
            $moduloId = $request->modulo_id;
            
            $persona = Persona::where('carnet', $carnet)->first();
            
            if (!$persona) {
                return response()->json([
                    'not_found' => true,
                    'message' => 'No se encontró ninguna persona con el carnet: ' . $carnet
                ]);
            }
            
            $docente = Docente::where('persona_id', $persona->id)->first();
            
            if ($docente) {
                $nombreCompleto = trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''));
                
                $moodleResult = null;
                if ($moduloId) {
                    $modulo = Modulo::with('oferta_academica.programa')->find($moduloId);
                    if ($modulo) {
                        $moodleResult = $this->matricularDocenteEnMoodle($docente, $modulo);
                    }
                }
                
                return response()->json([
                    'es_docente' => true,
                    'docente' => [
                        'id' => $docente->id,
                        'nombre' => $nombreCompleto,
                        'carnet' => $persona->carnet,
                        'persona_id' => $persona->id,
                    ],
                    'persona' => [
                        'id' => $persona->id,
                        'nombre' => $nombreCompleto,
                        'carnet' => $persona->carnet,
                        'correo' => $persona->correo,
                    ],
                    'moodle_result' => $moodleResult,
                ]);
            }
            
            $nombreCompleto = trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''));

            $persona->load(['ciudad.departamento', 'estudios.grado_academico', 'estudios.profesion', 'estudios.universidad']);

            $estudios = $persona->estudios->map(function ($est) {
                return [
                    'grado_academico_id'   => $est->grados_academico_id,
                    'grado_academico_nombre' => $est->grado_academico?->nombre,
                    'profesion_id'         => $est->profesione_id,
                    'profesion_nombre'     => $est->profesion?->nombre,
                    'universidad_id'       => $est->universidade_id,
                    'universidad_nombre'   => $est->universidad?->nombre,
                    'estado'               => $est->estado,
                    'principal'            => (bool) $est->principal,
                ];
            });

            return response()->json([
                'persona_encontrada' => true,
                'persona' => [
                    'id'               => $persona->id,
                    'nombre'           => $nombreCompleto,
                    'nombres'          => $persona->nombres,
                    'apellido_paterno' => $persona->apellido_paterno,
                    'apellido_materno' => $persona->apellido_materno,
                    'carnet'           => $persona->carnet,
                    'expedido'         => $persona->expedido,
                    'correo'           => $persona->correo,
                    'celular'          => $persona->celular,
                    'telefono'         => $persona->telefono,
                    'direccion'        => $persona->direccion,
                    'fecha_nacimiento' => $persona->fecha_nacimiento,
                    'sexo'             => $persona->sexo,
                    'estado_civil'     => $persona->estado_civil,
                    'ciudad_id'        => $persona->ciudade_id,
                    'departamento_id'  => $persona->ciudad?->departamento?->id,
                    'estudios'         => $estudios,
                ],
                'message' => 'La persona existe pero no está registrada como docente.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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

    private function generarUsernameDocente($nombres, $apellidoPaterno, $apellidoMaterno)
    {
        $nombre = $this->normalizarTexto(trim($nombres));
        $ap     = $this->normalizarTexto(trim($apellidoPaterno));
        $am     = $this->normalizarTexto(trim($apellidoMaterno));

        $parts = explode(' ', $nombre);
        $primerNombre   = $parts[0] ?? '';
        $segundaInicial = isset($parts[1]) ? substr($parts[1], 0, 1) : '';

        $username1 = substr($primerNombre, 0, 1) . $ap . $am;
        $username2 = $segundaInicial . $ap . $am;
        $username3 = $primerNombre . $ap . $am;

        $username1 = substr(preg_replace('/[^a-z0-9]/', '', $username1), 0, 20);
        $username2 = substr(preg_replace('/[^a-z0-9]/', '', $username2), 0, 20);
        $username3 = substr(preg_replace('/[^a-z0-9]/', '', $username3), 0, 20);

        if ($username1 && !User::where('username', $username1)->exists()) {
            return $username1;
        }
        if ($username2 && !User::where('username', $username2)->exists()) {
            return $username2;
        }
        if ($username3 && !User::where('username', $username3)->exists()) {
            return $username3;
        }

        return ($username1 ?: 'docente') . rand(1, 99);
    }

    private function generarPasswordDocente($carnet): string
    {
        $soloNumeros = preg_replace('/[^0-9]/', '', $carnet ?? '');
        if (strlen($soloNumeros) >= 7) {
            return $soloNumeros;
        }
        return str_pad($soloNumeros ?: '1234567', 7, '0', STR_PAD_RIGHT);
    }

    private function crearCuentasDocente(Persona $persona, string $username, string $password): array
    {
        $result = ['sistema' => false, 'moodle' => false, 'moodle_user_id' => null, 'message' => ''];

        $nombreCompleto = trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''));

        $existingUser = User::where('persona_id', $persona->id)
            ->orWhere('username', $username)
            ->orWhere('email', $persona->correo)
            ->first();

        if (!$existingUser && $persona->correo) {
            User::create([
                'name'           => $nombreCompleto,
                'username'       => $username,
                'email'          => $persona->correo,
                'password'       => $password,
                'role'           => 'moodle',
                'acceso_admin'   => false,
                'acceso_virtual' => true,
                'estado'         => 'Activo',
                'persona_id'     => $persona->id,
            ]);
            $result['sistema'] = true;
        }

        try {
            $moodleService = app(\App\Services\MoodleService::class);

            // 1) Buscar por username
            $existingMoodleUser = $moodleService->getUserByField('username', $username);
            $moodleUserId       = $existingMoodleUser ? (int) $existingMoodleUser['id'] : null;

            // 2) Fallback: buscar por correo
            if (!$moodleUserId && $persona->correo) {
                $byEmail      = $moodleService->getUserByField('email', $persona->correo);
                $moodleUserId = $byEmail ? (int) $byEmail['id'] : null;
            }

            if ($moodleUserId) {
                $result['moodle']         = true;
                $result['moodle_user_id'] = $moodleUserId;
            } else {
                $firstname    = trim($persona->nombres ?? '') ?: 'Docente';
                $lastname     = trim(($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) ?: 'Sin Apellido';
                $email        = $persona->correo ?: "{$username}@innova.edu.bo";
                $moodleUserId = $moodleService->createUser($username, $password, $firstname, $lastname, $email);
                if ($moodleUserId) {
                    $result['moodle']         = true;
                    $result['moodle_user_id'] = $moodleUserId;
                }
            }
        } catch (\Exception $e) {
            $result['message'] = 'Error con Moodle: ' . $e->getMessage();
        }

        return $result;
    }

    public function registrarDocenteCompleto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'persona_id'       => 'nullable|exists:personas,id',
            'modulo_id'        => 'nullable|exists:modulos,id',
            'carnet'           => 'required|string|max:20',
            'expedido'         => 'nullable|string|max:10',
            'nombres'          => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'correo'           => 'nullable|email|max:150',
            'celular'          => 'nullable|string|max:20',
            'telefono'         => 'nullable|string|max:20',
            'direccion'        => 'nullable|string|max:200',
            'fecha_nacimiento' => 'nullable|date',
            'sexo'             => 'nullable|in:M,F',
            'estado_civil'     => 'nullable|in:Soltero/a,Casado/a,Divorciado/a,Viudo/a,Unión Libre',
            'ciudade_id'       => 'nullable|exists:ciudades,id',
            'estudios'         => 'nullable|array',
            'estudios.*.grado_id' => 'required_with:estudios|exists:grados_academicos,id',
            'estudios.*.profesion_id' => 'nullable|exists:profesiones,id',
            'estudios.*.universidad_id' => 'nullable|exists:universidades,id',
            'estudios.*.estado' => 'required_with:estudios|string|max:50',
            'estudios.*.principal' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        if ($request->persona_id) {
            $existente = Docente::where('persona_id', $request->persona_id)->first();
            if ($existente) {
                $persona = Persona::find($request->persona_id);
                $moodleResult = null;
                if ($request->modulo_id) {
                    $modulo = Modulo::find($request->modulo_id);
                    if ($modulo) {
                        $moodleResult = $this->matricularDocenteEnMoodle($existente, $modulo);
                    }
                }
                return response()->json([
                    'success' => true,
                    'message' => 'El docente ya existe.',
                    'docente' => [
                        'id' => $existente->id,
                        'persona' => [
                            'id' => $persona->id,
                            'carnet' => $persona->carnet,
                            'nombres' => $persona->nombres,
                            'apellido_paterno' => $persona->apellido_paterno,
                            'apellido_materno' => $persona->apellido_materno,
                        ],
                    ],
                    'moodle_result' => $moodleResult,
                ]);
            }

            $docente = Docente::create([
                'persona_id' => $request->persona_id,
            ]);

            $this->registrarEstudios($request->persona_id, $request->estudios);

            $persona = Persona::find($request->persona_id);
            
            $username = $this->generarUsernameDocente($persona->nombres, $persona->apellido_paterno, $persona->apellido_materno);
            $password = $this->generarPasswordDocente($persona->carnet);
            $cuentaResult = $this->crearCuentasDocente($persona, $username, $password);

            $moodleResult = null;
            if ($request->modulo_id) {
                $modulo = Modulo::find($request->modulo_id);
                if ($modulo) {
                    $moodleResult = $this->matricularDocenteEnMoodle(
                        $docente, $modulo, $cuentaResult['moodle_user_id']
                    );
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Docente registrado correctamente.',
                'docente' => [
                    'id' => $docente->id,
                    'persona' => [
                        'id' => $persona->id,
                        'carnet' => $persona->carnet,
                        'nombres' => $persona->nombres,
                        'apellido_paterno' => $persona->apellido_paterno,
                        'apellido_materno' => $persona->apellido_materno,
                    ],
                ],
                'moodle_result' => $moodleResult,
            ]);
        }

        $carnetExists = Persona::where('carnet', strtoupper($request->carnet))->first();
        if ($carnetExists) {
            return response()->json(['success' => false, 'message' => 'Ya existe una persona con el carnet: ' . $request->carnet], 409);
        }

        $persona = Persona::create([
            'carnet'           => strtoupper($request->carnet),
            'expedido'         => $request->expedido ? strtoupper($request->expedido) : null,
            'nombres'          => strtoupper($request->nombres),
            'apellido_paterno' => strtoupper($request->apellido_paterno),
            'apellido_materno' => strtoupper($request->apellido_materno ?? ''),
            'correo'           => $request->correo,
            'celular'          => $request->celular,
            'telefono'         => $request->telefono,
            'direccion'        => $request->direccion,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo'             => $request->sexo,
            'estado_civil'     => $request->estado_civil,
            'ciudade_id'       => $request->ciudade_id ?: null,
        ]);

        $docente = Docente::create([
            'persona_id' => $persona->id,
        ]);

        $this->registrarEstudios($persona->id, $request->estudios);

        $username     = $this->generarUsernameDocente($persona->nombres, $persona->apellido_paterno, $persona->apellido_materno);
        $password     = $this->generarPasswordDocente($persona->carnet);
        $cuentaResult = $this->crearCuentasDocente($persona, $username, $password);

        $moodleResult = null;
        if ($request->modulo_id) {
            $modulo = Modulo::find($request->modulo_id);
            if ($modulo) {
                $moodleResult = $this->matricularDocenteEnMoodle(
                    $docente, $modulo, $cuentaResult['moodle_user_id']
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Docente registrado correctamente. ' . ($cuentaResult['sistema'] ? 'Cuenta de usuario creada.' : ''),
            'docente' => [
                'id' => $docente->id,
                'persona' => [
                    'id' => $persona->id,
                    'carnet' => $persona->carnet,
                    'nombres' => $persona->nombres,
                    'apellido_paterno' => $persona->apellido_paterno,
                    'apellido_materno' => $persona->apellido_materno,
                ],
            ],
            'moodle_result' => $moodleResult,
        ]);
    }

    private function registrarEstudios($personaId, $estudios)
    {
        if (!$estudios || !is_array($estudios) || empty($estudios)) {
            return;
        }

        foreach ($estudios as $est) {
            if (empty($est['grado_id'])) continue;
            Estudio::create([
                'persona_id'          => $personaId,
                'grados_academico_id' => $est['grado_id'],
                'profesione_id'       => $est['profesion_id'] ?? null,
                'universidade_id'     => $est['universidad_id'] ?? null,
                'estado'              => $est['estado'] ?? 'Graduado',
                'principal'           => $est['principal'] ?? 0,
            ]);
        }
    }

    /**
     * Matricula un docente en Moodle si el módulo tiene curso.
     * Si no tiene curso, ofrece crear uno.
     */
    private function matricularDocenteEnMoodle(Docente $docente, Modulo $modulo, ?int $existingMoodleUserId = null): array
    {
        $persona = $docente->persona;
        $result  = ['moodle' => false, 'mensaje' => '', 'sin_curso' => false];

        if (!$modulo->moodle_course_id) {
            $result['sin_curso'] = true;
            $result['mensaje']   = 'El módulo no tiene un curso en Moodle. ¿Desea crear uno?';
            return $result;
        }

        // Desmatricular al docente anterior si es diferente al nuevo
        $prevMatricula = MoodleMatricula::where('modulo_id', $modulo->id)
            ->whereNotNull('docente_id')
            ->where('docente_id', '!=', $docente->id)
            ->first();

        if ($prevMatricula && $prevMatricula->moodle_user_id) {
            $this->moodle->unenrollUserFromCourse(
                (int) $prevMatricula->moodle_user_id,
                $modulo->moodle_course_id
            );
            $prevMatricula->delete();
        }

        $moodleUserId = $existingMoodleUserId;

        if (!$moodleUserId) {
            $username = $this->generarUsernameDocente(
                $persona->nombres ?? '',
                $persona->apellido_paterno ?? '',
                $persona->apellido_materno ?? ''
            );

            // 1) Buscar por username
            $moodleUser   = $this->moodle->getUserByField('username', $username);
            $moodleUserId = $moodleUser ? (int) $moodleUser['id'] : null;

            // 2) Fallback: buscar por correo (evita invalidparameter si el email ya existe)
            if (!$moodleUserId && $persona->correo) {
                $moodleUserByEmail = $this->moodle->getUserByField('email', $persona->correo);
                $moodleUserId      = $moodleUserByEmail ? (int) $moodleUserByEmail['id'] : null;
            }

            // 3) Crear usuario si no existe en Moodle
            if (!$moodleUserId) {
                $password  = $this->generarPasswordDocente($persona->carnet);
                $firstname = trim($persona->nombres ?? '') ?: 'Docente';
                $lastname  = trim(($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) ?: 'Sin Apellido';
                $email     = $persona->correo ?: "{$username}@innova.edu.bo";

                $moodleUserId = $this->moodle->createUser($username, $password, $firstname, $lastname, $email);

                if (!$moodleUserId) {
                    $result['mensaje'] = 'Docente asignado localmente, pero no se pudo crear usuario en Moodle.';
                    return $result;
                }
            }
        }

        // Matricular como docente (rol 3 = teacher editor)
        $this->moodle->enrollUserInCourse($moodleUserId, $modulo->moodle_course_id, 3);

        MoodleMatricula::updateOrCreate(
            ['docente_id' => $docente->id, 'modulo_id' => $modulo->id],
            [
                'moodle_user_id'  => $moodleUserId,
                'moodle_course_id'=> $modulo->moodle_course_id,
                'matriculado_at'  => now(),
            ]
        );

        $result['moodle']         = true;
        $result['moodle_user_id'] = $moodleUserId;
        $result['mensaje']        = 'Docente matriculado en Moodle correctamente.';
        return $result;
    }

    /**
     * Crea el curso en Moodle para un módulo y luego matricula un docente.
     */
    public function crearCursoYMatricularDocente(Request $request, int $moduloId)
    {
        $modulo = Modulo::with(['oferta_academica.programa'])->find($moduloId);
        if (!$modulo) {
            return response()->json(['success' => false, 'message' => 'Módulo no encontrado.'], 404);
        }

        $docente = Docente::find($request->docente_id);
        if (!$docente) {
            return response()->json(['success' => false, 'message' => 'Docente no encontrado.'], 404);
        }

        $oferta = $modulo->oferta_academica;
        $programa = $oferta?->programa;

        if (!$programa) {
            return response()->json(['success' => false, 'message' => 'No se encontró el programa.'], 422);
        }

        // Crear categoría si no existe
        if (!$programa->moodle_category_id) {
            $parentId = (int) config('moodle.category_parent', 0);
            $categoryId = $this->moodle->createCategory($programa->nombre, $parentId);

            if (!$categoryId) {
                return response()->json(['success' => false, 'message' => 'No se pudo crear la categoría en Moodle.'], 500);
            }

            $programa->moodle_category_id = $categoryId;
            $programa->save();
        }

        $shortname = $this->moodle->buildCourseShortname($modulo->ofertas_academica_id, $modulo->n_modulo);

        // Verificar si ya existe
        $existing = $this->moodle->getCourseByShortname($shortname);
        if ($existing && isset($existing['id'])) {
            $modulo->moodle_course_id = $existing['id'];
            $modulo->save();
        } else {
            $courseId = $this->moodle->createCourse(
                $modulo->nombre,
                $shortname,
                $programa->moodle_category_id,
                $modulo->fecha_inicio?->format('Y-m-d'),
                $modulo->fecha_fin?->format('Y-m-d')
            );

            if (!$courseId) {
                return response()->json(['success' => false, 'message' => 'No se pudo crear el curso en Moodle.'], 500);
            }

            $modulo->moodle_course_id = $courseId;
            $modulo->save();

            $templateId = (int) config('moodle.template_course_id', 54);
            if ($templateId && $templateId !== $courseId) {
                $this->moodle->importCourse($templateId, $courseId);
                $this->moodle->cleanImportedCourse($courseId);
            }
        }

        // Matricular docente
        $moodleResult = $this->matricularDocenteEnMoodle($docente, $modulo);

        return response()->json([
            'success' => true,
            'message' => 'Curso creado y docente matriculado en Moodle.',
            'moodle_result' => $moodleResult,
        ]);
    }

    public function registrarPersonaYDocente(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carnet'           => 'required|string|max:20|unique:personas,carnet',
            'nombres'          => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'expedido'         => 'nullable|string|max:10',
            'correo'           => 'nullable|email|max:150',
            'celular'          => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'sexo'             => 'nullable|in:M,F',
            'estado_civil'     => 'nullable|string|max:50',
            'departamento_id'  => 'nullable|exists:departamentos,id',
            'ciudad_id'        => 'nullable|exists:ciudades,id',
            'telefono'         => 'nullable|string|max:20',
            'direccion'        => 'nullable|string|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $persona = Persona::create([
            'carnet'           => strtoupper($request->carnet),
            'nombres'          => strtoupper($request->nombres),
            'apellido_paterno' => strtoupper($request->apellido_paterno),
            'apellido_materno' => strtoupper($request->apellido_materno ?? ''),
            'expedido'         => strtoupper($request->expedido ?? ''),
            'correo'           => $request->correo,
            'celular'          => $request->celular,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo'             => $request->sexo,
            'estado_civil'     => $request->estado_civil,
            'departamento_id'  => $request->departamento_id,
            'ciudad_id'        => $request->ciudad_id,
            'telefono'         => $request->telefono,
            'direccion'        => $request->direccion,
        ]);

        $docente = Docente::create([
            'persona_id' => $persona->id,
        ]);

        $nombre = trim($persona->nombres . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno);

        return response()->json(['success' => true, 'message' => 'Persona y docente registrados correctamente.', 'data' => [
            'id' => $docente->id,
            'persona_id' => $persona->id,
            'nombre' => $nombre,
            'carnet' => $persona->carnet,
        ]]);
    }

    public function actualizar(Request $request, $id)
    {
        try {
            $modulo = Modulo::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombre'       => 'required|string|max:200',
                'color'        => 'nullable|string|max:7',
                'fecha_inicio' => 'nullable|date',
                'fecha_fin'    => 'nullable|date',
                'docente_id'   => 'nullable|exists:docentes,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $modulo->update([
                'nombre'       => $request->nombre,
                'color'        => $request->color ?? $modulo->color,
                'fecha_inicio' => $request->fecha_inicio ?? $modulo->fecha_inicio,
                'fecha_fin'    => $request->fecha_fin ?? $modulo->fecha_fin,
                'docente_id'   => $request->has('docente_id') ? $request->docente_id : $modulo->docente_id,
            ]);

            return response()->json(['success' => true, 'message' => 'Módulo actualizado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function listarHorarios($ofertaId, $moduloId)
    {
        try {
            $modulo = Modulo::with([
                'docente.persona', 
                'horarios.trabajadorCargo.trabajador.persona',
                'horarios.reprogramado',
                'horarios.reprogramado_a'
            ])
                ->where('ofertas_academica_id', $ofertaId)
                ->findOrFail($moduloId);

            $horarios = $modulo->horarios->map(function ($h) use ($modulo) {
                $docenteNombre = '';
                if ($h->trabajadorCargo && $h->trabajadorCargo->trabajador && $h->trabajadorCargo->trabajador->persona) {
                    $p = $h->trabajadorCargo->trabajador->persona;
                    $docenteNombre = trim(($p->nombres ?? '') . ' ' . ($p->apellido_paterno ?? '') . ' ' . ($p->apellido_materno ?? ''));
                } elseif ($modulo->docente && $modulo->docente->persona) {
                    $p = $modulo->docente->persona;
                    $docenteNombre = trim(($p->nombres ?? '') . ' ' . ($p->apellido_paterno ?? '') . ' ' . ($p->apellido_materno ?? ''));
                }

                return [
                    'id' => $h->id,
                    'fecha' => $h->fecha ? $h->fecha->format('Y-m-d') : '',
                    'hora_inicio' => $h->hora_inicio,
                    'hora_fin' => $h->hora_fin,
                    'estado' => $h->estado ?? 'Confirmado',
                    'color' => $h->color ?? $modulo->color,
                    'docente_nombre' => $docenteNombre,
                    'trabajadores_cargo_id' => $h->trabajadores_cargo_id,
                    'trabajador_cargo' => $h->trabajadorCargo ? ['nombre_cargo' => $h->trabajadorCargo->nombre_cargo] : null,
                    'reprogramado_id' => $h->reprogramado_id,
                    'reprogramado_de_fecha' => $h->reprogramado ? $h->reprogramado->fecha?->format('d/m/Y') : null,
                    'reprogramado_a_fecha' => $h->reprogramado_a ? $h->reprogramado_a->fecha?->format('d/m/Y') : null,
                ];
            });

            return response()->json(['data' => $horarios, 'modulo' => [
                'id' => $modulo->id,
                'nombre' => $modulo->nombre,
                'color' => $modulo->color,
                'estado' => $modulo->estado,
            ]]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function guardarHorario(Request $request, $moduloId)
    {
        $validator = Validator::make($request->all(), [
            'fecha'       => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin'    => 'required',
            'estado'      => 'nullable|string|max:50',
            'color'       => 'nullable|string|max:7',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $modulo = Modulo::findOrFail($moduloId);

        $horario = Horario::create([
            'modulo_id'            => $moduloId,
            'fecha'                => $request->fecha,
            'hora_inicio'          => $request->hora_inicio,
            'hora_fin'             => $request->hora_fin,
            'estado'               => $request->estado ?? 'Confirmado',
            'color'                => $request->color ?? $modulo->color,
            'trabajadores_cargo_id' => $request->trabajadores_cargo_id ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Horario registrado correctamente.', 'data' => $horario]);
    }

    public function actualizarHorario(Request $request, $id)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            return response()->json(['success' => false, 'message' => 'Horario no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'fecha'       => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin'    => 'required',
            'estado'      => 'nullable|string|max:50',
            'color'       => 'nullable|string|max:7',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $horario->update([
            'fecha'       => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin'    => $request->hora_fin,
            'estado'      => $request->estado ?? $horario->estado,
            'color'       => $request->color ?? $horario->color,
            'trabajadores_cargo_id' => $request->trabajadores_cargo_id ?? $horario->trabajadores_cargo_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Horario actualizado correctamente.', 'data' => $horario]);
    }

    public function eliminarHorario($id)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            return response()->json(['success' => false, 'message' => 'Horario no encontrado.'], 404);
        }
        $horario->delete();
        return response()->json(['success' => true, 'message' => 'Horario eliminado correctamente.']);
    }

    public function registrarEstudiosDocente(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'persona_id' => 'required|exists:personas,id',
            'estudios'   => 'required|array|min:1',
            'estudios.*.grado_id' => 'required|exists:grados_academicos,id',
            'estudios.*.profesion_id' => 'nullable|exists:profesiones,id',
            'estudios.*.universidad_id' => 'nullable|exists:universidades,id',
            'estudios.*.estado' => 'required|string|max:50',
            'estudios.*.principal' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $creados = [];
        foreach ($request->estudios as $est) {
            $estudio = Estudio::create([
                'persona_id'          => $request->persona_id,
                'grados_academico_id' => $est['grado_id'],
                'profesione_id'       => $est['profesion_id'] ?? null,
                'universidade_id'     => $est['universidad_id'] ?? null,
                'estado'              => $est['estado'],
                'principal'           => $est['principal'] ?? 0,
            ]);
            $creados[] = $estudio;
        }

        return response()->json(['success' => true, 'message' => count($creados) . ' estudio(s) registrado(s).', 'data' => $creados]);
    }

    public function cambiarEstadoModulo(Request $request, $id)
    {
        $modulo = Modulo::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:No Inició,En Desarrollo,Concluido',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $modulo->update(['estado' => $request->estado]);

        return response()->json(['success' => true, 'message' => 'Estado del módulo actualizado correctamente.']);
    }

    public function cambiarEstadoHorario($id)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            return response()->json(['success' => false, 'message' => 'Horario no encontrado.'], 404);
        }

        $estado = request('estado');
        if (!$estado) {
            return response()->json(['success' => false, 'message' => 'Estado requerido.'], 400);
        }

        if ($estado === 'Postergado') {
            $nuevaFecha = request('nueva_fecha');
            if (!$nuevaFecha) {
                return response()->json(['success' => false, 'message' => 'La nueva fecha es requerida para postergar.'], 422);
            }

            // Creamos el nuevo horario basado en el actual, copiando el enlace de videollamada
            Horario::create([
                'modulo_id'              => $horario->modulo_id,
                'fecha'                  => $nuevaFecha,
                'hora_inicio'            => $horario->hora_inicio,
                'hora_fin'               => $horario->hora_fin,
                'estado'                 => 'Confirmado',
                'trabajadores_cargo_id'  => $horario->trabajadores_cargo_id,
                'color'                  => $horario->color,
                'reprogramado_id'        => $horario->id,
                'enlace_videollamada_id' => $horario->enlace_videollamada_id,
            ]);

            $horario->color = '#94a3b8';
        }

        $campos = ['estado' => $estado, 'color' => $horario->color];

        if ($estado === 'Desarrollado') {
            $enlaceGrabacion = trim(request('enlace_grabacion', ''));
            if ($enlaceGrabacion !== '') {
                $campos['enlace_grabacion'] = $enlaceGrabacion;
            }
        }

        $horario->update($campos);

        return response()->json(['success' => true, 'message' => 'Estado actualizado.', 'data' => $horario]);
    }

    public function reprogramarHorario($id)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            return response()->json(['success' => false, 'message' => 'Horario no encontrado.'], 404);
        }

        $validator = Validator::make(request()->all(), [
            'fecha'       => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $nuevo = Horario::create([
            'modulo_id'             => $horario->modulo_id,
            'fecha'                 => request('fecha'),
            'hora_inicio'           => request('hora_inicio'),
            'hora_fin'              => request('hora_fin'),
            'estado'                => 'Reprogramado',
            'trabajadores_cargo_id' => $horario->trabajadores_cargo_id,
            'reprogramado_id'       => $horario->id,
        ]);

        $horario->update(['estado' => 'Reprogramado']);

        return response()->json(['success' => true, 'message' => 'Horario reprogramado.', 'data' => $nuevo]);
    }

    public function listarTrabajadores()
    {
        try {
            $trabajadores = TrabajadoresCargo::select('trabajadores_cargos.*')
                ->with(['trabajador.persona', 'cargo'])
                ->leftJoin('cargos', 'trabajadores_cargos.cargo_id', '=', 'cargos.id')
                ->orderBy('cargos.nombre', 'asc')
                ->get();
            return response()->json(['data' => $trabajadores]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function matricularTodos($moduloId)
    {
        try {
            $modulo = Modulo::with('oferta_academica.programa')->findOrFail($moduloId);
            $ofertaId = $modulo->ofertas_academica_id;

            $inscripciones = Inscripcione::where('ofertas_academica_id', $ofertaId)
                ->where('estado', 'Inscrito')
                ->whereNotIn('id', function($query) use ($moduloId) {
                    $query->select('inscripcione_id')
                        ->from('matriculaciones')
                        ->where('modulo_id', $moduloId);
                })
                ->get();

            $creados = 0;
            foreach ($inscripciones as $inscripcion) {
                Matriculacione::create([
                    'inscripcione_id' => $inscripcion->id,
                    'modulo_id' => $moduloId,
                ]);
                $creados++;
            }

            return response()->json([
                'success' => true,
                'message' => "{$creados} estudiante(s) matriculado(s) correctamente."
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function matricularEstudiante($inscripcionId)
    {
        try {
            $inscripcion = Inscripcione::findOrFail($inscripcionId);
            $moduloId = request('modulo_id');

            if (!$moduloId) {
                return response()->json(['success' => false, 'message' => 'ID de módulo requerido'], 400);
            }

            $existente = Matriculacione::where('inscripcione_id', $inscripcionId)
                ->where('modulo_id', $moduloId)
                ->first();

            if ($existente) {
                return response()->json(['success' => false, 'message' => 'El estudiante ya está matriculado en este módulo'], 400);
            }

            Matriculacione::create([
                'inscripcione_id' => $inscripcionId,
                'modulo_id' => $moduloId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estudiante matriculado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function guardarBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ofertas_academica_id' => 'required|exists:ofertas_academicas,id',
            'modulos' => 'required|array|min:1',
            'modulos.*.nombre' => 'required|string|max:200',
            'modulos.*.fecha_inicio' => 'required|date',
            'modulos.*.fecha_fin' => 'required|date|after_or_equal:modulos.*.fecha_inicio',
            'modulos.*.color' => 'nullable|string|max:7',
            'modulos.*.docente_id' => 'nullable|exists:docentes,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $oferta = OfertasAcademica::with('programa')->findOrFail($request->ofertas_academica_id);
            $programa = $oferta->programa;
            
            if (!$programa) {
                return response()->json(['success' => false, 'message' => 'No se encontró el programa de la oferta.'], 422);
            }

            $created = [];
            $updated = [];
            $nModulo = Modulo::where('ofertas_academica_id', $request->ofertas_academica_id)->max('n_modulo') + 1;
            
            foreach ($request->modulos as $data) {
                $modulo = null;
                $moduloId = $data['modulo_id'] ?? null;
                
                if ($moduloId) {
                    $modulo = Modulo::where('id', $moduloId)
                        ->where('ofertas_academica_id', $request->ofertas_academica_id)
                        ->first();
                }
                
                if (!$modulo) {
                    $modulo = new Modulo();
                    $modulo->n_modulo = $nModulo++;
                    $modulo->ofertas_academica_id = $request->ofertas_academica_id;
                }
                
                $modulo->nombre = $data['nombre'];
                $modulo->color = $data['color'] ?? '#3B82F6';
                $modulo->fecha_inicio = $data['fecha_inicio'];
                $modulo->fecha_fin = $data['fecha_fin'];
                $modulo->docente_id = $data['docente_id'] ?? null;
                $modulo->save();

                if ($programa) {
                    $shortname = $this->moodle->buildCourseShortname($modulo->ofertas_academica_id, $modulo->n_modulo);
                    
                    if ($modulo->moodle_course_id) {
                        $this->moodle->updateCourse(
                            $modulo->moodle_course_id,
                            $modulo->nombre,
                            $shortname,
                            $modulo->fecha_inicio?->format('Y-m-d'),
                            $modulo->fecha_fin?->format('Y-m-d')
                        );
                    } else {
                        $existing = $this->moodle->getCourseByShortname($shortname);
                        
                        if ($existing && isset($existing['id'])) {
                            $modulo->moodle_course_id = $existing['id'];
                            $modulo->save();
                        } else {
                            $categoryId = $programa->moodle_category_id;
                            if (!$categoryId) {
                                $parentId = (int) config('moodle.category_parent', 0);
                                $categoryId = $this->moodle->createCategory($programa->nombre, $parentId);
                                if ($categoryId) {
                                    $programa->moodle_category_id = $categoryId;
                                    $programa->save();
                                }
                            }
                            
                            if ($categoryId) {
                                $courseId = $this->moodle->createCourse(
                                    $modulo->nombre,
                                    $shortname,
                                    $categoryId,
                                    $modulo->fecha_inicio?->format('Y-m-d'),
                                    $modulo->fecha_fin?->format('Y-m-d')
                                );
                                if ($courseId) {
                                    $modulo->moodle_course_id = $courseId;
                                    $modulo->save();

                                    $templateId = (int) config('moodle.template_course_id', 54);
                                    if ($templateId && $templateId !== $courseId) {
                                        $this->moodle->importCourse($templateId, $courseId);
                                    }
                                }
                            }
                        }
                    }

                    if ($modulo->docente_id && $modulo->moodle_course_id) {
                        $this->matricularDocenteEnMoodle($modulo->docente, $modulo);
                    }
                }

                if ($modulo->wasRecentlyCreated) {
                    $created[] = $modulo->id;
                } else {
                    $updated[] = $modulo->id;
                }
            }

            $msg = count($created) > 0 && count($updated) > 0 
                ? 'Módulos actualizados y creados correctamente.'
                : (count($created) > 0 ? 'Módulos registrados correctamente.' : 'Módulos actualizados correctamente.');

            return response()->json([
                'success' => true,
                'message' => $msg,
                'created' => $created,
                'updated' => $updated,
            ]);
        } catch (\Exception $e) {
            \Log::error('guardarBatch error: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function actualizarGrabacionHorario(Request $request, $id)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            return response()->json(['success' => false, 'message' => 'Horario no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'enlace_grabacion' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $horario->update(['enlace_grabacion' => $request->enlace_grabacion ?? null]);

        return response()->json([
            'success' => true,
            'message' => 'Enlace de grabación actualizado correctamente.',
            'data'    => ['enlace_grabacion' => $horario->enlace_grabacion],
        ]);
    }

    public function asignarEnlaceVideollamada(Request $request, $moduloId)
    {
        $modulo = Modulo::with('horarios')->findOrFail($moduloId);

        $validator = Validator::make($request->all(), [
            'cuenta_id' => 'required|exists:cuentas_videollamada,id',
            'nombre'    => 'required|string|max:200',
            'enlace'    => 'required|string|max:500',
        ], [
            'cuenta_id.required' => 'Debe seleccionar una cuenta de videollamada.',
            'cuenta_id.exists'   => 'La cuenta seleccionada no existe.',
            'nombre.required'    => 'El nombre del enlace es obligatorio.',
            'enlace.required'    => 'El enlace de la sesión es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            if ($modulo->enlace_videollamada_id) {
                $enlace = EnlaceVideollamada::find($modulo->enlace_videollamada_id);
                if ($enlace) {
                    $enlace->update([
                        'cuenta_id' => $request->cuenta_id,
                        'nombre'    => $request->nombre,
                        'enlace'    => $request->enlace,
                    ]);
                } else {
                    $enlace = EnlaceVideollamada::create([
                        'cuenta_id' => $request->cuenta_id,
                        'nombre'    => $request->nombre,
                        'enlace'    => $request->enlace,
                        'activo'    => true,
                    ]);
                    $modulo->enlace_videollamada_id = $enlace->id;
                    $modulo->save();
                }
            } else {
                $enlace = EnlaceVideollamada::create([
                    'cuenta_id' => $request->cuenta_id,
                    'nombre'    => $request->nombre,
                    'enlace'    => $request->enlace,
                    'activo'    => true,
                ]);
                $modulo->enlace_videollamada_id = $enlace->id;
                $modulo->save();
            }

            $modulo->horarios()->update(['enlace_videollamada_id' => $enlace->id]);

            return response()->json([
                'success' => true,
                'message' => 'Enlace de videollamada ' . ($request->enlace_id ? 'actualizado' : 'registrado') . ' correctamente.',
                'data'    => $enlace,
            ]);
        } catch (\Exception $e) {
            Log::error('asignarEnlaceVideollamada error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al registrar el enlace.'], 500);
        }
    }
}
