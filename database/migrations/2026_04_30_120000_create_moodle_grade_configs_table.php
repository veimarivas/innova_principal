<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moodle_grade_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modulo_id');
            $table->integer('moodle_item_id');
            $table->string('activity_name');
            $table->string('activity_type', 50)->default('assign');
            $table->integer('cmid')->nullable();
            $table->decimal('weight', 6, 2)->default(0);
            $table->boolean('is_cumulative')->default(true);
            $table->timestamps();

            $table->foreign('modulo_id')->references('id')->on('modulos')->onDelete('cascade');
            $table->unique(['modulo_id', 'moodle_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moodle_grade_configs');
    }
};
