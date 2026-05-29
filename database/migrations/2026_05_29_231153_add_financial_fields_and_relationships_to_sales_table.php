<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('subtotal', 12, 2)->after('description')->default(0);
            $table->decimal('tax_amount', 12, 2)->after('subtotal')->default(0);
            $table->decimal('discount_amount', 12, 2)->after('tax_amount')->default(0);
            $table->foreignId('payment_method_id')->nullable()->after('client_id')->constrained()->onDelete('restrict');
            $table->foreignId('sale_status_id')->nullable()->after('payment_method_id')->constrained()->onDelete('restrict');
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_method_id');
            $table->dropConstrainedForeignId('sale_status_id');
            $table->dropColumn(['subtotal', 'tax_amount', 'discount_amount']);
            $table->dropSoftDeletes();
        });
    }
};
