<?php

namespace App\BookType\Seeders;

use App\BookType\Models\BookType;
use Illuminate\Database\Seeder;

class BookTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookType = new BookType();
        $bookType->description = 'General';
        $bookType->save();

        $bookType = new BookType();
        $bookType->description = 'Privado';
        $bookType->save();
    }
}
