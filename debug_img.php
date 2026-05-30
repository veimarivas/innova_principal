<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$courseId = (int)($argv[1] ?? 97);
$m = app('App\Services\MoodleService');

$ref  = new ReflectionClass($m);
$call = $ref->getMethod('call');
$call->setAccessible(true);

$contents = $call->invoke($m, 'core_course_get_contents', ['courseid' => $courseId]);

if (!is_array($contents)) {
    echo "ERROR: respuesta no es array\n";
    exit(1);
}

echo "=== CONTENIDO RAW DEL CURSO $courseId ===\n";
echo "Secciones: " . count($contents) . "\n\n";

foreach ($contents as $i => $sec) {
    $summary = $sec['summary'] ?? '';
    echo "SEC $i [" . ($sec['name'] ?? '') . "] summary_len=" . strlen($summary) . "\n";

    if ($summary) {
        // Ver si hay @@PLUGINFILE@@ o pluginfile
        $hasPluginfile = stripos($summary, 'pluginfile') !== false || stripos($summary, '@@PLUGINFILE') !== false;
        $hasImg = stripos($summary, '<img') !== false;
        echo "  tiene_img=$hasImg  tiene_pluginfile=$hasPluginfile\n";

        if ($hasImg || $hasPluginfile) {
            echo "  SUMMARY HTML:\n";
            echo "  " . str_replace("\n", "\n  ", substr($summary, 0, 1500)) . "\n";
        }
    }

    foreach ($sec['modules'] ?? [] as $j => $mod) {
        $intro = $mod['intro'] ?? '';
        $desc  = $mod['description'] ?? '';
        $text  = $intro ?: $desc;

        $hasImg        = stripos($text, '<img') !== false;
        $hasPluginfile = stripos($text, 'pluginfile') !== false || stripos($text, '@@PLUGINFILE') !== false;

        if (!$hasImg && !$hasPluginfile) continue;

        echo "  MOD $j [" . ($mod['modname'] ?? '') . "] " . ($mod['name'] ?? '') . "\n";
        echo "    intro_len=" . strlen($intro) . "  desc_len=" . strlen($desc) . "\n";

        // Extraer URLs de img
        preg_match_all('/src=["\'](.*?)["\']/i', $text, $mm);
        foreach ($mm[1] as $url) {
            echo "    SRC: $url\n";
        }
        preg_match_all('/@@PLUGINFILE@@([^\s"\'<]+)/i', $text, $mm2);
        foreach ($mm2[1] as $path) {
            echo "    @@PLUGINFILE@@$path\n";
        }

        echo "    HTML:\n";
        echo "    " . str_replace("\n", "\n    ", substr($text, 0, 1000)) . "\n";
    }
    echo "\n";
}

echo "\n=== PROCESADO POR normalizeSingleText ===\n";
// Ahora llamar a getCourseContents (con normalización)
$processed = $m->getCourseContents($courseId);

foreach ($processed as $i => $sec) {
    $summary = $sec['summary'] ?? '';
    if (!$summary) continue;

    $hasBase64 = stripos($summary, 'data:image') !== false;
    $hasImg    = stripos($summary, '<img') !== false;

    echo "SEC $i summary_procesado: hasImg=$hasImg  hasBase64=$hasBase64\n";
    if ($hasImg && !$hasBase64) {
        preg_match_all('/src=["\'](.*?)["\']/i', $summary, $mm);
        foreach ($mm[1] as $url) {
            echo "  SRC procesado: " . substr($url, 0, 200) . "\n";
        }
    }
}

echo "\n=== PROCESSED WITH DETAILS (rewritePluginfileUrlsInText) ===\n";
$withDetails = $m->getCourseContentsWithDetails($courseId);
foreach ($withDetails as $i => $sec) {
    $desc = $sec['description'] ?? '';
    if (!$desc) continue;

    $hasBase64 = stripos($desc, 'data:image') !== false;
    $hasImg    = stripos($desc, '<img') !== false;
    echo "SEC $i: hasImg=$hasImg  hasBase64=$hasBase64\n";

    if ($hasImg && !$hasBase64) {
        preg_match_all('/src=["\'](.*?)["\']/i', $desc, $mm);
        foreach ($mm[1] as $url) {
            echo "  SRC_SIN_BASE64: " . substr($url, 0, 300) . "\n";
        }
    }
    if ($hasBase64) {
        echo "  ✅ Imagen embebida en base64\n";
    }

    foreach ($sec['modules'] ?? [] as $j => $mod) {
        $modDesc = $mod['description'] ?? '';
        if (!$modDesc) continue;
        $hasBase64m = stripos($modDesc, 'data:image') !== false;
        $hasImgm    = stripos($modDesc, '<img') !== false;
        if (!$hasImgm) continue;
        echo "  MOD $j [" . ($mod['modname'] ?? '') . "]: hasImg=$hasImgm  hasBase64=$hasBase64m\n";
        if ($hasImgm && !$hasBase64m) {
            preg_match_all('/src=["\'](.*?)["\']/i', $modDesc, $mm2);
            foreach ($mm2[1] as $url) {
                echo "    SRC_SIN_BASE64: " . substr($url, 0, 300) . "\n";
            }
        }
    }
}
