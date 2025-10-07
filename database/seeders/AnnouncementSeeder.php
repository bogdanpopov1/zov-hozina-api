<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Category;
use App\Models\Breed;
use App\Models\Photo;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем тестового пользователя если его нет
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Тестовый Пользователь',
                'password' => bcrypt('password'),
                'phone' => '+7 (999) 123-45-67',
                'location' => 'Казань',
                'is_verified' => true,
            ]
        );

        // Получаем категории и породы
        $dogsCategory = Category::where('slug', 'dogs')->first();
        $catsCategory = Category::where('slug', 'cats')->first();
        
        $jackRussellBreed = Breed::where('slug', 'yorkshire-terrier')->first(); // Используем как джек-рассел
        $corgiBreed = Breed::where('slug', 'golden-retriever')->first(); // Используем как корги
        $scottishBreed = Breed::where('slug', 'british-shorthair')->first(); // Используем как шотландец
        $britishBreed = Breed::where('slug', 'british-shorthair')->first(); // Британская

        // 1. Джек-рассел-терьер
        $announcement1 = Announcement::create([
            'user_id' => $user->user_id,
            'category_id' => $dogsCategory->category_id,
            'breed_id' => $jackRussellBreed->breed_id,
            'pet_name' => 'Рекс',
            'pet_type' => 'собака',
            'pet_breed' => 'джек-рассел-терьер',
            'description' => 'Коричневая мордочка, очень игривый, всегда бежит знакомиться. Пропал 10.07.2025',
            'location_address' => 'улица Летняя, 1, Казань',
            'status' => 'active',
            'age' => 3,
            'gender' => 'male',
            'size' => 'small',
            'color' => 'белый с коричневыми пятнами',
            'is_vaccinated' => true,
            'is_sterilized' => false,
            'has_pedigree' => true,
            'price_type' => 'free',
            'additional_info' => 'Очень дружелюбный, откликается на кличку Рекс',
            'is_featured' => true,
        ]);

        // Фотография для джек-рассела
        Photo::create([
            'announcement_id' => $announcement1->announcement_id,
            'filename' => 'jack-russell-terrier.svg',
            'original_name' => 'jack-russell-terrier.svg',
            'path' => 'announcements/jack-russell-terrier.svg',
            'mime_type' => 'image/svg+xml',
            'size' => 2457,
            'width' => 800,
            'height' => 600,
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        // 2. Корги
        $announcement2 = Announcement::create([
            'user_id' => $user->user_id,
            'category_id' => $dogsCategory->category_id,
            'breed_id' => $corgiBreed->breed_id,
            'pet_name' => 'Снежок',
            'pet_type' => 'собака',
            'pet_breed' => 'корги',
            'description' => 'Белый и пушистый, очень дружелюбный. Пропал 10.07.2025',
            'location_address' => 'улица Мира, 27, Казань',
            'status' => 'active',
            'age' => 2,
            'gender' => 'male',
            'size' => 'small',
            'color' => 'рыже-белый',
            'is_vaccinated' => true,
            'is_sterilized' => false,
            'has_pedigree' => true,
            'price_type' => 'free',
            'additional_info' => 'Любит играть, очень активный',
            'is_featured' => true,
        ]);

        // Фотография для корги
        Photo::create([
            'announcement_id' => $announcement2->announcement_id,
            'filename' => 'corgi.svg',
            'original_name' => 'corgi.svg',
            'path' => 'announcements/corgi.svg',
            'mime_type' => 'image/svg+xml',
            'size' => 3120,
            'width' => 800,
            'height' => 600,
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        // 3. Шотландская вислоухая кошка
        $announcement3 = Announcement::create([
            'user_id' => $user->user_id,
            'category_id' => $catsCategory->category_id,
            'breed_id' => $scottishBreed->breed_id,
            'pet_name' => 'Мурка',
            'pet_type' => 'кошка',
            'pet_breed' => 'шотландская вислоухая',
            'description' => 'Серая полосатая, очень ласковая. Пропала 10.07.2025',
            'location_address' => 'улица Пушкина, 42, Казань',
            'status' => 'active',
            'age' => 4,
            'gender' => 'female',
            'size' => 'medium',
            'color' => 'серая полосатая',
            'is_vaccinated' => true,
            'is_sterilized' => true,
            'has_pedigree' => false,
            'price_type' => 'free',
            'additional_info' => 'Откликается на кличку Мурка, любит спать',
            'is_featured' => true,
        ]);

        // Фотография для шотландца
        Photo::create([
            'announcement_id' => $announcement3->announcement_id,
            'filename' => 'scottish-fold.svg',
            'original_name' => 'scottish-fold.svg',
            'path' => 'announcements/scottish-fold.svg',
            'mime_type' => 'image/svg+xml',
            'size' => 1980,
            'width' => 800,
            'height' => 600,
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        // 4. Британская короткошерстная кошка
        $announcement4 = Announcement::create([
            'user_id' => $user->user_id,
            'category_id' => $catsCategory->category_id,
            'breed_id' => $britishBreed->breed_id,
            'pet_name' => 'Барсик',
            'pet_type' => 'кошка',
            'pet_breed' => 'британская короткошерстная',
            'description' => 'Серая с желтыми глазами, на ошейнике золотой колокольчик. Пропал 10.07.2025',
            'location_address' => 'улица Восточная, 193, Казань',
            'status' => 'active',
            'age' => 5,
            'gender' => 'male',
            'size' => 'medium',
            'color' => 'серая',
            'is_vaccinated' => true,
            'is_sterilized' => true,
            'has_pedigree' => true,
            'price_type' => 'free',
            'additional_info' => 'На ошейнике золотой колокольчик с именем',
            'is_featured' => true,
        ]);

        // Фотография для британца
        Photo::create([
            'announcement_id' => $announcement4->announcement_id,
            'filename' => 'british-shorthair.svg',
            'original_name' => 'british-shorthair.svg',
            'path' => 'announcements/british-shorthair.svg',
            'mime_type' => 'image/svg+xml',
            'size' => 2670,
            'width' => 800,
            'height' => 600,
            'is_primary' => true,
            'sort_order' => 1,
        ]);
    }
}