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
        $roomType->capacity = $newRoomType['capacity'];
        $roomType->price_per_capacity = $newRoomType['pricePerCapacity'];
        $roomType->price_per_additional_person = $newRoomType['pricePerAdditionalPerson'];
        $roomType->age_free = $newRoomType['ageFree'];
        $roomType->creator_user_id = Auth::id();
        $roomType->save();
    }

    public function updateRoomType(RoomType $roomType, array $editRoomType): void
    {
        $roomType->description = $editRoomType['description'] ?? $roomType->description;
        $roomType->capacity = $editRoomType['capacity'] ?? $roomType->capacity;
        $roomType->price_per_capacity = $editRoomType['pricePerCapacity'] ?? $roomType->price_per_capacity;
        $roomType->price_per_additional_person = $editRoomType['pricePerAdditionalPerson'] ?? $roomType->price_per_additional_person;
        $roomType->age_free = $editRoomType['ageFree'] ?? $roomType->age_free;
        $roomType->last_modification_time = now()->format('Y-m-d H:i:s');
        $roomType->last_modifier_user_id = Auth::id();
        $roomType->save();
    }
}
