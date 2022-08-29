<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\TimeFrameController;
use App\Http\Controllers\Admin\User\CustomerController;
use App\Http\Controllers\Venues\VenueController;
use App\Http\Controllers\Admin\User\DriverController;
use App\Http\Controllers\Driver\JobController as DriverJobController;
use App\Http\Controllers\Customer\JobController as CustomerJobController;
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
Auth::routes(['verify' => true]);

Route::get('/', function () {
    return redirect('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*Admin Routes */
    Route::group(['middleware' => 'admin', 'prefix' => 'admin', 'name' => 'admin' . '.'], function () {
        Route::resource('/user/customer', CustomerController::class)->name('*', 'customer');
    });
    Route::get('/venue/createVenue', [VenueController::class, 'index'])->name('venue.verify');


});
