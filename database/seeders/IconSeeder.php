<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $icons = [
            ['name' => 'Wifi', 'class_name' => 'fa-solid fa-wifi'],
            ['name' => 'Televisión', 'class_name' => 'fa-solid fa-tv'],
            ['name' => 'Aire acondicionado', 'class_name' => 'fa-solid fa-snowflake'],
            ['name' => 'Calefacción', 'class_name' => 'fa-solid fa-fire'],
            ['name' => 'Agua caliente', 'class_name' => 'fa-solid fa-faucet-drip'],
            ['name' => 'Toallas / Baño', 'class_name' => 'fa-solid fa-shower'],
            ['name' => 'Plancha', 'class_name' => 'fa-solid fa-shirt'],
            ['name' => 'Cocina', 'class_name' => 'fa-solid fa-kitchen-set'],
            ['name' => 'Microondas', 'class_name' => 'fa-solid fa-scroll'], 
            ['name' => 'Refrigerador', 'class_name' => 'fa-solid fa-temperature-arrow-down'],
            ['name' => 'Horno / Manopla', 'class_name' => 'fa-solid fa-mitten'],
            ['name' => 'Lavadora', 'class_name' => 'fa-solid fa-soap'],
            ['name' => 'Secadora', 'class_name' => 'fa-solid fa-wind'],
        ];

        foreach ($icons as $icon) {
            Icon::firstOrCreate(
                ['class_name' => $icon['class_name']],
                ['name' => $icon['name']]
            );
        }
    }
}
