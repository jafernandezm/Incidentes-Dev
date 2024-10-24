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
        Schema::create('incidentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_id')->constrained('tipos')->onDelete('cascade'); // Referencia al tipo
            //tipo, descripcion, fecha, contenido, tipo_id
            //$table->string('tipo');
            $table->string('contenido');
            $table->string('descripcion');
            $table->date('fecha');
            $table->timestamps();
            //tipo_id, contenido, descripcion, fecha
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidentes');
    }
};
