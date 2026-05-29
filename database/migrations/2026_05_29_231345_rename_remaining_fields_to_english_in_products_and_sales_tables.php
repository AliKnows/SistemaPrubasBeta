<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('nombre', 'name');
            $table->renameColumn('descripcion', 'description');
            $table->renameColumn('precio', 'price');
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->renameColumn('quantity', 'quantity'); // Already English
            // unit_price, subtotal are already English
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name', 'nombre');
            $table->renameColumn('description', 'descripcion');
            $table->renameColumn('price', 'precio');
        });
    }
};
