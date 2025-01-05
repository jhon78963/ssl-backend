<?php

namespace App\ReservationType\Seeders;

use App\ReservationType\Models\ReservationType;
use Illuminate\Database\Seeder;

class ReservationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservationType = new ReservationType();
        $reservationType->description = 'General';
        $reservationType->save();

        $reservationType = new ReservationType();
        $reservationType->description = 'Privado';
        $reservationType->save();

        $reservationType = new ReservationType();
        $reservationType->description = 'Personal';
        $reservationType->save();
    }
}
