<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            InstitucionSeeder::class,
            CarreraSeeder::class,
            UsuarioSeeder::class,
            
        ]);
    }
}        
        
        /*Carrera::factory(5)->create();
        Institucion::factory(5)->create();
        Usuario::factory(20)->create();*/