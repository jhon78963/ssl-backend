<?php

namespace App\Reservation\Controllers;

use App\Reservation\Models\Reservation;
use App\Service\Models\Service;
use App\Service\Resources\ServiceGetAllAddResource;
use App\Service\Resources\ServiceGetLeftAddResource;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
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
            );
            $editReservation = [
                'total' => $reservation->total + $service->price * $request->input('quantity'),
            ];
            $this->modelService->update($reservation, $editReservation);
            DB::commit();
            return response()->json(['message' => 'Service added to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function getAll(Reservation $reservation): JsonResponse
    {
        $services = $reservation->services()->get();
        return response()->json( ServiceGetAllAddResource::collection($services));
    }

    public function getLeft(Reservation $reservation): JsonResponse
    {
        $allServices = Service::where('is_deleted', false)->get();
        $associatedServices = $reservation->services()->pluck('id')->toArray();
        $leftServices = $allServices->whereNotIn('id', $associatedServices);
        return response()->json( ServiceGetLeftAddResource::collection($leftServices));
    }

    public function remove(Reservation $reservation, Service $service, int $quantity): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($reservation, 'services', $service->id);
            $editReservation = [
                'total' => $reservation - $service->price * $quantity,
            ];
            $this->modelService->update($reservation, $editReservation);
            DB::commit();
            return response()->json(['message' => 'Service removed from the reservation']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
