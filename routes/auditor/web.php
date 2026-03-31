<?php

use App\Http\Controllers\Auditor\AuditSMPScoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Bujp\AssesmentController;
use App\Http\Controllers\Bujp\DashboardController;


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
Route::prefix('auditor')->group(function () {

    Route::middleware(['auth'])->group(function () {
        
        Route::prefix('audit-smp-score')->group(function () {
            Route::get('/', [AuditSMPScoreController::class, 'index'])->name('auditor.audit-smp-score.index');
            Route::get('/{audit}', [AuditSMPScoreController::class, 'show'])->name('auditor.audit-smp-score.show');
            Route::put('/{audit_score}/update', [AuditSMPScoreController::class, 'updateEvidence'])->name('auditor.audit-smp-score.update');
            Route::put('/{audit_score}/update-achievement', [AuditSMPScoreController::class, 'updateAchievement'])->name('auditor.audit-smp-score.update-achievement');
            Route::post('/{audit}/send', [AuditSMPScoreController::class, 'send'])->name('auditor.audit-smp-score.send');
        });
        
    });


});