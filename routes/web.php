<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authenticator;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::get('/api/auth  ', [Authenticator::class, 'index']);
Route::get('/api/auth/code', [Authenticator::class, 'getAuthCode']);
Route::get('/api/flight/offers', [Authenticator::class, 'flightOffers']);