<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ShippingStatus;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Atributos definidos en el UML
     * (id y timestamps los gestiona Laravel)
     */
    protected $fillable = [
        'unit_price',
        'seller_fee',
        'net_income',
        'shipping_status',
        'tracking_number',
        'order_id',
        'game_ad_id',
    ];

    /**
     * Casts coherentes con el dominio
     */
    protected $casts = [
        'unit_price' => 'float',
        'seller_fee' => 'float',
        'net_income' => 'float',
        'shipping_status' => ShippingStatus::class,
    ];

    // --- RELACIONES UML ---

    /**
     * Un OrderItem pertenece a un Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Un OrderItem se asocia a un GameAd (flecha UML)
     */
    public function gameAd()
    {
        return $this->belongsTo(GameAd::class);
    }
}