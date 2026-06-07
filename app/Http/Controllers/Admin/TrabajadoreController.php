<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Ciudade;
use App\Models\Departamento;
use App\Models\Persona;
use App\Models\Sede;
use App\Models\Sucursale;
use App\Models\Trabajadore;
use App\Models\TrabajadoresCargo;
use App\Models\User;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TrabajadoreController extends Controller
{
    public function index()
    {
        return view('admin.trabajadores.index');
    }

    public function listar(Request $request)
    {
        $query = Trabajadore::with([
            'persona.usuario',
            'persona.ciudad',
            'trabajadores_cargos.cargo',
            'trabajadores_cargos.sucursale.sede'
        ]);

        if ($request->filled('cargo_id')) {
            $query->whereHas('trabajadores_cargos', function ($q) use ($request) {
                $q->where('cargo_id', $request->cargo_id);
            });
        }

        $trabajadores = $query->orderBy('id', 'desc')->get();

        $data = $trabajadores->map(function ($t) {
            $arr = $t->toArray();
            $persona = $t->persona;
            $usuario = $persona?->usuario;
            $arr['tiene_cuenta_sistema'] = $usuario !== null && (bool) $usuario->acceso_admin;
            $arr['tiene_cuenta_moodle']  = $usuario !== null && (bool) $usuario->acceso_virtual;
            $arr['tiene_usuario']        = $usuario !== null;
            $arr['acceso_admin']         = (bool) ($usuario?->acceso_admin);
            $arr['acceso_virtual']       = (bool) ($usuario?->acceso_virtual);
            $arr['usuario_id']           = $usuario?->id;
            $arr['usuario_username']     = $usuario?->username;
            $arr['usuario_moodle_password'] = $usuario?->moodle_password;
            return $arr;
        });

        return response()->json(['data' => $data]);
    }

    public function buscarCarnet(Request $request)
    {
        $carnet = strtoupper(trim($request->carnet));
        $persona = Persona::with('ciudad')->where('carnet', $carnet)->first();

        if (!$persona) {
            return response()->json(['encontrado' => false]);
        }

        // Verificar si ya es trabajador
        $yaTrabajador = Trabajadore::where('persona_id', $persona->id)->exists();

        return response()->json([
            'encontrado'    => true,
            'ya_trabajador' => $yaTrabajador,
            'persona'       => $persona,
        ]);
    }

    public function listarCargos()
    {
        $cargos = Cargo::orderBy('nombre', 'asc')->get(['id', 'nombre']);
        return response()->json(['data' => $cargos]);
    }

    public function listarCargosActivos()
    {
        $cargos = TrabajadoresCargo::with(['trabajador.persona', 'cargo'])
            ->where('estado', 'Activo')
            ->orderBy('fecha_ingreso', 'desc')
            ->get()
            ->map(function ($tc) {
                $nombre = $tc->trabajador?->persona?->nombres . ' ' . $tc->trabajador?->persona?->apellido_paterno;
                $cargo = $tc->cargo?->nombre ?? $tc->nombre_cargo ?? 'Sin cargo';
                return [
                    'id' => $tc->id,
                    'nombre' => $nombre,
                    'cargo' => $cargo,
                ];
            });
        return response()->json(['data' => $cargos]);
    }

    public function listarSedes()
    {
        $sedes = Sede::orderBy('nombre', 'asc')->get(['id', 'nombre']);
        return response()->json(['data' => $sedes]);
    }

    public function listarSucursalesPorSede(Request $request)
    {
        $sedeId = $request->sede_id;
        $sucursales = Sucursale::where('sede_id', $sedeId)
            ->orderBy('nombre', 'asc')
            ->get(['id', 'nombre', 'sede_id', 'color', 'direccion']);
        return response()->json(['data' => $sucursales]);
    }

    public function listarDepartamentos()
    {
        $departamentos = Departamento::orderBy('nombre', 'asc')->get(['id', 'nombre']);
        return response()->json(['data' => $departamentos]);
    }

    public function listarCiudades()
    {
        $ciudades = Ciudade::orderBy('nombre', 'asc')->get(['id', 'nombre', 'departamento_id']);
        return response()->json(['data' => $ciudades]);
    }

    public function verificarCarnetPersona(Request $request)
    {
        $carnet = strtoupper(trim($request->carnet));
        $query  = Persona::where('carnet', $carnet);
        if ($request->filled('id')) {
            $query->where('id', '!=', $request->id);
        }
        return response()->json(['existe' => $query->exists()]);
    }

    public function verificarCorreoPersona(Request $request)
    {
        $correo = strtolower(trim($request->correo));
        $query  = Persona::where('correo', $correo);
        if ($request->filled('id')) {
            $query->where('id', '!=', $request->id);
        }
        return response()->json(['existe' => $query->exists()]);
    }

    public function guardarPersona(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carnet'           => 'required|string|max:20|unique:personas,carnet',
            'expedido'         => 'nullable|string|max:10',
            'nombres'          => 'required|string|max:100',
            'apellido_paterno' => 'nullable|string|max:80',
            'apellido_materno' => 'nullable|string|max:80',
            'sexo'             => 'nullable|in:M,F',
            'estado_civil'     => 'nullable|in:Soltero/a,Casado/a,Divorciado/a,Viudo/a,Unión Libre',
            'fecha_nacimiento' => 'nullable|date',
            'correo'           => 'nullable|email|max:150|unique:personas,correo',
            'direccion'        => 'nullable|string|max:200',
            'celular'          => 'nullable|string|max:20',
            'telefono'         => 'nullable|string|max:20',
            'ciudade_id'       => 'nullable|exists:ciudades,id',
            'fotografia'       => 'nullable|image|max:2048',
        ], [
            'carnet.required'  => 'El número de carnet es obligatorio.',
            'carnet.unique'    => 'Este carnet ya está registrado.',
            'nombres.required' => 'El nombre es obligatorio.',
            'correo.email'     => 'El correo no tiene un formato válido.',
            'correo.unique'    => 'Este correo ya está registrado.',
            'fotografia.image'  => 'El archivo debe ser una imagen.',
            'fotografia.max'   => 'La imagen no debe exceder 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        if (empty(trim($request->apellido_paterno ?? '')) && empty(trim($request->apellido_materno ?? ''))) {
            return response()->json([
                'success' => false,
                'errors'  => ['apellidos' => ['Debe registrar al menos un apellido (paterno o materno).']],
            ], 422);
        }

        $persona = Persona::create([
            'carnet'           => strtoupper($request->carnet),
            'expedido'         => $request->expedido ? strtoupper($request->expedido) : null,
            'nombres'          => strtoupper($request->nombres),
            'apellido_paterno' => $request->apellido_paterno ? strtoupper($request->apellido_paterno) : null,
            'apellido_materno' => $request->apellido_materno ? strtoupper($request->apellido_materno) : null,
            'sexo'             => $request->sexo ?: null,
            'estado_civil'     => $request->estado_civil ?: null,
            'fecha_nacimiento' => $request->fecha_nacimiento ?: null,
            'correo'           => $request->correo ? strtolower($request->correo) : null,
            'direccion'        => $request->direccion ? strtoupper($request->direccion) : null,
            'celular'          => $request->celular ?: null,
            'telefono'         => $request->telefono ?: null,
            'ciudade_id'       => $request->ciudade_id ?: null,
        ]);

        // Guardar fotografía si se subió
        if ($request->hasFile('fotografia')) {
            $imagen = $request->file('fotografia');
            $nombreArchivo = 'persona_' . $persona->id . '_' . time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images/personas'), $nombreArchivo);
            $persona->update(['fotografia' => $nombreArchivo]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Persona registrada correctamente.',
            'data'    => $persona,
        ]);
    }

    public function asignarTrabajador(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'persona_id'        => 'required|exists:personas,id',
            'cargos'            => 'required|array|min:1',
            'cargos.*.cargo_id' => 'required|exists:cargos,id',
            'cargos.*.sucursale_id' => 'required|exists:sucursales,id',
            'cargos.*.estado'   => 'required|in:Vigente,No Vigente',
            'cargos.*.fecha_ingreso' => 'required|date',
            'cargos.*.fecha_termino' => 'nullable|date|after_or_equal:cargos.*.fecha_ingreso',
        ], [
            'persona_id.required'    => 'La persona es obligatoria.',
            'cargos.required'        => 'Debe seleccionar al menos un cargo.',
            'cargos.min'             => 'Debe seleccionar al menos un cargo.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $trabajador = Trabajadore::where('persona_id', $request->persona_id)->first();
        if (!$trabajador) {
            $trabajador = Trabajadore::create(['persona_id' => $request->persona_id]);
        }

        foreach ($request->cargos as $cargoData) {
            TrabajadoresCargo::create([
                'trabajadore_id' => $trabajador->id,
                'sucursale_id'   => $cargoData['sucursale_id'],
                'cargo_id'       => $cargoData['cargo_id'],
                'estado'         => $cargoData['estado'],
                'fecha_ingreso'  => $cargoData['fecha_ingreso'],
                'fecha_termino'  => $cargoData['fecha_termino'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Trabajador asignado correctamente con ' . count($request->cargos) . ' cargo(s).',
            'data'    => $trabajador->load(['persona', 'trabajadores_cargos.cargo', 'trabajadores_cargos.sucursale']),
        ]);
    }

    public function obtenerTrabajador($id)
    {
        $trabajador = Trabajadore::with([
            'persona.ciudad',
            'trabajadores_cargos.cargo',
            'trabajadores_cargos.sucursale.sede'
        ])->findOrFail($id);

        return response()->json(['data' => $trabajador]);
    }

    public function actualizarCargos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trabajadore_id'    => 'required|exists:trabajadores,id',
            'cargos_agregar'    => 'nullable|array',
            'cargos_agregar.*.cargo_id' => 'required_with:cargos_agregar|exists:cargos,id',
            'cargos_agregar.*.sucursale_id' => 'required_with:cargos_agregar|exists:sucursales,id',
            'cargos_agregar.*.estado'   => 'required_with:cargos_agregar|in:Vigente,No Vigente',
            'cargos_agregar.*.fecha_ingreso' => 'required_with:cargos_agregar|date',
            'cargos_agregar.*.fecha_termino' => 'nullable|date|after_or_equal:cargos_agregar.*.fecha_ingreso',
            'cargos_eliminar'   => 'nullable|array',
            'cargos_eliminar.*' => 'required_with:cargos_eliminar|exists:trabajadores_cargos,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $trabajador = Trabajadore::findOrFail($request->trabajadore_id);

        if ($request->filled('cargos_eliminar')) {
            $trabajador->trabajadores_cargos()->whereIn('id', $request->cargos_eliminar)->delete();
        }

        if ($request->filled('cargos_agregar')) {
            foreach ($request->cargos_agregar as $cargoData) {
                TrabajadoresCargo::create([
                    'trabajadore_id' => $trabajador->id,
                    'sucursale_id'   => $cargoData['sucursale_id'],
                    'cargo_id'       => $cargoData['cargo_id'],
                    'estado'         => $cargoData['estado'],
                    'fecha_ingreso'  => $cargoData['fecha_ingreso'],
                    'fecha_termino'  => $cargoData['fecha_termino'] ?? null,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Cargos actualizados correctamente.',
            'data'    => $trabajador->load(['persona', 'trabajadores_cargos.cargo', 'trabajadores_cargos.sucursale.sede']),
        ]);
    }

    public function eliminar($id)
    {
        $trabajador = Trabajadore::find($id);
        if (!$trabajador) {
            return response()->json(['success' => false, 'message' => 'Trabajador no encontrado.'], 404);
        }

        $trabajador->trabajadores_cargos()->delete();
        $trabajador->delete();

        return response()->json(['success' => true, 'message' => 'Trabajador eliminado correctamente.']);
    }

    public function eliminarCargo($trabajadorId, $cargoId)
    {
        $trabajador = Trabajadore::find($trabajadorId);
        if (!$trabajador) {
            return response()->json(['success' => false, 'message' => 'Trabajador no encontrado.'], 404);
        }

        $tc = $trabajador->trabajadores_cargos()->find($cargoId);
        if (!$tc) {
            return response()->json(['success' => false, 'message' => 'Cargo asignado no encontrado.'], 404);
        }

        $tc->delete();
        return response()->json(['success' => true, 'message' => 'Cargo removido correctamente.']);
    }

    public function listarCargosParaUsuario()
    {
        try {
            // Verificar autenticación
            if (!auth()->check()) {
                return response()->json([
                    'data' => [],
                    'default' => null,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $user = auth()->user();

            // Verificar relación con Persona
            if (!$user->persona) {
                return response()->json([
                    'data' => [],
                    'default' => null,
                    'message' => 'Usuario sin persona asociada'
                ]);
            }

            $persona = $user->persona;

            // Verificar relación con Trabajador (usando load para evitar N+1)
            $trabajador = $persona->trabajador;

            if (!$trabajador) {
                return response()->json([
                    'data' => [],
                    'default' => null,
                    'message' => 'trabajador no registrado como trabajador'
                ]);
            }

            // Consultar cargos vigentes del trabajador
            $cargos = \App\Models\TrabajadoresCargo::with(['cargo', 'sucursale.sede'])
                ->where('trabajadore_id', $trabajador->id)
                ->where('estado', 'Vigente') // ← Ajusta según tu lógica: 'Activo', 1, etc.
                ->orderBy('principal', 'desc')
                ->orderBy('fecha_ingreso', 'desc')
                ->get()
                ->map(function ($tc) {
                    return [
                        'id' => $tc->id,
                        'nombre_cargo' => $tc->nombre_cargo,
                        'cargo' => $tc->cargo ? ['nombre' => $tc->cargo->nombre] : null,
                        'sucursale' => $tc->sucursale ? [
                            'nombre' => $tc->sucursale->nombre,
                            'sede' => $tc->sucursale->sede ? ['nombre' => $tc->sucursale->sede->nombre] : null
                        ] : null,
                        'principal' => $tc->principal,
                        'estado' => $tc->estado
                    ];
                });

            // Determinar cargo por defecto: principal o el primero
            $default = $cargos->firstWhere('principal', true)?->id ?? $cargos->first()?->id;

            return response()->json([
                'data' => $cargos,
                'default' => $default,
                'debug' => [
                    'user_id' => $user->id,
                    'persona_id' => $persona->id,
                    'trabajador_id' => $trabajador->id,
                    'cargos_encontrados' => $cargos->count()
                ]
            ]);
        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error en listarCargosParaUsuario: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id() ?? null
            ]);

            return response()->json([
                'data' => [],
                'default' => null,
                'message' => 'Error interno: ' . (app()->environment('local') ? $e->getMessage() : 'Contacte al administrador')
            ], 500);
        }
    }

    public function crearCuentas(Request $request, $id)
    {
        $trabajador = Trabajadore::with(['persona.usuario'])->findOrFail($id);
        $persona    = $trabajador->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        if (!$persona->correo) {
            return response()->json(['success' => false, 'message' => 'El trabajador no tiene correo electrónico registrado. Agréguelo primero.'], 422);
        }

        $password = $this->generarPasswordSistema($persona->carnet);
        $username = $this->generarUsernameMoodle(
            $persona->nombres ?? '',
            $persona->apellido_paterno ?? '',
            $persona->apellido_materno ?? ''
        );
        $nombre   = trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''));

        if ($persona->usuario) {
            $persona->usuario->update([
                'password'        => $password,
                'moodle_password' => $password,
                'estado'          => 'Activo',
            ]);
            $resultado = ['sistema' => [
                'email'    => $persona->correo,
                'username' => $persona->usuario->username,
                'password' => $password,
                'nota'     => 'Cuenta ya existía, se restableció la contraseña.',
            ]];
        } else {
            $emailUsuario = User::where('email', $persona->correo)->where('persona_id', '!=', $persona->id)->first();
            if ($emailUsuario) {
                return response()->json(['success' => false, 'message' => 'El correo ya está en uso por otro usuario del sistema.'], 422);
            }

            User::create([
                'name'            => $nombre,
                'username'        => $username,
                'email'           => $persona->correo,
                'password'        => $password,
                'moodle_password' => $password,
                'role'            => 'moodle',
                'acceso_admin'    => true,
                'acceso_virtual'  => true,
                'estado'          => 'Activo',
                'persona_id'      => $persona->id,
            ]);

            $resultado = ['sistema' => [
                'email'    => $persona->correo,
                'username' => $username,
                'password' => $password,
            ]];
        }

        try {
            $moodle = app(MoodleService::class);

            $existingMoodleUser = $moodle->getUserByField('username', $username);
            $moodleUserId       = $existingMoodleUser ? (int) $existingMoodleUser['id'] : null;

            if (!$moodleUserId && $persona->correo) {
                $byEmail      = $moodle->getUserByField('email', $persona->correo);
                $moodleUserId = $byEmail ? (int) $byEmail['id'] : null;
            }

            if ($moodleUserId) {
                $moodle->updateUserPassword($moodleUserId, $password);
                $resultado['moodle'] = [
                    'username' => $username,
                    'email'    => $persona->correo,
                    'nota'     => 'Usuario ya existía en Moodle, se actualizó la contraseña.',
                ];
            } else {
                $firstname    = trim($persona->nombres ?? '') ?: 'Trabajador';
                $lastname     = trim(($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) ?: 'Sin Apellido';
                $email        = $persona->correo ?: "{$username}@innova.edu.bo";
                $moodleUserId = $moodle->createUser($username, $password, $firstname, $lastname, $email);

                if ($moodleUserId) {
                    $resultado['moodle'] = ['username' => $username, 'password' => $password, 'email' => $email];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Error creating Moodle user for trabajador: ' . $e->getMessage());
        }

        $partes = array_keys($resultado);
        $msg = in_array('moodle', $partes)
            ? 'Cuentas del sistema y Moodle creadas con las mismas credenciales.'
            : 'Cuenta del sistema creada (Moodle no disponible).';

        return response()->json(['success' => true, 'message' => $msg, 'data' => $resultado]);
    }

    public function resetPasswordMoodle(Request $request, $id)
    {
        $trabajador = Trabajadore::with(['persona.usuario'])->findOrFail($id);
        $persona    = $trabajador->persona;

        if (!$persona || !$persona->usuario) {
            return response()->json(['success' => false, 'message' => 'El trabajador no tiene cuenta de usuario.'], 404);
        }

        $username = $persona->usuario->username;
        $password = $this->generarPasswordSistema($persona->carnet);
        $moodle   = app(MoodleService::class);

        $existingMoodleUser = $moodle->getUserByField('username', $username);
        $moodleUserId       = $existingMoodleUser ? (int) $existingMoodleUser['id'] : null;

        if (!$moodleUserId) {
            return response()->json(['success' => false, 'message' => 'No se encontró cuenta de Moodle para este trabajador.'], 404);
        }

        if (!$moodle->updateUserPassword($moodleUserId, $password)) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar la contraseña en Moodle.'], 500);
        }

        $persona->usuario->update(['moodle_password' => $password]);

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
}
