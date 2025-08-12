<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disponibilidades', function (Blueprint $table) {
            $table->id();

            // Un registro por día
            $table->date('fecha')->unique();

            // El admin puede cerrar manualmente un día aunque no tenga reservas
            $table->boolean('abierta')->default(true);

            // Observación opcional
            $table->string('nota', 200)->nullable();

            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disponibilidades');
    }
};


