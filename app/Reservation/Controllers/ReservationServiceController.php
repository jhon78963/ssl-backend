<?php

namespace App\Reservation\Controllers;

use App\Reservation\Models\Reservation;
use App\Service\Models\Service;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Requests\ModifyRequest;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class ReservationServiceController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Reservation $reservation, Service $service): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach(
                $reservation,
                'services',
                $service->id,
                $service->price,
                $request->input('quantity'),
                $request->input('isPaid'),
                $request->input('isFree'),
            );
            DB::commit();
            return response()->json(['message' => 'Service added to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function modify(ModifyRequest $request, Reservation $reservation, Service $service): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->modify(
                $reservation,
                'services',
                $service->id,
                null,
                $request->input('quantity'),
                $request->input('isPaid'),
                $request->input('isFree'),
            );
            DB::commit();
            return response()->json(['message' => 'Service modified to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function remove(Reservation $reservation, Service $service, int $quantity): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($reservation, 'services', $service->id);
            $this->updateReservation(
                $reservation,
                $service->price,
                $quantity
            );
            DB::commit();
            return response()->json(['message' => 'Service removed from the reservation']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    private function updateReservation(Reservation $reservation, float $servicePrice, float $serviceQuantity): void
    {
        $editReservation = [
            'total' => $reservation->total - $servicePrice * $serviceQuantity,
            'total_paid' => $reservation->total_paid - $servicePrice * $serviceQuantity,
            'consumptions_import' => $reservation->consumptions_import - $servicePrice * $serviceQuantity,
        ];
        $this->modelService->update($reservation, $editReservation);
    }
}
