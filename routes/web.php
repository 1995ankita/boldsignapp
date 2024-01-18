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

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('form');
});

// Route::get('/showMailForm',         [TemplateController::class, 'showMailForm']);
Route::get('/createTemplate',       [TemplateController::class, 'createTemplate']);
Route::post('/send-mail',           [TemplateController::class, 'sendMail'])->name('sendMail');
Route::post('/sendRemind',          [TemplateController::class, 'sendRemind']);
Route::get('/sendDocument',          [TemplateController::class, 'sendDocument']);


//Identity
Route::get('/createIdentity',       [TemplateController::class, 'createIdentity']);
Route::get('/listIdentity',         [TemplateController::class, 'listIdentity']);
Route::delete('/deleteIdentity/{email}', [TemplateController::class, 'deleteIdentity'])->name('deleteIdentity');

Route::get('/apiCreditsCount',       [TemplateController::class, 'apiCreditsCount']);
Route::post('/revokeDocument',       [TemplateController::class, 'revokeDocument']);

Route::get('/extendExpiry',         [TemplateController::class, 'extendExpiry']);
Route::get('/embeddedSigningLink/{documentId}/{email}', [TemplateController::class, 'embeddedSigningLink'])->name('generate-link');
Route::get('/list',                 [TemplateController::class, 'list']);
Route::get('/download-pdf',         [TemplateController::class, 'downloadPdf'])->name('download-pdf');
Route::get('/download-audittrail',  [TemplateController::class, 'downloadAudittrail'])->name('download-audittrail');

Route::get('/authorize',            [AuthController::class, 'redirectToAuthorization'])->name('auth.authorize');
Route::get('/callback',             [AuthController::class, 'getAccessToken'])->name('auth.callback');
