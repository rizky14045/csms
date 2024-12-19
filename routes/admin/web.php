<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\KeamananController;
use App\Http\Controllers\Admin\AssesmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MarturityController;
use App\Http\Controllers\Admin\MonthlyAuditController;

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
    Route::prefix('assesment')->group(function () {
        Route::get('/', [AssesmentController::class, 'index'])->name('admin.assesment.index');
        Route::get('/create', [AssesmentController::class, 'create'])->name('admin.assesment.create');
        Route::get('/edit', [AssesmentController::class, 'edit'])->name('admin.assesment.edit');
        Route::get('/show', [AssesmentController::class, 'show'])->name('admin.assesment.show');
    });
    Route::prefix('monthly-audit')->group(function () {
        Route::get('/', [MonthlyAuditController::class, 'index'])->name('admin.monthly-audit.index');
        Route::get('/create', [MonthlyAuditController::class, 'create'])->name('admin.monthly-audit.create');
        Route::get('/edit', [MonthlyAuditController::class, 'edit'])->name('admin.monthly-audit.edit');
        Route::get('/show', [MonthlyAuditController::class, 'show'])->name('admin.monthly-audit.show');
    });
    Route::prefix('marturity')->group(function () {
        Route::get('/', [MarturityController::class, 'index'])->name('admin.marturity.index');
        Route::get('/show', [MarturityController::class, 'show'])->name('admin.marturity.show');
    });

    Route::prefix('keamanan')->group(function () {
        Route::get('/', [KeamananController::class, 'index'])->name('admin.keamanan.index');
        Route::get('/show', [KeamananController::class, 'show'])->name('admin.keamanan.show');
    });
    Route::prefix('unit')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('admin.unit.index');
        Route::get('/create', [UnitController::class, 'create'])->name('admin.unit.create');
        Route::get('/edit', [UnitController::class, 'edit'])->name('admin.unit.edit');
    });
});