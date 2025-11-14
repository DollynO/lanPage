<?php

use App\Http\Controllers\Photos\PhotoController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

//Route::get('/', function () {
//    return view('welcome');
//})->name('home');

Route::get('/', function () {
    return redirect('/login');
})->name('home');

//Gallery
Route::get('gallery',         [PhotoController::class,'index'])->name('gallery.index');
Route::get('gallery/{party}', [PhotoController::class,'partyGallery'])->name('gallery.party');
Route::post('gallery/{party}',[PhotoController::class,'store'])->name('gallery.store');

Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function (){ return view('dashboard');})->name('dashboard');
    Route::get('/games', function (){ return view('games');})->name('games');
    Route::get('/foods', function (){ return view('foods');})->name('foods');

    //Gallery
    Route::get('gallery',         [PhotoController::class,'index'])->name('gallery.index');
    Route::get('gallery/{party}', [PhotoController::class,'partyGallery'])->name('gallery.party');
    Route::post('gallery/{party}',[PhotoController::class,'store'])->name('gallery.store');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');


    Route::group(['middleware' => [\App\Http\Middleware\AdminMiddleware::class]], function() {
        Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');
        Route::get('/settings', function (){ return view('settings');})->name('settings');
        Route::get('/parties', function (){ return view('parties');})->name('parties');
        Route::get('/users', function (){ return view('users');})->name('users');
        Route::get('/tournament', function (){ return view('tournament');})->name('tournament');
    });

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
