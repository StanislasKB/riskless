<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GlobalDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/auth')->name('auth.')->group(function () {
    Route::get('/register', [AuthController::class,'register_view'])->name('register.view');
    Route::post('/register', [AuthController::class,'register'])->name('register.post');
    Route::get('/confirm-mail', [AuthController::class,'confirm_mail_view'])->name('confirm_mail.view');
    Route::post('/confirm-mail', [AuthController::class,'confirm_mail'])->name('confirm_mail.post');
    Route::get('/resend-code', [AuthController::class,'resend_code'])->name('resend_code');
    Route::get('/login', [AuthController::class,'login_view'])->name('login.view');
    Route::get('/logout', [AuthController::class,'logout'])->name('logout');
    Route::post('/login', [AuthController::class,'login'])->name('login.post');
    Route::get('/check-reset-token', [AuthController::class,'check_reset_token_view'])->name('check_reset_token.view');
    Route::post('/check-reset-token', [AuthController::class,'check_reset_password_token'])->name('check_reset_token.post');
    Route::get('/password-forget', [AuthController::class,'password_forget_view'])->name('password_forget.view');
    Route::post('/password-forget', [AuthController::class,'reset_password_token'])->name('password_forget.post');
    Route::get('/resend-reset-token', [AuthController::class,'resend_reset_password_token'])->name('resend_reset_password_token');
    Route::get('/reset-password', [AuthController::class,'reset_password_view'])->name('reset_password.view');
    Route::post('/reset-password', [AuthController::class,'reset_password'])->name('reset_password.post');

});


Route::prefix('/global')->name('global.')->group(function () {
    Route::get('/dashboard', [GlobalDashboardController::class,'index'])->name('dashboard.view');
   

});