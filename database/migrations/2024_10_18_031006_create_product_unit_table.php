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
        Schema::create('product_unit', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->primary(['product_id', 'unit_id']);
            $table->datetime('creation_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('creator_user_id')->nullable();
            $table->foreign('creator_user_id')->references('id')->on('users');
            $table->datetime('last_modification_time')->nullable();
            $table->integer('last_modifier_user_id')->nullable();
            $table->foreign('last_modifier_user_id')->references('id')->on('users');
            $table->boolean('is_deleted')->default(false);
            $table->integer('deleter_user_id')->nullable();
            $table->foreign('deleter_user_id')->references('id')->on('users');
            $table->datetime('deletion_time')->nullable();
            $table->float('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_unit');
    }
};
