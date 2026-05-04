@extends('layouts.app')

@section('title', 'Carrito y Checkout – GameLink')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">

    {{-- Breadcrumbs --}}
    <div class="text-sm font-medium mb-3">
        <a href="{{ route('home') }}" class="text-text-muted hover:text-text-main transition-colors">Inicio</a>
        <span class="text-text-muted mx-2">&gt;</span>
        <span class="text-primary">Carrito y Checkout</span>
    </div>

    {{-- Cabecera --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-main tracking-tight">Tu Carrito de Compra</h1>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-primary/10 border border-primary/30 text-primary rounded-lg px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($items->isEmpty())
        {{-- Estado vacío --}}
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 bg-surface rounded-full flex items-center justify-center mb-6 border border-border">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-text-main mb-2">Tu carrito está vacío</h2>
            <p class="text-text-muted mb-8 max-w-sm">Explora el Marketplace y añade los artículos que te interesen.</p>
            <a href="{{ route('home') }}" class="bg-primary hover:bg-primary-hover text-white font-semibold px-6 py-2.5 rounded-lg transition-colors">
                Ir al Marketplace
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
            {{-- Columna Izquierda (Artículos + Formularios) --}}
            <div class="lg:col-span-8 space-y-4">

                {{-- Artículos del carrito --}}
                @foreach ($items as $item)
                    @php
                        $ad = $item->gameAd;
                        $game = $ad?->game;
                        $image = $game?->cover_image ?? null;
                    @endphp
                    <div class="bg-surface border border-border rounded-xl p-5 flex gap-5 relative group">
                        {{-- Eliminar --}}
                        <form action="{{ route('cart.remove', $item) }}" method="POST" class="absolute top-5 right-5"
                              data-confirm="¿Eliminar este artículo del carrito?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-text-muted hover:text-red-400 transition-colors" title="Eliminar del carrito">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>

                        {{-- Imagen --}}
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-lg bg-background border border-border overflow-hidden flex-shrink-0 flex items-center justify-center">
                            @if ($image)
                                <img src="{{ $image }}" alt="{{ $game?->title }}" class="w-full h-full object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <rect x="2" y="6" width="20" height="12" rx="2"/>
                                    <line x1="6" y1="12" x2="10" y2="12"/>
                                    <line x1="8" y1="10" x2="8" y2="14"/>
                                    <line x1="15" y1="13" x2="15.01" y2="13"/>
                                    <line x1="18" y1="11" x2="18.01" y2="11"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0 pr-8 flex flex-col justify-between py-1">
                            <div>
                                <h3 class="font-bold text-text-main text-lg">{{ $game?->title ?? 'Juego desconocido' }}</h3>
                                @if ($ad?->user)
                                    <p class="text-sm text-primary font-medium mt-1">
                                        Vendedor: {{ $ad->user->name }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-end justify-between mt-4">
                                {{-- Controles Cantidad --}}
                                <div class="flex items-center gap-4 text-text-main font-bold">
                                    <button class="text-text-muted hover:text-text-main transition-colors">-</button>
                                    <span>1</span>
                                    <button class="text-text-muted hover:text-text-main transition-colors">+</button>
                                </div>
                                {{-- Precio --}}
                                <div class="text-2xl font-black text-text-main mt-2 sm:mt-0">
                                    ${{ number_format($item->unit_price, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <form action="{{ route('cart.checkout') }}" method="POST" id="checkout-form">
                    @csrf
                    {{-- Datos de Envío --}}
                    <div class="bg-surface border border-border rounded-xl p-6 flex flex-col md:flex-row gap-6 mt-6 items-start">
                        <div class="md:w-1/4 flex items-center gap-3 text-text-main font-bold md:pt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <span class="leading-tight">Datos de<br>Envío</span>
                        </div>
                        <div class="flex-1 space-y-4 w-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-text-muted mb-1.5 ml-1">Nombre Completo</label>
                                    <input type="text" placeholder="Ej. Juan Pérez" required class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-text-muted mb-1.5 ml-1">Email</label>
                                    <input type="email" id="email-input" name="email" required placeholder="juan@ejemplo.com" class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition-colors">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text-muted mb-1.5 ml-1">Dirección de Entrega</label>
                                <input type="text" placeholder="Calle, Número, Depto" required class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition-colors">
                            </div>
                        </div>
                    </div>

                    {{-- Método de Pago --}}
                    <div class="bg-surface border border-border rounded-xl p-6 flex flex-col md:flex-row gap-6 items-start mt-4">
                        <div class="md:w-1/4 flex items-center gap-3 text-text-main font-bold md:pt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                                <path d="M7 15h2M11 15h2" />
                            </svg>
                            <span class="leading-tight">Método de<br>Pago</span>
                        </div>
                        <div class="flex-1 space-y-4 w-full">
                            <div>
                                <label class="block text-xs font-medium text-text-muted mb-1.5 ml-1">Número de Tarjeta</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="currentColor">
                                            <rect x="2" y="5" width="20" height="14" rx="2" />
                                            <path fill="#fff" opacity="0.2" d="M2 10h20v2H2z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="card-input" name="card_number" required placeholder="0000 0000 0000 0000" class="w-full bg-background border border-border rounded-lg pl-11 pr-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition-colors tracking-widest placeholder-text-muted/40">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-text-muted mb-1.5 ml-1">Fecha Expiración</label>
                                    <input type="text" id="exp-input" name="exp_date" required placeholder="MM/YY" class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition-colors tracking-wider">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-text-muted mb-1.5 ml-1">CVV</label>
                                    <input type="text" id="cvv-input" name="cvv" required placeholder="123" class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition-colors tracking-widest">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

            {{-- Columna Derecha (Resumen) --}}
            <div class="lg:col-span-4 space-y-4">
                {{-- Resumen de Pedido --}}
                <div class="bg-surface border border-border rounded-xl p-6 sticky top-20">
                    <h2 class="text-base font-bold text-text-main mb-6">Resumen de Pedido</h2>

                    @php
                        $subtotal  = $items->sum('unit_price');
                        $comision  = $items->sum('seller_fee') ?: ($subtotal * 0.02);
                        $baseTotal = $subtotal + $comision;
                        $descuento = $coupon ? round($baseTotal * $coupon['rate'], 2) : 0;
                        $totalF    = $baseTotal - $descuento;
                    @endphp

                    <div class="space-y-4 text-sm font-medium">
                        <div class="flex justify-between text-text-muted">
                            <span>Subtotal ({{ $items->count() }} productos)</span>
                            <span class="text-text-main">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-text-muted">
                            <span>Gastos de envío</span>
                            <span class="text-green-500">Gratis</span>
                        </div>
                        <div class="flex justify-between text-text-muted">
                            <span>Comisión GameLink (2%)</span>
                            <span class="text-text-main">${{ number_format($comision, 2) }}</span>
                        </div>
                        @if($coupon)
                        <div class="flex justify-between text-green-400 font-semibold">
                            <span>Descuento ({{ $coupon['code'] }} −{{ $coupon['rate'] * 100 }}%)</span>
                            <span>−${{ number_format($descuento, 2) }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="border-t border-border/70 border-dashed my-6"></div>

                    <div class="mb-5">
                        <div class="flex justify-between items-end">
                            <span class="text-base font-bold text-text-main pb-1">Total Final</span>
                            <div class="text-right">
                                <span class="text-[32px] font-black text-primary leading-none">${{ number_format($totalF, 2) }}</span>
                                <span class="text-[10px] text-text-muted block mt-1 uppercase tracking-wide">IVA incluido</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" form="checkout-form" class="w-full bg-primary hover:bg-primary-hover text-white font-bold py-3.5 rounded-lg flex items-center justify-center gap-2 transition-colors mb-5 shadow-lg shadow-primary/20">
                        Proceder al Pago
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                    <div class="flex items-center justify-center gap-3 text-xs text-text-muted px-1 mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="leading-tight">Compra protegida por GameLink Protection. <br> Garantía de 30 días en claves digitales.</span>
                    </div>
                </div>

                {{-- Código de descuento --}}
                <div class="bg-surface border border-border rounded-xl p-5">
                    <div class="flex items-center gap-2 text-primary font-semibold mb-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                        <span>¿Tienes un código de descuento?</span>
                    </div>

                    {{-- Banner con el código visible --}}
                    <div class="flex items-center gap-3 bg-primary/10 border border-primary/20 rounded-lg px-4 py-2.5 mb-3">
                        <svg class="w-4 h-4 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <p class="text-xs text-text-muted flex-1">Usa el código <span class="font-mono font-bold text-primary tracking-wider">GAMELINK20</span> y obtén un <strong class="text-primary">20% de descuento</strong> en tu compra.</p>
                    </div>

                    {{-- Feedback --}}
                    @if(session('coupon_success'))
                        <p class="text-xs text-green-400 mb-2">✔ {{ session('coupon_success') }}</p>
                    @endif
                    @if(session('coupon_error'))
                        <p class="text-xs text-red-400 mb-2">✖ {{ session('coupon_error') }}</p>
                    @endif

                    @if($coupon)
                        {{-- Cupón ya aplicado --}}
                        <div class="flex items-center justify-between bg-green-500/10 border border-green-500/20 rounded-lg px-4 py-2.5">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm font-mono font-bold text-green-400">{{ $coupon['code'] }}</span>
                                <span class="text-xs text-green-400">— {{ $coupon['rate'] * 100 }}% aplicado</span>
                            </div>
                            <form method="POST" action="{{ route('cart.coupon.remove') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-text-muted hover:text-red-400 transition-colors text-xs">✕ Quitar</button>
                            </form>
                        </div>
                    @else
                        {{-- Formulario para aplicar cupón --}}
                        <form method="POST" action="{{ route('cart.coupon') }}" class="flex gap-3">
                            @csrf
                            <input type="text" name="coupon_code" placeholder="Código" value="{{ old('coupon_code') }}"
                                   class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main focus:outline-none focus:border-primary transition-colors placeholder-text-muted/60 uppercase tracking-wider">
                            <button type="submit" class="bg-primary hover:bg-primary-hover text-white font-bold px-5 py-2.5 rounded-lg text-sm transition-colors flex-shrink-0 shadow-md shadow-primary/20">
                                Aplicar
                            </button>
                        </form>
                    @endif
                </div>

            </div>

        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    if (!form) return;

    const emailInput = document.getElementById('email-input');
    const cardInput = document.getElementById('card-input');
    const expInput = document.getElementById('exp-input');
    const cvvInput = document.getElementById('cvv-input');

    // ENMASCARAR TARJETA
    cardInput.addEventListener('input', function(e) {
        let val = e.target.value.replace(/\D/g, ''); 
        let formatted = '';
        for (let i = 0; i < val.length; i++) {
            if (i > 0 && i % 4 === 0) formatted += ' ';
            formatted += val[i];
        }
        e.target.value = formatted.substring(0, 19);
    });

    // ENMASCARAR EXPIRACIÓN
    expInput.addEventListener('input', function(e) {
        let val = e.target.value.replace(/\D/g, '');
        if (val.length > 2) {
            e.target.value = val.substring(0, 2) + '/' + val.substring(2, 4);
        } else {
            e.target.value = val;
        }
    });

    // ENMASCARAR CVV
    cvvInput.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
    });

    form.addEventListener('submit', function(e) {
        // Validación de email con regex
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            e.preventDefault();
            alert('Por favor, ingresa un correo electrónico válido.');
            emailInput.focus();
            return;
        }

        // Validar tarjeta (solo longitud, demo)
        let cardRaw = cardInput.value.replace(/\s/g, '');
        if (cardRaw.length < 15) {
            e.preventDefault();
            alert('Por favor, ingresa una tarjeta de crédito válida de 16 dígitos.');
            cardInput.focus();
            return;
        }

        // Validar expiración
        if (expInput.value.length < 5) {
            e.preventDefault();
            alert('Por favor, ingresa una fecha de expiración válida (MM/AA).');
            expInput.focus();
            return;
        }
    });
});
</script>

@endsection
