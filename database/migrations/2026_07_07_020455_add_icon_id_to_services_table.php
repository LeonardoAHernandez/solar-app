<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Creamos la llave foránea apuntando a la tabla icons.
            // Es nullable por si tienes servicios existentes que aún no tengan icono asignado.
            $table->foreignId('icon_id')
                  ->nullable()
                  ->after('name')
                  ->constrained('icons')
                  ->nullOnDelete(); // Si borras un icono, el servicio se mantiene pero su icon_id pasa a ser null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['icon_id']);
            $table->dropColumn('icon_id');
        });
    }
};
