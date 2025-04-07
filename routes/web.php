<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contents\AuthController;
use App\Http\Controllers\Contents\UserController;

Route::get('/', function () {
    return view('welcome');
}); 


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/users', [UserController::class, 'showUsers'])->name('users');