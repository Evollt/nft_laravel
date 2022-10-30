<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarningRequest;
use App\Models\Category;
use App\Models\Warning;
use App\Models\WarningImages;
use GuzzleHttp\Client;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;

class SendWarningController extends Controller
{
    public function createWarning(WarningRequest $req) {
        // Берем айдишник той катергории, которую прислал пользователь
        $categories = Category::all();
        $categories = $categories->where('title', $req->category);
        $categoryId = 0;
        foreach($categories as $category) {
            $categoryId = $category->id;
        }

        // массив, которой я потом добавляю в бд.
        $warningArray = [
            'user_id' => Auth::user()->id,
            'category_id' => $categoryId,
            'title' => $req->title,
            'text' => $req->text
        ];

        Warning::create($warningArray);
        $warningTitle = Warning::all();
        $warningTitle = $warningTitle->where('title', $req->title)->where('text', $req->text)->first();
        // Проверяем прикрепил ли пользователь изображения
        if(!empty($req->file('images'))) {
            // нам нужно пройтись по всем изображениям, чтобы понять какое пренадлежит именно тому предупреждению
            foreach($req->file('images') as $image) {
                $image = $image->store('warnings', 'public');

                WarningImages::create([
                    'path' => '/storage/' . $image,
                    'warning_id' => $warningTitle->id
                ]);
            }
        }

        return redirect()->route('sendWarning', [ 'id' => $warningTitle->id ]);
    }

    public function sendWarning($id) {
        // после редиректа с создания предупреждения в бд я должен из прикрепленного id найти то предупреждение и все связанные с ним данные.
        $warning = Warning::find($id);

        // находим все, пренадлежащие этому предупреждению изображения и добавляем к ним названия домена
        $warningImages = [];
        foreach($warning->warningImage as $worn) {
            array_push($warningImages, 'http://127.0.0.1:8000' . $worn->path);
        }

        // здесь мне надо сделать запрос к этому пидору из Нидерланд, чтобы он добавил предупреждение к себе в админ доступ
        $client = new Client();
        $client->request('POST', "https://botstaging.site/api/v1/scam-post", [
            'json' => [ // на этой строчке обязательно должно быть написано json(бля... Чтож я все время хочу поменять эту строчку)
                'title' => $warning->title,
                'text' => $warning->text,
                'category' => $warning->category->title,
                'images' => $warningImages
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . 'g56lEczlb6WqdlwG6Tmeb6wC3W6kSHJvPTii7u5S'
            ]
        ]);

        return redirect()->route('index')->with('success', 'Данные успешно отправлены');
    }
}