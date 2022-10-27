<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginWithDiscordController extends Controller
{
    public function login() {
        if(Auth::check()) {
            return redirect()->route('index');
        }
        return redirect()->route('discord');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('index');
    }

    public function discord() {
        // эта хуйня переводит на страницу регистрации через discord
        return Socialite::driver('discord')->redirect();
    }

    public function discordRedirect() {
        // это массив с данным о пользователе
        $user = Socialite::driver('discord')->user();

        $getDiscriminator = $user->user;

        $userInfo = [
            'discord_id' => $user->id,
            'name' => $user->name,
            'discriminator' => $getDiscriminator['discriminator'],
            'email' => $user->email,
            'avatar' => $user->avatar,
            'sending_status' => 0
        ];

        // Проверка существования до создания и после создания, чтобы точно чел прошел
        $checkUserInDb = User::where('discord_id', $user->id)->first();

        if ($checkUserInDb === null) {
            User::create($userInfo);
        }

        // эту хуйню я создаю уже после создания пользователя, чтобы аунтенфицировать его
        $checkUserInDb = User::where('discord_id', $user->id)->first();

        Auth::login($checkUserInDb);

        return redirect()->route('index');
    }
}