<?php

namespace App\Locker\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LockerResource extends JsonResource
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
            'price' => $this->price,
            'priceString' => "S/ $this->number",
            'status' => $this->status->label(),
            'genderId' => $this->gender_id,
            'gender' => $this->gender->name,
            'reservationId' => $reservation ? $reservation->id : null,
            'customerId' => $reservation && $reservation->customer ? $reservation->customer->id : null,
            'customerName' => $reservation && $reservation->customer ? $reservation->customer->name : null,
            'customerSurname' => $reservation && $reservation->customer ? $reservation->customer->surname : null,
            'customerDni' => $reservation && $reservation->customer ? $reservation->customer->dni : null,
        ];
    }
}
