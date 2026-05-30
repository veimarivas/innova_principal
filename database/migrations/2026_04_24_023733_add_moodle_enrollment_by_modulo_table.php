<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moodle_matriculas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inscripcion_id');
            $table->unsignedBigInteger('modulo_id');
            $table->unsignedBigInteger('moodle_user_id');
            $table->unsignedBigInteger('moodle_course_id');
            $table->timestamp('matriculado_at');
            $table->timestamps();

            $table->unique(['inscripcion_id', 'modulo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moodle_matriculas');
    }
};
