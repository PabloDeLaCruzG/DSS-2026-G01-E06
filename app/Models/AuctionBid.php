<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionBid extends Model{

    use HasFactory;

    protected $fillable = [
        'game_id',
        'amount',
        'bid_time'

    ];

    // Si existe una subasta, obligatoriamente será de un producto
    public function gamead(){
        return $this->belongsTo(GameAd::class, 'game_ad_id');
    }

    // En una subasta puedednn participar varios usuarios
    public function users(){
        return $this->belongsToMany(User::class, 'auction_bid_user');
    }
}