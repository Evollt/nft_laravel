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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginWithDiscordController::class, 'login'])->name('login');

Route::get('/discord', [LoginWithDiscordController::class, 'discord'])->name('discord');

Route::get('/discordRedirect', [LoginWithDiscordController::class, 'discordRedirect'])->name('discordRedirect');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [LoginWithDiscordController::class, 'logout'])->name('logout');
    Route::post('/createWarning', [SendWarningController::class, 'createWarning'])->name('createWarning');
    Route::get('/sendWarning/{id}', [SendWarningController::class, 'sendWarning'])->name('sendWarning');
    Route::get('/subscribe', [HomeController::class, 'subscribe'])->name('subscribe');
    Route::get('/scam', [HomeController::class, 'scam'])->name('scam');
});