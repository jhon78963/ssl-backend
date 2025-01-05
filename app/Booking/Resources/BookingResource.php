<?php

namespace App\Booking\Resources;

use App\Reservation\Resources\RoomsResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'schedule' => $this->schedule->description,
            'total' => $this->total,
            'peopleExtraImport' => $this->people_extra_import ?? 0,
            'facilitiesImport' => $this->facilities_import ?? 0,
            'status' => $this->status->label(),
            'customer' => [
                'id' => $this->customer_id,
                'name' => $this->customer->name,
                'surname' => $this->customer->surname,
            ],
            'facilities' => $this->rooms->isNotEmpty()
                ? RoomsResource::collection($this->rooms)
                : null,
            'notes' => $this->notes,
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
