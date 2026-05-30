<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoController extends Controller
{
    public function index()
    {
        return view('admin.tipos.index');
    }

    public function listar()
    {
        $tipos = Tipo::orderBy('nombre', 'asc')->get();
        return response()->json(['data' => $tipos]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        if ($request->has('id')) {
            $existe = Tipo::where('nombre', $nombre)->where('id', '!=', $request->id)->exists();
        } else {
            $existe = Tipo::where('nombre', $nombre)->exists();
        }
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:tipos,nombre',
            'descripcion' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $tipo = Tipo::create([
            'nombre' => strtoupper($request->nombre),
            'descripcion' => $request->descripcion ? strtoupper($request->descripcion) : null
        ]);
        return response()->json(['success' => true, 'message' => 'Tipo registrado correctamente.', 'data' => $tipo]);
    }

    public function actualizar(Request $request, $id)
    {
        $tipo = Tipo::find($id);
        if (!$tipo) {
            return response()->json(['success' => false, 'message' => 'Tipo no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:tipos,nombre,' . $id,
            'descripcion' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $tipo->update([
            'nombre' => strtoupper($request->nombre),
            'descripcion' => $request->descripcion ? strtoupper($request->descripcion) : null
        ]);
        return response()->json(['success' => true, 'message' => 'Tipo actualizado correctamente.', 'data' => $tipo]);
    }

    public function eliminar($id)
    {
        $tipo = Tipo::find($id);
        if (!$tipo) {
            return response()->json(['success' => false, 'message' => 'Tipo no encontrado.'], 404);
        }
        $tipo->delete();
        return response()->json(['success' => true, 'message' => 'Tipo eliminado correctamente.']);
    }
}
