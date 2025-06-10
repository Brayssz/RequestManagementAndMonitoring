<?php

use App\Http\Controllers\Contents\AllotmentController;
use App\Http\Controllers\Contents\AppController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contents\AuthController;
use App\Http\Controllers\Contents\FundSourceController;
use App\Http\Controllers\Contents\PDFController;
use App\Http\Controllers\Contents\ReportController;
use App\Http\Controllers\Contents\RequestingOfficeController;
use App\Http\Controllers\Contents\RequestorController;
use App\Http\Controllers\Contents\UserController;
use App\Http\Controllers\Contents\RequestController;
use Symfony\Component\Routing\RequestContext;

Route::get('/', [AppController::class, 'index']); 

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'showUsers'])->name('users');
    Route::get('/requesting-offices', [RequestingOfficeController::class, 'showRequestingOffices'])->name('requesting-offices')->defaults('type', 'office');
    Route::get('/requesting-schools', [RequestingOfficeController::class, 'showRequestingOffices'])->name('requesting-schools')->defaults('type', 'schools');
    Route::get('requestors', [RequestorController::class,'showRequestors'])->name('requestors');
    Route::get('dashboard', [AppController::class,'showDashboard'])->name('dashboard');
    Route::get('fund-sources', [FundSourceController::class, 'showFundSources'])->name('fund-sources');
    Route::get('receive-requests', [RequestController::class, 'showRequests'])->name('receive-requests');
    Route::get('summary-report', [ReportController::class, 'generateMonthlySummary'])->name('summary-report');
    Route::get('summary-report-pdf', [PDFController::class, 'generateMonthlySummary'])->name('summary-report-pdf');
    Route::get('request-history-report', [ReportController::class, 'requestHistoryReport'])->name('request-history-report');
    Route::get('request-history-report-pdf', [PDFController::class, 'requestHistoryReport'])->name('request-history-report-pdf');
    Route::get('request-logs-report', [ReportController::class, 'requestLogsReport'])->name('request-logs-report');
    Route::get('request-logs-report-pdf', [PDFController::class, 'requestLogsReport'])->name('request-logs-report-pdf');
    Route::get('monthly-request', [AppController::class, 'showMonthlyRequests'])->name('monthly-request');
});
