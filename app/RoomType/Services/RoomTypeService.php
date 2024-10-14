<?php

namespace App\RoomType\Services;

use App\RoomType\Models\RoomType;
use App\Shared\Services\ModelService;

class RoomTypeService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newRoomType): void
    {
        $this->modelService->create(new RoomType(), $newRoomType);
    }

    public function delete(RoomType $roomType): void
    {
        $this->modelService->delete($roomType);
    }

    public function update(RoomType $roomType, array $editRoomType): void
    {
        $this->modelService->update($roomType, $editRoomType);
    }

    public function validate(RoomType $roomType, string $modelName): mixed
    {
        return $this->modelService->validate($roomType, $modelName);
    }
}
