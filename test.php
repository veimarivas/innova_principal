<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$f = Illuminate\Support\Facades\DB::connection('moodle')->table('forum')->orderBy('id', 'desc')->limit(5)->get();
print_r($f);
