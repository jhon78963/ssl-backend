<?php

namespace App\Reservation\Resources;

use App\Customer\Resources\CustomerResource;
use App\Product\Resources\ProductResource;
use App\Service\Resources\ServiceResource;
use Carbon\Carbon;
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
            'id' => $this->id,
            'reservationDate' => $this->dateFormat($this->reservation_date),
            'total' => $this->total,
            'totalString' => "S/ $this->total",
            'status' => $this->status->label(),
            'products' => ProductResource::collection($this->products),
            'services' => ServiceResource::collection($this->services),
        ];

        if ($this->customer_id) {
            $specificData = [
                'customerId' => $this->customer_id,
                'customer' => $this->customer->name . ' ' . $this->customer->surname,
                'lockerId' => $this->locker_id,
                'locker' => 'N°' . $this->locker->number,
            ];
        } else {
            $specificData = [
                'roomId' => $this->room_id,
                'room' => 'Habitación N° ' . $this->room->room_number,
                'customers' => CustomerResource::collection($this->customers),
            ];
        }

        return array_merge($baseData, $specificData);
    }

    private function dateFormat($date): string|null {
        if ($date === null) {
            return null;
        }
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        $date = $date->format('d/m/Y h:i:s A');
        return $date;
    }
}
