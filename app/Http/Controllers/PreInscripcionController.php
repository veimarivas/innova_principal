<?php

namespace App\Http\Controllers;

use App\Models\EnlacePreinscripcion;
use App\Models\Persona;
use App\Models\Estudiante;
use App\Models\Inscripcione;
use App\Models\PlanesConcepto;
use Illuminate\Http\Request;

class PreInscripcionController extends Controller
{
    public function show(string $token)
    {
        $enlace = EnlacePreinscripcion::where('token', $token)
            ->where('activo', true)
            ->with([
                'ofertaAcademica.programa',
                'ofertaAcademica.posgrado.tipo',
                'ofertaAcademica.sucursal.sede',
                'ofertaAcademica.modalidad',
                'ofertaAcademica.fase',
                'ofertaAcademica.modulos.docente.persona',
                'trabajadoresCargo.trabajador.persona',
                'trabajadoresCargo.cargo',
                'trabajadoresCargo.sucursale',
                'planesPago',
            ])
            ->firstOrFail();

        $oferta = $enlace->ofertaAcademica;

        // Si el enlace tiene un plan asignado, cargar solo los conceptos de ese plan
        // Si no tiene plan, cargar todos los planes configurados para la oferta
        if ($enlace->planes_pago_id) {
            $conceptosPlan = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
                ->where('planes_pago_id', $enlace->planes_pago_id)
                ->with('concepto')
                ->get();
            $planesPago = collect();
        } else {
            $conceptosPlan = collect();
            $planesPago = $oferta->planesConceptos()
                ->with(['plan_pago', 'concepto'])
                ->get()
                ->groupBy(fn($pc) => optional($pc->plan_pago)->nombre ?? 'General');
        }

        return view('preinscripcion.index', compact(
            'enlace', 'oferta', 'planesPago', 'conceptosPlan', 'token'
        ));
    }

    public function store(Request $request, string $token)
    {
        $enlace = EnlacePreinscripcion::where('token', $token)
            ->where('activo', true)
            ->firstOrFail();

        $validated = $request->validate([
            'nombres'          => 'required|string|max:120',
            'apellido_paterno' => 'required|string|max:80',
            'apellido_materno' => 'nullable|string|max:80',
            'carnet'           => 'nullable|string|max:30',
            'telefono'         => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:120',
            'observacion'      => 'nullable|string|max:500',
        ]);

        // 1. Buscar o crear Persona (por carnet si se proporcionó)
        $persona = null;
        if (!empty($validated['carnet'])) {
            $persona = Persona::where('carnet', $validated['carnet'])->first();
        }

        if (!$persona) {
            $persona = Persona::create([
                'nombres'          => $validated['nombres'],
                'apellido_paterno' => $validated['apellido_paterno'],
                'apellido_materno' => $validated['apellido_materno'] ?? null,
                'carnet'           => $validated['carnet'] ?? null,
                'correo'           => $validated['email'] ?? null,
                'celular'          => $validated['telefono'] ?? null,
            ]);
        }

        // 2. Buscar o crear Estudiante para esa persona
        $estudiante = Estudiante::firstOrCreate(['persona_id' => $persona->id]);

        // 3. Crear la inscripción Pre-Inscrita (si no existe ya para esta oferta)
        $yaInscrito = Inscripcione::where('ofertas_academica_id', $enlace->oferta_academica_id)
            ->where('estudiante_id', $estudiante->id)
            ->exists();

        if (!$yaInscrito) {
            Inscripcione::create([
                'ofertas_academica_id'  => $enlace->oferta_academica_id,
                'estudiante_id'         => $estudiante->id,
                'trabajadores_cargo_id' => $enlace->trabajadores_cargo_id,
                'planes_pago_id'        => $enlace->planes_pago_id,
                'estado'                => 'Pre-Inscrito',
                'adelanto_bs'           => 0,
                'fecha_registro'        => now(),
                'observacion'           => $validated['observacion'] ?? null,
            ]);
        }

        return redirect()->route('preinscripcion.exito', ['token' => $token]);
    }

    public function exito(string $token)
    {
        $enlace = EnlacePreinscripcion::where('token', $token)
            ->with([
                'ofertaAcademica.programa',
                'trabajadoresCargo.trabajador.persona',
                'trabajadoresCargo.cargo',
                'planesPago',
            ])
            ->firstOrFail();

        return view('preinscripcion.exito', compact('enlace'));
    }
}
