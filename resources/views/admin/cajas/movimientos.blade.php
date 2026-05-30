@extends('layouts.master')
@section('title') Movimientos de Caja @endsection

@section('css')
<style>
:root {
    --d-card: #fff;
    --d-card-border: #e2e8f0;
    --d-card-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);
    --d-muted: #64748b;
    --d-title: #1e293b;
    --d-body: #495057;
    --d-header-border: #e2e8f0;
    --d-header-bg: #f8fafc;
    --d-row-border: rgba(0,0,0,0.06);
    --d-row-hover: rgba(252,123,4,0.04);
}
[data-bs-theme="dark"] {
    --d-card: #1e2228;
    --d-card-border: rgba(255,255,255,0.08);
    --d-card-shadow: 0 1px 3px rgba(0,0,0,0.25), 0 4px 16px rgba(0,0,0,0.18);
    --d-muted: #9ca3af;
    --d-title: #f0f0f0;
    --d-body: #ced4da;
    --d-header-border: rgba(255,255,255,0.06);
    --d-header-bg: rgba(255,255,255,0.03);
    --d-row-border: rgba(255,255,255,0.06);
    --d-row-hover: rgba(252,123,4,0.06);
}

@keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
.anim-fade { animation:fadeUp 0.5s ease both; }
.delay-1 { animation-delay:0.05s; }
.delay-2 { animation-delay:0.10s; }
.delay-3 { animation-delay:0.15s; }
.delay-4 { animation-delay:0.20s; }

.mov-stat { display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border-radius:12px; border:1px solid var(--d-card-border); background:var(--d-card); box-shadow:var(--d-card-shadow); transition:all 0.3s; }
.mov-stat:hover { transform:translateY(-2px); }
.mov-stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1rem; }
.mov-stat-icon.bg-success-subtle { background:rgba(40,167,69,0.10); color:#28a745; }
.mov-stat-icon.bg-danger-subtle { background:rgba(220,53,69,0.10); color:#dc3545; }
.mov-stat-icon.bg-info-subtle { background:rgba(13,110,253,0.10); color:#0d6efd; }
.mov-stat-label { font-size:0.7rem; font-weight:600; color:var(--d-muted); text-transform:uppercase; letter-spacing:0.4px; }
.mov-stat-value { font-size:1.1rem; font-weight:800; color:var(--d-title); }

.mov-list { display:flex; flex-direction:column; gap:0.35rem; }
.mov-item {
    display:flex; align-items:center; gap:1rem;
    padding:0.75rem 1rem; border-radius:10px;
    border:1px solid var(--d-row-border);
    background:var(--d-card); transition:all 0.2s;
    animation:fadeUp 0.35s ease both;
}
.mov-item:hover { background:var(--d-row-hover); border-color:var(--d-header-border); }
.mov-item.ingreso { border-left:3px solid #28a745; }
.mov-item.egreso { border-left:3px solid #dc3545; }
.mov-item:nth-child(1) { animation-delay:0.02s; }
.mov-item:nth-child(2) { animation-delay:0.04s; }
.mov-item:nth-child(3) { animation-delay:0.06s; }
.mov-item:nth-child(4) { animation-delay:0.08s; }
.mov-item:nth-child(5) { animation-delay:0.10s; }
.mov-item-icon {
    width:36px; height:36px; border-radius:50%;
    display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.9rem;
}
.mov-item.ingreso .mov-item-icon { background:rgba(40,167,69,0.12); color:#28a745; }
.mov-item.egreso .mov-item-icon { background:rgba(220,53,69,0.12); color:#dc3545; }
.mov-item-body { flex:1; min-width:0; }
.mov-item-title { font-size:0.85rem; font-weight:600; color:var(--d-body); }
.mov-item-desc { font-size:0.75rem; color:var(--d-muted); margin-top:0.1rem; }
.mov-item-amount { font-size:0.95rem; font-weight:700; white-space:nowrap; }
.mov-item-amount.ingreso { color:#28a745; }
.mov-item-amount.egreso { color:#dc3545; }
.mov-item-date { font-size:0.7rem; color:var(--d-muted); white-space:nowrap; }

.info-card {
    background:var(--d-card); border:1px solid var(--d-card-border);
    border-radius:14px; overflow:hidden; box-shadow:var(--d-card-shadow);
}
.info-card-header {
    display:flex; align-items:center; gap:0.6rem;
    padding:0.9rem 1.25rem;
    border-bottom:1px solid var(--d-header-border);
    background:var(--d-header-bg);
}
.info-card-header h6 { margin:0; font-weight:700; font-size:0.82rem; color:var(--d-title); }
.info-card-header i { font-size:0.95rem; color:#fc7b04; }
.info-card-body { padding:0.25rem 0; }
.info-row { display:flex; justify-content:space-between; align-items:center; padding:0.6rem 1.25rem; border-bottom:1px solid var(--d-row-border); }
.info-row:last-child { border-bottom:none; }
.info-label { font-size:0.78rem; font-weight:600; color:var(--d-muted); }
.info-value { font-size:0.88rem; font-weight:600; color:var(--d-body); text-align:right; }
</style>
@endsection

@section('content')
<!-- Hero -->
<div class="dept-page-header anim-fade delay-1">
    <div class="container-fluid">
        <div class="dph-inner">
            <div class="dph-left">
                <div class="dph-icon-wrap" style="background:linear-gradient(135deg,#198754 0%,#28a745 100%);box-shadow:0 4px 12px rgba(25,135,84,0.25);">
                    <i class="ri-history-line"></i>
                </div>
                <div class="dph-text-block">
                    <h1 class="dph-title">Movimientos de Caja</h1>
                    <p class="dph-desc">{{ $caja->nombre }} &middot; <strong>{{ $caja->trabajadorCargo?->trabajador?->persona?->nombre ?? 'Sin asignar' }} {{ $caja->trabajadorCargo?->trabajador?->persona?->apellido_paterno ?? '' }}</strong></p>
                    <ol class="dph-breadcrumb">
                        <li><i class="ri-home-4-line"></i> Finanzas</li>
                        <li class="dph-sep"><i class="ri-arrow-right-s-line"></i></li>
                        <li><a href="{{ route('admin.cajas.index') }}" style="color:inherit;text-decoration:none;">Cajas</a></li>
                        <li class="dph-sep"><i class="ri-arrow-right-s-line"></i></li>
                        <li class="active">Movimientos</li>
                    </ol>
                </div>
            </div>
            <div class="dph-right">
                <a href="{{ route('admin.cajas.index') }}" class="back-link">
                    <i class="ri-arrow-left-line"></i> Volver a Cajas
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <!-- Stats -->
    <div class="row g-3 mb-4 anim-fade delay-2">
        <div class="col-md-4">
            <div class="mov-stat">
                <div class="mov-stat-icon bg-success-subtle"><i class="ri-arrow-up-line"></i></div>
                <div>
                    <div class="mov-stat-label">Total Ingresos</div>
                    <div class="mov-stat-value" style="color:#28a745;">Bs. {{ number_format($totalIngresos, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mov-stat">
                <div class="mov-stat-icon bg-danger-subtle"><i class="ri-arrow-down-line"></i></div>
                <div>
                    <div class="mov-stat-label">Total Egresos</div>
                    <div class="mov-stat-value" style="color:#dc3545;">Bs. {{ number_format($totalEgresos, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mov-stat">
                <div class="mov-stat-icon bg-info-subtle"><i class="ri-funds-line"></i></div>
                <div>
                    <div class="mov-stat-label">Balance</div>
                    <div class="mov-stat-value" style="color:{{ ($totalIngresos - $totalEgresos) >= 0 ? '#28a745' : '#dc3545' }};">
                        Bs. {{ number_format($totalIngresos - $totalEgresos, 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Info Caja -->
        <div class="col-md-4 anim-fade delay-3">
            <div class="info-card">
                <div class="info-card-header">
                    <i class="ri-money-dollar-box-line"></i>
                    <h6>Información de Caja</h6>
                </div>
                <div class="info-card-body">
                    <div class="info-row">
                        <span class="info-label">Nombre</span>
                        <span class="info-value">{{ $caja->nombre }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Responsable</span>
                        <span class="info-value">{{ $caja->trabajadorCargo?->trabajador?->persona?->nombre ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Estado</span>
                        <span class="info-value">
                            @if($caja->estado === 'Abierta')
                                <span class="badge bg-success" style="font-size:0.7rem;">Abierta</span>
                            @else
                                <span class="badge bg-secondary" style="font-size:0.7rem;">Cerrada</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Monto Inicial</span>
                        <span class="info-value">Bs. {{ number_format($caja->monto_inicial, 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Monto Actual</span>
                        <span class="info-value" style="color:#198754;">Bs. {{ number_format($caja->monto_actual, 2) }}</span>
                    </div>
                    @if($caja->fecha_apertura)
                    <div class="info-row">
                        <span class="info-label">Apertura</span>
                        <span class="info-value">{{ $caja->fecha_apertura->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    @if($caja->fecha_cierre)
                    <div class="info-row">
                        <span class="info-label">Cierre</span>
                        <span class="info-value">{{ $caja->fecha_cierre->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Movimientos -->
        <div class="col-md-8 anim-fade delay-4">
            <div class="info-card">
                <div class="info-card-header">
                    <i class="ri-swap-line"></i>
                    <h6>Historial de Movimientos</h6>
                    @if($movimientos->total() > 0)
                    <span class="badge" style="margin-left:auto;background:rgba(252,123,4,0.12);color:#fc7b04;font-size:0.68rem;font-weight:700;">
                        {{ $movimientos->total() }} registro{{ $movimientos->total() !== 1 ? 's' : '' }}
                    </span>
                    @endif
                </div>
                <div class="info-card-body" style="padding:0.75rem;">
                    @if($movimientos->count() > 0)
                    <div class="mov-list">
                        @foreach($movimientos as $mov)
                        <div class="mov-item {{ strtolower($mov->tipo) }}">
                            <div class="mov-item-icon">
                                <i class="ri-{{ $mov->tipo === 'Ingreso' ? 'arrow-up' : 'arrow-down' }}-line"></i>
                            </div>
                            <div class="mov-item-body">
                                <div class="mov-item-title">
                                    <span class="badge bg-{{ $mov->tipo === 'Ingreso' ? 'success' : 'danger' }}" style="font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.3px;padding:0.2rem 0.5rem;">
                                        {{ $mov->tipo }}
                                    </span>
                                    @if($mov->pago)
                                    <span style="font-size:0.75rem;color:var(--d-muted);margin-left:0.5rem;">
                                        <i class="ri-file-list-line"></i> Pago #{{ $mov->pago_id }}
                                    </span>
                                    @endif
                                </div>
                                <div class="mov-item-desc">{{ $mov->descripcion ?? 'Sin descripción' }}</div>
                            </div>
                            <div class="text-end" style="flex-shrink:0;">
                                <div class="mov-item-amount {{ strtolower($mov->tipo) }}">
                                    {{ $mov->tipo === 'Ingreso' ? '+' : '-' }} Bs. {{ number_format($mov->monto, 2) }}
                                </div>
                                <div class="mov-item-date">{{ $mov->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $movimientos->links() }}
                    </div>
                    @else
                    <div class="text-center py-5" style="color:var(--d-muted);">
                        <i class="ri-swap-line d-block mb-2" style="font-size:2rem;opacity:0.4;"></i>
                        <p class="mb-0">No hay movimientos registrados para esta caja</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection