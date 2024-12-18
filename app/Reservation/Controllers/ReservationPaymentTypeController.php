<?php

namespace App\Reservation\Controllers;

use App\PaymentType\Models\PaymentType;
use App\Reservation\Models\Reservation;
use App\Service\Resources\ServiceGetAllAddResource;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class ReservationPaymentTypeController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Reservation $reservation, PaymentType $paymentType): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach(
                $reservation,
                'paymentTypes',
                $paymentType->id,
                null,
                null,
                null,
                null,
                $request->input('payment'),
                $request->input('cashPayment'),
                $request->input('cardPayment'),
            );
            DB::commit();
            return response()->json(['message' => 'Payment Type added to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function getAll(Reservation $reservation): JsonResponse
    {
        $paymentTypes = $reservation->paymentTypes()->get();
        return response()->json( ServiceGetAllAddResource::collection($paymentTypes));
    }

    public function remove(
        Reservation $reservation,
        PaymentType $paymentType,
        float $payment,
        float $cashPayment,
        float $cardPayment
    ): JsonResponse {
        DB::beginTransaction();
        try {
            $this->modelService->detach($reservation, 'paymentTypes', $paymentType->id);
            $editReservation = [
                'total' => $reservation->total - ($paymentType->id == 3 ? $cashPayment + $cardPayment : $payment),
            ];
            $this->modelService->update($reservation, $editReservation);
            DB::commit();
            return response()->json(['message' => 'Payment Type removed from the reservation']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
