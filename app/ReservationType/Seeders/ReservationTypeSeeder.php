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
        $bookType = new ReservationType();
        $bookType->description = 'General';
        $bookType->save();

        $bookType = new ReservationType();
        $bookType->description = 'Privado';
        $bookType->save();

        $bookType = new ReservationType();
        $bookType->description = 'Personal';
        $bookType->save();
    }
}
