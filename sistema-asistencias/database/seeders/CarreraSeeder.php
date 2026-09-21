<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarreraSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('carreras')->insert([
            ['id' => 1, 'nombre' => 'Ing. Software'],
            ['id' => 2, 'nombre' => 'Sistemas'],
        ]);
    }
}
