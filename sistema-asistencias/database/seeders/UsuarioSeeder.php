<?php

namespace Database\Seeders;

use App\Models\Carrera;
use App\Models\Institucion;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $senati = Institucion::where('nombre', 'SENATI')->first();
        $software = Carrera::where(
            'nombre',
            'Ingeniería de Software con Inteligencia Artificial'
        )->first();

        // Admin fijo
        Usuario::firstOrCreate(
            ['dni' => '00000001'],
            [
                'nombres' => 'Admin',
                'apellidos' => 'General',
                'institucion_id' => $senati->id,
                'carrera_id' => $software->id,
                'rol' => 'admin',
                'modalidad' => null,
                'activo' => true,
                'email' => 'admin@sistema.com',
                'password' => Hash::make('password'),
            ]
        );

        // Usuario fijo para tus pruebas
        Usuario::firstOrCreate(
            ['dni' => '74827384'],
            [
                'nombres' => 'Jorge',
                'apellidos' => 'Quenta Oliva',
                'institucion_id' => $senati->id,
                'carrera_id' => $software->id,
                'rol' => 'practicante',
                'modalidad' => 'presencial',
                'activo' => true,
            ]
        );

        $instituciones = Institucion::all();
        $carreras = Carrera::all();

        // 10 presenciales
        Usuario::factory(10)
            ->presencial()
            ->state(fn () => [
                'institucion_id' => $instituciones->random()->id,
                'carrera_id' => $carreras->random()->id,
                'rol' => 'practicante',
            ])
            ->create();

        // 9 remotos activos
        Usuario::factory(9)
            ->remoto()
            ->state(fn () => [
                'institucion_id' => $instituciones->random()->id,
                'carrera_id' => $carreras->random()->id,
                'rol' => 'practicante',
            ])
            ->create();

        // 1 remoto inactivo
        Usuario::factory(1)
            ->remoto()
            ->inactivo()
            ->state(fn () => [
                'institucion_id' => $instituciones->random()->id,
                'carrera_id' => $carreras->random()->id,
                'rol' => 'practicante',
            ])
            ->create();
    }
}