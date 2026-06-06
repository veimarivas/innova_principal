<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ciudade;
use App\Models\Cuota;
use App\Models\Departamento;
use App\Models\Detalle;
use App\Models\Estudiante;
use App\Models\Estudio;
use App\Models\Inscripcione;
use App\Models\Pago;
use App\Models\PagosCuota;
use App\Models\Persona;
use App\Models\User;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EstudianteController extends Controller
{
    public function index()
    {
        return view('admin.estudiantes.index');
    }

    public function cuotasJson($id)
    {
        $cuotas = \App\Models\Cuota::whereHas('inscripcion', function ($q) use ($id) {
            $q->where('estudiante_id', $id);
        })->orderBy('fecha_vencimiento')->get();
        
        return response()->json($cuotas->map(function ($c) {
            return [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'monto_bs' => $c->monto_bs,
                'pago_pendiente_bs' => $c->pago_pendiente_bs ?? 0,
                'estado' => $c->estado,
                'fecha_vencimiento' => $c->fecha_vencimiento,
                'inscripcion_id' => $c->inscripcione_id,
            ];
        }));
    }

    public function verDetalle($id)
    {
        $estudiante = Estudiante::with([
            'persona.ciudad.departamento',
            'persona.estudios.grado_academico',
            'persona.estudios.profesion',
            'persona.estudios.universidad'
        ])->findOrFail($id);

        $inscripciones = Inscripcione::where('estudiante_id', $id)
            ->with([
                'ofertaAcademica.posgrado',
                'ofertaAcademica.programa',
                'ofertaAcademica.fase',
                'ofertaAcademica.modalidad',
                'ofertaAcademica.sucursal',
                'planesPago',
                'trabajador_cargo.cargo',
                'trabajador_cargo.sucursale.sede',
                'trabajador_cargo.trabajador.persona',
                'cuotas.pagosCuota.pago.detalles',
                'matriculaciones.modulo.docente.persona'
            ])
            ->orderBy('fecha_registro', 'desc')
            ->get();

        // Obtener datos del trabajador autenticado
        $trabajadorActual = null;
        if (auth()->check() && auth()->user()->persona) {
            $persona = auth()->user()->persona;
            $trabajador = $persona->trabajador;
            
            if ($trabajador) {
                $cargoPrincipal = $trabajador->trabajadores_cargos()
                    ->where('estado', 'Vigente')
                    ->orderBy('id', 'desc')
                    ->first();
                
                if ($cargoPrincipal) {
                    $trabajadorActual = [
                        'id' => $cargoPrincipal->id,
                        'nombre' => $persona->nombres . ' ' . 
                                   $persona->apellido_paterno . ' ' . 
                                   $persona->apellido_materno,
                        'cargo' => $cargoPrincipal->cargo->nombre ?? 'Sin cargo',
                        'sucursal' => $cargoPrincipal->sucursale->nombre ?? '',
                        'sede' => $cargoPrincipal->sucursale->sede->nombre ?? ''
                    ];
                }
            }
        }

        $estudios = collect();
        $estudioPrincipal = null;
        if ($estudiante->persona && $estudiante->persona->estudios) {
            $estudios = $estudiante->persona->estudios()->with(['grado_academico', 'profesion', 'universidad'])->orderBy('principal', 'desc')->orderBy('id', 'desc')->get();
            $estudioPrincipal = $estudios->firstWhere('principal', 1);
            if (!$estudioPrincipal) {
                $estudioPrincipal = $estudios->first();
            }
        }

        $tiposEstudio = \App\Models\GradoAcademico::orderBy('nombre')->get();
        $profesiones = \App\Models\Profesione::orderBy('nombre')->get();
        $universidades = \App\Models\Universidade::orderBy('nombre')->get();
        $cuentasBancarias = \App\Models\CuentaBancaria::with('banco')->where('estado', true)->get();
        
        return view('admin.estudiantes.detalle', compact('estudiante', 'inscripciones', 'trabajadorActual', 'estudios', 'estudioPrincipal', 'tiposEstudio', 'profesiones', 'universidades', 'cuentasBancarias'));
    }

    public function subirDocumento(Request $request, $id)
    {
        $request->validate([
            'tipo_documento' => 'required|in:fotografia_carnet,fotografia_certificado_nacimiento,documento_academico,documento_provision_nacional',
            'archivo' => 'required|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'estudio_id' => 'nullable|integer|exists:estudios,id',
        ]);

        $estudiante = Estudiante::findOrFail($id);
        $persona = $estudiante->persona;
        $tipoDocumento = $request->tipo_documento;

        $esDocumentoAcademico = in_array($tipoDocumento, ['documento_academico', 'documento_provision_nacional']);

        if ($esDocumentoAcademico) {
            if ($request->filled('estudio_id')) {
                $estudio = Estudio::where('id', $request->estudio_id)->where('persona_id', $persona->id)->first();
                if (!$estudio) {
                    return response()->json(['success' => false, 'error' => 'Estudio no encontrado.'], 404);
                }
            } else {
                $estudio = $persona->estudios()->where('principal', 1)->orderBy('id', 'asc')->first();
                if (!$estudio) {
                    $estudio = $persona->estudios()->orderBy('id', 'asc')->first();
                }
                if (!$estudio) {
                    return response()->json(['success' => false, 'error' => 'No se encontró estudio registrado para este estudiante.'], 400);
                }
            }
        }

        $archivo = $request->file('archivo');
        $nombreArchivo = $tipoDocumento . '_' . $estudiante->id . '_' . time() . '.' . $archivo->getClientOriginalExtension();
        $ruta = $archivo->storeAs('documentos', $nombreArchivo, 'public');

        try {
            if ($esDocumentoAcademico) {
                $campoArchivo = $tipoDocumento === 'documento_academico' ? 'documento_academico' : 'documento_provision_nacional';
                if ($estudio->$campoArchivo && Storage::disk('public')->exists($estudio->$campoArchivo)) {
                    Storage::disk('public')->delete($estudio->$campoArchivo);
                }
                $estudio->update([$campoArchivo => $ruta]);
            } else {
                $campoVerificacion = $tipoDocumento === 'fotografia_carnet' ? 'carnet_verificado' : 'certificado_nacimiento_verificado';
                $persona->update([
                    $tipoDocumento => $ruta,
                    $campoVerificacion => 0,
                ]);
            }
            return response()->json(['success' => true, 'ruta' => $ruta, 'mensaje' => 'Documento subido exitosamente']);
        } catch (\Exception $e) {
            Storage::disk('public')->delete($ruta);
            return response()->json(['success' => false, 'error' => 'Error al guardar en la base de datos.'], 500);
        }
    }

public function verificarDocumento(Request $request, $id)
    {
        $request->validate([
            'tipo_documento' => 'required|in:fotografia_carnet,fotografia_certificado_nacimiento,documento_academico,documento_provision_nacional',
            'estudio_id' => 'nullable|integer|exists:estudios,id',
        ]);

        $estudiante = Estudiante::findOrFail($id);
        $persona = $estudiante->persona;
        $tipoDocumento = $request->tipo_documento;
        $accion = $request->input('accion', 'verificar');
        $esVerificar = $accion !== 'quitar';

        $esDocumentoAcademico = in_array($tipoDocumento, ['documento_academico', 'documento_provision_nacional']);

        try {
            if ($esDocumentoAcademico) {
                if ($request->filled('estudio_id')) {
                    $estudio = Estudio::where('id', $request->estudio_id)->where('persona_id', $persona->id)->first();
                    if (!$estudio) {
                        return response()->json(['success' => false, 'error' => 'Estudio no encontrado.'], 404);
                    }
                } else {
                    $estudio = $persona->estudios()->where('principal', 1)->orderBy('id', 'asc')->first();
                    if (!$estudio) {
                        $estudio = $persona->estudios()->orderBy('id', 'asc')->first();
                    }
                    if (!$estudio) {
                        return response()->json(['success' => false, 'error' => 'No se encontró estudio registrado.'], 400);
                    }
                }
                $campoVerificacion = $tipoDocumento === 'documento_academico' ? 'documento_academico_verificado' : 'documento_provision_verificado';
                $estudio->update([$campoVerificacion => $esVerificar ? 1 : 0]);
            } else {
                $campoVerificacion = $tipoDocumento === 'fotografia_carnet' ? 'carnet_verificado' : 'certificado_nacimiento_verificado';
                $persona->update([$campoVerificacion => $esVerificar ? 1 : 0]);
            }
            $mensaje = $esVerificar ? 'Documento verificado' : 'Verificacion quitada';
            return response()->json(['success' => true, 'mensaje' => $mensaje]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Error al procesar el documento.'], 500);
        }
    }

    public function visualizarDocumento(Request $request, $id)
    {
        $request->validate([
            'tipo' => 'required|in:fotografia_carnet,fotografia_certificado_nacimiento,documento_academico,documento_provision_nacional',
            'estudio_id' => 'nullable|integer|exists:estudios,id',
        ]);

        $estudiante = Estudiante::findOrFail($id);
        $persona = $estudiante->persona;
        $tipo = $request->tipo;

        $esDocumentoAcademico = in_array($tipo, ['documento_academico', 'documento_provision_nacional']);

        $rutaArchivo = null;

        if ($esDocumentoAcademico) {
            if ($request->filled('estudio_id')) {
                $estudio = Estudio::where('id', $request->estudio_id)->where('persona_id', $persona->id)->first();
            } else {
                $estudio = $persona->estudios()->where('principal', 1)->orderBy('id', 'asc')->first();
                if (!$estudio) {
                    $estudio = $persona->estudios()->orderBy('id', 'asc')->first();
                }
            }
            if ($estudio) {
                $campoArchivo = $tipo === 'documento_academico' ? 'documento_academico' : 'documento_provision_nacional';
                $rutaArchivo = $estudio->$campoArchivo;
            }
        } else {
            $rutaArchivo = $persona->$tipo;
        }

        if (!$rutaArchivo || !Storage::disk('public')->exists($rutaArchivo)) {
            return response()->json(['success' => false, 'error' => 'Documento no encontrado.'], 404);
        }

        $archivo = Storage::disk('public')->get($rutaArchivo);
        $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION);
        $mimeType = match ($extension) {
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            default => 'application/octet-stream',
        };

        return response($archivo, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($rutaArchivo) . '"',
        ]);
    }

    public function setPrincipalEstudio($id, $estudioId)
    {
        $estudiante = Estudiante::findOrFail($id);
        $persona = $estudiante->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        $estudio = Estudio::where('id', $estudioId)->where('persona_id', $persona->id)->firstOrFail();

        Estudio::where('persona_id', $persona->id)->update(['principal' => false]);
        $estudio->update(['principal' => true]);

        return response()->json(['success' => true, 'message' => 'Estudio marcado como principal.']);
    }

    public function eliminarEstudio($id, $estudioId)
    {
        $estudiante = Estudiante::findOrFail($id);
        $persona = $estudiante->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        $estudio = Estudio::where('id', $estudioId)->where('persona_id', $persona->id)->firstOrFail();

        // Delete associated files
        if ($estudio->documento_academico && Storage::disk('public')->exists($estudio->documento_academico)) {
            Storage::disk('public')->delete($estudio->documento_academico);
        }
        if ($estudio->documento_provision_nacional && Storage::disk('public')->exists($estudio->documento_provision_nacional)) {
            Storage::disk('public')->delete($estudio->documento_provision_nacional);
        }

        $estudio->delete();

        return response()->json(['success' => true, 'message' => 'Estudio eliminado correctamente.']);
    }

    public function buscarView()
    {
        $cuentasBancarias = \App\Models\CuentaBancaria::with('banco')->where('estado', true)->get();
        return view('admin.estudiantes.buscar', compact('cuentasBancarias'));
    }

    public function listar()
    {
        $estudiantes = Estudiante::with([
            'persona.ciudad',
            'persona.usuario',
        ])->orderBy('id', 'desc')->get();

        $estudianteIds = $estudiantes->pluck('id')->all();

        // Estudiantes con moodle_user_id en inscripciones (creado por crearCuentas / crearCuentasBatch)
        $conMoodleInscripcion = DB::table('inscripciones')
            ->whereIn('estudiante_id', $estudianteIds)
            ->whereNotNull('moodle_user_id')
            ->distinct()
            ->pluck('estudiante_id')
            ->toArray();

        // Estudiantes con moodle_user_id en moodle_matriculas (flujo antiguo)
        $conMoodleMatricula = DB::table('moodle_matriculas')
            ->join('inscripciones', 'moodle_matriculas.inscripcion_id', '=', 'inscripciones.id')
            ->whereIn('inscripciones.estudiante_id', $estudianteIds)
            ->whereNotNull('moodle_matriculas.moodle_user_id')
            ->distinct()
            ->pluck('inscripciones.estudiante_id')
            ->toArray();

        // Estudiantes cuya persona tiene usuario con username (creado junto a cuenta Moodle)
        $personaIds = $estudiantes->pluck('persona_id')->filter()->all();
        $conMoodleUsuario = DB::table('users')
            ->whereIn('persona_id', $personaIds)
            ->whereNotNull('username')
            ->where('username', '!=', '')
            ->pluck('persona_id')
            ->toArray();

        // Mapear persona_id → estudiante_id para los que tienen usuario con username
        $personaToEstudiante = $estudiantes->whereIn('persona_id', $conMoodleUsuario)
            ->pluck('id')->toArray();

        $conMoodle = array_unique(array_merge($conMoodleInscripcion, $conMoodleMatricula, $personaToEstudiante));

        $data = $estudiantes->map(function ($e) use ($conMoodle) {
            $arr = $e->toArray();
            $arr['tiene_cuenta_sistema'] = $e->persona && $e->persona->usuario !== null;
            $arr['tiene_cuenta_moodle']  = in_array($e->id, $conMoodle);
            $arr['usuario_username'] = $e->persona && $e->persona->usuario
                ? $e->persona->usuario->username
                : null;
            $arr['usuario_moodle_password'] = $e->persona && $e->persona->usuario
                ? $e->persona->usuario->moodle_password
                : null;
            return $arr;
        });

        return response()->json(['data' => $data]);
    }

    public function crearCuentas(Request $request, $id)
    {
        $estudiante = Estudiante::with(['persona.usuario'])->findOrFail($id);
        $persona    = $estudiante->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        $tieneSistema = $persona->usuario !== null;
        $tieneMoodle  = DB::table('moodle_matriculas')
            ->join('inscripciones', 'moodle_matriculas.inscripcion_id', '=', 'inscripciones.id')
            ->where('inscripciones.estudiante_id', $id)
            ->whereNotNull('moodle_matriculas.moodle_user_id')
            ->exists();

        if ($tieneSistema && $tieneMoodle) {
            return response()->json(['success' => false, 'message' => 'Este estudiante ya tiene ambas cuentas activas.'], 422);
        }

        if (!$persona->correo && !$tieneSistema) {
            return response()->json(['success' => false, 'message' => 'El estudiante no tiene correo electrónico registrado. Agréguelo primero.'], 422);
        }

        $password  = $this->generarPasswordSistema($persona->carnet);
        $moodleUsername = $request->filled('username')
            ? preg_replace('/\s+/', '', strtolower($request->input('username')))
            : $this->generarUsernameMoodle(
                $persona->nombres ?? '',
                $persona->apellido_paterno ?? '',
                $persona->apellido_materno ?? ''
              );
        $resultado = [];

        // ── Cuenta del sistema ──────────────────────────────────────────────
        if (!$tieneSistema) {
            if (User::where('email', $persona->correo)->exists()) {
                return response()->json(['success' => false, 'message' => 'El correo ya está en uso por otro usuario del sistema.'], 422);
            }
            $nombre = trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''));
            User::create([
                'name'            => $nombre,
                'username'        => $moodleUsername,
                'email'           => $persona->correo,
                'password'        => $password,
                'moodle_password' => $password,
                'role'            => 'moodle',
                'estado'          => 'Activo',
                'persona_id'      => $persona->id,
            ]);
            $resultado['sistema'] = ['email' => $persona->correo, 'username' => $moodleUsername, 'password' => $password];
        }

        // ── Cuenta Moodle ───────────────────────────────────────────────────
        if (!$tieneMoodle) {
            $moodle       = app(MoodleService::class);
            $existingUser = $moodle->findExistingMoodleUser(
                $persona->nombres ?? '',
                $persona->apellido_paterno ?? '',
                $persona->apellido_materno ?? ''
            );

            if (!$existingUser) {
                $username  = $moodleUsername;
                $firstname = trim($persona->nombres ?? '') ?: 'Sin Nombre';
                $lastname  = trim(($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) ?: 'Sin Apellido';
                $email     = $persona->correo ?: "{$username}@innova.edu.bo";

                $moodleUserId = $moodle->createUser($username, $password, $firstname, $lastname, $email);

                if (!$moodleUserId) {
                    return response()->json(['success' => false, 'message' => 'Error al crear el usuario en Moodle. Verifique la conexión.'], 500);
                }

                Inscripcione::where('estudiante_id', $id)
                    ->whereNull('moodle_user_id')
                    ->update([
                        'moodle_user_id'        => $moodleUserId,
                        'en_moodle'             => true,
                        'matriculado_moodle_at' => now(),
                    ]);

                $resultado['moodle'] = ['username' => $username, 'password' => $password, 'email' => $email];
            } else {
                $resultado['moodle'] = [
                    'username' => $existingUser['username'],
                    'email'    => $existingUser['email'] ?? ($persona->correo ?? ''),
                    'nota'     => 'Usuario ya existía en Moodle.',
                ];
            }
        }

        $partes = array_keys($resultado);
        $msg = count($partes) === 2
            ? 'Cuentas del sistema y Moodle creadas con las mismas credenciales.'
            : (isset($resultado['sistema']) ? 'Cuenta del sistema creada.' : 'Cuenta de Moodle creada.');

        return response()->json(['success' => true, 'message' => $msg, 'data' => $resultado]);
    }

    public function crearCuentasBatch(Request $request)
    {
        $estudiantes = json_decode($request->estudiantes, true);

        if (!$estudiantes || count($estudiantes) === 0) {
            return response()->json(['success' => false, 'message' => 'No hay estudiantes seleccionados.'], 400);
        }

        $creados = 0;
        $errores = [];

        foreach ($estudiantes as $est) {
            try {
                $estudiante = Estudiante::with('persona.usuario')->find($est['id']);
                if (!$estudiante || !$estudiante->persona) {
                    $errores[] = "Estudiante no encontrado: {$est['nombre']}";
                    continue;
                }

                $persona   = $estudiante->persona;
                $username  = $est['username'];
                $password  = $est['password'];
                $email     = $persona->correo ?: "{$username}@innova.edu.bo";
                $firstname = trim($persona->nombres ?? '') ?: 'Estudiante';
                $lastname  = trim(($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) ?: 'Sin Apellido';

                $moodleService = app(\App\Services\MoodleService::class);

                // Cuenta Moodle
                $moodleUserId = null;
                $existing = $moodleService->getUserByField('username', $username);
                if ($existing && isset($existing['id'])) {
                    $moodleUserId = (int) $existing['id'];
                } else {
                    $moodleUserId = $moodleService->createUser($username, $password, $firstname, $lastname, $email);
                }

                if ($moodleUserId) {
                    Inscripcione::where('estudiante_id', $estudiante->id)
                        ->whereNull('moodle_user_id')
                        ->update([
                            'moodle_user_id'        => $moodleUserId,
                            'en_moodle'             => true,
                            'matriculado_moodle_at' => now(),
                        ]);
                    $creados++;
                } else {
                    $errores[] = "No se pudo crear usuario en Moodle: {$est['nombre']}";
                }

                // Cuenta sistema Laravel
                $userRecord = User::where('persona_id', $persona->id)->first()
                    ?? ($persona->correo ? User::where('email', $persona->correo)->first() : null);

                if (!$userRecord && $persona->correo) {
                    $nombre = trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''));
                    User::create([
                        'name'            => $nombre,
                        'username'        => $username,
                        'email'           => $persona->correo,
                        'password'        => $password,
                        'moodle_password' => $password,
                        'role'            => 'moodle',
                        'estado'          => 'Activo',
                        'persona_id'      => $persona->id,
                    ]);
                } elseif ($userRecord) {
                    $userRecord->update(['moodle_password' => $password]);
                }
            } catch (\Exception $e) {
                $errores[] = "Error con {$est['nombre']}: " . $e->getMessage();
            }
        }

        $msg = $creados > 0
            ? "Se crearon cuentas para {$creados} estudiante(s)."
            : 'No se pudieron crear las cuentas.';

        if ($errores) {
            $msg .= ' Errores: ' . implode('; ', $errores);
        }

        return response()->json(['success' => $creados > 0, 'message' => $msg]);
    }

    public function resetPasswordMoodle(Request $request, $id)
    {
        $estudiante = Estudiante::with(['persona.usuario'])->findOrFail($id);
        $persona    = $estudiante->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        $moodleUserId = DB::table('moodle_matriculas')
            ->join('inscripciones', 'moodle_matriculas.inscripcion_id', '=', 'inscripciones.id')
            ->where('inscripciones.estudiante_id', $id)
            ->whereNotNull('moodle_matriculas.moodle_user_id')
            ->value('moodle_matriculas.moodle_user_id');

        if (!$moodleUserId) {
            return response()->json(['success' => false, 'message' => 'No se encontró cuenta de Moodle para este estudiante.'], 404);
        }

        $password = $this->generarPasswordSistema($persona->carnet);
        $moodle   = app(MoodleService::class);

        if (!$moodle->updateUserPassword((int) $moodleUserId, $password)) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar la contraseña en Moodle.'], 500);
        }

        if ($persona->usuario) {
            $persona->usuario->update(['moodle_password' => $password]);
        }

        return response()->json(['success' => true, 'password' => $password]);
    }

    private function generarPasswordSistema(?string $carnet): string
    {
        $digits = preg_replace('/[^0-9]/', '', $carnet ?: '');
        return strlen($digits) >= 7 ? $digits : 'innova' . $digits;
    }

    private function generarUsernameMoodle(string $nombres, string $apPaterno, string $apMaterno): string
    {
        $reemplazos = ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n'];
        $palabras = preg_split('/\s+/', trim($nombres));
        $primerNombre = $palabras[0] ?? '';

        $ap = strtr(preg_replace('/[^a-záéíóúüñ]/u', '', mb_strtolower($apPaterno)), $reemplazos);
        $am = strtr(preg_replace('/[^a-záéíóúüñ]/u', '', mb_strtolower($apMaterno)), $reemplazos);
        $inicial = mb_strtolower(mb_substr($primerNombre, 0, 1));
        $inicial = strtr($inicial, $reemplazos);

        $username = substr($inicial . $ap . $am, 0, 20);
        return $username ?: ('usuario' . abs(crc32($nombres . $apPaterno)));
    }

    public function buscar(Request $request)
    {
        $query = $request->input('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['data' => []]);
        }
        
        $estudiantes = Estudiante::with(['persona.ciudad'])
            ->whereHas('persona', function ($q) use ($query) {
                $q->where('carnet', 'like', '%' . $query . '%')
                  ->orWhere('nombres', 'like', '%' . $query . '%')
                  ->orWhere('apellido_paterno', 'like', '%' . $query . '%')
                  ->orWhere('apellido_materno', 'like', '%' . $query . '%');
            })
            ->limit(20)
            ->get()
            ->map(function ($estudiante) {
                $persona = $estudiante->persona;
                $inscripciones = \App\Models\Inscripcione::where('estudiante_id', $estudiante->id)
                    ->whereIn('estado', ['Inscrito', 'Pre-Inscrito', 'Confirmado'])
                    ->with(['cuotas', 'cuotas.pagosCuota', 'ofertaAcademica.programa'])
                    ->get();
                
                $totalPlan = 0;
                $totalPagado = 0;
                $cuotasPendientes = 0;
                $ofertasData = [];
                
                foreach ($inscripciones as $insc) {
                    $ofertaPlan = 0;
                    $ofertaPagado = 0;
                    $ofertaPendientes = 0;
                    
                    foreach ($insc->cuotas as $cuota) {
                        $ofertaPlan += $cuota->monto_bs;
                        $ofertaPagado += $cuota->pagosCuota->sum('monto_bs');
                        if ($cuota->estado !== 'Pagado') {
                            $ofertaPendientes++;
                        }
                    }
                    
                    $ofertasData[] = [
                        'oferta_id' => $insc->ofertaAcademica->id ?? null,
                        'inscripcion_id' => $insc->id,
                        'oferta_codigo' => $insc->ofertaAcademica->codigo ?? '—',
                        'oferta_nombre' => $insc->ofertaAcademica->programa->nombre ?? ($insc->ofertaAcademica->nombre ?? '—'),
                        'estado_inscripcion' => $insc->estado,
                        'total_plan' => $ofertaPlan,
                        'total_pagado' => $ofertaPagado,
                        'saldo' => $ofertaPlan - $ofertaPagado,
                        'cuotas_pendientes' => $ofertaPendientes,
                    ];
                    
                    $totalPlan += $ofertaPlan;
                    $totalPagado += $ofertaPagado;
                    $cuotasPendientes += $ofertaPendientes;
                }
                
                return [
                    'id' => $estudiante->id,
                    'estudiante_id' => $estudiante->id,
                    'persona_id' => $persona->id,
                    'nombre_completo' => trim($persona->nombres . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno),
                    'carnet' => $persona->carnet,
                    'celular' => $persona->celular,
                    'correo' => $persona->correo,
                    'estado' => $estudiante->estado,
                    'total_plan' => $totalPlan,
                    'total_pagado' => $totalPagado,
                    'saldo' => $totalPlan - $totalPagado,
                    'cuotas_pendientes' => $cuotasPendientes,
                    'ofertas' => $ofertasData,
                ];
            });

        return response()->json(['data' => $estudiantes]);
    }

    public function buscarCarnet(Request $request)
    {
        $carnet = strtoupper(trim($request->carnet));
        $persona = Persona::with(['ciudad.departamento', 'estudios.grado_academico', 'estudios.profesion', 'estudios.universidad'])
            ->where('carnet', $carnet)->first();

        if (!$persona) {
            return response()->json(['encontrado' => false]);
        }

        $estudiante = Estudiante::where('persona_id', $persona->id)->first();

        $estudiosData = $persona->estudios->map(function ($est) {
            return [
                'grado_academico_id'     => $est->grados_academico_id,
                'grado_academico_nombre' => $est->grado_academico?->nombre,
                'profesion_id'           => $est->profesione_id,
                'profesion_nombre'       => $est->profesion?->nombre,
                'universidad_id'         => $est->universidade_id,
                'universidad_nombre'     => $est->universidad?->nombre,
                'estado'                 => $est->estado,
                'principal'              => (bool) $est->principal,
            ];
        });

        $personaData = array_merge($persona->toArray(), [
            'estudios' => $estudiosData->toArray(),
        ]);

        return response()->json([
            'encontrado'    => true,
            'ya_estudiante' => $estudiante ? true : false,
            'estudiante_id' => $estudiante ? $estudiante->id : null,
            'persona'       => $personaData,
        ]);
    }

    public function guardarPersona(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carnet'           => 'required|string|max:20|unique:personas,carnet',
            'nombres'          => 'required|string|max:100',
            'apellido_paterno' => 'nullable|string|max:80',
            'apellido_materno' => 'nullable|string|max:80',
            'correo'           => 'required|email|max:150|unique:personas,correo',
            'celular'          => 'required|string|max:20',
            'telefono'         => 'nullable|string|max:20',
            'direccion'        => 'nullable|string|max:200',
            'ciudade_id'       => 'required|exists:ciudades,id',
            'sexo'             => 'required|in:M,F',
            'estado_civil'     => 'required|in:Soltero/a,Casado/a,Divorciado/a,Viudo/a,Unión Libre',
            'fecha_nacimiento' => 'nullable|date',
            'expedido'         => 'nullable|string|max:10',
            'fotografia'       => 'nullable|image|max:2048',
        ], [
            'carnet.required'      => 'El carnet es obligatorio.',
            'carnet.unique'        => 'El carnet ya está registrado.',
            'nombres.required'     => 'Los nombres son obligatorios.',
            'correo.required'      => 'El correo electrónico es obligatorio.',
            'correo.email'         => 'El correo no tiene un formato válido.',
            'correo.unique'        => 'El correo ya está registrado.',
            'celular.required'     => 'El celular es obligatorio.',
            'ciudade_id.required'  => 'La ciudad es obligatoria.',
            'ciudade_id.exists'    => 'La ciudad seleccionada no es válida.',
            'sexo.required'        => 'El sexo es obligatorio.',
            'sexo.in'              => 'El sexo debe ser M o F.',
            'estado_civil.required'=> 'El estado civil es obligatorio.',
            'estado_civil.in'      => 'El estado civil seleccionado no es válido.',
            'fotografia.image'     => 'El archivo debe ser una imagen.',
            'fotografia.max'       => 'La imagen no debe exceder 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $persona = Persona::create($validator->validated());

        // Guardar fotografía si se subió
        if ($request->hasFile('fotografia')) {
            $imagen = $request->file('fotografia');
            $nombreArchivo = 'persona_' . $persona->id . '_' . time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images/personas'), $nombreArchivo);
            $persona->update(['fotografia' => $nombreArchivo]);
        }

        // Guardar estudios académicos si se enviaron
        if ($request->filled('estudios_json')) {
            $estudiosData = json_decode($request->estudios_json, true);
            if (is_array($estudiosData)) {
                foreach ($estudiosData as $idx => $estudio) {
                    if (!empty($estudio['grados_academico_id'])) {
                        $persona->estudios()->create([
                            'grados_academico_id' => $estudio['grados_academico_id'],
                            'universidade_id'     => !empty($estudio['universidade_id']) ? $estudio['universidade_id'] : null,
                            'profesione_id'       => !empty($estudio['profesione_id'])   ? $estudio['profesione_id']   : null,
                            'principal'           => $idx === 0 ? 1 : 0,
                            'estado'              => 'Concluido',
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Persona registrada correctamente.',
            'data'    => $persona,
        ]);
    }

    public function validarCampos(Request $request)
    {
        try {
            $carnetDisponible = true;
            $correoDisponible = true;
            $mensajes = [];

            if ($request->has('carnet') && $request->carnet) {
                $existe = Persona::where('carnet', $request->carnet)->exists();
                $carnetDisponible = !$existe;
                $mensajes['carnet'] = $existe ? 'El carnet ya está registrado' : 'Carnet disponible';
            }

            if ($request->has('correo') && $request->correo) {
                $existe = Persona::where('correo', $request->correo)->exists();
                $correoDisponible = !$existe;
                $mensajes['correo'] = $existe ? 'El correo ya está registrado' : 'Correo disponible';
            }

            return response()->json([
                'disponible' => [
                    'carnet' => $carnetDisponible,
                    'correo' => $correoDisponible
                ],
                'mensajes' => $mensajes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al validar campos',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function registrarEstudiante(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'persona_id' => 'required|exists:personas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $yaEstudiante = Estudiante::where('persona_id', $request->persona_id)->exists();
        if ($yaEstudiante) {
            return response()->json(['success' => false, 'message' => 'Esta persona ya está registrada como estudiante.'], 422);
        }

        $estudiante = Estudiante::create([
            'persona_id' => $request->persona_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estudiante registrado correctamente.',
            'data'    => $estudiante->load(['persona.ciudad']),
        ]);
    }

    public function obtenerEstudiante($id)
    {
        $estudiante = Estudiante::with([
            'persona.ciudad',
            'persona.estudios.grado_academico',
            'persona.estudios.profesion',
            'persona.estudios.universidad',
        ])->findOrFail($id);
        return response()->json(['data' => $estudiante]);
    }

    public function actualizar(Request $request, $id)
    {
        $estudiante = Estudiante::with('persona')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'carnet'           => 'required|string|max:20|unique:personas,carnet,' . $estudiante->persona_id,
            'nombres'          => 'required|string|max:100',
            'apellido_paterno' => 'nullable|string|max:80',
            'apellido_materno' => 'nullable|string|max:80',
            'correo'           => 'nullable|email|max:150|unique:personas,correo,' . $estudiante->persona_id,
            'celular'          => 'nullable|string|max:20',
            'telefono'         => 'nullable|string|max:20',
            'direccion'        => 'nullable|string|max:200',
            'ciudade_id'       => 'nullable|exists:ciudades,id',
            'sexo'             => 'nullable|in:M,F',
            'estado_civil'     => 'nullable|in:Soltero/a,Casado/a,Divorciado/a,Viudo/a,Unión Libre',
            'fecha_nacimiento' => 'nullable|date',
            'expedido'         => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $estudiante->persona->update($validator->validated());
        $estudiante->persona->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Estudiante actualizado correctamente.',
            'data'    => $estudiante->load(['persona.ciudad']),
        ]);
    }

    public function eliminar($id)
    {
        $estudiante = Estudiante::find($id);
        if (!$estudiante) {
            return response()->json(['success' => false, 'message' => 'Estudiante no encontrado.'], 404);
        }

        $estudiante->delete();

        return response()->json(['success' => true, 'message' => 'Estudiante eliminado correctamente.']);
    }

    public function listarDepartamentos()
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        return response()->json(['data' => $departamentos]);
    }

    public function listarCiudades()
    {
        $ciudades = Ciudade::orderBy('nombre')->get();
        return response()->json(['data' => $ciudades]);
    }

    public function verificarCarnetPersona(Request $request)
    {
        $existe = Persona::where('carnet', strtoupper(trim($request->carnet)))->exists();
        return response()->json(['existe' => $existe]);
    }

    public function verificarCorreoPersona(Request $request)
    {
        $existe = Persona::where('correo', trim($request->correo))->exists();
        return response()->json(['existe' => $existe]);
    }

    public function registrarPago(Request $request, $cuotaId)
    {
        try {
            $cuota = Cuota::find($cuotaId);
            
            if (!$cuota) {
                return response()->json(['success' => false, 'message' => 'Cuota no encontrada.'], 404);
            }
            
            if ($cuota->estado === 'Pagado') {
                return response()->json(['success' => false, 'message' => 'La cuota ya está pagada.'], 409);
            }
            
            $validator = Validator::make($request->all(), [
                'monto' => 'required|numeric|min:0.01',
                'fecha_pago' => 'required|date',
                'metodo' => 'required|in:Efectivo,Qr,Transferencia,Parcial',
                'trabajador_cargo_id' => 'nullable|exists:trabajadores_cargos,id',
                'cuenta_bancaria_id' => 'nullable',
                'referencia' => 'nullable|string|max:255',
            ]);
            
            if ($validator->fails()) {
                \Log::info('registrarPago validation errors: ' . json_encode($validator->errors()->toArray()));
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            
            $monto = $request->monto;
            $fechaPago = $request->fecha_pago;
            $metodo = $request->metodo;
            $efectivo = $request->efectivo ?? 0;
            $qr = $request->qr ?? 0;
            $descuento = $request->descuento ?? 0;
            $trabajadorCargoId = $request->trabajador_cargo_id;
            
            if (!$trabajadorCargoId && auth()->user()) {
                $user = auth()->user();
                $personaId = $user->persona_id;
                if ($personaId) {
                    $trabajador = \App\Models\Trabajadore::where('persona_id', $personaId)->first();
                    if ($trabajador) {
                        $cargo = \App\Models\TrabajadoresCargo::where('trabajadore_id', $trabajador->id)
                            ->where('estado', 'Vigente')
                            ->first();
                        $trabajadorCargoId = $cargo?->id;
                    }
                }
            }
            
            if (!$trabajadorCargoId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró un cargo activo para el usuario autenticado. Contacte al administrador del sistema.'
                ], 422);
            }
            
            $montoPendiente = $cuota->pago_pendiente_bs ?? $cuota->monto_bs;
            $totalAbonado = $monto + $descuento;
            
            // Obtener estudiante_id
            $estudianteId = $cuota->inscripcion->estudiante_id;
            
            // Determinar estado del pago (monto + descuento vs monto pendiente)
            $estadoPago = ($totalAbonado >= $montoPendiente) ? 'Pagado' : 'Parcial';
            
            // Crear registro en tabla pagos
            $pago = Pago::create([
                'trabajadore_cargo_id' => $trabajadorCargoId,
                'monto_total' => $monto,
                'descuento_bs' => $descuento,
                'tipo_pago' => $metodo,
                'fecha_pago' => $fechaPago,
                'estado' => $estadoPago,
            ]);
            
            // Crear registro en tabla pagos_cuotas
            PagosCuota::create([
                'pago_id' => $pago->id,
                'cuota_id' => $cuotaId,
                'monto_bs' => $monto,
                'fecha_pago' => $fechaPago,
            ]);
            
            // Crear detalle según método
            if ($metodo === 'Parcial') {
                // Dos registros: uno para efectivo y uno para QR
                if ($efectivo > 0) {
                    $detalleEfectivo = Detalle::create([
                        'pago_id' => $pago->id,
                        'tipo_pago' => 'Efectivo',
                        'monto_bs' => $efectivo,
                    ]);
                    // Registrar en caja
                    $this->registrarEnCaja($pago, $detalleEfectivo, $efectivo, $cuota->nombre);
                }
                if ($qr > 0) {
                    $detalleQr = Detalle::create([
                        'pago_id' => $pago->id,
                        'tipo_pago' => 'Qr',
                        'monto_bs' => $qr,
                    ]);
                    // Registrar en cuenta bancaria
                    $this->registrarEnBanco($pago, $detalleQr, 'Qr', $qr, $cuota->nombre, $request);
                }
            } else {
                $detalle = Detalle::create([
                    'pago_id' => $pago->id,
                    'tipo_pago' => $metodo,
                    'monto_bs' => $monto,
                ]);
                
                // Registrar según método de pago
                if ($metodo === 'Efectivo') {
                    $this->registrarEnCaja($pago, $detalle, $monto, $cuota->nombre);
                } elseif (in_array($metodo, ['Qr', 'Transferencia'])) {
                    $this->registrarEnBanco($pago, $detalle, $metodo, $monto, $cuota->nombre, $request);
                }
            }
            
            // Actualizar cuota (restar monto + descuento)
            if ($totalAbonado >= $montoPendiente) {
                $cuota->update([
                    'estado' => 'Pagado',
                    'pago_pendiente_bs' => 0,
                    'fecha_pago' => $fechaPago,
                    'descuento_bs' => $descuento,
                ]);
            } else {
                $cuota->update([
                    'estado' => 'Parcial',
                    'pago_pendiente_bs' => $montoPendiente - $totalAbonado,
                    'fecha_pago' => $fechaPago,
                    'descuento_bs' => $descuento,
                ]);
            }
            
            $nuevaDeuda = Cuota::where('inscripcione_id', $cuota->inscripcione_id)
                ->whereIn('estado', ['Pendiente', 'Vencido', 'Parcial'])
                ->get()->sum(fn($c) => $c->pago_pendiente_bs ?? $c->monto_bs);

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado correctamente.',
                'data' => [
                    'pago_id'     => $pago->id,
                    'recibo'      => $pago->recibo,
                    'total_pagado'=> $monto,
                    'nueva_deuda' => $nuevaDeuda,
                ],
                'redirect' => "/admin/estudiantes/{$estudianteId}/detalle?tab=contable"
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function pagoMasivo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'estudiante_id' => 'required|exists:estudiantes,id',
                'inscripcion_id' => 'required|exists:inscripciones,id',
                'monto' => 'required|numeric|min:0.01',
                'descuento' => 'nullable|numeric|min:0',
                'metodo' => 'required|in:Efectivo,Qr,Transferencia,Parcial',
                'trabajador_cargo_id' => 'nullable|exists:trabajadores_cargos,id',
                'fecha_pago' => 'required|date',
                'cuotas' => 'nullable|array',
                'cuotas.*.id' => 'required|exists:cuotas,id',
                'cuotas.*.monto' => 'required|numeric|min:0.01',
                'cuenta_bancaria_id' => 'nullable',
                'referencia' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $estudianteId = $request->estudiante_id;
            $inscripcionId = $request->inscripcion_id;
            $monto = $request->monto;
            $descuento = $request->descuento ?? 0;
            $metodo = $request->metodo;
            $efectivo = $request->efectivo ?? 0;
            $qr = $request->qr ?? 0;
            $trabajadorCargoId = $request->trabajador_cargo_id;
            
            if (!$trabajadorCargoId && auth()->user()) {
                $user = auth()->user();
                $personaId = $user->persona_id;
                if ($personaId) {
                    $trabajador = \App\Models\Trabajadore::where('persona_id', $personaId)->first();
                    if ($trabajador) {
                        $cargo = \App\Models\TrabajadoresCargo::where('trabajadore_id', $trabajador->id)
                            ->where('estado', 'Vigente')
                            ->first();
                        $trabajadorCargoId = $cargo?->id;
                    }
                }
            }

            if (!$trabajadorCargoId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró un cargo activo para el usuario autenticado. Contacte al administrador del sistema.'
                ], 422);
            }

            $fechaPago = $request->fecha_pago;

            $inscripcion = Inscripcione::where('id', $inscripcionId)
                ->where('estudiante_id', $estudianteId)
                ->first();

            if (!$inscripcion) {
                return response()->json(['success' => false, 'message' => 'La inscripción no pertenece al estudiante.'], 404);
            }

            $cuotasData = $request->cuotas ?? [];
            
            // Si hay cuotas especificadas, usarlas; si no, buscar todas las pendientes
            if (!empty($cuotasData)) {
                $cuotasIds = array_column($cuotasData, 'id');
                $cuotas = Cuota::where('inscripcione_id', $inscripcionId)
                    ->whereIn('id', $cuotasIds)
                    ->get();
            } else {
                $cuotas = Cuota::where('inscripcione_id', $inscripcionId)
                    ->whereIn('estado', ['Pendiente', 'Vencido', 'Parcial'])
                    ->orderBy('nombre')
                    ->orderBy('n_cuota')
                    ->get();
            }

            if ($cuotas->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No hay cuotas pendientes.'], 400);
            }

            $deudaTotal = $cuotas->sum(function ($c) {
                return $c->pago_pendiente_bs ?? $c->monto_bs;
            });

            $montoDisponible = $monto + $descuento;

            if ($montoDisponible > $deudaTotal) {
                return response()->json(['success' => false, 'message' => 'El monto excede la deuda total (Bs. ' . number_format($deudaTotal, 2) . ').'], 400);
            }

            $cuotasAgrupadas = $cuotas->groupBy('nombre');

            $pagosRealizados = [];
            $montoRestante = $montoDisponible;

            $pago = Pago::create([
                'trabajadore_cargo_id' => $trabajadorCargoId,
                'monto_total' => $monto,
                'descuento_bs' => $descuento,
                'tipo_pago' => $metodo,
                'fecha_pago' => $fechaPago,
                'estado' => 'Pagado',
            ]);

            if ($metodo === 'Parcial') {
                if ($efectivo > 0) {
                    $detalleEfectivo = Detalle::create([
                        'pago_id' => $pago->id,
                        'tipo_pago' => 'Efectivo',
                        'monto_bs' => $efectivo,
                    ]);
                    $this->registrarEnCaja($pago, $detalleEfectivo, $efectivo, 'Pago masivo');
                }
                if ($qr > 0) {
                    $detalleQr = Detalle::create([
                        'pago_id' => $pago->id,
                        'tipo_pago' => 'Qr',
                        'monto_bs' => $qr,
                    ]);
                    $this->registrarEnBanco($pago, $detalleQr, 'Qr', $qr, 'Pago masivo', $request);
                }
            } else {
                $detalle = Detalle::create([
                    'pago_id' => $pago->id,
                    'tipo_pago' => $metodo,
                    'monto_bs' => $monto,
                ]);
                
                if ($metodo === 'Efectivo') {
                    $this->registrarEnCaja($pago, $detalle, $monto, 'Pago masivo');
                } elseif (in_array($metodo, ['Qr', 'Transferencia'])) {
                    $this->registrarEnBanco($pago, $detalle, $metodo, $monto, 'Pago masivo', $request);
                }
            }

            foreach ($cuotasAgrupadas as $nombre => $grupoCuotas) {
                foreach ($grupoCuotas as $cuota) {
                    if ($montoRestante <= 0) break;

                    $pendiente = $cuota->pago_pendiente_bs ?? $cuota->monto_bs;

                    if ($pendiente <= 0) continue;

                    // Si hay cuotas del frontend, usar esos montos; si no, calcular automáticamente
                    if (!empty($cuotasData)) {
                        $cuotaDataItem = collect($cuotasData)->firstWhere('id', $cuota->id);
                        $montoACuota = $cuotaDataItem ? $cuotaDataItem['monto'] : 0;
                    } else {
                        $montoACuota = min($pendiente, $montoRestante);
                    }

                    if ($montoACuota <= 0) continue;

                    PagosCuota::create([
                        'pago_id' => $pago->id,
                        'cuota_id' => $cuota->id,
                        'monto_bs' => $montoACuota,
                        'fecha_pago' => $fechaPago,
                    ]);

                    $nuevoPendiente = $pendiente - $montoACuota;

                    if ($nuevoPendiente <= 0) {
                        $cuota->update([
                            'estado' => 'Pagado',
                            'pago_pendiente_bs' => 0,
                            'fecha_pago' => $fechaPago,
                            'descuento_bs' => $descuento,
                        ]);
                    } else {
                        $cuota->update([
                            'estado' => 'Parcial',
                            'pago_pendiente_bs' => $nuevoPendiente,
                            'fecha_pago' => $fechaPago,
                            'descuento_bs' => $descuento,
                        ]);
                    }

                    $pagosRealizados[] = [
                        'cuota_id' => $cuota->id,
                        'nombre' => $cuota->nombre,
                        'monto' => $montoACuota,
                        'tipo' => $nuevoPendiente <= 0 ? 'Completo' : 'Parcial',
                    ];

                    $montoRestante -= $montoACuota;
                }
            }

            $nuevaDeuda = Cuota::where('inscripcione_id', $inscripcionId)
                ->whereIn('estado', ['Pendiente', 'Vencido', 'Parcial'])
                ->get()
                ->sum(function ($c) {
                    return $c->pago_pendiente_bs ?? $c->monto_bs;
                });

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado correctamente. Se pagaron ' . count($pagosRealizados) . ' cuota(s).',
                'data' => [
                    'pago_id' => $pago->id,
                    'recibo' => $pago->recibo,
                    'pagos_cuota' => $pagosRealizados,
                    'total_pagado' => $monto,
                    'nueva_deuda' => $nuevaDeuda,
                ],
                'redirect' => "/admin/estudiantes/{$estudianteId}/detalle?tab=contable"
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function generarReciboPdf($pagoId)
    {
        $pago = Pago::with([
            'trabajadorCargo.trabajador.persona',
            'detalles',
            'pagosCuotas.cuota.inscripcion.estudiante.persona',
            'pagosCuotas.cuota.inscripcion.ofertaAcademica.posgrado',
            'pagosCuotas.cuota.inscripcion.planesPago',
            'pagosCuotas.cuota.inscripcion.ofertaAcademica.sucursal.sede'
        ])
            ->findOrFail($pagoId);

        $pdf = \Pdf::loadView('admin.estudiantes.recibo', [
            'pago' => $pago,
        ]);

        if (request()->query('inline')) {
            return $pdf->stream('recibo-' . ($pago->recibo ?? $pago->id) . '.pdf');
        }

        return $pdf->download('recibo-' . ($pago->recibo ?? $pago->id) . '.pdf');
    }

    private function registrarEnCaja($pago, $detalle, $monto, $descripcion)
    {
        $trabajadorCargoId = $pago->trabajadore_cargo_id;
        
        $caja = \App\Models\Caja::firstOrCreate(
            ['trabajadore_cargo_id' => $trabajadorCargoId],
            ['nombre' => 'Caja Chica', 'monto_inicial' => 0, 'monto_actual' => 0, 'estado' => 'Abierta']
        );
        
        $detalle->caja_id = $caja->id;
        $detalle->save();
        
        \App\Models\CajaMovimiento::create([
            'caja_id' => $caja->id,
            'pago_id' => $pago->id,
            'tipo' => 'Ingreso',
            'monto' => $monto,
            'descripcion' => $descripcion,
        ]);
        
        $caja->increment('monto_actual', $monto);
    }

    private function registrarEnBanco($pago, $detalle, $tipoPago, $monto, $descripcion, $request)
    {
        $cuentaId = $request->input('cuenta_bancaria_id');
        
        if (!$cuentaId) {
            $cuenta = \App\Models\CuentaBancaria::where('es_principal', true)->where('estado', true)->first();
            $cuentaId = $cuenta?->id;
        }
        
        if ($cuentaId) {
            $detalle->cuenta_bancaria_id = $cuentaId;
            $detalle->referencia = $request->input('referencia', '');
            $detalle->save();
            
            \App\Models\MovimientoBanco::create([
                'cuenta_bancaria_id' => $cuentaId,
                'pago_id' => $pago->id,
                'tipo' => 'Ingreso',
                'monto' => $monto,
                'referencia' => $request->input('referencia', ''),
                'descripcion' => $descripcion,
            ]);
        }
    }
}
