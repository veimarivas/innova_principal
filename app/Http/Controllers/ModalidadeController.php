<?php

namespace App\Http\Controllers;

use App\Models\Modalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModalidadeController extends Controller
{
    public function index()
    {
        return view('admin.modalidades.index');
    }

    public function listar()
    {
        $modalidades = Modalidade::orderBy('nombre', 'asc')->get();
        return response()->json(['data' => $modalidades]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        if ($request->has('id')) {
            $existe = Modalidade::where('nombre', $nombre)
                ->where('id', '!=', $request->id)
                ->exists();
        } else {
            $existe = Modalidade::where('nombre', $nombre)->exists();
        }
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:modalidades,nombre'
        ], [
            'nombre.required' => 'El nombre de la modalidad es obligatorio.',
            'nombre.unique' => 'Esta modalidad ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $modalidad = Modalidade::create(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Modalidad registrada correctamente.', 'data' => $modalidad]);
    }

    public function actualizar(Request $request, $id)
    {
        $modalidad = Modalidade::find($id);
        if (!$modalidad) {
            return response()->json(['success' => false, 'message' => 'Modalidad no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:modalidades,nombre,' . $id
        ], [
            'nombre.required' => 'El nombre de la modalidad es obligatorio.',
            'nombre.unique' => 'Esta modalidad ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $modalidad->update(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Modalidad actualizada correctamente.', 'data' => $modalidad]);
    }

    public function eliminar($id)
    {
        $modalidad = Modalidade::find($id);
        if (!$modalidad) {
            return response()->json(['success' => false, 'message' => 'Modalidad no encontrada.'], 404);
        }
        $modalidad->delete();
        return response()->json(['success' => true, 'message' => 'Modalidad eliminada correctamente.']);
    }
}
