<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Zona dorada', 'category' => 'Zona'],
            ['name' => 'Zona diamante', 'category' => 'Zona'],
            ['name' => 'Zona tradicional', 'category' => 'Zona'],
            ['name' => 'Casa sola', 'category' => 'Tipo de alojamiento'],
            ['name' => 'Departamento', 'category' => 'Tipo de alojamiento'],
            ['name' => 'Condominio', 'category' => 'Tipo de alojamiento'],
            ['name' => 'Casa de huéspedes', 'category' => 'Tipo de alojamiento'],
            ['name' => 'Casa de campo', 'category' => 'Tipo de alojamiento'],   
            ['name' => 'Casa de playa', 'category' => 'Tipo de alojamiento'],
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag['name'],
                'category' => $tag['category']
            ]);
        }
    }
}
