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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('carnet');
            $table->string('expedido')->nullable();
            $table->string('nombres');
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->enum('sexo', ['M', 'F'])->nullable();
            $table->enum('estado_civil', ['Soltero/a', 'Casado/a', 'Divorciado/a', 'Viudo/a', 'Unión Libre'])->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('correo')->nullable();
            $table->string('direccion')->nullable();
            $table->string('celular')->nullable();
            $table->string('telefono')->nullable();
            $table->unsignedBigInteger('ciudade_id')->nullable();
            $table->string('fotografia')->nullable();
            $table->string('fotografia_carnet')->nullable();
            $table->boolean('carnet_verificado')->default(false);
            $table->string('fotografia_certificado_nacimiento')->nullable();
            $table->boolean('certificado_nacimiento_verificado')->default(false);
            $table->timestamps();

            $table->foreign('ciudade_id')->references('id')->on('ciudades');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
