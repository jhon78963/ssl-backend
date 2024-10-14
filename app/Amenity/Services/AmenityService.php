<?php

namespace App\Amenity\Services;

use App\Amenity\Models\Amenity;
use App\Shared\Services\ModelService;

class AmenityService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newAmenity): void
    {
        $this->modelService->create(
            new Amenity(),
            $newAmenity,
        );
    }

    public function delete(Amenity $amenity): void
    {
        $this->modelService->delete($amenity);
    }

    public function update(Amenity $amenity, array $editAmenity): void
    {
       $this->modelService->update(
            $amenity,
            $editAmenity,
        );
    }

    public function validate(Amenity $amenity, string $modelName): Amenity
    {
        return $this->modelService->validate($amenity, $modelName);
    }
}
