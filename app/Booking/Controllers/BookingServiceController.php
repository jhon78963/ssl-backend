<?php

namespace App\Booking\Controllers;

use App\Booking\Models\Booking;
use App\Service\Models\Service;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Requests\ModifyRequest;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class BookingServiceController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Booking $booking, Service $service): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach(
                $booking,
                'services',
                $service->id,
                $service->price,
                $request->input('quantity'),
                $request->input('isPaid'),
                $request->input('isFree'),
            );
            DB::commit();
            return response()->json(['message' => 'Service added to the booking.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function modify(ModifyRequest $request, Booking $booking, Service $service): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->modify(
                $booking,
                'services',
                $service->id,
                null,
                $request->input('quantity'),
                $request->input('isPaid'),
                $request->input('isFree'),
            );
            DB::commit();
            return response()->json(['message' => 'Service modified to the boking.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function remove(Booking $booking, Service $service, int $quantity): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($booking, 'services', $service->id);
            $editBooking = [
                'total' => $booking->total - $service->price * $quantity,
            ];
            $this->modelService->update($booking, $editBooking);
            DB::commit();
            return response()->json(['message' => 'Service removed from the booking']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
