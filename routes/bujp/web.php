<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Bujp\AuthController;
use App\Http\Controllers\Bujp\AssesmentController;
use App\Http\Controllers\Bujp\DashboardController;
use App\Http\Controllers\Bujp\ChangePasswordController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::prefix('bujp')->group(function () {
    
    Route::get('/login', [AuthController::class, 'getLogin'])->name('bujp.login')->middleware('guest.vendor');
    Route::post('/login', [AuthController::class, 'login'])->name('bujp.getLogin')->middleware('guest.vendor');
    Route::get('/faq', [FaqController::class, 'index'])->name('bujp.faq.index');


    Route::middleware(['auth.vendor'])->group(function () {
        
        Route::get('/home', [DashboardController::class, 'index'])->name('bujp.home.index');
        Route::post('/logout', [AuthController::class, 'logout'])->name('bujp.logout');

        Route::get('/change-password', [ChangePasswordController::class, 'changePassword'])->name('bujp.changePassword');
        Route::patch('/update-password', [ChangePasswordController::class, 'updatePassword'])->name('bujp.updatePassword');

        Route::prefix('assesment')->group(function () {
            Route::get('/', [AssesmentController::class, 'index'])->name('bujp.assesment.index');
            Route::get('/create', [AssesmentController::class, 'create'])->name('bujp.assesment.create');
            Route::post('/create', [AssesmentController::class, 'store'])->name('bujp.assesment.store');
            Route::get('/edit/{assesmentId}', [AssesmentController::class, 'edit'])->name('bujp.assesment.edit');
            Route::patch('/edit/{assesmentId}', [AssesmentController::class, 'update'])->name('bujp.assesment.update');
            Route::delete('/destroy/{assesmentId}', [AssesmentController::class, 'destroy'])->name('bujp.assesment.destroy');
            Route::patch('/send/{assesmentId}', [AssesmentController::class, 'send'])->name('bujp.assesment.send');
            Route::get('/show/{assesmentId}', [AssesmentController::class, 'show'])->name('bujp.assesment.show');
            Route::get('/preview/{assesmentId}', [AssesmentController::class, 'preview'])->name('bujp.assesment.preview');
            Route::patch('/update-question/{questionId}', [AssesmentController::class, 'updateQuestion'])->name('bujp.assesment.updateQuestion');
            Route::get('/report/{assesmentId}', [AssesmentController::class, 'report'])->name('bujp.assesment.report');
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('bujp.profile.index');
        });
        
    });


});