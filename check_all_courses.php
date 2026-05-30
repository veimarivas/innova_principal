<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\MoodleService;
use Illuminate\Support\Facades\DB;

$db = DB::connection('moodle');
$moodle = new MoodleService();

// Let's get all courses that have modules
$courses = $db->table('grade_items')
    ->where('itemtype', 'mod')
    ->whereNotNull('itemmodule')
    ->distinct()
    ->pluck('courseid');

echo "Checking " . count($courses) . " courses...\n";

foreach ($courses as $courseId) {
    // Get all grade items of type mod for this course
    $gradeItems = $db->table('grade_items')
        ->where('courseid', $courseId)
        ->where('itemtype', 'mod')
        ->whereNotNull('itemmodule')
        ->get();
        
    // Let's find cmids for these items
    $activeItemsCount = 0;
    foreach ($gradeItems as $gi) {
        $mod = $db->table('modules')->where('name', $gi->itemmodule)->first();
        if ($mod) {
            $cm = $db->table('course_modules')
                ->where('course', $courseId)
                ->where('module', $mod->id)
                ->where('instance', $gi->iteminstance)
                ->where('deletioninprogress', 0)
                ->first();
            if ($cm) {
                $activeItemsCount++;
            }
        }
    }
    
    // Call getStudentGrades with empty or dummy user list
    $res = $moodle->getStudentGrades($courseId, [999999]); // dummy user id
    
    echo "Course ID: $courseId | Grade Items (mod): " . count($gradeItems) . " | Active items: $activeItemsCount | getStudentGrades returned: " . count($res) . "\n";
    
    if ($activeItemsCount !== count($res)) {
        echo "  --> WARNING: Mismatch in Course ID $courseId!\n";
    }
}
