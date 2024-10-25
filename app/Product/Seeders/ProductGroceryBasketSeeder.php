<?php

namespace App\Product\Seeders;

use App\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductGroceryBasketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->name = 'Papa Lays';
        $product->price = 3;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Doritos';
        $product->price = 3;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Sáltica';
        $product->price = 3;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Nick';
        $product->price = 3;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Trident';
        $product->price = 3;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Sublime';
        $product->price = 3;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Vizzio';
        $product->price = 3;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Triángulo';
        $product->price = 3;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Chupetin';
        $product->price = 2;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Halls';
        $product->price = 3;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Chanpagne';
        $product->price = 100;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Cigarros';
        $product->price = 40;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Encendedor';
        $product->price = 2;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Shampoo';
        $product->price = 2;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Preservativos';
        $product->price = 9;
        $product->product_type_id = 7;
        $product->save();

        $product = new Product();
        $product->name = 'Prestobarba';
        $product->price = 3;
        $product->product_type_id = 7;
        $product->save();
    }
}
