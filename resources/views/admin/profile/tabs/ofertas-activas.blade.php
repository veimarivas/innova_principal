{{-- Cargo principal banner --}}
<div id="cargoPrincipalBanner" class="oa-cargo-banner mb-4 d-none">
    <div class="oa-cargo-icon">
        <i class="ri-briefcase-4-line"></i>
    </div>
    <div class="oa-cargo-body">
        <div class="oa-cargo-label">Cargo asignado</div>
        <div id="cargoPrincipalText" class="oa-cargo-text"></div>
    </div>
    <div class="oa-cargo-decoration"></div>
</div>

{{-- Filtros --}}
<div class="oa-filters-card mb-4">
    <div class="oa-filters-header">
        <div class="oa-filters-header-left">
            <div class="oa-filters-icon">
                <i class="ri-filter-3-line"></i>
            </div>
            <div>
                <div class="oa-filters-title">Filtros de búsqueda</div>
                <div class="oa-filters-subtitle">Filtra las ofertas por sucursal, código o programa</div>
            </div>
        </div>
    </div>
    <div class="oa-filters-body">
        <form id="ofertasFilterForm">
            <div class="row g-3 align-items-end">
                <div class="col-xl-5 col-md-5 col-sm-12">
                    <label class="oa-label">
                        <i class="ri-search-line"></i> Buscar programa o código
                    </label>
                    <div class="oa-search-group">
                        <i class="ri-search-line oa-search-icon"></i>
                        <input type="text" name="search" id="ofertasSearch"
                            class="oa-search-input" placeholder="Nombre del programa, código...">
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <label class="oa-label">
                        <i class="ri-map-pin-line"></i> Sucursal
                    </label>
                    <select name="sucursal_id" id="ofertasSucursal" class="oa-select">
                        <option value="">Todas las sucursales</option>
                        @foreach (\App\Models\Sucursale::orderBy('nombre')->get() as $suc)
                            <option value="{{ $suc->id }}">{{ $suc->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-2 col-md-3 col-sm-6">
                    <label class="oa-label">
                        <i class="ri-list-check"></i> Por página
                    </label>
                    <select name="per_page" id="ofertasPerPage" class="oa-select">
                        <option value="10">10 resultados</option>
                        <option value="20">20 resultados</option>
                        <option value="50">50 resultados</option>
                    </select>
                </div>
                <div class="col-xl-2 col-md-12 col-sm-12 d-flex gap-2">
                    <button type="submit" class="oa-btn-filter flex-grow-1">
                        <i class="ri-filter-line"></i>
                        <span>Filtrar</span>
                    </button>
                    <button type="button" id="resetOfertasFilter" class="oa-btn-reset" title="Limpiar filtros">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabla de ofertas --}}
<div class="oa-table-card">
    {{-- Header --}}
    <div class="oa-table-header">
        <div class="oa-table-header-left">
            <div class="oa-table-icon">
                <i class="ri-calendar-event-line"></i>
            </div>
            <div>
                <h5 class="oa-table-title">
                    Ofertas Académicas Activas
                    <span id="ofertasCount" class="oa-count-badge">0</span>
                </h5>
                <p class="oa-table-subtitle">Listado de ofertas académicas disponibles para pre-inscripción</p>
            </div>
        </div>
        <div class="oa-table-header-right">
            <button id="refreshOfertas" class="oa-btn-refresh" title="Actualizar">
                <i class="ri-refresh-line"></i>
                <span>Actualizar</span>
            </button>
        </div>
    </div>

    {{-- Info hint --}}
    <div class="oa-hint-bar">
        <div class="oa-hint-icon">
            <i class="ri-qr-code-line"></i>
        </div>
        <span>Haz clic en el botón <strong><i class="ri-qr-code-line"></i> QR</strong> de cada fila para generar tu enlace personalizado de pre-inscripción y compartirlo con tus estudiantes.</span>
    </div>

    {{-- Body --}}
    <div class="mkt-table-body">
        <div id="ofertasTableContainer">
            <div class="oa-loading-state">
                <div class="oa-spinner">
                    <div class="spinner-border" role="status" style="color: var(--prof-primary); width:2rem; height:2rem;"></div>
                </div>
                <p class="oa-loading-text">Cargando ofertas académicas...</p>
            </div>
        </div>
        <div id="ofertasPagination" class="mt-2"></div>
    </div>
</div>
