<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => 'Summer Collection',
                'slug' => 'summer-collection',
                'is_active' => 1,
                'usage_count' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Winter Special',
                'slug' => 'winter-special',
                'is_active' => 1,
                'usage_count' => 12,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'New Arrivals',
                'slug' => 'new-arrivals',
                'is_active' => 1,
                'usage_count' => 8,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Best Seller',
                'slug' => 'best-seller',
                'is_active' => 1,
                'usage_count' => 25,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Limited Edition',
                'slug' => 'limited-edition',
                'is_active' => 1,
                'usage_count' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sale Items',
                'slug' => 'sale-items',
                'is_active' => 0,
                'usage_count' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Featured',
                'slug' => 'featured',
                'is_active' => 1,
                'usage_count' => 18,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Popular',
                'slug' => 'popular',
                'is_active' => 1,
                'usage_count' => 22,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Trending',
                'slug' => 'trending',
                'is_active' => 0,
                'usage_count' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'is_active' => 1,
                'usage_count' => 11,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        \App\Models\Tag::insert($tags);
    }
}
