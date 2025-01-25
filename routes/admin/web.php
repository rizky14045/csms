<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\KPIAreaController;
use App\Http\Controllers\Admin\KPINoteController;
use App\Http\Controllers\Admin\KeamananController;
use App\Http\Controllers\Admin\KPILevelController;
use App\Http\Controllers\Admin\AssesmentController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MarturityController;
use App\Http\Controllers\Admin\KPISubAreaController;
use App\Http\Controllers\Admin\MonthlyAuditController;
use App\Http\Controllers\Admin\MarturityAreaController;
use App\Http\Controllers\Admin\MarturityNoteController;
use App\Http\Controllers\Admin\VulnerabilityController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\LevelAssesmentController;
use App\Http\Controllers\Admin\MarturityLevelController;
use App\Http\Controllers\Admin\MarturitySubAreaController;
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

        Route::prefix('marturity-area')->group(function () {
            Route::get('/', [MarturityAreaController::class, 'index'])->name('admin.marturity-area.index');
            Route::get('/create', [MarturityAreaController::class, 'create'])->name('admin.marturity-area.create');
            Route::post('/store', [MarturityAreaController::class, 'store'])->name('admin.marturity-area.store');
            Route::get('/edit/{areaId}', [MarturityAreaController::class, 'edit'])->name('admin.marturity-area.edit');
            Route::patch('/edit/{areaId}', [MarturityAreaController::class, 'update'])->name('admin.marturity-area.update');
            Route::delete('/delete/{areaId}', [MarturityAreaController::class, 'destroy'])->name('admin.marturity-area.destroy');
        });

        Route::prefix('marturity-sub-area')->group(function () {
            Route::get('/create/{areaId}', [MarturitySubAreaController::class, 'create'])->name('admin.marturity-sub-area.create');
            Route::post('/store/{areaId}', [MarturitySubAreaController::class, 'store'])->name('admin.marturity-sub-area.store');
            Route::get('/{subAreaId}/edit/{areaId}', [MarturitySubAreaController::class, 'edit'])->name('admin.marturity-sub-area.edit');
            Route::patch('/{subAreaId}/edit/{areaId}', [MarturitySubAreaController::class, 'update'])->name('admin.marturity-sub-area.update');
            Route::delete('/{subAreaId}/delete/{areaId}', [MarturitySubAreaController::class, 'destroy'])->name('admin.marturity-sub-area.destroy');
        });
        Route::prefix('marturity-level')->group(function () {
            Route::get('/create/{subAreaId}', [MarturityLevelController::class, 'create'])->name('admin.marturity-level.create');
            Route::post('/store/{subAreaId}', [MarturityLevelController::class, 'store'])->name('admin.marturity-level.store');
            Route::get('/{levelId}/edit/{subAreaId}', [MarturityLevelController::class, 'edit'])->name('admin.marturity-level.edit');
            Route::patch('/{levelId}/edit/{subAreaId}', [MarturityLevelController::class, 'update'])->name('admin.marturity-level.update');
            Route::delete('/{levelId}/delete/{subAreaId}', [MarturityLevelController::class, 'destroy'])->name('admin.marturity-level.destroy');
        });

        Route::prefix('marturity-note')->group(function () {
            Route::get('/create/{levelId}', [MarturityNoteController::class, 'create'])->name('admin.marturity-note.create');
            Route::post('/store/{levelId}', [MarturityNoteController::class, 'store'])->name('admin.marturity-note.store');
            Route::get('/{noteId}/edit/{levelId}', [MarturityNoteController::class, 'edit'])->name('admin.marturity-note.edit');
            Route::patch('/{noteId}/edit/{levelId}', [MarturityNoteController::class, 'update'])->name('admin.marturity-note.update');
            Route::delete('/{noteId}/delete/{levelId}', [MarturityNoteController::class, 'destroy'])->name('admin.marturity-note.destroy');
        });
        
        Route::prefix('kpi-area')->group(function () {
            Route::get('/', [KPIAreaController::class, 'index'])->name('admin.kpi-area.index');
            Route::get('/create', [KPIAreaController::class, 'create'])->name('admin.kpi-area.create');
            Route::post('/store', [KPIAreaController::class, 'store'])->name('admin.kpi-area.store');
            Route::get('/edit/{areaId}', [KPIAreaController::class, 'edit'])->name('admin.kpi-area.edit');
            Route::patch('/edit/{areaId}', [KPIAreaController::class, 'update'])->name('admin.kpi-area.update');
            Route::delete('/delete/{areaId}', [KPIAreaController::class, 'destroy'])->name('admin.kpi-area.destroy');
        });

        Route::prefix('kpi-sub-area')->group(function () {
            Route::get('/create/{areaId}', [KPISubAreaController::class, 'create'])->name('admin.kpi-sub-area.create');
            Route::post('/store/{areaId}', [KPISubAreaController::class, 'store'])->name('admin.kpi-sub-area.store');
            Route::get('/{subAreaId}/edit/{areaId}', [KPISubAreaController::class, 'edit'])->name('admin.kpi-sub-area.edit');
            Route::patch('/{subAreaId}/edit/{areaId}', [KPISubAreaController::class, 'update'])->name('admin.kpi-sub-area.update');
            Route::delete('/{subAreaId}/delete/{areaId}', [KPISubAreaController::class, 'destroy'])->name('admin.kpi-sub-area.destroy');
        });
        Route::prefix('kpi-level')->group(function () {
            Route::get('/create/{subAreaId}', [KPILevelController::class, 'create'])->name('admin.kpi-level.create');
            Route::post('/store/{subAreaId}', [KPILevelController::class, 'store'])->name('admin.kpi-level.store');
            Route::get('/{levelId}/edit/{subAreaId}', [KPILevelController::class, 'edit'])->name('admin.kpi-level.edit');
            Route::patch('/{levelId}/edit/{subAreaId}', [KPILevelController::class, 'update'])->name('admin.kpi-level.update');
            Route::delete('/{levelId}/delete/{subAreaId}', [KPILevelController::class, 'destroy'])->name('admin.kpi-level.destroy');
        });

        Route::prefix('kpi-note')->group(function () {
            Route::get('/create/{levelId}', [KPINoteController::class, 'create'])->name('admin.kpi-note.create');
            Route::post('/store/{levelId}', [KPINoteController::class, 'store'])->name('admin.kpi-note.store');
            Route::get('/{noteId}/edit/{levelId}', [KPINoteController::class, 'edit'])->name('admin.kpi-note.edit');
            Route::patch('/{noteId}/edit/{levelId}', [KPINoteController::class, 'update'])->name('admin.kpi-note.update');
            Route::delete('/{noteId}/delete/{levelId}', [KPINoteController::class, 'destroy'])->name('admin.kpi-note.destroy');
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