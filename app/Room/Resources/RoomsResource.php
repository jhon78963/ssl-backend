<?php

namespace App\Room\Resources;

use App\Amenity\Resources\AmenityResource;
use App\Image\Resources\ImageResource;
use App\Rate\Resources\RateResource;
use App\Review\Resources\ReviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomsResource extends JsonResource
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
            'number' => "R$this->number",
            'price' => $this->roomType->price_per_capacity,
            'status' => $this->status,
            'type' => 'room'
        ];
    }

    private function orderDesc() {
        return $this->images()->orderBy('id', 'desc')->get();
    }
}
