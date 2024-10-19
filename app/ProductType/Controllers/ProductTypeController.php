<?php

namespace App\ProductType\Controllers;

use App\ProductType\Models\ProductType;
use App\ProductType\Requests\ProductTypeCreateRequest;
use App\ProductType\Requests\ProductTypeUpdateRequest;
use App\ProductType\Resources\ProductTypeResource;
use App\ProductType\Services\ProductTypeService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;



class ProductTypeController extends Controller
{
    protected ProductTypeService $productTypeService;
    protected SharedService $sharedService;

    public function __construct(ProductTypeService $productTypeService, SharedService $sharedService)
    {
        $this->productTypeService = $productTypeService;
        $this->sharedService = $sharedService;
    }

    public function create(ProductTypeCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newProductType = $this->sharedService->convertCamelToSnake($request->validated());
            $this->productTypeService->create($newProductType);
            DB::commit();
            return response()->json(['message' => 'Product type created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(ProductType $productType): JsonResponse
    {
        DB::beginTransaction();
        try {
            $productTypeValidated = $this->productTypeService->validate($productType, 'ProductType');
            $this->productTypeService->delete($productTypeValidated);
            DB::commit();
            return response()->json(['message' => 'Product type deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(ProductType $productType): JsonResponse
    {
        $productTypeValidated = $this->productTypeService->validate($productType, 'ProductType');
        return response()->json(new ProductTypeResource($productTypeValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'ProductType',
            'ProductType',
            'description'
        );
        return response()->json(new GetAllCollection(
            ProductTypeResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(ProductTypeUpdateRequest $request, ProductType $productType): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editProductType = $this->sharedService->convertCamelToSnake($request->validated());
            $productTypeValidated = $this->productTypeService->validate($productType, 'ProductType');
            $this->productTypeService->update($productTypeValidated, $editProductType);
            DB::commit();
            return response()->json(['message' => 'Product type updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
