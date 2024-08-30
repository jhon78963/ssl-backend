<?php

namespace App\Company\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'businessName' => $this->business_name,
            'representativeLegal' => $this->representative_legal,
            'address' => $this->address,
            'phoneNumber' => $this->phone_number,
            'email' => $this->email,
            'googleMapsLocation' => $this->google_maps_location,
        ];
    }
}
