<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\GameAd;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Muestra el carrito del usuario autenticado.
     * Busca o crea un Order PENDING para el usuario.
     */
    public function index()
    {
        $order = Order::with(['orderItems.gameAd.game'])
            ->where('user_id', Auth::id())
            ->where('status', OrderStatus::PENDING)
            ->latest()
            ->first();

        $items = $order ? $order->orderItems : collect();

        return view('cart.index', compact('order', 'items'));
    }

    /**
     * Elimina un item del carrito.
     */
    public function remove(OrderItem $orderItem)
    {
        // Seguridad: solo el dueño del pedido puede borrar sus items
        abort_if($orderItem->order->user_id !== Auth::id(), 403);

        $order = $orderItem->order;
        $orderItem->delete();

        // Recalcular total del pedido
        $order->total_amount = $order->orderItems()->sum('unit_price');
        $order->save();

        return back()->with('success', 'Artículo eliminado del carrito.');
    }

    /**
     * Añade un item al carrito.
     */
    public function add(Request $request)
    {
        $request->validate([
            'game_ad_id' => 'required|exists:game_ads,id',
        ]);

        $ad = GameAd::findOrFail($request->game_ad_id);

        // 1. Obtener o crear el pedido PENDING
        $order = Order::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'status' => OrderStatus::PENDING,
            ],
            [
                'total_amount' => 0,
            ]
        );

        // 2. Crear el OrderItem
        // Nota: seller_fee es el 2% según el diseño
        $sellerFee = $ad->price * 0.02;
        
        OrderItem::create([
            'order_id' => $order->id,
            'game_ad_id' => $ad->id,
            'unit_price' => $ad->price,
            'seller_fee' => $sellerFee,
            'net_income' => $ad->price - $sellerFee,
            'shipping_status' => \App\Enums\ShippingStatus::PENDING,
        ]);

        // 3. Recalcular total del pedido
        $order->total_amount = $order->orderItems()->sum('unit_price');
        $order->save();

        return redirect()->route('cart.index')->with('success', '¡Artículo añadido al carrito!');
    }

    /**
     * Procesa el pago simulado.
     */
    public function checkout(Request $request)
    {
        // Puedes añadir validación básica aquí si deseas (aunque el JS ya lo frena)
        $request->validate([
            'email' => 'required|email',
            'card_number' => 'required',
            'exp_date' => 'required',
        ]);

        $order = Order::where('user_id', Auth::id())
            ->where('status', OrderStatus::PENDING)
            ->first();

        if (!$order || $order->orderItems->isEmpty()) {
            return back()->withErrors(['cart' => 'El carrito está vacío o no se encontró la orden.']);
        }

        // Marcar la orden como pagada
        $order->status = OrderStatus::PAID; // Ensure OrderStatus::PAID exists or change to 'PAID' if it's a string.
        $order->save();

        // Podrías vaciar el carrito o hacer algo más complejo aquí.
        return redirect()->route('cart.index')->with('success', '¡Pago realizado con éxito! Tu orden ha sido completada.');
    }
}
