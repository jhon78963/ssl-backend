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
        return [
            'id' => $this->id,
            'reservationDate' => $this->dateFormat($this->reservation_date),
            'total' => $this->total,
            'status' => $this->status->label(),
            'products' => ProductResource::collection($this->products),
            'services' => ServiceResource::collection($this->services),
            'customer' => [
                'id' => $this->customer_id,
                'name' => $this->customer->name,
                'surname' => $this->customer->surname,
            ],
            'facilities' => $this->rooms->isNotEmpty()
                ? RoomsResource::collection($this->rooms)
                : LockersResource::collection($this->lockers),
        ];
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
