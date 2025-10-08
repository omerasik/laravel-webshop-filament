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

        Schema::create('order_items', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->integer('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->integer('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('quantity')->nullable();
            $table->decimal('price', 8, 2)->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
