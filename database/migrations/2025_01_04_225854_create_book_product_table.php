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
        Schema::create('book_product', function (Blueprint $table) {
            $table->unsignedBigInteger(column: 'book_id');
            $table->foreign('book_id')->references('id')->on('books');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_free')->default(false);
            $table->primary(['book_id', 'product_id', 'is_paid', 'is_free']);
            $table->integer('quantity');
            $table->float('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_product');
    }
};
