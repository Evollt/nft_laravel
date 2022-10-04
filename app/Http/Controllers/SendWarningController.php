<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Warning;
use App\Models\WarningImages;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SendWarningController extends Controller
{
    public function createWarning(Request $req) {
        $categories = Category::all();
        $categories = $categories->where('title', $req->category);
        $categoryId = 0;
        foreach($categories as $category) {
            $categoryId = $category->id;
        }

        $warningArray = [
            'user_id' => Auth::user()->id,
            'category_id' => $categoryId,
            'title' => $req->title,
            'text' => $req->text
        ];
        Warning::create($warningArray);
        $warningTitle = Warning::all();
        $warningTitle = $warningTitle->where('title', $req->title)->where('text', $req->text)->first();
        foreach($req->file('images') as $image) {
            $image = $image->store('warnings', 'public');

            WarningImages::create([
                'path' => '/storage/' . $image,
                'warning_id' => $warningTitle->id
            ]);
        }

        return redirect()->route('sendWarning', [ 'id' => $warningTitle->id ]);
    }

    public function sendWarning($id) {
        $warning = Warning::find($id);

        $warningImages = [];
        foreach($warning->warningImage as $worn) {
            array_push($warningImages, 'http://127.0.0.1:8000' . $worn->path);
        }

        $client = new Client();
        $client->request('POST', "https://botstaging.site/api/v1/scam-post", [
            'json' => [
                'title' => $warning->title,
                'text' => $warning->text,
                'category' => $warning->category->title,
                'images' => $warningImages
            ],
            'headers' => [
                'Accept' => 'application/jsn',
                'Authorization' => 'Bearer ' . '3|szcMfMPXNNW8azrCyLG8kVJ2xoDD9ftuHl8ZBliQ'
            ]
        ]);

        return redirect()->route('dashboard')->with('success', 'Данные успешно отправлены');
        // return $warning->category;
        // return [
        //     'title' => $warning->title,
        //     'text' => $warning->text,
        //     'category' => $warning->category->title,
        //     'images' => $warningImages
        // ];
    }
}