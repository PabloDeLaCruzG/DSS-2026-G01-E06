<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Admin extends User
{
    // Esto le dice a Laravel que este modelo sigue usando la tabla 'users'
    protected $table = 'users';

    // Filtro que se aplica a todas las consultas de Admin
    protected static function booted()
    {
        static::addGlobalScope('admin', function (Builder $builder) {
            $builder->where('role', 'admin');
        });
    }

    public function banUser(User $user)
    {
        if (!$user->isAdmin()) {
            $user->is_banned = true;
            $user->save();
        }
    }
}
