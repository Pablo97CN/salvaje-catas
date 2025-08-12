<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoReservaSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotente: si ya existen por 'codigo', actualiza 'nombre'
        DB::table('estado_reservas')->upsert([
            ['codigo' => 'pendiente',  'nombre' => 'Pendiente'],
            ['codigo' => 'confirmada', 'nombre' => 'Confirmada'],
            ['codigo' => 'cancelada',  'nombre' => 'Cancelada'],
        ], ['codigo'], ['nombre']);
    }
}
