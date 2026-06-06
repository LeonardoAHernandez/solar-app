<?php

use App\Http\Controllers\Client\AccommodationController;
use App\Http\Controllers\Client\ImageController;
use App\Http\Controllers\Client\TagController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/client/accommodations')
    ->name('home');

// Alojamiento
Route::resource('accommodations', AccommodationController::class);

Route::get('accommodations/{accommodation}/images', [ImageController::class, 'index'])
->name('accommodations.images');

Route::get('accommodations/{accommodation}/tags', [TagController::class, 'index'])
        ->name('accommodations.tags.index');
Route::post('accommodations/{accommodation}/tags', [TagController::class, 'store'])
        ->name('accommodations.tags.store');


// Rutas de imágenes
Route::resource('images', ImageController::class);