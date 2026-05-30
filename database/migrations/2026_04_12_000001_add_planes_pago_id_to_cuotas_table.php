<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuotas', function (Blueprint $table) {
            $table->foreignId('planes_pago_id')->nullable()->constrained('planes_pagos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('cuotas', function (Blueprint $table) {
            $table->dropForeign(['planes_pago_id']);
            $table->dropColumn('planes_pago_id');
        });
    }
};