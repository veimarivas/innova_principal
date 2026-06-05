@extends('layouts.master')
@section('title')
    Buscar Estudiante - Gestión Financiera
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
    /* ── Apartado de ofertas académicas (búsqueda) ─────────────── */
    .ofertas-academicas-wrap {
        border-top: 1px solid #e2e8f0;
    }
    .ofertas-academicas-head {
        display: flex; align-items: center; gap: .45rem;
        font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em;
        color: #475569;
        margin-bottom: .6rem;
    }
    .ofertas-academicas-head i { color: #fc7b04; font-size: .95rem; }
    .ofertas-academicas-count {
        margin-left: auto;
        background: rgba(252,123,4,.12);
        color: #c96004;
        font-size: .65rem; font-weight: 800;
        padding: 1px 8px; border-radius: 20px;
    }
    .ofertas-academicas-grid {
        display: flex; flex-direction: column; gap: .55rem;
    }
    .oferta-acad-card {
        position: relative;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: .75rem .9rem;
        transition: border-color .2s ease, box-shadow .2s ease, transform .15s ease;
        overflow: hidden;
    }
    .oferta-acad-card::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px;
        background: #fc7b04;
        border-radius: 0 4px 4px 0;
    }
    .oferta-acad-card.oferta-al-dia::before    { background: #16a34a; }
    .oferta-acad-card.oferta-pendiente::before { background: #d97706; }
    .oferta-acad-card.oferta-sin-pago::before  { background: #dc2626; }
    .oferta-acad-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 14px rgba(15,23,42,.08);
        transform: translateY(-1px);
    }
    .oferta-acad-head {
        display: flex; justify-content: space-between; align-items: flex-start;
        gap: .6rem; margin-bottom: .55rem;
    }
    .oferta-acad-titulo {
        display: flex; flex-direction: column; gap: .2rem;
        min-width: 0; flex: 1;
    }
    .oferta-acad-codigo {
        display: inline-flex; align-items: center;
        font-family: 'Outfit', sans-serif;
        font-size: .65rem; font-weight: 800;
        background: rgba(252,123,4,.12); color: #c96004;
        padding: 2px 8px; border-radius: 6px;
        letter-spacing: .03em; align-self: flex-start;
    }
    .oferta-acad-nombre {
        font-size: .85rem; font-weight: 700; color: #0f172a;
        line-height: 1.3;
        word-break: break-word;
    }
    .oferta-acad-estado {
        display: inline-flex; align-items: center; gap: .25rem;
        font-size: .65rem; font-weight: 700;
        padding: .2rem .55rem; border-radius: 20px;
        white-space: nowrap; flex-shrink: 0;
        text-transform: uppercase; letter-spacing: .03em;
    }
    .oferta-acad-estado i { font-size: .68rem; }
    .oferta-acad-estado.al-dia    { background: rgba(34,197,94,.13); color: #15803d; }
    .oferta-acad-estado.pendiente { background: rgba(217,119,6,.13); color: #b45309; }
    .oferta-acad-estado.sin-pago  { background: rgba(220,38,38,.13); color: #b91c1c; }
    .oferta-acad-progress {
        display: flex; align-items: center; gap: .55rem;
        margin-bottom: .65rem;
    }
    .oferta-acad-progress-bar {
        flex: 1;
        height: 6px; border-radius: 4px;
        background: #f1f5f9; overflow: hidden;
    }
    .oferta-acad-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #16a34a, #22c55e);
        border-radius: 4px;
        transition: width .4s ease;
    }
    .oferta-acad-card.oferta-pendiente .oferta-acad-progress-fill {
        background: linear-gradient(90deg, #d97706, #f59e0b);
    }
    .oferta-acad-card.oferta-sin-pago .oferta-acad-progress-fill {
        background: linear-gradient(90deg, #dc2626, #ef4444);
    }
    .oferta-acad-progress-pct {
        font-family: 'Outfit', sans-serif;
        font-size: .72rem; font-weight: 800;
        color: #475569;
        min-width: 36px; text-align: right;
    }
    .oferta-acad-stats {
        display: flex; align-items: center; gap: .85rem;
    }
    .oferta-acad-stat {
        display: flex; flex-direction: column; line-height: 1.2;
    }
    .oferta-acad-stat-lbl {
        font-size: .62rem; color: #94a3b8;
        text-transform: uppercase; letter-spacing: .04em; font-weight: 600;
    }
    .oferta-acad-stat-val {
        font-family: 'Outfit', sans-serif;
        font-size: .82rem; font-weight: 700;
    }
    .oferta-acad-stat-val.pagado { color: #15803d; }
    .oferta-acad-stat-val.saldo  { color: #b45309; }
    .oferta-acad-card.oferta-al-dia .oferta-acad-stat-val.saldo { color: #16a34a; }
    .oferta-acad-card.oferta-sin-pago .oferta-acad-stat-val.saldo { color: #b91c1c; }
    .oferta-acad-pagar {
        margin-left: auto;
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .45rem .85rem; border: none;
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: #fff;
        font-size: .76rem; font-weight: 700;
        border-radius: 8px;
        cursor: pointer;
        transition: transform .15s ease, box-shadow .2s ease;
        box-shadow: 0 3px 10px rgba(22,163,74,.28);
    }
    .oferta-acad-pagar:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(22,163,74,.36);
    }
    .oferta-acad-done {
        margin-left: auto;
        display: inline-flex; align-items: center; gap: .3rem;
        font-size: .76rem; font-weight: 700; color: #15803d;
        padding: .4rem .7rem;
        background: rgba(34,197,94,.1);
        border-radius: 8px;
    }

    /* ══════════════════════════════════════════════════════════
       Modal Pago Masivo — diseño con paleta cálida del sistema
    ══════════════════════════════════════════════════════════ */
    .pmp-modal .modal-dialog { max-width: 1100px; }
    .pmp-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 25px 70px rgba(154,73,4,.18), 0 8px 24px rgba(0,0,0,.08);
    }

    /* ── Header ── */
    .pmp-header {
        position: relative;
        display: flex; align-items: center; gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #5c2a04 0%, #9a4904 45%, #c96004 75%, #fc7b04 100%);
        overflow: hidden;
    }
    .pmp-header::before {
        content: '';
        position: absolute; top: -50%; right: -8%;
        width: 280px; height: 280px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.14) 0%, transparent 70%);
        pointer-events: none;
    }
    .pmp-header-icon {
        width: 52px; height: 52px;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.28);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.55rem;
        flex-shrink: 0; z-index: 1;
        box-shadow: 0 4px 14px rgba(0,0,0,.18);
    }
    .pmp-header-text { z-index: 1; min-width: 0; flex: 1; }
    .pmp-header-title {
        font-family: 'Outfit', sans-serif;
        color: #fff; font-weight: 800; font-size: 1.15rem;
        margin: 0 0 2px;
        letter-spacing: -.015em;
    }
    .pmp-header-sub {
        color: rgba(255,255,255,.85);
        font-size: .82rem; font-weight: 500;
        display: block;
    }
    .pmp-close-btn {
        z-index: 1; flex-shrink: 0;
        width: 36px; height: 36px;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.28);
        border-radius: 9px;
        color: #fff; font-size: 1.15rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background .2s;
    }
    .pmp-close-btn:hover { background: rgba(255,255,255,.3); }

    /* ── Body ── */
    .pmp-body {
        padding: 1.25rem 1.5rem;
        background: #faf7f3;
    }
    .pmp-section { margin-bottom: 1.15rem; }
    .pmp-section:last-of-type { margin-bottom: 0; }
    .pmp-section-title {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .7rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .07em;
        color: #c96004;
        margin-bottom: .6rem;
        padding: .3rem .65rem;
        background: rgba(252,123,4,.08);
        border-radius: 6px;
    }
    .pmp-section-title i { font-size: .9rem; }

    /* ── Inputs ── */
    .pmp-label {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .04em;
        color: #475569;
        margin-bottom: .35rem;
    }
    .pmp-label i { color: #fc7b04; font-size: .85rem; }
    .pmp-label span { color: #94a3b8; font-weight: 600; text-transform: none; letter-spacing: 0; }

    .pmp-input.form-control,
    .pmp-input.form-select {
        background: #fff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: .6rem .85rem !important;
        font-size: .88rem !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        transition: border-color .2s, box-shadow .2s !important;
        font-family: inherit !important;
    }
    .pmp-input.form-control:focus,
    .pmp-input.form-select:focus {
        border-color: #fc7b04 !important;
        box-shadow: 0 0 0 4px rgba(252,123,4,.14) !important;
        outline: none !important;
    }

    /* ── Cuotas — tabla con look de tarjeta del programa ── */
    .pmp-cuotas-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        max-height: 340px; overflow-y: auto;
    }
    .pmp-cuotas-wrap h6 { display: none; } /* el subtítulo que mete el JS lo ocultamos: ya tenemos la cabecera de sección */
    .pmp-cuotas-wrap #tabla-cuotas-pendientes {
        margin: 0;
        font-size: .82rem;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .pmp-cuotas-wrap #tabla-cuotas-pendientes thead th {
        position: sticky; top: 0; z-index: 2;
        background: linear-gradient(180deg, #f8f5f1 0%, #f1ebe2 100%);
        color: #6b3102;
        font-size: .65rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .05em;
        padding: .65rem .75rem;
        border-bottom: 2px solid rgba(252,123,4,.18);
        white-space: nowrap;
    }
    .pmp-cuotas-wrap #tabla-cuotas-pendientes tbody td {
        padding: .65rem .75rem;
        border-top: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
    }
    .pmp-cuotas-wrap #tabla-cuotas-pendientes tbody tr:hover td {
        background: rgba(252,123,4,.04);
    }
    .pmp-cuotas-wrap #tabla-cuotas-pendientes tbody tr.cuota-incluida td {
        background: rgba(34,197,94,.06);
    }
    .pmp-cuotas-wrap #tabla-cuotas-pendientes .badge {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        border-radius: 20px !important;
        padding: .2rem .55rem !important;
        font-size: .65rem !important;
    }

    /* ── Resumen ── */
    .pmp-resumen {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: .85rem;
        padding: 1rem 1.15rem;
        background: linear-gradient(135deg, #fff 0%, #fff7ed 100%);
        border: 1px solid rgba(252,123,4,.18);
        border-radius: 12px;
    }
    .pmp-resumen-item {
        display: flex; align-items: center; gap: .7rem;
        padding: .35rem 0;
    }
    .pmp-resumen-item + .pmp-resumen-item {
        border-left: 1px dashed #fed7aa;
        padding-left: .85rem;
    }
    .pmp-resumen-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(220,38,38,.1);
        color: #b91c1c;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .pmp-resumen-icon.ingresado { background: rgba(37,99,235,.1); color: #2563eb; }
    .pmp-resumen-icon.nueva     { background: rgba(34,197,94,.1); color: #16a34a; }
    .pmp-resumen-lbl {
        font-size: .65rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: #64748b;
    }
    .pmp-resumen-val {
        font-family: 'Outfit', sans-serif;
        font-size: 1.05rem; font-weight: 800;
        color: #0f172a; line-height: 1.1;
    }
    .pmp-resumen-val.pmp-deuda     { color: #b91c1c; }
    .pmp-resumen-val.pmp-ingresado { color: #2563eb; }
    .pmp-resumen-val.pmp-nueva     { color: #16a34a; }

    /* ── Footer ── */
    .pmp-footer {
        display: flex; align-items: center; justify-content: flex-end;
        gap: .65rem;
        padding: 1rem 1.5rem;
        background: #fff;
        border-top: 1px solid #e2e8f0;
    }
    .pmp-btn {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .6rem 1.25rem;
        border-radius: 10px;
        font-size: .85rem; font-weight: 700;
        border: 1.5px solid transparent;
        cursor: pointer;
        transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
        font-family: inherit;
    }
    .pmp-btn-cancel {
        background: #fff;
        border-color: #e2e8f0;
        color: #475569;
    }
    .pmp-btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
    .pmp-btn-submit {
        background: linear-gradient(135deg, #9a4904 0%, #fc7b04 100%);
        color: #fff;
        border-color: #9a4904;
        box-shadow: 0 4px 14px rgba(154,73,4,.32);
    }
    .pmp-btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(154,73,4,.42);
    }

    @media (max-width: 720px) {
        .pmp-resumen { grid-template-columns: 1fr; }
        .pmp-resumen-item + .pmp-resumen-item { border-left: none; border-top: 1px dashed #fed7aa; padding-left: 0; padding-top: .65rem; }
    }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        @php
            $trabajadorCargoId = '';
            if (auth()->user()) {
                $user = auth()->user();
                if ($user->persona_id) {
                    $trabajador = \App\Models\Trabajadore::where('persona_id', $user->persona_id)->first();
                    if ($trabajador) {
                        $cargoActivo = \App\Models\TrabajadoresCargo::where('trabajadore_id', $trabajador->id)
                            ->where('estado', 'Vigente')
                            ->first();
                        $trabajadorCargoId = $cargoActivo?->id;
                    }
                }
            }
        @endphp
        <input type="hidden" id="current-trabajador-cargo" value="{{ $trabajadorCargoId }}">
        @if (!$trabajadorCargoId)
            <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                <i class="ri-alert-line me-2"></i>
                <strong>Advertencia:</strong> Tu usuario no tiene un cargo activo asignado. No podrás registrar pagos hasta
                que un administrador te asigne un cargo activo en el sistema.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header border-0 bg-transparent py-4 px-4" style="border-bottom: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-3"
                                style="width: 48px; height: 48px; background: #fc7b0415;">
                                <i class="ri-search-line" style="color: #fc7b04; font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold" style="color: #1e293b;">Buscar Estudiante</h4>
                                <p class="mb-0 text-muted" style="font-size: 0.875rem;">Busca por carnet, nombres o
                                    apellidos</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-lg-8">
                                <div class="position-relative">
                                    <i class="ri-search-line position-absolute"
                                        style="left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; z-index: 1;"></i>
                                    <input type="text" id="busquedaInput" class="form-control form-control-lg border-2"
                                        placeholder="Escribe para buscar (carnet, nombres o apellidos)..."
                                        style="padding-left: 48px; border-radius: 12px; border-color: #e2e8f0; font-size: 1rem;">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <button type="button" id="btnBuscar" class="btn btn-lg w-100 fw-semibold"
                                    style="background: #fc7b04; color: white; border-radius: 12px;">
                                    <i class="ri-search-line me-2"></i>Buscar
                                </button>
                            </div>
                        </div>

                        <!-- Resultados en Cards -->
                        <div id="resultadosBusqueda" class="d-none">
                            <h6 class="mb-3 fw-bold" style="color: #1e293b;">Resultados de búsqueda</h6>
                            <div id="cardsResultados" class="row g-3"></div>
                        </div>

                        <div id="sinResultados" class="text-center py-5 d-none">
                            <div class="mb-3">
                                <i class="ri-user-search-line" style="font-size: 4rem; color: #cbd5e1;"></i>
                            </div>
                            <h5 class="fw-semibold" style="color: #64748b;">Sin resultados</h5>
                            <p class="text-muted">No se encontraron estudiantes con ese criterio de búsqueda</p>
                        </div>

                        <div id="buscando" class="text-center py-5 d-none">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Buscando...</span>
                            </div>
                            <p class="mt-3 text-muted">Buscando estudiantes...</p>
                        </div>

                        <div id="placeholder" class="text-center py-5">
                            <div class="mb-3">
                                <i class="ri-user-follow-line" style="font-size: 4rem; color: #cbd5e1;"></i>
                            </div>
                            <h5 class="fw-semibold" style="color: #64748b;">Buscar estudiantes</h5>
                            <p class="text-muted">Ingresa al menos 2 caracteres para iniciar la búsqueda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Pago Masivo -->
        <div class="modal fade pmp-modal" id="modalPagoMasivo" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content pmp-content">

                    {{-- Header con gradiente naranja --}}
                    <div class="pmp-header">
                        <div class="pmp-header-icon"><i class="ri-bank-card-line"></i></div>
                        <div class="pmp-header-text">
                            <h5 class="pmp-header-title">Registro de Pago</h5>
                            <small class="pmp-header-sub" id="pago-masivo-oferta">—</small>
                        </div>
                        <button type="button" class="pmp-close-btn" data-bs-dismiss="modal" aria-label="Cerrar">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>

                    <form id="formPagoMasivo">
                        <div class="modal-body pmp-body">

                            {{-- Inputs principales --}}
                            <div class="pmp-section">
                                <div class="pmp-section-title"><i class="ri-edit-2-line"></i> Datos del pago</div>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="pmp-label"><i class="ri-money-dollar-line"></i> Monto a Pagar <span>(Bs.)</span></label>
                                        <input type="number" class="form-control pmp-input" id="pago-masivo-monto" name="monto" step="0.01" min="0.01" required placeholder="0.00">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="pmp-label"><i class="ri-discount-line"></i> Descuento <span>(Bs.)</span></label>
                                        <input type="number" class="form-control pmp-input" id="pago-masivo-descuento" name="descuento" step="0.01" min="0" value="0">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="pmp-label"><i class="ri-calendar-line"></i> Fecha de Pago</label>
                                        <input type="date" class="form-control pmp-input" id="pago-masivo-fecha" name="fecha_pago" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="pmp-label"><i class="ri-bank-card-line"></i> Método de Pago</label>
                                        <select class="form-select pmp-input" id="pago-masivo-metodo" name="metodo" required>
                                            <option value="">Seleccionar...</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Qr">QR</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Parcial">Parcial (Efectivo + QR)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6" id="pago-masivo-campo-efectivo" style="display:none;">
                                        <label class="pmp-label"><i class="ri-money-dollar-line"></i> Efectivo <span>(Bs.)</span></label>
                                        <input type="number" class="form-control pmp-input" id="pago-masivo-efectivo" name="efectivo" step="0.01" min="0" placeholder="0.00">
                                    </div>
                                    <div class="col-md-6" id="pago-masivo-campo-qr" style="display:none;">
                                        <label class="pmp-label"><i class="ri-qr-code-line"></i> QR <span>(Bs.)</span></label>
                                        <input type="number" class="form-control pmp-input" id="pago-masivo-qr" name="qr" step="0.01" min="0" placeholder="0.00">
                                    </div>
                                    <div class="col-md-6" id="pago-masivo-cuenta-bancaria-container" style="display:none;">
                                        <label class="pmp-label"><i class="ri-bank-line"></i> Cuenta Bancaria</label>
                                        <select class="form-select pmp-input" id="pago-masivo-cuenta-bancaria" name="cuenta_bancaria_id">
                                            <option value="">Seleccionar cuenta...</option>
                                            @foreach(($cuentasBancarias ?? []) as $cuenta)
                                                <option value="{{ $cuenta->id }}">{{ $cuenta->banco->nombre }} - {{ $cuenta->numero_cuenta }} ({{ $cuenta->tipo_cuenta }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6" id="pago-masivo-referencia-container" style="display:none;">
                                        <label class="pmp-label"><i class="ri-file-info-line"></i> Referencia</label>
                                        <input type="text" class="form-control pmp-input" id="pago-masivo-referencia" name="referencia" placeholder="Número de referencia">
                                    </div>
                                </div>
                            </div>

                            {{-- Lista de cuotas --}}
                            <div class="pmp-section">
                                <div class="pmp-section-title"><i class="ri-list-check-2"></i> Cuotas del programa</div>
                                <div id="pago-masivo-lista-cuotas" class="pmp-cuotas-wrap"></div>
                            </div>

                            {{-- Resumen --}}
                            <div class="pmp-resumen">
                                <div class="pmp-resumen-item">
                                    <div class="pmp-resumen-icon"><i class="ri-wallet-3-line"></i></div>
                                    <div>
                                        <div class="pmp-resumen-lbl">Total Deuda</div>
                                        <div class="pmp-resumen-val pmp-deuda" id="pago-masivo-deuda-total">—</div>
                                    </div>
                                </div>
                                <div class="pmp-resumen-item">
                                    <div class="pmp-resumen-icon ingresado"><i class="ri-money-dollar-circle-line"></i></div>
                                    <div>
                                        <div class="pmp-resumen-lbl">Monto Ingresado</div>
                                        <div class="pmp-resumen-val pmp-ingresado" id="pago-masivo-monto-ingresado">—</div>
                                    </div>
                                </div>
                                <div class="pmp-resumen-item">
                                    <div class="pmp-resumen-icon nueva"><i class="ri-checkbox-circle-line"></i></div>
                                    <div>
                                        <div class="pmp-resumen-lbl">Nueva Deuda</div>
                                        <div class="pmp-resumen-val pmp-nueva" id="pago-masivo-nueva-deuda">—</div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="pago-masivo-estudiante-id" name="estudiante_id">
                            <input type="hidden" id="pago-masivo-inscripcion-id" name="inscripcion_id">
                            <input type="hidden" id="pago-masivo-trabajador-cargo" name="trabajador_cargo_id">
                        </div>

                        <div class="pmp-footer">
                            <button type="button" class="pmp-btn pmp-btn-cancel" data-bs-dismiss="modal">
                                <i class="ri-close-line"></i> Cancelar
                            </button>
                            <button type="submit" class="pmp-btn pmp-btn-submit">
                                <i class="ri-save-line"></i> Registrar Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Recibo de Pago -->
        <div class="modal fade" id="modalReciboPago" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
                    <div class="modal-body p-0">
                        <!-- Cabecera verde de éxito -->
                        <div class="text-center py-4 px-4" style="background: linear-gradient(135deg, #16a34a, #15803d);">
                            <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                                style="width: 64px; height: 64px; background: rgba(255,255,255,0.2);">
                                <i class="ri-checkbox-circle-fill text-white" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="text-white fw-bold mb-1">Pago Registrado</h5>
                            <p class="text-white mb-0" style="opacity: .85; font-size: .875rem;"
                                id="recibo-mensaje-exito">—</p>
                        </div>

                        <!-- Datos del recibo -->
                        <div class="px-4 py-3" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">N° Recibo</span>
                                <span class="fw-bold" style="color: #1e293b;" id="recibo-numero">—</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted small">Total Pagado</span>
                                <span class="fw-bold text-success" id="recibo-total">—</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted small">Saldo Restante</span>
                                <span class="fw-bold" id="recibo-saldo">—</span>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="p-4">
                            <p class="text-muted text-center mb-3" style="font-size: .8rem;">¿Desea generar el comprobante
                                de pago?</p>
                            <div class="d-grid gap-2">
                                <button type="button" id="btn-imprimir-recibo" class="btn fw-semibold"
                                    style="background: #fc7b04; color: white; border-radius: 10px; padding: 10px;">
                                    <i class="ri-printer-line me-2"></i>Imprimir Recibo
                                </button>
                                <button type="button" id="btn-descargar-recibo"
                                    class="btn btn-outline-secondary fw-semibold"
                                    style="border-radius: 10px; padding: 10px;">
                                    <i class="ri-download-line me-2"></i>Descargar Recibo
                                </button>
                                <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal"
                                    style="font-size: .85rem;">
                                    Cerrar sin imprimir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const buscarInput = document.getElementById('busquedaInput');
            const btnBuscar = document.getElementById('btnBuscar');
            const resultadosDiv = document.getElementById('resultadosBusqueda');
            const cardsResultados = document.getElementById('cardsResultados');
            const sinResultados = document.getElementById('sinResultados');
            const buscando = document.getElementById('buscando');
            const placeholder = document.getElementById('placeholder');

            let debounceTimer;

            window.mostrarSeccion = function(seccion) {
                resultadosDiv.classList.add('d-none');
                sinResultados.classList.add('d-none');
                buscando.classList.add('d-none');
                placeholder.classList.add('d-none');
                seccion.classList.remove('d-none');
            }

            window.buscarEstudiantes = function(query) {
                if (query.length < 2) return;

                mostrarSeccion(buscando);

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    fetch('/admin/estudiantes/buscar/api?q=' + encodeURIComponent(query))
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            if (data.data && data.data.length > 0) {
                                renderizarResultados(data.data);
                            } else {
                                mostrarSeccion(sinResultados);
                            }
                        })
                        .catch(function(error) {
                            console.error('Error:', error);
                            mostrarSeccion(sinResultados);
                        });
                }, 300);
            }

            function renderizarResultados(estudiantes) {
                mostrarSeccion(resultadosDiv);

                cardsResultados.innerHTML = estudiantes.map(function(est) {
                    var estadoColor = est.saldo <= 0 ? '#16a34a' : (est.total_pagado > 0 ? '#d97706' :
                        '#dc2626');
                    var estadoBadge = est.saldo <= 0 ? 'Al día' : (est.cuotas_pendientes > 0 ? 'Pendiente' :
                        'Sin pagos');

                    var ofertasHtml = '';
                    if (est.ofertas && est.ofertas.length > 0) {
                        ofertasHtml = '<div class="ofertas-academicas-wrap mt-3 pt-3">' +
                            '<div class="ofertas-academicas-head">' +
                                '<i class="ri-graduation-cap-line"></i>' +
                                '<span>Ofertas académicas</span>' +
                                '<span class="ofertas-academicas-count">' + est.ofertas.length + '</span>' +
                            '</div>' +
                            '<div class="ofertas-academicas-grid">';

                        est.ofertas.forEach(function(oferta, idx) {
                            var pagadoNum   = parseFloat(oferta.total_pagado) || 0;
                            var saldoNum    = parseFloat(oferta.saldo) || 0;
                            var totalNum    = pagadoNum + saldoNum;
                            var pct         = totalNum > 0 ? Math.round((pagadoNum / totalNum) * 100) : 0;
                            var estadoCls   = saldoNum <= 0 ? 'al-dia' : (pagadoNum > 0 ? 'pendiente' : 'sin-pago');
                            var estadoTxt   = saldoNum <= 0 ? 'Al día' : (pagadoNum > 0 ? 'Pendiente' : 'Sin pagos');
                            var estadoIco   = saldoNum <= 0 ? 'ri-checkbox-circle-fill' : (pagadoNum > 0 ? 'ri-time-line' : 'ri-error-warning-line');

                            ofertasHtml += '<div class="oferta-acad-card oferta-' + estadoCls + '">' +
                                '<div class="oferta-acad-head">' +
                                    '<div class="oferta-acad-titulo">' +
                                        '<span class="oferta-acad-codigo">' + oferta.oferta_codigo + '</span>' +
                                        '<span class="oferta-acad-nombre">' + oferta.oferta_nombre + '</span>' +
                                    '</div>' +
                                    '<span class="oferta-acad-estado ' + estadoCls + '"><i class="' + estadoIco + '"></i> ' + estadoTxt + '</span>' +
                                '</div>' +
                                '<div class="oferta-acad-progress">' +
                                    '<div class="oferta-acad-progress-bar"><div class="oferta-acad-progress-fill" style="width:' + pct + '%;"></div></div>' +
                                    '<span class="oferta-acad-progress-pct">' + pct + '%</span>' +
                                '</div>' +
                                '<div class="oferta-acad-stats">' +
                                    '<div class="oferta-acad-stat">' +
                                        '<span class="oferta-acad-stat-lbl">Pagado</span>' +
                                        '<span class="oferta-acad-stat-val pagado">Bs. ' + pagadoNum.toFixed(2) + '</span>' +
                                    '</div>' +
                                    '<div class="oferta-acad-stat">' +
                                        '<span class="oferta-acad-stat-lbl">Saldo</span>' +
                                        '<span class="oferta-acad-stat-val saldo">Bs. ' + saldoNum.toFixed(2) + '</span>' +
                                    '</div>' +
                                    (saldoNum > 0 ?
                                        '<button type="button" class="oferta-acad-pagar btn-pagar-oferta" ' +
                                            'data-estudiante-id="' + est.estudiante_id + '" ' +
                                            'data-inscripcion-id="' + (oferta.inscripcion_id || '') + '" ' +
                                            'data-oferta-name="' + oferta.oferta_nombre + '">' +
                                            '<i class="ri-bank-card-line"></i> Pagar' +
                                        '</button>'
                                        : '<div class="oferta-acad-done"><i class="ri-checkbox-circle-fill"></i> Cancelado</div>') +
                                '</div>' +
                            '</div>';
                        });

                        ofertasHtml += '</div></div>';
                    }

                    return '<div class="col-lg-6 col-xl-6">' +
                        '<div class="card border-0 h-100" style="border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">' +
                        '<div class="card-body p-3">' +
                        '<div class="d-flex justify-content-between align-items-start mb-2">' +
                        '<div class="d-flex align-items-center gap-2">' +
                        '<div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #fc7b04, #e66a00);">' +
                        '<span class="text-white fw-bold">' + est.nombre_completo.charAt(0) + '</span>' +
                        '</div>' +
                        '<div>' +
                        '<a href="/admin/estudiantes/' + est.estudiante_id +
                        '/detalle" class="text-decoration-none fw-bold" style="color: #1e293b;">' + est
                        .nombre_completo + '</a>' +
                        '<div class="d-flex align-items-center gap-2">' +
                        '<span class="badge fs-9 fw-semibold text-white" style="background: #16a34a; padding: 2px 8px; border-radius: 4px;">' +
                        (est.carnet || '—') + '</span>' +
                        '<span class="badge fs-9" style="background: ' + estadoColor + '20; color: ' +
                        estadoColor + '; padding: 2px 8px; border-radius: 4px;">' + estadoBadge +
                        '</span>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="d-flex justify-content-between align-items-center py-2 px-2 rounded-2 mb-2" style="background: #f8fafc;">' +
                        '<div class="text-center">' +
                        '<div class="fs-9 text-muted">Celular</div>' +
                        (est.celular ? '<a href="tel:' + est.celular +
                            '" class="text-success text-decoration-none fw-semibold">' + est.celular +
                            '</a>' : '<span class="text-muted">—</span>') +
                        '</div>' +
                        '<div class="text-center">' +
                        '<div class="fs-9 text-muted">Correo</div>' +
                        (est.correo ? '<a href="mailto:' + est.correo +
                            '" class="text-primary text-decoration-none fw-semibold" style="font-size: 0.7rem;">' +
                            (est.correo.length > 15 ? est.correo.substring(0, 15) + '...' : est.correo) +
                            '</a>' : '<span class="text-muted">—</span>') +
                        '</div>' +
                        '</div>' +

                        '<div class="row g-2 text-center">' +
                        '<div class="col-4">' +
                        '<div class="p-2 rounded-2" style="background: #f1f5f9;">' +
                        '<div class="fs-9 text-muted">Total Plan</div>' +
                        '<div class="fw-bold" style="color: #1e293b;">Bs. ' + parseFloat(est.total_plan)
                        .toFixed(2) + '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-4">' +
                        '<div class="p-2 rounded-2" style="background: #dcfce7;">' +
                        '<div class="fs-9 text-success">Pagado</div>' +
                        '<div class="fw-bold text-success">Bs. ' + parseFloat(est.total_pagado).toFixed(2) +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-4">' +
                        '<div class="p-2 rounded-2" style="background: ' + estadoColor + '15;">' +
                        '<div class="fs-9" style="color: ' + estadoColor + ';">Saldo</div>' +
                        '<div class="fw-bold" style="color: ' + estadoColor + ';">Bs. ' + parseFloat(est
                            .saldo).toFixed(2) + '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="d-flex justify-content-end gap-2 mt-2">' +
                        '<a href="/admin/estudiantes/' + est.estudiante_id +
                        '/detalle" class="btn btn-sm fw-semibold" style="background: #1e293b; color: white; border-radius: 8px; padding: 6px 16px;">' +
                        '<i class="ri-eye-line me-1"></i>Ver Detalle' +
                        '</a>' +
                        '</div>' +

                        ofertasHtml +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }).join('');
            }

            buscarInput.addEventListener('input', function() {
                buscarEstudiantes(this.value);
            });

            btnBuscar.addEventListener('click', function() {
                buscarEstudiantes(buscarInput.value);
            });

            buscarInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    buscarEstudiantes(this.value);
                }
            });
        });

        // ===== TOAST =====
        function toast(tipo, mensaje) {
            const iconMap = {
                success: 'ri-checkbox-circle-line',
                error: 'ri-error-warning-line',
                warning: 'ri-alert-line'
            };
            const el = document.createElement('div');
            el.className = 'toast-notify ' + tipo;
            el.innerHTML = '<div class="toast-icon"><i class="' + (iconMap[tipo] || 'ri-information-line') +
                '"></i></div>' +
                '<div class="toast-body-text"><span>' + mensaje + '</span></div>';
            let c = document.getElementById('toastContainer');
            if (!c) {
                c = document.createElement('div');
                c.id = 'toastContainer';
                c.className = 'toast-container';
                document.body.appendChild(c);
            }
            c.appendChild(el);
            setTimeout(() => {
                el.classList.add('show');
            }, 10);
            setTimeout(() => {
                el.classList.add('hiding');
                setTimeout(() => el.remove(), 300);
            }, 4000);
        }

        // Modal Pago Masivo
        let cuotaData = [];
        let deudaTotalGlobal = 0;
        const modalPagoMasivo = new bootstrap.Modal(document.getElementById('modalPagoMasivo'));

        function abrirModalPago(estudianteId, inscripcionId, ofertaNombre) {
            document.getElementById('pago-masivo-estudiante-id').value = estudianteId;
            document.getElementById('pago-masivo-inscripcion-id').value = inscripcionId || '';
            document.getElementById('pago-masivo-oferta').textContent = ofertaNombre || 'Pago General';
            document.getElementById('pago-masivo-trabajador-cargo').value = document.getElementById(
                'current-trabajador-cargo').value;
            document.getElementById('pago-masivo-monto').value = '';
            document.getElementById('pago-masivo-descuento').value = '0';
            const nowLaPazBuscar = new Date(new Date().toLocaleString('en-US', { timeZone: 'America/La_Paz' }));
            const yearBuscar = String(nowLaPazBuscar.getFullYear());
            const monthBuscar = String(nowLaPazBuscar.getMonth() + 1).padStart(2, '0');
            const dayBuscar = String(nowLaPazBuscar.getDate()).padStart(2, '0');
            document.getElementById('pago-masivo-fecha').value = yearBuscar + '-' + monthBuscar + '-' + dayBuscar;
            document.getElementById('pago-masivo-metodo').value = '';
            document.getElementById('pago-masivo-efectivo').value = '';
            document.getElementById('pago-masivo-qr').value = '';
            var cbSel = document.getElementById('pago-masivo-cuenta-bancaria');
            if (cbSel) cbSel.value = '';
            var refInp = document.getElementById('pago-masivo-referencia');
            if (refInp) refInp.value = '';
            document.getElementById('pago-masivo-campo-efectivo').style.display = 'none';
            document.getElementById('pago-masivo-campo-qr').style.display = 'none';
            var cbCont = document.getElementById('pago-masivo-cuenta-bancaria-container');
            if (cbCont) cbCont.style.display = 'none';
            var refCont = document.getElementById('pago-masivo-referencia-container');
            if (refCont) refCont.style.display = 'none';

            fetch('/admin/estudiantes/' + estudianteId + '/cuotas-json')
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    // Si recibimos inscripcion_id, filtrar SOLO las cuotas de ese programa
                    if (inscripcionId) {
                        data = data.filter(function(c) {
                            return String(c.inscripcion_id || '') === String(inscripcionId);
                        });
                    } else if (data.length > 0) {
                        // Sin inscripción específica: usar la primera disponible para el guardado
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].inscripcion_id) {
                                document.getElementById('pago-masivo-inscripcion-id').value = data[i].inscripcion_id;
                                break;
                            }
                        }
                    }

                    var lista = document.getElementById('pago-masivo-lista-cuotas');

                    // Ordenar cuotas por concepto
                    var cuotasOrdenadas = [];
                    ['Matrícula', 'Colegiatura', 'Certificación'].forEach(function(concBuscada) {
                        data.forEach(function(cuota) {
                            if (cuota.estado !== 'Pagado') {
                                var nombreRaw = (cuota.nombre || '').toLowerCase();
                                var esConcepto = false;
                                if (concBuscada === 'Matrícula' && nombreRaw.includes('matr'))
                                    esConcepto = true;
                                else if (concBuscada === 'Colegiatura' && nombreRaw.includes('coleg'))
                                    esConcepto = true;
                                else if (concBuscada === 'Certificación' && nombreRaw.includes(
                                    'certif')) esConcepto = true;

                                if (esConcepto) {
                                    cuota.concepto = concBuscada;
                                    cuotasOrdenadas.push(cuota);
                                }
                            }
                        });
                    });

                    // Agregar las demás cuotas al final
                    data.forEach(function(cuota) {
                        if (cuota.estado !== 'Pagado' && !cuota.concepto) {
                            cuota.concepto = 'Otro';
                            cuotasOrdenadas.push(cuota);
                        }
                    });

                    var total = 0;
                    var html = '<h6 class="text-muted mb-3"><i class="ri-install-line"></i> Cuotas Pendientes</h6>';
                    html += '<table class="table table-sm table-hover" id="tabla-cuotas-pendientes"><thead><tr>';
                    html +=
                        '<th>#</th><th>Concepto</th><th>Cuota</th><th>Monto</th><th>Pendiente</th><th>A Pagar</th><th>Vencimiento</th><th>Estado</th>';
                    html += '</tr></thead><tbody>';

                    cuotasOrdenadas.forEach(function(cuota, idx) {
                        var concepto = cuota.concepto || 'Otro';
                        var color = concepto === 'Matrícula' ? '#2563eb' : (concepto === 'Colegiatura' ?
                            '#0891b2' : (concepto === 'Certificación' ? '#d97706' : '#64748b'));
                        var pendiente = parseFloat(cuota.pago_pendiente_bs) || 0;

                        html += '<tr class="cuota-row" data-concepto="' + concepto + '" data-cuota-id="' + cuota
                            .id + '" data-pendiente="' + pendiente + '">';
                        html += '<td>' + (idx + 1) + '</td>';
                        html += '<td><span class="badge fw-semibold" style="background: ' + color +
                            '20; color: ' + color + ';">' + concepto + '</span></td>';
                        html += '<td>' + cuota.nombre + '</td>';
                        html += '<td>Bs. ' + parseFloat(cuota.monto_bs).toFixed(2) + '</td>';
                        html += '<td class="text-warning fw-bold" data-pendiente="' + pendiente + '">Bs. ' +
                            pendiente.toFixed(2) + '</td>';
                        html += '<td class="text-success fw-bold a-pagar-cell">—</td>';
                        html += '<td>' + (cuota.fecha_vencimiento ? new Date(cuota.fecha_vencimiento)
                            .toLocaleDateString('es-ES') : '—') + '</td>';
                        html += '<td><span class="badge bg-warning text-dark">' + cuota.estado + '</span></td>';
                        html += '</tr>';
                        total += pendiente;
                    });

                    html += '</tbody></table>';
                    html += '</tbody></table>';
                    lista.innerHTML = html;
                    window.cuotaData = data;
                    window.deudaTotalGlobal = total;
                    document.getElementById('pago-masivo-deuda-total').textContent = 'Bs. ' + total.toFixed(2);
                    actualizarResumenPagoMasivo();
                });

            modalPagoMasivo.show();
        }

        function actualizarResumenPagoMasivo() {
            var monto = parseFloat(document.getElementById('pago-masivo-monto').value) || 0;
            var descuento = parseFloat(document.getElementById('pago-masivo-descuento').value) || 0;
            var metodo = document.getElementById('pago-masivo-metodo').value;

            var montoIngresado = monto;
            if (metodo === 'Parcial') {
                montoIngresado = (parseFloat(document.getElementById('pago-masivo-efectivo').value) || 0) +
                    (parseFloat(document.getElementById('pago-masivo-qr').value) || 0);
            }

            var totalIngresado = Math.round((montoIngresado + descuento) * 100) / 100;
            var nuevaDeuda = Math.max(0, Math.round((window.deudaTotalGlobal - totalIngresado) * 100) / 100);

            document.getElementById('pago-masivo-monto-ingresado').textContent = 'Bs. ' + totalIngresado.toFixed(2);
            document.getElementById('pago-masivo-nueva-deuda').textContent = 'Bs. ' + nuevaDeuda.toFixed(2);

            // Marcar cuotas según monto
            var tabla = document.getElementById('tabla-cuotas-pendientes');
            if (tabla) {
                var filas = tabla.querySelectorAll('.cuota-row');
                var remaining = totalIngresado;
                filas.forEach(function(fila) {
                    fila.style.background = '';
                    var cellAPagar = fila.querySelector('.a-pagar-cell');
                    if (cellAPagar) cellAPagar.textContent = '—';
                    var pendiente = parseFloat(fila.getAttribute('data-pendiente')) || 0;
                    if (remaining > 0 && pendiente > 0) {
                        var aPagar = Math.round(Math.min(pendiente, remaining) * 100) / 100;
                        if (aPagar > 0) {
                            fila.style.background = 'rgba(34, 197, 94, 0.15)';
                            if (cellAPagar) cellAPagar.textContent = 'Bs. ' + aPagar.toFixed(2);
                            remaining = Math.round((remaining - aPagar) * 100) / 100;
                        }
                    }
                });
            }
        }

        // Manejar botones de pagar
        document.addEventListener('click', function(e) {
            var btnOferta = e.target.closest('.btn-pagar-oferta');
            var btnEstudiante = e.target.closest('.btn-pagar-estudiante');

            if (btnOferta) {
                document.getElementById('pago-masivo-trabajador-cargo').value = document.getElementById(
                    'current-trabajador-cargo').value;
                abrirModalPago(btnOferta.dataset.estudianteId, btnOferta.dataset.inscripcionId, btnOferta.dataset
                    .ofertaName);
            } else if (btnEstudiante) {
                document.getElementById('pago-masivo-trabajador-cargo').value = document.getElementById(
                    'current-trabajador-cargo').value;
                abrirModalPago(btnEstudiante.dataset.estudianteId, '', btnEstudiante.dataset.ofertaName);
            }
        });

        document.getElementById('pago-masivo-metodo').addEventListener('change', function() {
            var campoEfectivo = document.getElementById('pago-masivo-campo-efectivo');
            var campoQr       = document.getElementById('pago-masivo-campo-qr');
            var cuentaBancariaContainer = document.getElementById('pago-masivo-cuenta-bancaria-container');
            var referenciaContainer     = document.getElementById('pago-masivo-referencia-container');

            if (this.value === 'Parcial') {
                campoEfectivo.style.display = 'block';
                campoQr.style.display = 'block';
                cuentaBancariaContainer.style.display = 'block';
                referenciaContainer.style.display = 'none';
            } else if (this.value === 'Qr') {
                campoEfectivo.style.display = 'none';
                campoQr.style.display = 'none';
                cuentaBancariaContainer.style.display = 'block';
                referenciaContainer.style.display = 'none';
            } else if (this.value === 'Transferencia') {
                campoEfectivo.style.display = 'none';
                campoQr.style.display = 'none';
                cuentaBancariaContainer.style.display = 'block';
                referenciaContainer.style.display = 'block';
            } else {
                campoEfectivo.style.display = 'none';
                campoQr.style.display = 'none';
                cuentaBancariaContainer.style.display = 'none';
                referenciaContainer.style.display = 'none';
            }
            actualizarResumenPagoMasivo();
        });

        document.getElementById('pago-masivo-qr').addEventListener('input', function() {
            var monto = parseFloat(document.getElementById('pago-masivo-monto').value) || 0;
            var qr = parseFloat(this.value) || 0;
            var efectivoCampo = document.getElementById('pago-masivo-efectivo');
            if (efectivoCampo) efectivoCampo.value = Math.max(0, monto - qr).toFixed(2);
            actualizarResumenPagoMasivo();
        });

        document.getElementById('pago-masivo-efectivo').addEventListener('input', function() {
            var monto = parseFloat(document.getElementById('pago-masivo-monto').value) || 0;
            var efectivo = parseFloat(this.value) || 0;
            var qrCampo = document.getElementById('pago-masivo-qr');
            if (qrCampo) qrCampo.value = Math.max(0, monto - efectivo).toFixed(2);
            actualizarResumenPagoMasivo();
        });

        document.getElementById('pago-masivo-monto').addEventListener('input', actualizarResumenPagoMasivo);
        document.getElementById('pago-masivo-descuento').addEventListener('input', actualizarResumenPagoMasivo);

        document.getElementById('formPagoMasivo').addEventListener('submit', function(e) {
            e.preventDefault();
            var estudianteId = document.getElementById('pago-masivo-estudiante-id').value;
            var inscripcionId = document.getElementById('pago-masivo-inscripcion-id').value;
            var monto = parseFloat(document.getElementById('pago-masivo-monto').value);
            var descuento = parseFloat(document.getElementById('pago-masivo-descuento').value) || 0;
            var metodo = document.getElementById('pago-masivo-metodo').value;
            var efectivo = parseFloat(document.getElementById('pago-masivo-efectivo').value) || 0;
            var qr = parseFloat(document.getElementById('pago-masivo-qr').value) || 0;
            var fechaPago = document.getElementById('pago-masivo-fecha').value;

            // Obtener cuotas seleccionadas
            var cuotasSeleccionadas = [];
            var tabla = document.getElementById('tabla-cuotas-pendientes');
            if (tabla) {
                tabla.querySelectorAll('.cuota-row').forEach(function(fila) {
                    var cellAPagar = fila.querySelector('.a-pagar-cell');
                    var cuotaId = fila.getAttribute('data-cuota-id');
                    var pendiente = parseFloat(fila.getAttribute('data-pendiente')) || 0;
                    if (cellAPagar && cellAPagar.textContent !== '—' && cellAPagar.textContent !== '') {
                        var aPagar = parseFloat(cellAPagar.textContent.replace('Bs. ', ''));
                        if (cuotaId && aPagar > 0) {
                            cuotasSeleccionadas.push({
                                id: cuotaId,
                                monto: aPagar
                            });
                        }
                    }
                });
            }

            console.log('Sending payment request:', {
                estudiante_id: estudianteId,
                inscripcion_id: inscripcionId,
                monto: monto,
                descuento: descuento,
                metodo: metodo,
                efectivo: efectivo,
                qr: qr,
                fecha_pago: fechaPago,
                cuotas: cuotasSeleccionadas
            });

            fetch('/admin/estudiantes/' + estudianteId + '/pago-masivo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        estudiante_id: estudianteId,
                        inscripcion_id: inscripcionId,
                        monto: monto,
                        descuento: descuento,
                        metodo: metodo,
                        efectivo: efectivo,
                        qr: qr,
                        fecha_pago: fechaPago,
                        cuotas: cuotasSeleccionadas,
                        trabajador_cargo_id: document.getElementById('pago-masivo-trabajador-cargo')
                            .value,
                        cuenta_bancaria_id: document.getElementById('pago-masivo-cuenta-bancaria')?.value || '',
                        referencia: document.getElementById('pago-masivo-referencia')?.value || '',
                    })
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        if (res.ok && data.success) {
                            modalPagoMasivo.hide();
                            toast('success', data.message || 'Pago registrado correctamente.');
                            buscarEstudiantes(document.getElementById('busquedaInput').value);
                            if (data.data && data.data.pago_id) {
                                document.getElementById('recibo-mensaje-exito').textContent = data
                                    .message || 'Pago registrado correctamente.';
                                document.getElementById('recibo-numero').textContent = data.data
                                    .recibo || '—';
                                document.getElementById('recibo-total').textContent = 'Bs. ' +
                                    parseFloat(data.data.total_pagado || 0).toFixed(2);
                                document.getElementById('recibo-saldo').textContent = 'Bs. ' +
                                    parseFloat(data.data.nueva_deuda || 0).toFixed(2);
                                var pagoId = data.data.pago_id;
                                document.getElementById('btn-imprimir-recibo').onclick = function() {
                                    window.open('/admin/estudiantes/recibo/' + pagoId +
                                        '/pdf?inline=1', '_blank');
                                };
                                document.getElementById('btn-descargar-recibo').onclick = function() {
                                    window.open('/admin/estudiantes/recibo/' + pagoId + '/pdf',
                                        '_blank');
                                };
                                var modalRecibo = new bootstrap.Modal(document.getElementById(
                                    'modalReciboPago'));
                                modalRecibo.show();
                            }
                        } else {
                            console.error('Error response:', data);
                            toast('error', data.message || 'Error al registrar el pago.');
                        }
                    });
                })
                .catch(function(err) {
                    console.error('Fetch error:', err);
                    toast('error', 'Error al registrar el pago. Intente nuevamente.');
                });
        });
    </script>
@endsection
