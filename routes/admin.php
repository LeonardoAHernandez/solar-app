<?php

use App\Http\Controllers\Admin\AccommodationController;
use App\Http\Controllers\Admin\DetailController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return redirect()->route('admin.accommodations.index');
})->name('home');

// Alojamiento
Route::resource('accommodations', AccommodationController::class);

Route::get('accommodations/{accommodation}/images', [ImageController::class, 'index'])
    ->name('accommodations.images');

// Rutas de imágenes
// Ruta masiva para guardar el orden
Route::put('images/update-order', [ImageController::class, 'updateOrder'])
    ->name('images.updateOrder');
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


// Ruta para manejar el cambio de estado del alojamiento (borrador/activo/inactivo)
Route::patch('accommodations/{accommodation}/status', [AccommodationController::class, 'updateStatus'])
    ->name('accommodations.status');



// Rutas de temporadas (temporadas altas/bajas)
Route::resource('seasons', SeasonController::class);
