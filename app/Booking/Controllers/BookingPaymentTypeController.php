<?php

namespace App\Booking\Controllers;

use App\Booking\Models\Booking;
use App\Booking\Services\BookingService;
use App\PaymentType\Models\PaymentType;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class BookingPaymentTypeController extends Controller
{

    protected BookingService $bookingService;
    protected ModelService $modelService;

    public function __construct(BookingService $bookingService, ModelService $modelService)
    {
        $this->bookingService = $bookingService;
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Booking $booking, PaymentType $paymentType): JsonResponse
    {
        DB::beginTransaction();
        try {
            $pivotExists = $this->validatePivot($booking->id, $paymentType->id);
            $this->operatePivote(
                $pivotExists,
                $booking,
                $paymentType->id,
                $request->input('payment'),
                $request->input('cashPayment'),
                $request->input('cardPayment'),
            );
            DB::commit();
            $this->createCash(
                $booking,
                $paymentType->id,
                $request->input('payment'),
                'Ingreso reserva Locker/Hab',
                false,
            );
            return response()->json(['message' => 'Payment Type added to the booking.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function remove(
        Booking $booking,
        int $paymentTypeId,
        float $payment,
    ): JsonResponse {
        DB::beginTransaction();
        try {
            $this->updateBookingPaymentType(
                $booking,
                $paymentTypeId,
                $payment,
            );
            DB::commit();
            $this->createCash(
                $booking,
                $paymentTypeId,
                $payment,
                'Devolución reserva Locker/Hab',
                true
            );
            return response()->json(['message' => 'Payment Type removed from the booking']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    private function updateBookingPaymentType(
        Booking $booking,
        int $paymentTypeId,
        float $payment,
    ): void {
        $pivotData = $booking->paymentTypes()
                ->where('payment_type_id', $paymentTypeId)
                ->first()
                ->pivot;

        $booking->paymentTypes()->updateExistingPivot($paymentTypeId, [
            'payment' => $pivotData->payment - $payment,
        ]);
    }

    private function validatePivot(int $bookingId, int $paymentTypeId): bool
    {
        return $this->modelService->validatePivote(
            'booking_payment_type',
            'booking_id',
            'payment_type_id',
            $bookingId,
            $paymentTypeId
        );
    }

    private function operatePivote(
        bool $pivotExists,
        Booking $booking,
        int $paymentTypeId,
        float $payment,
        float $cashPayment,
        float $cardPayment
    ): void {
        if ($pivotExists) {
            $pivotData = $booking->paymentTypes()
                ->where('payment_type_id', $paymentTypeId)
                ->first()
                ->pivot;
            $payment += $pivotData->payment ?? 0;
            $cashPayment += $pivotData->cash_payment ?? 0;
            $cardPayment += $pivotData->card_payment ?? 0;
            $this->modelService->modify(
                $booking,
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
                $booking,
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

    private function createCash(
        Booking $booking,
        int $paymentTypeId,
        float $totalPaid,
        string $description,
        bool $isRemove,
    ): void {
        if ($totalPaid > 0) {
            switch($paymentTypeId) {
                case 1:
                    $this->bookingService->createCash(
                        $booking,
                        $totalPaid,
                        $totalPaid,
                        0,
                        $description,
                        $isRemove
                    );
                    break;
                case 2:
                    $this->bookingService->createCash(
                        $booking,
                        $totalPaid,
                        0,
                        $totalPaid,
                        $description,
                        $isRemove
                    );
                    break;
                default:
                    break;
            }
        }
    }
}
