<?php

use App\Http\Controllers\Visitor\AccommodationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return redirect()->route('visitor.accommodations.index');
})->name('home');

Route::resource('accommodations', AccommodationController::class);
