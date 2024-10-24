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
        Schema::create('tipos', function (Blueprint $table) {
            $table->id();
            //nombre, descripcion
            //$table->enum('nombre', ['urlInfectada', 'htmlInfectado', 'dorksPasivo', 'dorksActivo', 'NDSW'])->default('urlInfectada');
            //Url Infectada , Html Infectado, Dorks Pasivo, Dorks Activo, Ataque NDSW
            $table->enum('nombre' , ['Url Infectada','Html Infectado', 'Dorks Pasivo', 'Dorks Activo' , 'Ataque NDSW' ])->default('Url Infectada');
            $table->string('descripcion');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos');
    }
};
