@extends('layouts.master')
@section('title') Movimientos de Caja @endsection

@section('css')
<style>
:root {
    --mv-primary: #198754;
    --mv-primary-dark: #145c32;
    --mv-orange: #fc7b04;
    --mv-orange-dark: #c25e00;
    --mv-orange-soft: rgba(252,123,4,0.10);
    --mv-surface: #f8fafc;
    --mv-surface-2: #ffffff;
    --mv-border: #e2e8f0;
    --mv-text: #1e293b;
    --mv-muted: #64748b;
}
[data-bs-theme="dark"] {
    --mv-surface: #1e1e2d;
    --mv-surface-2: #212229;
    --mv-border: rgba(255,255,255,0.08);
    --mv-text: #e9ecef;
    --mv-muted: #9ca3af;
    --mv-orange-soft: rgba(252,123,4,0.16);
}

@keyframes mvFade { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
.mv-page { animation: mvFade .45s ease-out; }

/* ── Hero ── */
.mv-hero {
    position:relative; overflow:hidden;
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:18px;
    padding:26px 32px; margin-bottom:22px;
    background:linear-gradient(135deg,#0a3d22 0%, #145c32 45%, #198754 100%);
    border-radius:22px; color:#fff;
    box-shadow:0 10px 32px rgba(20,92,50,0.30);
}
.mv-hero::before {
    content:''; position:absolute; top:-40%; right:-8%;
    width:360px; height:360px; border-radius:50%;
    background:radial-gradient(circle, rgba(40,167,69,0.28) 0%, transparent 70%);
    pointer-events:none;
}
.mv-hero-content { position:relative; z-index:1; max-width:60%; min-width:260px; }
.mv-hero-breadcrumb {
    display:inline-flex; align-items:center; gap:6px;
    font-size:0.74rem; font-weight:600; opacity:0.78; margin-bottom:6px;
}
.mv-hero-breadcrumb a { color:#a7f3d0; text-decoration:none; }
.mv-hero-breadcrumb a:hover { color:#fff; }
.mv-hero h1 { margin:0; font-size:1.55rem; font-weight:800; display:flex; align-items:center; gap:12px; letter-spacing:-0.02em; }
.mv-hero h1 i { color:#a7f3d0; }
.mv-hero-sub { margin:6px 0 0; opacity:0.85; font-size:0.88rem; display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
.mv-hero-sub i { color:#a7f3d0; }
.mv-hero-actions { position:relative; z-index:1; display:flex; gap:10px; flex-wrap:wrap; }
.mv-back-btn {
    display:inline-flex; align-items:center; gap:7px;
    padding:9px 16px; border-radius:11px;
    background:rgba(255,255,255,0.14); backdrop-filter:blur(8px);
    border:1px solid rgba(255,255,255,0.20); color:#fff;
    font-weight:700; font-size:0.82rem; text-decoration:none;
    transition:all .25s;
}
.mv-back-btn:hover { background:#fff; color:var(--mv-primary-dark); transform:translateX(-2px); }

/* ── Stats ── */
.mv-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:24px; }
@media (max-width:992px) { .mv-stats { grid-template-columns:repeat(2,1fr); } }
@media (max-width:480px) { .mv-stats { grid-template-columns:1fr; } }
.mv-stat {
    background:var(--mv-surface-2); border:1px solid var(--mv-border);
    border-radius:14px; padding:16px 18px;
    display:flex; align-items:center; gap:12px;
    box-shadow:0 1px 3px rgba(0,0,0,0.04);
    position:relative; overflow:hidden; transition:all .25s;
}
.mv-stat::before {
    content:''; position:absolute; top:0; left:0; right:0; height:3px;
    background:linear-gradient(90deg, var(--accent), transparent);
}
.mv-stat:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(0,0,0,0.07); }
.mv-stat.ingresos { --accent: #28a745; }
.mv-stat.egresos  { --accent: #dc3545; }
.mv-stat.balance  { --accent: var(--mv-orange); }
.mv-stat.cant     { --accent: #0d6efd; }
.mv-stat-icon {
    width:44px; height:44px; border-radius:12px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:1.2rem;
}
.mv-stat.ingresos .mv-stat-icon { background:rgba(40,167,69,0.10); color:#28a745; }
.mv-stat.egresos  .mv-stat-icon { background:rgba(220,53,69,0.10); color:#dc3545; }
.mv-stat.balance  .mv-stat-icon { background:var(--mv-orange-soft); color:var(--mv-orange-dark); }
.mv-stat.cant     .mv-stat-icon { background:rgba(13,110,253,0.10); color:#0d6efd; }
.mv-stat-lbl { font-size:0.68rem; font-weight:700; color:var(--mv-muted); text-transform:uppercase; letter-spacing:0.05em; }
.mv-stat-val { font-size:1.25rem; font-weight:800; color:var(--mv-text); font-family:'Outfit',sans-serif; line-height:1.1; margin-top:2px; }
.mv-stat-val.pos { color:#15803d; }
.mv-stat-val.neg { color:#b91c1c; }

/* ── Layout ── */
.mv-layout { display:grid; grid-template-columns:340px 1fr; gap:18px; align-items:start; }
@media (max-width:992px) { .mv-layout { grid-template-columns:1fr; } }

/* ── Card de información ── */
.mv-info-card {
    background:var(--mv-surface-2); border:1px solid var(--mv-border);
    border-radius:16px; overflow:hidden;
    box-shadow:0 1px 3px rgba(0,0,0,0.04);
    position:sticky; top:16px;
}
.mv-info-head {
    padding:18px 20px;
    background:linear-gradient(135deg,#fff3e0 0%, #fed7aa 100%);
    border-bottom:1px solid var(--mv-border);
}
.mv-info-icon-wrap {
    width:54px; height:54px; border-radius:14px;
    background:linear-gradient(135deg,var(--mv-orange),var(--mv-orange-dark));
    color:#fff; display:flex; align-items:center; justify-content:center;
    font-size:1.55rem; margin-bottom:10px;
    box-shadow:0 6px 16px rgba(252,123,4,0.30);
}
.mv-info-title { font-size:1.05rem; font-weight:800; color:#7c2d12; margin:0; letter-spacing:-0.01em; }
.mv-info-resp { font-size:0.78rem; color:#92400e; margin-top:3px; display:inline-flex; align-items:center; gap:4px; }
.mv-info-body { padding:6px 0; }
.mv-info-row {
    display:flex; justify-content:space-between; align-items:center;
    padding:0.65rem 1.25rem;
    border-bottom:1px solid rgba(0,0,0,0.04);
}
.mv-info-row:last-child { border-bottom:none; }
.mv-info-label {
    display:inline-flex; align-items:center; gap:5px;
    font-size:0.72rem; font-weight:700; color:var(--mv-muted);
    text-transform:uppercase; letter-spacing:0.04em;
}
.mv-info-label i { color:var(--mv-orange); font-size:0.82rem; }
.mv-info-value { font-size:0.86rem; font-weight:700; color:var(--mv-text); text-align:right; }
.mv-info-value.pos { color:#15803d; font-family:'Outfit',sans-serif; }
.mv-pill-estado {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 9px; border-radius:20px;
    font-size:0.66rem; font-weight:800; letter-spacing:0.3px; text-transform:uppercase;
}
.mv-pill-estado.abierta { background:rgba(40,167,69,0.12); color:var(--mv-primary); border:1px solid rgba(40,167,69,0.22); }
.mv-pill-estado.cerrada { background:rgba(108,117,125,0.12); color:#6c757d; border:1px solid rgba(108,117,125,0.22); }

/* ── Card lista ── */
.mv-list-card {
    background:var(--mv-surface-2); border:1px solid var(--mv-border);
    border-radius:16px; overflow:hidden;
    box-shadow:0 1px 3px rgba(0,0,0,0.04);
}
.mv-list-head {
    display:flex; align-items:center; justify-content:space-between; gap:12px;
    padding:14px 18px; border-bottom:1px solid var(--mv-border);
    background:linear-gradient(180deg,var(--mv-surface-2) 0%, var(--mv-surface) 100%);
    flex-wrap:wrap;
}
.mv-list-title { display:flex; align-items:center; gap:8px; font-size:0.92rem; font-weight:700; color:var(--mv-text); margin:0; }
.mv-list-title i { color:var(--mv-orange); }
.mv-chip-count {
    background:var(--mv-orange-soft); color:var(--mv-orange-dark);
    padding:3px 10px; border-radius:20px; font-size:0.7rem; font-weight:700;
}
.mv-tabs {
    display:inline-flex; gap:4px; padding:4px;
    background:var(--mv-surface); border:1px solid var(--mv-border); border-radius:10px;
}
.mv-tab {
    border:none; background:transparent; cursor:pointer;
    padding:5px 12px; border-radius:7px;
    font-size:0.74rem; font-weight:700; color:var(--mv-muted);
    display:inline-flex; align-items:center; gap:5px;
    transition:all .2s;
}
.mv-tab:hover { color:var(--mv-text); }
.mv-tab.active.ingresos { background:#28a745; color:#fff; }
.mv-tab.active.egresos  { background:#dc3545; color:#fff; }
.mv-tab.active.all      { background:var(--mv-orange); color:#fff; }

/* ── Items de movimiento ── */
.mv-list-body { padding:12px; }
.mv-item {
    display:grid;
    grid-template-columns:42px 1fr auto;
    gap:14px; align-items:center;
    padding:12px 14px; border-radius:12px;
    background:var(--mv-surface-2); border:1px solid var(--mv-border);
    margin-bottom:8px;
    transition:all .25s;
    position:relative; overflow:hidden;
}
.mv-item::before {
    content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
}
.mv-item.ingreso::before { background:linear-gradient(180deg,#28a745,#15803d); }
.mv-item.egreso::before  { background:linear-gradient(180deg,#dc3545,#b91c1c); }
.mv-item:hover {
    transform:translateY(-1px);
    box-shadow:0 8px 22px rgba(0,0,0,0.06);
    border-color:rgba(252,123,4,0.20);
}
.mv-item-icon {
    width:42px; height:42px; border-radius:50%;
    display:flex; align-items:center; justify-content:center; font-size:1.05rem;
    flex-shrink:0;
}
.mv-item.ingreso .mv-item-icon { background:rgba(40,167,69,0.12); color:#15803d; }
.mv-item.egreso  .mv-item-icon { background:rgba(220,53,69,0.12); color:#b91c1c; }
.mv-item-body { min-width:0; }
.mv-item-top {
    display:flex; align-items:center; gap:8px; flex-wrap:wrap;
    margin-bottom:4px;
}
.mv-item-pill {
    display:inline-flex; align-items:center; gap:4px;
    padding:2px 9px; border-radius:20px;
    font-size:0.62rem; font-weight:800; letter-spacing:0.3px; text-transform:uppercase;
    border:1px solid transparent;
}
.mv-item-pill.ingreso { background:rgba(40,167,69,0.12); color:#15803d; border-color:rgba(40,167,69,0.22); }
.mv-item-pill.egreso  { background:rgba(220,53,69,0.12); color:#b91c1c; border-color:rgba(220,53,69,0.22); }
.mv-item-recibo {
    display:inline-flex; align-items:center; gap:4px;
    font-size:0.7rem; font-weight:700;
    color:var(--mv-orange-dark); background:var(--mv-orange-soft);
    border:1px solid rgba(252,123,4,0.18);
    padding:2px 8px; border-radius:6px;
    text-decoration:none;
}
.mv-item-recibo:hover { background:var(--mv-orange); color:#fff; }
.mv-item-desc {
    font-size:0.84rem; color:var(--mv-text); font-weight:500;
    line-height:1.35;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
}
.mv-item-desc.empty { color:var(--mv-muted); font-style:italic; }
.mv-item-meta {
    display:flex; gap:10px; flex-wrap:wrap; margin-top:5px;
    font-size:0.7rem; color:var(--mv-muted);
}
.mv-item-meta span { display:inline-flex; align-items:center; gap:3px; }
.mv-item-meta i { color:var(--mv-orange); font-size:0.78rem; }

.mv-item-right { text-align:right; min-width:130px; }
.mv-item-amount {
    font-family:'Outfit',sans-serif;
    font-size:1.05rem; font-weight:800;
    white-space:nowrap;
}
.mv-item-amount.ingreso { color:#15803d; }
.mv-item-amount.egreso  { color:#b91c1c; }
.mv-item-amount-sub {
    font-size:0.66rem; font-weight:700; color:var(--mv-muted);
    text-transform:uppercase; letter-spacing:0.04em; margin-top:1px;
}

.mv-empty {
    text-align:center; padding:48px 20px; color:var(--mv-muted);
}
.mv-empty i { font-size:2.4rem; opacity:0.45; display:block; margin-bottom:8px; }

.mv-pagination { padding:14px; display:flex; justify-content:center; border-top:1px solid var(--mv-border); }
</style>
@endsection

@section('content')
@php
    \Carbon\Carbon::setLocale('es');
    $personaResp = $caja->trabajadorCargo?->trabajador?->persona;
    $nombreResp  = $personaResp
        ? trim(($personaResp->nombres ?? $personaResp->nombre ?? '').' '.($personaResp->apellido_paterno ?? '').' '.($personaResp->apellido_materno ?? ''))
        : 'Sin asignar';
    $balance = $totalIngresos - $totalEgresos;
@endphp

<div class="container-fluid py-3 mv-page">

    {{-- Hero --}}
    <div class="mv-hero">
        <div class="mv-hero-content">
            <div class="mv-hero-breadcrumb">
                <a href="{{ route('admin.cajas.index') }}"><i class="ri-arrow-left-s-line"></i> Cajas</a>
                <span style="opacity:0.5;">/</span>
                <span>Movimientos</span>
            </div>
            <h1><i class="ri-history-line"></i> {{ $caja->nombre }}</h1>
            <div class="mv-hero-sub">
                <i class="ri-user-3-line"></i> <span><strong>{{ $nombreResp ?: 'Sin asignar' }}</strong></span>
                @if($caja->fecha_apertura)
                    <span style="opacity:0.7;">·</span>
                    <i class="ri-calendar-event-line"></i>
                    <span>Apertura: {{ ucfirst($caja->fecha_apertura->translatedFormat('j \d\e F \d\e\l Y \a \l\a\s H:i')) }}</span>
                @endif
            </div>
        </div>
        <div class="mv-hero-actions">
            <a href="{{ route('admin.cajas.index') }}" class="mv-back-btn">
                <i class="ri-arrow-left-line"></i> Volver
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="mv-stats">
        <div class="mv-stat ingresos">
            <div class="mv-stat-icon"><i class="ri-arrow-up-circle-line"></i></div>
            <div>
                <div class="mv-stat-lbl">Total ingresos</div>
                <div class="mv-stat-val pos">Bs {{ number_format($totalIngresos, 2) }}</div>
            </div>
        </div>
        <div class="mv-stat egresos">
            <div class="mv-stat-icon"><i class="ri-arrow-down-circle-line"></i></div>
            <div>
                <div class="mv-stat-lbl">Total egresos</div>
                <div class="mv-stat-val neg">Bs {{ number_format($totalEgresos, 2) }}</div>
            </div>
        </div>
        <div class="mv-stat balance">
            <div class="mv-stat-icon"><i class="ri-funds-line"></i></div>
            <div>
                <div class="mv-stat-lbl">Balance neto</div>
                <div class="mv-stat-val {{ $balance >= 0 ? 'pos' : 'neg' }}">Bs {{ number_format($balance, 2) }}</div>
            </div>
        </div>
        <div class="mv-stat cant">
            <div class="mv-stat-icon"><i class="ri-swap-line"></i></div>
            <div>
                <div class="mv-stat-lbl">Movimientos</div>
                <div class="mv-stat-val">{{ $movimientos->total() }}</div>
            </div>
        </div>
    </div>

    {{-- Layout: info + lista --}}
    <div class="mv-layout">

        {{-- Info de caja --}}
        <aside class="mv-info-card">
            <div class="mv-info-head">
                <div class="mv-info-icon-wrap"><i class="ri-money-dollar-box-line"></i></div>
                <h6 class="mv-info-title">{{ $caja->nombre }}</h6>
                <div class="mv-info-resp"><i class="ri-user-3-line"></i> {{ $nombreResp ?: '—' }}</div>
            </div>
            <div class="mv-info-body">
                <div class="mv-info-row">
                    <span class="mv-info-label"><i class="ri-shield-check-line"></i> Estado</span>
                    <span class="mv-info-value">
                        @if($caja->estado === 'Abierta')
                            <span class="mv-pill-estado abierta"><i class="ri-checkbox-circle-fill"></i> Abierta</span>
                        @else
                            <span class="mv-pill-estado cerrada"><i class="ri-lock-fill"></i> Cerrada</span>
                        @endif
                    </span>
                </div>
                <div class="mv-info-row">
                    <span class="mv-info-label"><i class="ri-coins-line"></i> Monto inicial</span>
                    <span class="mv-info-value">Bs {{ number_format($caja->monto_inicial, 2) }}</span>
                </div>
                <div class="mv-info-row">
                    <span class="mv-info-label"><i class="ri-funds-line"></i> Monto actual</span>
                    <span class="mv-info-value pos">Bs {{ number_format($caja->monto_actual, 2) }}</span>
                </div>
                @if($caja->fecha_apertura)
                <div class="mv-info-row">
                    <span class="mv-info-label"><i class="ri-calendar-event-line"></i> Apertura</span>
                    <span class="mv-info-value" style="font-size:0.78rem;">
                        {{ ucfirst($caja->fecha_apertura->translatedFormat('l, j \d\e M Y')) }}
                        <div style="font-size:0.7rem;color:var(--mv-muted);font-weight:600;">{{ $caja->fecha_apertura->format('H:i') }}</div>
                    </span>
                </div>
                @endif
                @if($caja->fecha_cierre)
                <div class="mv-info-row">
                    <span class="mv-info-label"><i class="ri-lock-2-line"></i> Cierre</span>
                    <span class="mv-info-value" style="font-size:0.78rem;">
                        {{ ucfirst($caja->fecha_cierre->translatedFormat('l, j \d\e M Y')) }}
                        <div style="font-size:0.7rem;color:var(--mv-muted);font-weight:600;">{{ $caja->fecha_cierre->format('H:i') }}</div>
                    </span>
                </div>
                @endif
            </div>
        </aside>

        {{-- Lista de movimientos --}}
        <div class="mv-list-card">
            <div class="mv-list-head">
                <h6 class="mv-list-title"><i class="ri-swap-line"></i> Historial de movimientos
                    @if($movimientos->total() > 0)
                        <span class="mv-chip-count">{{ $movimientos->total() }}</span>
                    @endif
                </h6>
                @if($movimientos->total() > 0)
                <div class="mv-tabs" id="mvTabs">
                    <button type="button" class="mv-tab active all" data-filtro="todos"><i class="ri-list-check"></i> Todos</button>
                    <button type="button" class="mv-tab ingresos" data-filtro="ingreso"><i class="ri-arrow-up-line"></i> Ingresos</button>
                    <button type="button" class="mv-tab egresos" data-filtro="egreso"><i class="ri-arrow-down-line"></i> Egresos</button>
                </div>
                @endif
            </div>

            <div class="mv-list-body">
                @if($movimientos->count() > 0)
                    <div id="mvLista">
                        @foreach($movimientos as $mov)
                            @php
                                $tipoLower = strtolower((string) $mov->tipo);
                                $esIngreso = $tipoLower === 'ingreso';
                                $signo = $esIngreso ? '+' : '-';
                                $icon  = $esIngreso ? 'ri-arrow-up-line' : 'ri-arrow-down-line';
                                $reciboNum = $mov->pago?->recibo;
                            @endphp
                            <div class="mv-item {{ $tipoLower }}" data-tipo="{{ $tipoLower }}">
                                <div class="mv-item-icon"><i class="{{ $icon }}"></i></div>
                                <div class="mv-item-body">
                                    <div class="mv-item-top">
                                        <span class="mv-item-pill {{ $tipoLower }}">
                                            <i class="{{ $icon }}"></i> {{ ucfirst($tipoLower) }}
                                        </span>
                                        @if($reciboNum)
                                            <a href="{{ route('admin.estudiantes.generarReciboPdf', ['pagoId' => $mov->pago_id]) }}"
                                               target="_blank" class="mv-item-recibo" title="Ver recibo en PDF">
                                                <i class="ri-receipt-line"></i> {{ $reciboNum }}
                                            </a>
                                        @endif
                                    </div>
                                    <div class="mv-item-desc {{ $mov->descripcion ? '' : 'empty' }}">
                                        {{ $mov->descripcion ?? 'Sin descripción' }}
                                    </div>
                                    <div class="mv-item-meta">
                                        @if($mov->created_at)
                                            <span><i class="ri-calendar-line"></i> {{ ucfirst($mov->created_at->translatedFormat('l, j \d\e F \d\e\l Y')) }}</span>
                                            <span><i class="ri-time-line"></i> {{ $mov->created_at->format('H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mv-item-right">
                                    <div class="mv-item-amount {{ $tipoLower }}">{{ $signo }} Bs {{ number_format($mov->monto, 2) }}</div>
                                    <div class="mv-item-amount-sub">{{ $esIngreso ? 'Entrada' : 'Salida' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mv-empty" id="mvEmptyFilter" style="display:none;">
                        <i class="ri-filter-off-line"></i>
                        Sin movimientos del tipo seleccionado.
                    </div>

                    <div class="mv-pagination">
                        {{ $movimientos->links() }}
                    </div>
                @else
                    <div class="mv-empty">
                        <i class="ri-swap-line"></i>
                        No hay movimientos registrados para esta caja
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('#mvTabs .mv-tab');
    const items = document.querySelectorAll('#mvLista .mv-item');
    const emptyFilter = document.getElementById('mvEmptyFilter');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const filtro = this.dataset.filtro;
            let visibles = 0;
            items.forEach(it => {
                const match = filtro === 'todos' || it.dataset.tipo === filtro;
                it.style.display = match ? '' : 'none';
                if (match) visibles++;
            });
            if (emptyFilter) emptyFilter.style.display = visibles === 0 ? '' : 'none';
        });
    });
});
</script>
@endsection
