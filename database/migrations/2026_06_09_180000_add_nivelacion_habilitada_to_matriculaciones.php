<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matriculaciones', function (Blueprint $table) {
            $table->boolean('nivelacion_habilitada')->default(false)->after('nota_nivelacion');
        });
    }

    public function down(): void
    {
        Schema::table('matriculaciones', function (Blueprint $table) {
            $table->dropColumn('nivelacion_habilitada');
        });
    }
};
