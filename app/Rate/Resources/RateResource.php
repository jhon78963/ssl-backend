<?php

namespace App\Rate\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
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
            'price' => $this->price,
            'hourId' => $this->hour_id,
            'hour' => $this->hour->duration,
            'dayId' => $this->day_id,
            'day' => $this->day->name,
        ];
    }
}
