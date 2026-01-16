<?php

namespace Database\Seeders;

use App\Models\BudgetCard;
use Illuminate\Database\Seeder;

class BudgetCardSeeder extends Seeder
{
    public function run(): void
    {
        $cards = [
            [
                'title' => 'SHOP',
                'subtitle' => 'UNDER',
                'price' => 999,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'SHOP',
                'subtitle' => 'UNDER',
                'price' => 1499,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'SHOP',
                'subtitle' => 'UNDER',
                'price' => 1999,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'SHOP',
                'subtitle' => 'UNDER',
                'price' => 2999,
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($cards as $card) {
            BudgetCard::updateOrCreate(
                ['price' => $card['price']],
                $card
            );
        }
    }
}
