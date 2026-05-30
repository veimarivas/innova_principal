<?php

namespace App\Http\Controllers;

use App\Models\Convenio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ConvenioController extends Controller
{
    public function index()
    {
        return view('admin.convenios.index');
    }

    public function listar()
    {
        $convenios = Convenio::orderBy('nombre', 'asc')->get();
        return response()->json(['data' => $convenios]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        if ($request->has('id')) {
            $existe = Convenio::where('nombre', $nombre)->where('id', '!=', $request->id)->exists();
        } else {
            $existe = Convenio::where('nombre', $nombre)->exists();
        }
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:convenios,nombre',
            'sigla' => 'nullable|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('convenios', 'public');
        }

        $convenio = Convenio::create([
            'nombre' => strtoupper($request->nombre),
            'sigla' => $request->sigla ? strtoupper($request->sigla) : null,
            'imagen' => $imagenPath
        ]);
        return response()->json(['success' => true, 'message' => 'Convenio registrado correctamente.', 'data' => $convenio]);
    }

    public function actualizar(Request $request, $id)
    {
        $convenio = Convenio::find($id);
        if (!$convenio) {
            return response()->json(['success' => false, 'message' => 'Convenio no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:convenios,nombre,' . $id,
            'sigla' => 'nullable|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $imagenPath = $convenio->imagen;
        if ($request->hasFile('imagen')) {
            if ($convenio->imagen && Storage::disk('public')->exists($convenio->imagen)) {
                Storage::disk('public')->delete($convenio->imagen);
            }
            $imagenPath = $request->file('imagen')->store('convenios', 'public');
        }

        $convenio->update([
            'nombre' => strtoupper($request->nombre),
            'sigla' => $request->sigla ? strtoupper($request->sigla) : null,
            'imagen' => $imagenPath
        ]);
        return response()->json(['success' => true, 'message' => 'Convenio actualizado correctamente.', 'data' => $convenio]);
    }

    public function eliminar($id)
    {
        $convenio = Convenio::find($id);
        if (!$convenio) {
            return response()->json(['success' => false, 'message' => 'Convenio no encontrado.'], 404);
        }
        if ($convenio->imagen && Storage::disk('public')->exists($convenio->imagen)) {
            Storage::disk('public')->delete($convenio->imagen);
        }
        $convenio->delete();
        return response()->json(['success' => true, 'message' => 'Convenio eliminado correctamente.']);
    }
}
