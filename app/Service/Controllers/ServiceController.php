<?php

namespace App\Service\Controllers;

use App\Service\Models\Service;
use App\Service\Requests\ServiceCreateRequest;
use App\Service\Requests\ServiceUpdateRequest;
use App\Service\Resources\ServiceResource;
use App\Service\Services\ServiceService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class ServiceController extends Controller
{
    protected ServiceService $serviceService;
    protected SharedService $sharedService;

    public function __construct(ServiceService $serviceService, SharedService $sharedService)
    {
        $this->serviceService = $serviceService;
        $this->sharedService = $sharedService;
    }

    public function create(ServiceCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newService = $this->sharedService->convertCamelToSnake($request->validated());
            $this->serviceService->create($newService);
            DB::commit();
            return response()->json(['message' => 'Service created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(Service $service): JsonResponse
    {
        DB::beginTransaction();
        try {
            $serviceValidated = $this->serviceService->validate($service, 'Service');
            $this->serviceService->delete($serviceValidated);
            DB::commit();
            return response()->json(['message' => 'Service deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Service $service): JsonResponse
    {
        $serviceValidated = $this->serviceService->validate($service, 'Service');
        return response()->json(new ServiceResource($serviceValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Service', 'Service', 'name');
        return response()->json(new GetAllCollection(
            ServiceResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(ServiceUpdateRequest $request, Service $service): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editService = $this->sharedService->convertCamelToSnake($request->validated());
            $serviceValidated = $this->serviceService->validate($service, 'Service');
            $this->serviceService->update($serviceValidated, $editService);
            DB::commit();
            return response()->json(['message' => 'Service updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
