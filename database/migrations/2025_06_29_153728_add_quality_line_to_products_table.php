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
        Schema::table('products', function (Blueprint $table) {
            // Adicionar linha de qualidade
            $table->enum('quality_line', [
                'classic', 'quality', 'prime', 'pima', 'estonada', 'dry_sport'
            ])->default('classic')->after('category_id');
            
            // Adicionar público alvo
            $table->enum('target_audience', [
                'masculino', 'feminino', 'infantil', 'unissex'
            ])->default('unissex')->after('quality_line');
            
            // Adicionar cores disponíveis (array simples para MVP)
            $table->text('available_colors')->nullable()->after('target_audience');
            
            // Adicionar tamanhos disponíveis (array simples para MVP)
            $table->text('available_sizes')->nullable()->after('available_colors');
        });

        // Remover campos desnecessários para dropshipping
        Schema::table('products', function (Blueprint $table) {
            // Manter stock_quantity para controle do admin
            // Verificar e remover apenas campos realmente desnecessários
            if (Schema::hasColumn('products', 'manage_stock')) {
                $table->dropColumn('manage_stock');
            }
            if (Schema::hasColumn('products', 'supplier_id')) {
                $table->dropForeign(['supplier_id']);
                $table->dropColumn('supplier_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Remover novos campos
            $table->dropColumn([
                'quality_line', 'target_audience', 'available_colors', 'available_sizes'
            ]);
            
            // Recriar apenas campos que foram removidos
            $table->boolean('manage_stock')->default(true);
            $table->foreignId('supplier_id')->nullable()->constrained();
        });
    }
};
