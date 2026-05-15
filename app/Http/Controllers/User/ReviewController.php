<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GameAd;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(private ReviewService $reviewService) {}

    public function store(Request $request)
    {
        $request->validate([
            'game_ad_id' => 'required|exists:game_ads,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        $ad = GameAd::findOrFail($request->game_ad_id);

        if ($ad->user_id === auth()->id()) {
            return back()->with('error', 'No puedes valorar tu propia oferta.');
        }

        $already = Review::where('user_id', auth()->id())
            ->where('game_ad_id', $request->game_ad_id)
            ->exists();

        if ($already) {
            return back()->with('error', 'Ya has valorado esta oferta.');
        }

        $this->reviewService->create(
            auth()->id(),
            $request->game_ad_id,
            $request->rating,
            $request->comment
        );

        return back()->with('success', 'Reseña enviada correctamente.');
    }

    public function destroy(Review $review)
    {
        if (auth()->id() !== $review->user_id) {
            abort(403);
        }

        $this->reviewService->delete($review);

        return back()->with('success', 'Reseña eliminada correctamente.');
    }
}