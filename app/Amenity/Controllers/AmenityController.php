<?php

namespace App\Amenity\Controllers;

use App\Amenity\Models\Amenity;
use App\Amenity\Requests\AmenityCreateRequest;
use App\Amenity\Requests\AmenityUpdateRequest;
use App\Amenity\Resources\AmenityResource;
use App\Amenity\Services\AmenityService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class AmenityController extends Controller
{
    protected AmenityService $amenityService;
    protected SharedService $sharedService;

    public function __construct(AmenityService $amenityService, SharedService $sharedService)
    {
        $this->amenityService = $amenityService;
        $this->sharedService = $sharedService;
    }

    public function create(AmenityCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->amenityService->createAmenity($request->validated());
            DB::commit();
            return response()->json(['message' => 'Amenity created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(Amenity $amenity): JsonResponse
    {
        DB::beginTransaction();
        try {
            $amenityValidated = $this->sharedService->validateModel($amenity, 'Amenity');
            $this->sharedService->deleteModel($amenityValidated);
            DB::commit();
            return response()->json(['message' => 'Amenity deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Amenity $amenity): JsonResponse
    {
        $amenityValidated = $this->sharedService->validateModel($amenity, 'Amenity');
        return response()->json(new AmenityResource($amenityValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Amenity', 'Amenity', 'description');
        return response()->json(new GetAllCollection(
            AmenityResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(AmenityUpdateRequest $request, Amenity $Amenity): JsonResponse
    {
        DB::beginTransaction();
        try {
            $AmenityValidated = $this->sharedService->validateModel($Amenity, 'Amenity');
            $this->amenityService->updateAmenity($AmenityValidated, $request->validated());
            DB::commit();
            return response()->json(['message' => 'Amenity updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
