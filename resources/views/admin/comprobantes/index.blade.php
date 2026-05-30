@extends('layouts.master')
@section('title', 'Comprobantes de Pago')

@section('css')
<style>
.comp-page { padding: 1.5rem 0; }
.comp-header-card {
    background: linear-gradient(135deg, #9a4904 0%, #df6a04 100%);
    border-radius: 12px;
    padding: 1.5rem 2rem;
    color: white;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}
.comp-header-title { font-size: 1.4rem; font-weight: 700; margin: 0; color: white; }
.comp-header-sub   { font-size: 0.85rem; opacity: .85; margin: 0.25rem 0 0; }
.comp-filters {
    background: white;
    border-radius: 10px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.07);
}
.comp-filters form { display: flex; gap: .75rem; flex-wrap: wrap; align-items: flex-end; }
.comp-filters select, .comp-filters input[type="text"] {
    border: 1px solid #e2e8f0; border-radius: 6px; padding: .42rem .75rem;
    font-size: .875rem; color: #1e293b; background: #f8fafc; min-width: 160px;
}
.comp-filters button, .comp-filters a.btn-reset {
    padding: .42rem 1rem; border-radius: 6px; border: none;
    font-size: .875rem; font-weight: 500; cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: .3rem;
}
.btn-filtrar { background: #9a4904; color: white; }
.btn-reset   { background: #f1f5f9; color: #64748b; }
.comp-table-card { background: white; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,.07); overflow: hidden; }
.comp-table { width: 100%; border-collapse: collapse; }
.comp-table th {
    background: #f8fafc; color: #475569; font-size: .75rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .04em;
    padding: .75rem 1rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap;
}
.comp-table td {
    padding: .75rem 1rem; font-size: .85rem; color: #334155;
    border-bottom: 1px solid #f1f5f9; vertical-align: middle;
}
.comp-table tr:last-child td { border-bottom: none; }
.comp-table tr:hover td { background: #fafafa; }
.comp-tab-btn.active { color:#9a4904 !important; border-bottom-color:#9a4904 !important; }
.badge-estado {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .25rem .65rem; border-radius: 20px; font-size: .72rem; font-weight: 600;
}
.badge-estado.pendiente  { background: #fef3c7; color: #92400e; }
.badge-estado.verificado { background: #d1fae5; color: #065f46; }
.badge-estado.rechazado  { background: #fee2e2; color: #991b1b; }
.cuota-pill {
    display: inline-block; background: #f1f5f9; color: #475569;
    border-radius: 4px; padding: .15rem .5rem; font-size: .7rem; margin: .1rem; border: 1px solid #e2e8f0;
}
.btn-accion {
    padding: .28rem .65rem; border-radius: 5px; border: none;
    font-size: .75rem; font-weight: 500; cursor: pointer; transition: opacity .15s;
    display: inline-flex; align-items: center; gap: .25rem;
}
.btn-accion:hover { opacity: .82; }
.btn-verificar { background: #d1fae5; color: #065f46; }
.btn-rechazar  { background: #fee2e2; color: #991b1b; }
.btn-pendiente { background: #fef3c7; color: #92400e; }
.btn-ver       { background: #e0f2fe; color: #0369a1; }
.empty-comp    { text-align: center; padding: 3rem 1rem; color: #94a3b8; }
.empty-comp i  { font-size: 3rem; display: block; margin-bottom: .75rem; }
/* Modal verificar */
.cuota-pago-row {
    display: flex; align-items: center; gap: .75rem;
    padding: .6rem; border-bottom: 1px solid #f1f5f9;
}
.cuota-pago-row:last-child { border-bottom: none; }
.cuota-pago-info { flex: 1; }
.cuota-monto-input {
    width: 110px; border: 1px solid #e2e8f0; border-radius: 6px;
    padding: .35rem .6rem; font-size: .85rem; text-align: right;
    background: #f8fafc; color: #1e293b; flex-shrink: 0;
}
.cuota-monto-input:focus { outline: none; border-color: #9a4904; background: white; }
/* Modal verificar dos columnas */
#modalVerificar .modal-body { padding: 0; }
#modalVerificar .row.g-0 { min-height: 450px; }
.cuota-checkbox { width: 18px; height: 18px; cursor: pointer; }
.cuota-monto-input:disabled { opacity: 0.6; }
</style>
@endsection

@section('content')
<div class="comp-page container-fluid">

    <div class="comp-header-card">
        <div>
            <h1 class="comp-header-title"><i class="ri-file-list-3-line me-2"></i>Comprobantes de Pago</h1>
            <p class="comp-header-sub">Lista de comprobantes de pago enviados</p>
        </div>
        <div style="background:rgba(255,255,255,.15);border-radius:8px;padding:.5rem 1rem;font-size:.85rem;">
            Total: <strong>{{ $comprobantes->total() }}</strong>
        </div>
    </div>

    <div class="comp-tabs mb-3" style="display:flex;gap:.5rem;border-bottom:2px solid #e2e8f0;">
        <button type="button" class="comp-tab-btn {{ request('tab', 'nuevos') === 'nuevos' ? 'active' : '' }}"
            data-tab="nuevos" style="padding:.6rem 1.25rem;border:none;background:none;font-weight:600;font-size:.85rem;cursor:pointer;color:#64748b;border-bottom:2px solid transparent;margin-bottom:-2px;"
            onclick="window.location='{{ route('admin.comprobantes.index', ['tab' => 'nuevos']) }}'">
            <i class="ri-file-add-line me-1"></i>Nuevos
            <span style="background:#fef3c7;color:#92400e;padding:.1rem .4rem;border-radius:10px;font-size:.7rem;margin-left:.35rem;">{{ $comprobantes->where('estado', 'pendiente')->count() }}</span>
        </button>
        <button type="button" class="comp-tab-btn {{ request('tab') === 'verificados' ? 'active' : '' }}"
            data-tab="verificados" style="padding:.6rem 1.25rem;border:none;background:none;font-weight:600;font-size:.85rem;cursor:pointer;color:#64748b;border-bottom:2px solid transparent;margin-bottom:-2px;"
            onclick="window.location='{{ route('admin.comprobantes.index', ['tab' => 'verificados']) }}'">
            <i class="ri-checkbox-circle-line me-1"></i>Verificados
            <span style="background:#d1fae5;color:#065f46;padding:.1rem .4rem;border-radius:10px;font-size:.7rem;margin-left:.35rem;">{{ $comprobantes->where('estado', 'verificado')->count() }}</span>
        </button>
    </div>

    <div class="comp-filters">
        <form method="GET" action="{{ route('admin.comprobantes.index') }}">
            <input type="hidden" name="tab" value="{{ request('tab', 'nuevos') }}">
            <div>
                <label style="font-size:.75rem;font-weight:600;color:#64748b;display:block;margin-bottom:.25rem;">Estado</label>
                <select name="estado">
                    <option value="">Todos</option>
                    <option value="pendiente"  {{ request('estado')==='pendiente'  ? 'selected':'' }}>Pendiente</option>
                    <option value="verificado" {{ request('estado')==='verificado' ? 'selected':'' }}>Verificado</option>
                    <option value="rechazado"  {{ request('estado')==='rechazado'  ? 'selected':'' }}>Rechazado</option>
                </select>
            </div>
            <div>
                <label style="font-size:.75rem;font-weight:600;color:#64748b;display:block;margin-bottom:.25rem;">Buscar estudiante</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o carnet...">
            </div>
            <button type="submit" class="btn-filtrar"><i class="ri-filter-line"></i>Filtrar</button>
            <a href="{{ route('admin.comprobantes.index', ['tab' => request('tab', 'nuevos')]) }}" class="btn-reset"><i class="ri-refresh-line"></i></a>
        </form>
    </div>

    <div class="comp-table-card">
        @if($comprobantes->isEmpty())
            <div class="empty-comp">
                <i class="ri-file-unknow-line"></i>
                <p>No hay comprobantes de pago registrados.</p>
            </div>
        @else
        <div style="overflow-x:auto;">
            <table class="comp-table">
                <thead>
                    <tr>
                        <th>#</th><th>Estudiante</th><th>Programa</th><th>Plan de Pago</th>
                        <th>Asesor</th><th>Cuotas</th><th>Archivo</th>
                        <th>Obs.</th><th>Fecha</th><th>Estado</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comprobantes as $comp)
                    @php
                        $ins     = $comp->inscripcion;
                        $persona = $ins?->estudiante?->persona;
                        $asesor  = $ins?->trabajador_cargo?->trabajador?->persona;
                        $nombre  = trim(($persona?->nombres??'').' '.($persona?->apellido_paterno??'').' '.($persona?->apellido_materno??''));
                        $asesorN = $asesor ? trim(($asesor->nombres??'').' '.($asesor->apellido_paterno??'')) : '—';
                        $ext     = strtolower(pathinfo($comp->archivo, PATHINFO_EXTENSION));
                    @endphp
                    <tr>
                        <td style="color:#94a3b8;font-size:.75rem;">{{ $comp->id }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $nombre ?: '—' }}</div>
                            <div style="font-size:.72rem;color:#94a3b8;">CI: {{ $persona?->carnet ?? '—' }}</div>
                        </td>
                        <td style="max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"
                            title="{{ $ins?->ofertaAcademica?->programa?->nombre ?? '—' }}">
                            {{ $ins?->ofertaAcademica?->programa?->nombre ?? '—' }}
                        </td>
                        <td>{{ $ins?->planesPago?->nombre ?? '—' }}</td>
                        <td>{{ $asesorN }}</td>
                        <td>
                            @forelse($comp->cuotas as $cuota)
                                <span class="cuota-pill" title="Bs {{ number_format($cuota->monto_bs,2) }} — {{ $cuota->estado }}">
                                    {{ $cuota->nombre }} #{{ $cuota->n_cuota }}
                                </span>
                            @empty
                                <span style="color:#94a3b8;font-size:.75rem;">—</span>
                            @endforelse
                        </td>
                        <td>
                            <a href="{{ asset('storage/comprobantes/'.$comp->archivo) }}" target="_blank"
                               class="btn-accion btn-ver">
                                <i class="ri-{{ $ext==='pdf'?'file-pdf':'image' }}-line"></i>Ver
                            </a>
                        </td>
                        <td style="max-width:160px;font-size:.78rem;color:#64748b;">{{ $comp->observaciones ?: '—' }}</td>
                        <td style="white-space:nowrap;font-size:.78rem;">{{ $comp->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge-estado {{ $comp->estado }}">
                                <i class="ri-{{ $comp->estado==='verificado'?'check':($comp->estado==='rechazado'?'close':'time') }}-line"></i>
                                {{ ucfirst($comp->estado) }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:.3rem;flex-wrap:wrap;">
                                @if($comp->estado !== 'verificado')
                                <button class="btn-accion btn-verificar js-btn-verificar"
                                    data-id="{{ $comp->id }}"
                                    title="Verificar y registrar pago">
                                    <i class="ri-check-double-line"></i>Verificar
                                </button>
                                @endif
                                @if($comp->estado !== 'rechazado')
                                <button class="btn-accion btn-rechazar js-btn-simple"
                                    data-id="{{ $comp->id }}" data-accion="rechazar"
                                    title="Rechazar">
                                    <i class="ri-close-line"></i>
                                </button>
                                @endif
                                @if($comp->estado !== 'pendiente')
                                <button class="btn-accion btn-pendiente js-btn-simple"
                                    data-id="{{ $comp->id }}" data-accion="pendiente"
                                    title="Marcar pendiente">
                                    <i class="ri-time-line"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($comprobantes->lastPage() > 1)
        <div style="padding:1rem 1.25rem;border-top:1px solid #f1f5f9;">
            {{ $comprobantes->links() }}
        </div>
        @endif
        @endif
    </div>

</div>

{{-- Modal Verificar Comprobante (dos columnas) --}}
<div class="modal fade" id="modalVerificar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="max-width:900px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 10px 40px rgba(0,0,0,.2);">
            <div class="modal-header" style="background:linear-gradient(135deg,#065f46,#059669);color:white;border-radius:12px 12px 0 0;padding:1rem 1.5rem;">
                <h5 class="modal-title" style="font-weight:600;font-size:1rem;color:white;">
                    <i class="ri-check-double-line me-2"></i>Verificar Comprobante
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:0;">
                <div class="row g-0" style="margin:0;">
                    {{-- LADO IZQUIERDO: Preview del comprobante --}}
                    <div class="col-md-5" style="background:#f8fafc;padding:1.25rem;border-right:1px solid #e2e8f0;min-height:450px;">
                        <h6 style="font-size:.8rem;font-weight:600;color:#475569;margin-bottom:1rem;">
                            <i class="ri-file-image-line me-1"></i>Comprobante
                        </h6>
                        <div id="vPreviewContainer" style="background:white;border-radius:8px;padding:.5rem;box-shadow:0 1px 3px rgba(0,0,0,.1);min-height:350px;">
                            <div id="modalVerificarLoading" class="text-center py-5">
                                <div class="spinner-border" style="color:#059669;" role="status"></div>
                                <p class="mt-2 mb-0 text-muted" style="font-size:.85rem;">Cargando...</p>
                            </div>
                            <div id="vPreviewContent" style="display:none;">
                                <div id="vImagePreview" style="display:none;text-align:center;">
                                    <img id="vPreviewImg" src="" alt="Comprobante" style="max-width:100%;max-height:380px;border-radius:4px;">
                                </div>
                                <div id="vPdfPreview" style="display:none;">
                                    <iframe id="vPreviewIframe" src="" style="width:100%;height:380px;border:none;border-radius:4px;"></iframe>
                                </div>
                            </div>
                        </div>
                        <div id="vObservaciones" style="margin-top:1rem;font-size:.8rem;color:#64748b;padding:.5rem;background:white;border-radius:6px;border:1px solid #e2e8f0;"></div>
                    </div>

                    {{-- LADO DERECHO: Formulario de registro --}}
                    <div class="col-md-7" style="padding:1.25rem;">
                        <div id="modalVerificarBodyRight" style="display:none;">
                            {{-- Info estudiante --}}
                            <div style="padding:.65rem 1rem;background:#f8fafc;border-radius:8px;border-left:4px solid #059669;margin-bottom:1rem;">
                                <div style="font-weight:600;color:#1e293b;font-size:.9rem;" id="vEstudianteNombre"></div>
                                <div style="font-size:.78rem;color:#64748b;margin-top:.2rem;" id="vPlanNombre"></div>
                            </div>

                            {{-- Tipo de pago --}}
                            <div style="margin-bottom:1rem;">
                                <label style="font-size:.8rem;font-weight:600;color:#475569;display:block;margin-bottom:.35rem;">
                                    Tipo de pago <span style="color:#dc2626;">*</span>
                                </label>
                                <select id="vTipoPago" style="border:1px solid #e2e8f0;border-radius:6px;padding:.42rem .75rem;font-size:.875rem;width:100%;background:#f8fafc;">
                                    <option value="Qr">QR</option>
                                    <option value="Transferencia">Transferencia</option>
                                </select>
                            </div>

                            {{-- Cuenta bancaria (visible para QR y Transferencia) --}}
                            <div id="vCuentaBancariaContainer" style="margin-bottom:1rem;display:none;">
                                <label style="font-size:.8rem;font-weight:600;color:#475569;display:block;margin-bottom:.35rem;">
                                    Cuenta Bancaria <span style="color:#dc2626;">*</span>
                                </label>
                                <select id="vCuentaBancaria" style="border:1px solid #e2e8f0;border-radius:6px;padding:.42rem .75rem;font-size:.875rem;width:100%;background:#f8fafc;">
                                    <option value="">Seleccionar cuenta...</option>
                                    @foreach($cuentasBancarias as $cuenta)
                                        <option value="{{ $cuenta->id }}">{{ $cuenta->banco->nombre }} - {{ $cuenta->numero_cuenta }} ({{ $cuenta->tipo_cuenta }})</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Referencia (visible para Transferencia) --}}
                            <div id="vReferenciaContainer" style="margin-bottom:1rem;display:none;">
                                <label style="font-size:.8rem;font-weight:600;color:#475569;display:block;margin-bottom:.35rem;">
                                    Referencia <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="text" id="vReferencia" class="form-control" placeholder="Número de referencia" style="border:1px solid #e2e8f0;border-radius:6px;padding:.42rem .75rem;font-size:.875rem;background:#f8fafc;">
                            </div>

                            {{-- Campos parcial (escondidos por defecto) --}}
                            <div id="vCamposParcial" style="display:none;margin-bottom:1rem;padding:.75rem;background:#fef3c7;border-radius:8px;border:1px solid #fcd34d;">
                                <div style="font-size:.78rem;font-weight:600;color:#92400e;margin-bottom:.5rem;">
                                    <i class="ri-money-dollar-circle-line me-1"></i>Detalle de pago mixto
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label style="font-size:.72rem;color:#78350f;display:block;">Monto en efectivo</label>
                                        <input type="number" id="vEfectivo" class="form-control form-control-sm" placeholder="0.00" step="0.01" min="0">
                                    </div>
                                    <div class="col-6">
                                        <label style="font-size:.72rem;color:#78350f;display:block;">Monto por QR</label>
                                        <input type="number" id="vQr" class="form-control form-control-sm" placeholder="0.00" step="0.01" min="0">
                                    </div>
                                </div>
                                <div style="font-size:.7rem;color:#dc2626;margin-top:.35rem;" id="vParcialError" style="display:none;"></div>
                            </div>

                            {{-- Cuotas agrupadas por concepto --}}
                            <div>
                                <label style="font-size:.8rem;font-weight:600;color:#475569;display:block;margin-bottom:.5rem;">
                                    Cuotas a registrar <span style="color:#dc2626;">*</span>
                                </label>
                                <div id="vCuotasContainer" style="border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;max-height:200px;overflow-y:auto;"></div>
                            </div>

                            {{-- Monto total --}}
                            <div style="margin-top:1rem;padding:.75rem;background:#f0fdf4;border-radius:8px;border:1px solid #bbf7d0;">
                                <div style="display:flex;align-items:center;justify-content:space-between;">
                                    <label style="font-size:.8rem;font-weight:600;color:#166534;">Monto total del pago</label>
                                    <input type="number" id="vMontoTotal" style="width:120px;border:1px solid #86efac;border-radius:6px;padding:.35rem .6rem;font-size:.9rem;font-weight:600;text-align:right;color:#166534;background:white;" step="0.01" min="0">
                                </div>
                            </div>

                            {{-- Validación suma --}}
                            <div id="vSumaError" style="display:none;margin-top:.5rem;padding:.5rem;background:#fee2e2;border-radius:6px;font-size:.75rem;color:#991b1b;">
                                <i class="ri-error-warning-line me-1"></i>
                                <span id="vSumaErrorMsg"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;">
                <button type="button" class="btn" data-bs-dismiss="modal" style="padding:.5rem 1.25rem;border-radius:6px;border:1px solid #cbd5e1;background:white;color:#475569;font-weight:500;">
                    Cancelar
                </button>
                <button type="button" id="btnConfirmarVerificar" disabled style="padding:.5rem 1.25rem;border-radius:6px;border:none;background:#059669;color:white;font-weight:500;cursor:pointer;opacity:0.6;">
                    <i class="ri-check-double-line"></i> Verificar y Registrar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Recibo de Pago --}}
<div class="modal fade" id="modalReciboPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:450px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 10px 40px rgba(0,0,0,.2);">
            <div class="modal-header" style="background:linear-gradient(135deg,#059669,#10b981);color:white;border-radius:12px 12px 0 0;padding:1.25rem 1.5rem;">
                <h5 class="modal-title" style="font-weight:600;font-size:1.1rem;">
                    <i class="ri-check-line me-2"></i>Pago Registrado
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4" style="padding:1.5rem;">
                <div style="width:70px;height:70px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="ri-check-double-line" style="font-size:2rem;color:#16a34a;"></i>
                </div>
                <p id="recibo-mensaje-exito" style="font-size:.95rem;color:#374151;margin-bottom:1rem;">Pago registrado correctamente.</p>
                <div style="background:#f8fafc;border-radius:8px;padding:1rem;margin-bottom:1rem;">
                    <div style="font-size:.8rem;color:#64748b;">Número de Recibo</div>
                    <div id="recibo-numero" style="font-size:1.25rem;font-weight:700;color:#059669;">—</div>
                </div>
                <div style="background:#f8fafc;border-radius:8px;padding:1rem;">
                    <div style="font-size:.8rem;color:#64748b;">Total Pagado</div>
                    <div id="recibo-total" style="font-size:1.25rem;font-weight:700;color:#059669;">—</div>
                </div>
            </div>
            <div class="modal-footer" style="border:none;padding:1rem 1.5rem 1.5rem;">
                <button type="button" id="btn-imprimir-recibo" class="btn fw-semibold" style="background:#e2e8f0;color:#374151;">
                    <i class="ri-printer-line me-1"></i>Imprimir
                </button>
                <button type="button" id="btn-descargar-recibo" class="btn btn-outline-secondary fw-semibold">
                    <i class="ri-download-cloud-line me-1"></i>Descargar PDF
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toastComp" style="position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:none;min-width:280px;">
    <div id="toastCompInner" style="padding:.75rem 1.25rem;border-radius:8px;color:white;font-size:.875rem;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,.2);display:flex;align-items:center;gap:.6rem;">
        <i id="toastCompIcon"></i><span id="toastCompMsg"></span>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let verifiandoId = null;
    let cuotasData = [];

    function toast(tipo, msg) {
        const el = document.getElementById('toastComp');
        const inn = document.getElementById('toastCompInner');
        inn.style.background = tipo === 'success' ? '#16a34a' : '#dc2626';
        document.getElementById('toastCompIcon').className = tipo === 'success' ? 'ri-check-circle-line' : 'ri-error-warning-line';
        document.getElementById('toastCompMsg').textContent = msg;
        el.style.display = 'block';
        setTimeout(() => { el.style.display = 'none'; }, 3500);
    }

    function escH(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function agruparPorConcepto(cuotas) {
        const grupos = {};
        cuotas.forEach(c => {
            const concepto = c.concepto || 'Otros';
            if (!grupos[concepto]) grupos[concepto] = [];
            grupos[concepto].push(c);
        });
        return grupos;
    }

    function ordenarConceptos(grupos) {
        const orden = { 'Matriculación': 1, 'Colegiatura': 2, 'Certificación': 3 };
        return Object.keys(grupos).sort((a, b) => {
            const ordenA = orden[a] || 99;
            const ordenB = orden[b] || 99;
            return ordenA - ordenB;
        });
    }

    function distribuirMonto(montoTotal, cuotasSeleccionadas) {
        if (cuotasSeleccionadas.length === 0 || montoTotal <= 0) return {};
        
        const resultado = {};
        let restante = Math.round(montoTotal * 100) / 100;
        
        for (const c of cuotasSeleccionadas) {
            if (restante <= 0) {
                resultado[c.id] = 0;
                continue;
            }
            const pendiente = Math.round(c.pago_pendiente_bs * 100) / 100;
            const monto = Math.min(restante, pendiente);
            resultado[c.id] = Math.round(monto * 100) / 100;
            restante = Math.round((restante - monto) * 100) / 100;
        }
        
        return resultado;
    }

    function renderizarCuotas() {
        const container = document.getElementById('vCuotasContainer');
        const grupos = agruparPorConcepto(cuotasData);
        let html = '';
        
        ordenarConceptos(grupos).forEach(concepto => {
            const cuotas = grupos[concepto];
            html += `<div style="background:#f8fafc;padding:.5rem .75rem;border-bottom:1px solid #e2e8f0;">
                <span style="font-size:.75rem;font-weight:600;color:#475569;text-transform:uppercase;">${escH(concepto)}</span>
            </div>`;
            
cuotas.sort((a, b) => a.n_cuota - b.n_cuota).forEach(c => {
                const estadoLower = c.estado?.toLowerCase() || '';
                const estadoColor = estadoLower === 'pagado' ? '#16a34a' : estadoLower === 'vencido' ? '#dc2626' : '#f59e0b';
                const disabled = estadoLower === 'pagado' ? 'disabled' : '';
                const checked = c.seleccionada ? 'checked' : '';
                
                html += `<div class="cuota-pago-row" style="display:flex;align-items:center;gap:.5rem;padding:.5rem .75rem;border-bottom:1px solid #f1f5f9;">
                    <input type="checkbox" class="cuota-checkbox" data-cuota-id="${c.id}" ${checked} ${disabled}>
                    <div class="cuota-pago-info" style="flex:1;">
                        <div style="font-size:.8rem;font-weight:500;color:#1e293b;">${escH(c.nombre)} #${c.n_cuota}</div>
                        <div style="font-size:.7rem;color:#64748b;">Total: Bs ${c.monto_bs.toFixed(2)} · Pendiente: <strong>Bs ${c.pago_pendiente_bs.toFixed(2)}</strong></div>
                    </div>
                    <span style="font-size:.65rem;font-weight:600;color:${estadoColor};background:${estadoColor}1a;padding:.1rem .35rem;border-radius:3px;">${escH(c.estado)}</span>
                    <input type="number" class="cuota-monto-input" data-cuota-id="${c.id}" 
                        style="width:90px;border:1px solid #e2e8f0;border-radius:4px;padding:.25rem .5rem;font-size:.8rem;text-align:right;background:${c.seleccionada ? 'white' : '#f1f5f9'};${c.seleccionada ? '' : 'pointer-events:none;'}"
                        ${disabled} min="0" max="${c.pago_pendiente_bs}" step="0.01" value="${c.seleccionada ? c.pago_pendiente_bs.toFixed(2) : '0.00'}">
                </div>`;
            });
        });
        
        container.innerHTML = html;
        
        container.querySelectorAll('.cuota-checkbox').forEach(chk => {
            chk.addEventListener('change', () => {
                const input = container.querySelector(`.cuota-monto-input[data-cuota-id="${chk.dataset.cuotaId}"]`);
                if (chk.checked) {
                    input.style.background = 'white';
                    input.style.pointerEvents = 'auto';
                    const cuota = cuotasData.find(c => c.id == chk.dataset.cuotaId);
                    input.value = cuota.pago_pendiente_bs.toFixed(2);
                } else {
                    input.style.background = '#f1f5f9';
                    input.style.pointerEvents = 'none';
                    input.value = '0.00';
                }
                recalcularYDistribuir();
            });
        });
        
        container.querySelectorAll('.cuota-monto-input').forEach(inp => {
            inp.addEventListener('input', () => {
                const suma = calcularSuma();
                document.getElementById('vMontoTotal').value = suma.toFixed(2);
                validarSuma();
            });
        });
        
        recalcularYDistribuir();
    }

    function calcularSuma() {
        let suma = 0;
        document.querySelectorAll('.cuota-monto-input').forEach(inp => {
            suma += parseFloat(inp.value) || 0;
        });
        return suma;
    }

    function recalcularYDistribuir() {
        const montoTotal = parseFloat(document.getElementById('vMontoTotal').value) || 0;
        const seleccionadas = [];
        cuotasData.forEach(c => {
            const chk = document.querySelector(`.cuota-checkbox[data-cuota-id="${c.id}"]`);
            if (chk && chk.checked) seleccionadas.push(c);
        });
        
        if (seleccionadas.length > 0 && montoTotal > 0) {
            const distribucion = distribuirMonto(montoTotal, seleccionadas);
            Object.keys(distribucion).forEach(cuotaId => {
                const inp = document.querySelector(`.cuota-monto-input[data-cuota-id="${cuotaId}"]`);
                if (inp) inp.value = distribucion[cuotaId].toFixed(2);
            });
        }
        validarSuma();
    }

    function validarSuma() {
        const suma = calcularSuma();
        const total = parseFloat(document.getElementById('vMontoTotal').value) || 0;
        const diff = Math.abs(suma - total) > 0.01;
        const errorDiv = document.getElementById('vSumaError');
        
        if (diff) {
            errorDiv.style.display = 'block';
            document.getElementById('vSumaErrorMsg').textContent = `La suma de cuotas (Bs ${suma.toFixed(2)}) no coincide con el monto total (Bs ${total.toFixed(2)})`;
        } else {
            errorDiv.style.display = 'none';
        }
        return !diff;
    }

    function validarParcial() {
        const tipoPago = document.getElementById('vTipoPago').value;
        if (tipoPago !== 'Parcial') return true;
        
        const efectivo = parseFloat(document.getElementById('vEfectivo').value) || 0;
        const qr = parseFloat(document.getElementById('vQr').value) || 0;
        const total = parseFloat(document.getElementById('vMontoTotal').value) || 0;
        
        const errorDiv = document.getElementById('vParcialError');
        
        if (efectivo <= 0 || qr <= 0) {
            errorDiv.style.display = 'block';
            errorDiv.textContent = 'Ingresa monto en efectivo y por QR';
            return false;
        }
        
        if (Math.abs((efectivo + qr) - total) > 0.01) {
            errorDiv.style.display = 'block';
            errorDiv.textContent = `Efectivo (Bs ${efectivo.toFixed(2)}) + QR (Bs ${qr.toFixed(2)}) debe ser igual al monto total (Bs ${total.toFixed(2)})`;
            return false;
        }
        
        errorDiv.style.display = 'none';
        return true;
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-btn-verificar');
        if (!btn) return;

        verifiandoId = btn.dataset.id;
        
        document.getElementById('modalVerificarLoading').style.display = 'block';
        document.getElementById('vPreviewContent').style.display = 'none';
        document.getElementById('vObservaciones').textContent = '';
        document.getElementById('modalVerificarBodyRight').style.display = 'none';
        document.getElementById('btnConfirmarVerificar').disabled = false;
        document.getElementById('btnConfirmarVerificar').innerHTML = '<i class="ri-check-double-line"></i> Verificar y Registrar';
        
        document.getElementById('vTipoPago').value = 'Qr';
        document.getElementById('vCuentaBancaria').value = '';
        document.getElementById('vReferencia').value = '';
        document.getElementById('vCuentaBancariaContainer').style.display = 'block';
        document.getElementById('vReferenciaContainer').style.display = 'none';
        
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalVerificar')).show();

        fetch(`/admin/comprobantes/${verifiandoId}/cuotas`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('modalVerificarLoading').style.display = 'none';
                if (!data.success) {
                    toast('error', 'Error al cargar datos');
                    return;
                }

                document.getElementById('vEstudianteNombre').textContent = data.estudiante;
                document.getElementById('vPlanNombre').textContent = data.plan_nombre;
                
                document.getElementById('vObservaciones').textContent = data.comprobante.observaciones || '';
                const ext = data.comprobante.archivo_ext;
                const url = data.comprobante.archivo_url;
                
                if (ext === 'pdf') {
                    document.getElementById('vImagePreview').style.display = 'none';
                    document.getElementById('vPdfPreview').style.display = 'block';
                    document.getElementById('vPreviewIframe').src = url;
                } else {
                    document.getElementById('vPdfPreview').style.display = 'none';
                    document.getElementById('vImagePreview').style.display = 'block';
                    document.getElementById('vPreviewImg').src = url;
                }
                
                document.getElementById('vPreviewContent').style.display = 'block';
                
                cuotasData = data.cuotas;
                renderizarCuotas();
                
                document.getElementById('vTipoPago').value = 'Qr';
                document.getElementById('vCamposParcial').style.display = 'none';
                document.getElementById('vEfectivo').value = '';
                document.getElementById('vQr').value = '';
                document.getElementById('vCuentaBancaria').value = '';
                document.getElementById('vReferencia').value = '';
                document.getElementById('vCuentaBancariaContainer').style.display = 'block';
                document.getElementById('vReferenciaContainer').style.display = 'none';
                
                validarFormularioVerificar();
                
                document.getElementById('modalVerificarBodyRight').style.display = 'block';
            })
            .catch(() => {
                document.getElementById('modalVerificarLoading').style.display = 'none';
                toast('error', 'Error de conexión');
            });
    });

    document.getElementById('vTipoPago')?.addEventListener('change', function() {
        const camposParcial = document.getElementById('vCamposParcial');
        const cuentaContainer = document.getElementById('vCuentaBancariaContainer');
        const referenciaContainer = document.getElementById('vReferenciaContainer');
        
        camposParcial.style.display = this.value === 'Parcial' ? 'block' : 'none';
        
        if (this.value === 'Qr' || this.value === 'Transferencia') {
            cuentaContainer.style.display = 'block';
            referenciaContainer.style.display = this.value === 'Transferencia' ? 'block' : 'none';
        } else {
            cuentaContainer.style.display = 'none';
            referenciaContainer.style.display = 'none';
        }
        
        if (this.value !== 'Parcial') {
            document.getElementById('vEfectivo').value = '';
            document.getElementById('vQr').value = '';
            document.getElementById('vParcialError').style.display = 'none';
        }
        
        validarFormularioVerificar();
    });

    function validarFormularioVerificar() {
        const tipoPago = document.getElementById('vTipoPago').value;
        const cuentaBancaria = document.getElementById('vCuentaBancaria').value;
        const referencia = document.getElementById('vReferencia').value.trim();
        const btn = document.getElementById('btnConfirmarVerificar');
        
        let valido = true;
        let mensaje = '';
        
        if (!tipoPago) {
            valido = false;
            mensaje = 'Seleccione tipo de pago';
        } else if (tipoPago === 'Qr' || tipoPago === 'Transferencia') {
            if (!cuentaBancaria) {
                valido = false;
                mensaje = 'Seleccione cuenta bancaria';
            }
            if (tipoPago === 'Transferencia' && !referencia) {
                valido = false;
                mensaje = 'Ingrese la referencia';
            }
        }
        
        btn.disabled = !valido;
        return valido;
    }

    document.getElementById('vCuentaBancaria')?.addEventListener('change', validarFormularioVerificar);
    document.getElementById('vReferencia')?.addEventListener('input', validarFormularioVerificar);

    document.getElementById('vMontoTotal')?.addEventListener('input', recalcularYDistribuir);

    document.getElementById('btnConfirmarVerificar')?.addEventListener('click', function () {
        if (!verifiandoId) return;

        const seleccionadas = [];
        document.querySelectorAll('.cuota-checkbox:checked').forEach(chk => {
            seleccionadas.push(parseInt(chk.dataset.cuotaId));
        });
        
        if (seleccionadas.length === 0) {
            toast('error', 'Selecciona al menos una cuota');
            return;
        }

        if (!validarSuma()) {
            toast('error', 'La suma de cuotas debe ser igual al monto total');
            return;
        }

        if (!validarParcial()) {
            return;
        }

        const tipoPago = document.getElementById('vTipoPago').value;
        const montoTotal = parseFloat(document.getElementById('vMontoTotal').value);
        
        const cuotas = [];
        document.querySelectorAll('.cuota-checkbox:checked').forEach(chk => {
            const cuotaId = parseInt(chk.dataset.cuotaId);
            const input = document.querySelector(`.cuota-monto-input[data-cuota-id="${cuotaId}"]`);
            const monto = parseFloat(input.value);
            if (monto > 0) {
                cuotas.push({ cuota_id: cuotaId, monto });
            }
        });

        const payload = {
            tipo_pago: tipoPago,
            monto_total: montoTotal,
            cuotas: cuotas
        };

        if (tipoPago === 'Parcial') {
            payload.efectivo = parseFloat(document.getElementById('vEfectivo').value);
            payload.qr = parseFloat(document.getElementById('vQr').value);
        }

        if (tipoPago === 'Qr' || tipoPago === 'Transferencia') {
            payload.cuenta_bancaria_id = document.getElementById('vCuentaBancaria').value || '';
            payload.referencia = document.getElementById('vReferencia').value || '';
        }

        this.disabled = true;
        this.innerHTML = '<i class="ri-loader-4-line"></i> Procesando...';

        fetch(`/admin/comprobantes/${verifiandoId}/verificar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(data => {
            this.disabled = false;
            this.innerHTML = '<i class="ri-check-double-line"></i> Verificar y Registrar';
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalVerificar'))?.hide();
                toast('success', data.mensaje);
                if (data.data && data.data.pago_id) {
                    document.getElementById('recibo-mensaje-exito').textContent = data.mensaje;
                    document.getElementById('recibo-numero').textContent = data.data.recibo || '—';
                    document.getElementById('recibo-total').textContent = 'Bs. ' + parseFloat(data.data.total_pagado || 0).toFixed(2);
                    document.getElementById('btn-imprimir-recibo').onclick = () =>
                        window.open('/admin/estudiantes/recibo/' + data.data.pago_id + '/pdf?inline=1', '_blank');
                    document.getElementById('btn-descargar-recibo').onclick = () =>
                        window.open('/admin/estudiantes/recibo/' + data.data.pago_id + '/pdf', '_blank');
                    const modalRecibo = new bootstrap.Modal(document.getElementById('modalReciboPago'));
                    modalRecibo.show();
                    document.getElementById('modalReciboPago').addEventListener('hidden.bs.modal', function() {
                        window.location.reload();
                    }, { once: true });
                } else {
                    setTimeout(() => location.reload(), 1400);
                }
            } else {
                toast('error', data.message || 'Error al verificar');
            }
        })
        .catch(() => {
            this.disabled = false;
            this.innerHTML = '<i class="ri-check-double-line"></i> Verificar y Registrar';
            toast('error', 'Error de conexión');
        });
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-btn-simple');
        if (!btn) return;

        const id = btn.dataset.id;
        const accion = btn.dataset.accion;
        btn.disabled = true;

        fetch(`/admin/comprobantes/${id}/${accion}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { toast('success', data.mensaje); setTimeout(() => location.reload(), 1200); }
            else { toast('error', data.message || 'Error'); btn.disabled = false; }
        })
        .catch(() => { toast('error', 'Error de conexión'); btn.disabled = false; });
    });
})();
</script>
@endpush
