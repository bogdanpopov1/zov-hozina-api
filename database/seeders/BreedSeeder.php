<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Breed;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $breeds = [
            // Собаки
            [
                'category_id' => 1,
                'name' => 'Лабрадор',
                'slug' => 'labrador',
                'description' => 'Дружелюбная и активная порода собак',
                'size' => 'large',
                'temperament' => 'friendly, active, intelligent',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Немецкая овчарка',
                'slug' => 'german-shepherd',
                'description' => 'Умная и преданная порода собак',
                'size' => 'large',
                'temperament' => 'intelligent, loyal, protective',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Йоркширский терьер',
                'slug' => 'yorkshire-terrier',
                'description' => 'Маленькая и энергичная порода собак',
                'size' => 'small',
                'temperament' => 'energetic, brave, affectionate',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Золотистый ретривер',
                'slug' => 'golden-retriever',
                'description' => 'Добродушная и терпеливая порода собак',
                'size' => 'large',
                'temperament' => 'friendly, patient, intelligent',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Бульдог',
                'slug' => 'bulldog',
                'description' => 'Спокойная и дружелюбная порода собак',
                'size' => 'medium',
                'temperament' => 'calm, friendly, gentle',
                'is_active' => true,
            ],
            
            // Кошки
            [
                'category_id' => 2,
                'name' => 'Британская короткошерстная',
                'slug' => 'british-shorthair',
                'description' => 'Спокойная и независимая порода кошек',
                'size' => 'medium',
                'temperament' => 'calm, independent, gentle',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Персидская',
                'slug' => 'persian',
                'description' => 'Длинношерстная и спокойная порода кошек',
                'size' => 'medium',
                'temperament' => 'calm, gentle, quiet',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Мейн-кун',
                'slug' => 'maine-coon',
                'description' => 'Крупная и дружелюбная порода кошек',
                'size' => 'large',
                'temperament' => 'friendly, intelligent, playful',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Сиамская',
                'slug' => 'siamese',
                'description' => 'Активная и общительная порода кошек',
                'size' => 'medium',
                'temperament' => 'active, social, vocal',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Русская голубая',
                'slug' => 'russian-blue',
                'description' => 'Элегантная и спокойная порода кошек',
                'size' => 'medium',
                'temperament' => 'calm, gentle, reserved',
                'is_active' => true,
            ],
            
            // Птицы
            [
                'category_id' => 3,
                'name' => 'Волнистый попугай',
                'slug' => 'budgerigar',
                'description' => 'Маленькая и общительная птица',
                'size' => 'small',
                'temperament' => 'social, active, intelligent',
                'is_active' => true,
            ],
            [
                'category_id' => 3,
                'name' => 'Канарейка',
                'slug' => 'canary',
                'description' => 'Певчая птица с красивым голосом',
                'size' => 'small',
                'temperament' => 'musical, active, social',
                'is_active' => true,
            ],
            [
                'category_id' => 3,
                'name' => 'Ара',
                'slug' => 'macaw',
                'description' => 'Крупная и яркая попугай',
                'size' => 'large',
                'temperament' => 'intelligent, social, loud',
                'is_active' => true,
            ],
            
            // Рыбы
            [
                'category_id' => 4,
                'name' => 'Золотая рыбка',
                'slug' => 'goldfish',
                'description' => 'Популярная аквариумная рыбка',
                'size' => 'small',
                'temperament' => 'peaceful, active',
                'is_active' => true,
            ],
            [
                'category_id' => 4,
                'name' => 'Гуппи',
                'slug' => 'guppy',
                'description' => 'Яркая и неприхотливая рыбка',
                'size' => 'small',
                'temperament' => 'peaceful, active',
                'is_active' => true,
            ],
            
            // Грызуны
            [
                'category_id' => 5,
                'name' => 'Хомяк',
                'slug' => 'hamster',
                'description' => 'Маленький и активный грызун',
                'size' => 'small',
                'temperament' => 'active, nocturnal, independent',
                'is_active' => true,
            ],
            [
                'category_id' => 5,
                'name' => 'Морская свинка',
                'slug' => 'guinea-pig',
                'description' => 'Дружелюбный и общительный грызун',
                'size' => 'small',
                'temperament' => 'social, gentle, active',
                'is_active' => true,
            ],
            [
                'category_id' => 5,
                'name' => 'Кролик',
                'slug' => 'rabbit',
                'description' => 'Мягкий и дружелюбный грызун',
                'size' => 'medium',
                'temperament' => 'gentle, social, active',
                'is_active' => true,
            ],
            
            // Рептилии
            [
                'category_id' => 6,
                'name' => 'Черепаха',
                'slug' => 'turtle',
                'description' => 'Спокойная и долгоживущая рептилия',
                'size' => 'medium',
                'temperament' => 'calm, slow, independent',
                'is_active' => true,
            ],
            [
                'category_id' => 6,
                'name' => 'Игуана',
                'slug' => 'iguana',
                'description' => 'Крупная и экзотическая рептилия',
                'size' => 'large',
                'temperament' => 'calm, independent, territorial',
                'is_active' => true,
            ],
        ];

        foreach ($breeds as $breed) {
             // Используем updateOrCreate вместо create
            Breed::updateOrCreate(
                ['slug' => $breed['slug']], // Условие для поиска
                $breed // Данные для создания или обновления
            );
        }
    }
}
