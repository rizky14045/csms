<?php

use App\Http\Controllers\User\AgreementExternalController;
use App\Http\Controllers\User\AssesmentController;
use App\Http\Controllers\User\SecurepediaController;
use App\Http\Controllers\User\AttributeController;
use App\Http\Controllers\User\AuditSMPScoreController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\FaqController;
use App\Http\Controllers\User\FasumController;
use App\Http\Controllers\User\KeamananController;
use App\Http\Controllers\User\ListWorkController;
use App\Http\Controllers\User\MainSecurityProgramController;
use App\Http\Controllers\User\MarturityController;
use App\Http\Controllers\User\MonthlyAudit\AGHTController;
use App\Http\Controllers\User\MonthlyAudit\FormAttributeController;
use App\Http\Controllers\User\MonthlyAudit\FormForeignWorkerController;
use App\Http\Controllers\User\MonthlyAudit\FormFormulirController;
use App\Http\Controllers\User\MonthlyAudit\FormSecurityProgramController;
use App\Http\Controllers\User\MonthlyAudit\FormVulnerabilityExternalController;
use App\Http\Controllers\User\MonthlyAudit\FormVulnerabilityInternalController;
use App\Http\Controllers\User\MonthlyAudit\MonthlyWorkerSumController;
use App\Http\Controllers\User\MonthlyAudit\PenyerapanAnggaranController;
use App\Http\Controllers\User\MonthlyAudit\RealizationProgramController;
use App\Http\Controllers\User\MonthlyAudit\SecurityFormController;
use App\Http\Controllers\User\MonthlyAuditController;
use App\Http\Controllers\User\PraqualificationController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ResponsiblePersonController;
use App\Http\Controllers\User\SecurityController;
use App\Http\Controllers\User\SecurityExternalController;
use App\Http\Controllers\User\SecurityProgramController;
use App\Http\Controllers\User\VendorController;
use App\Http\Controllers\User\WorkerSumController;
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
Route::prefix('user')->group(function () {
    Route::get('/faq', [FaqController::class, 'index'])->name('user.faq.index');


    Route::middleware(['auth'])->group(function () {
        
        // Route::get('/home', [DashboardController::class, 'index'])->name('user.home.index');

        // Route::get('/change-password', [ChangePasswordController::class, 'changePassword'])->name('user.changePassword');
        // Route::patch('/update-password', [ChangePasswordController::class, 'updatePassword'])->name('user.updatePassword');

        Route::prefix('assesment')->group(function () {
            Route::get('/', [AssesmentController::class, 'index'])->name('user.assesment.index');
            // Route::get('/create', [AssesmentController::class, 'create'])->name('user.assesment.create');
            // Route::get('/edit', [AssesmentController::class, 'edit'])->name('user.assesment.edit');
            Route::get('/{assesment}/show', [AssesmentController::class, 'show'])->name('user.assesment.show');
            Route::get('/{assesment}/preview', [AssesmentController::class, 'preview'])->name('user.assesment.preview');
            Route::get('/{assesment}/report', [AssesmentController::class, 'report'])->name('user.assesment.report');
            Route::patch('/{assesment}/send', [AssesmentController::class, 'send'])->name('user.assesment.send');
            Route::patch('/{assesment}/revision', [AssesmentController::class, 'revision'])->name('user.assesment.revision');
            Route::patch('/update-question/{question}', [AssesmentController::class, 'updateQuestion'])->name('user.assesment.updateQuestion');
            // Route::patch('/revision-question/{question}', [AssesmentController::class, 'revisionQuestion'])->name('user.assesment.revisionQuestion');
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('user.profile.index');
        });

        
        Route::prefix('audit-smp-score')->group(function () {
            Route::get('/', [AuditSMPScoreController::class, 'index'])->name('user.audit-smp-score.index');
            Route::get('/create', [AuditSMPScoreController::class, 'create'])->name('user.audit-smp-score.create');
            Route::post('/', [AuditSMPScoreController::class, 'store'])->name('user.audit-smp-score.store');
            Route::get('/{audit}', [AuditSMPScoreController::class, 'show'])->name('user.audit-smp-score.show');
            Route::put('/{audit}/send', [AuditSMPScoreController::class, 'send'])->name('user.audit-smp-score.send');
            Route::put('/{audit_score}/update-self-audit', [AuditSMPScoreController::class, 'updateSelfAudit'])->name('user.audit-smp-score.update-self-audit');
            Route::put('/{audit_score}/update', [AuditSMPScoreController::class, 'updateEvidence'])->name('user.audit-smp-score.update');
        });
    
        Route::prefix('monthly-audit')->group(function () {
            Route::get('/', [MonthlyAuditController::class, 'index'])->name('user.monthly-audit.index');
            Route::get('/create', [MonthlyAuditController::class, 'create'])->name('user.monthly-audit.create');
            Route::post('/store', [MonthlyAuditController::class, 'store'])->name('user.monthly-audit.store');
            Route::get('/edit', [MonthlyAuditController::class, 'edit'])->name('user.monthly-audit.edit');
            Route::get('/show/{monthlyId}', [MonthlyAuditController::class, 'show'])->name('user.monthly-audit.show');
            Route::patch('/send/{monthlyId}', [MonthlyAuditController::class, 'sendReport'])->name('user.monthly-audit.send');
            Route::delete('/destroy/{monthlyId}', [MonthlyAuditController::class, 'destroy'])->name('user.monthly-audit.destroy');

            Route::get('/worker-sum/{monthlyId}', [MonthlyWorkerSumController::class, 'index'])->name('user.monthly-audit.worker-sum.index');

            Route::middleware(['monthly-take-over'])->group(function () {    
                Route::get('/form-formulir/{monthlyId}', [FormFormulirController::class, 'index'])->name('user.monthly-audit.form-formulir.index');
                Route::post('/form-formulir/{monthlyId}', [FormFormulirController::class, 'saveFormulir'])->name('user.monthly-audit.form-formulir.saveFormulir');
                Route::put('/form-formulir/{monthlyId}', [FormFormulirController::class, 'updateGangguan'])->name('user.monthly-audit.form-formulir.updateGangguan');

                Route::get('/security-form/{monthlyId}', [SecurityFormController::class, 'index'])->name('user.monthly-audit.security-form.index');
                Route::post('/security-form/{monthlyId}/upload/{formId}', [SecurityFormController::class, 'upload'])->name('user.monthly-audit.security-form.upload');

                //AGHT
                Route::get('/aght/{monthlyId}', [AGHTController::class, 'index'])->name('user.monthly-audit.aght.index');
                Route::get('/aght/{monthlyId}/create', [AGHTController::class, 'create'])->name('user.monthly-audit.aght.create');
                Route::post('/aght/{monthlyId}/create', [AGHTController::class, 'store'])->name('user.monthly-audit.aght.store');
                Route::get('/aght/{monthlyId}/edit/{aghtId}', [AGHTController::class, 'edit'])->name('user.monthly-audit.aght.edit');
                Route::patch('/aght/{monthlyId}/update/{aghtId}', [AGHTController::class, 'update'])->name('user.monthly-audit.aght.update');
                Route::delete('/aght/{monthlyId}/destroy/{aghtId}', [AGHTController::class, 'destroy'])->name('user.monthly-audit.aght.destroy');


                Route::get('/penyerapan-anggaran/{monthlyId}', [PenyerapanAnggaranController::class, 'index'])->name('user.monthly-audit.penyerapan-anggaran.index');
                Route::get('/penyerapan-anggaran/{monthlyId}/create', [PenyerapanAnggaranController::class, 'create'])->name('user.monthly-audit.penyerapan-anggaran.create');
                Route::post('/penyerapan-anggaran/{monthlyId}/create', [PenyerapanAnggaranController::class, 'store'])->name('user.monthly-audit.penyerapan-anggaran.store');
                Route::get('/penyerapan-anggaran/{monthlyId}/edit/{anggaranId}', [PenyerapanAnggaranController::class, 'edit'])->name('user.monthly-audit.penyerapan-anggaran.edit');
                Route::patch('/penyerapan-anggaran/{monthlyId}/update/{anggaranId}', [PenyerapanAnggaranController::class, 'update'])->name('user.monthly-audit.penyerapan-anggaran.update');
                Route::delete('/penyerapan-anggaran/{monthlyId}/destroy/{anggaranId}', [PenyerapanAnggaranController::class, 'destroy'])->name('user.monthly-audit.penyerapan-anggaran.destroy');

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

        Route::prefix('maturity')->group(function () {
            Route::get('/', [MarturityController::class, 'index'])->name('user.marturity.index');
            Route::get('/create', [MarturityController::class, 'create'])->name('user.marturity.create');
            Route::post('/store', [MarturityController::class, 'store'])->name('user.marturity.store');
            // Route::get('/edit/{marturity}', [MarturityController::class, 'edit'])->name('user.marturity.edit');
            // Route::patch('/edit/{marturity}', [MarturityController::class, 'update'])->name('user.marturity.update');
            Route::get('/show/{marturity}', [MarturityController::class, 'show'])->name('user.marturity.show');
            Route::get('/preview/{marturity}', [MarturityController::class, 'preview'])->name('user.marturity.preview');
            Route::patch('/send/{marturity}', [MarturityController::class, 'send'])->name('user.marturity.send');
            Route::patch('{marturity}/upload-note/{areaId}/{note}', [MarturityController::class, 'uploadNote'])->name('user.marturity.uploadNote');
            Route::post('{marturity}/upload-level/{level}', [MarturityController::class, 'uploadLevel'])->name('user.marturity.uploadLevel');
            Route::delete('{marturity}/delete-level-file/{level}', [MarturityController::class, 'deleteLevelFile'])->name('user.marturity.deleteLevelFile');
            // Route::delete('/destroy/{marturity}', [MarturityController::class, 'destroy'])->name('user.marturity.destroy');
        });

        
        
        Route::prefix('vendor')->group(function () {
            Route::get('/', [VendorController::class, 'index'])->name('user.vendor.index');
            Route::get('/create', [VendorController::class, 'create'])->name('user.vendor.create');
            Route::post('/store', [VendorController::class, 'store'])->name('user.vendor.store');
            Route::get('/edit/{id}', [VendorController::class, 'edit'])->name('user.vendor.edit');
            Route::patch('/edit/{id}', [VendorController::class, 'update'])->name('user.vendor.update');
            Route::delete('/delete/{id}', [VendorController::class, 'destroy'])->name('user.vendor.destroy');
        });
        

        
        
        







        Route::prefix('attribute')->group(function () {
            Route::get('/', [AttributeController::class, 'index'])->name('user.attribute.index');
            Route::get('/create', [AttributeController::class, 'create'])->name('user.attribute.create');
            Route::post('/store', [AttributeController::class, 'store'])->name('user.attribute.store');
            Route::get('/{attribute}/edit', [AttributeController::class, 'edit'])->name('user.attribute.edit');
            Route::patch('/{attribute}/edit', [AttributeController::class, 'update'])->name('user.attribute.update');
            Route::delete('/{attribute}/delete', [AttributeController::class, 'destroy'])->name('user.attribute.destroy');
        });

        Route::prefix('security')->group(function () {
            Route::get('/', [SecurityController::class, 'index'])->name('user.security.index');
            Route::get('/create', [SecurityController::class, 'create'])->name('user.security.create');
            Route::post('/store', [SecurityController::class, 'store'])->name('user.security.store');
            Route::get('/{security}/edit', [SecurityController::class, 'edit'])->name('user.security.edit');
            Route::patch('/{security}/edit', [SecurityController::class, 'update'])->name('user.security.update');
            Route::delete('/{security}/delete', [SecurityController::class, 'destroy'])->name('user.security.destroy');
        });

        Route::get('/worker-sum', [WorkerSumController::class, 'index'])->name('user.worker-sum.index');

        Route::get('/responsible-person/create', [ResponsiblePersonController::class, 'create'])->name('user.responsible-person.create');
        Route::post('/responsible-person/create', [ResponsiblePersonController::class, 'store'])->name('user.responsible-person.store');
        Route::get('/responsible-person/{person}/edit', [ResponsiblePersonController::class, 'edit'])->name('user.responsible-person.edit');
        Route::patch('/responsible-person/{person}/update', [ResponsiblePersonController::class, 'update'])->name('user.responsible-person.update');
        Route::delete('/responsible-person/{person}/destroy', [ResponsiblePersonController::class, 'destroy'])->name('user.responsible-person.destroy');

        Route::get('/security-external/create', [SecurityExternalController::class, 'create'])->name('user.security-external.create');
        Route::post('/security-external/create', [SecurityExternalController::class, 'store'])->name('user.security-external.store');
        Route::get('/security-external/{security}/edit', [SecurityExternalController::class, 'edit'])->name('user.security-external.edit');
        Route::patch('/security-external/{security}/update', [SecurityExternalController::class, 'update'])->name('user.security-external.update');
        Route::delete('/security-external/{security}/destroy', [SecurityExternalController::class, 'destroy'])->name('user.security-external.destroy');

        Route::get('/agreement-external/create', [AgreementExternalController::class, 'create'])->name('user.agreement-external.create');
        Route::post('/agreement-external/create', [AgreementExternalController::class, 'store'])->name('user.agreement-external.store');
        Route::get('/agreement-external/{agreement}/edit', [AgreementExternalController::class, 'edit'])->name('user.agreement-external.edit');
        Route::patch('/agreement-external/{agreement}/update', [AgreementExternalController::class, 'update'])->name('user.agreement-external.update');
        Route::delete('/agreement-external/{agreement}/destroy', [AgreementExternalController::class, 'destroy'])->name('user.agreement-external.destroy');

        Route::prefix('security-program')->group(function () {
            Route::get('/', [SecurityProgramController::class, 'index'])->name('user.security-program.index');
            Route::get('/create', [SecurityProgramController::class, 'create'])->name('user.security-program.create');
            Route::post('/store', [SecurityProgramController::class, 'store'])->name('user.security-program.store');
            Route::get('/{program}/edit', [SecurityProgramController::class, 'edit'])->name('user.security-program.edit');
            Route::patch('/{program}/update', [SecurityProgramController::class, 'update'])->name('user.security-program.update');
            Route::delete('/{program}/destroy', [SecurityProgramController::class, 'destroy'])->name('user.security-program.destroy');
        });
     

        Route::prefix('main-security-program')->group(function () {
            Route::get('/{program}', [MainSecurityProgramController::class, 'index'])->name('user.main-security-program.index');
            Route::get('/visual/{program}', [MainSecurityProgramController::class, 'visual'])->name('user.main-security-program.visual');
            Route::get('/create/{program}', [MainSecurityProgramController::class, 'create'])->name('user.main-security-program.create');
            Route::post('/store/{program}', [MainSecurityProgramController::class, 'store'])->name('user.main-security-program.store');
            Route::get('/{program}/{main}/edit', [MainSecurityProgramController::class, 'edit'])->name('user.main-security-program.edit');
            Route::patch('/{program}/{main}/update', [MainSecurityProgramController::class, 'update'])->name('user.main-security-program.update');
            Route::delete('/{program}/{main}/destroy', [MainSecurityProgramController::class, 'destroy'])->name('user.main-security-program.destroy');
        });

        Route::prefix('keamanan')->group(function () {
            Route::get('/', [KeamananController::class, 'index'])->name('user.keamanan.index');
            Route::get('/create', [KeamananController::class, 'create'])->name('user.keamanan.create');
            Route::post('/store', [KeamananController::class, 'store'])->name('user.keamanan.store');
            // Route::get('/{kpi}/edit', [KeamananController::class, 'edit'])->name('user.keamanan.edit');
            // Route::patch('/{kpi}/update', [KeamananController::class, 'update'])->name('user.keamanan.update');
            Route::get('/{kpi}/show', [KeamananController::class, 'show'])->name('user.keamanan.show');
            Route::get('/{kpi}/preview', [KeamananController::class, 'preview'])->name('user.keamanan.preview');
            Route::patch('/{kpi}/send', [KeamananController::class, 'send'])->name('user.keamanan.send');
            Route::patch('{kpi}/upload-note/{areaId}/{note}', [KeamananController::class, 'uploadNote'])->name('user.keamanan.uploadNote');
            Route::post('{kpi}/upload-level/{level}', [KeamananController::class, 'uploadLevel'])->name('user.keamanan.uploadLevel');
            // Route::delete('/{kpi}/destroy', [KeamananController::class, 'destroy'])->name('user.keamanan.destroy');
        });

        Route::prefix('fasum')->group(function () {
            Route::get('/', [FasumController::class, 'index'])->name('user.fasum.index');
            Route::get('/create', [FasumController::class, 'create'])->name('user.fasum.create');
            Route::post('/store', [FasumController::class, 'store'])->name('user.fasum.store');
            Route::get('/{fasum}/edit', [FasumController::class, 'edit'])->name('user.fasum.edit');
            Route::patch('/{fasum}/update', [FasumController::class, 'update'])->name('user.fasum.update');
            Route::delete('/{fasum}/destroy', [FasumController::class, 'destroy'])->name('user.fasum.destroy');
        });

        Route::prefix('securepedia')->group(function () {
            Route::get('/', [SecurepediaController::class, 'index'])->name('user.securepedia.index');
        });
    });


});