<?php

namespace Database\Seeders;
 
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        Usuario::create([
            'nombre'   => 'Administrador',
            'email'    => 'admin@salvajecatas.com',
            'telefono' => '600000000',
            'password' => 'admin123', // Se hashea automáticamente por el cast 'hashed'
            'rol'      => 'admin',
        ]);

        // Cliente
        Usuario::create([
            'nombre'   => 'Cliente Prueba',
            'email'    => 'cliente@salvajecatas.com',
            'telefono' => '600000001',
            'password' => 'cliente123',
            'rol'      => 'cliente',
        ]);
    }
}
