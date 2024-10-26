<?php

namespace App\ReservationType\Services;

use App\ReservationType\Models\ReservationType;
use App\Shared\Services\ModelService;

class ReservationTypeService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newReservationType): void
    {
        $this->modelService->create(new ReservationType(), $newReservationType);
    }

    public function delete(ReservationType $reservationType): void
    {
        $this->modelService->delete($reservationType);
    }

    public function update(ReservationType $reservationType, array $editReservationType): void
    {
        $this->modelService->update($reservationType, $editReservationType);
    }

    public function validate(ReservationType $reservationType, string $modelName): ReservationType
    {
        return $this->modelService->validate($reservationType, $modelName);
    }
}
