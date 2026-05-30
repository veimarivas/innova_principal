# Diseño: Configuración Múltiple de Planes y Conceptos en Área Contable

**Fecha:** 2026-04-05
**Autor:** Asistente IA

## Resumen

Mejorar el flujo de registro de configuraciones de precio en el tab "Área Contable" de `admin.ofertas-academicas.detalle`. El usuario selecciona un plan de pagos no registrado, agrega múltiples conceptos en filas dinámicas dentro de un modal, y guarda todo en un solo paso. Se implementa autocompletado de `precio_regular` desde el plan principal y se valida que exista un plan principal configurado antes de permitir registrar.

## Reglas de Negocio

1. **Plan ya registrado = no disponible**: Un plan de pagos que ya tiene al menos un concepto registrado en la oferta NO aparece en el selector de planes.
2. **Sin plan principal = no se puede registrar**: Si no existe ningún `planes_conceptos` vinculado a un `planes_pagos.principal = 1` para esta oferta, se bloquea el guardado. El precio base del plan principal es necesario para controlar descuentos.
3. **Combinación única**: No se puede duplicar la combinación `planes_pago_id + concepto_id` en la misma oferta.
4. **Cálculo automático**: `pago_bs = max(0, precio_regular - descuento_bs)`.
5. **Autocompletado de precio**: Al seleccionar un concepto en una fila, se busca en `planes_conceptos` donde `planes_pago_id` corresponde al plan con `principal = 1` y el mismo `concepto_id`, y se toma ese `pago_bs` como `precio_regular` de la nueva fila.
6. **Campos siempre editables**: Independientemente de si el plan es promoción o no, todos los campos (`n_cuotas`, `precio_regular`, `descuento_bs`) son editables. Si `es_promocion = 1`, se muestra un badge visual diferenciador.

## Arquitectura

### Componentes modificados

| Archivo | Cambio |
|---------|--------|
| `resources/views/admin/ofertas-academicas/detalle.blade.php` | Reemplazar modal `modalCrearPc` por modal dinámico con tabla de filas |
| `app/Http/Controllers/OfertasAcademicaController.php` | Agregar método `guardarPlanesConceptoMultiple` y endpoint de verificación |
| `routes/web.php` | Agregar rutas nuevas |

### Componentes nuevos

- **Modal `modalCrearPcMultiple`**: Modal `modal-lg` con selector de plan + tabla dinámica de conceptos
- **Tabla dinámica de filas**: Filas agregables/eliminables con selects de concepto y campos numéricos
- **Banner de advertencia**: Se muestra si no hay plan principal configurado

## UI — Modal Dinámico

### Estructura visual

```
┌─────────────────────────────────────────────────────────┐
│  [✕] Nueva Configuración de Precio                      │
├─────────────────────────────────────────────────────────┤
│  Plan de Pago *                                         │
│  [— Seleccionar — ▼]                                    │
│                                                         │
│  ┌──────────────────────────────────────────────────┐   │
│  │ Concepto *      │ Cuotas *│ P.Regular │ Descuento│   │
│  ├──────────────────────────────────────────────────┤   │
│  │ [Matrícula ▼]   │ [  1]   │ [  500.00] │ [  0.00]│✕  │
│  │ [Mensualidad ▼] │ [  6]   │ [  300.00] │ [ 50.00]│✕  │
│  │ [Cuota Extra ▼] │ [  2]   │ [  200.00] │ [  0.00]│✕  │
│  └──────────────────────────────────────────────────┘   │
│                                                         │
│  [+ Agregar Concepto]                                   │
│                                                         │
│  ┌──────────────────────────────────────────────────┐   │
│  │  Total a Pagar: Bs. 1,150.00                     │   │
│  └──────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────┤
│  [Cancelar]                          [Guardar Todo]     │
└─────────────────────────────────────────────────────────┘
```

### Comportamiento de filas

- **Agregar fila**: botón `+ Agregar Concepto` inserta fila vacía al final de la tabla
- **Eliminar fila**: botón `✕` en cada fila. Siempre visible excepto si es la única fila
- **Auto-completado**: al seleccionar concepto, se dispara búsqueda del `pago_bs` del plan principal y se llena `precio_regular`
- **Cálculo en vivo**: al cambiar `precio_regular` o `descuento_bs`, se recalcula `pago_bs` de la fila y el total general
- **Plan promoción**: si `es_promocion = 1`, se muestra badge "Promoción" junto al nombre del plan seleccionado

### Validación visual

- Fila sin concepto seleccionado → borde rojo en el select
- Sin plan principal registrado → banner naranja en el top del modal: *"No se puede registrar: no existe un plan principal configurado con precio base."*
- Botón "Guardar Todo" deshabilitado si: no hay plan seleccionado, no hay filas, o alguna fila está incompleta

## Backend

### Nuevo método en `OfertasAcademicaController`

```php
public function guardarPlanesConceptoMultiple(Request $request, $ofertaId)
```

**Endpoint:** `POST /admin/posgrados/ofertas/{ofertaId}/planes-conceptos/multiple`

### Payload esperado

```json
{
  "planes_pago_id": 2,
  "conceptos": [
    { "concepto_id": 1, "n_cuotas": 1, "precio_regular": 500.00, "descuento_bs": 0.00 },
    { "concepto_id": 2, "n_cuotas": 6, "precio_regular": 300.00, "descuento_bs": 50.00 },
    { "concepto_id": 3, "n_cuotas": 2, "precio_regular": 200.00, "descuento_bs": 0.00 }
  ]
}
```

### Validaciones del backend

1. `planes_pago_id` existe y NO está ya asignado a esta oferta
2. `conceptos` es un array con al menos 1 elemento
3. Cada concepto tiene: `concepto_id` (required), `n_cuotas` (required, integer >= 1), `precio_regular` (required, numeric >= 0)
4. `descuento_bs` es opcional, numeric >= 0, no puede ser mayor que `precio_regular`
5. No hay `concepto_id` duplicados en el mismo payload
6. Existe al menos un `planes_conceptos` con `planes_pagos.principal = 1` para esta oferta
7. Ningún `concepto_id` del payload ya está registrado en esta oferta

### Respuesta exitosa

```json
{
  "success": true,
  "message": "Configuración guardada: 3 conceptos registrados para Plan Mensual.",
  "data": {
    "planes_pago_id": 2,
    "conceptos_registrados": 3
  }
}
```

### Tabla de errores

| Escenario | HTTP | Mensaje |
|-----------|------|---------|
| Plan ya registrado | 400 | "Este plan ya tiene configuraciones en esta oferta" |
| Sin plan principal | 400 | "Debe existir un plan principal configurado antes de registrar esta configuración" |
| Concepto duplicado en payload | 422 | "No puede repetir el mismo concepto en la misma configuración" |
| Concepto ya registrado en oferta | 422 | "El concepto X ya está configurado en esta oferta" |
| Campos inválidos | 422 | Errores estándar de validación Laravel |
| Error de transacción | 500 | "Error al guardar la configuración" |

## Nuevos Endpoints

| Método | Ruta | Controlador | Propósito |
|--------|------|-------------|-----------|
| POST | `/ofertas/{ofertaId}/planes-conceptos/multiple` | `OfertasAcademicaController@guardarPlanesConceptoMultiple` | Guardar plan + múltiples conceptos en transacción |
| GET | `/ofertas/{ofertaId}/planes-conceptos/verificar-principal` | `OfertasAcademicaController@verificarPlanPrincipal` | Verificar si existe plan principal con conceptos para la oferta |
| GET | `/ofertas/{ofertaId}/planes-conceptos/precio-base/{conceptoId}` | `OfertasAcademicaController@obtenerPrecioBase` | Obtener `pago_bs` del concepto en el plan principal |

## Frontend — JavaScript

### Funciones nuevas (inline en `detalle.blade.php`)

| Función | Propósito |
|---------|-----------|
| `initNuevoPlanConcepto()` | Inicializa modal, carga selects de planes y conceptos, verifica plan principal |
| `agregarFilaConcepto()` | Agrega fila dinámica a la tabla |
| `eliminarFilaConcepto(idx)` | Elimina fila específica |
| `autoCompletarPrecio(filaIdx)` | Busca precio base del concepto y llena `precio_regular` |
| `calcularPagoFila(filaIdx)` | Recalcula `pago_bs` de una fila: `max(0, precio_regular - descuento_bs)` |
| `calcularTotalGeneral()` | Suma todos los `pago_bs` de las filas |
| `guardarTodo()` | Valida formulario y envía POST al backend |
| `renderFilasDesdePayload(data)` | (Opcional) Renderiza filas desde datos existentes |

### Flujo de datos

```
1. Click "Nueva Configuración"
   → GET /planes-pago/disponibles/{ofertaId}
   → GET /conceptos/disponibles/{ofertaId}
   → GET /planes-conceptos/verificar-principal/{ofertaId}
   → Renderiza modal

2. Usuario selecciona Plan
   → Si es_promocion = 1: muestra badge "Promoción"

3. Usuario agrega fila
   → Renderiza row con select de concepto + campos numéricos

4. Usuario selecciona concepto en fila
   → GET /planes-conceptos/precio-base/{conceptoId}
   → Auto-completa precio_regular con pago_bs del plan principal
   → Si no encuentra: muestra warning en la fila

5. Usuario cambia precio_regular o descuento_bs
   → Recalcula pago_bs de la fila
   → Recalcula total general

6. Click "Guardar Todo"
   → Valida frontend
   → POST /planes-conceptos/multiple/{ofertaId}
   → Success: cierra modal, recarga DataTable
   → Error: muestra toast con mensaje
```

## Transacción de base de datos

El método `guardarPlanesConceptoMultiple` usa `DB::transaction()` para garantizar atomicidad:

```php
DB::transaction(function () use ($ofertaId, $planId, $conceptos) {
    foreach ($conceptos as $c) {
        PlanesConcepto::create([
            'ofertas_academica_id' => $ofertaId,
            'planes_pago_id' => $planId,
            'concepto_id' => $c['concepto_id'],
            'n_cuotas' => $c['n_cuotas'],
            'precio_regular' => $c['precio_regular'],
            'descuento_bs' => $c['descuento_bs'] ?? 0,
            'pago_bs' => max(0, $c['precio_regular'] - ($c['descuento_bs'] ?? 0)),
        ]);
    }
});
```

## Responsividad

- Modal `modal-lg` en desktop, `modal-dialog-scrollable` en mobile
- Tabla de filas con scroll horizontal en pantallas pequeñas
- Campos numéricos con `input-sm` en mobile
- Botón "Guardar Todo" full-width en mobile

## Pruebas

### Casos de prueba

1. Registrar plan con 1 concepto → éxito
2. Registrar plan con 3 conceptos → éxito, 3 registros en BD
3. Intentar registrar plan ya existente → error 400
4. Intentar registrar sin plan principal configurado → error 400
5. Intentar registrar con concepto duplicado en payload → error 422
6. Intentar registrar con concepto ya existente en oferta → error 422
7. Auto-completado de precio desde plan principal → correcto
8. Auto-completado sin plan principal → warning, bloqueo de guardado
9. Cálculo de pago_bs con descuento → correcto
10. Cálculo de pago_bs sin descuento → igual a precio_regular
11. Descuento mayor que precio_regular → pago_bs = 0
12. Eliminar última fila → no permitido (siempre debe haber al menos 1)
