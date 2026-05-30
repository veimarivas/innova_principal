<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\TrabajadoresCargo;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index()
    {
        $trabajadores = TrabajadoresCargo::with('trabajador.persona', 'cargo')
            ->where('estado', 'Activo')
            ->get();

        $cajasAbiertas = Caja::where('estado', 'Abierta')->count();
        $totalIngresos = Caja::where('estado', 'Abierta')->sum('monto_actual');
        $totalCajas = Caja::count();

        return view('admin.cajas.index', compact(
            'trabajadores', 'cajasAbiertas', 'totalIngresos', 'totalCajas'
        ));
    }

    public function listar()
    {
        $cajas = Caja::with('trabajadorCargo.trabajador.persona')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $cajas->map(function ($caja) {
                return [
                    'id' => $caja->id,
                    'nombre' => $caja->nombre,
                    'trabajador' => $caja->trabajadorCargo?->trabajador?->persona
                        ? ($caja->trabajadorCargo->trabajador->persona->nombre . ' ' . $caja->trabajadorCargo->trabajador->persona->apellido_paterno)
                        : 'Sin asignar',
                    'monto_inicial' => (float) $caja->monto_inicial,
                    'monto_actual' => (float) $caja->monto_actual,
                    'estado' => $caja->estado,
                    'fecha_apertura' => $caja->fecha_apertura?->format('d/m/Y H:i'),
                    'fecha_cierre' => $caja->fecha_cierre?->format('d/m/Y H:i'),
                    'created_at' => $caja->created_at?->format('d/m/Y H:i'),
                    'movimientos_count' => $caja->movimientos()->count(),
                ];
            })
        ]);
    }

    public function abrir(Request $request)
    {
        $request->validate([
            'trabajadore_cargo_id' => 'required|exists:trabajadores_cargos,id',
            'monto_inicial' => 'required|numeric|min:0'
        ]);

        $cajaExistente = Caja::where('trabajadore_cargo_id', $request->trabajadore_cargo_id)
            ->where('estado', 'Abierta')
            ->first();

        if ($cajaExistente) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'El trabajador ya tiene una caja abierta.'], 400);
            }
            return back()->with('error', 'El trabajador ya tiene una caja abierta.');
        }

        $caja = Caja::create([
            'trabajadore_cargo_id' => $request->trabajadore_cargo_id,
            'nombre' => 'Caja Chica',
            'monto_inicial' => $request->monto_inicial,
            'monto_actual' => $request->monto_inicial,
            'estado' => 'Abierta',
            'fecha_apertura' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Caja abierta correctamente.', 'data' => $caja]);
        }

        return back()->with('success', 'Caja abierta correctamente.');
    }

    public function cerrar(Request $request, Caja $caja)
    {
        $request->validate([
            'monto_cierre' => 'required|numeric|min:0'
        ]);

        $caja->update([
            'estado' => 'Cerrada',
            'monto_actual' => $request->monto_cierre,
            'fecha_cierre' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Caja cerrada correctamente.', 'data' => $caja]);
        }

        return back()->with('success', 'Caja cerrada correctamente.');
    }

    public function movimientos(Caja $caja)
    {
        $caja->load(['trabajadorCargo.trabajador.persona']);
        $movimientos = $caja->movimientos()->with('pago')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalIngresos = $caja->movimientos()->where('tipo', 'Ingreso')->sum('monto');
        $totalEgresos = $caja->movimientos()->where('tipo', 'Egreso')->sum('monto');

        return view('admin.cajas.movimientos', compact(
            'caja', 'movimientos', 'totalIngresos', 'totalEgresos'
        ));
    }
}