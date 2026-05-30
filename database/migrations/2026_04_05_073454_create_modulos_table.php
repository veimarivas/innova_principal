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
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->integer('n_modulo')->default(0);
            $table->string('nombre');
            $table->enum('estado', ['No Inicio', 'En Desarrollo', 'Concluido'])->default('No Inicio');
            $table->string('color');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->unsignedBigInteger('docente_id')->nullable();
            $table->unsignedBigInteger('ofertas_academica_id');
            $table->timestamps();

            $table->foreign('docente_id')
                ->references('id')->on('docentes');
            $table->foreign('ofertas_academica_id')
                ->references('id')->on('ofertas_academicas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};
