<div class="tab-content-section" id="tab-plataforma">

    
    <div class="plt-header-bar">
        <div class="plt-header-left">
            <div class="plt-header-icon"><i class="ri-shield-keyhole-line"></i></div>
            <div>
                <div class="plt-header-title">Control de Acceso — Plataforma Moodle</div>
                <div class="plt-header-sub">Gestión de acceso por módulo para cada estudiante</div>
            </div>
        </div>
        <button id="btnRefreshPlataforma" class="plt-btn-refresh" onclick="cargarControlAcceso(true)">
            <i class="ri-refresh-line"></i> Actualizar
        </button>
    </div>

    
    <div class="plt-legend" id="plt-legend" style="display:none;">
        <span class="plt-chip plt-chip-pagado"><i class="ri-checkbox-circle-fill"></i> Pagado</span>
        <span class="plt-chip plt-chip-vencida"><i class="ri-alarm-warning-line"></i> Vencida</span>
        <span class="plt-chip plt-chip-pendiente"><i class="ri-time-line"></i> Pendiente</span>
        <span class="plt-chip plt-chip-sin"><i class="ri-minus-circle-line"></i> Sin cuota</span>
        <span class="plt-chip plt-chip-bloqueado"><i class="ri-lock-fill"></i> Bloqueado</span>
    </div>

    
    <div id="plataformaLoading" class="ins-state-box">
        <div class="spinner-border ins-spinner" style="color:#fc7b04;" role="status"></div>
        <p class="ins-state-text">Cargando datos de acceso…</p>
    </div>

    
    <div id="plataformaEmpty" class="ins-state-box" style="display:none;">
        <div class="ins-empty-icon" style="color:#fc7b04;"><i class="ri-shield-user-line"></i></div>
        <p class="ins-state-text">No hay estudiantes inscritos con estado <strong>Inscrito</strong>.</p>
    </div>

    
    <div id="plataformaContent" style="display:none;">
        <div class="plt-table-wrap">
            <table class="plt-tbl" id="tablaControlAcceso">
                <thead id="plataformaHead"></thead>
                <tbody id="plataformaBody"></tbody>
            </table>
        </div>
    </div>

</div>


<div class="modal fade" id="modalConfirmarAccesoPlataforma" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 10px 40px rgba(0,0,0,.2);">
            <div class="modal-header" style="border-bottom:1px solid #e2e8f0;padding:1rem 1.5rem;">
                <h5 class="modal-title" id="modalConfirmarAccesoPlataformaTitle" style="font-weight:600;color:#1e293b;font-size:.95rem;">
                    <i class="ri-shield-keyhole-line me-2"></i>Confirmar Acción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <p id="modalConfirmarAccesoPlataformaMsg" style="color:#475569;font-size:.9rem;line-height:1.6;margin:0;"></p>
                <div id="modalConfirmarAccesoPlataformaInfo"
                    style="margin-top:1rem;padding:.85rem 1rem;background:#f8fafc;border-radius:10px;border-left:4px solid #fc7b04;">
                    <strong style="color:#1e293b;font-size:.88rem;" id="modalEstudiantePlataforma"></strong>
                    <br><span style="color:#64748b;font-size:.8rem;" id="modalModuloPlataforma"></span>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:1rem 1.5rem;">
                <button type="button" data-bs-dismiss="modal"
                    style="padding:.45rem 1.1rem;border-radius:8px;border:1px solid #cbd5e1;background:white;color:#475569;font-weight:500;font-size:.85rem;cursor:pointer;">
                    Cancelar
                </button>
                <button type="button" id="btnConfirmarAccesoPlataforma"
                    style="padding:.45rem 1.1rem;border-radius:8px;border:none;background:#fc7b04;color:white;font-weight:600;font-size:.85rem;cursor:pointer;">
                    <i class="ri-check-line me-1"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/ofertas-academicas/partials/ofertas-detalle/tab-plataforma.blade.php ENDPATH**/ ?>