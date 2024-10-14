<?php

namespace App\Review\Controllers;

use App\Review\Models\Review;
use App\Review\Requests\ReviewCreateRequest;
use App\Review\Requests\ReviewUpdateRequest;
use App\Review\Resources\ReviewResource;
use App\Review\Services\ReviewService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;


class ReviewController extends Controller
{
    protected ReviewService $reviewService;
    protected SharedService $sharedService;

    public function __construct(
        ReviewService $reviewService,
        SharedService $sharedService,
    ) {
        $this->reviewService = $reviewService;
        $this->sharedService = $sharedService;
    }

    public function create(ReviewCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newReview = $this->sharedService->convertCamelToSnake($request->validated());
            $this->reviewService->create($newReview);
            DB::commit();
            return response()->json(['message' => 'Review created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(Review $review): JsonResponse
    {
        DB::beginTransaction();
        try {
            $reviewValidated = $this->reviewService->validate($review, 'Review');
            $this->reviewService->delete($reviewValidated);
            DB::commit();
            return response()->json(['message' => 'Review deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Review $review): JsonResponse
    {
        $reviewValidated = $this->reviewService->validate($review, 'Review');
        return response()->json(new ReviewResource($reviewValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'Review',
            'Review',
            'customer_name'
        );

        return response()->json(new GetAllCollection(
            ReviewResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(ReviewUpdateRequest $request, Review $review): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editReview = $this->sharedService->convertCamelToSnake($request->validated());
            $reviewValidated = $this->reviewService->validate($review, 'Review');
            $this->reviewService->update($reviewValidated, $editReview);
            DB::commit();
            return response()->json(['message' => 'Review updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
