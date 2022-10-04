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
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }

    public function discord() {
        return Socialite::driver('discord')->redirect();
    }

    public function discordRedirect() {
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

        $checkUserInDbSecond = User::where('discord_id', $user->id)->first();

        Auth::login($checkUserInDbSecond);

        return redirect()->route('dashboard');
    }
}