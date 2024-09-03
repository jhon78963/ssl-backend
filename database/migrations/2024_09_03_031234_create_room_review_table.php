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
        Schema::create('room_review', function (Blueprint $table) {
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('review_id')->nullable();
            $table->primary(['room_id', 'review_id']);
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
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('review_id')->references('id')->on('reviews');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_review');
    }
};
