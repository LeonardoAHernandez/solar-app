<?php

use App\Http\Controllers\Client\AccommodationController;
use App\Http\Controllers\Client\ImageController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/client/accommodations')
    ->name('home');

// Alojamiento
Route::resource('accommodations', AccommodationController::class);

Route::get('accommodations/{accommodation}/images', [ImageController::class, 'index'])
->name('accommodations.images');


// Rutas de imágenes
Route::resource('images', ImageController::class);