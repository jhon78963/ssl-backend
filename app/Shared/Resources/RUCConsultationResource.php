<?php

namespace App\Shared\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RUCConsultationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ruc' => $this->numeroDocumento,
            'businessName' => $this->nombre,
            'fiscalAddress' => $this->direccion,
            'status' => $this->estado,
            'condition' => $this->condicion,
        ];
    }
}
