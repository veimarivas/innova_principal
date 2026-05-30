<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\Sucursale;
use App\Models\OfertasAcademica;
use App\Models\Modulo;
use App\Models\Horario;
use Illuminate\Http\Request;

class CronogramaController extends Controller
{
    public function index()
    {
        $sedes = Sede::orderBy('nombre', 'asc')->get();
        return view('admin.cronogramas.index', compact('sedes'));
    }

    public function listarSucursales($sedeId)
    {
        $sucursales = Sucursale::where('sede_id', $sedeId)
            ->orderBy('nombre', 'asc')
            ->get(['id', 'nombre']);

        return response()->json(['data' => $sucursales]);
    }

    public function listarOfertasConHorarios($sucursalId)
    {
        $ofertas = OfertasAcademica::with(['posgrado', 'fase', 'modalidad'])
            ->where('sucursale_id', $sucursalId)
            ->orderBy('gestion', 'desc')
            ->orderBy('codigo', 'asc')
            ->get();

        $data = $ofertas->map(function ($oferta) {
            return [
                'id' => $oferta->id,
                'codigo' => $oferta->codigo,
                'color' => $oferta->color ?? '#6366f1',
                'gestion' => $oferta->gestion,
                'posgrado' => $oferta->posgrado ? [
                    'id' => $oferta->posgrado->id,
                    'nombre' => $oferta->posgrado->nombre,
                ] : null,
                'fase' => $oferta->fase ? [
                    'id' => $oferta->fase->id,
                    'nombre' => $oferta->fase->nombre,
                ] : null,
                'modalidad' => $oferta->modalidad ? [
                    'id' => $oferta->modalidad->id,
                    'nombre' => $oferta->modalidad->nombre,
                ] : null,
                'fecha_inicio_programa' => $oferta->fecha_inicio_programa?->format('Y-m-d'),
                'fecha_fin_programa' => $oferta->fecha_fin_programa?->format('Y-m-d'),
                'modulos_count' => $oferta->modulos->count(),
                'horarios_count' => $oferta->modulos->sum(fn($m) => $m->horarios->count()),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function listarHorarios(Request $request)
    {
        $mostrarColoresOferta = !$request->filled('oferta_id');

        $query = Horario::with([
            'modulo.docente.persona:id,nombres,apellido_paterno,apellido_materno',
            'modulo.oferta_academica.posgrado:id,nombre,objetivo',
            'modulo.oferta_academica.fase:id,nombre',
            'modulo.oferta_academica.modalidad:id,nombre',
            'trabajadorCargo.cargo',
        ])
        ->whereHas('modulo.oferta_academica', function ($q) use ($request) {
            if ($request->filled('sucursal_id')) {
                $q->where('sucursale_id', $request->sucursal_id);
            }
            if ($request->filled('oferta_id')) {
                $q->where('id', $request->oferta_id);
            }
        })
        ->orderBy('fecha', 'asc')
        ->orderBy('hora_inicio', 'asc');

        $horarios = $query->get();

        $data = $horarios->map(function ($horario) use ($mostrarColoresOferta) {
            $oferta = $horario->modulo?->oferta_academica;
            $modulo = $horario->modulo;
            $docente = $modulo?->docente?->persona;
            $posgrado = $oferta?->posgrado;

            $color = $mostrarColoresOferta 
                ? ($oferta?->color ?? '#6366f1')
                : ($modulo?->color ?? $oferta?->color ?? '#6366f1');

            return [
                'id' => $horario->id,
                'fecha' => $horario->fecha?->format('Y-m-d'),
                'hora_inicio' => $horario->hora_inicio,
                'hora_fin' => $horario->hora_fin,
                'estado' => $horario->estado,
                'color' => $color,
                'color_oferta' => $oferta?->color ?? '#6366f1',
                'modulo' => $modulo ? [
                    'id' => $modulo->id,
                    'nombre' => $modulo->nombre,
                    'n_modulo' => $modulo->n_modulo,
                    'estado' => $modulo->estado,
                    'color' => $modulo->color,
                    'fecha_inicio' => $modulo->fecha_inicio?->format('Y-m-d'),
                    'fecha_fin' => $modulo->fecha_fin?->format('Y-m-d'),
                ] : null,
                'oferta' => $oferta ? [
                    'id' => $oferta->id,
                    'codigo' => $oferta->codigo,
                    'gestion' => $oferta->gestion,
                    'color' => $oferta->color,
                    'fecha_inicio_programa' => $oferta->fecha_inicio_programa?->format('Y-m-d'),
                    'fecha_fin_programa' => $oferta->fecha_fin_programa?->format('Y-m-d'),
                    'posgrado' => $posgrado ? [
                        'id' => $posgrado->id,
                        'nombre' => $posgrado->nombre,
                        'objetivo' => $posgrado->objetivo,
                    ] : null,
                    'fase' => $oferta->fase ? [
                        'id' => $oferta->fase->id,
                        'nombre' => $oferta->fase->nombre,
                    ] : null,
                    'modalidad' => $oferta->modalidad ? [
                        'id' => $oferta->modalidad->id,
                        'nombre' => $oferta->modalidad->nombre,
                    ] : null,
                ] : null,
                'docente' => $docente ? [
                    'nombres' => $docente->nombres,
                    'apellido_paterno' => $docente->apellido_paterno,
                    'apellido_materno' => $docente->apellido_materno,
                ] : null,
                'trabajador_cargo' => $horario->trabajadorCargo?->cargo?->nombre,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function listarOfertas($sucursalId)
    {
        $ofertas = OfertasAcademica::with(['posgrado', 'fase'])
            ->where('sucursale_id', $sucursalId)
            ->orderBy('gestion', 'desc')
            ->orderBy('codigo', 'asc')
            ->get();

        return response()->json(['data' => $ofertas]);
    }
}
