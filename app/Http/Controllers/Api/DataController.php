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
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
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

        $breeds = Breed::where('category_id', $categoryId)
            ->where('name', 'LIKE', '%' . $query . '%')
            ->orderByRaw("CASE WHEN name LIKE ? THEN 1 ELSE 2 END", [$query . '%'])
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get();

        return response()->json($breeds);
    }
}