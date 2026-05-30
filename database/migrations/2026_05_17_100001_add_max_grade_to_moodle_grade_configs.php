<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('moodle_grade_configs', function (Blueprint $table) {
            $table->decimal('max_grade', 8, 2)->nullable()->after('activity_type');
        });
    }

    public function down(): void
    {
        Schema::table('moodle_grade_configs', function (Blueprint $table) {
            $table->dropColumn('max_grade');
        });
    }
};
