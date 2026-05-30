<?php

namespace App\Http\Controllers;

use App\Models\PlanesPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanesPagoController extends Controller
{
    public function index()
    {
        return view('admin.planes-pagos.index');
    }

    public function listar()
    {
        $planes = PlanesPago::orderBy('nombre', 'asc')->get();
        return response()->json(['data' => $planes]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        if ($request->has('id')) {
            $existe = PlanesPago::where('nombre', $nombre)
                ->where('id', '!=', $request->id)
                ->exists();
        } else {
            $existe = PlanesPago::where('nombre', $nombre)->exists();
        }
        return response()->json(['existe' => $existe]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:planes_pagos,nombre'
        ], [
            'nombre.required' => 'El nombre del plan de pago es obligatorio.',
            'nombre.unique' => 'Este plan de pago ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $plan = PlanesPago::create([
            'nombre' => strtoupper($request->nombre),
            'habilitado' => $request->boolean('habilitado', true),
            'principal' => $request->boolean('principal', false),
            'es_promocion' => $request->boolean('es_promocion', false),
            'fecha_inicio_promocion' => $request->fecha_inicio_promocion ?? null,
            'fecha_fin_promocion' => $request->fecha_fin_promocion ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Plan de pago registrado correctamente.', 'data' => $plan]);
    }

    public function actualizar(Request $request, $id)
    {
        $plan = PlanesPago::find($id);
        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Plan de pago no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:planes_pagos,nombre,' . $id
        ], [
            'nombre.required' => 'El nombre del plan de pago es obligatorio.',
            'nombre.unique' => 'Este plan de pago ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $plan->update([
            'nombre' => strtoupper($request->nombre),
            'habilitado' => $request->boolean('habilitado', true),
            'principal' => $request->boolean('principal', false),
            'es_promocion' => $request->boolean('es_promocion', false),
            'fecha_inicio_promocion' => $request->fecha_inicio_promocion ?? null,
            'fecha_fin_promocion' => $request->fecha_fin_promocion ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Plan de pago actualizado correctamente.', 'data' => $plan]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $plan = PlanesPago::find($id);
        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Plan de pago no encontrado.'], 404);
        }

        $plan->update(['habilitado' => $request->boolean('habilitado')]);
        return response()->json(['success' => true, 'message' => 'Estado actualizado correctamente.']);
    }

    public function eliminar($id)
    {
        $plan = PlanesPago::find($id);
        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Plan de pago no encontrado.'], 404);
        }
        $plan->delete();
        return response()->json(['success' => true, 'message' => 'Plan de pago eliminado correctamente.']);
    }
}
