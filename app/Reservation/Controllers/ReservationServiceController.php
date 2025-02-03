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
            $pivotExists = $this->validatePivot(
                $reservation->id,
                $service->id,
                $request->input('isPaid'),
                $request->input('isFree'),
            );
            $this->operatePivote(
                $pivotExists,
                $reservation,
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
            $isFree = $request->input('isFree');
            $isPaid = $request->input('isPaid');
            $isPaidBd = $request->input('isPaidBd');
            $quantity = $request->input('quantity');

            $pivotExists = $this->validatePivot(
                $reservation->id,
                $service->id,
                $request->input('isPaid'),
                $request->input('isFree'),
            );

            if ($pivotExists) {
                if ($isPaid != $isPaidBd) {
                    DB::table('reservation_service')
                        ->where('reservation_id', $reservation->id)
                        ->where('service_id', $service->id)
                        ->where('is_paid', $isPaid)
                        ->where('is_free', $isFree)
                        ->increment(
                            'quantity', $quantity,
                            [
                                'is_paid' => $isPaid,
                                'is_free' => $isFree
                            ]);

                    DB::table('reservation_service')
                        ->where('reservation_id', $reservation->id)
                        ->where('service_id', $service->id)
                        ->where('is_paid', $isPaidBd)
                        ->where('is_free', $isFree)
                        ->delete();
                } else {
                    $reservationService = DB::table('reservation_service')
                        ->where('reservation_id', $reservation->id)
                        ->where('service_id', $service->id)
                        ->where('is_paid', $isPaid)
                        ->where('is_free', $isFree)
                        ->first();

                    $totalQuantity = $reservationService->quantity + $quantity;

                    DB::table('reservation_service')
                        ->where('reservation_id', $reservation->id)
                        ->where('service_id', $service->id)
                        ->where('is_paid', $isPaidBd)
                        ->where('is_free', $isFree)
                        ->delete();

                    DB::table('reservation_service')->insert([
                        'reservation_id' => $reservation->id,
                        'service_id' => $service->id,
                        'is_paid' => $isPaid,
                        'is_free' => $isFree,
                        'quantity' => $totalQuantity,
                        'price' => $service->price,
                    ]);
                }
            } else {
                DB::table('reservation_service')
                    ->where('reservation_id', $reservation->id)
                    ->where('service_id', $service->id)
                    ->where('is_paid', $isPaidBd)
                    ->where('is_free', $isFree)
                    ->increment(
                        'quantity', $quantity,
                        [
                            'is_paid' => $isPaid,
                            'is_free' => $isFree
                        ]);
            }
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

    private function validatePivot(int $reservationId, int $serviceId, bool $isPaid, bool $isFree): bool
    {
        return DB::table('reservation_service')
            ->where('reservation_id', '=', $reservationId)
            ->where('service_id', '=', $serviceId)
            ->where('is_paid', '=', $isPaid)
            ->where('is_free', '=', $isFree)
            ->exists();
    }

    private function operatePivote(
        bool $pivotExists,
        Reservation $reservation,
        int $serviceId,
        float $price,
        int $quantity,
        bool $isPaid,
        bool $isFree,
    ) {
        if ($pivotExists) {
            DB::table('reservation_service')
                ->where('reservation_id', $reservation->id)
                ->where('service_id', $serviceId)
                ->where('is_paid', $isPaid)
                ->where('is_free', $isFree)
                ->increment('quantity', $quantity, [
                    'is_paid' => $isPaid,
                    'is_free' => $isFree
                ]);
        } else {
            $this->modelService->attach(
                $reservation,
                'services',
                $serviceId,
                $price,
                $quantity,
                $isPaid,
                $isFree,
            );
        }
    }
}
