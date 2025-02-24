<?php

namespace App\Category\Seeders;

use App\Category\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = new Category();
        $category->name = "Comida";
        $category->save();

        $category = new Category();
        $category->name = "Abarrotes";
        $category->save();

        $category = new Category();
        $category->name = "Lockers";
        $category->save();
    }
}
