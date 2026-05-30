<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('moodle_matriculas', function (Blueprint $table) {
            $table->boolean('acceso_suspendido')->default(false)->after('matriculado_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('moodle_matriculas', function (Blueprint $table) {
            $table->dropColumn('acceso_suspendido');
        });
    }
};
