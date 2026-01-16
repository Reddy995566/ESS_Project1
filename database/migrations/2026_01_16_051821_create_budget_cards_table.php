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
        Schema::create('budget_cards', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('SHOP'); // e.g., SAREES, SHIRTS, KURTAS
            $table->string('subtitle')->default('UNDER'); // e.g., UNDER, STARTING AT
            $table->integer('price'); // e.g., 999, 1499, 1999
            $table->string('link')->nullable(); // Custom link or auto-generate
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_cards');
    }
};
