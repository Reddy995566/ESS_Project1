<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('show_in_circle')->default(false)->after('show_in_homepage');
            $table->enum('circle_type', ['text', 'image'])->default('image')->after('show_in_circle');
            $table->string('circle_text')->nullable()->after('circle_type'); // For text type like "SALE"
            $table->string('circle_bg_color')->default('#4A0F23')->after('circle_text');
            $table->string('circle_text_color')->default('#D6B27A')->after('circle_bg_color');
            $table->string('circle_link')->nullable()->after('circle_text_color');
            $table->integer('circle_order')->default(0)->after('circle_link');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['show_in_circle', 'circle_type', 'circle_text', 'circle_bg_color', 'circle_text_color', 'circle_link', 'circle_order']);
        });
    }
};
