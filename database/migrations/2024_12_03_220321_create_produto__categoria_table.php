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
        Schema::create('produto_categoria', function (Blueprint $table) {
            $table->unsignedBigInteger('id_produto');
            $table->foreign('id_produto')
                  ->references('id')
                  ->on('produtos')
                  ->onDelete('restrict');

            $table->unsignedBigInteger('id_categoria');
            $table->foreign('id_categoria')
                  ->references('id')
                  ->on('categorias')
                  ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto_categoria');
    }
};
