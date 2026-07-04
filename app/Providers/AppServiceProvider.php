<?php

namespace App\Providers;

use App\Models\Season;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $currentSeasonType = 'low'; // Baja por defecto

        // Verificamos si la tabla existe para evitar fallos en comandos de consola (como migraciones iniciales)
        if (Schema::hasTable('seasons')) {
            $today = now()->toDateString();

            // Buscamos si el día de hoy cae dentro de algún rango registrado
            $activeSeason = Season::where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->first();

            if ($activeSeason) {
                $currentSeasonType = $activeSeason->type; // 'mid' o 'high'
            }
        }

        // Compartimos la temporada actual de forma global en la configuración del runtime
        config(['app.season' => $currentSeasonType]);
    }
}
