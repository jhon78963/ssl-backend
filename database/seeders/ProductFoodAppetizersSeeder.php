<?php

namespace Database\Seeders;

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
        $product->name = 'Alitas';
        $product->price = null;
        $product->product_type_id = 1;
        $product->save();
        $product->units()->attach(1, ['price' => 20]);
        $product->units()->attach(2, ['price' => 35]);

        $product = new Product();
        $product->name = 'Tequeños';
        $product->price = null;
        $product->product_type_id = 1;
        $product->save();
        $product->units()->attach(1, ['price' => 10]);
        $product->units()->attach(2, ['price' => 20]);
    }
}
