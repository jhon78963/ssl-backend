<?php

namespace Database\Seeders;

use App\Rate\Models\RateDay;
use Illuminate\Database\Seeder;

class RateDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rateDay = new RateDay();
        $rateDay->name = 'Lunes';
        $rateDay->abbreviation = 'LUN';
        $rateDay->save();

        $rateDay = new RateDay();
        $rateDay->name = 'Martes';
        $rateDay->abbreviation = 'MAR';
        $rateDay->save();

        $rateDay = new RateDay();
        $rateDay->name = 'Miércoles';
        $rateDay->abbreviation = 'MIÉ';
        $rateDay->save();

        $rateDay = new RateDay();
        $rateDay->name = 'Jueves';
        $rateDay->abbreviation = 'JUE';
        $rateDay->save();

        $rateDay = new RateDay();
        $rateDay->name = 'Viernes';
        $rateDay->abbreviation = 'VIE';
        $rateDay->save();

        $rateDay = new RateDay();
        $rateDay->name = 'Sábado';
        $rateDay->abbreviation = 'SÁB';
        $rateDay->save();

        $rateDay = new RateDay();
        $rateDay->name = 'Domingo';
        $rateDay->abbreviation = 'DOM';
        $rateDay->save();

    }
}
