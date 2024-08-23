<?php

namespace App\Auth\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'name' => $this->name,
            'surname' => $this->surname,
            'profilePicture' => $this->profile_picture,
            'role' => $this->role->name,
        ];
    }
}
