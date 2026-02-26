<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus;

class Order extends Model
{
    use HasFactory;

    
    protected $fillable = [ //id y fecha de creación se gestionan solos por laravell
        'total_amount',
        'status',
    ];

   
    protected $casts=[
      'total_amount'=> 'float',
      'status' => OrderStatus::class,
    ];
  

    public function user()  //cada pedido pertenece a un usuario
    {
        return $this->belongsTo(User::class);
    }


    public function orderItems()  //un order tiene uno o mas orderitem
    {
        return $this->hasMany(OrderItem::class);
    }
}