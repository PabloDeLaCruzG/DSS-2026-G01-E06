<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_ad_id',
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gameAd()
    {
        return $this->belongsTo('App\\Models\\GameAd', 'game_ad_id');
    }

    public function calculateAverage(): float
    {
        return (float) (static::query()
            ->where('game_ad_id', $this->game_ad_id)
            ->avg('rating') ?? 0.0);
    }
}
