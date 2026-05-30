<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalles', function (Blueprint $table) {
            $table->foreignId('cuenta_bancaria_id')->nullable()->constrained('cuentas_bancarias')->onDelete('set null');
            $table->foreignId('caja_id')->nullable()->constrained('cajas')->onDelete('set null');
            $table->string('referencia', 100)->nullable()->after('monto_bs');
        });
    }

    public function down(): void
    {
        Schema::table('detalles', function (Blueprint $table) {
            $table->dropForeign(['cuenta_bancaria_id']);
            $table->dropForeign(['caja_id']);
            $table->dropColumn(['cuenta_bancaria_id', 'caja_id', 'referencia']);
        });
    }
};