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
        $roomType->status = 'ACTIVO';
        $roomType->save();

        $roomType = new RoomType();
        $roomType->description = 'SUITE';
        $roomType->status = 'ACTIVO';
        $roomType->save();

        $roomType = new RoomType();
        $roomType->description = 'MATRI';
        $roomType->status = 'ACTIVO';
        $roomType->save();

        $roomType = new RoomType();
        $roomType->description = 'SIMPLE';
        $roomType->status = 'ACTIVO';
        $roomType->save();
    }
}
