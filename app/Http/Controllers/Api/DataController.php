<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Breed;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Получить список категорий животных.
     */
    public function getCategories()
    {
        $categories = Category::whereIn('slug', ['dogs', 'cats', 'birds', 'rodents', 'reptiles', 'fish'])
            ->orderBy('sort_order')
            ->get();
        return response()->json($categories);
    }

    /**
     * Поиск пород для автокомплита.
     */
    public function searchBreeds(Request $request)
    {
        $validatedData = $request->validate([
            'query' => 'required|string|min:2',
            'category_id' => 'required|integer|exists:categories,category_id'
        ]);

        $query = $validatedData['query'];
        $categoryId = $validatedData['category_id'];

        // --- КЛЮЧЕВОЕ ИЗМЕНЕНИЕ И УЛУЧШЕНИЕ ---
        $breeds = Breed::where('category_id', $categoryId)
            // 1. Ищем по вхождению в любую часть строки
            ->where('name', 'LIKE', '%' . $query . '%')
            // 2. Сортируем по релевантности: сначала те, что начинаются с запроса
            ->orderByRaw("CASE WHEN name LIKE ? THEN 1 ELSE 2 END", [$query . '%'])
            // 3. Вторичная сортировка по алфавиту
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get();

        return response()->json($breeds);
    }
}