<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('acceso_admin')->default(false)->after('role');
            $table->boolean('acceso_virtual')->default(false)->after('acceso_admin');
        });

        DB::table('users')->where('role', 'admin')->update(['acceso_admin' => true]);
        DB::table('users')->where('role', 'moodle')->update(['acceso_virtual' => true]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['acceso_admin', 'acceso_virtual']);
        });
    }
};
