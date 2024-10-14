<?php
namespace App\Locker\Services;

use App\Locker\Models\Locker;
use App\Shared\Services\ModelService;

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

    public function update(Locker $locker, array $editLocker): void
    {
        $this->modelService->update($locker, $editLocker);
    }

    public function validate(Locker $locker, string $modelName): mixed
    {
        return $this->modelService->validate($locker, $modelName);
    }
}
