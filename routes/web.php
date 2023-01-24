<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\SocialiteController;
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
    return view('welcome');
})->name('welcome');



Route::post('/do/login', [LoginController::class, 'login'])->name('doLogin');

Route::middleware(['guest'])->group(function () {
    Route::view('login', 'login')->name('login');

    Route::view('/register', 'register')->name('register');

    Route::post('/do/register', [LoginController::class, 'doRegister'])->name('doRegister');


});

Route::get('auth/{provider}', [SocialiteController::class, 'redirect']);

Route::get('auth/{provider}/callback', [SocialiteController::class, 'callback']);

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::view('/home', 'home')->name('home');
});