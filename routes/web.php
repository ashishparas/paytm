<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaytmController;
use Illuminate\Support\Facades\Auth;
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


Route::post('paytm-payment',[PaytmController::class, 'paytmPayment'])->name('paytm.payment');
Route::post('paytm-callback',[PaytmController::class, 'paytmCallback'])->name('paytm.callback');
Route::get('paytm-purchase',[PaytmController::class, 'paytmPurchase'])->name('paytm.purchase');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('order',[OrderController::class,"statusCheck"]);