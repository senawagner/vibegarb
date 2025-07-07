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
            // Primeiro, remover a foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Tornar user_id nullable
            $table->foreignId('user_id')->nullable()->change();
            
            // Recriar a foreign key constraint permitindo null
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remover a foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Tornar user_id obrigatório novamente
            $table->foreignId('user_id')->change();
            
            // Recriar a foreign key constraint original
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
