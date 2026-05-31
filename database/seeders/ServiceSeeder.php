<?php

namespace Database\Seeders;

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
            'Wifi',
            'TV',
            'Aire acondicionado',
            'Calefacción',
            'Agua caliente',
            'Toallas',
            'Plancha',
            'Cocina equipada',
            'Microondas',
            'Refrigerador',
            'Horno',
            'Lavadora',
            'Secadora',
        ];

        foreach ($services as $service) {
            Service::create([
                'name' => $service
            ]);
        }
    }
}
