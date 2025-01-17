<?php

namespace Database\Seeders;

use App\Cash\Seeders\CashSeeder;
use App\Cash\Seeders\CashTypeSeeder;
use App\Category\Seeders\CategorySeeder;
use App\Company\Seeders\CompanySeed;
use App\Gender\Seeders\GenderSeeder;
use App\Inventory\Seeders\InventorySeeder;
use App\Locker\Seeders\LockerSeeder;
use App\PaymentType\Seeders\PaymentTypeSeeder;
use App\Product\Seeders\ProductFoodAppetizersSeeder;
use App\Product\Seeders\ProductFoodCocktailsSeeder;
use App\Product\Seeders\ProductFoodDessertsSeeder;
use App\Product\Seeders\ProductFoodJuicesAndSmoothiesSeeder;
use App\Product\Seeders\ProductFoodMainDishesSeeder;
use App\Product\Seeders\ProductGroceryBasketSeeder;
use App\Product\Seeders\ProductGroceryFrigoBarSeeder;
use App\ProductType\Seeders\ProductTypeSeeder;
use App\Rate\Seeders\RateDaySeeder;
use App\Rate\Seeders\RateHourSeeder;
use App\ReservationType\Seeders\ReservationTypeSeeder;
use App\Role\Seeders\RoleSeed;
use App\Room\Seeders\RoomSeeder;
use App\RoomType\Seeders\RoomTypeSeeder;
use App\Schedule\Seeders\ScheduleSeeder;
use App\Service\Seeders\ServiceSeeder;
use App\Unit\Seeders\UnitSeeder;
use App\User\Seeders\UserSeed;
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
            RateDaySeeder::class,
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
            ServiceSeeder::class,
            ReservationTypeSeeder::class,
            RoomSeeder::class,
            PaymentTypeSeeder::class,
            CashSeeder::class,
            CashTypeSeeder::class,
            ScheduleSeeder::class,
            InventorySeeder::class,
        ]);
    }
}
