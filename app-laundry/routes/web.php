<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ExpenseController;

// Public / Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::resource('customers', CustomerController::class)->except(['show']);

    // Services (master data)
    Route::resource('services', ServiceController::class);

    // Expenses
    Route::resource('expenses', ExpenseController::class)->except(['show']);

    // Transactions (kasir)
    Route::resource('transactions', TransactionController::class);
    Route::get('transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');

    // Operations board (operator)
    Route::get('operations', [OperationController::class, 'index'])->name('operations.index');
    Route::post('operations/{transaction}/stage', [OperationController::class, 'updateStage'])->name('operations.updateStage');

    Route::get('/transactions/{transaction}/print',        [TransactionController::class, 'print'])->name('transactions.print');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');

    // Reports (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

        // Users (admin)
        Route::resource('users', UserController::class);

        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'store'])->name('settings.store');
    });
});

