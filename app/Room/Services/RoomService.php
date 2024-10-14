<?php

namespace App\Room\Services;

use App\Room\Models\Room;
use App\Shared\Services\ModelService;

class RoomService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newRoom): void
    {
        $this->modelService->create(new Room(), $newRoom);
    }

    public function delete(Room $room): void
    {
        $this->modelService->delete($room);
    }

    public function update(Room $room, array $editRoom): void
    {
        $this->modelService->update($room, $editRoom);
    }

    public function validate(Room $room, string $modelName): mixed
    {
        return $this->modelService->validate($room, $modelName);
    }
}
