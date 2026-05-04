<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\GameAd;
use App\Enums\OrderStatus;
use App\Notifications\OrderConfirmedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    const COUPON_CODE     = 'GAMELINK20';
    const COUPON_DISCOUNT = 0.20;

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

        $items  = $order ? $order->orderItems : collect();
        $coupon = session('coupon');

        return view('cart.index', compact('order', 'items', 'coupon'));
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
            'shipping_status' => $ad->format === \App\Models\GameAd::FORMAT_DIGITAL_KEY
                ? \App\Enums\ShippingStatus::INSTANT
                : \App\Enums\ShippingStatus::PENDING,
        ]);

        // 3. Recalcular total del pedido
        $order->total_amount = $order->orderItems()->sum('unit_price');
        $order->save();

        return redirect()->route('cart.index')->with('success', '¡Artículo añadido al carrito!');
    }

    /**
     * Aplica un cupón de descuento y lo guarda en sesión.
     */
    public function applyCoupon(Request $request)
    {
        $code = strtoupper(trim($request->input('coupon_code', '')));

        if ($code === self::COUPON_CODE) {
            session(['coupon' => ['code' => self::COUPON_CODE, 'rate' => self::COUPON_DISCOUNT]]);
            return back()->with('coupon_success', '¡Código aplicado! Tienes un 20% de descuento.');
        }

        return back()->with('coupon_error', 'Código de descuento inválido.');
    }

    /**
     * Elimina el cupón de descuento de la sesión.
     */
    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('coupon_success', 'Código de descuento eliminado.');
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
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Introduce un correo electrónico válido.',
            'card_number.required' => 'El número de tarjeta es obligatorio.',
            'exp_date.required' => 'La fecha de expiración es obligatoria.',
        ]);

        $order = Order::where('user_id', Auth::id())
            ->where('status', OrderStatus::PENDING)
            ->first();

        if (!$order || $order->orderItems->isEmpty()) {
            return back()->withErrors(['cart' => 'El carrito está vacío o no se encontró la orden.']);
        }

        // Aplicar cupón de descuento si existe en sesión
        if ($coupon = session('coupon')) {
            $order->total_amount = round($order->total_amount * (1 - $coupon['rate']), 2);
        }

        // Marcar la orden como pagada
        $order->status = OrderStatus::PAID;
        $order->save();

        // Decrementar stock de cada anuncio; marcar como SOLD si quantity llega a 0
        foreach ($order->orderItems as $item) {
            $ad = $item->gameAd;
            $ad->decrement('quantity');
            if ($ad->fresh()->quantity <= 0) {
                $ad->markAsSold();
            }
        }

        session()->forget('coupon');

        $order->user->notify(new OrderConfirmedNotification($order));

        return redirect()->route('orders.index')->with('success', '¡Pago realizado con éxito! Consulta tus claves digitales en Mis Pedidos.');
    }
}
