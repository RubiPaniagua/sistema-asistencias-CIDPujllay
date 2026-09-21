<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SesionRemota;
use Carbon\Carbon;

class SesionRemotaSeeder extends Seeder
{
    public function run(): void
    {
        SesionRemota::create([
            'codigo' => 'ABC123',
            'generado_por' => 1, // ID del Admin General
            'expira_at' => Carbon::now('America/Lima')->addHours(2), // Válido por 2 horas
            'activo' => true,
        ]);
    }
}