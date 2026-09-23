<?php

namespace Database\Seeders;

use App\Models\Carrera;
use App\Models\Institucion;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // $this->call([
        //     CarreraSeeder::class,
        //     UsuarioSeeder::class,
        // ]);

        Carrera::factory(5)->create();
        Institucion::factory(5)->create();
        Usuario::factory(20)->create();
    }
}