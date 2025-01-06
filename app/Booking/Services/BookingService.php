<?php

namespace App\Booking\Services;

use App\Booking\Models\Booking;
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
    protected ModelService $modelService;
    protected SharedService $sharedService;

    public function __construct(ModelService $modelService, SharedService $sharedService)
    {
        $this->modelService = $modelService;
        $this->sharedService = $sharedService;
    }

    public function create(array $newBooking): Booking
    {
        return $this->modelService->create(new Booking(), $newBooking);
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
            $query = $this->sharedService->dateFilter($query, $startDate, $endDate);
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
