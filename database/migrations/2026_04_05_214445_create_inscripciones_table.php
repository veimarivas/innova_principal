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
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ofertas_academica_id')->constrained('ofertas_academicas')->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('trabajadores_cargo_id')->constrained('trabajadores_cargos')->onDelete('cascade');
            $table->foreignId('planes_pago_id')->constrained('planes_pagos')->onDelete('cascade');
            $table->string('estado')->default('activo');
            $table->decimal('adelanto_bs', 10, 2)->default(0);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
