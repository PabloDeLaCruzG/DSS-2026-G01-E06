<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameAd extends Model
{

    use HasFactory;

    public const CONDITION_NEW = 'NEW';
    public const CONDITION_USED = 'USED';
    public const CONDITION_DIGITAL = 'DIGITAL';

    public const FORMAT_PHYSICAL = 'PHYSICAL';
    public const FORMAT_DIGITAL_KEY = 'DIGITAL_KEY';

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_SOLD = 'SOLD';
    public const STATUS_HIDDEN = 'HIDDEN';

    public const TYPE_BUY_NOW = 'BUY_NOW';
    public const TYPE_AUCTION = 'AUCTION';

    protected $fillable = [
        'price',
        'description',
        'condition',
        'format',
        'platforms',
        'digital_key',
        'images',
        'status',
        'type',
        'quantity'

    ];

    protected function casts(): array
    {
        return [
            'platforms' => 'array', // Para que lo trate como array en PHP
            'images' => 'array',    // Ya que estamos, las fotos también son un array
        ];
    }

    // Un anuncio solo puede ser de un juego
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    // Un anuncio puede como mucho tener una subasta
    public function auctionBid()
    {
        return $this->hasOne(AuctionBid::class, 'game_ad_id');
    }

    // Un anuncio puede tener varias Reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Un anuncio puede tener hasta 1 OrderItem
    public function orderitems()
    {
        return $this->hasOne(OrderItem::class);
    }

    // Un anuncio puede tener varios reportes
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    // Un anuncio tiene un usuario que lo publica
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isAuction(): bool
    {
        return $this->type === 'AUCTION';
    }

    public function markAsSold(): void
    {
        $this->update(['status' => 'SOLD']);
    }
}
