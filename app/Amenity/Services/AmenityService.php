<?php

namespace App\Amenity\Services;

use App\Amenity\Models\Amenity;
use Auth;

class AmenityService
{
    public function createAmenity(array $newAmenity): void
    {
        $amenity = new Amenity();
        $amenity->description = $newAmenity['description'];
        $amenity->creator_user_id = Auth::id();
        $amenity->save();
    }

    public function updateAmenity(Amenity $amenity, array $editAmenity): void
    {
        $amenity->description = $editAmenity['description'] ?? $amenity->description;
        $amenity->last_modification_time = now()->format('Y-m-d H:i:s');
        $amenity->last_modifier_user_id = Auth::id();
        $amenity->save();
    }
}
