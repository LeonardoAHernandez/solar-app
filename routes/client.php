<?php

use App\Http\Controllers\Client\AccomodationController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('client.dashboard');
// })->name('home');

Route::redirect('/', '/client/accommodations')
    ->name('home');

// Alojamiento
Route::resource('accommodations', AccomodationController::class);