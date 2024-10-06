<?php

namespace App\Room\Resources;

use App\Amenity\Resources\AmenityResource;
use App\Rate\Resources\RateResource;
use App\Review\Resources\ReviewResource;
use App\Shared\Resources\FileResource;
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
            'id'=> $this->id,
            'roomNumber' => $this->room_number,
            'roomName' => "Habitación N° $this->room_number",
            'capacity' => $this->capacity,
            'status' => $this->status,
            'roomStatus' => $this->getLabelStatus($this->status),
            'roomTypeId' => $this->room_type_id,
            'roomType' => $this->roomType->description,
            'images' => FileResource::collection($this->images),
            'amenities' => AmenityResource::collection($this->amenities),
            'rates' => RateResource::collection($this->rates),
            'reviews' => ReviewResource::collection($this->reviews),
        ];
    }

    private function getLabelStatus($status): string {
        return match ($status) {
            'DISPONIBLE'   => 'Disponible',
            'OCUPADO'      => 'Ocupado',
            'EN_LIMPIEZA'  => 'En Limpieza',
            default        => 'Estado desconocido',
        };
    }
}
