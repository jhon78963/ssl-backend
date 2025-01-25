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
        Schema::create('cash_operations', function (Blueprint $table) {
            $table->id();
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
            $table->integer('cash_id')->nullable();
            $table->foreign('cash_id')->references('id')->on('cashes');
            $table->integer('cash_type_id')->nullable();
            $table->foreign('cash_type_id')->references('id')->on('cash_types');
            $table->integer('schedule_id')->nullable();
            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->integer('reservation_id')->nullable();
            $table->foreign('reservation_id')->references('id')->on('reservations');
            $table->integer('booking_id')->nullable();
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->datetime('date');
            $table->string('description')->nullable();
            $table->float('amount')->default(0);
            $table->float('cash_amount')->default(0);
            $table->float('card_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_operations');
    }
};
