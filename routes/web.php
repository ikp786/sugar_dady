<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MiscMstController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StripePaymentController;

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
    $title         = "Login";
    $data          = compact('title');
    return view('admin_panel.login', $data);
});

Route::get('control_panel', [AuthController::class, 'login_index'])->name('control_panel');
Route::post('login', [AuthController::class, 'login_user'])->name('admin.login');

Route::get('logout', [DashboardController::class, 'logout_admin'])->name('admin.logout')->middleware(['admin']);
Route::get('test', [StripePaymentController::class, 'test'])->name('test');
Route::get('stripe', [StripePaymentController::class, 'stripe']);
Route::post('stripe', [StripePaymentController::class, 'stripePost'])->name('stripe.post');

Route::group(['middleware' => 'prevent-back-history'], function() {

    Route::group(['prefix' => 'control_panel','middleware' => 'admin'], function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['admin']);
        Route::get('admin/{admin_id}/edit', [AuthController::class, 'admin_edit'])->name('admin.edit')->middleware(['admin']);

        Route::any('admin/update/{admin_id}', [AuthController::class, 'admin_update'])->name('admin.update')->middleware(['admin']);
        Route::resource('misces', MiscMstController::class);
        Route::group(['prefix' => 'users'], function () {
        Route::get('list',[UserController::class,'index'])->name('users.list');
        Route::get('show/{id}',[UserController::class,'show'])->name('users.show');
        });
        Route::resource('plans', PlanController::class);
    });
});