# Fix: Editar Secciones en Moodle vía API REST — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Reemplazar el PDO directo en `MoodleService::editSection()` por la API REST `core_course_update_courses` para que los cambios de nombre/descripción de secciones persistan correctamente en Moodle.

**Architecture:** Cambio localizado a un único método en `app/Services/MoodleService.php`. Se reemplaza la conexión PDO con credenciales hardcodeadas por una llamada a `$this->call('core_course_update_courses', ...)`, mismo patrón que `reorderActivities()` y `reorderSections()` ya usan en el mismo archivo. No hay cambios en frontend, rutas ni controladores.

**Tech Stack:** PHP 8.x, Laravel, Moodle REST API

---

### Task 1: Modificar `MoodleService::editSection()`

**Files:**
- Modify: `app/Services/MoodleService.php:1108-1119`

- [ ] **Step 1: Aplicar el cambio**

Reemplazar el cuerpo del método `editSection()`:

```php
// ANTES (líneas 1108-1119)
public function editSection(int $courseId, int $sectionId, string $name, string $summary = ''): bool
{
    try {
        $pdo = new \PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
        $stmt = $pdo->prepare('UPDATE moodle5.mdl_course_sections SET name = ?, summary = ?, summaryformat = 1 WHERE id = ? AND course = ?');
        $stmt->execute([$name, $summary, $sectionId, $courseId]);
        return $stmt->rowCount() > 0;
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('editSection DB error: ' . $e->getMessage());
        return false;
    }
}

// DESPUÉS
public function editSection(int $courseId, int $sectionId, string $name, string $summary = ''): bool
{
    $response = $this->call('core_course_update_courses', [
        'courses[0][id]'                        => $courseId,
        'courses[0][sections][0][id]'           => $sectionId,
        'courses[0][sections][0][name]'         => $name,
        'courses[0][sections][0][summary]'      => $summary,
        'courses[0][sections][0][summaryformat]' => 1,
    ]);
    return $response !== null && empty($response);
}
```

- [ ] **Step 2: Verificar sintaxis PHP**

Run: `php -l app/Services/MoodleService.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Verificar que el cambio persiste en Moodle**

1. Ir al tab Actividades de cualquier módulo con curso Moodle
2. Hacer clic en el icono de lápiz junto a una sección
3. Cambiar el nombre y presionar Enter
4. Confirmar toast "Sección renombrada correctamente"
5. Recargar la página — el nuevo nombre debe aparecer
6. Abrir el curso en Moodle en otra pestaña — el cambio debe reflejarse

- [ ] **Step 4: Verificar edición de descripción (summary)**

1. Hacer clic en el icono de editar descripción de una sección
2. Modificar contenido y guardar
3. Recargar — la descripción debe persistir
4. Verificar en Moodle que el cambio se vea

- [ ] **Step 5: Commit**

```bash
git add app/Services/MoodleService.php
git commit -m "fix: reemplazar PDO directo por API REST en editSection()

El método editSection() usaba una conexión PDO directa a la base de
datos de Moodle con credenciales hardcodeadas (root/''), lo que
causaba que los cambios de nombre/descripción de secciones no
persistieran. Se reemplaza por core_course_update_courses vía
\$this->call(), consistente con el resto del servicio."
```
