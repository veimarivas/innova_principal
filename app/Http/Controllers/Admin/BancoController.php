<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    public function index()
    {
        $bancos = Banco::orderBy('nombre')->paginate(20);
        return view('admin.bancos.index', compact('bancos'));
    }

    public function listar()
    {
        $bancos = Banco::with('cuentas')->orderBy('nombre')->get();
        
        return response()->json([
            'data' => $bancos->map(function ($banco) {
                return [
                    'id' => $banco->id,
                    'nombre' => $banco->nombre,
                    'sigla' => $banco->sigla,
                    'estado' => $banco->estado,
                    'cuentas' => $banco->cuentas->map(function ($cuenta) {
                        return [
                            'id' => $cuenta->id,
                            'numero_cuenta' => $cuenta->numero_cuenta,
                            'tipo_cuenta' => $cuenta->tipo_cuenta,
                            'titular' => $cuenta->titular,
                            'ci_titular' => $cuenta->ci_titular,
                            'imagen_qr' => $cuenta->imagen_qr,
                            'es_principal' => $cuenta->es_principal,
                            'estado' => $cuenta->estado,
                        ];
                    })->toArray(),
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'sigla' => 'nullable|string|max:20'
        ]);

        $banco = Banco::create($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Banco creado correctamente.', 'data' => $banco]);
        }

        return back()->with('success', 'Banco creado correctamente.');
    }

    public function update(Request $request, Banco $banco)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'sigla' => 'nullable|string|max:20'
        ]);

        $banco->update($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Banco actualizado correctamente.', 'data' => $banco]);
        }

        return back()->with('success', 'Banco actualizado correctamente.');
    }

    public function destroy(Banco $banco)
    {
        if ($banco->cuentas()->count() > 0) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'No se puede eliminar el banco porque tiene cuentas asociadas.'], 400);
            }
            return back()->with('error', 'No se puede eliminar el banco porque tiene cuentas asociadas.');
        }

        $banco->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Banco eliminado correctamente.']);
        }

        return back()->with('success', 'Banco eliminado correctamente.');
    }

    public function toggleEstado(Banco $banco)
    {
        $banco->update(['estado' => !$banco->estado]);
        return response()->json([
            'success' => true,
            'estado' => $banco->estado,
            'message' => $banco->estado ? 'Banco activado' : 'Banco desactivado'
        ]);
    }
}