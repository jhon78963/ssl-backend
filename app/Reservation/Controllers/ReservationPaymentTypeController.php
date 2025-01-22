<?php

namespace App\Reservation\Controllers;

use App\PaymentType\Models\PaymentType;
use App\Reservation\Models\Reservation;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Resources\GetAllAddResource;
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
            $pivotExists = $this->validatePivot($reservation->id, $paymentType->id);
            $this->operatePivote(
                $pivotExists,
                $reservation,
                $paymentType->id,
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

    public function remove(
        Reservation $reservation,
        int $paymentTypeId,
        float $payment,
        // float $cashPayment,
        // float $cardPayment
    ): JsonResponse {
        DB::beginTransaction();
        try {
            $this->updateReservationPaymentType(
                $reservation,
                $paymentTypeId,
                $payment,
                // $cashPayment,
                // $cardPayment,
            );
            DB::commit();
            return response()->json(['message' => 'Payment Type removed from the reservation']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    private function updateReservationPaymentType(
        Reservation $reservation,
        int $paymentTypeId,
        float $payment,
        // float $cashPayment,
        // float $cardPayment,
    ): void {
        $pivotData = $reservation->paymentTypes()
                ->where('payment_type_id', $paymentTypeId)
                ->first()
                ->pivot;

        $reservation->paymentTypes()->updateExistingPivot($paymentTypeId, [
            'payment' => $pivotData->payment - $payment,
            // 'cash_payment' => $pivotData->cash_payment - $cashPayment,
            // 'card_payment' => $pivotData->card_payment - $cardPayment,
        ]);
    }

    private function validatePivot(int $reservationId, int $paymentTypeId): bool
    {
        return $this->modelService->validatePivote(
            'reservation_payment_type',
            'reservation_id',
            'payment_type_id',
            $reservationId,
            $paymentTypeId
        );
    }

    private function operatePivote(
        bool $pivotExists,
        Reservation $reservation,
        int $paymentTypeId,
        float $payment,
        float $cashPayment,
        float $cardPayment
    ): void {
        if ($pivotExists) {
            $pivotData = $reservation->paymentTypes()
                ->where('payment_type_id', $paymentTypeId)
                ->first()
                ->pivot;
            $payment += $pivotData->payment ?? 0;
            $cashPayment += $pivotData->cash_payment ?? 0;
            $cardPayment += $pivotData->card_payment ?? 0;
            $this->modelService->modify(
                $reservation,
                'paymentTypes',
                $paymentTypeId,
                null,
                null,
                null,
                null,
                $payment,
                $cashPayment,
                $cardPayment
            );
        } else {
            $this->modelService->attach(
                $reservation,
                'paymentTypes',
                $paymentTypeId,
                null,
                null,
                null,
                null,
                $payment,
                $cashPayment,
                $cardPayment
            );
        }
    }
}
