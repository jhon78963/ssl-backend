<?php

namespace App\Shared\Services;

use App\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Auth;

class ModelService
{
    public function attach(
        Model $model,
        string $relation,
        int $id,
        ?float $price = null,
        ?int $quantity = null,
        ?bool $isPaid = null,
        ?float $payment = null,
        ?float $cashPayment = null,
        ?float $cardPayment = null,
    ): void {
        $attributes = [];
        if ($price !== null) {
            $attributes['price'] = $price;
        }
        if ($quantity !== null) {
            $attributes['quantity'] = $quantity;
        }
        if ($isPaid !== null) {
            $attributes['is_paid'] = $isPaid;
        }
        if ($payment !== null) {
            $attributes['payment'] = $payment;
        }
        if ($cashPayment !== null) {
            $attributes['cash_payment'] = $cashPayment;
        }
        if ($cardPayment !== null) {
            $attributes['card_payment'] = $cardPayment;
        }
        $model->$relation()->attach($id, $attributes);
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

    public function get(Model $model, string $column, string|int $data): ?Model
    {
        return $model->where($column, '=', $data)->first();
    }

    public function mergeModels(array $models): Collection
    {
        $collection = collect();
        foreach ($models as $model) {
            if ($model instanceof Model) {
                $records = $model->where('is_deleted', '=', false)->get();
                $collection = $collection->merge($records);
            }
        }
        return $collection;
    }

    function update(Model $model, array $data): Model
    {
        $this->setUpdateAuditFields($model);
        $model->fill($data);
        $model->save();
        return $model;
    }

    public function validate(User | Model $model, string $modelName): User | Model
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
