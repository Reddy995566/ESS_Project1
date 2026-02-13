<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Size;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class DummyProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create category
        $category = Category::firstOrCreate(
            ['slug' => 'sarees'],
            [
                'name' => 'Sarees',
                'description' => 'Beautiful collection of sarees',
                'is_active' => true,
                'sort_order' => 1
            ]
        );

        // Get or create brand
        $brand = Brand::firstOrCreate(
            ['name' => 'Ethnic Wear'],
            [
                'description' => 'Premium ethnic wear collection',
                'is_active' => true,
                'sort_order' => 1
            ]
        );

        // Create or get colors
        $colors = [
            [
                'name' => 'Elegant Black',
                'slug' => 'elegant-black',
                'hex_code' => '#000000',
                'images' => [
                    'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=800',
                    'https://images.unsplash.com/photo-1583391733956-6c78276477e2?w=800',
                    'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=800',
                    'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=800&q=90',
                ]
            ],
            [
                'name' => 'Royal Ivory',
                'slug' => 'royal-ivory',
                'hex_code' => '#FFFFF0',
                'images' => [
                    'https://images.unsplash.com/photo-1583391733981-8b1e0a9b5d1f?w=800',
                    'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=800&sat=-100',
                    'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=800&sat=-50',
                    'https://images.unsplash.com/photo-1583391733956-6c78276477e2?w=800&sat=-50',
                ]
            ],
            [
                'name' => 'Denim Blue',
                'slug' => 'denim-blue',
                'hex_code' => '#1560BD',
                'images' => [
                    'https://images.unsplash.com/photo-1583391733956-6c78276477e2?w=800&hue=200',
                    'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=800&hue=200',
                    'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=800&hue=200',
                    'https://images.unsplash.com/photo-1583391733981-8b1e0a9b5d1f?w=800&hue=200',
                ]
            ],
            [
                'name' => 'Classic Navy',
                'slug' => 'classic-navy',
                'hex_code' => '#000080',
                'images' => [
                    'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=800&hue=220',
                    'https://images.unsplash.com/photo-1583391733956-6c78276477e2?w=800&hue=220',
                    'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=800&hue=220',
                    'https://images.unsplash.com/photo-1583391733981-8b1e0a9b5d1f?w=800&hue=220',
                ]
            ],
            [
                'name' => 'Magic Maroon',
                'slug' => 'magic-maroon',
                'hex_code' => '#800000',
                'images' => [
                    'https://images.unsplash.com/photo-1583391733981-8b1e0a9b5d1f?w=800&hue=340',
                    'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=800&hue=340',
                    'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=800&hue=340',
                    'https://images.unsplash.com/photo-1583391733956-6c78276477e2?w=800&hue=340',
                ]
            ],
            [
                'name' => 'Salmon Pink',
                'slug' => 'salmon-pink',
                'hex_code' => '#FA8072',
                'images' => [
                    'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=800&hue=350',
                    'https://images.unsplash.com/photo-1583391733956-6c78276477e2?w=800&hue=350',
                    'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=800&hue=350',
                    'https://images.unsplash.com/photo-1583391733981-8b1e0a9b5d1f?w=800&hue=350',
                ]
            ],
        ];

        $colorIds = [];
        foreach ($colors as $colorData) {
            $color = Color::firstOrCreate(
                ['name' => $colorData['name']],
                [
                    'hex_code' => $colorData['hex_code'],
                    'is_active' => true,
                    'sort_order' => count($colorIds) + 1
                ]
            );
            $colorIds[$color->id] = $colorData['images'];
        }

        // Create or get sizes
        $sizes = [
            ['name' => 'Small', 'abbreviation' => 'S', 'sort_order' => 1],
            ['name' => 'Medium', 'abbreviation' => 'M', 'sort_order' => 2],
            ['name' => 'Large', 'abbreviation' => 'L', 'sort_order' => 3],
            ['name' => 'Extra Large', 'abbreviation' => 'XL', 'sort_order' => 4],
            ['name' => 'XXL', 'abbreviation' => 'XXL', 'sort_order' => 5],
        ];

        $sizeIds = [];
        foreach ($sizes as $sizeData) {
            $size = Size::firstOrCreate(
                ['abbreviation' => $sizeData['abbreviation']],
                [
                    'name' => $sizeData['name'],
                    'sort_order' => $sizeData['sort_order'],
                    'is_active' => true
                ]
            );
            $sizeIds[] = $size->id;
        }

        // Create the product
        $product = Product::create([
            'name' => 'Premium Designer Saree Collection',
            'slug' => 'premium-designer-saree-collection-' . Str::random(6),
            'sku' => 'SAR-' . strtoupper(Str::random(8)),
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'price' => 2999,
            'sale_price' => 2499,
            'stock' => 100,
            'stock_status' => 'in_stock',
            'description' => '<p>Experience elegance with our Premium Designer Saree Collection. Crafted with finest quality fabric and intricate designs.</p>
<ul>
<li>Premium quality fabric</li>
<li>Intricate embroidery work</li>
<li>Perfect for weddings and special occasions</li>
<li>Comfortable all-day wear</li>
<li>Easy to drape</li>
</ul>',
            'washing_instructions' => 'Dry clean only|Do not bleach|Iron on low heat|Store in cool dry place',
            'is_featured' => true,
            'is_new' => true,
            'is_bestseller' => true,
            'status' => 'active',
            'approval_status' => 'approved',
            'meta_title' => 'Premium Designer Saree Collection - Buy Online',
            'meta_description' => 'Shop premium designer sarees online. Available in multiple colors and sizes. Free shipping on orders above ₹999.',
            'meta_keywords' => 'saree, designer saree, premium saree, wedding saree, party wear saree',
        ]);

        // Attach colors and sizes to product
        $product->colors()->attach(array_keys($colorIds));
        $product->sizes()->attach($sizeIds);

        // Create variants for each color-size combination
        $variantNumber = 1;
        foreach ($colorIds as $colorId => $images) {
            foreach ($sizeIds as $sizeId) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                    'sku' => $product->sku . '-C' . $colorId . '-S' . $sizeId,
                    'price' => $product->price,
                    'stock' => rand(10, 30),
                    'images' => $images,
                    'is_active' => true,
                ]);
                $variantNumber++;
            }
        }

        $this->command->info('✓ Dummy product created successfully!');
        $this->command->info('  Product: ' . $product->name);
        $this->command->info('  Colors: ' . count($colorIds));
        $this->command->info('  Sizes: ' . count($sizeIds));
        $this->command->info('  Variants: ' . ($variantNumber - 1));
        $this->command->info('  Slug: ' . $product->slug);
    }
}
