<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();

            // Características fijas (sin JSONB)
            $table->integer('duracion_minutos')->nullable(); // p.ej. 90
            

            $table->decimal('precio', 10, 2)->nullable();
            $table->boolean('activo')->default(true);

            // Ruta relativa del archivo en el disk 'public' (p.ej. services/abc.jpg)
            $table->string('image_path')->nullable();

            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};

