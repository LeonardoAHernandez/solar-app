<?php

use App\Http\Controllers\Client\AccommodationController;
use App\Http\Controllers\Client\DetailController;
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
// Administrar el catálogo global de etiquetas (tags)
Route::post('tags/manage', [TagController::class, 'createOrUpdateTag'])->name('tags.manage');


// Rutas para la gestión de Servicios del Alojamiento
Route::get('accommodations/{accommodation}/services', [ServiceController::class, 'index'])
    ->name('accommodations.services.index');
Route::post('accommodations/{accommodation}/services', [ServiceController::class, 'store'])
    ->name('accommodations.services.store');
// Administrar el catálogo global de servicios
Route::post('services/manage', [ServiceController::class, 'createOrUpdateService'])->name('services.manage');


// Rutas para la gestión de Detalles (cantidades) del Alojamiento
Route::get('accommodations/{accommodation}/details', [DetailController::class, 'index'])
    ->name('accommodations.details.index');
Route::post('accommodations/{accommodation}/details', [DetailController::class, 'store'])
    ->name('accommodations.details.store');
// Aadministrar el catálogo global de detalles
Route::post('details/manage', [DetailController::class, 'createOrUpdateDetail'])->name('details.manage');
