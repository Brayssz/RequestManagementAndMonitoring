<?php

use App\Http\Controllers\Contents\AllotmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contents\AuthController;
use App\Http\Controllers\Contents\FundSourceController;
use App\Http\Controllers\Contents\RequestingOfficeController;
use App\Http\Controllers\Contents\RequestorController;
use App\Http\Controllers\Contents\UserController;
use App\Http\Controllers\Contents\RequestController;

Route::get('/', function () {
    return view('welcome');
}); 


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/users', [UserController::class, 'showUsers'])->name('users');

Route::get('/requesting-offices', [RequestingOfficeController::class, 'showRequestingOffices'])->name('requesting-offices');

Route::get('requestors', [RequestorController::class,'showRequestors'])->name('requestors');

Route::get('fund-sources', [FundSourceController::class, 'showFundSources'])->name('fund-sources');

Route::get('allotments', [AllotmentController::class, 'showAllotments'])->name('allotments');

Route::get('receive-requests', [RequestController::class, 'showRequests'])->name('receive-requests');