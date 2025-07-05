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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // Preço no momento da adição
            $table->decimal('total', 10, 2); // Quantidade * preço
            $table->timestamps();

            // Indexes para performance
            $table->index(['user_id', 'session_id']);
            $table->index('session_id');
            
            // Unique constraint para evitar duplicatas
            $table->unique(['user_id', 'product_id', 'product_variant_id'], 'unique_user_product_variant');
            $table->unique(['session_id', 'product_id', 'product_variant_id'], 'unique_session_product_variant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
