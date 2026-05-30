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
                    <p>
                        <strong>{{ $cuentaBancaria->numero_cuenta }}</strong>
                        &middot; {{ $cuentaBancaria->tipo_cuenta }}
                        &middot;
                        <span class="acct-hero-badge {{ $cuentaBancaria->estado ? 'activa' : 'inactiva' }}">
                            <i class="ri-{{ $cuentaBancaria->estado ? 'checkbox-circle' : 'close-circle' }}-line"></i>
                            {{ $cuentaBancaria->estado ? 'Activa' : 'Inactiva' }}
                        </span>
                        @if($cuentaBancaria->es_principal)
                        <span class="acct-hero-badge principal">
                            <i class="ri-star-fill"></i> Principal
                        </span>
                        @endif
                    </p>
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
                Último: {{ $ultimoMovimiento->created_at->format('d/m/Y') }}
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
                        <span class="info-value">{{ $cuentaBancaria->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Actualizada</span>
                        <span class="info-value">{{ $cuentaBancaria->updated_at->format('d/m/Y') }}</span>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientos as $mov)
                        <tr>
                            <td style="white-space:nowrap;">{{ $mov->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($mov->tipo === 'ingreso')
                                    <span class="badge bg-success" style="font-size:0.68rem;font-weight:600;">Ingreso</span>
                                @elseif($mov->tipo === 'egreso')
                                    <span class="badge bg-danger" style="font-size:0.68rem;font-weight:600;">Egreso</span>
                                @else
                                    <span class="badge bg-secondary" style="font-size:0.68rem;">{{ $mov->tipo }}</span>
                                @endif
                            </td>
                            <td class="{{ $mov->tipo === 'ingreso' ? 'monto-pos' : 'monto-neg' }}">
                                {{ $mov->tipo === 'ingreso' ? '+' : '-' }}{{ number_format($mov->monto, 2) }} Bs
                            </td>
                            <td style="color:var(--d-muted);font-size:0.8rem;">{{ $mov->referencia ?? '—' }}</td>
                            <td style="color:var(--d-muted);font-size:0.8rem;">{{ $mov->descripcion ?? '—' }}</td>
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
});
</script>
@endsection