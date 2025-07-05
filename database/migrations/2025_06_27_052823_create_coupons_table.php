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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type'); // 'percentage' ou 'fixed'
            $table->decimal('value', 10, 2); // valor do desconto
            $table->decimal('minimum_amount', 10, 2)->nullable(); // valor mínimo do pedido
            $table->integer('usage_limit')->nullable(); // limite de uso
            $table->integer('used_count')->default(0); // quantas vezes foi usado
            $table->datetime('starts_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
