<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop existing unique constraint on slug only (name constraint doesn't exist)
            $table->dropUnique('categories_slug_unique');
        });
        
        // Add composite unique constraints that include deleted_at
        // This allows same name/slug for soft-deleted records
        DB::statement('CREATE UNIQUE INDEX categories_name_unique ON categories (name, deleted_at)');
        DB::statement('CREATE UNIQUE INDEX categories_slug_unique ON categories (slug, deleted_at)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the composite unique constraints
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_name_unique');
            $table->dropIndex('categories_slug_unique');
        });
        
        // Restore original unique constraint (only slug had one)
        Schema::table('categories', function (Blueprint $table) {
            $table->unique('slug');
        });
    }
};
