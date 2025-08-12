<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        Usuario::updateOrCreate(
            ['email' => 'admin@salvajecatas.com'],
            [
                'nombre'   => 'Administrador',
                'telefono' => '600000000',
                'password' => 'admin123', // se hashea por el cast 'hashed'
                'rol'      => 'admin',
            ]
        );

        // Cliente
        Usuario::updateOrCreate(
            ['email' => 'cliente@salvajecatas.com'],
            [
                'nombre'   => 'Cliente Prueba',
                'telefono' => '600000001',
                'password' => 'cliente123',
                'rol'      => 'cliente',
            ]
        );
    }
}
