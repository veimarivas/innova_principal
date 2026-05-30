<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos_respaldos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscripcione_id')->constrained('inscripciones')->onDelete('cascade');
            $table->foreignId('cuota_id')->nullable()->constrained('cuotas')->onDelete('set null');
            $table->string('archivo');
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['pendiente', 'verificado', 'rechazado'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_respaldos');
    }
};
