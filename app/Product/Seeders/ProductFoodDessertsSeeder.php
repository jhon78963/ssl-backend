<?php

namespace App\Product\Seeders;

use App\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductFoodDessertsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->name = 'Ensalada de Frutas';
        $product->price = 10;
        $product->product_type_id = 4;
        $product->save();

        $product = new Product();
        $product->name = 'Ensalada de Frutas';
        $product->price = 15;
        $product->product_type_id = 4;
        $product->save();
    }
}
