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
        $reservation = $this->reservationsInUse->first();

        return [
            'id' => $this->id,
            'number' => $this->number,
            'status' => $this->status,
            'price' => $this->price,
            'type' => $this->type,
            'reservationId' => $reservation?->id,
        ];
    }
}
