<?php

namespace App\Review\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'customerName' => $this->customer_name,
            'description' => $this->description,
            'rating' => $this->rating,
            'roomId' => $this->room_id,
            'room' => "Habitación N° " . $this->room->room_number,
        ];
    }
}
