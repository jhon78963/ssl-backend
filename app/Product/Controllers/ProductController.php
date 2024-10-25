<?php

namespace App\Product\Controllers;

use App\Product\Models\Product;
use App\Product\Requests\ProductCreateRequest;
use App\Product\Requests\ProductUpdateRequest;
use App\Product\Resources\ProductResource;
use App\Product\Services\ProductService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;



class ProductController extends Controller
{
    protected ProductService $productService;
    protected SharedService $sharedService;

    public function __construct(productService $productService, SharedService $sharedService)
    {
        $this->productService = $productService;
        $this->sharedService = $sharedService;
    }

    public function create(ProductCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newProduct = $this->sharedService->convertCamelToSnake($request->validated());
            $this->productService->create($newProduct);
            DB::commit();
            return response()->json(['message' => 'Product created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $productValidated = $this->productService->validate($product, 'Product');
            $this->productService->delete($productValidated);
            DB::commit();
            return response()->json(['message' => 'Product deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Product $product): JsonResponse
    {
        $productValidated = $this->productService->validate($product, 'Product');
        return response()->json(new ProductResource($productValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Product', 'Product', 'name');
        return response()->json(new GetAllCollection(
            ProductResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(ProductUpdateRequest $request, Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editProduct = $this->sharedService->convertCamelToSnake($request->validated());
            $productValidated = $this->productService->validate($product, 'Product');
            $this->productService->update($productValidated, $editProduct);
            DB::commit();
            return response()->json(['message' => 'Product updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
