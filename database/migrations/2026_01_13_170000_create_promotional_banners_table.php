<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotional_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('link')->nullable();
            
            // Desktop Image - ImageKit fields
            $table->string('desktop_image')->nullable();
            $table->string('desktop_imagekit_file_id')->nullable();
            $table->string('desktop_imagekit_url')->nullable();
            $table->string('desktop_imagekit_file_path')->nullable();
            
            // Mobile Image - ImageKit fields
            $table->string('mobile_image')->nullable();
            $table->string('mobile_imagekit_file_id')->nullable();
            $table->string('mobile_imagekit_url')->nullable();
            $table->string('mobile_imagekit_file_path')->nullable();
            
            // Position - which section to show after
            $table->string('position')->default('after_hot_collection');
            // Positions: after_hero_banner, after_all_time_favorites, after_hot_collection, after_shop_by_budget, after_video_reels
            
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            
            // Date range for scheduled banners
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotional_banners');
    }
};
