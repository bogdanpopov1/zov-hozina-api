<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Собака',
                'slug' => 'dog',
                'description' => 'Объявления о продаже и отдаче собак',
                'icon' => 'dog',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Кошка',
                'slug' => 'cat',
                'description' => 'Объявления о продаже и отдаче кошек',
                'icon' => 'cat',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Птица',
                'slug' => 'bird',
                'description' => 'Объявления о продаже и отдаче птиц',
                'icon' => 'bird',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}