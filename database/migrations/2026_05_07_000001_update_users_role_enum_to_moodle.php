<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'estudiante', 'moodle') NOT NULL DEFAULT 'user'");
        DB::statement("UPDATE users SET role = 'moodle' WHERE role = 'estudiante'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'moodle') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'estudiante', 'moodle') NOT NULL DEFAULT 'user'");
        DB::statement("UPDATE users SET role = 'estudiante' WHERE role = 'moodle'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'estudiante') NOT NULL DEFAULT 'user'");
    }
};
