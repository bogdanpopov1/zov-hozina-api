<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuccessStoryController extends Controller
{
    public function getStories()
    {
        $mockData = [
            [
                "id" => 1,
                "user_avatar_url" => "/images/mock/avatar.png",
                "user_name" => "Алина, хозяйка кота Марсика",
                "rating" => 5,
                "story_text" => "Я не успела даже толком испугаться! Опубликовала объявление о пропаже нашего Марсика, а уже через 15 минут пришло уведомление от бота – волонтер с соседней улицы отправил его фото. Не представляю, как бы я искала его без этой системы. Спасибо!",
                "pet_photo_url" => "/images/mock/story-cat1.png",
                "found_time_text" => "Найден за 20 минут",
                "found_date" => "10.07.2025"
            ],
            [
                "id" => 2,
                "user_avatar_url" => "/images/mock/avatar.png",
                "user_name" => "Игорь, хозяин джек-рассела Ре...",
                "rating" => 5,
                "story_text" => "Наш Рекс сорвался с поводка в парке. Мы бегали два часа безрезультатно. А потом просто открыли карту на сайте и увидели одну-единственную метку «замеченного животного», которую полчаса назад оставил волонтер. Мы поехали туда и Рекс был там!",
                "pet_photo_url" => "/images/mock/story-dog1.png",
                "found_time_text" => "Найден за 2 часа",
                "found_date" => "12.07.2025"
            ],
            [
                "id" => 3,
                "user_avatar_url" => "/images/mock/avatar.png",
                "user_name" => "Екатерина, хозяйка шпица Буси...",
                "rating" => 4,
                "story_text" => "Больше всего понравилось то, что на сайте нет ничего лишнего. Ты в панике, тебе не до сложного интерфейса. А тут все под рукой - вот форма, вот карта, вот кнопка «Помочь». Спасибо за фокус на главном!",
                "pet_photo_url" => "/images/mock/story-dog2.png",
                "found_time_text" => "Найдена за 1 день",
                "found_date" => "13.07.2025"
            ],
            [
                "id" => 4,
                "user_avatar_url" => "/images/mock/avatar.png",
                "user_name" => "Михаил, хозяин кошки Мурки",
                "rating" => 5,
                "story_text" => "Респект за макеты для печати! В нашем районе много пожилых людей без смартфонов. Расклеили объявления, и нам позвонила пенсионерка, которая видела нашу кошку в своем подвале.",
                "pet_photo_url" => "/images/mock/story-cat2.png",
                "found_time_text" => "Найдена за 2 дня",
                "found_date" => "15.07.2025"
            ]
        ];

        return response()->json($mockData);
    }
}