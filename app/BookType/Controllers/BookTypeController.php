<?php

namespace App\BookType\Controllers;

use App\BookType\Models\BookType;
use App\BookType\Requests\BookTypeCreateRequest;
use App\BookType\Requests\BookTypeUpdateRequest;
use App\BookType\Resources\BookTypeResource;
use App\BookType\Services\BookTypeService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class BookTypeController extends Controller
{
    protected BookTypeService $bookTypeService;
    protected SharedService $sharedService;

    public function __construct(BookTypeService $bookTypeService, SharedService $sharedService)
    {
        $this->bookTypeService = $bookTypeService;
        $this->sharedService = $sharedService;
    }

    public function create(BookTypeCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newBookType = $this->sharedService->convertCamelToSnake($request->validated());
            $this->bookTypeService->create($newBookType);
            DB::commit();
            return response()->json(['message' => 'Product type created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(BookType $bookType): JsonResponse
    {
        DB::beginTransaction();
        try {
            $bookTypeValidated = $this->bookTypeService->validate($bookType, 'BookType');
            $this->bookTypeService->delete($bookTypeValidated);
            DB::commit();
            return response()->json(['message' => 'Product type deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(BookType $bookType): JsonResponse
    {
        $bookTypeValidated = $this->bookTypeService->validate($bookType, 'BookType');
        return response()->json(new BookTypeResource($bookTypeValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'BookType',
            'BookType',
            'description'
        );
        return response()->json(new GetAllCollection(
            BookTypeResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(BookTypeUpdateRequest $request, BookType $bookType): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editBookType = $this->sharedService->convertCamelToSnake($request->validated());
            $bookTypeValidated = $this->bookTypeService->validate($bookType, 'BookType');
            $this->bookTypeService->update($bookTypeValidated, $editBookType);
            DB::commit();
            return response()->json(['message' => 'Product type updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
