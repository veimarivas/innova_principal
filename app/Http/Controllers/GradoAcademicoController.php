<?php

namespace App\Http\Controllers;

use App\Models\GradoAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GradoAcademicoController extends Controller
{
    public function index()
    {
        return view('admin.gradosacademicos.index');
    }

    public function listar()
    {
        $grados = GradoAcademico::orderBy('nombre', 'asc')->get();
        return response()->json(['data' => $grados]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        if ($request->has('id')) {
            $existe = GradoAcademico::where('nombre', $nombre)
                ->where('id', '!=', $request->id)
                ->exists();
        } else {
            $existe = GradoAcademico::where('nombre', $nombre)->exists();
        }
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:grados_academicos,nombre'
        ], [
            'nombre.required' => 'El nombre del grado académico es obligatorio.',
            'nombre.unique' => 'Este grado académico ya existe.',
            'nombre.max' => 'El nombre no puede tener mas de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $grado = GradoAcademico::create(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Grado académico registrado correctamente.', 'data' => $grado]);
    }

    public function actualizar(Request $request, $id)
    {
        $grado = GradoAcademico::find($id);
        if (!$grado) {
            return response()->json(['success' => false, 'message' => 'Grado académico no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:grados_academicos,nombre,' . $id
        ], [
            'nombre.required' => 'El nombre del grado académico es obligatorio.',
            'nombre.unique' => 'Este grado académico ya existe.',
            'nombre.max' => 'El nombre no puede tener mas de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $grado->update(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Grado académico actualizado correctamente.', 'data' => $grado]);
    }

    public function eliminar($id)
    {
        $grado = GradoAcademico::find($id);
        if (!$grado) {
            return response()->json(['success' => false, 'message' => 'Grado académico no encontrado.'], 404);
        }
        $grado->delete();
        return response()->json(['success' => true, 'message' => 'Grado académico eliminado correctamente.']);
    }
}
