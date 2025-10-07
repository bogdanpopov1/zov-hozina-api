<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User; // Убедитесь, что у вас есть модель User

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        // Предполагаем, что пользователь с ID=1 уже существует
        // Если нет, нужно будет сначала создать его
        if (!User::find(1)) {
             User::factory()->create(['user_id' => 1, 'email' => 'test@example.com']);
        }

        Announcement::create([
            'user_id' => 1,
            'pet_name' => 'Рекс',
            'pet_type' => 'собака',
            'pet_breed' => 'джек-рассел-терьер',
            'description' => 'Коричневая мордочка, очень игривый, всегда бежит знакомиться.',
            'location_address' => 'улица Летняя, 1',
            'status' => 'active',
        ]);

        Announcement::create([
            'user_id' => 1,
            'pet_name' => 'Снежок',
            'pet_type' => 'собака',
            'pet_breed' => 'корги',
            'description' => 'Белый и пушистый, очень дружелюбный.',
            'location_address' => 'улица Мира, 27',
            'status' => 'active',
        ]);
        
        // ... добавьте еще 2-3 объявления по аналогии
    }
}