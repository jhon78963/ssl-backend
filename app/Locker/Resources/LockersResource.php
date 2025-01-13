<?php

namespace App\Locker\Resources;

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
        // $reservation = $this->reservationsInUse->first();

        return [
            'id' => $this->id,
            'number' => "L$this->number",
            'price' => $this->price,
            'status' => $this->status,
            'genderId' => $this->gender_id,
            'gender' => $this->gender->name,
            'type' => 'locker',
        ];
    }
}
