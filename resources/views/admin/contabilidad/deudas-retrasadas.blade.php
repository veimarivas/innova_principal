@extends('layouts.master')

@section('title') Deudas Retrasadas @endsection

@section('content')
<style>
    :root {
        --fin-primary: #fc7b04;
        --fin-primary-dark: #c25e00;
        --fin-primary-light: #fff3e0;
        --fin-danger: #ef4444;
        --fin-danger-light: #fef2f2;
        --fin-warning: #f59e0b;
        --fin-warning-light: #fffbeb;
        --fin-surface: #f8fafc;
        --fin-surface-2: #ffffff;
        --fin-border: #e2e8f0;
        --fin-text: #1e293b;
        --fin-text-muted: #64748b;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 24px;
        --shadow-md: 0 4px 6px -1px rgba(0,0,0,.06), 0 2px 4px -2px rgba(0,0,0,.04);
        --shadow-lg: 0 10px 15px -3px rgba(0,0,0,.08), 0 4px 6px -4px rgba(0,0,0,.04);
    }
    [data-bs-theme="dark"] {
        --fin-surface: #1e1e2d;
        --fin-surface-2: #212229;
        --fin-border: #2d2d3a;
        --fin-text: #e9ecef;
        --fin-text-muted: #9ca3af;
        --fin-primary-light: rgba(252,123,4,.12);
    }
    .fin-page { animation: finFadeIn .45s ease-out; }
    @keyframes finFadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }

    .fin-header {
        position: relative; overflow: hidden;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;
        padding: 32px 36px; margin-bottom: 28px;
        background: linear-gradient(135deg, #c25e00 0%, #7a3900 100%);
        border-radius: var(--radius-xl); color: #fff;
    }
    .fin-header::before {
        content:''; position:absolute; top:-50%; right:-10%;
        width:400px; height:400px;
        background: radial-gradient(circle, rgba(252,123,4,.2) 0%, transparent 70%);
        pointer-events:none;
    }
    .fin-header-content { position:relative; z-index:1; }
    .fin-header h1 { margin:0; font-size:1.75rem; font-weight:700; display:flex; align-items:center; gap:12px; color:#fff; }
    .fin-header h1 i { color: #fbbf77; }
    .fin-header p { margin:8px 0 0; opacity:.85; font-size:.95rem; }
    .fin-header-meta {
        position:relative; z-index:1;
        background:rgba(255,255,255,.12); backdrop-filter:blur(8px);
        padding:12px 20px; border-radius:var(--radius-md); border:1px solid rgba(255,255,255,.2);
        font-size:.85rem;
    }
    .fin-header-meta i { color:#fbbf77; margin-right:6px; }

    .fin-filter-bar {
        background: var(--fin-surface-2); border: 1px solid var(--fin-border);
        border-radius: var(--radius-lg); padding: 16px 20px; margin-bottom: 24px;
        display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
    }
    .fin-filter-bar label { font-weight:600; font-size:.85rem; color:var(--fin-text-muted); white-space:nowrap; }
    .fin-filter-bar select { border-radius:var(--radius-sm); border:1px solid var(--fin-border); padding:8px 12px; font-size:.875rem; min-width:220px; background:var(--fin-surface); color:var(--fin-text); }
    .fin-filter-bar select:focus { outline:none; border-color:var(--fin-primary); box-shadow:0 0 0 3px rgba(252,123,4,.15); }

    /* ── Resumen superior ── */
    .fin-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:24px; }
    .fin-stat-card {
        background:var(--fin-surface-2); border:1px solid var(--fin-border);
        border-radius:var(--radius-lg); padding:18px 20px;
        display:flex; align-items:center; gap:14px;
        box-shadow:var(--shadow-md);
    }
    .fin-stat-icon {
        width:46px; height:46px; border-radius:12px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center; font-size:1.35rem;
    }
    .fin-stat-icon.danger { background:rgba(239,68,68,.12); color:#dc2626; }
    .fin-stat-icon.warning { background:rgba(245,158,11,.12); color:#d97706; }
    .fin-stat-icon.primary { background:var(--fin-primary-light); color:var(--fin-primary); }
    .fin-stat-lbl { font-size:.72rem; font-weight:600; color:var(--fin-text-muted); text-transform:uppercase; letter-spacing:.05em; }
    .fin-stat-val { font-size:1.35rem; font-weight:700; color:var(--fin-text); line-height:1.1; margin-top:2px; }
    @media(max-width:768px){ .fin-stats { grid-template-columns:1fr; } }

    /* ── Layout dos columnas ── */
    .fin-layout { display:grid; grid-template-columns:340px 1fr; gap:20px; align-items:start; }
    @media(max-width:992px){ .fin-layout { grid-template-columns:1fr; } }

    /* ── Sidebar de ofertas ── */
    .ofertas-sidebar {
        background:var(--fin-surface-2); border:1px solid var(--fin-border);
        border-radius:var(--radius-lg); padding:14px; box-shadow:var(--shadow-md);
        position:sticky; top:16px; max-height:calc(100vh - 40px); display:flex; flex-direction:column;
    }
    .ofertas-sidebar-head {
        display:flex; align-items:center; justify-content:space-between; gap:8px;
        padding:4px 6px 10px; margin-bottom:8px;
        border-bottom:1px solid var(--fin-border);
    }
    .ofertas-sidebar-title {
        font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em;
        color:var(--fin-text-muted); display:flex; align-items:center; gap:6px;
    }
    .ofertas-sidebar-title i { color:var(--fin-primary); }
    .ofertas-sidebar-count {
        background:var(--fin-primary-light); color:var(--fin-primary-dark);
        padding:2px 9px; border-radius:20px; font-size:.7rem; font-weight:700;
    }
    .ofertas-search { position:relative; margin-bottom:10px; }
    .ofertas-search i {
        position:absolute; left:11px; top:50%; transform:translateY(-50%);
        color:var(--fin-text-muted); font-size:.95rem;
    }
    .ofertas-search input {
        width:100%; padding:9px 12px 9px 34px;
        border:1px solid var(--fin-border); border-radius:var(--radius-md);
        background:var(--fin-surface); color:var(--fin-text); font-size:.85rem;
        transition:border-color .2s, box-shadow .2s;
    }
    .ofertas-search input:focus { outline:none; border-color:var(--fin-primary); box-shadow:0 0 0 3px rgba(252,123,4,.13); }
    .ofertas-list { overflow-y:auto; padding-right:4px; display:flex; flex-direction:column; gap:6px; }
    .ofertas-list::-webkit-scrollbar { width:6px; }
    .ofertas-list::-webkit-scrollbar-track { background:transparent; }
    .ofertas-list::-webkit-scrollbar-thumb { background:var(--fin-border); border-radius:10px; }
    .ofertas-list::-webkit-scrollbar-thumb:hover { background:#cbd5e1; }
    .oferta-item {
        position:relative;
        display:flex; align-items:flex-start; gap:11px;
        padding:11px 13px; border-radius:var(--radius-md);
        background:transparent; border:1px solid transparent;
        cursor:pointer; transition:all .2s;
    }
    .oferta-item:hover { background:var(--fin-surface); border-color:var(--fin-border); }
    .oferta-item.active {
        background:linear-gradient(135deg, rgba(252,123,4,.1) 0%, rgba(252,123,4,.04) 100%);
        border-color:rgba(252,123,4,.3);
        box-shadow:0 2px 8px rgba(252,123,4,.08);
    }
    .oferta-item.active::before {
        content:''; position:absolute; left:0; top:8px; bottom:8px; width:3px;
        background:linear-gradient(180deg, var(--fin-primary) 0%, var(--fin-primary-dark) 100%);
        border-radius:0 3px 3px 0;
    }
    .oferta-item-icon {
        width:36px; height:36px; border-radius:10px; flex-shrink:0;
        background:var(--fin-primary-light); color:var(--fin-primary);
        display:flex; align-items:center; justify-content:center; font-size:1.05rem;
        transition:all .2s;
    }
    .oferta-item.active .oferta-item-icon {
        background:linear-gradient(135deg, var(--fin-primary) 0%, var(--fin-primary-dark) 100%);
        color:#fff;
    }
    .oferta-item-body { flex:1; min-width:0; }
    .oferta-item-nombre {
        font-size:.86rem; font-weight:600; color:var(--fin-text);
        line-height:1.3; margin-bottom:4px;
        display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;
        overflow:hidden;
    }
    .oferta-item-meta { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .oferta-item-chip {
        display:inline-flex; align-items:center; gap:3px;
        font-size:.7rem; font-weight:600; padding:2px 8px; border-radius:20px;
        background:rgba(239,68,68,.1); color:#dc2626;
    }
    .oferta-item-chip.monto { background:rgba(245,158,11,.1); color:#b45309; }
    .ofertas-empty-search {
        text-align:center; padding:28px 12px;
        color:var(--fin-text-muted); font-size:.85rem;
    }

    .tab-panel-card {
        background:var(--fin-surface-2); border:1px solid var(--fin-border);
        border-radius:var(--radius-xl); padding:24px; box-shadow:var(--shadow-md);
    }
    .panel-header {
        display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;
        margin-bottom:24px; padding-bottom:20px; border-bottom:1px solid var(--fin-border);
    }
    .panel-title { margin:0; font-size:1.15rem; font-weight:600; color:var(--fin-text); display:flex; align-items:center; gap:10px; }
    .panel-title i { color:var(--fin-primary); }
    .panel-actions { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
    .badge-total {
        background:linear-gradient(135deg, #7a3900 0%, #c25e00 100%);
        color:#fff; padding:8px 16px; border-radius:20px; font-size:.82rem; font-weight:600;
        display:flex; align-items:center; gap:6px;
    }
    .btn-wa-all {
        background:linear-gradient(135deg,#25D366 0%,#128C7E 100%); color:#fff; border:none;
        padding:9px 18px; border-radius:var(--radius-md); font-size:.85rem; font-weight:600;
        cursor:pointer; display:flex; align-items:center; gap:7px;
        box-shadow:0 4px 12px rgba(37,211,102,.3); transition:all .25s;
    }
    .btn-wa-all:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(37,211,102,.4); color:#fff; }

    .est-card {
        background:var(--fin-surface-2); border:1px solid var(--fin-border);
        border-radius:var(--radius-lg); padding:22px; margin-bottom:14px;
        position:relative; overflow:hidden; transition:all .3s;
    }
    .est-card::before {
        content:''; position:absolute; top:0; left:0; width:4px; height:100%;
        background:linear-gradient(180deg,var(--fin-danger) 0%,transparent 100%);
        opacity:0; transition:opacity .3s;
    }
    .est-card:hover { box-shadow:var(--shadow-lg); transform:translateY(-2px); border-color:rgba(239,68,68,.2); }
    .est-card:hover::before { opacity:1; }

    .est-main { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:18px; }
    .est-avatar {
        width:50px; height:50px; border-radius:50%; flex-shrink:0;
        background:linear-gradient(135deg,#c25e00 0%,var(--fin-primary) 100%);
        display:flex; align-items:center; justify-content:center;
        color:#fff; font-size:1.2rem; font-weight:700;
    }
    .est-info { flex:1; min-width:180px; }
    .est-nombre { font-weight:600; font-size:1rem; color:var(--fin-text); margin-bottom:3px; }
    .est-celular { color:var(--fin-text-muted); font-size:.875rem; display:flex; align-items:center; gap:5px; }
    .est-celular i { color:var(--fin-primary); }
    .est-deuda { text-align:right; margin-top:6px; }
    .deuda-label { font-size:.72rem; color:var(--fin-text-muted); text-transform:uppercase; letter-spacing:.05em; }
    .deuda-value { font-size:1.3rem; font-weight:700; color:var(--fin-danger); }
    .est-actions { display:flex; gap:8px; flex-wrap:wrap; }
    .btn-act {
        padding:9px 15px; border-radius:var(--radius-md); font-size:.82rem; font-weight:600;
        cursor:pointer; display:flex; align-items:center; gap:6px; border:none; transition:all .25s;
    }
    .btn-act:hover { transform:translateY(-2px); }
    .btn-wa { background:#25D366; color:#fff; box-shadow:0 3px 10px rgba(37,211,102,.25); }
    .btn-wa:hover { background:#128C7E; color:#fff; }
    .btn-cuotas {
        background:linear-gradient(135deg,var(--fin-primary) 0%,var(--fin-primary-dark) 100%);
        color:#fff; box-shadow:0 3px 10px rgba(252,123,4,.3);
    }
    .btn-cuotas:hover { color:#fff; }

    .est-resumen {
        display:flex; gap:10px; flex-wrap:wrap;
        margin-top:14px; padding-top:14px; border-top:1px solid var(--fin-border);
    }
    .est-badge {
        padding:5px 12px; border-radius:20px; font-size:.78rem; font-weight:600;
        display:flex; align-items:center; gap:5px;
    }
    .est-badge.retrasada {
        background:rgba(239,68,68,.1); color:var(--fin-danger);
        border:1px solid rgba(239,68,68,.15);
    }
    .est-badge.retrasada i { animation:pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.45;} }

    .empty-state {
        text-align:center; padding:72px 40px;
        background:var(--fin-surface-2); border:1px solid var(--fin-border); border-radius:var(--radius-xl);
    }
    .empty-icon {
        width:90px; height:90px; border-radius:50%;
        background:linear-gradient(135deg,var(--fin-primary-light) 0%,rgba(252,123,4,.05) 100%);
        display:inline-flex; align-items:center; justify-content:center; margin-bottom:20px;
    }
    .empty-icon i { font-size:2.8rem; color:var(--fin-primary); }

    .modal-fin-header {
        background:linear-gradient(135deg,#c25e00 0%,#7a3900 100%);
        color:#fff; border-radius:var(--radius-lg) var(--radius-lg) 0 0; border:none; padding:18px 24px;
    }
    .modal-fin-header .modal-title { color:#fff; font-weight:600; }
    .modal-cuota-item {
        display:flex; justify-content:space-between; align-items:center;
        padding:14px; margin-bottom:10px; background:var(--fin-surface);
        border-radius:var(--radius-md); border:1px solid transparent; transition:all .2s;
    }
    .modal-cuota-item:hover { border-color:rgba(252,123,4,.2); }
    .cuota-num {
        width:34px; height:34px; border-radius:50%; flex-shrink:0; margin-right:12px;
        background:linear-gradient(135deg,var(--fin-primary) 0%,var(--fin-primary-dark) 100%);
        color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:600;
    }
    .cuota-nombre { font-weight:600; color:var(--fin-text); font-size:.9rem; }
    .cuota-fecha { font-size:.82rem; margin-top:2px; color:var(--fin-danger); font-weight:600; }
    .cuota-monto { font-size:1.05rem; font-weight:700; color:var(--fin-danger); }
    .modal-resumen {
        background:linear-gradient(135deg,rgba(239,68,68,.08) 0%,rgba(239,68,68,.03) 100%);
        padding:18px; border-radius:var(--radius-md); margin-top:14px;
        border:1px solid rgba(239,68,68,.15);
    }
    @media(max-width:768px){
        .fin-header { flex-direction:column; align-items:flex-start; padding:24px; }
        .est-main { flex-direction:column; }
        .est-actions { width:100%; }
        .btn-act { flex:1; justify-content:center; }
    }
</style>

<div class="container-fluid fin-page">

    {{-- HEADER --}}
    <div class="fin-header">
        <div class="fin-header-content">
            <h1><i class="ri-error-warning-line"></i>Deudas Retrasadas</h1>
            <p>Estudiantes con cuotas vencidas sin pagar</p>
        </div>
        <div class="fin-header-meta">
            <i class="ri-calendar-line"></i> {{ now()->format('d/m/Y') }}
        </div>
    </div>

    {{-- FILTRO --}}
    <form method="GET" action="{{ route('admin.contabilidad.deudas-retrasadas') }}" class="fin-filter-bar">
        <i class="ri-filter-3-line" style="color:var(--fin-primary);font-size:1.1rem;"></i>
        <label>Filtrar por oferta:</label>
        <select name="oferta_id" onchange="this.form.submit()">
            <option value="">— Todas las ofertas —</option>
            @foreach($todasOfertas as $id => $nombre)
                <option value="{{ $id }}" {{ request('oferta_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
            @endforeach
        </select>
        @if(request('oferta_id'))
            <a href="{{ route('admin.contabilidad.deudas-retrasadas') }}"
               class="btn btn-sm btn-light d-flex align-items-center gap-1">
                <i class="ri-close-line"></i> Limpiar
            </a>
        @endif
    </form>

    @if(empty($resultados))
        <div class="empty-state">
            <div class="empty-icon"><i class="ri-checkbox-circle-line"></i></div>
            <h3 style="font-weight:600;font-size:1.4rem;color:var(--fin-text);margin-bottom:6px;">
                ¡Sin deudas retrasadas!
            </h3>
            <p style="color:var(--fin-text-muted);">Todos los estudiantes están al día con sus pagos.</p>
        </div>
    @else
        @php
            $totalGlobal = 0;
            $totalEstGlobal = 0;
            foreach ($resultados as $o) {
                $totalGlobal += $o['total_monto'] ?? 0;
                $totalEstGlobal += $o['total_estudiantes'] ?? 0;
            }
        @endphp

        {{-- Resumen superior --}}
        <div class="fin-stats">
            <div class="fin-stat-card">
                <div class="fin-stat-icon primary"><i class="ri-book-2-line"></i></div>
                <div>
                    <div class="fin-stat-lbl">Ofertas con deuda</div>
                    <div class="fin-stat-val">{{ count($resultados) }}</div>
                </div>
            </div>
            <div class="fin-stat-card">
                <div class="fin-stat-icon warning"><i class="ri-group-line"></i></div>
                <div>
                    <div class="fin-stat-lbl">Estudiantes con cuotas vencidas</div>
                    <div class="fin-stat-val">{{ $totalEstGlobal }}</div>
                </div>
            </div>
            <div class="fin-stat-card">
                <div class="fin-stat-icon danger"><i class="ri-money-dollar-circle-line"></i></div>
                <div>
                    <div class="fin-stat-lbl">Total vencido</div>
                    <div class="fin-stat-val">Bs {{ number_format($totalGlobal, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="fin-layout">

            {{-- Sidebar de ofertas --}}
            <aside class="ofertas-sidebar">
                <div class="ofertas-sidebar-head">
                    <span class="ofertas-sidebar-title"><i class="ri-list-unordered"></i> Ofertas</span>
                    <span class="ofertas-sidebar-count">{{ count($resultados) }}</span>
                </div>
                <div class="ofertas-search">
                    <i class="ri-search-line"></i>
                    <input type="text" id="ofertaSearchInput" placeholder="Buscar oferta...">
                </div>
                <div class="ofertas-list" id="ofertasList">
                    @foreach($resultados as $index => $oferta)
                        <div class="oferta-item {{ $index === 0 ? 'active' : '' }}"
                             data-target="tab-content-{{ $oferta['oferta_id'] }}"
                             data-name="{{ Str::lower($oferta['oferta_nombre']) }}">
                            <div class="oferta-item-icon"><i class="ri-book-2-line"></i></div>
                            <div class="oferta-item-body">
                                <div class="oferta-item-nombre">{{ $oferta['oferta_nombre'] }}</div>
                                <div class="oferta-item-meta">
                                    <span class="oferta-item-chip">
                                        <i class="ri-user-line"></i> {{ $oferta['total_estudiantes'] }}
                                    </span>
                                    <span class="oferta-item-chip monto">
                                        Bs {{ number_format($oferta['total_monto'], 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="ofertas-empty-search d-none" id="ofertasEmptySearch">
                        <i class="ri-search-line" style="font-size:1.4rem;display:block;margin-bottom:6px;"></i>
                        Sin coincidencias
                    </div>
                </div>
            </aside>

            {{-- Panel principal --}}
            <div class="ofertas-panels" id="ofertasPanels">
            @foreach($resultados as $index => $oferta)
                <div class="oferta-panel {{ $index === 0 ? '' : 'd-none' }}"
                     id="tab-content-{{ $oferta['oferta_id'] }}">
                    <div class="tab-panel-card">
                        <div class="panel-header">
                            <h5 class="panel-title">
                                <i class="ri-book-2-line"></i>{{ $oferta['oferta_nombre'] }}
                            </h5>
                            <div class="panel-actions">
                                <div class="badge-total">
                                    <i class="ri-money-dollar-circle-line"></i>
                                    Total: Bs {{ number_format($oferta['total_monto'], 2) }}
                                </div>
                                <button class="btn-wa-all"
                                    onclick="enviarWaOferta({{ $oferta['oferta_id'] }})">
                                    <i class="ri-whatsapp-line"></i> Avisar a todos
                                </button>
                            </div>
                        </div>

                        @foreach($oferta['estudiantes'] as $est)
                            <div class="est-card">
                                <div class="est-main">
                                    <div class="est-avatar">{{ substr($est['nombre'], 0, 1) }}</div>
                                    <div class="est-info">
                                        <div class="est-nombre">{{ $est['nombre'] }}</div>
                                        @if($est['celular'])
                                            <div class="est-celular">
                                                <i class="ri-phone-line"></i>{{ $est['celular'] }}
                                            </div>
                                        @endif
                                        <div class="est-deuda">
                                            <div class="deuda-label">Total Vencido</div>
                                            <div class="deuda-value">Bs {{ number_format($est['monto_total'], 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="est-actions">
                                        @if($est['celular'])
                                            <button class="btn-act btn-wa"
                                                onclick="enviarWaEstudiante({{ $oferta['oferta_id'] }}, {{ $est['estudiante_id'] }})"
                                                title="Enviar por WhatsApp">
                                                <i class="ri-whatsapp-line"></i>
                                            </button>
                                        @endif
                                        <button class="btn-act btn-cuotas"
                                            onclick="verCuotas({{ $oferta['oferta_id'] }}, {{ $est['estudiante_id'] }})">
                                            <i class="ri-eye-line"></i> Ver ({{ $est['retrasadas'] }})
                                        </button>
                                    </div>
                                </div>
                                <div class="est-resumen">
                                    <span class="est-badge retrasada">
                                        <i class="ri-error-warning-line"></i>
                                        {{ $est['retrasadas'] }} cuota(s) vencida(s)
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    @endif
</div>

{{-- Modal resultado WhatsApp masivo --}}
<div class="modal fade" id="modalWaResultado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content" style="border:none;border-radius:var(--radius-lg);overflow:hidden;">
            <div id="modalWaResultadoHeader" class="text-center px-4 py-4"
                 style="background:linear-gradient(135deg,#25D366 0%,#128C7E 100%);color:#fff;">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                     style="width:64px;height:64px;background:rgba(255,255,255,.2);">
                    <i id="modalWaResultadoIcon" class="ri-whatsapp-line" style="font-size:2rem;"></i>
                </div>
                <h5 id="modalWaResultadoTitle" class="fw-bold text-white mb-1">Mensajes abiertos</h5>
                <p id="modalWaResultadoSubtitle" class="mb-0" style="opacity:.9;font-size:.875rem;">—</p>
            </div>
            <div class="modal-body p-4" style="background:var(--fin-surface-2);">
                <div id="modalWaResultadoBody" class="d-flex flex-column gap-2"></div>
            </div>
            <div class="modal-footer" style="background:var(--fin-surface);border-top:1px solid var(--fin-border);">
                <button class="btn btn-sm fw-semibold" data-bs-dismiss="modal"
                        style="background:var(--fin-primary);color:#fff;border-radius:var(--radius-md);padding:8px 20px;">
                    <i class="ri-check-line me-1"></i> Entendido
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal cuotas --}}
<div class="modal fade" id="modalCuotas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-fin-header">
                <h5 class="modal-title"><i class="ri-calendar-line me-2"></i>Cuotas Vencidas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalCuotasBody"></div>
            <div class="modal-footer" style="background:var(--fin-surface);border-top:1px solid var(--fin-border);">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const deudasData = @json($resultados);

    const modalCuotas = new bootstrap.Modal(document.getElementById('modalCuotas'));

    // ── Switcheo de ofertas (sidebar) ──
    document.querySelectorAll('.oferta-item').forEach(item => {
        item.addEventListener('click', function () {
            const target = this.dataset.target;
            document.querySelectorAll('.oferta-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.oferta-panel').forEach(p => p.classList.add('d-none'));
            const panel = document.getElementById(target);
            if (panel) {
                panel.classList.remove('d-none');
                if (window.innerWidth <= 992) {
                    panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });

    // ── Buscador de ofertas ──
    const searchInput = document.getElementById('ofertaSearchInput');
    const emptySearch = document.getElementById('ofertasEmptySearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            let visibles = 0;
            document.querySelectorAll('#ofertasList .oferta-item').forEach(item => {
                const match = !q || (item.dataset.name || '').includes(q);
                item.style.display = match ? '' : 'none';
                if (match) visibles++;
            });
            if (emptySearch) emptySearch.classList.toggle('d-none', visibles > 0);
        });
    }

    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
    }

    function formatFecha(fechaStr) {
        if (!fechaStr) return '—';
        const [y, m, d] = fechaStr.split('-');
        const meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
        return `${parseInt(d)} ${meses[parseInt(m)-1]} ${y}`;
    }

    function verCuotas(ofertaId, estudianteId) {
        const oferta = deudasData.find(o => o.oferta_id === ofertaId);
        const est = oferta?.estudiantes.find(e => e.estudiante_id === estudianteId);
        if (!est) return;

        const cuotas = est.cuotas;
        let html = cuotas.map(c => `
            <div class="modal-cuota-item">
                <div class="d-flex align-items-center">
                    <span class="cuota-num">${c.n_cuota}</span>
                    <div>
                        <div class="cuota-nombre">${escHtml(c.nombre)}</div>
                        <div class="cuota-fecha"><i class="ri-time-line me-1"></i>Vencía: ${formatFecha(c.fecha_pago)}</div>
                    </div>
                </div>
                <div class="cuota-monto">Bs ${Number(c.monto_bs).toFixed(2)}</div>
            </div>`).join('');

        const total = cuotas.reduce((s, c) => s + c.monto_bs, 0);
        html += `<div class="modal-resumen d-flex justify-content-between align-items-center">
            <strong style="color:var(--fin-text);">${cuotas.length} cuota(s) vencida(s)</strong>
            <span style="font-size:1.2rem;font-weight:700;color:var(--fin-danger);">Bs ${total.toFixed(2)}</span>
        </div>`;

        document.getElementById('modalCuotasBody').innerHTML = html;
        modalCuotas.show();
    }

    function enviarWaEstudiante(ofertaId, estudianteId) {
        const oferta = deudasData.find(o => o.oferta_id === ofertaId);
        const est = oferta?.estudiantes.find(e => e.estudiante_id === estudianteId);
        if (!est?.celular) return;

        const total = est.cuotas.reduce((s, c) => s + c.monto_bs, 0);
        let detalle = '';
        est.cuotas.forEach(c => {
            detalle += `• ${escHtml(c.nombre)} (Cuota ${c.n_cuota}): Bs ${Number(c.monto_bs).toFixed(2)} - venció el ${formatFecha(c.fecha_pago)}\n`;
        });

        const msg = `Estimado/a *${est.nombre}*, le comunicamos que tiene las siguientes cuotas *VENCIDAS* en *${oferta.oferta_nombre}*:\n\n${detalle}\n*TOTAL: Bs ${total.toFixed(2)}*\n\nPor favor realice el pago a la brevedad posible.\n\n_Área Financiera - Innova Ciencia Virtual_`;
        window.open(`https://wa.me/591${est.celular.replace(/\D/g,'')}?text=${encodeURIComponent(msg)}`, '_blank');
    }

    function enviarWaOferta(ofertaId) {
        const oferta = deudasData.find(o => o.oferta_id === ofertaId);
        if (!oferta) return;
        let enviados = 0, sinCelular = 0;
        oferta.estudiantes.forEach(est => {
            if (!est.celular) { sinCelular++; return; }
            const total = est.cuotas.reduce((s, c) => s + c.monto_bs, 0);
            let detalle = '';
            est.cuotas.forEach(c => {
                detalle += `• ${c.nombre} (Cuota ${c.n_cuota}): Bs ${Number(c.monto_bs).toFixed(2)} - venció el ${formatFecha(c.fecha_pago)}\n`;
            });
            const msg = `Estimado/a *${est.nombre}*, le comunicamos que tiene cuotas *VENCIDAS* en *${oferta.oferta_nombre}*:\n\n${detalle}\n*TOTAL: Bs ${total.toFixed(2)}*\n\nPor favor realice el pago a la brevedad posible.\n\n_Área Financiera - Innova Ciencia Virtual_`;
            window.open(`https://wa.me/591${est.celular.replace(/\D/g,'')}?text=${encodeURIComponent(msg)}`, '_blank');
            enviados++;
        });
        mostrarModalWaResultado({
            enviados: enviados,
            sinCelular: sinCelular,
            oferta: oferta.oferta_nombre
        });
    }

    const modalWaResultado = new bootstrap.Modal(document.getElementById('modalWaResultado'));

    function mostrarModalWaResultado({ enviados, sinCelular, oferta }) {
        const header = document.getElementById('modalWaResultadoHeader');
        const icon   = document.getElementById('modalWaResultadoIcon');
        const title  = document.getElementById('modalWaResultadoTitle');
        const sub    = document.getElementById('modalWaResultadoSubtitle');
        const body   = document.getElementById('modalWaResultadoBody');

        const exito = enviados > 0;
        header.style.background = exito
            ? 'linear-gradient(135deg,#25D366 0%,#128C7E 100%)'
            : 'linear-gradient(135deg,#f59e0b 0%,#b45309 100%)';
        icon.className = exito ? 'ri-checkbox-circle-line' : 'ri-error-warning-line';
        icon.style.fontSize = '2rem';
        title.textContent = exito ? '¡Mensajes abiertos!' : 'Sin celulares registrados';
        sub.textContent = exito
            ? 'Se abrieron las ventanas de WhatsApp.'
            : 'No hay estudiantes con celular en esta oferta.';

        const items = [
            {
                ico: 'ri-book-2-line',
                lbl: 'Oferta',
                val: escHtml(oferta),
                cls: 'primary'
            },
            {
                ico: 'ri-whatsapp-line',
                lbl: 'Mensajes abiertos',
                val: enviados,
                cls: 'success'
            }
        ];
        if (sinCelular > 0) {
            items.push({
                ico: 'ri-phone-off-line',
                lbl: 'Sin celular registrado',
                val: sinCelular,
                cls: 'warning'
            });
        }

        const colorMap = {
            primary: { bg: 'var(--fin-primary-light)', fg: 'var(--fin-primary-dark)' },
            success: { bg: 'rgba(37,211,102,.12)',     fg: '#128C7E' },
            warning: { bg: 'rgba(245,158,11,.12)',     fg: '#b45309' }
        };

        body.innerHTML = items.map(it => {
            const c = colorMap[it.cls];
            return `
                <div class="d-flex align-items-center gap-3 p-3"
                     style="background:var(--fin-surface);border:1px solid var(--fin-border);border-radius:var(--radius-md);">
                    <div class="d-flex align-items-center justify-content-center rounded-3"
                         style="width:38px;height:38px;background:${c.bg};color:${c.fg};font-size:1.05rem;flex-shrink:0;">
                        <i class="${it.ico}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--fin-text-muted);">${it.lbl}</div>
                        <div style="font-size:.95rem;font-weight:600;color:var(--fin-text);word-break:break-word;">${it.val}</div>
                    </div>
                </div>
            `;
        }).join('');

        modalWaResultado.show();
    }
</script>
@endpush
@endsection
