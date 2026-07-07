<?php

namespace Database\Seeders;

use App\Models\Icon;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Wifi', 'class' => 'fa-solid fa-wifi'],
            ['name' => 'TV', 'class' => 'fa-solid fa-tv'],
            ['name' => 'Aire acondicionado', 'class' => 'fa-solid fa-snowflake'],
            ['name' => 'Calefacción', 'class' => 'fa-solid fa-fire'],
            ['name' => 'Agua caliente', 'class' => 'fa-solid fa-faucet-drip'],
            ['name' => 'Toallas', 'class' => 'fa-solid fa-shower'],
            ['name' => 'Plancha', 'class' => 'fa-solid fa-shirt'],
            ['name' => 'Cocina equipada', 'class' => 'fa-solid fa-kitchen-set'],
            ['name' => 'Microondas', 'class' => 'fa-solid fa-scroll'],
            ['name' => 'Refrigerador', 'class' => 'fa-solid fa-temperature-arrow-down'],
            ['name' => 'Horno', 'class' => 'fa-solid fa-mitten'],
            ['name' => 'Lavadora', 'class' => 'fa-solid fa-soap'],
            ['name' => 'Secadora', 'class' => 'fa-solid fa-wind'],
        ];

        foreach ($services as $serviceData) {
            // Buscamos el registro del icono en la base de datos mediante su clase CSS
            $icon = Icon::where('class_name', $serviceData['class'])->first();

            Service::updateOrCreate(
                ['name' => $serviceData['name']],
                [
                    // Si el icono existe, le asignamos su ID obtenido; si no, lo dejamos en null
                    'icon_id' => $icon ? $icon->id : null
                ]
            );
        }
    }
}
