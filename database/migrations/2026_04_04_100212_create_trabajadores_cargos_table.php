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
        Schema::create('trabajadores_cargos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trabajadore_id')->nullable();
            $table->unsignedBigInteger('cargo_id')->nullable();
            $table->unsignedBigInteger('sucursale_id')->nullable();
            $table->enum('estado', ['Vigente', 'No Vigente'])->default('Vigente');
            $table->date('fecha_ingreso');
            $table->date('fecha_termino')->nullable();
            $table->timestamps();

            $table->foreign('trabajadore_id')->references('id')->on('trabajadores');
            $table->foreign('cargo_id')->references('id')->on('cargos');
            $table->foreign('sucursale_id')->references('id')->on('sucursales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajadores_cargos');
    }
};
