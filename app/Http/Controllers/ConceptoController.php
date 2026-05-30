<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConceptoController extends Controller
{
    public function index()
    {
        return view('admin.conceptos.index');
    }

    public function listar()
    {
        $conceptos = Concepto::orderBy('nombre', 'asc')->get();
        return response()->json(['data' => $conceptos]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        if ($request->has('id')) {
            $existe = Concepto::where('nombre', $nombre)
                ->where('id', '!=', $request->id)
                ->exists();
        } else {
            $existe = Concepto::where('nombre', $nombre)->exists();
        }
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:conceptos,nombre'
        ], [
            'nombre.required' => 'El nombre del concepto es obligatorio.',
            'nombre.unique' => 'Este concepto ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $concepto = Concepto::create(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Concepto registrado correctamente.', 'data' => $concepto]);
    }

    public function actualizar(Request $request, $id)
    {
        $concepto = Concepto::find($id);
        if (!$concepto) {
            return response()->json(['success' => false, 'message' => 'Concepto no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:conceptos,nombre,' . $id
        ], [
            'nombre.required' => 'El nombre del concepto es obligatorio.',
            'nombre.unique' => 'Este concepto ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $concepto->update(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Concepto actualizado correctamente.', 'data' => $concepto]);
    }

    public function eliminar($id)
    {
        $concepto = Concepto::find($id);
        if (!$concepto) {
            return response()->json(['success' => false, 'message' => 'Concepto no encontrado.'], 404);
        }
        $concepto->delete();
        return response()->json(['success' => true, 'message' => 'Concepto eliminado correctamente.']);
    }
}
