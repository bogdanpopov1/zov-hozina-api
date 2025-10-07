<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TelegramAuthController extends Controller
{
    public function handleCallback(Request $request)
    {
        $auth_data = $request->all();
        $check_hash = $auth_data['hash'];
        unset($auth_data['hash']);

        $data_check_arr = [];
        foreach ($auth_data as $key => $value) {
            $data_check_arr[] = $key . '=' . $value;
        }
        sort($data_check_arr);
        $data_check_string = implode("\n", $data_check_arr);

        $secret_key = hash('sha256', env('TELEGRAM_BOT_TOKEN'), true);
        $hash = hash_hmac('sha256', $data_check_string, $secret_key);

        if (strcmp($hash, $check_hash) !== 0) {
            return response()->json(['error' => 'Data is NOT from Telegram!'], 401);
        }

        // Ищем пользователя по telegram_id
        $user = User::where('telegram_id', $auth_data['id'])->first();

        // Если пользователя нет, создаем нового
        if (!$user) {
            $user = User::create([
                'name' => $auth_data['first_name'] . (isset($auth_data['last_name']) ? ' ' . $auth_data['last_name'] : ''),
                'email' => 'tg_' . $auth_data['id'] . '@zov-hozina.local', // Уникальный email
                'password' => bcrypt(Str::random(16)), // Случайный пароль
                'telegram_id' => $auth_data['id'],
                'telegram_username' => $auth_data['username'] ?? null,
            ]);
        }

        // Создаем токен для входа в систему
        $token = $user->createToken('telegram-login-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
}