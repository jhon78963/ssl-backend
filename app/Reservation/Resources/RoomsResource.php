<?php

namespace App\Reservation\Resources;

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
            'id' => $this->id,
            'number' => "R$this->number",
            'status' => $this->status,
            'price' => $this->roomType->price_per_capacity,
            'pricePerAdditionalPerson' => $this->roomType->price_per_additional_person,
            'additionalPeople' => $this->pivot->additional_people,
            'pricePerExtraHour' => $this->roomType->price_per_extra_hour,
            'extraHours' => $this->pivot->extra_hours,
            'isPaid' => $this->pivot->is_paid,
            'type' => 'room',
            'isBd' => true,
        ];
    }
}
