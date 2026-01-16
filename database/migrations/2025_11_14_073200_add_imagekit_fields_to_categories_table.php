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
        Schema::table('categories', function (Blueprint $table) {
            // ImageKit related fields
            $table->string('imagekit_file_id')->nullable()->after('image');
            $table->string('imagekit_url')->nullable()->after('imagekit_file_id');
            $table->string('imagekit_thumbnail_url')->nullable()->after('imagekit_url');
            $table->string('imagekit_file_path')->nullable()->after('imagekit_thumbnail_url');
            $table->integer('image_size')->nullable()->after('imagekit_file_path')->comment('Image size in bytes');
            $table->integer('original_image_size')->nullable()->after('image_size')->comment('Original image size in bytes');
            $table->integer('image_width')->nullable()->after('original_image_size')->default(540);
            $table->integer('image_height')->nullable()->after('image_width')->default(689);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'imagekit_file_id',
                'imagekit_url', 
                'imagekit_thumbnail_url',
                'imagekit_file_path',
                'image_size',
                'original_image_size',
                'image_width',
                'image_height'
            ]);
        });
    }
};
