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
        Schema::create('filtracions', function (Blueprint $table) {
            $table->id();
            $table->text('consulta');
            $table->text('tipo')->nullable();
            $table->text('filtracion')->nullable();
            $table->text('informacion')->nullable();
            $table->json('data')->nullable();
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
        Schema::dropIfExists('filtracions');
    }
};
