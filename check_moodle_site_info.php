<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$m = app('App\Services\MoodleService');
$ref = new ReflectionClass($m);
$call = $ref->getMethod('call');
$call->setAccessible(true);

$res = $call->invoke($m, 'core_webservice_get_site_info');
echo "SITE INFO:\n";
print_r($res);

if (isset($res['functions'])) {
    echo "\nAVAILABLE FUNCTIONS:\n";
    foreach ($res['functions'] as $f) {
        echo " - " . $f['name'] . "\n";
    }
}

if (isset($res['downloadfiles'])) {
    echo "\nDOWNLOAD FILES ENABLED: " . ($res['downloadfiles'] ? "YES" : "NO") . "\n";
} else {
    echo "\nDOWNLOAD FILES FIELD NOT FOUND IN SITE INFO\n";
}
