<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    const COUPON_CODE     = 'GAMELINK20';
    const COUPON_DISCOUNT = 0.20;

    public function __construct(private CartService $cartService) {}

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

        $items         = $order ? $order->orderItems : collect();
        $coupon        = session('coupon');
        $needsShipping = $items->contains(fn($item) => $item->gameAd?->format === 'PHYSICAL');

        return view('cart.index', compact('order', 'items', 'coupon', 'needsShipping'));
    }

    /**
     * Elimina un item del carrito.
     */
    public function remove(OrderItem $orderItem)
    {
        abort_if($orderItem->order->user_id !== Auth::id(), 403);

        $this->cartService->removeItem($orderItem);

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

        $this->cartService->addItem(Auth::id(), $request->game_ad_id);

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
        $order = Order::with('orderItems.gameAd')
            ->where('user_id', Auth::id())
            ->where('status', OrderStatus::PENDING)
            ->first();

        $needsShipping = $order && $order->orderItems->contains(
            fn($item) => $item->gameAd?->format === 'PHYSICAL'
        );

        $rules = [
            'email'       => 'required|email',
            'card_number' => 'required',
            'exp_date'    => 'required',
        ];

        if ($needsShipping) {
            $rules['shipping_name']    = 'required|string|max:255';
            $rules['shipping_address'] = 'required|string|max:255';
        }

        $request->validate($rules, [
            'email.required'            => 'El correo electrónico es obligatorio.',
            'email.email'               => 'Introduce un correo electrónico válido.',
            'card_number.required'      => 'El número de tarjeta es obligatorio.',
            'exp_date.required'         => 'La fecha de expiración es obligatoria.',
            'shipping_name.required'    => 'El nombre de entrega es obligatorio.',
            'shipping_address.required' => 'La dirección de entrega es obligatoria.',
        ]);

        if (!$order || $order->orderItems->isEmpty()) {
            return back()->withErrors(['cart' => 'El carrito está vacío o no se encontró la orden.']);
        }

        $this->cartService->checkout($order, session('coupon'));

        session()->forget('coupon');

        return redirect()->route('orders.index')->with('success', '¡Pago realizado con éxito! Consulta tus claves digitales en Mis Pedidos.');
    }
}
