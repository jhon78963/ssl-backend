<?php

namespace App\Reservation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LockersResource extends JsonResource
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
            'number' => "L$this->number",
            'status' => $this->status,
            'price' => $this->price,
            'isPaid' => $this->pivot->is_paid,
            'consumption' => (float)$this->pivot->consumption,
            'type' => 'locker',
            'isBd' => true,
            'isSelected' => false,
        ];
    }
}
