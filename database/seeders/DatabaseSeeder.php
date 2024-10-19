<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompanySeed::class,
            RoleSeed::class,
            UserSeed::class,
            RoomTypeSeeder::class,
            RateHourSeeder::class,
            RateDaySeeder::class,
            RateSeeder::class,
            GenderSeeder::class,
            LockerSeeder::class,
            CategorySeeder::class,
            ProductTypeSeeder::class,
            UnitSeeder::class,
            ProductFoodAppetizersSeeder::class,
            ProductFoodMainDishesSeeder::class,
            ProductFoodJuicesAndSmoothiesSeeder::class,
            ProductFoodDessertsSeeder::class,
            ProductFoodCocktailsSeeder::class,
            ProductGroceryFrigoBarSeeder::class,
            ProductGroceryBasketSeeder::class,
            ProductGroceryBarSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}
