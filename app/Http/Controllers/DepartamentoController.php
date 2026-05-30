<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Ciudade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartamentoController extends Controller
{
    // Vista principal de departamentos (admin)
    public function indexAdmin()
    {
        return view('admin.departamentos.index');
    }

    // Listar departamentos con sus ciudades
    public function listar()
    {
        $departamentos = Departamento::with('ciudades')->orderBy('nombre', 'desc')->get();
        return response()->json(['data' => $departamentos]);
    }

    // Crear departamento
    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:departamentos,nombre'
        ], [
            'nombre.required' => 'El nombre del departamento es obligatorio.',
            'nombre.unique' => 'Este departamento ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $departamento = Departamento::create(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Departamento registrado correctamente.', 'data' => $departamento]);
    }

    // Actualizar departamento
    public function actualizar(Request $request, $id)
    {
        $departamento = Departamento::find($id);
        if (!$departamento) {
            return response()->json(['success' => false, 'message' => 'Departamento no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:departamentos,nombre,' . $id
        ], [
            'nombre.required' => 'El nombre del departamento es obligatorio.',
            'nombre.unique' => 'Este departamento ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $departamento->update(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Departamento actualizado correctamente.', 'data' => $departamento]);
    }

    // Eliminar departamento (si no tiene ciudades asociadas)
    public function eliminar($id)
    {
        $departamento = Departamento::find($id);
        if (!$departamento) {
            return response()->json(['success' => false, 'message' => 'Departamento no encontrado.'], 404);
        }

        if ($departamento->ciudades()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'No se puede eliminar el departamento porque tiene ciudades asociadas.'], 400);
        }

        $departamento->delete();
        return response()->json(['success' => true, 'message' => 'Departamento eliminado correctamente.']);
    }

    // Agregar ciudad a un departamento
    public function agregarCiudad(Request $request, $id)
    {
        $departamento = Departamento::find($id);
        if (!$departamento) {
            return response()->json(['success' => false, 'message' => 'Departamento no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:ciudades,nombre,NULL,id,departamento_id,' . $id
        ], [
            'nombre.required' => 'El nombre de la ciudad es obligatorio.',
            'nombre.unique' => 'Esta ciudad ya existe en este departamento.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $ciudad = $departamento->ciudades()->create(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Ciudad agregada correctamente.', 'data' => $ciudad]);
    }

    // Actualizar ciudad
    public function actualizarCiudad(Request $request, $id, $ciudadId)
    {
        $departamento = Departamento::find($id);
        if (!$departamento) {
            return response()->json(['success' => false, 'message' => 'Departamento no encontrado.'], 404);
        }

        $ciudad = $departamento->ciudades()->find($ciudadId);
        if (!$ciudad) {
            return response()->json(['success' => false, 'message' => 'Ciudad no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:ciudades,nombre,' . $ciudadId . ',id,departamento_id,' . $id
        ], [
            'nombre.required' => 'El nombre de la ciudad es obligatorio.',
            'nombre.unique' => 'Esta ciudad ya existe en este departamento.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $ciudad->update(['nombre' => strtoupper($request->nombre)]);
        return response()->json(['success' => true, 'message' => 'Ciudad actualizada correctamente.', 'data' => $ciudad]);
    }

    // Eliminar ciudad
    public function eliminarCiudad($id, $ciudadId)
    {
        $departamento = Departamento::find($id);
        if (!$departamento) {
            return response()->json(['success' => false, 'message' => 'Departamento no encontrado.'], 404);
        }

        $ciudad = $departamento->ciudades()->find($ciudadId);
        if (!$ciudad) {
            return response()->json(['success' => false, 'message' => 'Ciudad no encontrada.'], 404);
        }

        $ciudad->delete();
        return response()->json(['success' => true, 'message' => 'Ciudad eliminada correctamente.']);
    }

    // Listar ciudades por departamento
    public function listarCiudades($id)
    {
        $departamento = Departamento::find($id);
        if (!$departamento) {
            return response()->json(['success' => false, 'message' => 'Departamento no encontrado.'], 404);
        }

        $ciudades = $departamento->ciudades()->orderBy('nombre')->get();
        return response()->json($ciudades);
    }
}
