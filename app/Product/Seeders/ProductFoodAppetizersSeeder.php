<?php

namespace App\Product\Seeders;

use App\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductFoodAppetizersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->name = 'Alitas (12 unidades)';
        $product->price = 35;
        $product->product_type_id = 1;
        $product->save();

        $product = new Product();
        $product->name = 'Alitas (6 unidades)';
        $product->price = 20;
        $product->product_type_id = 1;
        $product->save();

        $product = new Product();
        $product->name = 'Tequeños (12 unidades)';
        $product->price = 20;
        $product->product_type_id = 1;
        $product->save();

        $product = new Product();
        $product->name = 'Tequeños (6 unidades)';
        $product->price = 10;
        $product->product_type_id = 1;
        $product->save();
    }
}
