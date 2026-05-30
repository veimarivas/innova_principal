<div class="tab-content-section" id="tab-modulos">

    {{-- Header bar --}}
    <div class="tab-section-header">
        <div class="tab-section-header-left">
            <div class="tab-section-icon mod-icon-color"><i class="ri-layout-grid-line"></i></div>
            <div>
                <div class="tab-section-title">Módulos y Horarios</div>
                <div class="tab-section-sub">Organización académica y calendario de sesiones</div>
            </div>
        </div>
        <div class="tab-section-stats">
            <div class="tab-section-stat">
                <span class="tab-stat-value">{{ $oferta->n_modulos }}</span>
                <span class="tab-stat-label">Módulos</span>
            </div>
            <div class="tab-section-stat">
                <span class="tab-stat-value">{{ $oferta->cantidad_sesiones }}</span>
                <span class="tab-stat-label">Sesiones</span>
            </div>
        </div>
    </div>

    <div class="modulos-layout-wrapper">
        <div class="modulos-sidebar" id="modulosSidebar">
            <div class="modulos-sidebar-header">
                <div class="modulos-sidebar-title"><i class="ri-layout-grid-line"></i> Módulos</div>
            </div>
            <button class="btn-todos-modulos active" id="btnTodosModulos">
                <i class="ri-layout-grid-line"></i> Todos los módulos
            </button>
            <div id="modulosSidebarList">
                <div class="msi-loading"><i class="ri-loader-4-line msi-spin"></i> Cargando...</div>
            </div>
        </div>
        
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <div class="calendar-container">
            <div class="calendar-header">
                <div class="cal-header-row">
                    <div class="cal-header-left">
                        <button type="button" class="btn-toggle-sidebar" id="btnToggleSidebar" title="Alternar panel de módulos">
                            <i class="ri-menu-fold-line"></i>
                        </button>
                        <div class="cal-title-wrap">
                            <i class="ri-calendar-event-line cal-title-icon"></i>
                            <span class="cal-title-text">Calendario de Sesiones</span>
                        </div>
                    </div>
                    {{-- Acciones rápidas --}}
                    <div class="cal-header-actions">
                        <div class="cal-legend">
                            <span class="cal-legend-dot" style="background:#22c55e;"></span><span>Confirmado</span>
                            <span class="cal-legend-dot" style="background:transparent;border:1.5px dashed #94a3b8;"></span><span>Postergado</span>
                        </div>
                    </div>
                </div>

                {{-- Panel de módulo activo (se muestra cuando hay uno seleccionado) --}}
                <div id="moduloActivoPanel" class="modulo-activo-panel" style="display:none;">
                    <div class="map-bar" id="moduloActivoBar"></div>
                    <div class="map-body">
                        <div class="map-icon-wrap" id="moduloActivoIconWrap">
                            <i class="ri-layout-grid-line"></i>
                        </div>
                        <div class="map-info">
                            <div class="map-label">Filtrando módulo</div>
                            <div class="map-nombre" id="moduloActivoNombre">—</div>
                        </div>
                        <button type="button" class="map-clear modulo-badge-close" title="Ver todos los módulos">
                            <i class="ri-close-circle-fill"></i> Todos
                        </button>
                    </div>
                </div>

                {{-- Badge compacto (legacy — oculto, se mantiene para compatibilidad JS) --}}
                <div id="moduloSeleccionadoBadge" class="modulo-seleccionado-badge" style="display:none;">
                    <span class="modulo-badge-color"></span>
                    <span class="modulo-badge-name"></span>
                    <button type="button" class="modulo-badge-close" title="Quitar filtro"><i class="ri-close-line"></i></button>
                </div>
            </div>
            <div id="calendar"></div>
        </div>
    </div>
</div>
