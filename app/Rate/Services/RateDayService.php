<?php

namespace App\Rate\Services;

use App\Rate\Models\RateDay;
use Auth;

class RateDayService
{
    public function createRateDay(array $newRateDay): void
    {
        $rateDay = new RateDay();
        $rateDay->name = $newRateDay['name'];
        $rateDay->abbreviation = $newRateDay['abbreviation'];
        $rateDay->creator_user_id = Auth::id();
        $rateDay->save();
    }

    public function updateRateDay(RateDay $rateDay, array $editRateDay): void
    {
        $rateDay->name = $editRateDay['name'] ?? $rateDay->name;
        $rateDay->abbreviation = $editRateDay['abbreviation'] ?? $rateDay->abbreviation;
        $rateDay->last_modification_time = now()->format('Y-m-d H:i:s');
        $rateDay->last_modifier_user_id = Auth::id();
        $rateDay->save();
    }
}
