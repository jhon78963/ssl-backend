<?php

namespace App\Schedule\Controllers;

use App\Schedule\Resources\ScheduleResource;
use App\Schedule\Services\ScheduleService;
use Illuminate\Http\JsonResponse;

class ScheduleController
{
    protected ScheduleService $scheduleService;

    public function __construct(ScheduleService $scheduleService) {
        $this->scheduleService = $scheduleService;
    }

    public function get(): JsonResponse
    {
        return response()->json(
            new ScheduleResource(
                $this->scheduleService->get(true)
            ),
        );
    }
}
