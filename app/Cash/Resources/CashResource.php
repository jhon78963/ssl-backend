<?php

namespace App\Cash\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashResource extends JsonResource
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
            'cash' => $this->cash->description,
            'cashType' => $this->cashType->label,
            'schedule' => $this->schedule->description,
            'pettyCash' => $this->petty_cash_amount,
            'initialAmount' => $this->initial_amount,
            'employee' => $this->name,
        ];
    }
}
