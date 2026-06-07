@extends('layouts.master')
@section('title') Control de Pagos @endsection

@section('css')
<style>
:root {
    --cp-primary: #fc7b04;
    --cp-primary-dark: #c25e00;
    --cp-primary-light: rgba(252,123,4,0.10);
    --cp-surface: #f8fafc;
    --cp-surface-2: #ffffff;
    --cp-border: #e2e8f0;
    --cp-text: #1e293b;
    --cp-muted: #64748b;
}
[data-bs-theme="dark"] {
    --cp-surface: #1e1e2d;
    --cp-surface-2: #212229;
    --cp-border: rgba(255,255,255,0.08);
    --cp-text: #e9ecef;
    --cp-muted: #9ca3af;
    --cp-primary-light: rgba(252,123,4,0.16);
}

@keyframes cpFade { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
.cp-page { animation: cpFade .45s ease-out; }

/* ─── Hero ─── */
.cp-hero {
    position:relative; overflow:hidden;
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:18px;
    padding:28px 32px; margin-bottom:22px;
    background:linear-gradient(135deg,#2a1404 0%,#5a2e0c 45%,#c25e00 100%);
    border-radius:22px; color:#fff;
    box-shadow:0 10px 32px rgba(154,73,4,0.30);
}
.cp-hero::before {
    content:''; position:absolute; top:-40%; right:-8%;
    width:360px; height:360px; border-radius:50%;
    background:radial-gradient(circle, rgba(252,123,4,0.22) 0%, transparent 70%);
    pointer-events:none;
}
.cp-hero-content { position:relative; z-index:1; }
.cp-hero h1 { margin:0; font-size:1.55rem; font-weight:800; display:flex; align-items:center; gap:12px; letter-spacing:-0.02em; }
.cp-hero h1 i { color:#fed7aa; }
.cp-hero p { margin:6px 0 0; opacity:0.85; font-size:0.9rem; }
.cp-hero-meta {
    position:relative; z-index:1;
    display:flex; align-items:center; gap:8px;
    background:rgba(255,255,255,0.12); backdrop-filter:blur(8px);
    border:1px solid rgba(255,255,255,0.18); border-radius:12px;
    padding:8px 14px; color:#fff; font-size:0.82rem; font-weight:600;
}
.cp-hero-meta i { color:#fed7aa; }

/* ─── Filtros ─── */
.cp-filter {
    background:var(--cp-surface-2); border:1px solid var(--cp-border);
    border-radius:14px; padding:14px 18px; margin-bottom:22px;
    box-shadow:0 1px 3px rgba(0,0,0,0.04);
}
.cp-filter-tabs {
    display:inline-flex; gap:4px; padding:4px; margin-bottom:12px;
    background:var(--cp-surface); border:1px solid var(--cp-border); border-radius:10px;
}
.cp-filter-tab {
    border:none; background:transparent; cursor:pointer;
    padding:6px 14px; border-radius:7px; font-size:0.8rem; font-weight:700;
    color:var(--cp-muted); display:inline-flex; align-items:center; gap:5px;
    transition:all .2s;
}
.cp-filter-tab:hover { color:var(--cp-primary-dark); }
.cp-filter-tab.active { background:linear-gradient(135deg,var(--cp-primary),var(--cp-primary-dark)); color:#fff; box-shadow:0 3px 10px rgba(252,123,4,0.28); }

.cp-filter-form { display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; }
.cp-filter-field { display:flex; flex-direction:column; min-width:150px; }
.cp-filter-field label {
    font-size:0.7rem; font-weight:700; color:var(--cp-muted);
    text-transform:uppercase; letter-spacing:0.04em; margin-bottom:4px;
}
.cp-filter-field input, .cp-filter-field select {
    background:var(--cp-surface) !important; border:1px solid var(--cp-border) !important;
    border-radius:9px !important; padding:.5rem .75rem !important;
    color:var(--cp-text) !important; font-size:0.85rem !important;
}
.cp-filter-field input:focus, .cp-filter-field select:focus {
    outline:none !important; border-color:var(--cp-primary) !important;
    box-shadow:0 0 0 3px var(--cp-primary-light) !important;
}
.cp-filter-btn {
    display:inline-flex; align-items:center; gap:6px;
    background:linear-gradient(135deg,var(--cp-primary),var(--cp-primary-dark));
    color:#fff; border:none; border-radius:10px;
    padding:.5rem 1.1rem; font-size:0.82rem; font-weight:700;
    cursor:pointer; transition:all .2s;
    box-shadow:0 3px 10px rgba(252,123,4,0.28);
}
.cp-filter-btn:hover { color:#fff; transform:translateY(-1px); box-shadow:0 6px 18px rgba(252,123,4,0.38); }
.cp-filter-btn-reset {
    display:inline-flex; align-items:center; gap:5px;
    background:transparent; color:var(--cp-muted); border:1px solid var(--cp-border);
    border-radius:10px; padding:.45rem .95rem; font-size:0.8rem; font-weight:600;
    cursor:pointer; transition:all .2s; text-decoration:none;
}
.cp-filter-btn-reset:hover { color:var(--cp-text); border-color:var(--cp-muted); }

/* ─── KPIs ─── */
.cp-kpis { display:grid; grid-template-columns:repeat(5,1fr); gap:14px; margin-bottom:24px; }
@media (max-width:1200px) { .cp-kpis { grid-template-columns:repeat(3,1fr); } }
@media (max-width:768px)  { .cp-kpis { grid-template-columns:repeat(2,1fr); } }
@media (max-width:480px)  { .cp-kpis { grid-template-columns:1fr; } }
.cp-kpi {
    background:var(--cp-surface-2); border:1px solid var(--cp-border);
    border-radius:14px; padding:16px 18px;
    display:flex; align-items:center; gap:12px;
    box-shadow:0 1px 3px rgba(0,0,0,0.04);
    position:relative; overflow:hidden;
    transition:all .25s;
}
.cp-kpi::before {
    content:''; position:absolute; top:0; left:0; right:0; height:3px;
    background:linear-gradient(90deg, var(--kpi-color), transparent);
}
.cp-kpi:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(0,0,0,0.08); }
.cp-kpi.total       { --kpi-color: var(--cp-primary); }
.cp-kpi.pagos       { --kpi-color: #6366f1; }
.cp-kpi.efectivo    { --kpi-color: #0ea5e9; }
.cp-kpi.qr          { --kpi-color: #14b8a6; }
.cp-kpi.transfer    { --kpi-color: #6366f1; }
.cp-kpi-icon {
    width:42px; height:42px; border-radius:12px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:1.25rem;
}
.cp-kpi.total .cp-kpi-icon    { background:var(--cp-primary-light); color:var(--cp-primary-dark); }
.cp-kpi.pagos .cp-kpi-icon    { background:rgba(99,102,241,0.10); color:#4f46e5; }
.cp-kpi.efectivo .cp-kpi-icon { background:rgba(14,165,233,0.10); color:#0284c7; }
.cp-kpi.qr .cp-kpi-icon       { background:rgba(20,184,166,0.10); color:#0d9488; }
.cp-kpi.transfer .cp-kpi-icon { background:rgba(99,102,241,0.10); color:#4f46e5; }
.cp-kpi-lbl { font-size:0.68rem; font-weight:700; color:var(--cp-muted); text-transform:uppercase; letter-spacing:0.05em; }
.cp-kpi-val { font-size:1.2rem; font-weight:800; color:var(--cp-text); font-family:'Outfit',sans-serif; line-height:1.15; margin-top:2px; }
.cp-kpi-sub { font-size:0.7rem; color:var(--cp-muted); margin-top:2px; }

/* ─── Cards ─── */
.cp-card {
    background:var(--cp-surface-2); border:1px solid var(--cp-border);
    border-radius:16px; overflow:hidden;
    box-shadow:0 1px 3px rgba(0,0,0,0.04);
    margin-bottom:22px;
}
.cp-card-head {
    display:flex; align-items:center; justify-content:space-between; gap:12px;
    padding:14px 18px; border-bottom:1px solid var(--cp-border);
    background:linear-gradient(180deg,var(--cp-surface-2) 0%, var(--cp-surface) 100%);
}
.cp-card-title { display:flex; align-items:center; gap:8px; font-size:0.92rem; font-weight:700; color:var(--cp-text); margin:0; }
.cp-card-title i { color:var(--cp-primary); }
.cp-card-sub { font-size:0.74rem; color:var(--cp-muted); }
.cp-card-body { padding:16px 18px; }

.cp-charts-row { display:grid; grid-template-columns:1.6fr 1fr; gap:18px; margin-bottom:22px; }
@media (max-width:992px) { .cp-charts-row { grid-template-columns:1fr; } }
.cp-chart-container { position:relative; height:300px; }
.cp-chart-container-sm { position:relative; height:300px; }

/* ─── Tablas ─── */
.cp-table { width:100%; border-collapse:separate; border-spacing:0; }
.cp-table th {
    background:linear-gradient(180deg,#fafbfc,#f1f5f9);
    color:#475569; font-size:0.64rem; font-weight:800;
    text-transform:uppercase; letter-spacing:0.06em;
    padding:0.7rem 1rem; text-align:left;
    border-bottom:2px solid var(--cp-border);
}
.cp-table td {
    padding:0.75rem 1rem; border-bottom:1px solid #f1f5f9;
    font-size:0.85rem; color:var(--cp-text);
    vertical-align:middle;
}
.cp-table tbody tr:hover { background:rgba(252,123,4,0.03); }
.cp-table tbody tr:last-child td { border-bottom:none; }
.cp-table .text-end { text-align:right; }
.cp-table .text-center { text-align:center; }

.cp-trab-cell { display:flex; align-items:center; gap:10px; }
.cp-trab-avatar {
    width:36px; height:36px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,var(--cp-primary),var(--cp-primary-dark));
    color:#fff; display:flex; align-items:center; justify-content:center;
    font-weight:800; font-size:0.85rem;
}
.cp-trab-name { font-weight:700; color:var(--cp-text); font-size:0.88rem; }
.cp-trab-cargo { font-size:0.72rem; color:var(--cp-muted); }

.cp-pill {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 10px; border-radius:20px;
    font-size:0.7rem; font-weight:700;
    border:1px solid transparent;
}
.cp-pill.efectivo { background:rgba(14,165,233,0.10); color:#0284c7; border-color:rgba(14,165,233,0.20); }
.cp-pill.qr       { background:rgba(20,184,166,0.10); color:#0d9488; border-color:rgba(20,184,166,0.20); }
.cp-pill.transfer { background:rgba(99,102,241,0.10); color:#4f46e5; border-color:rgba(99,102,241,0.20); }
.cp-pill.parcial  { background:rgba(245,158,11,0.10); color:#b45309; border-color:rgba(245,158,11,0.20); }
.cp-pill.otro     { background:rgba(108,117,125,0.10); color:#6c757d; border-color:rgba(108,117,125,0.20); }

.cp-recibo-num {
    display:inline-flex; align-items:center; gap:5px;
    font-family:'Inter',monospace; font-size:0.78rem; font-weight:800;
    color:#1e293b; background:rgba(252,123,4,0.10); border:1px solid rgba(252,123,4,0.18);
    border-radius:7px; padding:3px 8px;
}
.cp-monto-pos { font-family:'Outfit',sans-serif; font-weight:800; color:#15803d; }
.cp-fecha-cell { display:flex; flex-direction:column; line-height:1.2; }
.cp-fecha-cell .dia { font-size:0.62rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:var(--cp-muted); }
.cp-fecha-cell .full { font-size:0.82rem; font-weight:600; color:var(--cp-text); }

.cp-act-btn {
    display:inline-flex; align-items:center; justify-content:center;
    width:32px; height:32px; border-radius:9px; border:none;
    background:var(--cp-primary-light); color:var(--cp-primary-dark);
    cursor:pointer; transition:all .2s;
}
.cp-act-btn:hover { background:linear-gradient(135deg,var(--cp-primary),var(--cp-primary-dark)); color:#fff; transform:translateY(-1px); box-shadow:0 4px 12px rgba(252,123,4,0.30); }

/* ─── Sub-tabla detalle de pago ─── */
.cp-row-expanded { background:#fdfaf5 !important; }
.cp-detalles-wrap {
    padding:14px 22px;
    background:linear-gradient(180deg,#fdfaf5,#fff);
    border-top:1px dashed rgba(252,123,4,0.18);
    border-bottom:1px dashed rgba(252,123,4,0.18);
}
.cp-detalles-grid {
    display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:12px;
}
.cp-detalle-card {
    background:#fff; border:1px solid var(--cp-border); border-radius:12px;
    padding:12px 14px; box-shadow:0 1px 3px rgba(0,0,0,0.04);
}
.cp-detalle-head {
    display:flex; align-items:center; gap:10px; margin-bottom:8px;
    padding-bottom:8px; border-bottom:1px dashed var(--cp-border);
}
.cp-detalle-icon {
    width:36px; height:36px; border-radius:10px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:1.1rem;
}
.cp-detalle-icon.efectivo { background:rgba(14,165,233,0.10); color:#0284c7; }
.cp-detalle-icon.qr       { background:rgba(20,184,166,0.10); color:#0d9488; }
.cp-detalle-icon.transfer { background:rgba(99,102,241,0.10); color:#4f46e5; }
.cp-detalle-icon.deposito { background:rgba(217,119,6,0.10); color:#b45309; }
.cp-detalle-icon.otro     { background:rgba(108,117,125,0.10); color:#6c757d; }
.cp-detalle-tipo { font-size:0.78rem; font-weight:800; color:var(--cp-text); letter-spacing:0.02em; }
.cp-detalle-monto { font-size:0.95rem; font-weight:800; color:var(--cp-text); font-family:'Outfit',sans-serif; margin-left:auto; }
.cp-detalle-row {
    display:flex; gap:8px; align-items:flex-start;
    font-size:0.76rem; color:var(--cp-text); line-height:1.3;
    margin-top:4px;
}
.cp-detalle-row i { color:var(--cp-primary); font-size:0.82rem; margin-top:2px; }
.cp-detalle-row strong { font-size:0.64rem; text-transform:uppercase; letter-spacing:0.04em; color:var(--cp-muted); font-weight:700; }
.cp-detalle-row .v { color:var(--cp-text); font-weight:600; }

.cp-summary-bar {
    display:flex; align-items:center; gap:10px; padding:10px 14px;
    background:rgba(252,123,4,0.06); border:1px solid rgba(252,123,4,0.18);
    border-radius:10px; margin-bottom:12px;
    font-size:0.82rem; color:var(--cp-text);
}
.cp-summary-bar i { color:var(--cp-primary); }
.cp-summary-bar strong { color:var(--cp-primary-dark); font-weight:800; }

.cp-empty-table {
    text-align:center; padding:50px 20px; color:var(--cp-muted);
}
.cp-empty-table i { font-size:2.4rem; opacity:0.45; display:block; margin-bottom:8px; }
</style>
@endsection

@section('content')
@php \Carbon\Carbon::setLocale('es'); @endphp
@php
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];
    $tipoToCls = ['Efectivo' => 'efectivo', 'Qr' => 'qr', 'Transferencia' => 'transfer', 'Parcial' => 'parcial'];
    $tipoToIcon = [
        'Efectivo' => 'ri-cash-line', 'Qr' => 'ri-qr-code-line',
        'Transferencia' => 'ri-bank-line', 'Depósito' => 'ri-safe-line',
        'Cheque' => 'ri-file-paper-2-line', 'Parcial' => 'ri-pie-chart-line',
    ];
@endphp

<div class="container-fluid py-3 cp-page">

    {{-- Hero --}}
    <div class="cp-hero">
        <div class="cp-hero-content">
            <h1><i class="ri-dashboard-2-line"></i> Control de Pagos</h1>
            <p>Detalle de cobros por trabajador, oferta académica y método de pago</p>
        </div>
        <div class="cp-hero-meta">
            <i class="ri-calendar-2-line"></i>
            {{ ucfirst($inicio->translatedFormat('j \d\e F Y')) }} — {{ ucfirst($fin->translatedFormat('j \d\e F Y')) }}
        </div>
    </div>

    {{-- Filtros --}}
    <div class="cp-filter">
        <div class="cp-filter-tabs">
            <button type="button" class="cp-filter-tab {{ $modo === 'mes' ? 'active' : '' }}" data-modo="mes">
                <i class="ri-calendar-line"></i> Mes / Gestión
            </button>
            <button type="button" class="cp-filter-tab {{ $modo === 'rango' ? 'active' : '' }}" data-modo="rango">
                <i class="ri-calendar-event-line"></i> Rango de fechas
            </button>
        </div>

        <form method="GET" action="{{ route('admin.contabilidad.control-pagos') }}" class="cp-filter-form" id="cpFiltroForm">
            <input type="hidden" name="modo" id="cpModo" value="{{ $modo }}">

            {{-- Bloque mes/gestion --}}
            <div class="cp-filter-field cp-filtro-mes" style="{{ $modo === 'mes' ? '' : 'display:none;' }}">
                <label>Gestión</label>
                <select name="gestion" class="form-select">
                    @foreach($gestiones as $g)
                        <option value="{{ $g }}" {{ $g == $gestion ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="cp-filter-field cp-filtro-mes" style="{{ $modo === 'mes' ? '' : 'display:none;' }}">
                <label>Mes</label>
                <select name="mes" class="form-select">
                    @foreach($meses as $i => $nombre)
                        <option value="{{ $i }}" {{ $i == $mes ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Bloque rango --}}
            <div class="cp-filter-field cp-filtro-rango" style="{{ $modo === 'rango' ? '' : 'display:none;' }}">
                <label>Desde</label>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio', $inicio->format('Y-m-d')) }}">
            </div>
            <div class="cp-filter-field cp-filtro-rango" style="{{ $modo === 'rango' ? '' : 'display:none;' }}">
                <label>Hasta</label>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin', $fin->format('Y-m-d')) }}">
            </div>

            <button type="submit" class="cp-filter-btn">
                <i class="ri-filter-3-line"></i> Aplicar
            </button>
            <a href="{{ route('admin.contabilidad.control-pagos') }}" class="cp-filter-btn-reset">
                <i class="ri-refresh-line"></i> Limpiar
            </a>
        </form>
    </div>

    {{-- KPIs --}}
    <div class="cp-kpis">
        <div class="cp-kpi total">
            <div class="cp-kpi-icon"><i class="ri-money-dollar-circle-line"></i></div>
            <div>
                <div class="cp-kpi-lbl">Total cobrado</div>
                <div class="cp-kpi-val">Bs {{ number_format($totalGeneral, 2) }}</div>
                @if($totalDesc > 0)
                    <div class="cp-kpi-sub">Descuentos: Bs {{ number_format($totalDesc, 2) }}</div>
                @endif
            </div>
        </div>
        <div class="cp-kpi pagos">
            <div class="cp-kpi-icon"><i class="ri-receipt-line"></i></div>
            <div>
                <div class="cp-kpi-lbl">Pagos registrados</div>
                <div class="cp-kpi-val">{{ $cantidadPagos }}</div>
            </div>
        </div>
        <div class="cp-kpi efectivo">
            <div class="cp-kpi-icon"><i class="ri-cash-line"></i></div>
            <div>
                <div class="cp-kpi-lbl">Efectivo</div>
                <div class="cp-kpi-val">Bs {{ number_format($totalEfectivo, 2) }}</div>
            </div>
        </div>
        <div class="cp-kpi qr">
            <div class="cp-kpi-icon"><i class="ri-qr-code-line"></i></div>
            <div>
                <div class="cp-kpi-lbl">QR</div>
                <div class="cp-kpi-val">Bs {{ number_format($totalQr, 2) }}</div>
            </div>
        </div>
        <div class="cp-kpi transfer">
            <div class="cp-kpi-icon"><i class="ri-bank-line"></i></div>
            <div>
                <div class="cp-kpi-lbl">Transferencia</div>
                <div class="cp-kpi-val">Bs {{ number_format($totalTransferencia, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="cp-charts-row">
        <div class="cp-card">
            <div class="cp-card-head">
                <h6 class="cp-card-title"><i class="ri-line-chart-line"></i> Ingresos diarios por método</h6>
                <span class="cp-card-sub">{{ count($chartLabels) }} día(s) en el período</span>
            </div>
            <div class="cp-card-body">
                <div class="cp-chart-container">
                    <canvas id="cpChartDiario"></canvas>
                </div>
            </div>
        </div>
        <div class="cp-card">
            <div class="cp-card-head">
                <h6 class="cp-card-title"><i class="ri-pie-chart-line"></i> Distribución por método</h6>
            </div>
            <div class="cp-card-body">
                <div class="cp-chart-container-sm">
                    <canvas id="cpChartMetodo"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Por Trabajador --}}
    <div class="cp-card">
        <div class="cp-card-head">
            <h6 class="cp-card-title"><i class="ri-team-line"></i> Cobros por trabajador</h6>
            <span class="cp-card-sub">{{ count($porTrabajador) }} trabajador(es) con pagos</span>
        </div>
        <div class="cp-card-body" style="padding:0;">
            @if (count($porTrabajador) > 0)
                <div style="overflow-x:auto;">
                    <table class="cp-table">
                        <thead>
                            <tr>
                                <th>Trabajador</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Efectivo</th>
                                <th class="text-end">QR</th>
                                <th class="text-end">Transfer.</th>
                                <th class="text-end">Total cobrado</th>
                                <th>Participación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($porTrabajador as $t)
                                @php
                                    $inicial = mb_strtoupper(mb_substr($t['nombre'], 0, 1));
                                    $pct = $totalGeneral > 0 ? round(($t['total'] / $totalGeneral) * 100, 1) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="cp-trab-cell">
                                            <div class="cp-trab-avatar">{{ $inicial ?: '?' }}</div>
                                            <div>
                                                <div class="cp-trab-name">{{ $t['nombre'] }}</div>
                                                <div class="cp-trab-cargo">{{ $t['cargo'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center"><span class="cp-pill otro">{{ $t['cantidad'] }}</span></td>
                                    <td class="text-end">Bs {{ number_format($t['efectivo'], 2) }}</td>
                                    <td class="text-end">Bs {{ number_format($t['qr'], 2) }}</td>
                                    <td class="text-end">Bs {{ number_format($t['transferencia'], 2) }}</td>
                                    <td class="text-end"><span class="cp-monto-pos">Bs {{ number_format($t['total'], 2) }}</span></td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:8px;min-width:140px;">
                                            <div style="flex:1;background:#f1f5f9;border-radius:20px;height:6px;overflow:hidden;">
                                                <div style="background:linear-gradient(90deg,#fc7b04,#c25e00);height:100%;width:{{ $pct }}%;"></div>
                                            </div>
                                            <span style="font-size:.72rem;font-weight:700;color:var(--cp-text);min-width:40px;text-align:right;">{{ $pct }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="cp-empty-table">
                    <i class="ri-team-line"></i>
                    Sin pagos en el período seleccionado.
                </div>
            @endif
        </div>
    </div>

    {{-- Por Oferta --}}
    <div class="cp-card">
        <div class="cp-card-head">
            <h6 class="cp-card-title"><i class="ri-graduation-cap-line"></i> Cobros por oferta académica</h6>
            <span class="cp-card-sub">{{ count($porOferta) }} oferta(s) con ingresos</span>
        </div>
        <div class="cp-card-body" style="padding:0;">
            @if (count($porOferta) > 0)
                <div style="overflow-x:auto;">
                    <table class="cp-table">
                        <thead>
                            <tr>
                                <th>Oferta académica</th>
                                <th class="text-center">Estudiantes</th>
                                <th class="text-center">Pagos</th>
                                <th class="text-end">Total cobrado</th>
                                <th>Participación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($porOferta as $o)
                                @php
                                    $pct = $totalGeneral > 0 ? round(($o['total'] / $totalGeneral) * 100, 1) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:10px;">
                                            <div style="width:34px;height:34px;border-radius:10px;background:rgba(252,123,4,0.10);color:var(--cp-primary-dark);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                <i class="ri-book-2-line"></i>
                                            </div>
                                            <div style="font-weight:700;color:var(--cp-text);">{{ $o['nombre'] }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center"><span class="cp-pill efectivo">{{ $o['estudiantes_count'] }}</span></td>
                                    <td class="text-center"><span class="cp-pill otro">{{ $o['cantidad'] }}</span></td>
                                    <td class="text-end"><span class="cp-monto-pos">Bs {{ number_format($o['total'], 2) }}</span></td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:8px;min-width:140px;">
                                            <div style="flex:1;background:#f1f5f9;border-radius:20px;height:6px;overflow:hidden;">
                                                <div style="background:linear-gradient(90deg,#0d9488,#0f766e);height:100%;width:{{ $pct }}%;"></div>
                                            </div>
                                            <span style="font-size:.72rem;font-weight:700;color:var(--cp-text);min-width:40px;text-align:right;">{{ $pct }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="cp-empty-table">
                    <i class="ri-graduation-cap-line"></i>
                    Sin pagos en el período seleccionado.
                </div>
            @endif
        </div>
    </div>

    {{-- Detalle de pagos --}}
    <div class="cp-card">
        <div class="cp-card-head">
            <h6 class="cp-card-title"><i class="ri-list-check-3"></i> Detalle de pagos</h6>
            <span class="cp-card-sub">{{ count($detallePagos) }} pago(s) — clic en el ojo para ver caja/banco/referencia</span>
        </div>
        <div class="cp-card-body" style="padding:0;">
            @if (count($detallePagos) > 0)
                <div style="overflow-x:auto;">
                    <table class="cp-table">
                        <thead>
                            <tr>
                                <th>Recibo</th>
                                <th>Fecha</th>
                                <th>Estudiante</th>
                                <th>Oferta</th>
                                <th>Cobrador</th>
                                <th>Método</th>
                                <th class="text-end">Monto</th>
                                <th class="text-center">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detallePagos as $pago)
                                @php
                                    $fechaCarbon = \Carbon\Carbon::parse($pago['fecha']);
                                    $clsTipo = $tipoToCls[$pago['tipo_pago']] ?? 'otro';
                                @endphp
                                <tr class="cp-fila" data-pago-id="{{ $pago['id'] }}">
                                    <td><span class="cp-recibo-num"><i class="ri-receipt-line"></i> {{ $pago['recibo'] }}</span></td>
                                    <td>
                                        <div class="cp-fecha-cell">
                                            <span class="dia">{{ ucfirst($fechaCarbon->translatedFormat('l')) }}</span>
                                            <span class="full">{{ $fechaCarbon->translatedFormat('j \d\e F \d\e\l Y') }}</span>
                                        </div>
                                    </td>
                                    <td><span style="font-weight:600;color:var(--cp-text);">{{ $pago['estudiante'] }}</span></td>
                                    <td><span style="font-size:.8rem;color:var(--cp-muted);">{{ $pago['oferta'] }}</span></td>
                                    <td><span style="font-size:.82rem;">{{ $pago['cobrador'] }}</span></td>
                                    <td><span class="cp-pill {{ $clsTipo }}">{{ $pago['tipo_pago'] }}</span></td>
                                    <td class="text-end"><span class="cp-monto-pos">Bs {{ number_format($pago['monto'], 2) }}</span></td>
                                    <td class="text-center">
                                        <button type="button" class="cp-act-btn btn-cp-toggle" data-pago-id="{{ $pago['id'] }}" title="Ver detalle">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="cp-detalle-row-tr" id="cp-detalle-{{ $pago['id'] }}" style="display:none;">
                                    <td colspan="8" style="padding:0;background:#fdfaf5;">
                                        <div class="cp-detalles-wrap">
                                            <div class="cp-summary-bar">
                                                <i class="ri-information-line"></i>
                                                <span>Detalle de medios de pago — total: <strong>Bs {{ number_format($pago['monto'], 2) }}</strong>@if($pago['descuento'] > 0) · descuento: <strong>Bs {{ number_format($pago['descuento'], 2) }}</strong>@endif</span>
                                                <a href="{{ $pago['pdf_url'] }}" target="_blank" class="cp-filter-btn" style="margin-left:auto;padding:.35rem .8rem;font-size:.74rem;">
                                                    <i class="ri-printer-line"></i> Imprimir recibo
                                                </a>
                                            </div>
                                            @if (count($pago['detalles']) > 0)
                                                <div class="cp-detalles-grid">
                                                    @foreach ($pago['detalles'] as $det)
                                                        @php
                                                            $detCls = match ($det['tipo']) {
                                                                'Efectivo'      => 'efectivo',
                                                                'Qr'            => 'qr',
                                                                'Transferencia' => 'transfer',
                                                                'Depósito'      => 'deposito',
                                                                default         => 'otro',
                                                            };
                                                            $detIcon = $tipoToIcon[$det['tipo']] ?? 'ri-exchange-line';
                                                        @endphp
                                                        <div class="cp-detalle-card">
                                                            <div class="cp-detalle-head">
                                                                <div class="cp-detalle-icon {{ $detCls }}"><i class="{{ $detIcon }}"></i></div>
                                                                <span class="cp-detalle-tipo">{{ $det['tipo'] }}</span>
                                                                <span class="cp-detalle-monto">Bs {{ number_format($det['monto'], 2) }}</span>
                                                            </div>

                                                            @if ($det['tipo'] === 'Efectivo')
                                                                @if ($det['caja'])
                                                                    <div class="cp-detalle-row">
                                                                        <i class="ri-safe-2-line"></i>
                                                                        <div>
                                                                            <strong>Caja chica</strong>
                                                                            <div class="v">{{ $det['caja']['nombre'] }}</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="cp-detalle-row">
                                                                        <i class="ri-user-3-line"></i>
                                                                        <div>
                                                                            <strong>Encargado/a</strong>
                                                                            <div class="v">{{ $det['caja']['encargado'] ?? '—' }}</div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="cp-detalle-row"><i class="ri-information-line"></i> <span style="color:var(--cp-muted);font-style:italic;">Sin caja registrada</span></div>
                                                                @endif
                                                            @elseif (in_array($det['tipo'], ['Qr', 'Transferencia', 'Depósito']))
                                                                @if ($det['cuenta'])
                                                                    <div class="cp-detalle-row">
                                                                        <i class="ri-bank-line"></i>
                                                                        <div>
                                                                            <strong>Banco</strong>
                                                                            <div class="v">{{ $det['cuenta']['banco'] }}</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="cp-detalle-row">
                                                                        <i class="ri-bank-card-line"></i>
                                                                        <div>
                                                                            <strong>Cuenta</strong>
                                                                            <div class="v" style="font-family:'Inter',monospace;">
                                                                                {{ $det['cuenta']['numero'] }}
                                                                                @if ($det['cuenta']['tipo'])
                                                                                    <span style="opacity:.7;font-weight:500;"> · {{ $det['cuenta']['tipo'] }}</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="cp-detalle-row"><i class="ri-information-line"></i> <span style="color:var(--cp-muted);font-style:italic;">Sin cuenta bancaria</span></div>
                                                                @endif
                                                                @if ($det['referencia'])
                                                                    <div class="cp-detalle-row">
                                                                        <i class="ri-hashtag"></i>
                                                                        <div>
                                                                            <strong>Referencia</strong>
                                                                            <div class="v" style="font-family:'Inter',monospace;word-break:break-all;">{{ $det['referencia'] }}</div>
                                                                        </div>
                                                                    </div>
                                                                @elseif ($det['tipo'] === 'Transferencia')
                                                                    <div class="cp-detalle-row"><i class="ri-information-line"></i> <span style="color:var(--cp-muted);font-style:italic;">Sin número de referencia</span></div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="cp-empty-table" style="padding:18px;">
                                                    <i class="ri-information-line"></i>
                                                    Sin detalles de medios de pago registrados.
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="cp-empty-table">
                    <i class="ri-receipt-line"></i>
                    Sin pagos registrados en este período.
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/libs/chart.js/chart.umd.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Toggle filtros ──
    const tabs = document.querySelectorAll('.cp-filter-tab');
    const modoInput = document.getElementById('cpModo');
    const camposMes = document.querySelectorAll('.cp-filtro-mes');
    const camposRango = document.querySelectorAll('.cp-filtro-rango');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const modo = this.dataset.modo;
            modoInput.value = modo;
            camposMes.forEach(c => c.style.display = modo === 'mes' ? '' : 'none');
            camposRango.forEach(c => c.style.display = modo === 'rango' ? '' : 'none');
        });
    });

    // ── Toggle detalles ──
    document.querySelectorAll('.btn-cp-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.pagoId;
            const fila = document.getElementById('cp-detalle-' + id);
            const filaPrincipal = document.querySelector('.cp-fila[data-pago-id="' + id + '"]');
            if (!fila) return;
            const visible = fila.style.display !== 'none';
            fila.style.display = visible ? 'none' : '';
            filaPrincipal?.classList.toggle('cp-row-expanded', !visible);
            this.querySelector('i').className = visible ? 'ri-eye-line' : 'ri-eye-off-line';
        });
    });

    // ── Charts ──
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const colorText = isDark ? '#9ca3af' : '#64748b';
    const colorGrid = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

    const labels  = @json($chartLabels);
    const efectivo = @json($chartEfectivo);
    const qr       = @json($chartQr);
    const transfer = @json($chartTransferencia);

    // Gráfico diario (líneas apiladas)
    const ctx1 = document.getElementById('cpChartDiario');
    if (ctx1 && labels.length > 0) {
        new Chart(ctx1.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Efectivo',
                        data: efectivo,
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(2,132,199,0.10)',
                        borderWidth: 2, fill: true, tension: 0.35, pointRadius: 0,
                    },
                    {
                        label: 'QR',
                        data: qr,
                        borderColor: '#0d9488',
                        backgroundColor: 'rgba(13,148,136,0.10)',
                        borderWidth: 2, fill: true, tension: 0.35, pointRadius: 0,
                    },
                    {
                        label: 'Transferencia',
                        data: transfer,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79,70,229,0.10)',
                        borderWidth: 2, fill: true, tension: 0.35, pointRadius: 0,
                    },
                ],
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { position: 'top', align: 'end', labels: { boxWidth: 10, boxHeight: 10, usePointStyle: true, pointStyle: 'rectRounded', padding: 12, color: colorText, font: { size: 11, weight: '600' } } },
                    tooltip: {
                        backgroundColor: isDark ? '#1e2228' : '#fff',
                        titleColor: isDark ? '#f0f0f0' : '#1a1a1a',
                        bodyColor: isDark ? '#ced4da' : '#3d2810',
                        borderColor: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.08)',
                        borderWidth: 1, padding: 12, cornerRadius: 8,
                        callbacks: { label: c => c.dataset.label + ': Bs ' + Number(c.raw).toLocaleString('es-BO', { minimumFractionDigits: 2 }) }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: colorText, font: { size: 10 }, maxTicksLimit: 14 } },
                    y: { beginAtZero: true, grid: { color: colorGrid }, ticks: { color: colorText, font: { size: 10 }, callback: v => 'Bs ' + v.toLocaleString() } }
                }
            }
        });
    } else if (ctx1) {
        ctx1.parentElement.innerHTML = '<div class="cp-empty-table"><i class="ri-line-chart-line"></i>Sin datos para graficar.</div>';
    }

    // Gráfico doughnut por método
    const totEf = @json($totalEfectivo);
    const totQr = @json($totalQr);
    const totTr = @json($totalTransferencia);
    const totOt = @json($totalOtros);
    const ctx2 = document.getElementById('cpChartMetodo');
    const totalMetodos = totEf + totQr + totTr + totOt;
    if (ctx2 && totalMetodos > 0) {
        new Chart(ctx2.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Efectivo', 'QR', 'Transferencia', 'Otros'],
                datasets: [{
                    data: [totEf, totQr, totTr, totOt],
                    backgroundColor: ['#0ea5e9', '#14b8a6', '#6366f1', '#94a3b8'],
                    borderWidth: 3,
                    borderColor: isDark ? '#212229' : '#fff',
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: colorText, padding: 12, usePointStyle: true, pointStyle: 'rectRounded', boxWidth: 10, boxHeight: 10, font: { size: 11, weight: '600' } } },
                    tooltip: {
                        backgroundColor: isDark ? '#1e2228' : '#fff',
                        titleColor: isDark ? '#f0f0f0' : '#1a1a1a',
                        bodyColor: isDark ? '#ced4da' : '#3d2810',
                        borderColor: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.08)',
                        borderWidth: 1, padding: 12, cornerRadius: 8,
                        callbacks: {
                            label: function(c) {
                                const total = c.dataset.data.reduce((a,b) => a+b, 0);
                                const pct = total > 0 ? Math.round((c.raw / total) * 1000) / 10 : 0;
                                return c.label + ': Bs ' + Number(c.raw).toLocaleString('es-BO', { minimumFractionDigits: 2 }) + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    } else if (ctx2) {
        ctx2.parentElement.innerHTML = '<div class="cp-empty-table"><i class="ri-pie-chart-line"></i>Sin datos para graficar.</div>';
    }
});
</script>
@endsection
