<?php

namespace Database\Seeders;

use App\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductFoodMainDishesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->name = 'Chicharrón de Pollo';
        $product->price = 20;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Chicharron de Pescado';
        $product->price = 20;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Lomo Saltado';
        $product->price = 20;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Saltado de Pollo';
        $product->price = 18;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Ceviche Plato';
        $product->price = 20;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Ceviche Copa';
        $product->price = 16;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Duo Marino';
        $product->price = 30;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Tallarín Saltado';
        $product->price = 20;
        $product->product_type_id = 2;
        $product->save();
    }
}
