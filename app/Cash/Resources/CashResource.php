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
        $data = [
            'id' => $this->id,
            'date' => $this->date,
            'cash' => $this->cash->description,
            'cashType' => $this->cashType->label,
            'cashTypeKey' => $this->cashType->key,
            'schedule' => $this->schedule->description,
            'pettyCash' => $this->cash->petty_cash_amount,
        ];

        $data['pettyCash'] = in_array($this->cashType->key, ['CASH_OPENING', 'CASH_CLOSURE'])
            ? $this->cash->petty_cash_amount
            : 0;

        $data['amount'] = $this->amount;
        $data['employee'] = $this->cash->name;

        return $data;
    }
}
