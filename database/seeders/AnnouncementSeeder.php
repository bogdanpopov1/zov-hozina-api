<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Category;
use App\Models\Breed;
use App\Models\Photo;
use Illuminate\Support\Facades\Log; // Добавлено для логирования ошибок

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Используем updateOrCreate для пользователя, чтобы избежать ошибок при повторном запуске
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Тестовый Пользователь',
                'password' => bcrypt('password'),
                'phone' => '+7 (999) 123-45-67',
                'location' => 'Казань',
                'is_verified' => true,
            ]
        );

        // 2. Получаем категории и породы с проверкой на их существование
        $dogsCategory = Category::where('slug', 'dogs')->first();
        $catsCategory = Category::where('slug', 'cats')->first();

        if (!$dogsCategory || !$catsCategory) {
            $this->command->error('Категории "dogs" или "cats" не найдены. Пожалуйста, сначала запустите CategorySeeder.');
            Log::error('Seeder Error: Categories "dogs" or "cats" not found.');
            return;
        }

        $breeds = [
            'yorkshire-terrier' => Breed::where('slug', 'yorkshire-terrier')->first(),
            'golden-retriever' => Breed::where('slug', 'golden-retriever')->first(),
            'british-shorthair' => Breed::where('slug', 'british-shorthair')->first(),
        ];

        // 3. Создаем массив данных для объявлений
        $announcementsData = [
            [
                'details' => [
                    'user_id' => $user->user_id,
                    'category_id' => $dogsCategory->category_id,
                    'breed_id' => $breeds['yorkshire-terrier']?->breed_id,
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
                ],
                'photo' => [
                    'filename' => 'dog1.png',
                    'path' => 'https://res.cloudinary.com/dwuv4bp72/image/upload/v1759917077/dog1_omtbw1.png',
                    'mime_type' => 'image/png',
                ]
            ],
            [
                'details' => [
                    'user_id' => $user->user_id,
                    'category_id' => $dogsCategory->category_id,
                    'breed_id' => $breeds['golden-retriever']?->breed_id,
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
                ],
                'photo' => [
                    'filename' => 'dog2.png',
                    'path' => 'https://res.cloudinary.com/dwuv4bp72/image/upload/v1759917077/dog2_recwhq.png',
                    'mime_type' => 'image/png',
                ]
            ],
            [
                'details' => [
                    'user_id' => $user->user_id,
                    'category_id' => $catsCategory->category_id,
                    'breed_id' => $breeds['british-shorthair']?->breed_id,
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
                ],
                'photo' => [
                    'filename' => 'cat1.png',
                    'path' => 'https://res.cloudinary.com/dwuv4bp72/image/upload/v1759917076/cat1_jyqf1s.png',
                    'mime_type' => 'image/png',
                ]
            ],
            [
                'details' => [
                    'user_id' => $user->user_id,
                    'category_id' => $catsCategory->category_id,
                    'breed_id' => $breeds['british-shorthair']?->breed_id,
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
                ],
                'photo' => [
                    'filename' => 'cat2.png',
                    'path' => 'https://res.cloudinary.com/dwuv4bp72/image/upload/v1759917076/cat2_jprpxt.png',
                    'mime_type' => 'image/png',
                ]
            ],
        ];

        // 4. Проходим по массиву и создаем/обновляем объявления и фото
        foreach ($announcementsData as $data) {
            // Проверяем, что порода была найдена
            if (is_null($data['details']['breed_id'])) {
                $this->command->error("Порода для объявления '{$data['details']['pet_name']}' не найдена. Пропускаем.");
                Log::error("Seeder Error: Breed not found for announcement '{$data['details']['pet_name']}'.");
                continue; // Пропускаем итерацию, если порода не найдена
            }

            $announcement = Announcement::updateOrCreate(
                // Уникальный ключ для поиска (имя питомца + адрес)
                [
                    'pet_name' => $data['details']['pet_name'],
                    'location_address' => $data['details']['location_address']
                ],
                // Данные для создания или обновления
                $data['details']
            );

            Photo::updateOrCreate(
                // Уникальный ключ для поиска (ID объявления + флаг основного фото)
                [
                    'announcement_id' => $announcement->announcement_id,
                    'is_primary' => true
                ],
                // Данные для создания или обновления фото
                [
                    'filename' => $data['photo']['filename'],
                    'original_name' => $data['photo']['filename'], // Используем одинаковое имя
                    'path' => $data['photo']['path'],
                    'mime_type' => $data['photo']['mime_type'],
                    'size' => 0, // Можно оставить 0 или убрать, если поле nullable
                    'width' => 800, // Можно оставить или убрать
                    'height' => 600, // Можно оставить или убрать
                    'sort_order' => 1,
                ]
            );
        }
        
        $this->command->info('Announcements and photos seeded successfully!');
    }
}