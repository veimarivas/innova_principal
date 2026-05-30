<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
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
        return response()->json(['data' => $users]);
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
}
