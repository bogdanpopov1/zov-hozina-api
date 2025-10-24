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
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update($validatedData);

        return response()->json($user);
    }

    /**
     * Получить объявления аутентифицированного пользователя.
     */
    public function getAnnouncements(Request $request)
    {
        $user = Auth::user();

        $announcements = $user->announcements()
            ->with(['user', 'photos'])
            ->latest()
            ->paginate(10);

        return response()->json($announcements);
    }

    /**
     * Обновление волонтерского статуса пользователя.
     */
    public function updateVolunteerStatus(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'is_volunteer' => ['required', 'boolean'],
        ]);

        $user->update($validatedData);

        return response()->json($user);
    }
}