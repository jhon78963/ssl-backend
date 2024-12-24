<?php

namespace App\Room\Resources;

use App\Amenity\Resources\AmenityResource;
use App\Image\Resources\ImageResource;
use App\Rate\Resources\RateResource;
use App\Review\Resources\ReviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'number' => $this->number,
            'roomName' => "Habitación N° $this->number",
            'status' => $this->status,
            'roomStatus' => $this->status->label(),
            'roomTypeId' => $this->room_type_id,
            'roomType' => $this->roomType->description,
            'images' => ImageResource::collection($this->orderDesc()),
            'reviews' => ReviewResource::collection($this->reviews),
        ];
    }

    private function orderDesc() {
        return $this->images()->orderBy('id', 'desc')->get();
    }
}
