<?php

namespace App\Book\Controllers;

use App\Book\Models\Book;
use App\Book\Services\BookService;
use App\Room\Models\Room;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Requests\ModifyRequest;
use App\Shared\Resources\GetAllAddResource;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class BookRoomController extends Controller
{
    protected BookService $bookService;
    protected ModelService $modelService;

    public function __construct(BookService $bookService, ModelService $modelService)
    {
        $this->bookService = $bookService;
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Book $book, Room $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach(
                $book,
                'rooms',
                $room->id,
                $request->input('price'),
                1,
                $request->input('isPaid'),
                null,
                null,
                null,
                null,
                $request->input('additionalPeople'),
                null,
            );
            DB::commit();
            $this->updateEndDate($book, $room);
            return response()->json(['message' => 'Room added to the booking.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function modify(ModifyRequest $request, Book $book, Room $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->modify(
                $book,
                'rooms',
                $room->id,
                null,
                null,
                $request->input('isPaid'),
                null,
                null,
                null,
                null,
                $request->input('additionalPeople'),
                null,
            );
            DB::commit();
            return response()->json(['message' => 'Room modified to the booking.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function getAll(Book $book): JsonResponse
    {
        $rooms = $book->rooms()->get();
        return response()->json( GetAllAddResource::collection($rooms));
    }

    public function remove(Book $book, Room $room, float $price): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($book, 'rooms', $room->id);
            $editBooking = [
                'total' => $book->total - $price,
            ];
            $this->modelService->update($book, $editBooking);
            DB::commit();
            return response()->json(['message' => 'Room removed from the booking']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    private function updateEndDate(Book $book, Room $room): void {
        $book->end_date = $this->bookService->increaseHours(
            $book->start_date,
            $room->roomType->rental_hours,
        );
        $book->save();
    }
}
