<?php

namespace App\Room\Services;

use App\Room\Models\RoomType;
use Auth;

class RoomTypeService
{
    public function createRoomType(array $newRoomType): void
    {
        $roomType = new RoomType();
        $roomType->description = $newRoomType['description'];
        $roomType->creator_user_id = Auth::id();
        $roomType->save();
    }

    public function updateRoomType(RoomType $roomType, array $editRoomType): void
    {
        $roomType->description = $editRoomType['description'] ?? $roomType->description;
        $roomType->last_modification_time = now()->format('Y-m-d H:i:s');
        $roomType->last_modifier_user_id = Auth::id();
        $roomType->save();
    }
}
