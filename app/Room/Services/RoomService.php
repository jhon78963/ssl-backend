<?php

namespace App\Room\Services;

use App\Room\Models\Room;
use App\Shared\Services\ModelService;
use Illuminate\Database\Eloquent\Collection;

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

    public function getRoomAvailable(): Collection
    {
        return Room::where('is_deleted', '=', false)
            ->where('status', '=', 'AVAILABLE')
            ->orderBy('id')
            ->get();
    }

    public function update(Room $room, array $editRoom): void
    {
        $this->modelService->update($room, $editRoom);
    }

    public function validate(Room $room, string $modelName): Room
    {
        return $this->modelService->validate($room, $modelName);
    }
}
