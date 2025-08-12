<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estado_reservas', function (Blueprint $table) {
            $table->id();

            // Identificador corto y único del estado, p. ej. 'pendiente', 'confirmada', 'cancelada'
            $table->string('codigo', 30)->unique();

            // Etiqueta legible
            $table->string('nombre', 100);

            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estado_reservas');
    }
};

