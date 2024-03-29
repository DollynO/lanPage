<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Auth;
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
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Dashboard
    Route::get('/dashboard',function (){ return view('dashboard');})->name('dashboard');
    // GameController
    Route::get('/games',[GameController::class, 'index'])->name('games');
    Route::get('/games/create',function(){ return view('entries/game');})->name('new_game');

    // Foods
    Route::get('/foods', function(){ return view('foods');})->name('foods');

    Route::group(['middleware' => ['admin']], function(){
        // Users
        Route::get('/users', function (){ return view('users');})->name('users');
        // Parties
        Route::get('/parties', function (){ return view('parties');})->name('parties');
        Route::get('/parties/create', function(){ return view('entries/party');})->name('new_party');
        // Tournament
        Route::get('/tournament', function() {
            return view('tournament');
        })->name('tournament');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
