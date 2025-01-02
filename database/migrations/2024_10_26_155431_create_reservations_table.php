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
        Schema::create('reservations', function (Blueprint $table) {
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
            $table->integer('reservation_type_id');
            $table->foreign('reservation_type_id')->references('id')->on('reservation_types');
            $table->integer('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->integer('schedule_id')->nullable();
            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->float('total')->nullable();
            $table->float('total_paid')->nullable();
            $table->float('people_extra_import')->nullable();
            $table->float('hours_extra_import')->nullable();
            $table->float('facilities_import')->nullable();
            $table->float('consumptions_import')->nullable();
            $table->float('broken_things_import')->nullable();
            $table->string('notes')->nullable();
            $table->enum('status', ['IN_USE', 'COMPLETED', 'CANCELLED'])->default('IN_USE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
