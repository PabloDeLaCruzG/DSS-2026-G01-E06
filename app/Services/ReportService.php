<?php

namespace App\Services;

use App\Models\GameAd;
use App\Models\Report;

class ReportService
{
    public function isDuplicate(int $userId, int $gameAdId): bool
    {
        return Report::where('user_id', $userId)
            ->where('game_ad_id', $gameAdId)
            ->where('status', Report::STATUS_OPEN)
            ->exists();
    }

    public function store(int $userId, GameAd $gameAd, string $reason): Report
    {
        return Report::create([
            'user_id'    => $userId,
            'game_ad_id' => $gameAd->id,
            'reason'     => $reason,
            'status'     => Report::STATUS_OPEN,
        ]);
    }
}
