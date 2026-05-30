<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enlaces_preinscripcion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oferta_academica_id')->constrained('ofertas_academicas')->cascadeOnDelete();
            $table->foreignId('trabajadores_cargo_id')->nullable()->constrained('trabajadores_cargos')->nullOnDelete();
            $table->uuid('token')->unique();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['oferta_academica_id', 'trabajadores_cargo_id'], 'uq_enlace_oferta_cargo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enlaces_preinscripcion');
    }
};
