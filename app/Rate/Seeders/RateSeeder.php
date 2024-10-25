<?php

namespace App\Rate\Seeders;

use App\Rate\Models\Rate;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prices = [1 => 80, 2 => 90, 3 => 100];
        for ($day = 1; $day <= 7; $day++) {
            foreach ($prices as $roomType => $price) {
                $rate = new Rate();
                $rate->day_id = $day;
                $rate->hour_id = 1;
                $rate->price = $price;
                $rate->save();
                $rate->roomTypes()->attach($roomType);
            }
        }
    }
}
