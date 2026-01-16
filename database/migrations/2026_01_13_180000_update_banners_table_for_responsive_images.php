<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            // Rename existing image to desktop_image
            $table->renameColumn('image', 'desktop_image');
        });

        Schema::table('banners', function (Blueprint $table) {
            // Add desktop ImageKit fields
            $table->string('desktop_imagekit_file_id')->nullable()->after('desktop_image');
            $table->string('desktop_imagekit_url')->nullable()->after('desktop_imagekit_file_id');
            
            // Add mobile image fields
            $table->string('mobile_image')->nullable()->after('desktop_imagekit_url');
            $table->string('mobile_imagekit_file_id')->nullable()->after('mobile_image');
            $table->string('mobile_imagekit_url')->nullable()->after('mobile_imagekit_file_id');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'desktop_imagekit_file_id',
                'desktop_imagekit_url',
                'mobile_image',
                'mobile_imagekit_file_id',
                'mobile_imagekit_url',
            ]);
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->renameColumn('desktop_image', 'image');
        });
    }
};
