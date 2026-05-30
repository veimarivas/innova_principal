<?php
/**
 * Script de diagnóstico temporal — ELIMINAR después de depurar
 * Uso: http://localhost/innova-ciencia-virtual/debug_moodle_images.php?course_id=2
 */
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$courseId = (int)($_GET['course_id'] ?? 2);

/** @var \App\Services\MoodleService $moodle */
$moodle = app(\App\Services\MoodleService::class);

// --- 1. Obtener contenidos RAW (antes de cualquier procesamiento) ---
$rawContents = app()->call(function(\Illuminate\Support\Facades\Http $http) use ($moodle, $courseId) {
    // Llamar directamente al método privado vía reflexión
    $ref = new ReflectionClass($moodle);
    $callMethod = $ref->getMethod('call');
    $callMethod->setAccessible(true);
    return $callMethod->invoke($moodle, 'core_course_get_contents', ['courseid' => $courseId]) ?? [];
});

echo "<h2>Diagnóstico de imágenes Moodle — Curso ID: $courseId</h2>";
echo "<style>body{font-family:monospace;font-size:13px;} .ok{color:green} .warn{color:orange} .err{color:red} pre{background:#f5f5f5;padding:10px;overflow:auto;max-height:300px;}</style>";

$totalSecs = count($rawContents);
echo "<p>Secciones encontradas: <strong>$totalSecs</strong></p>";

foreach ($rawContents as $i => $section) {
    echo "<hr><h3>Sección {$i}: " . htmlspecialchars($section['name'] ?? '') . "</h3>";
    
    $summary = $section['summary'] ?? '';
    if ($summary) {
        echo "<h4>Summary de sección:</h4>";
        echo "<pre>" . htmlspecialchars(substr($summary, 0, 2000)) . "</pre>";
        
        // Buscar imágenes en el summary
        preg_match_all('/src=["\']([^"\']+)["\']|@@PLUGINFILE@@([^\s"\'<]+)/i', $summary, $m);
        $urls = array_filter(array_merge($m[1] ?? [], $m[2] ?? []));
        if ($urls) {
            echo "<p class='warn'>⚠ URLs/placeholders encontrados en summary:</p><ul>";
            foreach ($urls as $url) {
                echo "<li>" . htmlspecialchars($url) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='warn'>⚠ No se encontraron URLs de imagen en el summary</p>";
        }
    } else {
        echo "<p>Summary vacío</p>";
    }
    
    foreach ($section['modules'] ?? [] as $j => $mod) {
        $modname = $mod['modname'] ?? 'unknown';
        $intro = $mod['intro'] ?? '';
        $desc = $mod['description'] ?? '';
        
        if (!$intro && !$desc) continue;
        
        echo "<h4>Módulo {$j}: [{$modname}] " . htmlspecialchars($mod['name'] ?? '') . "</h4>";
        
        $content = $intro ?: $desc;
        if (stripos($content, '<img') !== false || stripos($content, 'pluginfile') !== false || stripos($content, '@@PLUGINFILE') !== false) {
            echo "<p class='warn'>⚠ Contiene imágenes/pluginfile:</p>";
            echo "<pre>" . htmlspecialchars(substr($content, 0, 2000)) . "</pre>";
            
            // Buscar el src exacto
            preg_match_all('/src=["\']([^"\']+)["\']|@@PLUGINFILE@@([^\s"\'<]+)/i', $content, $m2);
            $urls2 = array_filter(array_merge($m2[1] ?? [], $m2[2] ?? []));
            if ($urls2) {
                echo "<ul>";
                foreach ($urls2 as $url) {
                    echo "<li class='warn'>" . htmlspecialchars($url) . "</li>";
                }
                echo "</ul>";
            }
        }
    }
}

// --- 2. Probar la URL normalizada ---
echo "<hr><h2>Prueba de normalización</h2>";

// Obtener los contenidos procesados
$processed = $moodle->getCourseContentsWithDetails($courseId);
foreach ($processed as $i => $section) {
    $desc = $section['description'] ?? '';
    if (!$desc) continue;
    
    echo "<h3>Sección {$i} — description procesada:</h3>";
    if (stripos($desc, 'data:image') !== false) {
        echo "<p class='ok'>✅ Imágenes convertidas a base64</p>";
        // Renderizar la imagen
        echo $desc;
    } elseif (stripos($desc, '<img') !== false) {
        echo "<p class='err'>❌ Hay img pero SIN base64:</p>";
        echo "<pre>" . htmlspecialchars(substr($desc, 0, 2000)) . "</pre>";
        // Intentar renderizar de todas formas
        echo "<div style='border:1px solid red;padding:10px;'>" . $desc . "</div>";
    } else {
        echo "<p>Sin imágenes en la descripción procesada</p>";
        echo "<pre>" . htmlspecialchars(substr($desc, 0, 500)) . "</pre>";
    }
    
    foreach ($section['modules'] ?? [] as $j => $mod) {
        $modDesc = $mod['description'] ?? '';
        if (!$modDesc) continue;
        if (stripos($modDesc, '<img') === false && stripos($modDesc, 'pluginfile') === false) continue;
        
        echo "<h4>Módulo [{$mod['modname']}] " . htmlspecialchars($mod['name'] ?? '') . "</h4>";
        if (stripos($modDesc, 'data:image') !== false) {
            echo "<p class='ok'>✅ Imagen en base64</p>";
            echo $modDesc;
        } else {
            echo "<p class='err'>❌ Sin base64:</p>";
            echo "<pre>" . htmlspecialchars(substr($modDesc, 0, 2000)) . "</pre>";
        }
    }
}
