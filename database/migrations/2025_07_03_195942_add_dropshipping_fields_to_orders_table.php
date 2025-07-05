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
            // Campos para dropshipping
            $table->decimal('production_cost', 10, 2)->default(0)->after('total_amount'); // Custo total de produção
            $table->decimal('supplier_shipping_cost', 10, 2)->default(0)->after('production_cost'); // Frete que a fábrica cobra
            $table->decimal('profit_margin', 10, 2)->default(0)->after('supplier_shipping_cost'); // Margem de lucro
            
            // Prazos e status de produção
            $table->integer('production_days')->default(0)->after('profit_margin'); // Prazo de produção em dias
            $table->date('estimated_production_date')->nullable()->after('production_days'); // Data estimada de produção
            $table->date('estimated_shipping_date')->nullable()->after('estimated_production_date'); // Data estimada de envio
            $table->date('estimated_delivery_date')->nullable()->after('estimated_shipping_date'); // Data estimada de entrega
            
            // Status específicos para dropshipping
            $table->enum('production_status', ['pending', 'confirmed', 'in_production', 'ready_to_ship', 'shipped', 'delivered'])->default('pending')->after('status');
            $table->enum('supplier_status', ['pending', 'confirmed', 'paid', 'in_production', 'shipped'])->default('pending')->after('production_status');
            
            // Informações da fábrica
            $table->string('supplier_order_number')->nullable()->after('supplier_status'); // Número do pedido na fábrica
            $table->text('supplier_notes')->nullable()->after('supplier_order_number'); // Observações para a fábrica
            $table->text('internal_notes')->nullable()->after('supplier_notes'); // Observações internas
            
            // Tracking da fábrica
            $table->string('supplier_tracking_code')->nullable()->after('internal_notes'); // Código de rastreio da fábrica
            $table->string('supplier_tracking_url')->nullable()->after('supplier_tracking_code'); // URL de rastreio da fábrica
            
            // Campos para controle financeiro
            $table->boolean('supplier_paid')->default(false)->after('supplier_tracking_url'); // Se já pagou a fábrica
            $table->date('supplier_payment_date')->nullable()->after('supplier_paid'); // Data do pagamento à fábrica
            $table->decimal('supplier_payment_amount', 10, 2)->default(0)->after('supplier_payment_date'); // Valor pago à fábrica
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'production_cost',
                'supplier_shipping_cost', 
                'profit_margin',
                'production_days',
                'estimated_production_date',
                'estimated_shipping_date',
                'estimated_delivery_date',
                'production_status',
                'supplier_status',
                'supplier_order_number',
                'supplier_notes',
                'internal_notes',
                'supplier_tracking_code',
                'supplier_tracking_url',
                'supplier_paid',
                'supplier_payment_date',
                'supplier_payment_amount'
            ]);
        });
    }
};
