<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard() {
        return view('dashboard', ['data' => Category::all()]);
    }

    public function user() {
        return Auth::user();
    }

    public function subscribe() {
        $user = User::find(Auth::user()->id);

        if(Auth::user()->sending_status) {
            $user->sending_status = 0;
            $user->save();

            $sending_status = false;
        } else {
            $user->sending_status = 1;
            $user->save();

            $sending_status = true;
        }


        $client = new Client();
        $client->request('POST', "https://botstaging.site/api/v1/subscribe", [
            'json' => [
                'discord_id' => $user->discord_id,
                'name' => $user->name,
                'discriminator' => $user->discriminator,
                'subscribe' => $sending_status,
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . '17wO6wp3qWVbA3F58hd6DYG6S3RqP6dePqDnhhvn'
            ]
        ]);

        return redirect()->back();
    }
}