<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    // ✅ Método que faltaba
    public function index()
    {
        return view('admin.areas.index');
    }

    public function listar()
    {
        $areas = Area::orderBy('nombre', 'asc')->get();
        return response()->json(['data' => $areas]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        if ($request->has('id')) {
            $existe = Area::where('nombre', $nombre)
                ->where('id', '!=', $request->id)
                ->exists();
        } else {
            $existe = Area::where('nombre', $nombre)->exists();
        }
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:areas,nombre'
        ], [
            'nombre.required' => 'El nombre del área es obligatorio.',
            'nombre.unique' => 'Esta área ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $area = Area::create(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Área registrada correctamente.', 'data' => $area]);
    }

    public function actualizar(Request $request, $id)
    {
        $area = Area::find($id);
        if (!$area) {
            return response()->json(['success' => false, 'message' => 'Área no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:areas,nombre,' . $id
        ], [
            'nombre.required' => 'El nombre del área es obligatorio.',
            'nombre.unique' => 'Esta área ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $area->update(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Área actualizada correctamente.', 'data' => $area]);
    }

    public function eliminar($id)
    {
        $area = Area::find($id);
        if (!$area) {
            return response()->json(['success' => false, 'message' => 'Área no encontrada.'], 404);
        }
        $area->delete();
        return response()->json(['success' => true, 'message' => 'Área eliminada correctamente.']);
    }
}
