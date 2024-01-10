<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TemplateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('form');
});

//Route::get('/createTemplate', [TemplateController::class, 'createTemplate'])->name('createTemplate');

Route::get('/showMailForm', [TemplateController::class, 'showMailForm']);
Route::post('/send-mail', [TemplateController::class, 'sendMail'])->name('sendMail');;

Route::get('/list', [TemplateController::class, 'list']);
Route::get('/download-pdf', [TemplateController::class, 'downloadPdf'])->name('download-pdf');
Route::get('/download-audittrail', [TemplateController::class, 'downloadAudittrail'])->name('download-audittrail');

Route::get('/authorize',  [AuthController::class, 'redirectToAuthorization'])->name('auth.authorize');
Route::get('/callback', [AuthController::class, 'getAccessToken'])->name('auth.callback');

Route::post('/sendRemind', [TemplateController::class, 'sendRemind']);

Route::get('/createIdentity', [TemplateController::class, 'createIdentity']);

// // Define the route pointing to the sendMail function in the TemplateController
// Route::get('/send-mail', [TemplateController::class, 'sendMail'])->name('sendMail');
