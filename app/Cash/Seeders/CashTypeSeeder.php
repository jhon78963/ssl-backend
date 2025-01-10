<?php

namespace App\Cash\Seeders;

use App\Cash\Models\CashType;
use Illuminate\Database\Seeder;

class CashTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cashType = new CashType();
        $cashType->key = 'CASH_OPENING';
        $cashType->label = 'Apertura de caja';
        $cashType->save();

        $cashType = new CashType();
        $cashType->key = 'CASH_INCOME';
        $cashType->label = 'Ingreso';
        $cashType->save();

        $cashType = new CashType();
        $cashType->key = 'CASH_EXPENSE';
        $cashType->label = 'Egreso';
        $cashType->save();

        $cashType = new CashType();
        $cashType->key = 'CASH_CLOSURE';
        $cashType->label = 'Cierre de caja';
        $cashType->save();
    }
}
