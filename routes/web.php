<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\AvailabilityCheckController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\GpsLogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentRequestController;
use App\Http\Controllers\RentalTripController;
use App\Http\Controllers\CustomerSupportRequestController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\VehicleMaintenanceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/blogs', 'blogs')->middleware('auth')->name('blogs');
Route::view('/terms-of-service', 'terms-of-service')->middleware('auth')->name('terms-of-service');
Route::view('/privacy-policy', 'privacy-policy')->middleware('auth')->name('privacy-policy');
Route::get('/fleet', [FleetController::class, 'index'])->name('fleet.index');
Route::get('/airport-hires', [HomeController::class, 'airportHires'])->name('airport-hires.index');
Route::get('/short-term-rentals', [HomeController::class, 'shortTermRentals'])->name('short-term-rentals.index');
Route::get('/medical-transport', [HomeController::class, 'medicalTransport'])->name('medical-transport.index');
Route::get('/group-packages', [HomeController::class, 'groupPackages'])->name('group-packages.index');
Route::get('/pricing', [HomeController::class, 'pricingIndex'])->name('pricing.index');
Route::get('/booking/confirm', [BookingController::class, 'create'])->name('booking.confirm');
Route::post('/booking/confirm', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('booking.success');
Route::get('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
Route::post('/support-requests', [CustomerSupportRequestController::class, 'store'])->middleware('auth')->name('support-requests.store');
Route::post('/airport-hires/support', [CustomerSupportRequestController::class, 'store'])->name('airport-hires.support.store');
Route::post('/rent-requests', [RentRequestController::class, 'store'])->name('rent-requests.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/customer/dashboard', [HomeController::class, 'customerDashboard'])
        ->middleware('role:customer')
        ->name('customer.dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/bookings/{booking}/cancel', [ProfileController::class, 'cancelBooking'])->name('profile.bookings.cancel');
    Route::get('/profile/bookings/{booking}/invoice/pdf', [ProfileController::class, 'invoicePdf'])->name('profile.bookings.invoice-pdf');

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:dashboard')->name('dashboard');
    Route::middleware('permission:cars')->group(function () {
        Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
        Route::get('/vehicle-pricings', [CarController::class, 'pricingIndex'])->name('vehicle-pricings.index');
        Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
        Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
        Route::patch('/cars/{car}/renewal', [CarController::class, 'updateRenewal'])->name('cars.renewal.update');
        Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
        Route::post('/vehicle-pricings', [CarController::class, 'storePricing'])->name('vehicle-pricings.store');
        Route::put('/vehicle-pricings/{vehiclePricing}', [CarController::class, 'updatePricing'])->name('vehicle-pricings.update');
        Route::delete('/vehicle-pricings/{vehiclePricing}', [CarController::class, 'destroyPricing'])->name('vehicle-pricings.destroy');
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

    Route::middleware('permission:vehicle_maintenance')->group(function () {
        Route::get('/vehicle-maintenance', [VehicleMaintenanceController::class, 'index'])->name('vehicle-maintenance.index');
        Route::get('/vehicle-maintenance/export/pdf', [VehicleMaintenanceController::class, 'exportPdf'])->name('vehicle-maintenance.export-pdf');
        Route::post('/vehicle-maintenance', [VehicleMaintenanceController::class, 'store'])->name('vehicle-maintenance.store');
        Route::put('/vehicle-maintenance/{vehicleMaintenance}', [VehicleMaintenanceController::class, 'update'])->name('vehicle-maintenance.update');
        Route::delete('/vehicle-maintenance/{vehicleMaintenance}', [VehicleMaintenanceController::class, 'destroy'])->name('vehicle-maintenance.destroy');
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

    Route::middleware(['role:admin,partner', 'permission:rental_trips'])->group(function () {
        Route::get('/rental-trips', [RentalTripController::class, 'index'])->name('rental-trips.index');
        Route::get('/rental-trips/export/pdf', [RentalTripController::class, 'exportPdf'])->name('rental-trips.export-pdf');
        Route::get('/rental-trips/{booking}/invoice/pdf', [RentalTripController::class, 'invoicePdf'])->name('rental-trips.invoice-pdf');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/support-requests', [CustomerSupportRequestController::class, 'index'])->name('support-requests.index');
        Route::get('/rent-requests', [RentRequestController::class, 'index'])->name('rent-requests.index');
        Route::put('/rent-requests/{rentRequest}', [RentRequestController::class, 'update'])->name('rent-requests.update');
        Route::post('/rent-requests/{rentRequest}/accept', [RentRequestController::class, 'accept'])->name('rent-requests.accept');
        Route::delete('/rent-requests/{rentRequest}', [RentRequestController::class, 'destroy'])->name('rent-requests.destroy');
        Route::get('/availability-check', [AvailabilityCheckController::class, 'index'])->name('availability-check.index');
        Route::post('/rental-trips/{booking}/cancel', [RentalTripController::class, 'cancel'])->name('rental-trips.cancel');
        Route::post('/rental-trips/{booking}/handover', [RentalTripController::class, 'handover'])->name('rental-trips.handover');
        Route::post('/rental-trips/{booking}/return', [RentalTripController::class, 'returnTrip'])->name('rental-trips.return');
        Route::post('/rental-trips/{booking}/payment/base/paid', [RentalTripController::class, 'markBasePaymentPaid'])->name('rental-trips.payment.base.paid');
        Route::post('/rental-trips/{booking}/payment/additional/paid', [RentalTripController::class, 'markAdditionalPaymentPaid'])->name('rental-trips.payment.additional.paid');
    });
});
