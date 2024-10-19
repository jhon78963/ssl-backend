<?php

namespace Database\Seeders;

use App\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductGroceryBarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->name = 'Ron BARCELO';
        $product->price = 100;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Vino VICTORIO';
        $product->price = 79;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Taberno';
        $product->price = 50;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Pisco 4 Gallos';
        $product->price = 100;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Passport';
        $product->price = 50;
        $product->product_type_id = 7;
        $product->save();
    }
}
