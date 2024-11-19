<?php

namespace App\Reservation\Services;

use App\Locker\Models\Locker;
use App\Reservation\Models\Reservation;
use App\Room\Models\Room;
use App\Shared\Services\ModelService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use DB;

class ReservationService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newReservation): Reservation
    {
        return $this->modelService->create(new Reservation(), $newReservation);
    }

    public function facilities(): Collection
    {
        $lockers = Locker::where('is_deleted', '=', false)
            ->select('id', DB::raw("CONCAT('L', number) as number"), 'status', 'price')
            ->get();

        $rooms = Room::where('is_deleted', '=', false)
            ->select('id', DB::raw("CONCAT('R', number) as number"), 'status')
            ->addSelect([
                'price' => function (Builder $query): void {
                    $query->select('price_per_capacity')
                        ->from('room_types')
                        ->whereColumn('room_types.id', 'rooms.room_type_id');
                },
            ])
            ->get();

        return $lockers->concat($rooms);
    }

    public function update(Reservation $reservation, array $editReservation): void
    {
        $this->modelService->update($reservation, $editReservation);
    }

    public function validate(Reservation $reservation, string $modelName): Reservation
    {
        return $this->modelService->validate($reservation, $modelName);
    }
}
