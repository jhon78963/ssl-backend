<?php

namespace App\Company\Seeders;

use App\Company\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = new Company();
        $company->business_name = "Suites&Sauna Laiss";
        $company->representative_legal = "Johan Layza";
        $company->address = "av. pesqueda N°209, (Al costado de Plaza Vea chacarero)";
        $company->phone_number = "+51 912 123 123";
        $company->email = "suiteslaiss@gmail.com";
        $company->google_maps_location = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4549223.588388079!2d-79.63136936823435!3d-8.652163121937386!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91ad1616fcd1501f%3A0xe1ddaee0efc7f0c3!2sAv.%20Pesqueda%20209%2C%20V%C3%ADctor%20Larco%20Herrera%2013006!5e1!3m2!1ses-419!2spe!4v1724291561514!5m2!1ses-419!2spe";
        $company->save();
    }
}
