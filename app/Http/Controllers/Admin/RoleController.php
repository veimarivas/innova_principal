<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index');
    }

    public function listar()
    {
        $roles = Role::withCount(['permissions', 'users'])
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'name' => $r->name,
                    'guard_name' => $r->guard_name,
                    'permissions_count' => $r->permissions_count,
                    'users_count' => $r->users_count,
                ];
            });

        return response()->json(['data' => $roles]);
    }

    public function verificarNombre(Request $request)
    {
        $name = strtolower(trim($request->name));
        $q = Role::where('name', $name)->where('guard_name', 'web');
        if ($request->filled('id')) {
            $q->where('id', '!=', $request->id);
        }
        return response()->json(['existe' => $q->exists()]);
    }

    public function permisos($id)
    {
        $role = Role::with('permissions')->find($id);
        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Rol no encontrado.'], 404);
        }

        $todos = Permission::orderBy('name')->get(['id', 'name']);
        $asignados = $role->permissions->pluck('id')->toArray();

        $grupos = [];
        foreach ($todos as $p) {
            $partes = explode('.', $p->name, 2);
            $modulo = $partes[0];
            $accion = $partes[1] ?? $p->name;
            $grupos[$modulo][] = [
                'id' => $p->id,
                'name' => $p->name,
                'accion' => $accion,
                'asignado' => in_array($p->id, $asignados),
            ];
        }

        return response()->json([
            'success' => true,
            'role' => ['id' => $role->id, 'name' => $role->name],
            'grupos' => $grupos,
        ]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:roles,name',
        ], [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique' => 'Este rol ya existe.',
            'name.max' => 'El nombre no puede tener más de 100 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $role = Role::create([
            'name' => strtolower(trim($request->name)),
            'guard_name' => 'web',
        ]);

        return response()->json(['success' => true, 'message' => 'Rol registrado correctamente.', 'data' => $role]);
    }

    public function actualizar(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Rol no encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:roles,name,' . $id,
        ], [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique' => 'Este rol ya existe.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $role->update(['name' => strtolower(trim($request->name))]);

        return response()->json(['success' => true, 'message' => 'Rol actualizado correctamente.', 'data' => $role]);
    }

    public function sincronizarPermisos(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Rol no encontrado.'], 404);
        }

        $permisos = $request->input('permisos', []);
        $permisos = is_array($permisos) ? $permisos : [];

        $found = Permission::whereIn('id', $permisos)->get();
        $role->syncPermissions($found);

        return response()->json([
            'success' => true,
            'message' => 'Permisos actualizados correctamente.',
            'count' => $found->count(),
        ]);
    }

    public function eliminar($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Rol no encontrado.'], 404);
        }

        if (in_array($role->name, ['admin'])) {
            return response()->json(['success' => false, 'message' => 'No se puede eliminar el rol admin.'], 400);
        }

        $role->delete();
        return response()->json(['success' => true, 'message' => 'Rol eliminado correctamente.']);
    }
}
