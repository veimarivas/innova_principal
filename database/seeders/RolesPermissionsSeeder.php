<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $modulos = [
            'usuarios', 'roles', 'permisos', 'personas', 'estudiantes', 'docentes',
            'trabajadores', 'cargos', 'departamentos', 'areas', 'tipos', 'convenios',
            'posgrados', 'ofertas', 'modulos', 'programas', 'grados-academicos',
            'profesiones', 'universidades', 'sedes', 'fases', 'modalidades',
            'conceptos', 'planes-pago', 'cronograma', 'cuentas-videollamada',
            'contabilidad', 'comprobantes', 'moodle', 'actividades', 'academico',
        ];
        $acciones = ['ver', 'crear', 'editar', 'eliminar'];

        $permisos = [];
        foreach ($modulos as $modulo) {
            foreach ($acciones as $accion) {
                $permisos[] = "{$modulo}.{$accion}";
            }
        }
        $permisos[] = 'dashboard.ver';

        foreach ($permisos as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $docente = Role::firstOrCreate(['name' => 'docente', 'guard_name' => 'web']);
        $estudiante = Role::firstOrCreate(['name' => 'estudiante', 'guard_name' => 'web']);
        $marketing = Role::firstOrCreate(['name' => 'marketing', 'guard_name' => 'web']);
        $contabilidad = Role::firstOrCreate(['name' => 'contabilidad', 'guard_name' => 'web']);

        $admin->syncPermissions(Permission::all());

        $docente->syncPermissions([
            'dashboard.ver', 'modulos.ver', 'actividades.ver', 'actividades.crear',
            'actividades.editar', 'academico.ver', 'academico.editar',
        ]);

        $estudiante->syncPermissions([
            'dashboard.ver', 'modulos.ver', 'actividades.ver',
        ]);

        $marketing->syncPermissions([
            'dashboard.ver', 'personas.ver', 'personas.crear', 'personas.editar',
            'estudiantes.ver', 'estudiantes.crear', 'estudiantes.editar',
            'ofertas.ver', 'planes-pago.ver', 'comprobantes.ver', 'comprobantes.crear',
        ]);

        $contabilidad->syncPermissions([
            'dashboard.ver', 'contabilidad.ver', 'contabilidad.crear', 'contabilidad.editar',
            'comprobantes.ver', 'comprobantes.editar', 'conceptos.ver', 'planes-pago.ver',
        ]);

        User::query()->where('role', 'admin')->get()->each(function ($u) use ($admin) {
            if (!$u->hasRole($admin)) {
                $u->assignRole($admin);
            }
        });
    }
}
