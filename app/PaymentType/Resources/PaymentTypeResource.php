<?php

namespace App\PaymentType\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTypeResource extends JsonResource
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
            'paymentType' => $this->description,
            'paid' => (float) $this->pivot->payment,
            'cash' => (float) $this->pivot->cash_payment,
            'card' => (float) $this->pivot->card_payment,
        ];
    }
}
