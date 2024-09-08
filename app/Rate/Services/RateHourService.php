<?php

namespace App\Rate\Services;

use App\Rate\Models\RateHour;
use Auth;

class RateHourService
{
    public function createRateHour(array $newrateHour): void
    {
        $rateHour = new RateHour();
        $rateHour->duration = $newrateHour['duration'];
        $rateHour->creator_user_id = Auth::id();
        $rateHour->save();
    }

    public function updateRateHour(RateHour $rateHour, array $editrateHour): void
    {
        $rateHour->duration = $editrateHour['duration'] ?? $rateHour->duration;
        $rateHour->last_modification_time = now()->format('Y-m-d H:i:s');
        $rateHour->last_modifier_user_id = Auth::id();
        $rateHour->save();
    }
}
