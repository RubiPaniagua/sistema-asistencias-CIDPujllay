<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SesionRemota;
use App\Models\Usuario;
use Carbon\Carbon;

class SesionRemotaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Usuario::where('rol', 'admin')->first();

        if (!$admin) {
            return;
        }

        SesionRemota::create([
            'codigo_temporal' => 'ABC123',
            'generado_por' => $admin->id,
            'expira_en' => Carbon::now('America/Lima')->addMinutes(15),
            'usado' => false,
        ]);
    }
}