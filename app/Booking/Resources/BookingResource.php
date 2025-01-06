<?php

namespace App\Booking\Resources;

use App\Booking\Resources\RoomsResource;
use App\PaymentType\Resources\PaymentTypeResource;
use App\Service\Resources\ServiceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BookingResource extends JsonResource
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
            'startDate' => $this->dateFormat($this->start_date),
            'endDate' => $this->dateFormat($this->end_date),
            'total' => $this->total,
            'peopleExtraImport' => $this->people_extra_import ?? 0,
            'facilitiesImport' => $this->facilities_import ?? 0,
            'consumptionsImport' => $this->consumptions_import ?? 0,
            'status' => $this->status->label(),
            'customer' => [
                'id' => $this->customer_id,
                'name' => $this->customer->name,
                'surname' => $this->customer->surname,
            ],
            'paymentTypes' => PaymentTypeResource::collection($this->paymentTypes),
            'products' => ProductsResource::collection($this->products),
            'services' => ServiceResource::collection($this->services),
            'facilities' => $this->rooms->isNotEmpty()
                ? RoomsResource::collection($this->rooms)
                : null,
            'notes' => $this->notes,
            'title' => $this->description,
        ];
    }

    private function dateFormat($date): string|null {
        if ($date === null) {
            return null;
        }
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        $date->format('d/m/Y h:i:s A');
        return $date;
    }
}
