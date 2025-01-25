<?php

namespace App\Customer\Seeders;

use App\Customer\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = new Customer();
        $customer->name = 'Clientes';
        $customer->surname = 'Varios';
        $customer->dni = '99999999';
        $customer->save();
    }
}
