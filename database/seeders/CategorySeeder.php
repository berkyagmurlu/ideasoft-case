<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
            ],
            [
                'name' => 'Clothing',
            ],
            [
                'name' => 'Books',
            ],
            [
                'name' => 'Home & Garden',
            ],
            [
                'name' => 'Sports & Outdoors',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 