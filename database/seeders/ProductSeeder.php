<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Electronics
            [
                'category_id' => 1,
                'name' => 'Smartphone',
                'description' => 'Latest smartphone model with advanced features',
                'price' => 999.99,
                'stock' => 50,
            ],
            [
                'category_id' => 1,
                'name' => 'Laptop',
                'description' => 'High-performance laptop for work and gaming',
                'price' => 1499.99,
                'stock' => 30,
            ],
            [
                'category_id' => 1,
                'name' => 'Wireless Earbuds',
                'description' => 'Premium wireless earbuds with noise cancellation',
                'price' => 199.99,
                'stock' => 100,
            ],

            // Clothing
            [
                'category_id' => 2,
                'name' => 'T-Shirt',
                'description' => 'Comfortable cotton t-shirt',
                'price' => 29.99,
                'stock' => 200,
            ],
            [
                'category_id' => 2,
                'name' => 'Jeans',
                'description' => 'Classic blue jeans',
                'price' => 79.99,
                'stock' => 150,
            ],
            [
                'category_id' => 2,
                'name' => 'Sneakers',
                'description' => 'Stylish and comfortable sneakers',
                'price' => 89.99,
                'stock' => 100,
            ],

            // Books
            [
                'category_id' => 3,
                'name' => 'Novel',
                'description' => 'Bestselling fiction novel',
                'price' => 19.99,
                'stock' => 300,
            ],
            [
                'category_id' => 3,
                'name' => 'Cookbook',
                'description' => 'Collection of gourmet recipes',
                'price' => 34.99,
                'stock' => 150,
            ],
            [
                'category_id' => 3,
                'name' => 'Self-Help Book',
                'description' => 'Guide to personal development',
                'price' => 24.99,
                'stock' => 200,
            ],

            // Home & Garden
            [
                'category_id' => 4,
                'name' => 'Plant Pot',
                'description' => 'Decorative ceramic plant pot',
                'price' => 39.99,
                'stock' => 100,
            ],
            [
                'category_id' => 4,
                'name' => 'Table Lamp',
                'description' => 'Modern design table lamp',
                'price' => 59.99,
                'stock' => 80,
            ],
            [
                'category_id' => 4,
                'name' => 'Throw Pillow',
                'description' => 'Soft decorative throw pillow',
                'price' => 29.99,
                'stock' => 150,
            ],

            // Sports & Outdoors
            [
                'category_id' => 5,
                'name' => 'Yoga Mat',
                'description' => 'Non-slip exercise yoga mat',
                'price' => 49.99,
                'stock' => 120,
            ],
            [
                'category_id' => 5,
                'name' => 'Tennis Racket',
                'description' => 'Professional tennis racket',
                'price' => 149.99,
                'stock' => 50,
            ],
            [
                'category_id' => 5,
                'name' => 'Camping Tent',
                'description' => '4-person waterproof camping tent',
                'price' => 199.99,
                'stock' => 40,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 