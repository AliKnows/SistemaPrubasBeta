<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('nombre', 'first_name');
            $table->renameColumn('apellido_paterno', 'last_name');
            $table->renameColumn('apellido_materno', 'second_last_name');
            $table->renameColumn('documento', 'document_number');
            $table->renameColumn('numero_telefonico', 'phone_number');
            $table->renameColumn('correo_electronico', 'email');
            $table->renameColumn('direccion', 'address');
            $table->renameColumn('fecha_nacimiento', 'birth_date');
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('first_name', 'nombre');
            $table->renameColumn('last_name', 'apellido_paterno');
            $table->renameColumn('second_last_name', 'apellido_materno');
            $table->renameColumn('document_number', 'documento');
            $table->renameColumn('phone_number', 'numero_telefonico');
            $table->renameColumn('email', 'correo_electronico');
            $table->renameColumn('address', 'direccion');
            $table->renameColumn('birth_date', 'fecha_nacimiento');
            $table->dropSoftDeletes();
        });
    }
};
