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
        Schema::create('resultado_escaneos', function (Blueprint $table) {
            $table->id();
            //escaneo_id
            $table->string('url');
            $table->json('data')->nullable();  // Cambiado a tipo JSON
            $table->text('detalle')->nullable();
            $table->uuid('escaneo_id'); // Define como UUID
            $table->foreign('escaneo_id')->references('id')->on('escaneos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultado_escaneos');
    }
};
