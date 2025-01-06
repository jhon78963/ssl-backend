<?php

namespace App\Shared\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetAllAddResource extends JsonResource
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
            'name' => $this->name,
            'price' => $this->price,
            'total' => isset($this->pivot) ? $this->price * ($this->pivot->quantity ?? 1) : $this->price,
            'quantity' => $this->pivot->quantity ?? null,
        ];
    }
}
