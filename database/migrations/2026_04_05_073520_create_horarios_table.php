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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('estado', ['Confirmado', 'Desarrollado', 'Postergado'])->default('Confirmado');

            $table->unsignedBigInteger('modulo_id')->nullable();
            $table->unsignedBigInteger('trabajadores_cargo_id')->nullable();
            $table->unsignedBigInteger('reprogramado_id')->nullable();
            $table->timestamps();

            $table->foreign('modulo_id')->references('id')->on('modulos');
            $table->foreign('trabajadores_cargo_id')->references('id')->on('trabajadores_cargos');
            $table->foreign('reprogramado_id')->references('id')->on('horarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
