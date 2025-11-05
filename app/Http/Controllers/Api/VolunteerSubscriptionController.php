<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VolunteerSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Проверяем, является ли пользователь волонтером
        if (!$user->is_volunteer) {
            return response()->json(['error' => 'Access denied. User is not a volunteer.'], 403);
        }

        $subscriptions = $user->subscriptions()->latest()->get();

        return response()->json($subscriptions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Проверяем, является ли пользователь волонтером
        if (!$user->is_volunteer) {
            return response()->json(['error' => 'Access denied. User is not a volunteer.'], 403);
        }

        $validatedData = $request->validate([
            'location_name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['required', 'integer', 'min:1', 'max:100'], // Радиус от 1 до 100 км
        ]);

        $subscription = $user->subscriptions()->create($validatedData);

        return response()->json($subscription, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(VolunteerSubscription $volunteerSubscription)
    {
        // Этот метод нам не понадобится
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VolunteerSubscription $volunteerSubscription)
    {
        // Этот метод нам не понадобится
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VolunteerSubscription $subscription)
    {
        $user = Auth::user();

        // Проверка авторизации: убеждаемся, что подписка принадлежит аутентифицированному пользователю
        if ($user->user_id !== $subscription->user_id) {
            return response()->json(['error' => 'Forbidden. You do not own this subscription.'], 403);
        }

        $subscription->delete();

        return response()->json(null, 204);
    }
}