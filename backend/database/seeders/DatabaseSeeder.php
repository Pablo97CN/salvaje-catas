<?php

namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EstadoReservaSeeder::class,
            UsuarioSeeder::class, // ← añadimos tu seeder de usuarios
        ]);
    }
}
