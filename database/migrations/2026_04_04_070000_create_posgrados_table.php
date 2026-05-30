<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posgrados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('creditaje')->default(0);
            $table->integer('carga_horaria')->default(0);
            $table->integer('duracion_numero')->default(0);
            $table->enum('duracion_unidad', ['Horas', 'Días', 'Semanas', 'Meses'])->default('Horas');
            $table->string('dirigido')->nullable();
            $table->string('objetivo')->nullable();

            $table->unsignedBigInteger('convenio_id');
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('tipo_id');
            $table->timestamps();

            $table->foreign('convenio_id')->references('id')->on('convenios');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('tipo_id')->references('id')->on('tipos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posgrados');
    }
};
