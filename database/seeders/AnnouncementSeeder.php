<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Category;
use App\Models\Breed;
use App\Models\Photo;
use Illuminate\Support\Facades\Log;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        // Используем updateOrCreate, чтобы избежать дублирования при повторном запуске
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Тестовый Пользователь',
                'password' => bcrypt('password'),
            ]
        );

        $dogsCategory = Category::where('slug', 'dogs')->first();
        $catsCategory = Category::where('slug', 'cats')->first();

        if (!$dogsCategory || !$catsCategory) {
            $this->command->error('Категории "dogs" или "cats" не найдены. Запустите CategorySeeder.');
            return;
        }

        $breeds = [
            'yorkshire-terrier' => Breed::where('slug', 'yorkshire-terrier')->first(),
            'golden-retriever' => Breed::where('slug', 'golden-retriever')->first(),
            'british-shorthair' => Breed::where('slug', 'british-shorthair')->first(),
        ];

        // Массив данных с добавленными координатами Казани
        $announcementsData = [
            [
                'details' => [
                    'user_id' => $user->user_id, 'category_id' => $dogsCategory->category_id, 'breed_id' => $breeds['yorkshire-terrier']?->breed_id,
                    'pet_name' => 'Рекс', 'pet_type' => 'собака', 'pet_breed' => 'джек-рассел-терьер',
                    'description' => 'Коричневая мордочка, очень игривый, всегда бежит знакомиться.',
                    'location_address' => 'Казань, ул. Летняя, 1',
                    'latitude' => 55.8300, 'longitude' => 49.0800, // Координаты
                    'status' => 'active', 'age' => 3, 'gender' => 'male', 'size' => 'small', 'color' => 'белый с коричневыми пятнами',
                    'is_featured' => true,
                ],
                'photo' => [ 'path' => 'https://res.cloudinary.com/dwuv4bp72/image/upload/v1759917077/dog1_omtbw1.png' ]
            ],
            [
                'details' => [
                    'user_id' => $user->user_id, 'category_id' => $dogsCategory->category_id, 'breed_id' => $breeds['golden-retriever']?->breed_id,
                    'pet_name' => 'Снежок', 'pet_type' => 'собака', 'pet_breed' => 'корги',
                    'description' => 'Белый и пушистый, очень дружелюбный.',
                    'location_address' => 'Казань, ул. Мира, 27',
                    'latitude' => 55.7500, 'longitude' => 49.1500, // Координаты
                    'status' => 'active', 'age' => 2, 'gender' => 'male', 'size' => 'small', 'color' => 'рыже-белый',
                    'is_featured' => true,
                ],
                'photo' => [ 'path' => 'https://res.cloudinary.com/dwuv4bp72/image/upload/v1759917077/dog2_recwhq.png' ]
            ],
            [
                'details' => [
                    'user_id' => $user->user_id, 'category_id' => $catsCategory->category_id, 'breed_id' => $breeds['british-shorthair']?->breed_id,
                    'pet_name' => 'Мурка', 'pet_type' => 'кошка', 'pet_breed' => 'шотландская вислоухая',
                    'description' => 'Серая полосатая, очень ласковая.',
                    'location_address' => 'Казань, ул. Пушкина, 42',
                    'latitude' => 55.7961, 'longitude' => 49.1064, // Координаты
                    'status' => 'active', 'age' => 4, 'gender' => 'female', 'size' => 'medium', 'color' => 'серая полосатая',
                    'is_featured' => true,
                ],
                'photo' => [ 'path' => 'https://res.cloudinary.com/dwuv4bp72/image/upload/v1759917076/cat1_jyqf1s.png' ]
            ],
            [
                'details' => [
                    'user_id' => $user->user_id, 'category_id' => $catsCategory->category_id, 'breed_id' => $breeds['british-shorthair']?->breed_id,
                    'pet_name' => 'Барсик', 'pet_type' => 'кошка', 'pet_breed' => 'британская короткошерстная',
                    'description' => 'Серая с желтыми глазами, на ошейнике золотой колокольчик.',
                    'location_address' => 'Казань, ул. Восточная, 193',
                    'latitude' => 55.8200, 'longitude' => 49.1200, // Координаты
                    'status' => 'active', 'age' => 5, 'gender' => 'male', 'size' => 'medium', 'color' => 'серая',
                    'is_featured' => true,
                ],
                'photo' => [ 'path' => 'https://res.cloudinary.com/dwuv4bp72/image/upload/v1759917076/cat2_jprpxt.png' ]
            ],
        ];

        foreach ($announcementsData as $data) {
            if (is_null($data['details']['breed_id'])) {
                Log::warning("Порода для '{$data['details']['pet_name']}' не найдена. Пропускаем.");
                continue;
            }

            $announcement = Announcement::updateOrCreate(
                ['pet_name' => $data['details']['pet_name'], 'location_address' => $data['details']['location_address']],
                $data['details']
            );

            Photo::updateOrCreate(
                ['announcement_id' => $announcement->announcement_id, 'is_primary' => true],
                [
                    'filename' => basename($data['photo']['path']),
                    'original_name' => basename($data['photo']['path']),
                    'path' => $data['photo']['path'],
                    'mime_type' => 'image/png', 'size' => 0, 'sort_order' => 1,
                ]
            );
        }

        $this->command->info('4 основных объявления успешно созданы/обновлены.');
    }
}