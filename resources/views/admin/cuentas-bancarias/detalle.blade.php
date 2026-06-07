@extends('layouts.master')
@section('title') Detalle de Cuenta Bancaria @endsection

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
    --d-thead-bg: #f8fafc;
    --d-thead-color: #64748b;
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
    --d-thead-bg: rgba(255,255,255,0.04);
    --d-thead-color: #9ca3af;
}

@keyframes fadeUp { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:translateY(0); } }
@keyframes scaleIn { from { opacity:0; transform:scale(0.92); } to { opacity:1; transform:scale(1); } }
@keyframes countUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }

.anim-fade { animation:fadeUp 0.5s ease both; }
.anim-scale { animation:scaleIn 0.45s ease both; }
.anim-count { animation:countUp 0.5s ease both; }

.delay-1 { animation-delay:0.05s; }
.delay-2 { animation-delay:0.10s; }
.delay-3 { animation-delay:0.15s; }
.delay-4 { animation-delay:0.20s; }
.delay-5 { animation-delay:0.25s; }
.delay-6 { animation-delay:0.30s; }

.acct-hero {
    background: linear-gradient(135deg, #2a1404 0%, #3d1f08 30%, #5a2e0c 60%, #743c04 100%);
    border-radius: 18px;
    padding: 2rem 2.25rem;
    margin-bottom: 1.75rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(58, 27, 4, 0.35);
}
.acct-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 80% 20%, rgba(252,123,4,0.20) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 80%, rgba(252,123,4,0.08) 0%, transparent 50%);
    pointer-events: none;
}
.acct-hero::after {
    content: '';
    position: absolute;
    top: -40%; right: -10%;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(252,123,4,0.12) 0%, transparent 70%);
    pointer-events: none;
}
.acct-hero-row { display:flex; align-items:center; justify-content:space-between; gap:1.5rem; flex-wrap:wrap; position:relative; z-index:1; }
.acct-hero-info { display:flex; align-items:center; gap:1.25rem; }
.acct-hero-icon {
    width:54px; height:54px; border-radius:14px;
    background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0.06) 100%);
    backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,0.12);
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.acct-hero-icon i { font-size:1.5rem; color:#fff; }
.acct-hero-text h1 { margin:0; font-size:1.45rem; font-weight:800; color:#fff; letter-spacing:-0.3px; }
.acct-hero-text p { margin:0.2rem 0 0; font-size:0.82rem; color:rgba(255,255,255,0.65); font-weight:500; }
.acct-hero-text p strong { color:rgba(255,255,255,0.9); }
.acct-hero-meta {
    display:flex; flex-wrap:wrap; gap:0.5rem;
    margin-top:0.6rem;
}
.acct-hero-num {
    display:inline-flex; align-items:center; gap:0.45rem;
    padding:0.4rem 0.85rem; border-radius:10px;
    background:rgba(255,255,255,0.10);
    border:1px solid rgba(255,255,255,0.16);
    color:#fff; font-family:'Inter','JetBrains Mono',monospace;
    font-size:0.85rem; font-weight:700; letter-spacing:0.6px;
    backdrop-filter:blur(8px);
}
.acct-hero-num i { font-size:0.85rem; color:rgba(255,255,255,0.65); }
.acct-hero-type {
    display:inline-flex; align-items:center; gap:0.35rem;
    padding:0.4rem 0.85rem; border-radius:10px;
    background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.12);
    color:rgba(255,255,255,0.85);
    font-size:0.75rem; font-weight:600;
}
.acct-hero-type i { font-size:0.78rem; color:rgba(255,255,255,0.6); }
.acct-hero-badge {
    display:inline-flex; align-items:center; gap:0.35rem;
    padding:0.4rem 1rem; border-radius:20px;
    font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.4px;
}
.acct-hero-badge.activa { background:rgba(40,167,69,0.18); color:#6fcf7f; border:1px solid rgba(40,167,69,0.25); }
.acct-hero-badge.inactiva { background:rgba(108,117,125,0.18); color:#adb5bd; border:1px solid rgba(108,117,125,0.25); }
.acct-hero-badge.principal { background:rgba(255,193,7,0.18); color:#ffc107; border:1px solid rgba(255,193,7,0.25); }
.acct-back-link {
    display:inline-flex; align-items:center; gap:0.4rem;
    padding:0.5rem 1rem; border-radius:10px;
    background:rgba(255,255,255,0.08); backdrop-filter:blur(8px);
    border:1px solid rgba(255,255,255,0.10);
    color:rgba(255,255,255,0.75); text-decoration:none;
    font-size:0.8rem; font-weight:600; transition:all 0.25s;
}
.acct-back-link:hover { background:rgba(255,255,255,0.14); color:#fff; transform:translateX(-2px); }

.stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.75rem; }
.stat-card {
    background:var(--d-card); border:1px solid var(--d-card-border);
    border-radius:14px; padding:1.25rem 1.5rem;
    position:relative; overflow:hidden;
    transition:all 0.3s ease; box-shadow:var(--d-card-shadow);
}
.stat-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(154,73,4,0.12); }
.stat-card::before {
    content:''; position:absolute; top:0; left:0; right:0; height:3px;
}
.stat-card.ingresos::before { background:linear-gradient(90deg,#28a745,#5dd879); }
.stat-card.egresos::before { background:linear-gradient(90deg,#dc3545,#f0747a); }
.stat-card.balance::before { background:linear-gradient(90deg,#fc7b04,#ffb347); }
.stat-card.movimientos::before { background:linear-gradient(90deg,#0d6efd,#5b9cff); }
.stat-card-icon {
    width:36px; height:36px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    margin-bottom:0.75rem; font-size:1rem;
}
.stat-card.ingresos .stat-card-icon { background:rgba(40,167,69,0.10); color:#28a745; }
.stat-card.egresos .stat-card-icon { background:rgba(220,53,69,0.10); color:#dc3545; }
.stat-card.balance .stat-card-icon { background:rgba(252,123,4,0.10); color:#fc7b04; }
.stat-card.movimientos .stat-card-icon { background:rgba(13,110,253,0.10); color:#0d6efd; }
.stat-card-label { font-size:0.72rem; font-weight:600; color:var(--d-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.35rem; }
.stat-card-value { font-size:1.5rem; font-weight:800; color:var(--d-title); letter-spacing:-0.5px; line-height:1.1; }
.stat-card-value small { font-size:0.65rem; font-weight:600; color:var(--d-muted); letter-spacing:0; }
.stat-card-sub { font-size:0.72rem; color:var(--d-muted); margin-top:0.25rem; }
.stat-card-sub i { font-size:0.65rem; }
.stat-card-sub .text-success { color:#28a745; }
.stat-card-sub .text-danger { color:#dc3545; }

.content-grid { display:grid; grid-template-columns:1.6fr 1fr; gap:1.5rem; margin-bottom:1.75rem; }

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
.info-row {
    display:flex; justify-content:space-between; align-items:center;
    padding:0.65rem 1.25rem;
    border-bottom:1px solid var(--d-row-border);
    transition:background 0.15s;
}
.info-row:last-child { border-bottom:none; }
.info-row:hover { background:var(--d-row-hover); }
.info-label { font-size:0.78rem; font-weight:600; color:var(--d-muted); }
.info-value { font-size:0.88rem; font-weight:600; color:var(--d-body); text-align:right; display:flex; align-items:center; gap:0.4rem; }

.qr-box {
    display:flex; flex-direction:column; align-items:center;
    padding:1.5rem; gap:0.75rem;
}
.qr-box img { max-width:150px; border-radius:10px; box-shadow:0 4px 16px rgba(0,0,0,0.08); }
.qr-box .qr-label { font-size:0.72rem; color:var(--d-muted); font-weight:500; display:flex; align-items:center; gap:0.35rem; }

.chart-card {
    background:var(--d-card); border:1px solid var(--d-card-border);
    border-radius:14px; overflow:hidden; box-shadow:var(--d-card-shadow);
}
.chart-card-body { padding:1.25rem; }
.chart-container { position:relative; width:100%; height:280px; }

.table-card {
    background:var(--d-card); border:1px solid var(--d-card-border);
    border-radius:14px; overflow:hidden; box-shadow:var(--d-card-shadow);
}
.mov-table { width:100%; border-collapse:collapse; }
.mov-table th {
    background:var(--d-thead-bg); color:var(--d-thead-color);
    font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.6px;
    padding:0.7rem 1.25rem; text-align:left;
    border-bottom:2px solid var(--d-header-border);
}
.mov-table td {
    padding:0.65rem 1.25rem; border-bottom:1px solid var(--d-row-border);
    font-size:0.84rem; color:var(--d-body); vertical-align:middle;
}
.mov-table tbody tr:hover td { background:var(--d-row-hover); }
.mov-table tbody tr { animation:fadeUp 0.35s ease both; }
.mov-table tbody tr:nth-child(1) { animation-delay:0.02s; }
.mov-table tbody tr:nth-child(2) { animation-delay:0.04s; }
.mov-table tbody tr:nth-child(3) { animation-delay:0.06s; }
.mov-table tbody tr:nth-child(4) { animation-delay:0.08s; }
.mov-table tbody tr:nth-child(5) { animation-delay:0.10s; }
.monto-pos { color:#198754; font-weight:700; }
.monto-neg { color:#dc3545; font-weight:700; }
.mov-empty { text-align:center; padding:2.5rem; color:var(--d-muted); }
.mov-empty i { font-size:2rem; opacity:0.4; margin-bottom:0.5rem; }

/* ── Mejoras visuales tabla de movimientos ── */
.mov-table th:first-child { padding-left:1.5rem; }
.mov-table td:first-child { padding-left:1.5rem; }
.mov-tipo-pill {
    display:inline-flex; align-items:center; gap:0.4rem;
    padding:0.3rem 0.7rem; border-radius:20px;
    font-size:0.7rem; font-weight:700; letter-spacing:0.3px;
    border:1px solid transparent;
}
.mov-tipo-pill i { font-size:0.78rem; }
.mov-tipo-pill.ingreso { background:rgba(40,167,69,0.10); color:#198754; border-color:rgba(40,167,69,0.20); }
.mov-tipo-pill.egreso  { background:rgba(220,53,69,0.10); color:#dc3545; border-color:rgba(220,53,69,0.20); }
.mov-tipo-pill.otro    { background:rgba(108,117,125,0.10); color:#6c757d; border-color:rgba(108,117,125,0.20); }
.mov-monto-cell {
    display:inline-flex; align-items:baseline; gap:0.25rem;
    font-family: 'Inter', system-ui, sans-serif;
    font-weight:700;
}
.mov-monto-cell .moneda { font-size:0.7rem; font-weight:600; opacity:0.7; }
.mov-ref-chip {
    display:inline-flex; align-items:center; gap:0.3rem;
    padding:0.2rem 0.55rem; border-radius:6px;
    background:rgba(15,23,42,0.04); color:var(--d-body);
    font-size:0.74rem; font-family:'Inter',monospace; font-weight:600;
    border:1px solid var(--d-row-border);
    max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
}
[data-bs-theme="dark"] .mov-ref-chip { background:rgba(255,255,255,0.04); }

/* ── Acción "Ver" en movimientos ── */
.mov-action {
    display:inline-flex; align-items:center; justify-content:center;
    width:32px; height:32px; border:none; border-radius:8px;
    background:rgba(252,123,4,0.10); color:#c25e00;
    cursor:pointer; transition:all .2s;
}
.mov-action:hover { background:linear-gradient(135deg,#fc7b04,#c25e00); color:#fff; transform:translateY(-1px); box-shadow:0 4px 12px rgba(252,123,4,0.3); }
.mov-action:disabled, .mov-action[disabled] { opacity:0.35; cursor:not-allowed; }

/* ── Modal detalle movimiento ── */
.mov-modal .modal-content { border:none; border-radius:16px; overflow:hidden; box-shadow:0 24px 60px rgba(0,0,0,0.18); }
.mov-modal-header {
    background:linear-gradient(135deg,#5a2e0c 0%,#743c04 60%,#fc7b04 100%);
    color:#fff; padding:1.25rem 1.5rem; display:flex; align-items:center; gap:14px;
}
.mov-modal-icon {
    width:44px; height:44px; border-radius:12px; flex-shrink:0;
    background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.20);
    display:flex; align-items:center; justify-content:center; font-size:1.3rem;
}
.mov-modal-title { margin:0; font-weight:700; font-size:1.05rem; }
.mov-modal-sub { font-size:0.78rem; opacity:0.85; margin-top:2px; }
.mov-modal-close {
    margin-left:auto; width:34px; height:34px; border:none; border-radius:8px;
    background:rgba(255,255,255,0.15); color:#fff; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
}
.mov-modal-close:hover { background:rgba(255,255,255,0.25); }
.mov-recibo-doc {
    background:#fff; border:1px solid #e2e8f0; border-radius:12px;
    padding:1.25rem 1.5rem; margin:0;
}
.mov-recibo-header {
    display:flex; align-items:center; justify-content:space-between; gap:1rem;
    padding-bottom:0.85rem; margin-bottom:0.85rem;
    border-bottom:2px solid #fc7b04;
}
.mov-recibo-brand { display:flex; align-items:center; gap:10px; }
.mov-recibo-brand img { width:38px; height:38px; object-fit:contain; }
.mov-recibo-brand-name { font-size:0.78rem; font-weight:800; color:#1e293b; line-height:1.15; }
.mov-recibo-brand-sub { font-size:0.66rem; color:#64748b; }
.mov-recibo-num-wrap { text-align:right; }
.mov-recibo-num-lbl { font-size:0.7rem; color:#c25e00; font-weight:700; letter-spacing:0.5px; }
.mov-recibo-num-val {
    display:inline-block; margin-top:2px;
    font-family:'Inter',monospace; font-size:0.95rem; font-weight:800; color:#1e293b;
    background:rgba(252,123,4,0.10); border:1px solid rgba(252,123,4,0.20);
    border-radius:7px; padding:3px 10px;
}
.mov-recibo-meta {
    display:grid; grid-template-columns:repeat(3,1fr); gap:0.5rem;
    font-size:0.78rem; margin-bottom:0.9rem;
}
.mov-recibo-meta-lbl { font-size:0.65rem; color:#64748b; text-transform:uppercase; letter-spacing:0.4px; font-weight:700; }
.mov-recibo-meta-val { color:#1e293b; font-weight:600; }
.mov-recibo-info {
    background:#fafaf7; border:1px dashed #e2e8f0; border-radius:10px;
    padding:0.75rem 0.95rem; margin-bottom:0.9rem;
}
.mov-recibo-info-row { display:flex; gap:0.4rem; font-size:0.82rem; line-height:1.4; }
.mov-recibo-info-row strong { color:#1e293b; min-width:90px; }
.mov-recibo-info-row span { color:#475569; }
.mov-recibo-table { width:100%; border-collapse:collapse; font-size:0.82rem; margin-bottom:0.6rem; }
.mov-recibo-table th {
    background:#f8f5f1; color:#7b6f62; font-size:0.7rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.4px;
    padding:0.5rem 0.65rem; text-align:left; border:1px solid #e9e2d9;
}
.mov-recibo-table td { padding:0.5rem 0.65rem; border:1px solid #e9e2d9; color:#1e293b; }
.mov-recibo-table .text-end { text-align:right; }
.mov-recibo-total-row { background:#fef3c7; font-weight:800; color:#92400e; }
.mov-recibo-desc {
    font-size:0.78rem; color:#b45309;
    background:rgba(245,158,11,0.08); border:1px solid rgba(245,158,11,0.2);
    padding:6px 10px; border-radius:7px; margin-bottom:0.7rem;
}
.mov-recibo-signs { display:flex; justify-content:space-between; gap:1rem; margin-top:1.25rem; font-size:0.72rem; }
.mov-recibo-sign { flex:1; text-align:center; }
.mov-recibo-sign-line { border-top:1.5px solid #1e293b; padding-top:4px; color:#1e293b; font-weight:600; }
.mov-recibo-sign-lbl { font-size:0.62rem; color:#64748b; text-transform:uppercase; letter-spacing:0.4px; margin-top:2px; }
.mov-modal-footer {
    display:flex; gap:8px; justify-content:flex-end; padding:0.85rem 1.5rem;
    background:#f8fafc; border-top:1px solid #e2e8f0;
}
.mov-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:0.55rem 1.1rem; border-radius:10px; font-size:0.82rem; font-weight:700;
    border:none; cursor:pointer; transition:all .2s;
}
.mov-btn-primary { background:linear-gradient(135deg,#fc7b04,#c25e00); color:#fff; }
.mov-btn-primary:hover { color:#fff; transform:translateY(-1px); box-shadow:0 6px 18px rgba(252,123,4,0.35); }
.mov-btn-secondary { background:#e2e8f0; color:#475569; }
.mov-btn-secondary:hover { background:#cbd5e1; }
.mov-no-pago {
    text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;
}
.mov-no-pago i { font-size:2.4rem; opacity:0.4; display:block; margin-bottom:0.5rem; }

@media (max-width:992px) {
    .stats-grid { grid-template-columns:repeat(2,1fr); }
    .content-grid { grid-template-columns:1fr; }
}
@media (max-width:576px) {
    .stats-grid { grid-template-columns:1fr; }
    .acct-hero { padding:1.5rem; }
}
</style>
@endsection

@section('content')
@php \Carbon\Carbon::setLocale('es'); @endphp
<!-- Hero -->
<div class="container-fluid py-3 anim-fade delay-1">
    <div class="acct-hero">
        <div class="acct-hero-row">
            <div class="acct-hero-info">
                <div class="acct-hero-icon">
                    <i class="ri-bank-card-line"></i>
                </div>
                <div class="acct-hero-text">
                    <h1>{{ $cuentaBancaria->banco->nombre }}</h1>
                    <p>{{ $cuentaBancaria->titular ?? 'Cuenta bancaria registrada' }}</p>
                    <div class="acct-hero-meta">
                        <span class="acct-hero-num">
                            <i class="ri-bank-card-2-line"></i>
                            {{ trim(chunk_split($cuentaBancaria->numero_cuenta, 4, ' ')) }}
                        </span>
                        <span class="acct-hero-type">
                            <i class="ri-{{ $cuentaBancaria->tipo_cuenta === 'Cuenta Corriente' ? 'building-line' : 'safe-line' }}"></i>
                            {{ $cuentaBancaria->tipo_cuenta }}
                        </span>
                        <span class="acct-hero-badge {{ $cuentaBancaria->estado ? 'activa' : 'inactiva' }}">
                            <i class="ri-{{ $cuentaBancaria->estado ? 'checkbox-circle' : 'close-circle' }}-line"></i>
                            {{ $cuentaBancaria->estado ? 'Activa' : 'Inactiva' }}
                        </span>
                        @if($cuentaBancaria->es_principal)
                        <span class="acct-hero-badge principal">
                            <i class="ri-star-fill"></i> Principal
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.bancos.index') }}" class="acct-back-link">
                <i class="ri-arrow-left-line"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="container-fluid px-3">
    <!-- Stats Row -->
    <div class="stats-grid anim-fade delay-2">
        <div class="stat-card ingresos">
            <div class="stat-card-icon"><i class="ri-arrow-up-line"></i></div>
            <div class="stat-card-label">Total Ingresos</div>
            <div class="stat-card-value">{{ number_format($totalIngresos, 2) }} <small>Bs</small></div>
            <div class="stat-card-sub"><i class="ri-exchange-dollar-line"></i> Suma de ingresos registrados</div>
        </div>
        <div class="stat-card egresos">
            <div class="stat-card-icon"><i class="ri-arrow-down-line"></i></div>
            <div class="stat-card-label">Total Egresos</div>
            <div class="stat-card-value">{{ number_format($totalEgresos, 2) }} <small>Bs</small></div>
            <div class="stat-card-sub"><i class="ri-exchange-dollar-line"></i> Suma de egresos registrados</div>
        </div>
        <div class="stat-card balance">
            <div class="stat-card-icon"><i class="ri-funds-line"></i></div>
            <div class="stat-card-label">Balance Neto</div>
            <div class="stat-card-value">{{ number_format($balance, 2) }} <small>Bs</small></div>
            @if($totalMovimientos > 0)
            <div class="stat-card-sub">
                @if($balance >= 0)
                <span class="text-success"><i class="ri-arrow-up-line"></i> Saldo positivo</span>
                @else
                <span class="text-danger"><i class="ri-arrow-down-line"></i> Saldo negativo</span>
                @endif
            </div>
            @endif
        </div>
        <div class="stat-card movimientos">
            <div class="stat-card-icon"><i class="ri-swap-line"></i></div>
            <div class="stat-card-label">Movimientos</div>
            <div class="stat-card-value">{{ $totalMovimientos }}</div>
            <div class="stat-card-sub">
                <i class="ri-calendar-line"></i>
                @if($ultimoMovimiento)
                Último: {{ $ultimoMovimiento->created_at ? ucfirst($ultimoMovimiento->created_at->translatedFormat('l, j \d\e F \d\e\l Y')) : '—' }}
                @else
                Sin movimientos
                @endif
            </div>
        </div>
    </div>

    <!-- Chart + Info Grid -->
    <div class="content-grid anim-fade delay-3">
        <!-- Chart -->
        <div class="chart-card">
            <div class="info-card-header">
                <i class="ri-bar-chart-2-line"></i>
                <h6>Flujo Diario (últimos 30 días)</h6>
            </div>
            <div class="chart-card-body">
                <div class="chart-container">
                    <canvas id="chartFlujo"></canvas>
                </div>
            </div>
        </div>

        <!-- Right Info Panel -->
        <div>
            <div class="info-card mb-3">
                <div class="info-card-header">
                    <i class="ri-information-line"></i>
                    <h6>Información de la Cuenta</h6>
                </div>
                <div class="info-card-body">
                    <div class="info-row">
                        <span class="info-label">Banco</span>
                        <span class="info-value">{{ $cuentaBancaria->banco->nombre }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Número</span>
                        <span class="info-value">{{ $cuentaBancaria->numero_cuenta }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tipo</span>
                        <span class="info-value">
                            <span class="badge" style="background:{{ $cuentaBancaria->tipo_cuenta === 'Cuenta Corriente' ? 'rgba(13,110,253,0.12)' : 'rgba(25,135,84,0.12)' }};color:{{ $cuentaBancaria->tipo_cuenta === 'Cuenta Corriente' ? '#0d6efd' : '#198754' }};font-size:0.7rem;font-weight:600;">
                                {{ $cuentaBancaria->tipo_cuenta }}
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Estado</span>
                        <span class="info-value">
                            @if($cuentaBancaria->estado)
                                <span class="badge bg-success" style="font-size:0.7rem;">Activa</span>
                            @else
                                <span class="badge bg-secondary" style="font-size:0.7rem;">Inactiva</span>
                            @endif
                            @if($cuentaBancaria->es_principal)
                                <span class="badge bg-warning text-dark" style="font-size:0.7rem;"><i class="ri-star-fill"></i> Principal</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            @if($cuentaBancaria->titular)
            <div class="info-card mb-3">
                <div class="info-card-header">
                    <i class="ri-user-line"></i>
                    <h6>Titular</h6>
                </div>
                <div class="info-card-body">
                    <div class="info-row">
                        <span class="info-label">Nombre</span>
                        <span class="info-value">{{ $cuentaBancaria->titular }}</span>
                    </div>
                    @if($cuentaBancaria->ci_titular)
                    <div class="info-row">
                        <span class="info-label">CI / NIT</span>
                        <span class="info-value">{{ $cuentaBancaria->ci_titular }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <div class="info-card mb-3">
                <div class="info-card-header">
                    <i class="ri-qr-code-line"></i>
                    <h6>Código QR</h6>
                    <button type="button" class="btn-close" style="margin-left:auto;position:relative;z-index:1;filter:none;opacity:0.65;font-size:0.75rem;width:auto;height:auto;padding:0.25rem 0.5rem;background:rgba(252,123,4,0.10);border-radius:6px;color:#fc7b04;cursor:pointer;" id="btnEditarQr" title="Editar QR">
                        <i class="ri-edit-line" style="font-size:0.85rem;"></i>
                    </button>
                </div>
                <div class="info-card-body qr-box" id="qrContainer">
                    @if($cuentaBancaria->imagen_qr)
                    <img src="{{ asset('storage/' . $cuentaBancaria->imagen_qr) }}" alt="QR" id="qrImagen">
                    <span class="qr-label" id="qrVence">
                        <i class="ri-calendar-line"></i>
                        Vence: {{ $cuentaBancaria->fecha_vencimiento_qr ? $cuentaBancaria->fecha_vencimiento_qr->format('d/m/Y') : 'Sin fecha' }}
                    </span>
                    @else
                    <div style="text-align:center;padding:0.75rem 0;color:var(--d-muted);">
                        <i class="ri-qr-code-line d-block mb-2" style="font-size:2.5rem;opacity:0.3;"></i>
                        <p class="mb-0" style="font-size:0.8rem;">Sin código QR registrado</p>
                        <button type="button" class="btn btn-modal-submit btn-sm mt-2" id="btnSubirQr" style="padding:0.35rem 1rem;font-size:0.75rem;">
                            <i class="ri-upload-line"></i> Subir QR
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <i class="ri-calendar-line"></i>
                    <h6>Auditoría</h6>
                </div>
                <div class="info-card-body">
                    <div class="info-row">
                        <span class="info-label">Creada</span>
                        <span class="info-value">{{ $cuentaBancaria->created_at ? ucfirst($cuentaBancaria->created_at->translatedFormat('l, j \d\e F \d\e\l Y')) : '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Actualizada</span>
                        <span class="info-value">{{ $cuentaBancaria->updated_at ? ucfirst($cuentaBancaria->updated_at->translatedFormat('l, j \d\e F \d\e\l Y')) : '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL QR ===================== -->
    <div class="modal fade" id="modalQr" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-qr-code-line"></i> Gestionar Código QR
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formQr" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div style="background:rgba(154,73,4,0.04);border:1px solid rgba(154,73,4,0.10);border-radius:12px;padding:1.25rem;">
                            @if($cuentaBancaria->imagen_qr)
                            <div class="text-center mb-3" id="qrPreviewContainer">
                                <img src="{{ asset('storage/' . $cuentaBancaria->imagen_qr) }}" id="qrPreview" style="max-width:130px;border-radius:8px;box-shadow:0 3px 12px rgba(0,0,0,0.08);">
                            </div>
                            @endif
                            <div class="mb-3">
                                <label for="imagenQrInput" class="form-label">
                                    <i class="ri-image-line" style="color:#fc7b04;"></i>
                                    Imagen QR
                                </label>
                                <input type="file" class="form-control" id="imagenQrInput" accept="image/jpeg,image/png,image/jpg">
                                <div style="font-size:0.7rem;color:var(--d-muted);margin-top:0.25rem;">JPEG, PNG o JPG. Máx 2MB.</div>
                            </div>
                            <div class="mb-2">
                                <label for="fechaVenceQr" class="form-label">
                                    <i class="ri-calendar-line" style="color:#fc7b04;"></i>
                                    Fecha de Vencimiento
                                </label>
                                <input type="date" class="form-control" id="fechaVenceQr"
                                       value="{{ $cuentaBancaria->fecha_vencimiento_qr ? $cuentaBancaria->fecha_vencimiento_qr->format('Y-m-d') : '' }}">
                            </div>
                            @if($cuentaBancaria->imagen_qr)
                            <div class="form-check mt-3">
                                <input type="checkbox" class="form-check-input" id="eliminarQrCheck">
                                <label class="form-check-label" for="eliminarQrCheck" style="color:#dc3545;font-size:0.82rem;">
                                    <i class="ri-delete-bin-line"></i> Eliminar QR actual
                                </label>
                            </div>
                            @endif
                        </div>
                        <div class="field-feedback mt-2" id="fbQr"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-modal-submit" id="btnGuardarQr">
                            <i class="ri-save-line"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Movimientos Table -->
    <div class="table-card anim-fade delay-4" style="margin-bottom:1.75rem;">
        <div class="info-card-header">
            <i class="ri-swap-line"></i>
            <h6>Movimientos Recientes</h6>
            @if($totalMovimientos > 0)
            <span class="badge" style="margin-left:auto;background:rgba(252,123,4,0.12);color:#fc7b04;font-size:0.68rem;font-weight:700;">
                {{ $totalMovimientos }} registro{{ $totalMovimientos !== 1 ? 's' : '' }}
            </span>
            @endif
        </div>
        <div>
            @if($movimientos->count() > 0)
            <div class="table-responsive">
                <table class="mov-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Referencia</th>
                            <th>Descripción</th>
                            <th style="text-align:center;width:80px;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientos as $mov)
                            @php
                                $tipoLower = strtolower((string) $mov->tipo);
                                $esIngreso = $tipoLower === 'ingreso';
                                $esEgreso  = $tipoLower === 'egreso';
                                $tipoCls   = $esIngreso ? 'ingreso' : ($esEgreso ? 'egreso' : 'otro');
                                $tipoIcon  = $esIngreso ? 'ri-arrow-up-circle-line' : ($esEgreso ? 'ri-arrow-down-circle-line' : 'ri-exchange-line');
                                $signo     = $esIngreso ? '+' : ($esEgreso ? '-' : '');
                                $montoCls  = $esIngreso ? 'monto-pos' : ($esEgreso ? 'monto-neg' : '');
                            @endphp
                        <tr>
                            <td style="white-space:nowrap;">
                                <div style="display:flex;flex-direction:column;line-height:1.25;">
                                    @if($mov->created_at)
                                        <span style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--d-muted);">{{ ucfirst($mov->created_at->translatedFormat('l')) }}</span>
                                        <span style="font-weight:600;color:var(--d-title);font-size:0.85rem;">{{ $mov->created_at->translatedFormat('j \d\e F \d\e\l Y') }}</span>
                                        <span style="font-size:0.7rem;color:var(--d-muted);">{{ $mov->created_at->format('H:i') }}</span>
                                    @else
                                        <span style="font-weight:600;color:var(--d-title);">—</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="mov-tipo-pill {{ $tipoCls }}">
                                    <i class="{{ $tipoIcon }}"></i> {{ ucfirst($tipoLower ?: $mov->tipo) }}
                                </span>
                            </td>
                            <td>
                                <span class="mov-monto-cell {{ $montoCls }}">
                                    {{ $signo }}{{ number_format($mov->monto, 2) }} <span class="moneda">Bs</span>
                                </span>
                            </td>
                            <td>
                                @if($mov->referencia)
                                    <span class="mov-ref-chip" title="{{ $mov->referencia }}">
                                        <i class="ri-hashtag" style="opacity:0.55;"></i>{{ $mov->referencia }}
                                    </span>
                                @else
                                    <span style="color:var(--d-muted);font-size:0.8rem;">—</span>
                                @endif
                            </td>
                            <td style="color:var(--d-body);font-size:0.82rem;">{{ $mov->descripcion ?? '—' }}</td>
                            <td style="text-align:center;">
                                @php
                                    $movPagoData = null;
                                    if ($mov->pago) {
                                        $pg = $mov->pago;
                                        $estNombre = '—';
                                        $programa  = '—';
                                        $plan      = '—';
                                        foreach ($pg->pagosCuotas as $pcm) {
                                            if ($pcm->cuota && $pcm->cuota->inscripcion) {
                                                $ins = $pcm->cuota->inscripcion;
                                                if ($ins->estudiante && $ins->estudiante->persona) {
                                                    $pp = $ins->estudiante->persona;
                                                    $estNombre = trim(($pp->nombres ?? '').' '.($pp->apellido_paterno ?? '').' '.($pp->apellido_materno ?? '')) ?: '—';
                                                }
                                                if ($ins->ofertaAcademica) {
                                                    $programa = $ins->ofertaAcademica->programa->nombre
                                                        ?? ($ins->ofertaAcademica->posgrado->nombre ?? ($ins->ofertaAcademica->nombre ?? '—'));
                                                }
                                                $plan = $ins->planesPago->nombre ?? '—';
                                                break;
                                            }
                                        }
                                        $cobrador = '—';
                                        if ($pg->trabajadorCargo && $pg->trabajadorCargo->trabajador && $pg->trabajadorCargo->trabajador->persona) {
                                            $cp = $pg->trabajadorCargo->trabajador->persona;
                                            $cobrador = trim(($cp->nombres ?? '').' '.($cp->apellido_paterno ?? '').' '.($cp->apellido_materno ?? '')) ?: '—';
                                        }
                                        $movPagoData = [
                                            'id'          => $pg->id,
                                            'recibo'      => $pg->recibo,
                                            'fecha'       => $pg->fecha_pago ? \Carbon\Carbon::parse($pg->fecha_pago)->translatedFormat('l, j \d\e F \d\e\l Y') : '—',
                                            'metodo'      => $pg->tipo_pago,
                                            'monto'       => (float) $pg->monto_total,
                                            'descuento'   => (float) ($pg->descuento_bs ?? 0),
                                            'estudiante'  => $estNombre,
                                            'programa'    => $programa,
                                            'plan'        => $plan,
                                            'cobrador'    => $cobrador,
                                            'pdf_url'     => route('admin.estudiantes.generarReciboPdf', ['pagoId' => $pg->id]),
                                            'cuotas'      => $pg->pagosCuotas->map(fn($pcm) => [
                                                'nombre'  => $pcm->cuota->nombre ?? ('Cuota #'.$pcm->cuota_id),
                                                'n_cuota' => $pcm->cuota->n_cuota ?? null,
                                                'monto'   => (float) $pcm->monto_bs,
                                            ])->values()->all(),
                                            'detalles'    => $pg->detalles->map(fn($d) => [
                                                'tipo'  => $d->tipo_pago,
                                                'monto' => (float) $d->monto_bs,
                                            ])->values()->all(),
                                        ];
                                    }
                                @endphp
                                @if($movPagoData)
                                    <button type="button" class="mov-action btn-ver-mov"
                                        data-pago='@json($movPagoData)'
                                        title="Ver detalle del recibo">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                @else
                                    <span style="color:var(--d-muted);">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-center">
                {{ $movimientos->links() }}
            </div>
            @else
            <div class="mov-empty">
                <i class="ri-swap-line d-block"></i>
                <p class="mb-0">No hay movimientos registrados para esta cuenta</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ─── Modal Detalle Movimiento / Recibo ─── --}}
<div class="modal fade mov-modal" id="modalMovDetalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="mov-modal-header">
                <div class="mov-modal-icon"><i class="ri-receipt-line"></i></div>
                <div>
                    <h5 class="mov-modal-title">Detalle del Recibo</h5>
                    <div class="mov-modal-sub" id="movRecibo">—</div>
                </div>
                <button type="button" class="mov-modal-close" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="ri-close-line"></i>
                </button>
            </div>

            <div class="modal-body p-3" style="background:#f8fafc;">
                <div class="mov-recibo-doc">
                    <div class="mov-recibo-header">
                        <div class="mov-recibo-brand">
                            <img src="{{ asset('images/logo_secundario.png') }}" alt="Logo" onerror="this.style.display='none'">
                            <div>
                                <div class="mov-recibo-brand-name">INNOVA CIENCIA VIRTUAL</div>
                                <div class="mov-recibo-brand-sub">Educación Superior Virtual</div>
                            </div>
                        </div>
                        <div class="mov-recibo-num-wrap">
                            <div class="mov-recibo-num-lbl">COMPROBANTE</div>
                            <div class="mov-recibo-num-val" id="movReciboNum">—</div>
                        </div>
                    </div>

                    <div class="mov-recibo-meta">
                        <div>
                            <div class="mov-recibo-meta-lbl">Fecha</div>
                            <div class="mov-recibo-meta-val" id="movFecha">—</div>
                        </div>
                        <div>
                            <div class="mov-recibo-meta-lbl">Método</div>
                            <div class="mov-recibo-meta-val" id="movMetodo">—</div>
                        </div>
                        <div>
                            <div class="mov-recibo-meta-lbl">Emisor</div>
                            <div class="mov-recibo-meta-val" id="movCobrador">—</div>
                        </div>
                    </div>

                    <div class="mov-recibo-info">
                        <div class="mov-recibo-info-row"><strong>Estudiante:</strong> <span id="movEstudiante">—</span></div>
                        <div class="mov-recibo-info-row"><strong>Programa:</strong> <span id="movPrograma">—</span></div>
                        <div class="mov-recibo-info-row"><strong>Plan:</strong> <span id="movPlan">—</span></div>
                    </div>

                    <table class="mov-recibo-table">
                        <thead>
                            <tr>
                                <th style="width:36px;">#</th>
                                <th>Concepto</th>
                                <th class="text-end" style="width:130px;">Monto (Bs)</th>
                            </tr>
                        </thead>
                        <tbody id="movConceptos"></tbody>
                        <tfoot>
                            <tr class="mov-recibo-total-row">
                                <td colspan="2">TOTAL</td>
                                <td class="text-end" id="movTotal">—</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="mov-recibo-desc" id="movDescuentoBox" style="display:none;">
                        <i class="ri-discount-line"></i> Descuento aplicado: <strong id="movDescuento">—</strong>
                    </div>

                    <div id="movDetalles" style="margin-top:0.6rem;"></div>

                    <div class="mov-recibo-signs">
                        <div class="mov-recibo-sign">
                            <div class="mov-recibo-sign-line" id="movEmisor">—</div>
                            <div class="mov-recibo-sign-lbl">EMISOR</div>
                        </div>
                        <div class="mov-recibo-sign">
                            <div class="mov-recibo-sign-line" id="movDepositante">—</div>
                            <div class="mov-recibo-sign-lbl">DEPOSITANTE</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mov-modal-footer">
                <a id="movDescargarPdf" href="#" target="_blank" class="mov-btn mov-btn-primary">
                    <i class="ri-printer-line"></i> Imprimir recibo
                </a>
                <button type="button" class="mov-btn mov-btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/libs/chart.js/chart.umd.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';

    const chartColors = {
        ingresos: { bg: isDark ? 'rgba(40,167,69,0.18)' : 'rgba(40,167,69,0.12)', border: '#28a745', point: '#28a745' },
        egresos:  { bg: isDark ? 'rgba(220,53,69,0.18)'  : 'rgba(220,53,69,0.12)',  border: '#dc3545', point: '#dc3545' },
        grid:     isDark ? 'rgba(255,255,255,0.05)'       : 'rgba(0,0,0,0.06)',
        text:     isDark ? '#9ca3af'                       : '#6b7280',
    };

    const labels = @json($chartLabels);
    const ingresos = @json($chartIngresos);
    const egresos = @json($chartEgresos);

    if (labels.length > 0) {
        const ctx = document.getElementById('chartFlujo').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Ingresos',
                        data: ingresos,
                        backgroundColor: chartColors.ingresos.bg,
                        borderColor: chartColors.ingresos.border,
                        borderWidth: 2,
                        borderRadius: 4,
                        borderSkipped: false,
                        pointRadius: 0,
                    },
                    {
                        label: 'Egresos',
                        data: egresos,
                        backgroundColor: chartColors.egresos.bg,
                        borderColor: chartColors.egresos.border,
                        borderWidth: 2,
                        borderRadius: 4,
                        borderSkipped: false,
                        pointRadius: 0,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: {
                            boxWidth: 10,
                            boxHeight: 10,
                            borderRadius: 3,
                            usePointStyle: true,
                            pointStyle: 'rectRounded',
                            padding: 16,
                            font: { size: 11, weight: '600' },
                            color: chartColors.text,
                        }
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#1e2228' : '#fff',
                        titleColor: isDark ? '#f0f0f0' : '#1a1a1a',
                        bodyColor: isDark ? '#ced4da' : '#3d2810',
                        borderColor: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.08)',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(ctx) {
                                return ctx.dataset.label + ': Bs ' + Number(ctx.raw).toLocaleString('es-BO', { minimumFractionDigits: 2 });
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            maxTicksLimit: 12,
                            font: { size: 10, weight: '500' },
                            color: chartColors.text,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: chartColors.grid },
                        ticks: {
                            font: { size: 10 },
                            color: chartColors.text,
                            callback: function(v) { return 'Bs ' + v.toLocaleString(); }
                        }
                    }
                }
            }
        });
    } else {
        const container = document.querySelector('.chart-container');
        if (container) {
            container.innerHTML = '<div class="mov-empty" style="padding-top:3rem;"><i class="ri-bar-chart-2-line d-block"></i><p class="mb-0">No hay datos en los últimos 30 días</p></div>';
        }
    }

    /* ─── QR MODAL ─────────────────────────────────────────── */
    const btnEditarQr = document.getElementById('btnEditarQr');
    const btnSubirQr = document.getElementById('btnSubirQr');
    const modalQr = document.getElementById('modalQr');
    const formQr = document.getElementById('formQr');
    const imagenInput = document.getElementById('imagenQrInput');
    const fechaInput = document.getElementById('fechaVenceQr');
    const eliminarCheck = document.getElementById('eliminarQrCheck');
    const qrPreview = document.getElementById('qrPreview');
    const qrPreviewContainer = document.getElementById('qrPreviewContainer');

    function openQrModal() {
        resetFieldQr();
        if (modalQr) new bootstrap.Modal(modalQr).show();
    }

    if (btnEditarQr) btnEditarQr.addEventListener('click', openQrModal);
    if (btnSubirQr) btnSubirQr.addEventListener('click', openQrModal);

    if (imagenInput) {
        imagenInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (qrPreviewContainer) qrPreviewContainer.classList.remove('d-none');
                    if (!qrPreview) {
                        const img = document.createElement('img');
                        img.id = 'qrPreview';
                        img.style.cssText = 'max-width:130px;border-radius:8px;box-shadow:0 3px 12px rgba(0,0,0,0.08);';
                        qrPreviewContainer.innerHTML = '';
                        qrPreviewContainer.appendChild(img);
                        img.src = e.target.result;
                    } else {
                        qrPreview.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
            if (eliminarCheck) eliminarCheck.checked = false;
        });
    }

    if (eliminarCheck) {
        eliminarCheck.addEventListener('change', function () {
            if (this.checked && imagenInput) {
                imagenInput.value = '';
                if (qrPreview) qrPreview.src = '';
                if (qrPreviewContainer) qrPreviewContainer.classList.add('d-none');
            }
        });
    }

    if (formQr) {
        formQr.addEventListener('submit', function (e) {
            e.preventDefault();
            const fb = document.getElementById('fbQr');
            fb.textContent = '';
            fb.className = 'field-feedback';

            const formData = new FormData();
            if (imagenInput && imagenInput.files[0]) {
                formData.append('imagen_qr', imagenInput.files[0]);
            }
            if (fechaInput) {
                formData.append('fecha_vencimiento_qr', fechaInput.value || '');
            }
            if (eliminarCheck && eliminarCheck.checked) {
                formData.append('eliminar_qr', '1');
            }
            formData.append('_token', '{{ csrf_token() }}');

            const btn = document.getElementById('btnGuardarQr');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando…';

            fetch('{{ route("admin.cuentas-bancarias.qr", $cuentaBancaria->id) }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            })
            .then(r => r.json().then(data => ({ ok: r.ok, data })))
            .then(({ ok, data }) => {
                if (ok && data.success) {
                    const m = bootstrap.Modal.getInstance(modalQr);
                    if (m) m.hide();
                    location.reload();
                } else {
                    fb.textContent = data.message || 'Error al guardar.';
                    fb.className = 'field-feedback error';
                }
            })
            .catch(() => {
                fb.textContent = 'Error de conexión. Verifique el tamaño del archivo (máx 2MB).';
                fb.className = 'field-feedback error';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-save-line"></i> Guardar';
            });
        });
    }

    function resetFieldQr() {
        const fb = document.getElementById('fbQr');
        if (fb) { fb.textContent = ''; fb.className = 'field-feedback'; }
    }

    /* ─── Modal detalle de movimiento (recibo) ─────────────────── */
    const modalMovEl = document.getElementById('modalMovDetalle');
    const modalMov = modalMovEl ? new bootstrap.Modal(modalMovEl) : null;

    function escMov(v) {
        if (v === null || v === undefined) return '—';
        return String(v).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
    }
    function fmtMov(n) {
        return Number(n || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    document.querySelectorAll('.btn-ver-mov').forEach(btn => {
        btn.addEventListener('click', function () {
            let data;
            try { data = JSON.parse(this.dataset.pago); } catch (e) { return; }
            if (!data) return;

            document.getElementById('movRecibo').textContent      = data.recibo || '—';
            document.getElementById('movReciboNum').textContent   = data.recibo || '—';
            document.getElementById('movFecha').textContent       = (data.fecha || '—').charAt(0).toUpperCase() + (data.fecha || '—').slice(1);
            document.getElementById('movMetodo').textContent      = data.metodo || '—';
            document.getElementById('movCobrador').textContent    = data.cobrador || '—';
            document.getElementById('movEstudiante').textContent  = data.estudiante || '—';
            document.getElementById('movPrograma').textContent    = data.programa || '—';
            document.getElementById('movPlan').textContent        = data.plan || '—';
            document.getElementById('movEmisor').textContent      = data.cobrador || '—';
            document.getElementById('movDepositante').textContent = data.estudiante || '—';

            // Conceptos
            const cuotas = data.cuotas || [];
            let total = 0;
            const tbody = document.getElementById('movConceptos');
            tbody.innerHTML = cuotas.length
                ? cuotas.map((c, i) => {
                    total += Number(c.monto || 0);
                    return `<tr>
                        <td style="text-align:center;font-weight:700;color:#7b6f62;">${i + 1}</td>
                        <td>${escMov(c.nombre)}${c.n_cuota ? ` <span style="color:#94a3b8;font-size:0.74rem;">(Cuota ${c.n_cuota})</span>` : ''}</td>
                        <td class="text-end" style="font-weight:700;">${fmtMov(c.monto)}</td>
                    </tr>`;
                }).join('')
                : `<tr><td colspan="3" style="text-align:center;color:#94a3b8;">Sin conceptos registrados</td></tr>`;
            document.getElementById('movTotal').textContent = fmtMov(data.monto || total);

            // Descuento
            const descBox = document.getElementById('movDescuentoBox');
            if (Number(data.descuento) > 0) {
                document.getElementById('movDescuento').textContent = 'Bs ' + fmtMov(data.descuento);
                descBox.style.display = 'block';
            } else {
                descBox.style.display = 'none';
            }

            // Detalles del método (Efectivo + QR si Parcial)
            const detalles = data.detalles || [];
            const detContainer = document.getElementById('movDetalles');
            if (detalles.length > 1) {
                detContainer.innerHTML = `
                    <div style="display:flex;gap:8px;flex-wrap:wrap;font-size:0.78rem;">
                        ${detalles.map(d => `
                            <span style="background:#f1f5f9;border:1px solid #e2e8f0;border-radius:8px;padding:5px 10px;color:#475569;">
                                <strong style="color:#1e293b;">${escMov(d.tipo)}:</strong> Bs ${fmtMov(d.monto)}
                            </span>
                        `).join('')}
                    </div>`;
            } else {
                detContainer.innerHTML = '';
            }

            // Link PDF
            const pdfLink = document.getElementById('movDescargarPdf');
            pdfLink.href = data.pdf_url || '#';

            if (modalMov) modalMov.show();
        });
    });
});
</script>
@endsection