<?php

namespace App\Schedule\Services;

use App\Schedule\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;

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

    private function prependSchedule(): Schedule {
        $schedule = new Schedule();
        $schedule->id = 0;
        $schedule->description = 'Todos';
        return $schedule;
    }

    public function getAll(): Collection {
        $schedules = Schedule::whereHas('reservations')->get();
        $schedules->prepend($this->prependSchedule());
        return $schedules;
    }
}
