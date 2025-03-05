<?php

namespace App\Product\Seeders;

use App\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductFoodCocktailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product();
        $product->name = 'Chilcanao';
        $product->price = 13;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Mojito';
        $product->price = 13;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Machu Pichu';
        $product->price = 13;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Laguna Azul';
        $product->price = 13;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Cuba Libre';
        $product->price = 13;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Destornillador';
        $product->price = 13;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Algarrobina';
        $product->price = 15;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Piña Colada';
        $product->price = 15;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Daiquiri';
        $product->price = 15;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Pantera Rosa';
        $product->price = 15;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Orgasmo';
        $product->price = 20;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Sex on the laiss';
        $product->price = 15;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Pisco sour';
        $product->price = 15;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Sacsayhuaman';
        $product->price = 15;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Martini';
        $product->price = 15;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Apple Martini';
        $product->price = 15;
        $product->product_type_id = 5;
        $product->save();

        $product = new Product();
        $product->name = 'Amor en llamas';
        $product->price = 15;
        $product->product_type_id = 5;
        $product->save();
    }
}
