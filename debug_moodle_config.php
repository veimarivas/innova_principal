<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$moodleUrl   = config('moodle.url');
$moodleToken = config('moodle.token');

echo "MOODLE_URL: $moodleUrl\n";
echo "MOODLE_TOKEN: " . substr($moodleToken, 0, 12) . "...\n";

$m = app('App\Services\MoodleService');
echo "PING: " . ($m->ping() ? 'OK' : 'FAIL') . "\n";

// Listar cursos para encontrar IDs válidos
$ref  = new ReflectionClass($m);
$call = $ref->getMethod('call');
$call->setAccessible(true);

// Intentar obtener cursos disponibles
$courses = $call->invoke($m, 'core_course_get_courses', []);
if (is_array($courses)) {
    echo "\nCursos disponibles:\n";
    foreach (array_slice($courses, 0, 10) as $c) {
        echo "  ID=" . ($c['id'] ?? '?') . " shortname=" . ($c['shortname'] ?? '?') . " fullname=" . substr($c['fullname'] ?? '', 0, 50) . "\n";
    }
} else {
    echo "ERROR listando cursos\n";
}

// Probar con course 97 directamente y mostrar error
$result = $call->invoke($m, 'core_course_get_contents', ['courseid' => 97]);
if ($result === null) {
    echo "\ncourse_id=97: retornó NULL (error en API, ver logs)\n";
    // Ver el log
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        $lines = file($logFile);
        $last = array_slice($lines, -20);
        echo "Últimas líneas del log:\n" . implode('', $last);
    }
} elseif (!is_array($result)) {
    echo "\ncourse_id=97: respuesta tipo " . gettype($result) . "\n";
} else {
    echo "\ncourse_id=97: OK, " . count($result) . " secciones\n";
}
