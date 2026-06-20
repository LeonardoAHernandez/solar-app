<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // Si el usuario tiene una sesión activa
    if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Forzamos un log para depurar si es necesario
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.accommodations.index');
        }
        
        // Si está logueado pero NO es admin, va a la vista de visitantes
        return redirect()->route('visitor.accommodations.index');
    }

    // Si NO está logueado (invitado), va a la home pública
    return redirect()->route('visitor.home');
})->name('public.home');