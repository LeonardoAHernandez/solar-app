<?php

use App\Http\Controllers\Client\AccommodationController;
use App\Http\Controllers\Client\ImageController;
use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\Client\TagController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/client/accommodations')
    ->name('home');

// Alojamiento
Route::resource('accommodations', AccommodationController::class);

Route::get('accommodations/{accommodation}/images', [ImageController::class, 'index'])
    ->name('accommodations.images');
    
// Rutas de imágenes
Route::resource('images', ImageController::class);

// Rutas para la gestión de Etiquetas del Alojamiento
Route::get('accommodations/{accommodation}/tags', [TagController::class, 'index'])
    ->name('accommodations.tags.index');
Route::post('accommodations/{accommodation}/tags', [TagController::class, 'store'])
    ->name('accommodations.tags.store');

// Rutas para la gestión de Servicios del Alojamiento
Route::get('accommodations/{accommodation}/services', [ServiceController::class, 'index'])
    ->name('accommodations.services.index');
Route::post('accommodations/{accommodation}/services', [ServiceController::class, 'store'])
    ->name('accommodations.services.store');

