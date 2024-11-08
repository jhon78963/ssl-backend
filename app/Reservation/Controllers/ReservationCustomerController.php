<?php

namespace App\Reservation\Controllers;

use App\Customer\Models\Customer;
use App\Reservation\Models\Reservation;
use App\Service\Resources\ServiceGetAllAddResource;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class ReservationCustomerController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Reservation $reservation, Customer $customer): JsonResponse
    {
        DB::beginTransaction();
        try {
            $customerCount = $reservation->customers()->count();
            $price = $customerCount >= 2 ? $request->input('price') : 0;
            $this->modelService->attach(
                $reservation,
                'customers',
                $customer->id,
                $price,
                1,
            );
            $editReservation = [
                'total' => $reservation->total + $price,
            ];
            $this->modelService->update($reservation, $editReservation);
            DB::commit();
            return response()->json(['message' => 'Customer added to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function getAll(Reservation $reservation): JsonResponse
    {
        $customers = $reservation->customers()->get();
        return response()->json( ServiceGetAllAddResource::collection($customers));
    }

    public function remove(Reservation $reservation, Customer $customer, float $price): JsonResponse
    {
        DB::beginTransaction();
        try {
            $customerCount = $reservation->customers()->count();
            $priceT = $customerCount > 2 ? $price : 0;
            $this->modelService->detach($reservation, 'customers', $customer->id);
            $editReservation = [
                'total' => $reservation->total - $priceT,
            ];
            $this->modelService->update($reservation, $editReservation);
            DB::commit();
            return response()->json(['message' => 'Customer removed from the reservation']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
