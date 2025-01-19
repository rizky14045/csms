<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\KeamananController;
use App\Http\Controllers\Admin\AssesmentController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MarturityController;
use App\Http\Controllers\Admin\MonthlyAuditController;
use App\Http\Controllers\Admin\VulnerabilityController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\LevelAssesmentController;
use App\Http\Controllers\Admin\CategoryAssesmentController;
use App\Http\Controllers\Admin\QuestionAssesmentController;

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
    Route::get('/login', [AuthController::class, 'getLogin'])->name('admin.login')->middleware('guest.admin');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.getLogin')->middleware('guest.admin');

    Route::middleware(['auth.admin'])->group(function () {
        
        Route::get('/home', [DashboardController::class, 'index'])->name('admin.home.index');
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
        
        Route::get('/change-password', [ChangePasswordController::class, 'changePassword'])->name('admin.changePassword');
        Route::patch('/update-password', [ChangePasswordController::class, 'updatePassword'])->name('admin.updatePassword');
        Route::prefix('assesment')->group(function () {
            Route::get('/', [AssesmentController::class, 'index'])->name('admin.assesment.index');
            Route::get('/create', [AssesmentController::class, 'create'])->name('admin.assesment.create');
            Route::get('/edit', [AssesmentController::class, 'edit'])->name('admin.assesment.edit');
            Route::get('/show/{assesmentId}', [AssesmentController::class, 'show'])->name('admin.assesment.show');
        });
        Route::prefix('monthly-audit')->group(function () {
            Route::get('/', [MonthlyAuditController::class, 'index'])->name('admin.monthly-audit.index');
            Route::get('/create', [MonthlyAuditController::class, 'create'])->name('admin.monthly-audit.create');
            Route::get('/edit', [MonthlyAuditController::class, 'edit'])->name('admin.monthly-audit.edit');
            Route::get('/show/{monthlyId}', [MonthlyAuditController::class, 'show'])->name('admin.monthly-audit.show');
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
            Route::post('/store', [UnitController::class, 'store'])->name('admin.unit.store');
            Route::get('/edit/{id}', [UnitController::class, 'edit'])->name('admin.unit.edit');
            Route::patch('/edit/{id}', [UnitController::class, 'update'])->name('admin.unit.update');
            Route::delete('/delete/{id}', [UnitController::class, 'destroy'])->name('admin.unit.destroy');
        });

        Route::prefix('category-assesment')->group(function () {
            Route::get('/', [CategoryAssesmentController::class, 'index'])->name('admin.category-assesment.index');
            Route::get('/create', [CategoryAssesmentController::class, 'create'])->name('admin.category-assesment.create');
            Route::post('/store', [CategoryAssesmentController::class, 'store'])->name('admin.category-assesment.store');
            Route::get('/edit/{categoryId}', [CategoryAssesmentController::class, 'edit'])->name('admin.category-assesment.edit');
            Route::patch('/edit/{categoryId}', [CategoryAssesmentController::class, 'update'])->name('admin.category-assesment.update');
            Route::delete('/delete/{categoryId}', [CategoryAssesmentController::class, 'destroy'])->name('admin.category-assesment.destroy');
        });

        Route::prefix('question-assesment')->group(function () {
            Route::get('/create/{categoryId}', [QuestionAssesmentController::class, 'create'])->name('admin.question-assesment.create');
            Route::post('/store/{categoryId}', [QuestionAssesmentController::class, 'store'])->name('admin.question-assesment.store');
            Route::get('{questionId}/edit/{categoryId}', [QuestionAssesmentController::class, 'edit'])->name('admin.question-assesment.edit');
            Route::patch('{questionId}/edit/{categoryId}', [QuestionAssesmentController::class, 'update'])->name('admin.question-assesment.update');
            Route::delete('{questionId}/delete/{categoryId}', [QuestionAssesmentController::class, 'destroy'])->name('admin.question-assesment.destroy');
        });

        Route::prefix('level-assesment')->group(function () {
            Route::get('/create/{questionId}', [LevelAssesmentController::class, 'create'])->name('admin.level-assesment.create');
            Route::post('/store/{questionId}', [LevelAssesmentController::class, 'store'])->name('admin.level-assesment.store');
            Route::get('{levelId}/edit/{questionId}', [LevelAssesmentController::class, 'edit'])->name('admin.level-assesment.edit');
            Route::patch('{levelId}/edit/{questionId}', [LevelAssesmentController::class, 'update'])->name('admin.level-assesment.update');
            Route::delete('{levelId}/delete/{questionId}', [LevelAssesmentController::class, 'destroy'])->name('admin.level-assesment.destroy');
        });

        Route::prefix('vulnerability')->group(function () {
            Route::get('/', [VulnerabilityController::class, 'index'])->name('admin.vulnerability.index');
            Route::get('/create', [VulnerabilityController::class, 'create'])->name('admin.vulnerability.create');
            Route::post('/store', [VulnerabilityController::class, 'store'])->name('admin.vulnerability.store');
            Route::get('/edit/{id}', [VulnerabilityController::class, 'edit'])->name('admin.vulnerability.edit');
            Route::patch('/edit/{id}', [VulnerabilityController::class, 'update'])->name('admin.vulnerability.update');
            Route::delete('/delete/{id}', [VulnerabilityController::class, 'destroy'])->name('admin.vulnerability.destroy');
        });

        Route::prefix('attribute')->group(function () {
            Route::get('/', [AttributeController::class, 'index'])->name('admin.attribute.index');
            Route::get('/create', [AttributeController::class, 'create'])->name('admin.attribute.create');
            Route::post('/store', [AttributeController::class, 'store'])->name('admin.attribute.store');
            Route::get('/edit/{id}', [AttributeController::class, 'edit'])->name('admin.attribute.edit');
            Route::patch('/edit/{id}', [AttributeController::class, 'update'])->name('admin.attribute.update');
            Route::delete('/delete/{id}', [AttributeController::class, 'destroy'])->name('admin.attribute.destroy');
        });
    });

});