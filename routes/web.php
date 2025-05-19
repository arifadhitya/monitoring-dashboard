<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\RajalStats;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('rajal', 'rajal')
        ->middleware(['auth', 'verified'])
        ->name('rajal');
// Route::view('rajal', RajalStats::class)
//     ->middleware(['auth', 'verified'])
//     ->name('rajal');

Route::view('ranap', 'ranap')
    ->middleware(['auth', 'verified'])
    ->name('ranap');

Route::view('lab', 'lab')
    ->middleware(['auth', 'verified'])
    ->name('lab');

Route::view('radio', 'radio')
    ->middleware(['auth', 'verified'])
    ->name('radio');

Route::view('antrol', 'antrol')
    ->middleware(['auth', 'verified'])
    ->name('antrol');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
