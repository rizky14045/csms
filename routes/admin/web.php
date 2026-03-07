<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AssesmentController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AuditSMPController;
use App\Http\Controllers\Admin\CategoryAssesmentController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KeamananController;
use App\Http\Controllers\Admin\KPIAreaController;
use App\Http\Controllers\Admin\KPILevelController;
use App\Http\Controllers\Admin\KPINoteController;
use App\Http\Controllers\Admin\KPISubAreaController;
use App\Http\Controllers\Admin\LevelAssesmentController;
use App\Http\Controllers\Admin\MarturityAreaController;
use App\Http\Controllers\Admin\MarturityController;
use App\Http\Controllers\Admin\MarturityLevelController;
use App\Http\Controllers\Admin\MarturityNoteController;
use App\Http\Controllers\Admin\MarturitySubAreaController;
use App\Http\Controllers\Admin\MonthlyAuditController;
use App\Http\Controllers\Admin\QuestionAssesmentController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\VulnerabilityController;
use Illuminate\Support\Facades\Route;

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
    Route::middleware(['auth','auth.admin'])->group(function () {
        
        Route::get('/home', [DashboardController::class, 'index'])->name('admin.home.index');
        
        Route::get('/change-password', [ChangePasswordController::class, 'changePassword'])->name('admin.changePassword');
        // Route::patch('/update-password', [ChangePasswordController::class, 'updatePassword'])->name('admin.updatePassword');
        Route::prefix('assesment')->group(function () {
            Route::get('/', [AssesmentController::class, 'index'])->name('admin.assesment.index');
            Route::get('/create', [AssesmentController::class, 'create'])->name('admin.assesment.create');
            Route::get('/edit', [AssesmentController::class, 'edit'])->name('admin.assesment.edit');
            Route::get('/show/{assesmentId}', [AssesmentController::class, 'show'])->name('admin.assesment.show');
            Route::get('/report/{assesmentId}', [AssesmentController::class, 'report'])->name('admin.assesment.report');
        });
        Route::prefix('monthly-audit')->group(function () {
            Route::get('/', [MonthlyAuditController::class, 'index'])->name('admin.monthly-audit.index');
            Route::get('/create', [MonthlyAuditController::class, 'create'])->name('admin.monthly-audit.create');
            Route::get('/edit', [MonthlyAuditController::class, 'edit'])->name('admin.monthly-audit.edit');
            Route::get('/show/{monthlyId}', [MonthlyAuditController::class, 'show'])->name('admin.monthly-audit.show');
        });
        Route::prefix('maturity')->group(function () {
            Route::get('/', [MarturityController::class, 'index'])->name('admin.marturity.index');
            Route::get('/show/{marturityId}', [MarturityController::class, 'show'])->name('admin.marturity.show');
        });

        Route::prefix('keamanan')->group(function () {
            Route::get('/', [KeamananController::class, 'index'])->name('admin.keamanan.index');
            Route::get('/show/{keamananId}', [KeamananController::class, 'show'])->name('admin.keamanan.show');
        });
        
        // Route::prefix('unit')->group(function () {
        //     Route::get('/', [UnitController::class, 'index'])->name('admin.unit.index');
        //     Route::get('/create', [UnitController::class, 'create'])->name('admin.unit.create');
        //     Route::post('/store', [UnitController::class, 'store'])->name('admin.unit.store');
        //     Route::get('/edit/{id}', [UnitController::class, 'edit'])->name('admin.unit.edit');
        //     Route::patch('/edit/{id}', [UnitController::class, 'update'])->name('admin.unit.update');
        //     Route::delete('/delete/{id}', [UnitController::class, 'destroy'])->name('admin.unit.destroy');
        // });
        // Route::prefix('admin')->group(function () {
        //     Route::get('/', [AdminController::class, 'index'])->name('admin.admin.index');
        //     Route::get('/create', [AdminController::class, 'create'])->name('admin.admin.create');
        //     Route::post('/store', [AdminController::class, 'store'])->name('admin.admin.store');
        //     Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('admin.admin.edit');
        //     Route::patch('/edit/{id}', [AdminController::class, 'update'])->name('admin.admin.update');
        //     Route::delete('/delete/{id}', [AdminController::class, 'destroy'])->name('admin.admin.destroy');
        // });

        Route::prefix('vulnerability')->group(function () {
            Route::get('/', [VulnerabilityController::class, 'index'])->name('admin.vulnerability.index');
            Route::get('/create', [VulnerabilityController::class, 'create'])->name('admin.vulnerability.create');
            Route::post('/store', [VulnerabilityController::class, 'store'])->name('admin.vulnerability.store');
            Route::get('/{vulnerability}/edit', [VulnerabilityController::class, 'edit'])->name('admin.vulnerability.edit');
            Route::patch('/{vulnerability}/edit', [VulnerabilityController::class, 'update'])->name('admin.vulnerability.update');
            Route::delete('/{vulnerability}/delete', [VulnerabilityController::class, 'destroy'])->name('admin.vulnerability.destroy');
        });

        Route::prefix('attribute')->group(function () {
            Route::get('/', [AttributeController::class, 'index'])->name('admin.attribute.index');
            Route::get('/create', [AttributeController::class, 'create'])->name('admin.attribute.create');
            Route::post('/store', [AttributeController::class, 'store'])->name('admin.attribute.store');
            Route::get('/{attribute}/edit', [AttributeController::class, 'edit'])->name('admin.attribute.edit');
            Route::patch('/{attribute}/edit', [AttributeController::class, 'update'])->name('admin.attribute.update');
            Route::delete('/{attribute}/delete', [AttributeController::class, 'destroy'])->name('admin.attribute.destroy');
        });

        Route::prefix('category-assesment')->group(function () {
            Route::get('/', [CategoryAssesmentController::class, 'index'])->name('admin.category-assesment.index');
            Route::get('/create', [CategoryAssesmentController::class, 'create'])->name('admin.category-assesment.create');
            Route::post('/store', [CategoryAssesmentController::class, 'store'])->name('admin.category-assesment.store');
            Route::get('/{category_assesment}/edit', [CategoryAssesmentController::class, 'edit'])->name('admin.category-assesment.edit');
            Route::patch('/{category_assesment}/edit', [CategoryAssesmentController::class, 'update'])->name('admin.category-assesment.update');
            Route::delete('/{category_assesment}/delete', [CategoryAssesmentController::class, 'destroy'])->name('admin.category-assesment.destroy');
        });

        Route::prefix('question-assesment')->group(function () {
            Route::get('/create/{category_assesment}', [QuestionAssesmentController::class, 'create'])->name('admin.question-assesment.create');
            Route::post('/store/{category_assesment}', [QuestionAssesmentController::class, 'store'])->name('admin.question-assesment.store');
            Route::get('{question_assesment}/edit/{category_assesment}', [QuestionAssesmentController::class, 'edit'])->name('admin.question-assesment.edit');
            Route::patch('{question_assesment}/edit/{category_assesment}', [QuestionAssesmentController::class, 'update'])->name('admin.question-assesment.update');
            Route::delete('{question_assesment}/delete/{category_assesment}', [QuestionAssesmentController::class, 'destroy'])->name('admin.question-assesment.destroy');
        });

        Route::prefix('level-assesment')->group(function () {
            Route::get('/create/{question_assesment}', [LevelAssesmentController::class, 'create'])->name('admin.level-assesment.create');
            Route::post('/store/{question_assesment}', [LevelAssesmentController::class, 'store'])->name('admin.level-assesment.store');
            Route::get('{level_assesment}/edit/{question_assesment}', [LevelAssesmentController::class, 'edit'])->name('admin.level-assesment.edit');
            Route::patch('{level_assesment}/edit/{question_assesment}', [LevelAssesmentController::class, 'update'])->name('admin.level-assesment.update');
            Route::delete('{level_assesment}/delete/{question_assesment}', [LevelAssesmentController::class, 'destroy'])->name('admin.level-assesment.destroy');
        });

        Route::prefix('marturity-area')->group(function () {
            Route::get('/', [MarturityAreaController::class, 'index'])->name('admin.marturity-area.index');
            Route::get('/create', [MarturityAreaController::class, 'create'])->name('admin.marturity-area.create');
            Route::post('/store', [MarturityAreaController::class, 'store'])->name('admin.marturity-area.store');
            Route::get('/{area}/edit', [MarturityAreaController::class, 'edit'])->name('admin.marturity-area.edit');
            Route::patch('/{area}/edit', [MarturityAreaController::class, 'update'])->name('admin.marturity-area.update');
            Route::delete('/{area}/delete', [MarturityAreaController::class, 'destroy'])->name('admin.marturity-area.destroy');
        });

        Route::prefix('marturity-sub-area')->group(function () {
            Route::get('/create/{area}', [MarturitySubAreaController::class, 'create'])->name('admin.marturity-sub-area.create');
            Route::post('/store/{area}', [MarturitySubAreaController::class, 'store'])->name('admin.marturity-sub-area.store');
            Route::get('/{sub_area}/edit/{area}', [MarturitySubAreaController::class, 'edit'])->name('admin.marturity-sub-area.edit');
            Route::patch('/{sub_area}/edit/{area}', [MarturitySubAreaController::class, 'update'])->name('admin.marturity-sub-area.update');
            Route::delete('/{sub_area}/delete/{area}', [MarturitySubAreaController::class, 'destroy'])->name('admin.marturity-sub-area.destroy');
        });

        Route::prefix('marturity-level')->group(function () {
            Route::get('/create/{sub_area}', [MarturityLevelController::class, 'create'])->name('admin.marturity-level.create');
            Route::post('/store/{sub_area}', [MarturityLevelController::class, 'store'])->name('admin.marturity-level.store');
            Route::get('/{level}/edit/{sub_area}', [MarturityLevelController::class, 'edit'])->name('admin.marturity-level.edit');
            Route::patch('/{level}/edit/{sub_area}', [MarturityLevelController::class, 'update'])->name('admin.marturity-level.update');
            Route::delete('/{level}/delete/{sub_area}', [MarturityLevelController::class, 'destroy'])->name('admin.marturity-level.destroy');
        });

        Route::prefix('marturity-note')->group(function () {
            Route::get('/create/{level}', [MarturityNoteController::class, 'create'])->name('admin.marturity-note.create');
            Route::post('/store/{level}', [MarturityNoteController::class, 'store'])->name('admin.marturity-note.store');
            Route::get('/{note}/edit/{level}', [MarturityNoteController::class, 'edit'])->name('admin.marturity-note.edit');
            Route::patch('/{note}/edit/{level}', [MarturityNoteController::class, 'update'])->name('admin.marturity-note.update');
            Route::delete('/{note}/delete/{level}', [MarturityNoteController::class, 'destroy'])->name('admin.marturity-note.destroy');
        });

        Route::prefix('kpi-area')->group(function () {
            Route::get('/', [KPIAreaController::class, 'index'])->name('admin.kpi-area.index');
            Route::get('/create', [KPIAreaController::class, 'create'])->name('admin.kpi-area.create');
            Route::post('/store', [KPIAreaController::class, 'store'])->name('admin.kpi-area.store');
            Route::get('/{area}/edit', [KPIAreaController::class, 'edit'])->name('admin.kpi-area.edit');
            Route::patch('/{area}/edit', [KPIAreaController::class, 'update'])->name('admin.kpi-area.update');
            Route::delete('/{area}/delete', [KPIAreaController::class, 'destroy'])->name('admin.kpi-area.destroy');
        });

        Route::prefix('kpi-sub-area')->group(function () {
            Route::get('/create/{area}', [KPISubAreaController::class, 'create'])->name('admin.kpi-sub-area.create');
            Route::post('/store/{area}', [KPISubAreaController::class, 'store'])->name('admin.kpi-sub-area.store');
            Route::get('/{sub_area}/edit/{area}', [KPISubAreaController::class, 'edit'])->name('admin.kpi-sub-area.edit');
            Route::patch('/{sub_area}/edit/{area}', [KPISubAreaController::class, 'update'])->name('admin.kpi-sub-area.update');
            Route::delete('/{sub_area}/delete/{area}', [KPISubAreaController::class, 'destroy'])->name('admin.kpi-sub-area.destroy');
        });

        Route::prefix('kpi-level')->group(function () {
            Route::get('/create/{sub_area}', [KPILevelController::class, 'create'])->name('admin.kpi-level.create');
            Route::post('/store/{sub_area}', [KPILevelController::class, 'store'])->name('admin.kpi-level.store');
            Route::get('/{level}/edit/{sub_area}', [KPILevelController::class, 'edit'])->name('admin.kpi-level.edit');
            Route::patch('/{level}/edit/{sub_area}', [KPILevelController::class, 'update'])->name('admin.kpi-level.update');
            Route::delete('/{level}/delete/{sub_area}', [KPILevelController::class, 'destroy'])->name('admin.kpi-level.destroy');
        });

        Route::prefix('kpi-note')->group(function () {
            Route::get('/create/{level}', [KPINoteController::class, 'create'])->name('admin.kpi-note.create');
            Route::post('/store/{level}', [KPINoteController::class, 'store'])->name('admin.kpi-note.store');
            Route::get('/{note}/edit/{level}', [KPINoteController::class, 'edit'])->name('admin.kpi-note.edit');
            Route::patch('/{note}/edit/{level}', [KPINoteController::class, 'update'])->name('admin.kpi-note.update');
            Route::delete('/{note}/delete/{level}', [KPINoteController::class, 'destroy'])->name('admin.kpi-note.destroy');
        });
        Route::prefix('audit-smp')->group(function () {
            Route::get('/', [AuditSMPController::class, 'index'])->name('admin.audit-smp.index');
            Route::get('/create', [AuditSMPController::class, 'create'])->name('admin.audit-smp.create');
            Route::post('/store', [AuditSMPController::class, 'store'])->name('admin.audit-smp.store');
            Route::get('/{audit}/edit', [AuditSMPController::class, 'edit'])->name('admin.audit-smp.edit');
            Route::patch('/{audit}/edit', [AuditSMPController::class, 'update'])->name('admin.audit-smp.update');
            Route::delete('/{audit}/delete', [AuditSMPController::class, 'destroy'])->name('admin.audit-smp.destroy');
            Route::get('/create/element/{auditId}', [AuditSMPController::class, 'createElement'])->name('admin.audit-smp.createElement');
            Route::get('/edit/element/{auditId}/{elementId}', [AuditSMPController::class, 'editElement'])->name('admin.audit-smp.editElement');
            Route::patch('/{auditId}/edit-element/{elementId}', [AuditSMPController::class, 'updateElement'])->name('admin.audit-smp.updateElement');
            Route::delete('/{auditId}/delete-element/{elementId}', [AuditSMPController::class, 'deleteElement'])->name('admin.audit-smp.deleteElement');
            Route::post('/store/element/{auditId}', [AuditSMPController::class, 'storeElement'])->name('admin.audit-smp.storeElement');
            Route::get('/create/evident/{auditId}', [AuditSMPController::class, 'createEvident'])->name('admin.audit-smp.createEvident');
            Route::post('/store/evident/{auditId}', [AuditSMPController::class, 'storeEvident'])->name('admin.audit-smp.storeEvident');
        });
    });

});