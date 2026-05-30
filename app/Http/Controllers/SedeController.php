<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\Sucursale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SedeController extends Controller
{
    public function indexAdmin()
    {
        return view('admin.sedes.index');
    }

    public function listar()
    {
        $sedes = Sede::with('sucursales')->orderBy('nombre', 'desc')->get();
        return response()->json(['data' => $sedes]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:sedes,nombre'
        ], [
            'nombre.required' => 'El nombre de la sede es obligatorio.',
            'nombre.unique' => 'Esta sede ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $sede = Sede::create(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Sede registrada correctamente.', 'data' => $sede]);
    }

    public function actualizar(Request $request, $id)
    {
        $sede = Sede::find($id);
        if (!$sede) {
            return response()->json(['success' => false, 'message' => 'Sede no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:sedes,nombre,' . $id
        ], [
            'nombre.required' => 'El nombre de la sede es obligatorio.',
            'nombre.unique' => 'Esta sede ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $sede->update(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Sede actualizada correctamente.', 'data' => $sede]);
    }

    public function eliminar($id)
    {
        $sede = Sede::find($id);
        if (!$sede) {
            return response()->json(['success' => false, 'message' => 'Sede no encontrada.'], 404);
        }

        if ($sede->sucursales()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'No se puede eliminar la sede porque tiene sucursales asociadas.'], 400);
        }

        $sede->delete();
        return response()->json(['success' => true, 'message' => 'Sede eliminada correctamente.']);
    }

    public function agregarSucursal(Request $request, $id)
    {
        $sede = Sede::find($id);
        if (!$sede) {
            return response()->json(['success' => false, 'message' => 'Sede no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'color' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:200',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric'
        ], [
            'nombre.required' => 'El nombre de la sucursal es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'color.required' => 'El color es obligatorio.',
            'color.max' => 'El color no puede tener más de 20 caracteres.',
            'direccion.max' => 'La dirección no puede tener más de 200 caracteres.',
            'latitud.numeric' => 'La latitud debe ser un valor numérico válido.',
            'longitud.numeric' => 'La longitud debe ser un valor numérico válido.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $sucursal = $sede->sucursales()->create([
            'nombre' => strtoupper($request->nombre),
            'color' => $request->color,
            'direccion' => $request->direccion ? strtoupper($request->direccion) : null,
            'latitud' => $request->latitud ?: null,
            'longitud' => $request->longitud ?: null
        ]);
        return response()->json(['success' => true, 'message' => 'Sucursal agregada correctamente.', 'data' => $sucursal]);
    }

    public function actualizarSucursal(Request $request, $id, $sucursalId)
    {
        $sede = Sede::find($id);
        if (!$sede) {
            return response()->json(['success' => false, 'message' => 'Sede no encontrada.'], 404);
        }

        $sucursal = $sede->sucursales()->find($sucursalId);
        if (!$sucursal) {
            return response()->json(['success' => false, 'message' => 'Sucursal no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'color' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:200',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric'
        ], [
            'nombre.required' => 'El nombre de la sucursal es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'color.required' => 'El color es obligatorio.',
            'color.max' => 'El color no puede tener más de 20 caracteres.',
            'direccion.max' => 'La dirección no puede tener más de 200 caracteres.',
            'latitud.numeric' => 'La latitud debe ser un valor numérico válido.',
            'longitud.numeric' => 'La longitud debe ser un valor numérico válido.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $sucursal->update([
            'nombre' => strtoupper($request->nombre),
            'color' => $request->color,
            'direccion' => $request->direccion ? strtoupper($request->direccion) : null,
            'latitud' => $request->latitud ?: null,
            'longitud' => $request->longitud ?: null
        ]);
        return response()->json(['success' => true, 'message' => 'Sucursal actualizada correctamente.', 'data' => $sucursal]);
    }

    public function eliminarSucursal($id, $sucursalId)
    {
        $sede = Sede::find($id);
        if (!$sede) {
            return response()->json(['success' => false, 'message' => 'Sede no encontrada.'], 404);
        }

        $sucursal = $sede->sucursales()->find($sucursalId);
        if (!$sucursal) {
            return response()->json(['success' => false, 'message' => 'Sucursal no encontrada.'], 404);
        }

        $sucursal->delete();
        return response()->json(['success' => true, 'message' => 'Sucursal eliminada correctamente.']);
    }
}
