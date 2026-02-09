<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if an index exists on a table
     */
    private function indexExists($table, $indexName): bool
    {
        $indexes = DB::select("SHOW INDEXES FROM {$table}");
        foreach ($indexes as $index) {
            if ($index->Key_name === $indexName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Colors: Add composite unique on name + seller_id
        if ($this->indexExists('colors', 'colors_name_unique')) {
            Schema::table('colors', function (Blueprint $table) {
                $table->dropUnique('colors_name_unique');
            });
        }
        Schema::table('colors', function (Blueprint $table) {
            $table->unique(['name', 'seller_id']);
        });

        // Sizes: Add composite unique on name + seller_id
        if ($this->indexExists('sizes', 'sizes_name_unique')) {
            Schema::table('sizes', function (Blueprint $table) {
                $table->dropUnique('sizes_name_unique');
            });
        }
        Schema::table('sizes', function (Blueprint $table) {
            $table->unique(['name', 'seller_id']);
        });

        // Collections: Add composite unique
        if ($this->indexExists('collections', 'collections_name_unique')) {
            Schema::table('collections', function (Blueprint $table) {
                $table->dropUnique('collections_name_unique');
            });
        }
        if ($this->indexExists('collections', 'collections_slug_unique')) {
            Schema::table('collections', function (Blueprint $table) {
                $table->dropUnique('collections_slug_unique');
            });
        }
        Schema::table('collections', function (Blueprint $table) {
            $table->unique(['name', 'seller_id']);
            $table->unique(['slug', 'seller_id']);
        });

        // Tags: Add composite unique
        if ($this->indexExists('tags', 'tags_name_unique')) {
            Schema::table('tags', function (Blueprint $table) {
                $table->dropUnique('tags_name_unique');
            });
        }
        if ($this->indexExists('tags', 'tags_slug_unique')) {
            Schema::table('tags', function (Blueprint $table) {
                $table->dropUnique('tags_slug_unique');
            });
        }
        Schema::table('tags', function (Blueprint $table) {
            $table->unique(['name', 'seller_id']);
            $table->unique(['slug', 'seller_id']);
        });

        // Categories: Add composite unique
        if ($this->indexExists('categories', 'categories_slug_unique')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropUnique('categories_slug_unique');
            });
        }
        Schema::table('categories', function (Blueprint $table) {
            $table->unique(['slug', 'seller_id']);
        });

        // Fabrics: Add composite unique
        if ($this->indexExists('fabrics', 'fabrics_slug_unique')) {
            Schema::table('fabrics', function (Blueprint $table) {
                $table->dropUnique('fabrics_slug_unique');
            });
        }
        Schema::table('fabrics', function (Blueprint $table) {
            $table->unique(['slug', 'seller_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Colors
        if ($this->indexExists('colors', 'colors_name_seller_id_unique')) {
            Schema::table('colors', function (Blueprint $table) {
                $table->dropUnique('colors_name_seller_id_unique');
            });
        }
        Schema::table('colors', function (Blueprint $table) {
            $table->unique('name');
        });

        // Sizes
        if ($this->indexExists('sizes', 'sizes_name_seller_id_unique')) {
            Schema::table('sizes', function (Blueprint $table) {
                $table->dropUnique('sizes_name_seller_id_unique');
            });
        }
        Schema::table('sizes', function (Blueprint $table) {
            $table->unique('name');
        });

        // Collections
        if ($this->indexExists('collections', 'collections_name_seller_id_unique')) {
            Schema::table('collections', function (Blueprint $table) {
                $table->dropUnique('collections_name_seller_id_unique');
            });
        }
        if ($this->indexExists('collections', 'collections_slug_seller_id_unique')) {
            Schema::table('collections', function (Blueprint $table) {
                $table->dropUnique('collections_slug_seller_id_unique');
            });
        }
        Schema::table('collections', function (Blueprint $table) {
            $table->unique('name');
            $table->unique('slug');
        });

        // Tags
        if ($this->indexExists('tags', 'tags_name_seller_id_unique')) {
            Schema::table('tags', function (Blueprint $table) {
                $table->dropUnique('tags_name_seller_id_unique');
            });
        }
        if ($this->indexExists('tags', 'tags_slug_seller_id_unique')) {
            Schema::table('tags', function (Blueprint $table) {
                $table->dropUnique('tags_slug_seller_id_unique');
            });
        }
        Schema::table('tags', function (Blueprint $table) {
            $table->unique('name');
            $table->unique('slug');
        });

        // Categories
        if ($this->indexExists('categories', 'categories_slug_seller_id_unique')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropUnique('categories_slug_seller_id_unique');
            });
        }
        Schema::table('categories', function (Blueprint $table) {
            $table->unique('slug');
        });

        // Fabrics
        if ($this->indexExists('fabrics', 'fabrics_slug_seller_id_unique')) {
            Schema::table('fabrics', function (Blueprint $table) {
                $table->dropUnique('fabrics_slug_seller_id_unique');
            });
        }
        Schema::table('fabrics', function (Blueprint $table) {
            $table->unique('slug');
        });
    }
};