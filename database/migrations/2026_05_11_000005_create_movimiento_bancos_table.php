<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimiento_bancos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_bancaria_id')->constrained('cuentas_bancarias')->onDelete('cascade');
            $table->foreignId('pago_id')->nullable()->constrained('pagos')->onDelete('set null');
            $table->enum('tipo', ['Ingreso', 'Egreso']);
            $table->decimal('monto', 12, 2);
            $table->string('referencia', 100)->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimiento_bancos');
    }
};