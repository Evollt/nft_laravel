<?php

use App\Http\Controllers\Auth\LoginWithDiscordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SendWarningController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/delphi', [HomeController::class, 'delphi'])->name('delphi');
Route::get('/login', [LoginWithDiscordController::class, 'login'])->name('login');
Route::get('/discord', [LoginWithDiscordController::class, 'discord'])->name('discord');
Route::get('/discordRedirect', [LoginWithDiscordController::class, 'discordRedirect'])->name('discordRedirect');
Route::post('/search', [HomeController::class, 'search'])->name('search');
Route::post('/createWarning', [SendWarningController::class, 'createWarning'])->name('createWarning');


Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginWithDiscordController::class, 'logout'])->name('logout');
    Route::get('/sendWarning/{id}', [SendWarningController::class, 'sendWarning'])->name('sendWarning');
    Route::get('/subscribe', [HomeController::class, 'subscribe'])->name('subscribe');
    Route::get('/scam', [HomeController::class, 'scam'])->name('scam');
    Route::get('/getScams', [HomeController::class, 'getScams'])->name('getScams');
});