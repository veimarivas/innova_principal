# SSO Moodle - Autenticación Automática para Recursos

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Permitir que el estudiante acceda a recursos y actividades de Moodle sin volver a iniciar sesión, usando autenticación automática via autologin key.

**Architecture:** Interceptar clics en enlaces de Moodle desde el dashboard del estudiante, generar una clave de autologin en el servidor, y redirigir automáticamente al usuario a Moodle autenticado.

**Tech Stack:** Laravel, JavaScript (jQuery), Moodle WebService (tool_mobile_get_autologin_key)

---

## File Structure

- Modify: `app/Http/Controllers/EstudianteDashboardController.php:99-143` - Agregar soporte JSON al endpoint moodleSso
- Modify: `resources/views/estudiante/dashboard.blade.php:679-684` - Agregar botón "Ir al curso"
- Modify: `resources/views/estudiante/dashboard.blade.php:709-847` - Agregar JavaScript de interceptación

---

### Task 1: Modificar endpoint moodleSso para soportar JSON

**Files:**
- Modify: `app/Http/Controllers/EstudianteDashboardController.php:99-143`

- [ ] **Step 1: Leer el archivo actual del controller**

Run: `Read app/Http/Controllers/EstudianteDashboardController.php offset 99 limit 50`

- [ ] **Step 2: Modificar el método moodleSso para devolver JSON**

Reemplazar el método completo `moodleSso` (líneas 99-143) con esta versión que soporta JSON:

```php
public function moodleSso(Request $request)
{
    $target = $request->query('target', '');
    $moodleBase = rtrim(config('moodle.url'), '/');
    $fallback = $moodleBase . '/login/index.php' . ($target ? '?wantsurl=' . urlencode($target) : '');

    if (!$target || !str_starts_with($target, $moodleBase)) {
        return $request->expectsJson()
            ? response()->json(['error' => 'URL inválida', 'redirectUrl' => $fallback])
            : redirect($fallback);
    }

    $user = Auth::user();
    $persona = $user->persona;

    if (!$persona || !$user->username) {
        return $request->expectsJson()
            ? response()->json(['error' => 'Sin perfil', 'redirectUrl' => $fallback])
            : redirect($fallback);
    }

    $digits = preg_replace('/[^0-9]/', '', $persona->carnet ?? '');
    $password = strlen($digits) >= 7 ? $digits : 'innova' . $digits;

    $tokens = $this->moodle->getUserToken($user->username, $password);
    if (!$tokens) {
        return $request->expectsJson()
            ? response()->json(['error' => 'Sin token Moodle', 'redirectUrl' => $fallback])
            : redirect($fallback);
    }

    $key = $this->moodle->getAutoLoginKey($tokens['token'], $tokens['privatetoken']);
    if (!$key) {
        return $request->expectsJson()
            ? response()->json(['error' => 'Sin clave autologin', 'redirectUrl' => $fallback])
            : redirect($fallback);
    }

    $moodleUserId = Inscripcione::whereHas('estudiante', fn($q) => $q->where('persona_id', $persona->id))
        ->whereNotNull('moodle_user_id')
        ->value('moodle_user_id');

    if (!$moodleUserId) {
        return $request->expectsJson()
            ? response()->json(['error' => 'Sin usuario Moodle', 'redirectUrl' => $fallback])
            : redirect($fallback);
    }

    $redirectUrl = $moodleBase . '/admin/tool/mobile/autologin.php'
        . '?userid=' . $moodleUserId
        . '&key=' . urlencode($key)
        . '&urltogo=' . urlencode($target);

    return $request->expectsJson()
        ? response()->json(['redirectUrl' => $redirectUrl])
        : redirect($redirectUrl);
}
```

- [ ] **Step 3: Verificar que el archivo está correcto**

Run: `Read app/Http/Controllers/EstudianteDashboardController.php offset 99 limit 60`

Expected: Verificar que el código modificado está correcto

---

### Task 2: Agregar botón "Ir al curso" en el dashboard

**Files:**
- Modify: `resources/views/estudiante/dashboard.blade.php:679-684`

- [ ] **Step 1: Leer la sección de botones del dashboard**

Run: `Read resources/views/estudiante/dashboard.blade.php offset 675 limit 30`

- [ ] **Step 2: Modificar para agregar botón "Ir al curso"**

Reemplazar las líneas 679-684 (el bloque @if y los botones) con:

```php
                            @if ($tieneMoodle)
                                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                                    <button class="est-btn-actividades btn-ver-actividades" data-modulo="{{ $modulo->id }}"
                                        data-panel="panel-mod-{{ $modulo->id }}">
                                        <i class="ri-eye-line"></i> Ver actividades
                                    </button>
                                    @php
                                        $moodleBase = rtrim(config('moodle.url'), '/');
                                        $courseUrl = $moodleBase . '/course/view.php?id=' . $matricula->moodle_course_id;
                                    @endphp
                                    <a href="{{ $courseUrl }}"
                                        class="est-btn-actividades btn-ir-curso"
                                        data-course-url="{{ $courseUrl }}"
                                        style="background:#5a8a30;"
                                        title="Abrir curso completo en Moodle">
                                        <i class="ri-external-link-line"></i> Ir al curso
                                    </a>
                                </div>
                            @endif
```

- [ ] **Step 3: Verificar el cambio**

Run: `Read resources/views/estudiante/dashboard.blade.php offset 675 limit 35`

Expected: Verificar que ambos botones aparecen correctamente

---

### Task 3: Agregar JavaScript de interceptación de clics

**Files:**
- Modify: `resources/views/estudiante/dashboard.blade.php:709-847`

- [ ] **Step 1: Leer la sección @section('script')**

Run: `Read resources/views/estudiante/dashboard.blade.php offset 705 limit 50`

- [ ] **Step 2: Agregar variables y funciones de SSO al inicio del script**

En la línea 712, después de `const CSRF = '{{ csrf_token() }}';`, agregar:

```javascript
const MoodleBase = '{{ rtrim(config("moodle.url"), "/") }}';
```

En la línea 713, después de `const loaded = {};`, agregar:

```javascript
// Función para redirigir a Moodle con autologin
function redirectToMoodle(targetUrl) {
    fetch('/moodle-sso?target=' + encodeURIComponent(targetUrl))
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.redirectUrl) {
                window.location.href = data.redirectUrl;
            } else if (data.error) {
                alert('Error al conectar con Moodle: ' + data.error);
                window.location.href = targetUrl;
            } else {
                window.location.href = targetUrl;
            }
        })
        .catch(function() {
            window.location.href = targetUrl;
        });
}
```

- [ ] **Step 3: Agregar listener para botón "Ir al curso"**

Al final de la función IIFE (antes del cierre `})();` en línea 845), agregar:

```javascript
// Botón "Ir al curso completo"
$(document).on('click', '.btn-ir-curso', function(e) {
    e.preventDefault();
    var courseUrl = $(this).data('course-url');
    redirectToMoodle(courseUrl);
});
```

- [ ] **Step 4: Modificar el render de actividades para que los enlaces usen la función de redirect**

En la función `renderActividades` (alrededor de línea 798-800), cambiar:

```javascript
const url = mod.url ? '<a href="' + escHtml(mod.url) +
    '" target="_blank" style="font-size:.72rem;color:#fc7b04;margin-left:.5rem;"><i class="ri-external-link-line"></i></a>' :
    '';
```

Por:

```javascript
const url = mod.url ? '<a href="#" class="moodle-link" data-target="' + escHtml(mod.url) +
    '" style="font-size:.72rem;color:#fc7b04;margin-left:.5rem;"><i class="ri-external-link-line"></i></a>' :
    '';
```

- [ ] **Step 5: Agregar listener para enlaces de Moodle en actividades**

Agregar después del listener del botón "Ir al curso":

```javascript
// Interceptar clics en enlaces de Moodle (dentro de paneles de actividades)
$(document).on('click', '.moodle-link', function(e) {
    e.preventDefault();
    var targetUrl = $(this).data('target');
    redirectToMoodle(targetUrl);
});
```

- [ ] **Step 6: Verificar los cambios completos del script**

Run: `Read resources/views/estudiante/dashboard.blade.php offset 705 limit 160`

Expected: Verificar que todo el JavaScript está correcto

---

### Task 4: Verificación y pruebas

**Files:**
- Test: Acceder como estudiante al dashboard
- Test: Hacer clic en "Ir al curso" y verificar que redirige a Moodle autenticado
- Test: Hacer clic en "Ver actividades", luego en un recurso, verificar que redirige a Moodle autenticado

- [ ] **Step 1: Probar el endpoint moodle-sso con curl o Postman**

Run: `php artisan serve` (en terminal separada)

Probar con navegador: acceder como estudiante y hacer clic en los botones

- [ ] **Step 2: Verificar que no hay errores JavaScript**

Abrir consola del navegador (F12) y verificar que no hay errores

- [ ] **Step 3: Probar el flujo completo**

1. Iniciar sesión como estudiante
2. Ir al dashboard
3. Hacer clic en "Ir al curso" - debe redirigir a Moodle sin pedir login
4. Volver al dashboard
5. Hacer clic en "Ver actividades" de un módulo
6. Hacer clic en cualquier recurso - debe redirigir a Moodle sin pedir login