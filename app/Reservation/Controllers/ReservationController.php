<?php

namespace App\Reservation\Controllers;

use App\Cash\Services\CashOperationService;
use App\Cash\Services\CashService;
use App\Reservation\Exports\ReservationsExport;
use App\Reservation\Models\Reservation;
use App\Reservation\Requests\ProductSearchRequest;
use App\Reservation\Requests\ReservationChangeStatusRequest;
use App\Reservation\Requests\ReservationCreateRequest;
use App\Reservation\Requests\ReservationExportRequest;
use App\Reservation\Requests\ReservationUpdateRequest;
use App\Reservation\Resources\FacilitiesResource;
use App\Reservation\Resources\ProductsResource;
use App\Reservation\Resources\ReservationResource;
use App\Reservation\Resources\ReservationTypeResource;
use App\Reservation\Services\ReservationService;
use App\Schedule\Services\ScheduleService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use DB;

class ReservationController extends Controller
{
    protected CashService $cashService;
    protected CashOperationService $cashOperationService;
    protected ReservationService $reservationService;
    protected ScheduleService $scheduleService;
    protected SharedService $sharedService;

    public function __construct(
        CashService $cashService,
        CashOperationService $cashOperationService,
        ReservationService $reservationService,
        ScheduleService $scheduleService,
        SharedService $sharedService,
    ) {
        $this->cashService = $cashService;
        $this->cashOperationService = $cashOperationService;
        $this->reservationService = $reservationService;
        $this->scheduleService = $scheduleService;
        $this->sharedService = $sharedService;
    }

    public function changeStatus(ReservationChangeStatusRequest $request, Reservation $reservation)
    {
        DB::beginTransaction();
        try {
            $editReservation = $this->sharedService->convertCamelToSnake($request->validated());
            $reservationValidated = $this->reservationService->validate(
                $reservation,
                'Reservation',
            );
            $this->reservationService->update($reservationValidated, $editReservation);
            DB::commit();
            return response()->json(['message' => 'Reservation status changed.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function create(ReservationCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newReservation = $this->sharedService->convertCamelToSnake($request->validated());
            $newReservation['schedule_id'] = $this->scheduleService->get();
            $reservationCreated = $this->reservationService->create($newReservation);
            DB::commit();
            $this->createCash($reservationCreated, $newReservation['total_paid_cash']);
            return response()->json([
                'message' => 'Reservation created.',
                'reservationId' => $reservationCreated->id,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function export(ReservationExportRequest $request): BinaryFileResponse
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $reservationType = $request->input('reservationType');
        $schedule = $request->input('schedule');
        return Excel::download(
            new ReservationsExport(
                $startDate,
                $endDate,
                $reservationType,
                $schedule,
            ),
            'reservation.xlsx'
        );
    }

    public function facilities(): JsonResponse
    {
        return response()->json(
            FacilitiesResource::collection(
                $this->reservationService->facilities(),
            ),
        );
    }

    public function validateFacilities(): JsonResponse
    {
        return response()->json([
            'count' => $this->reservationService->validateFacilities(),
        ]);
    }

    public function products(ProductSearchRequest $request): JsonResponse
    {
        $nameFilter = $request->input('name');
        $products = $nameFilter ? $this->reservationService->products($nameFilter) : [];
        return response()->json(
            ProductsResource::collection($products)
        );
    }

    public function reservationTypes(): JsonResponse
    {
        return response()->json(
            ReservationTypeResource::collection(
                $this->reservationService->reservationTypes(),
            ),
        );
    }

    public function get(Reservation $reservation): JsonResponse
    {
        $reservationValidated = $this->reservationService->validate($reservation, 'Reservation');
        return response()->json(new ReservationResource($reservationValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->reservationService->getAll(
            $request,
            'Reservation',
            'Reservation',
            $request->input('startDate'),
            $request->input('endDate'),
            $request->input(key: 'reservationType'),
            $request->input('schedule'),
        );
        return response()->json(new GetAllCollection(
            ReservationResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(ReservationUpdateRequest $request, Reservation $reservation): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editReservation = $this->sharedService->convertCamelToSnake($request->validated());
            $reservation = $this->reservationService->validate($reservation, 'Reservation');
            $this->reservationService->update(
                $reservation,
                $editReservation,
            );
            DB::commit();
            $this->createCash($reservation, $editReservation['total_paid_cash']);
            return response()->json(['message' => 'Reservation updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    private function createCash(Reservation $reservation, float $totalPaid): void
    {
        if ($totalPaid > 0) {
            $this->reservationService->createCash(
                $reservation,
                $totalPaid,
            );
        }
    }
}
