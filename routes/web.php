<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeoJsonController;
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

Route::prefix('geo')->group(function () {

    Route::get('/province/{id}', [GeoJsonController::class, 'getProvince'])
        ->name('geo.province');

    Route::get('/cities/{province_id}', [GeoJsonController::class, 'getCities'])
        ->name('geo.cities');

    Route::get('/city/{id}', [GeoJsonController::class, 'getCity'])
        ->name('geo.city');

});
require_once('lists/auth.php');
require_once('lists/role.php');
require_once('lists/permission.php');
require_once('lists/user.php');

require_once('user/web.php');
require_once('admin/web.php');
require_once('bujp/web.php');
require_once('auditor/web.php');

