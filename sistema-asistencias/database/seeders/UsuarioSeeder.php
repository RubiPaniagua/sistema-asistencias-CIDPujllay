<?php

namespace Database\Seeders;

use App\Models\Carrera;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $software = Carrera::where(
            'nombre',
            'Ingeniería de Software con Inteligencia Artificial'
        )->first();

        $sistemas = Carrera::where(
            'nombre',
            'Ingeniería de Sistemas'
        )->first();

        Usuario::create([
            'codigo' => 'ADMIN001',
            'nombre' => 'Administrador del Sistema',
            'carrera_id' => null,
            'rol' => 'admin',
            'modalidad' => null,
            'activo' => true,
            'email' => 'admin@sistema.com',
            'password' => 'password',
        ]);

        Usuario::create([
            'codigo' => 'PRES001',
            'nombre' => 'Juan Pérez',
            'carrera_id' => $software->id,
            'rol' => 'practicante',
            'modalidad' => 'presencial',
            'activo' => true,
            'email' => null,
            'password' => null,
        ]);

        Usuario::create([
            'codigo' => 'REM001',
            'nombre' => 'María López',
            'carrera_id' => $sistemas->id,
            'rol' => 'practicante',
            'modalidad' => 'remoto',
            'activo' => true,
            'email' => null,
            'password' => null,
        ]);
    }
}