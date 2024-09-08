<?php

namespace App\Rate\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateHourResource extends JsonResource
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
            'durationNumber'=> $this->duration,
            'duration'=> $this->duration == 1 ? $this->duration . ' día' : $this->duration . ' días',
        ];
    }
}
