<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ListWorkController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EvaluationController;
use App\Http\Controllers\Admin\PraqualificationController;

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
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'getLogin'])->name('admin.login');
    Route::get('/home', [DashboardController::class, 'index'])->name('admin.home.index');
    Route::get('/faq', [FaqController::class, 'index'])->name('admin.faq.index');
    Route::prefix('praqualification')->group(function () {
        Route::get('/', [PraqualificationController::class, 'index'])->name('admin.praqualification.index');
        Route::get('/create', [PraqualificationController::class, 'create'])->name('admin.praqualification.create');
        Route::get('/edit', [PraqualificationController::class, 'edit'])->name('admin.praqualification.edit');
    });
    
    Route::prefix('evaluation')->group(function () {
        Route::get('/', [EvaluationController::class, 'index'])->name('admin.evaluation.index');
        Route::get('/create', [EvaluationController::class, 'create'])->name('admin.evaluation.create');
        Route::get('/edit', [EvaluationController::class, 'edit'])->name('admin.evaluation.edit');
    });
    Route::prefix('supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('admin.supplier.index');
    });
    Route::prefix('list-work')->group(function () {
        Route::get('/', [ListWorkController::class, 'index'])->name('admin.list-work.index');
        Route::get('/create', [ListWorkController::class, 'create'])->name('admin.list-work.create');
        Route::get('/edit', [ListWorkController::class, 'edit'])->name('admin.list-work.edit');
    });
    Route::prefix('faq')->group(function () {
        Route::get('/', [FaqController::class, 'index'])->name('admin.faq.index');
        Route::get('/create', [FaqController::class, 'create'])->name('admin.faq.create');
        Route::get('/edit', [FaqController::class, 'edit'])->name('admin.faq.edit');
    });
});