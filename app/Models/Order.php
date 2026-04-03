<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [ // id y fecha de creación se gestionan solos por laravell
        'user_id',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'float',
        'status' => OrderStatus::class,
    ];

    public function user()  // cada pedido pertenece a un usuario
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()  // un order tiene uno o mas orderitem
    {
        return $this->hasMany(OrderItem::class);
    }
}
