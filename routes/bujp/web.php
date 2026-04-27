<?php

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
Route::prefix('bujp')->group(function () {

    Route::middleware(['auth.vendor'])->group(function () {
        
        Route::get('/home', [DashboardController::class, 'index'])->name('bujp.home.index');

        Route::prefix('assesment')->group(function () {
            Route::get('/', [AssesmentController::class, 'index'])->name('bujp.assesment.index');
            Route::get('/create', [AssesmentController::class, 'create'])->name('bujp.assesment.create');
            Route::post('/create', [AssesmentController::class, 'store'])->name('bujp.assesment.store');
            Route::get('/{assesment}/edit', [AssesmentController::class, 'edit'])->name('bujp.assesment.edit');
            Route::patch('/{assesment}/update', [AssesmentController::class, 'update'])->name('bujp.assesment.update');
            // Route::delete('/{assesment}/destroy', [AssesmentController::class, 'destroy'])->name('bujp.assesment.destroy');
            Route::patch('/{assesment}/send', [AssesmentController::class, 'send'])->name('bujp.assesment.send');
            Route::get('/{assesment}/show', [AssesmentController::class, 'show'])->name('bujp.assesment.show');
            Route::get('/{assesment}/preview', [AssesmentController::class, 'preview'])->name('bujp.assesment.preview');
            Route::patch('/update-question/{question}', [AssesmentController::class, 'updateQuestion'])->name('bujp.assesment.updateQuestion');
            Route::get('/{assesment}/report', [AssesmentController::class, 'report'])->name('bujp.assesment.report');
        });
        
    });


});