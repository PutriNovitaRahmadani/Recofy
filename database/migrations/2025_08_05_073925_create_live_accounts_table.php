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
        Schema::create('live_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('studio_id')->constrained()->onDelete('cascade');
            $table->text('cookie_header')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_accounts');
    }
};
