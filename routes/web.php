<?php

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

require_once('lists/auth.php');
require_once('lists/role.php');
require_once('lists/permission.php');
require_once('lists/user.php');

require_once('user/web.php');
require_once('admin/web.php');
require_once('bujp/web.php');

