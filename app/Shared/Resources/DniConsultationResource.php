<?php

namespace App\Shared\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DniConsultationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'dni' => $this->numeroDocumento ?? null,
            'name' => $this->nombres ?? null,
            'surname' => isset($this->apellidoPaterno, $this->apellidoMaterno) ? "$this->apellidoPaterno $this->apellidoMaterno" : null,
        ];
    }
}
