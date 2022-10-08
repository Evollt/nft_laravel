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
        // здесь передаю категории, чтобы в шаблоне я мог выводить все существующие на данный момент в бд категории предупреждений
        return view('dashboard', ['data' => Category::all()]);
    }

    public function subscribe() {
        // так, этот скрипт принципе прост, но боюсь через неделю я не пойму зачем я это написал. ахахаа
        $user = User::find(Auth::user()->id);

        // короче, тут я проверяю подписан чувак или отписан. Если отписан, то подписываю, если подписан, то отписываю(вот впринципе и вся логика)
        if($user->sending_status) {
            $user->sending_status = 0;
            $user->save();

            // кстати эта переменная нужна. Этот пидрила из Нидерланд хуйню какую-то вытворил и ему нельзя в api присылать числа 1 и 0, можно только true и false
            // в бд все хранится в tinyint, то есть 1 и 0
            $sending_status = false;
        } else {
            $user->sending_status = 1;
            $user->save();

            $sending_status = true;
        }


        // запрос пидору из Нидерланд на статус подписки пользователя
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