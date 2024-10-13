<?php

namespace App\Shared\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Auth;

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

    function create(Model $model, array $fields, array $data)
    {
        $this->assignFields($model, $fields, $data);
        $this->setCreationAuditFields($model);
        $model->save();
        return $model;
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

    function update(Model $model, array $fields, array $data): Model
    {
        $this->assignFields($model, $fields, $data);
        $this->setUpdateAuditFields($model);
        $model->save();
        return $model;
    }

    private function assignFields(Model $model, array $fields, array $data): void
    {
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $model->{$field} = $data[$field];
            }
        }
    }

    public function convertCamelToSnake(array $data)
    {

        return Arr::mapWithKeys($data, function ($value, $key) {
            return [Str::snake($key) => $value];
        });
    }

    private static function setCreationAuditFields(Model $model): void
    {
        $model->creator_user_id = Auth::id();
    }

    private static function setUpdateAuditFields(Model $model): void
    {
        $model->last_modifier_user_id = Auth::id();
        $model->last_modification_time = now()->format('Y-m-d H:i:s');
    }
}
