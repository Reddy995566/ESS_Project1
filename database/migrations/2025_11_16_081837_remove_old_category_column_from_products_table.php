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
        Schema::table('products', function (Blueprint $table) {
            // Remove old category and categories columns that conflict with relationships
            $table->dropColumn(['category', 'categories']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Re-add the columns if migration is rolled back
            $table->string('category')->nullable()->after('images');
            $table->longText('categories')->nullable()->after('category');
        });
    }
};
