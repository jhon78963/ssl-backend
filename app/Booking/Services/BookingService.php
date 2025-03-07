<?php

namespace App\Booking\Services;

use App\Booking\Models\Booking;
use App\Cash\Models\Cash;
use App\Cash\Models\CashOperation;
use App\Cash\Services\CashService;
use App\Room\Models\Room;
use App\Schedule\Services\ScheduleService;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Services\ModelService;
use App\Shared\Services\SharedService;
use Carbon\Carbon;

class BookingService {
    private int $limit = 10;
    private int $page = 1;
    private string $startDate = '';
    private string $endDate = '';
    private string $dni = '';
    protected CashService $cashService;
    protected ModelService $modelService;
    protected ScheduleService $scheduleService;
    protected SharedService $sharedService;

    public function __construct(
        CashService $cashService,
        ModelService $modelService,
        ScheduleService $scheduleService,
        SharedService $sharedService
    ) {
        $this->cashService = $cashService;
        $this->modelService = $modelService;
        $this->scheduleService = $scheduleService;
        $this->sharedService = $sharedService;
    }

    public function changeStatus(Booking $booking, array $editBooking): Booking
    {
        return $this->modelService->update($booking, $editBooking);
    }

    public function checkSchedule(int $roomId, string $startDate, int $hours): array
    {
        $startDateParsed = Carbon::parse($startDate);
        $endDate = $startDateParsed->addHours($hours)->toDateTimeString();

        $conflictingBooking = Booking::where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                      $query->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                    });
                })
            ->where('status', '=', 'PENDING')
            ->whereHas('rooms', function ($query) use ($roomId) {
                $query->where('room_id', $roomId);
            })->first();

        if ($conflictingBooking) {
            return [
                'conflict' => true,
                'conflictingStartDate' => Carbon::parse($conflictingBooking->start_date)->format('H:i'),
                'conflictingEndDate' => Carbon::parse($conflictingBooking->end_date)->format('H:i'),
            ];
        }

        return [
            'conflict' => false,
        ];
    }

    public function create(array $newBooking): Booking
    {
        return $this->modelService->create(new Booking(), $newBooking);
    }

    public function createCash(
        Booking $booking,
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
            'booking_id' => $booking->id,
            'cash_type_id' => 2,
            'schedule_id' => $this->scheduleService->get(),
            'date' => now(),
            'description' => $description,
            'amount' => $isRemove ? -$totalPaid : $totalPaid,
            'cash_amount' => $isRemove ? -$totalCashPaid : $totalCashPaid,
            'card_amount' => $isRemove ? -$totalCardPaid: $totalCardPaid,
        ]);
    }

    public function getAll(
        GetAllRequest  $request,
        string $entityName,
        string $modelName,
        ?string $startDate = null,
        ?string $endDate = null,
        ?string $dni = null,
    ): array {
        $limit = $request->query('limit', $this->limit);
        $page = $request->query('page', $this->page);
        $startDate = $request->query('startDate', $this->startDate);
        $endDate = $request->query('endDate', $this->endDate);
        $dni = $request->query('dni', $this->dni);

        $modelClass = "App\\$entityName\\Models\\$modelName";

        $query = $modelClass::query();

        if ($startDate || $endDate) {
            $query = $this->sharedService->dateRangeFilter($query, $startDate, $endDate);
        }

        if ($dni) {
            $query = $this->sharedService->searchFilter($query, $dni, 'description');
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

    public function increaseHours(string $startDate, int $hours): string
    {
        $date = Carbon::parse($startDate);
        $date->addHours($hours);
        return $date->toDateTimeString();
    }


    public function update(Booking $booking, array $editBooking): Booking
    {
        $editBooking['total_paid'] += $booking->total_paid;
        return $this->modelService->update($booking, $editBooking);
    }

    public function validate(Booking $booking, string $modelName): Booking
    {
        return $this->modelService->validate($booking, $modelName);
    }
}
