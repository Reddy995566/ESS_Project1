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
            // Drop old columns if they exist
            if (Schema::hasColumn('products', 'short_description')) {
                $table->dropColumn('short_description');
            }
            if (Schema::hasColumn('products', 'additional_information')) {
                $table->dropColumn('additional_information');
            }

            // Add new columns
            $table->text('washing_instructions')->nullable()->after('description');
            $table->text('shipping_information')->nullable()->after('washing_instructions');
            $table->text('returns_refunds')->nullable()->after('shipping_information');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['washing_instructions', 'shipping_information', 'returns_refunds']);

            // Restore old columns
            $table->string('short_description')->nullable()->after('description');
            $table->text('additional_information')->nullable()->after('short_description');
        });
    }
};
