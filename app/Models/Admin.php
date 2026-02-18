<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'department',
        'user_id',
    ];

    // Relación: Un admin pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Método banUser
    public function banUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->is_banned = true;
            $user->save();
        }
    }
}