<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            // Drop existing unique constraints
            $table->dropUnique(['name']);
            $table->dropUnique(['slug']);
            
            // Add composite unique constraints that include deleted_at
            // This allows same name/slug for soft-deleted records
            $table->unique(['name', 'deleted_at']);
            $table->unique(['slug', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            // Drop composite unique constraints
            $table->dropUnique(['name', 'deleted_at']);
            $table->dropUnique(['slug', 'deleted_at']);
            
            // Restore original unique constraints
            $table->unique('name');
            $table->unique('slug');
        });
    }
};
