<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'wallet_balance',
        'reputation',
        'is_banned',
    ];

    /**
     * Los atributos que deben ocultarse en la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Obtener los atributos que deben castearse.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_banned' => 'boolean',
            'isAdmin' => 'boolean',
            'isProfessional' => 'boolean',
            'canSell' => 'boolean',
            'wallet_balance' => 'float',
            'reputation' => 'float',
        ];
    }
    // Relación: Un usuario puede ser admin
    public function admin()
    {
        return $this->hasOne(Admin::class);
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

    /**
     * Verifica si el usuario tiene el rol de Administrador.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verifica si el usuario tiene un perfil profesional verificado.
     */
    public function isProfessional(): bool
    {
        // La relación 'professionalProfile' debe existir Y estar verificada
        return $this->professionalProfile && $this->professionalProfile->is_verified;
    }

    /**
     * Verifica si el usuario tiene permiso para vender (no está baneado).
     */
    public function canSell(): bool
    {
        return !$this->is_banned;
    }
}
