<?php

// Fichero: app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'wallet_balance',
        'reputation',
        'is_banned',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELACIONES DEL UML ---

    // Un usuario puede tener un perfil profesional (o no)
    public function professionalProfile()
    {
        return $this->hasOne(ProfessionalProfile::class);
    }

    // Un usuario puede tener muchas direcciones
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // Un usuario (vendedor) puede tener muchos anuncios
    public function gameAds()
    {
        return $this->hasMany(GameAd::class);
    }

    // Un usuario (comprador) puede tener muchos pedidos
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Un usuario puede hacer muchas pujas
    public function auctionBids()
    {
        return $this->hasMany(AuctionBid::class);
    }

    // Un usuario puede escribir muchas reseñas
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Un usuario puede crear muchos reportes
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
