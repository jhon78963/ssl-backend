<?php

namespace App\Rate\Services;

use App\Rate\Models\Rate;
use Auth;

class RateService
{
    public function createRate(array $newRate): void
    {
        $rate = new Rate();
        $rate->price = $newRate['price'];
        $rate->hour_id = $newRate['hourId'];
        $rate->day_id = $newRate['dayId'];
        $rate->creator_user_id = Auth::id();
        $rate->save();
    }

    public function updateRate(Rate $rate, array $editRate): void
    {
        $rate->price = $editRate['price'] ?? $rate->price;
        $rate->hour_id = $editRate['hourId'] ?? $rate->hour_id;
        $rate->day_id = $editRate['dayId'] ?? $rate->day_id;
        $rate->last_modification_time = now()->format('Y-m-d H:i:s');
        $rate->last_modifier_user_id = Auth::id();
        $rate->save();
    }
}
