<?php

namespace App\Cash\Services;

use App\Cash\Models\CashOperation;
use App\CashType\Models\CashType;
use App\Reservation\Models\Reservation;
use App\Schedule\Models\Schedule;
use App\Shared\Services\ModelService;

class CashOperationService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newCash): void
    {
        $this->modelService->create(new CashOperation(), $newCash);
    }

    public function total(int $cashId): mixed
    {
        return CashOperation::where('is_deleted', '=', false)
            ->where('cash_id', '=', $cashId)
            ->sum('amount');
    }

    public function validate() : CashType
    {
        $cashOperation = CashOperation::where('is_deleted', '=', false)
            ->orderBy('id', 'desc')
            ->first();

        return match ($cashOperation->cash_type_id ?? 3) {
            1 => CashType::find(3),
            2 => CashType::find(3),
            3 => CashType::find(1),
        };
    }

    public function schedule(): int
    {
        $currentTime = now()->format('H:i:s');
        $schedules = Schedule::where('is_deleted', '=', false)->get();

        $currentSchedule = null;
        foreach ($schedules as $schedule) {
            if ($currentTime >= $schedule->start_time || $currentTime <= $schedule->end_time) {
                $currentSchedule = $schedule->id;
            }
        }
        return $currentSchedule;
    }
}
