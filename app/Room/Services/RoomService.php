<?php

namespace App\Room\Services;

use App\Room\Models\Room;
use Auth;

class RoomService
{
    public function createRoom(array $newRoom): void
    {
        $room = new Room();
        $room->room_number = $newRoom['roomNumber'];
        $room->room_type_id = $newRoom['roomTypeId'];
        $room->status = 'DISPONIBLE';
        $room->creator_user_id = Auth::id();
        $room->save();
    }

    public function updateRoom(Room $room, array $editRoom): void
    {
        $room->room_number = $editRoom['roomNumber'] ?? $room->room_number;
        $room->room_type_id = $editRoom['roomTypeId'] ?? $room->room_type_id;
        $room->last_modification_time = now()->format('Y-m-d H:i:s');
        $room->last_modifier_user_id = Auth::id();
        $room->save();
    }

    public function changeStatusRoom(Room $room, array $editRoom): void
    {
        $room->status = $editRoom['status'];
        $room->last_modification_time = now()->format('Y-m-d H:i:s');
        $room->last_modifier_user_id = Auth::id();
        $room->save();
    }
}
