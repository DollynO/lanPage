<?php

use App\Http\Controllers\GameController;
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
});
