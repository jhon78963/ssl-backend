<?php

namespace App\Product\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'quantity' => $this->pivot->quantity,
            'total' => $this->price * $this->pivot->quantity,
            'isPaid' => $this->pivot->is_paid,
            'isPaidBd' => $this->pivot->is_paid,
            'isFree' => $this->pivot->is_free,
            'isFreeBd' => $this->pivot->is_free,
            'isBd' => true,
            'type' => 'product'
        ];
    }
}
