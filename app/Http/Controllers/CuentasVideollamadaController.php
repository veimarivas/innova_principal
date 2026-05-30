<?php

namespace App\Http\Controllers;

use App\Models\CuentasVideollamada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CuentasVideollamadaController extends Controller
{
    public function index()
    {
        return view('admin.cuentas-videollamada.index');
    }

    public function ver($id)
    {
        $cuenta = CuentasVideollamada::with([
            'enlaces' => fn($q) => $q->orderBy('nombre'),
            'enlaces.horarios' => fn($q) => $q->orderBy('fecha', 'desc')->orderBy('hora_inicio', 'desc'),
            'enlaces.horarios.modulo',
        ])->findOrFail($id);

        return view('admin.cuentas-videollamada.ver', compact('cuenta'));
    }

    public function listar()
    {
        $cuentas = CuentasVideollamada::orderBy('nombre', 'asc')->get();
        return response()->json(['data' => $cuentas]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        if ($request->has('id')) {
            $existe = CuentasVideollamada::where('nombre', $nombre)
                ->where('id', '!=', $request->id)
                ->exists();
        } else {
            $existe = CuentasVideollamada::where('nombre', $nombre)->exists();
        }
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:200|unique:cuentas_videollamada,nombre',
            'plataforma' => 'required|string|max:100',
        ], [
            'nombre.required' => 'El nombre de la cuenta es obligatorio.',
            'nombre.unique' => 'Esta cuenta ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 200 caracteres.',
            'plataforma.required' => 'La plataforma es obligatoria.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $cuenta = CuentasVideollamada::create([
            'nombre' => strtoupper($request->nombre),
            'plataforma' => $request->plataforma,
            'activo' => $request->boolean('activo', true),
        ]);

        return response()->json(['success' => true, 'message' => 'Cuenta registrada correctamente.', 'data' => $cuenta]);
    }

    public function actualizar(Request $request, $id)
    {
        $cuenta = CuentasVideollamada::find($id);
        if (!$cuenta) {
            return response()->json(['success' => false, 'message' => 'Cuenta no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:200|unique:cuentas_videollamada,nombre,' . $id,
            'plataforma' => 'required|string|max:100',
        ], [
            'nombre.required' => 'El nombre de la cuenta es obligatorio.',
            'nombre.unique' => 'Esta cuenta ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 200 caracteres.',
            'plataforma.required' => 'La plataforma es obligatoria.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $cuenta->update([
            'nombre' => strtoupper($request->nombre),
            'plataforma' => $request->plataforma,
            'activo' => $request->boolean('activo', $cuenta->activo),
        ]);

        return response()->json(['success' => true, 'message' => 'Cuenta actualizada correctamente.', 'data' => $cuenta]);
    }

    public function eliminar($id)
    {
        $cuenta = CuentasVideollamada::find($id);
        if (!$cuenta) {
            return response()->json(['success' => false, 'message' => 'Cuenta no encontrada.'], 404);
        }

        if ($cuenta->enlaces()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la cuenta porque tiene enlaces asociados.'
            ], 400);
        }

        $cuenta->delete();
        return response()->json(['success' => true, 'message' => 'Cuenta eliminada correctamente.']);
    }
}
