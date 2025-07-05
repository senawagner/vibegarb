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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Ex: "Tamanho M", "Cor Azul"
            $table->string('type'); // Ex: "size", "color"
            $table->string('value'); // Ex: "M", "azul"
            $table->decimal('price_adjustment', 10, 2)->default(0); // ajuste no preço
            $table->integer('stock_quantity')->default(0);
            $table->string('sku')->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
