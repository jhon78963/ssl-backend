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
            'pricePerAdditionalPerson' => $this->roomType->price_per_additional_person * $this->pivot->additional_people,
            'additionalPeople' => $this->pivot->additional_people,
            'isPaid' => $this->pivot->is_paid,
            'type' => 'room',
            'isBd' => true,
        ];
    }
}
