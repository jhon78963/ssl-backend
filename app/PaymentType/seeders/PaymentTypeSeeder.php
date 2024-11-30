<?php

namespace App\PaymentType\Seeders;

use App\PaymentType\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentTYpe = new PaymentType();
        $paymentTYpe->description = 'Efectivo';
        $paymentTYpe->save();

        $paymentTYpe = new PaymentType();
        $paymentTYpe->description = 'Tarjeta';
        $paymentTYpe->save();

        $paymentTYpe = new PaymentType();
        $paymentTYpe->description = 'Mixto';
        $paymentTYpe->save();
    }
}
