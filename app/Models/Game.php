<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    public const GENRE_ACTION = 'ACTION';
    public const GENRE_RPG = 'RPG';
    public const GENRE_SPORTS = 'SPORTS';

    public const PLATFORM_PS5 = 'PS5';
    public const PLATFORM_XBOX = 'XBOX';
    
    protected $fillable = [
        'title',
        'description',
        'release_date',
        'cover_image',
        'genre',
        'platform'
    ];

    // Un juego del catálogo puede tener muchos anuncios de venta
    public function gameAds()
    {
        return $this->hasMany(GameAd::class);
    }

    public function getLowestPrice(): float
    {
        return $this->gameAds()->min('price') ?? 0.0;
    }
}