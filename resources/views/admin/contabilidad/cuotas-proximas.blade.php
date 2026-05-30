@extends('layouts.master')

@section('title') Cuotas Próximas a Vencer @endsection

@section('content')
<style>
    :root {
        --fin-primary: #fc7b04;
        --fin-primary-dark: #c25e00;
        --fin-primary-light: #fff3e0;
        --fin-warning: #f59e0b;
        --fin-warning-dark: #b45309;
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
        --fin-warning-light: rgba(245,158,11,.12);
    }
    .fin-page { animation: finFadeIn .45s ease-out; }
    @keyframes finFadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }

    .fin-header {
        position:relative; overflow:hidden;
        display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:20px;
        padding:32px 36px; margin-bottom:28px;
        background:linear-gradient(135deg, #b45309 0%, #78350f 100%);
        border-radius:var(--radius-xl); color:#fff;
    }
    .fin-header::before {
        content:''; position:absolute; top:-50%; right:-10%;
        width:400px; height:400px;
        background:radial-gradient(circle,rgba(245,158,11,.2) 0%,transparent 70%);
        pointer-events:none;
    }
    .fin-header-content { position:relative; z-index:1; }
    .fin-header h1 { margin:0; font-size:1.75rem; font-weight:700; display:flex; align-items:center; gap:12px; color:#fff; }
    .fin-header h1 i { color:#fcd34d; }
    .fin-header p { margin:8px 0 0; opacity:.85; font-size:.95rem; }
    .fin-header-meta {
        position:relative; z-index:1;
        background:rgba(255,255,255,.12); backdrop-filter:blur(8px);
        padding:12px 20px; border-radius:var(--radius-md); border:1px solid rgba(255,255,255,.2);
        font-size:.85rem;
    }
    .fin-header-meta i { color:#fcd34d; margin-right:6px; }

    .fin-filter-bar {
        background:var(--fin-surface-2); border:1px solid var(--fin-border);
        border-radius:var(--radius-lg); padding:16px 20px; margin-bottom:24px;
        display:flex; align-items:center; gap:12px; flex-wrap:wrap;
    }
    .fin-filter-bar label { font-weight:600; font-size:.85rem; color:var(--fin-text-muted); white-space:nowrap; }
    .fin-filter-bar select { border-radius:var(--radius-sm); border:1px solid var(--fin-border); padding:8px 12px; font-size:.875rem; min-width:220px; background:var(--fin-surface); color:var(--fin-text); }
    .fin-filter-bar select:focus { outline:none; border-color:var(--fin-warning); box-shadow:0 0 0 3px rgba(245,158,11,.15); }

    .fin-tabs { overflow-x:auto; padding-bottom:4px; margin-bottom:24px; }
    .fin-tabs .nav-tabs { display:flex; gap:6px; border:none; min-width:max-content; }
    .fin-tabs .nav-link {
        border:none; border-radius:var(--radius-md); color:var(--fin-text-muted);
        font-weight:500; font-size:.875rem; padding:12px 22px;
        background:var(--fin-surface); white-space:nowrap;
        display:flex; align-items:center; gap:8px; transition:all .25s;
    }
    .fin-tabs .nav-link:hover { color:var(--fin-warning-dark); background:var(--fin-warning-light); transform:translateY(-2px); }
    .fin-tabs .nav-link.active {
        color:#fff; background:linear-gradient(135deg,var(--fin-warning) 0%,var(--fin-warning-dark) 100%);
        box-shadow:0 4px 12px rgba(245,158,11,.35); transform:translateY(-2px);
    }
    .tab-count {
        background:rgba(245,158,11,.15); color:var(--fin-warning-dark);
        padding:2px 9px; border-radius:20px; font-size:.72rem; font-weight:700;
    }
    .fin-tabs .nav-link.active .tab-count { background:rgba(255,255,255,.25); color:#fff; }

    .tab-panel-card {
        background:var(--fin-surface-2); border:1px solid var(--fin-border);
        border-radius:var(--radius-xl); padding:24px; box-shadow:var(--shadow-md);
    }
    .panel-header {
        display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;
        margin-bottom:24px; padding-bottom:20px; border-bottom:1px solid var(--fin-border);
    }
    .panel-title { margin:0; font-size:1.15rem; font-weight:600; color:var(--fin-text); display:flex; align-items:center; gap:10px; }
    .panel-title i { color:var(--fin-warning); }
    .panel-actions { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
    .badge-total {
        background:linear-gradient(135deg,#78350f 0%,#b45309 100%);
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
        background:linear-gradient(180deg,var(--fin-warning) 0%,transparent 100%);
        opacity:0; transition:opacity .3s;
    }
    .est-card:hover { box-shadow:var(--shadow-lg); transform:translateY(-2px); border-color:rgba(245,158,11,.25); }
    .est-card:hover::before { opacity:1; }

    .est-main { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:18px; }
    .est-avatar {
        width:50px; height:50px; border-radius:50%; flex-shrink:0;
        background:linear-gradient(135deg,#b45309 0%,var(--fin-warning) 100%);
        display:flex; align-items:center; justify-content:center;
        color:#fff; font-size:1.2rem; font-weight:700;
    }
    .est-info { flex:1; min-width:180px; }
    .est-nombre { font-weight:600; font-size:1rem; color:var(--fin-text); margin-bottom:3px; }
    .est-celular { color:var(--fin-text-muted); font-size:.875rem; display:flex; align-items:center; gap:5px; }
    .est-celular i { color:var(--fin-primary); }
    .est-deuda { text-align:right; margin-top:6px; }
    .deuda-label { font-size:.72rem; color:var(--fin-text-muted); text-transform:uppercase; letter-spacing:.05em; }
    .deuda-value { font-size:1.3rem; font-weight:700; color:var(--fin-warning-dark); }
    .est-actions { display:flex; gap:8px; flex-wrap:wrap; }
    .btn-act {
        padding:9px 15px; border-radius:var(--radius-md); font-size:.82rem; font-weight:600;
        cursor:pointer; display:flex; align-items:center; gap:6px; border:none; transition:all .25s;
    }
    .btn-act:hover { transform:translateY(-2px); }
    .btn-wa { background:#25D366; color:#fff; box-shadow:0 3px 10px rgba(37,211,102,.25); }
    .btn-wa:hover { background:#128C7E; color:#fff; }
    .btn-cuotas {
        background:linear-gradient(135deg,var(--fin-warning) 0%,var(--fin-warning-dark) 100%);
        color:#fff; box-shadow:0 3px 10px rgba(245,158,11,.3);
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
    .est-badge.proxima {
        background:rgba(245,158,11,.1); color:var(--fin-warning-dark);
        border:1px solid rgba(245,158,11,.2);
    }
    .dias-urgente { color:#ef4444; font-weight:700; }

    .empty-state {
        text-align:center; padding:72px 40px;
        background:var(--fin-surface-2); border:1px solid var(--fin-border); border-radius:var(--radius-xl);
    }
    .empty-icon {
        width:90px; height:90px; border-radius:50%;
        background:linear-gradient(135deg,var(--fin-warning-light) 0%,rgba(245,158,11,.05) 100%);
        display:inline-flex; align-items:center; justify-content:center; margin-bottom:20px;
    }
    .empty-icon i { font-size:2.8rem; color:var(--fin-warning); }

    .modal-fin-header {
        background:linear-gradient(135deg,#b45309 0%,#78350f 100%);
        color:#fff; border-radius:var(--radius-lg) var(--radius-lg) 0 0; border:none; padding:18px 24px;
    }
    .modal-fin-header .modal-title { color:#fff; font-weight:600; }
    .modal-cuota-item {
        display:flex; justify-content:space-between; align-items:center;
        padding:14px; margin-bottom:10px; background:var(--fin-surface);
        border-radius:var(--radius-md); border:1px solid transparent; transition:all .2s;
    }
    .modal-cuota-item:hover { border-color:rgba(245,158,11,.25); }
    .cuota-num {
        width:34px; height:34px; border-radius:50%; flex-shrink:0; margin-right:12px;
        background:linear-gradient(135deg,var(--fin-warning) 0%,var(--fin-warning-dark) 100%);
        color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:600;
    }
    .cuota-nombre { font-weight:600; color:var(--fin-text); font-size:.9rem; }
    .cuota-fecha-prox { font-size:.82rem; margin-top:2px; color:var(--fin-warning-dark); font-weight:500; }
    .cuota-fecha-urg  { font-size:.82rem; margin-top:2px; color:#ef4444; font-weight:700; }
    .cuota-monto { font-size:1.05rem; font-weight:700; color:var(--fin-warning-dark); }
    .modal-resumen {
        background:linear-gradient(135deg,rgba(245,158,11,.08) 0%,rgba(180,83,9,.03) 100%);
        padding:18px; border-radius:var(--radius-md); margin-top:14px;
        border:1px solid rgba(245,158,11,.2);
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
            <h1><i class="ri-time-line"></i>Cuotas Próximas a Vencer</h1>
            <p>Estudiantes con cuotas que vencen en los próximos 7 días</p>
        </div>
        <div class="fin-header-meta">
            <i class="ri-calendar-line"></i> {{ now()->format('d/m/Y') }}
            &nbsp;→&nbsp; {{ now()->addDays(7)->format('d/m/Y') }}
        </div>
    </div>

    {{-- FILTRO --}}
    <form method="GET" action="{{ route('admin.contabilidad.cuotas-proximas') }}" class="fin-filter-bar">
        <i class="ri-filter-3-line" style="color:var(--fin-warning);font-size:1.1rem;"></i>
        <label>Filtrar por oferta:</label>
        <select name="oferta_id" onchange="this.form.submit()">
            <option value="">— Todas las ofertas —</option>
            @foreach($todasOfertas as $id => $nombre)
                <option value="{{ $id }}" {{ request('oferta_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
            @endforeach
        </select>
        @if(request('oferta_id'))
            <a href="{{ route('admin.contabilidad.cuotas-proximas') }}"
               class="btn btn-sm btn-light d-flex align-items-center gap-1">
                <i class="ri-close-line"></i> Limpiar
            </a>
        @endif
    </form>

    @if(empty($resultados))
        <div class="empty-state">
            <div class="empty-icon"><i class="ri-checkbox-circle-line"></i></div>
            <h3 style="font-weight:600;font-size:1.4rem;color:var(--fin-text);margin-bottom:6px;">
                Sin cuotas próximas a vencer
            </h3>
            <p style="color:var(--fin-text-muted);">No hay cuotas que venzan en los próximos 7 días.</p>
        </div>
    @else
        <div class="fin-tabs">
            <ul class="nav nav-tabs" id="proxTabs" role="tablist">
                @foreach($resultados as $index => $oferta)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-content-{{ $oferta['oferta_id'] }}"
                            type="button" role="tab">
                            {{ Str::limit($oferta['oferta_nombre'], 28) }}
                            <span class="tab-count">{{ $oferta['total_estudiantes'] }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="tab-content">
            @foreach($resultados as $index => $oferta)
                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                     id="tab-content-{{ $oferta['oferta_id'] }}" role="tabpanel">
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
                                    <i class="ri-whatsapp-line"></i> Recordar a todos
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
                                            <div class="deuda-label">Monto Próximo</div>
                                            <div class="deuda-value">Bs {{ number_format($est['monto_total'], 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="est-actions">
                                        @if($est['celular'])
                                            <button class="btn-act btn-wa"
                                                onclick="enviarWaEstudiante({{ $oferta['oferta_id'] }}, {{ $est['estudiante_id'] }})"
                                                title="Enviar recordatorio WhatsApp">
                                                <i class="ri-whatsapp-line"></i>
                                            </button>
                                        @endif
                                        <button class="btn-act btn-cuotas"
                                            onclick="verCuotas({{ $oferta['oferta_id'] }}, {{ $est['estudiante_id'] }})">
                                            <i class="ri-eye-line"></i> Ver ({{ $est['proximas'] }})
                                        </button>
                                    </div>
                                </div>
                                <div class="est-resumen">
                                    <span class="est-badge proxima">
                                        <i class="ri-time-line"></i>
                                        {{ $est['proximas'] }} cuota(s) por vencer
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Modal cuotas --}}
<div class="modal fade" id="modalCuotas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-fin-header">
                <h5 class="modal-title"><i class="ri-calendar-line me-2"></i>Cuotas Próximas a Vencer</h5>
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

    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
    }
    function formatFecha(fechaStr) {
        if (!fechaStr) return '—';
        const [y, m, d] = String(fechaStr).split('-');
        const meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
        return `${parseInt(d)} ${meses[parseInt(m)-1]} ${y}`;
    }

    function verCuotas(ofertaId, estudianteId) {
        const oferta = deudasData.find(o => o.oferta_id === ofertaId);
        const est = oferta?.estudiantes.find(e => e.estudiante_id === estudianteId);
        if (!est) return;

        const cuotas = est.cuotas;
        let html = cuotas.map(c => {
            const dias = c.dias_restantes;
            const urgente = dias <= 3;
            const clsFecha = urgente ? 'cuota-fecha-urg' : 'cuota-fecha-prox';
            const textoFecha = urgente
                ? `<i class="ri-alarm-warning-line me-1"></i>URGENTE — vence en ${dias} día(s): ${formatFecha(c.fecha_pago)}`
                : `<i class="ri-time-line me-1"></i>Vence en ${dias} día(s): ${formatFecha(c.fecha_pago)}`;
            return `
            <div class="modal-cuota-item">
                <div class="d-flex align-items-center">
                    <span class="cuota-num">${c.n_cuota}</span>
                    <div>
                        <div class="cuota-nombre">${escHtml(c.nombre)}</div>
                        <div class="${clsFecha}">${textoFecha}</div>
                    </div>
                </div>
                <div class="cuota-monto">Bs ${Number(c.monto_bs).toFixed(2)}</div>
            </div>`;
        }).join('');

        const total = cuotas.reduce((s, c) => s + c.monto_bs, 0);
        html += `<div class="modal-resumen d-flex justify-content-between align-items-center">
            <strong style="color:var(--fin-text);">${cuotas.length} cuota(s) próxima(s) a vencer</strong>
            <span style="font-size:1.2rem;font-weight:700;color:var(--fin-warning-dark);">Bs ${total.toFixed(2)}</span>
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
            detalle += `• ${c.nombre} (Cuota ${c.n_cuota}): Bs ${Number(c.monto_bs).toFixed(2)} — vence el ${formatFecha(c.fecha_pago)} (${c.dias_restantes} día(s))\n`;
        });

        const msg = `Estimado/a *${est.nombre}*, le recordamos que tiene las siguientes cuotas *PRÓXIMAS A VENCER* en *${oferta.oferta_nombre}*:\n\n${detalle}\n*TOTAL: Bs ${total.toFixed(2)}*\n\nPor favor realice el pago con anticipación.\n\n_Área Financiera - Innova Ciencia Virtual_`;
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
                detalle += `• ${c.nombre} (Cuota ${c.n_cuota}): Bs ${Number(c.monto_bs).toFixed(2)} — vence el ${formatFecha(c.fecha_pago)} (${c.dias_restantes} día(s))\n`;
            });
            const msg = `Estimado/a *${est.nombre}*, le recordamos cuotas *PRÓXIMAS A VENCER* en *${oferta.oferta_nombre}*:\n\n${detalle}\n*TOTAL: Bs ${total.toFixed(2)}*\n\nPor favor realice el pago con anticipación.\n\n_Área Financiera - Innova Ciencia Virtual_`;
            window.open(`https://wa.me/591${est.celular.replace(/\D/g,'')}?text=${encodeURIComponent(msg)}`, '_blank');
            enviados++;
        });
        if (enviados > 0) alert(`${enviados} recordatorio(s) abierto(s)${sinCelular ? `, ${sinCelular} sin celular` : ''}.`);
        else alert('No hay estudiantes con celular registrado en esta oferta.');
    }
</script>
@endpush
@endsection
