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
        Schema::table('tags', function (Blueprint $table) {
            // Remove unnecessary columns for simple tags - check if they exist first
            if (Schema::hasColumn('tags', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('tags', 'color')) {
                $table->dropColumn('color');
            }
            if (Schema::hasColumn('tags', 'is_featured')) {
                $table->dropColumn('is_featured');
                $table->dropIndex(['is_featured']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            // Add back the columns if needed
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3B82F6');
            $table->boolean('is_featured')->default(false);
            $table->index(['is_featured']);
        });
    }
};
