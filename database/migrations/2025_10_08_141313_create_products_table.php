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

        Schema::create('products', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('stock')->nullable();
            $table->string('image', 255)->nullable();
            $table->integer('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
