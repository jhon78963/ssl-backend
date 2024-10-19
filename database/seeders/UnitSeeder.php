<?php

namespace Database\Seeders;

use App\Unit\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unit = new Unit();
        $unit->quantity = 6;
        $unit->unit = "unidades";
        $unit->save();

        $unit = new Unit();
        $unit->quantity = 12;
        $unit->unit = "unidades";
        $unit->save();

        $unit = new Unit();
        $unit->quantity = 0.5;
        $unit->unit = "hora";
        $unit->save();

        $unit = new Unit();
        $unit->quantity = 1;
        $unit->unit = "hora";
        $unit->save();
    }
}
