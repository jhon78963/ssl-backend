<?php

namespace Database\Seeders;

use App\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductFoodJuicesAndSmoothiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->name = 'Mixto';
        $product->price = 5;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Papaya';
        $product->price = 5;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = "Batido de Fresa";
        $product->price = 10;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Batido de Melón';
        $product->price = 10;
        $product->product_type_id = 3;
        $product->save();
    }
}
