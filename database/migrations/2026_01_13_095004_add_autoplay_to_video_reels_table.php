<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_reels', function (Blueprint $table) {
            $table->boolean('autoplay')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('video_reels', function (Blueprint $table) {
            $table->dropColumn('autoplay');
        });
    }
};
