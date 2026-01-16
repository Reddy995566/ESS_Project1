<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_reels', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('video_url'); // Video URL (ImageKit or external)
            $table->string('video_file_id')->nullable(); // ImageKit file ID
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('badge')->nullable(); // e.g., "LIMITED STOCK", "NEW", "TRENDING"
            $table->string('badge_color')->default('#3D5A3C'); // Badge background color
            $table->integer('views_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_reels');
    }
};
