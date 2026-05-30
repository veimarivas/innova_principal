<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$m = app('App\Services\MoodleService');
$ref = new ReflectionClass($m);
$call = $ref->getMethod('call');
$call->setAccessible(true);

$cid = 65;
echo "TESTING COURSE $cid\n";
$res = $call->invoke($m, 'core_course_get_contents', ['courseid' => $cid]);

if (!$res) {
    echo "ERROR: No data for course $cid\n";
    exit;
}

foreach ($res as $sec) {
    if (stripos($sec['summary'], '<img') !== false) {
        preg_match('/src=["\'](.*?)["\']/', $sec['summary'], $mm);
        if (isset($mm[1])) {
            $baseUrl = $mm[1];
            $url = $baseUrl . (strpos($baseUrl, '?') === false ? '?' : '&') . 'token=' . config('moodle.token');
            
            echo "URL ORIGINAL (en summary): $baseUrl\n";
            echo "URL CON TOKEN: $url\n";
            
            $resp = \Illuminate\Support\Facades\Http::get($url);
            echo "STATUS: " . $resp->status() . "\n";
            echo "TYPE: " . $resp->header('Content-Type') . "\n";
            echo "BODY: " . substr($resp->body(), 0, 300) . "\n";
            
            // Probar el proxy directo (pluginfile.php sin webservice)
            $directUrl = str_ireplace('/webservice/pluginfile.php', '/pluginfile.php', $baseUrl);
            $directUrlWithToken = $directUrl . (strpos($directUrl, '?') === false ? '?' : '&') . 'token=' . config('moodle.token');
            echo "URL DIRECTA CON TOKEN: $directUrlWithToken\n";
            $resp2 = \Illuminate\Support\Facades\Http::get($directUrlWithToken);
            echo "STATUS DIRECT: " . $resp2->status() . "\n";
            echo "TYPE DIRECT: " . $resp2->header('Content-Type') . "\n";
            echo "BODY DIRECT (300 chars): " . substr($resp2->body(), 0, 300) . "\n";
            
            break;
        }
    }
}
