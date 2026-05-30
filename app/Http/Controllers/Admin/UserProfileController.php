<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\GradoAcademico;
use App\Models\Profesione;
use App\Models\Sucursale;
use App\Models\Universidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $persona = $user->persona;

        // Calcular si tiene cargos de marketing
        $tieneMarketing = false;
        if ($persona && $persona->trabajador) {
            $tieneMarketing = $persona->trabajador->trabajadores_cargos()
                ->whereIn('cargo_id', [2, 3, 6])
                ->where('estado', 'Vigente')
                ->exists(); // exists() es más eficiente que count() > 0
        }

        // Cargar datos para formularios (si los necesitas)
        $grados = GradoAcademico::all();
        $profesiones = Profesione::all();
        $universidades = Universidade::all();
        $cargos = Cargo::all();
        $sucursales = Sucursale::all();

        return view('admin.profile.index', compact(
            'persona',
            'grados',
            'profesiones',
            'universidades',
            'cargos',
            'sucursales',
            'tieneMarketing'  // <-- Agregado
        ));
    }
}
