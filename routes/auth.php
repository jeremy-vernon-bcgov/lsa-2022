<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\PermissionsController;
use App\Http\Controllers\Auth\OptionsController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store'])
->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
->middleware('guest')
->name('login');

Route::get('/isauth', [AuthenticatedSessionController::class, 'check'])
->name('check');

Route::get('/profile', [AuthenticatedSessionController::class, 'profile'])
->name('profile');

Route::put('/users/update/{id}', [RegisteredUserController::class, 'update'])
->name('update');

Route::put('/users/role/{id}', [RegisteredUserController::class, 'updateRole'])
->name('update');

Route::get('/users/delete/{id}', [RegisteredUserController::class, 'delete'])
->name('delete');

Route::get('/users/{id}', [RegisteredUserController::class, 'show'])
->name('show');

Route::get('/users', [RegisteredUserController::class, 'index'])
->name('userlist');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
->middleware('guest')
->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
->middleware('guest')
->name('password.update');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
->middleware(['auth', 'signed', 'throttle:6,1'])
->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
->middleware(['auth', 'throttle:6,1'])
->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
->middleware('auth')
->name('logout');

Route::get('/permissions', [PermissionsController::class, 'index'])
->name('index');

Route::put('/permissions/update/{role}', [PermissionsController::class, 'update'])
->name('update');

Route::get('/permissions/{role}', [PermissionsController::class, 'view'])
->name('view');

Route::get('/options', [OptionsController::class, 'index'])
->name('index');

Route::put('/options/create', [OptionsController::class, 'store'])
->name('store');

Route::put('/options/update/{option}', [OptionsController::class, 'update'])
->name('update');

Route::get('/options/delete/{option}', [OptionsController::class, 'update'])
->name('destroy');
