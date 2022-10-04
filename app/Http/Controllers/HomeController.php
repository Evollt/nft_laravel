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
        } else {
            $user->sending_status = 1;
            $user->save();
        }


        $client = new Client();
        $client->request('POST', "https://botstaging.site/api/v1/subscribe", [
            'json' => [
                'discord_id' => Auth::user()->discord_id,
                'name' => Auth::user()->name,
                'discriminator' => Auth::user()->discriminator,
                'subscribe' => Auth::user()->sending_status,
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . '3|szcMfMPXNNW8azrCyLG8kVJ2xoDD9ftuHl8ZBliQ'
            ]
        ]);

        return redirect()->back();
        // return $res->getBody();
    }
}
