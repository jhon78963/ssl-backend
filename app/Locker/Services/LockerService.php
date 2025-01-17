<?php

namespace App\Locker\Services;

use App\Locker\Models\Locker;
use App\Shared\Services\ModelService;
use Illuminate\Database\Eloquent\Collection;

class LockerService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newLocker): void
    {
        $this->modelService->create(new Locker(), $newLocker);
    }

    public function delete(Locker $locker): void
    {
        $this->modelService->delete($locker);
    }

    public function get(int $genderId): Collection
    {
        $lockers = Locker::where('is_deleted', '=', false)
            ->where('gender_id', '=', $genderId)
            ->get();
        return $lockers;
    }

    public function getLockerAvailable(): Collection
    {
        return Locker::where('is_deleted', '=', false)
            ->where('status', '=', 'AVAILABLE')
            ->orderBy('id')
            ->get();
    }

    public function update(Locker $locker, array $editLocker): Locker
    {
        return $this->modelService->update($locker, $editLocker);
    }

    public function updatePrice(float $newPrice): mixed
    {
        return $this->modelService->updatePrice(
            'Locker',
            'Locker',
            'price',
            $newPrice
        );
    }

    public function validate(Locker $locker, string $modelName): Locker
    {
        return $this->modelService->validate($locker, $modelName);
    }
}
