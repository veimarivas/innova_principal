<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posgrados', function (Blueprint $table) {
            $table->boolean('estado')->default(true)->after('objetivo')->comment('true=Activo, false=No Activo');
        });
    }

    public function down(): void
    {
        Schema::table('posgrados', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
