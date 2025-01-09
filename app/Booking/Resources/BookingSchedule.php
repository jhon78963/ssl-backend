<?php

namespace App\Booking\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingSchedule extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'conflict' => $this->conflict,
            'conflictingStartDate' => $this->conflicting_start_date,
            'conflictingEndDate' => $this->conflicting_end_date,
        ];
    }
}
