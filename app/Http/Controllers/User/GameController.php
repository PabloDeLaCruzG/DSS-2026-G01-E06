<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        // SOLO juegos del usuario logueado
        $games = Game::where('user_id', Auth::id())
            ->paginate(4); 

        return view('user.games.index', compact('games'));
    }
}
