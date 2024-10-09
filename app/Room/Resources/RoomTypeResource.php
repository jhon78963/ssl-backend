<?php

namespace App\Room\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description'=> $this->description,
            'capacity'=> $this->capacity,
            'capacityTable'=> $this->getPersonLabel($this->capacity),
            'pricePerCapacity'=> (float) $this->price_per_capacity,
            'pricePerCapacityTable'=> "S/ $this->price_per_capacity",
            'pricePerAdditionalPerson'=> (float) $this->price_per_additional_person,
            'pricePerAdditionalPersonTable'=>"S/ $this->price_per_additional_person",
            'ageFree'=> $this->age_free,
            'ageFreeTable'=> $this->getAgeLabel($this->age_free),
        ];
    }

    private function getAgeLabel($age): string {
        return match ($age) {
            '1'   => "1 año",
            default        => "$age años",
        };
    }

    private function getPersonLabel($capacity): string {
        return match ($capacity) {
            '1'   => "1 persona",
            default        => "$capacity personas",
        };
    }
}
