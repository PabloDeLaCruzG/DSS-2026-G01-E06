<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'year',
        'cover_image',
        'trailer_url',
        'rating',
        'genres',
        'platforms',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'genres'       => 'array',
            'platforms'    => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function gameAds()
    {
        return $this->hasMany(GameAd::class);
    }

    public function getLowestPrice(): float
    {
        return $this->gameAds()->min('price') ?? 0.0;
    }
}
