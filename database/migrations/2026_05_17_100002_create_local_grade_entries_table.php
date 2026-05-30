<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('local_grade_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modulo_id');
            $table->integer('moodle_item_id'); // negative for manual items
            $table->integer('moodle_user_id');
            $table->decimal('grade', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('modulo_id')->references('id')->on('modulos')->onDelete('cascade');
            $table->unique(['modulo_id', 'moodle_item_id', 'moodle_user_id'], 'lge_modulo_item_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('local_grade_entries');
    }
};
