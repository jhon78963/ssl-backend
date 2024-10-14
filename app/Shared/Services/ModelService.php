<?php

namespace App\Shared\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;

class ModelService
{
    public function attach(Model $model, string $relation, int $id): void
    {
        $model->$relation()->attach($id);
    }

    function create(Model $model, array $data): Model
    {
        $this->setCreationAuditFields($model);
        $model->fill($data);
        $model->save();
        return $model;
    }

    public function detach(Model $model, string $relation, int $id): void
    {
        $model->$relation()->detach($id);
    }

    public function delete(Model $model): void
    {
        $this->setDeleteAuditFields($model);
        $model->save();
    }

    function update(Model $model, array $data): Model
    {
        $this->setUpdateAuditFields($model);
        $model->fill($data);
        $model->save();
        return $model;
    }

    public function validate($model, string $modelName): mixed
    {
        if ($model->is_deleted == true) {
            throw new ModelNotFoundException("$modelName does not exists.");
        }

        return $model;
    }

    private static function setCreationAuditFields(Model $model): void
    {
        $model->creator_user_id = Auth::id();
    }

    private static function setDeleteAuditFields(Model $model): void
    {
        $model->is_deleted = true;
        $model->deleter_user_id = Auth::id();
        $model->deletion_time = now()->format('Y-m-d H:i:s');
    }

    private static function setUpdateAuditFields(Model $model): void
    {
        $model->last_modifier_user_id = Auth::id();
        $model->last_modification_time = now()->format('Y-m-d H:i:s');
    }
}
