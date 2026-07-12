<?php

use App\Http\Controllers\Visitor\AccommodationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return redirect()->route('visitor.accommodations.index');
})->name('home');

Route::resource('accommodations', AccommodationController::class);

Route::get('/mis-intereses', function () {
    return view('visitor.interests.index'); 
})->name('accommodations.interests');