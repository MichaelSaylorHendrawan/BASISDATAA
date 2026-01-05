<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SessionAuthenticate;
use App\Http\Controllers\MemberController;

// Public routes
Route::get('/', fn() => redirect()->route('LoginPage') );

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'LoginPage')->name('LoginPage');
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');

    Route::get('/send-otp', 'SendOtpPage')->name('SendOtpPage');
    Route::post('/send-otp', 'SendOTPCode')->name('SendOTPCode');

    Route::get('/verify-otp', 'VerifyOtpPage')->name('VerifyOtpPage');
    Route::post('/verify-otp', 'VerifyOTP')->name('VerifyOTPCode');

    Route::get('/reset-password','ResetPasswordPage')->name('ResetPasswordPage');
    Route::post('/reset-password','ResetPassword')->name('ResetPassword');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'RegistrationPage')->name('RegistrationPage');
    Route::post('/register', 'register')->name('register');
});

// Protected routes
Route::middleware([SessionAuthenticate::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('DashboardPage');
    
    // Products routes with specific permissions
    Route::controller(ProductController::class)->prefix('products')->name('products.')->group(function () {
        Route::get('/', 'index')->middleware('permission:view-product')->name('index');
        Route::get('/create', 'create')->middleware('permission:create-product')->name('create');
        Route::post('/', 'store')->middleware('permission:create-product')->name('store');
        Route::get('/{product}/edit', 'edit')->middleware('permission:edit-product')->name('edit');
        Route::put('/{product}', 'update')->middleware('permission:edit-product')->name('update');
        Route::delete('/{product}', 'destroy')->middleware('permission:delete-product')->name('destroy');
    });
    
    // Users routes with specific permissions
    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->middleware('permission:view-user')->name('index');
        Route::get('/create', 'create')->middleware('permission:create-user')->name('create');
        Route::post('/', 'store')->middleware('permission:create-user')->name('store');
        Route::get('/{user}/edit', 'edit')->middleware('permission:edit-user')->name('edit');
        Route::put('/{user}', 'update')->middleware('permission:edit-user')->name('update');
        Route::delete('/{user}', 'destroy')->middleware('permission:delete-user')->name('destroy');
    });
    
    // Roles routes with specific permissions
    Route::controller(RoleController::class)->prefix('roles')->name('roles.')->group(function () {
        Route::get('/', 'index')->middleware('permission:view-role')->name('index');
        Route::get('/create', 'create')->middleware('permission:create-role')->name('create');
        Route::post('/', 'store')->middleware('permission:create-role')->name('store');
        Route::get('/{role}/edit', 'edit')->middleware('permission:edit-role')->name('edit');
        Route::put('/{role}', 'update')->middleware('permission:edit-role')->name('update');
        Route::delete('/{role}', 'destroy')->middleware('permission:delete-role')->name('destroy');
    });
    
    // Permissions routes with specific permissions
    Route::controller(PermissionController::class)->prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', 'index')->middleware('permission:view-permission')->name('index');
        Route::get('/create', 'create')->middleware('permission:create-permission')->name('create');
        Route::post('/', 'store')->middleware('permission:create-permission')->name('store');
        Route::get('/{permission}/edit', 'edit')->middleware('permission:edit-permission')->name('edit');
        Route::put('/{permission}', 'update')->middleware('permission:edit-permission')->name('update');
        Route::delete('/{permission}', 'destroy')->middleware('permission:delete-permission')->name('destroy');
    });

    // Tambahkan di dalam group protected (di bagian yang sama dengan routes products/users)
Route::controller(MemberController::class)
    ->prefix('members')
    ->name('members.')
    ->group(function () {
        Route::get('/', 'index')->middleware('permission:view-member')->name('index');
        Route::get('/{member}/edit', 'edit')->middleware('permission:edit-member')->name('edit');
        Route::delete('/{member}', 'destroy')->middleware('permission:delete-member')->name('destroy');
        });
});
