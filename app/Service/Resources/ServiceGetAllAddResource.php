<?php

namespace App\Service\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceGetAllAddResource extends JsonResource
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
            'quantity' => $this->pivot->quantity ?? null,
            'price' => isset($this->pivot) ? $this->price * ($this->pivot->quantity ?? 1) : $this->price,
        ];
    }
}
