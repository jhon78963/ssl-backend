<?php

namespace App\Reservation\Resources;

use App\Customer\Resources\CustomerResource;
use App\Product\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseData = [
            'reservationDate' => $this->reservation_date,
            'total' => $this->total,
            'status' => $this->status,
            'products' => ProductResource::collection($this->products),
            'services' => ProductResource::collection($this->services),
        ];

        if ($this->customer_id) {
            $specificData = [
                'customerId' => $this->customer_id,
                'customer' => $this->customer->name . ' ' . $this->customer->surname,
                'locker' => $this->locker,
            ];
        } else {
            $specificData = [
                'roomId' => $this->room_id,
                'room' => "Habitación N° $this->room_number",
                'customers' => CustomerResource::collection($this->customers),
            ];
        }

        return array_merge($baseData, $specificData);
    }
}
