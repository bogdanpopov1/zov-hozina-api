<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Announcement;
use App\Models\Breed;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Отключаем проверку внешних ключей
        Schema::disableForeignKeyConstraints();

        // Очищаем таблицы в ПРАВИЛЬНОМ порядке: от дочерних к родительским
        Announcement::query()->truncate();
        Breed::query()->truncate();
        Category::query()->truncate();
        // Photo и SearchLog очистятся каскадно, если настроено,
        // но для надежности можно добавить и их.

        // Включаем проверку обратно
        Schema::enableForeignKeyConstraints();

        // Вызываем сидеры для заполнения таблиц
        $this->call([
            CategorySeeder::class,
            BreedSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}