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
        Schema::create('products', function (Blueprint $table) {
            // jadikan item_id sebagai primary key
            $table->unsignedBigInteger('item_id')->primary(); // Shopee item id

            $table->string('title');
            $table->string('image')->nullable();
            
            // simpan ctime dalam bentuk epoch (Unix timestamp)
            $table->bigInteger('ctime')->nullable();

            // review data
            $table->float('rating_star')->nullable();
            $table->integer('rating_count')->default(0);
            $table->integer('liked_count')->default(0);

            // harga
            $table->bigInteger('price_min')->nullable();
            $table->bigInteger('price_max')->nullable();

            // penjualan
            $table->integer('historical_sold')->default(0);

            // stok barang
            $table->integer('stock')->default(0);

            // link & komisi
            $table->string('product_link')->nullable();
            $table->bigInteger('commission')->nullable();
            $table->bigInteger('seller_commission')->nullable();
            $table->bigInteger('shopee_commission')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};