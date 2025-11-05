<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Breed;
use Illuminate\Support\Facades\Schema; 

class BreedSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Очищаем таблицу перед заполнением
        Breed::query()->truncate(); // Используем truncate для полной очистки

        // Включаем проверку обратно
        Schema::enableForeignKeyConstraints();

        $breeds = [
            // Собаки (category_id = 1)
            ['category_id' => 1, 'name' => 'Неизвестная', 'slug' => 'unknown'],
            ['category_id' => 1, 'name' => 'Акита-ину', 'slug' => 'akita-inu'],
            ['category_id' => 1, 'name' => 'Алабай (САО)', 'slug' => 'alabay'],
            ['category_id' => 1, 'name' => 'Аляскинский маламут', 'slug' => 'alaskan-malamute'],
            ['category_id' => 1, 'name' => 'Английский бульдог', 'slug' => 'english-bulldog'],
            ['category_id' => 1, 'name' => 'Басенджи', 'slug' => 'basenji'],
            ['category_id' => 1, 'name' => 'Бассет-хаунд', 'slug' => 'basset-hound'],
            ['category_id' => 1, 'name' => 'Бельгийская овчарка', 'slug' => 'belgian-shepherd'],
            ['category_id' => 1, 'name' => 'Бернский зенненхунд', 'slug' => 'bernese-mountain-dog'],
            ['category_id' => 1, 'name' => 'Бигль', 'slug' => 'beagle'],
            ['category_id' => 1, 'name' => 'Бишон фризе', 'slug' => 'bichon-frise'],
            ['category_id' => 1, 'name' => 'Бобтейл', 'slug' => 'bobtail'],
            ['category_id' => 1, 'name' => 'Боксер', 'slug' => 'boxer'],
            ['category_id' => 1, 'name' => 'Бордер-колли', 'slug' => 'border-collie'],
            ['category_id' => 1, 'name' => 'Вельш-корги', 'slug' => 'welsh-corgi'],
            ['category_id' => 1, 'name' => 'Восточноевропейская овчарка', 'slug' => 'east-european-shepherd'],
            ['category_id' => 1, 'name' => 'Далматин', 'slug' => 'dalmatian'],
            ['category_id' => 1, 'name' => 'Джек-рассел-терьер', 'slug' => 'jack-russell-terrier'],
            ['category_id' => 1, 'name' => 'Доберман', 'slug' => 'doberman'],
            ['category_id' => 1, 'name' => 'Золотистый ретривер', 'slug' => 'golden-retriever'],
            ['category_id' => 1, 'name' => 'Йоркширский терьер', 'slug' => 'yorkshire-terrier'],
            ['category_id' => 1, 'name' => 'Кавказская овчарка', 'slug' => 'caucasian-shepherd'],
            ['category_id' => 1, 'name' => 'Кане-корсо', 'slug' => 'cane-corso'],
            ['category_id' => 1, 'name' => 'Кокер-спаниель', 'slug' => 'cocker-spaniel'],
            ['category_id' => 1, 'name' => 'Лабрадор-ретривер', 'slug' => 'labrador-retriever'],
            ['category_id' => 1, 'name' => 'Мопс', 'slug' => 'pug'],
            ['category_id' => 1, 'name' => 'Немецкая овчарка', 'slug' => 'german-shepherd'],
            ['category_id' => 1, 'name' => 'Немецкий дог', 'slug' => 'great-dane'],
            ['category_id' => 1, 'name' => 'Пекинес', 'slug' => 'pekingese'],
            ['category_id' => 1, 'name' => 'Питбуль', 'slug' => 'pit-bull'],
            ['category_id' => 1, 'name' => 'Померанский шпиц', 'slug' => 'pomeranian'],
            ['category_id' => 1, 'name' => 'Пудель', 'slug' => 'poodle'],
            ['category_id' => 1, 'name' => 'Ротвейлер', 'slug' => 'rottweiler'],
            ['category_id' => 1, 'name' => 'Русский той-терьер', 'slug' => 'russian-toy-terrier'],
            ['category_id' => 1, 'name' => 'Самоедская собака', 'slug' => 'samoyed'],
            ['category_id' => 1, 'name' => 'Сибирский хаски', 'slug' => 'siberian-husky'],
            ['category_id' => 1, 'name' => 'Стаффордширский терьер', 'slug' => 'staffordshire-terrier'],
            ['category_id' => 1, 'name' => 'Такса', 'slug' => 'dachshund'],
            ['category_id' => 1, 'name' => 'Французский бульдог', 'slug' => 'french-bulldog'],
            ['category_id' => 1, 'name' => 'Чау-чау', 'slug' => 'chow-chow'],
            ['category_id' => 1, 'name' => 'Чихуахуа', 'slug' => 'chihuahua'],
            ['category_id' => 1, 'name' => 'Шарпей', 'slug' => 'shar-pei'],
            ['category_id' => 1, 'name' => 'Ши-тцу', 'slug' => 'shih-tzu'],
            ['category_id' => 1, 'name' => 'Метис', 'slug' => 'mongrel'],

            // Кошки (category_id = 2)
            ['category_id' => 2, 'name' => 'Неизвестная', 'slug' => 'unknown'],
            ['category_id' => 2, 'name' => 'Абиссинская', 'slug' => 'abyssinian'],
            ['category_id' => 2, 'name' => 'Бенгальская', 'slug' => 'bengal'],
            ['category_id' => 2, 'name' => 'Британская короткошерстная', 'slug' => 'british-shorthair'],
            ['category_id' => 2, 'name' => 'Бурманская', 'slug' => 'burmese'],
            ['category_id' => 2, 'name' => 'Девон-рекс', 'slug' => 'devon-rex'],
            ['category_id' => 2, 'name' => 'Донской сфинкс', 'slug' => 'don-sphynx'],
            ['category_id' => 2, 'name' => 'Канадский сфинкс', 'slug' => 'canadian-sphynx'],
            ['category_id' => 2, 'name' => 'Корниш-рекс', 'slug' => 'cornish-rex'],
            ['category_id' => 2, 'name' => 'Курильский бобтейл', 'slug' => 'kurilian-bobtail'],
            ['category_id' => 2, 'name' => 'Мейн-кун', 'slug' => 'maine-coon'],
            ['category_id' => 2, 'name' => 'Норвежская лесная', 'slug' => 'norwegian-forest-cat'],
            ['category_id' => 2, 'name' => 'Ориентальная', 'slug' => 'oriental'],
            ['category_id' => 2, 'name' => 'Персидская', 'slug' => 'persian'],
            ['category_id' => 2, 'name' => 'Русская голубая', 'slug' => 'russian-blue'],
            ['category_id' => 2, 'name' => 'Сиамская', 'slug' => 'siamese'],
            ['category_id' => 2, 'name' => 'Сибирская', 'slug' => 'siberian'],
            ['category_id' => 2, 'name' => 'Тайская', 'slug' => 'thai'],
            ['category_id' => 2, 'name' => 'Турецкая ангора', 'slug' => 'turkish-angora'],
            ['category_id' => 2, 'name' => 'Шотландская вислоухая (скоттиш-фолд)', 'slug' => 'scottish-fold'],
            ['category_id' => 2, 'name' => 'Шотландская прямоухая (скоттиш-страйт)', 'slug' => 'scottish-straight'],
        ];

        foreach ($breeds as $breed) {
            Breed::create($breed);
        }
    }
}