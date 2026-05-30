<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Ver qué moodle_course_id tienen los módulos en la BD local
$modulos = DB::table('modulos')
    ->whereNotNull('moodle_course_id')
    ->select('id', 'nombre', 'moodle_course_id')
    ->orderBy('moodle_course_id')
    ->get();

echo "=== Módulos con moodle_course_id en la BD local ===\n";
foreach ($modulos as $mod) {
    echo "  Modulo ID={$mod->id} nombre=" . substr($mod->nombre, 0, 40) . " moodle_course_id={$mod->moodle_course_id}\n";
}

// Probar cada uno para ver cuál responde OK
$m   = app('App\Services\MoodleService');
$ref = new ReflectionClass($m);
$call = $ref->getMethod('call');
$call->setAccessible(true);

echo "\n=== Probando acceso a cursos Moodle ===\n";
foreach ($modulos->take(5) as $mod) {
    $cid    = $mod->moodle_course_id;
    $result = $call->invoke($m, 'core_course_get_contents', ['courseid' => $cid]);
    $ok     = is_array($result);
    echo "  course_id=$cid: " . ($ok ? "OK (" . count($result) . " secciones)" : "ERROR") . "\n";

    if ($ok) {
        // Ver si alguna sección tiene imágenes
        foreach ($result as $sec) {
            $summary = $sec['summary'] ?? '';
            if (stripos($summary, '<img') !== false || stripos($summary, '@@PLUGINFILE') !== false || stripos($summary, 'pluginfile') !== false) {
                echo "    *** SECCIÓN CON IMAGEN: " . ($sec['name'] ?? '') . "\n";
                
                // Intentar descargar una imagen de esta sección para ver qué responde Moodle exactamente
                preg_match('/src=["\'](.*?)["\']/i', $summary, $mimg);
                if (isset($mimg[1])) {
                    $testUrl = $mimg[1];
                    if (strpos($testUrl, 'token=') === false) {
                        $testUrl .= (strpos($testUrl, '?') !== false ? '&' : '?') . 'token=' . config('moodle.token');
                    }
                    echo "    PROBANDO DESCARGA: $testUrl\n";
                    try {
                        $res = \Illuminate\Support\Facades\Http::timeout(10)->get($testUrl);
                        echo "    STATUS: " . $res->status() . "\n";
                        echo "    CONTENT-TYPE: " . $res->header('Content-Type') . "\n";
                        echo "    BODY (primeros 200 chars): " . substr($res->body(), 0, 200) . "\n";
                    } catch (\Exception $e) {
                        echo "    ERROR DESCARGA: " . $e->getMessage() . "\n";
                    }
                }
                break;
            }
        }
    }
}
