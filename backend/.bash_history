php artisan make:model Disponibilidad -m
exit
php artisan make:model Disponibilidad -m
php artisan make:model Disponibilidad -m
php artisan make:model Reserva -m
exit
php artisan queue:table
php artisan queue:failed-table 
php artisan queue:batches-table
php artisan make:migration create_usuarios_table
php artisan make:model Usuario
php artisan migrate
php artisan migrate
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{     /**;      * Run the migrations.;      */;     public function up(): void
    {         Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            // Datos básicos
            $table->string('nombre', 120);
            $table->string('email')->unique();
            $table->string('telefono', 20)->nullable();
            // Auth
            $table->timestampTz('email_verified_at')->nullable();
            $table->string('password');
            // Rol (admin | cliente)
            $table->string('rol', 20)->default('cliente');
            // Timestamps estándar (con TZ)
            $table->timestampsTz();
        });
        // CHECK en rol (PostgreSQL)
        DB::statement("
            ALTER TABLE usuarios
            ADD CONSTRAINT usuarios_rol_chk
            CHECK (rol IN ('admin','cliente'))
        ");
        // Reset de contraseña (Laravel)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {         // Revertir en orden inverso;         Schema::dropIfExists('password_reset_tokens');
        // Eliminar constraint explícitamente (limpio en PG)
        DB::statement("ALTER TABLE usuarios DROP CONSTRAINT IF EXISTS usuarios_rol_chk");
        Schema::dropIfExists('usuarios');
    }
};
php artisan migrate
php artisan migrate:fresh
php artisan make:model Usuario
php artisan make:seeder UsuarioSeeder
exit
php artisan make:model Servicio -m
php artisan make:model EstadoReserva -m
php artisan make:model Reserva -m
php artisan make:model Disponibilidad -m
php artisan migrate:fresh --seed
php artisan migrate:fresh --seed
php artisan migrate:fresh --seed
php artisan migrate:fresh --seed
php artisan migrate:fresh --seed
php artisan migrate:fresh --seed
php artisan migrate:fresh --seed
exit
php artisan config:clear
php artisan migrate:fresh --seed
exit
