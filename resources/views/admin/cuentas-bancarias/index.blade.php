@extends('layouts.master')
@section('title', 'Cuentas Bancarias')

@section('css')
<style>
:root {
    --cb-primary: #fc7b04;
    --cb-primary-dark: #c25e00;
    --cb-primary-light: rgba(252,123,4,0.10);
    --cb-surface: #f8fafc;
    --cb-surface-2: #ffffff;
    --cb-border: #e2e8f0;
    --cb-text: #1e293b;
    --cb-muted: #64748b;
}
[data-bs-theme="dark"] {
    --cb-surface: #1e1e2d;
    --cb-surface-2: #212229;
    --cb-border: rgba(255,255,255,0.08);
    --cb-text: #e9ecef;
    --cb-muted: #9ca3af;
    --cb-primary-light: rgba(252,123,4,0.16);
}

@keyframes cbFadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
.cb-page { animation: cbFadeUp .45s ease-out; }

/* ─── Hero ─── */
.cb-hero {
    position:relative; overflow:hidden;
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:18px;
    padding:28px 32px; margin-bottom:24px;
    background:linear-gradient(135deg,#2a1404 0%,#5a2e0c 45%,#c25e00 100%);
    border-radius:22px; color:#fff;
    box-shadow:0 10px 36px rgba(154,73,4,0.32);
}
.cb-hero::before {
    content:''; position:absolute; top:-40%; right:-8%;
    width:360px; height:360px; border-radius:50%;
    background:radial-gradient(circle, rgba(252,123,4,0.22) 0%, transparent 70%);
    pointer-events:none;
}
.cb-hero-content { position:relative; z-index:1; }
.cb-hero h1 { margin:0; font-size:1.6rem; font-weight:800; display:flex; align-items:center; gap:12px; letter-spacing:-0.02em; }
.cb-hero h1 i { color:#fed7aa; font-size:1.45rem; }
.cb-hero p { margin:6px 0 0; opacity:0.85; font-size:0.9rem; }
.cb-hero-actions { position:relative; z-index:1; display:flex; gap:10px; flex-wrap:wrap; }
.cb-btn-new {
    display:inline-flex; align-items:center; gap:8px;
    padding:10px 18px; border-radius:12px;
    background:rgba(255,255,255,0.15); backdrop-filter:blur(8px);
    border:1px solid rgba(255,255,255,0.20); color:#fff;
    font-weight:700; font-size:0.85rem; cursor:pointer;
    transition:all .25s;
}
.cb-btn-new:hover { background:#fff; color:var(--cb-primary-dark); transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,0.18); }

/* ─── Stats ─── */
.cb-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:24px; }
.cb-stat {
    background:var(--cb-surface-2); border:1px solid var(--cb-border);
    border-radius:14px; padding:16px 18px;
    display:flex; align-items:center; gap:12px;
    box-shadow:0 1px 3px rgba(0,0,0,0.04);
    transition:all .25s;
}
.cb-stat:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(154,73,4,0.10); border-color:rgba(252,123,4,0.25); }
.cb-stat-icon {
    width:44px; height:44px; border-radius:12px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:1.25rem;
}
.cb-stat-icon.total    { background:var(--cb-primary-light); color:var(--cb-primary-dark); }
.cb-stat-icon.activas  { background:rgba(40,167,69,0.12); color:#198754; }
.cb-stat-icon.principal{ background:rgba(255,193,7,0.14); color:#d97706; }
.cb-stat-icon.qr       { background:rgba(13,110,253,0.12); color:#0d6efd; }
.cb-stat-lbl { font-size:0.7rem; color:var(--cb-muted); text-transform:uppercase; letter-spacing:0.05em; font-weight:700; }
.cb-stat-val { font-size:1.4rem; font-weight:800; color:var(--cb-text); line-height:1.1; margin-top:2px; font-family:'Outfit', sans-serif; }
@media (max-width:992px) { .cb-stats { grid-template-columns:repeat(2,1fr); } }
@media (max-width:576px) { .cb-stats { grid-template-columns:1fr; } }

/* ─── Filter bar ─── */
.cb-filter {
    background:var(--cb-surface-2); border:1px solid var(--cb-border);
    border-radius:14px; padding:12px 16px; margin-bottom:20px;
    display:flex; align-items:center; gap:10px; flex-wrap:wrap;
}
.cb-filter-search { position:relative; flex:1; min-width:240px; }
.cb-filter-search i {
    position:absolute; left:12px; top:50%; transform:translateY(-50%);
    color:var(--cb-muted); font-size:1rem;
}
.cb-filter-search input {
    width:100%; padding:10px 14px 10px 38px;
    background:var(--cb-surface); border:1px solid var(--cb-border); border-radius:10px;
    color:var(--cb-text); font-size:0.88rem;
    transition:border-color .2s, box-shadow .2s;
}
.cb-filter-search input:focus {
    outline:none; border-color:var(--cb-primary);
    box-shadow:0 0 0 3px var(--cb-primary-light);
}
.cb-filter-chip {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 14px; border-radius:20px;
    background:var(--cb-surface); border:1px solid var(--cb-border);
    color:var(--cb-muted); font-size:0.78rem; font-weight:600;
    cursor:pointer; transition:all .2s;
}
.cb-filter-chip:hover { border-color:var(--cb-primary); color:var(--cb-primary-dark); }
.cb-filter-chip.active { background:linear-gradient(135deg,var(--cb-primary),var(--cb-primary-dark)); color:#fff; border-color:var(--cb-primary); }

/* ─── Card de cuenta ─── */
.cb-cards-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(340px,1fr)); gap:18px; }
.cb-account-card {
    position:relative; overflow:hidden;
    background:var(--cb-surface-2); border:1px solid var(--cb-border);
    border-radius:18px;
    transition:all .3s cubic-bezier(.4,0,.2,1);
    display:flex; flex-direction:column;
}
.cb-account-card:hover {
    transform:translateY(-4px);
    box-shadow:0 20px 40px rgba(154,73,4,0.12);
    border-color:rgba(252,123,4,0.30);
}
.cb-card-strip {
    height:6px;
    background:linear-gradient(90deg,var(--cb-primary),var(--cb-primary-dark));
}
.cb-account-card.inactiva { opacity:0.72; }
.cb-account-card.inactiva .cb-card-strip { background:linear-gradient(90deg,#94a3b8,#64748b); }
.cb-account-card.principal .cb-card-strip { background:linear-gradient(90deg,#f59e0b,#d97706); }

.cb-card-body { padding:18px 20px; flex:1; display:flex; flex-direction:column; }
.cb-card-top {
    display:flex; align-items:flex-start; gap:12px; margin-bottom:14px;
}
.cb-card-bank-icon {
    width:46px; height:46px; border-radius:12px; flex-shrink:0;
    background:linear-gradient(135deg,#fff3e0,#fed7aa);
    color:var(--cb-primary-dark);
    display:flex; align-items:center; justify-content:center; font-size:1.35rem;
    border:1px solid rgba(252,123,4,0.18);
}
.cb-card-bank-name {
    font-size:1rem; font-weight:800; color:var(--cb-text); line-height:1.2;
    letter-spacing:-0.01em;
}
.cb-card-tipo {
    display:inline-flex; align-items:center; gap:4px;
    font-size:0.7rem; color:var(--cb-muted);
    margin-top:3px; font-weight:600;
}
.cb-card-tipo i { font-size:0.78rem; }
.cb-card-badges { margin-left:auto; display:flex; flex-direction:column; gap:4px; align-items:flex-end; flex-shrink:0; }
.cb-pill {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 10px; border-radius:20px;
    font-size:0.65rem; font-weight:700; letter-spacing:0.3px; text-transform:uppercase;
    white-space:nowrap;
}
.cb-pill.activa { background:rgba(40,167,69,0.12); color:#198754; border:1px solid rgba(40,167,69,0.2); }
.cb-pill.inactiva { background:rgba(108,117,125,0.12); color:#6c757d; border:1px solid rgba(108,117,125,0.2); }
.cb-pill.principal { background:linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e; border:1px solid rgba(217,119,6,0.25); }
.cb-pill i { font-size:0.7rem; }

.cb-card-numero {
    background:linear-gradient(135deg,#1e293b 0%,#0f172a 100%);
    color:#fff; border-radius:12px;
    padding:14px 16px; margin:6px 0 12px;
    position:relative; overflow:hidden;
}
.cb-card-numero::before {
    content:''; position:absolute; top:-50%; right:-20%;
    width:200px; height:200px; border-radius:50%;
    background:radial-gradient(circle,rgba(252,123,4,0.2),transparent 70%);
}
.cb-card-numero-lbl {
    font-size:0.62rem; opacity:0.55; text-transform:uppercase; letter-spacing:0.08em; font-weight:700;
    position:relative; z-index:1;
}
.cb-card-numero-val {
    font-family:'Inter','JetBrains Mono',monospace;
    font-size:1.05rem; font-weight:800; letter-spacing:0.12em;
    margin-top:3px; position:relative; z-index:1;
    color:#fed7aa;
}
.cb-card-chip {
    position:absolute; top:14px; right:16px; z-index:1;
    width:28px; height:20px; border-radius:4px;
    background:linear-gradient(135deg,#fed7aa,#fbbf24);
    opacity:0.7;
}

.cb-card-info {
    background:rgba(252,123,4,0.04); border:1px dashed rgba(252,123,4,0.18);
    border-radius:10px; padding:10px 12px; margin-bottom:12px;
}
.cb-card-info-row {
    display:flex; align-items:center; gap:6px;
    font-size:0.78rem; color:var(--cb-text); line-height:1.4;
}
.cb-card-info-row + .cb-card-info-row { margin-top:4px; }
.cb-card-info-row i { color:var(--cb-primary); font-size:0.85rem; width:16px; }
.cb-card-info-row strong { color:var(--cb-muted); font-weight:600; font-size:0.72rem; text-transform:uppercase; letter-spacing:0.03em; min-width:54px; }

.cb-card-extras {
    display:flex; gap:8px; margin-bottom:14px; flex-wrap:wrap;
}
.cb-extra-chip {
    display:inline-flex; align-items:center; gap:5px;
    padding:5px 10px; border-radius:8px;
    font-size:0.7rem; font-weight:600;
    background:var(--cb-surface); color:var(--cb-muted);
    border:1px solid var(--cb-border);
}
.cb-extra-chip.qr { background:rgba(13,110,253,0.08); color:#0d6efd; border-color:rgba(13,110,253,0.18); }
.cb-extra-chip i { font-size:0.78rem; }

.cb-card-actions {
    margin-top:auto; padding-top:12px;
    border-top:1px dashed var(--cb-border);
    display:flex; gap:6px; align-items:center; flex-wrap:wrap;
}
.cb-action-btn {
    width:36px; height:36px; border-radius:9px; border:none;
    display:inline-flex; align-items:center; justify-content:center;
    cursor:pointer; transition:all .2s;
    font-size:0.95rem;
}
.cb-action-btn.detalle {
    flex:1; width:auto; padding:0 14px; gap:6px;
    background:linear-gradient(135deg,var(--cb-primary),var(--cb-primary-dark));
    color:#fff; font-size:0.8rem; font-weight:700;
    box-shadow:0 3px 10px rgba(252,123,4,0.28);
}
.cb-action-btn.detalle:hover {
    color:#fff; transform:translateY(-1px);
    box-shadow:0 6px 18px rgba(252,123,4,0.38);
}
.cb-action-btn.editar    { background:rgba(13,110,253,0.10); color:#0d6efd; }
.cb-action-btn.editar:hover { background:#0d6efd; color:#fff; }
.cb-action-btn.principal { background:rgba(245,158,11,0.10); color:#d97706; }
.cb-action-btn.principal:hover { background:#d97706; color:#fff; }
.cb-action-btn.toggle    { background:rgba(108,117,125,0.10); color:#6c757d; }
.cb-action-btn.toggle:hover { background:#6c757d; color:#fff; }
.cb-action-btn.eliminar  { background:rgba(220,53,69,0.10); color:#dc3545; }
.cb-action-btn.eliminar:hover { background:#dc3545; color:#fff; }
.cb-action-form { display:inline-flex; margin:0; }

/* ─── Empty ─── */
.cb-empty {
    text-align:center; padding:60px 20px;
    background:var(--cb-surface-2); border:2px dashed var(--cb-border);
    border-radius:18px;
}
.cb-empty-icon {
    width:80px; height:80px; border-radius:50%; margin:0 auto 14px;
    background:linear-gradient(135deg,var(--cb-primary-light),rgba(252,123,4,0.04));
    display:inline-flex; align-items:center; justify-content:center;
}
.cb-empty-icon i { font-size:2.4rem; color:var(--cb-primary); }
.cb-empty h5 { color:var(--cb-text); font-weight:700; margin-bottom:6px; }
.cb-empty p  { color:var(--cb-muted); margin:0 0 16px; }

/* ─── Modal moderno ─── */
.cb-modal .modal-content {
    border:none; border-radius:18px; overflow:hidden;
    box-shadow:0 24px 60px rgba(0,0,0,0.18);
}
.cb-modal-header {
    background:linear-gradient(135deg,#5a2e0c 0%,#c25e00 60%,#fc7b04 100%);
    color:#fff; padding:18px 22px;
    display:flex; align-items:center; gap:12px; border:none;
}
.cb-modal-header-icon {
    width:44px; height:44px; border-radius:12px; flex-shrink:0;
    background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.22);
    display:flex; align-items:center; justify-content:center; font-size:1.25rem;
}
.cb-modal-title { margin:0; font-weight:700; font-size:1.05rem; }
.cb-modal-sub { font-size:0.78rem; opacity:0.85; }
.cb-modal-close-btn {
    margin-left:auto; width:34px; height:34px; border:none; border-radius:9px;
    background:rgba(255,255,255,0.15); color:#fff; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
}
.cb-modal-close-btn:hover { background:rgba(255,255,255,0.25); }
.cb-modal-body { padding:1.25rem 1.5rem; background:#fafaf7; }
.cb-form-label {
    display:inline-flex; align-items:center; gap:6px;
    font-size:0.74rem; font-weight:700; color:var(--cb-text);
    text-transform:uppercase; letter-spacing:0.04em; margin-bottom:5px;
}
.cb-form-label i { color:var(--cb-primary); font-size:0.85rem; }
.cb-input {
    width:100%;
    background:#fff !important; border:1.5px solid var(--cb-border) !important;
    border-radius:10px !important; padding:.55rem .85rem !important;
    font-size:0.88rem !important; color:var(--cb-text) !important;
    transition:border-color .2s, box-shadow .2s !important;
}
.cb-input:focus { border-color:var(--cb-primary) !important; box-shadow:0 0 0 3px var(--cb-primary-light) !important; outline:none !important; }
.cb-modal-footer {
    background:#fff; border-top:1px solid var(--cb-border); padding:0.85rem 1.5rem;
    display:flex; justify-content:flex-end; gap:8px;
}
.cb-modal-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:0.55rem 1.2rem; border-radius:10px;
    font-size:0.82rem; font-weight:700; border:none; cursor:pointer;
    transition:all .2s;
}
.cb-modal-btn-cancel { background:#e2e8f0; color:#475569; }
.cb-modal-btn-cancel:hover { background:#cbd5e1; }
.cb-modal-btn-submit {
    background:linear-gradient(135deg,var(--cb-primary),var(--cb-primary-dark));
    color:#fff; box-shadow:0 4px 12px rgba(252,123,4,0.28);
}
.cb-modal-btn-submit:hover { transform:translateY(-1px); box-shadow:0 8px 22px rgba(252,123,4,0.38); }

.cb-alert {
    display:flex; align-items:center; gap:10px;
    padding:12px 16px; border-radius:12px; margin-bottom:18px;
    background:rgba(40,167,69,0.10); color:#15803d; border:1px solid rgba(40,167,69,0.22);
    font-size:0.88rem; font-weight:600;
}
</style>
@endsection

@section('content')
@php
    \Carbon\Carbon::setLocale('es');
    $totalCuentas = $cuentas->total();
    $cuentasActivas = $cuentas->getCollection()->where('estado', true)->count();
    $cuentasPrincipales = $cuentas->getCollection()->where('es_principal', true)->count();
    $cuentasConQr = $cuentas->getCollection()->whereNotNull('imagen_qr')->where('imagen_qr', '!=', '')->count();
@endphp

<div class="container-fluid py-3 cb-page">

    {{-- Hero --}}
    <div class="cb-hero">
        <div class="cb-hero-content">
            <h1><i class="ri-bank-card-2-line"></i> Cuentas Bancarias</h1>
            <p>Administra las cuentas para recibir pagos por QR, transferencias y depósitos</p>
        </div>
        <div class="cb-hero-actions">
            <button class="cb-btn-new" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta">
                <i class="ri-add-circle-line"></i> Nueva Cuenta
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="cb-alert"><i class="ri-checkbox-circle-line" style="font-size:1.1rem;"></i> {{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="cb-stats">
        <div class="cb-stat">
            <div class="cb-stat-icon total"><i class="ri-bank-card-line"></i></div>
            <div>
                <div class="cb-stat-lbl">Total cuentas</div>
                <div class="cb-stat-val">{{ $totalCuentas }}</div>
            </div>
        </div>
        <div class="cb-stat">
            <div class="cb-stat-icon activas"><i class="ri-checkbox-circle-line"></i></div>
            <div>
                <div class="cb-stat-lbl">Activas</div>
                <div class="cb-stat-val">{{ $cuentasActivas }}</div>
            </div>
        </div>
        <div class="cb-stat">
            <div class="cb-stat-icon principal"><i class="ri-star-fill"></i></div>
            <div>
                <div class="cb-stat-lbl">Principales</div>
                <div class="cb-stat-val">{{ $cuentasPrincipales }}</div>
            </div>
        </div>
        <div class="cb-stat">
            <div class="cb-stat-icon qr"><i class="ri-qr-code-line"></i></div>
            <div>
                <div class="cb-stat-lbl">Con QR</div>
                <div class="cb-stat-val">{{ $cuentasConQr }}</div>
            </div>
        </div>
    </div>

    {{-- Filtro --}}
    <div class="cb-filter">
        <div class="cb-filter-search">
            <i class="ri-search-line"></i>
            <input type="text" id="cbBuscar" placeholder="Buscar por banco, número o titular...">
        </div>
        <button type="button" class="cb-filter-chip active" data-filtro="todas">
            <i class="ri-apps-line"></i> Todas
        </button>
        <button type="button" class="cb-filter-chip" data-filtro="activas">
            <i class="ri-checkbox-circle-line"></i> Activas
        </button>
        <button type="button" class="cb-filter-chip" data-filtro="principal">
            <i class="ri-star-fill"></i> Principal
        </button>
        <button type="button" class="cb-filter-chip" data-filtro="inactivas">
            <i class="ri-eye-off-line"></i> Inactivas
        </button>
    </div>

    {{-- Grid de cuentas --}}
    @if($cuentas->count() > 0)
        <div class="cb-cards-grid" id="cbGrid">
            @foreach($cuentas as $cuenta)
                @php
                    $estadoCls = $cuenta->estado ? 'activa' : 'inactiva';
                    $cardCls = !$cuenta->estado ? 'inactiva' : ($cuenta->es_principal ? 'principal' : '');
                    $numeroFmt = trim(chunk_split($cuenta->numero_cuenta, 4, ' '));
                @endphp
                <div class="cb-account-card {{ $cardCls }}"
                     data-banco="{{ Str::lower($cuenta->banco->nombre ?? '') }}"
                     data-numero="{{ Str::lower($cuenta->numero_cuenta) }}"
                     data-titular="{{ Str::lower($cuenta->titular ?? '') }}"
                     data-estado="{{ $cuenta->estado ? 'activa' : 'inactiva' }}"
                     data-principal="{{ $cuenta->es_principal ? '1' : '0' }}">
                    <div class="cb-card-strip"></div>
                    <div class="cb-card-body">
                        <div class="cb-card-top">
                            <div class="cb-card-bank-icon"><i class="ri-bank-line"></i></div>
                            <div style="flex:1;min-width:0;">
                                <div class="cb-card-bank-name">{{ $cuenta->banco->nombre ?? '—' }}</div>
                                <div class="cb-card-tipo">
                                    <i class="ri-{{ $cuenta->tipo_cuenta === 'Cuenta Corriente' ? 'building-line' : 'safe-line' }}"></i>
                                    {{ $cuenta->tipo_cuenta }}
                                </div>
                            </div>
                            <div class="cb-card-badges">
                                <span class="cb-pill {{ $estadoCls }}">
                                    <i class="ri-{{ $cuenta->estado ? 'checkbox-circle-fill' : 'close-circle-line' }}"></i>
                                    {{ $cuenta->estado ? 'Activa' : 'Inactiva' }}
                                </span>
                                @if($cuenta->es_principal)
                                <span class="cb-pill principal">
                                    <i class="ri-star-fill"></i> Principal
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="cb-card-numero">
                            <div class="cb-card-chip"></div>
                            <div class="cb-card-numero-lbl">Número de cuenta</div>
                            <div class="cb-card-numero-val">{{ $numeroFmt }}</div>
                        </div>

                        @if($cuenta->titular || $cuenta->ci_titular)
                        <div class="cb-card-info">
                            @if($cuenta->titular)
                                <div class="cb-card-info-row">
                                    <i class="ri-user-3-line"></i>
                                    <strong>Titular</strong>
                                    <span>{{ $cuenta->titular }}</span>
                                </div>
                            @endif
                            @if($cuenta->ci_titular)
                                <div class="cb-card-info-row">
                                    <i class="ri-id-card-line"></i>
                                    <strong>CI/NIT</strong>
                                    <span>{{ $cuenta->ci_titular }}</span>
                                </div>
                            @endif
                        </div>
                        @endif

                        <div class="cb-card-extras">
                            @if($cuenta->imagen_qr)
                                <span class="cb-extra-chip qr"><i class="ri-qr-code-line"></i> QR registrado</span>
                                @if($cuenta->fecha_vencimiento_qr)
                                    <span class="cb-extra-chip">
                                        <i class="ri-calendar-line"></i>
                                        Vence {{ \Carbon\Carbon::parse($cuenta->fecha_vencimiento_qr)->format('d/m/Y') }}
                                    </span>
                                @endif
                            @else
                                <span class="cb-extra-chip"><i class="ri-qr-code-line"></i> Sin QR</span>
                            @endif
                        </div>

                        <div class="cb-card-actions">
                            <a href="{{ route('admin.cuentas-bancarias.detalle', $cuenta->id) }}" class="cb-action-btn detalle" title="Ver detalle">
                                <i class="ri-eye-line"></i> Ver detalle
                            </a>
                            <button type="button" class="cb-action-btn editar"
                                    data-bs-toggle="modal" data-bs-target="#modalEditar{{ $cuenta->id }}"
                                    title="Editar">
                                <i class="ri-edit-line"></i>
                            </button>
                            @if(!$cuenta->es_principal)
                            <a href="{{ route('admin.cuentas-bancarias.principal', $cuenta->id) }}" class="cb-action-btn principal" title="Establecer como principal">
                                <i class="ri-star-line"></i>
                            </a>
                            @endif
                            <form action="{{ route('admin.cuentas-bancarias.toggle', $cuenta->id) }}" method="POST" class="cb-action-form">
                                @csrf
                                <button type="submit" class="cb-action-btn toggle" title="{{ $cuenta->estado ? 'Desactivar' : 'Activar' }}">
                                    <i class="ri-{{ $cuenta->estado ? 'eye-off-line' : 'eye-line' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.cuentas-bancarias.destroy', $cuenta->id) }}" method="POST"
                                  class="cb-action-form" onsubmit="return confirm('¿Eliminar esta cuenta?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cb-action-btn eliminar" title="Eliminar">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="cbEmptySearch" style="display:none;" class="cb-empty mt-3">
            <div class="cb-empty-icon"><i class="ri-search-line"></i></div>
            <h5>Sin coincidencias</h5>
            <p>No se encontraron cuentas con ese criterio.</p>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $cuentas->links() }}
        </div>
    @else
        <div class="cb-empty">
            <div class="cb-empty-icon"><i class="ri-bank-line"></i></div>
            <h5>Sin cuentas bancarias</h5>
            <p>Aún no hay cuentas registradas. Crea la primera para empezar a recibir pagos.</p>
            <button class="cb-btn-new" style="background:linear-gradient(135deg,#fc7b04,#c25e00);color:#fff;border:none;" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta">
                <i class="ri-add-circle-line"></i> Crear primera cuenta
            </button>
        </div>
    @endif
</div>

{{-- ════════════════ Modal Nueva Cuenta ════════════════ --}}
<div class="modal fade cb-modal" id="modalNuevaCuenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.cuentas-bancarias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="cb-modal-header">
                    <div class="cb-modal-header-icon"><i class="ri-add-circle-line"></i></div>
                    <div>
                        <h5 class="cb-modal-title">Nueva Cuenta Bancaria</h5>
                        <div class="cb-modal-sub">Completa los datos de la cuenta</div>
                    </div>
                    <button type="button" class="cb-modal-close-btn" data-bs-dismiss="modal" aria-label="Cerrar">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="cb-modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="cb-form-label"><i class="ri-bank-line"></i> Banco *</label>
                            <select name="banco_id" class="form-select cb-input" required>
                                <option value="">Seleccionar...</option>
                                @foreach($bancos as $banco)
                                    <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="cb-form-label"><i class="ri-bank-card-2-line"></i> Tipo de cuenta *</label>
                            <select name="tipo_cuenta" class="form-select cb-input" required>
                                <option value="Cuenta Corriente">Cuenta Corriente</option>
                                <option value="Cuenta de Ahorro">Cuenta de Ahorro</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="cb-form-label"><i class="ri-hashtag"></i> Número de cuenta *</label>
                            <input type="text" name="numero_cuenta" class="form-control cb-input" required placeholder="1234567890">
                        </div>
                        <div class="col-md-7">
                            <label class="cb-form-label"><i class="ri-user-3-line"></i> Titular</label>
                            <input type="text" name="titular" class="form-control cb-input" placeholder="Nombre del titular">
                        </div>
                        <div class="col-md-5">
                            <label class="cb-form-label"><i class="ri-id-card-line"></i> CI / NIT</label>
                            <input type="text" name="ci_titular" class="form-control cb-input">
                        </div>
                        <div class="col-md-6">
                            <label class="cb-form-label"><i class="ri-qr-code-line"></i> Imagen QR</label>
                            <input type="file" name="imagen_qr" class="form-control cb-input" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="cb-form-label"><i class="ri-calendar-line"></i> Vence QR</label>
                            <input type="date" name="fecha_vencimiento_qr" class="form-control cb-input">
                        </div>
                        <div class="col-12">
                            <div class="form-check" style="padding-left:1.6rem;">
                                <input type="checkbox" name="es_principal" class="form-check-input" id="esPrincipal" style="margin-top:0.2rem;">
                                <label class="form-check-label" for="esPrincipal" style="color:var(--cb-text);font-weight:600;font-size:0.85rem;">
                                    <i class="ri-star-fill" style="color:#d97706;"></i> Marcar como cuenta principal
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cb-modal-footer">
                    <button type="button" class="cb-modal-btn cb-modal-btn-cancel" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Cancelar
                    </button>
                    <button type="submit" class="cb-modal-btn cb-modal-btn-submit">
                        <i class="ri-save-line"></i> Guardar cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════ Modales Editar ════════════════ --}}
@foreach($cuentas as $cuenta)
<div class="modal fade cb-modal" id="modalEditar{{ $cuenta->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.cuentas-bancarias.update', $cuenta->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="cb-modal-header">
                    <div class="cb-modal-header-icon"><i class="ri-edit-line"></i></div>
                    <div>
                        <h5 class="cb-modal-title">Editar cuenta</h5>
                        <div class="cb-modal-sub">{{ $cuenta->banco->nombre ?? '—' }} · {{ $cuenta->numero_cuenta }}</div>
                    </div>
                    <button type="button" class="cb-modal-close-btn" data-bs-dismiss="modal" aria-label="Cerrar">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="cb-modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="cb-form-label"><i class="ri-bank-line"></i> Banco *</label>
                            <select name="banco_id" class="form-select cb-input" required>
                                @foreach($bancos as $banco)
                                    <option value="{{ $banco->id }}" {{ $cuenta->banco_id == $banco->id ? 'selected' : '' }}>{{ $banco->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="cb-form-label"><i class="ri-bank-card-2-line"></i> Tipo de cuenta *</label>
                            <select name="tipo_cuenta" class="form-select cb-input" required>
                                <option value="Cuenta Corriente" {{ $cuenta->tipo_cuenta == 'Cuenta Corriente' ? 'selected' : '' }}>Cuenta Corriente</option>
                                <option value="Cuenta de Ahorro" {{ $cuenta->tipo_cuenta == 'Cuenta de Ahorro' ? 'selected' : '' }}>Cuenta de Ahorro</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="cb-form-label"><i class="ri-hashtag"></i> Número de cuenta *</label>
                            <input type="text" name="numero_cuenta" class="form-control cb-input" value="{{ $cuenta->numero_cuenta }}" required>
                        </div>
                        <div class="col-md-7">
                            <label class="cb-form-label"><i class="ri-user-3-line"></i> Titular</label>
                            <input type="text" name="titular" class="form-control cb-input" value="{{ $cuenta->titular }}">
                        </div>
                        <div class="col-md-5">
                            <label class="cb-form-label"><i class="ri-id-card-line"></i> CI / NIT</label>
                            <input type="text" name="ci_titular" class="form-control cb-input" value="{{ $cuenta->ci_titular }}">
                        </div>
                        <div class="col-12">
                            <label class="cb-form-label"><i class="ri-calendar-line"></i> Vence QR</label>
                            <input type="date" name="fecha_vencimiento_qr" class="form-control cb-input" value="{{ $cuenta->fecha_vencimiento_qr ? \Carbon\Carbon::parse($cuenta->fecha_vencimiento_qr)->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-12">
                            <div class="form-check" style="padding-left:1.6rem;">
                                <input type="checkbox" name="es_principal" class="form-check-input" id="esPrincipal{{ $cuenta->id }}" {{ $cuenta->es_principal ? 'checked' : '' }} style="margin-top:0.2rem;">
                                <label class="form-check-label" for="esPrincipal{{ $cuenta->id }}" style="color:var(--cb-text);font-weight:600;font-size:0.85rem;">
                                    <i class="ri-star-fill" style="color:#d97706;"></i> Cuenta principal
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cb-modal-footer">
                    <button type="button" class="cb-modal-btn cb-modal-btn-cancel" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Cancelar
                    </button>
                    <button type="submit" class="cb-modal-btn cb-modal-btn-submit">
                        <i class="ri-save-line"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buscar = document.getElementById('cbBuscar');
    const grid = document.getElementById('cbGrid');
    const emptySearch = document.getElementById('cbEmptySearch');
    const chips = document.querySelectorAll('.cb-filter-chip');
    let filtroActivo = 'todas';

    function aplicarFiltros() {
        if (!grid) return;
        const q = (buscar?.value || '').trim().toLowerCase();
        let visibles = 0;
        grid.querySelectorAll('.cb-account-card').forEach(card => {
            const banco    = card.dataset.banco || '';
            const numero   = card.dataset.numero || '';
            const titular  = card.dataset.titular || '';
            const estado   = card.dataset.estado;
            const esPrinc  = card.dataset.principal === '1';

            let matchTexto = !q || banco.includes(q) || numero.includes(q) || titular.includes(q);
            let matchFiltro = true;
            if (filtroActivo === 'activas')    matchFiltro = estado === 'activa';
            if (filtroActivo === 'inactivas')  matchFiltro = estado === 'inactiva';
            if (filtroActivo === 'principal')  matchFiltro = esPrinc;

            const visible = matchTexto && matchFiltro;
            card.style.display = visible ? '' : 'none';
            if (visible) visibles++;
        });
        if (emptySearch) emptySearch.style.display = visibles === 0 ? '' : 'none';
    }

    buscar?.addEventListener('input', aplicarFiltros);
    chips.forEach(chip => {
        chip.addEventListener('click', function () {
            chips.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            filtroActivo = this.dataset.filtro;
            aplicarFiltros();
        });
    });
});
</script>
@endsection
