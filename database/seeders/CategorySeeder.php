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
                'name' => 'Собаки',
                'slug' => 'dogs',
                'description' => 'Объявления о продаже и отдаче собак',
                'icon' => 'dog',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Кошки',
                'slug' => 'cats',
                'description' => 'Объявления о продаже и отдаче кошек',
                'icon' => 'cat',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Птицы',
                'slug' => 'birds',
                'description' => 'Объявления о продаже и отдаче птиц',
                'icon' => 'bird',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Рыбы',
                'slug' => 'fish',
                'description' => 'Объявления о продаже и отдаче рыб',
                'icon' => 'fish',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Грызуны',
                'slug' => 'rodents',
                'description' => 'Объявления о продаже и отдаче грызунов',
                'icon' => 'mouse',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Рептилии',
                'slug' => 'reptiles',
                'description' => 'Объявления о продаже и отдаче рептилий',
                'icon' => 'snake',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            // Используем updateOrCreate вместо create
            Category::updateOrCreate(
                ['slug' => $category['slug']], // Условие для поиска
                $category // Данные для создания или обновления
            );
        }
    }
}
