<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago_respaldo_cuota', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pago_respaldo_id');
            $table->unsignedBigInteger('cuota_id');
            $table->timestamps();

            $table->foreign('pago_respaldo_id')->references('id')->on('pagos_respaldos')->onDelete('cascade');
            $table->foreign('cuota_id')->references('id')->on('cuotas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago_respaldo_cuota');
    }
};
