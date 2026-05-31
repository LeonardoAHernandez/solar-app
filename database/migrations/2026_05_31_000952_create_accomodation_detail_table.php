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
        Schema::create('accommodation_detail', function (Blueprint $table) {
            $table->id();

            $table->foreignId('accommodation_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('detail_id')
                ->constrained()
                ->onDelete('cascade');

            $table->integer('quantity');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_detail');
    }
};
