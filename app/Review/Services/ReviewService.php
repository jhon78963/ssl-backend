<?php

namespace App\Review\Services;

use App\Review\Models\Review;
use App\Shared\Services\ModelService;

class ReviewService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newReview): void
    {
        $this->modelService->create(new Review(), $newReview);
    }

    public function delete(Review $review): void
    {
        $this->modelService->delete($review);
    }

    public function update(Review $review, array $editReview): void
    {
        $this->modelService->update($review, $editReview);
    }

    public function validate(Review $review, string $modelName): mixed
    {
        return $this->modelService->validate($review, $modelName);
    }
}
