<?php

namespace App\Locker\Seeders;

use App\Locker\Models\Locker;
use Illuminate\Database\Seeder;

class LockerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 40; $i++) {
            $locker = new Locker();
            $locker->number = $i;
            $locker->price = 30;
            $locker->gender_id = 1;
            $locker->save();
        }

        for ($i = 41; $i <= 56; $i++) {
            $locker = new Locker();
            $locker->number = $i;
            $locker->price = 30;
            $locker->gender_id = 2;
            $locker->save();
        }
    }
}
