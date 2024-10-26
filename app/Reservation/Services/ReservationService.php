<?php

namespace App\Reservation\Services;

use App\Reservation\Models\Reservation;
use App\Shared\Services\ModelService;

class ReservationService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newReservation): void
    {
        $this->modelService->create(new Reservation(), $newReservation);
    }

    public function validate(Reservation $reservation, string $modelName): Reservation
    {
        return $this->modelService->validate($reservation, $modelName);
    }
}
