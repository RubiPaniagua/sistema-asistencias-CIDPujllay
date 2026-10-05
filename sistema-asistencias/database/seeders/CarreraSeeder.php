<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Ingeniería de Software con Inteligencia Artificial',
            'Ingeniería de Sistemas',
            'Ingeniería Industrial',
            'Administración de Empresas',
            'Diseño Gráfico',
        ] as $nombre) {
            Carrera::firstOrCreate([
                'nombre' => $nombre
            ]);
        }
    }
}