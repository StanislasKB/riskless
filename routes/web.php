<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\EmailChangeController;
use App\Http\Controllers\FicheRisqueController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\GlobalDashboardController;
use App\Http\Controllers\GrapheController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\IndicateurController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\MatriceController;
use App\Http\Controllers\PlanActionAvancementController;
use App\Http\Controllers\PlanActionController;
use App\Http\Controllers\ProcessusController;
use App\Http\Controllers\QuizzController;
use App\Http\Controllers\QuizzResponseController;
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
    Route::get('/configurations/delete/{id}/risque-cause', [ConfigurationController::class,'delete_risque_cause'])->name('delete_configuration_risque_cause.post');
    Route::post('/configurations/add/risque-category', [ConfigurationController::class,'store_risque_category'])->name('add_configuration_risque_category.post');
    Route::post('/configurations/update/{id}/risque-category', [ConfigurationController::class,'update_risque_category'])->name('update_configuration_risque_category.post');
    Route::get('/configurations/delete/{id}/risque-category', [ConfigurationController::class,'delete_risque_category'])->name('delete_configuration_risque_category.post');
    Route::post('/configurations/add/macroprocessus', [ConfigurationController::class,'store_macroprocessus'])->name('add_configuration_macroprocessus.post');
    Route::post('/configurations/update/{id}/macroprocessus', [ConfigurationController::class,'update_macroprocessus'])->name('update_configuration_macroprocessus.post');
    Route::get('/configurations/delete/{id}/macroprocessus', [ConfigurationController::class,'delete_macroprocessus'])->name('delete_configuration_macroprocessus.post');


    Route::get('/processus', [ProcessusController::class,'index'])->name('processus.view');
    Route::get('/processus/add', [ProcessusController::class,'add'])->name('add_processus.view');
    Route::get('/processus/{id}/detail', [ProcessusController::class,'details_view'])->name('detail_processus.view');
    Route::get('/processus/{id}/update', [ProcessusController::class,'update_view'])->name('update_processus.view');
    Route::post('/processus/{id}/update', [ProcessusController::class,'update'])->name('update_processus.post');
    Route::get('/processus/{id}/delete', [ProcessusController::class,'destroy'])->name('delete_processus.post');
    Route::post('/processus/add', [ProcessusController::class,'store'])->name('add_processus.post');


     // Formation
    Route::prefix('/formations')->name('formation.')->group( function () {
        Route::get('/list', [FormationController::class,'index'])->name('list.view');
        Route::get('/add', [FormationController::class,'add_view'])->name('add.view');
        Route::get('/update/{id}', [FormationController::class,'update_view'])->name('update.view');
        Route::post('/update/{id}', [FormationController::class,'update'])->name('update.post');
        Route::post('/add', [FormationController::class,'add'])->name('add.post');
        Route::get('/delete/{id}', [FormationController::class,'delete'])->name('delete');
        });
    // Quizz
    Route::prefix('/quizz')->name('quizz.')->group( function () {
        Route::get('/list', [QuizzController::class,'index'])->name('list.view');
        Route::get('/add', [QuizzController::class,'add_view'])->name('add.view');
        Route::get('/update/{id}', [QuizzController::class,'update_view'])->name('update.view');
        Route::post('/update/{id}', [QuizzController::class,'update'])->name('update.post');
        Route::post('/add', [QuizzController::class,'add'])->name('add.post');
        Route::get('/delete/{id}', [QuizzController::class,'delete'])->name('delete');
        });
    // Responses
    Route::prefix('/quizz-responses')->name('responses.')->group( function () {
        Route::get('/list', [QuizzResponseController::class,'index'])->name('list.view');
        Route::post('/add/{id}', [QuizzResponseController::class,'submit_response'])->name('add.post');
        Route::post('/score/{id}', [QuizzResponseController::class,'submit_score'])->name('score.post');
        });

       // Referentiel
       Route::get('/referentiel', [GlobalDashboardController::class,'referentiel'])->name('referentiel.view');
       Route::get('/referentiel/{id}/risk', [GlobalDashboardController::class,'detail_view'])->name('detail.referentiel.view');
       Route::get('/matrice', [MatriceController::class,'global'])->name('matrice.view');

       //Logs
     Route::get('/logs', [LogsController::class,'index'])->name('logs.view');
});

Route::prefix('/service/{uuid}')->name('service.')->group(function () {
    Route::get('/dashboard', [ServiceDashboardController::class,'index'])->name('dashboard.view');
    // plan actions
    Route::get('/plan-actions', [PlanActionController::class,'index'])->name('plan_actions.view');
    Route::post('/plan-actions/create', [PlanActionController::class,'store'])->name('plan-actions.store');
    Route::put('/plan-actions/{planId}/update', [PlanActionController::class,'update'])->name('plan-actions.update');
    Route::delete('/plan-actions/{planId}/delete', [PlanActionController::class,'destroy'])->name('plan-actions.delete');
    // avancements
    Route::get('/plan-actions/{planId}/avancements', [PlanActionAvancementController::class,'index'])->name('plan-actions.avancements.view');
    Route::post('/plan-actions/{planId}/avancements/create', [PlanActionAvancementController::class,'store'])->name('plan-actions.avancements.store');
    Route::put('/plan-actions/{planId}/avancements/{avancementId}/update', [PlanActionAvancementController::class,'update'])->name('plan-actions.avancements.update');
    Route::delete('/plan-actions/{planId}/avancements/{avancementId}/delete', [PlanActionAvancementController::class,'destroy'])->name('plan-actions.avancements.delete');
    // incidents
    Route::get('/incidents', [IncidentController::class,'index'])->name('incident.view');
    Route::post('/incidents/create', [IncidentController::class,'store'])->name('incident.store');
    Route::put('/incidents/{incidentId}/update', [IncidentController::class,'update'])->name('incident.update');
    Route::delete('/incidents/{incidentId}/delete', [IncidentController::class,'destroy'])->name('incident.delete');

    Route::get('/referentiel', [FicheRisqueController::class,'index'])->name('fiche_risque.view');
    Route::get('/referentiel/risk/add', [FicheRisqueController::class,'form_view'])->name('add_fiche_risque.view');
    Route::post('/referentiel/risk/add', [FicheRisqueController::class,'store'])->name('add_fiche_risque.post');
    Route::post('/referentiel/risk/import', [FicheRisqueController::class,'importExcel'])->name('import_fiche_risque.post');
    Route::get('/referentiel/{id}/risk/validate', [FicheRisqueController::class,'validateFicheRisque'])->name('validate_fiche_risque.get');
    Route::get('/referentiel/{id}/risk/edit', [FicheRisqueController::class,'edit_view'])->name('edit_fiche_risque.view');
    Route::get('/referentiel/{id}/risk/delete', [FicheRisqueController::class,'deleteFicheRisque'])->name('delete_fiche_risque.view');
    Route::post('/referentiel/{id}/risk/edit', [FicheRisqueController::class,'update'])->name('edit_fiche_risque.post');
    Route::get('/{id}/risk', [FicheRisqueController::class,'detail_view'])->name('detail_fiche_risque.view');

    Route::get('/matrice', [MatriceController::class,'index'])->name('matrice.view');

    //  indicateurs
    Route::get('/indicateurs', [IndicateurController::class,'index'])->name('indicateurs.view');
    Route::get('/indicateurs/{id}/detail', [IndicateurController::class,'details_view'])->name('detail_indicateur.view');
    Route::get('/indicateurs/{id}/edit', [IndicateurController::class,'edit_view'])->name('edit_indicateur.view');
    Route::post('/indicateurs/{id}/edit', [IndicateurController::class,'update'])->name('edit_indicateur.post');
    Route::post('/indicateurs/{id}/update-evolution', [IndicateurController::class,'evolution'])->name('evolution_indicateur.post');
    Route::get('/indicateurs/{id}/delete', [IndicateurController::class,'delete'])->name('delete_indicateur.view');
    Route::get('/indicateurs/add', [IndicateurController::class,'add_view'])->name('add_indicateur.view');
    Route::post('/indicateurs/add', [IndicateurController::class,'store'])->name('add_indicateur.post');
    Route::get('/indicateurs/{id}/graphe', [GrapheController::class,'graphe_indicateur'])->name('graphe_indicateur.view');



});
