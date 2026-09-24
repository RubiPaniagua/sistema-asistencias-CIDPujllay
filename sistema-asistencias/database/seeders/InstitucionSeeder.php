<?php

namespace Database\Seeders;

use App\Models\Institucion;
use Illuminate\Database\Seeder;

class InstitucionSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'SENATI',
            'UNSA',
            'SICMA',
            'Escuela Militar de Chorrillos',
        ] as $nombre) {
            Institucion::firstOrCreate([
                'nombre' => $nombre
            ]);
        }
    }
}