<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyticsController;

// asset 
use App\Http\Controllers\AssetController;

// Dashborad 
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\TransactionController;




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

// Route::get('/', function () {
//     return view('index');
// });


// Dashborad 
Route::get('/dashboard', [DashboardController::class, 'index'])->name('index');

Route::get('/analytics', [AnalyticsController::class, 'analytics'])->name('analytics');

Route::get('/services', function () {
    return view('services');
})->name('settings');


// asset 
Route::resource('assets', AssetController::class);

Route::resource('transactions', TransactionController::class)->only(['index', 'show', 'store', 'destroy']);

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::group(['middleware' => 'auth'], function() {
//     Route::get('/dashboard', 'AssetController@index');
//     Route::resource('assets', 'AssetController');
// });


Auth::routes();


// Route::get('/', function () {
//     return redirect()->route('login'); // Redirect root ke halaman login
// });
Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('index');

// Group Middleware untuk Auth
Route::group(['middleware' => 'auth'], function() {
    // Route Dashboard
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

    // Resource Route untuk AssetController
    Route::resource('assets', App\Http\Controllers\AssetController::class);
    // Route::resource('analytics', App\Http\Controllers\AnalyticsController::class);
});