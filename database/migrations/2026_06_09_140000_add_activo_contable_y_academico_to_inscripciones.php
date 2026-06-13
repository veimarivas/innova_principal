<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->boolean('activo_contable')->default(true)->after('activo');
            $table->boolean('activo_academico')->default(true)->after('activo_contable');
        });
    }

    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->dropColumn(['activo_contable', 'activo_academico']);
        });
    }
};
