<?php

namespace App\Room\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'roomNumber' => $this->room_number,
            'capacity' => $this->capacity,
            'status' => $this->status,
            'roomTypeId' => $this->room_type_id,
        ];
    }
}
