<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\EmailChangeController;
use App\Http\Controllers\GlobalDashboardController;
use App\Http\Controllers\ProcessusController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceDashboardController;
use App\Http\Controllers\UserController;
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
    Route::get('/first-reset-password', [AuthController::class,'first_reset_password_view'])->name('first_reset_password.view');
    Route::post('/reset-password', [AuthController::class,'reset_password'])->name('reset_password.post');
    Route::post('/first-reset-password', [AuthController::class,'first_reset_password'])->name('first_reset_password.post');

});


Route::prefix('/global')->name('global.')->group(function () {
    Route::get('/dashboard', [GlobalDashboardController::class,'index'])->name('dashboard.view');

    Route::get('/services', [ServiceController::class,'index'])->name('service.view');
    Route::post('/services/new', [ServiceController::class,'store'])->name('add_service.post');
    Route::post('/services/user/new', [ServiceController::class,'add_service_user'])->name('add_service_user.post');
    Route::post('/services/user/{id}/permission-update', [ServiceController::class,'updateUserPermissions'])->name('update_service_user_permission.post');
    

    Route::get('/users', [UserController::class,'users_view'])->name('users.view');
    Route::get('/users/profile/email-change-request', [EmailChangeController::class,'request_view'])->name('email_request.view');
    Route::get('/users/profile/email-change-check-token', [EmailChangeController::class,'email_change_check_view'])->name('email_change_check.view');
    Route::get('/users/profile/email-change-resend-token', [EmailChangeController::class,'resendCode'])->name('email_change_resend_code.view');
    Route::post('/users/profile/email-change-check-token', [EmailChangeController::class,'verifyCode'])->name('email_change_check.post');
    Route::post('/users/profile/email-change-request', [EmailChangeController::class,'requestEmailChange'])->name('email_request.post');
    Route::get('/users/profile', [UserController::class,'user_profile'])->name('user_profile.view');
    Route::post('/users/new', [UserController::class,'add_user'])->name('add_user.post');
    Route::post('/users/update-username', [UserController::class,'update_username'])->name('update_username.post');
    Route::post('/users/notification-settings', [UserController::class,'updateNotificationPreferences'])->name('update_notifications.post');
    Route::post('/users/update-password', [AuthController::class,'change_password'])->name('update_password.post');
    Route::post('/users/update-profile-image', [UserController::class,'update_profil_img'])->name('update_profil_img.post');
    Route::get('/users/{id}/status/update', [AuthController::class,'change_user_status'])->name('update_user_status');
    Route::get('/users/{id}/status/delete', [AuthController::class,'delete_user'])->name('delete_user_status');


    Route::get('/configurations', [ConfigurationController::class,'index'])->name('configurations.view');
    Route::get('/configurations/add', [ConfigurationController::class,'add'])->name('add_configuration.view');
    Route::post('/configurations/add/risque-cause', [ConfigurationController::class,'store_risque_cause'])->name('add_configuration_risque_cause.post');
    Route::post('/configurations/update/{id}/risque-cause', [ConfigurationController::class,'update_risque_cause'])->name('update_configuration_risque_cause.post');
    Route::post('/configurations/add/risque-category', [ConfigurationController::class,'store_risque_category'])->name('add_configuration_risque_category.post');
    Route::post('/configurations/add/macroprocessus', [ConfigurationController::class,'store_macroprocessus'])->name('add_configuration_macroprocessus.post');
   

    Route::get('/processus', [ProcessusController::class,'index'])->name('processus.view');
    Route::get('/processus/add', [ProcessusController::class,'add'])->name('add_processus.view');
    Route::post('/processus/add', [ProcessusController::class,'store'])->name('add_processus.post');
});

Route::prefix('/service/{uuid}')->name('service.')->group(function () {
    Route::get('/dashboard', [ServiceDashboardController::class,'index'])->name('dashboard.view');
});