<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$db = DB::connection('moodle');
$nullNameItems = $db->table('grade_items')
    ->where('itemtype', 'mod')
    ->where(function($q) {
        $q->whereNull('itemname')->orWhere('itemname', '');
    })
    ->get();

echo "Found " . $nullNameItems->count() . " items with null or empty itemname.\n";
foreach ($nullNameItems->take(10) as $it) {
    echo "ID: {$it->id} | Course: {$it->courseid} | Module: {$it->itemmodule} | Instance: {$it->iteminstance}\n";
}
