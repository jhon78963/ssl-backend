<?php

namespace App\Reservation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilitiesResource extends JsonResource
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
            'number' => $this->number,
            'status' => $this->status,
            'price' => $this->price,
            'pricePerAdditionalPerson' => (float)$this->price_per_additional_person ?? 0,
            'pricePerExtraHour' => (float)$this->price_per_extra_hour ?? 0,
            'type' => $this->type,
            'reservationId' => $this->reservation_id,
        ];
    }
}
