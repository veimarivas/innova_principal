<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trabajadore_cargo_id')->nullable();
            $table->decimal('monto_total', 10, 2)->default(0);
            $table->decimal('descuento_bs', 10, 2)->default(0);
            $table->enum('tipo_pago', ['Efectivo', 'Qr', 'Parcial'])->nullable();
            $table->date('fecha_pago')->nullable();
            $table->string('estado')->default('Pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};