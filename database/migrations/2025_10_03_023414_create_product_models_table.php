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
        Schema::create('product_models', function (Blueprint $table) {
            // relasi ke products
            $table->unsignedBigInteger('product_item_id');
            $table->foreign('product_item_id')
                  ->references('item_id')
                  ->on('products')
                  ->onDelete('cascade');

            // model data
            $table->unsignedBigInteger('model_id'); // Shopee model id
            $table->string('name')->nullable();     // boleh kosong
            $table->bigInteger('price')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('sold')->default(0);

            $table->timestamps();

            // supaya tidak ada duplikasi model per produk
            $table->unique(['product_item_id', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_models');
    }
};