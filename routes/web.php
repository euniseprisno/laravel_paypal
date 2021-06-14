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
    return view('auth.login');
});


Route::get('/dashboard', 'PayPalController@index')->middleware(['auth'])->name('dashboard');

Route::post('/payment', 'PayPalController@payment')->middleware(['auth'])->name('payment');
Route::get('/cancel', 'PayPalController@cancel')->middleware(['auth'])->name('cancel');
Route::get('/success', 'PayPalController@success')->middleware(['auth'])->name('success');

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
