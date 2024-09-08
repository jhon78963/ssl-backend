<?php

namespace App\Room\Services;
use App\Room\Models\Room;
use App\Room\Models\RoomType;
use Auth;

class RoomService
{
    public function createRoom(array $newRoom): void
    {
        $room = new Room();
        $room->room_number = $newRoom['roomNumber'];
        $room->capacity = $newRoom['capacity'];
        $room->status = $newRoom['status'];
        $room->room_type_id = $newRoom['roomTypeId'];
        $room->creator_user_id = Auth::id();
        $room->save();
    }

    public function updateRoom(Room $room, array $editRoom): void
    {
        $room->room_number = $editRoom['roomNumber'] ?? $room->room_number;
        $room->capacity = $editRoom['capacity'] ?? $room->capacity;
        $room->status = $editRoom['status'] ?? $room->status;
        $room->room_type_id = $editRoom['roomTypeId'] ?? $room->room_type_id;
        $room->last_modification_time = now()->format('Y-m-d H:i:s');
        $room->last_modifier_user_id = Auth::id();
        $room->save();
    }
}
