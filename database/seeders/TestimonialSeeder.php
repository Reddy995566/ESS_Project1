<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Megha S.',
                'review' => 'The saree I ordered was even more stunning in person — rich colors, fine zari work, and premium feel. Definitely shopping again!',
                'rating' => 5,
                'sort_order' => 1,
            ],
            [
                'name' => 'Ritu P.',
                'review' => 'Received so many compliments at my cousin\'s wedding. Varyam sarees are showstoppers — luxurious yet affordable!',
                'rating' => 5,
                'sort_order' => 2,
            ],
            [
                'name' => 'Kavita M.',
                'review' => 'Superb quality and fast delivery! The blouse piece fabric was just as beautiful. Perfect for festive gifting too.',
                'rating' => 5,
                'sort_order' => 3,
            ],
            [
                'name' => 'Neha A.',
                'review' => 'I bought two satin sarees from Varyam — Lightweight yet grand. My new favorite brand.',
                'rating' => 5,
                'sort_order' => 4,
            ],
            [
                'name' => 'Sneha R.',
                'review' => 'Loved the packaging, loved the saree, and loved the compliments I got! Truly lovely.',
                'rating' => 5,
                'sort_order' => 5,
            ],
            [
                'name' => 'Anjali K.',
                'review' => 'The texture is so soft and easy to drape. Perfect for long events. Highly recommended!',
                'rating' => 5,
                'sort_order' => 6,
            ],
            [
                'name' => 'Priya M.',
                'review' => 'Elegant designs and authentic fabric. My mother absolutely loved the Kanjivaram saree.',
                'rating' => 5,
                'sort_order' => 7,
            ],
            [
                'name' => 'Divya S.',
                'review' => 'Fastest delivery I\'ve experienced for ethnic wear. The quality exceeded my expectations.',
                'rating' => 5,
                'sort_order' => 8,
            ],
            [
                'name' => 'Tanvi B.',
                'review' => 'Beautiful craftsmanship. The zari detailing is intricate and looks very premium.',
                'rating' => 5,
                'sort_order' => 9,
            ],
            [
                'name' => 'Suman L.',
                'review' => 'A wide range of collections to choose from. Found distinct colors and patterns here.',
                'rating' => 5,
                'sort_order' => 10,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create([
                'name' => $testimonial['name'],
                'review' => $testimonial['review'],
                'rating' => $testimonial['rating'],
                'sort_order' => $testimonial['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}
