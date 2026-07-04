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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('category');

            $table->text('description')->nullable();

            $table->integer('status')->default(1);

            $table->integer('capacityMin');
            $table->integer('capacityMax')->nullable();

            $table->float('price_lowSeason', 10, 2);
            $table->float('price_midSeason', 10, 2);
            $table->float('price_highSeason', 10, 2);
            $table->text('locationURL');

            $table->timestamp('published_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
