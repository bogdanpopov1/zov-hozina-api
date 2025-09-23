<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function getUrgent(Request $request)
    {
        $mockData = [
            [
                "id" => 1,
                "location_city" => "Казань",
                "status_text" => "В поисках",
                "pet_breed" => "джек-рассел-терьер",
                "location_address" => "улица Летняя, 1",
                "last_updated" => "10.07.2025",
                "image_url" => "/images/mock/dog1.png"
            ],
            [
                "id" => 2,
                "location_city" => "Казань",
                "status_text" => "В поисках",
                "pet_breed" => "корги",
                "location_address" => "улица Мира, 27",
                "last_updated" => "10.07.2025",
                "image_url" => "/images/mock/dog2.png"
            ],
            [
                "id" => 3,
                "location_city" => "Казань",
                "status_text" => "В поисках",
                "pet_breed" => "шотландец",
                "location_address" => "улица Пушкина, 42",
                "last_updated" => "10.07.2025",
                "image_url" => "/images/mock/cat1.png"
            ],
            [
                "id" => 4,
                "location_city" => "Казань",
                "status_text" => "В поисках",
                "pet_breed" => "британец",
                "location_address" => "улица Восточная, 193",
                "last_updated" => "10.07.2025",
                "image_url" => "/images/mock/cat2.png"
            ],
        ];

        return response()->json($mockData);
    }
}
