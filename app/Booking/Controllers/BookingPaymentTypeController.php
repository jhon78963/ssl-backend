<?php

namespace App\Booking\Controllers;

use App\Booking\Models\Booking;
use App\PaymentType\Models\PaymentType;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class BookingPaymentTypeController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Booking $booking, PaymentType $paymentType): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach(
                $booking,
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
            return response()->json(['message' => 'Payment Type added to the booking.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function remove(
        Booking $booking,
        PaymentType $paymentType,
        float $payment,
        float $cashPayment,
        float $cardPayment
    ): JsonResponse {
        DB::beginTransaction();
        try {
            $this->modelService->detach($booking, 'paymentTypes', $paymentType->id);
            $editBooking = [
                'total' => $booking->total - ($paymentType->id == 3 ? $cashPayment + $cardPayment : $payment),
            ];
            $this->modelService->update($booking, $editBooking);
            DB::commit();
            return response()->json(['message' => 'Payment Type removed from the booking']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
