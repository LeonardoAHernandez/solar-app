<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Raíz pública de la aplicación
Route::get('/', function () {
    if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('client.accommodations.index');
        }
    }

    return view('welcome');
})->name('public.home');