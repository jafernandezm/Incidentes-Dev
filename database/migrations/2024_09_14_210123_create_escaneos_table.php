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
        Schema::create('escaneos', function (Blueprint $table) {
            $table->uuid('id')->primary();// Define UUID como clave primaria
            $table->string('url')->nullable();
            $table->string('tipo')->nullable();
            $table->date('fecha');
            $table->string('resultado');
            $table->json('detalles')->nullable();
            $table->timestamps();         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escaneos');
    }
};
