<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

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
}
