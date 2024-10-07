<?php

namespace App\Room\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoomRelationService
{
    public function attach(Model $room, string $relation, int $id): ?array
    {
        DB::beginTransaction();
        try {
            $room->$relation()->attach($id);
            DB::commit();
            return null;
        } catch (\Exception $e) {
            DB::rollback();
            return ['error' => $e->getMessage()];
        }
    }

    public function detach(Model $room, string $relation, int $id): ?array
    {
        DB::beginTransaction();
        try {
            $room->$relation()->detach($id);
            DB::commit();
            return null;
        } catch (\Exception $e) {
            DB::rollback();
            return ['error' => $e->getMessage()];
        }
    }
}
