<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\OutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(HomeController::class)->group(function () {
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::middleware('guest')->group(function () {
        Route::get('/', 'signin')->name('signIn');
        Route::get('/signup', 'signup')->name('signUp');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', 'profile');
        Route::post('/profile/upload-profile', 'uploadProfile');
        Route::post('/profile/upload-cover', 'uploadCover');

        Route::put('/links/{link}', [LinkController::class, 'update']);
        Route::post('/link-details/create', [LinkController::class, 'store'])->name('links.create');
    });
});


Route::controller(AuthController::class)->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('facebook', 'redirectToFacebook');
        Route::get('facebook/callback', 'handleFacebookCallback');
    });
});



Route::get('auth/{provider}/callback', [OutController::class, 'index'])->where('provider', '.*');
Route::post('social-login/{provider}', [AuthController::class, 'SocialSignup']);
