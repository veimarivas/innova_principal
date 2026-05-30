<?php

namespace App\Http\Controllers;

use App\Models\Universidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UniversidadeController extends Controller
{
    public function index()
    {
        return view('admin.universidades.index');
    }

    public function listar()
    {
        $universidades = Universidade::orderBy('nombre', 'asc')->get();
        return response()->json(['data' => $universidades]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        if ($request->has('id')) {
            $existe = Universidade::where('nombre', $nombre)
                ->where('id', '!=', $request->id)
                ->exists();
        } else {
            $existe = Universidade::where('nombre', $nombre)->exists();
        }
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:150|unique:universidades,nombre',
            'sigla'  => 'nullable|string|max:20'
        ], [
            'nombre.required' => 'El nombre de la universidad es obligatorio.',
            'nombre.unique' => 'Esta universidad ya existe.',
            'nombre.max' => 'El nombre no puede tener mas de 150 caracteres.',
            'sigla.max' => 'La sigla no puede tener mas de 20 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $universidade = Universidade::create([
            'nombre' => strtoupper($request->nombre),
            'sigla'  => $request->sigla ? strtoupper($request->sigla) : null
        ]);
        return response()->json(['success' => true, 'message' => 'Universidad registrada correctamente.', 'data' => $universidade]);
    }

    public function actualizar(Request $request, $id)
    {
        $universidade = Universidade::find($id);
        if (!$universidade) {
            return response()->json(['success' => false, 'message' => 'Universidad no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:150|unique:universidades,nombre,' . $id,
            'sigla'  => 'nullable|string|max:20'
        ], [
            'nombre.required' => 'El nombre de la universidad es obligatorio.',
            'nombre.unique' => 'Esta universidad ya existe.',
            'nombre.max' => 'El nombre no puede tener mas de 150 caracteres.',
            'sigla.max' => 'La sigla no puede tener mas de 20 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $universidade->update([
            'nombre' => strtoupper($request->nombre),
            'sigla'  => $request->sigla ? strtoupper($request->sigla) : null
        ]);
        return response()->json(['success' => true, 'message' => 'Universidad actualizada correctamente.', 'data' => $universidade]);
    }

    public function eliminar($id)
    {
        $universidade = Universidade::find($id);
        if (!$universidade) {
            return response()->json(['success' => false, 'message' => 'Universidad no encontrada.'], 404);
        }
        $universidade->delete();
        return response()->json(['success' => true, 'message' => 'Universidad eliminada correctamente.']);
    }
}
