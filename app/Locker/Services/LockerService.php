<?php
namespace App\Locker\Services;
use App\Locker\Models\Locker;
use App\Shared\Services\ModelRelationService;
use Auth;

class LockerService
{
    protected ModelRelationService $modelRelationService;

    public function __construct(ModelRelationService $modelRelationService)
    {
        $this->modelRelationService = $modelRelationService;
    }

    public function createLocker(array $newLocker): void
    {
        $newLocker = $this->modelRelationService->convertCamelToSnake($newLocker);
        $this->modelRelationService->create(
            new Locker(),
            ['number', 'gender_id'],
            $newLocker,
        );
    }

    public function updateLocker(Locker $locker, array $editLocker)
    {
        $editLocker = $this->modelRelationService->convertCamelToSnake($editLocker);
        $this->modelRelationService->update(
            $locker,
            ['number', 'gender_id'],
            $editLocker,
        );
    }
}
