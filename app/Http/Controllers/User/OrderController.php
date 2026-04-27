<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderItems.gameAd.game')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }
}
