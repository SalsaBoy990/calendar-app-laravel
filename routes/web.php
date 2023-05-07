<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'role:administrator'], function () {

    Route::get('/admin-test', function() {
        return 'You are an admin!';
    });

});

// Routes only for authenticated users...
Route::group(
    ['middleware' => ['auth', 'verified', 'role:site-admin'], 'prefix' => 'admin'],
    function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('user/manage', [UserController::class, 'index'])->name('user.manage');
        Route::get('role-permission/manage', [RolePermissionController::class, 'index'])->name('role-permission.manage');
    }
);


