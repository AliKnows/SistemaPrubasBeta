<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'Pending', 'Paid', 'Cancelled'
            $table->string('color_hex', 7)->default('#000000');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_statuses');
    }
};
