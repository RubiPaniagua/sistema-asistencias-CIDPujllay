<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        Usuario::create([
            'codigo' => 'ADM001',
            'nombre' => 'Admin General',
            'carrera_id' => 1,
            'rol' => 'admin',
            'modalidad' => null,
            'activo' => true,
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
        ]);

        // Practicante Presencial
        Usuario::create([
            'codigo' => 'P202601',
            'nombre' => 'Juan Pérez',
            'carrera_id' => 1,
            'rol' => 'practicante',
            'modalidad' => 'presencial',
            'activo' => true,
        ]);

        // Practicante Remoto
        Usuario::create([
            'codigo' => 'R202602',
            'nombre' => 'Maria Lopez',
            'carrera_id' => 2,
            'rol' => 'practicante',
            'modalidad' => 'remoto',
            'activo' => true,
        ]);
    }
}