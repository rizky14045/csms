<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\FaqController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\KeamananController;
use App\Http\Controllers\User\ListWorkController;
use App\Http\Controllers\User\AssesmentController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\MarturityController;
use App\Http\Controllers\User\MonthlyAuditController;
use App\Http\Controllers\User\PraqualificationController;

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
Route::prefix('user')->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('user.home.index');
    Route::get('/faq', [FaqController::class, 'index'])->name('user.faq.index');
    Route::prefix('assesment')->group(function () {
        Route::get('/', [AssesmentController::class, 'index'])->name('user.assesment.index');
        Route::get('/create', [AssesmentController::class, 'create'])->name('user.assesment.create');
        Route::get('/edit', [AssesmentController::class, 'edit'])->name('user.assesment.edit');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('user.profile.index');
    });
    
    Route::prefix('monthly-audit')->group(function () {
        Route::get('/', [MonthlyAuditController::class, 'index'])->name('user.monthly-audit.index');
        Route::get('/create', [MonthlyAuditController::class, 'create'])->name('user.monthly-audit.create');
        Route::get('/edit', [MonthlyAuditController::class, 'edit'])->name('user.monthly-audit.edit');
        Route::get('/show', [MonthlyAuditController::class, 'show'])->name('user.monthly-audit.show');
    });

    Route::prefix('marturity')->group(function () {
        Route::get('/', [MarturityController::class, 'index'])->name('user.marturity.index');
        Route::get('/create', [MarturityController::class, 'create'])->name('user.marturity.create');
        Route::get('/edit', [MarturityController::class, 'edit'])->name('user.marturity.edit');
        Route::get('/show', [MarturityController::class, 'show'])->name('user.marturity.show');
    });

    Route::prefix('keamanan')->group(function () {
        Route::get('/', [KeamananController::class, 'index'])->name('user.keamanan.index');
        Route::get('/create', [KeamananController::class, 'create'])->name('user.keamanan.create');
        Route::get('/edit', [KeamananController::class, 'edit'])->name('user.keamanan.edit');
        Route::get('/show', [KeamananController::class, 'show'])->name('user.keamanan.show');
    });

});