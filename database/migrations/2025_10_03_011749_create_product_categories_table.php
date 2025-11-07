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
        Schema::create('product_categories', function (Blueprint $table) {
            // foreign key ke products (item_id sebagai PK)
            $table->unsignedBigInteger('product_item_id');
            $table->foreign('product_item_id')
                  ->references('item_id')
                  ->on('products')
                  ->onDelete('cascade');

            // foreign key ke categories (catid sebagai unique key)
            $table->bigInteger('category_catid');
            $table->foreign('category_catid')
                  ->references('catid')
                  ->on('categories')
                  ->onDelete('cascade');

            // kombinasi unik supaya tidak ada duplikasi product-category
            $table->unique(['product_item_id', 'category_catid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};