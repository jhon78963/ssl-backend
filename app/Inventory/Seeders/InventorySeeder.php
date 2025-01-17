<?php

namespace App\Inventory\Seeders;

use App\Inventory\Models\Inventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventory = new Inventory();
        $inventory->description = 'Toalla';
        $inventory->stock = 0;
        $inventory->stock_in_use = 0;
        $inventory->save();

        $inventory = new Inventory();
        $inventory->description = 'Sandalias';
        $inventory->stock = 0;
        $inventory->stock_in_use = 0;
        $inventory->save();

        $inventory = new Inventory();
        $inventory->description = 'Llave';
        $inventory->stock = 0;
        $inventory->stock_in_use = 0;
        $inventory->save();
    }
}
