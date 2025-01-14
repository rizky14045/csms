<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\FaqController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\KeamananController;
use App\Http\Controllers\User\ListWorkController;
use App\Http\Controllers\User\SecurityController;
use App\Http\Controllers\User\AssesmentController;
use App\Http\Controllers\User\AttributeController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\MarturityController;
use App\Http\Controllers\User\MonthlyAuditController;
use App\Http\Controllers\User\SecurityProgramController;
use App\Http\Controllers\User\PraqualificationController;
use App\Http\Controllers\User\MonthlyAudit\AGHTController;
use App\Http\Controllers\User\MainSecurityProgramController;
use App\Http\Controllers\User\MonthlyAudit\WorkerSumController;
use App\Http\Controllers\User\MonthlyAudit\FormFormulirController;
use App\Http\Controllers\User\MonthlyAudit\SecurityFormController;
use App\Http\Controllers\User\MonthlyAudit\FormAttributeController;
use App\Http\Controllers\User\MonthlyAudit\SecurityExternalController;
use App\Http\Controllers\User\MonthlyAudit\AgreementExternalController;
use App\Http\Controllers\User\MonthlyAudit\FormForeignWorkerController;
use App\Http\Controllers\User\MonthlyAudit\ResponsiblePersonController;
use App\Http\Controllers\User\MonthlyAudit\RealizationProgramController;
use App\Http\Controllers\User\MonthlyAudit\FormSecurityProgramController;
use App\Http\Controllers\User\MonthlyAudit\FormVulnerabilityExternalController;
use App\Http\Controllers\User\MonthlyAudit\FormVulnerabilityInternalController;
use App\Http\Controllers\User\ChangePasswordController;

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
    
    Route::get('/login', [AuthController::class, 'getLogin'])->name('user.login')->middleware('guest');
    Route::post('/login', [AuthController::class, 'login'])->name('user.getLogin')->middleware('guest');
    Route::get('/faq', [FaqController::class, 'index'])->name('user.faq.index');


    Route::middleware(['auth'])->group(function () {
        
        Route::get('/home', [DashboardController::class, 'index'])->name('user.home.index');
        Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');

        Route::get('/change-password', [ChangePasswordController::class, 'changePassword'])->name('user.changePassword');
        Route::patch('/update-password', [ChangePasswordController::class, 'updatePassword'])->name('user.updatePassword');

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
            Route::post('/store', [MonthlyAuditController::class, 'store'])->name('user.monthly-audit.store');
            Route::get('/edit', [MonthlyAuditController::class, 'edit'])->name('user.monthly-audit.edit');
            Route::get('/show', [MonthlyAuditController::class, 'show'])->name('user.monthly-audit.show');
            
            Route::middleware(['monthly-take-over'])->group(function () {    
                Route::get('/form-formulir/{monthlyId}', [FormFormulirController::class, 'index'])->name('user.monthly-audit.form-formulir.index');
                Route::post('/form-formulir/{monthlyId}', [FormFormulirController::class, 'saveFormulir'])->name('user.monthly-audit.form-formulir.saveFormulir');
                Route::get('/worker-sum/{monthlyId}', [WorkerSumController::class, 'index'])->name('user.monthly-audit.worker-sum.index');

                Route::get('/worker-sum/{monthlyId}', [WorkerSumController::class, 'index'])->name('user.monthly-audit.worker-sum.index');
                
                Route::get('/responsible-person/{monthlyId}/create', [ResponsiblePersonController::class, 'create'])->name('user.monthly-audit.responsible-person.create');
                Route::post('/responsible-person/{monthlyId}/create', [ResponsiblePersonController::class, 'store'])->name('user.monthly-audit.responsible-person.store');
                Route::get('/responsible-person/{monthlyId}/edit/{personId}', [ResponsiblePersonController::class, 'edit'])->name('user.monthly-audit.responsible-person.edit');
                Route::patch('/responsible-person/{monthlyId}/update/{personId}', [ResponsiblePersonController::class, 'update'])->name('user.monthly-audit.responsible-person.update');
                Route::delete('/responsible-person/{monthlyId}/destroy/{personId}', [ResponsiblePersonController::class, 'destroy'])->name('user.monthly-audit.responsible-person.destroy');
                
                Route::get('/security-external/{monthlyId}/create', [SecurityExternalController::class, 'create'])->name('user.monthly-audit.security-external.create');
                Route::post('/security-external/{monthlyId}/create', [SecurityExternalController::class, 'store'])->name('user.monthly-audit.security-external.store');
                Route::get('/security-external/{monthlyId}/edit/{securityId}', [SecurityExternalController::class, 'edit'])->name('user.monthly-audit.security-external.edit');
                Route::patch('/security-external/{monthlyId}/update/{securityId}', [SecurityExternalController::class, 'update'])->name('user.monthly-audit.security-external.update');
                Route::delete('/security-external/{monthlyId}/destroy/{securityId}', [SecurityExternalController::class, 'destroy'])->name('user.monthly-audit.security-external.destroy');

                Route::get('/agreement-external/{monthlyId}/create', [AgreementExternalController::class, 'create'])->name('user.monthly-audit.agreement-external.create');
                Route::post('/agreement-external/{monthlyId}/create', [AgreementExternalController::class, 'store'])->name('user.monthly-audit.agreement-external.store');
                Route::get('/agreement-external/{monthlyId}/edit/{agreementId}', [AgreementExternalController::class, 'edit'])->name('user.monthly-audit.agreement-external.edit');
                Route::patch('/agreement-external/{monthlyId}/update/{agreementId}', [AgreementExternalController::class, 'update'])->name('user.monthly-audit.agreement-external.update');
                Route::delete('/agreement-external/{monthlyId}/destroy/{agreementId}', [AgreementExternalController::class, 'destroy'])->name('user.monthly-audit.agreement-external.destroy');

                Route::get('/security-form/{monthlyId}', [SecurityFormController::class, 'index'])->name('user.monthly-audit.security-form.index');
                Route::post('/security-form/{monthlyId}/upload/{formId}', [SecurityFormController::class, 'upload'])->name('user.monthly-audit.security-form.upload');

                //AGHT
                Route::get('/aght/{monthlyId}', [AGHTController::class, 'index'])->name('user.monthly-audit.aght.index');
                Route::get('/aght/{monthlyId}/create', [AGHTController::class, 'create'])->name('user.monthly-audit.aght.create');
                Route::post('/aght/{monthlyId}/create', [AGHTController::class, 'store'])->name('user.monthly-audit.aght.store');
                Route::get('/aght/{monthlyId}/edit/{aghtId}', [AGHTController::class, 'edit'])->name('user.monthly-audit.aght.edit');
                Route::patch('/aght/{monthlyId}/update/{aghtId}', [AGHTController::class, 'update'])->name('user.monthly-audit.aght.update');
                Route::delete('/aght/{monthlyId}/destroy/{aghtId}', [AGHTController::class, 'destroy'])->name('user.monthly-audit.aght.destroy');

                //Form Security Program
                Route::get('/security-program/{monthlyId}', [FormSecurityProgramController::class, 'index'])->name('user.monthly-audit.security-program.index');
                Route::get('/security-program-visual/{monthlyId}', [FormSecurityProgramController::class, 'visual'])->name('user.monthly-audit.security-program.visual');
                Route::get('/realization-program/{monthlyId}/realization/{programId}', [RealizationProgramController::class, 'index'])->name('user.monthly-audit.realization-program.index');
                Route::get('/realization-program/{monthlyId}/realization/{programId}/edit/{mainId}', [RealizationProgramController::class, 'edit'])->name('user.monthly-audit.realization-program.edit');
                Route::patch('/realization-program/{monthlyId}/realization/{programId}/edit/{mainId}', [RealizationProgramController::class, 'update'])->name('user.monthly-audit.realization-program.update');
                Route::get('/realization-program/{monthlyId}/realization/{programId}/visual', [RealizationProgramController::class, 'visual'])->name('user.monthly-audit.realization-program.visual');
        
                Route::get('/form-attribute/{monthlyId}', [FormAttributeController::class, 'index'])->name('user.monthly-audit.form-attribute.index');
                Route::post('/form-attribute/{monthlyId}/attribute', [FormAttributeController::class, 'saveAttribute'])->name('user.monthly-audit.form-attribute.saveAttribute');
                Route::post('/form-attribute/{monthlyId}/administration', [FormAttributeController::class, 'saveAdministration'])->name('user.monthly-audit.form-attribute.saveAdministration');
                Route::post('/form-attribute/{monthlyId}/sarana', [FormAttributeController::class, 'saveSarana'])->name('user.monthly-audit.form-attribute.saveSarana');

                //TKA
                Route::get('/form-foreign-worker/{monthlyId}', [FormForeignWorkerController::class, 'index'])->name('user.monthly-audit.form-foreign-worker.index');
                Route::get('/form-foreign-worker/{monthlyId}/create', [FormForeignWorkerController::class, 'create'])->name('user.monthly-audit.form-foreign-worker.create');
                Route::post('/form-foreign-worker/{monthlyId}/create', [FormForeignWorkerController::class, 'store'])->name('user.monthly-audit.form-foreign-worker.store');
                Route::get('/form-foreign-worker/{monthlyId}/edit/{foreignId}', [FormForeignWorkerController::class, 'edit'])->name('user.monthly-audit.form-foreign-worker.edit');
                Route::patch('/form-foreign-worker/{monthlyId}/update/{foreignId}', [FormForeignWorkerController::class, 'update'])->name('user.monthly-audit.form-foreign-worker.update');
                Route::delete('/form-foreign-worker/{monthlyId}/destroy/{foreignId}', [FormForeignWorkerController::class, 'destroy'])->name('user.monthly-audit.form-foreign-worker.destroy');

                //kerawaanan internal
                Route::get('/form-vulnerability-internal/{monthlyId}', [FormVulnerabilityInternalController::class, 'index'])->name('user.monthly-audit.form-vulnerability-internal.index');
                Route::post('/form-vulnerability-internal/{monthlyId}', [FormVulnerabilityInternalController::class, 'save'])->name('user.monthly-audit.form-vulnerability-internal.save');
                //kerawanan eksternal
                Route::get('/form-vulnerability-external/{monthlyId}', [FormVulnerabilityExternalController::class, 'index'])->name('user.monthly-audit.form-vulnerability-external.index');
                Route::post('/form-vulnerability-external/{monthlyId}', [FormVulnerabilityExternalController::class, 'save'])->name('user.monthly-audit.form-vulnerability-external.save');
            });

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
        
        Route::prefix('attribute')->group(function () {
            Route::get('/', [AttributeController::class, 'index'])->name('user.attribute.index');
            Route::get('/create', [AttributeController::class, 'create'])->name('user.attribute.create');
            Route::post('/store', [AttributeController::class, 'store'])->name('user.attribute.store');
            Route::get('/edit/{id}', [AttributeController::class, 'edit'])->name('user.attribute.edit');
            Route::patch('/edit/{id}', [AttributeController::class, 'update'])->name('user.attribute.update');
            Route::delete('/delete/{id}', [AttributeController::class, 'destroy'])->name('user.attribute.destroy');
        });

        Route::prefix('security')->group(function () {
            Route::get('/', [SecurityController::class, 'index'])->name('user.security.index');
            Route::get('/create', [SecurityController::class, 'create'])->name('user.security.create');
            Route::post('/store', [SecurityController::class, 'store'])->name('user.security.store');
            Route::get('/edit/{id}', [SecurityController::class, 'edit'])->name('user.security.edit');
            Route::patch('/edit/{id}', [SecurityController::class, 'update'])->name('user.security.update');
            Route::delete('/delete/{id}', [SecurityController::class, 'destroy'])->name('user.security.destroy');
        });
        Route::prefix('security-program')->group(function () {
            Route::get('/', [SecurityProgramController::class, 'index'])->name('user.security-program.index');
            Route::get('/create', [SecurityProgramController::class, 'create'])->name('user.security-program.create');
            Route::post('/store', [SecurityProgramController::class, 'store'])->name('user.security-program.store');
            Route::get('/edit/{id}', [SecurityProgramController::class, 'edit'])->name('user.security-program.edit');
            Route::patch('/edit/{id}', [SecurityProgramController::class, 'update'])->name('user.security-program.update');
            Route::delete('/delete/{id}', [SecurityProgramController::class, 'destroy'])->name('user.security-program.destroy');
        });
        Route::prefix('main-security-program')->group(function () {
            Route::get('/{programId}', [MainSecurityProgramController::class, 'index'])->name('user.main-security-program.index');
            Route::get('/visual/{programId}', [MainSecurityProgramController::class, 'visual'])->name('user.main-security-program.visual');
            Route::get('/create/{programId}', [MainSecurityProgramController::class, 'create'])->name('user.main-security-program.create');
            Route::post('/store/{programId}', [MainSecurityProgramController::class, 'store'])->name('user.main-security-program.store');
            Route::get('/{programId}/edit/{id}', [MainSecurityProgramController::class, 'edit'])->name('user.main-security-program.edit');
            Route::patch('/{programId}/edit/{id}', [MainSecurityProgramController::class, 'update'])->name('user.main-security-program.update');
            Route::delete('/{programId}/delete/{id}', [MainSecurityProgramController::class, 'destroy'])->name('user.main-security-program.destroy');
        });
    });


});