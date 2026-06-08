{{-- Filtros --}}
<div class="mkt-filters-card mb-4">
    <div class="mkt-filters-header">
        <i class="ri-filter-3-line"></i>
        <span>Filtros Avanzados</span>
    </div>
    <div class="mkt-filters-body">
        <form id="marketingFilterForm">
            <div class="row g-2 align-items-end">
                <div class="col-xl-2 col-md-3 col-sm-6">
                    <label class="mkt-label">Año</label>
                    <select name="year" id="marketingYear" class="mkt-select">
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-xl-2 col-md-3 col-sm-6">
                    <label class="mkt-label">Mes</label>
                    <select name="month" id="marketingMonth" class="mkt-select">
                        <option value="todos" {{ date('n') == 'todos' ? 'selected' : '' }}>Todos los meses</option>
                        @php $meses=[1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre']; @endphp
                        @foreach ($meses as $k => $m)
                            <option value="{{ $k }}" {{ $k == date('n') ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-2 col-md-3 col-sm-6">
                    <label class="mkt-label">Estado</label>
                    <select name="estado" id="marketingEstado" class="mkt-select">
                        <option value="">Todos</option>
                        <option value="Inscrito">Inscrito</option>
                        <option value="Pre-Inscrito">Pre-Inscrito</option>
                    </select>
                </div>
                <div class="col-xl-3 col-md-3 col-sm-6">
                    <label class="mkt-label">Programa</label>
                    <select name="programa_id" id="marketingPrograma" class="mkt-select">
                        <option value="">Todos los programas</option>
                        @foreach (\App\Models\Programa::orderBy('nombre')->get() as $programa)
                            <option value="{{ $programa->id }}">{{ $programa->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-2 col-md-8 col-sm-8">
                    <label class="mkt-label">Buscar</label>
                    <div class="mkt-search-group">
                        <i class="ri-search-line"></i>
                        <input type="text" name="search" id="marketingSearch" class="mkt-search-input" placeholder="Nombre o carnet...">
                    </div>
                </div>
                <div class="col-xl-1 col-md-4 col-sm-4 d-flex align-items-end gap-1">
                    <button type="submit" class="mkt-btn-filter flex-grow-1">
                        <i class="ri-filter-line"></i>
                    </button>
                    <button type="button" id="resetMarketingFilter" class="mkt-btn-reset">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Estadísticas rápidas --}}
<div class="row g-2 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="mkt-stat-card">
            <div class="mkt-stat-body">
                <div class="flex-grow-1">
                    <div class="mkt-stat-value" id="totalInscripcionesCard">0</div>
                    <p class="mkt-stat-label">Total Inscripciones</p>
                </div>
                <div class="mkt-stat-icon" style="background:rgba(154,73,4,0.10);color:#9a4904;">
                    <i class="ri-user-add-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mkt-stat-card">
            <div class="mkt-stat-body">
                <div class="flex-grow-1">
                    <div class="mkt-stat-value" id="totalInscritosCard">0</div>
                    <p class="mkt-stat-label">Inscritos</p>
                </div>
                <div class="mkt-stat-icon" style="background:rgba(16,185,129,0.10);color:#10b981;">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mkt-stat-card">
            <div class="mkt-stat-body">
                <div class="flex-grow-1">
                    <div class="mkt-stat-value" id="totalPreInscritosCard">0</div>
                    <p class="mkt-stat-label">Pre-Inscritos</p>
                </div>
                <div class="mkt-stat-icon" style="background:rgba(252,123,4,0.12);color:#fc7b04;">
                    <i class="ri-time-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mkt-stat-card">
            <div class="mkt-stat-body">
                <div class="flex-grow-1">
                    <div class="mkt-stat-value" style="font-size:1rem;" id="periodoActualCard">—</div>
                    <p class="mkt-stat-label">Período Actual</p>
                </div>
                <div class="mkt-stat-icon" style="background:rgba(100,116,139,0.10);color:#64748b;">
                    <i class="ri-calendar-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Gráficos --}}
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="mkt-chart-card">
            <div class="mkt-chart-header">
                <h5 class="mkt-chart-title">
                    <i class="ri-bar-chart-line"></i>
                    <span id="chartTitle">Inscripciones por Mes ({{ date('Y') }})</span>
                </h5>
            </div>
            <div class="mkt-chart-body">
                <div style="height:280px;position:relative;">
                    <canvas id="marketingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mkt-chart-card">
            <div class="mkt-chart-header">
                <h5 class="mkt-chart-title">
                    <i class="ri-pie-chart-line"></i> Top 5 Programas
                </h5>
            </div>
            <div class="mkt-chart-body">
                <div style="height:280px;position:relative;">
                    <canvas id="programasChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabla de inscripciones --}}
<div class="mkt-table-card">
    <div class="mkt-table-header">
        <h5 class="mkt-table-title">
            <i class="ri-list-check"></i> Lista de Inscripciones
            <span id="tableCount" class="mkt-badge">0</span>
        </h5>
        <button id="refreshMarketing" class="mkt-btn-outline">
            <i class="ri-refresh-line"></i>
        </button>
    </div>
    <div class="mkt-table-body">
        <div id="marketingTableContainer">
            <div class="text-center py-5">
                <div class="spinner-border" role="status" style="color:#9a4904;"></div>
                <p class="mt-2 text-muted">Cargando datos...</p>
            </div>
        </div>
        <div id="marketingPagination" class="mt-3"></div>
    </div>
</div>

{{-- Sección Comprobantes de Pago --}}
<div class="mkt-table-card mt-4">
    <div class="mkt-table-header">
        <h5 class="mkt-table-title">
            <i class="ri-file-list-3-line"></i> Comprobantes de Pago
            <span id="comprobantesCount" class="mkt-badge">0</span>
        </h5>
        <button id="refreshInscritos" class="mkt-btn-outline" title="Recargar">
            <i class="ri-refresh-line"></i>
        </button>
    </div>
    <div class="mkt-table-body">
        <div id="inscritosComprobanteContainer">
            <div class="text-center py-4">
                <div class="spinner-border" role="status" style="color:#9a4904;"></div>
                <p class="mt-2 text-muted" style="font-size:.875rem;">Cargando inscritos...</p>
            </div>
        </div>
    </div>
</div>

{{-- Modal Subir Comprobante --}}
<div class="modal fade" id="modalComprobante" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width:620px;">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">

            <div class="modal-header" style="background:linear-gradient(135deg,#9a4904,#df6a04,#fc7b04);border:none;padding:1.25rem 1.5rem;display:flex;align-items:center;gap:14px;">
                <div style="width:44px;height:44px;border-radius:12px;flex-shrink:0;background:rgba(255,255,255,0.18);border:1px solid rgba(255,255,255,0.25);display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
                    <i class="ri-file-list-3-line" style="color:#fff;"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <h5 class="modal-title" style="font-weight:700;font-size:1.05rem;color:#fff;margin:0;">
                        Subir Comprobante de Pago
                    </h5>
                    <div style="font-size:0.73rem;color:rgba(255,255,255,0.85);margin-top:2px;">Registrar documento de pago del estudiante</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="opacity:0.8;"></button>
            </div>

            <div class="modal-body" style="padding:1.5rem;">

                {{-- Info estudiante --}}
                <div id="compInscritoInfo" style="display:flex;align-items:flex-start;gap:12px;padding:.75rem 1rem;background:#fffbeb;border-radius:10px;border:1px solid rgba(252,123,4,.2);margin-bottom:1.25rem;">
                    <div style="width:40px;height:40px;border-radius:10px;background:rgba(252,123,4,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-user-line" style="color:#fc7b04;font-size:1.1rem;"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;color:#1e293b;font-size:.88rem;" id="compEstudianteNombre"></div>
                        <div style="font-size:.78rem;color:#64748b;margin-top:.2rem;" id="compEstudianteDetalle"></div>
                    </div>
                </div>

                {{-- Archivo --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#475569;display:block;margin-bottom:.35rem;">
                        <i class="ri-upload-cloud-line" style="color:#fc7b04;font-size:.8rem;"></i> Archivo del comprobante <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="file" id="compArchivo" accept=".jpg,.jpeg,.png,.pdf"
                        style="width:100%;padding:.5rem .75rem;border:1.5px solid #e2e8f0;border-radius:9px;font-size:.85rem;background:#f8fafc;transition:border-color .2s;"
                        onfocus="this.style.borderColor='#9a4904';this.style.boxShadow='0 0 0 3px rgba(154,73,4,.1)';this.style.background='#fff'"
                        onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';this.style.background='#f8fafc'">
                    <div style="font-size:.7rem;color:#94a3b8;margin-top:.35rem;display:flex;align-items:center;gap:4px;">
                        <i class="ri-information-line"></i> JPG, PNG o PDF — máx. 5 MB
                    </div>
                </div>

                {{-- Observaciones --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#475569;display:block;margin-bottom:.35rem;">
                        <i class="ri-edit-line" style="color:#fc7b04;font-size:.8rem;"></i> Observaciones
                    </label>
                    <textarea id="compObservaciones" rows="2" placeholder="Opcional..."
                        style="width:100%;padding:.5rem .75rem;border:1.5px solid #e2e8f0;border-radius:9px;font-size:.85rem;background:#f8fafc;resize:vertical;transition:border-color .2s;font-family:'Plus Jakarta Sans',sans-serif;"
                        onfocus="this.style.borderColor='#9a4904';this.style.boxShadow='0 0 0 3px rgba(154,73,4,.1)';this.style.background='#fff'"
                        onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';this.style.background='#f8fafc'"></textarea>
                </div>

                {{-- Cuotas --}}
                <div>
                    <label style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#475569;display:block;margin-bottom:.45rem;">
                        <i class="ri-coins-line" style="color:#fc7b04;font-size:.8rem;"></i> Cuotas que cubre este comprobante <span style="color:#dc2626;">*</span>
                    </label>
                    <div id="compCuotasLoading" style="text-align:center;padding:1.25rem 0;">
                        <div class="spinner-border spinner-border-sm" style="color:#9a4904;"></div>
                        <span style="margin-left:.5rem;font-size:.8rem;color:#64748b;">Cargando cuotas...</span>
                    </div>
                    <div id="compCuotasContainer" style="display:none;"></div>
                </div>

            </div>

            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;display:flex;gap:8px;">
                <button type="button" class="btn" data-bs-dismiss="modal"
                    style="display:inline-flex;align-items:center;gap:6px;padding:.5rem 1.25rem;border-radius:9px;border:1px solid #cbd5e1;background:white;color:#475569;font-weight:600;font-size:.85rem;cursor:pointer;transition:all .2s;"
                    onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                    <i class="ri-close-line"></i> Cancelar
                </button>
                <button type="button" id="btnEnviarComprobante"
                    style="display:inline-flex;align-items:center;gap:6px;padding:.5rem 1.25rem;border-radius:9px;border:none;background:linear-gradient(135deg,#9a4904,#df6a04);color:white;font-weight:700;font-size:.85rem;cursor:pointer;transition:all .2s;box-shadow:0 4px 12px rgba(154,73,4,.28);"
                    onmouseover="this.style.boxShadow='0 6px 18px rgba(154,73,4,.38)'" onmouseout="this.style.boxShadow='0 4px 12px rgba(154,73,4,.28)'">
                    <i class="ri-upload-cloud-line"></i> Enviar Comprobante
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Modal Cambiar a Inscrito --}}
<div class="modal fade" id="modalCambiarAInscritoMkt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.18);">

            <div class="modal-header" style="background:linear-gradient(135deg,#78350f,#b45309,#f59e0b);color:white;padding:1.25rem 1.5rem;border:none;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ri-user-star-line" style="font-size:1.4rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-size:1rem;font-weight:700;color:#fff;">Cambiar a Inscrito</h5>
                        <div style="font-size:.73rem;color:rgba(255,255,255,.8);margin-top:.15rem;">Asignar plan de pago y completar la inscripción</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding:0;">

                {{-- Info estudiante --}}
                <div style="padding:1.25rem 1.5rem;background:#fffbeb;border-bottom:1px solid rgba(245,158,11,.25);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#fbbf24);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 14px rgba(245,158,11,.35);">
                            <i class="ri-user-line" style="font-size:1.3rem;color:#78350f;"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:700;font-size:.95rem;color:#1e293b;" id="mktCambiarEstNombre">—</div>
                            <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                <span style="display:inline-flex;align-items:center;gap:.25rem;font-size:.73rem;color:#92400e;background:rgba(245,158,11,.15);padding:.2rem .65rem;border-radius:20px;border:1px solid rgba(245,158,11,.35);font-weight:600;">
                                    <i class="ri-id-card-line" style="font-size:.75rem;"></i>
                                    <span id="mktCambiarEstCi">—</span>
                                </span>
                                <span style="display:inline-flex;align-items:center;gap:.25rem;font-size:.72rem;color:#92400e;background:rgba(245,158,11,.2);padding:.2rem .65rem;border-radius:20px;border:1px solid rgba(245,158,11,.4);font-weight:700;">
                                    <i class="ri-user-add-line" style="font-size:.75rem;"></i> Pre-Inscrito
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Selección de plan --}}
                <div style="padding:1.25rem 1.5rem;">
                    <label class="form-label" style="font-weight:700;font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#475569;margin-bottom:.55rem;">
                        <i class="ri-file-list-3-line me-1" style="color:#f59e0b;"></i>Plan de Pago <span class="text-danger">*</span>
                    </label>
                    <select id="mktCambiarPlanSelect" class="form-select" style="border-radius:10px;border:1.5px solid #e2e8f0;font-size:.85rem;padding:.65rem .875rem;color:#334155;">
                        <option value="">— Seleccionar plan de pago —</option>
                    </select>

                    {{-- Detalle plan --}}
                    <div id="mktCambiarPlanDetalle" class="mt-3" style="display:none;">
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                            <div style="padding:.6rem 1rem;background:linear-gradient(135deg,rgba(245,158,11,.07),rgba(251,191,36,.03));border-bottom:1px solid rgba(245,158,11,.15);display:flex;align-items:center;gap:.5rem;">
                                <i class="ri-list-ordered" style="color:#f59e0b;font-size:.9rem;"></i>
                                <span style="font-size:.72rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.4px;">Detalle del Plan</span>
                            </div>
                            <div style="padding:.75rem 1rem;" id="mktCambiarPlanDetalleBody"></div>
                        </div>
                    </div>

                    <div style="margin-top:1rem;padding:.75rem 1rem;background:rgba(245,158,11,.05);border:1px dashed rgba(245,158,11,.35);border-radius:10px;display:flex;align-items:flex-start;gap:.6rem;">
                        <i class="ri-information-line" style="color:#f59e0b;font-size:1rem;flex-shrink:0;margin-top:.05rem;"></i>
                        <p class="mb-0" style="font-size:.77rem;color:#64748b;line-height:1.6;">Al confirmar se crearán las cuotas correspondientes al plan seleccionado y se completará la inscripción del estudiante.</p>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;background:#f8fafc;gap:.5rem;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;font-size:.82rem;padding:.4rem 1rem;">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
                <button type="button" id="mktBtnConfirmarCambiar"
                    style="background:linear-gradient(135deg,#f59e0b,#fbbf24);border:none;color:#78350f;border-radius:8px;font-size:.82rem;padding:.4rem 1.15rem;font-weight:700;box-shadow:0 4px 12px rgba(245,158,11,.35);">
                    <i class="ri-user-check-line me-1"></i>Confirmar Inscripción
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Modal de Documentos --}}
<div class="modal fade" id="documentosModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #9a4904, #df6a04); color: white;">
                <h5 class="modal-title">
                    <i class="ri-file-info-line me-2"></i>Documentos del Estudiante
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="documentosEstudianteInfo" class="mb-3 p-3" style="background: var(--prof-surface); border-radius: 8px;">
                    <h6 class="mb-1" id="docEstudianteNombre">—</h6>
                    <small class="text-muted" id="docEstudianteCarnet">—</small>
                </div>
                <div id="documentosList" class="row g-3">
                    <div class="text-center py-4">
                        <div class="spinner-border" role="status" style="color:#9a4904;"></div>
                        <p class="mt-2 text-muted">Cargando documentos...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
