<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function listar()
    {
        $users = User::with('persona')->orderBy('name', 'asc')->get();

        $data = $users->map(function ($u) {
            $arr            = $u->toArray();
            $estadoLower    = strtolower($u->estado ?? 'activo');
            $sistemaActiva  = $estadoLower === 'activo';
            $arr['estado']               = $sistemaActiva ? 'Activo' : 'Inactivo';
            $arr['tiene_cuenta_sistema'] = $sistemaActiva;
            $arr['tiene_cuenta_moodle']  = $sistemaActiva;
            $arr['usuario_username']     = $u->username ?: ($u->persona?->carnet ?? '');
            return $arr;
        });

        return response()->json(['data' => $data]);
    }

    public function buscarPorCarnet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carnet' => 'required|string'
        ], [
            'carnet.required' => 'El número de carnet es obligatorio.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $persona = Persona::where('carnet', $request->carnet)->first();

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'No se encontró ninguna persona con el carnet: ' . $request->carnet]);
        }

        if ($persona->usuario) {
            return response()->json(['success' => false, 'message' => 'Esta persona ya tiene una cuenta de usuario creada.']);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $persona->id,
                'carnet' => $persona->carnet,
                'nombres' => $persona->nombres,
                'apellido_paterno' => $persona->apellido_paterno,
                'apellido_materno' => $persona->apellido_materno,
                'correo' => $persona->correo
            ]
        ]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'persona_id' => 'required|exists:personas,id|unique:users,persona_id',
            'name' => 'required|string|max:150'
        ], [
            'persona_id.required' => 'La persona es obligatoria.',
            'persona_id.exists' => 'La persona seleccionada no existe.',
            'persona_id.unique' => 'Esta persona ya tiene una cuenta de usuario.',
            'name.required' => 'El nombre de usuario es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 150 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $persona = Persona::find($request->persona_id);

        $email = $persona->correo;
        if (!$email) {
            return response()->json(['success' => false, 'message' => 'La persona no tiene un correo registrado. No se puede crear la cuenta.'], 422);
        }

        $emailExists = User::where('email', $email)->exists();
        if ($emailExists) {
            return response()->json(['success' => false, 'message' => 'Ya existe una cuenta con el correo de esta persona.'], 422);
        }

        $user = User::create([
            'name' => strtoupper($request->name),
            'email' => $email,
            'password' => Hash::make($persona->carnet),
            'role' => 'admin',
            'estado' => 'activo',
            'persona_id' => $persona->id
        ]);

        return response()->json(['success' => true, 'message' => 'Cuenta de usuario creada correctamente.', 'data' => $user]);
    }

    public function eliminar($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
        }

        $user->delete();
        return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente.']);
    }

    /**
     * Reinicia la contraseña al carnet de la persona, tanto en el sistema como en Moodle.
     */
    public function reiniciarPassword($id)
    {
        $user = User::with('persona')->find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
        }

        $persona     = $user->persona;
        $newPassword = ($persona && $persona->carnet) ? $persona->carnet : 'innova123';

        $user->update([
            'password'        => Hash::make($newPassword),
            'moodle_password' => $newPassword,
        ]);

        $moodleOk = false;
        $usernameMoodle = $user->username ?: ($persona?->carnet ?? null);
        if ($usernameMoodle) {
            try {
                $moodle             = app(MoodleService::class);
                $existingMoodleUser = $moodle->getUserByField('username', $usernameMoodle);
                $moodleUserId       = $existingMoodleUser ? (int) $existingMoodleUser['id'] : null;
                if ($moodleUserId && $moodle->updateUserPassword($moodleUserId, $newPassword)) {
                    $moodleOk = true;
                }
            } catch (\Throwable $e) {
                $moodleOk = false;
            }
        }

        return response()->json([
            'success'   => true,
            'password'  => $newPassword,
            'moodle_ok' => $moodleOk,
            'message'   => $moodleOk
                ? 'Contraseña reiniciada en sistema y Moodle.'
                : 'Contraseña reiniciada en sistema. Moodle no pudo actualizarse.',
        ]);
    }

    /**
     * Activa o desactiva la cuenta. Sincroniza con Moodle (suspended).
     */
    public function toggleEstado($id)
    {
        $user = User::with('persona')->find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
        }

        $estaActivo  = strtolower($user->estado ?? 'activo') === 'activo';
        $nuevoEstado = $estaActivo ? 'Inactivo' : 'Activo';
        $suspender   = $estaActivo;

        $moodleOk       = null;
        $usernameMoodle = $user->username ?: ($user->persona?->carnet ?? null);
        if ($usernameMoodle) {
            try {
                $moodle             = app(MoodleService::class);
                $existingMoodleUser = $moodle->getUserByField('username', $usernameMoodle);
                $moodleUserId       = $existingMoodleUser ? (int) $existingMoodleUser['id'] : null;
                if ($moodleUserId) {
                    $moodleOk = $moodle->suspendUser($moodleUserId, $suspender);
                }
            } catch (\Throwable $e) {
                $moodleOk = false;
            }
        }

        $user->update(['estado' => $nuevoEstado]);

        $msg = $estaActivo ? 'Cuenta deshabilitada' : 'Cuenta habilitada';
        if ($moodleOk === true)  $msg .= ' (sistema + Moodle).';
        elseif ($moodleOk === false) $msg .= ' en sistema. Moodle no respondió.';
        else $msg .= '.';

        return response()->json([
            'success'   => true,
            'estado'    => $nuevoEstado,
            'moodle_ok' => $moodleOk,
            'message'   => $msg,
        ]);
    }
}
