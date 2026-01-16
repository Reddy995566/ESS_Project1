<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'site_name', 'value' => 'Clothing Ecommerce', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_email', 'value' => 'info@clothingecommerce.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_phone', 'value' => '+91 1234567890', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_address', 'value' => '123 Fashion Street, Mumbai, India', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_description', 'value' => 'Your one-stop shop for trendy clothing', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
