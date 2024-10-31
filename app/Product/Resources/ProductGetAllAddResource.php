<?php

namespace App\Product\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductGetAllAddResource extends JsonResource
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
            'total' => isset($this->pivot) && $this->pivot->quantity ? $this->price * ($this->pivot->quantity ?? 1) : $this->price,
            'quantity' => $this->pivot->quantity ?? null,
            'productType' => $this->productType->description,
        ];
    }
}
