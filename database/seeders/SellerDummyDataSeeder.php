<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Fabric;
use App\Models\Brand;
use App\Models\Size;
use App\Models\Collection;
use App\Models\Tag;

class SellerDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Find user by email
        $user = User::where('email', '732kajaldas@gmail.com')->first();
        
        if (!$user) {
            $this->command->error('User with email 732kajaldas@gmail.com not found!');
            return;
        }
        
        $seller = Seller::where('user_id', $user->id)->first();
        
        if (!$seller) {
            $this->command->error('Seller not found for this user!');
            return;
        }
        
        $sellerId = $seller->id;
        $this->command->info("Adding dummy data for seller ID: {$sellerId}");
        
        // Add Categories
        $this->addCategories($sellerId);
        
        // Add Colors
        $this->addColors($sellerId);
        
        // Add Fabrics
        $this->addFabrics($sellerId);
        
        // Add Brands
        $this->addBrands($sellerId);
        
        // Add Sizes
        $this->addSizes($sellerId);
        
        // Add Collections
        $this->addCollections($sellerId);
        
        // Add Tags
        $this->addTags($sellerId);
        
        $this->command->info('All dummy data added successfully!');
    }
    
    private function addCategories($sellerId): void
    {
        $categories = [
            ['name' => 'Sarees', 'slug' => 'sarees', 'description' => 'Beautiful Indian sarees'],
            ['name' => 'Kurtis', 'slug' => 'kurtis', 'description' => 'Stylish kurtis for women'],
            ['name' => 'Salwar Suits', 'slug' => 'salwar-suits', 'description' => 'Traditional salwar suits'],
            ['name' => 'Lehengas', 'slug' => 'lehengas', 'description' => 'Bridal and party wear lehengas'],
            ['name' => 'Dupattas', 'slug' => 'dupattas', 'description' => 'Designer dupattas'],
        ];
        
        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name'], 'seller_id' => $sellerId],
                array_merge($category, ['seller_id' => $sellerId, 'is_active' => true])
            );
        }
        
        $this->command->info('Categories added: ' . count($categories));
    }
    
    private function addColors($sellerId): void
    {
        $colors = [
            ['name' => 'Red', 'hex_code' => '#FF0000'],
            ['name' => 'Blue', 'hex_code' => '#0000FF'],
            ['name' => 'Green', 'hex_code' => '#008000'],
            ['name' => 'Black', 'hex_code' => '#000000'],
            ['name' => 'White', 'hex_code' => '#FFFFFF'],
            ['name' => 'Yellow', 'hex_code' => '#FFFF00'],
            ['name' => 'Pink', 'hex_code' => '#FFC0CB'],
            ['name' => 'Purple', 'hex_code' => '#800080'],
            ['name' => 'Orange', 'hex_code' => '#FFA500'],
            ['name' => 'Gold', 'hex_code' => '#FFD700'],
        ];
        
        foreach ($colors as $color) {
            Color::firstOrCreate(
                ['name' => $color['name'], 'seller_id' => $sellerId],
                array_merge($color, ['seller_id' => $sellerId, 'is_active' => true, 'sort_order' => 0])
            );
        }
        
        $this->command->info('Colors added: ' . count($colors));
    }
    
    private function addFabrics($sellerId): void
    {
        $fabrics = [
            ['name' => 'Cotton', 'slug' => 'cotton', 'description' => 'Pure cotton fabric'],
            ['name' => 'Silk', 'slug' => 'silk', 'description' => 'Premium silk fabric'],
            ['name' => 'Georgette', 'slug' => 'georgette', 'description' => 'Light georgette fabric'],
            ['name' => 'Chiffon', 'slug' => 'chiffon', 'description' => 'Soft chiffon fabric'],
            ['name' => 'Banarasi', 'slug' => 'banarasi', 'description' => 'Traditional Banarasi fabric'],
            ['name' => 'Linen', 'slug' => 'linen', 'description' => 'Natural linen fabric'],
        ];
        
        foreach ($fabrics as $fabric) {
            Fabric::firstOrCreate(
                ['name' => $fabric['name'], 'seller_id' => $sellerId],
                array_merge($fabric, ['seller_id' => $sellerId, 'is_active' => true, 'sort_order' => 0])
            );
        }
        
        $this->command->info('Fabrics added: ' . count($fabrics));
    }
    
    private function addBrands($sellerId): void
    {
        $brands = [
            ['name' => 'FabIndia', 'description' => 'Traditional Indian wear'],
            ['name' => 'Biba', 'description' => 'Ethnic fashion brand'],
            ['name' => 'W for Woman', 'description' => 'Contemporary ethnic wear'],
            ['name' => 'Aurelia', 'description' => 'Fusion wear brand'],
            ['name' => 'Global Desi', 'description' => 'Boho-chic fashion'],
        ];
        
        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['name' => $brand['name'], 'seller_id' => $sellerId],
                array_merge($brand, ['seller_id' => $sellerId, 'is_active' => true, 'sort_order' => 0])
            );
        }
        
        $this->command->info('Brands added: ' . count($brands));
    }
    
    private function addSizes($sellerId): void
    {
        $sizes = [
            ['name' => 'Small', 'abbreviation' => 'S'],
            ['name' => 'Medium', 'abbreviation' => 'M'],
            ['name' => 'Large', 'abbreviation' => 'L'],
            ['name' => 'Extra Large', 'abbreviation' => 'XL'],
            ['name' => 'Double XL', 'abbreviation' => 'XXL'],
            ['name' => 'Free Size', 'abbreviation' => 'FS'],
        ];
        
        foreach ($sizes as $size) {
            Size::firstOrCreate(
                ['name' => $size['name'], 'seller_id' => $sellerId],
                array_merge($size, ['seller_id' => $sellerId, 'is_active' => true, 'sort_order' => 0])
            );
        }
        
        $this->command->info('Sizes added: ' . count($sizes));
    }
    
    private function addCollections($sellerId): void
    {
        $collections = [
            ['name' => 'Summer Collection 2026', 'slug' => 'summer-collection-2026', 'description' => 'Latest summer trends'],
            ['name' => 'Wedding Special', 'slug' => 'wedding-special', 'description' => 'Bridal and wedding wear'],
            ['name' => 'Festive Collection', 'slug' => 'festive-collection', 'description' => 'Festival special outfits'],
            ['name' => 'Office Wear', 'slug' => 'office-wear', 'description' => 'Professional ethnic wear'],
            ['name' => 'Party Wear', 'slug' => 'party-wear', 'description' => 'Stylish party outfits'],
        ];
        
        foreach ($collections as $collection) {
            Collection::firstOrCreate(
                ['name' => $collection['name'], 'seller_id' => $sellerId],
                array_merge($collection, ['seller_id' => $sellerId, 'is_active' => true, 'sort_order' => 0])
            );
        }
        
        $this->command->info('Collections added: ' . count($collections));
    }
    
    private function addTags($sellerId): void
    {
        $tags = [
            ['name' => 'New Arrival', 'slug' => 'new-arrival'],
            ['name' => 'Best Seller', 'slug' => 'best-seller'],
            ['name' => 'Trending', 'slug' => 'trending'],
            ['name' => 'Sale', 'slug' => 'sale'],
            ['name' => 'Handcrafted', 'slug' => 'handcrafted'],
            ['name' => 'Premium', 'slug' => 'premium'],
            ['name' => 'Discount', 'slug' => 'discount'],
        ];
        
        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['name' => $tag['name'], 'seller_id' => $sellerId],
                array_merge($tag, ['seller_id' => $sellerId, 'is_active' => true])
            );
        }
        
        $this->command->info('Tags added: ' . count($tags));
    }
}