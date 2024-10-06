<?php

namespace App\Company\Services;

use App\Company\Models\Company;
use Auth;

class CompanyService
{
    public function updateCompany(Company $company, array $editCompany): void
    {
        $company->business_name = $editCompany['businessName'] ?? $company->business_name;
        $company->representative_legal = $editCompany['representative_legal'] ?? $company->representative_legal;
        $company->address = $editCompany['address'] ?? $company->address;
        $company->phone_number = $editCompany['phone_number'] ?? $company->phone_number;
        $company->email = $editCompany['email'] ?? $company->email;
        $company->google_maps_location = $editCompany['google_maps_location'] ?? $company->google_maps_location;
        $company->last_modification_time = now()->format('Y-m-d H:i:s');
        $company->last_modifier_user_id = Auth::id();
        $company->save();
    }
}
