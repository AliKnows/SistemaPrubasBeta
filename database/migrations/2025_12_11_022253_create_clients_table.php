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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("nombre");
            $table->string("apellido_paterno");
            $table->string("apellido_materno")->nullable();
            $table->string("documento")->unique();
            $table->string("numero_telefonico")->unique()->nullable();
            $table->string("correo_electronico")->unique();
            $table->string("direccion")->nullable();
            $table->date("fecha_nacimiento")->nullable();        
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    
    
    }
};
