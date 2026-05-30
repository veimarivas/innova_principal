<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$moodleCourseId = 78; // Let's try 78 or find one that has moodle_course_id
$modulo = \App\Models\Modulo::whereNotNull('moodle_course_id')->first();
if ($modulo) {
    $moodleCourseId = $modulo->moodle_course_id;
    echo "Using Course ID: " . $moodleCourseId . " for Modulo ID: " . $modulo->id . "\n";
}

$db = DB::connection('moodle');
$items = $db->table('grade_items')->where('courseid', $moodleCourseId)->get();
foreach ($items as $it) {
    echo "ID: {$it->id} | Type: {$it->itemtype} | Module: {$it->itemmodule} | Instance: {$it->iteminstance} | Name: " . ($it->itemname ?? 'NULL') . "\n";
}
