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

    protected $fillable = [
        'user_id',      // 🔥 AÑADIDO
        'game_id',      // 🔥 AÑADIDO
        'price',
        'description',
        'condition',
        'format',
        'platforms',
        'digital_key',
        'images',
        'status',
        'quantity'
    ];

    protected function casts(): array
    {
        return [
            'platforms' => 'array',
            'images' => 'array',
        ];
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderitems()
    {
        return $this->hasOne(OrderItem::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsSold(): void
    {
        $this->update(['status' => 'SOLD']);
    }
}