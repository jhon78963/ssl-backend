<?php

namespace App\Reservation\Services;

use App\Booking\Services\BookingService;
use App\Cash\Models\CashOperation;
use App\Cash\Services\CashService;
use App\Locker\Models\Locker;
use App\Product\Models\Product;
use App\Reservation\Models\Reservation;
use App\ReservationType\Models\ReservationType;
use App\Room\Models\Room;
use App\Schedule\Services\ScheduleService;
use App\Service\Models\Service;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Services\ModelService;
use App\Shared\Services\SharedService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use DB;

class ReservationService
{
    private int $limit = 10;
    private int $page = 1;
    private string $schedule = '';
    private string $reservationType = '';
    private string $startDate = '';
    private string $endDate = '';
    protected BookingService $bookingService;
    protected CashService $cashService;
    protected ModelService $modelService;
    protected ScheduleService $scheduleService;
    protected SharedService $sharedService;

    public function __construct(
        BookingService $bookingService,
        CashService $cashService,
        ModelService $modelService,
        ScheduleService $scheduleService,
        SharedService $sharedService
    ) {
        $this->bookingService = $bookingService;
        $this->cashService = $cashService;
        $this->modelService = $modelService;
        $this->scheduleService = $scheduleService;
        $this->sharedService = $sharedService;
    }

    public function create(array $newReservation): Reservation
    {
        return $this->modelService->create(new Reservation(), $newReservation);
    }

    public function createCash(
        Reservation $reservation,
        float $totalPaid,
        float $totalCashPaid,
        float $totalCardPaid,
        string $description,
        bool $isRemove
    ): void {
        $cash = $this->cashService->currentCash();
        $this->modelService->create(
            new CashOperation(),
            [
            'cash_id' => $cash->id,
            'reservation_id' => $reservation->id,
            'cash_type_id' => 2,
            'schedule_id' => $this->scheduleService->get(),
            'date' => now(),
            'description' => $description,
            'amount' => $isRemove ? -$totalPaid : $totalPaid,
            'cash_amount' => $isRemove ? -$totalCashPaid : $totalCashPaid,
            'card_amount' => $isRemove ? -$totalCardPaid: $totalCardPaid,
        ]);
    }

    public function facilities(): Collection
    {
        $lockers = Locker::with(
            [
                'reservations' => function ($query) {
                    $query->where('status', '!=', 'COMPLETED');
                }
            ])
            ->where('is_deleted', '=', false)
            ->select('id', DB::raw("CONCAT('L', number) as number"), 'status', 'price')
            ->get()
            ->map(function (Locker $locker): Locker {
                $locker->type = 'locker';
                $locker->reservation_id = $locker->reservations->first()?->id;
                return $locker;
            });

            $rooms = Room::with([
                'reservations' => function ($query) {
                    $query->where('status', '!=', 'COMPLETED');
                },
                'bookings' => function($query) {
                    $query->where('status', '!=', 'COMPLETED');
                },
                'roomType'
            ])
                ->where('is_deleted', '=', false)
                ->select('id', DB::raw("CONCAT('R', number) as number"), 'status', 'room_type_id')
                ->addSelect([
                    'price' => function (Builder $query): void {
                        $query->select('price_per_capacity')
                            ->from('room_types')
                            ->whereColumn('room_types.id', 'rooms.room_type_id')
                            ->limit(1);
                    },
                    'price_per_additional_person' => function (Builder $query): void {
                        $query->select('price_per_additional_person')
                            ->from('room_types')
                            ->whereColumn('room_types.id', 'rooms.room_type_id')
                            ->limit(1);
                    },
                    'price_per_extra_hour' => function (Builder $query): void {
                        $query->select('price_per_extra_hour')
                            ->from('room_types')
                            ->whereColumn('room_types.id', 'rooms.room_type_id')
                            ->limit(1);
                    },
                ])
                ->get()
                ->map(function (Room $room): Room {
                    $room->type = 'room';
                    $room->status = $this->getRoomStatus($room);
                    $room->reservation_id = $room->reservations->first()?->id;
                    $room->booking_id = $room->bookings->first()?->id;
                    return $room;
                });

        return $lockers
                ->concat($rooms)
                ->sortBy(function (Locker|Room $item): int {
                    preg_match('/\d+/', $item->number, $matches);
                    return (int) $matches[0];
                })->values();
    }

    public function validateFacilities(): float|int
    {
        $lockers = Locker::where('is_deleted', '=', false)
            ->where('status', '=', 'IN_USE')
            ->count();
        $rooms = Room::where('is_deleted', '=', false)
            ->where('status', '=', 'IN_USE')
            ->count();

        return $lockers + $rooms;
    }

    public function products(?string $nameFilter = null): Collection
    {
        $products = Product::where('is_deleted', '=', false)
            ->when($nameFilter, function ($query) use ($nameFilter) {
                $query->whereRaw('LOWER(name) LIKE ?', [strtolower("%{$nameFilter}%")]);
            })
            ->select('id', 'name', 'price', 'product_type_id')
            ->get()
            ->map(function (Product $product): Product {
                $product->type = 'product';
                return $product;
            });

        $services = Service::where('is_deleted', '=', false)
            ->when($nameFilter, function ($query) use ($nameFilter) {
                $query->whereRaw('LOWER(name) LIKE ?', [strtolower("%{$nameFilter}%")]);
            })
            ->select('id', 'name', 'price')
            ->get()
            ->map(function (Service $service): Service {
                $service->type = 'service';
                return $service;
            });

        return $products->concat($services);
    }

    private function checkSchedule(Room $room, string $startDate): array
    {
        return $this->bookingService->checkSchedule(
            $room->id,
            $startDate,
            $room->roomType->rental_hours
        );
    }

    private function getRoomStatus(Room $room): mixed
    {
        $schedule = $this->checkSchedule($room, now());
        if ($schedule['conflict']) {
            return 'BOOKED';
        }
        return $room->status;
    }

    private function prependReservationType(): ReservationType {
        $reservationType = new ReservationType();
        $reservationType->id = 0;
        $reservationType->description = 'Todos';
        return $reservationType;
    }

    public function reservationTypes(): Collection {
        $reservationTypes = ReservationType::whereHas('reservations')->get();
        $reservationTypes->prepend($this->prependReservationType());
        return $reservationTypes;
    }

    public function getAll(
        GetAllRequest  $request,
        string $entityName,
        string $modelName,
        ?string $startDate = null,
        ?string $endDate = null,
        string $reservationType = null,
        ?string $schedule = null,
    ): array {
        $limit = $request->query('limit', $this->limit);
        $page = $request->query('page', $this->page);
        $startDate = $request->query('startDate', $this->startDate);
        $endDate = $request->query('endDate', $this->endDate);
        $schedule = $request->query('schedule', $this->schedule);
        $reservationType = $request->query('reservationType', $this->reservationType);

        $modelClass = "App\\$entityName\\Models\\$modelName";

        $query = $modelClass::query();


        if ($reservationType) {
            $query->where('reservation_type_id', $reservationType);
        }

        if ($schedule) {
            $query->where('schedule_id', $schedule);
        }

        if ($startDate || $endDate) {
            $query = $this->sharedService->dateRangeFilter($query, $startDate, $endDate);
        }

        $total = $query->count();
        $pages = ceil($total / $limit);

        $models = $query->where('is_deleted', false)
                    ->skip(($page - 1) * $limit)
                    ->take($limit)
                    ->orderBy('id', 'asc')
                    ->get();

        return [
            'collection' => $models,
            'total'=> $total,
            'pages' => $pages,
        ];
    }

    public function update(Reservation $reservation, array $editReservation): Reservation
    {
        $editReservation['total_paid'] += $reservation->total_paid;
        return $this->modelService->update($reservation, $editReservation);
    }

    public function validate(Reservation $reservation, string $modelName): Reservation
    {
        return $this->modelService->validate($reservation, $modelName);
    }
}
