<?php

namespace App\Cash\Seeders;

use App\Cash\Models\Cash;
use Illuminate\Database\Seeder;

class CashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cash = new Cash();
        $cash->description = 'Caja Principal';
        $cash->save();
    }
}
