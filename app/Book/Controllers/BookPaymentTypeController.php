<?php

namespace App\Book\Controllers;

use App\Book\Models\Book;
use App\PaymentType\Models\PaymentType;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Resources\GetAllAddResource;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class BookPaymentTypeController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Book $book, PaymentType $paymentType): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach(
                $book,
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

    public function getAll(Book $book): JsonResponse
    {
        $paymentTypes = $book->paymentTypes()->get();
        return response()->json( GetAllAddResource::collection($paymentTypes));
    }

    public function remove(
        Book $book,
        PaymentType $paymentType,
        float $payment,
        float $cashPayment,
        float $cardPayment
    ): JsonResponse {
        DB::beginTransaction();
        try {
            $this->modelService->detach($book, 'paymentTypes', $paymentType->id);
            $editBooking = [
                'total' => $book->total - ($paymentType->id == 3 ? $cashPayment + $cardPayment : $payment),
            ];
            $this->modelService->update($book, $editBooking);
            DB::commit();
            return response()->json(['message' => 'Payment Type removed from the booking']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
