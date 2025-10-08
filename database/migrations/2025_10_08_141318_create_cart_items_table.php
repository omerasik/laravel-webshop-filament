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
        Schema::disableForeignKeyConstraints();

        Schema::create('cart_items', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->integer('cart_id')->nullable();
            $table->foreign('cart_id')->references('id')->on('carts');
            $table->integer('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('quantity')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
