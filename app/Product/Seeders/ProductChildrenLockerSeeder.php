<?php

namespace App\Product\Seeders;

use App\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductChildrenLockerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->name = 'Niños menores a 13 años';
        $product->price = 20;
        $product->product_type_id = 8;
        $product->save();

        $product = new Product();
        $product->name = 'Niños mayores a 14 años';
        $product->price = 30;
        $product->product_type_id = 8;
        $product->save();
    }
}
