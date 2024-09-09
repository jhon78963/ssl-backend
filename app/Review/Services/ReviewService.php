<?php

namespace App\Review\Services;

use App\Review\Models\Review;
use Auth;

class ReviewService
{
    public function createReview(array $newReview): void
    {
        $review = new Review();
        $review->customer_name = $newReview['customerName'];
        $review->description = $newReview['description'];
        $review->rating = $newReview['rating'];
        $review->creator_user_id = Auth::id();
        $review->save();
    }

    public function updateReview(Review $review, array $editReview): void
    {
        $review->customer_name = $editReview['customerName'] ?? $review->customer_name;
        $review->description = $editReview['description'] ?? $review->description;
        $review->rating = $editReview['rating'] ?? $review->rating;
        $review->last_modification_time = now()->format('Y-m-d H:i:s');
        $review->last_modifier_user_id = Auth::id();
        $review->save();
    }
}
