<?php

namespace App\Shared\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelRelationService
{
    public function attach(Model $model, string $relation, int $id): ?array
    {
        DB::beginTransaction();
        try {
            $model->$relation()->attach($id);
            DB::commit();
            return null;
        } catch (\Exception $e) {
            DB::rollback();
            return ['error' => $e->getMessage()];
        }
    }

    public function detach(Model $model, string $relation, int $id): ?array
    {
        DB::beginTransaction();
        try {
            $model->$relation()->detach($id);
            DB::commit();
            return null;
        } catch (\Exception $e) {
            DB::rollback();
            return ['error' => $e->getMessage()];
        }
    }
}
