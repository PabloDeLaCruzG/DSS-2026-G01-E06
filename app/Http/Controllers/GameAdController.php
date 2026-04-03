<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameAdController extends Controller
{
    public function create()
    {
        return view('gameAds.sell');
    }
}