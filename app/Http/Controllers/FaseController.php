<?php

namespace App\Http\Controllers;

use App\Models\Fase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaseController extends Controller
{
    public function index()
    {
        return view('admin.fases.index');
    }

    public function listar()
    {
        $fases = Fase::orderBy('n_fase', 'asc')->get();
        return response()->json(['data' => $fases]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        if ($request->has('id')) {
            $existe = Fase::where('nombre', $nombre)
                ->where('id', '!=', $request->id)
                ->exists();
        } else {
            $existe = Fase::where('nombre', $nombre)->exists();
        }
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'n_fase' => 'required|integer|unique:fases,n_fase',
            'nombre' => 'required|string|max:100|unique:fases,nombre'
        ], [
            'n_fase.required' => 'El número de fase es obligatorio.',
            'n_fase.integer' => 'El número de fase debe ser un valor numérico.',
            'n_fase.unique' => 'Este número de fase ya existe.',
            'nombre.required' => 'El nombre de la fase es obligatorio.',
            'nombre.unique' => 'Esta fase ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $fase = Fase::create([
            'n_fase' => $request->n_fase,
            'nombre' => strtoupper($request->nombre),
            'color' => $request->color
        ]);
        return response()->json(['success' => true, 'message' => 'Fase registrada correctamente.', 'data' => $fase]);
    }

    public function actualizar(Request $request, $id)
    {
        $fase = Fase::find($id);
        if (!$fase) {
            return response()->json(['success' => false, 'message' => 'Fase no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'n_fase' => 'required|integer|unique:fases,n_fase,' . $id,
            'nombre' => 'required|string|max:100|unique:fases,nombre,' . $id
        ], [
            'n_fase.required' => 'El número de fase es obligatorio.',
            'n_fase.integer' => 'El número de fase debe ser un valor numérico.',
            'n_fase.unique' => 'Este número de fase ya existe.',
            'nombre.required' => 'El nombre de la fase es obligatorio.',
            'nombre.unique' => 'Esta fase ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $fase->update([
            'n_fase' => $request->n_fase,
            'nombre' => strtoupper($request->nombre),
            'color' => $request->color
        ]);
        return response()->json(['success' => true, 'message' => 'Fase actualizada correctamente.', 'data' => $fase]);
    }

    public function eliminar($id)
    {
        $fase = Fase::find($id);
        if (!$fase) {
            return response()->json(['success' => false, 'message' => 'Fase no encontrada.'], 404);
        }
        $fase->delete();
        return response()->json(['success' => true, 'message' => 'Fase eliminada correctamente.']);
    }
}
