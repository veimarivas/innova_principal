
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
                        <?php for($i = date('Y'); $i >= 2020; $i--): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e($i == date('Y') ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-xl-2 col-md-3 col-sm-6">
                    <label class="mkt-label">Mes</label>
                    <select name="month" id="marketingMonth" class="mkt-select">
                        <option value="todos">Todos los meses</option>
                        <?php $meses=[1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre']; ?>
                        <?php $__currentLoopData = $meses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k); ?>"><?php echo e($m); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <?php $__currentLoopData = \App\Models\Programa::orderBy('nombre')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($programa->id); ?>"><?php echo e($programa->nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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


<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="mkt-chart-card">
            <div class="mkt-chart-header">
                <h5 class="mkt-chart-title">
                    <i class="ri-bar-chart-line"></i>
                    <span id="chartTitle">Inscripciones por Mes (<?php echo e(date('Y')); ?>)</span>
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


<div class="modal fade" id="modalComprobante" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width:620px;">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 10px 40px rgba(0,0,0,.2);">
            <div class="modal-header" style="background:linear-gradient(135deg,#9a4904,#df6a04);color:white;border-radius:12px 12px 0 0;">
                <h5 class="modal-title" style="font-weight:600;">
                    <i class="ri-file-list-3-line me-2"></i>Subir Comprobante de Pago
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">

                
                <div id="compInscritoInfo" style="padding:.75rem 1rem;background:#f8fafc;border-radius:8px;border-left:4px solid #fc7b04;margin-bottom:1.25rem;">
                    <div style="font-weight:600;color:#1e293b;" id="compEstudianteNombre"></div>
                    <div style="font-size:.8rem;color:#64748b;margin-top:.2rem;" id="compEstudianteDetalle"></div>
                </div>

                
                <div style="margin-bottom:1rem;">
                    <label style="font-size:.8rem;font-weight:600;color:#475569;display:block;margin-bottom:.4rem;">
                        Archivo del comprobante <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="file" id="compArchivo" accept=".jpg,.jpeg,.png,.pdf"
                        style="border:1px solid #e2e8f0;border-radius:6px;padding:.4rem .75rem;font-size:.85rem;width:100%;background:#f8fafc;">
                    <div style="font-size:.72rem;color:#94a3b8;margin-top:.3rem;">JPG, PNG o PDF — máx. 5 MB</div>
                </div>

                
                <div style="margin-bottom:1.25rem;">
                    <label style="font-size:.8rem;font-weight:600;color:#475569;display:block;margin-bottom:.4rem;">Observaciones</label>
                    <textarea id="compObservaciones" rows="2" placeholder="Opcional..."
                        style="border:1px solid #e2e8f0;border-radius:6px;padding:.5rem .75rem;font-size:.85rem;width:100%;background:#f8fafc;resize:vertical;"></textarea>
                </div>

                
                <div>
                    <label style="font-size:.8rem;font-weight:600;color:#475569;display:block;margin-bottom:.5rem;">
                        Cuotas que cubre este comprobante <span style="color:#dc2626;">*</span>
                    </label>
                    <div id="compCuotasLoading" class="text-center py-3">
                        <div class="spinner-border spinner-border-sm" style="color:#9a4904;"></div>
                        <span class="ms-2 text-muted" style="font-size:.8rem;">Cargando cuotas...</span>
                    </div>
                    <div id="compCuotasContainer" style="display:none;"></div>
                </div>

            </div>
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;">
                <button type="button" class="btn" data-bs-dismiss="modal"
                    style="padding:.5rem 1.25rem;border-radius:6px;border:1px solid #cbd5e1;background:white;color:#475569;font-weight:500;">
                    Cancelar
                </button>
                <button type="button" id="btnEnviarComprobante"
                    style="padding:.5rem 1.25rem;border-radius:6px;border:none;background:#9a4904;color:white;font-weight:500;cursor:pointer;">
                    <i class="ri-upload-cloud-line me-1"></i> Enviar Comprobante
                </button>
            </div>
        </div>
    </div>
</div>


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
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/profile/tabs/marketing.blade.php ENDPATH**/ ?>