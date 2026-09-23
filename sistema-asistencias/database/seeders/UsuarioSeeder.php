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
            'codigo' => 'ADM001',
            'dni' => '00000001',
            'nombre' => 'Admin General',
            'institucion' => 'SENATI',
            'carrera_id' => 1,
            'rol' => 'admin',
            'modalidad' => null,
            'activo' => true,
            'email' => 'admin@sistema.com',
            'password' => 'password',
        ]);

        Usuario::create([
            'codigo' => 'P202601',
            'dni' => '74829103',
            'nombre' => 'Juan Pérez',
            'institucion' => 'SENATI',
            'carrera_id' => 1,
            'rol' => 'practicante',
            'modalidad' => 'presencial',
            'activo' => true,
            'email' => null,
            'password' => null,
        ]);

        Usuario::create([
            'codigo' => 'R202602',
            'dni' => '83920192',
            'nombre' => 'Maria Lopez',
            'institucion' => 'UNSA',
            'carrera_id' => 2,
            'rol' => 'practicante',
            'modalidad' => 'remoto',
            'activo' => true,
            'email' => null,
            'password' => null,
        ]);
    }
}