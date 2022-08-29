<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\User\CustomerController;
use App\Http\Controllers\Enduser\EndUserController;
use App\Http\Controllers\Venues\VenueController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\DealsController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* Login Routes */
Route::post('/login', [CustomerController::class, 'loginUser'])->name('customer.login');

Route::get('/hi',function() {
    return 'user';
});

/* customer Routes */
Route::post('/customer_signup', [CustomerController::class, 'apistore'])->name('customer.signup');
Route::get('/edit_customer/{id}', [CustomerController::class, 'apiedit'])->name('edit.customer');
Route::post('/customer_update', [CustomerController::class, 'apiupdate'])->name('customer.apiupdate');
Route::get('/customer_verify/{id}', [CustomerController::class, 'verify'])->name('customer.verify');


/* venue */
Route::post('/addApiVenue', [VenueController::class, 'apistore'])->name('venue.addApiVenue');
Route::get('/viewVenue', [VenueController::class, 'index'])->name('venue.viewVenue');
Route::get('/editVenue/{id}', [VenueController::class, 'edit'])->name('edit.venue');
Route::post('/updateApiVenue', [VenueController::class, 'apiupdate'])->name('venue.apiupdate');
Route::get('/showAllVenue', [VenueController::class, 'showAll'])->name('venue.showAll');


/* Deals */
Route::post('/addDeal', [DealsController::class, 'store'])->name('deal.addDeal');
Route::get('/editDeal/{id}', [DealsController::class, 'edit'])->name('edit.deal');
Route::post('/updateDeal', [DealsController::class, 'update'])->name('deal.update');


/* user Routes */
Route::post('/user_signup', [EndUserController::class, 'store'])->name('user.signup');
Route::get('/edit_user/{id}', [EndUserController::class, 'apiedit'])->name('edit.user');
Route::post('/user_update', [EndUserController::class, 'apiupdate'])->name('user.update');
Route::get('/userViewVenue', [EndUserController::class, 'viewVenue'])->name('user.viewVenue');
Route::get('/userViewDeals', [EndUserController::class, 'viewDeals'])->name('user.viewDeal');
Route::post('/userViewVenueDeals', [EndUserController::class, 'viewVenueDeals'])->name('user.viewVenueDeal');


