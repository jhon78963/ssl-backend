<?php

namespace App\Reservation\Controllers;

use App\Customer\Models\Customer;
use App\Reservation\Models\Reservation;
use App\Shared\Controllers\Controller;
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

    public function add(Reservation $reservation, Customer $customer): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach($reservation, 'customers', $customer->id);
            DB::commit();
            return response()->json(['message' => 'Customer added to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function remove(Reservation $reservation, Customer $customer): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($reservation, 'customers', $customer->id);
            DB::commit();
            return response()->json(['message' => 'Customer removed from the reservation']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
