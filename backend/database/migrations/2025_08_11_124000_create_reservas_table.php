<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();

            // FK al cliente que hace la reserva
            $table->foreignId('usuario_id')
                  ->constrained('usuarios')
                  ->cascadeOnDelete();

            // FK al servicio elegido
            $table->foreignId('servicio_id')
                  ->constrained('servicios')
                  ->restrictOnDelete();

            // Una sola reserva por día en todo el sistema
            $table->date('fecha')->unique();

            // Datos de la reserva
            $table->integer('personas');          // valida en request: min:1
            $table->string('alergias', 255)->nullable();
            $table->string('ubicacion', 120)->nullable();

            // (Opcional) estado de la reserva, lo dejamos nullable
            // Requiere que exista la tabla 'estado_reservas' al migrar.
            $table->foreignId('estado_reserva_id')
                  ->nullable()
                  ->constrained('estado_reservas')
                  ->restrictOnDelete();

            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};

