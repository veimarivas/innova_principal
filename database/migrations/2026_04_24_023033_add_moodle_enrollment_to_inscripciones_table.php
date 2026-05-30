<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->unsignedBigInteger('moodle_user_id')->nullable()->after('estudiante_id');
            $table->boolean('en_moodle')->default(false)->after('moodle_user_id');
            $table->timestamp('matriculado_moodle_at')->nullable()->after('en_moodle');
        });
    }

    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->dropColumn(['moodle_user_id', 'en_moodle', 'matriculado_moodle_at']);
        });
    }
};
