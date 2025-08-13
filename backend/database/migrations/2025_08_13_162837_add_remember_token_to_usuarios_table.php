<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            if (! Schema::hasColumn('usuarios', 'remember_token')) {
                $table->rememberToken(); // varchar(100) nullable
            }
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            if (Schema::hasColumn('usuarios', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
        });
    }
};
