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
        $isHighSeason = false;

        // 2. Control preventivo por si estás corriendo migraciones desde cero
        if (Schema::hasTable('seasons')) {
            $today = now()->toDateString(); // Obtiene la fecha de hoy (YYYY-MM-DD)

            // Buscamos si la fecha de hoy se encuentra entre el inicio y fin de alguna temporada guardada
            $isHighSeason = Season::where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->exists(); // Devuelve true si encuentra al menos una coincidencia
        }

        // 3. Compartimos el resultado tal cual lo teníamos antes
        View::share('isHighSeason', $isHighSeason);
        config(['app.is_high_season' => $isHighSeason]);
    }
}
