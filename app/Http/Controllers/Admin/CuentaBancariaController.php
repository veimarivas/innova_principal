<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\CuentaBancaria;
use App\Models\MovimientoBanco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CuentaBancariaController extends Controller
{
    public function index()
    {
        $cuentas = CuentaBancaria::with('banco')
            ->orderBy('banco_id')
            ->orderBy('numero_cuenta')
            ->paginate(20);
            
        $bancos = Banco::where('estado', true)->orderBy('nombre')->get();
            
        return view('admin.cuentas-bancarias.index', compact('cuentas', 'bancos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'numero_cuenta' => 'required|string|max:50',
            'tipo_cuenta' => 'required|in:Cuenta Corriente,Cuenta de Ahorro',
            'titular' => 'nullable|string|max:150',
            'ci_titular' => 'nullable|string|max:20',
            'imagen_qr' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('imagen_qr')) {
            $data['imagen_qr'] = $request->file('imagen_qr')->store('cuentas/qr', 'public');
        }

        if ($request->es_principal) {
            CuentaBancaria::where('banco_id', $request->banco_id)->update(['es_principal' => false]);
        }

        $cuenta = CuentaBancaria::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Cuenta bancaria creada correctamente.', 'data' => $cuenta]);
        }

        return back()->with('success', 'Cuenta bancaria creada correctamente.');
    }

    public function update(Request $request, CuentaBancaria $cuentaBancaria)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'numero_cuenta' => 'required|string|max:50',
            'tipo_cuenta' => 'required|in:Cuenta Corriente,Cuenta de Ahorro',
            'titular' => 'nullable|string|max:150',
            'ci_titular' => 'nullable|string|max:20',
            'fecha_vencimiento_qr' => 'nullable|date',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('imagen_qr')) {
            $data['imagen_qr'] = $request->file('imagen_qr')->store('cuentas/qr', 'public');
        }

        if ($request->es_principal) {
            CuentaBancaria::where('banco_id', $request->banco_id)
                ->where('id', '!=', $cuentaBancaria->id)
                ->update(['es_principal' => false]);
        }

        $cuentaBancaria->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Cuenta bancaria actualizada correctamente.', 'data' => $cuentaBancaria]);
        }

        return back()->with('success', 'Cuenta bancaria actualizada correctamente.');
    }

    public function destroy(CuentaBancaria $cuentaBancaria)
    {
        $cuentaBancaria->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Cuenta bancaria eliminada correctamente.']);
        }

        return back()->with('success', 'Cuenta bancaria eliminada correctamente.');
    }

    public function toggleEstado(CuentaBancaria $cuentaBancaria)
    {
        $cuentaBancaria->update(['estado' => !$cuentaBancaria->estado]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'estado' => $cuentaBancaria->estado,
                'message' => $cuentaBancaria->estado ? 'Cuenta activada' : 'Cuenta desactivada'
            ]);
        }

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function detalle(CuentaBancaria $cuentaBancaria)
    {
        $cuentaBancaria->load('banco');

        $movimientos = $cuentaBancaria->movimientos()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalIngresos = $cuentaBancaria->movimientos()
            ->where('tipo', 'ingreso')
            ->sum('monto');

        $totalEgresos = $cuentaBancaria->movimientos()
            ->where('tipo', 'egreso')
            ->sum('monto');

        $balance = $totalIngresos - $totalEgresos;

        $totalMovimientos = $cuentaBancaria->movimientos()->count();

        $ultimoMovimiento = $cuentaBancaria->movimientos()
            ->orderBy('created_at', 'desc')
            ->first();

        $chartData = $cuentaBancaria->movimientos()
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as fecha"),
                DB::raw("SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END) as ingresos"),
                DB::raw("SUM(CASE WHEN tipo = 'egreso' THEN monto ELSE 0 END) as egresos")
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $chartLabels = $chartData->pluck('fecha')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'));
        $chartIngresos = $chartData->pluck('ingresos');
        $chartEgresos = $chartData->pluck('egresos');

        return view('admin.cuentas-bancarias.detalle', compact(
            'cuentaBancaria', 'movimientos',
            'totalIngresos', 'totalEgresos', 'balance',
            'totalMovimientos', 'ultimoMovimiento',
            'chartLabels', 'chartIngresos', 'chartEgresos'
        ));
    }

    public function actualizarQr(Request $request, CuentaBancaria $cuentaBancaria)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'imagen_qr' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fecha_vencimiento_qr' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $data = [];

        if ($request->hasFile('imagen_qr')) {
            if ($cuentaBancaria->imagen_qr) {
                \Storage::disk('public')->delete($cuentaBancaria->imagen_qr);
            }
            $data['imagen_qr'] = $request->file('imagen_qr')->store('cuentas/qr', 'public');
        }

        if ($request->has('fecha_vencimiento_qr')) {
            $data['fecha_vencimiento_qr'] = $request->fecha_vencimiento_qr ?: null;
        }

        if ($request->has('eliminar_qr') && $request->eliminar_qr) {
            if ($cuentaBancaria->imagen_qr) {
                \Storage::disk('public')->delete($cuentaBancaria->imagen_qr);
            }
            $data['imagen_qr'] = null;
        }

        if (!empty($data)) {
            $cuentaBancaria->update($data);
        }

        return response()->json(['success' => true, 'message' => 'QR actualizado correctamente.', 'data' => $cuentaBancaria]);
    }

    public function setPrincipal(CuentaBancaria $cuentaBancaria)
    {
        CuentaBancaria::where('banco_id', $cuentaBancaria->banco_id)
            ->update(['es_principal' => false]);
            
        $cuentaBancaria->update(['es_principal' => true]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Cuenta principal actualizada.', 'data' => $cuentaBancaria]);
        }

        return back()->with('success', 'Cuenta principal actualizada.');
    }
}