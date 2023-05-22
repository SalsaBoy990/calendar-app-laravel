<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClientController;
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

// Auth endpoints from Laravel UI
Auth::routes();
// Auth endpoints from Laravel UI END


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// 2FA endpoints
Route::get('2fa', [App\Http\Controllers\UserCodeController::class, 'index'])->name('2fa.index');
Route::post('2fa', [App\Http\Controllers\UserCodeController::class, 'store'])->name('2fa.post');
Route::get('2fa/reset', [App\Http\Controllers\UserCodeController::class, 'resend'])->name('2fa.resend');
// 2FA endpoints END


// A test endpoint
Route::group(['middleware' => 'role:administrator'], function () {

    Route::get('/admin-test', function() {
        return 'You are an admin!';
    });

});

// Routes only for authenticated users

// for super admins only
Route::group(
    ['middleware' => ['auth', 'verified', '2fa', 'role:super-administrator'], 'prefix' => 'admin'],
    function () {
        Route::get('role-permission/manage', [RolePermissionController::class, 'index'])->name('role-permission.manage');
    }
);

// for super admins and simple admins only
Route::group(
    ['middleware' => ['auth', 'verified', '2fa', 'role:super-administrator|administrator'], 'prefix' => 'admin'],
    function () {
        Route::get('user/manage', [UserController::class, 'index'])->name('user.manage');
        Route::get('client/manage', [ClientController::class, 'index'])->name('client.manage');
        Route::get('calendar', [CalendarController::class, 'index'])->name('calendar');
        Route::get('workers', [CalendarController::class, 'workers'])->name('workers');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    }
);

// for super admins, simple admins, and workers
Route::group(
    ['middleware' => ['auth', 'verified', '2fa', 'role:super-administrator|administrator|worker'], 'prefix' => 'admin'],
    function () {

        Route::get('user/account/{user}', [UserController::class, 'account'])->name('user.account');
        Route::put('user/update/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('user/destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    }
);
// Routes only for authenticated users END

