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
        Schema::table('cuotas', function (Blueprint $table) {
            // Eliminar la clave foránea y la columna planes_pago_id
            $table->dropForeign(['planes_pago_id']);
            $table->dropColumn('planes_pago_id');

            // Agregar nuevos campos
            $table->string('nombre')->after('inscripcione_id')->comment('Nombre del concepto y número de cuota');
            $table->decimal('pago_pendiente_bs', 10, 2)->after('monto_bs')->default(0);
            $table->decimal('descuento_bs', 10, 2)->after('pago_pendiente_bs')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuotas', function (Blueprint $table) {
            // Restaurar planes_pago_id
            $table->unsignedBigInteger('planes_pago_id')->nullable()->after('inscripcione_id');
            $table->foreign('planes_pago_id')->references('id')->on('planes_pagos')->onDelete('cascade');

            // Eliminar nuevos campos
            $table->dropColumn(['nombre', 'pago_pendiente_bs', 'descuento_bs']);
        });
    }
};
