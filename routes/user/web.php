<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\FaqController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ListWorkController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\EvaluationController;
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
    Route::prefix('praqualification')->group(function () {
        Route::get('/', [PraqualificationController::class, 'index'])->name('user.praqualification.index');
        Route::get('/create', [PraqualificationController::class, 'create'])->name('user.praqualification.create');
        Route::get('/edit', [PraqualificationController::class, 'edit'])->name('user.praqualification.edit');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('user.profile.index');
    });
    
    Route::prefix('evaluation')->group(function () {
        Route::get('/', [EvaluationController::class, 'index'])->name('user.evaluation.index');
        Route::get('/create', [EvaluationController::class, 'create'])->name('user.evaluation.create');
        Route::get('/edit', [EvaluationController::class, 'edit'])->name('user.evaluation.edit');
    });
    Route::prefix('list-work')->group(function () {
        Route::get('/', [ListWorkController::class, 'index'])->name('user.list-work.index');
        Route::get('/create', [ListWorkController::class, 'create'])->name('user.list-work.create');
        Route::get('/edit', [ListWorkController::class, 'edit'])->name('user.list-work.edit');
    });
});