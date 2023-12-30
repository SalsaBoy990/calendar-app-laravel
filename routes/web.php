<?php

use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkerController;
use App\Http\Controllers\Auth\UserCodeController;
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


// Auth endpoints from Laravel UI
/*Auth::routes([
    'register' => false,
]);*/
// Auth endpoints from Laravel UI END


Route::group(
    [],
    function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        Route::get('/', function () {
            return view('public.pages.welcome');
        })->name('frontpage');


        Route::get('/home', [HomeController::class, 'index'])->name('home');

        /* 2FA */
        Route::get('2fa', [UserCodeController::class, 'index'])->name('2fa.index');
        /* 2FA END */
    });


Route::group(
    ['prefix' => 'admin'],
    function () {

        /* Login/Logout/Register */
        Route::get('login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
        Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
        // Route::get('register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
        // Route::post('register', 'App\Http\Controllers\Auth\RegisterController@register');
        /* Login/Logout/Register END */


        /* Password */
        Route::get('password/reset',
            'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email',
            'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}',
            'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset',
            'App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.update');
        Route::get('password/confirm',
            'App\Http\Controllers\Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
        Route::post('password/confirm', 'App\Http\Controllers\Auth\ConfirmPasswordController@confirm');
        /* Password END */


        /* Email */
        Route::get('email/verify',
            'App\Http\Controllers\Auth\VerificationController@show')->name('verification.notice');
        Route::get('email/verify/{id}/{hash}',
            'App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify');
        Route::post('email/resend',
            'App\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');
        /* Email END */

    });


// Routes only for authenticated users

// for super admins only
Route::group(
    ['middleware' => ['auth', 'verified', '2fa', 'role:super-administrator'], 'prefix' => 'admin'],
    function () {

        /* Roles and Permissions */
        Route::get('role-permission/manage', [RolePermissionController::class, 'index'])
            ->name('role-permission.manage');
        /* Roles and Permissions END */

    }
);


// for super admins and simple admins only
Route::group(
    ['middleware' => ['auth', 'verified', '2fa', 'role:super-administrator|administrator'], 'prefix' => 'admin'],
    function () {
        Route::get('user/manage', [UserController::class, 'index'])->name('user.manage');
        Route::get('worker/manage', [WorkerController::class, 'index'])->name('worker.manage');
        Route::get('client/manage', [ClientController::class, 'index'])->name('client.manage');
        Route::get('calendar', [CalendarController::class, 'index'])->name('calendar');
        Route::get('workers', [CalendarController::class, 'workers'])->name('workers');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('statistics', [StatsController::class, 'index'])->name('statistics');
    }
);


// for super admins, simple admins, and editors
Route::group(
    ['middleware' => ['auth', 'verified', '2fa'], 'prefix' => 'admin'],
    function () {

        /* Account/Users */
        Route::get('user/account/{user}', [UserController::class, 'account'])->name('user.account');
        Route::put('user/update/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('user/destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::delete('user/account/delete/{user}',
            [UserController::class, 'deleteAccount'])->name('user.account.delete');
        /* Account/Users END */

    }
);


Route::group(
    ['middleware' => ['auth', 'verified']],
    function () {

        /* 2fa endpoints for authenticated users */
        Route::post('2fa', [UserCodeController::class, 'store'])->name('2fa.post');
        Route::get('2fa/reset', [UserCodeController::class, 'resend'])
            ->name('2fa.resend');
        /* 2fa endpoints for authenticated users END */

    });
// Routes only for authenticated users END


/*Route::get('/migrate', function () {
    Artisan::call('migrate',
        array(
            '--path' => 'database/migrations',
            '--database' => 'mysql',
            '--force' => true
        ));
    echo 'Migration OK';
    exit;
});*/
