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
        $schedule->description = 'Tarde';
        $schedule->save();

        $schedule = new Schedule();
        $schedule->description = 'Noche';
        $schedule->save();
    }
}
