<?php

namespace App\Room\Seeders;

use App\PaymentType\Models\PaymentType;
use App\Room\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $room = new Room();
        $room->number = '201';
        $room->room_type_id = 3;
        $room->description = 'Disco';
        $room->save();

        $room = new Room();
        $room->number = '202';
        $room->room_type_id = 1;
        $room->description = 'Pekín';
        $room->save();

        $room = new Room();
        $room->number = '203';
        $room->room_type_id = 1;
        $room->description = 'Japón';
        $room->save();

        $room = new Room();
        $room->number = '204';
        $room->room_type_id = 2;
        $room->description = 'Karaoke I';
        $room->save();

        $room = new Room();
        $room->number = '205';
        $room->room_type_id = 1;
        $room->description = 'Joker';
        $room->save();

        $room = new Room();
        $room->number = '206';
        $room->room_type_id = 1;
        $room->description = 'Cancún';
        $room->save();

        $room = new Room();
        $room->number = '207';
        $room->room_type_id = 1;
        $room->description = 'Zahara';
        $room->save();

        $room = new Room();
        $room->number = '208';
        $room->room_type_id = 1;
        $room->description = 'El padrino';
        $room->save();

        $room = new Room();
        $room->number = '209';
        $room->room_type_id = 1;
        $room->description = 'Egipto';
        $room->save();

        $room = new Room();
        $room->number = '210';
        $room->room_type_id = 3;
        $room->description = 'Karaoke II';
        $room->save();

        $room = new Room();
        $room->number = '211';
        $room->room_type_id = 1;
        $room->description = 'Quino';
        $room->save();

        $room = new Room();
        $room->number = '212';
        $room->room_type_id = 3;
        $room->description = 'Edén';
        $room->save();
    }
}
