<?php

namespace Database\Seeders;

use App\Rate\Models\RateHour;
use Illuminate\Database\Seeder;

class RateHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rateHour = new RateHour();
        $rateHour->duration = 4;
        $rateHour->save();
    }
}
