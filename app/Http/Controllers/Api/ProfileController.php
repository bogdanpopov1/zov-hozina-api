<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Обновление информации профиля аутентифицированного пользователя.
     */
    public function update(Request $request)
    {
        // Получаем текущего пользователя
        $user = Auth::user();

        // Валидация входящих данных
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            // Email обновлять сложнее, т.к. требует подтверждения. Пока оставим так.
        ]);

        // Обновляем данные пользователя
        $user->update($validatedData);

        // Возвращаем обновленную модель пользователя
        return response()->json($user);
    }
}