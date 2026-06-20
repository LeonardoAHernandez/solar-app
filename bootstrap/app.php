<?php

use App\Http\Middleware\CheckAdminRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // <-- Importante: Añadir esta línea para usar Auth

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web', 'auth', 'admin')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware('')
                // ->prefix('visitor')
                ->name('visitor.')
                ->group(base_path('routes/visitor.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registramos el alias para poder usarlo en los archivos de rutas
        $middleware->alias([
            'admin' => CheckAdminRole::class,
        ]);

        // Redirección para usuarios ya autenticados que entran a la raíz o rutas 'guest'
        $middleware->redirectUsersTo(function () {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user && $user->isAdmin()) {
                return route('admin.accommodations.index');
            }

            return route('visitor.accommodations.index');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();