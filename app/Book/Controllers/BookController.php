<?php

namespace App\Book\Controllers;

use App\Book\Models\Book;
use App\Book\Requests\BookChangeStatusRequest;
use App\Book\Requests\BookCreateRequest;
use App\Book\Requests\BookUpdateRequest;
use App\Book\Resources\BookResource;
use App\Book\Services\BookService;
use App\Schedule\Services\ScheduleService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class BookController extends Controller
{
    protected BookService $bookService;
    protected ScheduleService $scheduleService;
    protected SharedService $sharedService;

    public function __construct(
        BookService $bookService,
        ScheduleService $scheduleService,
        SharedService $sharedService,
    ) {
        $this->bookService = $bookService;
        $this->scheduleService = $scheduleService;
        $this->sharedService = $sharedService;
    }
    public function changeStatus(BookChangeStatusRequest $request, Book $book): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editBook = $this->sharedService->convertCamelToSnake($request->validated());
            $bookValidated = $this->bookService->validate(
                $book,
                'Book'
            );
            $this->bookService->update($bookValidated, $editBook);
            DB::commit();
            return response()->json(['message' => 'Book status changed.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function create(BookCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newBook = $this->sharedService->convertCamelToSnake($request->validated());
            $newBook['schedule_id'] = $this->scheduleService->get();
            $createdBook = $this->bookService->create($newBook);
            DB::commit();
            return response()->json([
                'message' => 'Book created.',
                'bookId' => $createdBook->id,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Book $book): JsonResponse
    {
        $bookValidated = $this->bookService->validate($book, 'Book');
        return response()->json(new BookResource($bookValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->bookService->getAll(
            $request,
            'Book',
            'Book',
            $request->input('startDate'),
            $request->input('endDate'),
            $request->input('schedule'),
        );
        return response()->json(new GetAllCollection(
            BookResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(BookUpdateRequest $request, Book $book): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editBookValidated = $this->sharedService->convertCamelToSnake($request->validated());
            $bookValidated = $this->bookService->validate($book, 'Book');
            $this->bookService->update(
                $bookValidated,
                $editBookValidated
            );
            DB::commit();
            return response()->json(['message' => 'Book updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
