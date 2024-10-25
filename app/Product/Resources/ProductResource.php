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
        if ($this->price) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'price' => $this->price,
                'priceString' => "S/ $this->price",
                'productTypeId' => $this->product_type_id,
                'productType' => $this->productType->description,
            ];
        } else {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'portions' => $this->prepareData(),
            ];
        }
    }

    private function prepareData(): mixed
    {
        return $this->portions->map(function ($unit): array {
            return [
                'timeString' => $this->getTimeString($unit->quantity),
                'priceString' => 'S/ ' . $unit->pivot->price,
                'portion' => $unit->quantity,
                'price' => $unit->pivot->price,
            ];
        });
    }

    private function getTimeString($portion): string
    {
        return match ($portion) {
            "1" => "$portion unidad",
            default => "$portion unidades",
        };
    }
}