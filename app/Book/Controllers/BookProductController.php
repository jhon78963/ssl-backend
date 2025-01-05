<?php

namespace App\Book\Controllers;

use App\Book\Models\Book;
use App\Product\Models\Product;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Requests\ModifyRequest;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class BookProductController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Book $book, Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach(
                $book,
                'products',
                $product->id,
                $product->price,
                $request->input('quantity'),
                $request->input('isPaid'),
                $request->input('isFree'),
            );
            DB::commit();
            return response()->json(['message' => 'Product added to the booking.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function modify(ModifyRequest $request, Book $book, Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->modify(
                $book,
                'products',
                $product->id,
                null,
                $request->input('quantity'),
                $request->input('isPaid'),
                $request->input('isFree'),
            );
            DB::commit();
            return response()->json(['message' => 'Product modified to the booking.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function remove(Book $book, Product $product, int $quantity): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($book, 'products', $product->id);
            $editBooking = [
                'total' => $book->total - $product->price * $quantity,
            ];
            $this->modelService->update($book, $editBooking);
            DB::commit();
            return response()->json(['message' => 'Product removed from the booking']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
