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
        Schema::create('planes_pagos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('habilitado')->default(true);
            $table->boolean('principal')->default(false);
            $table->boolean('es_promocion')->default(false);
            $table->date('fecha_inicio_promocion')->nullable();
            $table->date('fecha_fin_promocion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes_pagos');
    }
};
