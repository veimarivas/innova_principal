<?php

namespace App\Http\Controllers;

use App\Models\Profesione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfesionController extends Controller
{
    public function index()
    {
        return view('admin.profesiones.index');
    }

    public function listar()
    {
        $profesiones = Profesione::orderBy('nombre', 'asc')->get();
        return response()->json(['data' => $profesiones]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        if ($request->has('id')) {
            $existe = Profesione::where('nombre', $nombre)
                ->where('id', '!=', $request->id)
                ->exists();
        } else {
            $existe = Profesione::where('nombre', $nombre)->exists();
        }
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:profesiones,nombre'
        ], [
            'nombre.required' => 'El nombre de la profesión es obligatorio.',
            'nombre.unique' => 'Esta profesión ya existe.',
            'nombre.max' => 'El nombre no puede tener mas de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $profesion = Profesione::create(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Profesión registrada correctamente.', 'data' => $profesion]);
    }

    public function actualizar(Request $request, $id)
    {
        $profesion = Profesione::find($id);
        if (!$profesion) {
            return response()->json(['success' => false, 'message' => 'Profesión no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:profesiones,nombre,' . $id
        ], [
            'nombre.required' => 'El nombre de la profesión es obligatorio.',
            'nombre.unique' => 'Esta profesión ya existe.',
            'nombre.max' => 'El nombre no puede tener mas de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $profesion->update(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Profesión actualizada correctamente.', 'data' => $profesion]);
    }

    public function eliminar($id)
    {
        $profesion = Profesione::find($id);
        if (!$profesion) {
            return response()->json(['success' => false, 'message' => 'Profesión no encontrada.'], 404);
        }
        $profesion->delete();
        return response()->json(['success' => true, 'message' => 'Profesión eliminada correctamente.']);
    }
}
