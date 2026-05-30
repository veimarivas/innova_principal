<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('moodle_grade_configs', function (Blueprint $table) {
            $table->string('calculation_mode', 20)->default('ponderar')->after('is_cumulative');
        });
    }

    public function down(): void
    {
        Schema::table('moodle_grade_configs', function (Blueprint $table) {
            $table->dropColumn('calculation_mode');
        });
    }
};
