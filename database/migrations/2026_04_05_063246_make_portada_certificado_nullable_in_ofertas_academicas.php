<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ofertas_academicas', function (Blueprint $table) {
            $table->string('portada')->nullable()->change();
            $table->string('certificado')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ofertas_academicas', function (Blueprint $table) {
            $table->string('portada')->nullable(false)->change();
            $table->string('certificado')->nullable(false)->change();
        });
    }
};
