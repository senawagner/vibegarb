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
            // Renomear campo total para total_amount
            $table->renameColumn('total', 'total_amount');
            
            // Adicionar campos de pagamento
            $table->string('payment_method')->nullable()->after('total_amount');
            $table->enum('payment_status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending')->after('payment_method');
            
            // Adicionar dados do cliente
            $table->string('customer_name')->after('user_id');
            $table->string('customer_email')->after('customer_name');
            $table->string('customer_phone')->after('customer_email');
            
            // Adicionar campos de endereço individual (além do JSON)
            $table->string('shipping_zipcode', 9)->after('billing_address');
            $table->string('shipping_address_line')->after('shipping_zipcode');
            $table->string('shipping_number', 10)->after('shipping_address_line');
            $table->string('shipping_complement')->nullable()->after('shipping_number');
            $table->string('shipping_neighborhood')->after('shipping_complement');
            $table->string('shipping_city')->after('shipping_neighborhood');
            $table->string('shipping_state', 2)->after('shipping_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remover campos adicionados
            $table->dropColumn([
                'payment_method',
                'payment_status',
                'customer_name',
                'customer_email', 
                'customer_phone',
                'shipping_zipcode',
                'shipping_address_line',
                'shipping_number',
                'shipping_complement',
                'shipping_neighborhood',
                'shipping_city',
                'shipping_state'
            ]);
            
            // Reverter nome da coluna
            $table->renameColumn('total_amount', 'total');
        });
    }
};
