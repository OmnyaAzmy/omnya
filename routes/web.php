<?php

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
});

Route::get('/login', function () {
    return view('login');
})
->name('login');

Route::get('/login/{provider}', [SocialiteController::class, 'redirectToProvider'])
    ->where('provider', 'facebook')
    ->name('login.oauth')
    ->middleware('guest');

Route::get('/login/{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])
    ->where('provider', 'facebook')
    ->middleware('guest');

Route::get('/dashboard', function () {
    return auth()->user();
})
->name('dashboard');
