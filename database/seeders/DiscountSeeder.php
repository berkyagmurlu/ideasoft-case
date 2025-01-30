<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $discounts = [
            // Total Amount Discount
            [
                'name' => 'Big Purchase Discount',
                'type' => 'total_amount',
                'min_amount' => 1000,
                'discount_rate' => 10,
                'is_active' => true,
            ],

            // Category Quantity Discounts
            [
                'name' => 'Buy 2 Get 1 Free on Books',
                'type' => 'category_quantity',
                'category_id' => 3, // Books
                'min_quantity' => 3,
                'free_items' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Buy 3 Get 1 Free on Clothing',
                'type' => 'category_quantity',
                'category_id' => 2, // Clothing
                'min_quantity' => 4,
                'free_items' => 1,
                'is_active' => true,
            ],

            // Category Multiple Discounts
            [
                'name' => '15% Off Electronics',
                'type' => 'category_multiple',
                'category_id' => 1, // Electronics
                'discount_rate' => 15,
                'is_active' => true,
            ],
            [
                'name' => '20% Off Sports Equipment',
                'type' => 'category_multiple',
                'category_id' => 5, // Sports & Outdoors
                'discount_rate' => 20,
                'is_active' => true,
            ],

            // VIP Customer Discount
            [
                'name' => 'VIP Customer Discount',
                'type' => 'user_revenue',
                'user_revenue_min' => 10000,
                'discount_rate' => 25,
                'is_active' => true,
            ],

            // Loyal Customer Discount
            [
                'name' => 'Loyal Customer Discount',
                'type' => 'membership_duration',
                'membership_months_min' => 12,
                'discount_rate' => 15,
                'is_active' => true,
            ],
        ];

        foreach ($discounts as $discount) {
            Discount::create($discount);
        }
    }
} 