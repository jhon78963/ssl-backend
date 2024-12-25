<?php

namespace App\RoomType\Resources;

use App\Amenity\Resources\AmenityResource;
use App\Rate\Resources\RateResource;
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
            'capacityTable' => $this->getPersonLabel($this->capacity),
            'rentalHours' => $this->rental_hours,
            'rentalHoursTable' => $this->getHourLabel($this->rental_hours),
            'pricePerCapacity'=> (float) $this->price_per_capacity,
            'pricePerCapacityTable'=> "S/ $this->price_per_capacity",
            'pricePerAdditionalPerson'=> (float) $this->price_per_additional_person,
            'pricePerAdditionalPersonTable'=>"S/ $this->price_per_additional_person",
            'pricePerExtraHour'=> (float) $this->price_per_extra_hour,
            'pricePerExtraHourTable'=>"S/ $this->price_per_extra_hour",
            'ageFree'=> $this->age_free,
            'ageFreeTable'=> $this->getAgeLabel($this->age_free),
            'amenities' => AmenityResource::collection($this->amenities),
            'rates' => RateResource::collection($this->rates),
        ];
    }

    private function getAgeLabel($age): string {
        return match ($age) {
            1   => "1 año",
            default        => "$age años",
        };
    }

    private function getPersonLabel($capacity): string {
        return match ($capacity) {
            '1'   => "1 persona",
            default        => "$capacity personas",
        };
    }

    private function getHourLabel($hour): string {
        return match ($hour) {
            1   => "1 hora",
            default        => "$hour horas",
        };
    }
}
