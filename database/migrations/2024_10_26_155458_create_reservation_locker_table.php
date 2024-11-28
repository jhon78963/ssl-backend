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
        Schema::create('locker_reservation', function (Blueprint $table) {
            $table->unsignedBigInteger('reservation_id');
            $table->foreign('reservation_id')->references('id')->on('reservations');
            $table->unsignedBigInteger('locker_id');
            $table->foreign('locker_id')->references('id')->on('lockers');
            $table->primary(['reservation_id', 'locker_id']);
            $table->float('price')->default(0);
            $table->integer('quantity')->default(1);
            $table->boolean('is_paid')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locker_reservation');
    }
};
