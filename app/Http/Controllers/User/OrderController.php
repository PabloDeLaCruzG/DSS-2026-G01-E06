<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems.gameAd.game'])
            ->where('user_id', Auth::id())
            ->where('status', '!=', OrderStatus::PENDING)
            ->latest()
            ->get();

        return view('user.orders.index', compact('orders'));
    }
}
