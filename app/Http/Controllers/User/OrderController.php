<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return view('user.orders.index');
    }
}
