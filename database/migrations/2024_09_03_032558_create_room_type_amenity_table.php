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
        Schema::create('amenity_room_type', function (Blueprint $table) {
            $table->unsignedBigInteger('room_type_id');
            $table->foreign('room_type_id')->references('id')->on('room_types');
            $table->unsignedBigInteger('amenity_id');
            $table->foreign('amenity_id')->references('id')->on('amenities');
            $table->primary(['room_type_id', 'amenity_id']);
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenity_room_type');
    }
};
