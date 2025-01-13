<?php

namespace App\Product\Seeders;

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
        $product->name = 'Mixto (1/2 litro)';
        $product->price = 5;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Mixto (1 litro)';
        $product->price = 10;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Jugo de Papaya (1/2 litro)';
        $product->price = 7;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Jugo de Papaya (1 litro)';
        $product->price = 14;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = "Batido de Fresa (1/2 litro)";
        $product->price = 10;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = "Batido de Fresa (1 litro)";
        $product->price = 20;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Batido de Melón (1/2 litro)';
        $product->price = 10;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Batido de Melón (1 litro)';
        $product->price = 20;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Jugo de Naranja (1/2 litro)';
        $product->price = 10;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Jugo de Naranja (1 litro)';
        $product->price = 20;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Jugo de Piña (1/2 litro)';
        $product->price = 10;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Jugo de Piña (1 litro)';
        $product->price = 20;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Jugo de Sandía (1/2 litro)';
        $product->price = 10;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Jugo de Sandía (1 litro)';
        $product->price = 20;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Limonada (1/2 litro)';
        $product->price = 8;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Limonada (1 litro)';
        $product->price = 15;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Limonada Pink (1/2 litro)';
        $product->price = 10;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Limonada Pink (1 litro)';
        $product->price = 18;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Limonada Eléctrica (1/2 litro)';
        $product->price = 10;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Limonada Eléctrica (1 litro)';
        $product->price = 18;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Limonada Batida (1/2 litro)';
        $product->price = 10;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Limonada Batida (1 litro)';
        $product->price = 18;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Limonada Frozen (1/2 litro)';
        $product->price = 10;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Limonada Frozen (1 litro)';
        $product->price = 18;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Sangría (1/2 litro)';
        $product->price = 18;
        $product->product_type_id = 3;
        $product->save();

        $product = new Product();
        $product->name = 'Sangría (1 litro)';
        $product->price = 35;
        $product->product_type_id = 3;
        $product->save();
    }
}
