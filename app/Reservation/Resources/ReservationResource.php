<?php

namespace App\Reservation\Resources;

use App\PaymentType\Resources\PaymentTypeResource;
use App\Product\Resources\ProductResource;
use App\Service\Resources\ServiceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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
            'startDateF' => $this->dateFormat($this->start_date),
            'endDate' => $this->dateFormat($this->end_date),
            'startDate' => $this->start_date,
            'reservationType' => $this->reservationType->description,
            'schedule' => $this->schedule->description,
            'cash' => $this->cashOperation?->cash?->name,
            'total' => $this->total,
            'totalPaid' => $this->total_paid,
            'peopleExtraImport' => $this->people_extra_import ?? 0,
            'hoursExtraImport' => $this->hours_extra_import ?? 0,
            'facilitiesImport' => $this->facilities_import ?? 0,
            'consumptionsImport' => $this->consumptions_import ?? 0,
            'brokenThingsImport' => $this->broken_things_import ?? 0,
            'statusLabel' => $this->status->label(),
            'status' => $this->status,
            'paymentTypes' => PaymentTypeResource::collection($this->paymentTypes),
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
            'notes' => $this->notes,
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
