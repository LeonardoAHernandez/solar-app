<?php

namespace Database\Seeders;

use App\Models\Detail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $details = [
            'Habitacion(es)',
            'Cama(s)',
            'Baño(s)',
            'Cocina',
            'Sala',
            'Comedor',
            'Lavandería',
            'Estacionamiento',
            'Piscina',
            'Jardín',
            'Terraza',
            'Balcón'
        ];

        foreach ($details as $detail) {
            Detail::create([
                'name' => $detail
            ]);
        }
    }
}