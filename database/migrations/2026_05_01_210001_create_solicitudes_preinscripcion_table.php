<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_preinscripcion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enlace_id')->constrained('enlaces_preinscripcion')->cascadeOnDelete();
            $table->string('nombres');
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable();
            $table->string('carnet')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('observacion')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_preinscripcion');
    }
};
