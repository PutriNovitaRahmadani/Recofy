<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_affiliates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->unique();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->string('product_link')->nullable();
            $table->string('seller_name')->nullable();

            $table->decimal('price_min', 15, 2)->nullable();
            $table->decimal('price_max', 15, 2)->nullable();
            $table->decimal('price_min_before_discount', 15, 2)->nullable();
            $table->decimal('price_max_before_discount', 15, 2)->nullable();

            $table->integer('historical_sold')->default(0);
            $table->decimal('commission_rate', 8, 2)->default(0);
            $table->decimal('commission_nominal', 15, 2)->default(0);
            $table->decimal('rating_star', 4, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->integer('liked_count')->default(0);

            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamp('ctime')->nullable();

            // Kolom tambahan untuk trend
            $table->decimal('trend_score', 10, 2)->default(0); // ✅ untuk nilai trend

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_affiliates');
    }
};
