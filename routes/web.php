<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyticsController;

// asset 
use App\Http\Controllers\AssetController;

// Dashborad 
use App\Http\Controllers\DashboardController;



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
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/analytics', [AnalyticsController::class, 'analytics'])->name('analytics');

Route::get('/services', function () {
    return view('services');
})->name('settings');


// asset 
Route::resource('assets', AssetController::class);

