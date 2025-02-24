<?php

namespace App\RoomType\Seeders;

use App\RoomType\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomType = new RoomType();
        $roomType->description = 'SINGLE';
        $roomType->capacity = 2;
        $roomType->rental_hours = 4;
        $roomType->price_per_capacity = 100;
        $roomType->price_per_additional_person = 30;
        $roomType->price_per_extra_hour = 30;
        $roomType->age_free = 8;
        $roomType->save();

        $roomType = new RoomType();
        $roomType->description = 'KING';
        $roomType->capacity = 2;
        $roomType->rental_hours = 4;
        $roomType->price_per_capacity = 110;
        $roomType->price_per_additional_person = 30;
        $roomType->price_per_extra_hour = 30;
        $roomType->age_free = 8;
        $roomType->save();

        $roomType = new RoomType();
        $roomType->description = 'VIP';
        $roomType->capacity = 2;
        $roomType->rental_hours = 4;
        $roomType->price_per_capacity = 120;
        $roomType->price_per_additional_person = 30;
        $roomType->price_per_extra_hour = 30;
        $roomType->age_free = 8;
        $roomType->save();
    }
}
