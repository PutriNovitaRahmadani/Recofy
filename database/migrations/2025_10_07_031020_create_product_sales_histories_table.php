<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_affiliates', function (Blueprint $table) {
            $table->id();
            $table->string('item_id')->unique();
            $table->string('shop_id')->nullable();
            $table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->string('product_link')->nullable();
            $table->string('seller_name')->nullable();
            $table->decimal('price_min', 15, 2)->nullable();
            $table->decimal('price_max', 15, 2)->nullable();
            $table->decimal('price_min_before_discount', 15, 2)->nullable();
            $table->decimal('price_max_before_discount', 15, 2)->nullable();
            $table->integer('historical_sold')->nullable();
            $table->decimal('commission_rate', 8, 2)->nullable();
            $table->decimal('commission_nominal', 15, 2)->nullable();
            $table->float('rating_star', 3, 2)->nullable();
            $table->integer('rating_count')->nullable();
            $table->integer('liked_count')->nullable();
            $table->integer('category_id')->nullable();
            $table->timestamp('ctime')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_affiliates');
    }
};
