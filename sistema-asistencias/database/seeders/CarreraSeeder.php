<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    public function run(): void
    {
        Carrera::create([
            'nombre' => 'Ingeniería de Software con Inteligencia Artificial',
        ]);

        Carrera::create([
            'nombre' => 'Ingeniería de Sistemas',
        ]);
    }
}