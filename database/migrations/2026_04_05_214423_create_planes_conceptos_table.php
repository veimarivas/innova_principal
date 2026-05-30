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
        Schema::create('planes_conceptos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ofertas_academica_id')->constrained('ofertas_academicas')->onDelete('cascade');
            $table->foreignId('planes_pago_id')->constrained('planes_pagos')->onDelete('cascade');
            $table->foreignId('concepto_id')->constrained('conceptos')->onDelete('cascade');
            $table->integer('n_cuotas')->default(1);
            $table->decimal('pago_bs', 10, 2)->default(0);
            $table->decimal('precio_regular', 10, 2)->default(0);
            $table->decimal('descuento_bs', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes_conceptos');
    }
};
