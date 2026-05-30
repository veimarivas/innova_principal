# Mejoras en Vista Listar Ofertas Académicas

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Optimizar filtros (una fila), quitar controles DataTable innecesarios, agregar colores a fechas, mejorar diseño de fase, y agregar modal de planes de pago.

**Architecture:** Modificaciones en vista Blade y controlador. Los cambios son visuales y de UX, sin cambios en modelo de datos.

**Tech Stack:** Laravel Blade, jQuery DataTables, CSS personalizado, AJAX.

---

## Archivos a modificar

| Archivo | Responsabilidad |
|---------|-----------------|
| `resources/views/admin/ofertas-academicas/listar.blade.php` | CSS de filtros, columnas, modal, JavaScript |
| `app/Http/Controllers/OfertasAcademicaController.php` | Nuevo endpoint para planes de pago |
| `routes/web.php` | Nueva ruta para endpoint |

---

### Task 1: Optimizar filtros en una sola fila

**Files:**
- Modify: `resources/views/admin/ofertas-academicas/listar.blade.php:84-148`

**Steps:**

- [ ] **Step 1: Modificar CSS de filtros**

Reemplazar el CSS actual de `.filters-container` (líneas 94-104) con:

```css
.filters-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem 1rem;
    align-items: flex-end;
}

.filter-group {
    flex: 1 0 auto;
    min-width: 100px;
    max-width: 180px;
}

.filter-group label {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    margin-bottom: 0.25rem;
    display: block;
}

.filter-group select,
.filter-group input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--gray-200);
    border-radius: 0.5rem;
    font-size: 0.85rem;
    background: white;
}
```

- [ ] **Step 2: Ajustar inputs de filtro**

En las líneas del HTML de filtros (336-396), asegurar que el grupo de búsqueda tenga `style="min-width: 160px;"` y los botones tengan `style="min-width: auto;"`.

- [ ] **Step 3: Verificar en responsive**

El media query existente (líneas 301-316) ya maneja el caso móvil, pero asegurar que los filtros pasen a columna en móviles.
```

---

### Task 2: Quitar "Mostrar" y "Buscar" de DataTable

**Files:**
- Modify: `resources/views/admin/ofertas-academicas/listar.blade.php:511`

**Steps:**

- [ ] **Step 1: Cambiar dom de DataTable**

Cambiar la línea 511:
```php
dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
```

Por:
```php
dom: 'ftrip',
```

Esto elimina:
- `l` (length - dropdown "Mostrar X")
- `f` (filter - input de búsqueda interno de DataTable)

El buscador personalizado ya existe (`#searchOfertas`) y funciona con el evento keyup.

- [ ] **Step 2: Ocultar estilos de DataTable length**

Agregar en el CSS (después de línea 176):
```css
.dataTables_wrapper .dataTables_length {
    display: none;
}
```

---

### Task 3: Columna Fechas con colores por estado

**Files:**
- Modify: `app/Http/Controllers/OfertasAcademicaController.php:570-595`
- Modify: `resources/views/admin/ofertas-academicas/listar.blade.php:283-290`

**Steps:**

- [ ] **Step 1: Agregar clase CSS para fechas**

Agregar en el CSS (después de línea 286):
```css
.fecha-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.2rem 0.5rem;
    border-radius: 6px;
}

.fecha-badge.proxima {
    background: rgba(34, 197, 94, 0.12);
    color: #16a34a;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.fecha-badge.en-proceso {
    background: rgba(245, 158, 11, 0.12);
    color: #d97706;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.fecha-badge.en-curso {
    background: rgba(239, 68, 68, 0.12);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.2);
}
```

- [ ] **Step 2: Modificar lógica de fechas en controlador**

En el método `listarGlobal()` del controlador, modificar la construcción de `$fechasHtml` (alrededor de línea 570-595) para incluir clases según el estado:

```php
$hoy = now()->toDateString();
$fechaInicioInsc = $oferta->fecha_inicio_inscripciones?->toDateString();
$fechaInicioProg = $oferta->fecha_inicio_programa?->toDateString();
$fechaFinProg = $oferta->fecha_fin_programa?->toDateString();

$badgeClass = 'en-curso';
$badgeIcon = 'ri-time-line';

if ($fechaInicioInsc && $fechaInicioInsc > $hoy) {
    $badgeClass = 'proxima';
    $badgeIcon = 'ri-calendar-event-line';
} elseif ($fechaInicioInsc && $fechaInicioProg && $hoy >= $fechaInicioInsc && $hoy <= $fechaInicioProg) {
    $badgeClass = 'en-proceso';
    $badgeIcon = 'ri-loader-4-line';
}

$fechasHtml = '
    <div class="fechas-cell">
        <div class="fecha-badge ' . $badgeClass . '">
            <i class="' . $badgeIcon . '"></i>
            ' . ($oferta->fecha_inicio_programa?->format('d/m/Y') ?? '—') . '
        </div>
    </div>';
```

---

### Task 4: Columna Fase mejorada con iconos

**Files:**
- Modify: `app/Http/Controllers/OfertasAcademicaController.php:600-620`
- Modify: `resources/views/admin/ofertas-academicas/listar.blade.php:219-226`

**Steps:**

- [ ] **Step 1: Agregar CSS de badge-fase-mejorado**

Reemplazar el CSS de `.badge-fase` (líneas 219-226) con versión mejorada:

```css
.badge-fase {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: linear-gradient(135deg, rgba(252, 123, 4, 0.15), rgba(154, 73, 4, 0.1));
    border: 1px solid rgba(252, 123, 4, 0.25);
    color: #9a4904;
    font-weight: 600;
    font-size: 0.75rem;
    padding: 0.25rem 0.6rem;
    border-radius: 20px;
}

.badge-fase i {
    font-size: 0.85rem;
}
```

- [ ] **Step 2: Modificar construcción del badge de fase**

En el controlador, donde se genera `$faseHtml` (alrededor de línea 600-620), cambiar para incluir icono según tipo de fase:

```php
$faseNombre = $oferta->fase?->nombre ?? 'Sin fase';
$faseIcon = 'ri-circle-line';

// Detectar tipo de fase por nombre
$faseLower = strtolower($faseNombre);
if (str_contains($faseLower, 'inscrip')) {
    $faseIcon = 'ri-shopping-bag-line';
} elseif (str_contains($faseLower, 'curso')) {
    $faseIcon = 'ri-time-line';
} elseif (str_contains($faseLower, 'fin') || str_contains($faseLower, 'culmin')) {
    $faseIcon = 'ri-check-double-line';
}

$faseHtml = '
    <span class="badge-fase">
        <i class="' . $faseIcon . '"></i>
        ' . $faseNombre . '
    </span>';
```

---

### Task 5: Agregar endpoint para listar planes de pago

**Files:**
- Modify: `app/Http/Controllers/OfertasAcademicaController.php:24` (agregar use)
- Create: `app/Http/Controllers/OfertasAcademicaController.php:nuevo método`
- Modify: `routes/web.php:nueva ruta`

**Steps:**

- [ ] **Step 1: Agregar modelo ConfiguracionPrecio si no existe**

Verificar si existe el modelo `ConfiguracionPrecio`. Si no existe, crear en `app/Models/ConfiguracionPrecio.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionPrecio extends Model
{
    protected $table = 'configuracion_precios';
    
    protected $fillable = [
        'oferta_academica_id',
        'plan_pago_id',
        'concepto_id',
        'cuotas',
        'precio_regular_bs',
        'descuento_bs',
        'pago_bs',
    ];

    public function oferta()
    {
        return $this->belongsTo(OfertasAcademica::class, 'oferta_academica_id');
    }

    public function planPago()
    {
        return $this->belongsTo(PlanesPago::class, 'plan_pago_id');
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'concepto_id');
    }
}
```

- [ ] **Step 2: Agregar método en controlador**

Agregar al final del `OfertasAcademicaController` (antes del cierre de clase):

```php
public function listarConfiguracionesPrecio($ofertaId)
{
    try {
        $configs = ConfiguracionPrecio::with(['planPago', 'concepto'])
            ->where('oferta_academica_id', $ofertaId)
            ->get();
            
        return response()->json([
            'data' => $configs->map(function($c) {
                return [
                    'id' => $c->id,
                    'plan_pago' => $c->planPago ? [
                        'id' => $c->planPago->id,
                        'nombre' => $c->planPago->nombre,
                        'es_promocion' => $c->planPago->es_promocion,
                        'fecha_inicio_promocion' => $c->planPago->fecha_inicio_promocion,
                        'fecha_fin_promocion' => $c->planPago->fecha_fin_promocion,
                    ] : null,
                    'concepto' => $c->concepto ? [
                        'id' => $c->concepto->id,
                        'nombre' => $c->concepto->nombre,
                    ] : null,
                    'cuotas' => $c->cuotas,
                    'precio_regular_bs' => $c->precio_regular_bs,
                    'descuento_bs' => $c->descuento_bs,
                    'pago_bs' => $c->pago_bs,
                ];
            })
        ];
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
```

- [ ] **Step 3: Agregar ruta**

En `routes/web.php`, agregar después de la ruta de `listarGlobal` (línea 128):

```php
Route::get('/ofertas-academicas/{id}/configuraciones-precio', [OfertasAcademicaController::class, 'listarConfiguracionesPrecio'])->name('admin.ofertas.configuracionesPrecio');
```

---

### Task 6: Botón "Ver Planes de Pago" y Modal

**Files:**
- Modify: `app/Http/Controllers/OfertasAcademicaController.php:630-640`
- Modify: `resources/views/admin/ofertas-academicas/listar.blade.php:396-420`
- Modify: `resources/views/admin/ofertas-academicas/listar.blade.php:424-535`

**Steps:**

- [ ] **Step 1: Modificar botón de acciones en controlador**

En la generación de `$accionesHtml` del método `listarGlobal` (alrededor de línea 630-640), agregar botón de ver planes:

```php
$planesBtn = '
    <button class="action-btn btn-ver-planes" 
            data-oferta-id="' . $oferta->id . '" 
            data-codigo="' . escHtml($oferta->codigo) . '"
            title="Ver Planes de Pago">
        <i class="ri-credit-card-line"></i>
    </button>';

$accionesHtml = '
    <div class="d-flex gap-1">
        <a href="' . route('admin.ofertas.detalle', $oferta->id) . '" class="action-btn" title="Ver Detalle">
            <i class="ri-eye-line"></i>
        </a>
        ' . $planesBtn . '
    </div>';
```

- [ ] **Step 2: Agregar HTML del modal**

En `listar.blade.php`, después de la tabla (línea 421) y antes del `@section('script')`, agregar:

```html
<div class="modal fade" id="modalPlanesPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title">
                    <i class="ri-credit-card-line"></i> Planes de Pago - <span id="planesOfertaCodigo"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="planesPagoLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando planes de pago...</p>
                </div>
                <div id="planesPagoEmpty" class="text-center py-4" style="display: none;">
                    <i class="ri-inbox-line fs-1 text-muted"></i>
                    <p class="mt-2 text-muted">No hay planes de pago configurados</p>
                </div>
                <div id="planesPagoContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
```

- [ ] **Step 3: Agregar CSS del modal**

Agregar en el bloque `@section('css')` (después de línea 316):

```css
.modal-header-gradient {
    background: linear-gradient(135deg, #391b04 0%, #5c2d0a 50%, #c96004 100%);
    border-bottom: none;
    padding: 1.25rem 1.5rem;
}
.modal-header-gradient .modal-title {
    color: #fff;
    font-weight: 700;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.modal-header-gradient .modal-title i { font-size: 1.25rem; }
.modal-header-gradient .btn-close {
    filter: invert(1);
    opacity: 0.8;
}
.modal-header-gradient .btn-close:hover { opacity: 1; }
```

- [ ] **Step 4: Agregar JavaScript para cargar planes**

En `@section('script')`, agregar después de la inicialización de DataTable (después de línea 533):

```javascript
// Modal de Planes de Pago
let planesPagoData = [];

$(document).on('click', '.btn-ver-planes', function(e) {
    e.preventDefault();
    const ofertaId = $(this).data('oferta-id');
    const codigo = $(this).data('codigo');
    
    $('#planesOfertaCodigo').text(codigo);
    $('#planesPagoLoading').show();
    $('#planesPagoEmpty').hide();
    $('#planesPagoContainer').empty();
    
    $.ajax({
        url: '/admin/ofertas-academicas/' + ofertaId + '/configuraciones-precio',
        type: 'GET',
        success: function(response) {
            $('#planesPagoLoading').hide();
            planesPagoData = response.data || [];
            
            if (!planesPagoData.length) {
                $('#planesPagoEmpty').show();
                return;
            }
            
            renderPlanesPagoCards();
            $('#modalPlanesPago').modal('show');
        },
        error: function() {
            $('#planesPagoLoading').hide();
            alert('Error al cargar los planes de pago');
        }
    });
});

function renderPlanesPagoCards() {
    const $container = $('#planesPagoContainer');
    
    // Similar a renderContableCards() en detalle.blade.php
    const planes = {};
    planesPagoData.forEach(function(item) {
        const planNombre = item.plan_pago?.nombre || 'Sin plan';
        const planId = item.plan_pago?.id || 0;
        const esPromocion = item.plan_pago?.es_promocion || false;
        const fechaInicio = item.plan_pago?.fecha_inicio_promocion || null;
        const fechaFin = item.plan_pago?.fecha_fin_promocion || null;
        
        if (!planes[planId]) {
            planes[planId] = { 
                nombre: planNombre, 
                es_promocion: esPromocion, 
                fecha_inicio: fechaInicio, 
                fecha_fin: fechaFin, 
                conceptos: [] 
            };
        }
        planes[planId].conceptos.push(item);
    });
    
    let html = '';
    Object.keys(planes).forEach(function(planId) {
        const plan = planes[planId];
        let totalPlan = 0;
        plan.conceptos.forEach(function(c) { 
            totalPlan += parseFloat(c.pago_bs || 0); 
        });
        
        const cardClass = plan.es_promocion ? 'contable-plan-card contable-plan-promo mb-3' : 'contable-plan-card mb-3';
        const promoBadge = plan.es_promocion 
            ? '<span class="contable-promo-badge"><i class="ri-gift-line"></i> Promoción</span>' 
            : '';
        
        html += '<div class="' + cardClass + '">';
        html += '<div class="contable-plan-header">';
        html += '<div class="contable-plan-header-left">';
        html += '<span class="contable-plan-nombre">' + plan.nombre + '</span>';
        html += promoBadge;
        html += '</div>';
        html += '<div class="contable-plan-header-right">';
        html += '<span class="contable-plan-total">Bs. ' + totalPlan.toFixed(2) + '</span>';
        html += '</div></div>';
        
        if (plan.es_promocion && plan.fecha_inicio && plan.fecha_fin) {
            html += '<div class="contable-promo-dates-bar">';
            html += '<i class="ri-calendar-event-line"></i> ' + plan.fecha_inicio + ' al ' + plan.fecha_fin;
            html += '</div>';
        }
        
        html += '<div class="contable-conceptos-list">';
        html += '<table class="contable-conceptos-table"><thead><tr>';
        html += '<th>Concepto</th><th>Cuotas</th><th class="text-end">P. Regular</th>';
        if (plan.es_promocion) html += '<th class="text-end">Descuento</th>';
        html += '<th class="text-end">Pago</th></tr></thead><tbody>';
        
        plan.conceptos.forEach(function(c) {
            html += '<tr>';
            html += '<td>' + (c.concepto?.nombre || '—') + '</td>';
            html += '<td><span class="contable-cuotas-badge">' + c.cuotas + '</span></td>';
            html += '<td class="text-end">Bs. ' + parseFloat(c.precio_regular_bs || 0).toFixed(2) + '</td>';
            if (plan.es_promocion) {
                html += '<td class="text-end">Bs. ' + parseFloat(c.descuento_bs || 0).toFixed(2) + '</td>';
            }
            html += '<td class="text-end"><strong>Bs. ' + parseFloat(c.pago_bs || 0).toFixed(2) + '</strong></td>';
            html += '</tr>';
        });
        
        html += '</tbody></table></div></div>';
    });
    
    $container.html(html);
}
```

- [ ] **Step 5: Agregar estilos de cards de planes**

Agregar en CSS (después de las clases de modal):

```css
.contable-plan-card {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-radius: 14px;
    overflow: hidden;
}
.contable-plan-card.contable-plan-promo {
    border-color: rgba(252,123,4,0.25);
    background: linear-gradient(135deg, #fff 0%, rgba(252,123,4,0.03) 100%);
}
.contable-plan-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.contable-plan-nombre {
    font-weight: 800;
    font-size: 1.05rem;
    color: var(--gray-900);
}
.contable-plan-total {
    font-size: 1.1rem;
    font-weight: 800;
    color: #fc7b04;
}
.contable-promo-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.7rem;
    color: #fc7b04;
    font-weight: 700;
    padding: 0.2rem 0.6rem;
    border-radius: 6px;
    background: rgba(252,123,4,0.12);
}
.contable-promo-dates-bar {
    padding: 0.5rem 1.25rem;
    background: rgba(252,123,4,0.06);
    border-bottom: 1px solid rgba(252,123,4,0.1);
    font-size: 0.78rem;
    color: #fc7b04;
    font-weight: 600;
}
.contable-conceptos-table {
    width: 100%;
    border-collapse: collapse;
}
.contable-conceptos-table thead th {
    font-size: 0.7rem;
    text-transform: uppercase;
    color: var(--gray-500);
    font-weight: 700;
    padding: 0.5rem 1rem;
    border-bottom: 1px solid var(--gray-200);
}
.contable-conceptos-table tbody td {
    padding: 0.6rem 1rem;
    font-size: 0.85rem;
    border-bottom: 1px solid var(--gray-100);
}
.contable-cuotas-badge {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    border-radius: 6px;
    background: rgba(99,102,241,0.12);
    color: #6366f1;
    font-weight: 700;
    font-size: 0.75rem;
}
```

---

### Task 7: Verificar y probar

**Files:**
- Test: Visitar `/admin/ofertas-academicas/listar`

**Steps:**

- [ ] **Step 1: Verificar filtros en una fila**

Navegar a la página de listado y verificar que todos los filtros (Convenio, Área, Tipo, Fase, Gestión, Buscar, botones) estén en la misma línea sin scroll horizontal.

- [ ] **Step 2: Verificar que no aparecen "Mostrar" y "Buscar" de DataTable**

El dropdown de "Mostrar X registros" y el input de búsqueda interno de DataTable deben estar ocultos. El buscador personalizado debe funcionar.

- [ ] **Step 3: Verificar colores en columna fechas**

Las fechas deben mostrar badges con colores según el estado: verde (próxima), naranja (en proceso), rojo (en curso).

- [ ] **Step 4: Verificar diseño de fase mejorada**

Los badges de fase deben tener gradiente, borde sutil e iconos según el tipo.

- [ ] **Step 5: Probar modal de planes de pago**

Hacer clic en el botón de acciones (icono de tarjeta) y verificar que se abra el modal con los planes de pago cargados desde AJAX.