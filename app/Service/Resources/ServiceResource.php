<?php

namespace App\Service\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            ];

        } else {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'times' => $this->prepareData(),
            ];
        }
    }

    private function prepareData(): mixed
    {
        return $this->portions->map(function ($unit): array {
            return [
                'timeString' => $this->getTimeString($unit->quantity),
                'priceString' => 'S/ ' . $unit->pivot->price,
                'time' => $unit->quantity,
                'price' => $unit->pivot->price,
            ];
        });
    }

    private function getTimeString($hour): string
    {
        return match ($hour) {
            "0.5" => "1/2 hora",
            "1" => "$hour hora",
            default => "$hour horas",
        };
    }
}
