<?php

namespace App\Reservation\Services;

use App\Locker\Models\Locker;
use App\Product\Models\Product;
use App\Reservation\Models\Reservation;
use App\Room\Models\Room;
use App\Service\Models\Service;
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
        $lockers = Locker::with('reservations')->where('is_deleted', '=', false)
            ->select('id', DB::raw("CONCAT('L', number) as number"), 'status', 'price')
            ->get()
            ->map(function (Locker $locker): Locker {
                $locker->type = 'locker';
                $locker->reservation_id = $locker->reservations->first()?->id;
                return $locker;
            });

        $rooms = Room::where('is_deleted', '=', false)
            ->select('id', DB::raw("CONCAT('R', number) as number"), 'status')
            ->addSelect([
                'price' => function (Builder $query): void {
                    $query->select('price_per_capacity')
                        ->from('room_types')
                        ->whereColumn('room_types.id', 'rooms.room_type_id');
                },
            ])
            ->get()
            ->map(function (Room $room): Room {
                $room->type = 'room';
                $room->reservation_id = $room->reservations->first()?->id;
                return $room;
            });

        return $lockers
                ->concat($rooms)
                ->sortBy(function (Locker|Room $item): int {
                    preg_match('/\d+/', $item->number, $matches);
                    return (int) $matches[0];
                })->values();
    }

    public function products(?string $nameFilter = null): Collection
    {
        $products = Product::where('is_deleted', '=', false)
            ->when($nameFilter, function ($query) use ($nameFilter) {
                $query->whereRaw('LOWER(name) LIKE ?', [strtolower("{$nameFilter}%")]);
            })
            ->select('id', 'name', 'price')
            ->get()
            ->map(function (Product $product): Product {
                $product->type = 'product';
                return $product;
            });

        $services = Service::where('is_deleted', '=', false)
            ->when($nameFilter, function ($query) use ($nameFilter) {
                $query->whereRaw('LOWER(name) LIKE ?', [strtolower("{$nameFilter}%")]);
            })
            ->select('id', 'name', 'price')
            ->get()
            ->map(function (Service $service): Service {
                $service->type = 'service';
                return $service;
            });

        return $products->concat($services);
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
