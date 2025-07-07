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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('supplier_order_id')->nullable()->after('supplier_status')->comment('ID do pedido no sistema do fornecedor (ex: Dimona)');
            $table->timestamp('sent_to_supplier_at')->nullable()->after('supplier_order_id')->comment('Data e hora que o pedido foi enviado ao fornecedor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['supplier_order_id', 'sent_to_supplier_at']);
        });
    }
};
