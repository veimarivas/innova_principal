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
        Schema::create('ofertas_academicas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');

            $table->unsignedBigInteger('sucursale_id')->nullable();
            $table->unsignedBigInteger('modalidade_id')->nullable();
            $table->unsignedBigInteger('posgrado_id');
            $table->unsignedBigInteger('programa_id');
            $table->unsignedBigInteger('fase_id');
            $table->date('fecha_inicio_inscripciones')->default(now());
            $table->date('fecha_inicio_programa')->default(now());
            $table->date('fecha_fin_programa')->default(now());
            $table->integer('gestion');
            $table->integer('n_modulos');
            $table->integer('cantidad_sesiones');
            $table->integer('version');
            $table->integer('grupo');
            $table->integer('nota_minima');
            $table->unsignedBigInteger('responsable_marketing_id')->nullable();
            $table->unsignedBigInteger('responsable_academico_id')->nullable();

            $table->string('portada')->nullable();
            $table->string('certificado')->nullable();
            $table->string('color');
            $table->timestamps();

            $table->foreign('sucursale_id')->references('id')->on('sucursales');
            $table->foreign('modalidade_id')->references('id')->on('modalidades');
            $table->foreign('posgrado_id')->references('id')->on('posgrados');
            $table->foreign('programa_id')->references('id')->on('programas');
            $table->foreign('fase_id')->references('id')->on('fases');
            $table->foreign('responsable_marketing_id')->references('id')->on('trabajadores_cargos');
            $table->foreign('responsable_academico_id')->references('id')->on('trabajadores_cargos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ofertas_academicas');
    }
};
