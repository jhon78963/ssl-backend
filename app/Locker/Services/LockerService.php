<?php

namespace App\Locker\Services;

use App\Gender\Models\Gender;
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
        $lockers = Locker::where('gender_id', '=', $genderId)->get();
        return $lockers;
    }

    public function update(Locker $locker, array $editLocker): Locker
    {
        return $this->modelService->update($locker, $editLocker);
    }

    public function validate(Locker $locker, string $modelName): Locker
    {
        return $this->modelService->validate($locker, $modelName);
    }
}
