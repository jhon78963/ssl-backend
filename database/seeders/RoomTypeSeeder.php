<?php

namespace Database\Seeders;

use App\Room\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomType = new RoomType();
        $roomType->description = 'VIP';
        $roomType->save();

        $roomType = new RoomType();
        $roomType->description = 'SUITE';
        $roomType->save();

        $roomType = new RoomType();
        $roomType->description = 'MATRI';
        $roomType->save();

        $roomType = new RoomType();
        $roomType->description = 'SIMPLE';
        $roomType->save();
    }
}
