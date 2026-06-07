@extends('layouts.master')
@section('title') Cajas @endsection

@section('css')
<style>
:root {
    --cj-primary: #198754;
    --cj-primary-dark: #145c32;
    --cj-primary-soft: rgba(40,167,69,0.08);
    --cj-warning: #d97706;
    --cj-warning-soft: rgba(217,119,6,0.10);
    --cj-orange: #fc7b04;
    --cj-orange-dark: #c25e00;
    --cj-orange-soft: rgba(252,123,4,0.10);
    --cj-surface: #f8fafc;
    --cj-surface-2: #ffffff;
    --cj-border: #e2e8f0;
    --cj-text: #1e293b;
    --cj-muted: #64748b;
}
[data-bs-theme="dark"] {
    --cj-surface: #1e1e2d;
    --cj-surface-2: #212229;
    --cj-border: rgba(255,255,255,0.08);
    --cj-text: #e9ecef;
    --cj-muted: #9ca3af;
    --cj-primary-soft: rgba(40,167,69,0.14);
}

@keyframes cjFade { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
.cj-page { animation: cjFade .45s ease-out; }

/* ── Hero ── */
.cj-hero {
    position:relative; overflow:hidden;
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:18px;
    padding:28px 32px; margin-bottom:22px;
    background:linear-gradient(135deg,#0a3d22 0%, #145c32 50%, #198754 100%);
    border-radius:22px; color:#fff;
    box-shadow:0 10px 32px rgba(20,92,50,0.30);
}
.cj-hero::before {
    content:''; position:absolute; top:-40%; right:-8%;
    width:360px; height:360px; border-radius:50%;
    background:radial-gradient(circle, rgba(40,167,69,0.30) 0%, transparent 70%);
    pointer-events:none;
}
.cj-hero-content { position:relative; z-index:1; }
.cj-hero h1 { margin:0; font-size:1.55rem; font-weight:800; display:flex; align-items:center; gap:12px; letter-spacing:-0.02em; }
.cj-hero h1 i { color:#a7f3d0; }
.cj-hero p { margin:6px 0 0; opacity:0.85; font-size:0.9rem; }
.cj-hero-actions { position:relative; z-index:1; }
.cj-btn-new {
    display:inline-flex; align-items:center; gap:8px;
    padding:10px 18px; border-radius:12px;
    background:rgba(255,255,255,0.15); backdrop-filter:blur(8px);
    border:1px solid rgba(255,255,255,0.20); color:#fff;
    font-weight:700; font-size:0.85rem; cursor:pointer;
    transition:all .25s;
}
.cj-btn-new:hover { background:#fff; color:var(--cj-primary-dark); transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,0.18); }

/* ── Stats ── */
.cj-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:22px; }
.cj-stat {
    background:var(--cj-surface-2); border:1px solid var(--cj-border);
    border-radius:14px; padding:16px 18px;
    display:flex; align-items:center; gap:12px;
    box-shadow:0 1px 3px rgba(0,0,0,0.04); transition:all .25s;
}
.cj-stat:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(0,0,0,0.07); }
.cj-stat-icon {
    width:44px; height:44px; border-radius:12px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:1.25rem;
}
.cj-stat-icon.abiertas { background:var(--cj-primary-soft); color:var(--cj-primary); }
.cj-stat-icon.cerradas { background:rgba(108,117,125,0.10); color:#6c757d; }
.cj-stat-icon.saldo    { background:var(--cj-orange-soft); color:var(--cj-orange-dark); }
.cj-stat-icon.total    { background:rgba(13,110,253,0.10); color:#0d6efd; }
.cj-stat-lbl { font-size:0.7rem; font-weight:700; color:var(--cj-muted); text-transform:uppercase; letter-spacing:0.05em; }
.cj-stat-val { font-size:1.35rem; font-weight:800; color:var(--cj-text); font-family:'Outfit',sans-serif; line-height:1.1; margin-top:2px; }
.cj-stat-sub { font-size:0.68rem; color:var(--cj-muted); margin-top:2px; }
@media (max-width:992px) { .cj-stats { grid-template-columns:repeat(2,1fr); } }
@media (max-width:480px) { .cj-stats { grid-template-columns:1fr; } }

/* ── Filtros ── */
.cj-filter {
    background:var(--cj-surface-2); border:1px solid var(--cj-border);
    border-radius:14px; padding:12px 16px; margin-bottom:20px;
    display:flex; align-items:center; gap:10px; flex-wrap:wrap;
}
.cj-filter-search { position:relative; flex:1; min-width:220px; }
.cj-filter-search i {
    position:absolute; left:12px; top:50%; transform:translateY(-50%);
    color:var(--cj-muted);
}
.cj-filter-search input {
    width:100%; padding:10px 14px 10px 38px;
    background:var(--cj-surface); border:1px solid var(--cj-border); border-radius:10px;
    color:var(--cj-text); font-size:0.88rem;
}
.cj-filter-search input:focus {
    outline:none; border-color:var(--cj-primary);
    box-shadow:0 0 0 3px var(--cj-primary-soft);
}
.cj-chip {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 14px; border-radius:20px;
    background:var(--cj-surface); border:1px solid var(--cj-border);
    color:var(--cj-muted); font-size:0.78rem; font-weight:600;
    cursor:pointer; transition:all .2s;
}
.cj-chip:hover { border-color:var(--cj-primary); color:var(--cj-primary-dark); }
.cj-chip.active { background:linear-gradient(135deg,var(--cj-primary),var(--cj-primary-dark)); color:#fff; border-color:var(--cj-primary); }
.cj-chip[data-filtro="cerradas"].active { background:linear-gradient(135deg,#6c757d,#495057); border-color:#6c757d; }

/* ── Card de caja ── */
.cj-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(310px,1fr)); gap:16px; }
.cj-card {
    position:relative; overflow:hidden;
    background:var(--cj-surface-2); border:1px solid var(--cj-border);
    border-radius:16px;
    display:flex; flex-direction:column;
    transition:all .3s cubic-bezier(.4,0,.2,1);
}
.cj-card:hover { transform:translateY(-3px); box-shadow:0 18px 38px rgba(0,0,0,0.08); border-color:rgba(40,167,69,0.30); }
.cj-card-strip { height:5px; background:linear-gradient(90deg, var(--cj-primary), #28a745); }
.cj-card.cerrada .cj-card-strip { background:linear-gradient(90deg,#94a3b8,#64748b); }
.cj-card.cerrada { opacity:0.86; }

.cj-card-body { padding:16px 18px; flex:1; display:flex; flex-direction:column; }
.cj-card-top {
    display:flex; align-items:flex-start; gap:11px; margin-bottom:12px;
}
.cj-card-icon {
    width:44px; height:44px; border-radius:12px; flex-shrink:0;
    background:linear-gradient(135deg,#d1fae5,#a7f3d0);
    color:var(--cj-primary-dark);
    display:flex; align-items:center; justify-content:center; font-size:1.3rem;
    border:1px solid rgba(40,167,69,0.18);
}
.cj-card.cerrada .cj-card-icon { background:linear-gradient(135deg,#f1f5f9,#e2e8f0); color:#64748b; border-color:rgba(108,117,125,0.18); }
.cj-card-title { font-size:0.98rem; font-weight:800; color:var(--cj-text); line-height:1.2; letter-spacing:-0.01em; }
.cj-card-trab {
    font-size:0.74rem; color:var(--cj-muted); margin-top:2px;
    display:inline-flex; align-items:center; gap:4px;
}
.cj-card-trab i { color:var(--cj-orange); font-size:0.78rem; }
.cj-card-badge { margin-left:auto; flex-shrink:0; }
.cj-pill {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 9px; border-radius:20px;
    font-size:0.66rem; font-weight:800; letter-spacing:0.3px; text-transform:uppercase;
    white-space:nowrap;
}
.cj-pill.abierta { background:rgba(40,167,69,0.12); color:var(--cj-primary); border:1px solid rgba(40,167,69,0.22); }
.cj-pill.cerrada { background:rgba(108,117,125,0.12); color:#6c757d; border:1px solid rgba(108,117,125,0.22); }

.cj-card-saldo {
    background:linear-gradient(135deg,#0f172a,#1e293b);
    color:#fff; border-radius:12px; padding:14px 16px; margin-bottom:12px;
    position:relative; overflow:hidden;
}
.cj-card-saldo::before {
    content:''; position:absolute; top:-40%; right:-15%;
    width:180px; height:180px; border-radius:50%;
    background:radial-gradient(circle,rgba(40,167,69,0.25),transparent 70%);
}
.cj-card.cerrada .cj-card-saldo::before { background:radial-gradient(circle,rgba(108,117,125,0.25),transparent 70%); }
.cj-card-saldo-lbl { font-size:0.62rem; opacity:0.6; text-transform:uppercase; letter-spacing:0.08em; font-weight:700; position:relative; z-index:1; }
.cj-card-saldo-val {
    font-family:'Outfit',sans-serif; font-size:1.55rem; font-weight:800;
    margin-top:2px; color:#a7f3d0; position:relative; z-index:1; line-height:1.1;
}
.cj-card.cerrada .cj-card-saldo-val { color:#cbd5e1; }
.cj-card-saldo-sub { font-size:0.7rem; opacity:0.65; margin-top:6px; position:relative; z-index:1; }

.cj-card-info {
    background:rgba(40,167,69,0.04); border:1px dashed rgba(40,167,69,0.18);
    border-radius:10px; padding:9px 12px; margin-bottom:12px;
    font-size:0.76rem; color:var(--cj-text);
    display:flex; align-items:center; gap:6px;
}
.cj-card.cerrada .cj-card-info { background:rgba(108,117,125,0.04); border-color:rgba(108,117,125,0.18); }
.cj-card-info i { color:var(--cj-primary); font-size:0.85rem; }
.cj-card.cerrada .cj-card-info i { color:#64748b; }
.cj-card-info strong { color:var(--cj-muted); font-weight:700; font-size:0.66rem; text-transform:uppercase; letter-spacing:0.04em; margin-right:4px; }

.cj-card-actions {
    margin-top:auto; padding-top:12px; border-top:1px dashed var(--cj-border);
    display:flex; gap:6px; align-items:center;
}
.cj-action {
    flex:1; display:inline-flex; align-items:center; justify-content:center; gap:5px;
    padding:0.5rem 0.7rem; border-radius:9px; border:none;
    font-size:0.78rem; font-weight:700; cursor:pointer; transition:all .2s;
    text-decoration:none;
}
.cj-action.movimientos { background:linear-gradient(135deg,var(--cj-orange),var(--cj-orange-dark)); color:#fff; box-shadow:0 3px 10px rgba(252,123,4,0.28); }
.cj-action.movimientos:hover { color:#fff; transform:translateY(-1px); box-shadow:0 6px 18px rgba(252,123,4,0.38); }
.cj-action.cerrar { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 3px 10px rgba(217,119,6,0.25); }
.cj-action.cerrar:hover { color:#fff; transform:translateY(-1px); box-shadow:0 6px 18px rgba(217,119,6,0.38); }

/* ── Empty / loading ── */
.cj-empty {
    text-align:center; padding:60px 20px;
    background:var(--cj-surface-2); border:2px dashed var(--cj-border);
    border-radius:18px;
}
.cj-empty-icon {
    width:80px; height:80px; border-radius:50%; margin:0 auto 14px;
    background:linear-gradient(135deg,var(--cj-primary-soft),rgba(40,167,69,0.04));
    display:inline-flex; align-items:center; justify-content:center;
}
.cj-empty-icon i { font-size:2.4rem; color:var(--cj-primary); }
.cj-empty h5 { color:var(--cj-text); font-weight:700; margin-bottom:6px; }
.cj-empty p  { color:var(--cj-muted); margin:0 0 12px; }
.cj-loading {
    display:flex; align-items:center; justify-content:center; gap:10px;
    padding:60px 20px; color:var(--cj-muted); font-size:0.9rem;
}

/* ── Modal moderno ── */
.cj-modal .modal-content { border:none; border-radius:18px; overflow:hidden; box-shadow:0 24px 60px rgba(0,0,0,0.18); }
.cj-modal-header {
    color:#fff; padding:18px 22px; display:flex; align-items:center; gap:12px; border:none;
}
.cj-modal-header.abrir  { background:linear-gradient(135deg,#0a3d22,#145c32 50%,#198754); }
.cj-modal-header.cerrar { background:linear-gradient(135deg,#78350f,#b45309 50%,#d97706); }
.cj-modal-icon {
    width:44px; height:44px; border-radius:12px; flex-shrink:0;
    background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.22);
    display:flex; align-items:center; justify-content:center; font-size:1.25rem;
}
.cj-modal-title { margin:0; font-weight:700; font-size:1.05rem; }
.cj-modal-sub { font-size:0.78rem; opacity:0.85; }
.cj-modal-close {
    margin-left:auto; width:34px; height:34px; border:none; border-radius:9px;
    background:rgba(255,255,255,0.15); color:#fff; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
}
.cj-modal-close:hover { background:rgba(255,255,255,0.25); }
.cj-modal-body { padding:1.25rem 1.5rem; background:#fafaf7; }
.cj-form-label {
    display:inline-flex; align-items:center; gap:6px;
    font-size:0.74rem; font-weight:700; color:var(--cj-text);
    text-transform:uppercase; letter-spacing:0.04em; margin-bottom:5px;
}
.cj-form-label i { color:var(--cj-primary); font-size:0.85rem; }
.cj-input {
    width:100%; background:#fff !important; border:1.5px solid var(--cj-border) !important;
    border-radius:10px !important; padding:.55rem .85rem !important;
    font-size:0.88rem !important; color:var(--cj-text) !important;
    transition:border-color .2s, box-shadow .2s !important;
}
.cj-input:focus { border-color:var(--cj-primary) !important; box-shadow:0 0 0 3px var(--cj-primary-soft) !important; outline:none !important; }

.cj-summary-card {
    background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);
    border:1px solid rgba(217,119,6,0.20); border-radius:12px;
    padding:14px 16px; margin-bottom:14px;
    display:flex; align-items:center; gap:12px;
}
.cj-summary-icon {
    width:42px; height:42px; border-radius:12px; flex-shrink:0;
    background:rgba(217,119,6,0.15); color:var(--cj-warning);
    display:flex; align-items:center; justify-content:center; font-size:1.2rem;
}
.cj-summary-lbl { font-size:0.7rem; color:#92400e; font-weight:700; text-transform:uppercase; letter-spacing:0.04em; }
.cj-summary-name { font-size:0.95rem; font-weight:800; color:#1e293b; }
.cj-summary-monto { font-family:'Outfit',sans-serif; font-size:1.05rem; font-weight:800; color:#92400e; margin-top:2px; }

.cj-modal-footer {
    background:#fff; border-top:1px solid var(--cj-border); padding:0.85rem 1.5rem;
    display:flex; justify-content:flex-end; gap:8px;
}
.cj-modal-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:0.55rem 1.2rem; border-radius:10px;
    font-size:0.82rem; font-weight:700; border:none; cursor:pointer;
    transition:all .2s;
}
.cj-modal-btn-cancel { background:#e2e8f0; color:#475569; }
.cj-modal-btn-cancel:hover { background:#cbd5e1; }
.cj-modal-btn-submit-green {
    background:linear-gradient(135deg,var(--cj-primary),var(--cj-primary-dark));
    color:#fff; box-shadow:0 4px 12px rgba(40,167,69,0.30);
}
.cj-modal-btn-submit-green:hover { transform:translateY(-1px); box-shadow:0 8px 22px rgba(40,167,69,0.40); }
.cj-modal-btn-submit-warning {
    background:linear-gradient(135deg,#f59e0b,#d97706);
    color:#fff; box-shadow:0 4px 12px rgba(217,119,6,0.30);
}
.cj-modal-btn-submit-warning:hover { transform:translateY(-1px); box-shadow:0 8px 22px rgba(217,119,6,0.40); }

.cj-feedback { margin-top:8px; font-size:0.78rem; font-weight:600; }
.cj-feedback.error { color:#dc3545; }
</style>
@endsection

@section('content')
@php \Carbon\Carbon::setLocale('es'); @endphp

<div class="container-fluid py-3 cj-page">

    {{-- Hero --}}
    <div class="cj-hero">
        <div class="cj-hero-content">
            <h1><i class="ri-money-dollar-box-line"></i> Cajas</h1>
            <p>Gestión de apertura, cierre y control de cajas chicas</p>
        </div>
        <div class="cj-hero-actions">
            <button class="cj-btn-new" id="btnAbrirCaja">
                <i class="ri-add-circle-line"></i> Abrir caja
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="cj-stats">
        <div class="cj-stat">
            <div class="cj-stat-icon abiertas"><i class="ri-checkbox-circle-line"></i></div>
            <div>
                <div class="cj-stat-lbl">Cajas abiertas</div>
                <div class="cj-stat-val">{{ $cajasAbiertas }}</div>
                <div class="cj-stat-sub">en operación</div>
            </div>
        </div>
        <div class="cj-stat">
            <div class="cj-stat-icon cerradas"><i class="ri-lock-line"></i></div>
            <div>
                <div class="cj-stat-lbl">Cerradas</div>
                <div class="cj-stat-val">{{ $totalCajas - $cajasAbiertas }}</div>
                <div class="cj-stat-sub">históricas</div>
            </div>
        </div>
        <div class="cj-stat">
            <div class="cj-stat-icon saldo"><i class="ri-funds-line"></i></div>
            <div>
                <div class="cj-stat-lbl">Saldo en cajas</div>
                <div class="cj-stat-val">Bs {{ number_format($totalIngresos, 2) }}</div>
                <div class="cj-stat-sub">monto actual total</div>
            </div>
        </div>
        <div class="cj-stat">
            <div class="cj-stat-icon total"><i class="ri-archive-line"></i></div>
            <div>
                <div class="cj-stat-lbl">Total registradas</div>
                <div class="cj-stat-val">{{ $totalCajas }}</div>
                <div class="cj-stat-sub">cajas creadas</div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="cj-filter">
        <div class="cj-filter-search">
            <i class="ri-search-line"></i>
            <input type="text" id="cjBuscar" placeholder="Buscar por nombre o responsable...">
        </div>
        <button type="button" class="cj-chip active" data-filtro="todas"><i class="ri-apps-line"></i> Todas</button>
        <button type="button" class="cj-chip" data-filtro="abiertas"><i class="ri-checkbox-circle-line"></i> Abiertas</button>
        <button type="button" class="cj-chip" data-filtro="cerradas"><i class="ri-lock-line"></i> Cerradas</button>
    </div>

    {{-- Loading --}}
    <div class="cj-loading" id="cjLoading">
        <span class="spinner-border spinner-border-sm" role="status"></span>
        Cargando cajas...
    </div>

    {{-- Grid de cajas --}}
    <div class="cj-grid" id="cjGrid" style="display:none;"></div>

    {{-- Empty --}}
    <div class="cj-empty" id="cjEmpty" style="display:none;">
        <div class="cj-empty-icon"><i class="ri-money-dollar-box-line"></i></div>
        <h5>Sin cajas registradas</h5>
        <p>Aún no hay cajas. Abre la primera para empezar a operar.</p>
        <button class="cj-btn-new" style="background:linear-gradient(135deg,var(--cj-primary),var(--cj-primary-dark));color:#fff;border:none;" id="btnAbrirCaja2">
            <i class="ri-add-circle-line"></i> Abrir primera caja
        </button>
    </div>

    {{-- Empty búsqueda --}}
    <div class="cj-empty" id="cjEmptySearch" style="display:none;">
        <div class="cj-empty-icon"><i class="ri-search-line"></i></div>
        <h5>Sin coincidencias</h5>
        <p>No se encontraron cajas con ese criterio.</p>
    </div>

</div>

{{-- ════════════════ Modal Abrir Caja ════════════════ --}}
<div class="modal fade cj-modal" id="modalAbrirCaja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content">
            <div class="cj-modal-header abrir">
                <div class="cj-modal-icon"><i class="ri-add-circle-line"></i></div>
                <div>
                    <h5 class="cj-modal-title">Abrir caja</h5>
                    <div class="cj-modal-sub">Asigna responsable y monto inicial</div>
                </div>
                <button type="button" class="cj-modal-close" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <form id="formAbrirCaja" novalidate autocomplete="off">
                <div class="cj-modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="trabajadorAbrir" class="cj-form-label"><i class="ri-user-3-line"></i> Responsable *</label>
                            <select class="form-select cj-input" id="trabajadorAbrir" required>
                                <option value="">Seleccionar responsable...</option>
                                @foreach($trabajadores as $t)
                                    <option value="{{ $t->id }}">
                                        {{ $t->trabajador->persona->nombres ?? ($t->trabajador->persona->nombre ?? '') }} {{ $t->trabajador->persona->apellido_paterno ?? '' }}
                                        @if($t->cargo) — {{ $t->cargo->nombre ?? $t->nombre_cargo }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="montoAbrir" class="cj-form-label"><i class="ri-money-dollar-circle-line"></i> Monto inicial (Bs) *</label>
                            <input type="number" class="form-control cj-input" id="montoAbrir" step="0.01" min="0" value="0" required placeholder="0.00">
                        </div>
                    </div>
                    <div class="cj-feedback" id="fbAbrir"></div>
                </div>
                <div class="cj-modal-footer">
                    <button type="button" class="cj-modal-btn cj-modal-btn-cancel" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Cancelar
                    </button>
                    <button type="submit" class="cj-modal-btn cj-modal-btn-submit-green" id="btnAbrir">
                        <i class="ri-lock-unlock-line"></i> Abrir caja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════ Modal Cerrar Caja ════════════════ --}}
<div class="modal fade cj-modal" id="modalCerrar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content">
            <div class="cj-modal-header cerrar">
                <div class="cj-modal-icon"><i class="ri-lock-line"></i></div>
                <div>
                    <h5 class="cj-modal-title">Cerrar caja</h5>
                    <div class="cj-modal-sub">Confirma el monto final</div>
                </div>
                <button type="button" class="cj-modal-close" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <form id="formCerrarCaja" novalidate autocomplete="off">
                <input type="hidden" id="idCerrarCaja">
                <div class="cj-modal-body">
                    <div class="cj-summary-card">
                        <div class="cj-summary-icon"><i class="ri-money-dollar-box-line"></i></div>
                        <div style="flex:1;min-width:0;">
                            <div class="cj-summary-lbl">Caja seleccionada</div>
                            <div class="cj-summary-name" id="nombreCerrarCaja">—</div>
                            <div class="cj-summary-monto">Monto actual: <span id="montoActualCerrar">—</span></div>
                        </div>
                    </div>
                    <div>
                        <label for="montoCierre" class="cj-form-label"><i class="ri-money-dollar-circle-line"></i> Monto de cierre (Bs) *</label>
                        <input type="number" class="form-control cj-input" id="montoCierre" step="0.01" min="0" required placeholder="0.00">
                    </div>
                    <div class="cj-feedback" id="fbCerrar"></div>
                </div>
                <div class="cj-modal-footer">
                    <button type="button" class="cj-modal-btn cj-modal-btn-cancel" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Cancelar
                    </button>
                    <button type="submit" class="cj-modal-btn cj-modal-btn-submit-warning" id="btnCerrar">
                        <i class="ri-lock-line"></i> Cerrar caja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="toastContainer" class="toast-container"></div>
@endsection

@section('script')
<script>
(function () {
    'use strict';

    const CSRF = '{{ csrf_token() }}';
    let cajas = [];
    let filtroActivo = 'todas';

    document.addEventListener('DOMContentLoaded', function () {
        bindEvents();
        cargarCajas();
    });

    function bindEvents() {
        document.getElementById('btnAbrirCaja')?.addEventListener('click', abrirModalNueva);
        document.getElementById('btnAbrirCaja2')?.addEventListener('click', abrirModalNueva);

        document.getElementById('cjBuscar')?.addEventListener('input', renderCajas);

        document.querySelectorAll('.cj-chip').forEach(chip => {
            chip.addEventListener('click', function () {
                document.querySelectorAll('.cj-chip').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                filtroActivo = this.dataset.filtro;
                renderCajas();
            });
        });

        document.getElementById('formAbrirCaja').addEventListener('submit', function (e) {
            e.preventDefault();
            abrirCaja();
        });
        document.getElementById('formCerrarCaja').addEventListener('submit', function (e) {
            e.preventDefault();
            cerrarCaja();
        });

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-cerrar-caja');
            if (!btn) return;
            const id     = btn.dataset.id;
            const nombre = btn.dataset.nombre;
            const monto  = btn.dataset.monto;
            document.getElementById('idCerrarCaja').value = id;
            document.getElementById('nombreCerrarCaja').textContent = nombre;
            document.getElementById('montoActualCerrar').textContent = 'Bs ' + fmt(monto);
            document.getElementById('montoCierre').value = monto;
            document.getElementById('fbCerrar').textContent = '';
            openModal('modalCerrar');
        });
    }

    function abrirModalNueva() {
        document.getElementById('formAbrirCaja').reset();
        document.getElementById('trabajadorAbrir').value = '';
        document.getElementById('montoAbrir').value = '0';
        document.getElementById('fbAbrir').textContent = '';
        openModal('modalAbrirCaja');
    }

    function cargarCajas() {
        document.getElementById('cjLoading').style.display = 'flex';
        document.getElementById('cjGrid').style.display = 'none';
        document.getElementById('cjEmpty').style.display = 'none';
        document.getElementById('cjEmptySearch').style.display = 'none';

        fetch('{{ route("admin.cajas.listar") }}', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(json => {
                cajas = json.data || [];
                document.getElementById('cjLoading').style.display = 'none';
                renderCajas();
            })
            .catch(() => {
                document.getElementById('cjLoading').style.display = 'none';
                toast('error', 'Error al cargar cajas.');
            });
    }

    function renderCajas() {
        const grid = document.getElementById('cjGrid');
        const empty = document.getElementById('cjEmpty');
        const emptySearch = document.getElementById('cjEmptySearch');

        if (!cajas.length) {
            grid.style.display = 'none';
            empty.style.display = '';
            emptySearch.style.display = 'none';
            return;
        }

        const q = (document.getElementById('cjBuscar')?.value || '').trim().toLowerCase();
        const filtradas = cajas.filter(c => {
            const matchQ = !q || (c.nombre || '').toLowerCase().includes(q) || (c.trabajador || '').toLowerCase().includes(q);
            let matchF = true;
            if (filtroActivo === 'abiertas') matchF = c.estado === 'Abierta';
            if (filtroActivo === 'cerradas') matchF = c.estado !== 'Abierta';
            return matchQ && matchF;
        });

        if (!filtradas.length) {
            grid.style.display = 'none';
            empty.style.display = 'none';
            emptySearch.style.display = '';
            return;
        }

        empty.style.display = 'none';
        emptySearch.style.display = 'none';
        grid.style.display = '';

        grid.innerHTML = filtradas.map(c => {
            const esAbierta = c.estado === 'Abierta';
            return `
                <div class="cj-card ${esAbierta ? '' : 'cerrada'}">
                    <div class="cj-card-strip"></div>
                    <div class="cj-card-body">
                        <div class="cj-card-top">
                            <div class="cj-card-icon"><i class="ri-money-dollar-box-line"></i></div>
                            <div style="flex:1;min-width:0;">
                                <div class="cj-card-title">${escHtml(c.nombre)}</div>
                                <div class="cj-card-trab"><i class="ri-user-3-line"></i> ${escHtml(c.trabajador || '—')}</div>
                            </div>
                            <div class="cj-card-badge">
                                <span class="cj-pill ${esAbierta ? 'abierta' : 'cerrada'}">
                                    <i class="ri-${esAbierta ? 'checkbox-circle-fill' : 'lock-fill'}"></i>
                                    ${esAbierta ? 'Abierta' : 'Cerrada'}
                                </span>
                            </div>
                        </div>

                        <div class="cj-card-saldo">
                            <div class="cj-card-saldo-lbl">Monto actual</div>
                            <div class="cj-card-saldo-val">Bs ${fmt(c.monto_actual)}</div>
                            <div class="cj-card-saldo-sub">Inicial: Bs ${fmt(c.monto_inicial)}</div>
                        </div>

                        <div class="cj-card-info">
                            <i class="ri-calendar-event-line"></i>
                            <strong>Apertura</strong>
                            <span>${escHtml(c.fecha_apertura || '—')}</span>
                        </div>

                        <div class="cj-card-actions">
                            <a href="/admin/cajas/${c.id}/movimientos" class="cj-action movimientos">
                                <i class="ri-history-line"></i> Ver movimientos
                            </a>
                            ${esAbierta ? `
                                <button class="cj-action cerrar btn-cerrar-caja"
                                    data-id="${c.id}" data-nombre="${escHtml(c.nombre)}" data-monto="${c.monto_actual}">
                                    <i class="ri-lock-line"></i> Cerrar
                                </button>` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function abrirCaja() {
        const trabajadorId = document.getElementById('trabajadorAbrir').value;
        const monto = document.getElementById('montoAbrir').value;
        const fb = document.getElementById('fbAbrir');

        if (!trabajadorId) {
            fb.textContent = 'Debe seleccionar un responsable.';
            fb.className = 'cj-feedback error';
            return;
        }
        fb.textContent = '';

        setBtnLoading('#btnAbrir', true, 'Abriendo…');
        $.post('{{ route("admin.cajas.abrir") }}', {
            _token: CSRF,
            trabajadore_cargo_id: trabajadorId,
            monto_inicial: monto
        })
        .done(r => {
            closeModal('modalAbrirCaja');
            cargarCajas();
            toast('success', r.message || 'Caja abierta correctamente.');
        })
        .fail(xhr => {
            const msg = xhr.responseJSON?.message || 'Error al abrir caja.';
            fb.textContent = msg;
            fb.className = 'cj-feedback error';
        })
        .always(() => setBtnLoading('#btnAbrir', false, '<i class="ri-lock-unlock-line"></i> Abrir caja'));
    }

    function cerrarCaja() {
        const id = document.getElementById('idCerrarCaja').value;
        const monto = document.getElementById('montoCierre').value;
        const fb = document.getElementById('fbCerrar');

        if (!monto || parseFloat(monto) < 0) {
            fb.textContent = 'El monto de cierre es requerido.';
            fb.className = 'cj-feedback error';
            return;
        }
        fb.textContent = '';

        setBtnLoading('#btnCerrar', true, 'Cerrando…');
        $.ajax({
            url: '/admin/cajas/' + id + '/cerrar',
            type: 'POST',
            data: { _token: CSRF, monto_cierre: monto }
        })
        .done(r => {
            closeModal('modalCerrar');
            cargarCajas();
            toast('success', r.message || 'Caja cerrada correctamente.');
        })
        .fail(xhr => {
            const msg = xhr.responseJSON?.message || 'Error al cerrar caja.';
            toast('error', msg);
        })
        .always(() => setBtnLoading('#btnCerrar', false, '<i class="ri-lock-line"></i> Cerrar caja'));
    }

    function setBtnLoading(sel, loading, labelHtml) {
        const btn = document.querySelector(sel);
        if (!btn) return;
        btn.disabled = loading;
        if (loading) {
            btn.dataset.orig = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' + labelHtml;
        } else {
            btn.innerHTML = labelHtml;
        }
    }

    function openModal(id) { new bootstrap.Modal(document.getElementById(id)).show(); }
    function closeModal(id) {
        const el = document.getElementById(id);
        const m = bootstrap.Modal.getInstance(el);
        if (m) m.hide();
    }
    function escHtml(str) { return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    function fmt(n) { return Number(n || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

    function toast(tipo, mensaje) {
        const iconMap = { success: 'ri-check-double-line', error: 'ri-close-circle-line', warning: 'ri-alert-line' };
        const el = document.createElement('div');
        el.className = 'toast-notify ' + tipo;
        el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') + '"></i></div>'
            + '<div class="toast-body-text"><span>' + mensaje + '</span></div>'
            + '<button class="toast-close" title="Cerrar"><i class="ri-close-line"></i></button>';
        let c = document.getElementById('toastContainer');
        if (!c) {
            c = document.createElement('div');
            c.id = 'toastContainer';
            c.className = 'toast-container';
            document.body.appendChild(c);
        }
        c.appendChild(el);
        el.querySelector('.toast-close').addEventListener('click', () => el.remove());
        setTimeout(() => el.remove(), 4500);
    }
})();
</script>
@endsection
