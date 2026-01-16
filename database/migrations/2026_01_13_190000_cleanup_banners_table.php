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
        Schema::table('banners', function (Blueprint $table) {
            // Drop unnecessary columns
            if (Schema::hasColumn('banners', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('banners', 'subtitle')) {
                $table->dropColumn('subtitle');
            }
            if (Schema::hasColumn('banners', 'button_text')) {
                $table->dropColumn('button_text');
            }
            if (Schema::hasColumn('banners', 'button_position')) {
                $table->dropColumn('button_position');
            }
            if (Schema::hasColumn('banners', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('banners', 'imagekit_file_id')) {
                $table->dropColumn('imagekit_file_id');
            }
            if (Schema::hasColumn('banners', 'imagekit_url')) {
                $table->dropColumn('imagekit_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_position')->nullable();
            $table->string('image')->nullable();
            $table->string('imagekit_file_id')->nullable();
            $table->string('imagekit_url')->nullable();
        });
    }
};
