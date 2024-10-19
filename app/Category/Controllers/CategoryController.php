<?php

namespace App\Category\Controllers;

use App\Category\Models\Category;
use App\Category\Requests\CategoryCreateRequest;
use App\Category\Requests\CategoryUpdateRequest;
use App\Category\Resources\CategoryResource;
use App\Category\Services\CategoryService;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;


class CategoryController
{
    protected CategoryService $categoryService;
    protected SharedService $sharedService;

    public function __construct(CategoryService $categoryService, SharedService $sharedService)
    {
        $this->categoryService = $categoryService;
        $this->sharedService = $sharedService;
    }

    public function create(CategoryCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newCategory = $this->sharedService->convertCamelToSnake($request->validated());
            $this->categoryService->create($newCategory);
            DB::commit();
            return response()->json(['message' => 'Category created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(Category $category): JsonResponse
    {
        DB::beginTransaction();
        try {
            $categoryValidated = $this->categoryService->validate($category, 'Category');
            $this->categoryService->delete($categoryValidated);
            DB::commit();
            return response()->json(['message' => 'Category deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Category $category): JsonResponse
    {
        $categoryValidated = $this->categoryService->validate($category, 'Category');
        return response()->json(new CategoryResource($categoryValidated));
    }

    public function getAll(GetAllRequest  $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'Category',
            'Category',
            'name'
        );
        return response()->json(new GetAllCollection(
            CategoryResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editCategory = $this->sharedService->convertCamelToSnake($request->validated());
            $categoryValidated = $this->categoryService->validate($category, 'Category');
            $this->categoryService->update($categoryValidated, $editCategory);
            DB::commit();
            return response()->json(['message' => 'Category updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
