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
            // Basic fields
            $table->string('short_description')->nullable()->after('description');
            $table->decimal('compare_price', 10, 2)->nullable()->after('sale_price');
            
            // Stock fields
            $table->enum('stock_status', ['in_stock', 'out_of_stock'])->default('in_stock')->after('stock');
            
            // Relations
            $table->unsignedBigInteger('brand_id')->nullable()->after('stock_status');
            $table->json('categories')->nullable()->after('category');
            $table->json('tags')->nullable()->after('colors');
            $table->json('collections')->nullable()->after('tags');
            
            // Display settings
            $table->enum('visibility', ['visible', 'hidden', 'catalog', 'search'])->default('visible')->after('status');
            
            // Product badges
            $table->boolean('is_featured')->default(false)->after('featured');
            $table->boolean('is_new')->default(false)->after('is_featured');
            $table->boolean('is_trending')->default(false)->after('is_new');
            $table->boolean('is_bestseller')->default(false)->after('is_trending');
            $table->boolean('is_topsale')->default(false)->after('is_bestseller');
            $table->boolean('is_sale')->default(false)->after('is_topsale');
            $table->boolean('is_discounted')->default(false)->after('is_sale');
            $table->boolean('show_in_homepage')->default(false)->after('is_discounted');
            $table->boolean('is_exclusive')->default(false)->after('show_in_homepage');
            $table->boolean('is_limited_edition')->default(false)->after('is_exclusive');
            
            // SEO fields
            $table->string('meta_title', 60)->nullable()->after('is_limited_edition');
            $table->string('meta_description', 160)->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->text('focus_keywords')->nullable()->after('meta_keywords');
            $table->string('canonical_url')->nullable()->after('focus_keywords');
            $table->string('og_title', 60)->nullable()->after('canonical_url');
            $table->string('og_description', 160)->nullable()->after('og_title');
            
            // Foreign key
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropColumn([
                'short_description', 'compare_price', 'stock_status', 'brand_id',
                'categories', 'tags', 'collections', 'visibility',
                'is_featured', 'is_new', 'is_trending', 'is_bestseller', 'is_topsale',
                'is_sale', 'is_discounted', 'show_in_homepage', 'is_exclusive', 'is_limited_edition',
                'meta_title', 'meta_description', 'meta_keywords', 'focus_keywords',
                'canonical_url', 'og_title', 'og_description'
            ]);
        });
    }
};
