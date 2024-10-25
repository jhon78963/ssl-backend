<?php

namespace App\Product\Seeders;

use App\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductGroceryFrigoBarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->name = 'Cerveza Pilsen Trujillo';
        $product->price = 10;
        $product->product_type_id = 6;
        $product->save();

        $product = new Product();
        $product->name = 'Cerveza Pilsen Callao';
        $product->price = 10;
        $product->product_type_id = 6;
        $product->save();

        $product = new Product();
        $product->name = 'Cerveza Corona';
        $product->price = 10;
        $product->product_type_id = 6;
        $product->save();

        $product = new Product();
        $product->name = 'Cerveza en Lata';
        $product->price = 7;
        $product->product_type_id = 6;
        $product->save();

        $product = new Product();
        $product->name = 'Agua';
        $product->price = 3;
        $product->product_type_id = 6;
        $product->save();

        $product = new Product();
        $product->name = 'Gaseosa Coca Cola';
        $product->price = 4;
        $product->product_type_id = 6;
        $product->save();

        $product = new Product();
        $product->name = 'Gaseosa Inka Kola';
        $product->price = 4;
        $product->product_type_id = 6;
        $product->save();

        $product = new Product();
        $product->name = 'Sporade';
        $product->price = 4;
        $product->product_type_id = 6;
        $product->save();

        $product = new Product();
        $product->name = 'Red Bull';
        $product->price = 10;
        $product->product_type_id = 6;
        $product->save();

        $product = new Product();
        $product->name = 'Wild';
        $product->price = 10;
        $product->product_type_id = 6;
        $product->save();

        $product = new Product();
        $product->name = 'Mikes';
        $product->price = 10;
        $product->product_type_id = 6;
        $product->save();

        $product = new Product();
        $product->name = 'Vino Santiago Queirolo';
        $product->price = 40;
        $product->product_type_id = 6;
        $product->save();

        $product = new Product();
        $product->name = 'Volt';
        $product->price = 4;
        $product->product_type_id = 6;
        $product->save();
    }
}
