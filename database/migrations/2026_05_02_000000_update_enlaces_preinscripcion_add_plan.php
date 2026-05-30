<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ya no necesitamos la tabla de solicitudes; las pre-inscripciones van a inscripciones
        Schema::dropIfExists('solicitudes_preinscripcion');

        Schema::table('enlaces_preinscripcion', function (Blueprint $table) {
            // Agregar índice independiente en oferta_academica_id para que
            // la FK pueda funcionar sin el UNIQUE compuesto
            $table->index('oferta_academica_id', 'idx_enlace_oferta');

            // Quitar FK que depende del UNIQUE compuesto
            $table->dropForeign('enlaces_preinscripcion_oferta_academica_id_foreign');

            // Ahora sí se puede quitar el UNIQUE compuesto
            $table->dropUnique('uq_enlace_oferta_cargo');

            // Restaurar FK apoyándose en el nuevo índice simple
            $table->foreign('oferta_academica_id', 'fk_enlace_oferta')
                ->references('id')->on('ofertas_academicas')
                ->cascadeOnDelete();

            // Plan de pago opcional — null = enlace sin plan asignado
            $table->foreignId('planes_pago_id')
                ->nullable()
                ->after('trabajadores_cargo_id')
                ->constrained('planes_pagos')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('enlaces_preinscripcion', function (Blueprint $table) {
            $table->dropForeign(['planes_pago_id']);
            $table->dropColumn('planes_pago_id');
            $table->unique(['oferta_academica_id', 'trabajadores_cargo_id'], 'uq_enlace_oferta_cargo');
        });
    }
};
