<?php

namespace App\Schedule\Seeders;

use App\Schedule\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedule = new Schedule();
        $schedule->description = 'Día';
        $schedule->start_time = '08:00';
        $schedule->end_time = '18:59:59';
        $schedule->save();

        $schedule = new Schedule();
        $schedule->description = 'Noche';
        $schedule->start_time = '19:00';
        $schedule->end_time = '07:00';
        $schedule->save();
    }
}
