# Diseño: Mejora de Vista Detalle de Oferta Académica

**Fecha:** 2026-04-05
**Autor:** Asistente IA

## Resumen

Rediseñar la vista `/ofertas/{id}/detalle` para mostrar toda la información de la oferta académica de forma organizada, incluir la sección de módulos con gestión de horarios, y preparar un placeholder para el área contable.

## Arquitectura

### Estructura de la vista

La vista se organiza en **tabs horizontales** con 3 secciones:

1. **Información General** — datos completos de la oferta (mejorado)
2. **Módulos y Horarios** — tabla de módulos con gestión de horarios (nuevo)
3. **Área Contable** — placeholder para desarrollo futuro

### Navegación

Tabs horizontales en la parte superior del contenido, debajo del header de página. Cada tab muestra su sección correspondiente sin recargar la página.

## Sección 1: Información General

### Layout

Mantener la estructura actual pero mejorar visualmente:

- **Header**: código de oferta, badges (fase, modalidad, gestión, versión), botón editar
- **Grid de datos**: 3 columnas en desktop, 1 en mobile
- **Fechas como timeline**: línea visual conectando las 3 fechas clave
- **Responsables**: cards con avatar, nombre y rol
- **Documentos**: cards con preview visual

### Datos mostrados

- Código, Programa, Posgrado, Fase, Gestión, Versión/Grupo
- Modalidad, Sucursal, Color
- Fechas: Inscripciones, Inicio programa, Fin programa
- Configuración: N° módulos, N° sesiones, Nota mínima
- Responsables: Académico y Marketing
- Documentos: Portada y Certificado

## Sección 2: Módulos y Horarios

### Tabla de módulos

**Columnas:**
- N° — badge circular con el color del módulo
- Nombre — texto del módulo
- Docente — nombre completo + carnet (o "Sin asignar")
- Fechas — formato "dd/mm → dd/mm"
- Estado — badge: No Inicio (warning), En Desarrollo (success), Concluido (info)
- Horarios — badge con count (ej: "3 horarios")
- Acciones — botón "Ver horarios" (icono calendario)

**Datos:** Se cargan vía AJAX desde el endpoint existente `/ofertas/{ofertaId}/modulos/listar`, enriquecido con `horarios` y `docente.persona`.

### Modal de horarios

Al hacer click en "Ver horarios":

**Header:**
- Nombre del módulo con su color como acento
- Badge de estado
- Botón "Agregar horario"

**Tabla de horarios existentes:**
- Fecha (dd/mm/yyyy)
- Hora inicio → Hora fin
- Docente (nombre del docente del módulo)
- Estado (badge: Programado, En curso, Finalizado, Reprogramado)
- Acciones: Editar, Eliminar

**Formulario de agregar/editar horario:**
- Campo Fecha (date picker)
- Campo Hora inicio (time picker)
- Campo Hora fin (time picker)
- Botones: Guardar, Cancelar

**Flujo:**
1. Click "Ver horarios" → abre modal
2. Click "Agregar horario" → muestra formulario inline o sub-modal
3. Guardar → AJAX POST → recarga tabla de horarios
4. Editar → carga datos en formulario → PUT → recarga
5. Eliminar → confirmación → DELETE → recarga

### Endpoints necesarios

**Existentes:**
- `GET /ofertas/{ofertaId}/modulos/listar` — listar módulos de una oferta

**Nuevos requeridos:**
- `GET /ofertas/{ofertaId}/modulos/{moduloId}/horarios` — listar horarios de un módulo
- `POST /ofertas/{ofertaId}/modulos/{moduloId}/horarios` — crear horario
- `PUT /horarios/{id}` — actualizar horario
- `DELETE /horarios/{id}` — eliminar horario

## Sección 3: Área Contable

Placeholder visual con:
- Icono de construcción
- Título: "Área Contable"
- Subtítulo: "Próximamente"
- Descripción: "Esta sección estará disponible para gestionar costos, pagos y facturación de la oferta académica."

## Controller: OfertasAcademicaController

### Método `detalle` — actualización

Agregar eager loading para módulos y horarios:

```php
$oferta = OfertasAcademica::with([
    'posgrado', 'sucursal', 'modalidad', 'programa', 'fase',
    'trabajador_cargo_academico.trabajador.persona',
    'trabajador_cargo_marketing.trabajador.persona',
    'modulos.docente.persona',
    'modulos.horarios.trabajadorCargo.trabajador.persona',
])->findOrFail($id);
```

## Model: Modulo

Ya tiene relación `horarios()` definida. Solo necesita eager loading.

## Model: Horario

Ya tiene relaciones `modulo()` y `trabajador_cargo()` definidas.

## CSS / Estilos

Nuevos estilos necesarios:
- Tabs horizontales (`.oferta-tabs`, `.oferta-tab`, `.oferta-tab.active`)
- Timeline de fechas (`.fecha-timeline`, `.fecha-node`, `.fecha-line`)
- Tabla de módulos (`.modulos-table`, `.modulo-row`)
- Badge de color de módulo (`.modulo-color-badge`)
- Tabla de horarios (`.horarios-table`)
- Placeholder contable (`.contable-placeholder`)

## Responsividad

- Tabs: scroll horizontal en mobile
- Grid de información: 1 columna en mobile
- Tablas: scroll horizontal en mobile
- Modales: full-width en mobile

## Flujo de usuario

1. Usuario navega a detalle de oferta desde la lista
2. Ve la pestaña "Información General" por defecto
3. Puede cambiar a "Módulos y Horarios" para ver los módulos
4. En módulos, puede hacer click en "Ver horarios" para gestionar horarios de un módulo específico
5. Puede agregar, editar y eliminar horarios
6. La pestaña "Área Contable" muestra un placeholder indicando que estará disponible
