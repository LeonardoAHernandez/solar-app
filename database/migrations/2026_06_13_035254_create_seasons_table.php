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
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // Ejemplo: "Verano 2026", "Navidad"
            $table->date('start_date'); // Fecha de inicio
            $table->date('end_date');   // Fecha de fin
            $table->string('type', 10); // Agregado: 'mid' o 'high'
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
