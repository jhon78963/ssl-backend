<?php

namespace App\Schedule\Controllers;

use App\Schedule\Resources\ScheduleResource;
use App\Schedule\Services\ScheduleService;
use App\Shared\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ScheduleController extends Controller
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
