<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GpsLogController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionManagementController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:dashboard')->name('dashboard');
    Route::middleware('permission:cars')->group(function () {
        Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
        Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
        Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
        Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
    });

    Route::middleware('permission:payments')->group(function () {
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::post('/payments/{payment}/paid', [PaymentController::class, 'markPaid'])->name('payments.paid');
        Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    });

    Route::middleware('permission:customers')->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });

    Route::middleware('permission:expenses')->group(function () {
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    });

    Route::middleware('permission:agreements')->group(function () {
        Route::get('/agreements', [AgreementController::class, 'index'])->name('agreements.index');
        Route::post('/agreements', [AgreementController::class, 'store'])->name('agreements.store');
        Route::put('/agreements/{agreement}', [AgreementController::class, 'update'])->name('agreements.update');
        Route::delete('/agreements/{agreement}', [AgreementController::class, 'destroy'])->name('agreements.destroy');
    });

    Route::middleware('permission:gps_logs')->group(function () {
        Route::get('/gps-logs', [GpsLogController::class, 'index'])->name('gps-logs.index');
        Route::get('/gps-logs/report', [GpsLogController::class, 'monthlyReport'])->name('gps-logs.report');
        Route::post('/gps-logs/sheet', [GpsLogController::class, 'saveSheet'])->name('gps-logs.sheet');
        Route::post('/gps-logs/service', [GpsLogController::class, 'saveService'])->name('gps-logs.service');
        Route::post('/gps-logs', [GpsLogController::class, 'store'])->name('gps-logs.store');
        Route::put('/gps-logs/{gpsLog}', [GpsLogController::class, 'update'])->name('gps-logs.update');
        Route::delete('/gps-logs/{gpsLog}', [GpsLogController::class, 'destroy'])->name('gps-logs.destroy');
    });

    Route::middleware(['role:admin', 'permission:users_manage'])->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware(['role:admin', 'permission:permissions_manage'])->group(function () {
        Route::get('/permissions', [PermissionManagementController::class, 'index'])->name('permissions.index');
        Route::put('/permissions/{role}', [PermissionManagementController::class, 'update'])->name('permissions.update');
    });
});
