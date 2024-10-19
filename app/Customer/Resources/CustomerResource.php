<?php

namespace App\Customer\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'dni' => $this->dni,
            'name' => $this->name,
            'surname' => $this->surname,
            'cellphone' => $this->cellphone,
            'email' => $this->email,
            'genderId' => $this->gender_id,
            'gender' => $this->gender->name,
        ];
    }
}
