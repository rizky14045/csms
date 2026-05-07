<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardFasumController;
use App\Http\Controllers\FetchController;
use App\Http\Controllers\GeoJsonController;
use App\Http\Controllers\MonthlyAuditExportController;
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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/register', function () {
    return view('user.register');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/fasum/dashboard', [DashboardFasumController::class, 'index'])->middleware(['auth'])->name('fasum.dashboard');

Route::get('/export-form-formulir/{monthlyId}', [MonthlyAuditExportController::class, 'exportFormFormulir'])->middleware(['auth'])->name('export.monthly.form-formulir');
Route::get('/export-worker-sum/{monthlyId}', [MonthlyAuditExportController::class, 'exportWorkerSum'])->middleware(['auth'])->name('export.monthly.worker-sum');
Route::get('/export-security-form/{monthlyId}',[MonthlyAuditExportController::class, 'exportFormSecurity'])->middleware(['auth'])->name('export.monthly.security-form');
Route::get(
    '/export-aght/{monthlyId}',
    [MonthlyAuditExportController::class, 'exportAght']
)
->middleware(['auth'])
->name('export.monthly.aght');
Route::get(
    '/export-form-attribute/{monthlyId}',
    [MonthlyAuditExportController::class, 'exportFormAttribute']
)
->middleware(['auth'])
->name('export.monthly.form-attribute');

Route::get(
    '/export-foreign-worker/{monthlyId}',
    [MonthlyAuditExportController::class, 'exportForeignWorker']
)
->middleware(['auth'])
->name('export.monthly.foreign-worker');
Route::get(
    '/export-security-program/{monthlyId}',
    [MonthlyAuditExportController::class, 'exportSecurityProgram']
)
->middleware(['auth'])
->name('export.monthly.security-program');
Route::get(
    '/export-vulnerability-internal/{monthlyId}',
    [MonthlyAuditExportController::class, 'exportVulnerabilityInternal']
)
->middleware(['auth'])
->name('export.monthly.vulnerability-internal');

Route::get(
    '/export-vulnerability-external/{monthlyId}',
    [MonthlyAuditExportController::class, 'exportVulnerabilityExternal']
)
->middleware(['auth'])
->name('export.monthly.vulnerability-external');

Route::get(
    '/export-penyerapan-anggaran/{monthlyId}',
    [MonthlyAuditExportController::class, 'exportBudgetAbsorption']
)->middleware(['auth'])->name('export.monthly.penyerapan-anggaran');

Route::get(
    '/export-all/{monthlyId}',
    [MonthlyAuditExportController::class, 'exportAll']
)->middleware(['auth'])->name('export.monthly.all');

Route::prefix('geo')->group(function () {

    Route::get('/province/{id}', [GeoJsonController::class, 'getProvince'])
        ->name('geo.province');

    Route::get('/cities/{province_id}', [GeoJsonController::class, 'getCities'])
        ->name('geo.cities');

    Route::get('/city/{id}', [GeoJsonController::class, 'getCity'])
        ->name('geo.city');

});
Route::get('/units/by-type', [FetchController::class, 'fetchUnitByType'])->name('units.byType');
require_once('lists/auth.php');
require_once('lists/role.php');
require_once('lists/permission.php');
require_once('lists/user.php');

require_once('user/web.php');
require_once('admin/web.php');
require_once('bujp/web.php');
require_once('auditor/web.php');

