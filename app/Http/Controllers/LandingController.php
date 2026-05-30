<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use App\Models\Area;
use App\Models\Trabajadore;
use App\Models\Convenio;
use App\Models\Sucursale;
use App\Models\OfertasAcademica;
use App\Models\Fase;

class LandingController extends Controller
{
    public function show($id)
    {
        $oferta = OfertasAcademica::with([
            'posgrado.tipo',
            'posgrado.area',
            'posgrado.convenio',
            'programa',
            'sucursal.sede',
            'modalidad',
            'fase',
            'planesConceptos.plan_pago',
            'planesConceptos.concepto',
            'modulos.docente.persona',
        ])->findOrFail($id);

        // Agrupa los planes de pago con sus conceptos
        $planesPago = $oferta->planesConceptos
            ->groupBy(fn($pc) => optional($pc->plan_pago)->nombre ?? 'General');

        // Otras ofertas del mismo posgrado (relacionadas)
        $relacionadas = OfertasAcademica::with([
            'posgrado.tipo', 'sucursal', 'planesConceptos.plan_pago', 'fase',
        ])
        ->where('posgrado_id', $oferta->posgrado_id)
        ->where('id', '!=', $oferta->id)
        ->limit(3)
        ->get();

        return view('oferta-detalle', compact('oferta', 'planesPago', 'relacionadas'));
    }

    public function index()
    {
        $tipos = Tipo::all();

        $trabajadores = Trabajadore::with([
            'persona',
            'trabajadores_cargos.cargo',
            'trabajadores_cargos.sucursale.sede',
        ])->get()->groupBy(function ($trabajador) {
            $cargo = $trabajador->trabajadores_cargos->first();
            return ($cargo && $cargo->cargo) ? $cargo->cargo->nombre : 'General';
        });

        $convenios = Convenio::all();

        $sucursales = Sucursale::with('sede')->get();

        $sucursalesDisponibles = Sucursale::whereHas('ofertas_academicas')->with('sede')->get();

        $ofertas = OfertasAcademica::with([
            'posgrado.tipo',
            'posgrado.area',
            'posgrado.convenio',
            'programa',
            'sucursal',
            'planesConceptos.plan_pago',
            'planesConceptos.concepto',
            'fase',
        ])
        ->whereHas('fase', function ($query) {
            $query->where('nombre', 'Inscripciones');
        })
        ->get();

        return view('welcome', compact(
            'tipos',
            'trabajadores',
            'convenios',
            'sucursales',
            'sucursalesDisponibles',
            'ofertas'
        ));
    }

    public function catalogo()
    {
        $tipos = Tipo::whereHas('posgrados.ofertas_academicas')->get();
        $areas = Area::whereHas('posgrados.ofertas_academicas')->get();
        $convenios = Convenio::whereHas('posgrados.ofertas_academicas')->get();
        $sucursalesDisponibles = Sucursale::whereHas('ofertas_academicas')->with('sede')->get();

        $ofertas = OfertasAcademica::with([
            'posgrado.tipo',
            'posgrado.area',
            'posgrado.convenio',
            'programa',
            'sucursal.sede',
            'modalidad',
            'fase',
            'planesConceptos.plan_pago',
            'planesConceptos.concepto',
        ])
        ->whereHas('fase', function ($query) {
            $query->where('nombre', 'Inscripciones');
        })
        ->get();

        return view('catalogo', compact('tipos', 'areas', 'convenios', 'sucursalesDisponibles', 'ofertas'));
    }
}
