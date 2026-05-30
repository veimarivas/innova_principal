{{-- Filtros --}}
<div class="mkt-filters-card mb-4">
    <div class="mkt-filters-header">
        <i class="ri-filter-3-line"></i>
        <span>Filtros de Oferta</span>
    </div>
    <div class="mkt-filters-body">
        <form id="ofertasFilterForm">
            <div class="row g-2 align-items-end">
                <div class="col-xl-4 col-md-5 col-sm-8">
                    <label class="mkt-label">Buscar programa o código</label>
                    <div class="mkt-search-group">
                        <i class="ri-search-line"></i>
                        <input type="text" name="search" id="ofertasSearch" class="mkt-search-input" placeholder="Nombre o código...">
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <label class="mkt-label">Sucursal</label>
                    <select name="sucursal_id" id="ofertasSucursal" class="mkt-select">
                        <option value="">Todas las sucursales</option>
                        @foreach (\App\Models\Sucursale::orderBy('nombre')->get() as $suc)
                            <option value="{{ $suc->id }}">{{ $suc->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-2 col-md-3 col-sm-6">
                    <label class="mkt-label">Resultados por página</label>
                    <select name="per_page" id="ofertasPerPage" class="mkt-select">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-xl-1 col-md-2 col-sm-4 d-flex align-items-end gap-1">
                    <button type="submit" class="mkt-btn-filter flex-grow-1">
                        <i class="ri-filter-line"></i>
                    </button>
                    <button type="button" id="resetOfertasFilter" class="mkt-btn-reset">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Cargo principal --}}
<div id="cargoPrincipalBanner" class="mkt-cargo-banner mb-4 d-none">
    <i class="ri-briefcase-line"></i>
    <span id="cargoPrincipalText"></span>
</div>

{{-- Tabla de ofertas --}}
<div class="mkt-table-card">
    <div class="mkt-table-header">
        <h5 class="mkt-table-title">
            <i class="ri-calendar-event-line"></i> Ofertas Académicas Activas
            <span id="ofertasCount" class="mkt-badge">0</span>
        </h5>
        <small style="color:#64748b;font-size:0.72rem;">Haz clic en <i class="ri-qr-code-line"></i> para generar tu enlace personalizado de pre-inscripción.</small>
        <button id="refreshOfertas" class="mkt-btn-outline">
            <i class="ri-refresh-line"></i>
        </button>
    </div>
    <div class="mkt-table-body">
        <div id="ofertasTableContainer">
            <div class="text-center py-5">
                <div class="spinner-border" role="status" style="color:#9a4904;"></div>
                <p class="mt-2 text-muted">Cargando ofertas...</p>
            </div>
        </div>
        <div id="ofertasPagination" class="mt-3"></div>
    </div>
</div>
