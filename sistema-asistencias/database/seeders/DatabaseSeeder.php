<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Orden importante: usuarios depende de carreras
        $this->call([
            CarreraSeeder::class,
            UsuarioSeeder::class,
        ]);
    }
}
