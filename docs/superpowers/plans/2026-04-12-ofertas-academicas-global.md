# Implementación: Vista de Ofertas Académicas Globales

> **For agentic workers:** Use implementing-plans skill to execute task-by-task

**Goal:** Crear una vista admin que liste todas las ofertas académicas de todos los posgrados con filtros (convenio, área, tipo, fase, gestión) y buscador en tiempo real.

**Architecture:** Nueva ruta independiente, nuevo método en controller existente, nueva vista Blade con DataTable y filtros en misma página.

**Tech Stack:** Laravel Blade, DataTables, jQuery, PHP

---

## Task 1: Agregar rutas en web.php

**Files:**
- Modify: `routes/web.php:200` (agregar después de rutas de posgrads)

- [ ] **Step 1: Agregar rutas**

Buscar en web.php donde termina el grupo de posgrads (línea ~126) y agregar después:

```php
Route::get('/admin/ofertas-academicas', [OfertasAcademicaController::class, 'indexGlobal'])->name('admin.ofertas.index');
Route::get('/admin/ofertas-academicas/listar', [OfertasAcademicaController::class, 'listarGlobal'])->name('admin.ofertas.listar');
```

- [ ] **Step 2: Verificar que el import del controller existe**

Al inicio de web.php debe haber: `use App\Http\Controllers\OfertasAcademicaController;`

---

## Task 2: Agregar métodos en OfertasAcademicaController

**Files:**
- Modify: `app/Http/Controllers/OfertasAcademicaController.php:466` (agregar al final, antes del último corchete)

- [ ] **Step 1: Agregar método indexGlobal()**

```php
public function indexGlobal()
{
    $convenios = \App\Models\Convenio::orderBy('nombre')->get();
    $areas = \App\Models\Area::orderBy('nombre')->get();
    $tipos = \App\Models\Tipo::orderBy('nombre')->get();
    $fases = Fase::orderBy('nombre')->get();
    $gestiones = OfertasAcademica::distinct()->pluck('gestion')->sort()->values();

    return view('admin.ofertas-academicas.listar', compact(
        'convenios', 'areas', 'tipos', 'fases', 'gestiones'
    ));
}
```

- [ ] **Step 2: Agregar método listarGlobal()**

```php
public function listarGlobal(Request $request)
{
    try {
        $query = OfertasAcademica::with([
            'posgrado', 'posgrado.convenio', 'posgrado.area', 'posgrado.tipo',
            'sucursal', 'modalidad', 'programa', 'fase'
        ]);

        if ($request->filled('convenio_id')) {
            $query->whereHas('posgrado', function ($q) use ($request) {
                $q->where('convenio_id', $request->convenio_id);
            });
        }

        if ($request->filled('area_id')) {
            $query->whereHas('posgrado', function ($q) use ($request) {
                $q->where('area_id', $request->area_id);
            });
        }

        if ($request->filled('tipo_id')) {
            $query->whereHas('posgrado', function ($q) use ($request) {
                $q->where('tipo_id', $request->tipo_id);
            });
        }

        if ($request->filled('fase_id')) {
            $query->where('fase_id', $request->fase_id);
        }

        if ($request->filled('gestion')) {
            $query->where('gestion', $request->gestion);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('codigo', 'like', "%{$search}%")
                    ->orWhereHas('programa', function ($q2) use ($search) {
                        $q2->where('nombre', 'like', "%{$search}%");
                    })
                    ->orWhereHas('posgrado', function ($q2) use ($search) {
                        $q2->where('nombre', 'like', "%{$search}%");
                    });
            });
        }

        $ofertas = $query->orderBy('gestion', 'desc')
            ->orderBy('codigo', 'desc')
            ->get();

        return response()->json(['data' => $ofertas]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
```

- [ ] **Step 3: Agregar use para Area y Tipo si no existen**

Verificar que el controller tenga:
```php
use App\Models\Area;
use App\Models\Tipo;
use App\Models\Convenio;
```

Si no están, agregarlos junto a los otros imports.

---

## Task 3: Agregar entrada en Sidebar

**Files:**
- Modify: `resources/views/layouts/sidebar.blade.php:1210`

- [ ] **Step 1: Agregar menú "Todas las Ofertas"**

Después de la línea 1209 (Cronograma), agregar:

```php
<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route('admin.ofertas.index') }}">
        <i class="ri-book-open-line"></i> <span>Todas las Ofertas</span>
    </a>
</li>
```

---

## Task 4: Crear vista listar.blade.php

**Files:**
- Create: `resources/views/admin/ofertas-academicas/listar.blade.php`

- [ ] **Step 1: Copiar estructura base de existing view**

La nueva vista debe tener:
- Header con título y botón "Nueva Oferta"
- Barra de filtros con selects para: Convenio, Área, Tipo, Fase, Gestión, y un input de búsqueda
- Tabla DataTable con las columnas especificadas

Estructura参考 admin.posgrados.index.blade.php líneas ~1055-1080 para el layout de filtros y tabla.

Columnas de la tabla:
1. Código
2. Posgrado (nombre)
3. Programa
4. Fase (badge con color)
5. Gestión
6. Sucursal
7. Modalidad
8. Inicio Insc.
9. Inicio Prog.
10. Acciones (ver, editar, eliminar)

- [ ] **Step 2: Agregar CSS para filtros**

```css
.ofertas-global-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 20px;
}
.ofertas-global-filters .filter-group {
    min-width: 150px;
}
.ofertas-global-filters .filter-group label {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 4px;
    display: block;
}
```

- [ ] **Step 3: Inicializar DataTable con filtros**

```javascript
let tablaOfertasGlobal = $('#tabla-ofertas-global').DataTable({
    processing: true,
    serverSide: false,
    ajax: {
        url: '/admin/ofertas-academicas/listar',
        data: function(d) {
            d.convenio_id = $('#filterConvenio').val();
            d.area_id = $('#filterArea').val();
            d.tipo_id = $('#filterTipo').val();
            d.fase_id = $('#filterFase').val();
            d.gestion = $('#filterGestion').val();
            d.search = $('#searchOfertas').val();
        }
    },
    columns: [
        { data: 'codigo' },
        { data: 'posgrado.nombre' },
        { data: 'programa.nombre' },
        { data: 'fase.nombre' },
        { data: 'gestion' },
        { data: 'sucursal.nombre' },
        { data: 'modalidad.nombre' },
        { data: 'fecha_inicio_inscripciones' },
        { data: 'fecha_inicio_programa' },
        { data: 'id', render: function(id) { return '...'; } }
    ],
    language: { emptyTable: 'No hay ofertas registradas' }
});
```

- [ ] **Step 4: Agregar debounce para búsqueda**

```javascript
let searchTimeout;
$('#searchOfertas').on('keyup', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        tablaOfertasGlobal.draw();
    }, 300);
});

$('#btnAplicarFiltros').on('click', function() {
    tablaOfertasGlobal.draw();
});
```

- [ ] **Step 5: Botones de acciones**

Similar a admin.posgrados.index.blade.php línea ~2279:
```javascript
'<a href="/admin/posgrads/ofertas/' + d.id + '/detalle" class="btn btn-action" title="Ver detalle"><i class="ri-eye-line"></i></a>'
```

---

## Task 5: Testing

**Files:**
- Test: Navegar a `/admin/ofertas-academicas` en el navegador

- [ ] **Step 1: Verificar que la página carga sin errores**

- [ ] **Step 2: Verificar que los filtros aparecen**

- [ ] **Step 3: Probar que la tabla muestra datos**

- [ ] **Step 4: Probar los filtros**
   - Seleccionar un filtro y clickar "Aplicar"
   - Verificar que los resultados cambian

- [ ] **Step 5: Probar el buscador**
   - Escribir en el input de búsqueda
   - Verificar resultados en tiempo real (con debounce)

---

## Task 6: Commit

```bash
git add .
git commit -m "feat: add global ofertas academicas view with filters"
```