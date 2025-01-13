<?php

namespace App\Product\Seeders;

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
        $product->name = 'Chicharrón de Pollo (plato)';
        $product->price = 20;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Chicharrón de Pollo (fuente)';
        $product->price = 38;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Chicharron de Pescado (plato)';
        $product->price = 16;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Chicharron de Pescado (fuente)';
        $product->price = 30;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Lomo Saltado (plato)';
        $product->price = 20;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Lomo Saltado (fuente)';
        $product->price = 38;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Saltado de Pollo (plato)';
        $product->price = 18;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Saltado de Pollo (fuente)';
        $product->price = 35;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Arroz chaufa oriental';
        $product->price = 20;
        $product->product_type_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Mostrito';
        $product->price = 25;
        $product->product_type_id = 2;
        $product->save();

        // $product = new Product();
        // $product->name = 'Ceviche Plato';
        // $product->price = 20;
        // $product->product_type_id = 2;
        // $product->save();

        // $product = new Product();
        // $product->name = 'Ceviche Copa';
        // $product->price = 16;
        // $product->product_type_id = 2;
        // $product->save();

        // $product = new Product();
        // $product->name = 'Duo Marino';
        // $product->price = 30;
        // $product->product_type_id = 2;
        // $product->save();

        // $product = new Product();
        // $product->name = 'Tallarín Saltado';
        // $product->price = 20;
        // $product->product_type_id = 2;
        // $product->save();
    }
}
