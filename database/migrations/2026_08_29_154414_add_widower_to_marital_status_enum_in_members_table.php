<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE members MODIFY marital_status ENUM('single', 'married', 'divorced', 'widowed', 'widower') NOT NULL DEFAULT 'single'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE members SET marital_status = 'widowed' WHERE marital_status = 'widower'");
        DB::statement("ALTER TABLE members MODIFY marital_status ENUM('single', 'married', 'divorced', 'widowed') NOT NULL DEFAULT 'single'");
    }
};
