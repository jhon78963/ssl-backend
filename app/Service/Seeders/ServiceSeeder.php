<?php

namespace App\Service\Seeders;

use App\Service\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service = new Service();
        $service->name = 'Masajes Relajantes';
        $service->price = null;
        $service->save();
        $service->times()->attach(3, ['price' => 30]);
        $service->times()->attach(4, ['price' => 50]);

        $service = new Service();
        $service->name = 'Masajes Descontracturantes';
        $service->price = null;
        $service->save();
        $service->times()->attach(3, ['price' => 30]);
        $service->times()->attach(4, ['price' => 50]);

        $service = new Service();
        $service->name = 'Terapia';
        $service->price = 50;
        $service->save();

        $service = new Service();
        $service->name = 'Maderoterapia';
        $service->price = 30;
        $service->save();

        $service = new Service();
        $service->name = 'Reflexología';
        $service->price = 30;
        $service->save();

        $service = new Service();
        $service->name = 'Limpieza facial profunda';
        $service->price = 80;
        $service->save();

        $service = new Service();
        $service->name = 'Plasma rico en plaquetas';
        $service->price = 90;
        $service->save();

        $service = new Service();
        $service->name = 'Peptonas';
        $service->price = 90;
        $service->save();

        $service = new Service();
        $service->name = 'Carboxiterapia + Ampollas reductoras';
        $service->price = 40;
        $service->save();
    }
}
