<?php

namespace App\Schedule\Services;

use App\Schedule\Models\Schedule;

class ScheduleService
{
    public function get(bool $isCurrentSchedule = false): mixed
    {
        $currentTime = now()->format('H:i:s');
        $schedules = Schedule::where('is_deleted', '=', false)->get();

        $currentSchedule = null;
        foreach ($schedules as $schedule) {
            if ($currentTime >= $schedule->start_time || $currentTime <= $schedule->end_time) {
                $currentSchedule = $isCurrentSchedule ? $schedule : $schedule->id;
            }
        }
        return $currentSchedule;
    }
}
