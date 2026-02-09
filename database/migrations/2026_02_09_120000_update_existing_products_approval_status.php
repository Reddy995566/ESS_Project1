<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing products without seller_id to approved (admin products)
        DB::table('products')
            ->whereNull('seller_id')
            ->update(['approval_status' => 'approved']);
            
        // Update existing products with seller_id to pending (seller products)
        DB::table('products')
            ->whereNotNull('seller_id')
            ->update(['approval_status' => 'pending']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert all products to approved
        DB::table('products')->update(['approval_status' => 'approved']);
    }
};