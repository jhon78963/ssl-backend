<?php

namespace Database\Seeders;

use App\ProductType\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productType = new ProductType();
        $productType->description = "Piqueos";
        $productType->category_id = 1;
        $productType->save();

        $productType = new ProductType();
        $productType->description = "Platos a la carta";
        $productType->category_id = 1;
        $productType->save();

        $productType = new ProductType();
        $productType->description = "Jugos & Batidos";
        $productType->category_id = 1;
        $productType->save();

        $productType = new ProductType();
        $productType->description = "Postres";
        $productType->category_id = 1;
        $productType->save();

        $productType = new ProductType();
        $productType->description = "Cocteles";
        $productType->category_id = 1;
        $productType->save();

        $productType = new ProductType();
        $productType->description = "Productos frigo bar";
        $productType->category_id = 2;
        $productType->save();

        $productType = new ProductType();
        $productType->description = "Canasta";
        $productType->category_id = 2;
        $productType->save();

        $productType = new ProductType();
        $productType->description = "Barra";
        $productType->category_id = 2;
        $productType->save();
    }
}
