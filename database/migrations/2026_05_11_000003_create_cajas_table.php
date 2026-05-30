<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajadore_cargo_id')->constrained('trabajadores_cargos')->onDelete('cascade');
            $table->string('nombre', 50)->default('Caja Chica');
            $table->decimal('monto_inicial', 12, 2)->default(0);
            $table->decimal('monto_actual', 12, 2)->default(0);
            $table->enum('estado', ['Abierta', 'Cerrada'])->default('Cerrada');
            $table->dateTime('fecha_apertura')->nullable();
            $table->dateTime('fecha_cierre')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};