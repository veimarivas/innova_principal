<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$db = DB::connection('moodle');
$moodleCourseId = 78;

$gradeItems = $db->table('grade_items')
    ->where('courseid', $moodleCourseId)
    ->where('itemtype', 'mod')
    ->whereNotNull('itemmodule')
    ->get(['id', 'itemname', 'itemmodule', 'iteminstance']);

$itemsByModule = [];
foreach ($gradeItems as $gi) {
    $itemsByModule[$gi->itemmodule][] = $gi->iteminstance;
}

foreach ($itemsByModule as $modName => $instances) {
    $mod = $db->table('modules')->where('name', $modName)->first(['id']);
    if (!$mod) {
        echo "Module type $modName not found in mdl_modules!\n";
        continue;
    }
    
    $cms = $db->table('course_modules')
        ->where('course', $moodleCourseId)
        ->where('module', $mod->id)
        ->whereIn('instance', $instances)
        ->get(); // Let's get all columns
        
    echo "Module: $modName (Module ID in Moodle: {$mod->id})\n";
    foreach ($cms as $cmRow) {
        echo "  CM ID: {$cmRow->id} | Instance: {$cmRow->instance} | DeletionInProgress: {$cmRow->deletioninprogress}\n";
    }
}

foreach ($gradeItems as $gi) {
    echo "Grade Item ID: {$gi->id} | Name: {$gi->itemname} | Module: {$gi->itemmodule} | Instance: {$gi->iteminstance}\n";
}
