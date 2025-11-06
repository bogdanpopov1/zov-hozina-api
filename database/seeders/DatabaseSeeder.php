<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Логика очистки полностью удалена и перенесена в команду migrate:fresh
        $this->call([
            CategorySeeder::class,
            BreedSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}