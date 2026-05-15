<?php

namespace App\Services;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function create(int $userId, int $gameAdId, int $rating, ?string $comment): Review
    {
        return DB::transaction(function () use ($userId, $gameAdId, $rating, $comment) {
            $review = Review::create([
                'user_id'    => $userId,
                'game_ad_id' => $gameAdId,
                'rating'     => $rating,
                'comment'    => $comment,
            ]);

            $this->updateReputation($review->gameAd->user_id);

            return $review;
        });
    }

    public function delete(Review $review): void
    {
        DB::transaction(function () use ($review) {
            $sellerId = $review->gameAd->user_id;
            $review->delete();
            $this->updateReputation($sellerId);
        });
    }

    private function updateReputation(int $userId): void
    {
        $avg = Review::whereHas('gameAd', fn($q) => $q->where('user_id', $userId))
            ->avg('rating') ?? 0;

        User::where('id', $userId)->update(['reputation' => round($avg, 2)]);
    }
}
