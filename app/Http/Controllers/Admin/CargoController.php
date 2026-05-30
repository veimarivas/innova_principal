<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CargoController extends Controller
{
    public function index()
    {
        return view('admin.cargos.index');
    }

    public function listar()
    {
        $cargos = Cargo::orderBy('nombre', 'asc')->get();
        return response()->json(['data' => $cargos]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        if ($request->has('id')) {
            $existe = Cargo::where('nombre', $nombre)
                ->where('id', '!=', $request->id)
                ->exists();
        } else {
            $existe = Cargo::where('nombre', $nombre)->exists();
        }
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:cargos,nombre'
        ], [
            'nombre.required' => 'El nombre del cargo es obligatorio.',
            'nombre.unique' => 'Este cargo ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $cargo = Cargo::create(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Cargo registrado correctamente.', 'data' => $cargo]);
    }

    public function actualizar(Request $request, $id)
    {
        $cargo = Cargo::find($id);
        if (!$cargo) {
            return response()->json(['success' => false, 'message' => 'Cargo no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:cargos,nombre,' . $id
        ], [
            'nombre.required' => 'El nombre del cargo es obligatorio.',
            'nombre.unique' => 'Este cargo ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $cargo->update(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Cargo actualizado correctamente.', 'data' => $cargo]);
    }

    public function eliminar($id)
    {
        $cargo = Cargo::find($id);
        if (!$cargo) {
            return response()->json(['success' => false, 'message' => 'Cargo no encontrado.'], 404);
        }
        $cargo->delete();
        return response()->json(['success' => true, 'message' => 'Cargo eliminado correctamente.']);
    }
}
