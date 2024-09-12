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
        $baseUrl = config('app.url') . '/storage';
        return [
            'roomNumber' => $this->room_number,
            'capacity' => $this->capacity,
            'status' => $this->status,
            'roomType' => $this->roomType->description,
            'images' => $this->images->map(function ($image) use ($baseUrl): string {
                return "$baseUrl/$image->file_path";
            }),
        ];
    }
}
