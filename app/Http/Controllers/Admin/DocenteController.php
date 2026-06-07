<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Estudio;
use App\Models\GradoAcademico;
use App\Models\Persona;
use App\Models\Profesione;
use App\Models\Universidade;
use App\Models\User;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocenteController extends Controller
{
    public function index()
    {
        return view('admin.docentes.index');
    }

    public function verDetalle($id)
    {
        $docente = Docente::with([
            'persona.ciudad.departamento',
            'persona.usuario',
            'persona.estudios.grado_academico',
            'persona.estudios.profesion',
            'persona.estudios.universidad',
            'modulos.ofertaAcademica.programa'
        ])->findOrFail($id);

        $gradosAcademicos = GradoAcademico::orderBy('nombre')->get();
        $profesiones      = Profesione::orderBy('nombre')->get();
        $universidades    = Universidade::orderBy('nombre')->get();

        return view('admin.docentes.detalle', compact('docente', 'gradosAcademicos', 'profesiones', 'universidades'));
    }

    public function obtenerDocente($id)
    {
        $docente = Docente::with([
            'persona.ciudad.departamento',
            'persona.usuario',
            'persona.estudios.grado_academico',
            'persona.estudios.profesion',
            'persona.estudios.universidad',
        ])->findOrFail($id);

        $arr = $docente->toArray();
        $arr['tiene_cuenta_sistema'] = $docente->persona && $docente->persona->usuario !== null;
        $arr['tiene_cuenta_moodle'] = $arr['tiene_cuenta_sistema'];
        $arr['usuario_username'] = $docente->persona && $docente->persona->usuario
            ? $docente->persona->usuario->username
            : null;

        return response()->json(['success' => true, 'data' => $arr]);
    }

    public function listar()
    {
        $docentes = Docente::with([
            'persona.ciudad',
            'persona.usuario',
        ])->orderBy('id', 'desc')->get();

        $data = $docentes->map(function ($e) {
            $arr = $e->toArray();
            $usuario = $e->persona?->usuario;
            $arr['tiene_cuenta_sistema'] = $usuario !== null;
            $arr['tiene_cuenta_moodle']  = $usuario !== null;
            $arr['tiene_usuario']        = $usuario !== null;
            $arr['usuario_id']           = $usuario?->id;
            $arr['acceso_admin']         = (bool) ($usuario?->acceso_admin);
            $arr['acceso_virtual']       = (bool) ($usuario?->acceso_virtual);
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

        $docente = Docente::where('persona_id', $persona->id)->first();

        return response()->json([
            'encontrado'    => true,
            'ya_docente'    => $docente ? true : false,
            'docente_id'    => $docente ? $docente->id : null,
            'persona'       => $persona,
        ]);
    }

    public function guardarPersona(Request $request)
    {
        $validator = validator($request->all(), [
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

    public function registrarDocente(Request $request)
    {
        $request->validate([
            'persona_id' => 'required|exists:personas,id',
        ]);

        $yaDocente = Docente::where('persona_id', $request->persona_id)->exists();
        if ($yaDocente) {
            return response()->json(['success' => false, 'message' => 'Esta persona ya está registrada como docente.'], 422);
        }

        $docente = Docente::create([
            'persona_id' => $request->persona_id,
        ]);

        // Si la persona ya tiene un User en el sistema, otorgarle acceso virtual.
        $user = User::where('persona_id', $request->persona_id)->first();
        if ($user && !$user->acceso_virtual) {
            $user->update(['acceso_virtual' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Docente registrado correctamente.',
            'data'    => $docente->load(['persona.ciudad']),
        ]);
    }

    public function crearCuentas(Request $request, $id)
    {
        $docente = Docente::with(['persona.usuario'])->findOrFail($id);
        $persona = $docente->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        if (!$persona->correo) {
            return response()->json(['success' => false, 'message' => 'El docente no tiene correo electrónico registrado. Agréguelo primero.'], 422);
        }

        $password  = $this->generarPasswordSistema($persona->carnet);
        $username  = $this->generarUsername($persona->nombres ?? '', $persona->apellido_paterno ?? '', $persona->apellido_materno ?? '');
        $nombre    = trim(($persona->nombres ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''));

        if ($persona->usuario) {
            $persona->usuario->update([
                'password'        => $password,
                'moodle_password' => $password,
                'estado'          => 'Activo',
            ]);
            $resultado = ['sistema' => ['email' => $persona->correo, 'username' => $persona->usuario->username, 'password' => $password, 'nota' => 'Cuenta ya existía, se restableció la contraseña.']];
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
                'acceso_admin'    => false,
                'acceso_virtual'  => true,
                'estado'          => 'Activo',
                'persona_id'      => $persona->id,
            ]);

            $resultado = ['sistema' => ['email' => $persona->correo, 'username' => $username, 'password' => $password]];
        }

        // ── Cuenta Moodle ───────────────────────────────────────────────────
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
                $firstname    = trim($persona->nombres ?? '') ?: 'Docente';
                $lastname     = trim(($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? '')) ?: 'Sin Apellido';
                $email        = $persona->correo ?: "{$username}@innova.edu.bo";
                $moodleUserId = $moodle->createUser($username, $password, $firstname, $lastname, $email);

                if ($moodleUserId) {
                    $resultado['moodle'] = ['username' => $username, 'password' => $password, 'email' => $email];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Error creating Moodle user for docente: ' . $e->getMessage());
        }

        $partes = array_keys($resultado);
        $msg = in_array('moodle', $partes)
            ? 'Cuentas del sistema y Moodle creadas con las mismas credenciales.'
            : 'Cuenta del sistema creada (Moodle no disponible).';

        return response()->json(['success' => true, 'message' => $msg, 'data' => $resultado]);
    }

    private function generarPasswordSistema(?string $carnet): string
    {
        $digits = preg_replace('/[^0-9]/', '', $carnet ?: '');
        return strlen($digits) >= 7 ? $digits : 'innova' . $digits;
    }

    private function generarUsername(string $nombres, string $apPaterno, string $apMaterno): string
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

    public function guardarEstudio(Request $request, $id)
    {
        $request->validate([
            'grados_academico_id'      => 'required|exists:grados_academicos,id',
            'profesione_id'            => 'required|exists:profesiones,id',
            'universidade_id'          => 'required|exists:universidades,id',
            'estado'                   => 'required|in:En Desarrollo,Concluido',
            'principal'                => 'nullable|boolean',
            'documento_academico'          => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'documento_provision_nacional' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
        ]);

        $docente = Docente::with('persona')->findOrFail($id);
        $persona = $docente->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        $duplicado = Estudio::where('persona_id', $persona->id)
            ->where('grados_academico_id', $request->grados_academico_id)
            ->where('profesione_id', $request->profesione_id)
            ->where('universidade_id', $request->universidade_id)
            ->exists();

        if ($duplicado) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe un estudio con el mismo grado académico, profesión y universidad para este docente.'
            ], 422);
        }

        $esPrincipal = $request->boolean('principal');

        if ($esPrincipal) {
            Estudio::where('persona_id', $persona->id)->where('principal', true)->update(['principal' => false]);
        }

        $estudio = Estudio::create([
            'persona_id'          => $persona->id,
            'grados_academico_id' => $request->grados_academico_id,
            'profesione_id'       => $request->profesione_id,
            'universidade_id'     => $request->universidade_id,
            'estado'              => $request->estado,
            'principal'           => $esPrincipal,
        ]);

        $archivos = [];

        if ($request->hasFile('documento_academico')) {
            $ext    = $request->file('documento_academico')->getClientOriginalExtension();
            $nombre = 'doc_academico_' . $estudio->id . '_' . time() . '.' . $ext;
            $archivos['documento_academico'] = $request->file('documento_academico')->storeAs('documentos', $nombre, 'public');
        }

        if ($request->hasFile('documento_provision_nacional')) {
            $ext2    = $request->file('documento_provision_nacional')->getClientOriginalExtension();
            $nombre2 = 'doc_provision_' . $estudio->id . '_' . time() . '.' . $ext2;
            $archivos['documento_provision_nacional'] = $request->file('documento_provision_nacional')->storeAs('documentos', $nombre2, 'public');
        }

        if (!empty($archivos)) {
            $estudio->update($archivos);
        }

        return response()->json([
            'success' => true,
            'message' => 'Estudio registrado correctamente.',
            'data'    => $estudio->load(['grado_academico', 'profesion', 'universidad']),
        ]);
    }

    public function subirDocumentoEstudio(Request $request, $id, $estudioId)
    {
        $request->validate([
            'tipo_documento' => 'required|in:documento_academico,documento_provision_nacional',
            'archivo'        => 'required|file|mimes:pdf,png,jpg,jpeg|max:2048',
        ]);

        $docente = Docente::with('persona')->findOrFail($id);
        $persona = $docente->persona;

        $estudio = Estudio::where('id', $estudioId)
            ->where('persona_id', $persona->id)
            ->firstOrFail();

        $tipo   = $request->tipo_documento;
        $prefix = $tipo === 'documento_academico' ? 'doc_academico' : 'doc_provision';
        $ext    = $request->file('archivo')->getClientOriginalExtension();
        $nombre = $prefix . '_' . $estudioId . '_' . time() . '.' . $ext;

        $old = $estudio->$tipo;
        if ($old && Storage::disk('public')->exists($old)) {
            Storage::disk('public')->delete($old);
        }

        $path = $request->file('archivo')->storeAs('documentos', $nombre, 'public');

        $verificadoField = $tipo . '_verificado';
        $estudio->update([$tipo => $path, $verificadoField => false]);

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return response()->json([
            'success' => true,
            'message' => 'Documento subido correctamente.',
            'is_pdf'  => $ext === 'pdf',
        ]);
    }

    public function verificarDocumentoEstudio(Request $request, $id, $estudioId)
    {
        $request->validate([
            'tipo_documento' => 'required|in:documento_academico,documento_provision_nacional',
            'accion'         => 'required|in:verificar,quitar',
        ]);

        $docente = Docente::with('persona')->findOrFail($id);
        $persona = $docente->persona;

        $estudio = Estudio::where('id', $estudioId)
            ->where('persona_id', $persona->id)
            ->firstOrFail();

        $verificadoField = $request->tipo_documento . '_verificado';
        $valor = $request->accion === 'verificar';
        $estudio->update([$verificadoField => $valor]);

        return response()->json([
            'success'    => true,
            'message'    => $valor ? 'Documento verificado correctamente.' : 'Verificación removida.',
            'verificado' => $valor,
        ]);
    }

    public function visualizarDocumentoEstudio(Request $request, $id, $estudioId)
    {
        $docente = Docente::with('persona')->findOrFail($id);
        $persona = $docente->persona;

        $estudio = Estudio::where('id', $estudioId)
            ->where('persona_id', $persona->id)
            ->firstOrFail();

        $tipo = $request->query('tipo', 'documento_academico');

        if (!in_array($tipo, ['documento_academico', 'documento_provision_nacional'])) {
            abort(400, 'Tipo inválido.');
        }

        $ruta = $estudio->$tipo;

        if (!$ruta || !Storage::disk('public')->exists($ruta)) {
            abort(404, 'Archivo no encontrado.');
        }

        $content = Storage::disk('public')->get($ruta);
        $ext     = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
        $mimes   = ['pdf' => 'application/pdf', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg'];
        $mime    = $mimes[$ext] ?? 'application/octet-stream';

        return response($content, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . basename($ruta) . '"');
    }

    public function setPrincipalEstudio($id, $estudioId)
    {
        $docente = Docente::with('persona')->findOrFail($id);
        $persona = $docente->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        Estudio::where('persona_id', $persona->id)->update(['principal' => false]);

        $estudio = Estudio::where('id', $estudioId)
            ->where('persona_id', $persona->id)
            ->firstOrFail();

        $estudio->update(['principal' => true]);

        return response()->json(['success' => true, 'message' => 'Estudio marcado como principal.']);
    }

    public function eliminarEstudio($id, $estudioId)
    {
        $docente = Docente::with('persona')->findOrFail($id);
        $persona = $docente->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Persona no encontrada.'], 404);
        }

        $estudio = Estudio::where('id', $estudioId)
            ->where('persona_id', $persona->id)
            ->firstOrFail();

        $estudio->delete();

        return response()->json(['success' => true, 'message' => 'Estudio eliminado correctamente.']);
    }

    public function subirDocumento(Request $request, $id)
    {
        $request->validate([
            'tipo_documento' => 'required|in:fotografia_carnet,fotografia_certificado_nacimiento',
            'archivo'        => 'required|file|mimes:pdf,png,jpg,jpeg|max:2048',
        ]);

        $docente = Docente::with('persona')->findOrFail($id);
        $persona = $docente->persona;

        $tipo   = $request->tipo_documento;
        $prefix = $tipo === 'fotografia_carnet' ? 'carnet' : 'certificado_nacimiento';
        $ext    = $request->file('archivo')->getClientOriginalExtension();
        $nombre = $prefix . '_' . $persona->id . '_' . time() . '.' . $ext;

        $old = $persona->$tipo;
        if ($old && Storage::disk('public')->exists($old)) {
            Storage::disk('public')->delete($old);
        }

        $path = $request->file('archivo')->storeAs('documentos', $nombre, 'public');
        $persona->update([$tipo => $path]);

        return response()->json([
            'success' => true,
            'mensaje' => 'Documento subido correctamente.',
        ]);
    }

    public function verificarDocumento(Request $request, $id)
    {
        $request->validate([
            'tipo_documento' => 'required|in:fotografia_carnet,fotografia_certificado_nacimiento',
            'accion'         => 'required|in:verificar,quitar',
        ]);

        $docente = Docente::with('persona')->findOrFail($id);
        $persona = $docente->persona;

        $verificadoField = $request->tipo_documento === 'fotografia_carnet' ? 'carnet_verificado' : 'certificado_nacimiento_verificado';
        $valor = $request->accion === 'verificar';
        $persona->update([$verificadoField => $valor]);

        return response()->json([
            'success' => true,
            'mensaje' => $valor ? 'Documento verificado correctamente.' : 'Verificación removida.',
        ]);
    }

    public function visualizarDocumento(Request $request, $id)
    {
        $docente = Docente::with('persona')->findOrFail($id);
        $persona = $docente->persona;

        $tipo = $request->query('tipo', 'fotografia_carnet');

        if (!in_array($tipo, ['fotografia_carnet', 'fotografia_certificado_nacimiento'])) {
            abort(400, 'Tipo inválido.');
        }

        $ruta = $persona->$tipo;

        if (!$ruta || !Storage::disk('public')->exists($ruta)) {
            abort(404, 'Archivo no encontrado.');
        }

        $content = Storage::disk('public')->get($ruta);
        $ext     = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
        $mimes   = ['pdf' => 'application/pdf', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg'];
        $mime    = $mimes[$ext] ?? 'application/octet-stream';

        return response($content, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . basename($ruta) . '"');
    }

    public function eliminar($id)
    {
        $docente = Docente::find($id);
        if (!$docente) {
            return response()->json(['success' => false, 'message' => 'Docente no encontrado.'], 404);
        }

        $docente->delete();

        return response()->json(['success' => true, 'message' => 'Docente eliminado correctamente.']);
    }

    public function resetPasswordMoodle(Request $request, $id)
    {
        $docente = Docente::with(['persona.usuario'])->findOrFail($id);
        $persona = $docente->persona;

        if (!$persona || !$persona->usuario) {
            return response()->json(['success' => false, 'message' => 'El docente no tiene cuenta de usuario.'], 404);
        }

        $username = $persona->usuario->username;
        $password = $this->generarPasswordSistema($persona->carnet);
        $moodle   = app(MoodleService::class);

        $existingMoodleUser = $moodle->getUserByField('username', $username);
        $moodleUserId       = $existingMoodleUser ? (int) $existingMoodleUser['id'] : null;

        if (!$moodleUserId) {
            return response()->json(['success' => false, 'message' => 'No se encontró cuenta de Moodle para este docente.'], 404);
        }

        if (!$moodle->updateUserPassword($moodleUserId, $password)) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar la contraseña en Moodle.'], 500);
        }

        $persona->usuario->update(['moodle_password' => $password]);

        return response()->json(['success' => true, 'password' => $password]);
    }
}