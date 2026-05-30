# Diseño: Cronograma General — Calendario de Módulos y Horarios

**Fecha:** 2026-04-05
**Autor:** Asistente IA

## Problema

No existe una vista centralizada para ver todos los horarios de todos los módulos filtrados por Sede → Sucursal → Oferta Académica en un calendario FullCalendar.

## Arquitectura

### Controller: CronogramaController (nuevo)

- `index()` — renderiza la vista, pasa `$sedes` para el primer filtro
- `listarHorarios(Request $request)` — endpoint AJAX que devuelve modulos + horarios filtrados por sede/sucursal/oferta

### View: admin/cronogramas/index.blade.php (nueva)

- 3 selects en cascada: Sede → Sucursal → Oferta
- FullCalendar con eventos por modulo (rango de fechas)
- Modal de detalle al hacer clic en un evento

### Routes (agregar al grupo existente admin/posgrados)

- `GET /admin/posgrados/cronograma` → `CronogramaController@index`
- `GET /admin/posgrados/cronograma/horarios` → `CronogramaController@listarHorarios`
- `GET /admin/posgrados/cronograma/sucursales/{sedeId}` → `CronogramaController@listarSucursales`
- `GET /admin/posgrados/cronograma/ofertas/{sucursalId}` → `CronogramaController@listarOfertas`

## Flujo de filtros en cascada

1. Page loads → Sede populated, Sucursal/Oferta disabled
2. Select Sede → Sucursal enabled, populated via AJAX
3. Select Sucursal → Oferta enabled, populated via AJAX
4. Select Oferta → calendar fetches events via AJAX
5. Any filter change → calendar refetches

## Calendar Events

Cada evento = un modulo (no un horario individual):
- **Title:** `modulo.nombre`
- **Start:** `modulo.fecha_inicio`
- **End:** `modulo.fecha_fin + 1 day` (FullCalendar end dates are exclusive)
- **Color:** `modulo.color`
- **extendedProps:** modulo_id, oferta_id, oferta_codigo, docente_nombre, horarios_count, estado

## Modal de Detalle (al hacer clic en evento)

- Header: barra de color + nombre del modulo + badge de estado
- Info: docente, fechas, oferta, total sesiones
- Tabla: cada horario como fila (fecha, hora inicio→fin, estado, trabajador)

## Endpoint Response (listarHorarios)

```json
{
  "data": [
    {
      "id": 1,
      "nombre": "MODULO 1",
      "color": "#6366f1",
      "fecha_inicio": "2026-04-06",
      "fecha_fin": "2026-04-30",
      "estado": "En Desarrollo",
      "docente": { "persona": { "nombres": "...", "apellido_paterno": "...", "apellido_materno": "..." } },
      "oferta": { "codigo": "POS-2026-001" },
      "horarios_count": 4,
      "horarios": [
        {
          "id": 2,
          "fecha": "2026-04-06",
          "hora_inicio": "19:00",
          "hora_fin": "22:00",
          "estado": "Confirmado",
          "trabajador_cargo": { "nombre_cargo": "Docente" }
        }
      ]
    }
  ]
}
```

## Archivos a crear/modificar

| Archivo | Acción |
|---------|--------|
| `app/Http/Controllers/CronogramaController.php` | Crear |
| `resources/views/admin/cronogramas/index.blade.php` | Crear |
| `routes/web.php` | Modificar (agregar 3 rutas al grupo posgrados) |

## Pruebas

1. Navegar a `/admin/posgrados/cronograma` — debe cargar con filtros
2. Seleccionar Sede → Sucursal se habilita
3. Seleccionar Sucursal → Oferta se habilita
4. Seleccionar Oferta → calendario muestra modulos como eventos
5. Clic en evento → modal con detalles del modulo y tabla de horarios
