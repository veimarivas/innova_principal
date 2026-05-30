<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos_cuotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pago_id')->nullable();
            $table->unsignedBigInteger('cuota_id')->nullable();
            $table->decimal('monto_bs', 10, 2)->default(0);
            $table->date('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_cuotas');
    }
};