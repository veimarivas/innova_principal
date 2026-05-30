<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cuota;
use App\Models\Pago;
use App\Models\PagoRespaldo;
use App\Models\PagosCuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComprobantesPagoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']);
    }

    public function index(Request $request)
    {
        $tab = $request->tab ?? 'nuevos';

        $query = PagoRespaldo::with([
            'inscripcion.estudiante.persona',
            'inscripcion.ofertaAcademica.programa',
            'inscripcion.planesPago',
            'inscripcion.trabajador_cargo.trabajador.persona',
            'cuotas',
        ]);

        if ($tab === 'nuevos') {
            $query->whereIn('estado', ['pendiente', 'rechazado']);
        } elseif ($tab === 'verificados') {
            $query->where('estado', 'verificado');
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('inscripcion.estudiante.persona', function ($q) use ($s) {
                $q->where('nombres', 'like', "%{$s}%")
                    ->orWhere('apellido_paterno', 'like', "%{$s}%")
                    ->orWhere('carnet', 'like', "%{$s}%");
            });
        }

        $comprobantes = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $cuentasBancarias = \App\Models\CuentaBancaria::with('banco')->where('estado', true)->get();

        return view('admin.comprobantes.index', compact('comprobantes', 'tab', 'cuentasBancarias'));
    }

    public function getCuotas(int $id)
    {
        $comprobante = PagoRespaldo::with([
            'cuotas',
            'inscripcion.estudiante.persona',
            'inscripcion.planesPago',
        ])->findOrFail($id);

        // Obtener IDs de cuotas asociadas al comprobante
        $cuotasComprobanteIds = $comprobante->cuotas->pluck('id')->toArray();

        // Obtener todas las cuotas de la inscripción del comprobante
        $inscripcion = $comprobante->inscripcion;
        $todasCuotas = $inscripcion ? Cuota::where('inscripcione_id', $inscripcion->id)->get() : collect();

        // Extraer concepto del nombre (ej: "Matrícula - Cuota 1" → "Matrícula")
        $extraerConcepto = function ($nombre) {
            $partes = explode(' - ', $nombre);
            return $partes[0] ?? $nombre;
        };

        $persona = $comprobante->inscripcion?->estudiante?->persona;
        $nombre  = trim(($persona?->nombres ?? '') . ' ' . ($persona?->apellido_paterno ?? '') . ' ' . ($persona?->apellido_materno ?? ''));

        return response()->json([
            'success'      => true,
            'comprobante'  => [
                'id'             => $comprobante->id,
                'archivo_url'    => asset('storage/comprobantes/' . $comprobante->archivo),
                'archivo_ext'    => strtolower(pathinfo($comprobante->archivo, PATHINFO_EXTENSION)),
                'observaciones'  => $comprobante->observaciones,
            ],
            'estudiante'   => $nombre,
            'plan_nombre'  => $comprobante->inscripcion?->planesPago?->nombre ?? 'Sin plan',
            'cuotas'       => $todasCuotas->map(function ($c) use ($cuotasComprobanteIds, $extraerConcepto) {
                return [
                    'id'                => $c->id,
                    'nombre'            => $c->nombre,
                    'n_cuota'           => $c->n_cuota,
                    'monto_bs'          => (float) $c->monto_bs,
                    'pago_pendiente_bs' => (float) $c->pago_pendiente_bs,
                    'estado'            => $c->estado,
                    'seleccionada'      => in_array($c->id, $cuotasComprobanteIds),
                    'concepto'          => $extraerConcepto($c->nombre),
                ];
            })->values(),
        ]);
    }

    public function verificar(Request $request, int $id)
    {
        $request->validate([
            'tipo_pago'    => 'required|string|max:20',
            'monto_total'  => 'required|numeric|min:0.01',
            'cuotas'       => 'required|array|min:1',
            'cuotas.*.cuota_id' => 'required|integer',
            'cuotas.*.monto'    => 'required|numeric|min:0.01',
            'efectivo'     => 'nullable|numeric|min:0',
            'qr'           => 'nullable|numeric|min:0',
            'cuenta_bancaria_id' => 'nullable',
            'referencia'   => 'nullable|string|max:255',
        ]);

        if ($request->tipo_pago === 'Parcial') {
            $efectivo = (float) ($request->efectivo ?? 0);
            $qr = (float) ($request->qr ?? 0);
            $total = (float) $request->monto_total;

            if ($efectivo <= 0 || $qr <= 0) {
                return response()->json(['success' => false, 'message' => 'Para pago parcial debe ingresar monto en efectivo y por QR.'], 422);
            }
            if (abs(($efectivo + $qr) - $total) > 0.01) {
                return response()->json(['success' => false, 'message' => 'La suma de efectivo + QR debe ser igual al monto total del pago.'], 422);
            }
        }

        $comprobante = PagoRespaldo::with('cuotas', 'inscripcion.trabajador_cargo')->findOrFail($id);

        if ($comprobante->estado === 'verificado') {
            return response()->json(['success' => false, 'message' => 'Este comprobante ya fue verificado.'], 422);
        }

        $cuotaIdsComprobante = $comprobante->cuotas->pluck('id')->toArray();
        $cuotasInput = $request->cuotas;

        foreach ($cuotasInput as $item) {
            if (!in_array((int) $item['cuota_id'], $cuotaIdsComprobante)) {
                return response()->json(['success' => false, 'message' => "La cuota {$item['cuota_id']} no pertenece a este comprobante."], 422);
            }
        }

        $pagoIdGenerado = null;
        $montoTotalPago = 0;

        DB::transaction(function () use ($comprobante, $cuotasInput, $request, &$pagoIdGenerado, &$montoTotalPago) {
            $montoTotalPago = round((float) $request->monto_total, 2);

            $tipoPagoFinal = $request->tipo_pago === 'Parcial' ? 'Parcial' : $request->tipo_pago;

            $pago = Pago::create([
                'trabajadore_cargo_id' => $comprobante->inscripcion?->trabajadores_cargo_id,
                'monto_total'         => $montoTotalPago,
                'descuento_bs'        => 0,
                'tipo_pago'           => $tipoPagoFinal,
                'fecha_pago'          => now()->toDateString(),
                'estado'              => 'Pagado',
            ]);

            $pagoIdGenerado = $pago->id;

            $tipoPagoDetalle = $request->tipo_pago === 'Parcial' ? 'Efectivo' : $request->tipo_pago;
            
            $detalle = \App\Models\Detalle::create([
                'pago_id'   => $pago->id,
                'tipo_pago' => $tipoPagoDetalle,
                'monto_bs'  => $montoTotalPago,
            ]);
            
            if ($tipoPagoDetalle === 'Efectivo') {
                $this->registrarEnCaja($pago, $detalle, $montoTotalPago);
            } elseif (in_array($tipoPagoDetalle, ['Qr', 'Transferencia'])) {
                $this->registrarEnBanco($pago, $detalle, $tipoPagoDetalle, $montoTotalPago, $request);
            }

            foreach ($cuotasInput as $item) {
                $cuotaId = (int) $item['cuota_id'];
                $monto = (float) round($item['monto'], 2);
                $cuota = Cuota::find($cuotaId);
                if (!$cuota || $monto <= 0) continue;

                PagosCuota::create([
                    'pago_id'    => $pago->id,
                    'cuota_id'   => $cuota->id,
                    'monto_bs'   => $monto,
                    'fecha_pago' => now()->toDateString(),
                ]);

                $nuevoPendiente = max(0, (float) $cuota->pago_pendiente_bs - $monto);
                $nuevoEstado    = $nuevoPendiente <= 0 ? 'Pagado' : 'Parcial';

                $cuota->update([
                    'pago_pendiente_bs' => round($nuevoPendiente, 2),
                    'estado'            => $nuevoEstado,
                    'fecha_pago'        => $nuevoPendiente <= 0 ? now()->toDateString() : $cuota->fecha_pago,
                ]);
            }

            $comprobante->update(['estado' => 'verificado']);
        });

        $pagoActualizado = Pago::find($pagoIdGenerado);

        return response()->json([
            'success' => true,
            'mensaje' => 'Comprobante verificado y pagos registrados correctamente.',
            'data' => [
                'pago_id' => $pagoIdGenerado,
                'recibo' => $pagoActualizado->recibo,
                'total_pagado' => $montoTotalPago,
            ]
        ]);
    }

    public function rechazar(Request $request, int $id)
    {
        $comprobante = PagoRespaldo::findOrFail($id);
        $comprobante->update(['estado' => 'rechazado']);

        return response()->json(['success' => true, 'mensaje' => 'Comprobante rechazado.']);
    }

    public function pendiente(Request $request, int $id)
    {
        $comprobante = PagoRespaldo::findOrFail($id);
        $comprobante->update(['estado' => 'pendiente']);

        return response()->json(['success' => true, 'mensaje' => 'Comprobante marcado como pendiente.']);
    }

    private function registrarEnCaja($pago, $detalle, $monto)
    {
        $trabajadorCargoId = $pago->trabajadore_cargo_id;
        
        $caja = \App\Models\Caja::firstOrCreate(
            ['trabajadore_cargo_id' => $trabajadorCargoId],
            ['nombre' => 'Caja Chica', 'monto_inicial' => 0, 'monto_actual' => 0, 'estado' => 'Abierta']
        );
        
        $detalle->caja_id = $caja->id;
        $detalle->save();
        
        \App\Models\CajaMovimiento::create([
            'caja_id' => $caja->id,
            'pago_id' => $pago->id,
            'tipo' => 'Ingreso',
            'monto' => $monto,
            'descripcion' => 'Pago verificado de comprobante',
        ]);
        
        $caja->increment('monto_actual', $monto);
    }

    private function registrarEnBanco($pago, $detalle, $tipoPago, $monto, $request)
    {
        $cuentaId = $request->input('cuenta_bancaria_id');
        
        if (!$cuentaId) {
            $cuenta = \App\Models\CuentaBancaria::where('es_principal', true)->where('estado', true)->first();
            $cuentaId = $cuenta?->id;
        }
        
        if ($cuentaId) {
            $detalle->cuenta_bancaria_id = $cuentaId;
            $detalle->referencia = $request->input('referencia', '');
            $detalle->save();
            
            \App\Models\MovimientoBanco::create([
                'cuenta_bancaria_id' => $cuentaId,
                'pago_id' => $pago->id,
                'tipo' => 'Ingreso',
                'monto' => $monto,
                'referencia' => $request->input('referencia', ''),
                'descripcion' => 'Pago verificado de comprobante',
            ]);
        }
    }
}
