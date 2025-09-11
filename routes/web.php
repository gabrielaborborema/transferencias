<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Login;
use App\Http\Controllers\LogoutController;

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('login', Login::class)->name('login');
    Route::get('/', fn() => redirect()->route('login'));
});

Route::middleware('auth')->group(function () {
    Route::get('/', fn() => redirect()->route('home'));

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::post('/logout', LogoutController::class)->name('logout');
});
