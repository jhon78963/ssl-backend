<?php

namespace App\Room\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoomRelationService
{
    public function attach(Model $room, string $relation, int $id): array
    {
        DB::beginTransaction();
        try {
            $room->$relation()->attach($id);
            DB::commit();
            return ['message' => ucfirst($relation) . ' added to the room.', 'status' => 201];
        } catch (\Exception $e) {
            DB::rollback();
            return ['error' => $e->getMessage(), 'status' => 500];
        }
    }

    public function detach(Model $room, string $relation, int $id): array
    {
        DB::beginTransaction();
        try {
            $room->$relation()->detach($id);
            DB::commit();
            return ['message' => ucfirst($relation) . ' removed from the room.', 'status' => 201];
        } catch (\Exception $e) {
            DB::rollback();
            return ['error' => $e->getMessage(), 'status' => 500];
        }
    }
}
