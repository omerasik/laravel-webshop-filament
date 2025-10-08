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

        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->integer('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->decimal('price', 8, 2)->nullable();
            $table->string('order_status', 100)->nullable();
            $table->string('payment_status', 100)->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->string('payment_address', 255)->nullable();
            $table->string('shipping_address', 255)->nullable();
            $table->integer('zip')->nullable();
            $table->integer('bus')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
