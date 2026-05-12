<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Note: The role column is already defined in the main users table migration
     * (0001_01_01_000000_create_users_table.php). This migration is kept for
     * historical compatibility but the actual column definition is in the main migration.
     */
    public function up(): void
    {
        // Role column is already added in the main users table migration
        // No action needed here to avoid duplicate column errors
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed - role column is defined in main migration
    }
};
