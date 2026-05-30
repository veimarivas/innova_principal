<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas_bancarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banco_id')->constrained('bancos')->onDelete('cascade');
            $table->string('numero_cuenta', 50);
            $table->enum('tipo_cuenta', ['Cuenta Corriente', 'Cuenta de Ahorro']);
            $table->string('titular', 150)->nullable();
            $table->string('ci_titular', 20)->nullable();
            $table->string('imagen_qr')->nullable();
            $table->date('fecha_vencimiento_qr')->nullable();
            $table->boolean('es_principal')->default(false);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas_bancarias');
    }
};