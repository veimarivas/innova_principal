<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('admin.permisos.index');
    }

    public function listar()
    {
        $permisos = Permission::withCount('roles')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($p) {
                $partes = explode('.', $p->name, 2);
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'modulo' => $partes[0] ?? '',
                    'accion' => $partes[1] ?? '',
                    'roles_count' => $p->roles_count,
                ];
            });

        return response()->json(['data' => $permisos]);
    }

    public function verificarNombre(Request $request)
    {
        $name = strtolower(trim($request->name));
        $q = Permission::where('name', $name)->where('guard_name', 'web');
        if ($request->filled('id')) {
            $q->where('id', '!=', $request->id);
        }
        return response()->json(['existe' => $q->exists()]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:120|regex:/^[a-z0-9\-\_\.]+$/|unique:permissions,name',
        ], [
            'name.required' => 'El nombre del permiso es obligatorio.',
            'name.unique' => 'Este permiso ya existe.',
            'name.regex' => 'Solo letras minúsculas, números, puntos, guiones y guion bajo.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $permission = Permission::create([
            'name' => strtolower(trim($request->name)),
            'guard_name' => 'web',
        ]);

        return response()->json(['success' => true, 'message' => 'Permiso registrado correctamente.', 'data' => $permission]);
    }

    public function actualizar(Request $request, $id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json(['success' => false, 'message' => 'Permiso no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:120|regex:/^[a-z0-9\-\_\.]+$/|unique:permissions,name,' . $id,
        ], [
            'name.required' => 'El nombre del permiso es obligatorio.',
            'name.unique' => 'Este permiso ya existe.',
            'name.regex' => 'Solo letras minúsculas, números, puntos, guiones y guion bajo.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $permission->update(['name' => strtolower(trim($request->name))]);

        return response()->json(['success' => true, 'message' => 'Permiso actualizado correctamente.', 'data' => $permission]);
    }

    public function eliminar($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json(['success' => false, 'message' => 'Permiso no encontrado.'], 404);
        }
        $permission->delete();
        return response()->json(['success' => true, 'message' => 'Permiso eliminado correctamente.']);
    }
}
